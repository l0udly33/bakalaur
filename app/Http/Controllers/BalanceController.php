<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BalanceController extends Controller
{
    public function showAddForm()
    {
        return view('balance.add');
    }

    public function addBalance(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|min:1',
        ]);

        $user = Auth::user();
        $user->balance += $request->amount;
        $user->save();

        return response()->json(['success' => true, 'new_balance' => $user->balance]);
    }

    public function showWithdrawForm()
    {
        return view('balance.withdraw');
    }

    public function withdrawBalance(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|min:1',
        ]);

        $user = Auth::user();
        $amount = $request->amount;

        if ($amount > $user->balance) {
            return response()->json(['success' => false, 'error' => 'Nepakanka lėšų.']);
        }

        
        $user->balance -= $amount;
        $user->save();

        return response()->json(['success' => true, 'new_balance' => $user->balance]);
    }
}
