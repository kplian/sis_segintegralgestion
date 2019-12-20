CREATE OR REPLACE FUNCTION ssig.ft_agrupador_indicador_sel (
  p_administrador integer,
  p_id_usuario integer,
  p_tabla varchar,
  p_transaccion varchar
)
RETURNS varchar AS
$body$
/**************************************************************************
 SISTEMA:		Seguimiento integral de gestión
 FUNCION: 		ssig.ft_agrupador_indicador_sel
 DESCRIPCION:   Funcion que devuelve conjuntos de registros de las consultas relacionadas con la tabla 'ssig.tagrupador_indicador'
 AUTOR: 		 (admin)
 FECHA:	        16-02-2017 01:23:13
 COMENTARIOS:	
***************************************************************************
 HISTORIAL DE MODIFICACIONES:

 DESCRIPCION:	
 AUTOR:			
 FECHA:		
***************************************************************************/

DECLARE

	v_consulta    		varchar;
	v_parametros  		record;
	v_nombre_funcion   	text;
	v_resp				varchar;
			    
BEGIN

	v_nombre_funcion = 'ssig.ft_agrupador_indicador_sel';
    v_parametros = pxp.f_get_record(p_tabla);

	/*********************************    
 	#TRANSACCION:  'SSIG_AGIN_SEL'
 	#DESCRIPCION:	Consulta de datos
 	#AUTOR:		admin	
 	#FECHA:		16-02-2017 01:23:13
	***********************************/

	if(p_transaccion='SSIG_AGIN_SEL')then
     				
    	begin
    		--Sentencia de la consulta
			v_consulta:='
            			select
                        agin.id_agrupador_indicador,
                        agin.id_agrupador,
                        agin.id_indicador,
                        /*agin.id_funcionario_ingreso,
                        PERSON.nombre_completo2::varchar AS desc_person,
                        agin.id_funcionario_evaluacion,
                        PERSON1.nombre_completo2::varchar AS desc_person2,*/
                        agin.peso,
                        agin.estado_reg,
                        agin.id_usuario_ai,
                        agin.fecha_reg,
                        agin.usuario_ai,
                        agin.id_usuario_reg,
                        agin.id_usuario_mod,
                        agin.fecha_mod,
                        usu1.cuenta as usr_reg,
                        usu2.cuenta as usr_mod,
                        ind.indicador,
                        ind.sigla,                        
                        padre.nombre AS nombre_padre,                       
                        sum(agin.peso) OVER (partition by agin.id_agrupador)::INTEGER as totalidad,
                        (select ar.resultado from ssig.tagrupador_indicador_resultado ar where ar.id_agrupador_indicador=agin.id_agrupador_indicador and ar.id_periodo='||v_parametros.id_periodo||')::numeric as resultado,
                        (select ar.semaforo1 from ssig.tagrupador_indicador_resultado ar where ar.id_agrupador_indicador=agin.id_agrupador_indicador and ar.id_periodo='||v_parametros.id_periodo||')::varchar as semaforo1,
                        (select ar.semaforo2 from ssig.tagrupador_indicador_resultado ar where ar.id_agrupador_indicador=agin.id_agrupador_indicador and ar.id_periodo='||v_parametros.id_periodo||')::varchar as semaforo2,
                        (select ar.semaforo3 from ssig.tagrupador_indicador_resultado ar where ar.id_agrupador_indicador=agin.id_agrupador_indicador and ar.id_periodo='||v_parametros.id_periodo||')::varchar as semaforo3,
                        (select ar.semaforo4 from ssig.tagrupador_indicador_resultado ar where ar.id_agrupador_indicador=agin.id_agrupador_indicador and ar.id_periodo='||v_parametros.id_periodo||')::varchar as semaforo4,
                        (select ar.semaforo5 from ssig.tagrupador_indicador_resultado ar where ar.id_agrupador_indicador=agin.id_agrupador_indicador and ar.id_periodo='||v_parametros.id_periodo||')::varchar as semaforo5,
                        (select ar.valor_real from ssig.tagrupador_indicador_resultado ar where ar.id_agrupador_indicador=agin.id_agrupador_indicador and ar.id_periodo='||v_parametros.id_periodo||')::varchar as valor_real,
                        agin.semaforo,
                        agin.comparacion,
                        (select ar.ruta_icono from ssig.tagrupador_indicador_resultado ar where ar.id_agrupador_indicador=agin.id_agrupador_indicador and ar.id_periodo='||v_parametros.id_periodo||')::varchar as ruta_icono,
                        (select ar.justificacion from ssig.tagrupador_indicador_resultado ar where ar.id_agrupador_indicador=agin.id_agrupador_indicador and ar.id_periodo='||v_parametros.id_periodo||')::varchar as justificacion,
                        
                        (ssig.f_ordenar_sigla(ind.sigla))::varchar as  orden_sigla,
                        agin.orden_logico::integer
                        
                        from ssig.tagrupador_indicador agin
                        LEFT JOIN ssig.tagrupador padre ON padre.id_agrupador = agin.id_agrupador
                        inner join segu.tusuario usu1 on usu1.id_usuario = agin.id_usuario_reg                        
                        left join segu.tusuario usu2 on usu2.id_usuario = agin.id_usuario_mod						
                        inner join ssig.tindicador ind on ind.id_indicador = agin.id_indicador
                        /*left join orga.tfuncionario t on t.id_funcionario= agin.id_funcionario_ingreso 
                        left join orga.tfuncionario t1 on t1.id_funcionario= agin.id_funcionario_evaluacion
                        inner join segu.tpersona per on per.id_persona = t.id_persona
                        inner join segu.vpersona PERSON on PERSON.id_persona = per.id_persona 
                        left join segu.tpersona per1 on per1.id_persona = t1.id_persona
                        left join segu.vpersona PERSON1 on PERSON1.id_persona = per1.id_persona*/                     
				        where  ';
			
			--Definicion de la respuesta                        
               
			v_consulta:=v_consulta||v_parametros.filtro;
                             
			v_consulta:=v_consulta||' order by ' ||v_parametros.ordenacion|| ' ' || v_parametros.dir_ordenacion || ' limit ' || v_parametros.cantidad || ' offset ' || v_parametros.puntero;
--RAISE NOTICE '%',v_parametros.filtro;  
			return v_consulta;
						
		end;

	/*********************************    
 	#TRANSACCION:  'SSIG_AGIN_CONT'
 	#DESCRIPCION:	Conteo de registros
 	#AUTOR:		admin	
 	#FECHA:		16-02-2017 01:23:13
	***********************************/

	elsif(p_transaccion='SSIG_AGIN_CONT')then

		begin
			--Sentencia de la consulta de conteo de registros
			v_consulta:='select count(id_agrupador_indicador)
					    from ssig.tagrupador_indicador agin
					    inner join segu.tusuario usu1 on usu1.id_usuario = agin.id_usuario_reg
						left join segu.tusuario usu2 on usu2.id_usuario = agin.id_usuario_mod
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
