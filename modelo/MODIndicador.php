<?php
/**
*@package pXP
*@file gen-MODIndicador.php
*@author  (admin)
*@date 21-11-2016 14:51:35
*@description Clase que envia los parametros requeridos a la Base de datos para la ejecucion de las funciones, y que recibe la respuesta del resultado de la ejecucion de las mismas
*/

class MODIndicador extends MODbase{
	
	function __construct(CTParametro $pParam){
		parent::__construct($pParam);
	}
			
	function listarIndicador(){
		//Definicion de variables para ejecucion del procedimientp
		$this->procedimiento='ssig.ft_indicador_sel';
		$this->transaccion='SSIG_IND_SEL';
		$this->tipo_procedimiento='SEL';//tipo de transaccion
				
		//Definicion de la lista del resultado del query
		$this->captura('id_indicador','int4');	
		$this->captura('id_indicador_unidad','int4');
		$this->captura('id_indicador_frecuencia','int4');
		$this->captura('id_gestion','int4');
		$this->captura('num_decimal','int4');
		$this->captura('semaforo','varchar');
		$this->captura('estado_reg','varchar');
		$this->captura('sigla','varchar');
		$this->captura('descipcion','varchar');
		$this->captura('comparacion','varchar');
		$this->captura('indicador','varchar');
		$this->captura('usuario_ai','varchar');
		$this->captura('fecha_reg','timestamp');
		$this->captura('id_usuario_reg','int4');
		$this->captura('id_usuario_ai','int4');
		$this->captura('fecha_mod','timestamp');
		$this->captura('id_usuario_mod','int4');
		$this->captura('usr_reg','varchar');
		$this->captura('usr_mod','varchar');
	    $this->captura('unidad','varchar');
		$this->captura('tipo','varchar');
	    $this->captura('frecuencia','varchar');
	    $this->captura('gestion','int4');
		
		$this->captura('registro_completado','int4');
        

		
	//	$this->captura('fecha','date');
	//	$this->captura('hito','varchar');
	//	$this->captura('semaforo1','varchar');
	//	$this->captura('semaforo2','varchar');
	//	$this->captura('semaforo3','varchar');
	//	$this->captura('semaforo4','varchar');
	//	$this->captura('semaforo5','varchar');
	//	$this->captura('valor','varchar');
	//    $this->captura('justificacion','varchar');
	//    $this->captura('no_reporta','bit');
	//	$this->captura('id_indicador_valor','int4');
		$this->captura('id_funcionario_ingreso','int4');
		$this->captura('desc_person','varchar');
		$this->captura('id_funcionario_evaluacion','int4');
		$this->captura('desc_person2','varchar');

		$this->captura('orden_sigla','varchar');
		//Ejecuta la instruccion
		$this->armarConsulta();
		$this->ejecutarConsulta();
		
		//Devuelve la respuesta
		return $this->respuesta;
	}
			
	function insertarIndicador(){
		//Definicion de variables para ejecucion del procedimiento
		$this->procedimiento='ssig.ft_indicador_ime';
		$this->transaccion='SSIG_IND_INS';
		$this->tipo_procedimiento='IME';
				
		//Define los parametros para la funcion
		$this->setParametro('id_indicador_unidad','id_indicador_unidad','int4');
		$this->setParametro('id_indicador_frecuencia','id_indicador_frecuencia','int4');
		$this->setParametro('id_gestion','id_gestion','int4');
		$this->setParametro('num_decimal','num_decimal','int4');
		$this->setParametro('semaforo','semaforo','varchar');
		$this->setParametro('estado_reg','estado_reg','varchar');
		$this->setParametro('sigla','sigla','varchar');
		$this->setParametro('descipcion','descipcion','varchar');
		$this->setParametro('comparacion','comparacion','varchar');
		$this->setParametro('indicador','indicador','varchar');
		
		$this->setParametro('id_funcionario_ingreso','id_funcionario_ingreso','int4');
		$this->setParametro('id_funcionario_evaluacion','id_funcionario_evaluacion','int4');
		//Ejecuta la instruccion
		$this->armarConsulta();
		$this->ejecutarConsulta();

		//Devuelve la respuesta
		return $this->respuesta;
	}
			
	function modificarIndicador(){
		//Definicion de variables para ejecucion del procedimiento
		$this->procedimiento='ssig.ft_indicador_ime';
		$this->transaccion='SSIG_IND_MOD';
		$this->tipo_procedimiento='IME';
				
		//Define los parametros para la funcion
		$this->setParametro('id_indicador','id_indicador','int4');
		$this->setParametro('id_indicador_unidad','id_indicador_unidad','int4');
		$this->setParametro('id_indicador_frecuencia','id_indicador_frecuencia','int4');
		$this->setParametro('id_gestion','id_gestion','int4');
		$this->setParametro('num_decimal','num_decimal','int4');
		$this->setParametro('semaforo','semaforo','varchar');
		$this->setParametro('estado_reg','estado_reg','varchar');
		$this->setParametro('sigla','sigla','varchar');
		$this->setParametro('descipcion','descipcion','varchar');
		$this->setParametro('comparacion','comparacion','varchar');
		$this->setParametro('indicador','indicador','varchar');
		
		//$this->setParametro('fecha','fecha','date');
		//$this->setParametro('hito','hito','varchar');
		//$this->setParametro('semaforo1','semaforo1','varchar');
		//$this->setParametro('semaforo2','semaforo2','varchar');
		//$this->setParametro('semaforo3','semaforo3','varchar');
		//$this->setParametro('semaforo4','semaforo4','varchar');
		//$this->setParametro('semaforo5','semaforo5','varchar');
		//$this->setParametro('valor','valor','varchar');
		//$this->setParametro('justificacion','justificacion','varchar');
		//$this->setParametro('no_reporta','no_reporta','bit');
		//$this->setParametro('id_indicador_valor','id_indicador_valor','int4');
		$this->setParametro('id_funcionario_ingreso','id_funcionario_ingreso','int4');
		$this->setParametro('id_funcionario_evaluacion','id_funcionario_evaluacion','int4');
		//Ejecuta la instruccion
		$this->armarConsulta();
		$this->ejecutarConsulta();

		//Devuelve la respuesta
		return $this->respuesta;
	}
			
	function eliminarIndicador(){
		//Definicion de variables para ejecucion del procedimiento
		$this->procedimiento='ssig.ft_indicador_ime';
		$this->transaccion='SSIG_IND_ELI';
		$this->tipo_procedimiento='IME';
				
		//Define los parametros para la funcion
		$this->setParametro('id_indicador','id_indicador','int4');

		//Ejecuta la instruccion
		$this->armarConsulta();
		$this->ejecutarConsulta();

		//Devuelve la respuesta
		return $this->respuesta;
	}
    function aprobarGestion()
    {
        //Definicion de variables para ejecucion del procedimiento
        $this->procedimiento = 'ssig.ft_indicador_ime';
        $this->transaccion = 'SSIG_AGESTION';
        $this->tipo_procedimiento = 'IME';

        //Define los parametros para la funcion
        $this->setParametro('id_gestion', 'id_gestion', 'int4');
		$this->setParametro('estado', 'estado', 'bool');

        //Ejecuta la instruccion
        $this->armarConsulta();
        $this->ejecutarConsulta();

        //Devuelve la respuesta
        return $this->respuesta;
    }
	function estadoGestion()
    {
        //Definicion de variables para ejecucion del procedimiento
        $this->procedimiento = 'ssig.ft_indicador_ime';
        $this->transaccion = 'SSIG_ESTADO_GESTION';
        $this->tipo_procedimiento = 'IME';

        //Define los parametros para la funcion
        $this->setParametro('id_gestion', 'id_gestion', 'int4');

        //Ejecuta la instruccion
        $this->armarConsulta();
        $this->ejecutarConsulta();

        //Devuelve la respuesta
        return $this->respuesta;
    }
	function verEstadoIndicador()
    {
        //Definicion de variables para ejecucion del procedimiento
        $this->procedimiento = 'ssig.ft_indicador_ime';
        $this->transaccion = 'SSIG_EGINDICADOR';
        $this->tipo_procedimiento = 'IME';

        //Define los parametros para la funcion
        $this->setParametro('id_gestion', 'id_gestion', 'int4');

        //Ejecuta la instruccion
        $this->armarConsulta();
        $this->ejecutarConsulta();

        //Devuelve la respuesta
        return $this->respuesta;
    }
			
}
?>