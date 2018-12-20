<?php
class RConsultaOpObligacionPagoXls
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
        $this->docexcel->getActiveSheet()->setTitle('Consulta OP por plan de pagos');
		
		//var_dump('ver gestion ',$gestion);
		//$this->docexcel->getActiveSheet()->setTitle($gestion);
        $this->docexcel->setActiveSheetIndex(0);

        $datos = $this->objParam->getParametro('datos');
		
        $gestion = $this->objParam->getParametro('gestion');
        $styleTitulos1 = array(
            'font'  => array(
                'bold'  => true,
                'size'  => 12,
                'name'  => 'Arial'
            ),
            'alignment' => array(
                'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
                'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,
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
                'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
                'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,
            ),

        );

        $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(0,2,'CONSULTA OP POR PLAN DE PAGOS' );
        $this->docexcel->getActiveSheet()->getStyle('A2:O2')->applyFromArray($styleTitulos1);
        $this->docexcel->getActiveSheet()->mergeCells('A2:O2');
		
        $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(0,3,'GESTIÓN '.$gestion );
        $this->docexcel->getActiveSheet()->getStyle('A3:O3')->applyFromArray($styleTitulos1);
        $this->docexcel->getActiveSheet()->mergeCells('A3:O3');

        $this->docexcel->getActiveSheet()->getColumnDimension('B')->setWidth(25);
        $this->docexcel->getActiveSheet()->getColumnDimension('C')->setWidth(30);
        $this->docexcel->getActiveSheet()->getColumnDimension('D')->setWidth(40);
        $this->docexcel->getActiveSheet()->getColumnDimension('E')->setWidth(30);
        $this->docexcel->getActiveSheet()->getColumnDimension('F')->setWidth(30);
        $this->docexcel->getActiveSheet()->getColumnDimension('G')->setWidth(30);
		$this->docexcel->getActiveSheet()->getColumnDimension('H')->setWidth(30);
		
		$this->docexcel->getActiveSheet()->getColumnDimension('I')->setWidth(30);
		$this->docexcel->getActiveSheet()->getColumnDimension('J')->setWidth(30);
		$this->docexcel->getActiveSheet()->getColumnDimension('K')->setWidth(30);
		$this->docexcel->getActiveSheet()->getColumnDimension('L')->setWidth(30);
		$this->docexcel->getActiveSheet()->getColumnDimension('M')->setWidth(30);
		$this->docexcel->getActiveSheet()->getColumnDimension('N')->setWidth(30);
		$this->docexcel->getActiveSheet()->getColumnDimension('O')->setWidth(30);
		$this->docexcel->getActiveSheet()->getColumnDimension('P')->setWidth(30);
		$this->docexcel->getActiveSheet()->getColumnDimension('Q')->setWidth(30);
		$this->docexcel->getActiveSheet()->getColumnDimension('R')->setWidth(30);
		$this->docexcel->getActiveSheet()->getColumnDimension('S')->setWidth(30);
		$this->docexcel->getActiveSheet()->getColumnDimension('T')->setWidth(30);
		$this->docexcel->getActiveSheet()->getColumnDimension('U')->setWidth(30);
		$this->docexcel->getActiveSheet()->getColumnDimension('V')->setWidth(30);
		$this->docexcel->getActiveSheet()->getColumnDimension('W')->setWidth(30);
		$this->docexcel->getActiveSheet()->getColumnDimension('X')->setWidth(30);
		$this->docexcel->getActiveSheet()->getColumnDimension('Y')->setWidth(30);
		$this->docexcel->getActiveSheet()->getColumnDimension('Z')->setWidth(30);
		$this->docexcel->getActiveSheet()->getColumnDimension('AA')->setWidth(30);
		$this->docexcel->getActiveSheet()->getColumnDimension('AB')->setWidth(30);
		$this->docexcel->getActiveSheet()->getColumnDimension('AC')->setWidth(30);
		$this->docexcel->getActiveSheet()->getColumnDimension('AD')->setWidth(30);
		$this->docexcel->getActiveSheet()->getColumnDimension('AE')->setWidth(30);
		$this->docexcel->getActiveSheet()->getColumnDimension('AF')->setWidth(30);
		$this->docexcel->getActiveSheet()->getColumnDimension('AG')->setWidth(30);
		$this->docexcel->getActiveSheet()->getColumnDimension('AH')->setWidth(30);
		$this->docexcel->getActiveSheet()->getColumnDimension('AI')->setWidth(30);
		$this->docexcel->getActiveSheet()->getColumnDimension('AJ')->setWidth(30);
        /*$this->docexcel->getActiveSheet()->getColumnDimension('H')->setWidth(30);
        $this->docexcel->getActiveSheet()->getColumnDimension('I')->setWidth(30);
        $this->docexcel->getActiveSheet()->getColumnDimension('J')->setWidth(30);
        $this->docexcel->getActiveSheet()->getColumnDimension('K')->setWidth(30);
        $this->docexcel->getActiveSheet()->getColumnDimension('L')->setWidth(30);
        $this->docexcel->getActiveSheet()->getColumnDimension('M')->setWidth(30);
        $this->docexcel->getActiveSheet()->getColumnDimension('N')->setWidth(30);
        $this->docexcel->getActiveSheet()->getColumnDimension('O')->setWidth(30);*/




        $this->docexcel->getActiveSheet()->getStyle('A5:AJ5')->getAlignment()->setWrapText(true);
        $this->docexcel->getActiveSheet()->getStyle('A5:AJ5')->applyFromArray($styleTitulos2);



        //*************************************Cabecera*****************************************
        $this->docexcel->getActiveSheet()->setCellValue('A5','Nº');
        $this->docexcel->getActiveSheet()->setCellValue('B5','Num. tramite');
        $this->docexcel->getActiveSheet()->setCellValue('C5','Estado');
        $this->docexcel->getActiveSheet()->setCellValue('D5','Ultimacuota pp');
        $this->docexcel->getActiveSheet()->setCellValue('E5','Ultima cuota pp');
        $this->docexcel->getActiveSheet()->setCellValue('F5','fecha');
        $this->docexcel->getActiveSheet()->setCellValue('G5','funcionario');
		$this->docexcel->getActiveSheet()->setCellValue('H5','proveedor');
		
		$this->docexcel->getActiveSheet()->setCellValue('I5','Total pago');
		$this->docexcel->getActiveSheet()->setCellValue('J5','Moneda');
		$this->docexcel->getActiveSheet()->setCellValue('K5','Pago variable');
		$this->docexcel->getActiveSheet()->setCellValue('L5','Pedido sap');
		$this->docexcel->getActiveSheet()->setCellValue('M5','Monto adjudicado');
		
		$this->docexcel->getActiveSheet()->setCellValue('N5','Anticipo total');
		$this->docexcel->getActiveSheet()->setCellValue('O5','Saldo anticipo por retener');
		$this->docexcel->getActiveSheet()->setCellValue('P5','Monto estimado sg');
		$this->docexcel->getActiveSheet()->setCellValue('Q5','Nro cuota');
		$this->docexcel->getActiveSheet()->setCellValue('R5','Estado rev');
		
		$this->docexcel->getActiveSheet()->setCellValue('S5','Tipo cuota');
		$this->docexcel->getActiveSheet()->setCellValue('T5','Nombre pago');
		$this->docexcel->getActiveSheet()->setCellValue('U5','Fecha tentativa');
		$this->docexcel->getActiveSheet()->setCellValue('V5','Liquido pagable');
		$this->docexcel->getActiveSheet()->setCellValue('W5','Obligacion pago');
		
		$this->docexcel->getActiveSheet()->setCellValue('X5','Documento');
		$this->docexcel->getActiveSheet()->setCellValue('Y5','Monto');
		$this->docexcel->getActiveSheet()->setCellValue('Z5','Monto excento');
		$this->docexcel->getActiveSheet()->setCellValue('AA5','Monto anticipo');
		$this->docexcel->getActiveSheet()->setCellValue('AB5','Descuento anticipo');
		
		$this->docexcel->getActiveSheet()->setCellValue('AC5','Retencion garantia');
		$this->docexcel->getActiveSheet()->setCellValue('AD5','Monto no pagado');
		$this->docexcel->getActiveSheet()->setCellValue('AE5','Multas');
		$this->docexcel->getActiveSheet()->setCellValue('AF5','Descuento intercambio servicio');
		$this->docexcel->getActiveSheet()->setCellValue('AG5','Descuento ley');
		
		$this->docexcel->getActiveSheet()->setCellValue('AH5','Total ejecutar presupuestariamente');
		$this->docexcel->getActiveSheet()->setCellValue('AI5','Cuenta bancaria');
		$this->docexcel->getActiveSheet()->setCellValue('AJ5','Libro de banos');

        /*$this->docexcel->getActiveSheet()->setCellValue('H5','MPORTE TOTAL DE LA COMPRAA');
        $this->docexcel->getActiveSheet()->setCellValue('I5','IMPORTE NO SUJETO A CREDITO FISCAL B');

        $this->docexcel->getActiveSheet()->setCellValue('J5','SUBTOTAL C = A - B');
        if($datos[0]['gestion']<2017) {
            $this->docexcel->getActiveSheet()->setCellValue('K5', 'DESCUENTOS BONIFICACION ES Y REBAJAS OBTENIDAS D');
        }else{
            $this->docexcel->getActiveSheet()->setCellValue('K5', 'DESCUENTOS BONIFICACION ES Y REBAJAS SUJETAS AL IVA D');
        }
        $this->docexcel->getActiveSheet()->setCellValue('L5','MPORTE BASE PARA CREDITO FISCAL E = C-D');
        $this->docexcel->getActiveSheet()->setCellValue('M5','CREDITO FISCAL F = E*13%');
        $this->docexcel->getActiveSheet()->setCellValue('N5','CODIGO DE CONTROL');
        $this->docexcel->getActiveSheet()->setCellValue('O5','TIPO DE COMPRA');*/



    }
    function generarDatos()
    {
        $styleTitulos3 = array(
            'alignment' => array(
                'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
                'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,
            ),
        );

        $this->numero = 1;
        $fila = 6;
        $datos = $this->objParam->getParametro('datos');
        $this->imprimeCabecera(0);
        //var_dump($datos);exit;
        foreach ($datos as $value){

                $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(0, $fila, $this->numero);
                $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(1, $fila, $value['num_tramite']);
                $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(2, $fila, $value['estado']);
                $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(3, $fila, $value['ultima_cuota_pp']);
                $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(4, $fila, $value['ultimo_estado_pp']);
                $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(5, $fila, $value['fecha']);
                $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(6, $fila, $value['funcionario']);
				$this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(7, $fila, $value['proveedor']);
				
				$this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(8, $fila, $value['total_pago']);
				$this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(9, $fila, $value['moneda']);
				$this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(10, $fila, $value['pago_variable']);
				$this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(11, $fila, $value['pedido_sap']);
				$this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(12, $fila, $value['monto_adjudicado']);
				
				$this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(13, $fila, $value['anticipo_total']);
				$this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(14, $fila, $value['saldo_anticipo_por_retener']);
				$this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(15, $fila, $value['monto_estimado_sg']);
				$this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(16, $fila, $value['nro_cuota']);
				$this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(17, $fila, $value['estado_rev']);
				
				$this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(18, $fila, $value['tipo_cuota']);
				$this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(19, $fila, $value['nombre_pago']);
				$this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(20, $fila, $value['fecha_tentativa']);
				$this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(21, $fila, $value['liquido_pagable']);
				$this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(22, $fila, $value['obligacion_pago']);
				
				$this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(23, $fila, $value['documento']);
				$this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(24, $fila, $value['monto']);
				$this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(25, $fila, $value['monto_excento']);
				$this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(26, $fila, $value['monto_anticipo']);
				$this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(27, $fila, $value['descuento_anticipo']);
				
				$this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(28, $fila, $value['retencion_garantia']);
				$this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(29, $fila, $value['monto_no_pagado']);
				$this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(30, $fila, $value['multas']);
				$this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(31, $fila, $value['descuento_intercambio_servicio']);
				$this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(32, $fila, $value['descuento_ley']);
				
				$this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(33, $fila, $value['total_ejecutar_presupuestariamente']);
				$this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(34, $fila, $value['cuenta_bancaria']);
				$this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(35, $fila, $value['libro_banos']);
				
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