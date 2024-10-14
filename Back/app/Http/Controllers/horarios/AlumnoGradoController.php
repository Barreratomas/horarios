<?php

namespace App\Http\Controllers\horarios;

use App\Models\AlumnoGrado;
use App\Http\Controllers\Controller;
use App\Services\horarios\AlumnoGradoService;
use Illuminate\Http\Request;

class AlumnoGradoController extends Controller
{
    private $alumnoGradoService;

    public function __construct(AlumnoGradoService $alumnoGradoService)
    {
        $this->alumnoGradoService = $alumnoGradoService;
    }

    //-------------------------------------------------------------------------------------------------------------
    // Swagger Documentation

    /**
     * @OA\Get(
     *      path="/api/horarios/alumnoGrados",
     *      summary="Obtener todas las relaciones Alumno-Grado",
     *      description="Devuelve todas las relaciones Alumno-Grado",
     *      operationId="getAlumnoGrados",
     *      tags={"AlumnoGrado"},
     *      @OA\Response(
     *          response=200,
     *          description="Relaciones obtenidas correctamente",
     *          @OA\JsonContent(
     *              type="array",
     *              @OA\Items(ref="#/components/schemas/AlumnoGrado")
     *          )
     *      ),
     *      @OA\Response(
     *          response=500,
     *          description="Error al obtener las relaciones"
     *      )
     * )
     */
    public function index()
    {
        return $this->alumnoGradoService->obtenerTodosAlumnoGrado();
    }

    /**
     * @OA\Post(
     *      path="/api/horarios/alumnoGrados/guardar",
     *      summary="Guardar una nueva relación Alumno-Grado",
     *      description="Crea una nueva relación Alumno-Grado",
     *      operationId="guardarAlumnoGrado",
     *      tags={"AlumnoGrado"},
     *      @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(ref="#/components/schemas/AlumnoGrado")
     *      ),
     *      @OA\Response(
     *          response=201,
     *          description="Relación creada correctamente",
     *          @OA\JsonContent(ref="#/components/schemas/AlumnoGrado")
     *      ),
     *      @OA\Response(
     *          response=400,
     *          description="Error al crear la relación"
     *      )
     * )
     */
    public function store(Request $request)
    {
        return $this->alumnoGradoService->guardarAlumnoGrado($request);
    }

    /**
     * @OA\Get(
     *      path="/api/horarios/alumnoGrados/alumno/{id_alumno}",
     *      summary="Obtener relación Alumno-Grado por ID de alumno",
     *      description="Devuelve la relación Alumno-Grado por ID de alumno",
     *      operationId="getAlumnoGradoPorIdAlumno",
     *      tags={"AlumnoGrado"},
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
     *          @OA\JsonContent(ref="#/components/schemas/AlumnoGrado")
     *      ),
     *      @OA\Response(
     *          response=404,
     *          description="AlumnoGrado no encontrado"
     *      )
     * )
     */
    public function showByAlumno($id_alumno)
    {
        return $this->alumnoGradoService->obtenerAlumnoGradoPorIdAlumno($id_alumno);
    }

    /**
     * @OA\Delete(
     *      path="/api/horarios/alumnoGrados/eliminar/{id_alumno}",
     *      summary="Eliminar una relación Alumno-Grado",
     *      description="Elimina una relación Alumno-Grado por id_alumno",
     *      operationId="eliminarAlumnoGrado",
     *      tags={"AlumnoGrado"},
     *      @OA\Parameter(
     *          name="id_alumno",
     *          in="path",
     *          description="Id del alumno",
     *          required=true,
     *          @OA\Schema(type="integer")
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Relación eliminada correctamente"
     *      ),
     *      @OA\Response(
     *          response=404,
     *          description="AlumnoGrado no encontrado"
     *      ),
     *      @OA\Response(
     *          response=500,
     *          description="Error al eliminar la relación"
     *      )
     * )
     */
    public function destroy($id_alumno)
    {
        return $this->alumnoGradoService->eliminarAlumnoGradoPorIdAlumno($id_alumno);
    }
}
