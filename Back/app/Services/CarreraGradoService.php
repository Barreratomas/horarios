<?php

namespace App\Services;

use App\Models\CarreraGrado;
use Illuminate\Support\Facades\Log;
use App\Repositories\CarreraGradoRepository;
use App\Services\horarios\GradoService;
use App\Services\horarios\CarreraService;
use App\Mappers\CarreraGradoMapper;
use App\Models\Alumno;
use App\Models\horarios\GradoUC;
use Exception;

class CarreraGradoService implements CarreraGradoRepository
{

    protected $carreraGradoMapper;
    protected $gradoService;
    protected $carreraService;

    public function __construct(CarreraGradoMapper $carreraGradoMapper, GradoService $gradoService, CarreraService $carreraService)
    {
        $this->carreraGradoMapper = $carreraGradoMapper;
        $this->gradoService = $gradoService;
        $this->carreraService = $carreraService;
    }

    public function obtenerTodosCarreraGrado()
    {
        try {
            // Obtener carreras, grados y unidades curriculares
            $carrerasGrados = CarreraGrado::with(['carrera', 'grado', 'grado_uc.unidadCurricular'])->get();

            // Transformar los datos para enviarlos en el formato esperado
            $normalizedData = $carrerasGrados->map(function ($item) {
                return [
                    'id_carrera_grado' => $item->id_carrera_grado,
                    'id_carrera' => $item->id_carrera,
                    'id_grado' => $item->id_carrera_grado,
                    'carrera' => [
                        'id_carrera' => $item->carrera->id_carrera,
                        'carrera' => $item->carrera->carrera,
                        'cupo' => $item->carrera->cupo,
                    ],
                    'grado' => [
                        'id_grado' => $item->grado->id_grado,
                        'grado' => $item->grado->grado,
                        'division' => $item->grado->division,
                        'detalle' => $item->grado->detalle,
                        'capacidad' => $item->capacidad,
                        'grado_uc' => $item->grado_uc->map(function ($uc) {
                            return [
                                'id_uc' => $uc->id_uc,
                                'unidad_curricular' => [
                                    'id_uc' => $uc->unidadCurricular->id_uc,
                                    'unidad_curricular' => $uc->unidadCurricular->unidad_curricular,
                                    'tipo' => $uc->unidadCurricular->tipo,
                                    'horas_sem' => $uc->unidadCurricular->horas_sem,
                                    'horas_anual' => $uc->unidadCurricular->horas_anual,
                                    'formato' => $uc->unidadCurricular->formato,
                                ],
                            ];
                        }),
                    ],
                ];
            });

            return response()->json($normalizedData, 200);
        } catch (Exception $e) {
            Log::error('Error al obtener los carrerasGrados: ' . $e->getMessage());
            return response()->json(['error' => 'Hubo un error al obtener los carrerasGrados'], 500);
        }
    }

    public function obtenerCarreraGradoPorMaterias($id_alumno)
    {

        $alumno = Alumno::find($id_alumno);
        if (!$alumno) {
            return response()->json(['error' => 'Alumno no encontrado'], 404);
        }
        try {
            $uc = [];
            foreach ($alumno->alumno_uc as $alumnoUC) {
                $uc[] = $alumnoUC->id_uc;
            }

            $id_carrera = $alumno->alumno_carrera->first()->id_carrera;

            $carreraGrados = GradoUC::whereIn('id_uc', $uc)
                ->whereHas('carreraGrado', function ($query) use ($id_carrera) {
                    $query->where('id_carrera', $id_carrera);
                })
                ->get()
                ->pluck('id_carrera_grado');



            $grados = CarreraGrado::with(['grado', 'carrera'])
                ->whereIn('id_carrera_grado', $carreraGrados)
                ->get()
                ->map(function ($carreraGrado) {
                    $totalAlumnos = $carreraGrado->alumno_grado()->count(); // Obtener el número de alumnos asignados
                    $capacidadDisponible = $carreraGrado->capacidad - $totalAlumnos; // Capacidad total menos alumnos actuales

                    return [
                        'id_carrera_grado' => $carreraGrado->id_carrera_grado,
                        'capacidad' => $capacidadDisponible,
                        'grado' => $carreraGrado->grado->grado,
                        'division' => $carreraGrado->grado->division,
                        'carrera' => $carreraGrado->carrera->carrera,
                    ];
                });
            if ($grados->isEmpty()) {
                return response()->json(['error' => "No se encontraron grados para el alumno {$alumno->DNI}"], 404);
            }
            log::info("grados {$grados}");
            return response()->json($grados, 200);

            return response()->json(200);
        } catch (Exception $e) {
            Log::error('Error al obtener el alumnoGrado: ' . $e->getMessage());
            return response()->json(['error' => 'Hubo un error al obtener el alumnoGrado'], 500);
        }
    }



    public function obtenerCarreraGrado($id_carreraGrado)
    {
        try {
            // Buscar el CarreraGrado por su ID e incluir las relaciones necesarias
            $carreraGrado = CarreraGrado::with([
                'carrera',
                'grado',
                'grado_uc.unidadCurricular' // Incluir las materias relacionadas
            ])->findOrFail($id_carreraGrado);

            // Estructurar la respuesta con la información relevante
            $response = [
                'id_carrera_grado' => $carreraGrado->id_carrera_grado,
                'carrera' => $carreraGrado->carrera->carrera ?? 'Sin carrera',
                'id_carrera' => $carreraGrado->id_carrera,
                'grado' => [
                    'nombre' => $carreraGrado->grado->grado ?? 'Sin grado',
                    'detalle' => $carreraGrado->grado->detalle ?? 'Sin detalle'
                ],
                'capacidad' => $carreraGrado->capacidad,
                'materias' => $carreraGrado->grado_uc->map(function ($gradoUC) {
                    return $gradoUC->unidadCurricular->unidad_curricular ?? 'Sin nombre';
                })
            ];

            return response()->json($response, 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'No se pudo obtener el CarreraGrado: ' . $e->getMessage()], 404);
        }
    }


    public function obtenerCarreraGradoPorIdCarrera($id_carrera)
    {
        try {
            // Buscar registros con los detalles de las relaciones incluyendo las Unidades Curriculares (materias)
            $carreraGrado = CarreraGrado::with(['carrera', 'grado', 'grado.grado_uc.unidadCurricular'])
                ->where('id_carrera', $id_carrera)
                ->get();

            // Verificar si hay resultados
            if ($carreraGrado->isEmpty()) {
                return response()->json(['error' => 'CarreraGrado no encontrado'], 404);
            }

            return response()->json($carreraGrado, 200);
        } catch (Exception $e) {
            Log::error('Error al obtener el carreraGrado: ' . $e->getMessage());
            return response()->json(['error' => 'Hubo un error al obtener el carreraGrado'], 500);
        }
    }

    public function obtenerCarreraGradoPorIdCarreraSinUC($id_carrera)
    {
        try {

            $carreraGrado = CarreraGrado::with(['grado'])
                ->where('id_carrera', $id_carrera)
                ->get();

            // Verificar si hay resultados
            if ($carreraGrado->isEmpty()) {
                return response()->json(['error' => 'CarreraGrado no encontrado'], 404);
            }

            return response()->json($carreraGrado, 200);
        } catch (Exception $e) {
            Log::error('Error al obtener el carreraGrado: ' . $e->getMessage());
            return response()->json(['error' => 'Hubo un error al obtener el carreraGrado'], 500);
        }
    }


    public function obtenerCarreraGradoPorIdGrado($id_grado)
    {
        try {
            // Buscar registros con los detalles de las relaciones
            $carreraGrado = CarreraGrado::with(['carrera', 'grado'])
                ->where('id_grado', $id_grado)
                ->get();

            // Verificar si hay resultados
            if ($carreraGrado->isEmpty()) {
                return response()->json(['error' => 'CarreraGrado no encontrado'], 404);
            }

            return response()->json($carreraGrado, 200);
        } catch (Exception $e) {
            Log::error('Error al obtener el carreraGrado: ' . $e->getMessage());
            return response()->json(['error' => 'Hubo un error al obtener el carreraGrado'], 500);
        }
    }


    public function update($idCarreraGrado, $nuevaCapacidad)
    {
        try {
            // Busca el registro correspondiente con la relación grado y carrera
            $carreraGrado = CarreraGrado::with(['grado', 'carrera'])->findOrFail($idCarreraGrado);

            // Obtiene el cupo total de la carrera
            $cupoCarrera = $carreraGrado->carrera->cupo;

            // Suma la capacidad existente en carrera_grado (excluyendo el registro actual)
            $capacidadExistente = CarreraGrado::where('id_carrera', $carreraGrado->id_carrera)
                ->where('id_carrera_grado', '!=', $idCarreraGrado)
                ->sum('capacidad') ?? 0;

            // Valida que la nueva capacidad no supere el cupo total
            if (($capacidadExistente + $nuevaCapacidad) > $cupoCarrera) {
                return response()->json([
                    'error' => 'La capacidad actualizada supera el cupo total de la carrera.'
                ], 400);
            }

            // Actualiza la capacidad
            $carreraGrado->capacidad = $nuevaCapacidad;
            $carreraGrado->save();

            Log::info("Capacidad actualizada correctamente", [
                'id_carrera_grado' => $idCarreraGrado,
                'nueva_capacidad' => $nuevaCapacidad
            ]);

            // Prepara los datos para la respuesta
            $gradoDetalles = $carreraGrado->grado;

            return response()->json([
                'message' => 'Capacidad actualizada exitosamente',
                'data' => [
                    'id_carrera_grado' => $carreraGrado->id_carrera_grado,
                    'capacidad' => $carreraGrado->capacidad,
                    'grado' => [
                        'id_grado' => $gradoDetalles->id_grado,
                        'grado' => $gradoDetalles->grado,
                        'division' => $gradoDetalles->division,
                        'detalle' => $gradoDetalles->detalle
                    ]
                ]
            ], 200);
        } catch (\Exception $e) {
            Log::error("Error al actualizar la capacidad", [
                'id_carrera_grado' => $idCarreraGrado,
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'error' => 'Hubo un error al actualizar la capacidad'
            ], 500);
        }
    }



    public function guardarCarreraGrado($id_carrera, $id_grado, $capacidad)
    {
        $grado = $this->gradoService->obtenerGradoPorId($id_grado);
        $carrera = $this->carreraService->obtenerCarreraPorId($id_carrera);
        if (!$grado || !$carrera) {
            return ['error' => 'No se encontró el grado o la carrera'];
        }



        try {
            // Verificar si ya existe un registro con el mismo id_carrera y id_grado
            $existingCarreraGrado = CarreraGrado::where('id_carrera', $id_carrera)
                ->where('id_grado', $id_grado)
                ->first();

            if ($existingCarreraGrado) {
                return response()->json(['message' => 'El registro ya existe', 'data' => $existingCarreraGrado], 409);
            }

            // Suma la capacidad de todos los grados ya asociados a la carrera
            $capacidadExistente = CarreraGrado::where('id_carrera', $id_carrera)->sum('capacidad');

            // Valida si la suma de capacidades más la nueva supera el cupo de la carrera
            if (($capacidadExistente + $capacidad) > $carrera->cupo) {
                return response()->json([
                    'error' => 'No se puede crear la comisión proque la capacidad supera el cupo total de la carrera.'
                ], 400);
            }

            $carreraGrado = $this->carreraGradoMapper->toCarreraGrado($id_carrera, $id_grado, $capacidad);
            $carreraGrado->save();
            return response()->json($carreraGrado, 201);
        } catch (Exception $e) {
            Log::error('Error al guardar la carreraGrado: ' . $e->getMessage());
            return response()->json(['error' => 'Hubo un error al guardar la carreraGrado'], 500);
        }
    }






    public function eliminarCarreraGradoPorIdGradoYCarrera($id_carrera_grado)
    {
        $carreraGrado = CarreraGrado::where('id_carrera_grado', $id_carrera_grado)->first();
        $carreraGradoCopy = CarreraGrado::with(["carrera", "grado"])->where("id_carrera_grado", $id_carrera_grado)->first();
        if (!$carreraGrado) {
            return response()->json(['error' => 'CarreraGrado no encontrado'], 404);
        }
        try {

            // Buscar si existen otros registros con el mismo id_grado y division
            $existenOtrosGrados = CarreraGrado::where('id_grado', $carreraGrado->id_grado)
                ->whereHas('grado', function ($query) use ($carreraGrado) {
                    $query->where('division', $carreraGrado->grado->division);
                })
                ->count();

            if ($existenOtrosGrados == 1) {
                $carreraGrado->grado()->delete();
            }
            // Eliminar relaciones dependientes
            $carreraGrado->alumno_grado()->delete();
            $carreraGrado->grado_uc()->delete();
            $carreraGrado->disponibilidad()->delete();


            // Eliminar CarreraGrado
            $carreraGrado->delete();
            return response()->json($carreraGradoCopy, 200);
        } catch (Exception $e) {
            Log::error('Error al eliminar la carreraGrado: ' . $e->getMessage());
            return response()->json(['error' => 'Hubo un error al eliminar la carreraGrado'], 500);
        }
    }
}
