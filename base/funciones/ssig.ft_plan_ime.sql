--------------- SQL ---------------

CREATE OR REPLACE FUNCTION ssig.ft_plan_ime (
  p_administrador integer,
  p_id_usuario integer,
  p_tabla varchar,
  p_transaccion varchar
)
RETURNS varchar AS
$body$
/**************************************************************************
 SISTEMA:		Seguimiento integral de gestión
 FUNCION: 		ssig.ft_plan_ime
 DESCRIPCION:   Funcion que gestiona las operaciones basicas (inserciones, modificaciones, eliminaciones de la tabla 'ssig.tplan'
 AUTOR: 		 (admin)
 FECHA:	        11-04-2017 14:31:46
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
  v_id_plan           INTEGER;
  va_id_funcionarios  VARCHAR [];
  v_id_funcionario    INTEGER;
  v_estado_gestion    VARCHAR;
  v_id_plan_padre     INTEGER;
  item                RECORD;
  item1               RECORD;
  v_mensaje           varchar;

BEGIN

  v_nombre_funcion = 'ssig.ft_plan_ime';
  v_parametros = pxp.f_get_record(p_tabla);

  /*********************************
   #TRANSACCION:  'SSIG_SSIGPLAN_INS'
   #DESCRIPCION:	Insercion de registros
   #AUTOR:		admin
   #FECHA:		11-04-2017 14:31:46
  ***********************************/

  IF (p_transaccion = 'SSIG_SSIGPLAN_INS')
  THEN

    BEGIN

      IF v_parametros.id_plan_padre != 'id' AND v_parametros.id_plan_padre != ''
      THEN
        v_id_plan_padre = v_parametros.id_plan_padre :: INTEGER;
      END IF;

      --Sentencia de la insercion
      INSERT INTO ssig.tplan (
        id_plan_padre,
        id_gestion,
        nivel,
        nombre_plan,
        peso,
        aprobado,
        estado_reg,
        id_usuario_ai,
        fecha_reg,
        usuario_ai,
        id_usuario_reg,
        fecha_mod,
        id_usuario_mod
      ) VALUES (
        v_id_plan_padre,
        v_parametros.id_gestion,
        v_parametros.nivel,
        v_parametros.nombre_plan,
        v_parametros.peso,
        v_parametros.aprobado,
        'activo',
        v_parametros._id_usuario_ai,
        now(),
        v_parametros._nombre_usuario_ai,
        p_id_usuario,
        NULL,
        NULL


      )
      RETURNING id_plan
        INTO v_id_plan;

      -- Insertar curso funcionario
      va_id_funcionarios := string_to_array(v_parametros.id_funcionarios, ',');

      FOREACH v_id_funcionario IN ARRAY va_id_funcionarios
      LOOP
        INSERT INTO ssig.tplan_funcionario (
          id_plan,
          id_funcionario,
          estado_reg,
          fecha_reg,
          usuario_ai,
          id_usuario_reg,
          id_usuario_ai,
          fecha_mod,
          id_usuario_mod
        ) VALUES (
          v_id_plan,
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
                                  'Definición de Planes almacenado(a) con exito (id_plan' || v_id_plan || ')');
      v_resp = pxp.f_agrega_clave(v_resp, 'id_plan', v_id_plan :: VARCHAR);

      --Devuelve la respuesta
      RETURN v_resp;

    END;

    /*********************************
     #TRANSACCION:  'SSIG_SSIGPLAN_MOD'
     #DESCRIPCION:	Modificacion de registros
     #AUTOR:		admin
     #FECHA:		11-04-2017 14:31:46
    ***********************************/

  ELSIF (p_transaccion = 'SSIG_SSIGPLAN_MOD')
    THEN

      BEGIN

        IF v_parametros.id_plan_padre != 'id' AND v_parametros.id_plan_padre != ''
        THEN
          v_id_plan_padre = v_parametros.id_plan_padre :: INTEGER;
        END IF;

        --Sentencia de la modificacion
        UPDATE ssig.tplan
        SET
          id_plan_padre  = v_id_plan_padre,
          id_gestion     = v_parametros.id_gestion,
          nivel          = v_parametros.nivel,
          nombre_plan    = v_parametros.nombre_plan,
          peso           = v_parametros.peso,
          aprobado       = v_parametros.aprobado,
          fecha_mod      = now(),
          id_usuario_mod = p_id_usuario,
          id_usuario_ai  = v_parametros._id_usuario_ai,
          usuario_ai     = v_parametros._nombre_usuario_ai
        WHERE id_plan = v_parametros.id_plan;

        -- Editar curso funcionario
        DELETE FROM ssig.tplan_funcionario pf
        WHERE pf.id_plan = v_parametros.id_plan;
        va_id_funcionarios := string_to_array(v_parametros.id_funcionarios, ',');
        FOREACH v_id_funcionario IN ARRAY va_id_funcionarios
        LOOP
          INSERT INTO ssig.tplan_funcionario (
            id_plan,
            id_funcionario,
            estado_reg,
            fecha_reg,
            usuario_ai,
            id_usuario_reg,
            id_usuario_ai,
            fecha_mod,
            id_usuario_mod
          ) VALUES (
            v_parametros.id_plan,
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
        v_resp = pxp.f_agrega_clave(v_resp, 'mensaje', 'Definición de Planes modificado(a)');
        v_resp = pxp.f_agrega_clave(v_resp, 'id_plan', v_parametros.id_plan :: VARCHAR);

        --Devuelve la respuesta
        RETURN v_resp;

      END;

      /*********************************
       #TRANSACCION:  'SSIG_SSIGPLAN_ELI'
       #DESCRIPCION:	Eliminacion de registros
       #AUTOR:		admin
       #FECHA:		11-04-2017 14:31:46
      ***********************************/

  ELSIF (p_transaccion = 'SSIG_SSIGPLAN_ELI')
    THEN

      BEGIN
        --Sentencia de la eliminacion
        
        --raise EXCEPTION 'errores %',v_parametros.id_plan;

        delete from ssig.tplan p where p.id_plan = v_parametros.id_plan;


        --Definicion de la respuesta
        v_resp = pxp.f_agrega_clave(v_resp, 'mensaje', 'Definición de Planes eliminado(a)');
        v_resp = pxp.f_agrega_clave(v_resp, 'id_plan', v_parametros.id_plan :: VARCHAR);

        --Devuelve la respuesta
        RETURN v_resp;

      END;

      /*********************************
       #TRANSACCION:  'SSIG_SSIGPAPR_MOD'
       #DESCRIPCION:	mnodificando el valor de aprobado de los planes
       #AUTOR:		Juan
       #FECHA:		15-05-2017 14:31:46
      ***********************************/
  ELSIF (p_transaccion = 'SSIG_SSIGPAPR_MOD')
    THEN
      BEGIN
      --v_parametros.id_gestion::INTEGER
        v_mensaje:=''; 
        --IF v_parametros.aprobado = 1 THEN
          IF exists(SELECT 1 FROM ssig.tplan
                             WHERE id_gestion = v_parametros.id_gestion::INTEGER AND (nivel < 2 OR nivel IS NULL) AND
                             (peso_acumulado < 100 OR peso_acumulado > 100 OR peso_acumulado IS NULL))  THEN
              --controla nivel null y nivel 1               
              v_mensaje:= (SELECT 'ERROR!! Uno de los acumulados de los planes no esta igual a 100' FROM ssig.tplan
                             WHERE id_gestion = v_parametros.id_gestion::INTEGER AND (nivel < 2 OR nivel IS NULL) AND
                             (peso_acumulado < 100 OR peso_acumulado > 100 OR peso_acumulado IS NULL) LIMIT 1);               
          ELSE
                   FOR item in (SELECT p.nombre_plan,p.id_plan,
                                                   CASE WHEN p.nivel = 2 THEN 
                                                       (SELECT sum(ll.peso) from ssig.tplan pp join ssig.tlinea ll on pp.id_plan=ll.id_plan 
                                                        where pp.id_plan=p.id_plan and ll.id_linea_padre is null and pp.id_gestion = v_parametros.id_gestion::INTEGER)::varchar
                                                    ELSE 
                                                        p.peso_acumulado::varchar
                                                    END::varchar as porcentaje_acumulado_aux
                                                    FROM ssig.tplan p
                                                    WHERE p.id_gestion = v_parametros.id_gestion::INTEGER AND p.nivel = 2)LOOP
                          if(item.porcentaje_acumulado_aux::INTEGER < 100 or item.porcentaje_acumulado_aux::INTEGER > 100 or item.porcentaje_acumulado_aux::INTEGER IS NULL)then
                               --Controla nivel 2 de planes
                               v_mensaje:='ERROR!! Los acumulados de los planes con iconos rojos no esta igual a 100';  
                          else
                               IF exists(SELECT l.peso_acumulado,l.nombre_linea from ssig.tlinea l where (l.id_plan=item.id_plan AND l.peso_acumulado !=100 )  and ( l.nivel<2 or l.nivel is null  or l.peso_acumulado is NULL))then
                                --Controla los acumulados de lines 
                                    for item1 in (SELECT l.peso_acumulado,l.nombre_linea from ssig.tlinea l where (l.id_plan=item.id_plan AND l.peso_acumulado !=100) and (l.nivel<2 or l.nivel is null or l.peso_acumulado is NULL))LOOP
                                        v_mensaje:='ERROR!! El acumulado en la linea perteneciente a los planes con iconos rojos no esta igual a 100';
                                    end LOOP;
                               ELSE        
                                    FOR item1 in (SELECT ((SELECT sum(la.avance_previsto) FROM ssig.tlinea_avance la where  la.id_linea = l.id_linea))::NUMERIC as total_avance_previsto,l.nombre_linea::VARCHAR 
                                                FROM ssig.tlinea l where l.id_plan = item.id_plan) LOOP
                                        if(item1.total_avance_previsto::NUMERIC < 100 or item1.total_avance_previsto::NUMERIC > 100)then
                                            v_mensaje:='ERROR!! El total de avance previsto perteneciente a los planes con iconos rojos no esta igual a 100';  
                                        end if;            
                                    end LOOP;
                               end IF;
                          end IF;         
                  end LOOP;
            
            
          END IF;
          IF(v_mensaje::VARCHAR = '' or v_parametros.aprobado::BIT = 0::BIT)THEN
                  UPDATE ssig.tplan
                  SET
                    aprobado = v_parametros.aprobado :: BIT
                  WHERE id_gestion = v_parametros.id_gestion;
                  
                  IF(v_parametros.aprobado::BIT = 1::BIT )THEN
                      v_mensaje:='Aprobado';
                  ELSE
                      v_mensaje:='Desaprobado';
                  END IF;
                  
          end if;
          
       -- END IF;
        --Definicion de la respuesta
        v_resp = pxp.f_agrega_clave(v_resp, 'mensaje', 'Definición de Planes modificado(s)');
        v_resp = pxp.f_agrega_clave(v_resp,'aprobado','%'||v_mensaje||'%'::varchar);
        --Devuelve la respuesta

        RETURN v_resp;

      END;


      /*********************************
       #TRANSACCION:  'SSIG_PLAN_ESTGEST'
       #DESCRIPCION:	Validar si la gestion seleccionada esta aprobada o desaprobada
       #AUTOR:		yac
       #FECHA:		31-05-2017 14:01:15
      ***********************************/

  ELSIF (p_transaccion = 'SSIG_PLAN_ESTGEST')
    THEN

      BEGIN
        --Sentencia de la modificacion
        v_estado_gestion :=  (SELECT aprobado
                              FROM ssig.tplan
                              WHERE id_gestion = v_parametros.id_gestion :: INTEGER
                              ORDER BY id_gestion ASC
                              LIMIT 1) :: VARCHAR;

        --RAISE NOTICE 'estado gestion juan %',v_resp;
        --RAISE EXCEPTION '%', v_resp;

        --Devuelve la respuesta
        v_resp = pxp.f_agrega_clave(v_resp, 'mensaje', 'estado gestion');
        v_resp = pxp.f_agrega_clave(v_resp, 'aprobado', '%' || v_estado_gestion || '%' :: VARCHAR);
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