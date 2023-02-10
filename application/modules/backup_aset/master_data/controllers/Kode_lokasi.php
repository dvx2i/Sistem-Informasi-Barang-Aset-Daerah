<?php

if (!defined('BASEPATH'))
  exit('No direct script access allowed');

class Kode_lokasi extends CI_Controller
{
  function __construct()
  {
    parent::__construct();
    $this->global_model->cek_hak_akses('1');
    $this->load->model('Kode_lokasi_model');
    $this->load->library('form_validation');
    $this->load->library('datatables');
  }

  public function index()
  {
    $data['css'] = array(
      'assets/datatables/dataTables.bootstrap.css',
    );

    $data['js'] = array(
      'assets/datatables/jquery.dataTables.js',
      'assets/datatables/dataTables.bootstrap.js'
    );
    $data['content'] = $this->load->view('kode_lokasi/tbl_kode_lokasi_list', '', true);
    $data['breadcrumb'] = array(
      array('label' => 'Administrator', 'url' => '#', 'icon' => 'fa fa-pie-chart', 'li_class' => ''),
      array('label' => 'Kode Lokasi', 'url' => '#', 'icon' => '', 'li_class' => 'active'),
      // array('label' => 'Buat', 'url' => '#','icon'=>'', 'li_class'=>'active'),
    );

    $this->load->view('template', $data);
  }

  public function json()
  {
    header('Content-Type: application/json');
    echo $this->Kode_lokasi_model->json();
  }

  public function read($id)
  {
    $row = $this->Kode_lokasi_model->get_by_id($id);
    if ($row) {
      $data = array(
        'id_kode_lokasi' => $row->id_kode_lokasi,
        'intra_kom_ekstra_kom' => $row->intra_kom_ekstra_kom,
        'propinsi' => $row->propinsi,
        'kabupaten' => $row->kabupaten,
        'pengguna' => $row->pengguna,
        'kuasa_pengguna' => $row->kuasa_pengguna,
        'sub_kuasa_pengguna' => $row->sub_kuasa_pengguna,
        'instansi' => $row->instansi,
        'keterangan' => $row->keterangan,
      );
      $this->load->view('kode_lokasi/tbl_kode_lokasi_read', $data);
    } else {
      $this->session->set_flashdata('message', 'Record Not Found');
      redirect(base_url('master_data/kode_lokasi'));
    }
  }

  public function create()
  {
    $data = array(
      'button' => 'Buat',
      'action' => base_url('master_data/kode_lokasi/create_action'),
      'id_kode_lokasi' => set_value('id_kode_lokasi'),
      'intra_kom_ekstra_kom' => set_value('intra_kom_ekstra_kom'),
      'propinsi' => set_value('propinsi'),
      'kabupaten' => set_value('kabupaten'),
      'pengguna' => set_value('pengguna'),
      'pengguna_urusan_unsur' => set_value('pengguna_urusan_unsur'),
      'pengguna_bidang' => set_value('pengguna_bidang'),
      'pengguna_opd' => set_value('pengguna_opd'),
      'kuasa_pengguna' => set_value('kuasa_pengguna'),
      'sub_kuasa_pengguna' => set_value('sub_kuasa_pengguna'),
      'instansi' => set_value('instansi'),
      'keterangan' => set_value('keterangan'),
    );
    $data['content'] = $this->load->view('kode_lokasi/tbl_kode_lokasi_form', $data, true);
    $data['breadcrumb'] = array(
      array('label' => 'Administrator', 'url' => '#', 'icon' => 'fa fa-pie-chart', 'li_class' => ''),
      array('label' => 'Kode Lokasi', 'url' => '#', 'icon' => '', 'li_class' => ''),
      array('label' => 'Buat', 'url' => '#', 'icon' => '', 'li_class' => 'active'),
    );
    $this->load->view('template', $data);
  }

  public function create_action()
  {
    $this->_rules();

    if ($this->form_validation->run() == FALSE) {
      $this->create();
    } else {
      $data = array(
        'intra_kom_ekstra_kom' => $this->input->post('intra_kom_ekstra_kom', TRUE),
        'propinsi' => $this->input->post('propinsi', TRUE),
        'kabupaten' => $this->input->post('kabupaten', TRUE),
        'pengguna_urusan_unsur' => $this->input->post('pengguna_urusan_unsur', true),
        'pengguna_bidang' => $this->input->post('pengguna_bidang', true),
        'pengguna_opd' => $this->input->post('pengguna_opd', true),
        'pengguna' => $this->input->post('pengguna', TRUE),
        'kuasa_pengguna' => $this->input->post('kuasa_pengguna', TRUE),
        'sub_kuasa_pengguna' => $this->input->post('sub_kuasa_pengguna', TRUE),
        'instansi' => $this->input->post('instansi', TRUE),
        'keterangan' => $this->input->post('keterangan', TRUE),
      );

      $this->Kode_lokasi_model->insert($data);
      $this->global_model->_logs($menu = 'master_data', $sub_menu = 'kode_lokasi', $tabel_name = 'tbl_kode_lokasi', $action_id = null, $action = 'insert', $data = $data);
      $this->session->set_flashdata('message', 'Berhasil Menambah Data');
      redirect(base_url('master_data/kode_lokasi'));
    }
  }

  public function update($id)
  {
    $row = $this->Kode_lokasi_model->get_by_id($id);

    if ($row) {
      $data = array(
        'button' => 'Perbarui',
        'action' => base_url('master_data/kode_lokasi/update_action'),
        'id_kode_lokasi' => set_value('id_kode_lokasi', $row->id_kode_lokasi),
        'intra_kom_ekstra_kom' => set_value('intra_kom_ekstra_kom', $row->intra_kom_ekstra_kom),
        'propinsi' => set_value('propinsi', $row->propinsi),
        'kabupaten' => set_value('kabupaten', $row->kabupaten),
        'pengguna_urusan_unsur' => set_value('pengguna_urusan_unsur', $row->pengguna_urusan_unsur),
        'pengguna_bidang' => set_value('pengguna_bidang', $row->pengguna_bidang),
        'pengguna_opd' => set_value('pengguna_opd', $row->pengguna_opd),
        'pengguna' => set_value('pengguna', $row->pengguna),
        'kuasa_pengguna' => set_value('kuasa_pengguna', $row->kuasa_pengguna),
        'sub_kuasa_pengguna' => set_value('sub_kuasa_pengguna', $row->sub_kuasa_pengguna),
        'instansi' => set_value('instansi', $row->instansi),
        'keterangan' => set_value('keterangan', $row->keterangan),
      );
      $data['content'] = $this->load->view('kode_lokasi/tbl_kode_lokasi_form', $data, true);
      $data['breadcrumb'] = array(
        array('label' => 'Administrator', 'url' => '#', 'icon' => 'fa fa-pie-chart', 'li_class' => ''),
        array('label' => 'Kode Lokasi', 'url' => '#', 'icon' => '', 'li_class' => ''),
        array('label' => 'Perbarui', 'url' => '#', 'icon' => '', 'li_class' => 'active'),
      );
      $this->load->view('template', $data);
    } else {
      $this->session->set_flashdata('message', 'Record Not Found');
      redirect(base_url('master_data/kode_lokasi'));
    }
  }

  public function update_action()
  {
    $this->_rules();

    if ($this->form_validation->run() == FALSE) {
      $this->update($this->input->post('id_kode_lokasi', TRUE));
    } else {
      $data = array(
        'intra_kom_ekstra_kom' => $this->input->post('intra_kom_ekstra_kom', TRUE),
        'propinsi' => $this->input->post('propinsi', TRUE),
        'kabupaten' => $this->input->post('kabupaten', TRUE),
        'pengguna_urusan_unsur' => $this->input->post('pengguna_urusan_unsur', true),
        'pengguna_bidang' => $this->input->post('pengguna_bidang', true),
        'pengguna_opd' => $this->input->post('pengguna_opd', true),
        'pengguna' => $this->input->post('pengguna', TRUE),
        'kuasa_pengguna' => $this->input->post('kuasa_pengguna', TRUE),
        'sub_kuasa_pengguna' => $this->input->post('sub_kuasa_pengguna', TRUE),
        'instansi' => $this->input->post('instansi', TRUE),
        'keterangan' => $this->input->post('keterangan', TRUE),
      );

      $this->Kode_lokasi_model->update($this->input->post('id_kode_lokasi', TRUE), $data);
      $this->global_model->_logs($menu = 'master_data', $sub_menu = 'kode_lokasi', $tabel_name = 'tbl_kode_lokasi', $action_id = $this->input->post('id_kode_lokasi', TRUE), $action = 'update', $data = $data);
      $this->session->set_flashdata('message', 'Berhasil Memperbarui');
      redirect(base_url('master_data/kode_lokasi'));
    }
  }

  public function delete($id)
  {
    $row = $this->Kode_lokasi_model->get_by_id($id);

    if ($row) {
      $this->Kode_lokasi_model->delete($id);
      $this->global_model->_logs($menu = 'master_data', $sub_menu = 'kode_lokasi', $tabel_name = 'tbl_kode_lokasi', $action_id = $id, $action = 'delete', $data = $data);
      $this->session->set_flashdata('message', 'Berhasil menghapus');
      redirect(base_url('master_data/kode_lokasi'));
    } else {
      $this->session->set_flashdata('message', 'Record Not Found');
      redirect(base_url('master_data/kode_lokasi'));
    }
  }

  public function _rules()
  {
    $this->form_validation->set_rules('intra_kom_ekstra_kom', 'intra kom ekstra kom', 'trim|required');
    $this->form_validation->set_rules('propinsi', 'propinsi', 'trim|required');
    $this->form_validation->set_rules('kabupaten', 'kabupaten', 'trim|required');
    $this->form_validation->set_rules('pengguna', 'pengguna', 'trim|required');
    $this->form_validation->set_rules('kuasa_pengguna', 'kuasa pengguna', 'trim|required');
    $this->form_validation->set_rules('sub_kuasa_pengguna', 'sub kuasa pengguna', 'trim|required');
    $this->form_validation->set_rules('instansi', 'instansi', 'trim|required');
    $this->form_validation->set_rules('keterangan', 'keterangan', 'trim|required');

    $this->form_validation->set_rules('id_kode_lokasi', 'id_kode_lokasi', 'trim');
    $this->form_validation->set_error_delimiters('<span class="text-danger">', '</span>');
  }
}

/* End of file Kode_lokasi.php */
/* Location: ./application/controllers/Kode_lokasi.php */
/* Please DO NOT modify this information : */
/* Generated by Harviacode Codeigniter CRUD Generator 2018-09-19 06:22:19 */
/* http://harviacode.com */
