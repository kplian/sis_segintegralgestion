<?php
/**
*@package pXP
*@file gen-MODLineaAvance.php
*@author  (admin)
*@date 19-02-2017 02:21:07
*@description Clase que envia los parametros requeridos a la Base de datos para la ejecucion de las funciones, y que recibe la respuesta del resultado de la ejecucion de las mismas
*/


class MODLineaAvance extends MODbase{
	
	function __construct(CTParametro $pParam){
		parent::__construct($pParam);
	}	
	function listarLineaAvance(){
		//Definicion de variables para ejecucion del procedimientp
		$this->procedimiento='ssig.ft_linea_avance_sel';
		$this->transaccion='SSIG_LIAV_SEL';
		
		$this->tipo_procedimiento='SEL';//tipo de transaccion
		
		$this->setParametro('id_plan','id_plan','int4');

		$this->captura('id_linea_avance','int4');
		$this->captura('id_linea','int4');
		$this->captura('nombre_linea','varchar');
		$this->captura('peso','numeric');
		$this->captura('peso_acumulado','varchar');
		$this->captura('peso_restante','varchar');
		$this->captura('id_funcionarios','varchar');
		$this->captura('funcionarios','varchar');
		$this->captura('mes','varchar');
		$this->captura('avance_previsto','varchar');
		$this->captura('avance_real','varchar');
		$this->captura('comentario','varchar');
	    $this->captura('aprobado_real','bool');
		
		$this->captura('estado_reg','varchar');
		$this->captura('id_usuario_ai','int4');
		$this->captura('usuario_ai','varchar');
		$this->captura('fecha_reg','timestamp');
		$this->captura('id_usuario_mod','int4');
		$this->captura('fecha_mod','timestamp');
		$this->captura('usr_reg','varchar');
		$this->captura('usr_mod','varchar');
		$this->captura('nivel','int4');
	    $this->captura('linea_padre','varchar');
		$this->captura('id_linea_padre','int4');
		$this->captura('id_plan','int4');
		$this->captura('orden','varchar');
		$this->captura('orden_logico','int4');
		//Ejecuta la instruccion
		$this->armarConsulta();
		$this->ejecutarConsulta();
		
		//Devuelve la respuesta
		return $this->respuesta;
	}
	function listarLineaAvance_ordenado(){
		//Definicion de variables para ejecucion del procedimientp
		$this->procedimiento='ssig.ft_linea_avance_sel';
		$this->transaccion='SSIG_LINIAS_SEL';
		
		$this->tipo_procedimiento='SEL';//tipo de transaccion
		
		$this->setParametro('id_plan','id_plan','int4');



		$this->captura('id_linea_temporal','int4');
		//$this->captura('id_linea_avance','int4');
		$this->captura('id_linea','int4');
		$this->captura('nombre_linea','varchar');
		$this->captura('peso','numeric');
		$this->captura('peso_acumulado','varchar');
		$this->captura('peso_restante','varchar');
		$this->captura('id_funcionarios','varchar');
		$this->captura('funcionarios','varchar');
		

		//$this->captura('mes','varchar');
		//$this->captura('avance_previsto','varchar');
		//$this->captura('avance_real','varchar');
		//$this->captura('comentario','varchar');
	    //$this->captura('aprobado_real','bool');	
		

		$this->captura('estado_reg','varchar');
		$this->captura('id_usuario_ai','int4');
		$this->captura('usuario_ai','varchar');
		$this->captura('fecha_reg','timestamp');
		$this->captura('id_usuario_mod','int4');
		$this->captura('fecha_mod','timestamp');
		$this->captura('usr_reg','varchar');
		$this->captura('usr_mod','varchar');
		$this->captura('nivel','int4');
	    $this->captura('linea_padre','varchar');
		$this->captura('id_linea_padre','int4');
		$this->captura('id_plan','int4');
		$this->captura('orden_logico','int4');
		//Ejecuta la instruccion
		$this->armarConsulta();
		$this->ejecutarConsulta();
		

		//Devuelve la respuesta
		return $this->respuesta;
	}

    function listarLineaAvanceDinamico(){
	    $this->procedimiento='ssig.ft_linea_avance_sel';
		$this->transaccion='SSIG_LADINA_SEL';
		
		$this->tipo_procedimiento='SEL';//tipo de transaccion
		
		$this->setParametro('id_plan','id_plan','int4');
		
    
		$datos = $this->objParam->getParametro('datos');
		
        $this->captura('id_linea_avance','int4');                                   
		$this->captura('id_linea','int4');
		$this->captura('nombre_linea','varchar');
		$this->captura('peso','numeric');
		$this->captura('peso_acumulado','int4');
		$this->captura('peso_restante','int4');
		$this->captura('nivel','int4');
		$this->captura('linea_padre','varchar');
		$this->captura('cod_linea_padre','int4');
		$this->captura('id_plan','int4');
		$this->captura('id_usuario_reg','int4');
		//$this->captura('fecha_reg','timestamp');
		//$this->captura('estado_reg','varchar');
		
		
		$arrayMeses= explode('@',$datos);
		$tamaño = sizeof($arrayMeses);

		for($i=1;$i<$tamaño;$i++){
		
			$this->captura($arrayMeses[$i],'varchar');
			
			if($i!=$tamaño-1){
			   $this->captura('id_lavance'.$i.'','int4');
		    }
		}

		
		$this->captura('aprobado_real','bool');
	    $this->captura('cod_hijos','varchar');
		$this->captura('cod_linea','varchar');
		

		
		//Ejecuta la instruccion
		$this->armarConsulta();
		$this->ejecutarConsulta();
		
		//Devuelve la respuesta
		return $this->respuesta;
    }
			
	function insertarLineaAvance(){
		$this->procedimiento='ssig.ft_linea_avance_ime';
		$this->transaccion='SSIG_LIAV_MOD';
		$this->tipo_procedimiento='IME';
				
		//Define los parametros para la funcion
        $datos = $this->objParam->getParametro('datos');
		$this->setParametro('id_plan','id_plan','int4');
		$this->setParametro('id_linea_avance','id_linea_avance','int4');
		$this->setParametro('id_linea','id_linea','int4');
		$this->setParametro('nombre_linea','nombre_linea','varchar');
		$this->setParametro('peso','peso','numeric');
		$this->setParametro('peso_acumulado','peso_acumulado','int4');
		$this->setParametro('peso_restante','peso_restante','int4');
        $this->setParametro('nivel','nivel','int4');
		$this->setParametro('linea_padre','linea_padre','varchar');
		$this->setParametro('id_linea_padre','id_linea_padre','int4');
		$this->setParametro('id_usuario_reg','id_usuario_reg','int4');

        $this->setParametro('orden_logico','orden_logico','int4');

		
		$aux = $this->objParam->getParametro(0);
		$datos = $aux['datos'];
		
		//var_dump($this->objParam);
		//var_dump($datos);exit;	
		$arrayMeses= explode('@',$datos);
		$tamaño = sizeof($arrayMeses);
		
		for($i=1;$i<$tamaño;$i++){			
			$this->setParametro($arrayMeses[$i],$arrayMeses[$i],'varchar');
			if($i!=$tamaño-1){
			   $this->captura('id_lavance'.$i.'','int4');
			   $this->setParametro('id_lavance'.$i,'id_lavance'.$i,'int4');
		    }
		}
        //$this->setParametro('aprobado_real','aprobado_real','bool');
		$this->setParametro('id_linea_avance_temporal','id_linea_avance_temporal','int4');
		//Ejecuta la instruccion
		$this->armarConsulta();
		$this->ejecutarConsulta();

		//Devuelve la respuesta
		return $this->respuesta;
	}
			
	function modificarLineaAvance(){
		//Definicion de variables para ejecucion del procedimiento
		$this->procedimiento='ssig.ft_linea_avance_ime';
		$this->transaccion='SSIG_LIAV_MOD';
		$this->tipo_procedimiento='IME';
				
		//Define los parametros para la funcion

		$this->setParametro('id_plan','id_plan','int4');
		$this->setParametro('id_linea_avance','id_linea_avance','int4');
		$this->setParametro('id_linea','id_linea','int4');
		$this->setParametro('nombre_linea','nombre_linea','varchar');
		$this->setParametro('peso','peso','numeric');
		$this->setParametro('peso_acumulado','peso_acumulado','int4');
		$this->setParametro('peso_restante','peso_restante','int4');
        $this->setParametro('nivel','nivel','int4');
		$this->setParametro('linea_padre','linea_padre','varchar');
		$this->setParametro('id_linea_padre','id_linea_padre','int4');
		$this->setParametro('id_usuario_reg','id_usuario_reg','int4');

        $this->datos = $this->objParam->getParametro('datos');
		
		$arrayMeses= explode('@',$datos);
		$tamaño = sizeof($arrayMeses);
		
		for($i=1;$i<$tamaño;$i++){			
			$this->setParametro($arrayMeses[$i],$arrayMeses[$i],'varchar');
		}
	    $this->captura('aprobado_real','bool');
	
					
		
		

		//Ejecuta la instruccion
		$this->armarConsulta();
		$this->ejecutarConsulta();

		//Devuelve la respuesta
		return $this->respuesta;
	}
			
	function eliminarLineaAvance(){
		//Definicion de variables para ejecucion del procedimiento
		$this->procedimiento='ssig.ft_linea_avance_ime';
		$this->transaccion='SSIG_LIAV_ELI';
		$this->tipo_procedimiento='IME';
				
		//Define los parametros para la funcion
		//$this->setParametro('id_linea_avance','id_linea_avance','int4');
		$this->setParametro('id_linea','id_linea','int4');

		//Ejecuta la instruccion
		$this->armarConsulta();
		$this->ejecutarConsulta();

		//Devuelve la respuesta
		return $this->respuesta;
	}
	function GenerarColumnaMeses(){
		
		$this->procedimiento='ssig.ft_linea_avance_ime';
		$this->transaccion='SSIG_CD_LAVANCE';
		
		$this->tipo_procedimiento='IME';
		
		
				
		$this->setParametro('id_plan','id_plan','int4');

		//Ejecuta la instruccion
		$this->armarConsulta();
		$this->ejecutarConsulta();

		//Devuelve la respuesta
		return $this->respuesta;
	}
	function listarAvanceReal(){
		//Definicion de variables para ejecucion del procedimientp
		$this->procedimiento='ssig.ft_linea_avance_sel';
		$this->transaccion='SSIG_AREAL_SEL';
		
		$this->tipo_procedimiento='SEL';//tipo de transaccion
		
		$this->setParametro('mes','mes','varchar');
		//$this->setParametro('id_plan','id_plan','int4');
		//var_dump("modelo juan ".$this->setParametro('id_plan','id_plan','int4'));exit;	

		$this->captura('id_linea','int4');
		$this->captura('nombre_linea','varchar');
		$this->captura('peso','numeric');
		$this->captura('nivel','int4');
		$this->captura('linea_padre','varchar');
		$this->captura('id_linea_padre','int4');
		$this->captura('id_plan','int4');
		$this->captura('id_linea_avance','int4');
		$this->captura('avance_previsto','numeric');
		$this->captura('avance_real','numeric');
		$this->captura('acumulado_previsto','numeric');
		$this->captura('acumulado_real','numeric');
		$this->captura('desviacion','numeric');
		$this->captura('comentario','varchar');
		$this->captura('aprobado_real','bool');
		
		$this->captura('estado_reg','varchar');
		$this->captura('id_usuario_ai','int4');
		$this->captura('usuario_ai','varchar');
		$this->captura('fecha_reg','timestamp');
		$this->captura('usr_reg','varchar');
		$this->captura('id_usuario_reg','int4');
		$this->captura('cod_hijos','varchar');
		$this->captura('cod_linea','int4');
		$this->captura('cod_linea_padre','int4');
		
		$this->captura('dato','varchar');
		

		//Ejecuta la instruccion
		$this->armarConsulta();
		$this->ejecutarConsulta();
		
		//Devuelve la respuesta
		return $this->respuesta;
	}	
	function listarMeses(){
		//Definicion de variables para ejecucion del procedimientp
		$this->procedimiento='ssig.ft_linea_avance_sel';
		$this->transaccion='SSIG_LMESES_SEL';
		
		$this->tipo_procedimiento='SEL';//tipo de transaccion
		
		//$datos = $this->objParam->getParametro('id_plan');
		//var_dump("testear planes ",$this->objParam->getParametro('cod_plan'));exit;	
        $this->setParametro('id_plan','id_plann','int4');

		$this->captura('id_linea_avance','int4');
		$this->captura('mes','varchar');
		$this->captura('aprobado_real','bool');
		$this->captura('cod_linea_avance','int4');


		//Ejecuta la instruccion
		$this->armarConsulta();
		$this->ejecutarConsulta();
		
		//Devuelve la respuesta
		return $this->respuesta;
	}
	function insertarAvanceReal(){
		$this->procedimiento='ssig.ft_linea_avance_ime';
		$this->transaccion='SSIG_APREVISTO_INS';
		$this->tipo_procedimiento='IME';
				
		$this->setParametro('id_linea_avance','id_linea_avance','int4');

		$this->setParametro('avance_real','avance_real','numeric');
		$this->setParametro('comentario','comentario','varchar');
		$this->setParametro('dato','dato','varchar');
		

		//Ejecuta la instruccion
		$this->armarConsulta();
		$this->ejecutarConsulta();

		//Devuelve la respuesta
		return $this->respuesta;	
	}
	function EstadoAvanceReal(){

		
		$this->procedimiento='ssig.ft_linea_avance_ime';
		$this->transaccion='SSIG_ES_APREV_INS';
		$this->tipo_procedimiento='IME';

        //Define los parametros para la funcion
        //$this->setParametro('id_linea_avance','id_linea_avance','int4');
		$this->setParametro('id_plan','id_plan','int4');
		$this->setParametro('mes','mes','varchar');

        //Ejecuta la instruccion
        $this->armarConsulta();
        $this->ejecutarConsulta();

        //Devuelve la respuesta
        return $this->respuesta;
		
	}
	function aprobarAvanceReal(){
		
		$this->procedimiento='ssig.ft_linea_avance_ime';
		$this->transaccion='SSIG_AP_LAVANCE_INS';
		$this->tipo_procedimiento='IME';

        //Define los parametros para la funcion
        //$this->setParametro('id_linea_avance','id_linea_avance','int4');
		$this->setParametro('id_plan','id_plan','int4');
		$this->setParametro('mes','mes','varchar');
		$this->setParametro('estado','estado','varchar');


        //Ejecuta la instruccion
        $this->armarConsulta();
        $this->ejecutarConsulta();

        //Devuelve la respuesta
        return $this->respuesta;
	}
	

}
?>