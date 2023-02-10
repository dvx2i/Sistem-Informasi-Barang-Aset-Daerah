<?php

if (!defined('BASEPATH')) exit('No direct script access allowed');

class Kir extends CI_Controller
{
  function __construct()
  {
    parent::__construct();
    $this->global_model->cek_hak_akses('19');
    $this->load->model('laporan_model');
    $this->load->model('kir_model');
    $this->load->model('stock_opname_kib_model');
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

    $data['content'] = $this->load->view('kir/home', $data, true);
    $data['breadcrumb'] = array(
      array('label' => 'Laporan', 'url' => '#', 'icon' => 'fa fa-pie-chart', 'li_class' => ''),
      array('label' => 'Buku Inventaris Ruang', 'url' => '#', 'icon' => '', 'li_class' => 'active'),
      // array('label' => 'Buat', 'url' => '#','icon'=>'', 'li_class'=>'active'),
    );
    $this->load->view('template', $data);
  }

  public function excel()
  {
    // $data['kode_jenis'] = $this->input->post('kode_jenis');
    $data['id_pemilik'] = $this->input->post('pemilik');
	$data['mulai'] = $this->input->post('mulai');

    $data['id_pengguna'] = $this->input->post('id_pengguna');
    $data['id_kuasa_pengguna'] = $this->input->post('id_kuasa_pengguna');
    $data['id_sub_kuasa_pengguna'] = $this->input->post('id_sub_kuasa_pengguna');
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
        $this->session->set_flashdata('message', 'Bulan Perolehan Tidak Valid');
          redirect(site_url('laporan/inventaris'));
      }
    }

    $data['intra_ekstra'] = $this->input->post('intra_ekstra');
    $data['format'] = $this->input->post('format');




    $data['field_name'] = array(
      'no','id_kib',  'nomor_register', 'kode_barang',   'nama_barang',  'deskripsi', 'merk_type',  'tanggal_perolehan', 'jumlah_barang','satuan', 'keterangan'
    );

    $data['label'] = array(
      'no','id_kib',  'nomor_register', 'kode_barang',   'nama_barang',  'deskripsi', 'merk_type',  'tanggal_perolehan', 'jumlah_barang','satuan', 'keterangan'
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

    if($data['last_bulan'] > 6){
      $semester = '2';
    }else{
      $semester = '1';
    }
    $backup_kib = $this->stock_opname_kib_model->get_by_lokasi($result_lokasi->pengguna,$semester,$data['last_tahun']);

    if($backup_kib->num_rows() > 0){
      $data['prefix'] = "_".$data['last_tahun']."_s".$semester;
    }else{
      $data['prefix'] = "";
    }

    // die($data['prefix']);


    if ($data['format'] == 'pdf')
      $this->toPdf($data);
    else if ($data['format'] == 'excel')
      $this->toExcel($data);
  }

  public function toExcel($data = null)
  {
    // $temp_register =  explode(',', "000001,000007");
    // $nomor_register = cek_register_kosong($temp_register);
    // echo $nomor_register;

    // die();
    $field_name = $data['field_name'];
    $label = $data['label'];

    $itemcount = count($field_name);

    $alphabet = array();
    for ($na = 0; $na < $itemcount; $na++) {
      $alphabet[] = $this->generateAlphabet($na);
    }
    $last_alpabet = $alphabet[$itemcount - 1];

    $itemfield = "";
    foreach ($field_name as $key => $value) :
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
    $header2 = array(
      'alignment' => array(
        'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
        'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,
      ),
      'font' => array(
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
      ->setCellValue('A' . $counter, 'Buku Inventaris Ruang');
    if (!empty($this->input->post('start_date')) or !empty($this->input->post('last_date'))) {
      $counter = $counter + 1;
      $objPHPExcel->getActiveSheet()->mergeCells('A' . $counter . ':' . $last_alpabet . $counter);
      $objPHPExcel->getActiveSheet()->getStyle('A' . $counter . ':' . $last_alpabet . $counter)
        ->applyFromArray($header2)
        ->getFont()->setSize(9);

      $objPHPExcel->setActiveSheetIndex(0)
        ->setCellValue('A' . $counter, 'Periode : ' . $this->input->post('start_date') . ' sampai dengan ' . $this->input->post('last_date'));
    }

    $objPHPExcel->getActiveSheet()->mergeCells('A4:C4');
    $objPHPExcel->getActiveSheet()->mergeCells('A5:C5');
    $objPHPExcel->getActiveSheet()->mergeCells('A6:C6');

    $objPHPExcel->setActiveSheetIndex(0)
      ->setCellValue('A4', 'Kuasa Pengguna Barang')
      ->setCellValue('D4', ': ' . $data['nama_lokasi'])

      ->setCellValue('A5', 'Pengguna Barang')
      ->setCellValue('D5', ': ')

      ->setCellValue('A6', 'Kode Lokasi ')
      ->setCellValue('D6', ': ' . $data['kode_lokasi'])
      
      ->setCellValue('A7', 'Nama Ruangan')
      ->setCellValue('D7', ': ');
    $counter = 8;
    $objPHPExcel->getActiveSheet()
      ->getStyle($last_alpabet . $counter)
      ->getAlignment()
      ->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

    $counter = $counter + 1;
    $first_row_header = $counter;
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A' . $counter, 'No');
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('B' . $counter, 'ID KIB');
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('C' . $counter, 'Nomor Register');
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('D' . $counter, 'Kode Barang');
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('E' . $counter, 'Nama Barang');
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('F' . $counter, 'Spesifikasi Nama Barang');
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('G' . $counter, 'Spesifikasi Barang');
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('I' . $counter, 'Jumlah');
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('J' . $counter, 'Satuan');
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('K' . $counter, 'Keterangan');

    $objPHPExcel->getActiveSheet()->mergeCells('G' . $counter . ':H' . $counter);


    $counter = $counter + 1;
    $last_row_header = $counter;
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('G' . $counter, 'Merk / Type');
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('H' . $counter, 'Tahun Perolehan');


    $objPHPExcel->getActiveSheet()->mergeCells('A' . $first_row_header . ':A' . $last_row_header);
    $objPHPExcel->getActiveSheet()->mergeCells('B' . $first_row_header . ':B' . $last_row_header);
    $objPHPExcel->getActiveSheet()->mergeCells('F' . $first_row_header . ':F' . $last_row_header);
    $objPHPExcel->getActiveSheet()->mergeCells('C' . $first_row_header . ':C' . $last_row_header);
    $objPHPExcel->getActiveSheet()->mergeCells('D' . $first_row_header . ':D' . $last_row_header);
    $objPHPExcel->getActiveSheet()->mergeCells('E' . $first_row_header . ':E' . $last_row_header);
    $objPHPExcel->getActiveSheet()->mergeCells('F' . $first_row_header . ':F' . $last_row_header);
    $objPHPExcel->getActiveSheet()->mergeCells('I' . $first_row_header . ':I' . $last_row_header);
    $objPHPExcel->getActiveSheet()->mergeCells('J' . $first_row_header . ':J' . $last_row_header);
    $objPHPExcel->getActiveSheet()->mergeCells('K' . $first_row_header . ':K' . $last_row_header);


    $objPHPExcel->getActiveSheet()->getStyle("A" . $first_row_header . ":" . $last_alpabet . $last_row_header)
      ->applyFromArray($table_header)
      ->getFont()->setSize(12);
      $objPHPExcel->getActiveSheet()->getStyle("A9:" . $last_alpabet . $last_row_header)
      ->getAlignment()->setWrapText(true);
    //end header

    // ===============================

    $ex = $objPHPExcel->setActiveSheetIndex(0);
    $counter = $counter + 1;
if($data['mulai']){
		$mulai = $data['mulai'];}
		else{
			$mulai = 0;
		}
    $no = $mulai == 0 ? $mulai +1 : $mulai;
    $inventaris = $this->kir_model->get_all_limit($data,$mulai);
    
    // if($this->session->userdata('session')->id_upik == 'JSS-A0149'){
    //   print_r($inventaris); die;
    // }

    $i = 0;
    $st_jumlah = 0;
    $st_harga = 0;
    
    
    foreach ($inventaris as $value) {
      
      if ($value['kode_jenis'] == '02') {
        foreach ($alphabet as $key => $v_alphabet) {
          if (($field_name[$key] == 'sertifikat_tanggal' or $field_name[$key] == 'gedung_tanggal' or $field_name[$key] == 'dokumen_tanggal' or $field_name[$key] == 'tanggal_mulai') and ($value[$field_name[$key]] != '')) {
            $ex->setCellValue($v_alphabet . $counter, tgl_indo($value[$field_name[$key]]));
            // die($value[$field_name[$key]]);
          } else if (($field_name[$key] == 'tanggal_perolehan') and ($value[$field_name[$key]] != '')  and ($value[$field_name[$key]] != '0000-00-00')) {
            $ex->setCellValue($v_alphabet . $counter, date('Y', strtotime($value[$field_name[$key]])));
          } else if (($field_name[$key] == 'tanggal_perolehan') and ($value[$field_name[$key]] == '0000-00-00')) {
            $ex->setCellValue($v_alphabet . $counter, "");
          } else if (($field_name[$key] == 'ukuran_barang') and ($value[$field_name[$key]] == '0')) {
            $ex->setCellValue($v_alphabet . $counter, '');
          } else if ($field_name[$key] == "nomor_register") {
            $temp_register =  explode(',', $value[$field_name[$key]]);
            $nomor_register = cek_register_kosong($temp_register);
            $ex->setCellValue($v_alphabet . $counter, $nomor_register);
          } elseif ($field_name[$key] == "sertifikat_nomor") {
            $objPHPExcel->getActiveSheet()->setCellValueExplicit($v_alphabet . $counter, $value[$field_name[$key]], PHPExcel_Cell_DataType::TYPE_STRING);
            // $ex->setCellValue($v_alphabet.$counter, "'".$value[$field_name[$key]]);
          } else if (($field_name[$key] == 'no')) {
            $ex->setCellValue($v_alphabet . $counter, $no++);
          } else
            $ex->setCellValue($v_alphabet . $counter, $value[$field_name[$key]]);
        }

        $st_jumlah++;
        $st_harga += $value['harga'];
        $i++;
        $counter++;
      }
    }
    

    if ($i != 0) {
      $ex->setCellValue('A' . $counter, 'Jumlah KIB B');
      $ex->setCellValue('M' . $counter, $st_jumlah);
      $ex->setCellValue('N' . $counter, $st_harga);
      $counter++;

      $st_jumlah = 0;
      $st_harga = 0;
      $i = 0;
    }
    foreach ($inventaris as $value) {
      
      if ($value['kode_jenis'] == '05') {
        foreach ($alphabet as $key => $v_alphabet) {
          if (($field_name[$key] == 'sertifikat_tanggal' or $field_name[$key] == 'gedung_tanggal' or $field_name[$key] == 'dokumen_tanggal' or $field_name[$key] == 'tanggal_mulai') and ($value[$field_name[$key]] != '')) {
            $ex->setCellValue($v_alphabet . $counter, tgl_indo($value[$field_name[$key]]));
            // die($value[$field_name[$key]]);
          } else if (($field_name[$key] == 'tanggal_perolehan') and ($value[$field_name[$key]] != '')  and ($value[$field_name[$key]] != '0000-00-00')) {
            $ex->setCellValue($v_alphabet . $counter, date('Y', strtotime($value[$field_name[$key]])));
          } else if (($field_name[$key] == 'tanggal_perolehan') and ($value[$field_name[$key]] == '0000-00-00')) {
            $ex->setCellValue($v_alphabet . $counter, "");
          } else if (($field_name[$key] == 'ukuran_barang') and ($value[$field_name[$key]] == '0')) {
            $ex->setCellValue($v_alphabet . $counter, '');
          } else if ($field_name[$key] == "nomor_register") {
            $temp_register =  explode(',', $value[$field_name[$key]]);
            $nomor_register = cek_register_kosong($temp_register);
            $ex->setCellValue($v_alphabet . $counter, $nomor_register);
          } elseif ($field_name[$key] == "sertifikat_nomor") {
            $objPHPExcel->getActiveSheet()->setCellValueExplicit($v_alphabet . $counter, $value[$field_name[$key]], PHPExcel_Cell_DataType::TYPE_STRING);
            // $ex->setCellValue($v_alphabet.$counter, "'".$value[$field_name[$key]]);
          } else if (($field_name[$key] == 'no')) {
            $ex->setCellValue($v_alphabet . $counter, $no++);
          } else
            $ex->setCellValue($v_alphabet . $counter, $value[$field_name[$key]]);
        }

        $st_jumlah++;
        $st_harga += $value['harga'];
        $i++;
        $counter++;
      }
    }
    

    if ($i != 0) {
      $ex->setCellValue('A' . $counter, 'Jumlah KIB E');
      $ex->setCellValue('M' . $counter, $st_jumlah);
      $ex->setCellValue('N' . $counter, $st_harga);
      $counter++;

      $st_jumlah = 0;
      $st_harga = 0;
      $i = 0;
    }
    

    $last_row = $counter - 1;
    $objPHPExcel->getActiveSheet()->getStyle($alphabet[0] . "9:" . $alphabet[$itemcount - 1] . $last_row)
      ->applyFromArray($borderStyleArray);

      
      $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(7);
      $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(20);
      $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(10);
      $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(30);
      $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(30);
      $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(20);
      $objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(50);
      $objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(30);
      $objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(30);
      $objPHPExcel->getActiveSheet()->getColumnDimension('J')->setWidth(15);
      $objPHPExcel->getActiveSheet()->getColumnDimension('K')->setWidth(20);
      $objPHPExcel->getActiveSheet()->getColumnDimension('L')->setWidth(20);
      $objPHPExcel->getActiveSheet()->getColumnDimension('M')->setWidth(20);
      $objPHPExcel->getActiveSheet()->getColumnDimension('N')->setWidth(20);
      $objPHPExcel->getActiveSheet()->getColumnDimension('O')->setWidth(20);
      $objPHPExcel->getActiveSheet()->getColumnDimension('P')->setWidth(20);
      $objPHPExcel->getActiveSheet()->getColumnDimension('Q')->setWidth(50);
    // foreach ($alphabet as $columnID) {
    //   $objPHPExcel->getActiveSheet()->getColumnDimension($columnID)
    //     ->setAutoSize(true);
    // }

    foreach ($field_name as $key => $value) :
      // if ( $value == "luas" or $value == "harga" or $value == "ukuran_cc" or $value == "luas_lantai_m2" or $value == "luas_m2" or $value == "panjang_km" or $value == "lebar_m" or $value == "luas_m2" or $value == "hewan_tumbuhan_ukuran" or $value == "jumlah"  or $value == "nilai_kontrak") {
      if ($value == "harga") {
        $cell = $alphabet[$key] . "1:" . $alphabet[$key] . $counter;
        $objPHPExcel->getActiveSheet()->getStyle($cell)->getNumberFormat()->setFormatCode('#,##0.00');
      }
    // if ($value == "sertifikat_nomor") {
    //   $cell=$alphabet[$key]."1:".$alphabet[$key].$counter;
    //   $objPHPExcel->getActiveSheet()->getStyle($cell)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_TEXT);
    // }
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
      ->setTitle("Export Buku Inventaris Ruang")
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
    header('Content-Disposition: attachment;filename="Export Buku Inventaris Ruang ' . date('Y-m-d') . '.xlsx"');

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
    //$data['jenis'] = $this->global_model->kode_jenis[$data['kode_jenis']];
    // $id_kode_lokasi = null;
    // if ($data['id_sub_kuasa_pengguna'] != '') {
    //   $id_kode_lokasi = $data['id_sub_kuasa_pengguna'];
    //   array_pop($data['field_name']);
    //   array_pop($data['label']);
    // }
    // else if ($data['id_kuasa_pengguna'] != '') {
    //   $id_kode_lokasi = $data['id_kuasa_pengguna'];
    // }
    // else if ($data['id_pengguna'] != '') {
    //   $id_kode_lokasi = $data['id_pengguna'];
    // }
    //
    // $data['kode_lokasi'] = $this->laporan_model->get_lokasi_by_id($id_kode_lokasi)->kode_lokasi;

    $data['inventaris'] = $this->kir_model->get_all($data);

    // die(json_encode($data['inventaris']));
    $html = $this->load->view('kir/report_pdf', $data, true);
    $this->pdfgenerator->generate($html, 'Buku Inventaris Ruang', true, 'folio', 'landscape');
    //$this->load->view('kir/report_pdf', $data);

  }
}
