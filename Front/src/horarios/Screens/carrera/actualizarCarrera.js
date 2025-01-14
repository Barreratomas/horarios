import React, { useState, useEffect } from 'react';
import { useNavigate, useOutletContext, useParams } from 'react-router-dom';
import { Modal, Button } from 'react-bootstrap';

const ActualizarCarrera = () => {
  const usuario = sessionStorage.getItem('userType');
  const [showModal, setShowModal] = useState(false); // Estado para controlar el modal
  const [isSubmitting, setIsSubmitting] = useState(false); // Estado para controlar el envío del formulario
  const [detalles, setDetalles] = useState('');

  const [carrera, setCarrera] = useState('');
  const [cupo, setCupo] = useState('');
  const [errors, setErrors] = useState({});
  const navigate = useNavigate();
  const { routes } = useOutletContext();
  const { carreraId } = useParams(); // Obtener el ID de la carrera desde la URL

  // Obtener los datos de la carrera existente
  useEffect(() => {
    const fetchCarrera = async () => {
      try {
        const response = await fetch(`http://127.0.0.1:8000/api/horarios/carreras/${carreraId}`);
        const data = await response.json();

        if (response.ok) {
          setCarrera(data.carrera);
          setCupo(data.cupo);
        } else {
          console.error('Error al obtener los datos de la carrera:', data);
        }
      } catch (error) {
        console.error('Error:', error);
      }
    };

    fetchCarrera();
  }, [carreraId]);
  const handleSubmit = (e) => {
    e.preventDefault();
    setShowModal(true); // Mostrar el modal de confirmación
  };
  const handleConfirmUpdate = async () => {
    setIsSubmitting(true);

    try {
      const response = await fetch(
        `http://127.0.0.1:8000/api/horarios/carreras/actualizar/${carreraId}`,
        {
          method: 'PUT',
          headers: {
            'Content-Type': 'application/json'
          },
          body: JSON.stringify({ carrera, cupo, usuario, detalles })
        }
      );

      if (response.ok) {
        navigate(`${routes.base}/${routes.carreras.main}`, {
          state: { successMessage: 'Carrera actualizada con éxito' }
        });
      } else {
        const data = await response.json();
        if (data.errors) {
          setErrors(data.errors); // Manejar errores de validación
        }
      }
    } catch (error) {
      console.error('Error actualizando carrera:', error);
    } finally {
      setIsSubmitting(false); // Finalizar el proceso de envío
      setShowModal(false); // Cerrar el modal de confirmación
    }
  };
  const handleCancelUpdate = () => {
    setShowModal(false); // Cerrar el modal de confirmación sin hacer nada
  };

  return (
    <div className="container py-3">
      <div className="row align-items-center justify-content-center">
        <div className="col-6 text-center">
          <form onSubmit={handleSubmit}>
            <label htmlFor="nombre">Ingrese el nombre de la carrera</label>
            <br />
            <input
              type="text"
              name="carrera"
              value={carrera}
              onChange={(e) => setCarrera(e.target.value)}
            />
            <br />
            {errors.carrera && <div className="text-danger">{errors.carrera}</div>}
            <br />
            <label htmlFor="cupo">Ingrese el cupo</label>
            <br />
            <input
              type="number"
              name="cupo"
              value={cupo}
              onChange={(e) => setCupo(e.target.value)}
            />
            <br />
            {errors.cupo && <div className="text-danger">{errors.cupo}</div>}
            <br />
            <button type="submit" className="btn btn-primary mt-3">
              {isSubmitting ? 'Actualizando...' : 'Actualizar carrera'}
            </button>
            <br />
            <br />
            <button
              type="button"
              className="btn btn-danger"
              onClick={() => navigate(`${routes.base}/${routes.carreras.main}`)}
            >
              Volver Atrás
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

export default ActualizarCarrera;
