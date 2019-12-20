<?php
/**
*@package pXP
*@file gen-MODAgrupador.php
*@author  (admin)
*@date 05-06-2017 04:46:40
*@description Clase que envia los parametros requeridos a la Base de datos para la ejecucion de las funciones, y que recibe la respuesta del resultado de la ejecucion de las mismas
*/

class MODAgrupador extends MODbase{
	//
	function __construct(CTParametro $pParam){
		parent::__construct($pParam);
	}
			
	function listarAgrupador(){
		//Definicion de variables para ejecucion del procedimientp
		$this->procedimiento='ssig.ft_agrupador_sel';
		$this->transaccion='SSIG_SSIG_AG_SEL';
		$this->tipo_procedimiento='SEL';//tipo de transaccion
				
		//Definicion de la lista del resultado del query
		$this->captura('id_agrupador','int4');
		$this->captura('id_agrupador_padre','int4');
		$this->captura('id_funcionario','int4');
		$this->captura('nombre','varchar');
		$this->captura('descripcion','varchar');
		$this->captura('nivel','int4');
		$this->captura('peso','int4');
		$this->captura('estado_reg','varchar');
		$this->captura('id_usuario_ai','int4');
		$this->captura('id_usuario_reg','int4');
		$this->captura('usuario_ai','varchar');
		$this->captura('fecha_reg','timestamp');
		$this->captura('id_usuario_mod','int4');
		$this->captura('fecha_mod','timestamp');
		$this->captura('usr_reg','varchar');
		$this->captura('usr_mod','varchar');
		
		//Ejecuta la instruccion
		$this->armarConsulta();
		$this->ejecutarConsulta();
		
		//Devuelve la respuesta
		return $this->respuesta;
	}

	function listarAgrupadorArb(){
		//Definicion de variables para ejecucion del procedimientp
		$this->procedimiento='ssig.ft_agrupador_sel';
        $this->setCount(false);
        $this->transaccion='SSIG_AG_SEL_ARB';
		$this->tipo_procedimiento='SEL';//tipo de transaccion


        $id_padre = $this->objParam->getParametro('id_padre');
        $this->setParametro('id_padre', 'id_padre', 'varchar');	
		$this->setParametro('id_gestion', 'id_gestion', 'int4');
		$this->setParametro('id_periodo', 'id_periodo', 'int4');
		//$this->setParametro('id_funcionario', 'id_funcionario', 'int4');
		
		//Definicion de la lista del resultado del query
		$this->captura('id_agrupador','int4');
		$this->captura('id_agrupador_padre','int4');
		$this->captura('id_funcionario','int4');
		$this->captura('nombre','varchar');
		$this->captura('descripcion','varchar');
		$this->captura('nivel','int4');
		$this->captura('peso','int4');
		$this->captura('desc_person','varchar');
        $this->captura('tipo_nodo','varchar');
		$this->captura('id_gestion','int4');		
 		$this->captura('aprobado', 'bit');
		$this->captura('porcentaje_acum', 'int4');
        $this->captura('porcentaje_rest', 'int4');
		$this->captura('nombre_agrupador_padre', 'varchar');
		$this->captura('porcentaje_acumulado', 'varchar');
        $this->captura('porcentaje_restante', 'varchar');
		$this->captura('resultado', 'numeric');
		$this->captura('id_periodo', 'int4');
		$this->captura('orden_logico', 'int4');
		
		$this->captura('orden_logico_temporal', 'varchar');
		//Ejecuta la instruccion
		$this->armarConsulta();
		$this->ejecutarConsulta();

		//Devuelve la respuesta
		return $this->respuesta;
	}
			
	function insertarAgrupador(){
		//Definicion de variables para ejecucion del procedimiento
		$this->procedimiento='ssig.ft_agrupador_ime';
		$this->transaccion='SSIG_SSIG_AG_INS';
		$this->tipo_procedimiento='IME';
				
		//Define los parametros para la funcion
		$this->setParametro('id_agrupador_padre','id_agrupador_padre','varchar');
		$this->setParametro('id_funcionario','id_funcionario','int4');
		$this->setParametro('nombre','nombre','varchar');
		$this->setParametro('descripcion','descripcion','varchar');
		$this->setParametro('nivel','nivel','int4');
		$this->setParametro('peso','peso','int4');
		$this->setParametro('estado_reg','estado_reg','varchar');
		$this->setParametro('id_gestion', 'id_gestion', 'int4');
		$this->setParametro('aprobado', 'aprobado', 'bit');
		$this->setParametro('orden_logico', 'orden_logico', 'int4');
		
		//Ejecuta la instruccion
		$this->armarConsulta();
		$this->ejecutarConsulta();
		

		//Devuelve la respuesta
		return $this->respuesta;
	}
			
	function modificarAgrupador(){
		//Definicion de variables para ejecucion del procedimiento
		$this->procedimiento='ssig.ft_agrupador_ime';
		$this->transaccion='SSIG_SSIG_AG_MOD';
		$this->tipo_procedimiento='IME';
				
		//Define los parametros para la funcion
		$this->setParametro('id_agrupador','id_agrupador','int4');
		$this->setParametro('id_agrupador_padre','id_agrupador_padre','int4');
		$this->setParametro('id_funcionario','id_funcionario','int4');
		$this->setParametro('nombre','nombre','varchar');
		$this->setParametro('descripcion','descripcion','varchar');
		$this->setParametro('nivel','nivel','int4');
		$this->setParametro('peso','peso','int4');
		$this->setParametro('estado_reg','estado_reg','varchar');
        $this->setParametro('orden_logico', 'orden_logico', 'int4');
        
		//Ejecuta la instruccion
		$this->armarConsulta();
		$this->ejecutarConsulta();

		//Devuelve la respuesta
		return $this->respuesta;
	}
			
	function eliminarAgrupador(){
		//Definicion de variables para ejecucion del procedimiento
		$this->procedimiento='ssig.ft_agrupador_ime';
		$this->transaccion='SSIG_SSIG_AG_ELI';
		$this->tipo_procedimiento='IME';
				
		//Define los parametros para la funcion
		$this->setParametro('id_agrupador','id_agrupador','int4');

		//Ejecuta la instruccion
		$this->armarConsulta();
		$this->ejecutarConsulta();

		//Devuelve la respuesta
		return $this->respuesta;
	}
	//
	function aprobarPlanes()
    {
        //Definicion de variables para ejecucion del procedimiento
        $this->procedimiento = 'ssig.ft_agrupador_ime';
        $this->transaccion = 'SSIG_SSIG_APR';
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
        $this->transaccion = 'PM_GES_SEL';
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
        $this->procedimiento = 'ssig.ft_agrupador_ime';
        $this->transaccion = 'SSIG_SSIG_AG_EST_GES';
        $this->tipo_procedimiento = 'IME';

        //Define los parametros para la funcion
        $this->setParametro('id_gestion', 'id_gestion', 'int4');

        //Ejecuta la instruccion
        $this->armarConsulta();
        $this->ejecutarConsulta();

        //Devuelve la respuesta
        return $this->respuesta;
    }
	function listarInterpretacionIndicador(){
		//Definicion de variables para ejecucion del procedimientp
		$this->procedimiento='ssig.ft_agrupador_sel';
		$this->transaccion='SSIG_INT_INDI_SEL';
		$this->tipo_procedimiento='SEL';//tipo de transaccion
				
		//Definicion de la lista del resultado del query
		$this->captura('id_interpretacion_indicador','int4');
		$this->captura('id_gestion','int4');
		$this->captura('interpretacion','varchar');
		$this->captura('porcentaje','int4');
		$this->captura('icono','varchar');
		
		//Ejecuta la instruccion
		$this->armarConsulta();
		$this->ejecutarConsulta();
		
		//Devuelve la respuesta
		return $this->respuesta;
	}
	function modificarInterpretacionIndicador(){
		//Definicion de variables para ejecucion del procedimiento
		$this->procedimiento='ssig.ft_agrupador_ime';
		$this->transaccion='SSIG_INTERINDI_MOD';
		$this->tipo_procedimiento='IME';
				
				
		//Define los parametros para la funcion
		$this->setParametro('id_interpretacion_indicador','id_interpretacion_indicador','int4');
		$this->setParametro('id_gestion','id_gestion','int4');
		$this->setParametro('porcentaje','porcentaje','int4');
		
		//Ejecuta la instruccion
		$this->armarConsulta();
		$this->ejecutarConsulta();
		
		//Devuelve la respuesta
		return $this->respuesta;
	}
    function reporteCuadroDeMando(){
		//Definicion de variables para ejecucion del procedimientp
		$this->procedimiento='ssig.ft_agrupador_sel';
		$this->transaccion='SSIG_CMANDO_SEL';
		$this->tipo_procedimiento='SEL';//tipo de transaccion
				
		$this->setCount(false);

		$this->setParametro('id_gestion','id_gestion','int4');
		$this->setParametro('id_periodo','id_periodo','int4');

		//Definicion de la lista del resultado del query
		$this->captura('id_cmando_temporal','int4');
		$this->captura('id_gestion','int4');
		$this->captura('id_agrupador','int4');
		$this->captura('id_agrupador_padre','int4');
		$this->captura('nivel','int4');
		$this->captura('nombre','varchar');
		$this->captura('nivel_1','varchar');
		$this->captura('nivel_2','varchar');
		$this->captura('nivel_3','varchar');
		$this->captura('nivel_4','varchar');

		$this->captura('peso','numeric');
		$this->captura('resultado','numeric');

		$this->captura('unidad','varchar');
		$this->captura('frecuencia','varchar');
		$this->captura('tipo_semaforo','varchar');
		$this->captura('orden_comparacion','varchar');
		$this->captura('valor_real','varchar');
		$this->captura('semaforo_1','varchar');
		$this->captura('semaforo_2','varchar');
		$this->captura('semaforo_3','varchar');
		$this->captura('semaforo_4','varchar');
		$this->captura('semaforo_5','varchar');
		$this->captura('funcionario_ingreso','varchar');
		$this->captura('ruta_icono','varchar');
		$this->captura('funcionario_evaluacion','varchar');
		$this->captura('sigla','varchar');
		//Ejecuta la instruccion
		$this->armarConsulta();
		$this->ejecutarConsulta();
		
		//Devuelve la respuesta
		return $this->respuesta;
    }
			
}
?>