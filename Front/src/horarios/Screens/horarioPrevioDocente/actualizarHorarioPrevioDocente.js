import React, { useState, useEffect } from 'react';
import { useNavigate, useParams, useOutletContext } from 'react-router-dom';
import { Modal, Button } from 'react-bootstrap';

const ActualizarHorarioPrevio = () => {
  const usuario = sessionStorage.getItem('userType');
  const navigate = useNavigate();
  const { hpdId } = useParams();
  const { routes } = useOutletContext();

  const [dia, setDia] = useState(''); // Usar un solo valor para el día
  const [hora, setHora] = useState(''); // Usar un solo valor para la hora
  const [detalles, setDetalles] = useState('');
  const [errors, setErrors] = useState([]);
  const [fetchError, setFetchError] = useState('');
  const [successMessage, setSuccessMessage] = useState('');
  const [showModal, setShowModal] = useState(false);
  const [isSubmitting, setIsSubmitting] = useState(false);

  useEffect(() => {
    const fetchHorario = async () => {
      try {
        const response = await fetch(
          `http://127.0.0.1:8000/api/horarios/horariosPreviosDocentes/${hpdId}`
        );
        if (!response.ok) throw new Error('Error al obtener el horario');

        const data = await response.json();
        console.log(data);

        // Verificar si data es un objeto con las propiedades dia y hora
        if (data.dia && data.hora) {
          setDia(data.dia);
          setHora(data.hora);
        } else {
          setDia('');
          setHora('');
        }
      } catch (error) {
        setFetchError(error.message || 'No se pudo cargar el horario');
        setErrors([]);
      }
    };

    fetchHorario();
  }, [hpdId]);

  const handleSubmit = (e) => {
    e.preventDefault();
    setShowModal(true); // Mostrar el modal de confirmación
  };

  const handleConfirmUpdate = async () => {
    console.log(detalles);
    setIsSubmitting(true);
    try {
      const response = await fetch(
        `http://127.0.0.1:8000/api/horarios/horariosPreviosDocentes/actualizar/${hpdId}`,
        {
          method: 'PUT',
          headers: {
            'Content-Type': 'application/json'
          },
          body: JSON.stringify({
            dia,
            hora,
            detalles,
            usuario
          })
        }
      );

      if (response.ok) {
        setSuccessMessage('Horario actualizado correctamente');
        setTimeout(() => navigate(`${routes.base}/${routes.horariosPreviosDocente.main}`), 3000);
      } else {
        const data = await response.json();
        if (data.errors) {
          setErrors(data.errors);
        }
      }
    } catch (error) {
      console.error('Error actualizando el horario:', error);
      setErrors(['Hubo un error al intentar actualizar el horario.']);
    } finally {
      setIsSubmitting(false);
      setShowModal(false);
    }
  };

  // Cancelar la actualización
  const handleCancelUpdate = () => {
    setShowModal(false); // Cerrar el modal sin hacer nada
  };

  return (
    <div className="container py-3">
      <div className="row align-items-center justify-content-center">
        <div className="col-6 text-center">
          <form onSubmit={handleSubmit}>
            {fetchError && <div className="alert alert-danger">{fetchError}</div>}

            <div className="mb-3">
              <label htmlFor="dia">Seleccione el día</label>
              <select
                className="form-select"
                id="dia"
                name="dia"
                value={dia}
                onChange={(e) => setDia(e.target.value)}
                required
              >
                <option value="">Seleccione un día</option>
                <option value="lunes">Lunes</option>
                <option value="martes">Martes</option>
                <option value="miercoles">Miércoles</option>
                <option value="jueves">Jueves</option>
                <option value="viernes">Viernes</option>
              </select>
            </div>
            <div className="mb-3">
              <label htmlFor="hora">Seleccione la hora</label>
              <input
                type="time"
                className="form-control"
                id="hora"
                name="hora"
                value={hora}
                onChange={(e) => setHora(e.target.value)}
                required
                min="18:50"
                max="22:30"
              />
            </div>

            <button type="submit" className="btn btn-primary mt-3">
              {isSubmitting ? 'Actualizando...' : 'Actualizar horario previo'}
            </button>
          </form>

          {errors.length > 0 && (
            <div className="alert alert-danger mt-3">
              <ul>
                {errors.map((error, index) => (
                  <li key={index}>{error}</li>
                ))}
              </ul>
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

          {successMessage && <div className="alert alert-success mt-3">{successMessage}</div>}
        </div>
      </div>
    </div>
  );
};

export default ActualizarHorarioPrevio;
