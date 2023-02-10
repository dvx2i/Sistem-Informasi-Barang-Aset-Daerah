<?php

if (!defined('BASEPATH')) exit('No direct script access allowed');

class Persetujuan_skpd extends MX_Controller{
  function __construct(){
    parent::__construct();
    $this->global_model->cek_hak_akses('80'); //validasi register
    $this->load->model('persetujuan_skpd_model');
    $this->load->model('global_mutasi_model');
    $this->load->library('form_validation');
    $this->load->library('datatables');
    $this->load->helper('my_global');
  }

  public function index(){
    $data['css']= array(
      'assets/datatables/dataTables.bootstrap.css',
      // 'assets/sweetalert/sweetalert.css',
      'assets/sweetalert/sweetalert2.css'
    );

    $data['js']=array(
      'assets/datatables/jquery.dataTables.js',
      'assets/datatables/dataTables.bootstrap.js',
      // 'assets/sweetalert/sweetalert.min.js',
      'assets/sweetalert/sweetalert2.min.js',
    );

    $data['content']=$this->load->view('persetujuan_skpd/list',$data, true);
    $data['breadcrumb'] = array(
      array('label' => 'Mutasi', 'url' => '#','icon'=>'fa fa-pie-chart', 'li_class'=>''),
      array('label' => 'Validasi Register', 'url' => '#','icon'=>'', 'li_class'=>'active'),
      // array('label' => 'Detail', 'url' => '#','icon'=>'', 'li_class'=>'active'),
    );
    $this->load->view('template', $data);
  }

  public function detail($id_mutasi){
    $data['css']= array(
      'assets/datatables/dataTables.bootstrap.css',
      'assets/sweetalert/sweetalert.css',
      'assets/jquery/fancybox-master/dist/jquery.fancybox.css',
      "assets/css/progress-tracker.css",
    );

    $data['js']=array(
      'assets/datatables/jquery.dataTables.js',
      'assets/datatables/dataTables.bootstrap.js',
      'assets/sweetalert/sweetalert.min.js',
      'assets/jquery/fancybox-master/dist/jquery.fancybox.js',
    );

    $data['id_mutasi']=$id_mutasi;
    $data['mutasi']=$this->global_mutasi_model->get_mutasi($id_mutasi);
    $data['mutasi_picture']=$this->global_mutasi_model->get_mutasi_picture($id_mutasi);
    $data['list_status_mutasi'] = $this->global_mutasi_model->get_status_mutasi_all();
    $data['id_status_mutasi'] = $this->global_mutasi_model->get_mutasi($id_mutasi);

    $data['content']=$this->load->view('persetujuan_skpd/detail',$data, true);
    $data['breadcrumb'] = array(
      array('label' => 'Mutasi', 'url' => '#','icon'=>'fa fa-pie-chart', 'li_class'=>''),
      array('label' => 'Validasi Register', 'url' => '#','icon'=>'', 'li_class'=>''),
      array('label' => 'Detail', 'url' => '#','icon'=>'', 'li_class'=>'active'),
    );
    $this->load->view('template', $data);
  }


  public function json() {
    header('Content-Type: application/json');
    echo $this->persetujuan_skpd_model->json();
  }

  public function json_detail($id_mutasi) {
    header('Content-Type: application/json');
    echo $this->persetujuan_skpd_model->json_detail($id_mutasi);
  }

  /*
  public function mutasi_action($id_mutasi){
    $nomor_bast = $this->input->post('nomor_bast');
    $mutasi = $this->persetujuan_skpd_model->get_by_id($id_mutasi);
    if ($mutasi->nomor_bast != $nomor_bast) {
      echo json_encode(array('status' => FALSE,'message'=>'Nomor BAST Tidak Sama' ));
      die();
    }
    $result = $this->persetujuan_skpd_model->mutasi_action($id_mutasi);
    $this->persetujuan_skpd_model->update_pengajuan_register($id_mutasi,array('status_proses' => '3'));
    echo json_encode(array('status' => TRUE, ));
  }
  */

  public function persetujuan_action($id_mutasi){
    $jenis = $this->input->post('jenis');
    $val = $this->input->post('val');

    // $mutasi = $this->persetujuan_skpd_model->get_by_id($id_mutasi);
    // $result = $this->persetujuan_skpd_model->mutasi_action($id_mutasi);
    if ($jenis == 'persetujuan_lama') {
      $data_update = array(
        'persetujuan_skpd_lama' => $val,
        'status_proses' => $val=='2'?'2':'1'
      );
    }
    elseif ($jenis == 'persetujuan_baru') {
      $data_update = array(
        'persetujuan_skpd_baru' => $val,
        'status_proses' => $val=='2'?'3':'2'
      );
    }


    $this->persetujuan_skpd_model->update_persetujuan_skpd($id_mutasi,$data_update);
    echo json_encode(array('status' => TRUE, ));
  }


}
