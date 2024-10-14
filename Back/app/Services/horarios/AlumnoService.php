<?php

namespace App\Services\horarios;

use App\Repositories\horarios\AlumnoRepository;
use App\Mappers\horarios\AlumnoMapper;
use App\Models\Alumno;
use Exception;
use Illuminate\Support\Facades\Log;


class AlumnoService implements AlumnoRepository
{
    private $alumnoMapper;

    public function __construct(AlumnoMapper $alumnoMapper)
    {
        $this->alumnoMapper = $alumnoMapper;
    }

    public function obtenerTodosAlumnos()
    {
        try {
            $alumnos = Alumno::all();
            return response()->json($alumnos, 200);
        } catch (Exception $e) {
            Log::error('Error al obtener los alumnos: ' . $e->getMessage());
            return response()->json(['error' => 'Hubo un error al obtener los alumnos'], 500);
        }
    }

    public function obtenerAlumnoPorId($id)
    {
        $alumno = Alumno::find($id);
        if (!$alumno) {
            return response()->json(['error' => 'Alumno no encontrado'], 404);
        }
        try {
            return response()->json($alumno, 200);
        } catch (Exception $e) {
            Log::error('Error al obtener el alumno: ' . $e->getMessage());
            return response()->json(['error' => 'Hubo un error al obtener el alumno'], 500);
        }
    }

    public function guardarAlumno($request)
    {
        try {
            $alumnoData = $request->all();
            $alumno = new Alumno($alumnoData);
            $alumnoModel = $this->alumnoMapper->toAlumno($alumno);
            $alumnoModel->save();
            return response()->json($alumnoModel, 201);
        } catch (Exception $e) {
            Log::error('Error al guardar el alumno: ' . $e->getMessage());
            return response()->json(['error' => 'Hubo un error al guardar el alumno'], 500);
        }
    }

    public function actualizarAlumno($request, $id)
    {
        $alumno = Alumno::find($id);
        if (!$alumno) {
            return response()->json(['error' => 'Alumno no encontrado'], 404);
        }
        try {
            $alumno->update($request->all());
            return response()->json($alumno, 200);
        } catch (Exception $e) {
            Log::error('Error al actualizar el alumno: ' . $e->getMessage());
            return response()->json(['error' => 'Hubo un error al actualizar el alumno'], 500);
        }
    }

    public function eliminarAlumnoPorId($id)
    {
        try {
            $alumno = Alumno::find($id);
            if ($alumno) {
                $alumno->delete();
                return response()->json(['success' => 'Alumno eliminado con exito'], 200);
            } else {
                return response()->json(['error' => 'Alumno no encontrado'], 404);
            }
        } catch (Exception $e) {
            Log::error('Error al eliminar el alumno: ' . $e->getMessage());
            return response()->json(['error' => 'Hubo un error al eliminar el alumno'], 500);
        }
    }
}
