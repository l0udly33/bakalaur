<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Order;
use App\Models\Chat;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class TrainerOrderController extends Controller
{
    use AuthorizesRequests;
    public function index(Order $order)
    {

        $orders = Order::where('trainer_id', Auth::id())->with('user')->get();

        return view('trainer.orders', compact('orders'));
    }

    public function show(Order $order)
    {
        if (auth()->user()->id !== $order->trainer_id) {
            abort(403);
        }

        $chats = $order->chats()->with('user')->latest()->get();

        return view('trainer.order-show', compact('order', 'chats'));
    }

    public function updateStatus(Request $request, Order $order)
    {
        $request->validate([
            'status' => 'required|in:pending,completed,canceled',
        ]);

        if (in_array($order->status, ['completed', 'canceled'])) {
            return back()->with('error', 'Šio užsakymo statuso keisti negalima.');
        }

        $newStatus = $request->status;

        $order->status = $newStatus;
        $order->save();

        if ($newStatus === 'completed' && $order->trainer && $order->price > 0) {
            $order->trainer->balance += $order->price;
            $order->trainer->save();
        }

        if ($newStatus === 'canceled' && $order->user && $order->price > 0) {
            $order->user->balance += $order->price;
            $order->user->save();
        }

        return back()->with('success', 'Užsakymo statusas atnaujintas.');
    }
}
