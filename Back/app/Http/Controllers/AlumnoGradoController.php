<?php

namespace App\Http\Controllers;

use App\Models\AlumnoGrado;
use App\Http\Controllers\Controller;
use App\Services\AlumnoGradoService;
use Illuminate\Http\Request;

class AlumnoGradoController extends Controller
{
    private $alumnoGradoService;

    public function __construct(AlumnoGradoService $alumnoGradoService)
    {
        $this->alumnoGradoService = $alumnoGradoService;
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

    /**
     * @OA\Post(
     *      path="/api/horarios/alumnoGrados/guardar/{id_alumno}/{id_grado}",
     *      summary="Guardar una nueva relación Alumno-Grado",
     *      description="Crea una nueva relación Alumno-Grado",
     *      operationId="guardarAlumnoGrado",
     *      tags={"AlumnoGrado"},
     *      @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(
     *             required={"id_alumno", "id_grado"},
     *            @OA\Property(property="id_alumno", type="integer", format="int64", example=1),
     *           @OA\Property(property="id_grado", type="integer", format="int64", example=1)
     *       )
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
    public function destroy($id_alumno)
    {
        return $this->alumnoGradoService->eliminarAlumnoGradoPorIdAlumno($id_alumno);
    }



    /**
     * @OA\Post(
     *      path="/api/horarios/alumnoGrados/asignar",
     *      summary="Asignar alumnos a grados",
     *      description="Asigna alumnos a grados",
     *      operationId="asignarAlumnosGrados",
     *      tags={"AlumnoGrado"},
     *      @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(
     *              required={"alumnos", "grados"},
     *              @OA\Property(
     *                  property="alumnos",
     *                  type="array",
     *                  @OA\Items(type="integer")
     *              ),
     *              @OA\Property(
     *                  property="grados",
     *                  type="array",
     *                  @OA\Items(type="integer")
     *              )
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Alumnos asignados correctamente"
     *      ),
     *      @OA\Response(
     *          response=400,
     *          description="Error al asignar los alumnos"
     *      )
     * )
     */
    public function asignarAlumnosGrados(Request $request){
        $alumnos = $request->alumnos;
        $grados = $request->grados;
        return $this->alumnoGradoService->asignarAlumnosGrados($alumnos, $grados);
    }

}
