<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TrainerProfile;
use Illuminate\Support\Facades\Auth;

class TrainerProfileController extends Controller
{
    public function edit()
    {
        $profile = Auth::user()->trainerProfile;

        return view('trainer.profile-edit', compact('profile'));
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'profile_picture' => 'nullable|image|max:2048',
            'description' => 'nullable|string',
            'languages' => 'nullable|string',
            'rank' => 'nullable|string|in:Iron,Bronze,Silver,Gold,Platinum,Diamond,Ascendant,Immortal,Radiant',
            'pricing' => 'nullable|array|max:3',
            'pricing.*.hours' => 'required_with:pricing.*.price|numeric',
            'pricing.*.price' => 'required_with:pricing.*.hours|numeric',
            'availability' => 'nullable|array',           
            'achievements' => 'nullable|array|max:5',
            'achievements.*.place' => 'nullable|in:1,2,3',
            'achievements.*.text' => 'nullable|string|max:255',            
        ]);

        $user = Auth::user();
        
        $profile = $user->trainerProfile ?? new TrainerProfile(['user_id' => $user->id]);

        if ($request->hasFile('profile_picture')) {
            $imageData = file_get_contents($request->file('profile_picture')->getRealPath());
            $profile->profile_picture = $imageData;
        }

        $profile->description = $validated['description'] ?? '';
        $profile->languages = $validated['languages'] ?? '';
        $profile->rank = $validated['rank'] ?? null;
        $profile->pricing = $validated['pricing'] ?? [];
        $profile->availability = $validated['availability'] ?? [];

        $profile->free_trial = $request->has('free_trial');

        $profile->achievements = collect($request->input('achievements'))
            ->filter(fn($a) => !empty($a['place']) && !empty($a['text']))
            ->values()
            ->toJson();

        $profile->save();

        return redirect()->back()->with('success', 'Profilis atnaujintas');
    }

    public function show($id)
    {
        $user = \App\Models\User::findOrFail($id);

        if ($user->role !== 'trainer') {
            abort(404);
        }

        $profile = $user->trainerProfile;

        $profile = TrainerProfile::where('user_id', $user->id)->first();
        $reviews = $profile ? $profile->reviews()->with('user')->latest()->get() : collect();

        return view('trainer.profile-show', [
            'user' => $user,
            'profile' => $profile,
            'reviews' => $reviews,
        ]);
    }
}
