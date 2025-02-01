import React, { useState, useEffect } from 'react';
import { useNavigate, useOutletContext, useLocation } from 'react-router-dom';
import { Modal, Button, Spinner } from 'react-bootstrap';
import '../../css/loading.css';
import DataTable from 'react-data-table-component';
import { useNotification } from '../layouts/parcials/notification';
import ErrorPage from '../layouts/parcials/errorPage';

const AsignacionAlumno = () => {
  const [detalles, setDetalles] = useState('');
  const usuario = sessionStorage.getItem('userType');
  const [showModal, setShowModal] = useState(false);
  const [alumnoToDelete, setAlumnoToDelete] = useState(null);
  const [gradoToDelete, setGradoToDelete] = useState(null);

  const navigate = useNavigate();
  const { routes } = useOutletContext();
  const location = useLocation();

  const [filteredAlumnos, setFilteredAlumnos] = useState([]);
  const [searchQuery, setSearchQuery] = useState('');

  const [alumnos, setAlumnos] = useState([]);
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

    const fetchAlumnos = async () => {
      try {
        const response = await fetch('http://127.0.0.1:8000/api/horarios/alumnoGrados/relaciones', {
          headers: { Accept: 'application/json' }
        });

        if (!response.ok) throw new Error('Error al obtener los alumnos');

        const data = await response.json();
        console.log(data);
        setAlumnos(data);
        setFilteredAlumnos(data);
        setServerUp(true);
      } catch (error) {
        console.log('Error al obtener aulas:', error.message);
      } finally {
        setLoading(false);
      }
    };

    fetchAlumnos();
  }, [location, navigate]);

  const handleSearch = (e) => {
    const query = e.target.value.toLowerCase();
    setSearchQuery(query);

    const filtered = alumnos.filter(
      (alumno) =>
        alumno.alumno.DNI.toString().includes(query) ||
        `${alumno.alumno.nombre} ${alumno.alumno.apellido}`.toLowerCase().includes(query)
    );

    setFilteredAlumnos(filtered);
  };

  const handleDelete = async () => {
    try {
      const response = await fetch(
        `http://127.0.0.1:8000/api/horarios/alumnoGrados/${alumnoToDelete}/${gradoToDelete}`,
        {
          method: 'DELETE',
          body: JSON.stringify({ detalles, usuario }),
          headers: { 'Content-Type': 'application/json' }
        }
      );

      if (!response.ok) throw new Error('Error al eliminar la asignación');
      const data = await response.json();

      setAlumnos(
        alumnos.filter(
          (alumno) =>
            !(alumno.id_alumno === alumnoToDelete && alumno.id_carrera_grado === gradoToDelete)
        )
      );
      setFilteredAlumnos(
        filteredAlumnos.filter(
          (alumno) =>
            !(alumno.id_alumno === alumnoToDelete && alumno.id_carrera_grado === gradoToDelete)
        )
      );

      addNotification(data.message, 'success');

      setShowModal(false);
    } catch (error) {
      addNotification(error.message, 'danger');
    }
  };

  const handleAssignIngresantes = async () => {
    addNotification('Comenzó el proceso de asignación de ingresantes', 'info');

    try {
      const response = await fetch(
        'http://127.0.0.1:8000/api/horarios/alumnoGrados/asignarIngresantes',
        {
          method: 'GET',
          headers: { 'Content-Type': 'application/json' }
        }
      );

      if (!response.ok) throw new Error('Error al asignar alumno');

      navigate(`${routes.base}/${routes.asignacionesAlumno.main}`, {
        state: {
          successMessage: 'Los alumnos ingresantes fueron asignados a los grados con éxito',
          updated: true
        }
      });
    } catch (error) {
      addNotification(error.message, 'danger');
    }
  };
  const handleAssignNoIngresantes = async () => {
    addNotification('Comenzó el proceso de asignación de no ingresantes', 'info');

    try {
      const response = await fetch('http://127.0.0.1:8000/api/horarios/alumnoGrados/asignar', {
        method: 'GET',
        headers: { 'Content-Type': 'application/json' }
      });

      if (!response.ok) throw new Error('Error al asignar alumnos no ingresantes');

      navigate(`${routes.base}/${routes.asignacionesAlumno.main}`, {
        state: {
          successMessage: 'Los alumnos no ingresantes fueron asignados a los grados con éxito',
          updated: true
        }
      });
    } catch (error) {
      addNotification(error.message, 'danger');
    } finally {
      console.log();
    }
  };
  const columns = [
    {
      name: 'DNI',
      selector: (row) => row.alumno.DNI,
      sortable: true
    },
    {
      name: 'Nombre',
      selector: (row) => `${row.alumno.nombre} ${row.alumno.apellido}`,
      sortable: true
    },
    {
      name: 'Grado',
      selector: (row) =>
        row.grado
          ? `${row.grado.grado}° ${row.grado.division} (${row.grado.detalle})`
          : 'No asignado'
    },
    {
      name: 'Carrera',
      selector: (row) => row.alumno?.carrera || 'No asignada'
    },
    {
      name: 'Acciones',
      cell: (row) => (
        <>
          <button
            className="btn btn-primary me-2"
            onClick={() => {
              const url = `${routes.base}/${routes.asignacionesAlumno.actualizar(
                row.id_alumno,
                row.id_carrera_grado
              )}`;
              navigate(url);
            }}
          >
            Actualizar
          </button>
          <button
            className="btn btn-danger"
            onClick={() => {
              setAlumnoToDelete(row.id_alumno);
              setGradoToDelete(row.id_carrera_grado);
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
        <div className="loading-container">
          <Spinner animation="border" role="status" className="spinner" variant="primary" />
          <p className="text-center">Cargando...</p>
        </div>
      ) : serverUp ? (
        <div className="container py-3">
          <div className="row align-items-center justify-content-center">
            <div className="col-6 text-center">
              <div className="filter mb-2 d-flex flex-wrap align-items-center">
                <input
                  type="text"
                  className="form-control mb-2 mb-md-0 me-md-2"
                  placeholder="Buscar por DNI o Nombre y Apellido"
                  value={searchQuery}
                  onChange={handleSearch}
                />
              </div>

              <button className="btn btn-primary me-2" onClick={handleAssignNoIngresantes}>
                Asignacion masiva no ingresantes
              </button>
              <button className="btn btn-primary me-2" onClick={handleAssignIngresantes}>
                Asignacion masiva ingresantes
              </button>
              <button
                type="button"
                className="btn btn-primary me-2"
                onClick={() => navigate(`${routes.base}/${routes.asignacionesAlumno.crear}`)}
                style={{ display: 'inline-block', marginRight: '10px' }}
              >
                Asignar solo un alumno
              </button>
            </div>
          </div>

          <div className="container">
            <DataTable
              columns={columns}
              data={filteredAlumnos}
              pagination
              highlightOnHover
              responsive
            />
          </div>
        </div>
      ) : (
        <ErrorPage message="La seccion de aginación de alumnos a grados" statusCode={500} />
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
    </>
  );
};

export default AsignacionAlumno;
