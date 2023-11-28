<?php

namespace App\Http\Controllers;

use App\Enums\Actions;
use App\Models\User;
use App\Enums\Errors;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Pioneira\Security\Laravel\Facades\SecurityValidation;

class RegisterController extends Controller
{
    public function create(Request $request)
    {
        $validateRequest = [
            'name'            => 'required|min:3|string',
            'email' => ['required', SecurityValidation::emailFormat()],
            'password' => ['required', SecurityValidation::senhaFormat()],
        ];

        SecurityValidation::apiSecurityValidate($request, $validateRequest);

        if (User::firstWhere('email', request('email'))) {
            return Errors::USER_ALREADY_REGISTERED->response(["email" => $request->email], 409);
        }

        try {
            $user = new User;
            $user->name = request('name');
            $user->email = request('email');
            $user->password = request('password');
            $user->save();
        } catch (\Exception $err) {
            Log::error('Erro ao tentar salvar usuário no banco de dados', [
                'erro' => $err
            ]);

            return Errors::USER_CREATE_FAILED->response(["email" => $request->email], 409);
        }

        LogController::addsLog(null, $request->name, Actions::SIGN_UP_STORED);


        return response()->json('Usuario cadastrado com sucesso');
    }
}
