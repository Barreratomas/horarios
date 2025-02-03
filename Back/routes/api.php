<?php

use App\Http\Controllers\AlumnoController;
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
use App\Http\Controllers\horarios\UnidadCurricularController;
use App\Http\Controllers\horarios\CursadaController;
use App\Http\Controllers\CarreraUCController;
use App\Http\Controllers\InscripcionController;
use App\Http\Controllers\AlumnoGradoController;
use App\Http\Controllers\CarreraGradoController;
use App\Http\Controllers\CarreraPlanController;
use App\Http\Controllers\DocenteController;
use App\Http\Controllers\LogModificacionEliminacionController;
use App\Models\AlumnoGrado;

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
Route::get('/horarios/gradoUC/idGrado/relaciones/{id}', [GradoUcController::class, 'obtenerGradoUcPorIdGradoConRelaciones']);

Route::get('/horarios/gradoUC/idUC/{id}', [GradoUcController::class, 'obtenerGradoUcPorIdUC']);
Route::post('/horarios/gradoUC/guardar', [GradoUcController::class, 'store']);
Route::delete('/horarios/gradoUC/eliminar/idGrado/{id}', [GradoUcController::class, 'eliminarGradoUcPorIdGrado']);
Route::delete('/horarios/gradoUC/eliminar/idUC/{id}', [GradoUcController::class, 'eliminarGradoUcPorIdUC']);


// Disponibilidad
//Route::get('/horarios/disponibilidad', [DisponibilidadController::class, 'index']);
//Route::get('/horarios/disponibilidad/{id}', [DisponibilidadController::class, 'show']);
//Route::post('/horarios/disponibilidad/store', [DisponibilidadController::class, 'store']);
//Route::put('/horarios/disponibilidad/update/{id}', [DisponibilidadController::class, 'update']);
Route::get('/horarios/disponibilidad/guardarDisponibilidades', [DisponibilidadController::class, 'guardarDisponibilidades']);
Route::delete('/horarios/disponibilidad/eliminar', [DisponibilidadController::class, 'eliminar']);


// Route::get('/disponibilidad/guardar', [DisponibilidadController::class, 'guardar'])->name('storeDisponibilidad');


//Route::get('/disponibilidad/disponibilidad-index',[DisponibilidadController::class,'redireccionar'])->name('redireccionarDisponibilidad');
Route::get('/disponibilidad/actualizar-disponibilidad/{h_p_d}/{dm}', [DisponibilidadController::class, 'actualizar'])->name('actualizarDisponibilidad');



// horario
Route::get('/horarios/horarios', [HorarioController::class, 'index']);
Route::get('/horarios/horarios/{id}', [HorarioController::class, 'show']);
Route::get('/horarios/horariosPorCarreraGrado/{id}', [HorarioController::class, 'mostrarPorCarreraGrado']);
Route::get('/horarios/horariosPorDocente/{id}', [HorarioController::class, 'mostrarPorDocente']);
Route::post('/horarios/horarios/guardar', [HorarioController::class, 'store']);
Route::put('/horarios/horarios/actualizar/{id}', [HorarioController::class, 'update']);
Route::delete('/horarios/horarios/eliminar/{id}', [HorarioController::class, 'destroy']);


// HorarioPrevioDocente
Route::get('/horarios/horariosPreviosDocentes', [HorarioPrevioDocenteController::class, 'index']);
Route::get('/horarios/horariosPreviosDocentes/{id}', [HorarioPrevioDocenteController::class, 'show']);
Route::post('/horarios/horariosPreviosDocentes/guardar', [HorarioPrevioDocenteController::class, 'store']);
Route::put('/horarios/horariosPreviosDocentes/actualizar/{id}', [HorarioPrevioDocenteController::class, 'update']);
Route::delete('/horarios/horariosPreviosDocentes/eliminar/{id}', [HorarioPrevioDocenteController::class, 'destroy']);

// PlanEstudio
Route::get('/horarios/planEstudio', [PlanEstudioController::class, 'index']);
Route::get('/horarios/planEstudio/relaciones', [PlanEstudioController::class, 'indexConRelaciones']);
Route::get('/horarios/planEstudio/{id}', [PlanEstudioController::class, 'show']);
Route::get('/horarios/planEstudio/relaciones/{id}', [PlanEstudioController::class, 'showConRelaciones']);
Route::post('/horarios/planEstudio/guardar', [PlanEstudioController::class, 'store']);
Route::post('/horarios/planEstudio/finalizar/{id}', [PlanEstudioController::class, 'finalizar_plan']);

Route::put('/horarios/planEstudio/actualizar/{id}', [PlanEstudioController::class, 'update']);
Route::delete('/horarios/planEstudio/eliminar/{id}', [PlanEstudioController::class, 'destroy']);

// UCPlan
Route::get('/horarios/uCPlan', [UCPlanController::class, 'index']);
Route::get('/horarios/uCPlan/{id}', [UCPlanController::class, 'show']);
Route::post('/horarios/uCPlan/guardar', [UCPlanController::class, 'store']);
Route::put('/horarios/uCPlan/actualizar/{id}', [UCPlanController::class, 'update']);
Route::delete('/horarios/uCPlan/eliminar/{id}', [UCPlanController::class, 'destroy']);

// CarreraPlan
Route::get('/horarios/uCPlan', [CarreraPlanController::class, 'index']);
Route::get('/horarios/uCPlan/{id}', [CarreraPlanController::class, 'obtenerCarreraPlanPorIdCarrera']);
Route::get('/horarios/uCPlan/{id}/relaciones', [CarreraPlanController::class, 'obtenerCarreraPlanPorIdCarreraConMaterias']);
Route::get('/horarios/uCPlan/{id}', [CarreraPlanController::class, 'obtenerCarreraPlanPorIdPlan']);
Route::post('/horarios/uCPlan/guardar', [CarreraPlanController::class, 'store']);
Route::delete('/horarios/uCPlan/eliminar/{id}', [CarreraPlanController::class, 'eliminarCarreraPlanPorIdCarreraYPlan']);


// UnidadCurricular
Route::get('/horarios/unidadCurricular', [UnidadCurricularController::class, 'index']);
Route::get('/horarios/unidadCurricular/{id}', [UnidadCurricularController::class, 'show']);
Route::post('/horarios/unidadCurricular/guardar', [UnidadCurricularController::class, 'store']);
Route::put('/horarios/unidadCurricular/actualizar/{id}', [UnidadCurricularController::class, 'update']);
Route::delete('/horarios/unidadCurricular/eliminar/{id}', [UnidadCurricularController::class, 'destroy']);

// CarreraUC
Route::get('/horarios/carreraUC', [CarreraUCController::class, 'index']);
Route::get('/horarios/carreraUC/idCarrera/{id}', [CarreraUCController::class, 'show']);
Route::get('/horarios/carreraUC/idUC/{id}', [CarreraUCController::class, 'show']);
Route::post('/horarios/carreraUC/guardar', [CarreraUCController::class, 'store']);
Route::delete('/horarios/carreraUC/eliminar/idCarrera/{id}', [CarreraUCController::class, 'destroy']);
Route::delete('/horarios/carreraUC/eliminar/idUC/{id}', [CarreraUCController::class, 'destroy']);


// Inscipcion
Route::get('/horarios/inscripcion', [InscripcionController::class, 'index']);
Route::get('/horarios/inscripcion/{id}', [InscripcionController::class, 'show']);
Route::post('/horarios/inscripcion/guardar', [InscripcionController::class, 'store']);
Route::put('/horarios/inscripcion/actualizar/{id}', [InscripcionController::class, 'update']);
Route::delete('/horarios/inscripcion/eliminar/{id}', [InscripcionController::class, 'destroy']);

Route::get('/horarios/alumnos', [AlumnoController::class, 'index']);

// AlumnoGrado
Route::get('/horarios/alumnoGrados', [AlumnoGradoController::class, 'index']);
Route::get('/horarios/alumnoGrados/relaciones', [AlumnoGradoController::class, 'indexConRelaciones']);
Route::get('/horarios/alumnoGrados/alumno/{id_alumno}', [AlumnoGradoController::class, 'showByAlumno']);
Route::get('/horarios/alumnoGrados/grado/relaciones/{id_grado}', [AlumnoGradoController::class, 'showByGradoConRelaciones']);
Route::get('/horarios/alumnoGrados/grado/{id_grado}', [AlumnoGradoController::class, 'showByGrado']);
Route::post('/horarios/alumnoGrados/guardar/{id_alumno}/{id_grado}', [AlumnoGradoController::class, 'store']);
Route::get('/horarios/alumnoGrados/asignarIngresantes', [AlumnoGradoController::class, 'asignarAlumnosACarrerasIngresante']);
Route::get('/horarios/alumnoGrados/asignar', [AlumnoGradoController::class, 'asignarAlumnosACarreras']);
Route::put('/horarios/alumnoGrados/actualizar/{id_alumno}/{id_grado_actual}/{id_grado}', [AlumnoGradoController::class, 'cambiarGrado']);
Route::delete('/horarios/alumnoGrados/{id_alumno}/{id_grado}', [AlumnoGradoController::class, 'destroy']);


// Docente
Route::get('/horarios/docentes', [DocenteController::class, 'index']);
Route::get('/horarios/docentes/{id}', [DocenteController::class, 'show']);
Route::post('/horarios/docentes/guardar', [DocenteController::class, 'store']);
Route::put('/horarios/docentes/actualizar/{id}', [DocenteController::class, 'update']);
Route::delete('/horarios/docentes/eliminar/{id}', [DocenteController::class, 'destroy']);

// CarreraGrado
Route::get('/horarios/carreraGrados', [CarreraGradoController::class, 'index']);
Route::get('/horarios/carreraGrados/{id_carreraGrado}', [CarreraGradoController::class, 'showByCarreraGrado']);
Route::get('/horarios/carreraGrados/materias/{alumno}', [CarreraGradoController::class, 'showGradosByMaterias']);

Route::get('/horarios/carreraGrados/carrera/{id_carrera}', [CarreraGradoController::class, 'showByCarrera']);
Route::get('/horarios/carreraGrados/carrera/SinUC/{id_carrera}', [CarreraGradoController::class, 'showByCarreraSinUC']);
Route::get('/horarios/carreraGrados/grado/{id_grado}', [CarreraGradoController::class, 'showByGrado']);
Route::post('/horarios/carreraGrados/guardar/{id_carrera}/{id_grado}', [CarreraGradoController::class, 'store']);
Route::delete('/horarios/carreraGrados/eliminar/{id_carrera_grado}', [CarreraGradoController::class, 'destroy']);


// Logs
Route::get('/horarios/logs', [LogModificacionEliminacionController::class, 'index']);
Route::post('/horarios/logs', [LogModificacionEliminacionController::class, 'store']);

// Cursada
Route::get('/horarios/cursadas', [CursadaController::class, 'index']);
Route::get('/horarios/cursadas/{id}', [CursadaController::class, 'show']);
Route::post('/horarios/cursadas/guardar', [CursadaController::class, 'store']);
Route::put('/horarios/cursadas/actualizar/{id}', [CursadaController::class, 'update']);
Route::delete('/horarios/cursadas/eliminar/{id}', [CursadaController::class, 'destroy']);
