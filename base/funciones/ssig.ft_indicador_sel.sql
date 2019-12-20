CREATE OR REPLACE FUNCTION ssig.ft_indicador_sel (
  p_administrador integer,
  p_id_usuario integer,
  p_tabla varchar,
  p_transaccion varchar
)
RETURNS varchar AS
$body$
/**************************************************************************
 SISTEMA:		Seguimiento integral de gestión
 FUNCION: 		ssig.ft_indicador_sel
 DESCRIPCION:   Funcion que devuelve conjuntos de registros de las consultas relacionadas con la tabla 'ssig.tindicador'
 AUTOR: 		 JUAN
 FECHA:	        24-11-2017
 COMENTARIOS:	
***************************************************************************
 HISTORIAL DE MODIFICACIONES:

 DESCRIPCION: Registro de datos del modulo indicadores	
 AUTOR: JUAN			
 FECHA:	24-11-2017	
***************************************************************************/

DECLARE

	v_consulta    		varchar;
	v_parametros  		record;
	v_nombre_funcion   	text;
	v_resp				varchar;
			    
BEGIN

	v_nombre_funcion = 'ssig.ft_indicador_sel';
    v_parametros = pxp.f_get_record(p_tabla);

	/*********************************    
 	#TRANSACCION:  'SSIG_IND_SEL'
 	#DESCRIPCION:	Consulta de datos
 	#AUTOR:		JUAN	
 	#FECHA:		24-11-2017
	***********************************/

	if(p_transaccion='SSIG_IND_SEL')then
     				
    	begin
    		--Sentencia de la consulta
			v_consulta:='select
						ind.id_indicador,
						ind.id_indicador_unidad,
						ind.id_indicador_frecuencia,
						ind.id_gestion,
						ind.num_decimal,
						ind.semaforo,
						ind.estado_reg,
						ind.sigla,
						ind.descipcion,
						ind.comparacion,
						ind.indicador,
						ind.usuario_ai,
						ind.fecha_reg,
						ind.id_usuario_reg,
						ind.id_usuario_ai,
						ind.fecha_mod,
						ind.id_usuario_mod,
						usu1.cuenta as usr_reg,
						usu2.cuenta as usr_mod,
                        inun.unidad,
                        inun.tipo,
                        ifr.frecuencia,
                        ge.gestion::integer,
                        (select sum(
                        case when  ((ind.semaforo = ''Simple'' and ind.comparacion=''Asc'' and ifr.frecuencia !=''Hito'') and (iv.semaforo1='''' or iv.semaforo2='''' or iv.semaforo3='''')) or ((ind.semaforo = ''Simple'' and ind.comparacion=''Desc'' and ifr.frecuencia !=''Hito'') and (iv.semaforo1='''' or iv.semaforo2='''' or iv.semaforo3=''''))  then 
                                1::integer 
                            else 
                                 case when  ((ind.semaforo = ''Simple'' and ind.comparacion=''Asc'' and ifr.frecuencia =''Hito'') and (iv.semaforo1='''' or iv.semaforo2='''' or iv.semaforo3='''' or iv.hito='''')) or ((ind.semaforo = ''Simple'' and ind.comparacion=''Desc'' and ifr.frecuencia =''Hito'') and (iv.semaforo1='''' or iv.semaforo2='''' or iv.semaforo3='''' or iv.hito=''''))  then 
                                      1::integer 
                                    else
                                       case when  ((ind.semaforo = ''Compuesto'' and ind.comparacion=''Asc'' and ifr.frecuencia !=''Hito'') and (iv.semaforo1='''' or iv.semaforo2='''' or iv.semaforo3='''' or iv.semaforo4='''' or iv.semaforo5='''')) or ((ind.semaforo = ''Compuesto'' and ind.comparacion=''Desc'' and ifr.frecuencia !=''Hito'') and (iv.semaforo1='''' or iv.semaforo2='''' or iv.semaforo3='''' or iv.semaforo4='''' or iv.semaforo5=''''))  then 
                                               1::integer
                                           else
                                               case when  ((ind.semaforo = ''Compuesto'' and ind.comparacion=''Asc'' and ifr.frecuencia =''Hito'') and (iv.semaforo1='''' and iv.semaforo2='''' and iv.semaforo3='''' and iv.semaforo4='''' and iv.semaforo5='''' and iv.hito='''')) or ((ind.semaforo = ''Compuesto'' and ind.comparacion=''Desc'' and ifr.frecuencia =''Hito'') and (iv.semaforo1='''' and iv.semaforo2='''' and iv.semaforo3='''' and iv.semaforo4='''' and iv.semaforo5='''' and iv.hito=''''))  then
                                                     1::integer
                                                  else
                                                     0::integer
                                               end
                                       end  
                                 end
                        end)
                        from ssig.tindicador_valor iv 
                        where iv.id_indicador=ind.id_indicador limit 1
                        )::integer as registro_completado,
                        
                        ind.id_funcionario_ingreso,
                        
                        (select PERSON.nombre_completo2 from  orga.tfuncionario t 
                                join segu.vpersona PERSON on PERSON.id_persona = t.id_persona 
                                where t.id_funcionario= ind.id_funcionario_ingreso )::varchar as desc_person,
                                
                        ind.id_funcionario_evaluacion,
                        
                        (select PERSON.nombre_completo2 from  orga.tfuncionario t 
                                join segu.vpersona PERSON on PERSON.id_persona = t.id_persona 
                                where t.id_funcionario= ind.id_funcionario_evaluacion )::varchar as desc_person2,
                                
                        (ssig.f_ordenar_sigla(ind.sigla))::varchar as  orden_sigla
                                
						from ssig.tindicador ind
                        join ssig.tindicador_unidad inun on inun.id_indicador_unidad=ind.id_indicador_unidad
                        join ssig.tindicador_frecuencia ifr on ifr.id_indicador_frecuencia=ind.id_indicador_frecuencia
                        join param.tgestion ge on ge.id_gestion=ind.id_gestion
						inner join segu.tusuario usu1 on usu1.id_usuario = ind.id_usuario_reg
						left join segu.tusuario usu2 on usu2.id_usuario = ind.id_usuario_mod
				        where  ';
			
			--Definicion de la respuesta
			v_consulta:=v_consulta||v_parametros.filtro;
			v_consulta:=v_consulta||' order by ' ||v_parametros.ordenacion|| ' ' || v_parametros.dir_ordenacion || ' limit ' || v_parametros.cantidad || ' offset ' || v_parametros.puntero;

			--Devuelve la respuesta
            
            --RAISE NOTICE 'Indicador juan %',v_consulta;
            --RAISE EXCEPTION 'Error provocado por juan %', v_consulta;
            
			return v_consulta;
						
		end;

	/*********************************    
 	#TRANSACCION:  'SSIG_IND_CONT'
 	#DESCRIPCION:	Conteo de registros
 	#AUTOR:		admin	
 	#FECHA:		21-11-2016 14:51:35
	***********************************/

	elsif(p_transaccion='SSIG_IND_CONT')then

		begin
			--Sentencia de la consulta de conteo de registros
			v_consulta:='select count(ind.id_indicador)
					    from ssig.tindicador ind
                        join ssig.tindicador_unidad inun on inun.id_indicador_unidad=ind.id_indicador_unidad
                        join ssig.tindicador_frecuencia ifr on ifr.id_indicador_frecuencia=ind.id_indicador_frecuencia
                        join param.tgestion ge on ge.id_gestion=ind.id_gestion
					    inner join segu.tusuario usu1 on usu1.id_usuario = ind.id_usuario_reg
						left join segu.tusuario usu2 on usu2.id_usuario = ind.id_usuario_mod
					    where ';
			
			--Definicion de la respuesta		    
			v_consulta:=v_consulta||v_parametros.filtro;

			--Devuelve la respuesta
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
