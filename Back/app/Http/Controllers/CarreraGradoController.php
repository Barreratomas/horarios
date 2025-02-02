<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\LogsRequest;
use App\Services\CarreraGradoService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\LogModificacionEliminacionController;


class CarreraGradoController extends Controller
{
    protected $carreraGradoService;
    protected $logModificacionEliminacionController;

    public function __construct(CarreraGradoService $carreraGradoService, LogModificacionEliminacionController $logModificacionEliminacionController)
    {
        $this->carreraGradoService = $carreraGradoService;
        $this->logModificacionEliminacionController = $logModificacionEliminacionController;
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

    //  devueve los registros de carrera grado que tiene las materias de un alumno
    public function showGradosByMaterias($id_alumno)
    {
        log::info("en controlador {$id_alumno}");

        return $this->carreraGradoService->obtenerCarreraGradoPorMaterias($id_alumno);
    }




    public function showByCarreraGrado($id_carreraGrado)
    {
        return $this->carreraGradoService->obtenerCarreraGrado($id_carreraGrado);
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
    public function showByCarreraSinUC($id_carrera)
    {
        return $this->carreraGradoService->obtenerCarreraGradoPorIdCarreraSinUC($id_carrera);
    }
    // trae el grado con su carrera y materias asignadas
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
    public function store($id_carrera, $id_grado, $capacidad)
    {
        return $this->carreraGradoService->guardarCarreraGrado($id_carrera, $id_grado, $capacidad);
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
    public function destroy($id_carrera_grado, LogsRequest $request)
    {
        $detalle = $request->input('detalles');
        $usuario = $request->input('usuario');
        DB::beginTransaction();
        try {
            $carreraGradoResponse = $this->carreraGradoService->eliminarCarreraGradoPorIdGradoYCarrera($id_carrera_grado);
            if ($carreraGradoResponse->getStatusCode() !== 200) {
                DB::rollBack();

                return $carreraGradoResponse;
            }
            $carreraGrado = $carreraGradoResponse->getData();
            $grado = $carreraGrado->grado->grado;
            $division = $carreraGrado->grado->division;
            $carrera = $carreraGrado->carrera->carrera;
            Log::info('Datos del grado eliminado:', (array) $carreraGrado->grado);
            Log::info('Datos del grado carrera eliminado:', (array) $carreraGrado->carrera);

            $accion = "Eliminación del grado: " . $grado . " división: " . $division . " carrera: " . $carrera;

            $this->logModificacionEliminacionController->store($accion, $usuario, $detalle);

            DB::commit();
            return response()->json([
                'message' => 'Comisión eliminada correctamente.'
            ], 200);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'error' => 'Hubo un problema al eliminar la comisión: ' . $e->getMessage()
            ], 500);
        }
    }
}
