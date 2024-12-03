import React, { useState, useEffect } from 'react';
import { Modal, Button } from 'react-bootstrap';

const HorarioPrevio = () => {
  const [horariosPrevios, setHorariosPrevios] = useState([]);
  const [filteredHorarios, setFilteredHorarios] = useState([]);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState(null);
  const [filterText, setFilterText] = useState('');
  const [showModal, setShowModal] = useState(false);
  const [horarioToDelete, setHorarioToDelete] = useState(null);

  // Obtener los horarios previos desde la API
  useEffect(() => {
    const fetchHorariosPrevios = async () => {
      try {
        const response = await fetch('http://127.0.0.1:8000/api/horarios/horariosPreviosDocentes', {
          headers: { Accept: 'application/json' }
        });

        if (!response.ok) throw new Error('Error al obtener los horarios previos');
        const data = await response.json();
        setHorariosPrevios(data);
        setFilteredHorarios(data); // Inicializamos con todos los horarios
      } catch (error) {
        setError(error.message);
      } finally {
        setLoading(false);
      }
    };

    fetchHorariosPrevios();
  }, []);

  useEffect(() => {
    // Filtrar horarios cuando cambie el texto del filtro
    if (filterText === '') {
      setFilteredHorarios(horariosPrevios); // Si no hay texto de filtro, mostramos todos los horarios
    } else {
      setFilteredHorarios(
        horariosPrevios.filter(
          (horario) =>
            horario.docente_nombre.toLowerCase().includes(filterText.toLowerCase()) ||
            horario.dia.toLowerCase().includes(filterText.toLowerCase()) ||
            horario.hora.includes(filterText)
        )
      );
    }
  }, [filterText, horariosPrevios]);

  const handleDelete = async () => {
    try {
      const response = await fetch(
        `http://127.0.0.1:8000/api/horarios/eliminar/${horarioToDelete}`,
        {
          method: 'DELETE',
          headers: { 'Content-Type': 'application/json' }
        }
      );

      if (!response.ok) throw new Error('Error al eliminar el horario');

      setHorariosPrevios(horariosPrevios.filter((h) => h.id !== horarioToDelete));
      setFilteredHorarios(filteredHorarios.filter((h) => h.id !== horarioToDelete));
      setShowModal(false);
    } catch (error) {
      setError(error.message || 'Error al eliminar el horario');
    }
  };

  if (loading) return <p>Cargando horarios...</p>;
  if (error) return <p>Error: {error}</p>;

  return (
    <div className="container py-3">
      <h2>Horarios Previos</h2>
      <div className="mb-3">
        <input
          type="text"
          className="form-control"
          placeholder="Filtrar por docente, día o hora"
          value={filterText}
          onChange={(e) => setFilterText(e.target.value)}
        />
      </div>
      {filteredHorarios.length > 0 ? (
        <div className="list-group">
          {filteredHorarios.map((horario) => (
            <div
              key={horario.id}
              className="list-group-item d-flex justify-content-between align-items-center"
            >
              <div>
                <strong>Docente:</strong> {horario.docente_nombre} <br />
                <strong>Día:</strong> {horario.dia} <br />
                <strong>Hora:</strong> {horario.hora}
              </div>
              <button
                className="btn btn-danger"
                onClick={() => {
                  setHorarioToDelete(horario.id);
                  setShowModal(true);
                }}
              >
                Eliminar
              </button>
            </div>
          ))}
        </div>
      ) : (
        <p>No hay horarios previos disponibles.</p>
      )}

      {/* Modal de confirmación */}
      <Modal show={showModal} onHide={() => setShowModal(false)}>
        <Modal.Header closeButton>
          <Modal.Title>Confirmar eliminación</Modal.Title>
        </Modal.Header>
        <Modal.Body>
          <p>¿Estás seguro de que quieres eliminar este horario?</p>
        </Modal.Body>
        <Modal.Footer>
          <Button variant="secondary" onClick={() => setShowModal(false)}>
            Cancelar
          </Button>
          <Button variant="danger" onClick={handleDelete}>
            Eliminar
          </Button>
        </Modal.Footer>
      </Modal>
    </div>
  );
};

export default HorarioPrevio;
