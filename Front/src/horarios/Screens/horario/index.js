import React from 'react';
import Table from '../layouts/parcials/table';
import FormularioHoraio from '../layouts/parcials/formularioHorario';

const Horario = () => {
  return (
    <div className="container">
      <p>hola</p>
      <FormularioHoraio />
      <div className="row">
        <Table />
      </div>
    </div>
  );
};

export default Horario;
