--------------- SQL ---------------

CREATE OR REPLACE FUNCTION ssig.ft_cuestionario_funcionario_ime (
  p_administrador integer,
  p_id_usuario integer,
  p_tabla varchar,
  p_transaccion varchar
)
RETURNS varchar AS
$body$
/**************************************************************************
 SISTEMA:		Seguimiento integral de gestión
 FUNCION: 		ssig.ft_cuestionario_funcionario_ime
 DESCRIPCION:   Funcion que gestiona las operaciones basicas (inserciones, modificaciones, eliminaciones de la tabla 'ssig.tcuestionario_funcionario'
 AUTOR: 		 (mguerra)
 FECHA:	        22-04-2020 06:47:37
 COMENTARIOS:	
***************************************************************************
 HISTORIAL DE MODIFICACIONES:
#ISSUE				FECHA				AUTOR				DESCRIPCION
 #0				22-04-2020 06:47:37								Funcion que gestiona las operaciones basicas (inserciones, modificaciones, eliminaciones de la tabla 'ssig.tcuestionario_funcionario'	
 #
 ***************************************************************************/

DECLARE

	v_nro_requerimiento    	integer;
	v_parametros           	record;
	v_id_requerimiento     	integer;
	v_resp		            varchar;
	v_nombre_funcion        text;
	v_mensaje_error         text;
	v_id_cuestionario_funcionario	integer;
			    
BEGIN

    v_nombre_funcion = 'ssig.ft_cuestionario_funcionario_ime';
    v_parametros = pxp.f_get_record(p_tabla);

	/*********************************    
 	#TRANSACCION:  'SSIG_CUEFUN_INS'
 	#DESCRIPCION:	Insercion de registros
 	#AUTOR:		mguerra	
 	#FECHA:		22-04-2020 06:47:37
	***********************************/

	if(p_transaccion='SSIG_CUEFUN_INS')then
					
        begin
        	--Sentencia de la insercion
        	insert into ssig.tcuestionario_funcionario(
			estado_reg,
			obs_dba,
			id_cuestionario,
			id_funcionario,
			id_usuario_reg,
			fecha_reg,
			id_usuario_ai,
			usuario_ai,
			id_usuario_mod,
			fecha_mod
          	) values(
			'activo',
			v_parametros.obs_dba,
			v_parametros.id_cuestionario,
			v_parametros.id_funcionario,
			p_id_usuario,
			now(),
			v_parametros._id_usuario_ai,
			v_parametros._nombre_usuario_ai,
			null,
			null
							
			
			
			)RETURNING id_cuestionario_funcionario into v_id_cuestionario_funcionario;
			
			--Definicion de la respuesta
			v_resp = pxp.f_agrega_clave(v_resp,'mensaje','Cuestionario Funcionario almacenado(a) con exito (id_cuestionario_funcionario'||v_id_cuestionario_funcionario||')'); 
            v_resp = pxp.f_agrega_clave(v_resp,'id_cuestionario_funcionario',v_id_cuestionario_funcionario::varchar);

            --Devuelve la respuesta
            return v_resp;

		end;

	/*********************************    
 	#TRANSACCION:  'SSIG_CUEFUN_MOD'
 	#DESCRIPCION:	Modificacion de registros
 	#AUTOR:		mguerra	
 	#FECHA:		22-04-2020 06:47:37
	***********************************/

	elsif(p_transaccion='SSIG_CUEFUN_MOD')then

		begin
			--Sentencia de la modificacion
			update ssig.tcuestionario_funcionario set
			obs_dba = v_parametros.obs_dba,
			id_cuestionario = v_parametros.id_cuestionario,
			id_funcionario = v_parametros.id_funcionario,
			id_usuario_mod = p_id_usuario,
			fecha_mod = now(),
			id_usuario_ai = v_parametros._id_usuario_ai,
			usuario_ai = v_parametros._nombre_usuario_ai
			where id_cuestionario_funcionario=v_parametros.id_cuestionario_funcionario;
               
			--Definicion de la respuesta
            v_resp = pxp.f_agrega_clave(v_resp,'mensaje','Cuestionario Funcionario modificado(a)'); 
            v_resp = pxp.f_agrega_clave(v_resp,'id_cuestionario_funcionario',v_parametros.id_cuestionario_funcionario::varchar);
               
            --Devuelve la respuesta
            return v_resp;
            
		end;

	/*********************************    
 	#TRANSACCION:  'SSIG_CUEFUN_ELI'
 	#DESCRIPCION:	Eliminacion de registros
 	#AUTOR:		mguerra	
 	#FECHA:		22-04-2020 06:47:37
	***********************************/

	elsif(p_transaccion='SSIG_CUEFUN_ELI')then

		begin
			--Sentencia de la eliminacion
			delete from ssig.tcuestionario_funcionario
            where id_cuestionario_funcionario=v_parametros.id_cuestionario_funcionario;
               
            --Definicion de la respuesta
            v_resp = pxp.f_agrega_clave(v_resp,'mensaje','Cuestionario Funcionario eliminado(a)'); 
            v_resp = pxp.f_agrega_clave(v_resp,'id_cuestionario_funcionario',v_parametros.id_cuestionario_funcionario::varchar);
              
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