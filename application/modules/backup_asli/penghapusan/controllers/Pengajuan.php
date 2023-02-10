<?php

if (!defined('BASEPATH')) exit('No direct script access allowed');

class Pengajuan extends CI_Controller
{
  function __construct()
  {
    parent::__construct();
    $this->global_model->cek_hak_akses('29'); //pengajuan penghapusan
    $this->load->model('pengajuan_model');
    $this->load->model('global_penghapusan_model');
    $this->load->library('form_validation');
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

    $data['content'] = $this->load->view('pengajuan/list', $data, true);
    $data['breadcrumb'] = array(
      array('label' => 'Penghapusan', 'url' => '#', 'icon' => 'fa fa-pie-chart', 'li_class' => ''),
      array('label' => 'Pengajuan', 'url' => '#', 'icon' => '', 'li_class' => 'active'),
      // array('label' => 'Detail', 'url' => '#','icon'=>'', 'li_class'=>'active'),
    );
    $this->load->view('template', $data);
  }


  public function detail($id_penghapusan)
  {
    $data['css'] = array(
      'assets/datatables/dataTables.bootstrap.css',
    );

    $data['js'] = array(
      'assets/datatables/jquery.dataTables.js',
      'assets/datatables/dataTables.bootstrap.js'
    );

    $data['id_penghapusan'] = $id_penghapusan;
    $data['content'] = $this->load->view('pengajuan/detail', $data, true);
    $data['breadcrumb'] = array(
      array('label' => 'Penghapusan', 'url' => '#', 'icon' => 'fa fa-pie-chart', 'li_class' => ''),
      array('label' => 'Pengajuan', 'url' => '#', 'icon' => '', 'li_class' => ''),
      array('label' => 'Detail', 'url' => '#', 'icon' => '', 'li_class' => 'active'),
    );
    $this->load->view('template', $data);
  }

  public function json_list()
  {
    header('Content-Type: application/json');
    echo $this->pengajuan_model->json_list();
  }

  public function json_detail($id_penghapusan)
  {
    header('Content-Type: application/json');
    echo $this->pengajuan_model->json_detail($id_penghapusan);
  }


  public function create()
  {
    $data = array(
      'button' => 'Buat',
      'action' => base_url('penghapusan/pengajuan/create_action'),
      'id_penghapusan' => set_value('id_penghapusan'),
      'kib' => set_value('kib'),
      'tanggal' => set_value('tanggal'),
      'barang' => set_value('barang'),
      'nama_paket' => set_value('nama_paket'),
    );

    $data['css'] = array(
      'assets/datatables/dataTables.bootstrap.css',
      "assets/bootstrap/bootstrap-datepicker-master/dist/css/bootstrap-datepicker.min.css",
      "assets/jquery/select2-4.0.6-rc.1/dist/css/select2.css",
      "assets/jquery/select2-bootstrap-theme-master/dist/select2-bootstrap.min.css",
      'assets/adminlte/plugins/iCheck/all.css',
      'assets/sweetalert/sweetalert.css',
    );
    $data['js'] = array(
      'assets/datatables/jquery.dataTables.js',
      'assets/datatables/dataTables.bootstrap.js',
      "assets/bootstrap/bootstrap-datepicker-master/dist/js/bootstrap-datepicker.min.js",
      "assets/bootstrap/bootstrap-datepicker-master/dist/locales/bootstrap-datepicker.id.min.js",
      "assets/jquery/jQuery-Mask-Plugin-master/dist/jquery.mask.min.js",
      "assets/jquery/select2-4.0.6-rc.1/dist/js/select2.min.js",
      "assets/js/penghapusan.js",
      'assets/adminlte/plugins/iCheck/icheck.min.js',
      'assets/sweetalert/sweetalert.min.js',
    );

    $data['list_kib'] = $this->global_model->kode_jenis;

    $data['lokasi'] = $this->global_model->get_all_lokasi();

    $data['content'] = $this->load->view('pengajuan/form', $data, true);
    $data['breadcrumb'] = array(
      array('label' => 'Penghapusan', 'url' => '#', 'icon' => 'fa fa-pie-chart', 'li_class' => ''),
      array('label' => 'Pengajuan', 'url' => '#', 'icon' => '', 'li_class' => ''),
      array('label' => 'Buat', 'url' => '#', 'icon' => '', 'li_class' => 'active'),
    );
    $this->load->view('template', $data);
  }

  public function create_action()
  {
    $this->_rules();

    if ($this->form_validation->run() == FALSE) {
      $this->create();
    } else {
      $this->db->trans_start();
      $aset_lain_lain = $this->global_penghapusan_model->get_kode_aset_lain_lain();
      $id_kode_lokasi = $this->session->userdata('session')->id_kode_lokasi;
      $nama_lokasi = $this->session->userdata('session')->nama_lokasi;
      $arr_barang = array();
      foreach ($this->input->post('pengajuan') as $key => $value) {
        
        $kode_jenis = $key;
        if($key == 0){
          $kode_jenis = '5.03';
        }

        if($this->input->post('jenis') == 'k'){
          $paket = 'KENDARAAN_'.$nama_lokasi;
        }else{
          $paket = $this->global_model->kode_jenis[$kode_jenis]['nama_pendek'].'_'.$nama_lokasi;
        }

        $data = array(
          'tanggal_pengajuan' => date('Y-m-d', tgl_inter($this->input->post('tanggal_pengajuan', TRUE))),
          'id_kode_lokasi' => $id_kode_lokasi,
          'nama_paket' => $paket,
        );
        // print_r($_POST['pengajuan']); die;
        // die(json_encode($data));
        $id = $this->pengajuan_model->insert($data);

        foreach ($value as $value_2) {
          array_push(
            $arr_barang,
            array(
              "id_penghapusan" => $id,
              "kode_jenis" => $kode_jenis,
              "id_kib" => $value_2,
              // "id_kode_barang_baru" => $aset_lain_lain[$key],
              "status_diterima" => '1',
              // "tanggal_diterima" => date('Y-m-d',tgl_inter($this->input->post('tanggal',TRUE))),
            )
          );
        }
      }
      // die(json_encode($arr_barang));
      $this->db->trans_complete();
      $this->pengajuan_model->insert_barang($arr_barang);
      $this->global_model->_logs($menu = 'penghapusan', $sub_menu = 'pengajuan', $tabel_name = 'tbl_penghapusan_barang', $action_id = null, $action = 'insert', $data = $arr_barang, $feature = 'create_action');
      $this->session->set_flashdata('message', 'Berhasil membuat pengajuan.');
      redirect(base_url('penghapusan/pengajuan'));
    }
  }

  
  public function cetak($id_pengahapusan_lampiran)
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
    $field_name = array("no","kode_barang",  "nomor_register", "nama_barang", "merk_type", "nomor_nomor", "tahun_perolehan", "kondisi", "status_diterima", "status_diterima", "keterangan");
    $header_label = array('No','Kode Barang', 'No. Register', 'Nama Barang', 'Merk / tipe', "No.Sertifikat/ No.Pabrik/ No.Chasis/ No.Mesin/ No.Polisi/ No.BPKB", 'Tahun Perolehan', 'Kondisi (B/RR/RB)', 'Ditarik', 'Tidak Ditarik', 'Keterangan');
    $itemcount = count($header_label);
    $alphabet = array();
    for ($na = 0; $na < $itemcount; $na++) {
      $alphabet[] = $this->generateAlphabet($na);
    }
    $last_alpabet = $alphabet[$itemcount - 1];

    //header
    $counter = 1;
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A' . $counter, 'BERITA ACARA PENGECEKAN DAN PENARIKAN BARANG');
    $objPHPExcel->getActiveSheet()->mergeCells('A' . $counter . ':' . $last_alpabet . $counter);
    $counter++;
    
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A' . $counter, 'USULAN PENGHAPUSAN BARANG MILIK DAERAH');
    $objPHPExcel->getActiveSheet()->mergeCells('A' . $counter . ':' . $last_alpabet . $counter);
    $objPHPExcel->getActiveSheet()->getStyle("A1:" . $last_alpabet . $counter)
      ->applyFromArray($header)
      ->getFont()->setSize(12);
    $counter++;
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A' . $counter, 'NOMOR');
    $objPHPExcel->getActiveSheet()->mergeCells('A' . $counter . ':' . $last_alpabet . $counter);
    $counter++;
    $counter++;
    $merge_botom = $counter+1;
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A' . $counter, 'Pada hari ini '.hari_ini().', Tanggal '.tgl_indo(date('Y-m-d')).' telah dilaksanakan pengecekan dan penarikan atas usulan penghapusan barang milik daerah dengan hasil sebagai berikut:');
    $objPHPExcel->getActiveSheet()->mergeCells('A' . $counter . ':' . $last_alpabet . $merge_botom);

    $counter = 8;
    $counter_content = $counter;
    $merge_botom = $counter+1;
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A' . $counter, 'NOMOR');
    $objPHPExcel->getActiveSheet()->mergeCells('A' . $counter . ':C' . $counter);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('D' . $counter, 'SPESIFIKASI BARANG');
    $objPHPExcel->getActiveSheet()->mergeCells('D' . $counter . ':F' . $counter);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('G' . $counter, 'TAHUN PEROLEHAN');
    $objPHPExcel->getActiveSheet()->mergeCells('G' . $counter . ':G' . $merge_botom);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('H' . $counter, 'Kondisi (B/RR/RB)');
    $objPHPExcel->getActiveSheet()->mergeCells('H' . $counter . ':H' . $merge_botom);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('I' . $counter, 'Pengecekan *)');
    $objPHPExcel->getActiveSheet()->mergeCells('I' . $counter . ':J' . $counter);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('K' . $counter, 'Keterangan');
    $objPHPExcel->getActiveSheet()->mergeCells('K' . $counter . ':K' . $merge_botom);
    $counter++;
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A' . $counter, 'No. Urut');
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('B' . $counter, 'Kode Barang');
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('C' . $counter, 'Reg.');
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('D' . $counter, 'Nama/Jenis Barang');
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('E' . $counter, 'Merk/Type');
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('F' . $counter, 'No.Sertifikat/ No.Pabrik/ No.Chasis/ No.Mesin/ No.Polisi/ No.BPKB');
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('I' . $counter, 'Ditarik');
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('J' . $counter, 'Tidak Ditarik');
    $counter++;
    $objPHPExcel->getActiveSheet()->getStyle("A7:K9")
    ->applyFromArray($table_header);
    $objPHPExcel->getActiveSheet()->getStyle("A7:K8")
    ->getAlignment()->setWrapText(true);
    // $objPHPExcel->getActiveSheet()->getRowDimension(1)->setRowHeight(-1);
    $objPHPExcel->getActiveSheet()->getStyle("A5:K5")
    ->getAlignment()->setWrapText(true);
    $objPHPExcel->getActiveSheet()->getStyle("A8:K9")
    ->getAlignment()->setWrapText(true);

    $lampiran = $this->pengajuan_model->get_lampiran($id_pengahapusan_lampiran);
    // die(json_encode($lampiran));
    foreach ($lampiran as $key => $value) {

      $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A' . $counter, $value['no']);
      $objPHPExcel->setActiveSheetIndex(0)->setCellValue('B' . $counter, $value['kode_barang']);
      $objPHPExcel->setActiveSheetIndex(0)->setCellValue('C' . $counter, $value['nomor_register']);
      if(!empty($value['id_inventaris'])){
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('D' . $counter, $value['nama_barang'].' / '.$value['deskripsi']);
      }else{
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('D' . $counter, $value['nama_barang']);
      }
      $objPHPExcel->setActiveSheetIndex(0)->setCellValue('E' . $counter, $value['merk_type']);
      $objPHPExcel->setActiveSheetIndex(0)->setCellValue('F' . $counter, $value['nomor_nomor']);
      if($value['tahun_perolehan'] != '0'){
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('G' . $counter, $value['tahun_perolehan']);
      }else{
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('G' . $counter, "");
      }
      $objPHPExcel->setActiveSheetIndex(0)->setCellValue('H' . $counter, "");
      $objPHPExcel->setActiveSheetIndex(0)->setCellValue('I' . $counter, $value['status_diterima']);
      $objPHPExcel->setActiveSheetIndex(0)->setCellValue('J' . $counter, $value['status_diterima']);
      if(!empty($value['id_inventaris'])){
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('K' . $counter, $value['keterangan'].' / '.$value['id_inventaris']);
      }else{
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('K' . $counter, $value['keterangan']);
      }

      $counter++;
    }
    $counter--;
    $objPHPExcel->getActiveSheet()->getStyle("A" . $counter_content . ":" . $last_alpabet . $counter)
      ->applyFromArray($borderStyleArray);


    // foreach ($field_name as $key => $value) :
    //   if ($value == "harga_barang" or $value == "akumulasi_penyusutan" or $value == "nilai_buku") {
    //     $cell = $alphabet[$key] . $counter_content . ":" . $alphabet[$key] . $counter;
    //     $objPHPExcel->getActiveSheet()->getStyle($cell)->getNumberFormat()->setFormatCode('#,##0');
    //   }
    // endforeach;

    $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(7);
    $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(20);
    $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(10);
    $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(50);
    $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(30);
    $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(50);
    $objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(20);
    $objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(20);
    $objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(10);
    $objPHPExcel->getActiveSheet()->getColumnDimension('J')->setWidth(10);
    $objPHPExcel->getActiveSheet()->getColumnDimension('K')->setWidth(30);
    //autosize
    // foreach ($alphabet as $columnID) {
    //   $objPHPExcel->getActiveSheet()->getColumnDimension($columnID)
    //     ->setAutoSize(true);
    // }
    $objPHPExcel->getProperties()->setCreator("BERITA ACARA PENARIKAN")
      ->setLastModifiedBy("BERITA ACARA PENARIKAN")
      ->setTitle("BERITA ACARA PENARIKAN")
      ->setSubject("BERITA ACARA PENARIKAN")
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
    header('Content-Disposition: attachment;filename="BERITA ACARA PENARIKAN ' . date('Y-m-d') . '.xlsx"');

    $objWriter->save('php://output');
    die();
  }

  public function delete($id)
  {
    $row = $this->pengajuan_model->get_by_id($id);

    if ($row) {
      $this->pengajuan_model->delete_penghapusan_barang($id);
      $this->pengajuan_model->delete($id);
      $this->global_model->_logs($menu = 'penghapusan', $sub_menu = 'pengajuan', $tabel_name = 'tbl_penghapusan', $action_id = $id, $action = 'delete', $data = $row, $feature = 'delete');
      $this->global_model->_logs($menu = 'penghapusan', $sub_menu = 'pengajuan', $tabel_name = 'tbl_penghapusan_barang', $action_id = $id, $action = 'delete', $data = $row, $feature = 'delete');
      //($menu='location', $sub_menu='set_location', $tabel_name='tbl_kode_location', $action_id=$session->id_user, $action='set', $data=$data, $feature);
      $this->session->set_flashdata('message', 'Berhasil menghapus pengajuan.');
      redirect(base_url('penghapusan/pengajuan'));
    } else {
      $this->session->set_flashdata('message', 'Record Not Found');
      redirect(base_url('penghapusan/pengajuan'));
    }
  }

  public function upload($id_penghapusan)
  {
    $row = $this->pengajuan_model->get_by_id($id_penghapusan);

    if ($row) {
      $data = array(
        'button' => 'Simpan',
        'action' => base_url('penghapusan/pengajuan'),
        'id_penghapusan' => set_value('id_penghapusan', $row->id_penghapusan),
        'tanggal_pengajuan' => set_value('tanggal_pengajuan', tgl_indo($row->tanggal_pengajuan)),
      );
      $data['css'] = array(
        'assets/jquery/fancybox-master/dist/jquery.fancybox.css',
      );
      $data['js'] = array(
        'assets/jquery/fancybox-master/dist/jquery.fancybox.js',
        // 'assets/js/mutasi.js'
      );

      $data['picture'] = $this->global_penghapusan_model->get_picture($id_penghapusan);

      $data['content'] = $this->load->view('pengajuan/form_upload', $data, true);
      $data['breadcrumb'] = array(
        array('label' => 'Penghapusan', 'url' => '#', 'icon' => 'fa fa-pie-chart', 'li_class' => ''),
        array('label' => 'Pengajuan', 'url' => '#', 'icon' => '', 'li_class' => ''),
        array('label' => 'Unggah', 'url' => '#', 'icon' => '', 'li_class' => 'active'),
      );
      $this->load->view('template', $data);
    } else {
      $this->session->set_flashdata('message', 'Data tidak titemukan');
      redirect(base_url('penghapusan/pengajuan'));
    }
  }


  function upload_foto()
  {
    $id_penghapusan = $this->input->post('id_penghapusan');
    // $folder = 'assets/files/mutasi/picture_bast_'.md5($id_mutasi);
    $folder = "assets/files/penghapusan";

    if (!file_exists($folder)) {
      mkdir($folder, 0755);
      $dir = $folder;
    } else $dir = $folder;
    $fieldname = 'file';

    $config['upload_path']    = $dir;
    $config['allowed_types']  = 'jpg|jpeg|png';
    $config['overwrite']      = TRUE;
    $config['file_ext_tolower'] = TRUE;
    $config['max_size']     = 2 * 1024 * 1024;
    //add 27 oktober 2018
    // $config['encrypt_name'] = TRUE;
    $config['file_name'] = md5($id_penghapusan) . '_' . date('H_i_s');
    // die($config['file_name']);

    $this->load->library('upload');

    $this->upload->initialize($config);

    if ($this->upload->do_upload($fieldname)) {
      $upload = array();
      $upload = $this->upload->data();


      //set ratio
      $ratio = $upload['image_height'] / $upload['image_width'];
      $this->load->library('image_lib');
      $resize['image_library'] = 'gd2';
      $resize['source_image'] = $dir . '/' . $upload['file_name'];
      $resize['maintain_ratio'] = TRUE;
      $resize['height'] = $ratio * 600;
      $resize['width'] = 600;

      $this->image_lib->initialize($resize);
      $this->image_lib->resize();

      $status = array('status' => TRUE, 'message' => $upload);

      //update url BAST
      $data_insert = array(
        'id_penghapusan' => $id_penghapusan,
        'url' => $dir . '/' . $upload['file_name'],
      );
      $id_penghapusan_picture = $this->pengajuan_model->insert_picture($data_insert);
      $this->global_model->_logs($menu = 'penghapusan', $sub_menu = 'pengajuan', $tabel_name = 'tbl_penghapusan_picture', $action_id = null, $action = 'insert', $data = $data_insert, $feature = 'upload_foto');
    } else {
      $status = array('status' => FALSE, 'message' => $this->upload->display_errors());
    }

    if ($status['status']) {
      echo json_encode(array(
        'status' => true, 'extension' => $status['message']['file_ext'],
        'path' => $dir . '/' . $status['message']['file_name'],
        'id_penghapusan_picture' => $id_penghapusan_picture,
        'img_src' => base_url($dir . '/' . $upload['file_name']),
      ));
    } else {
      echo json_encode(array(
        'status' => false,
        'error_message' => $status['message'],
      ));
    }
  }


  public function remove_picture($id_penghapusan_picture)
  {
    $this->pengajuan_model->remove_picture($id_penghapusan_picture);
    $this->global_model->_logs($menu = 'penghapusan', $sub_menu = 'pengajuan', $tabel_name = 'tbl_penghapusan_picture', $action_id = $id_penghapusan_picture, $action = 'delete', $data = array("id_penghapusan_picture" => $id_penghapusan_picture,), $feature = 'remove_picture');
    echo json_encode(array('status' => true,));
  }

  public function _rules()
  {
    $this->form_validation->set_rules('tanggal_pengajuan', 'tanggal pengajuan', 'trim|required');

    $this->form_validation->set_rules('id_penghapusan', 'id penghapusan', 'trim');
    $this->form_validation->set_error_delimiters('<span class="text-danger">', '</span>');
    $this->form_validation->set_message('required', 'Objek %s harus di isi.');
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
