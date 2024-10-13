import React, { useState, useEffect } from 'react';
import { useNavigate, useOutletContext, useParams } from 'react-router-dom';

const ActualizarCarrera = () => {
  const [carrera, setCarrera] = useState('');
  const [cupo, setCupo] = useState('');
  const [errors, setErrors] = useState({});
  const navigate = useNavigate();
  const { routes } = useOutletContext();
  const { carreraId } = useParams(); // Obtener el ID de la carrera desde la URL

  // Obtener los datos de la carrera existente
  useEffect(() => {
    const fetchCarrera = async () => {
      try {
        const response = await fetch(`http://127.0.0.1:8000/api/horarios/carreras/${carreraId}`);
        const data = await response.json();

        if (response.ok) {
          setCarrera(data.carrera);
          setCupo(data.cupo);
        } else {
          console.error('Error al obtener los datos de la carrera:', data);
        }
      } catch (error) {
        console.error('Error:', error);
      }
    };

    fetchCarrera();
  }, [carreraId]);

  const handleSubmit = async (e) => {
    e.preventDefault();
    setErrors({});

    try {
      const response = await fetch(
        `http://127.0.0.1:8000/api/horarios/carreras/actualizar/${carreraId}`,
        {
          method: 'PUT',
          headers: {
            'Content-Type': 'application/json'
          },
          body: JSON.stringify({ carrera, cupo })
        }
      );

      if (response.ok) {
        navigate(`${routes.base}/${routes.carreras.main}`, {
          state: { successMessage: 'Carrera actualizada con éxito' }
        });
      } else {
        const data = await response.json();
        if (data.errors) {
          setErrors(data.errors); // Manejar errores de validación
        }
      }
    } catch (error) {
      console.error('Error actualizando carrera:', error);
    }
  };

  return (
    <div className="container py-3">
      <div className="row align-items-center justify-content-center">
        <div className="col-6 text-center">
          <form onSubmit={handleSubmit}>
            <label htmlFor="nombre">Ingrese el nombre de la carrera</label>
            <br />
            <input
              type="text"
              name="carrera"
              value={carrera}
              onChange={(e) => setCarrera(e.target.value)}
            />
            <br />
            {errors.carrera && <div className="text-danger">{errors.carrera}</div>}
            <br />
            <label htmlFor="cupo">Ingrese el cupo</label>
            <br />
            <input
              type="number"
              name="cupo"
              value={cupo}
              onChange={(e) => setCupo(e.target.value)}
            />
            <br />
            {errors.cupo && <div className="text-danger">{errors.cupo}</div>}
            <br />
            <button type="submit" className="btn btn-primary me-2">
              Actualizar
            </button>
          </form>
        </div>
      </div>

      {Object.keys(errors).length > 0 && (
        <div className="container" style={{ width: '500px' }}>
          <div className="alert alert-danger">
            <ul>
              {Object.values(errors).map((error, index) => (
                <li key={index}>{error}</li>
              ))}
            </ul>
          </div>
        </div>
      )}
    </div>
  );
};

export default ActualizarCarrera;
