<input type="radio" name="payment_method" value="Cash" required>
Cash

<input type="radio" name="payment_method" value="GCash">
GCash

<input type="radio" name="payment_method" value="Maya">
Maya
<input type="hidden" name="department" value="{{ $selectedDepartment }}">
<input type="hidden" name="doctor_id" value="{{ $selectedDoctor->id }}">
<input type="hidden" name="selected_date" value="{{ $selected_date }}">
<input type="hidden" name="selected_time" value="{{ $selected_time }}">
<input type="hidden" name="patient_name" value="{{ $patient_name }}">
<input type="hidden" name="patient_email" value="{{ $patient_email }}">
<input type="hidden" name="patient_phone" value="{{ $patient_phone }}">
<input type="hidden" name="notes" value="{{ $notes }}">



