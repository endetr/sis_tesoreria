<?php
// Extend the TCPDF class to create custom MultiRow
class RProcesoCaja extends ReportePDF {
	var $datos_titulo;
	var $datos_detalle;
	var $ancho_hoja;
	var $gerencia;
	var $numeracion;
	var $ancho_sin_totales;
	var $cantidad_columnas_estaticas;
	var $s1;
	var $s2;
	var $s3;
	var $s4;
	var $s5;
	var $s6;
	var $t1;
	var $t2;
	var $t3;
	var $t4;
	var $t5;
	var $t6;
	var $total;
	var $datos_entidad;
	var $datos_periodo;
	var $cant;
	var $valor;
	var $rz;
	var $fun;
	var $fec;
	function datosHeader ($detalle,$resultado,$tpoestado,$auxiliar) {
		$this->SetHeaderMargin(10);
		$this->SetAutoPageBreak(TRUE, 10);
		$this->ancho_hoja = $this->getPageWidth()-PDF_MARGIN_LEFT-PDF_MARGIN_RIGHT-10;
		$this->datos_detalle = $detalle;
		$this->datos_titulo = $resultado;
		$this->SetMargins(20, 15, 5,10);
	}
	//
	function getDataSource(){
		return  $this->datos_detalle;		
	}
	//
	function Header() {		
	}
	//	
	function generarCabecera(){
		$conf_par_tablewidths=array(7,15,20,30,20,20,20,20);
		$conf_par_tablenumbers=array(0,0,0,0,0,0,0,0);
		$conf_par_tablealigns=array('C','C','C','C','C','C','C','C');
		$conf_tabletextcolor=array();
		$this->tablewidths=$conf_par_tablewidths;
		$this->tablealigns=$conf_par_tablealigns;
		$this->tablenumbers=$conf_par_tablenumbers;
		$this->tableborders=$conf_tableborders;
		$this->tabletextcolor=$conf_tabletextcolor;
		$valor=$a;
		$RowArray = array(
							's0' => 'Nº',
							's1' => 'FECHA',
							's2' => 'NRO TRAMITE',
							's3' => 'RAZON SOCIAL',
							's4' => 'FUNCIONARIO',
							's5' => 'TIPO',
							's6' => 'MONTO',
							's7' => 'IMPORTE',
						);
		$this->MultiRow($RowArray, false, 1);
	}
	//
	function generarReporte() {
		$this->setFontSubsetting(false);
		$this->AddPage();
		$this->generarCuerpo($this->datos_detalle);
	}
	//		
	function generarCuerpo($detalle){		
		//function		
		$this->cab();
		$count = 1;
		$sw = 0;
		$ult_region = '';
		$fill = 0;
		$this->total = count($detalle);
		$this->s1 = 0;
		$this->s2 = 0;
		$this->s3 = 0;
		$this->s4 = 0;
		$this->s5 = 0;
		$this->imprimirLinea($val,$count,$fill);
	}
	//
	function imprimirLinea($val,$count,$fill){
		$this->SetFillColor(224, 235, 255);
		$this->SetTextColor(0);
		$this->SetFont('','',6);
		$this->tablenumbers=array(0,0,0,0,0,0,2,2);
		$this->tablealigns=array('C','L','L','L','L','L','R','R');			
		$this->tableborders=array('RLTB','RLTB','RLTB','RLTB','RLTB','RLTB','RLTB','RLTB');
		$this->tabletextcolor=array();	
		$i=0;
		foreach ($this->getDataSource() as $datarow) {
			if($i==0){
				$rz=$datarow['razon_social'];
				$fun=$datarow['funcionario'];
				$fec=$datarow['fecha'];
			}else{			
				$RowArray = array(
					's0' => $i,
					's1' => trim($datarow['fecha']),
					's2' => trim($datarow['tramites']),
					's3' => trim($datarow['razon_social']),				
					's4' => trim($datarow['funcionario']),
					's5' => trim($datarow['tipo']),
					's6' => $datarow['monto'],
					's7' => $datarow['importe_pago_liquido']
				);
				$fill = !$fill;					
				$this->total = $this->total -1;								
				$this-> MultiRow($RowArray,$fill,0);			
				$this->revisarfinPagina($datarow);
			}	
			$i++;			
		}			
		$this->cerrarCuadro();
		$this->cerrarCuadroTotal();	
		$this->tablewidths=$conf_par_tablewidths;
		$this->tablealigns=$conf_par_tablealigns;
		$this->tablenumbers=$conf_par_tablenumbers;
		$this->tableborders=$conf_tableborders;
		$this->tabletextcolor=$conf_tabletextcolor;		
	} 
	//
	function revisarfinPagina($a){
		$dimensions = $this->getPageDimensions();
		$hasBorder = false;
		$startY = $this->GetY();
		$this->getNumLines($row['cell1data'], 90);
		$this->calcularMontos($a);			
		if ($startY > 237) {			
			$this->cerrarCuadro();
			$this->cerrarCuadroTotal();		
			if($this->total!= 0){
				$this->AddPage();
				$this->generarCabecera();
			}				
		}
	}
	//
	function calcularMontos($val){
		$this->s1 = $this->s1 + $val['monto'];
		$this->s2 = $this->s2 + $val['importe_pago_liquido'];
		
		$this->t1 = $this->t1 + $val['monto'];
		$this->t2 = $this->t2 + $val['importe_pago_liquido'];					
	}	
	//revisarfinPagina pie
	function cerrarCuadro(){
		//si noes inicio termina el cuardro anterior
		$conf_par_tablewidths=array(7,15,20,30,20,20,20,20);				
		$this->tablealigns=array('R','R','R','R','R','R','R','R');		
		$this->tablenumbers=array(0,0,0,0,0,0,2,2);
		$this->tableborders=array('T','T','T','T','T','T','LRTB','LRTB');								
		$RowArray = array(  's0' => '',
							's1' => '',
							's2' => '',
							's3' => '',
							's4' => '',	 
							'espacio' => 'Subtotal',
							's5' => $this->s1,
							's6' => $this->s2
						);		
		$this-> MultiRow($RowArray,false,1);
		$this->s1 = 0;
		$this->s2 = 0;
		$this->s3 = 0;
		$this->s4 = 0;
	}
	//revisarfinPagina pie
	function cerrarCuadroTotal(){
		$conf_par_tablewidths=array(7,15,20,30,20,20,20,20);			
		$this->tablealigns=array('R','R','R','R','R','R','R','R');		
		$this->tablenumbers=array(0,0,0,0,0,0,2,2);
		$this->tableborders=array('','','','','','','LRTB','LRTB');									
		$RowArray = array(
					't0' => '', 
					't1' => '',
					't2' => '',
					't3' => '',
					't4' => '',
					'espacio' => 'TOTAL: ',
					't5' => $this->t1,
					't6' => $this->t2
				);
		$this-> MultiRow($RowArray,false,1);	
	}
	//
	function Footer() {		
		$this->setY(-15);
		$ormargins = $this->getOriginalMargins();
		$this->SetTextColor(0, 0, 0);
		$line_width = 0.85 / $this->getScaleFactor();
		$this->SetLineStyle(array('width' => $line_width, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0, 'color' => array(0, 0, 0)));
		$ancho = round(($this->getPageWidth() - $ormargins['left'] - $ormargins['right']) / 3);
		$this->Ln(2);
		$cur_y = $this->GetY();
		$this->Cell($ancho, 0, '', '', 0, 'L');
		$pagenumtxt = 'Página'.' '.$this->getAliasNumPage().' de '.$this->getAliasNbPages();
		$this->Cell($ancho, 0, $pagenumtxt, '', 0, 'C');
		$this->Cell($ancho, 0, '', '', 0, 'R');
		$this->Ln();
		$fecha_rep = date("d-m-Y H:i:s");
		$this->Cell($ancho, 0, '', '', 0, 'L');
		$this->Ln($line_width);
	}
	//
	function cab() {
		$white = array('LTRB' =>array('width' => 0.3, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0, 'color' => array(255, 255, 255)));
		$black = array('T' =>array('width' => 0.3, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0, 'color' => array(0, 0, 0)));
		$this->Ln(3);
		$this->Image(dirname(__FILE__).'/../../lib/imagenes/logos/logo.jpg', 10,5,40,20);
		$this->ln(5);
		$this->SetFont('','B',12);		
		$this->Cell(0,5,"REPORTE CAJA CHICA",0,1,'C');					
		$this->Ln(3);
		
		$height = 2;
		$width1 = 5;
		$esp_width = 10;
		$width_c1= 30;
		$width_c2= 70;		
		
		$this->SetFont('', 'B',6);
		$this->SetFillColor(192,192,192, true);	
		$this->Cell($width1, $height, '', 0, 0, 'L', false, '', 0, false, 'T', 'C');
		$this->Cell($width_c1, $height, 'RAZON SOCIAL', 0, 0, 'L', false, '', 0, false, 'T', 'C');
		$this->SetFont('', '',6);				
		$this->Cell($width_c2, $height, $rz, 0, 0, 'L', true, '', 0, false, 'T', 'C');
		$this->Ln(3);
		
		$this->SetFont('', 'B',6);
		$this->SetFillColor(192,192,192, true);	
		$this->Cell($width1, $height, '', 0, 0, 'L', false, '', 0, false, 'T', 'C');
		$this->Cell($width_c1, $height, 'FUNCIONARIO', 0, 0, 'L', false, '', 0, false, 'T', 'C');
		$this->SetFont('', '',6);				
		$this->Cell($width_c2, $height, $fun, 0, 0, 'L', true, '', 0, false, 'T', 'C');
		$this->Ln(3);
		
		$this->SetFont('', 'B',6);
		$this->SetFillColor(192,192,192, true);	
		$this->Cell($width1, $height, '', 0, 0, 'L', false, '', 0, false, 'T', 'C');
		$this->Cell($width_c1, $height, 'FECHA', 0, 0, 'L', false, '', 0, false, 'T', 'C');
		$this->SetFont('', '',6);				
		$this->Cell($width_c2, $height, $fec, 0, 0, 'L', true, '', 0, false, 'T', 'C');
		$this->Ln(2);
		
		$this->Ln(6);
		$this->SetFont('','B',6);
		$this->generarCabecera();
	}		
}
?>