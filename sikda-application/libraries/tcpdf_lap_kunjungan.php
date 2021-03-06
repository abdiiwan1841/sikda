<?php
/**
 * @see http://www.fpdf.org
 * @see http://www.fpdf.org/en/doc/
 */
class TCPDF_Lap_Kunjungan extends TCPDF
{
	var $CI,
		$db,
		$pdf,
		$page_width = 297, $page_height = 210, //A4 Size in mm
		$font_family = 'Helvetica',

		$w_no    = 8,
		$w_kode  = 10,
		$w_unit  = 67,
		$w_bayar = 15,
		$w_total = 27,
		$h_colspan = 20,
		$h_bayar   = 15,
		$h_baru_lama  = 5,	//limit: h_colspan - h_bayar
		$h_gender     = 5,
		$text_tbl_size = 8,  //limit: h_colspan - h_bayar - h_baru_lama
		$header_height = 0,

		$h_content = 5.5,
		$cara_bayars,

		$nama_puskesmas, $kode_puskesmas, $from_date, $to_date, $nama_pimpinan;

	function __construct(){
		parent::__construct('L','mm','A4'); //@see http://www.fpdf.org/en/doc/fpdf.htm

		$this->CI = & get_instance();
		$this->db = $this->CI->load->database('sikda', TRUE);
	}
	function Header(){
	    $this->SetFont($this->font_family,'',18); //@see http://www.fpdf.org/en/doc/setfont.htm
	    $this->Ln(10);
	    $this->Cell(0,30,"Laporan Kunjungan Rawat Jalan",0,2,'C');

	    $text_height = 8;
	    $text_size   = 11;

		/**
		 * Print puskesmas
		 */
		$this->setFont($this->font_family,'B',$text_size);
		$this->Cell(50, $text_height, 'Puskesmas : ',0,0,'R');
		$this->Cell(5,$text_height,'');
		$this->setFont($this->font_family,'',$text_size);
		$this->Cell(35, $text_height, $this->kode_puskesmas,0,0,'L');
		$this->Cell(30, $text_height, $this->nama_puskesmas,0,1,'L');

		/**
		 * Print Periode
		 */
		$this->setFont($this->font_family,'B',$text_size);
		$this->Cell(50, $text_height, 'Periode : ',0,0,'R');
		$this->Cell(5,$text_height,'');
		$this->setFont($this->font_family,'',$text_size);
		$this->Cell(25, $text_height, $this->from_date,0,0,'L');
		$this->Cell(10, $text_height, 's/d',0,0,'L');
		$this->Cell(25, $text_height, $this->to_date,0,1,'L');
		$this->ln(2);

		/**
		 * Print Table Header
		 */
		$colspan_height = $this->h_colspan;
		$bayar_height   = $this->h_bayar;
		$header_top = $this->GetY(); //@see http://www.fpdf.org/en/doc/gety.htm

		$this->setFont($this->font_family,'',$this->text_tbl_size);
		$this->middleCell($this->w_no,   $colspan_height, 'No.'      , 1, 0, 'C');
		$this->middleCell($this->w_kode, $colspan_height, 'Kode'     , 1, 0, 'C');
		$this->middleCell($this->w_unit, $colspan_height, 'Nama Unit', 1, 0, 'C');
		$nama_unit_right = $this->getX();

		$right = $nama_unit_right;
		foreach($this->cara_bayars as $a){
			$this->setXY($right, $header_top);
			$this->middleCell($this->w_bayar, $bayar_height, strtolower($a->CUSTOMER), 1, 2, 'C');
			$this->setX($right);
			$this->setX($right);
			$this->genderCol();
			$this->genderCol(1);
			$right = $this->getX();
		}
		$this->setXY($right, $header_top);
		$this->middleCell($this->w_total, $bayar_height, 'Total', 1, 2, 'C');
		$this->setX($right);
		$this->genderCol();
		$this->genderCol(1);
		$this->middleCell($this->w_total - $this->w_bayar, $this->h_gender, 'JML', 1, 1, 'C');

		$this->header_height = $this->getY() + 0.1;
	    // Title
	    // Line break
	}
	//@see http://www.tcpdf.org/examples/example_003.phps
	public function Footer(){
        // Position at 15 mm from bottom
        $this->SetY(-15);
        $this->SetFont($this->font_family, '', 10);


        $y = $this->getY();
        $this->fullLine($y);
        $this->Cell(0, 10, 'Laporan Kunjungan Rawat Jalan', 0, false, 'L', 0, '', 0, false, 'T', 'M');
        $this->setY($y);
        $this->Cell(0, 10, 'Page '.$this->getAliasNumPage().' of '.$this->getAliasNbPages(), 0, false, 'C', 0, '', 0, false, 'T', 'M');
        $this->setY($y);
        $this->Cell(0, 10, Date('d/m/Y H:i A'), 0, false, 'R', 0, '', 0, false, 'T', 'M');
 	}

 	public function Body($units, $from, $to, $pid, $kbp=false){

 		$jumlah = array();

 		$condition = "pel.KD_PUSKESMAS = '$pid'";
 		if($kbp){
 			$condition = "SUBSTR(pel.KD_PUSKESMAS,2,4) = '$pid'";
 		}

		$h = $this->h_content;
		$w = $this->w_bayar/2;
		$this->setFont($this->font_family,'',$this->text_tbl_size);
		$this->setCellPaddings(2); //@see http://www.tcpdf.org/doc/code/classTCPDF.html#aba22c5159414bb96cadfa66efd89bc7c
		foreach($units as $index=>$u){
			$total_pria = 0;
			$total_wanita = 0;
			$this->Cell($this->w_no,   $h, $index+1    , 1, 0, 'L');
			$this->Cell($this->w_kode, $h, $u->KD_UNIT , 1, 0, 'L');
			$this->Cell($this->w_unit, $h, $u->UNIT    , 1, 0, 'L');
			foreach($this->cara_bayars as $b){

				$query = "SELECT 
							( 		
									SELECT COUNT(pel.KD_PASIEN) AS KUNJUNGAN_PRIA
									FROM pelayanan pel
									INNER JOIN pasien p ON pel.KD_PASIEN=p.KD_PASIEN AND p.KD_JENIS_KELAMIN='L'
									WHERE (pel.TGL_PELAYANAN BETWEEN '$from' AND '$to')
									AND $condition
									AND pel.KD_UNIT = '$u->KD_UNIT'
									AND pel.JENIS_PASIEN = '$b->KD_CUSTOMER'
							) AS KUNJUNGAN_PRIA,
							( 		
									SELECT COUNT(pel.KD_PASIEN) AS KUNJUNGAN_WANITA
									FROM pelayanan pel
									INNER JOIN pasien p ON pel.KD_PASIEN=p.KD_PASIEN AND p.KD_JENIS_KELAMIN='P'
									WHERE (pel.TGL_PELAYANAN BETWEEN '$from' AND '$to')
									AND $condition
									AND pel.KD_UNIT = '$u->KD_UNIT'
									AND pel.JENIS_PASIEN = '$b->KD_CUSTOMER'
							) AS KUNJUNGAN_WANITA";

				$result = $this->db->query($query)->row();
				$kunj_pria = 0;
				$kunj_wanita = 0;
				if(!empty($result)){
					if(!empty($result->KUNJUNGAN_PRIA)) $kunj_pria = $result->KUNJUNGAN_PRIA;
					if(!empty($result->KUNJUNGAN_WANITA)) $kunj_wanita = $result->KUNJUNGAN_WANITA;
				}
				$this->Cell($w, $h, $kunj_pria, 1, 0, 'C');
				$this->Cell($w, $h, $kunj_wanita, 1, 0, 'C');

				if(!isset($jumlah[$b->KD_CUSTOMER])){
					$jumlah[$b->KD_CUSTOMER] = array(
							'L'=>$kunj_pria,
							'P'=>$kunj_wanita
						);
				}else{
					$jumlah[$b->KD_CUSTOMER]['L']+=$kunj_pria;
					$jumlah[$b->KD_CUSTOMER]['P']+=$kunj_wanita;
				}

				$total_pria += $kunj_pria;
				$total_wanita += $kunj_wanita;
			}
			$this->Cell($w, $h, $total_pria, 1, 0, 'C');
			$this->Cell($w, $h, $total_wanita, 1, 0, 'C');
			$this->Cell($this->w_total - $this->w_bayar, $h, $total_pria+$total_wanita, 1, 1, 'C');
		}


		$h_jumlah = $h+3;
		$text_jmlh_size = $this->text_tbl_size + 1;
		$this->setCellPaddings(2, '', 5);
		$this->setFont($this->font_family, 'B', $text_jmlh_size);
		$this->Cell($this->w_no + $this->w_kode + $this->w_unit, $h_jumlah, 'Jumlah', 1, 0, 'R');
		$this->setCellPadding(0);
		$this->setFont($this->font_family, '', $text_jmlh_size);

		$total_seluruh_pria = 0;
		$total_seluruh_wanita = 0;
		foreach($jumlah as $j){
			$this->Cell($w, $h_jumlah, $j['L'], 1, 0, 'C');
			$this->Cell($w, $h_jumlah, $j['P'], 1, 0, 'C');
			$total_seluruh_pria += $j['L'];
			$total_seluruh_wanita += $j['P'];
		}
			$this->Cell($w, $h_jumlah, $total_seluruh_pria, 1, 0, 'C');
			$this->Cell($w, $h_jumlah, $total_seluruh_wanita, 1, 0, 'C');
		$this->Cell($this->w_total - $this->w_bayar, $h_jumlah, $total_seluruh_pria+$total_seluruh_wanita, 1, 1, 'C');
 	}

	//@see resep_pdf/cetak_resep
	function publish($from, $to, $pid, $jenis){


		if($jenis=='rpt_hrj'){
			$this->kode_puskesmas = $pid;
			$this->nama_puskesmas = $this->CI->session->userdata('puskesmas');
			$this->from_date = Date('d-m-Y',strtotime($from));
			$this->to_date = Date('d-m-Y',strtotime($to));
			$where = "(TGL_PELAYANAN BETWEEN '$from' AND '$to') AND p.KD_PUSKESMAS = '$pid'";
			$kbp=false;
		}else{
			$this->kode_puskesmas = $pid;
			$this->nama_puskesmas = $this->CI->session->userdata('nama_kabupaten');
			$this->from_date = Date('d-m-Y',strtotime($from));
			$this->to_date = Date('d-m-Y',strtotime($to));
			$where = "(TGL_PELAYANAN BETWEEN '$from' AND '$to') AND SUBSTR(p.KD_PUSKESMAS,2,4) = '$pid'";
			$kbp=true;
		}


		$units = $this->db->select('p.KD_UNIT, u.UNIT')->from('pelayanan p')
				->join('mst_unit u','u.KD_UNIT=p.KD_UNIT')
				->where($where,NULL,FALSE)->group_by('p.KD_UNIT')->order_by('p.KD_UNIT asc')->get()->result();
		if(empty($units)) die('Tidak ada data');

		$this->cara_bayars =  $this->db->select()->from('mst_kel_pasien')->get()->result();

		$this->setTitle('Laporan Kunjungan 05/2014');
		$this->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

		$this->AddPage();
		$this->setTopMargin($this->header_height);

		$this->Body($units, $from, $to, $pid, $kbp);

		// if(!$kbp){
		// 	$this->Ln(10);
		// 	$this->Cell(0,10, 'Kepala Puskesmas');
		// }

		$this->Output();
	}

	// print middle aligned column
	private function middleCell($w, $h, $txt, $border, $ln, $h_align){
		$this->MultiCell($w, $h, $txt, $border, $h_align, false, $ln, '', '', true, 0, false, true, $h, 'M');
	}

	// print column baru or lama
	private function baruLamaCol($lama=false, $ln=0){
		if($lama) $txt = 'Lama';
		else $txt = 'Baru';

		$this->Cell($this->w_bayar/2, $this->h_baru_lama, $txt, 1, $ln, 'C');
	}
	//print column gender L or P
	private function genderCol($p=false, $ln=0){
		if($p) $txt = 'P';
		else $txt = 'L';

		$this->Cell($this->w_bayar/2, $this->h_gender, $txt, 1, $ln, 'C');
	}

	//for line break
	private function fullLine($y){
	    $this->Line(10,$y,$this->page_width-10,$y);
	}
}