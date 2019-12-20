
/***********************************I-DEP-JUAN-SSIG-0-20/04/2017*****************************************/
----------------------------------
--COPY LINES TO dependencies.sql FILE
---------------------------------

select pxp.f_insert_testructura_gui ('SSIG', 'SISTEMA');
select pxp.f_insert_testructura_gui ('IN', 'SSIG');
/***********************************F-DEP-JUAN-SSIG-0-20/04/2017*****************************************/




/***********************************I-DEP-JUAN-SSIG-0-20/04/2017*****************************************/
--------------- SQL ---------------

ALTER TABLE ssig.tindicador
  ADD CONSTRAINT tindicador_fk FOREIGN KEY (id_indicador_unidad)
    REFERENCES ssig.tindicador_unidad(id_indicador_unidad)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION
    NOT DEFERRABLE;

--------------- SQL ---------------

ALTER TABLE ssig.tindicador
  ADD CONSTRAINT tindicador_fk1 FOREIGN KEY (id_indicador_frecuencia)
    REFERENCES ssig.tindicador_frecuencia(id_indicador_frecuencia)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION
    NOT DEFERRABLE;

--------------- SQL ---------------

ALTER TABLE ssig.tindicador_valor
  ADD CONSTRAINT tindicador_valor_fk FOREIGN KEY (id_indicador)
    REFERENCES ssig.tindicador(id_indicador)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION
    NOT DEFERRABLE;

--------------- SQL ---------------

ALTER TABLE ssig.tindicador
  ADD CONSTRAINT tindicador_fk2 FOREIGN KEY (id_gestion)
    REFERENCES param.tgestion(id_gestion)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION
    NOT DEFERRABLE;
/***********************************F-DEP-JUAN-SSIG-0-20/04/2017*****************************************/


/***********************************I-DEP-CAP-SSIG-0-24/04/2017*****************************************/
ALTER TABLE ssig.tplan
  ADD CONSTRAINT tplan_fk FOREIGN KEY (id_plan_padre)
    REFERENCES ssig.tplan(id_plan)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION
    NOT DEFERRABLE;

ALTER TABLE ssig.tplan
  ADD CONSTRAINT tplan_fk1 FOREIGN KEY (id_gestion)
    REFERENCES param.tgestion(id_gestion)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION
    NOT DEFERRABLE;

ALTER TABLE ssig.tlinea
  ADD CONSTRAINT tlinea_fk FOREIGN KEY (id_linea_padre)
    REFERENCES ssig.tlinea(id_linea)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION
    NOT DEFERRABLE;
    
ALTER TABLE ssig.tlinea
  ADD CONSTRAINT tlinea_fk1 FOREIGN KEY (id_plan)
    REFERENCES ssig.tplan(id_plan)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION
    NOT DEFERRABLE;
/***********************************F-DEP-CAP-SSIG-0-24/04/2017*****************************************/


/**********************************I-DEP-JUAN-SSIG-0-9/05/2017********************************************/
select pxp.f_delete_testructura_gui ('SEIN', 'IN');
select pxp.f_insert_testructura_gui ('SEIN', 'SSIG');
/**********************************F-DEP-JUAN-SSIG-0-9/05/2017********************************************/

/**********************************I-DEP-YAC-SSIG-0-9/05/2017********************************************/
select pxp.f_insert_testructura_gui ('SSIGPLAN', 'SSIG');
select pxp.f_insert_testructura_gui ('SSIGPLANDEF', 'SSIGPLAN');
/**********************************F-DEP-YAC-SSIG-0-9/05/2017********************************************/
/**********************************I-DEP-YAC-SSIG-0-29/05/2017********************************************/

ALTER TABLE ssig.tplan_funcionario
  ADD CONSTRAINT tplan_funcionario_fk FOREIGN KEY (id_plan)
REFERENCES ssig.tplan(id_plan)
ON DELETE CASCADE
ON UPDATE NO ACTION
NOT DEFERRABLE;


ALTER TABLE ssig.tplan_funcionario
  ADD CONSTRAINT tplan_funcionario_fk1 FOREIGN KEY (id_funcionario)
REFERENCES orga.tfuncionario(id_funcionario)
ON DELETE CASCADE
ON UPDATE NO ACTION
NOT DEFERRABLE;


ALTER TABLE ssig.tlinea_funcionario
  ADD CONSTRAINT tlinea_funcionario_fk FOREIGN KEY (id_linea)
REFERENCES ssig.tlinea(id_linea)
ON DELETE CASCADE
ON UPDATE NO ACTION
NOT DEFERRABLE;


ALTER TABLE ssig.tlinea_funcionario
  ADD CONSTRAINT tlinea_funcionario_fk1 FOREIGN KEY (id_funcionario)
REFERENCES orga.tfuncionario(id_funcionario)
ON DELETE CASCADE
ON UPDATE NO ACTION
NOT DEFERRABLE;

/**********************************F-DEP-YAC-SSIG-0-29/05/2017********************************************/
/**********************************I-DEP-YAC-SSIG-0-31/05/2017********************************************/

ALTER TABLE ssig.tplan
  ADD COLUMN peso_acumulado INTEGER;

create  trigger trig_actualiza_peso_acumulado_plan_del AFTER delete on ssig.tplan for each row execute procedure ssig.f_trig_actualiza_peso_acumulado_plan_del();
create  trigger trig_actualiza_peso_acumulado_plan_upd AFTER UPDATE on ssig.tplan for each row execute procedure ssig.f_trig_actualiza_peso_acumulado_plan_upd();
create  trigger trig_actualiza_peso_acumulado_plan_ins AFTER insert on ssig.tplan for each row execute procedure ssig.f_trig_actualiza_peso_acumulado_plan_ins();

ALTER TABLE ssig.tlinea
  ADD COLUMN peso_acumulado INTEGER;

create  trigger trig_actualiza_peso_acumulado_linea_del AFTER delete on ssig.tlinea for each row execute procedure ssig.f_trig_actualiza_peso_acumulado_linea_del();
create  trigger trig_actualiza_peso_acumulado_linea_upd AFTER UPDATE on ssig.tlinea for each row execute procedure ssig.f_trig_actualiza_peso_acumulado_linea_upd();
create  trigger trig_actualiza_peso_acumulado_linea_ins AFTER insert on ssig.tlinea for each row execute procedure ssig.f_trig_actualiza_peso_acumulado_linea_ins();

/**********************************F-DEP-YAC-SSIG-0-31/05/2017********************************************/
/**********************************I-DEP-MANU-SSIG-0-05/06/2017********************************************/


ALTER TABLE ssig.tagrupador
  ADD CONSTRAINT tagrupador_fk FOREIGN KEY (id_agrupador_padre)
REFERENCES ssig.tagrupador(id_agrupador)
ON DELETE NO ACTION
ON UPDATE NO ACTION
NOT DEFERRABLE;
/**********************************F-DEP-MANU-SSIG-0-05/06/2017********************************************/
/**********************************I-DEP-YAC-SSIG-0-06/06/2017********************************************/

select pxp.f_insert_testructura_gui ('SSIG_AG', 'SSIG');

/**********************************F-DEP-YAC-SSIG-0-06/06/2017********************************************/

/**********************************I-DEP-JUAN-SSIG-0-07/06/2017********************************************/
select pxp.f_insert_testructura_gui ('INDI', 'SSIG');
select pxp.f_insert_testructura_gui ('IN', 'INDI');
select pxp.f_insert_testructura_gui ('SEIN', 'INDI');
select pxp.f_insert_testructura_gui ('SSIG_AG', 'INDI');
/**********************************F-DEP-JUAN-SSIG-0-07/06/2017********************************************/

/**********************************I-DEP-MANU-SSIG-0-13/06/2017********************************************/
ALTER TABLE ssig.tagrupador_indicador
  ADD CONSTRAINT tagrupador_indicador_fk FOREIGN KEY (id_agrupador)
    REFERENCES ssig.tagrupador(id_agrupador)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION
    NOT DEFERRABLE;

ALTER TABLE ssig.tagrupador_indicador
  ADD CONSTRAINT tagrupador_indicador_fk1 FOREIGN KEY (id_indicador)
    REFERENCES ssig.tindicador(id_indicador)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION
    NOT DEFERRABLE;

ALTER TABLE ssig.tagrupador_indicador
  ADD CONSTRAINT tagrupador_indicador_fk2 FOREIGN KEY (id_funcionario_ingreso)
    REFERENCES orga.tfuncionario(id_funcionario)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION
    NOT DEFERRABLE;

ALTER TABLE ssig.tagrupador_indicador
  ADD CONSTRAINT tagrupador_indicador_fk3 FOREIGN KEY (id_funcionario_evaluacion)
    REFERENCES orga.tfuncionario(id_funcionario)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION
    NOT DEFERRABLE;   

ALTER TABLE ssig.tagrupador
  ADD CONSTRAINT tagrupador_fk2 FOREIGN KEY (id_gestion)
    REFERENCES param.tgestion(id_gestion)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION
    NOT DEFERRABLE;  
/**********************************F-DEP-MANU-SSIG-0-13/06/2017********************************************/

/**********************************I-DEP-JUAN-SSIG-0-14/06/2017********************************************/
ALTER TABLE ssig.tlinea_avance
  ADD CONSTRAINT tlinea_avance_fk FOREIGN KEY (id_linea)
    REFERENCES ssig.tlinea(id_linea)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION
    NOT DEFERRABLE;
/**********************************F-DEP-JUAN-SSIG-0-14/06/2017********************************************/

/**********************************I-DEP-MANU-SSIG-0-19/06/2017********************************************/

CREATE TRIGGER trig_actualiza_peso_acumulado_agrupador_ins
  AFTER INSERT 
  ON ssig.tagrupador FOR EACH ROW 
  EXECUTE PROCEDURE ssig.f_trig_actualiza_peso_acumulado_agrupador_ins(); 

CREATE TRIGGER trig_actualiza_peso_acumulado_agrupador_upd
  AFTER UPDATE 
  ON ssig.tagrupador FOR EACH ROW 
  EXECUTE PROCEDURE ssig.f_trig_actualiza_peso_acumulado_agrupador_upd();
 	
CREATE TRIGGER trig_actualiza_peso_acumulado_agrupador_del
  AFTER DELETE 
  ON ssig.tagrupador FOR EACH ROW 
  EXECUTE PROCEDURE ssig.f_trig_actualiza_peso_acumulado_agrupador_del();
/**********************************F-DEP-MANU-SSIG-0-19/06/2017********************************************/

/**********************************I-DEP-MANU-SSIG-0-20/06/2017********************************************/
ALTER TABLE ssig.tagrupador_indicador
  ADD COLUMN peso_acumulado INTEGER;
  
ALTER TABLE ssig.tagrupador_indicador
  ADD COLUMN id_agrupador_indicador_padre INTEGER;  
  
ALTER TABLE ssig.tagrupador_indicador
  ADD CONSTRAINT tagrupador_indicador_fk4 FOREIGN KEY (id_agrupador_indicador_padre)
    REFERENCES ssig.tagrupador_indicador(id_agrupador_indicador)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION
    NOT DEFERRABLE;  
/**********************************F-DEP-MANU-SSIG-0-20/06/2017********************************************/ 

/**********************************I-DEP-JUAN-SSIG-0-23/06/2017********************************************/
ALTER TABLE ssig.tindicador
  ALTER COLUMN indicador TYPE VARCHAR(1000) COLLATE pg_catalog."default";

/**********************************F-DEP-JUAN-SSIG-0-23/06/2017********************************************/


/**********************************I-DEP-JUAN-SSIG-0-25/07/2017********************************************/
-- Elimina en cascada todo linea
ALTER TABLE ssig.tlinea
  DROP CONSTRAINT tlinea_fk RESTRICT;

ALTER TABLE ssig.tlinea
  ADD CONSTRAINT tlinea_fk FOREIGN KEY (id_linea_padre)
    REFERENCES ssig.tlinea(id_linea)
    ON DELETE CASCADE
    ON UPDATE NO ACTION
    NOT DEFERRABLE;
    
--------------- SQL ---------------

ALTER TABLE ssig.tlinea_avance
  DROP CONSTRAINT tlinea_avance_fk RESTRICT;

ALTER TABLE ssig.tlinea_avance
  ADD CONSTRAINT tlinea_avance_fk FOREIGN KEY (id_linea)
    REFERENCES ssig.tlinea(id_linea)
    ON DELETE CASCADE
    ON UPDATE NO ACTION
    NOT DEFERRABLE;
    
--------------- SQL ---------------

-- Elimina todo plan en cascada
ALTER TABLE ssig.tplan
  DROP CONSTRAINT tplan_fk RESTRICT;

ALTER TABLE ssig.tplan
  ADD CONSTRAINT tplan_fk FOREIGN KEY (id_plan_padre)
    REFERENCES ssig.tplan(id_plan)
    ON DELETE CASCADE
    ON UPDATE NO ACTION
    NOT DEFERRABLE;
    
--------------- SQL ---------------

-- object recreation
ALTER TABLE ssig.tlinea
  DROP CONSTRAINT tlinea_fk1 RESTRICT;

ALTER TABLE ssig.tlinea
  ADD CONSTRAINT tlinea_fk1 FOREIGN KEY (id_plan)
    REFERENCES ssig.tplan(id_plan)
    ON DELETE CASCADE
    ON UPDATE NO ACTION
    NOT DEFERRABLE;
/**********************************F-DEP-JUAN-SSIG-0-25/07/2017********************************************/

/**********************************I-DEP-JUAN-SSIG-0-25/10/2017********************************************/

ALTER TABLE ssig.tinterpretacion_indicador
  ADD CONSTRAINT tinterpretacion_indicador_fk FOREIGN KEY (id_gestion)
    REFERENCES param.tgestion(id_gestion)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION
    NOT DEFERRABLE;
/**********************************F-DEP-JUAN-SSIG-0-25/10/2017********************************************/

/**********************************I-DEP-JUAN-SSIG-0-26/10/2017********************************************/

ALTER TABLE ssig.tagrupador
  ADD CONSTRAINT tagrupador_fk1 FOREIGN KEY (id_interpretacion_indicador)
    REFERENCES ssig.tinterpretacion_indicador(id_interpretacion_indicador)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION
    NOT DEFERRABLE;

ALTER TABLE ssig.tagrupador_indicador
  ADD CONSTRAINT tagrupador_indicador_fk FOREIGN KEY (id_interpretacion_indicador)
    REFERENCES ssig.tinterpretacion_indicador(id_interpretacion_indicador)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION
    NOT DEFERRABLE;
    
/**********************************F-DEP-JUAN-SSIG-0-26/10/2017********************************************/
    
/**********************************I-DEP-JUAN-SSIG-0-09/07/2018********************************************/

ALTER TABLE ssig.tindicador
  ADD COLUMN id_funcionario_ingreso INTEGER;
  

ALTER TABLE ssig.tindicador
  ADD COLUMN id_funcionario_evaluacion INTEGER;
  
  
ALTER TABLE ssig.tagrupador_indicador
  DROP COLUMN id_funcionario_ingreso;


ALTER TABLE ssig.tagrupador_indicador
  DROP COLUMN id_funcionario_evaluacion;
/**********************************F-DEP-JUAN-SSIG-0-09/07/2018********************************************/


/**********************************I-DEP-JUAN-SSIG-0-19/09/2018********************************************/

ALTER TABLE ssig.tagrupador_indicador
  ADD COLUMN orden_logico INTEGER;
/**********************************F-DEP-JUAN-SSIG-0-19/09/2018********************************************/

/**********************************I-DEP-JUAN-SSIG-0-28/05/2019********************************************/
ALTER TABLE ssig.tlinea
  ALTER COLUMN nombre_linea TYPE VARCHAR(500) COLLATE pg_catalog."default"; --#3 endetr juan 28/05/2019 incrementar  lineas a 500 caracter
/**********************************F-DEP-JUAN-SSIG-0-28/05/2019********************************************/

/**********************************I-DEP-JUAN-SSIG-0-30/05/2019********************************************/
ALTER TABLE ssig.tlinea
  ALTER COLUMN peso TYPE NUMERIC;
/**********************************F-DEP-JUAN-SSIG-0-30/05/2019********************************************/