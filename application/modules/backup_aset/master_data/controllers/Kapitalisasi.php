<?php

if (!defined('BASEPATH'))
exit('No direct script access allowed');

class Kapitalisasi extends CI_Controller
{
  function __construct(){
    parent::__construct();
    $this->global_model->cek_hak_akses('2');
    $this->load->model('kapitalisasi_model');
    $this->load->library('form_validation');
    $this->load->library('datatables');
  }

  public function index()
  {
    $data['css']= array(
      'assets/datatables/dataTables.bootstrap.css',
    );

    $data['js']=array(
      'assets/datatables/jquery.dataTables.js',
      'assets/datatables/dataTables.bootstrap.js'
    );
    $data['content'] = $this->load->view('kapitalisasi/list', '', true);
    $data['breadcrumb'] = array(
      array('label' => 'Administrator', 'url' => '#','icon'=>'fa fa-pie-chart', 'li_class'=>''),
      array('label' => 'Master Kapitalisasi', 'url' => '#','icon'=>'', 'li_class'=>'active'),
      // array('label' => 'Buat', 'url' => '#','icon'=>'', 'li_class'=>'active'),
    );
    $this->load->view('template', $data);
  }

  public function json() {
    header('Content-Type: application/json');
    echo $this->kapitalisasi_model->json();
  }

  public function create()
  {
    $data = array(
    'button' => 'Buat',
    'action' => base_url('master_data/kapitalisasi/create_action'),
    'Kelompok_Manfaat' => set_value('Kelompok_Manfaat'),
    'Bidang_Barang' => set_value('Bidang_Barang'),
    'Batas_A' => set_value('Batas_A'),
    'Batas_B' => set_value('Batas_B'),
    'Batas_C' => set_value('Batas_C'),
    'Batas_D' => set_value('Batas_D'),
    'Batas_E' => set_value('Batas_E'),
    'Batas_F' => set_value('Batas_F'),
    'Tambah_A' => set_value('Tambah_A'),
    'Tambah_B' => set_value('Tambah_B'),
    'Tambah_C' => set_value('Tambah_C'),
    'Tambah_D' => set_value('Tambah_D'),
    'Tambah_E' => set_value('Tambah_E'),
    /*'id_kode_barang' => set_value('id_kode_barang'),
    'kode_barang' => set_value('kode_barang'),
    'nama_barang' => set_value('nama_barang'),
    'kode_akun' => set_value('kode_akun'),
    'kode_kelompok' => set_value('kode_kelompok'),
    'kode_jenis' => set_value('kode_jenis'),
    'kode_objek' => set_value('kode_objek'),
    'kode_rincian_objek' => set_value('kode_rincian_objek'),
    'kode_sub_rincian_objek' => set_value('kode_sub_rincian_objek'),
    'kode_sub_sub_rincian_objek' => set_value('kode_sub_sub_rincian_objek'),
    'umur_ekonomis' => set_value('umur_ekonomis'),
    'nilai_residu' => set_value('nilai_residu'),
    'kelompok_manfaat' => set_value('kelompok_manfaat'),*/
    );
    $data['list_bidang_barang'] = $this->kapitalisasi_model->get_bidang_barang();
    $data['content'] = $this->load->view('kapitalisasi/form', $data, true);
    $data['breadcrumb'] = array(
      array('label' => 'Administrator', 'url' => '#','icon'=>'fa fa-pie-chart', 'li_class'=>''),
      array('label' => 'Master Kapitalisasi', 'url' => '#','icon'=>'', 'li_class'=>''),
      array('label' => 'Buat', 'url' => '#','icon'=>'', 'li_class'=>'active'),
    );
    $this->load->view('template', $data);
  }

  public function create_action(){
    $this->_rules();

    if ($this->form_validation->run() == FALSE) {
      $this->create();
    } else {
      $data = array(
        'Bidang_Barang' => $this->input->post('bidang_barang',TRUE),
        'Batas_A' => $this->input->post('batas_a',TRUE),
        'Batas_B' => $this->input->post('batas_b',TRUE),
        'Batas_C' => $this->input->post('batas_c',TRUE),
        'Batas_D' => $this->input->post('batas_d',TRUE),
        'Batas_E' => $this->input->post('batas_e',TRUE),
        'Batas_F' => $this->input->post('batas_f',TRUE),
        'Tambah_A' => $this->input->post('tambah_a',TRUE),
        'Tambah_B' => $this->input->post('tambah_b',TRUE),
        'Tambah_C' => $this->input->post('tambah_c',TRUE),
        'Tambah_D' => $this->input->post('tambah_d',TRUE),
        'Tambah_E' => $this->input->post('tambah_e',TRUE),
      /*
      'kode_barang' => $this->input->post('kode_barang',TRUE),
      'nama_barang' => $this->input->post('nama_barang',TRUE),
      'kode_akun' => $this->input->post('kode_akun',TRUE),
      'kode_kelompok' => $this->input->post('kode_kelompok',TRUE),
      'kode_jenis' => $this->input->post('kode_jenis',TRUE),
      'kode_objek' => $this->input->post('kode_objek',TRUE),
      'kode_rincian_objek' => $this->input->post('kode_rincian_objek',TRUE),
      'kode_sub_rincian_objek' => $this->input->post('kode_sub_rincian_objek',TRUE),
      'kode_sub_sub_rincian_objek' => $this->input->post('kode_sub_sub_rincian_objek',TRUE),
      'umur_ekonomis' => $this->input->post('umur_ekonomis',TRUE),
      'nilai_residu' => $this->input->post('nilai_residu',TRUE),
      'kelompok_manfaat' => $this->input->post('kelompok_manfaat',TRUE),
      */
      );

      $this->kapitalisasi_model->insert($data);
      $this->global_model->_logs($menu='master_data', $sub_menu='master_kapitalisasi', $tabel_name='tbl_master_kapitalisasi', $action_id=null, $action='insert', $data=$data);
      $this->session->set_flashdata('message', 'Berhasil Menambah Data');
      redirect(base_url('master_data/kapitalisasi'));
    }
  }



  public function update($id){
    $row = $this->kapitalisasi_model->get_by_id($id);

    if ($row) {
      $data = array(
      'button' => 'Perbarui',
      'action' => base_url('master_data/kapitalisasi/update_action'),
      'Kelompok_Manfaat' => set_value('Kelompok_Manfaat',$row->Kelompok_Manfaat),
      'Bidang_Barang' => set_value('Bidang_Barang',$row->Bidang_Barang),
      'Batas_A' => set_value('Batas_A',$row->Batas_A),
      'Batas_B' => set_value('Batas_B',$row->Batas_B),
      'Batas_C' => set_value('Batas_C',$row->Batas_C),
      'Batas_D' => set_value('Batas_D',$row->Batas_D),
      'Batas_E' => set_value('Batas_E',$row->Batas_E),
      'Batas_F' => set_value('Batas_F',$row->Batas_F),
      'Tambah_A' => set_value('Tambah_A',$row->Tambah_A),
      'Tambah_B' => set_value('Tambah_B',$row->Tambah_B),
      'Tambah_C' => set_value('Tambah_C',$row->Tambah_C),
      'Tambah_D' => set_value('Tambah_D',$row->Tambah_D),
      'Tambah_E' => set_value('Tambah_E',$row->Tambah_E),
      /*'id_kode_barang' => set_value('id_kode_barang', $row->id_kode_barang),
      'kode_barang' => set_value('kode_barang', $row->kode_barang),
      'nama_barang' => set_value('nama_barang', $row->nama_barang),
      'kode_akun' => set_value('kode_akun', $row->kode_akun),
      'kode_kelompok' => set_value('kode_kelompok', $row->kode_kelompok),
      'kode_jenis' => set_value('kode_jenis', $row->kode_jenis),
      'kode_objek' => set_value('kode_objek', $row->kode_objek),
      'kode_rincian_objek' => set_value('kode_rincian_objek', $row->kode_rincian_objek),
      'kode_sub_rincian_objek' => set_value('kode_sub_rincian_objek', $row->kode_sub_rincian_objek),
      'kode_sub_sub_rincian_objek' => set_value('kode_sub_sub_rincian_objek', $row->kode_sub_sub_rincian_objek),
      'umur_ekonomis' => set_value('umur_ekonomis', $row->umur_ekonomis),
      'nilai_residu' => set_value('nilai_residu', $row->nilai_residu),
      'kelompok_manfaat' => set_value('kelompok_manfaat', $row->kelompok_manfaat),*/
      );
      $data['list_bidang_barang'] = $this->kapitalisasi_model->get_bidang_barang();
      $data['content'] = $this->load->view('kapitalisasi/form', $data, true);
      $data['breadcrumb'] = array(
        array('label' => 'Administrator', 'url' => '#','icon'=>'fa fa-pie-chart', 'li_class'=>''),
        array('label' => 'Master Kapitalisasi', 'url' => '#','icon'=>'', 'li_class'=>''),
        array('label' => 'Perbarui', 'url' => '#','icon'=>'', 'li_class'=>'active'),
      );
      $this->load->view('template', $data);
    } else {
      $this->session->set_flashdata('message', 'Record Not Found');
      redirect(base_url('master_data/kode_barang'));
    }
  }

  public function update_action(){
    $this->_rules();

    if ($this->form_validation->run() == FALSE) {
      $this->update($this->input->post('id_kode_barang', TRUE));
    } else {
      $data = array(
        'Bidang_Barang' => $this->input->post('bidang_barang',TRUE),
        'Batas_A' => $this->input->post('batas_a',TRUE),
        'Batas_B' => $this->input->post('batas_b',TRUE),
        'Batas_C' => $this->input->post('batas_c',TRUE),
        'Batas_D' => $this->input->post('batas_d',TRUE),
        'Batas_E' => $this->input->post('batas_e',TRUE),
        'Batas_F' => $this->input->post('batas_f',TRUE),
        'Tambah_A' => $this->input->post('tambah_a',TRUE),
        'Tambah_B' => $this->input->post('tambah_b',TRUE),
        'Tambah_C' => $this->input->post('tambah_c',TRUE),
        'Tambah_D' => $this->input->post('tambah_d',TRUE),
        'Tambah_E' => $this->input->post('tambah_e',TRUE),
      );

      $this->kapitalisasi_model->update($this->input->post('Kelompok_Manfaat', TRUE), $data);
      $this->global_model->_logs($menu='master_data', $sub_menu='master_kapitalisasi', $tabel_name='tbl_master_kapitalisasi', $action_id=$this->input->post('Kelompok_Manfaat', TRUE), $action='update', $data=$data);
      $this->session->set_flashdata('message', 'Berhasil Memperbarui');
      redirect(base_url('master_data/kapitalisasi'));
    }
  }

  public function delete($id){
    $row = $this->kapitalisasi_model->get_by_id($id);

    if ($row) {
      $this->kapitalisasi_model->delete($id);
      $this->global_model->_logs($menu='master_data', $sub_menu='master_kapitalisasi', $tabel_name='tbl_master_kapitalisasi', $action_id=$id, $action='delete', $data=$data);
      $this->session->set_flashdata('message', 'Berhasil menghapus');
      redirect(base_url('master_data/kapitalisasi'));
    } else {
      $this->session->set_flashdata('message', 'Record Not Found');
      redirect(base_url('master_data/kapitalisasi'));
    }
  }

  public function _rules(){
    $this->form_validation->set_rules('bidang_barang', 'Bidang Barang', 'trim|required');
    /*$this->form_validation->set_rules('Batas_A', 'Batas A', 'trim|required');
    $this->form_validation->set_rules('Batas_B', 'Batas B', 'trim|required');
    $this->form_validation->set_rules('Batas_C', 'Batas C', 'trim|required');
    $this->form_validation->set_rules('Batas_D', 'Batas D', 'trim|required');
    $this->form_validation->set_rules('Batas_E', 'Batas E', 'trim|required');
    $this->form_validation->set_rules('Batas_F', 'Batas F', 'trim|required');
    $this->form_validation->set_rules('Tambah_A', 'Batas A', 'trim|required');
    $this->form_validation->set_rules('Tambah_B', 'Batas B', 'trim|required');
    $this->form_validation->set_rules('Tambah_C', 'Batas C', 'trim|required');
    $this->form_validation->set_rules('Tambah_D', 'Batas D', 'trim|required');
    $this->form_validation->set_rules('Tambah_E', 'Batas E', 'trim|required');*/



    $this->form_validation->set_rules('kelompok_manfaat', 'Kelompok Manfaat', 'trim');
    $this->form_validation->set_error_delimiters('<span class="text-danger">', '</span>');
  }

}
