<?php

namespace App\Http\Controllers\horarios;

use App\Http\Controllers\Controller;
use App\Services\horarios\GradoUCService;
use Illuminate\Http\Request;

class GradoUCController extends Controller
{
    protected $gradoUCService;

    public function __construct(GradoUCService $gradoUCService)
    {
        $this->gradoUCService = $gradoUCService;
    }

    /**
     * @OA\Get(
     *     path="/api/grado_uc",
     *     summary="Obtener todos los registros de GradoUC",
     *     tags={"GradoUC"},
     *     @OA\Response(response=200, description="Éxito")6
     * )
     */
    public function index()
    {
        $gradoUC = $this->gradoUCService->obtenerTodosGradoUC();
        return response()->json($gradoUC, 200);
    }

    /**
     * @OA\Get(
     *     path="/api/grado_uc/{id_grado}/{id_UC}",
     *     summary="Obtener un registro de GradoUC por ID",
     *     tags={"GradoUC"},
     *     @OA\Parameter(name="id_grado", in="path", required=true),
     *     @OA\Parameter(name="id_UC", in="path", required=true),
     *     @OA\Response(response=200, description="Éxito")
     * )
     */
    public function show($id_grado, $id_UC)
    {
        $gradoUC = $this->gradoUCService->obtenerGradoUCPorId($id_grado, $id_UC);
        if ($gradoUC) {
            return response()->json($gradoUC, 200);
        } else {
            return response()->json(['message' => 'Registro no encontrado'], 404);
        }
    }

    /**
     * @OA\Post(
     *     path="/api/grado_uc",
     *     summary="Crear un nuevo registro de GradoUC",
     *     tags={"GradoUC"},
     *     @OA\RequestBody(
     *         description="Datos del nuevo GradoUC",
     *         required=true,
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="id_grado", type="integer"),
     *             @OA\Property(property="id_UC", type="integer")
     *         )
     *     ),
     *     @OA\Response(response=201, description="Creado con éxito")
     * )
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'id_grado' => 'required|integer',
            'id_UC' => 'required|integer'
        ]);

        $gradoUCData = $request->only(['id_grado', 'id_UC']);
        $gradoUC = $this->gradoUCService->guardarGradoUC($gradoUCData);

        return response()->json($gradoUC, 201);
    }

    /**
     * @OA\Delete(
     *     path="/api/grado_uc/{id_grado}/{id_UC}",
     *     summary="Eliminar un registro de GradoUC",
     *     tags={"GradoUC"},
     *     @OA\Parameter(name="id_grado", in="path", required=true),
     *     @OA\Parameter(name="id_UC", in="path", required=true),
     *     @OA\Response(response=200, description="Eliminado con éxito")
     * )
     */
    public function destroy($id_grado, $id_UC)
    {
        $eliminado = $this->gradoUCService->eliminarGradoUC($id_grado, $id_UC);
        if ($eliminado) {
            return response()->json(['message' => 'Registro eliminado con éxito'], 200);
        } else {
            return response()->json(['message' => 'Registro no encontrado'], 404);
        }
    }
}
