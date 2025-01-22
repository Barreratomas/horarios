import React, { useState, useEffect } from 'react';
import { useNavigate, useOutletContext, useLocation } from 'react-router-dom';
import { Modal, Button } from 'react-bootstrap';
import '../../css/acordeon.css';
import { useNotification } from '../layouts/parcials/notification';
import ErrorPage from '../layouts/parcials/errorPage';

const Accordion = ({ title, children }) => {
  const [isOpen, setIsOpen] = useState(false);

  const toggleAccordion = () => {
    setIsOpen(!isOpen);
  };

  return (
    <div className="accordion">
      <div className="accordion-header" onClick={toggleAccordion}>
        <h3>{title}</h3>
      </div>
      <div className={`accordion-body ${isOpen ? 'open' : ''}`}>{children}</div>
    </div>
  );
};

const Comisiones = () => {
  const usuario = sessionStorage.getItem('userType');
  const navigate = useNavigate();
  const { routes } = useOutletContext();
  const location = useLocation();

  const [filteredComisiones, setFilteredComisiones] = useState([]);
  const [searchCriteria, setSearchCriteria] = useState({
    carrera: '',
    detalle: ''
  });
  const [loading, setLoading] = useState(true);
  const [serverUp, setServerUp] = useState(false);
  const [grados, setGrados] = useState([]);
  const [carreras, setCarreras] = useState([]);

  const [showModal, setShowModal] = useState(false);
  const [gradoToDelete, setGradoToDelete] = useState(null);
  const [detalles, setDetalles] = useState('');

  const { addNotification } = useNotification();

  useEffect(() => {
    if (location.state?.successMessage) {
      addNotification(location.state.successMessage, 'success');

      if (location.state.updated) {
        navigate(location.pathname, { replace: true, state: {} });
      }
    }

    const fetchGrados = async () => {
      setLoading(true);
      try {
        const response = await fetch('http://127.0.0.1:8000/api/horarios/carreraGrados', {
          headers: { Accept: 'application/json' }
        });

        if (!response.ok) throw new Error('Error al obtener los grados');

        const data = await response.json();
        const normalizedData = data.map((item) => ({
          carrera: {
            carrera: item.carrera.carrera,
            cupo: item.carrera.cupo,
            id_carrera: item.carrera.id_carrera
          },
          grado: {
            capacidad: item.grado.capacidad,
            detalle: item.grado.detalle,
            division: item.grado.division,
            grado: item.grado.grado,
            id_grado: item.grado.id_grado,
            grado_uc: item.grado.grado_uc
          },
          id_carrera: item.id_carrera,
          id_grado: item.id_grado,
          id_carrera_grado: item.id_carrera_grado
        }));

        setGrados(normalizedData);
        setFilteredComisiones(normalizedData);

        // Obtener una lista única de carreras para el select
        const uniqueCarreras = Array.from(
          new Set(normalizedData.map((comision) => comision.carrera.carrera))
        );
        setCarreras(uniqueCarreras);
        setServerUp(true);
      } catch (error) {
        console.error('Error al obtener grados:', error);
      } finally {
        setLoading(false);
      }
    };

    fetchGrados();
  }, [location.state, navigate, location.pathname]);

  const handleDelete = async () => {
    try {
      const response = await fetch(
        `http://127.0.0.1:8000/api/horarios/carreraGrados/eliminar/${gradoToDelete}`,
        {
          method: 'DELETE',
          body: JSON.stringify({ detalles: detalles, usuario }),
          headers: { 'Content-Type': 'application/json' }
        }
      );

      if (!response.ok) throw new Error('Error al eliminar el grado');
      const data = await response.json();
      console.log('el grado ', data);
      setGrados(grados.filter((grado) => grado.id_carrera_grado !== gradoToDelete));
      setFilteredComisiones(
        filteredComisiones.filter((comision) => comision.id_carrera_grado !== gradoToDelete)
      );

      addNotification('Se eliminó el grado', 'success');

      setShowModal(false); // Cerrar el modal
    } catch (error) {
      addNotification(error.message, 'danger');
    }
  };

  const handleSearch = (event) => {
    const { name, value } = event.target;

    const newCriteria = {
      ...searchCriteria,
      [name]: value
    };
    setSearchCriteria(newCriteria);

    const filtered = grados.filter(
      (comision) =>
        (newCriteria.carrera === '' || comision.carrera.carrera === newCriteria.carrera) &&
        comision.grado.detalle.toLowerCase().includes(newCriteria.detalle.toLowerCase())
    );

    setFilteredComisiones(filtered);
  };

  const handleClearFilters = () => {
    setSearchCriteria({ carrera: '', detalle: '' });
    setFilteredComisiones(grados);
  };

  return (
    <>
      {loading ? (
        <p>Cargando...</p>
      ) : serverUp ? (
        <div className="container py-3">
          <div className="row align-items-center justify-content-center">
            <div className="col-12 text-center">
              <div className="filter mb-2 d-flex flex-wrap align-items-center">
                <input
                  type="text"
                  className="form-control mb-2 mb-md-0 me-md-2"
                  placeholder="Buscar por detalle..."
                  name="detalle"
                  value={searchCriteria.detalle}
                  onChange={handleSearch}
                  style={{ flex: '0 0 50%' }} // El input ocupa el 50%
                />
                <select
                  className="form-select mb-2 mb-md-0 me-md-2"
                  name="carrera"
                  value={searchCriteria.carrera}
                  onChange={handleSearch}
                  style={{ flex: '0 0 25%' }} // El select tipo ocupa el 25%
                >
                  <option value="">Todas las carreras</option>
                  {carreras.map((carrera, index) => (
                    <option key={index} value={carrera}>
                      {carrera}
                    </option>
                  ))}
                </select>

                <button
                  type="button"
                  className="btn btn-secondary me-2 px-0 py-1 mx-2"
                  onClick={handleClearFilters}
                  style={{ flex: '0 0 15%' }} // El select tipo ocupa el 15%
                >
                  Limpiar filtros
                </button>
              </div>

              <button
                type="button"
                className="btn btn-primary me-2"
                onClick={() => navigate(`${routes.base}/${routes.comisiones.crear}`)}
              >
                Crear
              </button>
            </div>
          </div>

          <div className="container">
            {filteredComisiones.length > 0 ? (
              filteredComisiones.map(({ id_carrera_grado, carrera, grado }) => (
                <div
                  key={id_carrera_grado} // Usar id_carrera_grado como clave única
                  style={{
                    border: '1px solid #ccc',
                    borderRadius: '5px',
                    padding: '10px',
                    marginBottom: '10px',
                    width: '30vw'
                  }}
                >
                  <h5>Carrera: {carrera.carrera}</h5>
                  <p>Grado: {grado.grado}</p>
                  <p>División: {grado.division}</p>
                  <p>Detalle: {grado.detalle}</p>
                  <p>Capacidad: {grado.capacidad}</p>

                  <div>
                    <Accordion title="Ver materias">
                      <ul>
                        {grado.grado_uc && grado.grado_uc.length > 0 ? (
                          grado.grado_uc.map((uc, index) => (
                            <li key={index}>{uc.unidad_curricular.unidad_curricular}</li>
                          ))
                        ) : (
                          <p>No hay materias asignadas</p>
                        )}
                      </ul>
                    </Accordion>
                  </div>

                  <div className="botones">
                    <button
                      type="button"
                      className="btn btn-primary me-2"
                      onClick={() =>
                        navigate(`${routes.base}/${routes.comisiones.actualizar(id_carrera_grado)}`)
                      }
                    >
                      Actualizar
                    </button>
                    <button
                      className="btn btn-danger"
                      onClick={() => {
                        setGradoToDelete(id_carrera_grado);
                        setShowModal(true);
                      }}
                    >
                      Eliminar
                    </button>
                  </div>
                </div>
              ))
            ) : (
              <p>No se encontraron comisiones que coincidan con la búsqueda.</p>
            )}
          </div>

          {/* Modal de confirmación */}
          <Modal show={showModal} onHide={() => setShowModal(false)}>
            <Modal.Header closeButton></Modal.Header>
            <Modal.Body>
              <label htmlFor="detalles">Por favor, ingrese el motivo de eliminacion:</label>
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
        </div>
      ) : (
        <ErrorPage message="La seccion de grados" statusCode={500} />
      )}
    </>
  );
};

export default Comisiones;
