import React, { useState, useEffect } from 'react';
import { useNavigate, useOutletContext, useLocation } from 'react-router-dom';

const AsignacionAlumno = () => {
  const navigate = useNavigate();
  const { routes } = useOutletContext();
  const location = useLocation();

  const [filteredAlumnos, setFilteredAlumnos] = useState([]);
  const [searchQuery, setSearchQuery] = useState('');

  const [alumnos, setAlumnos] = useState([]);
  const [loading, setLoading] = useState(true);
  const [serverUp, setServerUp] = useState(false);
  const [successMessage, setSuccessMessage] = useState('');
  const [hideMessage, setHideMessage] = useState(false);
  const [errors, setErrors] = useState([]);

  useEffect(() => {
    if (location.state?.successMessage) {
      setSuccessMessage(location.state.successMessage);

      setTimeout(() => setHideMessage(true), 3000);

      setTimeout(() => {
        setSuccessMessage('');
        setHideMessage(false);
        navigate(location.pathname, { replace: true });
      }, 3500);
    }

    const fetchAlumnos = async () => {
      try {
        const response = await fetch('http://127.0.0.1:8000/api/horarios/alumnoGrados/relaciones', {
          headers: { Accept: 'application/json' }
        });

        if (!response.ok) throw new Error('Error al obtener los alumnos');

        const data = await response.json();
        setAlumnos(data);
        setFilteredAlumnos(data);
        setServerUp(true);
      } catch (error) {
        console.error('Error al obtener los alumnos:', error);
        setErrors([error.message || 'Error al conectar con el servidor...']);
      } finally {
        setLoading(false);
      }
    };

    fetchAlumnos();
  }, [location, navigate]);

  const handleSearch = (e) => {
    const query = e.target.value.toLowerCase();
    setSearchQuery(query);

    const filtered = alumnos.filter(
      (alumno) =>
        alumno.alumno.DNI.toString().includes(query) ||
        `${alumno.alumno.nombre} ${alumno.alumno.apellido}`.toLowerCase().includes(query)
    );

    setFilteredAlumnos(filtered);
  };

  const handleDelete = async (id_alumno, id_grado) => {
    if (!window.confirm('¿Estás seguro de eliminar esta asignación?')) return;

    try {
      const response = await fetch(
        `http://127.0.0.1:8000/api/horarios/alumnoGrados/${id_alumno}/${id_grado}`,
        {
          method: 'DELETE',
          headers: { 'Content-Type': 'application/json' }
        }
      );

      if (!response.ok) throw new Error('Error al eliminar la asignación');

      setAlumnos(alumnos.filter((alumno) => alumno.id_alumno !== id_alumno));
      setFilteredAlumnos(filteredAlumnos.filter((alumno) => alumno.id_alumno !== id_alumno));

      setSuccessMessage('Asignación eliminada correctamente');

      setTimeout(() => setHideMessage(true), 3000);
      setTimeout(() => {
        setSuccessMessage('');
        setHideMessage(false);
      }, 3500);
    } catch (error) {
      setErrors([error.message || 'Error al eliminar la asignación']);
    }
  };

  const handleAssignIngresantes = async () => {
    // Mostrar notificación al iniciar
    setSuccessMessage('Comenzó el proceso de asignación de ingresantes');
    setHideMessage(false);
    try {
      const response = await fetch(
        'http://127.0.0.1:8000/api/horarios/alumnoGrados/asignarIngresantes',
        {
          method: 'GET',
          headers: { 'Content-Type': 'application/json' }
        }
      );

      if (!response.ok) throw new Error('Error al asignar alumno');

      const data = await response.json();
      setSuccessMessage(data.message || 'Alumno asignado correctamente');
      navigate(`${routes.base}/${routes.asignacionesAlumno.main}`, { replace: true });
    } catch (error) {
      setErrors([error.message || 'Error al asignar el alumno']);
    } finally {
      setTimeout(() => {
        setHideMessage(true);
        setSuccessMessage('');
      }, 3500);
    }
  };
  const handleAssignNoIngresantes = async () => {
    setSuccessMessage('Comenzó el proceso de asignación de no ingresantes');
    setHideMessage(false);
    try {
      const response = await fetch('http://127.0.0.1:8000/api/horarios/alumnoGrados/asignar', {
        method: 'GET',
        headers: { 'Content-Type': 'application/json' }
      });

      if (!response.ok) throw new Error('Error al asignar alumnos no ingresantes');

      const data = await response.json();
      setSuccessMessage(data.message || 'Alumnos no ingresantes asignados correctamente');
      navigate(`${routes.base}/${routes.asignacionesAlumno.main}`, { replace: true });
    } catch (error) {
      setErrors([error.message || 'Error al asignar alumnos no ingresantes']);
    } finally {
      setTimeout(() => {
        setHideMessage(true);
        setSuccessMessage('');
      }, 3500);
    }
  };

  return (
    <div className="container py-3">
      <div className="row align-items-center justify-content-center">
        <div className="col-6 text-center">
          <div className="filter mb-2 d-flex flex-wrap align-items-center">
            {/* Input ocupa el 70% del espacio */}
            <input
              type="text"
              className="form-control mb-2 mb-md-0 me-md-2"
              placeholder="Buscar por DNI o Nombre y Apellido"
              value={searchQuery}
              onChange={handleSearch}
            />
          </div>
          <button className="btn btn-primary me-2" onClick={handleAssignNoIngresantes}>
            Asignar alumnos no ingresantes
          </button>
          <button className="btn btn-primary me-2" onClick={handleAssignIngresantes}>
            Asignacion masiva ingresantes
          </button>
          <button
            type="button"
            className="btn btn-primary me-2"
            onClick={() => navigate(`${routes.base}/${routes.asignacionesAlumno.crear}`)}
            style={{ display: 'inline-block', marginRight: '10px' }}
          >
            Asignar solo un alumno
          </button>
        </div>
      </div>

      {loading ? (
        <p>Cargando...</p>
      ) : serverUp ? (
        <div className="container">
          {filteredAlumnos.map((alumno) => (
            <div
              key={alumno.id_alumno}
              style={{
                border: '1px solid #ccc',
                borderRadius: '5px',
                padding: '10px',
                marginBottom: '10px',
                width: '30vw'
              }}
            >
              <p>DNI: {alumno.alumno.DNI}</p>
              <p>
                Nombre: {alumno.alumno.nombre} {alumno.alumno.apellido}
              </p>
              <p>
                Grado: {alumno.grado.grado}° {alumno.grado.division} ({alumno.grado.detalle})
              </p>
              <p>Carrera: {alumno.alumno.carrera}</p>
              <div className="botones">
                <button
                  className="btn btn-primary me-2"
                  onClick={() => {
                    const url = `${routes.base}/${routes.asignacionesAlumno.actualizar(
                      alumno.id_alumno,
                      alumno.grado.id_grado
                    )}`;
                    navigate(url);
                  }}
                >
                  Actualizar
                </button>
                <button
                  className="btn btn-danger"
                  onClick={() => handleDelete(alumno.id_alumno, alumno.grado.id_grado)}
                >
                  Eliminar
                </button>
              </div>
            </div>
          ))}
        </div>
      ) : (
        <h1>Este módulo no está disponible en este momento</h1>
      )}

      <div id="messages-container" className={`container ${hideMessage ? 'hide-messages' : ''}`}>
        {errors.length > 0 && (
          <div className="alert alert-danger">
            <ul>
              {errors.map((error, index) => (
                <li key={index}>{error}</li>
              ))}
            </ul>
          </div>
        )}
        {successMessage && <div className="alert alert-success">{successMessage}</div>}
      </div>
    </div>
  );
};

export default AsignacionAlumno;
