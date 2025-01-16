import React, { useState } from 'react';
import { useNavigate, useOutletContext } from 'react-router-dom'; // Para redirigir después de la creación
import { useNotification } from '../layouts/parcials/notification';

const CrearAula = () => {
  const [nombre, setNombre] = useState('');
  const [tipoAula, setTipoAula] = useState('');
  const [capacidad, setCapacidad] = useState('');

  const navigate = useNavigate();
  const { routes } = useOutletContext();

  const { addNotification } = useNotification();

  // Maneja el envío del formulario
  const handleSubmit = async (e) => {
    e.preventDefault();

    try {
      const response = await fetch('http://127.0.0.1:8000/api/horarios/aulas/guardar', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json'
        },
        body: JSON.stringify({ nombre, tipo_aula: tipoAula, capacidad })
      });

      if (response.ok) {
        console.log();
        navigate(`${routes.base}/${routes.aulas.main}`, {
          state: { successMessage: 'Aula creada con éxito', updated: true }
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
            {/* Manejar errores de capacidad */}
            <button type="submit" className="btn btn-primary me-2">
              Crear
            </button>
            <br />
            <br />
            <button
              type="button"
              className="btn btn-danger"
              onClick={() => navigate(`${routes.base}/${routes.aulas.main}`)}
            >
              Volver Atrás
            </button>
          </form>
        </div>
      </div>
    </div>
  );
};

export default CrearAula;
