import React, { useState, useEffect } from 'react';
import { useNavigate, useOutletContext, useLocation } from 'react-router-dom';
import { Modal, Button } from 'react-bootstrap';

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
  const [successMessage, setSuccessMessage] = useState('');
  const [hideMessage, setHideMessage] = useState(false);
  const [errors, setErrors] = useState([]);

  useEffect(() => {
    if (location.state && location.state.successMessage) {
      setSuccessMessage(location.state.successMessage);

      // Mostrar el mensaje durante 3 segundos
      setTimeout(() => {
        setHideMessage(true); // Ocultar con la clase CSS
      }, 3000);

      // Limpiar después de la transición
      setTimeout(() => {
        setSuccessMessage('');
        setHideMessage(false);

        navigate(location.pathname, { replace: true }); // Reemplaza la entrada en el historial para no tener el state
      }, 3500);
    }

    const fetchCarreras = async () => {
      try {
        const response = await fetch('http://127.0.0.1:8000/api/horarios/carreras', {
          headers: { Accept: 'application/json' }
        });

        if (!response.ok) throw new Error(' ');

        const jsonResponse = await response.json();
        if (jsonResponse) {
          setCarreras(jsonResponse);
          setServerUp(true);
        } else {
          alert('Servidor fuera de servicio...');
        }
      } catch (error) {
        console.error('Error checking server status:', error);
        alert('Error al verificar el servidor...');
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

      setCarreras(carreras.filter((carrera) => carrera.id_carrera !== carreraToDelete));
      setSuccessMessage('Carrera eliminada con éxito');

      setTimeout(() => setHideMessage(true), 3000);
      setTimeout(() => {
        setSuccessMessage('');
        setHideMessage(false);
      }, 3500);
      setShowModal(false); // Cerrar el modal
    } catch (error) {
      setErrors([error.message || 'Error al eliminar carrera']);
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
        <h1>Este módulo no está disponible en este momento</h1>
      )}
      {/* Modal de confirmación */}
      <Modal show={showModal} onHide={() => setShowModal(false)}>
        <Modal.Header closeButton>
          <Modal.Title>Confirmar eliminación</Modal.Title>
        </Modal.Header>
        <Modal.Body>
          <p>¿Estás seguro de que quieres eliminar esta carrera?</p>
          <div className="form-group">
            <label htmlFor="detalles">Detalles:</label>
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
      <div id="messages-container" className={`container ${hideMessage ? 'hide-messages' : ''}`}>
        {errors.length > 0 && (
          <div className="alert alert-danger">
            <ul>
              {errors.map((error, index) => (
                <li key={index}>{error}</li>
              ))}
            </ul>
          </div>
        )}
        {successMessage && <div className="alert alert-success">{successMessage}</div>}
      </div>
    </div>
  );
};

export default Carreras;
