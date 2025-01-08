CREATE DATABASE urquiza;

SET time_zone = 'America/Argentina/Buenos_Aires';

# CREACION DE TABLAS

CREATE TABLE Usuarios (
    dni VARCHAR(255) PRIMARY KEY,
    nombre VARCHAR(255),
    apellido VARCHAR(255),
    email VARCHAR(255),
    contrasenia VARCHAR(255),
    tipo ENUM('alumno', 'docente', 'admin'),
    fecha_creacion DATETIME,
    fecha_modificacion DATETIME
);

CREATE TABLE Comisiones (
    id INT AUTO_INCREMENT PRIMARY KEY,
    anio INT,
    division INT,
    carrera ENUM('DS', 'ITI', 'AF'),
    fecha_creacion DATETIME,
    fecha_modificacion DATETIME
);

CREATE TABLE Aulas (
    nro INT PRIMARY KEY,
    laboratorio BOOL,
    fecha_creacion DATETIME,
    fecha_modificacion DATETIME
);


CREATE TABLE Horarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    inicio TIME,
    fin TIME,
    dia ENUM('L', 'MA', 'MI', 'J', 'V'),
    fecha_creacion DATETIME,
    fecha_modificacion DATETIME
);

CREATE TABLE Materias (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(255),
    fecha_creacion DATETIME,
    fecha_modificacion DATETIME
);
# RELACIONES ENTRE TABLAS

CREATE TABLE Alumnos_comision (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_alumno INT,
    id_comision INT,
    FOREIGN KEY (id_alumno) REFERENCES Alumnos(id),
    FOREIGN KEY (id_comision) REFERENCES Comisiones(id),
    UNIQUE KEY unique_alumno_comision (id_alumno, id_comision)
);

CREATE TABLE Cambios_docente (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_docente_anter INT,
    id_docente_nuevo INT,
    fecha_modificacion DATE,
    FOREIGN KEY (id_docente_anter) REFERENCES Docentes(id),
    FOREIGN KEY (id_docente_nuevo) REFERENCES Docentes(id),
	UNIQUE KEY unique_docentes (id_docente_anter, id_docente_nuevo)
);

CREATE TABLE Aulas_materia (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_aula INT,
    id_materia INT,
    FOREIGN KEY (id_aula) REFERENCES Aulas(id),
    FOREIGN KEY (id_materia) REFERENCES Materias(id),
	UNIQUE KEY unique_aula_materia (id_aula, id_materia)
);

CREATE TABLE Docentes_materia (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_docente INT,
    id_materia INT,
    FOREIGN KEY (id_docente) REFERENCES Docentes(id),
    FOREIGN KEY (id_materia) REFERENCES Materias(id),
	UNIQUE KEY unique_docente_materia (id_docente, id_materia)
);

CREATE TABLE Horarios_materia (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_horario INT,
    id_materia INT,
    FOREIGN KEY (id_horario) REFERENCES Horarios(id),
    FOREIGN KEY (id_materia) REFERENCES Materias(id),
	UNIQUE KEY unique_horario_materia (id_horario, id_materia)
);

CREATE TABLE Comisiones_materia (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_comision INT,
    id_materia INT,
    FOREIGN KEY (id_comision) REFERENCES Comisiones(id),
    FOREIGN KEY (id_materia) REFERENCES Materias(id),
	UNIQUE KEY unique_comision_materia (id_comision, id_materia)
);



#-----------------------------------------------------------------

# CARGAR DATOS

INSERT INTO Alumnos (nombre, apellido, email) VALUES
('Juan', 'Pérez', 'juan@example.com'),
('María', 'González', 'maria@example.com'),
('Pedro', 'López', 'pedro@example.com');


INSERT INTO Comisiones (anio, division, carrera) VALUES
(1, 1, 'DS'),
(1, 2, 'ITI'),
(1, 3, 'AF'),
(2, 1, 'DS'),
(2, 2, 'ITI'),
(2, 3, 'AF'),
(3, 1, 'DS'),
(3, 2, 'ITI'),
(3, 3, 'AF');

INSERT INTO Aulas (nombre, laboratorio) VALUES
('Aula 101', 0),
('Laboratorio 1', 1),
('Aula 202', 0);

INSERT INTO Docentes (nombre, apellido, email) VALUES
('Carlos', 'Martínez', 'carlos@example.com'),
('Ana', 'Rodríguez', 'ana@example.com'),
('Luis', 'García', 'luis@example.com');

INSERT INTO Horarios (inicio, fin, dia) VALUES
('08:00:00', '10:00:00', 'L'),
('10:30:00', '12:30:00', 'MA'),
('14:00:00', '16:00:00', 'MI');

INSERT INTO Materias (nombre) VALUES
('Matemáticas'),
('Programación'),
('Historia');

INSERT INTO Alumnos_comision (id_alumno, id_comision) VALUES
(1, 1),
(2, 2),
(3, 3);

INSERT INTO Cambios_docente (id_docente_anter, id_docente_nuevo, cambio) VALUES
(1, 2, '2023-01-15'),
(3, 1, '2023-02-20'),
(2, 3, '2023-03-10');

INSERT INTO Aulas_materia (id_aula, id_materia) VALUES
(1, 1),
(2, 2),
(3, 3);

INSERT INTO Docentes_materia (id_docente, id_materia) VALUES
(1, 1),
(2, 2),
(3, 3);

INSERT INTO Horarios_materia (id_horario, id_materia) VALUES
(1, 1),
(2, 2),
(3, 3);

INSERT INTO Comisiones_materia (id_comision, id_materia) VALUES
(1, 3),
(3, 2),
(6, 2),
(7, 1);



# Insertar

INSERT INTO Aulas (nombre, laboratorio, fecha_creacion, fecha_modificacion) VALUES
('Aula 101', 1, '2023-12-01 09:00:00', '2023-12-01 09:00:00'),
('Aula 102', 0, '2023-12-02 10:30:00', '2023-12-02 10:30:00'),
('Aula 103', 1, '2023-12-03 11:45:00', '2023-12-03 11:45:00'),
('Aula 104', 0, '2023-12-04 13:15:00', '2023-12-04 13:15:00'),
('Aula 105', 1, '2023-12-05 14:30:00', '2023-12-05 14:30:00');





