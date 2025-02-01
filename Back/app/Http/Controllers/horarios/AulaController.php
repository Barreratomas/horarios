<?php

namespace App\Http\Controllers\horarios;

use App\Http\Requests\horarios\AulaRequest;
use App\Models\horarios\Aula;
use App\Services\horarios\AulaService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\DTO\AulaDTO;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\LogModificacionEliminacionController;
use App\DTO;
use App\Http\Requests\LogsRequest;

class AulaController extends Controller
{
    protected $aulaService;
    protected $logModificacionEliminacionController;

    public function __construct(AulaService $aulaService,  LogModificacionEliminacionController $logModificacionEliminacionController)
    {
        $this->aulaService = $aulaService;
        $this->logModificacionEliminacionController = $logModificacionEliminacionController;
    }

    /*
     public function index(){
         $aulas = $this->aulaService->obtenerTodasAulas();
         return view('aula.index', compact('aulas'));
     }


    
    
     public function obtenerAula(Request $request){
         $id = $request->input('id');
         $aula = $this->aulaService->obtenerAula($id);
         return view('aula.show', compact('aula'));
     }

     


     public function crear(){
         return view('aula.crearAula');
     }
   
     public function guardarAula(AulaRequest $request){

         $nombre = $request->input('nombre');
         $tipo_aula = $request->input('tipo_aula');

         $response=$this->aulaService->guardarAula($nombre,$tipo_aula);
         if (isset($response['success'])) {
             return redirect()->route('indexAula')->with('success', $response['success']);
         } else {
             return redirect()->route('indexAula')->withErrors(['error' => $response['error']]);
         };
     }


     public function formularioActualizar(Aula $aula){
         return view('aula.actualizarAula', compact('aula'));
     }

     public function actualizarAula(AulaRequest $request, Aula $aula){
         
         $nombre = $request->input('nombre');
         $tipo_aula = $request->input('tipo_aula');
         $response=$this->aulaService->actualizarAula($nombre,$tipo_aula,$aula);
         if (isset($response['success'])) {
             return redirect()->route('indexAula')->with('success', $response['success']);
         } else {
             return redirect()->route('indexAula')->withErrors(['error' => $response['error']]);
         };    }


     public function eliminarAula(Aula $aula){
         $response=$this->aulaService->eliminarAula($aula);
         if (isset($response['success'])) {
             return redirect()->route('indexAula')->with('success', $response['success']);
         } else {
             return redirect()->route('indexAula')->withErrors(['error' => $response['error']]);
         };
     }
         
     
 */

    //-------------------------------------------------------------------------------------------------------------
    // Swagger Documentation

    /**
     * @OA\Get(
     *      path="/api/horarios/aulas",
     *     summary="Obtener todas las aulas",
     *     description="Devuelve todas las aulas",
     *     operationId="getAulas",
     *     tags={"Aula"},
     *     @OA\Response(
     *          response=200,
     *          description="Aulas",
     *          @OA\JsonContent(
     *              type="array",
     *              @OA\Items(ref="#/components/schemas/Aula")
     *          )
     *      ),
     *     @OA\Response(
     *          response=500,
     *          description="Error al obtener las aulas"
     *      )
     * )
     */
    public function index()
    {
        return $this->aulaService->obtenerAulas();
    }


    /**
     * @OA\Get(
     *     path="/api/horarios/aulas/{id}",
     *     summary="Obtener un aula por id",
     *     description="Obtener un aula por id",
     *     operationId="obtenerAulaPorId",
     *     tags={"Aula"},
     *     @OA\Parameter(
     *          name="id",
     *          in="path",
     *          description="Id del aula",
     *          required=true,
     *          @OA\Schema(
     *              type="integer"
     *          )
     *     ),
     *     @OA\Response(
     *     response=200,
     *     description="Aula obtenida correctamente",
     *     @OA\JsonContent(ref="#/components/schemas/Aula")
     *     ),
     *     @OA\Response(
     *     response=404,
     *     description="No se encontró el aula"
     *  ),
     *     @OA\Response(
     *     response=500,
     *     description="Error al obtener el aula"
     *   )
     * )
     */
    public function show($id)
    {
        return $this->aulaService->obtenerAulaPorId($id);
    }



    /**
     * @OA\Post(
     *     path="/api/horarios/aulas/guardar",
     *     summary="Guardar un aula",
     *     description="Guardar un aula",
     *     operationId="guardarAula",
     *     tags={"Aula"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/AulaDTO")
     *     ),
     *     @OA\Response(
     *     response=201,
     *     description="Aula guardada correctamente",
     *     @OA\JsonContent(ref="#/components/schemas/AulaDTO")
     *     ),
     *     @OA\Response(
     *     response=400,
     *     description="Error al guardar el aula"
     *     )
     * )
     */
    public function store(AulaRequest $request)
    {
        return $this->aulaService->guardarAulas($request);
    }




    /**
     * @OA\Put(
     *     path="/api/horarios/aulas/actualizar/{id}",
     *     summary="Actualizar un aula",
     *     description="Actualizar un aula",
     *     operationId="actualizarAula",
     *     tags={"Aula"},
     *    @OA\Parameter(
     *      name="id",
     *      in="path",
     *      description="Id del aula",
     *      required=true,
     *      @OA\Schema(
     *          type="integer"
     *      ) 
     *     ),
     *     @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(ref="#/components/schemas/AulaDTO")
     *     ),
     *     @OA\Response(
     *          response=200,
     *          description="Aula actualizada correctamente",
     *          @OA\JsonContent(ref="#/components/schemas/Aula")
     *     ),
     *     @OA\Response(
     *          response=500,
     *          description="Error al actualizar el aula"
     *     )
     * )
     */
    public function update(AulaRequest $request, $id)
    {
        $detalle = $request->input('detalles');
        $usuario = $request->input('usuario');

        DB::beginTransaction();

        try {

            $aulaResponse  = $this->aulaService->actualizarAulas($request, $id);

            if ($aulaResponse->getStatusCode() != 200) {
                DB::rollBack();
                return response()->json(['error' => 'Hubo un error al actualizar el aula'], 500);
            }

            $aula = $aulaResponse->getData();

            $nombreAula = $aula->nombre;
            $accion = "Actualizacion del aula " . $nombreAula . "(id:" . $aula->id_aula . ")";

            $this->logModificacionEliminacionController->store($accion, $usuario, $detalle);

            DB::commit();

            return response()->json(['message' => 'Aula actualizada exitosamente'], 200);
        } catch (\Exception $e) {
            DB::rollBack();

            Log::error("Error al actualizar el aula: " . $e->getMessage());

            return response()->json(['error' => 'Hubo un error al actualizar el aula'], 500);
        }
    }


    /**
     * @OA\Delete(
     *     path="/api/horarios/aulas/eliminar/{id}",
     *     summary="Eliminar un aula",
     *     description="Eliminar un aula",
     *     operationId="eliminarAula",
     *     tags={"Aula"},
     *     @OA\Parameter(
     *     name="id",
     *     in="path",
     *     description="Id del aula",
     *     required=true,
     *     @OA\Schema(
     *     type="integer"
     *    )
     *     ),
     *     @OA\Response(
     *     response=200,
     *     description="Aula eliminada correctamente"
     * ),
     *     @OA\Response(
     *     response=500,
     *     description="Error al eliminar el aula"
     * )
     * )
     */
    public function destroy($id, LogsRequest $request)
    {

        $detalle = $request->input('detalles');
        $usuario = $request->input('usuario');

        DB::beginTransaction();

        try {
            $aulaResponse = $this->aulaService->eliminarAulas($id);


            if ($aulaResponse->getStatusCode() !== 200) {
                DB::rollBack();
                return $aulaResponse;
            }

            $aula = $aulaResponse->getData();
            if (!isset($aula->nombre_aula)) {
                throw new \Exception('No se pudo obtener el nombre del aula.');
            }

            $nombreAula = $aula->nombre_aula;
            $accion = "Eliminación del aula " . $nombreAula . "(id:" . $id . ")";

            $this->logModificacionEliminacionController->store($accion, $usuario, $detalle);

            DB::commit();

            return response()->json([
                'message' => 'Aula eliminada correctamente.'
            ], 200);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'error' => 'Hubo un problema al eliminar el aula: ' . $e->getMessage()
            ], 500);
        }
    }
}
