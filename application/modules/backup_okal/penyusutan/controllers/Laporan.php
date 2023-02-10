<?php

if (!defined('BASEPATH')) exit('No direct script access allowed');

class Laporan extends MX_Controller{
  function __construct(){
    parent::__construct();

    $this->load->model('laporan_model');
    $this->load->helper('my_global');
  }

  public function get_pengguna(){
    $pengguna = $this->laporan_model->get_pengguna($this->input->post('kode_jenis'));

    $data['option']='<option value="">Silahkan Pilih</option>';
    foreach ($pengguna as $key => $value) {
      $data['option'] .='<option  value="'.$value->id_pengguna.'">'.$value->pengguna.' - '.$value->instansi.'</option>';
    }
    echo json_encode($data);
  }

  public function get_kuasa_pengguna(){
    $kuasa_pengguna = $this->laporan_model->get_kuasa_pengguna($this->input->post('id_pengguna'));

    $data['option']='<option value="">Silahkan Pilih</option>';
    foreach ($kuasa_pengguna as $key => $value) {
      $data['option'] .='<option  value="'.$value->id_kuasa_pengguna.'">'.$value->kuasa_pengguna.' - '.$value->instansi.'</option>';
    }
    echo json_encode($data);
  }

  public function get_sub_kuasa_pengguna(){
    $sub_kuasa_pengguna = $this->laporan_model->get_sub_kuasa_pengguna($this->input->post('id_pengguna'),$this->input->post('id_kuasa_pengguna'));

    $data['option']='<option value="">Silahkan Pilih</option>';
    foreach ($sub_kuasa_pengguna as $key => $value) {
      $data['option'] .='<option  value="'.$value->id_sub_kuasa_pengguna.'">'.$value->sub_kuasa_pengguna.' - '.$value->instansi.'</option>';
    }
    echo json_encode($data);
  }


}
