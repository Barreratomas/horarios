import React, { useState, useEffect } from 'react';
import { useNavigate, useOutletContext, useLocation } from 'react-router-dom';
import { Modal, Button } from 'react-bootstrap';
import DataTable from 'react-data-table-component';
import { useNotification } from '../layouts/parcials/notification';
import ErrorPage from '../layouts/parcials/errorPage';

const Aulas = () => {
  const [detalles, setDetalles] = useState('');
  const usuario = sessionStorage.getItem('userType');
  const [showModal, setShowModal] = useState(false);
  const [aulaToDelete, setAulaToDelete] = useState(null);

  const navigate = useNavigate();
  const { routes } = useOutletContext();
  const location = useLocation();

  const [loading, setLoading] = useState(true);
  const [serverUp, setServerUp] = useState(false);
  const [aulas, setAulas] = useState([]);
  const [filteredAulas, setFilteredAulas] = useState([]);
  const [searchCriteria, setSearchCriteria] = useState({
    nombre: '',
    tipo: ''
  });

  const { addNotification } = useNotification();

  // Opciones para los selects
  const tipos = ['Normal', 'Laboratorio', 'Sum'];

  useEffect(() => {
    if (location.state?.successMessage) {
      addNotification(location.state.successMessage, 'success');

      if (location.state.updated) {
        navigate(location.pathname, { replace: true, state: {} });
      }
    }

    const fetchAulas = async () => {
      setLoading(true);
      try {
        const response = await fetch('http://127.0.0.1:8000/api/horarios/aulas', {
          headers: { Accept: 'application/json' }
        });
        if (!response.ok) {
          throw new Error(`Error`);
        }
        const data = await response.json();

        if (data.error) {
          addNotification(data.error, 'danger');
        } else {
          setAulas(data);
          setFilteredAulas(data);
          setServerUp(true);
        }
      } catch (error) {
        setServerUp(false);
      } finally {
        setLoading(false);
      }
    };

    fetchAulas();
  }, [location.state, location.pathname]);

  const handleDelete = async () => {
    try {
      const response = await fetch(
        `http://127.0.0.1:8000/api/horarios/aulas/eliminar/${aulaToDelete}`,

        {
          method: 'DELETE',
          body: JSON.stringify({ detalles: detalles, usuario }),
          headers: { 'Content-Type': 'application/json' }
        }
      );

      if (!response.ok) throw new Error('Error al eliminar aula');
      const data = await response.json();

      setAulas(aulas.filter((aula) => aula.id_aula !== aulaToDelete));
      setFilteredAulas(filteredAulas.filter((aula) => aula.id_aula !== aulaToDelete));

      addNotification(data.message, 'success');

      setShowModal(false);
    } catch (error) {
      addNotification(error.message, 'danger');
    }
  };

  const handleSearch = (event) => {
    const { name, value } = event.target;

    // Actualiza los criterios de búsqueda
    const newCriteria = {
      ...searchCriteria,
      [name]: value.toLowerCase()
    };
    setSearchCriteria(newCriteria);

    // Aplica los filtros considerando todos los criterios seleccionados
    const filtered = aulas.filter(
      (aula) =>
        aula.nombre.toLowerCase().includes(newCriteria.nombre) &&
        (!newCriteria.tipo || aula.tipo_aula.toLowerCase() === newCriteria.tipo)
    );

    setFilteredAulas(filtered);
  };

  const handleClearFilters = () => {
    setSearchCriteria({
      nombre: '',
      tipo: ''
    });
    setFilteredAulas(aulas);
  };

  // Define columns for DataTable
  const columns = [
    {
      name: 'Nombre',
      selector: (row) => row.nombre,
      sortable: true
    },
    {
      name: 'Tipo',
      selector: (row) => row.tipo_aula,
      sortable: true
    },
    {
      name: 'Capacidad',
      selector: (row) => row.capacidad,
      sortable: true
    },
    {
      name: 'Acciones',
      cell: (row) => (
        <div className="botones">
          <button
            type="button"
            className="btn btn-primary me-2"
            onClick={() => navigate(`${routes.base}/${routes.aulas.actualizar(row.id_aula)}`)}
          >
            Actualizar
          </button>
          <button
            type="button"
            className="btn btn-danger"
            onClick={() => {
              setAulaToDelete(row.id_aula);
              setShowModal(true);
            }}
          >
            Eliminar
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
          <div className="row align-items-center justify-content-center mb-3">
            <div className="col-12 text-center">
              <div className="filter mb-2 d-flex flex-wrap align-items-center">
                <input
                  type="text"
                  className="form-control mb-2 mb-md-0 me-md-2"
                  placeholder="Buscar por nombre de aula..."
                  name="nombre"
                  value={searchCriteria.nombre}
                  onChange={handleSearch}
                  style={{ flex: '0 0 50%' }}
                />

                <select
                  className="form-select mb-2 mb-md-0 me-md-2"
                  name="tipo"
                  value={searchCriteria.tipo}
                  onChange={handleSearch}
                  style={{ flex: '0 0 25%' }}
                >
                  <option value="">Filtrar por tipo...</option>
                  {tipos.map((tipo) => (
                    <option key={tipo} value={tipo.toLowerCase()}>
                      {tipo}
                    </option>
                  ))}
                </select>

                <button
                  type="button"
                  className="btn btn-secondary me-2 px-0 py-1 mx-2"
                  onClick={handleClearFilters}
                  style={{ flex: '0 0 15%' }}
                >
                  Limpiar Filtros
                </button>
              </div>
              <button
                type="button"
                className="btn btn-primary"
                onClick={() => navigate(`${routes.base}/${routes.aulas.crear}`)}
              >
                Crear
              </button>
            </div>
          </div>

          {/* DataTable to display aulas */}

          <DataTable
            title="Aulas"
            columns={columns}
            data={filteredAulas}
            pagination
            highlightOnHover
            responsive
          />

          {/* Modal for confirmation */}
          <Modal show={showModal} onHide={() => setShowModal(false)}>
            <Modal.Header closeButton></Modal.Header>
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
      ) : (
        <ErrorPage message="La seccion de aulas" statusCode={500} />
      )}
    </>
  );
};

export default Aulas;
