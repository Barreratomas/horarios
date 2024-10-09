import React, { useState, useEffect } from 'react';
import { useParams, useNavigate } from 'react-router-dom';

const ActualizarMateria = () => {
  const { id } = useParams(); // Obtener el id de la materia de la URL
  const [nombre, setNombre] = useState('');
  const [modulosSemanales, setModulosSemanales] = useState('');
  const [errors, setErrors] = useState([]);
  const [successMessage, setSuccessMessage] = useState('');
  const navigate = useNavigate();

  // Simular obtener la materia por ID (puedes reemplazarlo con una llamada a la API)
  useEffect(() => {
    const obtenerMateria = async () => {
      try {
        const response = await fetch(`/api/materias/${id}`); // Cambiar URL según tu API
        if (response.ok) {
          const data = await response.json();
          setNombre(data.nombre);
          setModulosSemanales(data.modulos_semanales);
        }
      } catch (error) {
        setErrors([error.message]);
      }
    };
    obtenerMateria();
  }, [id]);

  // Manejar el envío del formulario
  const handleSubmit = async (e) => {
    e.preventDefault();
    setErrors([]);

    try {
      const response = await fetch(`/api/materias/${id}`, {
        method: 'PUT',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-Token': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify({
          nombre,
          modulos_semanales: modulosSemanales
        })
      });

      if (response.ok) {
        setSuccessMessage('Materia actualizada correctamente');
        setTimeout(() => navigate('/horarios/materias'), 2000); // Redireccionar después de actualizar
      } else {
        const errorData = await response.json();
        setErrors(errorData.errors || ['Ocurrió un error al actualizar la materia']);
      }
    } catch (error) {
      setErrors([error.message]);
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

            <label htmlFor="modulos_semanales">Ingrese la cantidad de módulos semanales</label>
            <br />
            <input
              type="text"
              name="modulos_semanales"
              value={modulosSemanales}
              onChange={(e) => setModulosSemanales(e.target.value)}
            />
            <br />
            <br />
            {errors.modulos_semanales && (
              <div className="text-danger">{errors.modulos_semanales}</div>
            )}

            <button type="submit" className="btn btn-primary me-2">
              Actualizar
            </button>
          </form>
        </div>
      </div>

      <div className="container" style={{ width: '500px' }}>
        {errors.length > 0 && (
          <div className="alert alert-danger">
            <ul>
              {errors.map((error, index) => (
                <li key={index}>{error}</li>
              ))}
            </ul>
          </div>
        )}

        {successMessage && <div className="alert alert-success">{successMessage}</div>}
      </div>
    </div>
  );
};

export default ActualizarMateria;
