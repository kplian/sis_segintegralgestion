CREATE OR REPLACE FUNCTION ssig.ft_cuestionario_sel (
  p_administrador integer,
  p_id_usuario integer,
  p_tabla varchar,
  p_transaccion varchar
)
RETURNS varchar AS
$body$
/**************************************************************************
 SISTEMA:		Seguimiento integral de gestión
 FUNCION: 		ssig.ft_cuestionario_sel
 DESCRIPCION:   Funcion que devuelve conjuntos de registros de las consultas relacionadas con la tabla 'ssig.tcuestionario'
 AUTOR: 		 (mguerra)
 FECHA:	        21-04-2020 08:31:41
 COMENTARIOS:
***************************************************************************
 HISTORIAL DE MODIFICACIONES:
#ISSUE				FECHA				AUTOR				DESCRIPCION
 #0				21-04-2020 08:31:41								Funcion que devuelve conjuntos de registros de las consultas relacionadas con la tabla 'ssig.tcuestionario'
 #3				04-05-2020 08:31:41			manuel guerra	corrección de count en función
 #
 ***************************************************************************/

DECLARE

	v_consulta    		varchar;
	v_parametros  		record;
	v_nombre_funcion   	text;
	v_resp				varchar;
    v_filtro			varchar;
	v_consulta1      	VARCHAR;
    v_consulta2      	VARCHAR;
    v_consulta3      	VARCHAR;
    item              	RECORD;
  	item1             	RECORD;
    v_aux				integer;
    v_id_usuario		integer;
    v_cuestionario		VARCHAR;
    v_peso				numeric;
    v_valor				varchar;
    v_valor_b			varchar;
BEGIN

	v_nombre_funcion = 'ssig.ft_cuestionario_sel';
    v_parametros = pxp.f_get_record(p_tabla);

	/*********************************
 	#TRANSACCION:  'SSIG_CUE_SEL'
 	#DESCRIPCION:	Consulta de datos
 	#AUTOR:		mguerra
 	#FECHA:		21-04-2020 08:31:41
	***********************************/

	if(p_transaccion='SSIG_CUE_SEL')then

    	begin
    		--Sentencia de la consulta
            IF v_parametros.pes_estado in ('borrador') THEN
				v_filtro = 'cue.estado=''borrador'' ';
            END IF;
            IF v_parametros.pes_estado in ('enviado') THEN
            	v_filtro = 'cue.estado=''enviado'' ';
            END IF;
			v_consulta:='
            			WITH funcionario as
                        (SELECT array_to_string( array_agg(PERSON.nombre_completo2) , ''<br>'') as funcionarios,c.id_cuestionario
                        FROM ssig.tcuestionario_funcionario cf
                        JOIN ssig.tcuestionario c ON c.id_cuestionario=cf.id_cuestionario
                        JOIN orga.tfuncionario FUNCIO ON FUNCIO.id_funcionario=cf.id_funcionario
                        JOIN SEGU.vpersona PERSON ON PERSON.id_persona=FUNCIO.id_persona
                        group by c.id_cuestionario)

            			select
						cue.id_cuestionario,
						cue.estado_reg,
						cue.cuestionario,
						cue.habilitar,
						cue.observacion,
						cue.id_usuario_reg,
						cue.fecha_reg,
						cue.id_usuario_ai,
						cue.usuario_ai,
						cue.id_usuario_mod,
						cue.fecha_mod,
                        cue.estado,
						usu1.cuenta as usr_reg,
						usu2.cuenta as usr_mod,
                        ''''::varchar as funcionarios,
                        (SELECT array_to_string( array_agg( cf.id_funcionario), '','' )
                        FROM ssig.tcuestionario_funcionario cf
                        JOIN ssig.tcuestionario c ON c.id_cuestionario=cf.id_cuestionario
                        WHERE cf.id_cuestionario=cue.id_cuestionario)::VARCHAR AS id_funcionarios,
                        cue.peso,
                        t.tipo::varchar,
                        cue.id_tipo_evalucion,
                        t.nombre::varchar as desc_nombre
						from ssig.tcuestionario cue
						inner join segu.tusuario usu1 on usu1.id_usuario = cue.id_usuario_reg
						left join segu.tusuario usu2 on usu2.id_usuario = cue.id_usuario_mod
                        left join funcionario f on f.id_cuestionario=cue.id_cuestionario
                        join ssig.tencuesta t on t.id_encuesta = cue.id_tipo_evalucion
				        where '||v_filtro||' and';

			--Definicion de la respuesta
			v_consulta:=v_consulta||v_parametros.filtro;
            raise notice '%',v_consulta;

			v_consulta:=v_consulta||' order by ' ||v_parametros.ordenacion|| ' ' || v_parametros.dir_ordenacion || ' limit ' || v_parametros.cantidad || ' offset ' || v_parametros.puntero;

			--Devuelve la respuesta
			return v_consulta;

		end;

	/*********************************
 	#TRANSACCION:  'SSIG_CUE_CONT'
 	#DESCRIPCION:	Conteo de registros
 	#AUTOR:		mguerra
 	#FECHA:		21-04-2020 08:31:41
	***********************************/

	elsif(p_transaccion='SSIG_CUE_CONT')then

		begin
        	IF v_parametros.pes_estado in ('borrador') THEN
				v_filtro = 'cue.estado=''borrador'' ';
            END IF;
            IF v_parametros.pes_estado in ('enviado') THEN
            	v_filtro = 'cue.estado=''enviado'' ';
            END IF;
			--Sentencia de la consulta de conteo de registros
			v_consulta:=' WITH funcionario as
                        (SELECT array_to_string( array_agg(PERSON.nombre_completo2) , ''<br>'') as funcionarios,c.id_cuestionario
                        FROM ssig.tcuestionario_funcionario cf
                        JOIN ssig.tcuestionario c ON c.id_cuestionario=cf.id_cuestionario
                        JOIN orga.tfuncionario FUNCIO ON FUNCIO.id_funcionario=cf.id_funcionario
                        JOIN SEGU.vpersona PERSON ON PERSON.id_persona=FUNCIO.id_persona
                        group by c.id_cuestionario)
            			select count(cue.id_cuestionario)
					    from ssig.tcuestionario cue
						inner join segu.tusuario usu1 on usu1.id_usuario = cue.id_usuario_reg
						left join segu.tusuario usu2 on usu2.id_usuario = cue.id_usuario_mod
                        join funcionario f on f.id_cuestionario=cue.id_cuestionario
                        inner join ssig.tencuesta t on t.id_encuesta = cue.id_tipo_evalucion
				        where '||v_filtro||' and ';

			--Definicion de la respuesta
            raise notice '%',v_consulta;
			v_consulta:=v_consulta||v_parametros.filtro;

			--Devuelve la respuesta
			return v_consulta;

		end;


    /*********************************
    #TRANSACCION:  'SSIG_LISCUE_SEL'
    #DESCRIPCION: Lista de cuestionario
    #AUTOR:   manu
    #FECHA:   20-11-2020 10:44:58
    ***********************************/

  	elseif(p_transaccion='SSIG_LISCUE_SEL')then

    	begin

			v_consulta1 :='';
            v_consulta1 := v_consulta1 || 'CREATE TEMP TABLE ttemporal(id_temporal SERIAL,
                                                                      id_pregunta INTEGER,
                                                                      pregunta VARCHAR,
                                                                      tipo VARCHAR,
                                                                      respuesta VARCHAR,
                                                                      id_cuestionario INTEGER,
                                                                      id_categoria INTEGER,
                                                                      id_usuario_reg INTEGER,
                                                                      sw_nivel INTEGER,
                                                                      id_func_evaluado INTEGER
                                                                      ) ON COMMIT DROP';
            EXECUTE(v_consulta1);
                 -- raise EXCEPTION '%',v_parametros.id_cuestionario;
            FOR item IN(
            			with recursive nodes(id_encuesta, id_encuesta_padre, nombre,categoria, pregunta,estado_reg,id_usuario_reg) as (
    						select padre.id_encuesta,padre.id_encuesta_padre,padre.nombre,padre.categoria,padre.pregunta,padre.estado_reg,padre.id_usuario_reg
                            from ssig.tencuesta padre
                            left join ssig.tcuestionario c on c.id_tipo_evalucion=padre.id_encuesta
                            where padre.id_encuesta_padre is null
                            and c.id_cuestionario=v_parametros.id_cuestionario

                            UNION ALL

                            select hijo.id_encuesta,hijo.id_encuesta_padre, hijo.nombre,hijo.categoria,hijo.pregunta,hijo.estado_reg,hijo.id_usuario_reg-- concat(n.nombre, ' ----> ', hijo.nombre)
                            from ssig.tencuesta hijo
                            join nodes n on n.id_encuesta = hijo.id_encuesta_padre
                        )
                          select
							n.id_encuesta as id_categoria,
                            n.categoria,
                            n.nombre,
                            n.estado_reg,
                            n.id_usuario_reg,
                            n.id_encuesta_padre as id_cuestionario
                          from nodes n
                          where n.categoria='si'
                       ) LOOP

            	v_consulta2 :='';v_aux=1;
                v_consulta2 := v_consulta2 ||'INSERT INTO ttemporal(id_pregunta,
                                                                    pregunta,
                                                                    tipo,
                                                                    respuesta,
                                                                    id_cuestionario,
                                                                    id_categoria,
                                                                    id_usuario_reg,
                                                                    sw_nivel,
                                                                    id_func_evaluado
                                                                    )
                                                                    VALUES';

                v_consulta2 :=v_consulta2||'('||item.id_categoria||',
                							'''|| item.nombre||''',
                                            '''||''||''',
                                            '''||''||''',
                                            '||item.id_cuestionario||',
                                            '||item.id_categoria||',
                                            '||item.id_usuario_reg||',
                                            '||v_aux::INTEGER||',
                                            '||v_parametros.id_funcionario::INTEGER||')';

                EXECUTE(v_consulta2);
					FOR item1 IN(
                   				WITH RECURSIVE arbol AS (
                                  SELECT
                                  enc.id_encuesta_padre,
                                  enc.id_encuesta,
                                  enc.nombre,
                                  cue.id_cuestionario
                                  FROM ssig.tencuesta enc
                                  JOIN ssig.tcuestionario cue on enc.id_encuesta = cue.id_tipo_evalucion
                                  JOIN ssig.ttipo_evalucion te on te.id_tipo_evalucion=cue.id_tipo_evalucion
                                  WHERE enc.grupo='si' and cue.id_cuestionario=v_parametros.id_cuestionario

                                  UNION

                                  SELECT
                                  t.id_encuesta_padre,
                                  t.id_encuesta,
                                  t.nombre,
                                  0 as id_cuestionario
                                  FROM ssig.tencuesta t
                                  JOIN ssig.tencuesta rt ON rt.id_encuesta_padre = t.id_encuesta
                                  WHERE t.categoria='si'
                              )
                              SELECT
                              rt.id_encuesta as id_pregunta,
                              rt.nombre as pregunta,
                              rt.tipo_pregunta as tipo,
                              rt.id_encuesta  as id_categoria,
                              rt.id_usuario_reg,
                              (
                                  SELECT
                                  (
                                  SELECT
                                  CASE
                                  WHEN (res.respuesta=1) THEN 'Excelente'
                                  WHEN (res.respuesta=2) THEN 'Destacable'
                                  WHEN (res.respuesta=3) THEN 'Acorde a la posición'
                                  WHEN (res.respuesta=4) THEN 'En desarrollo'
                                  WHEN (res.respuesta=5) THEN 'A desarrollo'
                                  WHEN (rt.tipo_pregunta='Texto') THEN res.respuesta_texto
                                  ELSE ''
                                  END AS respuesta
                                  )
                                  FROM ssig.trespuestas res
                                  WHERE res.id_pregunta=rt.id_encuesta and
                                  res.id_cuestionario = v_parametros.id_cuestionario AND
                                  res.id_func_evaluado = v_parametros.id_funcionario AND
                                  res.id_funcionario in(
                                                          SELECT funcio.id_funcionario
                                                          FROM orga.tfuncionario funcio
                                                          JOIN segu.vpersona person ON funcio.id_persona = person.id_persona
                                                          JOIN segu.tusuario usu ON person.id_persona=usu.id_persona
                                                          WHERE usu.id_usuario= p_id_usuario
                                                        )
                              )as respuesta

                              FROM arbol
                              JOIN ssig.tencuesta rt ON rt.id_encuesta_padre = arbol.id_encuesta
                              left JOIN ssig.tcuestionario cue on rt.id_encuesta = cue.id_tipo_evalucion
                              where arbol.id_encuesta_padre is not null and
                              rt.pregunta='si' and
                              arbol.id_encuesta = item.id_categoria
                                ) LOOP
                          v_consulta2 :='';v_aux=0;
                          v_consulta2 := v_consulta2 || 'INSERT INTO
                                                         	ttemporal(id_pregunta,
                                                                  pregunta,
                                                                  tipo,
                                                                  respuesta,
                                                                  id_cuestionario,
                                                                  id_categoria,
                                                                  id_usuario_reg,
                                                                  sw_nivel,
                                                                  id_func_evaluado
                                                                  )VALUES';
                          v_consulta1:='';
                          IF(item1.respuesta IS NULL)THEN
                             v_consulta1:='';
                          ELSE
                             v_consulta1:=item1.respuesta;
                          END IF;

                          v_consulta2 :=v_consulta2||'('||item1.id_pregunta||',
                          							'''|| item1.pregunta||''',
                                                    '''|| item1.tipo||''',
                                                    '''||v_consulta1::varchar||''',
                                                    '|| v_parametros.id_cuestionario||',
                                                    '||item1.id_categoria||',
                                                    '||v_parametros.id_usuario::INTEGER||',
                                                    '||v_aux::INTEGER||',
                                                    '||v_parametros.id_funcionario::INTEGER||')';

                          --v_consulta3=v_consulta2;
                          EXECUTE(v_consulta2);
                     END LOOP;
           		END LOOP;

      	--Sentencia de la consulta
		v_consulta:='SELECT * FROM ttemporal WHERE ';
        raise notice '%',v_consulta;
--raise exception '%',v_consulta;
        --raise exception '%',v_consulta3;
      	v_consulta:=v_consulta||v_parametros.filtro;
      				v_consulta:=v_consulta||' order by ' ||v_parametros.ordenacion|| ' ' || v_parametros.dir_ordenacion || ' limit ' || v_parametros.cantidad || ' offset ' || v_parametros.puntero;

      --Devuelve la respuesta
      return v_consulta;

    end;
    /*********************************
    #TRANSACCION:  'SSIG_LISCUE_CONT'
    #DESCRIPCION: Conteo de registros de cuestionario
    #AUTOR:   MANU
    #FECHA:   20-11-2017 10:44:58
    ***********************************/

  	elsif(p_transaccion='SSIG_LISCUE_CONT')then

    	begin
      		--Sentencia de la consulta de conteo de registros
      			 v_consulta:='with recursive nodes(id_encuesta, id_encuesta_padre, nombre, pregunta,categoria) as (
                                    select padre.id_encuesta,padre.id_encuesta_padre,padre.nombre,padre.pregunta,padre.categoria
                                    from ssig.tencuesta padre
                                    left join ssig.tcuestionario c on c.id_tipo_evalucion=padre.id_encuesta
                                    where padre.id_encuesta_padre is null
                                    and c.id_cuestionario='|| v_parametros.id_cuestionario||'

                                    UNION ALL

                                    select hijo.id_encuesta,hijo.id_encuesta_padre, hijo.nombre,hijo.pregunta,hijo.categoria
                                    from ssig.tencuesta hijo
                                    join nodes n on n.id_encuesta = hijo.id_encuesta_padre
                                )
                                select count(id_encuesta)
                                from nodes n
                                where n.pregunta=''si'' or n.categoria=''si'' and ';--#3

            --Definicion de la respuesta
            v_consulta:=v_consulta||v_parametros.filtro;
            --Devuelve la respuesta
            return v_consulta;


            --Definicion de la respuesta
            v_consulta:=v_consulta||v_parametros.filtro;
            --Devuelve la respuesta
            return v_consulta;

    	end;

    /*********************************
    #TRANSACCION:  'SSIG_RLISCU_SEL'
    #DESCRIPCION: Lista de cuestionario
    #AUTOR:   manu
    #FECHA:   20-11-2020 10:44:58
    ***********************************/

  	elseif(p_transaccion='SSIG_RLISCU_SEL')then

    	begin

            SELECT
            u.id_usuario
            INTO
            v_id_usuario
            FROM segu.tusuario u
            JOIN segu.vpersona p  ON p.id_persona = u.id_persona
            JOIN orga.tfuncionario f ON f.id_persona = p.id_persona
            where f.id_funcionario=v_parametros.id_funcionario;
            select
            c.cuestionario,
            COALESCE(c.peso,0)
            into
            v_cuestionario,
            v_peso
            from ssig.tcuestionario c
            where c.id_cuestionario =v_parametros.id_cuestionario;

			v_consulta1 :='';
            v_consulta1 := v_consulta1 || 'CREATE TEMP TABLE ttemporal(id_temporal SERIAL,
                                                                      id_pregunta INTEGER,
                                                                      pregunta VARCHAR,
                                                                      tipo VARCHAR,
                                                                      respuesta VARCHAR,
                                                                      id_cuestionario INTEGER,
                                                                      id_categoria INTEGER,
                                                                      id_usuario_reg INTEGER,
                                                                      sw_nivel INTEGER,
                                                                      cuestionario VARCHAR,
                                                                      peso NUMERIC
                                                                      ) ON COMMIT DROP';
            EXECUTE(v_consulta1);
            FOR item IN(SELECT
            			c.id_categoria,
                        c.categoria,
                        c.estado_reg,
                        c.id_usuario_reg,
                        c.id_cuestionario
                        FROM ssig.tcategoria c
                        JOIN segu.tusuario usu1 on usu1.id_usuario = c.id_usuario_reg
                        WHERE c.habilitar = TRUE and c.id_cuestionario = v_parametros.id_cuestionario) LOOP

            	v_consulta2 :='';v_aux=1;
                v_consulta2 := v_consulta2 ||'INSERT INTO ttemporal(id_pregunta,
                                                                    pregunta,
                                                                    tipo,
                                                                    respuesta,
                                                                    id_cuestionario,
                                                                    id_categoria,
                                                                    id_usuario_reg,
                                                                    sw_nivel,
                                                                    cuestionario,
                                                                    peso
                                                                    )
                                                                    VALUES';

                v_consulta2 :=v_consulta2||'('||item.id_categoria||',
                							'''|| item.categoria||''',
                                            '''||''||''',
                                            '''||''||''',
                                            '||item.id_cuestionario||',
                                            '||item.id_categoria||',
                                            '||item.id_usuario_reg||',
                                            '||v_aux::INTEGER||',
                                            '''|| v_cuestionario::VARCHAR||''',
                                            '||v_peso::numeric||')';

                EXECUTE(v_consulta2);
                    --raise exception'%,%,%',v_parametros.id_cuestionario,p_id_usuario,item.id_categoria;--11,330,4
					FOR item1 IN(SELECT
                    			 p.id_pregunta,
                                 p.pregunta,
                                 p.tipo,
                                 p.id_categoria,
                                 p.id_usuario_reg,
                                 (
                                    SELECT
                                    (
                                    SELECT
                                    CASE
                                    WHEN (resp.respuesta=1 and pre.tipo='Selección') THEN 'Excelente'
                                    WHEN (resp.respuesta=2 and pre.tipo='Selección') THEN 'Destacable'
                                    WHEN (resp.respuesta=3 and pre.tipo='Selección') THEN 'Acorde a la posición'
                                    WHEN (resp.respuesta=4 and pre.tipo='Selección') THEN 'En desarrollo'
                                    WHEN (resp.respuesta=5 and pre.tipo='Selección') THEN 'A desarrollo'
                                    WHEN (pre.tipo='Texto') THEN resp.respuesta_texto
                                    ELSE ''
                                    END AS respuesta
                                    )
                                    FROM ssig.trespuestas resp
                                    JOIN ssig.tpregunta pre on pre.id_pregunta=resp.id_pregunta
                                    JOIN ssig.tcuestionario cue on cue.id_cuestionario=resp.id_cuestionario
                                    JOIN ssig.tcategoria cat on cat.id_categoria=resp.id_categoria
                                    WHERE resp.id_pregunta=p.id_pregunta AND
                                    resp.id_cuestionario= v_parametros.id_cuestionario AND
                                    resp.id_funcionario = v_parametros.id_funcionario
                                )::varchar as respuesta
                                FROM ssig.tpregunta p
                                JOIN segu.tusuario usu1 on usu1.id_usuario = p.id_usuario_reg
                                WHERE
                                p.id_categoria=item.id_categoria and
                                p.habilitar=TRUE
                                ORDER BY p.pregunta
                                ) LOOP

                          v_consulta2 :='';v_aux=0;
                          v_consulta2 := v_consulta2 || 'INSERT INTO
                                                         ttemporal(id_pregunta,
                                                                  pregunta,
                                                                  tipo,
                                                                  respuesta,
                                                                  id_cuestionario,
                                                                  id_categoria,
                                                                  id_usuario_reg,
                                                                  sw_nivel,
                                                                  cuestionario,
                                                                  peso
                                                                  )VALUES';
                          v_consulta1:='';
                          IF(item1.respuesta IS NULL)THEN
                             v_consulta1:='';
                          ELSE
                             v_consulta1:=item1.respuesta;
                          END IF;

                          v_consulta2 :=v_consulta2||'('||item1.id_pregunta||',
                          							'''|| item1.pregunta||''',
                                                    '''|| item1.tipo||''',
                                                    '''||v_consulta1::varchar||''',
                                                    '|| item.id_cuestionario||',
                                                    '||item1.id_categoria||',
                                                    '|| v_id_usuario::integer ||',
                                                    '||v_aux::INTEGER||',
                                                    '''|| v_cuestionario::VARCHAR||''',
                                                    '||v_peso::numeric||')';
                          v_consulta3=v_consulta2;

                          EXECUTE(v_consulta2);
                     END LOOP;

           		END LOOP;



      	--Sentencia de la consulta
        v_consulta:='SELECT * FROM ttemporal WHERE ';
        raise notice '%',v_consulta;
      	v_consulta:=v_consulta||v_parametros.filtro;


      --Devuelve la respuesta
      return v_consulta;

    end;


    /*********************************
 	#TRANSACCION:  'SSIG_CUEREP_SEL'
 	#DESCRIPCION:	Consulta de datos
 	#AUTOR:		mguerra
 	#FECHA:		21-04-2020 08:31:41
	***********************************/

	elsif(p_transaccion='SSIG_CUEREP_SEL')then

    	begin
			v_consulta:='SELECT
                         enc.id_encuesta,
                         enc.nombre
                         FROM ssig.tencuesta enc
                         WHERE enc.id_encuesta_padre is null and';

			--Definicion de la respuesta
			v_consulta:=v_consulta||v_parametros.filtro;
			v_consulta:=v_consulta||' order by ' ||v_parametros.ordenacion|| ' ' || v_parametros.dir_ordenacion || ' limit ' || v_parametros.cantidad || ' offset ' || v_parametros.puntero;

			--Devuelve la respuesta
			return v_consulta;

		end;

	/*********************************
 	#TRANSACCION:  'SSIG_CUEREP_CONT'
 	#DESCRIPCION:	Conteo de registros
 	#AUTOR:		mguerra
 	#FECHA:		21-04-2020 08:31:41
	***********************************/

	elsif(p_transaccion='SSIG_CUEREP_CONT')then

		begin
			--Sentencia de la consulta de conteo de registros
			v_consulta:='SELECT
                         count(enc.id_encuesta)
                         FROM ssig.tencuesta enc
                         WHERE enc.id_encuesta_padre is null and ';
			--Definicion de la respuesta
			v_consulta:=v_consulta||v_parametros.filtro;

			--Devuelve la respuesta
			return v_consulta;

		end;

     /*********************************
 	#TRANSACCION:  'SSIG_REEN_SEL'
 	#DESCRIPCION:	Reporte encuesta
 	#AUTOR:		mguerra
 	#FECHA:		21-04-2020 08:31:41
	***********************************/

	elsif(p_transaccion='SSIG_REEN_SEL')then

    	begin
			v_consulta:='with recursive nodes(id_encuesta, id_encuesta_padre, nombre,grupo,categoria,
                              pregunta,peso_categoria) as
                            (
                                select padre.id_encuesta,
                                       padre.id_encuesta_padre,
                                       padre.nombre,
                                       padre.grupo,
                                       padre.categoria,
                                       padre.pregunta,
                                       padre.peso_categoria
                                from ssig.tencuesta padre
                                where padre.id_encuesta_padre is null and
                                      padre.id_encuesta = '||v_parametros.id_encuesta||'
                                UNION ALL
                                select hijo.id_encuesta,
                                       hijo.id_encuesta_padre,
                                       hijo.nombre,
                                       hijo.grupo,
                                       n.categoria,
                                       hijo.pregunta,
                                       hijo.peso_categoria
                                from ssig.tencuesta hijo
                                     join nodes n on n.id_encuesta = hijo.id_encuesta_padre)
                            select id_encuesta_padre,
                                   (
                                     select l.nombre
                                     FROM ssig.tencuesta e
                                          JOIN ssig.tencuesta k on k.id_encuesta = e.id_encuesta_padre
                                          JOIN ssig.tencuesta l on l.id_encuesta = k.id_encuesta_padre
                                     WHERE e.id_encuesta = nodes.id_encuesta_padre
                                   ) as titulo,
                                   (
                                     select k.nombre
                                     FROM ssig.tencuesta e
                                          JOIN ssig.tencuesta k on k.id_encuesta = e.id_encuesta_padre
                                     WHERE e.id_encuesta = nodes.id_encuesta_padre
                                   ) as grupo,
                                   (
                                     select enc.peso_categoria
                                     from ssig.tencuesta enc
                                     where enc.id_encuesta = nodes.id_encuesta_padre
                                   ) as pesoxpregunta,
                                   (
                                     select enc.nombre
                                     from ssig.tencuesta enc
                                     where enc.id_encuesta = nodes.id_encuesta_padre
                                   ) as nombre_cat,
                                   fun.desc_funcionario1 as evaluador,
                                   ger.nombre_unidad as gerencia,
                                   func.desc_funcionario1 as evaluado,
                                   func.descripcion_cargo,
                                   sum(CASE
                                         WHEN (r.respuesta = 1) THEN (
                                                                       select enc.peso_categoria * 1
                                                                       from ssig.tencuesta enc
                                                                       where enc.id_encuesta =
                                                                         nodes.id_encuesta_padre
                                   )
                                         WHEN (r.respuesta = 2) THEN (
                                                                       select enc.peso_categoria * 0.8
                                                                       from ssig.tencuesta enc
                                                                       where enc.id_encuesta =
                                                                         nodes.id_encuesta_padre
                                   )
                                         WHEN (r.respuesta = 3) THEN (
                                                                       select enc.peso_categoria * 0.5
                                                                       from ssig.tencuesta enc
                                                                       where enc.id_encuesta =
                                                                         nodes.id_encuesta_padre
                                   )
                                         WHEN (r.respuesta = 4) THEN (
                                                                       select enc.peso_categoria * 0.3
                                                                       from ssig.tencuesta enc
                                                                       where enc.id_encuesta =
                                                                         nodes.id_encuesta_padre
                                   )
                                         WHEN (r.respuesta = 5) THEN (
                                                                       select r.respuesta * 0
                                                                       from ssig.tencuesta enc
                                                                       where enc.id_encuesta =
                                                                         nodes.id_encuesta_padre
                                   )
                                       END) as resp
                            from nodes
                                 left join ssig.trespuestas r on r.id_pregunta = nodes.id_encuesta
                                 inner join orga.vfuncionario fun on fun.id_funcionario = r.id_funcionario
                                 inner join orga.vfuncionario_cargo func on func.id_funcionario =
                                   r.id_func_evaluado
                                 inner join orga.tuo ger on ger.id_uo = orga.f_get_uo_gerencia(func.id_uo,
                                   NULL::integer, NULL::date) and (func.fecha_finalizacion is null or
                                   func.fecha_finalizacion >= now()::date)
                            where nodes.pregunta = ''si''
                            group by nodes.id_encuesta_padre,
                                     fun.desc_funcionario1,
                                     func.descripcion_cargo,
                                     ger.nombre_unidad,
                                     evaluador,
                                     evaluado,
                                     pesoxpregunta,
                                     titulo,
                                     grupo';

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
PARALLEL UNSAFE
COST 100;

ALTER FUNCTION ssig.ft_cuestionario_sel (p_administrador integer, p_id_usuario integer, p_tabla varchar, p_transaccion varchar)
  OWNER TO postgres;