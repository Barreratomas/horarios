import React, { useState, useEffect } from 'react';
import { useNavigate } from 'react-router-dom';

const Asignaciones = () => {
  const [docentes, setDocentes] = useState([]);
  const [messages, setMessages] = useState({
    success: null,
    error: null
  });
  const [errors, setErrors] = useState({});
  const navigate = useNavigate();

  // Obtener docentes
  useEffect(() => {
    const obtenerDocentes = async () => {
      try {
        // traerme los datos de disponibilidad
        const response = await fetch('http://127.0.0.1:8000/api/horarios/uCPlan', {
          headers: { Accept: 'application/json' }
        });
        if (!response.ok) throw new Error('Error al obtener docentes');
        const data = await response.json();
        setDocentes(data);
      } catch (error) {
        setMessages({ ...messages, error: error.message });
      }
    };
    obtenerDocentes();
  }, []);

  // Manejar eliminación de asignación
  const eliminarAsignacion = async (h_p_d, dm) => {
    try {
      const response = await fetch(`/api/asignaciones/${h_p_d}/${dm}`, {
        method: 'DELETE',
        headers: {
          'X-CSRF-Token': document.querySelector('meta[name="csrf-token"]').content
        }
      });

      if (!response.ok) {
        const errorData = await response.json();
        setErrors(errorData.errors || {});
      } else {
        // Actualiza la lista de docentes
        setDocentes(docentes.filter((docente) => docente.horarioPrevioDocente.id_h_p_d !== h_p_d));
        setMessages({ ...messages, success: 'Asignación eliminada con éxito' });
      }
    } catch (error) {
      setMessages({ ...messages, error: error.message });
    }
  };

  return (
    <div>
      <div className="container py-3">
        <div className="row align-items-center justify-content-center">
          <div className="col-6 text-center"></div>
        </div>
      </div>

      <div className="container">
        <p>a espera de que se termine disponibilidad</p>
        {docentes.map((docente) => (
          <div
            key={docente.dni}
            style={{
              border: '1px solid #ccc',
              borderRadius: '5px',
              padding: '10px',
              marginBottom: '10px',
              width: '30vw'
            }}
          >
            <p>
              Nombre: {docente.nombre} {docente.apellido}
            </p>
            <p>DNI: {docente.dni}</p>

            {docente.horarioPrevioDocente.zip(docente.docenteMateria).map(([h_p_d, dm]) =>
              h_p_d && dm && dm.disponibilidad ? (
                <div
                  key={dm.id_dm}
                  style={{
                    border: '1px solid #ccc',
                    borderRadius: '5px',
                    padding: '10px',
                    marginBottom: '10px',
                    width: '28.5vw'
                  }}
                >
                  {/* {h_p_d.dia && <p>Día horario previo: {h_p_d.dia}</p>}
                  <p>Hora horario previo: {h_p_d.hora}</p>
                  <p>Materia: {dm.materia.nombre}</p>
                  <p>Nombre del aula: {dm.aula.nombre}</p>
                  <p>
                    Comisión: {dm.comision.anio}°{dm.comision.division}
                  </p>
                  <p>Carrera: {dm.comision.carrera.nombre}</p> */}

                  <button
                    className="btn btn-secondary m-2"
                    onClick={() => navigate(`/actualizar-asignacion/${h_p_d.id_h_p_d}/${dm.id_dm}`)}
                  >
                    Actualizar asignación
                  </button>
                  <button
                    className="btn btn-danger"
                    onClick={() => eliminarAsignacion(h_p_d.id_h_p_d, dm.id_dm)}
                  >
                    Eliminar asignación
                  </button>
                </div>
              ) : null
            )}

            <div className="botones">
              <button
                className="btn btn-primary me-2"
                onClick={() => navigate(`/asignar/${docente.dni}`)}
              >
                Asignar
              </button>
            </div>
          </div>
        ))}
      </div>

      <div id="messages-container" className="container">
        {messages.error && <div className="alert alert-danger">{messages.error}</div>}
        {errors.any && (
          <div className="alert alert-danger">
            <ul>
              {Object.values(errors).map((error, index) => (
                <li key={index}>{error}</li>
              ))}
            </ul>
          </div>
        )}
        {messages.success && <div className="alert alert-success">{messages.success}</div>}
      </div>
    </div>
  );
};

export default Asignaciones;
