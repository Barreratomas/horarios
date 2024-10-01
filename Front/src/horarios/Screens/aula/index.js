import React, { useState, useEffect } from 'react';
import { useNavigate, useOutletContext } from 'react-router-dom';
const Aulas = () => {
  const navigate = useNavigate();
  const { routes } = useOutletContext();

  const [aulas, setAulas] = useState([]);
  const [errors, setErrors] = useState([]);
  const [successMessage, setSuccessMessage] = useState('');

  // Simula la carga de datos desde una API (puedes adaptarlo a tu fuente de datos)
  useEffect(() => {
    // Simulación de una llamada a una API para obtener aulas
    const fetchAulas = async () => {
      try {
        const response = await fetch('/api/aulas'); // Ruta de tu API
        const data = await response.json();
        setAulas(data); // Guardar las aulas obtenidas en el estado
      } catch (error) {
        console.error('Error fetching aulas:', error);
        setErrors(['Error al cargar aulas']);
      }
    };

    fetchAulas();
  }, []);

  const handleDelete = async (id_aula) => {
    try {
      await fetch(`/api/aulas/${id_aula}`, {
        method: 'DELETE',
        headers: {
          'Content-Type': 'application/json'
        }
      });
      setAulas(aulas.filter((aula) => aula.id_aula !== id_aula)); // Eliminar del estado
      setSuccessMessage('Aula eliminada con éxito');
    } catch (error) {
      setErrors(['Error al eliminar aula']);
    }
  };

  return (
    <div className="container py-3">
      <div className="row align-items-center justify-content-center">
        <div className="col-6 text-center">
          <button
            type="button"
            className="btn btn-primary me-2"
            onClick={() => navigate(`${routes.base}/${routes.aulas.crear}`)}
            style={{ display: 'inline-block', marginRight: '10px' }}
          >
            Crear
          </button>
        </div>
      </div>

      <div className="container">
        {aulas.map((aula) => (
          <div
            key={aula.id_aula}
            style={{
              border: '1px solid #ccc',
              borderRadius: '5px',
              padding: '10px',
              marginBottom: '10px',
              width: '30vw'
            }}
          >
            <p>Nombre: {aula.nombre}</p>
            <p>Tipo de Aula: {aula.tipo_aula}</p>
            <div className="botones">
              <button
                type="button"
                className="btn btn-primary me-2"
                // id parentesis
                onClick={() => navigate(`${routes.base}/${routes.aulas.actualizar}`)}
                style={{ display: 'inline-block', marginRight: '10px' }}
              >
                Crear
              </button>
              <button
                type="button"
                className="btn btn-danger"
                onClick={() => handleDelete(aula.id_aula)}
              >
                Eliminar
              </button>
            </div>
          </div>
        ))}
      </div>

      <div id="messages-container" className="container">
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

export default Aulas;
