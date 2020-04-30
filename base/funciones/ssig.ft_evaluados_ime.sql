--------------- SQL ---------------

CREATE OR REPLACE FUNCTION ssig.ft_evaluados_ime (
  p_administrador integer,
  p_id_usuario integer,
  p_tabla varchar,
  p_transaccion varchar
)
RETURNS varchar AS
$body$
/**************************************************************************
 SISTEMA:		Seguimiento integral de gestión
 FUNCION: 		ssig.ft_evaluados_ime
 DESCRIPCION:   Funcion que gestiona las operaciones basicas (inserciones, modificaciones, eliminaciones de la tabla 'ssig.tevaluados'
 AUTOR: 		 (admin.miguel)
 FECHA:	        28-04-2020 01:32:33
 COMENTARIOS:	
***************************************************************************
 HISTORIAL DE MODIFICACIONES:
#ISSUE				FECHA				AUTOR				DESCRIPCION
 #0				28-04-2020 01:32:33								Funcion que gestiona las operaciones basicas (inserciones, modificaciones, eliminaciones de la tabla 'ssig.tevaluados'	
 #
 ***************************************************************************/

DECLARE

	v_nro_requerimiento    	integer;
	v_parametros           	record;
	v_id_requerimiento     	integer;
	v_resp		            varchar;
	v_nombre_funcion        text;
	v_mensaje_error         text;
	v_id_evaluados	integer;
			    
BEGIN

    v_nombre_funcion = 'ssig.ft_evaluados_ime';
    v_parametros = pxp.f_get_record(p_tabla);

	/*********************************    
 	#TRANSACCION:  'SSIG_EVS_INS'
 	#DESCRIPCION:	Insercion de registros
 	#AUTOR:		admin.miguel	
 	#FECHA:		28-04-2020 01:32:33
	***********************************/

	if(p_transaccion='SSIG_EVS_INS')then
					
        begin
        	--Sentencia de la insercion
        	insert into ssig.tevaluados(
			estado_reg,
			obs_dba,
			id_cuestionario_funcionario,
			id_funcionario,
			evaluar,
			id_usuario_reg,
			fecha_reg,
			id_usuario_ai,
			usuario_ai,
			id_usuario_mod,
			fecha_mod
          	) values(
			'activo',
			v_parametros.obs_dba,
			v_parametros.id_cuestionario_funcionario,
			v_parametros.id_funcionario,
			v_parametros.evaluar,
			p_id_usuario,
			now(),
			v_parametros._id_usuario_ai,
			v_parametros._nombre_usuario_ai,
			null,
			null
							
			
			
			)RETURNING id_evaluados into v_id_evaluados;
			
			--Definicion de la respuesta
			v_resp = pxp.f_agrega_clave(v_resp,'mensaje','Evaluados almacenado(a) con exito (id_evaluados'||v_id_evaluados||')'); 
            v_resp = pxp.f_agrega_clave(v_resp,'id_evaluados',v_id_evaluados::varchar);

            --Devuelve la respuesta
            return v_resp;

		end;

	/*********************************    
 	#TRANSACCION:  'SSIG_EVS_MOD'
 	#DESCRIPCION:	Modificacion de registros
 	#AUTOR:		admin.miguel	
 	#FECHA:		28-04-2020 01:32:33
	***********************************/

	elsif(p_transaccion='SSIG_EVS_MOD')then

		begin
			--Sentencia de la modificacion
			update ssig.tevaluados set
			obs_dba = v_parametros.obs_dba,
			id_cuestionario_funcionario = v_parametros.id_cuestionario_funcionario,
			id_funcionario = v_parametros.id_funcionario,
			evaluar = v_parametros.evaluar,
			id_usuario_mod = p_id_usuario,
			fecha_mod = now(),
			id_usuario_ai = v_parametros._id_usuario_ai,
			usuario_ai = v_parametros._nombre_usuario_ai
			where id_evaluados=v_parametros.id_evaluados;
               
			--Definicion de la respuesta
            v_resp = pxp.f_agrega_clave(v_resp,'mensaje','Evaluados modificado(a)'); 
            v_resp = pxp.f_agrega_clave(v_resp,'id_evaluados',v_parametros.id_evaluados::varchar);
               
            --Devuelve la respuesta
            return v_resp;
            
		end;

	/*********************************    
 	#TRANSACCION:  'SSIG_EVS_ELI'
 	#DESCRIPCION:	Eliminacion de registros
 	#AUTOR:		admin.miguel	
 	#FECHA:		28-04-2020 01:32:33
	***********************************/

	elsif(p_transaccion='SSIG_EVS_ELI')then

		begin
			--Sentencia de la eliminacion
			delete from ssig.tevaluados
            where id_evaluados=v_parametros.id_evaluados;
               
            --Definicion de la respuesta
            v_resp = pxp.f_agrega_clave(v_resp,'mensaje','Evaluados eliminado(a)'); 
            v_resp = pxp.f_agrega_clave(v_resp,'id_evaluados',v_parametros.id_evaluados::varchar);
              
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