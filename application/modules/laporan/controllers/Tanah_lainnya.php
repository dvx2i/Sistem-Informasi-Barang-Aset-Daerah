    <?php

    if (!defined('BASEPATH')) {
      exit('No direct script access allowed');
    }

    class Tanah_lainnya extends CI_Controller
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

        $data['content'] = $this->load->view('tanah_lainnya/home', $data, true);
        $data['breadcrumb'] = array(
          array('label' => 'Lampiran LKPD', 'url' => '#', 'icon' => 'fa fa-pie-chart', 'li_class' => ''),
          array('label' => 'Tanah Kekancingan', 'url' => '#', 'icon' => '', 'li_class' => 'active'),
          // array('label' => 'Buat', 'url' => '#','icon'=>'', 'li_class'=>'active'),
        );
        $this->load->view('template', $data);
      }

      public function excel()
      {
        
        $data['kode_jenis'] = '01';
        $data['kode_kelompok'] = '3';
		    $data['mulai'] = $this->input->post('mulai');
        $data['id_pemilik'] = '4'; // Lainnya
        $data['id_pengguna'] = $this->input->post('id_pengguna');
        $data['id_kuasa_pengguna'] = $this->input->post('id_kuasa_pengguna');
        $data['id_sub_kuasa_pengguna'] = $this->input->post('id_sub_kuasa_pengguna');
        $data['intra_ekstra'] = $this->input->post('intra_ekstra');
        $data['atb'] = '';
        // die(json_encode($_POST));
        
        $data['start_date'] = date('Y-m-d', tgl_inter("1 " . $this->input->post('bulan_tahun')));
        $data['last_date'] = date('Y-m-d', tgl_inter("31 " . $this->input->post('last_bulan_tahun')));
        
        if($this->input->post('bulan_tahun') == ''){
          $data['start_date'] = '';
        }
        

        $data['intra_ekstra'] = $this->input->post('intra_ekstra');
        $data['format'] = $this->input->post('format');

          $data['field_name'] = array('no', 'penggunaan', 'luas','letak_alamat', 'sertifikat_nomor','status_hak');
          $data['label'] = array("No", "Peruntukan", "Luas", "Nomor Perjanjain", "Keterangan");
        
        
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
          ->setCellValue('A1', 'DAFTAR PERJANJIAN PINJAM PAKAI TANAH KRATON')
          ->setCellValue('A2', '')
          ->setCellValue('A4', '')
          ->setCellValue('A5', '')
          ->setCellValue($alphabet[$col2] . '5', $data['des_pemilik']);

        $objPHPExcel->getActiveSheet()
          ->getStyle($alphabet[$col2] . '5')
          ->getAlignment()
          ->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

        //header
          $counter = 6;
          $first_row_header = $counter;
          $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A' . $counter, 'No.');

          $objPHPExcel->setActiveSheetIndex(0)->setCellValue('B' . $counter, 'PERUNTUKAN TANAH');
          $objPHPExcel->setActiveSheetIndex(0)->setCellValue('C' . $counter, 'LUAS (M2)');
          $objPHPExcel->setActiveSheetIndex(0)->setCellValue('D' . $counter, 'ALAMAT');
          $objPHPExcel->setActiveSheetIndex(0)->setCellValue('E' . $counter, 'NOMOR PERJANJIAN');
          $objPHPExcel->setActiveSheetIndex(0)->setCellValue('F' . $counter, 'KETERANGAN');
        

        $objPHPExcel->getActiveSheet()->getStyle("A" . $first_row_header . ":F" . $counter)
          ->applyFromArray($table_header)
          ->getFont()->setSize(12);
          $objPHPExcel->getActiveSheet()->getStyle("A6:F6")
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
      $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(55);
      $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(9);
      $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(40);
      $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(20);
      $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(20);
        // foreach ($alphabet as $columnID) {
        //   $objPHPExcel->getActiveSheet()->getColumnDimension($columnID)
        //     ->setAutoSize(true);
        // }
//print_r($field_name

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



        $objPHPExcel->getProperties()->setCreator("Tanah Kekancingan")
          ->setLastModifiedBy("Tanah Kekancingan")
          ->setTitle("Export Data Tanah Kekancingan")
          ->setSubject("Export Tanah Kekancingan To Excel")
          ->setDescription("Doc for Office 2007 XLSX, generated by PHPExcel.")
          ->setKeywords("office 2007 openxml php")
          ->setCategory("PHPExcel");
        $objPHPExcel->getActiveSheet()->setTitle('Data Tanah Kekancingan');

        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
        ob_end_clean(); // MENGHILANGKAN ERROR CANNOT OPEN
        header('Last-Modified:' . gmdate("D, d M Y H:i:s") . 'GMT');
        header('Chace-Control: no-store, no-cache, must-revalation');
        header('Chace-Control: post-check=0, pre-check=0', false);
        header('Pragma: no-cache');
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="Export Data Tanah Kekancingan ' . date('Y-m-d') . '.xlsx"');

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
