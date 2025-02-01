import React, { useState } from 'react';
import { useNavigate, useOutletContext } from 'react-router-dom';
import { useNotification } from '../layouts/parcials/notification';

const CrearMateria = () => {
  const [unidadCurricular, setUnidadCurricular] = useState('');
  const [tipo, setTipo] = useState('');
  const [horasSem, setHorasSem] = useState('');
  const [horasAnual, setHorasAnual] = useState('');
  const [formato, setFormato] = useState('');

  const navigate = useNavigate();
  const { routes } = useOutletContext();
  const { addNotification } = useNotification();

  // Manejar el envío del formulario
  const handleSubmit = async (e) => {
    e.preventDefault();

    try {
      const response = await fetch('http://127.0.0.1:8000/api/horarios/unidadCurricular/guardar', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json'
        },
        body: JSON.stringify({
          unidad_curricular: unidadCurricular,
          tipo,
          horas_sem: horasSem,
          horas_anual: horasAnual,
          formato
        })
      });
      const data = await response.json();
      if (data.error) {
        addNotification(data.errors, 'danger');
      } else {
        navigate(`${routes.base}/${routes.materias.main}`, {
          state: { successMessage: 'Materia creada con éxito', updated: true }
        });
      }
    } catch (error) {
      addNotification(`Error de conexión`, 'danger');
    }
  };

  return (
    <div className="container py-3">
      <div className="row align-items-center justify-content-center">
        <div className="col-6 text-center">
          <form onSubmit={handleSubmit}>
            <label htmlFor="unidadCurricular">Unidad Curricular</label>
            <br />
            <input
              type="text"
              name="unidadCurricular"
              value={unidadCurricular}
              onChange={(e) => setUnidadCurricular(e.target.value)}
            />
            <br />
            <br />

            <label htmlFor="tipo">Tipo</label>
            <br />
            <input type="text" name="tipo" value={tipo} onChange={(e) => setTipo(e.target.value)} />
            <br />
            <br />

            <label htmlFor="horasSem">Horas Semanales</label>
            <br />
            <input
              type="number"
              name="horasSem"
              value={horasSem}
              onChange={(e) => setHorasSem(e.target.value)}
            />
            <br />
            <br />

            <label htmlFor="horasAnual">Horas Anuales</label>
            <br />
            <input
              type="number"
              name="horasAnual"
              value={horasAnual}
              onChange={(e) => setHorasAnual(e.target.value)}
            />
            <br />
            <br />

            <label htmlFor="formato">Formato</label>
            <br />
            <input
              type="text"
              name="formato"
              value={formato}
              onChange={(e) => setFormato(e.target.value)}
            />
            <br />
            <br />

            <button type="submit" className="btn btn-primary me-2">
              Crear
            </button>
            <br />
            <br />
            <button
              type="button"
              className="btn btn-danger"
              onClick={() => navigate(`${routes.base}/${routes.materias.main}`)}
            >
              Volver Atrás
            </button>
          </form>
        </div>
      </div>
    </div>
  );
};

export default CrearMateria;
