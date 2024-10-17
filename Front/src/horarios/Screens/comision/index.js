import React, { useState, useEffect } from 'react';
import { useNavigate, useOutletContext, useLocation } from 'react-router-dom';

const Comisiones = () => {
  const navigate = useNavigate();
  const { routes } = useOutletContext(); // Acceder a las rutas definidas
  const location = useLocation(); // Obtener ubicación actual para manejar el estado de navegación

  const [loading, setLoading] = useState(true);
  const [serverUp, setServerUp] = useState(false);
  const [grados, setGrados] = useState([]);
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

    const fetchGrados = async () => {
      setLoading(true);
      try {
        const response = await fetch('http://127.0.0.1:8000/api/horarios/grados', {
          headers: { Accept: 'application/json' }
        });

        if (!response.ok) throw new Error('Error al obtener los grados');

        const data = await response.json();
        console.log(data);
        setGrados(data);
        setServerUp(true);
      } catch (error) {
        console.error('Error al obtener grados:', error);
        setErrors([error.message || 'Servidor fuera de servicio...']);
      } finally {
        setLoading(false);
      }
    };

    fetchGrados();
  }, [location.state, navigate, location.pathname]);

  const handleDelete = async (id) => {
    if (!window.confirm('¿Estás seguro de eliminar este grado?')) return;

    try {
      const response = await fetch(`http://127.0.0.1:8000/api/horarios/grados/${id}`, {
        method: 'DELETE'
      });

      if (!response.ok) throw new Error('Error al eliminar el grado');

      setGrados(grados.filter((grado) => grado.id_grado !== id));
      setSuccessMessage('Grado eliminado correctamente');

      setTimeout(() => setHideMessage(true), 3000); // Ocultar mensaje en 3 segundos
      setTimeout(() => {
        setSuccessMessage('');
        setHideMessage(false);
        navigate(location.pathname, { replace: true }); // Limpiar navegación
      }, 3500);
    } catch (error) {
      console.error('Error al eliminar grado:', error);
      setErrors([error.message || 'Error al eliminar el grado']);
    }
  };

  return (
    <>
      {loading ? (
        <p>Cargando...</p>
      ) : serverUp ? (
        <div className="container py-3">
          <div className="row align-items-center justify-content-center">
            <div className="col-6 text-center">
              <button
                type="button"
                className="btn btn-primary me-2"
                onClick={() => navigate(`${routes.base}/${routes.comisiones.crear}`)}
                style={{ display: 'inline-block', marginRight: '10px' }}
              >
                Crear
              </button>
            </div>
          </div>

          <div className="container">
            {grados.map((grado) => (
              <div
                key={grado.id_grado}
                style={{
                  border: '1px solid #ccc',
                  borderRadius: '5px',
                  padding: '10px',
                  marginBottom: '10px',
                  width: '30vw'
                }}
              >
                <p>Grado: {grado.grado}</p>
                <p>División: {grado.division}</p>
                <p>Detalle: {grado.detalle}</p>
                <p>Capacidad: {grado.capacidad}</p>

                <div className="botones">
                  <button
                    type="button"
                    className="btn btn-primary me-2"
                    onClick={() =>
                      navigate(`${routes.base}/${routes.comisiones.actualizar(grado.id_grado)}`)
                    }
                    style={{ display: 'inline-block', marginRight: '10px' }}
                  >
                    Actualizar
                  </button>

                  <button
                    type="button"
                    className="btn btn-danger"
                    onClick={() => handleDelete(grado.id_grado)}
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

export default Comisiones;
