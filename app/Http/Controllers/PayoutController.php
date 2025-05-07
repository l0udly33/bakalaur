<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PayoutRequest;

class PayoutController extends Controller
{
    public function form()
    {
        return view('trainer.payout');
    }

    public function submit(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|min:1',
            'paypal_email' => 'required|email'
        ]);

        $trainer = auth()->user();

       
        if ($request->amount > $trainer->balance) {
            return back()->withErrors(['amount' => 'Negalite išsiimti daugiau nei turite balanse.']);
        }

        \App\Models\PayoutRequest::create([
            'trainer_id' => $trainer->id,
            'amount' => $request->amount,
            'paypal_email' => $request->paypal_email,
        ]);

        return redirect()->route('trainer.payout.form')->with('success', 'Išmokėjimo prašymas pateiktas.');
    }
}
