import React, { useState, useEffect } from 'react';
import TablaHorario from '../layouts/parcials/table';
import FormularioHorario from '../layouts/parcials/formularioHorario';
import { Spinner } from 'react-bootstrap';
import '../../css/loading.css';

const Horario = () => {
  const [comisiones, setComisiones] = useState([]);
  const [horarios, setHorarios] = useState([]);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState('');

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

      if (!response.ok) {
        console.error('Error del backend:', data.error);
      } else {
        setHorarios(data);
        setError('');
      }
    } catch (error) {
      setError('No se pudo cargar los horarios');
    } finally {
      setLoading(false);
    }
  };

  // Fetch de las comisiones
  useEffect(() => {
    const fetchComisiones = async () => {
      try {
        const response = await fetch('http://127.0.0.1:8000/api/horarios/carreraGrados');
        if (!response.ok) {
          throw new Error('Error al cargar las comisiones');
        }
        const data = await response.json();
        setComisiones(data);
        setError('');
      } catch (error) {
        setError('No se pudo cargar las comisiones');
      } finally {
        setLoading(false);
      }
    };

    fetchComisiones();
  }, []);

  const handleComisionSeleccionada = (comisionSeleccionada) => {
    fetchHorarios(comisionSeleccionada);
  };

  if (loading) {
    return (
      <div className="loading-container">
        <Spinner animation="border" role="status" className="spinner" variant="primary" />
        <p className="text-center">Cargando...</p>
      </div>
    );
  }

  if (error) {
    return <div className="container text-center text-danger">{error}</div>;
  }

  return (
    <div className="container">
      <FormularioHorario
        comisiones={comisiones}
        onComisionSeleccionada={handleComisionSeleccionada}
      />
      <div className="row">
        <TablaHorario horarios={horarios} modo="alumno" />
      </div>
    </div>
  );
};

export default Horario;
