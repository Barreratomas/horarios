// import React, { useState, useEffect } from 'react';
import React, { useState } from 'react';
import { useNavigate, useOutletContext } from 'react-router-dom';

const Carreras = () => {
  const navigate = useNavigate();
  const { routes } = useOutletContext();

  const [carreras, setCarreras] = useState([]);
  const [messages, setMessages] = useState({ success: '', errors: [] });

  // Función para obtener las carreras desde la API
  // useEffect(() => {
  //   fetch('/api/carreras') // Ajusta la URL a tu endpoint real
  //     .then((response) => response.json())
  //     .then((data) => setCarreras(data))
  //     .catch((error) => console.error('Error al obtener las carreras:', error));
  // }, []);

  // Función para eliminar una carrera
  const handleDelete = (idCarrera) => {
    fetch(`/api/carreras/${idCarrera}`, {
      method: 'DELETE',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-Token': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
      }
    })
      .then((response) => response.json())
      .then((data) => {
        if (data.success) {
          setMessages({ success: 'Carrera eliminada con éxito', errors: [] });
          setCarreras(carreras.filter((carrera) => carrera.id_carrera !== idCarrera));
        } else {
          setMessages({ success: '', errors: data.errors });
        }
      })
      .catch((error) => console.error('Error al eliminar la carrera:', error));
  };

  return (
    <div className="container py-3">
      <div className="row align-items-center justify-content-center">
        <div className="col-6 text-center">
          <button
            type="button"
            className="btn btn-primary me-2"
            onClick={() => navigate(`${routes.base}/${routes.carreras.crear}`)}
            style={{ display: 'inline-block', marginRight: '10px' }}
          >
            Crear
          </button>
        </div>
      </div>

      <div className="container">
        {carreras.map((carrera) => (
          <div
            key={carrera.id_carrera}
            style={{
              border: '1px solid #ccc',
              borderRadius: '5px',
              padding: '10px',
              marginBottom: '10px',
              width: '30vw'
            }}
          >
            <p>Carrera: {carrera.nombre}</p>
            <div className="botones">
              <button
                type="button"
                className="btn btn-primary me-2"
                // poner el id dentro de los parentesis
                onClick={() => navigate(`${routes.base}/${routes.carreras.actuaizar()}`)}
                style={{ display: 'inline-block', marginRight: '10px' }}
              >
                Actualizar
              </button>

              <button
                type="button"
                className="btn btn-danger"
                onClick={() => handleDelete(carrera.id_carrera)}
                style={{ display: 'inline-block' }}
              >
                Eliminar
              </button>
            </div>
          </div>
        ))}
      </div>

      <div id="messages-container" className="container">
        {messages.errors.length > 0 && (
          <div className="alert alert-danger">
            <ul>
              {messages.errors.map((error, index) => (
                <li key={index}>{error}</li>
              ))}
            </ul>
          </div>
        )}

        {messages.success && <div className="alert alert-success">{messages.success}</div>}
      </div>
    </div>
  );
};

export default Carreras;
