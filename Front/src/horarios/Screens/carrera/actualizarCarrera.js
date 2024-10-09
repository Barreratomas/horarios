import React, { useEffect, useState } from 'react';
import { useParams } from 'react-router-dom';

const ActualizarCarrera = () => {
  const { id } = useParams(); // Obtener el id de la carrera desde la URL
  const [nombre, setNombre] = useState('');
  const [errors, setErrors] = useState([]);

  // Función para obtener los datos de la carrera
  const fetchCarrera = async () => {
    try {
      const response = await fetch(`/api/carreras/${id}`);
      const data = await response.json();
      if (response.ok) {
        setNombre(data.nombre); // Asignar el nombre de la carrera a la variable de estado
      } else {
        console.error('Error al obtener los datos de la carrera:', data);
      }
    } catch (error) {
      console.error('Error:', error);
    }
  };

  // Cargar los datos de la carrera al montar el componente
  useEffect(() => {
    fetchCarrera();
  }, [id]);

  // Función para manejar el envío del formulario
  const handleSubmit = async (e) => {
    e.preventDefault();
    setErrors([]); // Reiniciar errores

    // Enviar solicitud PUT a la API
    try {
      const response = await fetch(`/api/carreras/${id}`, {
        method: 'PUT',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-Token': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({ nombre })
      });

      const data = await response.json();

      if (response.ok) {
        // Redirigir o mostrar mensaje de éxito
        alert('Carrera actualizada con éxito');
      } else {
        // Manejar errores de validación
        if (data.errors) {
          setErrors(data.errors);
        }
      }
    } catch (error) {
      console.error('Error al actualizar la carrera:', error);
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
            <br />
            <button type="submit" className="btn btn-primary me-2">
              Actualizar
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

export default ActualizarCarrera;
