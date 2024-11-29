import React, { useState, useEffect } from 'react';
import { useNavigate, useOutletContext, useLocation } from 'react-router-dom';
import '../../css/acordeon.css';

// Componente Accordion reutilizable
const Accordion = ({ title, children }) => {
  const [isOpen, setIsOpen] = useState(false);

  const toggleAccordion = () => {
    setIsOpen(!isOpen);
  };

  return (
    <div className="accordion">
      <div className="accordion-header" onClick={toggleAccordion}>
        <h3>{title}</h3>
      </div>
      <div className={`accordion-body ${isOpen ? 'open' : ''}`}>{children}</div>
    </div>
  );
};

const Planes = () => {
  const navigate = useNavigate();
  const { routes } = useOutletContext();
  const location = useLocation();

  const [loading, setLoading] = useState(true);
  const [serverUp, setServerUp] = useState(false);
  const [planes, setPlanes] = useState([]);
  const [filteredPlanes, setFilteredPlanes] = useState([]);
  const [errors, setErrors] = useState([]);
  const [successMessage, setSuccessMessage] = useState('');
  const [hideMessage, setHideMessage] = useState(false);
  const [filterText, setFilterText] = useState(''); // Nuevo estado para el texto de filtro

  useEffect(() => {
    if (location.state && location.state.successMessage) {
      setSuccessMessage(location.state.successMessage);
      setTimeout(() => setHideMessage(true), 3000);
      setTimeout(() => {
        setSuccessMessage('');
        setHideMessage(false);
        navigate(location.pathname, { replace: true });
      }, 3500);
    }

    const fetchPlanes = async () => {
      setLoading(true);
      try {
        const response = await fetch('http://127.0.0.1:8000/api/horarios/planEstudio/relaciones', {
          headers: { Accept: 'application/json' }
        });

        if (!response.ok) throw new Error('Error al obtener los planes');

        const data = await response.json();
        setPlanes(data);
        setFilteredPlanes(data); // Inicializamos el estado de planes filtrados
        setServerUp(true);
      } catch (error) {
        setErrors([error.message || 'Servidor fuera de servicio...']);
      } finally {
        setLoading(false);
      }
    };

    fetchPlanes();
  }, [location.state, navigate, location.pathname]);

  useEffect(() => {
    // Filtrar planes cuando el texto de filtro cambie
    if (filterText === '') {
      setFilteredPlanes(planes); // Si no hay texto de filtro, mostramos todos los planes
    } else {
      setFilteredPlanes(
        planes.filter(
          (plan) => plan.detalle.toLowerCase().includes(filterText.toLowerCase()) // Filtro por detalle
        )
      );
    }
  }, [filterText, planes]);

  const handleDelete = async (id_plan) => {
    if (!window.confirm('¿Estás seguro de eliminar este plan?')) return;

    try {
      const response = await fetch(
        `http://127.0.0.1:8000/api/horarios/planEstudio/eliminar/${id_plan}`,
        {
          method: 'DELETE',
          headers: { 'Content-Type': 'application/json' }
        }
      );

      if (!response.ok) throw new Error('Error al eliminar el plan');

      setPlanes(planes.filter((plan) => plan.id_plan !== id_plan));
      setSuccessMessage('Plan eliminado correctamente');

      setTimeout(() => setHideMessage(true), 3000);
      setTimeout(() => {
        setSuccessMessage('');
        setHideMessage(false);
        navigate(location.pathname, { replace: true });
      }, 3500);
    } catch (error) {
      setErrors([error.message || 'Error al eliminar el plan']);
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
              <div className="mb-3">
                <input
                  type="text"
                  className="form-control"
                  placeholder="Filtrar por detalle"
                  value={filterText}
                  onChange={(e) => setFilterText(e.target.value)} // Actualiza el estado del filtro
                />
              </div>

              <button
                type="button"
                className="btn btn-primary me-2"
                onClick={() => navigate(`${routes.base}/${routes.planes.crear}`)}
              >
                Crear
              </button>
            </div>
          </div>

          <div className="container">
            {filteredPlanes.map((plan) => (
              <div
                key={plan.id_plan}
                style={{
                  border: '1px solid #ccc',
                  borderRadius: '5px',
                  padding: '10px',
                  marginBottom: '10px',
                  width: '30vw'
                }}
              >
                <p>Detalle: {plan.detalle}</p>
                <p>Fecha Inicio: {new Date(plan.fecha_inicio).toLocaleDateString()}</p>
                <p>Fecha Fin: {new Date(plan.fecha_fin).toLocaleDateString()}</p>

                {/* Accordion para las unidades curriculares */}
                <Accordion title="Ver Unidades Curriculares">
                  {plan.unidades_curriculares.length > 0 ? (
                    <ul>
                      {plan.unidades_curriculares.map((uc) => (
                        <li key={uc.id_uc}>
                          <strong>{uc.unidad_curricular}</strong> - {uc.tipo} ({uc.formato})
                          <br />
                          <small>
                            {uc.horas_sem} horas/semana, {uc.horas_anual} horas/año
                          </small>
                        </li>
                      ))}
                    </ul>
                  ) : (
                    <p>No hay unidades curriculares asociadas.</p>
                  )}
                </Accordion>

                {/* Accordion para las carreras */}
                <Accordion title="Ver Carrera Asociada">
                  {plan.carreras.length > 0 ? (
                    <ul>
                      {plan.carreras.map((carrera) => (
                        <li key={carrera.id_carrera}>
                          <strong>{carrera.carrera}</strong> (Cupo: {carrera.cupo})
                        </li>
                      ))}
                    </ul>
                  ) : (
                    <p>No hay carreras asociadas.</p>
                  )}
                </Accordion>

                <div className="botones">
                  <button
                    type="button"
                    className="btn btn-primary me-2"
                    onClick={() =>
                      navigate(`${routes.base}/${routes.planes.actualizar(plan.id_plan)}`)
                    }
                  >
                    Actualizar
                  </button>

                  <button
                    type="button"
                    className="btn btn-danger"
                    onClick={() => handleDelete(plan.id_plan)}
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

export default Planes;
