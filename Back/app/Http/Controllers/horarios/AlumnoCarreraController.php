<?php

namespace App\Http\Controllers\horarios;

use app\Models\AlumnoCarrera;
use App\Http\Controllers\Controller;
use App\Services\horarios\AlumnoCarreraService;
use Illuminate\Http\Request;

class AlumnoCarreraController extends Controller
{
    protected $alumnoCarreraService;

    public function __construct(AlumnoCarreraService $alumnoCarreraService)
    {
        $this->alumnoCarreraService = $alumnoCarreraService;
    }

    //-------------------------------------------------------------------------------------------------------------
    // Swagger Documentation

    /**
     * @OA\Get(
     *      path="/api/horarios/alumnoCarreras",
     *      summary="Obtener todas las relaciones Alumno-Carrera",
     *      description="Devuelve todas las relaciones Alumno-Carrera",
     *      operationId="getAlumnoCarreras",
     *      tags={"AlumnoCarrera"},
     *      @OA\Response(
     *          response=200,
     *          description="Relaciones obtenidas correctamente",
     *          @OA\JsonContent(
     *              type="array",
     *              @OA\Items(ref="#/components/schemas/AlumnoCarrera")
     *          )
     *      ),
     *      @OA\Response(
     *          response=500,
     *          description="Error al obtener las relaciones"
     *      )
     * )
     */
    public function obtenerTodos()
    {
        return $this->alumnoCarreraService->obtenerTodosAlumnoCarrera();
    }

    /**
     * @OA\Get(
     *      path="/api/horarios/alumnoCarreras/{id_alumno}",
     *      summary="Obtener relaciones Alumno-Carrera por ID de alumno",
     *      description="Devuelve la relación Alumno-Carrera correspondiente al ID de un alumno",
     *      operationId="getAlumnoCarreraPorIdAlumno",
     *      tags={"AlumnoCarrera"},
     *      @OA\Parameter(
     *          name="id_alumno",
     *          in="path",
     *          description="ID del alumno",
     *          required=true,
     *          @OA\Schema(type="integer")
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Relación obtenida correctamente",
     *          @OA\JsonContent(ref="#/components/schemas/AlumnoCarrera")
     *      ),
     *      @OA\Response(
     *          response=404,
     *          description="Relación no encontrada"
     *      )
     * )
     */
    public function obtenerPorIdAlumno($id_alumno)
    {
        return $this->alumnoCarreraService->obtenerAlumnoCarreraPorIdAlumno($id_alumno);
    }

    /**
     * @OA\Post(
     *      path="/api/horarios/alumnoCarreras",
     *      summary="Guardar una nueva relación Alumno-Carrera",
     *      description="Crea una nueva relación Alumno-Carrera",
     *      operationId="guardarAlumnoCarrera",
     *      tags={"AlumnoCarrera"},
     *      @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(ref="#/components/schemas/AlumnoCarrera")
     *      ),
     *      @OA\Response(
     *          response=201,
     *          description="Relación creada correctamente",
     *          @OA\JsonContent(ref="#/components/schemas/AlumnoCarrera")
     *      ),
     *      @OA\Response(
     *          response=400,
     *          description="Error al crear la relación"
     *      )
     * )
     */
    public function guardar(Request $request)
    {
        return $this->alumnoCarreraService->guardarAlumnoCarrera($request);
    }

    /**
     * @OA\Delete(
     *      path="/api/horarios/alumnoCarreras/{id_alumno}",
     *      summary="Eliminar una relación Alumno-Carrera por ID de alumno",
     *      description="Elimina una relación Alumno-Carrera correspondiente al ID de un alumno",
     *      operationId="eliminarAlumnoCarreraPorIdAlumno",
     *      tags={"AlumnoCarrera"},
     *      @OA\Parameter(
     *          name="id_alumno",
     *          in="path",
     *          description="ID del alumno",
     *          required=true,
     *          @OA\Schema(type="integer")
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Relación eliminada correctamente"
     *      ),
     *      @OA\Response(
     *          response=404,
     *          description="Relación no encontrada"
     *      )
     * )
     */
    public function eliminarPorIdAlumno($id_alumno)
    {
        return $this->alumnoCarreraService->eliminarAlumnoCarreraPorIdAlumno($id_alumno);
    }

    /**
     * @OA\Delete(
     *      path="/api/horarios/alumnoCarreras/{id_carrera}",
     *      summary="Eliminar una relación Alumno-Carrera por ID de carrera",
     *      description="Elimina una relación Alumno-Carrera correspondiente al ID de una carrera",
     *      operationId="eliminarAlumnoCarreraPorIdCarrera",
     *      tags={"AlumnoCarrera"},
     *      @OA\Parameter(
     *          name="id_carrera",
     *          in="path",
     *          description="ID de la carrera",
     *          required=true,
     *          @OA\Schema(type="integer")
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Relación eliminada correctamente"
     *      ),
     *      @OA\Response(
     *          response=404,
     *          description="Relación no encontrada"
     *      )
     * )
     */
    public function eliminarPorIdCarrera($id_carrera)
    {
        return $this->alumnoCarreraService->eliminarAlumnoCarreraPorIdCarrera($id_carrera);
    }
}
