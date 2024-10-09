import React, { useState, useEffect } from 'react';
import { useNavigate, useOutletContext } from 'react-router-dom';

const Materias = () => {
  const navigate = useNavigate();
  const { routes } = useOutletContext();

  const [materias, setMaterias] = useState([]);
  const [errors, setErrors] = useState([]);
  const [successMessage, setSuccessMessage] = useState('');

  // Cargar las materias desde una API o un backend
  useEffect(() => {
    const fetchMaterias = async () => {
      try {
        const response = await fetch('/api/materias'); // Cambia esta URL a tu API
        const data = await response.json();
        setMaterias(data);
      } catch (error) {
        setErrors([error.message]); // Guardar errores en caso de fallo
      }
    };

    fetchMaterias();
  }, []);

  // Eliminar materia
  const eliminarMateria = async (id) => {
    try {
      const response = await fetch(`/api/materias/${id}`, {
        method: 'DELETE',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-Token': document.querySelector('meta[name="csrf-token"]').content
        }
      });

      if (response.ok) {
        setMaterias(materias.filter((materia) => materia.id !== id)); // Actualizar la lista de materias
        setSuccessMessage('Materia eliminada correctamente');
      } else {
        const errorData = await response.json();
        setErrors(errorData.errors || ['No se pudo eliminar la materia']);
      }
    } catch (error) {
      setErrors([error.message]);
    }
  };

  return (
    <div className="container py-3">
      <div className="row align-items-center justify-content-center">
        <div className="col-6 text-center">
          <button
            type="button"
            className="btn btn-primary me-2"
            onClick={() => navigate(`${routes.base}/${routes.materias.crear}`)}
            style={{ display: 'inline-block', marginRight: '10px' }}
          >
            Crear
          </button>
        </div>
      </div>

      {/* Listado de materias */}
      <div className="container">
        {materias.map((materia) => (
          <div
            key={materia.id_materia}
            style={{
              border: '1px solid #ccc',
              borderRadius: '5px',
              padding: '10px',
              marginBottom: '10px',
              width: '30vw'
            }}
          >
            <p>Nombre: {materia.nombre}</p>
            <p>Módulos semanales: {materia.modulos_semanales}</p>
            <div className="botones">
              <button
                type="button"
                className="btn btn-primary me-2"
                // id en parentesis
                onClick={() => navigate(`${routes.base}/${routes.materias.actualizar()}`)}
                style={{ display: 'inline-block', marginRight: '10px' }}
              >
                Actualizar
              </button>

              <button
                type="button"
                className="btn btn-danger"
                onClick={() => eliminarMateria(materia.id_materia)}
              >
                Eliminar
              </button>
            </div>
          </div>
        ))}
      </div>

      {/* Contenedor de mensajes */}
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

export default Materias;
