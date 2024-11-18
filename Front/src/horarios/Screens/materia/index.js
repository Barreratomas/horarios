import React, { useState, useEffect } from 'react';
import { useNavigate, useOutletContext, useLocation } from 'react-router-dom';

const Materias = () => {
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

  const handleDelete = async (id_uc) => {
    try {
      const response = await fetch(
        `http://127.0.0.1:8000/api/horarios/unidadCurricular/eliminar/${id_uc}`,
        {
          method: 'DELETE',
          headers: { 'Content-Type': 'application/json' }
        }
      );

      if (!response.ok) throw new Error('Error al eliminar materia');

      setMaterias(materias.filter((materia) => materia.id_uc !== id_uc));
      setFilteredMaterias(filteredMaterias.filter((materia) => materia.id_uc !== id_uc));
      setSuccessMessage('Materia eliminada correctamente');

      setTimeout(() => setHideMessage(true), 3000);
      setTimeout(() => {
        setSuccessMessage('');
        setHideMessage(false);
        navigate(location.pathname, { replace: true });
      }, 3500);
    } catch (error) {
      setErrors([error.message || 'Error al eliminar materia']);
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
    const filtered = materias.filter(
      (materia) =>
        materia.unidad_curricular.toLowerCase().includes(newCriteria.unidad_curricular) &&
        (!newCriteria.tipo || materia.tipo.toLowerCase() === newCriteria.tipo) &&
        (!newCriteria.formato || materia.formato.toLowerCase() === newCriteria.formato)
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

  return (
    <>
      {loading ? (
        <p>Cargando...</p>
      ) : serverUp ? (
        <div className="container py-3">
          <div className="row align-items-center justify-content-center mb-3">
            <div className="col-12 text-center">
              <div className="filter mb-2 d-flex flex-wrap align-items-center">
                {/* Input ocupa el 70% del espacio */}
                <input
                  type="text"
                  className="form-control mb-2 mb-md-0 me-md-2"
                  placeholder="Buscar por unidad curricular..."
                  name="unidad_curricular"
                  value={searchCriteria.unidad_curricular}
                  onChange={handleSearch}
                  style={{ flex: '0 0 50%' }} // El input ocupa el 70%
                />

                {/* Select tipo ocupa el 15% del espacio */}
                <select
                  className="form-select mb-2 mb-md-0 me-md-2"
                  name="tipo"
                  value={searchCriteria.tipo}
                  onChange={handleSearch}
                  style={{ flex: '0 0 15%' }} // El select tipo ocupa el 15%
                >
                  <option value="">Filtrar por tipo...</option>
                  {tipos.map((tipo) => (
                    <option key={tipo} value={tipo.toLowerCase()}>
                      {tipo}
                    </option>
                  ))}
                </select>

                {/* Select formato ocupa el 15% del espacio */}
                <select
                  className="form-select mb-2 mb-md-0"
                  name="formato"
                  value={searchCriteria.formato}
                  onChange={handleSearch}
                  style={{ flex: '0 0 15%' }} // El select formato ocupa el 15%
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
                  style={{ flex: '0 0 15%' }} // El select tipo ocupa el 15%
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

          <div className="container">
            {filteredMaterias.length > 0 ? (
              filteredMaterias.map((materia) => (
                <div
                  key={materia.id_uc}
                  style={{
                    border: '1px solid #ccc',
                    borderRadius: '5px',
                    padding: '10px',
                    marginBottom: '10px',
                    width: '30vw'
                  }}
                >
                  <p>Unidad Curricular: {materia.unidad_curricular}</p>
                  <p>Tipo: {materia.tipo}</p>
                  <p>Horas Semanales: {materia.horas_sem}</p>
                  <p>Horas Anuales: {materia.horas_anual}</p>
                  <p>Formato: {materia.formato}</p>

                  <div className="botones">
                    <button
                      type="button"
                      className="btn btn-primary me-2"
                      onClick={() =>
                        navigate(`${routes.base}/${routes.materias.actualizar(materia.id_uc)}`)
                      }
                    >
                      Actualizar
                    </button>

                    <button
                      type="button"
                      className="btn btn-danger"
                      onClick={() => handleDelete(materia.id_uc)}
                    >
                      Eliminar
                    </button>
                  </div>
                </div>
              ))
            ) : (
              <p>No se encontraron materias que coincidan con la búsqueda.</p>
            )}
          </div>

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
