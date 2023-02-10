<?php

if (!defined('BASEPATH')) exit('No direct script access allowed');

class Pengajuan extends MX_Controller
{
  protected $kode_jenis = '01';
  function __construct()
  {
    parent::__construct();
    //$this->global_model->cek_hak_akses('23'); //pengajuan
    $this->load->model('pengajuan_model');
    $this->load->model('global_mutasi_model');
    $this->load->library('form_validation');
    $this->load->library('datatables');
    $this->load->helper('my_global');
  }

  public function index()
  {
    $data['css'] = array(
      'assets/datatables/dataTables.min.css',
    );

    $data['js'] = array(
      // 'assets/datatables/jquery.dataTables.js',
      // 'assets/datatables/dataTables.min.js'
    );

    $data['content'] = $this->load->view('pengajuan/list', $data, true);
    $data['breadcrumb'] = array(
      array('label' => 'Barang ruang', 'url' => '#', 'icon' => 'fa fa-pie-chart', 'li_class' => ''),
      array('label' => 'Barang', 'url' => '#', 'icon' => '', 'li_class' => 'active'),
      // array('label' => 'Buat', 'url' => '#','icon'=>'', 'li_class'=>'active'),
    );
    $this->load->view('template', $data);
  }



  public function detail($id_mutasi)
  {
    $id_mutasi = decrypt_url($id_mutasi);

    $data['css'] = array(
      'assets/datatables/dataTables.bootstrap.css',
      "assets/css/progress-tracker.css",
    );

    $data['js'] = array(
      'assets/datatables/jquery.dataTables.js',
      'assets/datatables/dataTables.bootstrap.js'
    );

    $data['list_status_mutasi'] = $this->global_mutasi_model->get_status_mutasi_all();
    $data['id_status_mutasi'] = $this->global_mutasi_model->get_mutasi($id_mutasi);
    // die(json_encode($data['id_status_mutasi']));
    $data['id_mutasi'] = $id_mutasi;
    $data['content'] = $this->load->view('pengajuan/detail', $data, true);
    $data['breadcrumb'] = array(
      array('label' => 'Mutasi', 'url' => '#', 'icon' => 'fa fa-pie-chart', 'li_class' => ''),
      array('label' => 'Pengajuan', 'url' => '#', 'icon' => '', 'li_class' => ''),
      array('label' => 'Detail', 'url' => '#', 'icon' => '', 'li_class' => 'active'),
    );
    $this->load->view('template', $data);
  }


  public function json()
  {
    header('Content-Type: application/json');
    echo $this->pengajuan_model->json();
  }

  public function json_detail($id_mutasi)
  {
    header('Content-Type: application/json');
    echo $this->pengajuan_model->json_detail($id_mutasi);
  }


  public function create()
  {
    //echo "aa";die;
    $data = array(
      'button' => 'Buat',
      'action' => base_url('master_ruang/pengajuan/create_action'),
      'id_ruang' => set_value('id_ruang'),
      'kib' => set_value('kib'),
      'tanggal' => set_value('tanggal'),
      'id_pengguna' => set_value('id_pengguna'),
      'id_kuasa_pengguna' => set_value('id_kuasa_pengguna'),
      'id_sub_kuasa_pengguna' => set_value('id_sub_kuasa_pengguna'),
      'kode_lokasi' => set_value('kode_lokasi'),
      //'kode_lokasi_baru' => set_value('kode_lokasi_baru'),

      'barang' => set_value('barang'),
    );

    $data['css'] = array(
      'assets/datatables/dataTables.bootstrap.css',
      "assets/bootstrap/bootstrap-datepicker-master/dist/css/bootstrap-datepicker.min.css",
      "assets/jquery/select2-4.0.6-rc.1/dist/css/select2.css",
      "assets/jquery/select2-bootstrap-theme-master/dist/select2-bootstrap.min.css",
      'assets/adminlte/plugins/iCheck/all.css',
      'assets/sweetalert/sweetalert.css',
    );
    $data['js'] = array(
      'assets/datatables/jquery.dataTables.js',
      'assets/datatables/dataTables.bootstrap.js',
      "assets/bootstrap/bootstrap-datepicker-master/dist/js/bootstrap-datepicker.min.js",
      "assets/bootstrap/bootstrap-datepicker-master/dist/locales/bootstrap-datepicker.id.min.js",
      "assets/jquery/jQuery-Mask-Plugin-master/dist/jquery.mask.min.js",
      "assets/jquery/select2-4.0.6-rc.1/dist/js/select2.min.js",
      'assets/sweetalert/sweetalert.min.js',
      "assets/js/mutasi.js",
      'assets/adminlte/plugins/iCheck/icheck.min.js',
    );

    $data['list_kib'] = $this->global_model->kode_jenis_ruang;

    $session = $this->session->userdata('session');
    // die(json_encode(substr($session->kode_lokasi, -1)));
    $data['show_lokasi'] = false;
    if (substr($session->kode_lokasi, -1) == "*")
      $data['show_lokasi'] = true;

    //filter

    $data['pemilik'] = $this->global_model->get_pemilik();
    $data['intra_ekstra'] = array(
      '00' => 'Semua',
      '01' => 'Intrakomptable',
      '02' => 'Extrakomptable',
    );

    //$session = $this->session->userdata('session');
    $lokasi_explode = $this->global_model->get_kode_lokasi_by_id($session->id_kode_lokasi);

    $data['pengguna_list'] = $this->global_model->get_pengguna();
    // die(json_encode($data['pengguna_list']));
    $data['pengguna'] = $this->global_model->get_view_pengguna($lokasi_explode->pengguna);
    // if (empty($data['pengguna'])) $data['pengguna']->id_pengguna = "";

    // die(json_encode($lokasi_explode->pengguna));
    if ($data['pengguna']) {
      $data['kuasa_pengguna_list'] = $this->global_model->get_kuasa_pengguna_by_pengguna($data['pengguna']->pengguna);
      $data['kuasa_pengguna'] = $this->global_model->get_view_kuasa_pengguna($lokasi_explode->pengguna, $lokasi_explode->kuasa_pengguna);
        // die(json_encode($data['sub_kuasa_pengguna_list']));
        //     $data['sub_kuasa_pengguna'] = $this->global_model->get_view_sub_kuasa_pengguna($lokasi_explode->pengguna, $lokasi_explode->kuasa_pengguna, $lokasi_explode->sub_kuasa_pengguna);
      }
    

    if ($data['kode_lokasi'])
      $data['lokasi'] = $this->pengajuan_model->get_lokasi($data['kode_lokasi']);

    $data['content'] = $this->load->view('pengajuan/form', $data, true);
    $data['breadcrumb'] = array(
      array('label' => 'master_ruang', 'url' => '#', 'icon' => 'fa fa-pie-chart', 'li_class' => ''),
      array('label' => 'Pengajuan', 'url' => '#', 'icon' => '', 'li_class' => ''),
      array('label' => 'Buat', 'url' => '#', 'icon' => '', 'li_class' => 'active'),
    );
    $this->load->view('template', $data);
  }

  public function create_action()
  {
    
    $this->_rules();
    // die($this->input->post('kode_lokasi_baru', TRUE));
    if ($this->form_validation->run() == FALSE) {
      //echo "aaa";die;
      $this->create();
    } else {
 //echo "bb";die;
      $id_pengguna = $this->input->post('id_pengguna', TRUE);
      $id_lokasi = $this->input->post('id_kuasa_pengguna', TRUE);
      $id_skpd = $this->global_mutasi_model->get_skpd($id_lokasi)['id_pengguna'];
      $id_ruang =$this->input->post('id_ruang', TRUE);
      $list_id_b = $this->input->post('list_id_b');
      $list_id_e = $this->input->post('list_id_e');
       //$id_kib =$this->input->post('id_kib', TRUE);
      //$id_skpd_baru = $this->global_mutasi_model->get_skpd($id_lokasi_baru)['id_pengguna'];
      // die(date("Y-m-d", tgl_inter('11 Agustus 2020')));

      // die(json_encode($data));
      $this->db->trans_start();
      //$id = $this->pengajuan_model->insert($data); //insert ke tbl_mutasi
      // die(json_encode($this->input->post('pengajuan')));
      // $arr_barang = array();
      // foreach ($this->input->post('pengajuan') as $key => $value) {
        // die(json_encode($value));
      //   foreach ($value as $value_2) {
      //     array_push(
      //       $arr_barang,
      //       array(
      //         "id_ruang" => $id_ruang,
      //         "kode_jenis" => str_replace("-", ".", $key), // index array menggunkan "." tidak mau
      //         "id_kib" => $value_2,
      //         'id_skpd' => $id_skpd,
      //         "status_diterima" => '1',
      //          'id_kode_lokasi' => $id_lokasi,
      //         "tanggal_diterima" => date('Y-m-d'),
      //       )
      //     );
      //   }
      // }
      // die('-');
      // die(json_encode($this->input->post()));
       //die(json_encode($arr_barang));

      $arr_barang = array();
      // die(json_encode($value));
      
      
      if(!empty($list_id_b)){
        $list_id_b = explode(",",$list_id_b);
        foreach($list_id_b as $key => $value_x){
          array_push(
            $arr_barang,
            array(
              "id_ruang" => $id_ruang,
              "kode_jenis" => '02', // index array menggunkan "." tidak mau
              "id_kib" => $value_x,
              'id_skpd' => $id_skpd,
              "status_diterima" => '1',
              'id_kode_lokasi' => $id_lokasi,
              "tanggal_diterima" => date('Y-m-d'),
            )
          );

        }
      }

      
      if(!empty($list_id_e)){
        $list_id_e = explode(",",$list_id_e);
        foreach($list_id_e as $key => $value_x){
          array_push(
            $arr_barang,
            array(
              "id_ruang" => $id_ruang,
              "kode_jenis" => '05', // index array menggunkan "." tidak mau
              "id_kib" => $value_x,
              'id_skpd' => $id_skpd,
              "status_diterima" => '1',
              'id_kode_lokasi' => $id_lokasi,
              "tanggal_diterima" => date('Y-m-d'),
            )
          );

        }
      }

      
      $this->pengajuan_model->insert_barang($arr_barang); //insert ke tbl_mutasi_barang


      $this->global_model->_logs($menu = 'master_ruang', $sub_menu = 'pengajuan', $tabel_name = 'tbl_ruang_barang', $action_id = null, $action = 'insert', $data = $arr_barang, $feature = 'create_action');
      //($menu='location', $sub_menu='set_location', $tabel_name='tbl_kode_location', $action_id=$session->id_user, $action='set', $data=$data, $feature);

      $this->db->trans_complete();
      $this->session->set_flashdata('message', 'Berhasil Insert Barang.');
      redirect(base_url('master_ruang/pengajuan'));
    }
  }

  public function delete($id)
  {
    
    $id = decrypt_url($id);
    $row = $this->pengajuan_model->get_by_id($id);

    if ($row) {
      $this->pengajuan_model->delete_mutasi_barang($id);
      $this->pengajuan_model->delete($id);
      $this->global_model->_logs($menu = 'mutasi', $sub_menu = 'pengajuan', $tabel_name = 'tbl_mutasi', $action_id = $id, $action = 'delete', $data = $row, $feature = 'delete');
      $this->global_model->_logs($menu = 'mutasi', $sub_menu = 'pengajuan', $tabel_name = 'tbl_mutasi_barang', $action_id = $id, $action = 'delete', $data = $row, $feature = 'delete');
      //($menu='location', $sub_menu='set_location', $tabel_name='tbl_kode_location', $action_id=$session->id_user, $action='set', $data=$data, $feature);
      $this->session->set_flashdata('message', 'Berhasil menghapus pengajuan mutasi');
      redirect(base_url('mutasi/pengajuan'));
    } else {
      $this->session->set_flashdata('message', 'Record Not Found');
      redirect(base_url('mutasi/pengajuan'));
    }
  }


  public function get_lokasi()
  {
    // $lokasi = $this->pengajuan_model->get_lokasi($this->input->post('id_pengguna'));
    $lokasi = $this->pengajuan_model->get_lokasi($this->input->post('id_kode_lokasi'));

    $data['option'] = '<option value="">Silahkan Pilih</option>';
    foreach ($lokasi as $key => $value) {
      $data['option'] .= '<option  value="' . $value['id_kode_lokasi'] . '" selected>' . $value['kode_lokasi'] . ' - ' . $value['instansi'] . '</option>';
    }
    echo json_encode($data);
  }
  public function get_lokasi_ruang()
  {
    // $lokasi = $this->pengajuan_model->get_lokasi($this->input->post('id_pengguna'));
    $lokasi = $this->pengajuan_model->get_lokasi_ruang($this->input->post('id_kuasa_pengguna'));

    $data['option'] = '<option value="">Silahkan Pilih</option>';
    foreach ($lokasi as $key => $value) {
      $data['option'] .= '<option  value="' . $value['id_ruang'] . '" selected>' . $value['nama_gedung'] . ' - ' . $value['lantai'] . ' - ' . $value['nama_ruang'] . ' (' . $value['deskripsi']. ')'.'</option>';
    }
    echo json_encode($data);
  }


  public function _rules()
  {
    //$this->form_validation->set_rules('tanggal', 'tanggal', 'trim|required');
    //$this->form_validation->set_rules('kode_lokasi', 'kode lokasi tujuan', 'trim|required');
    $this->form_validation->set_rules('id_ruang', 'Ruang', 'trim');
    $this->form_validation->set_error_delimiters('<span class="text-danger">', '</span>');
    $this->form_validation->set_message('required', 'Objek %s harus di isi.');
  }
}
