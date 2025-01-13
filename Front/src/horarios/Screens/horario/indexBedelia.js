import React, { useState, useEffect } from 'react';
import TablaHorario from '../layouts/parcials/tableBedelia';

const HorarioBedelia = () => {
  const [horarios, setHorarios] = useState([]);
  const [loading, setLoading] = useState(true);

  // Llamada a la API para obtener los horarios
  useEffect(() => {
    const fetchHorarios = async () => {
      try {
        const response = await fetch('http://127.0.0.1:8000/api/horarios/horarios');
        if (response.ok) {
          const data = await response.json();
          setHorarios(data);
        } else {
          console.error('Error al obtener los horarios');
        }
      } catch (error) {
        console.error('Error en la llamada a la API:', error);
      } finally {
        setLoading(false);
      }
    };

    fetchHorarios();
  }, []);

  return (
    <div className="container py-3">
      <div className="row">
        {loading ? <div>Cargando horarios...</div> : <TablaHorario horarios={horarios} />}
      </div>
    </div>
  );
};

export default HorarioBedelia;
