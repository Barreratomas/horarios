<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\StudentController;
use App\Http\Controllers\horarios\CambioDocenteController;
use App\Models\horarios\CambioDocente;
use Illuminate\Support\Facades\Route;
use Laravel\Socialite\Facades\Socialite;
use App\Http\Controllers\horarios\AulaController;


Route::middleware(['auth:sanctum'])->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/super-admin/administration', [StudentController::class, 'index']);
    Route::get('/students/{id}', [StudentController::class, 'indexById']);
    Route::post('/students/profile-photo', [StudentController::class, 'updateProfilePhoto']);
    Route::patch('/students/{id}', [StudentController::class, 'updateApprovalStatus']);
    Route::delete('/students/delete/{id}', [StudentController::class, 'destroy']);
});

Route::post('/signup/super-admin', [AuthController::class, 'signupSuperAdmin']);
Route::post('/signup', [AuthController::class, 'signup']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/password/forgot', [AuthController::class, 'forgotPassword']);
Route::post('/password/reset', [AuthController::class, 'resetPassword']);
Route::get('/verify-token/{token}', [AuthController::class, 'verifyResetToken']);

// Route::get('auth/google', function () {
//     return Socialite::driver('google')->redirect();
// });

// Route::get('auth/google/callback', function () {
//     $user = Socialite::driver('google')->user();
// });

Route::post('/auth/google/callback', [AuthController::class, 'googleCallback']);



//------------------------------------------------------------------------------------------------------------------------------------------------
// Swagger

// Aulas
Route::get('/horarios/aulas', [AulaController::class, 'index']);
Route::get('/horarios/aulas/{id}', [AulaController::class, 'show']);
Route::post('/horarios/aulas/guardar', [AulaController::class, 'store']);
Route::put('/horarios/aulas/actualizar/{id}', [AulaController::class, 'update']);
Route::delete('/horarios/aulas/eliminar/{id}', [AulaController::class, 'destroy']);

// CambioDocente
Route::get('/horarios/cambioDocente', [CambioDocenteController::class,'index']);
Route::get('/horarios/cambioDocente/{id}', [CambioDocenteController::class,'show']);
Route::post('/horarios/cambioDocente/guardar', [CambioDocenteController::class,'store']);
Route::put('/horarios/cambioDocente/actualizar/{id}', [CambioDocenteController::class,'update']);
Route::delete('/horarios/cambioDocente/eliminar/{id}', [CambioDocenteController::class,'destroy']);


