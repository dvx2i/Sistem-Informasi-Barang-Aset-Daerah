<?php

if (!defined('BASEPATH')) exit('No direct script access allowed');

class Kib extends CI_Controller{
  function __construct(){
    parent::__construct();
    $this->global_model->cek_hak_akses('18');
    $this->load->model('Kib_model');
    $this->load->model('laporan_model');
    $this->load->helper('my_global');
  }

  public function index(){
    $data['css']=array(
    "assets/bootstrap/bootstrap-datepicker-master/dist/css/bootstrap-datepicker.min.css",
    "assets/jquery/select2-4.0.6-rc.1/dist/css/select2.min.css",
    "assets/jquery/select2-bootstrap-theme-master/dist/select2-bootstrap.min.css",
    'assets/adminlte/plugins/iCheck/all.css',
    "assets/css/laporan.css",
    );
    $data['js']=array(
    "assets/bootstrap/bootstrap-datepicker-master/dist/js/bootstrap-datepicker.min.js",
    "assets/bootstrap/bootstrap-datepicker-master/dist/locales/bootstrap-datepicker.id.min.js",
    "assets/jquery/select2-4.0.6-rc.1/dist/js/select2.min.js",
    'assets/adminlte/plugins/iCheck/icheck.min.js',
    "assets/js/laporan.js",

    );
    $data['kib'] = $this->global_model->kode_jenis;

    $data['pemilik']=$this->global_model->get_pemilik();
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
      $data['kuasa_pengguna'] = $this->global_model->get_view_kuasa_pengguna($lokasi_explode->pengguna,$lokasi_explode->kuasa_pengguna);
      if ($data['kuasa_pengguna']) {
        $data['sub_kuasa_pengguna_list'] = $this->laporan_model->get_sub_kuasa_pengguna($data['kuasa_pengguna']->id_pengguna,$data['kuasa_pengguna']->id_kuasa_pengguna);
        $data['sub_kuasa_pengguna'] = $this->global_model->get_view_sub_kuasa_pengguna($lokasi_explode->pengguna,$lokasi_explode->kuasa_pengguna,$lokasi_explode->sub_kuasa_pengguna);
      }
    }

    $data['content']=$this->load->view('kib/home',$data, true);
    $this->load->view('template', $data);
  }


  public function excel() {
    $data['kode_jenis'] = $this->input->post('kode_jenis');
    $data['id_pemilik'] = $this->input->post('pemilik');
    $data['id_pengguna'] = $this->input->post('id_pengguna');
    $data['id_kuasa_pengguna'] = $this->input->post('id_kuasa_pengguna');
    $data['id_sub_kuasa_pengguna'] = $this->input->post('id_sub_kuasa_pengguna');
    $data['intra_ekstra'] = $this->input->post('intra_ekstra');

    if ($this->input->post('start_date'))
      $data['start_date'] = date('Y-m-d',tgl_inter($this->input->post('start_date')));
    else $data['start_date'] = '';
    if ($this->input->post('last_date'))
      $data['last_date'] = date('Y-m-d',tgl_inter($this->input->post('last_date')));
    else $data['last_date'] = '';

    $data['intra_ekstra'] = $this->input->post('intra_ekstra');
    $data['format'] = $this->input->post('format');

    if ($data['kode_jenis'] == '01') {
      $data['field_name'] = array('no','nama_barang','kode_barang','nomor_register','luas','tahun_pengadaan','letak_alamat','status_hak','sertifikat_tanggal','sertifikat_nomor','penggunaan','asal_usul','harga','keterangan','instansi',);
      $data['label'] = array("No","Nama Barang / Uraian","Kode Barang","Nomor Register","Luas (M2)","Tahun Pengadaan","Letak Alamat","Status Hak","Sertifikat Tanggal","Sertifikat Nomor","Penggunaan","Asal Usul","Harga","Keterangan","Unit Kerja",);
    }
    else if ($data['kode_jenis'] == '02') {
      $data['field_name'] = array('no','kode_barang','nama_barang','nomor_register','merk_type','ukuran_cc','bahan','tahun_pembelian','nomor_pabrik','nomor_rangka','nomor_mesin','nomor_polisi','nomor_bpkb','asal_usul','harga','keterangan','instansi',);
      $data['label'] = array("No","Kode Barang","Nama Barang / Uraian","Nomor Register","Merk Type","Ukuran (CC)","Bahan","Tahun Pembelian","Nomor Pabrik","Nomor Rangka","Nomor Mesin","Nomor Polisi","Nomor Bpkb","Asal Usul","Harga","Keterangan","Unit Kerja",);
    }
    else if ($data['kode_jenis'] == '03') {
      $data['field_name'] = array('no','nama_barang','kode_barang','nomor_register','kondisi_bangunan','bangunan_bertingkat','bangunan_beton','luas_lantai_m2','lokasi_alamat','gedung_tanggal','gedung_nomor','luas_m2','status','nomor_kode_tanah','asal_usul','harga','keterangan','instansi',);
      $data['label'] = array("No","Nama Barang / Uraian","Kode Barang","Nomor Register","Kondisi Bangunan","Bangunan Bertingkat","Bangunan Beton","Luas Lantai (M2)","Lokasi Alamat","Gedung Tanggal","Gedung Nomor","Luas (M2)","Status","Nomor Kode Tanah","Asal Usul","Harga","Keterangan","Unit Kerja",);
    }
    else if ($data['kode_jenis'] == '04') {
      $data['field_name'] = array('no','nama_barang','kode_barang','nomor_register','konstruksi','panjang_km','lebar_m','luas_m2','letak_lokasi','dokumen_tanggal','dokumen_nomor','status_tanah','kode_tanah','asal_usul','harga','kondisi','keterangan','instansi',);
      $data['label'] = array('No','Nama Barang / Uraian','Kode Barang','Nomor Register','Konstruksi','Panjang (KM)','Lebar (M)','Luas (M2)','Letak Lokasi','Dokumen Tanggal','Dokumen Nomor','Status Tanah','Kode Tanah','Asal Usul','Harga','Kondisi','Keterangan',"Instansi",);
    }
    else if ($data['kode_jenis'] == '05') {
      $data['field_name'] = array('no','nama_barang','kode_barang','nomor_register','judul_pencipta','spesifikasi','kesenian_asal_daerah','kesenian_pencipta','kesenian_bahan','hewan_tumbuhan_jenis','hewan_tumbuhan_ukuran','jumlah','tahun_pembelian','asal_usul','harga','keterangan','instansi',);
      $data['label'] = array('No','Nama Barang / Uraian','Kode Barang','Nomor Register','Judul Pencipta','Spesifikasi','Kesenian Asal Daerah','Kesenian Pencipta','Kesenian Bahan','Hewan Tumbuhan Jenis','Hewan Tumbuhan Ukuran','Jumlah','Tahun Pembelian','Asal Usul','Harga','Keterangan',"Unit Kerja",);
    }
    else if ($data['kode_jenis'] == '06') {
      $data['field_name'] = array('no','nama_barang','bangunan','kontruksi_bertingkat','kontruksi_beton','luas_m2','lokasi_alamat','dokumen_tanggal','dokumen_nomor','tanggal_mulai','status_tanah','nomor_kode_tanah','asal_usul','nilai_kontrak','keterangan','instansi',);//,'kode_barang'
      $data['label'] = array('No','Nama Barang / Uraian','Bangunan','Kontruksi Bertingkat','Kontruksi Beton','Luas (M2)','Lokasi Alamat','Dokumen Tanggal','Dokumen Nomor','Tanggal Mulai','Status Tanah','Nomor Kode Tanah','Asal Usul','Nilai Kontrak','Keterangan',"Unit Kerja",);//,'Kode Barang'
    }

    $id_kode_lokasi = null;
    if ($data['id_sub_kuasa_pengguna'] != '') {
      $id_kode_lokasi = $data['id_sub_kuasa_pengguna'];
      array_pop($data['field_name']);
      array_pop($data['label']);
    }
    else if ($data['id_kuasa_pengguna'] != '') {
      $id_kode_lokasi = $data['id_kuasa_pengguna'];
    }
    else if ($data['id_pengguna'] != '') {
      $id_kode_lokasi = $data['id_pengguna'];
    }

    //tertanda
    $tertanda = $this->laporan_model->get_tertanda($id_kode_lokasi);
    $data['tertanda'] = $tertanda->result();
    // die(json_encode($data['tertanda']));
    // $data['des_jabatan'] = array(
    //                         'kepala_skpd' => 'KEPALA SKPD',
    //                         'kepala_unit_kerja' => 'KEPALA UNIT KERJA',
    //                         'pejabat_penata_usaha_barang' => 'PEJABAT PENATA USAHA BARANG',
    //                         'pengurus_barang' => 'PENGURUS BARANG',
    //                         'pengurus_barang_pembantu' => 'PENGURUS BARANG PEMBANTU',
    //                       );

    $result_pemilik = $this->global_model->get_pemilik_by_id($data['id_pemilik']);
    $data['des_pemilik'] = $result_pemilik->kode .' - '.$result_pemilik->nama;
    $result_lokasi = $this->laporan_model->get_lokasi_by_id($id_kode_lokasi);
    $kode_lokasi = $result_pemilik->kode.'.'.$result_lokasi->kode_lokasi;
    $data['kode_lokasi'] = get_intra_extra(remove_star($kode_lokasi),$data['intra_ekstra']);
    $data['nama_lokasi'] = ' - '.$result_lokasi->instansi;

    if ($data['format'] == 'pdf')
      $this->toPdf($data);
    else if ($data['format'] == 'excel')
      $this->toExcel($data);


  }

  public function toExcel($data = null){
    $jenis = $this->global_model->kode_jenis[$data['kode_jenis']];
    $field_name = $data['field_name'];
    $label = $data['label'];

		$itemcount = count($label);

		$alphabet = Array();
    for ($na = 0; $na < $itemcount; $na++) {
        $alphabet[]=$this->generateAlphabet($na);
    }
    $last_alpabet=$alphabet[$itemcount-1];

		$itemfield="";
		foreach ($label as $key => $value) :
			$itemfield.=$value.',';
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



    $objPHPExcel->getActiveSheet()->getStyle("A1:F2")
            ->applyFromArray($header)
            ->getFont()->setSize(13);
    $objPHPExcel->getActiveSheet()->getStyle("A4:F4")
            ->getFont()->setSize(12);
    $objPHPExcel->getActiveSheet()->mergeCells('A1:'.$last_alpabet.'1');
    $objPHPExcel->getActiveSheet()->mergeCells('A2:'.$last_alpabet.'2');
    $objPHPExcel->getActiveSheet()->mergeCells('A3:'.$last_alpabet.'3');

    $col1 = $itemcount/2;
    $col2 = $col1;
    $col1 = $col1-1;


    $objPHPExcel->getActiveSheet()->mergeCells('A4:'.$alphabet[$col1].'4');
    $objPHPExcel->getActiveSheet()->mergeCells($alphabet[$col2].'4:'.$last_alpabet.'4');

    $objPHPExcel->setActiveSheetIndex(0)
        ->setCellValue('A1', 'KARTU INVENTARIS BARANG (KIB)')
        ->setCellValue('A2', $jenis['nama'])
        ->setCellValue('A4', 'No. Kode Lokasi : '.$data['kode_lokasi'].$data['nama_lokasi'])
        ->setCellValue($alphabet[$col2].'4', $data['des_pemilik']);

    $objPHPExcel->getActiveSheet()
      ->getStyle($alphabet[$col2].'4')
      ->getAlignment()
      ->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

    // foreach ($alphabet as $key => $value) {
		// 	$objPHPExcel->setActiveSheetIndex(0)->setCellValue($value.'6', $label[$key]);
		// }



    //header
    $counter = 6; $first_row_header = $counter;
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A'.$counter, 'No.');

    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('B'.$counter, 'Jenis barang / Nama barang');
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('C'.$counter, 'Nomor');

    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('E'.$counter, 'Luas (M2)');
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('F'.$counter, 'Tahun Pengadaan');
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('G'.$counter, 'Letak / Alamat');
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('H'.$counter, 'Status Tanah');

    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('K'.$counter, 'Penggunaan');
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('L'.$counter, 'Asal usul');
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('M'.$counter, 'Harga (Ribuan Rp.)');
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('N'.$counter, 'Keterangan');
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('O'.$counter, 'Unit Kerja');

    $objPHPExcel->getActiveSheet()->mergeCells('C'.$counter.':D'.$counter);
    $objPHPExcel->getActiveSheet()->mergeCells('H'.$counter.':J'.$counter);


    $counter = $counter+1; $midle_row_header = $counter;
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('C'.$counter, 'Kode Barang');
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('D'.$counter, 'Register');
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('H'.$counter, 'Hak');
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('I'.$counter, 'Sertifikat');
    $objPHPExcel->getActiveSheet()->mergeCells('I'.$counter.':J'.$counter);

    $counter = $counter+1; $last_row_header = $counter;
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('I'.$counter, 'Tanggal');
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('J'.$counter, 'Nomor');

    $objPHPExcel->getActiveSheet()->mergeCells('A'.$first_row_header.':A'.$last_row_header);
    $objPHPExcel->getActiveSheet()->mergeCells('B'.$first_row_header.':B'.$last_row_header);
    $objPHPExcel->getActiveSheet()->mergeCells('E'.$first_row_header.':E'.$last_row_header);
    $objPHPExcel->getActiveSheet()->mergeCells('F'.$first_row_header.':F'.$last_row_header);
    $objPHPExcel->getActiveSheet()->mergeCells('G'.$first_row_header.':G'.$last_row_header);
    $objPHPExcel->getActiveSheet()->mergeCells('K'.$first_row_header.':K'.$last_row_header);
    $objPHPExcel->getActiveSheet()->mergeCells('L'.$first_row_header.':L'.$last_row_header);
    $objPHPExcel->getActiveSheet()->mergeCells('M'.$first_row_header.':M'.$last_row_header);
    $objPHPExcel->getActiveSheet()->mergeCells('N'.$first_row_header.':N'.$last_row_header);
    $objPHPExcel->getActiveSheet()->mergeCells('O'.$first_row_header.':O'.$last_row_header);

    $objPHPExcel->getActiveSheet()->mergeCells('C'.$midle_row_header.':C'.$last_row_header);
    $objPHPExcel->getActiveSheet()->mergeCells('D'.$midle_row_header.':D'.$last_row_header);
    $objPHPExcel->getActiveSheet()->mergeCells('H'.$midle_row_header.':H'.$last_row_header);

    $objPHPExcel->getActiveSheet()->getStyle("A".$first_row_header.":".$last_alpabet.$last_row_header)
            ->applyFromArray($table_header)
            ->getFont()->setSize(12);
    //end header




		$ex = $objPHPExcel->setActiveSheetIndex(0);
		$counter = $counter+1;

    $kib = $this->Kib_model->get_all($data);
    foreach ($kib as $value) {
      foreach ($alphabet as $key => $v_alphabet) {
        if ($field_name[$key] == 'sertifikat_tanggal' or $field_name[$key] == 'gedung_tanggal' or $field_name[$key] == 'dokumen_tanggal' or $field_name[$key] == 'tanggal_mulai')
          $ex->setCellValue($v_alphabet.$counter, tgl_indo($value[$field_name[$key]]));
        else
          $ex->setCellValue($v_alphabet.$counter, $value[$field_name[$key]]);
      }
      $counter = $counter+1;
    }

    $last_row = $counter-1;
    $objPHPExcel->getActiveSheet()->getStyle($alphabet[0]."6:".$alphabet[$itemcount-1].$last_row)
            ->applyFromArray($borderStyleArray);

		// foreach($alphabet as $columnID) {
		//     $objPHPExcel->getActiveSheet()->getColumnDimension($columnID)
		//         ->setAutoSize(true);
		// }
    // foreach($alphabet as $columnID) {
		//     $objPHPExcel->getActiveSheet()->getColumnDimension($columnID)
		//         ->setAutoSize(FALSE);
		// }
    //
    // foreach($alphabet as $columnID) {
    //   $value1 =  $objPHPExcel->setActiveSheetIndex(0)->getCell('A'.$first_row_header)->getValue();
    //   $value2 =  $objPHPExcel->setActiveSheetIndex(0)->getCell('A'.$las_row_header)->getValue();
    //   $width = mb_strwidth ($value); //Return the width of the string
    //   $objPHPExcel->setActiveSheetIndex(0)->getColumnDimension('A')->setWidth($width+2);
		// }
    $objPHPExcel->setActiveSheetIndex(0)->getColumnDimension('A')->setWidth(5);
    $objPHPExcel->setActiveSheetIndex(0)->getColumnDimension('B')->setWidth(40);
    $objPHPExcel->setActiveSheetIndex(0)->getColumnDimension('C')->setWidth(20);
    $objPHPExcel->setActiveSheetIndex(0)->getColumnDimension('D')->setWidth(15);
    $objPHPExcel->setActiveSheetIndex(0)->getColumnDimension('E')->setWidth(15);
    $objPHPExcel->setActiveSheetIndex(0)->getColumnDimension('F')->setWidth(20);
    $objPHPExcel->setActiveSheetIndex(0)->getColumnDimension('G')->setWidth(15);
    $objPHPExcel->setActiveSheetIndex(0)->getColumnDimension('H')->setWidth(15);
    $objPHPExcel->setActiveSheetIndex(0)->getColumnDimension('I')->setWidth(18);
    $objPHPExcel->setActiveSheetIndex(0)->getColumnDimension('J')->setWidth(15);
    $objPHPExcel->setActiveSheetIndex(0)->getColumnDimension('K')->setWidth(15);
    $objPHPExcel->setActiveSheetIndex(0)->getColumnDimension('L')->setWidth(15);
    $objPHPExcel->setActiveSheetIndex(0)->getColumnDimension('M')->setWidth(20);
    $objPHPExcel->setActiveSheetIndex(0)->getColumnDimension('N')->setWidth(30); //$objPHPExcel->getActiveSheet()->getStyle('N9:N10')->getAlignment()->setWrapText(true);
    $objPHPExcel->setActiveSheetIndex(0)->getColumnDimension('O')->setWidth(15);


		foreach ($field_name as $key => $value) :
			if ( $value == "luas" or $value == "harga" or $value == "ukuran_cc" or $value == "luas_lantai_m2" or $value == "luas_m2" or $value == "panjang_km" or $value == "lebar_m" or $value == "luas_m2" or $value == "hewan_tumbuhan_ukuran" or $value == "jumlah"  or $value == "nilai_kontrak") {
				$cell=$alphabet[$key]."1:".$alphabet[$key].$counter;
        $objPHPExcel->getActiveSheet()->getStyle($cell)->getNumberFormat()->setFormatCode('#,##0');
			}
		endforeach;


    $first_row = $counter+2;
    $last_row = $counter+8;
    $counter = $counter+2;

    $selisih_bagi_3 = $itemcount/3;
    $last_col1 = $selisih_bagi_3 - 1;

    $start_col2 = $last_col1+1;
    $last_col2 = ($selisih_bagi_3 * 2)-1;

    $start_col3 = $last_col2+1;
    $last_col = $itemcount-1;



    if (isset($data['tertanda'][0]) or isset($data['tertanda'][1]) or isset($data['tertanda'][2])) {
      $objPHPExcel->getActiveSheet()->getStyle($alphabet[0].$first_row.":".$alphabet[$last_col].$last_row)->applyFromArray($header)->getFont()->setSize(10);

      $objPHPExcel->getActiveSheet()->mergeCells($alphabet[0].$counter.':'.$alphabet[$last_col1].$counter);
      $objPHPExcel->getActiveSheet()->mergeCells($alphabet[$start_col3].$counter.':'.$alphabet[$last_col].$counter);
      $objPHPExcel->setActiveSheetIndex(0)->setCellValue($alphabet[0].$counter, 'MENGETAHUI');
      $objPHPExcel->setActiveSheetIndex(0)->setCellValue($alphabet[$start_col3].$counter, 'Yogyakarta, '.tgl_indo(date('Y-m-d')));

      $counter = $counter+1;
      $objPHPExcel->getActiveSheet()->mergeCells($alphabet[0].$counter.':'.$alphabet[$last_col1].$counter);
      $objPHPExcel->getActiveSheet()->mergeCells($alphabet[$start_col2].$counter.':'.$alphabet[$last_col2].$counter);
      $objPHPExcel->getActiveSheet()->mergeCells($alphabet[$start_col3].$counter.':'.$alphabet[$last_col].$counter);
      if (isset($data['tertanda'][0]))
      $objPHPExcel->setActiveSheetIndex(0)->setCellValue($alphabet[0].$counter, $data['tertanda'][0]->jabatan);
      if (isset($data['tertanda'][1]))
      $objPHPExcel->setActiveSheetIndex(0)->setCellValue($alphabet[$start_col2].$counter, $data['tertanda'][1]->jabatan);
      if (isset($data['tertanda'][2]))
      $objPHPExcel->setActiveSheetIndex(0)->setCellValue($alphabet[$start_col3].$counter, $data['tertanda'][2]->jabatan);

      $counter = $counter+4;
      $objPHPExcel->getActiveSheet()->mergeCells($alphabet[0].$counter.':'.$alphabet[$last_col1].$counter);
      $objPHPExcel->getActiveSheet()->mergeCells($alphabet[$start_col2].$counter.':'.$alphabet[$last_col2].$counter);
      $objPHPExcel->getActiveSheet()->mergeCells($alphabet[$start_col3].$counter.':'.$alphabet[$last_col].$counter);
      if (isset($data['tertanda'][0]))
      $objPHPExcel->setActiveSheetIndex(0)->setCellValue($alphabet[0].$counter, '( '.$data['tertanda'][0]->nama.' )');
      if (isset($data['tertanda'][1]))
      $objPHPExcel->setActiveSheetIndex(0)->setCellValue($alphabet[$start_col2].$counter, '( '.$data['tertanda'][1]->nama.' )');
      if (isset($data['tertanda'][2]))
      $objPHPExcel->setActiveSheetIndex(0)->setCellValue($alphabet[$start_col3].$counter, '( '.$data['tertanda'][2]->nama.' )');

      $counter = $counter+1;
      $objPHPExcel->getActiveSheet()->mergeCells($alphabet[0].$counter.':'.$alphabet[$last_col1].$counter);
      $objPHPExcel->getActiveSheet()->mergeCells($alphabet[$start_col2].$counter.':'.$alphabet[$last_col2].$counter);
      $objPHPExcel->getActiveSheet()->mergeCells($alphabet[$start_col3].$counter.':'.$alphabet[$last_col].$counter);
      if (isset($data['tertanda'][0]))
      $objPHPExcel->setActiveSheetIndex(0)->setCellValue($alphabet[0].$counter, 'NIP. '.$data['tertanda'][0]->nip);
      if (isset($data['tertanda'][1]))
      $objPHPExcel->setActiveSheetIndex(0)->setCellValue($alphabet[$start_col2].$counter, 'NIP. '.$data['tertanda'][1]->nip);
      if (isset($data['tertanda'][2]))
      $objPHPExcel->setActiveSheetIndex(0)->setCellValue($alphabet[$start_col3].$counter, 'NIP. '.$data['tertanda'][2]->nip);
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
		header('Last-Modified:'. gmdate("D, d M Y H:i:s").'GMT');
		header('Chace-Control: no-store, no-cache, must-revalation');
		header('Chace-Control: post-check=0, pre-check=0', FALSE);
		header('Pragma: no-cache');
		header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
		header('Content-Disposition: attachment;filename="Export Data KIB '. date('Y-m-d') .'.xlsx"');

		$objWriter->save('php://output');
		die();
	}
  function generateAlphabet($na) {
	     $sa = "";
	     while ($na >= 0) {
	         $sa = chr($na % 26 + 65) . $sa;
	         $na = floor($na / 26) - 1;
	     }
	     return $sa;
	}

  public function toPdf($data = null){
    $this->load->library('PdfGenerator');
    $data['jenis'] = $this->global_model->kode_jenis[$data['kode_jenis']];

    $data['kib'] = $this->Kib_model->get_all($data);

		$html = $this->load->view('kib/report_pdf_dev', $data, true);
    $this->pdfgenerator->generate($html,'KIB', true, 'folio', 'landscape');
    // $this->load->view('kib/report_pdf', $data);

  }

}
