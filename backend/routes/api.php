<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DataController;
use App\Http\Controllers\FileController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\UploadController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('create', [RegisterController::class, 'create']);
Route::post('auth/login', [AuthController::class, 'login']);

Route::middleware('jwt.auth')->group(function () {
    Route::post('auth/logout', [AuthController::class, 'logout']);

    Route::post('file-upload', [FileController::class, 'saveFile']);
    Route::get('quantidade-movimentacao', [DataController::class, 'qtdMov']);
    Route::get('valor-movimentacao', [DataController::class, 'vlrMov']);
    Route::get('rx1-px1-movimentacao', [DataController::class, 'rx1Px1']);
    Route::get('coop-agencia', [DataController::class, 'coopAgencia']);
    Route::get('relacao-credito-debito', [DataController::class, 'relacaoCdtDbt']);

    Route::post('upload', [UploadController::class, 'store'])->name('upload.store')->withoutMiddleware('throttle:api');
});
