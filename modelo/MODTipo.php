<?php
/**
*@package pXP
*@file gen-MODTipo.php
*@author  (mguerra)
*@date 27-04-2020 11:27:10
*@description Clase que envia los parametros requeridos a la Base de datos para la ejecucion de las funciones, y que recibe la respuesta del resultado de la ejecucion de las mismas
 HISTORIAL DE MODIFICACIONES:
#ISSUE				FECHA				AUTOR				DESCRIPCION
 #0				27-04-2020 11:27:10								CREACION

*/

class MODTipo extends MODbase{
	
	function __construct(CTParametro $pParam){
		parent::__construct($pParam);
	}
			
	function listarTipo(){
		//Definicion de variables para ejecucion del procedimientp
		$this->procedimiento='ssig.ft_tipo_sel';
		$this->transaccion='SSIG_TPO_SEL';
		$this->tipo_procedimiento='SEL';//tipo de transaccion
				
		//Definicion de la lista del resultado del query
		$this->captura('id_tipo','int4');
		$this->captura('estado_reg','varchar');		
		$this->captura('tipo','varchar');
		$this->captura('observacion','varchar');
		$this->captura('id_usuario_reg','int4');
		$this->captura('fecha_reg','timestamp');
		$this->captura('id_usuario_ai','int4');
		$this->captura('usuario_ai','varchar');
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
			
	function insertarTipo(){
		//Definicion de variables para ejecucion del procedimiento
		$this->procedimiento='ssig.ft_tipo_ime';
		$this->transaccion='SSIG_TPO_INS';
		$this->tipo_procedimiento='IME';
				
		//Define los parametros para la funcion
		$this->setParametro('estado_reg','estado_reg','varchar');		
		$this->setParametro('tipo','tipo','varchar');
		$this->setParametro('observacion','observacion','varchar');

		//Ejecuta la instruccion
		$this->armarConsulta();
		$this->ejecutarConsulta();

		//Devuelve la respuesta
		return $this->respuesta;
	}
			
	function modificarTipo(){
		//Definicion de variables para ejecucion del procedimiento
		$this->procedimiento='ssig.ft_tipo_ime';
		$this->transaccion='SSIG_TPO_MOD';
		$this->tipo_procedimiento='IME';
				
		//Define los parametros para la funcion
		$this->setParametro('id_tipo','id_tipo','int4');
		$this->setParametro('estado_reg','estado_reg','varchar');		
		$this->setParametro('tipo','tipo','varchar');
		$this->setParametro('observacion','observacion','varchar');

		//Ejecuta la instruccion
		$this->armarConsulta();
		$this->ejecutarConsulta();

		//Devuelve la respuesta
		return $this->respuesta;
	}
			
	function eliminarTipo(){
		//Definicion de variables para ejecucion del procedimiento
		$this->procedimiento='ssig.ft_tipo_ime';
		$this->transaccion='SSIG_TPO_ELI';
		$this->tipo_procedimiento='IME';
				
		//Define los parametros para la funcion
		$this->setParametro('id_tipo','id_tipo','int4');

		//Ejecuta la instruccion
		$this->armarConsulta();
		$this->ejecutarConsulta();

		//Devuelve la respuesta
		return $this->respuesta;
	}
			
}
?>