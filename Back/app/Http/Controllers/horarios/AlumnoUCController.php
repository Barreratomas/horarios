<?php

namespace App\Http\Controllers\horarios;

use App\Models\AlumnoUC;
use App\Http\Controllers\Controller;
use App\Services\horarios\AlumnoUCService;
use Illuminate\Http\Request;

class AlumnoUCController extends Controller
{
    protected $alumnoUCService;

    public function __construct(AlumnoUCService $alumnoUCService)
    {
        $this->alumnoUCService = $alumnoUCService;
    }

    //------------------------------------------------------------------------------------------------------------------
    // Swagger

    /**
     * @OA\Get(
     *     path="/api/horarios/alumnoUC",
     *     tags={"AlumnoUC"},
     *     summary="Obtener todos los alumnosUC",
     *     description="Retorna un array de alumnosUC",
     *     @OA\Response(
     *         response=200,
     *         description="Operación exitosa"
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Error al obtener los alumnosUC"
     *     )
     * )
     */
    public function index()
    {
        return $this->alumnoUCService->obtenerTodosAlumnoUC();
    }

    /**
     * @OA\Get(
     *     path="/api/horarios/alumnoUC/alumno/{id}",
     *     tags={"AlumnoUC"},
     *     summary="Obtener un alumnoUC por ID de alumno",
     *     description="Retorna un alumnoUC",
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
     *         description="AlumnoUC no encontrada"
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Error al obtener el alumnoUC"
     *     )
     * )
     */
    public function obtenerPorIdAlumno($id)
    {
        return $this->alumnoUCService->obtenerAlumnoUCPorIdAlumno($id);
    }

    /**
     * @OA\Get(
     *     path="/api/horarios/alumnoUC/uc/{id}",
     *     tags={"AlumnoUC"},
     *     summary="Obtener un alumnoUC por ID de UC",
     *     description="Retorna un alumnoUC",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID de la UC",
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
     *         description="AlumnoUC no encontrada"
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Error al obtener el alumnoUC"
     *     )
     * )
     */
    public function obtenerPorIdUC($id)
    {
        return $this->alumnoUCService->obtenerAlumnoUCPorIdUC($id);
    }

    /**
     * @OA\Post(
     *     path="/api/horarios/alumnoUC/guardar",
     *     tags={"AlumnoUC"},
     *     summary="Guardar un alumnoUC",
     *     description="Retorna el alumnoUC guardado",
     *     @OA\RequestBody(
     *         @OA\JsonContent(ref="#/components/schemas/AlumnoUC")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Operación exitosa"
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Error al guardar el alumnoUC"
     *     )
     * )
     */
    public function store(Request $request)
    {
        return $this->alumnoUCService->guardarAlumnoUC($request);
    }

    /**
     * @OA\Delete(
     *     path="/api/horarios/alumnoUC/eliminar/alumno/{id}",
     *     tags={"AlumnoUC"},
     *     summary="Eliminar un alumnoUC por ID de alumno",
     *     description="Retorna el alumnoUC eliminado",
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
     *         description="AlumnoUC no encontrada"
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Error al eliminar el alumnoUC"
     *     )
     * )
     */
    public function eliminarPorIdAlumno($id)
    {
        return $this->alumnoUCService->eliminarAlumnoUCPorIdAlumno($id);
    }

    /**
     * @OA\Delete(
     *     path="/api/horarios/alumnoUC/eliminar/uc/{id}",
     *     tags={"AlumnoUC"},
     *     summary="Eliminar un alumnoUC por ID de UC",
     *     description="Retorna el alumnoUC eliminado",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID de la UC",
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
     *         description="AlumnoUC no encontrada"
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Error al eliminar el alumnoUC"
     *     )
     * )
     */
    public function eliminarPorIdUC($id)
    {
        return $this->alumnoUCService->eliminarAlumnoUCPorIdUC($id);
    }
}
