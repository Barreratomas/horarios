<?php

namespace App\Http\Controllers\horarios;

use App\Services\horarios\PlanEstudioService;
use App\Http\Requests\horarios\PlanEstudioRequest;
use App\Http\Controllers\Controller;
use App\Services\CarreraPlanService;
use App\Services\horarios\UCPlanService;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PlanEstudioController extends Controller
{
    protected $planEstudioService;
    protected $UCPlanService;
    protected $CarreraPlanService;

    public function __construct(PlanEstudioService $planEstudioService, UCPlanService $UCPlanService, CarreraPlanService $CarreraPlanService)
    {
        $this->planEstudioService = $planEstudioService;
        $this->UCPlanService = $UCPlanService;
        $this->CarreraPlanService = $CarreraPlanService;

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
     *    path="/api/horarios/planEstudio/{id}",
     *     operationId="getPlanEstudioById",
     *    tags={"PlanEstudio"},
     *    summary="Get planEstudio information",
     *   description="Returns planEstudio data",
     *   @OA\Parameter(
     *       name="id",
     *      in="path",
     *     description="ID of planEstudio to return",
     *   required=true,
     *  @OA\Schema(
     *    type="integer"
     *  )   
     * ),
     * @OA\Response(
     *    response=200,
     *  description="successful operation",
     * @OA\JsonContent(ref="#/components/schemas/PlanEstudio")
     * ),
     * @OA\Response(
     *   response=404,
     * description="PlanEstudio not found"
     * )
     * )
     */
    public function show($id)
    {
        return $this->planEstudioService->obtenerPlanEstudioPorId($id);
    }


    /**
     * @OA\Post(
     *     path="/api/horarios/planEstudio/guardar",
     *     tags={"PlanEstudio"},
     *     operationId="storePlanEstudio",
     *     summary="Store new PlanEstudio",
     *     description="Returns PlanEstudio data",
     *     @OA\RequestBody(
     *         description="PlanEstudio object that needs to be stored",
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/PlanEstudioDTO")
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="successful operation",
     *         @OA\JsonContent(ref="#/components/schemas/PlanEstudio")
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Invalid input"
     *     )
     * )
     */
    public function store(PlanEstudioRequest $request)
    {
        DB::beginTransaction();
    
        try {
            $data = $request->only(['detalle', 'fecha_inicio', 'fecha_fin']);
            $id_carrera = $request->input('id_carrera');
            $materias = $request->input('materias');
    
            // Log de datos iniciales
            Log::info('Datos del request para guardar plan de estudio(ya controller)', [
                'data' => $data,
                'id_carrera' => $id_carrera,
                'materias' => $materias,
            ]);
    
            // Guardar plan de estudio
            $PEResponse = $this->planEstudioService->guardarPlanEstudio($data);
            Log::info('Respuesta del servicio guardarPlanEstudio', [
                'response' => $PEResponse
            ]);
    
            $PE = $PEResponse->getData();
            Log::info('Datos extraídos de la respuesta de guardarPlanEstudio', [
                'PE' => $PE
            ]);
    
            if (isset($PE->error)) {
                Log::error('Error en la respuesta de guardarPlanEstudio', [
                    'error' => $PE->error
                ]);
                return response()->json(['error' => $PE->error], 500);
            }
    
            // Guardar UCPlan
            $ucplanResponse = $this->UCPlanService->guardarUCPlan($PE->id_plan, $materias);
            Log::info('Respuesta del servicio guardarUCPlan', [
                'response' => $ucplanResponse
            ]);
    
            if ($ucplanResponse->getStatusCode() !== 201) {
                DB::rollBack();
                Log::error('Error al guardar UCPlan', [
                    'response' => $ucplanResponse->getData()
                ]);
                return response()->json($ucplanResponse->getData(), 500);
            }
    
            // Guardar CarreraPlan
            $carreraPlanResponse = $this->CarreraPlanService->guardarCarreraPlan($PE->id_plan, $id_carrera);
            Log::info('Respuesta del servicio guardarCarreraPlan', [
                'response' => $carreraPlanResponse
            ]);
    
            if ($carreraPlanResponse->getStatusCode() !== 201) {
                DB::rollBack();
                Log::error('Error al guardar CarreraPlan', [
                    'response' => $carreraPlanResponse->getData()
                ]);
                return response()->json($carreraPlanResponse->getData(), 500);
            }
    
            DB::commit();
            Log::info('Plan de estudio guardado con éxito');
            return response()->json(['message' => 'Plan de estudio guardado con éxito'], 201);
                
        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Error al guardar el plan de estudio', [
                'exception' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            return response()->json(['error' => 'Hubo un error al guardar el plan de estudio'], 500);
        }
    }
    


    /**
     * @OA\Put(
     *     path="/api/horarios/planEstudio/actualizar/{id}",
     *     tags={"PlanEstudio"},
     *     operationId="updatePlanEstudio",
     *     summary="Update an existing PlanEstudio",
     *     description="Returns updated PlanEstudio data",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID of PlanEstudio to update",
     *         required=true,
     *         @OA\Schema(
     *             type="integer"
     *         )
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         description="PlanEstudio object that needs to be updated",
     *         @OA\JsonContent(ref="#/components/schemas/PlanEstudioDTO")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="successful operation",
     *         @OA\JsonContent(ref="#/components/schemas/PlanEstudio")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="PlanEstudio not found"
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Invalid input"
     *     )
     * )
     */
    public function update(PlanEstudioRequest $request, $id)
    {
        return $this->planEstudioService->actualizarPlanEstudio($request->all(), $id);
    }


    /**
     * @OA\Delete(
     *     path="/api/horarios/planEstudio/eliminar/{id}",
     *     tags={"PlanEstudio"},
     *     summary="Delete an existing PlanEstudio",
     *     description="Deletes a PlanEstudio",
     *     operationId="deletePlanEstudio",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID of PlanEstudio to delete",
     *         required=true,
     *         @OA\Schema(
     *             type="integer"
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="successful operation",
     *         @OA\JsonContent(ref="#/components/schemas/PlanEstudio")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="PlanEstudio not found"
     *     )
     * )
     */
    public function destroy($id)
    {
        return $this->planEstudioService->eliminarPlanEstudio($id);
    }


}
