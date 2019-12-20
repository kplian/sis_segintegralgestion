<?php
/**
*@package pXP
*@file gen-ACTLinea.php
*@author  (admin)
*@date 11-04-2017 20:20:49
*@description Clase que recibe los parametros enviados por la vista para mandar a la capa de Modelo
*/

class ACTLinea extends ACTbase{    
			
	function listarLinea(){
		$this->objParam->defecto('ordenacion','id_linea');

		$this->objParam->defecto('dir_ordenacion','asc');
		if($this->objParam->getParametro('tipoReporte')=='excel_grid' || $this->objParam->getParametro('tipoReporte')=='pdf_grid'){
			$this->objReporte = new Reporte($this->objParam,$this);
			$this->res = $this->objReporte->generarReporteListado('MODLinea','listarLinea');
		} else{
			$this->objFunc=$this->create('MODLinea');
			
			$this->res=$this->objFunc->listarLinea($this->objParam);
		}
		$this->res->imprimirRespuesta($this->res->generarJson());
	}
    
    function listarLineaArb(){
		$node = $this->objParam->getParametro('node');
		//$clasificacion = $this->objParam->getParametro('clasificacion');								
        $id_linea = $this->objParam->getParametro('id_linea');

        $tipo_nodo = $this->objParam->getParametro('tipo_nodo');


        /* if($this->objParam->getParametro('id_plan') > 0){
             $this->objParam->addFiltro(" linea.id_plan = ".$this->objParam->getParametro('id_plan'));
         }else{
             $this->objParam->addFiltro(" linea.id_plan = 0 ");

         }*/
        if ($node == 'id') {
            $this->objParam->addParametro('id_padre', '%');
        } else {
            $this->objParam->addParametro('id_padre', $id_linea);
        }
       //  $this->objParam->addParametro('id_plan', $this->objParam->getParametro('id_plan'));
		//$this->objParam->addParametro('clasificacion', $clasificacion);
								
        $this->objFunc = $this->create('MODLinea');
        $this->res = $this->objFunc->listarLineaArb();
						
        $this->res->setTipoRespuestaArbol();
								
        $arreglo = array();

        //$arreglo_valores=array();
		
		//para cambiar un valor por otro en una variable
		// array_push($arreglo_valores,array('variable'=>'checked','val_ant'=>'true','val_nue'=>true));
		// array_push($arreglo_valores,array('variable'=>'checked','val_ant'=>'false','val_nue'=>false));
		// $this->res->setValores($arreglo_valores);


        array_push($arreglo, array('nombre' => 'id', 'valor' => 'id_linea'));
        array_push($arreglo, array('nombre' => 'id_p', 'valor' => 'id_linea_padre'));

        array_push($arreglo, array('nombre' => 'text', 'valores' => '#nombre_linea#'));
        array_push($arreglo, array('nombre' => 'cls', 'valor' => 'peso'));
        array_push($arreglo, array('nombre' => 'qtip', 'valores' => '<b>#id_linea# </b><br><b>#nombre_linea#</b><br/>#peso#'));
		
        /*Estas funciones definen reglas para los nodos en funcion a los tipo de nodos que contenga cada uno*/
        $this->res->addNivelArbol('tipo_nodo', 'raiz', array('leaf' => false, 'draggable' => false, 'allowDelete' => false, 'allowEdit' => true, 'cls' => 'folder', 'tipo_nodo' => 'raiz', 'icon' => '../../../lib/imagenes/a_form_edit.png'), $arreglo);

        $this->res->addNivelArbol('tipo_nodo', 'hijo', array('leaf' => false, 'draggable' => false, 'allowDelete' => true, 'allowEdit' => true, 'tipo_nodo' => 'hijo', 'icon' => '../../../lib/imagenes/a_form_edit.png'), $arreglo);

        $this->res->addNivelArbol('tipo_nodo', 'hoja', array('leaf' => true, 'draggable' => false, 'allowDelete' => true, 'allowEdit' => true, 'tipo_nodo' => 'hoja', 'icon' => '../../../lib/imagenes/a_form.png'), $arreglo);

    
        //Se imprime el arbol en formato JSON
        //var_dump($this->res->generarJson());exit;
        $this->res->imprimirRespuesta($this->res->generarJson());
	}
				
	function insertarLinea(){
		$this->objFunc=$this->create('MODLinea');	
		if($this->objParam->insertar('id_linea')){
			$this->res=$this->objFunc->insertarLinea($this->objParam);			
		} else{			
			$this->res=$this->objFunc->modificarLinea($this->objParam);
		}
		$this->res->imprimirRespuesta($this->res->generarJson());
	}
						
	function eliminarLinea(){
			$this->objFunc=$this->create('MODLinea');	
		$this->res=$this->objFunc->eliminarLinea($this->objParam);
		$this->res->imprimirRespuesta($this->res->generarJson());
	}
			
}

?>