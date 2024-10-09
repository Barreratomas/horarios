// Definición de rutas
export const getRoutes = () => ({
  base: '/horarios',
  home: '/',
  aulas: {
    main: 'aulas',
    crear: 'aulas/crear',
    actualizar: (aulaId) => `aulas/actualizar/${aulaId}` // ID al final
  },
  materias: {
    main: 'materias',
    crear: 'materias/crear',
    actualizar: (materiaId) => `materias/actualizar/${materiaId}` // ID al final
  },
  carreras: {
    main: 'carreras',
    crear: 'carreras/crear',
    actualizar: (carreraId) => `carreras/actualizar/${carreraId}` // ID al final
  },
  comisiones: {
    main: 'comisiones',
    crear: 'comisiones/crear',
    actualizar: (comisionId) => `comisiones/actualizar/${comisionId}` // ID al final
  },
  asignaciones: 'asignaciones',
  crearHorarioPrevio: (dni) => `crear-horario-previo/${dni}`,
  actualizarHorarioPrevio: (hpdId, dmId) => `actualizar-horario-previo/${hpdId}/${dmId}`,
  planilla: {
    alumnos: 'planilla-alumnos',
    bedelia: 'planilla-bedelia',
    docente: 'planilla-docente'
  }
});
