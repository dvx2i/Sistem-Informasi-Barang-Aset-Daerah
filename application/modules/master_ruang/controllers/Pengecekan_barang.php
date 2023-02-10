<?php

if (!defined('BASEPATH')) exit('No direct script access allowed');
use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\Writer\Word2007;

class Pengecekan_barang extends MX_Controller
{
  function __construct()
  {
    parent::__construct();
    $this->global_model->cek_hak_akses('25'); //pengecekan barang
    $this->load->model('global_mutasi_model');
    $this->load->model('pengecekan_barang_model');
    $this->load->library('form_validation');
    $this->load->library('datatables');
    $this->load->helper('my_global');
  }

  public function index()
  {
    $data['css'] = array(
      'assets/datatables/dataTables.min.css',
    );

    $data['js'] = array(
      // 'assets/datatables/jquery.dataTables.js',
      // 'assets/datatables/dataTables.min.js'
    );

    $data['content'] = $this->load->view('pengecekan_barang/list', $data, true);
    $data['breadcrumb'] = array(
      array('label' => 'Mutasi', 'url' => '#', 'icon' => 'fa fa-pie-chart', 'li_class' => ''),
      array('label' => 'Penerimaan', 'url' => '#', 'icon' => '', 'li_class' => 'active'),
      // array('label' => 'Buat', 'url' => '#','icon'=>'', 'li_class'=>'active'),
    );

    $this->load->view('template', $data);
  }

  public function detail($id_mutasi)
  {
    
    $id_mutasi = decrypt_url($id_mutasi);
    $data['css'] = array(
      'assets/datatables/dataTables.bootstrap.css',
      "assets/css/progress-tracker.css",
    );

    $data['js'] = array(
      'assets/datatables/jquery.dataTables.js',
      'assets/datatables/dataTables.bootstrap.js'
    );

    $data['id_mutasi'] = $id_mutasi;

    $data['list_status_mutasi'] = $this->global_mutasi_model->get_status_mutasi_all();
    $data['id_status_mutasi'] = $this->global_mutasi_model->get_mutasi($id_mutasi);

    $data['content'] = $this->load->view('pengecekan_barang/detail', $data, true);
    $data['breadcrumb'] = array(
      array('label' => 'Mutasi', 'url' => '#', 'icon' => 'fa fa-pie-chart', 'li_class' => ''),
      array('label' => 'Penerimaan', 'url' => '#', 'icon' => '', 'li_class' => ''),
      array('label' => 'Detail', 'url' => '#', 'icon' => '', 'li_class' => 'active'),
    );
    $this->load->view('template', $data);
  }

  public function json()
  {
    header('Content-Type: application/json');
    echo $this->pengecekan_barang_model->json();
  }

  public function json_detail($id_mutasi)
  {
    header('Content-Type: application/json');
    echo $this->pengecekan_barang_model->json_detail($id_mutasi);
  }

  public function form_pengecekan($id)
  {
    
    $id = decrypt_url($id);
    $row = $this->pengecekan_barang_model->get_by_id($id);

    if ($row) {
      $data = array(
        'button' => 'Ajukan',
        'action' => base_url('mutasi/pengecekan_barang/pengecekan_barang_action/' . $id),
        'id_mutasi' => set_value('id_mutasi', $row->id_mutasi),
        'tanggal' => set_value('tanggal', tgl_indo($row->tanggal)),
        'id_kode_lokasi_lama' => set_value('id_kode_lokasi_lama', $row->id_kode_lokasi_lama),
        'id_kode_lokasi_baru' => set_value('id_kode_lokasi_baru', $row->id_kode_lokasi_baru),
      );
      $data['css'] = array(
        'assets/datatables/dataTables.bootstrap.css',
        "assets/bootstrap/bootstrap-datepicker-master/dist/css/bootstrap-datepicker.min.css",
        "assets/jquery/select2-4.0.6-rc.1/dist/css/select2.css",
        "assets/jquery/select2-bootstrap-theme-master/dist/select2-bootstrap.min.css",
        'assets/adminlte/plugins/iCheck/all.css',
        "assets/css/progress-tracker.css",
      );
      $data['js'] = array(
        'assets/datatables/jquery.dataTables.js',
        'assets/datatables/dataTables.bootstrap.js',
        "assets/bootstrap/bootstrap-datepicker-master/dist/js/bootstrap-datepicker.min.js",
        "assets/bootstrap/bootstrap-datepicker-master/dist/locales/bootstrap-datepicker.id.min.js",
        "assets/jquery/jQuery-Mask-Plugin-master/dist/jquery.mask.min.js",
        "assets/jquery/select2-4.0.6-rc.1/dist/js/select2.min.js",
        'assets/adminlte/plugins/iCheck/icheck.min.js',
        "assets/js/mutasi.js",
      );

      $data['lokasi'] = $this->global_model->get_all_lokasi();
      $data['list_status_mutasi'] = $this->global_mutasi_model->get_status_mutasi_all();
      $data['id_status_mutasi'] = $this->global_mutasi_model->get_mutasi($id);

      $data['jenis_mutasi'] = $this->pengecekan_barang_model->get_jenis_mutasi($id)->jenis; //ambil kode jenis yang ada
      $arr_jenis = explode(',', $data['jenis_mutasi']);

      if (in_array('1', $arr_jenis)) $data['kib_active'][] = '1';
      else $data['kib_hidden']['1'] = 'hidden';
      if (in_array('2', $arr_jenis)) $data['kib_active'][] = '2';
      else $data['kib_hidden']['2'] = 'hidden';
      if (in_array('3', $arr_jenis)) $data['kib_active'][] = '3';
      else $data['kib_hidden']['3'] = 'hidden';
      if (in_array('4', $arr_jenis)) $data['kib_active'][] = '4';
      else $data['kib_hidden']['4'] = 'hidden';
      if (in_array('5', $arr_jenis)) $data['kib_active'][] = '5';
      else $data['kib_hidden']['5'] = 'hidden';
      if (in_array('6', $arr_jenis)) $data['kib_active'][] = '6';
      else $data['kib_hidden']['6'] = 'hidden';
      if (in_array('5.03', $arr_jenis)) $data['kib_active'][] = '5.03';
      else $data['kib_hidden']['5.03'] = 'hidden';
      $data['content'] = $this->load->view('pengecekan_barang/form', $data, true);

      $data['breadcrumb'] = array(
        array('label' => 'Mutasi', 'url' => '#', 'icon' => 'fa fa-pie-chart', 'li_class' => ''),
        array('label' => 'Penerimaan', 'url' => '#', 'icon' => '', 'li_class' => ''),
        array('label' => 'Pengecekan', 'url' => '#', 'icon' => '', 'li_class' => 'active'),
      );
      $this->load->view('template', $data);
    } else {
      $this->session->set_flashdata('message', 'Record Not Found');
      redirect(base_url('mutasi'));
    }
  }

  public function pengecekan_barang_action($id_mutasi = null)
  {
    $arr_barang = array();
    foreach ($this->input->post('pengajuan') as $key => $value) {
      array_push(
        $arr_barang,
        array(
          "id_mutasi_barang" => $key,
          "status_diterima" => $value,
          "tanggal_diterima" => date('Y-m-d'),
        )
      );
      $this->global_model->_logs($menu = 'mutasi', $sub_menu = 'pengecekan_barang', $tabel_name = 'tbl_mutasi_barang', $action_id = $key, $action = 'update', $data = array("id_mutasi_barang" => $key, "status_diterima" => $value, "tanggal_diterima" => date('Y-m-d'),), $feature = 'pengecekan_barang_action');
      //($menu='location', $sub_menu='set_location', $tabel_name='tbl_kode_location', $action_id=$session->id_user, $action='set', $data=$data, $feature);
    }

    $this->pengecekan_barang_model->update_pengajuan($arr_barang, 'id_mutasi_barang');
    $this->pengecekan_barang_model->update_pengajuan_register($id_mutasi, array('status_proses' => '3'));

    $this->session->set_flashdata('message', 'Pengecekan Barang selesai');
    redirect(base_url('mutasi/pengecekan_barang'));
  }

  public function form_pengajuan_register($id)
  {
    
    $id = decrypt_url($id);
    $row = $this->pengecekan_barang_model->get_by_id($id);

    if ($row) {
      $data = array(
        'button' => 'Ajukan',
        'action' => base_url('mutasi/pengecekan_barang/pengajuan_register_action/' . $id),
        'id_mutasi' => set_value('id_mutasi', $row->id_mutasi),
        'tanggal' => set_value('tanggal', tgl_indo($row->tanggal)),
        'id_kode_lokasi_lama' => set_value('id_kode_lokasi_lama', $row->id_kode_lokasi_lama),
        'id_kode_lokasi_baru' => set_value('id_kode_lokasi_baru', $row->id_kode_lokasi_baru),
        'tanggal_bast' => set_value('tanggal_bast', $row->tanggal_bast),
        'nomor_bast' => set_value('nomor_bast', $row->nomor_bast),
      );
      $data['css'] = array(
        "assets/bootstrap/bootstrap-datepicker-master/dist/css/bootstrap-datepicker.min.css",
        "assets/jquery/select2-4.0.6-rc.1/dist/css/select2.css",
        "assets/jquery/select2-bootstrap-theme-master/dist/select2-bootstrap.min.css",
        'assets/jquery/fancybox-master/dist/jquery.fancybox.css',
        "assets/css/progress-tracker.css",
      );
      $data['js'] = array(
        "assets/bootstrap/bootstrap-datepicker-master/dist/js/bootstrap-datepicker.min.js",
        "assets/bootstrap/bootstrap-datepicker-master/dist/locales/bootstrap-datepicker.id.min.js",
        "assets/jquery/select2-4.0.6-rc.1/dist/js/select2.min.js",
        'assets/jquery/fancybox-master/dist/jquery.fancybox.js',
        // 'assets/js/mutasi.js'
      );

      $data['lokasi'] = $this->global_model->get_all_lokasi();
      $data['list_status_mutasi'] = $this->global_mutasi_model->get_status_mutasi_all();
      $data['id_status_mutasi'] = $this->global_mutasi_model->get_mutasi($id);
      $data['picture_bast'] = $this->pengecekan_barang_model->get_picture_bast($id);
      $data['content'] = $this->load->view('pengecekan_barang/register_form', $data, true);
      $data['breadcrumb'] = array(
        array('label' => 'Mutasi', 'url' => '#', 'icon' => 'fa fa-pie-chart', 'li_class' => ''),
        array('label' => 'Penerimaan', 'url' => '#', 'icon' => '', 'li_class' => ''),
        array('label' => 'Pengajuan Register', 'url' => '#', 'icon' => '', 'li_class' => 'active'),
      );
      $this->load->view('template', $data);
    } else {
      $this->session->set_flashdata('message', 'Record Not Found');
      redirect(base_url('mutasi'));
    }
  }

  public function pengajuan_register_action($id_mutasi = null)
  {
    // $this->_rules();

    // if ($this->form_validation->run() == FALSE) {
    //   $this->form_pengajuan_register($id_mutasi);
    // } else {
      
    $row = $this->pengecekan_barang_model->get_by_id($id_mutasi);

    if(!empty($row->tanggal_bast)) {
        $data_update = array(
          // 'tanggal_bast' => date('Y-m-d', tgl_inter($this->input->post('tanggal_bast', TRUE))),
          // 'nomor_bast' => $this->input->post('nomor_bast'),
          'status_validasi' => '1',
          // 'tanggal_validasi' => date('Y-m-d'),
        );
        $this->pengecekan_barang_model->update_pengajuan_register($id_mutasi, $data_update);
        $this->pengecekan_barang_model->update_pengajuan_register($id_mutasi, array('status_proses' => '4'));

        $this->session->set_flashdata('message', 'Pengajuan register berhasil.');
        redirect(base_url('mutasi/pengecekan_barang'));
      }else{
        $id = encrypt_url($id);
        
        $this->session->set_flashdata('message', 'Belum Input Tanggal & Nomor BAST.');
        redirect(base_url('form_pengajuan_register/'.$id));
      }
    // }
  }

  function upload_bast()
  {
    $id_mutasi = $this->input->post('id_mutasi');
    // $folder = 'assets/files/mutasi/picture_bast_'.md5($id_mutasi);
    $folder = "assets/files/mutasi";
    // print_r($_FILES); die;
    $fileExt = pathinfo($_FILES["file"]["name"], PATHINFO_EXTENSION);
// die($fileExt);
    if (!file_exists($folder)) {
      mkdir($folder, 0755);
      $dir = $folder;
    } else $dir = $folder;
    $fieldname = 'file';

    $config['upload_path']    = $dir;
    $config['allowed_types']  = 'jpg|jpeg|png|pdf';
    $config['overwrite']      = TRUE;
    $config['file_ext_tolower'] = TRUE;
    // $config['max_size']     = 2 * 1024 * 1024;
    //add 27 oktober 2018
    // $config['encrypt_name'] = TRUE;
    $config['file_name'] = md5($id_mutasi) . '_' . date('H_i_s');
    // die($config['file_name']);

    $this->load->library('upload');

    $this->upload->initialize($config);

    if ($this->upload->do_upload($fieldname)) {
      $upload = array();
      $upload = $this->upload->data();

      if (strtolower($fileExt) != 'pdf') {
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
      }

      $status = array('status' => TRUE, 'message' => $upload);

      //update url BAST
      $data_insert = array(
        'id_mutasi' => $id_mutasi,
        'url' => $dir . '/' . $upload['file_name'],
      );
      $id_mutasi_picture = $this->pengecekan_barang_model->insert_picture($data_insert);
      $this->global_model->_logs($menu = 'mutasi', $sub_menu = 'pengecekan_barang', $tabel_name = 'tbl_mutasi_picture', $action_id = null, $action = 'insert', $data = $data_insert, $feature = 'upload_bast');
    } else {
      $status = array('status' => FALSE, 'message' => $this->upload->display_errors());
    }

    if ($status['status']) {
      echo json_encode(array(
        'status' => true, 'extension' => $status['message']['file_ext'],
        'path' => $dir . '/' . $status['message']['file_name'],
        'id_mutasi_picture' => $id_mutasi_picture,
        'img_src' => base_url($dir . '/' . $upload['file_name']),
      ));
    } else {
      echo json_encode(array(
        'status' => false,
        'error_message' => $status['message'],
      ));
    }
  }

  public function remove_picture($id_mutasi_picture)
  {
    $this->pengecekan_barang_model->remove_picture($id_mutasi_picture);
    $this->global_model->_logs($menu = 'mutasi', $sub_menu = 'pengecekan_barang', $tabel_name = 'tbl_mutasi_picture', $action_id = $id_mutasi_picture, $action = 'delete', $data = array("id_mutasi_picture" => $id_mutasi_picture,), $feature = 'remove_picture');
    echo json_encode(array('status' => true,));
  }

  public function _rules()
  {
    $this->form_validation->set_rules('tanggal_bast', 'tanggal bast', 'trim');
    $this->form_validation->set_rules('nomor_bast', 'nomor bast', 'trim');

    $this->form_validation->set_error_delimiters('<span class="text-danger">', '</span>');
    $this->form_validation->set_message('required', 'Objek %s harus di isi.');
  }


  public function bast($id)
  {
    
    $id = decrypt_url($id);
    $row = $this->pengecekan_barang_model->get_by_id($id);

    if ($row) {
      $data = array(
        'button' => 'Simpan',
        'action' => base_url('mutasi/pengecekan_barang/bast_action/' . $id),
        'id_mutasi' => set_value('id_mutasi', $row->id_mutasi),
        'tanggal' => set_value('tanggal', tgl_indo($row->tanggal)),
        'id_kode_lokasi_lama' => set_value('id_kode_lokasi_lama', $row->id_kode_lokasi_lama),
        'id_kode_lokasi_baru' => set_value('id_kode_lokasi_baru', $row->id_kode_lokasi_baru),
        'tanggal_bast' => set_value('tanggal_bast', $row->tanggal_bast),
        'nomor_bast' => set_value('nomor_bast', $row->nomor_bast),
      );
      $data['css'] = array(
        "assets/bootstrap/bootstrap-datepicker-master/dist/css/bootstrap-datepicker.min.css",
        "assets/jquery/select2-4.0.6-rc.1/dist/css/select2.css",
        "assets/jquery/select2-bootstrap-theme-master/dist/select2-bootstrap.min.css",
        'assets/jquery/fancybox-master/dist/jquery.fancybox.css',
        "assets/css/progress-tracker.css",
        'assets/sweetalert/sweetalert.css',
      );
      $data['js'] = array(
        "assets/bootstrap/bootstrap-datepicker-master/dist/js/bootstrap-datepicker.min.js",
        "assets/bootstrap/bootstrap-datepicker-master/dist/locales/bootstrap-datepicker.id.min.js",
        "assets/jquery/select2-4.0.6-rc.1/dist/js/select2.min.js",
        'assets/jquery/fancybox-master/dist/jquery.fancybox.js',
        'assets/adminlte/plugins/iCheck/icheck.min.js',
        'assets/sweetalert/sweetalert.min.js',
        // 'assets/js/mutasi.js'
      );

      $data['lokasi'] = $this->global_model->get_all_lokasi();
      $data['list_status_mutasi'] = $this->global_mutasi_model->get_status_mutasi_all();
      $data['id_status_mutasi'] = $this->global_mutasi_model->get_mutasi($id);
      // $data['picture_bast'] = $this->pengecekan_barang_model->get_picture_bast($id);
      $data['content'] = $this->load->view('pengecekan_barang/bast_form', $data, true);
      $data['breadcrumb'] = array(
        array('label' => 'Mutasi', 'url' => '#', 'icon' => 'fa fa-pie-chart', 'li_class' => ''),
        array('label' => 'Penerimaan', 'url' => '#', 'icon' => '', 'li_class' => ''),
        array('label' => 'BAST', 'url' => '#', 'icon' => '', 'li_class' => 'active'),
      );
      $this->load->view('template', $data);
    } else {
      $this->session->set_flashdata('message', 'Record Not Found');
      redirect(base_url('mutasi'));
    }
  }


  public function bast_action($id_mutasi = null)
  {
    $this->_rules_bast();

    if ($this->form_validation->run() == FALSE) {
      $this->bast($id_mutasi);
    } else {
      $data_update = array(
        'tanggal_bast' => date('Y-m-d', tgl_inter($this->input->post('tanggal_bast', TRUE))),
        'nomor_bast' => $this->input->post('nomor_bast'),
        'status_validasi' => '1',
        'tanggal_validasi' => date('Y-m-d'),
      );
      $this->pengecekan_barang_model->update_bast($id_mutasi, $data_update);

      // $this->session->set_flashdata('message', 'Input BAST berhasil.');
      // redirect(base_url('mutasi/pengecekan_barang'));
      // $this->pengecekan_barang_model->update_pengajuan_register($id_mutasi, array('status_proses' => '4'));
      $this->cetak_bast_word($id_mutasi);
    }
  }

  public function _rules_bast()
  {
    $this->form_validation->set_rules('tanggal_bast', 'tanggal bast', 'trim|required');
    $this->form_validation->set_rules('nomor_bast', 'nomor bast', 'trim|required');

    $this->form_validation->set_error_delimiters('<span class="text-danger">', '</span>');
    $this->form_validation->set_message('required', 'Objek %s harus di isi.');
  }


  public function cetak_bast($id_mutasi)
  {
    $this->load->library('PdfGenerator');
    $data["data"] = "";
    // $data['jenis'] = $this->global_model->kode_jenis[$data['kode_jenis']];

    // $data['kib'] = $this->Kib_model->get_all($data);
    // die(json_encode($data['kib']));
    $html = $this->load->view('pengecekan_barang/cetak_bast_pdf', $data, true);
    // $this->pdfgenerator->generate($html, 'KIB', true, 'folio', 'landscape');
    $this->pdfgenerator->generate($html, 'KIB', true, 'folio', 'portrait');
    // $this->load->view('pengecekan_barang/cetak_bast_pdf', $data);
  }
  
  public function cetak_bast_word($id_mutasi)
  {
    $this->load->load->library('word');
    $data["data"] = "";
    $row   = $this->pengecekan_barang_model->get_bast($id_mutasi);
    $barang= $this->pengecekan_barang_model->get_jumlah_barang($id_mutasi);
    // $data['jenis'] = $this->global_model->kode_jenis[$data['kode_jenis']];

    // $data['kib'] = $this->Kib_model->get_all($data);
    // die(json_encode($data));
    $PHPWord = $this->word; // New Word Document
    $section = $PHPWord->createSection(); // New portrait section
    // Add text elements
    $PHPWord->addFontStyle('judul', array('name'=>'Times','bold'=>true, 'size'=>16));
    $PHPWord->addParagraphStyle('judulStyle', array('align'=>'center', 'spaceAfter'=>100));
    $section->addText('BERITA ACARA SERAH TERIMA (BAST) 
                       BARANG MILIK DAERAH MILIK PEMERINTAH KOTA YOGYAKARTA', 'judul', 'judulStyle');
    $section->addTextBreak(1);
    $PHPWord->addFontStyle('rStyle', array('name'=>'Times', 'size'=>12));
    $PHPWord->addParagraphStyle('isiStyle', array('align'=>'both', 'spaceAfter'=>100));
    $section->addText('Nomor : '.$row->nomor_bast.'', 'rStyle', 'judulStyle');
    $section->addTextBreak(1);
    $section->addText('Pada hari ini,  '.hari($row->nomor_bast).'   tanggal  '.tgl_indo($row->tanggal_bast).' bertempat di Yogyakarta   , kami yang bertanda tangan di bawah ini :','rStyle', 'isiStyle');
    $section->addText('     I.    Nama                  : '.$row->pihak_lama,'rStyle', 'isiStyle');
    $section->addText('           NIP                     : '.$row->pihak_nip_lama,'rStyle', 'isiStyle');
    $section->addText('           Jabatan                : Kepala Unit Kerja','rStyle', 'isiStyle');
    $section->addText('           Dalam hal ini bertindak untuk dan atas nama '.$row->instansi_lama.' selaku Kuasa Pengguna Barang pada '.$row->skpd_lama.' untuk selanjutnya disebut sebagai PIHAK PERTAMA.','rStyle', 'isiStyle');
    // $section->addText('           ','rStyle', 'isiStyle');
    // $section->addText('           ','rStyle', 'isiStyle');
    $section->addText('     II.   Nama                  : '.$row->pihak_baru,'rStyle', 'isiStyle');
    $section->addText('           NIP                     : '.$row->pihak_nip_baru,'rStyle', 'isiStyle');
    $section->addText('           Jabatan                : Kepala Unit Kerja','rStyle', 'isiStyle');
    $section->addText('           Dalam hal ini bertindak untuk dan atas nama '.$row->instansi_baru.' selaku Kuasa Pengguna Barang pada '.$row->skpd_baru.' untuk selanjutnya disebut sebagai PIHAK KEDUA.','rStyle', 'isiStyle');
    // $section->addText('            ','rStyle', 'isiStyle');
    // $section->addText('           ','rStyle', 'isiStyle');
    // Save File / Download (Download dialog, prompt user to save or simply open it)
    $section->addText('Telah melakukan serah terima Barang Milik Daerah yang pengelolaannya dilaksanakan dan menjadi tanggung jawab mutlak PIHAK PERTAMA berdasarkan dengan ketentuan sebagaimana disebutkan dalam pasal-pasal di bawah ini: ','rStyle', 'isiStyle');
    $section->addTextBreak(1);
    $section->addText('Pasal 1', 'rStyle', 'judulStyle');
    $section->addText('PIHAK PERTAMA menyerahkan dan PIHAK KEDUA menerima hak atas Barang Milik Daerah
    sejumlah '.$barang->jumlah.' unit, senilai Rp '.my_format_number($barang->harga).'
    ('.Terbilang($barang->harga).'), dengan rincian sebagaimana terlampir  ','rStyle', 'isiStyle');
    $section->addTextBreak(1);
    $section->addText('Pasal 2', 'rStyle', 'judulStyle');
    $section->addText('Dengan ditandatanganinya Berita Acara Serah Terima ini maka tanggung jawab pengelolaan Barang
    Milik Daerah sebagaimana tersebut dalam Pasal 1 beralih dari PIHAK PERTAMA kepada PIHAK
    KEDUA','rStyle', 'isiStyle');
    $section->addTextBreak(1);
    $section->addText('Pasal 3', 'rStyle', 'judulStyle');
    $section->addText('Berita Acara Serah Terima Barang Milik Daerah ini dibuat sebagai bukti yang sah dalam rangkap 2
    (dua) dan mempunyai kekuatan hukum yang sama bagi PIHAK PERTAMA dan PIHAK KEDUA ','rStyle', 'isiStyle');
    $section->addTextBreak(2);
    
    $section->addText('       PIHAK KEDUA                                  PIHAK PERTAMA', 'rStyle', 'judulStyle');
    $section->addTextBreak(2);
    $section->addTextBreak(2);
    $section->addText('  '.$row->pihak_lama.'                                  '.$row->pihak_baru.'', 'rStyle', 'judulStyle');
    $section->addText('  NIP. '.$row->pihak_nip_lama.'                                      NIP. '.$row->pihak_nip_baru.'                      ', 'rStyle', 'judulStyle');
    $section->addTextBreak(2);
    $section->addTextBreak(2);
    $section->addText('Mengetahui', 'rStyle', 'judulStyle');
    $section->addTextBreak(2);
    $section->addTextBreak(2);
    $section->addText('(.........................)', 'rStyle', 'judulStyle');
    $section->addText('NIP.                       ', 'rStyle', 'judulStyle');


    $filename='BAST.docx'; //save our document as this file name
    header('Content-Type: application/vnd.openxmlformats-officedocument.wordprocessingml.document'); //mime type
    header('Content-Disposition: attachment;filename="'.$filename.'"'); //tell browser what's the file name
    header('Cache-Control: max-age=0'); //no cache
    $objWriter = PHPWord_IOFactory::createWriter($PHPWord, 'Word2007');
    $objWriter->save('php://output');
    $html = $this->load->view('pengecekan_barang/cetak_bast_pdf', $data, true);
    // $this->pdfgenerator->generate($html, 'KIB', true, 'folio', 'landscape');
    $this->pdfgenerator->generate($html, 'KIB', true, 'folio', 'portrait');
    // $this->load->view('pengecekan_barang/cetak_bast_pdf', $data);
  }

  public function toExcel($id_mutasi)
  {
    // $temp_register =  explode(',', "000001,000007");
    // $nomor_register = cek_register_kosong($temp_register);
    // echo $nomor_register;

    $data['field_name'] = array(
      'no',  'kode_barang',  'nomor_register',  'nama_barang',  'merk_type',  'sertifikat_nomor',  'bahan', 'asal_usul', 'tanggal_perolehan', 'ukuran_barang', 'satuan', 'keadaan_barang', 'jumlah_barang', 'harga', 'keterangan', 'instansi'
    );
    $data['label'] = array(
      'No',  'Kode Barang',  'Nomor Register',  'Nama Barang / Uraian',  'Merk / Type', 'Sertifikat Nomor', 'Bahan', 'Asal/Cara Perolehan Barang', 'Tahun Perolehan', 'Ukuran Barang / konstruksi (P,S,D)', 'Satuan', 'Keadaan Barang(B,RR,RB)', 'Jumlah Barang', 'Harga', 'Keterangan', 'Unit Kerja'
    );
    // die();
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

    $row = $this->pengecekan_barang_model->get_bast($id_mutasi);

    $counter = 2;
    $objPHPExcel->getActiveSheet()->mergeCells('A' . $counter . ':' . $last_alpabet . $counter);
    $objPHPExcel->getActiveSheet()->getStyle('A' . $counter . ':' . $last_alpabet . $counter)
      ->applyFromArray($header)
      ->getFont()->setSize(9);
    $objPHPExcel->setActiveSheetIndex(0)
      ->setCellValue('A' . $counter, 'LAMPIRAN MUTASI BARANG');
      $objPHPExcel->setActiveSheetIndex(0)
        ->setCellValue('A3', 'NOMOR : '.$row->nomor_bast);
        $objPHPExcel->getActiveSheet()->mergeCells('A3:' . $last_alpabet . '3');
        $objPHPExcel->getActiveSheet()->getStyle('A3:' . $last_alpabet . '3')
          ->applyFromArray($header)
          ->getFont()->setSize(9);
    if (!empty($this->input->post('start_date')) or !empty($this->input->post('last_date'))) {
      $counter = $counter + 1;
      $objPHPExcel->getActiveSheet()->mergeCells('A' . $counter . ':' . $last_alpabet . $counter);
      $objPHPExcel->getActiveSheet()->getStyle('A' . $counter . ':' . $last_alpabet . $counter)
        ->applyFromArray($header2)
        ->getFont()->setSize(9);

     }

    $objPHPExcel->getActiveSheet()->mergeCells('A4:C4');
    $objPHPExcel->getActiveSheet()->mergeCells('A5:C5');
    $objPHPExcel->getActiveSheet()->mergeCells('A6:C6');

    

    $objPHPExcel->setActiveSheetIndex(0)
      ->setCellValue('A4', 'SKPD')
      ->setCellValue('D4', ': '.$row->instansi_lama)

      ->setCellValue('A5', 'KABUPATEN/KOTA')
      ->setCellValue('D5', ': Kota Yogyakarta')

      ->setCellValue('F5', 'NO. KODE LOKASI')
      ->setCellValue('G5', ': '.$row->kode_lokasi)
      
      ->setCellValue('A6', 'PROVINSI')
      ->setCellValue('D6', ': D.I Yogyakarta');
    $counter = 7;
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue($last_alpabet . $counter, '');
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
    if (in_array('instansi', $data['field_name']))
      $objPHPExcel->setActiveSheetIndex(0)->setCellValue('P' . $counter, 'Unit Kerja');

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

    $objPHPExcel->getActiveSheet()->getStyle("A" . $first_row_header . ":" . $last_alpabet . $last_row_header)
      ->applyFromArray($table_header)
      ->getFont()->setSize(12);
      $objPHPExcel->getActiveSheet()->getStyle("A8:P9")
      ->getAlignment()->setWrapText(true);
    //end header

    // ===============================

    $ex = $objPHPExcel->setActiveSheetIndex(0);
    $counter = $counter + 1;

    $inventaris = $this->pengecekan_barang_model->get_lampiran_kib($id_mutasi);

    //  print_r($inventaris); die;
    foreach ($inventaris as $value) {
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
        } else
          $ex->setCellValue($v_alphabet . $counter, $value[$field_name[$key]]);
      }
      $counter = $counter + 1;
    }

    $last_row = $counter - 1;
    $objPHPExcel->getActiveSheet()->getStyle($alphabet[0] . "8:" . $alphabet[$itemcount - 1] . $last_row)
      ->applyFromArray($borderStyleArray);

    
      $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(7);
      $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(20);
      $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(10);
      $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(50);
      $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(30);
      $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(20);
      $objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(20);
      $objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(30);
      $objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(10);
      $objPHPExcel->getActiveSheet()->getColumnDimension('J')->setWidth(15);
      $objPHPExcel->getActiveSheet()->getColumnDimension('K')->setWidth(20);
      $objPHPExcel->getActiveSheet()->getColumnDimension('L')->setWidth(20);
      $objPHPExcel->getActiveSheet()->getColumnDimension('M')->setWidth(20);
      $objPHPExcel->getActiveSheet()->getColumnDimension('N')->setWidth(20);
      $objPHPExcel->getActiveSheet()->getColumnDimension('O')->setWidth(20);
      $objPHPExcel->getActiveSheet()->getColumnDimension('P')->setWidth(50);
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


    
      $objPHPExcel->getActiveSheet()->getStyle($alphabet[0] . $first_row . ":" . $alphabet[$last_col] . $last_row)->applyFromArray($header)->getFont()->setSize(10);

      $objPHPExcel->getActiveSheet()->mergeCells($alphabet[0] . $counter . ':' . $alphabet[$last_col1] . $counter);
      $objPHPExcel->getActiveSheet()->mergeCells($alphabet[$start_col3] . $counter . ':' . $alphabet[$last_col] . $counter);
      // $objPHPExcel->setActiveSheetIndex(0)->setCellValue($alphabet[0] . $counter, 'MENGETAHUI');
      $objPHPExcel->setActiveSheetIndex(0)->setCellValue($alphabet[$start_col3] . $counter, 'Yogyakarta, ' . tgl_indo(date('Y-m-d')));

      $counter = $counter + 1;
      $objPHPExcel->getActiveSheet()->mergeCells($alphabet[0] . $counter . ':' . $alphabet[$last_col1] . $counter);
      $objPHPExcel->getActiveSheet()->mergeCells($alphabet[$start_col2] . $counter . ':' . $alphabet[$last_col2] . $counter);
      $objPHPExcel->getActiveSheet()->mergeCells($alphabet[$start_col3] . $counter . ':' . $alphabet[$last_col] . $counter);

        $objPHPExcel->setActiveSheetIndex(0)->setCellValue($alphabet[0] . $counter, 'PIHAK KEDUA');
    
        // $objPHPExcel->setActiveSheetIndex(0)->setCellValue($alphabet[$start_col2] . $counter, 'PIHAK KEDUA');
      
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue($alphabet[$start_col3] . $counter, 'PIHAK PERTAMA');

      $counter = $counter + 4;
      $objPHPExcel->getActiveSheet()->mergeCells($alphabet[0] . $counter . ':' . $alphabet[$last_col1] . $counter);
      $objPHPExcel->getActiveSheet()->mergeCells($alphabet[$start_col2] . $counter . ':' . $alphabet[$last_col2] . $counter);
      $objPHPExcel->getActiveSheet()->mergeCells($alphabet[$start_col3] . $counter . ':' . $alphabet[$last_col] . $counter);
      
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue($alphabet[0] . $counter, '( ' . $row->pihak_baru . ' )');
      
        // $objPHPExcel->setActiveSheetIndex(0)->setCellValue($alphabet[$start_col2] . $counter, '( ' . $row->pihak_baru . ' )');
      
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue($alphabet[$start_col3] . $counter, '( ' . $row->pihak_lama . ' )');

      $counter = $counter + 1;
      $objPHPExcel->getActiveSheet()->mergeCells($alphabet[0] . $counter . ':' . $alphabet[$last_col1] . $counter);
      $objPHPExcel->getActiveSheet()->mergeCells($alphabet[$start_col2] . $counter . ':' . $alphabet[$last_col2] . $counter);
      $objPHPExcel->getActiveSheet()->mergeCells($alphabet[$start_col3] . $counter . ':' . $alphabet[$last_col] . $counter);
      
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue($alphabet[0] . $counter, 'NIP. ' . $row->pihak_nip_baru);
      
        // $objPHPExcel->setActiveSheetIndex(0)->setCellValue($alphabet[$start_col2] . $counter, 'NIP. ' . $row->pihak_nip_baru);
      
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue($alphabet[$start_col3] . $counter, 'NIP. ' . $row->pihak_nip_lama);
    

    $objPHPExcel->getProperties()->setCreator("Lampiran Mutasi Barang ")
      ->setLastModifiedBy("Lampiran Mutasi Barang ")
      ->setTitle("Export Lampiran Mutasi Barang ")
      ->setSubject("Export Lampiran Mutasi Barang  To Excel")
      ->setDescription("Doc for Office 2007 XLSX, generated by PHPExcel.")
      ->setKeywords("office 2007 openxml php")
      ->setCategory("PHPExcel");
    $objPHPExcel->getActiveSheet()->setTitle('Lampiran Mutasi Barang ');

    $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
    ob_end_clean(); // MENGHILANGKAN ERROR CANNOT OPEN
    header('Last-Modified:' . gmdate("D, d M Y H:i:s") . 'GMT');
    header('Chace-Control: no-store, no-cache, must-revalation');
    header('Chace-Control: post-check=0, pre-check=0', FALSE);
    header('Pragma: no-cache');
    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment;filename="Lampiran Mutasi Barang ' . date('Y-m-d') . '.xlsx"');

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
