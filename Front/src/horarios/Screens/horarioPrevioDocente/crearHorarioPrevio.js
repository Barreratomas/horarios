import React, { useState } from 'react';
import { useNavigate } from 'react-router-dom';

const CrearHorarioPrevio = ({ dni }) => {
  const [trabajaInstitucion, setTrabajaInstitucion] = useState('no');
  const [dia, setDia] = useState('');
  const [hora, setHora] = useState('');
  const [errors, setErrors] = useState({});
  const navigate = useNavigate();

  // Manejar cambios en los campos de entrada
  const handleTrabajaInstitucionChange = (event) => {
    setTrabajaInstitucion(event.target.value);
  };

  const handleSubmit = async (event) => {
    event.preventDefault();
    const formData = new FormData();
    formData.append('trabajaInstitucion', trabajaInstitucion);
    formData.append('dia', dia);
    formData.append('hora', hora);

    try {
      const response = await fetch(`/api/storeHPD/${dni}`, {
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
        // Redirigir o realizar alguna acción después del éxito
        navigate('/ruta-deseada');
      }
    } catch (error) {
      console.error('Error al crear el docente:', error);
    }
  };

  return (
    <div className="container py-3">
      <div className="row align-items-center justify-content-center">
        <div className="col-6 text-center">
          <form onSubmit={handleSubmit}>
            <label htmlFor="trabajaInstitucion">¿Trabaja en otra institución?</label>
            <br />
            <input
              type="radio"
              name="trabajaInstitucion"
              value="si"
              checked={trabajaInstitucion === 'si'}
              onChange={handleTrabajaInstitucionChange}
            />
            <label htmlFor="trabaja_si">Sí</label>
            <br />
            <input
              type="radio"
              name="trabajaInstitucion"
              value="no"
              checked={trabajaInstitucion === 'no'}
              onChange={handleTrabajaInstitucionChange}
            />
            <label htmlFor="trabaja_no">No</label>
            <br />
            <br />

            {trabajaInstitucion === 'si' && (
              <div id="mostrarCampos">
                <label htmlFor="dia">Ingrese el día</label>
                <br />
                <input
                  type="text"
                  name="dia"
                  value={dia}
                  onChange={(e) => setDia(e.target.value)}
                />
                <br />
                <br />
                {errors.dia && <div className="text-danger">{errors.dia}</div>}

                <label htmlFor="hora">Ingrese la hora de salida</label>
                <br />
                <input
                  type="time"
                  name="hora"
                  value={hora}
                  onChange={(e) => setHora(e.target.value)}
                />
                <br />
                <br />
                {errors.hora && <div className="text-danger">{errors.hora}</div>}
              </div>
            )}

            <button type="submit" className="btn btn-primary me-2">
              Siguiente
            </button>
          </form>
        </div>
      </div>

      <div className="container" style={{ width: '500px' }}>
        {Object.keys(errors).length > 0 && (
          <div className="alert alert-danger">
            <ul>
              {Object.values(errors).map((error, index) => (
                <li key={index}>{error}</li>
              ))}
            </ul>
          </div>
        )}
      </div>
    </div>
  );
};

export default CrearHorarioPrevio;
