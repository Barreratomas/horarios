<?php

namespace App\Http\Controllers;

use App\Models\AlumnoGrado;
use App\Http\Controllers\Controller;
use App\Http\Controllers\LogModificacionEliminacionController;
use App\Http\Requests\LogsRequest;
use App\Services\AlumnoGradoService;
use App\Services\CarreraGradoService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class AlumnoGradoController extends Controller
{
    private $alumnoGradoService;
    private $carreraGradoService;
    protected $logModificacionEliminacionController;

    public function __construct(AlumnoGradoService $alumnoGradoService, CarreraGradoService $carreraGradoService, LogModificacionEliminacionController $logModificacionEliminacionController)
    {
        $this->alumnoGradoService = $alumnoGradoService;

        $this->carreraGradoService = $carreraGradoService;

        $this->logModificacionEliminacionController = $logModificacionEliminacionController;
    }

    //-------------------------------------------------------------------------------------------------------------
    // Swagger Documentation

    /**
     * @OA\Get(
     *      path="/api/horarios/alumnoGrados",
     *      summary="Obtener todas las relaciones Alumno-Grado",
     *      description="Devuelve todas las relaciones Alumno-Grado",
     *      operationId="getAlumnoGrados",
     *      tags={"AlumnoGrado"},
     *      @OA\Response(
     *          response=200,
     *          description="Relaciones obtenidas correctamente",
     *          @OA\JsonContent(
     *              type="array",
     *              @OA\Items(ref="#/components/schemas/AlumnoGrado")
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
        return $this->alumnoGradoService->obtenerTodosAlumnoGrado();
    }


    public function indexConRelaciones()
    {
        return $this->alumnoGradoService->obtenerTodosAlumnoGradoConRelaciones();
    }
    /**
     * @OA\Post(
     *      path="/api/horarios/alumnoGrados/guardar/{id_alumno}/{id_grado}",
     *      summary="Guardar una nueva relación Alumno-Grado",
     *      description="Crea una nueva relación Alumno-Grado",
     *      operationId="guardarAlumnoGrado",
     *      tags={"AlumnoGrado"},
     *      @OA\Parameter(
     *          name="id_alumno",
     *          description="ID del alumno",
     *          required=true,
     *          in="path",
     *          @OA\Schema(type="integer")
     *      ),
     *      @OA\Parameter(
     *          name="id_grado",
     *          description="ID del grado",
     *          required=true,
     *          in="path",
     *          @OA\Schema(type="integer")
     *      ),
     *      @OA\Response(
     *          response=201,
     *          description="Relación creada correctamente"
     *      ),
     *      @OA\Response(
     *          response=500,
     *          description="Error al crear la relación"
     *      )
     * )
     */
    public function store($id_alumno, $id_grado)
    {

        return $this->alumnoGradoService->guardarAlumnoGrado($id_alumno, $id_grado);
    }


    /**
     * @OA\Get(
     *      path="/api/horarios/alumnoGrados/alumno/{id_alumno}",
     *      summary="Obtener relación Alumno-Grado por ID de alumno",
     *      description="Devuelve la relación Alumno-Grado por ID de alumno",
     *      operationId="getAlumnoGradoPorIdAlumno",
     *      tags={"AlumnoGrado"},
     *      @OA\Parameter(
     *          name="id_alumno",
     *          in="path",
     *          description="ID del alumno",
     *          required=true,
     *          @OA\Schema(type="integer")
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Relación obtenida correctamente",
     *          @OA\JsonContent(ref="#/components/schemas/AlumnoGrado")
     *      ),
     *      @OA\Response(
     *          response=404,
     *          description="AlumnoGrado no encontrado"
     *      )
     * )
     */


    public function showByAlumno($id_alumno)
    {
        return $this->alumnoGradoService->obtenerAlumnoGradoPorIdAlumno($id_alumno);
    }

    public function showByAlumnoConRelaciones($id_alumno)
    {
        return $this->alumnoGradoService->obtenerAlumnoGradoPorIdAlumnoConRelaciones($id_alumno);
    }

    /**
     * @OA\Get(
     *      path="/api/horarios/alumnoGrados/grado/{id_grado}",
     *      summary="Obtener relación Alumno-Grado por ID de grado",
     *      description="Devuelve la relación Alumno-Grado por ID de grado",
     *      operationId="getAlumnoGradoPorIdGrado",
     *      tags={"AlumnoGrado"},
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
     *          @OA\JsonContent(ref="#/components/schemas/AlumnoGrado")
     *      ),
     *      @OA\Response(
     *          response=404,
     *          description="AlumnoGrado no encontrado"
     *      )
     * )
     */
    public function showByGrado($id_grado)
    {
        return $this->alumnoGradoService->obtenerAlumnoGradoPorIdGrado($id_grado);
    }

    /**
     * @OA\Delete(
     *      path="/api/horarios/alumnoGrados/eliminar/{id_alumno}",
     *      summary="Eliminar una relación Alumno-Grado",
     *      description="Elimina una relación Alumno-Grado por id_alumno",
     *      operationId="eliminarAlumnoGrado",
     *      tags={"AlumnoGrado"},
     *      @OA\Parameter(
     *          name="id_alumno",
     *          in="path",
     *          description="Id del alumno",
     *          required=true,
     *          @OA\Schema(type="integer")
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Relación eliminada correctamente"
     *      ),
     *      @OA\Response(
     *          response=404,
     *          description="AlumnoGrado no encontrado"
     *      ),
     *      @OA\Response(
     *          response=500,
     *          description="Error al eliminar la relación"
     *      )
     * )
     */
    public function destroy($id_alumno, $id_grado,  LogsRequest $request)
    {

        $detalle = $request->input('detalles');
        $usuario = $request->input('usuario');

        DB::beginTransaction();

        try {
            $alumnoGradoResponse = $this->alumnoGradoService->eliminarAlumnoGrado($id_alumno, $id_grado);

            $alumnoGrado = $alumnoGradoResponse->getData();
            Log::info(json_encode($alumnoGrado));
            $dniAlumno = $alumnoGrado->dni_alumno;
            $nombreGrado = $alumnoGrado->detalle_grado;

            if (!isset($dniAlumno) || !isset($nombreGrado)) {
                throw new \Exception('No se pudo obtener el nombre del alumno del grado.');
            }

            $accion = "Eliminación del alumno de dni " . $dniAlumno . " del grado " . $nombreGrado . "(id: " . $id_grado . ")";

            $this->logModificacionEliminacionController->store($accion, $usuario, $detalle);

            DB::commit();

            return response()->json([
                'message' => 'Alumno eliminado correctamente del grado.'
            ], 200);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'error' => 'Hubo un problema al eliminar al alumno del grado: ' . $e->getMessage()
            ], 500);
        }
    }




    public function asignarAlumnosACarrerasIngresante()
    {
        return $this->alumnoGradoService->asignarAlumnosACarrerasIngresante();
    }

    public function asignarAlumnosACarreras()
    {
        return $this->alumnoGradoService->asignarAlumnosACarreras();
    }

    public function cambiarGrado($id_alumno, $id_grado_actual, $id_grado, LogsRequest $request)
    {
        $detalle = $request->input('detalles');
        $usuario = $request->input('usuario');

        // Iniciar transacción
        DB::beginTransaction();

        try {


            $alumnoGradoResponse = $this->alumnoGradoService->actualizarAlumnoGrado($id_alumno, $id_grado_actual, $id_grado);

            // Verificación de respuesta
            if (!$alumnoGradoResponse || !$alumnoGradoResponse->getData()) {
                Log::error("Error: La respuesta de la actualización del grado es nula o vacía para el alumno con ID $id_alumno.");
                throw new \Exception('Error al obtener los datos del alumno y los grados.');
            }
            if ($alumnoGradoResponse->status() !== 200) {
                return $alumnoGradoResponse;
            }

            $alumnoGrado = $alumnoGradoResponse->getData();
            $dniAlumno = $alumnoGrado->dni_alumno;
            $gradoActual = $alumnoGrado->detalle_grado_actual;
            $gradoNuevo = $alumnoGrado->detalle_grado_nuevo;

            // Verificar si los valores obtenidos son válidos
            if (!isset($dniAlumno) || !isset($gradoActual) || !isset($gradoNuevo)) {
                Log::error("Error: No se pudieron obtener los detalles necesarios. DNI: $dniAlumno, Grado Actual: $gradoActual, Grado Nuevo: $gradoNuevo.");
                throw new \Exception('No se pudo obtener el nombre del alumno del grado.');
            }



            $accion = "Actualización del alumno de dni " . $dniAlumno . " de " . $gradoActual . "(id: " . $id_grado_actual . ")" . " hacia " . $gradoNuevo . "(id:" . $id_grado . ")";

            // Guardar log de modificación
            $this->logModificacionEliminacionController->store($accion, $usuario, $detalle);

            // Commit de la transacción
            DB::commit();


            return response()->json([
                'message' => 'Grado del alumno actualizado correctamente.'
            ], 200);
        } catch (\Exception $e) {
            // Rollback de la transacción
            DB::rollBack();

            // Log del error
            Log::error("Error al actualizar el grado del alumno. Excepción: " . $e->getMessage());

            return response()->json([
                'error' => 'Hubo un problema al eliminar al alumno del grado: ' . $e->getMessage()
            ], 500);
        }
    }
    public function cambiarGradoRecursante(Request $request)
    {
        return $this->alumnoGradoService->cambiarGradoRecursante($request->id_alumno, $request->id_grado);
    }
}
