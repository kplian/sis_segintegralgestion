CREATE OR REPLACE FUNCTION "ssig"."ft_categoria_ime" (	
				p_administrador integer, p_id_usuario integer, p_tabla character varying, p_transaccion character varying)
RETURNS character varying AS
$BODY$

/**************************************************************************
 SISTEMA:		Seguimiento integral de gestión
 FUNCION: 		ssig.ft_categoria_ime
 DESCRIPCION:   Funcion que gestiona las operaciones basicas (inserciones, modificaciones, eliminaciones de la tabla 'ssig.tcategoria'
 AUTOR: 		 (mguerra)
 FECHA:	        21-04-2020 08:42:04
 COMENTARIOS:	
***************************************************************************
 HISTORIAL DE MODIFICACIONES:
#ISSUE				FECHA				AUTOR				DESCRIPCION
 #0				21-04-2020 08:42:04								Funcion que gestiona las operaciones basicas (inserciones, modificaciones, eliminaciones de la tabla 'ssig.tcategoria'	
 #
 ***************************************************************************/

DECLARE

	v_nro_requerimiento    	integer;
	v_parametros           	record;
	v_id_requerimiento     	integer;
	v_resp		            varchar;
	v_nombre_funcion        text;
	v_mensaje_error         text;
	v_id_categoria	integer;
			    
BEGIN

    v_nombre_funcion = 'ssig.ft_categoria_ime';
    v_parametros = pxp.f_get_record(p_tabla);

	/*********************************    
 	#TRANSACCION:  'SSIG_CAT_INS'
 	#DESCRIPCION:	Insercion de registros
 	#AUTOR:		mguerra	
 	#FECHA:		21-04-2020 08:42:04
	***********************************/

	if(p_transaccion='SSIG_CAT_INS')then
					
        begin
        	--Sentencia de la insercion
        	insert into ssig.tcategoria(
			estado_reg,
			obs_dba,
			id_tipo_categoria,
			id_moneda,
			codigo,
			nombre,
			monto,
			id_destino,
			id_destino,
			monto_sp,
			monto_hotel,
			estado_reg,
			obs_dba,
			categoria,
			habilitar,
			observacion,
			id_cuestionario,
			id_usuario_reg,
			fecha_reg,
			id_usuario_ai,
			usuario_ai,
			id_usuario_reg,
			fecha_reg,
			id_usuario_ai,
			usuario_ai,
			id_usuario_mod,
			fecha_mod,
			id_usuario_mod,
			fecha_mod
          	) values(
			'activo',
			v_parametros.obs_dba,
			v_parametros.id_tipo_categoria,
			v_parametros.id_moneda,
			v_parametros.codigo,
			v_parametros.nombre,
			v_parametros.monto,
			v_parametros.id_destino,
			v_parametros.id_destino,
			v_parametros.monto_sp,
			v_parametros.monto_hotel,
			'activo',
			v_parametros.obs_dba,
			v_parametros.categoria,
			v_parametros.habilitar,
			v_parametros.observacion,
			v_parametros.id_cuestionario,
			p_id_usuario,
			now(),
			v_parametros._id_usuario_ai,
			v_parametros._nombre_usuario_ai,
			p_id_usuario,
			now(),
			v_parametros._id_usuario_ai,
			v_parametros._nombre_usuario_ai,
			null,
			null,
			null,
			null
							
			
			
			)RETURNING id_categoria into v_id_categoria;
			
			--Definicion de la respuesta
			v_resp = pxp.f_agrega_clave(v_resp,'mensaje','Categoria almacenado(a) con exito (id_categoria'||v_id_categoria||')'); 
            v_resp = pxp.f_agrega_clave(v_resp,'id_categoria',v_id_categoria::varchar);

            --Devuelve la respuesta
            return v_resp;

		end;

	/*********************************    
 	#TRANSACCION:  'SSIG_CAT_MOD'
 	#DESCRIPCION:	Modificacion de registros
 	#AUTOR:		mguerra	
 	#FECHA:		21-04-2020 08:42:04
	***********************************/

	elsif(p_transaccion='SSIG_CAT_MOD')then

		begin
			--Sentencia de la modificacion
			update ssig.tcategoria set
			obs_dba = v_parametros.obs_dba,
			id_tipo_categoria = v_parametros.id_tipo_categoria,
			id_moneda = v_parametros.id_moneda,
			codigo = v_parametros.codigo,
			nombre = v_parametros.nombre,
			monto = v_parametros.monto,
			id_destino = v_parametros.id_destino,
			id_destino = v_parametros.id_destino,
			monto_sp = v_parametros.monto_sp,
			monto_hotel = v_parametros.monto_hotel,
			obs_dba = v_parametros.obs_dba,
			categoria = v_parametros.categoria,
			habilitar = v_parametros.habilitar,
			observacion = v_parametros.observacion,
			id_cuestionario = v_parametros.id_cuestionario,
			id_usuario_mod = p_id_usuario,
			fecha_mod = now(),
			id_usuario_mod = p_id_usuario,
			fecha_mod = now(),
			id_usuario_ai = v_parametros._id_usuario_ai,
			usuario_ai = v_parametros._nombre_usuario_ai
			where id_categoria=v_parametros.id_categoria;
               
			--Definicion de la respuesta
            v_resp = pxp.f_agrega_clave(v_resp,'mensaje','Categoria modificado(a)'); 
            v_resp = pxp.f_agrega_clave(v_resp,'id_categoria',v_parametros.id_categoria::varchar);
               
            --Devuelve la respuesta
            return v_resp;
            
		end;

	/*********************************    
 	#TRANSACCION:  'SSIG_CAT_ELI'
 	#DESCRIPCION:	Eliminacion de registros
 	#AUTOR:		mguerra	
 	#FECHA:		21-04-2020 08:42:04
	***********************************/

	elsif(p_transaccion='SSIG_CAT_ELI')then

		begin
			--Sentencia de la eliminacion
			delete from ssig.tcategoria
            where id_categoria=v_parametros.id_categoria;
               
            --Definicion de la respuesta
            v_resp = pxp.f_agrega_clave(v_resp,'mensaje','Categoria eliminado(a)'); 
            v_resp = pxp.f_agrega_clave(v_resp,'id_categoria',v_parametros.id_categoria::varchar);
              
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
ALTER FUNCTION "ssig"."ft_categoria_ime"(integer, integer, character varying, character varying) OWNER TO postgres;
