<?php

namespace App\Http\Controllers\horarios;

use App\Services\horarios\PlanEstudioService;
use App\Http\Requests\horarios\PlanEstudioRequest;
use App\Http\Controllers\Controller;
use App\Http\Controllers\LogModificacionEliminacionController;
use App\Http\Requests\LogsRequest;
use App\Services\CarreraPlanService;
use App\Services\horarios\UCPlanService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PlanEstudioController extends Controller
{
    protected $planEstudioService;
    protected $UCPlanService;
    protected $CarreraPlanService;
    protected $logModificacionEliminacionController;

    public function __construct(PlanEstudioService $planEstudioService, UCPlanService $UCPlanService, CarreraPlanService $CarreraPlanService, LogModificacionEliminacionController $logModificacionEliminacionController)
    {
        $this->planEstudioService = $planEstudioService;
        $this->UCPlanService = $UCPlanService;
        $this->CarreraPlanService = $CarreraPlanService;
        $this->logModificacionEliminacionController = $logModificacionEliminacionController;

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


    public function indexConRelaciones()
    {
        return $this->planEstudioService->obtenerPlanEstudioConRelaciones();
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
    
    public function showConRelaciones($id)
    {
        return $this->planEstudioService->obtenerPlanEstudioPorIdConRelaciones($id);
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
          
            // Guardar plan de estudio
            $PEResponse = $this->planEstudioService->guardarPlanEstudio($data);
          
    
            $PE = $PEResponse->getData();
         
    
            if (isset($PE->error)) {
                Log::error('Error en la respuesta de guardarPlanEstudio', [
                    'error' => $PE->error
                ]);
                return response()->json(['error' => $PE->error], 500);
            }
    
            // Guardar UCPlan
            $ucplanResponse = $this->UCPlanService->guardarUCPlan($PE->id_plan, $materias);
          
    
            if ($ucplanResponse->getStatusCode() !== 201) {
                DB::rollBack();
                Log::error('Error al guardar UCPlan', [
                    'response' => $ucplanResponse->getData()
                ]);
                return response()->json($ucplanResponse->getData(), 500);
            }
    
            // Guardar CarreraPlan
            $carreraPlanResponse = $this->CarreraPlanService->guardarCarreraPlan($PE->id_plan, $id_carrera);
          
    
            if ($carreraPlanResponse->getStatusCode() !== 201) {
                DB::rollBack();
                Log::error('Error al guardar CarreraPlan', [
                    'response' => $carreraPlanResponse->getData()
                ]);
                return response()->json($carreraPlanResponse->getData(), 500);
            }
    
            DB::commit();
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
        $detalle = $request->input('detalles');
        $usuario = $request->input('usuario');
        
        DB::beginTransaction();

        try {
            // Obtener datos del request
            $data = $request->only(['detalle', 'fecha_inicio', 'fecha_fin']);
            $id_carrera = $request->input('id_carrera');
            $materias = $request->input('materias');

           
            

            // Actualizar el PlanEstudio
            $planEstudioResponse = $this->planEstudioService->actualizarPlanEstudio($data, $id);
          

            if ($planEstudioResponse->getStatusCode() !== 200) {
                DB::rollBack();
                Log::error('Error al actualizar PlanEstudio', [
                    'response' => $planEstudioResponse->getData()
                ]);
                return response()->json($planEstudioResponse->getData(), 500);
            }

            // Actualizar UCPlan si se envio materias
            if (isset($materias)) {
                $ucplanResponse = $this->UCPlanService->actualizarUCPlan($materias, $id);
               

                if ($ucplanResponse->getStatusCode() !== 200) {
                    DB::rollBack();
                    Log::error('Error al actualizar UCPlan', [
                        'response' => $ucplanResponse->getData()
                    ]);
                    return response()->json($ucplanResponse->getData(), 500);
                }
            }


            // Actualizar CarreraPlan si se envio id_carrera
            if (isset($id_carrera)) {
                $carreraPlanResponse = $this->CarreraPlanService->actualizarCarreraPlan($id_carrera, $id);
               

                if ($carreraPlanResponse->getStatusCode() !== 200) {
                    DB::rollBack();
                    Log::error('Error al actualizar CarreraPlan', [
                        'response' => $carreraPlanResponse->getData()
                    ]);
                    return response()->json($carreraPlanResponse->getData(), 500);
                }
            }

            $planEstudio = $planEstudioResponse->getData();

            $nombrePlanEstudio = $planEstudio->detalle;
            $accion = "Actualización del plan de estudio " . $nombrePlanEstudio."(id:".$id.")";
            
            $this->logModificacionEliminacionController->store($accion,$usuario,$detalle);

            DB::commit();
            return response()->json(['message' => 'Plan de estudio actualizado con éxito'], 200);

        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Error al actualizar el plan de estudio', [
                'exception' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            return response()->json(['error' => 'Hubo un error al actualizar el plan de estudio'], 500);
        }
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
    public function destroy($id, LogsRequest $request)
    {
        $detalle = $request->input('detalles');
        $usuario = $request->input('usuario');

        DB::beginTransaction();

        try {
            $planEstudioResponse = $this->planEstudioService->eliminarPlanEstudio($id);
            
            $planEstudio = $planEstudioResponse->getData();
            if (!isset($planEstudio->nombre_plan_estudio)) {
                throw new \Exception('No se pudo obtener el nombre del plan de estudio.');
            }

            $nombrePlanEstudio = $planEstudio->nombre_plan_estudio;
            $accion = "Eliminación del plan de estudio " . $nombrePlanEstudio."(id:".$id.")";
            
            $this->logModificacionEliminacionController->store($accion,$usuario,$detalle);

            DB::commit();

            return response()->json([
                'message' => 'Plan de estudio eliminado correctamente.'
            ], 200);

        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'error' => 'Hubo un problema al eliminar el plan de estudio: ' . $e->getMessage()
            ], 500);
        }
    }

}