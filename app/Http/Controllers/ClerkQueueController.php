<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PatientQueue;
use Illuminate\Support\Facades\Auth;

class ClerkQueueController extends Controller
{
    public function index() {
        $queue = PatientQueue::with('patient')->orderBy('queue_number')->get();
        return view('clerk.dashboard', compact('queue'));
    }

    public function update(Request $request, $id) {
        $queueItem = PatientQueue::findOrFail($id);
        $queueItem->status = $request->status;
        $queueItem->save();

        // TODO: Trigger notification here
        return redirect()->back()->with('success', 'Queue updated.');
    }
}