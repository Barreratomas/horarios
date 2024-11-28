<?php

namespace App\Repositories;

interface AlumnoGradoRepository
{
    public function obtenerTodosAlumnoGrado();
    public function obtenerTodosAlumnoGradoConRelaciones();
    public function obtenerAlumnoGradoPorIdAlumno($id_alumno);
    public function obtenerAlumnoGradoPorIdAlumnoConRelaciones($id_alumno);
    public function obtenerAlumnoGradoPorIdGrado($id_grado);
    //public function guardarAlumnoGrado($id_alumno, $id_gradoo);
    public function eliminarAlumnoGradoPorIdAlumnoYIdGrado($id_alumno,$id_grado);
    public function eliminarAlumnoGradoPorIdGrado($id_grado);

    //asignar todos los alumnos a sus respectivas carreras partiendo de la tabla de alumnos_uc
    public function asignarAlumnosACarreras();
    //cambiar de comision a alumno recursante
    public function cambiarGradoRecursante($dni, $id_grado);


}
