import React, { useState, useEffect } from 'react';

const Sesiones = () => {
  const [userType, setUserType] = useState(sessionStorage.getItem('userType') || 'admin');

  useEffect(() => {
    sessionStorage.setItem('userType', userType);
  }, [userType]); // Actualiza el almacenamiento cada vez que userType cambie

  const handleUserTypeChange = (event) => {
    const newUserType = event.target.value;
    setUserType(newUserType);
    window.location.reload(); // Opcional: recargar para reflejar cambios
  };

  return (
    <div className="session-selector">
      <label htmlFor="userType" className="form-label">
        Cambiar sesión:
      </label>
      <select
        id="userType"
        className="form-select"
        value={userType}
        onChange={handleUserTypeChange}
      >
        <option value="admin">Administrador</option>
        <option value="alumno">Alumno</option>
        <option value="docente">Docente</option>
      </select>
    </div>
  );
};

export default Sesiones;
