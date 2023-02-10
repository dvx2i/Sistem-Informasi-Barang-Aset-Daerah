<?php

if (!defined('BASEPATH'))
exit('No direct script access allowed');

class Master_intra_extra extends CI_Controller{
  function __construct(){
    parent::__construct();
    $this->global_model->cek_hak_akses('5');
    $this->load->model('Master_intra_extra_model');
    $this->load->library('form_validation');
    $this->load->library('datatables');
  }

  public function index(){
    $data['css']= array(
      'assets/datatables/dataTables.bootstrap.css',
    );

    $data['js']=array(
      'assets/datatables/jquery.dataTables.js',
      'assets/datatables/dataTables.bootstrap.js'
    );
    $data['content'] = $this->load->view('master_intra_extra/tbl_master_intra_extra_list', '', true);
    $data['breadcrumb'] = array(
      array('label' => 'Administrator', 'url' => '#','icon'=>'fa fa-pie-chart', 'li_class'=>''),
      array('label' => 'Master Intra/Extra', 'url' => '#','icon'=>'', 'li_class'=>'active'),
      // array('label' => 'Buat', 'url' => '#','icon'=>'', 'li_class'=>'active'),
    );
    $this->load->view('template', $data);
  }

  public function json() {
    header('Content-Type: application/json');
    echo $this->Master_intra_extra_model->json();
  }

  // public function read($id){
  //   $row = $this->Master_intra_extra_model->get_by_id($id);
  //   if ($row) {
  //     $data = array(
  //       'id_master_intra_extra' => $row->id_master_intra_extra,
  //       'kode_jenis' => $row->kode_jenis,
  //       'value' => $row->value,
  //       'created_at' => $row->created_at,
  //       'updated_at' => $row->updated_at,
  //     );
  //     $this->load->view('master_intra_extra/tbl_master_intra_extra_read', $data);
  //   } else {
  //     $this->session->set_flashdata('message', 'Record Not Found');
  //     redirect(base_url('master_intra_extra'));
  //   }
  // }

  // public function create(){
  //   $data = array(
  //     'button' => 'Buat',
  //     'action' => base_url('master_data/master_intra_extra/create_action'),
  //     'id_master_intra_extra' => set_value('id_master_intra_extra'),
  //     'kode_jenis' => set_value('kode_jenis'),
  //     'value' => set_value('value'),
  //     // 'created_at' => set_value('created_at'),
  //     // 'updated_at' => set_value('updated_at'),
  //     );
  //     $this->load->view('master_intra_extra/tbl_master_intra_extra_form', $data);
  //   }

    // public function create_action(){
    //   $this->_rules();
    //
    //   if ($this->form_validation->run() == FALSE) {
    //     $this->create();
    //   } else {
    //     $data = array(
    //     'kode_jenis' => $this->input->post('kode_jenis',TRUE),
    //     'value' => $this->input->post('value',TRUE),
    //     // 'created_at' => $this->input->post('created_at',TRUE),
    //     // 'updated_at' => $this->input->post('updated_at',TRUE),
    //     );
    //
    //     $this->Master_intra_extra_model->insert($data);
    //     $this->global_model->_logs($menu='master_data', $sub_menu='master_intra_extra', $tabel_name='tbl_master_intra_extra', $action_id=null, $action='insert', $data=$data);
    //     $this->session->set_flashdata('message', 'Create Record Success');
    //     redirect(base_url('master_data/master_intra_extra'));
    //   }
    // }

    public function update($id){
      $row = $this->Master_intra_extra_model->get_by_id($id);

      if ($row) {
        $data = array(
        'button' => 'Perbarui',
        'action' => base_url('master_data/master_intra_extra/update_action'),
        'id_master_intra_extra' => set_value('id_master_intra_extra', $row->id_master_intra_extra),
        'kode_jenis' => set_value('kode_jenis', $row->kode_jenis),
        'kib' => set_value('kib', $row->kib),
        'value' => set_value('value', $row->value),
        // 'created_at' => set_value('created_at', $row->created_at),
        // 'updated_at' => set_value('updated_at', $row->updated_at),
        );

        // $data['css']=array();
        $data['js']=array(
        "assets/jquery/jQuery-Mask-Plugin-master/dist/jquery.mask.min.js",
        'assets/js/master_data.js',
        );

        $data['content'] = $this->load->view('master_intra_extra/tbl_master_intra_extra_form', $data, true);
        $data['breadcrumb'] = array(
          array('label' => 'Administrator', 'url' => '#','icon'=>'fa fa-pie-chart', 'li_class'=>''),
          array('label' => 'Master Intra/Extra', 'url' => '#','icon'=>'', 'li_class'=>''),
          array('label' => 'Perbarui', 'url' => '#','icon'=>'', 'li_class'=>'active'),
        );
        $this->load->view('template', $data);
      } else {
        $this->session->set_flashdata('message', 'Record Not Found');
        redirect(base_url('master_data/master_intra_extra'));
      }
    }

    public function update_action(){
      $this->_rules();

      if ($this->form_validation->run() == FALSE) {
        $this->update($this->input->post('id_master_intra_extra', TRUE));
      } else {
        $data = array(
        'kode_jenis' => $this->input->post('kode_jenis',TRUE),
        'value' => str_replace('.', '', $this->input->post('value',TRUE)),//$this->input->post('value',TRUE),
        // 'created_at' => $this->input->post('created_at',TRUE),
        'updated_at' => date('Y-m-d H:i:s'),
        );

        $this->Master_intra_extra_model->update($this->input->post('id_master_intra_extra', TRUE), $data);
        $this->global_model->_logs($menu='master_data', $sub_menu='master_intra_extra', $tabel_name='tbl_master_intra_extra', $action_id=$this->input->post('id_master_intra_extra', TRUE), $action='update', $data=$data);
        $this->session->set_flashdata('message', 'Berhasil Memperbarui');
        redirect(base_url('master_data/master_intra_extra'));
      }
    }

    // public function delete($id){
    //   $row = $this->Master_intra_extra_model->get_by_id($id);
    //
    //   if ($row) {
    //     $this->Master_intra_extra_model->delete($id);
    //     $this->global_model->_logs($menu='master_data', $sub_menu='master_intra_extra', $tabel_name='tbl_master_intra_extra', $action_id=$id, $action='delete', $data=$row);
    //     $this->session->set_flashdata('message', 'Delete Record Success');
    //     redirect(base_url('master_intra_extra'));
    //   } else {
    //     $this->session->set_flashdata('message', 'Record Not Found');
    //     redirect(base_url('master_intra_extra'));
    //   }
    // }

    public function _rules(){
      $this->form_validation->set_rules('kode_jenis', 'kode jenis', 'trim|required');
      $this->form_validation->set_rules('value', 'value', 'trim|required');
      // $this->form_validation->set_rules('created_at', 'created at', 'trim|required');
      // $this->form_validation->set_rules('updated_at', 'updated at', 'trim|required');

      $this->form_validation->set_rules('id_master_intra_extra', 'id_master_intra_extra', 'trim');
      $this->form_validation->set_error_delimiters('<span class="text-danger">', '</span>');
    }

  }

  /* End of file Master_intra_extra.php */
  /* Location: ./application/controllers/Master_intra_extra.php */
  /* Please DO NOT modify this information : */
  /* Generated by Harviacode Codeigniter CRUD Generator 2018-09-26 20:46:54 */
  /* http://harviacode.com */
