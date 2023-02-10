<?php

if (!defined('BASEPATH')) exit('No direct script access allowed');

class Validasi extends MX_Controller
{
  protected $kode_jenis = '03';

  function __construct()
  {
    parent::__construct();
    $this->global_model->cek_hak_akses('14');
    $this->load->model('Validasi_model');
    $this->load->library('form_validation');
    $this->load->library('datatables');
    $this->load->helper('my_global');
    $this->load->library('Global_library');
    $this->form_validation->CI = &$this;
  }

  public function index()
  {
    $data['css'] = array(
      'assets/datatables/dataTables.bootstrap.css',
      'assets/css/kib.css',
      'assets/sweetalert/sweetalert.css',
      'assets/adminlte/plugins/iCheck/all.css'
    );

    $data['js'] = array(
      'assets/datatables/jquery.dataTables.js',
      'assets/datatables/dataTables.bootstrap.js',
      'assets/sweetalert/sweetalert.min.js',
      'assets/adminlte/plugins/iCheck/icheck.min.js'
    );

    $data['kode_jenis'] = $this->kode_jenis;
    $data['menu'] = $this->global_model->kode_jenis[$this->kode_jenis];

    // $jumlah_data = $this->Validasi_model->jumlah_data();
    $this->load->library('pagination');
    $config['base_url'] = base_url() . 'kib_c/validasi/index/';
    // $config['total_rows'] = $jumlah_data;
    $config['per_page'] = 10;
    $from = $this->uri->segment(4);
    $this->pagination->initialize($config);
    $data['skpd'] = $this->Validasi_model->data($config['per_page'], $from);
    $data["content"] = $this->load->view('kib_c/validasi_skpd', $data, TRUE);
    $data['breadcrumb'] = array(
      array('label' => 'Validasi', 'url' => '#', 'icon' => 'fa fa-pie-chart', 'li_class' => ''),
      array('label' => 'KIB C', 'url' => '#', 'icon' => '', 'li_class' => 'active'),
    );
    $this->load->view('template', $data);
  }

  public function detail($id_kode_lokasi)
  {
    $data['css'] = array(
      'assets/datatables/dataTables.min.css',
      "assets/bootstrap/bootstrap-datepicker-master/dist/css/bootstrap-datepicker.min.css",
      'assets/sweetalert/sweetalert2.css',
      'assets/css/kib.css',
      'assets/adminlte/plugins/iCheck/all.css'
    );

    $data['js'] = array(
      // 'assets/datatables/jquery.dataTables.js',
      // 'assets/datatables/dataTables.bootstrap.js',
      'assets/sweetalert/sweetalert2.min.js',
      "assets/bootstrap/bootstrap-datepicker-master/dist/js/bootstrap-datepicker.min.js",
      "assets/bootstrap/bootstrap-datepicker-master/dist/locales/bootstrap-datepicker.id.min.js",
      'assets/adminlte/plugins/iCheck/icheck.min.js'
    );
    $session = $this->session->userdata('session');
    // die(json_encode(substr($session->kode_lokasi, -1)));
    $data['show_lokasi'] = false;
    if (substr($session->kode_lokasi, -1) == "*")
      $data['show_lokasi'] = true;
    $data['kode_jenis'] = $this->kode_jenis;
    $data['menu'] = $this->global_model->kode_jenis[$this->kode_jenis];

    // $this->global_model->set_location($id_kode_lokasi);
    $data['id_kode_lokasi'] = $id_kode_lokasi;

    $data["content"] = $this->load->view('kib_c/validasi', $data, TRUE);
    $data['breadcrumb'] = array(
      array('label' => 'Validasi', 'url' => '#', 'icon' => 'fa fa-pie-chart', 'li_class' => ''),
      array('label' => 'KIB C', 'url' => '#', 'icon' => '', 'li_class' => ''),
      array('label' => 'Detail', 'url' => '#', 'icon' => '', 'li_class' => 'active'),
    );
    $this->load->view('template', $data);
  }


  public function json($id_kode_lokasi)
  {
    header('Content-Type: application/json');
    echo $this->Validasi_model->json($id_kode_lokasi);
  }

  public function read($id)
  {
    $row = $this->Validasi_model->get_by_id($id);
    // bila sudah di validasi dan bukan super admin
    if ($row) {
      // $kode_barang = $this->global_library->get_kode_barang($row->kode_barang);
      // $kode_lokasi=$this->global_library->get_kode_lokasi($row->kode_lokasi);
      $kode_barang = $this->global_model->get_kode_barang_by_id($row->id_kode_barang);
      $kode_lokasi = $this->global_model->get_kode_lokasi_by_id($row->id_kode_lokasi);
      $data = array(
        'button' => 'Detail',
        'menu' => $this->global_model->kode_jenis[$this->kode_jenis]['nama'],
        'action' => base_url('kib_c/update_action'),
        'id_kib_c' => set_value('id_kib_c', $row->id_kib_c),
        'sumber_dana' => set_value('sumber_dana', $row->id_sumber_dana),
        'rekening' => set_value('rekening', $row->id_rekening),
        'nama_barang' => set_value('nama_barang', $row->nama_barang),
        'kode_barang' => set_value('kode_barang', $row->kode_barang),
        // 'nomor_register' => set_value('nomor_register', $row->nomor_register),
        'kondisi_bangunan' => set_value('kondisi_bangunan', $row->kondisi_bangunan),
        'bangunan_bertingkat' => set_value('bangunan_bertingkat', $row->bangunan_bertingkat),
        'bangunan_beton' => set_value('bangunan_beton', $row->bangunan_beton),
        'luas_lantai_m2' => set_value('luas_lantai_m2', $row->luas_lantai_m2),
        'lokasi_alamat' => set_value('lokasi_alamat', $row->lokasi_alamat),
        'gedung_tanggal' => set_value('gedung_tanggal', tgl_indo($row->gedung_tanggal)),
        'gedung_nomor' => set_value('gedung_nomor', $row->gedung_nomor),
        'luas_m2' => set_value('luas_m2', $row->luas_m2),
        'status' => set_value('status', $row->status),
        'nomor_kode_tanah' => set_value('nomor_kode_tanah', $row->nomor_kode_tanah),
        'asal_usul' => set_value('asal_usul', $row->asal_usul),
        'harga' => set_value('harga', $row->harga),
        'keterangan' => set_value('keterangan', $row->keterangan),
        'deskripsi' => set_value('deskripsi', $row->deskripsi),
        'satuan' => set_value('satuan', $row->satuan),
        'umur_ekonomis' => set_value('satuan', $row->umur_ekonomis),

        'kode_lokasi' => set_value('kode_lokasi', $row->kode_lokasi),
        /*'kode_objek' => set_value('kode_objek', $kode_barang['kode_objek']),
        'kode_rincian_objek' => set_value('kode_rincian_objek', $kode_barang['kode_rincian_objek']),
        'kode_sub_rincian_objek' => set_value('kode_sub_rincian_objek', $kode_barang['kode_sub_rincian_objek']),
        'kode_sub_sub_rincian_objek' => set_value('kode_sub_sub_rincian_objek', $kode_barang['kode_sub_sub_rincian_objek']),
        */
        'kode_objek' => set_value('kode_objek', $kode_barang->kode_objek),
        'kode_rincian_objek' => set_value('kode_rincian_objek', $kode_barang->kode_rincian_objek),
        'kode_sub_rincian_objek' => set_value('kode_sub_rincian_objek', $kode_barang->kode_sub_rincian_objek),
        'kode_sub_sub_rincian_objek' => set_value('kode_sub_sub_rincian_objek', $kode_barang->kode_sub_sub_rincian_objek),
        'status_pemilik' => set_value('status_pemilik', $row->id_pemilik),
        'nama_lokasi' => set_value('nama_lokasi', $kode_lokasi->instansi),
        'jumlah_barang' => set_value('jumlah_barang', 0),

        'tanggal_transaksi' => set_value('tanggal_transaksi', tgl_indo($row->tanggal_transaksi)),
        'nomor_transaksi' => set_value('nomor_transaksi', $row->nomor_transaksi),
        // 'tanggal_pembelian' => set_value('tanggal_pembelian', tgl_indo($row->tanggal_pembelian)),
        'tanggal_perolehan' => set_value('tanggal_perolehan', tgl_indo($row->tanggal_perolehan)),
        'kib_f' => set_value('kib_f', $row->kib_f),
        'latitute' => set_value('latitute', $row->latitute),
        'longitute' => set_value('longitute', $row->longitute),
        'validasi' => set_value('validasi', $row->validasi),
      );

      $data['css'] = array(
        "assets/bootstrap/bootstrap-datepicker-master/dist/css/bootstrap-datepicker.min.css",
        "assets/jquery/select2-4.0.6-rc.1/dist/css/select2.min.css",
        "assets/jquery/select2-bootstrap-theme-master/dist/select2-bootstrap.min.css",
        'assets/adminlte/plugins/iCheck/all.css',
        'assets/sweetalert/sweetalert2.css',
        "assets/css/kib.css",
      );
      $data['js'] = array(
        "assets/bootstrap/bootstrap-datepicker-master/dist/js/bootstrap-datepicker.min.js",
        "assets/bootstrap/bootstrap-datepicker-master/dist/locales/bootstrap-datepicker.id.min.js",
        "assets/jquery/jQuery-Mask-Plugin-master/dist/jquery.mask.min.js",
        "assets/jquery/select2-4.0.6-rc.1/dist/js/select2.min.js",
        'assets/adminlte/plugins/iCheck/icheck.min.js',
        'assets/sweetalert/sweetalert2.min.js',
        "assets/js/kib.js",
      );

      //REKLAS CONDITION
      $data['data_reklas'] = null;
      $data_reklas = $this->session->userdata('data_reklas');
      if (!empty($data_reklas)) {
        $kode_jenis_asal = $this->global_model->kode_jenis[$data_reklas['kode_jenis']];
        $kib_asal = $this->global_model->row_get_where('view_kib', array('kode_jenis' => $data_reklas['kode_jenis'], 'id_kib' => $data_reklas['id_kib'],));
        $kode_barang_asal = $this->global_model->row_get_where('tbl_kode_barang', array('id_kode_barang' => $data_reklas['id_kode_barang'],));
        $kode_barang_tujuan = $this->global_model->row_get_where('tbl_kode_barang', array('id_kode_barang' => $data_reklas['id_kode_barang_tujuan'],));
        $data['data_reklas'] = $data_reklas;
        $data['kode_jenis_asal'] = $kode_jenis_asal;
        $data['kib_asal'] = $kib_asal;
        $data['kode_barang_asal'] = $kode_barang_asal;

        $kode_jenis = $kode_barang_tujuan['kode_akun'] . "." . $kode_barang_tujuan['kode_kelompok'] . "." . $kode_barang_tujuan['kode_jenis'] . ".";
        $kode_objek = $kode_jenis . $kode_barang_tujuan['kode_objek'] . ".";
        $kode_rincian_objek = $kode_objek  . $kode_barang_tujuan['kode_rincian_objek'] . ".";
        $kode_sub_rincian_objek = $kode_rincian_objek  . $kode_barang_tujuan['kode_sub_rincian_objek'] . ".";
        $kode_sub_sub_rincian_objek = $kode_sub_rincian_objek  . $kode_barang_tujuan['kode_sub_sub_rincian_objek'];

        $data['kode_objek'] = set_value('kode_objek', $kode_objek);
        $data['kode_rincian_objek'] = set_value('kode_rincian_objek', $kode_rincian_objek);
        $data['kode_sub_rincian_objek'] = set_value('kode_sub_rincian_objek', $kode_sub_rincian_objek);
        $data['kode_sub_sub_rincian_objek'] = set_value('kode_sub_sub_rincian_objek', $kode_sub_sub_rincian_objek);
        $data['kode_barang'] = set_value('kode_barang', $kode_barang_tujuan['kode_barang']);
        $data['nama_barang'] = set_value('nama_barang', $kode_barang_tujuan['nama_barang_simbada'] ? $kode_barang_tujuan['nama_barang_simbada'] : $kode_barang_tujuan['nama_barang']);
        // die(json_encode($data));
        
        $data['sumber_dana'] = set_value('id_sumber_dana', $kib_asal['id_sumber_dana']);
        $data['rekening'] = set_value('id_rekening', $kib_asal['id_rekening']);
      }
      //END REKLAS CONDITION


      //sumber dana
      $data['lis_sumber_dana'] = $this->global_model->get_sumber_dana();
      //kode rekening
      $data['lis_rekening'] = $this->global_model->get_rekening($data['sumber_dana']);

      //pemilik
      $data['pemilik'] = $this->global_model->get_pemilik();
      // objek
      $data['objek'] = $this->global_model->get_objek($this->kode_jenis);

      $kode = $this->global_library->explode_kode_barang($data['kode_barang']);

      // rincian_objek
      $data['rincian_objek'] = $this->global_model->get_rincian_objek($kode['kode_jenis'], $kode['kode_objek']);
      // sub_rincian_objek
      $data['sub_rincian_objek'] = $this->global_model->get_sub_rincian_objek($kode['kode_jenis'], $kode['kode_objek'], $kode['kode_rincian_objek']);
      // sub_sub_rincian_objek
      $data['sub_sub_rincian_objek'] = $this->global_model->get_sub_sub_rincian_objek($kode['kode_jenis'], $kode['kode_objek'], $kode['kode_rincian_objek'], $kode['kode_sub_rincian_objek']);

      $data['master_kondisi_bangunan'] = $this->global_model->get_master_kondisi_bangunan();
      // $data['master_status_tanah'] = $this->global_model->get_master_status_tanah();
      $data['master_hak_tanah'] = $this->global_model->get_master_hak_tanah();
      $data['master_asal_usul'] = $this->global_model->get_master_asal_usul();
      // $data['sertifikat_nomor'] = $this->global_model->get_sertifikat_nomor();
      $data['master_satuan'] = $this->global_model->get_master_satuan();

      $data['kib_a'] = $this->global_model->get_kib_a_by_id_lokasi($this->session->userdata('session')->id_kode_lokasi);

      $data['content'] = $this->load->view('kib_c/validasi_read', $data, TRUE);
      $data['breadcrumb'] = array(
        array('label' => 'Data KIB', 'url' => '#', 'icon' => 'fa fa-pie-chart', 'li_class' => ''),
        array('label' => 'KIB C', 'url' => '#', 'icon' => '', 'li_class' => ''),
        array('label' => 'Edit', 'url' => '#', 'icon' => '', 'li_class' => 'active'),
      );
      $this->load->view('template', $data);
    } else {
      $this->session->set_flashdata('message', 'Data Tidak Ditemukan');
      redirect(base_url('kib_c'));
    }
  }
}

/* End of file Kib_c.php */
/* Location: ./application/controllers/Kib_c.php */
/* Please DO NOT modify this information : */
/* Generated by Harviacode Codeigniter CRUD Generator 2018-08-07 08:13:39 */
/* http://harviacode.com */
