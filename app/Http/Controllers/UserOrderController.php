<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use Illuminate\Support\Facades\Auth;
use App\Models\Review;

class UserOrderController extends Controller
{
    public function index()
    {
        $orders = Order::where('user_id', Auth::id())->with('trainer')->latest()->get();

        return view('orders.user-orders', compact('orders'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'trainer_id' => 'required|exists:users,id',
            'selected_option' => 'required',
            'description' => 'nullable|string',
        ]);

        $trainer = \App\Models\User::where('id', $request->trainer_id)->where('role', 'trainer')->firstOrFail();
        $profile = $trainer->trainerProfile;

        if ($request->selected_option === 'free_trial') {
            
            $existingFreeTrial = \App\Models\Order::where('user_id', auth()->id())
                ->where('trainer_id', $trainer->id)
                ->where('hours', 0.25)
                ->where('price', 0)
                ->exists();

            if ($existingFreeTrial) {
                return back()->withErrors(['selected_option' => 'Jūs jau pasinaudojote nemokama įvado treniruote pas šį trenerį.']);
            }

            \App\Models\Order::create([
                'user_id' => auth()->id(),
                'trainer_id' => $trainer->id,
                'status' => 'pending',
                'description' => $request->description,
                'price' => 0,
                'hours' => 0.25,
            ]);

            return redirect()->route('orders.user')->with('success', 'Užsakymas išsiųstas sėkmingai.');
        }

        if (!is_numeric($request->selected_option) || !isset($profile->pricing[$request->selected_option])) {
            return back()->withErrors(['selected_option' => 'Pasirinkta parinktis neegzistuoja.']);
        }

        $option = $profile->pricing[$request->selected_option];

        \App\Models\Order::create([
            'user_id' => auth()->id(),
            'trainer_id' => $trainer->id,
            'status' => 'pending',
            'description' => $request->description,
            'price' => $option['price'],
            'hours' => $option['hours'],
        ]);

        return redirect()->route('orders.user')->with('success', 'Užsakymas išsiųstas sėkmingai!');
    }

    public function pay(Order $order)
    {
        $user = auth()->user();

        if ($order->status !== 'pending') {
            return back()->with('error', 'Užsakymas jau apmokėtas arba atšauktas.');
        }

        if ($user->id !== $order->user_id) {
            return back()->with('error', 'Neturite teisės apmokėti šio užsakymo.');
        }

        if ($user->balance < $order->price) {
            return back()->with('error', 'Nepakanka lėšų balanse.');
        }

        $user->balance -= $order->price;
        $user->save();

        $order->status = 'paid';
        $order->save();

        return back()->with('success', 'Užsakymas apmokėtas sėkmingai.');
    }

    public function submitReview(Request $request, Order $order)
    {
        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:1000',
        ]);

        if ($order->review || $order->user_id !== auth()->id()) {
            return back()->with('error', 'Atsiliepimą galima palikti tik vieną kartą.');
        }

        Review::create([
            'order_id' => $order->id,
            'user_id' => auth()->id(),
            'trainer_profile_id' => $order->trainer->trainerProfile->id,
            'rating' => $request->rating,
            'comment' => $request->comment,
        ]);

        return back()->with('success', 'Atsiliepimas išsaugotas');
    }

    public function repeat(Order $order)
    {
        if (auth()->id() !== $order->user_id) {
            return redirect()->back()->with('error', 'Neturite teisės pakartoti šio užsakymo.');
        }

        $newOrder = $order->replicate();
        $newOrder->status = 'pending';
        $newOrder->created_at = now();
        $newOrder->updated_at = now();
        $newOrder->save();

        return redirect()->route('user.orders')->with('success', 'Užsakymas sėkmingai pakartotas.');
    }

    public function destroyReview($id)
    {
        $review = Review::findOrFail($id);

        // Optional: Only allow admins to delete
        if (auth()->user()->role !== 'admin') {
            return back()->with('error', 'Neturite teisės ištrinti šio atsiliepimo.');
        }

        $review->delete();

        return back()->with('success', 'Atsiliepimas sėkmingai ištrintas.');
    }
}
