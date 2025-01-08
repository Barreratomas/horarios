<?php

namespace App\Http\Controllers;

use app\Models\Alumno;
use App\Http\Controllers\Controller;
use App\Services\AlumnoService;
// use app\Services\AlumnoService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;//+

class AlumnoController extends Controller
{
    protected $alumnoService;

    public function __construct(AlumnoService $alumnoService)
    {

        $this->alumnoService = $alumnoService;
    }

    /**
     * @OA\Get(
     *     path="/api/alumnos",
     *     tags={"Alumno"},
     *     summary="Obtener todos los alumnos",
     *     description="Retorna un array de alumnos",
     *     @OA\Response(
     *         response=200,
     *         description="Operación exitosa"
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Error al obtener los alumnos"
     *     )
     * )
     */
    public function index()
    {
        return $this->alumnoService->obtenerTodosAlumnos();
    }

    /**
     * @OA\Get(
     *     path="/api/alumnos/{id}",
     *     tags={"Alumno"},
     *     summary="Obtener un alumno por ID",
     *     description="Retorna un alumno",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID del alumno",
     *         required=true,
     *         @OA\Schema(
     *             type="integer",
     *             format="int64"
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Operación exitosa"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="No se encontró el alumno"
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Error al obtener el alumno"
     *     )
     * )
     */
    public function show($id)
    {
        return $this->alumnoService->obtenerAlumnoPorId($id);
    }

    /**
     * @OA\Post(
     *     path="/api/alumnos/guardar",
     *     tags={"Alumno"},
     *     summary="Guardar un alumno",
     *     description="Guardar un alumno",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/Alumno")
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Operación exitosa"
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Error al guardar el alumno"
     *     )
     * )
     */
    public function store(Request $request)
    {
        return $this->alumnoService->guardarAlumno($request);
    }

    /**
     * @OA\Put(
     *     path="/api/alumnos/actualizar/{id}",
     *     tags={"Alumno"},
     *     summary="Actualizar un alumno",
     *     description="Actualizar un alumno",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID del alumno",
     *         required=true,
     *         @OA\Schema(
     *             type="integer",
     *             format="int64"
     *         )
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/Alumno")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Operación exitosa"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="No se encontró el alumno"
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Error al actualizar el alumno"
     *     )
     * )
     */
    public function update(Request $request, $id)
    {
        return $this->alumnoService->actualizarAlumno($request, $id);
    }

    /**
     * @OA\Delete(
     *     path="/api/alumnos/{id}",
     *     tags={"Alumno"},
     *     summary="Eliminar un alumno",
     *     description="Eliminar un alumno",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID del alumno",
     *         required=true,
     *         @OA\Schema(
     *             type="integer",
     *             format="int64"
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Operación exitosa"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="No se encontró el alumno"
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Error al eliminar el alumno"
     *     )
     * )
     */
    public function destroy($id)
    {
        return $this->alumnoService->eliminarAlumnoPorId($id);
    }
}