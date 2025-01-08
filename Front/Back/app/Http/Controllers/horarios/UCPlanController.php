<?php

namespace App\Http\Controllers\horarios;

use App\Services\horarios\UCPlanService;
use App\Http\Requests\horarios\UCPlanRequest;
use App\Http\Controllers\Controller;
 

class UCPlanController extends Controller
{
    protected $uCPlanService;

    public function __construct(UCPlanService $uCPlanService)
    {
        $this->uCPlanService = $uCPlanService;

    }


    //-------------------------------------------------------------------------------------------------------------
    // Swagger Documentation


    /**
     * @OA\Get(
     *     path="/api/horarios/ucPlan",
     *      operationId="getUCPlanList",
     *      tags={"UCPlan"},
     *      summary="Get list of ucPlan",
     *      description="Returns list of ucPlan",
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *          @OA\JsonContent(
     *             type="array",
     *            @OA\Items(ref="#/components/schemas/UCPlan")
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
        return $this->uCPlanService->obtenerUCPlan();
    }

    
    /**
     * @OA\Get(
     *     path="/api/horarios/ucPlan/{id}",
     *      operationId="getUCPlanById",
     *      tags={"UCPlan"},
     *      summary="Get ucPlan information",
     *      description="Returns ucPlan data",
     *      @OA\Parameter(
     *          name="id",
     *          description="UCPlan id",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *          @OA\JsonContent(ref="#/components/schemas/UCPlan")
     *      ),
     *      @OA\Response(response=400, description="Bad request"),
     *      @OA\Response(response=401, description="Unauthorized"),
     *      @OA\Response(response=404, description="Resource Not Found"),
     *      @OA\Response(response=500, description="Internal Server Error")
     *     )
     */
    public function show($id)
    {
        return $this->uCPlanService->obtenerUCPlanPorId($id);
    }


    /**
     * @OA\Post(
     *      path="/api/horarios/ucPlan/guardar",
     *      operationId="storeUCPlan",
     *      tags={"UCPlan"},
     *      summary="Store new UCPlan",
     *      description="Returns ucPlan data",
     *      @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(ref="#/components/schemas/UCPlan")
     *      ),
     *      @OA\Response(
     *          response=201,
     *          description="Successful operation",
     *          @OA\JsonContent(ref="#/components/schemas/UCPlan")
     *      ),
     *      @OA\Response(response=400, description="Bad request"),
     *      @OA\Response(response=401, description="Unauthorized"),
     *      @OA\Response(response=404, description="Resource Not Found"),
     *      @OA\Response(response=500, description="Internal Server Error")
     * )
     */
    public function store(UCPlanRequest $request)
    {
        return $this->uCPlanService->guardarUCPlan($request);
    }

     
    /**
     * @OA\Put(
     *     path="/api/horarios/ucPlan/actualizar/{id}",
     *     summary="Update a UCPlan",
     *     description="Update a UCPlan",
     *     operationId="updateUCPlan",
     *     tags={"UCPlan"},
     *     @OA\Parameter(
     *          name="id",
     *          in="path",
     *          description="ID of UCPlan to update",
     *          required=true,
     *          @OA\Schema(
     *              type="integer"
     *          )
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/UCPlan")
     *     ),
     *     @OA\Response(
     *     response=200,
     *     description="UCPlan updated successfully",
     *     @OA\JsonContent(ref="#/components/schemas/UCPlan")
     *     ),
     *     @OA\Response(
     *     response=404,
     *     description="UCPlan not found"
     *  ),
     *     @OA\Response(
     *     response=500,
     *     description="Error updating UCPlan"
     *   )
     * )
     */
    public function update(UCPlanRequest $request, $id)
    {
        return $this->uCPlanService->actualizarUCPlan($request, $id);
    }

    
    /**
     * @OA\Delete(
     *     path="/api/horarios/ucPlan/eliminar/{id}",
     *     summary="Delete a UCPlan",
     *     description="Delete a UCPlan",
     *     operationId="deleteUCPlan",
     *     tags={"UCPlan"},
     *     @OA\Parameter(
     *          name="id",
     *          in="path",
     *          description="ID of UCPlan to delete",
     *          required=true,
     *          @OA\Schema(
     *              type="integer"
     *          )
     *     ),
     *     @OA\Response(
     *     response=200,
     *     description="UCPlan deleted successfully",
     *     @OA\JsonContent(ref="#/components/schemas/UCPlan")
     *     ),
     *     @OA\Response(
     *     response=404,
     *     description="UCPlan not found"
     *  ),
     *     @OA\Response(
     *     response=500,
     *     description="Error deleting UCPlan"
     *   )
     * )
     */
    public function destroy($id)
    {
        return $this->uCPlanService->eliminarUCPlan($id);
    }

}
