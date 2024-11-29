import React, { useState, useEffect } from 'react';
import { useNavigate, useOutletContext, useParams } from 'react-router-dom';

const ActualizarAsignarAlumno = () => {
  const { alumnoId, idGradoActual } = useParams(); // idGrado será el grado actual
  const [carrera, setCarrera] = useState(''); // Estado para la carrera seleccionada
  const [grado, setGrado] = useState(''); // Estado para el grado seleccionado
  const [carreras, setCarreras] = useState([]); // Estado para las carreras disponibles
  const [grados, setGrados] = useState([]); // Estado para los grados disponibles
  const [errors, setErrors] = useState({});
  const navigate = useNavigate();
  const { routes } = useOutletContext();

  // Obtener las carreras disponibles
  useEffect(() => {
    const fetchCarreras = async () => {
      try {
        const response = await fetch('http://127.0.0.1:8000/api/horarios/carreras');
        if (!response.ok) {
          throw new Error('Error al obtener carreras');
        }
        const carrerasData = await response.json();
        setCarreras(carrerasData); // Asignar carreras desde la API
      } catch (error) {
        console.error('Error al obtener carreras:', error);
      }
    };

    fetchCarreras();
  }, []);

  // Obtener los grados disponibles según la carrera seleccionada
  useEffect(() => {
    if (carrera) {
      const fetchGrados = async () => {
        try {
          const response = await fetch(
            `http://127.0.0.1:8000/api/horarios/carreraGrados/carrera/SinUC/${carrera}`
          );
          if (!response.ok) {
            throw new Error('Error al obtener grados');
          }
          const gradosData = await response.json();
          setGrados(gradosData); // Asignar grados desde la API
        } catch (error) {
          console.error('Error al obtener grados:', error);
        }
      };

      fetchGrados();
    }
  }, [carrera]);

  const handleSubmit = async (e) => {
    e.preventDefault();
    setErrors({}); // Limpiar errores previos

    try {
      const response = await fetch(
        `http://127.0.0.1:8000/api/horarios/alumnoGrados/actualizar/${alumnoId}/${idGradoActual}/${grado}`,
        {
          method: 'PUT',
          headers: {
            'Content-Type': 'application/json'
          }
        }
      );

      if (response.ok) {
        navigate(`${routes.base}/${routes.asignacionesAlumno.main}`, {
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
            {/* Selector de Carrera */}
            <label htmlFor="carrera">Seleccione la carrera</label>
            <br />
            <select
              className="form-select"
              name="carrera"
              value={carrera}
              onChange={(e) => setCarrera(e.target.value)}
              required
            >
              <option value="">Seleccione una carrera</option>
              {carreras.map((c) => (
                <option key={c.id_carrera} value={c.id_carrera}>
                  {c.carrera} {/* Muestra el nombre de la carrera */}
                </option>
              ))}
            </select>
            <br />
            {errors.carrera && <div className="text-danger">{errors.carrera}</div>}
            <br />

            {/* Selector de Grado, solo se muestra después de seleccionar una carrera */}
            {carrera && (
              <>
                <label htmlFor="grado">Seleccione el grado</label>
                <br />
                <select
                  className="form-select"
                  name="grado"
                  value={grado}
                  onChange={(e) => setGrado(e.target.value)}
                  required
                >
                  <option value="">Seleccione un grado</option>
                  {grados.map((g) => (
                    <option key={g.id_grado} value={g.id_grado}>
                      {`${g.grado.detalle} (Capacidad: ${g.grado.capacidad})- ${g.carrera.carrera}`}
                    </option>
                  ))}
                </select>

                <br />
                {errors.grado && <div className="text-danger">{errors.grado}</div>}
                <br />
              </>
            )}

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
