<?php

namespace App\Http\Controllers\horarios;

use App\Http\Requests\horarios\CambioDocenteRequest;
use App\Services\horarios\CambioDocenteService;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;


class CambioDocenteController extends Controller
{
    protected $cambioDocenteService;

    public function __construct(CambioDocenteService $cambioDocenteService)
    {
        $this->cambioDocenteService = $cambioDocenteService;
    }

    /*

    public function index()
    {
        $cambiosDocente = $this->cambioDocenteService->obtenerTodosCambiosDocente();
        return view('cambioDocente.index', compact('cambiosDocente'));
    }

    public function mostrarCambioDocente(Request $request)
    {
        $id = $request->input('id');
        $cambioDocente = $this->cambioDocenteService->obtenerCambioDocentePorId($id);
        
        return view('cambioDocente.show', compact('cambioDocente'));
    }

    public function store(Request $request)
    {
        $docente_anterior=$request->input('docente_anterior');
        $docente_nuevo=$request->input('docente_nuevo');

        $response = $this->cambioDocenteService->guardarCambioDocente($docente_anterior,$docente_nuevo,);
        if (isset($response['success'])) {
            return redirect()->route('cambioDocente.index')->with('success',  $response['success']);
        }else{
            return redirect()->route('cambioDocente.index')->withErrors('error',  $response['error']);

        }
    }

    public function actualizar(Request $request)
    {        
        $id=$request->input('id');
        $docente_anterior=$request->input('docente_anterior');
        $docente_nuevo=$request->input('docente_nuevo');

        $response = $this->cambioDocenteService->actualizarCambioDocente($id,$docente_anterior,$docente_nuevo);
        if (isset($response['success'])) {
            return redirect()->route('cambioDocente.index')->with('success',  $response['success']);
        }else{
            return redirect()->route('cambioDocente.index')->withErrors('error',  $response['error']);

        }
    }

    public function eliminar(Request $request)
    {        
        $id=$request->input('id');
        $response = $this->cambioDocenteService->eliminarCambioDocentePorId($id);
        if (isset($response['success'])) {
            return redirect()->route('cambioDocente.index')->with('success',  $response['success']);
        }else{
            return redirect()->route('cambioDocente.index')->withErrors('error',  $response['error']);

        }
    }
        */
    
    //---------------------------------------------------------------------------------------------------------
    // Swagger

    /**
     * @OA\Get(
     *     path="/api/horarios/cambioDocente",
     *     tags={"CambioDocente"},
     *     summary="Obtener todos los cambios de docente",
     *     description="Retorna un array de cambios de docente",
     *     @OA\Response(
     *         response=200,
     *         description="Operación exitosa"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="No se encontraron cambios de docente"
     *     )
     * )
     */
    public function index()
    {
        return $this->cambioDocenteService->obtenerTodosCambiosDocente();
    }

    /**
     * @OA\Get(
     *     path="/api/horarios/cambioDocente/{id}",
     *     tags={"CambioDocente"},
     *     summary="Obtener cambio de docente por id",
     *     description="Retorna un cambio de docente",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Id del cambio de docente",
     *         required=true,
     *         @OA\Schema(
     *             type="integer"
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Operación exitosa",
     *        @OA\JsonContent(ref="#/components/schemas/CambioDocente")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="No se encontró el cambio de docente"
     *     ),
     *    @OA\Response(
     *        response=500,
     *       description="Error al obtener el cambio de docente"
     *   )
     * )
     */
    public function show($id)
    {
        return $this->cambioDocenteService->obtenerCambioDocentePorId($id);
    }

    /**
     * @OA\Post(
     *     path="/api/horarios/cambioDocente/guardar",
     *     tags={"CambioDocente"},
     *     summary="Guardar cambio de docente",
     *     description="Guardar un cambio de docente",
     *     @OA\RequestBody(
     *         @OA\JsonContent(ref="#/components/schemas/CambioDocenteDTO")
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Cambio de docente guardado",
     *        @OA\JsonContent(ref="#/components/schemas/CambioDocente")
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Error al guardar el cambio de docente"
     *     )
     * )
     */
    public function store(CambioDocenteRequest $request)
    {
        return $this->cambioDocenteService->guardarCambioDocente($request);
    }

    /**
     * @OA\Put(
     *     path="/api/horarios/cambioDocente/actualizar/{id}",
     *     tags={"CambioDocente"},
     *     summary="Actualizar cambio de docente",
     *     description="Actualizar un cambio de docente",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Id del cambio de docente",
     *         required=true,
     *         @OA\Schema(
     *             type="integer"
     *         )
     *     ),
     *     @OA\RequestBody(
     *         @OA\JsonContent(ref="#/components/schemas/CambioDocenteDTO")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Cambio de docente actualizado"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="No se encontró el cambio de docente"
     *     )
     * )
     */
    public function update(CambioDocenteRequest $request, $id)
    {
        return $this->cambioDocenteService->actualizarCambioDocente($request, $id);
    }

    /**
     * @OA\Delete(
     *     path="/api/horarios/cambioDocente/eliminar/{id}",
     *     tags={"CambioDocente"},
     *     summary="Eliminar cambio de docente por id",
     *     description="Eliminar un cambio de docente",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Id del cambio de docente",
     *         required=true,
     *         @OA\Schema(
     *             type="integer"
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Cambio de docente eliminado"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="No se encontró el cambio de docente"
     *     )
     * )
     */
    public function destroy($id)
    {
        return $this->cambioDocenteService->eliminarCambioDocentePorId($id);
    }
    

}

