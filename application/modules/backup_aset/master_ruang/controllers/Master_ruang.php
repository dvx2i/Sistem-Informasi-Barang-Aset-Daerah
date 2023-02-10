<?php

if (!defined('BASEPATH')) exit('No direct script access allowed');

class Master_ruang extends MX_Controller
{
  protected $kode_jenis = '01';

  function __construct()
  {
    parent::__construct();
    $this->global_model->cek_hak_akses('6');
    $this->load->model('Ruang_a_model');
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

    //$data['menu'] = $this->global_model->kode_jenis[$this->kode_jenis];
    $data["content"] = $this->load->view('master_ruang/tbl_ruang_list', $data, TRUE);
    $data['breadcrumb'] = array(
      array('label' => 'Master Ruang', 'url' => '#', 'icon' => 'fa fa-pie-chart', 'li_class' => ''),
      array('label' => 'Ruang', 'url' => '#', 'icon' => '', 'li_class' => 'active'),
    );
    $this->load->view('template', $data);
  }

  public function json()
  {
    header('Content-Type: application/json');
    echo $this->Ruang_a_model->json();
  }

  public function read($id)
  {
    $id = decrypt_url($id);
   // echo $id;die;
    $row = $this->Ruang_a_model->get_by_id($id);
    //print_r($row);die;
    if ($row) {
      $data = array(
        'menu' => 'Master Ruang',
        'id_ruang' => $row->id_ruang,
        'nama_ruang' => $row->nama_ruang,
        'letak_alamat' => $row->letak_alamat,
        'status_ruang' => $row->status_ruang,
        'lantai' => $row->lantai,
        'nama_gedung' => $row->nama_gedung,
        /*'kode_barang' => $row->kode_barang,
        'nomor_register' => $row->nomor_register,
        'luas' => my_format_number($row->luas),
        'tahun_pengadaan' => $row->tahun_pengadaan,
        'letak_alamat' => $row->letak_alamat,
        'status_hak' => $row->status_hak,
        'sertifikat_tanggal' => tgl_indo($row->sertifikat_tanggal),
        'sertifikat_nomor' => $row->sertifikat_nomor,*/
        //'penggunaan' => $row->penggunaan,
        /*'asal_usul' => $row->asal_usul,
        'harga' => my_format_number($row->harga),*/
       
        'kode_lokasi' => $row->kode_lokasi,
        'deskripsi' => $row->deskripsi,
      );
      $data['content'] = $this->load->view('master_ruang/tbl_ruang_read', $data, TRUE);
      $this->load->view('template', $data);
    } else {
      $this->session->set_flashdata('message', 'Data Tidak Ditemukan');
      redirect(base_url('master_ruang'));
    }
  }

  public function create()
  {

    $pemilik = $this->global_model->get_pemilik_by_id($this->default_pemilik);
    $kode_lokasi = remove_star($pemilik->kode . '.' . $this->session->userdata('session')->kode_lokasi);
    $data = array(
      'button' => 'Buat',
      'menu' => 'Master Ruang',
      'action' => base_url('master_ruang/create_action'),
      'id_ruang' => set_value('id_ruang'),
	  'nama_ruang' => set_value('nama_ruang'),
    'status_ruang' => set_value('status_ruang'),
    'nama_gedung' => set_value('nama_gedung'),
     'lantai' => set_value('lantai'),
    'letak_alamat' => set_value('letak_alamat'),
    'deskripsi' => set_value('deskripsi'),
      /*'sumber_dana' => set_value('sumber_dana'),
      'rekening' => set_value('rekening'),
      'nama_barang' => set_value('nama_barang'),
      'kode_barang' => set_value('kode_barang'),
      'kondisi' => set_value('kondisi'),
      // 'nomor_register' => set_value('nomor_register'),
      'luas' => set_value('luas'),
      'tahun_pengadaan' => set_value('tahun_pengadaan'),
      'letak_alamat' => set_value('letak_alamat'),
      'status_hak' => set_value('status_hak'),
      'sertifikat_tanggal' => set_value('sertifikat_tanggal'),
      'sertifikat_nomor' => set_value('sertifikat_nomor'),
      'penggunaan' => set_value('penggunaan'),
      'asal_usul' => set_value('asal_usul'),
      'harga' => set_value('harga'),
      'umur_ekonomis' => set_value('umur_ekonomis'),
      'keterangan' => set_value('keterangan'),
      'deskripsi' => set_value('deskripsi'),
      'kode_lokasi' => set_value('kode_lokasi', $kode_lokasi),
*/
	  
      'kode_lokasi' => set_value('kode_lokasi', $kode_lokasi),
      'nama_lokasi' => set_value('nama_lokasi', $this->session->userdata('session')->nama_lokasi),
      'status_pemilik' => set_value('status_pemilik', $this->default_pemilik),
     
      /*'kode_objek' => set_value('kode_objek'),
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
      'kib_f' => set_value('kib_f', 1),
	  */
      'latitute' => set_value('latitute'),
      'longitute' => set_value('longitute'),
      /*'satuan' => set_value('satuan', 'Bidang'),
      'validasi' => set_value('validasi', 1),*/
    );
    // die(json_encode($data));
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
      //"assets/js/kib.js",
    );

    //REKLAS CONDITION
    $data['data_reklas'] = null;
   // $data_reklas = $this->session->userdata('data_reklas');
    /*if (!empty($data_reklas)) {
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
    */
    //END REKLAS CONDITION

    //sumber dana
    //$data['lis_sumber_dana'] = $this->global_model->get_sumber_dana();
    //kode rekening
   // $data['lis_rekening'] = $this->global_model->get_rekening($data['sumber_dana']);

    //pemilik
    $data['pemilik'] = $this->global_model->get_pemilik();
    // objek
   /* $data['objek'] = $this->global_model->get_objek($this->kode_jenis);
    // die(json_encode($data['objek']));
    $kode = $this->global_library->explode_kode_barang($data['kode_barang']);

    // rincian_objek
    $data['rincian_objek'] = $this->global_model->get_rincian_objek($kode['kode_jenis'], $kode['kode_objek']);
    // sub_rincian_objek
    $data['sub_rincian_objek'] = $this->global_model->get_sub_rincian_objek($kode['kode_jenis'], $kode['kode_objek'], $kode['kode_rincian_objek']);
    // sub_sub_rincian_objek 
    //06 may 2019
    $data['sub_sub_rincian_objek'] = $this->global_model->get_sub_sub_rincian_objek($kode['kode_jenis'], $kode['kode_objek'], $kode['kode_rincian_objek'], $kode['kode_sub_rincian_objek']);

    $data['master_hak_tanah'] = $this->global_model->get_master_hak_tanah();
    $data['master_penggunaan'] = $this->global_model->get_master_penggunaan();
    $data['master_asal_usul'] = $this->global_model->get_master_asal_usul();
    $data['master_kondisi_bangunan'] = $this->global_model->get_master_kondisi_bangunan();
    $data['master_satuan'] = $this->global_model->get_master_satuan();
*/
    $data["content"] = $this->load->view('master_ruang/tbl_ruang_form', $data, TRUE);
    $data['breadcrumb'] = array(
      array('label' => 'Master Ruang', 'url' => '#', 'icon' => 'fa fa-pie-chart', 'li_class' => ''),
      array('label' => 'Ruang', 'url' => '#', 'icon' => '', 'li_class' => ''),
      array('label' => 'Buat', 'url' => '#', 'icon' => '', 'li_class' => 'active'),
    );
    $this->load->view('template', $data);
  }

  public function create_action()
  {

    $this->_rules();

    if ($this->form_validation->run() == FALSE) {
//print_r($this->input->post());die;
      $this->create();
    } else {
      // $data['kode_barang_kib_f'] = 
 //echo"bb";die;
      $data = array(
        
        'id_pemilik' => $this->input->post('status_pemilik', TRUE),
        'id_kode_lokasi' => $this->global_model->get_kode_lokasi($this->input->post('kode_lokasi', TRUE))->id_kode_lokasi,
       'nama_ruang' => $this->input->post('nama_ruang', TRUE),
    'status_ruang' => $this->input->post('status_ruang', TRUE),
    'nama_gedung' => $this->input->post('nama_gedung', TRUE),
     'lantai' => $this->input->post('lantai', TRUE),
    'letak_alamat' => $this->input->post('letak_alamat', TRUE),
    'deskripsi' => $this->input->post('deskripsi', TRUE),
     
    'keterangan' => $this->input->post('keterangan', TRUE),
      'deskripsi' => $this->input->post('deskripsi', TRUE),
      'kode_lokasi' =>$this->input->post('kode_lokasi', TRUE),
      //'nama_lokasi' => $this->session->userdata('session')->nama_lokasi,
      //'status_pemilik' => $this->default_pemilik,
     
      'latitute' => $this->input->post('latitute', TRUE),
      'longitute' => $this->input->post('longitute', TRUE),

        'created_at' => date('Y-m-d H:i:s'),
        'updated_at' => date('Y-m-d H:i:s'),
      );
       //die(json_encode($data));
     
        $this->Ruang_a_model->insert($data);
        $this->global_model->_logs($menu = 'master_ruang', $sub_menu = 'ruang', $tabel_name = 'tbl_ruang', $action_id = null, $action = 'insert', $data = $data);
      

      $this->session->set_flashdata('message', 'Data Baru Berhasil Dibuat');
      redirect(base_url('master_ruang'));
    }
  }

  public function update($id)
  {
    //$pemilik = $this->global_model->get_pemilik_by_id($this->default_pemilik);
    //$kode_lokasi = remove_star($pemilik->kode . '.' . $this->session->userdata('session')->kode_lokasi);
    $id = decrypt_url($id);
    $row = $this->Ruang_a_model->get_by_id($id);
    // if ($row->validasi == '2' and $this->session->userdata('session')->id_role != '1')
    //   redirect('dashboard');
    if ($row) {
      // $kode_barang = $this->global_library->get_kode_barang($row->kode_barang);
      // $kode_lokasi=$this->global_library->get_kode_lokasi($row->kode_lokasi);
      //$kode_barang = $this->global_model->get_kode_barang_by_id($row->id_kode_barang);
      //$kode_lokasi = $this->global_model->get_kode_lokasi_by_id($row->id_kode_lokasi);
      $pemilik = $this->global_model->get_pemilik_by_id($this->default_pemilik);
    $kode_lokasi = remove_star($pemilik->kode . '.' . $this->session->userdata('session')->kode_lokasi);

      $data = array(
        'button' => 'Perbarui',
        'menu' => 'Master Ruang',
        'action' => base_url('master_ruang/update_action'),
        'id_ruang' => set_value('id_ruang',$row->id_ruang),
    'nama_ruang' => set_value('nama_ruang',$row->nama_ruang),
    'status_ruang' => set_value('status_ruang',$row->status_ruang),
    'nama_gedung' => set_value('nama_gedung',$row->nama_gedung),
     'lantai' => set_value('lantai',$row->lantai),
    'letak_alamat' => set_value('letak_alamat',$row->letak_alamat),
    'deskripsi' => set_value('deskripsi',$row->deskripsi),
    'kode_lokasi' => set_value('kode_lokasi', $kode_lokasi),
      'nama_lokasi' => set_value('nama_lokasi', $this->session->userdata('session')->nama_lokasi),
      'status_pemilik' => set_value('status_pemilik', $this->default_pemilik),
       'latitute' => set_value('latitute',$row->latitute),
      'longitute' => set_value('longitute',$row->longitute),
      );
      // die(json_encode($data));
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
      
      //END REKLAS CONDITION

      // die(json_encode($data));

      //sumber dana
       $data['pemilik'] = $this->global_model->get_pemilik();
      $data['content'] = $this->load->view('master_ruang/tbl_ruang_form', $data, TRUE);
      $data['breadcrumb'] = array(
        array('label' => 'KIR', 'url' => '#', 'icon' => 'fa fa-pie-chart', 'li_class' => ''),
        array('label' => 'Master Ruang', 'url' => '#', 'icon' => '', 'li_class' => ''),
        array('label' => 'Edit', 'url' => '#', 'icon' => '', 'li_class' => 'active'),
      );
      $this->load->view('template', $data);
    } else {
      $this->session->set_flashdata('message', 'Data Tidak Ditemukan');
      redirect(base_url('master_ruang'));
    }
  }

  public function update_action()
  {
    //print_r($this->input->post());die;
    $this->_rules_update();
//echo "aaa";die;

    if ($this->form_validation->run() == FALSE) {
      //echo "bb";die;
      $this->update($this->input->post('id_ruang', TRUE));
    } else {
       //echo "cc";die;
      $data = array(

        'id_pemilik' => $this->input->post('status_pemilik', TRUE),
        'id_kode_lokasi' => $this->global_model->get_kode_lokasi($this->input->post('kode_lokasi', TRUE))->id_kode_lokasi,
       'nama_ruang' => $this->input->post('nama_ruang', TRUE),
    'status_ruang' => $this->input->post('status_ruang', TRUE),
    'nama_gedung' => $this->input->post('nama_gedung', TRUE),
     'lantai' => $this->input->post('lantai', TRUE),
    'letak_alamat' => $this->input->post('letak_alamat', TRUE),
    'deskripsi' => $this->input->post('deskripsi', TRUE),
     
    'keterangan' => $this->input->post('keterangan', TRUE),
      'deskripsi' => $this->input->post('deskripsi', TRUE),
      'kode_lokasi' =>$this->input->post('kode_lokasi', TRUE),
      //'nama_lokasi' => $this->session->userdata('session')->nama_lokasi,
      //'status_pemilik' => $this->default_pemilik,
     
      'latitute' => $this->input->post('latitute', TRUE),
      'longitute' => $this->input->post('longitute', TRUE),

        //'created_at' => date('Y-m-d H:i:s'),
        'updated_at' => date('Y-m-d H:i:s'),
      );
      


     
     // $row = $this->Ruang_a_model->get_by_id($this->input->post('id_ruang', TRUE));
  //die(json_encode($data));
      

      //REKLAS CONDITION
     
        $this->Ruang_a_model->update($this->input->post('id_ruang', TRUE), $data);
        $this->global_model->_logs($menu = 'KIR', $sub_menu = 'master_ruang', $tabel_name = 'tbl_ruang', $action_id = $this->input->post('id_ruang', TRUE), $action = 'update', $data = $data);
        $this->session->set_flashdata('message', 'Data Berhasil Diubah');
        redirect(base_url('master_ruang'));
    
  }
}

  public function delete($id)
  {
    $id = decrypt_url($id);
    $row = $this->Ruang_a_model->get_by_id($id);
    $bulan = date('n');
    $tahun = date('Y');
    $id_kode_lokasi = $row->id_kode_lokasi;
    //$locked = $this->global_model->get_locked($bulan,$tahun,$id_kode_lokasi);

    if ($row) {
      $this->db->trans_start();

     // $this->global_model->insert_histori_kib('01', $id, 'delete_kib'); // 1 = KIB A

      $this->Ruang_a_model->delete($id);
     // $this->Kib_a_model->delete_histori_barang($id);
      $this->global_model->_logs($menu = 'KIR', $sub_menu = 'master_ruang', $tabel_name = 'tbl_ruang', $action_id = $id, $action = 'delete', $data = $row);
      $this->session->set_flashdata('message', 'Data Berhasil Dihapus');

      $this->db->trans_complete(); # Completing transaction

      if ($this->db->trans_status() === FALSE) {
          # Something went wrong.
          $this->db->trans_rollback();
          $this->session->set_flashdata('message', 'Data Tidak Ditemukan');
          redirect(base_url('master_ruang'));
      } 
      else {
          # Committing data to the database.
          $this->db->trans_commit();
          redirect(base_url('master_ruang'));
      }
    } else {
      $this->session->set_flashdata('message', 'Data Tidak Ditemukan');
      redirect(base_url('master_ruang'));
    }
  }

  public function delete_all()
  {
    // print_r($_POST); die;
    if(!empty($_POST[ 'list_id'])) {

      $this->db->trans_start();
      
      foreach ($_POST['list_id'] as $key => $value) {

        $id = $value;
        $row = $this->Ruang_a_model->get_by_id($id);
        $bulan = date('n');
        $tahun = date('Y');
        $id_kode_lokasi = $row->id_kode_lokasi;
        //$locked = $this->global_model->get_locked($bulan, $tahun, $id_kode_lokasi);

        if ($row) {
          // die;
         
         
          //$this->global_model->insert_histori_kib('01', $id, 'delete_kib'); // 1 = KIB A
         // $this->Kib_a_model->delete_histori_barang($id);
          $this->global_model->_logs($menu = 'KIR', $sub_menu = 'master_ruang', $tabel_name = 'tbl_ruang', $action_id = $id, $action = 'delete', $data = $row);
          // $this->session->set_flashdata('message', 'Data Berhasil Dihapus');
         

          $this->Ruang_a_model->delete($id);

       
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
}


  public function _rules()
  {
    $this->form_validation->set_rules('nama_ruang', 'nama ruang', 'trim|required');
    $this->form_validation->set_rules('lantai', 'Lantai', 'trim|required');
    // $this->form_validation->set_rules('nomor_register', 'nomor register', 'trim|required');
    //$this->form_validation->set_rules('luas', 'luas', 'trim|required');
   // $this->form_validation->set_rules('tahun_pengadaan', 'tahun pengadaan', 'trim|required|callback_max_year');
    $this->form_validation->set_rules('letak_alamat', 'letak alamat', 'trim|required');
    $this->form_validation->set_rules('status_ruang', 'status ruang', 'trim|required');
    //$this->form_validation->set_rules('sertifikat_tanggal', 'sertifikat tanggal', 'trim|required|callback_max_year');
    $this->form_validation->set_rules('nama_gedung', 'nama gedung', 'trim|required');
    //$this->form_validation->set_rules('penggunaan', 'penggunaan', 'trim|required');
    // $this->form_validation->set_rules('asal_usul', 'asal usul', 'trim|required');
    //$this->form_validation->set_rules('rekening', 'Kode Rekening', 'trim|required');
    //$this->form_validation->set_rules('harga', 'harga', 'trim|required');
   // $this->form_validation->set_rules('keterangan', 'keterangan', 'trim|required');
    $this->form_validation->set_rules('kode_lokasi', 'kode lokasi', 'trim|required');

    //$this->form_validation->set_rules('tanggal_transaksi', 'Tanggal Transaksi', 'trim|required|callback_max_year');
    //$this->form_validation->set_rules('nomor_transaksi', 'Nomor Transaksi', 'trim|required');
    //$this->form_validation->set_rules('tanggal_pembelian', 'Tanggal Pembelian', 'trim|required|callback_max_year');
   // $this->form_validation->set_rules('tanggal_perolehan', 'Tanggal Perolehan', 'trim|required|callback_max_year');

   // $this->form_validation->set_rules('id_ruang', 'id_kib_a', 'trim');
    $this->form_validation->set_error_delimiters('<span class="text-danger">', '</span>');
    $this->form_validation->set_message('required', ' %s harus di isi.');
  }

  public function _rules_update()
  {
   $this->form_validation->set_rules('nama_ruang', 'nama ruang', 'trim|required');
    $this->form_validation->set_rules('lantai', 'Lantai', 'trim|required');
    // $this->form_validation->set_rules('nomor_register', 'nomor register', 'trim|required');
   // $this->form_validation->set_rules('luas', 'luas', 'trim|required');
   // $this->form_validation->set_rules('tahun_pengadaan', 'tahun pengadaan', 'trim|required|callback_max_year');
    $this->form_validation->set_rules('letak_alamat', 'letak alamat', 'trim|required');
    $this->form_validation->set_rules('status_ruang', 'status ruang', 'trim|required');
    //$this->form_validation->set_rules('sertifikat_tanggal', 'sertifikat tanggal', 'trim|required|callback_max_year');
    $this->form_validation->set_rules('nama_gedung', 'nama gedung', 'trim|required');
    //$this->form_validation->set_rules('penggunaan', 'penggunaan', 'trim|required');
    // $this->form_validation->set_rules('asal_usul', 'asal usul', 'trim|required');
    //$this->form_validation->set_rules('rekening', 'Kode Rekening', 'trim|required');
    //$this->form_validation->set_rules('harga', 'harga', 'trim|required');
   // $this->form_validation->set_rules('keterangan', 'keterangan', 'trim|required');
    $this->form_validation->set_rules('kode_lokasi', 'kode lokasi', 'trim|required');

    //$this->form_validation->set_rules('tanggal_transaksi', 'Tanggal Transaksi', 'trim|required|callback_max_year');
    //$this->form_validation->set_rules('nomor_transaksi', 'Nomor Transaksi', 'trim|required');
    //$this->form_validation->set_rules('tanggal_pembelian', 'Tanggal Pembelian', 'trim|required|callback_max_year');
   // $this->form_validation->set_rules('tanggal_perolehan', 'Tanggal Perolehan', 'trim|required|callback_max_year');

   // $this->form_validation->set_rules('id_ruang', 'id_kib_a', 'trim');
    $this->form_validation->set_error_delimiters('<span class="text-danger">', '</span>');
    $this->form_validation->set_message('required', ' %s harus di isi.');
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

  /* End of file Kib_a.php */
  /* Location: ./application/controllers/Kib_a.php */
  /* Please DO NOT modify this information : */
  /* Generated by Harviacode Codeigniter CRUD Generator 2018-08-07 08:12:54 */
  /* http://harviacode.com */
