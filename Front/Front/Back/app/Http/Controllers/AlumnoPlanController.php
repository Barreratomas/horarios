<?php

namespace App\Http\Controllers;

use App\Models\AlumnoPlan;
use App\Http\Controllers\Controller;
use App\Services\AlumnoPlanService;
use Illuminate\Http\Request;

class AlumnoPlanController extends Controller
{
    private $alumnoPlanService;

    public function __construct(AlumnoPlanService $alumnoPlanService)
    {
        $this->alumnoPlanService = $alumnoPlanService;
    }

    //-------------------------------------------------------------------------------------------------------------
    // Swagger Documentation

    /**
     * @OA\Get(
     *      path="/api/horarios/alumnoPlanes",
     *      summary="Obtener todas las relaciones Alumno-Plan",
     *      description="Devuelve todas las relaciones Alumno-Plan",
     *      operationId="getAlumnoPlanes",
     *      tags={"AlumnoPlan"},
     *      @OA\Response(
     *          response=200,
     *          description="Relaciones obtenidas correctamente",
     *          @OA\JsonContent(
     *              type="array",
     *              @OA\Items(ref="#/components/schemas/AlumnoPlan")
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
        return $this->alumnoPlanService->obtenerTodosAlumnoPlan();
    }

    /**
     * @OA\Post(
     *      path="/api/horarios/alumnoPlanes/guardar",
     *      summary="Guardar una nueva relación Alumno-Plan",
     *      description="Crea una nueva relación Alumno-Plan",
     *      operationId="guardarAlumnoPlan",
     *      tags={"AlumnoPlan"},
     *      @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(ref="#/components/schemas/AlumnoPlan")
     *      ),
     *      @OA\Response(
     *          response=201,
     *          description="Relación creada correctamente",
     *          @OA\JsonContent(ref="#/components/schemas/AlumnoPlan")
     *      ),
     *      @OA\Response(
     *          response=400,
     *          description="Error al crear la relación"
     *      )
     * )
     */
    public function store(Request $request)
    {
        return $this->alumnoPlanService->guardarAlumnoPlan($request);
    }

    /**
     * @OA\Get(
     *      path="/api/horarios/alumnoPlanes/alumno/{id_alumno}",
     *      summary="Obtener relación Alumno-Plan por ID de alumno",
     *      description="Devuelve la relación Alumno-Plan por ID de alumno",
     *      operationId="getAlumnoPlanPorIdAlumno",
     *      tags={"AlumnoPlan"},
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
     *          @OA\JsonContent(ref="#/components/schemas/AlumnoPlan")
     *      ),
     *      @OA\Response(
     *          response=404,
     *          description="AlumnoPlan no encontrado"
     *      )
     * )
     */
    public function showByAlumno($id_alumno)
    {
        return $this->alumnoPlanService->obtenerAlumnoPlanPorIdAlumno($id_alumno);
    }

    /**
     * @OA\Get(
     *      path="/api/horarios/alumnoPlanes/plan/{id_plan}",
     *      summary="Obtener relación Alumno-Plan por ID de plan",
     *      description="Devuelve la relación Alumno-Plan por ID de plan",
     *      operationId="getAlumnoPlanPorIdPlan",
     *      tags={"AlumnoPlan"},
     *      @OA\Parameter(
     *          name="id_plan",
     *          in="path",
     *          description="ID del plan de estudio",
     *          required=true,
     *          @OA\Schema(type="integer")
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Relación obtenida correctamente",
     *          @OA\JsonContent(ref="#/components/schemas/AlumnoPlan")
     *      ),
     *      @OA\Response(
     *          response=404,
     *          description="AlumnoPlan no encontrado"
     *      )
     * )
     */
    public function showByPlan($id_plan)
    {
        return $this->alumnoPlanService->obtenerAlumnoPlanPorIdPlan($id_plan);
    }

    /**
     * @OA\Delete(
     *      path="/api/horarios/alumnoPlanes/eliminar/alumno/{id_alumno}",
     *      summary="Eliminar una relación Alumno-Plan por ID de alumno",
     *      description="Elimina una relación Alumno-Plan por ID de alumno",
     *      operationId="eliminarAlumnoPlanPorIdAlumno",
     *      tags={"AlumnoPlan"},
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
     *          description="AlumnoPlan no encontrado"
     *      ),
     *      @OA\Response(
     *          response=500,
     *          description="Error al eliminar la relación"
     *      )
     * )
     */
    public function destroyByAlumno($id_alumno)
    {
        return $this->alumnoPlanService->eliminarAlumnoPlanPorIdAlumno($id_alumno);
    }

    /**
     * @OA\Delete(
     *      path="/api/horarios/alumnoPlanes/eliminar/plan/{id_plan}",
     *      summary="Eliminar una relación Alumno-Plan por ID de plan",
     *      description="Elimina una relación Alumno-Plan por ID de plan",
     *      operationId="eliminarAlumnoPlanPorIdPlan",
     *      tags={"AlumnoPlan"},
     *      @OA\Parameter(
     *          name="id_plan",
     *          in="path",
     *          description="ID del plan de estudio",
     *          required=true,
     *          @OA\Schema(type="integer")
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Relación eliminada correctamente"
     *      ),
     *      @OA\Response(
     *          response=404,
     *          description="AlumnoPlan no encontrado"
     *      ),
     *      @OA\Response(
     *          response=500,
     *          description="Error al eliminar la relación"
     *      )
     * )
     */
    public function destroyByPlan($id_plan)
    {
        return $this->alumnoPlanService->eliminarAlumnoPlanPorIdPlan($id_plan);
    }
}