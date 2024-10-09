import React, { useState } from 'react';

const CrearMateria = () => {
  const [nombre, setNombre] = useState('');
  const [modulosSemanales, setModulosSemanales] = useState('');
  const [errors, setErrors] = useState([]);
  const [successMessage, setSuccessMessage] = useState('');

  // Manejar el envío del formulario
  const handleSubmit = async (e) => {
    e.preventDefault();
    setErrors([]); // Limpiar errores anteriores

    try {
      const response = await fetch('/api/materias', {
        // Cambia la URL según tu API
        method: 'POST',
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
        setSuccessMessage('Materia creada correctamente');
        setNombre('');
        setModulosSemanales('');
      } else {
        const errorData = await response.json();
        setErrors(errorData.errors || ['Ocurrió un error al crear la materia']);
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
              Crear
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

export default CrearMateria;
