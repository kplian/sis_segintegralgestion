<?php

/**
 * @package pXP
 * @file gen-MODPlan.php
 * @author  (admin)
 * @date 11-04-2017 14:31:46
 * @description Clase que envia los parametros requeridos a la Base de datos para la ejecucion de las funciones, y que recibe la respuesta del resultado de la ejecucion de las mismas
 */
class MODPlan extends MODbase
{

    function __construct(CTParametro $pParam)
    {
        parent::__construct($pParam);
    }

    function listarPlan()
    {
        //Definicion de variables para ejecucion del procedimientp
        $this->procedimiento = 'ssig.ft_plan_sel';
        $this->transaccion = 'SSIG_SSIGPLAN_SEL';
        $this->tipo_procedimiento = 'SEL';//tipo de transaccion

        //Definicion de la lista del resultado del query
        $this->captura('id_plan', 'int4');
        $this->captura('id_plan_padre', 'int4');
        $this->captura('id_gestion', 'int4');
        $this->captura('nivel', 'int4');
        $this->captura('nombre_plan', 'varchar');
        $this->captura('peso', 'int4');
        $this->captura('aprobado', 'bit');
        $this->captura('estado_reg', 'varchar');
        $this->captura('id_usuario_ai', 'int4');
        $this->captura('fecha_reg', 'timestamp');
        $this->captura('usuario_ai', 'varchar');
        $this->captura('id_usuario_reg', 'int4');
        $this->captura('fecha_mod', 'timestamp');
        $this->captura('id_usuario_mod', 'int4');
        $this->captura('usr_reg', 'varchar');
        $this->captura('usr_mod', 'varchar');

        //Ejecuta la instruccion
        $this->armarConsulta();
        $this->ejecutarConsulta();

        //Devuelve la respuesta
        return $this->respuesta;
    }

    function listarPlanArb()
    {
        $this->procedimiento = 'ssig.ft_plan_sel';
        $this->setCount(false);
        $this->transaccion = 'SSIG_PLAN_SEL_ARB';
        $this->tipo_procedimiento = 'SEL';

        $id_padre = $this->objParam->getParametro('id_padre');

        $this->setParametro('id_padre', 'id_padre', 'varchar');

        $this->setParametro('id_gestion', 'id_gestion', 'integer');

        //Definicion de la lista del resultado del query
        $this->captura('id_plan', 'int4');
        $this->captura('id_plan_padre', 'int4');
        $this->captura('id_gestion', 'int4');
        $this->captura('nivel', 'int4');
        $this->captura('nombre_plan', 'varchar');
        $this->captura('peso', 'int4');
        $this->captura('aprobado', 'bit');
        $this->captura('tipo_nodo', 'varchar');
        $this->captura('porcentaje_acum', 'int4');
        $this->captura('porcentaje_rest', 'int4');

        $this->captura('id_funcionarios', 'varchar');
        $this->captura('funcionarios', 'varchar');
        $this->captura('nombre_plan_padre', 'varchar');
        $this->captura('porcentaje_acumulado', 'varchar');
        $this->captura('porcentaje_restante', 'varchar');
		
		$this->captura('porcentaje_acumulado_aux', 'varchar');
		$this->captura('completado', 'integer');
		
		
		

        $this->armarConsulta();
        $this->ejecutarConsulta();

        return $this->respuesta;
    }

    function insertarPlan()
    {
        //Definicion de variables para ejecucion del procedimiento
        $this->procedimiento = 'ssig.ft_plan_ime';
        $this->transaccion = 'SSIG_SSIGPLAN_INS';
        $this->tipo_procedimiento = 'IME';

        //Define los parametros para la funcion
        $this->setParametro('id_plan_padre', 'id_plan_padre', 'varchar');
        $this->setParametro('id_gestion', 'id_gestion', 'int4');
        $this->setParametro('nivel', 'nivel', 'int4');
        $this->setParametro('nombre_plan', 'nombre_plan', 'varchar');
        $this->setParametro('peso', 'peso', 'int4');
        $this->setParametro('aprobado', 'aprobado', 'bit');
        $this->setParametro('estado_reg', 'estado_reg', 'varchar');

        $this->setParametro('id_funcionarios', 'id_funcionarios', 'varchar');

        //Ejecuta la instruccion
        $this->armarConsulta();
        $this->ejecutarConsulta();

        //Devuelve la respuesta
        return $this->respuesta;
    }

    function modificarPlan()
    {
        //Definicion de variables para ejecucion del procedimiento
        $this->procedimiento = 'ssig.ft_plan_ime';
        $this->transaccion = 'SSIG_SSIGPLAN_MOD';
        $this->tipo_procedimiento = 'IME';

        //Define los parametros para la funcion
        $this->setParametro('id_plan', 'id_plan', 'int4');
        $this->setParametro('id_plan_padre', 'id_plan_padre', 'varchar');
        $this->setParametro('id_gestion', 'id_gestion', 'int4');
        $this->setParametro('nivel', 'nivel', 'int4');
        $this->setParametro('nombre_plan', 'nombre_plan', 'varchar');
        $this->setParametro('peso', 'peso', 'int4');
        $this->setParametro('aprobado', 'aprobado', 'bit');
        $this->setParametro('estado_reg', 'estado_reg', 'varchar');
        $this->setParametro('id_funcionarios', 'id_funcionarios', 'varchar');


        //Ejecuta la instruccion
        $this->armarConsulta();
        $this->ejecutarConsulta();

        //Devuelve la respuesta
        return $this->respuesta;
    }

    function eliminarPlan()
    {
        //Definicion de variables para ejecucion del procedimiento
        $this->procedimiento = 'ssig.ft_plan_ime';
        $this->transaccion = 'SSIG_SSIGPLAN_ELI';
        $this->tipo_procedimiento = 'IME';

        //Define los parametros para la funcion
        $this->setParametro('id_plan', 'id_plan', 'int4');

        //Ejecuta la instruccion
        $this->armarConsulta();
        $this->ejecutarConsulta();

        //Devuelve la respuesta
        return $this->respuesta;
    }

    function aprobarPlanes()
    {
        //Definicion de variables para ejecucion del procedimiento
        $this->procedimiento = 'ssig.ft_plan_ime';
        $this->transaccion = 'SSIG_SSIGPAPR_MOD';
        $this->tipo_procedimiento = 'IME';

        //Define los parametros para la funcion
        $this->setParametro('id_gestion', 'id_gestion', 'int4');
        $this->setParametro('aprobado', 'aprobado', 'int4');

        //Ejecuta la instruccion
        $this->armarConsulta();
        $this->ejecutarConsulta();

        //Devuelve la respuesta
        return $this->respuesta;
    }

    function listarGestion()
    {
        //Definicion de variables para ejecucion del procedimientp
        $this->procedimiento = 'ssig.f_gestion_sel';
        $this->transaccion = 'SSIG_GES_SEL';
        $this->tipo_procedimiento = 'SEL';//tipo de transaccion

        //Definicion de la lista del resultado del query
        $this->captura('id_gestion', 'int4');
        $this->captura('id_moneda_base', 'int4');
        $this->captura('id_empresa', 'int4');
        $this->captura('estado_reg', 'varchar');
        $this->captura('estado', 'varchar');
        $this->captura('gestion', 'int4');
        $this->captura('fecha_reg', 'timestamp');
        $this->captura('id_usuario_reg', 'int4');
        $this->captura('id_usuario_mod', 'int4');
        $this->captura('fecha_mod', 'timestamp');
        $this->captura('usr_reg', 'varchar');
        $this->captura('usr_mod', 'varchar');
        $this->captura('desc_empresa', 'varchar');
        $this->captura('moneda', 'varchar');
        $this->captura('codigo_moneda', 'varchar');
        $this->captura('tipo', 'varchar');
        $this->captura('existe_plan', 'int4');

        //Ejecuta la instruccion
        $this->armarConsulta();
        $this->ejecutarConsulta();

        //Devuelve la respuesta
        return $this->respuesta;
    }

    function estadoGestion()
    {
        //Definicion de variables para ejecucion del procedimiento
        $this->procedimiento = 'ssig.ft_plan_ime';
        $this->transaccion = 'SSIG_PLAN_ESTGEST';
        $this->tipo_procedimiento = 'IME';

        //Define los parametros para la funcion
        $this->setParametro('id_gestion', 'id_gestion', 'int4');

        //Ejecuta la instruccion
        $this->armarConsulta();
        $this->ejecutarConsulta();

        //Devuelve la respuesta
        return $this->respuesta;
    }
    function reportePlanGlobal(){
        //Definicion de variables para ejecucion del procedimientp
        $this->procedimiento = 'ssig.ft_plan_sel';
        $this->transaccion='SSIG_PLAN_GLOBAL_SEL';
        $this->tipo_procedimiento='SEL';//tipo de transaccion
                
        $this->setCount(false);

        $this->setParametro('id_gestion','id_gestion','int4');
        $this->setParametro('id_plan','id_plan','int4');
        $this->setParametro('nivel','nivel','int4');

        //Definicion de la lista del resultado del query
        $this->captura('id_pglobal_temporal','int4');

        $this->captura('id_plan','int4');
        $this->captura('id_plan_padre','int4');
        $this->captura('id_linea','int4');
        $this->captura('id_linea_padre','int4');
        $this->captura('nivel','int4');
        $this->captura('nivel_1','varchar');
        $this->captura('nivel_2','varchar');
        $this->captura('nivel_3','varchar');
        $this->captura('nivel_4','varchar');
        $this->captura('responsable','varchar');
        $this->captura('peso','int4');

        $this->captura('avance_previsto_ene','numeric');
        $this->captura('avance_real_ene','numeric');
        $this->captura('desviacion_mes_ene','numeric');
        $this->captura('acum_previsto_ene','numeric');
        $this->captura('acum_real_ene','numeric');
        $this->captura('desviacion_acumulada_ene','numeric');

        $this->captura('avance_previsto_feb','numeric');
        $this->captura('avance_real_feb','numeric');
        $this->captura('desviacion_mes_feb','numeric');
        $this->captura('acum_previsto_feb','numeric');
        $this->captura('acum_real_feb','numeric');
        $this->captura('desviacion_acumulada_feb','numeric');

        $this->captura('avance_previsto_mar','numeric');
        $this->captura('avance_real_mar','numeric');
        $this->captura('desviacion_mes_mar','numeric');
        $this->captura('acum_previsto_mar','numeric');
        $this->captura('acum_real_mar','numeric');
        $this->captura('desviacion_acumulada_mar','numeric');

        $this->captura('avance_previsto_abr','numeric');
        $this->captura('avance_real_abr','numeric');
        $this->captura('desviacion_mes_abr','numeric');
        $this->captura('acum_previsto_abr','numeric');
        $this->captura('acum_real_abr','numeric');
        $this->captura('desviacion_acumulada_abr','numeric');

        $this->captura('avance_previsto_may','numeric');
        $this->captura('avance_real_may','numeric');
        $this->captura('desviacion_mes_may','numeric');
        $this->captura('acum_previsto_may','numeric');
        $this->captura('acum_real_may','numeric');
        $this->captura('desviacion_acumulada_may','numeric');

        $this->captura('avance_previsto_jun','numeric');
        $this->captura('avance_real_jun','numeric');
        $this->captura('desviacion_mes_jun','numeric');
        $this->captura('acum_previsto_jun','numeric');
        $this->captura('acum_real_jun','numeric');
        $this->captura('desviacion_acumulada_jun','numeric');

        $this->captura('avance_previsto_jul','numeric');
        $this->captura('avance_real_jul','numeric');
        $this->captura('desviacion_mes_jul','numeric');
        $this->captura('acum_previsto_jul','numeric');
        $this->captura('acum_real_jul','numeric');
        $this->captura('desviacion_acumulada_jul','numeric');

        $this->captura('avance_previsto_ago','numeric');
        $this->captura('avance_real_ago','numeric');
        $this->captura('desviacion_mes_ago','numeric');
        $this->captura('acum_previsto_ago','numeric');
        $this->captura('acum_real_ago','numeric');
        $this->captura('desviacion_acumulada_ago','numeric');

        $this->captura('avance_previsto_sep','numeric');
        $this->captura('avance_real_sep','numeric');
        $this->captura('desviacion_mes_sep','numeric');
        $this->captura('acum_previsto_sep','numeric');
        $this->captura('acum_real_sep','numeric');
        $this->captura('desviacion_acumulada_sep','numeric');

        $this->captura('avance_previsto_oct','numeric');
        $this->captura('avance_real_oct','numeric');
        $this->captura('desviacion_mes_oct','numeric');
        $this->captura('acum_previsto_oct','numeric');
        $this->captura('acum_real_oct','numeric');
        $this->captura('desviacion_acumulada_oct','numeric');

        $this->captura('avance_previsto_nov','numeric');
        $this->captura('avance_real_nov','numeric');
        $this->captura('desviacion_mes_nov','numeric');
        $this->captura('acum_previsto_nov','numeric');
        $this->captura('acum_real_nov','numeric');
        $this->captura('desviacion_acumulada_nov','numeric');

        $this->captura('avance_previsto_dic','numeric');
        $this->captura('avance_real_dic','numeric');
        $this->captura('desviacion_mes_dic','numeric');
        $this->captura('acum_previsto_dic','numeric');
        $this->captura('acum_real_dic','numeric');
        $this->captura('desviacion_acumulada_dic','numeric');
        $this->captura('orden_logico','int4');
        //Ejecuta la instruccion
        $this->armarConsulta();
        $this->ejecutarConsulta();
        
        //Devuelve la respuesta
        return $this->respuesta;
    }

}

?>