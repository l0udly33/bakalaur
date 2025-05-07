<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class AdminApplicationController extends Controller
{
    public function view($userId)
    {
        $user = User::with('trainerApplication')->findOrFail($userId);

        if (!$user->trainerApplication) {
            return redirect()->back()->with('error', 'Naudotojas nepateikęs paraiškos.');
        }

        return view('admin.applications.view', compact('user'));
    }

    public function approve(User $user)
    {
        $user->update(['role' => 'trainer']);
        return back()->with('success', 'Paraiška patvirtinta.');
    }

    public function reject(User $user)
    {
        $user->trainerApplication()->delete();
        return back()->with('success', 'Paraiška atmesta.');
    }

    public function saveNotes(Request $request, User $user)
    {
        $request->validate([
            'notes' => 'nullable|string|max:2000',
        ]);

        $user->trainerApplication->admin_notes = $request->notes;
        $user->trainerApplication->save();

        return back()->with('success', 'Pastabos išsaugotos.');
    }
}
