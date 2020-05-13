<?php
/**
*@package pXP
*@file gen-ACTCuestionarioFuncionario.php
*@author  (mguerra)
*@date 22-04-2020 06:47:37
*@description Clase que recibe los parametros enviados por la vista para mandar a la capa de Modelo
 HISTORIAL DE MODIFICACIONES:
#ISSUE				FECHA				AUTOR				DESCRIPCION
 #0				22-04-2020 06:47:37								CREACION

*/
require_once(dirname(__FILE__).'/../../pxp/pxpReport/DataSource.php');
require_once dirname(__FILE__).'/../../pxp/lib/lib_reporte/ReportePDFFormulario.php';
include_once(dirname(__FILE__).'/../../sis_segintegralgestion/reportes/reporteCuestionario.php');

class ACTCuestionarioFuncionario extends ACTbase{    
			
	function listarCuestionarioFuncionario(){
		$this->objParam->defecto('ordenacion','id_cuestionario_funcionario');
		$this->objParam->defecto('dir_ordenacion','asc');
		if($this->objParam->getParametro('id_cuestionario')!=null){
			$this->objParam->addFiltro("cuefun.id_cuestionario = ".$this->objParam->getParametro('id_cuestionario')); 
		}else{
			$this->objParam->addFiltro("cuefun.id_cuestionario = 0"); 
		}		
		
		if($this->objParam->getParametro('tipoReporte')=='excel_grid' || $this->objParam->getParametro('tipoReporte')=='pdf_grid'){
			$this->objReporte = new Reporte($this->objParam,$this);
			$this->res = $this->objReporte->generarReporteListado('MODCuestionarioFuncionario','listarCuestionarioFuncionario');
		} else{
			$this->objFunc=$this->create('MODCuestionarioFuncionario');
			
			$this->res=$this->objFunc->listarCuestionarioFuncionario($this->objParam);
		}
		$this->res->imprimirRespuesta($this->res->generarJson());
	}
				
	function insertarCuestionarioFuncionario(){
		$this->objFunc=$this->create('MODCuestionarioFuncionario');	
		if($this->objParam->insertar('id_cuestionario_funcionario')){
			$this->res=$this->objFunc->insertarCuestionarioFuncionario($this->objParam);			
		} else{			
			$this->res=$this->objFunc->modificarCuestionarioFuncionario($this->objParam);
		}
		$this->res->imprimirRespuesta($this->res->generarJson());
	}
						
	function eliminarCuestionarioFuncionario(){
			$this->objFunc=$this->create('MODCuestionarioFuncionario');	
		$this->res=$this->objFunc->eliminarCuestionarioFuncionario($this->objParam);
		$this->res->imprimirRespuesta($this->res->generarJson());
	}
	//
	function listarCuestionarioEvaluacion(){
		$this->objParam->defecto('ordenacion','id_cuestionario_funcionario');        		
		$this->objParam->defecto('dir_ordenacion','asc');

		if($this->objParam->getParametro('pes_estado')=='proceso'){
			$this->objParam->addFiltro("cuefun.estado in (''proceso'')");
	   	}
	   	if($this->objParam->getParametro('pes_estado')=='finalizado'){
			$this->objParam->addFiltro("cuefun.estado in (''finalizado'')");
	   	}
		if($this->objParam->getParametro('tipoReporte')=='excel_grid' || $this->objParam->getParametro('tipoReporte')=='pdf_grid'){
			$this->objReporte = new Reporte($this->objParam,$this);
			$this->res = $this->objReporte->generarReporteListado('MODCuestionarioFuncionario','listarCuestionarioEvaluacion');
		} else{
			$this->objFunc=$this->create('MODCuestionarioFuncionario');			
			$this->res=$this->objFunc->listarCuestionarioEvaluacion($this->objParam);
		}
		$this->res->imprimirRespuesta($this->res->generarJson());
	}
	//
	function reporteCuestionario() {
		//$dataSource = new DataSource();		
		$this->objFun=$this->create('MODCuestionarioFuncionario');	
		$this->res = $this->objFun->reporteCuestionario();
		if($this->res->getTipo()=='ERROR'){
			$this->res->imprimirRespuesta($this->res->generarJson());
			exit;
		}		
		//var_dump($this->res->datos);exit;
		$titulo ='Reporte';
		$nombreArchivo=uniqid(md5(session_id()).$titulo);
		$nombreArchivo.='.xls';
		$this->objParam->addParametro('nombre_archivo',$nombreArchivo);
		$this->objParam->addParametro('datos',$this->res->datos);			
		$this->objReporteFormato=new reporteCuestionario($this->objParam);	
		$this->objReporteFormato->generarDatos();
		$this->objReporteFormato->generarReporte();
		$this->mensajeExito=new Mensaje();
		$this->mensajeExito->setMensaje('EXITO','Reporte.php','Reporte generado','Se genero con éxito el reporte: '.$nombreArchivo,'control');
		$this->mensajeExito->setArchivoGenerado($nombreArchivo);
		$this->mensajeExito->imprimirRespuesta($this->mensajeExito->generarJson());			
	}			
}

?>