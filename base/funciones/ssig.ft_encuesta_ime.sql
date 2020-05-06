CREATE OR REPLACE FUNCTION ssig.ft_encuesta_ime (
  p_administrador integer,
  p_id_usuario integer,
  p_tabla varchar,
  p_transaccion varchar
)
RETURNS varchar AS
$body$
/**************************************************************************
 SISTEMA:		Seguimiento integral de gestión
 FUNCION: 		ssig.ft_encuesta_ime
 DESCRIPCION:   Funcion que gestiona las operaciones basicas (inserciones, modificaciones, eliminaciones de la tabla 'ssig.tencuesta'
 AUTOR: 		 (admin.miguel)
 FECHA:	        29-04-2020 06:10:09
 COMENTARIOS:
***************************************************************************
 HISTORIAL DE MODIFICACIONES:
#ISSUE				FECHA				AUTOR				DESCRIPCION
 #0				29-04-2020 06:10:09								Funcion que gestiona las operaciones basicas (inserciones, modificaciones, eliminaciones de la tabla 'ssig.tencuesta'
 #
 ***************************************************************************/

DECLARE

	v_nro_requerimiento    	integer;
	v_parametros           	record;
	v_id_requerimiento     	integer;
	v_resp		            varchar;
	v_nombre_funcion        text;
	v_mensaje_error         text;
	v_id_encuesta			integer;
    v_id_encuesta_padre		integer;
    v_grupo					varchar;
    v_categoria				varchar;
    v_pregunta				varchar;
    v_record				record;


BEGIN

    v_nombre_funcion = 'ssig.ft_encuesta_ime';
    v_parametros = pxp.f_get_record(p_tabla);

	/*********************************
 	#TRANSACCION:  'SSIG_ETA_INS'
 	#DESCRIPCION:	Insercion de registros
 	#AUTOR:		admin.miguel
 	#FECHA:		29-04-2020 06:10:09
	***********************************/

	if(p_transaccion='SSIG_ETA_INS')then

        begin


           v_id_encuesta_padre = null;

           v_grupo = 'no';
           v_categoria = 'no';
           v_pregunta = 'no';

           if exists (select 1
                      from ssig.tencuesta en
                 	  where en.nro_order = v_parametros.nro_order and
                 	  en.tipo_nombre !='pregunta')then
                      raise exception 'Ya existe un registro registrado con el codigo %',v_parametros.nro_order;
           end if;

           if v_parametros.id_encuesta_padre != 'id' and v_parametros.id_encuesta_padre != '' THEN
           		v_id_encuesta_padre  = v_parametros.id_encuesta_padre::integer;
           end if;


           if (v_parametros.tipo_nombre = 'encuesta')then
           		v_id_encuesta_padre = null;
           end if;

           if (v_parametros.tipo_nombre = 'grupo')then
           		v_grupo = 'si';
           end if;

           if (v_parametros.tipo_nombre = 'categoria')then
           		v_categoria = 'si';
           end if;

           if (v_parametros.tipo_nombre = 'pregunta')then
           		v_pregunta = 'si';
           end if;

        	--Sentencia de la insercion
        	insert into ssig.tencuesta(
			estado_reg,
			obs_dba,
			nro_order,
			nombre,
			grupo,
			categoria,
			habilitado_categoria,
			peso_categoria,
			pregunta,
			habilitado_pregunta,
			tipo_pregunta,
			id_encuesta_padre,
			id_usuario_reg,
			fecha_reg,
			id_usuario_ai,
			usuario_ai,
			id_usuario_mod,
			fecha_mod,
            tipo,
            tipo_nombre
          	) values(
			'activo',
			v_parametros.obs_dba,
			v_parametros.nro_order,
			v_parametros.nombre,
			v_grupo,
			v_categoria,
			v_parametros.habilitado_categoria,
			v_parametros.peso_categoria,
			v_pregunta,
			v_parametros.habilitado_pregunta,
			v_parametros.tipo_pregunta,
			v_id_encuesta_padre,
			p_id_usuario,
			now(),
			v_parametros._id_usuario_ai,
			v_parametros._nombre_usuario_ai,
			null,
			null,
            v_parametros.tipo,
            v_parametros.tipo_nombre
			)RETURNING id_encuesta into v_id_encuesta;

			--Definicion de la respuesta
			v_resp = pxp.f_agrega_clave(v_resp,'mensaje','Encuesta almacenado(a) con exito (id_encuesta'||v_id_encuesta||')');
            v_resp = pxp.f_agrega_clave(v_resp,'id_encuesta',v_id_encuesta::varchar);

            --Devuelve la respuesta
            return v_resp;


		end;

	/*********************************
 	#TRANSACCION:  'SSIG_ETA_MOD'
 	#DESCRIPCION:	Modificacion de registros
 	#AUTOR:		admin.miguel
 	#FECHA:		29-04-2020 06:10:09
	***********************************/

	elsif(p_transaccion='SSIG_ETA_MOD')then

		begin
        v_id_encuesta_padre = null;

           v_grupo = 'no';
           v_categoria = 'no';
           v_pregunta = 'no';


           if v_parametros.id_encuesta_padre != 'id' and v_parametros.id_encuesta_padre != '' THEN
           		v_id_encuesta_padre  = v_parametros.id_encuesta_padre::integer;
           end if;


           if (v_parametros.tipo_nombre = 'encuesta')then
           		v_id_encuesta_padre = null;
           end if;

           if (v_parametros.tipo_nombre = 'grupo')then
           		v_grupo = 'si';
           end if;

           if (v_parametros.tipo_nombre = 'categoria')then
           		v_categoria = 'si';
           end if;

           if (v_parametros.tipo_nombre = 'pregunta')then
           		v_pregunta = 'si';
           end if;

			--Sentencia de la modificacion
			update ssig.tencuesta set
			obs_dba = v_parametros.obs_dba,
			nro_order = v_parametros.nro_order,
			nombre = v_parametros.nombre,
			grupo = v_grupo,
			categoria = v_categoria,
			habilitado_categoria = v_parametros.habilitado_categoria,
			peso_categoria = v_parametros.peso_categoria,
			pregunta = v_pregunta,
			habilitado_pregunta = v_parametros.habilitado_pregunta,
			tipo_pregunta = v_parametros.tipo_pregunta,
		    id_encuesta_padre = v_id_encuesta_padre,
			id_usuario_mod = p_id_usuario,
			fecha_mod = now(),
			id_usuario_ai = v_parametros._id_usuario_ai,
			usuario_ai = v_parametros._nombre_usuario_ai,
            tipo = v_parametros.tipo,
            tipo_nombre = v_parametros.tipo_nombre
			where id_encuesta=v_parametros.id_encuesta;

			--Definicion de la respuesta
            v_resp = pxp.f_agrega_clave(v_resp,'mensaje','Encuesta modificado(a)');
            v_resp = pxp.f_agrega_clave(v_resp,'id_encuesta',v_parametros.id_encuesta::varchar);

            --Devuelve la respuesta
            return v_resp;

		end;

	/*********************************
 	#TRANSACCION:  'SSIG_ETA_ELI'
 	#DESCRIPCION:	Eliminacion de registros
 	#AUTOR:		admin.miguel
 	#FECHA:		29-04-2020 06:10:09
	***********************************/

	elsif(p_transaccion='SSIG_ETA_ELI')then

		begin
			for v_record in (with recursive encuesta_o( id_encuesta,
            											id_encuesta_padre) as (
                                             	select en.id_encuesta,
                                                       en.id_encuesta_padre
                                           		from ssig.tencuesta en
                                     		  	where en.id_encuesta = v_parametros.id_encuesta
                                     union
                                                select e.id_encuesta,
                                                       e.id_encuesta_padre
                                                from ssig.tencuesta e
                                                inner join encuesta_o s on s.id_encuesta = e.id_encuesta_padre
                                    )select en.id_encuesta,
                                    		en.id_encuesta_padre
                                     from encuesta_o en
                                     )loop

                    delete from ssig.tencuesta
            		where id_encuesta=v_record.id_encuesta;

            end loop;


            --Definicion de la respuesta
            v_resp = pxp.f_agrega_clave(v_resp,'mensaje','Encuesta eliminado(a)');
            v_resp = pxp.f_agrega_clave(v_resp,'id_encuesta',v_parametros.id_encuesta::varchar);

            --Devuelve la respuesta
            return v_resp;

		end;

	else

    	raise exception 'Transaccion inexistente: %',p_transaccion;

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

ALTER FUNCTION ssig.ft_encuesta_ime (p_administrador integer, p_id_usuario integer, p_tabla varchar, p_transaccion varchar)
  OWNER TO postgres;