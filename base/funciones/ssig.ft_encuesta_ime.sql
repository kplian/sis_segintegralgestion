--------------- SQL ---------------

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
        
           if v_parametros.id_encuesta_padre != 'id' and v_parametros.id_encuesta_padre != '' THEN
                   v_id_encuesta_padre  = v_parametros.id_encuesta_padre::integer;
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
			v_parametros.grupo,
			v_parametros.categoria,
			v_parametros.habilitado_categoria,
			v_parametros.peso_categoria,
			v_parametros.pregunta,
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
            (case
            	when  v_parametros.grupo = 'si' then
                'grupo'
                when  v_parametros.categoria = 'si' then 
                'categoria'
                when  v_parametros.pregunta = 'si' then 
                'pregunta'
            end )
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
        
         if v_parametros.id_encuesta_padre != 'id' and v_parametros.id_encuesta_padre != '' THEN
                   v_id_encuesta_padre  = v_parametros.id_encuesta_padre::integer;
           end if;
			--Sentencia de la modificacion
			update ssig.tencuesta set
			obs_dba = v_parametros.obs_dba,
			nro_order = v_parametros.nro_order,
			nombre = v_parametros.nombre,
			grupo = v_parametros.grupo,
			categoria = v_parametros.categoria,
			habilitado_categoria = v_parametros.habilitado_categoria,
			peso_categoria = v_parametros.peso_categoria,
			pregunta = v_parametros.pregunta,
			habilitado_pregunta = v_parametros.habilitado_pregunta,
			tipo_pregunta = v_parametros.tipo_pregunta,
		    id_encuesta_padre = v_id_encuesta_padre,
			id_usuario_mod = p_id_usuario,
			fecha_mod = now(),
			id_usuario_ai = v_parametros._id_usuario_ai,
			usuario_ai = v_parametros._nombre_usuario_ai,
            tipo = v_parametros.tipo,
            tipo_nombre = (case
                            when  v_parametros.grupo = 'si' then
                            'grupo'
                            when  v_parametros.categoria = 'si' then 
                            'categoria'
                            when  v_parametros.pregunta = 'si' then 
                            'pregunta'
                        end)
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
			--Sentencia de la eliminacion
			delete from ssig.tencuesta
            where id_encuesta=v_parametros.id_encuesta;
               
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