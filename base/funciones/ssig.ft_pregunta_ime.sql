CREATE OR REPLACE FUNCTION "ssig"."ft_pregunta_ime" (	
				p_administrador integer, p_id_usuario integer, p_tabla character varying, p_transaccion character varying)
RETURNS character varying AS
$BODY$

/**************************************************************************
 SISTEMA:		Seguimiento integral de gestión
 FUNCION: 		ssig.ft_pregunta_ime
 DESCRIPCION:   Funcion que gestiona las operaciones basicas (inserciones, modificaciones, eliminaciones de la tabla 'ssig.tpregunta'
 AUTOR: 		 (mguerra)
 FECHA:	        21-04-2020 08:17:42
 COMENTARIOS:	
***************************************************************************
 HISTORIAL DE MODIFICACIONES:
#ISSUE				FECHA				AUTOR				DESCRIPCION
 #0				21-04-2020 08:17:42								Funcion que gestiona las operaciones basicas (inserciones, modificaciones, eliminaciones de la tabla 'ssig.tpregunta'	
 #
 ***************************************************************************/

DECLARE

	v_nro_requerimiento    	integer;
	v_parametros           	record;
	v_id_requerimiento     	integer;
	v_resp		            varchar;
	v_nombre_funcion        text;
	v_mensaje_error         text;
	v_id_pregunta	integer;
			    
BEGIN

    v_nombre_funcion = 'ssig.ft_pregunta_ime';
    v_parametros = pxp.f_get_record(p_tabla);

	/*********************************    
 	#TRANSACCION:  'SSIG_PRE_INS'
 	#DESCRIPCION:	Insercion de registros
 	#AUTOR:		mguerra	
 	#FECHA:		21-04-2020 08:17:42
	***********************************/

	if(p_transaccion='SSIG_PRE_INS')then
					
        begin
        	--Sentencia de la insercion
        	insert into ssig.tpregunta(
			estado_reg,
			obs_dba,
			pregunta,
			habilitar,
			tipo,
			resultado,
			observacion,
			id_categoria,
			id_usuario_reg,
			fecha_reg,
			id_usuario_ai,
			usuario_ai,
			id_usuario_mod,
			fecha_mod
          	) values(
			'activo',
			v_parametros.obs_dba,
			v_parametros.pregunta,
			v_parametros.habilitar,
			v_parametros.tipo,
			v_parametros.resultado,
			v_parametros.observacion,
			v_parametros.id_categoria,
			p_id_usuario,
			now(),
			v_parametros._id_usuario_ai,
			v_parametros._nombre_usuario_ai,
			null,
			null
							
			
			
			)RETURNING id_pregunta into v_id_pregunta;
			
			--Definicion de la respuesta
			v_resp = pxp.f_agrega_clave(v_resp,'mensaje','Pregunta almacenado(a) con exito (id_pregunta'||v_id_pregunta||')'); 
            v_resp = pxp.f_agrega_clave(v_resp,'id_pregunta',v_id_pregunta::varchar);

            --Devuelve la respuesta
            return v_resp;

		end;

	/*********************************    
 	#TRANSACCION:  'SSIG_PRE_MOD'
 	#DESCRIPCION:	Modificacion de registros
 	#AUTOR:		mguerra	
 	#FECHA:		21-04-2020 08:17:42
	***********************************/

	elsif(p_transaccion='SSIG_PRE_MOD')then

		begin
			--Sentencia de la modificacion
			update ssig.tpregunta set
			obs_dba = v_parametros.obs_dba,
			pregunta = v_parametros.pregunta,
			habilitar = v_parametros.habilitar,
			tipo = v_parametros.tipo,
			resultado = v_parametros.resultado,
			observacion = v_parametros.observacion,
			id_categoria = v_parametros.id_categoria,
			id_usuario_mod = p_id_usuario,
			fecha_mod = now(),
			id_usuario_ai = v_parametros._id_usuario_ai,
			usuario_ai = v_parametros._nombre_usuario_ai
			where id_pregunta=v_parametros.id_pregunta;
               
			--Definicion de la respuesta
            v_resp = pxp.f_agrega_clave(v_resp,'mensaje','Pregunta modificado(a)'); 
            v_resp = pxp.f_agrega_clave(v_resp,'id_pregunta',v_parametros.id_pregunta::varchar);
               
            --Devuelve la respuesta
            return v_resp;
            
		end;

	/*********************************    
 	#TRANSACCION:  'SSIG_PRE_ELI'
 	#DESCRIPCION:	Eliminacion de registros
 	#AUTOR:		mguerra	
 	#FECHA:		21-04-2020 08:17:42
	***********************************/

	elsif(p_transaccion='SSIG_PRE_ELI')then

		begin
			--Sentencia de la eliminacion
			delete from ssig.tpregunta
            where id_pregunta=v_parametros.id_pregunta;
               
            --Definicion de la respuesta
            v_resp = pxp.f_agrega_clave(v_resp,'mensaje','Pregunta eliminado(a)'); 
            v_resp = pxp.f_agrega_clave(v_resp,'id_pregunta',v_parametros.id_pregunta::varchar);
              
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
ALTER FUNCTION "ssig"."ft_pregunta_ime"(integer, integer, character varying, character varying) OWNER TO postgres;
