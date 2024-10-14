<?php

namespace App\Http\Controllers\horarios;

use App\Services\horarios\UnidadCurricularService;
use App\Http\Requests\horarios\UnidadCurricularRequest;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;


class UnidadCurricularController extends Controller
{
    protected $unidadCurricularService;

    public function __construct(UnidadCurricularService $unidadCurricularService)
    {
        $this->unidadCurricularService = $unidadCurricularService;

    }


    //-------------------------------------------------------------------------------------------------------------
    // Swagger Documentation


    /**
     * @OA\Get(
     *     path="/api/horarios/unidadCurricular",
     *      operationId="getUnidadCurricularList",
     *      tags={"UnidadCurricular"},
     *      summary="Get list of unidadCurricular",
     *      description="Returns list of unidadCurricular",
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *          @OA\JsonContent(
     *             type="array",
     *            @OA\Items(ref="#/components/schemas/UnidadCurricular")
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
     *     path="/api/horarios/unidadCurricular",
     *      operationId="storeUnidadCurricular",
     *      tags={"UnidadCurricular"},
     *      summary="Store new unidadCurricular",
     *      description="Returns unidadCurricular data",
     *      @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(ref="#/components/schemas/UnidadCurricular")
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
    public function store(Request $request)
    {
        return $this->unidadCurricularService->guardarUnidadCurricular($request);
    }


    /**
     * @OA\Put(
     *     path="/api/horarios/unidadCurricular/{id}",
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
     *          @OA\JsonContent(ref="#/components/schemas/UnidadCurricular")
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
        return $this->unidadCurricularService->actualizarUnidadCurricular($request, $id);
    }

    
    /**
     * @OA\Delete(
     *     path="/api/horarios/unidadCurricular/{id}",
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
    public function destroy($id)
    {
        return $this->unidadCurricularService->eliminarUnidadCurricular($id);
    }

}
