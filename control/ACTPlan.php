<?php

/**
 * @package pXP
 * @file gen-ACTPlan.php
 * @author  (admin)
 * @date 11-04-2017 14:31:46
 * @description Clase que recibe los parametros enviados por la vista para mandar a la capa de Modelo
 */
require_once(dirname(__FILE__).'/../../pxp/pxpReport/DataSource.php');
require_once dirname(__FILE__).'/../../pxp/lib/lib_reporte/ReportePDFFormulario.php';
require_once(dirname(__FILE__).'/../reportes/RPlanGlobal.php');

class ACTPlan extends ACTbase
{

    function listarPlan()
    {
        $this->objParam->defecto('ordenacion', 'id_plan');

        $this->objParam->defecto('dir_ordenacion', 'asc');
        if ($this->objParam->getParametro('tipoReporte') == 'excel_grid' || $this->objParam->getParametro('tipoReporte') == 'pdf_grid') {
            $this->objReporte = new Reporte($this->objParam, $this);
            $this->res = $this->objReporte->generarReporteListado('MODPlan', 'listarPlan');
        } else {
            $this->objFunc = $this->create('MODPlan');
            $this->res = $this->objFunc->listarPlan($this->objParam);
        }
        $this->res->imprimirRespuesta($this->res->generarJson());
    }

    function listarPlanArb()
    {

        //Obtniene el parametro enviado por la vista
        $node = $this->objParam->getParametro('node');

        //$clasificacion = $this->objParam->getParametro('clasificacion');
        $id_plan = $this->objParam->getParametro('id_plan');
        //
        $tipo_nodo = $this->objParam->getParametro('tipo_nodo');


        if ($node == 'id') {
            $this->objParam->addParametro('id_padre', '%');
        } else {
            $this->objParam->addParametro('id_padre', $id_plan);
        }


        //$this->objParam->addParametro('clasificacion', $clasificacion);

        //creamos el modelo
        $this->objFunc = $this->create('MODPlan');
        $this->res = $this->objFunc->listarPlanArb();

        $this->res->setTipoRespuestaArbol();

        $arreglo = array();

        //$arreglo_valores=array();

        //para cambiar un valor por otro en una variable
        // array_push($arreglo_valores,array('variable'=>'checked','val_ant'=>'true','val_nue'=>true));
        // array_push($arreglo_valores,array('variable'=>'checked','val_ant'=>'false','val_nue'=>false));
        // $this->res->setValores($arreglo_valores);


        array_push($arreglo, array('nombre' => 'id', 'valor' => 'id_plan'));
        array_push($arreglo, array('nombre' => 'id_p', 'valor' => 'id_plan_padre'));

        array_push($arreglo, array('nombre' => 'text', 'valores' => '#nombre_plan# <font color="blue">PESO:#peso#%</font> #porcentaje_acum# #porcentaje_rest#'));
        array_push($arreglo, array('nombre' => 'cls', 'valor' => 'peso'));
        array_push($arreglo, array('nombre' => 'qtip', 'valores' => '<b>ID PLAN:</b> #id_plan# <br><b>NOMBRE:</b> #nombre_plan#<br/><b>PESO:</b> #peso# %'));

        /*Estas funciones definen reglas para los nodos en funcion a los tipo de nodos que contenga cada uno*/
        $this->res->addNivelArbol('tipo_nodo', 'raiz', array('leaf' => false, 'draggable' => false, 'allowDelete' => true, 'allowEdit' => true, 'cls' => 'folder', 'tipo_nodo' => 'raiz', 'icon' => '../../../lib/imagenes/orga32x32.png'), $arreglo);

        $this->res->addNivelArbol('tipo_nodo', 'hijo', array('leaf' => false, 'draggable' => false, 'allowDelete' => true, 'allowEdit' => true, 'tipo_nodo' => 'hijo', 'icon' => '../../../lib/imagenes/alma32x32.png'), $arreglo);

        $this->res->addNivelArbol('tipo_nodo', 'hoja', array('leaf' => true, 'draggable' => false, 'allowDelete' => true, 'allowEdit' => true, 'tipo_nodo' => 'hoja', 'icon' => '../../../lib/imagenes/a_form.png'), $arreglo);
        

        //Se imprime el arbol en formato JSON
        //var_dump($this->res->generarJson());exit;
        $this->res->imprimirRespuesta($this->res->generarJson());
    }

    function insertarPlan()
    {
        $this->objFunc = $this->create('MODPlan');
        if ($this->objParam->insertar('id_plan')) {
            $this->res = $this->objFunc->insertarPlan($this->objParam);
        } else {
            $this->res = $this->objFunc->modificarPlan($this->objParam);
        }
        $this->res->imprimirRespuesta($this->res->generarJson());
    }

    function eliminarPlan()
    {
        $this->objFunc = $this->create('MODPlan');
        $this->res = $this->objFunc->eliminarPlan($this->objParam);
        $this->res->imprimirRespuesta($this->res->generarJson());
    }

    function aprobarPlanes()
    {
        //$id_gestion = $this->objParam->getParametro('id_gestion');

        //$this->objParam->addParametro('id_gestion', $id_gestion);
        $this->objFunc = $this->create('MODPlan');
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
            $this->objFunc = $this->create('MODPlan');

            $this->res = $this->objFunc->listarGestion($this->objParam);
        }
        $this->res->imprimirRespuesta($this->res->generarJson());
    }

    function estadoGestion()
    {
        $this->objFunc = $this->create('MODPlan');
        $this->res = $this->objFunc->estadoGestion($this->objParam);
        $this->res->imprimirRespuesta($this->res->generarJson());
    }
    function reportePlanGlobal(){
        $var='';
           
            $this->objFun=$this->create('MODPlan');
            $this->res = $this->objFun->reportePlanGlobal();

            if($this->res->getTipo()=='ERROR'){
                $this->res->imprimirRespuesta($this->res->generarJson());
                exit;
            }
         
            $var = 'TRANSPORTADORA DE ELÉCTRICIDAD';
            //obtener titulo de reporte
            $titulo ='PLAN GLOBAL ';
            //Genera el nombre del archivo (aleatorio + titulo)
            $nombreArchivo=uniqid(md5(session_id()).$titulo);
            $nombreArchivo.='.xls';
            $this->objParam->addParametro('nombre_archivo',$nombreArchivo);
            $this->objParam->addParametro('datos',$this->res->datos);
            $this->objParam->addParametro('var',$var);
            $this->objParam->addParametro('gestion',$this->objParam->getParametro('gestion'));
            $this->objParam->addParametro('nombre_plan',$this->objParam->getParametro('nombre_plan'));
            //$this->objParam->addParametro('periodo',$this->objParam->getParametro('periodo'));
      
            $this->objReporteFormato=new RPlanGlobal($this->objParam);
            $this->objReporteFormato->generarDatos();
            $this->objReporteFormato->generarReporte();

            $this->mensajeExito=new Mensaje();
            $this->mensajeExito->setMensaje('EXITO','Reporte.php','Reporte generado',
                'Se generó con éxito el reporte: '.$nombreArchivo,'control');
            $this->mensajeExito->setArchivoGenerado($nombreArchivo);
            $this->mensajeExito->imprimirRespuesta($this->mensajeExito->generarJson());


    }


}

?>