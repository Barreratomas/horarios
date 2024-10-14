import React, { useState } from 'react';
import { useNavigate, useOutletContext } from 'react-router-dom'; // Para redirigir después de la creación

const CrearComision = () => {
  const [grado, setGrado] = useState('');
  const [division, setDivision] = useState('');
  const [detalle, setDetalle] = useState('');
  const [capacidad, setCapacidad] = useState('');
  const [errors, setErrors] = useState([]);

  const navigate = useNavigate();
  const { routes } = useOutletContext();

  // Función para manejar el envío del formulario
  const handleSubmit = async (e) => {
    e.preventDefault();
    setErrors([]); // Reiniciar errores

    try {
      const response = await fetch('http://127.0.0.1:8000/api/horarios/grados/guardar', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json'
        },
        body: JSON.stringify({ grado, division, detalle, capacidad })
      });

      if (response.ok) {
        navigate(`${routes.base}/${routes.comisiones.main}`, {
          state: { successMessage: 'Grado creado con éxito' }
        });
      } else {
        const data = await response.json();
        if (data.errors) {
          setErrors(data.errors); // Manejar errores de validación
        }
      }
    } catch (error) {
      console.error('Error creando grado:', error);
    }
  };

  return (
    <div className="container py-3">
      <div className="row align-items-center justify-content-center">
        <div className="col-6 text-center">
          <form onSubmit={handleSubmit}>
            <label htmlFor="grado">Ingrese el grado</label>
            <br />
            <input
              type="number"
              name="grado"
              value={grado}
              onChange={(e) => setGrado(e.target.value)}
              required
            />
            <br />
            <br />
            {errors.grado && <div className="text-danger">{errors.grado}</div>}

            <label htmlFor="division">Ingrese la división</label>
            <br />
            <input
              type="number"
              name="division"
              value={division}
              onChange={(e) => setDivision(e.target.value)}
              required
            />
            <br />
            <br />
            {errors.division && <div className="text-danger">{errors.division}</div>}

            <label htmlFor="detalle">Ingrese el detalle</label>
            <br />
            <input
              type="text"
              name="detalle"
              value={detalle}
              onChange={(e) => setDetalle(e.target.value)}
              maxLength="70"
            />
            <br />
            <br />
            {errors.detalle && <div className="text-danger">{errors.detalle}</div>}

            <label htmlFor="capacidad">Ingrese la capacidad</label>
            <br />
            <input
              type="number"
              name="capacidad"
              value={capacidad}
              onChange={(e) => setCapacidad(e.target.value)}
              required
            />
            <br />
            <br />
            {errors.capacidad && <div className="text-danger">{errors.capacidad}</div>}

            <br />
            <button type="submit" className="btn btn-primary me-2">
              Crear
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

export default CrearComision;
