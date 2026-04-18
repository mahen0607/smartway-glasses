<?php
// app/Http/Controllers/AccountController.php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class AccountController extends Controller
{
    /**
     * POST /settings/akun
     * Update nama, email, dan password (opsional)
     */
    public function update(Request $request)
    {
        $user = Auth::user();

        // Validasi
        $rules = [
            'name'  => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email,' . $user->id],
        ];

        // Validasi password hanya jika diisi
        if ($request->filled('password')) {
            $rules['password']              = ['required', 'confirmed', Password::min(8)];
            $rules['password_confirmation'] = ['required'];

            // Wajib masukkan password lama untuk keamanan
            $rules['current_password'] = ['required', function ($attr, $value, $fail) use ($user) {
                if (!Hash::check($value, $user->password)) {
                    $fail('Password lama tidak sesuai.');
                }
            }];
        }

        $validated = $request->validate($rules);

        // Update nama & email
        $user->name  = $validated['name'];
        $user->email = $validated['email'];

        // Update password jika diisi
        if ($request->filled('password')) {
            $user->password = Hash::make($validated['password']);
        }

        $user->save();

        return response()->json([
            'success' => true,
            'message' => 'Akun berhasil diperbarui!',
            'name'    => $user->name,
            'email'   => $user->email,
        ]);
    }
}
