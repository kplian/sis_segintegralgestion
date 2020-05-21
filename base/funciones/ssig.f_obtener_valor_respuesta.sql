CREATE OR REPLACE FUNCTION ssig.f_obtener_valor_respuesta (
  p_respuesta integer,
  p_id_catalogo integer
)
RETURNS numeric AS
$body$
DECLARE
   v_resp               varchar;
   v_nombre_funcion     text;
   v_resultado			numeric;
   v_valor				numeric;
   v_peso				numeric;

BEGIN

  	v_nombre_funcion = 'ssig.f_obtener_valor_respuesta';

    v_valor = 0;

    select enc.peso_categoria into v_peso
    from ssig.tencuesta enc
    where enc.id_encuesta = p_id_catalogo;


    if (p_respuesta = 1 )then

    	v_valor = 1;

    elsif (p_respuesta = 2 )then

    	v_valor = 0.8;

	elsif (p_respuesta = 3 )then

		v_valor = 0.5;

	elsif (p_respuesta = 4 )then

    	v_valor = 0.3;

    elsif (p_respuesta = 5 )then

    	v_valor = 0;

    end if;

    return v_peso * v_valor;
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

ALTER FUNCTION ssig.f_obtener_valor_respuesta (p_respuesta integer, p_id_catalogo integer)
  OWNER TO dbaamamani;