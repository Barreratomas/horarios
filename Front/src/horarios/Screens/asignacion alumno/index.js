import React, { useState, useEffect } from 'react';
import { useNavigate, useOutletContext, useLocation } from 'react-router-dom';

const AsignacionAlumno = () => {
  const navigate = useNavigate();
  const { routes } = useOutletContext();
  const location = useLocation();

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
        const response = await fetch('http://127.0.0.1:8000/api/horarios/uCPlan', {
          headers: { Accept: 'application/json' }
        });

        if (!response.ok) throw new Error('Error al obtener los alumnos');

        const data = await response.json();
        setAlumnos(data);
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

  const handleDelete = async (dni) => {
    if (!window.confirm('¿Estás seguro de eliminar esta asignación?')) return;

    try {
      // falta la api que elimina a el alumno de la comision
      const response = await fetch(`http://127.0.0.1:8000/api/alumnos/eliminar/${dni}`, {
        method: 'DELETE',
        headers: { 'Content-Type': 'application/json' }
      });

      if (!response.ok) throw new Error('Error al eliminar la asignación');

      setAlumnos(alumnos.filter((alumno) => alumno.dni !== dni));
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

  const handleAssignStudent = async () => {
    try {
      // falta la api que agrega a el alumno de la comision

      const response = await fetch('http://127.0.0.1:8000/api/asignar-alumno', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({
          /* Aquí puedes incluir los datos necesarios para la asignación */
        })
      });

      if (!response.ok) throw new Error('Error al asignar alumno');

      const data = await response.json();
      setSuccessMessage(data.message || 'Alumno asignado correctamente');
      navigate(`${routes.base}/${routes.asignacionesAlumno.main}`, { replace: true });
    } catch (error) {
      setErrors([error.message || 'Error al asignar el alumno']);
    }
  };

  return (
    <div className="container py-3">
      <div className="row align-items-center justify-content-center">
        <div className="col-6 text-center">
          <button
            className="btn btn-primary me-2"
            onClick={handleAssignStudent} // Cambiar a la nueva función
          >
            Asignar Alumno
          </button>
        </div>
      </div>

      {loading ? (
        <p>Cargando...</p>
      ) : serverUp ? (
        <div className="container">
          <p>A espera de que se haga la API de asignación de alumnos</p>
          {alumnos.map((alumno) => (
            <div
              key={alumno.dni}
              style={{
                border: '1px solid #ccc',
                borderRadius: '5px',
                padding: '10px',
                marginBottom: '10px',
                width: '30vw'
              }}
            >
              <p>DNI: {alumno.dni}</p>
              <p>Nombre: {alumno.nombre}</p>
              <p>
                Grado: {alumno.grado}° {alumno.division}
              </p>
              <p>Carrera: {alumno.carrera}</p>

              <div className="botones">
                <button
                  className="btn btn-primary me-2"
                  onClick={() =>
                    navigate(`${routes.base}/${routes.asignacionesAlumno.actualizar(alumno.dni)}`)
                  }
                >
                  Actualizar
                </button>
                <button className="btn btn-danger" onClick={() => handleDelete(alumno.dni)}>
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
