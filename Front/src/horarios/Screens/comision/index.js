import React, { useState, useEffect } from 'react';
import { useNavigate, useOutletContext, useLocation } from 'react-router-dom';

const HorarioPrevio = () => {
  const navigate = useNavigate();
  const { routes } = useOutletContext(); // Acceder a las rutas definidas
  const location = useLocation(); // Obtener ubicación actual para manejar el estado de navegación

  const [loading, setLoading] = useState(true);
  const [serverUp, setServerUp] = useState(false);
  const [horarios, setHorarios] = useState([]);
  const [errors, setErrors] = useState([]);
  const [successMessage, setSuccessMessage] = useState('');
  const [hideMessage, setHideMessage] = useState(false);

  // Efecto para manejar la carga inicial y los mensajes de éxito
  useEffect(() => {
    if (location.state && location.state.successMessage) {
      setSuccessMessage(location.state.successMessage);

      setTimeout(() => setHideMessage(true), 3000); // Ocultar mensaje en 3 segundos

      setTimeout(() => {
        setSuccessMessage('');
        setHideMessage(false);
        navigate(location.pathname, { replace: true }); // Limpiar el estado de navegación
      }, 3500);
    }

    const fetchHorarios = async () => {
      setLoading(true);
      try {
        const response = await fetch('http://127.0.0.1:8000/api/horarios_previos', {
          headers: { Accept: 'application/json' }
        });

        if (!response.ok) throw new Error('Error al obtener horarios previos');

        const data = await response.json();
        setHorarios(data);
        setServerUp(true);
      } catch (error) {
        console.error('Error al obtener horarios previos:', error);
        setErrors([error.message || 'Servidor fuera de servicio...']);
      } finally {
        setLoading(false);
      }
    };

    fetchHorarios();
  }, [location.state, navigate, location.pathname]);

  // Función para eliminar un horario previo
  const handleDelete = async (id) => {
    if (!window.confirm('¿Estás seguro de eliminar este horario?')) return;

    try {
      const response = await fetch(`http://127.0.0.1:8000/api/horarios_previos/${id}`, {
        method: 'DELETE'
      });

      if (!response.ok) throw new Error('Error al eliminar el horario');

      setHorarios(horarios.filter((horario) => horario.id !== id));
      setSuccessMessage('Horario eliminado correctamente');

      setTimeout(() => setHideMessage(true), 3000); // Ocultar mensaje en 3 segundos
      setTimeout(() => {
        setSuccessMessage('');
        setHideMessage(false);
        navigate(location.pathname, { replace: true }); // Limpiar navegación
      }, 3500);
    } catch (error) {
      console.error('Error al eliminar horario:', error);
      setErrors([error.message || 'Error al eliminar el horario']);
    }
  };

  return (
    <>
      {loading ? (
        <p>Cargando...</p>
      ) : serverUp ? (
        <div className="container py-3">
          <h2>Horarios Previos</h2>

          <div className="row align-items-center justify-content-center">
            <div className="col-6 text-center">
              <button
                type="button"
                className="btn btn-primary me-2"
                onClick={() => navigate(`${routes.base}/${routes.horariosPrevios.crear}`)}
                style={{ display: 'inline-block', marginRight: '10px' }}
              >
                Crear
              </button>
            </div>
          </div>

          <div className="container">
            {horarios.map((horario) => (
              <div
                key={horario.id}
                style={{
                  border: '1px solid #ccc',
                  borderRadius: '5px',
                  padding: '10px',
                  marginBottom: '10px',
                  width: '30vw'
                }}
              >
                <p>Docente: {horario.docente_nombre}</p>
                <p>Día: {horario.dia}</p>
                <p>Hora: {horario.hora}</p>

                <div className="botones">
                  <button
                    type="button"
                    className="btn btn-primary me-2"
                    onClick={() =>
                      navigate(`${routes.base}/${routes.horariosPrevios.actualizar(horario.id)}`)
                    }
                    style={{ display: 'inline-block', marginRight: '10px' }}
                  >
                    Actualizar
                  </button>

                  <button
                    type="button"
                    className="btn btn-danger"
                    onClick={() => handleDelete(horario.id)}
                  >
                    Eliminar
                  </button>
                </div>
              </div>
            ))}
          </div>

          <div
            id="messages-container"
            className={`container ${hideMessage ? 'hide-messages' : ''}`}
          >
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
      ) : (
        <h1>Este módulo no está disponible en este momento</h1>
      )}
    </>
  );
};

export default HorarioPrevio;
