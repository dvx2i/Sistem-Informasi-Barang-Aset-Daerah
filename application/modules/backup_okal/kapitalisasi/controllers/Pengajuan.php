<?php

if (!defined('BASEPATH')) exit('No direct script access allowed');

class Pengajuan extends MX_Controller
{
  function __construct()
  {
    parent::__construct();
    $this->global_model->cek_hak_akses('60'); //pengajuan penghapusan
    $this->load->model('pengajuan_model');
    $this->load->model('global_kapitalisasi_model');
    $this->load->library('form_validation');
    $this->load->library('datatables');
    $this->load->helper('my_global');
  }

  public function index()
  {
    //HAPUS SESSION REKLAS
    $this->session->unset_userdata('data_reklas');
    $data['css'] = array(
      'assets/datatables/dataTables.bootstrap.css',
      'assets/sweetalert/sweetalert2.css',
    );

    $data['js'] = array(
      'assets/datatables/jquery.dataTables.js',
      'assets/datatables/dataTables.bootstrap.js',
      'assets/sweetalert/sweetalert2.js',
    );

    $data['content'] = $this->load->view('pengajuan/list', $data, true);
    $data['breadcrumb'] = array(
      array('label' => 'Kapitalisasi', 'url' => '#', 'icon' => 'fa fa-pie-chart', 'li_class' => ''),
      array('label' => 'Pengajuan', 'url' => '#', 'icon' => '', 'li_class' => 'active'),
      // array('label' => 'Detail', 'url' => '#','icon'=>'', 'li_class'=>'active'),
    );
    $this->load->view('template', $data);
  }


  public function json_list()
  {
    header('Content-Type: application/json');
    echo $this->pengajuan_model->json_list();
  }


  public function create()
  {
    $data = array(
      'button' => 'Buat',
      'action' => base_url('kapitalisasi/pengajuan/create_action'),
      // 'id_penghapusan' => set_value('id_penghapusan'),
      'kib' => set_value('kib'),
      'tanggal' => set_value('tanggal'),
      'barang' => set_value('barang'),
    );

    $data['css'] = array(
      'assets/datatables/dataTables.bootstrap.css',
      "assets/bootstrap/bootstrap-datepicker-master/dist/css/bootstrap-datepicker.min.css",
      "assets/jquery/select2-4.0.6-rc.1/dist/css/select2.css",
      "assets/jquery/select2-bootstrap-theme-master/dist/select2-bootstrap.min.css",
      // 'assets/adminlte/plugins/iCheck/all.css',
    );
    $data['js'] = array(
      'assets/datatables/jquery.dataTables.js',
      'assets/datatables/dataTables.bootstrap.js',
      "assets/bootstrap/bootstrap-datepicker-master/dist/js/bootstrap-datepicker.min.js",
      "assets/bootstrap/bootstrap-datepicker-master/dist/locales/bootstrap-datepicker.id.min.js",
      "assets/jquery/jQuery-Mask-Plugin-master/dist/jquery.mask.min.js",
      "assets/jquery/select2-4.0.6-rc.1/dist/js/select2.min.js",
      // "assets/js/penghapusan.js",
      // 'assets/adminlte/plugins/iCheck/icheck.min.js',
    );

    //REKLAS CONDITION
    $data['data_reklas'] = null;
    $data_reklas = $this->session->userdata('data_reklas');
    // die(json_encode($data_reklas));
    if (!empty($data_reklas)) {
      $kode_jenis_asal = $this->global_model->kode_jenis[$data_reklas['kode_jenis']];

      // $kib_asal = $this->global_model->row_get_where('view_kib', array('kode_jenis' => $data_reklas['kode_jenis'],'id_kib' => $data_reklas['id_kib'],));
      // $kode_barang_asal = $this->global_model->row_get_where('tbl_kode_barang', array('id_kode_barang' => $data_reklas['id_kode_barang'],));
      // $kode_barang_tujuan = $this->global_model->row_get_where('tbl_kode_barang', array('id_kode_barang' => $data_reklas['id_kode_barang_tujuan'],));
      $data['data_reklas'] = $data_reklas;
      // $data['kode_jenis_asal'] = $kode_jenis_asal;
      // $data['kib_asal'] = $kib_asal;
      // $data['kode_barang_asal'] = $kode_barang_asal;

      $jenis_tujuan_obj = explode('.', $data_reklas['kode_jenis_tujuan']);

      $data['kode_jenis_tujuan'] = $jenis_tujuan_obj[2];
      // $data['kode_objek'] = set_value('kode_objek', $kode_barang_tujuan['kode_objek']);
      // $data['kode_rincian_objek'] = set_value('kode_rincian_objek', $kode_barang_tujuan['kode_rincian_objek']);
      // $data['kode_sub_rincian_objek'] = set_value('kode_sub_rincian_objek', $kode_barang_tujuan['kode_sub_rincian_objek']);
      // $data['kode_sub_sub_rincian_objek'] = set_value('kode_sub_sub_rincian_objek', $kode_barang_tujuan['kode_sub_sub_rincian_objek']);
      // $data['kode_barang'] = set_value('kode_barang', $kode_barang_tujuan['kode_barang']);
      // $data['nama_barang'] = set_value('nama_barang', $kode_barang_tujuan['nama_barang_simbada'] ? $kode_barang_tujuan['nama_barang_simbada'] : $kode_barang_tujuan['nama_barang']);
      // die(json_encode($data));
    }
    //END REKLAS CONDITION




    $data['list_kib'] = $this->global_model->kode_jenis;

    $data['lokasi'] = $this->global_model->get_all_lokasi();

    $data['content'] = $this->load->view('pengajuan/form', $data, true);
    $data['breadcrumb'] = array(
      array('label' => 'Kapitalisasi', 'url' => '#', 'icon' => 'fa fa-pie-chart', 'li_class' => ''),
      array('label' => 'Daftar Barang', 'url' => '#', 'icon' => '', 'li_class' => 'active'),
      // array('label' => 'Buat', 'url' => '#','icon'=>'', 'li_class'=>'active'),
    );
    $this->load->view('template', $data);
  }

  public function form_kapitalisasi($kode_jenis, $id_kib)
  {
    $data = array(
      'button' => 'Buat',
      'action' => base_url('kapitalisasi/pengajuan/create_action/' . $kode_jenis . '/' . $id_kib),

      'tanggal_pengajuan' => set_value('tanggal_pengajuan'),
      'nilai_kapitalisasi' => set_value('nilai_kapitalisasi'),
      'sumber_dana' => set_value('sumber_dana'),
      'rekening' => set_value('rekening'),
      'kode_jenis' => $kode_jenis
    );
    $data['kib'] = $this->global_model->get_kib($kode_jenis, $id_kib);
    $data['role_kapitalisasi'] = json_encode($this->global_kapitalisasi_model->get_role_kapitalisasi($data['kib']->id_kode_barang));
    // die(json_encode($data['role_kapitalisasi']));
    $data['css'] = array(
      // 'assets/datatables/dataTables.bootstrap.css',
      "assets/bootstrap/bootstrap-datepicker-master/dist/css/bootstrap-datepicker.min.css",
      "assets/jquery/select2-4.0.6-rc.1/dist/css/select2.css",
      "assets/jquery/select2-bootstrap-theme-master/dist/select2-bootstrap.min.css",
      'assets/sweetalert/sweetalert.css',
      // 'assets/adminlte/plugins/iCheck/all.css',
    );
    $data['js'] = array(
      // 'assets/datatables/jquery.dataTables.js',
      // 'assets/datatables/dataTables.bootstrap.js',
      "assets/bootstrap/bootstrap-datepicker-master/dist/js/bootstrap-datepicker.min.js",
      "assets/bootstrap/bootstrap-datepicker-master/dist/locales/bootstrap-datepicker.id.min.js",
      "assets/jquery/jQuery-Mask-Plugin-master/dist/jquery.mask.min.js",
      "assets/jquery/select2-4.0.6-rc.1/dist/js/select2.min.js",
      'assets/sweetalert/sweetalert.min.js',
      // "assets/js/penghapusan.js",
      // 'assets/adminlte/plugins/iCheck/icheck.min.js',
    );

    //REKLAS CONDITION
    $data['data_reklas'] = null;
    $data_reklas = $this->session->userdata('data_reklas');
    // die(json_encode($data_reklas));
    if (!empty($data_reklas)) {
      $kode_jenis_asal = $this->global_model->kode_jenis[$data_reklas['kode_jenis']];

      // $res = $this->db->get_where($kode_jenis_asal['table'],  array($kode_jenis_asal['id_name'] => $id_kib));
      $kib_asal = $this->global_model->row_get_where($kode_jenis_asal['table'], array($kode_jenis_asal['id_name'] => $data_reklas['id_kib'],));
      $kode_barang_asal = $this->global_model->row_get_where('tbl_kode_barang', array('id_kode_barang' => $data_reklas['id_kode_barang'],));
      // $kode_barang_tujuan = $this->global_model->row_get_where('tbl_kode_barang', array('id_kode_barang' => $data_reklas['id_kode_barang_tujuan'],));
      $data['data_reklas'] = $data_reklas;
      $data['kode_jenis_asal'] = $data_reklas['kode_jenis'];
      $data['kib_asal'] = $kib_asal;
      $data['kode_barang_asal'] = $kode_barang_asal;
      $data['tanggal_pengajuan'] = set_value('tanggal_pengajuan', tgl_indo($data_reklas['tanggal']));

      // $jenis_tujuan_obj = explode('.', $data_reklas['kode_jenis_tujuan']);
      // $kode_jenis_tujuan = $this->global_model->kode_jenis[$data_reklas['kode_jenis']];
      // die(json_encode($data));
      // $data['kode_jenis_tujuan'] = $jenis_tujuan_obj[2];
      // $data['kode_objek'] = set_value('kode_objek', $kode_barang_tujuan['kode_objek']);
      // $data['kode_rincian_objek'] = set_value('kode_rincian_objek', $kode_barang_tujuan['kode_rincian_objek']);
      // $data['kode_sub_rincian_objek'] = set_value('kode_sub_rincian_objek', $kode_barang_tujuan['kode_sub_rincian_objek']);
      // $data['kode_sub_sub_rincian_objek'] = set_value('kode_sub_sub_rincian_objek', $kode_barang_tujuan['kode_sub_sub_rincian_objek']);
      // $data['kode_barang'] = set_value('kode_barang', $kode_barang_tujuan['kode_barang']);
      // $data['nama_barang'] = set_value('nama_barang', $kode_barang_tujuan['nama_barang_simbada'] ? $kode_barang_tujuan['nama_barang_simbada'] : $kode_barang_tujuan['nama_barang']);
      // die(json_encode($data));
    }
    //END REKLAS CONDITION

    
    //sumber dana
    $data['lis_sumber_dana'] = $this->global_model->get_sumber_dana();
    //kode rekening
    $data['lis_rekening'] = $this->global_model->get_rekening($data['sumber_dana']);



    // $data['lokasi'] = $this->global_model->get_all_lokasi();

    $data['content'] = $this->load->view('pengajuan/form_kapitalisasi', $data, true);
    $data['breadcrumb'] = array(
      array('label' => 'Kapitalisasi', 'url' => '#', 'icon' => 'fa fa-pie-chart', 'li_class' => ''),
      array('label' => 'Pengajuan', 'url' => '#', 'icon' => '', 'li_class' => ''),
      array('label' => 'Buat', 'url' => '#', 'icon' => '', 'li_class' => 'active'),
    );
    $this->load->view('template', $data);
  }

  public function create_action($kode_jenis, $id_kib)
  {
    $this->_rules();

    if ($this->form_validation->run() == FALSE) {
      $this->form_kapitalisasi($kode_jenis, $id_kib);
    } else {

      $jenis = '';
      $nilai_kapitalisasi = 0;
      if ($this->input->post('nilai_kapitalisasi')) {
        $jenis = 'kapitalisasi';
        $nilai_kapitalisasi = $this->input->post('nilai_kapitalisasi');
        // die($nilai_kapaitalisasi);
      } else if ($this->input->post('koreksi_tambah')) {
        $jenis = 'koreksi_tambah';
        $nilai_kapitalisasi = $this->input->post('koreksi_tambah');
      } else if ($this->input->post('koreksi_kurang')) {
        $jenis = 'koreksi_kurang';
        $nilai_kapitalisasi = $this->input->post('koreksi_kurang');
      }
      // print_r($_POST); die;

      $harga_awal = $this->pengajuan_model->get_harga_awal($kode_jenis, $id_kib);


      $status_data = 'kapitalisasi';
      //REKLAS CONDITION
      $data['data_reklas'] = null;
      $data_reklas = $this->session->userdata('data_reklas');
      // die(json_encode($data_reklas));
      if (!empty($data_reklas)) {
        $status_data = 'reklas';
      }
      
			  $nilai_kapitalisasi = str_replace('.', '', $nilai_kapitalisasi);
			  $nilai_kapitalisasi = str_replace('Rp', '', $nilai_kapitalisasi);
			  $nilai_kapitalisasi = str_replace(',', '.', $nilai_kapitalisasi);

      $data_insert = array(
        'jenis' => $jenis,
        'tanggal_pengajuan' => date('Y-m-d', tgl_inter($this->input->post('tanggal_pengajuan', TRUE))),
        'status' => '1',
        'id_kode_lokasi' => $this->session->userdata('session')->id_kode_lokasi,
        'kode_jenis' => $kode_jenis,
        'id_kib' => $id_kib,
        'id_kode_barang' => $this->input->post('id_kode_barang', TRUE),
        'nilai_kapitalisasi' => $nilai_kapitalisasi,
        'nilai_awal' => $harga_awal,
        'penambahan_umur_ekonomis' => str_replace('.', '', $this->input->post('penambahan_umur_ekonomis', TRUE)),
        'status_data' => $status_data,
        'id_sumber_dana_kapitalisasi' => $this->input->post('sumber_dana', TRUE),
        'id_rekening_kapitalisasi' => $this->input->post('rekening', TRUE),
      );
      // die(json_encode($data_reklas));
      $id = $this->pengajuan_model->insert($data_insert);
      $this->global_model->_logs($menu = 'kapitalisasi', $sub_menu = 'pengajuan', $tabel_name = 'tbl_kapitalisasi', $action_id = null, $action = 'insert', $data = $data_insert, $feature = 'create_action');


      //REKLAS CONDITION
      // $data_reklas = $this->session->userdata('data_reklas');
      if (!empty($data_reklas)) {
        //REKLAS CONDITION
        $data_insert['status_barang'] = 'reklas_kode';
        $this->global_model->global_update(
          'tbl_reklas_kode',
          array('id_reklas_kode' => $data_reklas['id_reklas_kode']),
          array('id_kib_tujuan' => $id_kib,)
        );
        $this->global_model->_logs($menu = 'kapitalisasi', $sub_menu = 'pengajuan', $tabel_name = 'tbl_kapitalisasi', $action_id = $id, $action = 'insert_reklas', $data = $data_insert, $feature = 'create_action');
        //HAPUS SESSION REKLAS
        $this->session->unset_userdata('data_reklas');
        redirect(base_url('reklas/reklas_kode'));
        //END REKLAS CONDITION
      }

      $this->session->set_flashdata('message', 'Berhasil membuat pengajuan.');
      redirect(base_url('kapitalisasi/pengajuan'));
    }
  }

  public function delete($id)
  {
    $row = $this->pengajuan_model->get_by_id($id);
    if ($row and $row->status == 2) {
      $this->session->set_flashdata('message', 'Data tidak dapat dihapus.');
      redirect(base_url('kapitalisasi/pengajuan'));
    } else if ($row) {
      $this->pengajuan_model->delete($id);
      $this->global_model->_logs($menu = 'kapitalisasi', $sub_menu = 'pengajuan', $tabel_name = 'tbl_kapitalisasi', $action_id = $id, $action = 'delete', $data = $row, $feature = 'delete');
      //($menu='location', $sub_menu='set_location', $tabel_name='tbl_kode_location', $action_id=$session->id_user, $action='set', $data=$data, $feature);
      $this->session->set_flashdata('message', 'Berhasil menghapus pengajuan.');
      redirect(base_url('kapitalisasi/pengajuan'));
    } else {
      $this->session->set_flashdata('message', 'Record Not Found');
      redirect(base_url('kapitalisasi/pengajuan'));
    }
  }

  public function _rules()
  {
    $this->form_validation->set_rules('tanggal_pengajuan', 'tanggal pengajuan', 'trim|required');

    // $this->form_validation->set_rules('nilai_kapitalisasi', 'nominal', 'trim|required');
    $this->form_validation->set_error_delimiters('<span class="text-danger">', '</span>');
    $this->form_validation->set_message('required', 'Objek %s harus di isi.');
    // $this->form_validation->set_message('required', '<b title="Objek %s harus di isi.">*</b>');
  }
}
