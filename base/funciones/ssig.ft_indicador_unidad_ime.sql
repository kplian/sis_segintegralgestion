--------------- SQL ---------------

CREATE OR REPLACE FUNCTION ssig.ft_indicador_unidad_ime (
  p_administrador integer,
  p_id_usuario integer,
  p_tabla varchar,
  p_transaccion varchar
)
RETURNS varchar AS
$body$
/**************************************************************************
 SISTEMA:		Seguimiento integral de gestión
 FUNCION: 		ssig.ft_indicador_unidad_ime
 DESCRIPCION:   Funcion que gestiona las operaciones basicas (inserciones, modificaciones, eliminaciones de la tabla 'ssig.tindicador_unidad'
 AUTOR: 		 (admin)
 FECHA:	        21-11-2016 09:55:49
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
	v_id_indicador_unidad	integer;
			    
BEGIN

    v_nombre_funcion = 'ssig.ft_indicador_unidad_ime';
    v_parametros = pxp.f_get_record(p_tabla);

	/*********************************    
 	#TRANSACCION:  'SSIG_INUN_INS'
 	#DESCRIPCION:	Insercion de registros
 	#AUTOR:		admin	
 	#FECHA:		21-11-2016 09:55:49
	***********************************/

	if(p_transaccion='SSIG_INUN_INS')then
					
        begin
        	--Sentencia de la insercion
        	insert into ssig.tindicador_unidad(
			estado_reg,
			tipo,
			unidad,
			usuario_ai,
			fecha_reg,
			id_usuario_reg,
			id_usuario_ai,
			id_usuario_mod,
			fecha_mod
          	) values(
			'activo',
			v_parametros.tipo,
			v_parametros.unidad,
			v_parametros._nombre_usuario_ai,
			now(),
			p_id_usuario,
			v_parametros._id_usuario_ai,
			null,
			null
							
			
			
			)RETURNING id_indicador_unidad into v_id_indicador_unidad;
			
			--Definicion de la respuesta
			v_resp = pxp.f_agrega_clave(v_resp,'mensaje','Indicador unidad almacenado(a) con exito (id_indicador_unidad'||v_id_indicador_unidad||')'); 
            v_resp = pxp.f_agrega_clave(v_resp,'id_indicador_unidad',v_id_indicador_unidad::varchar);

            --Devuelve la respuesta
            return v_resp;

		end;

	/*********************************    
 	#TRANSACCION:  'SSIG_INUN_MOD'
 	#DESCRIPCION:	Modificacion de registros
 	#AUTOR:		admin	
 	#FECHA:		21-11-2016 09:55:49
	***********************************/

	elsif(p_transaccion='SSIG_INUN_MOD')then

		begin
			--Sentencia de la modificacion
			update ssig.tindicador_unidad set
			tipo = v_parametros.tipo,
			unidad = v_parametros.unidad,
			id_usuario_mod = p_id_usuario,
			fecha_mod = now(),
			id_usuario_ai = v_parametros._id_usuario_ai,
			usuario_ai = v_parametros._nombre_usuario_ai
			where id_indicador_unidad=v_parametros.id_indicador_unidad;
               
			--Definicion de la respuesta
            v_resp = pxp.f_agrega_clave(v_resp,'mensaje','Indicador unidad modificado(a)'); 
            v_resp = pxp.f_agrega_clave(v_resp,'id_indicador_unidad',v_parametros.id_indicador_unidad::varchar);
               
            --Devuelve la respuesta
            return v_resp;
            
		end;

	/*********************************    
 	#TRANSACCION:  'SSIG_INUN_ELI'
 	#DESCRIPCION:	Eliminacion de registros
 	#AUTOR:		admin	
 	#FECHA:		21-11-2016 09:55:49
	***********************************/

	elsif(p_transaccion='SSIG_INUN_ELI')then

		begin
			--Sentencia de la eliminacion
			delete from ssig.tindicador_unidad
            where id_indicador_unidad=v_parametros.id_indicador_unidad;
               
            --Definicion de la respuesta
            v_resp = pxp.f_agrega_clave(v_resp,'mensaje','Indicador unidad eliminado(a)'); 
            v_resp = pxp.f_agrega_clave(v_resp,'id_indicador_unidad',v_parametros.id_indicador_unidad::varchar);
              
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