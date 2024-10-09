import React, { useState } from 'react';

const FormularioHoraio = ({ comisiones = [] }) => {
  const [comisionSeleccionada, setComisionSeleccionada] = useState('');
  const [error, setError] = useState('');

  const handleChange = (event) => {
    setComisionSeleccionada(event.target.value);
  };

  const handleSubmit = (event) => {
    event.preventDefault();

    if (!comisionSeleccionada) {
      setError('Por favor selecciona una comisión');
    } else {
      setError('');
      // Aquí manejarías la lógica de envío, como hacer un fetch o enviar los datos a la API
      console.log(`Comisión seleccionada: ${comisionSeleccionada}`);
      // Aquí harías la llamada a la API en lugar de solo un console.log
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
                onChange={handleChange}
                aria-label="Comisión"
              >
                <option value="">Selecciona una comisión</option>
                {comisiones.length > 0 ? (
                  comisiones
                    .sort((a, b) => {
                      if (a.anio === b.anio) {
                        return a.division.localeCompare(b.division);
                      }
                      return a.anio - b.anio;
                    })
                    .map((comision) => (
                      <option key={comision.id_comision} value={comision.id_comision}>
                        {comision.anio}°{comision.division} | {comision.carrera.nombre}
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
        </div>
      </div>
    </div>
  );
};

export default FormularioHoraio;
