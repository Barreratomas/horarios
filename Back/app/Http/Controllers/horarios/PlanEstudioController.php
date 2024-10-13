<?php

namespace App\Http\Controllers\horarios;

use App\Services\horarios\PlanEstudioService;
use App\Http\Requests\horarios\PlanEstudioRequest;
use App\Http\Controllers\Controller;
 

class PlanEstudioController extends Controller
{
    protected $planEstudioService;

    public function __construct(PlanEstudioService $planEstudioService)
    {
        $this->planEstudioService = $planEstudioService;

    }


    //-------------------------------------------------------------------------------------------------------------
    // Swagger Documentation



    /**
     * @OA\Get(
     *     path="/api/horarios/planEstudio",
     *      operationId="getPlanEstudioList",
     *      tags={"PlanEstudio"},
     *      summary="Get list of planEstudio",
     *      description="Returns list of planEstudio",
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *          @OA\JsonContent(
     *             type="array",
     *            @OA\Items(ref="#/components/schemas/PlanEstudio")
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
        return $this->planEstudioService->obtenerPlanEstudio();
    }


    
    /**
     * @OA\Get(
     *     path="/api/horarios/planEstudio/{id}",
     *      operationId="getPlanEstudioById",
     *      tags={"PlanEstudio"},
     *      summary="Get planEstudio information",
     *      description="Returns planEstudio data",
     *      @OA\Parameter(
     *          name="id",
     *          description="PlanEstudio id",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *          @OA\JsonContent(ref="#/components/schemas/PlanEstudio")
     *       ),
     *      @OA\Response(response=400, description="Bad request"),
     *      @OA\Response(response=401, description="Unauthorized"),
     *      @OA\Response(response=404, description="Resource Not Found"),
     *      @OA\Response(response=500, description="Internal Server Error")
     *     )
     */
    public function show($id)
    {
        return $this->planEstudioService->obtenerPlanEstudioPorId($id);
    }



    /**
     * @OA\Post(
     *      path="/api/horarios/planEstudio/guardar",
     *      operationId="storePlanEstudio",
     *      tags={"PlanEstudio"},
     *      summary="Store new planEstudio",
     *      description="Returns planEstudio data",
     *      @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(ref="#/components/schemas/PlanEstudioDTO")
     *      ),
     *      @OA\Response(
     *          response=201,
     *          description="Successful operation",
     *          @OA\JsonContent(ref="#/components/schemas/PlanEstudio")
     *       ),
     *      @OA\Response(response=400, description="Bad request"),
     *      @OA\Response(response=401, description="Unauthorized"),
     *      @OA\Response(response=404, description="Resource Not Found"),
     *      @OA\Response(response=500, description="Internal Server Error")
     *     )
     */
    public function store(PlanEstudioRequest $request)
    {
        return $this->planEstudioService->guardarPlanEstudio($request);
    }



    /**
     * @OA\Put(
     *      path="/api/horarios/planEstudio/actualizar/{id}",
     *      operationId="updatePlanEstudio",
     *      tags={"PlanEstudio"},
     *      summary="Update existing planEstudio",
     *      description="Returns updated planEstudio data",
     *      @OA\Parameter(
     *          name="id",
     *          description="PlanEstudio id",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     *      @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(ref="#/components/schemas/PlanEstudioDTO")
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *          @OA\JsonContent(ref="#/components/schemas/PlanEstudio")
     *       ),
     *      @OA\Response(response=400, description="Bad request"),
     *      @OA\Response(response=401, description="Unauthorized"),
     *      @OA\Response(response=404, description="Resource Not Found"),
     *      @OA\Response(response=500, description="Internal Server Error")
     *     )
     */
    public function update(PlanEstudioRequest $request, $id)
    {
        return $this->planEstudioService->actualizarPlanEstudio($request, $id);
    }


    
    /**
     * @OA\Delete(
     *      path="/api/horarios/planEstudio/eliminar/{id}",
     *      operationId="deletePlanEstudio",
     *      tags={"PlanEstudio"},
     *      summary="Delete existing planEstudio",
     *      description="Deletes a record and returns no content",
     *      @OA\Parameter(
     *          name="id",
     *          description="PlanEstudio id",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     *      @OA\Response(
     *          response=204,
     *          description="Successful operation",
     *       ),
     *      @OA\Response(response=400, description="Bad request"),
     *      @OA\Response(response=401, description="Unauthorized"),
     *      @OA\Response(response=404, description="Resource Not Found"),
     *      @OA\Response(response=500, description="Internal Server Error")
     *     )
     */
    public function destroy($id)
    {
        return $this->planEstudioService->eliminarPlanEstudio($id);
    }

}
