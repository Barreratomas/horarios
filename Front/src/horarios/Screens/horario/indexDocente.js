import React from 'react';
import Table from '../layouts/parcials/table'; // Suponiendo que el componente de la tabla se llama Table
import FormularioHorarioDocente from '../layouts/parcials/formularioHorarioDocente';

const HorarioDocente = () => {
  const hideMenu = true; // Simulación de variable de PHP

  return (
    <div>
      {/* Lógica para ocultar el menú si es necesario */}
      {hideMenu && <div className="hidden-menu">Menu oculto</div>}

      {/* Aquí va el formulario de horario docente */}
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
