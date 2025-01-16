import React, { useState, useEffect } from 'react';
import { useNavigate, useOutletContext } from 'react-router-dom';
import { useNotification } from '../layouts/parcials/notification';

const CrearAsignacionAlumno = () => {
  const [alumnos, setAlumnos] = useState([]);
  const [grados, setGrados] = useState([]);
  const [dni, setDni] = useState('');
  const [grado, setGrado] = useState('');

  const navigate = useNavigate();
  const { routes } = useOutletContext();

  const { addNotification } = useNotification();

  // Cargar alumnos desde la API
  const fetchAlumnos = async () => {
    try {
      const response = await fetch('http://127.0.0.1:8000/api/horarios/alumnos');
      const data = await response.json();
      setAlumnos(data);
    } catch (error) {
      addNotification(`Error de conexión`, 'danger');
    }
  };

  // Cargar grados y carreras desde la API
  const fetchGrados = async () => {
    try {
      const response = await fetch('http://127.0.0.1:8000/api/horarios/carreraGrados');
      const data = await response.json();
      console.log(data);
      setGrados(data);
    } catch (error) {
      addNotification(`Error de conexión`, 'danger');
    }
  };

  useEffect(() => {
    fetchAlumnos();
    fetchGrados();
  }, []);

  const handleSubmit = async (e) => {
    e.preventDefault();
    const [id_grado, id_carrera] = grado.split('-');
    console.log(`id_alumno: ${dni}, grado: ${id_grado}, carrera: ${id_carrera}`);
    try {
      const response = await fetch(
        `http://127.0.0.1:8000/api/horarios/alumnoGrados/guardar/${dni}/${id_grado}`,
        {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json'
          }
        }
      );
      if (response.ok) {
        navigate(`${routes.base}/${routes.asignacionesAlumno.main}`, {
          state: { successMessage: 'El alumno fue asignado con éxito', updated: true }
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
        <div className="col-6">
          <form onSubmit={handleSubmit} className="text-center">
            {/* Selección de Alumno */}
            <div className="mb-3">
              <label htmlFor="dni" className="form-label">
                Seleccione el Alumno
              </label>
              <select
                name="dni"
                value={dni}
                onChange={(e) => setDni(e.target.value)}
                className="form-select"
                required
              >
                <option value="">Seleccione un alumno</option>
                {alumnos.map((alumno) => (
                  <option key={alumno.id_alumno} value={alumno.id_alumno}>
                    {`${alumno.nombre} ${alumno.apellido} - DNI: ${alumno.DNI}`}
                  </option>
                ))}
              </select>
            </div>

            {/* Selección de Grado y Carrera */}
            <div className="mb-3">
              <label htmlFor="grado" className="form-label">
                Seleccione el Grado y Carrera
              </label>
              <select
                name="grado"
                value={grado}
                onChange={(e) => setGrado(e.target.value)}
                className="form-select"
                required
              >
                <option value="">Seleccione un grado y carrera</option>
                {grados.map((item) => (
                  <option
                    key={`${item.id_grado}-${item.carrera.id_carrera}`}
                    value={`${item.id_grado}-${item.carrera.id_carrera}`}
                  >
                    {`Grado: ${item.grado.grado}, División: ${item.grado.division} - Carrera: ${item.carrera.carrera}`}
                  </option>
                ))}
              </select>
            </div>
            {/* Botón de Envío */}
            <button type="submit" className="btn btn-primary">
              Crear Asignación
            </button>
            <br />
            <br />
            <button
              type="button"
              className="btn btn-danger"
              onClick={() => navigate(`${routes.base}/${routes.asignacionesAlumno.main}`)}
            >
              Volver Atrás
            </button>
          </form>
        </div>
      </div>
    </div>
  );
};

export default CrearAsignacionAlumno;
