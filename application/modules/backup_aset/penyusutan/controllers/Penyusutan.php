<?php

if (!defined('BASEPATH')) exit('No direct script access allowed');

class Penyusutan extends MX_Controller
{
  function __construct()
  {
    parent::__construct();
    $this->global_model->cek_hak_akses('42'); //laporan
    $this->load->model('Penyusutan_model');
    $this->load->model('laporan_model');
    $this->load->helper('my_global');
  }

  public function index()
  {
    $data['css'] = array(
      "assets/bootstrap/bootstrap-datepicker-master/dist/css/bootstrap-datepicker.min.css",
      "assets/jquery/select2-4.0.6-rc.1/dist/css/select2.min.css",
      "assets/jquery/select2-bootstrap-theme-master/dist/select2-bootstrap.min.css",
      'assets/adminlte/plugins/iCheck/all.css',
      "assets/css/laporan.css",
    );
    $data['js'] = array(
      "assets/bootstrap/bootstrap-datepicker-master/dist/js/bootstrap-datepicker.min.js",
      "assets/bootstrap/bootstrap-datepicker-master/dist/locales/bootstrap-datepicker.id.min.js",
      "assets/jquery/select2-4.0.6-rc.1/dist/js/select2.min.js",
      'assets/adminlte/plugins/iCheck/icheck.min.js',
      "assets/js/laporan.js",

    );
    $data['kib'] = $this->global_model->kode_jenis;

    $data['pemilik'] = $this->global_model->get_pemilik();
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

    $data['content'] = $this->load->view('/penyusutan/home', $data, true);
    $data['breadcrumb'] = array(
      array('label' => 'Laporan', 'url' => '#', 'icon' => 'fa fa-pie-chart', 'li_class' => ''),
      array('label' => 'Penyusutan', 'url' => '#', 'icon' => '', 'li_class' => 'active'),
      // array('label' => 'Buat', 'url' => '#','icon'=>'', 'li_class'=>'active'),
    );
    $this->load->view('template', $data);
  }



  public function ndownload()
  {
    // $data['kode_jenis'] = $this->input->post('kode_jenis');
    $data['id_pemilik'] = $this->input->post('pemilik');

    $data['id_pengguna'] = $this->input->post('id_pengguna');
    $data['id_kuasa_pengguna'] = $this->input->post('id_kuasa_pengguna');
    $data['id_sub_kuasa_pengguna'] = $this->input->post('id_sub_kuasa_pengguna');
    $data['intra_ekstra'] = $this->input->post('intra_ekstra');
    
    $data['start_date'] = '';
    $data['last_date'] = date('Y-m-d', tgl_inter("31 " . $this->input->post('bulan_tahun')));
    // if ($this->input->post('start_date'))
    //   $data['start_date'] = date('Y-m-d', tgl_inter($this->input->post('start_date')));
    // else $data['start_date'] = '';
    // if ($this->input->post('last_date'))
    //   $data['last_date'] = date('Y-m-d', tgl_inter($this->input->post('last_date')));
    // else $data['last_date'] = '';

    $data['intra_ekstra'] = $this->input->post('intra_ekstra');
    $data['format'] = $this->input->post('format');


    $data['field_name'] = array(
      // 'no',  'kode_barang',  'nomor_register',  'nama_barang',  'merk_type', 'tanggal_pembelian', 'umur_bulan', 'umur_ekonomis', 'jumlah_barang', 'nilai_perolehan', 'nilai_penyusutan', 'akumulasi_penyusutan', 'nilai_buku', 'instansi',
      'no',  'kode_barang',  'nomor_register',  'nama_barang',  'merk_type', 'tanggal_pembelian', 'umur_bulan', 'umur_ekonomis', 'nilai_perolehan', 'nilai_penyusutan', 'akumulasi_penyusutan', 'nilai_buku', 'instansi',
      // jumlah barang hilang
    );
    $data['label'] = array(
      // 'No',  'Kode Barang',  'Nomor Register',  'Nama Barang / Uraian',  'Merk / Type', 'Tanggal_pembelian/Pengadaan', 'umur_bulan', 'umur_ekonomis', 'Jumlah Barang', 'Nilai Perolehan', 'Nilai Penyusutan', 'Akumulasi Penyusutan', 'Nilai Buku', 'Unit Kerja',
      'No',  'Kode Barang',  'Nomor Register',  'Nama Barang / Uraian',  'Merk / Type', 'Tanggal_pembelian/Pengadaan', 'umur_bulan', 'umur_ekonomis', 'Nilai Perolehan', 'Nilai Penyusutan', 'Akumulasi Penyusutan', 'Nilai Buku', 'Unit Kerja',
    );


    $id_kode_lokasi = null;
    if ($data['id_sub_kuasa_pengguna'] != '') {
      $id_kode_lokasi = $data['id_sub_kuasa_pengguna'];
      array_pop($data['field_name']);
      array_pop($data['label']);
    } else if ($data['id_kuasa_pengguna'] != '') {
      $id_kode_lokasi = $data['id_kuasa_pengguna'];
    } else if ($data['id_pengguna'] != '') {
      $id_kode_lokasi = $data['id_pengguna'];
    }

    $result_pemilik = $this->global_model->get_pemilik_by_id($data['id_pemilik']);

    //tertanda
    $tertanda = $this->laporan_model->get_tertanda($id_kode_lokasi);
    $data['tertanda'] = $tertanda->result();

    $data['des_pemilik'] = $result_pemilik->kode . ' - ' . $result_pemilik->nama;
    $result_lokasi = $this->laporan_model->get_lokasi_by_id($id_kode_lokasi);
    $kode_lokasi = $result_pemilik->kode . '.' . $result_lokasi->kode_lokasi;
    $data['kode_lokasi'] = get_intra_extra(remove_star($kode_lokasi), $data['intra_ekstra']);
    $data['nama_lokasi'] = $result_lokasi->instansi;

    if ($data['format'] == 'pdf')
      $this->toPdf($data);
    else if ($data['format'] == 'excel')
      $this->toExcel($data);
  }

  public function toExcel($data = null)
  {
    $field_name = $data['field_name'];
    $label = $data['label'];

    $itemcount = count($label);

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

    $counter = 2;
    $objPHPExcel->getActiveSheet()->mergeCells('A' . $counter . ':' . $last_alpabet . $counter);
    $objPHPExcel->getActiveSheet()->getStyle('A' . $counter . ':' . $last_alpabet . $counter)
      ->applyFromArray($header)
      ->getFont()->setSize(9);
    $objPHPExcel->setActiveSheetIndex(0)
      ->setCellValue('A' . $counter, 'DAFTAR PENYUSUTAN BARANG');


    $objPHPExcel->getActiveSheet()->mergeCells('A4:C4');
    $objPHPExcel->getActiveSheet()->mergeCells('A5:C5');
    $objPHPExcel->getActiveSheet()->mergeCells('A6:C6');

    $objPHPExcel->setActiveSheetIndex(0)
      ->setCellValue('A4', 'SKPD')
      ->setCellValue('D4', ': ' . $data['nama_lokasi'])

      ->setCellValue('A5', 'KABUPATEN/KOTA')
      ->setCellValue('D5', ': Kota Yogyakarta')

      ->setCellValue('F5', 'NO. KODE LOKASI')
      ->setCellValue('G5', ': ' . $data['kode_lokasi'])

      ->setCellValue('A6', 'PROVINSI')
      ->setCellValue('D6', ': D.I Yogyakarta');
    $counter = 7;
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue($last_alpabet . $counter, $data['des_pemilik']);
    $objPHPExcel->getActiveSheet()
      ->getStyle($last_alpabet . $counter)
      ->getAlignment()
      ->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);


    $counter = $counter + 1;
    $first_row_header = $counter;
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A' . $counter, 'Nomor');
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('D' . $counter, 'Spesifikasi Barang');

    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('F' . $counter, 'Tanggal Pembelian/Pengadaan');
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('G' . $counter, 'Umur Barang');
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('H' . $counter, 'Umur Ekonomis');
    // $objPHPExcel->setActiveSheetIndex(0)->setCellValue('I' . $counter, 'Jumlah Barang');
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('I' . $counter, 'Nilai Perolehan');
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('J' . $counter, 'Nilai Penyusutan');
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('K' . $counter, 'Akumulasi Penyusutan');
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('L' . $counter, 'Nilai Buku');
    if (in_array('instansi', $data['field_name']))
      $objPHPExcel->setActiveSheetIndex(0)->setCellValue('M' . $counter, 'Unit Kerja');


    $objPHPExcel->getActiveSheet()->mergeCells('A' . $counter . ':C' . $counter);
    $objPHPExcel->getActiveSheet()->mergeCells('D' . $counter . ':E' . $counter);

    $counter = $counter + 1;
    $last_row_header = $counter;
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A' . $counter, 'Urut');
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('B' . $counter, 'Kode Barang');
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('C' . $counter, 'Register');
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('D' . $counter, 'Jenis / Nama Barang');
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('E' . $counter, 'Merk / Type');


    $objPHPExcel->getActiveSheet()->mergeCells('F' . $first_row_header . ':F' . $last_row_header);
    $objPHPExcel->getActiveSheet()->mergeCells('G' . $first_row_header . ':G' . $last_row_header);
    $objPHPExcel->getActiveSheet()->mergeCells('H' . $first_row_header . ':H' . $last_row_header);
    $objPHPExcel->getActiveSheet()->mergeCells('I' . $first_row_header . ':I' . $last_row_header);
    $objPHPExcel->getActiveSheet()->mergeCells('J' . $first_row_header . ':J' . $last_row_header);
    $objPHPExcel->getActiveSheet()->mergeCells('K' . $first_row_header . ':K' . $last_row_header);
    $objPHPExcel->getActiveSheet()->mergeCells('L' . $first_row_header . ':L' . $last_row_header);
    $objPHPExcel->getActiveSheet()->mergeCells('M' . $first_row_header . ':M' . $last_row_header);
    $objPHPExcel->getActiveSheet()->mergeCells('N' . $first_row_header . ':N' . $last_row_header);

    $objPHPExcel->getActiveSheet()->getStyle("A" . $first_row_header . ":" . $last_alpabet . $last_row_header)
      ->applyFromArray($table_header)
      ->getFont()->setSize(12);
    //end header

    // ===============================

    $ex = $objPHPExcel->setActiveSheetIndex(0);
    $counter = $counter + 1;

    $penyusutan = $this->Penyusutan_model->get_all($data);

    $no = 1;
    $t_perolehan = 0;
    $t_penyusutan  = 0;
    $t_akumulasi= 0;
    $t_nilaibuku = 0;

    foreach ($penyusutan as $value) {
      foreach ($alphabet as $key => $v_alphabet) {
        if (($field_name[$key] == 'tanggal_pembelian') and ($value[$field_name[$key]] != '')) {
          $ex->setCellValue($v_alphabet . $counter, tgl_indo($value[$field_name[$key]]));
          // die($value[$field_name[$key]]);
        }
        // else if (($field_name[$key] == 'tanggal_perolehan') and ($value[$field_name[$key]] != '')){
        //   $ex->setCellValue($v_alphabet.$counter, date('Y',strtotime($value[$field_name[$key]]))   );
        // }
        else if (($field_name[$key] == 'umur_bulan' or $field_name[$key] == 'umur_ekonomis' or $field_name[$key] == 'jumlah_barang' or $field_name[$key] == 'nilai_perolehan' or $field_name[$key] == 'nilai_penyusutan' or
          $field_name[$key] == 'akumulasi_penyusutan' or $field_name[$key] == 'nilai_buku') and ($value[$field_name[$key]] == '0')) {
            $ex->setCellValue($v_alphabet . $counter, '0');
        } else if ($field_name[$key] == "nomor_register") {
          $temp_register =  explode(',', $value[$field_name[$key]]);
          $nomor_register = cek_register_kosong($temp_register);
          $ex->setCellValue($v_alphabet . $counter, $nomor_register);
        }else if (($field_name[$key] == 'no')) {
          $ex->setCellValue($v_alphabet . $counter, $no++);
        }
        // elseif ($field_name[$key] == "sertifikat_nomor") {
        //   $objPHPExcel->getActiveSheet()->setCellValueExplicit($v_alphabet.$counter, $value[$field_name[$key]], PHPExcel_Cell_DataType::TYPE_STRING);
        //   // $ex->setCellValue($v_alphabet.$counter, "'".$value[$field_name[$key]]);
        // }
        else {
          $ex->setCellValue($v_alphabet . $counter, $value[$field_name[$key]]);
        }

        if($field_name[$key] == 'nilai_perolehan'){
          $t_perolehan += $value[$field_name[$key]];
        }
        if($field_name[$key] == 'nilai_penyusutan'){
          $t_penyusutan  += $value[$field_name[$key]];
        }
        if($field_name[$key] == 'akumulasi_penyusutan'){
          $t_akumulasi += $value[$field_name[$key]];
        }
        if($field_name[$key] == 'nilai_buku'){
          $t_nilaibuku += $value[$field_name[$key]];
        }
      }
      $counter = $counter + 1;
    }

    
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A' . $counter, 'Jumlah');
    $objPHPExcel->setActiveSheetIndex(0)->setCellValueExplicit('I' . $counter, $t_perolehan, PHPExcel_Cell_DataType::TYPE_NUMERIC);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValueExplicit('J' . $counter, $t_penyusutan, PHPExcel_Cell_DataType::TYPE_NUMERIC);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValueExplicit('K' . $counter, $t_akumulasi, PHPExcel_Cell_DataType::TYPE_NUMERIC);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValueExplicit('L' . $counter, $t_nilaibuku, PHPExcel_Cell_DataType::TYPE_NUMERIC);

    $objPHPExcel->getActiveSheet()->getStyle('I'.$counter)->getNumberFormat()->setFormatCode('#,##0.00');
    $objPHPExcel->getActiveSheet()->getStyle('J'.$counter)->getNumberFormat()->setFormatCode('#,##0.00');
    $objPHPExcel->getActiveSheet()->getStyle('K'.$counter)->getNumberFormat()->setFormatCode('#,##0.00');
    $objPHPExcel->getActiveSheet()->getStyle('L'.$counter)->getNumberFormat()->setFormatCode('#,##0.00');
    
    $objPHPExcel->getActiveSheet()->mergeCells('A' . $counter . ':H' . $counter);

    $last_row = $counter;
    $objPHPExcel->getActiveSheet()->getStyle($alphabet[0] . "8:" . $alphabet[$itemcount - 1] . $last_row)
      ->applyFromArray($borderStyleArray);

    foreach ($alphabet as $columnID) {
      if (in_array($columnID, array('G', 'H', 'I', 'J', 'K', 'L', 'M')))
        $objPHPExcel->getActiveSheet()->getColumnDimension($columnID)->setWidth(20);
      else
        $objPHPExcel->getActiveSheet()->getColumnDimension($columnID)->setAutoSize(true);
    }

    foreach ($field_name as $key => $value) :
      if (
        $value == 'nilai_perolehan' or $value == 'nilai_penyusutan' or
        $value == 'akumulasi_penyusutan' or $value == 'nilai_buku'
      ) {
        $cell = $alphabet[$key] . "1:" . $alphabet[$key] . $counter;
        $objPHPExcel->getActiveSheet()->getStyle($cell)->getNumberFormat()->setFormatCode('#,##0.00');
      }
    endforeach;

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

    $objPHPExcel->getProperties()->setCreator("ASET")
      ->setLastModifiedBy("ASET")
      ->setTitle("Export Data Penyusutan")
      ->setSubject("Export KIB To Excel")
      ->setDescription("Doc for Office 2007 XLSX, generated by PHPExcel.")
      ->setKeywords("office 2007 openxml php")
      ->setCategory("PHPExcel");
    $objPHPExcel->getActiveSheet()->setTitle('Data KIB');

    $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
    header('Last-Modified:' . gmdate("D, d M Y H:i:s") . 'GMT');
    header('Chace-Control: no-store, no-cache, must-revalation');
    header('Chace-Control: post-check=0, pre-check=0', FALSE);
    header('Pragma: no-cache');
    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment;filename="Export Data Penyusutan ' . date('Y-m-d') . '.xlsx"');

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
    $data['penyusutan'] = $this->Penyusutan_model->get_all($data);

    $html = $this->load->view('/penyusutan/report_pdf', $data, true);
    $this->pdfgenerator->generate($html, 'Daftar Penyusutan Barang ' . tgl_indo(date('Y-m-d')), true, 'folio', 'landscape');
     //$this->load->view('/penyusutan/report_pdf', $data);

  }
}
