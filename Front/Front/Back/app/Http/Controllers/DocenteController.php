<?php

namespace App\Http\Controllers;

use App\Http\Requests\DocenteRequest;
use App\Models\Docente;
use Illuminate\Http\Request;
use App\Services\DocenteService;
use App\Http\Controllers\Controller;


class DocenteController extends Controller
{
    protected $docenteService;


    public function __construct(DocenteService $docenteService)
    {
        $this->docenteService = $docenteService;
    }

    
    //------------------------------------------------------------------------------------------------------------------
    // Swagger

    /**
     * @OA\Get(
     *     path="/api/docentes",
     *     tags={"Docente"},
     *     summary="Obtener todos los docentes",
     *     description="Devuelve todos los docentes",
     *     operationId="obtenerDocente",
     *     @OA\Response(
     *     response=200,
     *     description="Docentes",
     *     @OA\JsonContent(
     *     type="array",
     *     @OA\Items(ref="#/components/schemas/Docente")
     *   )
     * ),
     *     @OA\Response(
     *     response=500,
     *     description="Error al obtener los docentes"
     * )
     * )
     */
    public function index()
    {
        return $this->docenteService->obtenerTodosLosDocente();
    }

    /**
     * @OA\Get(
     *     path="/api/docentes/{id}",
     *     tags={"Docente"},
     *     summary="Obtener docente por dni",
     *     description="Devuelve un docente",
     *     operationId="obtenerDocentePorDni",
     *     @OA\Parameter(
     *     name="dni",
     *     in="path",
     *     description="DNI del docente",
     *     required=true,
     *     @OA\Schema(
     *     type="string"
     * )
     * ),
     *     @OA\Response(
     *     response=200,
     *     description="Docente",
     *     @OA\JsonContent(ref="#/components/schemas/Docente")
     * ),
     *     @OA\Response(
     *     response=404,
     *     description="Docente no encontrado"
     * ),
     *     @OA\Response(
     *     response=500,
     *     description="Error al obtener el docente"
     * )
     * )
     */
    public function obtenerDocentePorId($id)
    {
        return $this->docenteService->obtenerDocentePorId($id);
    }

    /**
     * @OA\Post(
     *     path="/api/docentes/guardar",
     *     tags={"Docente"},
     *     summary="Guardar docente",
     *     description="Guarda un docente",
     *     operationId="guardarDocente",
     *     @OA\RequestBody(
     *     required=true,
     *     @OA\JsonContent(ref="#/components/schemas/DocenteDTO")
     * ),
     *     @OA\Response(
     *     response=200,
     *     description="Docente guardado correctamente"
     * ),
     *     @OA\Response(
     *     response=500,
     *     description="Error al guardar el docente"
     * )
     * )
     */
    public function guardarDocentes(Request $request)
    {
        $docente = $request->all();
        return $this->docenteService->guardarDocentes($docente);
    }

    /**
     * @OA\Put(
     *     path="/api/docentes/actualizar/{id}",
     *     tags={"Docente"},
     *     summary="Actualizar docente",
     *     description="Actualiza un docente",
     *     operationId="actualizarDocente",
     *     @OA\Parameter(
     *     name="id",
     *     in="path",
     *     description="ID del docente",
     *     required=true,
     *     @OA\Schema(
     *     type="string"
     * )
     * ),
     *     @OA\RequestBody(
     *     required=true,
     *     @OA\JsonContent(ref="#/components/schemas/DocenteDTO")
     * ),
     *     @OA\Response(
     *     response=200,
     *     description="Docente actualizado correctamente"
     * ),
     *     @OA\Response(
     *     response=404,
     *     description="Docente no encontrado"
     * ),
     *     @OA\Response(
     *     response=500,
     *     description="Error al actualizar el docente"
     * )
     * )
     */
    public function actualizarDocentes(Request $request, $id)
    {
        $docente = $request->all();
        return $this->docenteService->actualizarDocentes($docente, $id);
    }

    /**
     * @OA\Delete(
     *     path="/api/docentes/eliminar/{id}",
     *     tags={"Docente"},
     *     summary="Eliminar docente",
     *     description="Elimina un docente",
     *     operationId="eliminarDocente",
     *     @OA\Parameter(
     *     name="id",
     *     in="path",
     *     description="ID del docente",
     *     required=true,
     *     @OA\Schema(
     *     type="string"
     * )
     * ),
     *     @OA\Response(
     *     response=200,
     *     description="Docente eliminado correctamente"
     * ),
     *     @OA\Response(
     *     response=404,
     *     description="Docente no encontrado"
     * ),
     *     @OA\Response(
     *     response=500,
     *     description="Error al eliminar el docente"
     * )
     * )
     */
    public function eliminarDocentes($id)
    {
        return $this->docenteService->eliminarDocentes($id);
    }
}











