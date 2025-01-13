import React, { useState, useEffect } from 'react';
import TablaHorario from '../layouts/parcials/tableDocente'; // El componente de la tabla de horarios
import FormularioHorarioDocente from '../layouts/parcials/formularioHorarioDocente';

const HorarioDocente = () => {
  const [docentes, setDocentes] = useState([]); // Lista de docentes
  const [horarios, setHorarios] = useState([]); // Horarios del docente seleccionado
  const [loading, setLoading] = useState(true); // Para el estado de carga
  const [error, setError] = useState(''); // Mensajes de error

  // Función para obtener los horarios por docente
  const fetchHorariosDocente = async (idDocenteSeleccionado) => {
    try {
      setLoading(true);
      const response = await fetch(
        `http://127.0.0.1:8000/api/horarios/horariosPorDocente/${idDocenteSeleccionado}`,
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
      setError('No se pudo cargar los horarios del docente');
    } finally {
      setLoading(false);
    }
  };

  // Fetch de los docentes
  useEffect(() => {
    const fetchDocentes = async () => {
      try {
        const response = await fetch('http://127.0.0.1:8000/api/horarios/docentes');
        if (!response.ok) {
          throw new Error('Error al cargar los docentes');
        }
        const data = await response.json();
        setDocentes(data);
        setError('');
      } catch (error) {
        setError('No se pudo cargar los docentes');
      } finally {
        setLoading(false);
      }
    };

    fetchDocentes();
  }, []);

  // Función para manejar el docente seleccionado
  const handleDocenteSeleccionado = (idDocente) => {
    fetchHorariosDocente(idDocente);
  };

  // Condicionales de carga y error
  if (loading) {
    return <div className="container text-center">Cargando datos...</div>;
  }

  if (error) {
    return <div className="container text-center text-danger">{error}</div>;
  }

  return (
    <div className="container">
      <FormularioHorarioDocente
        docentes={docentes}
        onDocenteSeleccionado={handleDocenteSeleccionado}
      />
      <div className="row">
        <TablaHorario horarios={horarios} modo="docente" />
      </div>
    </div>
  );
};

export default HorarioDocente;
