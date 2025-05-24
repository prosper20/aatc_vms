<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ProfileController extends Controller
{
    public function edit()
{
    $employee = auth('employee')->user();
    return view('profile.edit', compact('employee'));
}

public function update(Request $request)
{
    $employee = auth('employee')->user();

    $request->validate([
        'name' => 'required|string|max:255',
        // Add other fields
    ]);

    $employee->update($request->only('name'));

    return redirect()->route('update-profile')->with('success', 'Profile updated.');
}
}
