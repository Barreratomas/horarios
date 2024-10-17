import React, { useState } from 'react';
import { useNavigate } from 'react-router-dom';

import 'bootstrap/dist/css/bootstrap.min.css';
import '../../../css/menu.css';
import { getRoutes } from '../../../Routes';

const Menu = () => {
  const routes = getRoutes(); // Llamada a la función para obtener las rutas

  const [isCollapsed, setIsCollapsed] = useState(true);
  const navigate = useNavigate();

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
                  className="nav-link"
                  onClick={() => navigate(`${routes.base}/${routes.home}`)}
                >
                  Home
                </button>
              </li>
              {sessionStorage.getItem('userType') === 'estudiante' && (
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
                          className="nav-link"
                          onClick={() => navigate(`${routes.base}/${routes.planilla.alumnos}`)}
                        >
                          Horarios alumno
                        </button>
                      </li>
                      <li className="nav-item">
                        <button
                          className="nav-link"
                          onClick={() => navigate(`${routes.base}/${routes.planilla.docente}`)}
                        >
                          Horarios docente
                        </button>
                      </li>
                    </>
                  )}
                  <li className="nav-item">
                    <button
                      className="nav-link"
                      onClick={() => navigate(`${routes.base}/${routes.planilla.bedelia}`)}
                    >
                      Horarios bedelia
                    </button>
                  </li>
                  <li className="nav-item">
                    <button
                      className="nav-link"
                      onClick={() => navigate(`${routes.base}/${routes.aulas.main}`)}
                    >
                      Aulas
                    </button>
                  </li>
                  <li className="nav-item">
                    <button
                      className="nav-link"
                      onClick={() => navigate(`${routes.base}/${routes.materias.main}`)}
                    >
                      Materias
                    </button>
                  </li>
                  <li className="nav-item">
                    <button
                      className="nav-link"
                      onClick={() => navigate(`${routes.base}/${routes.carreras.main}`)}
                    >
                      Carreras
                    </button>
                  </li>
                  <li className="nav-item">
                    <button
                      className="nav-link"
                      onClick={() => navigate(`${routes.base}/${routes.comisiones.main}`)}
                    >
                      Grados
                    </button>
                  </li>
                  <li className="nav-item">
                    <button
                      className="nav-link"
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
                      className="nav-link"
                      onClick={() =>
                        navigate(`${routes.base}/${routes.horariosPreviosDocente.main}`)
                      }
                    >
                      horario previo del docente(en desarrollo)
                    </button>
                  </li>
                  <li className="nav-item">
                    <button
                      className="nav-link"
                      onClick={() => navigate(`${routes.base}/${routes.disponibilidad.main}`)}
                    >
                      asignaciones del docente(en desarrollo)
                    </button>
                  </li>
                  <li className="nav-item">
                    <button
                      className="nav-link"
                      onClick={() => navigate(`${routes.base}/${routes.asignacionesAlumno.main}`)}
                    >
                      Asignacion alumnos a grados
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
              {sessionStorage.getItem('userType') && (
                <p style={{ color: 'red' }}>
                  Tipo de usuario: {sessionStorage.getItem('userType')}
                </p>
              )}
            </div>
          </div>
        </nav>
      </div>
    </div>
  );
};

export default Menu;
