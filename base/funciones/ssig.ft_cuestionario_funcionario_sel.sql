--------------- SQL ---------------

CREATE OR REPLACE FUNCTION ssig.ft_cuestionario_funcionario_sel (
  p_administrador integer,
  p_id_usuario integer,
  p_tabla varchar,
  p_transaccion varchar
)
RETURNS varchar AS
$body$
/**************************************************************************
 SISTEMA:		Seguimiento integral de gestión
 FUNCION: 		ssig.ft_cuestionario_funcionario_sel
 DESCRIPCION:   Funcion que devuelve conjuntos de registros de las consultas relacionadas con la tabla 'ssig.tcuestionario_funcionario'
 AUTOR: 		 (mguerra)
 FECHA:	        22-04-2020 06:47:37
 COMENTARIOS:	
***************************************************************************
 HISTORIAL DE MODIFICACIONES:
#ISSUE				FECHA				AUTOR				DESCRIPCION
 #0				22-04-2020 06:47:37								Funcion que devuelve conjuntos de registros de las consultas relacionadas con la tabla 'ssig.tcuestionario_funcionario'	
 #
 ***************************************************************************/

DECLARE

	v_consulta    		varchar;
	v_parametros  		record;
	v_nombre_funcion   	text;
	v_resp				varchar;
    v_filtro			varchar;
    v_id_funcionario	integer;			    
BEGIN

	v_nombre_funcion = 'ssig.ft_cuestionario_funcionario_sel';
    v_parametros = pxp.f_get_record(p_tabla);

	/*********************************    
 	#TRANSACCION:  'SSIG_CUEFUN_SEL'
 	#DESCRIPCION:	Consulta de datos
 	#AUTOR:		mguerra	
 	#FECHA:		22-04-2020 06:47:37
	***********************************/

	if(p_transaccion='SSIG_CUEFUN_SEL')then
     				
    	begin        	 
        	
    		--Sentencia de la consulta
			v_consulta:='select
						cuefun.id_cuestionario_funcionario,
						cuefun.estado_reg,						
						cuefun.id_cuestionario,
						cuefun.id_funcionario,
						cuefun.id_usuario_reg,
						cuefun.fecha_reg,
						cuefun.id_usuario_ai,
						cuefun.usuario_ai,
						cuefun.id_usuario_mod,
						cuefun.fecha_mod,
						usu1.cuenta as usr_reg,
						usu2.cuenta as usr_mod,
                        person.nombre_completo2::varchar AS desc_person,
                        funcio.codigo,
                        cuefun.sw_final                        	
						from ssig.tcuestionario_funcionario cuefun
                        join ssig.tcuestionario cue on cue.id_cuestionario = cuefun.id_cuestionario
						inner join segu.tusuario usu1 on usu1.id_usuario = cuefun.id_usuario_reg
						left join segu.tusuario usu2 on usu2.id_usuario = cuefun.id_usuario_mod
                        join orga.tfuncionario funcio on funcio.id_funcionario=cuefun.id_funcionario
                        join segu.vpersona person ON person.id_persona=funcio.id_persona
				        where  ';
			
			--Definicion de la respuesta
			v_consulta:=v_consulta||v_parametros.filtro;
			v_consulta:=v_consulta||' order by ' ||v_parametros.ordenacion|| ' ' || v_parametros.dir_ordenacion || ' limit ' || v_parametros.cantidad || ' offset ' || v_parametros.puntero;
			raise notice  '%',v_consulta;
			--Devuelve la respuesta
			return v_consulta;
						
		end;

	/*********************************    
 	#TRANSACCION:  'SSIG_CUEFUN_CONT'
 	#DESCRIPCION:	Conteo de registros
 	#AUTOR:		mguerra	
 	#FECHA:		22-04-2020 06:47:37
	***********************************/

	elsif(p_transaccion='SSIG_CUEFUN_CONT')then

		begin
                
			--Sentencia de la consulta de conteo de registros
			v_consulta:='select count(id_cuestionario_funcionario)
                        from ssig.tcuestionario_funcionario cuefun
                        join ssig.tcuestionario cue on cue.id_cuestionario = cuefun.id_cuestionario
						inner join segu.tusuario usu1 on usu1.id_usuario = cuefun.id_usuario_reg
						left join segu.tusuario usu2 on usu2.id_usuario = cuefun.id_usuario_mod
                        join orga.tfuncionario funcio on funcio.id_funcionario=cuefun.id_funcionario
                        join segu.vpersona person ON person.id_persona=funcio.id_persona
					    where  ';
			
			--Definicion de la respuesta		    
			v_consulta:=v_consulta||v_parametros.filtro;

			--Devuelve la respuesta
			return v_consulta;

		end;
	
    
    /*********************************    
 	#TRANSACCION:  'SSIG_LIST_SEL'
 	#DESCRIPCION:	Consulta de datos
 	#AUTOR:		mguerra	
 	#FECHA:		22-04-2020 06:47:37
	***********************************/

	elsif(p_transaccion='SSIG_LIST_SEL')then
     				
    	begin

        	IF v_parametros.pes_estado in ('proceso') THEN
				v_filtro = 'cuefun.estado=''proceso'' and (cuefun.sw_final=''no'' or cuefun.sw_final=null) and cue.estado =''enviado'' and';
            END IF;
            IF v_parametros.pes_estado in ('finalizado') THEN
            	v_filtro = 'cuefun.estado=''finalizado'' and cuefun.sw_final=''si'' and';
            END IF;
                              	
           	IF p_administrador = 1 THEN
            	v_filtro =v_filtro|| ' 0=0 ';
            ELSE
            	SELECT funcio.id_funcionario
                INTO v_id_funcionario
                FROM orga.tfuncionario funcio
                JOIN segu.vpersona person ON funcio.id_persona = person.id_persona
                JOIN segu.tusuario usu ON person.id_persona=usu.id_persona
                WHERE usu.id_usuario= p_id_usuario;
            	v_filtro = v_filtro||' (funcio.id_funcionario = ' ||v_id_funcionario || ' ) ';    
            END IF;
              
    		--Sentencia de la consulta
			v_consulta:='select
                        cuefun.id_cuestionario_funcionario,
                        cuefun.estado_reg,						
                        cuefun.id_cuestionario,
                        cuefun.id_funcionario,
                        cuefun.id_usuario_reg,
                        cue.cuestionario::varchar,
                        cuefun.fecha_reg,
                        cuefun.id_usuario_ai,
                        cuefun.usuario_ai,
                        cuefun.fecha_mod,
                        person.nombre_completo2::varchar AS desc_person,
                        funcio.codigo,
                        usu.cuenta::varchar,
                        cuefun.sw_final::varchar	
                        from ssig.tcuestionario_funcionario cuefun
                        join ssig.tcuestionario cue on cue.id_cuestionario = cuefun.id_cuestionario
                        join orga.tfuncionario funcio on funcio.id_funcionario=cuefun.id_funcionario
                        join segu.vpersona person ON person.id_persona=funcio.id_persona
                        join segu.tusuario usu on usu.id_persona  = person.id_persona
				        where  '|| v_filtro ||' and ';

			--Definicion de la respuesta
			v_consulta:=v_consulta||v_parametros.filtro;
			v_consulta:=v_consulta||' order by ' ||v_parametros.ordenacion|| ' ' || v_parametros.dir_ordenacion || ' limit ' || v_parametros.cantidad || ' offset ' || v_parametros.puntero;
			raise notice  '%',v_consulta;
			--Devuelve la respuesta
			return v_consulta;
						
		end;

	/*********************************    
 	#TRANSACCION:  'SSIG_LIST_CONT'
 	#DESCRIPCION:	Conteo de registros
 	#AUTOR:		mguerra	
 	#FECHA:		22-04-2020 06:47:37
	***********************************/

	elsif(p_transaccion='SSIG_LIST_CONT')then

		begin			
			IF v_parametros.pes_estado in ('proceso') THEN
				v_filtro = 'cuefun.estado=''proceso'' and (cuefun.sw_final=''no'' or cuefun.sw_final=null) and cue.estado =''enviado'' and';
            END IF;
            IF v_parametros.pes_estado in ('finalizado') THEN
            	v_filtro = 'cuefun.estado=''finalizado'' and cuefun.sw_final=''si'' and';
            END IF;
            
        	 	IF p_administrador = 1 THEN
            	v_filtro =v_filtro|| ' 0=0 ';
            ELSE
            	SELECT funcio.id_funcionario
                INTO v_id_funcionario
                FROM orga.tfuncionario funcio
                JOIN segu.vpersona person ON funcio.id_persona = person.id_persona
                JOIN segu.tusuario usu ON person.id_persona=usu.id_persona
                WHERE usu.id_usuario= p_id_usuario;
            	v_filtro = v_filtro||' (funcio.id_funcionario = ' ||v_id_funcionario || ' ) ';    
            END IF;
                    
    		--Sentencia de la consulta
			v_consulta:='select count(id_cuestionario_funcionario)
                        from ssig.tcuestionario_funcionario cuefun
                        join ssig.tcuestionario cue on cue.id_cuestionario = cuefun.id_cuestionario
                        join orga.tfuncionario funcio on funcio.id_funcionario=cuefun.id_funcionario
                        join segu.vpersona person ON person.id_persona=funcio.id_persona
                        join segu.tusuario usu on usu.id_persona  = person.id_persona
				        where '|| v_filtro ||' and ';
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
PARALLEL UNSAFE
COST 100;