<?php

if (!defined('BASEPATH')) exit('No direct script access allowed');

class Global_koreksi extends CI_Controller{
  function __construct(){
    parent::__construct();
    $this->load->model('pengajuan_model');
    $this->load->library('form_validation');
    $this->load->library('datatables');
    $this->load->helper('my_global');
    $this->load->model('User_model');
    $this->load->model('Global_penghapusan_model','penghapusan_model');
  }

  public function json_kib_a($pihak=null) {
    header('Content-Type: application/json');
    echo $this->penghapusan_model->json_kib_a($pihak);
  }

  public function json_kib_b($pihak=null) {
    header('Content-Type: application/json');
    echo $this->penghapusan_model->json_kib_b($pihak);
  }

  public function json_kib_c($pihak=null) {
    header('Content-Type: application/json');
    echo $this->penghapusan_model->json_kib_c($pihak);
  }

  public function json_kib_d($pihak=null) {
    header('Content-Type: application/json');
    echo $this->penghapusan_model->json_kib_d($pihak);
  }

  public function json_kib_e($pihak=null) {
    header('Content-Type: application/json');
    echo $this->penghapusan_model->json_kib_e($pihak);
  }

  public function json_kib_f($pihak=null) {
    header('Content-Type: application/json');
    echo $this->penghapusan_model->json_kib_f($pihak);
  }

}
