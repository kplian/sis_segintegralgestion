--------------- SQL ---------------

CREATE OR REPLACE FUNCTION ssig.ft_indicador_valor_sel (
  p_administrador integer,
  p_id_usuario integer,
  p_tabla varchar,
  p_transaccion varchar
)
RETURNS varchar AS
$body$
/**************************************************************************
 SISTEMA:		Seguimiento integral de gestión
 FUNCION: 		ssig.ft_indicador_valor_sel
 DESCRIPCION:   Funcion que devuelve conjuntos de registros de las consultas relacionadas con la tabla 'ssig.tindicador_valor'
 AUTOR: 		 (admin)
 FECHA:	        21-11-2016 14:01:15
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

	v_nombre_funcion = 'ssig.ft_indicador_valor_sel';
    v_parametros = pxp.f_get_record(p_tabla);

	/*********************************    
 	#TRANSACCION:  'SSIG_INVA_SEL'
 	#DESCRIPCION:	Consulta de datos
 	#AUTOR:		admin	
 	#FECHA:		21-11-2016 14:01:15
	***********************************/

	if(p_transaccion='SSIG_INVA_SEL')then
     				
    	begin
    		--Sentencia de la consulta
			v_consulta:='select
						inva.id_indicador_valor,
						inva.id_indicador,
						inva.semaforo3,
						inva.semaforo5,
						inva.no_reporta,
						inva.semaforo4,
						inva.estado_reg,
						inva.semaforo2,
						inva.valor,
						inva.fecha,
						inva.hito,
						inva.semaforo1,
						inva.justificacion,
						inva.fecha_reg,
						inva.usuario_ai,
						inva.id_usuario_reg,
						inva.id_usuario_ai,
						inva.fecha_mod,
						inva.id_usuario_mod,
						usu1.cuenta as usr_reg,
						usu2.cuenta as usr_mod,
                        ind.semaforo,
                        ifr.frecuencia
						from ssig.tindicador_valor inva
						inner join segu.tusuario usu1 on usu1.id_usuario = inva.id_usuario_reg
						left join segu.tusuario usu2 on usu2.id_usuario = inva.id_usuario_mod
                        join ssig.tindicador ind on ind.id_indicador=inva.id_indicador
                        join ssig.tindicador_frecuencia ifr on ifr.id_indicador_frecuencia=ind.id_indicador_frecuencia
				        where  ';
			
			--Definicion de la respuesta
			v_consulta:=v_consulta||v_parametros.filtro;
			v_consulta:=v_consulta||' order by ' ||v_parametros.ordenacion|| ' ' || v_parametros.dir_ordenacion || ' limit ' || v_parametros.cantidad || ' offset ' || v_parametros.puntero;

			--Devuelve la respuesta
			return v_consulta;
						
		end;

	/*********************************    
 	#TRANSACCION:  'SSIG_INVA_CONT'
 	#DESCRIPCION:	Conteo de registros
 	#AUTOR:		admin	
 	#FECHA:		21-11-2016 14:01:15
	***********************************/

	elsif(p_transaccion='SSIG_INVA_CONT')then

		begin
			--Sentencia de la consulta de conteo de registros
			v_consulta:='select count(id_indicador_valor)
					    from ssig.tindicador_valor inva
					    inner join segu.tusuario usu1 on usu1.id_usuario = inva.id_usuario_reg
						left join segu.tusuario usu2 on usu2.id_usuario = inva.id_usuario_mod
                        join ssig.tindicador ind on ind.id_indicador=inva.id_indicador
                        join ssig.tindicador_frecuencia ifr on ifr.id_indicador_frecuencia=ind.id_indicador_frecuencia
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