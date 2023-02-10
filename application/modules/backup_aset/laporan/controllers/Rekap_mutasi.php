<?php

if (!defined('BASEPATH')) exit('No direct script access allowed');

class Rekap_mutasi extends CI_Controller
{
  function __construct()
  {
    parent::__construct();
    $this->global_model->cek_hak_akses('19');
    $this->load->model('laporan_model');
    $this->load->model('rekap_mutasi_model');
    $this->load->helper('my_global');
  }

  public function index()
  {
    $data['css'] = array(
      "assets/bootstrap/bootstrap-datepicker-master/dist/css/bootstrap-datepicker.min.css",
      "assets/jquery/select2-4.0.6-rc.1/dist/css/select2.min.css",
      "assets/jquery/select2-bootstrap-theme-master/dist/select2-bootstrap.min.css",
      'assets/adminlte/plugins/iCheck/all.css',
      'assets/sweetalert/sweetalert.css',
      "assets/css/laporan.css",
    );
    $data['js'] = array(
      "assets/bootstrap/bootstrap-datepicker-master/dist/js/bootstrap-datepicker.min.js",
      "assets/bootstrap/bootstrap-datepicker-master/dist/locales/bootstrap-datepicker.id.min.js",
      "assets/jquery/select2-4.0.6-rc.1/dist/js/select2.min.js",
      'assets/adminlte/plugins/iCheck/icheck.min.js',
      'assets/sweetalert/dist/sweetalert.min.js',
      "assets/js/laporan.js",

    );

    // $data['kib'] = $this->global_model->kode_jenis;
    $data['pemilik'] = $this->global_model->get_pemilik();
    $data['pengguna'] = $this->global_model->get_pengguna();
    $data['intra_ekstra'] = array(
      '00' => 'Semua',
      '01' => 'Intrakomptable',
      '02' => 'Extrakomptable',
    );

    $session = $this->session->userdata('session');
    $lokasi_explode = $this->global_model->get_kode_lokasi_by_id($session->id_kode_lokasi);


    $data['pengguna_list'] = $this->global_model->get_pengguna();
    $data['pengguna'] = $this->global_model->get_view_pengguna($lokasi_explode->pengguna);

    if ($data['pengguna']) {
      $data['kuasa_pengguna_list'] = $this->laporan_model->get_kuasa_pengguna($data['pengguna']->id_pengguna);
      $data['kuasa_pengguna'] = $this->global_model->get_view_kuasa_pengguna($lokasi_explode->pengguna, $lokasi_explode->kuasa_pengguna);
      if ($data['kuasa_pengguna']) {
        $data['sub_kuasa_pengguna_list'] = $this->laporan_model->get_sub_kuasa_pengguna($data['kuasa_pengguna']->id_pengguna, $data['kuasa_pengguna']->id_kuasa_pengguna);
        $data['sub_kuasa_pengguna'] = $this->global_model->get_view_sub_kuasa_pengguna($lokasi_explode->pengguna, $lokasi_explode->kuasa_pengguna, $lokasi_explode->sub_kuasa_pengguna);
      }
    }

    $data['content'] = $this->load->view('rekap_mutasi/home', $data, true);
    $data['breadcrumb'] = array(
      array('label' => 'Laporan', 'url' => '#', 'icon' => 'fa fa-pie-chart', 'li_class' => ''),
      array('label' => 'Rekap Laporan Mutasi', 'url' => '#', 'icon' => '', 'li_class' => 'active'),
      // array('label' => 'Buat', 'url' => '#','icon'=>'', 'li_class'=>'active'),
    );
    $this->load->view('template', $data);
  }

  public function ndownload()
  {
    // $data['kode_jenis'] = $this->input->post('kode_jenis');
    $data['session'] = $this->session->userdata('session');
    $data['id_pemilik'] = $this->input->post('pemilik');

    $data['id_pengguna'] = $this->input->post('id_pengguna');
    $data['id_kuasa_pengguna'] = $this->input->post('id_kuasa_pengguna');
    $data['id_sub_kuasa_pengguna'] = $this->input->post('id_sub_kuasa_pengguna');
    $data['jenis_rekap'] = $this->input->post('jenis_rekap');
    $data['intra_ekstra'] = $this->input->post('intra_ekstra');

    $data['start_bulan'] = $this->input->post('start_bulan');
    $data['start_tahun'] = $this->input->post('start_tahun');;
    $data['last_bulan'] = $this->input->post('last_bulan');
    $data['last_tahun'] = $this->input->post('last_tahun');;

    $data['start_date'] =  $data['start_tahun'].'-'.$data['start_bulan'].'-01';
    $data['last_date']  = date("Y-m-t", strtotime($data['last_tahun'].'-'.$data['last_bulan'].'-01'));
    if($data['start_bulan'] == 0){
      $data['start_date'] = '';
    }else{
      $date1 = new DateTime($data['start_date']);
      $date2    = new DateTime($data['last_date']);

      if($date1 > $date2){
        $this->session->set_flashdata('message', 'Bulan Transaksi Tidak Valid');
          redirect(site_url('laporan/rekap_mutasi'));
      }
    }

    $data['intra_ekstra'] = $this->input->post('intra_ekstra');
    $data['format'] = $this->input->post('format');

    if ($data['jenis_rekap'] == 'objek') {
      $data['field_name'] = array(
        'no',  'jenis',  'objek',  'nama_barang',  'jumlah_awal',  'harga_awal', 'jumlah_min', 'harga_min', 'jumlah_plus', 'harga_plus', 'jumlah_akhir', 'harga_akhir',  'keterangan',
      );
      $data['label'] = array(
        'NO',  'JENIS',  'OBJEK',  'URAIAN', 'JUMLAH BARANG', 'JUMLAH HARGA (Rp.)', 'Keterangan',
      );
    } else if ($data['jenis_rekap'] == 'rincian_objek') {
      $data['field_name'] = array(
        'no',  'objek',  'rincian_objek',  'nama_barang',  'jumlah_awal',  'harga_awal', 'jumlah_min', 'harga_min', 'jumlah_plus', 'harga_plus', 'jumlah_akhir', 'harga_akhir',  'keterangan',
      );
      $data['label'] = array(
        'NO',  'OBJEK',  'RINCIAN OBJEK',  'URAIAN', 'JUMLAH BARANG', 'JUMLAH HARGA (Rp.)', 'Keterangan',
      );
    }
    // die($_SESSION['session']->id_kode_lokasi);
    // die(print_r($_SESSION['session']));
    // die($data['id_pengguna']);

    $id_kode_lokasi = null;
    if ($data['id_pengguna']) {
      if ($data['id_sub_kuasa_pengguna'] != '') {
        $id_kode_lokasi = $data['id_sub_kuasa_pengguna'];
        array_pop($data['field_name']);
        array_pop($data['label']);
      } else if ($data['id_kuasa_pengguna'] != '') {
        $id_kode_lokasi = $data['id_kuasa_pengguna'];
      } else if ($data['id_pengguna'] != '') {
        $id_kode_lokasi = $data['id_pengguna'];
      }
    } else {
      $id_kode_lokasi = '0'; // sekoota
    }


    $result_pemilik = $this->global_model->get_pemilik_by_id($data['id_pemilik']);

    //tertanda
    $tertanda = $this->laporan_model->get_tertanda($id_kode_lokasi);
    $data['tertanda'] = $tertanda->result();
    //die(json_encode($data['tertanda']));
    // $data['count_tertanda'] = $tertanda->num_rows();

    $data['des_pemilik'] = $result_pemilik->kode . ' - ' . $result_pemilik->nama;
    $result_lokasi = $this->laporan_model->get_lokasi_by_id($id_kode_lokasi);
    if($id_kode_lokasi != '0'){
      $kode_lokasi = $result_pemilik->kode . '.' . $result_lokasi->kode_lokasi;
      $data['nama_lokasi'] = $result_lokasi->instansi;
    }else{
      $kode_lokasi = '';
      $data['nama_lokasi'] = 'KOTA YOGYAKARTA';
    }
    $data['kode_lokasi'] = get_intra_extra(remove_star($kode_lokasi), $data['intra_ekstra']);

    $data['id_kode_lokasi'] = $id_kode_lokasi;

    $data['rekap_mutasi'] = $this->rekap_mutasi_model->get_all($data);
    if ($data['format'] == 'pdf'){
      $this->toPdf($data);
    }
    else if ($data['format'] == 'excel'){
      if($this->input->post('rekon') == '1'){
        $this->toExcelRekon($data);
      }else{
        $this->toExcel($data);
      }
    }
  }


  public function toPdf($data = null)
  {
    $this->load->library('PdfGenerator');

    $html = $this->load->view('rekap_mutasi/report_pdf', $data, true);
    $this->pdfgenerator->generate($html, 'Laporan Mutasi', true, 'folio', 'landscape');
    // $this->load->view('rekap_mutasi/report_pdf', $data);
  }

  public function toExcel($data = null)
  {
    // die(json_encode($data['rekap_mutasi']));
    $field_name = $data['field_name'];
    $label = $data['label'];
    $data['list_intra_ekstra'] = array(
      '00' => 'Semua',
      '01' => 'Intrakomptable',
      '02' => 'Extrakomptable',
    );
    $intra_extra = $data['list_intra_ekstra'][$data['intra_ekstra']];

    // $itemcount = count($label);
    $itemcount = 12;

    $alphabet = array();
    for ($na = 0; $na < $itemcount; $na++) {
      $alphabet[] = $this->generateAlphabet($na);
    }
    $last_alpabet = $alphabet[$itemcount - 1];

    $itemfield = "";
    foreach ($label as $key => $value) :
      $itemfield .= $value . ',';
    endforeach;
    $this->load->library("Excel/PHPExcel");

    // Create Objek phpExcel
    $objPHPExcel = new PHPExcel();

    $header = array(
      'alignment' => array(
        'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
        'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,
      ),
      'font' => array(
        'bold' => true,
        'color' => array('rgb' => '000000'),
        'name' => 'Verdana'
      )
    );
    $table_header = array(
      'alignment' => array(
        'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
        'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,
      ),
      'font' => array(
        'bold' => true,
      )

    );
    $link_style_array = array(
      'font' => array(
        'color' => array('rgb' => '0000FF'),
        'underline' => 'single',
      ),
    );

    $borderStyleArray = array(
      'borders' => array(
        'allborders' => array(
          'style' => PHPExcel_Style_Border::BORDER_THIN
        )
      )
    );

    $objPHPExcel->getActiveSheet()->mergeCells('A2:C2');
    $objPHPExcel->getActiveSheet()->mergeCells('A3:C3');
    $objPHPExcel->getActiveSheet()->mergeCells('A4:C4');

    $objPHPExcel->getActiveSheet()->mergeCells('F3:L3');
    $objPHPExcel->getActiveSheet()->getStyle('F3' . ':' . 'L3')
      ->getAlignment()
      ->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

    $objPHPExcel->setActiveSheetIndex(0)
      ->setCellValue('A2', 'SKPD/Unit Kerja')
      ->setCellValue('D2', ': ' . $data['nama_lokasi'])

      ->setCellValue('A3', 'KOTA')
      ->setCellValue('D3', ': Kota Yogyakarta')

      ->setCellValue('F3', 'Kode SKPD/Unit Kerja' . ' : ' . $data['kode_lokasi'])
      // ->setCellValue('G3', ': ' . $data['kode_lokasi'])

      ->setCellValue('A4', 'PROPINSI')
      ->setCellValue('D4', ': D.I Yogyakarta');


    $col1 = $itemcount / 2;
    $col2 = $col1;
    $col1 = $col1 - 1;

    $counter = 6;
    $objPHPExcel->getActiveSheet()->mergeCells('A' . $counter . ':' . $last_alpabet . $counter);
    $objPHPExcel->getActiveSheet()->getStyle('A' . $counter . ':' . $last_alpabet . $counter)
      ->applyFromArray($header)
      ->getFont()->setSize(9);
    $objPHPExcel->setActiveSheetIndex(0)
      ->setCellValue('A' . $counter, 'REKAPITULASI LAPORAN MUTASI');
    $counter = $counter + 1;
    $objPHPExcel->setActiveSheetIndex(0)
      ->setCellValue("A" . $counter, 'Filter : ' . strtoupper($intra_extra));
    $objPHPExcel->getActiveSheet()->mergeCells('D' . $counter . ':' . $last_alpabet . $counter);
    $objPHPExcel->getActiveSheet()->getStyle('D' . $counter . ':' . $last_alpabet . $counter)
      ->getAlignment()
      ->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
    $objPHPExcel->setActiveSheetIndex(0)
      ->setCellValue('D' . $counter, $data['des_pemilik']);


    $counter = $counter + 2;
    $start_row_table = $counter;
    /*    foreach ($alphabet as $key => $value) {
      $objPHPExcel->setActiveSheetIndex(0)->setCellValue($value . $counter, $label[$key]);
    }

    $objPHPExcel->getActiveSheet()->getStyle("A" . $counter . ":" . $last_alpabet . $counter)
      ->applyFromArray($table_header)
      ->getFont()->setSize(12);
*/

    $row_header1 = $counter + 2;
    $row_header2 = $counter + 1;
    $first_column = "A";
    $last_column = "L";
    // $row_header3 = $counter + 1;
    $objPHPExcel->getActiveSheet()->mergeCells('A' . $counter . ':A' . $row_header1);
    $objPHPExcel->getActiveSheet()->mergeCells('B' . $counter . ':B' . $row_header1);
    $objPHPExcel->getActiveSheet()->mergeCells('C' . $counter . ':C' . $row_header1);
    $objPHPExcel->getActiveSheet()->mergeCells('D' . $counter . ':E' . $row_header2);
    $objPHPExcel->getActiveSheet()->mergeCells('F' . $counter . ':I' . $counter);
    $objPHPExcel->getActiveSheet()->mergeCells('J' . $counter . ':K' . $row_header2);
    $objPHPExcel->getActiveSheet()->mergeCells('L' . $counter . ':L' . $row_header1);

    $objPHPExcel->setActiveSheetIndex(0)->setCellValue("A" . $counter, "No. Urt");
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue("B" . $counter, "OBJEK");
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue("C" . $counter, "URAIAN");
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue("D" . $counter, "Jumlah (Awal) Bulan ".bulan_indo($data['start_bulan']));
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue("F" . $counter, "MUTASI/PERUBAHAN");
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue("J" . $counter, "Jumlah (Akhir) Bulan ".bulan_indo($data['last_bulan']));
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue("L" . $counter, "Ket.");


    $counter = $counter + 1;
    $row_header1 = $counter + 2;
    $row_header2 = $counter + 1;
    // $objPHPExcel->getActiveSheet()->mergeCells('D' . $counter . ':D' . $row_header2);
    // $objPHPExcel->getActiveSheet()->mergeCells('E' . $counter . ':E' . $row_header2);
    $objPHPExcel->getActiveSheet()->mergeCells('F' . $counter . ':G' . $counter);
    $objPHPExcel->getActiveSheet()->mergeCells('H' . $counter . ':I' . $counter);


    $objPHPExcel->setActiveSheetIndex(0)->setCellValue("F" . $counter, "Berkurang");
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue("H" . $counter, "Bertambah");

    $counter = $counter + 1;
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue("D" . $counter, "Barang");
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue("E" . $counter, "Harga");
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue("F" . $counter, "Jumlah Barang");
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue("G" . $counter, "Jumlah Harga");
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue("H" . $counter, "Jumlah Barang");
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue("I" . $counter, "Jumlah Harga");
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue("J" . $counter, "Barang");
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue("K" . $counter, "Harga");
    // $objPHPExcel->setActiveSheetIndex(0)->setCellValue("M" . $counter, "");
    // $objPHPExcel->setActiveSheetIndex(0)->setCellValue("N" . $counter, "");
    // $objPHPExcel->setActiveSheetIndex(0)->setCellValue("O" . $counter, "");
    // $objPHPExcel->setActiveSheetIndex(0)->setCellValue("P" . $counter, "");

    $counter = $counter + 1;
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue("A" . $counter, "1");
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue("B" . $counter, "2");
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue("C" . $counter, "3");
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue("D" . $counter, "4");
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue("E" . $counter, "5");
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue("F" . $counter, "6");
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue("G" . $counter, "7");
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue("H" . $counter, "8");
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue("I" . $counter, "9");
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue("J" . $counter, "10");
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue("K" . $counter, "11");
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue("L" . $counter, "12.");
    // $objPHPExcel->setActiveSheetIndex(0)->setCellValue("M" . $counter, "");
    // $objPHPExcel->setActiveSheetIndex(0)->setCellValue("N" . $counter, "");
    // $objPHPExcel->setActiveSheetIndex(0)->setCellValue("O" . $counter, "");
    // $objPHPExcel->setActiveSheetIndex(0)->setCellValue("P" . $counter, "");

    $objPHPExcel->getActiveSheet()->getStyle("A" . $start_row_table . ":" . "L" . ($start_row_table + 3))
      ->applyFromArray($table_header)
      ->getFont()->setSize(12);
    $ex = $objPHPExcel->setActiveSheetIndex(0);
    $counter = $counter + 1;

    $t_jumlah_awal = 0;
    $t_harga_awal = 0;
    $t_jumlah_min = 0;
    $t_harga_min = 0;
    $t_jumlah_plus = 0;
    $t_harga_plus = 0;
    $t_jumlah_akhir = 0;
    $t_harga_akhir = 0;

    foreach ($data['rekap_mutasi'] as $value) {

      if($value['status'] == '1') :
        $t_jumlah_awal  += $value['jumlah_awal'];
        $t_harga_awal   += $value['harga_awal'];
        $t_jumlah_min   += $value['jumlah_min'];
        $t_harga_min    += $value['harga_min'];
        $t_jumlah_plus  += $value['jumlah_plus'];
        $t_harga_plus   += $value['harga_plus'];
        $t_jumlah_akhir += $value['jumlah_akhir'];
        $t_harga_akhir  += $value['harga_akhir'];
      endif;

      /*foreach ($alphabet as $key => $v_alphabet) {
        $ex->setCellValue($v_alphabet . $counter, $value[$field_name[$key]]);
      }*/
      $objPHPExcel->setActiveSheetIndex(0)->setCellValue("A" . $counter, $value['NO']);
      $objPHPExcel->setActiveSheetIndex(0)->setCellValue("B" . $counter, $value['objek']);
      $objPHPExcel->setActiveSheetIndex(0)->setCellValue("C" . $counter, $value['nama_barang']);
      $objPHPExcel->setActiveSheetIndex(0)->setCellValue("D" . $counter, $value['jumlah_awal']);
      $objPHPExcel->setActiveSheetIndex(0)->setCellValue("E" . $counter, $value['harga_awal']);
      $objPHPExcel->setActiveSheetIndex(0)->setCellValue("F" . $counter, $value['jumlah_min']);
      $objPHPExcel->setActiveSheetIndex(0)->setCellValue("G" . $counter, $value['harga_min']);
      $objPHPExcel->setActiveSheetIndex(0)->setCellValue("H" . $counter, $value['jumlah_plus']);
      $objPHPExcel->setActiveSheetIndex(0)->setCellValue("I" . $counter, $value['harga_plus']);
      $objPHPExcel->setActiveSheetIndex(0)->setCellValue("J" . $counter, $value['jumlah_akhir']);
      $objPHPExcel->setActiveSheetIndex(0)->setCellValue("K" . $counter, $value['harga_akhir']);
      $objPHPExcel->setActiveSheetIndex(0)->setCellValue("L" . $counter, "");

      $objPHPExcel->getActiveSheet()->getStyle('E'.$counter)->getNumberFormat()->setFormatCode('#,##0.00');
      $objPHPExcel->getActiveSheet()->getStyle('G'.$counter)->getNumberFormat()->setFormatCode('#,##0.00');
      $objPHPExcel->getActiveSheet()->getStyle('I'.$counter)->getNumberFormat()->setFormatCode('#,##0.00');
      $objPHPExcel->getActiveSheet()->getStyle('K'.$counter)->getNumberFormat()->setFormatCode('#,##0.00');














      //set font bold
      if ($data['jenis_rekap'] == 'objek') {
        if ($value['status'] == '1' or $value['status'] == '2') {
          $objPHPExcel->getActiveSheet()->getStyle('A' . $counter . ':' . $last_alpabet . $counter)
            ->getFont()->setBold(true);
        }
      } else if ($data['jenis_rekap'] == 'rincian_objek') {
        if ($value['status'] == '1' or $value['status'] == '2' or $value['status'] == '3') {
          $objPHPExcel->getActiveSheet()->getStyle('A' . $counter . ':' . $last_alpabet . $counter)
            ->getFont()->setBold(true);
        }
      }


      $counter = $counter + 1;
    }

    $objPHPExcel->setActiveSheetIndex(0)->setCellValue("A" . $counter, "");
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue("B" . $counter, "");
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue("C" . $counter, "JUMLAH ASET");
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue("D" . $counter, $t_jumlah_awal);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue("E" . $counter, $t_harga_awal);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue("F" . $counter, $t_jumlah_min);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue("G" . $counter, $t_harga_min);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue("H" . $counter, $t_jumlah_plus);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue("I" . $counter, $t_harga_plus);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue("J" . $counter, $t_jumlah_akhir);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue("K" . $counter, $t_harga_akhir);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue("L" . $counter, "");
    
    $objPHPExcel->getActiveSheet()->getStyle('E'.$counter)->getNumberFormat()->setFormatCode('#,##0.00');
    $objPHPExcel->getActiveSheet()->getStyle('G'.$counter)->getNumberFormat()->setFormatCode('#,##0.00');
    $objPHPExcel->getActiveSheet()->getStyle('I'.$counter)->getNumberFormat()->setFormatCode('#,##0.00');
    $objPHPExcel->getActiveSheet()->getStyle('K'.$counter)->getNumberFormat()->setFormatCode('#,##0.00');

    $objPHPExcel->getActiveSheet()->getStyle('A' . $counter . ':' . $last_alpabet . $counter)
            ->getFont()->setBold(true);

    $last_row = $counter;

    $objPHPExcel->getActiveSheet()
      ->getStyle($alphabet[1] . "8:" . $alphabet[2] . $last_row) // kolom B dan C
      ->getAlignment()
      ->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);

    $objPHPExcel->getActiveSheet()->getStyle($first_column . $start_row_table . ":" . $last_column . $last_row)
      ->applyFromArray($borderStyleArray);

    foreach ($alphabet as $columnID) {
      $objPHPExcel->getActiveSheet()->getColumnDimension($columnID)
        ->setAutoSize(true);
    }


    // foreach ($field_name as $key => $value) :
    //   if ($value == "jumlah_awal" or $value == "harga_awal" or $value == "jumlah_min" or $value == "harga_min" or $value == "jumlah_plus" or $value == "harga_plus" or $value == "jumlah_akhir" or $value == "harga_akhir") {
    //     $cell = $alphabet[$key] . "1:" . $alphabet[$key] . $counter;
    //     $objPHPExcel->getActiveSheet()->getStyle($cell)->getNumberFormat()->setFormatCode('#,##0');
    //   }
    // endforeach;

    $first_row = $counter + 2;
    $last_row = $counter + 8;
    $counter = $counter + 2;

    $selisih_bagi_3 = $itemcount / 3;
    $last_col1 = $selisih_bagi_3 - 1;

    $start_col2 = $last_col1 + 1;
    $last_col2 = ($selisih_bagi_3 * 2) - 1;

    $start_col3 = $last_col2 + 1;
    $last_col = $itemcount - 1;


    if (isset($data['tertanda'][0]) or isset($data['tertanda'][1]) or isset($data['tertanda'][2])) {
      $objPHPExcel->getActiveSheet()->getStyle($alphabet[0] . $first_row . ":" . $alphabet[$last_col] . $last_row)->applyFromArray($header)->getFont()->setSize(10);

      $objPHPExcel->getActiveSheet()->mergeCells($alphabet[0] . $counter . ':' . $alphabet[$last_col1] . $counter);
      $objPHPExcel->getActiveSheet()->mergeCells($alphabet[$start_col3] . $counter . ':' . $alphabet[$last_col] . $counter);
      $objPHPExcel->setActiveSheetIndex(0)->setCellValue($alphabet[0] . $counter, 'MENGETAHUI');
      $objPHPExcel->setActiveSheetIndex(0)->setCellValue($alphabet[$start_col3] . $counter, 'Yogyakarta, ' . tgl_indo(date('Y-m-d')));

      $counter = $counter + 1;
      $objPHPExcel->getActiveSheet()->mergeCells($alphabet[0] . $counter . ':' . $alphabet[$last_col1] . $counter);
      $objPHPExcel->getActiveSheet()->mergeCells($alphabet[$start_col2] . $counter . ':' . $alphabet[$last_col2] . $counter);
      $objPHPExcel->getActiveSheet()->mergeCells($alphabet[$start_col3] . $counter . ':' . $alphabet[$last_col] . $counter);
      if (isset($data['tertanda'][0]))
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue($alphabet[0] . $counter, $data['tertanda'][0]->jabatan);
      if (isset($data['tertanda'][1]))
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue($alphabet[$start_col2] . $counter, $data['tertanda'][1]->jabatan);
      if (isset($data['tertanda'][2]))
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue($alphabet[$start_col3] . $counter, $data['tertanda'][2]->jabatan);

      $counter = $counter + 4;
      $objPHPExcel->getActiveSheet()->mergeCells($alphabet[0] . $counter . ':' . $alphabet[$last_col1] . $counter);
      $objPHPExcel->getActiveSheet()->mergeCells($alphabet[$start_col2] . $counter . ':' . $alphabet[$last_col2] . $counter);
      $objPHPExcel->getActiveSheet()->mergeCells($alphabet[$start_col3] . $counter . ':' . $alphabet[$last_col] . $counter);
      if (isset($data['tertanda'][0]))
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue($alphabet[0] . $counter, '( ' . $data['tertanda'][0]->nama . ' )');
      if (isset($data['tertanda'][1]))
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue($alphabet[$start_col2] . $counter, '( ' . $data['tertanda'][1]->nama . ' )');
      if (isset($data['tertanda'][2]))
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue($alphabet[$start_col3] . $counter, '( ' . $data['tertanda'][2]->nama . ' )');

      $counter = $counter + 1;
      $objPHPExcel->getActiveSheet()->mergeCells($alphabet[0] . $counter . ':' . $alphabet[$last_col1] . $counter);
      $objPHPExcel->getActiveSheet()->mergeCells($alphabet[$start_col2] . $counter . ':' . $alphabet[$last_col2] . $counter);
      $objPHPExcel->getActiveSheet()->mergeCells($alphabet[$start_col3] . $counter . ':' . $alphabet[$last_col] . $counter);
      if (isset($data['tertanda'][0]))
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue($alphabet[0] . $counter, 'NIP. ' . $data['tertanda'][0]->nip);
      if (isset($data['tertanda'][1]))
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue($alphabet[$start_col2] . $counter, 'NIP. ' . $data['tertanda'][1]->nip);
      if (isset($data['tertanda'][2]))
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue($alphabet[$start_col3] . $counter, 'NIP. ' . $data['tertanda'][2]->nip);
    }


    $objPHPExcel->getProperties()->setCreator("KIB")
      ->setLastModifiedBy("KIB")
      ->setTitle("Export Data Rekap. Laporan Mutasi")
      ->setSubject("Export To Excel")
      ->setDescription("Doc for Office 2007 XLSX, generated by PHPExcel.")
      ->setKeywords("office 2007 openxml php")
      ->setCategory("PHPExcel");
    $objPHPExcel->getActiveSheet()->setTitle('Data Rekap. Laporan Mutasi');

    $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
    ob_end_clean(); // MENGHILANGKAN ERROR CANNOT OPEN
    header('Last-Modified:' . gmdate("D, d M Y H:i:s") . 'GMT');
    header('Chace-Control: no-store, no-cache, must-revalation');
    header('Chace-Control: post-check=0, pre-check=0', FALSE);
    header('Pragma: no-cache');
    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment;filename="Data Rekap. Laporan Mutasi ' . date('Y-m-d') . '.xlsx"');

    $objWriter->save('php://output');
    die();
  }
  

  public function toExcelRekon($data = null)
  {
    // die(json_encode($data['rekap_mutasi']));
    $field_name = $data['field_name'];
    $label = $data['label'];
    $data['list_intra_ekstra'] = array(
      '00' => 'Semua',
      '01' => 'Intrakomptable',
      '02' => 'Extrakomptable',
    );
    $intra_extra = $data['list_intra_ekstra'][$data['intra_ekstra']];

    // $itemcount = count($label);
    $itemcount = 12;

    $alphabet = array();
    for ($na = 0; $na < $itemcount; $na++) {
      $alphabet[] = $this->generateAlphabet($na);
    }
    $last_alpabet = $alphabet[$itemcount - 1];

    $itemfield = "";
    foreach ($label as $key => $value) :
      $itemfield .= $value . ',';
    endforeach;
    $this->load->library("Excel/PHPExcel");

    // Create Objek phpExcel
    $objPHPExcel = new PHPExcel();

    $header = array(
      'alignment' => array(
        'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
        'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,
      ),
      'font' => array(
        'bold' => true,
        'color' => array('rgb' => '000000'),
        'name' => 'Verdana'
      )
    );
    $table_header = array(
      'alignment' => array(
        'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
        'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,
      ),
      'font' => array(
        'bold' => true,
      )

    );
    $link_style_array = array(
      'font' => array(
        'color' => array('rgb' => '0000FF'),
        'underline' => 'single',
      ),
    );

    $borderStyleArray = array(
      'borders' => array(
        'allborders' => array(
          'style' => PHPExcel_Style_Border::BORDER_THIN
        )
      )
    );

    $objPHPExcel->getActiveSheet()->mergeCells('A1:C1');
    $objPHPExcel->getActiveSheet()->mergeCells('A2:C2');
    $objPHPExcel->getActiveSheet()->mergeCells('A3:C3');
    $objPHPExcel->getActiveSheet()->mergeCells('A5:C5');
    $objPHPExcel->getActiveSheet()->mergeCells('A6:C6');
    $objPHPExcel->getActiveSheet()->mergeCells('A7:C7');

    $objPHPExcel->getActiveSheet()->mergeCells('F6:L6');
    $objPHPExcel->getActiveSheet()->getStyle('F6' . ':' . 'L6')
      ->getAlignment()
      ->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

    $objPHPExcel->setActiveSheetIndex(0)
      ->setCellValue('A1', 'LAMPIRAN BERITA ACARA REKONSILIASI ASET TETAP DAN ASET LAINNYA')
      ->setCellValue('A2', 'Nomor :')
      ->setCellValue('A3', 'Tanggal : ')

      ->setCellValue('A5', 'SKPD/Unit Kerja')
      ->setCellValue('D5', ': ' . $data['nama_lokasi'])

      ->setCellValue('A6', 'KOTA')
      ->setCellValue('D6', ': Kota Yogyakarta')

      ->setCellValue('F6', 'Kode SKPD/Unit Kerja' . ' : ' . $data['kode_lokasi'])
      // ->setCellValue('G3', ': ' . $data['kode_lokasi'])

      ->setCellValue('A7', 'PROPINSI')
      ->setCellValue('D7', ': D.I Yogyakarta');


    $col1 = $itemcount / 2;
    $col2 = $col1;
    $col1 = $col1 - 1;

    $counter = 9;
    $objPHPExcel->getActiveSheet()->mergeCells('A' . $counter . ':' . $last_alpabet . $counter);
    $objPHPExcel->getActiveSheet()->getStyle('A' . $counter . ':' . $last_alpabet . $counter)
      ->applyFromArray($header)
      ->getFont()->setSize(9);
    $objPHPExcel->setActiveSheetIndex(0)
      ->setCellValue('A' . $counter, 'REKAPITULASI LAPORAN MUTASI');
    $counter = $counter + 1;
    $objPHPExcel->setActiveSheetIndex(0)
      ->setCellValue("A" . $counter, 'Filter : ' . strtoupper($intra_extra));
    $objPHPExcel->getActiveSheet()->mergeCells('D' . $counter . ':' . $last_alpabet . $counter);
    $objPHPExcel->getActiveSheet()->getStyle('D' . $counter . ':' . $last_alpabet . $counter)
      ->getAlignment()
      ->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
    $objPHPExcel->setActiveSheetIndex(0)
      ->setCellValue('D' . $counter, $data['des_pemilik']);


    $counter = $counter + 2;
    $start_row_table = $counter;
    /*    foreach ($alphabet as $key => $value) {
      $objPHPExcel->setActiveSheetIndex(0)->setCellValue($value . $counter, $label[$key]);
    }

    $objPHPExcel->getActiveSheet()->getStyle("A" . $counter . ":" . $last_alpabet . $counter)
      ->applyFromArray($table_header)
      ->getFont()->setSize(12);
*/

    $row_header1 = $counter + 2;
    $row_header2 = $counter + 1;
    $first_column = "A";
    $last_column = "L";
    // $row_header3 = $counter + 1;
    $objPHPExcel->getActiveSheet()->mergeCells('A' . $counter . ':A' . $row_header1);
    $objPHPExcel->getActiveSheet()->mergeCells('B' . $counter . ':B' . $row_header1);
    $objPHPExcel->getActiveSheet()->mergeCells('C' . $counter . ':C' . $row_header1);
    $objPHPExcel->getActiveSheet()->mergeCells('D' . $counter . ':E' . $row_header2);
    $objPHPExcel->getActiveSheet()->mergeCells('F' . $counter . ':I' . $counter);
    $objPHPExcel->getActiveSheet()->mergeCells('J' . $counter . ':K' . $row_header2);
    $objPHPExcel->getActiveSheet()->mergeCells('L' . $counter . ':L' . $row_header1);

    $objPHPExcel->setActiveSheetIndex(0)->setCellValue("A" . $counter, "No. Urt");
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue("B" . $counter, "OBJEK");
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue("C" . $counter, "URAIAN");
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue("D" . $counter, "Jumlah (Awal) Bulan ".bulan_indo($data['start_bulan']));
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue("F" . $counter, "MUTASI/PERUBAHAN");
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue("J" . $counter, "Jumlah (Akhir) Bulan ".bulan_indo($data['last_bulan']));
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue("L" . $counter, "Ket.");


    $counter = $counter + 1;
    $row_header1 = $counter + 2;
    $row_header2 = $counter + 1;
    // $objPHPExcel->getActiveSheet()->mergeCells('D' . $counter . ':D' . $row_header2);
    // $objPHPExcel->getActiveSheet()->mergeCells('E' . $counter . ':E' . $row_header2);
    $objPHPExcel->getActiveSheet()->mergeCells('F' . $counter . ':G' . $counter);
    $objPHPExcel->getActiveSheet()->mergeCells('H' . $counter . ':I' . $counter);


    $objPHPExcel->setActiveSheetIndex(0)->setCellValue("F" . $counter, "Berkurang");
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue("H" . $counter, "Bertambah");

    $counter = $counter + 1;
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue("D" . $counter, "Barang");
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue("E" . $counter, "Harga");
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue("F" . $counter, "Jumlah Barang");
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue("G" . $counter, "Jumlah Harga");
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue("H" . $counter, "Jumlah Barang");
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue("I" . $counter, "Jumlah Harga");
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue("J" . $counter, "Barang");
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue("K" . $counter, "Harga");
    // $objPHPExcel->setActiveSheetIndex(0)->setCellValue("M" . $counter, "");
    // $objPHPExcel->setActiveSheetIndex(0)->setCellValue("N" . $counter, "");
    // $objPHPExcel->setActiveSheetIndex(0)->setCellValue("O" . $counter, "");
    // $objPHPExcel->setActiveSheetIndex(0)->setCellValue("P" . $counter, "");

    $counter = $counter + 1;
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue("A" . $counter, "1");
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue("B" . $counter, "2");
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue("C" . $counter, "3");
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue("D" . $counter, "4");
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue("E" . $counter, "5");
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue("F" . $counter, "6");
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue("G" . $counter, "7");
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue("H" . $counter, "8");
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue("I" . $counter, "9");
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue("J" . $counter, "10");
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue("K" . $counter, "11");
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue("L" . $counter, "12.");
    // $objPHPExcel->setActiveSheetIndex(0)->setCellValue("M" . $counter, "");
    // $objPHPExcel->setActiveSheetIndex(0)->setCellValue("N" . $counter, "");
    // $objPHPExcel->setActiveSheetIndex(0)->setCellValue("O" . $counter, "");
    // $objPHPExcel->setActiveSheetIndex(0)->setCellValue("P" . $counter, "");

    $objPHPExcel->getActiveSheet()->getStyle("A" . $start_row_table . ":" . "L" . ($start_row_table + 3))
      ->applyFromArray($table_header)
      ->getFont()->setSize(12);
    $ex = $objPHPExcel->setActiveSheetIndex(0);
    $counter = $counter + 1;

    $t_jumlah_awal = 0;
    $t_harga_awal = 0;
    $t_jumlah_min = 0;
    $t_harga_min = 0;
    $t_jumlah_plus = 0;
    $t_harga_plus = 0;
    $t_jumlah_akhir = 0;
    $t_harga_akhir = 0;

    foreach ($data['rekap_mutasi'] as $value) {

      if($value['status'] == '1') :
        $t_jumlah_awal  += $value['jumlah_awal'];
        $t_harga_awal   += $value['harga_awal'];
        $t_jumlah_min   += $value['jumlah_min'];
        $t_harga_min    += $value['harga_min'];
        $t_jumlah_plus  += $value['jumlah_plus'];
        $t_harga_plus   += $value['harga_plus'];
        $t_jumlah_akhir += $value['jumlah_akhir'];
        $t_harga_akhir  += $value['harga_akhir'];
      endif;

      /*foreach ($alphabet as $key => $v_alphabet) {
        $ex->setCellValue($v_alphabet . $counter, $value[$field_name[$key]]);
      }*/
      $objPHPExcel->setActiveSheetIndex(0)->setCellValue("A" . $counter, $value['NO']);
      $objPHPExcel->setActiveSheetIndex(0)->setCellValue("B" . $counter, $value['objek']);
      $objPHPExcel->setActiveSheetIndex(0)->setCellValue("C" . $counter, $value['nama_barang']);
      $objPHPExcel->setActiveSheetIndex(0)->setCellValue("D" . $counter, $value['jumlah_awal']);
      $objPHPExcel->setActiveSheetIndex(0)->setCellValue("E" . $counter, $value['harga_awal']);
      $objPHPExcel->setActiveSheetIndex(0)->setCellValue("F" . $counter, $value['jumlah_min']);
      $objPHPExcel->setActiveSheetIndex(0)->setCellValue("G" . $counter, $value['harga_min']);
      $objPHPExcel->setActiveSheetIndex(0)->setCellValue("H" . $counter, $value['jumlah_plus']);
      $objPHPExcel->setActiveSheetIndex(0)->setCellValue("I" . $counter, $value['harga_plus']);
      $objPHPExcel->setActiveSheetIndex(0)->setCellValue("J" . $counter, $value['jumlah_akhir']);
      $objPHPExcel->setActiveSheetIndex(0)->setCellValue("K" . $counter, $value['harga_akhir']);
      $objPHPExcel->setActiveSheetIndex(0)->setCellValue("L" . $counter, "");

      $objPHPExcel->getActiveSheet()->getStyle('E'.$counter)->getNumberFormat()->setFormatCode('#,##0.00');
      $objPHPExcel->getActiveSheet()->getStyle('G'.$counter)->getNumberFormat()->setFormatCode('#,##0.00');
      $objPHPExcel->getActiveSheet()->getStyle('I'.$counter)->getNumberFormat()->setFormatCode('#,##0.00');
      $objPHPExcel->getActiveSheet()->getStyle('K'.$counter)->getNumberFormat()->setFormatCode('#,##0.00');














      //set font bold
      if ($data['jenis_rekap'] == 'objek') {
        if ($value['status'] == '1' or $value['status'] == '2') {
          $objPHPExcel->getActiveSheet()->getStyle('A' . $counter . ':' . $last_alpabet . $counter)
            ->getFont()->setBold(true);
        }
      } else if ($data['jenis_rekap'] == 'rincian_objek') {
        if ($value['status'] == '1' or $value['status'] == '2' or $value['status'] == '3') {
          $objPHPExcel->getActiveSheet()->getStyle('A' . $counter . ':' . $last_alpabet . $counter)
            ->getFont()->setBold(true);
        }
      }


      $counter = $counter + 1;
    }

    $objPHPExcel->setActiveSheetIndex(0)->setCellValue("A" . $counter, "");
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue("B" . $counter, "");
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue("C" . $counter, "JUMLAH ASET");
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue("D" . $counter, $t_jumlah_awal);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue("E" . $counter, $t_harga_awal);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue("F" . $counter, $t_jumlah_min);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue("G" . $counter, $t_harga_min);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue("H" . $counter, $t_jumlah_plus);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue("I" . $counter, $t_harga_plus);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue("J" . $counter, $t_jumlah_akhir);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue("K" . $counter, $t_harga_akhir);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue("L" . $counter, "");
    
    $objPHPExcel->getActiveSheet()->getStyle('E'.$counter)->getNumberFormat()->setFormatCode('#,##0.00');
    $objPHPExcel->getActiveSheet()->getStyle('G'.$counter)->getNumberFormat()->setFormatCode('#,##0.00');
    $objPHPExcel->getActiveSheet()->getStyle('I'.$counter)->getNumberFormat()->setFormatCode('#,##0.00');
    $objPHPExcel->getActiveSheet()->getStyle('K'.$counter)->getNumberFormat()->setFormatCode('#,##0.00');

    $objPHPExcel->getActiveSheet()->getStyle('A' . $counter . ':' . $last_alpabet . $counter)
            ->getFont()->setBold(true);

    $last_row = $counter;

    $objPHPExcel->getActiveSheet()
      ->getStyle($alphabet[1] . "8:" . $alphabet[2] . $last_row) // kolom B dan C
      ->getAlignment()
      ->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);

    $objPHPExcel->getActiveSheet()->getStyle($first_column . $start_row_table . ":" . $last_column . $last_row)
      ->applyFromArray($borderStyleArray);

    foreach ($alphabet as $columnID) {
      $objPHPExcel->getActiveSheet()->getColumnDimension($columnID)
        ->setAutoSize(true);
    }


    // foreach ($field_name as $key => $value) :
    //   if ($value == "jumlah_awal" or $value == "harga_awal" or $value == "jumlah_min" or $value == "harga_min" or $value == "jumlah_plus" or $value == "harga_plus" or $value == "jumlah_akhir" or $value == "harga_akhir") {
    //     $cell = $alphabet[$key] . "1:" . $alphabet[$key] . $counter;
    //     $objPHPExcel->getActiveSheet()->getStyle($cell)->getNumberFormat()->setFormatCode('#,##0');
    //   }
    // endforeach;

    $first_row = $counter + 2;
    $last_row = $counter + 8;
    $counter = $counter + 2;

    $selisih_bagi_3 = $itemcount / 3;
    $last_col1 = $selisih_bagi_3 - 1;

    $start_col2 = $last_col1 + 1;
    $last_col2 = ($selisih_bagi_3 * 2) - 1;

    $start_col3 = $last_col2 + 1;
    $last_col = $itemcount - 1;


    if (isset($data['tertanda'][0]) or isset($data['tertanda'][1]) or isset($data['tertanda'][2])) {
      $objPHPExcel->getActiveSheet()->getStyle($alphabet[0] . $first_row . ":" . $alphabet[$last_col] . $last_row)->applyFromArray($header)->getFont()->setSize(10);

      $objPHPExcel->getActiveSheet()->mergeCells($alphabet[0] . $counter . ':' . $alphabet[$last_col1] . $counter);
      $objPHPExcel->getActiveSheet()->mergeCells($alphabet[$start_col3] . $counter . ':' . $alphabet[$last_col] . $counter);
      $objPHPExcel->setActiveSheetIndex(0)->setCellValue($alphabet[0] . $counter, 'MENGETAHUI');
      $objPHPExcel->setActiveSheetIndex(0)->setCellValue($alphabet[$start_col3] . $counter, 'Yogyakarta, ' . tgl_indo(date('Y-m-d')));

      $counter = $counter + 1;
      $objPHPExcel->getActiveSheet()->mergeCells($alphabet[0] . $counter . ':' . $alphabet[$last_col1] . $counter);
      $objPHPExcel->getActiveSheet()->mergeCells($alphabet[$start_col2] . $counter . ':' . $alphabet[$last_col2] . $counter);
      $objPHPExcel->getActiveSheet()->mergeCells($alphabet[$start_col3] . $counter . ':' . $alphabet[$last_col] . $counter);
      if (isset($data['tertanda'][0]))
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue($alphabet[0] . $counter, $data['tertanda'][0]->jabatan);
      if (isset($data['tertanda'][1]))
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue($alphabet[$start_col2] . $counter, $data['tertanda'][1]->jabatan);
      if (isset($data['tertanda'][2]))
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue($alphabet[$start_col3] . $counter, $data['tertanda'][2]->jabatan);

      $counter = $counter + 4;
      $objPHPExcel->getActiveSheet()->mergeCells($alphabet[0] . $counter . ':' . $alphabet[$last_col1] . $counter);
      $objPHPExcel->getActiveSheet()->mergeCells($alphabet[$start_col2] . $counter . ':' . $alphabet[$last_col2] . $counter);
      $objPHPExcel->getActiveSheet()->mergeCells($alphabet[$start_col3] . $counter . ':' . $alphabet[$last_col] . $counter);
      if (isset($data['tertanda'][0]))
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue($alphabet[0] . $counter, '( ' . $data['tertanda'][0]->nama . ' )');
      if (isset($data['tertanda'][1]))
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue($alphabet[$start_col2] . $counter, '( ' . $data['tertanda'][1]->nama . ' )');
      if (isset($data['tertanda'][2]))
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue($alphabet[$start_col3] . $counter, '( ' . $data['tertanda'][2]->nama . ' )');

      $counter = $counter + 1;
      $objPHPExcel->getActiveSheet()->mergeCells($alphabet[0] . $counter . ':' . $alphabet[$last_col1] . $counter);
      $objPHPExcel->getActiveSheet()->mergeCells($alphabet[$start_col2] . $counter . ':' . $alphabet[$last_col2] . $counter);
      $objPHPExcel->getActiveSheet()->mergeCells($alphabet[$start_col3] . $counter . ':' . $alphabet[$last_col] . $counter);
      if (isset($data['tertanda'][0]))
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue($alphabet[0] . $counter, 'NIP. ' . $data['tertanda'][0]->nip);
      if (isset($data['tertanda'][1]))
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue($alphabet[$start_col2] . $counter, 'NIP. ' . $data['tertanda'][1]->nip);
      if (isset($data['tertanda'][2]))
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue($alphabet[$start_col3] . $counter, 'NIP. ' . $data['tertanda'][2]->nip);
    }


    $objPHPExcel->getProperties()->setCreator("KIB")
      ->setLastModifiedBy("KIB")
      ->setTitle("Export Data Rekap. Laporan Mutasi")
      ->setSubject("Export To Excel")
      ->setDescription("Doc for Office 2007 XLSX, generated by PHPExcel.")
      ->setKeywords("office 2007 openxml php")
      ->setCategory("PHPExcel");
    $objPHPExcel->getActiveSheet()->setTitle('Data Rekap. Laporan Mutasi');

    $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
    ob_end_clean(); // MENGHILANGKAN ERROR CANNOT OPEN
    header('Last-Modified:' . gmdate("D, d M Y H:i:s") . 'GMT');
    header('Chace-Control: no-store, no-cache, must-revalation');
    header('Chace-Control: post-check=0, pre-check=0', FALSE);
    header('Pragma: no-cache');
    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment;filename="Data Rekap. Laporan Mutasi ' . date('Y-m-d') . '.xlsx"');

    $objWriter->save('php://output');
    die();
  }

  function generateAlphabet($na)
  {
    $sa = "";
    while ($na >= 0) {
      $sa = chr($na % 26 + 65) . $sa;
      $na = floor($na / 26) - 1;
    }
    return $sa;
  }
}
