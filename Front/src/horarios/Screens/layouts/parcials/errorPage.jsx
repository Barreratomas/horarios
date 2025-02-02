import React from 'react';
import { useNavigate } from 'react-router-dom';
import '../../../css/errorPage.css';

const ErrorPage = ({ message = 'Página no encontrada', statusCode = 404 }) => {
  const navigate = useNavigate();

  const handleGoHome = () => {
    navigate('/horarios');
  };

  return (
    <div className="error-page-container">
      <h1 className="error-page-title">{statusCode} </h1>
      {message !== 'Página no encontrada' ? (
        <p className="error-page-message">
          {message} está en mantenimiento. Por favor, vuelva a intentarlo más tarde.
        </p>
      ) : (
        <p className="error-page-message">{message} </p>
      )}

      <button className="btn btn-home btn-primary" onClick={handleGoHome}>
        Volver al inicio
      </button>
    </div>
  );
};

export default ErrorPage;
