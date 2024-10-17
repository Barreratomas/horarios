import React, { useState, useEffect } from 'react';

const HorarioPrevio = () => {
  const [horariosPrevios, setHorariosPrevios] = useState([]);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState(null);

  // Obtener los horarios previos desde la API
  useEffect(() => {
    const fetchHorariosPrevios = async () => {
      try {
        const response = await fetch('http://127.0.0.1:8000/api/horarios/horarios_previos', {
          headers: { Accept: 'application/json' }
        });

        if (!response.ok) throw new Error('Error al obtener los horarios previos');
        const data = await response.json();
        setHorariosPrevios(data);
      } catch (error) {
        setError(error.message);
      } finally {
        setLoading(false);
      }
    };

    fetchHorariosPrevios();
  }, []);

  if (loading) return <p>Cargando horarios...</p>;
  if (error) return <p>Error: {error}</p>;

  return (
    <div className="container py-3">
      <h2>Horarios Previos</h2>
      {horariosPrevios.length > 0 ? (
        <ul className="list-group">
          {horariosPrevios.map((horario) => (
            <li key={horario.id} className="list-group-item">
              <strong>Docente:</strong> {horario.docente_nombre} <br />
              <strong>Día:</strong> {horario.dia} <br />
              <strong>Hora:</strong> {horario.hora}
            </li>
          ))}
        </ul>
      ) : (
        <p>No hay horarios previos disponibles.</p>
      )}
    </div>
  );
};

export default HorarioPrevio;
