<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class ProfileController extends Controller
{
    /**
     * Show the customer profile page.
     */
    public function show()
    {
        return view('customer.profile', [
            'user' => auth()->user()
        ]);
    }

    /**
     * Update the customer profile details.
     */
    public function update(Request $request)
    {
        $user = auth()->user();

        $request->validate([
            'name'    => 'required|string|max:255',
            'email'   => 'required|email|max:255|unique:users,email,' . $user->id,
            'phone'   => 'nullable|string|max:20',
            'address' => 'nullable|string|max:500',
        ]);

        $user->update([
            'name'    => $request->input('name'),
            'email'   => $request->input('email'),
            'phone'   => $request->input('phone'),
            'address' => $request->input('address'),
        ]);

        return redirect()->back()->with('success', __('Profile updated successfully!'));
    }

    /**
     * Update the customer password.
     */
    public function updatePassword(Request $request)
    {
        $user = auth()->user();

        $request->validate([
            'current_password' => 'required|string',
            'password'         => 'required|string|min:6|confirmed',
        ]);

        if (!Hash::check($request->input('current_password'), $user->password)) {
            return redirect()->back()->withErrors([
                'current_password' => __('The provided password does not match your current password.')
            ]);
        }

        $user->update([
            'password' => Hash::make($request->input('password'))
        ]);

        return redirect()->back()->with('success', __('Password updated successfully!'));
    }
}
