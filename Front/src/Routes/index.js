import React, { useEffect } from 'react';
import { Route, Routes, useLocation } from 'react-router-dom';
// import Home from '../Components/Home';
// import Carreras from '../Views/LandingView/Carreras';
// import Inscripciones from '../Views/LandingView/Inscripciones';
// import SuperAdmin from '../Views/SuperAdminView/SuperAdmin';
// import AlumnoProfile from '../Views/AlumnoView/Profile';
// import InscripcionesAlumno from '../Views/AlumnoView/InscripcionesAlumno';
// import Materias from '../Views/AlumnoView/Materias';
// import Error404 from '../Views/Error404';
// import LandingView from '../Views/LandingView';
// import AlumnoView from '../Views/AlumnoView';
// import SuperAdminView from '../Views/SuperAdminView';
// import SignUp from '../Views/LandingView/SignUp';
// import RecoverPassword from '../Views/LandingView/Login/RecoverPassword';
// import ResetPassword from '../Views/LandingView/Login/ResetPassword';
// import Login from '../Views/LandingView/Login';

import Base from '../horarios/Screens/layouts/base';
import Home from '../horarios/Screens/home';
import Aulas from '../horarios/Screens/aula';
import CrearAula from '../horarios/Screens/aula/crearAula';
import ActualizarAula from '../horarios/Screens/aula/actualizarAula';
import Materias from '../horarios/Screens/materia';
import CrearMateria from '../horarios/Screens/materia/crearMateria';
import ActualizarMateria from '../horarios/Screens/materia/actualizarMateria';
import Comisiones from '../horarios/Screens/comision';
import CrearComision from '../horarios/Screens/comision/crearComision';
import ActualizarComision from '../horarios/Screens/comision/actualizarComision';
import Asignaciones from '../horarios/Screens/asignacion';
import CrearHorarioPrevio from '../horarios/Screens/horarioPrevioDocente/crearHorarioPrevio';
import ActualizarHorarioPrevio from '../horarios/Screens/horarioPrevioDocente/actualizarHorarioPrevioDocente';
import Horario from '../horarios/Screens/horario/index';
import HorarioBedelia from '../horarios/Screens/horario/indexBedelia';
import HorarioDocente from '../horarios/Screens/horario/indexDocente';
import Carreras from '../horarios/Screens/carrera';
import CrearCarrera from '../horarios/Screens/carrera/crearCarrera';
import ActualizarCarrera from '../horarios/Screens/carrera/actualizarCarrera';
import { getRoutes } from '../horarios/Routes';
const RoutesLanding = () => {
  const { pathname } = useLocation();
  const routes = getRoutes(); // Llamada a la función para obtener las rutas

  useEffect(() => {
    window.scrollTo(0, 0);
  }, [pathname]);

  return (
    <Routes>
      {/* <Route path="/" element={<LandingView />}>
        <Route index element={<Home />} />
        <Route path="/signup" element={<SignUp />} />
        <Route path="/login" element={<Login />} />
        <Route path="/recover-password" element={<RecoverPassword />} />
        <Route path="/reset-password/:token" element={<ResetPassword />} />
        <Route path="/carreras" element={<Carreras />} />
        <Route path="/inscripciones" element={<Inscripciones />} />
      </Route>

      <Route path="/alumno" element={<AlumnoView />}>
        <Route index element={<Home />} />
        <Route path="profile/:id" element={<AlumnoProfile />} />
        <Route path="materias" element={<Materias />} />
        <Route path="inscripciones" element={<InscripcionesAlumno />} />
      </Route>

      <Route path="/super-admin" element={<SuperAdminView />}>
        <Route index element={<Home />} />
        <Route path="administracion" element={<SuperAdmin />} />
        <Route path="carreras" element={<Carreras />} />
        <Route path="inscripciones" element={<Inscripciones />} />
      </Route> */}

      {/* <Route path="*" element={<Error404 />} /> */}

      {/*horarios  */}
      <Route path={routes.base} element={<Base hideMenu={false} />}>
        <Route index element={<Home />} />
        {/* Aulas */}
        <Route path={routes.aulas.main} element={<Aulas />} />
        <Route path={routes.aulas.crear} element={<CrearAula />} />
        <Route path={routes.aulas.actualizar(':aulaId')} element={<ActualizarAula />} />
        {/* Materias */}
        <Route path={routes.materias.main} element={<Materias />} />
        <Route path={routes.materias.crear} element={<CrearMateria />} />
        <Route path={routes.materias.actualizar(':materiaId')} element={<ActualizarMateria />} />
        {/* Carreras */}
        <Route path={routes.carreras.main} element={<Carreras />} />
        <Route path={routes.carreras.crear} element={<CrearCarrera />} />
        <Route path={routes.carreras.actualizar(':carreraId')} element={<ActualizarCarrera />} />
        {/* Comisiones */}
        <Route path={routes.comisiones.main} element={<Comisiones />} />
        <Route path={routes.comisiones.crear} element={<CrearComision />} />
        <Route
          path={routes.comisiones.actualizar(':comisionId')}
          element={<ActualizarComision />}
        />
        {/* Asignaciones */}
        <Route path={routes.asignaciones} element={<Asignaciones />} />
        {/* Horario previo docente */}
        <Route path={routes.crearHorarioPrevio(':dni')} element={<CrearHorarioPrevio />} />
        <Route
          path={routes.actualizarHorarioPrevio(':hpdId', ':dmId')}
          element={<ActualizarHorarioPrevio />}
        />
        {/* Planilla */}
        <Route path={routes.planilla.alumnos} element={<Horario />} />
        <Route path={routes.planilla.bedelia} element={<HorarioBedelia />} />
        <Route path={routes.planilla.docente} element={<HorarioDocente />} />
        {/* plan de estudio */}
        <Route path={routes.planes.main} element={<Carreras />} />
        <Route path={routes.planes.crear} element={<CrearCarrera />} />
        <Route path={routes.planes.actualizar(':planId')} element={<ActualizarCarrera />} />
      </Route>
    </Routes>
  );
};

export default RoutesLanding;
