<?php

if (!defined('BASEPATH')) exit('No direct script access allowed');

class Penghapusan extends MX_Controller
{
  function __construct()
  {
    parent::__construct();
    $this->global_model->cek_hak_akses('71'); //penghapusan laporan
    $this->load->model('penghapusan_model');
    $this->load->library('datatables');
    $this->load->helper('my_global');
  }

  public function index()
  {
    $data['css'] = array(
      'assets/datatables/dataTables.bootstrap.css',
    );

    $data['js'] = array(
      'assets/datatables/jquery.dataTables.js',
      'assets/datatables/dataTables.bootstrap.js'
    );

    $data['content'] = $this->load->view('penghapusan/list', $data, true);
    $data['breadcrumb'] = array(
      array('label' => 'Penghapusan', 'url' => '#', 'icon' => 'fa fa-pie-chart', 'li_class' => ''),
      array('label' => 'Lampiran', 'url' => '#', 'icon' => '', 'li_class' => 'active'),
      // array('label' => 'Detail', 'url' => '#','icon'=>'', 'li_class'=>'active'),
    );
    $this->load->view('template', $data);
  }


  public function json_list()
  {
    header('Content-Type: application/json');
    echo $this->penghapusan_model->json_list();
  }


  public function laporan_detail($id_pengahapusan_lampiran)
  {
    $data['id_pengahapusan_lampiran'] = $id_pengahapusan_lampiran;
    
    $currMonth = date('m');
    $currYear = date('Y');
    $beforeYear = $currYear-1;

    if($currMonth > 6){
      $lastPenyusutan = date('Y-m-d', strtotime($currYear.'-06-30'));
    }else{
      $lastPenyusutan = date('Y-m-d', strtotime($beforeYear.'-12-31'));
    }
    // die($data['id_pengahapusan_lampiran']);
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
    $field_name = array("no", "nama_barang", "merk_type", "tanggal_pembelian", "kode_barang", "nomor_register", "asal_usul", "harga_barang", "nilai_penyusutan", "akumulasi_penyusutan", "nilai_buku","keterangan", "skpd", "instansi");
    $header_label = array('No', 'Nama Barang', 'Merk / tipe', 'Tanggal Beli', 'No. Kode Barang', 'No. Register', 'Asal Usul', 'Harga Barang (Rp.)', 'Nilai Penyusutan(Rp.)', 'Akumulasi Penyusutan(Rp.)', 'Nilai Buku(Rp.)', 'Keterangan', 'Nama SKPD', 'Nama Lokasi');
    $itemcount = count($header_label);
    $alphabet = array();
    for ($na = 0; $na < $itemcount; $na++) {
      $alphabet[] = $this->generateAlphabet($na);
    }
    $last_alpabet = $alphabet[$itemcount - 1];

    //header
    $counter = 1;
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A' . $counter, 'Lampiran Surat Keputusan');
    $objPHPExcel->getActiveSheet()->mergeCells('A' . $counter . ':' . $last_alpabet . $counter);
    $objPHPExcel->getActiveSheet()->getStyle("A1:" . $last_alpabet . $counter)
      ->applyFromArray($header)
      ->getFont()->setSize(12);
    $counter = 3;
    $counter_content = $counter;
    foreach ($header_label as $key => $value) {
      $objPHPExcel->setActiveSheetIndex(0)->setCellValue($alphabet[$key] . $counter, $value);
    }
    $counter++;

    $lampiran = $this->penghapusan_model->get_lampiran($id_pengahapusan_lampiran, $lastPenyusutan);
    // die(json_encode($lampiran));
    foreach ($lampiran as $key => $value) {
      foreach ($alphabet as $key2 => $value2) {
        if ($field_name[$key2] == 'tanggal_pembelian' and $value[$field_name[$key2]] != '') {
          $objPHPExcel->setActiveSheetIndex(0)->setCellValue($value2 . $counter, tgl_indo($value[$field_name[$key2]]));
        } if ($field_name[$key2] == 'keterangan') {
          if(!empty($value['id_inventaris'])){
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue($value2 . $counter, $value[$field_name[$key2]].' / '.$value['id_inventaris']);
          }else{
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue($value2 . $counter, $value[$field_name[$key2]]);
          }
        } else {
          $objPHPExcel->setActiveSheetIndex(0)->setCellValue($value2 . $counter, $value[$field_name[$key2]]);
        }
      }
      $counter++;
    }
    $counter--;
    $objPHPExcel->getActiveSheet()->getStyle("A" . $counter_content . ":" . $last_alpabet . $counter)
      ->applyFromArray($borderStyleArray);


    foreach ($field_name as $key => $value) :
      if ($value == "harga_barang" or $value == "akumulasi_penyusutan" or $value == "nilai_buku" or $value == "nilai_penyusutan") {
        $cell = $alphabet[$key] . $counter_content . ":" . $alphabet[$key] . $counter;
        $objPHPExcel->getActiveSheet()->getStyle($cell)->getNumberFormat()->setFormatCode('#,##0');
      }
    endforeach;


    //autosize
    foreach ($alphabet as $columnID) {
      $objPHPExcel->getActiveSheet()->getColumnDimension($columnID)
        ->setAutoSize(true);
    }
    $objPHPExcel->getProperties()->setCreator("LAMPIRAN")
      ->setLastModifiedBy("LAMPIRAN")
      ->setTitle("Lampiran Penghapusan")
      ->setSubject("Lampiran Penghapusan")
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
    header('Content-Disposition: attachment;filename="Lampiran Penghapusan ' . date('Y-m-d') . '.xlsx"');

    $objWriter->save('php://output');
    die();
  }


  public function laporan_rekap($id_pengahapusan_lampiran)
  {
    $data['id_pengahapusan_lampiran'] = $id_pengahapusan_lampiran;
    // die($data['id_pengahapusan_lampiran']);
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

    $center = array(
      'alignment' => array(
        'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
        'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,
      ),
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

    $border_bottom = array(
      'borders' => array(
        'bottom' => array(
          'style' => PHPExcel_Style_Border::BORDER_THIN
        )
      )
    );

    $field_name = array("no", "instansi", "jumlah_barang", "jumlah_harga", "jumlah_akumulasi_penyusutan", "jumlah_nilai_buku");
    $header_label = array('No', 'Nama Lokasi', 'Jumlah Barang', 'Jumlah Harga(Rp.)', 'Jumlah Akumulasi Penyusutan(Rp.)', 'Jumlah Nilai Buku(Rp.)');
    $itemcount = count($header_label);
    $alphabet = array();
    for ($na = 0; $na < $itemcount; $na++) {
      $alphabet[] = $this->generateAlphabet($na);
    }
    $last_alpabet = $alphabet[$itemcount - 1];

    //header
    $counter = 1;
    $counter_judul = $counter;
    $objPHPExcel->getActiveSheet()->mergeCells('C' . $counter . ':' . $last_alpabet . $counter);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('C' . $counter, 'LAMPIRAN I KEPUTUSAN WALI KOTA YOGYAKARTA');
    $counter++;
    $objPHPExcel->getActiveSheet()->mergeCells('C' . $counter . ':' . $last_alpabet . $counter);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('C' . $counter, 'NOMOR');
    $counter++;
    $objPHPExcel->getActiveSheet()->mergeCells('C' . $counter . ':' . $last_alpabet . $counter);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('C' . $counter, 'TENTANG PENGHAPUSAN BARANG MILIK DAERAH BERUPA NON KENDARAAN');
    $counter++;
    $objPHPExcel->getActiveSheet()->mergeCells('C' . $counter . ':' . $last_alpabet . $counter);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('C' . $counter, 'BERMOTOR DARI DAFTAR BARANG PENGGUNA BARANG/KUASA PENGGUNA');
    $counter++;
    $objPHPExcel->getActiveSheet()->mergeCells('C' . $counter . ':' . $last_alpabet . $counter);
    $objPHPExcel->getActiveSheet()->getStyle("C" . $counter . ":" . $last_alpabet . $counter)
      ->applyFromArray($border_bottom);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('C' . $counter, 'BARANG DILINGKUNGAN PEMERINTAH KOTA YOGYAKARTA');



    $counter = $counter + 2;
    $counter_judul2 = $counter;
    $objPHPExcel->getActiveSheet()->mergeCells('A' . $counter . ':' . $last_alpabet . $counter);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A' . $counter, 'REKAPITULASI DAFTAR BARANG INVENTARIS NON KENDARAAN MILIK PEMERINTAH KOTA YOGYAKARTA');
    $counter++;
    $objPHPExcel->getActiveSheet()->mergeCells('A' . $counter . ':' . $last_alpabet . $counter);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A' . $counter, 'YANG TELAH DIJUAL DAN DIHAPUS');

    $objPHPExcel->getActiveSheet()->getStyle("A" . $counter_judul2 . ":" . $last_alpabet . $counter)
      ->applyFromArray($center);

    // $objPHPExcel->getActiveSheet()->getStyle("A1:".$last_alpabet.$counter)
    //         ->applyFromArray($header)
    //         ->getFont()->setSize(12);

    $counter = $counter + 2;
    $counter_content = $counter;
    foreach ($header_label as $key => $value) {
      $objPHPExcel->setActiveSheetIndex(0)->setCellValue($alphabet[$key] . $counter, $value);
    }

    $counter++;
    $lampiran = $this->penghapusan_model->get_lampiran_rekap($id_pengahapusan_lampiran);

    // die(json_encode($lampiran));
    foreach ($lampiran as $key => $value) {
      foreach ($alphabet as $key2 => $value2) {
        if ($field_name[$key2] == 'tanggal_pembelian') {
          // $objPHPExcel->setActiveSheetIndex(0)->setCellValue($value2.$counter, tgl_indo($value[$field_name[$key2]]));
        } else {
          $objPHPExcel->setActiveSheetIndex(0)->setCellValue($value2 . $counter, $value[$field_name[$key2]]);
        }
      }
      $counter++;
    }
    $counter--;
    $objPHPExcel->getActiveSheet()->getStyle("A" . $counter_content . ":" . $last_alpabet . $counter)
      ->applyFromArray($borderStyleArray);


    foreach ($field_name as $key => $value) :
      if ($value == "jumlah_barang" or $value == "jumlah_harga" or $value == "jumlah_akumulasi_penyusutan" or $value == "jumlah_nilai_buku") {
        $cell = $alphabet[$key] . $counter_content . ":" . $alphabet[$key] . $counter;
        $objPHPExcel->getActiveSheet()->getStyle($cell)->getNumberFormat()->setFormatCode('#,##0');
      }
    endforeach;

    //autosize
    foreach ($alphabet as $columnID) {
      $objPHPExcel->getActiveSheet()->getColumnDimension($columnID)
        ->setAutoSize(true);
    }
    $objPHPExcel->getProperties()->setCreator("LAMPIRAN")
      ->setLastModifiedBy("LAMPIRAN")
      ->setTitle("Lampiran Penghapusan")
      ->setSubject("Lampiran Penghapusan")
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
    header('Content-Disposition: attachment;filename="Lampiran Penghapusan ' . date('Y-m-d') . '.xlsx"');

    $objWriter->save('php://output');
    die();
  }

  public function toExcel_20_nov_2018($id_pengahapusan_lampiran)
  {
    $data['id_pengahapusan_lampiran'] = $id_pengahapusan_lampiran;

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

    //header
    $counter = 2;
    $data['kode_jenis'] = '1';
    $result_1 = $this->penghapusan_model->get_lampiran($data);
    if ($result_1) {
      $field_name = array('no', 'nama_barang', 'kode_barang', 'nomor_register', 'luas', 'tahun_pengadaan', 'letak_alamat', 'status_hak', 'sertifikat_tanggal', 'sertifikat_nomor', 'penggunaan', 'asal_usul', 'harga', 'keterangan');
      $label = array("No", "Nama Barang / Uraian", "Kode Barang", "Nomor Register", "Luas (M2)", "Tahun Pengadaan", "Letak Alamat", "Status Hak", "Sertifikat Tanggal", "Sertifikat Nomor", "Penggunaan", "Asal Usul", "Harga", "Keterangan");
      $itemcount = count($label);
      $alphabet = array();
      for ($na = 0; $na < $itemcount; $na++) {
        $alphabet[] = $this->generateAlphabet($na);
      }
      $last_alpabet = $alphabet[$itemcount - 1];

      $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A' . $counter, 'KIB A (Tanah)');
      $counter = $counter + 1;
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
      $objPHPExcel->setActiveSheetIndex(0)->setCellValue('N' . $counter, 'Keterangan');
      // if (in_array('instansi', $data['field_name']) )
      //   $objPHPExcel->setActiveSheetIndex(0)->setCellValue('O'.$counter, 'Unit Kerja');



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

      $objPHPExcel->getActiveSheet()->mergeCells('C' . $midle_row_header . ':C' . $last_row_header);
      $objPHPExcel->getActiveSheet()->mergeCells('D' . $midle_row_header . ':D' . $last_row_header);
      $objPHPExcel->getActiveSheet()->mergeCells('H' . $midle_row_header . ':H' . $last_row_header);


      $ex = $objPHPExcel->setActiveSheetIndex(0);
      $counter = $counter + 1;

      foreach ($result_1 as $value) {
        foreach ($alphabet as $key => $v_alphabet) {
          if ($field_name[$key] == 'sertifikat_tanggal' or $field_name[$key] == 'gedung_tanggal' or $field_name[$key] == 'dokumen_tanggal' or $field_name[$key] == 'tanggal_mulai')
            $ex->setCellValue($v_alphabet . $counter, tgl_indo($value[$field_name[$key]]));
          else if ($field_name[$key] == 'nomor_register') {
            $temp_register =  explode(',', $value[$field_name[$key]]);
            $nomor_register = count($temp_register) > 1 ? $temp_register[0] . ' - ' . $temp_register[(count($temp_register) - 1)] : $temp_register[0];
            $ex->setCellValue($v_alphabet . $counter, $nomor_register);
          } else
            $ex->setCellValue($v_alphabet . $counter, $value[$field_name[$key]]);
        }
        $counter = $counter + 1;
      }
    }

    $data['kode_jenis'] = '2';
    $result_2 = $this->penghapusan_model->get_lampiran($data);
    if ($result_2) {
      $field_name = array('no', 'kode_barang', 'nama_barang', 'nomor_register', 'merk_type', 'ukuran_cc', 'bahan', 'tahun_pembelian', 'nomor_pabrik', 'nomor_rangka', 'nomor_mesin', 'nomor_polisi', 'nomor_bpkb', 'asal_usul', 'harga', 'keterangan');
      $label = array("No", "Kode Barang", "Nama Barang / Uraian", "Nomor Register", "Merk Type", "Ukuran (CC)", "Bahan", "Tahun Pembelian", "Nomor Pabrik", "Nomor Rangka", "Nomor Mesin", "Nomor Polisi", "Nomor Bpkb", "Asal Usul", "Harga", "Keterangan");
      $itemcount = count($label);
      $alphabet = array();
      for ($na = 0; $na < $itemcount; $na++) {
        $alphabet[] = $this->generateAlphabet($na);
      }

      $counter = $counter + 3;
      $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A' . $counter, 'KIB B (Peralatan & Mesin)');
      $counter = $counter + 1;
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
      $objPHPExcel->setActiveSheetIndex(0)->setCellValue('P' . $counter, 'Keterangan');
      // if (in_array('instansi', $data['field_name']) )
      //   $objPHPExcel->setActiveSheetIndex(0)->setCellValue('Q'.$counter, 'Unit Kerja');
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

      $ex = $objPHPExcel->setActiveSheetIndex(0);
      $counter = $counter + 1;

      foreach ($result_2 as $value) {
        foreach ($alphabet as $key => $v_alphabet) {
          if ($field_name[$key] == 'sertifikat_tanggal' or $field_name[$key] == 'gedung_tanggal' or $field_name[$key] == 'dokumen_tanggal' or $field_name[$key] == 'tanggal_mulai')
            $ex->setCellValue($v_alphabet . $counter, tgl_indo($value[$field_name[$key]]));
          else if ($field_name[$key] == 'nomor_register') {
            $temp_register =  explode(',', $value[$field_name[$key]]);
            $nomor_register = count($temp_register) > 1 ? $temp_register[0] . ' - ' . $temp_register[(count($temp_register) - 1)] : $temp_register[0];
            $ex->setCellValue($v_alphabet . $counter, $nomor_register);
          } else
            $ex->setCellValue($v_alphabet . $counter, $value[$field_name[$key]]);
        }
        $counter = $counter + 1;
      }
    }

    $data['kode_jenis'] = '3';
    $result_3 = $this->penghapusan_model->get_lampiran($data);
    if ($result_3) {
      $data['field_name'] = array('no', 'nama_barang', 'kode_barang', 'nomor_register', 'kondisi_bangunan', 'bangunan_bertingkat', 'bangunan_beton', 'luas_lantai_m2', 'lokasi_alamat', 'gedung_tanggal', 'gedung_nomor', 'luas_m2', 'status', 'nomor_kode_tanah', 'asal_usul', 'harga', 'keterangan');
      $data['label'] = array("No", "Nama Barang / Uraian", "Kode Barang", "Nomor Register", "Kondisi Bangunan", "Bangunan Bertingkat", "Bangunan Beton", "Luas Lantai (M2)", "Lokasi Alamat", "Gedung Tanggal", "Gedung Nomor", "Luas (M2)", "Status", "Nomor Kode Tanah", "Asal Usul", "Harga", "Keterangan");
      $itemcount = count($label);
      $alphabet = array();
      for ($na = 0; $na < $itemcount; $na++) {
        $alphabet[] = $this->generateAlphabet($na);
      }

      $counter = $counter + 3;
      $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A' . $counter, 'KIB C (Gedung & Bangunan)');
      $counter = $counter + 1;
      $first_row_header = $counter;
      $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A' . $counter, 'No. Urt');
      $objPHPExcel->setActiveSheetIndex(0)->setCellValue('B' . $counter, 'Nama Barang / Jenis Barang');
      $objPHPExcel->setActiveSheetIndex(0)->setCellValue('C' . $counter, 'Nomor');
      $objPHPExcel->setActiveSheetIndex(0)->setCellValue('E' . $counter, 'Kondisi Bangunan (B, KB, RB)');
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
      // if (in_array('instansi', $data['field_name']) )
      //   $objPHPExcel->setActiveSheetIndex(0)->setCellValue('R'.$counter, 'Unit Kerja');
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

      foreach ($result_3 as $value) {
        foreach ($alphabet as $key => $v_alphabet) {
          if ($field_name[$key] == 'sertifikat_tanggal' or $field_name[$key] == 'gedung_tanggal' or $field_name[$key] == 'dokumen_tanggal' or $field_name[$key] == 'tanggal_mulai')
            $ex->setCellValue($v_alphabet . $counter, tgl_indo($value[$field_name[$key]]));
          else if ($field_name[$key] == 'nomor_register') {
            $temp_register =  explode(',', $value[$field_name[$key]]);
            $nomor_register = count($temp_register) > 1 ? $temp_register[0] . ' - ' . $temp_register[(count($temp_register) - 1)] : $temp_register[0];
            $ex->setCellValue($v_alphabet . $counter, $nomor_register);
          } else
            $ex->setCellValue($v_alphabet . $counter, $value[$field_name[$key]]);
        }
        $counter = $counter + 1;
      }
    }

    $data['kode_jenis'] = '4';
    $result_4 = $this->penghapusan_model->get_lampiran($data);
    if ($result_4) {
      $data['field_name'] = array('no', 'nama_barang', 'kode_barang', 'nomor_register', 'konstruksi', 'panjang_km', 'lebar_m', 'luas_m2', 'letak_lokasi', 'dokumen_tanggal', 'dokumen_nomor', 'status_tanah', 'kode_tanah', 'asal_usul', 'harga', 'kondisi', 'keterangan');
      $data['label'] = array('No', 'Nama Barang / Uraian', 'Kode Barang', 'Nomor Register', 'Konstruksi', 'Panjang (KM)', 'Lebar (M)', 'Luas (M2)', 'Letak Lokasi', 'Dokumen Tanggal', 'Dokumen Nomor', 'Status Tanah', 'Kode Tanah', 'Asal Usul', 'Harga', 'Kondisi', 'Keterangan');
      $itemcount = count($label);
      $alphabet = array();
      for ($na = 0; $na < $itemcount; $na++) {
        $alphabet[] = $this->generateAlphabet($na);
      }

      $counter = $counter + 3;
      $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A' . $counter, 'KIB D (Jalan, Irigasi & Jaringan)');
      $counter = $counter + 1;
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
      $objPHPExcel->setActiveSheetIndex(0)->setCellValue('P' . $counter, 'Kondisi (B, KB, RB)');
      $objPHPExcel->setActiveSheetIndex(0)->setCellValue('Q' . $counter, 'Ket.');
      // if (in_array('instansi', $data['field_name']) )
      //   $objPHPExcel->setActiveSheetIndex(0)->setCellValue('R'.$counter, 'Unit Kerja');
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

      foreach ($result_4 as $value) {
        foreach ($alphabet as $key => $v_alphabet) {
          if ($field_name[$key] == 'sertifikat_tanggal' or $field_name[$key] == 'gedung_tanggal' or $field_name[$key] == 'dokumen_tanggal' or $field_name[$key] == 'tanggal_mulai')
            $ex->setCellValue($v_alphabet . $counter, tgl_indo($value[$field_name[$key]]));
          else if ($field_name[$key] == 'nomor_register') {
            $temp_register =  explode(',', $value[$field_name[$key]]);
            $nomor_register = count($temp_register) > 1 ? $temp_register[0] . ' - ' . $temp_register[(count($temp_register) - 1)] : $temp_register[0];
            $ex->setCellValue($v_alphabet . $counter, $nomor_register);
          } else
            $ex->setCellValue($v_alphabet . $counter, $value[$field_name[$key]]);
        }
        $counter = $counter + 1;
      }
    }

    $data['kode_jenis'] = '5';
    $result_5 = $this->penghapusan_model->get_lampiran($data);
    if ($result_5) {
      $data['field_name'] = array('no', 'nama_barang', 'kode_barang', 'nomor_register', 'judul_pencipta', 'spesifikasi', 'kesenian_asal_daerah', 'kesenian_pencipta', 'kesenian_bahan', 'hewan_tumbuhan_jenis', 'hewan_tumbuhan_ukuran', 'jumlah', 'tahun_pembelian', 'asal_usul', 'harga', 'keterangan');
      $data['label'] = array('No', 'Nama Barang / Uraian', 'Kode Barang', 'Nomor Register', 'Judul Pencipta', 'Spesifikasi', 'Kesenian Asal Daerah', 'Kesenian Pencipta', 'Kesenian Bahan', 'Hewan Tumbuhan Jenis', 'Hewan Tumbuhan Ukuran', 'Jumlah', 'Tahun Pembelian', 'Asal Usul', 'Harga', 'Keterangan');
      $itemcount = count($label);
      $alphabet = array();
      for ($na = 0; $na < $itemcount; $na++) {
        $alphabet[] = $this->generateAlphabet($na);
      }

      $counter = $counter + 3;
      $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A' . $counter, 'KIB E (Aset Tetap Lainya)');
      $counter = $counter + 1;
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
      $objPHPExcel->setActiveSheetIndex(0)->setCellValue('P' . $counter, 'Ket.');
      // if (in_array('instansi', $data['field_name']) )
      //   $objPHPExcel->setActiveSheetIndex(0)->setCellValue('Q'.$counter, 'Unit Kerja');
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

      foreach ($result_5 as $value) {
        foreach ($alphabet as $key => $v_alphabet) {
          if ($field_name[$key] == 'sertifikat_tanggal' or $field_name[$key] == 'gedung_tanggal' or $field_name[$key] == 'dokumen_tanggal' or $field_name[$key] == 'tanggal_mulai')
            $ex->setCellValue($v_alphabet . $counter, tgl_indo($value[$field_name[$key]]));
          else if ($field_name[$key] == 'nomor_register') {
            $temp_register =  explode(',', $value[$field_name[$key]]);
            $nomor_register = count($temp_register) > 1 ? $temp_register[0] . ' - ' . $temp_register[(count($temp_register) - 1)] : $temp_register[0];
            $ex->setCellValue($v_alphabet . $counter, $nomor_register);
          } else
            $ex->setCellValue($v_alphabet . $counter, $value[$field_name[$key]]);
        }
        $counter = $counter + 1;
      }
    }

    $data['kode_jenis'] = '6';
    $result_6 = $this->penghapusan_model->get_lampiran($data);
    if ($result_6) {
      $data['field_name'] = array('no', 'nama_barang', 'bangunan', 'kontruksi_bertingkat', 'kontruksi_beton', 'luas_m2', 'lokasi_alamat', 'dokumen_tanggal', 'dokumen_nomor', 'tanggal_mulai', 'status_tanah', 'nomor_kode_tanah', 'asal_usul', 'nilai_kontrak', 'keterangan');
      $data['label'] = array('No', 'Nama Barang / Uraian', 'Bangunan', 'Kontruksi Bertingkat', 'Kontruksi Beton', 'Luas (M2)', 'Lokasi Alamat', 'Dokumen Tanggal', 'Dokumen Nomor', 'Tanggal Mulai', 'Status Tanah', 'Nomor Kode Tanah', 'Asal Usul', 'Nilai Kontrak', 'Keterangan');
      $itemcount = count($label);
      $alphabet = array();
      for ($na = 0; $na < $itemcount; $na++) {
        $alphabet[] = $this->generateAlphabet($na);
      }

      $counter = $counter + 3;
      $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A' . $counter, 'KIB F (Konstruksi Dalam Pengerjaan)');
      $counter = $counter + 1;
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
      // if (in_array('instansi', $data['field_name']) )
      //   $objPHPExcel->setActiveSheetIndex(0)->setCellValue('P'.$counter, 'Unit Kerja');

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

      foreach ($result_6 as $value) {
        foreach ($alphabet as $key => $v_alphabet) {
          if ($field_name[$key] == 'sertifikat_tanggal' or $field_name[$key] == 'gedung_tanggal' or $field_name[$key] == 'dokumen_tanggal' or $field_name[$key] == 'tanggal_mulai')
            $ex->setCellValue($v_alphabet . $counter, tgl_indo($value[$field_name[$key]]));
          else if ($field_name[$key] == 'nomor_register') {
            $temp_register =  explode(',', $value[$field_name[$key]]);
            $nomor_register = count($temp_register) > 1 ? $temp_register[0] . ' - ' . $temp_register[(count($temp_register) - 1)] : $temp_register[0];
            $ex->setCellValue($v_alphabet . $counter, $nomor_register);
          } else
            $ex->setCellValue($v_alphabet . $counter, $value[$field_name[$key]]);
        }
        $counter = $counter + 1;
      }
    }

    $objPHPExcel->getProperties()->setCreator("LAMPIRAN")
      ->setLastModifiedBy("LAMPIRAN")
      ->setTitle("Lampiran Penghapusan")
      ->setSubject("Lampiran Penghapusan")
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
    header('Content-Disposition: attachment;filename="Lampiran Penghapusan ' . date('Y-m-d') . '.xlsx"');

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
