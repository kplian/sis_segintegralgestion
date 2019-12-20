<?php
/*
***************************************************************************
 HISTORIAL DE MODIFICACIONES:
 #ISSUE				FECHA				AUTOR				DESCRIPCION
 #1 master			25-01-2019 1        Juan       		    Reduce el tamaño de titulo en pensta del reporte Cuadro de mando	
 #
 ***************************************************************************/

class RcuadroMando
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
        //ini_set('memory_limit','512M');
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
		$tipo=$this->objParam->getParametro('var');
        $this->docexcel->getActiveSheet()->setTitle($tipo); //#1 master
        $this->docexcel->setActiveSheetIndex(0);

        $datos = $this->objParam->getParametro('datos');

        $styleTitulos1 = array(
            'font'  => array(
                'bold'  => true,
                'size'  => 12,
                'name'  => 'Arial'
            ),
            'alignment' => array(
				'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
				'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER
            ),
        );


        $styleTitulos2 = array(
            'font'  => array(
                'bold'  => true,
                'size'  => 9,
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
                    'rgb' => '0066CC'
                )
            ),
            'borders' => array(
                'allborders' => array(
                    'style' => PHPExcel_Style_Border::BORDER_THIN
                )
            ));
        $styleTitulos3 = array(
            'font'  => array(
                'bold'  => true,
                'size'  => 11,
                'name'  => 'Arial'
            ),
            'alignment' => array(
				'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT,
				'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER
            ),

        );
		//titulos    
		//$this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(0,2,'LIBRO DE ' .$tipo);
		$this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(0,2,'CUADRO DE MANDO INTEGRAL ');
		$this->docexcel->getActiveSheet()->getStyle('A2:S2')->applyFromArray($styleTitulos1);
		$this->docexcel->getActiveSheet()->mergeCells('A2:S2');

		$this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(0,3,'GESTIÓN : '.$this->objParam->getParametro('gestion').'              PERIODO : '.$this->objParam->getParametro('periodo'));
		$this->docexcel->getActiveSheet()->getStyle('A3:S3')->applyFromArray($styleTitulos1);
		$this->docexcel->getActiveSheet()->mergeCells('A3:S3');
			
			$this->docexcel->getActiveSheet()->getColumnDimension('B')->setWidth(25);
			$this->docexcel->getActiveSheet()->getColumnDimension('C')->setWidth(45);
			$this->docexcel->getActiveSheet()->getColumnDimension('D')->setWidth(65);
			$this->docexcel->getActiveSheet()->getColumnDimension('E')->setWidth(12);
			$this->docexcel->getActiveSheet()->getColumnDimension('F')->setWidth(150);
			$this->docexcel->getActiveSheet()->getColumnDimension('G')->setWidth(7);
			$this->docexcel->getActiveSheet()->getColumnDimension('H')->setWidth(20);
			$this->docexcel->getActiveSheet()->getColumnDimension('I')->setWidth(20);
			$this->docexcel->getActiveSheet()->getColumnDimension('J')->setWidth(20);
			$this->docexcel->getActiveSheet()->getColumnDimension('K')->setWidth(30);
			$this->docexcel->getActiveSheet()->getColumnDimension('L')->setWidth(30);
			$this->docexcel->getActiveSheet()->getColumnDimension('M')->setWidth(16);
			$this->docexcel->getActiveSheet()->getColumnDimension('N')->setWidth(16);
			$this->docexcel->getActiveSheet()->getColumnDimension('O')->setWidth(16);
			
			$this->docexcel->getActiveSheet()->getColumnDimension('P')->setWidth(16);
			$this->docexcel->getActiveSheet()->getColumnDimension('Q')->setWidth(16);
			$this->docexcel->getActiveSheet()->getColumnDimension('R')->setWidth(16);
			$this->docexcel->getActiveSheet()->getColumnDimension('S')->setWidth(40);
			//$this->docexcel->getActiveSheet()->getColumnDimension('T')->setWidth(40);

			$this->docexcel->getActiveSheet()->getStyle('A5:S5')->getAlignment()->setWrapText(true);
			$this->docexcel->getActiveSheet()->getStyle('A5:S5')->applyFromArray($styleTitulos2);
			//*************************************Cabecera*****************************************
			$this->docexcel->getActiveSheet()->setCellValue('A5','Nº');
			$this->docexcel->getActiveSheet()->setCellValue('B5','NIVEL 1');
			$this->docexcel->getActiveSheet()->setCellValue('C5','NIVEL 2');
			$this->docexcel->getActiveSheet()->setCellValue('D5','NIVEL 3');
			$this->docexcel->getActiveSheet()->setCellValue('E5','SIGLA');
			$this->docexcel->getActiveSheet()->setCellValue('F5','INIDICADOR');
			$this->docexcel->getActiveSheet()->setCellValue('G5','PESO');
			$this->docexcel->getActiveSheet()->setCellValue('H5','EVALUACIÓN');
			$this->docexcel->getActiveSheet()->setCellValue('I5','UNIDAD');
			$this->docexcel->getActiveSheet()->setCellValue('J5','FRECUENCIA');
			
			//$this->docexcel->getActiveSheet()->setCellValue('K5','TIPO SEMAFORO');

			$this->docexcel->getActiveSheet()->setCellValue('K5', 'ORDEN (COMPARACION)');

			$this->docexcel->getActiveSheet()->setCellValue('L5','VALOR REAL');
            $this->docexcel->getActiveSheet()->setCellValue('M5','SEMAFORO 3');
            $this->docexcel->getActiveSheet()->setCellValue('N5','SEMAFORO 2');
			$this->docexcel->getActiveSheet()->setCellValue('O5','SEMAFORO 1');
			
			
			
			$this->docexcel->getActiveSheet()->setCellValue('P5','SEMAFORO 4');
			$this->docexcel->getActiveSheet()->setCellValue('Q5','SEMAFORO 5');
			$this->docexcel->getActiveSheet()->setCellValue('R5','FUNCIONARIO INGRESO');	
			$this->docexcel->getActiveSheet()->setCellValue('S5','FUNCIONARIO EVALUACIÓN');
		

    }
    function generarDatos()
    {
        $styleTitulos3 = array(
            'alignment' => array(
                'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT,
                'vertical' => PHPExcel_Style_Alignment::VERTICAL_TOP,
            ),
        );
        $this->numero = 1;
        $fila = 6;
		$tipo=$this->objParam->getParametro('var');
        $datos = $this->objParam->getParametro('datos');
        $this->imprimeCabecera(0);

        $styleAlineado = array(
			'alignment' => array(
				'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT,
				'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER
			));
		//var_dump($datos);
							
		foreach ($datos as $value){				

				//Ordenar textos
			    $this->docexcel->getActiveSheet()->getStyle('B'.($fila).':S'.($fila).'')->applyFromArray($styleAlineado);
				//
               
                $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(0, $fila, $this->numero);
				$this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(1, $fila, $value['nivel_1']);
				$this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(2, $fila, $value['nivel_2']);
				$this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(3, $fila, $value['nivel_3']);
				$this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(4, $fila, $value['sigla']);
				$this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(5, $fila, $value['nivel_4']);
				$this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(6, $fila, $value['peso']);
                
                //if($value['nivel']=='4'){ 3 2 1 4 5 
                if($value['resultado']!=''){
			        $rggg = str_replace('#', '', $value['ruta_icono']);
			        $styleResultado = array(
			            'fill' => array(
			                'type' => PHPExcel_Style_Fill::FILL_SOLID,
			                'color' => array(
			                    'rgb' =>  $rggg //'0066CC'
			                )
			            ),

			            'borders' => array(
			                'allborders' => array(
			                    'style' => PHPExcel_Style_Border::BORDER_THIN
			                )
			            ));
                	$this->docexcel->getActiveSheet()->getStyle('H'.($fila).':H'.($fila).'')->applyFromArray($styleResultado);
                	$this->docexcel->getActiveSheet()->getStyle('L'.($fila).':L'.($fila).'')->applyFromArray($styleResultado);		
                }
                //}
                $resultado='NO REPORTA';
                if($value['resultado']){
                    $resultado=$value['resultado'];
                }
                $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(7, $fila, $resultado);

				$this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(8, $fila, $value['unidad']);
				$this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(9, $fila, $value['frecuencia']);
				//$this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(10, $fila, $value['tipo_semaforo']);
				$this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(10, $fila, $value['orden_comparacion']);
                $resultado='NO REPORTA';
                if($value['resultado']){
                    $resultado=$value['valor_real'];
                }
                $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(11, $fila, $resultado);

                $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(12, $fila, $value['semaforo_3']);
                $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(13, $fila, $value['semaforo_2']);
                $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(14, $fila, $value['semaforo_1']);
                
                
                $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(15, $fila, $value['semaforo_4']);
                $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(16, $fila, $value['semaforo_5']);
                $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(17, $fila, $value['funcionario_ingreso']);
                $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(18, $fila, $value['funcionario_evaluacion']);

			$fila++;
			$this->numero++;
		}
		

    }
    function generarReporte(){

        //$this->docexcel->setActiveSheetIndex(0);
        $this->objWriter = PHPExcel_IOFactory::createWriter($this->docexcel, 'Excel5');
        $this->objWriter->save($this->url_archivo);
        $this->imprimeCabecera(0);

    }

}
?>