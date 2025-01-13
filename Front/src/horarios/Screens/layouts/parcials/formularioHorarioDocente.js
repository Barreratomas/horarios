import React, { useState } from 'react';

const FormularioHorarioDocente = ({ docentes, onDocenteSeleccionado }) => {
  const [docenteSeleccionado, setDocenteSeleccionado] = useState('');

  const handleSubmit = (event) => {
    event.preventDefault();
    onDocenteSeleccionado(docenteSeleccionado);
  };

  return (
    <div className="container py-3">
      <div className="row align-items-center justify-content-center">
        <div className="col-6 text-center">
          <form onSubmit={handleSubmit}>
            <div className="mb-3">
              <label htmlFor="docente" style={{ fontFamily: 'sans-serif' }}>
                Selecciona un docente:
              </label>

              <select
                className="form-select"
                name="docente"
                value={docenteSeleccionado}
                onChange={(e) => {
                  setDocenteSeleccionado(e.target.value);
                }}
                aria-label="Docente"
              >
                <option value="">Selecciona un docente</option>
                {docentes.length > 0 ? (
                  docentes.map((docente) => (
                    <option key={docente.id_docente} value={docente.id_docente}>
                      {docente.nombre} {docente.apellido}
                    </option>
                  ))
                ) : (
                  <option value="" disabled>
                    No hay docentes disponibles
                  </option>
                )}
              </select>
            </div>

            <button type="submit" className="btn btn-primary me-2">
              Mostrar Horarios
            </button>
          </form>
        </div>
      </div>
    </div>
  );
};

export default FormularioHorarioDocente;
