import React, { useState, useEffect } from 'react';
import { useNavigate, useOutletContext } from 'react-router-dom';
import { useNotification } from '../layouts/parcials/notification';

const CrearComision = () => {
  const [grado, setGrado] = useState('');
  const [division, setDivision] = useState('');
  const [detalle, setDetalle] = useState('');
  const [capacidad, setCapacidad] = useState('');
  const [carreraSeleccionada, setCarreraSeleccionada] = useState('');
  const [materias, setMaterias] = useState([]);
  const [materiasSeleccionadas, setMateriasSeleccionadas] = useState([]);
  const [carreras, setCarreras] = useState([]);
  const [isLoadingCarreras, setIsLoadingCarreras] = useState(true);
  const [isLoadingMaterias, setIsLoadingMaterias] = useState(false);

  const { addNotification } = useNotification();

  const navigate = useNavigate();
  const { routes } = useOutletContext();

  // Cargar carreras
  useEffect(() => {
    const fetchCarreras = async () => {
      setIsLoadingCarreras(true);

      try {
        const response = await fetch('http://127.0.0.1:8000/api/horarios/carreras');
        const data = await response.json();

        if (response.ok) {
          setCarreras(data);
        } else {
          addNotification(data.errors, 'danger');
        }
      } catch (error) {
        addNotification(`Error de conexión`, 'danger');
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

      setIsLoadingMaterias(true);

      try {
        const response = await fetch(
          `http://127.0.0.1:8000/api/horarios/uCPlan/${carreraSeleccionada}/relaciones`
        );
        const data = await response.json();

        if (response.ok) {
          setMaterias(data[0]?.plan_estudio?.uc_plan || []);
        } else {
          addNotification(data.errors, 'danger');
        }
      } catch (error) {
        addNotification(`Error de conexión`, 'danger');
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
          state: { successMessage: 'Grado creado con éxito', updated: true }
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
        <div className="col-6 text-center">
          <form onSubmit={handleSubmit}>
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
            <br />
            <br />
            <button
              type="button"
              className="btn btn-danger"
              onClick={() => navigate(`${routes.base}/${routes.comisiones.main}`)}
            >
              Volver Atrás
            </button>
          </form>
        </div>
      </div>
    </div>
  );
};

export default CrearComision;
