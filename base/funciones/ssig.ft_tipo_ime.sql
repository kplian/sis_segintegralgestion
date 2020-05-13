CREATE OR REPLACE FUNCTION "ssig"."ft_tipo_ime" (	
				p_administrador integer, p_id_usuario integer, p_tabla character varying, p_transaccion character varying)
RETURNS character varying AS
$BODY$

/**************************************************************************
 SISTEMA:		Seguimiento integral de gestión
 FUNCION: 		ssig.ft_tipo_ime
 DESCRIPCION:   Funcion que gestiona las operaciones basicas (inserciones, modificaciones, eliminaciones de la tabla 'ssig.ttipo'
 AUTOR: 		 (mguerra)
 FECHA:	        27-04-2020 11:27:10
 COMENTARIOS:	
***************************************************************************
 HISTORIAL DE MODIFICACIONES:
#ISSUE				FECHA				AUTOR				DESCRIPCION
 #0				27-04-2020 11:27:10								Funcion que gestiona las operaciones basicas (inserciones, modificaciones, eliminaciones de la tabla 'ssig.ttipo'	
 #
 ***************************************************************************/

DECLARE

	v_nro_requerimiento    	integer;
	v_parametros           	record;
	v_id_requerimiento     	integer;
	v_resp		            varchar;
	v_nombre_funcion        text;
	v_mensaje_error         text;
	v_id_tipo	integer;
			    
BEGIN

    v_nombre_funcion = 'ssig.ft_tipo_ime';
    v_parametros = pxp.f_get_record(p_tabla);

	/*********************************    
 	#TRANSACCION:  'SSIG_TPO_INS'
 	#DESCRIPCION:	Insercion de registros
 	#AUTOR:		mguerra	
 	#FECHA:		27-04-2020 11:27:10
	***********************************/

	if(p_transaccion='SSIG_TPO_INS')then
					
        begin
        	--Sentencia de la insercion
        	insert into ssig.ttipo(
			estado_reg,
			obs_dba,
			tipo,
			observacion,
			id_usuario_reg,
			fecha_reg,
			id_usuario_ai,
			usuario_ai,
			id_usuario_mod,
			fecha_mod
          	) values(
			'activo',
			v_parametros.obs_dba,
			v_parametros.tipo,
			v_parametros.observacion,
			p_id_usuario,
			now(),
			v_parametros._id_usuario_ai,
			v_parametros._nombre_usuario_ai,
			null,
			null
							
			
			
			)RETURNING id_tipo into v_id_tipo;
			
			--Definicion de la respuesta
			v_resp = pxp.f_agrega_clave(v_resp,'mensaje','Tipo almacenado(a) con exito (id_tipo'||v_id_tipo||')'); 
            v_resp = pxp.f_agrega_clave(v_resp,'id_tipo',v_id_tipo::varchar);

            --Devuelve la respuesta
            return v_resp;

		end;

	/*********************************    
 	#TRANSACCION:  'SSIG_TPO_MOD'
 	#DESCRIPCION:	Modificacion de registros
 	#AUTOR:		mguerra	
 	#FECHA:		27-04-2020 11:27:10
	***********************************/

	elsif(p_transaccion='SSIG_TPO_MOD')then

		begin
			--Sentencia de la modificacion
			update ssig.ttipo set
			obs_dba = v_parametros.obs_dba,
			tipo = v_parametros.tipo,
			observacion = v_parametros.observacion,
			id_usuario_mod = p_id_usuario,
			fecha_mod = now(),
			id_usuario_ai = v_parametros._id_usuario_ai,
			usuario_ai = v_parametros._nombre_usuario_ai
			where id_tipo=v_parametros.id_tipo;
               
			--Definicion de la respuesta
            v_resp = pxp.f_agrega_clave(v_resp,'mensaje','Tipo modificado(a)'); 
            v_resp = pxp.f_agrega_clave(v_resp,'id_tipo',v_parametros.id_tipo::varchar);
               
            --Devuelve la respuesta
            return v_resp;
            
		end;

	/*********************************    
 	#TRANSACCION:  'SSIG_TPO_ELI'
 	#DESCRIPCION:	Eliminacion de registros
 	#AUTOR:		mguerra	
 	#FECHA:		27-04-2020 11:27:10
	***********************************/

	elsif(p_transaccion='SSIG_TPO_ELI')then

		begin
			--Sentencia de la eliminacion
			delete from ssig.ttipo
            where id_tipo=v_parametros.id_tipo;
               
            --Definicion de la respuesta
            v_resp = pxp.f_agrega_clave(v_resp,'mensaje','Tipo eliminado(a)'); 
            v_resp = pxp.f_agrega_clave(v_resp,'id_tipo',v_parametros.id_tipo::varchar);
              
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
ALTER FUNCTION "ssig"."ft_tipo_ime"(integer, integer, character varying, character varying) OWNER TO postgres;
