import React, { useState, useEffect } from 'react';
import { useNavigate, useOutletContext, useParams } from 'react-router-dom';
import { Modal, Button } from 'react-bootstrap';
import { useNotification } from '../layouts/parcials/notification';
const ActualizarMateria = () => {
  const usuario = sessionStorage.getItem('userType');
  console.log(usuario);
  const [showModal, setShowModal] = useState(false);
  const [isSubmitting, setIsSubmitting] = useState(false);
  const [detalles, setDetalles] = useState('');

  const { materiaId } = useParams();
  const [unidadCurricular, setUnidadCurricular] = useState('');
  const [tipo, setTipo] = useState('');
  const [horasSem, setHorasSem] = useState('');
  const [horasAnual, setHorasAnual] = useState('');
  const [formato, setFormato] = useState('');
  const navigate = useNavigate();
  const { routes } = useOutletContext();
  const { addNotification } = useNotification();

  // Obtener los datos de la materia por ID
  useEffect(() => {
    const obtenerMateria = async () => {
      try {
        const response = await fetch(
          `http://127.0.0.1:8000/api/horarios/unidadCurricular/${materiaId}`
        );
        const data = await response.json();

        if (response.ok) {
          setUnidadCurricular(data.unidad_curricular);
          setTipo(data.tipo);
          setHorasSem(data.horas_sem);
          setHorasAnual(data.horas_anual);
          setFormato(data.formato);
        } else {
          console.error('Error al obtener los datos de la materia:');
        }
      } catch (error) {
        console.error('Error de red al obtener la materia:', error);
      }
    };

    obtenerMateria();
  }, [materiaId]);

  const handleSubmit = (e) => {
    e.preventDefault();
    setShowModal(true);
  };

  const handleConfirmUpdate = async () => {
    setIsSubmitting(true);

    try {
      const response = await fetch(
        `http://127.0.0.1:8000/api/horarios/unidadCurricular/actualizar/${materiaId}`,
        {
          method: 'PUT',
          headers: {
            'Content-Type': 'application/json'
          },
          body: JSON.stringify({
            unidad_curricular: unidadCurricular,
            tipo,
            horas_sem: horasSem,
            horas_anual: horasAnual,
            formato,
            usuario,
            detalles
          })
        }
      );
      const data = await response.json();
      if (data.error) {
        addNotification(data.error, 'danger');
      } else {
        navigate(`${routes.base}/${routes.materias.main}`, {
          state: { successMessage: 'Materia actualizada con éxito', updated: true }
        });
      }
    } catch (error) {
      addNotification(`Error de conexión`, 'danger');
    } finally {
      setIsSubmitting(false);
      setShowModal(false);
    }
  };

  const handleCancelUpdate = () => {
    setShowModal(false);
  };

  return (
    <div className="container py-3">
      <div className="row align-items-center justify-content-center">
        <div className="col-6 text-center">
          <form onSubmit={handleSubmit}>
            <label htmlFor="unidadCurricular">Unidad Curricular</label>
            <br />
            <input
              type="text"
              name="unidadCurricular"
              value={unidadCurricular}
              onChange={(e) => setUnidadCurricular(e.target.value)}
              maxLength="60"
            />

            <br />
            <br />

            <label htmlFor="tipo">Tipo</label>
            <br />
            <input
              type="text"
              name="tipo"
              value={tipo}
              onChange={(e) => setTipo(e.target.value)}
              maxLength="20"
            />
            <br />
            <br />

            <label htmlFor="horasSem">Horas Semanales</label>
            <br />
            <input
              type="number"
              name="horasSem"
              value={horasSem}
              onChange={(e) => setHorasSem(e.target.value)}
            />
            <br />
            <br />

            <label htmlFor="horasAnual">Horas Anuales</label>
            <br />
            <input
              type="number"
              name="horasAnual"
              value={horasAnual}
              onChange={(e) => setHorasAnual(e.target.value)}
            />
            <br />
            <br />

            <label htmlFor="formato">Formato</label>
            <br />
            <input
              type="text"
              name="formato"
              value={formato}
              onChange={(e) => setFormato(e.target.value)}
            />
            <br />
            <br />

            <button type="submit" className="btn btn-primary mt-3">
              {isSubmitting ? 'Actualizando...' : 'Actualizar materia'}
            </button>
            <br />
            <br />
            <button
              type="button"
              className="btn btn-danger"
              onClick={() => navigate(`${routes.base}/${routes.materias.main}`)}
            >
              Volver Atrás
            </button>
          </form>
        </div>
      </div>

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

export default ActualizarMateria;
