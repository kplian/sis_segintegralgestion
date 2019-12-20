<?php
class RPlanGlobal
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
            76=>'BY',77=>'BZ',78=>'CA'
        );

    }
    function imprimeCabecera() {
        $this->docexcel->createSheet();		
		$tipo=$this->objParam->getParametro('var');
        $this->docexcel->getActiveSheet()->setTitle('GENERAL ');
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
		$this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(0,2, 'TRANSPORTADORA DE ELÉCTRICIDAD' );
		$this->docexcel->getActiveSheet()->getStyle('A2:CA2')->applyFromArray($styleTitulos1);
		$this->docexcel->getActiveSheet()->mergeCells('A2:CA2');

		$this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(0,3,' PLAN GLOBAL '.$this->objParam->getParametro('gestion').'  ('.$this->objParam->getParametro('nombre_plan').')');
		$this->docexcel->getActiveSheet()->getStyle('A3:CA3')->applyFromArray($styleTitulos1);
		$this->docexcel->getActiveSheet()->mergeCells('A3:CA3');
			
			$this->docexcel->getActiveSheet()->getColumnDimension('B')->setWidth(60);
			$this->docexcel->getActiveSheet()->getColumnDimension('C')->setWidth(60);
			$this->docexcel->getActiveSheet()->getColumnDimension('D')->setWidth(60);
			$this->docexcel->getActiveSheet()->getColumnDimension('E')->setWidth(60);
			$this->docexcel->getActiveSheet()->getColumnDimension('F')->setWidth(50);
			$this->docexcel->getActiveSheet()->getColumnDimension('G')->setWidth(7);

			$this->docexcel->getActiveSheet()->getColumnDimension('H')->setWidth(20);
			$this->docexcel->getActiveSheet()->getColumnDimension('I')->setWidth(20);
			$this->docexcel->getActiveSheet()->getColumnDimension('J')->setWidth(20);
			$this->docexcel->getActiveSheet()->getColumnDimension('K')->setWidth(30);
			$this->docexcel->getActiveSheet()->getColumnDimension('L')->setWidth(30);
			$this->docexcel->getActiveSheet()->getColumnDimension('M')->setWidth(16);

			$this->docexcel->getActiveSheet()->getColumnDimension('N')->setWidth(20);
			$this->docexcel->getActiveSheet()->getColumnDimension('O')->setWidth(20);
			$this->docexcel->getActiveSheet()->getColumnDimension('P')->setWidth(20);
			$this->docexcel->getActiveSheet()->getColumnDimension('Q')->setWidth(30);
			$this->docexcel->getActiveSheet()->getColumnDimension('R')->setWidth(30);
			$this->docexcel->getActiveSheet()->getColumnDimension('S')->setWidth(16);

            $this->docexcel->getActiveSheet()->getColumnDimension('T')->setWidth(20);
            $this->docexcel->getActiveSheet()->getColumnDimension('U')->setWidth(20);
            $this->docexcel->getActiveSheet()->getColumnDimension('V')->setWidth(20);
            $this->docexcel->getActiveSheet()->getColumnDimension('W')->setWidth(30);
            $this->docexcel->getActiveSheet()->getColumnDimension('X')->setWidth(30);
            $this->docexcel->getActiveSheet()->getColumnDimension('Y')->setWidth(16);

            $this->docexcel->getActiveSheet()->getColumnDimension('Z')->setWidth(20);
            $this->docexcel->getActiveSheet()->getColumnDimension('AA')->setWidth(20);
            $this->docexcel->getActiveSheet()->getColumnDimension('AB')->setWidth(20);
            $this->docexcel->getActiveSheet()->getColumnDimension('AC')->setWidth(30);
            $this->docexcel->getActiveSheet()->getColumnDimension('AD')->setWidth(30);
            $this->docexcel->getActiveSheet()->getColumnDimension('AE')->setWidth(16);

            $this->docexcel->getActiveSheet()->getColumnDimension('AF')->setWidth(20);
            $this->docexcel->getActiveSheet()->getColumnDimension('AG')->setWidth(20);
            $this->docexcel->getActiveSheet()->getColumnDimension('AH')->setWidth(20);
            $this->docexcel->getActiveSheet()->getColumnDimension('AI')->setWidth(30);
            $this->docexcel->getActiveSheet()->getColumnDimension('AJ')->setWidth(30);
            $this->docexcel->getActiveSheet()->getColumnDimension('AK')->setWidth(16);

            $this->docexcel->getActiveSheet()->getColumnDimension('AL')->setWidth(20);
            $this->docexcel->getActiveSheet()->getColumnDimension('AM')->setWidth(20);
            $this->docexcel->getActiveSheet()->getColumnDimension('AN')->setWidth(20);
            $this->docexcel->getActiveSheet()->getColumnDimension('AO')->setWidth(30);
            $this->docexcel->getActiveSheet()->getColumnDimension('AP')->setWidth(30);
            $this->docexcel->getActiveSheet()->getColumnDimension('AQ')->setWidth(16);

            $this->docexcel->getActiveSheet()->getColumnDimension('AR')->setWidth(20);
            $this->docexcel->getActiveSheet()->getColumnDimension('AS')->setWidth(20);
            $this->docexcel->getActiveSheet()->getColumnDimension('AT')->setWidth(20);
            $this->docexcel->getActiveSheet()->getColumnDimension('AU')->setWidth(30);
            $this->docexcel->getActiveSheet()->getColumnDimension('AV')->setWidth(30);
            $this->docexcel->getActiveSheet()->getColumnDimension('AW')->setWidth(16);

            $this->docexcel->getActiveSheet()->getColumnDimension('AX')->setWidth(20);
            $this->docexcel->getActiveSheet()->getColumnDimension('AY')->setWidth(20);
            $this->docexcel->getActiveSheet()->getColumnDimension('AZ')->setWidth(20);
            $this->docexcel->getActiveSheet()->getColumnDimension('BA')->setWidth(30);
            $this->docexcel->getActiveSheet()->getColumnDimension('BB')->setWidth(30);
            $this->docexcel->getActiveSheet()->getColumnDimension('BC')->setWidth(16);

            $this->docexcel->getActiveSheet()->getColumnDimension('BD')->setWidth(20);
            $this->docexcel->getActiveSheet()->getColumnDimension('BE')->setWidth(20);
            $this->docexcel->getActiveSheet()->getColumnDimension('BF')->setWidth(20);
            $this->docexcel->getActiveSheet()->getColumnDimension('BG')->setWidth(30);
            $this->docexcel->getActiveSheet()->getColumnDimension('BH')->setWidth(30);
            $this->docexcel->getActiveSheet()->getColumnDimension('BI')->setWidth(16);

            $this->docexcel->getActiveSheet()->getColumnDimension('BJ')->setWidth(20);
            $this->docexcel->getActiveSheet()->getColumnDimension('BK')->setWidth(20);
            $this->docexcel->getActiveSheet()->getColumnDimension('BL')->setWidth(20);
            $this->docexcel->getActiveSheet()->getColumnDimension('BM')->setWidth(30);
            $this->docexcel->getActiveSheet()->getColumnDimension('BN')->setWidth(30);
            $this->docexcel->getActiveSheet()->getColumnDimension('BO')->setWidth(16);

            $this->docexcel->getActiveSheet()->getColumnDimension('BP')->setWidth(20);
            $this->docexcel->getActiveSheet()->getColumnDimension('BQ')->setWidth(20);
            $this->docexcel->getActiveSheet()->getColumnDimension('BR')->setWidth(20);
            $this->docexcel->getActiveSheet()->getColumnDimension('BS')->setWidth(30);
            $this->docexcel->getActiveSheet()->getColumnDimension('BT')->setWidth(30);
            $this->docexcel->getActiveSheet()->getColumnDimension('BU')->setWidth(16);

            $this->docexcel->getActiveSheet()->getColumnDimension('BV')->setWidth(20);
            $this->docexcel->getActiveSheet()->getColumnDimension('BW')->setWidth(20);
            $this->docexcel->getActiveSheet()->getColumnDimension('BX')->setWidth(20);
            $this->docexcel->getActiveSheet()->getColumnDimension('BY')->setWidth(30);
            $this->docexcel->getActiveSheet()->getColumnDimension('BZ')->setWidth(30);
            $this->docexcel->getActiveSheet()->getColumnDimension('CA')->setWidth(16);
			//$this->docexcel->getActiveSheet()->getColumnDimension('T')->setWidth(40);

			$this->docexcel->getActiveSheet()->getStyle('A5:CA5')->getAlignment()->setWrapText(true);
			$this->docexcel->getActiveSheet()->getStyle('A5:CA5')->applyFromArray($styleTitulos2);

            $this->docexcel->getActiveSheet()->getStyle('A6:CA6')->getAlignment()->setWrapText(true);
            $this->docexcel->getActiveSheet()->getStyle('A6:CA6')->applyFromArray($styleTitulos2);
			//*************************************Cabecera*****************************************
			$this->docexcel->getActiveSheet()->setCellValue('A5','Nº');
            $this->docexcel->getActiveSheet()->mergeCells('A5:A6');
			$this->docexcel->getActiveSheet()->setCellValue('B5','NIVEL 1');
            $this->docexcel->getActiveSheet()->mergeCells('B5:B6');
			$this->docexcel->getActiveSheet()->setCellValue('C5','NIVEL 2');
            $this->docexcel->getActiveSheet()->mergeCells('C5:C6');
			$this->docexcel->getActiveSheet()->setCellValue('D5','NIVEL 3');
            $this->docexcel->getActiveSheet()->mergeCells('D5:D6');
			$this->docexcel->getActiveSheet()->setCellValue('E5','NIVEL 4');
            $this->docexcel->getActiveSheet()->mergeCells('E5:E6');
			$this->docexcel->getActiveSheet()->setCellValue('F5','RESPONSABLE');
            $this->docexcel->getActiveSheet()->mergeCells('F5:F6');
			$this->docexcel->getActiveSheet()->setCellValue('G5','PESO');
            $this->docexcel->getActiveSheet()->mergeCells('G5:G6');

            $this->docexcel->getActiveSheet()->setCellValue('H5','ENERO');
            $this->docexcel->getActiveSheet()->mergeCells('H5:M5');
            $this->docexcel->getActiveSheet()->setCellValue('H6','PREVISTO');
            $this->docexcel->getActiveSheet()->setCellValue('I6','REAL');
            $this->docexcel->getActiveSheet()->setCellValue('J6','DESVIACIÓN MES');
            $this->docexcel->getActiveSheet()->setCellValue('K6','ACUM. PREVISTO');
            $this->docexcel->getActiveSheet()->setCellValue('L6','ACUM. REAL');
            $this->docexcel->getActiveSheet()->setCellValue('M6','DESVIACIÓN ACUMULADA');
            
            $this->docexcel->getActiveSheet()->setCellValue('N5','FEBRERO');
            $this->docexcel->getActiveSheet()->mergeCells('N5:S5');
            $this->docexcel->getActiveSheet()->setCellValue('N6','PREVISTO');
            $this->docexcel->getActiveSheet()->setCellValue('O6','REAL');
            $this->docexcel->getActiveSheet()->setCellValue('P6','DESVIACIÓN MES');
            $this->docexcel->getActiveSheet()->setCellValue('Q6','ACUM. PREVISTO');
            $this->docexcel->getActiveSheet()->setCellValue('R6','ACUM. REAL');
            $this->docexcel->getActiveSheet()->setCellValue('S6','DESVIACIÓN ACUMULADA');

            $this->docexcel->getActiveSheet()->setCellValue('T5','MARZO');
            $this->docexcel->getActiveSheet()->mergeCells('T5:Y5');
            $this->docexcel->getActiveSheet()->setCellValue('T6','PREVISTO');
            $this->docexcel->getActiveSheet()->setCellValue('U6','REAL');
            $this->docexcel->getActiveSheet()->setCellValue('V6','DESVIACIÓN MES');
            $this->docexcel->getActiveSheet()->setCellValue('W6','ACUM. PREVISTO');
            $this->docexcel->getActiveSheet()->setCellValue('X6','ACUM. REAL');
            $this->docexcel->getActiveSheet()->setCellValue('Y6','DESVIACIÓN ACUMULADA');

            $this->docexcel->getActiveSheet()->setCellValue('Z5','ABRIL');
            $this->docexcel->getActiveSheet()->mergeCells('Z5:AE5');
            $this->docexcel->getActiveSheet()->setCellValue('Z6','PREVISTO');
            $this->docexcel->getActiveSheet()->setCellValue('AA6','REAL');
            $this->docexcel->getActiveSheet()->setCellValue('AB6','DESVIACIÓN MES');
            $this->docexcel->getActiveSheet()->setCellValue('AC6','ACUM. PREVISTO');
            $this->docexcel->getActiveSheet()->setCellValue('AD6','ACUM. REAL');
            $this->docexcel->getActiveSheet()->setCellValue('AE6','DESVIACIÓN ACUMULADA');

            $this->docexcel->getActiveSheet()->setCellValue('AF5','MAYO');
            $this->docexcel->getActiveSheet()->mergeCells('AF5:AK5');
            $this->docexcel->getActiveSheet()->setCellValue('AF6','PREVISTO');
            $this->docexcel->getActiveSheet()->setCellValue('AG6','REAL');
            $this->docexcel->getActiveSheet()->setCellValue('AH6','DESVIACIÓN MES');
            $this->docexcel->getActiveSheet()->setCellValue('AI6','ACUM. PREVISTO');
            $this->docexcel->getActiveSheet()->setCellValue('AJ6','ACUM. REAL');
            $this->docexcel->getActiveSheet()->setCellValue('AK6','DESVIACIÓN ACUMULADA');

            $this->docexcel->getActiveSheet()->setCellValue('AL5','JUNIO');
            $this->docexcel->getActiveSheet()->mergeCells('AL5:AQ5');
            $this->docexcel->getActiveSheet()->setCellValue('AL6','PREVISTO');
            $this->docexcel->getActiveSheet()->setCellValue('AM6','REAL');
            $this->docexcel->getActiveSheet()->setCellValue('AN6','DESVIACIÓN MES');
            $this->docexcel->getActiveSheet()->setCellValue('AO6','ACUM. PREVISTO');
            $this->docexcel->getActiveSheet()->setCellValue('AP6','ACUM. REAL');
            $this->docexcel->getActiveSheet()->setCellValue('AQ6','DESVIACIÓN ACUMULADA');

            $this->docexcel->getActiveSheet()->setCellValue('AR5','JULIO');
            $this->docexcel->getActiveSheet()->mergeCells('AR5:AW5');
            $this->docexcel->getActiveSheet()->setCellValue('AR6','PREVISTO');
            $this->docexcel->getActiveSheet()->setCellValue('AS6','REAL');
            $this->docexcel->getActiveSheet()->setCellValue('AT6','DESVIACIÓN MES');
            $this->docexcel->getActiveSheet()->setCellValue('AU6','ACUM. PREVISTO');
            $this->docexcel->getActiveSheet()->setCellValue('AV6','ACUM. REAL');
            $this->docexcel->getActiveSheet()->setCellValue('AW6','DESVIACIÓN ACUMULADA');

            $this->docexcel->getActiveSheet()->setCellValue('AX5','AGOSTO');
            $this->docexcel->getActiveSheet()->mergeCells('AX5:BC5');
            $this->docexcel->getActiveSheet()->setCellValue('AX6','PREVISTO');
            $this->docexcel->getActiveSheet()->setCellValue('AY6','REAL');
            $this->docexcel->getActiveSheet()->setCellValue('AZ6','DESVIACIÓN MES');
            $this->docexcel->getActiveSheet()->setCellValue('BA6','ACUM. PREVISTO');
            $this->docexcel->getActiveSheet()->setCellValue('BB6','ACUM. REAL');
            $this->docexcel->getActiveSheet()->setCellValue('BC6','DESVIACIÓN ACUMULADA');

            $this->docexcel->getActiveSheet()->setCellValue('BD5','SEPTIEMBRE');
            $this->docexcel->getActiveSheet()->mergeCells('BD5:BI5');
            $this->docexcel->getActiveSheet()->setCellValue('BD6','PREVISTO');
            $this->docexcel->getActiveSheet()->setCellValue('BE6','REAL');
            $this->docexcel->getActiveSheet()->setCellValue('BF6','DESVIACIÓN MES');
            $this->docexcel->getActiveSheet()->setCellValue('BG6','ACUM. PREVISTO');
            $this->docexcel->getActiveSheet()->setCellValue('BH6','ACUM. REAL');
            $this->docexcel->getActiveSheet()->setCellValue('BI6','DESVIACIÓN ACUMULADA');

            $this->docexcel->getActiveSheet()->setCellValue('BJ5','OCTUBRE');
            $this->docexcel->getActiveSheet()->mergeCells('BJ5:BO5');
            $this->docexcel->getActiveSheet()->setCellValue('BJ6','PREVISTO');
            $this->docexcel->getActiveSheet()->setCellValue('BK6','REAL');
            $this->docexcel->getActiveSheet()->setCellValue('BL6','DESVIACIÓN MES');
            $this->docexcel->getActiveSheet()->setCellValue('BM6','ACUM. PREVISTO');
            $this->docexcel->getActiveSheet()->setCellValue('BN6','ACUM. REAL');
            $this->docexcel->getActiveSheet()->setCellValue('BO6','DESVIACIÓN ACUMULADA');

            $this->docexcel->getActiveSheet()->setCellValue('BP5','NOVIEMBRE');
            $this->docexcel->getActiveSheet()->mergeCells('BP5:BU5');
            $this->docexcel->getActiveSheet()->setCellValue('BP6','PREVISTO');
            $this->docexcel->getActiveSheet()->setCellValue('BQ6','REAL');
            $this->docexcel->getActiveSheet()->setCellValue('BR6','DESVIACIÓN MES');
            $this->docexcel->getActiveSheet()->setCellValue('BS6','ACUM. PREVISTO');
            $this->docexcel->getActiveSheet()->setCellValue('BT6','ACUM. REAL');
            $this->docexcel->getActiveSheet()->setCellValue('BU6','DESVIACIÓN ACUMULADA');

            $this->docexcel->getActiveSheet()->setCellValue('BV5','DICIEMBRE');
            $this->docexcel->getActiveSheet()->mergeCells('BV5:CA5');
            $this->docexcel->getActiveSheet()->setCellValue('BV6','PREVISTO');
            $this->docexcel->getActiveSheet()->setCellValue('BW6','REAL');
            $this->docexcel->getActiveSheet()->setCellValue('BX6','DESVIACIÓN MES');
            $this->docexcel->getActiveSheet()->setCellValue('BY6','ACUM. PREVISTO');
            $this->docexcel->getActiveSheet()->setCellValue('BZ6','ACUM. REAL');
            $this->docexcel->getActiveSheet()->setCellValue('CA6','DESVIACIÓN ACUMULADA');

/*
			$this->docexcel->getActiveSheet()->setCellValue('K5', 'ORDEN (COMPARACION)');

			$this->docexcel->getActiveSheet()->setCellValue('L5','VALOR REAL');
            $this->docexcel->getActiveSheet()->setCellValue('M5','SEMAFORO 3');
            $this->docexcel->getActiveSheet()->setCellValue('N5','SEMAFORO 2');
			$this->docexcel->getActiveSheet()->setCellValue('O5','SEMAFORO 1');
			
			
			
			$this->docexcel->getActiveSheet()->setCellValue('P5','SEMAFORO 4');
			$this->docexcel->getActiveSheet()->setCellValue('Q5','SEMAFORO 5');
			$this->docexcel->getActiveSheet()->setCellValue('R5','FUNCIONARIO INGRESO');	
			$this->docexcel->getActiveSheet()->setCellValue('S5','FUNCIONARIO EVALUACIÓN');*/
		

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
        $fila = 7;
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
			    $this->docexcel->getActiveSheet()->getStyle('B'.($fila).':CA'.($fila).'')->applyFromArray($styleAlineado);
				//

               
                $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(0, $fila, $this->numero);
				$this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(1, $fila, $value['nivel_1']);
                $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(2, $fila, $value['nivel_2']);
                $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(3, $fila, $value['nivel_3']);
                $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(4, $fila, $value['nivel_4']);
				$this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(5, $fila, $value['responsable']);
				$this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(6, $fila, $value['peso']);

				$this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(7, $fila, $value['avance_previsto_ene']);
                $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(8, $fila, $value['avance_real_ene']);
                $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(9, $fila, $value['desviacion_mes_ene']);
                $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(10, $fila, $value['acum_previsto_ene']);
                $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(11, $fila, $value['acum_real_ene']);
                $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(12, $fila, $value['desviacion_acumulada_ene']);

                $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(13, $fila, $value['avance_previsto_feb']);
                $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(14, $fila, $value['avance_real_feb']);
                $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(15, $fila, $value['desviacion_mes_feb']);
                $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(16, $fila, $value['acum_previsto_feb']);
                $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(17, $fila, $value['acum_real_feb']);
                $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(18, $fila, $value['desviacion_acumulada_feb']);

                $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(19, $fila, $value['avance_previsto_mar']);
                $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(20, $fila, $value['avance_real_mar']);
                $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(21, $fila, $value['desviacion_mes_mar']);
                $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(22, $fila, $value['acum_previsto_mar']);
                $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(23, $fila, $value['acum_real_mar']);
                $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(24, $fila, $value['desviacion_acumulada_mar']);

                $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(25, $fila, $value['avance_previsto_abr']);
                $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(26, $fila, $value['avance_real_abr']);
                $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(27, $fila, $value['desviacion_mes_abr']);
                $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(28, $fila, $value['acum_previsto_abr']);
                $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(29, $fila, $value['acum_real_abr']);
                $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(30, $fila, $value['desviacion_acumulada_abr']);

                $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(31, $fila, $value['avance_previsto_may']);
                $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(32, $fila, $value['avance_real_may']);
                $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(33, $fila, $value['desviacion_mes_may']);
                $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(34, $fila, $value['acum_previsto_may']);
                $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(35, $fila, $value['acum_real_may']);
                $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(36, $fila, $value['desviacion_acumulada_may']);

                $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(37, $fila, $value['avance_previsto_jun']);
                $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(38, $fila, $value['avance_real_jun']);
                $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(39, $fila, $value['desviacion_mes_jun']);
                $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(40, $fila, $value['acum_previsto_jun']);
                $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(41, $fila, $value['acum_real_jun']);
                $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(42, $fila, $value['desviacion_acumulada_jun']);

                $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(43, $fila, $value['avance_previsto_jul']);
                $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(44, $fila, $value['avance_real_jul']);
                $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(45, $fila, $value['desviacion_mes_jul']);
                $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(46, $fila, $value['acum_previsto_jul']);
                $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(47, $fila, $value['acum_real_jul']);
                $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(48, $fila, $value['desviacion_acumulada_jul']);

                $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(49, $fila, $value['avance_previsto_ago']);
                $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(50, $fila, $value['avance_real_ago']);
                $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(51, $fila, $value['desviacion_mes_ago']);
                $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(52, $fila, $value['acum_previsto_ago']);
                $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(53, $fila, $value['acum_real_ago']);
                $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(54, $fila, $value['desviacion_acumulada_ago']);

                $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(55, $fila, $value['avance_previsto_sep']);
                $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(56, $fila, $value['avance_real_sep']);
                $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(57, $fila, $value['desviacion_mes_sep']);
                $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(58, $fila, $value['acum_previsto_sep']);
                $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(59, $fila, $value['acum_real_sep']);
                $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(60, $fila, $value['desviacion_acumulada_sep']);

                $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(61, $fila, $value['avance_previsto_oct']);
                $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(62, $fila, $value['avance_real_oct']);
                $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(63, $fila, $value['desviacion_mes_oct']);
                $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(64, $fila, $value['acum_previsto_oct']);
                $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(65, $fila, $value['acum_real_oct']);
                $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(66, $fila, $value['desviacion_acumulada_oct']);

                $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(67, $fila, $value['avance_previsto_nov']);
                $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(68, $fila, $value['avance_real_nov']);
                $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(69, $fila, $value['desviacion_mes_nov']);
                $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(70, $fila, $value['acum_previsto_nov']);
                $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(71, $fila, $value['acum_real_nov']);
                $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(72, $fila, $value['desviacion_acumulada_nov']);

                $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(73, $fila, $value['avance_previsto_dic']);
                $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(74, $fila, $value['avance_real_dic']);
                $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(75, $fila, $value['desviacion_mes_dic']);
                $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(76, $fila, $value['acum_previsto_dic']);
                $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(77, $fila, $value['acum_real_dic']);
                $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(78, $fila, $value['desviacion_acumulada_dic']);
				/*$this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(5, $fila, $value['nivel_4']);
				$this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(6, $fila, $value['peso']);
                

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

                $resultado='NO REPORTA';
                if($value['resultado']){
                    $resultado=$value['resultado'];
                }
                $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(7, $fila, $resultado);

				$this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(8, $fila, $value['unidad']);
				$this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(9, $fila, $value['frecuencia']);
	
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
                $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(18, $fila, $value['funcionario_evaluacion']);*/

			$fila++;
			$this->numero++;
		}
		

    }
    function imprimeTitulo($sheet,$i) {
        //Logo
        //$objDrawing = new PHPExcel_Worksheet_Drawing();
        //$objDrawing->setName('Logo');
        //$objDrawing->setDescription('Logo');
        //$objDrawing->setPath(dirname(__FILE__).'/../../lib'.$_SESSION['_DIR_LOGO']);
        //$objDrawing->setHeight(50);
        //$objDrawing->setWorksheet($this->docexcel->setActiveSheetIndex($i));
    }
    function generarReporte(){

        //$this->docexcel->setActiveSheetIndex(0);

        // Primer nivel
        $this->docexcel->setActiveSheetIndex(0);
        $this->imprimeTitulo($sheet,0);
        $this->imprimeCabecera();
        $this->generarDatos();
        //Segundo nivel
        $this->docexcel->setActiveSheetIndex(1);
        $this->imprimeTitulo($sheet,1);
        $this->objWriter = PHPExcel_IOFactory::createWriter($this->docexcel, 'Excel5');
        $this->objWriter->save($this->url_archivo);
        $this->imprimeCabecera_Nivel_uno();
         $this->generarDatos_Nivel_uno();
        //tercer nivel
        $this->docexcel->setActiveSheetIndex(2);
        $this->imprimeTitulo($sheet,2);
        $this->objWriter = PHPExcel_IOFactory::createWriter($this->docexcel, 'Excel5');
        $this->objWriter->save($this->url_archivo);
        $this->imprimeCabecera_Nivel_dos();
        $this->generarDatos_Nivel_dos();
        //cuarto nicel
        $this->docexcel->setActiveSheetIndex(3);
        $this->imprimeTitulo($sheet,3);
        $this->objWriter = PHPExcel_IOFactory::createWriter($this->docexcel, 'Excel5');
        $this->objWriter->save($this->url_archivo);
        $this->imprimeCabecera_Nivel_tres();
        $this->generarDatos_Nivel_tres();


        $this->objWriter = PHPExcel_IOFactory::createWriter($this->docexcel, 'Excel5');
        $this->objWriter->save($this->url_archivo);
    }
    function imprimeCabecera_Nivel_uno() {
        //$this->docexcel->createSheet();     
        $tipo=$this->objParam->getParametro('var');
        $this->docexcel->getActiveSheet()->setTitle('NIVEL (1) ');
        $this->docexcel->setActiveSheetIndex(1);

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
        $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(0,2, 'TRANSPORTADORA DE ELÉCTRICIDAD' );
        $this->docexcel->getActiveSheet()->getStyle('A2:CA2')->applyFromArray($styleTitulos1);
        $this->docexcel->getActiveSheet()->mergeCells('A2:CA2');

        $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(0,3,' PLAN GLOBAL '.$this->objParam->getParametro('gestion').'  ('.$this->objParam->getParametro('nombre_plan').')');
        $this->docexcel->getActiveSheet()->getStyle('A3:CA3')->applyFromArray($styleTitulos1);
        $this->docexcel->getActiveSheet()->mergeCells('A3:CA3');
            
            $this->docexcel->getActiveSheet()->getColumnDimension('B')->setWidth(60);
            $this->docexcel->getActiveSheet()->getColumnDimension('C')->setWidth(60);
            $this->docexcel->getActiveSheet()->getColumnDimension('D')->setWidth(60);
            $this->docexcel->getActiveSheet()->getColumnDimension('E')->setWidth(60);
            $this->docexcel->getActiveSheet()->getColumnDimension('F')->setWidth(50);
            $this->docexcel->getActiveSheet()->getColumnDimension('G')->setWidth(7);

            $this->docexcel->getActiveSheet()->getColumnDimension('H')->setWidth(20);
            $this->docexcel->getActiveSheet()->getColumnDimension('I')->setWidth(20);
            $this->docexcel->getActiveSheet()->getColumnDimension('J')->setWidth(20);
            $this->docexcel->getActiveSheet()->getColumnDimension('K')->setWidth(30);
            $this->docexcel->getActiveSheet()->getColumnDimension('L')->setWidth(30);
            $this->docexcel->getActiveSheet()->getColumnDimension('M')->setWidth(16);

            $this->docexcel->getActiveSheet()->getColumnDimension('N')->setWidth(20);
            $this->docexcel->getActiveSheet()->getColumnDimension('O')->setWidth(20);
            $this->docexcel->getActiveSheet()->getColumnDimension('P')->setWidth(20);
            $this->docexcel->getActiveSheet()->getColumnDimension('Q')->setWidth(30);
            $this->docexcel->getActiveSheet()->getColumnDimension('R')->setWidth(30);
            $this->docexcel->getActiveSheet()->getColumnDimension('S')->setWidth(16);

            $this->docexcel->getActiveSheet()->getColumnDimension('T')->setWidth(20);
            $this->docexcel->getActiveSheet()->getColumnDimension('U')->setWidth(20);
            $this->docexcel->getActiveSheet()->getColumnDimension('V')->setWidth(20);
            $this->docexcel->getActiveSheet()->getColumnDimension('W')->setWidth(30);
            $this->docexcel->getActiveSheet()->getColumnDimension('X')->setWidth(30);
            $this->docexcel->getActiveSheet()->getColumnDimension('Y')->setWidth(16);

            $this->docexcel->getActiveSheet()->getColumnDimension('Z')->setWidth(20);
            $this->docexcel->getActiveSheet()->getColumnDimension('AA')->setWidth(20);
            $this->docexcel->getActiveSheet()->getColumnDimension('AB')->setWidth(20);
            $this->docexcel->getActiveSheet()->getColumnDimension('AC')->setWidth(30);
            $this->docexcel->getActiveSheet()->getColumnDimension('AD')->setWidth(30);
            $this->docexcel->getActiveSheet()->getColumnDimension('AE')->setWidth(16);

            $this->docexcel->getActiveSheet()->getColumnDimension('AF')->setWidth(20);
            $this->docexcel->getActiveSheet()->getColumnDimension('AG')->setWidth(20);
            $this->docexcel->getActiveSheet()->getColumnDimension('AH')->setWidth(20);
            $this->docexcel->getActiveSheet()->getColumnDimension('AI')->setWidth(30);
            $this->docexcel->getActiveSheet()->getColumnDimension('AJ')->setWidth(30);
            $this->docexcel->getActiveSheet()->getColumnDimension('AK')->setWidth(16);

            $this->docexcel->getActiveSheet()->getColumnDimension('AL')->setWidth(20);
            $this->docexcel->getActiveSheet()->getColumnDimension('AM')->setWidth(20);
            $this->docexcel->getActiveSheet()->getColumnDimension('AN')->setWidth(20);
            $this->docexcel->getActiveSheet()->getColumnDimension('AO')->setWidth(30);
            $this->docexcel->getActiveSheet()->getColumnDimension('AP')->setWidth(30);
            $this->docexcel->getActiveSheet()->getColumnDimension('AQ')->setWidth(16);

            $this->docexcel->getActiveSheet()->getColumnDimension('AR')->setWidth(20);
            $this->docexcel->getActiveSheet()->getColumnDimension('AS')->setWidth(20);
            $this->docexcel->getActiveSheet()->getColumnDimension('AT')->setWidth(20);
            $this->docexcel->getActiveSheet()->getColumnDimension('AU')->setWidth(30);
            $this->docexcel->getActiveSheet()->getColumnDimension('AV')->setWidth(30);
            $this->docexcel->getActiveSheet()->getColumnDimension('AW')->setWidth(16);

            $this->docexcel->getActiveSheet()->getColumnDimension('AX')->setWidth(20);
            $this->docexcel->getActiveSheet()->getColumnDimension('AY')->setWidth(20);
            $this->docexcel->getActiveSheet()->getColumnDimension('AZ')->setWidth(20);
            $this->docexcel->getActiveSheet()->getColumnDimension('BA')->setWidth(30);
            $this->docexcel->getActiveSheet()->getColumnDimension('BB')->setWidth(30);
            $this->docexcel->getActiveSheet()->getColumnDimension('BC')->setWidth(16);

            $this->docexcel->getActiveSheet()->getColumnDimension('BD')->setWidth(20);
            $this->docexcel->getActiveSheet()->getColumnDimension('BE')->setWidth(20);
            $this->docexcel->getActiveSheet()->getColumnDimension('BF')->setWidth(20);
            $this->docexcel->getActiveSheet()->getColumnDimension('BG')->setWidth(30);
            $this->docexcel->getActiveSheet()->getColumnDimension('BH')->setWidth(30);
            $this->docexcel->getActiveSheet()->getColumnDimension('BI')->setWidth(16);

            $this->docexcel->getActiveSheet()->getColumnDimension('BJ')->setWidth(20);
            $this->docexcel->getActiveSheet()->getColumnDimension('BK')->setWidth(20);
            $this->docexcel->getActiveSheet()->getColumnDimension('BL')->setWidth(20);
            $this->docexcel->getActiveSheet()->getColumnDimension('BM')->setWidth(30);
            $this->docexcel->getActiveSheet()->getColumnDimension('BN')->setWidth(30);
            $this->docexcel->getActiveSheet()->getColumnDimension('BO')->setWidth(16);

            $this->docexcel->getActiveSheet()->getColumnDimension('BP')->setWidth(20);
            $this->docexcel->getActiveSheet()->getColumnDimension('BQ')->setWidth(20);
            $this->docexcel->getActiveSheet()->getColumnDimension('BR')->setWidth(20);
            $this->docexcel->getActiveSheet()->getColumnDimension('BS')->setWidth(30);
            $this->docexcel->getActiveSheet()->getColumnDimension('BT')->setWidth(30);
            $this->docexcel->getActiveSheet()->getColumnDimension('BU')->setWidth(16);

            $this->docexcel->getActiveSheet()->getColumnDimension('BV')->setWidth(20);
            $this->docexcel->getActiveSheet()->getColumnDimension('BW')->setWidth(20);
            $this->docexcel->getActiveSheet()->getColumnDimension('BX')->setWidth(20);
            $this->docexcel->getActiveSheet()->getColumnDimension('BY')->setWidth(30);
            $this->docexcel->getActiveSheet()->getColumnDimension('BZ')->setWidth(30);
            $this->docexcel->getActiveSheet()->getColumnDimension('CA')->setWidth(16);
            //$this->docexcel->getActiveSheet()->getColumnDimension('T')->setWidth(40);

            $this->docexcel->getActiveSheet()->getStyle('A5:CA5')->getAlignment()->setWrapText(true);
            $this->docexcel->getActiveSheet()->getStyle('A5:CA5')->applyFromArray($styleTitulos2);

            $this->docexcel->getActiveSheet()->getStyle('A6:CA6')->getAlignment()->setWrapText(true);
            $this->docexcel->getActiveSheet()->getStyle('A6:CA6')->applyFromArray($styleTitulos2);
            //*************************************Cabecera*****************************************
            $this->docexcel->getActiveSheet()->setCellValue('A5','Nº');
            $this->docexcel->getActiveSheet()->mergeCells('A5:A6');
            $this->docexcel->getActiveSheet()->setCellValue('B5','NIVEL 1');
            $this->docexcel->getActiveSheet()->mergeCells('B5:B6');
            $this->docexcel->getActiveSheet()->setCellValue('C5','NIVEL 2');
            $this->docexcel->getActiveSheet()->mergeCells('C5:C6');
            $this->docexcel->getActiveSheet()->setCellValue('D5','NIVEL 3');
            $this->docexcel->getActiveSheet()->mergeCells('D5:D6');
            $this->docexcel->getActiveSheet()->setCellValue('E5','NIVEL 4');
            $this->docexcel->getActiveSheet()->mergeCells('E5:E6');
            $this->docexcel->getActiveSheet()->setCellValue('F5','RESPONSABLE');
            $this->docexcel->getActiveSheet()->mergeCells('F5:F6');
            $this->docexcel->getActiveSheet()->setCellValue('G5','PESO');
            $this->docexcel->getActiveSheet()->mergeCells('G5:G6');

            $this->docexcel->getActiveSheet()->setCellValue('H5','ENERO');
            $this->docexcel->getActiveSheet()->mergeCells('H5:M5');
            $this->docexcel->getActiveSheet()->setCellValue('H6','PREVISTO');
            $this->docexcel->getActiveSheet()->setCellValue('I6','REAL');
            $this->docexcel->getActiveSheet()->setCellValue('J6','DESVIACIÓN MES');
            $this->docexcel->getActiveSheet()->setCellValue('K6','ACUM. PREVISTO');
            $this->docexcel->getActiveSheet()->setCellValue('L6','ACUM. REAL');
            $this->docexcel->getActiveSheet()->setCellValue('M6','DESVIACIÓN ACUMULADA');
            
            $this->docexcel->getActiveSheet()->setCellValue('N5','FEBRERO');
            $this->docexcel->getActiveSheet()->mergeCells('N5:S5');
            $this->docexcel->getActiveSheet()->setCellValue('N6','PREVISTO');
            $this->docexcel->getActiveSheet()->setCellValue('O6','REAL');
            $this->docexcel->getActiveSheet()->setCellValue('P6','DESVIACIÓN MES');
            $this->docexcel->getActiveSheet()->setCellValue('Q6','ACUM. PREVISTO');
            $this->docexcel->getActiveSheet()->setCellValue('R6','ACUM. REAL');
            $this->docexcel->getActiveSheet()->setCellValue('S6','DESVIACIÓN ACUMULADA');

            $this->docexcel->getActiveSheet()->setCellValue('T5','MARZO');
            $this->docexcel->getActiveSheet()->mergeCells('T5:Y5');
            $this->docexcel->getActiveSheet()->setCellValue('T6','PREVISTO');
            $this->docexcel->getActiveSheet()->setCellValue('U6','REAL');
            $this->docexcel->getActiveSheet()->setCellValue('V6','DESVIACIÓN MES');
            $this->docexcel->getActiveSheet()->setCellValue('W6','ACUM. PREVISTO');
            $this->docexcel->getActiveSheet()->setCellValue('X6','ACUM. REAL');
            $this->docexcel->getActiveSheet()->setCellValue('Y6','DESVIACIÓN ACUMULADA');

            $this->docexcel->getActiveSheet()->setCellValue('Z5','ABRIL');
            $this->docexcel->getActiveSheet()->mergeCells('Z5:AE5');
            $this->docexcel->getActiveSheet()->setCellValue('Z6','PREVISTO');
            $this->docexcel->getActiveSheet()->setCellValue('AA6','REAL');
            $this->docexcel->getActiveSheet()->setCellValue('AB6','DESVIACIÓN MES');
            $this->docexcel->getActiveSheet()->setCellValue('AC6','ACUM. PREVISTO');
            $this->docexcel->getActiveSheet()->setCellValue('AD6','ACUM. REAL');
            $this->docexcel->getActiveSheet()->setCellValue('AE6','DESVIACIÓN ACUMULADA');

            $this->docexcel->getActiveSheet()->setCellValue('AF5','MAYO');
            $this->docexcel->getActiveSheet()->mergeCells('AF5:AK5');
            $this->docexcel->getActiveSheet()->setCellValue('AF6','PREVISTO');
            $this->docexcel->getActiveSheet()->setCellValue('AG6','REAL');
            $this->docexcel->getActiveSheet()->setCellValue('AH6','DESVIACIÓN MES');
            $this->docexcel->getActiveSheet()->setCellValue('AI6','ACUM. PREVISTO');
            $this->docexcel->getActiveSheet()->setCellValue('AJ6','ACUM. REAL');
            $this->docexcel->getActiveSheet()->setCellValue('AK6','DESVIACIÓN ACUMULADA');

            $this->docexcel->getActiveSheet()->setCellValue('AL5','JUNIO');
            $this->docexcel->getActiveSheet()->mergeCells('AL5:AQ5');
            $this->docexcel->getActiveSheet()->setCellValue('AL6','PREVISTO');
            $this->docexcel->getActiveSheet()->setCellValue('AM6','REAL');
            $this->docexcel->getActiveSheet()->setCellValue('AN6','DESVIACIÓN MES');
            $this->docexcel->getActiveSheet()->setCellValue('AO6','ACUM. PREVISTO');
            $this->docexcel->getActiveSheet()->setCellValue('AP6','ACUM. REAL');
            $this->docexcel->getActiveSheet()->setCellValue('AQ6','DESVIACIÓN ACUMULADA');

            $this->docexcel->getActiveSheet()->setCellValue('AR5','JULIO');
            $this->docexcel->getActiveSheet()->mergeCells('AR5:AW5');
            $this->docexcel->getActiveSheet()->setCellValue('AR6','PREVISTO');
            $this->docexcel->getActiveSheet()->setCellValue('AS6','REAL');
            $this->docexcel->getActiveSheet()->setCellValue('AT6','DESVIACIÓN MES');
            $this->docexcel->getActiveSheet()->setCellValue('AU6','ACUM. PREVISTO');
            $this->docexcel->getActiveSheet()->setCellValue('AV6','ACUM. REAL');
            $this->docexcel->getActiveSheet()->setCellValue('AW6','DESVIACIÓN ACUMULADA');

            $this->docexcel->getActiveSheet()->setCellValue('AX5','AGOSTO');
            $this->docexcel->getActiveSheet()->mergeCells('AX5:BC5');
            $this->docexcel->getActiveSheet()->setCellValue('AX6','PREVISTO');
            $this->docexcel->getActiveSheet()->setCellValue('AY6','REAL');
            $this->docexcel->getActiveSheet()->setCellValue('AZ6','DESVIACIÓN MES');
            $this->docexcel->getActiveSheet()->setCellValue('BA6','ACUM. PREVISTO');
            $this->docexcel->getActiveSheet()->setCellValue('BB6','ACUM. REAL');
            $this->docexcel->getActiveSheet()->setCellValue('BC6','DESVIACIÓN ACUMULADA');

            $this->docexcel->getActiveSheet()->setCellValue('BD5','SEPTIEMBRE');
            $this->docexcel->getActiveSheet()->mergeCells('BD5:BI5');
            $this->docexcel->getActiveSheet()->setCellValue('BD6','PREVISTO');
            $this->docexcel->getActiveSheet()->setCellValue('BE6','REAL');
            $this->docexcel->getActiveSheet()->setCellValue('BF6','DESVIACIÓN MES');
            $this->docexcel->getActiveSheet()->setCellValue('BG6','ACUM. PREVISTO');
            $this->docexcel->getActiveSheet()->setCellValue('BH6','ACUM. REAL');
            $this->docexcel->getActiveSheet()->setCellValue('BI6','DESVIACIÓN ACUMULADA');

            $this->docexcel->getActiveSheet()->setCellValue('BJ5','OCTUBRE');
            $this->docexcel->getActiveSheet()->mergeCells('BJ5:BO5');
            $this->docexcel->getActiveSheet()->setCellValue('BJ6','PREVISTO');
            $this->docexcel->getActiveSheet()->setCellValue('BK6','REAL');
            $this->docexcel->getActiveSheet()->setCellValue('BL6','DESVIACIÓN MES');
            $this->docexcel->getActiveSheet()->setCellValue('BM6','ACUM. PREVISTO');
            $this->docexcel->getActiveSheet()->setCellValue('BN6','ACUM. REAL');
            $this->docexcel->getActiveSheet()->setCellValue('BO6','DESVIACIÓN ACUMULADA');

            $this->docexcel->getActiveSheet()->setCellValue('BP5','NOVIEMBRE');
            $this->docexcel->getActiveSheet()->mergeCells('BP5:BU5');
            $this->docexcel->getActiveSheet()->setCellValue('BP6','PREVISTO');
            $this->docexcel->getActiveSheet()->setCellValue('BQ6','REAL');
            $this->docexcel->getActiveSheet()->setCellValue('BR6','DESVIACIÓN MES');
            $this->docexcel->getActiveSheet()->setCellValue('BS6','ACUM. PREVISTO');
            $this->docexcel->getActiveSheet()->setCellValue('BT6','ACUM. REAL');
            $this->docexcel->getActiveSheet()->setCellValue('BU6','DESVIACIÓN ACUMULADA');

            $this->docexcel->getActiveSheet()->setCellValue('BV5','DICIEMBRE');
            $this->docexcel->getActiveSheet()->mergeCells('BV5:CA5');
            $this->docexcel->getActiveSheet()->setCellValue('BV6','PREVISTO');
            $this->docexcel->getActiveSheet()->setCellValue('BW6','REAL');
            $this->docexcel->getActiveSheet()->setCellValue('BX6','DESVIACIÓN MES');
            $this->docexcel->getActiveSheet()->setCellValue('BY6','ACUM. PREVISTO');
            $this->docexcel->getActiveSheet()->setCellValue('BZ6','ACUM. REAL');
            $this->docexcel->getActiveSheet()->setCellValue('CA6','DESVIACIÓN ACUMULADA');

    }
    function generarDatos_Nivel_uno()
    {
        $styleTitulos3 = array(
            'alignment' => array(
                'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT,
                'vertical' => PHPExcel_Style_Alignment::VERTICAL_TOP,
            ),
        );
        $this->numero = 1;
        $fila = 7;
        $tipo=$this->objParam->getParametro('var');
        $datos = $this->objParam->getParametro('datos');
        $this->imprimeCabecera_Nivel_uno(1);

        $styleAlineado = array(
            'alignment' => array(
                'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT,
                'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER
            ));
        //var_dump($datos);
                            
        foreach ($datos as $value){             

                //Ordenar textos
                $this->docexcel->getActiveSheet()->getStyle('B'.($fila).':CA'.($fila).'')->applyFromArray($styleAlineado);
                //
                if($value['nivel_1']){
                    $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(0, $fila, $this->numero);
                    $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(1, $fila, $value['nivel_1']);
                    $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(2, $fila, $value['nivel_2']);
                    $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(3, $fila, $value['nivel_3']);
                    $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(4, $fila, $value['nivel_4']);
                    $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(5, $fila, $value['responsable']);
                    $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(6, $fila, $value['peso']);

                    $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(7, $fila, $value['avance_previsto_ene']);
                    $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(8, $fila, $value['avance_real_ene']);
                    $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(9, $fila, $value['desviacion_mes_ene']);
                    $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(10, $fila, $value['acum_previsto_ene']);
                    $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(11, $fila, $value['acum_real_ene']);
                    $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(12, $fila, $value['desviacion_acumulada_ene']);

                    $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(13, $fila, $value['avance_previsto_feb']);
                    $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(14, $fila, $value['avance_real_feb']);
                    $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(15, $fila, $value['desviacion_mes_feb']);
                    $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(16, $fila, $value['acum_previsto_feb']);
                    $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(17, $fila, $value['acum_real_feb']);
                    $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(18, $fila, $value['desviacion_acumulada_feb']);

                    $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(19, $fila, $value['avance_previsto_mar']);
                    $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(20, $fila, $value['avance_real_mar']);
                    $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(21, $fila, $value['desviacion_mes_mar']);
                    $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(22, $fila, $value['acum_previsto_mar']);
                    $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(23, $fila, $value['acum_real_mar']);
                    $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(24, $fila, $value['desviacion_acumulada_mar']);

                    $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(25, $fila, $value['avance_previsto_abr']);
                    $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(26, $fila, $value['avance_real_abr']);
                    $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(27, $fila, $value['desviacion_mes_abr']);
                    $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(28, $fila, $value['acum_previsto_abr']);
                    $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(29, $fila, $value['acum_real_abr']);
                    $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(30, $fila, $value['desviacion_acumulada_abr']);

                    $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(31, $fila, $value['avance_previsto_may']);
                    $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(32, $fila, $value['avance_real_may']);
                    $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(33, $fila, $value['desviacion_mes_may']);
                    $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(34, $fila, $value['acum_previsto_may']);
                    $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(35, $fila, $value['acum_real_may']);
                    $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(36, $fila, $value['desviacion_acumulada_may']);

                    $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(37, $fila, $value['avance_previsto_jun']);
                    $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(38, $fila, $value['avance_real_jun']);
                    $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(39, $fila, $value['desviacion_mes_jun']);
                    $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(40, $fila, $value['acum_previsto_jun']);
                    $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(41, $fila, $value['acum_real_jun']);
                    $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(42, $fila, $value['desviacion_acumulada_jun']);

                    $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(43, $fila, $value['avance_previsto_jul']);
                    $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(44, $fila, $value['avance_real_jul']);
                    $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(45, $fila, $value['desviacion_mes_jul']);
                    $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(46, $fila, $value['acum_previsto_jul']);
                    $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(47, $fila, $value['acum_real_jul']);
                    $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(48, $fila, $value['desviacion_acumulada_jul']);

                    $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(49, $fila, $value['avance_previsto_ago']);
                    $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(50, $fila, $value['avance_real_ago']);
                    $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(51, $fila, $value['desviacion_mes_ago']);
                    $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(52, $fila, $value['acum_previsto_ago']);
                    $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(53, $fila, $value['acum_real_ago']);
                    $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(54, $fila, $value['desviacion_acumulada_ago']);

                    $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(55, $fila, $value['avance_previsto_sep']);
                    $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(56, $fila, $value['avance_real_sep']);
                    $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(57, $fila, $value['desviacion_mes_sep']);
                    $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(58, $fila, $value['acum_previsto_sep']);
                    $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(59, $fila, $value['acum_real_sep']);
                    $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(60, $fila, $value['desviacion_acumulada_sep']);

                    $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(61, $fila, $value['avance_previsto_oct']);
                    $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(62, $fila, $value['avance_real_oct']);
                    $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(63, $fila, $value['desviacion_mes_oct']);
                    $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(64, $fila, $value['acum_previsto_oct']);
                    $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(65, $fila, $value['acum_real_oct']);
                    $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(66, $fila, $value['desviacion_acumulada_oct']);

                    $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(67, $fila, $value['avance_previsto_nov']);
                    $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(68, $fila, $value['avance_real_nov']);
                    $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(69, $fila, $value['desviacion_mes_nov']);
                    $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(70, $fila, $value['acum_previsto_nov']);
                    $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(71, $fila, $value['acum_real_nov']);
                    $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(72, $fila, $value['desviacion_acumulada_nov']);

                    $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(73, $fila, $value['avance_previsto_dic']);
                    $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(74, $fila, $value['avance_real_dic']);
                    $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(75, $fila, $value['desviacion_mes_dic']);
                    $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(76, $fila, $value['acum_previsto_dic']);
                    $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(77, $fila, $value['acum_real_dic']);
                    $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(78, $fila, $value['desviacion_acumulada_dic']);
                    $fila++;
                    $this->numero++;
                }
        }
    }
    function imprimeCabecera_Nivel_dos() {
        //$this->docexcel->createSheet();     
        $tipo=$this->objParam->getParametro('var');
        $this->docexcel->getActiveSheet()->setTitle('NIVEL (1,2) ');
        $this->docexcel->setActiveSheetIndex(2);

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
        $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(0,2, 'TRANSPORTADORA DE ELÉCTRICIDAD' );
        $this->docexcel->getActiveSheet()->getStyle('A2:CA2')->applyFromArray($styleTitulos1);
        $this->docexcel->getActiveSheet()->mergeCells('A2:CA2');

        $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(0,3,' PLAN GLOBAL '.$this->objParam->getParametro('gestion').'  ('.$this->objParam->getParametro('nombre_plan').')');
        $this->docexcel->getActiveSheet()->getStyle('A3:CA3')->applyFromArray($styleTitulos1);
        $this->docexcel->getActiveSheet()->mergeCells('A3:CA3');
            
            $this->docexcel->getActiveSheet()->getColumnDimension('B')->setWidth(60);
            $this->docexcel->getActiveSheet()->getColumnDimension('C')->setWidth(60);
            $this->docexcel->getActiveSheet()->getColumnDimension('D')->setWidth(60);
            $this->docexcel->getActiveSheet()->getColumnDimension('E')->setWidth(60);
            $this->docexcel->getActiveSheet()->getColumnDimension('F')->setWidth(50);
            $this->docexcel->getActiveSheet()->getColumnDimension('G')->setWidth(7);

            $this->docexcel->getActiveSheet()->getColumnDimension('H')->setWidth(20);
            $this->docexcel->getActiveSheet()->getColumnDimension('I')->setWidth(20);
            $this->docexcel->getActiveSheet()->getColumnDimension('J')->setWidth(20);
            $this->docexcel->getActiveSheet()->getColumnDimension('K')->setWidth(30);
            $this->docexcel->getActiveSheet()->getColumnDimension('L')->setWidth(30);
            $this->docexcel->getActiveSheet()->getColumnDimension('M')->setWidth(16);

            $this->docexcel->getActiveSheet()->getColumnDimension('N')->setWidth(20);
            $this->docexcel->getActiveSheet()->getColumnDimension('O')->setWidth(20);
            $this->docexcel->getActiveSheet()->getColumnDimension('P')->setWidth(20);
            $this->docexcel->getActiveSheet()->getColumnDimension('Q')->setWidth(30);
            $this->docexcel->getActiveSheet()->getColumnDimension('R')->setWidth(30);
            $this->docexcel->getActiveSheet()->getColumnDimension('S')->setWidth(16);

            $this->docexcel->getActiveSheet()->getColumnDimension('T')->setWidth(20);
            $this->docexcel->getActiveSheet()->getColumnDimension('U')->setWidth(20);
            $this->docexcel->getActiveSheet()->getColumnDimension('V')->setWidth(20);
            $this->docexcel->getActiveSheet()->getColumnDimension('W')->setWidth(30);
            $this->docexcel->getActiveSheet()->getColumnDimension('X')->setWidth(30);
            $this->docexcel->getActiveSheet()->getColumnDimension('Y')->setWidth(16);

            $this->docexcel->getActiveSheet()->getColumnDimension('Z')->setWidth(20);
            $this->docexcel->getActiveSheet()->getColumnDimension('AA')->setWidth(20);
            $this->docexcel->getActiveSheet()->getColumnDimension('AB')->setWidth(20);
            $this->docexcel->getActiveSheet()->getColumnDimension('AC')->setWidth(30);
            $this->docexcel->getActiveSheet()->getColumnDimension('AD')->setWidth(30);
            $this->docexcel->getActiveSheet()->getColumnDimension('AE')->setWidth(16);

            $this->docexcel->getActiveSheet()->getColumnDimension('AF')->setWidth(20);
            $this->docexcel->getActiveSheet()->getColumnDimension('AG')->setWidth(20);
            $this->docexcel->getActiveSheet()->getColumnDimension('AH')->setWidth(20);
            $this->docexcel->getActiveSheet()->getColumnDimension('AI')->setWidth(30);
            $this->docexcel->getActiveSheet()->getColumnDimension('AJ')->setWidth(30);
            $this->docexcel->getActiveSheet()->getColumnDimension('AK')->setWidth(16);

            $this->docexcel->getActiveSheet()->getColumnDimension('AL')->setWidth(20);
            $this->docexcel->getActiveSheet()->getColumnDimension('AM')->setWidth(20);
            $this->docexcel->getActiveSheet()->getColumnDimension('AN')->setWidth(20);
            $this->docexcel->getActiveSheet()->getColumnDimension('AO')->setWidth(30);
            $this->docexcel->getActiveSheet()->getColumnDimension('AP')->setWidth(30);
            $this->docexcel->getActiveSheet()->getColumnDimension('AQ')->setWidth(16);

            $this->docexcel->getActiveSheet()->getColumnDimension('AR')->setWidth(20);
            $this->docexcel->getActiveSheet()->getColumnDimension('AS')->setWidth(20);
            $this->docexcel->getActiveSheet()->getColumnDimension('AT')->setWidth(20);
            $this->docexcel->getActiveSheet()->getColumnDimension('AU')->setWidth(30);
            $this->docexcel->getActiveSheet()->getColumnDimension('AV')->setWidth(30);
            $this->docexcel->getActiveSheet()->getColumnDimension('AW')->setWidth(16);

            $this->docexcel->getActiveSheet()->getColumnDimension('AX')->setWidth(20);
            $this->docexcel->getActiveSheet()->getColumnDimension('AY')->setWidth(20);
            $this->docexcel->getActiveSheet()->getColumnDimension('AZ')->setWidth(20);
            $this->docexcel->getActiveSheet()->getColumnDimension('BA')->setWidth(30);
            $this->docexcel->getActiveSheet()->getColumnDimension('BB')->setWidth(30);
            $this->docexcel->getActiveSheet()->getColumnDimension('BC')->setWidth(16);

            $this->docexcel->getActiveSheet()->getColumnDimension('BD')->setWidth(20);
            $this->docexcel->getActiveSheet()->getColumnDimension('BE')->setWidth(20);
            $this->docexcel->getActiveSheet()->getColumnDimension('BF')->setWidth(20);
            $this->docexcel->getActiveSheet()->getColumnDimension('BG')->setWidth(30);
            $this->docexcel->getActiveSheet()->getColumnDimension('BH')->setWidth(30);
            $this->docexcel->getActiveSheet()->getColumnDimension('BI')->setWidth(16);

            $this->docexcel->getActiveSheet()->getColumnDimension('BJ')->setWidth(20);
            $this->docexcel->getActiveSheet()->getColumnDimension('BK')->setWidth(20);
            $this->docexcel->getActiveSheet()->getColumnDimension('BL')->setWidth(20);
            $this->docexcel->getActiveSheet()->getColumnDimension('BM')->setWidth(30);
            $this->docexcel->getActiveSheet()->getColumnDimension('BN')->setWidth(30);
            $this->docexcel->getActiveSheet()->getColumnDimension('BO')->setWidth(16);

            $this->docexcel->getActiveSheet()->getColumnDimension('BP')->setWidth(20);
            $this->docexcel->getActiveSheet()->getColumnDimension('BQ')->setWidth(20);
            $this->docexcel->getActiveSheet()->getColumnDimension('BR')->setWidth(20);
            $this->docexcel->getActiveSheet()->getColumnDimension('BS')->setWidth(30);
            $this->docexcel->getActiveSheet()->getColumnDimension('BT')->setWidth(30);
            $this->docexcel->getActiveSheet()->getColumnDimension('BU')->setWidth(16);

            $this->docexcel->getActiveSheet()->getColumnDimension('BV')->setWidth(20);
            $this->docexcel->getActiveSheet()->getColumnDimension('BW')->setWidth(20);
            $this->docexcel->getActiveSheet()->getColumnDimension('BX')->setWidth(20);
            $this->docexcel->getActiveSheet()->getColumnDimension('BY')->setWidth(30);
            $this->docexcel->getActiveSheet()->getColumnDimension('BZ')->setWidth(30);
            $this->docexcel->getActiveSheet()->getColumnDimension('CA')->setWidth(16);
            //$this->docexcel->getActiveSheet()->getColumnDimension('T')->setWidth(40);

            $this->docexcel->getActiveSheet()->getStyle('A5:CA5')->getAlignment()->setWrapText(true);
            $this->docexcel->getActiveSheet()->getStyle('A5:CA5')->applyFromArray($styleTitulos2);

            $this->docexcel->getActiveSheet()->getStyle('A6:CA6')->getAlignment()->setWrapText(true);
            $this->docexcel->getActiveSheet()->getStyle('A6:CA6')->applyFromArray($styleTitulos2);
            //*************************************Cabecera*****************************************
            $this->docexcel->getActiveSheet()->setCellValue('A5','Nº');
            $this->docexcel->getActiveSheet()->mergeCells('A5:A6');
            $this->docexcel->getActiveSheet()->setCellValue('B5','NIVEL 1');
            $this->docexcel->getActiveSheet()->mergeCells('B5:B6');
            $this->docexcel->getActiveSheet()->setCellValue('C5','NIVEL 2');
            $this->docexcel->getActiveSheet()->mergeCells('C5:C6');
            $this->docexcel->getActiveSheet()->setCellValue('D5','NIVEL 3');
            $this->docexcel->getActiveSheet()->mergeCells('D5:D6');
            $this->docexcel->getActiveSheet()->setCellValue('E5','NIVEL 4');
            $this->docexcel->getActiveSheet()->mergeCells('E5:E6');
            $this->docexcel->getActiveSheet()->setCellValue('F5','RESPONSABLE');
            $this->docexcel->getActiveSheet()->mergeCells('F5:F6');
            $this->docexcel->getActiveSheet()->setCellValue('G5','PESO');
            $this->docexcel->getActiveSheet()->mergeCells('G5:G6');

            $this->docexcel->getActiveSheet()->setCellValue('H5','ENERO');
            $this->docexcel->getActiveSheet()->mergeCells('H5:M5');
            $this->docexcel->getActiveSheet()->setCellValue('H6','PREVISTO');
            $this->docexcel->getActiveSheet()->setCellValue('I6','REAL');
            $this->docexcel->getActiveSheet()->setCellValue('J6','DESVIACIÓN MES');
            $this->docexcel->getActiveSheet()->setCellValue('K6','ACUM. PREVISTO');
            $this->docexcel->getActiveSheet()->setCellValue('L6','ACUM. REAL');
            $this->docexcel->getActiveSheet()->setCellValue('M6','DESVIACIÓN ACUMULADA');
            
            $this->docexcel->getActiveSheet()->setCellValue('N5','FEBRERO');
            $this->docexcel->getActiveSheet()->mergeCells('N5:S5');
            $this->docexcel->getActiveSheet()->setCellValue('N6','PREVISTO');
            $this->docexcel->getActiveSheet()->setCellValue('O6','REAL');
            $this->docexcel->getActiveSheet()->setCellValue('P6','DESVIACIÓN MES');
            $this->docexcel->getActiveSheet()->setCellValue('Q6','ACUM. PREVISTO');
            $this->docexcel->getActiveSheet()->setCellValue('R6','ACUM. REAL');
            $this->docexcel->getActiveSheet()->setCellValue('S6','DESVIACIÓN ACUMULADA');

            $this->docexcel->getActiveSheet()->setCellValue('T5','MARZO');
            $this->docexcel->getActiveSheet()->mergeCells('T5:Y5');
            $this->docexcel->getActiveSheet()->setCellValue('T6','PREVISTO');
            $this->docexcel->getActiveSheet()->setCellValue('U6','REAL');
            $this->docexcel->getActiveSheet()->setCellValue('V6','DESVIACIÓN MES');
            $this->docexcel->getActiveSheet()->setCellValue('W6','ACUM. PREVISTO');
            $this->docexcel->getActiveSheet()->setCellValue('X6','ACUM. REAL');
            $this->docexcel->getActiveSheet()->setCellValue('Y6','DESVIACIÓN ACUMULADA');

            $this->docexcel->getActiveSheet()->setCellValue('Z5','ABRIL');
            $this->docexcel->getActiveSheet()->mergeCells('Z5:AE5');
            $this->docexcel->getActiveSheet()->setCellValue('Z6','PREVISTO');
            $this->docexcel->getActiveSheet()->setCellValue('AA6','REAL');
            $this->docexcel->getActiveSheet()->setCellValue('AB6','DESVIACIÓN MES');
            $this->docexcel->getActiveSheet()->setCellValue('AC6','ACUM. PREVISTO');
            $this->docexcel->getActiveSheet()->setCellValue('AD6','ACUM. REAL');
            $this->docexcel->getActiveSheet()->setCellValue('AE6','DESVIACIÓN ACUMULADA');

            $this->docexcel->getActiveSheet()->setCellValue('AF5','MAYO');
            $this->docexcel->getActiveSheet()->mergeCells('AF5:AK5');
            $this->docexcel->getActiveSheet()->setCellValue('AF6','PREVISTO');
            $this->docexcel->getActiveSheet()->setCellValue('AG6','REAL');
            $this->docexcel->getActiveSheet()->setCellValue('AH6','DESVIACIÓN MES');
            $this->docexcel->getActiveSheet()->setCellValue('AI6','ACUM. PREVISTO');
            $this->docexcel->getActiveSheet()->setCellValue('AJ6','ACUM. REAL');
            $this->docexcel->getActiveSheet()->setCellValue('AK6','DESVIACIÓN ACUMULADA');

            $this->docexcel->getActiveSheet()->setCellValue('AL5','JUNIO');
            $this->docexcel->getActiveSheet()->mergeCells('AL5:AQ5');
            $this->docexcel->getActiveSheet()->setCellValue('AL6','PREVISTO');
            $this->docexcel->getActiveSheet()->setCellValue('AM6','REAL');
            $this->docexcel->getActiveSheet()->setCellValue('AN6','DESVIACIÓN MES');
            $this->docexcel->getActiveSheet()->setCellValue('AO6','ACUM. PREVISTO');
            $this->docexcel->getActiveSheet()->setCellValue('AP6','ACUM. REAL');
            $this->docexcel->getActiveSheet()->setCellValue('AQ6','DESVIACIÓN ACUMULADA');

            $this->docexcel->getActiveSheet()->setCellValue('AR5','JULIO');
            $this->docexcel->getActiveSheet()->mergeCells('AR5:AW5');
            $this->docexcel->getActiveSheet()->setCellValue('AR6','PREVISTO');
            $this->docexcel->getActiveSheet()->setCellValue('AS6','REAL');
            $this->docexcel->getActiveSheet()->setCellValue('AT6','DESVIACIÓN MES');
            $this->docexcel->getActiveSheet()->setCellValue('AU6','ACUM. PREVISTO');
            $this->docexcel->getActiveSheet()->setCellValue('AV6','ACUM. REAL');
            $this->docexcel->getActiveSheet()->setCellValue('AW6','DESVIACIÓN ACUMULADA');

            $this->docexcel->getActiveSheet()->setCellValue('AX5','AGOSTO');
            $this->docexcel->getActiveSheet()->mergeCells('AX5:BC5');
            $this->docexcel->getActiveSheet()->setCellValue('AX6','PREVISTO');
            $this->docexcel->getActiveSheet()->setCellValue('AY6','REAL');
            $this->docexcel->getActiveSheet()->setCellValue('AZ6','DESVIACIÓN MES');
            $this->docexcel->getActiveSheet()->setCellValue('BA6','ACUM. PREVISTO');
            $this->docexcel->getActiveSheet()->setCellValue('BB6','ACUM. REAL');
            $this->docexcel->getActiveSheet()->setCellValue('BC6','DESVIACIÓN ACUMULADA');

            $this->docexcel->getActiveSheet()->setCellValue('BD5','SEPTIEMBRE');
            $this->docexcel->getActiveSheet()->mergeCells('BD5:BI5');
            $this->docexcel->getActiveSheet()->setCellValue('BD6','PREVISTO');
            $this->docexcel->getActiveSheet()->setCellValue('BE6','REAL');
            $this->docexcel->getActiveSheet()->setCellValue('BF6','DESVIACIÓN MES');
            $this->docexcel->getActiveSheet()->setCellValue('BG6','ACUM. PREVISTO');
            $this->docexcel->getActiveSheet()->setCellValue('BH6','ACUM. REAL');
            $this->docexcel->getActiveSheet()->setCellValue('BI6','DESVIACIÓN ACUMULADA');

            $this->docexcel->getActiveSheet()->setCellValue('BJ5','OCTUBRE');
            $this->docexcel->getActiveSheet()->mergeCells('BJ5:BO5');
            $this->docexcel->getActiveSheet()->setCellValue('BJ6','PREVISTO');
            $this->docexcel->getActiveSheet()->setCellValue('BK6','REAL');
            $this->docexcel->getActiveSheet()->setCellValue('BL6','DESVIACIÓN MES');
            $this->docexcel->getActiveSheet()->setCellValue('BM6','ACUM. PREVISTO');
            $this->docexcel->getActiveSheet()->setCellValue('BN6','ACUM. REAL');
            $this->docexcel->getActiveSheet()->setCellValue('BO6','DESVIACIÓN ACUMULADA');

            $this->docexcel->getActiveSheet()->setCellValue('BP5','NOVIEMBRE');
            $this->docexcel->getActiveSheet()->mergeCells('BP5:BU5');
            $this->docexcel->getActiveSheet()->setCellValue('BP6','PREVISTO');
            $this->docexcel->getActiveSheet()->setCellValue('BQ6','REAL');
            $this->docexcel->getActiveSheet()->setCellValue('BR6','DESVIACIÓN MES');
            $this->docexcel->getActiveSheet()->setCellValue('BS6','ACUM. PREVISTO');
            $this->docexcel->getActiveSheet()->setCellValue('BT6','ACUM. REAL');
            $this->docexcel->getActiveSheet()->setCellValue('BU6','DESVIACIÓN ACUMULADA');

            $this->docexcel->getActiveSheet()->setCellValue('BV5','DICIEMBRE');
            $this->docexcel->getActiveSheet()->mergeCells('BV5:CA5');
            $this->docexcel->getActiveSheet()->setCellValue('BV6','PREVISTO');
            $this->docexcel->getActiveSheet()->setCellValue('BW6','REAL');
            $this->docexcel->getActiveSheet()->setCellValue('BX6','DESVIACIÓN MES');
            $this->docexcel->getActiveSheet()->setCellValue('BY6','ACUM. PREVISTO');
            $this->docexcel->getActiveSheet()->setCellValue('BZ6','ACUM. REAL');
            $this->docexcel->getActiveSheet()->setCellValue('CA6','DESVIACIÓN ACUMULADA');

    }
    function generarDatos_Nivel_dos()
    {
        $styleTitulos3 = array(
            'alignment' => array(
                'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT,
                'vertical' => PHPExcel_Style_Alignment::VERTICAL_TOP,
            ),
        );
        $this->numero = 1;
        $fila = 7;
        $tipo=$this->objParam->getParametro('var');
        $datos = $this->objParam->getParametro('datos');
        $this->imprimeCabecera_Nivel_dos(2);

        $styleAlineado = array(
            'alignment' => array(
                'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT,
                'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER
            ));
        //var_dump($datos);
                            
        foreach ($datos as $value){             

                //Ordenar textos
                $this->docexcel->getActiveSheet()->getStyle('B'.($fila).':CA'.($fila).'')->applyFromArray($styleAlineado);
                //
                if($value['nivel_1']||$value['nivel_2']){
                    $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(0, $fila, $this->numero);
                    $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(1, $fila, $value['nivel_1']);
                    $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(2, $fila, $value['nivel_2']);
                    $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(3, $fila, $value['nivel_3']);
                    $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(4, $fila, $value['nivel_4']);
                    $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(5, $fila, $value['responsable']);
                    $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(6, $fila, $value['peso']);

                    $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(7, $fila, $value['avance_previsto_ene']);
                    $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(8, $fila, $value['avance_real_ene']);
                    $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(9, $fila, $value['desviacion_mes_ene']);
                    $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(10, $fila, $value['acum_previsto_ene']);
                    $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(11, $fila, $value['acum_real_ene']);
                    $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(12, $fila, $value['desviacion_acumulada_ene']);

                    $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(13, $fila, $value['avance_previsto_feb']);
                    $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(14, $fila, $value['avance_real_feb']);
                    $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(15, $fila, $value['desviacion_mes_feb']);
                    $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(16, $fila, $value['acum_previsto_feb']);
                    $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(17, $fila, $value['acum_real_feb']);
                    $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(18, $fila, $value['desviacion_acumulada_feb']);

                    $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(19, $fila, $value['avance_previsto_mar']);
                    $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(20, $fila, $value['avance_real_mar']);
                    $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(21, $fila, $value['desviacion_mes_mar']);
                    $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(22, $fila, $value['acum_previsto_mar']);
                    $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(23, $fila, $value['acum_real_mar']);
                    $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(24, $fila, $value['desviacion_acumulada_mar']);

                    $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(25, $fila, $value['avance_previsto_abr']);
                    $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(26, $fila, $value['avance_real_abr']);
                    $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(27, $fila, $value['desviacion_mes_abr']);
                    $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(28, $fila, $value['acum_previsto_abr']);
                    $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(29, $fila, $value['acum_real_abr']);
                    $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(30, $fila, $value['desviacion_acumulada_abr']);

                    $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(31, $fila, $value['avance_previsto_may']);
                    $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(32, $fila, $value['avance_real_may']);
                    $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(33, $fila, $value['desviacion_mes_may']);
                    $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(34, $fila, $value['acum_previsto_may']);
                    $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(35, $fila, $value['acum_real_may']);
                    $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(36, $fila, $value['desviacion_acumulada_may']);

                    $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(37, $fila, $value['avance_previsto_jun']);
                    $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(38, $fila, $value['avance_real_jun']);
                    $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(39, $fila, $value['desviacion_mes_jun']);
                    $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(40, $fila, $value['acum_previsto_jun']);
                    $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(41, $fila, $value['acum_real_jun']);
                    $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(42, $fila, $value['desviacion_acumulada_jun']);

                    $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(43, $fila, $value['avance_previsto_jul']);
                    $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(44, $fila, $value['avance_real_jul']);
                    $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(45, $fila, $value['desviacion_mes_jul']);
                    $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(46, $fila, $value['acum_previsto_jul']);
                    $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(47, $fila, $value['acum_real_jul']);
                    $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(48, $fila, $value['desviacion_acumulada_jul']);

                    $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(49, $fila, $value['avance_previsto_ago']);
                    $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(50, $fila, $value['avance_real_ago']);
                    $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(51, $fila, $value['desviacion_mes_ago']);
                    $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(52, $fila, $value['acum_previsto_ago']);
                    $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(53, $fila, $value['acum_real_ago']);
                    $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(54, $fila, $value['desviacion_acumulada_ago']);

                    $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(55, $fila, $value['avance_previsto_sep']);
                    $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(56, $fila, $value['avance_real_sep']);
                    $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(57, $fila, $value['desviacion_mes_sep']);
                    $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(58, $fila, $value['acum_previsto_sep']);
                    $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(59, $fila, $value['acum_real_sep']);
                    $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(60, $fila, $value['desviacion_acumulada_sep']);

                    $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(61, $fila, $value['avance_previsto_oct']);
                    $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(62, $fila, $value['avance_real_oct']);
                    $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(63, $fila, $value['desviacion_mes_oct']);
                    $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(64, $fila, $value['acum_previsto_oct']);
                    $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(65, $fila, $value['acum_real_oct']);
                    $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(66, $fila, $value['desviacion_acumulada_oct']);

                    $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(67, $fila, $value['avance_previsto_nov']);
                    $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(68, $fila, $value['avance_real_nov']);
                    $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(69, $fila, $value['desviacion_mes_nov']);
                    $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(70, $fila, $value['acum_previsto_nov']);
                    $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(71, $fila, $value['acum_real_nov']);
                    $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(72, $fila, $value['desviacion_acumulada_nov']);

                    $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(73, $fila, $value['avance_previsto_dic']);
                    $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(74, $fila, $value['avance_real_dic']);
                    $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(75, $fila, $value['desviacion_mes_dic']);
                    $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(76, $fila, $value['acum_previsto_dic']);
                    $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(77, $fila, $value['acum_real_dic']);
                    $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(78, $fila, $value['desviacion_acumulada_dic']);
                    $fila++;
                    $this->numero++;
                }
        }
    }
    function imprimeCabecera_Nivel_tres() {
        //$this->docexcel->createSheet();     
        $tipo=$this->objParam->getParametro('var');
        $this->docexcel->getActiveSheet()->setTitle('NIVEL (1,2,3) ');
        $this->docexcel->setActiveSheetIndex(3);

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
        $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(0,2, 'TRANSPORTADORA DE ELÉCTRICIDAD' );
        $this->docexcel->getActiveSheet()->getStyle('A2:CA2')->applyFromArray($styleTitulos1);
        $this->docexcel->getActiveSheet()->mergeCells('A2:CA2');

        $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(0,3,' PLAN GLOBAL '.$this->objParam->getParametro('gestion').'  ('.$this->objParam->getParametro('nombre_plan').')');
        $this->docexcel->getActiveSheet()->getStyle('A3:CA3')->applyFromArray($styleTitulos1);
        $this->docexcel->getActiveSheet()->mergeCells('A3:CA3');
            
            $this->docexcel->getActiveSheet()->getColumnDimension('B')->setWidth(60);
            $this->docexcel->getActiveSheet()->getColumnDimension('C')->setWidth(60);
            $this->docexcel->getActiveSheet()->getColumnDimension('D')->setWidth(60);
            $this->docexcel->getActiveSheet()->getColumnDimension('E')->setWidth(60);
            $this->docexcel->getActiveSheet()->getColumnDimension('F')->setWidth(50);
            $this->docexcel->getActiveSheet()->getColumnDimension('G')->setWidth(7);

            $this->docexcel->getActiveSheet()->getColumnDimension('H')->setWidth(20);
            $this->docexcel->getActiveSheet()->getColumnDimension('I')->setWidth(20);
            $this->docexcel->getActiveSheet()->getColumnDimension('J')->setWidth(20);
            $this->docexcel->getActiveSheet()->getColumnDimension('K')->setWidth(30);
            $this->docexcel->getActiveSheet()->getColumnDimension('L')->setWidth(30);
            $this->docexcel->getActiveSheet()->getColumnDimension('M')->setWidth(16);

            $this->docexcel->getActiveSheet()->getColumnDimension('N')->setWidth(20);
            $this->docexcel->getActiveSheet()->getColumnDimension('O')->setWidth(20);
            $this->docexcel->getActiveSheet()->getColumnDimension('P')->setWidth(20);
            $this->docexcel->getActiveSheet()->getColumnDimension('Q')->setWidth(30);
            $this->docexcel->getActiveSheet()->getColumnDimension('R')->setWidth(30);
            $this->docexcel->getActiveSheet()->getColumnDimension('S')->setWidth(16);

            $this->docexcel->getActiveSheet()->getColumnDimension('T')->setWidth(20);
            $this->docexcel->getActiveSheet()->getColumnDimension('U')->setWidth(20);
            $this->docexcel->getActiveSheet()->getColumnDimension('V')->setWidth(20);
            $this->docexcel->getActiveSheet()->getColumnDimension('W')->setWidth(30);
            $this->docexcel->getActiveSheet()->getColumnDimension('X')->setWidth(30);
            $this->docexcel->getActiveSheet()->getColumnDimension('Y')->setWidth(16);

            $this->docexcel->getActiveSheet()->getColumnDimension('Z')->setWidth(20);
            $this->docexcel->getActiveSheet()->getColumnDimension('AA')->setWidth(20);
            $this->docexcel->getActiveSheet()->getColumnDimension('AB')->setWidth(20);
            $this->docexcel->getActiveSheet()->getColumnDimension('AC')->setWidth(30);
            $this->docexcel->getActiveSheet()->getColumnDimension('AD')->setWidth(30);
            $this->docexcel->getActiveSheet()->getColumnDimension('AE')->setWidth(16);

            $this->docexcel->getActiveSheet()->getColumnDimension('AF')->setWidth(20);
            $this->docexcel->getActiveSheet()->getColumnDimension('AG')->setWidth(20);
            $this->docexcel->getActiveSheet()->getColumnDimension('AH')->setWidth(20);
            $this->docexcel->getActiveSheet()->getColumnDimension('AI')->setWidth(30);
            $this->docexcel->getActiveSheet()->getColumnDimension('AJ')->setWidth(30);
            $this->docexcel->getActiveSheet()->getColumnDimension('AK')->setWidth(16);

            $this->docexcel->getActiveSheet()->getColumnDimension('AL')->setWidth(20);
            $this->docexcel->getActiveSheet()->getColumnDimension('AM')->setWidth(20);
            $this->docexcel->getActiveSheet()->getColumnDimension('AN')->setWidth(20);
            $this->docexcel->getActiveSheet()->getColumnDimension('AO')->setWidth(30);
            $this->docexcel->getActiveSheet()->getColumnDimension('AP')->setWidth(30);
            $this->docexcel->getActiveSheet()->getColumnDimension('AQ')->setWidth(16);

            $this->docexcel->getActiveSheet()->getColumnDimension('AR')->setWidth(20);
            $this->docexcel->getActiveSheet()->getColumnDimension('AS')->setWidth(20);
            $this->docexcel->getActiveSheet()->getColumnDimension('AT')->setWidth(20);
            $this->docexcel->getActiveSheet()->getColumnDimension('AU')->setWidth(30);
            $this->docexcel->getActiveSheet()->getColumnDimension('AV')->setWidth(30);
            $this->docexcel->getActiveSheet()->getColumnDimension('AW')->setWidth(16);

            $this->docexcel->getActiveSheet()->getColumnDimension('AX')->setWidth(20);
            $this->docexcel->getActiveSheet()->getColumnDimension('AY')->setWidth(20);
            $this->docexcel->getActiveSheet()->getColumnDimension('AZ')->setWidth(20);
            $this->docexcel->getActiveSheet()->getColumnDimension('BA')->setWidth(30);
            $this->docexcel->getActiveSheet()->getColumnDimension('BB')->setWidth(30);
            $this->docexcel->getActiveSheet()->getColumnDimension('BC')->setWidth(16);

            $this->docexcel->getActiveSheet()->getColumnDimension('BD')->setWidth(20);
            $this->docexcel->getActiveSheet()->getColumnDimension('BE')->setWidth(20);
            $this->docexcel->getActiveSheet()->getColumnDimension('BF')->setWidth(20);
            $this->docexcel->getActiveSheet()->getColumnDimension('BG')->setWidth(30);
            $this->docexcel->getActiveSheet()->getColumnDimension('BH')->setWidth(30);
            $this->docexcel->getActiveSheet()->getColumnDimension('BI')->setWidth(16);

            $this->docexcel->getActiveSheet()->getColumnDimension('BJ')->setWidth(20);
            $this->docexcel->getActiveSheet()->getColumnDimension('BK')->setWidth(20);
            $this->docexcel->getActiveSheet()->getColumnDimension('BL')->setWidth(20);
            $this->docexcel->getActiveSheet()->getColumnDimension('BM')->setWidth(30);
            $this->docexcel->getActiveSheet()->getColumnDimension('BN')->setWidth(30);
            $this->docexcel->getActiveSheet()->getColumnDimension('BO')->setWidth(16);

            $this->docexcel->getActiveSheet()->getColumnDimension('BP')->setWidth(20);
            $this->docexcel->getActiveSheet()->getColumnDimension('BQ')->setWidth(20);
            $this->docexcel->getActiveSheet()->getColumnDimension('BR')->setWidth(20);
            $this->docexcel->getActiveSheet()->getColumnDimension('BS')->setWidth(30);
            $this->docexcel->getActiveSheet()->getColumnDimension('BT')->setWidth(30);
            $this->docexcel->getActiveSheet()->getColumnDimension('BU')->setWidth(16);

            $this->docexcel->getActiveSheet()->getColumnDimension('BV')->setWidth(20);
            $this->docexcel->getActiveSheet()->getColumnDimension('BW')->setWidth(20);
            $this->docexcel->getActiveSheet()->getColumnDimension('BX')->setWidth(20);
            $this->docexcel->getActiveSheet()->getColumnDimension('BY')->setWidth(30);
            $this->docexcel->getActiveSheet()->getColumnDimension('BZ')->setWidth(30);
            $this->docexcel->getActiveSheet()->getColumnDimension('CA')->setWidth(16);
            //$this->docexcel->getActiveSheet()->getColumnDimension('T')->setWidth(40);

            $this->docexcel->getActiveSheet()->getStyle('A5:CA5')->getAlignment()->setWrapText(true);
            $this->docexcel->getActiveSheet()->getStyle('A5:CA5')->applyFromArray($styleTitulos2);

            $this->docexcel->getActiveSheet()->getStyle('A6:CA6')->getAlignment()->setWrapText(true);
            $this->docexcel->getActiveSheet()->getStyle('A6:CA6')->applyFromArray($styleTitulos2);
            //*************************************Cabecera*****************************************
            $this->docexcel->getActiveSheet()->setCellValue('A5','Nº');
            $this->docexcel->getActiveSheet()->mergeCells('A5:A6');
            $this->docexcel->getActiveSheet()->setCellValue('B5','NIVEL 1');
            $this->docexcel->getActiveSheet()->mergeCells('B5:B6');
            $this->docexcel->getActiveSheet()->setCellValue('C5','NIVEL 2');
            $this->docexcel->getActiveSheet()->mergeCells('C5:C6');
            $this->docexcel->getActiveSheet()->setCellValue('D5','NIVEL 3');
            $this->docexcel->getActiveSheet()->mergeCells('D5:D6');
            $this->docexcel->getActiveSheet()->setCellValue('E5','NIVEL 4');
            $this->docexcel->getActiveSheet()->mergeCells('E5:E6');
            $this->docexcel->getActiveSheet()->setCellValue('F5','RESPONSABLE');
            $this->docexcel->getActiveSheet()->mergeCells('F5:F6');
            $this->docexcel->getActiveSheet()->setCellValue('G5','PESO');
            $this->docexcel->getActiveSheet()->mergeCells('G5:G6');

            $this->docexcel->getActiveSheet()->setCellValue('H5','ENERO');
            $this->docexcel->getActiveSheet()->mergeCells('H5:M5');
            $this->docexcel->getActiveSheet()->setCellValue('H6','PREVISTO');
            $this->docexcel->getActiveSheet()->setCellValue('I6','REAL');
            $this->docexcel->getActiveSheet()->setCellValue('J6','DESVIACIÓN MES');
            $this->docexcel->getActiveSheet()->setCellValue('K6','ACUM. PREVISTO');
            $this->docexcel->getActiveSheet()->setCellValue('L6','ACUM. REAL');
            $this->docexcel->getActiveSheet()->setCellValue('M6','DESVIACIÓN ACUMULADA');
            
            $this->docexcel->getActiveSheet()->setCellValue('N5','FEBRERO');
            $this->docexcel->getActiveSheet()->mergeCells('N5:S5');
            $this->docexcel->getActiveSheet()->setCellValue('N6','PREVISTO');
            $this->docexcel->getActiveSheet()->setCellValue('O6','REAL');
            $this->docexcel->getActiveSheet()->setCellValue('P6','DESVIACIÓN MES');
            $this->docexcel->getActiveSheet()->setCellValue('Q6','ACUM. PREVISTO');
            $this->docexcel->getActiveSheet()->setCellValue('R6','ACUM. REAL');
            $this->docexcel->getActiveSheet()->setCellValue('S6','DESVIACIÓN ACUMULADA');

            $this->docexcel->getActiveSheet()->setCellValue('T5','MARZO');
            $this->docexcel->getActiveSheet()->mergeCells('T5:Y5');
            $this->docexcel->getActiveSheet()->setCellValue('T6','PREVISTO');
            $this->docexcel->getActiveSheet()->setCellValue('U6','REAL');
            $this->docexcel->getActiveSheet()->setCellValue('V6','DESVIACIÓN MES');
            $this->docexcel->getActiveSheet()->setCellValue('W6','ACUM. PREVISTO');
            $this->docexcel->getActiveSheet()->setCellValue('X6','ACUM. REAL');
            $this->docexcel->getActiveSheet()->setCellValue('Y6','DESVIACIÓN ACUMULADA');

            $this->docexcel->getActiveSheet()->setCellValue('Z5','ABRIL');
            $this->docexcel->getActiveSheet()->mergeCells('Z5:AE5');
            $this->docexcel->getActiveSheet()->setCellValue('Z6','PREVISTO');
            $this->docexcel->getActiveSheet()->setCellValue('AA6','REAL');
            $this->docexcel->getActiveSheet()->setCellValue('AB6','DESVIACIÓN MES');
            $this->docexcel->getActiveSheet()->setCellValue('AC6','ACUM. PREVISTO');
            $this->docexcel->getActiveSheet()->setCellValue('AD6','ACUM. REAL');
            $this->docexcel->getActiveSheet()->setCellValue('AE6','DESVIACIÓN ACUMULADA');

            $this->docexcel->getActiveSheet()->setCellValue('AF5','MAYO');
            $this->docexcel->getActiveSheet()->mergeCells('AF5:AK5');
            $this->docexcel->getActiveSheet()->setCellValue('AF6','PREVISTO');
            $this->docexcel->getActiveSheet()->setCellValue('AG6','REAL');
            $this->docexcel->getActiveSheet()->setCellValue('AH6','DESVIACIÓN MES');
            $this->docexcel->getActiveSheet()->setCellValue('AI6','ACUM. PREVISTO');
            $this->docexcel->getActiveSheet()->setCellValue('AJ6','ACUM. REAL');
            $this->docexcel->getActiveSheet()->setCellValue('AK6','DESVIACIÓN ACUMULADA');

            $this->docexcel->getActiveSheet()->setCellValue('AL5','JUNIO');
            $this->docexcel->getActiveSheet()->mergeCells('AL5:AQ5');
            $this->docexcel->getActiveSheet()->setCellValue('AL6','PREVISTO');
            $this->docexcel->getActiveSheet()->setCellValue('AM6','REAL');
            $this->docexcel->getActiveSheet()->setCellValue('AN6','DESVIACIÓN MES');
            $this->docexcel->getActiveSheet()->setCellValue('AO6','ACUM. PREVISTO');
            $this->docexcel->getActiveSheet()->setCellValue('AP6','ACUM. REAL');
            $this->docexcel->getActiveSheet()->setCellValue('AQ6','DESVIACIÓN ACUMULADA');

            $this->docexcel->getActiveSheet()->setCellValue('AR5','JULIO');
            $this->docexcel->getActiveSheet()->mergeCells('AR5:AW5');
            $this->docexcel->getActiveSheet()->setCellValue('AR6','PREVISTO');
            $this->docexcel->getActiveSheet()->setCellValue('AS6','REAL');
            $this->docexcel->getActiveSheet()->setCellValue('AT6','DESVIACIÓN MES');
            $this->docexcel->getActiveSheet()->setCellValue('AU6','ACUM. PREVISTO');
            $this->docexcel->getActiveSheet()->setCellValue('AV6','ACUM. REAL');
            $this->docexcel->getActiveSheet()->setCellValue('AW6','DESVIACIÓN ACUMULADA');

            $this->docexcel->getActiveSheet()->setCellValue('AX5','AGOSTO');
            $this->docexcel->getActiveSheet()->mergeCells('AX5:BC5');
            $this->docexcel->getActiveSheet()->setCellValue('AX6','PREVISTO');
            $this->docexcel->getActiveSheet()->setCellValue('AY6','REAL');
            $this->docexcel->getActiveSheet()->setCellValue('AZ6','DESVIACIÓN MES');
            $this->docexcel->getActiveSheet()->setCellValue('BA6','ACUM. PREVISTO');
            $this->docexcel->getActiveSheet()->setCellValue('BB6','ACUM. REAL');
            $this->docexcel->getActiveSheet()->setCellValue('BC6','DESVIACIÓN ACUMULADA');

            $this->docexcel->getActiveSheet()->setCellValue('BD5','SEPTIEMBRE');
            $this->docexcel->getActiveSheet()->mergeCells('BD5:BI5');
            $this->docexcel->getActiveSheet()->setCellValue('BD6','PREVISTO');
            $this->docexcel->getActiveSheet()->setCellValue('BE6','REAL');
            $this->docexcel->getActiveSheet()->setCellValue('BF6','DESVIACIÓN MES');
            $this->docexcel->getActiveSheet()->setCellValue('BG6','ACUM. PREVISTO');
            $this->docexcel->getActiveSheet()->setCellValue('BH6','ACUM. REAL');
            $this->docexcel->getActiveSheet()->setCellValue('BI6','DESVIACIÓN ACUMULADA');

            $this->docexcel->getActiveSheet()->setCellValue('BJ5','OCTUBRE');
            $this->docexcel->getActiveSheet()->mergeCells('BJ5:BO5');
            $this->docexcel->getActiveSheet()->setCellValue('BJ6','PREVISTO');
            $this->docexcel->getActiveSheet()->setCellValue('BK6','REAL');
            $this->docexcel->getActiveSheet()->setCellValue('BL6','DESVIACIÓN MES');
            $this->docexcel->getActiveSheet()->setCellValue('BM6','ACUM. PREVISTO');
            $this->docexcel->getActiveSheet()->setCellValue('BN6','ACUM. REAL');
            $this->docexcel->getActiveSheet()->setCellValue('BO6','DESVIACIÓN ACUMULADA');

            $this->docexcel->getActiveSheet()->setCellValue('BP5','NOVIEMBRE');
            $this->docexcel->getActiveSheet()->mergeCells('BP5:BU5');
            $this->docexcel->getActiveSheet()->setCellValue('BP6','PREVISTO');
            $this->docexcel->getActiveSheet()->setCellValue('BQ6','REAL');
            $this->docexcel->getActiveSheet()->setCellValue('BR6','DESVIACIÓN MES');
            $this->docexcel->getActiveSheet()->setCellValue('BS6','ACUM. PREVISTO');
            $this->docexcel->getActiveSheet()->setCellValue('BT6','ACUM. REAL');
            $this->docexcel->getActiveSheet()->setCellValue('BU6','DESVIACIÓN ACUMULADA');

            $this->docexcel->getActiveSheet()->setCellValue('BV5','DICIEMBRE');
            $this->docexcel->getActiveSheet()->mergeCells('BV5:CA5');
            $this->docexcel->getActiveSheet()->setCellValue('BV6','PREVISTO');
            $this->docexcel->getActiveSheet()->setCellValue('BW6','REAL');
            $this->docexcel->getActiveSheet()->setCellValue('BX6','DESVIACIÓN MES');
            $this->docexcel->getActiveSheet()->setCellValue('BY6','ACUM. PREVISTO');
            $this->docexcel->getActiveSheet()->setCellValue('BZ6','ACUM. REAL');
            $this->docexcel->getActiveSheet()->setCellValue('CA6','DESVIACIÓN ACUMULADA');

    }
    function generarDatos_Nivel_tres()
    {
        $styleTitulos3 = array(
            'alignment' => array(
                'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT,
                'vertical' => PHPExcel_Style_Alignment::VERTICAL_TOP,
            ),
        );
        $this->numero = 1;
        $fila = 7;
        $tipo=$this->objParam->getParametro('var');
        $datos = $this->objParam->getParametro('datos');
        $this->imprimeCabecera_Nivel_tres(3);

        $styleAlineado = array(
            'alignment' => array(
                'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT,
                'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER
            ));
        //var_dump($datos);
                            
        foreach ($datos as $value){             

                //Ordenar textos
                $this->docexcel->getActiveSheet()->getStyle('B'.($fila).':CA'.($fila).'')->applyFromArray($styleAlineado);
                //
                if($value['nivel_1']||$value['nivel_2']||$value['nivel_3']){
                    $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(0, $fila, $this->numero);
                    $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(1, $fila, $value['nivel_1']);
                    $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(2, $fila, $value['nivel_2']);
                    $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(3, $fila, $value['nivel_3']);
                    $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(4, $fila, $value['nivel_4']);
                    $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(5, $fila, $value['responsable']);
                    $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(6, $fila, $value['peso']);

                    $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(7, $fila, $value['avance_previsto_ene']);
                    $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(8, $fila, $value['avance_real_ene']);
                    $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(9, $fila, $value['desviacion_mes_ene']);
                    $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(10, $fila, $value['acum_previsto_ene']);
                    $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(11, $fila, $value['acum_real_ene']);
                    $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(12, $fila, $value['desviacion_acumulada_ene']);

                    $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(13, $fila, $value['avance_previsto_feb']);
                    $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(14, $fila, $value['avance_real_feb']);
                    $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(15, $fila, $value['desviacion_mes_feb']);
                    $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(16, $fila, $value['acum_previsto_feb']);
                    $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(17, $fila, $value['acum_real_feb']);
                    $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(18, $fila, $value['desviacion_acumulada_feb']);

                    $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(19, $fila, $value['avance_previsto_mar']);
                    $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(20, $fila, $value['avance_real_mar']);
                    $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(21, $fila, $value['desviacion_mes_mar']);
                    $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(22, $fila, $value['acum_previsto_mar']);
                    $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(23, $fila, $value['acum_real_mar']);
                    $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(24, $fila, $value['desviacion_acumulada_mar']);

                    $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(25, $fila, $value['avance_previsto_abr']);
                    $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(26, $fila, $value['avance_real_abr']);
                    $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(27, $fila, $value['desviacion_mes_abr']);
                    $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(28, $fila, $value['acum_previsto_abr']);
                    $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(29, $fila, $value['acum_real_abr']);
                    $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(30, $fila, $value['desviacion_acumulada_abr']);

                    $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(31, $fila, $value['avance_previsto_may']);
                    $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(32, $fila, $value['avance_real_may']);
                    $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(33, $fila, $value['desviacion_mes_may']);
                    $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(34, $fila, $value['acum_previsto_may']);
                    $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(35, $fila, $value['acum_real_may']);
                    $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(36, $fila, $value['desviacion_acumulada_may']);

                    $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(37, $fila, $value['avance_previsto_jun']);
                    $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(38, $fila, $value['avance_real_jun']);
                    $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(39, $fila, $value['desviacion_mes_jun']);
                    $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(40, $fila, $value['acum_previsto_jun']);
                    $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(41, $fila, $value['acum_real_jun']);
                    $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(42, $fila, $value['desviacion_acumulada_jun']);

                    $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(43, $fila, $value['avance_previsto_jul']);
                    $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(44, $fila, $value['avance_real_jul']);
                    $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(45, $fila, $value['desviacion_mes_jul']);
                    $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(46, $fila, $value['acum_previsto_jul']);
                    $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(47, $fila, $value['acum_real_jul']);
                    $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(48, $fila, $value['desviacion_acumulada_jul']);

                    $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(49, $fila, $value['avance_previsto_ago']);
                    $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(50, $fila, $value['avance_real_ago']);
                    $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(51, $fila, $value['desviacion_mes_ago']);
                    $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(52, $fila, $value['acum_previsto_ago']);
                    $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(53, $fila, $value['acum_real_ago']);
                    $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(54, $fila, $value['desviacion_acumulada_ago']);

                    $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(55, $fila, $value['avance_previsto_sep']);
                    $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(56, $fila, $value['avance_real_sep']);
                    $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(57, $fila, $value['desviacion_mes_sep']);
                    $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(58, $fila, $value['acum_previsto_sep']);
                    $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(59, $fila, $value['acum_real_sep']);
                    $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(60, $fila, $value['desviacion_acumulada_sep']);

                    $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(61, $fila, $value['avance_previsto_oct']);
                    $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(62, $fila, $value['avance_real_oct']);
                    $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(63, $fila, $value['desviacion_mes_oct']);
                    $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(64, $fila, $value['acum_previsto_oct']);
                    $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(65, $fila, $value['acum_real_oct']);
                    $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(66, $fila, $value['desviacion_acumulada_oct']);

                    $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(67, $fila, $value['avance_previsto_nov']);
                    $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(68, $fila, $value['avance_real_nov']);
                    $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(69, $fila, $value['desviacion_mes_nov']);
                    $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(70, $fila, $value['acum_previsto_nov']);
                    $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(71, $fila, $value['acum_real_nov']);
                    $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(72, $fila, $value['desviacion_acumulada_nov']);

                    $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(73, $fila, $value['avance_previsto_dic']);
                    $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(74, $fila, $value['avance_real_dic']);
                    $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(75, $fila, $value['desviacion_mes_dic']);
                    $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(76, $fila, $value['acum_previsto_dic']);
                    $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(77, $fila, $value['acum_real_dic']);
                    $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(78, $fila, $value['desviacion_acumulada_dic']);
                    $fila++;
                    $this->numero++;
                }
        }
    }


}
?>