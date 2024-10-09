<?php

namespace App\Http\Controllers\horarios;

use App\Http\Controllers\Controller;
use App\Services\horarios\GradoUcService;
use Illuminate\Http\Request;

class GradoUcController extends Controller
{
    protected $gradoUcService;

    public function __construct(GradoUcService $gradoUcService)
    {
        $this->gradoUcService = $gradoUcService;
    }

    /**
     * @OA\Get(
     *     path="/api/grado_uc",
     *     summary="Obtener todos los registros de GradoUc",
     *     tags={"GradoUc"},
     *     @OA\Response(response=200, description="Éxito")
     * )
     */
    public function index()
    {
        $gradoUc = $this->gradoUcService->obtenerTodosGradoUc();
        return response()->json($gradoUc, 200);
    }

    /**
     * @OA\Get(
     *     path="/api/grado_uc/{id_grado}/{id_uc}",
     *     summary="Obtener un registro de GradoUc por ID",
     *     tags={"GradoUc"},
     *     @OA\Parameter(name="id_grado", in="path", required=true),
     *     @OA\Parameter(name="id_uc", in="path", required=true),
     *     @OA\Response(response=200, description="Éxito")
     * )
     */
    public function show($id_grado, $id_uc)
    {
        $gradoUc = $this->gradoUcService->obtenerGradoUcPorId($id_grado, $id_uc);
        if ($gradoUc) {
            return response()->json($gradoUc, 200);
        } else {
            return response()->json(['message' => 'Registro no encontrado'], 404);
        }
    }

    /**
     * @OA\Post(
     *     path="/api/grado_uc",
     *     summary="Crear un nuevo registro de GradoUc",
     *     tags={"GradoUc"},
     *     @OA\RequestBody(
     *         description="Datos del nuevo GradoUc",
     *         required=true,
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="id_grado", type="integer"),
     *             @OA\Property(property="id_uc", type="integer")
     *         )
     *     ),
     *     @OA\Response(response=201, description="Creado con éxito")
     * )
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'id_grado' => 'required|integer',
            'id_uc' => 'required|integer'
        ]);

        $gradoUcData = $request->only(['id_grado', 'id_uc']);
        $gradoUc = $this->gradoUcService->guardarGradoUc($gradoUcData);

        return response()->json($gradoUc, 201);
    }

    /**
     * @OA\Delete(
     *     path="/api/grado_uc/{id_grado}/{id_uc}",
     *     summary="Eliminar un registro de GradoUc",
     *     tags={"GradoUc"},
     *     @OA\Parameter(name="id_grado", in="path", required=true),
     *     @OA\Parameter(name="id_uc", in="path", required=true),
     *     @OA\Response(response=200, description="Eliminado con éxito")
     * )
     */
    public function destroy($id_grado, $id_uc)
    {
        $eliminado = $this->gradoUcService->eliminarGradoUc($id_grado, $id_uc);
        if ($eliminado) {
            return response()->json(['message' => 'Registro eliminado con éxito'], 200);
        } else {
            return response()->json(['message' => 'Registro no encontrado'], 404);
        }
    }
}
