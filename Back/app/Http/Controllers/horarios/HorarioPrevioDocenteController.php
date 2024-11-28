<?php

namespace App\Http\Controllers\horarios;

use App\Http\Requests\horarios\HorarioPrevioDocenteRequest;
use App\Services\horarios\HorarioPrevioDocenteService;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

/**
 * @OA\Tag(
 *     name="HorarioPrevioDocente",
 *     description="Gestión de horarios previos de docentes"
 * )
 */
class HorarioPrevioDocenteController extends Controller
{
    protected $horarioPrevioDocenteService;

    public function __construct(HorarioPrevioDocenteService $horarioPrevioDocenteService)
    {
        $this->horarioPrevioDocenteService = $horarioPrevioDocenteService;
    }

    /**
     * @OA\Get(
     *      path="/api/horarios/horariosPreviosDocentes",
     *      summary="Obtener todos los horarios previos de docentes",
     *      description="Devuelve una lista de horarios previos de docentes",
     *      operationId="getHorariosPreviosDocentes",
     *      tags={"HorarioPrevioDocente"},
     *      @OA\Response(
     *          response=200,
     *          description="Lista de horarios previos de docentes"
     *      ),
     *      @OA\Response(
     *          response=500,
     *          description="Error al obtener los horarios"
     *      )
     * )
     */
    public function index()
    {
        return $this->horarioPrevioDocenteService->obtenerTodosHorariosPreviosDocentes();
    }

/**
 * @OA\Get(
 *     path="/api/horarios/horariosPreviosDocentes/{id}",
 *     summary="Obtener un horario previo de docente por ID",
 *     description="Devuelve un horario previo de docente por su ID",
 *     operationId="getHorarioPrevioDocentePorId",
 *     tags={"HorarioPrevioDocente"},
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         description="ID del horario previo de docente",
 *         required=true,
 *         @OA\Schema(
 *             type="integer"
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Horario previo de docente obtenido correctamente"
 *     ),
 *     @OA\Response(
 *         response=404,
 *         description="No se encontró el horario previo"
 *     ),
 *     @OA\Response(
 *         response=500,
 *         description="Error al obtener el horario previo"
 *     )
 * )
 */
public function show($id_docente) 
{
    return $this->horarioPrevioDocenteService->obtenerHorarioPrevioDocentePorIdDocente($id_docente); // Llamo al método con el nombre correcto
}


    /**
     * @OA\Post(
     *     path="/api/horarios/horariosPreviosDocentes",
     *     summary="Crear un horario previo para un docente",
     *     description="Crea un nuevo horario previo para un docente",
     *     operationId="crearHorarioPrevioDocente",
     *     tags={"HorarioPrevioDocente"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/HorarioPrevioDocenteRequest")
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Horario previo de docente creado correctamente"
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Error al crear el horario previo"
     *     )
     * )
     */
    public function store(HorarioPrevioDocenteRequest $request)
    {
        $id_docente = $request->input('id_docente');
        $dia = $request->input('dia');
        $hora = $request->input('hora');

        return $this->horarioPrevioDocenteService->guardarHorarioPrevioDocente($id_docente, $dia, $hora);
    }

    /**
     * @OA\Put(
     *     path="/api/horarios/horariosPreviosDocentes/{id}",
     *     summary="Actualizar un horario previo de docente",
     *     description="Actualiza un horario previo de docente existente",
     *     operationId="actualizarHorarioPrevioDocente",
     *     tags={"HorarioPrevioDocente"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID del horario previo de docente",
     *         required=true,
     *         @OA\Schema(
     *             type="integer"
     *         )
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/HorarioPrevioDocenteRequest")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Horario previo de docente actualizado correctamente"
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Error al actualizar el horario previo"
     *     )
     * )
     */
    public function update(HorarioPrevioDocenteRequest $request, $id_h_p_d)
    {
        $dia = $request->input('dia');
        $hora = $request->input('hora');

        return $this->horarioPrevioDocenteService->actualizarHorarioPrevioDocente($id_h_p_d, $dia, $hora);
    }

    /**
     * @OA\Delete(
     *     path="/api/horarios/horariosPreviosDocentes/{id}",
     *     summary="Eliminar un horario previo de docente",
     *     description="Elimina un horario previo de docente por su ID",
     *     operationId="eliminarHorarioPrevioDocente",
     *     tags={"HorarioPrevioDocente"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID del horario previo de docente",
     *         required=true,
     *         @OA\Schema(
     *             type="integer"
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Horario previo eliminado correctamente"
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Error al eliminar el horario previo"
     *     )
     * )
     */
    public function destroy($id_h_p_d)
    {
        return $this->horarioPrevioDocenteService->eliminarHorarioPrevioDocentePorId($id_h_p_d);
    }
}
