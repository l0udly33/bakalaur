<?php

namespace App\Http\Controllers;

use App\Models\PayoutRequest;
use App\Models\User;
use Illuminate\Http\Request;

class AdminPayoutController extends Controller
{
    public function index(Request $request)
    {
        $query = PayoutRequest::with('trainer');

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('from')) {
            $query->whereDate('created_at', '>=', $request->from);
        }

        if ($request->filled('to')) {
            $query->whereDate('created_at', '<=', $request->to);
        }

        $payouts = $query->latest()->get();

        return view('admin.payouts.index', compact('payouts'));
    }

    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:pending,completed,canceled',
        ]);

        $payout = PayoutRequest::findOrFail($id);


        if ($request->status === 'completed' && $payout->status !== 'completed') {
            $trainer = $payout->trainer;

            if ($payout->amount > $trainer->balance) {
                return back()->withErrors(['message' => 'Nepakanka lėšų.']);
            }

            $trainer->balance -= $payout->amount;
            $trainer->save();
        }

        $payout->status = $request->status;
        $payout->save();

        return redirect()->back()->with('success', 'Išmokėjimo statusas atnaujintas.');
    }
}
