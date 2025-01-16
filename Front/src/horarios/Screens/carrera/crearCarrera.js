import React, { useState } from 'react';
import { useNavigate, useOutletContext } from 'react-router-dom'; // Para redirigir después de la creación
import { useNotification } from '../layouts/parcials/notification';

const CrearCarrera = () => {
  const [carrera, setCarrera] = useState('');
  const [cupo, setCupo] = useState('');

  const navigate = useNavigate();
  const { routes } = useOutletContext();

  const { addNotification } = useNotification();

  // Función para manejar el envío del formulario
  const handleSubmit = async (e) => {
    e.preventDefault();

    try {
      const response = await fetch('http://127.0.0.1:8000/api/horarios/carreras/guardar', {
        // Actualiza la URL aquí
        method: 'POST',
        headers: {
          'Content-Type': 'application/json'
        },
        body: JSON.stringify({ carrera, cupo }) // Incluir capacidad aquí
      });

      if (response.ok) {
        navigate(`${routes.base}/${routes.carreras.main}`, {
          state: { successMessage: 'carrera creada con éxito', updated: true }
        });
      } else {
        const data = await response.json();
        if (data.errors) {
          addNotification(data.errors, 'danger');
        }
      }
    } catch (error) {
      addNotification(`Error de conexión`, 'danger');
    }
  };

  return (
    <div className="container py-3">
      <div className="row align-items-center justify-content-center">
        <div className="col-6 text-center">
          <form onSubmit={handleSubmit}>
            <label htmlFor="carrera">Ingrese el nombre de la carrera</label>
            <br />
            <input
              type="text"
              name="carrera"
              value={carrera}
              onChange={(e) => setCarrera(e.target.value)}
            />
            <br />
            <br />
            <label htmlFor="cupo">Ingrese el cupo de la carrera</label>
            <br />
            <input
              type="number"
              name="cupo"
              value={cupo}
              onChange={(e) => setCupo(e.target.value)}
            />
            <br />
            <br />
            <br />
            <button type="submit" className="btn btn-primary me-2">
              Crear
            </button>
            <br />
            <br />
            <button
              type="button"
              className="btn btn-danger"
              onClick={() => navigate(`${routes.base}/${routes.carreras.main}`)}
            >
              Volver Atrás
            </button>
          </form>
        </div>
      </div>
    </div>
  );
};

export default CrearCarrera;
