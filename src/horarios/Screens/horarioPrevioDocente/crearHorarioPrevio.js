import React, { useState, useEffect } from 'react';
import { useNavigate, useOutletContext } from 'react-router-dom';

const CrearHorarioPrevio = () => {
  const [docenteSeleccionado, setDocenteSeleccionado] = useState('');
  const [docentes, setDocentes] = useState([]);
  const [errors, setErrors] = useState([]);
  const [diasYHoras, setDiasYHoras] = useState([{ dia: '', hora: '' }]); // Al menos un formulario

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
        id_docente: docenteSeleccionado,
        dia: diasYHoras.map((item) => item.dia),
        hora: diasYHoras.map((item) => item.hora)
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
        navigate(`${routes.base}/${routes.horariosPreviosDocente.main}`, {
          state: { successMessage: 'Horario previo creado con éxito' }
        });
      } else {
        const data = await response.json();
        if (data.errors) {
          setErrors(data.errors);
        }
      }
    } catch (error) {
      console.error('Error creando el horario previo:', error);
      setErrors(['Hubo un error al intentar crear el horario previo del docente.']);
    }
  };

  // Función para agregar un formulario de día y hora
  const agregarFormularioDiaHora = () => {
    setDiasYHoras([...diasYHoras, { dia: '', hora: '' }]);
  };

  // Función para manejar cambios en los formularios de día y hora
  const manejarCambioDiaHora = (index, event) => {
    const newDiasYHoras = [...diasYHoras];
    newDiasYHoras[index][event.target.name] = event.target.value;
    setDiasYHoras(newDiasYHoras);
  };

  // Función para eliminar un formulario de día y hora, pero asegurando que al menos quede uno
  const eliminarFormularioDiaHora = (index) => {
    if (diasYHoras.length > 1) {
      const newDiasYHoras = diasYHoras.filter((_, i) => i !== index);
      setDiasYHoras(newDiasYHoras);
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
                {/* Mostrar múltiples formularios de día y hora */}
                {diasYHoras.map((_, index) => (
                  <div key={index}>
                    <div className="mb-3">
                      <label htmlFor={`dia-${index}`}>Seleccione el día</label>
                      <select
                        className="form-select"
                        id={`dia-${index}`}
                        name="dia"
                        value={diasYHoras[index].dia}
                        onChange={(e) => manejarCambioDiaHora(index, e)}
                        required
                      >
                        <option value="">Seleccione un día</option>
                        <option value="lunes">Lunes</option>
                        <option value="martes">Martes</option>
                        <option value="miercoles">Miércoles</option>
                        <option value="jueves">Jueves</option>
                        <option value="viernes">Viernes</option>
                      </select>
                    </div>
                    <div className="mb-3">
                      <label htmlFor={`hora-${index}`}>Seleccione la hora</label>
                      <input
                        type="time"
                        className="form-control"
                        id={`hora-${index}`}
                        name="hora"
                        value={diasYHoras[index].hora}
                        onChange={(e) => manejarCambioDiaHora(index, e)}
                        required
                        min="18:50"
                        max="22:30"
                      />
                    </div>
                    <button
                      type="button"
                      className="btn btn-danger"
                      onClick={() => eliminarFormularioDiaHora(index)}
                    >
                      Eliminar
                    </button>
                  </div>
                ))}
                <button
                  type="button"
                  className="btn btn-secondary mt-3"
                  onClick={agregarFormularioDiaHora}
                >
                  Agregar otro día y hora
                </button>
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
