<?php

if (!defined('BASEPATH')) exit('No direct script access allowed');

class Kib_f extends MX_Controller
{
  protected $kode_jenis = '06';
  function __construct()
  {
    parent::__construct();

    /*header("refresh:3;url=" . base_url('dashboard'));
    die('halaman sudah di hapus');*/

    $this->global_model->cek_hak_akses('11');
    $this->load->model('Kib_f_model');
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
      $data['show_lokasi'] = true;

    //filter

    $data['pemilik'] = $this->global_model->get_pemilik();
    $data['intra_ekstra'] = array(
      '00' => 'Semua',
      '01' => 'Intrakomptable',
      '02' => 'Extrakomptable',
    );

    $session = $this->session->userdata('session');
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

    $data['menu'] = $this->global_model->kode_jenis[$this->kode_jenis];
    $data['content'] = $this->load->view('kib_f/tbl_kib_f_list', $data, TRUE);
    $this->load->view('template', $data);
  }

  public function json()
  {
    header('Content-Type: application/json');
    echo $this->Kib_f_model->json();
  }

  public function read($id)
  {
    $id = decrypt_url($id);
    $row = $this->Kib_f_model->get_by_id($id);
    if ($row) {
      $data = array(
        'menu' => $this->global_model->kode_jenis[$this->kode_jenis]['nama'],
        'id_kib_f' => $row->id_kib_f,
        'kode_barang' => $row->kode_barang,
        'nama_barang' => $row->nama_barang,
        'bangunan' => $row->bangunan,
        'kontruksi_bertingkat' => $row->kontruksi_bertingkat,
        'kontruksi_beton' => $row->kontruksi_beton,
        'luas_m2' => my_format_number($row->luas_m2),
        'lokasi_alamat' => $row->lokasi_alamat,
        'dokumen_tanggal' => tgl_indo($row->dokumen_tanggal),
        'dokumen_nomor' => $row->dokumen_nomor,
        'tanggal_mulai' => tgl_indo($row->tanggal_mulai),
        'status_tanah' => $row->status_tanah,
        'nomor_kode_tanah' => $row->nomor_kode_tanah,
        'asal_usul' => $row->asal_usul,
        'nilai_kontrak' => my_format_number($row->nilai_kontrak),
        'keterangan' => $row->keterangan,
        'deskripsi' => $row->deskripsi,
      );
      $data['content'] = $this->load->view('kib_f/tbl_kib_f_read', $data, TRUE);
      $this->load->view('template', $data);
    } else {
      $this->session->set_flashdata('message', 'Data Tidak Ditemukan');
      redirect(base_url('kib_f'));
    }
  }

  public function create()
  {
    $pemilik = $this->global_model->get_pemilik_by_id($this->default_pemilik);
    $kode_lokasi = remove_star($pemilik->kode . '.' . $this->session->userdata('session')->kode_lokasi);
    $data = array(
      'button' => 'Buat',
      'menu' => $this->global_model->kode_jenis[$this->kode_jenis]['nama'],
      'action' => base_url('kib_f/create_action'),
      'id_kib_f' => set_value('id_kib_f'),
      'sumber_dana' => set_value('sumber_dana'),
      'rekening' => set_value('rekening'),
      'kode_barang' => set_value('kode_barang'),
      'nama_barang' => set_value('nama_barang'),
      'bangunan' => set_value('bangunan'),
      'kontruksi_bertingkat' => set_value('kontruksi_bertingkat'),
      'kontruksi_beton' => set_value('kontruksi_beton'),
      'luas_m2' => set_value('luas_m2'),
      'lokasi_alamat' => set_value('lokasi_alamat'),
      'dokumen_tanggal' => set_value('dokumen_tanggal'),
      'dokumen_nomor' => set_value('dokumen_nomor'),
      'tanggal_mulai' => set_value('tanggal_mulai'),
      'status_tanah' => set_value('status_tanah'),
      'nomor_kode_tanah' => set_value('nomor_kode_tanah'),
      'asal_usul' => set_value('asal_usul'),
      'nilai_kontrak' => set_value('nilai_kontrak'),
      'satuan' => set_value('satuan'),
      'umur_ekonomis' => set_value('umur_ekonomis'),
      'keterangan' => set_value('keterangan'),

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
      'tanggal_perolehan' => set_value('tanggal_perolehan'),
      'referensi_id' => set_value('referensi_id'),
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
      $jenis = $this->global_model->kode_jenis[$data_reklas['kode_jenis']];
      $kib_asal = $this->global_model->row_get_where($jenis['table'], array($jenis['id_name'] => $data_reklas['id_kib'],));
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
      
      $data['tanggal_transaksi'] = set_value('tanggal_transaksi',  date('d-m-Y', strtotime($data_reklas['tanggal'])));
      $jenis_tujuan_obj = explode('.', $data_reklas['kode_jenis_tujuan']);
      if($jenis_tujuan_obj[2] != '06' && $data_reklas['kode_jenis'] != '06'){
        $data['harga'] = set_value('harga', $kib_asal['harga']);
      }elseif($jenis_tujuan_obj[2] != '06' && $data_reklas['kode_jenis'] == '06'){
        $data['harga'] = set_value('harga', $kib_asal['nilai_kontrak']);
      }else{
        $data['nilai_kontrak'] = set_value('nilai_kontrak', $kib_asal['nilai_kontrak']);
      }
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

    $data['referensi'] = $this->Kib_f_model->get_kib();

    $data['master_bangunan'] = $this->global_model->get_master_bangunan();
    // $data['master_status_tanah'] = $this->global_model->get_master_status_tanah();
    $data['master_hak_tanah'] = $this->global_model->get_master_hak_tanah();
    $data['master_asal_usul'] = $this->global_model->get_master_asal_usul();
    $data['master_satuan'] = $this->global_model->get_master_satuan();

    // $data['sertifikat_nomor'] = $this->global_model->get_sertifikat_nomor();
    $data['kib_a'] = $this->global_model->get_kib_a_by_id_lokasi($this->session->userdata('session')->id_kode_lokasi);

    $data['content'] = $this->load->view('kib_f/tbl_kib_f_form', $data, TRUE);
    $this->load->view('template', $data);
  }

  public function create_action()
  {
    $this->_rules();

    if ($this->form_validation->run() == FALSE) {
      $this->create();
    } else {
      if($this->input->post('umur_ekonomis', TRUE) == '0' || $this->input->post('umur_ekonomis', TRUE) == ''){
        $umur_ekonomis =  $this->global_model->get_kode_barang($this->input->post('kode_barang', TRUE))->umur_ekonomis == NULL ? '0' : $this->global_model->get_kode_barang($this->input->post('kode_barang', TRUE))->umur_ekonomis;
			}else{
        $umur_ekonomis = $this->input->post('umur_ekonomis', TRUE);
      }
      
			  $harga = str_replace('.', '', $this->input->post('nilai_kontrak', TRUE));
			  $harga = str_replace('Rp', '', $harga);
			  $harga = str_replace(',', '.', $harga);

      $data = array(
        'id_kode_barang' => $this->global_model->get_kode_barang($this->input->post('kode_barang', TRUE))->id_kode_barang,
        'id_pemilik' => $this->input->post('status_pemilik', TRUE),
        'id_kode_lokasi' => $this->global_model->get_kode_lokasi($this->input->post('kode_lokasi', TRUE))->id_kode_lokasi,
        'kode_barang' => $this->input->post('kode_barang', TRUE),
        'nama_barang' => $this->input->post('nama_barang', TRUE),
        'bangunan' => $this->input->post('bangunan', TRUE),
        'kontruksi_bertingkat' => $this->input->post('kontruksi_bertingkat', TRUE),
        'kontruksi_beton' => $this->input->post('kontruksi_beton', TRUE),
        'luas_m2' => str_replace('.', '', $this->input->post('luas_m2', TRUE)),
        'lokasi_alamat' => $this->input->post('lokasi_alamat', TRUE),
        'dokumen_tanggal' => date('Y-m-d', tgl_inter($this->input->post('dokumen_tanggal', TRUE))),
        'dokumen_nomor' => $this->input->post('dokumen_nomor', TRUE),
        'tanggal_mulai' => date('Y-m-d', tgl_inter($this->input->post('tanggal_mulai', TRUE))),
        'status_tanah' => $this->input->post('status_tanah', TRUE),
        'nomor_kode_tanah' => $this->input->post('nomor_kode_tanah', TRUE),
        'asal_usul' => $this->input->post('asal_usul', TRUE),
        'nilai_kontrak' => $harga,
        'keterangan' => $this->input->post('keterangan', TRUE),
        'kode_lokasi' => str_replace(".*", "", $this->global_model->get_kode_lokasi($this->input->post('kode_lokasi', TRUE))->kode_lokasi),
				'tanggal_transaksi' => date('Y-m-d', tgl_inter($this->input->post('tanggal_transaksi', TRUE))),
        'nomor_transaksi' => $this->input->post('nomor_transaksi', TRUE),
        'tanggal_perolehan' => date('Y-m-d', tgl_inter($this->input->post('tanggal_perolehan', TRUE))),
        'tanggal_pembelian' => '0000-00-00',
        'referensi_id' => set_null($this->input->post('referensi_id', TRUE)),
        'id_sumber_dana' => set_null($this->input->post('sumber_dana', TRUE)),
        'id_rekening' => set_null($this->input->post('rekening', TRUE)),
        'satuan' => $this->input->post('satuan', TRUE),
        'umur_ekonomis' => $umur_ekonomis,

        'created_at' => date('Y-m-d H:i:s'),
        'updated_at' => date('Y-m-d H:i:s'),
      );
      // die(json_encode($data));
      $jumlah_barang = str_replace('.', '', $this->input->post('jumlah_barang', TRUE));

      //REKLAS CONDITION
      $data_reklas = $this->session->userdata('data_reklas');

      if ($jumlah_barang > 1) {
        for ($jumlah_barang; $jumlah_barang > 0; $jumlah_barang--) {
          $this->Kib_f_model->insert($data); //memang di awal , supaya no register pas
          $this->global_model->_logs($menu = 'data_kib', $sub_menu = 'kib_f', $tabel_name = 'tbl_kib_f', $action_id = null, $action = 'insert', $data = $data);
          $data['created_at'] = date('Y-m-d H:i:s');
          $data['updated_at'] = date('Y-m-d H:i:s');
        }
      } else if (!empty($data_reklas)) {
        //REKLAS CONDITION
        $data['status_barang'] = 'reklas_kode';
        $id = $this->Kib_f_model->insert($data);
				$this->global_model->update_reklas($data_reklas['id_reklas_kode'],array('id_kib_tujuan' => $id));
        $this->global_model->_logs($menu = 'data_kib', $sub_menu = 'kib_f', $tabel_name = 'tbl_kib_f', $action_id = $id, $action = 'insert_reklas', $data = $data);

        //HAPUS SESSION REKLAS
        $this->session->unset_userdata('data_reklas');
        redirect(base_url('reklas/reklas_kode'));
        //END REKLAS CONDITION

      } else {
        $this->Kib_f_model->insert($data);
        $this->global_model->_logs($menu = 'data_kib', $sub_menu = 'kib_f', $tabel_name = 'tbl_kib_f', $action_id = null, $action = 'insert', $data = $data);
      }

      $this->session->set_flashdata('message', 'Data Baru Berhasil Dibuat');
      redirect(base_url('kib_f'));
    }
  }

  public function update($id)
  {
    $id = decrypt_url($id);
    $row = $this->Kib_f_model->get_by_id($id);
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
        'action' => base_url('kib_f/update_action'),
        'id_kib_f' => set_value('id_kib_f', $row->id_kib_f),
        'sumber_dana' => set_value('sumber_dana', $row->id_sumber_dana),
        'rekening' => set_value('rekening', $row->id_rekening),
        'kode_barang' => set_value('kode_barang', $row->kode_barang),
        'nama_barang' => set_value('nama_barang', $row->nama_barang),
        'bangunan' => set_value('bangunan', $row->bangunan),
        'kontruksi_bertingkat' => set_value('kontruksi_bertingkat', $row->kontruksi_bertingkat),
        'kontruksi_beton' => set_value('kontruksi_beton', $row->kontruksi_beton),
        'luas_m2' => set_value('luas_m2', $row->luas_m2),
        'lokasi_alamat' => set_value('lokasi_alamat', $row->lokasi_alamat),
        'dokumen_tanggal' => set_value('dokumen_tanggal', tgl_indo($row->dokumen_tanggal)),
        'dokumen_nomor' => set_value('dokumen_nomor', $row->dokumen_nomor),
        'tanggal_mulai' => set_value('tanggal_mulai', tgl_indo($row->tanggal_mulai)),
        'status_tanah' => set_value('status_tanah', $row->status_tanah),
        'nomor_kode_tanah' => set_value('nomor_kode_tanah', $row->nomor_kode_tanah),
        'asal_usul' => set_value('asal_usul', $row->asal_usul),
        'nilai_kontrak' => set_value('nilai_kontrak', $row->nilai_kontrak),
        'keterangan' => set_value('keterangan', $row->keterangan),
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
        'tanggal_perolehan' => set_value('tanggal_perolehan', tgl_indo($row->tanggal_perolehan)),
        'referensi_id' => set_value('referensi_id', $row->referensi_id),
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

      $data['referensi'] = $this->Kib_f_model->get_kib();

      $data['master_bangunan'] = $this->global_model->get_master_bangunan();
      // $data['master_status_tanah'] = $this->global_model->get_master_status_tanah();
      $data['master_hak_tanah'] = $this->global_model->get_master_hak_tanah();
      $data['master_asal_usul'] = $this->global_model->get_master_asal_usul();
      // $data['sertifikat_nomor'] = $this->global_model->get_sertifikat_nomor();
      $data['master_satuan'] = $this->global_model->get_master_satuan();



      $data['kib_a'] = $this->global_model->get_kib_a_by_id_lokasi($this->session->userdata('session')->id_kode_lokasi);

      $data['content'] = $this->load->view('kib_f/tbl_kib_f_form', $data, TRUE);
      $this->load->view('template', $data);
    } else {
      $this->session->set_flashdata('message', 'Data Tidak Ditemukan');
      redirect(base_url('kib_f'));
    }
  }

  public function update_action()
  {
    $this->_rules_update();

    if ($this->form_validation->run() == FALSE) {
      $this->update($this->input->post('id_kib_f', TRUE));
    } else {
      $data = array(

        'id_pemilik' => $this->input->post('status_pemilik', TRUE),

        'bangunan' => $this->input->post('bangunan', TRUE),
        'kontruksi_bertingkat' => $this->input->post('kontruksi_bertingkat', TRUE),
        'kontruksi_beton' => $this->input->post('kontruksi_beton', TRUE),
        'luas_m2' => str_replace('.', '', $this->input->post('luas_m2', TRUE)),
        'lokasi_alamat' => $this->input->post('lokasi_alamat', TRUE),
        'dokumen_tanggal' => $this->input->post('dokumen_tanggal', TRUE) != '-' ? date('Y-m-d', tgl_inter($this->input->post('dokumen_tanggal', TRUE))) : '0000-00-00',
        'dokumen_nomor' => $this->input->post('dokumen_nomor', TRUE),
        'tanggal_mulai' => $this->input->post('tanggal_mulai', TRUE) != '-' ? date('Y-m-d', tgl_inter($this->input->post('tanggal_mulai', TRUE))) : '0000-00-00',
        'status_tanah' => $this->input->post('status_tanah', TRUE),
        'nomor_kode_tanah' => $this->input->post('nomor_kode_tanah', TRUE),
        'asal_usul' => $this->input->post('asal_usul', TRUE),

        'keterangan' => $this->input->post('keterangan', TRUE),
        // 'kode_lokasi' => $this->input->post('kode_lokasi', TRUE),
        'tanggal_transaksi' => $this->input->post('tanggal_transaksi', TRUE) != '-' ? date('Y-m-d', tgl_inter($this->input->post('tanggal_transaksi', TRUE))) : '0000-00-00',
        'nomor_transaksi' => $this->input->post('nomor_transaksi', TRUE),
        
        'referensi_id' => set_null($this->input->post('referensi_id', TRUE)),
        'id_sumber_dana' => set_null($this->input->post('sumber_dana', TRUE)),
        'id_rekening' => set_null($this->input->post('rekening', TRUE)),
        'satuan' => set_null($this->input->post('satuan', TRUE)),
        'validasi' => '1',

        // 'created_at' => date('Y-m-d H:i:s'),
        'updated_at' => date('Y-m-d H:i:s'),
      );
      if ($this->input->post('validasi', TRUE) != '2') {
        
			  $harga = str_replace('.', '', $this->input->post('nilai_kontrak', TRUE));
			  $harga = str_replace('Rp', '', $harga);
			  $harga = str_replace(',', '.', $harga);
        // $data['id_kode_lokasi'] = $this->global_model->get_kode_lokasi($this->input->post('kode_lokasi', TRUE))->id_kode_lokasi;
        $data['id_kode_barang'] = $this->global_model->get_kode_barang($this->input->post('kode_barang', TRUE))->id_kode_barang;
        $data['kode_barang'] = $this->input->post('kode_barang', TRUE);
        // $data['kode_lokasi'] = str_replace(".*", "", $this->global_model->get_kode_lokasi($this->input->post('kode_lokasi', TRUE))->kode_lokasi);
				$data['nama_barang'] = $this->input->post('nama_barang', TRUE);
        $data['nilai_kontrak'] = $harga;
        $data['tanggal_perolehan'] = $this->input->post('tanggal_perolehan', TRUE) != '-' ?  date('Y-m-d', tgl_inter($this->input->post('tanggal_perolehan', TRUE))) : '0000-00-00';
			}else{
        $data['validasi'] = '2';
      }

      $row = $this->Kib_f_model->get_by_id($this->input->post('id_kib_f', TRUE));
      
      if ($row->validasi == 2 && $this->session->userdata('session')->id_role == '1') { // kib yg sudah tervalidasi
        $this->global_model->insert_histori_kib('06', $this->input->post('id_kib_f', TRUE), 'update_kib'); // 6 = KIB F
        $data['validasi'] = '2';
        $data['tanggal_perolehan'] = $this->input->post('tanggal_perolehan', TRUE) != '-' ?  date('Y-m-d', tgl_inter($this->input->post('tanggal_perolehan', TRUE))) : '0000-00-00';
			
        $data_histori = array(
          'tanggal_histori' => date('Y-m-d', tgl_inter($this->input->post('tanggal_transaksi', TRUE))),
          'tanggal_pembelian' => $data['tanggal_perolehan'],
          'id_sumber_dana'  => $data['id_sumber_dana'],
          'id_rekening'  => $data['id_rekening'],
        );

        // $this->global_model->update_histori_barang('06', $this->input->post('id_kib_f', TRUE), $data_histori);
        $this->global_model->update_histori_barang('06', $this->input->post('id_kib_f', TRUE), $row->tanggal_transaksi, 'entri', $data_histori);        
      }
      //REKLAS CONDITION
      $data_reklas = $this->session->userdata('data_reklas');
      if (!empty($data_reklas)) {
        //REKLAS CONDITION
        $data['status_barang'] = 'reklas_kode';
        $this->Kib_f_model->update($this->input->post('id_kib_f', TRUE), $data);
        // $this->global_model->global_update(
        //   'tbl_reklas_kode',
        //   array('id_reklas_kode' => $data_reklas['id_reklas_kode']),
        //   array('id_kib_tujuan' => $this->input->post('id_kib_f', TRUE),)
        // );
        $this->global_model->_logs($menu = 'data_kib', $sub_menu = 'kib_f', $tabel_name = 'tbl_kib_f', $action_id = $this->input->post('id_kib_f', TRUE), $action = 'update_reklas', $data = $data);

        //HAPUS SESSION REKLAS
        $this->session->unset_userdata('data_reklas');
        redirect(base_url('reklas/reklas_kode'));
        //END REKLAS CONDITION
      } else {
        $this->Kib_f_model->update($this->input->post('id_kib_f', TRUE), $data);
        $this->global_model->_logs($menu = 'data_kib', $sub_menu = 'kib_f', $tabel_name = 'tbl_kib_f', $action_id = $this->input->post('id_kib_f', TRUE), $action = 'update', $data = $data);
        $this->session->set_flashdata('message', 'Data Berhasil Diubah');
        redirect(base_url('kib_f'));
      }
    }
  }

  public function delete($id)
  {
    $id = decrypt_url($id);
    $row = $this->Kib_f_model->get_by_id($id);
    $tanggal_transaksi = $row->tanggal_transaksi;
    // die(substr($tanggal_transaksi, 0, 4));
    $bulan = substr($tanggal_transaksi, 5, 2) != '00' ? date('n', $tanggal_transaksi) : '12';
    $tahun = substr($tanggal_transaksi, 0, 4) != '0000' ? date('Y', $tanggal_transaksi) : '2020';
    $id_kode_lokasi = $row->id_kode_lokasi;
    $stockopname = $this->global_model->get_last_stockopname($id_kode_lokasi);
    // die(json_encode($stockopname));
    $tgl_stockopname = !empty($stockopname->tgl_stockopname) ? date("Y-m-t", strtotime($stockopname)) : '2020-12-31';
    $tgl_transaksi   = date("Y-m-d", strtotime($tanggal_transaksi));


    if ($tgl_transaksi < $tgl_stockopname) {
      echo json_encode(array('status' => FALSE, 'message' => 'Stock Opname Bulan ' . bulan_indo($bulan) . ' Tahun ' . $tahun . ' Telah Dikunci'));
      exit;
      break;
    }

    // bila sudah di validasi dan bukan super admin
    if ($row->validasi == '2' and $this->session->userdata('session')->id_role != '1'){
    // if ($row->validasi == '2')
      redirect('dashboard');
    }
      
    if ($row) {
      // die;
      $this->db->trans_start();
        // $this->global_model->insert_histori_kib('6', $id, 'delete_kib'); // 6 = KIB F

      $this->Kib_f_model->delete($id);
      $this->Kib_f_model->delete_histori_barang($id);
      $this->global_model->_logs($menu = 'data_kib', $sub_menu = 'kib_f', $tabel_name = 'tbl_kib_f', $action_id = $id, $action = 'delete', $data = $row);
      $this->session->set_flashdata('message', 'Data Berhasil Dihapus');
      $this->db->trans_complete(); # Completing transaction

      if ($this->db->trans_status() === FALSE) {
          # Something went wrong.
          $this->db->trans_rollback();
          $this->session->set_flashdata('message', 'Data Gagal Dihapus');
          redirect(base_url('kib_f'));
      } 
      else {
          # Committing data to the database.
          $this->db->trans_commit();
          redirect(base_url('kib_f'));
      }
    } else {
      $this->session->set_flashdata('message', 'Data Tidak Ditemukan');
      redirect(base_url('kib_f'));
    }
  }

  public function delete_all()
  {
    // print_r($_POST); die;
    if(!empty($_POST[ 'list_id'])) {

      $this->db->trans_start();
      
      foreach ($_POST['list_id'] as $key => $value) {

        $id = $value;
        $row = $this->Kib_f_model->get_by_id($id);
        $tanggal_transaksi = date('Y-m-d', strtotime($row->tanggal_transaksi));
        $bulan = substr($tanggal_transaksi, 5, 2) != '00' ? date('n', strtotime($tanggal_transaksi)) : '12';
        $tahun = substr($tanggal_transaksi, 0, 4) != '0000' ? date('Y', strtotime($tanggal_transaksi)) : '2020';
        $id_kode_lokasi = $row->id_kode_lokasi;
        $stockopname = $this->global_model->get_last_stockopname($id_kode_lokasi);
        $tgl_stockopname = !empty($stockopname->tgl_stockopname) ? date("Y-m-t", strtotime($stockopname->tgl_stockopname)) : '2020-12-31';
        $tgl_transaksi   = date("Y-m-d", strtotime($tanggal_transaksi));

        // die(json_encode($bulan));
        // exit;
        // break; 

        if ($tgl_transaksi < $tgl_stockopname) {
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

          if ($row->validasi == '2'){
          // $this->global_model->insert_histori_kib('6', $id, 'delete_kib'); // 6 = KIB F
          $this->Kib_f_model->delete_histori_barang($id);
          $this->global_model->_logs($menu = 'data_kib', $sub_menu = 'kib_f', $tabel_name = 'tbl_kib_f', $action_id = $id, $action = 'delete', $data = $row);
          }

          $this->Kib_f_model->delete($id);
          // $this->session->set_flashdata('message', 'Data Berhasil Dihapus');

        } else {
          echo json_encode(array('status' => TRUE, 'message' => 'Data Tidak Ditemukan'));
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
    $this->form_validation->set_rules('kode_barang', 'kode barang', 'trim|required');
    $this->form_validation->set_rules('nama_barang', 'nama barang', 'trim|required');
    $this->form_validation->set_rules('bangunan', 'bangunan', 'trim|required');
    $this->form_validation->set_rules('kontruksi_bertingkat', 'kontruksi bertingkat', 'trim|required');
    $this->form_validation->set_rules('kontruksi_beton', 'kontruksi beton', 'trim|required');
    $this->form_validation->set_rules('luas_m2', 'luas m2', 'trim|required');
    $this->form_validation->set_rules('lokasi_alamat', 'lokasi alamat', 'trim|required');
    $this->form_validation->set_rules('dokumen_tanggal', 'dokumen tanggal', 'trim|required');
    $this->form_validation->set_rules('dokumen_nomor', 'dokumen nomor', 'trim|required');
    $this->form_validation->set_rules('tanggal_mulai', 'tanggal mulai', 'trim|required');
    $this->form_validation->set_rules('status_tanah', 'status tanah', 'trim|required');
    $this->form_validation->set_rules('nomor_kode_tanah', 'nomor kode tanah', 'trim|required');
    // $this->form_validation->set_rules('asal_usul', 'asal usul', 'trim|required');
    $this->form_validation->set_rules('rekening', 'Kode Rekening', 'trim|required');
    $this->form_validation->set_rules('nilai_kontrak', 'nilai kontrak', 'trim|required');
    $this->form_validation->set_rules('keterangan', 'keterangan', 'trim|required');
    $this->form_validation->set_rules('tanggal_transaksi', 'tanggal transaksi', 'trim|required');
    $this->form_validation->set_rules('nomor_transaksi', 'nomor transaksi', 'trim|required');
    $this->form_validation->set_rules('tanggal_perolehan', 'tanggal perolehan', 'trim|required');

    $this->form_validation->set_rules('id_kib_f', 'id_kib_f', 'trim');
    $this->form_validation->set_error_delimiters('<span class="text-danger">', '</span>');
    $this->form_validation->set_message('required', 'Objek %s harus di isi.');
  }

  public function _rules_update()
  {
    // $this->form_validation->set_rules('kode_barang', 'kode barang', 'trim|required');
    // $this->form_validation->set_rules('nama_barang', 'nama barang', 'trim|required');
    $this->form_validation->set_rules('bangunan', 'bangunan', 'trim|required');
    $this->form_validation->set_rules('kontruksi_bertingkat', 'kontruksi bertingkat', 'trim|required');
    $this->form_validation->set_rules('kontruksi_beton', 'kontruksi beton', 'trim|required');
    $this->form_validation->set_rules('luas_m2', 'luas m2', 'trim|required');
    $this->form_validation->set_rules('lokasi_alamat', 'lokasi alamat', 'trim|required');
    $this->form_validation->set_rules('dokumen_tanggal', 'dokumen tanggal', 'trim|required');
    $this->form_validation->set_rules('dokumen_nomor', 'dokumen nomor', 'trim|required');
    $this->form_validation->set_rules('tanggal_mulai', 'tanggal mulai', 'trim|required');
    $this->form_validation->set_rules('status_tanah', 'status tanah', 'trim|required');
    $this->form_validation->set_rules('nomor_kode_tanah', 'nomor kode tanah', 'trim|required');
    // $this->form_validation->set_rules('asal_usul', 'asal usul', 'trim|required');
    // $this->form_validation->set_rules('rekening', 'Kode Rekening', 'trim|required');
    // $this->form_validation->set_rules('nilai_kontrak', 'nilai kontrak', 'trim|required');
    $this->form_validation->set_rules('keterangan', 'keterangan', 'trim|required');
    // $this->form_validation->set_rules('tanggal_transaksi', 'tanggal transaksi', 'trim|required');
    // $this->form_validation->set_rules('nomor_transaksi', 'nomor transaksi', 'trim|required');
    // $this->form_validation->set_rules('tanggal_perolehan', 'tanggal perolehan', 'trim|required');

    $this->form_validation->set_rules('id_kib_f', 'id_kib_f', 'trim');
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


  public function excel()
  {
    $this->load->helper('exportexcel');
    $namaFile = "tbl_kib_f.xls";
    $judul = "tbl_kib_f";
    $tablehead = 0;
    $tablebody = 1;
    $nourut = 1;
    //penulisan header
    header("Pragma: public");
    header("Expires: 0");
    header("Cache-Control: must-revalidate, post-check=0,pre-check=0");
    header("Content-Type: application/force-download");
    header("Content-Type: application/octet-stream");
    header("Content-Type: application/download");
    header("Content-Disposition: attachment;filename=" . $namaFile . "");
    header("Content-Transfer-Encoding: binary ");

    xlsBOF();

    $kolomhead = 0;
    xlsWriteLabel($tablehead, $kolomhead++, "No");
    xlsWriteLabel($tablehead, $kolomhead++, "Nama Barang");
    xlsWriteLabel($tablehead, $kolomhead++, "Bangunan");
    xlsWriteLabel($tablehead, $kolomhead++, "Kontruksi Bertingkat");
    xlsWriteLabel($tablehead, $kolomhead++, "Kontruksi Beton");
    xlsWriteLabel($tablehead, $kolomhead++, "Luas M2");
    xlsWriteLabel($tablehead, $kolomhead++, "Lokasi Alamat");
    xlsWriteLabel($tablehead, $kolomhead++, "Dokumen Tanggal");
    xlsWriteLabel($tablehead, $kolomhead++, "Dokumen Nomor");
    xlsWriteLabel($tablehead, $kolomhead++, "Tanggal Mulai");
    xlsWriteLabel($tablehead, $kolomhead++, "Status Tanah");
    xlsWriteLabel($tablehead, $kolomhead++, "Nomor Kode Tanah");
    xlsWriteLabel($tablehead, $kolomhead++, "Asal Usul");
    xlsWriteLabel($tablehead, $kolomhead++, "Nilai Kontrak");
    xlsWriteLabel($tablehead, $kolomhead++, "Keterangan");

    foreach ($this->Kib_f_model->get_all() as $data) {
      $kolombody = 0;

      //ubah xlsWriteLabel menjadi xlsWriteNumber untuk kolom numeric
      xlsWriteNumber($tablebody, $kolombody++, $nourut);
      xlsWriteLabel($tablebody, $kolombody++, $data->nama_barang);
      xlsWriteLabel($tablebody, $kolombody++, $data->bangunan);
      xlsWriteLabel($tablebody, $kolombody++, $data->kontruksi_bertingkat);
      xlsWriteLabel($tablebody, $kolombody++, $data->kontruksi_beton);
      xlsWriteLabel($tablebody, $kolombody++, $data->luas_m2);
      xlsWriteLabel($tablebody, $kolombody++, $data->lokasi_alamat);
      xlsWriteLabel($tablebody, $kolombody++, $data->dokumen_tanggal);
      xlsWriteLabel($tablebody, $kolombody++, $data->dokumen_nomor);
      xlsWriteLabel($tablebody, $kolombody++, $data->tanggal_mulai);
      xlsWriteLabel($tablebody, $kolombody++, $data->status_tanah);
      xlsWriteLabel($tablebody, $kolombody++, $data->nomor_kode_tanah);
      xlsWriteLabel($tablebody, $kolombody++, $data->asal_usul);
      xlsWriteNumber($tablebody, $kolombody++, $data->nilai_kontrak);
      xlsWriteLabel($tablebody, $kolombody++, $data->keterangan);

      $tablebody++;
      $nourut++;
    }

    xlsEOF();
    exit();
  }
}

/* End of file Kib_f.php */
/* Location: ./application/controllers/Kib_f.php */
/* Please DO NOT modify this information : */
/* Generated by Harviacode Codeigniter CRUD Generator 2018-08-07 08:14:32 */
/* http://harviacode.com */
