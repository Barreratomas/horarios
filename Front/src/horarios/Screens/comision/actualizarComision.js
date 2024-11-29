import React, { useState, useEffect } from 'react';
import { useNavigate, useOutletContext, useParams } from 'react-router-dom';

const ActualizarComision = () => {
  const { comisionId } = useParams(); // Obtener ID de la comisión desde la URL
  const [capacidad, setCapacidad] = useState('');
  const [materias, setMaterias] = useState([]);
  const [materiasSeleccionadas, setMateriasSeleccionadas] = useState([]);
  const [errors, setErrors] = useState({});
  const [fetchError, setFetchError] = useState('');
  const [isLoading, setIsLoading] = useState(true);

  const navigate = useNavigate();
  const { routes } = useOutletContext();

  // Obtener los datos de la comisión existente
  useEffect(() => {
    const fetchComision = async () => {
      try {
        const response = await fetch(`http://127.0.0.1:8000/api/horarios/grados/${comisionId}`);
        const data = await response.json();

        if (response.ok) {
          setCapacidad(data.capacidad);
          setMateriasSeleccionadas(data.materias || []);
        } else {
          setFetchError('Error al obtener el grado.');
        }
      } catch (error) {
        setFetchError('Error de red al intentar cargar los datos del grado.');
      } finally {
        setIsLoading(false);
      }
    };

    fetchComision();
  }, [comisionId]);

  // Cargar materias disponibles
  useEffect(() => {
    const fetchData = async () => {
      try {
        // Obtener las materias asociadas al grado
        const gradoUCResponse = await fetch(
          `http://127.0.0.1:8000/api/horarios/gradoUC/idGrado/relaciones/${comisionId}`
        );
        const gradoUCData = await gradoUCResponse.json();

        if (!gradoUCResponse.ok) {
          throw new Error('Error al cargar las materias asociadas al grado.');
        }

        // Extraer IDs de las materias asociadas al grado
        const materiasAsociadas = gradoUCData.map((item) => item.unidad_curricular.id_uc);
        setMateriasSeleccionadas(materiasAsociadas);

        // Obtener todas las materias disponibles
        const materiasResponse = await fetch('http://127.0.0.1:8000/api/horarios/unidadCurricular');
        const materiasData = await materiasResponse.json();

        if (!materiasResponse.ok) {
          throw new Error('Error al cargar las materias disponibles.');
        }

        setMaterias(materiasData);
      } catch (error) {
        setFetchError(error.message || 'Error de red.');
      } finally {
        setIsLoading(false);
      }
    };

    fetchData();
  }, [comisionId]);

  // Manejar la selección de materias
  const handleCheckboxChange = (idMateria) => {
    setMateriasSeleccionadas((prev) =>
      prev.includes(idMateria) ? prev.filter((id) => id !== idMateria) : [...prev, idMateria]
    );
  };

  // Manejar la actualización
  const handleSubmit = async (e) => {
    e.preventDefault();
    setErrors({}); // Limpiar errores previos

    try {
      const response = await fetch(
        `http://127.0.0.1:8000/api/horarios/grados/actualizar/${comisionId}`,
        {
          method: 'PUT',
          headers: {
            'Content-Type': 'application/json'
          },
          body: JSON.stringify({
            capacidad,
            materias: materiasSeleccionadas
          })
        }
      );

      if (response.ok) {
        navigate(`${routes.base}/${routes.comisiones.main}`, {
          state: { successMessage: 'Comisión actualizada con éxito' }
        });
      } else {
        const data = await response.json();
        if (data.errors) {
          setErrors(data.errors); // Mostrar errores de validación si los hay
        }
      }
    } catch (error) {
      setFetchError('Error al intentar actualizar la comisión.');
    }
  };

  if (isLoading) return <p>Cargando datos de la comisión...</p>;

  return (
    <div className="container py-3">
      <div className="row align-items-center justify-content-center">
        <div className="col-6 text-center">
          <form onSubmit={handleSubmit}>
            {fetchError && <div className="alert alert-danger">{fetchError}</div>}

            <label htmlFor="capacidad">Capacidad</label>
            <input
              type="number"
              name="capacidad"
              value={capacidad}
              onChange={(e) => setCapacidad(e.target.value)}
              placeholder="Capacidad"
              required
            />

            <div className="materias-list mt-3">
              <h5>Seleccione las materias asociadas:</h5>
              {materias.map((materia) => (
                <div key={materia.id_uc} className="form-check">
                  <input
                    className="form-check-input"
                    type="checkbox"
                    value={materia.id_uc}
                    checked={materiasSeleccionadas.includes(materia.id_uc)}
                    id={`materia-${materia.id_uc}`}
                    onChange={() => handleCheckboxChange(materia.id_uc)}
                  />
                  <label className="form-check-label" htmlFor={`materia-${materia.id_uc}`}>
                    {materia.unidad_curricular}
                  </label>
                </div>
              ))}
            </div>

            <button type="submit" className="btn btn-primary mt-3">
              Actualizar Comisión
            </button>
          </form>

          {Object.keys(errors).length > 0 && (
            <div className="alert alert-danger mt-3">
              <ul>
                {Object.values(errors).map((error, index) => (
                  <li key={index}>{error}</li>
                ))}
              </ul>
            </div>
          )}
        </div>
      </div>
    </div>
  );
};

export default ActualizarComision;
