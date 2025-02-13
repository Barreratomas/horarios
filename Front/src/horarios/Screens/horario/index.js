import React, { useState, useEffect } from 'react';
import TablaHorario from '../layouts/parcials/table';
import FormularioHorario from '../layouts/parcials/formularioHorario';
import { Spinner } from 'react-bootstrap';
import '../../css/loading.css';
import ErrorPage from '../layouts/parcials/errorPage';
const Horario = () => {
  const [comisiones, setComisiones] = useState([]);
  const [horarios, setHorarios] = useState([]);
  const [loading, setLoading] = useState(true);
  const [serverUp, setServerUp] = useState(false);
  const fetchHorarios = async (comisionSeleccionada) => {
    try {
      setLoading(true);
      const response = await fetch(
        `http://127.0.0.1:8000/api/horarios/horariosPorCarreraGrado/${comisionSeleccionada}`,
        {
          method: 'GET',
          headers: {
            'Content-Type': 'application/json'
          }
        }
      );
      const data = await response.json();
      if (data.error) {
        console.error('Error del backend:', data.error);
        setServerUp(false);
      } else {
        setHorarios(data);
        setServerUp(true);
      }
    } catch (error) {
      console.log('No se pudo cargar los horarios');
      setServerUp(false);
    } finally {
      setLoading(false);
    }
  };

  // Fetch de las comisiones
  useEffect(() => {
    const fetchComisiones = async () => {
      try {
        const response = await fetch('http://127.0.0.1:8000/api/horarios/carreraGrados');
        const data = await response.json();
        if (data.error) {
          console.error('Error del backend:', data.error);
          setServerUp(false);
        } else {
          setComisiones(data);
          setServerUp(true);
        }
      } catch (error) {
        console.log('No se pudo cargar las comisiones');
        setServerUp(false);
      } finally {
        setLoading(false);
      }
    };

    fetchComisiones();
  }, []);

  const handleComisionSeleccionada = (comisionSeleccionada) => {
    fetchHorarios(comisionSeleccionada);
  };

  return (
    <>
      {loading ? (
        <div className="loading-container">
          <Spinner animation="border" role="status" className="spinner" variant="primary" />
          <p className="text-center">Cargando...</p>
        </div>
      ) : serverUp ? (
        <div className="container">
          <FormularioHorario
            comisiones={comisiones}
            onComisionSeleccionada={handleComisionSeleccionada}
          />
          <div className="row">
            <TablaHorario horarios={horarios} modo="alumno" />
          </div>
        </div>
      ) : (
        <ErrorPage message="La seccion de horarios" statusCode={500} />
      )}
    </>
  );
};

export default Horario;
