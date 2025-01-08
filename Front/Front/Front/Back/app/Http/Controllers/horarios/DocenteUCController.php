<?php

namespace App\Http\Controllers\horarios;

use App\Http\Requests\DocenteRequest;
use App\Http\Requests\horarios\DocenteUCRequest; // Ensure this class exists in the specified namespace
use App\Models\Docente;
use Illuminate\Http\Request;
use App\Services\horarios\DocenteUCService;
use App\Http\Controllers\Controller;


class DocenteUCController extends Controller
{
    protected $docenteUCService;


    public function __construct(DocenteUCService $docenteUCService)
    {
        $this->docenteUCService = $docenteUCService;
    }


    //------------------------------------------------------------------------------------------------------------------
    // Swagger


    /**
     * @OA\Get(
     *     path="/api/horarios/docenteUC",
     *     tags={"DocenteUC"},
     *     summary="Obtener todos los docentesUC",
     *     description="Retorna un array de docentesUC",
     *     @OA\Response(
     *         response=200,
     *         description="Operación exitosa"
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Error al obtener los docentesUC"
     *     )
     * )
     */
    public function index(){
        return $this->docenteUCService->obtenerTodosDocentesUC();
    }

    /**
     * @OA\Get(
     *     path="/api/horarios/docenteUC/idDocente/{id}",
     *     tags={"DocenteUC"},
     *     summary="Obtener un docenteUC por ID de docente",
     *     description="Retorna un docenteUC",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID del docente",
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
     *         description="DocenteUC no encontrada"
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Error al obtener el docenteUC"
     *     )
     * )
     */
    public function obtenerDocenteUCPorIdDocente($id)
    {
        return $this->docenteUCService->obtenerDocenteUCPorIdDocente($id);
    }

    /**
     * @OA\Get(
     *     path="/api/horarios/docenteUC/idUC/{id}",
     *     tags={"DocenteUC"},
     *     summary="Obtener un docenteUC por ID de UC",
     *     description="Retorna un docenteUC",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID de la UC",
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
     *         description="DocenteUC no encontrada"
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Error al obtener el docenteUC"
     *     )
     * )
     */
    public function obtenerDocenteUCPorIdUC($id)
    {
        return $this->docenteUCService->obtenerDocenteUCPorIdUC($id);
    }

    /**
     * @OA\Post(
     *     path="/api/horarios/docenteUC/guardar",
     *     tags={"DocenteUC"},
     *     summary="Guardar un docenteUC",
     *     description="Retorna el docenteUC guardado",
     *     @OA\RequestBody(
     *         @OA\JsonContent(ref="#/components/schemas/DocenteUC")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Operación exitosa"
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Error al guardar el docenteUC"
     *     )
     * )
     */
    public function store(DocenteUCRequest $request)
    {
        return $this->docenteUCService->guardarDocenteUC($request);
    }

    /**
     * @OA\Put(
     *     path="/api/horarios/docenteUC/actualizar/idDocente/{id}",
     *     tags={"DocenteUC"},
     *     summary="Actualizar un docenteUC por ID de docente",
     *     description="Retorna el docenteUC actualizado",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID del docente",
     *         required=true,
     *         @OA\Schema(
     *             type="integer",
     *             format="int64"
     *         )
     *     ),
     *     @OA\RequestBody(
     *         @OA\JsonContent(ref="#/components/schemas/DocenteUC")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Operación exitosa"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="DocenteUC no encontrada"
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Error al actualizar el docenteUC"
     *     )
     * )
     */
    public function actualizarDocenteUCPorIdDocente(DocenteUCRequest $request, $id)
    {
        return $this->docenteUCService->actualizarDocenteUCPorIdDocente($request, $id);
    }
    
    /**
     * @OA\Put(
     *     path="/api/horarios/docenteUC/actualizar/idUC/{id}",
     *     tags={"DocenteUC"},
     *     summary="Actualizar un docenteUC por ID de UC",
     *     description="Retorna el docenteUC actualizado",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID de la UC",
     *         required=true,
     *         @OA\Schema(
     *             type="integer",
     *             format="int64"
     *         )
     *     ),
     *     @OA\RequestBody(
     *         @OA\JsonContent(ref="#/components/schemas/DocenteUC")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Operación exitosa"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="DocenteUC no encontrada"
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Error al actualizar el docenteUC"
     *     )
     * )
     */
    public function actualizarDocenteUCPorIdUC(DocenteUCRequest $request, $id){
        return $this->docenteUCService->actualizarDocenteUCPorIdUC($request, $id);
    }

    /**
     * @OA\Delete(
     *     path="/api/horarios/docenteUC/eliminar/idDocente/{id}",
     *     tags={"DocenteUC"},
     *     summary="Eliminar un docenteUC por ID de docente",
     *     description="Retorna el docenteUC eliminado",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID del docente",
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
     *         description="DocenteUC no encontrada"
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Error al eliminar el docenteUC"
     *     )
     * )
     */
    public function eliminarDocenteUCPorIdDocente($id)
    {
        return $this->docenteUCService->eliminarDocenteUCPorIdDocente($id);
    }

    /**
     * @OA\Delete(
     *     path="/api/horarios/docenteUC/eliminar/idUC/{id}",
     *     tags={"DocenteUC"},
     *     summary="Eliminar un docenteUC por ID de UC",
     *     description="Retorna el docenteUC eliminado",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID de la UC",
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
     *         description="DocenteUC no encontrada"
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Error al eliminar el docenteUC"
     *     )
     * )
     */
    public function eliminarDocenteUCPorIdUC($id)
    {
        return $this->docenteUCService->eliminarDocenteUCPorIdUC($id);
    }

}











