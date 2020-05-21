--------------- SQL ---------------

CREATE OR REPLACE FUNCTION ssig.ft_cuestionario_ime (
  p_administrador integer,
  p_id_usuario integer,
  p_tabla varchar,
  p_transaccion varchar
)
RETURNS varchar AS
$body$
/**************************************************************************
 SISTEMA:		Seguimiento integral de gestión
 FUNCION: 		ssig.ft_cuestionario_ime
 DESCRIPCION:   Funcion que gestiona las operaciones basicas (inserciones, modificaciones, eliminaciones de la tabla 'ssig.tcuestionario'
 AUTOR: 		 (mguerra)
 FECHA:	        21-04-2020 08:31:41
 COMENTARIOS:
***************************************************************************
 HISTORIAL DE MODIFICACIONES:
#ISSUE				FECHA				AUTOR				DESCRIPCION
 #0				21-04-2020 08:31:41								Funcion que gestiona las operaciones basicas (inserciones, modificaciones, eliminaciones de la tabla 'ssig.tcuestionario'
 #16            19/05/2020				manuel guerra		correcciones en correo
 #
 ***************************************************************************/

DECLARE

	v_nro_requerimiento    	integer;
	v_parametros           	record;
	v_id_requerimiento     	integer;
	v_resp		            varchar;
	v_nombre_funcion        text;
	v_mensaje_error         text;
	v_id_cuestionario		integer;
    va_id_funcionarios    	VARCHAR [];
  	v_id_funcionario      	INTEGER;
    item					record;
    v_correo				VARCHAR;
	v_id_pregunta           VARCHAR;
  	v_id_pregunta_texto     VARCHAR;
    v_record_tipo_evaluacion 	record;
	v_id_uo						integer;
    v_id_uo_estruc				integer;
    v_record					record;
    v_id_cuestionario_funcionario integer;
    v_recor_evaluador			  record;
    v_templa				      varchar;
    v_record_datos				  record;
    v_id_evaluador 				  integer[];
    v_lista_negra				  varchar;
  	v_acceso_directo		  		  varchar;
    v_id_correo						integer;--#16
BEGIN

    v_nombre_funcion = 'ssig.ft_cuestionario_ime';
    v_parametros = pxp.f_get_record(p_tabla);

	/*********************************
 	#TRANSACCION:  'SSIG_CUE_INS'
 	#DESCRIPCION:	Insercion de registros
 	#AUTOR:		mguerra
 	#FECHA:		21-04-2020 08:31:41
	***********************************/

	if(p_transaccion='SSIG_CUE_INS')then

        begin
        v_id_cuestionario = null;
        v_id_cuestionario_funcionario = null;


       -- raise exception '%',v_parametros.id_tipo_evalucion;

        	--Sentencia de la insercion
        	insert into ssig.tcuestionario(
			estado_reg,
            habilitar,
			id_usuario_reg,
			fecha_reg,
			id_usuario_ai,
			usuario_ai,
			id_usuario_mod,
			fecha_mod,
            estado,
            id_tipo_evalucion
          	) values(
			'activo',
			v_parametros.habilitar,
			p_id_usuario,
			now(),
			v_parametros._id_usuario_ai,
			v_parametros._nombre_usuario_ai,
			null,
			null,
            'borrador' ,
            v_parametros.id_tipo_evalucion
			)RETURNING id_cuestionario into v_id_cuestionario;


            if (v_id_cuestionario is not null) then

                select e.tipo
                into v_record_tipo_evaluacion
                from ssig.tencuesta e
                where e.id_encuesta= v_parametros.id_tipo_evalucion;
                if (v_record_tipo_evaluacion.tipo = 'auto_evaluacion')then

                     va_id_funcionarios := string_to_array(v_parametros.id_funcionarios, ',');

                	 foreach v_id_funcionario in array va_id_funcionarios loop
                                insert into ssig.tcuestionario_funcionario (
                                  id_cuestionario,
                                  id_funcionario,
                                  estado_reg,
                                  fecha_reg,
                                  usuario_ai,
                                  id_usuario_reg,
                                  id_usuario_ai,
                                  fecha_mod,
                                  id_usuario_mod
                                ) values (
                                  v_id_cuestionario,
                                  v_id_funcionario,
                                  'activo',
                                  now(),
                                  v_parametros._nombre_usuario_ai,
                                  p_id_usuario,
                                  v_parametros._id_usuario_ai,
                                  null,
                                  null
                              ) RETURNING id_cuestionario_funcionario into v_id_cuestionario_funcionario;

                              insert into ssig.tevaluados(id_usuario_reg,
                                                          id_usuario_mod,
                                                          fecha_reg,
                                                          fecha_mod,
                                                          estado_reg,
                                                          id_usuario_ai,
                                                          usuario_ai,
                                                          obs_dba,
                                                          id_cuestionario_funcionario,
                                                          id_funcionario,
                                                          evaluar
                                                        )
                                                        values (
                                                          p_id_usuario,
                                                          null,
                                                          now(),
                                                          null,
                                                          'activo',
                                                          v_parametros._id_usuario_ai,
                                                          v_parametros._nombre_usuario_ai,
                                                          null,
                                                          v_id_cuestionario_funcionario,
                                                          v_id_funcionario,
                                                          'si'
                                                        );

                     end loop;
                end if;

               if (v_record_tipo_evaluacion.tipo = 'inferior') then

               	va_id_funcionarios := string_to_array(v_parametros.id_funcionarios, ',');
                     --obtener datos de funcionario
                      foreach v_id_funcionario in array va_id_funcionarios loop

                        insert into ssig.tcuestionario_funcionario (
                                  id_cuestionario,
                                  id_funcionario,
                                  estado_reg,
                                  fecha_reg,
                                  usuario_ai,
                                  id_usuario_reg,
                                  id_usuario_ai,
                                  fecha_mod,
                                  id_usuario_mod
                                ) values (
                                  v_id_cuestionario,
                                  v_id_funcionario,
                                  'activo',
                                  now(),
                                  v_parametros._nombre_usuario_ai,
                                  p_id_usuario,
                                  v_parametros._id_usuario_ai,
                                  null,
                                  null
                              ) RETURNING id_cuestionario_funcionario into v_id_cuestionario_funcionario;


                                select uo.id_uo into v_id_uo
                                from orga.tuo_funcionario uo
                                where uo.id_funcionario = v_id_funcionario;

			--raise exception '%',v_id_uo;

                      			for v_record in ( with hijo as (select euo.id_uo_hijo,--id
                                                                 euo.id_uo_padre---padre
                                                           from orga.testructura_uo euo
                                                           where euo.id_uo_hijo = v_id_uo and euo.estado_reg = 'activo')
                                                select  orr.id_uo_hijo,
                                                        orr.id_uo_padre,
                                                        fun.id_funcionario,
                                                        fun.desc_funcionario1,
                                                        uo.nombre_unidad,
                                                        ni.numero_nivel
                                                from hijo hr
                                                inner join orga.testructura_uo orr on orr.id_uo_hijo = hr.id_uo_padre
                                                inner join orga.tuo uo on uo.id_uo = hr.id_uo_padre
                                                inner join orga.vfuncionario_cargo fun on fun.id_uo = orr.id_uo_hijo
                                                inner join orga.tnivel_organizacional ni on ni.id_nivel_organizacional = uo.id_nivel_organizacional
                                                where (fun.fecha_finalizacion is null or fun.fecha_finalizacion >= now()))loop

                                       insert into ssig.tevaluados(id_usuario_reg,
                                                          id_usuario_mod,
                                                          fecha_reg,
                                                          fecha_mod,
                                                          estado_reg,
                                                          id_usuario_ai,
                                                          usuario_ai,
                                                          obs_dba,
                                                          id_cuestionario_funcionario,
                                                          id_funcionario,
                                                          evaluar
                                                        )
                                                        values (
                                                          p_id_usuario,
                                                          null,
                                                          now(),
                                                          null,
                                                          'activo',
                                                          v_parametros._id_usuario_ai,
                                                          v_parametros._nombre_usuario_ai,
                                                          null,
                                                          v_id_cuestionario_funcionario,
                                                          v_record.id_funcionario,
                                                          'si'
                                                        );
                                end loop;

                     end loop;

                	end if;
                if (v_record_tipo_evaluacion.tipo = 'superior')then

                	va_id_funcionarios := string_to_array(v_parametros.id_funcionarios, ',');
                     --obtener datos de funcionario
                      foreach v_id_funcionario in array va_id_funcionarios loop

                        insert into ssig.tcuestionario_funcionario (
                                  id_cuestionario,
                                  id_funcionario,
                                  estado_reg,
                                  fecha_reg,
                                  usuario_ai,
                                  id_usuario_reg,
                                  id_usuario_ai,
                                  fecha_mod,
                                  id_usuario_mod
                                ) values (
                                  v_id_cuestionario,
                                  v_id_funcionario,
                                  'activo',
                                  now(),
                                  v_parametros._nombre_usuario_ai,
                                  p_id_usuario,
                                  v_parametros._id_usuario_ai,
                                  null,
                                  null
                              ) RETURNING id_cuestionario_funcionario into v_id_cuestionario_funcionario;


                                select uo.id_uo into v_id_uo
                                from orga.tuo_funcionario uo
                                where uo.id_funcionario = v_id_funcionario;


								--raise exception '%',v_id_uo;

                      			for v_record in (with recursive uo_mas_subordinados(id_uo_hijo,id_uo_padre) as (
                                       select euo.id_uo_hijo,--id
                                             id_uo_padre---padre
                                       from orga.testructura_uo euo
                                       where euo.id_uo_hijo = v_id_uo and euo.estado_reg = 'activo'
                                       union
                                       select e.id_uo_hijo,
                                              e.id_uo_padre
                                       from orga.testructura_uo e
                                       inner join uo_mas_subordinados s on s.id_uo_hijo = e.id_uo_padre
                                       and e.estado_reg = 'activo'
                                    )select fun.id_funcionario,
                                            fun.desc_funcionario1
                                     from uo_mas_subordinados suo
                                     inner join orga.tuo ou on ou.id_uo = suo.id_uo_hijo
                                     inner join orga.vfuncionario_cargo fun on fun.id_uo = suo.id_uo_hijo
                                     inner join orga.tnivel_organizacional ni on ni.id_nivel_organizacional = ou.id_nivel_organizacional
                                     where (fun.fecha_finalizacion is null or fun.fecha_finalizacion >= now()::date /*fun.fecha_finalizacion >= now()::date*/)
                                     and ni.numero_nivel = 4 and fun.id_funcionario <> v_id_funcionario)loop

                                       insert into ssig.tevaluados(id_usuario_reg,
                                                          id_usuario_mod,
                                                          fecha_reg,
                                                          fecha_mod,
                                                          estado_reg,
                                                          id_usuario_ai,
                                                          usuario_ai,
                                                          obs_dba,
                                                          id_cuestionario_funcionario,
                                                          id_funcionario,
                                                          evaluar
                                                        )
                                                        values (
                                                          p_id_usuario,
                                                          null,
                                                          now(),
                                                          null,
                                                          'activo',
                                                          v_parametros._id_usuario_ai,
                                                          v_parametros._nombre_usuario_ai,
                                                          null,
                                                          v_id_cuestionario_funcionario,
                                                          v_record.id_funcionario,
                                                          'si'
                                                        );
                                end loop;

                     end loop;

                end if;

                if (v_record_tipo_evaluacion.tipo = 'medio')then

                va_id_funcionarios := string_to_array(v_parametros.id_funcionarios, ',');
                     --obtener datos de funcionario
                      foreach v_id_funcionario in array va_id_funcionarios loop

                        insert into ssig.tcuestionario_funcionario (
                                  id_cuestionario,
                                  id_funcionario,
                                  estado_reg,
                                  fecha_reg,
                                  usuario_ai,
                                  id_usuario_reg,
                                  id_usuario_ai,
                                  fecha_mod,
                                  id_usuario_mod
                                ) values (
                                  v_id_cuestionario,
                                  v_id_funcionario,
                                  'activo',
                                  now(),
                                  v_parametros._nombre_usuario_ai,
                                  p_id_usuario,
                                  v_parametros._id_usuario_ai,
                                  null,
                                  null
                              ) RETURNING id_cuestionario_funcionario into v_id_cuestionario_funcionario;


                                select uo.id_uo into v_id_uo
                                from orga.tuo_funcionario uo
                                where uo.id_funcionario = v_id_funcionario;

		for v_record in (with recursive uo_mas_subordinados(id_uo_hijo,id_uo_padre) as (
                                       select euo.id_uo_hijo,--id
                                             id_uo_padre---padre
                                       from orga.testructura_uo euo
                                       where euo.id_uo_hijo = v_id_uo and euo.estado_reg = 'activo'
                                       union
                                       select e.id_uo_hijo,
                                              e.id_uo_padre
                                       from orga.testructura_uo e
                                       inner join uo_mas_subordinados s on  s.id_uo_padre = e.id_uo_padre
                                       and e.estado_reg = 'activo'
                                    )select fun.id_funcionario,
                                            fun.desc_funcionario1
                                     from uo_mas_subordinados suo
                                     inner join orga.tuo ou on ou.id_uo = suo.id_uo_hijo
                                     inner join orga.tnivel_organizacional ni on ni.id_nivel_organizacional = ou.id_nivel_organizacional
                                     inner join orga.vfuncionario_cargo fun on fun.id_uo = suo.id_uo_hijo
                                     where (fun.fecha_finalizacion is null or fun.fecha_finalizacion >=  now()/*'22/12/2019'::date */)
                                     and ni.numero_nivel = 4 and fun.id_funcionario <> v_id_funcionario )loop

                                       insert into ssig.tevaluados(id_usuario_reg,
                                                          id_usuario_mod,
                                                          fecha_reg,
                                                          fecha_mod,
                                                          estado_reg,
                                                          id_usuario_ai,
                                                          usuario_ai,
                                                          obs_dba,
                                                          id_cuestionario_funcionario,
                                                          id_funcionario,
                                                          evaluar
                                                        )
                                                        values (
                                                          p_id_usuario,
                                                          null,
                                                          now(),
                                                          null,
                                                          'activo',
                                                          v_parametros._id_usuario_ai,
                                                          v_parametros._nombre_usuario_ai,
                                                          null,
                                                          v_id_cuestionario_funcionario,
                                                          v_record.id_funcionario,
                                                          'si'
                                                        );
                                end loop;

                     end loop;
                end if;

            else
            	raise exception 'Algo Salio Mal';
            end if;


			--Definicion de la respuesta
			v_resp = pxp.f_agrega_clave(v_resp,'mensaje','Cuestionario almacenado(a) con exito (id_cuestionario'||v_id_cuestionario||')');
            v_resp = pxp.f_agrega_clave(v_resp,'id_cuestionario',v_id_cuestionario::varchar);

            --Devuelve la respuesta
            return v_resp;

		end;

	/*********************************
 	#TRANSACCION:  'SSIG_CUE_MOD'
 	#DESCRIPCION:	Modificacion de registros
 	#AUTOR:		mguerra
 	#FECHA:		21-04-2020 08:31:41
	***********************************/

	elsif(p_transaccion='SSIG_CUE_MOD')then

		begin

			--Sentencia de la modificacion
			update ssig.tcuestionario set
			habilitar = v_parametros.habilitar,
			id_usuario_mod = p_id_usuario,
			fecha_mod = now(),
			id_usuario_ai = v_parametros._id_usuario_ai,
			usuario_ai = v_parametros._nombre_usuario_ai,
            id_tipo_evalucion = v_parametros.id_tipo_evalucion
			where id_cuestionario=v_parametros.id_cuestionario;


            for v_recor_evaluador in ( select cf.id_cuestionario_funcionario
                                      from ssig.tcuestionario_funcionario cf
                                      where cf.id_cuestionario = v_parametros.id_cuestionario)loop

        		  delete from ssig.tevaluados ev
                  where ev.id_cuestionario_funcionario = v_recor_evaluador.id_cuestionario_funcionario;

            end loop;

            	 delete from ssig.tcuestionario_funcionario cf
        		 where cf.id_cuestionario = v_parametros.id_cuestionario;


                 if (v_parametros.id_cuestionario is not null) then

            	select e.tipo
                into v_record_tipo_evaluacion
                from ssig.tencuesta e
                where e.id_encuesta= v_parametros.id_tipo_evalucion;

                if (v_record_tipo_evaluacion.tipo = 'auto_evaluacion' )then

                     va_id_funcionarios := string_to_array(v_parametros.id_funcionarios, ',');

                	 foreach v_id_funcionario in array va_id_funcionarios loop
                                insert into ssig.tcuestionario_funcionario (
                                  id_cuestionario,
                                  id_funcionario,
                                  estado_reg,
                                  fecha_reg,
                                  usuario_ai,
                                  id_usuario_reg,
                                  id_usuario_ai,
                                  fecha_mod,
                                  id_usuario_mod
                                ) values (
                                  v_parametros.id_cuestionario,
                                  v_id_funcionario,
                                  'activo',
                                  now(),
                                  v_parametros._nombre_usuario_ai,
                                  p_id_usuario,
                                  v_parametros._id_usuario_ai,
                                  null,
                                  null
                              ) RETURNING id_cuestionario_funcionario into v_id_cuestionario_funcionario;

                              insert into ssig.tevaluados(id_usuario_reg,
                                                          id_usuario_mod,
                                                          fecha_reg,
                                                          fecha_mod,
                                                          estado_reg,
                                                          id_usuario_ai,
                                                          usuario_ai,
                                                          obs_dba,
                                                          id_cuestionario_funcionario,
                                                          id_funcionario,
                                                          evaluar
                                                        )
                                                        values (
                                                          p_id_usuario,
                                                          null,
                                                          now(),
                                                          null,
                                                          'activo',
                                                          v_parametros._id_usuario_ai,
                                                          v_parametros._nombre_usuario_ai,
                                                          null,
                                                          v_id_cuestionario_funcionario,
                                                          v_id_funcionario,
                                                          'si'
                                                        );

                     end loop;
                end if;

               if (v_record_tipo_evaluacion.tipo = 'inferior') then
                           	        --raise exception '%',v_parametros.id_cuestionario;

               	va_id_funcionarios := string_to_array(v_parametros.id_funcionarios, ',');
                     --obtener datos de funcionario
                      foreach v_id_funcionario in array va_id_funcionarios loop

                        insert into ssig.tcuestionario_funcionario (
                                  id_cuestionario,
                                  id_funcionario,
                                  estado_reg,
                                  fecha_reg,
                                  usuario_ai,
                                  id_usuario_reg,
                                  id_usuario_ai,
                                  fecha_mod,
                                  id_usuario_mod
                                ) values (
                                  v_parametros.id_cuestionario,
                                  v_id_funcionario,
                                  'activo',
                                  now(),
                                  v_parametros._nombre_usuario_ai,
                                  p_id_usuario,
                                  v_parametros._id_usuario_ai,
                                  null,
                                  null
                              ) RETURNING id_cuestionario_funcionario into v_id_cuestionario_funcionario;


                                select uo.id_uo into v_id_uo
                                from orga.tuo_funcionario uo
                                where uo.id_funcionario = v_id_funcionario;


                      			for v_record in ( with hijo as (select euo.id_uo_hijo,--id
                                                                 euo.id_uo_padre---padre
                                                           from orga.testructura_uo euo
                                                           where euo.id_uo_hijo = v_id_uo and euo.estado_reg = 'activo')
                                                select  orr.id_uo_hijo,
                                                        orr.id_uo_padre,
                                                        fun.id_funcionario,
                                                        fun.desc_funcionario1,
                                                        uo.nombre_unidad,
                                                        ni.numero_nivel
                                                from hijo hr
                                                inner join orga.testructura_uo orr on orr.id_uo_hijo = hr.id_uo_padre
                                                inner join orga.tuo uo on uo.id_uo = hr.id_uo_padre
                                                inner join orga.vfuncionario_cargo fun on fun.id_uo = orr.id_uo_hijo
                                                inner join orga.tnivel_organizacional ni on ni.id_nivel_organizacional = uo.id_nivel_organizacional
                                                where (fun.fecha_finalizacion is null or fun.fecha_finalizacion >= now()))loop

                                       insert into ssig.tevaluados(id_usuario_reg,
                                                          id_usuario_mod,
                                                          fecha_reg,
                                                          fecha_mod,
                                                          estado_reg,
                                                          id_usuario_ai,
                                                          usuario_ai,
                                                          obs_dba,
                                                          id_cuestionario_funcionario,
                                                          id_funcionario,
                                                          evaluar
                                                        )
                                                        values (
                                                          p_id_usuario,
                                                          null,
                                                          now(),
                                                          null,
                                                          'activo',
                                                          v_parametros._id_usuario_ai,
                                                          v_parametros._nombre_usuario_ai,
                                                          null,
                                                          v_id_cuestionario_funcionario,
                                                          v_record.id_funcionario,
                                                          'si'
                                                        );
                                end loop;

                     end loop;

                	end if;
                if (v_record_tipo_evaluacion.tipo = 'superior')then

                	va_id_funcionarios := string_to_array(v_parametros.id_funcionarios, ',');
                     --obtener datos de funcionario
                      foreach v_id_funcionario in array va_id_funcionarios loop

                        insert into ssig.tcuestionario_funcionario (
                                  id_cuestionario,
                                  id_funcionario,
                                  estado_reg,
                                  fecha_reg,
                                  usuario_ai,
                                  id_usuario_reg,
                                  id_usuario_ai,
                                  fecha_mod,
                                  id_usuario_mod
                                ) values (
                                  v_parametros.id_cuestionario,
                                  v_id_funcionario,
                                  'activo',
                                  now(),
                                  v_parametros._nombre_usuario_ai,
                                  p_id_usuario,
                                  v_parametros._id_usuario_ai,
                                  null,
                                  null
                              ) RETURNING id_cuestionario_funcionario into v_id_cuestionario_funcionario;


                                select uo.id_uo into v_id_uo
                                from orga.tuo_funcionario uo
                                where uo.id_funcionario = v_id_funcionario;


                      			for v_record in (with recursive uo_mas_subordinados(id_uo_hijo,id_uo_padre) as (
                                       select euo.id_uo_hijo,--id
                                             id_uo_padre---padre
                                       from orga.testructura_uo euo
                                       where euo.id_uo_hijo = v_id_uo and euo.estado_reg = 'activo'
                                       union
                                       select e.id_uo_hijo,
                                              e.id_uo_padre
                                       from orga.testructura_uo e
                                       inner join uo_mas_subordinados s on s.id_uo_hijo = e.id_uo_padre
                                       and e.estado_reg = 'activo'
                                    )select fun.id_funcionario,
                                            fun.desc_funcionario1
                                     from uo_mas_subordinados suo
                                     inner join orga.tuo ou on ou.id_uo = suo.id_uo_hijo
                                     inner join orga.vfuncionario_cargo fun on fun.id_uo = suo.id_uo_hijo
                                     inner join orga.tnivel_organizacional ni on ni.id_nivel_organizacional = ou.id_nivel_organizacional
                                     where (fun.fecha_finalizacion is null or fun.fecha_finalizacion >= now()::date /*fun.fecha_finalizacion >= now()::date*/)
                                     and ni.numero_nivel = 4 and fun.id_funcionario <> v_id_funcionario)loop

                                       insert into ssig.tevaluados(id_usuario_reg,
                                                          id_usuario_mod,
                                                          fecha_reg,
                                                          fecha_mod,
                                                          estado_reg,
                                                          id_usuario_ai,
                                                          usuario_ai,
                                                          obs_dba,
                                                          id_cuestionario_funcionario,
                                                          id_funcionario,
                                                          evaluar
                                                        )
                                                        values (
                                                          p_id_usuario,
                                                          null,
                                                          now(),
                                                          null,
                                                          'activo',
                                                          v_parametros._id_usuario_ai,
                                                          v_parametros._nombre_usuario_ai,
                                                          null,
                                                          v_id_cuestionario_funcionario,
                                                          v_record.id_funcionario,
                                                          'si'
                                                        );
                                end loop;

                     end loop;

                end if;

                if (v_record_tipo_evaluacion.tipo = 'medio')then

                va_id_funcionarios := string_to_array(v_parametros.id_funcionarios, ',');
                     --obtener datos de funcionario
                      foreach v_id_funcionario in array va_id_funcionarios loop

                        insert into ssig.tcuestionario_funcionario (
                                  id_cuestionario,
                                  id_funcionario,
                                  estado_reg,
                                  fecha_reg,
                                  usuario_ai,
                                  id_usuario_reg,
                                  id_usuario_ai,
                                  fecha_mod,
                                  id_usuario_mod
                                ) values (
                                  v_parametros.id_cuestionario,
                                  v_id_funcionario,
                                  'activo',
                                  now(),
                                  v_parametros._nombre_usuario_ai,
                                  p_id_usuario,
                                  v_parametros._id_usuario_ai,
                                  null,
                                  null
                              ) RETURNING id_cuestionario_funcionario into v_id_cuestionario_funcionario;


                                select uo.id_uo into v_id_uo
                                from orga.tuo_funcionario uo
                                where uo.id_funcionario = v_id_funcionario;

                      			for v_record in (with recursive uo_mas_subordinados(id_uo_hijo,id_uo_padre) as (
                                       select euo.id_uo_hijo,--id
                                             id_uo_padre---padre
                                       from orga.testructura_uo euo
                                       where euo.id_uo_hijo = v_id_uo and euo.estado_reg = 'activo'
                                       union
                                       select e.id_uo_hijo,
                                              e.id_uo_padre
                                       from orga.testructura_uo e
                                       inner join uo_mas_subordinados s on  s.id_uo_padre = e.id_uo_padre
                                       and e.estado_reg = 'activo'
                                    )select fun.id_funcionario,
                                            fun.desc_funcionario1
                                     from uo_mas_subordinados suo
                                     inner join orga.tuo ou on ou.id_uo = suo.id_uo_hijo
                                     inner join orga.tnivel_organizacional ni on ni.id_nivel_organizacional = ou.id_nivel_organizacional
                                     inner join orga.vfuncionario_cargo fun on fun.id_uo = suo.id_uo_hijo
                                     where (fun.fecha_finalizacion is null or fun.fecha_finalizacion >=  now()/*'22/12/2019'::date */)
                                     and ni.numero_nivel = 4 and fun.id_funcionario <> v_id_funcionario )loop

                                       insert into ssig.tevaluados(id_usuario_reg,
                                                          id_usuario_mod,
                                                          fecha_reg,
                                                          fecha_mod,
                                                          estado_reg,
                                                          id_usuario_ai,
                                                          usuario_ai,
                                                          obs_dba,
                                                          id_cuestionario_funcionario,
                                                          id_funcionario,
                                                          evaluar
                                                        )
                                                        values (
                                                          p_id_usuario,
                                                          null,
                                                          now(),
                                                          null,
                                                          'activo',
                                                          v_parametros._id_usuario_ai,
                                                          v_parametros._nombre_usuario_ai,
                                                          null,
                                                          v_id_cuestionario_funcionario,
                                                          v_record.id_funcionario,
                                                          'si'
                                                        );
                                end loop;

                     end loop;
                end if;

            else
            	raise exception 'Algo Salio Mal';
            end if;




			--Definicion de la respuesta
            v_resp = pxp.f_agrega_clave(v_resp,'mensaje','Cuestionario modificado(a)');
            v_resp = pxp.f_agrega_clave(v_resp,'id_cuestionario',v_parametros.id_cuestionario::varchar);

            --Devuelve la respuesta
            return v_resp;

		end;

	/*********************************
 	#TRANSACCION:  'SSIG_CUE_ELI'
 	#DESCRIPCION:	Eliminacion de registros
 	#AUTOR:		mguerra
 	#FECHA:		21-04-2020 08:31:41
	***********************************/

	elsif(p_transaccion='SSIG_CUE_ELI')then

		begin
			--Sentencia de la eliminacion
			delete from ssig.tcuestionario c
            where c.id_cuestionario=v_parametros.id_cuestionario and c.estado='borrador';

            --Definicion de la respuesta
            v_resp = pxp.f_agrega_clave(v_resp,'mensaje','Cuestionario eliminado(a)');
            v_resp = pxp.f_agrega_clave(v_resp,'id_cuestionario',v_parametros.id_cuestionario::varchar);

            --Devuelve la respuesta
            return v_resp;

		end;

    /*********************************
 	#TRANSACCION:  'SSIG_ENVCOR_IME'
 	#DESCRIPCION:	envia correo
 	#AUTOR:		admin
 	#FECHA:		26-01-2017 16:26:09
	***********************************/

	ELSIF(p_transaccion='SSIG_ENVCOR_IME')then

    	begin
            FOR item IN(select
                    cuefun.id_cuestionario_funcionario,
                    cuefun.estado_reg,
                    cuefun.id_cuestionario,
                    cuefun.id_funcionario,
                    person.nombre_completo2::varchar AS desc_person,
                    funcio.codigo,
                    (select usu11.id_usuario
                    from ssig.tcuestionario_funcionario cff
                    join ssig.tcuestionario scuu on scuu.id_cuestionario=cff.id_cuestionario
                    join orga.tfuncionario ff on ff.id_funcionario=cff.id_funcionario
                    join segu.vpersona pp on pp.id_persona=ff.id_persona
                    join segu.tusuario usu11 on usu11.id_persona = pp.id_persona
                    where ff.id_funcionario=cuefun.id_funcionario
                    limit 1)::integer as id_usuario,
                    cue.cuestionario

                    from ssig.tcuestionario_funcionario cuefun
                    join ssig.tcuestionario cue on cue.id_cuestionario = cuefun.id_cuestionario
                    inner join segu.tusuario usu1 on usu1.id_usuario = cuefun.id_usuario_reg
                    left join segu.tusuario usu2 on usu2.id_usuario = cuefun.id_usuario_mod
                    join orga.tfuncionario funcio on funcio.id_funcionario=cuefun.id_funcionario
                    join segu.vpersona person ON person.id_persona=funcio.id_persona
                    where cuefun.id_cuestionario = v_parametros.id_cuestionario
            )LOOP

                select email_empresa
                into v_correo
                from orga.tfuncionario
                where id_funcionario = item.id_funcionario;


                v_templa = '<!--[if gte mso 9]>

                            <v:image xmlns:v="urn:schemas-microsoft-com:vml" id="theImage"
                                     style=''behavior: url(#default#VML); display:inline-block; position:absolute; height:300px; width:600px; top:0; left:0; border:0; z-index:1;''
                                     src="https://drive.google.com/open?id=1UMblOqnm0BBTrfQPvQADRvq2yW6Prswz"/>

                            <v:shape xmlns:v="urn:schemas-microsoft-com:vml" id="theText"
                                     style=''behavior: url(#default#VML); display:inline-block; position:absolute; height:300px; width:600px; top:-5; left:-10; border:0; z-index:2;''>

                            <![endif]-->


                            <style>
                                .content-texto {
                                    margin-top: 190px;
                                    margin-left: 150px;
                                    font-size: 9pt;
                                    text-align: justify;
                                    margin-right: 9px;
                                    width: 400px;
                                }
                            </style>
                            <div>
                                <!--[if gte mso 9]>
                                <table height="349px" role="presentation" border="0" cellpadding="0" cellspacing="0"
                                       background="https://qcml8q.ch.files.1drv.com/y4mkxOmfvaVIcmmalOilP9UW4zDECfTkxm4BTZSJB7xbEqUqIYOdDukVR9_t127e4pE7ITpIln7_UwOy7KiTuPkX3oh1bjYa_wp9N_8TWqTs-M9cKbfELVUW6yCgU6A_4E00GH-iF_NRJpK7YlGqOIonpLb6Lc4d1uj9hLNVx1YiaeuQZ9XzjccYvz7sB-4wfg2?width=602&height=348&cropmode=none">
                                <![endif]-->
                                <table height="349px" role="presentation" border="0" cellpadding="0" cellspacing="0"
                                       background="/var/www/html/valvaradoetr/sis_segintegralgestion/vista/ImagenesIndicador/fondo-presentacion-01.jpg"
                                       style="background-image:url(https://qcml8q.ch.files.1drv.com/y4mkxOmfvaVIcmmalOilP9UW4zDECfTkxm4BTZSJB7xbEqUqIYOdDukVR9_t127e4pE7ITpIln7_UwOy7KiTuPkX3oh1bjYa_wp9N_8TWqTs-M9cKbfELVUW6yCgU6A_4E00GH-iF_NRJpK7YlGqOIonpLb6Lc4d1uj9hLNVx1YiaeuQZ9XzjccYvz7sB-4wfg2?width=602&height=348&cropmode=none);background-position: center top; background-repeat: no-repeat; background-color: #130d09;">
                                    <tbody>
                                    <tr>
                                        <td height="349px" width="400px">
                                            <div class="content-texto"
                                                 style=" margin-top: 190px;margin-left: 150px;font-size: 9pt;text-align: justify;margin-right: 9px;">
                                                <b>Estimados y estimadas</b> <br>
                                                <br>
                                                Les damos la bienvenida a la Fase Piloto del nuevo Sistema de Evaluación de Desempeño 360 de ENDE
                                                Transmisión.
                                                Agradecemos de antemano su valiosa participación en este proceso de mejora
                                                (En caso de que requiera, puede acceder al siguiente <a
                                                        href="https://www.youtube.com/watch?v=IQgIstt6uZU&feature=youtu.be"
                                                        style=" padding-left: 7px;">Video Tutorial</a>).
                                                <p> La evaluación se encuentra en el <b>ENDESIS</b> en el siguiente enlace:</p>
                                            </div>
                                        </td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>


                            <!--[if gte mso 9]>

                            </v:shape>

                            <![endif]-->

                ';

                INSERT INTO param.talarma(
                  acceso_directo,
                  id_funcionario,
                  fecha,
                  estado_reg,
                  descripcion,
                  id_usuario_reg,
                  fecha_reg,
                  id_usuario_mod,
                  fecha_mod,
                  tipo,
                  obs,
                  clase,
                  --titulo,
                  parametros,
                  id_usuario,
                  titulo_correo,
                  correos,
                  documentos,
                  id_proceso_wf,
                  id_estado_wf,
                  id_plantilla_correo,
                  estado_envio,
                  sw_correo,
                  pendiente
                  ) values(
                  '../../../sis_segintegralgestion/vista/pregunta/Respuesta.php', --acceso_directo
                  item.id_funcionario::INTEGER,  --par_id_funcionario
                  now(), --par_fecha
                  'activo',
                  v_templa,
                  1, --par_id_usuario admin
                  now(),
                  null,
                  null,
                  'notificacion',--par_tipo
                  ''::varchar, --par_obs
                  'Respuesta',--par_clase
                 -- 'Evaluacion',--par_titulo
                  '',--par_parametros
                  item.id_usuario::INTEGER,--par_id_usuario_alarma
                  'Evaluación de Desempeño 360',--par_titulo correo
                  v_correo,--par_correos
                  '',--par_documentos
                  NULL,--p_id_proceso_wf
                  NULL,--p_id_estado_wf
                  NULL,--p_id_plantilla_correo
                  'exito'::character varying, --v_estado_envio
                  0,
                  'no'
                )RETURNING id_alarma into v_id_correo;

				--#16
				v_acceso_directo:=  v_templa ||  replace(v_parametros.url_alarma,'ID_ALARMA',v_id_correo::varchar);

                update param.talarma
                set descripcion=v_acceso_directo
                where id_alarma=v_id_correo;

            END LOOP;

    		update ssig.tcuestionario
            set estado='enviado'
            where id_cuestionario=v_parametros.id_cuestionario;



            --Definicion de la respuesta
            v_resp = pxp.f_agrega_clave(v_resp,'mensaje','Cuestionario procesado');
    		return v_resp;

		end;

    /*********************************
 	#TRANSACCION:  'SSIG_SAVCUE_INS'
 	#DESCRIPCION:	Insercion de registro de cuestionario
 	#AUTOR:		manu
 	#FECHA:		21-11-2017 00:51:02
	***********************************/

	ELSIF(p_transaccion='SSIG_SAVCUE_INS')then

    	begin

            v_id_pregunta_texto:=NULL;
            v_id_pregunta:=NULL;

            IF(v_parametros.tipo='Selección')THEN
                    if(v_parametros.respuesta='Excelente')then
                         v_id_pregunta:='1';
                         ELSE
                         if(v_parametros.respuesta='Destacable')then
                              v_id_pregunta:='2';
                              else
                              if(v_parametros.respuesta='Acorde a la posición')then
                                  v_id_pregunta:='3';
                                  else
                                  if(v_parametros.respuesta='En desarrollo')then
                                      v_id_pregunta:='4';
                                      else
                                      if(v_parametros.respuesta='A desarrollo')then
                                      		v_id_pregunta:='5';
                                      end if;
                                  end if;
                              end if;
                         end if;
                    end if;
            ELSE
              v_id_pregunta_texto:=v_parametros.respuesta;
            END IF;

            --Insertamos
            IF(select count(id_cuestionario)
            	from ssig.trespuestas
                where id_cuestionario = v_parametros.id_cuestionario::INTEGER AND
                id_func_evaluado =v_parametros.id_funcionario and
                id_funcionario=(SELECT funcio.id_funcionario
                                FROM orga.tfuncionario funcio
                                JOIN segu.vpersona person ON funcio.id_persona = person.id_persona
                                JOIN segu.tusuario usu ON person.id_persona=usu.id_persona
                                WHERE usu.id_usuario= p_id_usuario) and
                id_pregunta=v_parametros.id_pregunta::INTEGER)THEN
                      --por if no hacer nada
                  ELSE
                      insert into ssig.trespuestas(
                      id_pregunta,
                      respuesta,
                      id_funcionario,
                      id_cuestionario,
                      id_categoria,
                      respuesta_texto,
                      id_func_evaluado
                      ) values(
                      v_parametros.id_pregunta,
                      v_id_pregunta::INTEGER,
                      (SELECT funcio.id_funcionario
                      FROM orga.tfuncionario funcio
                      JOIN segu.vpersona person ON funcio.id_persona = person.id_persona
                      JOIN segu.tusuario usu ON person.id_persona=usu.id_persona
                      WHERE usu.id_usuario= p_id_usuario),
                      v_parametros.id_cuestionario,
                      v_parametros.id_categoria,
                      v_id_pregunta_texto::VARCHAR,

                      v_parametros.id_funcionario

                      )RETURNING id_respuestas into v_id_cuestionario;
            END IF;

			--Definicion de la respuesta
			v_resp = pxp.f_agrega_clave(v_resp,'mensaje','Categoria almacenado(a) con exito (id_categoria'||v_parametros.id_cuestionario||')');
            v_resp = pxp.f_agrega_clave(v_resp,'id_categoria',v_id_cuestionario::varchar);

            --Devuelve la respuesta
            return v_resp;

		end;


    /*********************************
 	#TRANSACCION:  'SSIG_FINCUE_IME'
 	#DESCRIPCION:	envia correo
 	#AUTOR:		admin
 	#FECHA:		26-01-2017 16:26:09
	***********************************/

	ELSIF(p_transaccion='SSIG_FINCUE_IME')then

    	begin
		  v_id_evaluador = null;
          for v_record_datos in ( select 	cu.id_funcionario as id_evaluador,
        									ev.id_funcionario as id_evaluado
                                from ssig.tcuestionario_funcionario cu
                                inner join ssig.tevaluados ev on ev.id_cuestionario_funcionario = cu.id_cuestionario_funcionario
                                where cu.id_cuestionario = v_parametros.id_cuestionario and cu.id_funcionario = (SELECT funcio.id_funcionario
                                                                                                                FROM orga.tfuncionario funcio
                                                                                                                JOIN segu.vpersona person ON funcio.id_persona = person.id_persona
                                                                                                                JOIN segu.tusuario usu ON person.id_persona=usu.id_persona
                                                                                                                WHERE usu.id_usuario= p_id_usuario))loop

        			if not exists ( select distinct re.id_func_evaluado
                                from ssig.trespuestas re
                                where re.id_cuestionario = v_parametros.id_cuestionario and re.id_func_evaluado = v_record_datos.id_evaluado
                                     and re.id_funcionario = v_record_datos.id_evaluador)then

                                     v_id_evaluador = array_append(v_id_evaluador, v_record_datos.id_evaluado);
                    end if;

        	end loop;

            if array_length(v_id_evaluador, 1) != 0 then

            	select pxp.list(initcap(fu.desc_funcionario1)) into v_lista_negra
                from orga.vfuncionario fu
                where fu.id_funcionario = any(v_id_evaluador);

                      raise exception 'Aun le faltan evaluar a: %',v_lista_negra;
            end if;


        	update ssig.tcuestionario_funcionario
            set estado='finalizado',sw_final='si'
            where id_cuestionario=v_parametros.id_cuestionario
            and sw_final='no'
            and estado='proceso'
            and id_funcionario = (SELECT funcio.id_funcionario
                                  FROM orga.tfuncionario funcio
                                  JOIN segu.vpersona person ON funcio.id_persona = person.id_persona
                                  JOIN segu.tusuario usu ON person.id_persona=usu.id_persona
                                  WHERE usu.id_usuario= p_id_usuario);

			v_resp = pxp.f_agrega_clave(v_resp,'mensaje','id_cuestionario'||v_id_cuestionario||')');
            v_resp = pxp.f_agrega_clave(v_resp,'id_cuestionario',v_id_cuestionario::varchar);

            --Devuelve la respuesta
            return v_resp;
        END;

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