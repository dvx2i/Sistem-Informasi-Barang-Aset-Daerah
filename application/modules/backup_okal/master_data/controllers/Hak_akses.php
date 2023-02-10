<?php

if (!defined('BASEPATH'))
exit('No direct script access allowed');

class Hak_akses extends MX_Controller{
  function __construct(){
    parent::__construct();
    $this->global_model->cek_hak_akses('21');
    $this->load->model('Hak_akses_model','akses_model');
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
    $list_jabatan = $this->akses_model->getJabatan();

    $data['list_jabatan']=null;
    foreach ($list_jabatan as $value) {
      $data['list_jabatan'] .="'".ucwords($value->description)."',";
    }
    $data["content"]=$this->load->view('hak_akses/hak_akses_list', $data, TRUE);
    $data['breadcrumb'] = array(
      array('label' => 'Administrator', 'url' => '#','icon'=>'fa fa-pie-chart', 'li_class'=>''),
      array('label' => 'Hak Akses', 'url' => '#','icon'=>'', 'li_class'=>'active'),
      // array('label' => 'Buat', 'url' => '#','icon'=>'', 'li_class'=>'active'),
    );
    $this->load->view('template', $data);
  }

  public function json() {
    header('Content-Type: application/json');
    echo $this->akses_model->json();
  }


  public function create_action(){
    if ($this->akses_model->cekNamaHakAkses($this->input->post('description',TRUE))) {
      echo "Data Hak Akses sudah ada.";
      header( "refresh:3;url=".base_url('master_data/hak_akses') );
      die();
    }

    $role = strtolower($this->input->post('description',TRUE));
    $role = str_replace(' ', '_', $role);
    $data = array(
      'role' => $role,
      'description' => $this->input->post('description',TRUE),
    );

    $this->akses_model->insertRole($data);
    $this->global_model->_logs($menu='administrator', $sub_menu='hak_akses', $tabel_name='tbl_role', $action_id=null, $action='insert', $data=$data);
    $this->session->set_flashdata('message', 'Berhasil Membuat Hak Akses');
    redirect(base_url('master_data/hak_akses'));
  }

  public function update($id){
    $row = $this->akses_model->get_by_id($id);

    if ($row) {
      $data['data'] = array(
        'button' => 'Perbarui',
        'action' => base_url('master_data/hak_akses/update_action'),
        'id_role' => set_value('id_role', $row->id_role),
      );



      $data['css']=array(
        "assets/adminlte/bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css",
        "assets/jquery/select2-4.0.6-rc.1/dist/css/select2.min.css",
        "assets/jquery/select2-bootstrap-theme-master/dist/select2-bootstrap.min.css",
        'assets/adminlte/plugins/iCheck/all.css',
        'assets/sweetalert/sweetalert.css',
      );
      $data['js']=array(
        "assets/adminlte/bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js",
        "assets/jquery/jQuery-Mask-Plugin-master/dist/jquery.mask.min.js",
        "assets/jquery/select2-4.0.6-rc.1/dist/js/select2.min.js",
        'assets/adminlte/plugins/iCheck/icheck.min.js',
        'assets/sweetalert/sweetalert.min.js',
        "assets/js/user.js",
      );


      $data['list_menu'] = $this->akses_model->getMenu();
      $data['list_sub_menu'] = $this->akses_model->getSubMenu($id);

      // $hak_akses_array =  array();
      foreach ($data['list_sub_menu'] as $key => $value) {
        if ($value->id_hak_akses == 0 and $value->id_role == 0) {
          // array_push($hak_akses_array,array("id_role"=>$id,"id_menu"=>$value->id_menu));
          $data_insert = array("id_role"=>$id,"id_menu"=>$value->id_menu);
          $this->akses_model->insert($data_insert);
          $this->global_model->_logs($menu='administrator', $sub_menu='hak_akses', $tabel_name='tbl_hak_akses', $action_id=0, $action='insert', $data_insert);
        }
      }
      /*if ($hak_akses_array) { //insert menu yang belum ada di tbl hak akses
        $this->akses_model->insertHakAkses($hak_akses_array);
        $this->global_model->_logs($menu='administrator', $sub_menu='hak_akses', $tabel_name='tbl_hak_akses', $action_id=0, $action='insert', $hak_akses_array);
      }*/

      $data['list_sub_menu'] = $this->akses_model->getSubMenu($id); //get ulang tbl_hak_akses

      $data["content"]=$this->load->view('hak_akses/hak_akses_form', $data, TRUE);
      $data['breadcrumb'] = array(
        array('label' => 'Administrator', 'url' => '#','icon'=>'fa fa-pie-chart', 'li_class'=>''),
        array('label' => 'Hak Akses', 'url' => '#','icon'=>'', 'li_class'=>''),
        array('label' => 'Set Menu', 'url' => '#','icon'=>'', 'li_class'=>'active'),
      );
      $this->load->view('template', $data);

    } else {
      $this->session->set_flashdata('message', 'Record Not Found');
      redirect(base_url('master_data/hak_akses'));
    }
  }

  public function update_action(){

    $list_id_menu = $this->input->post('list_id_menu',TRUE);
    ksort($list_id_menu);
    // die(json_encode($list_id_menu));
    $checkbox_akses = $this->input->post('checkbox_akses',TRUE);
    ksort($checkbox_akses);
    // die(json_encode($checkbox_akses));

    foreach ($list_id_menu as $key => $value) {
      foreach ($value as $key2 => $value2) {
        $data_update = null;
        if (in_array($value2,$checkbox_akses)) {
          $data_update = array('active' => 'y', 'updated_at'=>date('Y-m-d H:i:s')) ;
          $this->akses_model->update($key, $data_update);

        }else {
          $data_update = array('active' => 'n', 'updated_at'=>date('Y-m-d H:i:s')) ;
          $this->akses_model->update($key, $data_update);
        }
        $this->global_model->_logs($menu='administrator', $sub_menu='hak_akses', $tabel_name='tbl_hak_akses', $action_id=$key, $action='update', $data_update);
      }
    }

    $this->session->set_flashdata('message', 'Update Berhasil');
    redirect(base_url('master_data/hak_akses'));
  }

  public function delete($id){
    $row = $this->User_model->get_by_id($id);

    if ($row) {
      $this->User_model->delete($id);
      $this->global_model->_logs($menu='user', $sub_menu='user', $tabel_name='tbl_user', $action_id=$id, $action='delete', $data=$row);
      $this->session->set_flashdata('message', 'Delete Record Success');
      redirect(base_url('user'));
    } else {
      $this->session->set_flashdata('message', 'Record Not Found');
      redirect(base_url('user'));
    }
  }

  public function _rules(){
    $this->form_validation->set_rules('nama', 'nama', 'trim|required');
    $this->form_validation->set_rules('nik', 'nik', 'trim|required');
    $this->form_validation->set_rules('nip', 'nip', 'trim|required');
    $this->form_validation->set_rules('alamat', 'alamat', 'trim|required');
    $this->form_validation->set_rules('email', 'email', 'trim|required|valid_email');
    $this->form_validation->set_rules('kode_lokasi', 'kode lokasi', 'trim|required');

    $this->form_validation->set_rules('id_user', 'id_user', 'trim');
    $this->form_validation->set_error_delimiters('<span class="text-danger">', '</span>');
  }

  public function get_jss(){
    $id_upik = $this->input->post('id_upik');
    $url = "https://webservice.jogjakota.go.id/jss/akun?id=".$id_upik;
    $data = json_decode(file_get_contents($url));
    $status = isset($data[0])?TRUE:FALSE;

    if ($status) {
      $data = $data[0];

      if ($this->User_model->get_by_id_upik($data->id_upik)) {
        $status=FALSE;
        echo json_encode(array('status' => $status,'message'=>'Data JSS sudah ada.' ));
      }else{
        $data->status=TRUE;
        echo json_encode($data);
      }
    }else {
      echo json_encode(array('status' => $status,'message'=>'Data tidak ditemukan.' ));
    }
  }

}

  /* End of file User.php */
  /* Location: ./application/controllers/User.php */
  /* Please DO NOT modify this information : */
  /* Generated by Harviacode Codeigniter CRUD Generator 2018-08-09 06:17:03 */
  /* http://harviacode.com */
