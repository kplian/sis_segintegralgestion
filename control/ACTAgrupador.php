<?php

/**
 * @package pXP
 * @file gen-ACTAgrupador.php
 * @author  (admin)
 * @date 05-06-2017 04:46:40
 * @description Clase que recibe los parametros enviados por la vista para mandar a la capa de Modelo
 */
require_once(dirname(__FILE__).'/../../pxp/pxpReport/DataSource.php');
require_once dirname(__FILE__).'/../../pxp/lib/lib_reporte/ReportePDFFormulario.php';
require_once(dirname(__FILE__).'/../reportes/RcuadroMando.php');
class ACTAgrupador extends ACTbase
{
//
    function listarAgrupador()
    {
        $this->objParam->defecto('ordenacion', 'id_agrupador');

        $this->objParam->defecto('dir_ordenacion', 'asc');
        if ($this->objParam->getParametro('tipoReporte') == 'excel_grid' || $this->objParam->getParametro('tipoReporte') == 'pdf_grid') {
            $this->objReporte = new Reporte($this->objParam, $this);
            $this->res = $this->objReporte->generarReporteListado('MODAgrupador', 'listarAgrupador');
        } else {
            $this->objFunc = $this->create('MODAgrupador');

            $this->res = $this->objFunc->listarAgrupador($this->objParam);
        }
        $this->res->imprimirRespuesta($this->res->generarJson());
    }

    function listarAgrupadorArb()
    {
        $node = $this->objParam->getParametro('node');
        $id_agrupador = $this->objParam->getParametro('id_agrupador');
        $tipo_nodo = $this->objParam->getParametro('tipo_nodo');
		
 		$this->objParam->addFiltro("ssig_ag.id_gestion = ".$this->objParam->getParametro('id_gestion'));
   	
        
        if ($node == 'id') {
            $this->objParam->addParametro('id_padre', '%');
        } else {
            $this->objParam->addParametro('id_padre', $id_agrupador);
        }

        $this->objFunc = $this->create('MODAgrupador');
        $this->res = $this->objFunc->listarAgrupadorArb();

        $this->res->setTipoRespuestaArbol();

        $arreglo = array();

        array_push($arreglo, array('nombre' => 'id', 'valor' => 'id_agrupador'));
        array_push($arreglo, array('nombre' => 'id_p', 'valor' => 'id_agrupador_padre'));

		array_push($arreglo, array('nombre' => 'text', 'valores' => '#nombre# <font color="blue">PESO:#peso#%</font> #porcentaje_acum# #porcentaje_rest#'));
        array_push($arreglo, array('nombre' => 'cls', 'valor' => 'peso'));
        array_push($arreglo, array('nombre' => 'qtip', 'valores' => '<b>#id_agrupador# </b><br><b>#nombre#</b><br/>#peso#'));

        /*Estas funciones definen reglas para los nodos en funcion a los tipo de nodos que contenga cada uno*/
 		$this->res->addNivelArbol('tipo_nodo', 'raiz', array('leaf' => false, 'draggable' => false, 'allowDelete' => true, 'allowEdit' => true, 'cls' => 'folder', 'tipo_nodo' => 'raiz', 'icon' => '../../../lib/imagenes/orga32x32.png'), $arreglo);

        $this->res->addNivelArbol('tipo_nodo', 'hijo', array('leaf' => false, 'draggable' => false, 'allowDelete' => true, 'allowEdit' => true, 'tipo_nodo' => 'hijo', 'icon' => '../../../lib/imagenes/alma32x32.png'), $arreglo);

        $this->res->addNivelArbol('tipo_nodo', 'hoja', array('leaf' => true, 'draggable' => false, 'allowDelete' => true, 'allowEdit' => true, 'tipo_nodo' => 'hoja', 'icon' => '../../../lib/imagenes/a_form.png'), $arreglo);


        //Se imprime el arbol en formato JSON
        //var_dump($this->res->generarJson());exit;
        $this->res->imprimirRespuesta($this->res->generarJson());
    }

    function insertarAgrupador()
    {
        $this->objFunc = $this->create('MODAgrupador');
        if ($this->objParam->insertar('id_agrupador')) {
            $this->res = $this->objFunc->insertarAgrupador($this->objParam);
        } else {
            $this->res = $this->objFunc->modificarAgrupador($this->objParam);
        }
        $this->res->imprimirRespuesta($this->res->generarJson());
    }

    function eliminarAgrupador()
    {
        $this->objFunc = $this->create('MODAgrupador');
        $this->res = $this->objFunc->eliminarAgrupador($this->objParam);
        $this->res->imprimirRespuesta($this->res->generarJson());
    }

    function aprobarPlanes()
    {
        $this->objFunc = $this->create('MODAgrupador');
        $this->res = $this->objFunc->aprobarPlanes($this->objParam);
        $this->res->imprimirRespuesta($this->res->generarJson());
    }
	
	function listarGestion()
    {
        $this->objParam->defecto('ordenacion', 'id_gestion');

        $this->objParam->defecto('dir_ordenacion', 'asc');
        if ($this->objParam->getParametro('tipoReporte') == 'excel_grid' || $this->objParam->getParametro('tipoReporte') == 'pdf_grid') {
            $this->objReporte = new Reporte($this->objParam, $this);
            $this->res = $this->objReporte->generarReporteListado('MODGestion', 'listarGestion');
        } else {
            $this->objFunc = $this->create('MODAgrupador');

            $this->res = $this->objFunc->listarGestion($this->objParam);
        }
        $this->res->imprimirRespuesta($this->res->generarJson());
    }
	
	function estadoGestion()
    {
        $this->objFunc = $this->create('MODAgrupador');
        $this->res = $this->objFunc->estadoGestion($this->objParam);
        $this->res->imprimirRespuesta($this->res->generarJson());
    }
    function listarInterpretacionIndicador()
    {
        $this->objParam->defecto('ordenacion', 'id_agrupador');

        
        if($this->objParam->getParametro('id_gestion')){
        	$this->objParam->addFiltro("ii.id_gestion = ".$this->objParam->getParametro('id_gestion')); 
        }
		else{
			$this->objParam->addFiltro("ii.id_gestion = 0"); 
		}
        $this->objParam->defecto('dir_ordenacion', 'asc');
        if ($this->objParam->getParametro('tipoReporte') == 'excel_grid' || $this->objParam->getParametro('tipoReporte') == 'pdf_grid') {
            $this->objReporte = new Reporte($this->objParam, $this);
            $this->res = $this->objReporte->generarReporteListado('MODAgrupador', 'listarInterpretacionIndicador');
        } else {
            $this->objFunc = $this->create('MODAgrupador');

            $this->res = $this->objFunc->listarInterpretacionIndicador($this->objParam);
        }
        $this->res->imprimirRespuesta($this->res->generarJson());
    }
    function modificarInterpretacionIndicador()
    {
        $this->objFunc = $this->create('MODAgrupador');

        $this->res = $this->objFunc->modificarInterpretacionIndicador($this->objParam);

        $this->res->imprimirRespuesta($this->res->generarJson());
    }
    function reporteCuadroDeMando(){
        $var='';
           
        //if($this->objParam->getParametro('formato_reporte') == 'xls'){
            $this->objFun=$this->create('MODAgrupador');
            $this->res = $this->objFun->reporteCuadroDeMando();

            if($this->res->getTipo()=='ERROR'){
                $this->res->imprimirRespuesta($this->res->generarJson());
                exit;
            }
         
            $var = 'CUADRO DE MANDO INTEGRAL';
            //obtener titulo de reporte
            $titulo ='SISTEMA DE SEGUIMIENTO';
            //Genera el nombre del archivo (aleatorio + titulo)
            $nombreArchivo=uniqid(md5(session_id()).$titulo);
            $nombreArchivo.='.xls';
            $this->objParam->addParametro('nombre_archivo',$nombreArchivo);
            $this->objParam->addParametro('datos',$this->res->datos);
            $this->objParam->addParametro('var',$var);
            $this->objParam->addParametro('gestion',$this->objParam->getParametro('gestion'));
            $this->objParam->addParametro('periodo',$this->objParam->getParametro('periodo'));
            //Instancia la clase de excel
            $this->objReporteFormato=new RcuadroMando($this->objParam);
            $this->objReporteFormato->generarDatos();
            $this->objReporteFormato->generarReporte();

            $this->mensajeExito=new Mensaje();
            $this->mensajeExito->setMensaje('EXITO','Reporte.php','Reporte generado',
                'Se generó con éxito el reporte: '.$nombreArchivo,'control');
            $this->mensajeExito->setArchivoGenerado($nombreArchivo);
            $this->mensajeExito->imprimirRespuesta($this->mensajeExito->generarJson());
        //}

    }
	
}

?>