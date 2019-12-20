CREATE OR REPLACE FUNCTION ssig.ft_linea_ime (
  p_administrador integer,
  p_id_usuario integer,
  p_tabla varchar,
  p_transaccion varchar
)
RETURNS varchar AS
$body$
/**************************************************************************
 SISTEMA:		Seguimiento integral de gestión
 FUNCION: 		ssig.ft_linea_ime
 DESCRIPCION:   Funcion que gestiona las operaciones basicas (inserciones, modificaciones, eliminaciones de la tabla 'ssig.tlinea'
 AUTOR: 		 (admin)
 FECHA:	        11-04-2017 20:20:49
 COMENTARIOS:	
***************************************************************************
 HISTORIAL DE MODIFICACIONES:

 DESCRIPCION:	
 AUTOR:			
 FECHA:		
***************************************************************************/

DECLARE

  v_nro_requerimiento INTEGER;
  v_parametros        RECORD;
  v_id_requerimiento  INTEGER;
  v_resp              VARCHAR;
  v_nombre_funcion    TEXT;
  v_mensaje_error     TEXT;
  v_id_linea          INTEGER;
  v_id_linea_padre    INTEGER;
  va_id_funcionarios  VARCHAR [];
  v_id_funcionario    INTEGER;
  
  v_gestion_inicio    date;
  v_gestion_fin       date;
  v_valor_frecuencia  text;
  v_gestion_contador  date;
  v_meses             text;
  
  v_consulta_avance_previsto record;
  v_cod_hijos_linea         VARCHAR [];
  v_id_linea_hijo           INTEGER;
  v_cod_hijos_linea_nivel3  VARCHAR [];
  v_id_linea_hijo_nivel3    INTEGER;     
  v_meses_linea             VARCHAR [];
  v_mes                     VARCHAR;
  v_peso_aux                INTEGER;

BEGIN

  v_nombre_funcion = 'ssig.ft_linea_ime';
  v_parametros = pxp.f_get_record(p_tabla);

  /*********************************
   #TRANSACCION:  'SSIG_LINEA_INS'
   #DESCRIPCION:	Insercion de registros
   #AUTOR:		admin
   #FECHA:		11-04-2017 20:20:49
  ***********************************/

  IF (p_transaccion = 'SSIG_LINEA_INS')
  THEN

    BEGIN

      IF v_parametros.id_linea_padre != 'id' AND v_parametros.id_linea_padre != ''
      THEN
        v_id_linea_padre = v_parametros.id_linea_padre :: INTEGER;
      END IF;

      --Sentencia de la insercion
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
        fecha_mod,
        orden_logico
      ) VALUES (
        v_id_linea_padre,
        v_parametros.id_plan,
        'activo',
        v_parametros.nivel,
        v_parametros.nombre_linea,
        v_parametros.peso,
        now(),
        v_parametros._nombre_usuario_ai,
        p_id_usuario,
        v_parametros._id_usuario_ai,
        NULL,
        NULL,
        v_parametros.orden_logico::INTEGER

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
                      v_meses := 'ene'|| (SELECT substring( date_part('year',CAST(v_gestion_inicio AS DATE))::VARCHAR from 3 for 4));
                        
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
                                                      fecha_mod,
                                                      dato) 
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
                                                      null,
                                                      ''
                                                );
                                               -- RAISE EXCEPTION 'Error provocado Juan %',v_meses::VARCHAR;
                     
                  end if;
                  if((SELECT date_part('month',CAST(v_gestion_inicio AS DATE)))=2)then
                      v_meses := 'feb'|| (SELECT substring( date_part('year',CAST(v_gestion_inicio AS DATE))::VARCHAR from 3 for 4));
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
                                                      fecha_mod,
                                                      dato) 
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
                                                      null,
                                                      ''
                                                );
                  end if;
                  if((SELECT date_part('month',CAST(v_gestion_inicio AS DATE)))=3)then
                      v_meses := 'mar'|| (SELECT substring( date_part('year',CAST(v_gestion_inicio AS DATE))::VARCHAR from 3 for 4));
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
                                                      fecha_mod,
                                                      dato) 
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
                                                      null,
                                                      ''
                                                );
                  end if;
                  if((SELECT  date_part('month',CAST(v_gestion_inicio AS DATE)))=4)then
                      v_meses := 'abr'|| (SELECT substring( date_part('year',CAST(v_gestion_inicio AS DATE))::VARCHAR from 3 for 4));
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
                                                      fecha_mod,
                                                      dato) 
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
                                                      null,
                                                      ''
                                                );
                  end if;
                  if((SELECT date_part('month',CAST(v_gestion_inicio AS DATE)))=5)then
                      v_meses := 'may'|| (SELECT substring( date_part('year',CAST(v_gestion_inicio AS DATE))::VARCHAR from 3 for 4));
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
                                                      fecha_mod,
                                                      dato) 
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
                                                      null,
                                                      ''
                                                );
                  end if;
                  if((SELECT date_part('month',CAST(v_gestion_inicio AS DATE)))=6)then
                      v_meses :=  'jun'|| (SELECT substring( date_part('year',CAST(v_gestion_inicio AS DATE))::VARCHAR from 3 for 4)) ;
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
                                                      fecha_mod,
                                                      dato) 
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
                                                      null,
                                                      ''
                                                );
                  end if;
                  if((SELECT date_part('month',CAST(v_gestion_inicio AS DATE)))=7)then
                      v_meses := 'jul'|| (SELECT substring( date_part('year',CAST(v_gestion_inicio AS DATE))::VARCHAR from 3 for 4)) ;
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
                                                      fecha_mod,
                                                      dato) 
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
                                                      null,
                                                      ''
                                                );
                  end if;
                  if((SELECT date_part('month',CAST(v_gestion_inicio AS DATE)))=8)then
                      v_meses := 'agos'|| (SELECT substring( date_part('year',CAST(v_gestion_inicio AS DATE))::VARCHAR from 3 for 4)) ;
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
                                                      fecha_mod,
                                                      dato) 
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
                                                      null,
                                                      ''
                                                );
                  end if;
                  if((SELECT date_part('month',CAST(v_gestion_inicio AS DATE)))=9)then
                      v_meses := 'sep'|| (SELECT substring( date_part('year',CAST(v_gestion_inicio AS DATE))::VARCHAR from 3 for 4)) ;
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
                                                      fecha_mod,
                                                      dato) 
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
                                                      null,
                                                      ''
                                                );
                  end if;   
                  if((SELECT date_part('month',CAST(v_gestion_inicio AS DATE)))=10)then
                      v_meses := 'oct'|| (SELECT substring( date_part('year',CAST(v_gestion_inicio AS DATE))::VARCHAR from 3 for 4)) ;
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
                                                      fecha_mod,
                                                      dato) 
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
                                                      null,
                                                      ''
                                                );
                  end if;  
                  if((SELECT date_part('month',CAST(v_gestion_inicio AS DATE)))=11)then
                      v_meses :=  'nov'|| (SELECT substring( date_part('year',CAST(v_gestion_inicio AS DATE))::VARCHAR from 3 for 4)) ;
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
                                                      fecha_mod,
                                                      dato) 
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
                                                      null,
                                                      ''
                                                );
                  end if; 
                  if((SELECT date_part('month',CAST(v_gestion_inicio AS DATE)))=12)then
                      v_meses :=  'dic'|| (SELECT substring( date_part('year',CAST(v_gestion_inicio AS DATE))::VARCHAR from 3 for 4));
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
                                                      fecha_mod,
                                                      dato) 
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
                                                      null,
                                                      ''
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

      --Definicion de la respuesta
      v_resp = pxp.f_agrega_clave(v_resp, 'mensaje',
                                  'Definición de líneas almacenado(a) con exito (id_linea' || v_id_linea || ')');
      v_resp = pxp.f_agrega_clave(v_resp, 'id_linea', v_id_linea :: VARCHAR);

      --Devuelve la respuesta
      RETURN v_resp;

    END;

    /*********************************
     #TRANSACCION:  'SSIG_LINEA_MOD'
     #DESCRIPCION:	Modificacion de registros
     #AUTOR:		admin
     #FECHA:		11-04-2017 20:20:49
    ***********************************/

  ELSIF (p_transaccion = 'SSIG_LINEA_MOD')
    THEN

      BEGIN

        IF v_parametros.id_linea_padre != 'id' AND v_parametros.id_linea_padre != '' THEN
          v_id_linea_padre = v_parametros.id_linea_padre :: INTEGER;
        END IF;
        --esta variable v_peso_aux sirve para recalcular o no los avances previsto o no casi al final de esta transaccion
        v_peso_aux:=(select l.peso::INTEGER from ssig.tlinea l where l.id_linea = v_parametros.id_linea)::INTEGER;
        --Sentencia de la modificacion
        UPDATE ssig.tlinea
        SET
          id_linea_padre = v_id_linea_padre,
          id_plan        = v_parametros.id_plan,
          nivel          = v_parametros.nivel,
          nombre_linea   = v_parametros.nombre_linea,
          peso           = v_parametros.peso,
          id_usuario_mod = p_id_usuario,
          fecha_mod      = now(),
          id_usuario_ai  = v_parametros._id_usuario_ai,
          usuario_ai     = v_parametros._nombre_usuario_ai,
          orden_logico   = v_parametros.orden_logico::INTEGER
        WHERE id_linea = v_parametros.id_linea;



        -- Editar curso funcionario
        DELETE FROM ssig.tlinea_funcionario lf
        WHERE lf.id_linea = v_parametros.id_linea;
        va_id_funcionarios := string_to_array(v_parametros.id_funcionarios, ',');
        FOREACH v_id_funcionario IN ARRAY va_id_funcionarios LOOP
          INSERT INTO ssig.tlinea_funcionario (
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
            v_parametros.id_linea,
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


        ---------------------------------------inicio para recalcular los datos de avance previsto ----------------------------------
        --agregar a la condicion que solo entre a los if en caso de modificar el peso
              IF((v_parametros.nivel = 1 or v_parametros.nivel = 2) and (v_peso_aux::INTEGER != v_parametros.peso::INTEGER))then
                      -- RAISE EXCEPTION 'testeos % ','modificado peso';
                      -- Borrar contenido de todos sus meses del id padre
                      UPDATE ssig.tlinea_avance
                      SET
                        avance_previsto = 0.00::NUMERIC,
                        avance_real= 0.00::NUMERIC,
                        aprobado_real = 'FALSE'::BOOLEAN
                      WHERE id_linea = v_parametros.id_linea_padre::INTEGER;
                      
                      IF(v_parametros.nivel = 2)then
                              UPDATE ssig.tlinea_avance
                              SET
                                avance_previsto = 0.00::NUMERIC,
                                avance_real= 0.00::NUMERIC,
                                aprobado_real = 'FALSE'::BOOLEAN
                              WHERE id_linea = (SELECT (SELECT ll.id_linea FROM ssig.tlinea ll where ll.id_linea=l.id_linea_padre::INTEGER) FROM ssig.tlinea l where l.id_linea=v_parametros.id_linea_padre::INTEGER);

                              for v_consulta_avance_previsto in (select array_to_string(ARRAY_AGG(ll.id_linea), ',', '')::VARCHAR as cod_hijos ,
                                                         (select array_to_string(ARRAY_AGG(la.mes), ',', '')::VARCHAR from ssig.tlinea_avance la where la.id_linea=v_parametros.id_linea_padre::INTEGER) as meses,
                                                         (select lll.nivel from ssig.tlinea lll where lll.id_linea=v_parametros.id_linea_padre::INTEGER) as nivel,
                                                         (select lll.id_linea from ssig.tlinea lll where lll.id_linea=v_parametros.id_linea_padre::INTEGER) as cod_linea,
                                                         (select lll.id_linea_padre from ssig.tlinea lll where lll.id_linea=v_parametros.id_linea_padre::INTEGER) as cod_padre,
                                                         (select lll.peso from ssig.tlinea lll where lll.id_linea=170) as peso
                                                         from ssig.tlinea ll where ll.id_linea_padre = v_parametros.id_linea_padre::INTEGER group by ll.nivel )  loop
                                                         
                                    v_cod_hijos_linea := string_to_array(v_consulta_avance_previsto.cod_hijos, ',');
                                    v_meses_linea := string_to_array(v_consulta_avance_previsto.meses, ',');
                                    
                                    FOREACH v_id_linea_hijo IN ARRAY v_cod_hijos_linea LOOP
                                        FOREACH v_mes IN ARRAY v_meses_linea LOOP
                                            --RAISE EXCEPTION 'error provocado para calculos %', (SELECT ((la.avance_previsto * (SELECT ll.peso from ssig.tlinea ll where ll.id_linea=v_id_linea_hijo::integer))/100)+(SELECT lava.avance_previsto from ssig.tlinea_avance lava where lava.id_linea=v_parametros.id_linea_padre::INTEGER and lava.mes::VARCHAR = v_mes::VARCHAR) from ssig.tlinea_avance la where la.id_linea = v_id_linea_hijo::integer and la.mes=v_mes::VARCHAR);
                                            UPDATE ssig.tlinea_avance
                                            SET
                                               --formula ((avance_previsto*peso_linea)/100)
                                               avance_previsto = (SELECT ((la.avance_previsto * (SELECT ll.peso from ssig.tlinea ll where ll.id_linea=v_id_linea_hijo::integer))/100)+(SELECT lava.avance_previsto from ssig.tlinea_avance lava where lava.id_linea=v_parametros.id_linea_padre::INTEGER and lava.mes::VARCHAR = v_mes::VARCHAR) from ssig.tlinea_avance la where la.id_linea = v_id_linea_hijo::integer and la.mes=v_mes::VARCHAR),
                                               avance_real = (SELECT ((la.avance_real * (SELECT ll.peso from ssig.tlinea ll where ll.id_linea=v_id_linea_hijo::integer))/100)+(SELECT lava.avance_real from ssig.tlinea_avance lava where lava.id_linea=v_parametros.id_linea_padre::INTEGER and lava.mes::VARCHAR = v_mes::VARCHAR) from ssig.tlinea_avance la where la.id_linea = v_id_linea_hijo::integer and la.mes=v_mes::VARCHAR)
                                            WHERE id_linea = v_parametros.id_linea_padre::INTEGER and mes = v_mes::VARCHAR;       
                                        END LOOP;
                                    END LOOP;
                              END LOOP;
                              v_parametros.id_linea_padre:=(SELECT (SELECT ll.id_linea FROM ssig.tlinea ll where ll.id_linea=l.id_linea_padre::INTEGER) FROM ssig.tlinea l where l.id_linea=v_parametros.id_linea_padre::INTEGER);
                              
                      END IF;
                      
                      --este for es considerado como un if .... siempre contiene un solo registro con 5 registros para los calculos
                      for v_consulta_avance_previsto in (select array_to_string(ARRAY_AGG(ll.id_linea), ',', '')::VARCHAR as cod_hijos ,
                                                 (select array_to_string(ARRAY_AGG(la.mes), ',', '')::VARCHAR from ssig.tlinea_avance la where la.id_linea=v_parametros.id_linea_padre::INTEGER) as meses,
                                                 (select lll.nivel from ssig.tlinea lll where lll.id_linea=v_parametros.id_linea_padre::INTEGER) as nivel,
                                                 (select lll.id_linea from ssig.tlinea lll where lll.id_linea=v_parametros.id_linea_padre::INTEGER) as cod_linea,
                                                 (select lll.id_linea_padre from ssig.tlinea lll where lll.id_linea=v_parametros.id_linea_padre::INTEGER) as cod_padre,
                                                 (select lll.peso from ssig.tlinea lll where lll.id_linea=v_parametros.id_linea_padre::INTEGER) as peso
                                                 from ssig.tlinea ll where ll.id_linea_padre = v_parametros.id_linea_padre::INTEGER group by ll.nivel )  loop
                                                 
                            v_cod_hijos_linea := string_to_array(v_consulta_avance_previsto.cod_hijos, ',');
                            v_meses_linea := string_to_array(v_consulta_avance_previsto.meses, ',');
                            
                            FOREACH v_id_linea_hijo IN ARRAY v_cod_hijos_linea LOOP
                                FOREACH v_mes IN ARRAY v_meses_linea LOOP
                                    --RAISE EXCEPTION 'error provocado para calculos %', (SELECT ((la.avance_previsto * (SELECT ll.peso from ssig.tlinea ll where ll.id_linea=v_id_linea_hijo::integer))/100)+(SELECT lava.avance_previsto from ssig.tlinea_avance lava where lava.id_linea=v_parametros.id_linea_padre::INTEGER and lava.mes::VARCHAR = v_mes::VARCHAR) from ssig.tlinea_avance la where la.id_linea = v_id_linea_hijo::integer and la.mes=v_mes::VARCHAR);
                                    UPDATE ssig.tlinea_avance
                                    SET
                                       --formula ((avance_previsto*peso_linea)/100)
                                       avance_previsto = (SELECT ((la.avance_previsto * (SELECT ll.peso from ssig.tlinea ll where ll.id_linea=v_id_linea_hijo::integer))/100)+(SELECT lava.avance_previsto from ssig.tlinea_avance lava where lava.id_linea=v_parametros.id_linea_padre::INTEGER and lava.mes::VARCHAR = v_mes::VARCHAR) from ssig.tlinea_avance la where la.id_linea = v_id_linea_hijo::integer and la.mes=v_mes::VARCHAR),
                                       avance_real     = (SELECT ((la.avance_real * (SELECT ll.peso from ssig.tlinea ll where ll.id_linea=v_id_linea_hijo::integer))/100)+(SELECT lava.avance_real from ssig.tlinea_avance lava where lava.id_linea=v_parametros.id_linea_padre::INTEGER and lava.mes::VARCHAR = v_mes::VARCHAR) from ssig.tlinea_avance la where la.id_linea = v_id_linea_hijo::integer and la.mes=v_mes::VARCHAR)
                                    WHERE id_linea = v_parametros.id_linea_padre::INTEGER and mes = v_mes::VARCHAR;       
                                END LOOP;
                            END LOOP;
                            --restaurar todo avance real
                            /*for v_consulta_avance_previsto in (select p.id_plan,la.avance_real,la.aprobado_real,la.id_linea_avance,la.mes 
                                                             from ssig.tlinea_avance la 
                                                             join ssig.tlinea l on l.id_linea=la.id_linea 
                                                             join ssig.tplan p on p.id_plan = l.id_plan
                                                             where p.id_plan = (SELECT lin.id_plan from ssig.tlinea lin where lin.id_linea=v_parametros.id_linea))  loop
                                  UPDATE ssig.tlinea_avance
                                  SET
                                    avance_real= 0.00::NUMERIC,
                                    aprobado_real = 'FALSE'::BOOLEAN,
                                    comentario ='',
                                    dato=''
                                  WHERE id_linea_avance = v_consulta_avance_previsto.id_linea_avance::INTEGER;
                          
                          END LOOP;*/                                   
                          --fin restauracion avance real       
                    END LOOP;
        END IF;
       --------------------------------------- fin de calculos de avance previsto  -------------------------------------------------
       
      
      
       --Definicion de la respuesta
        v_resp = pxp.f_agrega_clave(v_resp, 'mensaje', 'Definición de líneas modificado(a)');
        v_resp = pxp.f_agrega_clave(v_resp, 'id_linea', v_parametros.id_linea :: VARCHAR);

        --Devuelve la respuesta
        RETURN v_resp;

      END;

      /*********************************
       #TRANSACCION:  'SSIG_LINEA_ELI'
       #DESCRIPCION:	Eliminacion de registros
       #AUTOR:		admin
       #FECHA:		11-04-2017 20:20:49
      ***********************************/

  ELSIF (p_transaccion = 'SSIG_LINEA_ELI')
    THEN

      BEGIN
        --Sentencia de la eliminacion
        DELETE FROM ssig.tlinea
        WHERE id_linea = v_parametros.id_linea;
        
        ---------------------------------------inicio para recalcular los datos de avance previsto ----------------------------------
        --agregar a la condicion que solo entre a los if en casod e modificar el peso
              IF (v_parametros.nivel = 1 or v_parametros.nivel = 2)then
                      --RAISE EXCEPTION 'testeos % ',v_parametros.id_linea;
                      -- Borrar contenido de todos sus meses del id padre
                      UPDATE ssig.tlinea_avance
                      SET
                        avance_previsto = 0.00::NUMERIC,
                        avance_real= 0.00::NUMERIC,
                        aprobado_real = 'FALSE'::BOOLEAN
                      WHERE id_linea = v_parametros.id_linea_padre::INTEGER;
                      
                      IF(v_parametros.nivel = 2)then
                              UPDATE ssig.tlinea_avance
                              SET
                                avance_previsto = 0.00::NUMERIC,
                                avance_real= 0.00::NUMERIC,
                                aprobado_real = 'FALSE'::BOOLEAN
                              WHERE id_linea = (SELECT (SELECT ll.id_linea FROM ssig.tlinea ll where ll.id_linea=l.id_linea_padre::INTEGER) FROM ssig.tlinea l where l.id_linea=v_parametros.id_linea_padre::INTEGER);

                              for v_consulta_avance_previsto in (select array_to_string(ARRAY_AGG(ll.id_linea), ',', '')::VARCHAR as cod_hijos ,
                                                         (select array_to_string(ARRAY_AGG(la.mes), ',', '')::VARCHAR from ssig.tlinea_avance la where la.id_linea=v_parametros.id_linea_padre::INTEGER) as meses,
                                                         (select lll.nivel from ssig.tlinea lll where lll.id_linea=v_parametros.id_linea_padre::INTEGER) as nivel,
                                                         (select lll.id_linea from ssig.tlinea lll where lll.id_linea=v_parametros.id_linea_padre::INTEGER) as cod_linea,
                                                         (select lll.id_linea_padre from ssig.tlinea lll where lll.id_linea=v_parametros.id_linea_padre::INTEGER) as cod_padre,
                                                         (select lll.peso from ssig.tlinea lll where lll.id_linea=v_parametros.id_linea_padre::INTEGER) as peso
                                                         from ssig.tlinea ll where ll.id_linea_padre = v_parametros.id_linea_padre::INTEGER group by ll.nivel )  loop
                                                         
                                    v_cod_hijos_linea := string_to_array(v_consulta_avance_previsto.cod_hijos, ',');
                                    v_meses_linea := string_to_array(v_consulta_avance_previsto.meses, ',');
                                    
                                    FOREACH v_id_linea_hijo IN ARRAY v_cod_hijos_linea LOOP
                                        FOREACH v_mes IN ARRAY v_meses_linea LOOP
                                            --RAISE EXCEPTION 'error provocado para calculos %', (SELECT ((la.avance_previsto * (SELECT ll.peso from ssig.tlinea ll where ll.id_linea=v_id_linea_hijo::integer))/100)+(SELECT lava.avance_previsto from ssig.tlinea_avance lava where lava.id_linea=v_parametros.id_linea_padre::INTEGER and lava.mes::VARCHAR = v_mes::VARCHAR) from ssig.tlinea_avance la where la.id_linea = v_id_linea_hijo::integer and la.mes=v_mes::VARCHAR);
                                            UPDATE ssig.tlinea_avance
                                            SET
                                               --formula ((avance_previsto*peso_linea)/100)
                                               avance_previsto = (SELECT ((la.avance_previsto * (SELECT ll.peso from ssig.tlinea ll where ll.id_linea=v_id_linea_hijo::integer))/100)+(SELECT lava.avance_previsto from ssig.tlinea_avance lava where lava.id_linea=v_parametros.id_linea_padre::INTEGER and lava.mes::VARCHAR = v_mes::VARCHAR) from ssig.tlinea_avance la where la.id_linea = v_id_linea_hijo::integer and la.mes=v_mes::VARCHAR),
                                               avance_real = (SELECT ((la.avance_real * (SELECT ll.peso from ssig.tlinea ll where ll.id_linea=v_id_linea_hijo::integer))/100)+(SELECT lava.avance_real from ssig.tlinea_avance lava where lava.id_linea=v_parametros.id_linea_padre::INTEGER and lava.mes::VARCHAR = v_mes::VARCHAR) from ssig.tlinea_avance la where la.id_linea = v_id_linea_hijo::integer and la.mes=v_mes::VARCHAR)
                                            WHERE id_linea = v_parametros.id_linea_padre::INTEGER and mes = v_mes::VARCHAR;       
                                        END LOOP;
                                    END LOOP;
                              END LOOP;
                              v_parametros.id_linea_padre:=(SELECT (SELECT ll.id_linea FROM ssig.tlinea ll where ll.id_linea=l.id_linea_padre::INTEGER) FROM ssig.tlinea l where l.id_linea=v_parametros.id_linea_padre::INTEGER);
                              
                      END IF;
                      
                      --este for es considerado como un if .... siempre contiene un solo registro con 5 registros para los calculos
                      for v_consulta_avance_previsto in (select array_to_string(ARRAY_AGG(ll.id_linea), ',', '')::VARCHAR as cod_hijos ,
                                                 (select array_to_string(ARRAY_AGG(la.mes), ',', '')::VARCHAR from ssig.tlinea_avance la where la.id_linea=v_parametros.id_linea_padre::INTEGER) as meses,
                                                 (select lll.nivel from ssig.tlinea lll where lll.id_linea=v_parametros.id_linea_padre::INTEGER) as nivel,
                                                 (select lll.id_linea from ssig.tlinea lll where lll.id_linea=v_parametros.id_linea_padre::INTEGER) as cod_linea,
                                                 (select lll.id_linea_padre from ssig.tlinea lll where lll.id_linea=v_parametros.id_linea_padre::INTEGER) as cod_padre,
                                                 (select lll.peso from ssig.tlinea lll where lll.id_linea=v_parametros.id_linea_padre::INTEGER) as peso
                                                 from ssig.tlinea ll where ll.id_linea_padre = v_parametros.id_linea_padre::INTEGER group by ll.nivel )  loop
                                                 
                            v_cod_hijos_linea := string_to_array(v_consulta_avance_previsto.cod_hijos, ',');
                            v_meses_linea := string_to_array(v_consulta_avance_previsto.meses, ',');
                            
                            FOREACH v_id_linea_hijo IN ARRAY v_cod_hijos_linea LOOP
                                FOREACH v_mes IN ARRAY v_meses_linea LOOP
                                    --RAISE EXCEPTION 'error provocado para calculos %', (SELECT ((la.avance_previsto * (SELECT ll.peso from ssig.tlinea ll where ll.id_linea=v_id_linea_hijo::integer))/100)+(SELECT lava.avance_previsto from ssig.tlinea_avance lava where lava.id_linea=v_parametros.id_linea_padre::INTEGER and lava.mes::VARCHAR = v_mes::VARCHAR) from ssig.tlinea_avance la where la.id_linea = v_id_linea_hijo::integer and la.mes=v_mes::VARCHAR);
                                    UPDATE ssig.tlinea_avance
                                    SET
                                       --formula ((avance_previsto*peso_linea)/100)
                                       avance_previsto = (SELECT ((la.avance_previsto * (SELECT ll.peso from ssig.tlinea ll where ll.id_linea=v_id_linea_hijo::integer))/100)+(SELECT lava.avance_previsto from ssig.tlinea_avance lava where lava.id_linea=v_parametros.id_linea_padre::INTEGER and lava.mes::VARCHAR = v_mes::VARCHAR) from ssig.tlinea_avance la where la.id_linea = v_id_linea_hijo::integer and la.mes=v_mes::VARCHAR),
                                       avance_real = (SELECT ((la.avance_real * (SELECT ll.peso from ssig.tlinea ll where ll.id_linea=v_id_linea_hijo::integer))/100)+(SELECT lava.avance_real from ssig.tlinea_avance lava where lava.id_linea=v_parametros.id_linea_padre::INTEGER and lava.mes::VARCHAR = v_mes::VARCHAR) from ssig.tlinea_avance la where la.id_linea = v_id_linea_hijo::integer and la.mes=v_mes::VARCHAR)
                                    WHERE id_linea = v_parametros.id_linea_padre::INTEGER and mes = v_mes::VARCHAR;       
                                END LOOP;
                            END LOOP;
                            --restaurar todo avance real
                            /*for v_consulta_avance_previsto in (select p.id_plan,la.avance_real,la.aprobado_real,la.id_linea_avance,la.mes 
                                                             from ssig.tlinea_avance la 
                                                             join ssig.tlinea l on l.id_linea=la.id_linea 
                                                             join ssig.tplan p on p.id_plan = l.id_plan
                                                             where p.id_plan = (SELECT lin.id_plan from ssig.tlinea lin where lin.id_linea = v_parametros.id_linea_padre::INTEGER))  loop
                                  UPDATE ssig.tlinea_avance
                                  SET
                                    avance_real= 0.00::NUMERIC,
                                    aprobado_real = 'FALSE'::BOOLEAN,
                                    comentario ='',
                                    dato=''
                                  WHERE id_linea_avance = v_consulta_avance_previsto.id_linea_avance::INTEGER;
                          
                          END LOOP;*/                                   
                          --fin restauracion avance real       
                    END LOOP;
        END IF;
       --------------------------------------- fin de calculos de avance previsto  -------------------------------------------------

        --Definicion de la respuesta
        v_resp = pxp.f_agrega_clave(v_resp, 'mensaje', 'Definición de líneas eliminado(a)');
        v_resp = pxp.f_agrega_clave(v_resp, 'id_linea', v_parametros.id_linea :: VARCHAR);

        --Devuelve la respuesta
        RETURN v_resp;

      END;

  ELSE

    RAISE EXCEPTION 'Transaccion inexistente: %', p_transaccion;

  END IF;

  EXCEPTION

  WHEN OTHERS
    THEN
      v_resp = '';
      v_resp = pxp.f_agrega_clave(v_resp, 'mensaje', SQLERRM);
      v_resp = pxp.f_agrega_clave(v_resp, 'codigo_error', SQLSTATE);
      v_resp = pxp.f_agrega_clave(v_resp, 'procedimientos', v_nombre_funcion);
      RAISE EXCEPTION '%', v_resp;

END;
$body$
LANGUAGE 'plpgsql'
VOLATILE
CALLED ON NULL INPUT
SECURITY INVOKER
COST 100;
