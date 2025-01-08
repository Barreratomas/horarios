import React, { useState, useEffect } from 'react';
import Table from '../layouts/parcials/table';
import FormularioHoraio from '../layouts/parcials/formularioHorario';

const Horario = ({ id = null }) => {
  const [comisiones, setComisiones] = useState([]);
  const [horarios, setHorarios] = useState([]);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState('');

  useEffect(() => {
    const fetchHorarios = async () => {
      try {
        const response = await fetch('http://127.0.0.1:8000/api/horarios/horarios/{id}', {
          method: 'GET',
          headers: {
            'Content-Type': 'application/json'
          }
        });
        if (!response.ok) {
          throw new Error('Error al cargar las comisiones');
        }
        const data = await response.json();
        setHorarios(data);
      } catch (error) {
        setError('No se pudo cargar las comisiones');
      }
    };

    if (id !== null) {
      fetchHorarios();
    }
  }, []);

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
      } catch (error) {
        setError('No se pudo cargar las comisiones');
      } finally {
        setLoading(false); // Indica que la carga ha terminado
      }
    };

    fetchComisiones();
  }, []);

  // Si está cargando, muestra un mensaje de espera
  if (loading) {
    return <div className="container text-center">Cargando comisiones...</div>;
  }

  // Si hay un error al cargar las comisiones
  if (error) {
    return <div className="container text-center text-danger">{error}</div>;
  }

  return (
    <div className="container">
      <FormularioHoraio comisiones={comisiones} /> {/* Pasa las comisiones al formulario */}
      <div className="row">
        <Table horarios={horarios} />
      </div>
    </div>
  );
};

export default Horario;
