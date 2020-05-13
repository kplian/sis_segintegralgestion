<?php
/**
*@package pXP
*@file gen-MODCuestionarioFuncionario.php
*@author  (mguerra)
*@date 22-04-2020 06:47:37
*@description Clase que envia los parametros requeridos a la Base de datos para la ejecucion de las funciones, y que recibe la respuesta del resultado de la ejecucion de las mismas
 HISTORIAL DE MODIFICACIONES:
#ISSUE				FECHA				AUTOR				DESCRIPCION
 #0				22-04-2020 06:47:37								CREACION

*/

class MODCuestionarioFuncionario extends MODbase{
	
	function __construct(CTParametro $pParam){
		parent::__construct($pParam);
	}
			
	function listarCuestionarioFuncionario(){
		//Definicion de variables para ejecucion del procedimientp
		$this->procedimiento='ssig.ft_cuestionario_funcionario_sel';
		$this->transaccion='SSIG_CUEFUN_SEL';
		$this->tipo_procedimiento='SEL';//tipo de transaccion
				
		//Definicion de la lista del resultado del query
		$this->captura('id_cuestionario_funcionario','int4');
		$this->captura('estado_reg','varchar');
		$this->captura('id_cuestionario','int4');
		$this->captura('id_funcionario','int4');
		$this->captura('id_usuario_reg','int4');
		$this->captura('fecha_reg','timestamp');
		$this->captura('id_usuario_ai','int4');
		$this->captura('usuario_ai','varchar');
		$this->captura('id_usuario_mod','int4');
		$this->captura('fecha_mod','timestamp');
		$this->captura('usr_reg','varchar');
		$this->captura('usr_mod','varchar');
		$this->captura('desc_person','varchar');
		$this->captura('codigo','varchar');
		$this->captura('sw_final','varchar');
		//Ejecuta la instruccion
		$this->armarConsulta();
		$this->ejecutarConsulta();
		
		//Devuelve la respuesta
		return $this->respuesta;
	}
			
	function insertarCuestionarioFuncionario(){
		//Definicion de variables para ejecucion del procedimiento
		$this->procedimiento='ssig.ft_cuestionario_funcionario_ime';
		$this->transaccion='SSIG_CUEFUN_INS';
		$this->tipo_procedimiento='IME';
				
		//Define los parametros para la funcion
		$this->setParametro('estado_reg','estado_reg','varchar');
		$this->setParametro('id_cuestionario','id_cuestionario','int4');
		$this->setParametro('id_funcionario','id_funcionario','int4');

		//Ejecuta la instruccion
		$this->armarConsulta();
		$this->ejecutarConsulta();

		//Devuelve la respuesta
		return $this->respuesta;
	}
			
	function modificarCuestionarioFuncionario(){
		//Definicion de variables para ejecucion del procedimiento
		$this->procedimiento='ssig.ft_cuestionario_funcionario_ime';
		$this->transaccion='SSIG_CUEFUN_MOD';
		$this->tipo_procedimiento='IME';
				
		//Define los parametros para la funcion
		$this->setParametro('id_cuestionario_funcionario','id_cuestionario_funcionario','int4');
		$this->setParametro('estado_reg','estado_reg','varchar');
		$this->setParametro('id_cuestionario','id_cuestionario','int4');
		$this->setParametro('id_funcionario','id_funcionario','int4');

		//Ejecuta la instruccion
		$this->armarConsulta();
		$this->ejecutarConsulta();

		//Devuelve la respuesta
		return $this->respuesta;
	}
			
	function eliminarCuestionarioFuncionario(){
		//Definicion de variables para ejecucion del procedimiento
		$this->procedimiento='ssig.ft_cuestionario_funcionario_ime';
		$this->transaccion='SSIG_CUEFUN_ELI';
		$this->tipo_procedimiento='IME';
				
		//Define los parametros para la funcion
		$this->setParametro('id_cuestionario_funcionario','id_cuestionario_funcionario','int4');

		//Ejecuta la instruccion
		$this->armarConsulta();
		$this->ejecutarConsulta();

		//Devuelve la respuesta
		return $this->respuesta;
	}
	//
	function listarCuestionarioEvaluacion(){
		//Definicion de variables para ejecucion del procedimientp
		$this->procedimiento='ssig.ft_cuestionario_funcionario_sel';
		$this->transaccion='SSIG_LIST_SEL';
		$this->tipo_procedimiento='SEL';//tipo de transaccion
		$this->setParametro('pes_estado','pes_estado','varchar');	
		$this->setParametro('id_usuario', 'id_usuario','int4');
		//Definicion de la lista del resultado del query
		$this->captura('id_cuestionario_funcionario','int4');
		$this->captura('estado_reg','varchar');
		$this->captura('id_cuestionario','int4');
		$this->captura('id_funcionario','int4');
		$this->captura('id_usuario_reg','int4');		
		$this->captura('cuestionario','varchar');
		
		$this->captura('fecha_reg','timestamp');		
		$this->captura('id_usuario_ai','int4');
		$this->captura('usuario_ai','varchar');		
		$this->captura('fecha_mod','timestamp');
		
		$this->captura('desc_person','varchar');
		$this->captura('codigo','varchar');
		$this->captura('cuenta','varchar');
		$this->captura('sw_final','varchar');	
		//Ejecuta la instruccion
		$this->armarConsulta();
		$this->ejecutarConsulta();
		
		//Devuelve la respuesta
		return $this->respuesta;
	}
	//
	function reporteCuestionario(){
		//Definicion de variables para ejecucion del procedimientp
		$this->procedimiento='ssig.ft_cuestionario_sel';
		$this->transaccion='SSIG_RLISCU_SEL';
		$this->tipo_procedimiento='SEL';//tipo de transaccion
		$this-> setCount(false);		
		$this->setParametro('id_funcionario', 'id_funcionario','int4');
		$this->setParametro('id_cuestionario', 'id_cuestionario', 'int4');		
		//Definicion de la lista del resultado del query		
		$this->captura('id_temporal','int4');
		$this->captura('id_pregunta','int4');
		$this->captura('pregunta','varchar');
		$this->captura('tipo','varchar');
		$this->captura('respuesta','varchar');
		$this->captura('id_cuestionario','int4');		
		$this->captura('id_categoria','int4');
        $this->captura('id_usuario_reg','int4');
		$this->captura('sw_nivel','int4');
		$this->captura('cuestionario','varchar');
		$this->captura('peso','numeric');
		//Ejecuta la instruccion
		$this->armarConsulta();
		$this->ejecutarConsulta();
		
		//Devuelve la respuesta
		return $this->respuesta;
	}	
}
?>