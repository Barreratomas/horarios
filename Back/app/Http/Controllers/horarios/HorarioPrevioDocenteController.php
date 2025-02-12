<?php

namespace App\Http\Controllers\horarios;

use App\Http\Requests\horarios\HorarioPrevioDocenteRequest;
use App\Services\horarios\HorarioPrevioDocenteService;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\LogsRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\LogModificacionEliminacionController;

/**
 * @OA\Tag(
 *     name="HorarioPrevioDocente",
 *     description="Gestión de horarios previos de docentes"
 * )
 */
class HorarioPrevioDocenteController extends Controller
{
    protected $horarioPrevioDocenteService;
    protected $logModificacionEliminacionController;


    public function __construct(HorarioPrevioDocenteService $horarioPrevioDocenteService,  LogModificacionEliminacionController $logModificacionEliminacionController)
    {
        $this->horarioPrevioDocenteService = $horarioPrevioDocenteService;
        $this->logModificacionEliminacionController = $logModificacionEliminacionController;
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
    public function show($id_h_p_d)
    {
        return $this->horarioPrevioDocenteService->obtenerHorarioPrevioDocente($id_h_p_d);
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
     *         @OA\JsonContent(ref="#/components/schemas/HorarioPrevioDocente")
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
        $dias = $request->input('dia');
        $horas = $request->input('hora');

        // Validar que el número de días y horas coincida
        if (count($dias) !== count($horas)) {
            return response()->json([
                'error' => 'El número de días y horas no coincide.',
            ], 422);
        }

        // Llamamos al servicio para guardar los horarios previos del docente
        return $this->horarioPrevioDocenteService->guardarHorarioPrevioDocente($id_docente, $dias, $horas);
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
     *         @OA\JsonContent(ref="#/components/schemas/HorarioPrevioDocente")
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
public function update(Request $request, $id_h_p_d)
{

    $dias = $request->input('dia');
    $horas = $request->input('hora');
    $detalle = $request->input('detalles');
    $usuario = $request->input('usuario');
    DB::beginTransaction();

    try {
        $horarioPrevioResponse = $this->horarioPrevioDocenteService->actualizarHorarioPrevioDocente($id_h_p_d, $dias, $horas);
        $horarioPrevio = $horarioPrevioResponse->getData();

        if ($horarioPrevioResponse->getStatusCode() === 200) {
            $accion = "Actualización del horario previo docente (id: " . $id_h_p_d . ")";


            $this->logModificacionEliminacionController->store($accion, $usuario, $detalle);
            DB::commit();

            return response()->json(['success' => 'Horario Previo actualizado correctamente.'], 200);
        } else {
            DB::rollBack();
            return response()->json(['error' => $horarioPrevio['error'] ?? 'Error desconocido.'], 400);
        }
    } catch (\Throwable $th) {
        DB::rollBack();
        Log::error('Error en el proceso de actualización: ' . $th->getMessage());
        return response()->json(['error' => 'Hubo un error al procesar la solicitud.'], 500);
    }
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
    public function destroy($id_h_p_d, LogsRequest $request)
    {
        $detalle = $request->input('detalles');
        $usuario = $request->input('usuario');
        DB::beginTransaction();

        try {
            $horarioPrevioResponse = $this->horarioPrevioDocenteService->eliminarHorarioPrevioDocentePorId($id_h_p_d);
            $horarioPrevio = $horarioPrevioResponse->getData();

            if (isset($horarioPrevio->success)) {
                DB::commit();
                $accion = "Eliminación del horario previo docente " . "(id:" . $id_h_p_d . ")";

                $this->logModificacionEliminacionController->store($accion, $usuario, $detalle);
                return response()->json(['success' => 'Horario Previo eliminado correctamente.'], 200);
            } else {
                DB::rollBack();
                return response()->json(['error' => $horarioPrevio['error'] ?? 'Error desconocido.'], 400);
            }
        } catch (\Throwable $th) {
            DB::rollBack();
            Log::error('Error en el proceso de eliminación: ' . $th->getMessage());
            return response()->json(['error' => 'Hubo un error al procesar la solicitud.'], 500);
        }
    }
}
