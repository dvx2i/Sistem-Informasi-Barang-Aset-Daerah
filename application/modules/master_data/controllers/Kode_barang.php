<?php

if (!defined('BASEPATH'))
  exit('No direct script access allowed');

class Kode_barang extends CI_Controller
{
  function __construct()
  {
    parent::__construct();
    $this->global_model->cek_hak_akses('2');
    $this->load->model('Kode_barang_model');
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
    $data['content'] = $this->load->view('kode_barang/tbl_kode_barang_list', '', true);
    $data['breadcrumb'] = array(
      array('label' => 'Administrator', 'url' => '#', 'icon' => 'fa fa-pie-chart', 'li_class' => ''),
      array('label' => 'Kode Barang', 'url' => '#', 'icon' => '', 'li_class' => 'active'),
      // array('label' => 'Buat', 'url' => '#','icon'=>'', 'li_class'=>'active'),
    );
    $this->load->view('template', $data);
  }

  public function json()
  {
    header('Content-Type: application/json');
    echo $this->Kode_barang_model->json();
  }

  public function create()
  {
    $data = array(
      'button' => 'Buat',
      'action' => base_url('master_data/kode_barang/create_action'),
      'id_kode_barang' => set_value('id_kode_barang'),
      'kode_barang' => set_value('kode_barang'),
      'nama_barang' => set_value('nama_barang'),
      'kode_akun' => set_value('kode_akun'),
      'kode_kelompok' => set_value('kode_kelompok'),
      'kode_jenis' => set_value('kode_jenis'),
      'kode_objek' => set_value('kode_objek'),
      'kode_rincian_objek' => set_value('kode_rincian_objek'),
      'kode_sub_rincian_objek' => set_value('kode_sub_rincian_objek'),
      'kode_sub_sub_rincian_objek' => set_value('kode_sub_sub_rincian_objek'),
      'umur_ekonomis' => set_value('umur_ekonomis'),
      'nilai_residu' => set_value('nilai_residu'),
      'kelompok_manfaat' => set_value('kelompok_manfaat'),
    );
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

    $data['kode'] = $this->Kode_barang_model->get_kode();
    $data['list_master_kapitalisasi'] = $this->Kode_barang_model->get_master_kapitalisasi();
    $data['content'] = $this->load->view('kode_barang/tbl_kode_barang_form', $data, true);
    $data['breadcrumb'] = array(
      array('label' => 'Administrator', 'url' => '#', 'icon' => 'fa fa-pie-chart', 'li_class' => ''),
      array('label' => 'Kode Barang', 'url' => '#', 'icon' => '', 'li_class' => ''),
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
        'kode_barang' => $this->input->post('kode_barang', TRUE),
        'nama_barang_simbada' => $this->input->post('nama_barang', TRUE),
        'nama_barang' => $this->input->post('nama_barang', TRUE),
        'kode_akun' => $this->input->post('kode_akun', TRUE),
        'kode_kelompok' => $this->input->post('kode_kelompok', TRUE),
        'kode_jenis' => $this->input->post('kode_jenis', TRUE),
        'kode_objek' => $this->input->post('kode_objek', TRUE),
        'kode_rincian_objek' => $this->input->post('kode_rincian_objek', TRUE),
        'kode_sub_rincian_objek' => $this->input->post('kode_sub_rincian_objek', TRUE),
        'kode_sub_sub_rincian_objek' => $this->input->post('kode_sub_sub_rincian_objek', TRUE),
        'umur_ekonomis' => $this->input->post('umur_ekonomis', TRUE),
        'nilai_residu' => $this->input->post('nilai_residu', TRUE),
        'kelompok_manfaat' => $this->input->post('kelompok_manfaat', TRUE),
      );

      $this->Kode_barang_model->insert($data);
      $this->global_model->_logs($menu = 'master_data', $sub_menu = 'kode_barang', $tabel_name = 'tbl_kode_barang', $action_id = null, $action = 'insert', $data = $data);
      $this->session->set_flashdata('message', 'Berhasil Menambah Data');
      redirect(base_url('master_data/kode_barang'));
    }
  }

  public function update($id)
  {
    $row = $this->Kode_barang_model->get_by_id($id);

    if ($row) {
      $data = array(
        'button' => 'Perbarui',
        'action' => base_url('master_data/kode_barang/update_action'),
        'id_kode_barang' => set_value('id_kode_barang', $row->id_kode_barang),
        'kode_barang' => set_value('kode_barang', $row->kode_barang),
        'nama_barang' => set_value('nama_barang', $row->nama_barang_simbada),
        'kode_akun' => set_value('kode_akun', $row->kode_akun),
        'kode_kelompok' => set_value('kode_kelompok', $row->kode_kelompok),
        'kode_jenis' => set_value('kode_jenis', $row->kode_jenis),
        'kode_objek' => set_value('kode_objek', $row->kode_objek),
        'kode_rincian_objek' => set_value('kode_rincian_objek', $row->kode_rincian_objek),
        'kode_sub_rincian_objek' => set_value('kode_sub_rincian_objek', $row->kode_sub_rincian_objek),
        'kode_sub_sub_rincian_objek' => set_value('kode_sub_sub_rincian_objek', $row->kode_sub_sub_rincian_objek),
        'umur_ekonomis' => set_value('umur_ekonomis', $row->umur_ekonomis),
        'nilai_residu' => set_value('nilai_residu', $row->nilai_residu),
        'kelompok_manfaat' => set_value('kelompok_manfaat', $row->kelompok_manfaat),
      );

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
      $data['kode'] = $this->Kode_barang_model->get_kode();
      $data['list_master_kapitalisasi'] = $this->Kode_barang_model->get_master_kapitalisasi();
      $data['content'] = $this->load->view('kode_barang/tbl_kode_barang_form', $data, true);
      $data['breadcrumb'] = array(
        array('label' => 'Administrator', 'url' => '#', 'icon' => 'fa fa-pie-chart', 'li_class' => ''),
        array('label' => 'Kode Barang', 'url' => '#', 'icon' => '', 'li_class' => ''),
        array('label' => 'Perbarui', 'url' => '#', 'icon' => '', 'li_class' => 'active'),
      );
      $this->load->view('template', $data);
    } else {
      $this->session->set_flashdata('message', 'Record Not Found');
      redirect(base_url('master_data/kode_barang'));
    }
  }

  public function update_action()
  {
    $this->_rules();

    if ($this->form_validation->run() == FALSE) {
      $this->update($this->input->post('id_kode_barang', TRUE));
    } else {
      $data = array(
        'kode_barang' => $this->input->post('kode_barang', TRUE),
        'nama_barang_simbada' => $this->input->post('nama_barang', TRUE),
        'kode_akun' => $this->input->post('kode_akun', TRUE),
        'kode_kelompok' => $this->input->post('kode_kelompok', TRUE),
        'kode_jenis' => $this->input->post('kode_jenis', TRUE),
        'kode_objek' => $this->input->post('kode_objek', TRUE),
        'kode_rincian_objek' => $this->input->post('kode_rincian_objek', TRUE),
        'kode_sub_rincian_objek' => $this->input->post('kode_sub_rincian_objek', TRUE),
        'kode_sub_sub_rincian_objek' => $this->input->post('kode_sub_sub_rincian_objek', TRUE),
        'umur_ekonomis' => $this->input->post('umur_ekonomis', TRUE),
        'nilai_residu' => $this->input->post('nilai_residu', TRUE),
        'kelompok_manfaat' => $this->input->post('kelompok_manfaat', TRUE),
      );
      // die(json_encode($data));
      $this->Kode_barang_model->update($this->input->post('id_kode_barang', TRUE), $data);
      $this->global_model->_logs($menu = 'master_data', $sub_menu = 'kode_barang', $tabel_name = 'tbl_kode_barang', $action_id = $this->input->post('id_kode_barang', TRUE), $action = 'update', $data = $data);
      $this->session->set_flashdata('message', 'Berhasil Memperbarui');
      redirect(base_url('master_data/kode_barang'));
    }
  }

  public function delete($id)
  {
    $row = $this->Kode_barang_model->get_by_id($id);

    if ($row) {
      $this->Kode_barang_model->delete($id);
      $this->global_model->_logs($menu = 'master_data', $sub_menu = 'kode_barang', $tabel_name = 'tbl_kode_barang', $action_id = $id, $action = 'delete', $data = $data);
      $this->session->set_flashdata('message', 'Berhasil menghapus');
      redirect(base_url('master_data/kode_barang'));
    } else {
      $this->session->set_flashdata('message', 'Record Not Found');
      redirect(base_url('master_data/kode_barang'));
    }
  }

  public function _rules()
  {
    $this->form_validation->set_rules('kode_barang', 'kode barang', 'trim|required');
    $this->form_validation->set_rules('nama_barang', 'nama_barang', 'trim|required');
    $this->form_validation->set_rules('kode_akun', 'kode_akun', 'trim|required');
    $this->form_validation->set_rules('kode_kelompok', 'kode_kelompok', 'trim|required');
    $this->form_validation->set_rules('kode_jenis', 'kode_jenis', 'trim|required');
    $this->form_validation->set_rules('kode_objek', 'kode_objek', 'trim|required');
    $this->form_validation->set_rules('kode_rincian_objek', 'kode_rincian objek', 'trim|required');
    $this->form_validation->set_rules('kode_sub_rincian_objek', 'kode_sub rincian objek', 'trim|required');
    // $this->form_validation->set_rules('kode_sub_sub_rincian_objek', 'kode_sub sub rincian objek', 'trim|required');
    $this->form_validation->set_rules('umur_ekonomis', 'umur ekonomis', 'trim|required');
    $this->form_validation->set_rules('nilai_residu', 'nilai residu', 'trim|required');
    $this->form_validation->set_rules('kelompok_manfaat', 'kelompok_manfaat', 'trim|required');

    $this->form_validation->set_rules('id_kode_barang', 'id_kode_barang', 'trim');
    $this->form_validation->set_error_delimiters('<span class="text-danger">', '</span>');
  }


  function get_jenis()
  {
    $kode_kelompok = $this->input->post("kode_kelompok");

    $jenis = $this->Kode_barang_model->get_jenis($kode_kelompok);

    $data['option'] = '<option value="">==Silahkan Pilih==</option>';
    foreach ($jenis as $key => $value) {
      $data['option'] .= '<option  value="' . $value['kode_jenis'] . '">' . $value['kode_jenis'] . ' - ' . $value['nama_barang'] . '</option>';
    }
    echo json_encode($data);
  }

  function get_objek()
  {
    $kode_kelompok = $this->input->post("kode_kelompok");
    $kode_jenis = $this->input->post("kode_jenis");

    $lis_objek = $this->Kode_barang_model->get_objek($kode_kelompok, $kode_jenis);

    $data['option'] = '<option value="">==Silahkan Pilih==</option>';
    foreach ($lis_objek as $key => $value) {
      $data['option'] .= '<option  value="' . $value['kode_objek'] . '">' . $value['kode_objek'] . ' - ' . $value['nama_barang'] . '</option>';
    }
    echo json_encode($data);
  }

  function get_rincian_objek()
  {
    $kode_kelompok = $this->input->post("kode_kelompok");
    $kode_jenis = $this->input->post("kode_jenis");
    $kode_objek = $this->input->post("kode_objek");

    $lis_rincian_objek = $this->Kode_barang_model->get_rincian_objek($kode_kelompok, $kode_jenis, $kode_objek);

    $data['option'] = '<option value="">==Silahkan Pilih==</option>';
    foreach ($lis_rincian_objek as $key => $value) {
      $data['option'] .= '<option  value="' . $value['kode_rincian_objek'] . '">' . $value['kode_rincian_objek'] . ' - ' . $value['nama_barang'] . '</option>';
    }
    echo json_encode($data);
  }

  function get_sub_rincian_objek()
  {
    $kode_kelompok = $this->input->post("kode_kelompok");
    $kode_jenis = $this->input->post("kode_jenis");
    $kode_objek = $this->input->post("kode_objek");
    $kode_rincian_objek = $this->input->post("kode_rincian_objek");

    $lis_sub_rincian_objek = $this->Kode_barang_model->get_sub_rincian_objek($kode_kelompok, $kode_jenis, $kode_objek, $kode_rincian_objek);

    $data['option'] = '<option value="">==Silahkan Pilih==</option>';
    foreach ($lis_sub_rincian_objek as $key => $value) {
      $data['option'] .= '<option  value="' . $value['kode_sub_rincian_objek'] . '">' . $value['kode_sub_rincian_objek'] . ' - ' . $value['nama_barang'] . '</option>';
    }
    echo json_encode($data);
  }

  function get_sub_sub_rincian_objek()
  {
    $kode_kelompok = $this->input->post("kode_kelompok");
    $kode_jenis = $this->input->post("kode_jenis");
    $kode_objek = $this->input->post("kode_objek");
    $kode_rincian_objek = $this->input->post("kode_rincian_objek");
    $kode_sub_rincian_objek = $this->input->post("kode_sub_rincian_objek");

    // $lis_sub_rincian_objek = $this->Kode_barang_model->get_sub_rincian_objek($kode_kelompok, $kode_jenis, $kode_objek, $kode_rincian_objek);

    // $data['option'] = '<option value="">==Silahkan Pilih==</option>';
    // foreach ($lis_sub_rincian_objek as $key => $value) {
    //   $data['option'] .= '<option  value="' . $value['kode_sub_rincian_objek'] . '">' . $value['kode_sub_rincian_objek'] . ' - ' . $value['nama_barang'] . '</option>';
    // }
    // echo json_encode($data);
    $next_kode_sub_sub_rincian_objek = $this->Kode_barang_model->get_kode_sub_sub_rincian_objek($kode_kelompok, $kode_jenis, $kode_objek, $kode_rincian_objek, $kode_sub_rincian_objek);
    echo json_encode($next_kode_sub_sub_rincian_objek);
  }
}

/* End of file Kode_barang.php */
/* Location: ./application/controllers/Kode_barang.php */
/* Please DO NOT modify this information : */
/* Generated by Harviacode Codeigniter CRUD Generator 2018-09-19 06:22:01 */
/* http://harviacode.com */
