import React, { useEffect, useState } from 'react';

const Horarios = ({ horarios, userType }) => {
  const [horariosData, setHorariosData] = useState([]);

  // Configuraciones de horarios
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

  const dias = {
    1: 'lunes',
    2: 'martes',
    3: 'miercoles',
    4: 'jueves',
    5: 'viernes'
  };

  const colores = {
    1: 'rgba(250, 22, 22, 0.38)',
    2: 'rgba(22, 72, 250, 0.28)',
    3: 'rgba(54, 250, 22, 0.28)',
    4: 'rgba(22, 250, 236, 0.28)',
    5: 'rgba(246, 250, 22, 0.28)',
    6: 'rgba(250, 22, 200, 0.28)',
    7: 'rgba(122, 22, 250, 0.28)',
    8: 'rgba(250, 131, 22, 0.28)'
  };

  useEffect(() => {
    // Aquí iría la lógica para obtener los horarios desde un API si fuera necesario
    setHorariosData(horarios); // Supongo que el estado `horarios` se pasa como prop
  }, [horarios]);

  if (userType !== 'estudiante' && userType !== 'admin') {
    return null; // Si el tipo de usuario no es válido, no se renderiza nada
  }

  return (
    <table className="planilla1">
      <thead className="horarios">
        <tr>
          <th className="div">Días / Horarios</th>
          {[1, 2, 3, 4, 5, 6].map((i) => (
            <th key={i} className={`p${i}`}>
              {inicio[i]} - {fin[i]}
            </th>
          ))}
        </tr>
      </thead>
      <tbody>
        {Object.entries(dias).map(([key, dia]) => (
          <tr key={key} className="xd">
            <th className="dias" style={dia === 'viernes' ? { borderRadius: '0 0 0 20px' } : {}}>
              {dia}
            </th>
            {Array.from({ length: 6 }).map((_, i) => {
              const moduloInicio = i + 1;

              const horario = horariosData.find(
                (h) =>
                  h.dia === dia && h.modulo_inicio <= moduloInicio && h.modulo_fin > moduloInicio
              );

              if (horario) {
                return (
                  <td
                    key={i}
                    className="thhh"
                    style={{ backgroundColor: colores[Math.floor(Math.random() * 8) + 1] }}
                  >
                    <div className="elementos">
                      {horario.disponibilidad.docenteMateria.materia.nombre}
                    </div>
                    <div className="elementos" id="docente">
                      {horario.disponibilidad.docenteMateria.docente.nombre}{' '}
                      {horario.disponibilidad.docenteMateria.docente.apellido}
                    </div>
                    <div className="elementos" id="aula">
                      {horario.disponibilidad.docenteMateria.aula.nombre}
                    </div>
                  </td>
                );
              } else {
                return (
                  <td
                    key={i}
                    className="thhh"
                    style={{ backgroundColor: colores[Math.floor(Math.random() * 8) + 1] }}
                  />
                );
              }
            })}
          </tr>
        ))}
      </tbody>
    </table>
  );
};

export default Horarios;
