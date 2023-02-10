    <?php

    if (!defined('BASEPATH')) {
      exit('No direct script access allowed');
    }

    class Kib extends CI_Controller
    {
      public function __construct()
      {
        parent::__construct();
        $this->global_model->cek_hak_akses('18');
        $this->load->model('Kib_model');
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

        $data['content'] = $this->load->view('kib/home', $data, true);
        $data['breadcrumb'] = array(
          array('label' => 'Laporan', 'url' => '#', 'icon' => 'fa fa-pie-chart', 'li_class' => ''),
          array('label' => 'KIB', 'url' => '#', 'icon' => '', 'li_class' => 'active'),
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

        if ($data['kode_jenis'] == '01') {
          $data['field_name'] = array('no', 'nama_barang_desc', 'kode_barang', 'nomor_register', 'luas', 'tahun_pengadaan', 'letak_alamat', 'status_hak', 'sertifikat_tanggal', 'sertifikat_nomor', 'penggunaan', 'asal_usul', 'harga', 'kondisi', 'keterangan', 'latitute', 'id_kib_a');
          $data['label'] = array("No", "Nama Barang / Uraian", "Kode Barang", "Nomor Register", "Luas (M2)", "Tahun Pengadaan", "Letak Alamat", "Status Hak", "Sertifikat Tanggal", "Sertifikat Nomor", "Penggunaan", "Asal Usul", "Harga", 'Kondisi', "Keterangan","Latitute, Longitute","ID KIB");
          if($data['id_kuasa_pengguna'] == ''){
            array_push($data['field_name'], 'instansi');
          }
        } else if ($data['kode_jenis'] == '02') {
          $data['field_name'] = array('no', 'kode_barang', 'nama_barang_desc', 'nomor_register', 'merk_type', 'ukuran_cc', 'bahan', 'tahun_pembelian', 'nomor_pabrik', 'nomor_rangka', 'nomor_mesin', 'nomor_polisi', 'nomor_bpkb', 'asal_usul', 'harga', 'kondisi', 'keterangan', 'id_kib_b');
          $data['label'] = array("No", "Kode Barang", "Nama Barang / Uraian", "Nomor Register", "Merk Type", "Ukuran (CC)", "Bahan", "Tahun Pembelian", "Nomor Pabrik", "Nomor Rangka", "Nomor Mesin", "Nomor Polisi", "Nomor Bpkb", "Asal Usul", "Harga", 'Kondisi', "Keterangan","ID KIB");
          if($data['id_kuasa_pengguna'] == ''){
            array_push($data['field_name'], 'instansi');
          }
        } else if ($data['kode_jenis'] == '03') {
          $data['field_name'] = array('no', 'nama_barang_desc', 'kode_barang', 'nomor_register', 'kondisi_bangunan', 'bangunan_bertingkat', 'bangunan_beton', 'luas_lantai_m2', 'lokasi_alamat', 'gedung_tanggal', 'gedung_nomor', 'luas_m2', 'status', 'nomor_kode_tanah', 'asal_usul', 'harga', 'keterangan', 'latitute', 'id_kib_c');
          $data['label'] = array("No", "Nama Barang / Uraian", "Kode Barang", "Nomor Register", "Kondisi Bangunan", "Bangunan Bertingkat", "Bangunan Beton", "Luas Lantai (M2)", "Lokasi Alamat", "Gedung Tanggal", "Gedung Nomor", "Luas (M2)", "Status", "Nomor Kode Tanah", "Asal Usul", "Harga", "Keterangan","Latitute, Longitute","ID KIB");
          if($data['id_kuasa_pengguna'] == ''){
            array_push($data['field_name'], 'instansi');
          }
        } else if ($data['kode_jenis'] == '04') {
          $data['field_name'] = array('no', 'nama_barang_desc', 'kode_barang', 'nomor_register', 'konstruksi', 'panjang_km', 'lebar_m', 'luas_m2', 'letak_lokasi', 'dokumen_tanggal', 'dokumen_nomor', 'status_tanah', 'kode_tanah', 'asal_usul', 'harga', 'kondisi', 'keterangan', 'latitute', 'id_kib_d');
          $data['label'] = array('No', 'Nama Barang / Uraian', 'Kode Barang', 'Nomor Register', 'Konstruksi', 'Panjang (KM)', 'Lebar (M)', 'Luas (M2)', 'Letak Lokasi', 'Dokumen Tanggal', 'Dokumen Nomor', 'Status Tanah', 'Kode Tanah', 'Asal Usul', 'Harga', 'Kondisi', 'Keterangan',"Latitute, Longitute","ID KIB");
          if($data['id_kuasa_pengguna'] == ''){
            array_push($data['field_name'], 'instansi');
          }
        } else if ($data['kode_jenis'] == '05') {
          $data['field_name'] = array('no', 'nama_barang_desc', 'kode_barang', 'nomor_register', 'judul_pencipta', 'spesifikasi', 'kesenian_asal_daerah', 'kesenian_pencipta', 'kesenian_bahan', 'hewan_tumbuhan_jenis', 'hewan_tumbuhan_ukuran', 'jumlah', 'tahun_pembelian', 'asal_usul', 'harga', 'kondisi', 'keterangan', 'id_kib_e');
          $data['label'] = array('No', 'Nama Barang / Uraian', 'Kode Barang', 'Nomor Register', 'Judul Pencipta', 'Spesifikasi', 'Kesenian Asal Daerah', 'Kesenian Pencipta', 'Kesenian Bahan', 'Hewan Tumbuhan Jenis', 'Hewan Tumbuhan Ukuran', 'Jumlah', 'Tahun Pembelian', 'Asal Usul', 'Harga', 'Kondisi', 'Keterangan','ID KIB');
          if($data['id_kuasa_pengguna'] == ''){
            array_push($data['field_name'], 'instansi');
          }
        } else if ($data['kode_jenis'] == '06') {
          $data['field_name'] = array('no', 'nama_barang_desc', 'bangunan', 'kontruksi_bertingkat', 'kontruksi_beton', 'luas_m2', 'lokasi_alamat', 'dokumen_tanggal', 'dokumen_nomor', 'tanggal_mulai', 'status_tanah', 'nomor_kode_tanah', 'asal_usul', 'nilai_kontrak', 'keterangan', 'id_kib_f'); //,'kode_barang'
          $data['label'] = array('No', 'Nama Barang / Uraian', 'Bangunan', 'Konstruksi Bertingkat', 'Konstruksi Beton', 'Luas (M2)', 'Lokasi Alamat', 'Dokumen Tanggal', 'Dokumen Nomor', 'Tanggal Mulai', 'Status Tanah', 'Nomor Kode Tanah', 'Asal Usul', 'Nilai Kontrak', 'Keterangan','ID KIB'); //,'Kode Barang'
          if($data['id_kuasa_pengguna'] == ''){
            array_push($data['field_name'], 'instansi');
          }
        } else if ($data['kode_jenis'] == '5.03') {
          $data['field_name'] = array('no', 'kode_barang', 'nama_barang_desc',    'nomor_register', 'judul_kajian_nama_software', 'tahun_perolehan', 'asal_usul', 'harga', 'keterangan', 'id_kib_atb'); //,'kode_barang'
          $data['label'] =      array('No', 'Kode Barang', 'Jenis / Nama Barang', 'Nomor Register', 'Judul Kajian/Nama Software', 'Tahun Perolehan', 'Asal Usul Cara Perolehan', 'Harga (Rp.)', 'Keterangan','ID KIB'); //,'Kode Barang'
          if($data['id_kuasa_pengguna'] == ''){
            array_push($data['field_name'], 'instansi');
          }
        } else if ($data['kode_jenis'] == '5.02' or $data['kode_jenis'] == '5.04') {
          $data['field_name'] = array('no',  'kode_barang',  'nomor_register',  'nama_barang',  'merk_type',  'sertifikat_nomor',  'bahan', 'asal_usul', 'tanggal_perolehan', 'ukuran_barang', 'satuan', 'keadaan_barang', 'jumlah_barang', 'harga', 'keterangan', 'id_kib');
          $data['label'] = array('No',  'Kode Barang',  'Nomor Register',  'Nama Barang / Uraian',  'Merk / Type', 'Sertifikat Nomor', 'Bahan', 'Asal/Cara Perolehan Barang', 'Tahun Perolehan', 'Ukuran Barang / konstruksi (P,S,D)', 'Satuan', 'Keadaan Barang(B,RR,RB)', 'Jumlah Barang', 'Harga', 'Keterangan','ID KIB');
          if($data['id_kuasa_pengguna'] == ''){
            array_push($data['field_name'], 'instansi');
          }
        }
        
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
          // ASET LAINYA (KELOMPOK 5 SELAIN ATB)
          if ($data['kode_jenis'] == '5.02' or $data['kode_jenis'] == '5.04')
            $this->toExcelLainya($data);
          else
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
          ->setCellValue('A2', $jenis['nama'])
          ->setCellValue('A4', 'No. Kode Lokasi : ' . $data['kode_lokasi'] . $data['nama_lokasi'])
          ->setCellValue('A5', 'Filter : ' . strtoupper($intra_extra))
          ->setCellValue($alphabet[$col2] . '5', $data['des_pemilik']);

        $objPHPExcel->getActiveSheet()
          ->getStyle($alphabet[$col2] . '5')
          ->getAlignment()
          ->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

        //header
        if ($data['kode_jenis'] == '01') {
          $counter = 6;
          $first_row_header = $counter;
          $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A' . $counter, 'No.');

          $objPHPExcel->setActiveSheetIndex(0)->setCellValue('B' . $counter, 'Jenis barang / Nama barang');
          $objPHPExcel->setActiveSheetIndex(0)->setCellValue('C' . $counter, 'Nomor');

          $objPHPExcel->setActiveSheetIndex(0)->setCellValue('E' . $counter, 'Luas (M2)');
          $objPHPExcel->setActiveSheetIndex(0)->setCellValue('F' . $counter, 'Tahun Pengadaan');
          $objPHPExcel->setActiveSheetIndex(0)->setCellValue('G' . $counter, 'Letak / Alamat');
          $objPHPExcel->setActiveSheetIndex(0)->setCellValue('H' . $counter, 'Status Tanah');

          $objPHPExcel->setActiveSheetIndex(0)->setCellValue('K' . $counter, 'Penggunaan');
          $objPHPExcel->setActiveSheetIndex(0)->setCellValue('L' . $counter, 'Asal usul');
          $objPHPExcel->setActiveSheetIndex(0)->setCellValue('M' . $counter, 'Harga (Ribuan Rp.)');
          $objPHPExcel->setActiveSheetIndex(0)->setCellValue('N' . $counter, 'Kondisi');
          $objPHPExcel->setActiveSheetIndex(0)->setCellValue('O' . $counter, 'Keterangan');
          $objPHPExcel->setActiveSheetIndex(0)->setCellValue('P' . $counter, 'Latitute, Longitute');
          $objPHPExcel->setActiveSheetIndex(0)->setCellValue('Q' . $counter, 'ID KIB');
          if (in_array('instansi', $data['field_name'])) {
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('R' . $counter, 'Unit Kerja');
          }

          $objPHPExcel->getActiveSheet()->mergeCells('C' . $counter . ':D' . $counter);
          $objPHPExcel->getActiveSheet()->mergeCells('H' . $counter . ':J' . $counter);

          $counter = $counter + 1;
          $midle_row_header = $counter;
          $objPHPExcel->setActiveSheetIndex(0)->setCellValue('C' . $counter, 'Kode Barang');
          $objPHPExcel->setActiveSheetIndex(0)->setCellValue('D' . $counter, 'Register');
          $objPHPExcel->setActiveSheetIndex(0)->setCellValue('H' . $counter, 'Hak');
          $objPHPExcel->setActiveSheetIndex(0)->setCellValue('I' . $counter, 'Sertifikat');
          $objPHPExcel->getActiveSheet()->mergeCells('I' . $counter . ':J' . $counter);

          $counter = $counter + 1;
          $last_row_header = $counter;
          $objPHPExcel->setActiveSheetIndex(0)->setCellValue('I' . $counter, 'Tanggal');
          $objPHPExcel->setActiveSheetIndex(0)->setCellValue('J' . $counter, 'Nomor');

          $objPHPExcel->getActiveSheet()->mergeCells('A' . $first_row_header . ':A' . $last_row_header);
          $objPHPExcel->getActiveSheet()->mergeCells('B' . $first_row_header . ':B' . $last_row_header);
          $objPHPExcel->getActiveSheet()->mergeCells('E' . $first_row_header . ':E' . $last_row_header);
          $objPHPExcel->getActiveSheet()->mergeCells('F' . $first_row_header . ':F' . $last_row_header);
          $objPHPExcel->getActiveSheet()->mergeCells('G' . $first_row_header . ':G' . $last_row_header);
          $objPHPExcel->getActiveSheet()->mergeCells('K' . $first_row_header . ':K' . $last_row_header);
          $objPHPExcel->getActiveSheet()->mergeCells('L' . $first_row_header . ':L' . $last_row_header);
          $objPHPExcel->getActiveSheet()->mergeCells('M' . $first_row_header . ':M' . $last_row_header);
          $objPHPExcel->getActiveSheet()->mergeCells('N' . $first_row_header . ':N' . $last_row_header);
          $objPHPExcel->getActiveSheet()->mergeCells('O' . $first_row_header . ':O' . $last_row_header);
          $objPHPExcel->getActiveSheet()->mergeCells('P' . $first_row_header . ':P' . $last_row_header);
          $objPHPExcel->getActiveSheet()->mergeCells('Q' . $first_row_header . ':Q' . $last_row_header);
          $objPHPExcel->getActiveSheet()->mergeCells('R' . $first_row_header . ':R' . $last_row_header);

          $objPHPExcel->getActiveSheet()->mergeCells('C' . $midle_row_header . ':C' . $last_row_header);
          $objPHPExcel->getActiveSheet()->mergeCells('D' . $midle_row_header . ':D' . $last_row_header);
          $objPHPExcel->getActiveSheet()->mergeCells('H' . $midle_row_header . ':H' . $last_row_header);
        } else if ($data['kode_jenis'] == '02') {
          $counter = 6;
          $first_row_header = $counter;
          $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A' . $counter, 'No. Urut');
          $objPHPExcel->setActiveSheetIndex(0)->setCellValue('B' . $counter, 'Kode Barang');
          $objPHPExcel->setActiveSheetIndex(0)->setCellValue('C' . $counter, 'Nama Barang / Jenis Barang');
          $objPHPExcel->setActiveSheetIndex(0)->setCellValue('D' . $counter, 'Nomor Register');
          $objPHPExcel->setActiveSheetIndex(0)->setCellValue('E' . $counter, 'Merk / Type');
          $objPHPExcel->setActiveSheetIndex(0)->setCellValue('F' . $counter, 'Ukuran / CC');
          $objPHPExcel->setActiveSheetIndex(0)->setCellValue('G' . $counter, 'Bahan');
          $objPHPExcel->setActiveSheetIndex(0)->setCellValue('H' . $counter, 'Tahun Pembelian');
          $objPHPExcel->setActiveSheetIndex(0)->setCellValue('I' . $counter, 'Nomor');

          $objPHPExcel->setActiveSheetIndex(0)->setCellValue('N' . $counter, 'Asal usul Cara Perolehan');
          $objPHPExcel->setActiveSheetIndex(0)->setCellValue('O' . $counter, 'Harga');
          $objPHPExcel->setActiveSheetIndex(0)->setCellValue('P' . $counter, 'Kondisi (B/RR/RB)');
          $objPHPExcel->setActiveSheetIndex(0)->setCellValue('Q' . $counter, 'Keterangan');
          $objPHPExcel->setActiveSheetIndex(0)->setCellValue('R' . $counter, 'ID KIB');
          if (in_array('instansi', $data['field_name'])) {
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('S' . $counter, 'Unit Kerja');
          }

          $objPHPExcel->getActiveSheet()->mergeCells('I' . $counter . ':M' . $counter);

          $counter = $counter + 1;
          $last_row_header = $counter;
          $objPHPExcel->setActiveSheetIndex(0)->setCellValue('I' . $counter, 'Pabrik');
          $objPHPExcel->setActiveSheetIndex(0)->setCellValue('J' . $counter, 'Rangka');
          $objPHPExcel->setActiveSheetIndex(0)->setCellValue('K' . $counter, 'Mesin');
          $objPHPExcel->setActiveSheetIndex(0)->setCellValue('L' . $counter, 'Polisi');
          $objPHPExcel->setActiveSheetIndex(0)->setCellValue('M' . $counter, 'BPKB');

          $objPHPExcel->getActiveSheet()->mergeCells('A' . $first_row_header . ':A' . $last_row_header);
          $objPHPExcel->getActiveSheet()->mergeCells('B' . $first_row_header . ':B' . $last_row_header);
          $objPHPExcel->getActiveSheet()->mergeCells('C' . $first_row_header . ':C' . $last_row_header);
          $objPHPExcel->getActiveSheet()->mergeCells('D' . $first_row_header . ':D' . $last_row_header);
          $objPHPExcel->getActiveSheet()->mergeCells('E' . $first_row_header . ':E' . $last_row_header);
          $objPHPExcel->getActiveSheet()->mergeCells('F' . $first_row_header . ':F' . $last_row_header);
          $objPHPExcel->getActiveSheet()->mergeCells('G' . $first_row_header . ':G' . $last_row_header);
          $objPHPExcel->getActiveSheet()->mergeCells('H' . $first_row_header . ':H' . $last_row_header);

          $objPHPExcel->getActiveSheet()->mergeCells('N' . $first_row_header . ':N' . $last_row_header);
          $objPHPExcel->getActiveSheet()->mergeCells('O' . $first_row_header . ':O' . $last_row_header);
          $objPHPExcel->getActiveSheet()->mergeCells('P' . $first_row_header . ':P' . $last_row_header);
          $objPHPExcel->getActiveSheet()->mergeCells('Q' . $first_row_header . ':Q' . $last_row_header);
          $objPHPExcel->getActiveSheet()->mergeCells('R' . $first_row_header . ':R' . $last_row_header);
          $objPHPExcel->getActiveSheet()->mergeCells('S' . $first_row_header . ':S' . $last_row_header);
        } else if ($data['kode_jenis'] == '03') {
          $counter = 6;
          $first_row_header = $counter;
          $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A' . $counter, 'No. Urt');
          $objPHPExcel->setActiveSheetIndex(0)->setCellValue('B' . $counter, 'Nama Barang / Jenis Barang');
          $objPHPExcel->setActiveSheetIndex(0)->setCellValue('C' . $counter, 'Nomor');
          $objPHPExcel->setActiveSheetIndex(0)->setCellValue('E' . $counter, 'Kondisi Bangunan (B, RR, RB)');
          $objPHPExcel->setActiveSheetIndex(0)->setCellValue('F' . $counter, 'Konstruksi Bangunan');
          $objPHPExcel->setActiveSheetIndex(0)->setCellValue('H' . $counter, 'Luas Lantai (M2)');
          $objPHPExcel->setActiveSheetIndex(0)->setCellValue('I' . $counter, 'Letak / Lokasi Alamat');
          $objPHPExcel->setActiveSheetIndex(0)->setCellValue('J' . $counter, 'Dokumen Gedung');

          $objPHPExcel->setActiveSheetIndex(0)->setCellValue('L' . $counter, 'Luas (M2)');
          $objPHPExcel->setActiveSheetIndex(0)->setCellValue('M' . $counter, 'Status');
          $objPHPExcel->setActiveSheetIndex(0)->setCellValue('N' . $counter, 'Nomor Kode Tanah');
          $objPHPExcel->setActiveSheetIndex(0)->setCellValue('O' . $counter, 'Asal usul');
          $objPHPExcel->setActiveSheetIndex(0)->setCellValue('P' . $counter, 'Harga');
          $objPHPExcel->setActiveSheetIndex(0)->setCellValue('Q' . $counter, 'Ket.');
          $objPHPExcel->setActiveSheetIndex(0)->setCellValue('R' . $counter, 'Latitute, Longitute');
          $objPHPExcel->setActiveSheetIndex(0)->setCellValue('S' . $counter, 'ID KIB');
          if (in_array('instansi', $data['field_name'])) {
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('T' . $counter, 'Unit Kerja');
          }

          $objPHPExcel->getActiveSheet()->mergeCells('C' . $counter . ':D' . $counter);
          $objPHPExcel->getActiveSheet()->mergeCells('F' . $counter . ':G' . $counter);
          $objPHPExcel->getActiveSheet()->mergeCells('J' . $counter . ':K' . $counter);

          $counter = $counter + 1;
          $last_row_header = $counter;
          $objPHPExcel->setActiveSheetIndex(0)->setCellValue('C' . $counter, 'Kode Barang');
          $objPHPExcel->setActiveSheetIndex(0)->setCellValue('D' . $counter, 'Register');
          $objPHPExcel->setActiveSheetIndex(0)->setCellValue('F' . $counter, 'Bertingkat / Tidak');
          $objPHPExcel->setActiveSheetIndex(0)->setCellValue('G' . $counter, 'Beton / Tidak');
          $objPHPExcel->setActiveSheetIndex(0)->setCellValue('J' . $counter, 'Tanggal');
          $objPHPExcel->setActiveSheetIndex(0)->setCellValue('K' . $counter, 'Nomor');

          $objPHPExcel->getActiveSheet()->mergeCells('A' . $first_row_header . ':A' . $last_row_header);
          $objPHPExcel->getActiveSheet()->mergeCells('B' . $first_row_header . ':B' . $last_row_header);
          $objPHPExcel->getActiveSheet()->mergeCells('E' . $first_row_header . ':E' . $last_row_header);
          $objPHPExcel->getActiveSheet()->mergeCells('H' . $first_row_header . ':H' . $last_row_header);
          $objPHPExcel->getActiveSheet()->mergeCells('I' . $first_row_header . ':I' . $last_row_header);
          $objPHPExcel->getActiveSheet()->mergeCells('L' . $first_row_header . ':L' . $last_row_header);
          $objPHPExcel->getActiveSheet()->mergeCells('M' . $first_row_header . ':M' . $last_row_header);
          $objPHPExcel->getActiveSheet()->mergeCells('N' . $first_row_header . ':N' . $last_row_header);
          $objPHPExcel->getActiveSheet()->mergeCells('O' . $first_row_header . ':O' . $last_row_header);
          $objPHPExcel->getActiveSheet()->mergeCells('P' . $first_row_header . ':P' . $last_row_header);

          $objPHPExcel->getActiveSheet()->mergeCells('Q' . $first_row_header . ':Q' . $last_row_header);
          $objPHPExcel->getActiveSheet()->mergeCells('R' . $first_row_header . ':R' . $last_row_header);
          $objPHPExcel->getActiveSheet()->mergeCells('S' . $first_row_header . ':S' . $last_row_header);
          $objPHPExcel->getActiveSheet()->mergeCells('T' . $first_row_header . ':T' . $last_row_header);
        } else if ($data['kode_jenis'] == '04') {
          $counter = 6;
          $first_row_header = $counter;
          $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A' . $counter, 'No. Urt');
          $objPHPExcel->setActiveSheetIndex(0)->setCellValue('B' . $counter, 'Jenis Barang / Nama Barang');
          $objPHPExcel->setActiveSheetIndex(0)->setCellValue('C' . $counter, 'Nomor');

          $objPHPExcel->setActiveSheetIndex(0)->setCellValue('E' . $counter, 'Konstruksi');
          $objPHPExcel->setActiveSheetIndex(0)->setCellValue('F' . $counter, 'Panjang (Km)');
          $objPHPExcel->setActiveSheetIndex(0)->setCellValue('G' . $counter, 'Lebar(M)');
          $objPHPExcel->setActiveSheetIndex(0)->setCellValue('H' . $counter, 'Luas (M2)');
          $objPHPExcel->setActiveSheetIndex(0)->setCellValue('I' . $counter, 'Letak / Lokasi');
          $objPHPExcel->setActiveSheetIndex(0)->setCellValue('J' . $counter, 'Dokumen');

          $objPHPExcel->setActiveSheetIndex(0)->setCellValue('L' . $counter, 'Status Tanah');
          $objPHPExcel->setActiveSheetIndex(0)->setCellValue('M' . $counter, 'Nomor Kode Tanah');
          $objPHPExcel->setActiveSheetIndex(0)->setCellValue('N' . $counter, 'Asal-usul');
          $objPHPExcel->setActiveSheetIndex(0)->setCellValue('O' . $counter, 'Harga');
          $objPHPExcel->setActiveSheetIndex(0)->setCellValue('P' . $counter, 'Kondisi (B, RR, RB)');
          $objPHPExcel->setActiveSheetIndex(0)->setCellValue('Q' . $counter, 'Ket.');
          $objPHPExcel->setActiveSheetIndex(0)->setCellValue('R' . $counter, 'Latitute, Longitute');
          $objPHPExcel->setActiveSheetIndex(0)->setCellValue('S' . $counter, 'ID KIB');
          if (in_array('instansi', $data['field_name'])) {
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('T' . $counter, 'Unit Kerja');
          }

          $objPHPExcel->getActiveSheet()->mergeCells('C' . $counter . ':D' . $counter);
          $objPHPExcel->getActiveSheet()->mergeCells('J' . $counter . ':K' . $counter);

          $counter = $counter + 1;
          $last_row_header = $counter;
          $objPHPExcel->setActiveSheetIndex(0)->setCellValue('C' . $counter, 'Kode Barang');
          $objPHPExcel->setActiveSheetIndex(0)->setCellValue('D' . $counter, 'Register');
          $objPHPExcel->setActiveSheetIndex(0)->setCellValue('J' . $counter, 'Tanggal');
          $objPHPExcel->setActiveSheetIndex(0)->setCellValue('K' . $counter, 'Nomor');

          $objPHPExcel->getActiveSheet()->mergeCells('A' . $first_row_header . ':A' . $last_row_header);
          $objPHPExcel->getActiveSheet()->mergeCells('B' . $first_row_header . ':B' . $last_row_header);
          $objPHPExcel->getActiveSheet()->mergeCells('E' . $first_row_header . ':E' . $last_row_header);
          $objPHPExcel->getActiveSheet()->mergeCells('F' . $first_row_header . ':F' . $last_row_header);
          $objPHPExcel->getActiveSheet()->mergeCells('G' . $first_row_header . ':G' . $last_row_header);
          $objPHPExcel->getActiveSheet()->mergeCells('H' . $first_row_header . ':H' . $last_row_header);
          $objPHPExcel->getActiveSheet()->mergeCells('I' . $first_row_header . ':I' . $last_row_header);
          $objPHPExcel->getActiveSheet()->mergeCells('L' . $first_row_header . ':L' . $last_row_header);
          $objPHPExcel->getActiveSheet()->mergeCells('M' . $first_row_header . ':M' . $last_row_header);
          $objPHPExcel->getActiveSheet()->mergeCells('N' . $first_row_header . ':N' . $last_row_header);
          $objPHPExcel->getActiveSheet()->mergeCells('O' . $first_row_header . ':O' . $last_row_header);
          $objPHPExcel->getActiveSheet()->mergeCells('P' . $first_row_header . ':P' . $last_row_header);
          $objPHPExcel->getActiveSheet()->mergeCells('Q' . $first_row_header . ':Q' . $last_row_header);
          $objPHPExcel->getActiveSheet()->mergeCells('R' . $first_row_header . ':R' . $last_row_header);
          $objPHPExcel->getActiveSheet()->mergeCells('S' . $first_row_header . ':S' . $last_row_header);
          $objPHPExcel->getActiveSheet()->mergeCells('T' . $first_row_header . ':T' . $last_row_header);
        } else if ($data['kode_jenis'] == '05') {
          $counter = 6;
          $first_row_header = $counter;
          $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A' . $counter, 'No. Urut');
          $objPHPExcel->setActiveSheetIndex(0)->setCellValue('B' . $counter, 'Nama Barang / Jenis Barang');
          $objPHPExcel->setActiveSheetIndex(0)->setCellValue('C' . $counter, 'Nomor');

          $objPHPExcel->setActiveSheetIndex(0)->setCellValue('E' . $counter, 'Buku / Perpustakaan');

          $objPHPExcel->setActiveSheetIndex(0)->setCellValue('G' . $counter, 'Barang Bercorak Kesenian / Kebudayaan');

          $objPHPExcel->setActiveSheetIndex(0)->setCellValue('J' . $counter, 'Hewan / Ternak dan Tumbuhan');

          $objPHPExcel->setActiveSheetIndex(0)->setCellValue('L' . $counter, 'Jumlah');
          $objPHPExcel->setActiveSheetIndex(0)->setCellValue('M' . $counter, 'Tahun Cetak / Pembelian');
          $objPHPExcel->setActiveSheetIndex(0)->setCellValue('N' . $counter, 'Asal usul Cara Perolehan');
          $objPHPExcel->setActiveSheetIndex(0)->setCellValue('O' . $counter, 'Harga');
          $objPHPExcel->setActiveSheetIndex(0)->setCellValue('P' . $counter, 'Kondisi (B, RR, RB)');
          $objPHPExcel->setActiveSheetIndex(0)->setCellValue('Q' . $counter, 'Ket.');
          $objPHPExcel->setActiveSheetIndex(0)->setCellValue('R' . $counter, 'ID KIB');
          if (in_array('instansi', $data['field_name'])) {
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('S' . $counter, 'Unit Kerja');
          }

          $objPHPExcel->getActiveSheet()->mergeCells('C' . $counter . ':D' . $counter);
          $objPHPExcel->getActiveSheet()->mergeCells('E' . $counter . ':F' . $counter);
          $objPHPExcel->getActiveSheet()->mergeCells('G' . $counter . ':I' . $counter);
          $objPHPExcel->getActiveSheet()->mergeCells('J' . $counter . ':K' . $counter);

          $counter = $counter + 1;
          $last_row_header = $counter;
          $objPHPExcel->setActiveSheetIndex(0)->setCellValue('C' . $counter, 'Kode Barang');
          $objPHPExcel->setActiveSheetIndex(0)->setCellValue('D' . $counter, 'Register');
          $objPHPExcel->setActiveSheetIndex(0)->setCellValue('E' . $counter, 'Judul / Pencipta');
          $objPHPExcel->setActiveSheetIndex(0)->setCellValue('F' . $counter, 'Spesifikasi');
          $objPHPExcel->setActiveSheetIndex(0)->setCellValue('G' . $counter, 'Asal Daerah');
          $objPHPExcel->setActiveSheetIndex(0)->setCellValue('H' . $counter, 'Pencipta');
          $objPHPExcel->setActiveSheetIndex(0)->setCellValue('I' . $counter, 'Bahan');
          $objPHPExcel->setActiveSheetIndex(0)->setCellValue('J' . $counter, 'Jenis');
          $objPHPExcel->setActiveSheetIndex(0)->setCellValue('K' . $counter, 'Ukuran');

          $objPHPExcel->getActiveSheet()->mergeCells('A' . $first_row_header . ':A' . $last_row_header);
          $objPHPExcel->getActiveSheet()->mergeCells('B' . $first_row_header . ':B' . $last_row_header);

          $objPHPExcel->getActiveSheet()->mergeCells('L' . $first_row_header . ':L' . $last_row_header);
          $objPHPExcel->getActiveSheet()->mergeCells('M' . $first_row_header . ':M' . $last_row_header);
          $objPHPExcel->getActiveSheet()->mergeCells('N' . $first_row_header . ':N' . $last_row_header);
          $objPHPExcel->getActiveSheet()->mergeCells('O' . $first_row_header . ':O' . $last_row_header);
          $objPHPExcel->getActiveSheet()->mergeCells('P' . $first_row_header . ':P' . $last_row_header);
          $objPHPExcel->getActiveSheet()->mergeCells('Q' . $first_row_header . ':Q' . $last_row_header);
          $objPHPExcel->getActiveSheet()->mergeCells('R' . $first_row_header . ':R' . $last_row_header);
          $objPHPExcel->getActiveSheet()->mergeCells('S' . $first_row_header . ':S' . $last_row_header);
        } else if ($data['kode_jenis'] == '06') {
          $counter = 6;
          $first_row_header = $counter;
          $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A' . $counter, 'No. Urt');
          $objPHPExcel->setActiveSheetIndex(0)->setCellValue('B' . $counter, 'Jenis Barang / Nama Barang');
          $objPHPExcel->setActiveSheetIndex(0)->setCellValue('C' . $counter, 'Bangunan (S, SP, D)');
          $objPHPExcel->setActiveSheetIndex(0)->setCellValue('D' . $counter, 'Konstruksi Bangunan');

          $objPHPExcel->setActiveSheetIndex(0)->setCellValue('F' . $counter, 'Luas (M2)');
          $objPHPExcel->setActiveSheetIndex(0)->setCellValue('G' . $counter, 'Letak / Lokasi Alamat');
          $objPHPExcel->setActiveSheetIndex(0)->setCellValue('H' . $counter, 'Dokumen');

          $objPHPExcel->setActiveSheetIndex(0)->setCellValue('J' . $counter, 'Tgl, Bln, Thn mulai');
          $objPHPExcel->setActiveSheetIndex(0)->setCellValue('K' . $counter, 'Status Tanah');
          $objPHPExcel->setActiveSheetIndex(0)->setCellValue('L' . $counter, 'Nomor Kode Tanah');
          $objPHPExcel->setActiveSheetIndex(0)->setCellValue('M' . $counter, 'Asal-usul Pembiayaan');
          $objPHPExcel->setActiveSheetIndex(0)->setCellValue('N' . $counter, 'Nilai Kontrak (Ribuan Rp)');
          $objPHPExcel->setActiveSheetIndex(0)->setCellValue('O' . $counter, 'Ket.');
          $objPHPExcel->setActiveSheetIndex(0)->setCellValue('P' . $counter, 'ID KIB');
          if (in_array('instansi', $data['field_name'])) {
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('Q' . $counter, 'Unit Kerja');
          }

          $objPHPExcel->getActiveSheet()->mergeCells('D' . $counter . ':E' . $counter);
          $objPHPExcel->getActiveSheet()->mergeCells('H' . $counter . ':I' . $counter);

          $counter = $counter + 1;
          $last_row_header = $counter;
          $objPHPExcel->setActiveSheetIndex(0)->setCellValue('D' . $counter, 'Bertingkat / Tidak');
          $objPHPExcel->setActiveSheetIndex(0)->setCellValue('E' . $counter, 'Beton / Tidak');
          $objPHPExcel->setActiveSheetIndex(0)->setCellValue('H' . $counter, 'Tanggal');
          $objPHPExcel->setActiveSheetIndex(0)->setCellValue('I' . $counter, 'Nomor');

          $objPHPExcel->getActiveSheet()->mergeCells('A' . $first_row_header . ':A' . $last_row_header);
          $objPHPExcel->getActiveSheet()->mergeCells('B' . $first_row_header . ':B' . $last_row_header);
          $objPHPExcel->getActiveSheet()->mergeCells('C' . $first_row_header . ':C' . $last_row_header);
          $objPHPExcel->getActiveSheet()->mergeCells('F' . $first_row_header . ':F' . $last_row_header);
          $objPHPExcel->getActiveSheet()->mergeCells('G' . $first_row_header . ':G' . $last_row_header);
          $objPHPExcel->getActiveSheet()->mergeCells('J' . $first_row_header . ':J' . $last_row_header);
          $objPHPExcel->getActiveSheet()->mergeCells('K' . $first_row_header . ':K' . $last_row_header);
          $objPHPExcel->getActiveSheet()->mergeCells('L' . $first_row_header . ':L' . $last_row_header);
          $objPHPExcel->getActiveSheet()->mergeCells('M' . $first_row_header . ':M' . $last_row_header);
          $objPHPExcel->getActiveSheet()->mergeCells('N' . $first_row_header . ':N' . $last_row_header);
          $objPHPExcel->getActiveSheet()->mergeCells('O' . $first_row_header . ':O' . $last_row_header);
          $objPHPExcel->getActiveSheet()->mergeCells('P' . $first_row_header . ':P' . $last_row_header);
          $objPHPExcel->getActiveSheet()->mergeCells('Q' . $first_row_header . ':Q' . $last_row_header);
        } else if ($data['kode_jenis'] == '5.03') {
          $counter = 6;
          $first_row_header = $counter;
          $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A' . $counter, 'No');
          $objPHPExcel->setActiveSheetIndex(0)->setCellValue('B' . $counter, 'Kode Barang');
          $objPHPExcel->setActiveSheetIndex(0)->setCellValue('C' . $counter, 'Jenis /Nama barang');
          $objPHPExcel->setActiveSheetIndex(0)->setCellValue('D' . $counter, 'Nomor Register');
          $objPHPExcel->setActiveSheetIndex(0)->setCellValue('E' . $counter, 'Judul Kajian/Nama Software');
          $objPHPExcel->setActiveSheetIndex(0)->setCellValue('F' . $counter, 'Tahun Perolehan');
          $objPHPExcel->setActiveSheetIndex(0)->setCellValue('G' . $counter, 'Asal Usul Cara Perolehan');
          $objPHPExcel->setActiveSheetIndex(0)->setCellValue('H' . $counter, 'Harga (Rp.)');
          $objPHPExcel->setActiveSheetIndex(0)->setCellValue('I' . $counter, 'Keterangan');
          $objPHPExcel->setActiveSheetIndex(0)->setCellValue('J' . $counter, 'ID KIB');
          if (in_array('instansi', $data['field_name'])) {
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('K' . $counter, 'Unit Kerja');
          }


          $counter = $counter + 1;
          $last_row_header = $counter;
        }

        $objPHPExcel->getActiveSheet()->getStyle("A" . $first_row_header . ":" . $last_alpabet . $last_row_header)
          ->applyFromArray($table_header)
          ->getFont()->setSize(12);
          $objPHPExcel->getActiveSheet()->getStyle("A6:R7")
          ->getAlignment()->setWrapText(true);
        //end header

        $ex = $objPHPExcel->setActiveSheetIndex(0);
        $counter = $counter + 1;
		if($data['mulai']){
		$mulai = $data['mulai'];}
		else{
			$mulai = 0;
		}
		
    $no = $mulai == 0 ? $mulai +1 : $mulai;
        $kib = $this->Kib_model->get_all_kib_a_e_limit($data,$mulai);

        // die($this->db->last_query());
        
        foreach ($kib as $value) {
          foreach ($alphabet as $key => $v_alphabet) {
            // if (array_key_exists($key, $field_name)) {
              if ($field_name[$key] == 'sertifikat_tanggal' or $field_name[$key] == 'gedung_tanggal' or $field_name[$key] == 'dokumen_tanggal' or $field_name[$key] == 'tanggal_mulai' or $field_name[$key] == 'tgl_bln_thn_mulai') {
                if ($value[$field_name[$key]]) //cek apakah ada datanya
                {
                  $ex->setCellValue($v_alphabet . $counter, tgl_indo($value[$field_name[$key]]));
                }
              } else if ($field_name[$key] == 'nomor_register') {
                $temp_register = explode(',', $value[$field_name[$key]]);
                $nomor_register = cek_register_kosong($temp_register);
                $ex->setCellValue($v_alphabet . $counter, $nomor_register);
              }else if (($field_name[$key] == 'no')) {
                $ex->setCellValue($v_alphabet . $counter, $no++);
              } else {
                $ex->setCellValue($v_alphabet . $counter, $value[$field_name[$key]]);
              }
              // Should evaluate to FALSE
            // }; 
          }
          $counter = $counter + 1;
        }

        $last_row = $counter - 1;
        $objPHPExcel->getActiveSheet()->getStyle($alphabet[0] . "6:" . $alphabet[$itemcount - 1] . $last_row)
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
      if($last_alpabet == 'Q'){
      $objPHPExcel->getActiveSheet()->getColumnDimension('Q')->setWidth(50);
      }else{
        $objPHPExcel->getActiveSheet()->getColumnDimension('Q')->setWidth(20);
      }
      
      if($last_alpabet == 'R'){
      $objPHPExcel->getActiveSheet()->getColumnDimension('R')->setWidth(50);
      }else{
        $objPHPExcel->getActiveSheet()->getColumnDimension('R')->setWidth(20);
      }
      
      if($last_alpabet == 'S'){
      $objPHPExcel->getActiveSheet()->getColumnDimension('S')->setWidth(50);
      }else{
        $objPHPExcel->getActiveSheet()->getColumnDimension('S')->setWidth(20);
      }
      
      if($last_alpabet == 'T'){
      $objPHPExcel->getActiveSheet()->getColumnDimension('T')->setWidth(50);
      }else{
        $objPHPExcel->getActiveSheet()->getColumnDimension('T')->setWidth(20);
      }
        // foreach ($alphabet as $columnID) {
        //   $objPHPExcel->getActiveSheet()->getColumnDimension($columnID)
        //     ->setAutoSize(true);
        // }
//print_r($field_name
        foreach ($field_name as $key => $value) :
          // if ($value == "luas" or $value == "harga" or $value == "ukuran_cc" or $value == "luas_lantai_m2" or $value == "luas_m2" or $value == "panjang_km" or $value == "lebar_m" or $value == "luas_m2" or $value == "hewan_tumbuhan_ukuran" or $value == "jumlah" or $value == "nilai_kontrak") {
          if ($value == "harga" or $value == "nilai_kontrak") {
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
        header('Chace-Control: post-check=0, pre-check=0', false);
        header('Pragma: no-cache');
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="Export Data ' . $jenis['nama'] . ' ' . date('Y-m-d') . '.xlsx"');

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

        $inventaris = $this->Kib_model->get_kib_aset_lainya($data);
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

        $data['kib'] = $this->Kib_model->get_all($data);
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
        $return = $this->Kib_model->getParentLokasi($kode_lokasi);
        return $return->instansi;
        }
      }
    }
