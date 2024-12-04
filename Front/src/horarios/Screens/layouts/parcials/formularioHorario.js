import React, { useState } from 'react';

const FormularioHoraio = ({ comisiones = [] }) => {
  const [comisionSeleccionada, setComisionSeleccionada] = useState('');
  const [error, setError] = useState('');
  const [horario, setHorario] = useState(null);

  const handleSubmit = (event) => {
    event.preventDefault();

    if (!comisionSeleccionada) {
      setError('Por favor selecciona una comisión');
    } else {
      setError('');
      // Simula la obtención del horario basado en la comisión seleccionada
      const horarioSimulado = {
        lunes: [
          { aula: 'Aula 101', docente: 'Juan Pérez' },
          { aula: 'Aula 102', docente: 'María Gómez' }
        ],
        martes: [
          { aula: 'Aula 103', docente: 'Luis Fernández' },
          { aula: 'Aula 104', docente: 'Ana Rodríguez' }
        ]
      };
      setHorario(horarioSimulado);
    }
  };

  return (
    <div className="container py-3">
      <div className="row align-items-center justify-content-center">
        <div className="col-6 text-center">
          <form onSubmit={handleSubmit}>
            <div className="mb-3">
              <label htmlFor="comision" style={{ fontFamily: 'sans-serif' }}>
                Selecciona una comisión:
              </label>

              <select
                className="form-select"
                name="comision"
                value={comisionSeleccionada}
                onChange={(e) => setComisionSeleccionada(e.target.value)}
                aria-label="Comisión"
              >
                <option value="">Selecciona una comisión</option>
                {comisiones.length > 0 ? (
                  comisiones.map((comision, index) => (
                    <option
                      key={`${comision.grado.id_grado}-${index}`}
                      value={comision.grado.id_grado}
                    >
                      {comision.grado.grado}°{comision.grado.division} | {comision.carrera.carrera}
                    </option>
                  ))
                ) : (
                  <option value="" disabled>
                    No hay comisiones disponibles
                  </option>
                )}
              </select>

              {error && <p className="text-danger">{error}</p>}
            </div>

            <button type="submit" className="btn btn-primary me-2">
              Mostrar Horario
            </button>
          </form>

          {/* Mostrar el horario si está disponible */}
          {horario && (
            <div className="mt-3">
              <h4>Horario de la comisión seleccionada:</h4>
              <table className="table table-bordered">
                <thead>
                  <tr>
                    <th>Modulos</th>
                    <th>Lunes</th>
                    <th>Martes</th>
                  </tr>
                </thead>
                <tbody>
                  <tr>
                    <td>9:00 AM - 11:00 AM</td>
                    <td>
                      {horario.lunes[0]?.aula} - {horario.lunes[0]?.docente}
                    </td>
                    <td>
                      {horario.martes[0]?.aula} - {horario.martes[0]?.docente}
                    </td>
                  </tr>
                  <tr>
                    <td>2:00 PM - 4:00 PM</td>
                    <td>
                      {horario.lunes[1]?.aula} - {horario.lunes[1]?.docente}
                    </td>
                    <td>
                      {horario.martes[1]?.aula} - {horario.martes[1]?.docente}
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>
          )}
        </div>
      </div>
    </div>
  );
};

export default FormularioHoraio;
