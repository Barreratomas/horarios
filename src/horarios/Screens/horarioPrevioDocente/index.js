import React, { useState, useEffect } from 'react';
import { useNavigate, useOutletContext, useLocation } from 'react-router-dom';
import '../../css/acordeon.css';
import { Modal, Button } from 'react-bootstrap';

const HorarioPrevio = () => {
  const usuario = sessionStorage.getItem('userType');
  const [showModal, setShowModal] = useState(false);
  const [horarioToDelete, setHorarioToDelete] = useState(null);
  const [detalles, setDetalles] = useState('');

  const navigate = useNavigate();
  const { routes } = useOutletContext();
  const location = useLocation();

  const [horarios, setHorarios] = useState([]);
  const [filteredHorarios, setFilteredHorarios] = useState([]);
  const [loading, setLoading] = useState(true);
  const [errors, setErrors] = useState([]);
  const [successMessage, setSuccessMessage] = useState('');
  const [hideMessage, setHideMessage] = useState(false);

  useEffect(() => {
    if (location.state && location.state.successMessage) {
      setSuccessMessage(location.state.successMessage);
      setTimeout(() => setHideMessage(true), 3000);
      setTimeout(() => {
        setSuccessMessage('');
        setHideMessage(false);
        navigate(location.pathname, { replace: true });
      }, 3500);
    }

    const fetchHorarios = async () => {
      setLoading(true);
      try {
        const response = await fetch('http://127.0.0.1:8000/api/horarios/horariosPreviosDocentes', {
          headers: { Accept: 'application/json' }
        });
        if (!response.ok) throw new Error('Error al obtener los horarios previos');
        const data = await response.json();
        console.log(data);
        setHorarios(data);
        setFilteredHorarios(data);
      } catch (error) {
        setErrors([error.message]);
      } finally {
        setLoading(false);
      }
    };

    fetchHorarios();
  }, [location.state, navigate]);

  const handleDelete = async () => {
    try {
      const response = await fetch(
        `http://127.0.0.1:8000/api/horarios/horariosPreviosDocentes/eliminar/${horarioToDelete}`,
        {
          method: 'DELETE',
          body: JSON.stringify({ detalles: detalles, usuario }),
          headers: { 'Content-Type': 'application/json' }
        }
      );

      if (!response.ok) throw new Error('Error al eliminar el horario');
      setHorarios(horarios.filter((h) => h.id_h_p_d !== horarioToDelete));
      setFilteredHorarios(filteredHorarios.filter((h) => h.id_h_p_d !== horarioToDelete));
      setShowModal(false);
    } catch (error) {
      setErrors([error.message]);
    }
  };

  const handleSearch = (event) => {
    const { value } = event.target;
    const filtered = horarios.filter((horario) =>
      // Filtrar por el DNI del docente, si existe
      horario.docente?.[0]?.DNI.toString().includes(value)
    );
    setFilteredHorarios(filtered);
  };

  return (
    <>
      {loading ? (
        <p>Cargando...</p>
      ) : (
        <div className="container py-3">
          <div className="row align-items-center justify-content-center col-11">
            <div className="w-50 text-center  mx-1">
              <div className="mb-3">
                <input
                  type="text"
                  className="form-control mb-2 mb-md-0 me-md-2"
                  placeholder="Buscar por dni..."
                  name="detalle"
                  onChange={handleSearch}
                  style={{ flex: '0 0 50%' }}
                />
              </div>

              <button
                type="button"
                className="btn btn-primary me-2"
                onClick={() => navigate(`${routes.base}/${routes.horariosPreviosDocente.crear}`)}
              >
                Crear
              </button>
            </div>
          </div>

          <div className="container">
            {filteredHorarios.length > 0 ? (
              filteredHorarios.map(({ id_h_p_d, dia, hora, docente }) => (
                <div
                  key={id_h_p_d}
                  style={{
                    border: '1px solid #ccc',
                    borderRadius: '5px',
                    padding: '10px',
                    marginBottom: '10px',
                    width: '30vw'
                  }}
                >
                  <h6>
                    Horario de salida en otra institucion: {dia} - {hora}
                  </h6>
                  {docente && docente[0] && (
                    <p>
                      Docente : {docente[0].nombre} {docente[0].apellido}
                    </p>
                  )}
                  {docente && docente[0] && <p>Dni : {docente[0].DNI}</p>}
                  <div className="botones">
                    <button
                      type="button"
                      className="btn btn-primary me-2"
                      onClick={() =>
                        navigate(
                          `${routes.base}/${routes.horariosPreviosDocente.actualizarHorarioPrevio(
                            id_h_p_d
                          )}`
                        )
                      }
                    >
                      Actualizar
                    </button>
                    <button
                      className="btn btn-danger"
                      onClick={() => {
                        setHorarioToDelete(id_h_p_d);
                        setShowModal(true);
                      }}
                    >
                      Eliminar
                    </button>
                  </div>
                </div>
              ))
            ) : (
              <p>No se encontraron horarios previos.</p>
            )}
          </div>

          {/* Modal de confirmación */}
          <Modal show={showModal} onHide={() => setShowModal(false)}>
            <Modal.Header closeButton>
              <Modal.Title>Confirmar eliminación</Modal.Title>
            </Modal.Header>
            <Modal.Body>
              <p>¿Estás seguro de que quieres eliminar este horario previo?</p>
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

          <div
            id="messages-container"
            className={`container ${hideMessage ? 'hide-messages' : ''}`}
          >
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
      )}
    </>
  );
};

export default HorarioPrevio;
