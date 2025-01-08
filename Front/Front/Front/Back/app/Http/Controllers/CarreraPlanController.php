<?php

namespace App\Http\Controllers;

use App\Services\CarreraPlanService;
use App\Http\Requests\CarreraPlanRequest;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Request;

class CarreraPlanController extends Controller
{
    protected $carreraPlanService;

    public function __construct(CarreraPlanService $carreraPlanService)
    {
        $this->carreraPlanService = $carreraPlanService;
    }

    //-------------------------------------------------------------------------------------------------------------
    // Swagger Documentation

    /**
     * @OA\Get(
     *     path="/api/horarios/carreraPlan",
     *     operationId="getCarreraPlanList",
     *     tags={"CarreraPlan"},
     *     summary="Get list of CarreraPlan",
     *     description="Returns list of CarreraPlan",
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/CarreraPlan")
     *         )
     *     ),
     *     @OA\Response(response=400, description="Bad request"),
     *     @OA\Response(response=401, description="Unauthorized"),
     *     @OA\Response(response=404, description="Resource Not Found"),
     *     @OA\Response(response=500, description="Internal Server Error")
     * )
     */
    public function index()
    {
        return $this->carreraPlanService->obtenerTodosCarreraPlan();
    }

    /**
     * @OA\Get(
     *     path="/api/horarios/carreraPlan/idCarrera/{id}",
     *     operationId="getCarreraPlanByIdCarrera",
     *     tags={"CarreraPlan"},
     *     summary="Get CarreraPlan information by Carrera ID",
     *     description="Returns CarreraPlan data",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID of Carrera",
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
    public function obtenerCarreraPlanPorIdCarrera($id)
    {
        return $this->carreraPlanService->obtenerCarreraPlanPorIdCarrera($id);
    }

    public function obtenerCarreraPlanPorIdCarreraConMaterias($id){
        return $this->carreraPlanService->obtenerCarreraPlanPorIdCarreraConMaterias($id);

    }

    /**
     * @OA\Get(
     *     path="/api/horarios/carreraPlan/idPlan/{id}",
     *     operationId="getCarreraPlanByIdPlan",
     *     tags={"CarreraPlan"},
     *     summary="Get CarreraPlan information by Plan ID",
     *     description="Returns CarreraPlan data",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID of Plan",
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
    public function obtenerCarreraPlanPorIdPlan($id)
    {
        return $this->carreraPlanService->obtenerCarreraPlanPorIdPlan($id);
    }

    /**
     * @OA\Post(
     *     path="/api/horarios/carreraPlan/guardar",
     *     tags={"CarreraPlan"},
     *     summary="Store CarreraPlan",
     *     description="Save a new CarreraPlan",
     *     operationId="guardarCarreraPlan",
     *     @OA\RequestBody(
     *         description="CarreraPlan to be stored",
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/CarreraPlan")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="CarreraPlan successfully saved"
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Error saving CarreraPlan"
     *     )
     * )
     */
    public function store(Request $request)
{
    // Extraer los valores necesarios del request
    $id_carrera = $request->input('id_carrera');
    $id_plan = $request->input('id_plan');

    // Llamar al servicio con los argumentos requeridos
    return $this->carreraPlanService->guardarCarreraPlan($id_carrera, $id_plan);
}
  

    /**
     * @OA\Delete(
     *     path="/api/horarios/carreraPlan/eliminar/idPlan/{id}",
     *     tags={"CarreraPlan"},
     *     summary="Delete CarreraPlan by Plan ID",
     *     description="Delete CarreraPlan by Plan ID",
     *     operationId="eliminarCarreraPlanPorIdPlan",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID of CarreraPlan",
     *         required=true,
     *         @OA\Schema(
     *             type="integer"
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="CarreraPlan successfully deleted"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="CarreraPlan not found"
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Error deleting CarreraPlan"
     *     )
     * )
     */
    public function eliminarCarreraPlanPorIdCarreraYPlan($id_carrera, $id_plan)
    {
        return $this->carreraPlanService->eliminarCarreraPlanPorIdCarreraYPlan($id_carrera, $id_plan);
    }
    
}
