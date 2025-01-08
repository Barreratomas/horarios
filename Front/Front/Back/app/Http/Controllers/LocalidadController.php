<?php

namespace App\Http\Controllers;

use App\Services\LocalidadService;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class LocalidadController extends Controller
{
    protected $localidadService;

    public function __construct(LocalidadService $localidadService)
    {
        $this->localidadService = $localidadService;
    }

    /**
     * @OA\Get(
     *     path="/api/localidades",
     *     tags={"Localidad"},
     *     summary="Obtener todas las localidades",
     *     description="Retorna un array de localidades",
     *     @OA\Response(
     *         response=200,
     *         description="Operación exitosa"
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Error al obtener las localidades"
     *     )
     * )
     */
    public function index()
    {
        return $this->localidadService->obtenerTodasLocalidades();
    }

    /**
     * @OA\Get(
     *     path="/api/localidades/{id}",
     *     tags={"Localidad"},
     *     summary="Obtener una localidad por ID",
     *     description="Retorna una localidad",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID de la localidad",
     *         required=true,
     *         @OA\Schema(
     *             type="integer",
     *             format="int64"
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Operación exitosa"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="No se encontró la localidad"
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Error al obtener la localidad"
     *     )
     * )
     */
    public function show($id)
    {
        return $this->localidadService->obtenerLocalidadPorId($id);
    }

    /**
     * @OA\Post(
     *     path="/api/localidades/guardar",
     *     tags={"Localidad"},
     *     summary="Guardar una localidad",
     *     description="Guardar una localidad",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/LocalidadDTO")
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Operación exitosa"
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Error al guardar la localidad"
     *     )
     * )
     */
    public function store(Request $request)
    {
        return $this->localidadService->guardarLocalidad($request);
    }

    /**
     * @OA\Put(
     *     path="/api/localidades/actualizar/{id}",
     *     tags={"Localidad"},
     *     summary="Actualizar una localidad",
     *     description="Actualizar una localidad",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID de la localidad",
     *         required=true,
     *         @OA\Schema(
     *             type="integer",
     *             format="int64"
     *         )
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/LocalidadDTO")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Operación exitosa"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="No se encontró la localidad"
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Error al actualizar la localidad"
     *     )
     * )
     */
    public function update(Request $request, $id)
    {
        return $this->localidadService->actualizarLocalidad($request, $id);
    }

    /**
     * @OA\Delete(
     *     path="/api/localidades/{id}",
     *     tags={"Localidad"},
     *     summary="Eliminar una localidad",
     *     description="Eliminar una localidad",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID de la localidad",
     *         required=true,
     *         @OA\Schema(
     *             type="integer",
     *             format="int64"
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Operación exitosa"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="No se encontró la localidad"
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Error al eliminar la localidad"
     *     )
     * )
     */
    public function destroy($id)
    {
        return $this->localidadService->eliminarLocalidadPorId($id);
    }
}
