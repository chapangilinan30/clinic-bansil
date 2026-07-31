<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\ServiceController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\Admin\AppealController as AdminAppealController; // <-- New Admin Appeal Controller
use App\Http\Controllers\Clerk\ClerkController;
use App\Http\Controllers\Patient\DashboardController as PatientDashboardController;
use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\Doctor\PrescriptionController;
use App\Http\Controllers\Doctor\MedicineController;
use App\Http\Controllers\DoctorPatientController;
use App\Http\Controllers\DoctorScheduleController;
use App\Http\Controllers\DoctorAppointmentController;
use App\Models\Appointment;
use App\Http\Controllers\DoctorUnavailabilityController;

/*
|--------------------------------------------------------------------------
| Public Route
|--------------------------------------------------------------------------
*/
Route::get('/', function () {
    return view('welcome');
});

/*
|--------------------------------------------------------------------------
| Authenticated Routes
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'verified'])->group(function () {

    // -------------------------------
    // SMART DASHBOARD REDIRECT
    // -------------------------------
    Route::get('/dashboard', function () {
        $user = Auth::user();

        if ($user->role === 'admin') return redirect()->route('admin.dashboard');
        if ($user->role === 'doctor') return redirect()->route('doctor.dashboard');
        if ($user->role === 'clerk') return redirect()->route('clerk.dashboard');
        if ($user->role === 'user' || $user->role === 'patient') return redirect()->route('patient.dashboard');

        $history = Appointment::where('user_id', $user->id)
                    ->whereIn('status', ['completed', 'no-show'])
                    ->orderBy('created_at', 'desc')
                    ->get();

        $upcoming = Appointment::where('user_id', $user->id)
                   ->whereIn('status', ['pending', 'booked', 'checked-in', 'in-session'])
                    ->orderBy('created_at', 'asc')
                    ->get();

        return view('dashboard', compact('history', 'upcoming'));
    })->name('dashboard');

    /*
    |--------------------------------------------------------------------------
    | PATIENT DASHBOARD & BOOKING
    |--------------------------------------------------------------------------
    |
    */
    Route::prefix('patient')
        ->name('patient.')
        ->middleware(['role:patient']) 
        ->group(function () {
            
            Route::patch('/appointments/{id}/cancel', [PatientDashboardController::class, 'cancelAppointment'])->name('appointments.cancel');
            Route::get('/dashboard', [PatientDashboardController::class, 'index'])->name('dashboard');
            Route::get('/appointments', [PatientDashboardController::class, 'appointments'])->name('appointments');
            Route::get('/history', [PatientDashboardController::class, 'history'])->name('history');

            // ACCOUNT REACTIVATION APPEAL SUBMISSION
            Route::post('/reactivation-request', [PatientDashboardController::class, 'submitReactivation'])->name('reactivation.submit');

            // BOOKING STEPS
            Route::get('/booking', [BookingController::class, 'stepDepartment'])->name('booking.step1');
            Route::get('/booking/doctor', [BookingController::class, 'stepDoctor'])->name('booking.step2');
            Route::get('/booking/schedule', [BookingController::class, 'stepSchedule'])->name('booking.step3');
            Route::get('/booking/patient-info', [BookingController::class, 'stepPatientInfo'])->name('booking.step4');
            Route::post('/booking/confirm', [BookingController::class, 'confirm'])->name('booking.confirm');
            Route::post('/booking/store', [BookingController::class, 'store'])->name('booking.store');

            // AJAX HELPERS
            Route::get('/get-doctors/{departmentId}', [BookingController::class, 'getDoctors']);
            Route::get('/get-dates/{doctorId}', [BookingController::class, 'getDates']);
            Route::get('/get-times/{doctorId}/{date}', [BookingController::class, 'getTimes']);
        });

    /*
    |--------------------------------------------------------------------------
    | CLERK DASHBOARD
    |--------------------------------------------------------------------------
    |
    */
    Route::prefix('clerk')->name('clerk.')->middleware(['role:clerk'])->group(function () {
        Route::get('/dashboard', [ClerkController::class, 'index'])->name('dashboard');
        Route::patch('appointments/{id}/status', [ClerkController::class, 'updateStatus'])->name('appointments.update-status');
        Route::patch('/complete/{id}', [ClerkController::class, 'complete'])->name('appointments.complete');
        Route::get('/search-patients', [ClerkController::class, 'searchPatients'])->name('search_patients');
        Route::post('/appointments/walkin', [ClerkController::class, 'walkin'])->name('appointments.walkin');
        Route::post('/doctors/{id}/toggle-availability', [ClerkController::class, 'toggleAvailability'])->name('doctors.toggle-availability');
        
        Route::get('/doctor-schedules', [App\Http\Controllers\Clerk\DoctorScheduleController::class, 'index'])->name('doctor-schedules.index');
        Route::get('/doctor-schedules/{doctor}/edit', [App\Http\Controllers\Clerk\DoctorScheduleController::class, 'edit'])->name('doctor-schedules.edit');
        Route::post('/doctor-schedules', [App\Http\Controllers\Clerk\DoctorScheduleController::class, 'store'])->name('doctor-schedules.store');
        Route::post('/doctor-schedules/unavailability', [App\Http\Controllers\Clerk\DoctorScheduleController::class, 'storeUnavailability'])->name('doctor-schedules.unavailability.store');
        Route::delete('/doctor-schedules/unavailability/{id}', [App\Http\Controllers\Clerk\DoctorScheduleController::class, 'destroyUnavailability'])->name('doctor-schedules.unavailability.destroy');
        
        Route::get('/services/{service}/doctors', [ClerkController::class, 'getDoctorsByService'])->name('services.doctors');
        Route::get('/appointments/cancel', [ClerkController::class, 'cancelView'])->name('appointments.cancel.view');
        Route::post('/appointments/cancel', [ClerkController::class, 'cancelAppointments'])->name('appointments.cancel');
    });

    /*
    |--------------------------------------------------------------------------
    | DOCTOR ROUTES
    |--------------------------------------------------------------------------
    |
    */
    Route::prefix('doctor')
        ->name('doctor.')
        ->middleware(['role:doctor']) 
        ->group(function () {

            /* ===============================
               DOCTOR DASHBOARD
            =============================== */
            Route::get('/dashboard', function () {
                $doctorId = auth()->id();
                $today = now()->toDateString();

                $totalAppointmentsToday = Appointment::where('doctor_id', $doctorId)->whereDate('appointment_date', $today)->count();
                $patientsInQueue = Appointment::where('doctor_id', $doctorId)->whereIn('status', ['pending', 'checked-in', 'in_progress'])->count();
                $completedToday = Appointment::where('doctor_id', $doctorId)->where('status', 'completed')->whereDate('appointment_date', $today)->count();
                $ongoingConsultations = Appointment::where('doctor_id', $doctorId)->where('status', 'in_progress')->count();
                $todaysAppointments = Appointment::where('doctor_id', $doctorId)->whereDate('appointment_date', $today)->orderBy('appointment_time')->get();

                return view('doctor.dashboard', compact(
                    'totalAppointmentsToday', 'patientsInQueue', 'completedToday', 'ongoingConsultations', 'todaysAppointments'
                ));
            })->name('dashboard');

            /* ===============================
               DOCTOR APPOINTMENTS
            =============================== */
            Route::get('/appointments/{appointment}', [DoctorAppointmentController::class, 'show'])->name('appointment.show');
            Route::post('/appointments/{appointment}', [DoctorAppointmentController::class, 'update'])->name('appointment.update');

            /* ===============================
               DOCTOR SCHEDULES (UPDATED WORKFLOW)
            =============================== */
            Route::get('/schedules/cancel-management', [DoctorScheduleController::class, 'cancelPanel'])->name('schedules.cancel-panel');
            Route::get('/schedules/{id}/cancel', [DoctorScheduleController::class, 'showCancel'])->name('schedules.show-cancel');
            
            // Selective multi-checkbox processing route
            Route::post('/schedules/cancel-selected', [DoctorScheduleController::class, 'cancelSelected'])->name('schedules.cancel-selected');
            
            Route::resource('schedules', DoctorScheduleController::class)->only(['index', 'create', 'store', 'destroy']);

            /* ===============================
               DOCTOR UNAVAILABILITY (LEAVE)
            =============================== */
            Route::get('unavailability', [DoctorUnavailabilityController::class, 'index'])->name('unavailability.index');
            Route::post('unavailability', [DoctorUnavailabilityController::class, 'store'])->name('unavailability.store');
            Route::delete('unavailability/{id}', [DoctorUnavailabilityController::class, 'destroy'])->name('unavailability.destroy');

            /* ===============================
               DOCTOR CALENDAR
            =============================== */
            Route::get('calendar', [DoctorScheduleController::class, 'calendar'])->name('calendar');

            /* ===============================
               MEDICINES
            =============================== */
            Route::resource('medicines', MedicineController::class)->only(['index', 'store', 'destroy']);

            /* ===============================
               PRESCRIPTIONS
            =============================== */
            Route::get('prescriptions/create', [PrescriptionController::class, 'create'])->name('prescriptions.create');
            Route::get('prescriptions/create/{patient}', [PrescriptionController::class, 'createForPatient'])->name('prescriptions.create.patient');
            Route::post('prescriptions', [PrescriptionController::class, 'store'])->name('prescriptions.store');
            Route::post('prescriptions/dashboard', [PrescriptionController::class, 'storeDashboard'])->name('prescriptions.store_dashboard');
            Route::get('prescriptions/{prescription}', [PrescriptionController::class, 'show'])->name('prescriptions.show');
            Route::get('prescriptions/{prescription}/print', [PrescriptionController::class, 'print'])->name('prescriptions.print');

            /* ===============================
               DOCTOR PATIENTS
            =============================== */
            Route::resource('patients', DoctorPatientController::class);
        });

    /*
    |--------------------------------------------------------------------------
    | ADMIN DASHBOARD
    |--------------------------------------------------------------------------
    */
    Route::prefix('admin')->name('admin.')->middleware(['role:admin,doctor,clerk,staff'])->group(function () {
        Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
        Route::resource('users', UserController::class);
        Route::resource('services', ServiceController::class);
        Route::resource('reports', ReportController::class);

        // Disciplinary Appeals Workspace Management (Moved to Admin Group)
        Route::get('/appeals', [AdminAppealController::class, 'index'])->name('appeals.index');
        Route::post('/appeals/{id}/approve', [AdminAppealController::class, 'approve'])->name('appeals.approve');
        Route::post('/appeals/{id}/deny', [AdminAppealController::class, 'deny'])->name('appeals.deny');
    });
    /*
    |--------------------------------------------------------------------------
    | PROFILE
    |--------------------------------------------------------------------------
    */
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

/*
|--------------------------------------------------------------------------
| API / AJAX
|--------------------------------------------------------------------------
*/
Route::get('/api/doctors/{department}', function ($departmentId) {
    return \App\Models\User::where('department_id', $departmentId)
        ->where('role', 'doctor')
        ->get();
});

require __DIR__.'/auth.php';