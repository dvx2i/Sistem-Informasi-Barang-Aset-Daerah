<?php

if (!defined('BASEPATH')) exit('No direct script access allowed');

class Rekap_mutasi_c extends MX_Controller
{
  function __construct()
  {
    parent::__construct();
    $this->global_model->cek_hak_akses('49');
    $this->load->model('laporan_model');
    $this->load->model('rekap_mutasi_aset_model');
    $this->load->helper('my_global');
    $this->load->library('Global_library');
    // if($this->session->userdata('session')->id_upik != 'JSS-A0149'){
    //   redirect(site_url('global_controller/maintenance'));
    // }
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

    $data['kelompok_list'] = $this->global_model->get_kode_kelompok();
    $data['kib'] = null;
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

    $data['breadcrumb'] = array(
      array('label' => 'Lampiran LKPD', 'url' => '#', 'icon' => 'fa fa-pie-chart', 'li_class' => ''),
      array('label' => 'Rekap Mutasi Gedung dan Bangunan', 'url' => '#', 'icon' => '', 'li_class' => 'active'),
      // array('label' => 'Buat', 'url' => '#','icon'=>'', 'li_class'=>'active'),
    );
    $data['url'] = base_url('laporan/rekap_mutasi_c/ndownload');
    $data['content'] = $this->load->view('/rekap_mutasi_aset/home', $data, true);
    $this->load->view('template', $data);
  }

  public function ndownload()
  {
    $data['kode_jenis'] = '03';
    $data['kode_kelompok'] = '3';
    $data['jenis'] = $this->global_model->kode_jenis[$data['kode_jenis']];
    $data['session'] = $this->session->userdata('session');
    $data['id_pemilik'] = $this->input->post('pemilik');
    
    $data['list_intra_ekstra'] = array(
      '00' => 'Semua',
      '01' => 'Intrakomptable',
      '02' => 'Extrakomptable',
    );

    $data['id_pengguna'] = $this->input->post('id_pengguna');
    $data['id_kuasa_pengguna'] = $this->input->post('id_kuasa_pengguna');
    $data['id_sub_kuasa_pengguna'] = $this->input->post('id_sub_kuasa_pengguna');
    $data['jenis_rekap'] = $this->input->post('jenis_rekap');
    $data['intra_ekstra'] = $this->input->post('intra_ekstra');
    

    $data['start_date'] = date('Y-m-d', tgl_inter("1 " . $this->input->post('bulan_tahun')));
    $data['bulan'] = date('m', tgl_inter("1 " . $this->input->post('bulan_tahun')));
    $data['tahun'] = date('Y', tgl_inter("1 " . $this->input->post('bulan_tahun')));
    $data['last_date'] = date('Y-m-d', tgl_inter("31 " . $this->input->post('last_bulan_tahun')));

    $data['intra_ekstra'] = $this->input->post('intra_ekstra');
    $data['format'] = $this->input->post('format');
    
    $lokasi_explode = $this->global_model->get_kode_lokasi_by_id($data['id_pengguna']);
    $data['pengguna'] = $data['id_pengguna'] != '' ? $lokasi_explode->pengguna : '';
    /*
      $data['field_name'] = array(
        'NO',	'jenis',	'objek',	'nama_barang',	'total_jumlah',	'total_harga',	'keterangan',
      );

      $data['label'] = array(
        'NO',	'JENIS',	'OBJEK',	'URAIAN', 'JUMLAH BARANG','JUMLAH HARGA (Rp.)', 'Keterangan',
      );
*/

    $id_kode_lokasi = null;
    $data['per'] = 'PER SKPD ';
    if ($data['id_kuasa_pengguna'] != '') {
      $id_kode_lokasi = $data['id_kuasa_pengguna'];
      $data['per'] = 'PER LOKASI ';
    } else if ($data['id_pengguna'] == '') {
      $id_kode_lokasi = $data['id_pengguna'];
      $data['per'] = 'PER SKPD ';
    }else if ($data['id_pengguna'] != '') {
      $id_kode_lokasi = $data['id_pengguna'];
      $data['per'] = 'PER SKPD ';
    }

    $result_pemilik = $this->global_model->get_pemilik_by_id($data['id_pemilik']);

    //tertanda
    $tertanda = $this->laporan_model->get_tertanda($id_kode_lokasi);
    $data['tertanda'] = $tertanda->result();
    //die(json_encode($data['tertanda']));
    // $data['count_tertanda'] = $tertanda->num_rows();

    // $data['des_jabatan'] = array(
    //                         'kepala_skpd' => 'KEPALA SKPD',
    //                         'kepala_unit_kerja' => 'KEPALA UNIT KERJA',
    //                         'pejabat_penata_usaha_barang' => 'PEJABAT PENATA USAHA BARANG',
    //                         'pengurus_barang' => 'PENGURUS BARANG',
    //                         'pengurus_barang_pembantu' => 'PENGURUS BARANG PEMBANTU',
    //                       );

    $data['des_pemilik'] = $result_pemilik->kode . ' - ' . $result_pemilik->nama;
    $result_lokasi = $this->laporan_model->get_lokasi_by_id($id_kode_lokasi);

    $kode_lokasi = $data['id_pengguna'] != '' ? $result_pemilik->kode . '.' . $result_lokasi->kode_lokasi : '2';
    $data['kode_lokasi'] = $data['id_pengguna'] != '' ? get_intra_extra(remove_star($kode_lokasi), $data['intra_ekstra']) : '';
    $data['nama_lokasi'] = $data['id_pengguna'] != '' ? $result_lokasi->instansi : '';

    $data['id_kode_lokasi'] = $id_kode_lokasi;

    // die($this->input->post('rekon'));
    $data['mutasi'] = $this->rekap_mutasi_aset_model->get_all($data);
    
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

  
  public function toExcel($data = null)
  {
    
    $intra_extra = $data['list_intra_ekstra'][$data['intra_ekstra']];
    $jumlah_field = 40;

    $alphabet = array();
    for ($na = 0; $na < $jumlah_field; $na++) {
      $alphabet[] = $this->generateAlphabet($na);
    }
    $last_alpabet = $alphabet[$jumlah_field - 1];


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


    $counter = 2;
    $objPHPExcel->getActiveSheet()->mergeCells('A' . $counter . ':' . $last_alpabet . $counter);
    $objPHPExcel->getActiveSheet()->getStyle('A' . $counter . ':' . $last_alpabet . $counter)
      ->applyFromArray($header)
      ->getFont()->setSize(9);
    $objPHPExcel->setActiveSheetIndex(0)
      ->setCellValue('A' . $counter, 'MUTASI ASET '.strtoupper($data['jenis']['nama']).' '.$data['per'].' TAHUN '.$data['tahun']);


    $objPHPExcel->getActiveSheet()->mergeCells('A4:C4');
    $objPHPExcel->getActiveSheet()->mergeCells('A5:C5');
    $objPHPExcel->getActiveSheet()->mergeCells('A6:C6');

    $objPHPExcel->setActiveSheetIndex(0)
      ->setCellValue('A4', 'SKPD')
      ->setCellValue('D4', ': ' . $this->getParentLokasi($data['kode_lokasi']) . $data['nama_lokasi'])

      ->setCellValue('A5', 'KABUPATEN/KOTA')
      ->setCellValue('D5', ': Kota Yogyakarta')

      ->setCellValue('F5', 'NO. KODE LOKASI')
      ->setCellValue('G5', ': ' . $data['kode_lokasi'])

      ->setCellValue('A6', 'PROVINSI')
      ->setCellValue('D6', ': D.I Yogyakarta')
      
      ->setCellValue('A7', 'Filter : '.strtoupper($intra_extra));
    $counter = 7;
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue($last_alpabet . $counter, $data['des_pemilik']);
    $objPHPExcel->getActiveSheet()
      ->getStyle($last_alpabet . $counter)
      ->getAlignment()
      ->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);


    $counter = $counter + 1;
    $first_row_header = $counter;
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A' . $counter, 'NOMOR');
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('B' . $counter, 'NAMA SKPD');
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('C' . $counter, 'SALDO AWAL');
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('D' . $counter, 'PENAMBAHAN');
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('AH' . $counter, 'PENGURANGAN');
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('AN' . $counter, 'SALDO AKHIR');
    // if (in_array('instansi', $data['field_name']) )
    //   $objPHPExcel->setActiveSheetIndex(0)->setCellValue('P'.$counter, 'Unit Kerja');

    $objPHPExcel->getActiveSheet()->mergeCells('D' . $counter . ':AG' . $counter);
    $objPHPExcel->getActiveSheet()->mergeCells('AH' . $counter . ':AM' . $counter);

    $counter = $counter + 1;
    $last_row_header = 11;
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('D' . $counter, 'PENAMBAHAN BARU');
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('R' . $counter, 'PENAMBAHAN NILAI');
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('AF' . $counter, 'MUTASI MASUK');
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('AG' . $counter, 'REKLASIFIKASI');
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('AH' . $counter, 'PENGHAPUSAN');
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('AK' . $counter, 'PENGURANGAN NILAI');
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('AL' . $counter, 'MUTASI KELUAR');
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('AM' . $counter, 'REKLASIFIKASI');

    $objPHPExcel->getActiveSheet()->mergeCells('D' . $counter . ':Q' . $counter);
    $objPHPExcel->getActiveSheet()->mergeCells('R' . $counter . ':AE' . $counter);
    $objPHPExcel->getActiveSheet()->mergeCells('AH' . $counter . ':AJ' . $counter);

    
    $counter = $counter + 1;
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('D' . $counter, 'BM');
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('I' . $counter, 'Barjas');
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('J' . $counter, 'Belanja Tidak Terduga');
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('K' . $counter, 'Hibah');
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('P' . $counter, 'Lain-lain');
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('R' . $counter, 'BM');
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('W' . $counter, 'Barjas');
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('X' . $counter, 'Belanja Tidak Terduga');
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('Y' . $counter, 'Hibah');
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('AD' . $counter, 'Lain-lain');
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('AH' . $counter, 'Penghapusan');
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('AI' . $counter, 'Koreksi Catat');

    $objPHPExcel->getActiveSheet()->mergeCells('AI' . $counter . ':AJ' . $counter);
    $objPHPExcel->getActiveSheet()->mergeCells('A8:A11');
    $objPHPExcel->getActiveSheet()->mergeCells('B8:B11');
    $objPHPExcel->getActiveSheet()->mergeCells('C8:C11');
    $objPHPExcel->getActiveSheet()->mergeCells('D10:H10');
    $objPHPExcel->getActiveSheet()->mergeCells('I10:I11');
    $objPHPExcel->getActiveSheet()->mergeCells('J10:J11');
    $objPHPExcel->getActiveSheet()->mergeCells('K10:O10');
    $objPHPExcel->getActiveSheet()->mergeCells('P10:Q10');
    $objPHPExcel->getActiveSheet()->mergeCells('R10:V10');
    $objPHPExcel->getActiveSheet()->mergeCells('W10:W11');
    $objPHPExcel->getActiveSheet()->mergeCells('X10:X11');
    $objPHPExcel->getActiveSheet()->mergeCells('Y10:AC10');
    $objPHPExcel->getActiveSheet()->mergeCells('AD10:AE10');
    $objPHPExcel->getActiveSheet()->mergeCells('AH10:AH11');
    $objPHPExcel->getActiveSheet()->mergeCells('AF9:AF11');
    $objPHPExcel->getActiveSheet()->mergeCells('AG9:AG11');
    $objPHPExcel->getActiveSheet()->mergeCells('AK9:AK11');
    $objPHPExcel->getActiveSheet()->mergeCells('AL9:AL11');
    $objPHPExcel->getActiveSheet()->mergeCells('AM9:AM11');
    $objPHPExcel->getActiveSheet()->mergeCells('AN8:AN11');

    $counter = $counter + 1;
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('D' . $counter, 'BM KIB A');
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('E' . $counter, 'BM KIB B');
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('F' . $counter, 'BM KIB C');
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('G' . $counter, 'BM KIB D');
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('H' . $counter, 'BM KIB E');
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('K' . $counter, 'Pemerintah Pusat');
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('L' . $counter, 'Pemerintah Daerah');
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('M' . $counter, 'Lembaga Dalam Negeri');
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('N' . $counter, 'Lembaga Luar Negeri');
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('O' . $counter, 'Sumbangan Pihak Ketiga');
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('P' . $counter, 'Urai Catat');
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('Q' . $counter, 'Kurang Catat');
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('R' . $counter, 'BM A');
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('S' . $counter, 'BM B');
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('T' . $counter, 'BM C');
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('U' . $counter, 'BM D');
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('V' . $counter, 'BM E');
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('Y' . $counter, 'Pemerintah Pusat');
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('Z' . $counter, 'Pemerintah Daerah');
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('AA' . $counter, 'Lembaga Dalam Negeri');
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('AB' . $counter, 'Lembaga Luar Negeri');
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('AC' . $counter, 'Sumbangan Pihak Ketiga');
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('AD' . $counter, 'Kurang Catat');
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('AE' . $counter, 'Lebih Catat');
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('AI' . $counter, 'Urai Catat Catatan Lama');
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('AJ' . $counter, 'Lebih Catat');

    $objPHPExcel->getActiveSheet()->getStyle("A" . $first_row_header . ":AN" . $last_row_header)
      ->applyFromArray($table_header)
      ->getFont()->setSize(12);
      $objPHPExcel->getActiveSheet()->getStyle("A8:AN11")
      ->getAlignment()->setWrapText(true);
    //end header

    // ===============================

    $ex = $objPHPExcel->setActiveSheetIndex(0);
    // $counter = $counter+1;

    // $mutasi = $this->rekap_mutasi_kib_model->get_all($data);

    
    $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(7);
    $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(60);
    $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(20);
    $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(20);
    $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(20);
    $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(20);
    $objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(20);
    $objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(20);
    $objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(20);
    $objPHPExcel->getActiveSheet()->getColumnDimension('J')->setWidth(20);
    $objPHPExcel->getActiveSheet()->getColumnDimension('K')->setWidth(20);
    $objPHPExcel->getActiveSheet()->getColumnDimension('L')->setWidth(20);
    $objPHPExcel->getActiveSheet()->getColumnDimension('M')->setWidth(20);
    $objPHPExcel->getActiveSheet()->getColumnDimension('N')->setWidth(20);
    $objPHPExcel->getActiveSheet()->getColumnDimension('O')->setWidth(20);
    $objPHPExcel->getActiveSheet()->getColumnDimension('P')->setWidth(20);
    $objPHPExcel->getActiveSheet()->getColumnDimension('Q')->setWidth(20);
    $objPHPExcel->getActiveSheet()->getColumnDimension('R')->setWidth(20);
    $objPHPExcel->getActiveSheet()->getColumnDimension('S')->setWidth(20);
    $objPHPExcel->getActiveSheet()->getColumnDimension('T')->setWidth(20);
    $objPHPExcel->getActiveSheet()->getColumnDimension('U')->setWidth(20);
    $objPHPExcel->getActiveSheet()->getColumnDimension('V')->setWidth(20);
    $objPHPExcel->getActiveSheet()->getColumnDimension('W')->setWidth(20);
    $objPHPExcel->getActiveSheet()->getColumnDimension('X')->setWidth(20);
    $objPHPExcel->getActiveSheet()->getColumnDimension('Y')->setWidth(20);
    $objPHPExcel->getActiveSheet()->getColumnDimension('Z')->setWidth(20);
    $objPHPExcel->getActiveSheet()->getColumnDimension('AA')->setWidth(20);
    $objPHPExcel->getActiveSheet()->getColumnDimension('AB')->setWidth(20);
    $objPHPExcel->getActiveSheet()->getColumnDimension('AC')->setWidth(20);
    $objPHPExcel->getActiveSheet()->getColumnDimension('AD')->setWidth(20);
    $objPHPExcel->getActiveSheet()->getColumnDimension('AE')->setWidth(20);
    $objPHPExcel->getActiveSheet()->getColumnDimension('AF')->setWidth(20);
    $objPHPExcel->getActiveSheet()->getColumnDimension('AG')->setWidth(20);
    $objPHPExcel->getActiveSheet()->getColumnDimension('AH')->setWidth(20);
    $objPHPExcel->getActiveSheet()->getColumnDimension('AI')->setWidth(20);
    $objPHPExcel->getActiveSheet()->getColumnDimension('AJ')->setWidth(20);
    $objPHPExcel->getActiveSheet()->getColumnDimension('AK')->setWidth(20);
    $objPHPExcel->getActiveSheet()->getColumnDimension('AL')->setWidth(20);
    $objPHPExcel->getActiveSheet()->getColumnDimension('AM')->setWidth(20);
    $objPHPExcel->getActiveSheet()->getColumnDimension('AN')->setWidth(20);
    // foreach ($alphabet as $columnID) {
    //   $objPHPExcel->getActiveSheet()->getColumnDimension($columnID)
    //     ->setAutoSize(true);
    // }

    // die(json_encode($data['mutasi']));
    $urut = 1;
    
    foreach ($data['mutasi'] as $key => $value1) :
      
      $counter = $counter + 1;
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A' . $counter, $urut++);
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('B' . $counter, $value1['instansi']);
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('C' . $counter, $value1['saldo_awal']);

        $objPHPExcel->setActiveSheetIndex(0)->setCellValueExplicit('D' . $counter, $value1['entri_bm_a'], PHPExcel_Cell_DataType::TYPE_NUMERIC);
        $objPHPExcel->setActiveSheetIndex(0)->setCellValueExplicit('E' . $counter, $value1['entri_bm_b'], PHPExcel_Cell_DataType::TYPE_NUMERIC);
        $objPHPExcel->setActiveSheetIndex(0)->setCellValueExplicit('F' . $counter, $value1['entri_bm_c'], PHPExcel_Cell_DataType::TYPE_NUMERIC);
        $objPHPExcel->setActiveSheetIndex(0)->setCellValueExplicit('G' . $counter, $value1['entri_bm_d'], PHPExcel_Cell_DataType::TYPE_NUMERIC);
        $objPHPExcel->setActiveSheetIndex(0)->setCellValueExplicit('H' . $counter, $value1['entri_bm_e'], PHPExcel_Cell_DataType::TYPE_NUMERIC);
        $objPHPExcel->setActiveSheetIndex(0)->setCellValueExplicit('I' . $counter, $value1['entri_barjas'], PHPExcel_Cell_DataType::TYPE_NUMERIC);
        $objPHPExcel->setActiveSheetIndex(0)->setCellValueExplicit('J' . $counter, $value1['entri_tt'], PHPExcel_Cell_DataType::TYPE_NUMERIC);
        $objPHPExcel->setActiveSheetIndex(0)->setCellValueExplicit('K' . $counter, $value1['entri_hibah_1'], PHPExcel_Cell_DataType::TYPE_NUMERIC);
        $objPHPExcel->setActiveSheetIndex(0)->setCellValueExplicit('L' . $counter, $value1['entri_hibah_2'], PHPExcel_Cell_DataType::TYPE_NUMERIC);
        $objPHPExcel->setActiveSheetIndex(0)->setCellValueExplicit('M' . $counter, $value1['entri_hibah_3'], PHPExcel_Cell_DataType::TYPE_NUMERIC);
        $objPHPExcel->setActiveSheetIndex(0)->setCellValueExplicit('N' . $counter, $value1['entri_hibah_4'], PHPExcel_Cell_DataType::TYPE_NUMERIC);
        $objPHPExcel->setActiveSheetIndex(0)->setCellValueExplicit('O' . $counter, $value1['entri_hibah_5'], PHPExcel_Cell_DataType::TYPE_NUMERIC);
        $objPHPExcel->setActiveSheetIndex(0)->setCellValueExplicit('P' . $counter, $value1['entri_lain_1'], PHPExcel_Cell_DataType::TYPE_NUMERIC);
        $objPHPExcel->setActiveSheetIndex(0)->setCellValueExplicit('Q' . $counter, $value1['entri_lain_2'], PHPExcel_Cell_DataType::TYPE_NUMERIC);
        
        // $objPHPExcel->setActiveSheetIndex(0)->setCellValue('D' . $counter, $value1['entri']);
        // $objPHPExcel->setActiveSheetIndex(0)->setCellValue('E' . $counter, $value1['kapitalisasi']);
        
        $objPHPExcel->setActiveSheetIndex(0)->setCellValueExplicit('R' . $counter, $value1['kapitalisasi_bm_a'], PHPExcel_Cell_DataType::TYPE_NUMERIC);
        $objPHPExcel->setActiveSheetIndex(0)->setCellValueExplicit('S' . $counter, $value1['kapitalisasi_bm_b'], PHPExcel_Cell_DataType::TYPE_NUMERIC);
        $objPHPExcel->setActiveSheetIndex(0)->setCellValueExplicit('T' . $counter, $value1['kapitalisasi_bm_c'], PHPExcel_Cell_DataType::TYPE_NUMERIC);
        $objPHPExcel->setActiveSheetIndex(0)->setCellValueExplicit('U' . $counter, $value1['kapitalisasi_bm_d'], PHPExcel_Cell_DataType::TYPE_NUMERIC);
        $objPHPExcel->setActiveSheetIndex(0)->setCellValueExplicit('V' . $counter, $value1['kapitalisasi_bm_e'], PHPExcel_Cell_DataType::TYPE_NUMERIC);
        $objPHPExcel->setActiveSheetIndex(0)->setCellValueExplicit('W' . $counter, $value1['kapitalisasi_barjas_harga'], PHPExcel_Cell_DataType::TYPE_NUMERIC);
        $objPHPExcel->setActiveSheetIndex(0)->setCellValueExplicit('X' . $counter, $value1['kapitalisasi_tt_harga'], PHPExcel_Cell_DataType::TYPE_NUMERIC);
        $objPHPExcel->setActiveSheetIndex(0)->setCellValueExplicit('Y' . $counter, $value1['kapitalisasi_hibah_1'], PHPExcel_Cell_DataType::TYPE_NUMERIC);
        $objPHPExcel->setActiveSheetIndex(0)->setCellValueExplicit('Z' . $counter, $value1['kapitalisasi_hibah_2'], PHPExcel_Cell_DataType::TYPE_NUMERIC);
        $objPHPExcel->setActiveSheetIndex(0)->setCellValueExplicit('AA' . $counter, $value1['kapitalisasi_hibah_3'], PHPExcel_Cell_DataType::TYPE_NUMERIC);
        $objPHPExcel->setActiveSheetIndex(0)->setCellValueExplicit('AB' . $counter, $value1['kapitalisasi_hibah_4'], PHPExcel_Cell_DataType::TYPE_NUMERIC);
        $objPHPExcel->setActiveSheetIndex(0)->setCellValueExplicit('AC' . $counter, $value1['kapitalisasi_hibah_5'], PHPExcel_Cell_DataType::TYPE_NUMERIC);
        $objPHPExcel->setActiveSheetIndex(0)->setCellValueExplicit('AD' . $counter, $value1['kapitalisasi_lain_2'], PHPExcel_Cell_DataType::TYPE_NUMERIC);
        $objPHPExcel->setActiveSheetIndex(0)->setCellValueExplicit('AE' . $counter, $value1['kapitalisasi_lain_3'], PHPExcel_Cell_DataType::TYPE_NUMERIC);
        

        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('AF' . $counter, $value1['mutasi_in']);
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('AG' . $counter, $value1['reklas_plus']);
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('AH' . $counter, $value1['penghapusan']);
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('AI' . $counter, $value1['urai_catat']);
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('AJ' . $counter, $value1['lebih_catat']);
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('AK' . $counter, $value1['koreksi_kurang_harga']);
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('AL' . $counter, $value1['mutasi_out']);
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('AM' . $counter, $value1['reklas_min']);
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('AN' . $counter, $value1['saldo_akhir']);

        // endif;
        // $objPHPExcel->getActiveSheet()->getStyle('M' . $counter . ':T' . $counter)->getNumberFormat()->setFormatCode('#,##0');
        
        $objPHPExcel->getActiveSheet()->getStyle('C'.$counter)->getNumberFormat()->setFormatCode('#,##0.00');
        $objPHPExcel->getActiveSheet()->getStyle('D'.$counter)->getNumberFormat()->setFormatCode('#,##0.00');
        $objPHPExcel->getActiveSheet()->getStyle('E'.$counter)->getNumberFormat()->setFormatCode('#,##0.00');
        $objPHPExcel->getActiveSheet()->getStyle('F'.$counter)->getNumberFormat()->setFormatCode('#,##0.00');
        $objPHPExcel->getActiveSheet()->getStyle('G'.$counter)->getNumberFormat()->setFormatCode('#,##0.00');
        $objPHPExcel->getActiveSheet()->getStyle('H'.$counter)->getNumberFormat()->setFormatCode('#,##0.00');
        $objPHPExcel->getActiveSheet()->getStyle('I'.$counter)->getNumberFormat()->setFormatCode('#,##0.00');
        $objPHPExcel->getActiveSheet()->getStyle('J'.$counter)->getNumberFormat()->setFormatCode('#,##0.00');
        $objPHPExcel->getActiveSheet()->getStyle('K'.$counter)->getNumberFormat()->setFormatCode('#,##0.00');
        $objPHPExcel->getActiveSheet()->getStyle('L'.$counter)->getNumberFormat()->setFormatCode('#,##0.00');
        $objPHPExcel->getActiveSheet()->getStyle('M'.$counter)->getNumberFormat()->setFormatCode('#,##0.00');
        $objPHPExcel->getActiveSheet()->getStyle('N'.$counter)->getNumberFormat()->setFormatCode('#,##0.00');
        $objPHPExcel->getActiveSheet()->getStyle('O'.$counter)->getNumberFormat()->setFormatCode('#,##0.00');
        $objPHPExcel->getActiveSheet()->getStyle('P'.$counter)->getNumberFormat()->setFormatCode('#,##0.00');
        $objPHPExcel->getActiveSheet()->getStyle('Q'.$counter)->getNumberFormat()->setFormatCode('#,##0.00');
        $objPHPExcel->getActiveSheet()->getStyle('R'.$counter)->getNumberFormat()->setFormatCode('#,##0.00');
        $objPHPExcel->getActiveSheet()->getStyle('S'.$counter)->getNumberFormat()->setFormatCode('#,##0.00');
        $objPHPExcel->getActiveSheet()->getStyle('T'.$counter)->getNumberFormat()->setFormatCode('#,##0.00');
        $objPHPExcel->getActiveSheet()->getStyle('U'.$counter)->getNumberFormat()->setFormatCode('#,##0.00');
        $objPHPExcel->getActiveSheet()->getStyle('V'.$counter)->getNumberFormat()->setFormatCode('#,##0.00');
        $objPHPExcel->getActiveSheet()->getStyle('W'.$counter)->getNumberFormat()->setFormatCode('#,##0.00');
        $objPHPExcel->getActiveSheet()->getStyle('X'.$counter)->getNumberFormat()->setFormatCode('#,##0.00');
        $objPHPExcel->getActiveSheet()->getStyle('Y'.$counter)->getNumberFormat()->setFormatCode('#,##0.00');
        $objPHPExcel->getActiveSheet()->getStyle('Z'.$counter)->getNumberFormat()->setFormatCode('#,##0.00');
        $objPHPExcel->getActiveSheet()->getStyle('AA'.$counter)->getNumberFormat()->setFormatCode('#,##0.00');
        $objPHPExcel->getActiveSheet()->getStyle('AB'.$counter)->getNumberFormat()->setFormatCode('#,##0.00');
        $objPHPExcel->getActiveSheet()->getStyle('AC'.$counter)->getNumberFormat()->setFormatCode('#,##0.00');
        $objPHPExcel->getActiveSheet()->getStyle('AD'.$counter)->getNumberFormat()->setFormatCode('#,##0.00');
        $objPHPExcel->getActiveSheet()->getStyle('AE'.$counter)->getNumberFormat()->setFormatCode('#,##0.00');
        $objPHPExcel->getActiveSheet()->getStyle('AF'.$counter)->getNumberFormat()->setFormatCode('#,##0.00');
        $objPHPExcel->getActiveSheet()->getStyle('AG'.$counter)->getNumberFormat()->setFormatCode('#,##0.00');
        $objPHPExcel->getActiveSheet()->getStyle('AH'.$counter)->getNumberFormat()->setFormatCode('#,##0.00');
        $objPHPExcel->getActiveSheet()->getStyle('AI'.$counter)->getNumberFormat()->setFormatCode('#,##0.00');
        $objPHPExcel->getActiveSheet()->getStyle('AJ'.$counter)->getNumberFormat()->setFormatCode('#,##0.00');
        $objPHPExcel->getActiveSheet()->getStyle('AK'.$counter)->getNumberFormat()->setFormatCode('#,##0.00');
        $objPHPExcel->getActiveSheet()->getStyle('AL'.$counter)->getNumberFormat()->setFormatCode('#,##0.00');
        $objPHPExcel->getActiveSheet()->getStyle('AM'.$counter)->getNumberFormat()->setFormatCode('#,##0.00');
        $objPHPExcel->getActiveSheet()->getStyle('AN'.$counter)->getNumberFormat()->setFormatCode('#,##0.00');
    endforeach;

    $last = $counter;
    $counter = $counter + 1;
    
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A' . $counter, 'Jumlah');
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('C' . $counter, '=SUM(C11:C'.$last.')');
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('D' . $counter, '=SUM(D11:D'.$last.')');
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('E' . $counter, '=SUM(E11:E'.$last.')');
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('F' . $counter, '=SUM(F11:F'.$last.')');
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('G' . $counter, '=SUM(G11:G'.$last.')');
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('H' . $counter, '=SUM(H11:H'.$last.')');
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('I' . $counter, '=SUM(I11:I'.$last.')');
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('J' . $counter, '=SUM(J11:J'.$last.')');
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('K' . $counter, '=SUM(K11:K'.$last.')');
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('L' . $counter, '=SUM(L11:L'.$last.')');
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('M' . $counter, '=SUM(M11:M'.$last.')');
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('N' . $counter, '=SUM(N11:N'.$last.')');
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('O' . $counter, '=SUM(O11:O'.$last.')');
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('P' . $counter, '=SUM(P11:P'.$last.')');
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('Q' . $counter, '=SUM(Q11:Q'.$last.')');
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('R' . $counter, '=SUM(R11:R'.$last.')');
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('S' . $counter, '=SUM(S11:S'.$last.')');
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('T' . $counter, '=SUM(T11:T'.$last.')');
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('U' . $counter, '=SUM(U11:U'.$last.')');
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('V' . $counter, '=SUM(U11:U'.$last.')');
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('W' . $counter, '=SUM(U11:U'.$last.')');
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('X' . $counter, '=SUM(U11:U'.$last.')');
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('Y' . $counter, '=SUM(U11:U'.$last.')');
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('Z' . $counter, '=SUM(U11:U'.$last.')');
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('AA' . $counter, '=SUM(U11:U'.$last.')');
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('AB' . $counter, '=SUM(U11:U'.$last.')');
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('AC' . $counter, '=SUM(U11:U'.$last.')');
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('AD' . $counter, '=SUM(U11:U'.$last.')');
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('AE' . $counter, '=SUM(U11:U'.$last.')');
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('AF' . $counter, '=SUM(U11:U'.$last.')');
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('AG' . $counter, '=SUM(U11:U'.$last.')');
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('AH' . $counter, '=SUM(U11:U'.$last.')');
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('AI' . $counter, '=SUM(U11:U'.$last.')');
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('AJ' . $counter, '=SUM(U11:U'.$last.')');
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('AK' . $counter, '=SUM(U11:U'.$last.')');
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('AL' . $counter, '=SUM(U11:U'.$last.')');
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('AM' . $counter, '=SUM(U11:U'.$last.')');
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('AN' . $counter, '=SUM(U11:U'.$last.')');
    
    $objPHPExcel->getActiveSheet()->getStyle('C'.$counter)->getNumberFormat()->setFormatCode('#,##0.00');
    $objPHPExcel->getActiveSheet()->getStyle('D'.$counter)->getNumberFormat()->setFormatCode('#,##0.00');
    $objPHPExcel->getActiveSheet()->getStyle('E'.$counter)->getNumberFormat()->setFormatCode('#,##0.00');
    $objPHPExcel->getActiveSheet()->getStyle('F'.$counter)->getNumberFormat()->setFormatCode('#,##0.00');
    $objPHPExcel->getActiveSheet()->getStyle('G'.$counter)->getNumberFormat()->setFormatCode('#,##0.00');
    $objPHPExcel->getActiveSheet()->getStyle('H'.$counter)->getNumberFormat()->setFormatCode('#,##0.00');
    $objPHPExcel->getActiveSheet()->getStyle('I'.$counter)->getNumberFormat()->setFormatCode('#,##0.00');
    $objPHPExcel->getActiveSheet()->getStyle('J'.$counter)->getNumberFormat()->setFormatCode('#,##0.00');
    $objPHPExcel->getActiveSheet()->getStyle('K'.$counter)->getNumberFormat()->setFormatCode('#,##0.00');
    $objPHPExcel->getActiveSheet()->getStyle('L'.$counter)->getNumberFormat()->setFormatCode('#,##0.00');
    $objPHPExcel->getActiveSheet()->getStyle('M'.$counter)->getNumberFormat()->setFormatCode('#,##0.00');
    $objPHPExcel->getActiveSheet()->getStyle('N'.$counter)->getNumberFormat()->setFormatCode('#,##0.00');
    $objPHPExcel->getActiveSheet()->getStyle('O'.$counter)->getNumberFormat()->setFormatCode('#,##0.00');
    $objPHPExcel->getActiveSheet()->getStyle('P'.$counter)->getNumberFormat()->setFormatCode('#,##0.00');
    $objPHPExcel->getActiveSheet()->getStyle('Q'.$counter)->getNumberFormat()->setFormatCode('#,##0.00');
    $objPHPExcel->getActiveSheet()->getStyle('R'.$counter)->getNumberFormat()->setFormatCode('#,##0.00');
    $objPHPExcel->getActiveSheet()->getStyle('S'.$counter)->getNumberFormat()->setFormatCode('#,##0.00');
    $objPHPExcel->getActiveSheet()->getStyle('T'.$counter)->getNumberFormat()->setFormatCode('#,##0.00');
    $objPHPExcel->getActiveSheet()->getStyle('U'.$counter)->getNumberFormat()->setFormatCode('#,##0.00');
    $objPHPExcel->getActiveSheet()->getStyle('V'.$counter)->getNumberFormat()->setFormatCode('#,##0.00');
    $objPHPExcel->getActiveSheet()->getStyle('W'.$counter)->getNumberFormat()->setFormatCode('#,##0.00');
    $objPHPExcel->getActiveSheet()->getStyle('X'.$counter)->getNumberFormat()->setFormatCode('#,##0.00');
    $objPHPExcel->getActiveSheet()->getStyle('Y'.$counter)->getNumberFormat()->setFormatCode('#,##0.00');
    $objPHPExcel->getActiveSheet()->getStyle('Z'.$counter)->getNumberFormat()->setFormatCode('#,##0.00');
    $objPHPExcel->getActiveSheet()->getStyle('AA'.$counter)->getNumberFormat()->setFormatCode('#,##0.00');
    $objPHPExcel->getActiveSheet()->getStyle('AB'.$counter)->getNumberFormat()->setFormatCode('#,##0.00');
    $objPHPExcel->getActiveSheet()->getStyle('AC'.$counter)->getNumberFormat()->setFormatCode('#,##0.00');
    $objPHPExcel->getActiveSheet()->getStyle('AD'.$counter)->getNumberFormat()->setFormatCode('#,##0.00');
    $objPHPExcel->getActiveSheet()->getStyle('AE'.$counter)->getNumberFormat()->setFormatCode('#,##0.00');
    $objPHPExcel->getActiveSheet()->getStyle('AF'.$counter)->getNumberFormat()->setFormatCode('#,##0.00');
    $objPHPExcel->getActiveSheet()->getStyle('AG'.$counter)->getNumberFormat()->setFormatCode('#,##0.00');
    $objPHPExcel->getActiveSheet()->getStyle('AH'.$counter)->getNumberFormat()->setFormatCode('#,##0.00');
    $objPHPExcel->getActiveSheet()->getStyle('AI'.$counter)->getNumberFormat()->setFormatCode('#,##0.00');
    $objPHPExcel->getActiveSheet()->getStyle('AJ'.$counter)->getNumberFormat()->setFormatCode('#,##0.00');
    $objPHPExcel->getActiveSheet()->getStyle('AK'.$counter)->getNumberFormat()->setFormatCode('#,##0.00');
    $objPHPExcel->getActiveSheet()->getStyle('AL'.$counter)->getNumberFormat()->setFormatCode('#,##0.00');
    $objPHPExcel->getActiveSheet()->getStyle('AM'.$counter)->getNumberFormat()->setFormatCode('#,##0.00');
    $objPHPExcel->getActiveSheet()->getStyle('AN'.$counter)->getNumberFormat()->setFormatCode('#,##0.00');
    
    
    $objPHPExcel->getActiveSheet()->getStyle($alphabet[0] . "8:" . $alphabet[$jumlah_field - 1] . $counter)
      ->applyFromArray($borderStyleArray);
      
    $objPHPExcel->getActiveSheet()->mergeCells('A' . $counter . ':B' . $counter);

    $first_row = $counter + 2;
    $last_row = $counter + 10;
    $counter = $counter + 2;

    $selisih_bagi_3 = $jumlah_field / 3;
    $last_col1 = $selisih_bagi_3 - 1;

    $start_col2 = $last_col1 + 1;
    $last_col2 = ($selisih_bagi_3 * 2) - 1;

    $start_col3 = $last_col2 + 1;
    $last_col = $jumlah_field - 1;


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


    $objPHPExcel->getProperties()->setCreator(strtoupper($data['jenis']['nama']))
      ->setLastModifiedBy("REKAP MUTASI ".strtoupper($data['jenis']['nama']))
      ->setTitle("Export REKAP MUTASI ".strtoupper($data['jenis']['nama']))
      ->setSubject("Export REKAP MUTASI ".strtoupper($data['jenis']['nama'])." To Excel")
      ->setDescription("Doc for Office 2007 XLSX, generated by PHPExcel.")
      ->setKeywords("office 2007 openxml php")
      ->setCategory("PHPExcel");
    $objPHPExcel->getActiveSheet()->setTitle(strtoupper($data['jenis']['nama']));

    $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
    ob_end_clean(); // MENGHILANGKAN ERROR CANNOT OPEN
    header('Last-Modified:' . gmdate("D, d M Y H:i:s") . 'GMT');
    header('Chace-Control: no-store, no-cache, must-revalation');
    header('Chace-Control: post-check=0, pre-check=0', FALSE);
    header('Pragma: no-cache');
    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment;filename="Export Data KIB ' . date('Y-m-d') . '.xlsx"');

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

  public function toPdf($data = null)
  {
    $this->load->library('PdfGenerator');
    // $data['mutasi'] = $this->rekap_mutasi_aset_model->get_all($data);

    // die(json_encode($data['mutasi']));
    $html = $this->load->view('mutasi/report_pdf', $data, true);
    $this->pdfgenerator->generate($html, 'Daftar Mutasi Barang', true, 'folio', 'landscape');
    // $this->load->view('mutasi/report_pdf', $data);

  }

  public function getParentLokasi($kode_lokasi)
  {
    if($kode_lokasi != ''){
    $kode = explode(".", $kode_lokasi);
    $kode_lokasi = $kode[1].'.'.$kode[2].'.'.$kode[3].'.'.$kode[4].'.*.*'; 
    $return = $this->laporan_model->getParentLokasi($kode_lokasi);
      return $return->instansi;
    }else{
      return '';
    }
  }
}
