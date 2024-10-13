<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\StudentController;
use App\Http\Controllers\horarios\CambioDocenteController;
use App\Http\Controllers\horarios\CarreraController;
use App\Http\Controllers\horarios\DisponibilidadController;
use App\Models\horarios\CambioDocente;
use Illuminate\Support\Facades\Route;
use Laravel\Socialite\Facades\Socialite;
use App\Http\Controllers\horarios\AulaController;
use App\Http\Controllers\horarios\DocenteUCController;
use App\Http\Controllers\horarios\GradoController;
use App\Http\Controllers\horarios\GradoUcController;
use App\Http\Controllers\horarios\HorarioController;
use App\Http\Controllers\horarios\HorarioPrevioDocenteController;
use App\Http\Controllers\horarios\PlanEstudioController;
use App\Http\Controllers\horarios\UCPlanController;


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
Route::get('/horarios/cambioDocente', [CambioDocenteController::class, 'index']);
Route::get('/horarios/cambioDocente/{id}', [CambioDocenteController::class, 'show']);
Route::post('/horarios/cambioDocente/guardar', [CambioDocenteController::class, 'store']);
Route::put('/horarios/cambioDocente/actualizar/{id}', [CambioDocenteController::class, 'update']);
Route::delete('/horarios/cambioDocente/eliminar/{id}', [CambioDocenteController::class, 'destroy']);

// Carreras
Route::get('/horarios/carreras', [CarreraController::class, 'index']);
Route::get('/horarios/carreras/{id}', [CarreraController::class, 'show']);
Route::post('/horarios/carreras/guardar', [CarreraController::class, 'store']);
Route::put('/horarios/carreras/actualizar/{id}', [CarreraController::class, 'update']);
Route::delete('/horarios/carreras/eliminar/{id}', [CarreraController::class, 'destroy']);

// Disponibilidades
Route::get('/horarios/disponibilidad', [DisponibilidadController::class, 'index']);
Route::get('/horarios/disponibilidad/{id}', [DisponibilidadController::class, 'show']);
Route::post('/horarios/disponibilidad/guardar', [DisponibilidadController::class, 'store']);
Route::put('/horarios/disponibilidad/actualizar/{id}', [DisponibilidadController::class, 'update']);
Route::delete('/horarios/disponibilidad/eliminar/{id}', [DisponibilidadController::class, 'destroy']);

// DocenteUC
Route::get('/horarios/docenteUC', [DocenteUCController::class, 'index']);
Route::get('/horarios/docenteUC/idDocente/{id}', [DocenteUCController::class, 'obtenerDocenteUCPorIdDocente']);
Route::get('/horarios/docenteUC/idUC/{id}', [DocenteUCController::class, 'obtenerDocenteUCPorIdUC']);
Route::post('/horarios/docenteUC/guardar', [DocenteUCController::class, 'store']);
Route::put('/horarios/docenteUC/actualizar/idDocente/{id}', [DocenteUCController::class, 'actualizarDocenteUCPorIdDocente']);
Route::put('/horarios/docenteUC/actualizar/idUC/{id}', [DocenteUCController::class, 'actualizarDocenteUCPorIdUC']);
Route::delete('/horarios/docenteUC/eliminar/idDocente/{id}', [DocenteUCController::class, 'eliminarDocenteUCPorIdDocente']);
Route::delete('/horarios/docenteUC/eliminar/idUC/{id}', [DocenteUCController::class, 'eliminarDocenteUCPorIdUC']);

// Grados
Route::get('/horarios/grados', [GradoController::class, 'index']);
Route::get('/horarios/grados/{id}', [GradoController::class, 'show']);
Route::post('/horarios/grados/guardar', [GradoController::class, 'store']);
Route::put('/horarios/grados/actualizar/{id}', [GradoController::class, 'update']);
Route::delete('/horarios/grados/eliminar/{id}', [GradoController::class, 'destroy']);

// GradoUC
Route::get('/horarios/gradoUC', [GradoUcController::class, 'index']);
Route::get('/horarios/gradoUC/idGrado/{id}', [GradoUcController::class, 'obtenerGradoUcPorIdGrado']);
Route::get('/horarios/gradoUC/idUC/{id}', [GradoUcController::class, 'obtenerGradoUcPorIdUC']);
Route::post('/horarios/gradoUC/guardar', [GradoUcController::class, 'store']);
Route::delete('/horarios/gradoUC/eliminar/idGrado/{id}', [GradoUcController::class, 'eliminarGradoUcPorIdGrado']);
Route::delete('/horarios/gradoUC/eliminar/idUC/{id}', [GradoUcController::class, 'eliminarGradoUcPorIdUC']);

// Disponibilidad
Route::get('/horarios/disponibilidad', [DisponibilidadController::class, 'index']);
Route::get('/horarios/disponibilidad/{id}', [DisponibilidadController::class, 'show']);
Route::post('/horarios/disponibilidad/store', [DisponibilidadController::class, 'store']);
Route::put('/horarios/disponibilidad/update/{id}', [DisponibilidadController::class, 'update']);
Route::delete('/horarios/disponibilidad/eliminar/{id}', [DisponibilidadController::class, 'destroy']);


Route::get('/disponibilidad/guardar', [DisponibilidadController::class, 'guardar'])->name('storeDisponibilidad');
//Route::get('/disponibilidad/disponibilidad-index',[DisponibilidadController::class,'redireccionar'])->name('redireccionarDisponibilidad');
Route::get('/disponibilidad/actualizar-disponibilidad/{h_p_d}/{dm}', [DisponibilidadController::class, 'actualizar'])->name('actualizarDisponibilidad');
Route::get('/disponibilidad/disponibilidad-index-error', [DisponibilidadController::class, 'redireccionarError'])->name('redireccionarDisponibilidadError');


// horario
Route::get('/horario', [HorarioController::class, 'mostrarFormularioPartial'])->name('mostrarFormularioHorario');
Route::post('/horario', [HorarioController::class, 'mostrarHorario'])->name('mostrarHorario');

Route::get('/horario/docente', [HorarioController::class, 'mostrarFormularioDocentePartial'])->name('formularioHorarioDocente');
Route::post('/horario/docente', [HorarioController::class, 'mostrarHorarioDocente'])->name('mostrarHorarioDocente');

Route::get('/horario/bedelia', [HorarioController::class, 'mostrarHorarioBedelia'])->name('mostrarHorarioBedelia');

// Route::get('horario/crear-horario',[HorarioController::class,'crear'])->name('crearHorario');
Route::get('/horario/crear-horario', [HorarioController::class, 'store'])->name('storeHorario');

// HorarioPrevioDocente
Route::get('/docente/crear-h-p-d/{docente}', [HorarioPrevioDocenteController::class, 'crear'])->name('mostrarFormularioHPD');
Route::post('/docente/crear-h-p-d/{docente}', [HorarioPrevioDocenteController::class, 'store'])->name('storeHPD');
Route::get('/docente/actualizar-h_p_d/{h_p_d}/{dUC}', [HorarioPrevioDocenteController::class, 'formularioActualizar'])->name('mostrarActualizarHPD');
Route::put('/docente/actualizar-h_p_d/{h_p_d}/{dUC}', [HorarioPrevioDocenteController::class, 'actualizar'])->name('actualizarHPD');

// PlanEstudio
Route::get('/horarios/planEstudio', [PlanEstudioController::class, 'index']);
Route::get('/horarios/planEstudio/{id}', [PlanEstudioController::class, 'show']);
Route::post('/horarios/planEstudio/guardar', [PlanEstudioController::class, 'store']);
Route::put('/horarios/planEstudio/actualizar/{id}', [PlanEstudioController::class, 'update']);
Route::delete('/horarios/planEstudio/eliminar/{id}', [PlanEstudioController::class, 'destroy']);

// UCPlan
Route::get('/horarios/uCPlan', [UCPlanController::class, 'index']);
Route::get('/horarios/uCPlan/{id}', [UCPlanController::class, 'show']);
Route::post('/horarios/uCPlan/guardar', [UCPlanController::class, 'store']);
Route::put('/horarios/uCPlan/actualizar/{id}', [UCPlanController::class, 'update']);
Route::delete('/horarios/uCPlan/eliminar/{id}', [UCPlanController::class, 'destroy']);

// UnidadCurricular
Route::get('/horarios/unidadCurricular', [UCPlanController::class, 'index']);
Route::get('/horarios/unidadCurricular/{id}', [UCPlanController::class, 'show']);
Route::post('/horarios/unidadCurricular/guardar', [UCPlanController::class, 'store']);
Route::put('/horarios/unidadCurricular/actualizar/{id}', [UCPlanController::class, 'update']);
Route::delete('/horarios/unidadCurricular/eliminar/{id}', [UCPlanController::class, 'destroy']);

// CarreraUC
Route::get('/horarios/carreraUC', [UCPlanController::class, 'index']);
Route::get('/horarios/carreraUC/idCarrera/{id}', [UCPlanController::class, 'show']);
Route::get('/horarios/carreraUC/idUC/{id}', [UCPlanController::class, 'show']);
Route::post('/horarios/carreraUC/guardar', [UCPlanController::class, 'store']);
Route::delete('/horarios/carreraUC/eliminar/idCarrera/{id}', [UCPlanController::class, 'destroy']);
Route::delete('/horarios/carreraUC/eliminar/idUC/{id}', [UCPlanController::class, 'destroy']);

