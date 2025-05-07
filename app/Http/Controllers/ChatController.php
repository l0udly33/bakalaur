<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Chat;
use App\Models\Order;
use Illuminate\Support\Facades\Auth;

class ChatController extends Controller
{
    public function show($orderId)
    {
        $order = Order::with('chats.sender')->findOrFail($orderId);

        if (Auth::id() !== $order->user_id && Auth::id() !== $order->trainer_id) {
            abort(403);
        }

        return view('chat.show', compact('order'));
    }

    public function store(Request $request, $orderId)
    {
        $request->validate(['message' => 'required|string']);

        $order = Order::findOrFail($orderId);

        if (Auth::id() !== $order->user_id && Auth::id() !== $order->trainer_id) {
            abort(403);
        }

        Chat::create([
            'order_id' => $order->id,
            'sender_id' => Auth::id(),
            'message' => $request->message,
        ]);

        return redirect()->route('chat.show', $order->id);
    }
}
