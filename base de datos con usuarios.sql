DROP DATABASE IF EXISTS dbs229806;

CREATE DATABASE dbs229806;

use dbs229806;

DROP TABLE IF EXISTS centros,
usuarios,
reportes,
pwdreset,
registros,
comentarios;

/* CREACION DE LA TABLA centros */
CREATE TABLE centros (
  id INTEGER AUTO_INCREMENT,
  nombre VARCHAR(250),
  direccion VARCHAR(250),
  poblacion VARCHAR(250),
  telefono INTEGER,
  activo BOOLEAN,
  PRIMARY KEY (id)
);

/* INSERCIÓN DE DATOS EN LA TABLA centros */
INSERT INTO
  centros(
    id,
    nombre,
    direccion,
    poblacion,
    telefono,
    activo
  )
VALUES
  (
    28062126,
    'IES Villaverde',
    'C/de la Alianza 20-24 - 28041',
    'Madrid',
    917239181,
    1
  ),
  (
    28020910,
    'IES Juan de la Cierva',
    'C/ Caoba nº 1',
    'Madrid',
    914670167,
    1
  );

/* CREACIÓN DE LA TABLA usuarios */
CREATE TABLE usuarios (
  id INTEGER AUTO_INCREMENT,
  nombre VARCHAR(250),
  apellidos VARCHAR(250),
  telefono INTEGER(25),
  email VARCHAR(250),
  password VARCHAR(500),
  cookie INTEGER,
  ultimaCon VARCHAR(250),
  ipCon VARCHAR(250),
  admin BOOLEAN,
  activo BOOLEAN,
  PRIMARY KEY (id)
);

/* INSERCIÓN DE DATOS EN LA TABLA usuarios */
INSERT INTO
  usuarios(
    nombre,
    apellidos,
    telefono,
    email,
    password,
    admin,
    activo
  )
values
  (
    "admin",
    "Administrador",
    123456789,
    "admin",
    "3a4a03738e77ac660e4f48f3daa20ba616c821ed25263ad334e154077a06c4191858c6512f5ec8bff9a1d0e6b8307cb32779b2e30bf42d3d262dd041aa590211",
    true,
    1
  ),
  (
    "Carlos",
    "Pascual",
    123456789,
    "carlos@pascual.email",
    "3a4a03738e77ac660e4f48f3daa20ba616c821ed25263ad334e154077a06c4191858c6512f5ec8bff9a1d0e6b8307cb32779b2e30bf42d3d262dd041aa590211",
    false,
    1
  ),
  (
    "Guillermo",
    "Diaz",
    123456789,
    "guillermo@diaz.email",
    "3a4a03738e77ac660e4f48f3daa20ba616c821ed25263ad334e154077a06c4191858c6512f5ec8bff9a1d0e6b8307cb32779b2e30bf42d3d262dd041aa590211",
    false,
    1
  ),
  (
    "Samuel",
    "De Luque",
    123456789,
    "Samuel@DL.email",
    "3a4a03738e77ac660e4f48f3daa20ba616c821ed25263ad334e154077a06c4191858c6512f5ec8bff9a1d0e6b8307cb32779b2e30bf42d3d262dd041aa590211",
    false,
    1
  ),
  (
    "Ibai",
    "Llanura",
    123456789,
    "ibai@llanura.email",
    "3a4a03738e77ac660e4f48f3daa20ba616c821ed25263ad334e154077a06c4191858c6512f5ec8bff9a1d0e6b8307cb32779b2e30bf42d3d262dd041aa590211",
    false,
    1
  ),
  (
    "Aquiles",
    "Castro",
    123456789,
    "Aquiles@castro.email",
    "3a4a03738e77ac660e4f48f3daa20ba616c821ed25263ad334e154077a06c4191858c6512f5ec8bff9a1d0e6b8307cb32779b2e30bf42d3d262dd041aa590211",
    false,
    1
  );

/* CREACIÓN DE LA TABLA registros */
CREATE TABLE registros(
  idusuario INTEGER,
  idcentro INTEGER,
  rol VARCHAR(250),
  clase VARCHAR(250),
  curso VARCHAR(250),
  activo BOOLEAN,
  PRIMARY KEY (idusuario, idcentro, curso, rol),
  FOREIGN KEY (idusuario) References usuarios(id) ON UPDATE CASCADE,
  FOREIGN KEY (idcentro) References centros(id) ON UPDATE CASCADE
);

/* INSERCIÓN DE DATOS EN LA TABLA registros */
INSERT INTO
  registros(
    idusuario,
    idcentro,
    rol,
    clase,
    curso,
    activo
  )
values
  (
    3,
    28062126,
    "tutor",
    "2DAM",
    "2019/2020",
    1
  ),
  (
    4,
    28062126,
    "alumno",
    "2DAM",
    "2019/2020",
    1
  ),
  (
    5,
    28062126,
    "direccion",
    "",
    "2019/2020",
    1
  );

/* CREACIÓN DE LA TABLA reportes */
CREATE TABLE reportes (
  id INTEGER AUTO_INCREMENT,
  idalumno INTEGER,
  idcentro INTEGER,
  clase VARCHAR(250),
  curso VARCHAR(250),
  titulo TEXT,
  victima VARCHAR(750),
  acosador VARCHAR(750),
  lugar VARCHAR(750),
  descripcion TEXT,
  fechaHora VARCHAR(250),
  fechaHoraCreacion VARCHAR(250),
  PRIMARY KEY (id),
  FOREIGN KEY (idalumno) References usuarios(id),
  FOREIGN KEY (idcentro) References centros(id)
);

/* INSERCIÓN DE DATOS EN LA TABLA reportes */
INSERT INTO
  `reportes` (
    `id`,
    `idalumno`,
    `idcentro`,
    `clase`,
    `curso`,
    `titulo`,
    `victima`,
    `acosador`,
    `lugar`,
    `descripcion`,
    `fechaHora`,
    `fechaHoraCreacion`
  )
VALUES
  (
    1,
    4,
    28062126,
    '2DAM',
    '2019/2020',
    'Paliza a un compañero',
    'Juanito',
    'Jhony y Bryan',
    'Patio',
    'Jhony y Bryan le han dado una paliza a Juanito, desconozco el motivo.',
    'En el recreo de hoy',
    '22-05-2020 18:59:26'
  );

/* CREACIÓN DE LA TABLA comentarios */
CREATE TABLE comentarios (
  id INTEGER,
  idusuario INTEGER,
  idreporte INTEGER,
  comentario TEXT,
  fechaHora VARCHAR(250),
  PRIMARY KEY (id, idreporte),
  FOREIGN KEY (idusuario) References usuarios(id),
  FOREIGN KEY (idreporte) References reportes(id)
);

/* INSERCIÓN DE DATOS EN LA TABLA comentarios */
INSERT INTO
  `comentarios` (
    `id`,
    `idusuario`,
    `idreporte`,
    `comentario`,
    `fechaHora`
  )
VALUES
  (
    1,
    3,
    1,
    'Hola Samuel, muchas gracias por notificar el caso\r\n',
    '22-05-2020 19:16:12'
  ),
  (
    2,
    4,
    1,
    'No hay de qué, espero que se solucione los antes posible.\r\nUn saludo.',
    '22-05-2020 19:17:01'
  );

/* CREACIÓN DE LA TABLA pwdreset */
CREATE TABLE pwdreset(
  id INTEGER AUTO_INCREMENT,
  email VARCHAR(250),
  selector VARCHAR(250),
  token LONGTEXT,
  expira VARCHAR(250),
  PRIMARY KEY (id)
);