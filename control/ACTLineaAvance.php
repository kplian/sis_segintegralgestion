<?php
/**
*@package pXP
*@file gen-ACTLineaAvance.php
*@author  (admin)
*@date 19-02-2017 02:21:07
*@description Clase que recibe los parametros enviados por la vista para mandar a la capa de Modelo
*/

class ACTLineaAvance extends ACTbase{    

	function listarLineaAvance(){
		
		$this->objParam->defecto('ordenacion','orden');
		$this->objParam->defecto('dir_ordenacion','asc');

		if($this->objParam->getParametro('id_plan')!=''){
			$this->objParam->addFiltro("arb.id_plan = ".$this->objParam->getParametro('id_plan'));
			
		}
		else{
			$this->objParam->addFiltro("arb.id_plan = 0");
		}
		
		if($this->objParam->getParametro('tipoReporte')=='excel_grid' || $this->objParam->getParametro('tipoReporte')=='pdf_grid'){
			$this->objReporte = new Reporte($this->objParam,$this);
			$this->res = $this->objReporte->generarReporteListado('MODLineaAvance','listarLineaAvance');
		} else{
			$this->objFunc=$this->create('MODLineaAvance');
			
			$this->res=$this->objFunc->listarLineaAvance($this->objParam);
		}
		$this->res->imprimirRespuesta($this->res->generarJson());
	}
	function listarLineaAvance_ordenado(){
		
		$this->objParam->defecto('ordenacion','orden_logico');
		$this->objParam->defecto('dir_ordenacion','asc');

		if($this->objParam->getParametro('id_plan')!=''){
			$this->objParam->addFiltro("arb.id_plan = ".$this->objParam->getParametro('id_plan'));
		}
		else{
			$this->objParam->addFiltro("arb.id_plan = 0");
		}
		
		if($this->objParam->getParametro('tipoReporte')=='excel_grid' || $this->objParam->getParametro('tipoReporte')=='pdf_grid'){
			$this->objReporte = new Reporte($this->objParam,$this);
			$this->res = $this->objReporte->generarReporteListado('MODLineaAvance','listarLineaAvance_ordenado');
		} else{
			$this->objFunc=$this->create('MODLineaAvance');
			
			$this->res=$this->objFunc->listarLineaAvance_ordenado($this->objParam);
		}
		$this->res->imprimirRespuesta($this->res->generarJson());
	}

	function listarLineaAvanceDinamico(){
		
		if($this->objParam->getParametro('id_plan')!=''){
			$this->objParam->addFiltro("arb.id_plan = ".$this->objParam->getParametro('id_plan'));
			
		}
		else{
			$this->objParam->addFiltro("arb.id_plan = 0");
		}
		
		if($this->objParam->getParametro('tipoReporte')=='excel_grid' || $this->objParam->getParametro('tipoReporte')=='pdf_grid'){
			$this->objReporte = new Reporte($this->objParam,$this);
			$this->res = $this->objReporte->generarReporteListado('MODLineaAvance','listarLineaAvanceDinamico');
		} else{
			$this->objFunc=$this->create('MODLineaAvance');
			
			$this->res=$this->objFunc->listarLineaAvanceDinamico($this->objParam);
		}
		$this->res->imprimirRespuesta($this->res->generarJson());
		
	}
				
	function insertarLineaAvance(){
		

		  $codigo= $this->objParam->getParametro('datos');
			

		  $this->objFunc=$this->create('MODLineaAvance');	
		//if($this->objParam->insertar('id_linea')){

		   $this->res=$this->objFunc->insertarLineaAvance($this->objParam);
		   $this->res->imprimirRespuesta($this->res->generarJson());
		//}
		
	}
						
	function eliminarLineaAvance(){
		$this->objFunc=$this->create('MODLineaAvance');	
		$this->res=$this->objFunc->eliminarLineaAvance($this->objParam);
		$this->res->imprimirRespuesta($this->res->generarJson());
	}
    function GenerarColumnaMeses(){
		$this->objFunc=$this->create('MODLineaAvance');	
		$this->res=$this->objFunc->GenerarColumnaMeses($this->objParam);
		$this->res->imprimirRespuesta($this->res->generarJson());
    }
	function listarAvanceReal(){

		if($this->objParam->getParametro('id_plan')!='' && $this->objParam->getParametro('mes')!=''){
			$this->objParam->addFiltro("arb.id_plan = ".$this->objParam->getParametro('id_plan'));
		}
		else{
			$this->objParam->addFiltro("arb.id_plan = 0");
		}
		
		if($this->objParam->getParametro('tipoReporte')=='excel_grid' || $this->objParam->getParametro('tipoReporte')=='pdf_grid'){
			$this->objReporte = new Reporte($this->objParam,$this);
			$this->res = $this->objReporte->generarReporteListado('MODLineaAvance','listarAvanceReal');
		} else{
			$this->objFunc=$this->create('MODLineaAvance');
			
			$this->res=$this->objFunc->listarAvanceReal($this->objParam);
		}
		$this->res->imprimirRespuesta($this->res->generarJson());
	}
	function listarMeses(){
		
        //var_dump("testear controlador ",$this->objParam->getParametro('cod_plan'));exit;	
		
		if($this->objParam->getParametro('tipoReporte')=='excel_grid' || $this->objParam->getParametro('tipoReporte')=='pdf_grid'){
			$this->objReporte = new Reporte($this->objParam,$this);
			$this->res = $this->objReporte->generarReporteListado('MODLineaAvance','listarMeses');
		} else{
			$this->objFunc=$this->create('MODLineaAvance');
			
			$this->res=$this->objFunc->listarMeses($this->objParam);
		}
		$this->res->imprimirRespuesta($this->res->generarJson());
		
	}
	function insertarAvanceReal(){
		  $codigo= $this->objParam->getParametro('datos');
			

		  $this->objFunc=$this->create('MODLineaAvance');	
		//if($this->objParam->insertar('id_linea')){

		   $this->res=$this->objFunc->insertarAvanceReal($this->objParam);
		   $this->res->imprimirRespuesta($this->res->generarJson());
		//}
	}
	function EstadoAvanceReal(){
		 $this->objFunc=$this->create('MODLineaAvance');	
		 $this->res=$this->objFunc->EstadoAvanceReal($this->objParam);
		 $this->res->imprimirRespuesta($this->res->generarJson());
	}
	function aprobarAvanceReal(){
		 $this->objFunc=$this->create('MODLineaAvance');	
		 $this->res=$this->objFunc->aprobarAvanceReal($this->objParam);
		 $this->res->imprimirRespuesta($this->res->generarJson());
	}
		
}

?>