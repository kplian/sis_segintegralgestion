CREATE OR REPLACE FUNCTION ssig.f_trig_actualiza_peso_acumulado_linea_ins(
)
  RETURNS TRIGGER AS
$body$
DECLARE
  v_peso_acumulado INTEGER;
BEGIN

  IF COALESCE(NEW.nivel, 0) > 0 and COALESCE(NEW.nivel, 0) < 3
  THEN
    -- Calculamos el valor del peso _acumulado de los planes
    v_peso_acumulado = (SELECT coalesce(sum(coalesce(p2.peso, 0)), 0)
                        FROM ssig.tlinea p2
                        WHERE p2.id_linea_padre = NEW.id_linea_padre) :: INTEGER;

    -- Actualizamos el valor del porcentaje acumulado
    UPDATE ssig.tlinea
    SET peso_acumulado = v_peso_acumulado
    WHERE id_linea = NEW.id_linea_padre;
  END IF;

  RETURN new;

END;
$body$
LANGUAGE 'plpgsql'
VOLATILE
CALLED ON NULL INPUT
SECURITY INVOKER
COST 100;


