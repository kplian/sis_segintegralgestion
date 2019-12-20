--------------- SQL ---------------

CREATE OR REPLACE FUNCTION ssig.ft_linea_avance_sel (
  p_administrador integer,
  p_id_usuario integer,
  p_tabla varchar,
  p_transaccion varchar
)
RETURNS varchar AS
$body$
/**************************************************************************
 SISTEMA:   Seguimiento integral de gestión
 FUNCION:     ssig.ft_linea_avance_sel
 DESCRIPCION:   Funcion que devuelve conjuntos de registros de las consultas relacionadas con la tabla 'ssig.tlinea_avance'
 AUTOR:      (admin)
 FECHA:         19-02-2017 02:21:07
 COMENTARIOS: 
***************************************************************************
 HISTORIAL DE MODIFICACIONES:

 DESCRIPCION: 
 AUTOR:     
 FECHA:   
***************************************************************************/

/**************************************************************************
ISSUES    FORK     AUTOR    FECHA         DESCRIPCION
#5        ENDETR   JUAN     30/05/2019    Cambio tipo de variable peso a numeric
***************************************************************************/
DECLARE

  v_consulta        varchar;
  v_parametros      record;
  v_nombre_funcion    text;
  v_resp        varchar;
    
    v_gestion_inicio    date;
    v_gestion_fin       date;
    v_valor_frecuencia  text;
    v_gestion_contador  date;
    v_meses             text;
    
    v_consulta1       varchar;
    item                record;
  item1               record;
  item2               record;
  item3               record;
    v_total             numeric;
    v_consulta_temporal text;
    v_aprobado_real     boolean;
    v_hijos             varchar;
    v_cont_lavance     integer;
            
BEGIN

  v_nombre_funcion = 'ssig.ft_linea_avance_sel';
    v_parametros = pxp.f_get_record(p_tabla);

  /*********************************    
  #TRANSACCION:  'SSIG_LIAV_SEL'
  #DESCRIPCION: Consulta de datos
  #AUTOR:   JUAN  
  #FECHA:   19-02-2017 02:21:07
  ***********************************/

  if(p_transaccion='SSIG_LIAV_SEL')then
            
      begin

         v_consulta1 :='';
        
        --Sentencia de la consulta
      v_consulta:='WITH RECURSIVE arb_linea AS(
                                        SELECT l.*,
                                        l.nombre_linea::TEXT AS ancestros,
                                        l.orden_logico::varchar as orden,
                                        l.orden_logico::integer
                                        FROM ssig.tlinea l
                                        WHERE l.id_linea_padre IS NULL
                            UNION ALL
                                     SELECT l2.*,
                                     (al.ancestros || ''->'' || l2.nombre_linea)::TEXT AS ancestros,
                                     (al.orden::varchar||l2.orden_logico::varchar)::varchar as orden,
                                     l2.orden_logico::integer
                                     FROM ssig.tlinea l2
                                     JOIN arb_linea al ON al.id_linea=l2.id_linea_padre)
                        SELECT  
                             la.id_linea_avance,
                             arb.id_linea,
                             arb.nombre_linea,
                             arb.peso,
                             arb.peso_acumulado::varchar as peso_acumulado,
                             (100-arb.peso_acumulado)::varchar as peso_restante,
                             (select array_to_string( array_agg( lf.id_funcionario), '','' )
                               from ssig.tlinea_funcionario lf 
                               join ssig.tlinea l on l.id_linea=lf.id_linea
                               where lf.id_linea=arb.id_linea)::VARCHAR as id_funcionarios,
                             (select array_to_string( array_agg(PERSON.nombre_completo2), '','' )
                               from ssig.tlinea_funcionario lf 
                               join ssig.tlinea l on l.id_linea=lf.id_linea
                               join orga.tfuncionario FUNCIO on FUNCIO.id_funcionario=lf.id_funcionario
                               join SEGU.vpersona PERSON ON PERSON.id_persona=FUNCIO.id_persona
                               where lf.id_linea=arb.id_linea)::VARCHAR as funcionarios,
                               (case when la.mes is null then ''''::varchar else la.mes::varchar end)::VARCHAR as mes,
                               (case when la.avance_previsto is null then ''''::varchar else la.avance_previsto::varchar end)::varchar as avance_previsto,
                               (case when la.avance_real is null then ''''::varchar else la.avance_real::varchar end)::varchar  as avance_real,
                               (case when la.comentario is null then ''''::varchar else la.comentario::varchar end)::varchar as comentario,
                               (case when la.aprobado_real is null then FALSE::BOOLEAN else la.aprobado_real::BOOLEAN end)::BOOLEAN as aprobado_real,
                              
                              arb.estado_reg,
                               arb.id_usuario_ai,

                               arb.usuario_ai,
                               arb.fecha_reg,
                               arb.id_usuario_mod,
                               arb.fecha_mod,
                               usu1.cuenta as usr_reg,
                               usu2.cuenta as usr_mod,
                               arb.nivel::INTEGER,
                               lpa.nombre_linea as linea_padre,
                               --lpa.id_linea_padre,
                               arb.id_linea_padre,
                               arb.id_plan,
                               (arb.orden::varchar||lpa.orden_logico::varchar)::varchar as orden,
                               lpa.orden_logico::integer
                        FROM arb_linea arb left join ssig.tlinea_avance la on arb.id_linea=la.id_linea
                        inner join segu.tusuario usu1 on usu1.id_usuario = arb.id_usuario_reg
                        left join segu.tusuario usu2 on usu2.id_usuario = arb.id_usuario_mod 
                        left join ssig.tlinea lpa on lpa.id_linea=arb.id_linea_padre 
                        where  ';
      
      --Definicion de la respuesta
            --v_consulta := '';
            
      v_consulta:=v_consulta||v_parametros.filtro;
            --v_consulta:=v_consulta||' order by ' ||v_parametros.ordenacion|| ' ' || v_parametros.dir_ordenacion || '  limit ' || v_parametros.cantidad || ' offset ' || v_parametros.puntero;
      v_consulta:=v_consulta||' order by arb.ancestros asc ' || ' limit ' || v_parametros.cantidad || ' offset ' || v_parametros.puntero;
          

            return v_consulta;
            
    end;


  /*********************************    
  #TRANSACCION:  'SSIG_LINIAS_SEL'
  #DESCRIPCION: Consulta de datos
  #AUTOR:   JUAN  
  #FECHA:   19-02-2017 02:21:07
  ***********************************/

  ELSIF(p_transaccion='SSIG_LINIAS_SEL')then
            
      begin

         v_consulta1 :='';
         
         v_consulta1 := v_consulta1 || 'create temp table tt_linea_temporal(
                        id_linea_temporal serial,
                        id_linea integer,
                        nombre_linea varchar,
                        peso numeric, --#5
                        peso_acumulado varchar,
                        peso_restante  varchar,
                        id_funcionarios varchar,
                        funcionarios varchar,


                        estado_reg varchar,
                        id_usuario_ai integer,
                        usuario_ai varchar,
                        fecha_reg timestamp,
                        id_usuario_mod integer,
                        fecha_mod timestamp,
                        usr_reg varchar,
                        usr_mod varchar,
                        nivel integer,
                        linea_padre varchar,
                        id_linea_padre integer,
                        id_plan integer,
                        orden_logico integer) on commit drop'; 
                        
            execute(v_consulta1); 
            
              
        v_consulta :='';     
        v_consulta :='SELECT  
                             lpa.id_linea,
                             lpa.nombre_linea,
                             lpa.peso, --#5
                             lpa.peso_acumulado::varchar as peso_acumulado,
                             (100-lpa.peso_acumulado)::varchar as peso_restante,
                             (select array_to_string( array_agg( lf.id_funcionario), '','' )
                               from ssig.tlinea_funcionario lf 
                               join ssig.tlinea l on l.id_linea=lf.id_linea
                               where lf.id_linea=lpa.id_linea)::VARCHAR as id_funcionarios,
                             (select array_to_string( array_agg(PERSON.nombre_completo2), '','' )
                               from ssig.tlinea_funcionario lf 
                               join ssig.tlinea l on l.id_linea=lf.id_linea
                               join orga.tfuncionario FUNCIO on FUNCIO.id_funcionario=lf.id_funcionario
                               join SEGU.vpersona PERSON ON PERSON.id_persona=FUNCIO.id_persona
                               where lf.id_linea=lpa.id_linea)::VARCHAR as funcionarios,

                               lpa.estado_reg,
                               lpa.id_usuario_ai,

                               lpa.usuario_ai,
                               lpa.fecha_reg,
                               lpa.id_usuario_mod,
                               lpa.fecha_mod,
                               usu1.cuenta as usr_reg,
                               usu2.cuenta as usr_mod,
                               lpa.nivel::INTEGER,
                               --lpa.nombre_linea as linea_padre,
                               (select lpa.nombre_linea from ssig.tlinea li where li.id_linea=lpa.id_linea_padre)::varchar  as linea_padre,
                               lpa.id_linea_padre,
                               lpa.id_plan,
                               lpa.orden_logico::integer
                        FROM ssig.tlinea lpa
                        join segu.tusuario usu1 on usu1.id_usuario = lpa.id_usuario_reg
                        left join segu.tusuario usu2 on usu2.id_usuario = lpa.id_usuario_mod 
                        where  lpa.id_plan ='||v_parametros.id_plan||' and ';
                        
              --RAISE EXCEPTION 'Error provocado Juan %',v_consulta;    
           FOR item IN execute  v_consulta||' lpa.nivel is null order by lpa.orden_logico asc ' LOOP
                   --insertamos primer nivel
                   
                    INSERT INTO tt_linea_temporal (id_linea,
                                                   nombre_linea,
                                                   peso,
                                                   peso_acumulado,
                                                   peso_restante,
                                                   id_funcionarios,
                                                   funcionarios,


                                                   estado_reg,
                                                   id_usuario_ai,
                                                   usuario_ai,
                                                   fecha_reg,
                                                   
                                                   id_usuario_mod,
                                                   fecha_mod,
                                                   usr_reg,
                                                   usr_mod,
                                                   nivel,
                                                   linea_padre,
                                                   id_linea_padre,
                                                   id_plan,
                                                   orden_logico)
                                                   values
                                                   (item.id_linea,
                                                   item.nombre_linea,
                                                   item.peso,
                                                   item.peso_acumulado,
                                                   item.peso_restante,
                                                   item.id_funcionarios,
                                                   item.funcionarios,


                                                   item.estado_reg,
                                                   item.id_usuario_ai,
                                                   
                                                   item.usuario_ai,
                                                   item.fecha_reg,
                                                   item.id_usuario_mod,
                                                   item.fecha_mod,
                                                   item.usr_reg,
                                                   item.usr_mod,
                                                   item.nivel,
                                                   item.linea_padre,
                                                   item.id_linea_padre,
                                                   item.id_plan,
                                                   item.orden_logico);
                                                   
                                       
               FOR item1 IN execute  v_consulta||' lpa.nivel = 1 and  lpa.id_linea_padre = '||item.id_linea||' order by lpa.orden_logico asc ' LOOP
                             --insertamos segundo nivel
                          INSERT INTO tt_linea_temporal (id_linea,
                                                         nombre_linea,
                                                         peso,
                                                         peso_acumulado,
                                                         peso_restante,
                                                         id_funcionarios,
                                                         funcionarios,


                                                         estado_reg,
                                                         id_usuario_ai,
                                                         usuario_ai,
                                                         fecha_reg,
                                                         
                                                         id_usuario_mod,
                                                         fecha_mod,
                                                         usr_reg,
                                                         usr_mod,
                                                         nivel,
                                                         linea_padre,
                                                         id_linea_padre,
                                                         id_plan,
                                                         orden_logico)
                                                         values
                                                         (item1.id_linea,
                                                         item1.nombre_linea,
                                                         item1.peso,
                                                         item1.peso_acumulado,
                                                         item1.peso_restante,
                                                         item1.id_funcionarios,
                                                         item1.funcionarios,


                                                         item1.estado_reg,
                                                         item1.id_usuario_ai,
                                                         
                                                         item1.usuario_ai,
                                                         item1.fecha_reg,
                                                         item1.id_usuario_mod,
                                                         item1.fecha_mod,
                                                         item1.usr_reg,
                                                         item1.usr_mod,
                                                         item1.nivel,
                                                         item1.linea_padre,
                                                         item1.id_linea_padre,
                                                         item1.id_plan,
                                                         item1.orden_logico);
                                                   
                                                   --raise EXCEPTION 'error 1%',item1.nivel ;   
                   FOR item2 IN execute  v_consulta||' lpa.nivel = 2 and  lpa.id_linea_padre = '||item1.id_linea||'order by  lpa.orden_logico asc ' LOOP
                           --insertamos segundo nivel
                           /*if(item2.id_linea=624)then
                               raise EXCEPTION 'error 1%',item2.nombre_linea ; 
                           end if;*/
                              INSERT INTO tt_linea_temporal (id_linea,
                                                             nombre_linea,
                                                             peso,
                                                             peso_acumulado,
                                                             peso_restante,
                                                             id_funcionarios,
                                                             funcionarios,


                                                             estado_reg,
                                                             id_usuario_ai,
                                                             usuario_ai,
                                                             fecha_reg,
                                                             
                                                             id_usuario_mod,
                                                             fecha_mod,
                                                             usr_reg,
                                                             usr_mod,
                                                             nivel,
                                                             linea_padre,
                                                             id_linea_padre,
                                                             id_plan,
                                                             orden_logico)
                                                             values
                                                             (item2.id_linea,
                                                             item2.nombre_linea,
                                                             item2.peso,
                                                             item2.peso_acumulado,
                                                             item2.peso_restante,
                                                             item2.id_funcionarios,
                                                             item2.funcionarios,

                                                             item2.estado_reg,
                                                             item2.id_usuario_ai,
                                                             
                                                             item2.usuario_ai,
                                                             item2.fecha_reg,
                                                             item2.id_usuario_mod,
                                                             item2.fecha_mod,
                                                             item2.usr_reg,
                                                             item2.usr_mod,
                                                             item2.nivel,
                                                             item2.linea_padre,
                                                             item2.id_linea_padre,
                                                             item2.id_plan,
                                                             item2.orden_logico);
                                                             --raise EXCEPTION 'error 2 %',item2.nivel ;
                   END LOOP;
               END LOOP;
           END LOOP;
        
      
      --Definicion de la respuesta
            --v_consulta := '';
            
            --v_consulta := 'select id_linea from ssig.tlinea where ';
      --v_consulta:=v_consulta||v_parametros.filtro;
            --v_consulta:=v_consulta||' order by ' ||v_parametros.ordenacion|| ' ' || v_parametros.dir_ordenacion || '  limit ' || v_parametros.cantidad || ' offset ' || v_parametros.puntero;
      --v_consulta:=v_consulta||' order by arb.ancestros asc ' || ' limit ' || v_parametros.cantidad || ' offset ' || v_parametros.puntero;
          
      --Devuelve la respuesta
             
           -- raise EXCEPTION 'error 1%',' fin' ; 
           
           
            
            --RAISE EXCEPTION 'Error provocado Juan %',v_consulta;
           
            v_consulta:='select * from tt_linea_temporal arb where  '||v_parametros.filtro;
            return v_consulta;
            
    end;  
        
        
  /*********************************    
  #TRANSACCION:  'SSIG_LMESES_SEL'
  #DESCRIPCION: Consulta para el listado de meses según la gestión
  #AUTOR:   JUAN  
  #FECHA:   19-02-2017 02:21:07
  ***********************************/

  ELSIF(p_transaccion='SSIG_LMESES_SEL')then
            
      begin
        
      --RAISE EXCEPTION 'Error provocado Juan %',v_parametros.id_plan;
           
           v_consulta :='SELECT  la.id_linea_avance::INTEGER,la.mes::VARCHAR,la.aprobado_real,la.id_linea_avance::INTEGER as cod_linea_avance from ssig.tplan p 
       join ssig.tlinea l on p.id_plan=l.id_plan   
       join ssig.tlinea_avance la on la.id_linea=l.id_linea      
       where p.id_plan = '||v_parametros.id_plan||' and l.id_linea=(SELECT max(ll.id_linea)::INTEGER from ssig.tlinea ll where ll.id_plan='||v_parametros.id_plan||')and la.aprobado_real =TRUE 
       OR la.id_linea_avance = (
                                 SELECT min(la.id_linea_avance::INTEGER)
                                 from ssig.tplan p
                                 join ssig.tlinea l on p.id_plan = l.id_plan
                                 join ssig.tlinea_avance la on la.id_linea = l.id_linea
                                 where p.id_plan = '||v_parametros.id_plan||' and l.id_linea =(SELECT max(ll.id_linea)::INTEGER
                                                                      from ssig.tlinea ll where ll.id_plan = '||v_parametros.id_plan||')
                                                                      and la.aprobado_real=FALSE )::INTEGER';  
        
      --v_consulta:=v_consulta||v_parametros.filtro;
      v_consulta:=v_consulta||' order by la.id_linea_avance ' || ' limit ' || v_parametros.cantidad || ' offset ' || v_parametros.puntero;

      --Devuelve la respuesta
            -- RAISE EXCEPTION 'Error provocado meses Juan %',v_consulta;
      return v_consulta;
            
    end;
        
  /*********************************    
  #TRANSACCION:  'SSIG_LMESES_CONT'
  #DESCRIPCION: Conteo de registros
  #AUTOR:   JUAN  
  #FECHA:   19-02-2017 02:21:07
  ***********************************/

  elsif(p_transaccion='SSIG_LMESES_CONT')then

    begin
      --Sentencia de la consulta de conteo de registros

             v_consulta :='SELECT count(la.id_linea_avance) from ssig.tplan p 
                   join ssig.tlinea l on p.id_plan=l.id_plan   
                   join ssig.tlinea_avance la on la.id_linea=l.id_linea      
                   where p.id_plan = '||v_parametros.id_plan||' and l.id_linea=(SELECT max(ll.id_linea)::INTEGER from ssig.tlinea ll where ll.id_plan='||v_parametros.id_plan||')';  


      --Definicion de la respuesta        
      --v_consulta:=v_consulta||v_parametros.filtro;
            
            --RAISE EXCEPTION 'Error contador  Juan %',v_consulta;
      --Devuelve la respuesta
            
      return v_consulta;

    end; 
   
  /*********************************    
  #TRANSACCION:  'SSIG_LADINA_SEL'
  #DESCRIPCION: Devuelve datos a la lista dinamica generado en linea avance
  #AUTOR:   JUAN  
  #FECHA:   28-06-2017 02:21:07
  ***********************************/

  elsif(p_transaccion='SSIG_LADINA_SEL')then
         
        
      begin

         v_consulta1 :='';

         v_consulta1 := v_consulta1 || 'create temp table tt_linea_avance_temporal(
                        id_linea_avance serial,
                        id_linea integer,
                        nombre_linea varchar,
                        peso numeric, --#5
                        peso_acumulado integer,
                        peso_restante integer,
                        nivel integer,
                        linea_padre varchar,
                        cod_linea_padre integer,
                        id_plan integer,
                        id_usuario_reg integer'; 
                        
            
            v_cont_lavance:=0;    
            FOR item in  (select  la.mes       
            from ssig.tlinea l 
                        JOIN ssig.tplan p on l.id_plan = p.id_plan and p.id_plan=v_parametros.id_plan 
                        and l.id_linea=(select max(ll.id_linea) from ssig.tlinea ll join ssig.tplan pp on ll.id_plan=pp.id_plan where pp.id_plan=v_parametros.id_plan )
                        JOIN ssig.tlinea_avance la on la.id_linea = l.id_linea
                        order by la.id_linea_avance) LOOP
                        v_cont_lavance:= v_cont_lavance+1;
                        v_consulta1 :=v_consulta1||','||item.mes||' varchar';
                        v_consulta1 :=v_consulta1||', id_lavance'||v_cont_lavance||' integer';
                        
                        --RAISE EXCEPTION 'Error provocado Juan  %',v_consulta1;
           end loop;    
           v_consulta1 :=v_consulta1||', total varchar, aprobado_real boolean, cod_hijos varchar, cod_linea varchar) on commit drop';    
           execute(v_consulta1);
           
           
           --RAISE NOTICE 'consulta juan %', v_consulta1;

                                                        
            v_consulta_temporal :='';
            v_consulta_temporal := v_consulta_temporal || 'INSERT INTO tt_linea_avance_temporal (
                                                        id_linea,
                                                        nombre_linea,
                                                        peso,
                                                        peso_acumulado,
                                                        peso_restante,
                                                        nivel,
                                                        linea_padre,
                                                        cod_linea_padre,
                                                        id_plan,
                                                        id_usuario_reg';
            v_cont_lavance:=0;                                            
            FOR item in  (select  la.mes       
            from ssig.tlinea l 
                        JOIN ssig.tplan p on l.id_plan = p.id_plan and p.id_plan=v_parametros.id_plan 
                        and l.id_linea=(select max(ll.id_linea) from ssig.tlinea ll join ssig.tplan pp on ll.id_plan=pp.id_plan where pp.id_plan=v_parametros.id_plan )
                        JOIN ssig.tlinea_avance la on la.id_linea = l.id_linea
                        order by la.id_linea_avance) LOOP
                        v_cont_lavance:= v_cont_lavance+1;
                        v_consulta_temporal :=v_consulta_temporal||','||item.mes||',id_lavance'||v_cont_lavance;
            end loop;    
            v_consulta_temporal :=v_consulta_temporal||', total, aprobado_real, cod_hijos ,cod_linea) VALUES ';    
           
            --RAISE EXCEPTION 'Error provocado Juan 0 %',v_consulta_temporal;
             
        --Sentencia de la consulta
      FOR item in  (WITH RECURSIVE arb_linea AS(
                                        SELECT l.*,
                                        l.nombre_linea::TEXT AS ancestros
                                        FROM ssig.tlinea l
                                        WHERE l.id_linea_padre IS NULL
                                        
                            UNION ALL
                                     SELECT l2.*,
                                     (al.ancestros || '->' || l2.nombre_linea)::TEXT AS ancestros
                                     FROM ssig.tlinea l2
                                     JOIN arb_linea al ON al.id_linea=l2.id_linea_padre)
                        SELECT  
                             arb.id_linea,
                             arb.nombre_linea,
                             arb.peso,
                             arb.peso_acumulado::varchar as peso_acumulado,
                             (100-arb.peso_acumulado)::varchar as peso_restante,
                             (select array_to_string( array_agg( lf.id_funcionario), '','' )
                               from ssig.tlinea_funcionario lf 
                               join ssig.tlinea l on l.id_linea=lf.id_linea
                               where lf.id_linea=arb.id_linea)::VARCHAR as id_funcionarios,
                             (select array_to_string( array_agg(PERSON.nombre_completo2), '','' )
                               from ssig.tlinea_funcionario lf 
                               join ssig.tlinea l on l.id_linea=lf.id_linea
                               join orga.tfuncionario FUNCIO on FUNCIO.id_funcionario=lf.id_funcionario
                               join SEGU.vpersona PERSON ON PERSON.id_persona=FUNCIO.id_persona
                               where lf.id_linea=arb.id_linea)::VARCHAR as funcionarios,

                               arb.estado_reg,
                               arb.id_usuario_ai,

                               arb.usuario_ai,
                               arb.fecha_reg,
                               arb.id_usuario_mod,
                               arb.fecha_mod,
                               usu1.cuenta as usr_reg,
                               usu2.cuenta as usr_mod,
                               arb.nivel::INTEGER,
                               lpa.nombre_linea as linea_padre,
                               --lpa.id_linea_padre,
                               arb.id_linea_padre,
                               arb.id_plan,
                               arb.id_usuario_reg
                        FROM arb_linea arb 
                        inner join segu.tusuario usu1 on usu1.id_usuario = arb.id_usuario_reg
                        left  join segu.tusuario usu2 on usu2.id_usuario = arb.id_usuario_mod 
                        left  join ssig.tlinea lpa on lpa.id_linea=arb.id_linea_padre                    
                where arb.id_plan = v_parametros.id_plan
                        and arb.nivel is null
                        --order by arb.ancestros asc  
                        order by arb.orden_logico asc
                        ) LOOP
                        
                        
                       -- RAISE EXCEPTION 'Error provocado 2  %',REPLACE(item.nombre_linea,',', ' ');
                                v_consulta1 :='';
                                v_consulta1 :='(' || item.id_linea || ',''' || REPLACE(item.nombre_linea,',', ' ') || ''',' || item.peso || ',';
                                  IF(item.peso_acumulado IS NULL)THEN  
                                        v_consulta1 :=v_consulta1||0||',';
                                  ELSE
                                        v_consulta1 :=v_consulta1||item.peso_acumulado||',';   
                                  END IF;
                                  IF(item.peso_restante IS NULL)THEN  
                                        v_consulta1 :=v_consulta1||0||',';
                                  ELSE
                                        v_consulta1 :=v_consulta1||item.peso_restante||',';   
                                  END IF;
                                  IF(item.nivel IS NULL)THEN  
                                        v_consulta1 :=v_consulta1||0||',';
                                  ELSE
                                        v_consulta1 :=v_consulta1||item.nivel||',';   
                                  END IF;
                                  IF(item.linea_padre IS NULL)THEN  
                                        v_consulta1 :=v_consulta1||''''''||',';
                                  ELSE
                                        v_consulta1 :=v_consulta1||''''||item.linea_padre||''',';   
                                  END IF;
                                  IF(item.id_linea_padre IS NULL)THEN  
                                        v_consulta1 :=v_consulta1||0||',';
                                  ELSE
                                        v_consulta1 :=v_consulta1||item.id_linea_padre::INTEGER||',';   
                                  END IF;
                                v_consulta1 :=v_consulta1||item.id_plan||','||item.id_usuario_reg::INTEGER;  
                                
                                v_total :=0::numeric;
                                v_aprobado_real :='false'::BOOLEAN; 
                                
                                
                                FOR item1 in (select la.mes, la.id_linea_avance,la.avance_previsto,la.avance_real,la.aprobado_real from ssig.tlinea_avance la where la.id_linea = item.id_linea::INTEGER order by la.id_linea_avance) LOOP
                                     --RAISE EXCEPTION 'Error provocado %',item1.mes||' , '|| item1.id_linea_avance||' , '||item.id_linea;
                                     v_consulta1 := v_consulta1||','||item1.avance_previsto::numeric;
                                     v_aprobado_real := item1.aprobado_real::BOOLEAN;   
  
                                     v_total :=v_total::NUMERIC +item1.avance_previsto::NUMERIC;
                                     
                                     v_consulta1 :=v_consulta1||', '||item1.id_linea_avance::INTEGER;
                                      
                                end loop;  
                                v_hijos:='';
                                FOR item1 in (SELECT tl.id_linea from ssig.tlinea tl where tl.id_linea_padre=item.id_linea) LOOP
                                     --RAISE EXCEPTION 'Error provocado %',item1.mes||' , '|| item1.id_linea_avance||' , '||item.id_linea;
                                     v_hijos:=v_hijos||item1.id_linea||',';
                                end loop; 
                                
                                
                                
                                --no funciona raise exception con la consulta armada y la tabla temporal creada por las comillas simples probar directo con execute
                                --RAISE EXCEPTION 'Error provocado 2  %',v_consulta_temporal||v_consulta1;
                                v_consulta1 :=v_consulta1||','||v_total::NUMERIC||','||v_aprobado_real::BOOLEAN||','''||v_hijos::VARCHAR||''','||item.id_linea::VARCHAR||')';
                                --RAISE EXCEPTION 'Error provocado 2  %',REPLACE(v_consulta1,'''', '&');
                                execute(v_consulta_temporal||v_consulta1);
                                


                                ------------------------nivel 1 -------------------------------------
                                
                                FOR item1 in  (WITH RECURSIVE arb_linea AS(
                                                            SELECT l.*,
                                                            l.nombre_linea::TEXT AS ancestros
                                                            FROM ssig.tlinea l
                                                            WHERE l.id_linea_padre IS NULL
                                                            
                                                UNION ALL
                                                         SELECT l2.*,
                                                         (al.ancestros || '->' || l2.nombre_linea)::TEXT AS ancestros
                                                         FROM ssig.tlinea l2
                                                         JOIN arb_linea al ON al.id_linea=l2.id_linea_padre)
                                            SELECT  
                                                 arb.id_linea,
                                                 arb.nombre_linea,
                                                 arb.peso,
                                                 arb.peso_acumulado::varchar as peso_acumulado,
                                                 (100-arb.peso_acumulado)::varchar as peso_restante,
                                                 (select array_to_string( array_agg( lf.id_funcionario), ',' )
                                                   from ssig.tlinea_funcionario lf 
                                                   join ssig.tlinea l on l.id_linea=lf.id_linea
                                                   where lf.id_linea=arb.id_linea)::VARCHAR as id_funcionarios,
                                                 (select array_to_string( array_agg(PERSON.nombre_completo2), ',' )
                                                   from ssig.tlinea_funcionario lf 
                                                   join ssig.tlinea l on l.id_linea=lf.id_linea
                                                   join orga.tfuncionario FUNCIO on FUNCIO.id_funcionario=lf.id_funcionario
                                                   join SEGU.vpersona PERSON ON PERSON.id_persona=FUNCIO.id_persona
                                                   where lf.id_linea=arb.id_linea)::VARCHAR as funcionarios,

                                                   arb.estado_reg,
                                                   arb.id_usuario_ai,

                                                   arb.usuario_ai,
                                                   arb.fecha_reg,
                                                   arb.id_usuario_mod,
                                                   arb.fecha_mod,
                                                   usu1.cuenta as usr_reg,
                                                   usu2.cuenta as usr_mod,
                                                   arb.nivel::INTEGER,
                                                   lpa.nombre_linea as linea_padre,
                                                   --lpa.id_linea_padre,
                                                   arb.id_linea_padre,
                                                   arb.id_plan,
                                                   arb.id_usuario_reg
                                            FROM arb_linea arb 
                                            inner join segu.tusuario usu1 on usu1.id_usuario = arb.id_usuario_reg
                                            left  join segu.tusuario usu2 on usu2.id_usuario = arb.id_usuario_mod 
                                            left  join ssig.tlinea lpa on lpa.id_linea=arb.id_linea_padre                    
                                            where arb.id_plan = v_parametros.id_plan
                                            and arb.id_linea_padre=item.id_linea
                                            -- and arb.nivel = 1
                                            
                                            --order by arb.ancestros asc  
                                            order by arb.orden_logico asc
                                            ) LOOP
                                            
                                            
                                           -- RAISE EXCEPTION 'Error provocado 2  %',REPLACE(item.nombre_linea,',', ' ');
                                                    v_consulta1 :='';
                                                    v_consulta1 :='(' || item1.id_linea || ',''' || REPLACE(item1.nombre_linea,',', ' ') || ''',' || item1.peso || ',';
                                                      IF(item1.peso_acumulado IS NULL)THEN  
                                                            v_consulta1 :=v_consulta1||0||',';
                                                      ELSE
                                                            v_consulta1 :=v_consulta1||item1.peso_acumulado||',';   
                                                      END IF;
                                                      IF(item1.peso_restante IS NULL)THEN  
                                                            v_consulta1 :=v_consulta1||0||',';
                                                      ELSE
                                                            v_consulta1 :=v_consulta1||item1.peso_restante||',';   
                                                      END IF;
                                                      IF(item1.nivel IS NULL)THEN  
                                                            v_consulta1 :=v_consulta1||0||',';
                                                      ELSE
                                                            v_consulta1 :=v_consulta1||item1.nivel||',';   
                                                      END IF;
                                                      IF(item1.linea_padre IS NULL)THEN  
                                                            v_consulta1 :=v_consulta1||''''''||',';
                                                      ELSE
                                                            v_consulta1 :=v_consulta1||''''||item1.linea_padre||''',';   
                                                      END IF;
                                                      IF(item1.id_linea_padre IS NULL)THEN  
                                                            v_consulta1 :=v_consulta1||0||',';
                                                      ELSE
                                                            v_consulta1 :=v_consulta1||item1.id_linea_padre::INTEGER||',';   
                                                      END IF;
                                                    v_consulta1 :=v_consulta1||item1.id_plan||','||item1.id_usuario_reg::INTEGER;  
                                                    
                                                    v_total :=0::numeric;
                                                    v_aprobado_real :='false'::BOOLEAN; 
                                                    
                                                    
                                                    FOR item2 in (select la.mes, la.id_linea_avance,la.avance_previsto,la.avance_real,la.aprobado_real from ssig.tlinea_avance la where la.id_linea = item1.id_linea::INTEGER order by la.id_linea_avance) LOOP
                                                         --RAISE EXCEPTION 'Error provocado %',item1.mes||' , '|| item1.id_linea_avance||' , '||item.id_linea;
                                                         v_consulta1 := v_consulta1||','||item2.avance_previsto::numeric;
                                                         v_aprobado_real := item2.aprobado_real::BOOLEAN;   
                      
                                                         v_total :=v_total::NUMERIC +item2.avance_previsto::NUMERIC;
                                                         
                                                         v_consulta1 :=v_consulta1||', '||item2.id_linea_avance::INTEGER;
                                                          
                                                    end loop;  
                                                    v_hijos:='';
                                                    FOR item2 in (SELECT tl.id_linea from ssig.tlinea tl where tl.id_linea_padre=item1.id_linea) LOOP
                                                         --RAISE EXCEPTION 'Error provocado %',item1.mes||' , '|| item1.id_linea_avance||' , '||item.id_linea;
                                                         v_hijos:=v_hijos||item2.id_linea||',';
                                                    end loop; 
                                                    
                                                    
                                                    
                                                    --no funciona raise exception con la consulta armada y la tabla temporal creada por las comillas simples probar directo con execute
                                                    --RAISE EXCEPTION 'Error provocado 2  %',v_consulta_temporal||v_consulta1;
                                                    v_consulta1 :=v_consulta1||','||v_total::NUMERIC||','||v_aprobado_real::BOOLEAN||','''||v_hijos::VARCHAR||''','||item1.id_linea::VARCHAR||')';
                                                    --RAISE EXCEPTION 'Error provocado 2  %',REPLACE(v_consulta1,'''', '&');
                                                    execute(v_consulta_temporal||v_consulta1);
                                                    
                                                                        
                                                    ------------------------nivel 2 -------------------------------------
                                                    
                                                    FOR item2 in  (WITH RECURSIVE arb_linea AS(
                                                                                SELECT l.*,
                                                                                l.nombre_linea::TEXT AS ancestros
                                                                                FROM ssig.tlinea l
                                                                                WHERE l.id_linea_padre IS NULL
                                                                                
                                                                    UNION ALL
                                                                             SELECT l2.*,
                                                                             (al.ancestros || '->' || l2.nombre_linea)::TEXT AS ancestros
                                                                             FROM ssig.tlinea l2
                                                                             JOIN arb_linea al ON al.id_linea=l2.id_linea_padre)
                                                                SELECT  
                                                                     arb.id_linea,
                                                                     arb.nombre_linea,
                                                                     arb.peso,
                                                                     arb.peso_acumulado::varchar as peso_acumulado,
                                                                     (100-arb.peso_acumulado)::varchar as peso_restante,
                                                                     (select array_to_string( array_agg( lf.id_funcionario), ',' )
                                                                       from ssig.tlinea_funcionario lf 
                                                                       join ssig.tlinea l on l.id_linea=lf.id_linea
                                                                       where lf.id_linea=arb.id_linea)::VARCHAR as id_funcionarios,
                                                                     (select array_to_string( array_agg(PERSON.nombre_completo2), ',' )
                                                                       from ssig.tlinea_funcionario lf 
                                                                       join ssig.tlinea l on l.id_linea=lf.id_linea
                                                                       join orga.tfuncionario FUNCIO on FUNCIO.id_funcionario=lf.id_funcionario
                                                                       join SEGU.vpersona PERSON ON PERSON.id_persona=FUNCIO.id_persona
                                                                       where lf.id_linea=arb.id_linea)::VARCHAR as funcionarios,

                                                                       arb.estado_reg,
                                                                       arb.id_usuario_ai,

                                                                       arb.usuario_ai,
                                                                       arb.fecha_reg,
                                                                       arb.id_usuario_mod,
                                                                       arb.fecha_mod,
                                                                       usu1.cuenta as usr_reg,
                                                                       usu2.cuenta as usr_mod,
                                                                       arb.nivel::INTEGER,
                                                                       lpa.nombre_linea as linea_padre,
                                                                       --lpa.id_linea_padre,
                                                                       arb.id_linea_padre,
                                                                       arb.id_plan,
                                                                       arb.id_usuario_reg
                                                                FROM arb_linea arb 
                                                                inner join segu.tusuario usu1 on usu1.id_usuario = arb.id_usuario_reg
                                                                left  join segu.tusuario usu2 on usu2.id_usuario = arb.id_usuario_mod 
                                                                left  join ssig.tlinea lpa on lpa.id_linea=arb.id_linea_padre                    
                                                                where arb.id_plan = v_parametros.id_plan
                                                                and arb.id_linea_padre=item1.id_linea
                                                                --and arb.nivel = 2
                                                                
                                                                --order by arb.ancestros asc  
                                                                order by arb.orden_logico asc
                                                                ) LOOP
                                                                
                                                                
                                                               -- RAISE EXCEPTION 'Error provocado 2  %',REPLACE(item.nombre_linea,',', ' ');
                                                                        v_consulta1 :='';
                                                                        v_consulta1 :='(' || item2.id_linea || ',''' || REPLACE(item2.nombre_linea,',', ' ') || ''',' || item2.peso || ',';
                                                                          IF(item2.peso_acumulado IS NULL)THEN  
                                                                                v_consulta1 :=v_consulta1||0||',';
                                                                          ELSE
                                                                                v_consulta1 :=v_consulta1||item2.peso_acumulado||',';   
                                                                          END IF;
                                                                          IF(item2.peso_restante IS NULL)THEN  
                                                                                v_consulta1 :=v_consulta1||0||',';
                                                                          ELSE
                                                                                v_consulta1 :=v_consulta1||item2.peso_restante||',';   
                                                                          END IF;
                                                                          IF(item2.nivel IS NULL)THEN  
                                                                                v_consulta1 :=v_consulta1||0||',';
                                                                          ELSE
                                                                                v_consulta1 :=v_consulta1||item2.nivel||',';   
                                                                          END IF;
                                                                          IF(item2.linea_padre IS NULL)THEN  
                                                                                v_consulta1 :=v_consulta1||''''''||',';
                                                                          ELSE
                                                                                v_consulta1 :=v_consulta1||''''||item2.linea_padre||''',';   
                                                                          END IF;
                                                                          IF(item2.id_linea_padre IS NULL)THEN  
                                                                                v_consulta1 :=v_consulta1||0||',';
                                                                          ELSE
                                                                                v_consulta1 :=v_consulta1||item2.id_linea_padre::INTEGER||',';   
                                                                          END IF;
                                                                        v_consulta1 :=v_consulta1||item1.id_plan||','||item2.id_usuario_reg::INTEGER;  
                                                                        
                                                                        v_total :=0::numeric;
                                                                        v_aprobado_real :='false'::BOOLEAN; 
                                                                        
                                                                        
                                                                        FOR item3 in (select la.mes, la.id_linea_avance,la.avance_previsto,la.avance_real,la.aprobado_real from ssig.tlinea_avance la where la.id_linea = item2.id_linea::INTEGER order by la.id_linea_avance) LOOP
                                                                             --RAISE EXCEPTION 'Error provocado %',item1.mes||' , '|| item1.id_linea_avance||' , '||item.id_linea;
                                                                             v_consulta1 := v_consulta1||','||item3.avance_previsto::numeric;
                                                                             v_aprobado_real := item3.aprobado_real::BOOLEAN;   
                                          
                                                                             v_total :=v_total::NUMERIC +item3.avance_previsto::NUMERIC;
                                                                             
                                                                             v_consulta1 :=v_consulta1||', '||item3.id_linea_avance::INTEGER;
                                                                              
                                                                        end loop;  
                                                                        v_hijos:='';
                                                                        FOR item3 in (SELECT tl.id_linea from ssig.tlinea tl where tl.id_linea_padre=item2.id_linea) LOOP
                                                                             --RAISE EXCEPTION 'Error provocado %',item1.mes||' , '|| item1.id_linea_avance||' , '||item.id_linea;
                                                                             v_hijos:=v_hijos||item3.id_linea||',';
                                                                        end loop; 
                                                                        
                                                                        
                                                                        
                                                                        --no funciona raise exception con la consulta armada y la tabla temporal creada por las comillas simples probar directo con execute
                                                                        --RAISE EXCEPTION 'Error provocado 2  %',v_consulta_temporal||v_consulta1;
                                                                        v_consulta1 :=v_consulta1||','|| v_total::NUMERIC||','||v_aprobado_real::BOOLEAN||','''||v_hijos::VARCHAR||''','||item2.id_linea::VARCHAR||')';
                                                                        
                                                                        /*if(item2.id_linea_padre = 688 and item2.id_linea=785) then
                                                                          RAISE EXCEPTION 'Error provocado 2  %',REPLACE(v_consulta_temporal||v_consulta1,'''', '&');
                                                                        end if;*/
                                                                        
                                                                        execute(v_consulta_temporal||v_consulta1);
                                                                        
                                                                        
                                                                        
                                                    end loop;    
                                                    
                                                    ------------------------fin nivel 2 -------------------------------------
                                                    
                                                    
                                end loop;    
                                
                                ------------------------fin nivel 1 -------------------------------------


                                
                                
            end loop;    
                    
      --RAISE EXCEPTION 'Error provocado 3 %',v_consulta_temporal; 
      --Definicion de la respuesta
            --RAISE EXCEPTION 'Error provocado Juan 1 %',v_consulta;
      --v_consulta1:=v_consulta1||v_parametros.filtro;
            v_consulta1:='select * from tt_linea_avance_temporal arb  where arb.id_plan = '|| v_parametros.id_plan||' order by arb.id_linea_avance asc';
      v_consulta1:=v_consulta1||' ' || ' limit ' || v_parametros.cantidad || ' offset ' || v_parametros.puntero;
          
      --Devuelve la respuesta
            --RAISE EXCEPTION 'Error provocado Juan %',REPLACE(v_consulta1,'''', '&');
      return v_consulta1;
            
    end;
        
  /*********************************    
  #TRANSACCION:  'SSIG_LADINA_CONT'
  #DESCRIPCION: Conteo de registros
  #AUTOR:   JUAN  
  #FECHA:   19-02-2017 02:21:07
  ***********************************/

  elsif(p_transaccion='SSIG_LADINA_CONT')then

    begin
      --Sentencia de la consulta de conteo de registros
                        
      v_consulta:='SELECT  
                           count(arb.id_linea)
                        FROM ssig.tlinea arb left join ssig.tlinea_avance la on arb.id_linea=la.id_linea
                        --inner join segu.tusuario usu1 on usu1.id_usuario = arb.id_usuario_reg
                        --left join segu.tusuario usu2 on usu2.id_usuario = arb.id_usuario_mod 
                        left join ssig.tlinea lpa on lpa.id_linea=arb.id_linea_padre 
                        
              where ';
                        
                        
      
      --Definicion de la respuesta        
      v_consulta:=v_consulta||v_parametros.filtro;

      --Devuelve la respuesta
      return v_consulta;

    end;
  /*********************************    
  #TRANSACCION:  'SSIG_AREAL_SEL'
  #DESCRIPCION: Consulta de datos
  #AUTOR:   JUAN  
  #FECHA:   10-07-2017 02:21:07
  ***********************************/

  elsif(p_transaccion='SSIG_AREAL_SEL')then
            
      begin
        
        v_consulta1 := 'create temp table tt_linea_avance_temporal(
                         --id_linea_temporal serial,
                         id_linea serial,
                         nombre_linea varchar,
                         peso numeric,  --#5
                         nivel integer,
                         linea_padre varchar,
                         id_linea_padre integer,
                         id_plan integer,
                         id_linea_avance varchar,
                         avance_previsto numeric,
                         avance_real numeric,
                         acumulado_previsto numeric,
                         acumulado_real numeric,
                         desviacion numeric,
                         comentario varchar,
                         aprobado_real  boolean, 
                         estado_reg varchar,
                         id_usuario_ai integer,
                         usuario_ai varchar,
                         fecha_reg timestamp,
                         usr_reg varchar,
                         id_usuario_reg integer,
                         cod_hijos varchar,
                         cod_linea integer,
                         cod_linea_padre integer,
                         dato varchar,
                         orden_logico integer
                         ) on commit drop'; 
         
           execute(v_consulta1);
           
             /*v_consulta1 :='';
           
       v_consulta:='WITH RECURSIVE arb_linea AS(
                                        SELECT l.*,
                                        l.nombre_linea::TEXT AS ancestros
                                        FROM ssig.tlinea l
                                        WHERE l.id_linea_padre IS NULL
                            UNION ALL
                                     SELECT l2.*,
                                     (al.ancestros || ''->'' || l2.nombre_linea)::TEXT AS ancestros
                                     FROM ssig.tlinea l2
                                     JOIN arb_linea al ON al.id_linea=l2.id_linea_padre)
                        SELECT  
                             arb.id_linea,
                             arb.nombre_linea,
                             arb.peso,
                             arb.nivel::INTEGER,
                             lpa.nombre_linea::varchar as linea_padre,
                             arb.id_linea_padre,
                             arb.id_plan,
                             (select la.id_linea_avance from ssig.tlinea_avance la join ssig.tlinea l on la.id_linea=l.id_linea and l.id_linea=arb.id_linea and la.mes='''||v_parametros.mes||''')::INTEGER as id_linea_avance,
                             (select la.avance_previsto from ssig.tlinea_avance la join ssig.tlinea l on la.id_linea=l.id_linea and l.id_linea=arb.id_linea and la.mes='''||v_parametros.mes||''')::NUMERIC as avance_previsto,
                             (select la.avance_real from ssig.tlinea_avance la join ssig.tlinea l on la.id_linea=l.id_linea and l.id_linea=arb.id_linea and la.mes='''||v_parametros.mes||''')::NUMERIC as avance_real,
                             
                             
                             (SELECT sum(la.avance_previsto::NUMERIC) FROM ssig.tplan p
                                                                      join ssig.tlinea l on p.id_plan = l.id_plan
                                                                      join ssig.tlinea_avance la on la.id_linea = l.id_linea
                                                                      where p.id_plan = arb.id_plan 
                                                                             and la.id_linea=arb.id_linea
                                                                             and la.id_linea_avance <= (SELECT la.id_linea_avance::INTEGER
                                                                                                       from ssig.tplan p
                                                                                                       join ssig.tlinea l on p.id_plan = l.id_plan
                                                                                                       join ssig.tlinea_avance la on la.id_linea = l.id_linea
                                                                                                       where p.id_plan = arb.id_plan 
                                                                                                       and l.id_linea =arb.id_linea 
                                                                                                       and la.mes='''||v_parametros.mes||''' )::INTEGER)::NUMERIC as acumulado_previsto,
                                                                                                       
                             
                             (SELECT sum(la.avance_real::NUMERIC) FROM ssig.tplan p
                                                                      join ssig.tlinea l on p.id_plan = l.id_plan
                                                                      join ssig.tlinea_avance la on la.id_linea = l.id_linea
                                                                      where p.id_plan = arb.id_plan 
                                                                             and la.id_linea=arb.id_linea
                                                                             and la.id_linea_avance <= (SELECT min(la.id_linea_avance::INTEGER)
                                                                                                       from ssig.tplan p
                                                                                                       join ssig.tlinea l on p.id_plan = l.id_plan
                                                                                                       join ssig.tlinea_avance la on la.id_linea = l.id_linea
                                                                                                       where p.id_plan = arb.id_plan 
                                                                                                       and l.id_linea =arb.id_linea 
                                                                                                       and la.mes='''||v_parametros.mes||''')::INTEGER)::NUMERIC as acumulado_real,
                                                                                                       
                             
                             ((SELECT sum(la.avance_real::NUMERIC) FROM ssig.tplan p
                                                                      join ssig.tlinea l on p.id_plan = l.id_plan
                                                                      join ssig.tlinea_avance la on la.id_linea = l.id_linea
                                                                      where p.id_plan = arb.id_plan 
                                                                             and la.id_linea=arb.id_linea
                                                                             and la.id_linea_avance <= (SELECT min(la.id_linea_avance::INTEGER)
                                                                                                       from ssig.tplan p
                                                                                                       join ssig.tlinea l on p.id_plan = l.id_plan
                                                                                                       join ssig.tlinea_avance la on la.id_linea = l.id_linea
                                                                                                       where p.id_plan = arb.id_plan 
                                                                                                       and l.id_linea =arb.id_linea 
                                                                                                       and la.mes='''||v_parametros.mes||''')::INTEGER) - (SELECT sum(la.avance_previsto::NUMERIC) FROM ssig.tplan p
                                                                                                                                                                                                    join ssig.tlinea l on p.id_plan = l.id_plan
                                                                                                                                                                                                    join ssig.tlinea_avance la on la.id_linea = l.id_linea
                                                                                                                                                                                                    where p.id_plan = arb.id_plan 
                                                                                                                                                                                                           and la.id_linea=arb.id_linea
                                                                                                                                                                                                           and la.id_linea_avance <= (SELECT la.id_linea_avance::INTEGER
                                                                                                                                                                                                                                     from ssig.tplan p
                                                                                                                                                                                                                                     join ssig.tlinea l on p.id_plan = l.id_plan
                                                                                                                                                                                                                                     join ssig.tlinea_avance la on la.id_linea = l.id_linea
                                                                                                                                                                                                                                     where p.id_plan = arb.id_plan 
                                                                                                                                                                                                                                     and l.id_linea =arb.id_linea 
                                                                                                                                                                                                                                     and la.mes='''||v_parametros.mes||''' )::INTEGER)) ::NUMERIC as desviacion,
                                                                                                       
                             (select  la.comentario from ssig.tlinea_avance la join ssig.tlinea l on la.id_linea=l.id_linea and l.id_linea=arb.id_linea and la.mes='''||v_parametros.mes||''')::VARCHAR as comentario,
                             (select la.aprobado_real from ssig.tlinea_avance la join ssig.tlinea l on la.id_linea=l.id_linea and l.id_linea=arb.id_linea and la.mes='''||v_parametros.mes||''')::BOOLEAN as aprobado_real,
                             arb.estado_reg,
                             arb.id_usuario_ai,
                             arb.usuario_ai,
                             arb.fecha_reg,
                             usu1.cuenta as usr_reg,
                             arb.id_usuario_reg,
                             (select   array_to_string (ARRAY_AGG(ll.id_linea),'','')::VARCHAR from ssig.tlinea ll where ll.id_linea_padre=arb.id_linea)::VARCHAR as cod_hijos,
                             arb.id_linea::INTEGER as cod_linea,
                             arb.id_linea_padre::INTEGER as cod_linea_padre,
                             (select  la.dato from ssig.tlinea_avance la join ssig.tlinea l on la.id_linea=l.id_linea and l.id_linea=arb.id_linea and la.mes='''||v_parametros.mes||''')::VARCHAR as dato   
                             
                        FROM arb_linea arb 
                        inner join segu.tusuario usu1 on usu1.id_usuario = arb.id_usuario_reg
                        left  join segu.tusuario usu2 on usu2.id_usuario = arb.id_usuario_mod 
                        left  join ssig.tlinea lpa on lpa.id_linea=arb.id_linea_padre  
                        where  ';
      */
            

            ------------------------------------------------------------------------Aplicando orden logico----------------------------------------------------------------------------------------------     
            v_consulta1 ='';
            v_consulta1 ='
                        SELECT  
                             arb.id_linea::integer,
                             arb.nombre_linea,
                             arb.peso,
                             arb.nivel::INTEGER,
                             lpa.nombre_linea::varchar as linea_padre,
                             arb.id_linea_padre,
                             arb.id_plan,
                             (select la.id_linea_avance from ssig.tlinea_avance la join ssig.tlinea l on la.id_linea=l.id_linea and l.id_linea=arb.id_linea and la.mes='''||v_parametros.mes||''')::INTEGER as id_linea_avance,
                             (select la.avance_previsto from ssig.tlinea_avance la join ssig.tlinea l on la.id_linea=l.id_linea and l.id_linea=arb.id_linea and la.mes='''||v_parametros.mes||''')::NUMERIC as avance_previsto,
                             (select la.avance_real from ssig.tlinea_avance la join ssig.tlinea l on la.id_linea=l.id_linea and l.id_linea=arb.id_linea and la.mes='''||v_parametros.mes||''')::NUMERIC as avance_real,
                             
                             
                             (SELECT sum(la.avance_previsto::NUMERIC) FROM ssig.tplan p
                                                                      join ssig.tlinea l on p.id_plan = l.id_plan
                                                                      join ssig.tlinea_avance la on la.id_linea = l.id_linea
                                                                      where p.id_plan = arb.id_plan 
                                                                             and la.id_linea=arb.id_linea
                                                                             and la.id_linea_avance <= (SELECT la.id_linea_avance::INTEGER
                                                                                                       from ssig.tplan p
                                                                                                       join ssig.tlinea l on p.id_plan = l.id_plan
                                                                                                       join ssig.tlinea_avance la on la.id_linea = l.id_linea
                                                                                                       where p.id_plan = arb.id_plan 
                                                                                                       and l.id_linea =arb.id_linea 
                                                                                                       and la.mes='''||v_parametros.mes||''' )::INTEGER)::NUMERIC as acumulado_previsto,
                                                                                                       
                             
                             (SELECT sum(la.avance_real::NUMERIC) FROM ssig.tplan p
                                                                      join ssig.tlinea l on p.id_plan = l.id_plan
                                                                      join ssig.tlinea_avance la on la.id_linea = l.id_linea
                                                                      where p.id_plan = arb.id_plan 
                                                                             and la.id_linea=arb.id_linea
                                                                             and la.id_linea_avance <= (SELECT min(la.id_linea_avance::INTEGER)
                                                                                                       from ssig.tplan p
                                                                                                       join ssig.tlinea l on p.id_plan = l.id_plan
                                                                                                       join ssig.tlinea_avance la on la.id_linea = l.id_linea
                                                                                                       where p.id_plan = arb.id_plan 
                                                                                                       and l.id_linea =arb.id_linea 
                                                                                                       and la.mes='''||v_parametros.mes||''')::INTEGER)::NUMERIC as acumulado_real,
                                                                                                       
                             
                             ((SELECT sum(la.avance_real::NUMERIC) FROM ssig.tplan p
                                                                      join ssig.tlinea l on p.id_plan = l.id_plan
                                                                      join ssig.tlinea_avance la on la.id_linea = l.id_linea
                                                                      where p.id_plan = arb.id_plan 
                                                                             and la.id_linea=arb.id_linea
                                                                             and la.id_linea_avance <= (SELECT min(la.id_linea_avance::INTEGER)
                                                                                                       from ssig.tplan p
                                                                                                       join ssig.tlinea l on p.id_plan = l.id_plan
                                                                                                       join ssig.tlinea_avance la on la.id_linea = l.id_linea
                                                                                                       where p.id_plan = arb.id_plan 
                                                                                                       and l.id_linea =arb.id_linea 
                                                                                                       and la.mes='''||v_parametros.mes||''')::INTEGER) - (SELECT sum(la.avance_previsto::NUMERIC) FROM ssig.tplan p
                                                                                                                                                                                                    join ssig.tlinea l on p.id_plan = l.id_plan
                                                                                                                                                                                                    join ssig.tlinea_avance la on la.id_linea = l.id_linea
                                                                                                                                                                                                    where p.id_plan = arb.id_plan 
                                                                                                                                                                                                           and la.id_linea=arb.id_linea
                                                                                                                                                                                                           and la.id_linea_avance <= (SELECT la.id_linea_avance::INTEGER
                                                                                                                                                                                                                                     from ssig.tplan p
                                                                                                                                                                                                                                     join ssig.tlinea l on p.id_plan = l.id_plan
                                                                                                                                                                                                                                     join ssig.tlinea_avance la on la.id_linea = l.id_linea
                                                                                                                                                                                                                                     where p.id_plan = arb.id_plan 
                                                                                                                                                                                                                                     and l.id_linea =arb.id_linea 
                                                                                                                                                                                                                                     and la.mes='''||v_parametros.mes||''' )::INTEGER)) ::NUMERIC as desviacion,
                                                                                                       
                             (select  la.comentario from ssig.tlinea_avance la join ssig.tlinea l on la.id_linea=l.id_linea and l.id_linea=arb.id_linea and la.mes='''||v_parametros.mes||''')::VARCHAR as comentario,
                             (select la.aprobado_real from ssig.tlinea_avance la join ssig.tlinea l on la.id_linea=l.id_linea and l.id_linea=arb.id_linea and la.mes='''||v_parametros.mes||''')::BOOLEAN as aprobado_real,
                             arb.estado_reg,
                             arb.id_usuario_ai,
                             arb.usuario_ai,
                             arb.fecha_reg,
                             usu1.cuenta as usr_reg,
                             arb.id_usuario_reg,
                             (select   array_to_string (ARRAY_AGG(ll.id_linea),'','')::VARCHAR from ssig.tlinea ll where ll.id_linea_padre=arb.id_linea)::VARCHAR as cod_hijos,
                             arb.id_linea::INTEGER as cod_linea,
                             arb.id_linea_padre::INTEGER as cod_linea_padre,
                             (select  la.dato from ssig.tlinea_avance la join ssig.tlinea l on la.id_linea=l.id_linea and l.id_linea=arb.id_linea and la.mes='''||v_parametros.mes||''')::VARCHAR as dato   
                             ,arb.orden_logico::integer
                        FROM ssig.tlinea arb 
                        inner join segu.tusuario usu1 on usu1.id_usuario = arb.id_usuario_reg
                        left  join segu.tusuario usu2 on usu2.id_usuario = arb.id_usuario_mod 
                        left  join ssig.tlinea lpa on lpa.id_linea=arb.id_linea_padre  
                        where  ';
            v_consulta1:=v_consulta1||v_parametros.filtro;


            for item in execute(v_consulta1||' and (arb.nivel=0 or arb.nivel is null) order by arb.orden_logico asc') loop
                v_consulta_temporal :='INSERT INTO tt_linea_avance_temporal '||v_consulta1||' and arb.id_linea = '||item.id_linea;
                execute(v_consulta_temporal);
                for item1 in execute(v_consulta1||' and arb.id_linea_padre = '||item.id_linea||' order by arb.orden_logico asc') loop
                    v_consulta_temporal :='INSERT INTO tt_linea_avance_temporal '||v_consulta1||' and arb.id_linea = '||item1.id_linea;
                    execute(v_consulta_temporal);
                      for item2 in execute(v_consulta1||' and arb.id_linea_padre = '||item1.id_linea||' order by arb.orden_logico asc') loop
                          v_consulta_temporal :='INSERT INTO tt_linea_avance_temporal '||v_consulta1||' and arb.id_linea = '||item2.id_linea;
                          execute(v_consulta_temporal);
                      end loop;
                end loop;
            end loop;
            
            return 'select 
            id_linea::integer, 
            nombre_linea::varchar,
            peso::numeric,
            nivel::integer,
            linea_padre::varchar,
            id_linea_padre::integer,
            id_plan::integer,
            id_linea_avance::integer,
            avance_previsto::numeric,
            avance_real::numeric,
            acumulado_previsto::numeric,
            acumulado_real::numeric,
            desviacion::numeric,
            comentario::varchar,
            aprobado_real::boolean,
            estado_reg::varchar,
            id_usuario_ai::integer,
            usuario_ai::varchar,
            fecha_reg::timestamp,
            usr_reg::varchar,
            id_usuario_reg::integer,
            cod_hijos::varchar,
            cod_linea::integer,
            cod_linea_padre::integer,
            dato::varchar
            from tt_linea_avance_temporal ';
            
            ----------------------------------------------------------------------------------------------------------------------------------------------------------------------
    
        
          /*v_consulta:=v_consulta||v_parametros.filtro;
      v_consulta:=v_consulta||' order by arb.ancestros asc ' || ' limit ' || v_parametros.cantidad || ' offset ' || v_parametros.puntero;

            
      return v_consulta;*/
            
    end;    

  /*********************************    
  #TRANSACCION:  'SSIG_AREAL_CONT'
  #DESCRIPCION: Conteo de registros
  #AUTOR:   JUAN  
  #FECHA:   19-02-2017 02:21:07
  ***********************************/

  elsif(p_transaccion='SSIG_AREAL_CONT')then

    begin
             
      v_consulta:='SELECT  
                           count(arb.id_linea)
                        FROM ssig.tlinea arb left join ssig.tlinea_avance la on arb.id_linea=la.id_linea
                        inner join segu.tusuario usu1 on usu1.id_usuario = arb.id_usuario_reg
                        left join segu.tusuario usu2 on usu2.id_usuario = arb.id_usuario_mod 
                        left join ssig.tlinea lpa on lpa.id_linea=arb.id_linea_padre 
                        
              where ';

      v_consulta:=v_consulta||v_parametros.filtro;

      return v_consulta;

    end;
    
  /*********************************    
  #TRANSACCION:  'SSIG_LIAV_CONT'
  #DESCRIPCION: Conteo de registros
  #AUTOR:   JUAN  
  #FECHA:   19-02-2017 02:21:07
  ***********************************/

  elsif(p_transaccion='SSIG_LIAV_CONT')then

    begin
      --Sentencia de la consulta de conteo de registros
                        
      v_consulta:='WITH RECURSIVE arb_linea AS(
                                        SELECT l.*,
                                        l.nombre_linea::TEXT AS ancestros
                                        FROM ssig.tlinea l
                                        WHERE l.id_linea_padre IS NULL
                            UNION ALL
                                     SELECT l2.*,
                                     (al.ancestros || ''->'' || l2.nombre_linea)::TEXT AS ancestros
                                     FROM ssig.tlinea l2
                                     JOIN arb_linea al ON al.id_linea=l2.id_linea_padre)
                        SELECT  
                             COUNT(arb.id_linea)
                        FROM arb_linea arb left join ssig.tlinea_avance la on arb.id_linea=la.id_linea
                        inner join segu.tusuario usu1 on usu1.id_usuario = arb.id_usuario_reg
                        left join segu.tusuario usu2 on usu2.id_usuario = arb.id_usuario_mod 
                        left join ssig.tlinea lpa on lpa.id_linea=arb.id_linea_padre 
                        where  ';
                        
                        
      
      --Definicion de la respuesta        
      v_consulta:=v_consulta||v_parametros.filtro;

      --Devuelve la respuesta
      return v_consulta;

    end;
        
  /*********************************    
  #TRANSACCION:  'SSIG_LINIAS_CONT'
  #DESCRIPCION: Conteo de registros
  #AUTOR:   JUAN  
  #FECHA:   19-02-2017 02:21:07
  ***********************************/

  elsif(p_transaccion='SSIG_LINIAS_CONT')then

    begin
      --Sentencia de la consulta de conteo de registros
                        
      v_consulta:='WITH RECURSIVE arb_linea AS(
                                        SELECT l.*,
                                        l.nombre_linea::TEXT AS ancestros
                                        FROM ssig.tlinea l
                                        WHERE l.id_linea_padre IS NULL
                            UNION ALL
                                     SELECT l2.*,
                                     (al.ancestros || ''->'' || l2.nombre_linea)::TEXT AS ancestros
                                     FROM ssig.tlinea l2
                                     JOIN arb_linea al ON al.id_linea=l2.id_linea_padre)
                        SELECT  
                             COUNT(arb.id_linea)
                        FROM arb_linea arb left 
                        join ssig.tlinea_avance la on arb.id_linea=la.id_linea
                        --inner join segu.tusuario usu1 on usu1.id_usuario = arb.id_usuario_reg
                        left join segu.tusuario usu2 on usu2.id_usuario = arb.id_usuario_mod 
                        left join ssig.tlinea lpa on lpa.id_linea=arb.id_linea_padre 
                        where   ';
                        
                        
      
      --Definicion de la respuesta        
      v_consulta:=v_consulta||v_parametros.filtro;

      --Devuelve la respuesta
      return v_consulta;

    end;
          
  else
               
    raise exception 'Transaccion inexistente';
                   
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