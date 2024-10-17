<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Services\CarreraGradoService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class CarreraGradoController extends Controller
{
    protected $carreraGradoService;

    public function __construct(CarreraGradoService $carreraGradoService)
    {
        $this->carreraGradoService = $carreraGradoService;
    }


    /*
    * @OA\Get(
    *      path="/api/horarios/carreraGrados",
    *      summary="Obtener todas las relaciones Carrera-Grado",
    *      description="Devuelve todas las relaciones Carrera-Grado",
    *      operationId="getCarreraGrados",
    *      tags={"CarreraGrado"},
    *      @OA\Response(
    *          response=200,
    *          description="Relaciones obtenidas correctamente",
    *          @OA\JsonContent(
    *              type="array",
    *              @OA\Items(ref="#/components/schemas/CarreraGrado")
    *          )
    *      ),
    *      @OA\Response(
    *          response=500,
    *          description="Error al obtener las relaciones"
    *      )
    * )
    */
    public function index()
    {
        return $this->carreraGradoService->obtenerTodosCarreraGrado();
    }
    

    /*
    * @OA\Get(
    *      path="/api/horarios/carreraGrados/carrera/{id_carrera}",
    *      summary="Obtener relaciones Carrera-Grado por ID de carrera",
    *      description="Devuelve la relación Carrera-Grado correspondiente al ID de una carrera",
    *      operationId="getCarreraGradoPorIdCarrera",
    *      tags={"CarreraGrado"},
    *      @OA\Parameter(
    *          name="id_carrera",
    *          in="path",
    *          description="ID de la carrera",
    *          required=true,
    *          @OA\Schema(type="integer")
    *      ),
    *      @OA\Response(
    *          response=200,
    *          description="Relación obtenida correctamente",
    *          @OA\JsonContent(ref="#/components/schemas/CarreraGrado")
    *      ),
    *      @OA\Response(
    *          response=404,
    *          description="Relación no encontrada"
    *      )
    * )
    */
    public function showByCarrera($id_carrera)
    {
        return $this->carreraGradoService->obtenerCarreraGradoPorIdCarrera($id_carrera);
    }
    

    /*
    * @OA\Get(
    *      path="/api/horarios/carreraGrados/grado/{id_grado}",
    *      summary="Obtener relaciones Carrera-Grado por ID de grado",
    *      description="Devuelve la relación Carrera-Grado correspondiente al ID de un grado",
    *      operationId="getCarreraGradoPorIdGrado",
    *      tags={"CarreraGrado"},
    *      @OA\Parameter(
    *          name="id_grado",
    *          in="path",
    *          description="ID del grado",
    *          required=true,
    *          @OA\Schema(type="integer")
    *      ),
    *      @OA\Response(
    *          response=200,
    *          description="Relación obtenida correctamente",
    *          @OA\JsonContent(ref="#/components/schemas/CarreraGrado")
    *      ),
    *      @OA\Response(
    *          response=404,
    *          description="Relación no encontrada"
    *      )
    * )
    */
    public function showByGrado($id_grado)
    {
        return $this->carreraGradoService->obtenerCarreraGradoPorIdGrado($id_grado);
    }


    /*
    * @OA\Post(
    *      path="/api/horarios/carreraGrados/guardar/{id_carrera}/{id_grado}",
    *      summary="Guardar una relación Carrera-Grado",
    *      description="Crea una nueva relación Carrera-Grado",
    *      operationId="guardarCarreraGrado",
    *      tags={"CarreraGrado"},
    *      @OA\RequestBody(
    *          required=true,
    *          @OA\JsonContent(ref="#/components/schemas/CarreraGrado")
    *      ),
    *      @OA\Response(
    *          response=201,
    *          description="Relación creada correctamente",
    *          @OA\JsonContent(ref="#/components/schemas/CarreraGrado")
    *      ),
    *      @OA\Response(
    *          response=400,
    *          description="Error al crear la relación"
    *      )
    * )
    */
    public function store($id_carrera, $id_grado)
    {
        return $this->carreraGradoService->guardarCarreraGrado($id_carrera, $id_grado);
    }

   
    /*
    * @OA\Delete(
    *      path="/api/horarios/carreraGrados/eliminar/{id_carrera}/{id_grado}",
    *      summary="Eliminar una relación Carrera-Grado",
    *      description="Elimina una relación Carrera-Grado correspondiente al ID de una carrera y un grado",
    *      operationId="eliminarCarreraGrado",
    *      tags={"CarreraGrado"},
    *      @OA\Parameter(
    *          name="id_carrera",
    *          in="path",
    *          description="ID de la carrera",
    *          required=true,
    *          @OA\Schema(type="integer")
    *      ),
    *      @OA\Parameter(
    *          name="id_grado",
    *          in="path",
    *          description="ID del grado",
    *          required=true,
    *          @OA\Schema(type="integer")
    *      ),
    *      @OA\Response(
    *          response=200,
    *          description="Relación eliminada correctamente"
    *      ),
    *      @OA\Response(
    *          response=404,
    *          description="Relación no encontrada"
    *      )
    * )
    */
    public function destroy($id_carrera, $id_grado)
    {
       return $this->carreraGradoService->eliminarCarreraGradoPorIdGradoYCarrera( $id_carrera, $id_grado);
    }
}
