import React, { useState, useEffect } from 'react';
import { useNavigate } from 'react-router-dom';

const CrearHorarioPrevio = () => {
  const [profesores, setProfesores] = useState([]);
  const [docenteSeleccionado, setDocenteSeleccionado] = useState('');
  const [trabajaInstitucion, setTrabajaInstitucion] = useState('no');
  const [horarios, setHorarios] = useState([{ dia: '', hora: '' }]);
  const [errors, setErrors] = useState({});
  const navigate = useNavigate();

  // Obtener la lista de profesores al cargar el componente
  useEffect(() => {
    const obtenerProfesores = async () => {
      try {
        const response = await fetch('http://127.0.0.1:8000/api/profesores', {
          headers: { Accept: 'application/json' }
        });

        if (!response.ok) throw new Error('Error al obtener profesores');
        const data = await response.json();
        setProfesores(data);
      } catch (error) {
        console.error('Error:', error);
      }
    };

    obtenerProfesores();
  }, []);

  // Manejar cambios en el docente seleccionado
  const handleDocenteChange = (event) => {
    setDocenteSeleccionado(event.target.value);
  };

  // Manejar cambios en la opción de trabaja en otra institución
  const handleTrabajaInstitucionChange = (event) => {
    setTrabajaInstitucion(event.target.value);
  };

  // Manejar cambios en los campos dinámicos de días y horas
  const handleHorarioChange = (index, field, value) => {
    const nuevosHorarios = [...horarios];
    nuevosHorarios[index][field] = value;
    setHorarios(nuevosHorarios);
  };

  // Agregar un nuevo campo de día y hora
  const agregarHorario = () => {
    setHorarios([...horarios, { dia: '', hora: '' }]);
  };

  // Eliminar un campo de día y hora
  const eliminarHorario = (index) => {
    const nuevosHorarios = horarios.filter((_, i) => i !== index);
    setHorarios(nuevosHorarios);
  };

  // Manejar el envío del formulario
  const handleSubmit = async (event) => {
    event.preventDefault();

    const formData = new FormData();
    formData.append('docente', docenteSeleccionado);
    formData.append('trabajaInstitucion', trabajaInstitucion);
    formData.append('horarios', JSON.stringify(horarios));

    try {
      const response = await fetch('/api/storeHPD/', {
        method: 'POST',
        body: formData,
        headers: {
          'X-CSRF-Token': document.querySelector('meta[name="csrf-token"]').content
        }
      });

      if (!response.ok) {
        const errorData = await response.json();
        setErrors(errorData.errors || {});
      } else {
        navigate('/ruta-deseada'); // Redirige después de la creación
      }
    } catch (error) {
      console.error('Error al crear el horario:', error);
    }
  };

  return (
    <div className="container py-3">
      <h2>Crear Horario Previo</h2>

      <form onSubmit={handleSubmit}>
        <div className="mb-3">
          <label htmlFor="docente">Seleccione un docente:</label>
          <select
            id="docente"
            className="form-control"
            value={docenteSeleccionado}
            onChange={handleDocenteChange}
            required
          >
            <option value="">-- Seleccione un docente --</option>
            {profesores.map((profesor) => (
              <option key={profesor.id} value={profesor.id}>
                {profesor.nombre}
              </option>
            ))}
          </select>
        </div>

        {docenteSeleccionado && (
          <>
            <div className="mb-3">
              <label>¿Trabaja en otra institución?</label>
              <br />
              <input
                type="radio"
                name="trabajaInstitucion"
                value="si"
                checked={trabajaInstitucion === 'si'}
                onChange={handleTrabajaInstitucionChange}
              />
              <label>Sí</label>
              <br />
              <input
                type="radio"
                name="trabajaInstitucion"
                value="no"
                checked={trabajaInstitucion === 'no'}
                onChange={handleTrabajaInstitucionChange}
              />
              <label>No</label>
            </div>

            {trabajaInstitucion === 'si' && (
              <div id="camposHorarios">
                <h4>Días y Horas</h4>
                {horarios.map((horario, index) => (
                  <div key={index} className="mb-3">
                    <input
                      type="text"
                      placeholder="Día"
                      value={horario.dia}
                      onChange={(e) => handleHorarioChange(index, 'dia', e.target.value)}
                      className="form-control mb-2"
                    />
                    <input
                      type="time"
                      value={horario.hora}
                      onChange={(e) => handleHorarioChange(index, 'hora', e.target.value)}
                      className="form-control mb-2"
                    />
                    <button
                      type="button"
                      className="btn btn-danger"
                      onClick={() => eliminarHorario(index)}
                    >
                      Eliminar
                    </button>
                  </div>
                ))}

                <button type="button" className="btn btn-secondary" onClick={agregarHorario}>
                  Agregar Día y Hora
                </button>
              </div>
            )}
          </>
        )}

        <br />
        <button type="submit" className="btn btn-primary" disabled={!docenteSeleccionado}>
          Siguiente
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
  );
};

export default CrearHorarioPrevio;
