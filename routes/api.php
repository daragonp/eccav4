<?php

use App\Http\Controllers\Api\VerseApiController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

/*
|--------------------------------------------------------------------------
| API privada v1 (emisora)
|--------------------------------------------------------------------------
|
| Se desactiva el throttle GLOBAL del grupo 'api' con withoutMiddleware para
| estas rutas, porque ese limitador corre ANTES del middleware de autenticación
| y sus respuestas 429 no quedarían registradas, rompiendo el requisito de
| "registrar TODA petición". En su lugar, 'auth.apiclient' va PRIMERO (envuelve
| al throttle de ruta y así puede loguear incluso los 429) y 'throttle:60,1'
| va DESPUÉS. El orden es intencional: no debe invertirse.
|
*/
Route::prefix('v1')
    ->withoutMiddleware('throttle:api')
    ->middleware(['auth.apiclient', 'throttle:60,1'])
    ->group(function () {
        Route::get('/verse/today', [VerseApiController::class, 'today']);
    });
