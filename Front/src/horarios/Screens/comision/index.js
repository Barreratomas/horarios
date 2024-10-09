import React, { useState, useEffect } from 'react';
import { useNavigate, useOutletContext } from 'react-router-dom';

const Comisiones = () => {
  const navigate = useNavigate();
  const { routes } = useOutletContext();

  const [comisiones, setComisiones] = useState([]);
  const [errors, setErrors] = useState([]);
  const [successMessage, setSuccessMessage] = useState('');

  // Simular obtener las comisiones (puedes reemplazarlo con una llamada a la API)
  useEffect(() => {
    const obtenerComisiones = async () => {
      try {
        const response = await fetch('/api/comisiones'); // Cambiar URL según tu API
        if (response.ok) {
          const data = await response.json();
          setComisiones(data);
        } else {
          setErrors(['Error al obtener las comisiones']);
        }
      } catch (error) {
        setErrors([error.message]);
      }
    };
    obtenerComisiones();
  }, []);

  const handleEliminar = async (idComision) => {
    if (!window.confirm('¿Estás seguro de eliminar esta comisión?')) return;
    try {
      const response = await fetch(`/api/comisiones/${idComision}`, {
        method: 'DELETE',
        headers: {
          'X-CSRF-Token': document.querySelector('meta[name="csrf-token"]').content
        }
      });

      if (response.ok) {
        setSuccessMessage('Comisión eliminada correctamente');
        setComisiones(comisiones.filter((comision) => comision.id_comision !== idComision));
      } else {
        setErrors(['Error al eliminar la comisión']);
      }
    } catch (error) {
      setErrors([error.message]);
    }
  };

  return (
    <div className="container py-3">
      <div className="row align-items-center justify-content-center">
        <div className="col-6 text-center">
          <button
            type="button"
            className="btn btn-primary me-2"
            onClick={() => navigate(`${routes.base}/${routes.comisiones.crear}`)}
            style={{ display: 'inline-block', marginRight: '10px' }}
          >
            Crear
          </button>
        </div>
      </div>

      <div className="container">
        {comisiones.map((comision) => (
          <div
            key={comision.id_comision}
            style={{
              border: '1px solid #ccc',
              borderRadius: '5px',
              padding: '10px',
              marginBottom: '10px',
              width: '30vw'
            }}
          >
            <p>
              Comisión: {comision.anio}°{comision.division}
            </p>
            <p>Carrera: {comision.carrera.nombre}</p>
            <p>Capacidad: {comision.capacidad}</p>
            <div className="botones">
              <button
                type="button"
                className="btn btn-primary me-2"
                // id parentesis
                onClick={() => navigate(`${routes.base}/${routes.comisiones.actualizar()}`)}
                style={{ display: 'inline-block', marginRight: '10px' }}
              >
                Crear
              </button>
              <button
                type="button"
                className="btn btn-danger"
                onClick={() => handleEliminar(comision.id_comision)}
              >
                Eliminar
              </button>
            </div>
          </div>
        ))}
      </div>

      <div id="messages-container" className="container">
        {errors.length > 0 && (
          <div className="alert alert-danger">
            <ul>
              {errors.map((error, index) => (
                <li key={index}>{error}</li>
              ))}
            </ul>
          </div>
        )}

        {successMessage && <div className="alert alert-success">{successMessage}</div>}
      </div>
    </div>
  );
};

export default Comisiones;
