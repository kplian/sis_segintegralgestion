<?php
/**
*@package pXP
*@file gen-ACTAgrupadorIndicador.php
*@author  (admin)
*@date 08-06-2017 10:36:34
*@description Clase que recibe los parametros enviados por la vista para mandar a la capa de Modelo
*/

class ACTAgrupadorIndicador extends ACTbase{    
		
	function listarAgrupadorIndicador(){
		$this->objParam->defecto('ordenacion','id_agrupador_indicador');
		
		$this->objParam->defecto('dir_ordenacion','asc');
						
		if($this->objParam->getParametro('id_agrupador')== 0) {
			$this->objParam->addFiltro("agin.id_agrupador = 0 ");
		}else{
			$this->objParam->addFiltro("agin.id_agrupador = ".$this->objParam->getParametro('id_agrupador'));
		}
		
		
		if($this->objParam->getParametro('tipoReporte')=='excel_grid' || $this->objParam->getParametro('tipoReporte')=='pdf_grid'){
			$this->objReporte = new Reporte($this->objParam,$this);
			$this->res = $this->objReporte->generarReporteListado('MODAgrupadorIndicador','listarAgrupadorIndicador');
		} else{
			$this->objFunc=$this->create('MODAgrupadorIndicador');
			
			$this->res=$this->objFunc->listarAgrupadorIndicador($this->objParam);
		}
		$this->res->imprimirRespuesta($this->res->generarJson());
	}
				
	function insertarAgrupadorIndicador(){
		$this->objFunc=$this->create('MODAgrupadorIndicador');	
		if($this->objParam->insertar('id_agrupador_indicador')){
			$this->res=$this->objFunc->insertarAgrupadorIndicador($this->objParam);			
		} else{			
			$this->res=$this->objFunc->modificarAgrupadorIndicador($this->objParam);
		}
		$this->res->imprimirRespuesta($this->res->generarJson());
	}
						
	function eliminarAgrupadorIndicador(){
			$this->objFunc=$this->create('MODAgrupadorIndicador');	
		$this->res=$this->objFunc->eliminarAgrupadorIndicador($this->objParam);
		$this->res->imprimirRespuesta($this->res->generarJson());
	}
			
}

?>