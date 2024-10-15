import React, { useState, useEffect } from 'react';
import { useParams } from 'react-router-dom';
// import { useNavigate, useOutletContext, useLocation, useParams } from 'react-router-dom';

const PlanCarrera = () => {
  //   const navigate = useNavigate();
  //   const { routes } = useOutletContext();
  //   const location = useLocation();
  const { carreraId } = useParams(); // Obtener carreraId de la URL

  const [plan, setPlan] = useState([]); // Estado para almacenar el plan de la carrera
  //   const [errors, setErrors] = useState([]);

  // Efecto para obtener el plan al cargar el componente
  useEffect(() => {
    const fetchPlanCarrera = async () => {
      try {
        // hay que corregir la conexion a la api porque no esta puesta la ruta correcta
        const response = await fetch(`http://127.0.0.1:8000/api/horarios/planEstudio`, {
          headers: { Accept: 'application/json' }
        });

        if (!response.ok) throw new Error('Error al obtener el plan de la carrera');

        const data = await response.json();
        setPlan(data);
      } catch (error) {
        console.error('Error al obtener el plan:', error);
        // setErrors([error.message || 'Servidor fuera de servicio...']);
      }
    };

    fetchPlanCarrera();
  }, [carreraId]);

  return (
    <>
      <div className="container py-3">
        {/* <div className="row align-items-center justify-content-center">
            <div className="col-6 text-center">
              <button
                type="button"
                className="btn btn-primary me-2"
                onClick={() => navigate(`${routes.base}/${routes.planes.crear}`)}
                style={{ display: 'inline-block', marginRight: '10px' }}
              >
                Crear Plan
              </button>
            </div>
          </div> */}

        <div className="container">
          {plan.map((plan) => (
            <div
              key={plan.id}
              style={{
                border: '1px solid #ccc',
                borderRadius: '5px',
                padding: '10px',
                marginBottom: '10px',
                width: '30vw'
              }}
            >
              <p>esperando finalizacion del back</p>
              {/* <p>
                  Grado: {plan.grado}° {grado.division}
                </p>
                <p>Detalle: {grado.detalle}</p>
                <p>Capacidad: {}</p> */}
            </div>
          ))}
        </div>
      </div>
    </>
  );
};

export default PlanCarrera;
