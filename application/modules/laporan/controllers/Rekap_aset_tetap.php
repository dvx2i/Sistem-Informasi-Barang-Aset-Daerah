<?php

if (!defined('BASEPATH')) exit('No direct script access allowed');

class Rekap_aset_tetap extends MX_Controller
{
  function __construct()
  {
    parent::__construct();
    $this->global_model->cek_hak_akses('106');
    $this->load->model('laporan_model');
    $this->load->model('rekap_aset_tetap_model');
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
      array('label' => 'Rekap Aset Tetap', 'url' => '#', 'icon' => '', 'li_class' => 'active'),
      // array('label' => 'Buat', 'url' => '#','icon'=>'', 'li_class'=>'active'),
    );
    $data['url'] = base_url('laporan/rekap_aset_tetap/ndownload');
    $data['content'] = $this->load->view('/rekap_aset_tetap/home', $data, true);
    $this->load->view('template', $data);
  }

  public function ndownload()
  {
    $data['kode_jenis'] = '';
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
    

    $data['bulan'] = date('m', tgl_inter("1 " . $this->input->post('bulan_tahun')));
    $data['tahun'] = date('Y', tgl_inter("1 " . $this->input->post('bulan_tahun')));

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
    $data['mutasi'] = $this->rekap_aset_tetap_model->get_all($data);
    
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
    $jumlah_field = 12;

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
      ->setCellValue('A' . $counter, 'ASET TETAP '.' '.$data['per'].' TAHUN '.$data['tahun']);


    $objPHPExcel->getActiveSheet()->mergeCells('A4:C4');
    $objPHPExcel->getActiveSheet()->mergeCells('A5:C5');
    $objPHPExcel->getActiveSheet()->mergeCells('A6:C6');

    $objPHPExcel->setActiveSheetIndex(0)
      ->setCellValue('A4', '')
      ->setCellValue('D4', '')

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
    $last_row_header = 9;
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A' . $counter, 'NO');
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('B' . $counter, 'NAMA SKPD');
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('C' . $counter, 'TANAH');
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('D' . $counter, 'PERALATAN DAN MESIN');
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('E' . $counter, 'GEDUNG DAN BANGUNAN');
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('F' . $counter, 'JALAN JARINGAN DAN JEMBATAN');
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('G' . $counter, 'ASET TETAP LAINNYA');
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('H' . $counter, 'KONSTRUKSI DALAM PENGERJAAN');
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('I' . $counter, 'SALDO AKHIR');
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('J' . $counter, 'BEBAN PENYUSUTAN');
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('K' . $counter, 'AKUMULASI PENYUSUTAN');
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('L' . $counter, 'NILAI BUKU');
    // if (in_array('instansi', $data['field_name']) )
    //   $objPHPExcel->setActiveSheetIndex(0)->setCellValue('P'.$counter, 'Unit Kerja');

    $objPHPExcel->getActiveSheet()->getStyle("A" . $first_row_header . ":L" . $counter)
      ->applyFromArray($table_header)
      ->getFont()->setSize(12);
      $objPHPExcel->getActiveSheet()->getStyle("A" . $first_row_header . ":L" . $counter)
      ->getAlignment()->setWrapText(true);
    //end header

    // ===============================

    $ex = $objPHPExcel->setActiveSheetIndex(0);
    // $counter = $counter+1;

    // $mutasi = $this->rekap_aset_tetap_model->get_all($data);

    
    $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(7);
    $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(60);
    $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(23);
    $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(23);
    $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(23);
    $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(23);
    $objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(23);
    $objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(23);
    $objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(23);
    $objPHPExcel->getActiveSheet()->getColumnDimension('J')->setWidth(23);
    $objPHPExcel->getActiveSheet()->getColumnDimension('K')->setWidth(23);
    $objPHPExcel->getActiveSheet()->getColumnDimension('L')->setWidth(23);
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
        $objPHPExcel->setActiveSheetIndex(0)->setCellValueExplicit('C' . $counter, $value1['kib_a'], PHPExcel_Cell_DataType::TYPE_NUMERIC);

        $objPHPExcel->setActiveSheetIndex(0)->setCellValueExplicit('D' . $counter, $value1['kib_b'], PHPExcel_Cell_DataType::TYPE_NUMERIC);
        $objPHPExcel->setActiveSheetIndex(0)->setCellValueExplicit('E' . $counter, $value1['kib_c'], PHPExcel_Cell_DataType::TYPE_NUMERIC);
        $objPHPExcel->setActiveSheetIndex(0)->setCellValueExplicit('F' . $counter, $value1['kib_d'], PHPExcel_Cell_DataType::TYPE_NUMERIC);
        $objPHPExcel->setActiveSheetIndex(0)->setCellValueExplicit('G' . $counter, $value1['kib_e'], PHPExcel_Cell_DataType::TYPE_NUMERIC);
        $objPHPExcel->setActiveSheetIndex(0)->setCellValueExplicit('H' . $counter, $value1['kib_f'], PHPExcel_Cell_DataType::TYPE_NUMERIC);
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('I' . $counter, '=SUM(C'.$counter.':H'.$counter.')');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValueExplicit('J' . $counter, $value1['nilai_penyusutan'], PHPExcel_Cell_DataType::TYPE_NUMERIC);
        $objPHPExcel->setActiveSheetIndex(0)->setCellValueExplicit('K' . $counter, $value1['akumulasi_penyusutan'], PHPExcel_Cell_DataType::TYPE_NUMERIC);
        $objPHPExcel->setActiveSheetIndex(0)->setCellValueExplicit('L' . $counter, $value1['nilai_buku'], PHPExcel_Cell_DataType::TYPE_NUMERIC);

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
    endforeach;

    $last = $counter;
    $counter = $counter + 1;
    
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A' . $counter, 'Jumlah');
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('C' . $counter, '=SUM(C9:C'.$last.')');
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('D' . $counter, '=SUM(D9:D'.$last.')');
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('E' . $counter, '=SUM(E9:E'.$last.')');
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('F' . $counter, '=SUM(F9:F'.$last.')');
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('G' . $counter, '=SUM(G9:G'.$last.')');
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('H' . $counter, '=SUM(H9:H'.$last.')');
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('I' . $counter, '=SUM(I9:I'.$last.')');
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('J' . $counter, '=SUM(J9:J'.$last.')');
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('K' . $counter, '=SUM(K9:K'.$last.')');
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('L' . $counter, '=SUM(L9:L'.$last.')');
    
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
      ->setLastModifiedBy("ASET TETAP ")
      ->setTitle("Export ASET TETAP ")
      ->setSubject("Export ASET TETAP "." To Excel")
      ->setDescription("Doc for Office 2007 XLSX, generated by PHPExcel.")
      ->setKeywords("office 2007 openxml php")
      ->setCategory("PHPExcel");
    $objPHPExcel->getActiveSheet()->setTitle('ASET TETAP ');

    $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
    ob_end_clean(); // MENGHILANGKAN ERROR CANNOT OPEN
    header('Last-Modified:' . gmdate("D, d M Y H:i:s") . 'GMT');
    header('Chace-Control: no-store, no-cache, must-revalation');
    header('Chace-Control: post-check=0, pre-check=0', FALSE);
    header('Pragma: no-cache');
    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment;filename="Export ASET TETAP '. date('Y-m-d') . '.xlsx"');

    $objWriter->save('php://output');
    die();
  }
  
  public function toExcelRekon($data = null)
  {
    $jumlah_field = 23;

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


    $counter = 6;
    $objPHPExcel->getActiveSheet()->mergeCells('A' . $counter . ':' . $last_alpabet . $counter);
    $objPHPExcel->getActiveSheet()->getStyle('A' . $counter . ':' . $last_alpabet . $counter)
      ->applyFromArray($header)
      ->getFont()->setSize(9);
      $objPHPExcel->setActiveSheetIndex(0)
        ->setCellValue('A1', 'BERITA ACARA REKONSILIASI ASET TETAP DAN ASET LAINNYA');
        $objPHPExcel->setActiveSheetIndex(0)
          ->setCellValue('A2', 'Nomor : ……………………………………');
          $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A3', 'Pada SKPD/Unit Kerja: '.$data['nama_lokasi']);
            $objPHPExcel->setActiveSheetIndex(0)
              ->setCellValue('A4', 'Pada hari ini …………………………… tanggal ………………… Bulan ……………… Tahun ……………… di ……………… Kota Yogyakarta kami yang bertanda tangan dibawah ini, menyatakan bahwa telah melakukan rekonsliasi aset tetap dan aset lainnya dengan hasil sebagai berikut :');
    $objPHPExcel->setActiveSheetIndex(0)
      ->setCellValue('A' . $counter, 'DAFTAR MUTASI BARANG');

      $objPHPExcel->getActiveSheet()->mergeCells('A1:W1');
      $objPHPExcel->getActiveSheet()->mergeCells('A2:W2');
      $objPHPExcel->getActiveSheet()->mergeCells('A3:W3');
      $objPHPExcel->getActiveSheet()->mergeCells('A4:W4');
      $objPHPExcel->getActiveSheet()->getStyle('A1:W3')
      ->applyFromArray($header)
      ->getFont()->setSize(9);

    $objPHPExcel->getActiveSheet()->mergeCells('A8:C8');
    $objPHPExcel->getActiveSheet()->mergeCells('A9:C9');
    $objPHPExcel->getActiveSheet()->mergeCells('A10:C10');

    $objPHPExcel->setActiveSheetIndex(0)
      ->setCellValue('A8', 'SKPD')
      ->setCellValue('D8', ': ' . $data['nama_lokasi'])

      ->setCellValue('A9', 'KABUPATEN/KOTA')
      ->setCellValue('D9', ': Kota Yogyakarta')

      ->setCellValue('F9', 'NO. KODE LOKASI')
      ->setCellValue('G9', ': ' . $data['kode_lokasi'])

      ->setCellValue('A10', 'PROVINSI')
      ->setCellValue('D10', ': D.I Yogyakarta');
    $counter = 11;
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue($last_alpabet . $counter, $data['des_pemilik']);
    $objPHPExcel->getActiveSheet()
      ->getStyle($last_alpabet . $counter)
      ->getAlignment()
      ->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);


    $counter = $counter + 1;
    $first_row_header = $counter;
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A' . $counter, 'NOMOR');
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('D' . $counter, 'SPESIFIKASI BARANG');
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('H' . $counter, 'Asal/Cara Perolehan Barang');
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('I' . $counter, 'Tahun Beli/Perolehan');
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('J' . $counter, 'Ukuran Barang / konstruksi (P,SP,D)');
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('K' . $counter, 'Satuan');
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('L' . $counter, 'Kondisi (B,BR,RB)');
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('M' . $counter, 'Jumlah (Awal) Bulan '.bulan_indo($data['start_bulan']));

    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('O' . $counter, 'MUTASI/PERUBAHAN');
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('S' . $counter, 'Jumlah (Akhir) Bulan '.bulan_indo($data['last_bulan']));
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('U' . $counter, 'Kode Rekening');
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('V' . $counter, 'Uraian Belanja');
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('W' . $counter, 'Keterangan');
    // if (in_array('instansi', $data['field_name']) )
    //   $objPHPExcel->setActiveSheetIndex(0)->setCellValue('P'.$counter, 'Unit Kerja');

    $objPHPExcel->getActiveSheet()->mergeCells('A' . $counter . ':C' . $counter);
    $objPHPExcel->getActiveSheet()->mergeCells('D' . $counter . ':G' . $counter);
    $objPHPExcel->getActiveSheet()->mergeCells('M' . $counter . ':N' . $counter);
    $objPHPExcel->getActiveSheet()->mergeCells('O' . $counter . ':R' . $counter);
    $objPHPExcel->getActiveSheet()->mergeCells('S' . $counter . ':T' . $counter);

    $counter = $counter + 1;
    $last_row_header = $counter + 1;
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A' . $counter, 'No Urt');
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('B' . $counter, 'Kode Barang');
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('C' . $counter, 'Register');
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('D' . $counter, 'Nama / Jenis Barang');
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('E' . $counter, 'Merk / Type');
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('F' . $counter, 'No. Sertifikat, No. Pabrik, No Chasis, No. Mesin');
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('G' . $counter, 'Bahan');
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('M' . $counter, 'Barang');
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('N' . $counter, 'Harga');
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('O' . $counter, 'Berkurang');
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('Q' . $counter, 'Bertambah');
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('S' . $counter, 'Barang');
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('T' . $counter, 'Harga');


    $objPHPExcel->getActiveSheet()->mergeCells('A' . $counter . ':A' . $last_row_header);
    $objPHPExcel->getActiveSheet()->mergeCells('B' . $counter . ':B' . $last_row_header);
    $objPHPExcel->getActiveSheet()->mergeCells('C' . $counter . ':C' . $last_row_header);
    $objPHPExcel->getActiveSheet()->mergeCells('D' . $counter . ':D' . $last_row_header);
    $objPHPExcel->getActiveSheet()->mergeCells('E' . $counter . ':E' . $last_row_header);
    $objPHPExcel->getActiveSheet()->mergeCells('F' . $counter . ':F' . $last_row_header);
    $objPHPExcel->getActiveSheet()->mergeCells('G' . $counter . ':G' . $last_row_header);
    $objPHPExcel->getActiveSheet()->mergeCells('H' . $first_row_header . ':H' . $last_row_header);
    $objPHPExcel->getActiveSheet()->mergeCells('I' . $first_row_header . ':I' . $last_row_header);
    $objPHPExcel->getActiveSheet()->mergeCells('J' . $first_row_header . ':J' . $last_row_header);
    $objPHPExcel->getActiveSheet()->mergeCells('K' . $first_row_header . ':K' . $last_row_header);
    $objPHPExcel->getActiveSheet()->mergeCells('L' . $first_row_header . ':L' . $last_row_header);
    $objPHPExcel->getActiveSheet()->mergeCells('M' . $counter . ':M' . $last_row_header);
    $objPHPExcel->getActiveSheet()->mergeCells('N' . $counter . ':N' . $last_row_header);
    $objPHPExcel->getActiveSheet()->mergeCells('O' . $counter . ':P' . $counter);
    $objPHPExcel->getActiveSheet()->mergeCells('Q' . $counter . ':R' . $counter);
    $objPHPExcel->getActiveSheet()->mergeCells('S' . $counter . ':S' . $last_row_header);
    $objPHPExcel->getActiveSheet()->mergeCells('T' . $counter . ':T' . $last_row_header);
    $objPHPExcel->getActiveSheet()->mergeCells('U' . $first_row_header . ':U' . $last_row_header);
    $objPHPExcel->getActiveSheet()->mergeCells('V' . $first_row_header . ':V' . $last_row_header);
    $objPHPExcel->getActiveSheet()->mergeCells('W' . $first_row_header . ':W' . $last_row_header);


    $counter = $counter + 1;
    $last_row_header = $counter;
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('O' . $counter, 'Jumlah Barang');
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('P' . $counter, 'Jumlah Harga');
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('Q' . $counter, 'Jumlah Barang');
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('R' . $counter, 'Jumlah Harga');

    $objPHPExcel->getActiveSheet()->getStyle("A" . $first_row_header . ":W" . $last_row_header)
      ->applyFromArray($table_header)
      ->getFont()->setSize(12);
      $objPHPExcel->getActiveSheet()->getStyle("A12:W14")
      ->getAlignment()->setWrapText(true);
    //end header

    // ===============================

    $ex = $objPHPExcel->setActiveSheetIndex(0);
    // $counter = $counter+1;

    // $mutasi = $this->rekap_aset_tetap_model->get_all($data);

    
    $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(7);
    $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(20);
    $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(10);
    $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(60);
    $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(30);
    $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(30);
    $objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(30);
    $objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(30);
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
    $objPHPExcel->getActiveSheet()->getColumnDimension('W')->setWidth(30);
    // foreach ($alphabet as $columnID) {
    //   $objPHPExcel->getActiveSheet()->getColumnDimension($columnID)
    //     ->setAutoSize(true);
    // }

    // die(json_encode($data['mutasi']));
    $t_jumlah_in = 0;
    $t_harga_in  = 0;
    $t_jumlah_out= 0;
    $t_harga_out = 0;
    $counter_lokasi = 0;
    $urut = 1;
    foreach ($data['mutasi'] as $key => $value1) :
      $counter = $counter + 1;
      if ($value1['LEVEL'] == 1) :
        if ($counter_lokasi != 0) $counter = $counter + 1;
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A' . $counter, remove_star($value1['kode_lokasi']) . ' - ' . $value1['instansi']);
        $objPHPExcel->getActiveSheet()->mergeCells('A' . $counter . ':' . $last_alpabet . $counter);
        $objPHPExcel->getActiveSheet()->getStyle("A" . $counter . ":" . $last_alpabet . $counter)
          ->applyFromArray(array('font' => array('bold' => true)));
        $counter_lokasi = 1;
      else :
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A' . $counter, $urut++);
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('B' . $counter, $value1['kode_barang']);

        $temp_register =  explode(',', $value1['nomor_register']);
        $nomor_register = cek_register_kosong($temp_register);
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('C' . $counter, $nomor_register);
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('D' . $counter, $value1['nama_barang']);
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('E' . $counter, $value1['merk_type']);
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('F' . $counter, $value1['sertifikat_nomor']);
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('G' . $counter, $value1['bahan']);
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('H' . $counter, $value1['asal_usul']);
        // update fajar 5 okt 20
        // $tgl = explode(' ', tgl_indo($value1['tanggal_perolehan']));
        $tgl = explode(' ', tgl_indo($value1['tanggal_pembelian']));
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('I' . $counter, isset($tgl[2]) ? $tgl[2] : '');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('J' . $counter, $value1['ukuran_barang']);
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('K' . $counter, $value1['satuan']);
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('L' . $counter, $value1['keadaan_barang']);

        // if ($value1['min_id'] == $value1['min_id_by_kode_barang']) :
        $objPHPExcel->setActiveSheetIndex(0)->setCellValueExplicit('M' . $counter, $value1['jumlah_awal'], PHPExcel_Cell_DataType::TYPE_NUMERIC);
        $objPHPExcel->setActiveSheetIndex(0)->setCellValueExplicit('N' . $counter, $value1['harga_awal'], PHPExcel_Cell_DataType::TYPE_NUMERIC);
        // else :

        // endif;

        if ($value1['status_histori'] == 'penghapusan' or $value1['status_histori'] == 'mutasi_out'  or $value1['status_histori'] == 'koreksi_kurang') :
          $objPHPExcel->setActiveSheetIndex(0)->setCellValueExplicit('O' . $counter, $value1['jumlah'], PHPExcel_Cell_DataType::TYPE_NUMERIC);
          $objPHPExcel->setActiveSheetIndex(0)->setCellValueExplicit('P' . $counter, $value1['harga'], PHPExcel_Cell_DataType::TYPE_NUMERIC);
          $objPHPExcel->setActiveSheetIndex(0)->setCellValueExplicit('Q' . $counter, 0, PHPExcel_Cell_DataType::TYPE_NUMERIC);
          $objPHPExcel->setActiveSheetIndex(0)->setCellValueExplicit('R' . $counter, 0, PHPExcel_Cell_DataType::TYPE_NUMERIC);
          $t_jumlah_out+= $value1['jumlah'];
          $t_harga_out += $value1['harga'];
        elseif ($value1['status_histori'] == 'entri' or $value1['status_histori'] == 'mutasi_in'  or $value1['status_histori'] == 'koreksi_tambah') :
          $objPHPExcel->setActiveSheetIndex(0)->setCellValueExplicit('O' . $counter, 0, PHPExcel_Cell_DataType::TYPE_NUMERIC);
          $objPHPExcel->setActiveSheetIndex(0)->setCellValueExplicit('P' . $counter, 0, PHPExcel_Cell_DataType::TYPE_NUMERIC);
          $objPHPExcel->setActiveSheetIndex(0)->setCellValueExplicit('Q' . $counter, $value1['jumlah'], PHPExcel_Cell_DataType::TYPE_NUMERIC);
          $objPHPExcel->setActiveSheetIndex(0)->setCellValueExplicit('R' . $counter, $value1['harga'], PHPExcel_Cell_DataType::TYPE_NUMERIC);
          $t_jumlah_in += $value1['jumlah'];
          $t_harga_in  += $value1['harga'];
        endif;

        // if ($value1['max_id'] == $value1['max_id_by_kode_barang']) :
        $objPHPExcel->setActiveSheetIndex(0)->setCellValueExplicit('S' . $counter, $value1['jumlah_akhir'], PHPExcel_Cell_DataType::TYPE_NUMERIC);
        $objPHPExcel->setActiveSheetIndex(0)->setCellValueExplicit('T' . $counter, $value1['harga_akhir'], PHPExcel_Cell_DataType::TYPE_NUMERIC);
        // else :
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('U' . $counter, $value1['kode_rekening']);
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('V' . $counter, $value1['nama_rekening']);
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('W' . $counter, $value1['ket']);

        // endif;
        // $objPHPExcel->getActiveSheet()->getStyle('M' . $counter . ':T' . $counter)->getNumberFormat()->setFormatCode('#,##0');
        
        $objPHPExcel->getActiveSheet()->getStyle('N'.$counter)->getNumberFormat()->setFormatCode('#,##0.00');
        $objPHPExcel->getActiveSheet()->getStyle('P'.$counter)->getNumberFormat()->setFormatCode('#,##0.00');
        $objPHPExcel->getActiveSheet()->getStyle('R'.$counter)->getNumberFormat()->setFormatCode('#,##0.00');
        $objPHPExcel->getActiveSheet()->getStyle('T'.$counter)->getNumberFormat()->setFormatCode('#,##0.00');
      endif;
    endforeach;

    $counter = $counter + 1;
    
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A' . $counter, 'Jumlah');
    $objPHPExcel->setActiveSheetIndex(0)->setCellValueExplicit('O' . $counter, $t_jumlah_out, PHPExcel_Cell_DataType::TYPE_NUMERIC);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValueExplicit('P' . $counter, $t_harga_out, PHPExcel_Cell_DataType::TYPE_NUMERIC);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValueExplicit('Q' . $counter, $t_jumlah_in, PHPExcel_Cell_DataType::TYPE_NUMERIC);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValueExplicit('R' . $counter, $t_harga_in, PHPExcel_Cell_DataType::TYPE_NUMERIC);
    
    $objPHPExcel->getActiveSheet()->getStyle('N'.$counter)->getNumberFormat()->setFormatCode('#,##0.00');
    $objPHPExcel->getActiveSheet()->getStyle('P'.$counter)->getNumberFormat()->setFormatCode('#,##0.00');
    $objPHPExcel->getActiveSheet()->getStyle('R'.$counter)->getNumberFormat()->setFormatCode('#,##0.00');
    $objPHPExcel->getActiveSheet()->getStyle('T'.$counter)->getNumberFormat()->setFormatCode('#,##0.00');
    
    $objPHPExcel->getActiveSheet()->getStyle($alphabet[0] . "12:" . $alphabet[$jumlah_field - 1] . $counter)
      ->applyFromArray($borderStyleArray);
      
    $objPHPExcel->getActiveSheet()->mergeCells('A' . $counter . ':N' . $counter);

    $first_row = $counter + 2;
    $last_row = $counter + 8;
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

    $objPHPExcel->getProperties()->setCreator("KIB")
      ->setLastModifiedBy("KIB")
      ->setTitle("Export Data KIB")
      ->setSubject("Export KIB To Excel")
      ->setDescription("Doc for Office 2007 XLSX, generated by PHPExcel.")
      ->setKeywords("office 2007 openxml php")
      ->setCategory("PHPExcel");
    $objPHPExcel->getActiveSheet()->setTitle('Data KIB');

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
    // $data['mutasi'] = $this->rekap_aset_tetap_model->get_all($data);

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
