CREATE OR REPLACE FUNCTION ssig.ft_linea_sel (
  p_administrador integer,
  p_id_usuario integer,
  p_tabla varchar,
  p_transaccion varchar
)
RETURNS varchar AS
$body$
/**************************************************************************
 SISTEMA:		Seguimiento integral de gestión
 FUNCION: 		ssig.ft_linea_sel
 DESCRIPCION:   Funcion que devuelve conjuntos de registros de las consultas relacionadas con la tabla 'ssig.tlinea'
 AUTOR: 		 (admin)
 FECHA:	        11-04-2017 20:20:49
 COMENTARIOS:	
***************************************************************************
 HISTORIAL DE MODIFICACIONES:

 DESCRIPCION:	
 AUTOR:			
 FECHA:		
***************************************************************************/

DECLARE

  v_consulta       VARCHAR;
  v_parametros     RECORD;
  v_nombre_funcion TEXT;
  v_resp           VARCHAR;

BEGIN

  v_nombre_funcion = 'ssig.ft_linea_sel';
  v_parametros = pxp.f_get_record(p_tabla);

  /*********************************
   #TRANSACCION:  'SSIG_LINEA_SEL'
   #DESCRIPCION:	Consulta de datos
   #AUTOR:		admin
   #FECHA:		11-04-2017 20:20:49
  ***********************************/

  IF (p_transaccion = 'SSIG_LINEA_SEL')
  THEN

    BEGIN
      --Sentencia de la consulta
      v_consulta:='select
						linea.id_linea,
						linea.id_linea_padre,
						linea.id_plan,
						linea.estado_reg,
						linea.nivel,
						linea.nombre_linea,
						linea.peso,
						linea.fecha_reg,
						linea.usuario_ai,
						linea.id_usuario_reg,
						linea.id_usuario_ai,
						linea.id_usuario_mod,
						linea.fecha_mod,
						usu1.cuenta as usr_reg,
						usu2.cuenta as usr_mod	
						from ssig.tlinea linea
						inner join segu.tusuario usu1 on usu1.id_usuario = linea.id_usuario_reg
						left join segu.tusuario usu2 on usu2.id_usuario = linea.id_usuario_mod
				        where  ';

      --Definicion de la respuesta
      v_consulta:=v_consulta || v_parametros.filtro;
      v_consulta:=
      v_consulta || ' order by ' || v_parametros.ordenacion || ' ' || v_parametros.dir_ordenacion || ' limit ' ||
      v_parametros.cantidad || ' offset ' || v_parametros.puntero;

      --Devuelve la respuesta
      RETURN v_consulta;

    END;

    /*********************************
     #TRANSACCION:  'SSIG_LINEA_CONT'
     #DESCRIPCION:	Conteo de registros
     #AUTOR:		admin
     #FECHA:		11-04-2017 20:20:49
    ***********************************/

  ELSIF (p_transaccion = 'SSIG_LINEA_CONT')
    THEN

      BEGIN
        --Sentencia de la consulta de conteo de registros
        v_consulta:='select count(id_linea)
					    from ssig.tlinea linea
					    inner join segu.tusuario usu1 on usu1.id_usuario = linea.id_usuario_reg
						left join segu.tusuario usu2 on usu2.id_usuario = linea.id_usuario_mod
					    where ';

        --Definicion de la respuesta
        v_consulta:=v_consulta || v_parametros.filtro;

        --Devuelve la respuesta
        RETURN v_consulta;

      END;

  ELSEIF (p_transaccion = 'SSIG_LINEA_SEL_ARB')
    THEN

      BEGIN
        --Sentencia de la consulta
        v_consulta:='select
						linea.id_linea,
						linea.id_linea_padre,
						linea.id_plan,
						linea.nivel,
						linea.nombre_linea,
						linea.peso,
						case
              when (linea.id_linea_padre is  null )then
                ''raiz''::varchar
              when (linea.id_linea_padre is  not null and linea.nivel = 1) then
                ''hijo''::varchar
              when (linea.id_linea_padre is  not null and linea.nivel = 2) then
                ''hoja''::varchar
            END as tipo_nodo,
              linea.peso_acumulado  as porcentaje_acum,
              case when (linea.peso_acumulado>0) then 100-coalesce(linea.peso_acumulado,0) end  as porcentaje_rest,
            (select  array_to_string( array_agg(DISTINCT lf.id_funcionario), '','' )
                        from ssig.tlinea_funcionario lf join ssig.tlinea l on l.id_linea=lf.id_linea
                        where lf.id_linea=linea.id_linea)::VARCHAR as id_funcionarios,

            (select array_to_string( array_agg(DISTINCT PERSON.nombre_completo2), ''<br>'' )
                        from ssig.tlinea_funcionario lf join ssig.tlinea l on l.id_linea=lf.id_linea
                        join orga.tfuncionario FUNCIO on FUNCIO.id_funcionario=lf.id_funcionario
                        join SEGU.vpersona PERSON ON PERSON.id_persona=FUNCIO.id_persona
                        where lf.id_linea=linea.id_linea)::VARCHAR as funcionarios,
                        lpa.nombre_linea  as nombre_linea_padre,
            (''<font color="#228b22">ACUM.:''||linea.peso_acumulado||''%</font>'')::varchar as porcentaje_acumulado,
            case when (linea.peso_acumulado>0) then (''<font color="red">REST.:''||100-coalesce(linea.peso_acumulado,0)||''%</font>'')::varchar end as porcentaje_restante,
            
            linea.orden_logico::varchar,
            (ssig.f_ordenar_sigla(linea.orden_logico::varchar))::varchar as  orden_logico_temporal
            
						from ssig.tlinea linea left join ssig.tlinea lpa on lpa.id_linea=linea.id_linea_padre
				        where  ';
        IF v_parametros.id_padre != '%'
        THEN
          v_consulta:=v_consulta || ' linea.id_linea_padre= ' || v_parametros.id_padre;
        ELSE
          v_consulta:=v_consulta || ' linea.id_linea_padre is null ';
        END IF;
        v_consulta:=v_consulta || ' and linea.id_plan = ' || v_parametros.id_plan;

        RAISE NOTICE '%', v_consulta;
        --RAISE EXCEPTION 'error yac provocado';
        --Devuelve la respuesta
        RETURN v_consulta;

      END;

  ELSE

    RAISE EXCEPTION 'Transaccion inexistente';

  END IF;

  EXCEPTION

  WHEN OTHERS
    THEN
      v_resp = '';
      v_resp = pxp.f_agrega_clave(v_resp, 'mensaje', SQLERRM);
      v_resp = pxp.f_agrega_clave(v_resp, 'codigo_error', SQLSTATE);
      v_resp = pxp.f_agrega_clave(v_resp, 'procedimientos', v_nombre_funcion);
      RAISE EXCEPTION '%', v_resp;
END;
$body$
LANGUAGE 'plpgsql'
VOLATILE
CALLED ON NULL INPUT
SECURITY INVOKER
COST 100;
