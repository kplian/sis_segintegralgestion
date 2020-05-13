<?php
class reporteCuestionario
{
    private $docexcel;
	private $objWriter;
	private $numero;
	private $equivalencias=array();
	private $objParam;
	public  $url_archivo;
	function __construct(CTParametro $objParam)
	{
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
		$this->printerConfiguration();
	}
	function datosHeader ($detalle) {
		$this->datos_detalle = $detalle;
    }
    function generarReporte(){
		//pendientes
		$this->docexcel->setActiveSheetIndex(0);
		//$this->imprimeTitulo($sheet,0);
		$this->imprimeCabecera();
		$this->generarDatos();
		$this->objWriter = PHPExcel_IOFactory::createWriter($this->docexcel, 'Excel5');
		$this->objWriter->save($this->url_archivo);
    }	
    //
	function imprimeCabecera() {
        $datos = $this->objParam->getParametro('datos');
		$this->docexcel->createSheet();		
		$this->docexcel->getActiveSheet()->setTitle($datos[0]['cuestionario']);	
		$this->docexcel->setActiveSheetIndex(0);		
		$styleTitulos1 = array(
			'font'  => array(
			    'bold'  => false,
			    'size'  => 15
			),
			'alignment' => array(
			    'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
			    'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,
			),
		);
		$styleTitulos2 = array(
			'font'  => array(
			    'bold'  => true,
			    'size'  => 8,
			    'name'  => 'Arial',
			    'color' => array(
					'rgb' => '000000'
			    )
			),
			'alignment' => array(
			    'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
			    'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,
			),
			'fill' => array(
			    'type' => PHPExcel_Style_Fill::FILL_SOLID,
			    'color' => array(
			        'rgb' => 'FFFFFF'
			    )
			),
			'borders' => array(
			    'allborders' => array(
					'style' => PHPExcel_Style_Border::BORDER_THIN
			    )
			)
		);
		$styleTitulos3 = array(
			'font'  => array(
				'bold'  => true,
				'size'  => 11,
				'name'  => 'Arial'
			),
			'alignment' => array(
				'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
				'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,
			),
		);                
        //LOGO        
        $this->docexcel->getActiveSheet()->mergeCells('A1:A3');
        $this->docexcel->getActiveSheet()->mergeCells('A1:B1');
        //TITULO
        $this->docexcel->getActiveSheet()->mergeCells('C1:C3');
        $this->docexcel->getActiveSheet()->mergeCells('C1:K1');
        $this->docexcel->getActiveSheet()->getStyle('C1:K1')->applyFromArray($styleTitulos2);        
        $this->docexcel->getActiveSheet()->getStyle('C1:K1')->getAlignment()->setWrapText(true);
        $this->docexcel->getActiveSheet()->setCellValue('C1',$datos[0]['cuestionario']);
        //CODIGO?        
        $this->docexcel->getActiveSheet()->mergeCells('L1:L3');	
        $this->docexcel->getActiveSheet()->mergeCells('L1:M1');		
		$this->docexcel->getActiveSheet()->getStyle('L1:M1')->applyFromArray($styleTitulos2);        
        $this->docexcel->getActiveSheet()->getStyle('L1:M1')->getAlignment()->setWrapText(true);
        $this->docexcel->getActiveSheet()->setCellValue('L1','XXX');
        //*************************************Cabecera*****************************************
        $this->docexcel->getActiveSheet()->mergeCells('A5:A9');
        $this->docexcel->getActiveSheet()->mergeCells('A5:E5');
        $this->docexcel->getActiveSheet()->setCellValue('A5','Valores  Corporativos');
        
		$this->docexcel->getActiveSheet()->setCellValue('F5','A=Aplica');
		$this->docexcel->getActiveSheet()->setCellValue('G5','Peso');
		$this->docexcel->getActiveSheet()->setCellValue('H5','Excelente');
		$this->docexcel->getActiveSheet()->setCellValue('I5','Destacable');
        $this->docexcel->getActiveSheet()->setCellValue('J5','Acorde a la posición');
        $this->docexcel->getActiveSheet()->setCellValue('K5','En desarrollo');
        $this->docexcel->getActiveSheet()->setCellValue('L5','A desarrollar');
        $this->docexcel->getActiveSheet()->setCellValue('M5','Comentario');
        $this->docexcel->getActiveSheet()->mergeCells('M5:N5');
        $this->docexcel->getActiveSheet()->setCellValue('O5','% FINAL');
			
		$this->docexcel->getActiveSheet()->getColumnDimension('A')->setWidth(20);
		/*$this->docexcel->getActiveSheet()->getColumnDimension('C')->setWidth(12);
		$this->docexcel->getActiveSheet()->getColumnDimension('D')->setWidth(15);
		$this->docexcel->getActiveSheet()->getColumnDimension('E')->setWidth(12);
		$this->docexcel->getActiveSheet()->getColumnDimension('F')->setWidth(15);
        $this->docexcel->getActiveSheet()->getColumnDimension('G')->setWidth(30);	
        $this->docexcel->getActiveSheet()->getColumnDimension('H')->setWidth(30);	
        $this->docexcel->getActiveSheet()->getColumnDimension('I')->setWidth(8);	
        $this->docexcel->getActiveSheet()->getColumnDimension('J')->setWidth(12);
        $this->docexcel->getActiveSheet()->getColumnDimension('K')->setWidth(12);
        $this->docexcel->getActiveSheet()->getColumnDimension('L')->setWidth(12);*/
        
	}
	//
	function generarDatos()
	{	
        $styleTitulos2 = array(
			'font'  => array(
				'bold'  => FALSE,
				'size'  => 8,
				'name'  => 'Arial'
			),
			'alignment' => array(
				'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
				'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,
			),
		);
		$styleTitulos3 = array(
			'font'  => array(
				'bold'  => true,
				'size'  => 12,
				'name'  => 'Arial',
				'color' => array(
					'rgb' => 'FFFFFF'
				)
			),
			'alignment' => array(
				'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_RIGHT,
				'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER
			),
			'fill' => array(
				'type' => PHPExcel_Style_Fill::FILL_SOLID,
				'color' => array(
					'rgb' => '707A82'
				)
			),
			'borders' => array(
				'allborders' => array(
					'style' => PHPExcel_Style_Border::BORDER_THIN
				)
			)
        );
        $styleTitulos = array(
			'font'  => array(
			    'bold'  => true,
			    'size'  => 9,
			    'name'  => 'Arial',
			    'color' => array(
					'rgb' => 'FFFFFF'
			    )
			),
			'alignment' => array(
			    'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_RIGHT,
			    'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,
			),
			'fill' => array(
			    'type' => PHPExcel_Style_Fill::FILL_SOLID,
			    'color' => array(
			        'rgb' => '0066CC'
			    )
			),
			'borders' => array(
			    'allborders' => array(
					'style' => PHPExcel_Style_Border::BORDER_THIN
			    )
			)
		);
		$this->numero = 1;
		$fila =10;
		$datos = $this->objParam->getParametro('datos');
        $this->imprimeCabecera(0);
        $total=0;
        //var_dump($datos);
		foreach ($datos as $value){	
            $this->docexcel->getActiveSheet()->getStyle('A'.$fila.':N'.$fila.'')->applyFromArray($styleTitulos2);            
            $this->docexcel->getActiveSheet()->getStyle('A'.$fila.':N'.$fila.'')->getAlignment()->setWrapText(true);  

            switch($value['sw_nivel']) {
                case '1':
                    if($total==1){
                        $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(4, $fila, 'Total ');
                        $fila++;
                        $this->numero++;
                        $this->docexcel->getActiveSheet()->getStyle('A'.$fila.':N'.$fila.'')->applyFromArray($styleTitulos2);            
                        $this->docexcel->getActiveSheet()->getStyle('A'.$fila.':N'.$fila.'')->getAlignment()->setWrapText(true);  
                    }
                    $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(3, $fila, '*'.trim($value['pregunta']));
                    $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(7, $fila, '100%');
                    $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(8, $fila, '80%');
                    $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(9, $fila, '50%');
                    $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(10, $fila, '30%');
                    $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(11, $fila, '0%');
                    
                break;
                case '0':                 
                    $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(4, $fila, trim($value['pregunta']));                 
                    $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(5, $fila, trim($value['peso']));                 
                    if ($value['tipo']=='Selección') {                                                     
                        switch($value['respuesta']) {
                            case 'Excelente':
                                $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(7, $fila, 'X');
                            break;
                            case 'Destacable':
                                $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(8, $fila, 'X');
                            break;
                            case 'Acorde a la posición':
                                $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(9, $fila, 'X');
                            break;
                            case 'En desarrollo':
                                $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(10, $fila, 'X');
                            break;
                            case 'A desarrollo':
                                $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(11, $fila, 'X');
                            break;
                        } 
                    }elseif ($value['tipo']=='Texto') {
                        $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(12, $fila, trim($value['respuesta']));
                    }
                    $total=1;
                break;                
            }
            $fila++;
            $this->numero++;					
        }
        $this->docexcel->getActiveSheet()->getStyle('A'.$fila.':N'.$fila.'')->applyFromArray($styleTitulos2);  
        $this->docexcel->getActiveSheet()->getStyle('A'.$fila.':N'.$fila.'')->getAlignment()->setWrapText(true);  
        $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(4, $fila, 'Total ');				
		/*$this->docexcel->getActiveSheet()->getStyle('B'.($fila+1).':C'.($fila+1).'')->applyFromArray($styleTitulos);										
        $this->docexcel->getActiveSheet()->getStyle('D'.(6).':D'.($fila+1).'')->getNumberFormat()->setFormatCode('#,##0.00');
            
	
        $this->docexcel->getActiveSheet()->getStyle('D'.($fila+1).':J'.($fila+1).'')->applyFromArray($styleTitulos3);
        $this->docexcel->getActiveSheet()->mergeCells('B'.($fila+1).':C'.($fila+1).'');  
        $this->docexcel->getActiveSheet()->setCellValue('B'.($fila+1).'','TOTAL');


        $this->docexcel->getActiveSheet()->mergeCells('D'.($fila+1).':J'.($fila+1).'');  
        $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(3,$fila+1,'=SUM(J6:J'.($fila-1).')');	
        
        $this->docexcel->getActiveSheet()->getStyle('D'.($fila+4).':E'.($fila+4).'')->applyFromArray($styleTitulos);
        $this->docexcel->getActiveSheet()->getStyle('G'.($fila+4).':G'.($fila+4).'')->applyFromArray($styleTitulos);
        $this->docexcel->getActiveSheet()->mergeCells('D'.($fila+4).':E'.($fila+4).''); 	
        $this->docexcel->getActiveSheet()->setCellValue('D'.($fila+4).'','DPTO DE CONTABILIDAD');        
		$this->docexcel->getActiveSheet()->setCellValue('G'.($fila+4).'','DPTO DE FINANZAS');*/

    }
    function printerConfiguration(){
		$this->docexcel->setActiveSheetIndex(0)->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_PORTRAIT);
		$this->docexcel->setActiveSheetIndex(0)->getPageSetup()->setPaperSize(PHPExcel_Worksheet_PageSetup::PAPERSIZE_LETTER);
		$this->docexcel->setActiveSheetIndex(0)->getPageSetup()->setFitToWidth(1);
		$this->docexcel->setActiveSheetIndex(0)->getPageSetup()->setFitToHeight(0);
	}
}
?>