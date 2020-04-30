<?php
/**
*@package pXP
*@file gen-MODTemporal.php
*@author  (mguerra)
*@date 24-04-2020 00:16:08
*@description Clase que envia los parametros requeridos a la Base de datos para la ejecucion de las funciones, y que recibe la respuesta del resultado de la ejecucion de las mismas
 HISTORIAL DE MODIFICACIONES:
#ISSUE				FECHA				AUTOR				DESCRIPCION
 #0				24-04-2020 00:16:08								CREACION

*/

class MODTemporal extends MODbase{
	
	function __construct(CTParametro $pParam){
		parent::__construct($pParam);
	}
			
	function listarTemporal(){
		//Definicion de variables para ejecucion del procedimientp
		$this->procedimiento='ssig.ft_cuestionario_sel';
		$this->transaccion='SSIG_LISCUE_SEL';
		$this->tipo_procedimiento='SEL';//tipo de transaccion
				
		$this->setParametro('id_usuario', 'id_usuario','int4');
		$this->setParametro('id_cuestionario', 'id_cuestionario', 'int4');		
		$this->setParametro('id_funcionario', 'id_funcionario', 'int4');	
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

		$this->captura('id_funcionario','int4');
		//Ejecuta la instruccion
		$this->armarConsulta();
		$this->ejecutarConsulta();
		
		//Devuelve la respuesta
		return $this->respuesta;
	}
			
	function insertarTemporal(){
		//Definicion de variables para ejecucion del procedimiento
		$this->procedimiento='ssig.ft_cuestionario_IME';
		$this->transaccion='SSIG_SAVCUE_INS';
		$this->tipo_procedimiento='IME';
				
		//Define los parametros para la funcion		
		$this->setParametro('id_temporal','id_temporal','int4');
		$this->setParametro('pregunta','pregunta','varchar');
		$this->setParametro('sw_nivel','sw_nivel','int4');
		$this->setParametro('tipo','tipo','varchar');
		$this->setParametro('respuesta','respuesta','varchar');
		$this->setParametro('id_cuestionario','id_cuestionario','int4');
		$this->setParametro('id_categoria','id_categoria','int4');
		$this->setParametro('id_pregunta','id_pregunta','int4');
		$this->setParametro('id_usuario_reg','id_usuario_reg','int4');
		$this->setParametro('id_usuario', 'id_usuario', 'int4');

		$this->setParametro('id_funcionario', 'id_funcionario', 'int4');
		//Ejecuta la instruccion
		$this->armarConsulta();
		$this->ejecutarConsulta();

		//Devuelve la respuesta
		return $this->respuesta;
	}
			
	function modificarTemporal(){
		//Definicion de variables para ejecucion del procedimiento
		$this->procedimiento='ssig.ft_temporal_ime';
		$this->transaccion='SSIG_TMP_MOD';
		$this->tipo_procedimiento='IME';
				
		//Define los parametros para la funcion
		$this->setParametro('id_temporal','id_temporal','int4');
		$this->setParametro('id_pregunta','id_pregunta','int4');
		$this->setParametro('pregunta','pregunta','varchar');
		$this->setParametro('respuesta','respuesta','varchar');
		$this->setParametro('id_cuestionario','id_cuestionario','int4');
		$this->setParametro('id_categoria','id_categoria','int4');

		//Ejecuta la instruccion
		$this->armarConsulta();
		$this->ejecutarConsulta();

		//Devuelve la respuesta
		return $this->respuesta;
	}
			
	function eliminarTemporal(){
		//Definicion de variables para ejecucion del procedimiento
		$this->procedimiento='ssig.ft_temporal_ime';
		$this->transaccion='SSIG_TMP_ELI';
		$this->tipo_procedimiento='IME';
				
		//Define los parametros para la funcion
		$this->setParametro('id_temporal','id_temporal','int4');

		//Ejecuta la instruccion
		$this->armarConsulta();
		$this->ejecutarConsulta();

		//Devuelve la respuesta
		return $this->respuesta;
	}
	
	function insertarCuestionario(){
		//Definicion de variables para ejecucion del procedimiento
		$this->procedimiento='sigefo.ft_curso_ime';
		$this->transaccion='CUESTIO_INS';
		$this->tipo_procedimiento='IME';
				

        $this->setParametro('tipo_cuestionario', 'tipo_cuestionario', 'varchar');
		$this->setParametro('id_proveedor', 'id_proveedor', 'int4');
		
		$this->setParametro('id_usuario', 'id_usuario', 'int4');
		$this->setParametro('id_curso', 'id_curso', 'int4');
		
		$this->setParametro('id_temporal','id_temporal','int4');
		$this->setParametro('id_pregunta','id_pregunta','int4');
		
		$this->setParametro('pregunta','pregunta','varchar');
		$this->setParametro('respuesta','respuesta','varchar');
		$this->setParametro('tipo','tipo','varchar');
		
		$this->setParametro('nivel','nivel','varchar');
		$this->setParametro('id_usuario_reg','id_usuario_reg','int4');

		//Ejecuta la instruccion
		$this->armarConsulta();
		$this->ejecutarConsulta();

		//Devuelve la respuesta
		return $this->respuesta;
	}		
}
?>