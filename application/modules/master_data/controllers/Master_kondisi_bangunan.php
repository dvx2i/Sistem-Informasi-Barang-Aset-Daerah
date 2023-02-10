<?php

if (!defined('BASEPATH'))
exit('No direct script access allowed');

class Master_kondisi_bangunan extends CI_Controller{
  function __construct(){
    parent::__construct();
    $this->global_model->cek_hak_akses('5');
    $this->load->model('Master_kondisi_bangunan_model');
    $this->load->library('form_validation');
    $this->load->library('datatables');
  }

  public function index(){
    $this->load->view('master_kondisi_bangunan/tbl_master_kondisi_bangunan_list');
  }

  public function json() {
    header('Content-Type: application/json');
    echo $this->Master_kondisi_bangunan_model->json();
  }

  public function read($id){
    $row = $this->Master_kondisi_bangunan_model->get_by_id($id);
    if ($row) {
      $data = array(
        'id_master_kondisi_bangunan' => $row->id_master_kondisi_bangunan,
        'description' => $row->description,
        'created_at' => $row->created_at,
        'updated_at' => $row->updated_at,
      );
      $this->load->view('master_kondisi_bangunan/tbl_master_kondisi_bangunan_read', $data);
    } else {
      $this->session->set_flashdata('message', 'Record Not Found');
      redirect(base_url('master_data'));
    }
  }

  public function create(){
    $data = array(
      'button' => 'Buat',
      'action' => base_url('master_data/master_kondisi_bangunan/create_action'),
      'id_master_kondisi_bangunan' => set_value('id_master_kondisi_bangunan'),
      'description' => set_value('description'),
      );
      $data['content'] = $this->load->view('master_kondisi_bangunan/tbl_master_kondisi_bangunan_form', $data, TRUE);
      $data['breadcrumb'] = array(
        array('label' => 'Administrator', 'url' => '#','icon'=>'fa fa-pie-chart', 'li_class'=>''),
        array('label' => 'Master Kondisi Bangunan', 'url' => '#','icon'=>'', 'li_class'=>''),
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

        $this->Master_kondisi_bangunan_model->insert($data);
        $this->global_model->_logs($menu='master_data', $sub_menu='kondisi_bangunan', $tabel_name='tbl_master_kondisi_bangunan', $action_id=null, $action='insert', $data=$data);
        $this->session->set_flashdata('message', 'Create Record Success');
        redirect(base_url('master_data'));
      }
    }

    public function update($id){
      $row = $this->Master_kondisi_bangunan_model->get_by_id($id);

      if ($row) {
        $data = array(
        'button' => 'Perbarui',
        'action' => base_url('master_data/master_kondisi_bangunan/update_action'),
        'id_master_kondisi_bangunan' => set_value('id_master_kondisi_bangunan', $row->id_master_kondisi_bangunan),
        'description' => set_value('description', $row->description),
        );
        $data['content'] = $this->load->view('master_kondisi_bangunan/tbl_master_kondisi_bangunan_form', $data, TRUE);
        $data['breadcrumb'] = array(
          array('label' => 'Administrator', 'url' => '#','icon'=>'fa fa-pie-chart', 'li_class'=>''),
          array('label' => 'Master Kondisi Bangunan', 'url' => '#','icon'=>'', 'li_class'=>''),
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
        $this->update($this->input->post('id_master_kondisi_bangunan', TRUE));
      } else {
        $data = array(
        'description' => $this->input->post('description',TRUE),
        'updated_at' => date('Y-m-d H:i:s'),
        );

        $this->Master_kondisi_bangunan_model->update($this->input->post('id_master_kondisi_bangunan', TRUE), $data);
        $this->global_model->_logs($menu='master_data', $sub_menu='kondisi_bangunan', $tabel_name='tbl_master_kondisi_bangunan', $action_id=$this->input->post('id_master_kondisi_bangunan', TRUE), $action='update', $data=$data);
        $this->session->set_flashdata('message', 'Update Record Success');
        redirect(base_url('master_data'));
      }
    }

    public function delete($id){
      $row = $this->Master_kondisi_bangunan_model->get_by_id($id);
    
      if ($row) {
        $this->Master_kondisi_bangunan_model->delete($id);
        $this->global_model->_logs($menu='master_data', $sub_menu='kondisi_bangunan', $tabel_name='tbl_master_kondisi_bangunan', $action_id=$this->input->post('id_master_kondisi_bangunan', TRUE), $action='delete', $data=$row);
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

      $this->form_validation->set_rules('id_master_kondisi_bangunan', 'id_master_kondisi_bangunan', 'trim');
      $this->form_validation->set_error_delimiters('<span class="text-danger">', '</span>');
    }

  }

  /* End of file Master_kondisi_bangunan.php */
  /* Location: ./application/controllers/Master_kondisi_bangunan.php */
  /* Please DO NOT modify this information : */
  /* Generated by Harviacode Codeigniter CRUD Generator 2018-09-16 06:27:09 */
  /* http://harviacode.com */
