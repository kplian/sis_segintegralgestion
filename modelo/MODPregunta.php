<?php
/**
*@package pXP
*@file gen-MODPregunta.php
*@author  (mguerra)
*@date 21-04-2020 08:17:42
*@description Clase que envia los parametros requeridos a la Base de datos para la ejecucion de las funciones, y que recibe la respuesta del resultado de la ejecucion de las mismas
 HISTORIAL DE MODIFICACIONES:
#ISSUE				FECHA				AUTOR				DESCRIPCION
 #0				21-04-2020 08:17:42								CREACION

*/

class MODPregunta extends MODbase{
	
	function __construct(CTParametro $pParam){
		parent::__construct($pParam);
	}
			
	function listarPregunta(){
		//Definicion de variables para ejecucion del procedimientp
		$this->procedimiento='ssig.ft_pregunta_sel';
		$this->transaccion='SSIG_PRE_SEL';
		$this->tipo_procedimiento='SEL';//tipo de transaccion
				
		//Definicion de la lista del resultado del query
		$this->captura('id_pregunta','int4');
		$this->captura('estado_reg','varchar');
		$this->captura('pregunta','varchar');
		$this->captura('habilitar','boolean');
		$this->captura('tipo','varchar');
		$this->captura('resultado','numeric');
		$this->captura('observacion','varchar');
		$this->captura('id_categoria','int4');
		$this->captura('categoria','varchar');
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
			
	function insertarPregunta(){
		//Definicion de variables para ejecucion del procedimiento
		$this->procedimiento='ssig.ft_pregunta_ime';
		$this->transaccion='SSIG_PRE_INS';
		$this->tipo_procedimiento='IME';
				
		//Define los parametros para la funcion
		$this->setParametro('estado_reg','estado_reg','varchar');
		$this->setParametro('pregunta','pregunta','varchar');
		$this->setParametro('habilitar','habilitar','boolean');
		$this->setParametro('tipo','tipo','varchar');
		$this->setParametro('resultado','resultado','numeric');
		$this->setParametro('observacion','observacion','varchar');
		$this->setParametro('id_categoria','id_categoria','int4');

		//Ejecuta la instruccion
		$this->armarConsulta();
		$this->ejecutarConsulta();

		//Devuelve la respuesta
		return $this->respuesta;
	}
			
	function modificarPregunta(){
		//Definicion de variables para ejecucion del procedimiento
		$this->procedimiento='ssig.ft_pregunta_ime';
		$this->transaccion='SSIG_PRE_MOD';
		$this->tipo_procedimiento='IME';
				
		//Define los parametros para la funcion
		$this->setParametro('id_pregunta','id_pregunta','int4');
		$this->setParametro('estado_reg','estado_reg','varchar');
		$this->setParametro('pregunta','pregunta','varchar');
		$this->setParametro('habilitar','habilitar','boolean');
		$this->setParametro('tipo','tipo','varchar');
		$this->setParametro('resultado','resultado','numeric');
		$this->setParametro('observacion','observacion','varchar');
		$this->setParametro('id_categoria','id_categoria','int4');

		//Ejecuta la instruccion
		$this->armarConsulta();
		$this->ejecutarConsulta();

		//Devuelve la respuesta
		return $this->respuesta;
	}
			
	function eliminarPregunta(){
		//Definicion de variables para ejecucion del procedimiento
		$this->procedimiento='ssig.ft_pregunta_ime';
		$this->transaccion='SSIG_PRE_ELI';
		$this->tipo_procedimiento='IME';
				
		//Define los parametros para la funcion
		$this->setParametro('id_pregunta','id_pregunta','int4');

		//Ejecuta la instruccion
		$this->armarConsulta();
		$this->ejecutarConsulta();

		//Devuelve la respuesta
		return $this->respuesta;
	}
			
}
?>