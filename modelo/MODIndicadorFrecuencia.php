<?php
/**
*@package pXP
*@file gen-MODIndicadorFrecuencia.php
*@author  (admin)
*@date 21-11-2016 12:35:24
*@description Clase que envia los parametros requeridos a la Base de datos para la ejecucion de las funciones, y que recibe la respuesta del resultado de la ejecucion de las mismas
*/

class MODIndicadorFrecuencia extends MODbase{
	
	function __construct(CTParametro $pParam){
		parent::__construct($pParam);
	}
			
	function listarIndicadorFrecuencia(){
		//Definicion de variables para ejecucion del procedimientp
		$this->procedimiento='ssig.ft_indicador_frecuencia_sel';
		$this->transaccion='SSIG_INFR_SEL';
		$this->tipo_procedimiento='SEL';//tipo de transaccion
				
		//Definicion de la lista del resultado del query
		$this->captura('id_indicador_frecuencia','int4');
		$this->captura('valor','int4');
		$this->captura('hito','bool');
		$this->captura('estado_reg','varchar');
		$this->captura('frecuencia','varchar');
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
			
	function insertarIndicadorFrecuencia(){
		//Definicion de variables para ejecucion del procedimiento
		$this->procedimiento='ssig.ft_indicador_frecuencia_ime';
		$this->transaccion='SSIG_INFR_INS';
		$this->tipo_procedimiento='IME';
				
		//Define los parametros para la funcion
		$this->setParametro('valor','valor','int4');
		$this->setParametro('hito','hito','int4');
		$this->setParametro('estado_reg','estado_reg','varchar');
		$this->setParametro('frecuencia','frecuencia','varchar');

		//Ejecuta la instruccion
		$this->armarConsulta();
		$this->ejecutarConsulta();

		//Devuelve la respuesta
		return $this->respuesta;
	}
			
	function modificarIndicadorFrecuencia(){
		//Definicion de variables para ejecucion del procedimiento
		$this->procedimiento='ssig.ft_indicador_frecuencia_ime';
		$this->transaccion='SSIG_INFR_MOD';
		$this->tipo_procedimiento='IME';
				
		//Define los parametros para la funcion
		$this->setParametro('id_indicador_frecuencia','id_indicador_frecuencia','int4');
		$this->setParametro('valor','valor','int4');
		$this->setParametro('hito','hito','int4');
		$this->setParametro('estado_reg','estado_reg','varchar');
		$this->setParametro('frecuencia','frecuencia','varchar');

		//Ejecuta la instruccion
		$this->armarConsulta();
		$this->ejecutarConsulta();

		//Devuelve la respuesta
		return $this->respuesta;
	}
			
	function eliminarIndicadorFrecuencia(){
		//Definicion de variables para ejecucion del procedimiento
		$this->procedimiento='ssig.ft_indicador_frecuencia_ime';
		$this->transaccion='SSIG_INFR_ELI';
		$this->tipo_procedimiento='IME';
				
		//Define los parametros para la funcion
		$this->setParametro('id_indicador_frecuencia','id_indicador_frecuencia','int4');

		//Ejecuta la instruccion
		$this->armarConsulta();
		$this->ejecutarConsulta();

		//Devuelve la respuesta
		return $this->respuesta;
	}
			
}
?>