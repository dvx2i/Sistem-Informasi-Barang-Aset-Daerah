<?php

if (!defined('BASEPATH')) exit('No direct script access allowed');

class Laporan extends CI_Controller
{
  function __construct()
  {
    parent::__construct();

    $this->load->model('laporan_model');
    $this->load->helper('my_global');
    $this->load->library('Global_library');
  }

  public function get_kode_jenis()
  {
    $data['kode_barang'] = $this->input->post('kode_kelompok');
    $kode = $this->global_library->explode_kode_barang($data['kode_barang']);

    $kode_jenis = $this->laporan_model->get_kode_jenis($kode);

    $data['option'] = '<option value="">Silahkan Pilih</option>';
    foreach ($kode_jenis as $key => $value) {
      $data['option'] .= '<option  value="' . $value['kode_barang'] . '">' . $value['kode_barang'] . ' - ' . $value['nama_barang'] . '</option>';
    }
    echo json_encode($data);
    // $kode_jenis = $this->global_model->kode_jenis;
  }


  public function get_pengguna()
  {
    $pengguna = $this->laporan_model->get_pengguna($this->input->post('kode_jenis'));

    $data['option'] = '<option value="">Silahkan Pilih</option>';
    foreach ($pengguna as $key => $value) {
      $data['option'] .= '<option  value="' . $value->id_pengguna . '">' . $value->pengguna . ' - ' . $value->instansi . '</option>';
    }
    echo json_encode($data);
  }

  public function get_kuasa_pengguna()
  {
    $kuasa_pengguna = $this->laporan_model->get_kuasa_pengguna($this->input->post('id_pengguna'));

    $data['option'] = '<option value="">Silahkan Pilih</option>';
    foreach ($kuasa_pengguna as $key => $value) {
      $data['option'] .= '<option  value="' . $value->id_kuasa_pengguna . '">' . $value->kuasa_pengguna . ' - ' . $value->instansi . '</option>';
    }
    echo json_encode($data);
  }

  public function get_sub_kuasa_pengguna()
  {
    $sub_kuasa_pengguna = $this->laporan_model->get_sub_kuasa_pengguna($this->input->post('id_pengguna'), $this->input->post('id_kuasa_pengguna'));

    $data['option'] = '<option value="">Silahkan Pilih</option>';
    foreach ($sub_kuasa_pengguna as $key => $value) {
      $data['option'] .= '<option  value="' . $value->id_sub_kuasa_pengguna . '">' . $value->sub_kuasa_pengguna . ' - ' . $value->instansi . '</option>';
    }
    echo json_encode($data);
  }
}
