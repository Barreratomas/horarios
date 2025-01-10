import React, { useState, useEffect } from 'react';
import { useNavigate, useOutletContext, useParams } from 'react-router-dom';
import { Modal, Button } from 'react-bootstrap';

const ActualizarAsignarAlumno = () => {
  const usuario = sessionStorage.getItem('userType');
  const [showModal, setShowModal] = useState(false);
  const [isSubmitting, setIsSubmitting] = useState(false);
  const [detalles, setDetalles] = useState('');

  const { alumnoId, idGradoActual } = useParams();
  const [carrera, setCarrera] = useState('');
  const [grado, setGrado] = useState('');
  const [carreras, setCarreras] = useState([]);
  const [grados, setGrados] = useState([]);
  const [errors, setErrors] = useState({});
  const navigate = useNavigate();
  const { routes } = useOutletContext();

  // Obtener las carreras disponibles
  useEffect(() => {
    const fetchCarreras = async () => {
      try {
        const response = await fetch('http://127.0.0.1:8000/api/horarios/carreras');
        if (!response.ok) {
          throw new Error('Error al obtener carreras');
        }
        const carrerasData = await response.json();
        setCarreras(carrerasData);
      } catch (error) {
        console.error('Error al obtener carreras:', error);
      }
    };

    fetchCarreras();
  }, []);

  // Obtener los grados disponibles según la carrera seleccionada
  useEffect(() => {
    if (carrera) {
      const fetchGrados = async () => {
        try {
          const response = await fetch(
            `http://127.0.0.1:8000/api/horarios/carreraGrados/carrera/SinUC/${carrera}`
          );
          if (!response.ok) {
            throw new Error('Error al obtener grados');
          }
          const gradosData = await response.json();
          console.log(gradosData);
          setGrados(gradosData);
        } catch (error) {
          console.error('Error al obtener grados:', error);
        }
      };

      fetchGrados();
    }
  }, [carrera]);
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

      if (response.ok) {
        navigate(`${routes.base}/${routes.asignacionesAlumno.main}`, {
          state: { successMessage: 'Alumno actualizado con éxito' }
        });
      } else {
        const data = await response.json();
        if (data.errors) {
          setErrors(data.errors); // Mostrar errores de validación si los hay
        }
      }
    } catch (error) {
      console.error('Error al actualizar el alumno:', error);
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
            {/* Selector de Carrera */}
            <label htmlFor="carrera">Seleccione la carrera</label>
            <br />
            <select
              className="form-select"
              name="carrera"
              value={carrera}
              onChange={(e) => setCarrera(e.target.value)}
              required
            >
              <option value="">Seleccione una carrera</option>
              {carreras.map((c) => (
                <option key={c.id_carrera} value={c.id_carrera}>
                  {c.carrera} {/* Muestra el nombre de la carrera */}
                </option>
              ))}
            </select>
            <br />
            {errors.carrera && <div className="text-danger">{errors.carrera}</div>}
            <br />

            {/* Selector de Grado, solo se muestra después de seleccionar una carrera */}
            {carrera && (
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
                      {`${g.grado.detalle} (Capacidad: ${g.grado.capacidad})`}
                    </option>
                  ))}
                </select>

                <br />
                {errors.grado && <div className="text-danger">{errors.grado}</div>}
                <br />
              </>
            )}

            <button type="submit" className="btn btn-primary mt-3">
              {isSubmitting ? 'Actualizando...' : 'Actualizar asignación'}
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

export default ActualizarAsignarAlumno;
