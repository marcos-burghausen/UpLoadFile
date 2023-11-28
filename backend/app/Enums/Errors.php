<?php

namespace App\Enums;

use App\Http\Controllers\LogController;

enum Errors: string
{
    /**
     * 001 ao 100 -> Cadastro
     * 101 ao 200 -> Login
     * 201 ao 300 -> Senha
     * 301 ao 400 -> 
     * ...
     */

    case EXAMPLE_ERROR                  = "SP000";

    case USER_ALREADY_REGISTERED        = "SP001";          //usuario ja cadastrado
    case ERROR_WHILE_GETTING_USER_DATA  = "SP002";          //erro ao obter dados do usuario
    case USER_CREATE_FAILED             = "SP003";          //falha na criação do usuario

    case INVALID_USERNAME_OR_PASSWORD   = "SP200";          //usuario ou senha invalidos


    public function response(array $content = [], int $http = 409)
    {
        LogController::addsLog(null, $content["email"], Actions::ERRORS, [$this->name]);

        return response([
            'error_code' => $this->value,
            ...$content
        ], $http);
    }
}
