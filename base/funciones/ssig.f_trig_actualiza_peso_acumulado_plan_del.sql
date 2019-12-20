CREATE OR REPLACE FUNCTION ssig.f_trig_actualiza_peso_acumulado_plan_del(
)
  RETURNS TRIGGER AS
$body$
DECLARE
  v_peso_acumulado INTEGER;
BEGIN

  IF COALESCE(OLD.nivel, 0) < 3 and COALESCE(OLD.nivel, 0) > 0
  THEN
    -- Calculamos el valor del peso _acumulado de los planes
    v_peso_acumulado = (SELECT coalesce(sum(coalesce(p2.peso, 0)), 0)
                        FROM ssig.tplan p2
                        WHERE p2.id_plan_padre = OLD.id_plan_padre) :: INTEGER;

    -- Actualizamos el valor del porcentaje acumulado
    UPDATE ssig.tplan
    SET peso_acumulado = v_peso_acumulado
    WHERE id_plan = OLD.id_plan_padre;

  END IF;
  RAISE NOTICE '%',OLD;
  RETURN old ;

END;
$body$
LANGUAGE 'plpgsql'
VOLATILE
CALLED ON NULL INPUT
SECURITY INVOKER
COST 100;
