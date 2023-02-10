<?php

if (!defined('BASEPATH')) exit('No direct script access allowed');

class List_data extends MX_Controller
{
  function __construct()
  {
    parent::__construct();
    $this->global_model->cek_hak_akses('27'); //list data
    $this->load->model('list_data_model');
    $this->load->model('validasi_register_model');
    $this->load->model('global_mutasi_model');
    $this->load->library('form_validation');
    $this->load->library('datatables');
    $this->load->helper('my_global');
  }

  public function index()
  {
    $data['css'] = array(
      'assets/datatables/dataTables.min.css',
      'assets/sweetalert/sweetalert2.css'
    );

    $data['js'] = array(
      // 'assets/datatables/jquery.dataTables.js',
      // 'assets/datatables/dataTables.bootstrap.js',
      'assets/sweetalert/sweetalert2.min.js',
    );

    $data['content'] = $this->load->view('list_data/list', $data, true);
    $data['breadcrumb'] = array(
      array('label' => 'Mutasi', 'url' => '#', 'icon' => 'fa fa-pie-chart', 'li_class' => ''),
      array('label' => 'Daftar Data', 'url' => '#', 'icon' => '', 'li_class' => 'active'),
      // array('label' => 'Detail', 'url' => '#','icon'=>'', 'li_class'=>'active'),
    );
    $this->load->view('template', $data);
  }

  public function detail($id_mutasi)
  {
    $id_mutasi = decrypt_url($id_mutasi);

    $data['css'] = array(
      'assets/datatables/dataTables.bootstrap.css',
      // 'assets/sweetalert/sweetalert.css',
      'assets/sweetalert/sweetalert2.css',
      'assets/jquery/fancybox-master/dist/jquery.fancybox.css',
      "assets/css/progress-tracker.css",
      "assets/bootstrap/bootstrap-datepicker-master/dist/css/bootstrap-datepicker.min.css",
    );

    $data['js'] = array(
      'assets/datatables/jquery.dataTables.js',
      'assets/datatables/dataTables.bootstrap.js',
      // 'assets/sweetalert/sweetalert.min.js',
      'assets/sweetalert/sweetalert2.min.js',
      'assets/jquery/fancybox-master/dist/jquery.fancybox.js',
      "assets/bootstrap/bootstrap-datepicker-master/dist/js/bootstrap-datepicker.min.js",
      "assets/bootstrap/bootstrap-datepicker-master/dist/locales/bootstrap-datepicker.id.min.js",
    );

    $data['id_mutasi'] = $id_mutasi;
    $data['mutasi'] = $this->global_mutasi_model->get_mutasi($id_mutasi);
    $data['mutasi_picture'] = $this->global_mutasi_model->get_mutasi_picture($id_mutasi);
    $data['list_status_mutasi'] = $this->global_mutasi_model->get_status_mutasi_all();
    $data['id_status_mutasi'] = $this->global_mutasi_model->get_mutasi($id_mutasi);
    // die(json_encode($data));
    $data['content'] = $this->load->view('list_data/detail', $data, true);
    $data['breadcrumb'] = array(
      array('label' => 'Mutasi', 'url' => '#', 'icon' => 'fa fa-pie-chart', 'li_class' => ''),
      array('label' => 'List Data', 'url' => '#', 'icon' => '', 'li_class' => ''),
      array('label' => 'Detail', 'url' => '#', 'icon' => '', 'li_class' => 'active'),
    );
    $this->load->view('template', $data);
  
  }


  public function json()
  {
    header('Content-Type: application/json');
    echo $this->list_data_model->json();
  }

  public function json_detail($id_mutasi)
  {
    header('Content-Type: application/json');
    echo $this->validasi_register_model->json_detail($id_mutasi);
  }
  /*
  public function mutasi_action($id_mutasi){
    $nomor_bast = $this->input->post('nomor_bast');
    $mutasi = $this->validasi_register_model->get_by_id($id_mutasi);
    if ($mutasi->nomor_bast != $nomor_bast) {
      echo json_encode(array('status' => FALSE,'message'=>'Nomor BAST Tidak Sama' ));
      die();
    }
    $result = $this->validasi_register_model->mutasi_action($id_mutasi);
    // foreach ($result as $key => $value) {
    //   //set histori barang
		// 	$this->global_model->set_histori_barang('mutasi_out', $value->id_kode_lokasi_lama, $value->kode_jenis, $value->id_kib);
    //   $this->global_model->set_histori_barang('mutasi_in', $value->id_kode_lokasi_baru, $value->kode_jenis, $value->id_kib);
    // }
    $this->validasi_register_model->update_pengajuan_register($id_mutasi,array('status_proses' => '3'));
    echo json_encode(array('status' => TRUE, ));
  }
  */

  public function update($id)
  {
    // die(decrypt_url($id));
    $id = decrypt_url($id);
    $row = $this->list_data_model->get_by_id($id);

    if ($row && $this->session->userdata('session')->id_role == '1')  {
      $data = array(
        'button' => 'Ajukan',
        'action' => base_url('mutasi/list_data/update_action/' . $id),
        'id_mutasi' => set_value('id_mutasi', $row->id_mutasi),
        'tanggal' => set_value('tanggal', tgl_indo($row->tanggal)),
        'tanggal_validasi' => set_value('tanggal_validasi', tgl_indo($row->tanggal_validasi)),
        'id_kode_lokasi_lama' => set_value('id_kode_lokasi_lama', $row->id_kode_lokasi_lama),
        'id_kode_lokasi_baru' => set_value('id_kode_lokasi_baru', $row->id_kode_lokasi_baru),
      );
      $data['css'] = array(
        'assets/datatables/dataTables.bootstrap.css',
        'assets/sweetalert/sweetalert2.css',
        "assets/bootstrap/bootstrap-datepicker-master/dist/css/bootstrap-datepicker.min.css",
        "assets/jquery/select2-4.0.6-rc.1/dist/css/select2.css",
        "assets/jquery/select2-bootstrap-theme-master/dist/select2-bootstrap.min.css",
        'assets/adminlte/plugins/iCheck/all.css',
        "assets/css/progress-tracker.css",
      );
      $data['js'] = array(
        'assets/datatables/jquery.dataTables.js',
        'assets/datatables/dataTables.bootstrap.js',
        'assets/sweetalert/sweetalert2.min.js',
        "assets/bootstrap/bootstrap-datepicker-master/dist/js/bootstrap-datepicker.min.js",
        "assets/bootstrap/bootstrap-datepicker-master/dist/locales/bootstrap-datepicker.id.min.js",
        "assets/jquery/jQuery-Mask-Plugin-master/dist/jquery.mask.min.js",
        "assets/jquery/select2-4.0.6-rc.1/dist/js/select2.min.js",
        'assets/adminlte/plugins/iCheck/icheck.min.js',
        // "assets/js/mutasi.js",
      );

      $data['lokasi'] = $this->global_model->get_all_lokasi();
      $data['list_status_mutasi'] = $this->global_mutasi_model->get_status_mutasi_all();
      $data['id_status_mutasi'] = $this->global_mutasi_model->get_mutasi($id);

      $data['jenis_mutasi'] = $this->list_data_model->get_jenis_mutasi($id)->jenis; //ambil kode jenis yang ada
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
      $data['content'] = $this->load->view('list_data/form', $data, true);

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

  public function update_action($id_mutasi = null)
  {
    if($this->session->userdata('session')->id_role == '1'){
      
      $mutasi = $this->global_mutasi_model->get_mutasi($id_mutasi);
      $tanggal_histori = $mutasi->tanggal_validasi;

      $this->list_data_model->update_pengajuan_register($id_mutasi, array(
        'tanggal_validasi' => date('Y-m-d', tgl_inter($this->input->post('tanggal_validasi', TRUE)))));

        $row = $this->global_mutasi_model->get_mutasi_barang_diterima($id_mutasi);

        $data_histori = array(
          'tanggal_histori' => date('Y-m-d', tgl_inter($this->input->post('tanggal_validasi', TRUE)))
        );

        foreach ($row as $key) {
          $this->global_model->update_histori_barang($key->kode_jenis, $key->id_kib,$tanggal_histori, 'mutasi_in', $data_histori);
          
          $this->global_model->update_histori_barang($key->kode_jenis, $key->id_kib,$tanggal_histori, 'mutasi_out', $data_histori);
        }

      $this->global_model->_logs($menu = 'mutasi', $sub_menu = 'list_data', $tabel_name = 'tbl_mutasi', $action_id = $id_mutasi, $action = 'update', $data = array("tanggal" => tgl_inter($this->input->post('tanggal_validasi', TRUE)),), $feature = 'update_action');
      
      $this->session->set_flashdata('message', 'Berhasil Diupdate');
    }
    redirect(base_url('mutasi/list_data'));
  }
}
