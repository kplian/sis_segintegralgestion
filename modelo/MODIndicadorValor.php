<?php
/**
*@package pXP
*@file gen-MODIndicadorValor.php
*@author  (admin)
*@date 21-11-2016 14:01:15
*@description Clase que envia los parametros requeridos a la Base de datos para la ejecucion de las funciones, y que recibe la respuesta del resultado de la ejecucion de las mismas
*/

class MODIndicadorValor extends MODbase{
	
	function __construct(CTParametro $pParam){
		parent::__construct($pParam);
	}
			
	function listarIndicadorValor(){
		//Definicion de variables para ejecucion del procedimientp
		$this->procedimiento='ssig.ft_indicador_valor_sel';
		$this->transaccion='SSIG_INVA_SEL';
		$this->tipo_procedimiento='SEL';//tipo de transaccion
				
		//Definicion de la lista del resultado del query
		$this->captura('id_indicador_valor','int4');
		$this->captura('id_indicador','int4');
		$this->captura('semaforo3','varchar');
		$this->captura('semaforo5','varchar');
		$this->captura('no_reporta','varchar');
		$this->captura('semaforo4','varchar');
		$this->captura('estado_reg','varchar');
		$this->captura('semaforo2','varchar');
		$this->captura('valor','varchar');
		$this->captura('fecha','date');
		$this->captura('hito','varchar');
		$this->captura('semaforo1','varchar');
		$this->captura('justificacion','varchar');
		$this->captura('fecha_reg','timestamp');
		$this->captura('usuario_ai','varchar');
		$this->captura('id_usuario_reg','int4');
		$this->captura('id_usuario_ai','int4');
		$this->captura('fecha_mod','timestamp');
		$this->captura('id_usuario_mod','int4');
		$this->captura('usr_reg','varchar');
		$this->captura('usr_mod','varchar');
		
		$this->captura('semaforo','varchar');
		$this->captura('frecuencia','varchar');
		
		//Ejecuta la instruccion
		$this->armarConsulta();
		$this->ejecutarConsulta();
		
		//Devuelve la respuesta
		return $this->respuesta;
	}
			
	function insertarIndicadorValor(){
		//Definicion de variables para ejecucion del procedimiento
		$this->procedimiento='ssig.ft_indicador_valor_ime';
		$this->transaccion='SSIG_INVA_INS';
		$this->tipo_procedimiento='IME';
				
		//Define los parametros para la funcion
		$this->setParametro('id_indicador','id_indicador','int4');
		$this->setParametro('semaforo3','semaforo3','varchar');
		$this->setParametro('semaforo5','semaforo5','varchar');
		$this->setParametro('no_reporta','no_reporta','varchar');
		$this->setParametro('semaforo4','semaforo4','varchar');
		$this->setParametro('estado_reg','estado_reg','varchar');
		$this->setParametro('semaforo2','semaforo2','varchar');
		$this->setParametro('valor','valor','varchar');
		$this->setParametro('fecha','fecha','date');
		$this->setParametro('hito','hito','varchar');
		$this->setParametro('semaforo1','semaforo1','varchar');
		$this->setParametro('justificacion','justificacion','varchar');

		//Ejecuta la instruccion
		$this->armarConsulta();
		$this->ejecutarConsulta();

		//Devuelve la respuesta
		return $this->respuesta;
	}
	function insertarIndicadorValorNuevo(){
		//Definicion de variables para ejecucion del procedimiento
		$this->procedimiento='ssig.ft_indicador_valor_ime';
		$this->transaccion='SSIG_INVA_INS';
		$this->tipo_procedimiento='IME';
				
		//Define los parametros para la funcion
		$this->setParametro('id_indicador','id_indicador','int4');
		$this->setParametro('semaforo3','semaforo3','varchar');
		$this->setParametro('semaforo5','semaforo5','varchar');
		$this->setParametro('no_reporta','no_reporta','varchar');
		$this->setParametro('semaforo4','semaforo4','varchar');
		$this->setParametro('estado_reg','esado_reg','varchar');
		$this->setParametro('semaforo2','semaforo2','varchar');
		//$this->setParametro('valor','valor','varchar');
		$this->setParametro('hito','hito','varchar');
		$this->setParametro('semaforo1','semaforo1','varchar');
		//$this->setParametro('justificacion','justificacion','varchar');

		//Ejecuta la instruccion
		$this->armarConsulta();
		$this->ejecutarConsulta();

		//Devuelve la respuesta
		return $this->respuesta;
	}
			
	function modificarIndicadorValor(){
		//Definicion de variables para ejecucion del procedimiento
		$this->procedimiento='ssig.ft_indicador_valor_ime';
		$this->transaccion='SSIG_INVA_MOD';
		$this->tipo_procedimiento='IME';
				
		//Define los parametros para la funcion
		$this->setParametro('id_indicador_valor','id_indicador_valor','int4');
		$this->setParametro('id_indicador','id_indicador','int4');
		$this->setParametro('semaforo3','semaforo3','varchar');
		$this->setParametro('semaforo5','semaforo5','varchar');
		$this->setParametro('no_reporta','no_reporta','varchar');
		$this->setParametro('semaforo4','semaforo4','varchar');
		$this->setParametro('estado_reg','estado_reg','varchar');
		$this->setParametro('semaforo2','semaforo2','varchar');
		$this->setParametro('valor','valor','varchar');
		$this->setParametro('fecha','fecha','date');
		$this->setParametro('hito','hito','varchar');
		$this->setParametro('semaforo1','semaforo1','varchar');
		$this->setParametro('justificacion','justificacion','varchar');

		//Ejecuta la instruccion
		$this->armarConsulta();
		$this->ejecutarConsulta();

		//Devuelve la respuesta
		return $this->respuesta;
	}
			
	function eliminarIndicadorValor(){
		//Definicion de variables para ejecucion del procedimiento
		$this->procedimiento='ssig.ft_indicador_valor_ime';
		$this->transaccion='SSIG_INVA_ELI';
		$this->tipo_procedimiento='IME';
				
		//Define los parametros para la funcion
		$this->setParametro('id_indicador_valor','id_indicador_valor','int4');

		//Ejecuta la instruccion
		$this->armarConsulta();
		$this->ejecutarConsulta();

		//Devuelve la respuesta
		return $this->respuesta;
	}
			
}
?>