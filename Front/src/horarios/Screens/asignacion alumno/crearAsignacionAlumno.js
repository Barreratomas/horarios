import React, { useState, useEffect } from 'react';
import { useNavigate, useOutletContext } from 'react-router-dom'; // Para redirigir después de la creación

const CrearAsignacionAlumno = () => {
  const [alumnos, setAlumnos] = useState([]);
  const [grados, setGrados] = useState([]);
  const [dni, setDni] = useState('');
  const [grado, setGrado] = useState('');
  const [division, setDivision] = useState('');
  const [errors, setErrors] = useState({});

  const navigate = useNavigate();
  const { routes } = useOutletContext();

  // Cargar alumnos desde la API
  const fetchAlumnos = async () => {
    try {
      const response = await fetch('http://127.0.0.1:8000/api/horarios/alumnos');
      const data = await response.json();
      console.log(data);
      setAlumnos(data);
    } catch (error) {
      console.error('Error cargando alumnos:', error);
    }
  };

  // Cargar grados desde la API
  const fetchGrados = async () => {
    try {
      const response = await fetch('http://127.0.0.1:8000/api/grados');
      const data = await response.json();
      setGrados(data);
    } catch (error) {
      console.error('Error cargando grados:', error);
    }
  };

  // Ejecutar las llamadas a la API al cargar el componente
  useEffect(() => {
    fetchAlumnos();
    fetchGrados();
  }, []);

  // Maneja el envío del formulario
  const handleSubmit = async (e) => {
    e.preventDefault();
    setErrors({}); // Reiniciar los errores antes de la validación

    try {
      const response = await fetch('http://127.0.0.1:8000/api/asignar-alumno', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json'
        },
        body: JSON.stringify({ dni, grado, division })
      });

      if (response.ok) {
        navigate(`${routes.base}/${routes.asignacionesAlumno.main}`, {
          state: { successMessage: 'Asignación creada con éxito' }
        });
      } else {
        const data = await response.json();
        if (data.errors) {
          setErrors(data.errors);
        }
      }
    } catch (error) {
      console.error('Error creando la asignación:', error);
    }
  };

  return (
    <div className="container py-3">
      <div className="row align-items-center justify-content-center">
        <div className="col-6 text-center">
          <form onSubmit={handleSubmit}>
            <label htmlFor="dni">Seleccione el Alumno</label>
            <br />
            <select name="dni" value={dni} onChange={(e) => setDni(e.target.value)}>
              <option value="">Seleccione un alumno</option>
              {alumnos.map((alumno) => (
                <option key={alumno.id_alumno} value={alumno.id_alumno}>
                  {alumno.nombre} {alumno.apellido} - dni: {alumno.DNI}
                </option>
              ))}
            </select>
            <br />
            <br />
            {errors.dni && <div className="text-danger">{errors.dni}</div>}

            <label htmlFor="grado">Seleccione el Grado</label>
            <br />
            <select name="grado" value={grado} onChange={(e) => setGrado(e.target.value)}>
              <option value="">Seleccione un grado</option>
              {grados.map((grado) => (
                <option key={grado.id} value={grado.id}>
                  {grado.nombre}
                </option>
              ))}
            </select>
            <br />
            <br />
            {errors.grado && <div className="text-danger">{errors.grado}</div>}

            <label htmlFor="division">Ingrese la División</label>
            <br />
            <input
              type="text"
              name="division"
              value={division}
              onChange={(e) => setDivision(e.target.value)}
            />
            <br />
            <br />
            {errors.division && <div className="text-danger">{errors.division}</div>}

            <button type="submit" className="btn btn-primary me-2">
              Crear Asignación
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

export default CrearAsignacionAlumno;
