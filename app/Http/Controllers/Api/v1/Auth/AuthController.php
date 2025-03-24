<?php

namespace App\Http\Controllers\Api\v1\Auth;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\BaseController;
use App\Http\Requests\Auth\LoginReqeust;
use App\Http\Requests\Auth\RegisterRequest;
use Exception;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends BaseController
{
    /**
     * Register a new user.
     *
     * @param RegisterRequest $request
     * @return JsonResponse
     */
    public function register(RegisterRequest $request)
    {

        $validated = $request->validated();

        $user = User::create([
            'name'     => $validated['name'],
            'role_id'  => $validated['role_id'] ?? 2,
            'email'    => $validated['email'],
            'password' => Hash::make($validated['password']),
        ]);

        // Create a token
        $token = $user->createToken('KTrihM/Rxkek5u2ev7e7kBeOXyeIYQSSSGo+IVzJFlY=')->plainTextToken;

        $result = [
            'access_token' => $token,
            'token_type'   => 'Bearer',
            'user'         => $user,
        ];

        return $this->sendResponse($result, "Registration Successfully Done", 201);
    }

    /**
     * Login user and create token.
     *
     * @param LoginRequest $request
     * @return JsonResponse
     * @throws ValidationException
     */
    public function login(LoginReqeust $request): JsonResponse
    {
        try {
            $validated = $request->validated();

            $user = User::where('email', $validated['email'])->first();
            if (! $user || ! Hash::check($validated['password'], $user->password)) {
                return $this->sendError("Invalid Credentials");
            }

            // Token generation
            $token = $user->createToken('KTrihM/Rxkek5u2ev7e7kBeOXyeIYQSSSGo+IVzJFlY=')->plainTextToken;

            $result = [
                'access_token' => $token,
                'token_type'   => 'Bearer',
                'user'         => $user,
            ];

            return $this->sendResponse($result, "Login successful");
        } catch (Exception $e) {
            return $this->sendError($e->getMessage());
        }
    }

    /**
     * Logout user.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function logout(Request $request): JsonResponse
    {
        $request->user()->currentAccessToken()->delete();
        return $this->sendResponse([], 'User logged out successfully.');
    }


    /**
     * Get authenticated user information.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function me(Request $request)
    {
        try {
            $result = Auth::user();
            return $this->sendResponse($result->toArray(), 'User information retrieved successfully');
        } catch (Exception $e) {
            return $this->sendError($e->getMessage());
        }
    }

    /**
     * Refresh the user's token.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function refresh(Request $request): JsonResponse
    {
        $user = $request->user();
        // Revoke current token
        $request->user()->currentAccessToken()->delete();

        // Token generation
        $token = $user->createToken('KTrihM/Rxkek5u2ev7e7kBeOXyeIYQSSSGo+IVzJFlY=')->plainTextToken;

        $result = [
            'access_token' => $token,
            'token_type'   => 'Bearer',
            'user'         => $user,
        ];
        return $this->sendResponse($result, 'Token refreshed successfully');
    }
}
