<?php

if (!defined('BASEPATH')) exit('No direct script access allowed');

class Master_rekening extends CI_Controller{
  function __construct(){
    parent::__construct();
    $this->global_model->cek_hak_akses('5');
    $this->load->model('Master_rekening_model');
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
    $data['content'] = $this->load->view('master_rekening/tbl_master_rekening_list', '', true);
    $data['breadcrumb'] = array(
      array('label' => 'Administrator', 'url' => '#','icon'=>'fa fa-pie-chart', 'li_class'=>''),
      array('label' => 'Kode Rekening', 'url' => '#','icon'=>'', 'li_class'=>'active'),
      // array('label' => 'Buat', 'url' => '#','icon'=>'', 'li_class'=>'active'),
    );
    $this->load->view('template', $data);
    //$this->load->view('master_rekening/tbl_master_rekening_list');
  }

  public function json() {
    header('Content-Type: application/json');
    echo $this->Master_rekening_model->json();
  }

  public function read($id){
    $row = $this->Master_rekening_model->get_by_id($id);
    if ($row) {
      $data = array(
        'id_rekening' => $row->id_rekening,
		 'id_sumber_dana' => $row->id_sumber_dana,
		 'kode_rekening' => $row->kode_rekening,
        'nama_rekening' => $row->nama_rekening,
        //'created_at' => $row->created_at,
       // 'updated_at' => $row->updated_at,
      );
      $this->load->view('master_rekening/tbl_master_rekening_read', $data);
    } else {
      $this->session->set_flashdata('message', 'Record Not Found');
      redirect(base_url('master_data/master_rekening'));
    }
  }

  public function create(){
	  
    $data['css'] = array(
      // "assets/bootstrap/bootstrap-datepicker-master/dist/css/bootstrap-datepicker.min.css",
      "assets/jquery/select2-4.0.6-rc.1/dist/css/select2.min.css",
      "assets/jquery/select2-bootstrap-theme-master/dist/select2-bootstrap.min.css",
      // 'assets/adminlte/plugins/iCheck/all.css',
      "assets/css/kib.css",
    );
    $data['js'] = array(
      // "assets/bootstrap/bootstrap-datepicker-master/dist/js/bootstrap-datepicker.min.js",
      // "assets/bootstrap/bootstrap-datepicker-master/dist/locales/bootstrap-datepicker.id.min.js",
      // "assets/jquery/jQuery-Mask-Plugin-master/dist/jquery.mask.min.js",
      "assets/jquery/select2-4.0.6-rc.1/dist/js/select2.min.js",
      // 'assets/adminlte/plugins/iCheck/icheck.min.js',
      // "assets/js/kib.js",
    );
    $data = array(
      'button' => 'Buat',
      'action' => base_url('master_data/master_rekening/create_action'),
      'id_rekening' => set_value('id_rekening'),
	   'id_sumber_dana' => set_value('id_sumber_dana'),
      'kode_rekening' => set_value('kode_rekening'),
      'nama_rekening' =>set_value('nama_rekening'),
    );
	$data['list_seumber_dana'] = $this->Master_rekening_model->get_sumber_dana();
    $data['content'] = $this->load->view('master_rekening/tbl_master_rekening_form', $data, TRUE);
	//print_r($data['list_seumber_dana']);die;
    $data['breadcrumb'] = array(
      array('label' => 'Administrator', 'url' => '#','icon'=>'fa fa-pie-chart', 'li_class'=>''),
      array('label' => 'Master Rekening', 'url' => '#','icon'=>'', 'li_class'=>''),
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
      
	  'id_rekening' => $this->input->post('id_rekening',TRUE),
	   'id_sumber_dana' => $this->input->post('id_sumber_dana',TRUE),
      'kode_rekening' => $this->input->post('kode_rekening',TRUE),
      'nama_rekening' =>$this->input->post('nama_rekening',TRUE),
      );

      $this->Master_rekening_model->insert($data);
      $this->global_model->_logs($menu='master_data', $sub_menu='rekening', $tabel_name='tbl_master_rekening', $action_id=null, $action='insert', $data=$data);
      $this->session->set_flashdata('message', 'Create Record Success');
      redirect(base_url('master_data/master_rekening'));
    }
  }

  public function update($id){
    $row = $this->Master_rekening_model->get_by_id($id);
$data['css'] = array(
      // "assets/bootstrap/bootstrap-datepicker-master/dist/css/bootstrap-datepicker.min.css",
      "assets/jquery/select2-4.0.6-rc.1/dist/css/select2.min.css",
      "assets/jquery/select2-bootstrap-theme-master/dist/select2-bootstrap.min.css",
      // 'assets/adminlte/plugins/iCheck/all.css',
      "assets/css/kib.css",
    );
    $data['js'] = array(
      // "assets/bootstrap/bootstrap-datepicker-master/dist/js/bootstrap-datepicker.min.js",
      // "assets/bootstrap/bootstrap-datepicker-master/dist/locales/bootstrap-datepicker.id.min.js",
      // "assets/jquery/jQuery-Mask-Plugin-master/dist/jquery.mask.min.js",
      "assets/jquery/select2-4.0.6-rc.1/dist/js/select2.min.js",
      // 'assets/adminlte/plugins/iCheck/icheck.min.js',
      // "assets/js/kib.js",
    );
    if ($row) {
      $data = array(
      'button' => 'Perbarui',
      'action' => base_url('master_data/master_rekening/update_action'),
      'id_rekening' => set_value('id_rekening', $row->id_rekening),
	  'id_sumber_dana' => set_value('id_sumber_dana', $row->id_sumber_dana),
      'kode_rekening' => set_value('kode_rekeing', $row->kode_rekening),
	  'nama_rekening' => set_value('nama_rekening', $row->nama_rekening),
      );
	  $data['list_seumber_dana'] = $this->Master_rekening_model->get_sumber_dana();
      $data['content'] = $this->load->view('master_rekening/tbl_master_rekening_form', $data, TRUE);
      $data['breadcrumb'] = array(
        array('label' => 'Administrator', 'url' => '#','icon'=>'fa fa-pie-chart', 'li_class'=>''),
        array('label' => 'Master rekening', 'url' => '#','icon'=>'', 'li_class'=>''),
        array('label' => 'Perbarui', 'url' => '#','icon'=>'', 'li_class'=>'active'),
      );
      $this->load->view('template', $data);
    } else {
      $this->session->set_flashdata('message', 'Record Not Found');
      redirect(base_url('master_data/master_rekening'));
    }
  }

  public function update_action(){
    $this->_rules();

    if ($this->form_validation->run() == FALSE) {
      $this->update($this->input->post('id_master_rekening', TRUE));
    } else {
      $data = array(
     'id_rekening' => $this->input->post('id_rekening',TRUE),
	   'id_sumber_dana' => $this->input->post('id_sumber_dana',TRUE),
      'kode_rekening' => $this->input->post('kode_rekening',TRUE),
      'nama_rekening' =>$this->input->post('nama_rekening',TRUE),
      );

      $this->Master_rekening_model->update($this->input->post('id_rekening', TRUE), $data);
      $this->global_model->_logs($menu='master_data', $sub_menu='rekening', $tabel_name='tbl_master_rekening', $action_id=$this->input->post('id_rekening', TRUE), $action='update', $data=$data);
      $this->session->set_flashdata('message', 'Update Record Success');
      redirect(base_url('master_data/master_rekening'));
    }
  }

  public function delete($id){
    $row = $this->Master_rekening_model->get_by_id($id);
  
    if ($row) {
      $this->Master_rekening_model->delete($id);
      $this->global_model->_logs($menu='master_data', $sub_menu='rekening', $tabel_name='tbl_master_rekening', $action_id=$this->input->post('id_rekening', TRUE), $action='delete', $data=$row);
      $this->session->set_flashdata('message', 'Berhasil Menghapus Data');
      redirect(base_url('master_data/master_rekening'));
    } else {
      $this->session->set_flashdata('message', 'Record Not Found');
      redirect(base_url('master_data/master_rekening'));
    }
  }

  public function _rules(){
    $this->form_validation->set_rules('nama_rekening', 'nama_rekening', 'trim|required');
	$this->form_validation->set_rules('kode_rekening', 'kode_rekening', 'trim|required');
    // $this->form_validation->set_rules('created_at', 'created at', 'trim|required');
    // $this->form_validation->set_rules('updated_at', 'updated at', 'trim|required');

    $this->form_validation->set_rules('id_rekening', 'id_rekening', 'trim');
    $this->form_validation->set_error_delimiters('<span class="text-danger">', '</span>');
  }

}

/* End of file Master_rekening.php */
/* Location: ./application/controllers/Master_rekening.php */
/* Please DO NOT modify this information : */
/* Generated by Harviacode Codeigniter CRUD Generator 2018-09-16 06:26:02 */
/* http://harviacode.com */
