<?php

namespace App\Http\Controllers;

use App\Models\Profile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    public function show()
    {
        $user = Auth::user();
        $profile = $user->profile;

        return view('user.profile.show', compact('profile'));
    }

    public function edit()
    {
        $user = Auth::user();
        $profile = $user->profile;

        return view('user.profile.edit', compact('profile'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'required|string|max:255',
            'phone' => 'required|string|max:15',
        ]);

        $user = Auth::user();
        $profile = $user->profile;

        $profile->update($request->all());

        return redirect()->route('user.profile.show')->with('success', 'Profile updated successfully.');
    }
}