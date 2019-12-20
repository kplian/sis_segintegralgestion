CREATE OR REPLACE FUNCTION ssig.ft_plan_sel (
  p_administrador integer,
  p_id_usuario integer,
  p_tabla varchar,
  p_transaccion varchar
)
RETURNS varchar AS
$body$
/**************************************************************************
 SISTEMA:		Seguimiento integral de gestión
 FUNCION: 		ssig.ft_plan_sel
 DESCRIPCION:   Funcion que devuelve conjuntos de registros de las consultas relacionadas con la tabla 'ssig.tplan'
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

  v_consulta       VARCHAR;
  v_parametros     RECORD;
  v_nombre_funcion TEXT;
  v_estado_gestion VARCHAR;
  v_resp           VARCHAR;
  
  item             RECORD;
  item1            RECORD;
  item2            RECORD;
  item3            RECORD;
  v_id_plan_hijo   INTEGER[];
  v_consulta1      VARCHAR;
  v_consulta2      VARCHAR;
  v_consulta3      VARCHAR;
  v_columnas       VARCHAR;
BEGIN

  v_nombre_funcion = 'ssig.ft_plan_sel';
  v_parametros = pxp.f_get_record(p_tabla);

  /*********************************
   #TRANSACCION:  'SSIG_SSIGPLAN_SEL'
   #DESCRIPCION:	Consulta de datos
   #AUTOR:		admin
   #FECHA:		11-04-2017 14:31:46
  ***********************************/

  IF (p_transaccion = 'SSIG_SSIGPLAN_SEL')
  THEN

    BEGIN
      --Sentencia de la consulta
      v_consulta:='select
						ssigplan.id_plan,
						ssigplan.id_plan_padre,
						ssigplan.id_gestion,
						ssigplan.nivel,
						ssigplan.nombre_plan,
						ssigplan.peso,
						ssigplan.aprobado,
						ssigplan.estado_reg,
						ssigplan.id_usuario_ai,
						ssigplan.fecha_reg,
						ssigplan.usuario_ai,
						ssigplan.id_usuario_reg,
						ssigplan.fecha_mod,
						ssigplan.id_usuario_mod,
						usu1.cuenta as usr_reg,
						usu2.cuenta as usr_mod	
						from ssig.tplan ssigplan
						inner join segu.tusuario usu1 on usu1.id_usuario = ssigplan.id_usuario_reg
						left join segu.tusuario usu2 on usu2.id_usuario = ssigplan.id_usuario_mod
				        where  ';

      --Definicion de la respuesta
      v_consulta:=v_consulta || v_parametros.filtro;
      v_consulta:=
      v_consulta || ' order by ' || v_parametros.ordenacion || ' ' || v_parametros.dir_ordenacion || ' limit ' ||
      v_parametros.cantidad || ' offset ' || v_parametros.puntero;

      --Devuelve la respuesta
      RETURN v_consulta;

    END;

    /*********************************
     #TRANSACCION:  'SSIG_SSIGPLAN_CONT'
     #DESCRIPCION:	Conteo de registros
     #AUTOR:		admin
     #FECHA:		11-04-2017 14:31:46
    ***********************************/

  ELSIF (p_transaccion = 'SSIG_SSIGPLAN_CONT')
    THEN

      BEGIN
        --Sentencia de la consulta de conteo de registros
        v_consulta:='select count(id_plan)
					    from ssig.tplan ssigplan
					    inner join segu.tusuario usu1 on usu1.id_usuario = ssigplan.id_usuario_reg
						left join segu.tusuario usu2 on usu2.id_usuario = ssigplan.id_usuario_mod
					    where ';

        --Definicion de la respuesta
        v_consulta:=v_consulta || v_parametros.filtro;

        --Devuelve la respuesta
        RETURN v_consulta;

      END;

      /*********************************
       #TRANSACCION:  'SSIG_PLAN_SEL_ARB'
       #DESCRIPCION:    Consulta de datos
       #AUTOR:            jose luis yacelly
       #FECHA:            11-05-2017
      ***********************************/

  ELSEIF (p_transaccion = 'SSIG_PLAN_SEL_ARB')
    THEN

      BEGIN
        --Sentencia de la consulta
        v_consulta:='select
          ssigplan.id_plan,
          ssigplan.id_plan_padre,
          ssigplan.id_gestion,
          ssigplan.nivel,
          ssigplan.nombre_plan,
          ssigplan.peso,
          ssigplan.aprobado,
          case
          when (ssigplan.id_plan_padre is  null )then
            ''raiz''::varchar
          when (ssigplan.id_plan_padre is  not null and ssigplan.nivel = 1) then
            ''hijo''::varchar
          when (ssigplan.id_plan_padre is  not null and ssigplan.nivel = 2) then
            ''hoja''::varchar
          END as tipo_nodo,
          
          ssigplan.peso_acumulado as porcentaje_acum,
          
          case when (ssigplan.peso_acumulado>0) then 100-coalesce(ssigplan.peso_acumulado,0) end as porcentaje_rest,
          (select array_to_string( array_agg( pf.id_funcionario), '','' )
                        from ssig.tplan_funcionario pf join ssig.tplan p on p.id_plan=pf.id_plan
                        where pf.id_plan=ssigplan.id_plan)::VARCHAR as id_funcionarios,

                        (select array_to_string( array_agg(PERSON.nombre_completo2), ''<br>'' )
                        from ssig.tplan_funcionario pf join ssig.tplan p on p.id_plan=pf.id_plan
                        join orga.tfuncionario FUNCIO on FUNCIO.id_funcionario=pf.id_funcionario
                        join SEGU.vpersona PERSON ON PERSON.id_persona=FUNCIO.id_persona
                        where pf.id_plan=ssigplan.id_plan)::VARCHAR as funcionarios,
                        ppa.nombre_plan  as nombre_plan_padre,
                        
                        
                        CASE WHEN ssigplan.nivel = 2 THEN 
                           (''<font color="#228b22">ACUM.:''|| (SELECT sum(ll.peso) from ssig.tplan pp join ssig.tlinea ll on pp.id_plan=ll.id_plan 
                          where pp.id_plan=ssigplan.id_plan and ll.id_linea_padre is null)||''%</font>'')::varchar
                        ELSE 
                        (''<font color="#228b22">ACUM.:''||ssigplan.peso_acumulado||''%</font>'')::varchar
                        END as porcentaje_acumulado,
                        
                        
                        case when (ssigplan.peso_acumulado>0) then (''<font color="red">REST.:''||100-coalesce(ssigplan.peso_acumulado,0)||''%</font>'')::varchar end as porcentaje_restante,
                        
                        CASE WHEN ssigplan.nivel = 2 THEN 
                            (SELECT sum(ll.peso) from ssig.tplan pp join ssig.tlinea ll on pp.id_plan=ll.id_plan 
                          where pp.id_plan=ssigplan.id_plan and ll.id_linea_padre is null)::varchar
                        ELSE 
                        ssigplan.peso_acumulado::varchar
                        END as porcentaje_acumulado_aux,
                        
                        case when (((select case when (ll.peso_acumulado !=100 or ll.peso_acumulado is NULL)then 1::INTEGER else 0::INTEGER end
                                             from ssig.tlinea ll 
                                             where (ll.id_plan=ssigplan.id_plan and ll.nivel!=2 and ll.peso_acumulado!=100) or (ll.id_plan=ssigplan.id_plan and ll.nivel is  null and ll.peso_acumulado!=100) LIMIT 1 ) is null) ) then 
                                                 case when (SELECT case when (SELECT sum(lavance.avance_previsto) from ssig.tlinea_avance lavance where lavance.id_linea=lll.id_linea)!=100 then 1::integer else 0::integer end 
                                                            from ssig.tlinea lll  
                                                            where lll.id_plan=ssigplan.id_plan and (SELECT sum(lavance.avance_previsto) from ssig.tlinea_avance lavance where lavance.id_linea=lll.id_linea)!=100  limit 1)=1 then
                                                    1::INTEGER
                                                 else
                                                    0::INTEGER
                                                 end
                                             else 
                                                 1::integer 
                          end as completado

        from ssig.tplan ssigplan left join ssig.tplan ppa on ppa.id_plan=ssigplan.id_plan_padre
        where';

        IF v_parametros.id_padre != '%'
        THEN
          v_consulta:=v_consulta || ' ssigplan.id_plan_padre = ' || v_parametros.id_padre;
        ELSE
          v_consulta:=v_consulta || ' ssigplan.id_plan_padre is null';

        END IF;

        IF (v_parametros.id_gestion :: INTEGER >= 0)
        THEN
          v_consulta:=v_consulta || ' and ssigplan.id_gestion = ' || v_parametros.id_gestion;
        END IF;

        v_consulta:=v_consulta || ' order by ssigplan.id_plan';

        RAISE NOTICE '%', v_consulta;
        --RAISE EXCEPTION 'YAC ERROR';

        -- RAISE EXCEPTION 'yac errooor';
        --Devuelve la respuesta
        RETURN v_consulta;

      END;
      
  /*********************************
   #TRANSACCION:  'SSIG_PLAN_GLOBAL_SEL'
   #DESCRIPCION:	Consulta de datos
   #AUTOR:		JUAN
   #FECHA:		24-09-2019 14:31:46
  ***********************************/

  ELSIF (p_transaccion = 'SSIG_PLAN_GLOBAL_SEL')
  THEN

    BEGIN
    
      
      v_consulta := 'create temp table tt_pglobal_temporal( 
                      id_pglobal_temporal       serial,
                      id_plan                   integer,
                      id_plan_padre             integer,
                      id_linea                  integer,
                      id_linea_padre            integer,
                      nivel                     integer,
                      nivel_1                   varchar,
                      nivel_2                   varchar,
                      nivel_3                   varchar,
                      nivel_4                   varchar,
                      responsable               varchar,
                      peso                      integer,
                      
                      avance_previsto_ene       numeric,
                      avance_real_ene           numeric,
                      desviacion_mes_ene        numeric,
                      acum_previsto_ene         numeric,
                      acum_real_ene             numeric,
                      desviacion_acumulada_ene  numeric,
                      
                      avance_previsto_feb       numeric,
                      avance_real_feb           numeric,
                      desviacion_mes_feb        numeric,
                      acum_previsto_feb         numeric,
                      acum_real_feb             numeric,
                      desviacion_acumulada_feb  numeric,
                      
                      avance_previsto_mar       numeric,
                      avance_real_mar           numeric,
                      desviacion_mes_mar        numeric,
                      acum_previsto_mar         numeric,
                      acum_real_mar             numeric,
                      desviacion_acumulada_mar  numeric,
                      
                      avance_previsto_abr       numeric,
                      avance_real_abr           numeric,
                      desviacion_mes_abr        numeric,
                      acum_previsto_abr         numeric,
                      acum_real_abr             numeric,
                      desviacion_acumulada_abr  numeric,
                      
                      avance_previsto_may       numeric,
                      avance_real_may           numeric,
                      desviacion_mes_may        numeric,
                      acum_previsto_may         numeric,
                      acum_real_may             numeric,
                      desviacion_acumulada_may  numeric,
                      
                      avance_previsto_jun       numeric,
                      avance_real_jun           numeric,
                      desviacion_mes_jun        numeric,
                      acum_previsto_jun         numeric,
                      acum_real_jun             numeric,
                      desviacion_acumulada_jun  numeric,  
                      
                      avance_previsto_jul       numeric,
                      avance_real_jul           numeric,
                      desviacion_mes_jul        numeric,
                      acum_previsto_jul         numeric,
                      acum_real_jul             numeric,
                      desviacion_acumulada_jul  numeric,    
                      
                      avance_previsto_ago       numeric,
                      avance_real_ago           numeric,
                      desviacion_mes_ago        numeric,
                      acum_previsto_ago         numeric,
                      acum_real_ago             numeric,
                      desviacion_acumulada_ago  numeric,    
                      
                      avance_previsto_sep       numeric,
                      avance_real_sep           numeric,
                      desviacion_mes_sep        numeric,
                      acum_previsto_sep         numeric,
                      acum_real_sep             numeric,
                      desviacion_acumulada_sep  numeric,     
                      
                      avance_previsto_oct       numeric,
                      avance_real_oct           numeric,
                      desviacion_mes_oct        numeric,
                      acum_previsto_oct         numeric,
                      acum_real_oct             numeric,
                      desviacion_acumulada_oct  numeric,  
                      
                      avance_previsto_nov       numeric,
                      avance_real_nov           numeric,
                      desviacion_mes_nov        numeric,
                      acum_previsto_nov         numeric,
                      acum_real_nov             numeric,
                      desviacion_acumulada_nov  numeric,  
                      
                      avance_previsto_dic       numeric,
                      avance_real_dic           numeric,
                      desviacion_mes_dic        numeric,
                      acum_previsto_dic         numeric,
                      acum_real_dic             numeric,
                      desviacion_acumulada_dic  numeric,
                      orden_logico              integer
                      ) on commit drop';              
      execute(v_consulta); 
      
      v_columnas  :=' id_plan                   ,
                      id_plan_padre             ,
                      id_linea                  ,
                      id_linea_padre            ,
                      nivel                     ,
                      nivel_1                   ,
                      nivel_2                   ,
                      nivel_3                   ,
                      nivel_4                   ,
                      responsable               ,
                      peso                      ,
                      
                      avance_previsto_ene       ,
                      avance_real_ene           ,
                      desviacion_mes_ene        ,
                      acum_previsto_ene         ,
                      acum_real_ene             ,
                      desviacion_acumulada_ene  ,
                      
                      avance_previsto_feb       ,
                      avance_real_feb           ,
                      desviacion_mes_feb        ,
                      acum_previsto_feb         ,
                      acum_real_feb             ,
                      desviacion_acumulada_feb  ,
                      
                      avance_previsto_mar       ,
                      avance_real_mar           ,
                      desviacion_mes_mar        ,
                      acum_previsto_mar         ,
                      acum_real_mar             ,
                      desviacion_acumulada_mar  ,
                      
                      avance_previsto_abr       ,
                      avance_real_abr           ,
                      desviacion_mes_abr        ,
                      acum_previsto_abr         ,
                      acum_real_abr             ,
                      desviacion_acumulada_abr  ,
                      
                      avance_previsto_may       ,
                      avance_real_may           ,
                      desviacion_mes_may        ,
                      acum_previsto_may         ,
                      acum_real_may             ,
                      desviacion_acumulada_may  ,
                      
                      avance_previsto_jun       ,
                      avance_real_jun           ,
                      desviacion_mes_jun        ,
                      acum_previsto_jun         ,
                      acum_real_jun             ,
                      desviacion_acumulada_jun  ,  
                      
                      avance_previsto_jul       ,
                      avance_real_jul           ,
                      desviacion_mes_jul        ,
                      acum_previsto_jul         ,
                      acum_real_jul             ,
                      desviacion_acumulada_jul  ,    
                      
                      avance_previsto_ago       ,
                      avance_real_ago           ,
                      desviacion_mes_ago        ,
                      acum_previsto_ago         ,
                      acum_real_ago             ,
                      desviacion_acumulada_ago  ,    
                      
                      avance_previsto_sep       ,
                      avance_real_sep           ,
                      desviacion_mes_sep        ,
                      acum_previsto_sep         ,
                      acum_real_sep             ,
                      desviacion_acumulada_sep  ,     
                      
                      avance_previsto_oct       ,
                      avance_real_oct           ,
                      desviacion_mes_oct        ,
                      acum_previsto_oct         ,
                      acum_real_oct             ,
                      desviacion_acumulada_oct  ,  
                      
                      avance_previsto_nov       ,
                      avance_real_nov           ,
                      desviacion_mes_nov        ,
                      acum_previsto_nov         ,
                      acum_real_nov             ,
                      desviacion_acumulada_nov  ,  
                      
                      avance_previsto_dic       ,
                      avance_real_dic           ,
                      desviacion_mes_dic        ,
                      acum_previsto_dic         ,
                      acum_real_dic             ,
                      desviacion_acumulada_dic  ,
                      orden_logico                ';
                 
      if v_parametros.nivel = 2 THEN
          SELECT 
          pxp.aggarray(p.id_plan)
          INTO v_id_plan_hijo
          FROM ssig.tplan p WHERE p.id_plan=v_parametros.id_plan;
      else
          SELECT 
          pxp.aggarray(p.id_plan)
          INTO v_id_plan_hijo
          FROM ssig.tplan p WHERE p.id_plan_padre=v_parametros.id_plan;
      end if;
      

      v_consulta1:='';
      --v_id_plan_hijo = string_to_array(v_id_plan_hijo,',')::INTEGER[];
      --v_id_plan_hijo = REPLACE(v_id_plan_hijo,'{','')::INTEGER[];
      --v_id_plan_hijo = REPLACE(v_id_plan_hijo,'{','')::INTEGER[];
      v_consulta1:='SELECT  
                    l.id_plan,
                    p.id_plan_padre,
                    l.id_linea,
                    l.id_linea_padre,
                    l.nivel,
                    ''''::VARCHAR AS nivel_1, 
                    (CASE WHEN l.nivel is NULL THEN l.nombre_linea else '''' END)::VARCHAR AS nivel_2, 
                    (CASE WHEN l.nivel = 1 THEN l.nombre_linea else '''' END)::VARCHAR AS nivel_3, 
                    (CASE WHEN l.nivel = 2 THEN l.nombre_linea else '''' END)::VARCHAR AS nivel_4, 
                    
                    --vf.desc_funcionario2::varchar as responsable,
                    (select array_to_string( array_agg(vf.desc_funcionario2), '' / '' )from  ssig.tlinea_funcionario lf join orga.vfuncionario vf on vf.id_funcionario=lf.id_funcionario
                     where lf.id_linea=l.id_linea)::varchar as responsable,
                    l.peso,
                    
                    ene.avance_previsto::NUMERIC as avance_previsto_ene,
                    ene.avance_real::NUMERIC as avance_real_ene,
                    (ene.avance_real-ene.avance_previsto)::NUMERIC desviacion_mes_ene,
                    ene.avance_previsto::NUMERIC as acum_previsto_ene,
                    ene.avance_real::NUMERIC as acum_real_ene,
                    (ene.avance_real-ene.avance_previsto)::NUMERIC as desviacion_acumulada_ene,
                     
                    feb.avance_previsto::NUMERIC as avance_previsto_feb,
                    feb.avance_real::NUMERIC as avance_real_feb,
                    (feb.avance_real-feb.avance_previsto)::NUMERIC desviacion_mes_feb,
                    (ene.avance_previsto+feb.avance_previsto)::NUMERIC as acum_previsto_feb,
                    (ene.avance_real+feb.avance_real)::NUMERIC as acum_real_feb,
                    ((ene.avance_real+feb.avance_real)-
                    (ene.avance_previsto+feb.avance_previsto))::NUMERIC desviacion_acumulada_ene,

                    mar.avance_previsto::NUMERIC as avance_previsto_mar,
                    mar.avance_real::NUMERIC as avance_real_mar,
                    (mar.avance_real-mar.avance_previsto)::NUMERIC desviacion_mes_mar,
                    (ene.avance_previsto+feb.avance_previsto+mar.avance_previsto)::NUMERIC as acum_previsto_mar,
                    (ene.avance_real+feb.avance_real+mar.avance_real)::NUMERIC as acum_real_mar,
                    ((ene.avance_real+feb.avance_real+mar.avance_real)-(ene.avance_previsto+feb.avance_previsto+mar.avance_previsto))::NUMERIC desviacion_acumulada_mar,
                    
                    abr.avance_previsto::NUMERIC as avance_previsto_abr,
                    abr.avance_real::NUMERIC as avance_real_abr,
                    (abr.avance_real-abr.avance_previsto)::NUMERIC desviacion_mes_abr,
                    (ene.avance_previsto+feb.avance_previsto+mar.avance_previsto+abr.avance_previsto)::NUMERIC as acum_previsto_abr,
                    (ene.avance_real+feb.avance_real+mar.avance_real+abr.avance_real)::NUMERIC as acum_real_abr,
                    ((ene.avance_real+feb.avance_real+mar.avance_real+abr.avance_real)-(ene.avance_previsto+feb.avance_previsto+mar.avance_previsto+abr.avance_previsto))::NUMERIC desviacion_acumulada_mar,
                    
                    may.avance_previsto::NUMERIC as avance_previsto_may,
                    may.avance_real::NUMERIC as avance_real_may,
                    (may.avance_real-may.avance_previsto)::NUMERIC desviacion_mes_may,
                    (ene.avance_previsto+feb.avance_previsto+mar.avance_previsto+abr.avance_previsto+may.avance_previsto)::NUMERIC as acum_previsto_may,
                    (ene.avance_real+feb.avance_real+mar.avance_real+abr.avance_real+may.avance_real)::NUMERIC as acum_real_may,
                    ((ene.avance_real+feb.avance_real+mar.avance_real+abr.avance_real+may.avance_real)- (ene.avance_previsto+feb.avance_previsto+mar.avance_previsto+abr.avance_previsto+may.avance_previsto))::NUMERIC desviacion_acumulada_may,
                    
                    jun.avance_previsto::NUMERIC as avance_previsto_jun,
                    jun.avance_real::NUMERIC as avance_real_jun,
                    (jun.avance_real-jun.avance_previsto)::NUMERIC desviacion_mes_jun,
                    (ene.avance_previsto+feb.avance_previsto+mar.avance_previsto+abr.avance_previsto+may.avance_previsto+jun.avance_previsto)::NUMERIC as acum_previsto_jun,
                    (ene.avance_real+feb.avance_real+mar.avance_real+abr.avance_real+may.avance_real+jun.avance_real)::NUMERIC as acum_real_jun,
                    ((ene.avance_real+feb.avance_real+mar.avance_real+abr.avance_real+may.avance_real+jun.avance_real)-(ene.avance_previsto+feb.avance_previsto+mar.avance_previsto+abr.avance_previsto+may.avance_previsto+jun.avance_previsto))::NUMERIC desviacion_acumulada_jun,
                    
                    jul.avance_previsto::NUMERIC as avance_previsto_jul,
                    jul.avance_real::NUMERIC as avance_real_jul,
                    (jul.avance_real-jul.avance_previsto)::NUMERIC desviacion_mes_jul,
                    (ene.avance_previsto+feb.avance_previsto+mar.avance_previsto+abr.avance_previsto+may.avance_previsto+jun.avance_previsto+jul.avance_previsto)::NUMERIC as acum_previsto_jul,
                    (ene.avance_real+feb.avance_real+mar.avance_real+abr.avance_real+may.avance_real+jun.avance_real+jul.avance_real)::NUMERIC as acum_real_jul,
                    ((ene.avance_real+feb.avance_real+mar.avance_real+abr.avance_real+may.avance_real+jun.avance_real+jul.avance_real)-(ene.avance_previsto+feb.avance_previsto+mar.avance_previsto+abr.avance_previsto+may.avance_previsto+jun.avance_previsto+jul.avance_previsto))::NUMERIC desviacion_acumulada_jul,
                    
                    ago.avance_previsto::NUMERIC as avance_previsto_ago,
                    ago.avance_real::NUMERIC as avance_real_ago,
                    (ago.avance_real-ago.avance_previsto)::NUMERIC desviacion_mes_ago,
                    (ene.avance_previsto+feb.avance_previsto+mar.avance_previsto+abr.avance_previsto+may.avance_previsto+jun.avance_previsto+jul.avance_previsto+ago.avance_previsto)::NUMERIC as acum_previsto_ago,
                    (ene.avance_real+feb.avance_real+mar.avance_real+abr.avance_real+may.avance_real+jun.avance_real+jul.avance_real+ago.avance_real)::NUMERIC as acum_real_ago,
                    ((ene.avance_real+feb.avance_real+mar.avance_real+abr.avance_real+may.avance_real+jun.avance_real+jul.avance_real+ago.avance_real)- (ene.avance_previsto+feb.avance_previsto+mar.avance_previsto+abr.avance_previsto+may.avance_previsto+jun.avance_previsto+jul.avance_previsto+ago.avance_previsto))::NUMERIC desviacion_acumulada_ago,
                    
                    sep.avance_previsto::NUMERIC as avance_previsto_sep,
                    sep.avance_real::NUMERIC as avance_real_sep,
                    (sep.avance_real-sep.avance_previsto)::NUMERIC desviacion_mes_sep,
                    (ene.avance_previsto+feb.avance_previsto+mar.avance_previsto+abr.avance_previsto+may.avance_previsto+jun.avance_previsto+jul.avance_previsto+ago.avance_previsto+sep.avance_previsto)::NUMERIC as acum_previsto_sep,
                    (ene.avance_real+feb.avance_real+mar.avance_real+abr.avance_real+may.avance_real+jun.avance_real+jul.avance_real+ago.avance_real+sep.avance_real)::NUMERIC as acum_real_sep,
                    ((ene.avance_real+feb.avance_real+mar.avance_real+abr.avance_real+may.avance_real+jun.avance_real+jul.avance_real+ago.avance_real+sep.avance_real)-(ene.avance_previsto+feb.avance_previsto+mar.avance_previsto+abr.avance_previsto+may.avance_previsto+jun.avance_previsto+jul.avance_previsto+ago.avance_previsto+sep.avance_previsto))::NUMERIC desviacion_acumulada_sep,

                    oct.avance_previsto::NUMERIC as avance_previsto_oct,
                    oct.avance_real::NUMERIC as avance_real_oct,
                    (oct.avance_real-oct.avance_previsto)::NUMERIC desviacion_mes_oct,
                    (ene.avance_previsto+feb.avance_previsto+mar.avance_previsto+abr.avance_previsto+may.avance_previsto+jun.avance_previsto+jul.avance_previsto+ago.avance_previsto+sep.avance_previsto+oct.avance_previsto)::NUMERIC as acum_previsto_oct,
                    (ene.avance_real+feb.avance_real+mar.avance_real+abr.avance_real+may.avance_real+jun.avance_real+jul.avance_real+ago.avance_real+sep.avance_real+oct.avance_real)::NUMERIC as acum_real_oct,
                    ((ene.avance_real+feb.avance_real+mar.avance_real+abr.avance_real+may.avance_real+jun.avance_real+jul.avance_real+ago.avance_real+sep.avance_real+oct.avance_real)-(ene.avance_previsto+feb.avance_previsto+mar.avance_previsto+abr.avance_previsto+may.avance_previsto+jun.avance_previsto+jul.avance_previsto+ago.avance_previsto+sep.avance_previsto+oct.avance_previsto))::NUMERIC desviacion_acumulada_oct,
                    
                    nov.avance_previsto::NUMERIC as avance_previsto_nov,
                    nov.avance_real::NUMERIC as avance_real_nov,
                    (nov.avance_real-nov.avance_previsto)::NUMERIC desviacion_mes_nov,
                    (ene.avance_previsto+feb.avance_previsto+mar.avance_previsto+abr.avance_previsto+may.avance_previsto+jun.avance_previsto+jul.avance_previsto+ago.avance_previsto+sep.avance_previsto+oct.avance_previsto+nov.avance_previsto)::NUMERIC as acum_previsto_nov,
                    (ene.avance_real+feb.avance_real+mar.avance_real+abr.avance_real+may.avance_real+jun.avance_real+jul.avance_real+ago.avance_real+sep.avance_real+oct.avance_real+nov.avance_real)::NUMERIC as acum_real_nov,
                    ((ene.avance_real+feb.avance_real+mar.avance_real+abr.avance_real+may.avance_real+jun.avance_real+jul.avance_real+ago.avance_real+sep.avance_real+oct.avance_real+nov.avance_real)-(ene.avance_previsto+feb.avance_previsto+mar.avance_previsto+abr.avance_previsto+may.avance_previsto+jun.avance_previsto+jul.avance_previsto+ago.avance_previsto+sep.avance_previsto+oct.avance_previsto+nov.avance_previsto))::NUMERIC desviacion_acumulada_nov,
                    
                    dic.avance_previsto::NUMERIC as avance_previsto_dic,
                    dic.avance_real::NUMERIC as avance_real_dic,
                    (dic.avance_real-dic.avance_previsto)::NUMERIC desviacion_mes_dic,
                    (ene.avance_previsto+feb.avance_previsto+mar.avance_previsto+abr.avance_previsto+may.avance_previsto+jun.avance_previsto+jul.avance_previsto+ago.avance_previsto+sep.avance_previsto+oct.avance_previsto+nov.avance_previsto+dic.avance_previsto)::NUMERIC as acum_previsto_dic,
                    (ene.avance_real+feb.avance_real+mar.avance_real+abr.avance_real+may.avance_real+jun.avance_real+jul.avance_real+ago.avance_real+sep.avance_real+oct.avance_real+nov.avance_real+dic.avance_real)::NUMERIC as acum_real_dic,
                    ((ene.avance_real+feb.avance_real+mar.avance_real+abr.avance_real+may.avance_real+jun.avance_real+jul.avance_real+ago.avance_real+sep.avance_real+oct.avance_real+nov.avance_real+dic.avance_real)-(ene.avance_previsto+feb.avance_previsto+mar.avance_previsto+abr.avance_previsto+may.avance_previsto+jun.avance_previsto+jul.avance_previsto+ago.avance_previsto+sep.avance_previsto+oct.avance_previsto+nov.avance_previsto+dic.avance_previsto))::NUMERIC desviacion_acumulada_dic,
                    l.orden_logico
                    
                    FROM ssig.tlinea l 
                    join ssig.tplan p on p.id_plan=l.id_plan
                    inner join segu.tusuario usu1 on usu1.id_usuario = l.id_usuario_reg
                    left  join segu.tusuario usu2 on usu2.id_usuario = l.id_usuario_mod 
                    --left join ssig.tplan_funcionario pf on pf.id_plan=p.id_plan
                    --left JOIN ssig.tlinea_funcionario lf on lf.id_linea=l.id_linea
                    --left join orga.vfuncionario vf on vf.id_funcionario=lf.id_funcionario
                    --join param.tgestion g on g.id_gestion=p.id_gestion
                    join ssig.tlinea_avance ene on ene.id_linea=l.id_linea and ene.mes like ''%ene%''
                    join ssig.tlinea_avance feb on feb.id_linea=l.id_linea and feb.mes like ''%feb%''
                    join ssig.tlinea_avance mar on mar.id_linea=l.id_linea and mar.mes like ''%mar%''
                    join ssig.tlinea_avance abr on abr.id_linea=l.id_linea and abr.mes like ''%abr%''
                    join ssig.tlinea_avance may on may.id_linea=l.id_linea and may.mes like ''%may%''
                    join ssig.tlinea_avance jun on jun.id_linea=l.id_linea and jun.mes like ''%jun%''
                    join ssig.tlinea_avance jul on jul.id_linea=l.id_linea and jul.mes like ''%jul%''
                    join ssig.tlinea_avance ago on ago.id_linea=l.id_linea and ago.mes like ''%ago%''
                    join ssig.tlinea_avance sep on sep.id_linea=l.id_linea and sep.mes like ''%sep%''
                    join ssig.tlinea_avance oct on oct.id_linea=l.id_linea and oct.mes like ''%oct%''
                    join ssig.tlinea_avance nov on nov.id_linea=l.id_linea and nov.mes like ''%nov%''
                    join ssig.tlinea_avance dic on dic.id_linea=l.id_linea and dic.mes like ''%dic%''
                    WHERE p.id_gestion = '||v_parametros.id_gestion;
                    
      v_consulta3 :='
                    SELECT 
                    p.id_plan,
                    p.id_plan_padre,
                    0::integer as id_linea,
                    0::integer as id_linea_padre,
                    p.nivel,
                    p.nombre_plan::VARCHAR AS nivel_1, 
                    ''''::VARCHAR AS nivel_2, 
                    ''''::VARCHAR AS nivel_3, 
                    ''''::VARCHAR AS nivel_4, 
                    
                    --vf.desc_funcionario2::varchar as responsable,
                    (select array_to_string( array_agg(vf.desc_funcionario2), '' / '' ) 
                    from ssig.tplan_funcionario pf 
                    join orga.vfuncionario vf on vf.id_funcionario=pf.id_funcionario
                    where pf.id_plan=p.id_plan)::varchar as responsable,
                    
                    p.peso,
                    sum((l.peso::NUMERIC/100)*(ene.avance_previsto::NUMERIC))::NUMERIC as  avance_previsto_ene,
                    sum((l.peso::NUMERIC/100)*(ene.avance_real::NUMERIC))::NUMERIC as  avance_real_ene,
                    (sum((l.peso::NUMERIC/100)*(ene.avance_real::NUMERIC))::NUMERIC-sum((l.peso::NUMERIC/100)*(ene.avance_previsto::NUMERIC))::NUMERIC)::NUMERIC as desviacion_mes_ene,
                    sum((l.peso::NUMERIC/100)*(ene.avance_previsto::NUMERIC))::NUMERIC as acum_previsto_ene,
                    sum((l.peso::NUMERIC/100)*(ene.avance_real::NUMERIC))::NUMERIC as acum_real_ene,
                    (sum((l.peso::NUMERIC/100)*(ene.avance_real::NUMERIC))::NUMERIC-sum((l.peso::NUMERIC/100)*(ene.avance_previsto::NUMERIC))::NUMERIC)::NUMERIC as desviacion_acumulada_ene,

                    sum((l.peso::NUMERIC/100)*(feb.avance_previsto::NUMERIC))::NUMERIC as avance_previsto_feb,
                    sum((l.peso::NUMERIC/100)*(feb.avance_real::NUMERIC))::NUMERIC as avance_real_feb,
                    (sum((l.peso::NUMERIC/100)*(feb.avance_real::NUMERIC))::NUMERIC-sum((l.peso::NUMERIC/100)*(feb.avance_previsto::NUMERIC))::NUMERIC)::NUMERIC as desviacion_mes_feb,
                    (sum((l.peso::NUMERIC/100)*(ene.avance_previsto::NUMERIC))::NUMERIC+sum((l.peso::NUMERIC/100)*(feb.avance_previsto::NUMERIC))::NUMERIC)::NUMERIC as acum_previsto_feb,
                    (sum((l.peso::NUMERIC/100)*(ene.avance_real::NUMERIC))::NUMERIC+sum((l.peso::NUMERIC/100)*(feb.avance_real::NUMERIC))::NUMERIC)::NUMERIC as acum_real_feb,
                    ((sum((l.peso::NUMERIC/100)*(ene.avance_real::NUMERIC))::NUMERIC+sum((l.peso::NUMERIC/100)*(feb.avance_real::NUMERIC))::NUMERIC)::NUMERIC - (sum((l.peso::NUMERIC/100)*(ene.avance_previsto::NUMERIC))::NUMERIC+sum((l.peso::NUMERIC/100)*(feb.avance_previsto::NUMERIC))::NUMERIC)::NUMERIC)::NUMERIC as desviacion_acumulada_feb,

                    sum((l.peso::NUMERIC/100)*(mar.avance_previsto::NUMERIC))::NUMERIC as avance_previsto_mar,
                    sum((l.peso::NUMERIC/100)*(mar.avance_real::NUMERIC))::NUMERIC as avance_real_mar,
                    (sum((l.peso::NUMERIC/100)*(mar.avance_real::NUMERIC))::NUMERIC-sum((l.peso::NUMERIC/100)*(mar.avance_previsto::NUMERIC))::NUMERIC)::NUMERIC as desviacion_mes_mar,
                    (sum((l.peso::NUMERIC/100)*(ene.avance_previsto::NUMERIC))::NUMERIC + sum((l.peso::NUMERIC/100)*(feb.avance_previsto::NUMERIC))::NUMERIC + sum((l.peso::NUMERIC/100)*(mar.avance_previsto::NUMERIC))::NUMERIC)::NUMERIC as acum_previsto_mar,
                    (sum((l.peso::NUMERIC/100)*(ene.avance_real::NUMERIC))::NUMERIC + sum((l.peso::NUMERIC/100)*(feb.avance_real::NUMERIC))::NUMERIC + sum((l.peso::NUMERIC/100)*(mar.avance_real::NUMERIC))::NUMERIC)::NUMERIC  as acum_real_mar,
                    ((sum((l.peso::NUMERIC/100)*(ene.avance_real::NUMERIC))::NUMERIC + sum((l.peso::NUMERIC/100)*(feb.avance_real::NUMERIC))::NUMERIC + sum((l.peso::NUMERIC/100)*(mar.avance_real::NUMERIC))::NUMERIC)::NUMERIC - (sum((l.peso::NUMERIC/100)*(ene.avance_previsto::NUMERIC))::NUMERIC + sum((l.peso::NUMERIC/100)*(feb.avance_previsto::NUMERIC))::NUMERIC + sum((l.peso::NUMERIC/100)*(mar.avance_previsto::NUMERIC))::NUMERIC)::NUMERIC)::NUMERIC as  desviacion_acumulada_mar,

                    sum((l.peso::NUMERIC/100)*(abr.avance_previsto::NUMERIC))::NUMERIC as avance_previsto_abr,
                    sum((l.peso::NUMERIC/100)*(abr.avance_real::NUMERIC))::NUMERIC as avance_real_abr,
                    (sum((l.peso::NUMERIC/100)*(abr.avance_real::NUMERIC))::NUMERIC-sum((l.peso::NUMERIC/100)*(abr.avance_previsto::NUMERIC))::NUMERIC)::NUMERIC as desviacion_mes_abr,
                    (sum((l.peso::NUMERIC/100)*(ene.avance_previsto::NUMERIC))::NUMERIC + sum((l.peso::NUMERIC/100)*(feb.avance_previsto::NUMERIC))::NUMERIC + sum((l.peso::NUMERIC/100)*(mar.avance_previsto::NUMERIC))::NUMERIC + sum((l.peso::NUMERIC/100)*(abr.avance_previsto::NUMERIC))::NUMERIC)::NUMERIC as acum_previsto_abr,
                    (sum((l.peso::NUMERIC/100)*(ene.avance_real::NUMERIC))::NUMERIC + sum((l.peso::NUMERIC/100)*(feb.avance_real::NUMERIC))::NUMERIC + sum((l.peso::NUMERIC/100)*(mar.avance_real::NUMERIC))::NUMERIC + sum((l.peso::NUMERIC/100)*(abr.avance_real::NUMERIC))::NUMERIC)::NUMERIC as acum_real_abr,
                    ((sum((l.peso::NUMERIC/100)*(ene.avance_real::NUMERIC))::NUMERIC + sum((l.peso::NUMERIC/100)*(feb.avance_real::NUMERIC))::NUMERIC + sum((l.peso::NUMERIC/100)*(mar.avance_real::NUMERIC))::NUMERIC + sum((l.peso::NUMERIC/100)*(abr.avance_real::NUMERIC))::NUMERIC)::NUMERIC - (sum((l.peso::NUMERIC/100)*(ene.avance_previsto::NUMERIC))::NUMERIC + sum((l.peso::NUMERIC/100)*(feb.avance_previsto::NUMERIC))::NUMERIC + sum((l.peso::NUMERIC/100)*(mar.avance_previsto::NUMERIC))::NUMERIC + sum((l.peso::NUMERIC/100)*(abr.avance_previsto::NUMERIC))::NUMERIC)::NUMERIC)::NUMERIC as desviacion_acumulada_abr,

                    sum((l.peso::NUMERIC/100)*(may.avance_previsto::NUMERIC))::NUMERIC as avance_previsto_may,
                    sum((l.peso::NUMERIC/100)*(may.avance_real::NUMERIC))::NUMERIC as avance_real_may,
                    (sum((l.peso::NUMERIC/100)*(may.avance_real::NUMERIC))::NUMERIC - sum((l.peso::NUMERIC/100)*(may.avance_previsto::NUMERIC))::NUMERIC)::NUMERIC as desviacion_mes_may,
                    (sum((l.peso::NUMERIC/100)*(ene.avance_previsto::NUMERIC))::NUMERIC + sum((l.peso::NUMERIC/100)*(feb.avance_previsto::NUMERIC))::NUMERIC + sum((l.peso::NUMERIC/100)*(mar.avance_previsto::NUMERIC))::NUMERIC + sum((l.peso::NUMERIC/100)*(abr.avance_previsto::NUMERIC))::NUMERIC + sum((l.peso::NUMERIC/100)*(may.avance_previsto::NUMERIC))::NUMERIC)::NUMERIC as acum_previsto_may,
                    (sum((l.peso::NUMERIC/100)*(ene.avance_real::NUMERIC))::NUMERIC+sum((l.peso::NUMERIC/100)*(feb.avance_real::NUMERIC))::NUMERIC+sum((l.peso::NUMERIC/100)*(mar.avance_real::NUMERIC))::NUMERIC+ sum((l.peso::NUMERIC/100)*(abr.avance_real::NUMERIC))::NUMERIC+sum((l.peso::NUMERIC/100)*(may.avance_real::NUMERIC))::NUMERIC)::NUMERIC as acum_real_may,
                    ((sum((l.peso::NUMERIC/100)*(ene.avance_real::NUMERIC))::NUMERIC+sum((l.peso::NUMERIC/100)*(feb.avance_real::NUMERIC))::NUMERIC+sum((l.peso::NUMERIC/100)*(mar.avance_real::NUMERIC))::NUMERIC+ sum((l.peso::NUMERIC/100)*(abr.avance_real::NUMERIC))::NUMERIC+sum((l.peso::NUMERIC/100)*(may.avance_real::NUMERIC))::NUMERIC)::NUMERIC - (sum((l.peso::NUMERIC/100)*(ene.avance_previsto::NUMERIC))::NUMERIC + sum((l.peso::NUMERIC/100)*(feb.avance_previsto::NUMERIC))::NUMERIC + sum((l.peso::NUMERIC/100)*(mar.avance_previsto::NUMERIC))::NUMERIC + sum((l.peso::NUMERIC/100)*(abr.avance_previsto::NUMERIC))::NUMERIC + sum((l.peso::NUMERIC/100)*(may.avance_previsto::NUMERIC))::NUMERIC)::NUMERIC)::NUMERIC as desviacion_acumulada_may,
                        
                    sum((l.peso::NUMERIC/100)*(jun.avance_previsto::NUMERIC))::NUMERIC as avance_previsto_jun,
                    sum((l.peso::NUMERIC/100)*(jun.avance_real::NUMERIC))::NUMERIC as avance_real_jun,
                    (sum((l.peso::NUMERIC/100)*(jun.avance_real::NUMERIC))::NUMERIC-sum((l.peso::NUMERIC/100)*(jun.avance_previsto::NUMERIC))::NUMERIC)::NUMERIC as desviacion_mes_jun,
                    (sum((l.peso::NUMERIC/100)*(ene.avance_previsto::NUMERIC))::NUMERIC+sum((l.peso::NUMERIC/100)*(feb.avance_previsto::NUMERIC))::NUMERIC+sum((l.peso::NUMERIC/100)*(mar.avance_previsto::NUMERIC))::NUMERIC+sum((l.peso::NUMERIC/100)*(abr.avance_previsto::NUMERIC))::NUMERIC+sum((l.peso::NUMERIC/100)*(may.avance_previsto::NUMERIC))::NUMERIC+sum((l.peso::NUMERIC/100)*(jun.avance_previsto::NUMERIC))::NUMERIC)::NUMERIC as acum_previsto_jun,
                    (sum((l.peso::NUMERIC/100)*(ene.avance_real::NUMERIC))::NUMERIC+sum((l.peso::NUMERIC/100)*(feb.avance_real::NUMERIC))::NUMERIC+sum((l.peso::NUMERIC/100)*(mar.avance_real::NUMERIC))::NUMERIC+sum((l.peso::NUMERIC/100)*(abr.avance_real::NUMERIC))::NUMERIC+sum((l.peso::NUMERIC/100)*(may.avance_real::NUMERIC))::NUMERIC+sum((l.peso::NUMERIC/100)*(jun.avance_real::NUMERIC))::NUMERIC)::NUMERIC as acum_real_jun,
                    ((sum((l.peso::NUMERIC/100)*(ene.avance_real::NUMERIC))::NUMERIC+sum((l.peso::NUMERIC/100)*(feb.avance_real::NUMERIC))::NUMERIC+sum((l.peso::NUMERIC/100)*(mar.avance_real::NUMERIC))::NUMERIC+sum((l.peso::NUMERIC/100)*(abr.avance_real::NUMERIC))::NUMERIC+sum((l.peso::NUMERIC/100)*(may.avance_real::NUMERIC))::NUMERIC+sum((l.peso::NUMERIC/100)*(jun.avance_real::NUMERIC))::NUMERIC)::NUMERIC-(sum((l.peso::NUMERIC/100)*(ene.avance_previsto::NUMERIC))::NUMERIC+sum((l.peso::NUMERIC/100)*(feb.avance_previsto::NUMERIC))::NUMERIC+sum((l.peso::NUMERIC/100)*(mar.avance_previsto::NUMERIC))::NUMERIC+sum((l.peso::NUMERIC/100)*(abr.avance_previsto::NUMERIC))::NUMERIC+sum((l.peso::NUMERIC/100)*(may.avance_previsto::NUMERIC))::NUMERIC+sum((l.peso::NUMERIC/100)*(jun.avance_previsto::NUMERIC))::NUMERIC)::NUMERIC)::NUMERIC desviacion_acumulada_jun, 

                    sum((l.peso::NUMERIC/100)*(jul.avance_previsto::NUMERIC))::NUMERIC as avance_previsto_jul,
                    sum((l.peso::NUMERIC/100)*(jul.avance_real::NUMERIC))::NUMERIC as avance_real_jul,
                    (sum((l.peso::NUMERIC/100)*(jul.avance_real::NUMERIC))::NUMERIC-sum((l.peso::NUMERIC/100)*(jul.avance_previsto::NUMERIC))::NUMERIC)::NUMERIC as desviacion_mes_jul,
                    (sum((l.peso::NUMERIC/100)*(ene.avance_previsto::NUMERIC))::NUMERIC+sum((l.peso::NUMERIC/100)*(feb.avance_previsto::NUMERIC))::NUMERIC+sum((l.peso::NUMERIC/100)*(mar.avance_previsto::NUMERIC))::NUMERIC+sum((l.peso::NUMERIC/100)*(abr.avance_previsto::NUMERIC))::NUMERIC+sum((l.peso::NUMERIC/100)*(may.avance_previsto::NUMERIC))::NUMERIC+sum((l.peso::NUMERIC/100)*(jun.avance_previsto::NUMERIC))::NUMERIC+sum((l.peso::NUMERIC/100)*(jul.avance_previsto::NUMERIC))::NUMERIC)::NUMERIC as acum_previsto_jul,
                    (sum((l.peso::NUMERIC/100)*(ene.avance_real::NUMERIC))::NUMERIC+sum((l.peso::NUMERIC/100)*(feb.avance_real::NUMERIC))::NUMERIC+sum((l.peso::NUMERIC/100)*(mar.avance_real::NUMERIC))::NUMERIC+sum((l.peso::NUMERIC/100)*(abr.avance_real::NUMERIC))::NUMERIC+sum((l.peso::NUMERIC/100)*(may.avance_real::NUMERIC))::NUMERIC+sum((l.peso::NUMERIC/100)*(jun.avance_real::NUMERIC))::NUMERIC+sum((l.peso::NUMERIC/100)*(jul.avance_real::NUMERIC))::NUMERIC)::NUMERIC as acum_real_jul,
                    ((sum((l.peso::NUMERIC/100)*(ene.avance_real::NUMERIC))::NUMERIC+sum((l.peso::NUMERIC/100)*(feb.avance_real::NUMERIC))::NUMERIC+sum((l.peso::NUMERIC/100)*(mar.avance_real::NUMERIC))::NUMERIC+sum((l.peso::NUMERIC/100)*(abr.avance_real::NUMERIC))::NUMERIC+sum((l.peso::NUMERIC/100)*(may.avance_real::NUMERIC))::NUMERIC+sum((l.peso::NUMERIC/100)*(jun.avance_real::NUMERIC))::NUMERIC+sum((l.peso::NUMERIC/100)*(jul.avance_real::NUMERIC))::NUMERIC)::NUMERIC-(sum((l.peso::NUMERIC/100)*(ene.avance_previsto::NUMERIC))::NUMERIC+sum((l.peso::NUMERIC/100)*(feb.avance_previsto::NUMERIC))::NUMERIC+sum((l.peso::NUMERIC/100)*(mar.avance_previsto::NUMERIC))::NUMERIC+sum((l.peso::NUMERIC/100)*(abr.avance_previsto::NUMERIC))::NUMERIC+sum((l.peso::NUMERIC/100)*(may.avance_previsto::NUMERIC))::NUMERIC+sum((l.peso::NUMERIC/100)*(jun.avance_previsto::NUMERIC))::NUMERIC+sum((l.peso::NUMERIC/100)*(jul.avance_previsto::NUMERIC))::NUMERIC)::NUMERIC)::NUMERIC  as desviacion_acumulada_jul,

                    sum((l.peso::NUMERIC/100)*(ago.avance_previsto::NUMERIC))::NUMERIC as avance_previsto_ago,
                    sum((l.peso::NUMERIC/100)*(ago.avance_real::NUMERIC))::NUMERIC as avance_real_ago,
                    (sum((l.peso::NUMERIC/100)*(ago.avance_real::NUMERIC))::NUMERIC-sum((l.peso::NUMERIC/100)*(ago.avance_previsto::NUMERIC))::NUMERIC)::NUMERIC as desviacion_mes_ago,
                    (sum((l.peso::NUMERIC/100)*(ene.avance_previsto::NUMERIC))::NUMERIC+sum((l.peso::NUMERIC/100)*(feb.avance_previsto::NUMERIC))::NUMERIC+sum((l.peso::NUMERIC/100)*(mar.avance_previsto::NUMERIC))::NUMERIC+sum((l.peso::NUMERIC/100)*(abr.avance_previsto::NUMERIC))::NUMERIC+sum((l.peso::NUMERIC/100)*(may.avance_previsto::NUMERIC))::NUMERIC+sum((l.peso::NUMERIC/100)*(jun.avance_previsto::NUMERIC))::NUMERIC+sum((l.peso::NUMERIC/100)*(jul.avance_previsto::NUMERIC))::NUMERIC+sum((l.peso::NUMERIC/100)*(ago.avance_previsto::NUMERIC))::NUMERIC)::NUMERIC as acum_previsto_ago,
                    (sum((l.peso::NUMERIC/100)*(ene.avance_real::NUMERIC))::NUMERIC+sum((l.peso::NUMERIC/100)*(feb.avance_real::NUMERIC))::NUMERIC+sum((l.peso::NUMERIC/100)*(mar.avance_real::NUMERIC))::NUMERIC+sum((l.peso::NUMERIC/100)*(abr.avance_real::NUMERIC))::NUMERIC+sum((l.peso::NUMERIC/100)*(may.avance_real::NUMERIC))::NUMERIC+sum((l.peso::NUMERIC/100)*(jun.avance_real::NUMERIC))::NUMERIC+sum((l.peso::NUMERIC/100)*(jul.avance_real::NUMERIC))::NUMERIC+sum((l.peso::NUMERIC/100)*(ago.avance_real::NUMERIC))::NUMERIC)::NUMERIC as acum_real_ago,
                    ((sum((l.peso::NUMERIC/100)*(ene.avance_real::NUMERIC))::NUMERIC+sum((l.peso::NUMERIC/100)*(feb.avance_real::NUMERIC))::NUMERIC+sum((l.peso::NUMERIC/100)*(mar.avance_real::NUMERIC))::NUMERIC+sum((l.peso::NUMERIC/100)*(abr.avance_real::NUMERIC))::NUMERIC+sum((l.peso::NUMERIC/100)*(may.avance_real::NUMERIC))::NUMERIC+sum((l.peso::NUMERIC/100)*(jun.avance_real::NUMERIC))::NUMERIC+sum((l.peso::NUMERIC/100)*(jul.avance_real::NUMERIC))::NUMERIC+sum((l.peso::NUMERIC/100)*(ago.avance_real::NUMERIC))::NUMERIC)::NUMERIC-(sum((l.peso::NUMERIC/100)*(ene.avance_previsto::NUMERIC))::NUMERIC+sum((l.peso::NUMERIC/100)*(feb.avance_previsto::NUMERIC))::NUMERIC+sum((l.peso::NUMERIC/100)*(mar.avance_previsto::NUMERIC))::NUMERIC+sum((l.peso::NUMERIC/100)*(abr.avance_previsto::NUMERIC))::NUMERIC+sum((l.peso::NUMERIC/100)*(may.avance_previsto::NUMERIC))::NUMERIC+sum((l.peso::NUMERIC/100)*(jun.avance_previsto::NUMERIC))::NUMERIC+sum((l.peso::NUMERIC/100)*(jul.avance_previsto::NUMERIC))::NUMERIC+sum((l.peso::NUMERIC/100)*(ago.avance_previsto::NUMERIC))::NUMERIC)::NUMERIC)::NUMERIC as  desviacion_acumulada_ago,

                    sum((l.peso::NUMERIC/100)*(sep.avance_previsto::NUMERIC))::NUMERIC as avance_previsto_sep,
                    sum((l.peso::NUMERIC/100)*(sep.avance_real::NUMERIC))::NUMERIC  as avance_real_sep,
                    (sum((l.peso::NUMERIC/100)*(sep.avance_real::NUMERIC))::NUMERIC-sum((l.peso::NUMERIC/100)*(sep.avance_previsto::NUMERIC))::NUMERIC)::NUMERIC as desviacion_mes_sep,
                    (sum((l.peso::NUMERIC/100)*(ene.avance_previsto::NUMERIC))::NUMERIC+sum((l.peso::NUMERIC/100)*(feb.avance_previsto::NUMERIC))::NUMERIC+sum((l.peso::NUMERIC/100)*(mar.avance_previsto::NUMERIC))::NUMERIC+sum((l.peso::NUMERIC/100)*(abr.avance_previsto::NUMERIC))::NUMERIC+sum((l.peso::NUMERIC/100)*(may.avance_previsto::NUMERIC))::NUMERIC+sum((l.peso::NUMERIC/100)*(jun.avance_previsto::NUMERIC))::NUMERIC+sum((l.peso::NUMERIC/100)*(jul.avance_previsto::NUMERIC))::NUMERIC+sum((l.peso::NUMERIC/100)*(ago.avance_previsto::NUMERIC))::NUMERIC+sum((l.peso::NUMERIC/100)*(sep.avance_previsto::NUMERIC))::NUMERIC)::NUMERIC as acum_previsto_sep,
                    (sum((l.peso::NUMERIC/100)*(ene.avance_real::NUMERIC))::NUMERIC+sum((l.peso::NUMERIC/100)*(feb.avance_real::NUMERIC))::NUMERIC+sum((l.peso::NUMERIC/100)*(mar.avance_real::NUMERIC))::NUMERIC+sum((l.peso::NUMERIC/100)*(abr.avance_real::NUMERIC))::NUMERIC+sum((l.peso::NUMERIC/100)*(may.avance_real::NUMERIC))::NUMERIC+sum((l.peso::NUMERIC/100)*(jun.avance_real::NUMERIC))::NUMERIC+sum((l.peso::NUMERIC/100)*(jul.avance_real::NUMERIC))::NUMERIC+sum((l.peso::NUMERIC/100)*(ago.avance_real::NUMERIC))::NUMERIC+sum((l.peso::NUMERIC/100)*(sep.avance_real::NUMERIC))::NUMERIC)::NUMERIC as acum_real_sep,
                    ((sum((l.peso::NUMERIC/100)*(ene.avance_previsto::NUMERIC))::NUMERIC+sum((l.peso::NUMERIC/100)*(feb.avance_previsto::NUMERIC))::NUMERIC+sum((l.peso::NUMERIC/100)*(mar.avance_previsto::NUMERIC))::NUMERIC+sum((l.peso::NUMERIC/100)*(abr.avance_previsto::NUMERIC))::NUMERIC+sum((l.peso::NUMERIC/100)*(may.avance_previsto::NUMERIC))::NUMERIC+sum((l.peso::NUMERIC/100)*(jun.avance_previsto::NUMERIC))::NUMERIC+sum((l.peso::NUMERIC/100)*(jul.avance_previsto::NUMERIC))::NUMERIC+sum((l.peso::NUMERIC/100)*(ago.avance_previsto::NUMERIC))::NUMERIC+sum((l.peso::NUMERIC/100)*(sep.avance_previsto::NUMERIC))::NUMERIC)::NUMERIC-(sum((l.peso::NUMERIC/100)*(ene.avance_real::NUMERIC))::NUMERIC+sum((l.peso::NUMERIC/100)*(feb.avance_real::NUMERIC))::NUMERIC+sum((l.peso::NUMERIC/100)*(mar.avance_real::NUMERIC))::NUMERIC+sum((l.peso::NUMERIC/100)*(abr.avance_real::NUMERIC))::NUMERIC+sum((l.peso::NUMERIC/100)*(may.avance_real::NUMERIC))::NUMERIC+sum((l.peso::NUMERIC/100)*(jun.avance_real::NUMERIC))::NUMERIC+sum((l.peso::NUMERIC/100)*(jul.avance_real::NUMERIC))::NUMERIC+sum((l.peso::NUMERIC/100)*(ago.avance_real::NUMERIC))::NUMERIC+sum((l.peso::NUMERIC/100)*(sep.avance_real::NUMERIC))::NUMERIC)::NUMERIC)::NUMERIC as desviacion_acumulada_sep,

                    sum((l.peso::NUMERIC/100)*(oct.avance_previsto::NUMERIC))::NUMERIC as avance_previsto_oct,
                    sum((l.peso::NUMERIC/100)*(oct.avance_real::NUMERIC))::NUMERIC as avance_real_oct,
                    (sum((l.peso::NUMERIC/100)*(oct.avance_real::NUMERIC))::NUMERIC-sum((l.peso::NUMERIC/100)*(oct.avance_previsto::NUMERIC))::NUMERIC)::NUMERIC as desviacion_mes_oct,
                    (sum((l.peso::NUMERIC/100)*(ene.avance_previsto::NUMERIC))::NUMERIC+sum((l.peso::NUMERIC/100)*(feb.avance_previsto::NUMERIC))::NUMERIC+sum((l.peso::NUMERIC/100)*(mar.avance_previsto::NUMERIC))::NUMERIC+sum((l.peso::NUMERIC/100)*(abr.avance_previsto::NUMERIC))::NUMERIC+sum((l.peso::NUMERIC/100)*(may.avance_previsto::NUMERIC))::NUMERIC+sum((l.peso::NUMERIC/100)*(jun.avance_previsto::NUMERIC))::NUMERIC+sum((l.peso::NUMERIC/100)*(jul.avance_previsto::NUMERIC))::NUMERIC+sum((l.peso::NUMERIC/100)*(ago.avance_previsto::NUMERIC))::NUMERIC+sum((l.peso::NUMERIC/100)*(sep.avance_previsto::NUMERIC))::NUMERIC+sum((l.peso::NUMERIC/100)*(oct.avance_previsto::NUMERIC))::NUMERIC)::NUMERIC as acum_previsto_oct,
                    (sum((l.peso::NUMERIC/100)*(ene.avance_real::NUMERIC))::NUMERIC+sum((l.peso::NUMERIC/100)*(feb.avance_real::NUMERIC))::NUMERIC+sum((l.peso::NUMERIC/100)*(mar.avance_real::NUMERIC))::NUMERIC+sum((l.peso::NUMERIC/100)*(abr.avance_real::NUMERIC))::NUMERIC+sum((l.peso::NUMERIC/100)*(may.avance_real::NUMERIC))::NUMERIC+sum((l.peso::NUMERIC/100)*(jun.avance_real::NUMERIC))::NUMERIC+sum((l.peso::NUMERIC/100)*(jul.avance_real::NUMERIC))::NUMERIC+sum((l.peso::NUMERIC/100)*(ago.avance_real::NUMERIC))::NUMERIC+sum((l.peso::NUMERIC/100)*(sep.avance_real::NUMERIC))::NUMERIC+sum((l.peso::NUMERIC/100)*(oct.avance_real::NUMERIC))::NUMERIC)::NUMERIC as acum_real_oct,
                    ((sum((l.peso::NUMERIC/100)*(ene.avance_real::NUMERIC))::NUMERIC+sum((l.peso::NUMERIC/100)*(feb.avance_real::NUMERIC))::NUMERIC+sum((l.peso::NUMERIC/100)*(mar.avance_real::NUMERIC))::NUMERIC+sum((l.peso::NUMERIC/100)*(abr.avance_real::NUMERIC))::NUMERIC+sum((l.peso::NUMERIC/100)*(may.avance_real::NUMERIC))::NUMERIC+sum((l.peso::NUMERIC/100)*(jun.avance_real::NUMERIC))::NUMERIC+sum((l.peso::NUMERIC/100)*(jul.avance_real::NUMERIC))::NUMERIC+sum((l.peso::NUMERIC/100)*(ago.avance_real::NUMERIC))::NUMERIC+sum((l.peso::NUMERIC/100)*(sep.avance_real::NUMERIC))::NUMERIC+sum((l.peso::NUMERIC/100)*(oct.avance_real::NUMERIC))::NUMERIC)::NUMERIC-(sum((l.peso::NUMERIC/100)*(ene.avance_previsto::NUMERIC))::NUMERIC+sum((l.peso::NUMERIC/100)*(feb.avance_previsto::NUMERIC))::NUMERIC+sum((l.peso::NUMERIC/100)*(mar.avance_previsto::NUMERIC))::NUMERIC+sum((l.peso::NUMERIC/100)*(abr.avance_previsto::NUMERIC))::NUMERIC+sum((l.peso::NUMERIC/100)*(may.avance_previsto::NUMERIC))::NUMERIC+sum((l.peso::NUMERIC/100)*(jun.avance_previsto::NUMERIC))::NUMERIC+sum((l.peso::NUMERIC/100)*(jul.avance_previsto::NUMERIC))::NUMERIC+sum((l.peso::NUMERIC/100)*(ago.avance_previsto::NUMERIC))::NUMERIC+sum((l.peso::NUMERIC/100)*(sep.avance_previsto::NUMERIC))::NUMERIC+sum((l.peso::NUMERIC/100)*(oct.avance_previsto::NUMERIC))::NUMERIC)::NUMERIC)::NUMERIC as desviacion_acumulada_oct,

                    sum((l.peso::NUMERIC/100)*(nov.avance_previsto::NUMERIC))::NUMERIC as avance_previsto_nov,
                    sum((l.peso::NUMERIC/100)*(nov.avance_real::NUMERIC))::NUMERIC as avance_real_nov,
                    (sum((l.peso::NUMERIC/100)*(nov.avance_real::NUMERIC))::NUMERIC-sum((l.peso::NUMERIC/100)*(nov.avance_previsto::NUMERIC))::NUMERIC)::NUMERIC as desviacion_mes_nov,
                    (sum((l.peso::NUMERIC/100)*(ene.avance_previsto::NUMERIC))::NUMERIC+sum((l.peso::NUMERIC/100)*(feb.avance_previsto::NUMERIC))::NUMERIC+sum((l.peso::NUMERIC/100)*(mar.avance_previsto::NUMERIC))::NUMERIC+sum((l.peso::NUMERIC/100)*(abr.avance_previsto::NUMERIC))::NUMERIC+sum((l.peso::NUMERIC/100)*(may.avance_previsto::NUMERIC))::NUMERIC+sum((l.peso::NUMERIC/100)*(jun.avance_previsto::NUMERIC))::NUMERIC+sum((l.peso::NUMERIC/100)*(jul.avance_previsto::NUMERIC))::NUMERIC+sum((l.peso::NUMERIC/100)*(ago.avance_previsto::NUMERIC))::NUMERIC+sum((l.peso::NUMERIC/100)*(sep.avance_previsto::NUMERIC))::NUMERIC+sum((l.peso::NUMERIC/100)*(oct.avance_previsto::NUMERIC))::NUMERIC+sum((l.peso::NUMERIC/100)*(nov.avance_previsto::NUMERIC))::NUMERIC)::NUMERIC as acum_previsto_nov,
                    (sum((l.peso::NUMERIC/100)*(ene.avance_real::NUMERIC))::NUMERIC+sum((l.peso::NUMERIC/100)*(feb.avance_real::NUMERIC))::NUMERIC+sum((l.peso::NUMERIC/100)*(mar.avance_real::NUMERIC))::NUMERIC+sum((l.peso::NUMERIC/100)*(abr.avance_real::NUMERIC))::NUMERIC+sum((l.peso::NUMERIC/100)*(may.avance_real::NUMERIC))::NUMERIC+sum((l.peso::NUMERIC/100)*(jun.avance_real::NUMERIC))::NUMERIC+sum((l.peso::NUMERIC/100)*(jul.avance_real::NUMERIC))::NUMERIC+sum((l.peso::NUMERIC/100)*(ago.avance_real::NUMERIC))::NUMERIC+sum((l.peso::NUMERIC/100)*(sep.avance_real::NUMERIC))::NUMERIC+sum((l.peso::NUMERIC/100)*(oct.avance_real::NUMERIC))::NUMERIC+sum((l.peso::NUMERIC/100)*(nov.avance_real::NUMERIC))::NUMERIC)::NUMERIC as acum_real_nov,
                    ((sum((l.peso::NUMERIC/100)*(ene.avance_real::NUMERIC))::NUMERIC+sum((l.peso::NUMERIC/100)*(feb.avance_real::NUMERIC))::NUMERIC+sum((l.peso::NUMERIC/100)*(mar.avance_real::NUMERIC))::NUMERIC+sum((l.peso::NUMERIC/100)*(abr.avance_real::NUMERIC))::NUMERIC+sum((l.peso::NUMERIC/100)*(may.avance_real::NUMERIC))::NUMERIC+sum((l.peso::NUMERIC/100)*(jun.avance_real::NUMERIC))::NUMERIC+sum((l.peso::NUMERIC/100)*(jul.avance_real::NUMERIC))::NUMERIC+sum((l.peso::NUMERIC/100)*(ago.avance_real::NUMERIC))::NUMERIC+sum((l.peso::NUMERIC/100)*(sep.avance_real::NUMERIC))::NUMERIC+sum((l.peso::NUMERIC/100)*(oct.avance_real::NUMERIC))::NUMERIC+sum((l.peso::NUMERIC/100)*(nov.avance_real::NUMERIC))::NUMERIC)::NUMERIC-(sum((l.peso::NUMERIC/100)*(ene.avance_previsto::NUMERIC))::NUMERIC+sum((l.peso::NUMERIC/100)*(feb.avance_previsto::NUMERIC))::NUMERIC+sum((l.peso::NUMERIC/100)*(mar.avance_previsto::NUMERIC))::NUMERIC+sum((l.peso::NUMERIC/100)*(abr.avance_previsto::NUMERIC))::NUMERIC+sum((l.peso::NUMERIC/100)*(may.avance_previsto::NUMERIC))::NUMERIC+sum((l.peso::NUMERIC/100)*(jun.avance_previsto::NUMERIC))::NUMERIC+sum((l.peso::NUMERIC/100)*(jul.avance_previsto::NUMERIC))::NUMERIC+sum((l.peso::NUMERIC/100)*(ago.avance_previsto::NUMERIC))::NUMERIC+sum((l.peso::NUMERIC/100)*(sep.avance_previsto::NUMERIC))::NUMERIC+sum((l.peso::NUMERIC/100)*(oct.avance_previsto::NUMERIC))::NUMERIC+sum((l.peso::NUMERIC/100)*(nov.avance_previsto::NUMERIC))::NUMERIC)::NUMERIC)::NUMERIC desviacion_acumulada_nov,

                    sum((l.peso::NUMERIC/100)*(dic.avance_previsto::NUMERIC))::NUMERIC as avance_previsto_dic,
                    sum((l.peso::NUMERIC/100)*(dic.avance_real::NUMERIC))::NUMERIC as avance_real_dic,
                    (sum((l.peso::NUMERIC/100)*(dic.avance_real::NUMERIC))::NUMERIC-sum((l.peso::NUMERIC/100)*(dic.avance_previsto::NUMERIC))::NUMERIC)::NUMERIC as desviacion_mes_dic,
                    (sum((l.peso::NUMERIC/100)*(ene.avance_previsto::NUMERIC))::NUMERIC+sum((l.peso::NUMERIC/100)*(feb.avance_previsto::NUMERIC))::NUMERIC+sum((l.peso::NUMERIC/100)*(mar.avance_previsto::NUMERIC))::NUMERIC+sum((l.peso::NUMERIC/100)*(abr.avance_previsto::NUMERIC))::NUMERIC+sum((l.peso::NUMERIC/100)*(may.avance_previsto::NUMERIC))::NUMERIC+sum((l.peso::NUMERIC/100)*(jun.avance_previsto::NUMERIC))::NUMERIC+sum((l.peso::NUMERIC/100)*(jul.avance_previsto::NUMERIC))::NUMERIC+sum((l.peso::NUMERIC/100)*(ago.avance_previsto::NUMERIC))::NUMERIC+sum((l.peso::NUMERIC/100)*(sep.avance_previsto::NUMERIC))::NUMERIC+sum((l.peso::NUMERIC/100)*(oct.avance_previsto::NUMERIC))::NUMERIC+sum((l.peso::NUMERIC/100)*(nov.avance_previsto::NUMERIC))::NUMERIC+sum((l.peso::NUMERIC/100)*(dic.avance_previsto::NUMERIC))::NUMERIC)::NUMERIC as acum_previsto_dic,
                    (sum((l.peso::NUMERIC/100)*(ene.avance_real::NUMERIC))::NUMERIC+sum((l.peso::NUMERIC/100)*(feb.avance_real::NUMERIC))::NUMERIC+sum((l.peso::NUMERIC/100)*(mar.avance_real::NUMERIC))::NUMERIC+sum((l.peso::NUMERIC/100)*(abr.avance_real::NUMERIC))::NUMERIC+sum((l.peso::NUMERIC/100)*(may.avance_real::NUMERIC))::NUMERIC+sum((l.peso::NUMERIC/100)*(jun.avance_real::NUMERIC))::NUMERIC+sum((l.peso::NUMERIC/100)*(jul.avance_real::NUMERIC))::NUMERIC+sum((l.peso::NUMERIC/100)*(ago.avance_real::NUMERIC))::NUMERIC+sum((l.peso::NUMERIC/100)*(sep.avance_real::NUMERIC))::NUMERIC+sum((l.peso::NUMERIC/100)*(oct.avance_real::NUMERIC))::NUMERIC+sum((l.peso::NUMERIC/100)*(nov.avance_real::NUMERIC))::NUMERIC + sum((l.peso::NUMERIC/100)*(dic.avance_real::NUMERIC))::NUMERIC)::NUMERIC as acum_real_dic,
                    ((sum((l.peso::NUMERIC/100)*(ene.avance_real::NUMERIC))::NUMERIC+sum((l.peso::NUMERIC/100)*(feb.avance_real::NUMERIC))::NUMERIC+sum((l.peso::NUMERIC/100)*(mar.avance_real::NUMERIC))::NUMERIC+sum((l.peso::NUMERIC/100)*(abr.avance_real::NUMERIC))::NUMERIC+sum((l.peso::NUMERIC/100)*(may.avance_real::NUMERIC))::NUMERIC+sum((l.peso::NUMERIC/100)*(jun.avance_real::NUMERIC))::NUMERIC+sum((l.peso::NUMERIC/100)*(jul.avance_real::NUMERIC))::NUMERIC+sum((l.peso::NUMERIC/100)*(ago.avance_real::NUMERIC))::NUMERIC+sum((l.peso::NUMERIC/100)*(sep.avance_real::NUMERIC))::NUMERIC+sum((l.peso::NUMERIC/100)*(oct.avance_real::NUMERIC))::NUMERIC+sum((l.peso::NUMERIC/100)*(nov.avance_real::NUMERIC))::NUMERIC + sum((l.peso::NUMERIC/100)*(dic.avance_real::NUMERIC))::NUMERIC)::NUMERIC - (sum((l.peso::NUMERIC/100)*(ene.avance_previsto::NUMERIC))::NUMERIC+sum((l.peso::NUMERIC/100)*(feb.avance_previsto::NUMERIC))::NUMERIC+sum((l.peso::NUMERIC/100)*(mar.avance_previsto::NUMERIC))::NUMERIC+sum((l.peso::NUMERIC/100)*(abr.avance_previsto::NUMERIC))::NUMERIC+sum((l.peso::NUMERIC/100)*(may.avance_previsto::NUMERIC))::NUMERIC+sum((l.peso::NUMERIC/100)*(jun.avance_previsto::NUMERIC))::NUMERIC+sum((l.peso::NUMERIC/100)*(jul.avance_previsto::NUMERIC))::NUMERIC+sum((l.peso::NUMERIC/100)*(ago.avance_previsto::NUMERIC))::NUMERIC+sum((l.peso::NUMERIC/100)*(sep.avance_previsto::NUMERIC))::NUMERIC+sum((l.peso::NUMERIC/100)*(oct.avance_previsto::NUMERIC))::NUMERIC+sum((l.peso::NUMERIC/100)*(nov.avance_previsto::NUMERIC))::NUMERIC+sum((l.peso::NUMERIC/100)*(dic.avance_previsto::NUMERIC))::NUMERIC)::NUMERIC)::NUMERIC as desviacion_acumulada_dic
                    ,0::integer as orden_logico
                    FROM ssig.tplan p
                    --LEFT JOIN ssig.tplan_funcionario pf ON pf.id_plan=p.id_plan
                    --LEFT JOIN orga.vfuncionario vf ON vf.id_funcionario=pf.id_funcionario
                    JOIN ssig.tlinea l ON l.id_plan=p.id_plan
                    JOIN ssig.tlinea_avance ene ON ene.id_linea=l.id_linea AND ene.mes LIKE ''%ene%''
                    JOIN ssig.tlinea_avance feb ON feb.id_linea=l.id_linea AND feb.mes LIKE ''%feb%''
                    JOIN ssig.tlinea_avance mar ON mar.id_linea=l.id_linea AND mar.mes LIKE ''%mar%''
                    JOIN ssig.tlinea_avance abr ON abr.id_linea=l.id_linea AND abr.mes LIKE ''%abr%''
                    JOIN ssig.tlinea_avance may ON may.id_linea=l.id_linea AND may.mes LIKE ''%may%''
                    JOIN ssig.tlinea_avance jun ON jun.id_linea=l.id_linea AND jun.mes LIKE ''%jun%''
                    JOIN ssig.tlinea_avance jul ON jul.id_linea=l.id_linea AND jul.mes LIKE ''%jul%''
                    JOIN ssig.tlinea_avance ago ON ago.id_linea=l.id_linea AND ago.mes LIKE ''%ago%''
                    JOIN ssig.tlinea_avance sep ON sep.id_linea=l.id_linea AND sep.mes LIKE ''%sep%''
                    JOIN ssig.tlinea_avance oct ON oct.id_linea=l.id_linea AND oct.mes LIKE ''%oct%''
                    JOIN ssig.tlinea_avance nov ON nov.id_linea=l.id_linea AND nov.mes LIKE ''%nov%''
                    JOIN ssig.tlinea_avance dic ON dic.id_linea=l.id_linea AND dic.mes LIKE ''%dic%''
                    ';
                    
      v_consulta := '';

      -- v_resp = REPLACE(v_id_plan_hijo::VARCHAR,'{','');
      -- v_resp = REPLACE(v_resp::VARCHAR,'}','');
      FOR item IN EXECUTE(v_consulta3||' WHERE p.id_plan =ANY ('''||v_id_plan_hijo::VARCHAR||''')  AND p.id_gestion='||v_parametros.id_gestion||' AND l.nivel IS NULL GROUP BY p.id_plan, p.id_plan_padre, p.nivel, p.nombre_plan, p.peso order by p.nombre_plan asc ') LOOP
          v_consulta2 :='INSERT INTO tt_pglobal_temporal ('||v_columnas||') '||v_consulta3||' WHERE  p.id_plan = '||item.id_plan||' AND p.id_gestion='||v_parametros.id_gestion||' AND l.nivel IS NULL  GROUP BY p.id_plan, p.id_plan_padre, p.nivel, p.nombre_plan, p.peso';  
          execute v_consulta2;
          FOR item1 IN EXECUTE(v_consulta1||' and l.id_plan = '||item.id_plan||' and l.id_linea_padre is null  order by l.orden_logico ASC') LOOP
              v_consulta2 :='INSERT INTO tt_pglobal_temporal ('||v_columnas||') '||v_consulta1||' and l.id_linea = '||item1.id_linea;  
              execute v_consulta2;
              FOR item2 IN EXECUTE(v_consulta1||' and l.id_linea_padre = '||item1.id_linea||' order by l.orden_logico ASC') LOOP
                  v_consulta2 :='INSERT INTO tt_pglobal_temporal ('||v_columnas||') '||v_consulta1||' and l.id_linea = '||item2.id_linea;  
                  execute(v_consulta2);
                  FOR item3 IN EXECUTE(v_consulta1||' and l.id_linea_padre = '||item2.id_linea||' order by l.orden_logico ASC') LOOP
                      v_consulta2 :='INSERT INTO tt_pglobal_temporal ('||v_columnas||') '||v_consulta1||' and l.id_linea = '||item3.id_linea;  
                      execute(v_consulta2);
                  END LOOP;    
              END LOOP;
          END LOOP; 
      END LOOP;

      v_consulta3 :='select * from tt_pglobal_temporal ORDER BY id_pglobal_temporal';
      
      RETURN v_consulta3;

    END;

  ELSE

    RAISE EXCEPTION 'Transaccion inexistente';

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
