import React, { useState } from 'react';
import { useNavigate, useOutletContext } from 'react-router-dom'; // Para redirigir después de la creación

const CrearCarrera = () => {
  const [carrera, setCarrera] = useState('');
  const [cupo, setCupo] = useState('');
  const [errors, setErrors] = useState([]);

  const navigate = useNavigate();
  const { routes } = useOutletContext();

  // Función para manejar el envío del formulario
  const handleSubmit = async (e) => {
    e.preventDefault();
    setErrors({}); // Reiniciar los errores antes de la validacion

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
          state: { successMessage: 'Carrera creada con éxito' }
        });
      } else {
        const data = await response.json();
        if (data.errors) {
          setErrors(data.errors); // Manejar errores de validación
        }
      }
    } catch (error) {
      console.error('Error creando carrera:', error);
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
            {errors.carrera && <div className="text-danger">{errors.carrera}</div>}
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
            {errors.cupo && <div className="text-danger">{errors.cupo}</div>}
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

      {errors.length > 0 && (
        <div className="container" style={{ width: '500px' }}>
          <div className="alert alert-danger">
            <ul>
              {errors.map((error, index) => (
                <li key={index}>{error}</li>
              ))}
            </ul>
          </div>
        </div>
      )}
    </div>
  );
};

export default CrearCarrera;
