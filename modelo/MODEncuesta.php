<?php
/**
*@package pXP
*@file gen-MODEncuesta.php
*@author  (admin.miguel)
*@date 29-04-2020 06:10:09
*@description Clase que envia los parametros requeridos a la Base de datos para la ejecucion de las funciones, y que recibe la respuesta del resultado de la ejecucion de las mismas
 HISTORIAL DE MODIFICACIONES:
#ISSUE				FECHA				AUTOR				DESCRIPCION
 #0				29-04-2020 06:10:09								CREACION

*/

class MODEncuesta extends MODbase{
	
	function __construct(CTParametro $pParam){
		parent::__construct($pParam);
	}
			
	function listarEncuesta(){
		//Definicion de variables para ejecucion del procedimientp
		$this->procedimiento='ssig.ft_encuesta_sel';
		$this->transaccion='SSIG_ETA_SEL';
		$this->tipo_procedimiento='SEL';//tipo de transaccion
				
		//Definicion de la lista del resultado del query
		$this->captura('id_encuesta','int4');
		$this->captura('estado_reg','varchar');
		$this->captura('obs_dba','varchar');
		$this->captura('nro_order','varchar');
		$this->captura('nombre','varchar');
		$this->captura('grupo','varchar');
		$this->captura('categoria','varchar');
		$this->captura('habilitado_categoria','bool');
		$this->captura('peso_categoria','numeric');
		$this->captura('pregunta','varchar');
		$this->captura('habilitado_pregunta','bool');
		$this->captura('tipo_pregunta','varchar');
		$this->captura('id_encuesta_padre','int4');
		$this->captura('id_usuario_reg','int4');
		$this->captura('fecha_reg','timestamp');
		$this->captura('id_usuario_ai','int4');
		$this->captura('usuario_ai','varchar');
		$this->captura('id_usuario_mod','int4');
		$this->captura('fecha_mod','timestamp');
		$this->captura('usr_reg','varchar');
		$this->captura('usr_mod','varchar');
		$this->captura('tipo','varchar');
		$this->captura('tipo_nombre','varchar');

		//Ejecuta la instruccion
		$this->armarConsulta();
		$this->ejecutarConsulta();
		
		//Devuelve la respuesta
		return $this->respuesta;
	}
			
	function insertarEncuesta(){
		//Definicion de variables para ejecucion del procedimiento
		$this->procedimiento='ssig.ft_encuesta_ime';
		$this->transaccion='SSIG_ETA_INS';
		$this->tipo_procedimiento='IME';
				
		//Define los parametros para la funcion
		$this->setParametro('estado_reg','estado_reg','varchar');
		$this->setParametro('obs_dba','obs_dba','varchar');
		$this->setParametro('nro_order','nro_order','varchar');
		$this->setParametro('nombre','nombre','varchar');
		$this->setParametro('grupo','grupo','varchar');
		$this->setParametro('categoria','categoria','varchar');
		$this->setParametro('habilitado_categoria','habilitado_categoria','bool');
		$this->setParametro('peso_categoria','peso_categoria','numeric');
		$this->setParametro('pregunta','pregunta','varchar');
		$this->setParametro('habilitado_pregunta','habilitado_pregunta','bool');
		$this->setParametro('tipo_pregunta','tipo_pregunta','varchar');
		$this->setParametro('id_encuesta_padre','id_encuesta_padre','varchar');
        $this->setParametro('tipo','tipo','varchar');

		//Ejecuta la instruccion
		$this->armarConsulta();
		$this->ejecutarConsulta();

		//Devuelve la respuesta
		return $this->respuesta;
	}
			
	function modificarEncuesta(){
		//Definicion de variables para ejecucion del procedimiento
		$this->procedimiento='ssig.ft_encuesta_ime';
		$this->transaccion='SSIG_ETA_MOD';
		$this->tipo_procedimiento='IME';
				
		//Define los parametros para la funcion
		$this->setParametro('id_encuesta','id_encuesta','int4');
		$this->setParametro('estado_reg','estado_reg','varchar');
		$this->setParametro('obs_dba','obs_dba','varchar');
		$this->setParametro('nro_order','nro_order','varchar');
		$this->setParametro('nombre','nombre','varchar');
		$this->setParametro('grupo','grupo','varchar');
		$this->setParametro('categoria','categoria','varchar');
		$this->setParametro('habilitado_categoria','habilitado_categoria','bool');
		$this->setParametro('peso_categoria','peso_categoria','numeric');
		$this->setParametro('pregunta','pregunta','varchar');
		$this->setParametro('habilitado_pregunta','habilitado_pregunta','bool');
		$this->setParametro('tipo_pregunta','tipo_pregunta','varchar');
		$this->setParametro('id_encuesta_padre','id_encuesta_padre','varchar');
        $this->setParametro('tipo','tipo','varchar');

		//Ejecuta la instruccion
		$this->armarConsulta();
		$this->ejecutarConsulta();

		//Devuelve la respuesta
		return $this->respuesta;
	}
			
	function eliminarEncuesta(){
		//Definicion de variables para ejecucion del procedimiento
		$this->procedimiento='ssig.ft_encuesta_ime';
		$this->transaccion='SSIG_ETA_ELI';
		$this->tipo_procedimiento='IME';
				
		//Define los parametros para la funcion
		$this->setParametro('id_encuesta','id_encuesta','int4');

		//Ejecuta la instruccion
		$this->armarConsulta();
		$this->ejecutarConsulta();

		//Devuelve la respuesta
		return $this->respuesta;
	}
    function listarEncuestaArb(){
        //Definicion de variables para ejecucion del procedimientp
        $this->procedimiento='ssig.ft_encuesta_sel';
        $this->transaccion='SSIG_ETAR_SEL';
        $this->tipo_procedimiento='SEL';//tipo de transaccion
        $this-> setCount(false);

        $this->setParametro('node','node','varchar');

        $id_padre = $this->objParam->getParametro('id_padre');
        $this->setParametro('id_padre', 'id_padre', 'varchar');

        //Definicion de la lista del resultado del query
        $this->captura('id_encuesta','int4');
        $this->captura('estado_reg','varchar');
        $this->captura('obs_dba','varchar');
        $this->captura('nro_order','varchar');
        $this->captura('nombre','varchar');
        $this->captura('grupo','varchar');
        $this->captura('categoria','varchar');
        $this->captura('habilitado_categoria','bool');
        $this->captura('peso_categoria','numeric');
        $this->captura('pregunta','varchar');
        $this->captura('habilitado_pregunta','bool');
        $this->captura('tipo_pregunta','varchar');
        $this->captura('id_encuesta_padre','int4');
        $this->captura('id_usuario_reg','int4');
        $this->captura('fecha_reg','timestamp');
        $this->captura('id_usuario_ai','int4');
        $this->captura('usuario_ai','varchar');
        $this->captura('id_usuario_mod','int4');
        $this->captura('fecha_mod','timestamp');
        $this->captura('usr_reg','varchar');
        $this->captura('usr_mod','varchar');
        $this->captura('tipo','varchar');
        $this->captura('tipo_nombre','varchar');
        //Ejecuta la instruccion
        $this->armarConsulta();
        $this->ejecutarConsulta();

        //Devuelve la respuesta
        return $this->respuesta;
    }
			
}
?>