// Definición de rutas
export const getRoutes = () => ({
  base: '/horarios',
  home: '/',
  aulas: {
    main: 'aulas',
    crear: 'aulas/crear',
    actualizar: (aulaId) => `aulas/actualizar/${aulaId}`
  },
  materias: {
    main: 'materias',
    crear: 'materias/crear',
    actualizar: (materiaId) => `materias/actualizar/${materiaId}`
  },
  carreras: {
    main: 'carreras',
    crear: 'carreras/crear',
    actualizar: (carreraId) => `carreras/actualizar/${carreraId}`
  },
  comisiones: {
    main: 'comisiones',
    crear: 'comisiones/crear',
    actualizar: (comisionId) => `comisiones/actualizar/${comisionId}`
  },
  asignaciones: 'asignaciones',
  crearHorarioPrevio: (dni) => `crear-horario-previo/${dni}`,
  actualizarHorarioPrevio: (hpdId, dmId) => `actualizar-horario-previo/${hpdId}/${dmId}`,
  planilla: {
    alumnos: 'planilla-alumnos',
    bedelia: 'planilla-bedelia',
    docente: 'planilla-docente'
  },
  planes: {
    main: 'planes',
    crear: 'planes/crear',
    actualizar: (planId) => `planes/actualizar/${planId}`
  }
});
