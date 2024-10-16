import React, { useState, useEffect } from 'react';
import { useNavigate, useOutletContext, useParams } from 'react-router-dom';

const ActualizarAsignarAlumno = () => {
  const { alumnoId } = useParams(); // Obtener ID del alumno desde la URL
  const [carrera, setCarrera] = useState(''); // Estado para la carrera seleccionada
  const [grado, setGrado] = useState(''); // Estado para el grado seleccionado
  const [carreras, setCarreras] = useState([]); // Estado para las carreras disponibles
  const [grados, setGrados] = useState([]); // Estado para los grados disponibles
  const [errors, setErrors] = useState({});
  const navigate = useNavigate();
  const { routes } = useOutletContext();

  // Obtener los datos del alumno existente
  useEffect(() => {
    const fetchAlumno = async () => {
      try {
        const response = await fetch(`http://127.0.0.1:8000/api/alumnos/${alumnoId}`);
        const data = await response.json();

        if (response.ok) {
          setCarrera(data.carrera); // Suponiendo que el API devuelve la carrera
          setGrado(data.grado); // Suponiendo que el API devuelve el grado
        } else {
          console.error('Error al obtener el alumno:', data);
        }
      } catch (error) {
        console.error('Error:', error);
      }
    };

    fetchAlumno();
  }, [alumnoId]);

  // Obtener las carreras y grados disponibles
  useEffect(() => {
    const fetchCarrerasYGrados = async () => {
      try {
        const [carrerasResponse, gradosResponse] = await Promise.all([
          fetch('http://127.0.0.1:8000/api/carreras'),
          fetch('http://127.0.0.1:8000/api/grados')
        ]);

        if (!carrerasResponse.ok || !gradosResponse.ok) {
          throw new Error('Error al obtener carreras o grados');
        }

        const carrerasData = await carrerasResponse.json();
        const gradosData = await gradosResponse.json();

        setCarreras(carrerasData); // Asignar carreras desde la API
        setGrados(gradosData); // Asignar grados desde la API
      } catch (error) {
        console.error('Error al obtener carreras y grados:', error);
      }
    };

    fetchCarrerasYGrados();
  }, []);

  const handleSubmit = async (e) => {
    e.preventDefault();
    setErrors({}); // Limpiar errores previos

    try {
      const response = await fetch(`http://127.0.0.1:8000/api/alumnos/actualizar/${alumnoId}`, {
        method: 'PUT',
        headers: {
          'Content-Type': 'application/json'
        },
        body: JSON.stringify({ carrera, grado })
      });

      if (response.ok) {
        navigate(`${routes.base}/${routes.alumnos.main}`, {
          state: { successMessage: 'Alumno actualizado con éxito' }
        });
      } else {
        const data = await response.json();
        if (data.errors) {
          setErrors(data.errors); // Mostrar errores de validación si los hay
        }
      }
    } catch (error) {
      console.error('Error al actualizar el alumno:', error);
    }
  };

  return (
    <div className="container py-3">
      <div className="row align-items-center justify-content-center">
        <div className="col-6 text-center">
          <form onSubmit={handleSubmit}>
            <label htmlFor="carrera">Seleccione la carrera</label>
            <br />
            <select
              name="carrera"
              value={carrera}
              onChange={(e) => setCarrera(e.target.value)}
              required
            >
              <option value="">Seleccione una carrera</option>
              {carreras.map((c) => (
                <option key={c.id} value={c.nombre}>
                  {c.nombre}
                </option>
              ))}
            </select>
            <br />
            {errors.carrera && <div className="text-danger">{errors.carrera}</div>}
            <br />

            <label htmlFor="grado">Seleccione el grado</label>
            <br />
            <select name="grado" value={grado} onChange={(e) => setGrado(e.target.value)} required>
              <option value="">Seleccione un grado</option>
              {grados.map((g) => (
                <option key={g.id} value={g.nombre}>
                  {g.nombre}
                </option>
              ))}
            </select>
            <br />
            {errors.grado && <div className="text-danger">{errors.grado}</div>}
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

export default ActualizarAsignarAlumno;
