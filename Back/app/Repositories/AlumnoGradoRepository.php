<?php

namespace App\Repositories;

interface AlumnoGradoRepository
{
    public function obtenerTodosAlumnoGrado();
    public function obtenerAlumnoGradoPorIdAlumno($id_alumno);
    public function obtenerAlumnoGradoPorIdGrado($id_grado);
    //public function guardarAlumnoGrado($id_alumno, $id_gradoo);
    public function eliminarAlumnoGradoPorIdAlumno($id_alumno);
    public function eliminarAlumnoGradoPorIdGrado($id_grado);

    //asignar todos los alumnos a sus respectivas carreras partiendo de la tabla de alumnos_uc
    public function asignarAlumnosACarreras();


}
