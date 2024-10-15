import React, { useState } from 'react';
import { useNavigate, useOutletContext } from 'react-router-dom';

const CrearMateria = () => {
  const [unidadCurricular, setUnidadCurricular] = useState('');
  const [tipo, setTipo] = useState('');
  const [horasSem, setHorasSem] = useState('');
  const [horasAnual, setHorasAnual] = useState('');
  const [formato, setFormato] = useState('');
  const [errors, setErrors] = useState([]);

  const navigate = useNavigate();
  const { routes } = useOutletContext();

  // Manejar el envío del formulario
  const handleSubmit = async (e) => {
    e.preventDefault();
    setErrors([]); // Reiniciar errores

    try {
      const response = await fetch('http://127.0.0.1:8000/api/horarios/unidadCurricular/guardar', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json'
        },
        body: JSON.stringify({
          unidadCurricular,
          tipo,
          horasSem,
          horasAnual,
          formato
        })
      });

      if (response.ok) {
        navigate(`${routes.base}/${routes.materias.main}`, {
          state: { successMessage: 'Materia creada con éxito' }
        });
      } else {
        const data = await response.json();
        if (data.errors) setErrors(data.errors); // Manejar errores de validación
      }
    } catch (error) {
      console.error('Error creando materia:', error);
      setErrors([error.message]);
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
            {errors.unidadCurricular && (
              <div className="text-danger">{errors.unidadCurricular}</div>
            )}

            <label htmlFor="tipo">Tipo</label>
            <br />
            <input type="text" name="tipo" value={tipo} onChange={(e) => setTipo(e.target.value)} />
            <br />
            <br />
            {errors.tipo && <div className="text-danger">{errors.tipo}</div>}

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
            {errors.horasSem && <div className="text-danger">{errors.horasSem}</div>}

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
            {errors.horasAnual && <div className="text-danger">{errors.horasAnual}</div>}

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
            {errors.formato && <div className="text-danger">{errors.formato}</div>}

            <button type="submit" className="btn btn-primary me-2">
              Crear
            </button>
          </form>
        </div>
      </div>

      {errors.length > 0 && (
        <div className="container" style={{ width: '500px' }}>
          <div className="alert alert-danger">
            <ul>
              {errors.map((error, index) => (
                <li key={index}>{error}</li>
              ))}
            </ul>
          </div>
        </div>
      )}
    </div>
  );
};

export default CrearMateria;
