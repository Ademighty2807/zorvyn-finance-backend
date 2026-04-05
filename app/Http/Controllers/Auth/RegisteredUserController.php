<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class RegisteredUserController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|string|email|max:255|unique:users',
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'role'     => 'sometimes|in:admin,accountant,viewer', // optional
        ]);

        $user = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
        ]);

        // Assign role — use provided role or default to 'viewer'
        $user->assignRole($request->role ?? 'viewer');

        event(new Registered($user));

        $token = $user->createToken('main')->plainTextToken;

        return $this->success([
            'user'  => new UserResource($user->load('roles')),
            'token' => $token,
        ], 'Registration successful');
    }
}
