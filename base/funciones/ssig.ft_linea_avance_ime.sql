CREATE OR REPLACE FUNCTION ssig.ft_linea_avance_ime (
  p_administrador integer,
  p_id_usuario integer,
  p_tabla varchar,
  p_transaccion varchar
)
RETURNS varchar AS
$body$
/**************************************************************************
 SISTEMA:		Seguimiento integral de gestión
 FUNCION: 		ssig.ft_linea_avance_ime
 DESCRIPCION:   Funcion que gestiona las operaciones basicas (inserciones, modificaciones, eliminaciones de la tabla 'ssig.tlinea_avance'
 AUTOR: 		 (admin)
 FECHA:	        19-02-2017 02:21:07
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
	v_id_linea_avance	    integer;
    
    va_id_funcionarios      VARCHAR [];
    v_id_funcionario        INTEGER;
    v_id_linea              INTEGER;
    v_id_linea_padre        INTEGER;
    
    v_gestion_inicio        date;
    v_gestion_fin           date;
    v_valor_frecuencia      text;
    v_gestion_contador      date;
    v_meses                 text;
    
    item                    record;
    v_cont_lavance          integer;
    
    v_param                 varchar[]; 
    v_param_det             varchar[]; 
    v_tamano                integer;
    v_i                     integer;
    v_consulta    		    varchar;
    v_consulta_temporal     text;
    
    v_estado_linea_avance   BOOLEAN;
			    
BEGIN

    v_nombre_funcion = 'ssig.ft_linea_avance_ime';
    v_parametros = pxp.f_get_record(p_tabla);

	/*********************************    
 	#TRANSACCION:  'SSIG_LIAV_INS'
 	#DESCRIPCION:	Insercion de registros
 	#AUTOR:		admin	
 	#FECHA:		19-02-2017 02:21:07
	***********************************/

	if(p_transaccion='SSIG_LIAV_INS')then
					
        begin
   --     IF v_parametros.id_linea_padre != ''
   --     THEN
   --       v_id_linea_padre = v_parametros.id_linea_padre :: INTEGER;
--        END IF;
       --raise notice 'varibale juan %',v_parametros.id_plan;
       --RAISE EXCEPTION 'error provocado %', v_parametros.id_plan;
       
        INSERT INTO ssig.tlinea (
              id_linea_padre,
              id_plan,
              estado_reg,
              nivel,
              nombre_linea,
              peso,
              fecha_reg,
              usuario_ai,
              id_usuario_reg,
              id_usuario_ai,
              id_usuario_mod,
              fecha_mod
            ) VALUES (
              v_parametros.id_linea_padre :: INTEGER,
              v_parametros.id_plan::INTEGER,
              'activo',
              v_parametros.nivel::INTEGER,
              v_parametros.nombre_linea::VARCHAR,
              v_parametros.peso::INTEGER,
              now(),
              v_parametros._nombre_usuario_ai,
              p_id_usuario,
              v_parametros._id_usuario_ai,
              NULL,
              NULL

            )
            RETURNING id_linea
              INTO v_id_linea;
              
            --Insertado de meses en la tabla line avance
             v_gestion_inicio :=(select g.fecha_ini from ssig.tplan p JOIN param.tgestion g on g.id_gestion = p.id_gestion WHERE P.id_plan=v_parametros.id_plan  limit 1);   
             v_gestion_fin :=(select g.fecha_fin from ssig.tplan p JOIN param.tgestion g on g.id_gestion = p.id_gestion WHERE P.id_plan=v_parametros.id_plan  limit 1);
             v_valor_frecuencia := '1' || ' MONTH';
             v_meses :='';
           
             WHILE ((SELECT CAST(v_gestion_inicio AS DATE)) <= v_gestion_fin ) loop
                 
                  if((SELECT date_part('month',CAST(v_gestion_inicio AS DATE)))=1)then
                      v_meses := 'Ene'|| (SELECT substring( date_part('year',CAST(v_gestion_inicio AS DATE))::VARCHAR from 3 for 4));
                        
                      INSERT INTO ssig.tlinea_avance(id_linea,
                                                      mes,
                                                      avance_real,
                                                      avance_previsto,
                                                      estado_reg,
                                                      comentario,
                                                      aprobado_real,
                                                      id_usuario_ai,
                                                      usuario_ai,
                                                      fecha_reg,
                                                      id_usuario_reg,
                                                      id_usuario_mod,
                                                      fecha_mod) 
                                                VALUES(
                                                      v_id_linea::INTEGER,
                                                      v_meses::VARCHAR,
                                                      0,
                                                      0,
                                                      'activo',
                                                      '',
                                                      'false'::BOOLEAN,
                                                      v_parametros._id_usuario_ai,
                                                      v_parametros._nombre_usuario_ai,
                                                      now(),
                                                      p_id_usuario,
                                                      null,
                                                      null
                                                );
                                               -- RAISE EXCEPTION 'Error provocado Juan %',v_meses::VARCHAR;
                     
                  end if;
                  if((SELECT date_part('month',CAST(v_gestion_inicio AS DATE)))=2)then
                      v_meses := 'Feb'|| (SELECT substring( date_part('year',CAST(v_gestion_inicio AS DATE))::VARCHAR from 3 for 4));
                      INSERT INTO ssig.tlinea_avance(id_linea,
                                                      mes,
                                                      avance_real,
                                                      avance_previsto,
                                                      estado_reg,
                                                      comentario,
                                                      aprobado_real,
                                                      id_usuario_ai,
                                                      usuario_ai,
                                                      fecha_reg,
                                                      id_usuario_reg,
                                                      id_usuario_mod,
                                                      fecha_mod) 
                                                VALUES(
                                                      v_id_linea::INTEGER,
                                                      v_meses::VARCHAR,
                                                      0,
                                                      0,
                                                      'activo',
                                                      '',
                                                      'false'::BOOLEAN,
                                                      v_parametros._id_usuario_ai,
                                                      v_parametros._nombre_usuario_ai,
                                                      now(),
                                                      p_id_usuario,
                                                      null,
                                                      null
                                                );
                  end if;
                  if((SELECT date_part('month',CAST(v_gestion_inicio AS DATE)))=3)then
                      v_meses := 'Mar'|| (SELECT substring( date_part('year',CAST(v_gestion_inicio AS DATE))::VARCHAR from 3 for 4));
                      INSERT INTO ssig.tlinea_avance(id_linea,
                                                      mes,
                                                      avance_real,
                                                      avance_previsto,
                                                      estado_reg,
                                                      comentario,
                                                      aprobado_real,
                                                      id_usuario_ai,
                                                      usuario_ai,
                                                      fecha_reg,
                                                      id_usuario_reg,
                                                      id_usuario_mod,
                                                      fecha_mod) 
                                                VALUES(
                                                      v_id_linea::INTEGER,
                                                      v_meses::VARCHAR,
                                                      0,
                                                      0,
                                                      'activo',
                                                      '',
                                                      'false'::BOOLEAN,
                                                      v_parametros._id_usuario_ai,
                                                      v_parametros._nombre_usuario_ai,
                                                      now(),
                                                      p_id_usuario,
                                                      null,
                                                      null
                                                );
                  end if;
                  if((SELECT  date_part('month',CAST(v_gestion_inicio AS DATE)))=4)then
                      v_meses := 'Abr'|| (SELECT substring( date_part('year',CAST(v_gestion_inicio AS DATE))::VARCHAR from 3 for 4));
                      INSERT INTO ssig.tlinea_avance(id_linea,
                                                      mes,
                                                      avance_real,
                                                      avance_previsto,
                                                      estado_reg,
                                                      comentario,
                                                      aprobado_real,
                                                      id_usuario_ai,
                                                      usuario_ai,
                                                      fecha_reg,
                                                      id_usuario_reg,
                                                      id_usuario_mod,
                                                      fecha_mod) 
                                                VALUES(
                                                      v_id_linea::INTEGER,
                                                      v_meses::VARCHAR,
                                                      0,
                                                      0,
                                                      'activo',
                                                      '',
                                                      'false'::BOOLEAN,
                                                      v_parametros._id_usuario_ai,
                                                      v_parametros._nombre_usuario_ai,
                                                      now(),
                                                      p_id_usuario,
                                                      null,
                                                      null
                                                );
                  end if;
                  if((SELECT date_part('month',CAST(v_gestion_inicio AS DATE)))=5)then
                      v_meses := 'May'|| (SELECT substring( date_part('year',CAST(v_gestion_inicio AS DATE))::VARCHAR from 3 for 4));
                      INSERT INTO ssig.tlinea_avance(id_linea,
                                                      mes,
                                                      avance_real,
                                                      avance_previsto,
                                                      estado_reg,
                                                      comentario,
                                                      aprobado_real,
                                                      id_usuario_ai,
                                                      usuario_ai,
                                                      fecha_reg,
                                                      id_usuario_reg,
                                                      id_usuario_mod,
                                                      fecha_mod) 
                                                VALUES(
                                                      v_id_linea::INTEGER,
                                                      v_meses::VARCHAR,
                                                      0,
                                                      0,
                                                      'activo',
                                                      '',
                                                      'false'::BOOLEAN,
                                                      v_parametros._id_usuario_ai,
                                                      v_parametros._nombre_usuario_ai,
                                                      now(),
                                                      p_id_usuario,
                                                      null,
                                                      null
                                                );
                  end if;
                  if((SELECT date_part('month',CAST(v_gestion_inicio AS DATE)))=6)then
                      v_meses :=  'Jun'|| (SELECT substring( date_part('year',CAST(v_gestion_inicio AS DATE))::VARCHAR from 3 for 4)) ;
                      INSERT INTO ssig.tlinea_avance(id_linea,
                                                      mes,
                                                      avance_real,
                                                      avance_previsto,
                                                      estado_reg,
                                                      comentario,
                                                      aprobado_real,
                                                      id_usuario_ai,
                                                      usuario_ai,
                                                      fecha_reg,
                                                      id_usuario_reg,
                                                      id_usuario_mod,
                                                      fecha_mod) 
                                                VALUES(
                                                      v_id_linea::INTEGER,
                                                      v_meses::VARCHAR,
                                                      0,
                                                      0,
                                                      'activo',
                                                      '',
                                                      'false'::BOOLEAN,
                                                      v_parametros._id_usuario_ai,
                                                      v_parametros._nombre_usuario_ai,
                                                      now(),
                                                      p_id_usuario,
                                                      null,
                                                      null
                                                );
                  end if;
                  if((SELECT date_part('month',CAST(v_gestion_inicio AS DATE)))=7)then
                      v_meses := 'Jul'|| (SELECT substring( date_part('year',CAST(v_gestion_inicio AS DATE))::VARCHAR from 3 for 4)) ;
                      INSERT INTO ssig.tlinea_avance(id_linea,
                                                      mes,
                                                      avance_real,
                                                      avance_previsto,
                                                      estado_reg,
                                                      comentario,
                                                      aprobado_real,
                                                      id_usuario_ai,
                                                      usuario_ai,
                                                      fecha_reg,
                                                      id_usuario_reg,
                                                      id_usuario_mod,
                                                      fecha_mod) 
                                                VALUES(
                                                      v_id_linea::INTEGER,
                                                      v_meses::VARCHAR,
                                                      0,
                                                      0,
                                                      'activo',
                                                      '',
                                                      'false'::BOOLEAN,
                                                      v_parametros._id_usuario_ai,
                                                      v_parametros._nombre_usuario_ai,
                                                      now(),
                                                      p_id_usuario,
                                                      null,
                                                      null
                                                );
                  end if;
                  if((SELECT date_part('month',CAST(v_gestion_inicio AS DATE)))=8)then
                      v_meses := 'Agos'|| (SELECT substring( date_part('year',CAST(v_gestion_inicio AS DATE))::VARCHAR from 3 for 4)) ;
                      INSERT INTO ssig.tlinea_avance(id_linea,
                                                      mes,
                                                      avance_real,
                                                      avance_previsto,
                                                      estado_reg,
                                                      comentario,
                                                      aprobado_real,
                                                      id_usuario_ai,
                                                      usuario_ai,
                                                      fecha_reg,
                                                      id_usuario_reg,
                                                      id_usuario_mod,
                                                      fecha_mod) 
                                                VALUES(
                                                      v_id_linea::INTEGER,
                                                      v_meses::VARCHAR,
                                                      0,
                                                      0,
                                                      'activo',
                                                      '',
                                                      'false'::BOOLEAN,
                                                      v_parametros._id_usuario_ai,
                                                      v_parametros._nombre_usuario_ai,
                                                      now(),
                                                      p_id_usuario,
                                                      null,
                                                      null
                                                );
                  end if;
                  if((SELECT date_part('month',CAST(v_gestion_inicio AS DATE)))=9)then
                      v_meses := 'Sep'|| (SELECT substring( date_part('year',CAST(v_gestion_inicio AS DATE))::VARCHAR from 3 for 4)) ;
                      INSERT INTO ssig.tlinea_avance(id_linea,
                                                      mes,
                                                      avance_real,
                                                      avance_previsto,
                                                      estado_reg,
                                                      comentario,
                                                      aprobado_real,
                                                      id_usuario_ai,
                                                      usuario_ai,
                                                      fecha_reg,
                                                      id_usuario_reg,
                                                      id_usuario_mod,
                                                      fecha_mod) 
                                                VALUES(
                                                      v_id_linea::INTEGER,
                                                      v_meses::VARCHAR,
                                                      0,
                                                      0,
                                                      'activo',
                                                      '',
                                                      'false'::BOOLEAN,
                                                      v_parametros._id_usuario_ai,
                                                      v_parametros._nombre_usuario_ai,
                                                      now(),
                                                      p_id_usuario,
                                                      null,
                                                      null
                                                );
                  end if;   
                  if((SELECT date_part('month',CAST(v_gestion_inicio AS DATE)))=10)then
                      v_meses := 'Oct'|| (SELECT substring( date_part('year',CAST(v_gestion_inicio AS DATE))::VARCHAR from 3 for 4)) ;
                      INSERT INTO ssig.tlinea_avance(id_linea,
                                                      mes,
                                                      avance_real,
                                                      avance_previsto,
                                                      estado_reg,
                                                      comentario,
                                                      aprobado_real,
                                                      id_usuario_ai,
                                                      usuario_ai,
                                                      fecha_reg,
                                                      id_usuario_reg,
                                                      id_usuario_mod,
                                                      fecha_mod) 
                                                VALUES(
                                                      v_id_linea::INTEGER,
                                                      v_meses::VARCHAR,
                                                      0,
                                                      0,
                                                      'activo',
                                                      '',
                                                      'false'::BOOLEAN,
                                                      v_parametros._id_usuario_ai,
                                                      v_parametros._nombre_usuario_ai,
                                                      now(),
                                                      p_id_usuario,
                                                      null,
                                                      null
                                                );
                  end if;  
                  if((SELECT date_part('month',CAST(v_gestion_inicio AS DATE)))=11)then
                      v_meses :=  'Nov'|| (SELECT substring( date_part('year',CAST(v_gestion_inicio AS DATE))::VARCHAR from 3 for 4)) ;
                      INSERT INTO ssig.tlinea_avance(id_linea,
                                                      mes,
                                                      avance_real,
                                                      avance_previsto,
                                                      estado_reg,
                                                      comentario,
                                                      aprobado_real,
                                                      id_usuario_ai,
                                                      usuario_ai,
                                                      fecha_reg,
                                                      id_usuario_reg,
                                                      id_usuario_mod,
                                                      fecha_mod) 
                                                VALUES(
                                                      v_id_linea::INTEGER,
                                                      v_meses::VARCHAR,
                                                      0,
                                                      0,
                                                      'activo',
                                                      '',
                                                      'false'::BOOLEAN,
                                                      v_parametros._id_usuario_ai,
                                                      v_parametros._nombre_usuario_ai,
                                                      now(),
                                                      p_id_usuario,
                                                      null,
                                                      null
                                                );
                  end if; 
                  if((SELECT date_part('month',CAST(v_gestion_inicio AS DATE)))=12)then
                      v_meses :=  'Dic'|| (SELECT substring( date_part('year',CAST(v_gestion_inicio AS DATE))::VARCHAR from 3 for 4));
                      INSERT INTO ssig.tlinea_avance(id_linea,
                                                      mes,
                                                      avance_real,
                                                      avance_previsto,
                                                      estado_reg,
                                                      comentario,
                                                      aprobado_real,
                                                      id_usuario_ai,
                                                      usuario_ai,
                                                      fecha_reg,
                                                      id_usuario_reg,
                                                      id_usuario_mod,
                                                      fecha_mod) 
                                                VALUES(
                                                      v_id_linea::INTEGER,
                                                      v_meses::VARCHAR,
                                                      0,
                                                      0,
                                                      'activo',
                                                      '',
                                                      'false'::BOOLEAN,
                                                      v_parametros._id_usuario_ai,
                                                      v_parametros._nombre_usuario_ai,
                                                      now(),
                                                      p_id_usuario,
                                                      null,
                                                      null
                                                );
                  end if; 
 
                  v_gestion_contador=(SELECT CAST(v_gestion_inicio AS DATE) + CAST(v_valor_frecuencia AS INTERVAL));         
                  v_gestion_inicio=v_gestion_contador;
                  
             end loop;
            --fin insetado de linea avance  
              
            -- Insertar  funcionario
            va_id_funcionarios := string_to_array(v_parametros.id_funcionarios, ',');

            FOREACH v_id_funcionario IN ARRAY va_id_funcionarios
            LOOP
              INSERT INTO ssig.tlinea_funcionario(
                id_linea,
                id_funcionario,
                estado_reg,
                fecha_reg,
                usuario_ai,
                id_usuario_reg,
                id_usuario_ai,
                fecha_mod,
                id_usuario_mod
              ) VALUES (
                v_id_linea,
                v_id_funcionario :: INTEGER,
                'activo',
                now(),
                v_parametros._nombre_usuario_ai,
                p_id_usuario,
                v_parametros._id_usuario_ai,
                NULL,
                NULL
              );
            END LOOP; 
        
        	--Sentencia de la insercion
			
			--Definicion de la respuesta
			--v_resp = pxp.f_agrega_clave(v_resp,'mensaje','Linea avance almacenado(a) con exito (id_linea_avance'||v_id_linea_avance||')'); 
            --v_resp = pxp.f_agrega_clave(v_resp,'id_linea_avance',v_id_linea_avance::varchar);
            v_resp = pxp.f_agrega_clave(v_resp, 'mensaje', 'Definición de líneas modificado(a)');
            v_resp = pxp.f_agrega_clave(v_resp, 'id_linea', v_parametros.id_linea :: VARCHAR);
            --Devuelve la respuesta
            return v_resp;

		end;
	/*********************************    
 	#TRANSACCION:  'SSIG_APREVISTO_INS'
 	#DESCRIPCION:	Modificacion de registros
 	#AUTOR:		admin	
 	#FECHA:		19-02-2017 02:21:07
	***********************************/

	elsif(p_transaccion='SSIG_APREVISTO_INS')then

		begin
			--Sentencia de la modificacion
            
               --RAISE EXCEPTION '%',v_parametros.
        
                UPDATE ssig.tlinea_avance
                SET avance_real = v_parametros.avance_real::NUMERIC,
                comentario=v_parametros.comentario::VARCHAR,
                dato=v_parametros.dato::VARCHAR
                WHERE id_linea_avance = v_parametros.id_linea_avance::INTEGER;

            return v_resp;
            
		end;
	/*********************************    
 	#TRANSACCION:  'SSIG_LIAV_MOD'
 	#DESCRIPCION:	Modificacion de registros
 	#AUTOR:		admin	
 	#FECHA:		19-02-2017 02:21:07
	***********************************/

	elsif(p_transaccion='SSIG_LIAV_MOD')then

		begin
			--Sentencia de la modificacion
            v_i := 0; 
            v_param= string_to_array(v_parametros::TEXT,',');
            v_tamano = coalesce(array_length(v_param, 1),0);
            --v_i := 15;  el parametro v_parametros empieza desde el numero 1 segun las comas  "este funciona con el grid arbolito"
            v_i := 16;  --este funciona con un grid normal
            
            
--RAISE EXCEPTION 'error provocado por juan:> %',v_param[16]::VARCHAR;
--RAISE EXCEPTION 'error provocado por juan %',v_parametros::TEXT;
           v_consulta_temporal :='';    
           FOR item in (select la.mes, la.id_linea_avance,la.avance_previsto,la.avance_real,la.aprobado_real from ssig.tlinea_avance la where la.id_linea = v_parametros.id_linea::INTEGER order by la.id_linea_avance) LOOP
                --v_id_linea_avance =(select fecha_ini from param.tgestion  where id_gestion=v_param[v_i+1]::INTEGER);   
                --v_consulta_temporal :=v_consulta_temporal||'update ssig.tlinea_avance set avance_previsto = '||v_param[v_i]::NUMERIC||' WHERE id_linea_avance = '||v_param[v_i+1]::INTEGER;
                  --RAISE EXCEPTION 'error provocado por juan %',v_parametros.ene17::TEXT;
                --RAISE exception 'tester  %', v_consulta_temporal;
                UPDATE ssig.tlinea_avance
                SET avance_previsto = v_param[v_i]::NUMERIC
                WHERE id_linea_avance = v_param[v_i+1]::INTEGER;
                --execute(v_consulta_temporal);     
                v_i :=v_i+2;              
           end loop;   


               
			--Definicion de la respuesta
            -- v_resp = pxp.f_agrega_clave(v_resp,'mensaje','Linea avance modificado(a)'); 
            --v_resp = pxp.f_agrega_clave(v_resp,'id_linea_avance',v_parametros.id_linea_avance::varchar);
            --  v_resp = pxp.f_agrega_clave(v_resp, 'mensaje', 'Definición de líneas modificado(a)');
            --  v_resp = pxp.f_agrega_clave(v_resp, 'id_linea', v_parametros.id_linea :: VARCHAR);   
            --Devuelve la respuesta
            return v_resp;
            
		end;
        
	/*********************************    
 	#TRANSACCION:  'SSIG_CD_LAVANCE'
 	#DESCRIPCION:	Calcular cantidades de meses segun el rango de fechas por gestion
 	#AUTOR:		Juan	
 	#FECHA:		19-06-2017 02:21:07
	***********************************/

	elsif(p_transaccion='SSIG_CD_LAVANCE')then
     				
    	begin
    		--Sentencia de la consulta
             v_gestion_inicio :=(select g.fecha_ini from ssig.tplan p JOIN param.tgestion g on g.id_gestion = p.id_gestion WHERE P.id_plan=v_parametros.id_plan  limit 1);   
             v_gestion_fin :=(select g.fecha_fin from ssig.tplan p JOIN param.tgestion g on g.id_gestion = p.id_gestion WHERE P.id_plan=v_parametros.id_plan  limit 1);
             v_valor_frecuencia := '1' || ' MONTH';
             v_meses :='';
             WHILE ((SELECT CAST(v_gestion_inicio AS DATE)) <= v_gestion_fin ) loop
                 
                  if((SELECT date_part('month',CAST(v_gestion_inicio AS DATE)))=1)then
                      v_meses := v_meses || 'ene'|| (SELECT substring( date_part('year',CAST(v_gestion_inicio AS DATE))::VARCHAR from 3 for 4)) || ',';
                  end if;
                  if((SELECT date_part('month',CAST(v_gestion_inicio AS DATE)))=2)then
                      v_meses := v_meses || 'feb'|| (SELECT substring( date_part('year',CAST(v_gestion_inicio AS DATE))::VARCHAR from 3 for 4)) || ',';
                  end if;
                  if((SELECT date_part('month',CAST(v_gestion_inicio AS DATE)))=3)then
                      v_meses := v_meses || 'mar'|| (SELECT substring( date_part('year',CAST(v_gestion_inicio AS DATE))::VARCHAR from 3 for 4)) || ',';
                  end if;
                  if((SELECT  date_part('month',CAST(v_gestion_inicio AS DATE)))=4)then
                      v_meses := v_meses || 'abr'|| (SELECT substring( date_part('year',CAST(v_gestion_inicio AS DATE))::VARCHAR from 3 for 4)) || ',';
                  end if;
                  if((SELECT date_part('month',CAST(v_gestion_inicio AS DATE)))=5)then
                      v_meses := v_meses || 'may'|| (SELECT substring( date_part('year',CAST(v_gestion_inicio AS DATE))::VARCHAR from 3 for 4)) || ',';
                  end if;
                  if((SELECT date_part('month',CAST(v_gestion_inicio AS DATE)))=6)then
                      v_meses := v_meses || 'jun'|| (SELECT substring( date_part('year',CAST(v_gestion_inicio AS DATE))::VARCHAR from 3 for 4)) || ',';
                  end if;
                  if((SELECT date_part('month',CAST(v_gestion_inicio AS DATE)))=7)then
                      v_meses := v_meses || 'jul'|| (SELECT substring( date_part('year',CAST(v_gestion_inicio AS DATE))::VARCHAR from 3 for 4)) || ',';
                  end if;
                  if((SELECT date_part('month',CAST(v_gestion_inicio AS DATE)))=8)then
                      v_meses := v_meses || 'agos'|| (SELECT substring( date_part('year',CAST(v_gestion_inicio AS DATE))::VARCHAR from 3 for 4)) || ',';
                  end if;
                  if((SELECT date_part('month',CAST(v_gestion_inicio AS DATE)))=9)then
                      v_meses := v_meses || 'sep'|| (SELECT substring( date_part('year',CAST(v_gestion_inicio AS DATE))::VARCHAR from 3 for 4)) || ',';
                  end if;   
                  if((SELECT date_part('month',CAST(v_gestion_inicio AS DATE)))=10)then
                      v_meses := v_meses || 'oct'|| (SELECT substring( date_part('year',CAST(v_gestion_inicio AS DATE))::VARCHAR from 3 for 4)) || ',';
                  end if;  
                  if((SELECT date_part('month',CAST(v_gestion_inicio AS DATE)))=11)then
                      v_meses := v_meses || 'nov'|| (SELECT substring( date_part('year',CAST(v_gestion_inicio AS DATE))::VARCHAR from 3 for 4)) || ',';
                  end if; 
                  if((SELECT date_part('month',CAST(v_gestion_inicio AS DATE)))=12)then
                      v_meses := v_meses || 'dic'|| (SELECT substring( date_part('year',CAST(v_gestion_inicio AS DATE))::VARCHAR from 3 for 4)) || ',';
                  end if; 
 
                  v_gestion_contador=(SELECT CAST(v_gestion_inicio AS DATE) + CAST(v_valor_frecuencia AS INTERVAL));         
                  v_gestion_inicio=v_gestion_contador;
                  
             end loop;
		         v_meses := v_meses || 'total';
                
			--Definicion de la respuesta

			--Devuelve la respuesta
            RAISE NOTICE 'meses %',v_meses;
            --RAISE EXCEPTION 'Error provocado Juan %',v_meses;
        --Devuelve la respuesta
          v_resp = pxp.f_agrega_clave(v_resp,'mensaje','estado gestion'); 
          v_resp = pxp.f_agrega_clave(v_resp,'Meses','%'||v_meses||'%'::varchar);
          return v_resp;
						
		end;
        
    	/*********************************    
 	#TRANSACCION:  'SSIG_ES_APREV_INS'
 	#DESCRIPCION:	Validar si la gestion seleccionada por el usuario estan aprobada o desaprobada
 	#AUTOR:		JUAN	
 	#FECHA:		12-07-2017 14:01:15
	***********************************/

	elsif(p_transaccion='SSIG_ES_APREV_INS')then

		begin
	   --Sentencia de la modificacion
       --RAISE EXCEPTION 'Error provocado linea avance %', 'id_plan '||v_parametros.id_plan||' mes '||v_parametros.mes;
	   v_estado_linea_avance :=	(SELECT la.aprobado_real::BOOLEAN from ssig.tlinea l 
                        join ssig.tplan p
                        on p.id_plan=l.id_plan 
                        join ssig.tlinea_avance la  on la.id_linea=l.id_linea
                        where p.id_plan=v_parametros.id_plan::INTEGER and la.mes=v_parametros.mes::VARCHAR order by la.id_linea_avance LIMIT 1)::BOOLEAN;
       
             
                        
             /*UPDATE ssig.tlinea_avance
                SET aprobado_real = v_param[v_i]::NUMERIC
                WHERE id_linea_avance = v_param[v_i+1]::INTEGER;*/
                        
       
        --Devuelve la respuesta
          v_resp = pxp.f_agrega_clave(v_resp,'mensaje','estado gestion'); 
          v_resp = pxp.f_agrega_clave(v_resp,'estado','%'||v_estado_linea_avance||'%'::varchar);
            return v_resp;
            
	end; 
    	/*********************************    
 	#TRANSACCION:  'SSIG_AP_LAVANCE_INS'
 	#DESCRIPCION:	CAMBIAR EL ESTADO DE APROBADO REAL DE LINEA AVANCE EN EL MODULO DE AVANCE REAL
 	#AUTOR:		JUAN	
 	#FECHA:		12-07-2017 14:01:15
	***********************************/

	elsif(p_transaccion='SSIG_AP_LAVANCE_INS')then

		begin
	   --Sentencia de la modificacion
       --RAISE EXCEPTION 'Error provocado linea avance %', 'id_plan '||v_parametros.id_plan||' mes '||v_parametros.mes;
	   for item in (SELECT la.aprobado_real::INTEGER,la.id_linea_avance from ssig.tlinea l 
                        join ssig.tplan p
                        on p.id_plan=l.id_plan 
                        join ssig.tlinea_avance la  on la.id_linea=l.id_linea
                        where p.id_plan=v_parametros.id_plan::INTEGER and la.mes=v_parametros.mes::VARCHAR order by la.id_linea_avance) LOOP
       
                UPDATE ssig.tlinea_avance
                SET aprobado_real = v_parametros.estado::BOOLEAN
                WHERE id_linea_avance = item.id_linea_avance::INTEGER;
        
       END LOOP;     
                        
     
                        
       
        --Devuelve la respuesta
         -- v_resp = pxp.f_agrega_clave(v_resp,'mensaje','estado gestion'); 
         -- v_resp = pxp.f_agrega_clave(v_resp,'aprobado','%'||v_estado_linea_avance||'%'::varchar);
            return v_resp;
            
	end;

	/*********************************    
 	#TRANSACCION:  'SSIG_LIAV_ELI'
 	#DESCRIPCION:	Eliminacion de registros
 	#AUTOR:		admin	
 	#FECHA:		19-02-2017 02:21:07
	***********************************/

	elsif(p_transaccion='SSIG_LIAV_ELI')then

		begin
/*			--Sentencia de la eliminacion
			delete from ssig.tlinea_avance
            where id_linea_avance=v_parametros.id_linea_avance;
               
            --Definicion de la respuesta
            v_resp = pxp.f_agrega_clave(v_resp,'mensaje','Linea avance eliminado(a)'); 
            v_resp = pxp.f_agrega_clave(v_resp,'id_linea_avance',v_parametros.id_linea_avance::varchar);
              
            --Devuelve la respuesta*/
            
         DELETE FROM ssig.tlinea_avance
        WHERE id_linea = v_parametros.id_linea;    
            
        DELETE FROM ssig.tlinea
        WHERE id_linea = v_parametros.id_linea;

        --Definicion de la respuesta
        v_resp = pxp.f_agrega_clave(v_resp, 'mensaje', 'Definición de líneas eliminado(a)');
        v_resp = pxp.f_agrega_clave(v_resp, 'id_linea', v_parametros.id_linea :: VARCHAR);
       -- raise notice 'varibale juan %',v_parametros.id_linea;
       -- RAISE EXCEPTION 'error provocado %', v_parametros.id_linea;
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
