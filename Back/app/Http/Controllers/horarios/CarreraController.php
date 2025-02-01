<?php

namespace App\Http\Controllers\horarios;

use App\Http\Requests\horarios\CarreraRequest;
use App\Models\Carrera;
use App\Services\horarios\CarreraService;
use App\Http\Controllers\Controller;
use App\Http\Controllers\LogModificacionEliminacionController;
use App\Http\Requests\LogsRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class CarreraController extends Controller
{
    protected $carreraService;
    protected $logModificacionEliminacionController;

    public function __construct(CarreraService $carreraService, LogModificacionEliminacionController $logModificacionEliminacionController)
    {
        $this->carreraService = $carreraService;
        $this->logModificacionEliminacionController = $logModificacionEliminacionController;
    }

    /*

    public function index()
    {
        $carreras = $this->carreraService->obtenerTodasCarreras();
        return view('carrera.index', compact('carreras'));
    }

    public function mostrarCarrera(Request $request)
    {
        $id = $request->input('id');
        $carrera = $this->carreraService->obtenerCarreraPorId($id);
        
        return view('#', compact('carrera'));
    }

    public function crear()
    {
        return view('carrera.crearCarrera');
    }

    public function store(carreraRequest $request)
    {
        
        $nombre = $request->input('nombre');

        $response = $this->carreraService->guardarCarrera($nombre);
        if (isset($response['success'])) {
            return redirect()->route('indexCarrera')->with('success', $response['success']);
        } else {
            return redirect()->route('indexCarrera')->withErrors(['error' => $response['error']]);
        }
    }

    public function formularioActualizar( Carrera $carrera){
        return view('carrera.actualizarCarrera', compact('carrera'));
    }


    public function actualizar(carreraRequest $request,Carrera $carrera)
    {
        $nombre = $request->input('nombre');


        $response = $this->carreraService->actualizarCarrera($nombre,$carrera);
        if (isset($response['success'])) {
            return redirect()->route('indexCarrera')->with('success', $response['success']);
        } else {
            return redirect()->route('indexCarrera')->withErrors(['error' => $response['error']]);
        }
    }

    public function eliminar(Carrera $carrera)
    {

        $response = $this->carreraService->eliminarCarreraPorId($carrera);
        if (isset($response['success'])) {
            return redirect()->route('indexCarrera')->with('success', $response['success']);
        } else {
            return redirect()->route('indexCarrera')->withErrors(['error' => $response['error']]);
        }
    }

    */

    //---------------------------------------------------------------------------------------------------------
    // Swagger

    /**
     * @OA\Get(
     *     path="/api/horarios/carreras",
     *     summary="Obtener todas las carreras",
     *     description="Devuelve todas las carreras",
     *     operationId="getCambiosDocente",
     *     tags={"Carrera"},
     *     @OA\Response(
     *     response=200,
     *     description="Carreras",
     *     @OA\JsonContent(
     *     type="array",
     *     @OA\Items(ref="#/components/schemas/Carrera")
     *   )
     * ),
     *     @OA\Response(
     *     response=500,
     *     description="Error al obtener las carreras"
     * )
     * )
     */
    public function index()
    {
        return $this->carreraService->obtenerTodosCarrera();
    }

    /**
     * @OA\Get(
     *     path="/api/horarios/carreras/{id}",
     *     summary="Obtener una carrera por id",
     *     description="Devuelve una carrera",
     *     operationId="getCambioDocentePorId",
     *     tags={"Carrera"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID de la carrera",
     *         required=true,
     *         @OA\Schema(
     *             type="integer"
     *         )
     *     ),
     *     @OA\Response(
     *     response=200,
     *     description="Carrera",
     *     @OA\JsonContent(ref="#/components/schemas/Carrera")
     * ),
     *     @OA\Response(
     *     response=404,
     *     description="No existe la carrera"
     * ),
     *     @OA\Response(
     *     response=500,
     *     description="Error al obtener la carrera"
     * )
     * )
     */
    public function show($id)
    {
        return $this->carreraService->obtenerCarreraPorId($id);
    }

    /**
     * @OA\Post(
     *     path="/api/horarios/carreras/guardar",
     *     summary="Guardar una carrera",
     *     description="Guardar una carrera",
     *     operationId="guardarCambioDocente",
     *     tags={"Carrera"},
     *     @OA\RequestBody(
     *         @OA\JsonContent(ref="#/components/schemas/CarreraDTO")
     *     ),
     *     @OA\Response(
     *     response=200,
     *     description="Carrera guardada correctamente",
     *     @OA\JsonContent(ref="#/components/schemas/Carrera")
     * ),
     *     @OA\Response(
     *     response=500,
     *     description="Error al guardar la carrera"
     * )
     * )
     */
    public function store(CarreraRequest $request)
    {
        return $this->carreraService->guardarCarrera($request);
    }

    /**
     * @OA\Put(
     *     path="/api/horarios/carreras/actualizar/{id}",
     *     summary="Actualizar una carrera",
     *     description="Actualizar una carrera",
     *     tags={"Carrera"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID de la carrera",
     *         required=true,
     *         @OA\Schema(
     *             type="integer"
     *         )
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/CarreraDTO")
     *     ),
     *     @OA\Response(
     *     response=200,
     *     description="Carrera actualizada correctamente",
     *     @OA\JsonContent(ref="#/components/schemas/Carrera")
     *     ),
     *     @OA\Response(
     *     response=404,
     *     description="No existe la carrera"
     *     )
     * )
     */
    public function update(CarreraRequest $request, $id)
    {
        $detalle = $request->input('detalles');
        $usuario = $request->input('usuario');
        DB::beginTransaction();

        try {

            $carreraResponse  = $this->carreraService->actualizarCarrera($request, $id);

            if ($carreraResponse->getStatusCode() != 200) {
                DB::rollBack();
                return response()->json(['error' => 'Hubo un error al actualizar la carrera'], 500);
            }

            $carrera = $carreraResponse->getData();

            $nombreCarrera = $carrera->carrera;
            $accion = "actualizacion de la carrera " . $nombreCarrera . "(id:" . $id . ")";

            $this->logModificacionEliminacionController->store($accion, $usuario, $detalle);

            DB::commit();

            return response()->json(['message' => 'Carrera actualizada exitosamente'], 200);
        } catch (\Exception $e) {
            DB::rollBack();

            Log::error("Error al actualizar la carrera:  " . $e->getMessage());

            return response()->json(['error' => 'Hubo un error al actualizar la carrera'], 500);
        }
    }

    /**
     * @OA\Delete(
     *     path="/api/horarios/carreras/eliminar/{id}",
     *     summary="Eliminar una carrera",
     *     description="Eliminar una carrera",
     *     operationId="eliminarCambioDocentePorId",
     *     tags={"Carrera"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID de la carrera",
     *         required=true,
     *         @OA\Schema(
     *             type="integer"
     *         )
     *     ),
     *     @OA\Response(
     *     response=200,
     *     description="Carrera eliminada correctamente"
     * ),
     *     @OA\Response(
     *     response=404,
     *     description="No existe la carrera"
     * ),
     *     @OA\Response(
     *     response=500,
     *     description="Error al eliminar la carrera"
     * )
     * )
     */
    public function destroy($id, LogsRequest $request)
    {
        $detalle = $request->input('detalles');
        $usuario = $request->input('usuario');

        DB::beginTransaction();

        try {
            $carreraResponse = $this->carreraService->eliminarCarreraPorId($id);

            if ($carreraResponse->getStatusCode() !== 200) {
                DB::rollBack();
                return $carreraResponse;
            }

            $carrera = $carreraResponse->getData();
            if (!isset($carrera->nombre_carrera)) {
                throw new \Exception('No se pudo obtener el nombre de la carrera.');
            }

            $nombreCarrera = $carrera->nombre_carrera;
            $accion = "Eliminación de la carrera " . $nombreCarrera . "(id:" . $id . ")";

            $this->logModificacionEliminacionController->store($accion, $usuario, $detalle);

            DB::commit();

            return response()->json([
                'message' => 'Carrera eliminada correctamente.'
            ], 200);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'error' => 'Hubo un problema al eliminar la carrera: ' . $e->getMessage()
            ], 500);
        }
    }
}
