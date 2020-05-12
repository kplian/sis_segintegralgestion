<?php
/**
*@package pXP
*@file gen-ACTEvaluados.php
*@author  (admin.miguel)
*@date 28-04-2020 01:32:33
*@description Clase que recibe los parametros enviados por la vista para mandar a la capa de Modelo
 HISTORIAL DE MODIFICACIONES:
#ISSUE				FECHA				AUTOR				DESCRIPCION
 #0				28-04-2020 01:32:33								CREACION

*/

class ACTEvaluados extends ACTbase{    
			
	function listarEvaluados(){
		$this->objParam->defecto('ordenacion','id_evaluados');
		$this->objParam->defecto('dir_ordenacion','asc');
        if($this->objParam->getParametro('id_cuestionario_funcionario') != '') {
            $this->objParam->addFiltro("evs.id_cuestionario_funcionario = " .$this->objParam->getParametro('id_cuestionario_funcionario'));
        }
            $this->objParam->addFiltro("evs.id_cuestionario_funcionario = " .
                $this->objParam->getParametro('id_cuestionario_funcionario')."and evs.id_funcionario  not in (select re.id_func_evaluado 
                          																				  from ssig.trespuestas re       
                                                                                                          inner join ssig.tcuestionario_funcionario fu on fu.id_cuestionario = re.id_cuestionario                                                                                      
                                                                                                          where fu.id_cuestionario_funcionario = ".$this->objParam->getParametro('id_cuestionario_funcionario')." )");

		if($this->objParam->getParametro('tipoReporte')=='excel_grid' || $this->objParam->getParametro('tipoReporte')=='pdf_grid'){
			$this->objReporte = new Reporte($this->objParam,$this);
			$this->res = $this->objReporte->generarReporteListado('MODEvaluados','listarEvaluados');
		} else{
			$this->objFunc=$this->create('MODEvaluados');
			
			$this->res=$this->objFunc->listarEvaluados($this->objParam);
		}
		$this->res->imprimirRespuesta($this->res->generarJson());
	}
				
	function insertarEvaluados(){
		$this->objFunc=$this->create('MODEvaluados');	
		if($this->objParam->insertar('id_evaluados')){
			$this->res=$this->objFunc->insertarEvaluados($this->objParam);			
		} else{			
			$this->res=$this->objFunc->modificarEvaluados($this->objParam);
		}
		$this->res->imprimirRespuesta($this->res->generarJson());
	}
						
	function eliminarEvaluados(){
			$this->objFunc=$this->create('MODEvaluados');	
		$this->res=$this->objFunc->eliminarEvaluados($this->objParam);
		$this->res->imprimirRespuesta($this->res->generarJson());
	}
			
}

?>