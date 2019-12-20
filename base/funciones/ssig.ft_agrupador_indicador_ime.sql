CREATE OR REPLACE FUNCTION ssig.ft_agrupador_indicador_ime (
  p_administrador integer,
  p_id_usuario integer,
  p_tabla varchar,
  p_transaccion varchar
)
RETURNS varchar AS
$body$
/**************************************************************************
 SISTEMA:		Seguimiento integral de gestión
 FUNCION: 		ssig.ft_agrupador_indicador_ime
 DESCRIPCION:   Funcion que gestiona las operaciones basicas (inserciones, modificaciones, eliminaciones de la tabla 'ssig.tagrupador_indicador'
 AUTOR: 		 (admin)
 FECHA:	        16-02-2017 01:23:13
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
	v_id_agrupador_indicador	integer;
    v_id_agrupador_indicador_padre	integer;
			    
BEGIN

    v_nombre_funcion = 'ssig.ft_agrupador_indicador_ime';
    v_parametros = pxp.f_get_record(p_tabla);

	/*********************************    
 	#TRANSACCION:  'SSIG_AGIN_INS'
 	#DESCRIPCION:	Insercion de registros
 	#AUTOR:		admin	
 	#FECHA:		16-02-2017 01:23:13
	***********************************/

	if(p_transaccion='SSIG_AGIN_INS')then
					
        begin
        	
        	--Sentencia de la insercion
        	insert into ssig.tagrupador_indicador(
			id_agrupador,
            --id_agrupador_indicador_padre,
			id_indicador,
			--id_funcionario_ingreso,
			--id_funcionario_evaluacion,
			peso,
			estado_reg,
			id_usuario_ai,
			fecha_reg,
			usuario_ai,
			id_usuario_reg,
			id_usuario_mod,
			fecha_mod,
            orden_logico
          	) values(
			v_parametros.id_agrupador,
            --v_parametros.id_agrupador_indicador_padre :: INTEGER,
			v_parametros.id_indicador,
			--v_parametros.id_funcionario_ingreso,
			--v_parametros.id_funcionario_evaluacion,
			v_parametros.peso,
			'activo',
			v_parametros._id_usuario_ai,
			now(),
			v_parametros._nombre_usuario_ai,
			p_id_usuario,
			null,
			null,
            v_parametros.orden_logico
							
			
			
			)RETURNING id_agrupador_indicador into v_id_agrupador_indicador;
			
			--Definicion de la respuesta
			v_resp = pxp.f_agrega_clave(v_resp,'mensaje','agrupador_indicador almacenado(a) con exito (id_agrupador_indicador'||v_id_agrupador_indicador||')'); 
            v_resp = pxp.f_agrega_clave(v_resp,'id_agrupador_indicador',v_id_agrupador_indicador::varchar);

            --Devuelve la respuesta
            
            return v_resp;

		end;

	/*********************************    
 	#TRANSACCION:  'SSIG_AGIN_MOD'
 	#DESCRIPCION:	Modificacion de registros
 	#AUTOR:		admin	
 	#FECHA:		16-02-2017 01:23:13
	***********************************/

	elsif(p_transaccion='SSIG_AGIN_MOD')then

		begin
			--Sentencia de la modificacion                
            
			update ssig.tagrupador_indicador set            
			id_agrupador = v_parametros.id_agrupador,            
--            id_agrupador_indicador_padre = v_parametros.id_agrupador_indicador_padre,            
			id_indicador = v_parametros.id_indicador,
			--id_funcionario_ingreso = v_parametros.id_funcionario_ingreso,
			--id_funcionario_evaluacion = v_parametros.id_funcionario_evaluacion,
			peso = v_parametros.peso,
			id_usuario_mod = p_id_usuario,
			fecha_mod = now(),
			id_usuario_ai = v_parametros._id_usuario_ai,
			usuario_ai = v_parametros._nombre_usuario_ai,
            orden_logico = v_parametros.orden_logico
			where id_agrupador_indicador=v_parametros.id_agrupador_indicador;
               
			--Definicion de la respuesta
            v_resp = pxp.f_agrega_clave(v_resp,'mensaje','agrupador_indicador modificado(a)'); 
            v_resp = pxp.f_agrega_clave(v_resp,'id_agrupador_indicador',v_parametros.id_agrupador_indicador::varchar);
               
            --Devuelve la respuesta
            return v_resp;
            
		end;

	/*********************************    
 	#TRANSACCION:  'SSIG_AGIN_ELI'
 	#DESCRIPCION:	Eliminacion de registros
 	#AUTOR:		admin	
 	#FECHA:		16-02-2017 01:23:13
	***********************************/

	elsif(p_transaccion='SSIG_AGIN_ELI')then

		begin
			--Sentencia de la eliminacion
			delete from ssig.tagrupador_indicador
            where id_agrupador_indicador=v_parametros.id_agrupador_indicador;
               
            --Definicion de la respuesta
            v_resp = pxp.f_agrega_clave(v_resp,'mensaje','agrupador_indicador eliminado(a)'); 
            v_resp = pxp.f_agrega_clave(v_resp,'id_agrupador_indicador',v_parametros.id_agrupador_indicador::varchar);
              
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
