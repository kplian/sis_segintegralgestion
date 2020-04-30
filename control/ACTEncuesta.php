<?php
/**
*@package pXP
*@file gen-ACTEncuesta.php
*@author  (admin.miguel)
*@date 29-04-2020 06:10:09
*@description Clase que recibe los parametros enviados por la vista para mandar a la capa de Modelo
 HISTORIAL DE MODIFICACIONES:
#ISSUE				FECHA				AUTOR				DESCRIPCION
 #0				29-04-2020 06:10:09								CREACION

*/

class ACTEncuesta extends ACTbase{    
			
	function listarEncuesta(){
		$this->objParam->defecto('ordenacion','id_encuesta');
		$this->objParam->defecto('dir_ordenacion','asc');
		if ($this->objParam->getParametro('raiz')!= ''){
            $this->objParam->addFiltro("eta.id_encuesta_padre is null ");

        }
		if($this->objParam->getParametro('tipoReporte')=='excel_grid' || $this->objParam->getParametro('tipoReporte')=='pdf_grid'){
			$this->objReporte = new Reporte($this->objParam,$this);
			$this->res = $this->objReporte->generarReporteListado('MODEncuesta','listarEncuesta');
		} else{
			$this->objFunc=$this->create('MODEncuesta');
			
			$this->res=$this->objFunc->listarEncuesta($this->objParam);
		}
		$this->res->imprimirRespuesta($this->res->generarJson());
	}
    function listarEncuestaArb(){

        //obtiene el parametro nodo enviado por la vista
        $node = $this->objParam->getParametro('node');
        $id_encuesta_padre = $this->objParam->getParametro('id_encuesta');
        // var_dump($id_encuesta_padre); exit;

        if($node=='id'){
            $this->objParam->addParametro('id_padre','%');
        }
        else {
            $this->objParam->addParametro('id_padre',$id_encuesta_padre);
        }
        $this->objFunc=$this->create('MODEncuesta');
        $this->res=$this->objFunc->listarEncuestaArb();
        $this->res->setTipoRespuestaArbol();
        $arreglo=array();

        array_push($arreglo,array('nombre'=>'id','valor'=>'id_encuesta'));
        array_push($arreglo,array('nombre'=>'id_p','valor'=>'id_encuesta_padre'));

        array_push($arreglo, array('nombre' => 'text', 'valores' => '#nro_order#'));
        array_push($arreglo,array('nombre'=>'cls','valor'=>'nombre'));
        array_push($arreglo,array('nombre'=>'qtip','valores'=>'<b> #nro_order#</b><br/><b> #nombre#</b>'));

        /*Estas funciones definen reglas para los nodos en funcion a los tipo de nodos que contenga cada uno*/
        $this->res->addNivelArbol('tipo_nodo', 'raiz', array('leaf' => false, 'draggable' => false, 'allowDelete' => false, 'allowEdit' => true, 'cls' => 'folder', 'tipo_nodo' => 'raiz', 'icon' => '../../../lib/imagenes/a_form_edit.png'), $arreglo);

        // $this->res->addNivelArbol('tipo_nodo', 'hijo', array('leaf' => false, 'draggable' => false, 'allowDelete' => true, 'allowEdit' => true, 'tipo_nodo' => 'hijo', 'icon' => '../../../lib/imagenes/a_form_edit.png'), $arreglo);

        // $this->res->addNivelArbol('tipo_nodo', 'hoja', array('leaf' => true, 'draggable' => false, 'allowDelete' => true, 'allowEdit' => true, 'tipo_nodo' => 'hoja', 'icon' => '../../../lib/imagenes/a_form.png'), $arreglo);

        $this->res->imprimirRespuesta($this->res->generarJson());
    }
				
	function insertarEncuesta(){
		$this->objFunc=$this->create('MODEncuesta');	
		if($this->objParam->insertar('id_encuesta')){
		    // var_dump($this->objParam);exit;
			$this->res=$this->objFunc->insertarEncuesta($this->objParam);			
		} else{			
			$this->res=$this->objFunc->modificarEncuesta($this->objParam);
		}
		$this->res->imprimirRespuesta($this->res->generarJson());
	}
						
	function eliminarEncuesta(){
			$this->objFunc=$this->create('MODEncuesta');	
		$this->res=$this->objFunc->eliminarEncuesta($this->objParam);
		$this->res->imprimirRespuesta($this->res->generarJson());
	}
			
}

?>