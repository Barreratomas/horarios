import React, { useState, useEffect } from 'react';
import TablaHorario from '../layouts/parcials/tableDocente'; // El componente de la tabla de horarios
import FormularioHorarioDocente from '../layouts/parcials/formularioHorarioDocente';
import { Spinner } from 'react-bootstrap';
import '../../css/loading.css';
import ErrorPage from '../layouts/parcials/errorPage';

const HorarioDocente = () => {
  const [docentes, setDocentes] = useState([]);
  const [horarios, setHorarios] = useState([]);
  const [loading, setLoading] = useState(true);
  const [serverUp, setServerUp] = useState(false);

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
      if (data.error) {
        console.error('Error del backend:', data.error);
        setServerUp(false);
      } else {
        setHorarios(data);
        setServerUp(true);
      }
    } catch (error) {
      console.log('No se pudo cargar los horarios del docente');
      setServerUp(false);
    } finally {
      setLoading(false);
    }
  };

  // Fetch de los docentes
  useEffect(() => {
    const fetchDocentes = async () => {
      try {
        const response = await fetch('http://127.0.0.1:8000/api/horarios/docentes');
        const data = await response.json();
        if (data.error) {
          console.error('Error del backend:', data.error);
          setServerUp(false);
        } else {
          setDocentes(data);
          setServerUp(true);
        }
      } catch (error) {
        console.log('No se pudo cargar los docentes');
        setServerUp(false);
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

  return (
    <>
      {loading ? (
        <div className="loading-container">
          <Spinner animation="border" role="status" className="spinner" variant="primary" />
          <p className="text-center">Cargando...</p>
        </div>
      ) : serverUp ? (
        <div className="container">
          <FormularioHorarioDocente
            docentes={docentes}
            onDocenteSeleccionado={handleDocenteSeleccionado}
          />
          <div className="row">
            <TablaHorario horarios={horarios} modo="docente" />
          </div>
        </div>
      ) : (
        <ErrorPage message="La seccion de horarios" statusCode={500} />
      )}
    </>
  );
};

export default HorarioDocente;
