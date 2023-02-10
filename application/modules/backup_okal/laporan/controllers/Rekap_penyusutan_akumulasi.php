<?php

if (!defined('BASEPATH')) exit('No direct script access allowed');

class Rekap_penyusutan_akumulasi extends MX_Controller
{
  function __construct()
  {
    parent::__construct();
    $this->global_model->cek_hak_akses('42'); //laporan
    $this->load->model('penyusutan_rekap_model');
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

    $data['content'] = $this->load->view('penyusutan_rekap/home', $data, true);
    $data['breadcrumb'] = array(
      array('label' => 'Laporan', 'url' => '#', 'icon' => 'fa fa-pie-chart', 'li_class' => ''),
      array('label' => 'Rekap Penyusutan', 'url' => '#', 'icon' => '', 'li_class' => 'active'),
      // array('label' => 'Buat', 'url' => '#','icon'=>'', 'li_class'=>'active'),
    );
    $this->load->view('template', $data);
  }



  public function ndownload()
  {
    $data['session'] = $this->session->userdata('session');
    $data['id_pemilik'] = $this->input->post('pemilik');

    $data['id_pengguna'] = $this->input->post('id_pengguna');
    $data['id_kuasa_pengguna'] = $this->input->post('id_kuasa_pengguna');
    $data['id_sub_kuasa_pengguna'] = $this->input->post('id_sub_kuasa_pengguna');
    $data['jenis_rekap'] = $this->input->post('jenis_rekap');
    $data['intra_ekstra'] = $this->input->post('intra_ekstra');

    $data['start_date'] = '';
    $data['last_date'] = date('Y-m-d', tgl_inter("31 " . $this->input->post('bulan_tahun')));
    
    $data['start_date2'] = '';
    $data['last_date2'] = date('Y', strtotime($data['last_date'])).'-06-30';
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
        // 'no',	'jenis',	'objek',	'nama_barang',	'total_jumlah',	'total_harga',	'nilai_penyusutan', 'akumulasi_penyusutan', 'nilai_buku',
        'NO',  'objek',  'nama_barang',  'total_jumlah',  'total_harga',  'nilai_penyusutan', 'akumulasi_penyusutan', 'nilai_buku',
        // jenis hilang
      );
      $data['label'] = array(
        // 'NO',	'JENIS',	'OBJEK',	'URAIAN', 'JUMLAH BARANG','JUMLAH HARGA', 'NILAI PENYUSUTAN', 'AKUMULASI PENYUSUTAN', 'NILAI BUKU',
        'NO',  'OBJEK',  'URAIAN', 'JUMLAH BARANG', 'JUMLAH HARGA', 'NILAI PENYUSUTAN', 'AKUMULASI PENYUSUTAN', 'NILAI BUKU', 'JUMLAH BARANG', 'JUMLAH HARGA', 'NILAI PENYUSUTAN', 'AKUMULASI PENYUSUTAN', 'NILAI BUKU',  'NILAI PENYUSUTAN'
      );
    } else if ($data['jenis_rekap'] == 'rincian_objek') {
      $data['field_name'] = array(
        'NO',  'objek',  'nama_barang',  'total_jumlah',  'total_harga',  'nilai_penyusutan', 'akumulasi_penyusutan', 'nilai_buku',
      );
      $data['label'] = array(
        'NO',  'RINCIAN OBJEK',  'URAIAN', 'JUMLAH BARANG', 'JUMLAH HARGA', 'NILAI PENYUSUTAN', 'AKUMULASI PENYUSUTAN', 'NILAI BUKU',
      );
    }

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
      $id_kode_lokasi = $_SESSION['session']->id_kode_lokasi;
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

    $data['id_kode_lokasi'] = $id_kode_lokasi;

    $data['rekap_penyusutan'] = $this->penyusutan_rekap_model->get_all($data);
    
    $data['rekap_penyusutan2'] = $this->penyusutan_rekap_model->get_all_juni($data);
    if ($data['format'] == 'pdf')
      $this->toPdf($data);
    else if ($data['format'] == 'excel')
      $this->toExcel($data);
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

    $objPHPExcel->getActiveSheet()->mergeCells('A2:C2');
    $objPHPExcel->getActiveSheet()->mergeCells('A3:C3');
    $objPHPExcel->getActiveSheet()->mergeCells('A4:C4');

    $objPHPExcel->setActiveSheetIndex(0)
      ->setCellValue('A2', 'SKPD/Unit Kerja')
      ->setCellValue('D2', ': ' . $data['nama_lokasi'])

      ->setCellValue('A3', 'KOTA')
      ->setCellValue('D3', ': Kota Yogyakarta')

      ->setCellValue('F3', 'Kode SKPD/Unit Kerja')
      ->setCellValue('G3', ': ' . $data['kode_lokasi'])

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
      ->setCellValue('A' . $counter, 'REKAPITULASI PENYUSUTAN BARANG');
    $counter = $counter + 1;
    $objPHPExcel->setActiveSheetIndex(0)
      ->setCellValue("A" . $counter, 'Filter : ' . strtoupper($intra_extra));
    // $objPHPExcel->getActiveSheet()->mergeCells('B' . $counter . ':' . $last_alpabet . $counter);
    // $objPHPExcel->getActiveSheet()->getStyle('B' . $counter . ':' . $last_alpabet . $counter)
    //   ->getAlignment()
    //   ->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
    $objPHPExcel->setActiveSheetIndex(0)
      ->setCellValue('H' . $counter, $data['des_pemilik']);


    $counter = $counter + 2;
    $start_row_table = $counter;

    $first_row_header = $counter;
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A' . $counter, 'NO');

    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('B' . $counter, 'OBJEK');
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('C' . $counter, 'URAIAN');
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('D' . $counter, 'SEMESTER 1');
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('I' . $counter, 'SEMESTER 2');
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('N' . $counter, 'TOTAL PENYUSUTAN');
    
    $objPHPExcel->getActiveSheet()->mergeCells('D' . $counter . ':H' . $counter);
    $objPHPExcel->getActiveSheet()->mergeCells('I' . $counter . ':M' . $counter);
    // $objPHPExcel->getActiveSheet()->mergeCells('N' . $counter . ':O' . $counter);

    $counter = $counter + 1;
    $last_row_header = $counter;
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('D' . $counter, 'JUMLAH BARANG');
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('E' . $counter, 'JUMLAH HARGA');
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('F' . $counter, 'NILAI PENYUSUTAN');
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('G' . $counter, 'AKUMULASI PENYUSUTAN');
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('H' . $counter, 'NILAI BUKU');
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('I' . $counter, 'JUMLAH BARANG');
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('J' . $counter, 'JUMLAH HARGA');
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('K' . $counter, 'NILAI PENYUSUTAN');
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('L' . $counter, 'AKUMULASI PENYUSUTAN');
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('M' . $counter, 'NILAI BUKU');


    $objPHPExcel->getActiveSheet()->mergeCells('A' . $first_row_header . ':A' . $last_row_header);
    $objPHPExcel->getActiveSheet()->mergeCells('B' . $first_row_header . ':B' . $last_row_header);
    $objPHPExcel->getActiveSheet()->mergeCells('C' . $first_row_header . ':C' . $last_row_header);
    $objPHPExcel->getActiveSheet()->mergeCells('N' . $first_row_header . ':N' . $last_row_header);

    $objPHPExcel->getActiveSheet()->getStyle("A" . $first_row_header . ":N" .  $last_row_header)
      ->applyFromArray($table_header)
      ->getFont()->setSize(12);

    $ex = $objPHPExcel->setActiveSheetIndex(0);
    $counter = 11;
    
    $t_jumlah1 = 0;
    $t_harga1  = 0;
    $t_penyusutan1= 0;
    $t_akumulasi1 = 0;
    $t_nilaibuku1 = 0;
    $t_jumlah2 = 0;
    $t_harga2  = 0;
    $t_penyusutan2= 0;
    $t_akumulasi2 = 0;
    $t_nilaibuku2 = 0;

    $penyusutan = array();
    
    foreach ($data['rekap_penyusutan2'] as $key => $value) {
      $penyusutan[$key]['NO'] = $value['NO'];
      $penyusutan[$key]['objek'] = $value['objek'];
      $penyusutan[$key]['status'] = $value['status'];
      $penyusutan[$key]['nama_barang'] = $value['nama_barang'];
      $penyusutan[$key]['total_jumlah1'] = $value['total_jumlah'];
      $penyusutan[$key]['total_harga1'] = $value['total_harga'];
      $penyusutan[$key]['nilai_penyusutan1'] = $value['nilai_penyusutan'];
      $penyusutan[$key]['akumulasi_penyusutan1'] = $value['akumulasi_penyusutan'];
      $penyusutan[$key]['nilai_buku1'] = $value['nilai_buku'];
    }
    foreach ($data['rekap_penyusutan'] as $key => $value) {
      $penyusutan[$key]['total_jumlah2'] = $value['total_jumlah'];
      $penyusutan[$key]['total_harga2'] = $value['total_harga'];
      $penyusutan[$key]['nilai_penyusutan2'] = $value['nilai_penyusutan'];
      $penyusutan[$key]['akumulasi_penyusutan2'] = $value['akumulasi_penyusutan'];
      $penyusutan[$key]['nilai_buku2'] = $value['nilai_buku'];
    }

  //  die(json_encode($penyusutan)); die;

    foreach ($penyusutan as $value) {

      $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A' . $counter, $value['NO']);
      $objPHPExcel->setActiveSheetIndex(0)->setCellValue('B' . $counter, $value['objek']);
      $objPHPExcel->setActiveSheetIndex(0)->setCellValueExplicit('C' . $counter, $value['nama_barang']);
      $objPHPExcel->setActiveSheetIndex(0)->setCellValueExplicit('D' . $counter, $value['total_jumlah1'], PHPExcel_Cell_DataType::TYPE_NUMERIC);
      $objPHPExcel->setActiveSheetIndex(0)->setCellValueExplicit('E' . $counter, $value['total_harga1'], PHPExcel_Cell_DataType::TYPE_NUMERIC);
      $objPHPExcel->setActiveSheetIndex(0)->setCellValueExplicit('F' . $counter, $value['nilai_penyusutan1'], PHPExcel_Cell_DataType::TYPE_NUMERIC);
      $objPHPExcel->setActiveSheetIndex(0)->setCellValueExplicit('G' . $counter, $value['akumulasi_penyusutan1'], PHPExcel_Cell_DataType::TYPE_NUMERIC);
      $objPHPExcel->setActiveSheetIndex(0)->setCellValueExplicit('H' . $counter, $value['nilai_buku1'], PHPExcel_Cell_DataType::TYPE_NUMERIC);
      $objPHPExcel->setActiveSheetIndex(0)->setCellValueExplicit('I' . $counter, $value['total_jumlah2'], PHPExcel_Cell_DataType::TYPE_NUMERIC);
      $objPHPExcel->setActiveSheetIndex(0)->setCellValueExplicit('J' . $counter, $value['total_harga2'], PHPExcel_Cell_DataType::TYPE_NUMERIC);
      $objPHPExcel->setActiveSheetIndex(0)->setCellValueExplicit('K' . $counter, $value['nilai_penyusutan2'], PHPExcel_Cell_DataType::TYPE_NUMERIC);
      $objPHPExcel->setActiveSheetIndex(0)->setCellValueExplicit('L' . $counter, $value['akumulasi_penyusutan2'], PHPExcel_Cell_DataType::TYPE_NUMERIC);
      $objPHPExcel->setActiveSheetIndex(0)->setCellValueExplicit('M' . $counter, $value['nilai_buku2'], PHPExcel_Cell_DataType::TYPE_NUMERIC);
      $objPHPExcel->setActiveSheetIndex(0)->setCellValueExplicit('N' . $counter, "=F".$counter."+K".$counter."", PHPExcel_Cell_DataType::TYPE_NUMERIC);
      
    $objPHPExcel->getActiveSheet()->getStyle('E'.$counter)->getNumberFormat()->setFormatCode('#,##0.00');
    $objPHPExcel->getActiveSheet()->getStyle('F'.$counter)->getNumberFormat()->setFormatCode('#,##0.00');
    $objPHPExcel->getActiveSheet()->getStyle('G'.$counter)->getNumberFormat()->setFormatCode('#,##0.00');
    $objPHPExcel->getActiveSheet()->getStyle('H'.$counter)->getNumberFormat()->setFormatCode('#,##0.00');
    // $objPHPExcel->getActiveSheet()->getStyle('I'.$counter)->getNumberFormat()->setFormatCode('#,##0.00');
    $objPHPExcel->getActiveSheet()->getStyle('J'.$counter)->getNumberFormat()->setFormatCode('#,##0.00');
    $objPHPExcel->getActiveSheet()->getStyle('K'.$counter)->getNumberFormat()->setFormatCode('#,##0.00');
    $objPHPExcel->getActiveSheet()->getStyle('L'.$counter)->getNumberFormat()->setFormatCode('#,##0.00');
    $objPHPExcel->getActiveSheet()->getStyle('M'.$counter)->getNumberFormat()->setFormatCode('#,##0.00');
    $objPHPExcel->getActiveSheet()->getStyle('N'.$counter)->getNumberFormat()->setFormatCode('#,##0.00');

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

      
      if($value['status'] == '1') {
          $t_jumlah1 += $value['total_jumlah1'];
          $t_harga1 += $value['total_harga1'];
          $t_penyusutan1  += $value['nilai_penyusutan1'];
          $t_akumulasi1 += $value['akumulasi_penyusutan1'];
          $t_nilaibuku1 += $value['nilai_buku1'];
          $t_jumlah2 += $value['total_jumlah2'];
          $t_harga2 += $value['total_harga2'];
          $t_penyusutan2  += $value['nilai_penyusutan2'];
          $t_akumulasi2 += $value['akumulasi_penyusutan2'];
          $t_nilaibuku2 += $value['nilai_buku2'];
      }


      $counter = $counter + 1;
    }
    

    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A' . $counter, 'Jumlah');
    $objPHPExcel->setActiveSheetIndex(0)->setCellValueExplicit('D' . $counter, $t_jumlah1);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValueExplicit('E' . $counter, $t_harga1, PHPExcel_Cell_DataType::TYPE_NUMERIC);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValueExplicit('F' . $counter, $t_penyusutan1, PHPExcel_Cell_DataType::TYPE_NUMERIC);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValueExplicit('G' . $counter, $t_akumulasi1, PHPExcel_Cell_DataType::TYPE_NUMERIC);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValueExplicit('H' . $counter, $t_nilaibuku1, PHPExcel_Cell_DataType::TYPE_NUMERIC);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValueExplicit('I' . $counter, $t_jumlah2);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValueExplicit('J' . $counter, $t_harga2, PHPExcel_Cell_DataType::TYPE_NUMERIC);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValueExplicit('K' . $counter, $t_penyusutan2, PHPExcel_Cell_DataType::TYPE_NUMERIC);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValueExplicit('L' . $counter, $t_akumulasi2, PHPExcel_Cell_DataType::TYPE_NUMERIC);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValueExplicit('M' . $counter, $t_nilaibuku2, PHPExcel_Cell_DataType::TYPE_NUMERIC);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValueExplicit('N' . $counter, "=F".$counter."+K".$counter, PHPExcel_Cell_DataType::TYPE_NUMERIC);

    $objPHPExcel->getActiveSheet()->getStyle('E'.$counter)->getNumberFormat()->setFormatCode('#,##0.00');
    $objPHPExcel->getActiveSheet()->getStyle('F'.$counter)->getNumberFormat()->setFormatCode('#,##0.00');
    $objPHPExcel->getActiveSheet()->getStyle('G'.$counter)->getNumberFormat()->setFormatCode('#,##0.00');
    $objPHPExcel->getActiveSheet()->getStyle('H'.$counter)->getNumberFormat()->setFormatCode('#,##0.00');
    // $objPHPExcel->getActiveSheet()->getStyle('I'.$counter)->getNumberFormat()->setFormatCode('#,##0.00');
    $objPHPExcel->getActiveSheet()->getStyle('J'.$counter)->getNumberFormat()->setFormatCode('#,##0.00');
    $objPHPExcel->getActiveSheet()->getStyle('K'.$counter)->getNumberFormat()->setFormatCode('#,##0.00');
    $objPHPExcel->getActiveSheet()->getStyle('L'.$counter)->getNumberFormat()->setFormatCode('#,##0.00');
    $objPHPExcel->getActiveSheet()->getStyle('M'.$counter)->getNumberFormat()->setFormatCode('#,##0.00');
    $objPHPExcel->getActiveSheet()->getStyle('N'.$counter)->getNumberFormat()->setFormatCode('#,##0.00');
    
    $objPHPExcel->getActiveSheet()->mergeCells('A' . $counter . ':C' . $counter);

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
      if ($value == "total_harga" or $value == "nilai_penyusutan" or $value == "akumulasi_penyusutan" or $value == "nilai_buku") {
        $cell = $alphabet[$key] . "1:" . $alphabet[$key] . $counter;
        $objPHPExcel->getActiveSheet()->getStyle($cell)->getNumberFormat()->setFormatCode('#,##0.00');
      }elseif($value == "total_jumlah" ){
        $cell = $alphabet[$key] . "1:" . $alphabet[$key] . $counter;
        $objPHPExcel->getActiveSheet()->getStyle($cell)->getNumberFormat()->setFormatCode('#,##0');
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
      ->setTitle("Export Data Rekap. Penyusutan Barang")
      ->setSubject("Export To Excel")
      ->setDescription("Doc for Office 2007 XLSX, generated by PHPExcel.")
      ->setKeywords("office 2007 openxml php")
      ->setCategory("PHPExcel");
    $objPHPExcel->getActiveSheet()->setTitle('Data Rekap. Penysutan Barang');

    $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
    header('Last-Modified:' . gmdate("D, d M Y H:i:s") . 'GMT');
    header('Chace-Control: no-store, no-cache, must-revalation');
    header('Chace-Control: post-check=0, pre-check=0', FALSE);
    header('Pragma: no-cache');
    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment;filename="Data Rekap. Penyusutan Barang ' . date('Y-m-d') . '.xlsx"');

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
    // $data['penyusutan'] = $this->penyusutan_rekap_model->get_all($data);

    $html = $this->load->view('/penyusutan_rekap/report_pdf', $data, true);
    $this->pdfgenerator->generate($html, 'Rekap Penyusutan Barang ' . tgl_indo(date('Y-m-d')), true, 'folio', 'landscape');
    // $this->load->view('/penyusutan/report_pdf', $data);

  }
}
