import React, { useState, useEffect } from 'react';
import { useNavigate, useOutletContext, useLocation } from 'react-router-dom';

const Materias = () => {
  const navigate = useNavigate();
  const { routes } = useOutletContext();
  const location = useLocation();

  const [loading, setLoading] = useState(true); // Estado para manejar la carga
  const [serverUp, setServerUp] = useState(false); // Estado del servidor

  const [materias, setMaterias] = useState([]);
  const [errors, setErrors] = useState([]);
  const [successMessage, setSuccessMessage] = useState('');
  const [hideMessage, setHideMessage] = useState(false);

  useEffect(() => {
    if (location.state && location.state.successMessage) {
      setSuccessMessage(location.state.successMessage);

      setTimeout(() => setHideMessage(true), 3000); // Ocultar mensaje en 3s

      setTimeout(() => {
        setSuccessMessage('');
        setHideMessage(false);
        navigate(location.pathname, { replace: true }); // Limpiar estado de location
      }, 3500);
    }

    const fetchMaterias = async () => {
      setLoading(true);
      try {
        const response = await fetch('http://127.0.0.1:8000/api/horarios/unidadCurricular', {
          headers: { Accept: 'application/json' }
        });

        if (!response.ok) throw new Error(' ');

        const data = await response.json();
        setMaterias(data);
        setServerUp(true);
      } catch (error) {
        console.error('Error al obtener materias:', error);
        alert('Servidor fuera de servicio...');
      } finally {
        setLoading(false);
      }
    };

    fetchMaterias();
  }, [location.state, navigate, location.pathname]);

  const handleDelete = async (id_uc) => {
    try {
      const response = await fetch(`http://127.0.0.1:8000/api/materias/eliminar/${id_uc}`, {
        method: 'DELETE',
        headers: { 'Content-Type': 'application/json' }
      });

      if (!response.ok) throw new Error('Error al eliminar materia');

      setMaterias(materias.filter((materia) => materia.id_uc !== id_uc));
      setSuccessMessage('Materia eliminada correctamente');

      setTimeout(() => setHideMessage(true), 3000); // Ocultar mensaje
      setTimeout(() => {
        setSuccessMessage('');
        setHideMessage(false);
        navigate(location.pathname, { replace: true });
      }, 3500);
    } catch (error) {
      setErrors([error.message || 'Error al eliminar materia']);
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
                onClick={() => navigate(`${routes.base}/${routes.materias.crear}`)}
                style={{ display: 'inline-block', marginRight: '10px' }}
              >
                Crear
              </button>
            </div>
          </div>

          <div className="container">
            {materias.map((materia) => (
              <div
                key={materia.id_uc}
                style={{
                  border: '1px solid #ccc',
                  borderRadius: '5px',
                  padding: '10px',
                  marginBottom: '10px',
                  width: '30vw'
                }}
              >
                <p>Unidad Curricular: {materia.Unidad_Curricular}</p>
                <p>Tipo: {materia.Tipo}</p>
                <p>Horas Semanales: {materia.HorasSem}</p>
                <p>Horas Anuales: {materia.HorasAnual}</p>
                <p>Formato: {materia.Formato}</p>

                <div className="botones">
                  <button
                    type="button"
                    className="btn btn-primary me-2"
                    onClick={() =>
                      navigate(`${routes.base}/${routes.materias.actualizar(materia.id_uc)}`)
                    }
                    style={{ display: 'inline-block', marginRight: '10px' }}
                  >
                    Actualizar
                  </button>

                  <button
                    type="button"
                    className="btn btn-danger"
                    onClick={() => handleDelete(materia.id_uc)}
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

export default Materias;
