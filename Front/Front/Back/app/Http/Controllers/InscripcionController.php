<?php

namespace App\Http\Controllers;

use App\Services\InscripcionService;
use App\Http\Requests\InscripcionRequest;
use App\Http\Controllers\Controller;


class InscripcionController extends Controller
{
    protected $inscripcionService;

    public function __construct(InscripcionService $inscripcionService)
    {
        $this->inscripcionService = $inscripcionService;

    }


    //-------------------------------------------------------------------------------------------------------------
    // Swagger Documentation


    /**
     * @OA\Get(
     *      path="/api/horarios/inscripcion",
     *      operationId="getInscripcionList",
     *      tags={"Inscripcion"},
     *      summary="Get list of inscripcion",
     *      description="Returns list of inscripcion",
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *          @OA\JsonContent(
     *            type="array",
     *           @OA\Items(ref="#/components/schemas/Inscripcion")
     *         )
     *       ),
     *      @OA\Response(response=400, description="Bad request"),
     *      @OA\Response(response=401, description="Unauthorized"),
     *      @OA\Response(response=404, description="Resource Not Found"),
     *      @OA\Response(response=500, description="Internal Server Error")
     *     )
     */
    public function index()
    {
        return $this->inscripcionService->obtenerTodosInscripcion();
    }


    /**
     * @OA\Get(
     *      path="/api/horarios/inscripcion/{id}",
     *      operationId="getInscripcionById",
     *      tags={"Inscripcion"},
     *      summary="Get inscripcion information",
     *      description="Returns inscripcion data",
     *      @OA\Parameter(
     *          name="id",
     *          description="Inscripcion Id",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *          @OA\JsonContent()
     *       ),
     *      @OA\Response(response=400, description="Bad request"),
     *      @OA\Response(response=401, description="Unauthorized"),
     *      @OA\Response(response=404, description="Resource Not Found"),
     *      @OA\Response(response=500, description="Internal Server Error")
     *     )
     */
    public function show($id)
    {
        return $this->inscripcionService->obtenerInscripcionPorId($id);
    }


    /**
     * @OA\Post(
     *      path="/api/horarios/inscripcion/guardar",
     *      operationId="storeInscripcion",
     *      tags={"Inscripcion"},
     *      summary="Store new inscripcion",
     *      description="Returns inscripcion data",
     *      @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(ref="#/components/schemas/InscripcionDTO")
     *      ),
     *      @OA\Response(
     *          response=201,
     *          description="Successful operation",
     *          @OA\JsonContent()
     *       ),
     *      @OA\Response(response=400, description="Bad request"),
     *      @OA\Response(response=401, description="Unauthorized"),
     *      @OA\Response(response=404, description="Resource Not Found"),
     *      @OA\Response(response=500, description="Internal Server Error")
     *     )
     */
    public function store(InscripcionRequest $request)
    {
        return $this->inscripcionService->guardarInscripcion($request);
    }


    /**
     * @OA\Put(
     *      path="/api/horarios/inscripcion/actualizar/{id}",
     *      operationId="updateInscripcion",
     *      tags={"Inscripcion"},
     *      summary="Update existing inscripcion",
     *      description="Returns updated inscripcion data",
     *      @OA\Parameter(
     *          name="id",
     *          description="Inscripcion Id",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     *      @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(ref="#/components/schemas/InscripcionDTO")
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *          @OA\JsonContent()
     *       ),
     *      @OA\Response(response=400, description="Bad request"),
     *      @OA\Response(response=401, description="Unauthorized"),
     *      @OA\Response(response=404, description="Resource Not Found"),
     *      @OA\Response(response=500, description="Internal Server Error")
     *     )
     */
    public function update(InscripcionRequest $request, $id)
    {
        return $this->inscripcionService->actualizarInscripcion($request, $id);
    }


    /**
     * @OA\Delete(
     *      path="/api/horarios/inscripcion/eliminar/{id}",
     *      operationId="deleteInscripcion",
     *      tags={"Inscripcion"},
     *      summary="Delete existing inscripcion",
     *      description="Deletes a record and returns no content",
     *      @OA\Parameter(
     *          name="id",
     *          description="Inscripcion Id",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     *      @OA\Response(
     *          response=204,
     *          description="Successful operation",
     *          @OA\JsonContent()
     *       ),
     *      @OA\Response(response=400, description="Bad request"),
     *      @OA\Response(response=401, description="Unauthorized"),
     *      @OA\Response(response=404, description="Resource Not Found"),
     *      @OA\Response(response=500, description="Internal Server Error")
     *     )
     */
    public function destroy($id)
    {
        return $this->inscripcionService->eliminarInscripcion($id);
    }


}
