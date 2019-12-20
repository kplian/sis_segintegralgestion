CREATE OR REPLACE FUNCTION ssig.f_trig_actualiza_peso_acumulado_agrupador_ins (
)
RETURNS trigger AS
$body$
DECLARE
  v_peso_acumulado INTEGER;
BEGIN

  IF COALESCE(NEW.nivel, 0) > 0 and COALESCE(NEW.nivel, 0) < 3
  THEN
    -- Calculamos el valor del peso _acumulado de los planes
    v_peso_acumulado = (SELECT coalesce(sum(coalesce(p2.peso, 0)), 0)
                        FROM ssig.tagrupador p2
                        WHERE p2.id_agrupador_padre = NEW.id_agrupador_padre) :: INTEGER;

    -- Actualizamos el valor del porcentaje acumulado
    UPDATE ssig.tagrupador
    SET peso_acumulado = v_peso_acumulado
    WHERE id_agrupador = NEW.id_agrupador_padre;
  END IF;

  RAISE NOTICE '%', new;
  RETURN new;

END;
$body$
LANGUAGE 'plpgsql'
VOLATILE
CALLED ON NULL INPUT
SECURITY INVOKER
COST 100;