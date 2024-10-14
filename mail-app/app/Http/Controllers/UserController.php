<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{

    /**
     * Register - for the user to create his account
     * Return: access_token and refresh token or error message
     */
    public function register(Request $request)
    {
        if (User::where('email', $request->email)->exists()) {
            return response()->json([
                'message' =>  'User with this email already exits'
            ], 409);
        }
        $validator = Validator::make($request->all(), [
            'first_name' => "required|string|max:255",
            'last_name' => "required|string|max:255",
            'email' => "required|string|email|unique:users",
            'password' => 'required|string|min:8',
            'profile' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }
        $profilePath = null;
        if ($request->hasFile('profile')) {
            $profilePath = $request->file('profile')->store('profiles', 'public');
        }

        $user = User::create([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'profile' => $profilePath,
        ]);
        $accessToken = $user->createToken('AccessToken')->plainTextToken;
        $refreshToken = Str::random(60);
        DB::table('refresh_tokens')->insert([
            'user_id' => $user->id,
            'token' => Hash::make($refreshToken),
            'expires_at' => null,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return response()->json([
            'access_token' => $accessToken,
            'refresh_token' => $refreshToken,
            'token_type' => 'Bearer',
            'expires_in' => 900, // 15 minutes in seconds
        ], 201);
    }

    /**
     * login - for the user to log into his account
     * Return: on success a sanctum token and refresh token else and error
     */
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email',
            'password' => 'required|string'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()
            ], 422);
        }

        $user = User::where('email', $request->email)->first();
        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json([
                'message' => 'Invalid login credential'
            ], 401);
        }
        $token = $user->createToken('AccessToken')->plainTextToken;
        $existingRefreshToken = DB::table('refresh_tokens')
            ->where('user_id', $user->id)
            ->first();
        if (!$existingRefreshToken) {
            $refreshToken = Str::random(60);
            DB::table('refresh_tokens')->insert([
                'user_id' => $user->id,
                'token' => Hash::make($refreshToken),
                'expires_at' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
        return response()->json([
            'message' => 'Login successful',
            'access_token' => $token,
            'refresh_tokens' => $refreshToken,
            'user' => $user
        ], 200);
    }
    /**
     * logout - for the user to logout
     * Return: On success revoke the access_token or error message
     */
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        DB::table('refresh_tokens')
        ->where('user_id', $request->user()->id)
        ->delete();

        return response()->json([
            'message' => 'Successfully logged out',
        ], 200);
    }

    public function refresh(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'refresh_token' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()
            ], 422);
        }
        $refreshToken = DB::table('refresh_tokens')->where('token', $request->refresh_token)->first();
        $user = User::find($refreshToken->user_id);
        $accessToken = $user->createToken('AccessToken')->plainTextToken;
        return response()->json([
            'access_token' => $accessToken,
            'expires_in' => 900,
        ], 200);
    }
}
