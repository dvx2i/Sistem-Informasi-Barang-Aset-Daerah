<?php

if (!defined('BASEPATH')) exit('No direct script access allowed');

class Validasi extends MX_Controller
{
  function __construct()
  {
    parent::__construct();
    $this->global_model->cek_hak_akses('61'); //validasi kapitalisasi
    $this->load->model('validasi_model');
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
      "assets/bootstrap/bootstrap-datepicker-master/dist/css/bootstrap-datepicker.min.css",
    );

    $data['js'] = array(
      'assets/datatables/jquery.dataTables.js',
      'assets/datatables/dataTables.bootstrap.js',
      'assets/sweetalert/sweetalert2.min.js',
      "assets/bootstrap/bootstrap-datepicker-master/dist/js/bootstrap-datepicker.min.js",
      "assets/bootstrap/bootstrap-datepicker-master/dist/locales/bootstrap-datepicker.id.min.js",
    );

    $data['content'] = $this->load->view('validasi/list', $data, true);
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
    echo $this->validasi_model->json();
  }

  public function validasi_action($id_kapitalisasi = null)
  {
    $row = $this->validasi_model->get_by_id($id_kapitalisasi);
    $tgl_pengajuan = date('Y-m-d', strtotime($row->tanggal_pengajuan));
    $bulan = date('n', strtotime($tgl_pengajuan));
    $tahun = date('Y', strtotime($tgl_pengajuan));
    // $tanggal_kapitalisasi = $tgl_pengajuan >= date('Y-m-d')?$tgl_pengajuan:date('Y-m-d');
    $tanggal_kapitalisasi = $tgl_pengajuan;

      $locked = $this->validasi_model->get_locked($bulan, $tahun, $row->id_kode_lokasi);
      if ($locked < 1) {
        
        $this->db->trans_start();

        $data_update = array(
          'tanggal_kapitalisasi' => $tanggal_kapitalisasi,
          'status' => '2',
          'updated_at' => date('Y-m-d H:i:s'),
        );
        $this->validasi_model->update($id_kapitalisasi, $data_update);

        if ($row->jenis == 'koreksi_tambah') {
          $data_kib = $this->global_model->get_kib($row->kode_jenis, $row->id_kib);
          $total = $data_kib->harga + $row->nilai_kapitalisasi;
          $this->global_model->update_kib($row->kode_jenis, $row->id_kib, array('harga' => $total, 'id_sumber_dana_kapitalisasi' => $row->id_sumber_dana_kapitalisasi, 'id_rekening_kapitalisasi' => $row->id_rekening_kapitalisasi));
          $this->global_model->insert_histori_kib($row->kode_jenis, $row->id_kib, 'koreksi_tambah');
          $this->validasi_model->set_histori_barang('koreksi_tambah', $row->id_kode_lokasi, $row->kode_jenis, $row->id_kib,$tgl_pengajuan);
        } else if ($row->jenis == 'koreksi_kurang') {
          $data_kib = $this->global_model->get_kib($row->kode_jenis, $row->id_kib);
          $total = $data_kib->harga - $row->nilai_kapitalisasi;
          $this->global_model->update_kib($row->kode_jenis, $row->id_kib, array('harga' => $total, 'id_sumber_dana_kapitalisasi' => $row->id_sumber_dana_kapitalisasi, 'id_rekening_kapitalisasi' => $row->id_rekening_kapitalisasi));
          $this->global_model->insert_histori_kib($row->kode_jenis, $row->id_kib, 'koreksi_kurang');
          $this->validasi_model->set_histori_barang('koreksi_kurang', $row->id_kode_lokasi, $row->kode_jenis, $row->id_kib,$tgl_pengajuan);
        } else {
          $data_kib = $this->global_model->get_kib($row->kode_jenis, $row->id_kib);
          $total = $data_kib->harga + $row->nilai_kapitalisasi;
          $umur_ekonomis = $data_kib->umur_ekonomis+$row->penambahan_umur_ekonomis;
          $this->global_model->update_kib($row->kode_jenis, $row->id_kib, array('harga' => $total, 'id_sumber_dana_kapitalisasi' => $row->id_sumber_dana_kapitalisasi, 'id_rekening_kapitalisasi' => $row->id_rekening_kapitalisasi, 'umur_ekonomis' => $umur_ekonomis));
          $this->global_model->insert_histori_kib($row->kode_jenis, $row->id_kib, 'kapitalisasi');
          $this->validasi_model->set_histori_barang('koreksi_tambah', $row->id_kode_lokasi, $row->kode_jenis, $row->id_kib,$tgl_pengajuan);
        }


        $this->global_model->_logs($menu = 'kapitalisasi', $sub_menu = 'validasi', $tabel_name = 'tbl_kapitalisasi', $action_id = $id_kapitalisasi, $action = 'update', $data = $data_update, $feature = 'validasi_action');
        
        $this->db->trans_complete();
        
        echo json_encode(array('status' => TRUE,));
      } else {
        echo json_encode(array('status' => FALSE, 'message' => 'Stock Opname Bulan ' . bulan_indo($bulan) . ' Tahun ' . $tahun . ' Telah Dikunci'));
      }
  }

  public function delete($id)
  {
    $row = $this->validasi_model->get_by_id($id);
    if ($row and $row->status == 2) {
      $this->session->set_flashdata('message', 'Data tidak dapat dihapus.');
      redirect(base_url('kapitalisasi/validasi'));
    } else if ($row) {
      $this->validasi_model->delete($id);
      $this->global_model->_logs($menu = 'kapitalisasi', $sub_menu = 'validasi', $tabel_name = 'tbl_kapitalisasi', $action_id = $id, $action = 'delete', $data = $row, $feature = 'delete');
      //($menu='location', $sub_menu='set_location', $tabel_name='tbl_kode_location', $action_id=$session->id_user, $action='set', $data=$data, $feature);
      $this->session->set_flashdata('message', 'Berhasil menghapus pengajuan.');
      redirect(base_url('kapitalisasi/validasi'));
    } else {
      $this->session->set_flashdata('message', 'Record Not Found');
      redirect(base_url('kapitalisasi/validasi'));
    }
  }
}
