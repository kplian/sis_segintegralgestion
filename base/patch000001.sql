
/***********************************I-SCP-JUAN-SSIG-0-20/04/2017****************************************/
--------------- SQL ---------------

CREATE TABLE ssig.tindicador_unidad (
  id_indicador_unidad SERIAL NOT NULL,
  unidad VARCHAR(100),
  tipo VARCHAR(100),
  PRIMARY KEY(id_indicador_unidad)
) INHERITS (pxp.tbase)

WITH (oids = false);


--------------- SQL ---------------

CREATE TABLE ssig.tindicador_frecuencia (
  id_indicador_frecuencia SERIAL NOT NULL,
  frecuencia VARCHAR(50),
  valor INTEGER,
  hito BOOLEAN,
  PRIMARY KEY(id_indicador_frecuencia)
) INHERITS (pxp.tbase)

WITH (oids = false);

--------------- SQL ---------------

CREATE TABLE ssig.tindicador (
  id_indicador SERIAL NOT NULL,
  id_gestion INTEGER,
  sigla VARCHAR(50),
  indicador VARCHAR(100),
  descipcion VARCHAR(1000),
  id_indicador_unidad INTEGER,
  num_decimal INTEGER,
  id_indicador_frecuencia INTEGER,
  semaforo VARCHAR(50),
  comparacion VARCHAR(50),
  aprobado BOOLEAN,
  PRIMARY KEY(id_indicador)
) INHERITS (pxp.tbase)

WITH (oids = false);

--------------- SQL ---------------

CREATE TABLE ssig.tindicador_valor (
  id_indicador_valor SERIAL NOT NULL,
  id_indicador INTEGER,
  hito VARCHAR(100),
  fecha DATE,
  semaforo1 VARCHAR(50),
  semaforo2 VARCHAR(50),
  semaforo3 VARCHAR(50),
  semaforo4 VARCHAR(50),
  semaforo5 VARCHAR(50),
  valor VARCHAR(200),
  justificacion VARCHAR(1000),
  no_reporta BOOLEAN,
  PRIMARY KEY(id_indicador_valor)
) INHERITS (pxp.tbase)

WITH (oids = false);

/***********************************F-SCP-JUAN-SSIG-0-20/04/2017****************************************/


/***********************************I-SCP-CAP-SSIG-0-24/04/2017****************************************/
CREATE TABLE ssig.tplan (
  id_plan SERIAL NOT NULL,
  id_plan_padre INTEGER,
  id_gestion INTEGER,
  nombre_plan VARCHAR(150),
  peso INTEGER,
  aprobado BIT(1),
  nivel INTEGER,
  PRIMARY KEY(id_plan)
) INHERITS (pxp.tbase)

WITH (oids = false);


CREATE TABLE ssig.tlinea (
  id_linea SERIAL NOT NULL,
  id_linea_padre INTEGER,
  id_plan INTEGER,
  nombre_linea VARCHAR(150),
  peso INTEGER,
  nivel INTEGER,
  PRIMARY KEY(id_linea)
) INHERITS (pxp.tbase)

WITH (oids = false);
/***********************************F-SCP-CAP-SSIG-0-24/04/2017****************************************/
/***********************************I-SCP-YAC-SSIG-0-29/05/2017****************************************/

CREATE TABLE ssig.tplan_funcionario (
  id_plan INTEGER,
  id_funcionario INTEGER
) INHERITS (pxp.tbase)

WITH (oids = false);


CREATE TABLE ssig.tlinea_funcionario (
  id_linea INTEGER,
  id_funcionario INTEGER
) INHERITS (pxp.tbase)

WITH (oids = false);
/***********************************F-SCP-YAC-SSIG-0-29/05/2017****************************************/
/***********************************I-SCP-MANU-SSIG-0-05/06/2017****************************************/

CREATE TABLE ssig.tagrupador (
  id_agrupador SERIAL NOT NULL,
  id_agrupador_padre INTEGER,
  nombre VARCHAR(255),
  nivel INTEGER,
  descripcion VARCHAR,
  peso INTEGER,
  id_funcionario INTEGER,
  id_gestion INTEGER,
  PRIMARY KEY(id_agrupador)
) INHERITS (pxp.tbase)

WITH (oids = false);

/***********************************F-SCP-MANU-SSIG-0-05/06/2017****************************************/

/***********************************I-SCP-MANU-SSIG-0-13/06/2017****************************************/
CREATE TABLE ssig.tagrupador_indicador (
  id_agrupador_indicador SERIAL NOT NULL,
  id_agrupador INTEGER,
  id_indicador INTEGER,
  peso INTEGER,
  id_funcionario_ingreso INTEGER,
  id_funcionario_evaluacion INTEGER,
  PRIMARY KEY(id_agrupador_indicador)
) INHERITS (pxp.tbase)

WITH (oids = false);

/***********************************F-SCP-MANU-SSIG-0-13/06/2017****************************************/

/***********************************I-SCP-MANU-SSIG-0-14/06/2017****************************************/

ALTER TABLE ssig.tagrupador
  ADD COLUMN peso_acumulado INTEGER;

ALTER TABLE ssig.tagrupador
  ADD COLUMN aprobado BIT(1);  
  
/***********************************F-SCP-MANU-SSIG-0-14/06/2017****************************************/

/***********************************I-SCP-JUAN-SSIG-0-14/06/2017****************************************/
CREATE TABLE ssig.tlinea_avance (
  id_linea_avance SERIAL NOT NULL,
  id_linea INTEGER,
  mes VARCHAR(50),
  avance_previsto NUMERIC(10,2),
  avance_real NUMERIC(10,10),
  comentario VARCHAR(500),
  aprobado_real BOOLEAN,
  PRIMARY KEY(id_linea_avance)
) INHERITS (pxp.tbase)

WITH (oids = false);
/***********************************F-SCP-JUAN-SSIG-0-14/06/2017****************************************/

/***********************************I-SCP-JUAN-SSIG-0-30/08/2017****************************************/
ALTER TABLE ssig.tindicador_valor
  ALTER COLUMN no_reporta TYPE VARCHAR(50);
  
ALTER TABLE ssig.tlinea_avance
  ADD COLUMN dato VARCHAR(3000);
/***********************************F-SCP-JUAN-SSIG-0-30/08/2017****************************************/

/***********************************I-SCP-JUAN-SSIG-0-25/10/2017****************************************/

CREATE TABLE ssig.tinterpretacion_indicador (
  id_interpretacion_indicador SERIAL NOT NULL,
  interpretacion VARCHAR(50),
  color VARCHAR(100),
  icono VARCHAR(2000),
  porcentaje INTEGER,
  id_gestion INTEGER,
  PRIMARY KEY(id_interpretacion_indicador)
) INHERITS (pxp.tbase)

WITH (oids = false);
/***********************************F-SCP-JUAN-SSIG-0-25/10/2017****************************************/

/***********************************I-SCP-JUAN-SSIG-0-26/10/2017****************************************/
ALTER TABLE ssig.tagrupador
  ADD COLUMN resultado NUMERIC(10,2);
  
ALTER TABLE ssig.tagrupador
  ADD COLUMN id_interpretacion_indicador INTEGER;
  

ALTER TABLE ssig.tagrupador_indicador
  ADD COLUMN id_interpretacion_indicador INTEGER;

ALTER TABLE ssig.tagrupador_indicador
  ADD COLUMN resultado NUMERIC(10,2);

ALTER TABLE ssig.tinterpretacion_indicador
  ADD COLUMN posicion INTEGER;
/***********************************F-SCP-JUAN-SSIG-0-26/10/2017****************************************/

/***********************************I-SCP-JUAN-SSIG-0-14/12/2017****************************************/

ALTER TABLE ssig.tagrupador_indicador
  ADD COLUMN semaforo1 VARCHAR(100);

--------------- SQL ---------------

ALTER TABLE ssig.tagrupador_indicador
  ADD COLUMN semaforo2 VARCHAR(100);
--------------- SQL ---------------

ALTER TABLE ssig.tagrupador_indicador
  ADD COLUMN semaforo3 VARCHAR(100);
--------------- SQL ---------------

ALTER TABLE ssig.tagrupador_indicador
  ADD COLUMN semaforo4 VARCHAR(100);
--------------- SQL ---------------

ALTER TABLE ssig.tagrupador_indicador
  ADD COLUMN semaforo5 VARCHAR(100);
--------------- SQL ---------------

ALTER TABLE ssig.tagrupador_indicador
  ADD COLUMN valor_real VARCHAR(100);
  
--------------- SQL ---------------

ALTER TABLE ssig.tagrupador_indicador
  ADD COLUMN semaforo VARCHAR(100);
--------------- SQL ---------------

ALTER TABLE ssig.tagrupador_indicador
  ADD COLUMN comparacion VARCHAR(100);
  
--------------- SQL ---------------

ALTER TABLE ssig.tagrupador_indicador
  ADD COLUMN ruta_icono VARCHAR(3000);
--------------- SQL ---------------

ALTER TABLE ssig.tagrupador_indicador
  ADD COLUMN justificacion VARCHAR(1000);
  
/***********************************F-SCP-JUAN-SSIG-0-14/12/2017****************************************/

/***********************************I-SCP-JUAN-SSIG-0-02/08/2018****************************************/

ALTER TABLE ssig.tlinea
  ADD COLUMN orden_logico INTEGER;
/***********************************F-SCP-JUAN-SSIG-0-02/08/2018****************************************/

/***********************************I-SCP-JUAN-SSIG-0-17/08/2018****************************************/

ALTER TABLE ssig.tagrupador_resultado
  ADD COLUMN ruta_icono VARCHAR(3000);
/***********************************F-SCP-JUAN-SSIG-0-17/08/2018****************************************/