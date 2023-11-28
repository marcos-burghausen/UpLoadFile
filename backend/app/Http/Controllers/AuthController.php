<?php

namespace App\Http\Controllers;

use App\Enums\Actions;
use App\Enums\Errors;
use Illuminate\Http\Request;
use Pioneira\Security\Laravel\Facades\SecurityValidation;

class AuthController extends Controller
{
    /**
     * Get a JWT via given credentials.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request)
    {
        $validateRequest = [
            'email' => ['required', SecurityValidation::emailFormat()],
            'password' => ['required', SecurityValidation::senhaFormat()],
        ];
        SecurityValidation::apiSecurityValidate($request, $validateRequest);

        $credentials = request(['email', 'password']);
        $token = auth()->attempt($credentials);
        if (!$token) {
            LogController::addsLog(null, $request->email, Actions::USER_OR_PASSWORD_INVALID);
            return Errors::INVALID_USERNAME_OR_PASSWORD->response(['email' => $request->get('email')]);
        }

        $user = auth()->user();
        $token =  $this->respondWithToken($token);
        LogController::addsLog(null, $request->email, Actions::LOGIN);
        return response()->json([
            'token' => $token->original,
            'user' => $user->name
        ]);
    }

    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        $user = auth()->user();

        LogController::addsLog(null, $user->email, Actions::LOGOUT);

        auth()->logout();

        return response()->json(['message' => 'Logout com sucesso']);
    }

    /**
     * Get the token array structure.
     *
     * @param  string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 600
        ]);
    }
}
