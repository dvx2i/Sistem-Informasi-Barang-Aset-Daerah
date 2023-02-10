<?php

if (!defined('BASEPATH')) exit('No direct script access allowed');

class Validasi extends MX_Controller{
  function __construct(){
    parent::__construct();
    $this->global_model->cek_hak_akses('61');//validasi kapitalisasi
    $this->load->model('validasi_model');
    $this->load->model('global_kapitalisasi_model');
    $this->load->library('form_validation');
    $this->load->library('datatables');
    $this->load->helper('my_global');
  }

  public function index(){
    $data['css']= array(
      'assets/datatables/dataTables.bootstrap.css',
      'assets/sweetalert/sweetalert2.css',
    );

    $data['js']=array(
      'assets/datatables/jquery.dataTables.js',
      'assets/datatables/dataTables.bootstrap.js',
      'assets/sweetalert/sweetalert2.min.js',
    );

    $data['content']=$this->load->view('validasi/list',$data, true);
    $data['breadcrumb'] = array(
      array('label' => 'Kapitalisasi', 'url' => '#','icon'=>'fa fa-pie-chart', 'li_class'=>''),
      array('label' => 'Validasi', 'url' => '#','icon'=>'', 'li_class'=>'active'),
      // array('label' => 'Detail', 'url' => '#','icon'=>'', 'li_class'=>'active'),
    );
    $this->load->view('template', $data);
  }


  public function json() {
    header('Content-Type: application/json');
    echo $this->validasi_model->json();
  }

  public function validasi_action($id_kapitalisasi=null){
    $row = $this->validasi_model->get_by_id($id_kapitalisasi);
    $tgl_pengajuan = date('Y-m-d',strtotime($row->tanggal_pengajuan));
    // $tanggal_kapitalisasi = $tgl_pengajuan >= date('Y-m-d')?$tgl_pengajuan:date('Y-m-d');
    $tanggal_kapitalisasi = $tgl_pengajuan;
    $data_update = array(
      'tanggal_kapitalisasi' => $tanggal_kapitalisasi,
      'status' => '2',
      'updated_at' => date('Y-m-d H:i:s'),
    );
    $this->validasi_model->update($id_kapitalisasi,$data_update);
    $this->global_model->_logs($menu='kapitalisasi', $sub_menu='validasi', $tabel_name='tbl_kapitalisasi', $action_id=$id_kapitalisasi, $action='update', $data=$data_update, $feature='validasi_action');
    echo json_encode(array('status' => TRUE, ));
  }

  public function delete($id){
    $row = $this->validasi_model->get_by_id($id);
    if ($row and $row->status == 2) {
      $this->session->set_flashdata('message', 'Data tidak dapat dihapus.');
      redirect(base_url('kapitalisasi/validasi'));
    }
    else if ($row) {
      $this->validasi_model->delete($id);
      $this->global_model->_logs($menu='kapitalisasi', $sub_menu='validasi', $tabel_name='tbl_kapitalisasi', $action_id=$id, $action='delete', $data=$row, $feature='delete');
                              //($menu='location', $sub_menu='set_location', $tabel_name='tbl_kode_location', $action_id=$session->id_user, $action='set', $data=$data, $feature);
      $this->session->set_flashdata('message', 'Berhasil menghapus pengajuan.');
      redirect(base_url('kapitalisasi/validasi'));
    } else {
      $this->session->set_flashdata('message', 'Record Not Found');
      redirect(base_url('kapitalisasi/validasi'));
    }
  }


}
