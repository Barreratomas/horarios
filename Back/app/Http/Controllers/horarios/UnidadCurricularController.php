<?php

namespace App\Http\Controllers\horarios;

use App\Services\horarios\UnidadCurricularService;
use App\Http\Requests\horarios\UnidadCurricularRequest;
use App\Http\Controllers\Controller;
use App\Http\Controllers\LogModificacionEliminacionController;
use App\Http\Requests\LogsRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;



class UnidadCurricularController extends Controller
{
    protected $unidadCurricularService;
    protected $logModificacionEliminacionController;

    public function __construct(UnidadCurricularService $unidadCurricularService, LogModificacionEliminacionController $logModificacionEliminacionController)
    {
        $this->unidadCurricularService = $unidadCurricularService;
        $this->logModificacionEliminacionController = $logModificacionEliminacionController;

    }


    //-------------------------------------------------------------------------------------------------------------
    // Swagger Documentation


    /**
     * @OA\Get(
     *      path="/api/horarios/unidadCurricular",
     *     summary="Obtener todas las UnidadCurricular",
     *     description="Devuelve todas las UnidadCurricular",
     *     operationId="getUnidadCurricular",
     *     tags={"UnidadCurricular"},
     *     @OA\Response(
     *          response=200,
     *          description="UnidadCurricular",
     *          @OA\JsonContent(
     *              type="array",
     *              @OA\Items(ref="#/components/schemas/UnidadCurricular")
     *          )
     *      ),
     *     @OA\Response(
     *          response=500,
     *          description="Error al obtener las UnidadCurricular"
     *      )
     * )
     */
    public function index()
    {
        return $this->unidadCurricularService->obtenerUnidadCurricular();
    }


    /**
     * @OA\Get(
     *     path="/api/horarios/unidadCurricular/{id}",
     *      operationId="getUnidadCurricularById",
     *      tags={"UnidadCurricular"},
     *      summary="Get unidadCurricular information",
     *      description="Returns unidadCurricular data",
     *      @OA\Parameter(
     *          name="id",
     *          description="UnidadCurricular id",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *          @OA\JsonContent(ref="#/components/schemas/UnidadCurricular")
     *      ),
     *      @OA\Response(response=400, description="Bad request"),
     *      @OA\Response(response=401, description="Unauthorized"),
     *      @OA\Response(response=404, description="Resource Not Found"),
     *      @OA\Response(response=500, description="Internal Server Error")
     *     )
     */
    public function show($id)
    {
        return $this->unidadCurricularService->obtenerUnidadCurricularPorId($id);
    }


    /**
     * @OA\Post(
     *     path="/api/horarios/unidadCurricular/guardar",
     *      operationId="storeUnidadCurricular",
     *      tags={"UnidadCurricular"},
     *      summary="Store new unidadCurricular",
     *      description="Returns unidadCurricular data",
     *      @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(ref="#/components/schemas/UnidadCurricularDTO")
     *      ),
     *      @OA\Response(
     *          response=201,
     *          description="Successful operation",
     *          @OA\JsonContent(ref="#/components/schemas/UnidadCurricular")
     *      ),
     *      @OA\Response(response=400, description="Bad request"),
     *      @OA\Response(response=401, description="Unauthorized"),
     *      @OA\Response(response=404, description="Resource Not Found"),
     *      @OA\Response(response=500, description="Internal Server Error")
     *     )
     */
    public function store(UnidadCurricularRequest $request)
    {
        return $this->unidadCurricularService->guardarUnidadCurricular($request);
    }


    /**
     * @OA\Put(
     *     path="/api/horarios/unidadCurricular/actualizar/{id}",
     *      operationId="updateUnidadCurricular",
     *      tags={"UnidadCurricular"},
     *      summary="Update existing unidadCurricular",
     *      description="Returns updated unidadCurricular data",
     *      @OA\Parameter(
     *          name="id",
     *          description="UnidadCurricular id",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     *      @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(ref="#/components/schemas/UnidadCurricularDTO")
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *          @OA\JsonContent(ref="#/components/schemas/UnidadCurricular")
     *      ),
     *      @OA\Response(response=400, description="Bad request"),
     *      @OA\Response(response=401, description="Unauthorized"),
     *      @OA\Response(response=404, description="Resource Not Found"),
     *      @OA\Response(response=500, description="Internal Server Error")
     *     )
     */
    public function update(UnidadCurricularRequest $request, $id)
    {
        $detalle = $request->input('detalles');
        $usuario = $request->input('usuario');

        DB::beginTransaction();

        try {
            
            $unidadCurricularResponse  = $this->unidadCurricularService->actualizarUnidadCurricular($request, $id);
    
            if ($unidadCurricularResponse->getStatusCode() != 200) {
                DB::rollBack();
                return response()->json(['error' => 'Hubo un error al actualizar la unidad curricular'], 500);
            }

            $unidadCurricular = $unidadCurricularResponse->getData();
    
            $nombreUnidadCurricular = $unidadCurricular->unidad_curricular;
            $accion = "Actualizacion de la unidad curricular " . $nombreUnidadCurricular."(id:".$unidadCurricular->id_uc.")";
            
            $this->logModificacionEliminacionController->store($accion,$usuario,$detalle);

            DB::commit();
    
            return response()->json(['message' => 'Unidad curricular actualizada exitosamente'], 200);
            
        } catch (\Exception $e) {
            DB::rollBack();
    
            Log::error("Error al actualizar la unidad curricular: " . $e->getMessage());
    
            return response()->json(['error' => 'Hubo un error al actualizar la unidad curricular'], 500);
        }
    }


    /**
     * @OA\Delete(
     *     path="/api/horarios/unidadCurricular/eliminar/{id}",
     *      operationId="deleteUnidadCurricular",
     *      tags={"UnidadCurricular"},
     *      summary="Delete existing unidadCurricular",
     *      description="Deletes a record and returns no content",
     *      @OA\Parameter(
     *          name="id",
     *          description="UnidadCurricular id",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     *      @OA\Response(
     *          response=204,
     *          description="Successful operation",
     *      ),
     *      @OA\Response(response=400, description="Bad request"),
     *      @OA\Response(response=401, description="Unauthorized"),
     *      @OA\Response(response=404, description="Resource Not Found"),
     *      @OA\Response(response=500, description="Internal Server Error")
     *     )
     */
    public function destroy($id, LogsRequest $request)
    {
        $detalle = $request->input('detalles');
        $usuario = $request->input('usuario');

        DB::beginTransaction();

        try {
            $unidadCurricularResponse = $this->unidadCurricularService->eliminarUnidadCurricular($id);
            
            $unidadCurricular = $unidadCurricularResponse->getData();
            if (!isset($unidadCurricular->nombre_uc)) {
                throw new \Exception('No se pudo obtener el nombre de la unidad curricular.');
            }

            $nombreUnidadCurricular = $unidadCurricular->nombre_uc;
            $accion = "Eliminación de la unidad curricular " . $nombreUnidadCurricular."(id:".$id.")";
            
            $this->logModificacionEliminacionController->store($accion,$usuario,$detalle);

            DB::commit();

            return response()->json([
                'message' => 'Unidad curricular eliminada correctamente.'
            ], 200);

        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'error' => 'Hubo un problema al eliminar la unidad curricular: ' . $e->getMessage()
            ], 500);
        }

    }

}
