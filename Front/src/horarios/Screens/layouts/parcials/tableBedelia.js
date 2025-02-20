import '../../../css/tabla.css';
import React, { useState } from 'react';

const TablaHorario = ({ horarios: initialHorarios }) => {
  const [horarios, setHorarios] = useState(initialHorarios);

  const [filtroGrado, setFiltroGrado] = useState('');
  const [filtroDivision, setFiltroDivision] = useState('');
  const [filtroCarrera, setFiltroCarrera] = useState('');

  const [selectedModule, setSelectedModule] = useState(null);
  const [selectedIds, setSelectedIds] = useState(new Set());
  const [isSelecting, setIsSelecting] = useState(false);
  const [actionType, setActionType] = useState(null);
  const [showModal, setShowModal] = useState(false);
  const [formData, setFormData] = useState({
    aula: '',
    docente: '',
    materia: '',
    modalidad: 'p' // Por defecto presencial
  });
  const [getAulas, SetGetAulas] = useState([]);
  const [getDocentes, SetGetDocentes] = useState([]);
  const [getUcs, SetGeUcs] = useState([]);
  const [module, setModule] = useState('');
  const [day, setDay] = useState('');
  const [currentIDCarreraGrado, setCurrentIDCarreraGrado] = useState('');

  console.log(horarios);

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

    setSelectedModule({ dia, modulo });

    if (isSelecting) {
      setSelectedIds((prev) => {
        const newSet = new Set(prev);

        if (actionType === 'update') {
          // Añadir la nueva selección
          newSet.add(JSON.stringify({ dia, modulo, id_disp }));

          // Convertir el Set en un array para verificar el tamaño
          const newArray = Array.from(newSet);

          // Si ya hay más de dos elementos, eliminar el más antiguo
          if (newArray.length > 2) {
            newArray.shift();
          }

          // Actualizar estado con el nuevo conjunto
          const updatedSet = new Set(newArray);
          console.log('ids actualizados:', Array.from(updatedSet)); // Mostrar el valor actualizado
          return updatedSet;
        } else {
          // Comportamiento por defecto para otros casos
          newSet.add(JSON.stringify({ dia, modulo, id_disp }));
          console.log('ids actualizados (default):', Array.from(newSet));
          return newSet;
        }
      });
    } else {
      setContextMenu({ visible: true, x, y, contenido });
    }
  };

  const handleClickOutside = () => {
    if (isSelecting) {
      return;
    }

    setContextMenu({ ...contextMenu, visible: false });
    setSelectedModule(null); // Limpia el módulo seleccionado al hacer clic fuera
    if (!isSelecting) setSelectedIds(new Set());
  };

  const iniciarSeleccion = (action) => {
    setActionType(action);
    setSelectedIds(new Set()); // Limpiar selecciones anteriores
    setIsSelecting(true);
    setContextMenu({ visible: false, x: 0, y: 0, contenido: null });
  };

  const cancelarSeleccion = () => {
    setIsSelecting(false);
    setSelectedIds(new Set());
    setSelectedModule(null); // Limpia el módulo seleccionado
    setActionType(null);
  };

  const enableModal = async (id_carrera_grado) => {
    await getDisponibles(id_carrera_grado);
    setCurrentIDCarreraGrado(id_carrera_grado);
    setShowModal(true);
  };

  const disabledModal = () => {
    SetGetAulas([]);
    SetGetDocentes([]);
    SetGeUcs([]);
    setModule('');
    setDay('');
    setCurrentIDCarreraGrado('');
    setShowModal(false);
  };

  const handleModalSubmit = async () => {
    setShowModal(false);
    await asignarHorario();
  };

  const getDisponibles = async (id_carrera_grado) => {
    try {
      const { dia, modulo } = selectedModule;
      const body = {
        dia,
        modulo,
        id_carrera_grado
      };
      const response = await fetch(
        `http://127.0.0.1:8000/api/horarios/disponibilidad/disponibles`,
        {
          method: 'POST',
          headers: { 'Content-Type': 'application/json' },
          body: JSON.stringify(body)
        }
      );
      const data = await response.json();
      if (data.error) {
        console.log('error nas');
      } else {
        console.log(data);
        SetGetAulas(data.aulasDisponibles || []);
        SetGeUcs(data.ucDisponibles || []);
        setModule(body.modulo);
        setDay(body.dia);
      }
    } catch (error) {
      console.log(error);
    }
  };

  const getDocentesDisponibles = async (id_uc) => {
    console.log(id_uc);
    try {
      const body = {
        dia: day,
        modulo: module,
        id_uc
      };
      const response = await fetch(
        `http://127.0.0.1:8000/api/horarios/disponibilidad/disponiblesDocentes`,
        {
          method: 'POST',
          headers: { 'Content-Type': 'application/json' },
          body: JSON.stringify(body)
        }
      );
      const data = await response.json();
      if (data.error) {
        console.log('error naaaaaaaaaaaaaaaas');
      } else {
        console.log(data);
        SetGetDocentes(data.docentesDisponibles || []);
      }
    } catch (error) {
      console.log('errorrrrrrrrrrrrrr');

      console.log(error);
    }
  };

  const asignarHorario = async () => {
    try {
      console.log(day);
      const body = {
        dia: day,
        modulo: module,
        id_uc: formData.materia,
        id_docente: formData.docente,
        id_aula: formData.aula,
        id_carrera_grado: currentIDCarreraGrado,
        modalidad: formData.modalidad
      };

      const response = await fetch(`http://127.0.0.1:8000/api/horarios/disponibilidad/asignar`, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(body)
      });

      const data = await response.json();

      if (data.error) {
        console.error('Error del servidor:', data.error);
      } else {
        setHorarios(data);
      }
    } catch (error) {
      console.error('Error al asignar el horario:', error);
    } finally {
      setContextMenu({ ...contextMenu, visible: false });
      setModule('');
      setDay('');
      setCurrentIDCarreraGrado('');
    }
  };

  // Función para eliminar el horario
  const confirmarEliminacion = async () => {
    try {
      const disponibilidades = Array.from(selectedIds).map((item) => JSON.parse(item));

      console.log(Array.from(disponibilidades));
      const response = await fetch(`http://127.0.0.1:8000/api/horarios/disponibilidad/eliminar`, {
        method: 'DELETE',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ disponibilidades }) // Enviar el array de objetos en formato JSON
      });

      const data = await response.json();

      if (data.error) {
        console.error('Error del servidor:', data.error);
      } else {
        console.log('Horario eliminadossssssss:', data);

        // Actualizar el estado de horarios eliminando el horario con el id_disp
        setHorarios(data);
      }
    } catch (error) {
      console.error('Error al eliminar el horario:', error);
    } finally {
      setContextMenu({ ...contextMenu, visible: false });
    }
  };

  // Función para actualizar los módulos seleccionados
  const confirmarActualizacion = async () => {
    if (selectedIds.size !== 2) {
      console.log('la seleccion de modulos debe ser 2', selectedIds.size);
      return;
    }

    try {
      const disponibilidades = Array.from(selectedIds).map((item) => JSON.parse(item));
      console.log(Array.from(disponibilidades));
      const response = await fetch(`http://127.0.0.1:8000/api/horarios/disponibilidad/actualizar`, {
        method: 'PUT',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ disponibilidades }) // Enviar el array de objetos en formato JSON
      });

      const data = await response.json();

      if (data.error) {
        console.error('Error del servidor:', data.error);
      } else {
        console.log('Horario actualizado:', data);

        // Actualizar el estado de horarios con los nuevos datos
        setHorarios(data);
      }
    } catch (error) {
      console.error('Error al actualizar el horario:', error);
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

  const horariosFiltrados = Object.entries(horariosPorCarrera).filter(([, horariosGrado]) => {
    const carrera = horariosGrado[0]?.disponibilidad?.carrera_grado?.carrera?.carrera || '';
    const grado = horariosGrado[0]?.disponibilidad?.carrera_grado?.grado?.grado || '';
    const division = horariosGrado[0]?.disponibilidad?.carrera_grado?.grado?.division || '';

    return (
      (filtroCarrera === '' || carrera.toString() === filtroCarrera) &&
      (filtroGrado === '' || grado.toString() === filtroGrado) &&
      (filtroDivision === '' || division.toString() === filtroDivision)
    );
  });

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
      const modalidad = horario.disponibilidad.id_aula === 'V' ? 'V' : '';

      const aula = modalidad === 'V' ? '' : horario.disponibilidad.aula?.nombre || 'Sin Aula';
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
    const idCarreraGrado =
      horariosGrado?.[0]?.disponibilidad?.carrera_grado?.id_carrera_grado || 'N/A';

    return (
      <div
        className="vacio"
        onContextMenu={(e) => {
          handleRightClick(
            e,
            {
              unidadCurricular: 'Sin Unidad Curricular',
              modalidad: 'N/A',
              aula: 'Sin Aula',
              docenteNombre: 'Sin Docente',
              id_disp: null,
              idCarreraGrado
            },
            dia,
            modulo,
            null // No hay id_disp en celdas vacías
          );
          console.log(
            `Celda vacía seleccionada -> ID Carrera/Grado: ${horariosGrado?.[0]?.disponibilidad?.carrera_grado?.id_carrera_grado || 'N/A'}, Día: ${dia}, Módulo: ${modulo}`
          );
        }}
      >
        <div className="test-vacio">-</div>
      </div>
    );
  };

  return (
    <div onClick={handleClickOutside}>
      <h3>Horarios Bedelia</h3>
      <div className="filtros">
        <label>
          Grado:
          <select value={filtroGrado} onChange={(e) => setFiltroGrado(e.target.value)}>
            <option value="">Todos</option>
            {[
              ...new Set(horarios.map((h) => h.disponibilidad?.carrera_grado?.grado?.grado || ''))
            ].map((grado, index) => (
              <option key={index} value={grado}>
                {grado}
              </option>
            ))}
          </select>
        </label>

        <label>
          División:
          <select value={filtroDivision} onChange={(e) => setFiltroDivision(e.target.value)}>
            <option value="">Todas</option>
            {[
              ...new Set(
                horarios.map((h) => h.disponibilidad?.carrera_grado?.grado?.division || '')
              )
            ].map((division, index) => (
              <option key={index} value={division}>
                {division}
              </option>
            ))}
          </select>
        </label>
        <label>
          Carrera:
          <select value={filtroCarrera} onChange={(e) => setFiltroCarrera(e.target.value)}>
            <option value="">Todas</option>
            {[
              ...new Set(
                horarios.map((h) => h.disponibilidad?.carrera_grado?.carrera?.carrera || '')
              )
            ]
              .filter(Boolean)
              .map((carrera, index) => (
                <option key={index} value={carrera}>
                  {carrera}
                </option>
              ))}
          </select>
        </label>
      </div>

      {isSelecting ? (
        <div className="seleccion-container">
          <div className="barra-opciones">
            <p>Selecciona los módulos a {actionType === 'delete' ? 'eliminar' : 'actualizar'}</p>
            <div className="buttons">
              <button
                onClick={actionType === 'delete' ? confirmarEliminacion : confirmarActualizacion}
              >
                Confirmar {actionType === 'delete' ? 'Eliminación' : 'Actualización'}
              </button>

              <button onClick={cancelarSeleccion}>Cancelar</button>
            </div>
          </div>
        </div>
      ) : null}

      {horariosFiltrados.map(([idCarreraGrado, horariosGrado]) => {
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
      {contextMenu.visible && !isSelecting && (
        <div className="context-menu" style={{ top: contextMenu.y, left: contextMenu.x }}>
          {contextMenu.contenido.id_disp ? (
            <>
              <h4>{contextMenu.contenido.unidadCurricular}</h4>
              <p>Modalidad: {contextMenu.contenido.modalidad}</p>
              <p>Aula: {contextMenu.contenido.aula}</p>
              <p>Docente: {contextMenu.contenido.docenteNombre}</p>
              <p>ID: {contextMenu.contenido.id_disp}</p>
              <button onClick={() => iniciarSeleccion('delete')}>Eliminar</button>
              <button onClick={() => iniciarSeleccion('update')}>Actualizar</button>
            </>
          ) : (
            <>
              <p>ID: {contextMenu.contenido.idCarreraGrado}</p>

              <button onClick={() => iniciarSeleccion('update')}>Actualizar</button>
              <button onClick={() => enableModal(contextMenu.contenido.idCarreraGrado)}>
                Asignar
              </button>
            </>
          )}
        </div>
      )}

      {showModal && (
        <div className="modal">
          <div className="modal-content">
            <h3>Asignar Horario</h3>
            <form
              onSubmit={(e) => {
                e.preventDefault();
                handleModalSubmit();
              }}
            >
              <label>
                Aula:
                <select
                  value={formData.aula}
                  onChange={(e) => setFormData({ ...formData, aula: e.target.value })}
                  required
                >
                  <option value="" disabled>
                    Selecciona un aula
                  </option>
                  {getAulas.map((aula) => (
                    <option key={aula.id_aula} value={aula.id_aula}>
                      {aula.nombre} {/* Reemplaza `nombre` por la propiedad correcta */}
                    </option>
                  ))}
                </select>
              </label>

              <label>
                Materia:
                <select
                  value={formData.materia}
                  onChange={(e) => {
                    const selectedUc = e.target.value;
                    setFormData({ ...formData, materia: selectedUc });
                    getDocentesDisponibles(selectedUc); // Llamada a la función al seleccionar la materia
                  }}
                  required
                >
                  <option value="" disabled>
                    Selecciona una UC
                  </option>
                  {getUcs.map((uc) => (
                    <option key={uc.id_uc} value={uc.id_uc}>
                      {uc.unidad_curricular}
                      {/* Aquí uc es solo un ID; ajusta si hay más datos */}
                    </option>
                  ))}
                </select>
              </label>

              <label>
                Docente:
                <select
                  value={formData.docente}
                  onChange={(e) => setFormData({ ...formData, docente: e.target.value })}
                  required
                >
                  <option value="" disabled>
                    Selecciona un docente
                  </option>
                  {getDocentes.map((docente) => (
                    <option key={docente.id_docente} value={docente.id_docente}>
                      {docente.nombre} {docente.apellido}
                      {/* Reemplaza `nombre` por la propiedad correcta */}
                    </option>
                  ))}
                </select>
              </label>

              <label>
                Modalidad:
                <select
                  value={formData.modalidad}
                  onChange={(e) => setFormData({ ...formData, modalidad: e.target.value })}
                >
                  <option value="p">Presencial</option>
                  <option value="v">Virtual</option>
                </select>
              </label>

              <button type="submit">Confirmar</button>
              <button type="button" onClick={() => disabledModal()}>
                Cancelar
              </button>
            </form>
          </div>
        </div>
      )}
    </div>
  );
};

export default TablaHorario;
