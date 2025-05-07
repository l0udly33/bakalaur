<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Order;
use App\Models\Chat;

class AdminTrainerController extends Controller
{
    public function orders($id)
    {
        $trainer = User::findOrFail($id);
        $orders = Order::where('trainer_id', $id)->get();

        return view('admin.trainers.orders', compact('trainer', 'orders'));
    }

    public function viewChat($orderId)
    {
        $order = Order::with('user', 'trainer')->findOrFail($orderId);
        $messages = \App\Models\Chat::where('order_id', $orderId)
            ->with('sender') 
            ->orderBy('created_at')
            ->get();

        return view('admin.trainers.chat', compact('order', 'messages'));
    }

    public function sendMessage(Request $request, $orderId)
    {
        $request->validate([
            'message' => 'required|string|max:1000',
        ]);

        \App\Models\Chat::create([
            'order_id' => $orderId,
            'sender_id' => auth()->id(),
            'message' => $request->message,
        ]);

        return redirect()->route('admin.chat.view', $orderId)->with('success', 'Žinutė išsiųsta.');
    }

}


?>
