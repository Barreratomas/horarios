import React from 'react';
import Table from '../layouts/parcials/table'; // Suponiendo que el componente de la tabla se llama Table
import FormularioHoraio from '../layouts/parcials/formularioHorario';

const Horario = () => {
  //const hideMenu = true; // Simulando la variable PHP $hideMenu
  return (
    <div className="container">
      <p>hola</p>
      <FormularioHoraio />
      <div className="row">
        <Table /> {/* Renderizar la tabla */}
      </div>
    </div>
  );
};

export default Horario;
