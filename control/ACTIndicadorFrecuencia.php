<?php
/**
*@package pXP
*@file gen-ACTIndicadorFrecuencia.php
*@author  (admin)
*@date 21-11-2016 12:35:24
*@description Clase que recibe los parametros enviados por la vista para mandar a la capa de Modelo
*/

class ACTIndicadorFrecuencia extends ACTbase{    
			
	function listarIndicadorFrecuencia(){
		$this->objParam->defecto('ordenacion','id_indicador_frecuencia');

		$this->objParam->defecto('dir_ordenacion','asc');
		if($this->objParam->getParametro('tipoReporte')=='excel_grid' || $this->objParam->getParametro('tipoReporte')=='pdf_grid'){
			$this->objReporte = new Reporte($this->objParam,$this);
			$this->res = $this->objReporte->generarReporteListado('MODIndicadorFrecuencia','listarIndicadorFrecuencia');
		} else{
			$this->objFunc=$this->create('MODIndicadorFrecuencia');
			
			$this->res=$this->objFunc->listarIndicadorFrecuencia($this->objParam);
		}
		$this->res->imprimirRespuesta($this->res->generarJson());
	}
				
	function insertarIndicadorFrecuencia(){
		$this->objFunc=$this->create('MODIndicadorFrecuencia');	
		if($this->objParam->insertar('id_indicador_frecuencia')){
			$this->res=$this->objFunc->insertarIndicadorFrecuencia($this->objParam);			
		} else{			
			$this->res=$this->objFunc->modificarIndicadorFrecuencia($this->objParam);
		}
		$this->res->imprimirRespuesta($this->res->generarJson());
	}
						
	function eliminarIndicadorFrecuencia(){
			$this->objFunc=$this->create('MODIndicadorFrecuencia');	
		$this->res=$this->objFunc->eliminarIndicadorFrecuencia($this->objParam);
		$this->res->imprimirRespuesta($this->res->generarJson());
	}
			
}

?>