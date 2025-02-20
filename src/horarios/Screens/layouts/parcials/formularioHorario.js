import React, { useState } from 'react';

const FormularioHoraio = ({ comisiones = [], onComisionSeleccionada }) => {
  const [comisionSeleccionada, setComisionSeleccionada] = useState('');
  const [error, setError] = useState('');
  // const [horario, setHorario] = useState(null);
  console.log(comisiones);

  const handleSubmit = (event) => {
    event.preventDefault();

    if (!comisionSeleccionada) {
      setError('Por favor selecciona una comisión');
    } else {
      console.log(comisionSeleccionada);
      onComisionSeleccionada(comisionSeleccionada);
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
        </div>
      </div>
    </div>
  );
};

export default FormularioHoraio;
