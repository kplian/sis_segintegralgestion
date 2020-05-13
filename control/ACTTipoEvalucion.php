<?php
/**
*@package pXP
*@file gen-ACTTipoEvalucion.php
*@author  (admin.miguel)
*@date 27-04-2020 14:34:48
*@description Clase que recibe los parametros enviados por la vista para mandar a la capa de Modelo
 HISTORIAL DE MODIFICACIONES:
#ISSUE				FECHA				AUTOR				DESCRIPCION
 #0				27-04-2020 14:34:48								CREACION

*/

class ACTTipoEvalucion extends ACTbase{    
			
	function listarTipoEvalucion(){
		$this->objParam->defecto('ordenacion','id_tipo_evalucion');

		$this->objParam->defecto('dir_ordenacion','asc');
		if($this->objParam->getParametro('tipoReporte')=='excel_grid' || $this->objParam->getParametro('tipoReporte')=='pdf_grid'){
			$this->objReporte = new Reporte($this->objParam,$this);
			$this->res = $this->objReporte->generarReporteListado('MODTipoEvalucion','listarTipoEvalucion');
		} else{
			$this->objFunc=$this->create('MODTipoEvalucion');
			
			$this->res=$this->objFunc->listarTipoEvalucion($this->objParam);
		}
		$this->res->imprimirRespuesta($this->res->generarJson());
	}
				
	function insertarTipoEvalucion(){
		$this->objFunc=$this->create('MODTipoEvalucion');	
		if($this->objParam->insertar('id_tipo_evalucion')){
			$this->res=$this->objFunc->insertarTipoEvalucion($this->objParam);			
		} else{			
			$this->res=$this->objFunc->modificarTipoEvalucion($this->objParam);
		}
		$this->res->imprimirRespuesta($this->res->generarJson());
	}
						
	function eliminarTipoEvalucion(){
			$this->objFunc=$this->create('MODTipoEvalucion');	
		$this->res=$this->objFunc->eliminarTipoEvalucion($this->objParam);
		$this->res->imprimirRespuesta($this->res->generarJson());
	}
			
}

?>