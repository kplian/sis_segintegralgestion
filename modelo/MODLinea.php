<?php
/**
*@package pXP
*@file gen-MODLinea.php
*@author  (admin)
*@date 11-04-2017 20:20:49
*@description Clase que envia los parametros requeridos a la Base de datos para la ejecucion de las funciones, y que recibe la respuesta del resultado de la ejecucion de las mismas
*/

class MODLinea extends MODbase{
	
	function __construct(CTParametro $pParam){
		parent::__construct($pParam);
	}
			
	function listarLinea(){
		//Definicion de variables para ejecucion del procedimientp
		$this->procedimiento='ssig.ft_linea_sel';
		$this->transaccion='SSIG_LINEA_SEL';
		$this->tipo_procedimiento='SEL';//tipo de transaccion

		//Definicion de la lista del resultado del query
		$this->captura('id_linea','int4');
		$this->captura('id_linea_padre','int4');
		$this->captura('id_plan','int4');
		$this->captura('estado_reg','varchar');
		$this->captura('nivel','int4');
		$this->captura('nombre_linea','varchar');
		$this->captura('peso','numeric'); //#5
		$this->captura('fecha_reg','timestamp');
		$this->captura('usuario_ai','varchar');
		$this->captura('id_usuario_reg','int4');
		$this->captura('id_usuario_ai','int4');
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
    function listarLineaArb(){
		$this->procedimiento = 'ssig.ft_linea_sel';
        $this->setCount(false);
        $this->transaccion = 'SSIG_LINEA_SEL_ARB';
        $this->tipo_procedimiento = 'SEL';

        $id_padre = $this->objParam->getParametro('id_padre');
        $this->setParametro('id_padre', 'id_padre', 'varchar');

        $this->setParametro('id_plan', 'id_plan', 'int4');
        $this->setParametro('init_plan', 'init_plan', 'int4');

       //Definicion de la lista del resultado del query
		$this->captura('id_linea','int4');
		$this->captura('id_linea_padre','int4');
		$this->captura('id_plan','int4');
        $this->captura('nivel','int4');
		$this->captura('nombre_linea','varchar');
		$this->captura('peso','numeric'); //#5
        $this->captura('tipo_nodo','varchar');

        $this->captura('porcentaje_acum', 'int4');
        $this->captura('porcentaje_rest', 'int4');

        $this->captura('id_funcionarios','varchar');
        $this->captura('funcionarios','varchar');

        $this->captura('nombre_linea_padre','varchar');

        $this->captura('porcentaje_acumulado', 'varchar');
        $this->captura('porcentaje_restante', 'varchar');

        $this->captura('orden_logico', 'varchar');
        $this->captura('orden_logico_temporal', 'varchar');
        
        $this->armarConsulta();
        $this->ejecutarConsulta();

        return $this->respuesta;
	}
			
	function insertarLinea(){
		//Definicion de variables para ejecucion del procedimiento
		$this->procedimiento='ssig.ft_linea_ime';
		$this->transaccion='SSIG_LINEA_INS';
		$this->tipo_procedimiento='IME';
				
		//Define los parametros para la funcion
		$this->setParametro('id_linea_padre','id_linea_padre','varchar');
		$this->setParametro('id_plan','id_plan','int4');
		$this->setParametro('estado_reg','estado_reg','varchar');
		$this->setParametro('nivel','nivel','int4');
		$this->setParametro('nombre_linea','nombre_linea','varchar');
		$this->setParametro('peso','peso','numeric'); //#5

        $this->setParametro('id_funcionarios','id_funcionarios','varchar');

        $this->setParametro('orden_logico','orden_logico','int4');
        //Ejecuta la instruccion
		$this->armarConsulta();
		$this->ejecutarConsulta();

		//Devuelve la respuesta
		return $this->respuesta;
	}
			
	function modificarLinea(){
		//Definicion de variables para ejecucion del procedimiento
		$this->procedimiento='ssig.ft_linea_ime';
		$this->transaccion='SSIG_LINEA_MOD';
		$this->tipo_procedimiento='IME';
				
		//Define los parametros para la funcion
		$this->setParametro('id_linea','id_linea','int4');
		$this->setParametro('id_linea_padre','id_linea_padre','varchar');
		$this->setParametro('id_plan','id_plan','int4');
		$this->setParametro('estado_reg','estado_reg','varchar');
		$this->setParametro('nivel','nivel','int4');
		$this->setParametro('nombre_linea','nombre_linea','varchar');
		$this->setParametro('peso','peso','numeric'); //#5
 
        $this->setParametro('id_funcionarios','id_funcionarios','varchar');

        $this->setParametro('orden_logico','orden_logico','int4');

        //Ejecuta la instruccion
		$this->armarConsulta();
		$this->ejecutarConsulta();

		//Devuelve la respuesta
		return $this->respuesta;
	}
			
	function eliminarLinea(){
		//Definicion de variables para ejecucion del procedimiento
		$this->procedimiento='ssig.ft_linea_ime';
		$this->transaccion='SSIG_LINEA_ELI';
		$this->tipo_procedimiento='IME';
				
		//Define los parametros para la funcion
		$this->setParametro('id_linea','id_linea','int4');

		$this->setParametro('id_linea_padre','id_linea_padre','varchar');
		$this->setParametro('id_plan','id_plan','int4');
		$this->setParametro('estado_reg','estado_reg','varchar');
		$this->setParametro('nivel','nivel','int4');
		$this->setParametro('nombre_linea','nombre_linea','varchar');
		$this->setParametro('peso','peso','numeric'); //#5

        $this->setParametro('id_funcionarios','id_funcionarios','varchar');

		//Ejecuta la instruccion
		$this->armarConsulta();
		$this->ejecutarConsulta();

		//Devuelve la respuesta
		return $this->respuesta;
	}
			
}
?>