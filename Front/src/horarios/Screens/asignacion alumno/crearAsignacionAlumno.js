import React, { useState, useEffect } from 'react';
import { useNavigate, useOutletContext } from 'react-router-dom';
import { useNotification } from '../layouts/parcials/notification';

const CrearAsignacionAlumno = () => {
  const [alumnos, setAlumnos] = useState([]);
  const [filteredAlumnos, setFilteredAlumnos] = useState([]);
  const [selectedAlumno, setSelectedAlumno] = useState(null);

  const [grados, setGrados] = useState([]);
  const [search, setSearch] = useState('');
  const [grado, setGrado] = useState('');
  const [showGrados, setShowGrados] = useState(false);
  const navigate = useNavigate();
  const { routes } = useOutletContext();

  const { addNotification } = useNotification();

  // Cargar alumnos desde la API
  const fetchAlumnos = async () => {
    try {
      const response = await fetch('http://127.0.0.1:8000/api/horarios/alumnos');
      const data = await response.json();
      setAlumnos(data);
      setFilteredAlumnos(data);
    } catch (error) {
      addNotification(`Error de conexión`, 'danger');
    }
  };

  // Cargar grados y carreras desde la API
  const fetchGrados = async (id_alumno) => {
    try {
      const response = await fetch(
        `http://127.0.0.1:8000/api/horarios/carreraGrados/materias/${id_alumno}`
      );
      const data = await response.json();
      if (data.error) {
        addNotification(data.error, 'danger');
        setShowGrados(false);
      } else {
        console.log(data);
        setGrados(data);
        setShowGrados(true);
      }
    } catch (error) {
      addNotification(`Error de conexión`, 'danger');
      setShowGrados(false);
    }
  };

  useEffect(() => {
    fetchAlumnos();
  }, []);

  // Filtrar alumnos por DNI
  const handleFilterDni = (e) => {
    const searchValue = e.target.value;
    setSearch(searchValue);

    const filtered = alumnos.filter((alumno) => {
      const dniString = String(alumno.DNI).toLowerCase();
      const fullName = `${alumno.nombre} ${alumno.apellido}`.toLowerCase();
      return (
        dniString.includes(searchValue.toLowerCase()) ||
        fullName.includes(searchValue.toLowerCase())
      );
    });
    setFilteredAlumnos(filtered);
  };

  // Seleccionar alumno
  const handleSelectAlumno = (alumno) => {
    setSelectedAlumno(alumno);
    fetchGrados(alumno.id_alumno);

    setFilteredAlumnos([]);
  };

  const handleSubmit = async (id_carrera_grado) => {
    try {
      console.log(selectedAlumno.id_alumno);
      console.log(id_carrera_grado);
      const response = await fetch(
        `http://127.0.0.1:8000/api/horarios/alumnoGrados/guardar/${selectedAlumno.id_alumno}/${id_carrera_grado}`,
        {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json'
          }
        }
      );
      const data = await response.json();
      if (data.error) {
        addNotification(data.error, 'danger');
      } else {
        navigate(`${routes.base}/${routes.asignacionesAlumno.main}`, {
          state: { successMessage: 'El alumno fue asignado con éxito', updated: true }
        });
      }
    } catch (error) {
      addNotification(`Error de conexión`, 'danger');
    }
  };

  return (
    <div className="container py-3">
      <div className="row align-items-center justify-content-center">
        <div className="col-6">
          <form className="text-center">
            {/* Input para filtrar por DNI */}
            <div className="mb-3">
              <label htmlFor="dni" className="form-label">
                Buscar por DNI o nombre del Alumno
              </label>
              <input
                type="text"
                className="form-control"
                placeholder="Escriba el DNI o nombre del alumno"
                value={search}
                onChange={handleFilterDni}
              />

              {/* Mostrar los resultados filtrados debajo del campo de entrada solo si hay un filtro */}
              {search && (
                <div className="mt-2">
                  {filteredAlumnos.length > 0 && (
                    <ul className="list-group" style={{ maxHeight: '200px', overflowY: 'auto' }}>
                      {filteredAlumnos.map((alumno) => (
                        <li
                          key={alumno.id_alumno}
                          className="list-group-item"
                          onClick={() => handleSelectAlumno(alumno)}
                          style={{ cursor: 'pointer' }}
                        >
                          {`${alumno.nombre} ${alumno.apellido} - DNI: ${alumno.DNI}`}
                        </li>
                      ))}
                    </ul>
                  )}
                </div>
              )}
            </div>

            {/* Selección de Grado y Carrera */}
            <div className="mb-3">
              <label htmlFor="grado" className="form-label">
                Seleccione el Grado y Carrera
              </label>
              <select
                name="grado"
                value={grado}
                onChange={(e) => setGrado(e.target.value)}
                className="form-select"
                required
                disabled={!showGrados}
              >
                <option value="">Seleccione una comision</option>
                {grados && Array.isArray(grados) && grados.length > 0 ? (
                  grados.map((item) => (
                    <option key={`${item.id_carrera_grado}`} value={`${item.id_carrera_grado}`}>
                      {`Grado: ${item.grado}, División: ${item.division} - Carrera: ${item.carrera} - Capacidad: ${item.capacidad}`}
                    </option>
                  ))
                ) : (
                  <option>No hay grados disponibles</option>
                )}
              </select>
            </div>
            {/* Botón de Envío */}
            <button
              type="button"
              className="btn btn-primary"
              onClick={() => handleSubmit(grado)}
              disabled={!showGrados}
            >
              Crear Asignación
            </button>
            <br />
            <br />
            <button
              type="button"
              className="btn btn-danger"
              onClick={() => navigate(`${routes.base}/${routes.asignacionesAlumno.main}`)}
            >
              Volver Atrás
            </button>
          </form>
        </div>
      </div>
    </div>
  );
};

export default CrearAsignacionAlumno;
