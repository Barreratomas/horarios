import React, { useState, useEffect } from 'react';
import { useNavigate, useParams, useOutletContext } from 'react-router-dom';

const ActualizarHorarioPrevio = () => {
  const navigate = useNavigate();
  const { hpdId } = useParams(); // Get the schedule ID from the URL
  const { routes } = useOutletContext(); // Routes from the context

  const [trabajaOtraInstitucion, setTrabajaOtraInstitucion] = useState('');
  const [fechasYHoras, setFechasYHoras] = useState([{ fecha: '', hora: '' }]); // At least one form
  const [errors, setErrors] = useState([]);
  const [fetchError, setFetchError] = useState(''); // Error related to fetching data
  const [successMessage, setSuccessMessage] = useState('');

  useEffect(() => {
    const fetchHorario = async () => {
      try {
        const response = await fetch(`http://127.0.0.1:8000/api/horarios_previos/${hpdId}`);
        if (!response.ok) throw new Error('Error al obtener el horario');

        const data = await response.json();
        setTrabajaOtraInstitucion(data.trabajaInstitucion);
        setFechasYHoras(data.fechasYHoras);
      } catch (error) {
        setFetchError(error.message || 'No se pudo cargar el horario');
        setErrors([]);
      }
    };

    fetchHorario();
  }, [hpdId]);

  const handleSubmit = async (e) => {
    e.preventDefault();
    setErrors([]);
    try {
      const data = {
        trabajaInstitucion: trabajaOtraInstitucion,
        dia: fechasYHoras.map((item) => item.fecha),
        hora: fechasYHoras.map((item) => item.hora)
      };

      const response = await fetch(
        `http://127.0.0.1:8000/api/horarios/horariosPreviosDocentes/actualizar/${hpdId}`,
        {
          method: 'PUT',
          headers: {
            'Content-Type': 'application/json'
          },
          body: JSON.stringify(data)
        }
      );

      if (response.ok) {
        setSuccessMessage('Horario actualizado correctamente');
        setTimeout(() => navigate(`${routes.base}/${routes.comisiones.main}`), 3000);
      } else {
        const data = await response.json();
        if (data.errors) {
          setErrors(data.errors);
        }
      }
    } catch (error) {
      console.error('Error actualizando el horario:', error);
      setErrors(['Hubo un error al intentar actualizar el horario.']);
    }
  };

  // Function to add a date-time form
  const agregarFormularioFechaHora = () => {
    setFechasYHoras([...fechasYHoras, { fecha: '', hora: '' }]);
  };

  // Function to handle changes in the date-time forms
  const manejarCambioFechaHora = (index, event) => {
    const newFechasYHoras = [...fechasYHoras];
    newFechasYHoras[index][event.target.name] = event.target.value;
    setFechasYHoras(newFechasYHoras);
  };

  // Function to remove a date-time form, ensuring at least one remains
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

            <button type="submit" className="btn btn-primary mt-3">
              Actualizar horario previo
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

          {successMessage && <div className="alert alert-success mt-3">{successMessage}</div>}
        </div>
      </div>
    </div>
  );
};

export default ActualizarHorarioPrevio;
