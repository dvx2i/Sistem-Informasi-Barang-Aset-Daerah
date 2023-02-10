<?php

if (!defined('BASEPATH')) exit('No direct script access allowed');

class Koreksi extends MX_Controller{
  function __construct(){
    parent::__construct();
    // $this->global_model->cek_hak_akses('60');//pengajuan penghapusan
    $this->load->model('koreksi_model');
    $this->load->model('global_koreksi_model');
    $this->load->library('form_validation');
    $this->load->library('datatables');
    $this->load->helper('my_global');
  }

  public function index(){
    $data['css']= array(
      'assets/datatables/dataTables.bootstrap.css',
    );

    $data['js']=array(
      'assets/datatables/jquery.dataTables.js',
      'assets/datatables/dataTables.bootstrap.js'
    );

    $data['content']=$this->load->view('koreksi/koreksi/list',$data, true);
    $data['breadcrumb'] = array(
      array('label' => 'Koreksi', 'url' => '#','icon'=>'fa fa-pie-chart', 'li_class'=>''),
      array('label' => 'Koreksi', 'url' => '#','icon'=>'', 'li_class'=>'active'),
      // array('label' => 'Detail', 'url' => '#','icon'=>'', 'li_class'=>'active'),
    );
    $this->load->view('template', $data);
  }


  public function json_list() {
    header('Content-Type: application/json');
    echo $this->pengajuan_model->json_list();
  }


  public function create(){
    $data = array(
    'button' => 'Buat',
    'action' => base_url('koreksi/create_action'),
    // 'id_penghapusan' => set_value('id_penghapusan'),
    'kib' => set_value('kib'),
    'tanggal' => set_value('tanggal'),
    'barang' => set_value('barang'),
    );

    $data['css']=array(
    'assets/datatables/dataTables.bootstrap.css',
    "assets/bootstrap/bootstrap-datepicker-master/dist/css/bootstrap-datepicker.min.css",
    "assets/jquery/select2-4.0.6-rc.1/dist/css/select2.css",
    "assets/jquery/select2-bootstrap-theme-master/dist/select2-bootstrap.min.css",
    // 'assets/adminlte/plugins/iCheck/all.css',
    );
    $data['js']=array(
    'assets/datatables/jquery.dataTables.js',
    'assets/datatables/dataTables.bootstrap.js',
    "assets/bootstrap/bootstrap-datepicker-master/dist/js/bootstrap-datepicker.min.js",
    "assets/bootstrap/bootstrap-datepicker-master/dist/locales/bootstrap-datepicker.id.min.js",
    "assets/jquery/jQuery-Mask-Plugin-master/dist/jquery.mask.min.js",
    "assets/jquery/select2-4.0.6-rc.1/dist/js/select2.min.js",
    // "assets/js/penghapusan.js",
    // 'assets/adminlte/plugins/iCheck/icheck.min.js',
    );

    $data['list_kib']=$this->global_model->kode_jenis;

    $data['lokasi'] = $this->global_model->get_all_lokasi();

    $data['content']=$this->load->view('koreksi/koreksi/form', $data, true);
    $data['breadcrumb'] = array(
      array('label' => 'Kapitalisasi', 'url' => '#','icon'=>'fa fa-pie-chart', 'li_class'=>''),
      array('label' => 'Lis Barang', 'url' => '#','icon'=>'', 'li_class'=>'active'),
      // array('label' => 'Buat', 'url' => '#','icon'=>'', 'li_class'=>'active'),
    );
    $this->load->view('template', $data);
  }

  public function form_koreksi($kode_jenis,$id_kib){
    $data = array(
    'button' => 'Buat',
    'action' => base_url('koreksi/koreksi/create_action/'.$kode_jenis.'/'.$id_kib),

    'tanggal_pengajuan' => set_value('tanggal_pengajuan'),
    'nilai_kapitalisasi' => set_value('nilai_kapitalisasi'),
    );
    $data['kib'] = $this->global_model->get_kib($kode_jenis, $id_kib);
    $data['role_kapitalisasi'] = json_encode($this->global_kapitalisasi_model->get_role_kapitalisasi($data['kib']->id_kode_barang));
    // die(json_encode($data['role_kapitalisasi']));
    $data['css']=array(
    // 'assets/datatables/dataTables.bootstrap.css',
    "assets/bootstrap/bootstrap-datepicker-master/dist/css/bootstrap-datepicker.min.css",
    "assets/jquery/select2-4.0.6-rc.1/dist/css/select2.css",
    "assets/jquery/select2-bootstrap-theme-master/dist/select2-bootstrap.min.css",
    // 'assets/adminlte/plugins/iCheck/all.css',
    );
    $data['js']=array(
    // 'assets/datatables/jquery.dataTables.js',
    // 'assets/datatables/dataTables.bootstrap.js',
    "assets/bootstrap/bootstrap-datepicker-master/dist/js/bootstrap-datepicker.min.js",
    "assets/bootstrap/bootstrap-datepicker-master/dist/locales/bootstrap-datepicker.id.min.js",
    "assets/jquery/jQuery-Mask-Plugin-master/dist/jquery.mask.min.js",
    "assets/jquery/select2-4.0.6-rc.1/dist/js/select2.min.js",
    // "assets/js/penghapusan.js",
    // 'assets/adminlte/plugins/iCheck/icheck.min.js',
    );

    // $data['lokasi'] = $this->global_model->get_all_lokasi();

    $data['content']=$this->load->view('pengajuan/form_kapitalisasi', $data, true);
    $data['breadcrumb'] = array(
      array('label' => 'Kapitalisasi', 'url' => '#','icon'=>'fa fa-pie-chart', 'li_class'=>''),
      array('label' => 'Pengajuan', 'url' => '#','icon'=>'', 'li_class'=>''),
      array('label' => 'Buat', 'url' => '#','icon'=>'', 'li_class'=>'active'),
    );
    $this->load->view('template', $data);
  }

  public function create_action($kode_jenis,$id_kib){
    $this->_rules();

    if ($this->form_validation->run() == FALSE) {
      $this->form_kapitalisasi($kode_jenis,$id_kib);
    } else {
      $data_insert = array(
        'tanggal_pengajuan' => date('Y-m-d', tgl_inter($this->input->post('tanggal_pengajuan',TRUE))),
        'status' => '1',
        'id_kode_lokasi'=>$this->session->userdata('session')->id_kode_lokasi,
        'kode_jenis'=>$kode_jenis,
        'id_kib'=>$id_kib,
        'id_kode_barang'=>$this->input->post('id_kode_barang',TRUE),
        'nilai_kapitalisasi'=>str_replace('.', '', $this->input->post('nilai_kapitalisasi',TRUE)),
        'penambahan_umur_ekonomis'=>str_replace('.', '', $this->input->post('penambahan_umur_ekonomis',TRUE)),
      );
      $this->pengajuan_model->insert($data_insert);
      $this->global_model->_logs($menu='kapitalisasi', $sub_menu='pengajuan', $tabel_name='tbl_kapitalisasi', $action_id=null, $action='insert', $data=$data_insert, $feature='create_action');
      $this->session->set_flashdata('message', 'Berhasil membuat pengajuan.');
      redirect(base_url('kapitalisasi/pengajuan'));
    }
  }

  public function delete($id){
    $row = $this->pengajuan_model->get_by_id($id);
    if ($row and $row->status == 2) {
      $this->session->set_flashdata('message', 'Data tidak dapat dihapus.');
      redirect(base_url('kapitalisasi/pengajuan'));
    }
    else if ($row) {
      $this->pengajuan_model->delete($id);
      $this->global_model->_logs($menu='kapitalisasi', $sub_menu='pengajuan', $tabel_name='tbl_kapitalisasi', $action_id=$id, $action='delete', $data=$row, $feature='delete');
                              //($menu='location', $sub_menu='set_location', $tabel_name='tbl_kode_location', $action_id=$session->id_user, $action='set', $data=$data, $feature);
      $this->session->set_flashdata('message', 'Berhasil menghapus pengajuan.');
      redirect(base_url('kapitalisasi/pengajuan'));
    } else {
      $this->session->set_flashdata('message', 'Record Not Found');
      redirect(base_url('kapitalisasi/pengajuan'));
    }
  }

  public function _rules(){
    $this->form_validation->set_rules('tanggal_pengajuan', 'tanggal pengajuan', 'trim|required');

    $this->form_validation->set_rules('nilai_kapitalisasi', 'nominal', 'trim|required');
    $this->form_validation->set_error_delimiters('<span class="text-danger">', '</span>');
    $this->form_validation->set_message('required', 'Objek %s harus di isi.');
    // $this->form_validation->set_message('required', '<b title="Objek %s harus di isi.">*</b>');
  }



}
