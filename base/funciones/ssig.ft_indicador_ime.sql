CREATE OR REPLACE FUNCTION ssig.ft_indicador_ime (
  p_administrador integer,
  p_id_usuario integer,
  p_tabla varchar,
  p_transaccion varchar
)
RETURNS varchar AS
$body$
/**************************************************************************
 SISTEMA:		Seguimiento integral de gestión
 FUNCION: 		ssig.ft_indicador_ime
 DESCRIPCION:   Funcion que gestiona las operaciones basicas (inserciones, modificaciones, eliminaciones de la tabla 'ssig.tindicador'
 AUTOR: 		 (Juan)
 FECHA:	        21-11-2016 14:51:35
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
	v_id_indicador	        integer;
    
    --Variables de semana
    v_id_indicador_Contador	integer;
    -- v_gestion            text;
    v_gestion_inicio        date;
    v_gestion_fin           date;
    v_gestion_contador      date;
    v_valor_frecuencia      text;
    v_frecuencia_hito       boolean;
    
    v_estado_gestion        VARCHAR;
    
    consultaIndicadores     record;
    consultaIndicadorValor  record;
    v_banderaAprobacion     VARCHAR;
    banderaAprobado         boolean;
    
    v_id_indicador_frecuencia INTEGER;
    v_comparacion           varchar;
    v_id_unidad             INTEGER;
    --fin variables semana
			    
BEGIN

    v_nombre_funcion = 'ssig.ft_indicador_ime';
    v_parametros = pxp.f_get_record(p_tabla);

	/*********************************    
 	#TRANSACCION:  'SSIG_IND_INS'
 	#DESCRIPCION:	Insercion de registros
 	#AUTOR:		JUAN	
 	#FECHA:		21-11-2016 14:51:35
	***********************************/

	if(p_transaccion='SSIG_IND_INS')then
					
        begin
        	--Sentencia de la insercion
        	insert into ssig.tindicador(
			id_indicador_unidad,
			id_indicador_frecuencia,
			id_gestion,
			num_decimal,
			semaforo,
			estado_reg,
			sigla,
			descipcion,
			comparacion,
			indicador,
			usuario_ai,
			fecha_reg,
			id_usuario_reg,
			id_usuario_ai,
			fecha_mod,
			id_usuario_mod,
            id_funcionario_ingreso,
            id_funcionario_evaluacion
          	) values(
			v_parametros.id_indicador_unidad,
			v_parametros.id_indicador_frecuencia,
			v_parametros.id_gestion,
			v_parametros.num_decimal,
			v_parametros.semaforo,
			'activo',
			v_parametros.sigla,
			v_parametros.descipcion,
			v_parametros.comparacion,
			v_parametros.indicador,
			v_parametros._nombre_usuario_ai,
			now(),
			p_id_usuario,
			v_parametros._id_usuario_ai,
			null,
			null,
            v_parametros.id_funcionario_ingreso,
            v_parametros.id_funcionario_evaluacion

			)RETURNING id_indicador into v_id_indicador;

			--Definicion de la respuesta
			v_resp = pxp.f_agrega_clave(v_resp,'mensaje','Indicador almacenado(a) con exito (id_indicador'||v_id_indicador||')'); 
            v_resp = pxp.f_agrega_clave(v_resp,'id_indicador',v_id_indicador::varchar);

            --Devuelve la respuesta
            
       --Inicio de insertado de indicadores valores
       -- v_gestion=(select gestion from param.tgestion  where id_gestion=v_parametros.id_gestion);
        v_gestion_inicio=(select fecha_ini from param.tgestion  where id_gestion=v_parametros.id_gestion);   
        v_gestion_fin=(select fecha_fin from param.tgestion  where id_gestion=v_parametros.id_gestion);
       -- if (v_parametros.id_indicador_frecuencia=2) THEN	
                v_valor_frecuencia=(select valor from ssig.tindicador_frecuencia where id_indicador_frecuencia = v_parametros.id_indicador_frecuencia)|| ' days';
                v_frecuencia_hito= (select hito from ssig.tindicador_frecuencia where id_indicador_frecuencia = v_parametros.id_indicador_frecuencia);
                
              --El if en caso de ser la frecuendia de tipo año es necesario restar -1 a la fecha inicio. Motivo (01/01/2017 + 365 dias = 01/01/2018) no toma encuenta el primer dia sino apartir del segundo dia                  
              if((select frecuencia from ssig.tindicador_frecuencia where id_indicador_frecuencia = v_parametros.id_indicador_frecuencia)='Anual')THEN
                  v_gestion_inicio = (SELECT CAST(v_gestion_inicio AS DATE) - CAST('1 days' AS INTERVAL));
              END IF;
              WHILE ((SELECT CAST(v_gestion_inicio AS DATE) + CAST(v_valor_frecuencia AS INTERVAL)) <= v_gestion_fin AND v_frecuencia_hito!=TRUE) loop
                v_gestion_contador=(SELECT CAST(v_gestion_inicio AS DATE) + CAST(v_valor_frecuencia AS INTERVAL));         
                v_gestion_inicio=v_gestion_contador;
                
                insert into ssig.tindicador_valor(
                id_indicador,
                semaforo3,
                semaforo5,
                no_reporta,
                semaforo4,
                estado_reg,
                semaforo2,

                fecha,
                hito,
                semaforo1,

                fecha_reg,
                usuario_ai,
                id_usuario_reg,
                id_usuario_ai,
                fecha_mod,
                id_usuario_mod
                ) values(
                v_id_indicador,
                '',
                '',
                'No reporta'::VARCHAR,
                '',
                'activo',
                '',

                 v_gestion_inicio,
                '',
                '',

                now(),
                v_parametros._nombre_usuario_ai,
                p_id_usuario,
                v_parametros._id_usuario_ai,
                null,
                null
                );   
              end loop;

          -- Fin de Insertado de indicadors valores
            
            return v_resp;

		end;

	/*********************************    
 	#TRANSACCION:  'SSIG_IND_MOD'
 	#DESCRIPCION:	Modificacion de registros
 	#AUTOR:		Juan	
 	#FECHA:		21-11-2016 14:51:35
	***********************************/

	elsif(p_transaccion='SSIG_IND_MOD')then

		begin
          v_id_indicador_frecuencia := (select ifr.id_indicador_frecuencia from ssig.tindicador i 
                                        join  ssig.tindicador_frecuencia ifr 
                                        on i.id_indicador_frecuencia=ifr.id_indicador_frecuencia
                                        where i.id_indicador=v_parametros.id_indicador)::INTEGER; 
          v_comparacion := (select i.comparacion from ssig.tindicador i where i.id_indicador = v_parametros.id_indicador)::VARCHAR;  
          
          v_id_unidad := (select i.id_indicador_unidad from ssig.tindicador i where i.id_indicador = v_parametros.id_indicador)::INTEGER;                              
           --Incio de update tindicador valor
            --raise EXCEPTION '%', v_parametros.id_funcionario_ingreso; 
			--Sentencia de la modificacion
			update ssig.tindicador set
			id_indicador_unidad = v_parametros.id_indicador_unidad,
			id_indicador_frecuencia = v_parametros.id_indicador_frecuencia,
			id_gestion = v_parametros.id_gestion,
			num_decimal = v_parametros.num_decimal,
			semaforo = v_parametros.semaforo,
			sigla = v_parametros.sigla,
			descipcion = v_parametros.descipcion,
			comparacion = v_parametros.comparacion,
			indicador = v_parametros.indicador,
			fecha_mod = now(),
			id_usuario_mod = p_id_usuario,
			id_usuario_ai = v_parametros._id_usuario_ai,
			usuario_ai = v_parametros._nombre_usuario_ai,
            id_funcionario_ingreso = v_parametros.id_funcionario_ingreso,
            id_funcionario_evaluacion = v_parametros.id_funcionario_evaluacion
			where id_indicador=v_parametros.id_indicador;
            
            --RAISE NOTICE '%', v_parametros.id_indicador_frecuencia;
            --raise EXCEPTION '%', v_parametros.id_indicador_frecuencia; 
                          
            if( (v_parametros.id_indicador_frecuencia::INTEGER != v_id_indicador_frecuencia::INTEGER) or (v_comparacion::varchar != v_parametros.comparacion::varchar)or (v_id_unidad != v_parametros.id_indicador_unidad) )THEN
                   delete from ssig.tindicador_valor where id_indicador=v_parametros.id_indicador;
                  
                  --Inicio de insertado de indicadores valores
                  v_gestion_inicio=(select fecha_ini from param.tgestion  where id_gestion=v_parametros.id_gestion);   
                  v_gestion_fin=(select fecha_fin from param.tgestion  where id_gestion=v_parametros.id_gestion);

                  v_valor_frecuencia=(select valor from ssig.tindicador_frecuencia where id_indicador_frecuencia = v_parametros.id_indicador_frecuencia)|| ' days';
                  v_frecuencia_hito= (select hito from ssig.tindicador_frecuencia where id_indicador_frecuencia = v_parametros.id_indicador_frecuencia);

                  --El if en caso de ser la frecuendia de tipo año es necesario restar -1 a la fecha inicio. Motivo (01/01/2017 + 365 dias = 01/01/2018) no toma encuenta el primer dia sino apartir del segundo dia           
                  if((select frecuencia from ssig.tindicador_frecuencia where id_indicador_frecuencia = v_parametros.id_indicador_frecuencia)='Anual')THEN
                      v_gestion_inicio = (SELECT CAST(v_gestion_inicio AS DATE) - CAST('1 days' AS INTERVAL));
                  END IF;
                  WHILE ((SELECT CAST(v_gestion_inicio AS DATE) + CAST(v_valor_frecuencia AS INTERVAL)) <= v_gestion_fin AND v_frecuencia_hito!=TRUE) loop
                  v_gestion_contador=(SELECT CAST(v_gestion_inicio AS DATE) + CAST(v_valor_frecuencia AS INTERVAL));         
                  v_gestion_inicio=v_gestion_contador;
                  
                  insert into ssig.tindicador_valor(
                  id_indicador,
                  semaforo3,
                  semaforo5,
                  no_reporta,
                  semaforo4,
                  estado_reg,
                  semaforo2,

                  fecha,
                  hito,
                  semaforo1,

                  fecha_reg,
                  usuario_ai,
                  id_usuario_reg,
                  id_usuario_ai,
                  fecha_mod,
                  id_usuario_mod
                  ) values(
                  v_parametros.id_indicador,
                  '',
                  '',
                  'No reporta'::VARCHAR,
                  '',
                  'activo',
                  '',

                   v_gestion_inicio,
                  '',
                  '',

                  now(),
                  v_parametros._nombre_usuario_ai,
                  p_id_usuario,
                  v_parametros._id_usuario_ai,
                  null,
                  null
                  );   
                end loop;
            END IF;

          -- Fin de Insertado de indicadors valores
               
			--Definicion de la respuesta
            v_resp = pxp.f_agrega_clave(v_resp,'mensaje','Indicador modificado(a)'); 
            v_resp = pxp.f_agrega_clave(v_resp,'id_indicador',v_parametros.id_indicador::varchar);
               
            --Devuelve la respuesta
            RAISE NOTICE '%', v_resp;
            return v_resp;
            
		end;
        
    
        
    
    	/*********************************    
 	#TRANSACCION:  'SSIG_VCINDICADOR'
 	#DESCRIPCION:	VALIDAR CAMBIO DE FRECUENCIA DE INDICADOR
 	#AUTOR:		JUAN	
 	#FECHA:		23-06-2017 14:01:15
	***********************************/

	elsif(p_transaccion='SSIG_VCINDICADOR')then

		begin
       --Validar si se tiene todos los emaforos registrado antes de aprobar la gestion 
        banderaAprobado :=false::BOOLEAN;
        for consultaIndicadores in (SELECT i.id_gestion,i.id_indicador,i.semaforo,i.comparacion,ifr.frecuencia,iv.hito,iv.semaforo1,iv.semaforo2,iv.semaforo3,iv.semaforo4,iv.semaforo5  FROM ssig.tindicador i join ssig.tindicador_frecuencia ifr on ifr.id_indicador_frecuencia=i.id_indicador_frecuencia JOIN ssig.tindicador_valor iv on iv.id_indicador = i.id_indicador  where i.id_indicador::INTEGER = v_parametros.id_indicador::INTEGER )  loop
  
             if((consultaIndicadores.frecuencia::VARCHAR ='Hito' and consultaIndicadores.semaforo::VARCHAR='Simple' and consultaIndicadores.comparacion::VARCHAR='Asc') or (consultaIndicadores.frecuencia::VARCHAR ='Hito' and consultaIndicadores.semaforo::VARCHAR='Simple' and consultaIndicadores.comparacion::VARCHAR='Desc') )then 
                 if(consultaIndicadores.semaforo1 !='' or consultaIndicadores.semaforo2 !='' or consultaIndicadores.semaforo3 !='' or consultaIndicadores.hito !='')THEN
                     banderaAprobado :=true::BOOLEAN;
                 end if; 
             end if; 
             
             if((consultaIndicadores.frecuencia::VARCHAR !='Hito' and consultaIndicadores.semaforo::VARCHAR='Simple' and consultaIndicadores.comparacion::VARCHAR='Asc') or (consultaIndicadores.frecuencia::VARCHAR !='Hito' and consultaIndicadores.semaforo::VARCHAR='Simple' and consultaIndicadores.comparacion::VARCHAR='Desc') )then 
                 if(consultaIndicadores.semaforo1 !='' or consultaIndicadores.semaforo2 !='' or consultaIndicadores.semaforo3 !='')THEN
                     banderaAprobado :=true::BOOLEAN;
                 end if; 
             end if; 
             
             if((consultaIndicadores.frecuencia::VARCHAR ='Hito' and consultaIndicadores.semaforo::VARCHAR='Compuesto' and consultaIndicadores.comparacion::VARCHAR='Asc' or consultaIndicadores.comparacion::VARCHAR='Desc') or (consultaIndicadores.frecuencia::VARCHAR ='Hito' and consultaIndicadores.semaforo::VARCHAR='Compuesto' and consultaIndicadores.comparacion::VARCHAR='Desc') )then 
                 if(consultaIndicadores.semaforo1 !='' or consultaIndicadores.semaforo2 !='' or consultaIndicadores.semaforo3 !='' or consultaIndicadores.semaforo4 !='' or consultaIndicadores.semaforo5 !='' or consultaIndicadores.hito !='')THEN
                     banderaAprobado :=true::BOOLEAN;
                 end if; 
             end if;
             
             if((consultaIndicadores.frecuencia::VARCHAR !='Hito' and consultaIndicadores.semaforo::VARCHAR='Compuesto' and consultaIndicadores.comparacion::VARCHAR='Asc') or (consultaIndicadores.frecuencia::VARCHAR !='Hito' and consultaIndicadores.semaforo::VARCHAR='Compuesto' and consultaIndicadores.comparacion::VARCHAR='Desc') )then 
                 if(consultaIndicadores.semaforo1 !='' or consultaIndicadores.semaforo2 !='' or consultaIndicadores.semaforo3 !='' or consultaIndicadores.semaforo4 !='' or consultaIndicadores.semaforo5 !='')THEN
                     banderaAprobado :=true::BOOLEAN;
                 end if; 
             end if;

        end loop;
           
       -- RAISE NOTICE 'ver semaforo juan %',banderaAprobado;
       -- RAISE EXCEPTION 'Error provocado por juan %', banderaAprobado;

        --Devuelve la respuesta
          v_resp = pxp.f_agrega_clave(v_resp,'mensaje','estado gestion'); 
          v_resp = pxp.f_agrega_clave(v_resp,'aprobado','%'||banderaAprobado||'%'::varchar);
            return v_resp;
            
	end;   
        
    	/*********************************    
 	#TRANSACCION:  'SSIG_AGESTION'
 	#DESCRIPCION:	Se realizo la opción de aprobar segun la gestion seleccionada por el usuario en la vista de Form_indicador
 	#AUTOR:		admin	
 	#FECHA:		21-11-2016 14:01:15
	***********************************/

	elsif(p_transaccion='SSIG_AGESTION')then

		begin
        
       --  RAISE NOTICE 'estado juan %', v_parametros.estado;
       --  RAISE EXCEPTION '%', v_resp;
         
			--Sentencia de la modificacion
			update ssig.tindicador set aprobado = v_parametros.estado::BOOLEAN where id_gestion=v_parametros.id_gestion;
               
			--Definicion de la respuesta
            v_resp = pxp.f_agrega_clave(v_resp,'mensaje','Indicador valor modificado(a)'); 
            v_resp = pxp.f_agrega_clave(v_resp,'id_indicador_valor',v_parametros.id_gestion::varchar);
               
            --Devuelve la respuesta
            return v_resp;
            
		end;   
        
    	/*********************************    
 	#TRANSACCION:  'SSIG_ESTADO_GESTION'
 	#DESCRIPCION:	Validar si la gestion seleccionada por el usuario estan aprobada o desaprobada
 	#AUTOR:		admin	
 	#FECHA:		21-11-2016 14:01:15
	***********************************/

	elsif(p_transaccion='SSIG_ESTADO_GESTION')then

		begin
	   --Sentencia de la modificacion
	   v_estado_gestion :=	(SELECT aprobado FROM ssig.tindicador  WHERE id_gestion = v_parametros.id_gestion::INTEGER  ORDER BY id_gestion ASC LIMIT 1)::VARCHAR ;
       

       -- RAISE NOTICE 'ver semaforo juan %',banderaAprobado;
       -- RAISE EXCEPTION 'Error provocado por juan %', banderaAprobado;
       
        --Devuelve la respuesta
          v_resp = pxp.f_agrega_clave(v_resp,'mensaje','estado gestion'); 
          v_resp = pxp.f_agrega_clave(v_resp,'aprobado','%'||v_estado_gestion||'%'::varchar);
            return v_resp;
            
	end;   
    
    	/*********************************    
 	#TRANSACCION:  'SSIG_EGINDICADOR'
 	#DESCRIPCION:	Verificar si todos los emaforos correspondientes estan llenados para poder aprobar la gestion
 	#AUTOR:		JUAN	
 	#FECHA:		21-11-2016 14:01:15
	***********************************/

	elsif(p_transaccion='SSIG_EGINDICADOR')then

		begin

       --Validar si se tiene todos los emaforos registrado antes de aprobar la gestion 
        banderaAprobado :=false::BOOLEAN;
        for consultaIndicadores in (SELECT i.id_gestion,i.id_indicador,i.semaforo,i.comparacion,ifr.frecuencia,iv.hito,iv.semaforo1,iv.semaforo2,iv.semaforo3,iv.semaforo4,iv.semaforo5  FROM ssig.tindicador i join ssig.tindicador_frecuencia ifr on ifr.id_indicador_frecuencia=i.id_indicador_frecuencia JOIN ssig.tindicador_valor iv on iv.id_indicador = i.id_indicador  where i.id_gestion::INTEGER = v_parametros.id_gestion::INTEGER )  loop
  
             if((consultaIndicadores.frecuencia::VARCHAR ='Hito' and consultaIndicadores.semaforo::VARCHAR='Simple' and consultaIndicadores.comparacion::VARCHAR='Asc') or (consultaIndicadores.frecuencia::VARCHAR ='Hito' and consultaIndicadores.semaforo::VARCHAR='Simple' and consultaIndicadores.comparacion::VARCHAR='Desc') )then 
                 if(consultaIndicadores.semaforo1='' or consultaIndicadores.semaforo2='' or consultaIndicadores.semaforo3='' or consultaIndicadores.hito='')THEN
                     banderaAprobado :=true::BOOLEAN;
                 end if; 
             end if; 
             
             if((consultaIndicadores.frecuencia::VARCHAR !='Hito' and consultaIndicadores.semaforo::VARCHAR='Simple' and consultaIndicadores.comparacion::VARCHAR='Asc') or (consultaIndicadores.frecuencia::VARCHAR !='Hito' and consultaIndicadores.semaforo::VARCHAR='Simple' and consultaIndicadores.comparacion::VARCHAR='Desc') )then 
                 if(consultaIndicadores.semaforo1='' or consultaIndicadores.semaforo2='' or consultaIndicadores.semaforo3='')THEN
                     banderaAprobado :=true::BOOLEAN;
                 end if; 
             end if; 
             
             if((consultaIndicadores.frecuencia::VARCHAR ='Hito' and consultaIndicadores.semaforo::VARCHAR='Compuesto' and consultaIndicadores.comparacion::VARCHAR='Asc') or (consultaIndicadores.frecuencia::VARCHAR ='Hito' and consultaIndicadores.semaforo::VARCHAR='Compuesto' and consultaIndicadores.comparacion::VARCHAR='Desc') )then 
                 if(consultaIndicadores.semaforo1='' or consultaIndicadores.semaforo2='' or consultaIndicadores.semaforo3='' or consultaIndicadores.semaforo4='' or consultaIndicadores.semaforo5='' or consultaIndicadores.hito='')THEN
                     banderaAprobado :=true::BOOLEAN;
                 end if; 
             end if;
             
             if((consultaIndicadores.frecuencia::VARCHAR !='Hito' and consultaIndicadores.semaforo::VARCHAR='Compuesto' and consultaIndicadores.comparacion::VARCHAR='Asc') or (consultaIndicadores.frecuencia::VARCHAR !='Hito' and consultaIndicadores.semaforo::VARCHAR='Compuesto' and consultaIndicadores.comparacion::VARCHAR='Desc') )then 
                 if(consultaIndicadores.semaforo1='' or consultaIndicadores.semaforo2='' or consultaIndicadores.semaforo3='' or consultaIndicadores.semaforo4='' or consultaIndicadores.semaforo5='')THEN
                     banderaAprobado :=true::BOOLEAN;
                 end if; 
             end if;

        end loop;
           
        --RAISE NOTICE 'ver semaforo juan %',banderaAprobado;
        --RAISE EXCEPTION 'Error provocado por juan %', banderaAprobado;

        --Devuelve la respuesta
          v_resp = pxp.f_agrega_clave(v_resp,'mensaje','estado gestion'); 
          v_resp = pxp.f_agrega_clave(v_resp,'aprobado','%'||banderaAprobado||'%'::varchar);
            return v_resp;
            
	end;   

	/*********************************    
 	#TRANSACCION:  'SSIG_IND_ELI'
 	#DESCRIPCION:	Eliminacion de registros
 	#AUTOR:		Juan	
 	#FECHA:		21-11-2016 14:51:35
	***********************************/

	elsif(p_transaccion='SSIG_IND_ELI')then

		begin
			--Sentencia de la eliminacion
            
            delete from ssig.tindicador_valor
            where id_indicador=v_parametros.id_indicador;
            
			delete from ssig.tindicador
            where id_indicador=v_parametros.id_indicador;
               
            --Definicion de la respuesta
            v_resp = pxp.f_agrega_clave(v_resp,'mensaje','Indicador eliminado(a)'); 
            v_resp = pxp.f_agrega_clave(v_resp,'id_indicador',v_parametros.id_indicador::varchar);
              
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
