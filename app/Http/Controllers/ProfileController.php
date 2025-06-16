<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    public function edit()
    {
        $staff = auth('staff')->user();
        return view('profile.edit', compact('staff'));
    }

    public function update(Request $request)
    {
        $staff = auth('staff')->user();

        $validationRules = [
            'name' => 'required|string|max:255',
            'organization' => 'required|string|max:255',
            'designation' => 'required|string|max:255',
            'country_code' => 'required|string|max:10',
            'phone' => 'required|string|max:15',
        ];

        // Only require password if profile isn't completed
        if (!$staff->profile_completed) {
            $validationRules['new_password'] = 'required|min:8';
            $validationRules['confirm_password'] = 'required|same:new_password';
        } else {
            $validationRules['new_password'] = 'nullable|min:8';
            $validationRules['confirm_password'] = 'nullable|same:new_password';
        }

        $request->validate($validationRules);

        $data = $request->only([
            'name', 'organization', 'designation',
            'country_code', 'phone'
        ]);

        // Handle password update if provided or if profile isn't completed
        if ($request->filled('new_password') || !$staff->profile_completed) {
            $data['password'] = Hash::make($request->new_password);
        }

        // Only set profile_completed if it wasn't already
        if (!$staff->profile_completed) {
            $data['profile_completed'] = true;
        }

        $staff->update($data);

        return redirect()->route('staff.dashboard')
            ->with('success', $staff->profile_completed ? __('Profile updated successfully!') : __('Profile completed successfully!'));
    }

    // public function update(Request $request)
    // {
    //     $staff = auth('staff')->user();

    //     $request->validate([
    //         'name' => 'required|string|max:255',
    //         'organization' => 'required|string|max:255',
    //         'designation' => 'required|string|max:255',
    //         'country_code' => 'required|string|max:10',
    //         'phone' => 'required|string|max:15',
    //         'new_password' => 'nullable|min:8',
    //         'confirm_password' => 'nullable|same:new_password'
    //     ]);

    //     $data = $request->only([
    //         'name', 'organization', 'designation',
    //         'country_code', 'phone'
    //     ]);

    //     // Handle password update if provided
    //     if ($request->filled('new_password')) {
    //         $data['password'] = Hash::make($request->new_password);
    //     }

    //     $data['profile_completed'] = true;

    //     $staff->update($data);

    //     return redirect()->route('staff.dashboard')
    //         ->with('success', 'Profile updated successfully!');
    // }
}
