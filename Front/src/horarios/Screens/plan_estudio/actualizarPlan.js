import React, { useState, useEffect } from 'react';
import { useNavigate, useOutletContext, useParams } from 'react-router-dom';

const ActualizarPlan = () => {
  const [detalle, setDetalle] = useState('');
  const [fechaInicio, setFechaInicio] = useState('');
  const [fechaFin, setFechaFin] = useState('');
  const [materias, setMaterias] = useState([]);
  const [selectedMaterias, setSelectedMaterias] = useState([]); // Arreglo de IDs seleccionados
  const [errors, setErrors] = useState([]);

  const navigate = useNavigate();
  const { routes } = useOutletContext();
  const { planId } = useParams(); // Asumiendo que el ID del plan se pasa como parámetro en la URL

  // Obtener todas las materias al cargar el componente
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

    // Obtener información del plan a actualizar
    const fetchPlan = async () => {
      try {
        const response = await fetch(`http://127.0.0.1:8000/api/horarios/planEstudio/${planId}`);
        if (!response.ok) throw new Error('Error al obtener el plan');
        const data = await response.json();

        // Asignar los datos del plan al estado
        setDetalle(data.detalle);
        setFechaInicio(data.fecha_inicio);
        setFechaFin(data.fecha_fin);
        setSelectedMaterias(data.materias); // Asumiendo que 'materias' es un array de IDs
      } catch (error) {
        console.error('Error al obtener el plan:', error);
        setErrors([error.message || 'Servidor fuera de servicio...']);
      }
    };

    fetchMaterias();
    fetchPlan();
  }, [planId]);

  // Manejar selección de una materia en el checkbox
  const handleCheckboxChange = (materiaId) => {
    if (selectedMaterias.includes(materiaId)) {
      setSelectedMaterias((prev) => prev.filter((id) => id !== materiaId)); // Remover del array
    } else {
      setSelectedMaterias((prev) => [...prev, materiaId]); // Agregar al array
    }
  };

  // Manejar envío del formulario
  const handleSubmit = async (e) => {
    e.preventDefault();
    setErrors([]); // Reiniciar errores

    try {
      const response = await fetch(`http://127.0.0.1:8000/api/planes/actualizar/${planId}`, {
        method: 'PUT',
        headers: {
          'Content-Type': 'application/json'
        },
        body: JSON.stringify({
          detalle,
          fecha_inicio: fechaInicio,
          fecha_fin: fechaFin,
          materias: selectedMaterias
        })
      });

      if (response.ok) {
        navigate(`${routes.base}/${routes.planes.main}`, {
          state: { successMessage: 'Plan actualizado con éxito' }
        });
      } else {
        const data = await response.json();
        if (data.errors) {
          setErrors(data.errors); // Manejar errores de validación
        }
      }
    } catch (error) {
      console.error('Error actualizando plan:', error);
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
              maxLength="70"
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

            <label>Seleccione las materias</label>
            <div className="materias-list">
              {materias.map((materia) => (
                <div key={materia.id_u} className="form-check">
                  <input
                    className="form-check-input"
                    type="checkbox"
                    value={materia.id_u}
                    id={`materia-${materia.id_u}`}
                    checked={selectedMaterias.includes(materia.id_uc)} // Marcar checkbox si está seleccionado
                    onChange={() => handleCheckboxChange(materia.id_u)} // Llamar a la función con el ID
                  />
                  <label className="form-check-label" htmlFor={`materia-${materia.id_u}`}>
                    {materia.unidad_curricular}
                  </label>
                </div>
              ))}
            </div>

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

export default ActualizarPlan;
