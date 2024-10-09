import React from 'react';
import '../../../css/tabla.css';
// ESTO ES SOLAMENTE LA ESTRUCTURA
const Tabla = () => {
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

  const dias = ['lunes', 'martes', 'miércoles', 'jueves', 'viernes'];

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

  return (
    <div className="bedelia-horario">
      <h3 style={{ fontFamily: 'sans-serif', color: 'white' }}>Año: 1</h3>
      <h4 style={{ fontFamily: 'sans-serif', color: 'white' }}>División: A</h4>
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
              <th className="dias" style={dia === 'viernes' ? { borderRadius: '0 0 0 20px' } : {}}>
                {dia}
              </th>
              {Object.keys(inicio).map((modulo) => (
                <td
                  className="thhh"
                  style={{ backgroundColor: colores[modulo % colores.length] }}
                  key={modulo}
                >
                  {' '}
                </td>
              ))}
            </tr>
          ))}
        </tbody>
      </table>
    </div>
  );
};

export default Tabla;

// import React from 'react';
// import '../../../css/tabla.css';

// const Tabla = ({ userType, horariosAgrupados, horarios }) => {
//   const inicio = {
//     1: '19:20',
//     2: '20:00',
//     3: '20:40',
//     4: '21:30',
//     5: '22:10',
//     6: '22:50'
//   };
//   const fin = {
//     1: '20:00',
//     2: '20:40',
//     3: '21:20',
//     4: '22:10',
//     5: '22:50',
//     6: '23:30'
//   };
//   const dias = ['lunes', 'martes', 'miércoles', 'jueves', 'viernes'];
//   const colores = [
//     'rgba(250, 22, 22, 0.38)',
//     'rgba(22, 72, 250, 0.28)',
//     'rgba(54, 250, 22, 0.28)',
//     'rgba(22, 250, 236, 0.28)',
//     'rgba(246, 250, 22, 0.28)',
//     'rgba(250, 22, 200, 0.28)',
//     'rgba(122, 22, 250, 0.28)',
//     'rgba(250, 131, 22, 0.28)'
//   ];

//   const horasPermitidas = {
//     1: '19:20',
//     2: '20:00',
//     3: '20:40',
//     4: '21:20',
//     5: '21:30',
//     6: '22:10',
//     7: '22:50'
//   };

//   // Tabla para 'bedelia' o 'admin'
//   const TablaHorario = ({ horariosPorDivision }) => (
//     <table className="planilla1">
//       <thead className="horarios">
//         <tr>
//           <th>Días / Horarios</th>
//           {Object.keys(inicio).map((i) => (
//             <th key={i}>
//               {inicio[i]} - {fin[i]}
//             </th>
//           ))}
//         </tr>
//       </thead>
//       <tbody>
//         {dias.map((dia) => (
//           <tr key={dia}>
//             <th>{dia}</th>
//             {horariosPorDivision.map((horario, index) =>
//               horario.dia === dia ? (
//                 Object.keys(inicio).map((modulo) => (
//                   <td
//                     key={modulo}
//                     style={{
//                       backgroundColor: colores[Math.floor(Math.random() * colores.length)]
//                     }}
//                   >
//                     {modulo >= horario.modulo_inicio && modulo < horario.modulo_fin ? (
//                       <>
//                         <div>{horario.disponibilidad.docenteMateria.materia.nombre}</div>
//                         <div>{`${horario.disponibilidad.docenteMateria.docente.nombre} ${horario.disponibilidad.docenteMateria.docente.apellido}`}</div>
//                         <div>{horario.disponibilidad.docenteMateria.aula.nombre}</div>
//                         <div>{horario.carrera.nombre}</div>
//                       </>
//                     ) : (
//                       ''
//                     )}
//                   </td>
//                 ))
//               ) : (
//                 <td key={index}></td>
//               )
//             )}
//           </tr>
//         ))}
//       </tbody>
//     </table>
//   );

//   // Tabla para 'estudiante'
//   const TablaEstudiante = ({ horarios }) => (
//     <table border="1">
//       <thead>
//         <tr>
//           <th>Día</th>
//           <th>Modulo Inicio</th>
//           <th>Modulo Fin</th>
//           <th>V/P</th>
//           <th>Aula</th>
//           <th>Materia</th>
//           <th>Docente</th>
//         </tr>
//       </thead>
//       <tbody>
//         {horarios.map((horario, index) => (
//           <tr key={index}>
//             <td>{horario.dia || 'N/A'}</td>
//             <td>{horasPermitidas[horario.modulo_inicio] || 'N/A'}</td>
//             <td>{horasPermitidas[horario.modulo_fin] || 'N/A'}</td>
//             <td>{horario.v_p === 'p' ? 'Presencial' : 'Virtual'}</td>
//             <td>{horario.aula || 'N/A'}</td>
//             <td>{horario.materia || 'N/A'}</td>
//             <td>{`${horario.disponibilidad.docenteMateria.docente.nombre || 'N/A'} ${
//               horario.disponibilidad.docenteMateria.docente.apellido || 'N/A'
//             }`}</td>
//           </tr>
//         ))}
//       </tbody>
//     </table>
//   );

//   // Tabla para 'docente'
//   const TablaDocente = ({ horariosPorDivision }) => (
//     <table border="1">
//       <thead>
//         <tr>
//           <th>Día</th>
//           <th>Modulo Inicio</th>
//           <th>Modulo Fin</th>
//           <th>V/P</th>
//           <th>Aula</th>
//           <th>Materia</th>
//           <th>Comisión</th>
//         </tr>
//       </thead>
//       <tbody>
//         {horariosPorDivision.map((horario, index) => (
//           <tr key={index}>
//             <td>{horario.dia || 'N/A'}</td>
//             <td>{horasPermitidas[horario.modulo_inicio] || 'N/A'}</td>
//             <td>{horasPermitidas[horario.modulo_fin] || 'N/A'}</td>
//             <td>{horario.v_p === 'p' ? 'Presencial' : 'Virtual'}</td>
//             <td>{horario.aula || 'N/A'}</td>
//             <td>{horario.materia || 'N/A'}</td>
//             <td>{`${horario.anio || 'N/A'}°${horario.division || 'N/A'}`}</td>
//           </tr>
//         ))}
//       </tbody>
//     </table>
//   );

//   return (
//     <div className="container">
//       {userType === 'bedelia' || userType === 'admin' ? (
//         horariosAgrupados.map((carrera, horariosPorCarrera) =>
//           Object.entries(horariosPorCarrera).map(([anio, divisionesPorAnio]) => (
//             <div key={anio}>
//               <h3 style={{ fontFamily: 'sans-serif', color: 'white' }}>Año: {anio}</h3>
//               {Object.entries(divisionesPorAnio).map(([division, horariosPorDivision]) => (
//                 <div key={division}>
//                   <h4 style={{ fontFamily: 'sans-serif', color: 'white' }}>División: {division}</h4>
//                   <TablaHorario horariosPorDivision={horariosPorDivision} />
//                 </div>
//               ))}
//             </div>
//           ))
//         )
//       ) : userType === 'estudiante' ? (
//         <TablaEstudiante horarios={horarios} />
//       ) : userType === 'docente' ? (
//         Object.entries(horariosAgrupados).map(([anio, divisionesPorAnio]) => (
//           <div key={anio}>
//             <h2>Año: {anio}</h2>
//             {Object.entries(divisionesPorAnio).map(([division, horariosPorDivision]) => (
//               <div key={division}>
//                 <strong>División: {division}</strong>
//                 <TablaDocente horariosPorDivision={horariosPorDivision} />
//               </div>
//             ))}
//           </div>
//         ))
//       ) : null}
//     </div>
//   );
// };

// export default Tabla;
