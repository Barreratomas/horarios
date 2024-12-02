import React, { useState, useEffect } from 'react';
import { useNavigate, useOutletContext } from 'react-router-dom';

const CrearHorarioPrevio = () => {
  const [trabajaOtraInstitucion, setTrabajaOtraInstitucion] = useState('');
  const [docenteSeleccionado, setDocenteSeleccionado] = useState('');
  const [docentes, setDocentes] = useState([]);
  const [errors, setErrors] = useState([]);
  const [fechasYHoras, setFechasYHoras] = useState([{ fecha: '', hora: '' }]); // Al menos un formulario

  const [fetchError, setFetchError] = useState('');

  const navigate = useNavigate();
  const { routes } = useOutletContext();

  // Cargar docentes
  useEffect(() => {
    const fetchDocentes = async () => {
      setFetchError('');

      try {
        const response = await fetch('http://127.0.0.1:8000/api/horarios/docentes');
        if (response.ok) {
          const data = await response.json();
          setDocentes(data);
        } else {
          setFetchError('Error al cargar los docentes.');
        }
      } catch (error) {
        setFetchError('Error de red al intentar cargar los docentes.');
      }
    };

    fetchDocentes();
  }, []);

  // Validar y enviar el formulario
  const handleSubmit = async (e) => {
    e.preventDefault();
    setErrors([]);

    try {
      // Estructurar los datos para enviar los arrays 'dia' y 'hora'
      const data = {
        docente: docenteSeleccionado,
        trabajaInstitucion: trabajaOtraInstitucion,
        dia: fechasYHoras.map((item) => item.fecha),
        hora: fechasYHoras.map((item) => item.hora)
      };

      const response = await fetch(
        'http://127.0.0.1:8000/api/horarios/horariosPreviosDocentes/guardar',
        {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json'
          },
          body: JSON.stringify(data)
        }
      );

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

  // Función para agregar un formulario de fecha y hora
  const agregarFormularioFechaHora = () => {
    setFechasYHoras([...fechasYHoras, { fecha: '', hora: '' }]);
  };

  // Función para manejar cambios en los formularios de fecha y hora
  const manejarCambioFechaHora = (index, event) => {
    const newFechasYHoras = [...fechasYHoras];
    newFechasYHoras[index][event.target.name] = event.target.value;
    setFechasYHoras(newFechasYHoras);
  };

  // Función para eliminar un formulario de fecha y hora, pero asegurando que al menos quede uno
  const eliminarFormularioFechaHora = (index) => {
    if (fechasYHoras.length > 1) {
      const newFechasYHoras = fechasYHoras.filter((_, i) => i !== index);
      setFechasYHoras(newFechasYHoras);
    }
  };

  return (
    <div className="container py-3">
      <div className="row align-items-center justify-content-center">
        <div className="col-6 text-center">
          <form onSubmit={handleSubmit}>
            {fetchError && <div className="alert alert-danger">{fetchError}</div>}

            <label htmlFor="docente">Seleccione un docente</label>
            <select
              className="form-select"
              name="docente"
              value={docenteSeleccionado}
              onChange={(e) => setDocenteSeleccionado(e.target.value)}
              required
            >
              <option value="">Seleccione un docente</option>
              {docentes.map((docente) => (
                <option key={docente.id_docente} value={docente.id_docente}>
                  {docente.nombre} {docente.apellido} | dni: {docente.DNI}
                </option>
              ))}
            </select>

            <br />
            {/* Mostrar opciones solo si se seleccionó un docente */}
            {docenteSeleccionado && (
              <>
                <label>¿Trabaja en otra institución?</label>
                <div className="mb-3 d-flex justify-content-center align-items-center gap-5">
                  <div>
                    <input
                      type="radio"
                      name="trabajaInstitucion"
                      value="si"
                      checked={trabajaOtraInstitucion === 'si'}
                      onChange={(e) => setTrabajaOtraInstitucion(e.target.value)}
                    />
                    <label>Sí</label>
                  </div>
                  <div>
                    <input
                      type="radio"
                      name="trabajaInstitucion"
                      value="no"
                      checked={trabajaOtraInstitucion === 'no'}
                      onChange={(e) => setTrabajaOtraInstitucion(e.target.value)}
                    />
                    <label>No</label>
                  </div>
                </div>
                {trabajaOtraInstitucion === 'si' && (
                  <>
                    {/* Mostrar múltiples formularios de fecha y hora */}
                    {fechasYHoras.map((_, index) => (
                      <div key={index}>
                        <div className="mb-3">
                          <label htmlFor={`fecha-${index}`}>Seleccione la fecha</label>
                          <input
                            type="date"
                            className="form-control"
                            id={`fecha-${index}`}
                            name="fecha"
                            value={fechasYHoras[index].fecha}
                            onChange={(e) => manejarCambioFechaHora(index, e)}
                            required
                          />
                        </div>
                        <div className="mb-3">
                          <label htmlFor={`hora-${index}`}>Seleccione la hora</label>
                          <input
                            type="time"
                            className="form-control"
                            id={`hora-${index}`}
                            name="hora"
                            value={fechasYHoras[index].hora}
                            onChange={(e) => manejarCambioFechaHora(index, e)}
                            required
                          />
                        </div>
                        <button
                          type="button"
                          className="btn btn-danger"
                          onClick={() => eliminarFormularioFechaHora(index)}
                        >
                          Eliminar
                        </button>
                      </div>
                    ))}
                    <button
                      type="button"
                      className="btn btn-secondary mt-3"
                      onClick={agregarFormularioFechaHora}
                    >
                      Agregar otra fecha y hora
                    </button>
                  </>
                )}
              </>
            )}

            <button type="submit" className="btn btn-primary mt-3">
              Crear horario previo
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

export default CrearHorarioPrevio;
