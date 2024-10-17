import React, { useState, useEffect } from 'react';
import { useNavigate, useOutletContext, useLocation } from 'react-router-dom';

const Aulas = () => {
  const navigate = useNavigate();
  const { routes } = useOutletContext();
  const location = useLocation();

  const [loading, setLoading] = useState(true); // Estado para manejar la carga
  const [serverUp, setServerUp] = useState(false); // Estado para manejar el estado del servidor

  const [aulas, setAulas] = useState([]);
  const [errors, setErrors] = useState([]);
  const [successMessage, setSuccessMessage] = useState('');
  const [hideMessage, setHideMessage] = useState(false);

  useEffect(() => {
    if (location.state && location.state.successMessage) {
      setSuccessMessage(location.state.successMessage);

      // Mostrar el mensaje durante 3 segundos
      setTimeout(() => {
        setHideMessage(true); // Ocultar con la clase CSS
      }, 3000);

      // Limpiar después de la transición
      setTimeout(() => {
        setSuccessMessage(''); // Eliminar el mensaje después de la transición
        setHideMessage(false); // Resetear para la próxima vez

        // aca navega a la misma ruta sin estado para limpiar el location.state
        navigate(location.pathname, { replace: true }); // Reemplaza la entrada en el historial para no tener el state
      }, 3500);
    }

    const checkServerStatus = async () => {
      setLoading(true);
      try {
        const response = await fetch('http://127.0.0.1:8000/api/horarios/aulas', {
          headers: { Accept: 'application/json' }
        });

        if (!response.ok) throw new Error(' ');

        const jsonResponse = await response.json();
        if (jsonResponse) {
          setAulas(jsonResponse);
          setServerUp(true);
        } else {
          alert('Servidor fuera de servicio...');
        }
      } catch (error) {
        console.error('Error checking server status:', error);
        alert('Error al verificar el servidor...');
      } finally {
        setLoading(false);
      }
    };

    checkServerStatus();
  }, [location.state, navigate, location.pathname]);

  const handleDelete = async (id_aula) => {
    try {
      const response = await fetch(`http://127.0.0.1:8000/api/horarios/aulas/eliminar/${id_aula}`, {
        method: 'DELETE',
        headers: { 'Content-Type': 'application/json' }
      });

      if (!response.ok) throw new Error('Error al eliminar aula');

      setAulas(aulas.filter((aula) => aula.id_aula !== id_aula));
      setSuccessMessage('Aula eliminada con éxito');

      // Mostrar el mensaje durante 3 segundos
      setTimeout(() => {
        setHideMessage(true); // Ocultar con la clase CSS
      }, 3000);

      setTimeout(() => {
        setSuccessMessage('');
        setHideMessage(false);

        // Limpiar el estado de location
        navigate(location.pathname, { replace: true });
      }, 3500);
    } catch (error) {
      setErrors([error.message || 'Error al eliminar aula']);
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
                onClick={() => navigate(`${routes.base}/${routes.aulas.crear}`)}
                style={{ display: 'inline-block', marginRight: '10px' }}
              >
                Crear
              </button>
            </div>
          </div>

          <div className="container">
            {aulas.map((aula) => (
              <div
                key={aula.id_aula}
                style={{
                  border: '1px solid #ccc',
                  borderRadius: '5px',
                  padding: '10px',
                  marginBottom: '10px',
                  width: '30vw'
                }}
              >
                <p>Nombre: {aula.nombre}</p>
                <p>Tipo de Aula: {aula.tipo_aula}</p>
                <p>Capacidad: {aula.capacidad}</p>

                <div className="botones">
                  <button
                    type="button"
                    className="btn btn-primary me-2"
                    onClick={() =>
                      navigate(`${routes.base}/${routes.aulas.actualizar(aula.id_aula)}`)
                    } // Corregido: Comillas alrededor de la plantilla
                    style={{ display: 'inline-block', marginRight: '10px' }}
                  >
                    Actualizar
                  </button>
                  <button
                    type="button"
                    className="btn btn-danger"
                    onClick={() => handleDelete(aula.id_aula)}
                  >
                    Eliminar
                  </button>
                </div>
              </div>
            ))}
          </div>

          <div
            id="messages-container"
            className={`container ${hideMessage ? 'hide-messages' : ''}`} // Corregido: Comillas para el template string
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

export default Aulas;
