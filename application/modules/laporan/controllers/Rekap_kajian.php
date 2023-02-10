    <?php

    if (!defined('BASEPATH')) {
      exit('No direct script access allowed');
    }

    class Rekap_kajian extends CI_Controller
    {
      public function __construct()
      {
        parent::__construct();
        $this->global_model->cek_hak_akses('18');
        $this->load->model('Rekap_atb_model');
        $this->load->model('laporan_model');
        $this->load->model('stock_opname_kib_model');
        $this->load->helper('my_global');
        $this->load->library('Global_library');
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
        // $data['kib'] = $this->global_model->kode_jenis;
        $data['kib'] = null;
        // die(json_encode($data['kelompok_list']));

        $data['pemilik'] = $this->global_model->get_pemilik();
        $data['intra_ekstra'] = array(
          '00' => 'Semua',
          '01' => 'Intrakomptable',
          '02' => 'Extrakomptable',
        );

        $session = $this->session->userdata('session');
        $lokasi_explode = $this->global_model->get_kode_lokasi_by_id($session->id_kode_lokasi);

        $data['pengguna_list'] = $this->global_model->get_pengguna();
        // die(json_encode($data['pengguna_list']));
        $data['pengguna'] = $this->global_model->get_view_pengguna($lokasi_explode->pengguna);
        // if (empty($data['pengguna'])) $data['pengguna']->id_pengguna = "";

        // die(json_encode($lokasi_explode->pengguna));
        if ($data['pengguna']) {
          $data['kuasa_pengguna_list'] = $this->laporan_model->get_kuasa_pengguna($data['pengguna']->id_pengguna);
          $data['kuasa_pengguna'] = $this->global_model->get_view_kuasa_pengguna($lokasi_explode->pengguna, $lokasi_explode->kuasa_pengguna);
          if ($data['kuasa_pengguna']) {
            $data['sub_kuasa_pengguna_list'] = $this->laporan_model->get_sub_kuasa_pengguna($data['kuasa_pengguna']->id_pengguna, $data['kuasa_pengguna']->id_kuasa_pengguna);
            $data['sub_kuasa_pengguna'] = $this->global_model->get_view_sub_kuasa_pengguna($lokasi_explode->pengguna, $lokasi_explode->kuasa_pengguna, $lokasi_explode->sub_kuasa_pengguna);
          }
        }

        $data['content'] = $this->load->view('rekap_kajian/home', $data, true);
        $data['breadcrumb'] = array(
          array('label' => 'Lampiran LKPD', 'url' => '#', 'icon' => 'fa fa-pie-chart', 'li_class' => ''),
          array('label' => 'Kajian', 'url' => '#', 'icon' => '', 'li_class' => 'active'),
          // array('label' => 'Buat', 'url' => '#','icon'=>'', 'li_class'=>'active'),
        );
        $this->load->view('template', $data);
      }

      public function excel()
      {
        $data['kode_barang'] = $this->input->post('kode_jenis');
        $kode = $this->global_library->explode_kode_barang($data['kode_barang']);
        if ($kode['kode_kelompok'] == '5')
          $data['kode_jenis'] = $kode['kode_kelompok'] . "." . $kode['kode_jenis'];
        elseif ($kode['kode_kelompok'] == '3')
          $data['kode_jenis'] = $kode['kode_jenis'];

        // die($data['kode_jenis']);
		 $data['mulai'] = $this->input->post('mulai');
        $data['id_pemilik'] = $this->input->post('pemilik');
        $data['id_pengguna'] = $this->input->post('id_pengguna');
        $data['id_kuasa_pengguna'] = $this->input->post('id_kuasa_pengguna');
        $data['id_sub_kuasa_pengguna'] = $this->input->post('id_sub_kuasa_pengguna');
        $data['intra_ekstra'] = $this->input->post('intra_ekstra');
        $data['atb'] = $this->input->post('atb');
        // die(json_encode($_POST));
        
        $data['start_date'] = date('Y-m-d', tgl_inter("1 " . $this->input->post('bulan_tahun')));
        $data['last_date'] = date('Y-m-d', tgl_inter("31 " . $this->input->post('last_bulan_tahun')));
        
        if($this->input->post('bulan_tahun') == ''){
          $data['start_date'] = '';
        }
        

        $data['intra_ekstra'] = $this->input->post('intra_ekstra');
        $data['format'] = $this->input->post('format');

         
    $data['field_name'] = array(
      // 'no',  'kode_barang',  'nomor_register',  'nama_barang',  'merk_type', 'tanggal_pembelian', 'umur_bulan', 'umur_ekonomis', 'jumlah_barang', 'nilai_perolehan', 'nilai_penyusutan', 'akumulasi_penyusutan', 'nilai_buku', 'instansi',
      'no',  'kode_barang',  'nomor_register',  'nama_barang', 'judul_kajian_nama_software',  'tanggal_perolehan', 'umur_bulan', 'umur_ekonomis_p', 'harga', 'nilai_penyusutan', 'akumulasi_penyusutan', 'nilai_buku', 'instansi',
      // jumlah barang hilang
    );
    $data['label'] = array(
      // 'No',  'Kode Barang',  'Nomor Register',  'Nama Barang / Uraian',  'Merk / Type', 'Tanggal_pembelian/Pengadaan', 'umur_bulan', 'umur_ekonomis', 'Jumlah Barang', 'Nilai Perolehan', 'Nilai Penyusutan', 'Akumulasi Penyusutan', 'Nilai Buku', 'Unit Kerja',
      'No',  'Kode Barang',  'Nomor Register',  'Nama Barang / Uraian', 'Judul' ,  'Tanggal_pembelian/Pengadaan', 'Umur Barang', 'Umur Ekonomis', 'Harga', 'Nilai Penyusutan', 'Akumulasi Penyusutan', 'Nilai Buku', 'Unit Kerja',
    );
       
        
        // $session = $this->session->userdata('session');
        // if($session->id_upik == 'JSS-A0149'){
        //   die(json_encode($data['id_kuasa_pengguna']));
        // }

        $id_kode_lokasi = null;
        // if ($data['id_pengguna']) {

        if ($data['id_sub_kuasa_pengguna'] != '') { //sub lokasi
          $id_kode_lokasi = $data['id_sub_kuasa_pengguna'];
          array_pop($data['field_name']);
          array_pop($data['label']);
        } else if ($data['id_kuasa_pengguna'] != '') { //lokasi
          $id_kode_lokasi = $data['id_kuasa_pengguna'];
        } else if ($data['id_pengguna'] != '') { //skpd
          $id_kode_lokasi = $data['id_pengguna'];
        }
        // }else {
        //   $id_kode_lokasi = $this->ses
        // }
        //tertanda
        $tertanda = $this->laporan_model->get_tertanda($id_kode_lokasi);
        $data['tertanda'] = $tertanda->result();

        $result_pemilik = $this->global_model->get_pemilik_by_id($data['id_pemilik']);
        $data['des_pemilik'] = $result_pemilik->kode . ' - ' . $result_pemilik->nama;
        $result_lokasi = $this->laporan_model->get_lokasi_by_id($id_kode_lokasi);
        // die($this->session->userdata('session')->id_kode_lokasi);
        $juni = 'Juni '.date('Y', strtotime($data['last_date']));
          
        if(date('m', strtotime($data['last_date'])) > 6 && $this->input->post('last_bulan_tahun') != $juni){
          $semester = '2';
        }else{
          $semester = '1';
        }
        $backup_kib = $this->stock_opname_kib_model->get_by_lokasi($result_lokasi->pengguna,$semester,date('Y', strtotime($data['last_date'])));
        

        if($backup_kib->num_rows() > 0){
          $data['prefix'] = "_".date('Y', strtotime($data['last_date']))."_s".$semester;
        }else{
          $data['prefix'] = "";
        }
        
  // $session = $this->session->userdata('session');
  // if($session->id_upik == 'jss-a7324'){
  //   die($data['prefix']);
  // }

        $kode_lokasi = $result_pemilik->kode . '.' . $result_lokasi->kode_lokasi;
        $data['kode_lokasi'] = get_intra_extra(remove_star($kode_lokasi), $data['intra_ekstra']);
        $data['nama_lokasi'] = ' - ' . $result_lokasi->instansi;
        // die(json_encode($data['kode_jenis']));
        if ($data['format'] == 'pdf') {
          $this->toPdf($data);
        } else if ($data['format'] == 'excel') {
            $this->toExcel($data);
        }
      }

      public function toExcel($data = null)
      {
        ini_set('memory_limit', '1024M');
        $data['list_intra_ekstra'] = array(
          '00' => 'Semua',
          '01' => 'Intrakomptable',
          '02' => 'Extrakomptable',
        );
        $intra_extra = $data['list_intra_ekstra'][$data['intra_ekstra']];

        $jenis = $this->global_model->kode_jenis[$data['kode_jenis']];
        $field_name = $data['field_name'];
        $label = $data['label'];
        // die(json_encode($field_name));
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
            'name' => 'Verdana',
          ),
        );

        $table_header = array(
          'alignment' => array(
            'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
            'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,
          ),
          'font' => array(
            'bold' => true,
          ),

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
              'style' => PHPExcel_Style_Border::BORDER_THIN,
            ),
          ),
        );

        $objPHPExcel->getActiveSheet()->getStyle("A1:F2")
          ->applyFromArray($header)
          ->getFont()->setSize(13);
        $objPHPExcel->getActiveSheet()->getStyle("A4:F5")
          ->getFont()->setSize(12);
        $objPHPExcel->getActiveSheet()->mergeCells('A1:' . $last_alpabet . '1');
        $objPHPExcel->getActiveSheet()->mergeCells('A2:' . $last_alpabet . '2');
        $objPHPExcel->getActiveSheet()->mergeCells('A3:' . $last_alpabet . '3');

        $col1 = $itemcount / 2;
        $col2 = $col1;
        $col1 = $col1 - 1;

        $objPHPExcel->getActiveSheet()->mergeCells('A4:' . $alphabet[$col1] . '4');
        $objPHPExcel->getActiveSheet()->mergeCells($alphabet[$col2] . '4:' . $last_alpabet . '4');
        $objPHPExcel->getActiveSheet()->mergeCells('A5:' . $alphabet[$col1] . '5');
        $objPHPExcel->getActiveSheet()->mergeCells($alphabet[$col2] . '5:' . $last_alpabet . '5');


        $objPHPExcel->setActiveSheetIndex(0)
          ->setCellValue('A1', 'KARTU INVENTARIS BARANG (KIB)')
          ->setCellValue('A2', 'KAJIAN')
          ->setCellValue('A4', 'No. Kode Lokasi : ' . $data['kode_lokasi'] . $data['nama_lokasi'])
          ->setCellValue('A5', 'Filter : ' . strtoupper($intra_extra))
          ->setCellValue($alphabet[$col2] . '5', $data['des_pemilik']);

        $objPHPExcel->getActiveSheet()
          ->getStyle($alphabet[$col2] . '5')
          ->getAlignment()
          ->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

        //header
          
    $counter = 8;
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
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('E' . $counter, 'Judul');


    $objPHPExcel->getActiveSheet()->mergeCells('F' . $first_row_header . ':F' . $last_row_header);
    $objPHPExcel->getActiveSheet()->mergeCells('G' . $first_row_header . ':G' . $last_row_header);
    $objPHPExcel->getActiveSheet()->mergeCells('H' . $first_row_header . ':H' . $last_row_header);
    $objPHPExcel->getActiveSheet()->mergeCells('I' . $first_row_header . ':I' . $last_row_header);
    $objPHPExcel->getActiveSheet()->mergeCells('J' . $first_row_header . ':J' . $last_row_header);
    $objPHPExcel->getActiveSheet()->mergeCells('K' . $first_row_header . ':K' . $last_row_header);
    $objPHPExcel->getActiveSheet()->mergeCells('L' . $first_row_header . ':L' . $last_row_header);
    $objPHPExcel->getActiveSheet()->mergeCells('M' . $first_row_header . ':M' . $last_row_header);

    $objPHPExcel->getActiveSheet()->getStyle("A" . $first_row_header . ":" . $last_alpabet . $last_row_header)
      ->applyFromArray($table_header)
      ->getFont()->setSize(12);
    //end header
    
    $counter = $counter + 1;
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A' . $counter, 1);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('B' . $counter, 2);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('C' . $counter, 3);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('D' . $counter, 4);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('E' . $counter, 5);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('F' . $counter, 6);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('G' . $counter, 7);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('H' . $counter, 8);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('I' . $counter, 9);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('J' . $counter, 10);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('K' . $counter, 11);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('L' . $counter, 12);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('M' . $counter, 12);

        $ex = $objPHPExcel->setActiveSheetIndex(0);
        $counter = $counter + 1;
		if($data['mulai']){
		$mulai = $data['mulai'];}
		else{
			$mulai = 0;
		}
    
    $no = 1;
    $t_perolehan = 0;
    $t_penyusutan  = 0;
    $t_akumulasi= 0;
    $t_nilaibuku = 0;
    $st_perolehan = 0;
    $st_penyusutan  = 0;
    $st_akumulasi= 0;
    $st_nilaibuku = 0;
		
    $no = $mulai == 0 ? $mulai +1 : $mulai;
        $kib = $this->Rekap_atb_model->get_atb($data,$mulai);

        // die($this->db->last_query());
        // die(json_encode($kib)); die;

        foreach ($kib as $value) {
          if($value['LEVEL'] == '1'){
            if($st_perolehan != 0){
              $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A' . $counter, 'Sub Jumlah');
              $objPHPExcel->setActiveSheetIndex(0)->setCellValueExplicit('I' . $counter, $st_perolehan, PHPExcel_Cell_DataType::TYPE_NUMERIC);
              $objPHPExcel->setActiveSheetIndex(0)->setCellValueExplicit('J' . $counter, $st_penyusutan, PHPExcel_Cell_DataType::TYPE_NUMERIC);
              $objPHPExcel->setActiveSheetIndex(0)->setCellValueExplicit('K' . $counter, $st_akumulasi, PHPExcel_Cell_DataType::TYPE_NUMERIC);
              $objPHPExcel->setActiveSheetIndex(0)->setCellValueExplicit('L' . $counter, $st_nilaibuku, PHPExcel_Cell_DataType::TYPE_NUMERIC);
          
              $objPHPExcel->getActiveSheet()->getStyle('I'.$counter)->getNumberFormat()->setFormatCode('#,##0.00');
              $objPHPExcel->getActiveSheet()->getStyle('J'.$counter)->getNumberFormat()->setFormatCode('#,##0.00');
              $objPHPExcel->getActiveSheet()->getStyle('K'.$counter)->getNumberFormat()->setFormatCode('#,##0.00');
              $objPHPExcel->getActiveSheet()->getStyle('L'.$counter)->getNumberFormat()->setFormatCode('#,##0.00');
              $objPHPExcel->getActiveSheet()->mergeCells('A' . $counter . ':C' . $counter);
              
            $objPHPExcel->getActiveSheet()->getStyle("A" . $counter . ":" . $last_alpabet . $counter)
            ->applyFromArray(
              array('font' => array(
                'bold' => true,
              )))
            ->getFont();
              
            $counter = $counter + 2;
            }

            $ex->setCellValue( "A". $counter, "SKPD :");
            $ex->setCellValue( "D". $counter, $value['instansi']);
            $objPHPExcel->getActiveSheet()->mergeCells('A' . $counter . ':C' . $counter);
            $objPHPExcel->getActiveSheet()->getStyle("A" . $counter . ":" . $last_alpabet . $counter)
              ->applyFromArray(
                array('font' => array(
                  'bold' => true,
                )))
              ->getFont();
              
    $st_perolehan = 0;
    $st_penyusutan  = 0;
    $st_akumulasi= 0;
    $st_nilaibuku = 0;
              
          }else{

            foreach ($alphabet as $key => $v_alphabet) {
              if (($field_name[$key] == 'tanggal_perolehan') and ($value[$field_name[$key]] != '')) {
                $ex->setCellValue($v_alphabet . $counter, tgl_indo($value[$field_name[$key]]));
                // die($value[$field_name[$key]]);
              }
              // else if (($field_name[$key] == 'tanggal_perolehan') and ($value[$field_name[$key]] != '')){
              //   $ex->setCellValue($v_alphabet.$counter, date('Y',strtotime($value[$field_name[$key]]))   );
              // }
              else if (($field_name[$key] == 'umur_bulan' or $field_name[$key] == 'umur_ekonomis' or  $field_name[$key] == 'harga' or $field_name[$key] == 'nilai_penyusutan' or
                $field_name[$key] == 'akumulasi_penyusutan' or $field_name[$key] == 'nilai_buku') and ($value[$field_name[$key]] == '0')) {
                  $ex->setCellValue($v_alphabet . $counter, '0');
              } else if ($field_name[$key] == "nomor_register") {
                $temp_register =  explode(',', $value[$field_name[$key]]);
                $nomor_register = cek_register_kosong($temp_register);
                $ex->setCellValue($v_alphabet . $counter, $nomor_register);
              }else if (($field_name[$key] == 'no')) {
                $ex->setCellValue($v_alphabet . $counter, $no++);
              }else if (($field_name[$key] == 'harga')) {
                $ex->setCellValueExplicit('I' . $counter,  $value[$field_name[$key]], PHPExcel_Cell_DataType::TYPE_NUMERIC);
                $ex->getStyle('I'.$counter)->getNumberFormat()->setFormatCode('#,##0.00');
              }
              // elseif ($field_name[$key] == "sertifikat_nomor") {
              //   $objPHPExcel->getActiveSheet()->setCellValueExplicit($v_alphabet.$counter, $value[$field_name[$key]], PHPExcel_Cell_DataType::TYPE_STRING);
              //   // $ex->setCellValue($v_alphabet.$counter, "'".$value[$field_name[$key]]);
              // }
              else {
                $ex->setCellValue($v_alphabet . $counter, $value[$field_name[$key]]);
              }
              
      
              if($field_name[$key] == 'harga'){
                $t_perolehan += $value[$field_name[$key]];
                $st_perolehan += $value[$field_name[$key]];
              }
              if($field_name[$key] == 'nilai_penyusutan'){
                $t_penyusutan  += $value[$field_name[$key]];
                $st_penyusutan  += $value[$field_name[$key]];
              }
              if($field_name[$key] == 'akumulasi_penyusutan'){
                $t_akumulasi += $value[$field_name[$key]];
                $st_akumulasi += $value[$field_name[$key]];
              }
              if($field_name[$key] == 'nilai_buku'){
                $t_nilaibuku += $value[$field_name[$key]];
                $st_nilaibuku += $value[$field_name[$key]];
              }
            }
          }
            $counter = $counter + 1;
        }

        if($st_perolehan != 0){
          $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A' . $counter, 'Sub Jumlah');
          $objPHPExcel->setActiveSheetIndex(0)->setCellValueExplicit('I' . $counter, $st_perolehan, PHPExcel_Cell_DataType::TYPE_NUMERIC);
          $objPHPExcel->setActiveSheetIndex(0)->setCellValueExplicit('J' . $counter, $st_penyusutan, PHPExcel_Cell_DataType::TYPE_NUMERIC);
          $objPHPExcel->setActiveSheetIndex(0)->setCellValueExplicit('K' . $counter, $st_akumulasi, PHPExcel_Cell_DataType::TYPE_NUMERIC);
          $objPHPExcel->setActiveSheetIndex(0)->setCellValueExplicit('L' . $counter, $st_nilaibuku, PHPExcel_Cell_DataType::TYPE_NUMERIC);
      
          $objPHPExcel->getActiveSheet()->getStyle('I'.$counter)->getNumberFormat()->setFormatCode('#,##0.00');
          $objPHPExcel->getActiveSheet()->getStyle('J'.$counter)->getNumberFormat()->setFormatCode('#,##0.00');
          $objPHPExcel->getActiveSheet()->getStyle('K'.$counter)->getNumberFormat()->setFormatCode('#,##0.00');
          $objPHPExcel->getActiveSheet()->getStyle('L'.$counter)->getNumberFormat()->setFormatCode('#,##0.00');
          $objPHPExcel->getActiveSheet()->mergeCells('A' . $counter . ':C' . $counter);
          
        $objPHPExcel->getActiveSheet()->getStyle("A" . $counter . ":" . $last_alpabet . $counter)
        ->applyFromArray(
          array('font' => array(
            'bold' => true,
          )))
        ->getFont();
          
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
          if (in_array($columnID, array('F','G', 'H', 'I', 'J', 'K', 'L')))
            $objPHPExcel->getActiveSheet()->getColumnDimension($columnID)->setWidth(20);
          else
            $objPHPExcel->getActiveSheet()->getColumnDimension($columnID)->setAutoSize(true);
        }
    
        // foreach ($field_name as $key => $value) :
        //   if (
        //     $value == 'nilai_perolehan' or $value == 'nilai_penyusutan' or
        //     $value == 'akumulasi_penyusutan' or $value == 'nilai_buku'
        //   ) {
        //     $cell = $alphabet[$key] . "1:" . $alphabet[$key] . $counter;
        //     $objPHPExcel->getActiveSheet()->getStyle($cell)->getNumberFormat()->setFormatCode('#,##0.00');
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
          $objPHPExcel->setActiveSheetIndex(0)->setCellValue($alphabet[0] . $counter, 'Mengetahui');
          $objPHPExcel->setActiveSheetIndex(0)->setCellValue($alphabet[$start_col3] . $counter, 'Yogyakarta, ' . tgl_indo(date('Y-m-d')));

          $counter = $counter + 1;
          $objPHPExcel->getActiveSheet()->mergeCells($alphabet[0] . $counter . ':' . $alphabet[$last_col1] . $counter);
          $objPHPExcel->getActiveSheet()->mergeCells($alphabet[$start_col2] . $counter . ':' . $alphabet[$last_col2] . $counter);
          $objPHPExcel->getActiveSheet()->mergeCells($alphabet[$start_col3] . $counter . ':' . $alphabet[$last_col] . $counter);
          if (isset($data['tertanda'][0])) {
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue($alphabet[0] . $counter, $data['tertanda'][0]->jabatan);
          }

          if (isset($data['tertanda'][1])) {
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue($alphabet[$start_col2] . $counter, $data['tertanda'][1]->jabatan);
          }

          if (isset($data['tertanda'][2])) {
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue($alphabet[$start_col3] . $counter, $data['tertanda'][2]->jabatan);
          }

          $counter = $counter + 4;
          $objPHPExcel->getActiveSheet()->mergeCells($alphabet[0] . $counter . ':' . $alphabet[$last_col1] . $counter);
          $objPHPExcel->getActiveSheet()->mergeCells($alphabet[$start_col2] . $counter . ':' . $alphabet[$last_col2] . $counter);
          $objPHPExcel->getActiveSheet()->mergeCells($alphabet[$start_col3] . $counter . ':' . $alphabet[$last_col] . $counter);
          if (isset($data['tertanda'][0])) {
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue($alphabet[0] . $counter, '( ' . $data['tertanda'][0]->nama . ' )');
          }

          if (isset($data['tertanda'][1])) {
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue($alphabet[$start_col2] . $counter, '( ' . $data['tertanda'][1]->nama . ' )');
          }

          if (isset($data['tertanda'][2])) {
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue($alphabet[$start_col3] . $counter, '( ' . $data['tertanda'][2]->nama . ' )');
          }

          $counter = $counter + 1;
          $objPHPExcel->getActiveSheet()->mergeCells($alphabet[0] . $counter . ':' . $alphabet[$last_col1] . $counter);
          $objPHPExcel->getActiveSheet()->mergeCells($alphabet[$start_col2] . $counter . ':' . $alphabet[$last_col2] . $counter);
          $objPHPExcel->getActiveSheet()->mergeCells($alphabet[$start_col3] . $counter . ':' . $alphabet[$last_col] . $counter);
          if (isset($data['tertanda'][0])) {
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue($alphabet[0] . $counter, 'NIP. ' . $data['tertanda'][0]->nip);
          }

          if (isset($data['tertanda'][1])) {
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue($alphabet[$start_col2] . $counter, 'NIP. ' . $data['tertanda'][1]->nip);
          }

          if (isset($data['tertanda'][2])) {
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue($alphabet[$start_col3] . $counter, 'NIP. ' . $data['tertanda'][2]->nip);
          }
        }



        $objPHPExcel->getProperties()->setCreator("Kajian")
          ->setLastModifiedBy("Kajian")
          ->setTitle("Export Data Kajian")
          ->setSubject("Export Kajian To Excel")
          ->setDescription("Doc for Office 2007 XLSX, generated by PHPExcel.")
          ->setKeywords("office 2007 openxml php")
          ->setCategory("PHPExcel");
        $objPHPExcel->getActiveSheet()->setTitle('Data Kajian');

        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
        ob_end_clean(); // MENGHILANGKAN ERROR CANNOT OPEN
        header('Last-Modified:' . gmdate("D, d M Y H:i:s") . 'GMT');
        header('Chace-Control: no-store, no-cache, must-revalation');
        header('Chace-Control: post-check=0, pre-check=0', false);
        header('Pragma: no-cache');
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="Export Data Kajian' . date('Y-m-d') . '.xlsx"');

        $objWriter->save('php://output');
        die();
      }


      public function toExcelLainya($data = null)
      {
        // $temp_register =  explode(',', "000001,000007");
        // $nomor_register = cek_register_kosong($temp_register);
        // echo $nomor_register;
        // echo json_encode($data);
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
          ->setCellValue('A' . $counter, 'LAPORAN KIB');
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
          ->setCellValue('A4', 'SKPD')
          ->setCellValue('D4', ': '. $this->getParentLokasi($data['kode_lokasi']) . $data['nama_lokasi'])

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
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A' . $counter, 'NOMOR');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('D' . $counter, 'SPESIFIKASI BARANG');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('G' . $counter, 'Bahan');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('H' . $counter, 'Asal/Cara Perolehan Barang');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('I' . $counter, 'Tahun Perolehan');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('J' . $counter, 'Ukuran Barang / konstrukdi (P,S,D)');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('K' . $counter, 'Satuan');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('L' . $counter, 'Kondisi (B,RR,RB)');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('M' . $counter, 'Jumlah');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('O' . $counter, 'Keterangan');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('P' . $counter, 'ID KIB');
        if (in_array('instansi', $data['field_name']))
          $objPHPExcel->setActiveSheetIndex(0)->setCellValue('Q' . $counter, 'Unit Kerja');

        $objPHPExcel->getActiveSheet()->mergeCells('A' . $counter . ':C' . $counter);
        $objPHPExcel->getActiveSheet()->mergeCells('D' . $counter . ':F' . $counter);
        $objPHPExcel->getActiveSheet()->mergeCells('M' . $counter . ':N' . $counter);


        $counter = $counter + 1;
        $last_row_header = $counter;
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A' . $counter, 'No Urt');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('B' . $counter, 'Kode Barang');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('C' . $counter, 'Register');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('D' . $counter, 'Nama / Jenis Barang');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('E' . $counter, 'Merk / Type');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('F' . $counter, 'No. Sertifikat, No. Pabrik, No Chasis, No. Mesin');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('M' . $counter, 'Barang');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('N' . $counter, 'Harga');


        $objPHPExcel->getActiveSheet()->mergeCells('G' . $first_row_header . ':G' . $last_row_header);
        $objPHPExcel->getActiveSheet()->mergeCells('H' . $first_row_header . ':H' . $last_row_header);
        $objPHPExcel->getActiveSheet()->mergeCells('I' . $first_row_header . ':I' . $last_row_header);
        $objPHPExcel->getActiveSheet()->mergeCells('J' . $first_row_header . ':J' . $last_row_header);
        $objPHPExcel->getActiveSheet()->mergeCells('K' . $first_row_header . ':K' . $last_row_header);
        $objPHPExcel->getActiveSheet()->mergeCells('L' . $first_row_header . ':L' . $last_row_header);
        $objPHPExcel->getActiveSheet()->mergeCells('O' . $first_row_header . ':O' . $last_row_header);

        $objPHPExcel->getActiveSheet()->mergeCells('P' . $first_row_header . ':P' . $last_row_header);
        $objPHPExcel->getActiveSheet()->mergeCells('Q' . $first_row_header . ':Q' . $last_row_header);

        $objPHPExcel->getActiveSheet()->getStyle("A" . $first_row_header . ":" . $last_alpabet . $last_row_header)
          ->applyFromArray($table_header)
          ->getFont()->setSize(12);
          
        $objPHPExcel->getActiveSheet()->getStyle("A6:R7")
        ->getAlignment()->setWrapText(true);
        //end header

        // ===============================

        $ex = $objPHPExcel->setActiveSheetIndex(0);
        $counter = $counter + 1;

        $inventaris = $this->Rekap_atb_model->get_kib_aset_lainya($data);
        // die(json_encode($field_name));
        $i = 0;
        $st_jumlah = 0;
        $st_harga = 0;
        $no = 1;

        // foreach ($jenis as $key) {
          
          foreach ($inventaris as $value) {
            if ($value['kode_jenis'] == '5.03') {
              foreach ($alphabet as $key => $v_alphabet) {
                if (($field_name[$key] == 'sertifikat_tanggal' or $field_name[$key] == 'gedung_tanggal' or $field_name[$key] == 'dokumen_tanggal' or $field_name[$key] == 'tanggal_mulai') and ($value[$field_name[$key]] != '')) {
                  $ex->setCellValue($v_alphabet . $counter, tgl_indo($value[$field_name[$key]]));
                  // die($value[$field_name[$key]]);
                } else if (($field_name[$key] == 'tanggal_perolehan') and ($value[$field_name[$key]] != '')) {
                  $ex->setCellValue($v_alphabet . $counter, date('Y', strtotime($value[$field_name[$key]])));
                } else if (($field_name[$key] == 'ukuran_barang') and ($value[$field_name[$key]] == '0')) {
                  $ex->setCellValue($v_alphabet . $counter, '');
                } else if ($field_name[$key] == "nomor_register") {
                  $temp_register =  explode(',', $value[$field_name[$key]]);
                  $nomor_register = cek_register_kosong($temp_register);
                  $ex->setCellValue($v_alphabet . $counter, $nomor_register);
                } elseif ($field_name[$key] == "sertifikat_nomor") {
                  $objPHPExcel->getActiveSheet()->setCellValueExplicit($v_alphabet . $counter, $value[$field_name[$key]], PHPExcel_Cell_DataType::TYPE_STRING);
                  // $ex->setCellValue($v_alphabet.$counter, "'".$value[$field_name[$key]]);
                }elseif ($field_name[$key] == "no") {
                  $objPHPExcel->getActiveSheet()->setCellValue($v_alphabet . $counter, $no++);
                  // $ex->setCellValue($v_alphabet.$counter, "'".$value[$field_name[$key]]);
                } else {
                  $ex->setCellValue($v_alphabet . $counter, $value[$field_name[$key]]);
                }

              }
              $st_jumlah++;
              $st_harga += $value['harga'];
              $i++;
              $counter++;
            }
          }

          if ($i != 0) {
            $ex->setCellValue('A' . $counter, 'Jumlah KIB ATB');
            $ex->setCellValue('M' . $counter, $st_jumlah);
            $ex->setCellValue('N' . $counter, $st_harga);
            $counter++;

            $st_jumlah = 0;
            $st_harga = 0;
            $i = 0;
            $no = 1;
          }

          foreach ($inventaris as $value) {
            if ($value['kode_jenis'] == '01') {
              foreach ($alphabet as $key => $v_alphabet) {
                if (($field_name[$key] == 'sertifikat_tanggal' or $field_name[$key] == 'gedung_tanggal' or $field_name[$key] == 'dokumen_tanggal' or $field_name[$key] == 'tanggal_mulai') and ($value[$field_name[$key]] != '')) {
                  $ex->setCellValue($v_alphabet . $counter, tgl_indo($value[$field_name[$key]]));
                  // die($value[$field_name[$key]]);
                } else if (($field_name[$key] == 'tanggal_perolehan') and ($value[$field_name[$key]] != '')) {
                  $ex->setCellValue($v_alphabet . $counter, date('Y', strtotime($value[$field_name[$key]])));
                } else if (($field_name[$key] == 'ukuran_barang') and ($value[$field_name[$key]] == '0')) {
                  $ex->setCellValue($v_alphabet . $counter, '');
                } else if ($field_name[$key] == "nomor_register") {
                  $temp_register =  explode(',', $value[$field_name[$key]]);
                  $nomor_register = cek_register_kosong($temp_register);
                  $ex->setCellValue($v_alphabet . $counter, $nomor_register);
                } elseif ($field_name[$key] == "sertifikat_nomor") {
                  $objPHPExcel->getActiveSheet()->setCellValueExplicit($v_alphabet . $counter, $value[$field_name[$key]], PHPExcel_Cell_DataType::TYPE_STRING);
                  // $ex->setCellValue($v_alphabet.$counter, "'".$value[$field_name[$key]]);
                }elseif ($field_name[$key] == "no") {
                  $objPHPExcel->getActiveSheet()->setCellValue($v_alphabet . $counter, $no++);
                  // $ex->setCellValue($v_alphabet.$counter, "'".$value[$field_name[$key]]);
                } else {
                  $ex->setCellValue($v_alphabet . $counter, $value[$field_name[$key]]);
                }

              }
              $st_jumlah++;
              $st_harga += $value['harga'];
              $i++;
              $counter++;
            }
          }

          if ($i != 0) {
            $ex->setCellValue('A' . $counter, 'Jumlah KIB A');
            $ex->setCellValue('M' . $counter, $st_jumlah);
            $ex->setCellValue('N' . $counter, $st_harga);
            $counter++;

            $st_jumlah = 0;
            $st_harga = 0;
            $i = 0;
            $no = 1;
          }
          
          foreach ($inventaris as $value) {
            if ($value['kode_jenis'] == '02') {
              foreach ($alphabet as $key => $v_alphabet) {
                if (($field_name[$key] == 'sertifikat_tanggal' or $field_name[$key] == 'gedung_tanggal' or $field_name[$key] == 'dokumen_tanggal' or $field_name[$key] == 'tanggal_mulai') and ($value[$field_name[$key]] != '')) {
                  $ex->setCellValue($v_alphabet . $counter, tgl_indo($value[$field_name[$key]]));
                  // die($value[$field_name[$key]]);
                } else if (($field_name[$key] == 'tanggal_perolehan') and ($value[$field_name[$key]] != '')) {
                  $ex->setCellValue($v_alphabet . $counter, date('Y', strtotime($value[$field_name[$key]])));
                } else if (($field_name[$key] == 'ukuran_barang') and ($value[$field_name[$key]] == '0')) {
                  $ex->setCellValue($v_alphabet . $counter, '');
                } else if ($field_name[$key] == "nomor_register") {
                  $temp_register =  explode(',', $value[$field_name[$key]]);
                  $nomor_register = cek_register_kosong($temp_register);
                  $ex->setCellValue($v_alphabet . $counter, $nomor_register);
                } elseif ($field_name[$key] == "sertifikat_nomor") {
                  $objPHPExcel->getActiveSheet()->setCellValueExplicit($v_alphabet . $counter, $value[$field_name[$key]], PHPExcel_Cell_DataType::TYPE_STRING);
                  // $ex->setCellValue($v_alphabet.$counter, "'".$value[$field_name[$key]]);
                }elseif ($field_name[$key] == "no") {
                  $objPHPExcel->getActiveSheet()->setCellValue($v_alphabet . $counter, $no++);
                  // $ex->setCellValue($v_alphabet.$counter, "'".$value[$field_name[$key]]);
                } else {
                  $ex->setCellValue($v_alphabet . $counter, $value[$field_name[$key]]);
                }

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
            $no = 1;
          }
          
          foreach ($inventaris as $value) {
            if ($value['kode_jenis'] == '03') {
              foreach ($alphabet as $key => $v_alphabet) {
                if (($field_name[$key] == 'sertifikat_tanggal' or $field_name[$key] == 'gedung_tanggal' or $field_name[$key] == 'dokumen_tanggal' or $field_name[$key] == 'tanggal_mulai') and ($value[$field_name[$key]] != '')) {
                  $ex->setCellValue($v_alphabet . $counter, tgl_indo($value[$field_name[$key]]));
                  // die($value[$field_name[$key]]);
                } else if (($field_name[$key] == 'tanggal_perolehan') and ($value[$field_name[$key]] != '')) {
                  $ex->setCellValue($v_alphabet . $counter, date('Y', strtotime($value[$field_name[$key]])));
                } else if (($field_name[$key] == 'ukuran_barang') and ($value[$field_name[$key]] == '0')) {
                  $ex->setCellValue($v_alphabet . $counter, '');
                } else if ($field_name[$key] == "nomor_register") {
                  $temp_register =  explode(',', $value[$field_name[$key]]);
                  $nomor_register = cek_register_kosong($temp_register);
                  $ex->setCellValue($v_alphabet . $counter, $nomor_register);
                } elseif ($field_name[$key] == "sertifikat_nomor") {
                  $objPHPExcel->getActiveSheet()->setCellValueExplicit($v_alphabet . $counter, $value[$field_name[$key]], PHPExcel_Cell_DataType::TYPE_STRING);
                  // $ex->setCellValue($v_alphabet.$counter, "'".$value[$field_name[$key]]);
                }elseif ($field_name[$key] == "no") {
                  $objPHPExcel->getActiveSheet()->setCellValue($v_alphabet . $counter, $no++);
                  // $ex->setCellValue($v_alphabet.$counter, "'".$value[$field_name[$key]]);
                } else {
                  $ex->setCellValue($v_alphabet . $counter, $value[$field_name[$key]]);
                }

              }
              $st_jumlah++;
              $st_harga += $value['harga'];
              $i++;
              $counter++;
            }
          }

          if ($i != 0) {
            $ex->setCellValue('A' . $counter, 'Jumlah KIB C');
            $ex->setCellValue('M' . $counter, $st_jumlah);
            $ex->setCellValue('N' . $counter, $st_harga);
            $counter++;

            $st_jumlah = 0;
            $st_harga = 0;
            $i = 0;
            $no = 1;
          }
          
          foreach ($inventaris as $value) {
            if ($value['kode_jenis'] == '04') {
              foreach ($alphabet as $key => $v_alphabet) {
                if (($field_name[$key] == 'sertifikat_tanggal' or $field_name[$key] == 'gedung_tanggal' or $field_name[$key] == 'dokumen_tanggal' or $field_name[$key] == 'tanggal_mulai') and ($value[$field_name[$key]] != '')) {
                  $ex->setCellValue($v_alphabet . $counter, tgl_indo($value[$field_name[$key]]));
                  // die($value[$field_name[$key]]);
                } else if (($field_name[$key] == 'tanggal_perolehan') and ($value[$field_name[$key]] != '')) {
                  $ex->setCellValue($v_alphabet . $counter, date('Y', strtotime($value[$field_name[$key]])));
                } else if (($field_name[$key] == 'ukuran_barang') and ($value[$field_name[$key]] == '0')) {
                  $ex->setCellValue($v_alphabet . $counter, '');
                } else if ($field_name[$key] == "nomor_register") {
                  $temp_register =  explode(',', $value[$field_name[$key]]);
                  $nomor_register = cek_register_kosong($temp_register);
                  $ex->setCellValue($v_alphabet . $counter, $nomor_register);
                } elseif ($field_name[$key] == "sertifikat_nomor") {
                  $objPHPExcel->getActiveSheet()->setCellValueExplicit($v_alphabet . $counter, $value[$field_name[$key]], PHPExcel_Cell_DataType::TYPE_STRING);
                  // $ex->setCellValue($v_alphabet.$counter, "'".$value[$field_name[$key]]);
                }elseif ($field_name[$key] == "no") {
                  $objPHPExcel->getActiveSheet()->setCellValue($v_alphabet . $counter, $no++);
                  // $ex->setCellValue($v_alphabet.$counter, "'".$value[$field_name[$key]]);
                } else {
                  $ex->setCellValue($v_alphabet . $counter, $value[$field_name[$key]]);
                }

              }
              $st_jumlah++;
              $st_harga += $value['harga'];
              $i++;
              $counter++;
            }
          }

          if ($i != 0) {
            $ex->setCellValue('A' . $counter, 'Jumlah KIB D');
            $ex->setCellValue('M' . $counter, $st_jumlah);
            $ex->setCellValue('N' . $counter, $st_harga);
            $counter++;

            $st_jumlah = 0;
            $st_harga = 0;
            $i = 0;
            $no = 1;
          }
          
          foreach ($inventaris as $value) {
            if ($value['kode_jenis'] == '05') {
              foreach ($alphabet as $key => $v_alphabet) {
                if (($field_name[$key] == 'sertifikat_tanggal' or $field_name[$key] == 'gedung_tanggal' or $field_name[$key] == 'dokumen_tanggal' or $field_name[$key] == 'tanggal_mulai') and ($value[$field_name[$key]] != '')) {
                  $ex->setCellValue($v_alphabet . $counter, tgl_indo($value[$field_name[$key]]));
                  // die($value[$field_name[$key]]);
                } else if (($field_name[$key] == 'tanggal_perolehan') and ($value[$field_name[$key]] != '')) {
                  $ex->setCellValue($v_alphabet . $counter, date('Y', strtotime($value[$field_name[$key]])));
                } else if (($field_name[$key] == 'ukuran_barang') and ($value[$field_name[$key]] == '0')) {
                  $ex->setCellValue($v_alphabet . $counter, '');
                } else if ($field_name[$key] == "nomor_register") {
                  $temp_register =  explode(',', $value[$field_name[$key]]);
                  $nomor_register = cek_register_kosong($temp_register);
                  $ex->setCellValue($v_alphabet . $counter, $nomor_register);
                } elseif ($field_name[$key] == "sertifikat_nomor") {
                  $objPHPExcel->getActiveSheet()->setCellValueExplicit($v_alphabet . $counter, $value[$field_name[$key]], PHPExcel_Cell_DataType::TYPE_STRING);
                  // $ex->setCellValue($v_alphabet.$counter, "'".$value[$field_name[$key]]);
                }elseif ($field_name[$key] == "no") {
                  $objPHPExcel->getActiveSheet()->setCellValue($v_alphabet . $counter, $no++);
                  // $ex->setCellValue($v_alphabet.$counter, "'".$value[$field_name[$key]]);
                } else {
                  $ex->setCellValue($v_alphabet . $counter, $value[$field_name[$key]]);
                }

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
            $no = 1;
          }
          
          foreach ($inventaris as $value) {
            if ($value['kode_jenis'] == '06') {
              foreach ($alphabet as $key => $v_alphabet) {
                if (($field_name[$key] == 'sertifikat_tanggal' or $field_name[$key] == 'gedung_tanggal' or $field_name[$key] == 'dokumen_tanggal' or $field_name[$key] == 'tanggal_mulai') and ($value[$field_name[$key]] != '')) {
                  $ex->setCellValue($v_alphabet . $counter, tgl_indo($value[$field_name[$key]]));
                  // die($value[$field_name[$key]]);
                } else if (($field_name[$key] == 'tanggal_perolehan') and ($value[$field_name[$key]] != '')) {
                  $ex->setCellValue($v_alphabet . $counter, date('Y', strtotime($value[$field_name[$key]])));
                } else if (($field_name[$key] == 'ukuran_barang') and ($value[$field_name[$key]] == '0')) {
                  $ex->setCellValue($v_alphabet . $counter, '');
                } else if ($field_name[$key] == "nomor_register") {
                  $temp_register =  explode(',', $value[$field_name[$key]]);
                  $nomor_register = cek_register_kosong($temp_register);
                  $ex->setCellValue($v_alphabet . $counter, $nomor_register);
                } elseif ($field_name[$key] == "sertifikat_nomor") {
                  $objPHPExcel->getActiveSheet()->setCellValueExplicit($v_alphabet . $counter, $value[$field_name[$key]], PHPExcel_Cell_DataType::TYPE_STRING);
                  // $ex->setCellValue($v_alphabet.$counter, "'".$value[$field_name[$key]]);
                }elseif ($field_name[$key] == "no") {
                  $objPHPExcel->getActiveSheet()->setCellValue($v_alphabet . $counter, $no++);
                  // $ex->setCellValue($v_alphabet.$counter, "'".$value[$field_name[$key]]);
                } else {
                  $ex->setCellValue($v_alphabet . $counter, $value[$field_name[$key]]);
                }

              }
              $st_jumlah++;
              $st_harga += $value['harga'];
              $i++;
              $counter++;
            }
          }

          if ($i != 0) {
            $ex->setCellValue('A' . $counter, 'Jumlah KIB F');
            $ex->setCellValue('M' . $counter, $st_jumlah);
            $ex->setCellValue('N' . $counter, $st_harga);
            $counter++;

            $st_jumlah = 0;
            $st_harga = 0;
            $i = 0;
            $no = 1;
          }
            
        // }


        $last_row = $counter - 1;
        $objPHPExcel->getActiveSheet()->getStyle($alphabet[0] . "8:" . $alphabet[$itemcount - 1] . $last_row)
          ->applyFromArray($borderStyleArray);

          
      $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(7);
      $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(20);
      $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(60);
      $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(20);
      $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(60);
      $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(20);
      $objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(20);
      $objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(10);
      $objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(20);
      $objPHPExcel->getActiveSheet()->getColumnDimension('J')->setWidth(20);
      $objPHPExcel->getActiveSheet()->getColumnDimension('K')->setWidth(20);
      $objPHPExcel->getActiveSheet()->getColumnDimension('L')->setWidth(20);
      $objPHPExcel->getActiveSheet()->getColumnDimension('M')->setWidth(20);
      $objPHPExcel->getActiveSheet()->getColumnDimension('N')->setWidth(20);
      $objPHPExcel->getActiveSheet()->getColumnDimension('O')->setWidth(20);
      $objPHPExcel->getActiveSheet()->getColumnDimension('P')->setWidth(20);
      $objPHPExcel->getActiveSheet()->getColumnDimension('Q')->setWidth(50);
      $objPHPExcel->getActiveSheet()->getColumnDimension('R')->setWidth(50);

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
          ->setTitle("Export Buku Inventaris")
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
        header('Content-Disposition: attachment;filename="Export Laporan KIB ' . date('Y-m-d') . '.xlsx"');

        $objWriter->save('php://output');
        die();
      }


      public function generateAlphabet($na)
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
        $data['jenis'] = $this->global_model->kode_jenis[$data['kode_jenis']];

        $data['kib'] = $this->Rekap_atb_model->get_all($data);
        // die(json_encode($data['kib']));
        $html = $this->load->view('kib/report_pdf_dev', $data, true);
        $this->pdfgenerator->generate($html, 'KIB', true, 'folio', 'landscape');
        // $this->load->view('kib/report_pdf_dev', $data);

      }

      public function getParentLokasi($kode_lokasi)
      {
        $kode = explode(".", $kode_lokasi);
        if(isset($kode[1])){
        $kode_lokasi = $kode[1].'.'.$kode[2].'.'.$kode[3].'.'.$kode[4].'.*.*'; 
        $return = $this->Rekap_atb_model->getParentLokasi($kode_lokasi);
        return $return->instansi;
        }
      }
    }
