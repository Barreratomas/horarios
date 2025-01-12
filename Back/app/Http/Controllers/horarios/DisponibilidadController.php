<?php

namespace App\Http\Controllers\horarios;

use App\Models\horarios\Aula;
use App\Models\horarios\Disponibilidad;
use App\Models\horarios\DocenteUC;
use App\Models\Horario;
use App\Models\horarios\Grado;
use App\Models\horarios\GradoUC;
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
use App\Models\CarreraGrado;
use Illuminate\Support\Facades\Log;



class DisponibilidadController extends Controller
{
    protected $disponibilidadService;


    public function __construct(DisponibilidadService $disponibilidadService)
    {
        $this->disponibilidadService = $disponibilidadService;
    }


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




    public function guardarDisponibilidades()
    {
        DB::beginTransaction();

        try {

            $grados = Grado::all();
            $carrerasGrados = CarreraGrado::all();

            // recorrer solo los grado en bucle
            $asignados = 0; // Contador para asignaciones exitosas
            $noAsignados = 0; // Contador para asignaciones no realizadas   

            foreach ($carrerasGrados as $carreraGrado) {
                // obtener todas las materias de $gradp
                $materias = DB::table('carrera_grado')
                    ->join('grado_uc', 'carrera_grado.id_carrera_grado', '=', 'grado_uc.id_carrera_grado')
                    ->join('unidad_curricular', 'grado_uc.id_uc', '=', 'unidad_curricular.id_uc')
                    ->select(
                        'unidad_curricular.id_uc',
                        'unidad_curricular.formato',
                        'unidad_curricular.horas_sem'
                    )
                    ->where('carrera_grado.id_carrera_grado', '=', $carreraGrado->id_carrera_grado)
                    ->get();




                foreach ($materias as $materia) {
                    Log::info("- id de materia: {$materia->id_uc} (Formato: {$materia->formato}, Horas: {$materia->horas_sem})");
                    // obtener los docentes que tengan materia
                    $docentes = DB::table('unidad_curricular')
                        ->join('docente_uc', 'unidad_curricular.id_uc', '=', 'docente_uc.id_uc')
                        ->join('docente', 'docente_uc.id_docente', '=', 'docente.id_docente')
                        ->select(
                            'docente.id_docente',
                        )
                        ->where('unidad_curricular.id_uc', '=', $materia->id_uc)  // Filtrar por la materia actual
                        ->get();

                    Log::info("- docentes: {$docentes} ");

                    foreach ($docentes as $docente) {
                        // verificar si el docente tiene horarios previos
                        $horariosPrevios = DB::table('horario_previo_docente')
                            ->where('id_docente', $docente->id_docente)
                            ->get();  // Devuelve una colección con todos los campos
                        Log::info("- entro horarios  $horariosPrevios");

                        if (is_array($horariosPrevios) && !empty($horariosPrevios)) {
                            //se asigna el modulo de inicio dependiendo la hora previa del docente 
                            foreach ($horariosPrevios as $previo) {

                                $horaPrevia = $this->disponibilidadService->horaPrevia($previo->hora);
                                Log::info("- hora previa: {$horaPrevia} ");

                                // llamar a modulosRepartidos
                                $response = $this->disponibilidadService->modulosRepartidos($materia->horas_sem, $docente->id_docente, $carreraGrado->id_carrera_grado, $materia->id_uc, $previo->id_h_p_d, $horaPrevia, $previo->dia);
                            }
                        } else {


                            // llamar a modulosRepartidos   
                            $response = $this->disponibilidadService->modulosRepartidos($materia->horas_sem, $docente->id_docente, $carreraGrado->id_carrera_grado, $materia->id_uc);
                        }

                        if ($response) {
                            $exitoEnAsignacion = false;

                            foreach ($response as $respuesta) {
                                // Evaluar si la respuesta tiene 'status' y es 'success'
                                if (isset($respuesta->original['status']) && $respuesta->original['status'] === 'success') {
                                    $exitoEnAsignacion = true;
                                }
                                if ($exitoEnAsignacion) {
                                    $asignados++;
                                } else {
                                    $noAsignados++;
                                }
                            }
                        } else {
                            $noAsignados++;
                        }
                    }
                }
            }
            Log::info("Total asignados: $asignados");
            Log::info("Total no asignados: $noAsignados");




            DB::commit();
            return response()->json([
                'status' => 'success',
                'message' => 'Horarios creados con éxito',
                'data' => [
                    'asignados' => $asignados,
                    'noAsignados' => $noAsignados,
                ],
            ], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => 'error',
                'message' => 'Se produjo un error al crear los horarios',

            ], 200);
        }
    }














    public function actualizar(Request $request, $id_disponibilidad)
    {
        // $id_dm = $dm->id_dm;
        // // Buscar registros en la tabla disponibilidades que tengan el mismo id_dm
        // $disponibilidad_vieja = Disponibilidad::where('id_dm', $id_dm)->get();
        // // Verificar si se encontraron registros
        // if ($disponibilidad_vieja->isNotEmpty()) {
        //     foreach ($disponibilidad_vieja as $registro) {
        //         $registro->delete();
        //     }
        // }

        // $modulos_semanales = UnidadCurricular::where('id_materia', $dm->id_materia)->value('modulos_semanales');
        // $id_aula = Aula::where("id_aula", $dm->id_aula)->value('id_aula');
        // $id_grado = Grado::where("id_grado", $dm->id_grado)->value('id_grado');
        // $id_h_p_d = $h_p_d->id_h_p_d;
        // $diaInstituto = $h_p_d->dia;
        // $moduloPrevio = $this->disponibilidadService->horaPrevia($id_h_p_d);


        // $distribucion = $this->disponibilidadService->modulosRepartidos($modulos_semanales, $moduloPrevio, $id_dm, $id_grado, $id_aula, $diaInstituto);
        // if (empty($distribucion)) {
        //     $dm->delete();
        //     $h_p_d->delete();
        //     return redirect()->route('indexAsignacion');
        // }

        // foreach ($distribucion as $data) {
        //     $dia = $data['dia'];
        //     $modulo_inicio = $data['modulo_inicio'];
        //     $modulo_fin = $data['modulo_fin'];

        //     $params = [
        //         'id_dm' => $id_dm,
        //         'id_h_p_d' => $id_h_p_d,
        //         'dia' => $dia,
        //         'modulo_inicio' => $modulo_inicio,
        //         'modulo_fin' => $modulo_fin,

        //     ];

        //     $response = $this->disponibilidadService->actualizarDisponibilidad($params, "");
        // }
        // if ($response && isset($response['success'])) {

        //     return redirect()->route('storeHorario')->with('success', $response['success']);
        // } else {
        //     $dm->delete();
        //     $h_p_d->delete();
        //     return redirect()->route('indexAsignacion')->withErrors(['error' => $response['error']]);
        // }
    }



    public function eliminar(Request $request)
    {
        $id = $request->input('id');
        $response = $this->disponibilidadService->eliminarDisponibilidadPorId($id);
        if (isset($response['success'])) {
            return redirect()->route('disponibilidades.index')->with('success', $response['success']);
        } else {
            return redirect()->route('disponibilidades.index')->withErrors(['error' => $response['error']]);
        }
    }
}
