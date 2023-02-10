<?php

if (!defined('BASEPATH')) exit('No direct script access allowed');

class Kib_e extends MX_Controller
{
  protected $kode_jenis = '05';
  function __construct()
  {
    parent::__construct();
    $this->global_model->cek_hak_akses('10');
    $this->load->model('Kib_e_model');
    $this->load->library('form_validation');
    $this->load->library('datatables');
    $this->load->library('Global_library');
    $this->load->helper('my_global');
    $this->form_validation->CI = &$this;
  }

  public function index()
  {
    //HAPUS SESSION REKLAS
     $this->session->unset_userdata('data_reklas');

    $data['css'] = array(
      'assets/datatables/dataTables.min.css',
      "assets/jquery/select2-4.0.6-rc.1/dist/css/select2.min.css",
      "assets/jquery/select2-bootstrap-theme-master/dist/select2-bootstrap.min.css",
      'assets/sweetalert/sweetalert2.css',
    );

    $data['js'] = array(
      // 'assets/datatables/jquery.dataTables.js',
      // 'assets/datatables/dataTables.bootstrap.js',
      "assets/jquery/select2-4.0.6-rc.1/dist/js/select2.min.js",
      'assets/sweetalert/sweetalert2.min.js',
    );
    $session = $this->session->userdata('session');
    // die(json_encode(substr($session->kode_lokasi, -1)));
    $data['show_lokasi'] = false;
    if (substr($session->kode_lokasi, -1) == "*")

    
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
      // if ($data['kuasa_pengguna']) {
      //   $data['sub_kuasa_pengguna_list'] = $this->global_model->get_sub_kuasa_pengguna($data['kuasa_pengguna']->id_pengguna, $data['kuasa_pengguna']->id_kuasa_pengguna);
      //   $data['sub_kuasa_pengguna'] = $this->global_model->get_view_sub_kuasa_pengguna($lokasi_explode->pengguna, $lokasi_explode->kuasa_pengguna, $lokasi_explode->sub_kuasa_pengguna);
      // }
    }
    //filter

      $data['show_lokasi'] = true;
    $data['menu'] = $this->global_model->kode_jenis[$this->kode_jenis];
    $data['content'] = $this->load->view('kib_e/tbl_kib_e_list', $data, TRUE);
    $data['breadcrumb'] = array(
      array('label' => 'Data KIB', 'url' => '#', 'icon' => 'fa fa-pie-chart', 'li_class' => ''),
      array('label' => 'KIB E', 'url' => '#', 'icon' => '', 'li_class' => 'active'),
    );
    $this->load->view('template', $data);
  }

  public function json()
  {
    header('Content-Type: application/json');
    echo $this->Kib_e_model->json();
  }

  public function read($id)
  {
    $id = decrypt_url($id);
    $row = $this->Kib_e_model->get_by_id($id);
    
    if ($row) {
      // $kode_barang = $this->global_library->get_kode_barang($row->kode_barang);
      $kode_barang = $this->global_model->get_kode_barang_by_id($row->id_kode_barang);
      // $kode_lokasi=$this->global_library->get_kode_lokasi($row->kode_lokasi);
      $kode_lokasi = $this->global_model->get_kode_lokasi_by_id($row->id_kode_lokasi);
      $data = array(
        'button' => 'Detail',
        'menu' => $this->global_model->kode_jenis[$this->kode_jenis]['nama'],
        'action' => base_url('kib_e/update_action'),
        'id_kib_e' => set_value('id_kib_e', $row->id_kib_e),
        'sumber_dana' => set_value('sumber_dana', $row->id_sumber_dana),
        'rekening' => set_value('rekening', $row->id_rekening),
        'nama_barang' => set_value('nama_barang', $row->nama_barang),
        'kode_barang' => set_value('kode_barang', $row->kode_barang),
        'kondisi' => set_value('kondisi', $row->kondisi),
        // 'nomor_register' => set_value('nomor_register', $row->nomor_register),
        'judul_pencipta' => set_value('judul_pencipta', $row->judul_pencipta),
        'spesifikasi' => set_value('spesifikasi', $row->spesifikasi),
        'kesenian_asal_daerah' => set_value('kesenian_asal_daerah', $row->kesenian_asal_daerah),
        'kesenian_pencipta' => set_value('kesenian_pencipta', $row->kesenian_pencipta),
        'kesenian_bahan' => set_value('kesenian_bahan', $row->kesenian_bahan),
        'hewan_tumbuhan_jenis' => set_value('hewan_tumbuhan_jenis', $row->hewan_tumbuhan_jenis),
        'hewan_tumbuhan_ukuran' => set_value('hewan_tumbuhan_ukuran', $row->hewan_tumbuhan_ukuran),
        // 'jumlah' => set_value('jumlah', $row->jumlah),
        'tahun_pembelian' => set_value('tahun_pembelian', $row->tahun_pembelian),
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
        'tanggal_pembelian' => set_value('tanggal_pembelian', tgl_indo($row->tanggal_pembelian)),
        'tanggal_perolehan' => set_value('tanggal_perolehan', tgl_indo($row->tanggal_perolehan)),
        'kib_f' => set_value('kib_f', $row->kib_f),
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

      $data['master_asal_usul'] = $this->global_model->get_master_asal_usul();
      $data['master_kondisi_bangunan'] = $this->global_model->get_master_kondisi_bangunan();
      $data['master_satuan'] = $this->global_model->get_master_satuan();


      $data['content'] = $this->load->view('kib_e/tbl_kib_e_read', $data, TRUE);
      $data['breadcrumb'] = array(
        array('label' => 'Data KIB', 'url' => '#', 'icon' => 'fa fa-pie-chart', 'li_class' => ''),
        array('label' => 'KIB E', 'url' => '#', 'icon' => '', 'li_class' => ''),
        array('label' => 'Edit', 'url' => '#', 'icon' => '', 'li_class' => 'active'),
      );
      $this->load->view('template', $data);
    } else {
      $this->session->set_flashdata('message', 'Data Tidak Ditemukan');
      redirect(base_url('kib_e'));
    }
  }

  public function create()
  {
    $this->load->helper('my_global');
    $pemilik = $this->global_model->get_pemilik_by_id($this->default_pemilik);
    $kode_lokasi = remove_star($pemilik->kode . '.' . $this->session->userdata('session')->kode_lokasi);
    $data['menu'] = $this->global_model->kode_jenis[$this->kode_jenis];
    $data = array(
      'button' => 'Buat',
      'menu' => $this->global_model->kode_jenis[$this->kode_jenis]['nama'],
      'action' => base_url('kib_e/create_action'),
      'id_kib_e' => set_value('id_kib_e'),
      'sumber_dana' => set_value('sumber_dana'),
      'rekening' => set_value('rekening'),
      'nama_barang' => set_value('nama_barang'),
      'kode_barang' => set_value('kode_barang'),
      'kondisi' => set_value('kondisi'),
      // 'nomor_register' => set_value('nomor_register'),
      'judul_pencipta' => set_value('judul_pencipta'),
      'spesifikasi' => set_value('spesifikasi'),
      'kesenian_asal_daerah' => set_value('kesenian_asal_daerah'),
      'kesenian_pencipta' => set_value('kesenian_pencipta'),
      'kesenian_bahan' => set_value('kesenian_bahan'),
      'hewan_tumbuhan_jenis' => set_value('hewan_tumbuhan_jenis'),
      'hewan_tumbuhan_ukuran' => set_value('hewan_tumbuhan_ukuran'),
      // 'jumlah' => set_value('jumlah'),
      'tahun_pembelian' => set_value('tahun_pembelian'),
      'asal_usul' => set_value('asal_usul'),
      'harga' => set_value('harga'),
      'satuan' => set_value('satuan'),
      'umur_ekonomis' => set_value('umur_ekonomis'),
      'keterangan' => set_value('keterangan'),
      'deskripsi' => set_value('deskripsi'),

      'kode_lokasi' => set_value('kode_lokasi', $kode_lokasi),
      'kode_objek' => set_value('kode_objek'),
      'kode_rincian_objek' => set_value('kode_rincian_objek'),
      'kode_sub_rincian_objek' => set_value('kode_sub_rincian_objek'),
      'kode_sub_sub_rincian_objek' => set_value('kode_sub_sub_rincian_objek'),
      'status_pemilik' => set_value('status_pemilik', $this->default_pemilik),
      'nama_lokasi' => set_value('nama_lokasi', $this->session->userdata('session')->nama_lokasi),
      'jumlah_barang' => set_value('jumlah_barang', 1),

      'tanggal_transaksi' => set_value('tanggal_transaksi'),
      'nomor_transaksi' => set_value('nomor_transaksi'),
      'tanggal_pembelian' => set_value('tanggal_pembelian'),
      'tanggal_perolehan' => set_value('tanggal_perolehan'),
      'kib_f' => set_value('kib_f', '1'),
      'validasi' => set_value('validasi'),
    );
    $data['css'] = array(
      "assets/bootstrap/bootstrap-datepicker-master/dist/css/bootstrap-datepicker.min.css",
      "assets/jquery/select2-4.0.6-rc.1/dist/css/select2.min.css",
      "assets/jquery/select2-bootstrap-theme-master/dist/select2-bootstrap.min.css",
      'assets/adminlte/plugins/iCheck/all.css',
      'assets/sweetalert/sweetalert.css',
      "assets/css/kib.css",
    );
    $data['js'] = array(
      "assets/bootstrap/bootstrap-datepicker-master/dist/js/bootstrap-datepicker.min.js",
      "assets/bootstrap/bootstrap-datepicker-master/dist/locales/bootstrap-datepicker.id.min.js",
      "assets/jquery/jQuery-Mask-Plugin-master/dist/jquery.mask.min.js",
      "assets/jquery/select2-4.0.6-rc.1/dist/js/select2.min.js",
      'assets/adminlte/plugins/iCheck/icheck.min.js',
      'assets/sweetalert/sweetalert.min.js',
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
      
      $data['tanggal_transaksi'] = set_value('tanggal_transaksi',  date('d-m-Y', strtotime($data_reklas['tanggal'])));
      if($data_reklas['kode_jenis'] != '06'){
        $data['nilai_kontrak'] = set_value('nilai_kontrak', $kib_asal['harga']);
      }else{
        $data['nilai_kontrak'] = set_value('nilai_kontrak', $kib_asal['nilai_kontrak']);
      }
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

    $data['master_asal_usul'] = $this->global_model->get_master_asal_usul();
    $data['master_kondisi_bangunan'] = $this->global_model->get_master_kondisi_bangunan();
    $data['master_satuan'] = $this->global_model->get_master_satuan();


    $data['content'] = $this->load->view('kib_e/tbl_kib_e_form', $data, TRUE);
    $data['breadcrumb'] = array(
      array('label' => 'Data KIB', 'url' => '#', 'icon' => 'fa fa-pie-chart', 'li_class' => ''),
      array('label' => 'KIB E', 'url' => '#', 'icon' => '', 'li_class' => ''),
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
      // $data['kode_barang_kib_f'] = $this->global_model->get_kode_barang_kib_f('5');
      if($this->input->post('umur_ekonomis', TRUE) == '0' || $this->input->post('umur_ekonomis', TRUE) == ''){
        $umur_ekonomis =  $this->global_model->get_kode_barang($this->input->post('kode_barang', TRUE))->umur_ekonomis == NULL ? '0' : $this->global_model->get_kode_barang($this->input->post('kode_barang', TRUE))->umur_ekonomis;
			}else{
        $umur_ekonomis = $this->input->post('umur_ekonomis', TRUE);
      }
      
			  $harga = str_replace('.', '', $this->input->post('harga', TRUE));
			  $harga = str_replace('Rp', '', $harga);
			  $harga = str_replace(',', '.', $harga);

      $data = array(
        'id_kode_barang' => $this->global_model->get_kode_barang($this->input->post('kode_barang', TRUE))->id_kode_barang,
        'id_pemilik' => $this->input->post('status_pemilik', TRUE),
        'id_kode_lokasi' => $this->global_model->get_kode_lokasi($this->input->post('kode_lokasi', TRUE))->id_kode_lokasi,
        'nama_barang' => $this->input->post('nama_barang', TRUE),
        'kode_barang' => $this->input->post('kode_barang', TRUE),
        'kondisi' => $this->input->post('kondisi', TRUE),
        'judul_pencipta' => $this->input->post('judul_pencipta', TRUE),
        'spesifikasi' => $this->input->post('spesifikasi', TRUE),
        'kesenian_asal_daerah' => $this->input->post('kesenian_asal_daerah', TRUE),
        'kesenian_pencipta' => $this->input->post('kesenian_pencipta', TRUE),
        'kesenian_bahan' => $this->input->post('kesenian_bahan', TRUE),
        'hewan_tumbuhan_jenis' => $this->input->post('hewan_tumbuhan_jenis', TRUE),
        'hewan_tumbuhan_ukuran' => str_replace('.', '', $this->input->post('hewan_tumbuhan_ukuran', TRUE)),
        'jumlah' => 1, //str_replace('.','',$this->input->post('jumlah',TRUE)) ,
        'tahun_pembelian' => $this->input->post('tahun_pembelian', TRUE),
        'asal_usul' => $this->input->post('asal_usul', TRUE),
        'harga' => $harga,

        'keterangan' => $this->input->post('keterangan', TRUE),
        'deskripsi' => $this->input->post('deskripsi', TRUE),
        'kode_lokasi' => str_replace(".*", "", $this->global_model->get_kode_lokasi($this->input->post('kode_lokasi', TRUE))->kode_lokasi),
				'tanggal_transaksi' => date('Y-m-d', tgl_inter($this->input->post('tanggal_transaksi', TRUE))),
        'nomor_transaksi' => $this->input->post('nomor_transaksi', TRUE),
        'tanggal_pembelian' => date('Y-m-d', tgl_inter($this->input->post('tanggal_pembelian', TRUE))),
        'tanggal_perolehan' => date('Y-m-d', tgl_inter($this->input->post('tanggal_perolehan', TRUE))),
        // 'kib_f' => $this->input->post('kib_f', TRUE) ? '2' : '1',
        // 'kode_barang_f' => $data['kode_barang_kib_f']->kode_barang,
        'id_sumber_dana' => set_null($this->input->post('sumber_dana', TRUE)),
        'id_rekening' => set_null($this->input->post('rekening', TRUE)),
        'satuan' => $this->input->post('satuan', TRUE),
        'umur_ekonomis' => $umur_ekonomis,

        'created_at' => date('Y-m-d H:i:s'),
        'updated_at' => date('Y-m-d H:i:s'),
      );

      $jumlah_barang = str_replace('.', '', $this->input->post('jumlah_barang', TRUE));

      //REKLAS CONDITION
      $data_reklas = $this->session->userdata('data_reklas');

      if ($jumlah_barang > 1) {
        $insert_id = $this->Kib_e_model->insert($data); //ambil id untuk input_banyak
        $this->Kib_e_model->update($insert_id, array('input_banyak' => $insert_id,)); //update insert yang pertama
        $data['input_banyak'] = $insert_id;
        $jumlah_barang--; //karena sudah insert 1 kali di atas
        for ($jumlah_barang; $jumlah_barang > 0; $jumlah_barang--) {
          $this->Kib_e_model->insert($data);
          $this->global_model->_logs($menu = 'data_kib', $sub_menu = 'kib_e', $tabel_name = 'tbl_kib_e', $action_id = null, $action = 'insert', $data = $data);
          $data['created_at'] = date('Y-m-d H:i:s');
          $data['updated_at'] = date('Y-m-d H:i:s');
        }
      } else if (!empty($data_reklas)) {
        //REKLAS CONDITION
        $data['status_barang'] = 'reklas_kode';
        $id = $this->Kib_e_model->insert($data);
				$this->global_model->update_reklas($data_reklas['id_reklas_kode'],array('id_kib_tujuan' => $id));
        $this->global_model->_logs($menu = 'data_kib', $sub_menu = 'kib_e', $tabel_name = 'tbl_kib_e', $action_id = $id, $action = 'insert_reklas', $data = $data);

        //HAPUS SESSION REKLAS
        $this->session->unset_userdata('data_reklas');
        redirect(base_url('reklas/reklas_kode'));
        //END REKLAS CONDITION        
      } else {
        $this->Kib_e_model->insert($data);
        $this->global_model->_logs($menu = 'data_kib', $sub_menu = 'kib_e', $tabel_name = 'tbl_kib_e', $action_id = null, $action = 'insert', $data = $data);
      }

      $this->session->set_flashdata('message', 'Data Baru Berhasil Dibuat');
      redirect(base_url('kib_e'));
    }
  }

  public function update($id)
  {
    $id = decrypt_url($id);
    $this->load->helper('my_global');
    $row = $this->Kib_e_model->get_by_id($id);
    // bila sudah di validasi dan bukan super admin
    // if ($row->validasi == '2' and $this->session->userdata('session')->id_role != '1')
    //   redirect('dashboard');
    if ($row) {
      // $kode_barang = $this->global_library->get_kode_barang($row->kode_barang);
      $kode_barang = $this->global_model->get_kode_barang_by_id($row->id_kode_barang);
      // $kode_lokasi=$this->global_library->get_kode_lokasi($row->kode_lokasi);
      $kode_lokasi = $this->global_model->get_kode_lokasi_by_id($row->id_kode_lokasi);
      $data = array(
        'button' => 'Update',
        'menu' => $this->global_model->kode_jenis[$this->kode_jenis]['nama'],
        'action' => base_url('kib_e/update_action'),
        'id_kib_e' => set_value('id_kib_e', $row->id_kib_e),
        'sumber_dana' => set_value('sumber_dana', $row->id_sumber_dana),
        'rekening' => set_value('rekening', $row->id_rekening),
        'nama_barang' => set_value('nama_barang', $row->nama_barang),
        'kode_barang' => set_value('kode_barang', $row->kode_barang),
        'kondisi' => set_value('kondisi', $row->kondisi),
        // 'nomor_register' => set_value('nomor_register', $row->nomor_register),
        'judul_pencipta' => set_value('judul_pencipta', $row->judul_pencipta),
        'spesifikasi' => set_value('spesifikasi', $row->spesifikasi),
        'kesenian_asal_daerah' => set_value('kesenian_asal_daerah', $row->kesenian_asal_daerah),
        'kesenian_pencipta' => set_value('kesenian_pencipta', $row->kesenian_pencipta),
        'kesenian_bahan' => set_value('kesenian_bahan', $row->kesenian_bahan),
        'hewan_tumbuhan_jenis' => set_value('hewan_tumbuhan_jenis', $row->hewan_tumbuhan_jenis),
        'hewan_tumbuhan_ukuran' => set_value('hewan_tumbuhan_ukuran', $row->hewan_tumbuhan_ukuran),
        // 'jumlah' => set_value('jumlah', $row->jumlah),
        'tahun_pembelian' => set_value('tahun_pembelian', $row->tahun_pembelian),
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
        'tanggal_pembelian' => set_value('tanggal_pembelian', tgl_indo($row->tanggal_pembelian)),
        'tanggal_perolehan' => set_value('tanggal_perolehan', tgl_indo($row->tanggal_perolehan)),
        'kib_f' => set_value('kib_f', $row->kib_f),
        'validasi' => set_value('validasi', $row->validasi),
      );
      $data['css'] = array(
        "assets/bootstrap/bootstrap-datepicker-master/dist/css/bootstrap-datepicker.min.css",
        "assets/jquery/select2-4.0.6-rc.1/dist/css/select2.min.css",
        "assets/jquery/select2-bootstrap-theme-master/dist/select2-bootstrap.min.css",
        'assets/adminlte/plugins/iCheck/all.css',
        'assets/sweetalert/sweetalert.css',
        "assets/css/kib.css",
      );
      $data['js'] = array(
        "assets/bootstrap/bootstrap-datepicker-master/dist/js/bootstrap-datepicker.min.js",
        "assets/bootstrap/bootstrap-datepicker-master/dist/locales/bootstrap-datepicker.id.min.js",
        "assets/jquery/jQuery-Mask-Plugin-master/dist/jquery.mask.min.js",
        "assets/jquery/select2-4.0.6-rc.1/dist/js/select2.min.js",
        'assets/adminlte/plugins/iCheck/icheck.min.js',
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

      $data['master_asal_usul'] = $this->global_model->get_master_asal_usul();
      $data['master_kondisi_bangunan'] = $this->global_model->get_master_kondisi_bangunan();
      $data['master_satuan'] = $this->global_model->get_master_satuan();


      $data['content'] = $this->load->view('kib_e/tbl_kib_e_form', $data, TRUE);
      $data['breadcrumb'] = array(
        array('label' => 'Data KIB', 'url' => '#', 'icon' => 'fa fa-pie-chart', 'li_class' => ''),
        array('label' => 'KIB E', 'url' => '#', 'icon' => '', 'li_class' => ''),
        array('label' => 'Edit', 'url' => '#', 'icon' => '', 'li_class' => 'active'),
      );
      $this->load->view('template', $data);
    } else {
      $this->session->set_flashdata('message', 'Data Tidak Ditemukan');
      redirect(base_url('kib_e'));
    }
  }

  public function update_action()
  {
    $this->_rules_update();

    if ($this->form_validation->run() == FALSE) {
      $this->update($this->input->post('id_kib_e', TRUE));
    } else {
      
      $this->db->trans_start();

      $data = array(

        'id_pemilik' => $this->input->post('status_pemilik', TRUE),


        'kondisi' => $this->input->post('kondisi', TRUE),
        'judul_pencipta' => $this->input->post('judul_pencipta', TRUE),
        'spesifikasi' => $this->input->post('spesifikasi', TRUE),
        'kesenian_asal_daerah' => $this->input->post('kesenian_asal_daerah', TRUE),
        'kesenian_pencipta' => $this->input->post('kesenian_pencipta', TRUE),
        'kesenian_bahan' => $this->input->post('kesenian_bahan', TRUE),
        'hewan_tumbuhan_jenis' => $this->input->post('hewan_tumbuhan_jenis', TRUE),
        'hewan_tumbuhan_ukuran' => str_replace('.', '', $this->input->post('hewan_tumbuhan_ukuran', TRUE)),
        'jumlah' => 1, //str_replace('.','',$this->input->post('jumlah',TRUE)) ,
        'tahun_pembelian' => $this->input->post('tahun_pembelian', TRUE),
        'asal_usul' => $this->input->post('asal_usul', TRUE),

        'keterangan' => $this->input->post('keterangan', TRUE),
        'deskripsi' => $this->input->post('deskripsi', TRUE),
        'tanggal_transaksi' => $this->input->post('tanggal_transaksi', TRUE) != '-' ? date('Y-m-d', tgl_inter($this->input->post('tanggal_transaksi', TRUE))) : '0000-00-00',
        'nomor_transaksi' => $this->input->post('nomor_transaksi', TRUE),
        'tanggal_pembelian' => $this->input->post('tanggal_pembelian', TRUE) != '-' ?  date('Y-m-d', tgl_inter($this->input->post('tanggal_pembelian', TRUE))) : '0000-00-00',

        // 'kib_f' => $this->input->post('kib_f', TRUE) ? '2' : '1',
        'id_sumber_dana' => set_null($this->input->post('sumber_dana', TRUE)),
        'id_rekening' => set_null($this->input->post('rekening', TRUE)),
        'satuan' => set_null($this->input->post('satuan', TRUE)),
        'validasi' => '1',

        // 'created_at' => date('Y-m-d H:i:s'),
        'updated_at' => date('Y-m-d H:i:s'),
      );
      if ($this->input->post('validasi', TRUE) != '2') {
        
			  $harga = str_replace('.', '', $this->input->post('harga', TRUE));
			  $harga = str_replace('Rp', '', $harga);
			  $harga = str_replace(',', '.', $harga);
        // $data['id_kode_lokasi'] = $this->global_model->get_kode_lokasi($this->input->post('kode_lokasi', TRUE))->id_kode_lokasi;
        $data['id_kode_barang'] = $this->global_model->get_kode_barang($this->input->post('kode_barang', TRUE))->id_kode_barang;
        $data['nama_barang'] = $this->input->post('nama_barang', TRUE);
        // $data['kode_lokasi'] = str_replace(".*", "", $this->global_model->get_kode_lokasi($this->input->post('kode_lokasi', TRUE))->kode_lokasi);
				$data['kode_barang'] = $this->input->post('kode_barang', TRUE);
        $data['harga'] = $harga;
        $data['tanggal_perolehan'] = $this->input->post('tanggal_perolehan', TRUE) != '-' ?  date('Y-m-d', tgl_inter($this->input->post('tanggal_perolehan', TRUE))) : '0000-00-00';
			}else{
        $data['validasi'] = '2';
      }

      $row = $this->Kib_e_model->get_by_id($this->input->post('id_kib_e', TRUE));

      if ($row->validasi == 2 && $this->session->userdata('session')->id_role == '1') { // kib yg sudah tervalidasi (hanya admin yg bisa)
        $this->global_model->insert_histori_kib('05', $this->input->post('id_kib_e', TRUE), 'update_kib'); // 5 = KIB E
        $data['validasi'] = '2';
        $data['tanggal_perolehan'] = $this->input->post('tanggal_perolehan', TRUE) != '-' ?  date('Y-m-d', tgl_inter($this->input->post('tanggal_perolehan', TRUE))) : '0000-00-00';
			
        $data_histori = array(
          'tanggal_histori' => date('Y-m-d', tgl_inter($this->input->post('tanggal_transaksi', TRUE)))
        );

        if ($row->input_banyak > 0) {

          $row_kib = $this->global_model->get_kib_input_banyak('05', $this->input->post('id_kib_e', TRUE));

          foreach ($row_kib as $key) {
            $this->global_model->update_histori_barang('05', $this->input->post('id_kib_e', TRUE), $row->tanggal_transaksi, 'entri', $data_histori);
          }

        } else {
          $this->global_model->update_histori_barang('05', $this->input->post('id_kib_e', TRUE), $row->tanggal_transaksi, 'entri', $data_histori);
        }
      }

      //REKLAS CONDITION
      $data_reklas = $this->session->userdata('data_reklas');
      if (!empty($data_reklas)) {
        //REKLAS CONDITION
        $data['status_barang'] = 'reklas_kode';
        $this->Kib_e_model->update($this->input->post('id_kib_e', TRUE), $data);
        // $this->global_model->global_update(
        //   'tbl_reklas_kode',
        //   array('id_reklas_kode' => $data_reklas['id_reklas_kode']),
        //   array('id_kib_tujuan' => $this->input->post('id_kib_e', TRUE),)
        // );
        $this->global_model->_logs($menu = 'data_kib', $sub_menu = 'kib_e', $tabel_name = 'tbl_kib_e', $action_id = $this->input->post('id_kib_e', TRUE), $action = 'update_reklas', $data = $data);

        //HAPUS SESSION REKLAS
        $this->session->unset_userdata('data_reklas');
        redirect(base_url('reklas/reklas_kode'));
        //END REKLAS CONDITION
      } else {

        if($row->input_banyak > 0){
          $this->Kib_e_model->update_input_banyak($row->input_banyak, $data);
        }else{
        $this->Kib_e_model->update($this->input->post('id_kib_e', TRUE), $data);
        }
        $this->global_model->_logs($menu = 'data_kib', $sub_menu = 'kib_e', $tabel_name = 'tbl_kib_e', $action_id = $this->input->post('id_kib_e', TRUE), $action = 'update', $data = $data);
        
        
        $this->db->trans_complete();
        
        $this->session->set_flashdata('message', 'Data Berhasil Diubah');
        redirect(base_url('kib_e'));
      }
    }
  }

  public function delete($id)
  {
    $id = decrypt_url($id);
    $row = $this->Kib_e_model->get_by_id($id);
    $bulan = date('n');
    $tahun = date('Y');
    $id_kode_lokasi = $row->id_kode_lokasi;
    $locked = $this->global_model->get_locked($bulan,$tahun,$id_kode_lokasi);

    if($locked > 0){
    $this->session->set_flashdata('message', 'Stock Opname Bulan '.bulan_indo($bulan).' Telah Dikunci');
    redirect(base_url('kib_e'));
    }

    // bila sudah di validasi dan bukan super admin
    if ($row->validasi == '2' and $this->session->userdata('session')->id_role != '1'){
    // if ($row->validasi == '2')
      redirect('dashboard');
    }
      
    if ($row) {
      // die;
      $this->db->trans_start();
        $this->global_model->insert_histori_kib('05', $id, 'delete_kib'); // 5 = KIB E

      $this->Kib_e_model->delete($id);
      $this->Kib_e_model->delete_histori_barang($id);
      $this->global_model->_logs($menu = 'data_kib', $sub_menu = 'kib_e', $tabel_name = 'tbl_kib_e', $action_id = $id, $action = 'delete', $data = $row);
      $this->session->set_flashdata('message', 'Data Berhasil Dihapus');
      $this->db->trans_complete(); # Completing transaction

      if ($this->db->trans_status() === FALSE) {
          # Something went wrong.
          $this->db->trans_rollback();
          $this->session->set_flashdata('message', 'Data Tidak Ditemukan');
          redirect(base_url('kib_e'));
      } 
      else {
          # Committing data to the database.
          $this->db->trans_commit();
          redirect(base_url('kib_e'));
      }
    } else {
      $this->session->set_flashdata('message', 'Data Tidak Ditemukan');
      redirect(base_url('kib_e'));
    }
  }

  public function delete_all()
  {
    // print_r($_POST); die;
    if(!empty($_POST[ 'list_id'])) {
      
      $this->db->trans_start();
      
      foreach ($_POST['list_id'] as $key => $value) {

        $id = $value;
        $row = $this->Kib_e_model->get_by_id($id);
        $bulan = date('n');
        $tahun = date('Y');
        $id_kode_lokasi = $row->id_kode_lokasi;
        $locked = $this->global_model->get_locked($bulan, $tahun, $id_kode_lokasi);

        if ($locked > 0) {
          echo json_encode(array('status' => FALSE, 'message' => 'Stock Opname Bulan ' . bulan_indo($bulan) . ' Tahun ' . $tahun . ' Telah Dikunci'));
          exit;
          break;
        }

        // bila sudah di validasi dan bukan super admin
        if ($row->validasi == '2' and $this->session->userdata('session')->id_role != '1') {
          // if ($row->validasi == '2')
          echo json_encode(array('status' => FALSE, 'message' => 'Stock Opname Bulan ' . bulan_indo($bulan) . ' Tahun ' . $tahun . ' Telah Dikunci'));
          exit;
          break;
        }

        if ($row) {
          // die;


          $this->Kib_e_model->delete($id);
          if ($row->validasi == '2'){
            $this->global_model->insert_histori_kib('05', $id, 'delete_kib'); // 5 = KIB E
            $this->Kib_e_model->delete_histori_barang($id);
            $this->global_model->_logs($menu = 'data_kib', $sub_menu = 'kib_e', $tabel_name = 'tbl_kib_e', $action_id = $id, $action = 'delete', $data = $row);
          // $this->session->set_flashdata('message', 'Data Berhasil Dihapus');
          }

        } else {
          echo json_encode(array('status' => TRUE, 'message' => 'Data Tidak Ditemukan'));
          exit;
          break;
        }
      }

      $this->db->trans_complete(); # Completing transaction

      if ($this->db->trans_status() === FALSE) {
        # Something went wrong.
        $this->db->trans_rollback();
        echo json_encode(array('status' => FALSE, 'message' => 'Gagal Dihapus'));
        exit;
      } else {
        # Committing data to the database.
        $this->db->trans_commit();
        echo json_encode(array('status' => TRUE, 'message' => 'Berhasil Dihapus'));
        // exit;
      }
    }
  }

  public function _rules()
  {
    $this->form_validation->set_rules('nama_barang', 'nama barang', 'trim|required');
    $this->form_validation->set_rules('kode_barang', 'kode barang', 'trim|required');
    // $this->form_validation->set_rules('nomor_register', 'nomor register', 'trim|required');
    $this->form_validation->set_rules('judul_pencipta', 'judul pencipta', 'trim|required');
    $this->form_validation->set_rules('spesifikasi', 'spesifikasi', 'trim|required');
    $this->form_validation->set_rules('kesenian_asal_daerah', 'kesenian asal daerah', 'trim|required');
    $this->form_validation->set_rules('kesenian_pencipta', 'kesenian pencipta', 'trim|required');
    $this->form_validation->set_rules('kesenian_bahan', 'kesenian bahan', 'trim|required');
    $this->form_validation->set_rules('hewan_tumbuhan_jenis', 'hewan tumbuhan jenis', 'trim|required');
    $this->form_validation->set_rules('hewan_tumbuhan_ukuran', 'hewan tumbuhan ukuran', 'trim|required');
    // $this->form_validation->set_rules('jumlah', 'jumlah', 'trim|required');
    $this->form_validation->set_rules('tahun_pembelian', 'tahun pembelian', 'trim|required');
    // $this->form_validation->set_rules('asal_usul', 'asal usul', 'trim|required');
    $this->form_validation->set_rules('rekening', 'Kode Rekening', 'trim|required');
    $this->form_validation->set_rules('harga', 'harga', 'trim|required');
    $this->form_validation->set_rules('keterangan', 'keterangan', 'trim|required');

    $this->form_validation->set_rules('tanggal_transaksi', 'Tanggal Transaksi', 'trim|required|callback_max_year');
    $this->form_validation->set_rules('nomor_transaksi', 'Nomor Transaksi', 'trim|required');
    $this->form_validation->set_rules('tanggal_pembelian', 'Tanggal Pembelian', 'trim|required|callback_max_year');
    $this->form_validation->set_rules('tanggal_perolehan', 'Tanggal Perolehan', 'trim|required|callback_max_year');

    $this->form_validation->set_rules('id_kib_e', 'id_kib_e', 'trim');
    $this->form_validation->set_error_delimiters('<span class="text-danger">', '</span>');
    $this->form_validation->set_message('required', 'Objek %s harus di isi.');
  }

  public function _rules_update()
  {
    // $this->form_validation->set_rules('nama_barang', 'nama barang', 'trim|required');
    // $this->form_validation->set_rules('kode_barang', 'kode barang', 'trim|required');
    // $this->form_validation->set_rules('nomor_register', 'nomor register', 'trim|required');
    $this->form_validation->set_rules('judul_pencipta', 'judul pencipta', 'trim|required');
    $this->form_validation->set_rules('spesifikasi', 'spesifikasi', 'trim|required');
    $this->form_validation->set_rules('kesenian_asal_daerah', 'kesenian asal daerah', 'trim|required');
    $this->form_validation->set_rules('kesenian_pencipta', 'kesenian pencipta', 'trim|required');
    $this->form_validation->set_rules('kesenian_bahan', 'kesenian bahan', 'trim|required');
    $this->form_validation->set_rules('hewan_tumbuhan_jenis', 'hewan tumbuhan jenis', 'trim|required');
    $this->form_validation->set_rules('hewan_tumbuhan_ukuran', 'hewan tumbuhan ukuran', 'trim|required');
    // $this->form_validation->set_rules('jumlah', 'jumlah', 'trim|required');
    $this->form_validation->set_rules('tahun_pembelian', 'tahun pembelian', 'trim|required');
    // $this->form_validation->set_rules('asal_usul', 'asal usul', 'trim|required');
    // $this->form_validation->set_rules('rekening', 'Kode Rekening', 'trim|required');
    // $this->form_validation->set_rules('harga', 'harga', 'trim|required');
    $this->form_validation->set_rules('keterangan', 'keterangan', 'trim|required');

    // $this->form_validation->set_rules('tanggal_transaksi', 'Tanggal Transaksi', 'trim|required|callback_max_year');
    // $this->form_validation->set_rules('nomor_transaksi', 'Nomor Transaksi', 'trim|required');
    // $this->form_validation->set_rules('tanggal_pembelian', 'Tanggal Pembelian', 'trim|required|callback_max_year');
    // $this->form_validation->set_rules('tanggal_perolehan', 'Tanggal Perolehan', 'trim|required|callback_max_year');

    $this->form_validation->set_rules('id_kib_e', 'id_kib_e', 'trim');
    $this->form_validation->set_error_delimiters('<span class="text-danger">', '</span>');
    $this->form_validation->set_message('required', 'Objek %s harus di isi.');
  }

  public function max_year($field_value)
  {
    $tahun_ini = date('Y');
    if (strlen($field_value) > 4) {
      $field_value = date('Y', tgl_inter($field_value));
    }

    if ((int) $field_value > (int) $tahun_ini) {
      $this->form_validation->set_message('max_year', "Tidak boleh lebih dari tahun sekarang");
      return FALSE;
    } else {
      return TRUE;
    }
  }
}

  /* End of file Kib_e.php */
  /* Location: ./application/controllers/Kib_e.php */
  /* Please DO NOT modify this information : */
  /* Generated by Harviacode Codeigniter CRUD Generator 2018-08-07 08:14:13 */
  /* http://harviacode.com */
