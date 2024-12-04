<?php

namespace App\Http\Controllers\horarios;

use App\Http\Requests\horarios\HorarioDocenteRequest;
use App\Http\Requests\horarios\HorarioRequest;
use App\Models\Carrera;
use App\Models\horarios\Disponibilidad;
use App\Models\horarios\Horario;
use App\Models\horarios\Grado;
use Illuminate\Http\Request;
use App\Services\horarios\HorarioService;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use App\Http\Controllers\Controller;

class HorarioController extends Controller
{
    protected $horarioService;

    public function __construct(HorarioService $horarioService)
    {
        $this->horarioService = $horarioService;
    }

    /**
     * @OA\Get(
     *     path="/horarios",
     *     summary="Obtener todos los horarios",
     *     tags={"Horarios"},
     *     @OA\Response(
     *         response=200,
     *         description="Lista de horarios obtenida correctamente"
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Error al obtener los horarios"
     *     )
     * )
     */
    public function index()
    {
        return $this->horarioService->obtenerTodosHorarios();
    }

    /**
     * @OA\Get(
     *     path="/horarios/{id}",
     *     summary="Obtener un horario por ID",
     *     tags={"Horarios"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID del horario",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Horario obtenido correctamente"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Horario no encontrado"
     *     )
     * )
     */
    public function show($id)
    {
        return $this->horarioService->obtenerHorarioPorId($id);
    }

    /**
     * @OA\Post(
     *     path="/horarios",
     *     summary="Crear un nuevo horario",
     *     tags={"Horarios"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="grado_id", type="integer", description="ID del grado"),
     *             @OA\Property(property="unidad_curricular_id", type="integer", description="ID de la unidad curricular"),
     *             @OA\Property(property="aula_id", type="integer", description="ID del aula"),
     *             @OA\Property(property="dia", type="string", description="Día de la semana"),
     *             @OA\Property(property="hora_inicio", type="string", format="time", description="Hora de inicio"),
     *             @OA\Property(property="hora_fin", type="string", format="time", description="Hora de finalización")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Horario creado correctamente"
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Datos inválidos"
     *     )
     * )
     */
    public function store(HorarioRequest $request)
    {
        return $this->horarioService->guardarHorarios($request);
    }

    /**
     * @OA\Put(
     *     path="/horarios/{id}",
     *     summary="Actualizar un horario",
     *     tags={"Horarios"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID del horario",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="grado_id", type="integer", description="ID del grado"),
     *             @OA\Property(property="unidad_curricular_id", type="integer", description="ID de la unidad curricular"),
     *             @OA\Property(property="aula_id", type="integer", description="ID del aula"),
     *             @OA\Property(property="dia", type="string", description="Día de la semana"),
     *             @OA\Property(property="hora_inicio", type="string", format="time", description="Hora de inicio"),
     *             @OA\Property(property="hora_fin", type="string", format="time", description="Hora de finalización")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Horario actualizado correctamente"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Horario no encontrado"
     *     )
     * )
     */
    public function update(HorarioRequest $request, $id)
    {
        return $this->horarioService->actualizarHorarios($request, $id);
    }

    /**
     * @OA\Delete(
     *     path="/horarios/{id}",
     *     summary="Eliminar un horario",
     *     tags={"Horarios"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID del horario",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Horario eliminado correctamente"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Horario no encontrado"
     *     )
     * )
     */
    public function destroy($id)
    {
        return $this->horarioService->eliminarHorarios($id);
    }



 //-----------------------------------------------------------------------------------------------------
    // Swagger


    /**
     * @OA\Get(
     *     path="/api/horarios",
     *     tags={"Horarios"},
     *     summary="Obtener todos los horarios",
     *     description="Retorna un array de horarios",
     *     @OA\Response(
     *         response=200,
     *         description="Operación exitosa"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="No se encontraron horarios"
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Error al obtener los horarios"
     *     )
     * )
     */
    public function obtenerTodosHorariosSwagger()
    {
       return $this->horarioService->obtenerTodosHorariosSwagger();
    }


    /**
     * @OA\Get(
     *     path="/api/horarios/{id}",
     *     tags={"Horarios"},
     *     summary="Obtener horario por id",
     *     description="Retorna un horario",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID del horario",
     *         required=true,
     *         @OA\Schema(
     *             type="integer"
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Operación exitosa"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="No se encontró el horario"
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Error al obtener el horario"
     *     )
     * )
     */
    public function obtenerHorarioPorIdSwagger($id)
    {
        return $this->horarioService->obtenerHorarioPorIdSwagger($id);
    }

    /**
     * @OA\Post(
     *     path="/api/horarios/guardar",
     *     tags={"Horarios"},
     *     summary="Guardar horario",
     *     description="Guardar un nuevo horario",
     *     @OA\RequestBody(
     *         description="Datos del horario",
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/Horario")
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Horario guardado correctamente"
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Error al guardar el horario"
     *     )
     * )
     */
    public function guardarHorariosSwagger(Request $request)
    {
        return $this->horarioService->guardarHorariosSwagger($request);
    }

    /**
     * @OA\Put(
     *     path="/api/horarios/actualizar/{id}",
     *     tags={"Horarios"},
     *     summary="Actualizar horario",
     *     description="Actualizar un horario existente",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID del horario",
     *         required=true,
     *         @OA\Schema(
     *             type="integer"
     *         )
     *     ),
     *     @OA\RequestBody(
     *         description="Datos del horario",
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/Horario")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Horario actualizado correctamente"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="No se encontró el horario"
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Error al actualizar el horario"
     *     )
     * )
     */
    public function actualizarHorariosSwagger(Request $request, $id)
    {
        return $this->horarioService->actualizarHorariosSwagger($request, $id);
    }

    /**
     * @OA\Delete(
     *     path="/api/horarios/eliminar/{id}",
     *     tags={"Horarios"},
     *     summary="Eliminar horario",
     *     description="Eliminar un horario existente",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID del horario",
     *         required=true,
     *         @OA\Schema(
     *             type="integer"
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Horario eliminado correctamente"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="No se encontró el horario"
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Error al eliminar el horario"
     *     )
     * )
     */
    public function eliminarHorariosSwagger($id)
    {
        return $this->horarioService->eliminarHorariosSwagger($id);
    }

}