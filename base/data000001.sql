/********************************************I-DAT-JUAN-SSIG-0-20/04/2017********************************************/

/* Data for the 'segu.tsubsistema' table  (Records 1 - 1) */

INSERT INTO segu.tsubsistema ("codigo", "nombre", "fecha_reg", "prefijo", "estado_reg", "nombre_carpeta")
VALUES 
  (E'SSIG', E'Seguimiento integral de gestión', E'2016-11-21', E'SSIG', E'activo', E'segintegralgestion');
  
----------------------------------
--COPY LINES TO data.sql FILE  
---------------------------------


select pxp.f_insert_tgui ('SEGUIMIENTO INTEGRAL DE GESTIÓN', '', 'SSIG', 'si', 1, '', 1, '', '', 'SSIG');
select pxp.f_insert_tgui ('Indicador', 'Registro principal de indicadores', 'IN', 'si', 1, 'sis_segintegralgestion/vista/indicador/Indicador.php', 2, '', 'Indicador', 'SSIG');


/********************************************F-DAT-JUAN-SSIG-0-20/04/2017********************************************/

/********************************************I-DAT-JUAN-SSIG-0-9/05/2017********************************************/
select pxp.f_insert_tgui ('Seguimiento indicador', 'Interfaz con herencia de indicates para la insercion de valores y justificacion ', 'SEIN', 'si', 2, 'sis_segintegralgestion/vista/indicador/FormSeguimientoIndicador.php', 3, '', 'FormSeguimientoIndicador', 'SSIG');
/********************************************F-DAT-JUAN-SSIG-0-9/05/2017********************************************/

/********************************************I-DAT-YAC-SSIG-0-9/05/2017********************************************/
select pxp.f_insert_tgui ('Planificación', 'Seguimiento a la Planificación', 'SSIGPLAN', 'si', 100, '', 2, '', '', 'SSIG');
select pxp.f_insert_tgui ('Planificación', 'Definición de la planificación', 'SSIGPLANDEF', 'si', 1, 'sis_segintegralgestion/vista/plan/Plan.php', 3, '', 'Plan', 'SSIG');

/********************************************F-DAT-YAC-SSIG-0-9/05/2017********************************************/


/********************************************I-DAT-JUAN-SSIG-0-10/05/2017********************************************/
INSERT INTO ssig.tindicador_frecuencia ("id_usuario_reg", "id_usuario_mod", "fecha_reg", "fecha_mod", "estado_reg", "id_usuario_ai", "usuario_ai", "id_indicador_frecuencia", "frecuencia", "valor", "hito")
VALUES 
  (1, NULL, E'2016-11-21 11:37:44.648', E'2016-11-21 11:37:44.648', E'activo', NULL, NULL, 1, E'Diario', 1, False),
  (1, NULL, E'2016-11-21 11:37:49.044', E'2016-11-21 11:37:49.044', E'activo', NULL, NULL, 2, E'Semanal', 7, False),
  (1, NULL, E'2016-11-21 11:37:52.797', E'2016-11-21 11:37:52.797', E'activo', NULL, NULL, 3, E'Mensual', 30, False),
  (1, NULL, E'2016-11-21 11:37:56.976', E'2016-11-21 11:37:56.976', E'activo', NULL, NULL, 4, E'Trimestral', 90, False),
  (1, NULL, E'2016-11-21 11:38:00.540', E'2016-11-21 11:38:00.540', E'activo', NULL, NULL, 5, E'Semestral', 180, False),
  (1, NULL, E'2016-11-21 11:38:05.076', E'2016-11-21 11:38:05.076', E'activo', NULL, NULL, 6, E'Anual', 365, False),
  (1, NULL, E'2016-11-21 11:38:09.492', E'2016-11-21 11:38:09.492', E'activo', NULL, NULL, 7, E'Hito', 0, True);
  
INSERT INTO ssig.tindicador_unidad ("id_usuario_reg", "id_usuario_mod", "fecha_reg", "fecha_mod", "estado_reg", "id_usuario_ai", "usuario_ai", "id_indicador_unidad", "unidad", "tipo")
VALUES 
  (1, NULL, E'2016-11-21 11:24:50.878', E'2016-11-21 11:24:50.878', E'activo', NULL, NULL, 1, E'Cantidad', E'Numero'),
  (1, NULL, E'2016-11-21 11:24:55.045', E'2016-11-21 11:24:55.045', E'activo', NULL, NULL, 2, E'Porcentaje', E'Numero'),
  (1, NULL, E'2016-11-21 11:24:58.505', E'2016-11-21 11:24:58.505', E'activo', NULL, NULL, 3, E'Días', E'Numero'),
  (1, NULL, E'2016-11-21 11:25:01.833', E'2016-11-21 11:25:01.833', E'activo', NULL, NULL, 4, E'Fecha', E'Fecha'),
  (1, NULL, E'2016-11-21 11:25:05.762', E'2016-11-21 11:25:05.762', E'activo', NULL, NULL, 5, E'Moneda Nal.', E'Numero'),
  (1, NULL, E'2016-11-21 11:25:10.105', E'2016-11-21 11:25:10.105', E'activo', NULL, NULL, 6, E'USD', E'Numero'),
  (1, NULL, E'2016-11-21 11:25:16.388', E'2016-11-21 11:25:16.388', E'activo', NULL, NULL, 7, E'Indice', E'Numero'),
  (1, NULL, E'2016-11-21 11:25:20.901', E'2016-11-21 11:25:20.901', E'activo', NULL, NULL, 8, E'Miles USD', E'Numero'),
  (1, NULL, E'2016-11-21 11:25:25.690', E'2016-11-21 11:25:25.690', E'activo', NULL, NULL, 9, E'USD/Empleado', E'Numero'),
  (1, NULL, E'2016-11-21 11:25:31.459', E'2016-11-21 11:25:31.459', E'activo', NULL, NULL, 10, E'Hrs', E'Hrs'),
  (1, NULL, E'2016-11-21 11:25:36.976', E'2016-11-21 11:25:36.976', E'activo', NULL, NULL, 11, E'USD/Km', E'Numero'),
  (1, NULL, E'2016-11-21 11:25:41.126', E'2016-11-21 11:25:41.126', E'activo', NULL, NULL, 12, E'Kg/año', E'Numero'),
  (1, NULL, E'2016-11-21 11:25:45.617', E'2016-11-21 11:25:45.617', E'activo', NULL, NULL, 13, E'Miles Bs.', E'Numero');

/********************************************F-DAT-JUAN-SSIG-0-10/05/2017********************************************/
/********************************************I-DAT-YAC-SSIG-0-06/06/2017********************************************/

select pxp.f_insert_tgui ('Agrupador', 'Agrupador', 'SSIG_AG', 'si', 3, 'sis_segintegralgestion/vista/agrupador/Agrupador.php', 2, '', 'Agrupador', 'SSIG');


/********************************************F-DAT-YAC-SSIG-0-06/06/2017********************************************/


/********************************************I-DAT-JUAN-SSIG-0-06/06/2017********************************************/
select pxp.f_insert_tgui ('Indicador', 'Registro principal de indicadores', 'IN', 'si', 1, 'sis_segintegralgestion/vista/indicador/FormIndicador.php', 2, '', 'FormIndicador', 'SSIG');
/********************************************F-DAT-JUAN-SSIG-0-06/06/2017********************************************/


/********************************************I-DAT-JUAN-SSIG-0-07/06/2017********************************************/
select pxp.f_insert_tgui ('SEGUIMIENTO INTEGRAL DE GESTIÓN', '', 'SSIG', 'si', 1, '', 1, '', '', 'SSIG');
select pxp.f_insert_tgui ('Indicador', 'Registro principal de indicadores', 'IN', 'si', 0, 'sis_segintegralgestion/vista/indicador/FormIndicador.php', 2, '', 'FormIndicador', 'SSIG');
select pxp.f_insert_tgui ('Seguimiento indicador', 'Interfaz con herencia de indicates para la insercion de valores y justificacion ', 'SEIN', 'si', 0, 'sis_segintegralgestion/vista/indicador/FormSeguimientoIndicador.php', 3, '', 'FormSeguimientoIndicador', 'SSIG');
select pxp.f_insert_tgui ('Agrupador', 'Agrupador', 'SSIG_AG', 'si', 0, 'sis_segintegralgestion/vista/agrupador/Agrupador.php', 2, '', 'Agrupador', 'SSIG');
select pxp.f_insert_tgui ('Indicadores', 'Indicadores', 'INDI', 'si', 50, '', 2, '', '', 'SSIG');
/********************************************F-DAT-JUAN-SSIG-0-07/06/2017********************************************/

/********************************************I-DAT-JUAN-SSIG-0-22/06/2017********************************************/
select pxp.f_insert_tgui ('Indicador', 'Registro principal de indicadores', 'IN', 'si', 1, 'sis_segintegralgestion/vista/indicador/FormIndicador.php', 2, '', 'FormIndicador', 'SSIG');
select pxp.f_insert_tgui ('Seguimiento indicador', 'Interfaz con herencia de indicates para la insercion de valores y justificacion ', 'SEIN', 'si', 2, 'sis_segintegralgestion/vista/indicador/FormSeguimientoIndicador.php', 3, '', 'FormSeguimientoIndicador', 'SSIG');
select pxp.f_insert_tgui ('Agrupador', 'Agrupador', 'SSIG_AG', 'si', 3, 'sis_segintegralgestion/vista/agrupador/Agrupador.php', 2, '', 'Agrupador', 'SSIG');
/********************************************F-DAT-JUAN-SSIG-0-22/06/2017********************************************/ 

/********************************************I-DAT-JUAN-SSIG-0-26/10/2017********************************************/
INSERT INTO ssig.tinterpretacion_indicador ("id_usuario_reg", "fecha_reg", "estado_reg", "interpretacion", "color", "icono", "porcentaje", "id_gestion","posicion")
VALUES 
  (1, (SELECT fecha_reg from param.tgestion limit 1), E'activo', 'Cumplimiento', '#3399CC', '../../../sis_segintegralgestion/vista/ImagenesInterpretacionIndicador/Cumplimiento.png', 100, (SELECT id_gestion from param.tgestion limit 1),1),
  (1, (SELECT fecha_reg from param.tgestion limit 1), E'activo', 'Exito', '#66CC99', '../../../sis_segintegralgestion/vista/ImagenesInterpretacionIndicador/Exito.png', 85, (SELECT id_gestion from param.tgestion limit 1),2),
  (1, (SELECT fecha_reg from param.tgestion limit 1), E'activo', 'Riesgo', '#F0D58C', '../../../sis_segintegralgestion/vista/ImagenesInterpretacionIndicador/Riesgo.png', 50, (SELECT id_gestion from param.tgestion limit 1),3),
  (1, (SELECT fecha_reg from param.tgestion limit 1), E'activo', 'Fracaso', '#FA8072', '../../../sis_segintegralgestion/vista/ImagenesInterpretacionIndicador/Fracaso.png', 0, (SELECT id_gestion from param.tgestion limit 1),4);
/********************************************F-DAT-JUAN-SSIG-0-26/10/2017********************************************/ 
/********************************************I-DAT-MMV-SSIG-2-30/04/2020********************************************/
select pxp.f_insert_tgui ('Parametros', 'Parametros', 'SIGPAR', 'si', 3, '', 2, '', '', 'SSIG');
select pxp.f_insert_testructura_gui ('SIGPAR', 'SSIG');
select pxp.f_insert_tgui ('Encuestas', 'Encuestas', 'ENS', 'si', 2, 'sis_segintegralgestion\vista\encuesta\Encuesta.php', 3, '', 'Encuesta', 'SSIG');
select pxp.f_insert_testructura_gui ('ENS', 'SIGPAR');
select pxp.f_insert_tgui ('Cuestionario', 'Cuestionario', 'SIGCUE', 'si', 3, 'sis_segintegralgestion\vista\cuestionario\Cuestionario.php', 3, '', 'Cuestionario', 'SSIG');
select pxp.f_insert_testructura_gui ('SIGCUE', 'SIGPAR');
select pxp.f_insert_tgui ('Evaluacion', 'Evaluacion', 'EVARES', 'si', 3, '', 2, '', '', 'SSIG');
select pxp.f_insert_testructura_gui ('EVARES', 'SSIG');
select pxp.f_insert_tgui ('Evaluacion Funcionario', 'Evaluacion Funcionario', 'EVAFUN', 'si', 1, 'sis_segintegralgestion\vista\pregunta\Respuesta.php', 3, '', 'Respuesta', 'SSIG');
select pxp.f_insert_testructura_gui ('EVAFUN', 'EVARES');
/********************************************F-DAT-MMV-SSIG-2-30/04/2020********************************************/

