<?php
/**
*@package pXP
*@file gen-ACTCuestionario.php
*@author  (mguerra)
*@date 21-04-2020 08:31:41
*@description Clase que recibe los parametros enviados por la vista para mandar a la capa de Modelo
 HISTORIAL DE MODIFICACIONES:
#ISSUE				FECHA				AUTOR				DESCRIPCION
 #0				21-04-2020 08:31:41								CREACION

*/

class ACTCuestionario extends ACTbase{    
			
	function listarCuestionario(){
		$this->objParam->defecto('ordenacion','id_cuestionario');
		$this->objParam->defecto('dir_ordenacion','asc');

		if($this->objParam->getParametro('pes_estado')=='borrador'){
			$this->objParam->addFiltro("cue.estado in (''borrador'')");
	   	}
	   	if($this->objParam->getParametro('pes_estado')=='enviado'){
			$this->objParam->addFiltro("cue.estado in (''enviado'')");
	   	}
		
		if($this->objParam->getParametro('tipoReporte')=='excel_grid' || $this->objParam->getParametro('tipoReporte')=='pdf_grid'){
			$this->objReporte = new Reporte($this->objParam,$this);
			$this->res = $this->objReporte->generarReporteListado('MODCuestionario','listarCuestionario');
		} else{
			$this->objFunc=$this->create('MODCuestionario');
			
			$this->res=$this->objFunc->listarCuestionario($this->objParam);
		}
		$this->res->imprimirRespuesta($this->res->generarJson());
	}
				
	function insertarCuestionario(){
		$this->objFunc=$this->create('MODCuestionario');	
		if($this->objParam->insertar('id_cuestionario')){
			$this->res=$this->objFunc->insertarCuestionario($this->objParam);			
		} else{			
			$this->res=$this->objFunc->modificarCuestionario($this->objParam);
		}
		$this->res->imprimirRespuesta($this->res->generarJson());
	}
						
	function eliminarCuestionario(){
			$this->objFunc=$this->create('MODCuestionario');	
		$this->res=$this->objFunc->eliminarCuestionario($this->objParam);
		$this->res->imprimirRespuesta($this->res->generarJson());
	}
	//
	function enviarCorreo(){		
		$this->objFunc=$this->create('MODCuestionario');			
		$this->res=$this->objFunc->enviarCorreo($this->objParam);		
		$this->res->imprimirRespuesta($this->res->generarJson());	
	}
	//
	function finCuestionario(){		
		$this->objFunc=$this->create('MODCuestionario');			
		$this->res=$this->objFunc->finCuestionario($this->objParam);		
		$this->res->imprimirRespuesta($this->res->generarJson());	
	}
	//
	function listarRepCuestionario(){
		$this->objParam->defecto('ordenacion','id_encuesta');
		$this->objParam->defecto('dir_ordenacion','asc');
				
		$this->objFunc=$this->create('MODCuestionario');			
		$this->res=$this->objFunc->listarRepCuestionario($this->objParam);		
		$this->res->imprimirRespuesta($this->res->generarJson());
	}		
}

?>