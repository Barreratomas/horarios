import React, { useState, useEffect } from 'react';
import { useNavigate, useParams, useOutletContext } from 'react-router-dom';
import { Modal, Button } from 'react-bootstrap';
import { useNotification } from '../layouts/parcials/notification';

const ActualizarPlan = () => {
  const usuario = sessionStorage.getItem('userType');
  const [showModal, setShowModal] = useState(false); // Estado para controlar el modal
  const [isSubmitting, setIsSubmitting] = useState(false); // Estado para controlar el envío del formulario
  const [detalles, setDetalles] = useState('');

  const [detalle, setDetalle] = useState('');
  const [fechaInicio, setFechaInicio] = useState('');
  const [fechaFin, setFechaFin] = useState('');
  const [materias, setMaterias] = useState([]);
  const [selectedMaterias, setSelectedMaterias] = useState([]);
  const [carreras, setCarreras] = useState([]);
  const [selectedCarrera, setSelectedCarrera] = useState('');

  const { addNotification } = useNotification();

  const navigate = useNavigate();
  const { routes } = useOutletContext();
  const { planId } = useParams(); // Obtener el ID del plan desde la URL

  // Obtener el plan de estudio actual para editarlo
  useEffect(() => {
    const fetchPlan = async () => {
      try {
        const response = await fetch(
          `http://127.0.0.1:8000/api/horarios/planEstudio/relaciones/${planId}`,
          {
            headers: { Accept: 'application/json' }
          }
        );
        if (!response.ok) throw new Error('Error al obtener plan de estudio');
        const data = await response.json();
        console.log(data);
        setDetalle(data.detalle);
        setFechaInicio(data.fecha_inicio);
        setFechaFin(data.fecha_fin);
        setSelectedCarrera(data.carreras[0]?.id_carrera || '');
        setSelectedMaterias(data.unidades_curriculares.map((materia) => materia.id_uc));
      } catch (error) {
        addNotification(`Error de conexión`, 'danger');
      }
    };

    // Obtener materias
    const fetchMaterias = async () => {
      try {
        const response = await fetch('http://127.0.0.1:8000/api/horarios/unidadCurricular', {
          headers: { Accept: 'application/json' }
        });
        if (!response.ok) throw new Error('Error al obtener materias');
        const data = await response.json();
        setMaterias(data);
      } catch (error) {
        addNotification(`Error de conexión`, 'danger');
      }
    };

    // Obtener carreras
    const fetchCarreras = async () => {
      try {
        const response = await fetch('http://127.0.0.1:8000/api/horarios/carreras', {
          headers: { Accept: 'application/json' }
        });
        if (!response.ok) throw new Error('Error al obtener carreras');
        const data = await response.json();
        setCarreras(data);
      } catch (error) {
        addNotification(`Error de conexión`, 'danger');
      }
    };

    fetchPlan();
    fetchMaterias();
    fetchCarreras();
  }, [planId]);

  // Manejar selección de una materia en el checkbox
  const handleCheckboxChange = (materiaId) => {
    if (selectedMaterias.includes(materiaId)) {
      setSelectedMaterias((prev) => prev.filter((id) => id !== materiaId));
    } else {
      setSelectedMaterias((prev) => [...prev, materiaId]);
    }
  };

  const handleSubmit = (e) => {
    e.preventDefault();
    setShowModal(true); // Mostrar el modal de confirmación
  };

  const handleConfirmUpdate = async () => {
    setIsSubmitting(true);

    try {
      const response = await fetch(
        `http://127.0.0.1:8000/api/horarios/planEstudio/actualizar/${planId}`,
        {
          method: 'PUT',
          headers: {
            'Content-Type': 'application/json'
          },
          body: JSON.stringify({
            detalle,
            fecha_inicio: fechaInicio,
            fecha_fin: fechaFin,
            id_carrera: selectedCarrera,
            materias: selectedMaterias,
            detalles,
            usuario
          })
        }
      );

      if (response.ok) {
        navigate(`${routes.base}/${routes.planes.main}`, {
          state: { successMessage: 'Plan actualizado con éxito', updated: true }
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
      setIsSubmitting(false);
      setShowModal(false);
    }
  };
  // Cancelar la actualización
  const handleCancelUpdate = () => {
    setShowModal(false); // Cerrar el modal de confirmación sin hacer nada
  };
  return (
    <div className="container py-3">
      <div className="row align-items-center justify-content-center">
        <div className="col-6 text-center">
          <form onSubmit={handleSubmit}>
            <label htmlFor="detalle">Detalle</label>
            <br />
            <input
              type="text"
              name="detalle"
              value={detalle}
              onChange={(e) => setDetalle(e.target.value)}
              maxLength="50"
              required
            />
            <br />
            <br />

            <label htmlFor="fecha_inicio">Fecha de inicio</label>
            <br />
            <input
              type="date"
              name="fecha_inicio"
              value={fechaInicio}
              onChange={(e) => setFechaInicio(e.target.value)}
              required
            />
            <br />
            <br />

            <label htmlFor="fecha_fin">Fecha de fin</label>
            <br />
            <input
              type="date"
              name="fecha_fin"
              value={fechaFin}
              onChange={(e) => setFechaFin(e.target.value)}
            />
            <br />
            <br />

            <label htmlFor="carrera">Seleccione la carrera</label>
            <br />
            <select
              className="form-select"
              name="carrera"
              value={selectedCarrera}
              onChange={(e) => setSelectedCarrera(e.target.value)}
              required
            >
              <option value="">Seleccione una carrera...</option>
              {carreras.map((carrera) => (
                <option key={carrera.id_carrera} value={carrera.id_carrera}>
                  {carrera.carrera}
                </option>
              ))}
            </select>
            <br />

            <label>Seleccione las materias</label>
            <div className="materias-list">
              {materias.map((materia) => (
                <div key={materia.id_uc} className="form-check">
                  <input
                    className="form-check-input"
                    type="checkbox"
                    value={materia.id_uc}
                    id={`materia-${materia.id_uc}`}
                    checked={selectedMaterias.includes(materia.id_uc)}
                    onChange={() => handleCheckboxChange(materia.id_uc)}
                  />
                  <label className="form-check-label" htmlFor={`materia-${materia.id_uc}`}>
                    {materia.unidad_curricular}
                  </label>
                </div>
              ))}
            </div>

            <br />
            <button type="submit" className="btn btn-primary mt-3">
              {isSubmitting ? 'Actualizando...' : 'Actualizar plan'}
            </button>
            <br />
            <br />
            <button
              type="button"
              className="btn btn-danger"
              onClick={() => navigate(`${routes.base}/${routes.planes.main}`)}
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

export default ActualizarPlan;
