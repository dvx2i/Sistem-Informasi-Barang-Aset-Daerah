<?php

if (!defined('BASEPATH')) exit('No direct script access allowed');

class Reklas_kode extends MX_Controller
{
  function __construct()
  {
    parent::__construct();
    $this->global_model->cek_hak_akses('100'); //reklas kode
    $this->load->model('reklas_kode_model');
    // $this->load->model('global_mutasi_model');
    $this->load->library('form_validation');
    $this->load->library('datatables');
    $this->load->helper('my_global');
    $this->load->library('Global_library');
    $this->load->model('Global_reklas_model', 'reklas_model');
  }

  public function index($mode = null)
  {
    $data['css'] = array(
      'assets/datatables/dataTables.bootstrap.css',
    );

    $data['js'] = array(
      'assets/datatables/jquery.dataTables.js',
      'assets/datatables/dataTables.bootstrap.js'
    );
    if ($mode == 'batal_entri') {
      $data_reklas = $this->session->userdata('data_reklas');
      // die(json_encode($data_reklas['id_reklas_kode']));
      // die(json_encode($data_reklas['id_kib_tujuan']));
      if ($data_reklas['id_kib_tujuan'] == null) {
        $this->delete($data_reklas['id_reklas_kode']);
      }
    }


    $data['content'] = $this->load->view('reklas_kode/list', $data, true);
    $data['breadcrumb'] = array(
      array('label' => 'Reklas', 'url' => '#', 'icon' => 'fa fa-pie-chart', 'li_class' => ''),
      array('label' => 'Reklas Kode', 'url' => '#', 'icon' => '', 'li_class' => 'active'),
      // array('label' => 'Buat', 'url' => '#','icon'=>'', 'li_class'=>'active'),
    );
    $this->load->view('template', $data);
  }


  /*
  public function detail($id_mutasi)
  {
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
*/

  public function json()
  {
    header('Content-Type: application/json');
    echo $this->reklas_kode_model->json();
  }

  /*
  public function json_detail($id_mutasi)
  {
    header('Content-Type: application/json');
    echo $this->pengajuan_model->json_detail($id_mutasi);
  }
*/

  public function create()
  {
    $data = array(
      'button' => 'Buat',
      'action' => base_url('reklas/reklas_kode/create_action'),
      'id_reklas_kode' => set_value('id_reklas_kode'),
      'tanggal' => set_value('tanggal'),
      'kode_jenis' => set_value('kode_jenis'),
      'id_kib' => set_value('id_kib'),


      'kode_kelompok_tujuan' => set_value('kode_kelompok_tujuan'),
      'kode_jenis_tujuan' => set_value('kode_jenis_tujuan'),
      'kode_objek' => set_value('kode_objek'),
      'kode_rincian_objek' => set_value('kode_rincian_objek'),
      'kode_sub_rincian_objek' => set_value('kode_sub_rincian_objek'),
      'kode_sub_sub_rincian_objek' => set_value('kode_sub_sub_rincian_objek'),
      'kode_barang' => set_value('kode_barang'),
      'nama_barang' => set_value('nama_barang'),
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
      "assets/js/reklas.js",
      'assets/adminlte/plugins/iCheck/icheck.min.js',
    );

    $data['list_kib'] = $this->global_model->kode_jenis;

    $data['pengguna'] = $this->global_model->get_pengguna();
    // die(json_encode($data['pengguna']));
    // if (!empty($data['id_pengguna']))
    //   $data['lokasi'] = $this->pengajuan_model->get_lokasi($data['id_pengguna']);

    $data['list_kelompok'] = $this->reklas_kode_model->get_list_kelompok();

    //jenis
    $kode = $this->global_library->explode_kode_barang(set_value('kode_kelompok_tujuan'));
    $data['jenis'] = $this->reklas_model->get_jenis($kode['kode_kelompok']);

    // objek
    $kode = $this->global_library->explode_kode_barang(set_value('kode_kelompok_tujuan'));
    $data['objek'] = $this->reklas_model->get_objek($kode['kode_kelompok'], set_value('kode_jenis'));
    // die(set_value('kode_kelompok_tujuan') . '====' . set_value('kode_jenis'));


    // rincian_objek
    $kode = $this->global_library->explode_kode_barang(set_value('kode_objek'));
    $data['rincian_objek'] = $this->reklas_model->get_rincian_objek($kode);

    // sub_rincian_objek
    $kode = $this->global_library->explode_kode_barang(set_value('kode_rincian_objek'));
    $data['sub_rincian_objek'] = $this->reklas_model->get_sub_rincian_objek($kode);

    // sub_sub_rincian_objek
    $kode = $this->global_library->explode_kode_barang(set_value('kode_sub_rincian_objek'));
    $data['sub_sub_rincian_objek'] = $this->reklas_model->get_sub_sub_rincian_objek($kode);


    $data['content'] = $this->load->view('reklas_kode/form', $data, true);
    $data['breadcrumb'] = array(
      array('label' => 'Reklas', 'url' => '#', 'icon' => 'fa fa-pie-chart', 'li_class' => ''),
      array('label' => 'Reklas Kode', 'url' => '#', 'icon' => '', 'li_class' => ''),
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
      $id_lokasi_lama = $this->session->userdata('session')->id_kode_lokasi;
      /*
      reklas_barang
      reklas_aset_lainya
      reklas_jenis/entri/kapitalisasi
      reklas_kapitalisasi
      */
      $idkib = $this->input->post('id_kib', TRUE);
      $kode_jenis = $this->input->post('kode_jenis', TRUE);
      if($kode_jenis == '5.04'){
        $kode_jenis = $this->input->post('kode_jenis_lainnya'.$idkib, TRUE);
      }
      
      // if($this->session->userdata('session')->id_upik == 'jss-a7324'){
      //   die($kode_jenis);
      // }
      $kib_detail = $this->reklas_kode_model->get_kib_by_jenis_id($kode_jenis, $this->input->post('id_kib', TRUE));
      
      $kode_kelompok_tujuan = $this->input->post('kode_kelompok_tujuan', TRUE);
      $kode_jenis_tujuan = $this->input->post('kode_jenis_tujuan', TRUE);
      $jenis_reklas_post = $this->input->post('jenis_reklas', TRUE);

      $jenis_tujuan_obj = explode('.', $kode_jenis_tujuan);

      $jenis_reklas = null;
      if ($kode_kelompok_tujuan == '1.3.') {
        if (!empty($jenis_reklas_post) == 'kapitalisasi'){
          // $jenis_reklas = $jenis_reklas_post; 
          $jenis_reklas = 'kapitalisasi'; // jika checkbox kapitalisasi checked
          $id_kib_tujuan = NULL;
        }
        else if ($kode_jenis == $jenis_tujuan_obj[2]) {
          $jenis_reklas = 'reklas_barang'; // dalam kelompok aset tetap dan jenis yg sama
          $id_kib_tujuan= $this->input->post('id_kib', TRUE);
        }else if ($kode_jenis != $jenis_tujuan_obj[2]){
          $jenis_reklas = 'reklas_jenis'; // dalam kelompok aset tetap dan jenis berbeda (entri ulang)
          $id_kib_tujuan = NULL;
        }
      } elseif ($kode_kelompok_tujuan == '1.5.') {
        $jenis_reklas = 'reklas_aset_lainya'; // beda kelompok
        $id_kib_tujuan= $this->input->post('id_kib', TRUE);
      }

      $data = array(
        'tanggal' => date('Y-m-d', tgl_inter($this->input->post('tanggal', TRUE))),
        'id_kode_lokasi' => $kib_detail['id_kode_lokasi'], // bisa di ambil dari id kib
        'kode_jenis' => $kode_jenis,
        'id_kib' => $this->input->post('id_kib', TRUE),
        'id_kib_tujuan' => $id_kib_tujuan,
        'id_kode_barang' => $kib_detail['id_kode_barang'],
        'kode_kelompok_tujuan' => $this->input->post('kode_kelompok_tujuan', TRUE),
        'kode_jenis_tujuan' => $this->input->post('kode_jenis_tujuan', TRUE),
        'id_kode_barang_tujuan' => $this->global_model->get_kode_barang($this->input->post('kode_barang', TRUE))->id_kode_barang,
        'jenis_reklas' => $jenis_reklas,
        'status_validasi' => '1',
        'created_at' => date('Y-m-d H:i:s'),
        'updated_at' => date('Y-m-d H:i:s'),
      );

      /*
      entri : 
        1.jenis reklas <> 'kapitalisasi' dan
        2. kode jenis awal dan tujuan berbeda
      
      */
      // die(json_encode($data));
      $this->db->trans_start();
      $data['id_reklas_kode'] = $this->reklas_kode_model->insert($data);

      $this->global_model->_logs($menu = 'reklas', $sub_menu = 'reklas_kode', $tabel_name = 'tbl_reklas_kode', $action_id = null, $action = 'insert', $data = $data, $feature = 'create_action');
      //($menu='location', $sub_menu='set_location', $tabel_name='tbl_kode_location', $action_id=$session->id_user, $action='set', $data=$data, $feature);
      $this->db->trans_complete();
      if ($jenis_reklas == 'reklas_jenis') { // ENTRI//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
        // $this->session->set_flashdata('data_reklas', $data);
        $this->session->set_userdata('data_reklas', $data);
        if ($jenis_tujuan_obj[2] == '01')
          redirect(base_url('kib_a/create'));
        else if ($jenis_tujuan_obj[2] == '02')
          redirect(base_url('kib_b/create'));
        else if ($jenis_tujuan_obj[2] == '03')
          redirect(base_url('kib_c/create'));
        else if ($jenis_tujuan_obj[2] == '04')
          redirect(base_url('kib_d/create'));
        else if ($jenis_tujuan_obj[2] == '05')
          redirect(base_url('kib_e/create'));
        else if ($jenis_tujuan_obj[2] == '06')
          redirect(base_url('kib_f/create'));
      } //++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
      elseif ($jenis_reklas == 'kapitalisasi') { // ENTRI
        // $this->session->set_flashdata('data_reklas', $data);
        $this->session->set_userdata('data_reklas', $data);
        redirect(base_url('kapitalisasi/pengajuan/create'));
      }


      $this->session->set_flashdata('message', 'Berhasil membuat pengajuan.');
      redirect(base_url('reklas/reklas_kode'));
    }
  }


  public function update_entri($id_reklas)
  {
    $row = $this->global_model->row_get_where('tbl_reklas_kode', array('id_reklas_kode' => $id_reklas,));
    //$this->reklas_kode_model->get_by_id($id_reklas);
    if ($row) {
      // $data = array(
      //   'id_reklas_kode' => $row->id_reklas_kode,
      //   'tanggal' => date('Y-m-d', tgl_inter($this->input->post('tanggal', TRUE))),
      //   'id_kode_lokasi' => $kib_detail['id_kode_lokasi'], // bisa di ambil dari id kib
      //   'kode_jenis' => $this->input->post('kode_jenis', TRUE),
      //   'id_kib' => $this->input->post('id_kib', TRUE),
      //   'id_kode_barang' => $kib_detail['id_kode_barang'],
      //   'kode_kelompok_tujuan' => $this->input->post('kode_kelompok_tujuan', TRUE),
      //   'kode_jenis_tujuan' => $this->input->post('kode_jenis_tujuan', TRUE),
      //   'id_kode_barang_tujuan' => $this->global_model->get_kode_barang($this->input->post('kode_barang', TRUE))->id_kode_barang,
      //   'jenis_reklas' => $jenis_reklas,
      //   'status_validasi' => '1',
      //   // 'created_at' => date('Y-m-d H:i:s'),
      //   'updated_at' => date('Y-m-d H:i:s'),
      // );

      $this->session->set_userdata('data_reklas', $row);
      $jenis_tujuan_obj = explode('.', $row['kode_jenis_tujuan']);
      if (!empty($row['id_kib_tujuan'])) {
        if ($jenis_tujuan_obj[2] == '01')
          redirect(base_url('kib_a/update/' . $row['id_kib_tujuan']));
        else if ($jenis_tujuan_obj[2] == '02')
          redirect(base_url('kib_b/update/' . $row['id_kib_tujuan']));
        else if ($jenis_tujuan_obj[2] == '03')
          redirect(base_url('kib_c/update/' . $row['id_kib_tujuan']));
        else if ($jenis_tujuan_obj[2] == '04')
          redirect(base_url('kib_d/update/' . $row['id_kib_tujuan']));
        else if ($jenis_tujuan_obj[2] == '05')
          redirect(base_url('kib_e/update/' . $row['id_kib_tujuan']));
        else if ($jenis_tujuan_obj[2] == '06')
          redirect(base_url('kib_f/update/' . $row['id_kib_tujuan']));
      } else {
        if ($jenis_tujuan_obj[2] == '01')
          redirect(base_url('kib_a/create'));
        else if ($jenis_tujuan_obj[2] == '02')
          redirect(base_url('kib_b/create'));
        else if ($jenis_tujuan_obj[2] == '03')
          redirect(base_url('kib_c/create'));
        else if ($jenis_tujuan_obj[2] == '04')
          redirect(base_url('kib_d/create'));
        else if ($jenis_tujuan_obj[2] == '05')
          redirect(base_url('kib_e/create'));
        else if ($jenis_tujuan_obj[2] == '06')
          redirect(base_url('kib_f/create'));
      }
    }
  }

  /* UNTUK KAPITALISASI UPDATE NUNGGU PERMINTAAN DARI ASET */
  /*  public function update_kapitalisasi($id_reklas)
  {
    $row = $this->global_model->row_get_where('tbl_reklas_kode', array('id_reklas_kode' => $id_reklas,));
    //$this->reklas_kode_model->get_by_id($id_reklas);
    if ($row) {
      $this->session->set_userdata('data_reklas', $row);
      $jenis_tujuan_obj = explode('.', $row['kode_jenis_tujuan']);
      if (!empty($row['id_kib_tujuan'])) {
        // redirect(base_url('kib_a/update/' . $row['id_kib_tujuan']));
        redirect(base_url('kapitalisasi/pengajuan/form_kapitalisasi/$kode_jenis/$id_kib'));
      } else {
        redirect(base_url('kapitalisasi/pengajuan/create'));
      }
    }
  }
*/
  public function delete($id)
  {
    $row = $this->reklas_kode_model->get_by_id($id);
    // die(json_encode($row));
    if ($row) {
      $jenis_tujuan_obj = explode('.', $row->kode_jenis_tujuan);
      $table = null;
      $where_data = null;
      if ($row->id_kib_tujuan) {
        if ($row->jenis_reklas == 'reklas_jenis') {
          if ($jenis_tujuan_obj[2] == '01') {
            $table = 'tbl_kib_a';
            $where_data = array('id_kib_a' => $row->id_kib_tujuan);
          } else if ($jenis_tujuan_obj[2] == '02') {
            $table = 'tbl_kib_b';
            $where_data = array('id_kib_b' => $row->id_kib_tujuan);
          } else if ($jenis_tujuan_obj[2] == '03') {
            $table = 'tbl_kib_c';
            $where_data = array('id_kib_c' => $row->id_kib_tujuan);
          } else if ($jenis_tujuan_obj[2] == '04') {
            $table = 'tbl_kib_d';
            $where_data = array('id_kib_d' => $row->id_kib_tujuan);
          } else if ($jenis_tujuan_obj[2] == '05') {
            $table = 'tbl_kib_e';
            $where_data = array('id_kib_e' => $row->id_kib_tujuan);
          } else if ($jenis_tujuan_obj[2] == '06') {
            $table = 'tbl_kib_f';
            $where_data = array('id_kib_f' => $row->id_kib_tujuan);
          }
          $this->reklas_kode_model->delete_kib($table, $where_data);
        } elseif ($row->jenis_reklas == 'kapitalisasi') {
          $table = 'tbl_kapitalisasi';
          $where_data = array('id_kapitalisasi' => $row->id_kib_tujuan);
          $this->reklas_kode_model->delete_kib($table, $where_data);
        }
      }
      $this->reklas_kode_model->delete($id);
      $this->global_model->_logs($menu = 'reklas', $sub_menu = 'reklas_kode', $tabel_name = 'tbl_reklas_kode', $action_id = $id, $action = 'delete', $data = $row, $feature = 'delete');
      //($menu='location', $sub_menu='set_location', $tabel_name='tbl_kode_location', $action_id=$session->id_user, $action='set', $data=$data, $feature);
      $this->session->set_flashdata('message', 'Berhasil menghapus pengajuan Reklas');
      redirect(base_url('reklas/reklas_kode'));
    } else {
      $this->session->set_flashdata('message', 'Record Not Found');
      redirect(base_url('reklas/reklas_kode'));
    }
  }

  /*
  public function get_lokasi()
  {
    $lokasi = $this->pengajuan_model->get_lokasi($this->input->post('id_pengguna'));

    $data['option'] = '<option value="">Silahkan Pilih</option>';
    foreach ($lokasi as $key => $value) {
      $data['option'] .= '<option  value="' . $value['id_kode_lokasi'] . '">' . $value['kode_lokasi'] . ' - ' . $value['instansi'] . '</option>';
    }
    echo json_encode($data);
  }
  */

  function get_barang_lainya()
  {
    $kode_jenis = $this->input->post('kode_jenis');
    $id_kode_jenis_aset_lainya = $this->input->post('id_kode_jenis_aset_lainya');
    $data = $this->reklas_kode_model->get_barang_aset_lainya($kode_jenis, $id_kode_jenis_aset_lainya);
    echo json_encode($data);
  }

  function get_list_kib()
  {
    $kode_jenis = $this->input->post('kode_jenis');

    if($kode_jenis == '01'){
      $this->load->view('reklas_kode/list_kib_a');
    }
    if($kode_jenis == '02'){
      $this->load->view('reklas_kode/list_kib_b');
    }
    if($kode_jenis == '03'){
      $this->load->view('reklas_kode/list_kib_c');
    }
    if($kode_jenis == '04'){
      $this->load->view('reklas_kode/list_kib_d');
    }
    if($kode_jenis == '05'){
      $this->load->view('reklas_kode/list_kib_e');
    }
    if($kode_jenis == '06'){
      $this->load->view('reklas_kode/list_kib_f');
    }
    if($kode_jenis == '5.03'){
      $this->load->view('reklas_kode/list_kib_atb');
    }
    if($kode_jenis == '5.04'){
      $this->load->view('reklas_kode/list_kib_lainnya');
    }
  }

  public function _rules()
  {
    $this->form_validation->set_rules('tanggal', 'tanggal', 'trim|required');
    $this->form_validation->set_rules('kode_jenis', 'Jenis', 'trim|required');
    $this->form_validation->set_rules('kode_kelompok_tujuan', 'Kode Kelompok', 'trim|required');
    $this->form_validation->set_rules('kode_barang', 'Kode Barang', 'trim|required');
    $this->form_validation->set_rules('id_kib', 'Pilih Barang Awal', 'trim|required');
    // $this->form_validation->set_rules('id_pengajuan', 'id_pengajuan', 'trim');
    $this->form_validation->set_error_delimiters('<span class="text-danger">', '</span>');
    $this->form_validation->set_message('required', ' Objek %s harus di isi.');
  }

  /*
  public function create_kib_a()
  {
    $pemilik = $this->global_model->get_pemilik_by_id($this->default_pemilik);
    $kode_lokasi = remove_star($pemilik->kode . '.' . $this->session->userdata('session')->kode_lokasi);
    $data = array(
      'button' => 'Buat',
      'menu' => $this->global_model->kode_jenis[$this->kode_jenis]['nama'],
      'action' => base_url('kib_a/create_action'),
      'id_kib_a' => set_value('id_kib_a'),
      'sumber_dana' => set_value('sumber_dana'),
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
      'kib_f' => set_value('kib_f', 1),
      'latitute' => set_value('latitute'),
      'longitute' => set_value('longitute'),
      'validasi' => set_value('validasi'),
    );

    $data['css'] = array(
      "assets/bootstrap/bootstrap-datepicker-master/dist/css/bootstrap-datepicker.min.css",
      "assets/jquery/select2-4.0.6-rc.1/dist/css/select2.min.css",
      "assets/jquery/select2-bootstrap-theme-master/dist/select2-bootstrap.min.css",
      'assets/adminlte/plugins/iCheck/all.css',
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

    //sumber dana
    $data['lis_sumber_dana'] = $this->global_model->get_sumber_dana();
    //kode rekening
    $data['lis_rekening'] = $this->global_model->get_rekening($data['sumber_dana']);

    //pemilik
    $data['pemilik'] = $this->global_model->get_pemilik();
    // objek
    $data['objek'] = $this->global_model->get_objek($this->kode_jenis);

    $kode = $this->global_library->explode_kode_barang(set_value('kode_barang'));

    // rincian_objek
    $data['rincian_objek'] = $this->global_model->get_rincian_objek($kode['kode_jenis'], $kode['kode_objek']);
    // sub_rincian_objek
    $data['sub_rincian_objek'] = $this->global_model->get_sub_rincian_objek($kode['kode_jenis'], $kode['kode_objek'], $kode['kode_rincian_objek']);
    // sub_sub_rincian_objek 
    //06 may 2019
    // $data['sub_sub_rincian_objek'] = $this->global_model->get_sub_sub_rincian_objek($kode['kode_jenis'], $kode['kode_objek'], $kode['kode_rincian_objek'], $kode['kode_sub_rincian_objek']);

    $data['master_hak_tanah'] = $this->global_model->get_master_hak_tanah();
    $data['master_penggunaan'] = $this->global_model->get_master_penggunaan();
    $data['master_asal_usul'] = $this->global_model->get_master_asal_usul();
    $data['master_kondisi_bangunan'] = $this->global_model->get_master_kondisi_bangunan();

    $data["content"] = $this->load->view('kib_a/tbl_kib_a_form', $data, TRUE);
    $data['breadcrumb'] = array(
      array('label' => 'Data KIB', 'url' => '#', 'icon' => 'fa fa-pie-chart', 'li_class' => ''),
      array('label' => 'KIB A', 'url' => '#', 'icon' => '', 'li_class' => ''),
      array('label' => 'Buat', 'url' => '#', 'icon' => '', 'li_class' => 'active'),
    );
    $this->load->view('template', $data);
  }

  public function create_action_kib_a()
  {
    $this->_rules();

    if ($this->form_validation->run() == FALSE) {
      $this->create();
    } else {
      // $data['kode_barang_kib_f'] = $this->global_model->get_kode_barang_kib_f('1');

      $data = array(
        'id_kode_barang' => $this->global_model->get_kode_barang($this->input->post('kode_barang', TRUE))->id_kode_barang,
        'id_pemilik' => $this->input->post('status_pemilik', TRUE),
        'id_kode_lokasi' => $this->global_model->get_kode_lokasi($this->input->post('kode_lokasi', TRUE))->id_kode_lokasi,
        'nama_barang' => $this->input->post('nama_barang', TRUE),
        'kode_barang' => $this->input->post('kode_barang', TRUE),
        'kondisi' => $this->input->post('kondisi', TRUE),
        'luas' => to_float($this->input->post('luas', TRUE)),
        'tahun_pengadaan' => $this->input->post('tahun_pengadaan', TRUE),
        'letak_alamat' => $this->input->post('letak_alamat', TRUE),
        'status_hak' => $this->input->post('status_hak', TRUE),
        'sertifikat_tanggal' => date('Y-m-d', tgl_inter($this->input->post('sertifikat_tanggal', TRUE))),
        'sertifikat_nomor' => $this->input->post('sertifikat_nomor', TRUE),
        'penggunaan' => $this->input->post('penggunaan', TRUE),
        'asal_usul' => $this->input->post('asal_usul', TRUE),
        'harga' => str_replace('.', '', $this->input->post('harga', TRUE)),
        'keterangan' => $this->input->post('keterangan', TRUE),
        'deskripsi' => $this->input->post('deskripsi', TRUE),
        'kode_lokasi' => $this->input->post('kode_lokasi', TRUE),
        'tanggal_transaksi' => date('Y-m-d', tgl_inter($this->input->post('tanggal_transaksi', TRUE))),
        'nomor_transaksi' => $this->input->post('nomor_transaksi', TRUE),
        'tanggal_pembelian' => date('Y-m-d', tgl_inter($this->input->post('tanggal_pembelian', TRUE))),
        'tanggal_perolehan' => date('Y-m-d', tgl_inter($this->input->post('tanggal_perolehan', TRUE))),
        // 'kib_f' => $this->input->post('kib_f', TRUE) ? '2' : '1',
        // 'kode_barang_f' => $data['kode_barang_kib_f']->kode_barang,
        'latitute' => $this->input->post('latitute', TRUE),
        'longitute' => $this->input->post('longitute', TRUE),
        'id_sumber_dana' => set_null($this->input->post('sumber_dana', TRUE)),
        'id_rekening' => set_null($this->input->post('rekening', TRUE)),

        'created_at' => date('Y-m-d H:i:s'),
        'updated_at' => date('Y-m-d H:i:s'),
      );
      // die(json_encode($data));
      $jumlah_barang = str_replace('.', '', $this->input->post('jumlah_barang', TRUE));

      if ($jumlah_barang > 1) {
        for ($jumlah_barang; $jumlah_barang > 0; $jumlah_barang--) {
          $this->Kib_a_model->insert($data); //memang di awal , supaya no register pas
          $this->global_model->_logs($menu = 'data_kib', $sub_menu = 'kib_a', $tabel_name = 'tbl_kib_a', $action_id = null, $action = 'insert', $data = $data);
          $data['created_at'] = date('Y-m-d H:i:s');
          $data['updated_at'] = date('Y-m-d H:i:s');
        }
      } else {
        $this->Kib_a_model->insert($data);
        $this->global_model->_logs($menu = 'data_kib', $sub_menu = 'kib_a', $tabel_name = 'tbl_kib_a', $action_id = null, $action = 'insert', $data = $data);
      }

      $this->session->set_flashdata('message', 'Create Record Success');
      redirect(base_url('kib_a'));
    }
  }*/
}
