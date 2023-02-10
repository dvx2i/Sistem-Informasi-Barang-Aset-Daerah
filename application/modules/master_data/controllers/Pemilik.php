<?php

if (!defined('BASEPATH'))
exit('No direct script access allowed');

class Pemilik extends CI_Controller
{
  function __construct(){
    parent::__construct();
    $this->global_model->cek_hak_akses('5');
    $this->load->model('Pemilik_model');
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
    $data['content'] = $this->load->view('pemilik/tbl_pemilik_list', '', true);
    $data['breadcrumb'] = array(
      array('label' => 'Administrator', 'url' => '#','icon'=>'fa fa-pie-chart', 'li_class'=>''),
      array('label' => 'Kode Pemilik', 'url' => '#','icon'=>'', 'li_class'=>'active'),
      // array('label' => 'Buat', 'url' => '#','icon'=>'', 'li_class'=>'active'),
    );
    $this->load->view('template', $data);
  }

  public function json() {
    header('Content-Type: application/json');
    echo $this->Pemilik_model->json();
  }

  public function read($id)
  {
    $row = $this->Pemilik_model->get_by_id($id);
    if ($row) {
      $data = array(
        'id_pemilik' => $row->id_pemilik,
        'nama' => $row->nama,
        'kode' => $row->kode,
      );
      $this->load->view('pemilik/tbl_pemilik_read', $data);
    } else {
      $this->session->set_flashdata('message', 'Record Not Found');
      redirect(base_url('master_data/pemilik'));
    }
  }

  public function create()
  {
    $data = array(
      'button' => 'Buat',
      'action' => base_url('master_data/pemilik/create_action'),
      'id_pemilik' => set_value('id_pemilik'),
      'nama' => set_value('nama'),
      'kode' => set_value('kode'),
      );
      $data['content'] = $this->load->view('pemilik/tbl_pemilik_form', $data, true);
      $data['breadcrumb'] = array(
        array('label' => 'Administrator', 'url' => '#','icon'=>'fa fa-pie-chart', 'li_class'=>''),
        array('label' => 'Kode Pemilik', 'url' => '#','icon'=>'', 'li_class'=>''),
        array('label' => 'Buat', 'url' => '#','icon'=>'', 'li_class'=>'active'),
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
        'nama' => $this->input->post('nama',TRUE),
        'kode' => $this->input->post('kode',TRUE),
        );

        $this->Pemilik_model->insert($data);
        $this->global_model->_logs($menu='master_data', $sub_menu='kode_pemilik', $tabel_name='tbl_pemilik', $action_id=null, $action='insert', $data=$data);
        $this->session->set_flashdata('message', 'Berhasil Menambah Data');
        redirect(base_url('master_data/pemilik'));
      }
    }

    public function update($id)
    {
      $row = $this->Pemilik_model->get_by_id($id);

      if ($row) {
        $data = array(
        'button' => 'Perbarui',
        'action' => base_url('master_data/pemilik/update_action'),
        'id_pemilik' => set_value('id_pemilik', $row->id_pemilik),
        'nama' => set_value('nama', $row->nama),
        'kode' => set_value('kode', $row->kode),
        );
        $data['content'] = $this->load->view('pemilik/tbl_pemilik_form', $data, true);
        $data['breadcrumb'] = array(
          array('label' => 'Administrator', 'url' => '#','icon'=>'fa fa-pie-chart', 'li_class'=>''),
          array('label' => 'Kode Pemilik', 'url' => '#','icon'=>'', 'li_class'=>''),
          array('label' => 'Perbarui', 'url' => '#','icon'=>'', 'li_class'=>'active'),
        );
        $this->load->view('template', $data);
      } else {
        $this->session->set_flashdata('message', 'Record Not Found');
        redirect(base_url('master_data/pemilik'));
      }
    }

    public function update_action()
    {
      $this->_rules();

      if ($this->form_validation->run() == FALSE) {
        $this->update($this->input->post('id_pemilik', TRUE));
      } else {
        $data = array(
        'nama' => $this->input->post('nama',TRUE),
        'kode' => $this->input->post('kode',TRUE),
        );

        $this->Pemilik_model->update($this->input->post('id_pemilik', TRUE), $data);
        $this->global_model->_logs($menu='master_data', $sub_menu='kode_pemilik', $tabel_name='tbl_pemilik', $action_id=$this->input->post('id_pemilik', TRUE), $action='update', $data=$data);
        $this->session->set_flashdata('message', 'Berhasil Memperbarui');
        redirect(base_url('master_data/pemilik'));
      }
    }

    public function delete($id)
    {
      $row = $this->Pemilik_model->get_by_id($id);

      if ($row) {
        $this->Pemilik_model->delete($id);
        $this->global_model->_logs($menu='master_data', $sub_menu='kode_pemilik', $tabel_name='tbl_pemilik', $action_id=$id, $action='delete', $data=$data);
        $this->session->set_flashdata('message', 'Berhasil menghapus');
        redirect(base_url('master_data/pemilik'));
      } else {
        $this->session->set_flashdata('message', 'Record Not Found');
        redirect(base_url('master_data/pemilik'));
      }
    }

    public function _rules()
    {
      $this->form_validation->set_rules('nama', 'nama', 'trim|required');
      $this->form_validation->set_rules('kode', 'kode', 'trim|required');

      $this->form_validation->set_rules('id_pemilik', 'id_pemilik', 'trim');
      $this->form_validation->set_error_delimiters('<span class="text-danger">', '</span>');
    }

  }

  /* End of file Pemilik.php */
  /* Location: ./application/controllers/Pemilik.php */
  /* Please DO NOT modify this information : */
  /* Generated by Harviacode Codeigniter CRUD Generator 2018-09-19 06:22:58 */
  /* http://harviacode.com */
