<?php

namespace App\Http\Controllers;

use App\Services\CarreraUCService;
use App\Http\Requests\CarreraUCRequest;
use App\Http\Controllers\Controller;


class CarreraUCController extends Controller
{
    protected $carreraUCService;

    public function __construct(CarreraUCService $carreraUCService)
    {
        $this->carreraUCService = $carreraUCService;

    }


    //-------------------------------------------------------------------------------------------------------------
    // Swagger Documentation



    /**
     * @OA\Get(
     *     path="/api/horarios/carreraUC",
     *      operationId="getCarreraUCList",
     *      tags={"CarreraUC"},
     *      summary="Get list of carreraUC",
     *      description="Returns list of carreraUC",
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *          @OA\JsonContent(
     *             type="array",
     *            @OA\Items(ref="#/components/schemas/CarreraUC")
     *          )
     *      ),
     *      @OA\Response(response=400, description="Bad request"),
     *      @OA\Response(response=401, description="Unauthorized"),
     *      @OA\Response(response=404, description="Resource Not Found"),
     *      @OA\Response(response=500, description="Internal Server Error")
     *     )
     */
    public function index()
    {
        return $this->carreraUCService->obtenerTodosCarreraUC();
    }


    /**
     * @OA\Get(
     *     path="/api/horarios/carreraUC/idCarrera/{id}",
     *      operationId="getCarreraUCByIdCarrera",
     *     tags={"CarreraUC"},
     *     summary="Get carreraUC information",
     *     description="Returns carreraUC data",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID del Carrera",
     *         required=true,
     *         @OA\Schema(
     *             type="integer",
     *             format="int64"
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Resource Not Found"
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Internal Server Error"
     *     )
     * )
     */
    public function obtenerCarreraUCPorIdCarrera($id)
    {
        return $this->carreraUCService->obtenerCarreraUCPorIdCarrera($id);
    }


    /**
     * @OA\Get(
     *     path="/api/horarios/carreraUC/idUC/{id}",
     *      operationId="getCarreraUCByIdUC",
     *     tags={"CarreraUC"},
     *     summary="Get carreraUC information",
     *     description="Returns carreraUC data",
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
     *         description="Successful operation"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Resource Not Found"
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Internal Server Error"
     *     )
     * )
     */
    public function obtenerCarreraUCPorIdUC($id)
    {
        return $this->carreraUCService->obtenerCarreraUCPorIdUC($id);
    }


    /**
     * @OA\Post(
     *     path="/api/horarios/carreraUC/guardar",
     *     tags={"CarreraUC"},
     *     summary="Almacenar carreraUC",
     *     description="Guardar nueva carreraUC",
     *     operationId="guardarCarreraUC",
     *     @OA\RequestBody(
     *         description="CarreraUC a ser guardada",
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/CarreraUC")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="CarreraUC guardada correctamente"
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Error al guardar la carreraUC"
     *     )
     * )
     */
    public function store(CarreraUCRequest $request)
    {
        return $this->carreraUCService->guardarCarreraUC($request);
    }


    /**
     * @OA\Delete(
     *     path="/api/horarios/carreraUC/eliminar/idCarrera/{id}",
     *     tags={"CarreraUC"},
     *     summary="Eliminar carreraUC",
     *     description="Eliminar carreraUC por ID",
     *     operationId="eliminarCarreraUCPorIdCarrera",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID de la carreraUC",
     *         required=true,
     *         @OA\Schema(
     *             type="integer"
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="CarreraUC eliminada correctamente"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="CarreraUC no encontrada"
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Error al eliminar la carreraUC"
     *     )
     * )
     */
    public function eliminarCarreraUCPorIdCarrera($id)
    {
        return $this->carreraUCService->eliminarCarreraUCPorIdCarrera($id);
    }

    /**
     * @OA\Delete(
     *     path="/api/horarios/carreraUC/eliminar/idUC/{id}",
     *     tags={"CarreraUC"},
     *     summary="Eliminar carreraUC",
     *     description="Eliminar carreraUC por ID",
     *     operationId="eliminarCarreraUCPorIdUC",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID de la carreraUC",
     *         required=true,
     *         @OA\Schema(
     *             type="integer"
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="CarreraUC eliminada correctamente"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="CarreraUC no encontrada"
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Error al eliminar la carreraUC"
     *     )
     * )
     */
    public function eliminarCarreraUCPorIdUC($id)
    {
        return $this->carreraUCService->eliminarCarreraUCPorIdUC($id);
    }


}
