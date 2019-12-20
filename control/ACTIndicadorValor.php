<?php
/**
*@package pXP
*@file gen-ACTIndicadorValor.php
*@author  (admin)
*@date 21-11-2016 14:01:15
*@description Clase que recibe los parametros enviados por la vista para mandar a la capa de Modelo
*/

class ACTIndicadorValor extends ACTbase{    
			
	function listarIndicadorValor(){
		$this->objParam->defecto('ordenacion','id_indicador_valor');

		$this->objParam->defecto('dir_ordenacion','asc');
		
		 $this->objParam->addFiltro("inva.id_indicador = ".$this->objParam->getParametro('id_indicador')); 
		 
		if($this->objParam->getParametro('tipoReporte')=='excel_grid' || $this->objParam->getParametro('tipoReporte')=='pdf_grid'){
			$this->objReporte = new Reporte($this->objParam,$this);
			$this->res = $this->objReporte->generarReporteListado('MODIndicadorValor','listarIndicadorValor');
		} else{
			$this->objFunc=$this->create('MODIndicadorValor');
			
			$this->res=$this->objFunc->listarIndicadorValor($this->objParam);
		}
		$this->res->imprimirRespuesta($this->res->generarJson());
	}
				
	function insertarIndicadorValor(){
		$this->objFunc=$this->create('MODIndicadorValor');	
		
		if($this->objParam->insertar('id_indicador_valor')){
			$this->res=$this->objFunc->insertarIndicadorValor($this->objParam);			
		} else{			
			$this->res=$this->objFunc->modificarIndicadorValor($this->objParam);
		}
		$this->res->imprimirRespuesta($this->res->generarJson());
	}

    function insertarIndicadorValorNuevo(){
		$this->objFunc=$this->create('MODIndicadorValor');	
		
			$this->res=$this->objFunc->insertarIndicadorValorNuevo($this->objParam);			
	
		$this->res->imprimirRespuesta($this->res->generarJson());
	}
						
	function eliminarIndicadorValor(){
			$this->objFunc=$this->create('MODIndicadorValor');	
		$this->res=$this->objFunc->eliminarIndicadorValor($this->objParam);
		$this->res->imprimirRespuesta($this->res->generarJson());
	}
			
}

?>