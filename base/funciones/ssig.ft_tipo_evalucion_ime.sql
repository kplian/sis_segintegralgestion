CREATE OR REPLACE FUNCTION "ssig"."ft_tipo_evalucion_ime" (	
				p_administrador integer, p_id_usuario integer, p_tabla character varying, p_transaccion character varying)
RETURNS character varying AS
$BODY$

/**************************************************************************
 SISTEMA:		Seguimiento integral de gestión
 FUNCION: 		ssig.ft_tipo_evalucion_ime
 DESCRIPCION:   Funcion que gestiona las operaciones basicas (inserciones, modificaciones, eliminaciones de la tabla 'ssig.ttipo_evalucion'
 AUTOR: 		 (admin.miguel)
 FECHA:	        27-04-2020 14:34:48
 COMENTARIOS:	
***************************************************************************
 HISTORIAL DE MODIFICACIONES:
#ISSUE				FECHA				AUTOR				DESCRIPCION
 #0				27-04-2020 14:34:48								Funcion que gestiona las operaciones basicas (inserciones, modificaciones, eliminaciones de la tabla 'ssig.ttipo_evalucion'	
 #
 ***************************************************************************/

DECLARE

	v_nro_requerimiento    	integer;
	v_parametros           	record;
	v_id_requerimiento     	integer;
	v_resp		            varchar;
	v_nombre_funcion        text;
	v_mensaje_error         text;
	v_id_tipo_evalucion	integer;
			    
BEGIN

    v_nombre_funcion = 'ssig.ft_tipo_evalucion_ime';
    v_parametros = pxp.f_get_record(p_tabla);

	/*********************************    
 	#TRANSACCION:  'SSIG_TEN_INS'
 	#DESCRIPCION:	Insercion de registros
 	#AUTOR:		admin.miguel	
 	#FECHA:		27-04-2020 14:34:48
	***********************************/

	if(p_transaccion='SSIG_TEN_INS')then
					
        begin
        	--Sentencia de la insercion
        	insert into ssig.ttipo_evalucion(
			estado_reg,
			obs_dba,
			codigo,
			nombre,
			id_nivel_organizacional,
			id_usuario_reg,
			fecha_reg,
			id_usuario_ai,
			usuario_ai,
			id_usuario_mod,
			fecha_mod
          	) values(
			'activo',
			v_parametros.obs_dba,
			v_parametros.codigo,
			v_parametros.nombre,
			v_parametros.id_nivel_organizacional,
			p_id_usuario,
			now(),
			v_parametros._id_usuario_ai,
			v_parametros._nombre_usuario_ai,
			null,
			null
							
			
			
			)RETURNING id_tipo_evalucion into v_id_tipo_evalucion;
			
			--Definicion de la respuesta
			v_resp = pxp.f_agrega_clave(v_resp,'mensaje','Tipo Evalucion almacenado(a) con exito (id_tipo_evalucion'||v_id_tipo_evalucion||')'); 
            v_resp = pxp.f_agrega_clave(v_resp,'id_tipo_evalucion',v_id_tipo_evalucion::varchar);

            --Devuelve la respuesta
            return v_resp;

		end;

	/*********************************    
 	#TRANSACCION:  'SSIG_TEN_MOD'
 	#DESCRIPCION:	Modificacion de registros
 	#AUTOR:		admin.miguel	
 	#FECHA:		27-04-2020 14:34:48
	***********************************/

	elsif(p_transaccion='SSIG_TEN_MOD')then

		begin
			--Sentencia de la modificacion
			update ssig.ttipo_evalucion set
			obs_dba = v_parametros.obs_dba,
			codigo = v_parametros.codigo,
			nombre = v_parametros.nombre,
			id_nivel_organizacional = v_parametros.id_nivel_organizacional,
			id_usuario_mod = p_id_usuario,
			fecha_mod = now(),
			id_usuario_ai = v_parametros._id_usuario_ai,
			usuario_ai = v_parametros._nombre_usuario_ai
			where id_tipo_evalucion=v_parametros.id_tipo_evalucion;
               
			--Definicion de la respuesta
            v_resp = pxp.f_agrega_clave(v_resp,'mensaje','Tipo Evalucion modificado(a)'); 
            v_resp = pxp.f_agrega_clave(v_resp,'id_tipo_evalucion',v_parametros.id_tipo_evalucion::varchar);
               
            --Devuelve la respuesta
            return v_resp;
            
		end;

	/*********************************    
 	#TRANSACCION:  'SSIG_TEN_ELI'
 	#DESCRIPCION:	Eliminacion de registros
 	#AUTOR:		admin.miguel	
 	#FECHA:		27-04-2020 14:34:48
	***********************************/

	elsif(p_transaccion='SSIG_TEN_ELI')then

		begin
			--Sentencia de la eliminacion
			delete from ssig.ttipo_evalucion
            where id_tipo_evalucion=v_parametros.id_tipo_evalucion;
               
            --Definicion de la respuesta
            v_resp = pxp.f_agrega_clave(v_resp,'mensaje','Tipo Evalucion eliminado(a)'); 
            v_resp = pxp.f_agrega_clave(v_resp,'id_tipo_evalucion',v_parametros.id_tipo_evalucion::varchar);
              
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
$BODY$
LANGUAGE 'plpgsql' VOLATILE
COST 100;
ALTER FUNCTION "ssig"."ft_tipo_evalucion_ime"(integer, integer, character varying, character varying) OWNER TO postgres;
