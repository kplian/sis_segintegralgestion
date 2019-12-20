--------------- SQL ---------------

CREATE OR REPLACE FUNCTION ssig.ft_indicador_frecuencia_sel (
  p_administrador integer,
  p_id_usuario integer,
  p_tabla varchar,
  p_transaccion varchar
)
RETURNS varchar AS
$body$
/**************************************************************************
 SISTEMA:		Seguimiento integral de gestión
 FUNCION: 		ssig.ft_indicador_frecuencia_sel
 DESCRIPCION:   Funcion que devuelve conjuntos de registros de las consultas relacionadas con la tabla 'ssig.tindicador_frecuencia'
 AUTOR: 		 (admin)
 FECHA:	        21-11-2016 12:35:24
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

	v_nombre_funcion = 'ssig.ft_indicador_frecuencia_sel';
    v_parametros = pxp.f_get_record(p_tabla);

	/*********************************    
 	#TRANSACCION:  'SSIG_INFR_SEL'
 	#DESCRIPCION:	Consulta de datos
 	#AUTOR:		admin	
 	#FECHA:		21-11-2016 12:35:24
	***********************************/

	if(p_transaccion='SSIG_INFR_SEL')then
     				
    	begin
    		--Sentencia de la consulta
			v_consulta:='select
						infr.id_indicador_frecuencia,
						infr.valor,
						infr.hito,
						infr.estado_reg,
						infr.frecuencia,
						infr.id_usuario_ai,
						infr.id_usuario_reg,
						infr.usuario_ai,
						infr.fecha_reg,
						infr.id_usuario_mod,
						infr.fecha_mod,
						usu1.cuenta as usr_reg,
						usu2.cuenta as usr_mod	
						from ssig.tindicador_frecuencia infr
						inner join segu.tusuario usu1 on usu1.id_usuario = infr.id_usuario_reg
						left join segu.tusuario usu2 on usu2.id_usuario = infr.id_usuario_mod
				        where  ';
			
			--Definicion de la respuesta
			v_consulta:=v_consulta||v_parametros.filtro;
			v_consulta:=v_consulta||' order by ' ||v_parametros.ordenacion|| ' ' || v_parametros.dir_ordenacion || ' limit ' || v_parametros.cantidad || ' offset ' || v_parametros.puntero;

			--Devuelve la respuesta
			return v_consulta;
						
		end;

	/*********************************    
 	#TRANSACCION:  'SSIG_INFR_CONT'
 	#DESCRIPCION:	Conteo de registros
 	#AUTOR:		admin	
 	#FECHA:		21-11-2016 12:35:24
	***********************************/

	elsif(p_transaccion='SSIG_INFR_CONT')then

		begin
			--Sentencia de la consulta de conteo de registros
			v_consulta:='select count(id_indicador_frecuencia)
					    from ssig.tindicador_frecuencia infr
					    inner join segu.tusuario usu1 on usu1.id_usuario = infr.id_usuario_reg
						left join segu.tusuario usu2 on usu2.id_usuario = infr.id_usuario_mod
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