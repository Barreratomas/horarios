import React, { useState, useEffect } from 'react';
import { useNavigate, useOutletContext, useParams } from 'react-router-dom';
import { Modal, Button } from 'react-bootstrap';
import { useNotification } from '../layouts/parcials/notification';

const ActualizarAsignarAlumno = () => {
  const usuario = sessionStorage.getItem('userType');
  const [showModal, setShowModal] = useState(false);
  const [isSubmitting, setIsSubmitting] = useState(false);
  const [detalles, setDetalles] = useState('');
  const { alumnoId, idGradoActual } = useParams();
  const [grado, setGrado] = useState('');
  const [grados, setGrados] = useState([]);
  const navigate = useNavigate();
  const { routes } = useOutletContext();

  const { addNotification } = useNotification();

  // Obtener los grados disponibles según la carrera seleccionada
  useEffect(() => {
    const fetchGrados = async () => {
      try {
        const response = await fetch(
          `http://127.0.0.1:8000/api/horarios/carreraGrados/materias/${alumnoId}/`
        );
        const data = await response.json();
        if (data.error) {
          addNotification(data.error, 'danger');
        } else {
          console.log(data);
          setGrados(data);
        }
      } catch (error) {
        addNotification(`Error de conexión`, 'danger');
      }
    };

    fetchGrados();
  }, []);
  const handleSubmit = (e) => {
    e.preventDefault();
    setShowModal(true); // Mostrar el modal de confirmación
  };

  const handleConfirmUpdate = async () => {
    setIsSubmitting(true);

    try {
      const response = await fetch(
        `http://127.0.0.1:8000/api/horarios/alumnoGrados/actualizar/${alumnoId}/${idGradoActual}/${grado}`,
        {
          method: 'PUT',
          headers: {
            'Content-Type': 'application/json'
          },
          body: JSON.stringify({ detalles, usuario })
        }
      );

      const data = await response.json();
      console.log(data);
      if (data.error) {
        addNotification(data.error, 'danger');
      } else {
        navigate(`${routes.base}/${routes.asignacionesAlumno.main}`, {
          state: { successMessage: 'El grado del alumno fue actualizado con éxito', updated: true }
        });
      }
    } catch (error) {
      addNotification(`Error de conexión`, 'danger');
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
            {/* Selector de Grado, solo se muestra después de seleccionar una carrera */}
            <>
              <label htmlFor="grado">Seleccione el grado</label>
              <br />
              <select
                className="form-select"
                name="grado"
                value={grado}
                onChange={(e) => setGrado(e.target.value)}
                required
              >
                <option value="">Seleccione un grado</option>
                {grados.map((g) => (
                  <option key={g.id_carrera_grado} value={g.id_carrera_grado}>
                    {`Grado:${g.grado} división:${g.division} (Capacidad: ${g.capacidad})`}
                  </option>
                ))}
              </select>

              <br />
              <br />
            </>

            <button type="submit" className="btn btn-primary mt-3" disabled={!grado}>
              {isSubmitting ? 'Actualizando...' : 'Actualizar asignación'}
            </button>
            <br />
            <br />
            <button
              type="button"
              className="btn btn-danger"
              onClick={() => navigate(`${routes.base}/${routes.asignacionesAlumno.main}`)}
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

export default ActualizarAsignarAlumno;
