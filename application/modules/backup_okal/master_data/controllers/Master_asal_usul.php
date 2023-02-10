<?php

if (!defined('BASEPATH')) exit('No direct script access allowed');

class Master_asal_usul extends CI_Controller{
  function __construct(){
    parent::__construct();
    $this->global_model->cek_hak_akses('5');
    $this->load->model('Master_asal_usul_model');
    $this->load->library('form_validation');
    $this->load->library('datatables');
  }

  public function index(){
    $this->load->view('master_asal_usul/tbl_master_asal_usul_list');
  }

  public function json() {
    header('Content-Type: application/json');
    echo $this->Master_asal_usul_model->json();
  }

  public function read($id){
    $row = $this->Master_asal_usul_model->get_by_id($id);
    if ($row) {
      $data = array(
        'id_master_asal_usul' => $row->id_master_asal_usul,
        'description' => $row->description,
        'created_at' => $row->created_at,
        'updated_at' => $row->updated_at,
      );
      $this->load->view('master_asal_usul/tbl_master_asal_usul_read', $data);
    } else {
      $this->session->set_flashdata('message', 'Record Not Found');
      redirect(base_url('master_data'));
    }
  }

  public function create(){
    $data = array(
      'button' => 'Buat',
      'action' => base_url('master_data/master_asal_usul/create_action'),
      'id_master_asal_usul' => set_value('id_master_asal_usul'),
      'description' => set_value('description'),
    );
    $data['content'] = $this->load->view('master_asal_usul/tbl_master_asal_usul_form', $data, TRUE);
    $data['breadcrumb'] = array(
      array('label' => 'Administrator', 'url' => '#','icon'=>'fa fa-pie-chart', 'li_class'=>''),
      array('label' => 'Master Asal-usul', 'url' => '#','icon'=>'', 'li_class'=>''),
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
      'description' => $this->input->post('description',TRUE),
      );

      $this->Master_asal_usul_model->insert($data);
      $this->global_model->_logs($menu='master_data', $sub_menu='asal_usul', $tabel_name='tbl_master_asal_usul', $action_id=null, $action='insert', $data=$data);
      $this->session->set_flashdata('message', 'Create Record Success');
      redirect(base_url('master_data'));
    }
  }

  public function update($id){
    $row = $this->Master_asal_usul_model->get_by_id($id);

    if ($row) {
      $data = array(
      'button' => 'Perbarui',
      'action' => base_url('master_data/master_asal_usul/update_action'),
      'id_master_asal_usul' => set_value('id_master_asal_usul', $row->id_master_asal_usul),
      'description' => set_value('description', $row->description),
      );
      $data['content'] = $this->load->view('master_asal_usul/tbl_master_asal_usul_form', $data, TRUE);
      $data['breadcrumb'] = array(
        array('label' => 'Administrator', 'url' => '#','icon'=>'fa fa-pie-chart', 'li_class'=>''),
        array('label' => 'Master Asal-usul', 'url' => '#','icon'=>'', 'li_class'=>''),
        array('label' => 'Perbarui', 'url' => '#','icon'=>'', 'li_class'=>'active'),
      );
      $this->load->view('template', $data);
    } else {
      $this->session->set_flashdata('message', 'Record Not Found');
      redirect(base_url('master_data'));
    }
  }

  public function update_action(){
    $this->_rules();

    if ($this->form_validation->run() == FALSE) {
      $this->update($this->input->post('id_master_asal_usul', TRUE));
    } else {
      $data = array(
      'description' => $this->input->post('description',TRUE),
      'updated_at' => date('Y-m-d H:i:s'),
      );

      $this->Master_asal_usul_model->update($this->input->post('id_master_asal_usul', TRUE), $data);
      $this->global_model->_logs($menu='master_data', $sub_menu='asal_usul', $tabel_name='tbl_master_asal_usul', $action_id=$this->input->post('id_master_asal_usul', TRUE), $action='update', $data=$data);
      $this->session->set_flashdata('message', 'Update Record Success');
      redirect(base_url('master_data'));
    }
  }

  public function delete($id){
    $row = $this->Master_asal_usul_model->get_by_id($id);
  
    if ($row) {
      $this->Master_asal_usul_model->delete($id);
      $this->global_model->_logs($menu='master_data', $sub_menu='asal_usul', $tabel_name='tbl_master_asal_usul', $action_id=$this->input->post('id_master_asal_usul', TRUE), $action='delete', $data=$row);
      $this->session->set_flashdata('message', 'Berhasil Menghapus Data');
      redirect(base_url('master_data'));
    } else {
      $this->session->set_flashdata('message', 'Record Not Found');
      redirect(base_url('master_data'));
    }
  }

  public function _rules(){
    $this->form_validation->set_rules('description', 'description', 'trim|required');
    // $this->form_validation->set_rules('created_at', 'created at', 'trim|required');
    // $this->form_validation->set_rules('updated_at', 'updated at', 'trim|required');

    $this->form_validation->set_rules('id_master_asal_usul', 'id_master_asal_usul', 'trim');
    $this->form_validation->set_error_delimiters('<span class="text-danger">', '</span>');
  }

}

/* End of file Master_asal_usul.php */
/* Location: ./application/controllers/Master_asal_usul.php */
/* Please DO NOT modify this information : */
/* Generated by Harviacode Codeigniter CRUD Generator 2018-09-16 06:26:02 */
/* http://harviacode.com */
