import React, { useState, useEffect } from 'react';
import { useNavigate, useOutletContext, useParams } from 'react-router-dom';

const ActualizarComision = () => {
  const { comisionId } = useParams(); // Obtener ID de la comisión desde la URL
  const [capacidad, setCapacidad] = useState('');
  const [errors, setErrors] = useState({});
  const navigate = useNavigate();
  const { routes } = useOutletContext();

  // Obtener los datos de la comisión existente
  useEffect(() => {
    const fetchComision = async () => {
      try {
        const response = await fetch(`http://127.0.0.1:8000/api/horarios/grados/${comisionId}`);
        const data = await response.json();

        if (response.ok) {
          setCapacidad(data.capacidad);
        } else {
          console.error('Error al obtener la comisión:', data);
        }
      } catch (error) {
        console.error('Error:', error);
      }
    };

    fetchComision();
  }, [comisionId]);

  const handleSubmit = async (e) => {
    e.preventDefault();
    setErrors({}); // Limpiar errores previos

    try {
      const response = await fetch(
        `http://127.0.0.1:8000/api/horarios/grados/actualizar/${comisionId}`,
        {
          method: 'PUT',
          headers: {
            'Content-Type': 'application/json'
          },
          body: JSON.stringify({ capacidad })
        }
      );

      if (response.ok) {
        navigate(`${routes.base}/${routes.comisiones.main}`, {
          state: { successMessage: 'Carrera actualizada con éxito' }
        });
      } else {
        const data = await response.json();
        if (data.errors) {
          setErrors(data.errors); // Mostrar errores de validación si los hay
        }
      }
    } catch (error) {
      console.error('Error al actualizar la comisión:', error);
    }
  };

  return (
    <div className="container py-3">
      <div className="row align-items-center justify-content-center">
        <div className="col-6 text-center">
          <form onSubmit={handleSubmit}>
            <label htmlFor="capacidad">Ingrese la capacidad</label>
            <br />
            <input
              type="number"
              name="capacidad"
              value={capacidad}
              onChange={(e) => setCapacidad(e.target.value)}
            />
            <br />
            {errors.capacidad && <div className="text-danger">{errors.capacidad}</div>}
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

export default ActualizarComision;
