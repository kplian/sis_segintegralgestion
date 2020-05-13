<?php
/**
*@package pXP
*@file gen-MODTipoEvalucion.php
*@author  (admin.miguel)
*@date 27-04-2020 14:34:48
*@description Clase que envia los parametros requeridos a la Base de datos para la ejecucion de las funciones, y que recibe la respuesta del resultado de la ejecucion de las mismas
 HISTORIAL DE MODIFICACIONES:
#ISSUE				FECHA				AUTOR				DESCRIPCION
 #0				27-04-2020 14:34:48								CREACION

*/

class MODTipoEvalucion extends MODbase{
	
	function __construct(CTParametro $pParam){
		parent::__construct($pParam);
	}
			
	function listarTipoEvalucion(){
		//Definicion de variables para ejecucion del procedimientp
		$this->procedimiento='ssig.ft_tipo_evalucion_sel';
		$this->transaccion='SSIG_TEN_SEL';
		$this->tipo_procedimiento='SEL';//tipo de transaccion
				
		//Definicion de la lista del resultado del query
		$this->captura('id_tipo_evalucion','int4');
		$this->captura('estado_reg','varchar');
		$this->captura('obs_dba','varchar');
		$this->captura('codigo','varchar');
		$this->captura('nombre','varchar');
		$this->captura('id_nivel_organizacional','int4');
		$this->captura('id_usuario_reg','int4');
		$this->captura('fecha_reg','timestamp');
		$this->captura('id_usuario_ai','int4');
		$this->captura('usuario_ai','varchar');
		$this->captura('id_usuario_mod','int4');
		$this->captura('fecha_mod','timestamp');
		$this->captura('usr_reg','varchar');
		$this->captura('usr_mod','varchar');
		$this->captura('desc_nombre_nivel','text');
        $this->captura('tipo','varchar');
		//Ejecuta la instruccion
		$this->armarConsulta();
		$this->ejecutarConsulta();
		
		//Devuelve la respuesta
		return $this->respuesta;
	}
			
	function insertarTipoEvalucion(){
		//Definicion de variables para ejecucion del procedimiento
		$this->procedimiento='ssig.ft_tipo_evalucion_ime';
		$this->transaccion='SSIG_TEN_INS';
		$this->tipo_procedimiento='IME';
				
		//Define los parametros para la funcion
		$this->setParametro('estado_reg','estado_reg','varchar');
		$this->setParametro('obs_dba','obs_dba','varchar');
		$this->setParametro('codigo','codigo','varchar');
		$this->setParametro('nombre','nombre','varchar');
		$this->setParametro('id_nivel_organizacional','id_nivel_organizacional','int4');
        $this->setParametro('tipo','tipo','varchar');
		//Ejecuta la instruccion
		$this->armarConsulta();
		$this->ejecutarConsulta();

		//Devuelve la respuesta
		return $this->respuesta;
	}
			
	function modificarTipoEvalucion(){
		//Definicion de variables para ejecucion del procedimiento
		$this->procedimiento='ssig.ft_tipo_evalucion_ime';
		$this->transaccion='SSIG_TEN_MOD';
		$this->tipo_procedimiento='IME';
				
		//Define los parametros para la funcion
		$this->setParametro('id_tipo_evalucion','id_tipo_evalucion','int4');
		$this->setParametro('estado_reg','estado_reg','varchar');
		$this->setParametro('obs_dba','obs_dba','varchar');
		$this->setParametro('codigo','codigo','varchar');
		$this->setParametro('nombre','nombre','varchar');
		$this->setParametro('id_nivel_organizacional','id_nivel_organizacional','int4');
        $this->setParametro('tipo','tipo','varchar');
		//Ejecuta la instruccion
		$this->armarConsulta();
		$this->ejecutarConsulta();

		//Devuelve la respuesta
		return $this->respuesta;
	}
			
	function eliminarTipoEvalucion(){
		//Definicion de variables para ejecucion del procedimiento
		$this->procedimiento='ssig.ft_tipo_evalucion_ime';
		$this->transaccion='SSIG_TEN_ELI';
		$this->tipo_procedimiento='IME';
				
		//Define los parametros para la funcion
		$this->setParametro('id_tipo_evalucion','id_tipo_evalucion','int4');

		//Ejecuta la instruccion
		$this->armarConsulta();
		$this->ejecutarConsulta();

		//Devuelve la respuesta
		return $this->respuesta;
	}
			
}
?>