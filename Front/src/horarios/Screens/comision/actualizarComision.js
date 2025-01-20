import React, { useState, useEffect } from 'react';
import { useNavigate, useOutletContext, useParams } from 'react-router-dom';
import { Modal, Button } from 'react-bootstrap';
import { useNotification } from '../layouts/parcials/notification';

const ActualizarComision = () => {
  const usuario = sessionStorage.getItem('userType');
  const { comisionId } = useParams();
  const [capacidad, setCapacidad] = useState('');
  const [materias, setMaterias] = useState([]);
  const [carrera, setCarrera] = useState([]);

  const [materiasSeleccionadas, setMateriasSeleccionadas] = useState([]);
  const [detalles, setDetalles] = useState('');

  const [isLoading, setIsLoading] = useState(true);
  const [showModal, setShowModal] = useState(false);
  const [isSubmitting, setIsSubmitting] = useState(false);

  const navigate = useNavigate();
  const { routes } = useOutletContext();

  const { addNotification } = useNotification();

  // Obtener los datos de la comisión existente
  useEffect(() => {
    const fetchComision = async () => {
      try {
        const response = await fetch(
          `http://127.0.0.1:8000/api/horarios/carreraGrados/${comisionId}`
        );
        const data = await response.json();
        if (response.ok) {
          setCapacidad(data.capacidad);
          setMateriasSeleccionadas(data.materias || []);
          setCarrera(data.id_carrera);
        } else {
          addNotification(data.errors, 'danger');
        }
      } catch (error) {
        addNotification(`Error de conexión`, 'danger');
      }
    };

    fetchComision();
  }, [comisionId]);

  // Cargar materias disponibles
  useEffect(() => {
    const fetchMaterias = async () => {
      console.log('id de la carrera', carrera);
      if (!carrera) {
        console.log('Carrera aún no está disponible o no tiene id_carrera. Esperando...');
        return; // Esperar hasta que `carrera` tenga un valor válido
      }

      try {
        // Obtener las materias asociadas al grado
        const gradoUCResponse = await fetch(
          `http://127.0.0.1:8000/api/horarios/gradoUC/idGrado/relaciones/${comisionId}`
        );
        const gradoUCData = await gradoUCResponse.json();

        if (!gradoUCResponse.ok || !Array.isArray(gradoUCData)) {
          console.log('No hay materias asociadas ');
          setMateriasSeleccionadas([]); // Dejar materias seleccionadas vacías
        } else {
          // Extraer IDs de las materias asociadas al grado
          const materiasAsociadas = gradoUCData.map((item) => item.unidad_curricular.id_uc);
          setMateriasSeleccionadas(materiasAsociadas);
        }

        // Obtener todas las materias disponibles
        console.log('id de la carrera arriba', carrera);

        const materiasResponse = await fetch(
          `http://127.0.0.1:8000/api/horarios/uCPlan/${carrera}/relaciones`
        );
        console.log(materiasResponse);

        const materiasData = await materiasResponse.json();

        if (!materiasResponse.ok) {
          throw new Error('Error al cargar las materias disponibles.');
        }
        console.log('sin formato ', materiasData);

        const materiasFormateadas = materiasData
          .map((plan) =>
            plan.plan_estudio.uc_plan.map((uc) => ({
              id_uc: uc.id_uc,
              unidad_curricular: uc.unidad_curricular.unidad_curricular
            }))
          )
          .flat(); // Para aplanar el arreglo resultante de arrays dentro de arrays.

        console.log('formaterasd ', materiasFormateadas);

        setMaterias(materiasFormateadas);
        setIsLoading(false);
      } catch (error) {
        console.log(error.message || 'Error de conexión.', 'danger');
      }
    };

    fetchMaterias();
  }, [carrera, comisionId]);

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
          state: { successMessage: 'Grado actualizado con éxito', updated: true }
        });
      } else {
        const data = await response.json();
        if (data.errors) {
          addNotification(data.errors, 'danger');
        }
      }
    } catch (error) {
      addNotification(`Error de conexión`, 'danger');
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
              {materias.map((materia, index) => {
                return (
                  <div key={`${materia.id_uc}-${index}`} className="form-check">
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
                );
              })}
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
        </div>
      </div>

      {/* Modal de confirmación */}
      <Modal show={showModal} onHide={handleCancelUpdate}>
        <Modal.Header closeButton>
          <Modal.Title>Confirmar actualización</Modal.Title>
        </Modal.Header>
        <Modal.Body>
          <label htmlFor="detalles">Por favor, ingrese el motivo de actualización:</label>
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
