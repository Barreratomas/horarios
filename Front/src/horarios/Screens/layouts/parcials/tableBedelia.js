import '../../../css/tabla.css';
import React, { useState } from 'react';

const TablaHorario = ({ horarios: initialHorarios }) => {
  const [horarios, setHorarios] = useState(initialHorarios);
  const [selectedModule, setSelectedModule] = useState(null);
  const [selectedIds, setSelectedIds] = useState(new Set());
  const dias = ['lunes', 'martes', 'miercoles', 'jueves', 'viernes'];
  const inicio = {
    1: '19:20',
    2: '20:00',
    3: '20:40',
    4: '21:30',
    5: '22:10',
    6: '22:50'
  };

  const fin = {
    1: '20:00',
    2: '20:40',
    3: '21:20',
    4: '22:10',
    5: '22:50',
    6: '23:30'
  };

  const colores = [
    'rgba(250, 22, 22, 0.38)',
    'rgba(22, 72, 250, 0.28)',
    'rgba(54, 250, 22, 0.28)',
    'rgba(22, 250, 236, 0.28)',
    'rgba(246, 250, 22, 0.28)',
    'rgba(250, 22, 200, 0.28)',
    'rgba(122, 22, 250, 0.28)',
    'rgba(250, 131, 22, 0.28)'
  ];

  const [contextMenu, setContextMenu] = useState({
    visible: false,
    x: 0,
    y: 0,
    contenido: null
  });

  const handleRightClick = (event, contenido, dia, modulo, id_disp) => {
    event.preventDefault();
    const x = event.clientX + window.scrollX;
    const y = event.clientY + window.scrollY;

    // Almacena el módulo seleccionado
    setSelectedModule({ dia, modulo });
    setSelectedIds((prev) => new Set(prev.add(id_disp))); // Añade el id_disp al conjunto

    setContextMenu({
      visible: true,
      x,
      y,
      contenido
    });
  };

  const handleClickOutside = () => {
    setContextMenu({ ...contextMenu, visible: false });
    setSelectedModule(null); // Limpia el módulo seleccionado al hacer clic fuera
  };

  // Función para eliminar el horario
  const handleEliminar = async (id_disp) => {
    try {
      const response = await fetch(
        `http://127.0.0.1:8000/api/horarios/disponibilidad/eliminar/${id_disp}`,
        {
          method: 'DELETE'
        }
      );

      if (!response.ok) {
        throw new Error(`Error HTTP: ${response.status}`);
      }

      const data = await response.json();

      if (data.error) {
        console.error('Error del servidor:', data.error);
      } else {
        console.log('Horario eliminado:', data.data);

        // Actualizar el estado de horarios eliminando el horario con el id_disp
        setHorarios((prevHorarios) =>
          prevHorarios.filter((horario) => horario.id_disp !== id_disp)
        );
      }
    } catch (error) {
      console.error('Error al eliminar el horario:', error);
    } finally {
      setContextMenu({ ...contextMenu, visible: false });
    }
  };

  // Agrupar los horarios por id_carrera_grado
  const horariosPorCarrera = horarios.reduce((acc, horario) => {
    const idCarreraGrado = horario.disponibilidad.carrera_grado.id_carrera_grado;

    if (!acc[idCarreraGrado]) {
      acc[idCarreraGrado] = [];
    }
    acc[idCarreraGrado].push(horario);
    return acc;
  }, {});

  const obtenerContenidoCelda = (dia, modulo, horariosGrado) => {
    const horario = horariosGrado.find((h) => {
      return (
        h.disponibilidad.dia.toLowerCase() === dia &&
        parseInt(h.disponibilidad.modulo_inicio) <= modulo &&
        parseInt(h.disponibilidad.modulo_fin) >= modulo
      );
    });

    if (horario) {
      const unidadCurricular =
        horario.disponibilidad.unidad_curricular?.unidad_curricular || 'Sin Unidad Curricular';
      const modalidad = horario.modalidad.toUpperCase();
      const aula = horario.disponibilidad.aula?.nombre || 'Sin Aula';
      const docenteNombre = horario.disponibilidad.docente
        ? `${horario.disponibilidad.docente.nombre} ${horario.disponibilidad.docente.apellido}`
        : 'Sin Docente';
      const id_disp = horario.id_disp;

      return (
        <div
          className="horario-info"
          onContextMenu={(e) =>
            handleRightClick(
              e,
              { unidadCurricular, modalidad, aula, docenteNombre, id_disp },
              dia,
              modulo,
              id_disp
            )
          }
        >
          <div>{unidadCurricular}</div>
          <div>{modalidad}</div>
          <div>{aula}</div>
          <div>{docenteNombre}</div>
        </div>
      );
    }

    return '';
  };

  return (
    <div onClick={handleClickOutside}>
      <h3>horarios bedelia</h3>
      {Object.entries(horariosPorCarrera).map(([idCarreraGrado, horariosGrado]) => {
        const grado =
          horariosGrado?.[0]?.disponibilidad?.carrera_grado?.grado?.grado || 'Sin Grado';
        const division =
          horariosGrado?.[0]?.disponibilidad?.carrera_grado?.grado?.division || 'Sin División';
        const carreras =
          horariosGrado?.[0]?.disponibilidad?.carrera_grado?.carrera?.carrera || 'Sin Carrera';

        return (
          <div key={idCarreraGrado}>
            <div className="comision">
              <h4>Grado: {grado}</h4>
              <h4>División: {division}</h4>
              <h4>Carrera: {carreras}</h4>
            </div>

            <div className="bedelia-horario">
              <table className="planilla1">
                <thead className="horarios">
                  <tr>
                    <th className="div">Días / Horarios</th>
                    {Object.keys(inicio).map((key) => (
                      <th className={`p${key}`} key={key}>
                        {inicio[key]} - {fin[key]}
                      </th>
                    ))}
                  </tr>
                </thead>

                <tbody>
                  {dias.map((dia) => (
                    <tr className="xd" key={dia}>
                      <th
                        className="dias"
                        style={dia === 'viernes' ? { borderRadius: '0 0 0 20px' } : {}}
                      >
                        {dia.charAt(0).toUpperCase() + dia.slice(1)}
                      </th>

                      {Object.keys(inicio).map((modulo) => {
                        const isSelected =
                          selectedModule &&
                          selectedModule.dia === dia &&
                          selectedModule.modulo === parseInt(modulo);

                        const isRedBorder = selectedIds.has(
                          horariosGrado.find(
                            (h) =>
                              h.disponibilidad.dia === dia &&
                              h.disponibilidad.modulo_inicio <= modulo &&
                              h.disponibilidad.modulo_fin >= modulo
                          )?.id_disp
                        );

                        return (
                          <td
                            className="thhh"
                            style={{
                              backgroundColor: colores[modulo % colores.length],
                              textAlign: 'center',
                              border: isSelected
                                ? '2px solid white' // Resalta el borde blanco si el módulo está seleccionado
                                : isRedBorder
                                  ? '2px solid red' // Borde rojo para los módulos con el mismo id_disp
                                  : 'none'
                            }}
                            key={modulo}
                          >
                            {obtenerContenidoCelda(dia, parseInt(modulo), horariosGrado)}
                          </td>
                        );
                      })}
                    </tr>
                  ))}
                </tbody>
              </table>
            </div>
          </div>
        );
      })}
      {/* Panel contextual */}
      {contextMenu.visible && (
        <div className="context-menu" style={{ top: contextMenu.y, left: contextMenu.x }}>
          <h4>{contextMenu.contenido.unidadCurricular}</h4>
          <p>Modalidad: {contextMenu.contenido.modalidad}</p>
          <p>Aula: {contextMenu.contenido.aula}</p>
          <p>Docente: {contextMenu.contenido.docenteNombre}</p>
          <p>ID: {contextMenu.contenido.id_disp}</p>
          <button onClick={() => handleEliminar(contextMenu.contenido.id_disp)}>Eliminar</button>
        </div>
      )}
    </div>
  );
};

export default TablaHorario;
