<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class AdminController extends Controller
{
    public function index(Request $request)
    {
        $query = User::query();

        if ($request->get('sort') === 'role') {
            $query->orderBy('role');
        }

        $users = $query->get();

        return view('admin', compact('users'));
    }

    public function edit(User $user)
    {
        return view('admin.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'role' => 'required|in:user,trainer,admin,blocked',
        ]);

        $user->name = $request->name;
        $user->email = $request->email;
        $user->role = $request->role;

        $user->admin_notes = $request->admin_notes;

        $user->save();

        return redirect()->route('admin')->with('success', 'Naudotojas atnaujintas sėkmingai.');
    }


}
