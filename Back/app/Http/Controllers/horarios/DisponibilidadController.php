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
use Illuminate\Support\Facades\Log;



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


    public function guardarDisponibilidades()
    {
        DB::beginTransaction();


        $grados = Grado::all();

        // $gradosUC = GradoUC::all();
        // $disponibilidades = Disponibilidad::all();
        // $modulosSemanalesMap = UnidadCurricular::pluck('modulos_semanales', 'id_uc');

        // recorrer solo los grado en bucle
        $asignados = 0; // Contador para asignaciones exitosas
        $noAsignados = 0; // Contador para asignaciones no realizadas        
        foreach ($grados as $grado) {
            // obtener todas las materias de $gradp
            $materias = DB::table('grado')
                ->join('grado_uc', 'grado.id_grado', '=', 'grado_uc.id_grado')
                ->join('unidad_curricular', 'grado_uc.id_uc', '=', 'unidad_curricular.id_uc')
                ->select(
                    'unidad_curricular.id_uc',
                    'unidad_curricular.formato',
                    'unidad_curricular.horas_sem'
                )
                ->where('grado.id_grado', '=', $grado->id_grado) // Filtrar solo por el grado actual
                ->get();




            foreach ($materias as $materia) {
                // Log::info("- id de materia: {$materia->id_uc} (Formato: {$materia->formato}, Horas: {$materia->horas_sem})");
                // obtener los docentes que tengan materia
                $docentes = DB::table('unidad_curricular')
                    ->join('docente_uc', 'unidad_curricular.id_uc', '=', 'docente_uc.id_uc')
                    ->join('docente', 'docente_uc.id_docente', '=', 'docente.id_docente')
                    ->select(
                        'docente.id_docente',
                    )
                    ->where('unidad_curricular.id_uc', '=', $materia->id_uc)  // Filtrar por la materia actual
                    ->get();

                foreach ($docentes as $docente) {
                    // verificar si el docente tiene horarios previos
                    $horariosPrevios = DB::table('horario_previo_docente')
                        ->where('id_docente', $docente->id_docente)
                        ->get();  // Devuelve una colección con todos los campos

                    if ($horariosPrevios) {
                        //se asigna el modulo de inicio dependiendo la hora previa del docente 
                        foreach ($horariosPrevios as $previo) {

                            $horaPrevia = $this->disponibilidadService->horaPrevia($previo->hora);
                            // llamar a modulosRepartidos
                            $distribucion = $this->disponibilidadService->modulosRepartidos($materia->horas_sem, $docente->id_docente, $grado->id_grado, $horaPrevia, $previo->dia);
                        }
                    } else {
                        // llamar a modulosRepartidos   
                        $distribucion = $this->disponibilidadService->modulosRepartidos($materia->horas_sem, $docente->id_docente, $grado->id_grado);
                    }
                    // Loguear la distribución de módulos
                    Log::info('Distribución de módulos para el docente ' . $docente->id_docente . ' sin horario previo:', [
                        'distribucion' => json_encode($distribucion)
                    ]);
                    if (empty($distribucion)) {
                        continue;
                    }
                    foreach ($distribucion as $dia => $data) {
                        $params = [
                            'id_uc' => $materia->id_uc,
                            'id_docente' => $docente->id_docente,
                            'id_h_p_d' => $previo ? $previo->id_h_p_d : null, // Si $previo no existe, asigna null
                            'id_aula' => $data['aula'],
                            'id_grado' => $grado->id_grado,
                            'dia' => $dia,
                            'modulo_inicio' => $data['modulo_inicio'],
                            'modulo_fin' => $data['modulo_fin'],
                        ];
                        $response = $this->disponibilidadService->guardarDisponibilidad($params);
                        if ($response) {
                            $asignados++; // Incrementar el contador de asignados
                        } else {
                            $noAsignados++; // Incrementar el contador de no asignados
                        }
                    }
                }


            }
        }
        // Loguear los resultados
Log::info("Total asignados: $asignados");
Log::info("Total no asignados: $noAsignados");
        try {
            //     $id_grado = $grado->id_grado;
            //     $id_uc = $grado->id_uc;

            //     $docentesUC = DocenteUC::where('id_uc', $id_uc)->pluck('id_docente');

            //     foreach ($docentesUC as $id_docente) {
            //         $horariosPreviosDocente = HorarioPrevioDocente::where('id_docente', $id_docente)->get();

            //         foreach ($horariosPreviosDocente as $horarioPrevioDocente) {
            //             foreach ($aulas as $aula) {
            //                 $id_aula = $aula->id_aula;

            //                 // if ($this->verificarDisponibilidad($disponibilidades, $id_aula, $horarioPrevioDocente->id_h_p_d, $id_docente, $id_grado, $id_uc)) {
            //                 //     continue;
            //                 // }

            //                 $modulos_semanales = $modulosSemanalesMap[$id_uc] ?? null;
            //                 $diaInstituto = $horarioPrevioDocente->dia;
            //                 $moduloPrevio = $this->disponibilidadService->horaPrevia($horarioPrevioDocente->id_h_p_d);

            //                 $distribucion = $this->disponibilidadService->modulosRepartidos(
            //                     $modulos_semanales,
            //                     $moduloPrevio,
            //                     $id_uc,
            //                     $id_grado,
            //                     $id_aula,
            //                     $diaInstituto
            //                 );

            //                 if (empty($distribucion)) {
            //                     continue;
            //                 }

            //                 foreach ($distribucion as $data) {
            //                     $params = [
            //                         'id_uc' => $id_uc,
            //                         'id_h_p_d' => $horarioPrevioDocente->id_h_p_d,
            //                         'id_grado' => $id_grado,
            //                         'id_docente' => $id_docente,
            //                         'id_aula' => $id_aula,
            //                         'dia' => $data['dia'],
            //                         'modulo_inicio' => $data['modulo_inicio'],
            //                         'modulo_fin' => $data['modulo_fin'],
            //                     ];

            //                     $response = $this->disponibilidadService->guardarDisponibilidad($params);

            //                     if (isset($response['error'])) {
            //                         DB::rollBack();
            //                         return redirect()->route('indexAsignacion')->withErrors(['error' => $response['error']]);
            //                     }
            //                 }
            //             }
            //         }
            //     }
            // }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
        }
    }




    // public function guardar()
    // {   

    //     // Iniciar la transacción
    //     DB::beginTransaction();

    //     try{

    //         $gradosUC = GradoUC::all(); // Relación de los grados con las unidades curriculares
    //         $aulas = Aula::all(); // Relación de las aulas
    //         $disponibilidades = Disponibilidad::all(); // Relación de las disponibilidades


    //         foreach ($gradosUC as $gradoUC){
    //             $id_grado = $gradoUC->id_grado;
    //             $id_uc = $gradoUC->id_uc;


    //             $docentesUC = DocenteUC::where('id_uc', $id_uc)->pluck('id_uc'); // Relación de los docentes con las unidades curriculares

    //             foreach ($docentesUC as $docenteUC){
    //                 $id_docente = $docenteUC->id_docente;

    //                 $horariosPreviosDocente = HorarioPrevioDocente::where('id_docente', $id_docente)->get(); // Relación de los horarios previos de los docentes

    //                 foreach($aulas as $aula){
    //                     $id_aula = $aula->id_aula;

    //                     //Verifica si se encuentra un aula asignada
    //                     if($disponibilidades->contains('id_aula', $id_aula)){
    //                         continue;
    //                     }

    //                     // Verifica si se encuentra un horario previo asignado
    //                     if($disponibilidades->contains('id_h_p_d', $horariosPreviosDocente->id_h_p_d)){
    //                         continue;
    //                     }

    //                     // Verfica si se encuentra un docente asignado
    //                     if($disponibilidades->contains('id_docente', $docenteUC->id_docente)){
    //                         continue;
    //                     }

    //                     // Verifica si se encuentra un grado asignado
    //                     if($disponibilidades->contains('id_grado', $gradoUC->id_grado)){
    //                         continue;
    //                     }

    //                     // Verifica si se encuentra una unidad curricular asignada
    //                     if($disponibilidades->contains('id_uc', $gradoUC->id_uc)){
    //                         continue;
    //                     }

    //                     $yaDisponible = Disponibilidad::where('id_uc', $id_uc)
    //                         ->where('id_docente', $id_docente)
    //                         ->where('id_h_p_d', $horariosPreviosDocente->id_h_p_d)
    //                         ->where('id_aula', $id_aula)
    //                         ->where('id_grado', $id_grado)
    //                         ->exists();

    //                     if($yaDisponible){
    //                         continue;
    //                     }

    //                     $modulos_semanales = UnidadCurricular::where('id_uc', $id_uc)->value('modulos_semanales');
    //                     $diaInstituto = $horariosPreviosDocente->dia;
    //                     $moduloPrevio = $this->disponibilidadService->horaPrevia($horariosPreviosDocente->id_h_p_d);

    //                     $distribucion = $this->disponibilidadService->modulosRepartidos(
    //                         $modulos_semanales,
    //                         $moduloPrevio,
    //                         $id_uc,
    //                         $id_grado,
    //                         $id_aula,
    //                         $diaInstituto
    //                     );

    //                     if (empty($distribucion)) {
    //                         continue;
    //                     }

    //                     foreach ($distribucion as $data) {
    //                         $dia = $data['dia'];
    //                         $modulo_inicio = $data['modulo_inicio'];
    //                         $modulo_fin = $data['modulo_fin'];

    //                         $params = [
    //                             'id_uc' => $id_uc,
    //                             'id_h_p_d' => $horariosPreviosDocente->id_h_p_d,
    //                             'id_grado' => $id_grado,
    //                             'id_docente' => $id_docente,
    //                             'id_aula' => $id_aula,
    //                             'dia' => $dia,
    //                             'modulo_inicio' => $modulo_inicio,
    //                             'modulo_fin' => $modulo_fin
    //                         ];

    //                         $response = $this->disponibilidadService->guardarDisponibilidad($params);

    //                         if (isset($response['error'])) {
    //                             DB::rollBack();
    //                             return redirect()->route('indexAsignacion')->withErrors(['error' => $response['error']]);
    //                         }
    //                     }
    //                 }
    //             }
    //         }
    //     } catch (\Exception $e) {
    //         DB::rollBack();
    //         return redirect()->route('indexAsignacion')->withErrors(['error' => $e->getMessage()]);
    //     }

    // }

    /*
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
        

        public function guardarDisponibilidad()
        {
            // Iniciar la transacción
            DB::beginTransaction();
        
            try {
                $gradosUC = GradoUC::all(); // Relación de los grados con las unidades curriculares
                $horariosParaGuardar = []; // Almacena los datos para la inserción masiva
        
                foreach ($gradosUC as $gradoUC) {
                    $id_grado = $gradoUC->id_grado;
                    $id_uc = $gradoUC->id_uc;
        
                    $docentesUC = DocenteUC::where('id_uc', $id_uc)->get(); // Relación de los docentes con las unidades curriculares
        
                    foreach ($docentesUC as $docenteUC) {
                        $id_docente = $docenteUC->id_docente;
        
                        $horariosPreviosDocente = HorarioPrevioDocente::where('id_docente', $id_docente)->get(); // Relación de los horarios previos
        
                        foreach ($horariosPreviosDocente as $horarioPrevio) {
                            $id_h_p_d = $horarioPrevio->id_h_p_d;
                            $diaInstituto = $horarioPrevio->dia;
        
                            // Calcular el módulo previo y la distribución
                            $moduloPrevio = $this->disponibilidadService->horaPrevia($id_h_p_d);
                            $modulos_semanales = UnidadCurricular::where(column: 'id_uc', $id_uc)->value('modulos_semanales');
        
                            $distribucion = $this->disponibilidadService->modulosRepartidos(
                                $modulos_semanales,
                                $moduloPrevio,
                                $id_uc,
                                $id_grado,
                                null, // Aquí estaba el id_aula, ahora lo dejamos nulo o lo eliminamos
                                $diaInstituto
                            );
        
                            if (empty($distribucion)) {
                                continue; // Si no hay horarios disponibles, pasa al siguiente docente
                            }
        
                            foreach ($distribucion as $data) {
                                $horariosParaGuardar[] = [
                                    'id_uc' => $id_uc,
                                    'id_h_p_d' => $id_h_p_d,
                                    'id_grado' => $id_grado,
                                    'dia' => $data['dia'],
                                    'modulo_inicio' => $data['modulo_inicio'],
                                    'modulo_fin' => $data['modulo_fin']
                                ];
                            }
                        }
                    }
                }
        
                // Guardado masivo
                if (!empty($horariosParaGuardar)) {
                    Disponibilidad::insert($horariosParaGuardar);
                }
        
                // Confirmar la transacción
                DB::commit();
        
                return redirect()->route('indexAsignacion')->with('success', 'Horarios asignados exitosamente.');
            } catch (\Exception $e) {
                // Revertir la transacción en caso de error
                DB::rollBack();
                return redirect()->route('indexAsignacion')->withErrors(['error' => $e->getMessage()]);
            }
        }
            */



    /*
    public function guardar(){
        
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
        */



    public function redireccionarError()
    {
        return view("disponibilidad.error");
    }



    public function actualizar(HorarioPrevioDocente $h_p_d, DocenteUC $dm)
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
        $id_aula = Aula::where("id_aula", $dm->id_aula)->value('id_aula');
        $id_grado = Grado::where("id_grado", $dm->id_grado)->value('id_grado');
        $id_h_p_d = $h_p_d->id_h_p_d;
        $diaInstituto = $h_p_d->dia;
        $moduloPrevio = $this->disponibilidadService->horaPrevia($id_h_p_d);


        $distribucion = $this->disponibilidadService->modulosRepartidos($modulos_semanales, $moduloPrevio, $id_dm, $id_grado, $id_aula, $diaInstituto);
        if (empty($distribucion)) {
            $dm->delete();
            $h_p_d->delete();
            return redirect()->route('indexAsignacion');
        }

        foreach ($distribucion as $data) {
            $dia = $data['dia'];
            $modulo_inicio = $data['modulo_inicio'];
            $modulo_fin = $data['modulo_fin'];

            $params = [
                'id_dm' => $id_dm,
                'id_h_p_d' => $id_h_p_d,
                'dia' => $dia,
                'modulo_inicio' => $modulo_inicio,
                'modulo_fin' => $modulo_fin,

            ];

            // dd($params);        
            $response = $this->disponibilidadService->actualizarDisponibilidad($params);
        }
        if ($response && isset($response['success'])) {

            return redirect()->route('storeHorario')->with('success', $response['success']);
        } else {
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


}
