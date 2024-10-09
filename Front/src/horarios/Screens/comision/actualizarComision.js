import React, { useState, useEffect } from 'react';
import { useParams, useNavigate } from 'react-router-dom';

const ActualizarComision = () => {
  const { id } = useParams();
  const [anio, setAnio] = useState('');
  const [division, setDivision] = useState('');
  const [idCarrera, setIdCarrera] = useState('');
  const [capacidad, setCapacidad] = useState('');
  const [carreras, setCarreras] = useState([]);
  const [errors, setErrors] = useState({});
  const navigate = useNavigate();

  // Obtener comision y carreras
  useEffect(() => {
    const obtenerDatos = async () => {
      try {
        // Obtener detalles de la comision
        const comisionResponse = await fetch(`/api/comisiones/${id}`);
        if (!comisionResponse.ok) throw new Error('Error al obtener la comision');
        const comisionData = await comisionResponse.json();

        // Obtener carreras
        const carrerasResponse = await fetch('/api/carreras');
        if (!carrerasResponse.ok) throw new Error('Error al obtener las carreras');
        const carrerasData = await carrerasResponse.json();

        // Establecer los datos en el estado
        setAnio(comisionData.anio);
        setDivision(comisionData.division);
        setIdCarrera(comisionData.id_carrera);
        setCapacidad(comisionData.capacidad);
        setCarreras(carrerasData);
      } catch (error) {
        setErrors({ fetch: error.message });
      }
    };
    obtenerDatos();
  }, [id]);

  const handleSubmit = async (e) => {
    e.preventDefault();
    setErrors({}); // Limpiar errores antes de intentar el envío

    const formData = {
      anio,
      division,
      id_carrera: idCarrera,
      capacidad
    };

    try {
      const response = await fetch(`/api/comisiones/${id}`, {
        method: 'PUT',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-Token': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify(formData)
      });

      if (response.ok) {
        navigate('/horarios/comisiones'); // Redirigir después de la actualización
      } else {
        const errorData = await response.json();
        setErrors(errorData.errors || {});
      }
    } catch (error) {
      setErrors({ form: error.message });
    }
  };

  return (
    <div className="container py-3">
      <div className="row align-items-center justify-content-center">
        <div className="col-6 text-center">
          <form onSubmit={handleSubmit}>
            <label htmlFor="anio">Ingrese el año</label>
            <br />
            <input
              type="number"
              name="anio"
              value={anio}
              onChange={(e) => setAnio(e.target.value)}
            />
            <br />
            <br />
            {errors.anio && <div className="text-danger">{errors.anio}</div>}

            <label htmlFor="division">Ingrese la división</label>
            <br />
            <input
              type="number"
              name="division"
              value={division}
              onChange={(e) => setDivision(e.target.value)}
            />
            <br />
            <br />
            {errors.division && <div className="text-danger">{errors.division}</div>}

            <label htmlFor="id_carrera">Selecciona una carrera:</label>
            <br />
            <select
              name="id_carrera"
              value={idCarrera}
              onChange={(e) => setIdCarrera(e.target.value)}
            >
              <option value="">Selecciona una carrera</option>
              {carreras.map((carrera) => (
                <option key={carrera.id_carrera} value={carrera.id_carrera}>
                  {carrera.nombre}
                </option>
              ))}
            </select>
            <br />
            <br />
            {errors.id_carrera && <div className="text-danger">{errors.id_carrera}</div>}

            <label htmlFor="capacidad">Ingrese la capacidad</label>
            <br />
            <input
              type="number"
              name="capacidad"
              value={capacidad}
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

      <div className="container" style={{ width: '500px' }}>
        {errors.form && (
          <div className="alert alert-danger">
            <ul>
              <li>{errors.form}</li>
            </ul>
          </div>
        )}
      </div>
    </div>
  );
};

export default ActualizarComision;
