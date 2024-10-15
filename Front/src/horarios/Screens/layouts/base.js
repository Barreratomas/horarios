// import React, { useEffect } from 'react';
import { Outlet } from 'react-router-dom';
// import { useNavigate } from 'react-router-dom';
import Menu from './parcials/menu';
import 'bootstrap/dist/css/bootstrap.min.css';
import '../../css/index.css';
import '../../css/notificacion.css';
import '../../css/formularios.css';
import '../../css/indexSecciones.css';

import { useEffect } from 'react';
import { getRoutes } from '../../Routes';
// Componente principal
const Base = ({ hideMenu }) => {
  const routes = getRoutes(); // Llamada a la función para obtener las rutas
  // const navigate = useNavigate();

  useEffect(() => {
    sessionStorage.setItem('userType', 'admin');
  });

  // useEffect(() => {
  //   // Redirige si no hay userType
  //   if (!sessionStorage.getItem('userType')) {
  //     navigate('/logout');
  //   }

  //   // Eliminar mensajes después de un segundo
  //   const timer = setTimeout(() => {
  //     const errorMessages = document.querySelectorAll('.alert-danger');
  //     errorMessages.forEach((message) => {
  //       message.parentElement.classList.add('hide-messages');
  //       setTimeout(() => {
  //         message.remove();
  //       }, 3500);
  //     });

  //     const successMessages = document.querySelectorAll('.alert-success');
  //     successMessages.forEach((message) => {
  //       message.parentElement.classList.add('hide-messages');
  //       setTimeout(() => {
  //         message.remove();
  //       }, 3500);
  //     });
  //   }, 3500);

  //   return () => clearTimeout(timer); // Limpiar el timer
  // }, [navigate]);

  return (
    <div>
      {!hideMenu && <Menu />} {/* Muestra el menú si hideMenu no está definido */}
      <Outlet context={{ routes }} />
    </div>
  );
};

export default Base;
