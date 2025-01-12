import React, { useState, useEffect } from 'react';
import { useNavigate, useOutletContext, useParams } from 'react-router-dom';
import { Modal, Button, Spinner } from 'react-bootstrap';

const ActualizarMateria = () => {
  const usuario = sessionStorage.getItem('userType');
  const [showModal, setShowModal] = useState(false);
  const [isSubmitting, setIsSubmitting] = useState(false);
  const [detalles, setDetalles] = useState('');
  const [loading, setLoading] = useState(true); // Estado para controlar si se están cargando los datos

  const { materiaId } = useParams();
  const [unidadCurricular, setUnidadCurricular] = useState('');
  const [tipo, setTipo] = useState('');
  const [horasSem, setHorasSem] = useState('');
  const [horasAnual, setHorasAnual] = useState('');
  const [formato, setFormato] = useState('');
  const [errors, setErrors] = useState({});
  const navigate = useNavigate();
  const { routes } = useOutletContext();

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
          console.error('Error al obtener la materia:', data);
        }
      } catch (error) {
        console.error('Error:', error);
      } finally {
        setLoading(false); // Desactivar el estado de carga
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

      if (response.ok) {
        navigate(`${routes.base}/${routes.materias.main}`, {
          state: { successMessage: 'Materia actualizada con éxito' }
        });
      } else {
        const data = await response.json();
        if (data.errors) {
          setErrors(data.errors);
        }
      }
    } catch (error) {
      console.error('Error al actualizar la materia:', error);
    } finally {
      setIsSubmitting(false);
      setShowModal(false);
    }
  };

  const handleCancelUpdate = () => {
    setShowModal(false);
  };

  if (loading) {
    return (
      <div className="d-flex justify-content-center align-items-center" style={{ height: '100vh' }}>
        <Spinner animation="border" variant="primary" />
      </div>
    );
  }

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
            {errors.unidad_curricular && (
              <div className="text-danger">{errors.unidad_curricular}</div>
            )}
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
            {errors.tipo && <div className="text-danger">{errors.tipo}</div>}
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
            {errors.horas_sem && <div className="text-danger">{errors.horas_sem}</div>}
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
            {errors.horas_anual && <div className="text-danger">{errors.horas_anual}</div>}
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
            {errors.formato && <div className="text-danger">{errors.formato}</div>}
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

export default ActualizarMateria;
