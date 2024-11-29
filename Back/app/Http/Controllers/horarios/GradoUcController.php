<?php

namespace App\Http\Controllers\horarios;

use App\Http\Controllers\Controller;
use App\Services\horarios\GradoUcService;
use Illuminate\Http\Request;

class GradoUcController extends Controller
{
    protected $gradoUCService;

    public function __construct(GradoUcService $gradoUCService)
    {
        $this->gradoUCService = $gradoUCService;
    }


    /** 
        * @OA\Get(
        *     path="/api/horarios/gradoUC",
        *     tags={"GradoUC"},
        *     summary="Obtener todos los gradosUC",
        *     description="Retorna un array de gradosUC",
        *     @OA\Response(
        *         response=200,
        *         description="Operación exitosa"
        *     ),
        *     @OA\Response(
        *         response=500,
        *         description="Error al obtener los gradosUC"
        *     )
        * )
    */
    public function index()
    {
        return $this->gradoUCService->obtenerTodosGradoUC();
    }
    
    /**
        * @OA\Get(
        *     path="/api/horarios/gradoUC/idGrado/{id}",
        *     tags={"GradoUC"},
        *     summary="Obtener un gradoUC por ID de grado",
        *     description="Retorna un gradoUC",
        *     @OA\Parameter(
        *         name="id",
        *         in="path",
        *         description="ID del grado",
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
        *         description="No se encontró el gradoUC"
        *     ),
        *     @OA\Response(
        *         response=500,
        *         description="Error al obtener el gradoUC"
        *     )
        * )
    */


    public function obtenerGradoUcPorIdGrado($id_grado){
        return $this->gradoUCService->obtenerGradoUcPorIdGrado($id_grado);
    }


    

    public function obtenerGradoUcPorIdGradoConRelaciones($id_grado){
        return $this->gradoUCService->obtenerGradoUcPorIdGradoConRelaciones($id_grado);
    }
    /**
        * @OA\Get(
        *     path="/api/horarios/gradoUC/idUC/{id}",
        *     tags={"GradoUC"},
        *     summary="Obtener un gradoUC por ID de UC",
        *     description="Retorna un gradoUC",
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
        *         description="No se encontró el gradoUC"
        *     ),
        *     @OA\Response(
        *         response=500,
        *         description="Error al obtener el gradoUC"
        *     )
        * )
    */
    public function obtenerGradoUcPorIdUC($id_UC){
        return $this->gradoUCService->obtenerGradoUcPorIdUC($id_UC);
    }

    /**
        * @OA\Post(
        *     path="/api/horarios/gradoUC/guardar",
        *     tags={"GradoUC"},
        *     summary="Guardar un gradoUC",
        *     description="Guardar un gradoUC",
        *     @OA\RequestBody(
        *         required=true,
        *         @OA\JsonContent(ref="#/components/schemas/GradoUC")
        *     ),
        *     @OA\Response(
        *         response=201,
        *         description="Operación exitosa"
        *     ),
        *     @OA\Response(
        *         response=500,
        *         description="Error al guardar el gradoUC"
        *     )
        * )
    */
    public function store(Request $request)
    {
        $id_grado = $request->input('id_grado'); 
        $materias = $request->input('materias');
        return $this->gradoUCService->guardarGradoUC($id_grado, $materias);
    }

    /**
        * @OA\Delete(
        *     path="/api/horarios/gradoUC/idGrado/{id}",
        *     tags={"GradoUC"},
        *     summary="Eliminar un gradoUC por ID de grado",
        *     description="Eliminar un gradoUC",
        *     @OA\Parameter(
        *         name="id",
        *         in="path",
        *         description="ID del grado",
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
        *         description="No se encontró el gradoUC"
        *     ),
        *     @OA\Response(
        *         response=500,
        *         description="Error al eliminar el gradoUC"
        *     )
        * )
    */
    public function eliminarGradoUcPorIdGrado($id_grado)
    {
        return $this->gradoUCService->eliminarGradoUcPorIdGrado($id_grado);
    }

    /**
        * @OA\Delete(
        *     path="/api/horarios/gradoUC/idUC/{id}",
        *     tags={"GradoUC"},
        *     summary="Eliminar un gradoUC por ID de UC",
        *     description="Eliminar un gradoUC",
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
        *         description="No se encontró el gradoUC"
        *     ),
        *     @OA\Response(
        *         response=500,
        *         description="Error al eliminar el gradoUC"
        *     )
        * )
    */
    public function eliminarGradoUcPorIdUC($id_UC)
    {
        return $this->gradoUCService->eliminarGradoUcPorIdUC($id_UC);
    }
}
