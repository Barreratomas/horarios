import React, { useState } from 'react';
import { useNavigate, useOutletContext, useParams } from 'react-router-dom'; // Para redirigir después de la actualización

const ActualizarAula = () => {
  const [nombre, setNombre] = useState('');
  const [tipoAula, setTipoAula] = useState('');
  const [capacidad, setCapacidad] = useState('');
  const [errors, setErrors] = useState({});
  const navigate = useNavigate();
  const { routes } = useOutletContext();
  const { aulaId } = useParams();

  const handleSubmit = async (e) => {
    e.preventDefault();
    setErrors({});

    try {
      const response = await fetch(
        `http://127.0.0.1:8000/api/horarios/aulas/actualizar/${aulaId}`,
        {
          method: 'PUT',
          headers: {
            'Content-Type': 'application/json'
          },
          body: JSON.stringify({ nombre, tipo_aula: tipoAula, capacidad })
        }
      );

      if (response.ok) {
        navigate(`${routes.base}/${routes.aulas.main}`); // Redirigir a la lista de aulas después de actualizar con éxito
      } else {
        const data = await response.json();
        if (data.errors) {
          setErrors(data.errors); // Manejar errores de validación
        }
      }
    } catch (error) {
      console.error('Error actualizando aula:', error);
    }
  };

  return (
    <div className="container py-3">
      <div className="row align-items-center justify-content-center">
        <div className="col-6 text-center">
          <form onSubmit={handleSubmit}>
            <label htmlFor="nombre">Ingrese el nombre</label>
            <br />
            <input
              type="text"
              name="nombre"
              value={nombre} // Vincular el estado del nombre al input
              onChange={(e) => setNombre(e.target.value)}
            />
            <br />
            <br />
            {errors.nombre && <div className="text-danger">{errors.nombre}</div>}
            <label htmlFor="tipo_aula">Ingrese el tipo de aula</label>
            <br />
            <input
              type="text"
              name="tipo_aula"
              value={tipoAula} // Vincular el estado del tipo de aula al input
              onChange={(e) => setTipoAula(e.target.value)}
            />
            <br />
            <br />
            {errors.tipo_aula && <div className="text-danger">{errors.tipo_aula}</div>}
            <label htmlFor="capacidad">Ingrese la capacidad</label>
            <br />
            <input
              type="number"
              name="capacidad"
              value={capacidad} // Vincular el estado de capacidad al input
              onChange={(e) => setCapacidad(e.target.value)}
            />
            <br />
            <br />
            {errors.capacidad && <div className="text-danger">{errors.capacidad}</div>}
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

export default ActualizarAula;
