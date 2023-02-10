<?php

if (!defined('BASEPATH')) exit('No direct script access allowed');

class Penghapusan_barang extends MX_Controller
{
  function __construct()
  {
    parent::__construct();
    $this->global_model->cek_hak_akses('32'); //penghapusan_barang
    $this->load->model('penghapusan_barang_model');
    $this->load->library('form_validation');
    $this->load->library('datatables');
    $this->load->helper('my_global');
  }

  public function index()
  {
    $data['css'] = array(
      'assets/datatables/dataTables.bootstrap.css',
      'assets/sweetalert/sweetalert2.css',
      "assets/bootstrap/bootstrap-datepicker-master/dist/css/bootstrap-datepicker.min.css",
    );

    $data['js'] = array(
      'assets/datatables/jquery.dataTables.js',
      'assets/datatables/dataTables.bootstrap.js',
      'assets/sweetalert/sweetalert2.min.js',
      "assets/bootstrap/bootstrap-datepicker-master/dist/js/bootstrap-datepicker.min.js",
      "assets/bootstrap/bootstrap-datepicker-master/dist/locales/bootstrap-datepicker.id.min.js",
    );

    $data['content'] = $this->load->view('penghapusan_barang/list', $data, true);
    $data['breadcrumb'] = array(
      array('label' => 'Penghapusan', 'url' => '#', 'icon' => 'fa fa-pie-chart', 'li_class' => ''),
      array('label' => 'Penghapusan Barang', 'url' => '#', 'icon' => '', 'li_class' => 'active'),
      // array('label' => 'Detail', 'url' => '#','icon'=>'', 'li_class'=>'active'),
    );
    $this->load->view('template', $data);
  }

  public function detail($id_pengahapusan_lampiran)
  {
    $data['css'] = array(
      'assets/datatables/dataTables.bootstrap.css',

    );

    $data['js'] = array(
      'assets/datatables/jquery.dataTables.js',
      'assets/datatables/dataTables.bootstrap.js'
    );

    $data['id_pengahapusan_lampiran'] = $id_pengahapusan_lampiran;
    $data['content'] = $this->load->view('penghapusan_barang/detail', $data, true);
    $data['breadcrumb'] = array(
      array('label' => 'Penghapusan', 'url' => '#', 'icon' => 'fa fa-pie-chart', 'li_class' => ''),
      array('label' => 'Penghapusan Barang', 'url' => '#', 'icon' => '', 'li_class' => ''),
      array('label' => 'Detail', 'url' => '#', 'icon' => '', 'li_class' => 'active'),
    );
    $this->load->view('template', $data);
  }

  public function json()
  {
    header('Content-Type: application/json');
    echo $this->penghapusan_barang_model->json();
  }

  public function json_detail($id_pengahapusan_lampiran)
  {
    header('Content-Type: application/json');
    echo $this->penghapusan_barang_model->json_detail($id_pengahapusan_lampiran);
  }

  public function create()
  {
    $data = array(
      'button' => 'Buat',
      'action' => base_url('penghapusan/penghapusan_barang/create_action'),
      'tanggal' => set_value('tanggal'),
      'nomor_sk' => set_value('nomor_sk'),
    );

    $data['css'] = array(
      'assets/datatables/dataTables.bootstrap.css',
      "assets/bootstrap/bootstrap-datepicker-master/dist/css/bootstrap-datepicker.min.css",
      "assets/jquery/select2-4.0.6-rc.1/dist/css/select2.css",
      "assets/jquery/select2-bootstrap-theme-master/dist/select2-bootstrap.min.css",
      'assets/adminlte/plugins/iCheck/all.css',
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
    );

    $data['list_kib'] = $this->global_model->kode_jenis;

    $data['lokasi'] = $this->global_model->get_all_lokasi();

    $data['content'] = $this->load->view('penghapusan_barang/form', $data, true);
    $this->load->view('template', $data);
  }

  public function _rules()
  {
    $this->form_validation->set_rules('tanggal', 'tanggal', 'trim|required');

    $this->form_validation->set_rules('nomor_sk', 'nomor sk', 'trim|required');
    $this->form_validation->set_error_delimiters('<span class="text-danger">', '</span>');
    $this->form_validation->set_message('required', 'Objek %s harus di isi.');
  }

  public function validasi_action($id_penghapusan_lampiran)
  {
    // print_r($_FILES); die;
    $nomor_sk = $this->input->post('nomor_sk');
    $jenis_sk = $this->input->post('jenis_sk');
    $tanggal_sk = date('Y-m-d', tgl_inter($this->input->post('tanggal_sk', true)));
    $tanggal_validasi = date('Y-m-d', tgl_inter($this->input->post('tanggal_validasi', true)));
    $bulan = date('n', strtotime($tanggal_validasi));
    $tahun = date('Y', strtotime($tanggal_validasi));
    $penghapusan = $this->penghapusan_barang_model->get_penghapusan($id_penghapusan_lampiran);
    $tgl_pengajuan = date('Y-m-d', strtotime($penghapusan->tanggal_pengajuan));

    if($tanggal_validasi >= $tgl_pengajuan){

      $locked = $this->penghapusan_barang_model->get_locked($bulan, $tahun, $penghapusan->id_kode_lokasi);
      if ($locked < 1) {
        $file_sk    = $this->upload_file($id_penghapusan_lampiran);

        $data_update = array(
          'nomor_sk' => $nomor_sk,
          'tanggal_sk' => $tanggal_sk,
          'status_validasi' => '2',
          'jenis_sk' => $jenis_sk,
          'tanggal_validasi' => $tanggal_validasi,
          'file_sk'   => $file_sk['path']
        );
        // print_r($jenis_sk); die;
        $this->penghapusan_barang_model->update_penghapusan_lampiran($id_penghapusan_lampiran, $data_update);
        $this->global_model->_logs($menu = 'penghapusan', $sub_menu = 'penghapusan_barang', $tabel_name = 'tbl_penghapusan_lampiran', $action_id = $id_penghapusan_lampiran, $action = 'update', $data = $data_update, $feature = 'validasi_action');

        $result = $this->penghapusan_barang_model->proc_penghapusan($id_penghapusan_lampiran);

        echo json_encode(array('status' => TRUE,));
      } else {
        echo json_encode(array('status' => FALSE, 'message' => 'Stock Opname Bulan ' . bulan_indo($bulan) . ' Tahun ' . $tahun . ' Telah Dikunci'));
      }
    } else {
      echo json_encode(array('status' => FALSE, 'message' => 'Tanggal Validasi Tidak Valid'));
    }
  }

  function upload_file($id_penghapusan_lampiran)
  {
    // $folder = 'assets/files/mutasi/picture_bast_'.md5($id_mutasi);
    $folder = "assets/files/penghapusan";

    if (!file_exists($folder)) {
      mkdir($folder, 0755);
      $dir = $folder;
    } else $dir = $folder;
    $fieldname = 'file_sk';

    $config['upload_path']    = $dir;
    $config['allowed_types']  = 'jpg|jpeg|png|pdf|doc';
    $config['overwrite']      = TRUE;
    $config['file_ext_tolower'] = TRUE;
    // $config['max_size']     = 2 * 1024 * 1024;
    //add 27 oktober 2018
    // $config['encrypt_name'] = TRUE;
    $config['file_name'] = md5($id_penghapusan_lampiran) . '_' . date('H_i_s');
    // die($config['file_name']);

    $this->load->library('upload');

    $this->upload->initialize($config);

    if ($this->upload->do_upload($fieldname)) {
      $upload = array();
      $upload = $this->upload->data();


      $status = array('status' => TRUE, 'message' => $upload);
    } else {
      $status = array('status' => FALSE, 'message' => $this->upload->display_errors());
    }

    if ($status['status']) {
      $data = array(
        'status' => true, 'extension' => $status['message']['file_ext'],
        'path' => $dir . '/' . $status['message']['file_name'],
        'file_src' => base_url($dir . '/' . $upload['file_name']),
      );

      return $data;
    } else {
      echo json_encode(array(
        'status' => FALSE,
        'error_message' => $status['message'],
      ));
      exit();
    }
  }
  
  public function delete()
  { 
    $id_pengahapusan_lampiran = $this->input->post('id_penghapusan_lampiran');

    $penghapusan = $this->penghapusan_barang_model->get_penghapusan_by_lampiran($id_pengahapusan_lampiran);

    $this->db->trans_start();

    $this->penghapusan_barang_model->delete_lampiran($id_pengahapusan_lampiran);

    foreach($penghapusan as $key) {
      
      $this->penghapusan_barang_model->update_penghapusan_status($key->id_penghapusan, array('status_proses' => '2', ));
    }

    $this->db->trans_complete();

    echo json_encode(array('status' => TRUE,));
  }
}
