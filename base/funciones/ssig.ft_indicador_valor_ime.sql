--------------- SQL ---------------

CREATE OR REPLACE FUNCTION ssig.ft_indicador_valor_ime (
  p_administrador integer,
  p_id_usuario integer,
  p_tabla varchar,
  p_transaccion varchar
)
RETURNS varchar AS
$body$
/**************************************************************************
 SISTEMA:		Seguimiento integral de gestión
 FUNCION: 		ssig.ft_indicador_valor_ime
 DESCRIPCION:   Funcion que gestiona las operaciones basicas (inserciones, modificaciones, eliminaciones de la tabla 'ssig.tindicador_valor'
 AUTOR: 		 (admin)
 FECHA:	        21-11-2016 14:01:15
 COMENTARIOS:	
***************************************************************************
 HISTORIAL DE MODIFICACIONES:

 ISSUES  FECHA         AUTOR      DESCRIPCION
  #8	 18/06/2019	   Juan       Corrección de validación en indicador valor
***************************************************************************/
 
DECLARE

	v_nro_requerimiento    	integer;
	v_parametros           	record;
	v_id_requerimiento     	integer;
	v_resp		            varchar;
	v_nombre_funcion        text;
	v_mensaje_error         text;
	v_id_indicador_valor	integer;
    
    item                    record;
    item1                   record;
    item_periodo            record;
    v_posicion              integer;
    v_filtro_periodo        VARCHAR;
			    
BEGIN

    v_nombre_funcion = 'ssig.ft_indicador_valor_ime';
    v_parametros = pxp.f_get_record(p_tabla);

	/*********************************    
 	#TRANSACCION:  'SSIG_INVA_INS'
 	#DESCRIPCION:	Insercion de registros
 	#AUTOR:		admin	
 	#FECHA:		21-11-2016 14:01:15
	***********************************/

	if(p_transaccion='SSIG_INVA_INS')then
					
        begin
     
        	--Sentencia de la insercion
     
        	insert into ssig.tindicador_valor(
			id_indicador,
			semaforo3,
			semaforo5,
			no_reporta,
			semaforo4,
			estado_reg,
			semaforo2,
		--	valor,
			fecha,
			hito,
			semaforo1,
		--	justificacion,
			fecha_reg,
			usuario_ai,
			id_usuario_reg,
			id_usuario_ai,
			fecha_mod,
			id_usuario_mod
          	) values(
			v_parametros.id_indicador,
			v_parametros.semaforo3,
			v_parametros.semaforo5,
            v_parametros.no_reporta,
			v_parametros.semaforo4,
			'activo',
			v_parametros.semaforo2,
		--	v_parametros.valor,
			now(),
			v_parametros.hito,
			v_parametros.semaforo1,
		--	v_parametros.justificacion,
			now(),
			v_parametros._nombre_usuario_ai,
			p_id_usuario,
			v_parametros._id_usuario_ai,
			null,
			null
			)RETURNING id_indicador_valor into v_id_indicador_valor;
			
             --#8 se quito codigo recalculo en agrupadores, por demora demasiado y no siempre son registrados todos los campos 
            
			--Definicion de la respuesta
			v_resp = pxp.f_agrega_clave(v_resp,'mensaje','Indicador valor almacenado(a) con exito (id_indicador_valor'||v_id_indicador_valor||')'); 
            v_resp = pxp.f_agrega_clave(v_resp,'id_indicador_valor',v_id_indicador_valor::varchar);

            --Devuelve la respuesta
            
           
            return v_resp;

		end;
	/*********************************    
 	#TRANSACCION:  'SSIG_INVA_MOD'
 	#DESCRIPCION:	Modificacion de registros
 	#AUTOR:		JUAN	
 	#FECHA:		24-05-2018 15:01:15
	***********************************/

	elsif(p_transaccion='SSIG_INVA_MOD')then

		begin
        --raise EXCEPTION 'errror %',v_parametros.fecha::date;
			--Sentencia de la modificacion
			update ssig.tindicador_valor set
			id_indicador = v_parametros.id_indicador,
			semaforo3 = v_parametros.semaforo3,
			semaforo5 = v_parametros.semaforo5,
			no_reporta = v_parametros.no_reporta::varchar,
			semaforo4 = v_parametros.semaforo4,
			semaforo2 = v_parametros.semaforo2,
			valor = v_parametros.valor,
			fecha = v_parametros.fecha,
			hito = v_parametros.hito,
			semaforo1 = v_parametros.semaforo1,
			justificacion = v_parametros.justificacion,
			fecha_mod = now(),
			id_usuario_mod = p_id_usuario,
			id_usuario_ai = v_parametros._id_usuario_ai,
			usuario_ai = v_parametros._nombre_usuario_ai
			where id_indicador_valor=v_parametros.id_indicador_valor;
               
                
			--Definicion de la respuesta
            v_resp = pxp.f_agrega_clave(v_resp,'mensaje','Indicador valor modificado(a)'); 
            v_resp = pxp.f_agrega_clave(v_resp,'id_indicador_valor',v_parametros.id_indicador_valor::varchar);
               
            --Devuelve la respuesta
            return v_resp;
            
		end;

	/*********************************    
 	#TRANSACCION:  'SSIG_INVA_ELI'
 	#DESCRIPCION:	Eliminacion de registros
 	#AUTOR:		admin	
 	#FECHA:		21-11-2016 14:01:15
	***********************************/

	elsif(p_transaccion='SSIG_INVA_ELI')then

		begin
			--Sentencia de la eliminacion
			delete from ssig.tindicador_valor
            where id_indicador_valor=v_parametros.id_indicador_valor;
               
            --Definicion de la respuesta
            v_resp = pxp.f_agrega_clave(v_resp,'mensaje','Indicador valor eliminado(a)'); 
            v_resp = pxp.f_agrega_clave(v_resp,'id_indicador_valor',v_parametros.id_indicador_valor::varchar);
              
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