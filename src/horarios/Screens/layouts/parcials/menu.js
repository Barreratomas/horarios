import React, { useState } from 'react';
import { useNavigate, useLocation } from 'react-router-dom';
import Sesiones from './sesiones';

import 'bootstrap/dist/css/bootstrap.min.css';
import '../../../css/menu.css';
import { getRoutes } from '../../../Routes';

const Menu = () => {
  const routes = getRoutes(); // Llamada a la función para obtener las rutas

  const [isCollapsed, setIsCollapsed] = useState(true);
  const navigate = useNavigate();
  const location = useLocation(); // Obtiene la ruta actual

  // Maneja el toggle del menú
  const handleToggle = () => {
    setIsCollapsed(!isCollapsed);

    // Lógica para animar el botón
    const botonMenu = document.getElementById('toggleButton');
    botonMenu.classList.toggle('float-right');

    // Animación de transición
    if (botonMenu.classList.contains('float-right')) {
      botonMenu.style.marginLeft = '210px';
      botonMenu.style.transition = '0.3s';

      botonMenu.disabled = false;
    } else {
      botonMenu.style.marginLeft = '0';
      botonMenu.style.transition = '0.3s';
    }
  };
  const isActive = (path) => location.pathname === path; // Verifica si la ruta coincide con la actual
  const crearDisponibilidades = async () => {
    try {
      const response = await fetch(
        'http://127.0.0.1:8000/api/horarios/disponibilidad/guardarDisponibilidades',
        {
          method: 'GET', // Método GET
          headers: {
            'Content-Type': 'application/json'
          }
        }
      );

      if (!response.ok) {
        throw new Error('Error al obtener los datos');
      }

      const data = await response.json(); // Convertimos la respuesta en JSON

      // Verificamos el estado recibido en el JSON
      if (data.status === 'success') {
        console.log(data.message); // "Horarios creados con éxito"
        console.log(`Asignados: ${data.data.asignados}, No asignados: ${data.data.noAsignados}`);
      } else {
        console.error('Ocurrió un error en el servidor:', data.message);
      }
    } catch (error) {
      console.error('Error al crear disponibilidades:', error);
    }
  };

  return (
    <div>
      <div className="button-container">
        <button
          id="toggleButton"
          className="btn btn-primary"
          type="button"
          aria-controls="sidebar"
          aria-expanded={!isCollapsed}
          aria-label="Toggle navigation"
          onClick={handleToggle}
        >
          <i className="fas fa-bars"></i>
        </button>
      </div>

      <div className={`sidebar ${isCollapsed ? '' : 'show'}`} id="sidebar">
        <nav className="col-md-3 col-lg-2 d-md-block bg-light vh-100 navElemento">
          <div className="position-sticky cont-nav">
            <ul className="nav flex-column">
              <li className="nav-item">
                <button
                  className={`nav-link ${
                    isActive(`${routes.base}/${routes.home}`) ? 'active' : ''
                  }`}
                  onClick={() => navigate(`${routes.base}/${routes.home}`)}
                >
                  Home
                </button>
              </li>
              {sessionStorage.getItem('userType') === 'alumno' && (
                <li className="nav-item">
                  <button
                    className="nav-link"
                    onClick={() => navigate(`${routes.base}/${routes.planilla.alumnos}`)}
                  >
                    Horarios
                  </button>
                </li>
              )}
              {sessionStorage.getItem('userType') === 'docente' && (
                <li className="nav-item">
                  <button
                    className="nav-link"
                    onClick={() => navigate(`${routes.base}/${routes.planilla.docente}`)}
                  >
                    Horarios
                  </button>
                </li>
              )}
              {(sessionStorage.getItem('userType') === 'bedelia' ||
                sessionStorage.getItem('userType') === 'admin') && (
                <>
                  {sessionStorage.getItem('userType') === 'admin' && (
                    <>
                      <li className="nav-item">
                        <button
                          className={`nav-link ${
                            isActive(`${routes.base}/${routes.planilla.alumnos}`) ? 'active' : ''
                          }`}
                          onClick={() => navigate(`${routes.base}/${routes.planilla.alumnos}`)}
                        >
                          Horarios alumno
                        </button>
                      </li>
                      <li className="nav-item">
                        <button
                          className={`nav-link ${
                            isActive(`${routes.base}/${routes.planilla.docente}`) ? 'active' : ''
                          }`}
                          onClick={() => navigate(`${routes.base}/${routes.planilla.docente}`)}
                        >
                          Horarios docente
                        </button>
                      </li>
                    </>
                  )}
                  <li className="nav-item">
                    <button
                      className={`nav-link ${
                        isActive(`${routes.base}/${routes.planilla.bedelia}`) ? 'active' : ''
                      }`}
                      onClick={() => navigate(`${routes.base}/${routes.planilla.bedelia}`)}
                    >
                      Horarios bedelia
                    </button>
                  </li>
                  <li className="nav-item">
                    <button
                      className={`nav-link ${
                        isActive(`${routes.base}/${routes.aulas.main}`) ? 'active' : ''
                      }`}
                      onClick={() => navigate(`${routes.base}/${routes.aulas.main}`)}
                    >
                      Aulas
                    </button>
                  </li>
                  <li className="nav-item">
                    <button
                      className={`nav-link ${
                        isActive(`${routes.base}/${routes.materias.main}`) ? 'active' : ''
                      }`}
                      onClick={() => navigate(`${routes.base}/${routes.materias.main}`)}
                    >
                      Materias
                    </button>
                  </li>
                  <li className="nav-item">
                    <button
                      className={`nav-link ${
                        isActive(`${routes.base}/${routes.carreras.main}`) ? 'active' : ''
                      }`}
                      onClick={() => navigate(`${routes.base}/${routes.carreras.main}`)}
                    >
                      Carreras
                    </button>
                  </li>
                  <li className="nav-item">
                    <button
                      className={`nav-link ${
                        isActive(`${routes.base}/${routes.comisiones.main}`) ? 'active' : ''
                      }`}
                      onClick={() => navigate(`${routes.base}/${routes.comisiones.main}`)}
                    >
                      Grados
                    </button>
                  </li>
                  <li className="nav-item">
                    <button
                      className={`nav-link ${
                        isActive(`${routes.base}/${routes.planes.main}`) ? 'active' : ''
                      }`}
                      onClick={() => navigate(`${routes.base}/${routes.planes.main}`)}
                    >
                      Planes de estudio
                    </button>
                  </li>
                  {/* <li className="nav-item">
                    <a className="nav-link" href="/docentes">
                      Docentes
                    </a>
                  </li> */}
                  <li className="nav-item">
                    <button
                      className={`nav-link ${
                        isActive(`${routes.base}/${routes.horariosPreviosDocente.main}`)
                          ? 'active'
                          : ''
                      }`}
                      onClick={() =>
                        navigate(`${routes.base}/${routes.horariosPreviosDocente.main}`)
                      }
                    >
                      horario previo del docente
                    </button>
                  </li>
                  <li className="nav-item">
                    <button
                      className={`nav-link ${
                        isActive(`${routes.base}/${routes.disponibilidad.main}`) ? 'active' : ''
                      }`}
                      onClick={() => navigate(`${routes.base}/${routes.disponibilidad.main}`)}
                    >
                      asignaciones del docente(en desarrollo)
                    </button>
                  </li>
                  <li className="nav-item">
                    <button
                      className={`nav-link ${
                        isActive(`${routes.base}/${routes.asignacionesAlumno.main}`) ? 'active' : ''
                      }`}
                      onClick={() => navigate(`${routes.base}/${routes.asignacionesAlumno.main}`)}
                    >
                      Asignacion alumnos a grados
                    </button>
                  </li>
                  <li className="nav-item">
                    <button
                      className={`nav-link ${
                        isActive(`${routes.base}/${routes.logs.main}`) ? 'active' : ''
                      }`}
                      onClick={() => navigate(`${routes.base}/${routes.logs.main}`)}
                    >
                      Logs
                    </button>
                  </li>
                  <li className="nav-item">
                    <button className="nav-link" onClick={crearDisponibilidades}>
                      crear horarios
                    </button>
                  </li>
                </>
              )}
            </ul>
            <div className="logout">
              <a className="nav-link" href="/logout">
                <button type="button" className="btn btn-danger">
                  Logout
                </button>
              </a>
            </div>
            <div className="userType">
              <Sesiones />
            </div>
          </div>
        </nav>
      </div>
    </div>
  );
};

export default Menu;
