<?php

if (!defined('BASEPATH')) exit('No direct script access allowed');

class Validasi_register extends MX_Controller
{
  function __construct()
  {
    parent::__construct();
    $this->global_model->cek_hak_akses('26'); //validasi register
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
      // 'assets/sweetalert/sweetalert.css',
      'assets/sweetalert/sweetalert2.css'
    );

    $data['js'] = array(
      // 'assets/datatables/jquery.dataTables.js',
      // 'assets/datatables/dataTables.bootstrap.js',
      // 'assets/sweetalert/sweetalert.min.js',
      'assets/sweetalert/sweetalert2.min.js',
    );

    $data['content'] = $this->load->view('validasi_register/list', $data, true);
    $data['breadcrumb'] = array(
      array('label' => 'Mutasi', 'url' => '#', 'icon' => 'fa fa-pie-chart', 'li_class' => ''),
      array('label' => 'Validasi Register', 'url' => '#', 'icon' => '', 'li_class' => 'active'),
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
    $data['content'] = $this->load->view('validasi_register/detail', $data, true);
    $data['breadcrumb'] = array(
      array('label' => 'Mutasi', 'url' => '#', 'icon' => 'fa fa-pie-chart', 'li_class' => ''),
      array('label' => 'Validasi Register', 'url' => '#', 'icon' => '', 'li_class' => ''),
      array('label' => 'Detail', 'url' => '#', 'icon' => '', 'li_class' => 'active'),
    );
    $this->load->view('template', $data);
  }


  public function json()
  {
    header('Content-Type: application/json');
    echo $this->validasi_register_model->json();
  }

  public function json_detail($id_mutasi)
  {
    header('Content-Type: application/json');
    echo $this->validasi_register_model->json_detail($id_mutasi);
  }

  public function mutasi_action($id_mutasi)
  {
        $nomor_bast = $this->input->post('nomor_bast');
        $row = $this->validasi_register_model->get_by_id($id_mutasi);

        $tgl_pengajuan = date('Y-m-d', strtotime($row->tanggal));
        $tanggal_validasi = date('Y-m-d', tgl_inter($this->input->post('tanggal_validasi', true)));
        $bulan = date('n', strtotime($tanggal_validasi));
        $tahun = date('Y', strtotime($tanggal_validasi));
        // $tanggal_kapitalisasi = $tgl_pengajuan >= date('Y-m-d')?$tgl_pengajuan:date('Y-m-d');
        $tanggal_kapitalisasi = $tgl_pengajuan;


        if ($tanggal_validasi >= $tgl_pengajuan) {

          $locked = $this->validasi_register_model->get_locked($bulan, $tahun, $row->id_kode_lokasi_baru, $row->id_kode_lokasi_lama);
          if ($locked < 1) {
              /*
              if ($mutasi->nomor_bast != $nomor_bast) {
              echo json_encode(array('status' => FALSE, 'message' => 'Nomor BAST Tidak Sama'));
              die();
              }*/
              // $this->db->trans_start(); tidak pakai transaction karena no register tidak lgsung terupdate jd no regiter akan sama
              $result = $this->validasi_register_model->mutasi_action($id_mutasi,$tanggal_validasi);
              // foreach ($result as $key => $value) {
              //   //set histori barang
              // 	$this->global_model->set_histori_barang('mutasi_out', $value->id_kode_lokasi_lama, $value->kode_jenis, $value->id_kib);
              //   $this->global_model->set_histori_barang('mutasi_in', $value->id_kode_lokasi_baru, $value->kode_jenis, $value->id_kib);
              // }
              // die(json_encode(array('status_proses' => '6')));
              $this->validasi_register_model->update_pengajuan_register($id_mutasi, array('status_validasi' => '2','status_proses' => '5','tanggal_validasi' => $tanggal_validasi));
              // $this->db->trans_complete();
              echo json_encode(array('status' => TRUE,));
          } else {
              echo json_encode(array('status' => FALSE, 'message' => 'Stock Opname Bulan ' . bulan_indo($bulan) . ' Tahun ' . $tahun . ' Telah Dikunci'));
          }
      } else {
          echo json_encode(array('status' => FALSE, 'message' => 'Tanggal Validasi Tidak Valid'));
      }
    }
}
