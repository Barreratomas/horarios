import React, { useState, useEffect } from 'react';
import { useNavigate, useParams, useOutletContext } from 'react-router-dom';

const ActualizarHorarioPrevio = () => {
  const navigate = useNavigate();
  const { id } = useParams(); // Obtener el ID del horario desde la URL
  const { routes } = useOutletContext(); // Rutas del contexto

  const [docente, setDocente] = useState('');
  const [dia, setDia] = useState('');
  const [hora, setHora] = useState('');
  const [loading, setLoading] = useState(true);
  const [errors, setErrors] = useState([]);
  const [successMessage, setSuccessMessage] = useState('');

  // Cargar la información del horario actual
  useEffect(() => {
    const fetchHorario = async () => {
      setLoading(true);
      try {
        const response = await fetch(`http://127.0.0.1:8000/api/horarios_previos/${id}`);
        if (!response.ok) throw new Error('Error al obtener el horario');

        const data = await response.json();
        setDocente(data.docente_nombre);
        setDia(data.dia);
        setHora(data.hora);
      } catch (error) {
        console.error('Error al obtener horario:', error);
        setErrors([error.message || 'No se pudo cargar el horario']);
      } finally {
        setLoading(false);
      }
    };

    fetchHorario();
  }, [id]);

  // Función para manejar la actualización del horario
  const handleSubmit = async (e) => {
    e.preventDefault();
    try {
      const response = await fetch(`http://127.0.0.1:8000/api/actualizar-h_p_d/{h_p_d}/{dUC}`, {
        method: 'PUT',
        headers: {
          'Content-Type': 'application/json'
        },
        body: JSON.stringify({
          docente_nombre: docente,
          dia,
          hora
        })
      });

      if (!response.ok) throw new Error('Error al actualizar el horario');

      setSuccessMessage('Horario actualizado correctamente');
      setTimeout(() => navigate(routes.base), 3000); // Volver a la lista de horarios
    } catch (error) {
      console.error('Error al actualizar el horario:', error);
      setErrors([error.message || 'No se pudo actualizar el horario']);
    }
  };

  return (
    <div className="container py-3">
      <h2>Actualizar Horario</h2>

      {loading ? (
        <p>Cargando...</p>
      ) : (
        <form onSubmit={handleSubmit}>
          <div className="mb-3">
            <label htmlFor="docente" className="form-label">
              Docente
            </label>
            <input
              type="text"
              className="form-control"
              id="docente"
              value={docente}
              onChange={(e) => setDocente(e.target.value)}
              required
            />
          </div>

          <div className="mb-3">
            <label htmlFor="dia" className="form-label">
              Día
            </label>
            <input
              type="text"
              className="form-control"
              id="dia"
              value={dia}
              onChange={(e) => setDia(e.target.value)}
              required
            />
          </div>

          <div className="mb-3">
            <label htmlFor="hora" className="form-label">
              Hora
            </label>
            <input
              type="text"
              className="form-control"
              id="hora"
              value={hora}
              onChange={(e) => setHora(e.target.value)}
              required
            />
          </div>

          {errors.length > 0 && (
            <div className="alert alert-danger">
              <ul>
                {errors.map((error, index) => (
                  <li key={index}>{error}</li>
                ))}
              </ul>
            </div>
          )}

          {successMessage && <div className="alert alert-success">{successMessage}</div>}

          <button type="submit" className="btn btn-primary">
            Actualizar
          </button>
        </form>
      )}
    </div>
  );
};

export default ActualizarHorarioPrevio;
