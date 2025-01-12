import React, { useState, useEffect } from 'react';
import { useNavigate, useOutletContext, useParams } from 'react-router-dom';
import { Modal, Button } from 'react-bootstrap';

const ActualizarComision = () => {
  const usuario = sessionStorage.getItem('userType');
  const { comisionId } = useParams();
  const [capacidad, setCapacidad] = useState('');
  const [materias, setMaterias] = useState([]);
  const [materiasSeleccionadas, setMateriasSeleccionadas] = useState([]);
  const [detalles, setDetalles] = useState('');
  const [errors, setErrors] = useState({});
  const [fetchError, setFetchError] = useState('');
  const [isLoading, setIsLoading] = useState(true);
  const [showModal, setShowModal] = useState(false);
  const [isSubmitting, setIsSubmitting] = useState(false);

  const navigate = useNavigate();
  const { routes } = useOutletContext();

  // Obtener los datos de la comisión existente
  useEffect(() => {
    const fetchComision = async () => {
      try {
        const response = await fetch(
          `http://127.0.0.1:8000/api/horarios/carreraGrados/${comisionId}`
        );
        const data = await response.json();
        console.log(data);
        if (response.ok) {
          setCapacidad(data.capacidad);
          setMateriasSeleccionadas(data.materias || []);
        } else {
          setFetchError('Error al obtener el grado.');
        }
      } catch (error) {
        setFetchError('Error de red al intentar cargar los datos del grado.');
      } finally {
        setIsLoading(false);
      }
    };

    fetchComision();
  }, [comisionId]);

  // Cargar materias disponibles
  useEffect(() => {
    const fetchData = async () => {
      try {
        // Obtener las materias asociadas al grado
        const gradoUCResponse = await fetch(
          `http://127.0.0.1:8000/api/horarios/gradoUC/idGrado/relaciones/${comisionId}`
        );
        const gradoUCData = await gradoUCResponse.json();

        if (!gradoUCResponse.ok || !Array.isArray(gradoUCData)) {
          console.log('No hay materias asociadas al grado o error en los datos.');
          setMateriasSeleccionadas([]); // Dejar materias seleccionadas vacías
        } else {
          // Extraer IDs de las materias asociadas al grado
          const materiasAsociadas = gradoUCData.map((item) => item.unidad_curricular.id_uc);
          setMateriasSeleccionadas(materiasAsociadas);
        }

        // Obtener todas las materias disponibles
        const materiasResponse = await fetch('http://127.0.0.1:8000/api/horarios/unidadCurricular');
        const materiasData = await materiasResponse.json();

        if (!materiasResponse.ok) {
          throw new Error('Error al cargar las materias disponibles.');
        }

        setMaterias(materiasData);
      } catch (error) {
        setFetchError(error.message || 'Error de red.');
      } finally {
        setIsLoading(false);
      }
    };

    fetchData();
  }, [comisionId]);

  // Manejar la selección de materias
  const handleCheckboxChange = (idMateria) => {
    setMateriasSeleccionadas((prev) =>
      prev.includes(idMateria) ? prev.filter((id) => id !== idMateria) : [...prev, idMateria]
    );
  };
  const handleSubmit = (e) => {
    e.preventDefault();
    setShowModal(true); // Mostrar el modal de confirmación
  };
  // Manejar la actualización
  const handleConfirmUpdate = async () => {
    setIsSubmitting(true); // Iniciar el proceso de envío

    try {
      const response = await fetch(
        `http://127.0.0.1:8000/api/horarios/grados/actualizar/${comisionId}`,
        {
          method: 'PUT',
          headers: {
            'Content-Type': 'application/json'
          },
          body: JSON.stringify({
            capacidad,
            materias: materiasSeleccionadas,
            detalles,
            usuario
          })
        }
      );

      if (response.ok) {
        navigate(`${routes.base}/${routes.comisiones.main}`, {
          state: { successMessage: 'Comisión actualizada con éxito' }
        });
      } else {
        const data = await response.json();
        if (data.errors) {
          setErrors(data.errors);
        }
      }
    } catch (error) {
      setFetchError('Error al intentar actualizar la comisión.');
    } finally {
      setIsSubmitting(false); // Finalizar el proceso de envío
      setShowModal(false); // Cerrar el modal de confirmación
    }
  };

  // Cancelar la actualización
  const handleCancelUpdate = () => {
    setShowModal(false); // Cerrar el modal de confirmación sin hacer nada
  };

  if (isLoading) return <p>Cargando datos de la comisión...</p>;

  return (
    <div className="container py-3">
      <div className="row align-items-center justify-content-center">
        <div className="col-6 text-center">
          <form onSubmit={handleSubmit}>
            {fetchError && <div className="alert alert-danger">{fetchError}</div>}

            <label htmlFor="capacidad">Capacidad</label>
            <input
              type="number"
              name="capacidad"
              value={capacidad}
              onChange={(e) => setCapacidad(e.target.value)}
              placeholder="Capacidad"
              required
            />

            <div className="materias-list mt-3">
              <h5>Seleccione las materias asociadas:</h5>
              {materias.map((materia) => (
                <div key={materia.id_uc} className="form-check">
                  <input
                    className="form-check-input"
                    type="checkbox"
                    value={materia.id_uc}
                    checked={materiasSeleccionadas.includes(materia.id_uc)}
                    id={`materia-${materia.id_uc}`}
                    onChange={() => handleCheckboxChange(materia.id_uc)}
                  />
                  <label className="form-check-label" htmlFor={`materia-${materia.id_uc}`}>
                    {materia.unidad_curricular}
                  </label>
                </div>
              ))}
            </div>

            <button type="submit" className="btn btn-primary mt-3">
              {isSubmitting ? 'Actualizando...' : 'Actualizar grado'}
            </button>
            <br />
            <br />
            <button
              type="button"
              className="btn btn-danger"
              onClick={() => navigate(`${routes.base}/${routes.comisiones.main}`)}
            >
              Volver Atrás
            </button>
          </form>

          {Object.keys(errors).length > 0 && (
            <div className="alert alert-danger mt-3">
              <ul>
                {Object.values(errors).map((error, index) => (
                  <li key={index}>{error}</li>
                ))}
              </ul>
            </div>
          )}
        </div>
      </div>

      {/* Modal de confirmación */}
      <Modal show={showModal} onHide={handleCancelUpdate}>
        <Modal.Header closeButton>
          <Modal.Title>Confirmar actualización</Modal.Title>
        </Modal.Header>
        <Modal.Body>
          <label htmlFor="detalles">Detalles:</label>
          <textarea
            name="detalles"
            value={detalles}
            onChange={(e) => setDetalles(e.target.value)}
            required
            className="form-control"
          />
        </Modal.Body>
        <Modal.Footer>
          <Button variant="secondary" onClick={handleCancelUpdate}>
            Cancelar
          </Button>
          <Button variant="primary" onClick={handleConfirmUpdate}>
            Confirmar
          </Button>
        </Modal.Footer>
      </Modal>
    </div>
  );
};

export default ActualizarComision;
