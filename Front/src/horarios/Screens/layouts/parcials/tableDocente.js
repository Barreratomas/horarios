import React from 'react';
import '../../../css/tabla.css';

const TablaHorario = ({ horarios }) => {
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
      const modalidad = horario.disponibilidad.id_aula === 'V' ? 'V' : '';

      const aula = modalidad === 'V' ? '' : horario.disponibilidad.aula?.nombre || 'Sin Aula';
      const docenteNombre = horario.disponibilidad.docente
        ? `${horario.disponibilidad.docente.nombre} ${horario.disponibilidad.docente.apellido}`
        : 'Sin Docente';

      return (
        <div className="horario-info">
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
    <div>
      {Object.entries(horariosPorCarrera).map(([idCarreraGrado, horariosGrado]) => {
        const grado =
          horariosGrado?.[0]?.disponibilidad?.carrera_grado?.grado?.grado || 'Sin Grado';
        const division =
          horariosGrado?.[0]?.disponibilidad?.carrera_grado?.grado?.division || 'Sin División';

        return (
          <div key={idCarreraGrado}>
            <div className="comision">
              <h4>Grado: {grado}</h4>
              <h4>División: {division}</h4>
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

                      {Object.keys(inicio).map((modulo) => (
                        <td
                          className="thhh"
                          style={{
                            backgroundColor: colores[modulo % colores.length],
                            textAlign: 'center'
                          }}
                          key={modulo}
                        >
                          {obtenerContenidoCelda(dia, parseInt(modulo), horariosGrado)}
                        </td>
                      ))}
                    </tr>
                  ))}
                </tbody>
              </table>
            </div>
          </div>
        );
      })}
    </div>
  );
};

export default TablaHorario;
