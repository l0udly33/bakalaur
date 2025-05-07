<?php

namespace App\Http\Controllers;

use App\Models\TrainerApplication;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class TrainerApplicationController extends Controller
{
    public function showForm()
    {
        if (auth()->user()->trainerApplication()->exists()) {
            return redirect()->route('user.statistics')->with('error', 'Jūs esate pateikę paraišką.');
        }

        return view('trainer.apply');
    }

    public function submit(Request $request)
    {
        $request->validate([
            'full_name' => 'required|string|max:255',
            'rank' => 'required|string|max:50',
            'age' => 'required|integer|min:16|max:100',
            'experience' => 'required|string',
            'motivation' => 'required|string',
        ]);

        TrainerApplication::create([
            'user_id' => auth()->id(),
            'full_name' => $request->full_name,
            'rank' => $request->rank,
            'age' => $request->age,
            'experience' => $request->experience,
            'motivation' => $request->motivation,
        ]);

        return redirect()->route('user.statistics')->with('success', 'Prašymas išsiųstas.');
    }
}
