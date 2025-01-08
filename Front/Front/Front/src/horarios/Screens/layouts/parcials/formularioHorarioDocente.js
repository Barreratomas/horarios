import React, { useState } from 'react';

const FormularioHorarioDocente = () => {
  const [dni, setDni] = useState('');
  const [error, setError] = useState('');

  const handleSubmit = (e) => {
    e.preventDefault();

    // Validación simple del DNI (puedes ajustarla según tus necesidades)
    if (!dni) {
      setError('Por favor, ingrese el DNI del docente.');
      return;
    }

    //  hacer una solicitud POST a la ruta correspondiente
    // Ejemplo:
    // fetch('/mostrarHorarioDocente', {
    //   method: 'POST',
    //   headers: {
    //     'Content-Type': 'application/json',
    //     'X-CSRF-Token': document.querySelector('meta[name="csrf-token"]').getAttribute('content'), // Asegúrate de incluir CSRF Token
    //   },
    //   body: JSON.stringify({ dni }),
    // })
    // .then(response => response.json())
    // .then(data => {
    //   // Maneja la respuesta
    // })
    // .catch(error => {
    //   console.error('Error:', error);
    // });
  };

  return (
    <div className="container py-3">
      <div className="row align-items-center justify-content-center">
        <div className="col-6 text-center">
          <form onSubmit={handleSubmit}>
            <div className="mb-3">
              <label htmlFor="dni" style={{ fontFamily: 'sans-serif' }}>
                Ingrese el DNI del docente:
              </label>
              <input
                type="number"
                className="form-control"
                name="dni"
                id="dni"
                value={dni}
                onChange={(e) => setDni(e.target.value)}
              />
              {error && <div className="text-danger">{error}</div>}
            </div>

            <button type="submit" className="btn btn-primary me-2">
              Mostrar horarios
            </button>
          </form>
        </div>
      </div>
    </div>
  );
};

export default FormularioHorarioDocente;
