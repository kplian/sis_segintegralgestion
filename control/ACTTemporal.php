<?php
/**
*@package pXP
*@file gen-ACTTemporal.php
*@author  (mguerra)
*@date 24-04-2020 00:16:08
*@description Clase que recibe los parametros enviados por la vista para mandar a la capa de Modelo
 HISTORIAL DE MODIFICACIONES:
#ISSUE				FECHA				AUTOR				DESCRIPCION
 #0				24-04-2020 00:16:08								CREACION

*/

class ACTTemporal extends ACTbase{    
			
	function listarTemporal(){
		$this->objParam->defecto('ordenacion','id_temporal');
		$this->objParam->defecto('dir_ordenacion','asc');		
		   
		if($this->objParam->getParametro('tipoReporte')=='excel_grid' || $this->objParam->getParametro('tipoReporte')=='pdf_grid'){
			$this->objReporte = new Reporte($this->objParam,$this);
			$this->res = $this->objReporte->generarReporteListado('MODTemporal','listarTemporal');
		} else{
			$this->objFunc=$this->create('MODTemporal');			
			$this->res=$this->objFunc->listarTemporal($this->objParam);
		}
		$this->res->imprimirRespuesta($this->res->generarJson());
	}
				
	function insertarTemporal(){
		$this->objFunc=$this->create('MODTemporal');			
		$this->res=$this->objFunc->insertarTemporal($this->objParam);					
		$this->res->imprimirRespuesta($this->res->generarJson());
	}
						
	function eliminarTemporal(){
			$this->objFunc=$this->create('MODTemporal');	
		$this->res=$this->objFunc->eliminarTemporal($this->objParam);
		$this->res->imprimirRespuesta($this->res->generarJson());
	}	
}

?>