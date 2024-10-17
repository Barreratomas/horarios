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
    plan: (carreraId) => `carreras/plan/${carreraId}`,
    actualizar: (carreraId) => `carreras/actualizar/${carreraId}`
  },
  comisiones: {
    main: 'comisiones',
    crear: 'comisiones/crear',
    actualizar: (comisionId) => `comisiones/actualizar/${comisionId}`
  },
  horariosPreviosDocente: {
    main: 'horarios-previos-docentes',
    crear: 'horarios-previos-docentes/crear',
    actualizarHorarioPrevio: (hpdId) => `horarios-previos-docentes/actualizar/${hpdId}`
  },
  planilla: {
    alumnos: 'planilla-alumnos',
    bedelia: 'planilla-bedelia',
    docente: 'planilla-docente'
  },
  disponibilidad: {
    main: 'disponibilidad'
    // actualizar: (planId) => `planes/actualizar/${planId}` a espera de decision de como llevar a cabo
  },
  planes: {
    main: 'planes',
    crear: 'planes/crear',
    actualizar: (planId) => `planes/actualizar/${planId}`
  },
  asignacionesAlumno: {
    main: 'asignacion-alumno',
    crear: 'asignacion-alumno/crear',
    actualizar: (alumnoId) => `asignaciones-alumno/actualizar/${alumnoId}`
  }
});
