import React, { useState, useEffect } from 'react';
import { useNavigate, useOutletContext, useParams } from 'react-router-dom';
import { Modal, Button } from 'react-bootstrap';
import { useNotification } from '../layouts/parcials/notification';

const ActualizarAula = () => {
  const usuario = sessionStorage.getItem('userType');
  const [showModal, setShowModal] = useState(false);
  const [isSubmitting, setIsSubmitting] = useState(false);
  const [detalles, setDetalles] = useState('');

  const [nombre, setNombre] = useState('');
  const [tipoAula, setTipoAula] = useState('');
  const [capacidad, setCapacidad] = useState('');
  const navigate = useNavigate();
  const { routes } = useOutletContext();
  const { aulaId } = useParams();

  const { addNotification } = useNotification();

  // Cargar los datos del aula para su visualización
  useEffect(() => {
    const fetchAula = async () => {
      try {
        const response = await fetch(`http://127.0.0.1:8000/api/horarios/aulas/${aulaId}`);
        const data = await response.json();
        console.log(data);
        if (response.ok) {
          setNombre(data.nombre);
          setTipoAula(data.tipo_aula);
          setCapacidad(data.capacidad);
        } else {
          console.error('Error al obtener los datos del aula');
        }
      } catch (error) {
        console.error('Error de red al obtener el aula:', error);
      }
    };

    fetchAula();
  }, [aulaId]);

  const handleSubmit = (e) => {
    e.preventDefault();
    setShowModal(true);
  };

  const handleConfirmUpdate = async () => {
    setIsSubmitting(true);

    try {
      const response = await fetch(
        `http://127.0.0.1:8000/api/horarios/aulas/actualizar/${aulaId}`,
        {
          method: 'PUT',
          headers: {
            'Content-Type': 'application/json'
          },
          body: JSON.stringify({ nombre, tipo_aula: tipoAula, capacidad, detalles, usuario })
        }
      );

      if (response.ok) {
        navigate(`${routes.base}/${routes.aulas.main}`, {
          state: { successMessage: 'Aula actualizada con éxito', updated: true }
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
            <label htmlFor="nombre">Ingrese el nombre</label>
            <br />
            <input
              type="text"
              name="nombre"
              value={nombre} // Vincular el estado del nombre al input
              onChange={(e) => setNombre(e.target.value)}
            />
            <br />
            <br />
            <label htmlFor="tipo_aula">Ingrese el tipo de aula</label>
            <br />
            <input
              type="text"
              name="tipo_aula"
              value={tipoAula} // Vincular el estado del tipo de aula al input
              onChange={(e) => setTipoAula(e.target.value)}
            />
            <br />
            <br />
            <label htmlFor="capacidad">Ingrese la capacidad</label>
            <br />
            <input
              type="number"
              name="capacidad"
              value={capacidad} // Vincular el estado de capacidad al input
              onChange={(e) => setCapacidad(e.target.value)}
            />
            <br />
            <br />
            <button type="submit" className="btn btn-primary mt-3">
              {isSubmitting ? 'Actualizando...' : 'Actualizar aula'}
            </button>
            <br />
            <br />
            <button
              type="button"
              className="btn btn-danger"
              onClick={() => navigate(`${routes.base}/${routes.aulas.main}`)}
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

export default ActualizarAula;
