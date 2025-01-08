import React from 'react';
import '../css/home.css';

import logoUrquiza from '../images/home/logoUrquiza.png';
import AF from '../images/home/AF.png';
import DS from '../images/home/DS.png';
import ITI from '../images/home/ITI.png';
import FlechaAbajo from '../images/home/FlechaAbajo.png';
import circlitos from '../images/home/circulitos.png';
import Hombre from '../images/home/Hombre.png';
import Mujer from '../images/home/Mujer.png';
import Calendario from '../images/home/Calendario.png';

const Home = () => {
  return (
    <div className="bedelia-home">
      <section className="section">
        <div className="izquierda">
          <div className="logo-wrapper">
            <div className="logo">
              <div className="logo-display-parent">
                <div className="logo-display"></div>
                <img className="logo1-1-icon" alt="logo" src={logoUrquiza} />
              </div>
            </div>
          </div>
          <div className="carreras">
            <div className="af">
              <img className="image-5-icon" alt="AF" src={AF} />
            </div>
            <div className="ds">
              <img className="image-4-icon" alt="DS" src={DS} />
            </div>
            <div className="iti">
              <img className="image-6-icon" alt="ITI" src={ITI} />
            </div>
          </div>
        </div>
        <div className="derecha">
          <div className="titulo-wrapper">
            <h1 className="titulo">Planilla Horaria</h1>
          </div>
          <div className="subtitulo-parent">
            <h1 className="subtitulo">Propósito</h1>
            <b className="parrafo">
              <p className="en-este-sitio">
                En este sitio te brindaremos la planificación horaria tanto para alumnos como para
                docentes, brindándote así los horarios de acuerdo a cada comisión y carrera
                pertinente.
              </p>
              <p className="mejorando-asi-todo">
                Mejorando así todo el sistema de horarios de cursada y aumentando la eficiencia
                tanto para los Docentes como para los alumnos.
              </p>
            </b>
          </div>
          <div className="derecha-inner">
            <div className="boton-parent">
              <button className="boton">
                <div className="button">Ver más</div>
              </button>
              <div className="wrapper-flechaabajo-wrapper">
                <div className="wrapper-flechaabajo">
                  <img className="flechaabajo-icon" alt="FlechaAbajo" src={FlechaAbajo} />
                </div>
              </div>
            </div>
          </div>
        </div>
      </section>

      {/* Seccion 1 */}
      <section className="section1">
        <div className="title-paragraph-button">
          <div className="frame-parent">
            <div className="titulo-parent">
              <h1 className="titulo1">Carga de la base de datos alumnos</h1>
              <div className="parrafo1">
                Permite cargar a los estudiantes de las diferentes carreras, posibilitando así
                mejorar el sistema de planificación horaria.
              </div>
            </div>
            <button className="boton1">
              <div className="subir">Subir</div>
            </button>
          </div>
        </div>
        <div className="derecha1">
          <div className="pantalla">
            <div className="barra">
              <img className="circulitos-icon" alt="circlitos" src={circlitos} />
            </div>
            <div className="hombre-wrapper">
              <img className="hombre-icon" alt="Hombre" src={Hombre} />
            </div>
          </div>
        </div>
      </section>

      {/* Seccion 2 */}
      <section className="section2">
        <div className="inner">
          <div className="titulo-group">
            <h1 className="titulo2">Docentes para comisiones</h1>
            <div className="parrafo2">
              Permite cargar a los docentes en la comisión que les corresponde, mejorando la
              eficiencia de la planificación horaria.
            </div>
            <div className="boton-wrapper">
              <button className="boton2">
                <div className="subir1">Subir</div>
              </button>
            </div>
          </div>
        </div>
        <div className="pantalla1">
          <div className="barra1">
            <img className="circulitos-icon1" alt="circlitos" src={circlitos} />
          </div>
          <div className="mujer-wrapper">
            <img className="mujer-icon" alt="Mujer" src={Mujer} />
          </div>
        </div>
      </section>

      {/* Seccion 3 */}
      <section className="section3">
        <div className="pantalla2">
          <div className="barra2">
            <img className="circulitos-icon2" alt="circlitos" src={circlitos} />
          </div>
          <div className="pantalla-inner">
            <div className="grilla-parent">
              <div className="grilla">{/* Grid content goes here */}</div>
              <div className="calendario-wrapper">
                <img className="calendario-icon" alt="Calendario" src={Calendario} />
              </div>
            </div>
          </div>
        </div>
        <div className="child">
          <div className="titulo-container">
            <h1 className="titulo3">Aulas</h1>
            <div className="parrafo-parent">
              <div className="parrafo3">
                Permite ver la planificación horaria distribuida en un calendario académico en el
                cual tanto los docentes como los alumnos podrán guiarse de acuerdo a sus horas
                pactadas.
              </div>
              <button className="boton3">
                <div className="revisar">Revisar</div>
              </button>
            </div>
          </div>
        </div>
      </section>

      {/* Seccion 4 */}
      <section className="section4">
        <div className="transparencia">
          <div className="titulo-frame">
            <h1 className="titulo4">Consultas</h1>
          </div>
          <div className="parrafo-wrapper">
            <div className="parrafo4">
              Si tienes alguna consulta, no dudes en escribirnos aquí abajo o en nuestras redes.
            </div>
          </div>
          <div className="pantalla3">
            <div className="barra3">
              <img className="circulitos-icon3" alt="circlitos" src={circlitos} />
            </div>
            <div className="pantalla-child">
              <div className="frame-group">
                <div className="name-and-last-name-field-parent">
                  <div className="name-and-last-name-field">
                    <div className="rectangle-group">
                      <div className="frame-inner"></div>
                      <div className="nombre">Nombre</div>
                    </div>
                    <div className="rectangle-container">
                      <div className="frame-child1"></div>
                      <input className="apellido" placeholder="Apellido" type="text" />
                    </div>
                  </div>
                  <div className="input-query-box">
                    <div className="input-query-box-child"></div>
                    <input className="dni" placeholder="DNI" type="text" />
                  </div>
                </div>
                <div className="consulta-parent">
                  <textarea
                    className="consulta"
                    placeholder="Ingrese su consulta"
                    rows="13"
                    cols="68"
                  ></textarea>
                  <div className="send-button">
                    <button className="boton4">
                      <div className="enviar">Enviar</div>
                    </button>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </section>
    </div>
  );
};

export default Home;
