<?php
/**
*@package pXP
*@file gen-ACTIndicadorUnidad.php
*@author  (admin)
*@date 21-11-2016 09:55:49
*@description Clase que recibe los parametros enviados por la vista para mandar a la capa de Modelo
*/

class ACTIndicadorUnidad extends ACTbase{    
			
	function listarIndicadorUnidad(){
		$this->objParam->defecto('ordenacion','id_indicador_unidad');

		$this->objParam->defecto('dir_ordenacion','asc');
		if($this->objParam->getParametro('tipoReporte')=='excel_grid' || $this->objParam->getParametro('tipoReporte')=='pdf_grid'){
			$this->objReporte = new Reporte($this->objParam,$this);
			$this->res = $this->objReporte->generarReporteListado('MODIndicadorUnidad','listarIndicadorUnidad');
		} else{
			$this->objFunc=$this->create('MODIndicadorUnidad');
			
			$this->res=$this->objFunc->listarIndicadorUnidad($this->objParam);
		}
		$this->res->imprimirRespuesta($this->res->generarJson());
	}
				
	function insertarIndicadorUnidad(){
		$this->objFunc=$this->create('MODIndicadorUnidad');	
		if($this->objParam->insertar('id_indicador_unidad')){
			$this->res=$this->objFunc->insertarIndicadorUnidad($this->objParam);			
		} else{			
			$this->res=$this->objFunc->modificarIndicadorUnidad($this->objParam);
		}
		$this->res->imprimirRespuesta($this->res->generarJson());
	}
						
	function eliminarIndicadorUnidad(){
			$this->objFunc=$this->create('MODIndicadorUnidad');	
		$this->res=$this->objFunc->eliminarIndicadorUnidad($this->objParam);
		$this->res->imprimirRespuesta($this->res->generarJson());
	}
			
}

?>