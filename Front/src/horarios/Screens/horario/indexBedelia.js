import React, { useState, useEffect } from 'react';
import TablaHorario from '../layouts/parcials/tableBedelia';
import { Spinner } from 'react-bootstrap';
import '../../css/loading.css';
import ErrorPage from '../layouts/parcials/errorPage';

const HorarioBedelia = () => {
  const [horarios, setHorarios] = useState([]);
  const [loading, setLoading] = useState(true);
  const [serverUp, setServerUp] = useState(false);
  // Llamada a la API para obtener los horarios
  useEffect(() => {
    const fetchHorarios = async () => {
      try {
        const response = await fetch('http://127.0.0.1:8000/api/horarios/horarios');
        const data = await response.json();

        if (data.error) {
          console.error('Error al obtener los horarios');
        } else {
          setServerUp(true);
          setHorarios(data);
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
    <>
      {loading ? (
        <div className="loading-container">
          <Spinner animation="border" role="status" className="spinner" variant="primary" />
          <p className="text-center">Cargando...</p>
        </div>
      ) : serverUp ? (
        <div className="container py-3">
          <div className="row">
            <TablaHorario horarios={horarios} />
          </div>
        </div>
      ) : (
        <ErrorPage message="La seccion de horarios bedelia" statusCode={500} />
      )}
    </>
  );
};

export default HorarioBedelia;
