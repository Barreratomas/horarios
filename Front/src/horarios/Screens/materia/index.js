import React, { useState, useEffect } from 'react';
import { useNavigate, useOutletContext, useLocation } from 'react-router-dom';
import { Modal, Button } from 'react-bootstrap';
import DataTable from 'react-data-table-component';

const Materias = () => {
  const [detalles, setDetalles] = useState(''); // Estado para los detalles
  const usuario = sessionStorage.getItem('userType');
  const [showModal, setShowModal] = useState(false);
  const [materiaToDelete, setMateriaToDelete] = useState(null);

  const navigate = useNavigate();
  const { routes } = useOutletContext();
  const location = useLocation();

  const [loading, setLoading] = useState(true);
  const [serverUp, setServerUp] = useState(false);
  const [materias, setMaterias] = useState([]);
  const [filteredMaterias, setFilteredMaterias] = useState([]);
  const [searchCriteria, setSearchCriteria] = useState({
    unidad_curricular: '',
    tipo: '',
    formato: ''
  });
  const [errors, setErrors] = useState([]);
  const [successMessage, setSuccessMessage] = useState('');
  const [hideMessage, setHideMessage] = useState(false);

  // Opciones para los selects
  const tipos = ['Anual', 'Cuatrimestral'];
  const formatos = ['Taller', 'Materia', 'Laboratorio'];

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

    const fetchMaterias = async () => {
      setLoading(true);
      try {
        const response = await fetch('http://127.0.0.1:8000/api/horarios/unidadCurricular', {
          headers: { Accept: 'application/json' }
        });

        if (!response.ok) throw new Error(' ');

        const data = await response.json();
        setMaterias(data);
        setFilteredMaterias(data);
        setServerUp(true);
      } catch (error) {
        console.error('Error al obtener materias:', error);
        alert('Servidor fuera de servicio...');
      } finally {
        setLoading(false);
      }
    };

    fetchMaterias();
  }, [location.state, navigate, location.pathname]);

  const handleDelete = async () => {
    try {
      const response = await fetch(
        `http://127.0.0.1:8000/api/horarios/unidadCurricular/eliminar/${materiaToDelete}`,
        {
          method: 'DELETE',
          body: JSON.stringify({ detalles: detalles, usuario }),
          headers: { 'Content-Type': 'application/json' }
        }
      );

      if (!response.ok) throw new Error('Error al eliminar materia');

      setMaterias(materias.filter((materia) => materia.id_uc !== materiaToDelete));
      setFilteredMaterias(filteredMaterias.filter((materia) => materia.id_uc !== materiaToDelete));
      setSuccessMessage('Materia eliminada correctamente');

      setTimeout(() => setHideMessage(true), 3000);
      setTimeout(() => {
        setSuccessMessage('');
        setHideMessage(false);
        navigate(location.pathname, { replace: true });
      }, 3500);
      setShowModal(false); // Cerrar el modal
    } catch (error) {
      setErrors([error.message || 'Error al eliminar materia']);
    }
  };

  const handleSearch = (event) => {
    const { name, value } = event.target;

    const newCriteria = {
      ...searchCriteria,
      [name]: value.trim().toLowerCase()
    };
    setSearchCriteria(newCriteria);

    // Aplica los filtros considerando todos los criterios seleccionados
    const filtered = materias.filter(
      (materia) =>
        (!newCriteria.unidad_curricular ||
          materia.unidad_curricular?.toLowerCase().includes(newCriteria.unidad_curricular)) &&
        (!newCriteria.tipo || materia.tipo?.toLowerCase() === newCriteria.tipo) &&
        (!newCriteria.formato || materia.formato?.toLowerCase() === newCriteria.formato)
    );

    setFilteredMaterias(filtered);
  };

  const handleClearFilters = () => {
    setSearchCriteria({
      unidad_curricular: '',
      tipo: '',
      formato: ''
    });
    setFilteredMaterias(materias);
  };

  const columns = [
    {
      name: 'Unidad Curricular',
      selector: (row) => row.unidad_curricular,
      sortable: true
    },
    {
      name: 'Tipo',
      selector: (row) => row.tipo,
      sortable: true
    },
    {
      name: 'Horas Semanales',
      selector: (row) => row.horas_sem,
      sortable: true
    },
    {
      name: 'Horas Anuales',
      selector: (row) => row.horas_anual,
      sortable: true
    },
    {
      name: 'Formato',
      selector: (row) => row.formato,
      sortable: true
    },
    {
      name: 'Acciones',
      cell: (row) => (
        <>
          <button
            className="btn btn-primary me-2"
            onClick={() => navigate(`${routes.base}/${routes.materias.actualizar(row.id_uc)}`)}
          >
            Actualizar
          </button>
          <button
            className="btn btn-danger"
            onClick={() => {
              setMateriaToDelete(row.id_uc);
              setShowModal(true);
            }}
          >
            Eliminar
          </button>
        </>
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
                  placeholder="Buscar por unidad curricular..."
                  name="unidad_curricular"
                  value={searchCriteria.unidad_curricular}
                  onChange={handleSearch}
                  style={{ flex: '0 0 50%' }}
                />

                <select
                  className="form-select mb-2 mb-md-0 me-md-2"
                  name="tipo"
                  value={searchCriteria.tipo}
                  onChange={handleSearch}
                  style={{ flex: '0 0 15%' }}
                >
                  <option value="">Filtrar por tipo...</option>
                  {tipos.map((tipo) => (
                    <option key={tipo} value={tipo.toLowerCase()}>
                      {tipo}
                    </option>
                  ))}
                </select>

                <select
                  className="form-select mb-2 mb-md-0"
                  name="formato"
                  value={searchCriteria.formato}
                  onChange={handleSearch}
                  style={{ flex: '0 0 15%' }}
                >
                  <option value="">Filtrar por formato...</option>
                  {formatos.map((formato) => (
                    <option key={formato} value={formato.toLowerCase()}>
                      {formato}
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
                onClick={() => navigate(`${routes.base}/${routes.materias.crear}`)}
              >
                Crear
              </button>
            </div>
          </div>

          <DataTable
            title="Materias"
            columns={columns}
            data={filteredMaterias} /* Usar datos filtrados */
            pagination
            highlightOnHover
            responsive
          />

          <Modal show={showModal} onHide={() => setShowModal(false)}>
            <Modal.Header closeButton>
              <Modal.Title>Confirmar eliminación</Modal.Title>
            </Modal.Header>
            <Modal.Body>
              <div className="form-group">
                <label htmlFor="detalles">Por favor, ingrese el motivo de eliminación:</label>
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
        <h1>Este módulo no está disponible en este momento</h1>
      )}
    </>
  );
};

export default Materias;
