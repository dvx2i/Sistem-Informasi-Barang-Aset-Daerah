<?php

if (!defined('BASEPATH')) exit('No direct script access allowed');

class Validasi_reklas_kode extends MX_Controller
{
  function __construct()
  {
    parent::__construct();
    $this->global_model->cek_hak_akses('101'); //validasi reklas kode
    $this->load->model('validasi_reklas_kode_model', 'this_model');
    $this->load->model('global_reklas_model', 'reklas_model');
    $this->load->library('form_validation');
    $this->load->library('datatables');
    $this->load->helper('my_global');
  }

  public function index()
  {
    $data['css'] = array(
      'assets/datatables/dataTables.bootstrap.css',
      // 'assets/sweetalert/sweetalert.css',
      "assets/bootstrap/bootstrap-datepicker-master/dist/css/bootstrap-datepicker.min.css",
      'assets/sweetalert/sweetalert2.css'
    );

    $data['js'] = array(
      'assets/datatables/jquery.dataTables.js',
      'assets/datatables/dataTables.bootstrap.js',
      // 'assets/sweetalert/sweetalert.min.js',
      'assets/sweetalert/sweetalert2.min.js',
      "assets/bootstrap/bootstrap-datepicker-master/dist/js/bootstrap-datepicker.min.js",
      "assets/bootstrap/bootstrap-datepicker-master/dist/locales/bootstrap-datepicker.id.min.js",
    );

    $data['content'] = $this->load->view('validasi_reklas_kode/list', $data, true);
    $data['breadcrumb'] = array(
      array('label' => 'Reklas', 'url' => '#', 'icon' => 'fa fa-pie-chart', 'li_class' => ''),
      array('label' => 'Validasi Reklas Kode', 'url' => '#', 'icon' => '', 'li_class' => 'active'),
      // array('label' => 'Detail', 'url' => '#','icon'=>'', 'li_class'=>'active'),
    );
    $this->load->view('template', $data);
  }
  /*
  public function detail($id_mutasi)
  {
    $data['css'] = array(
      'assets/datatables/dataTables.bootstrap.css',
      // 'assets/sweetalert/sweetalert.css',
      'assets/sweetalert/sweetalert2.css',
      'assets/jquery/fancybox-master/dist/jquery.fancybox.css',
      "assets/css/progress-tracker.css",
    );

    $data['js'] = array(
      'assets/datatables/jquery.dataTables.js',
      'assets/datatables/dataTables.bootstrap.js',
      // 'assets/sweetalert/sweetalert.min.js',
      'assets/sweetalert/sweetalert2.min.js',
      'assets/jquery/fancybox-master/dist/jquery.fancybox.js',
    );

    $data['id_mutasi'] = $id_mutasi;
    $data['mutasi'] = $this->global_mutasi_model->get_mutasi($id_mutasi);
    $data['mutasi_picture'] = $this->global_mutasi_model->get_mutasi_picture($id_mutasi);
    $data['list_status_mutasi'] = $this->global_mutasi_model->get_status_mutasi_all();
    $data['id_status_mutasi'] = $this->global_mutasi_model->get_mutasi($id_mutasi);
    // die(json_encode($data));
    $data['content'] = $this->load->view('validasi_register/detail', $data, true);
    $data['breadcrumb'] = array(
      array('label' => 'Mutasi', 'url' => '#', 'icon' => 'fa fa-pie-chart', 'li_class' => ''),
      array('label' => 'Validasi Register', 'url' => '#', 'icon' => '', 'li_class' => ''),
      array('label' => 'Detail', 'url' => '#', 'icon' => '', 'li_class' => 'active'),
    );
    $this->load->view('template', $data);
  }
*/

  public function json()
  {
    header('Content-Type: application/json');
    echo $this->this_model->json();
  }
  /*
  public function json_detail($id_mutasi)
  {
    header('Content-Type: application/json');
    echo $this->validasi_register_model->json_detail($id_mutasi);
  }
*/
  public function reklas_action($id_reklas_kode)
  {
    $row = $this->this_model->get_by_id($id_reklas_kode);
    $tgl_pengajuan = date('Y-m-d', strtotime($row->tanggal));
    $tanggal_validasi = date('Y-m-d', tgl_inter($this->input->post('tanggal_validasi', true)));
    $bulan = date('n', strtotime($tanggal_validasi));
    $tahun = date('Y', strtotime($tanggal_validasi));
    // $nomor_bast = $this->input->post('nomor_bast');
    // $mutasi = $this->validasi_register_model->get_by_id($id_mutasi);
    // if ($mutasi->nomor_bast != $nomor_bast) {
    //   echo json_encode(array('status' => FALSE, 'message' => 'Nomor BAST Tidak Sama'));
    //   die();
    // }
    if(empty($row->id_kib_tujuan)){
      echo json_encode(array('status' => FALSE, 'message' => 'KIB Tujuan Tidak Ditemukan.'));
    }
    else{
      if ($tanggal_validasi >= $tgl_pengajuan) {

        $locked = $this->this_model->get_locked($bulan, $tahun, $row->id_kode_lokasi);
        if ($locked < 1) {
          $this->db->trans_start();
          $result = $this->this_model->reklas_action($id_reklas_kode, $tanggal_validasi);
          // $this->validasi_register_model->update_pengajuan_register($id_mutasi, array('status_proses' => '5'));
          $this->db->trans_complete();
          // echo json_encode(array('status' => TRUE, 'message' => 'Gagal Validasi Reklas'));
          echo json_encode(array('status' => TRUE, 'message' => 'Berhasil Validasi Reklas'));
        } else {
          echo json_encode(array('status' => FALSE, 'message' => 'Stock Opname Bulan ' . bulan_indo($bulan) . ' Tahun ' . $tahun . ' Telah Dikunci'));
        }
      } else {
        echo json_encode(array('status' => FALSE, 'message' => 'Tanggal Validasi Tidak Valid'));
      }
    }
  }
}
