import React, { useState, useEffect } from 'react';
import { useNavigate, useOutletContext } from 'react-router-dom';

const CrearComision = () => {
  const [grado, setGrado] = useState('');
  const [division, setDivision] = useState('');
  const [detalle, setDetalle] = useState('');
  const [capacidad, setCapacidad] = useState('');
  const [carreraSeleccionada, setCarreraSeleccionada] = useState('');
  const [carreras, setCarreras] = useState([]); // Estado para almacenar las carreras
  const [errors, setErrors] = useState([]);

  const navigate = useNavigate();
  const { routes } = useOutletContext();

  // Obtener las carreras desde la API al montar el componente
  useEffect(() => {
    const fetchCarreras = async () => {
      try {
        const response = await fetch('http://127.0.0.1:8000/api/horarios/carreras');
        if (response.ok) {
          const data = await response.json();
          console.log(data);
          setCarreras(data); // Asume que `data` es un array de carreras
        } else {
          console.error('Error al obtener las carreras:', response.statusText);
        }
      } catch (error) {
        console.error('Error en la solicitud:', error);
      }
    };

    fetchCarreras();
  }, []);

  // Función para manejar el envío del formulario
  const handleSubmit = async (e) => {
    e.preventDefault();
    setErrors([]);

    try {
      const response = await fetch('http://127.0.0.1:8000/api/horarios/grados/guardar', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json'
        },
        body: JSON.stringify({
          grado,
          division,
          detalle,
          capacidad,
          id_carrera: carreraSeleccionada // Incluye la carrera seleccionada
        })
      });

      if (response.ok) {
        navigate(`${routes.base}/${routes.comisiones.main}`, {
          state: { successMessage: 'Grado creado con éxito' }
        });
      } else {
        const data = await response.json();
        if (data.errors) {
          setErrors(data.errors);
        }
      }
    } catch (error) {
      console.error('Error creando grado:', error);
    }
  };

  return (
    <div className="container py-3">
      <div className="row align-items-center justify-content-center">
        <div className="col-6 text-center">
          <form onSubmit={handleSubmit}>
            {/* Selección de carrera */}
            <label htmlFor="carrera">Seleccione una carrera</label>
            <br />
            <select
              className="form-select"
              name="carrera"
              value={carreraSeleccionada}
              onChange={(e) => setCarreraSeleccionada(e.target.value)}
              required
            >
              <option value="">Seleccione una carrera</option>
              {carreras.map((carrera) => (
                <option key={carrera.id_carrera} value={carrera.id_carrera}>
                  {carrera.carrera}
                </option>
              ))}
            </select>
            <br />
            <br />

            <label htmlFor="grado">Ingrese el grado</label>
            <br />
            <input
              type="number"
              name="grado"
              value={grado}
              onChange={(e) => setGrado(e.target.value)}
              required
            />
            <br />
            <br />

            <label htmlFor="division">Ingrese la división</label>
            <br />
            <input
              type="number"
              name="division"
              value={division}
              onChange={(e) => setDivision(e.target.value)}
              required
            />
            <br />
            <br />

            <label htmlFor="detalle">Ingrese el detalle</label>
            <br />
            <input
              type="text"
              name="detalle"
              value={detalle}
              onChange={(e) => setDetalle(e.target.value)}
              maxLength="70"
            />
            <br />
            <br />

            <label htmlFor="capacidad">Ingrese la capacidad</label>
            <br />
            <input
              type="number"
              name="capacidad"
              value={capacidad}
              onChange={(e) => setCapacidad(e.target.value)}
              required
            />
            <br />
            <br />

            <button type="submit" className="btn btn-primary me-2">
              Crear
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

export default CrearComision;
