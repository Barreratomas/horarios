import React, { useState, useEffect } from 'react';
import { useNavigate, useOutletContext } from 'react-router-dom';

const CrearComision = () => {
  const [grado, setGrado] = useState('');
  const [division, setDivision] = useState('');
  const [detalle, setDetalle] = useState('');
  const [capacidad, setCapacidad] = useState('');
  const [carreraSeleccionada, setCarreraSeleccionada] = useState('');
  const [materias, setMaterias] = useState([]);
  const [materiasSeleccionadas, setMateriasSeleccionadas] = useState([]);
  const [carreras, setCarreras] = useState([]);
  const [errors, setErrors] = useState([]);
  const [isLoadingCarreras, setIsLoadingCarreras] = useState(true);
  const [isLoadingMaterias, setIsLoadingMaterias] = useState(false);
  const [fetchError, setFetchError] = useState('');

  const navigate = useNavigate();
  const { routes } = useOutletContext();

  // Cargar carreras
  useEffect(() => {
    const fetchCarreras = async () => {
      setFetchError('');
      setIsLoadingCarreras(true);

      try {
        const response = await fetch('http://127.0.0.1:8000/api/horarios/carreras');
        if (response.ok) {
          const data = await response.json();
          setCarreras(data);
        } else {
          setFetchError('Error al cargar las carreras.');
        }
      } catch (error) {
        setFetchError('Error de red al intentar cargar las carreras.');
      } finally {
        setIsLoadingCarreras(false);
      }
    };

    fetchCarreras();
  }, []);

  // Cargar materias asociadas a la carrera seleccionada
  useEffect(() => {
    const fetchMaterias = async () => {
      if (!carreraSeleccionada) {
        setMaterias([]);
        return;
      }

      setFetchError('');
      setIsLoadingMaterias(true);

      try {
        const response = await fetch(
          `http://127.0.0.1:8000/api/horarios/uCPlan/${carreraSeleccionada}/relaciones`
        );
        if (response.ok) {
          const data = await response.json();
          setMaterias(data[0]?.plan_estudio?.uc_plan || []);
        } else {
          setFetchError('Error al cargar las materias.');
        }
      } catch (error) {
        setFetchError('Error de red al intentar cargar las materias.');
      } finally {
        setIsLoadingMaterias(false);
      }
    };

    fetchMaterias();
  }, [carreraSeleccionada]);

  // Manejar la selección de materias
  const handleCheckboxChange = (idMateria) => {
    setMateriasSeleccionadas((prev) =>
      prev.includes(idMateria) ? prev.filter((id) => id !== idMateria) : [...prev, idMateria]
    );
  };

  // Validar y enviar el formulario
  const handleSubmit = async (e) => {
    e.preventDefault();
    setErrors([]);

    if (capacidad <= 0) {
      setErrors(['La capacidad debe ser un número positivo.']);
      return;
    }

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
          id_carrera: carreraSeleccionada,
          materias: materiasSeleccionadas
        })
      });

      if (response.ok) {
        navigate(`${routes.base}/${routes.comisiones.main}`, {
          state: { successMessage: 'Comisión creada con éxito' }
        });
      } else {
        const data = await response.json();
        if (data.errors) {
          setErrors(data.errors);
        }
      }
    } catch (error) {
      console.error('Error creando la comisión:', error);
      setErrors(['Hubo un error al intentar crear la comisión.']);
    }
  };

  return (
    <div className="container py-3">
      <div className="row align-items-center justify-content-center">
        <div className="col-6 text-center">
          <form onSubmit={handleSubmit}>
            {fetchError && <div className="alert alert-danger">{fetchError}</div>}

            <label htmlFor="carrera">Seleccione una carrera</label>
            <select
              className="form-select"
              name="carrera"
              value={carreraSeleccionada}
              onChange={(e) => setCarreraSeleccionada(e.target.value)}
              disabled={isLoadingCarreras}
              required
            >
              <option value="">Seleccione una carrera</option>
              {carreras.map((carrera) => (
                <option key={carrera.id_carrera} value={carrera.id_carrera}>
                  {carrera.carrera}
                </option>
              ))}
            </select>
            {isLoadingCarreras && <p>Cargando carreras...</p>}

            {isLoadingMaterias ? (
              <p>Cargando materias...</p>
            ) : (
              materias.length > 0 && (
                <div className="materias-list">
                  <h5>Seleccione las materias del plan:</h5>
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
                        {materia.unidad_curricular.unidad_curricular}
                      </label>
                    </div>
                  ))}
                </div>
              )
            )}
            <br />
            <input
              type="number"
              name="grado"
              value={grado}
              onChange={(e) => setGrado(e.target.value)}
              placeholder="Grado"
              required
            />
            <input
              type="number"
              name="division"
              value={division}
              onChange={(e) => setDivision(e.target.value)}
              placeholder="División"
              required
            />
            <input
              type="text"
              name="detalle"
              value={detalle}
              onChange={(e) => setDetalle(e.target.value)}
              placeholder="Detalle"
              maxLength="70"
            />
            <input
              type="number"
              name="capacidad"
              value={capacidad}
              onChange={(e) => setCapacidad(e.target.value)}
              placeholder="Capacidad"
              required
            />

            <button type="submit" className="btn btn-primary mt-3">
              Crear Comisión
            </button>
          </form>
          {errors.length > 0 && (
            <div className="alert alert-danger mt-3">
              <ul>
                {errors.map((error, index) => (
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

export default CrearComision;
