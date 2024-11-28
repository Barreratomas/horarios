import React, { useState, useEffect } from 'react';
import { useNavigate, useOutletContext } from 'react-router-dom';

const CrearPlan = () => {
  const [detalle, setDetalle] = useState('');
  const [fechaInicio, setFechaInicio] = useState('');
  const [fechaFin, setFechaFin] = useState('');
  const [materias, setMaterias] = useState([]);
  const [selectedMaterias, setSelectedMaterias] = useState([]);
  const [carreras, setCarreras] = useState([]);
  const [selectedCarrera, setSelectedCarrera] = useState('');
  const [errors, setErrors] = useState([]);

  const navigate = useNavigate();
  const { routes } = useOutletContext();

  // Obtener materias y carreras al cargar el componente
  useEffect(() => {
    const fetchMaterias = async () => {
      try {
        const response = await fetch('http://127.0.0.1:8000/api/horarios/unidadCurricular', {
          headers: { Accept: 'application/json' }
        });
        if (!response.ok) throw new Error('Error al obtener materias');
        const data = await response.json();
        setMaterias(data);
      } catch (error) {
        console.error('Error al obtener materias:', error);
        setErrors([error.message || 'Servidor fuera de servicio...']);
      }
    };

    const fetchCarreras = async () => {
      try {
        const response = await fetch('http://127.0.0.1:8000/api/horarios/carreras', {
          headers: { Accept: 'application/json' }
        });
        if (!response.ok) throw new Error('Error al obtener carreras');
        const data = await response.json();
        setCarreras(data);
      } catch (error) {
        console.error('Error al obtener carreras:', error);
        setErrors([error.message || 'Servidor fuera de servicio...']);
      }
    };

    fetchMaterias();
    fetchCarreras();
  }, []);

  // Manejar selección de una materia en el checkbox
  const handleCheckboxChange = (materiaId) => {
    if (selectedMaterias.includes(materiaId)) {
      setSelectedMaterias((prev) => prev.filter((id) => id !== materiaId));
    } else {
      setSelectedMaterias((prev) => [...prev, materiaId]);
    }
  };

  // Manejar envío del formulario
  const handleSubmit = async (e) => {
    e.preventDefault();
    setErrors([]);

    try {
      const response = await fetch('http://127.0.0.1:8000/api/horarios/planEstudio/guardar', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json'
        },
        body: JSON.stringify({
          detalle,
          fecha_inicio: fechaInicio,
          fecha_fin: fechaFin,
          id_carrera: selectedCarrera,
          materias: selectedMaterias
        })
      });

      if (response.ok) {
        navigate(`${routes.base}/${routes.planes.main}`, {
          state: { successMessage: 'Plan creado con éxito' }
        });
      } else {
        const data = await response.json();
        if (data.errors) setErrors(data.errors);
      }
    } catch (error) {
      console.error('Error creando plan:', error);
    }
  };

  return (
    <div className="container py-3">
      <div className="row align-items-center justify-content-center">
        <div className="col-6 text-center">
          <form onSubmit={handleSubmit}>
            <label htmlFor="detalle">Ingrese el detalle</label>
            <br />
            <input
              type="text"
              name="detalle"
              value={detalle}
              onChange={(e) => setDetalle(e.target.value)}
              maxLength="50"
              required
            />
            <br />
            <br />
            {errors.detalle && <div className="text-danger">{errors.detalle}</div>}

            <label htmlFor="fecha_inicio">Ingrese la fecha de inicio</label>
            <br />
            <input
              type="date"
              name="fecha_inicio"
              value={fechaInicio}
              onChange={(e) => setFechaInicio(e.target.value)}
              required
            />
            <br />
            <br />
            {errors.fecha_inicio && <div className="text-danger">{errors.fecha_inicio}</div>}

            <label htmlFor="fecha_fin">Ingrese la fecha de fin</label>
            <br />
            <input
              type="date"
              name="fecha_fin"
              value={fechaFin}
              onChange={(e) => setFechaFin(e.target.value)}
              required
            />
            <br />
            <br />
            {errors.fecha_fin && <div className="text-danger">{errors.fecha_fin}</div>}

            <label htmlFor="carrera">Seleccione la carrera</label>
            <br />
            <select
              className="form-select"
              name="carrera"
              value={selectedCarrera}
              onChange={(e) => setSelectedCarrera(e.target.value)}
              required
            >
              <option value="">Seleccione una carrera...</option>
              {carreras.map((carrera) => (
                <option key={carrera.id_carrera} value={carrera.id_carrera}>
                  {carrera.carrera}
                </option>
              ))}
            </select>
            <br />
            {errors.carrera && <div className="text-danger">{errors.carrera}</div>}

            <label>Seleccione las materias</label>
            <div className="materias-list">
              {materias.map((materia) => (
                <div key={materia.id_uc} className="form-check">
                  <input
                    className="form-check-input"
                    type="checkbox"
                    value={materia.id_uc}
                    id={`materia-${materia.id_uc}`}
                    onChange={() => handleCheckboxChange(materia.id_uc)}
                  />
                  <label className="form-check-label" htmlFor={`materia-${materia.id_uc}`}>
                    {materia.unidad_curricular}
                  </label>
                </div>
              ))}
            </div>
            {errors.materias && <div className="text-danger">{errors.materias}</div>}

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

export default CrearPlan;
