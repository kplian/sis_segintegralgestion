<?php

/**
 * @package pXP
 * @file gen-ACTIndicador.php
 * @author  (admin)
 * @date 21-11-2016 14:51:35
 * @description Clase que recibe los parametros enviados por la vista para mandar a la capa de Modelo
 */
class ACTIndicador extends ACTbase
{

    function listarIndicador()
    {
        $this->objParam->defecto('ordenacion', 'id_indicador');

        $this->objParam->defecto('dir_ordenacion', 'asc');

        if ($this->objParam->getParametro('id_gestion') != '') {
            $this->objParam->addFiltro("ge.id_gestion = ".$this->objParam->getParametro('id_gestion'));
        } else {
            $this->objParam->addFiltro("ge.id_gestion = 0");

        }
        if ($this->objParam->getParametro('tipoReporte') == 'excel_grid' || $this->objParam->getParametro('tipoReporte') == 'pdf_grid') {
            $this->objReporte = new Reporte($this->objParam, $this);
            $this->res = $this->objReporte->generarReporteListado('MODIndicador', 'listarIndicador');
        } else {
            $this->objFunc = $this->create('MODIndicador');

            $this->res = $this->objFunc->listarIndicador($this->objParam);
        }
        $this->res->imprimirRespuesta($this->res->generarJson());

    }

    function insertarIndicador()
    {
        $this->objFunc = $this->create('MODIndicador');
        if ($this->objParam->insertar('id_indicador')) {
            $this->res = $this->objFunc->insertarIndicador($this->objParam);
        } else {
            $this->res = $this->objFunc->modificarIndicador($this->objParam);
        }
        $this->res->imprimirRespuesta($this->res->generarJson());
    }

    function eliminarIndicador()
    {
        $this->objFunc = $this->create('MODIndicador');
        $this->res = $this->objFunc->eliminarIndicador($this->objParam);
        $this->res->imprimirRespuesta($this->res->generarJson());
    }
    function aprobarGestion()
    {
        //$id_gestion = $this->objParam->getParametro('id_gestion');

        //$this->objParam->addParametro('id_gestion', $id_gestion);
        $this->objFunc = $this->create('MODIndicador');
        $this->res = $this->objFunc->aprobarGestion($this->objParam);
        $this->res->imprimirRespuesta($this->res->generarJson());
    }
	function estadoGestion(){
		$this->objFunc = $this->create('MODIndicador');
        $this->res = $this->objFunc->estadoGestion($this->objParam);
        $this->res->imprimirRespuesta($this->res->generarJson());
	}
	function verEstadoIndicador(){
		$this->objFunc = $this->create('MODIndicador');
        $this->res = $this->objFunc->verEstadoIndicador($this->objParam);
        $this->res->imprimirRespuesta($this->res->generarJson());
	}
	
	function listarIndicadorAgrupador()
    {
        $this->objParam->defecto('ordenacion', 'id_indicador');

        $this->objParam->defecto('dir_ordenacion', 'asc');
		
        if ($this->objParam->getParametro('id_gestion') != '') {
            $this->objParam->addFiltro("ge.id_gestion = ".$this->objParam->getParametro('id_gestion'));
        } else {
            $this->objParam->addFiltro("ge.id_gestion = 0");

        }
		
        if ($this->objParam->getParametro('tipoReporte') == 'excel_grid' || $this->objParam->getParametro('tipoReporte') == 'pdf_grid') {
            $this->objReporte = new Reporte($this->objParam, $this);
            $this->res = $this->objReporte->generarReporteListado('MODIndicador', 'listarIndicador');
        } else {
            $this->objFunc = $this->create('MODIndicador');

            $this->res = $this->objFunc->listarIndicador($this->objParam);
        }
		
        $this->res->imprimirRespuesta($this->res->generarJson());
    }
	function validarCambioEstadoIndicador(){
		$this->objFunc = $this->create('MODIndicador');
        $this->res = $this->objFunc->verEstadoIndicador($this->objParam);
        $this->res->imprimirRespuesta($this->res->generarJson());
	}

} 

?>