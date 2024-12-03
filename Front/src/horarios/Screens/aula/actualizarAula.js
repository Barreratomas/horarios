import React, { useState } from 'react';
import { useNavigate, useOutletContext, useParams } from 'react-router-dom';
import { Modal, Button } from 'react-bootstrap';

const ActualizarAula = () => {
  const usuario = sessionStorage.getItem('userType');
  const [showModal, setShowModal] = useState(false); // Estado para controlar el modal
  const [isSubmitting, setIsSubmitting] = useState(false); // Estado para controlar el envío del formulario
  const [detalles, setDetalles] = useState('');

  const [nombre, setNombre] = useState('');
  const [tipoAula, setTipoAula] = useState('');
  const [capacidad, setCapacidad] = useState('');
  const [errors, setErrors] = useState({});
  const navigate = useNavigate();
  const { routes } = useOutletContext();
  const { aulaId } = useParams();

  const handleSubmit = (e) => {
    e.preventDefault();
    setShowModal(true); // Mostrar el modal de confirmación
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
          state: { successMessage: 'Aula actualizada con éxito' }
        });
      } else {
        const data = await response.json();
        if (data.errors) {
          setErrors(data.errors);
        }
      }
    } catch (error) {
      console.error('Error actualizando aula:', error);
    } finally {
      setIsSubmitting(false); // Finalizar el proceso de envío
      setShowModal(false); // Cerrar el modal de confirmación
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
            {errors.nombre && <div className="text-danger">{errors.nombre}</div>}
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
            {errors.tipo_aula && <div className="text-danger">{errors.tipo_aula}</div>}
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
            {errors.capacidad && <div className="text-danger">{errors.capacidad}</div>}
            <button type="submit" className="btn btn-primary mt-3">
              {isSubmitting ? 'Actualizando...' : 'Actualizar aula'}
            </button>
          </form>
        </div>
      </div>

      {Object.keys(errors).length > 0 && (
        <div className="container" style={{ width: '500px' }}>
          <div className="alert alert-danger">
            <ul>
              {Object.values(errors).map((error, index) => (
                <li key={index}>{error}</li>
              ))}
            </ul>
          </div>
        </div>
      )}
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

export default ActualizarAula;
