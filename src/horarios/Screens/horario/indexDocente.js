import React from 'react';
import Table from '../layouts/parcials/table'; // Suponiendo que el componente de la tabla se llama Table
import FormularioHorarioDocente from '../layouts/parcials/formularioHorarioDocente';

const HorarioDocente = () => {
  return (
    <div>
      <FormularioHorarioDocente />

      <div className="container">
        <div className="row">
          <Table />
        </div>
      </div>
    </div>
  );
};

export default HorarioDocente;
