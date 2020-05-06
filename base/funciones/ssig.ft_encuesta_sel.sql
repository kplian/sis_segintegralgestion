CREATE OR REPLACE FUNCTION ssig.ft_encuesta_sel (
  p_administrador integer,
  p_id_usuario integer,
  p_tabla varchar,
  p_transaccion varchar
)
RETURNS varchar AS
$body$
/**************************************************************************
 SISTEMA:		Seguimiento integral de gestión
 FUNCION: 		ssig.ft_encuesta_sel
 DESCRIPCION:   Funcion que devuelve conjuntos de registros de las consultas relacionadas con la tabla 'ssig.tencuesta'
 AUTOR: 		 (admin.miguel)
 FECHA:	        29-04-2020 06:10:09
 COMENTARIOS:
***************************************************************************
 HISTORIAL DE MODIFICACIONES:
#ISSUE				FECHA				AUTOR				DESCRIPCION
 #0				29-04-2020 06:10:09								Funcion que devuelve conjuntos de registros de las consultas relacionadas con la tabla 'ssig.tencuesta'
 #
 ***************************************************************************/

DECLARE

	v_consulta    		varchar;
	v_parametros  		record;
	v_nombre_funcion   	text;
	v_resp				varchar;
    v_where				varchar;

BEGIN

	v_nombre_funcion = 'ssig.ft_encuesta_sel';
    v_parametros = pxp.f_get_record(p_tabla);

	/*********************************
 	#TRANSACCION:  'SSIG_ETA_SEL'
 	#DESCRIPCION:	Consulta de datos
 	#AUTOR:		admin.miguel
 	#FECHA:		29-04-2020 06:10:09
	***********************************/

	if(p_transaccion='SSIG_ETA_SEL')then

    	begin
    		--Sentencia de la consulta
			v_consulta:='select
						eta.id_encuesta,
						eta.estado_reg,
						eta.obs_dba,
						eta.nro_order,
						eta.nombre,
						eta.grupo,
						eta.categoria,
						eta.habilitado_categoria,
						eta.peso_categoria,
						eta.pregunta,
						eta.habilitado_pregunta,
						eta.tipo_pregunta,
						eta.id_encuesta_padre,
						eta.id_usuario_reg,
						eta.fecha_reg,
						eta.id_usuario_ai,
						eta.usuario_ai,
						eta.id_usuario_mod,
						eta.fecha_mod,
						usu1.cuenta as usr_reg,
						usu2.cuenta as usr_mod,
                        eta.tipo,
                        eta.tipo_nombre
						from ssig.tencuesta eta
						inner join segu.tusuario usu1 on usu1.id_usuario = eta.id_usuario_reg
						left join segu.tusuario usu2 on usu2.id_usuario = eta.id_usuario_mod
				        where  ';

			--Definicion de la respuesta
			v_consulta:=v_consulta||v_parametros.filtro;
			v_consulta:=v_consulta||' order by ' ||v_parametros.ordenacion|| ' ' || v_parametros.dir_ordenacion || ' limit ' || v_parametros.cantidad || ' offset ' || v_parametros.puntero;

			--Devuelve la respuesta
			return v_consulta;

		end;

	/*********************************
 	#TRANSACCION:  'SSIG_ETA_CONT'
 	#DESCRIPCION:	Conteo de registros
 	#AUTOR:		admin.miguel
 	#FECHA:		29-04-2020 06:10:09
	***********************************/

	elsif(p_transaccion='SSIG_ETA_CONT')then

		begin
			--Sentencia de la consulta de conteo de registros
			v_consulta:='select count(id_encuesta)
					    from ssig.tencuesta eta
					    inner join segu.tusuario usu1 on usu1.id_usuario = eta.id_usuario_reg
						left join segu.tusuario usu2 on usu2.id_usuario = eta.id_usuario_mod
					    where ';

			--Definicion de la respuesta
			v_consulta:=v_consulta||v_parametros.filtro;

			--Devuelve la respuesta
			return v_consulta;

		end;

    /*********************************
 	#TRANSACCION:  'SSIG_ETAR_SEL'
 	#DESCRIPCION:	Arbol
 	#AUTOR:		admin.miguel
 	#FECHA:		29-04-2020 06:10:09
	***********************************/

	elsif(p_transaccion='SSIG_ETAR_SEL')then

		begin

              if(v_parametros.node = 'id') then
                v_where := ' eta.id_encuesta_padre is NULL  ';
              else
                v_where := ' eta.id_encuesta_padre = '||v_parametros.node;
              end if;

			v_consulta:='select eta.id_encuesta,
                                eta.estado_reg,
                                eta.obs_dba,
                                eta.nro_order,
                                eta.nombre,
                                eta.grupo,
                                eta.categoria,
                                eta.habilitado_categoria,
                                eta.peso_categoria,
                                eta.pregunta,
                                eta.habilitado_pregunta,
                                eta.tipo_pregunta,
                                eta.id_encuesta_padre,
                                eta.id_usuario_reg,
                                eta.fecha_reg,
                                eta.id_usuario_ai,
                                eta.usuario_ai,
                                eta.id_usuario_mod,
                                eta.fecha_mod,
                                usu1.cuenta as usr_reg,
                                usu2.cuenta as usr_mod,
                                eta.tipo,
                                eta.tipo_nombre,
                                (case
                                	when eta.tipo_nombre = ''encuesta'' then
                                    ''raiz''
                                    when eta.tipo_nombre in (''grupo'', ''categoria'') then
                                    ''hijo''
                                    else
                                    ''hoja''
                                    end)::varchar as tipo_nodo
                                from ssig.tencuesta eta
                                inner join segu.tusuario usu1 on usu1.id_usuario = eta.id_usuario_reg
                                left join segu.tusuario usu2 on usu2.id_usuario = eta.id_usuario_mod
                                where '||v_where|| '';

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

ALTER FUNCTION ssig.ft_encuesta_sel (p_administrador integer, p_id_usuario integer, p_tabla varchar, p_transaccion varchar)
  OWNER TO postgres;