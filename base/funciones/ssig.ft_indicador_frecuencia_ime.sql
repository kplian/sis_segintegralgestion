--------------- SQL ---------------

CREATE OR REPLACE FUNCTION ssig.ft_indicador_frecuencia_ime (
  p_administrador integer,
  p_id_usuario integer,
  p_tabla varchar,
  p_transaccion varchar
)
RETURNS varchar AS
$body$
/**************************************************************************
 SISTEMA:		Seguimiento integral de gestión
 FUNCION: 		ssig.ft_indicador_frecuencia_ime
 DESCRIPCION:   Funcion que gestiona las operaciones basicas (inserciones, modificaciones, eliminaciones de la tabla 'ssig.tindicador_frecuencia'
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

	v_nro_requerimiento    	integer;
	v_parametros           	record;
	v_id_requerimiento     	integer;
	v_resp		            varchar;
	v_nombre_funcion        text;
	v_mensaje_error         text;
	v_id_indicador_frecuencia	integer;
			    
BEGIN

    v_nombre_funcion = 'ssig.ft_indicador_frecuencia_ime';
    v_parametros = pxp.f_get_record(p_tabla);

	/*********************************    
 	#TRANSACCION:  'SSIG_INFR_INS'
 	#DESCRIPCION:	Insercion de registros
 	#AUTOR:		admin	
 	#FECHA:		21-11-2016 12:35:24
	***********************************/

	if(p_transaccion='SSIG_INFR_INS')then
					
        begin
        	--Sentencia de la insercion
        	insert into ssig.tindicador_frecuencia(
			valor,
			hito,
			estado_reg,
			frecuencia,
			id_usuario_ai,
			id_usuario_reg,
			usuario_ai,
			fecha_reg,
			id_usuario_mod,
			fecha_mod
          	) values(
			v_parametros.valor,
			v_parametros.hito,
			'activo',
			v_parametros.frecuencia,
			v_parametros._id_usuario_ai,
			p_id_usuario,
			v_parametros._nombre_usuario_ai,
			now(),
			null,
			null
							
			
			
			)RETURNING id_indicador_frecuencia into v_id_indicador_frecuencia;
			
			--Definicion de la respuesta
			v_resp = pxp.f_agrega_clave(v_resp,'mensaje','Indicador frecuencia almacenado(a) con exito (id_indicador_frecuencia'||v_id_indicador_frecuencia||')'); 
            v_resp = pxp.f_agrega_clave(v_resp,'id_indicador_frecuencia',v_id_indicador_frecuencia::varchar);

            --Devuelve la respuesta
            return v_resp;

		end;

	/*********************************    
 	#TRANSACCION:  'SSIG_INFR_MOD'
 	#DESCRIPCION:	Modificacion de registros
 	#AUTOR:		admin	
 	#FECHA:		21-11-2016 12:35:24
	***********************************/

	elsif(p_transaccion='SSIG_INFR_MOD')then

		begin
			--Sentencia de la modificacion
			update ssig.tindicador_frecuencia set
			valor = v_parametros.valor,
			hito = v_parametros.hito,
			frecuencia = v_parametros.frecuencia,
			id_usuario_mod = p_id_usuario,
			fecha_mod = now(),
			id_usuario_ai = v_parametros._id_usuario_ai,
			usuario_ai = v_parametros._nombre_usuario_ai
			where id_indicador_frecuencia=v_parametros.id_indicador_frecuencia;
               
			--Definicion de la respuesta
            v_resp = pxp.f_agrega_clave(v_resp,'mensaje','Indicador frecuencia modificado(a)'); 
            v_resp = pxp.f_agrega_clave(v_resp,'id_indicador_frecuencia',v_parametros.id_indicador_frecuencia::varchar);
               
            --Devuelve la respuesta
            return v_resp;
            
		end;

	/*********************************    
 	#TRANSACCION:  'SSIG_INFR_ELI'
 	#DESCRIPCION:	Eliminacion de registros
 	#AUTOR:		admin	
 	#FECHA:		21-11-2016 12:35:24
	***********************************/

	elsif(p_transaccion='SSIG_INFR_ELI')then

		begin
			--Sentencia de la eliminacion
			delete from ssig.tindicador_frecuencia
            where id_indicador_frecuencia=v_parametros.id_indicador_frecuencia;
               
            --Definicion de la respuesta
            v_resp = pxp.f_agrega_clave(v_resp,'mensaje','Indicador frecuencia eliminado(a)'); 
            v_resp = pxp.f_agrega_clave(v_resp,'id_indicador_frecuencia',v_parametros.id_indicador_frecuencia::varchar);
              
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
COST 100;