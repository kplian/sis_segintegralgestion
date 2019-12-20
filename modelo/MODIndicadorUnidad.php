<?php
/**
*@package pXP
*@file gen-MODIndicadorUnidad.php
*@author  (admin)
*@date 21-11-2016 09:55:49
*@description Clase que envia los parametros requeridos a la Base de datos para la ejecucion de las funciones, y que recibe la respuesta del resultado de la ejecucion de las mismas
*/

class MODIndicadorUnidad extends MODbase{
	
	function __construct(CTParametro $pParam){
		parent::__construct($pParam);
	}
			
	function listarIndicadorUnidad(){
		//Definicion de variables para ejecucion del procedimientp
		$this->procedimiento='ssig.ft_indicador_unidad_sel';
		$this->transaccion='SSIG_INUN_SEL';
		$this->tipo_procedimiento='SEL';//tipo de transaccion
				
		//Definicion de la lista del resultado del query
		$this->captura('id_indicador_unidad','int4');
		$this->captura('estado_reg','varchar');
		$this->captura('tipo','varchar');
		$this->captura('unidad','varchar');
		$this->captura('usuario_ai','varchar');
		$this->captura('fecha_reg','timestamp');
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
			
	function insertarIndicadorUnidad(){
		//Definicion de variables para ejecucion del procedimiento
		$this->procedimiento='ssig.ft_indicador_unidad_ime';
		$this->transaccion='SSIG_INUN_INS';
		$this->tipo_procedimiento='IME';
				
		//Define los parametros para la funcion
		$this->setParametro('estado_reg','estado_reg','varchar');
		$this->setParametro('tipo','tipo','numeric');
		$this->setParametro('unidad','unidad','varchar');

		//Ejecuta la instruccion
		$this->armarConsulta();
		$this->ejecutarConsulta();

		//Devuelve la respuesta
		return $this->respuesta;
	}
			
	function modificarIndicadorUnidad(){
		//Definicion de variables para ejecucion del procedimiento
		$this->procedimiento='ssig.ft_indicador_unidad_ime';
		$this->transaccion='SSIG_INUN_MOD';
		$this->tipo_procedimiento='IME';
				
		//Define los parametros para la funcion
		$this->setParametro('id_indicador_unidad','id_indicador_unidad','int4');
		$this->setParametro('estado_reg','estado_reg','varchar');
		$this->setParametro('tipo','tipo','numeric');
		$this->setParametro('unidad','unidad','varchar');

		//Ejecuta la instruccion
		$this->armarConsulta();
		$this->ejecutarConsulta();

		//Devuelve la respuesta
		return $this->respuesta;
	}
			
	function eliminarIndicadorUnidad(){
		//Definicion de variables para ejecucion del procedimiento
		$this->procedimiento='ssig.ft_indicador_unidad_ime';
		$this->transaccion='SSIG_INUN_ELI';
		$this->tipo_procedimiento='IME';
				
		//Define los parametros para la funcion
		$this->setParametro('id_indicador_unidad','id_indicador_unidad','int4');

		//Ejecuta la instruccion
		$this->armarConsulta();
		$this->ejecutarConsulta();

		//Devuelve la respuesta
		return $this->respuesta;
	}
			
}
?>