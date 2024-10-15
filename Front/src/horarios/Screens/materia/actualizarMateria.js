import React, { useState, useEffect } from 'react';
import { useNavigate, useOutletContext, useParams } from 'react-router-dom';

const ActualizarMateria = () => {
  const { materiaId } = useParams(); // Obtener ID de la materia desde la URL
  const [unidadCurricular, setUnidadCurricular] = useState('');
  const [tipo, setTipo] = useState('');
  const [horasSem, setHorasSem] = useState('');
  const [horasAnual, setHorasAnual] = useState('');
  const [formato, setFormato] = useState('');
  const [errors, setErrors] = useState({});
  const navigate = useNavigate();
  const { routes } = useOutletContext(); // Obtener rutas desde el contexto

  // Obtener los datos de la materia por ID
  useEffect(() => {
    const obtenerMateria = async () => {
      try {
        const response = await fetch(
          `http://127.0.0.1:8000/api/horaios/unidadCurricular/${materiaId}`
        );
        const data = await response.json();

        if (response.ok) {
          setUnidadCurricular(data.unidad_curricular);
          setTipo(data.tipo);
          setHorasSem(data.horas_sem);
          setHorasAnual(data.horas_anual);
          setFormato(data.formato);
        } else {
          console.error('Error al obtener la materia:', data);
        }
      } catch (error) {
        console.error('Error:', error);
      }
    };

    obtenerMateria();
  }, [materiaId]);

  // Manejar el envío del formulario
  const handleSubmit = async (e) => {
    e.preventDefault();
    setErrors({});

    try {
      const response = await fetch(
        `http://127.0.0.1:8000/api/horarios/unidadCurricular/actualizar/${materiaId}`,
        {
          method: 'PUT',
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
        }
      );

      if (response.ok) {
        navigate(`${routes.base}/${routes.materias.main}`, {
          state: { successMessage: 'Materia actualizada con éxito' }
        });
      } else {
        const data = await response.json();
        if (data.errors) {
          setErrors(data.errors);
        }
      }
    } catch (error) {
      console.error('Error al actualizar la materia:', error);
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
              maxLength="60"
            />
            {errors.unidad_curricular && (
              <div className="text-danger">{errors.unidad_curricular}</div>
            )}
            <br />
            <br />

            <label htmlFor="tipo">Tipo</label>
            <br />
            <input
              type="text"
              name="tipo"
              value={tipo}
              onChange={(e) => setTipo(e.target.value)}
              maxLength="20"
            />
            {errors.tipo && <div className="text-danger">{errors.tipo}</div>}
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
            {errors.horas_sem && <div className="text-danger">{errors.horas_sem}</div>}
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
            {errors.horas_anual && <div className="text-danger">{errors.horas_anual}</div>}
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
            {errors.formato && <div className="text-danger">{errors.formato}</div>}
            <br />
            <br />

            <button type="submit" className="btn btn-primary">
              Actualizar
            </button>
          </form>
        </div>
      </div>

      {Object.keys(errors).length > 0 && (
        <div className="container" style={{ width: '500px' }}>
          <div className="alert alert-danger">
            <ul>
              {Object.values(errors).map((error, index) => (
                <li key={index}>{error}</li>
              ))}
            </ul>
          </div>
        </div>
      )}
    </div>
  );
};

export default ActualizarMateria;
