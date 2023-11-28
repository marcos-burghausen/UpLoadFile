<?php

namespace App\Http\Controllers;

use Exception;
use App\Enums\Actions;
use App\Models\Log as LogModel;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;

class LogController extends Controller
{

    /**
     * @param string $username      email of the usuario
     * @param string $timestamp     request time
     * @param string $user_agent    browser name
     * @param string $ip            ip id
     * @param Actions $action       identified action
     * @param array $parameters     additional request details
     * @return void
     */

    public static function addsLog(string $entity_id = null, string $username, Actions $action, array $parameters = []): void
    {
        try {
            LogModel::create([
                'email' => $username,
                'timestamp' => date('d/m/Y - H:i:s', $_SERVER['REQUEST_TIME']),
                'user_agent' => $_SERVER['HTTP_USER_AGENT'],
                'ip' => $_SERVER['REMOTE_ADDR'],
                'action' => $action->getAction(),
                'parameters' => $parameters
            ]);
        } catch (Exception $err) {
            Log::error('Error adding log to database', [
                'error' => $err
            ]);
        }
    }
}
