// NotificationContext.js
import React, { createContext, useState, useContext } from 'react';
// import '../styles/notification.css';

// Crear el contexto
const NotificationContext = createContext();

// Hook para usar el contexto de notificaciones
export const useNotification = () => useContext(NotificationContext);

export const NotificationProvider = ({ children }) => {
  const [notifications, setNotifications] = useState([]);

  // Función para agregar una notificación
  const addNotification = (message, type = 'info') => {
    setNotifications((prev) => [...prev, { message, type }]);
    setTimeout(() => {
      setNotifications((prev) => prev.slice(1)); // elimina la notificación después de 5 segundos
    }, 5000);
  };

  return (
    <NotificationContext.Provider value={{ addNotification, notifications }}>
      {children}
      <Notifications notifications={notifications} />
    </NotificationContext.Provider>
  );
};

const Notifications = ({ notifications }) => {
  return (
    <div id="messages-container" className={notifications.length ? '' : 'hide-messages'}>
      {notifications.map((notification) => (
        <div
          key={notification.id}
          className={`notification alerta alert alert-${notification.type}`}
        >
          {notification.message}
        </div>
      ))}
    </div>
  );
};
