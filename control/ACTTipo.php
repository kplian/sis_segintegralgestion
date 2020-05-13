<?php
/**
*@package pXP
*@file gen-ACTTipo.php
*@author  (mguerra)
*@date 27-04-2020 11:27:10
*@description Clase que recibe los parametros enviados por la vista para mandar a la capa de Modelo
 HISTORIAL DE MODIFICACIONES:
#ISSUE				FECHA				AUTOR				DESCRIPCION
 #0				27-04-2020 11:27:10								CREACION

*/

class ACTTipo extends ACTbase{    
			
	function listarTipo(){
		$this->objParam->defecto('ordenacion','id_tipo');

		$this->objParam->defecto('dir_ordenacion','asc');
		if($this->objParam->getParametro('tipoReporte')=='excel_grid' || $this->objParam->getParametro('tipoReporte')=='pdf_grid'){
			$this->objReporte = new Reporte($this->objParam,$this);
			$this->res = $this->objReporte->generarReporteListado('MODTipo','listarTipo');
		} else{
			$this->objFunc=$this->create('MODTipo');
			
			$this->res=$this->objFunc->listarTipo($this->objParam);
		}
		$this->res->imprimirRespuesta($this->res->generarJson());
	}
				
	function insertarTipo(){
		$this->objFunc=$this->create('MODTipo');	
		if($this->objParam->insertar('id_tipo')){
			$this->res=$this->objFunc->insertarTipo($this->objParam);			
		} else{			
			$this->res=$this->objFunc->modificarTipo($this->objParam);
		}
		$this->res->imprimirRespuesta($this->res->generarJson());
	}
						
	function eliminarTipo(){
			$this->objFunc=$this->create('MODTipo');	
		$this->res=$this->objFunc->eliminarTipo($this->objParam);
		$this->res->imprimirRespuesta($this->res->generarJson());
	}
			
}

?>