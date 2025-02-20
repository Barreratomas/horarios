import React, { useState, useEffect } from 'react';
import { useNavigate, useOutletContext, useLocation } from 'react-router-dom';
import { Modal, Button } from 'react-bootstrap'; // Importamos el modal y el botón
import '../../css/acordeon.css';
import DataTable from 'react-data-table-component';

// Componente para mostrar la información expandida de cada fila (materias)
const ExpandedComponent = ({ data }) => {
  return (
    <div style={{ padding: '10px 20px', backgroundColor: '#f8f9fa' }}>
      <h5>Materias</h5>
      {data.grado.grado_uc && data.grado.grado_uc.length > 0 ? (
        <ul>
          {data.grado.grado_uc.map((uc, index) => (
            <li key={index}>{uc.unidad_curricular.unidad_curricular}</li>
          ))}
        </ul>
      ) : (
        <p>No hay materias asignadas</p>
      )}
    </div>
  );
};
const Comisiones = () => {
  const usuario = sessionStorage.getItem('userType');
  const navigate = useNavigate();
  const { routes } = useOutletContext(); // Acceder a las rutas definidas
  const location = useLocation(); // Manejar el estado de navegación

  const [filteredComisiones, setFilteredComisiones] = useState([]);
  const [searchCriteria, setSearchCriteria] = useState({
    carrera: '',
    detalle: ''
  });
  const [loading, setLoading] = useState(true);
  const [serverUp, setServerUp] = useState(false);
  const [grados, setGrados] = useState([]);
  const [carreras, setCarreras] = useState([]);
  const [errors, setErrors] = useState([]);
  const [successMessage, setSuccessMessage] = useState('');
  const [hideMessage, setHideMessage] = useState(false);

  const [showModal, setShowModal] = useState(false);
  const [gradoToDelete, setGradoToDelete] = useState(null);
  const [detalles, setDetalles] = useState('');

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
          id_grado: item.id_grado
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
        setErrors([error.message || 'Servidor fuera de servicio...']);
      } finally {
        setLoading(false);
      }
    };

    fetchGrados();
  }, [location.state, navigate, location.pathname]);

  const handleDelete = async () => {
    try {
      const response = await fetch(
        `http://127.0.0.1:8000/api/horarios/grados/eliminar/${gradoToDelete}`,
        {
          method: 'DELETE',
          body: JSON.stringify({ detalles: detalles, usuario }), // Enviar detalles con la eliminación
          headers: { 'Content-Type': 'application/json' }
        }
      );

      if (!response.ok) throw new Error('Error al eliminar el grado');

      setGrados(grados.filter((grado) => grado.grado.id_grado !== gradoToDelete));
      setFilteredComisiones(
        filteredComisiones.filter((comision) => comision.grado.id_grado !== gradoToDelete)
      );
      setSuccessMessage('Grado eliminado correctamente');

      setTimeout(() => setHideMessage(true), 3000);
      setTimeout(() => {
        setSuccessMessage('');
        setHideMessage(false);
        navigate(location.pathname, { replace: true });
      }, 3500);
      setShowModal(false); // Cerrar el modal
    } catch (error) {
      console.error('Error al eliminar grado:', error);
      setErrors([error.message || 'Error al eliminar el grado']);
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

  // Definición de columnas para el DataTable
  const columns = [
    {
      name: 'Carrera',
      selector: (row) => row.carrera.carrera,
      sortable: true
    },
    {
      name: 'Cupo',
      selector: (row) => row.carrera.cupo,
      sortable: true
    },
    {
      name: 'Grado',
      selector: (row) => row.grado.grado,
      sortable: true
    },
    {
      name: 'División',
      selector: (row) => row.grado.division,
      sortable: true
    },
    {
      name: 'Detalle',
      selector: (row) => row.grado.detalle,
      sortable: true
    },
    {
      name: 'Capacidad',
      selector: (row) => row.grado.capacidad,
      sortable: true
    },

    {
      name: 'Acciones',
      cell: (row) => (
        <div style={{ display: 'flex', gap: '0.25rem' }}>
          <button
            type="button"
            className="btn btn-primary btn-sm"
            style={{ fontSize: '0.90rem', padding: '0.45rem 0.5rem' }}
            onClick={() =>
              navigate(`${routes.base}/${routes.comisiones.actualizar(row.grado.id_grado)}`)
            }
          >
            Actualiza
          </button>
          <button
            type="button"
            className="btn btn-danger btn-sm"
            style={{ fontSize: '0.90rem', padding: '0.45rem 0.5rem' }}
            onClick={() => {
              setGradoToDelete(row.grado.id_grado);
              setShowModal(true);
            }}
          >
            Elimina
          </button>
        </div>
      )
    }
  ];
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
          <h1 className="titulo">Grados</h1>
          <DataTable
            columns={columns}
            data={filteredComisiones}
            pagination
            responsive
            highlightOnHover
            expandableRows
            expandableRowsComponent={ExpandedComponent}
            customStyles={{
              rows: {
                style: {
                  transition: 'all 0.3s ease'
                }
              },
              headCells: {
                style: {
                  fontSize: '1.1rem',
                  letterSpacing: '0.5px'
                }
              }
            }}
          />
          {/* Modal de confirmación */}
          <Modal show={showModal} onHide={() => setShowModal(false)}>
            <Modal.Header closeButton>
              <Modal.Title>Confirmar eliminación</Modal.Title>
            </Modal.Header>
            <Modal.Body>
              <p>¿Estás seguro de que quieres eliminar este grado?</p>
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
      ) : (
        <p>No se pudo conectar al servidor.</p>
      )}
    </>
  );
};

export default Comisiones;
