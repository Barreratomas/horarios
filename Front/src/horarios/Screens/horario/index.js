import React, { useState, useEffect } from 'react';
import Tabla from '../layouts/parcials/table';
import FormularioHoraio from '../layouts/parcials/formularioHorario';

const Horario = () => {
  const [comisiones, setComisiones] = useState([]);
  const [horarios, setHorarios] = useState([]);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState('');
  const [comision, setComision] = useState('');

  const fetchHorarios = async () => {
    console.log(comision);
    try {
      setLoading(true);
      const response = await fetch(`http://127.0.0.1:8000/api/horarios/horarios/${comision}`, {
        method: 'GET',
        headers: {
          'Content-Type': 'application/json'
        }
      });
      const data = await response.json();

      if (!response.ok) {
        console.error('Error del backend:', data.error);
      } else {
        setHorarios(data);
        setError('');
        console.log(data);
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
        const response = await fetch('http://127.0.0.1:8000/api/horarios/carreraGrados'); // Reemplaza con la URL correcta
        if (!response.ok) {
          throw new Error('Error al cargar las comisiones');
        }
        const data = await response.json();
        setComisiones(data); // Establece las comisiones en el estado
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
    setComision(comisionSeleccionada);
    fetchHorarios();
  };

  if (loading) {
    return <div className="container text-center">Cargando datos...</div>;
  }

  if (error) {
    return <div className="container text-center text-danger">{error}</div>;
  }

  return (
    <div className="container">
      <FormularioHoraio
        comisiones={comisiones}
        onComisionSeleccionada={handleComisionSeleccionada}
      />
      <div className="row">
        <Tabla horarios={horarios} />
      </div>
    </div>
  );
};

export default Horario;
