<?php
/**
*@package pXP
*@file gen-MODEvaluados.php
*@author  (admin.miguel)
*@date 28-04-2020 01:32:33
*@description Clase que envia los parametros requeridos a la Base de datos para la ejecucion de las funciones, y que recibe la respuesta del resultado de la ejecucion de las mismas
 HISTORIAL DE MODIFICACIONES:
#ISSUE				FECHA				AUTOR				DESCRIPCION
 #0				28-04-2020 01:32:33								CREACION

*/

class MODEvaluados extends MODbase{
	
	function __construct(CTParametro $pParam){
		parent::__construct($pParam);
	}
			
	function listarEvaluados(){
		//Definicion de variables para ejecucion del procedimientp
		$this->procedimiento='ssig.ft_evaluados_sel';
		$this->transaccion='SSIG_EVS_SEL';
		$this->tipo_procedimiento='SEL';//tipo de transaccion
				
		//Definicion de la lista del resultado del query
		$this->captura('id_evaluados','int4');
		$this->captura('estado_reg','varchar');
		$this->captura('obs_dba','varchar');
		$this->captura('id_cuestionario_funcionario','int4');
		$this->captura('id_funcionario','int4');
		$this->captura('evaluar','varchar');
		$this->captura('id_usuario_reg','int4');
		$this->captura('fecha_reg','timestamp');
		$this->captura('id_usuario_ai','int4');
		$this->captura('usuario_ai','varchar');
		$this->captura('id_usuario_mod','int4');
		$this->captura('fecha_mod','timestamp');
		$this->captura('usr_reg','varchar');
		$this->captura('usr_mod','varchar');
		$this->captura('desc_funcionario1','text');
        $this->captura('nombre_unidad','varchar');

        //Ejecuta la instruccion
		$this->armarConsulta();
		$this->ejecutarConsulta();
		
		//Devuelve la respuesta
		return $this->respuesta;
	}
			
	function insertarEvaluados(){
		//Definicion de variables para ejecucion del procedimiento
		$this->procedimiento='ssig.ft_evaluados_ime';
		$this->transaccion='SSIG_EVS_INS';
		$this->tipo_procedimiento='IME';
				
		//Define los parametros para la funcion
		$this->setParametro('estado_reg','estado_reg','varchar');
		$this->setParametro('obs_dba','obs_dba','varchar');
		$this->setParametro('id_cuestionario_funcionario','id_cuestionario_funcionario','int4');
		$this->setParametro('id_funcionario','id_funcionario','int4');
		$this->setParametro('evaluar','evaluar','varchar');

		//Ejecuta la instruccion
		$this->armarConsulta();
		$this->ejecutarConsulta();

		//Devuelve la respuesta
		return $this->respuesta;
	}
			
	function modificarEvaluados(){
		//Definicion de variables para ejecucion del procedimiento
		$this->procedimiento='ssig.ft_evaluados_ime';
		$this->transaccion='SSIG_EVS_MOD';
		$this->tipo_procedimiento='IME';
				
		//Define los parametros para la funcion
		$this->setParametro('id_evaluados','id_evaluados','int4');
		$this->setParametro('estado_reg','estado_reg','varchar');
		$this->setParametro('obs_dba','obs_dba','varchar');
		$this->setParametro('id_cuestionario_funcionario','id_cuestionario_funcionario','int4');
		$this->setParametro('id_funcionario','id_funcionario','int4');
		$this->setParametro('evaluar','evaluar','varchar');

		//Ejecuta la instruccion
		$this->armarConsulta();
		$this->ejecutarConsulta();

		//Devuelve la respuesta
		return $this->respuesta;
	}
			
	function eliminarEvaluados(){
		//Definicion de variables para ejecucion del procedimiento
		$this->procedimiento='ssig.ft_evaluados_ime';
		$this->transaccion='SSIG_EVS_ELI';
		$this->tipo_procedimiento='IME';
				
		//Define los parametros para la funcion
		$this->setParametro('id_evaluados','id_evaluados','int4');

		//Ejecuta la instruccion
		$this->armarConsulta();
		$this->ejecutarConsulta();

		//Devuelve la respuesta
		return $this->respuesta;
	}
			
}
?>