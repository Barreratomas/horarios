<?php

namespace App\Http\Controllers\horarios;

use App\Models\horarios\Aula;
use App\Models\horarios\Disponibilidad;
use App\Models\horarios\DocenteUC;
use App\Models\Horario;
use App\Models\horarios\Grado;
use App\Models\horarios\HorarioPrevioDocente;
use App\Models\horarios\Materia;
use App\Models\horarios\UnidadCurricular;
use App\Services\horarios\DisponibilidadService;
use App\Http\Requests\horarios\DisponibilidadRequest;
use DateInterval;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use App\Http\Controllers\Controller;


class DisponibilidadController extends Controller
{
    protected $disponibilidadService;

    public function __construct(DisponibilidadService $disponibilidadService)
    {
        $this->disponibilidadService = $disponibilidadService;
    }

    /*
    public function index()
    {
        $disponibilidades = $this->disponibilidadService->obtenerTodasDisponibilidades();
        return view('disponibilidad.index', compact('disponibilidades'));
    }

    public function mostrarDisponibilidad(Request $request)
    {
        $id = $request->input('id');
        $disponibilidad = $this->disponibilidadService->obtenerDisponibilidadPorId($id);
        
        return view('disponibilidad.show', compact('disponibilidad'));
    }

   */


   
    public function guardar()
    {   


        // Obtener los modulos_semanales directamente desde la tabla Materias usando el id_dm
        $DocenteUC = DocenteUC::orderBy('id_dm', 'desc')->first();
        $id_dm=$DocenteUC->id_dm;
        $modulos_semanales = UnidadCurricular::where('id_materia', $DocenteUC->id_materia)->value('modulos_semanales');

        $id_aula = Aula::where("id_aula",$DocenteUC->id_aula)->value('id_aula');
        $id_grado = Grado::where("id_grado",$DocenteUC->id_grado)->value('id_grado');

            

        // Obtener el id_h_p_d más reciente
        $h_p_d = HorarioPrevioDocente::orderBy('id_h_p_d', 'desc')->first();
        $id_h_p_d = $h_p_d->id_h_p_d;
        $diaInstituto = $h_p_d->dia;
        
        $moduloPrevio=$this->disponibilidadService->horaPrevia($id_h_p_d);

        
        $distribucion=$this->disponibilidadService->modulosRepartidos($modulos_semanales,$moduloPrevio,$id_dm,$id_grado,$id_aula,$diaInstituto);
        if (empty($distribucion)) {
            $DocenteUC->delete();
            $h_p_d->delete();
            return redirect()->route('indexAsignacion');
        }
            
        foreach ($distribucion as $data) {
            $dia=$data['dia'];
            $modulo_inicio=$data['modulo_inicio'];
            $modulo_fin=$data['modulo_fin'];
            
            $params=[
                'id_dm'=>$id_dm,
                'id_h_p_d'=>$id_h_p_d,
                'dia'=>$dia,
                'modulo_inicio'=>$modulo_inicio,
                'modulo_fin'=>$modulo_fin,
    
            ];

            // dd($params);        
            $response = $this->disponibilidadService->guardarDisponibilidad($params);
            

            
        }
        if($response && isset($response['success'])) {

            return redirect()->route('storeHorario')->with('success', $response['success']);
        }else{
            $DocenteUC->delete();
            $h_p_d->delete();
            return redirect()->route('indexAsignacion')->withErrors(['error' => $response['error']]);

        }
               
    }


    
    public function redireccionarError(){
        return view("disponibilidad.error");
    }


    
    public function actualizar( HorarioPrevioDocente $h_p_d,DocenteUC $dm)
    {   
        $id_dm = $dm->id_dm;
        // Buscar registros en la tabla disponibilidades que tengan el mismo id_dm
        $disponibilidad_vieja = Disponibilidad::where('id_dm', $id_dm)->get();
        // Verificar si se encontraron registros
        if ($disponibilidad_vieja->isNotEmpty()) {
            foreach ($disponibilidad_vieja as $registro) {
                $registro->delete();
            }
        }

        $modulos_semanales = UnidadCurricular::where('id_materia', $dm->id_materia)->value('modulos_semanales');
        $id_aula = Aula::where("id_aula",$dm->id_aula)->value('id_aula');
        $id_grado = Grado::where("id_grado",$dm->id_grado)->value('id_grado');
        $id_h_p_d = $h_p_d->id_h_p_d;
        $diaInstituto = $h_p_d->dia;
        $moduloPrevio=$this->disponibilidadService->horaPrevia($id_h_p_d);
        

        $distribucion=$this->disponibilidadService->modulosRepartidos($modulos_semanales,$moduloPrevio,$id_dm,$id_grado,$id_aula,$diaInstituto);
        if (empty($distribucion)) {
            $dm->delete();
            $h_p_d->delete();
            return redirect()->route('indexAsignacion');
        }
            
        foreach ($distribucion as $data) {
            $dia=$data['dia'];
            $modulo_inicio=$data['modulo_inicio'];
            $modulo_fin=$data['modulo_fin'];
            
            $params=[
                'id_dm'=>$id_dm,
                'id_h_p_d'=>$id_h_p_d,
                'dia'=>$dia,
                'modulo_inicio'=>$modulo_inicio,
                'modulo_fin'=>$modulo_fin,
    
            ];

            // dd($params);        
            $response = $this->disponibilidadService->actualizarDisponibilidad($params);
            

            
        }
        if($response && isset($response['success'])) {

            return redirect()->route('storeHorario')->with('success', $response['success']);
        }else{
            $dm->delete();
            $h_p_d->delete();
            return redirect()->route('indexAsignacion')->withErrors(['error' => $response['error']]);

        }
        

/*
        $response = $this->disponibilidadService->actualizarDisponibilidad($params);
        if (isset($response['success'])) {
            return redirect()->route('disponibilidades.index')->with('success', $response['success']);
        }else{
            return redirect()->route('disponibilidades.index')->withErrors(['error' => $response['error']]);
        }
            */
    
    }

    /*

    public function eliminar(Request $request)
    {
        $id = $request->input('id');
        $response = $this->disponibilidadService->eliminarDisponibilidadPorId($id);
        if (isset($response['success'])) {
            return redirect()->route('disponibilidades.index')->with('success', $response['success']);
        }else{
            return redirect()->route('disponibilidades.index')->withErrors(['error' => $response['error']]);

        }
    }
        */

    //-------------------------------------------------------------------------------------------------------------------------
    // swagger

    /**
     * @OA\Get(
     *     path="/api/horarios/disponibilidad",
     *     tags={"Disponibilidad"},
     *     summary="Obtener todas las disponibilidades",
     *     @OA\Response(
     *         response=200,
     *         description="Devuelve todas las disponibilidades"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="No se encontraron disponibilidades"
     *     )
     * )
     */
    public function index()
    {
        return $this->disponibilidadService->obtenerTodasDisponibilidades();
    }

    /**
     * @OA\Get(
     *     path="/api/horarios/disponibilidad/{id}",
     *     tags={"Disponibilidad"},
     *     summary="Obtener disponibilidad por id",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID de la disponibilidad",
     *         required=true,
     *         @OA\Schema(
     *             type="integer"
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Devuelve la disponibilidad"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="No se encontró la disponibilidad"
     *     )
     * )
     */
    public function show($id)
    {
        return $this->disponibilidadService->obtenerDisponibilidadPorId($id);
    }

    /**
     * @OA\Post(
     *     path="/api/horarios/disponibilidad/store",
     *     tags={"Disponibilidad"},
     *     summary="Guardar disponibilidad",
     *     @OA\RequestBody(
     *         @OA\JsonContent(ref="#/components/schemas/DisponibilidadDTO")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Disponibilidad guardada correctamente"
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Error al guardar la disponibilidad"
     *     )
     * )
     */
    public function store(DisponibilidadRequest $request)
    {
        return $this->disponibilidadService->guardarDisponibilidadSwagger($request);
    }

    /**
     * @OA\Put(
     *     path="/api/horarios/disponibilidad/update/{id}",
     *     tags={"Disponibilidad"},
     *     summary="Actualizar disponibilidad",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID de la disponibilidad",
     *         required=true,
     *         @OA\Schema(
     *             type="integer"
     *         )
     *     ),
     *     @OA\RequestBody(
     *         @OA\JsonContent(ref="#/components/schemas/DisponibilidadDTO")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Disponibilidad actualizada correctamente"
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Error al actualizar la disponibilidad"
     *     )
     * )
     */
    public function update(DisponibilidadRequest $request, $id)
    {
        return $this->disponibilidadService->actualizarDisponibilidadSwagger($request, $id);
    }

    /**
     * @OA\Delete(
     *     path="/api/horarios/disponibilidad/eliminar/{id}",
     *     tags={"Disponibilidad"},
     *     summary="Eliminar disponibilidad por id",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID de la disponibilidad",
     *         required=true,
     *         @OA\Schema(
     *             type="integer"
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Disponibilidad eliminada correctamente"
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Error al eliminar la disponibilidad"
     *     )
     * )
     */
    public function destroy($id)
    {
        return $this->disponibilidadService->eliminarDisponibilidadPorId($id);
    }
}
