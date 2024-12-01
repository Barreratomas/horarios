<?php

namespace App\Http\Controllers\horarios;

use App\Http\Requests\horarios\GradoRequest;
use App\Models\horarios\Grado;
use App\Services\horarios\GradoService;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\LogModificacionEliminacionController;
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
            // Guardar el grado (solo los campos necesarios para grado)
            $gradoResponse = $this->gradoService->guardarGrados($request->only(['grado', 'division', 'detalle', 'capacidad']));
            $grado = $gradoResponse->getData();  // Extrae el contenido del JSON
        
            // Obtener el ID de la carrera
            $id_carrera = $request->input('id_carrera');
            
            // Guardar la relación entre carrera y grado
            $this->carreraGradoService->guardarCarreraGrado($id_carrera, $grado->id_grado);
            
            // Guardar las materias relacionadas con el grado
            $materias = $request->input('materias');
            if ($materias) {
                $this->gradoUcService->guardarGradoUC( $grado->id_grado, $materias);
            }
    
            // Si todo va bien, hacer commit
            DB::commit();
    
            // Responder con el mensaje de éxito
            return response()->json([
                'message' => 'Grado creado y asignado a carrera exitosamente', 
                'data' => $grado
            ], 201);
    
        } catch (\Exception $e) {
            // Si ocurre un error, hacer rollback de la transacción
            DB::rollBack();
            
            // Registrar el error
            Log::error('Error al guardar el grado y las relaciones: ' . $e->getMessage());
            
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
            $gradoResponse = $this->gradoService->actualizarGrados($request, $id);
    
            if ($gradoResponse->getStatusCode() != 200) {
                DB::rollBack();
                return response()->json(['error' => 'Hubo un error al actualizar el grado'], 500);
            }
    
            // Obtenemos el objeto de grado actualizado
            $grado = $gradoResponse->getData();
    
            // Validamos si hay materias para actualizar
            $materias = $request->input('materias');
            if ($materias) {
                // Actualizamos las materias asociadas al grado
                $this->gradoUcService->actualizarGradoUC($grado->id_grado, $materias);
            }

            $nombreGrado = $grado->nombre_grado;
            $accion = "Actualizacion del grado " . $nombreGrado;
            
            $this->logModificacionEliminacionController->store($accion,$usuario,$detalle);
    
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
        public function destroy($id, Request $request)
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
            $accion = "Eliminación del grado " . $nombreGrado;
            
            $this->logModificacionEliminacionController->store($accion,$usuario,$detalle);

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
