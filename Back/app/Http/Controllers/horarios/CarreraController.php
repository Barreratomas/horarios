<?php

namespace App\Http\Controllers\horarios;

use App\Http\Requests\horarios\CarreraRequest;
use App\Models\Carrera;
use App\Services\horarios\CarreraService;
use App\Http\Controllers\Controller;


use Illuminate\Http\Request;

class CarreraController extends Controller
{
    protected $carreraService;

    public function __construct(CarreraService $carreraService)
    {
        $this->carreraService = $carreraService;
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
        return $this->carreraService->actualizarCarrera($request, $id);
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
    public function destroy($id)
    {
        return $this->carreraService->eliminarCarreraPorId($id);
    }
}
