<?php

namespace App\Http\Controllers\horarios;

use App\Http\Requests\horarios\GradoRequest;
use App\Models\horarios\Grado;
use App\Services\horarios\GradoService;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Log;

class GradoController extends Controller
{
    protected $gradoService;

    public function __construct(GradoService $gradoService)
    {
        $this->gradoService = $gradoService;
    }

    //-------------------------------------------------------------------------------------------------------------
    // Swagger Documentation

    /**
     * @OA\Get(
     *      path="/api/horarios/grados",
     *     summary="Obtener todos los grados",
     *     description="Devuelve todos los grados",
     *     operationId="getGrados",
     *     tags={"Grado"},
     *     @OA\Response(
     *          response=200,
     *          description="Grados",
     *          @OA\JsonContent(
     *              type="array",
     *              @OA\Items(ref="#/components/schemas/Grado")
     *          )
     *      ),
     *     @OA\Response(
     *          response=500,
     *          description="Error al obtener los grados"
     *      )
     * )
     */
    public function index()
    {
        return $this->gradoService->obtenerGrados();
    }

    /**
     * @OA\Get(
     *     path="/api/horarios/grados/{id}",
     *     summary="Obtener un grado por id",
     *     description="Obtener un grado por id",
     *     operationId="obtenerGradoPorId",
     *     tags={"Grado"},
     *     @OA\Parameter(
     *     name="id",
     *     in="path",
     *     description="Id del grado",
     *     required=true,
     *     @OA\Schema(
     *         type="integer"
     *     )
     *     ),
     *     @OA\Response(
     *     response=200,
     *     description="Grado obtenido correctamente",
     *     @OA\JsonContent(ref="#/components/schemas/Grado")
     *     ),
     *     @OA\Response(
     *     response=404,
     *     description="No se encontró el grado"
     *  ),
     *     @OA\Response(
     *     response=500,
     *     description="Error al obtener el grado"
     *   )
     * )
     */
    public function show($id)
    {
        return $this->gradoService->obtenerGradoPorId($id);
    }

    /**
     * @OA\Post(
     *     path="/api/horarios/grados/guardar",
     *     summary="Guardar un grado",
     *     description="Guardar un grado",
     *     operationId="guardarGrado",
     *     tags={"Grado"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/GradoDTO")
     *     ),
     *     @OA\Response(
     *     response=200,
     *     description="Grado guardado correctamente"
     *  ),
     *     @OA\Response(
     *     response=400,
     *     description="Error al guardar el grado"
     *  )
     * )
     */
    public function store(GradoRequest $request)
    {
        return $this->gradoService->guardarGrados($request);
        // $gradoResponse = $this->gradoService->guardarGrados($request);
        // $grado = $gradoResponse->getData();  // Extrae el contenido del JSON

        // Log::info('ID del grado:', [ $grado->id_grado ]);
    }

    /**
     * @OA\Put(
     *     path="/api/horarios/grados/actualizar/{id}",
     *     summary="Actualizar un grado",
     *     description="Actualizar un grado",
     *     operationId="actualizarGrado",
     *     tags={"Grado"},
     *     @OA\Parameter(
     *          name="id",
     *          in="path",
     *          description="Id del grado",
     *          required=true,
     *          @OA\Schema(
     *              type="integer"
     *          )
     *     ),
     *     @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(ref="#/components/schemas/GradoDTO")
     *     ),
     *     @OA\Response(
     *          response=200,
     *          description="Grado actualizado correctamente"
     *     ),
     *     @OA\Response(
     *          response=500,
     *          description="Error al actualizar el grado"
     *     )
     * )
     */
    public function update(GradoRequest $request, $id)
    {
        return $this->gradoService->actualizarGrados($request, $id);
    }

    /**
     * @OA\Delete(
     *     path="/api/horarios/grados/eliminar/{id}",
     *     summary="Eliminar un grado",
     *     description="Eliminar un grado",
     *     operationId="eliminarGrado",
     *     tags={"Grado"},
     *     @OA\Parameter(
     *     name="id",
     *     in="path",
     *     description="Id del grado",
     *     required=true,
     *     @OA\Schema(
     *     type="integer"
     *    )
     *     ),
     *     @OA\Response(
     *     response=200,
     *     description="Grado eliminado correctamente"
     * ),
     *     @OA\Response(
     *     response=500,
     *     description="Error al eliminar el grado"
     * )
     * )
     */
    public function destroy($id)
    {
        return $this->gradoService->eliminarGrados($id);
    }
}
