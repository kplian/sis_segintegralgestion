<?php
/**
*@package pXP
*@file gen-MODAgrupadorIndicador.php
*@author  (admin)
*@date 08-06-2017 10:36:34
*@description Clase que envia los parametros requeridos a la Base de datos para la ejecucion de las funciones, y que recibe la respuesta del resultado de la ejecucion de las mismas
*/

class MODAgrupadorIndicador extends MODbase{
	
	////
	function __construct(CTParametro $pParam){
		parent::__construct($pParam);
	}
			
	function listarAgrupadorIndicador(){
		//Definicion de variables para ejecucion del procedimientp
		$this->procedimiento='ssig.ft_agrupador_indicador_sel';
		$this->transaccion='SSIG_AGIN_SEL';
		$this->tipo_procedimiento='SEL';//tipo de transaccion
		
        $this->setParametro('id_periodo', 'id_periodo', 'int4');	
		//$this->setParametro('id_agrupador', 'id_agrupador', 'int4');				
		//Definicion de la lista del resultado del query
		$this->captura('id_agrupador_indicador','int4');
		$this->captura('id_agrupador','int4');
		$this->captura('id_indicador','int4');
		/*$this->captura('id_funcionario_ingreso','int4');
		$this->captura('desc_person','varchar');
		$this->captura('id_funcionario_evaluacion','int4');
		$this->captura('desc_person2','varchar');*/
		$this->captura('peso','int4');
		$this->captura('estado_reg','varchar');
		$this->captura('id_usuario_ai','int4');
		$this->captura('fecha_reg','timestamp');
		$this->captura('usuario_ai','varchar');
		$this->captura('id_usuario_reg','int4');
		$this->captura('id_usuario_mod','int4');
		$this->captura('fecha_mod','timestamp');
		$this->captura('usr_reg','varchar');
		$this->captura('usr_mod','varchar');
		$this->captura('indicador','varchar');
		$this->captura('sigla','varchar');
		$this->captura('nombre_padre','varchar');
		//$this->captura('id_agrupador_indicador_padre','int4');
		$this->captura('totalidad','int4');
		$this->captura('resultado','numeric');
		$this->captura('semaforo1','varchar');
		$this->captura('semaforo2','varchar');
		$this->captura('semaforo3','varchar');
		$this->captura('semaforo4','varchar');
		$this->captura('semaforo5','varchar');
		$this->captura('valor_real','varchar');
		$this->captura('semaforo','varchar');
		$this->captura('comparacion','varchar');
		$this->captura('ruta_icono','varchar');
		$this->captura('justificacion','varchar');
		
		$this->captura('orden_sigla','varchar');
		$this->captura('orden_logico','int4');
		
		//Ejecuta la instruccion
		$this->armarConsulta();
		$this->ejecutarConsulta();
		
		//Devuelve la respuesta
		return $this->respuesta;
	}
			
	function insertarAgrupadorIndicador(){
		//Definicion de variables para ejecucion del procedimiento
		$this->procedimiento='ssig.ft_agrupador_indicador_ime';
		$this->transaccion='SSIG_AGIN_INS';
		$this->tipo_procedimiento='IME';
				
		//Define los parametros para la funcion
		$this->setParametro('id_agrupador','id_agrupador','int4');
		//$this->setParametro('id_agrupador_indicador_padre','id_agrupador_indicador_padre','int4');
		$this->setParametro('id_indicador','id_indicador','int4');
		$this->setParametro('id_funcionario_ingreso','id_funcionario_ingreso','int4');
		$this->setParametro('id_funcionario_evaluacion','id_funcionario_evaluacion','int4');
		$this->setParametro('peso','peso','int4');
		$this->setParametro('estado_reg','estado_reg','varchar');

        $this->setParametro('orden_logico','orden_logico','int4');
		//Ejecuta la instruccion
		$this->armarConsulta();
		$this->ejecutarConsulta();

		//Devuelve la respuesta
		return $this->respuesta;
	}
			
	function modificarAgrupadorIndicador(){
		//Definicion de variables para ejecucion del procedimiento
		$this->procedimiento='ssig.ft_agrupador_indicador_ime';
		$this->transaccion='SSIG_AGIN_MOD';
		$this->tipo_procedimiento='IME';
				
		//Define los parametros para la funcion
		$this->setParametro('id_agrupador_indicador','id_agrupador_indicador','int4');
		//$this->setParametro('id_agrupador_indicador_padre','id_agrupador_indicador_padre','int4');
		$this->setParametro('id_agrupador','id_agrupador','int4');
		$this->setParametro('id_indicador','id_indicador','int4');
		$this->setParametro('id_funcionario_ingreso','id_funcionario_ingreso','int4');
		$this->setParametro('id_funcionario_evaluacion','id_funcionario_evaluacion','int4');
		$this->setParametro('peso','peso','int4');
		$this->setParametro('estado_reg','estado_reg','varchar');

        $this->setParametro('orden_logico','orden_logico','int4');
		//Ejecuta la instruccion
		$this->armarConsulta();
		$this->ejecutarConsulta();

		//Devuelve la respuesta
		return $this->respuesta;
	}
			
	function eliminarAgrupadorIndicador(){
		//Definicion de variables para ejecucion del procedimiento
		$this->procedimiento='ssig.ft_agrupador_indicador_ime';
		$this->transaccion='SSIG_AGIN_ELI';
		$this->tipo_procedimiento='IME';
				
		//Define los parametros para la funcion
		$this->setParametro('id_agrupador_indicador','id_agrupador_indicador','int4');

		//Ejecuta la instruccion
		$this->armarConsulta();
		$this->ejecutarConsulta();

		//Devuelve la respuesta
		return $this->respuesta;
	}
			
}
?>