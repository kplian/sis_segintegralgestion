<?php
/**
 *@package pXP
 *@file RReporteRegistrosVentaCC
 *@author  (Miguel Mamani)
 *@date 19/12/2108
 *@description Archivo con la interfaz de usuario que permite la ejecucion de todas las funcionalidades del sistema
 * HISTORIAL DE MODIFICACIONES:
 * ISSUE 		   FECHA   			 AUTOR				 DESCRIPCION:
 *
 */
class RReporteCuestionario{
    private $docexcel;
    private $objWriter;
    private $equivalencias=array();
    private $objParam;
    public  $url_archivo;
    private $titulos = array();
    private $content = array();
    private $funcionario = array();
    private $list = 0;

    function __construct(CTParametro $objParam){
        $this->objParam = $objParam;
        $this->url_archivo = "../../../reportes_generados/".$this->objParam->getParametro('nombre_archivo');
        set_time_limit(400);
        $cacheMethod = PHPExcel_CachedObjectStorageFactory:: cache_to_phpTemp;
        $cacheSettings = array('memoryCacheSize'  => '10MB');
        PHPExcel_Settings::setCacheStorageMethod($cacheMethod, $cacheSettings);

        $this->docexcel = new PHPExcel();
        $this->docexcel->getProperties()->setCreator("PXP")
            ->setLastModifiedBy("PXP")
            ->setTitle($this->objParam->getParametro('titulo_archivo'))
            ->setSubject($this->objParam->getParametro('titulo_archivo'))
            ->setDescription('Reporte "'.$this->objParam->getParametro('titulo_archivo').'", generado por el framework PXP')
            ->setKeywords("office 2007 openxml php")
            ->setCategory("Report File");
        $this->equivalencias=array( 0=>'A',1=>'B',2=>'C',3=>'D',4=>'E',5=>'F',6=>'G',7=>'H',8=>'I',
            9=>'J',10=>'K',11=>'L',12=>'M',13=>'N',14=>'O',15=>'P',16=>'Q',17=>'R',
            18=>'S',19=>'T',20=>'U',21=>'V',22=>'W',23=>'X',24=>'Y',25=>'Z',
            26=>'AA',27=>'AB',28=>'AC',29=>'AD',30=>'AE',31=>'AF',32=>'AG',33=>'AH',
            34=>'AI',35=>'AJ',36=>'AK',37=>'AL',38=>'AM',39=>'AN',40=>'AO',41=>'AP',
            42=>'AQ',43=>'AR',44=>'AS',45=>'AT',46=>'AU',47=>'AV',48=>'AW',49=>'AX',
            50=>'AY',51=>'AZ',
            52=>'BA',53=>'BB',54=>'BC',55=>'BD',56=>'BE',57=>'BF',58=>'BG',59=>'BH',
            60=>'BI',61=>'BJ',62=>'BK',63=>'BL',64=>'BM',65=>'BN',66=>'BO',67=>'BP',
            68=>'BQ',69=>'BR',70=>'BS',71=>'BT',72=>'BU',73=>'BV',74=>'BW',75=>'BX',
            76=>'BY',77=>'BZ');
    }

    function imprimeCabecera() {
        $this->docexcel->createSheet();
        $this->docexcel->getActiveSheet()->setTitle('Encuesta');
        $this->docexcel->setActiveSheetIndex(0);

        $styleTitulos = array(
            'font'  => array(
                'bold'  => true,
                'size'  => 12,
                'name'  => 'Arial',
                'color' => array(
                    'rgb' => 'FFFFFF'
                )
            ),
            'alignment' => array(
                'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
                'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,
            ),
            'fill' => array(
                'type' => PHPExcel_Style_Fill::FILL_SOLID,
                'color' => array(
                    'rgb' => '022D8A'
                )
            ),
        );
        $styleFuncionario = array(
            'font'  => array(
                'bold'  => true,
                'size'  => 10,
                'name'  => 'Arial',
                'color' => array(
                    'rgb' => '070707'
                )
            ),
            'borders' => array(
                'allborders' => array(
                    'style' => PHPExcel_Style_Border::BORDER_THIN
                )
            ),
            'alignment' => array(
                'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
                'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,
            ),
            'fill' => array(
                'type' => PHPExcel_Style_Fill::FILL_SOLID,
                'color' => array(
                    'rgb' => '99E4F1'
                )
            ),
        );
        $styleCatalogo = array(
            'font'  => array(
                'bold'  => true,
                'size'  => 10,
                'name'  => 'Arial',
                'color' => array(
                    'rgb' => '070707'
                )
            ),
            'borders' => array(
                'allborders' => array(
                    'style' => PHPExcel_Style_Border::BORDER_THIN
                )
            ),
            'alignment' => array(
                'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
                'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,
            ),
            'fill' => array(
                'type' => PHPExcel_Style_Fill::FILL_SOLID,
                'color' => array(
                    'rgb' => 'F9F7C3'
                )
            ),
        );
        $styleCatalogo2 = array(
            'font'  => array(
                'bold'  => true,
                'size'  => 10,
                'name'  => 'Arial',
                'color' => array(
                    'rgb' => '070707'
                )
            ),
            'borders' => array(
                'allborders' => array(
                    'style' => PHPExcel_Style_Border::BORDER_THIN
                )
            ),
            'alignment' => array(
                'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
                'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,
            ),
            'fill' => array(
                'type' => PHPExcel_Style_Fill::FILL_SOLID,
                'color' => array(
                    'rgb' => 'E6F9C3'
                )
            ),
        );

        //modificacionw
        $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(1,3,$this->objParam->getParametro('datos')[0]['titulo']);


        $this->docexcel->getActiveSheet()->getColumnDimension('B')->setWidth(50);
        $this->docexcel->getActiveSheet()->getColumnDimension('C')->setWidth(50);
        $this->docexcel->getActiveSheet()->getColumnDimension('D')->setWidth(10);
        $this->docexcel->getActiveSheet()->getStyle('B5:D6')->applyFromArray($styleFuncionario);
        $this->docexcel->getActiveSheet()->getStyle('B5:D6')->getAlignment()->setWrapText(true);

        $this->docexcel->getActiveSheet()->setCellValue('B5','Nombre');
        $this->docexcel->getActiveSheet()->mergeCells('B5:B6');

        $this->docexcel->getActiveSheet()->setCellValue('C5','Cargo');
        $this->docexcel->getActiveSheet()->mergeCells('C5:C6');

        $this->docexcel->getActiveSheet()->setCellValue('D5','Ger.');
        $this->docexcel->getActiveSheet()->mergeCells('D5:D6');

        $datos = $this->objParam->getParametro('datos');

        foreach ($datos as $value){
            if (!array_key_exists($value['grupo'], $this->titulos) ||
                !array_key_exists($value['nombre_cat'], $this->titulos[$value['grupo']])) {
                $this->titulos[$value['grupo']][$value['nombre_cat']] = 1;
            } else {
                $this->titulos[$value['grupo']][$value['nombre_cat']]++;
            }
        }
        $columnaSub = 4;
        $num = 1;
        $columna = 4;

        // var_dump($this->titulos);exit;

        foreach ($this->titulos as $value => $key){
            $resultado = array_merge($key, array('Promedio'=>''));
            /*if ($num == 1){
                $col = $columna ;
            }else{
                $col =  count($resultado) +  $columna - 1;
            }*/
            foreach ($resultado as $item => $key2){
                $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow($columnaSub,6, $item);
                $this->docexcel->getActiveSheet()->getColumnDimension($this->equivalencias[$columnaSub])->setWidth(13);
                $this->docexcel->getActiveSheet()->getStyle($this->equivalencias[4] . "6:" . $this->equivalencias[$columnaSub] . "6")->getAlignment()->setWrapText(true);
                $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow($columna ,5, $value);

                $columnaSub ++;
                $this->list= $columnaSub ;

            }

            //  $this->docexcel->getActiveSheet()->mergeCells($this->equivalencias[$col]."5:".$this->equivalencias[$col +  count($resultado) - 1]."5");
            // if ($num == 1 ){
               //  $this->docexcel->getActiveSheet()->getStyle($this->equivalencias[$col]."5:".$this->equivalencias[$col +  count($resultado) - 1]."6")->applyFromArray($styleCatalogo);
            // }else{
           //     $this->docexcel->getActiveSheet()->getStyle($this->equivalencias[$col]."5:".$this->equivalencias[$col +  count($resultado) - 1]."6")->applyFromArray($styleCatalogo2);
            // }
            $columna ++;
            $num++;
        }
        $this->docexcel->getActiveSheet()->getStyle("B3:".$this->equivalencias[$this->list - 1 ]."4")->applyFromArray($styleTitulos);
        $this->docexcel->getActiveSheet()->mergeCells("B3:".$this->equivalencias[$this->list - 1]."4");
    }
    function generarDatos(){
        $this->imprimeCabecera();
        $datos = $this->objParam->getParametro('datos');
        $fila = 7;
        $styleTitulos = array(
            'borders' => array(
                'allborders' => array(
                    'style' => PHPExcel_Style_Border::BORDER_THIN
                )
            )
        );
        $styleTitulos2 = array(
            'font'  => array(
                'bold'  => true,
                'size'  => 11,
                'name'  => 'Arial'
            ),
            'fill' => array(
                'type' => PHPExcel_Style_Fill::FILL_SOLID,
                'color' => array(
                    'rgb' => 'D6EAF8'
                )
            ),
            'borders' => array(
                'allborders' => array(
                    'style' => PHPExcel_Style_Border::BORDER_THIN
                )
            ));
        foreach ($datos as $value){
            if (!array_key_exists($value['evaluado'],$this->content) ||
                !array_key_exists($value['descripcion_cargo'],$this->content[$value['evaluado']]) ||
                !array_key_exists($value['gerencia'],$this->content[$value['evaluado']][$value['descripcion_cargo']])||
                !array_key_exists($value['evaluador'],$this->content[$value['evaluado']][$value['descripcion_cargo']][$value['gerencia']]) ||
                !array_key_exists($value['grupo'],$this->content[$value['evaluado']][$value['descripcion_cargo']][$value['gerencia']])[$value['evaluador']] ||
                !array_key_exists($value['nombre_cat'],$this->content[$value['evaluado']][$value['descripcion_cargo']][$value['gerencia']])[$value['grupo']] ||
                !array_key_exists($value['resp'],$this->content[$value['evaluado']][$value['descripcion_cargo']][$value['gerencia']])[$value['grupo']][$value['nombre_cat']] ) {

                $this->content[$value['evaluado']][$value['descripcion_cargo']][$value['gerencia']][$value['evaluador']][$value['grupo']][$value['nombre_cat']][$value['resp']] = 1;
            } else {
                $this->content[$value['evaluado']][$value['descripcion_cargo']][$value['gerencia']][$value['evaluador']][$value['grupo']][$value['nombre_cat']] [$value['resp']] ++;
            }
        }
        foreach ($datos as $value){
            if(!array_key_exists($value['evaluador'],$this->funcionario) ||
                !array_key_exists($value['evaluado'],$this->funcionario[$value['evaluador']])){
                $this->funcionario[$value['evaluador']][$value['evaluado']] = 1;
            }else{
                $this->funcionario[$value['evaluador']][$value['evaluado']] ++;
            }
        }
        $ger ='';
        $eva ='';
        $e ='';
        foreach ($this->content as $funcionario =>$key) {
            foreach ($key as $cargo => $key2) {
                foreach ($key2 as $gerncia => $key3){
                    if ($gerncia != $ger) {
                        $this->imprimeSubtitulo($fila, $gerncia);
                        $ger = $gerncia;
                        $fila++;
                    }
                    foreach ($key3 as $evaluador => $key4) {

                     //   if ($this->objParam->getParametro('datos')[0]['tipo'] != 'auto_evaluacion') {
                            if ($evaluador != $eva) {
                                if ($eva != '') {
                                    $fila = $fila + 2;
                                }
                                $this->imprimeEvaludaro($fila, $evaluador);
                                $eva = $evaluador;
                                $fila++;
                            }
                      //  }
                        $columna = 4;
                        foreach ($key4 as $grupo => $key5){
                            $contar = count($key5);
                            $resultado = array_merge($key5, array('Promedio'=> array(0)));
                            $sum = 0;
                            foreach ($resultado as $item => $key6){
                                foreach ($key6 as $resul => $key7){
                                    if ($resul == 0){
                                        $resul = $sum / $contar ;
                                        $sum = 0;
                                    }
                                    $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow($columna, $fila, $resul);
                                    $fin =  $fila ;
                                    $ini = $fila - count($this->funcionario[$evaluador]);
                                    $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow($columna, $fila + 1,"=SUM(".$this->equivalencias[$columna]."$ini:".$this->equivalencias[$columna]."$fin)");
                                    $sum = $sum + $resul;
                                }
                                $columna ++;
                            }
                        }
                        if ($evaluador != $e) {
                             $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(1, $fila + count($this->funcionario[$evaluador]), 'TOTALES  PROMEDIO');
                             $total = $fila + count($this->funcionario[$evaluador]);
                             $this->docexcel->getActiveSheet()->mergeCells("B".$total.":D".$total);
                             $this->docexcel->getActiveSheet()->getStyle($this->equivalencias[1] . $total.":" . $this->equivalencias[$this->list-1] . $total)->applyFromArray($styleTitulos2);
                            $e = $evaluador;
                        }
                    }
                    $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(2, $fila, $cargo);
                }
            }
            $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(1, $fila, $funcionario);
            $this->docexcel->getActiveSheet()->getStyle($this->equivalencias[1] . $fila.":" . $this->equivalencias[$this->list-1] . $fila)->applyFromArray($styleTitulos);
            $fila++;
        }
    }
    function imprimeSubtitulo($fila, $valor) {
        $styleTitulos = array(
            'font'  => array(
                'bold'  => true,
                'size'  => 10,
                'name'  => 'Arial'
            ),
            'fill' => array(
                'type' => PHPExcel_Style_Fill::FILL_SOLID,
                'color' => array(
                    'rgb' => 'D7DDD8'
                )
            ),
            'borders' => array(
                'allborders' => array(
                    'style' => PHPExcel_Style_Border::BORDER_THIN
                )
            ));


        $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(1, $fila, $valor);
        $this->docexcel->getActiveSheet()->getStyle($this->equivalencias[1] . $fila.":" . $this->equivalencias[$this->list-1] . $fila)->applyFromArray($styleTitulos);
        $this->docexcel->getActiveSheet()->mergeCells($this->equivalencias[1] . $fila.":" . $this->equivalencias[$this->list-1] . $fila);

    }
    function imprimeEvaludaro($fila, $valor) {
        $styleTitulos = array(
            'font'  => array(
                'bold'  => true,
                'size'  => 9,
                'name'  => 'Arial'
            ),
            'fill' => array(
                'type' => PHPExcel_Style_Fill::FILL_SOLID,
                'color' => array(
                    'rgb' => 'F9C7C3'
                )
            ),
            'borders' => array(
                'allborders' => array(
                    'style' => PHPExcel_Style_Border::BORDER_THIN
                )
            ));


        $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(1, $fila, $valor);
        $this->docexcel->getActiveSheet()->getStyle($this->equivalencias[1] . $fila.":" . $this->equivalencias[$this->list-1] . $fila)->applyFromArray($styleTitulos);
        $this->docexcel->getActiveSheet()->mergeCells($this->equivalencias[1] . $fila.":" . $this->equivalencias[$this->list-1] . $fila);
    }

    function generarReporte(){
        $this->objWriter = PHPExcel_IOFactory::createWriter($this->docexcel, 'Excel5');
        $this->objWriter->save($this->url_archivo);

    }

}
?>