import { useEffect, useState } from 'react';
import { Spinner } from 'react-bootstrap';
import '../../css/loading.css';
import ErrorPage from '../layouts/parcials/errorPage';

const Logs = () => {
  const [logs, setLogs] = useState([]);

  const [filteredLogs, setFilteredLogs] = useState([]);
  const [searchCriteria, setSearchCriteria] = useState({
    accion: ''
  });
  const [loading, setLoading] = useState(true);
  const [serverUp, setServerUp] = useState(false);
  useEffect(() => {
    const fetchLogs = async () => {
      setLoading(true);
      try {
        const response = await fetch('http://127.0.0.1:8000/api/horarios/logs', {
          headers: { Accept: 'application/json' }
        });
        if (!response.ok) throw new Error(`HTTP error! status: ${response.status}`);
        const data = await response.json();
        setLogs(data);
        setFilteredLogs(data);
        setServerUp(true);
      } catch (error) {
        console.error('Error al obtener logs:', error);
      } finally {
        setLoading(false);
      }
    };
    fetchLogs();
  }, []);

  const handleSearch = (e) => {
    const { name, value } = e.target;
    const newCriteria = {
      ...searchCriteria,
      [name]: value.toLowerCase()
    };
    setSearchCriteria(newCriteria);

    const filtered = logs.filter((log) => log.accion.toLowerCase().includes(newCriteria.accion));
    setFilteredLogs(filtered);
  };
  return (
    <>
      {loading ? (
        <div className="loading-container">
          <Spinner animation="border" role="status" className="spinner" variant="primary" />
          <p className="text-center">Cargando...</p>
        </div>
      ) : serverUp ? (
        <div className="container py-3">
          <div className="row align-items-center justify-content-center mb-3">
            <div className="col-12 text-center">
              <div className="filter mb-2 d-flex flex-wrap align-items-center">
                <input
                  type="text"
                  className="form-control mb-2 mb-md-0 me-md-2"
                  placeholder="Buscar por accion del log..."
                  name="accion"
                  onChange={handleSearch}
                />
              </div>

              <div className="container px-0">
                {filteredLogs.length > 0 ? (
                  filteredLogs.map((log) => (
                    <div
                      key={log.id_log}
                      style={{
                        border: '1px solid #ccc',
                        borderRadius: '5px',
                        padding: '10px',
                        marginBottom: '10px',
                        width: '30vw'
                      }}
                    >
                      <h5>{log.accion}</h5>
                      <p>Realizado por: {log.usuario}</p>
                      <p>Fecha: {log.fecha_accion}</p>
                      <p>Detalles: {log.detalles}</p>
                    </div>
                  ))
                ) : (
                  <p>No se encontraron logs que coincidan con la búsqueda.</p>
                )}
              </div>
            </div>
          </div>
        </div>
      ) : (
        <ErrorPage message="La seccion de lohs" statusCode={500} />
      )}
    </>
  );
};

export default Logs;
