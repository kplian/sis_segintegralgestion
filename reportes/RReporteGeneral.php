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
class RReporteGeneral{
    private $docexcel;
    private $objWriter;
    private $equivalencias=array();
    private $objParam;
    private $content = array();
    private $nombres = array();
    private $content2 = array();
    private $nombres2 = array();
    public  $url_archivo;
    private $titulo = array();
    private $list = 0;
    private $list2 = 0;

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
        $styleFuncionario = array(
            'font'  => array(
                'bold'  => true,
                'size'  => 7,
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
                    'rgb' => 'C9DAE2'
                )
            ),
        );
        $styleCatalogo = array(
            'font'  => array(
                'bold'  => true,
                'size'  => 7,
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
                    'rgb' => 'C9DAE2'
                )
            ),
        );
        $styleCatalogo4 = array(
            'font'  => array(
                'bold'  => true,
                'size'  => 6,
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
                    'rgb' => 'F0C859'
                )
            ),
        );
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
        $datos = $this->objParam->getParametro('datos');
        $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(1,3,$this->objParam->getParametro('encuesta'));
        $this->docexcel->getActiveSheet()->getColumnDimension('B')->setWidth(50);
        $this->docexcel->getActiveSheet()->getColumnDimension('C')->setWidth(50);
        $this->docexcel->getActiveSheet()->getColumnDimension('D')->setWidth(10);
        $this->docexcel->getActiveSheet()->getStyle('B5:D7')->applyFromArray($styleFuncionario);
        $this->docexcel->getActiveSheet()->getStyle('B5:D7')->getAlignment()->setWrapText(true);
        $this->docexcel->getActiveSheet()->setCellValue('B5','Nombre');
        $this->docexcel->getActiveSheet()->mergeCells('B5:B7');
        $this->docexcel->getActiveSheet()->setCellValue('C5','Cargo');
        $this->docexcel->getActiveSheet()->mergeCells('C5:C7');
        $this->docexcel->getActiveSheet()->setCellValue('D5','Ger.');
        $this->docexcel->getActiveSheet()->mergeCells('D5:D7');

        foreach ($datos as $value) {
            if (!array_key_exists($value['evaluacion'], $this->titulo) ||
                !array_key_exists($value['peso_encuesta'], $this->titulo[$value['evaluacion']])) {
                $this->titulo[$value['evaluacion']][$value['peso_encuesta']] = 1;
            } else {
                $this->titulo[$value['evaluacion']][$value['peso_encuesta']]++;
            }
        }
        $columna = 4;
        foreach ($this->titulo as $item => $key){
            $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow($columna, 5, $item);
            $this->docexcel->getActiveSheet()->getColumnDimension($this->equivalencias[$columna])->setWidth(25);
            $this->docexcel->getActiveSheet()->getStyle($this->equivalencias[4] . "5:" . $this->equivalencias[$columna] . "7")->getAlignment()->setWrapText(true);
            $this->docexcel->getActiveSheet()->getStyle($this->equivalencias[4] . "5:" . $this->equivalencias[$columna] . "7")->applyFromArray($styleCatalogo);
            $this->docexcel->getActiveSheet()->mergeCells($this->equivalencias[$columna] . "5:" . $this->equivalencias[$columna] . "7");
            $columna++;
            $this->list = $columna;
        }
        $this->docexcel->getActiveSheet()->getStyle("B3:" . $this->equivalencias[$this->list - 1] . "4")->applyFromArray($styleTitulos);
        $this->docexcel->getActiveSheet()->mergeCells("B3:" . $this->equivalencias[$this->list - 1] . "4");
    }
    function generarDatos(){
        $this->imprimeCabecera();
        $styleTitulos = array(
            'borders' => array(
                'allborders' => array(
                    'style' => PHPExcel_Style_Border::BORDER_THIN
                )
            ),
            'fill' => array(
                'type' => PHPExcel_Style_Fill::FILL_SOLID,
                'color' => array(
                    'rgb' => 'F8FAFB'
                )
            ),
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
        $datos = $this->objParam->getParametro('datos');

        foreach ($datos as $value) {
            if (!array_key_exists($value['desc_funcionario1'], $this->nombres) ||
                !array_key_exists($value['descripcion_cargo'], $this->nombres[$value['desc_funcionario1']]) ||
                !array_key_exists($value['gerencia'], $this->nombres[$value['desc_funcionario1']][$value['descripcion_cargo']])
            ) {
                $this->nombres[$value['desc_funcionario1']][$value['descripcion_cargo']][$value['gerencia']] = 1;
            } else {
                $this->nombres[$value['desc_funcionario1']][$value['descripcion_cargo']][$value['gerencia']] ++;
            }
        }
        $fill = 8;
        // var_dump($this->nombres);exit;
        foreach ($this->nombres as $nombre => $key){
            $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(1, $fill, $nombre);
            $this->docexcel->getActiveSheet()->getStyle($this->equivalencias[1] . $fill . ":" . $this->equivalencias[$this->list - 1] . $fill)->applyFromArray($styleTitulos);
            foreach ($key as $cargo => $key2){
                $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(2, $fill, $cargo);
                foreach ($key2 as $ger => $key4){
                    $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(3, $fill, $ger);
                }
            }
            $fill ++;
        }
       // nombres
        foreach ($datos as $value) {
            if (!array_key_exists($value['desc_funcionario1'], $this->content) ||
                !array_key_exists($value['evaluacion'], $this->content[$value['desc_funcionario1']]) ||
                !array_key_exists($value['resultado'], $this->content[$value['evaluado']][$value['evaluacion']])
            ) {
                $this->content[$value['desc_funcionario1']][$value['evaluacion']][$value['resultado']] = 1;
            } else {
                $this->content[$value['desc_funcionario1']][$value['evaluacion']][$value['resultado']] ++;
            }
        }

        $fila = 8;
        foreach ($this->content as $funcionario => $key){
            $columna = 4;
            foreach ($key as $evaluacion => $key2){
                foreach ($key2 as  $resultado => $key3){
                    $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow($columna, $fila, $resultado);
                    $columna++;
                }
            }
            $fila++;
        }
        $this->imprimeCabecera3();
    }
    function imprimeCabecera3() {
        $this->docexcel->createSheet(1);
        $this->docexcel->setActiveSheetIndex(1);
        $this->docexcel->getActiveSheet()->setTitle('Puntaje');
        $styleFuncionario = array(
            'font'  => array(
                'bold'  => true,
                'size'  => 7,
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
                    'rgb' => 'C9DAE2'
                )
            ),
        );
        $styleCatalogo = array(
            'font'  => array(
                'bold'  => true,
                'size'  => 7,
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
                    'rgb' => 'C9DAE2'
                )
            ),
        );
        $styleCatalogo4 = array(
            'font'  => array(
                'bold'  => true,
                'size'  => 6,
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
                    'rgb' => 'F0C859'
                )
            ),
        );
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
        $datos = $this->objParam->getParametro('datos');
        $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(1,3,'PUNTAJE GENERAL EVALUACION DESEMPEÑO VALORES 360 GRADOS');
        $this->docexcel->getActiveSheet()->getColumnDimension('B')->setWidth(50);
        $this->docexcel->getActiveSheet()->getColumnDimension('C')->setWidth(50);
        $this->docexcel->getActiveSheet()->getColumnDimension('D')->setWidth(10);
        $this->docexcel->getActiveSheet()->getStyle('B5:D7')->applyFromArray($styleFuncionario);
        $this->docexcel->getActiveSheet()->getStyle('B5:D7')->getAlignment()->setWrapText(true);
        $this->docexcel->getActiveSheet()->setCellValue('B5','Nombre');
        $this->docexcel->getActiveSheet()->mergeCells('B5:B7');
        $this->docexcel->getActiveSheet()->setCellValue('C5','Cargo');
        $this->docexcel->getActiveSheet()->mergeCells('C5:C7');
        $this->docexcel->getActiveSheet()->setCellValue('D5','Ger.');
        $this->docexcel->getActiveSheet()->mergeCells('D5:D7');
        foreach ($datos as $value) {
            if (!array_key_exists($value['evaluacion'], $this->titulo) ||
                !array_key_exists($value['peso_encuesta'], $this->titulo[$value['evaluacion']])) {
                $this->titulo[$value['evaluacion']][$value['peso_encuesta']] = 1;
            } else {
                $this->titulo[$value['evaluacion']][$value['peso_encuesta']]++;
            }
        }
        $dibujar =  array_merge($this->titulo, array('PUNTAJE TOTAL' => array()));
        $columna = 4;
        $total = 0;
        foreach ($dibujar as $item => $key){
            $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow($columna, 5, $item);
            $this->docexcel->getActiveSheet()->getColumnDimension($this->equivalencias[$columna])->setWidth(25);
            $this->docexcel->getActiveSheet()->getStyle($this->equivalencias[4] . "5:" . $this->equivalencias[$columna] . "6")->getAlignment()->setWrapText(true);
            $this->docexcel->getActiveSheet()->getStyle($this->equivalencias[4] . "5:" . $this->equivalencias[$columna] . "6")->applyFromArray($styleCatalogo);
            $this->docexcel->getActiveSheet()->mergeCells($this->equivalencias[$columna] . "5:" . $this->equivalencias[$columna] . "6");
            foreach ($key as $item1 => $key2){
                $total = $item1 + $total ;
                $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow($columna,7, $item1);
                $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow($columna + 1,7, $total);
                $this->docexcel->getActiveSheet()->getStyle($this->equivalencias[4] . "7:" . $this->equivalencias[$columna] . "7")->getAlignment()->setWrapText(true);
                $this->docexcel->getActiveSheet()->getStyle($this->equivalencias[4] . "7:" . $this->equivalencias[$columna + 1] . "7")->applyFromArray($styleCatalogo4);
            }
            $columna++;
            $this->list2 = $columna;
        }
        $this->docexcel->getActiveSheet()->getStyle("B3:" . $this->equivalencias[$this->list2 - 1] . "4")->applyFromArray($styleTitulos);
        $this->docexcel->getActiveSheet()->mergeCells("B3:" . $this->equivalencias[$this->list2 - 1] . "4");

        $styleTitulos = array(
            'borders' => array(
                'allborders' => array(
                    'style' => PHPExcel_Style_Border::BORDER_THIN
                )
            ),
            'fill' => array(
                'type' => PHPExcel_Style_Fill::FILL_SOLID,
                'color' => array(
                    'rgb' => 'F8FAFB'
                )
            ),
        );
        $datos = $this->objParam->getParametro('datos');
        foreach ($datos as $value) {
            if (!array_key_exists($value['desc_funcionario1'], $this->nombres2) ||
                !array_key_exists($value['descripcion_cargo'], $this->nombres2[$value['desc_funcionario1']]) ||
                !array_key_exists($value['gerencia'], $this->nombres[$value['desc_funcionario1']][$value['descripcion_cargo']])
            ) {
                $this->nombres2[$value['desc_funcionario1']][$value['descripcion_cargo']][$value['gerencia']] = 1;
            } else {
                $this->nombres2[$value['desc_funcionario1']][$value['descripcion_cargo']][$value['gerencia']] ++;
            }
        }
        $fill = 8;
        foreach ($this->nombres2 as $nombre => $key){
            $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(1, $fill, $nombre);
            $this->docexcel->getActiveSheet()->getStyle($this->equivalencias[1] . $fill . ":" . $this->equivalencias[$this->list2 - 1] . $fill)->applyFromArray($styleTitulos);
            foreach ($key as $cargo => $key2){
                $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(2, $fill, $cargo);
                foreach ($key2 as $ger => $key4){
                    $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(3, $fill, $ger);
                }
            }
            $fill ++;
        }
        // nombres
        foreach ($datos as $value) {
            if (!array_key_exists($value['desc_funcionario1'], $this->content2) ||
                !array_key_exists($value['evaluacion'], $this->content2[$value['desc_funcionario1']]) ||
                !array_key_exists($value['puntaje'], $this->content2[$value['evaluado']][$value['evaluacion']])
            ) {
                $this->content2[$value['desc_funcionario1']][$value['evaluacion']][$value['puntaje']] = 1;
            } else {
                $this->content2[$value['desc_funcionario1']][$value['evaluacion']][$value['puntaje']] ++;
            }
        }
        $fila = 8;
        foreach ($this->content2 as $funcionario => $key){
       //     $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(0, $fila, $funcionario);
            $columna = 4;
            foreach ($key as $evaluacion => $key2){
                foreach ($key2 as  $resultado => $key3){
                    $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow($columna, $fila, $resultado);
                    $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow($this->list2 - 1 , $fila, "=SUM(" . $this->equivalencias[4] . "$fila:" . $this->equivalencias[$columna] ."$fila)");
                    $columna++;
                }
            }
            $fila++;
        }
    }
    function generarReporte(){
        $this->objWriter = PHPExcel_IOFactory::createWriter($this->docexcel, 'Excel5');
        $this->objWriter->save($this->url_archivo);

    }

}
?>