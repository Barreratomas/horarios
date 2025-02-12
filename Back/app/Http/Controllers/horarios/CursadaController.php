<?php

namespace App\Http\Controllers\horarios;

use App\Models\horarios\Cursada;
use App\Services\horarios\CursadaService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\LogModificacionEliminacionController;
use App\DTO;
use App\Http\Requests\LogsRequest;

class CursadaController extends Controller
{
    protected $cursadaService;
    protected $logModificacionEliminacionController; 

    public function __construct(CursadaService $cursadaService,  LogModificacionEliminacionController $logModificacionEliminacionController)
    {
        $this->cursadaService = $cursadaService;
        $this->logModificacionEliminacionController = $logModificacionEliminacionController;

    }

    //-------------------------------------------------------------------------------------------------------------
    // Swagger Documentation

    /**
     * @OA\Get(
     *      path="/api/horarios/cursadas",
     *     summary="Obtener todas las cursadas",
     *     description="Devuelve todas las cursadas",
     *     operationId="getCursadas",
     *     tags={"Cursada"},
     *     @OA\Response(
     *          response=200,
     *          description="Cursadas",
     *          @OA\JsonContent(
     *              type="array",
     *              @OA\Items(ref="#/components/schemas/Cursada")
     *          )
     *      ),
     *     @OA\Response(
     *          response=500,
     *          description="Error al obtener las cursadas"
     *      )
     * )
     */
    public function index()
    {
        return $this->cursadaService->obtenerCursadas();
    }


    /**
     * @OA\Get(
     *     path="/api/horarios/cursadas/{id}",
     *     summary="Obtener una cursada por id",
     *     description="Obtener una cursada por id",
     *     operationId="obtenerCursadaPorId",
     *     tags={"Cursada"},
     *     @OA\Parameter(
     *          name="id",
     *          in="path",
     *          description="Id de la cursada",
     *          required=true,
     *          @OA\Schema(
     *              type="integer"
     *          )
     *     ),
     *     @OA\Response(
     *     response=200,
     *     description="Cursada obtenida correctamente",
     *     @OA\JsonContent(ref="#/components/schemas/Cursada")
     *     ),
     *     @OA\Response(
     *     response=404,
     *     description="No se encontró la cursada"
     *  ),
     *     @OA\Response(
     *     response=500,
     *     description="Error al obtener la cursada"
     *   )
     * )
     */
    public function show($id)
    {
        return $this->cursadaService->obtenerCursadasPorId($id);
    }



    /**
     * @OA\Post(
     *     path="/api/horarios/cursadas/guardar",
     *     summary="Guardar un cursada",
     *     description="Guardar un cursada",
     *     operationId="guardarCursada",
     *     tags={"Cursada"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/CursadaDTO")
     *     ),
     *     @OA\Response(
     *     response=201,
     *     description="Cursada guardada correctamente",
     *     @OA\JsonContent(ref="#/components/schemas/CursadaDTO")
     *     ),
     *     @OA\Response(
     *     response=400,
     *     description="Error al guardar el cursada"
     *     )
     * )
     */
    public function store(Request $request)
    {
        return $this->cursadaService->guardarCursadas($request);
    }




    /**
     * @OA\Put(
     *     path="/api/horarios/cursadas/actualizar/{id}",
     *     summary="Actualizar un cursada",
     *     description="Actualizar un cursada",
     *     operationId="actualizarCursada",
     *     tags={"Cursada"},
     *    @OA\Parameter(
     *      name="id",
     *      in="path",
     *      description="Id de la cursada",
     *      required=true,
     *      @OA\Schema(
     *          type="integer"
     *      ) 
     *     ),
     *     @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(ref="#/components/schemas/CursadaDTO")
     *     ),
     *     @OA\Response(
     *          response=200,
     *          description="Cursada actualizada correctamente",
     *          @OA\JsonContent(ref="#/components/schemas/Cursada")
     *     ),
     *     @OA\Response(
     *          response=500,
     *          description="Error al actualizar la cursada"
     *     )
     * )
     */
    public function update(Request $request, $id)
    {
        return $this->cursadaService->actualizarCursadas($request, $id);
    }


    /**
     * @OA\Delete(
     *     path="/api/horarios/cursadas/eliminar/{id}",
     *     summary="Eliminar un cursada",
     *     description="Eliminar un cursada",
     *     operationId="eliminarCursada",
     *     tags={"Cursada"},
     *     @OA\Parameter(
     *     name="id",
     *     in="path",
     *     description="Id de la cursada",
     *     required=true,
     *     @OA\Schema(
     *     type="integer"
     *    )
     *     ),
     *     @OA\Response(
     *     response=200,
     *     description="Cursada eliminada correctamente"
     * ),
     *     @OA\Response(
     *     response=500,
     *     description="Error al eliminar la cursada"
     * )
     * )
     */
    public function destroy($id)
    {
        return $this->cursadaService->eliminarCursadas($id);
    }

}
