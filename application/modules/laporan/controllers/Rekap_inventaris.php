<?php

if (!defined('BASEPATH')) exit('No direct script access allowed');

class Rekap_inventaris extends CI_Controller
{
  function __construct()
  {
    parent::__construct();
    $this->global_model->cek_hak_akses('19');
    $this->load->model('laporan_model');
    $this->load->model('rekap_inventaris_model');
    $this->load->helper('my_global');
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

    $data['content'] = $this->load->view('rekap_inventaris/home', $data, true);
    $data['breadcrumb'] = array(
      array('label' => 'Laporan', 'url' => '#', 'icon' => 'fa fa-pie-chart', 'li_class' => ''),
      array('label' => 'Rekap Buku Inventaris', 'url' => '#', 'icon' => '', 'li_class' => 'active'),
      // array('label' => 'Buat', 'url' => '#','icon'=>'', 'li_class'=>'active'),
    );
    $this->load->view('template', $data);
  }

  public function excel()
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
    $data['start_tahun'] = date('Y');
    $data['last_bulan'] = $this->input->post('last_bulan');
    $data['last_tahun'] = $this->input->post('last_tahun');

    // if ($this->input->post('start_date'))
    //   $data['start_date'] = date('Y-m-d', tgl_inter($this->input->post('start_date')));
    // else $data['start_date'] = '';
    // if ($this->input->post('last_date'))
    //   $data['last_date'] = date('Y-m-d', tgl_inter($this->input->post('last_date')));
    // else $data['last_date'] = '';
    $data['intra_ekstra'] = $this->input->post('intra_ekstra');
    $data['format'] = $this->input->post('format');

    if ($data['jenis_rekap'] == 'objek') {
      $data['field_name'] = array(
        // 'no',  'jenis',  'objek',  'nama_barang',  'total_jumlah',  'total_harga',  'keterangan',
        'NO',  'objek',  'nama_barang',  'total_jumlah',  'total_harga',  'keterangan',
      );
      $data['label'] = array(
        // 'NO',  'JENIS',  'OBJEK',  'URAIAN', 'JUMLAH BARANG', 'JUMLAH HARGA (Rp.)', 'Keterangan',
        'NO',  'OBJEK',  'URAIAN', 'JUMLAH BARANG', 'JUMLAH HARGA (Rp.)', 'Keterangan',
      );
    } else if ($data['jenis_rekap'] == 'rincian_objek') {
      $data['field_name'] = array(
        // 'no',  'objek',  'rincian_objek',  'nama_barang',  'total_jumlah',  'total_harga',  'keterangan',
        'NO',  'objek',  'nama_barang',  'total_jumlah',  'total_harga',  'keterangan',
      );
      $data['label'] = array(
        // 'NO',  'OBJEK',  'RINCIAN OBJEK',  'URAIAN', 'JUMLAH BARANG', 'JUMLAH HARGA (Rp.)', 'Keterangan',
        'NO',  'RINCIAN OBJEK',  'URAIAN', 'JUMLAH BARANG', 'JUMLAH HARGA (Rp.)', 'Keterangan',
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
      $id_kode_lokasi = '0';
    }


    $result_pemilik = $this->global_model->get_pemilik_by_id($data['id_pemilik']);

    //tertanda
    $tertanda = $this->laporan_model->get_tertanda($id_kode_lokasi);
    $data['tertanda'] = $tertanda->result();
    //die(json_encode($data['tertanda']));
    // $data['count_tertanda'] = $tertanda->num_rows();

    $data['des_pemilik'] = $result_pemilik->kode . ' - ' . $result_pemilik->nama;
    $result_lokasi = $this->laporan_model->get_lokasi_by_id($id_kode_lokasi);
    $kode_lokasi = $result_pemilik->kode . '.' . $result_lokasi->kode_lokasi;
    $data['kode_lokasi'] = get_intra_extra(remove_star($kode_lokasi), $data['intra_ekstra']);
    $data['nama_lokasi'] = $result_lokasi->instansi;
    $data['pengguna'] = $result_lokasi->pengguna;

    $data['id_kode_lokasi'] = $id_kode_lokasi;

    if($data['last_bulan'] <> 0){ // jika bukan saldo awal
      if($data['id_kuasa_pengguna'] <> '' && $data['id_kode_lokasi'] <> '0'){ //jika bukan rekap satu skpd
        $data['rekap_inventaris'] = $this->rekap_inventaris_model->get_all($data);
      }elseif($data['id_kode_lokasi'] == '0'){ //jika sekota
        $data['rekap_inventaris'] = $this->rekap_inventaris_model->get_all_kota($data);
      }else{
        $data['rekap_inventaris'] = $this->rekap_inventaris_model->get_all_skpd($data);
      }
    }else{
      if($data['id_kuasa_pengguna'] <> '' && $data['id_kode_lokasi'] <> '0'){ //jika bukan rekap satu skpd

        $data['rekap_inventaris'] = $this->rekap_inventaris_model->get_saldo_awal($data);
        if(empty($data['rekap_inventaris'])){
          
          $this->rekap_inventaris_model->set_saldo_awal($data);
          $data['rekap_inventaris'] = $this->rekap_inventaris_model->get_saldo_awal($data);

        }
      }elseif($data['id_kode_lokasi'] == '0'){ //jika sekota
        $data['rekap_inventaris'] = $this->rekap_inventaris_model->get_saldo_awal_kota($data);
      }
        else{
        $data['rekap_inventaris'] = $this->rekap_inventaris_model->get_saldo_awal_skpd($data);
      }
    }

    // print_r($data['rekap_inventaris']); die;

    if ($data['format'] == 'pdf')
      $this->toPdf($data);
    else if ($data['format'] == 'excel')
      $this->toExcel($data);
  }

  public function saldo_awal(){
    
    $lokasi = $this->global_model->get_lokasi_all_by_pengguna();

    foreach($lokasi as $key) {
      $data['session'] = $this->session->userdata('session');
      $data['id_pemilik'] = '2';

      $data['id_pengguna'] = $key->pengguna;
      $data['id_kuasa_pengguna'] = $key->kuasa_pengguna;
      $data['id_sub_kuasa_pengguna'] = '';
      $data['id_kode_lokasi'] = $key->id_kode_lokasi;
      $data['intra_ekstra'] = '02';
      
      $data['start_bulan'] = '';
      $data['start_tahun'] = date('Y');
      $data['last_bulan'] = 0;
      $data['last_tahun'] = date('Y');
      
      $this->rekap_inventaris_model->set_saldo_awal($data);
    }
    die('RAMPUNG BOS');
  }


  public function toPdf($data = null)
  {
    $this->load->library('PdfGenerator');

    $html = $this->load->view('rekap_inventaris/report_pdf', $data, true);
    $this->pdfgenerator->generate($html, 'Buku Inventaris', true, 'folio', 'portrait');
    // $this->load->view('rekap_inventaris/report_pdf', $data);

  }

  public function toExcel($data = null)
  {
    $data['list_intra_ekstra'] = array(
      '00' => 'Semua',
      '01' => 'Intrakomptable',
      '02' => 'Extrakomptable',
    );
    $intra_extra = $data['list_intra_ekstra'][$data['intra_ekstra']];

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

    $objPHPExcel->getActiveSheet()->mergeCells('A2:B2');
    $objPHPExcel->getActiveSheet()->mergeCells('A3:B3');
    $objPHPExcel->getActiveSheet()->mergeCells('A4:B4');

    $objPHPExcel->setActiveSheetIndex(0)
      ->setCellValue('A2', 'SKPD/Unit Kerja')
      ->setCellValue('C2', ': ' . $data['nama_lokasi'])

      ->setCellValue('A3', 'KOTA')
      ->setCellValue('C3', ': Kota Yogyakarta')

      ->setCellValue('F3', 'Kode SKPD/Unit Kerja ' . ': ' . $data['kode_lokasi'])
      
      ->setCellValue('F4', 'Kondisi : '.bulan_indo($data['start_bulan']).' s/d '.bulan_indo($data['last_bulan']))
      // ->setCellValue('G3', ': ' . $data['kode_lokasi'])

      ->setCellValue('A4', 'PROPINSI')
      ->setCellValue('C4', ': D.I Yogyakarta');


    $col1 = $itemcount / 2;
    $col2 = $col1;
    $col1 = $col1 - 1;

    $counter = 6;
    $objPHPExcel->getActiveSheet()->mergeCells('A' . $counter . ':' . $last_alpabet . $counter);
    $objPHPExcel->getActiveSheet()->getStyle('A' . $counter . ':' . $last_alpabet . $counter)
      ->applyFromArray($header)
      ->getFont()->setSize(9);
    $objPHPExcel->setActiveSheetIndex(0)
      ->setCellValue('A' . $counter, 'REKAPITULASI BUKU INVENTARIS');
    if (!empty($this->input->post('start_date')) or !empty($this->input->post('last_date'))) {
      $counter = $counter + 1;
      $objPHPExcel->getActiveSheet()->mergeCells('A' . $counter . ':' . $last_alpabet . $counter);
      $objPHPExcel->getActiveSheet()->getStyle('A' . $counter . ':' . $last_alpabet . $counter)
        ->getAlignment()
        ->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
      $objPHPExcel->setActiveSheetIndex(0)
        ->setCellValue('A' . $counter, 'Periode : ' . $this->input->post('start_date') . ' sampai dengan ' . $this->input->post('last_date'));
    }

    $counter = $counter + 1;
    $objPHPExcel->getActiveSheet()->mergeCells('A' . $counter . ':B' . $counter);
    // $objPHPExcel->getActiveSheet()->getStyle('A' . $counter . ':' . $last_alpabet . $counter)
    //   ->getAlignment()
    //   ->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
    $objPHPExcel->setActiveSheetIndex(0)
      ->setCellValue("A" . $counter, 'Filter : ' . strtoupper($intra_extra));
    $objPHPExcel->setActiveSheetIndex(0)
      ->setCellValue($last_alpabet . $counter, $data['des_pemilik']);


    $counter = $counter + 2;
    $start_row_table = $counter;
    foreach ($alphabet as $key => $value) {
      $objPHPExcel->setActiveSheetIndex(0)->setCellValue($value . $counter, $label[$key]);
    }
    $objPHPExcel->getActiveSheet()->getStyle("A" . $counter . ":" . $last_alpabet . $counter)
      ->applyFromArray($table_header)
      ->getFont()->setSize(12);

    $ex = $objPHPExcel->setActiveSheetIndex(0);
    $counter = $counter + 1;
    
    $t_jumlah = 0;
    $t_harga = 0;

    foreach ($data['rekap_inventaris'] as $value) {

      
      if($value['status'] == '1') :
        $t_jumlah += $value['total_jumlah'];
        $t_harga   += $value['total_harga'];
      endif;

      foreach ($alphabet as $key => $v_alphabet) {
        if ($field_name[$key] == 'objek' and $value[$field_name[$key]] == '999')
          $ex->setCellValue($v_alphabet . $counter, "");
        else
          $ex->setCellValue($v_alphabet . $counter, $value[$field_name[$key]]);
      }

      //set font bold
      if ($data['jenis_rekap'] == 'objek') {
        if ($value['status'] == '1' or $value['status'] == '2' or $value['status'] == '4') {
          $objPHPExcel->getActiveSheet()->getStyle('A' . $counter . ':' . $last_alpabet . $counter)
            ->getFont()->setBold(true);
        }
      } else if ($data['jenis_rekap'] == 'rincian_objek') {
        if ($value['status'] == '1' or $value['status'] == '2' or $value['status'] == '3' or $value['status'] == '5') {
          $objPHPExcel->getActiveSheet()->getStyle('A' . $counter . ':' . $last_alpabet . $counter)
            ->getFont()->setBold(true);
        }
      }


      $counter = $counter + 1;
    }
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue("A" . $counter, "");
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue("B" . $counter, "");
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue("C" . $counter, "JUMLAH ASET");
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue("D" . $counter, $t_jumlah);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue("E" . $counter, $t_harga);
    
    $objPHPExcel->getActiveSheet()->getStyle('E'.$counter)->getNumberFormat()->setFormatCode('#,##0.00');

    $objPHPExcel->getActiveSheet()->getStyle('A' . $counter . ':' . $last_alpabet . $counter)
            ->getFont()->setBold(true);

    $last_row = $counter;

    $objPHPExcel->getActiveSheet()
      ->getStyle($alphabet[1] . "8:" . $alphabet[2] . $last_row) // kolom B dan C
      ->getAlignment()
      ->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);

    $objPHPExcel->getActiveSheet()->getStyle($alphabet[0] . $start_row_table . ":" . $alphabet[$itemcount - 1] . $last_row)
      ->applyFromArray($borderStyleArray);

    foreach ($alphabet as $columnID) {
      $objPHPExcel->getActiveSheet()->getColumnDimension($columnID)
        ->setAutoSize(true);
    }

    foreach ($field_name as $key => $value) :
      if ( $value == "total_harga") {
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


    $objPHPExcel->getProperties()->setCreator("KIB")
      ->setLastModifiedBy("KIB")
      ->setTitle("Export Data Rekap. Buku Inventaris")
      ->setSubject("Export To Excel")
      ->setDescription("Doc for Office 2007 XLSX, generated by PHPExcel.")
      ->setKeywords("office 2007 openxml php")
      ->setCategory("PHPExcel");
    $objPHPExcel->getActiveSheet()->setTitle('Data Rekap. Buku Inventaris');

    $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
    ob_end_clean(); // MENGHILANGKAN ERROR CANNOT OPEN
    header('Last-Modified:' . gmdate("D, d M Y H:i:s") . 'GMT');
    header('Chace-Control: no-store, no-cache, must-revalation');
    header('Chace-Control: post-check=0, pre-check=0', FALSE);
    header('Pragma: no-cache');
    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment;filename="Data Rekap. Buku Inventaris ' . date('Y-m-d') . '.xlsx"');

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
