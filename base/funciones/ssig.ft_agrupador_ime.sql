--------------- SQL ---------------

CREATE OR REPLACE FUNCTION ssig.ft_agrupador_ime (
  p_administrador integer,
  p_id_usuario integer,
  p_tabla varchar,
  p_transaccion varchar
)
RETURNS varchar AS
$body$
/**************************************************************************
 SISTEMA:   Seguimiento integral de gestiÃ³n
 FUNCION:     ssig.ft_agrupador_ime
 DESCRIPCION:   Funcion que gestiona las operaciones basicas (inserciones, modificaciones, eliminaciones de la tabla 'ssig.tagrupador'
 AUTOR:      (admin)
 FECHA:         05-06-2017 04:46:40
 COMENTARIOS: 
***************************************************************************
 HISTORIAL DE MODIFICACIONES:

 DESCRIPCION: 
 AUTOR:     
 FECHA:   
 
 
ISSUE   FORK           FECHA        DESCRIPCION
#2      endetr Juan    27/02/2019   se agrego este if para considerar el no se hizo
#1      endetr Juan    23/03/2020   Cambio de logica en agrupadores gestion 2019
***************************************************************************/

DECLARE
 
  v_nro_requerimiento  INTEGER;
  v_parametros         RECORD;
  v_id_requerimiento   INTEGER;
  v_resp               VARCHAR;
  v_nombre_funcion     TEXT;
  v_mensaje_error      TEXT;
  v_id_agrupador       INTEGER;
  v_id_agrupador_padre INTEGER;
  v_estado_gestion     INTEGER;
  
  item                 record;
  item1                record;
  item_periodo         record;
  v_posicion           integer;
  v_gn2                numeric;
  v_gn1                numeric;
  v_gn0                numeric;
  v_peso_no_reporta    numeric;
  
  v_filtro_periodo    VARCHAR;
  
  v_resultado           numeric;
  v_gestion           integer;

BEGIN

  v_nombre_funcion = 'ssig.ft_agrupador_ime';
  v_parametros = pxp.f_get_record(p_tabla);

  /*********************************
   #TRANSACCION:  'SSIG_SSIG_AG_INS'
   #DESCRIPCION:  Insercion de registros
   #AUTOR:    admin
   #FECHA:    05-06-2017 04:46:40
  ***********************************/


  IF (p_transaccion = 'SSIG_SSIG_AG_INS')
  THEN
    BEGIN
    
      
      IF v_parametros.id_agrupador_padre != 'id' AND v_parametros.id_agrupador_padre != ''
      THEN
        v_id_agrupador_padre = v_parametros.id_agrupador_padre :: INTEGER;
      END IF;

      --insertamos la parametrizacion indicador si no existe
      if NOT EXISTS(SELECT * FROM ssig.tinterpretacion_indicador WHERE id_gestion=v_parametros.id_gestion::INTEGER)then
            INSERT INTO ssig.tinterpretacion_indicador ("id_usuario_reg", "fecha_reg", "estado_reg", "interpretacion", "color", "icono", "porcentaje", "id_gestion","posicion")
            VALUES 
              (1, (SELECT fecha_reg from param.tgestion order by id_gestion desc limit 1), E'activo', 'Cumplimiento', '#3399CC', '../../../sis_segintegralgestion/vista/ImagenesInterpretacionIndicador/Cumplimiento.png', 100, v_parametros.id_gestion::INTEGER,1),
              (1, (SELECT fecha_reg from param.tgestion order by id_gestion desc limit 1), E'activo', 'Exito', '#66CC99', '../../../sis_segintegralgestion/vista/ImagenesInterpretacionIndicador/Exito.png', 85, v_parametros.id_gestion::INTEGER,2),
              (1, (SELECT fecha_reg from param.tgestion order by id_gestion desc limit 1), E'activo', 'Riesgo', '#F0D58C', '../../../sis_segintegralgestion/vista/ImagenesInterpretacionIndicador/Riesgo.png', 50, v_parametros.id_gestion::INTEGER,3),
              (1, (SELECT fecha_reg from param.tgestion order by id_gestion desc limit 1), E'activo', 'Fracaso', '#FA8072', '../../../sis_segintegralgestion/vista/ImagenesInterpretacionIndicador/Fracaso.png', 0, v_parametros.id_gestion::INTEGER,4);
      end if;
      -----------------------------------------------
      --Sentencia de la insercion
      INSERT INTO ssig.tagrupador (
        id_agrupador_padre,
        id_funcionario,
        nombre,
        descripcion,
        nivel,
        peso,
        estado_reg,
        id_usuario_ai,
        id_usuario_reg,
        usuario_ai,
        fecha_reg,
        id_usuario_mod,
        fecha_mod,
        id_gestion,
        aprobado,
        orden_logico
      ) VALUES (
        v_id_agrupador_padre,
        v_parametros.id_funcionario,
        v_parametros.nombre,
        v_parametros.descripcion,
        v_parametros.nivel,
        v_parametros.peso,
        'activo',
        v_parametros._id_usuario_ai,
        p_id_usuario,
        v_parametros._nombre_usuario_ai,
        now(),
        NULL,
        NULL,
        v_parametros.id_gestion,
        v_parametros.aprobado,
        v_parametros.orden_logico
      )
      RETURNING id_agrupador
        INTO v_id_agrupador;

      --Definicion de la respuesta
      v_resp = pxp.f_agrega_clave(v_resp, 'mensaje',
                                  'Agrupador almacenado(a) con exito (id_agrupador' || v_id_agrupador || ')');
      v_resp = pxp.f_agrega_clave(v_resp, 'id_agrupador', v_id_agrupador :: VARCHAR);
      --Devuelve la respuesta
      
      RETURN v_resp;

    END;

    /*********************************
     #TRANSACCION:  'SSIG_SSIG_AG_MOD'
     #DESCRIPCION:  Modificacion de registros
     #AUTOR:    admin
     #FECHA:    05-06-2017 04:46:40
    ***********************************/

  ELSIF (p_transaccion = 'SSIG_SSIG_AG_MOD')
    THEN

      BEGIN
        --Sentencia de la modificacion
        UPDATE ssig.tagrupador
        SET
          id_agrupador_padre = v_parametros.id_agrupador_padre,
          id_funcionario     = v_parametros.id_funcionario,
          nombre             = v_parametros.nombre,
          descripcion        = v_parametros.descripcion,
          nivel              = v_parametros.nivel,
          peso               = v_parametros.peso,
          id_usuario_mod     = p_id_usuario,
          fecha_mod          = now(),
          id_usuario_ai      = v_parametros._id_usuario_ai,
          usuario_ai         = v_parametros._nombre_usuario_ai,
          orden_logico       = v_parametros.orden_logico
        WHERE id_agrupador = v_parametros.id_agrupador;

        --Definicion de la respuesta
        v_resp = pxp.f_agrega_clave(v_resp, 'mensaje', 'Agrupador modificado(a)');
        v_resp = pxp.f_agrega_clave(v_resp, 'id_agrupador', v_parametros.id_agrupador :: VARCHAR);

        --Devuelve la respuesta
        RETURN v_resp;

      END;
    /*********************************
     #TRANSACCION:  'SSIG_INTERINDI_MOD'
     #DESCRIPCION:  Modificacion interpretación de indicador
     #AUTOR:    admin
     #FECHA:    05-06-2017 04:46:40
    ***********************************/

  ELSIF (p_transaccion = 'SSIG_INTERINDI_MOD')
    THEN

      BEGIN
        --Sentencia de la modificacion
        UPDATE ssig.tinterpretacion_indicador
        SET
          porcentaje = v_parametros.porcentaje
        WHERE id_interpretacion_indicador = v_parametros.id_interpretacion_indicador;

        --Definicion de la respuesta
        v_resp = pxp.f_agrega_clave(v_resp, 'mensaje', 'interpretacion modificado(a)');
        v_resp = pxp.f_agrega_clave(v_resp, 'id_agrupador', v_parametros.id_interpretacion_indicador :: VARCHAR);

        --Devuelve la respuesta
        RETURN v_resp;

      END;

      /*********************************
       #TRANSACCION:  'SSIG_SSIG_AG_ELI'
       #DESCRIPCION:  Eliminacion de registros
       #AUTOR:    admin
       #FECHA:    05-06-2017 04:46:40
      ***********************************/

  ELSIF (p_transaccion = 'SSIG_SSIG_AG_ELI')
    THEN

      BEGIN
        --Sentencia de la eliminacion
        DELETE FROM ssig.tagrupador
        WHERE id_agrupador = v_parametros.id_agrupador;

        --Definicion de la respuesta
        v_resp = pxp.f_agrega_clave(v_resp, 'mensaje', 'Agrupador eliminado(a)');
        v_resp = pxp.f_agrega_clave(v_resp, 'id_agrupador', v_parametros.id_agrupador :: VARCHAR);

        --Devuelve la respuesta
        RETURN v_resp;

      END;
      
      
      /*********************************
       #TRANSACCION:  'SSIG_SSIG_AG_EST_GES'
       #DESCRIPCION:  Validar si la gestion seleccionada esta aprobada o desaprobada
       #AUTOR:    MANU
       #FECHA:    31-05-2017 14:01:15
      ***********************************/
  
    ELSIF (p_transaccion = 'SSIG_SSIG_AG_EST_GES')
      THEN
  
        BEGIN
          --Sentencia de la modificacion
          v_estado_gestion :=  (SELECT aprobado
                                FROM ssig.tagrupador
                                WHERE id_gestion = v_parametros.id_gestion :: INTEGER
                                ORDER BY id_gestion ASC
                                LIMIT 1) :: VARCHAR;
          --Devuelve la respuesta
          v_resp = pxp.f_agrega_clave(v_resp, 'mensaje', 'estado gestion');
          v_resp = pxp.f_agrega_clave(v_resp, 'aprobado', '%' || v_estado_gestion || '%' :: VARCHAR);
          RETURN v_resp;
  
        END;      
      
        /*********************************
       #TRANSACCION:  'SSIG_SSIG_APR'
       #DESCRIPCION:  MODIFICAR APROBACION
       #AUTOR:    JUAN
       #FECHA:    23-11-2017 14:31:46
      ***********************************/
    ELSIF (p_transaccion = 'SSIG_SSIG_APR')
      THEN
        BEGIN
          --raise EXCEPTION 'erro %',(SELECT  p.id_periodo,extract(MONTH from p.fecha_ini)   FROM param.tperiodo p where p.id_periodo=v_parametros.id_periodo) ;
            --A qui restaurar resultados en agrupadores y agrupador_indicador
            
            
            if (v_parametros.id_gestion <=2018)THEN
                    select g.gestion
                    INTO v_gestion 
                    from param.tgestion g 
                    where g.id_gestion=v_parametros.id_gestion;
                      
                    UPDATE ssig.tagrupador
                    SET
                      resultado=0.00:: numeric,
                      id_interpretacion_indicador=NULL
                    WHERE id_gestion = v_parametros.id_gestion;

                    UPDATE ssig.tagrupador_indicador
                    SET
                      resultado=0.00:: numeric,
                      semaforo1='',
                      semaforo2='',
                      semaforo3='',
                      semaforo4='',
                      semaforo5='',
                      valor_real='',
                      semaforo='',
                      comparacion='',
                      ruta_icono='',
                      justificacion='',
                      id_interpretacion_indicador=NULL
                    WHERE id_agrupador IN(SELECT a.id_agrupador FROM ssig.tagrupador a join ssig.tagrupador_indicador ai on ai.id_agrupador=a.id_agrupador WHERE a.id_gestion = v_parametros.id_gestion);
                      
                    if NOT EXISTS(SELECT * FROM ssig.tinterpretacion_indicador WHERE id_gestion=v_parametros.id_gestion::INTEGER)then
                          INSERT INTO ssig.tinterpretacion_indicador ("id_usuario_reg", "fecha_reg", "estado_reg", "interpretacion", "color", "icono", "porcentaje", "id_gestion","posicion")
                          VALUES 
                            (1, (SELECT fecha_reg from param.tgestion order by id_gestion desc limit 1), E'activo', 'Cumplimiento', '#3399CC', '../../../sis_segintegralgestion/vista/ImagenesInterpretacionIndicador/Cumplimiento.png', 100, v_parametros.id_gestion::INTEGER,1),
                            (1, (SELECT fecha_reg from param.tgestion order by id_gestion desc limit 1), E'activo', 'Exito', '#66CC99', '../../../sis_segintegralgestion/vista/ImagenesInterpretacionIndicador/Exito.png', 85, v_parametros.id_gestion::INTEGER,2),
                            (1, (SELECT fecha_reg from param.tgestion order by id_gestion desc limit 1), E'activo', 'Riesgo', '#F0D58C', '../../../sis_segintegralgestion/vista/ImagenesInterpretacionIndicador/Riesgo.png', 50, v_parametros.id_gestion::INTEGER,3),
                            (1, (SELECT fecha_reg from param.tgestion order by id_gestion desc limit 1), E'activo', 'Fracaso', '#FA8072', '../../../sis_segintegralgestion/vista/ImagenesInterpretacionIndicador/Fracaso.png', 0, v_parametros.id_gestion::INTEGER,4);
                    end if;
                    --Fin restauracion de agrupadores y agrupadores_indicadores
                    IF v_parametros.aprobado = 1
                    THEN
                      IF exists(SELECT 1
                                FROM ssig.tagrupador
                                WHERE id_gestion = v_parametros.id_gestion AND (nivel < 2 OR nivel IS NULL) AND
                                      (peso_acumulado < 100 OR peso_acumulado IS NULL)) 
                                OR
                                (SELECT 
                                  CASE 
                                      WHEN SUM(agin.peso)<100 
                                          THEN TRUE::BOOLEAN
                                      ELSE 
                                          FALSE::BOOLEAN    
                                  END AS TOTAL
                                  FROM ssig.tagrupador_indicador agin
                                  LEFT JOIN ssig.tagrupador padre ON padre.id_agrupador = agin.id_agrupador
                                  INNER JOIN segu.tusuario usu1 ON usu1.id_usuario = agin.id_usuario_reg                        
                                  LEFT JOIN segu.tusuario usu2 ON usu2.id_usuario = agin.id_usuario_mod           
                                  INNER JOIN ssig.tindicador ind ON ind.id_indicador = agin.id_indicador
                                  --LEFT JOIN orga.tfuncionario t ON t.id_funcionario= agin.id_funcionario_ingreso 
                                  --LEFT JOIN orga.tfuncionario t1 ON t1.id_funcionario= agin.id_funcionario_evaluacion
                                  --INNER JOIN segu.tpersona per ON per.id_persona = t.id_persona
                                  --INNER JOIN segu.vpersona PERSON ON PERSON.id_persona = per.id_persona 
                                  --INNER JOIN segu.tpersona per1 ON per1.id_persona = t1.id_persona
                                  --INNER JOIN segu.vpersona PERSON1 ON PERSON1.id_persona = per1.id_persona
                                  WHERE agin.peso <100 AND padre.id_gestion = v_parametros.id_gestion
                                  GROUP BY agin.id_agrupador
                                  LIMIT 1)                                                                  
                      THEN
                        RAISE EXCEPTION 'no estan validados los datos, le falta llenar un nivel, o le falta completar un peso';
                      ELSE
                        UPDATE ssig.tagrupador
                        SET
                          aprobado = v_parametros.aprobado :: BIT
                        WHERE id_gestion = v_parametros.id_gestion;
                        --Calcular resultados para la tabla agrupadores y la atabla agrupador_indicador

                          
                        DELETE FROM ssig.tagrupador_resultado where id_gestion = v_parametros.id_gestion;
                        DELETE FROM ssig.tagrupador_indicador_resultado where id_gestion = v_parametros.id_gestion;
                          
                        FOR item_periodo IN(SELECT p.id_gestion,p.id_periodo,p.fecha_fin, extract(MONTH from p.fecha_fin) as periodo from param.tperiodo p 
                                            where p.id_gestion=v_parametros.id_gestion
                                            order by p.fecha_fin asc )LOOP

                                      FOR item IN(WITH RECURSIVE arb_agrupador AS(
                                                              SELECT a.*,
                                                              a.nombre::TEXT AS ancestros
                                                              FROM ssig.tagrupador a
                                                              WHERE a.id_agrupador_padre IS NULL
                                                  UNION ALL
                                                           SELECT a2.*,
                                                           (al.ancestros || '->' || a2.nombre)::TEXT AS ancestros
                                                           FROM ssig.tagrupador a2
                                                           JOIN arb_agrupador al ON al.id_agrupador=a2.id_agrupador_padre)  
                                              SELECT 
                                                   arb.id_agrupador::INTEGER, 
                                                   arb.id_agrupador_padre::INTEGER,
                                                   arb.nombre,
                                                   arb.peso,
                                                   arb.peso_acumulado,
                                                   arb.aprobado,
                                                   arb.nivel,
                                                   (select   array_to_string (ARRAY_AGG(aa.id_agrupador),',')::VARCHAR from ssig.tagrupador aa where aa.id_agrupador_padre=arb.id_agrupador)::VARCHAR as cod_hijos,
                                                   (select array_to_string( array_agg( i.id_indicador), ',' )
                                                     from ssig.tagrupador_indicador ai 
                                                     join ssig.tindicador i on i.id_indicador=ai.id_indicador
                                                     where ai.id_agrupador=arb.id_agrupador)::VARCHAR as cod_indicadores,
                                                   arb.resultado 
                                              FROM arb_agrupador arb 
                                              left  join ssig.tagrupador agr on agr.id_agrupador=arb.id_agrupador_padre
                                              AND agr.id_gestion=v_parametros.id_gestion    
                                              order by arb.ancestros asc) LOOP
                                                
                                                
                                              IF(item.nivel::INTEGER = 2) THEN
                                                
                                                      v_gn2:=0::NUMERIC;
                                                      v_peso_no_reporta=0::NUMERIC;
                                                      FOR item1 IN  (SELECT ai.id_agrupador_indicador,
                                                                           i.id_indicador
                                                                    from ssig.tagrupador a 
                                                                    join ssig.tagrupador_indicador ai on a.id_agrupador=ai.id_agrupador 
                                                                    join ssig.tindicador i on i.id_indicador=ai.id_indicador
                                                                    join ssig.tindicador_unidad iu on iu.id_indicador_unidad=i.id_indicador_unidad
                                                                    where a.id_gestion=v_parametros.id_gestion and a.id_agrupador=item.id_agrupador ) LOOP
                         
                                                            -- TENER CUIDADO EL EL WHERE CON ELIMINAR TODOS LOS VALORES REALES EXISTENTES
                                                              UPDATE ssig.tindicador_valor
                                                                  SET valor=NULL
                                                              where  (id_indicador=item1.id_indicador and no_reporta='No reporta') or (id_indicador=item1.id_indicador and valor='');    
                                                            ------------------------------
                                                                          
                                                      END LOOP;
                                                        
                                                      v_filtro_periodo :='';
                                                      --RAISE EXCEPTION 'error per %',(SELECT  extract(MONTH from p.fecha_ini)   FROM param.tperiodo p where p.id_periodo=v_parametros.id_periodo);  
                                                      v_filtro_periodo :=(SELECT  extract(MONTH from p.fecha_fin)   FROM param.tperiodo p where p.id_periodo=item_periodo.id_periodo)::VARCHAR;
                         
                                                       
                                                     FOR item1 IN  (SELECT ai.id_agrupador_indicador,
                                                                                   ai.peso,i.id_indicador,
                                                                                   (SELECT iv.valor from ssig.tindicador_valor iv where iv.id_indicador=i.id_indicador  and extract(MONTH from iv.fecha) <= v_filtro_periodo::INTEGER order by iv.fecha desc  limit 1) as valor_real,
                                                                                   (SELECT iv.semaforo3 from ssig.tindicador_valor iv where iv.id_indicador=i.id_indicador and extract(MONTH from iv.fecha) <= v_filtro_periodo::INTEGER order by iv.fecha desc  limit 1) as valor_meta,
                                                                                   i.semaforo,
                                                                                   i.comparacion,
                                                                                   (SELECT iv.semaforo1 from ssig.tindicador_valor iv where iv.id_indicador=i.id_indicador  and extract(MONTH from iv.fecha) <= v_filtro_periodo::INTEGER order by iv.fecha desc  limit 1)::VARCHAR as Semaforo1, -- <= v_filtro_periodo
                                                                                   (SELECT iv.semaforo2 from ssig.tindicador_valor iv where iv.id_indicador=i.id_indicador  and extract(MONTH from iv.fecha) <= v_filtro_periodo::INTEGER order by iv.fecha desc  limit 1)::VARCHAR as Semaforo2,
                                                                                   (SELECT iv.semaforo3 from ssig.tindicador_valor iv where iv.id_indicador=i.id_indicador  and extract(MONTH from iv.fecha) <= v_filtro_periodo::INTEGER order by iv.fecha desc  limit 1)::VARCHAR as Semaforo3,
                                                                                   (SELECT iv.semaforo4 from ssig.tindicador_valor iv where iv.id_indicador=i.id_indicador  and extract(MONTH from iv.fecha) <= v_filtro_periodo::INTEGER order by iv.fecha desc  limit 1)::VARCHAR as Semaforo4,
                                                                                   (SELECT iv.semaforo5 from ssig.tindicador_valor iv where iv.id_indicador=i.id_indicador  and extract(MONTH from iv.fecha) <= v_filtro_periodo::INTEGER order by iv.fecha desc  limit 1)::VARCHAR as Semaforo5,
                                                                               (SELECT iv.justificacion from ssig.tindicador_valor iv where iv.id_indicador=i.id_indicador  and extract(MONTH from iv.fecha) <= v_filtro_periodo::INTEGER order by iv.fecha desc  limit 1)::VARCHAR as justificacion,
                                                                                   iu.tipo,
                                                                                  (SELECT iv.no_reporta from ssig.tindicador_valor iv where iv.id_indicador=i.id_indicador  and extract(MONTH from iv.fecha) <= v_filtro_periodo::INTEGER order by iv.fecha desc  limit 1)::VARCHAR as no_reporta,
                                                                                   ai.peso,
                                                                                   a.id_agrupador
                                                                            from ssig.tagrupador a 
                                                                            join ssig.tagrupador_indicador ai on a.id_agrupador=ai.id_agrupador 
                                                                            join ssig.tindicador i on i.id_indicador=ai.id_indicador
                                                                            join ssig.tindicador_unidad iu on iu.id_indicador_unidad=i.id_indicador_unidad
                                                                            where a.id_gestion=v_parametros.id_gestion and a.id_agrupador=item.id_agrupador ) LOOP
                                                           
                                                                  
                                                              v_posicion:=0;
                                                                
                                                              IF(item1.semaforo='Simple' AND item1.comparacion='Asc' AND item1.no_reporta !='No reporta')THEN
                                                                 IF(item1.tipo='Numero' or item1.tipo='Hrs')THEN
                                                                    IF(item1.valor_real::NUMERIC >= item1.Semaforo3::NUMERIC and  v_gestion <=2018 )then
                                                                    --azul
                                                                     v_posicion:=1;
                                                                    ELSE
                                                                        IF( (case when v_gestion<=2018 then (item1.valor_real::NUMERIC < item1.Semaforo3::NUMERIC) else 0=0 end) and (item1.valor_real::NUMERIC >= item1.Semaforo2::NUMERIC))then
                                                                           --verde
                                                                            v_posicion:=2;
                                                                        ELSE
                                                                            IF((item1.valor_real::NUMERIC < item1.Semaforo2::NUMERIC) and (item1.valor_real::NUMERIC >= item1.Semaforo1::NUMERIC))then
                                                                               --amarillo
                                                                                v_posicion:=3;
                                                                            ELSE
                                                                                IF(item1.valor_real::NUMERIC < item1.Semaforo1::NUMERIC)then
                                                                                --rojo
                                                                                 v_posicion:=4;
                                                                                END IF;
                                                                            END IF;
                                                                        END IF;
                                                                    END IF; 
                                                                 ELSE
                                                                     IF(item1.tipo='Fecha')THEN
                                                                        IF(item1.valor_real::DATE >= item1.Semaforo3::DATE  and  v_gestion <=2018)then
                                                                        --azul
                                                                         v_posicion:=1;
                                                                        ELSE
                                                                            IF(  (case when v_gestion<=2018 then (item1.valor_real::DATE < item1.Semaforo3::DATE) else 0=0 end) and (item1.valor_real::DATE >= item1.Semaforo2::DATE))then
                                                                               --verde
                                                                                v_posicion:=2;
                                                                            ELSE
                                                                                IF((item1.valor_real::DATE < item1.Semaforo2::DATE) and (item1.valor_real::DATE >= item1.Semaforo1::DATE))then
                                                                                   --amarillo
                                                                                    v_posicion:=3;
                                                                                ELSE
                                                                                    IF(item1.valor_real::DATE < item1.Semaforo1::DATE)then
                                                                                    --rojo
                                                                                     v_posicion:=4;
                                                                                    END IF;
                                                                                END IF;
                                                                            END IF;
                                                                        END IF; 
                                                                     END IF;
                                                                 END IF;
                                                              END IF;
                                                              IF(item1.semaforo='Simple' AND item1.comparacion='Desc' AND item1.no_reporta !='No reporta')THEN
                                                                   IF(item1.tipo='Numero' or item1.tipo='Hrs')THEN
                                                                      IF(item1.valor_real::NUMERIC <= item1.Semaforo3::NUMERIC and  v_gestion <=2018 )then
                                                                      --azul
                                                                       v_posicion:=1;
                                                                      ELSE
                                                                          IF( (case when v_gestion<=2018 then  (item1.valor_real::NUMERIC > item1.Semaforo3::NUMERIC) else 0=0 end) and (item1.valor_real::NUMERIC <= item1.Semaforo2::NUMERIC))then
                                                                             --verde
                                                                              v_posicion:=2;
                                                                          ELSE
                                                                              IF((item1.valor_real::NUMERIC > item1.Semaforo2::NUMERIC) and (item1.valor_real::NUMERIC <= item1.Semaforo1::NUMERIC))then
                                                                                 --amarillo
                                                                                  v_posicion:=3;
                                                                              ELSE
                                                                                  IF(item1.valor_real::NUMERIC > item1.Semaforo1::NUMERIC)then
                                                                                  --rojo
                                                                                   v_posicion:=4;
                                                                                  END IF;
                                                                              END IF;
                                                                          END IF;
                                                                      END IF; 
                                                                   ELSE
                                                                       IF(item1.tipo='Fecha')THEN
                                                                          IF(item1.valor_real::DATE <= item1.Semaforo3::DATE and  v_gestion <=2018)then
                                                                          --azul
                                                                           v_posicion:=1;
                                                                          ELSE
                                                                              IF( (case when v_gestion<=2018 then (item1.valor_real::DATE > item1.Semaforo3::DATE) else 0=0 END) and (item1.valor_real::DATE <= item1.Semaforo2::DATE))then
                                                                                 --verde4
                                                                                  v_posicion:=2;
                                                                              ELSE
                                                                                  IF((item1.valor_real::DATE > item1.Semaforo2::DATE) and (item1.valor_real::DATE <= item1.Semaforo1::DATE))then
                                                                                     --amarillo
                                                                                      v_posicion:=3;
                                                                                  ELSE
                                                                                      IF(item1.valor_real::DATE > item1.Semaforo1::DATE)then
                                                                                      --rojo
                                                                                       v_posicion:=4;
                                                                                      END IF;
                                                                                  END IF;
                                                                              END IF;
                                                                          END IF; 
                                                                       END IF;
                                                                   END IF;
                                                              END IF;
                                                              IF(item1.semaforo='Compuesto' AND item1.comparacion='Asc' AND item1.no_reporta !='No reporta')THEN
                                                                  IF(item1.tipo='Numero' or item1.tipo='Hrs')THEN
                                                                      IF((item1.valor_real::NUMERIC > item1.Semaforo5::NUMERIC) or (item1.valor_real::NUMERIC < item1.Semaforo1::NUMERIC) )then
                                                                      --azul
                                                                       v_posicion:=1;
                                                                      ELSE
                                                                          IF((item1.valor_real::NUMERIC <= item1.Semaforo5::NUMERIC and item1.valor_real::NUMERIC > item1.Semaforo4::NUMERIC) or (item1.valor_real::NUMERIC >= item1.Semaforo1::NUMERIC and item1.valor_real::NUMERIC < item1.Semaforo2::NUMERIC))then
                                                                             --verde
                                                                              v_posicion:=2;
                                                                          ELSE
                                                                              IF((item1.valor_real::NUMERIC <= item1.Semaforo4::NUMERIC and item1.valor_real::NUMERIC > item1.Semaforo3::NUMERIC) or (item1.valor_real::NUMERIC <= item1.Semaforo3::NUMERIC and item1.valor_real::NUMERIC >= item1.Semaforo2::NUMERIC))then
                                                                                 --amarillo
                                                                                  v_posicion:=3;
                                                                              ELSE
                                                                                  IF(item1.valor_real::NUMERIC = item1.Semaforo3::NUMERIC)then
                                                                                  --rojo
                                                                                   v_posicion:=4;
                                                                                  END IF;
                                                                              END IF;
                                                                          END IF;
                                                                      END IF; 
                                                                   ELSE
                                                                       IF(item1.tipo='Fecha')THEN
                                                                              IF((item1.valor_real::DATE > item1.Semaforo5::DATE) or (item1.valor_real::DATE < item1.Semaforo1::DATE) )then
                                                                              --azul
                                                                               v_posicion:=1;
                                                                              ELSE
                                                                                  IF((item1.valor_real::DATE <= item1.Semaforo5::DATE and item1.valor_real::DATE > item1.Semaforo4::DATE) or (item1.valor_real::DATE >= item1.Semaforo1::DATE and item1.valor_real::DATE < item1.Semaforo2::DATE))then
                                                                                     --verde
                                                                                      v_posicion:=2;
                                                                                  ELSE
                                                                                      IF((item1.valor_real::DATE <= item1.Semaforo4::DATE and item1.valor_real::DATE > item1.Semaforo3::DATE) or (item1.valor_real::DATE <= item1.Semaforo3::DATE and item1.valor_real::DATE >= item1.Semaforo2::DATE))then
                                                                                         --amarillo
                                                                                          v_posicion:=3;
                                                                                      ELSE
                                                                                          IF(item1.valor_real::DATE = item1.Semaforo3::DATE)then
                                                                                          --rojo
                                                                                           v_posicion:=4;
                                                                                          END IF;
                                                                                      END IF;
                                                                                  END IF;
                                                                              END IF; 
                                                                       END IF;
                                                                   END IF;
                                                              END IF;
                                                              IF(item1.semaforo='Compuesto' AND item1.comparacion='Desc' AND item1.no_reporta !='No reporta')THEN
                                                                    IF(item1.tipo='Numero' or item1.tipo='Hrs')THEN
                                                                        IF((item1.valor_real::NUMERIC < item1.Semaforo5::NUMERIC) or (item1.valor_real::NUMERIC > item1.Semaforo1::NUMERIC) )then
                                                                        --rojo
                                                                         v_posicion:=1;
                                                                        ELSE
                                                                            IF((item1.valor_real::NUMERIC >= item1.Semaforo5::NUMERIC and item1.valor_real::NUMERIC < item1.Semaforo4::NUMERIC) OR (item1.valor_real::NUMERIC > item1.Semaforo2::NUMERIC and item1.valor_real::NUMERIC <= item1.Semaforo1::NUMERIC))then
                                                                               --amarillo
                                                                                v_posicion:=2;
                                                                            ELSE
                                                                                IF((item1.valor_real::NUMERIC >= item1.Semaforo4::NUMERIC and item1.valor_real::NUMERIC < item1.Semaforo3::NUMERIC) OR (item1.valor_real::NUMERIC > item1.Semaforo3::NUMERIC and item1.valor_real::NUMERIC <= item1.Semaforo2::NUMERIC))then
                                                                                   --verde
                                                                                    v_posicion:=3;
                                                                                ELSE
                                                                                    IF(item1.valor_real::NUMERIC = item1.Semaforo3::NUMERIC)then
                                                                                    --azul
                                                                                     v_posicion:=4;
                                                                                    END IF;
                                                                                END IF;
                                                                            END IF;
                                                                        END IF; 
                                                                     ELSE
                                                                         IF(item1.tipo='Fecha')THEN
                                                                              IF((item1.valor_real::DATE < item1.Semaforo5::DATE) or (item1.valor_real::DATE > item1.Semaforo1::DATE) )then
                                                                              --rojo
                                                                               v_posicion:=1;
                                                                              ELSE
                                                                                  IF((item1.valor_real::DATE >= item1.Semaforo5::DATE and item1.valor_real::DATE < item1.Semaforo4::DATE) OR (item1.valor_real::DATE > item1.Semaforo2::DATE and item1.valor_real::DATE <= item1.Semaforo1::DATE))then
                                                                                     --amarillo
                                                                                      v_posicion:=2;
                                                                                  ELSE
                                                                                      IF((item1.valor_real::DATE >= item1.Semaforo4::DATE and item1.valor_real::DATE < item1.Semaforo3::DATE) OR (item1.valor_real::DATE > item1.Semaforo3::DATE and item1.valor_real::DATE <= item1.Semaforo2::DATE))then
                                                                                         --verde
                                                                                          v_posicion:=3;
                                                                                      ELSE
                                                                                          IF(item1.valor_real::DATE = item1.Semaforo3::DATE)then
                                                                                          --azul
                                                                                           v_posicion:=4;
                                                                                          END IF;
                                                                                      END IF;
                                                                                  END IF;
                                                                              END IF; 
                                                                         END IF;
                                                                     END IF;
                                                              END IF;
                                                                
                                                                                                

                                                                
                                                              --Insertamos resultados a la tabala agrupador indicador
                                                              --RAISE EXCEPTION 'Error provocado por juan jimenez  %',(SELECT ii.porcentaje from ssig.tinterpretacion_indicador ii where ii.id_gestion=v_parametros.id_gestion::INTEGER and ii.posicion=v_posicion::INTEGER)::INTEGER;
                                                              --Calculamos nivel 3 
                                                              IF(item1.no_reporta !='No reporta')THEN
                                                                          
                                                                      -- inicio #2 endetr Juan  27/02/2019 se agrego este if para considerar el no se hizo
                                                                      IF(item1.no_reporta ='No se hizo')THEN

                                                                              IF NOT EXISTS(SELECT * from ssig.tagrupador_indicador_resultado air 
                                                                                           where air.id_agrupador_indicador=item1.id_agrupador_indicador::INTEGER and 
                                                                                           air.id_periodo=item_periodo.id_periodo::INTEGER) THEN
                                                                                  insert into  ssig.tagrupador_indicador_resultado 
                                                                                  (id_agrupador_indicador,
                                                                                  id_periodo,
                                                                                  resultado,
                                                                                  id_gestion,
                                                                                  ruta_icono,
                                                                                  semaforo1,
                                                                                  semaforo2,
                                                                                  semaforo3,
                                                                                  semaforo4,
                                                                                  semaforo5,
                                                                                  justificacion,
                                                                                  valor_real)VALUES
                                                                                  (item1.id_agrupador_indicador::INTEGER,
                                                                                  item_periodo.id_periodo::INTEGER,
                                                                                  0::INTEGER,
                                                                                  v_parametros.id_gestion,
                                                                                  --la variable v_posicion se cambio a 4 para el caso no se hizo  fracaso
                                                                                  (SELECT ii.color from ssig.tinterpretacion_indicador ii where ii.id_gestion=v_parametros.id_gestion::INTEGER and ii.posicion=4::INTEGER)::VARCHAR,
                                                                                  item1.Semaforo1::VARCHAR,
                                                                                  item1.Semaforo2::VARCHAR,
                                                                                  item1.Semaforo3::VARCHAR,
                                                                                  item1.Semaforo4::VARCHAR,
                                                                                  item1.Semaforo5::VARCHAR,
                                                                                  item1.justificacion::VARCHAR,
                                                                                  item1.valor_real::VARCHAR);
                                                                              END IF;    
                                                                        ELSE
                                                                              IF NOT EXISTS(SELECT * from ssig.tagrupador_indicador_resultado air 
                                                                                           where air.id_agrupador_indicador=item1.id_agrupador_indicador::INTEGER and 
                                                                                           air.id_periodo=item_periodo.id_periodo::INTEGER) THEN
                                                                                  insert into  ssig.tagrupador_indicador_resultado 
                                                                                  (id_agrupador_indicador,
                                                                                  id_periodo,
                                                                                  resultado,
                                                                                  id_gestion,
                                                                                  ruta_icono,
                                                                                  semaforo1,
                                                                                  semaforo2,
                                                                                  semaforo3,
                                                                                  semaforo4,
                                                                                  semaforo5,
                                                                                  justificacion,
                                                                                  valor_real)VALUES
                                                                                  (item1.id_agrupador_indicador::INTEGER,
                                                                                  item_periodo.id_periodo::INTEGER,
                                                                                  (SELECT ii.porcentaje from ssig.tinterpretacion_indicador ii where ii.id_gestion=v_parametros.id_gestion::INTEGER and ii.posicion=v_posicion::INTEGER)::INTEGER,
                                                                                  v_parametros.id_gestion,
                                                                                  -- El campo icono se cambio por color
                                                                                  (SELECT ii.color from ssig.tinterpretacion_indicador ii where ii.id_gestion=v_parametros.id_gestion::INTEGER and ii.posicion=v_posicion::INTEGER)::VARCHAR,
                                                                                  item1.Semaforo1::VARCHAR,
                                                                                  item1.Semaforo2::VARCHAR,
                                                                                  item1.Semaforo3::VARCHAR,
                                                                                  item1.Semaforo4::VARCHAR,
                                                                                  item1.Semaforo5::VARCHAR,
                                                                                  item1.justificacion::VARCHAR,
                                                                                  item1.valor_real::VARCHAR);
                                                                              END IF;  
                                                                        END IF;
                                                                       --fin #2
                                                                    
                                                                 /* UPDATE ssig.tagrupador_indicador 
                                                                  SET 
                                                                      resultado=(SELECT ii.porcentaje from ssig.tinterpretacion_indicador ii where ii.id_gestion=v_parametros.id_gestion::INTEGER and ii.posicion=v_posicion::INTEGER)::INTEGER,
                                                                      id_interpretacion_indicador=(SELECT ii.id_interpretacion_indicador from ssig.tinterpretacion_indicador ii where ii.id_gestion=v_parametros.id_gestion::INTEGER and ii.posicion=v_posicion::INTEGER)::INTEGER,
                                                                      semaforo1=item1.Semaforo1::VARCHAR,
                                                                      semaforo2=item1.Semaforo2::VARCHAR,
                                                                      semaforo3=item1.Semaforo3::VARCHAR,
                                                                      semaforo4=item1.Semaforo4::VARCHAR,
                                                                      semaforo5=item1.Semaforo5::VARCHAR,
                                                                      valor_real=item1.valor_real::VARCHAR,
                                                                      semaforo=item1.semaforo::VARCHAR,
                                                                      comparacion=item1.comparacion::VARCHAR,
                                                                       -- El campo icono se cambio por color
                                                                      ruta_icono=(SELECT ii.color from ssig.tinterpretacion_indicador ii where ii.id_gestion=v_parametros.id_gestion::INTEGER and ii.posicion=v_posicion::INTEGER)::VARCHAR,
                                                                      justificacion=item1.justificacion
                                                                  WHERE id_agrupador_indicador=item1.id_agrupador_indicador::INTEGER;*/
                                                                    
                                                                  --v_gn2:=v_gn2+((SELECT ii.porcentaje * item1.peso from ssig.tinterpretacion_indicador ii where ii.id_gestion=v_parametros.id_gestion::INTEGER and ii.posicion=v_posicion::INTEGER)::NUMERIC / (v_peso_no_reporta::NUMERIC)::NUMERIC);
                                                              ELSE
                                                                
                                                                  IF NOT EXISTS(SELECT * from ssig.tagrupador_indicador_resultado air 
                                                                               where air.id_agrupador_indicador=item1.id_agrupador_indicador::INTEGER and 
                                                                               air.id_periodo=item_periodo.id_periodo::INTEGER) THEN 
                                                                                 
                                                                        insert into  ssig.tagrupador_indicador_resultado 
                                                                        (id_agrupador_indicador,
                                                                        id_periodo,
                                                                        resultado,
                                                                        id_gestion,
                                                                        semaforo1,
                                                                        semaforo2,
                                                                        semaforo3,
                                                                        semaforo4,
                                                                        semaforo5,
                                                                        justificacion,
                                                                        valor_real)VALUES
                                                                        (item1.id_agrupador_indicador::INTEGER,
                                                                        item_periodo.id_periodo::INTEGER,
                                                                        NULL,
                                                                        v_parametros.id_gestion,
                                                                        item1.Semaforo1::VARCHAR,
                                                                        item1.Semaforo2::VARCHAR,
                                                                        item1.Semaforo3::VARCHAR,
                                                                        item1.Semaforo4::VARCHAR,
                                                                        item1.Semaforo5::VARCHAR,
                                                                        item1.justificacion::VARCHAR,
                                                                        item1.valor_real::VARCHAR); 
                                                                  end if;
                                                                    
                                                                  /*UPDATE ssig.tagrupador_indicador 
                                                                  SET 
                                                                      resultado=NULL,
                                                                      semaforo1=item1.Semaforo1::VARCHAR,
                                                                      semaforo2=item1.Semaforo2::VARCHAR,
                                                                      semaforo3=item1.Semaforo3::VARCHAR,
                                                                      semaforo4=item1.Semaforo4::VARCHAR,
                                                                      semaforo5=item1.Semaforo5::VARCHAR,
                                                                      valor_real=item1.valor_real::VARCHAR,
                                                                      semaforo=item1.semaforo::VARCHAR,
                                                                      comparacion=item1.comparacion::VARCHAR,
                                                                      id_interpretacion_indicador=(SELECT ii.id_interpretacion_indicador from ssig.tinterpretacion_indicador ii where ii.id_gestion=v_parametros.id_gestion::INTEGER and ii.posicion=v_posicion::INTEGER)::INTEGER,
                                                                      justificacion=item1.justificacion
                                                                  WHERE id_agrupador_indicador=item1.id_agrupador_indicador::INTEGER;*/
                                                                    
                                                              end if;
                                                                
                                                      end loop;  
                                                      --raise exception 'error provocado % ','UPDATE ssig.tagrupador SET resultado='|| v_gn2::NUMERIC || 'WHERE id_agrupador='||item.id_agrupador::INTEGER;
                                                        
                                                      --calculamos nivel 2
                                                      if (SELECT sum((air.resultado::NUMERIC * ai.peso::NUMERIC)/(SELECT sum(aii.peso) from ssig.tagrupador_indicador aii  join ssig.tagrupador_indicador_resultado airr on airr.id_agrupador_indicador=aii.id_agrupador_indicador and airr.id_gestion=v_parametros.id_gestion::integer and airr.id_periodo=item_periodo.id_periodo::INTEGER   where aii.id_agrupador=item.id_agrupador and airr.resultado is not NULL)::NUMERIC) FROM ssig.tagrupador_indicador ai join ssig.tagrupador_indicador_resultado air on air.id_agrupador_indicador=ai.id_agrupador_indicador and air.id_gestion=v_parametros.id_gestion::integer and air.id_periodo=item_periodo.id_periodo::INTEGER  WHERE ai.id_agrupador=item.id_agrupador::INTEGER and air.resultado is not NULL)>0 then
                                                            
                                                          v_resultado :=(SELECT sum((air.resultado::NUMERIC * ai.peso::NUMERIC)/
                                                                                    (SELECT sum(aii.peso) from ssig.tagrupador_indicador aii join ssig.tagrupador_indicador_resultado airr on airr.id_agrupador_indicador=aii.id_agrupador_indicador and airr.id_gestion=v_parametros.id_gestion::integer and airr.id_periodo=item_periodo.id_periodo::INTEGER  where aii.id_agrupador=item.id_agrupador and airr.resultado is not NULL)::NUMERIC)
                                                                          FROM ssig.tagrupador_indicador ai 
                                                                          join ssig.tagrupador_indicador_resultado air on air.id_agrupador_indicador=ai.id_agrupador_indicador and air.id_gestion=v_parametros.id_gestion::integer and air.id_periodo=item_periodo.id_periodo::INTEGER   where ai.id_agrupador=item.id_agrupador::INTEGER and air.resultado is not NULL)::NUMERIC;
                                                                  
                                                          IF NOT EXISTS(SELECT * from ssig.tagrupador_resultado air 
                                                                       where air.id_agrupador=item.id_agrupador::INTEGER and 
                                                                       air.id_periodo=item_periodo.id_periodo::INTEGER) THEN
                                                                         
                                                                 
                                                                /*if(item.id_agrupador=121 and item_periodo.id_periodo=12 and v_resultado::INTEGER = 100::NUMERIC )then
                                                                   raise EXCEPTION 'verrr %',v_resultado;
                                                                   raise EXCEPTION 'errrr %',(SELECT ii.porcentaje FROM ssig.tinterpretacion_indicador ii where ii.id_gestion = v_parametros.id_gestion::INTEGER and (v_resultado::NUMERIC>=ii.porcentaje::NUMERIC) order by ii.porcentaje desc limit 1);
                                                                end if;*/
                                                                  
                                                                insert into  ssig.tagrupador_resultado 
                                                                (id_agrupador,
                                                                id_periodo,
                                                                resultado,
                                                                id_gestion,
                                                                ruta_icono)VALUES
                                                                (item.id_agrupador::INTEGER,
                                                                item_periodo.id_periodo::INTEGER,
                                                                v_resultado::NUMERIC,
                                                                v_parametros.id_gestion,
                                                                (SELECT ii.color FROM ssig.tinterpretacion_indicador ii where ii.id_gestion = v_parametros.id_gestion::INTEGER and (v_resultado::INTEGER>=ii.porcentaje::INTEGER) order by ii.porcentaje desc limit 1)
                                                                );
                                                          ELSE
                                                                UPDATE  ssig.tagrupador_resultado 
                                                                SET id_agrupador =item.id_agrupador::INTEGER,
                                                                id_periodo = item_periodo.id_periodo::INTEGER,
                                                                resultado = v_resultado::NUMERIC,
                                                                id_gestion = v_parametros.id_gestion::INTEGER,
                                                                ruta_icono = (SELECT ii.color FROM ssig.tinterpretacion_indicador ii where ii.id_gestion = v_parametros.id_gestion::INTEGER and (v_resultado::INTEGER>=ii.porcentaje::INTEGER) order by ii.porcentaje desc limit 1)
                                                                WHERE  id_agrupador=item.id_agrupador::INTEGER and id_periodo=item_periodo.id_periodo::INTEGER and id_gestion=v_parametros.id_gestion::INTEGER;
                                                          END IF;
                                                        
                                                          /*UPDATE ssig.tagrupador 
                                                          SET 
                                                              resultado=(SELECT sum((ai.resultado::NUMERIC * ai.peso::NUMERIC)/(SELECT sum(aii.peso) from ssig.tagrupador_indicador aii where aii.id_agrupador=item.id_agrupador and aii.resultado is not NULL)::NUMERIC) FROM ssig.tagrupador_indicador ai where ai.id_agrupador=item.id_agrupador::INTEGER and ai.resultado is not NULL)::NUMERIC
                                                          WHERE id_agrupador=item.id_agrupador::INTEGER;*/
                                                        
                                                      else
                                                        
                                                      IF NOT EXISTS(SELECT * from ssig.tagrupador_resultado air 
                                                                       where air.id_agrupador=item.id_agrupador::INTEGER and 
                                                                       air.id_periodo=item_periodo.id_periodo::INTEGER) THEN
                                                                          
                                                           insert into  ssig.tagrupador_resultado 
                                                            (id_agrupador,
                                                            id_periodo,
                                                            resultado,
                                                            id_gestion,
                                                            ruta_icono)VALUES
                                                            (item.id_agrupador::INTEGER,
                                                            item_periodo.id_periodo::INTEGER,
                                                            NULL,
                                                            v_parametros.id_gestion,
                                                            (SELECT ii.color FROM ssig.tinterpretacion_indicador ii where ii.id_gestion = v_parametros.id_gestion::INTEGER and (0::NUMERIC>=ii.porcentaje::NUMERIC) order by ii.porcentaje desc limit 1)
                                                            );
                                                         
                                                       END IF;  
                                                          
                                                          /*UPDATE ssig.tagrupador
                                                          SET 
                                                              resultado=0::NUMERIC
                                                          WHERE id_agrupador=item.id_agrupador::INTEGER;*/
                                                      end if;
                         
                                                        
                                                      --Calculamos nivel 1
                                                      v_resultado := (SELECT sum(ar.resultado::numeric * a.peso::numeric)/sum(a.peso::numeric)
                                                                      FROM ssig.tagrupador a 
                                                                      join ssig.tagrupador_resultado ar on ar.id_agrupador=a.id_agrupador and ar.id_gestion=v_parametros.id_gestion::INTEGER and ar.id_periodo=item_periodo.id_periodo::INTEGER  where a.id_agrupador_padre=item.id_agrupador_padre::INTEGER and ar.resultado is not null)::NUMERIC;          
                                                              
                                                      IF NOT EXISTS(SELECT * from ssig.tagrupador_resultado air 
                                                                       where air.id_agrupador=item.id_agrupador_padre::INTEGER and 
                                                                       air.id_periodo=item_periodo.id_periodo::INTEGER ) THEN
                                                              
                                                            /*IF(item.id_agrupador_padre::INTEGER = 157 and item_periodo.id_periodo::integer = 19 )then
                                                               --raise exception 'ids  %',v_parametros.id_gestion||' - '||item_periodo.id_periodo||' - '||item.id_agrupador_padre;
                                                               raise exception 'vvvv %', (SELECT 
                                                                                          sum(ar.resultado) 
                                                                                          FROM ssig.tagrupador a 
                                                                                          join ssig.tagrupador_resultado ar on ar.id_agrupador=a.id_agrupador and ar.id_gestion=2 and ar.id_periodo=19  
                                                                                          where a.id_agrupador_padre=157)::VARCHAR;
                                                               raise exception 'ver suma  %' ,(SELECT sum(ar.resultado) FROM ssig.tagrupador a join ssig.tagrupador_resultado ar on ar.id_agrupador=a.id_agrupador and ar.id_gestion=v_parametros.id_gestion::INTEGER and ar.id_periodo=item_periodo.id_periodo::INTEGER  where a.id_agrupador_padre=item.id_agrupador_padre::INTEGER)::VARCHAR;
                                                            end if;*/
                                                        
                                                              
                                                            insert into  ssig.tagrupador_resultado 
                                                            (id_agrupador,
                                                            id_periodo,
                                                            resultado,
                                                            id_gestion,
                                                            ruta_icono)VALUES
                                                            (item.id_agrupador_padre::INTEGER,
                                                            item_periodo.id_periodo::INTEGER,
                                                            v_resultado::NUMERIC,
                                                            v_parametros.id_gestion,
                                                            (SELECT ii.color FROM ssig.tinterpretacion_indicador ii where ii.id_gestion = v_parametros.id_gestion::INTEGER and (v_resultado::NUMERIC>=ii.porcentaje::NUMERIC) order by ii.porcentaje desc limit 1));
                                                        
                                                      ELSE
                                                            UPDATE  ssig.tagrupador_resultado 
                                                            SET id_agrupador =item.id_agrupador_padre::INTEGER,
                                                            id_periodo = item_periodo.id_periodo::INTEGER,
                                                            resultado = v_resultado::NUMERIC,
                                                            id_gestion = v_parametros.id_gestion::INTEGER,
                                                            ruta_icono = (SELECT ii.color FROM ssig.tinterpretacion_indicador ii where ii.id_gestion = v_parametros.id_gestion::INTEGER and (v_resultado::NUMERIC>=ii.porcentaje::NUMERIC) order by ii.porcentaje desc limit 1)
                                                            WHERE  id_agrupador=item.id_agrupador_padre::INTEGER and id_periodo=item_periodo.id_periodo::INTEGER and id_gestion=v_parametros.id_gestion::INTEGER;
                                                      END IF; 
                                                        
                                                      /*UPDATE ssig.tagrupador
                                                      SET 
                                                          resultado=(SELECT sum(a.resultado) FROM ssig.tagrupador a where a.id_agrupador_padre=item.id_agrupador_padre::INTEGER)::NUMERIC / (SELECT count(a.id_agrupador) FROM ssig.tagrupador a where a.id_agrupador_padre=item.id_agrupador_padre::INTEGER)::NUMERIC
                                                      WHERE id_agrupador=item.id_agrupador_padre::INTEGER;*/
                                                        
                                                        
                                                      --Calculamos nivel 0
                                                        
                                                        
                                                      v_resultado := (SELECT sum(ar.resultado::numeric * a.peso::numeric)/sum(a.peso::numeric) 
                                                                      FROM ssig.tagrupador a 
                                                                      join ssig.tagrupador_resultado ar on ar.id_agrupador=a.id_agrupador and ar.id_gestion=v_parametros.id_gestion::INTEGER and ar.id_periodo=item_periodo.id_periodo::INTEGER
                                                                      where a.id_agrupador_padre = (SELECT id_agrupador_padre FROM ssig.tagrupador where id_agrupador=item.id_agrupador_padre::INTEGER)::INTEGER and ar.resultado is not null)::NUMERIC;          
                                                             
                                                      IF NOT EXISTS(SELECT * from ssig.tagrupador_resultado air 
                                                                       where air.id_agrupador=(SELECT id_agrupador_padre FROM ssig.tagrupador where id_agrupador=item.id_agrupador_padre::INTEGER)::INTEGER and 
                                                                       air.id_periodo=item_periodo.id_periodo::INTEGER) THEN
                                                              
                                                            insert into  ssig.tagrupador_resultado 
                                                            (id_agrupador,
                                                            id_periodo,
                                                            resultado,
                                                            id_gestion,
                                                            ruta_icono)VALUES
                                                            ((SELECT id_agrupador_padre FROM ssig.tagrupador where id_agrupador=item.id_agrupador_padre::INTEGER)::INTEGER,
                                                            item_periodo.id_periodo::INTEGER,
                                                            v_resultado::NUMERIC,
                                                            v_parametros.id_gestion,
                                                            (SELECT ii.color FROM ssig.tinterpretacion_indicador ii where ii.id_gestion = v_parametros.id_gestion::INTEGER and (v_resultado::NUMERIC>=ii.porcentaje::NUMERIC) order by ii.porcentaje desc limit 1));
                                                        
                                                      ELSE
                                                            UPDATE  ssig.tagrupador_resultado 
                                                            SET id_agrupador = (SELECT id_agrupador_padre FROM ssig.tagrupador where id_agrupador=item.id_agrupador_padre::INTEGER)::INTEGER,
                                                            id_periodo = item_periodo.id_periodo::INTEGER,
                                                            resultado = v_resultado::NUMERIC,
                                                            id_gestion = v_parametros.id_gestion::INTEGER,
                                                            ruta_icono = (SELECT ii.color FROM ssig.tinterpretacion_indicador ii where ii.id_gestion = v_parametros.id_gestion::INTEGER and (v_resultado::NUMERIC>=ii.porcentaje::NUMERIC) order by ii.porcentaje desc limit 1)
                                                            where id_agrupador = (SELECT id_agrupador_padre FROM ssig.tagrupador where id_agrupador=item.id_agrupador_padre::INTEGER)::INTEGER and id_periodo = item_periodo.id_periodo::INTEGER and id_gestion=v_parametros.id_gestion::INTEGER;
                                                      END IF;  
                                                        
                                                      /* UPDATE ssig.tagrupador
                                                      SET 
                                                          resultado=(SELECT sum(a.resultado) FROM ssig.tagrupador a where a.id_agrupador_padre=(SELECT id_agrupador_padre FROM ssig.tagrupador where id_agrupador=item.id_agrupador_padre::INTEGER)::INTEGER)::NUMERIC / (SELECT count(a.id_agrupador) FROM ssig.tagrupador a where a.id_agrupador_padre=(SELECT id_agrupador_padre FROM ssig.tagrupador where id_agrupador=item.id_agrupador_padre::INTEGER)::INTEGER)::NUMERIC
                                                      WHERE id_agrupador=(SELECT id_agrupador_padre FROM ssig.tagrupador where id_agrupador=item.id_agrupador_padre::INTEGER)::INTEGER;*/
                                                                
                                              END IF;

                                      END LOOP;
                        END loop;               
                        --Fin calculo de resultados
                      END IF;
                        
                    ELSE
                      UPDATE ssig.tagrupador
                      SET
                        aprobado = v_parametros.aprobado :: BIT
                      WHERE id_gestion = v_parametros.id_gestion;
                    END IF;
                      
                    --Definicion de la respuesta
                    v_resp = pxp.f_agrega_clave(v_resp, 'mensaje', 'Modificado(s)');
                    --Devuelve la respuesta
                    RETURN v_resp;
                    
            else --#1 inicio
            
           select g.gestion
                    INTO v_gestion 
                    from param.tgestion g 
                    where g.id_gestion=v_parametros.id_gestion;
                      
                    UPDATE ssig.tagrupador
                    SET
                      resultado=0.00:: numeric,
                      id_interpretacion_indicador=NULL
                    WHERE id_gestion = v_parametros.id_gestion;

                    UPDATE ssig.tagrupador_indicador
                    SET
                      resultado=0.00:: numeric,
                      semaforo1='',
                      semaforo2='',
                      semaforo3='',
                      semaforo4='',
                      semaforo5='',
                      valor_real='',
                      semaforo='',
                      comparacion='',
                      ruta_icono='',
                      justificacion='',
                      id_interpretacion_indicador=NULL
                    WHERE id_agrupador IN(SELECT a.id_agrupador FROM ssig.tagrupador a join ssig.tagrupador_indicador ai on ai.id_agrupador=a.id_agrupador WHERE a.id_gestion = v_parametros.id_gestion);
                      
                    if NOT EXISTS(SELECT * FROM ssig.tinterpretacion_indicador WHERE id_gestion=v_parametros.id_gestion::INTEGER)then
                          INSERT INTO ssig.tinterpretacion_indicador ("id_usuario_reg", "fecha_reg", "estado_reg", "interpretacion", "color", "icono", "porcentaje", "id_gestion","posicion")
                          VALUES 
                            (1, (SELECT fecha_reg from param.tgestion order by id_gestion desc limit 1), E'activo', 'Cumplimiento', '#3399CC', '../../../sis_segintegralgestion/vista/ImagenesInterpretacionIndicador/Cumplimiento.png', 100, v_parametros.id_gestion::INTEGER,1),
                            (1, (SELECT fecha_reg from param.tgestion order by id_gestion desc limit 1), E'activo', 'Exito', '#66CC99', '../../../sis_segintegralgestion/vista/ImagenesInterpretacionIndicador/Exito.png', 85, v_parametros.id_gestion::INTEGER,2),
                            (1, (SELECT fecha_reg from param.tgestion order by id_gestion desc limit 1), E'activo', 'Riesgo', '#F0D58C', '../../../sis_segintegralgestion/vista/ImagenesInterpretacionIndicador/Riesgo.png', 50, v_parametros.id_gestion::INTEGER,3),
                            (1, (SELECT fecha_reg from param.tgestion order by id_gestion desc limit 1), E'activo', 'Fracaso', '#FA8072', '../../../sis_segintegralgestion/vista/ImagenesInterpretacionIndicador/Fracaso.png', 0, v_parametros.id_gestion::INTEGER,4);
                    end if;
                    --Fin restauracion de agrupadores y agrupadores_indicadores
                    IF v_parametros.aprobado = 1
                    THEN
                      IF exists(SELECT 1
                                FROM ssig.tagrupador
                                WHERE id_gestion = v_parametros.id_gestion AND (nivel < 2 OR nivel IS NULL) AND
                                      (peso_acumulado < 100 OR peso_acumulado IS NULL)) 
                                OR
                                (SELECT 
                                  CASE 
                                      WHEN SUM(agin.peso)<100 
                                          THEN TRUE::BOOLEAN
                                      ELSE 
                                          FALSE::BOOLEAN    
                                  END AS TOTAL
                                  FROM ssig.tagrupador_indicador agin
                                  LEFT JOIN ssig.tagrupador padre ON padre.id_agrupador = agin.id_agrupador
                                  INNER JOIN segu.tusuario usu1 ON usu1.id_usuario = agin.id_usuario_reg                        
                                  LEFT JOIN segu.tusuario usu2 ON usu2.id_usuario = agin.id_usuario_mod           
                                  INNER JOIN ssig.tindicador ind ON ind.id_indicador = agin.id_indicador
                                  --LEFT JOIN orga.tfuncionario t ON t.id_funcionario= agin.id_funcionario_ingreso 
                                  --LEFT JOIN orga.tfuncionario t1 ON t1.id_funcionario= agin.id_funcionario_evaluacion
                                  --INNER JOIN segu.tpersona per ON per.id_persona = t.id_persona
                                  --INNER JOIN segu.vpersona PERSON ON PERSON.id_persona = per.id_persona 
                                  --INNER JOIN segu.tpersona per1 ON per1.id_persona = t1.id_persona
                                  --INNER JOIN segu.vpersona PERSON1 ON PERSON1.id_persona = per1.id_persona
                                  WHERE agin.peso <100 AND padre.id_gestion = v_parametros.id_gestion
                                  GROUP BY agin.id_agrupador
                                  LIMIT 1)                                                                  
                      THEN
                        RAISE EXCEPTION 'no estan validados los datos, le falta llenar un nivel, o le falta completar un peso';
                      ELSE
                        UPDATE ssig.tagrupador
                        SET
                          aprobado = v_parametros.aprobado :: BIT
                        WHERE id_gestion = v_parametros.id_gestion;
                        --Calcular resultados para la tabla agrupadores y la atabla agrupador_indicador

                          
                        DELETE FROM ssig.tagrupador_resultado where id_gestion = v_parametros.id_gestion;
                        DELETE FROM ssig.tagrupador_indicador_resultado where id_gestion = v_parametros.id_gestion;
                          
                        FOR item_periodo IN(SELECT p.id_gestion,p.id_periodo,p.fecha_fin, extract(MONTH from p.fecha_fin) as periodo from param.tperiodo p 
                                            where p.id_gestion=v_parametros.id_gestion
                                            order by p.fecha_fin asc )LOOP

                                      FOR item IN(WITH RECURSIVE arb_agrupador AS(
                                                              SELECT a.*,
                                                              a.nombre::TEXT AS ancestros
                                                              FROM ssig.tagrupador a
                                                              WHERE a.id_agrupador_padre IS NULL
                                                  UNION ALL
                                                           SELECT a2.*,
                                                           (al.ancestros || '->' || a2.nombre)::TEXT AS ancestros
                                                           FROM ssig.tagrupador a2
                                                           JOIN arb_agrupador al ON al.id_agrupador=a2.id_agrupador_padre)  
                                              SELECT 
                                                   arb.id_agrupador::INTEGER, 
                                                   arb.id_agrupador_padre::INTEGER,
                                                   arb.nombre,
                                                   arb.peso,
                                                   arb.peso_acumulado,
                                                   arb.aprobado,
                                                   arb.nivel,
                                                   (select   array_to_string (ARRAY_AGG(aa.id_agrupador),',')::VARCHAR from ssig.tagrupador aa where aa.id_agrupador_padre=arb.id_agrupador)::VARCHAR as cod_hijos,
                                                   (select array_to_string( array_agg( i.id_indicador), ',' )
                                                     from ssig.tagrupador_indicador ai 
                                                     join ssig.tindicador i on i.id_indicador=ai.id_indicador
                                                     where ai.id_agrupador=arb.id_agrupador)::VARCHAR as cod_indicadores,
                                                   arb.resultado 
                                              FROM arb_agrupador arb 
                                              left  join ssig.tagrupador agr on agr.id_agrupador=arb.id_agrupador_padre
                                              AND agr.id_gestion=v_parametros.id_gestion    
                                              order by arb.ancestros asc) LOOP
                                                
                                                
                                              IF(item.nivel::INTEGER = 2) THEN
                                                
                                                      v_gn2:=0::NUMERIC;
                                                      v_peso_no_reporta=0::NUMERIC;
                                                      FOR item1 IN  (SELECT ai.id_agrupador_indicador,
                                                                           i.id_indicador
                                                                    from ssig.tagrupador a 
                                                                    join ssig.tagrupador_indicador ai on a.id_agrupador=ai.id_agrupador 
                                                                    join ssig.tindicador i on i.id_indicador=ai.id_indicador
                                                                    join ssig.tindicador_unidad iu on iu.id_indicador_unidad=i.id_indicador_unidad
                                                                    where a.id_gestion=v_parametros.id_gestion and a.id_agrupador=item.id_agrupador ) LOOP
                         
                                                            -- TENER CUIDADO EL EL WHERE CON ELIMINAR TODOS LOS VALORES REALES EXISTENTES
                                                              UPDATE ssig.tindicador_valor
                                                                  SET valor=NULL
                                                              where  (id_indicador=item1.id_indicador and no_reporta='No reporta') or (id_indicador=item1.id_indicador and valor='');    
                                                            ------------------------------
                                                                          
                                                      END LOOP;
                                                        
                                                      v_filtro_periodo :='';
                                                      --RAISE EXCEPTION 'error per %',(SELECT  extract(MONTH from p.fecha_ini)   FROM param.tperiodo p where p.id_periodo=v_parametros.id_periodo);  
                                                      v_filtro_periodo :=(SELECT  extract(MONTH from p.fecha_fin)   FROM param.tperiodo p where p.id_periodo=item_periodo.id_periodo)::VARCHAR;
                         
                                                       
                                                     FOR item1 IN  (SELECT ai.id_agrupador_indicador,
                                                                                   ai.peso,i.id_indicador,
                                                                                   (SELECT iv.valor from ssig.tindicador_valor iv where iv.id_indicador=i.id_indicador  and extract(MONTH from iv.fecha) <= v_filtro_periodo::INTEGER order by iv.fecha desc  limit 1) as valor_real,
                                                                                   (SELECT iv.semaforo3 from ssig.tindicador_valor iv where iv.id_indicador=i.id_indicador and extract(MONTH from iv.fecha) <= v_filtro_periodo::INTEGER order by iv.fecha desc  limit 1) as valor_meta,
                                                                                   i.semaforo,
                                                                                   i.comparacion,
                                                                                   (SELECT iv.semaforo1 from ssig.tindicador_valor iv where iv.id_indicador=i.id_indicador  and extract(MONTH from iv.fecha) <= v_filtro_periodo::INTEGER order by iv.fecha desc  limit 1)::VARCHAR as Semaforo1, -- <= v_filtro_periodo
                                                                                   (SELECT iv.semaforo2 from ssig.tindicador_valor iv where iv.id_indicador=i.id_indicador  and extract(MONTH from iv.fecha) <= v_filtro_periodo::INTEGER order by iv.fecha desc  limit 1)::VARCHAR as Semaforo2,
                                                                                   (SELECT iv.semaforo3 from ssig.tindicador_valor iv where iv.id_indicador=i.id_indicador  and extract(MONTH from iv.fecha) <= v_filtro_periodo::INTEGER order by iv.fecha desc  limit 1)::VARCHAR as Semaforo3,
                                                                                   (SELECT iv.semaforo4 from ssig.tindicador_valor iv where iv.id_indicador=i.id_indicador  and extract(MONTH from iv.fecha) <= v_filtro_periodo::INTEGER order by iv.fecha desc  limit 1)::VARCHAR as Semaforo4,
                                                                                   (SELECT iv.semaforo5 from ssig.tindicador_valor iv where iv.id_indicador=i.id_indicador  and extract(MONTH from iv.fecha) <= v_filtro_periodo::INTEGER order by iv.fecha desc  limit 1)::VARCHAR as Semaforo5,
                                                                               (SELECT iv.justificacion from ssig.tindicador_valor iv where iv.id_indicador=i.id_indicador  and extract(MONTH from iv.fecha) <= v_filtro_periodo::INTEGER order by iv.fecha desc  limit 1)::VARCHAR as justificacion,
                                                                                   iu.tipo,
                                                                                  (SELECT iv.no_reporta from ssig.tindicador_valor iv where iv.id_indicador=i.id_indicador  and extract(MONTH from iv.fecha) <= v_filtro_periodo::INTEGER order by iv.fecha desc  limit 1)::VARCHAR as no_reporta,
                                                                                   ai.peso,
                                                                                   a.id_agrupador
                                                                            from ssig.tagrupador a 
                                                                            join ssig.tagrupador_indicador ai on a.id_agrupador=ai.id_agrupador 
                                                                            join ssig.tindicador i on i.id_indicador=ai.id_indicador
                                                                            join ssig.tindicador_unidad iu on iu.id_indicador_unidad=i.id_indicador_unidad
                                                                            where a.id_gestion=v_parametros.id_gestion and a.id_agrupador=item.id_agrupador ) LOOP
                                                           
                                                                  
                                                              v_posicion:=0;
                                                                
                                                              IF(item1.semaforo='Simple' AND item1.comparacion='Asc' AND item1.no_reporta !='No reporta')THEN
                                                                 IF(item1.tipo='Numero' or item1.tipo='Hrs')THEN
                                                                    IF(item1.valor_real::NUMERIC >= item1.Semaforo3::NUMERIC and  v_gestion <=2018 )then
                                                                    --azul
                                                                     v_posicion:=1;
                                                                    ELSE
                                                                        IF( (item1.valor_real::NUMERIC > item1.Semaforo2::NUMERIC))then
                                                                           --verde
                                                                            v_posicion:=2;
                                                                        ELSE
                                                                            IF((item1.valor_real::NUMERIC <= item1.Semaforo2::NUMERIC) and (item1.valor_real::NUMERIC > item1.Semaforo1::NUMERIC))then
                                                                               --amarillo
                                                                                v_posicion:=3;
                                                                            ELSE
                                                                                IF(item1.valor_real::NUMERIC <= item1.Semaforo1::NUMERIC)then
                                                                                --rojo
                                                                                 v_posicion:=4;
                                                                                END IF;
                                                                            END IF;
                                                                        END IF;
                                                                    END IF; 
                                                                 ELSE
                                                                     IF(item1.tipo='Fecha')THEN
                                                                        IF(item1.valor_real::DATE >= item1.Semaforo3::DATE  and  v_gestion <=2018)then
                                                                        --azul
                                                                         v_posicion:=1;
                                                                        ELSE
                                                                            IF( item1.valor_real::DATE > item1.Semaforo2::DATE)then
                                                                               --verde
                                                                                v_posicion:=2;
                                                                            ELSE
                                                                                IF((item1.valor_real::DATE <= item1.Semaforo2::DATE) and (item1.valor_real::DATE > item1.Semaforo1::DATE))then
                                                                                   --amarillo
                                                                                    v_posicion:=3;
                                                                                ELSE
                                                                                    IF(item1.valor_real::DATE <= item1.Semaforo1::DATE)then
                                                                                    --rojo
                                                                                     v_posicion:=4;
                                                                                    END IF;
                                                                                END IF;
                                                                            END IF;
                                                                        END IF; 
                                                                     END IF;
                                                                 END IF;
                                                              END IF;
                                                              IF(item1.semaforo='Simple' AND item1.comparacion='Desc' AND item1.no_reporta !='No reporta')THEN
                                                                   IF(item1.tipo='Numero' or item1.tipo='Hrs')THEN
                                                                      IF(item1.valor_real::NUMERIC <= item1.Semaforo3::NUMERIC and  v_gestion <=2018 )then
                                                                      --azul
                                                                       v_posicion:=1;
                                                                      ELSE
                                                                          IF (item1.valor_real::NUMERIC <= item1.Semaforo2::NUMERIC)then
                                                                             --verde
                                                                              v_posicion:=2;
                                                                          ELSE
                                                                              IF((item1.valor_real::NUMERIC > item1.Semaforo2::NUMERIC) and (item1.valor_real::NUMERIC <= item1.Semaforo1::NUMERIC))then
                                                                                 --amarillo
                                                                                  v_posicion:=3;
                                                                              ELSE
                                                                                  IF(item1.valor_real::NUMERIC > item1.Semaforo1::NUMERIC)then
                                                                                  --rojo
                                                                                   v_posicion:=4;
                                                                                  END IF;
                                                                              END IF;
                                                                          END IF;
                                                                      END IF; 
                                                                   ELSE
                                                                       IF(item1.tipo='Fecha')THEN
                                                                          IF(item1.valor_real::DATE <= item1.Semaforo3::DATE and  v_gestion <=2018)then
                                                                          --azul
                                                                           v_posicion:=1;
                                                                          ELSE
                                                                              IF( item1.valor_real::DATE <= item1.Semaforo2::DATE)then
                                                                                 --verde4
                                                                                  v_posicion:=2;
                                                                              ELSE
                                                                                  IF((item1.valor_real::DATE > item1.Semaforo2::DATE) and (item1.valor_real::DATE <= item1.Semaforo1::DATE))then
                                                                                     --amarillo
                                                                                      v_posicion:=3;
                                                                                  ELSE
                                                                                      IF(item1.valor_real::DATE > item1.Semaforo1::DATE)then
                                                                                      --rojo
                                                                                       v_posicion:=4;
                                                                                      END IF;
                                                                                  END IF;
                                                                              END IF;
                                                                          END IF; 
                                                                       END IF;
                                                                   END IF;
                                                              END IF;
                                                              IF(item1.semaforo='Compuesto' AND item1.comparacion='Asc' AND item1.no_reporta !='No reporta')THEN
                                                                  IF(item1.tipo='Numero' or item1.tipo='Hrs')THEN
                                                                      IF((item1.valor_real::NUMERIC > item1.Semaforo5::NUMERIC) or (item1.valor_real::NUMERIC < item1.Semaforo1::NUMERIC) )then
                                                                      --azul
                                                                       v_posicion:=1;
                                                                      ELSE
                                                                          IF((item1.valor_real::NUMERIC <= item1.Semaforo5::NUMERIC and item1.valor_real::NUMERIC > item1.Semaforo4::NUMERIC) or (item1.valor_real::NUMERIC >= item1.Semaforo1::NUMERIC and item1.valor_real::NUMERIC < item1.Semaforo2::NUMERIC))then
                                                                             --verde
                                                                              v_posicion:=2;
                                                                          ELSE
                                                                              IF((item1.valor_real::NUMERIC <= item1.Semaforo4::NUMERIC and item1.valor_real::NUMERIC > item1.Semaforo3::NUMERIC) or (item1.valor_real::NUMERIC <= item1.Semaforo3::NUMERIC and item1.valor_real::NUMERIC >= item1.Semaforo2::NUMERIC))then
                                                                                 --amarillo
                                                                                  v_posicion:=3;
                                                                              ELSE
                                                                                  IF(item1.valor_real::NUMERIC = item1.Semaforo3::NUMERIC)then
                                                                                  --rojo
                                                                                   v_posicion:=4;
                                                                                  END IF;
                                                                              END IF;
                                                                          END IF;
                                                                      END IF; 
                                                                   ELSE
                                                                       IF(item1.tipo='Fecha')THEN
                                                                              IF((item1.valor_real::DATE > item1.Semaforo5::DATE) or (item1.valor_real::DATE < item1.Semaforo1::DATE) )then
                                                                              --azul
                                                                               v_posicion:=1;
                                                                              ELSE
                                                                                  IF((item1.valor_real::DATE <= item1.Semaforo5::DATE and item1.valor_real::DATE > item1.Semaforo4::DATE) or (item1.valor_real::DATE >= item1.Semaforo1::DATE and item1.valor_real::DATE < item1.Semaforo2::DATE))then
                                                                                     --verde
                                                                                      v_posicion:=2;
                                                                                  ELSE
                                                                                      IF((item1.valor_real::DATE <= item1.Semaforo4::DATE and item1.valor_real::DATE > item1.Semaforo3::DATE) or (item1.valor_real::DATE <= item1.Semaforo3::DATE and item1.valor_real::DATE >= item1.Semaforo2::DATE))then
                                                                                         --amarillo
                                                                                          v_posicion:=3;
                                                                                      ELSE
                                                                                          IF(item1.valor_real::DATE = item1.Semaforo3::DATE)then
                                                                                          --rojo
                                                                                           v_posicion:=4;
                                                                                          END IF;
                                                                                      END IF;
                                                                                  END IF;
                                                                              END IF; 
                                                                       END IF;
                                                                   END IF;
                                                              END IF;
                                                              IF(item1.semaforo='Compuesto' AND item1.comparacion='Desc' AND item1.no_reporta !='No reporta')THEN
                                                                    IF(item1.tipo='Numero' or item1.tipo='Hrs')THEN
                                                                        IF((item1.valor_real::NUMERIC < item1.Semaforo5::NUMERIC) or (item1.valor_real::NUMERIC > item1.Semaforo1::NUMERIC) )then
                                                                        --rojo
                                                                         v_posicion:=1;
                                                                        ELSE
                                                                            IF((item1.valor_real::NUMERIC >= item1.Semaforo5::NUMERIC and item1.valor_real::NUMERIC < item1.Semaforo4::NUMERIC) OR (item1.valor_real::NUMERIC > item1.Semaforo2::NUMERIC and item1.valor_real::NUMERIC <= item1.Semaforo1::NUMERIC))then
                                                                               --amarillo
                                                                                v_posicion:=2;
                                                                            ELSE
                                                                                IF((item1.valor_real::NUMERIC >= item1.Semaforo4::NUMERIC and item1.valor_real::NUMERIC < item1.Semaforo3::NUMERIC) OR (item1.valor_real::NUMERIC > item1.Semaforo3::NUMERIC and item1.valor_real::NUMERIC <= item1.Semaforo2::NUMERIC))then
                                                                                   --verde
                                                                                    v_posicion:=3;
                                                                                ELSE
                                                                                    IF(item1.valor_real::NUMERIC = item1.Semaforo3::NUMERIC)then
                                                                                    --azul
                                                                                     v_posicion:=4;
                                                                                    END IF;
                                                                                END IF;
                                                                            END IF;
                                                                        END IF; 
                                                                     ELSE
                                                                         IF(item1.tipo='Fecha')THEN
                                                                              IF((item1.valor_real::DATE < item1.Semaforo5::DATE) or (item1.valor_real::DATE > item1.Semaforo1::DATE) )then
                                                                              --rojo
                                                                               v_posicion:=1;
                                                                              ELSE
                                                                                  IF((item1.valor_real::DATE >= item1.Semaforo5::DATE and item1.valor_real::DATE < item1.Semaforo4::DATE) OR (item1.valor_real::DATE > item1.Semaforo2::DATE and item1.valor_real::DATE <= item1.Semaforo1::DATE))then
                                                                                     --amarillo
                                                                                      v_posicion:=2;
                                                                                  ELSE
                                                                                      IF((item1.valor_real::DATE >= item1.Semaforo4::DATE and item1.valor_real::DATE < item1.Semaforo3::DATE) OR (item1.valor_real::DATE > item1.Semaforo3::DATE and item1.valor_real::DATE <= item1.Semaforo2::DATE))then
                                                                                         --verde
                                                                                          v_posicion:=3;
                                                                                      ELSE
                                                                                          IF(item1.valor_real::DATE = item1.Semaforo3::DATE)then
                                                                                          --azul
                                                                                           v_posicion:=4;
                                                                                          END IF;
                                                                                      END IF;
                                                                                  END IF;
                                                                              END IF; 
                                                                         END IF;
                                                                     END IF;
                                                              END IF;
                                                                
                                                                                                

                                                                
                                                              --Insertamos resultados a la tabala agrupador indicador
                                                              --RAISE EXCEPTION 'Error provocado por juan jimenez  %',(SELECT ii.porcentaje from ssig.tinterpretacion_indicador ii where ii.id_gestion=v_parametros.id_gestion::INTEGER and ii.posicion=v_posicion::INTEGER)::INTEGER;
                                                              --Calculamos nivel 3 
                                                              IF(item1.no_reporta !='No reporta')THEN
                                                                          
                                                                      -- inicio #2 endetr Juan  27/02/2019 se agrego este if para considerar el no se hizo
                                                                      IF(item1.no_reporta ='No se hizo')THEN

                                                                              IF NOT EXISTS(SELECT * from ssig.tagrupador_indicador_resultado air 
                                                                                           where air.id_agrupador_indicador=item1.id_agrupador_indicador::INTEGER and 
                                                                                           air.id_periodo=item_periodo.id_periodo::INTEGER) THEN
                                                                                  insert into  ssig.tagrupador_indicador_resultado 
                                                                                  (id_agrupador_indicador,
                                                                                  id_periodo,
                                                                                  resultado,
                                                                                  id_gestion,
                                                                                  ruta_icono,
                                                                                  semaforo1,
                                                                                  semaforo2,
                                                                                  semaforo3,
                                                                                  semaforo4,
                                                                                  semaforo5,
                                                                                  justificacion,
                                                                                  valor_real,
                                                                                  no_reporta)VALUES
                                                                                  (item1.id_agrupador_indicador::INTEGER,
                                                                                  item_periodo.id_periodo::INTEGER,
                                                                                  0::INTEGER,
                                                                                  v_parametros.id_gestion,
                                                                                  --la variable v_posicion se cambio a 4 para el caso no se hizo  fracaso
                                                                                  (SELECT ii.color from ssig.tinterpretacion_indicador ii where ii.id_gestion=v_parametros.id_gestion::INTEGER and ii.posicion=1::INTEGER)::VARCHAR,
                                                                                  item1.Semaforo1::VARCHAR,
                                                                                  item1.Semaforo2::VARCHAR,
                                                                                  item1.Semaforo3::VARCHAR,
                                                                                  item1.Semaforo4::VARCHAR,
                                                                                  item1.Semaforo5::VARCHAR,
                                                                                  item1.justificacion::VARCHAR,
                                                                                  item1.valor_real::VARCHAR,
                                                                                  item1.no_reporta::VARCHAR);
                                                                              END IF;    
                                                                        ELSE
                                                                              IF NOT EXISTS(SELECT * from ssig.tagrupador_indicador_resultado air 
                                                                                           where air.id_agrupador_indicador=item1.id_agrupador_indicador::INTEGER and 
                                                                                           air.id_periodo=item_periodo.id_periodo::INTEGER) THEN
                                                                                  insert into  ssig.tagrupador_indicador_resultado 
                                                                                  (id_agrupador_indicador,
                                                                                  id_periodo,
                                                                                  resultado,
                                                                                  id_gestion,
                                                                                  ruta_icono,
                                                                                  semaforo1,
                                                                                  semaforo2,
                                                                                  semaforo3,
                                                                                  semaforo4,
                                                                                  semaforo5,
                                                                                  justificacion,
                                                                                  valor_real,
                                                                                  no_reporta)VALUES
                                                                                  (item1.id_agrupador_indicador::INTEGER,
                                                                                  item_periodo.id_periodo::INTEGER,
                                                                                  (SELECT ii.porcentaje from ssig.tinterpretacion_indicador ii where ii.id_gestion=v_parametros.id_gestion::INTEGER and ii.posicion=v_posicion::INTEGER)::INTEGER,
                                                                                  v_parametros.id_gestion,
                                                                                  -- El campo icono se cambio por color
                                                                                  (SELECT ii.color from ssig.tinterpretacion_indicador ii where ii.id_gestion=v_parametros.id_gestion::INTEGER and ii.posicion=v_posicion::INTEGER)::VARCHAR,
                                                                                  item1.Semaforo1::VARCHAR,
                                                                                  item1.Semaforo2::VARCHAR,
                                                                                  item1.Semaforo3::VARCHAR,
                                                                                  item1.Semaforo4::VARCHAR,
                                                                                  item1.Semaforo5::VARCHAR,
                                                                                  item1.justificacion::VARCHAR,
                                                                                  item1.valor_real::VARCHAR,
                                                                                  item1.no_reporta::VARCHAR);
                                                                              END IF;  
                                                                        END IF;
                                                                       --fin #2
                                                                    
                                                                 /* UPDATE ssig.tagrupador_indicador 
                                                                  SET 
                                                                      resultado=(SELECT ii.porcentaje from ssig.tinterpretacion_indicador ii where ii.id_gestion=v_parametros.id_gestion::INTEGER and ii.posicion=v_posicion::INTEGER)::INTEGER,
                                                                      id_interpretacion_indicador=(SELECT ii.id_interpretacion_indicador from ssig.tinterpretacion_indicador ii where ii.id_gestion=v_parametros.id_gestion::INTEGER and ii.posicion=v_posicion::INTEGER)::INTEGER,
                                                                      semaforo1=item1.Semaforo1::VARCHAR,
                                                                      semaforo2=item1.Semaforo2::VARCHAR,
                                                                      semaforo3=item1.Semaforo3::VARCHAR,
                                                                      semaforo4=item1.Semaforo4::VARCHAR,
                                                                      semaforo5=item1.Semaforo5::VARCHAR,
                                                                      valor_real=item1.valor_real::VARCHAR,
                                                                      semaforo=item1.semaforo::VARCHAR,
                                                                      comparacion=item1.comparacion::VARCHAR,
                                                                       -- El campo icono se cambio por color
                                                                      ruta_icono=(SELECT ii.color from ssig.tinterpretacion_indicador ii where ii.id_gestion=v_parametros.id_gestion::INTEGER and ii.posicion=v_posicion::INTEGER)::VARCHAR,
                                                                      justificacion=item1.justificacion
                                                                  WHERE id_agrupador_indicador=item1.id_agrupador_indicador::INTEGER;*/
                                                                    
                                                                  --v_gn2:=v_gn2+((SELECT ii.porcentaje * item1.peso from ssig.tinterpretacion_indicador ii where ii.id_gestion=v_parametros.id_gestion::INTEGER and ii.posicion=v_posicion::INTEGER)::NUMERIC / (v_peso_no_reporta::NUMERIC)::NUMERIC);
                                                              ELSE
                                                                
                                                                  IF NOT EXISTS(SELECT * from ssig.tagrupador_indicador_resultado air 
                                                                               where air.id_agrupador_indicador=item1.id_agrupador_indicador::INTEGER and 
                                                                               air.id_periodo=item_periodo.id_periodo::INTEGER) THEN 
                                                                                 
                                                                        insert into  ssig.tagrupador_indicador_resultado 
                                                                        (id_agrupador_indicador,
                                                                        id_periodo,
                                                                        resultado,
                                                                        id_gestion,
                                                                        semaforo1,
                                                                        semaforo2,
                                                                        semaforo3,
                                                                        semaforo4,
                                                                        semaforo5,
                                                                        justificacion,
                                                                        valor_real,
                                                                        no_reporta)VALUES
                                                                        (item1.id_agrupador_indicador::INTEGER,
                                                                        item_periodo.id_periodo::INTEGER,
                                                                        NULL,
                                                                        v_parametros.id_gestion,
                                                                        item1.Semaforo1::VARCHAR,
                                                                        item1.Semaforo2::VARCHAR,
                                                                        item1.Semaforo3::VARCHAR,
                                                                        item1.Semaforo4::VARCHAR,
                                                                        item1.Semaforo5::VARCHAR,
                                                                        item1.justificacion::VARCHAR,
                                                                        item1.valor_real::VARCHAR,
                                                                        item1.no_reporta::VARCHAR); 
                                                                  end if;
                                                                    
                                                                  /*UPDATE ssig.tagrupador_indicador 
                                                                  SET 
                                                                      resultado=NULL,
                                                                      semaforo1=item1.Semaforo1::VARCHAR,
                                                                      semaforo2=item1.Semaforo2::VARCHAR,
                                                                      semaforo3=item1.Semaforo3::VARCHAR,
                                                                      semaforo4=item1.Semaforo4::VARCHAR,
                                                                      semaforo5=item1.Semaforo5::VARCHAR,
                                                                      valor_real=item1.valor_real::VARCHAR,
                                                                      semaforo=item1.semaforo::VARCHAR,
                                                                      comparacion=item1.comparacion::VARCHAR,
                                                                      id_interpretacion_indicador=(SELECT ii.id_interpretacion_indicador from ssig.tinterpretacion_indicador ii where ii.id_gestion=v_parametros.id_gestion::INTEGER and ii.posicion=v_posicion::INTEGER)::INTEGER,
                                                                      justificacion=item1.justificacion
                                                                  WHERE id_agrupador_indicador=item1.id_agrupador_indicador::INTEGER;*/
                                                                    
                                                              end if;
                                                                
                                                      end loop;  
                                                      --raise exception 'error provocado % ','UPDATE ssig.tagrupador SET resultado='|| v_gn2::NUMERIC || 'WHERE id_agrupador='||item.id_agrupador::INTEGER;
                                                        
                                                      --calculamos nivel 2
                                                      if (SELECT sum((air.resultado::NUMERIC * ai.peso::NUMERIC)/(SELECT sum(aii.peso) from ssig.tagrupador_indicador aii  join ssig.tagrupador_indicador_resultado airr on airr.id_agrupador_indicador=aii.id_agrupador_indicador and airr.id_gestion=v_parametros.id_gestion::integer and airr.id_periodo=item_periodo.id_periodo::INTEGER   where aii.id_agrupador=item.id_agrupador and airr.resultado is not NULL)::NUMERIC) FROM ssig.tagrupador_indicador ai join ssig.tagrupador_indicador_resultado air on air.id_agrupador_indicador=ai.id_agrupador_indicador and air.id_gestion=v_parametros.id_gestion::integer and air.id_periodo=item_periodo.id_periodo::INTEGER  WHERE ai.id_agrupador=item.id_agrupador::INTEGER and air.resultado is not NULL)>0 then
                                                            
                                                          v_resultado :=(SELECT sum((air.resultado::NUMERIC * ai.peso::NUMERIC)/
                                                                                    (SELECT sum(aii.peso) from ssig.tagrupador_indicador aii join ssig.tagrupador_indicador_resultado airr on airr.id_agrupador_indicador=aii.id_agrupador_indicador and airr.id_gestion=v_parametros.id_gestion::integer and airr.id_periodo=item_periodo.id_periodo::INTEGER  where aii.id_agrupador=item.id_agrupador and airr.resultado is not NULL)::NUMERIC)
                                                                          FROM ssig.tagrupador_indicador ai 
                                                                          join ssig.tagrupador_indicador_resultado air on air.id_agrupador_indicador=ai.id_agrupador_indicador and air.id_gestion=v_parametros.id_gestion::integer and air.id_periodo=item_periodo.id_periodo::INTEGER   where ai.id_agrupador=item.id_agrupador::INTEGER and air.resultado is not NULL)::NUMERIC;
                                                                  
                                                          IF NOT EXISTS(SELECT * from ssig.tagrupador_resultado air 
                                                                       where air.id_agrupador=item.id_agrupador::INTEGER and 
                                                                       air.id_periodo=item_periodo.id_periodo::INTEGER) THEN
                                                                         
                                                                 
                                                                /*if(item.id_agrupador=121 and item_periodo.id_periodo=12 and v_resultado::INTEGER = 100::NUMERIC )then
                                                                   raise EXCEPTION 'verrr %',v_resultado;
                                                                   raise EXCEPTION 'errrr %',(SELECT ii.porcentaje FROM ssig.tinterpretacion_indicador ii where ii.id_gestion = v_parametros.id_gestion::INTEGER and (v_resultado::NUMERIC>=ii.porcentaje::NUMERIC) order by ii.porcentaje desc limit 1);
                                                                end if;*/
                                                                  
                                                                insert into  ssig.tagrupador_resultado 
                                                                (id_agrupador,
                                                                id_periodo,
                                                                resultado,
                                                                id_gestion,
                                                                ruta_icono)VALUES
                                                                (item.id_agrupador::INTEGER,
                                                                item_periodo.id_periodo::INTEGER,
                                                                v_resultado::NUMERIC,
                                                                v_parametros.id_gestion,
                                                                (SELECT ii.color FROM ssig.tinterpretacion_indicador ii where ii.id_gestion = v_parametros.id_gestion::INTEGER and (v_resultado::INTEGER>=ii.porcentaje::INTEGER) order by ii.porcentaje desc limit 1)
                                                                );
                                                          ELSE
                                                                UPDATE  ssig.tagrupador_resultado 
                                                                SET id_agrupador =item.id_agrupador::INTEGER,
                                                                id_periodo = item_periodo.id_periodo::INTEGER,
                                                                resultado = v_resultado::NUMERIC,
                                                                id_gestion = v_parametros.id_gestion::INTEGER,
                                                                ruta_icono = (SELECT ii.color FROM ssig.tinterpretacion_indicador ii where ii.id_gestion = v_parametros.id_gestion::INTEGER and (v_resultado::INTEGER>=ii.porcentaje::INTEGER) order by ii.porcentaje desc limit 1)
                                                                WHERE  id_agrupador=item.id_agrupador::INTEGER and id_periodo=item_periodo.id_periodo::INTEGER and id_gestion=v_parametros.id_gestion::INTEGER;
                                                          END IF;
                                                        
                                                          /*UPDATE ssig.tagrupador 
                                                          SET 
                                                              resultado=(SELECT sum((ai.resultado::NUMERIC * ai.peso::NUMERIC)/(SELECT sum(aii.peso) from ssig.tagrupador_indicador aii where aii.id_agrupador=item.id_agrupador and aii.resultado is not NULL)::NUMERIC) FROM ssig.tagrupador_indicador ai where ai.id_agrupador=item.id_agrupador::INTEGER and ai.resultado is not NULL)::NUMERIC
                                                          WHERE id_agrupador=item.id_agrupador::INTEGER;*/
                                                        
                                                      else
                                                        
                                                      IF NOT EXISTS(SELECT * from ssig.tagrupador_resultado air 
                                                                       where air.id_agrupador=item.id_agrupador::INTEGER and 
                                                                       air.id_periodo=item_periodo.id_periodo::INTEGER) THEN
                                                                          
                                                           insert into  ssig.tagrupador_resultado 
                                                            (id_agrupador,
                                                            id_periodo,
                                                            resultado,
                                                            id_gestion,
                                                            ruta_icono)VALUES
                                                            (item.id_agrupador::INTEGER,
                                                            item_periodo.id_periodo::INTEGER,
                                                            NULL,
                                                            v_parametros.id_gestion,
                                                            (SELECT ii.color FROM ssig.tinterpretacion_indicador ii where ii.id_gestion = v_parametros.id_gestion::INTEGER and (0::NUMERIC>=ii.porcentaje::NUMERIC) order by ii.porcentaje desc limit 1)
                                                            );
                                                         
                                                       END IF;  
                                                          
                                                          /*UPDATE ssig.tagrupador
                                                          SET 
                                                              resultado=0::NUMERIC
                                                          WHERE id_agrupador=item.id_agrupador::INTEGER;*/
                                                      end if;
                         
                                                        
                                                      --Calculamos nivel 1
                                                      v_resultado := (SELECT sum(ar.resultado::numeric * a.peso::numeric)/sum(a.peso::numeric)
                                                                      FROM ssig.tagrupador a 
                                                                      join ssig.tagrupador_resultado ar on ar.id_agrupador=a.id_agrupador and ar.id_gestion=v_parametros.id_gestion::INTEGER and ar.id_periodo=item_periodo.id_periodo::INTEGER  where a.id_agrupador_padre=item.id_agrupador_padre::INTEGER and ar.resultado is not null)::NUMERIC;          
                                                              
                                                      IF NOT EXISTS(SELECT * from ssig.tagrupador_resultado air 
                                                                       where air.id_agrupador=item.id_agrupador_padre::INTEGER and 
                                                                       air.id_periodo=item_periodo.id_periodo::INTEGER ) THEN
                                                              
                                                            /*IF(item.id_agrupador_padre::INTEGER = 157 and item_periodo.id_periodo::integer = 19 )then
                                                               --raise exception 'ids  %',v_parametros.id_gestion||' - '||item_periodo.id_periodo||' - '||item.id_agrupador_padre;
                                                               raise exception 'vvvv %', (SELECT 
                                                                                          sum(ar.resultado) 
                                                                                          FROM ssig.tagrupador a 
                                                                                          join ssig.tagrupador_resultado ar on ar.id_agrupador=a.id_agrupador and ar.id_gestion=2 and ar.id_periodo=19  
                                                                                          where a.id_agrupador_padre=157)::VARCHAR;
                                                               raise exception 'ver suma  %' ,(SELECT sum(ar.resultado) FROM ssig.tagrupador a join ssig.tagrupador_resultado ar on ar.id_agrupador=a.id_agrupador and ar.id_gestion=v_parametros.id_gestion::INTEGER and ar.id_periodo=item_periodo.id_periodo::INTEGER  where a.id_agrupador_padre=item.id_agrupador_padre::INTEGER)::VARCHAR;
                                                            end if;*/
                                                        
                                                              
                                                            insert into  ssig.tagrupador_resultado 
                                                            (id_agrupador,
                                                            id_periodo,
                                                            resultado,
                                                            id_gestion,
                                                            ruta_icono)VALUES
                                                            (item.id_agrupador_padre::INTEGER,
                                                            item_periodo.id_periodo::INTEGER,
                                                            v_resultado::NUMERIC,
                                                            v_parametros.id_gestion,
                                                            (SELECT ii.color FROM ssig.tinterpretacion_indicador ii where ii.id_gestion = v_parametros.id_gestion::INTEGER and (v_resultado::NUMERIC>=ii.porcentaje::NUMERIC) order by ii.porcentaje desc limit 1));
                                                        
                                                      ELSE
                                                            UPDATE  ssig.tagrupador_resultado 
                                                            SET id_agrupador =item.id_agrupador_padre::INTEGER,
                                                            id_periodo = item_periodo.id_periodo::INTEGER,
                                                            resultado = v_resultado::NUMERIC,
                                                            id_gestion = v_parametros.id_gestion::INTEGER,
                                                            ruta_icono = (SELECT ii.color FROM ssig.tinterpretacion_indicador ii where ii.id_gestion = v_parametros.id_gestion::INTEGER and (v_resultado::NUMERIC>=ii.porcentaje::NUMERIC) order by ii.porcentaje desc limit 1)
                                                            WHERE  id_agrupador=item.id_agrupador_padre::INTEGER and id_periodo=item_periodo.id_periodo::INTEGER and id_gestion=v_parametros.id_gestion::INTEGER;
                                                      END IF; 
                                                        
                                                      /*UPDATE ssig.tagrupador
                                                      SET 
                                                          resultado=(SELECT sum(a.resultado) FROM ssig.tagrupador a where a.id_agrupador_padre=item.id_agrupador_padre::INTEGER)::NUMERIC / (SELECT count(a.id_agrupador) FROM ssig.tagrupador a where a.id_agrupador_padre=item.id_agrupador_padre::INTEGER)::NUMERIC
                                                      WHERE id_agrupador=item.id_agrupador_padre::INTEGER;*/
                                                        
                                                        
                                                      --Calculamos nivel 0
                                                        
                                                        
                                                      v_resultado := (SELECT sum(ar.resultado::numeric * a.peso::numeric)/sum(a.peso::numeric) 
                                                                      FROM ssig.tagrupador a 
                                                                      join ssig.tagrupador_resultado ar on ar.id_agrupador=a.id_agrupador and ar.id_gestion=v_parametros.id_gestion::INTEGER and ar.id_periodo=item_periodo.id_periodo::INTEGER
                                                                      where a.id_agrupador_padre = (SELECT id_agrupador_padre FROM ssig.tagrupador where id_agrupador=item.id_agrupador_padre::INTEGER)::INTEGER and ar.resultado is not null)::NUMERIC;          
                                                             
                                                      IF NOT EXISTS(SELECT * from ssig.tagrupador_resultado air 
                                                                       where air.id_agrupador=(SELECT id_agrupador_padre FROM ssig.tagrupador where id_agrupador=item.id_agrupador_padre::INTEGER)::INTEGER and 
                                                                       air.id_periodo=item_periodo.id_periodo::INTEGER) THEN
                                                              
                                                            insert into  ssig.tagrupador_resultado 
                                                            (id_agrupador,
                                                            id_periodo,
                                                            resultado,
                                                            id_gestion,
                                                            ruta_icono)VALUES
                                                            ((SELECT id_agrupador_padre FROM ssig.tagrupador where id_agrupador=item.id_agrupador_padre::INTEGER)::INTEGER,
                                                            item_periodo.id_periodo::INTEGER,
                                                            v_resultado::NUMERIC,
                                                            v_parametros.id_gestion,
                                                            (SELECT ii.color FROM ssig.tinterpretacion_indicador ii where ii.id_gestion = v_parametros.id_gestion::INTEGER and (v_resultado::NUMERIC>=ii.porcentaje::NUMERIC) order by ii.porcentaje desc limit 1));
                                                        
                                                      ELSE
                                                            UPDATE  ssig.tagrupador_resultado 
                                                            SET id_agrupador = (SELECT id_agrupador_padre FROM ssig.tagrupador where id_agrupador=item.id_agrupador_padre::INTEGER)::INTEGER,
                                                            id_periodo = item_periodo.id_periodo::INTEGER,
                                                            resultado = v_resultado::NUMERIC,
                                                            id_gestion = v_parametros.id_gestion::INTEGER,
                                                            ruta_icono = (SELECT ii.color FROM ssig.tinterpretacion_indicador ii where ii.id_gestion = v_parametros.id_gestion::INTEGER and (v_resultado::NUMERIC>=ii.porcentaje::NUMERIC) order by ii.porcentaje desc limit 1)
                                                            where id_agrupador = (SELECT id_agrupador_padre FROM ssig.tagrupador where id_agrupador=item.id_agrupador_padre::INTEGER)::INTEGER and id_periodo = item_periodo.id_periodo::INTEGER and id_gestion=v_parametros.id_gestion::INTEGER;
                                                      END IF;  
                                                        
                                                      /* UPDATE ssig.tagrupador
                                                      SET 
                                                          resultado=(SELECT sum(a.resultado) FROM ssig.tagrupador a where a.id_agrupador_padre=(SELECT id_agrupador_padre FROM ssig.tagrupador where id_agrupador=item.id_agrupador_padre::INTEGER)::INTEGER)::NUMERIC / (SELECT count(a.id_agrupador) FROM ssig.tagrupador a where a.id_agrupador_padre=(SELECT id_agrupador_padre FROM ssig.tagrupador where id_agrupador=item.id_agrupador_padre::INTEGER)::INTEGER)::NUMERIC
                                                      WHERE id_agrupador=(SELECT id_agrupador_padre FROM ssig.tagrupador where id_agrupador=item.id_agrupador_padre::INTEGER)::INTEGER;*/
                                                                
                                              END IF;

                                      END LOOP;
                        END loop;               
                        --Fin calculo de resultados
                      END IF;
                        
                    ELSE
                      UPDATE ssig.tagrupador
                      SET
                        aprobado = v_parametros.aprobado :: BIT
                      WHERE id_gestion = v_parametros.id_gestion;
                    END IF;
                      
                    --Definicion de la respuesta
                    v_resp = pxp.f_agrega_clave(v_resp, 'mensaje', 'Modificado(s)');
                    --Devuelve la respuesta
                    RETURN v_resp;
            end if; --#1 fin
            
            

  
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