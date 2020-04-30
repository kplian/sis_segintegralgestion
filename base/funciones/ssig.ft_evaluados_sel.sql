--------------- SQL ---------------

CREATE OR REPLACE FUNCTION ssig.ft_evaluados_sel (
  p_administrador integer,
  p_id_usuario integer,
  p_tabla varchar,
  p_transaccion varchar
)
RETURNS varchar AS
$body$
/**************************************************************************
 SISTEMA:		Seguimiento integral de gestión
 FUNCION: 		ssig.ft_evaluados_sel
 DESCRIPCION:   Funcion que devuelve conjuntos de registros de las consultas relacionadas con la tabla 'ssig.tevaluados'
 AUTOR: 		 (admin.miguel)
 FECHA:	        28-04-2020 01:32:33
 COMENTARIOS:	
***************************************************************************
 HISTORIAL DE MODIFICACIONES:
#ISSUE				FECHA				AUTOR				DESCRIPCION
 #0				28-04-2020 01:32:33								Funcion que devuelve conjuntos de registros de las consultas relacionadas con la tabla 'ssig.tevaluados'	
 #
 ***************************************************************************/

DECLARE

	v_consulta    		varchar;
	v_parametros  		record;
	v_nombre_funcion   	text;
	v_resp				varchar;
			    
BEGIN

	v_nombre_funcion = 'ssig.ft_evaluados_sel';
    v_parametros = pxp.f_get_record(p_tabla);

	/*********************************    
 	#TRANSACCION:  'SSIG_EVS_SEL'
 	#DESCRIPCION:	Consulta de datos
 	#AUTOR:		admin.miguel	
 	#FECHA:		28-04-2020 01:32:33
	***********************************/

	if(p_transaccion='SSIG_EVS_SEL')then
     				
    	begin
    		--Sentencia de la consulta
			v_consulta:='select
						evs.id_evaluados,
						evs.estado_reg,
						evs.obs_dba,
						evs.id_cuestionario_funcionario,
						evs.id_funcionario,
						evs.evaluar,
						evs.id_usuario_reg,
						evs.fecha_reg,
						evs.id_usuario_ai,
						evs.usuario_ai,
						evs.id_usuario_mod,
						evs.fecha_mod,
						usu1.cuenta as usr_reg,
						usu2.cuenta as usr_mod,
                        fun.desc_funcionario1,
                        fun.nombre_unidad	
						from ssig.tevaluados evs
						inner join segu.tusuario usu1 on usu1.id_usuario = evs.id_usuario_reg
                        inner join orga.vfuncionario_cargo fun on fun.id_funcionario = evs.id_funcionario
						left join segu.tusuario usu2 on usu2.id_usuario = evs.id_usuario_mod
				        where (fun.fecha_finalizacion is null or fun.fecha_finalizacion >= now()::date) and  ';
			
			--Definicion de la respuesta
			v_consulta:=v_consulta||v_parametros.filtro;
			v_consulta:=v_consulta||' order by ' ||v_parametros.ordenacion|| ' ' || v_parametros.dir_ordenacion || ' limit ' || v_parametros.cantidad || ' offset ' || v_parametros.puntero;

			--Devuelve la respuesta
			return v_consulta;
						
		end;

	/*********************************    
 	#TRANSACCION:  'SSIG_EVS_CONT'
 	#DESCRIPCION:	Conteo de registros
 	#AUTOR:		admin.miguel	
 	#FECHA:		28-04-2020 01:32:33
	***********************************/

	elsif(p_transaccion='SSIG_EVS_CONT')then

		begin
			--Sentencia de la consulta de conteo de registros
			v_consulta:='select count(id_evaluados)
					    from ssig.tevaluados evs
					    inner join segu.tusuario usu1 on usu1.id_usuario = evs.id_usuario_reg
                        inner join orga.vfuncionario_cargo fun on fun.id_funcionario = evs.id_funcionario
						left join segu.tusuario usu2 on usu2.id_usuario = evs.id_usuario_mod
				        where (fun.fecha_finalizacion is null or fun.fecha_finalizacion >= now()::date) and ';
			
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
PARALLEL UNSAFE
COST 100;