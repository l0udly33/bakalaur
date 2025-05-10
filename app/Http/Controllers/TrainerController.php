<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class TrainerController extends Controller
{
    public function index(Request $request)
    {
        $query = \App\Models\User::with('profile')->whereHas('profile');

        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('rank')) {
            $query->whereHas('profile', function ($q) use ($request) {
                $q->where('rank', $request->rank);
            });
        }

        if ($request->filled('day')) {
            $query->whereHas('profile', function ($q) use ($request) {
                $q->where('availability->notes', 'like', '%' . $request->day . '%');
            });
        }

        if ($request->has('has_achievements')) {
            $query->whereHas('profile', function ($q) {
                $q->whereNotNull('achievements');
            });
        }

        $trainers = $query->get();

        $trainers = $trainers->map(function ($trainer) {
            $lowestPrice = null;

            if ($trainer->profile && is_array($trainer->profile->pricing)) {
                $prices = collect($trainer->profile->pricing)
                    ->pluck('price')
                    ->filter()
                    ->map(fn($val) => (float) $val);

                $lowestPrice = $prices->min();
            }

            $trainer->lowest_price = $lowestPrice ?? INF;
            return $trainer;
        });

        if ($request->sort === 'asc') {
            $trainers = $trainers->sortBy('lowest_price');
        } elseif ($request->sort === 'desc') {
            $trainers = $trainers->sortByDesc('lowest_price');
        }

        return view('trainers', compact('trainers'));
    }
}
