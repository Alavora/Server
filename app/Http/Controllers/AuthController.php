<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Auth\SessionGuard;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

/**
 * Controller for Auth calls
 * @package App\Http\Controllers
 */
class AuthController extends Controller
{
    /**
     * Registers a new user and automatically login returning the token
     *
     * @param Request $request
     * @return void
     */
    public function register(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
            'address' => 'required|string|max:255',
            'phone' => 'required|string|max:255',
            'longitude' => '',
            'latitude' => '',
        ]);

        $user = User::create([
            'name' => $validatedData['name'],
            'email' => $validatedData['email'],
            'password' => Hash::make($validatedData['password']),
            'address' => $validatedData['address'],
            'phone' => $validatedData['phone'],
        ]);

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'access_token' => $token,
            'token_type' => 'Bearer',
        ]);
    }

    /**
     * Login the user and returns a valid token for nexts api calls
     *
     * @param Request $request containing email and password
     * @return token if login succesfull, 422 if not
     */
    public function login(Request $request)
    {
        if (!Auth::attempt($request->only('email', 'password'))) {
            return response()->json([
                'message' => 'Invalid login details'
            ], 401);
        }

        $user = User::where('email', $request['email'])->firstOrFail();

        $token = $user->createToken('auth_token')->plainTextToken;

        $user = Auth::user();
        // dd($user);
        return response()->json([
            'access_token' => $token,
            'token_type' => 'Bearer',
            'user' => new UserResource($user),
        ]);
    }

    /**
     * Logout current user, invalidating its token.
     *
     * @param Request $request
     * @return void
     */
    public function logout(Request $request)
    {
        // Auth::logout();

        if ($request->user()->currentAccessToken()->delete()) {
            return response()->json([
                'status_code' => 200,
                'message' => 'Logged Out',
            ], 200);
        } else {
            return response()->json([
                'status_code' => 408,
                'message' => 'failed',
            ], 408);
        }
    }

    /**
     * Returns logged user info
     *
     * @param Request $request
     * @return void
     */
    public function me(Request $request)
    {
        return $request->user();
    }

    /**
     * Updates user info
     *
     * @param Request $request
     * @return void
     */
    public function update(Request $request)
    {
        $user = Auth::user();
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255',
            'password' => 'required|string|min:8',
            'address' => 'string|max:255',
            'phone' => 'string|max:255',
            'longitude' => '',
            'latitude' => '',
        ]);

        $user = User::findOrFail($user->id);

        // $user->name  = $validatedData['name'];
        // $user->email  = $validatedData['email'];
        // $user->password  = Hash::make($validatedData['password']);
        // $user->address  = $validatedData['address'];
        // $user->phone  = $validatedData['phone'];

        // $user->save();

        $user->update($validatedData);

        return response()->json([
            'return' => True,
            'user' => new UserResource($user)
        ], 202);
    }
}
