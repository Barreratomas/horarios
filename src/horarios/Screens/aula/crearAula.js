import React, { useState } from 'react';
import { useNavigate, useOutletContext } from 'react-router-dom'; // Para redirigir después de la creación

const CrearAula = () => {
  const [nombre, setNombre] = useState('');
  const [tipoAula, setTipoAula] = useState('');
  const [capacidad, setCapacidad] = useState(''); // Nuevo estado para capacidad
  const [errors, setErrors] = useState({});

  const navigate = useNavigate();
  const { routes } = useOutletContext();

  // Maneja el envío del formulario
  const handleSubmit = async (e) => {
    e.preventDefault();
    setErrors({}); // Reiniciar los errores antes de la validacion

    try {
      const response = await fetch('http://127.0.0.1:8000/api/horarios/aulas/guardar', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json'
        },
        body: JSON.stringify({ nombre, tipo_aula: tipoAula, capacidad })
      });

      if (response.ok) {
        navigate(`${routes.base}/${routes.aulas.main}`, {
          state: { successMessage: 'Aula creada con éxito' }
        });
      } else {
        const data = await response.json();
        if (data.errors) {
          setErrors(data.errors); // Manejar errores de validación
        }
      }
    } catch (error) {
      console.error('Error creando aula:', error);
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
              value={nombre}
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
              value={tipoAula}
              onChange={(e) => setTipoAula(e.target.value)}
            />
            <br />
            <br />
            {errors.tipo_aula && <div className="text-danger">{errors.tipo_aula}</div>}
            <label htmlFor="capacidad">Ingrese la capacidad</label> {/* Nuevo label */}
            <br />
            <input
              type="number" // Cambia el tipo a "number" para capacidad
              name="capacidad"
              value={capacidad}
              onChange={(e) => setCapacidad(e.target.value)} // Maneja el cambio de capacidad
            />
            <br />
            <br />
            {errors.capacidad && <div className="text-danger">{errors.capacidad}</div>}{' '}
            {/* Manejar errores de capacidad */}
            <button type="submit" className="btn btn-primary me-2">
              Crear
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

export default CrearAula;
