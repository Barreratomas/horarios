<?php

namespace App\Http\Controllers\horarios;

use App\Http\Requests\horarios\GradoRequest;
use App\Models\horarios\Grado;
use App\Services\horarios\GradoService;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\LogModificacionEliminacionController;
use App\Http\Requests\LogsRequest;
use App\Services\CarreraGradoService;
use App\Services\horarios\GradoUcService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class GradoController extends Controller
{
    protected $gradoService;
    protected $carreraGradoService;
    protected $gradoUcService;
    protected $logModificacionEliminacionController;



    public function __construct(GradoService $gradoService, CarreraGradoService $carreraGradoService, GradoUcService $gradoUcService, LogModificacionEliminacionController $logModificacionEliminacionController)
    {
        $this->gradoService = $gradoService;
        $this->carreraGradoService = $carreraGradoService;
        $this->gradoUcService = $gradoUcService;
        $this->logModificacionEliminacionController = $logModificacionEliminacionController;
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
        DB::beginTransaction();

        try {
            // Log inicial
            Log::info('Iniciando la creación de un nuevo grado', [
                'datos_recibidos' => $request->all()
            ]);

            // Guardar el grado (solo los campos necesarios para grado)
            Log::info('Guardando el grado', ['campos' => $request->only(['grado', 'division', 'detalle'])]);
            $gradoResponse = $this->gradoService->guardarGrados($request->only(['grado', 'division', 'detalle']));
            $grado = $gradoResponse->getData(); // Extrae el contenido del JSON

            Log::info('Grado guardado correctamente', ['grado' => $grado]);

            // Obtener el ID de la carrera
            $id_carrera = $request->input('id_carrera');
            Log::info('ID de la carrera obtenido', ['id_carrera' => $id_carrera]);

            // Guardar la relación entre carrera y grado
            Log::info('Guardando la relación Carrera-Grado', [
                'id_carrera' => $id_carrera,
                'id_grado' => $grado->id_grado,
                'capacidad' => $request->input('capacidad')
            ]);

            $carreraGradoResponse = $this->carreraGradoService->guardarCarreraGrado($id_carrera, $grado->id_grado, $request->input('capacidad'));

            $carreraGrado = $carreraGradoResponse->getData();

            Log::info('Relación Carrera-Grado guardada correctamente', ['carreraGrado' => $carreraGrado]);

            // Guardar las materias relacionadas con el grado
            $materias = $request->input('materias');
            if ($materias) {
                Log::info('Guardando materias relacionadas con el grado', [
                    'id_carrera_grado' => $carreraGrado->id_carrera_grado,
                    'materias' => $materias
                ]);
                $this->gradoUcService->guardarGradoUC($carreraGrado->id_carrera_grado, $materias);
                Log::info('Materias guardadas correctamente');
            }

            DB::commit();

            // Log de éxito
            Log::info('Grado creado y asignado a carrera exitosamente', [
                'grado' => $grado,
                'carreraGrado' => $carreraGrado
            ]);

            // Responder con el mensaje de éxito
            return response()->json([
                'message' => 'Grado creado y asignado a carrera exitosamente',
                'data' => $grado
            ], 201);
        } catch (\Exception $e) {
            // Si ocurre un error, hacer rollback de la transacción
            DB::rollBack();

            // Registrar el error
            Log::error('Error al guardar el grado y las relaciones', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            // Responder con un error
            return response()->json(['error' => 'Hubo un error al guardar el grado y sus relaciones'], 500);
        }
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
        $detalle = $request->input('detalles');
        $usuario = $request->input('usuario');

        // Iniciar la transacción para asegurar la atomicidad
        DB::beginTransaction();

        try {
            // Primero, actualizamos el grado con la información de la solicitud
            $CarreraGradoResponse = $this->carreraGradoService->update($id, $request->input('capacidad'));


            if ($CarreraGradoResponse->getStatusCode() != 200) {
                DB::rollBack();
                return response()->json(['error' => 'Hubo un error al actualizar el grado'], 500);
            }

            // Obtenemos el objeto de grado actualizado
            $carreraGrado = json_decode(json_encode($CarreraGradoResponse->getData()), true);
            Log::info("carreraGrado");

            Log::info(json_encode($carreraGrado));
            // Validamos si hay materias para actualizar
            $materias = $request->input('materias');
            if ($materias) {
                // Actualizamos las materias asociadas al grado
                $this->gradoUcService->actualizarGradoUC($carreraGrado['data']['id_carrera_grado'], $materias);
            }
            $nombreGrado = $carreraGrado['data']['grado']['detalle'];
            $accion = "Actualización del grado " . $nombreGrado . "(id:" . $carreraGrado['data']['id_carrera_grado'] . ")";
            $this->logModificacionEliminacionController->store($accion, $usuario, $detalle);


            DB::commit();

            return response()->json(['message' => 'Grado y materias actualizados exitosamente'], 200);
        } catch (\Exception $e) {
            DB::rollBack();

            Log::error("Error al actualizar el grado y sus materias: " . $e->getMessage());

            return response()->json(['error' => 'Hubo un error al actualizar el grado'], 500);
        }
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
    public function destroy($id, LogsRequest $request)
    {
        $detalle = $request->input('detalles');
        $usuario = $request->input('usuario');

        DB::beginTransaction();

        try {
            $gradoResponse = $this->gradoService->eliminarGrados($id);

            $grado = $gradoResponse->getData();
            if (!isset($grado->nombre_grado)) {
                throw new \Exception('No se pudo obtener el nombre del grado.');
            }

            $nombreGrado = $grado->nombre_grado;
            $accion = "Eliminación del grado " . $nombreGrado . "(id:" . $id . ")";

            $this->logModificacionEliminacionController->store($accion, $usuario, $detalle);

            DB::commit();

            return response()->json([
                'message' => 'Grado eliminado correctamente.'
            ], 200);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'error' => 'Hubo un problema al eliminar el grado: ' . $e->getMessage()
            ], 500);
        }
    }
}
