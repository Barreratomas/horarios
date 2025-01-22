import React, { useState, useEffect } from 'react';
import { useNavigate, useOutletContext, useLocation } from 'react-router-dom';
import { Modal, Button } from 'react-bootstrap';
import { useNotification } from '../layouts/parcials/notification';
import ErrorPage from '../layouts/parcials/errorPage';
const Carreras = () => {
  const [detalles, setDetalles] = useState('');
  const usuario = sessionStorage.getItem('userType');
  const [showModal, setShowModal] = useState(false);
  const [carreraToDelete, setCarreraToDelete] = useState(null);

  const navigate = useNavigate();
  const { routes } = useOutletContext();
  const location = useLocation();

  const [carreras, setCarreras] = useState([]);
  const [loading, setLoading] = useState(true);
  const [serverUp, setServerUp] = useState(false);

  const { addNotification } = useNotification();

  useEffect(() => {
    if (location.state?.successMessage) {
      addNotification(location.state.successMessage, 'success');

      if (location.state.updated) {
        navigate(location.pathname, { replace: true, state: {} });
      }
    }

    const fetchCarreras = async () => {
      try {
        const response = await fetch('http://127.0.0.1:8000/api/horarios/carreras', {
          headers: { Accept: 'application/json' }
        });

        if (!response.ok) throw new Error('Error al obtener carreras ');

        const data = await response.json();

        setCarreras(data);
        setServerUp(true);
      } catch (error) {
        console.log('Error al obtener carreras:', error.message);
      } finally {
        setLoading(false);
      }
    };

    fetchCarreras();
  }, [location, navigate]);

  const handleDelete = async () => {
    try {
      const response = await fetch(
        `http://127.0.0.1:8000/api/horarios/carreras/eliminar/${carreraToDelete}`,

        {
          method: 'DELETE',
          body: JSON.stringify({ detalles: detalles, usuario }),
          headers: { 'Content-Type': 'application/json' }
        }
      );

      if (!response.ok) throw new Error('Error al eliminar carrera');
      const data = await response.json();

      setCarreras(carreras.filter((carrera) => carrera.id_carrera !== carreraToDelete));

      addNotification(data.message, 'success');

      setShowModal(false); // Cerrar el modal
    } catch (error) {
      addNotification(error.message, 'danger');
    }
  };

  return (
    <div className="container py-3">
      <div className="row align-items-center justify-content-center">
        <div className="col-6 text-center">
          <button
            className="btn btn-primary me-2"
            onClick={() => navigate(`${routes.base}/${routes.carreras.crear}`)}
          >
            Crear
          </button>
        </div>
      </div>

      {loading ? (
        <p>Cargando...</p>
      ) : serverUp ? (
        <div className="container">
          {carreras.map((carrera) => (
            <div
              key={carrera.id_carrera}
              style={{
                border: '1px solid #ccc',
                borderRadius: '5px',
                padding: '10px',
                marginBottom: '10px',
                width: '30vw'
              }}
            >
              <p>Carrera: {carrera.carrera}</p>
              <p>Cupo: {carrera.cupo} </p>

              <div className="botones">
                <button
                  className="btn btn-primary me-2"
                  onClick={() =>
                    navigate(`${routes.base}/${routes.carreras.actualizar(carrera.id_carrera)}`)
                  }
                >
                  Actualizar
                </button>

                <button
                  className="btn btn-danger"
                  onClick={() => {
                    setCarreraToDelete(carrera.id_carrera); // Establecer el grado a eliminar
                    setShowModal(true); // Mostrar el modal
                  }}
                >
                  Eliminar
                </button>
              </div>
            </div>
          ))}
        </div>
      ) : (
        <ErrorPage message="La seccion de carreras" statusCode={500} />
      )}
      {/* Modal de confirmación */}
      <Modal show={showModal} onHide={() => setShowModal(false)}>
        <Modal.Header closeButton>
          <Modal.Title>Confirmar eliminación</Modal.Title>
        </Modal.Header>
        <Modal.Body>
          <div className="form-group">
            <label htmlFor="detalles">Por favor, ingrese el motivo de eliminacion:</label>
            <textarea
              id="detalles"
              className="form-control"
              rows="3"
              value={detalles}
              onChange={(e) => setDetalles(e.target.value)}
            />
          </div>
        </Modal.Body>
        <Modal.Footer>
          <Button variant="secondary" onClick={() => setShowModal(false)}>
            Cancelar
          </Button>
          <Button variant="danger" onClick={handleDelete}>
            Eliminar
          </Button>
        </Modal.Footer>
      </Modal>
    </div>
  );
};

export default Carreras;
