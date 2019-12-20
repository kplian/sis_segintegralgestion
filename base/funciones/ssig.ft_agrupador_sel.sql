CREATE OR REPLACE FUNCTION ssig.ft_agrupador_sel (
  p_administrador integer,
  p_id_usuario integer,
  p_tabla varchar,
  p_transaccion varchar
)
RETURNS varchar AS
$body$
/**************************************************************************
 SISTEMA:		Seguimiento integral de gestión
 FUNCION: 		ssig.ft_agrupador_sel
 DESCRIPCION:   Funcion que devuelve conjuntos de registros de las consultas relacionadas con la tabla 'ssig.tagrupador'
 AUTOR: 		 (admin)
 FECHA:	        05-06-2017 04:46:40
 COMENTARIOS:	
***************************************************************************
 HISTORIAL DE MODIFICACIONES:

 DESCRIPCION:	
 AUTOR:			
 FECHA:		
***************************************************************************/

DECLARE
    
    v_consulta2    		varchar;
    v_consulta1    		varchar;
	v_consulta    		varchar;
	v_parametros  		record;
	v_nombre_funcion   	text;
	v_resp				varchar;
    
    item				record;
    item1				record;
    item2				record;
    item3				record;
    
    v_sem1				varchar;
    v_sem2				varchar;
    v_sem3				varchar;
    v_sem4				varchar;
    v_sem5				varchar;
    v_filtro_periodo    varchar; 
    v_resultado         varchar;
    v_valor_real          varchar;
    v_ruta_icono          varchar;
			    
BEGIN

	v_nombre_funcion = 'ssig.ft_agrupador_sel';
    v_parametros = pxp.f_get_record(p_tabla);

	/*********************************    
 	#TRANSACCION:  'SSIG_SSIG_AG_SEL'
 	#DESCRIPCION:	Consulta de datos
 	#AUTOR:		admin	
 	#FECHA:		05-06-2017 04:46:40
	***********************************/

	if(p_transaccion='SSIG_SSIG_AG_SEL')then
     				
    	begin
    		--Sentencia de la consulta
			v_consulta:='select
						ssig_ag.id_agrupador,
						ssig_ag.id_agrupador_padre,
						ssig_ag.id_funcionario,
						ssig_ag.nombre,
						ssig_ag.descripcion,
						ssig_ag.nivel,
						ssig_ag.peso,
						ssig_ag.estado_reg,
						ssig_ag.id_usuario_ai,
						ssig_ag.id_usuario_reg,
						ssig_ag.usuario_ai,
						ssig_ag.fecha_reg,
						ssig_ag.id_usuario_mod,
						ssig_ag.fecha_mod,
						usu1.cuenta as usr_reg,
						usu2.cuenta as usr_mod	
						from ssig.tagrupador ssig_ag
						inner join segu.tusuario usu1 on usu1.id_usuario = ssig_ag.id_usuario_reg
						left join segu.tusuario usu2 on usu2.id_usuario = ssig_ag.id_usuario_mod
				        where  ';
			
			--Definicion de la respuesta
			v_consulta:=v_consulta||v_parametros.filtro;
			v_consulta:=v_consulta||' order by ' ||v_parametros.ordenacion|| ' ' || v_parametros.dir_ordenacion || ' limit ' || v_parametros.cantidad || ' offset ' || v_parametros.puntero;

			--Devuelve la respuesta
			return v_consulta;
						
		end;

	/*********************************    
 	#TRANSACCION:  'SSIG_SSIG_AG_CONT'
 	#DESCRIPCION:	Conteo de registros
 	#AUTOR:		admin	
 	#FECHA:		05-06-2017 04:46:40
	***********************************/

	elsif(p_transaccion='SSIG_SSIG_AG_CONT')then

		begin
			--Sentencia de la consulta de conteo de registros
			v_consulta:='select count(id_agrupador)
					    from ssig.tagrupador ssig_ag
					    inner join segu.tusuario usu1 on usu1.id_usuario = ssig_ag.id_usuario_reg
						left join segu.tusuario usu2 on usu2.id_usuario = ssig_ag.id_usuario_mod
                        inner join param.tgestion g on g.id_gestion=ssig_ag.id_gestion
					    where ';
			
			--Definicion de la respuesta		    
			v_consulta:=v_consulta||v_parametros.filtro;

			--Devuelve la respuesta
			return v_consulta;

		end;
	/*********************************    
 	#TRANSACCION:  'SSIG_INT_INDI_SEL'
 	#DESCRIPCION:	Consulta de parametrizacion de indicadores
 	#AUTOR:		JUAN	
 	#FECHA:		15-11-2017 04:46:40
	***********************************/

	ELSIF(p_transaccion='SSIG_INT_INDI_SEL')then
     				
    	begin
    		--Sentencia de la consulta
			v_consulta:='SELECT 
                          ii.id_interpretacion_indicador,
                          ii.id_gestion,
                          ii.interpretacion,
                          ii.porcentaje,
                          ii.icono  
                          FROM ssig.tinterpretacion_indicador ii
				          where  ';
			
			--Definicion de la respuesta
			v_consulta:=v_consulta||v_parametros.filtro;
			v_consulta:=v_consulta||' order by ' ||v_parametros.ordenacion|| ' ' || v_parametros.dir_ordenacion || ' limit ' || v_parametros.cantidad || ' offset ' || v_parametros.puntero;

			--Devuelve la respuesta
			return v_consulta;
						
		end;
	/*********************************    
 	#TRANSACCION:  'SSIG_INT_INDI_CONT'
 	#DESCRIPCION:	Conteo de registros
 	#AUTOR:		JUAN	
 	#FECHA:		15-11-2017 04:46:40
	***********************************/

	elsif(p_transaccion='SSIG_INT_INDI_CONT')then

		begin
			--Sentencia de la consulta de conteo de registros
			v_consulta:='SELECT count(ii.id_interpretacion_indicador) 
                         FROM ssig.tinterpretacion_indicador ii
					     where ';
			
			--Definicion de la respuesta		    
			v_consulta:=v_consulta||v_parametros.filtro;

			--Devuelve la respuesta
			return v_consulta;

		end;
/*
		/*********************************
     #TRANSACCION:  'SSIG_AG_SEL_ARB'
     #DESCRIPCION:	Consulta de datos de los agrupadores en estructur ade arbol
     #AUTOR:		MANU
     #FECHA:		05-06-2017 04:46:40
    ***********************************/
    
		elsif(p_transaccion='SSIG_SSIG_AG_CONT')then

		begin
			--Sentencia de la consulta de conteo de registros
			v_consulta:='select count(id_agrupador)
					    from ssig.tagrupador ssig_ag
					    inner join segu.tusuario usu1 on usu1.id_usuario = ssig_ag.id_usuario_reg
						left join segu.tusuario usu2 on usu2.id_usuario = ssig_ag.id_usuario_mod
                        inner join param.tgestion g on g.id_gestion=ssig_ag.id_gestion
					    where ';
			
			--Definicion de la respuesta		    
			v_consulta:=v_consulta||v_parametros.filtro;

			--Devuelve la respuesta
			return v_consulta;

		end;*/

	/*********************************    
 	#TRANSACCION:  'SSIG_CMANDO_SEL'
 	#DESCRIPCION:	Consulta de parametrizacion de indicadores
 	#AUTOR:		JUAN	
 	#FECHA:		15-11-2017 04:46:40
	***********************************/

	ELSIF(p_transaccion='SSIG_CMANDO_SEL')then
     				
    	begin
    		--Sentencia de la consulta
            --raise EXCEPTION 'err %',v_parametros.id_gestion||' - '||v_parametros.id_periodo;
            v_consulta2 :='';
            v_consulta2 :='select
                                padre.id_gestion,
                                padre.id_agrupador,
                                agin.id_agrupador::integer as id_agrupador_padre,
                                4::INTEGER as nivel,
                                padre.nombre::varchar,
                                ''''::VARCHAR as nivel_1,
                                ''''::VARCHAR as nivel_2,
                                ''''::VARCHAR as nivel_3,
                                ind.indicador::VARCHAR as nivel_4,
                                agin.peso::NUMERIC,
                                ar.resultado::NUMERIC,
                                (select iu.unidad from ssig.tindicador ind join ssig.tindicador_unidad iu on iu.id_indicador_unidad=ind.id_indicador_unidad  where ind.id_indicador=agin.id_indicador)::VARCHAR as unidad,
                                (select inf.frecuencia from ssig.tindicador ind join ssig.tindicador_frecuencia inf on inf.id_indicador_frecuencia=ind.id_indicador_frecuencia  where ind.id_indicador=agin.id_indicador)::VARCHAR as frecuencia,
                                (select ''falta'' from ssig.tindicador ind join ssig.tindicador_unidad iu on iu.id_indicador_unidad = ind.id_indicador_unidad  where ind.id_indicador = agin.id_indicador)::VARCHAR as tipo_semaforo,
                                (ind.semaforo||'' - ''||ind.comparacion)::VARCHAR as orden_comparacion,
                                 --agin.valor_real::VARCHAR,
                                 (select ares.valor_real from ssig.tagrupador_indicador_resultado ares where ares.id_agrupador_indicador=agin.id_agrupador_indicador and ares.id_periodo='||v_parametros.id_periodo::INTEGER||')::varchar as valor_real,                                                    
                                (select ares.semaforo1 from ssig.tagrupador_indicador_resultado ares where ares.id_agrupador_indicador=agin.id_agrupador_indicador and ares.id_periodo='||v_parametros.id_periodo::INTEGER||')::varchar as semaforo_1,
                                (select ares.semaforo2 from ssig.tagrupador_indicador_resultado ares where ares.id_agrupador_indicador=agin.id_agrupador_indicador and ares.id_periodo='||v_parametros.id_periodo::INTEGER||')::varchar as semaforo_2,
                                (select ares.semaforo3 from ssig.tagrupador_indicador_resultado ares where ares.id_agrupador_indicador=agin.id_agrupador_indicador and ares.id_periodo='||v_parametros.id_periodo::INTEGER||')::varchar as semaforo_3,
                                (select ares.semaforo4 from ssig.tagrupador_indicador_resultado ares where ares.id_agrupador_indicador=agin.id_agrupador_indicador and ares.id_periodo='||v_parametros.id_periodo::INTEGER||')::varchar as semaforo_4,
                                (select ares.semaforo5 from ssig.tagrupador_indicador_resultado ares where ares.id_agrupador_indicador=agin.id_agrupador_indicador and ares.id_periodo='||v_parametros.id_periodo::INTEGER||')::varchar as semaforo_5,
                                (select vf.desc_funcionario1 from orga.tfuncionario ff join orga.vfuncionario vf on vf.id_funcionario=ff.id_funcionario where ff.id_funcionario=ind.id_funcionario_ingreso)::varchar as funcionario_ingreso,
                                ar.ruta_icono,
                                (select vf.desc_funcionario1 from orga.tfuncionario ff join orga.vfuncionario vf on vf.id_funcionario=ff.id_funcionario where ff.id_funcionario=ind.id_funcionario_evaluacion)::varchar as funcionario_evaluacion,
                                ind.sigla::varchar,
                                ind.id_indicador,
                                agin.id_agrupador_indicador,
                                agin.orden_logico
                                from ssig.tagrupador_indicador agin
                                LEFT JOIN ssig.tagrupador padre ON padre.id_agrupador = agin.id_agrupador
                                inner join segu.tusuario usu1 on usu1.id_usuario = agin.id_usuario_reg                        
                                left join segu.tusuario usu2 on usu2.id_usuario = agin.id_usuario_mod						
                                inner join ssig.tindicador ind on ind.id_indicador = agin.id_indicador
                                
                                join  ssig.tagrupador_indicador_resultado ar on ar.id_agrupador_indicador=agin.id_agrupador_indicador
                                where padre.id_gestion='||v_parametros.id_gestion::INTEGER||' and ar.id_gestion='||v_parametros.id_gestion::INTEGER||'  and ar.id_periodo='||v_parametros.id_periodo::INTEGER||' and ';
                                
             --RAISE NOTICE 'err notice %', v_consulta2; 
             --RAISE EXCEPTION 'err %', v_consulta2;  
             
			v_consulta1:='SELECT 
                          l.id_gestion::INTEGER,
                          l.id_agrupador::INTEGER,
                          l.id_agrupador_padre::INTEGER,
                          l.nivel::INTEGER,
                          l.nombre::VARCHAR,
                          (case when l.nivel = 0 OR l.nivel IS NULL then l.nombre else '''' end)::VARCHAR as nivel_1,
                          (case when l.nivel = 1 then l.nombre else '''' end)::VARCHAR as nivel_2,
                          (case when l.nivel = 2 then l.nombre else '''' end)::VARCHAR as nivel_3,
                          (case when l.nivel = 3 then l.nombre else '''' end)::VARCHAR as nivel_4,
                          l.peso::NUMERIC,
                          ar.resultado::NUMERIC,
                          ''''::VARCHAR AS unidad,
                          ''''::VARCHAR AS frecuencia,
                          ''''::VARCHAR AS tipo_semaforo,
                          ''''::VARCHAR AS orden_comparacion,
                          ''''::VARCHAR AS valor_real,
                          ''''::VARCHAR AS semaforo_1,
                          ''''::VARCHAR AS semaforo_2,
                          ''''::VARCHAR AS semaforo_3,
                          ''''::VARCHAR AS semaforo_4,
                          ''''::VARCHAR AS semaforo_5,
                          ''''::VARCHAR AS funcionario_ingreso,
                          ar.ruta_icono::VARCHAR AS ruta_icono,
                          ''''::VARCHAR AS funcionario_evaluacion,
                          ''''::VARCHAR AS sigla
                          FROM ssig.tagrupador l
                          join  ssig.tagrupador_resultado ar on ar.id_agrupador=l.id_agrupador
			             where  ar.id_gestion = '||v_parametros.id_gestion::INTEGER||' and  l.id_gestion = '||v_parametros.id_gestion::INTEGER||' and ar.id_periodo = '||v_parametros.id_periodo::INTEGER||' and ';
            
             --RAISE NOTICE 'err notice %', v_consulta1; 
             --RAISE EXCEPTION 'err %', v_consulta1;   
                   
             v_consulta :='';
             v_consulta := 'create temp table tt_cmando_temporal(
                            id_cmando_temporal serial,
                            id_gestion integer,
                            id_agrupador integer,
                            id_agrupador_padre integer,
                            nivel integer,
                            nombre  varchar,
                            nivel_1 varchar,
                            nivel_2 varchar,
                            nivel_3 varchar,
                            nivel_4 varchar,
                            peso numeric,
                            resultado numeric,
                            
                            unidad varchar,
                            frecuencia varchar,
                            tipo_semaforo varchar,
                            orden_comparacion varchar,
                            valor_real varchar,
                            semaforo_1 varchar,
                            semaforo_2 varchar,
                            semaforo_3 varchar,
                            semaforo_4 varchar,
                            semaforo_5 varchar,
                            funcionario_ingreso varchar,
                            ruta_icono varchar,
                            funcionario_evaluacion varchar,
                            sigla varchar) on commit drop'; 
			execute(v_consulta); 
            
            FOR item IN execute  v_consulta1||' (l.id_agrupador_padre is null )   order by l.orden_logico asc '  LOOP
                  insert into tt_cmando_temporal (id_gestion,id_agrupador,id_agrupador_padre,nivel,nombre,nivel_1,nivel_2,nivel_3,nivel_4,peso,resultado,unidad,frecuencia,tipo_semaforo,orden_comparacion,valor_real,semaforo_1,semaforo_2,semaforo_3,semaforo_4,semaforo_5,funcionario_ingreso,ruta_icono) 
                  values 
                  (item.id_gestion,
                  item.id_agrupador,
                  item.id_agrupador_padre,
                  item.nivel,
                  item.nombre,
                  item.nivel_1,
                  item.nivel_2,
                  item.nivel_3,
                  item.nivel_4,
                  item.peso,
                  item.resultado,
                  item.unidad,
                  item.frecuencia,
                  item.tipo_semaforo,
                  item.orden_comparacion,
                  item.valor_real,
                  item.semaforo_1,
                  item.semaforo_2,
                  item.semaforo_3,
                  item.semaforo_4,
                  item.semaforo_5,
                  item.funcionario_ingreso,
                  item.ruta_icono);
                      FOR item1 IN execute  v_consulta1||' l.id_agrupador_padre = '||item.id_agrupador||' order by l.orden_logico asc ' LOOP
                           --raise notice 'norice eeee  %',v_consulta1;
                           -- raise exception 'eerr %',v_consulta1||' l.id_agrupador_padre = '||item.id_agrupador||' order by l.orden_logico asc ';
                            insert into tt_cmando_temporal (id_gestion,id_agrupador,id_agrupador_padre,nivel,nombre,nivel_1,nivel_2,nivel_3,nivel_4,peso,resultado,unidad,frecuencia,tipo_semaforo,orden_comparacion,valor_real,semaforo_1,semaforo_2,semaforo_3,semaforo_4,semaforo_5,funcionario_ingreso,ruta_icono) 
                            values 
                            (item1.id_gestion,
                            item1.id_agrupador,
                            item1.id_agrupador_padre,
                            item1.nivel,
                            item1.nombre,
                            item1.nivel_1,
                            item1.nivel_2,
                            item1.nivel_3,
                            item1.nivel_4,
                            item1.peso,
                            item1.resultado,
                            item1.unidad,
                            item1.frecuencia,
                            item1.tipo_semaforo,
                            item1.orden_comparacion,
                            item1.valor_real,
                            item1.semaforo_1,
                            item1.semaforo_2,
                            item1.semaforo_3,
                            item1.semaforo_4,
                            item1.semaforo_5,
                            item1.funcionario_ingreso,
                            item1.ruta_icono);
                            FOR item2 IN execute  v_consulta1||' l.id_agrupador_padre = '||item1.id_agrupador||' order by l.orden_logico asc ' LOOP
                                  insert into tt_cmando_temporal (id_gestion,id_agrupador,id_agrupador_padre,nivel,nombre,nivel_1,nivel_2,nivel_3,nivel_4,peso,resultado,unidad,frecuencia,tipo_semaforo,orden_comparacion,valor_real,semaforo_1,semaforo_2,semaforo_3,semaforo_4,semaforo_5,funcionario_ingreso,ruta_icono) 
                                  values 
                                  (item2.id_gestion,
                                  item2.id_agrupador,
                                  item2.id_agrupador_padre,
                                  item2.nivel,
                                  item2.nombre,
                                  item2.nivel_1,
                                  item2.nivel_2,
                                  item2.nivel_3,
                                  item2.nivel_4,
                                  item2.peso,
                                  item2.resultado,
                                  item2.unidad,
                                  item2.frecuencia,
                                  item2.tipo_semaforo,
                                  item2.orden_comparacion,
                                  item2.valor_real,
                                  item2.semaforo_1,
                                  item2.semaforo_2,
                                  item2.semaforo_3,
                                  item2.semaforo_4,
                                  item2.semaforo_5,
                                  item2.funcionario_ingreso,
                                  item2.ruta_icono);
                                    FOR item3 IN execute  v_consulta2||' padre.id_agrupador = '||item2.id_agrupador||' ORDER BY agin.orden_logico, (ssig.f_ordenar_sigla(ind.sigla))::TEXT  ASC ' LOOP
                                    
                                          --raise notice 'notice %',item3.funcionario_ingreso;
                                          --raise EXCEPTION 'error %',item3.funcionario_ingreso;
                                          IF(item3.semaforo_1 is NULL or item3.semaforo_1 ='')then
                                              v_filtro_periodo :=(SELECT  extract(MONTH from p.fecha_fin)   FROM param.tperiodo p where p.id_periodo::INTEGER = v_parametros.id_periodo::INTEGER)::VARCHAR;
                                              IF EXISTS (SELECT iv.semaforo1 from ssig.tindicador_valor iv where iv.id_indicador=item3.id_indicador  and extract(MONTH from iv.fecha) <= v_filtro_periodo::INTEGER order by iv.fecha desc  limit 1)then
                                                  v_sem1 :=(SELECT iv.semaforo1 from ssig.tindicador_valor iv where iv.id_indicador=item3.id_indicador  and extract(MONTH from iv.fecha) <= v_filtro_periodo::INTEGER order by iv.fecha desc  limit 1)::VARCHAR;
                                                  v_sem2 :=(SELECT iv.semaforo2 from ssig.tindicador_valor iv where iv.id_indicador=item3.id_indicador  and extract(MONTH from iv.fecha) <= v_filtro_periodo::INTEGER order by iv.fecha desc  limit 1)::VARCHAR;
                                                  v_sem3 :=(SELECT iv.semaforo3 from ssig.tindicador_valor iv where iv.id_indicador=item3.id_indicador  and extract(MONTH from iv.fecha) <= v_filtro_periodo::INTEGER order by iv.fecha desc  limit 1)::VARCHAR;
                                                  v_sem4 :=(SELECT iv.semaforo4 from ssig.tindicador_valor iv where iv.id_indicador=item3.id_indicador  and extract(MONTH from iv.fecha) <= v_filtro_periodo::INTEGER order by iv.fecha desc  limit 1)::VARCHAR;
                                                  v_sem5 :=(SELECT iv.semaforo5 from ssig.tindicador_valor iv where iv.id_indicador=item3.id_indicador  and extract(MONTH from iv.fecha) <= v_filtro_periodo::INTEGER order by iv.fecha desc  limit 1)::VARCHAR;
                                                 /* v_valor_real :=(SELECT iv.valor from ssig.tindicador_valor iv where iv.id_indicador=item3.id_indicador  and extract(MONTH from iv.fecha) <= v_filtro_periodo::INTEGER order by iv.fecha desc  limit 1)::VARCHAR;
                                                  v_ruta_icono := (select ai.ruta_icono 
                                                  from ssig.tagrupador_indicador_resultado ai 
                                                  join ssig.tagrupador_indicador agi on agi.id_agrupador_indicador=ai.id_agrupador_indicador
                                                  join ssig.tindicador ind on ind.id_indicador=agi.id_indicador
                                                  join ssig.tindicador_valor iv on  iv.id_indicador=ind.id_indicador
                                                  where ai.id_agrupador_indicador=item3.id_agrupador_indicador 
                                                  and extract(MONTH from iv.fecha) <= v_filtro_periodo::INTEGER order by iv.fecha desc limit 1)::VARCHAR;
                                                  v_resultado :=(select ai.ruta_icono 
                                                  from ssig.tagrupador_indicador_resultado ai 
                                                  join ssig.tagrupador_indicador agi on agi.id_agrupador_indicador=ai.id_agrupador_indicador
                                                  join ssig.tindicador ind on ind.id_indicador=agi.id_indicador
                                                  join ssig.tindicador_valor iv on  iv.id_indicador=ind.id_indicador
                                                  where ai.id_agrupador_indicador=item3.id_agrupador_indicador 
                                                  and extract(MONTH from iv.fecha) <= v_filtro_periodo::INTEGER order by iv.fecha desc limit 1)::VARCHAR;*/
                                              else
                                                  v_sem1 :=(SELECT iv.semaforo1 from ssig.tindicador_valor iv where iv.id_indicador=item3.id_indicador  and extract(MONTH from iv.fecha) >= v_filtro_periodo::INTEGER order by iv.fecha desc  limit 1)::VARCHAR;
                                                  v_sem2 :=(SELECT iv.semaforo2 from ssig.tindicador_valor iv where iv.id_indicador=item3.id_indicador  and extract(MONTH from iv.fecha) >= v_filtro_periodo::INTEGER order by iv.fecha desc  limit 1)::VARCHAR;
                                                  v_sem3 :=(SELECT iv.semaforo3 from ssig.tindicador_valor iv where iv.id_indicador=item3.id_indicador  and extract(MONTH from iv.fecha) >= v_filtro_periodo::INTEGER order by iv.fecha desc  limit 1)::VARCHAR;
                                                  v_sem4 :=(SELECT iv.semaforo4 from ssig.tindicador_valor iv where iv.id_indicador=item3.id_indicador  and extract(MONTH from iv.fecha) >= v_filtro_periodo::INTEGER order by iv.fecha desc  limit 1)::VARCHAR;
                                                  v_sem5 :=(SELECT iv.semaforo5 from ssig.tindicador_valor iv where iv.id_indicador=item3.id_indicador  and extract(MONTH from iv.fecha) >= v_filtro_periodo::INTEGER order by iv.fecha desc  limit 1)::VARCHAR;
                                                  v_valor_real :='';
                                                  v_ruta_icono := '';
                                                  v_resultado := '';
                                              end if;
                                          else
                                              v_sem1 :=item3.semaforo_1;
                                              v_sem2 :=item3.semaforo_2;
                                              v_sem3 :=item3.semaforo_3;
                                              v_sem4 :=item3.semaforo_4;
                                              v_sem5 :=item3.semaforo_5;
                                              /*v_valor_real := item3.valor_real;
                                              v_ruta_icono := item3.ruta_icono;
                                              v_resultado := item3.resultado;*/
                                          end if;

                                          
                                          insert into tt_cmando_temporal (id_gestion,id_agrupador,id_agrupador_padre,nivel,nombre,nivel_1,nivel_2,nivel_3,nivel_4,peso,resultado,unidad,frecuencia,tipo_semaforo,orden_comparacion,valor_real,semaforo_1,semaforo_2,semaforo_3,semaforo_4,semaforo_5,funcionario_ingreso,ruta_icono,funcionario_evaluacion,sigla) 
                                          values 
                                          (item3.id_gestion,
                                          item3.id_agrupador,
                                          item3.id_agrupador_padre,
                                          item3.nivel,
                                          item3.nombre,
                                          item3.nivel_1,
                                          item3.nivel_2,
                                          item3.nivel_3,
                                          item3.nivel_4,
                                          item3.peso,
                                          item3.resultado,
                                          item3.unidad,
                                          item3.frecuencia,
                                          item3.tipo_semaforo,
                                          item3.orden_comparacion,
                                          item3.valor_real,
                                          v_sem1::VARCHAR,
                                          v_sem2::VARCHAR,
                                          v_sem3::VARCHAR,
                                          v_sem4::VARCHAR,
                                          v_sem5::VARCHAR,
                                          item3.funcionario_ingreso,
                                          item3.ruta_icono,
                                          item3.funcionario_evaluacion,
                                          item3.sigla::VARCHAR||' ' 
                                          );
                                          

                                    end loop;

                            end loop;
                      end loop;
            end loop;
            
            v_consulta :='select * from tt_cmando_temporal where ';
            
			--Definicion de la respuesta
			v_consulta:=v_consulta||v_parametros.filtro;
			--v_consulta:=v_consulta||' order by ' ||v_parametros.ordenacion|| ' ' || v_parametros.dir_ordenacion || ' limit ' || v_parametros.cantidad || ' offset ' || v_parametros.puntero;

			--Devuelve la respuesta
            raise notice 'notice %',v_consulta;
            --raise EXCEPTION 'error %',v_consulta;
			return v_consulta||' order by id_cmando_temporal asc';
						
		end;
        
		/*********************************
     #TRANSACCION:  'SSIG_AG_SEL_ARB'
     #DESCRIPCION:	Consulta de datos de los agrupadores en estructur ade arbol
     #AUTOR:		MANU
     #FECHA:		05-06-2017 04:46:40
    ***********************************/

		elseif(p_transaccion='SSIG_AG_SEL_ARB')then
			begin
                --raise EXCEPTION 'llega';
				--Sentencia de la consulta
				v_consulta:='
                		SELECT
                        DISTINCT
                        ssig_ag.id_agrupador,
                        ssig_ag.id_agrupador_padre,
                        ssig_ag.id_funcionario,
                        ssig_ag.nombre,
                        ssig_ag.descripcion,
                        CASE 
                          WHEN ssig_ag.nivel ISNULL 
                              THEN 0::INTEGER 
                          ELSE ssig_ag.nivel::INTEGER 
                        END 
                          AS nivel,
                        ssig_ag.peso,
                        PERSON.nombre_completo2::varchar AS desc_person,
                        CASE
                          WHEN (ssig_ag.id_agrupador_padre is  null )
                              THEN ''raiz''::varchar
                          WHEN (ssig_ag.id_agrupador_padre is  not null and ssig_ag.nivel = 1) 
                              THEN ''hijo''::varchar
                          WHEN (ssig_ag.id_agrupador_padre is  not null and ssig_ag.nivel = 2) 
                              THEN ''hoja''::varchar 
                        END AS tipo_nodo,
                        ssig_ag.id_gestion,
                        ssig_ag.aprobado,
                        ssig_ag.peso_acumulado AS porcentaje_acum,
                        CASE 
                          WHEN (ssig_ag.peso_acumulado>0) 
                              THEN 100-coalesce(ssig_ag.peso_acumulado,0) 
                        END AS porcentaje_rest,   
                        padre.nombre AS nombre_agrupador_padre,
                        CASE 
                          WHEN ssig_ag.peso_acumulado ISNULL 
                              THEN (''<font color="#228b22">ACUM.:''||SUM(sag.peso) OVER (partition by sag.id_agrupador)||''%</font>'')::varchar
                          ELSE (''<font color="#228b22">ACUM.:''||ssig_ag.peso_acumulado||''%</font>'')::varchar 
                        END AS porcentaje_acumulado,  
                        CASE 
                          WHEN (ssig_ag.peso_acumulado>0) 
                              THEN (''<font color="red">REST.:''||100-coalesce(ssig_ag.peso_acumulado,0)||''%</font>'')::varchar 
                          WHEN ssig_ag.peso_acumulado ISNULL 
                              THEN ''-''::varchar    
                        END AS porcentaje_restante,
                        
                        (select ar.resultado from ssig.tagrupador_resultado ar where ar.id_agrupador=ssig_ag.id_agrupador and ar.id_periodo='||v_parametros.id_periodo||') ::NUMERIC resultado,
                        ('||v_parametros.id_periodo||')::INTEGER as id_periodo,
                        ssig_ag.orden_logico::INTEGER ,
                        (ssig.f_ordenar_sigla(COALESCE(ssig_ag.orden_logico,0)::varchar))::varchar as  orden_logico_temporal
                                                      
                        FROM ssig.tagrupador ssig_ag
                        left JOIN ssig.tagrupador_indicador sag ON sag.id_agrupador=ssig_ag.id_agrupador
                        LEFT JOIN ssig.tagrupador padre ON padre.id_agrupador = ssig_ag.id_agrupador_padre
                        LEFT JOIN orga.tfuncionario t ON t.id_funcionario=ssig_ag.id_funcionario 
                        INNER JOIN segu.tpersona per ON per.id_persona = t.id_persona
                        INNER JOIN segu.vpersona PERSON ON PERSON.id_persona = per.id_persona 
                        INNER JOIN param.tgestion g ON g.id_gestion=ssig_ag.id_gestion
				        WHERE  ';

				IF v_parametros.id_padre != '%'
				THEN
					v_consulta:=v_consulta || ' ssig_ag.id_agrupador_padre= ' || v_parametros.id_padre;
				ELSE
					v_consulta:=v_consulta || ' ssig_ag.id_agrupador_padre is null ';
				END IF;
               
                
				IF (v_parametros.id_gestion :: INTEGER >= 0)
                THEN
                	v_consulta:=v_consulta || ' and ssig_ag.id_gestion = ' || v_parametros.id_gestion;
                ELSE
					v_consulta:=v_consulta || ' and ssig_ag.id_gestion = 0  ';                 
                END IF;
				--Definicion de la respuesta
				--v_consulta:=v_consulta||v_parametros.filtro;
				--v_consulta:=v_consulta||' order by ' ||v_parametros.ordenacion|| ' ' || v_parametros.dir_ordenacion || ' limit ' || v_parametros.cantidad || ' offset ' || v_parametros.puntero;
			
				--Devuelve la respuesta
                --raise notice 'err %',v_consulta;
                --raise exception '%',v_consulta;
				return v_consulta;

			end;
					
	else
					     
		raise exception 'Transaccion inexistente';
					         
	end if;
					
EXCEPTION
					
	WHEN OTHERS THEN
			v_resp='';
			v_resp = pxp.f_agrega_clave(v_resp,'mensaje',SQLERRM);
			v_resp = pxp.f_agrega_clave(v_resp,'codigo_error',SQLSTATE);
			v_resp = pxp.f_agrega_clave(v_resp,'procedimientos',v_nombre_funcion);
			raise exception '%',v_resp;
END;
$body$
LANGUAGE 'plpgsql'
VOLATILE
CALLED ON NULL INPUT
SECURITY INVOKER
COST 100;
