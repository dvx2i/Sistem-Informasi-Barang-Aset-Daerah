<?php

if (!defined('BASEPATH')) exit('No direct script access allowed');

class List_data extends MX_Controller
{
  function __construct()
  {
    parent::__construct();
    $this->global_model->cek_hak_akses('62'); //list kapitalisasi
    $this->load->model('list_model');
    $this->load->model('global_kapitalisasi_model');
    $this->load->library('form_validation');
    $this->load->library('datatables');
    $this->load->helper('my_global');
  }

  public function index()
  {
    $data['css'] = array(
      'assets/datatables/dataTables.bootstrap.css',
      'assets/sweetalert/sweetalert2.css',
    );

    $data['js'] = array(
      'assets/datatables/jquery.dataTables.js',
      'assets/datatables/dataTables.bootstrap.js',
      'assets/sweetalert/sweetalert2.min.js',
    );

    $data['content'] = $this->load->view('list/list', $data, true);
    $data['breadcrumb'] = array(
      array('label' => 'Kapitalisasi', 'url' => '#', 'icon' => 'fa fa-pie-chart', 'li_class' => ''),
      array('label' => 'Validasi', 'url' => '#', 'icon' => '', 'li_class' => 'active'),
      // array('label' => 'Detail', 'url' => '#','icon'=>'', 'li_class'=>'active'),
    );
    $this->load->view('template', $data);
  }


  public function json()
  {
    header('Content-Type: application/json');
    echo $this->list_model->json();
  }

  public function update($id_kapitalisasi)
  {
    $id_kapitalisasi = decrypt_url($id_kapitalisasi);
    $row = $this->list_model->get_by_id($id_kapitalisasi);

    if ($row) {
      $data = array(
        'button' => 'Update',
        'action' => base_url('kapitalisasi/list_data/update_action/' . encrypt_url($id_kapitalisasi)),

        'tanggal_pengajuan' => set_value('tanggal_pengajuan', tgl_indo($row->tanggal_pengajuan)),
        'nilai_kapitalisasi' => set_value('nilai_kapitalisasi', $row->nilai_kapitalisasi),
        'sumber_dana' => set_value('sumber_dana', $row->id_sumber_dana_kapitalisasi),
        'rekening' => set_value('rekening', $row->id_rekening_kapitalisasi),
        'jenis' => $row->jenis,
        'umur_ekonomis' => $row->penambahan_umur_ekonomis,
        'kode_jenis' => $row->kode_jenis
      );

      $kode_jenis = $row->kode_jenis;
      $id_kib     = $row->id_kib;

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

      //sumber dana
      $data['lis_sumber_dana'] = $this->global_model->get_sumber_dana();
      //kode rekening
      $data['lis_rekening'] = $this->global_model->get_rekening($data['sumber_dana']);

      // $data['lokasi'] = $this->global_model->get_all_lokasi();

      $data['content'] = $this->load->view('list/form_kapitalisasi', $data, true);
      $data['breadcrumb'] = array(
        array('label' => 'Kapitalisasi', 'url' => '#', 'icon' => 'fa fa-pie-chart', 'li_class' => ''),
        array('label' => 'List Data', 'url' => '#', 'icon' => '', 'li_class' => ''),
        array('label' => 'Ubah', 'url' => '#', 'icon' => '', 'li_class' => 'active'),
      );
      $this->load->view('template', $data);
    } else {
      $this->session->set_flashdata('message', 'Data tidak ditemukan');
      redirect(base_url('kapitalisasi/list_data'));
    }
  }

  public function detail($id_kapitalisasi)
  {
    $id_kapitalisasi = decrypt_url($id_kapitalisasi);
    $row = $this->list_model->get_by_id($id_kapitalisasi);

    if ($row) {
      $data = array(
        'button' => 'Detail',
        'action' => base_url('kapitalisasi/list_data/update_action/' . encrypt_url($id_kapitalisasi)),

        'tanggal_pengajuan' => set_value('tanggal_pengajuan', tgl_indo($row->tanggal_pengajuan)),
        'nilai_kapitalisasi' => set_value('nilai_kapitalisasi', $row->nilai_kapitalisasi),
        'sumber_dana' => set_value('sumber_dana', $row->id_sumber_dana_kapitalisasi),
        'rekening' => set_value('rekening', $row->id_rekening_kapitalisasi),
        'jenis' => $row->jenis,
        'umur_ekonomis' => $row->penambahan_umur_ekonomis,
        'kode_jenis' => $row->kode_jenis
      );

      $kode_jenis = $row->kode_jenis;
      $id_kib     = $row->id_kib;

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

      //sumber dana
      $data['lis_sumber_dana'] = $this->global_model->get_sumber_dana();
      //kode rekening
      $data['lis_rekening'] = $this->global_model->get_rekening($data['sumber_dana']);

      // $data['lokasi'] = $this->global_model->get_all_lokasi();

      $data['content'] = $this->load->view('list/form_kapitalisasi', $data, true);
      $data['breadcrumb'] = array(
        array('label' => 'Kapitalisasi', 'url' => '#', 'icon' => 'fa fa-pie-chart', 'li_class' => ''),
        array('label' => 'List Data', 'url' => '#', 'icon' => '', 'li_class' => ''),
        array('label' => 'Ubah', 'url' => '#', 'icon' => '', 'li_class' => 'active'),
      );
      $this->load->view('template', $data);
    } else {
      $this->session->set_flashdata('message', 'Data tidak ditemukan');
      redirect(base_url('kapitalisasi/list_data'));
    }
  }

  public function update_action($id_kapitalisasi)
  {

    $this->_rules();

    if ($this->form_validation->run() == FALSE) {
      $this->update($id_kapitalisasi);
    } else {
      $id_kapitalisasi = decrypt_url($id_kapitalisasi);
      $row = $this->list_model->get_by_id($id_kapitalisasi);

      $tgl_pengajuan = date('Y-m-d', tgl_inter($this->input->post('tanggal_pengajuan', TRUE)));

      $bulan = date('n', strtotime($tgl_pengajuan));
      $tahun = date('Y', strtotime($tgl_pengajuan));

      $locked = $this->global_model->get_locked($bulan, $tahun, $row->id_kode_lokasi);

      if ($locked < 1) {

        $jenis = '';
        $nilai_kapitalisasi = 0;
        if ($this->input->post('nilai_kapitalisasi')) {
          $jenis = 'kapitalisasi';
          $status_histori = $jenis;
          $nilai_kapitalisasi = $this->input->post('nilai_kapitalisasi');
          // die($nilai_kapaitalisasi);
        } else if ($this->input->post('koreksi_tambah')) {
          $jenis = 'koreksi_tambah';
          $status_histori = $jenis;
          $nilai_kapitalisasi = $this->input->post('koreksi_tambah');
        } else if ($this->input->post('koreksi_kurang')) {
          $jenis = 'koreksi_kurang';
          $status_histori = $jenis;
          $nilai_kapitalisasi = $this->input->post('koreksi_kurang');
        }
        // print_r($_POST); die;

        // $harga_awal = $this->pengajuan_model->get_harga_awal($row->kode_jenis, $row->id_kib);

        $nilai_kapitalisasi = str_replace('.', '', $nilai_kapitalisasi);
        $nilai_kapitalisasi = str_replace('Rp', '', $nilai_kapitalisasi);
        $nilai_kapitalisasi = str_replace(',', '.', $nilai_kapitalisasi);
        $umur_ekonomis = 0;

        // if($jenis = 'kapitalisasi')
        // {
        //   $umur_ekonomis = 
        // }
        $this->db->trans_start();

        $data_update = array(
          'jenis' => $jenis,
          'tanggal_pengajuan' => date('Y-m-d', tgl_inter($this->input->post('tanggal_pengajuan', TRUE))),
          'tanggal_kapitalisasi' => date('Y-m-d', tgl_inter($this->input->post('tanggal_pengajuan', TRUE))),
          // 'nilai_kapitalisasi' => $nilai_kapitalisasi,
          // 'penambahan_umur_ekonomis' => $umur_ekonomis,
          'id_sumber_dana_kapitalisasi' => $this->input->post('sumber_dana', TRUE),
          'id_rekening_kapitalisasi' => $this->input->post('rekening', TRUE),
        );
        // die(json_encode($data_reklas));
        $id = $this->list_model->update($id_kapitalisasi, $data_update);
        $this->global_model->_logs($menu = 'kapitalisasi', $sub_menu = 'list_data', $tabel_name = 'tbl_kapitalisasi', $action_id = null, $action = 'update', $data = $data_update, $feature = 'update_action');

        $data_histori = array(
          'tanggal_histori' => date('Y-m-d', tgl_inter($this->input->post('tanggal_pengajuan', TRUE)))
        );

        $this->global_model->update_histori_barang($row->kode_jenis, $row->id_kib, $row->tanggal_pengajuan, $status_histori, $data_histori);

        $this->db->trans_complete();

        $this->session->set_flashdata('message', 'Berhasil dismpan.');
        redirect(base_url('kapitalisasi/list_data'));
      } else {
        echo json_encode(array('status' => FALSE, 'message' => 'Stock Opname Bulan ' . bulan_indo($bulan) . ' Tahun ' . $tahun . ' Telah Dikunci'));
      }
    }
  }

  public function delete($id)
  {
    $id = decrypt_url($id);
    $row = $this->list_model->get_by_id($id);
    if ($row) {
      $tgl_pengajuan = $row->tanggal_pengajuan;

      $bulan = date('n', strtotime($tgl_pengajuan));
      $tahun = date('Y', strtotime($tgl_pengajuan));

      $locked = $this->global_model->get_locked($bulan, $tahun, $row->id_kode_lokasi);

      if ($locked < 1) {
        $this->db->trans_start();
        $kode_jenis = $row->kode_jenis;
        $id_kib     = $row->id_kib;

        $kib = $this->global_model->get_kib($kode_jenis, $id_kib);

        if ($kode_jenis != '06') { // jika bukan kib f

          if ($row->jenis == 'koreksi_kurang') {
            $harga = $kib->harga + $row->nilai_kapitalisasi;
          } else {
            $harga = $kib->harga - $row->nilai_kapitalisasi;
          }


          $data = array(
            'harga' => $harga,
          );
        } else {
          
          if ($row->jenis == 'koreksi_kurang') {
            $harga = $kib->nilai_kontrak + $row->nilai_kapitalisasi;
          } else {
            $harga = $kib->nilai_kontrak - $row->nilai_kapitalisasi;
          }

          $data = array(
            'nilai_kontrak' => $harga,
          );
        }

        $this->global_model->update_kib($kode_jenis, $id_kib, $data);


        $this->global_model->delete_histori_barang($kode_jenis, $id_kib,$row->tanggal_pengajuan,$row->jenis);

        $this->list_model->delete($id);
        
        $this->global_model->_logs($menu = 'kapitalisasi', $sub_menu = 'pengajuan', $tabel_name = 'tbl_kapitalisasi', $action_id = $id, $action = 'delete', $data = $row, $feature = 'delete');
        //($menu='location', $sub_menu='set_location', $tabel_name='tbl_kode_location', $action_id=$session->id_user, $action='set', $data=$data, $feature);

        $this->db->trans_complete();
        $this->session->set_flashdata('message', 'Berhasil menghapus kapitalisasi.');
        redirect(base_url('kapitalisasi/pengajuan'));
      } else {
        echo json_encode(array('status' => FALSE, 'message' => 'Stock Opname Bulan ' . bulan_indo($bulan) . ' Tahun ' . $tahun . ' Telah Dikunci'));
      }
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
  /*
  public function validasi_action($id_kapitalisasi=null){
    $data_update = array(
      'tanggal_kapitalisasi' => date('Y-m-d'),
      'status' => '2',
      'updated_at' => date('Y-m-d H:i:s'),
    );
    $this->validasi_model->update($id_kapitalisasi,$data_update);
    $this->global_model->_logs($menu='kapitalisasi', $sub_menu='validasi', $tabel_name='tbl_kapitalisasi', $action_id=$id_kapitalisasi, $action='update', $data=$data_update, $feature='validasi_action');
    echo json_encode(array('status' => TRUE, ));
  }

  public function delete($id){
    $row = $this->validasi_model->get_by_id($id);
    if ($row and $row->status == 2) {
      $this->session->set_flashdata('message', 'Data tidak dapat dihapus.');
      redirect(base_url('kapitalisasi/validasi'));
    }
    else if ($row) {
      $this->validasi_model->delete($id);
      $this->global_model->_logs($menu='kapitalisasi', $sub_menu='validasi', $tabel_name='tbl_kapitalisasi', $action_id=$id, $action='delete', $data=$row, $feature='delete');
                              //($menu='location', $sub_menu='set_location', $tabel_name='tbl_kode_location', $action_id=$session->id_user, $action='set', $data=$data, $feature);
      $this->session->set_flashdata('message', 'Berhasil menghapus pengajuan.');
      redirect(base_url('kapitalisasi/validasi'));
    } else {
      $this->session->set_flashdata('message', 'Record Not Found');
      redirect(base_url('kapitalisasi/validasi'));
    }
  }
*/
}
