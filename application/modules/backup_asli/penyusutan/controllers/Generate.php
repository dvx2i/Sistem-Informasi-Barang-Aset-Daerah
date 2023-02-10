<?php

if (!defined('BASEPATH')) exit('No direct script access allowed');

class Generate extends MX_Controller
{
  function __construct()
  {
    parent::__construct();
    $this->global_model->cek_hak_akses('41'); //generate
    $this->load->model('generate_model');
    $this->load->model('laporan_model');
    $this->load->helper('my_global');
    $this->load->library('datatables');
    $this->load->library('Global_library');
  }

  public function index()
  {
    $now = explode(' ', tgl_indo(date('Y-m-d')));
    $bulan_tahun = $now[1] . ' ' . $now[2];
    $tahun = $now[2];
    /*
    $date_1 = date('Y') . '-06-' . '01';
    $date_2 = date('Y') . '-12-' . '01';

    $arrayTanggal = array(
      date('t-m-Y', strtotime($date_1)),
      date('t-m-Y', strtotime($date_2)),
    );
    */
    // die(json_encode($arrayTanggal));
    $data = array(
      'bulan_tahun' => set_value('bulan_tahun', $bulan_tahun),
      // 'tahun' => set_value('tahun', $tahun),
      // 'list_tanggal' => $arrayTanggal,
      'tetap_proses' => '1',
    );

    $data['css'] = array(
      'assets/datatables/dataTables.bootstrap.css',
      "assets/jquery/select2-4.0.6-rc.1/dist/css/select2.css",
      "assets/jquery/select2-bootstrap-theme-master/dist/select2-bootstrap.min.css",
      "assets/bootstrap/bootstrap-datepicker-master/dist/css/bootstrap-datepicker.min.css",
    );
    $data['js'] = array(
      'assets/datatables/jquery.dataTables.js',
      'assets/datatables/dataTables.bootstrap.js',
      "assets/jquery/select2-4.0.6-rc.1/dist/js/select2.min.js",
      "assets/bootstrap/bootstrap-datepicker-master/dist/js/bootstrap-datepicker.min.js",
      "assets/bootstrap/bootstrap-datepicker-master/dist/locales/bootstrap-datepicker.id.min.js",
    );

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
    $data['show_lokasi'] = true;

    $data['content'] = $this->load->view('/generate/home', $data, true);
    $this->load->view('template', $data);
  }
  
  public function json()
  {
    header('Content-Type: application/json');
    echo $this->generate_model->json();
  }

  public function action()
  {

    $session = $this->session->userdata('session');
    $data = array(
      'bulan_tahun' => set_value('bulan_tahun', $this->input->post('bulan_tahun')),
      // 'tahun' => set_value('tahun', $this->input->post('tahun')),
      // 'tanggal' => set_value('tanggal', $this->input->post('tanggal')),
      'tetap_proses' => $this->input->post('tetap_proses'),
    );
    // die(json_encode($data));

    $data['css'] = array(
      'assets/datatables/dataTables.bootstrap.css',
      "assets/jquery/select2-4.0.6-rc.1/dist/css/select2.css",
      "assets/jquery/select2-bootstrap-theme-master/dist/select2-bootstrap.min.css",
      "assets/bootstrap/bootstrap-datepicker-master/dist/css/bootstrap-datepicker.min.css",
      'assets/sweetalert/sweetalert2.css',
    );
    $data['js'] = array(
      'assets/datatables/jquery.dataTables.js',
      'assets/datatables/dataTables.bootstrap.js',
      "assets/jquery/select2-4.0.6-rc.1/dist/js/select2.min.js",
      "assets/bootstrap/bootstrap-datepicker-master/dist/js/bootstrap-datepicker.min.js",
      "assets/bootstrap/bootstrap-datepicker-master/dist/locales/bootstrap-datepicker.id.min.js",
      'assets/sweetalert/sweetalert2.min.js',
    );

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
    $data['show_lokasi'] = true;
    // die((string)$this->input->post('bulan_tahun'));
    $tanggal_penyusutan = date('Y-m-d', tgl_inter("01 " . $this->input->post('bulan_tahun')));
    // $tahun_penyusutan = $this->input->post('bulan_tahun');
    // $tanggal = $this->input->post('tanggal');
    $tanggal_penyusutan = date("Y-m-t", strtotime($tanggal_penyusutan));

    $bulan = date('n', strtotime($tanggal_penyusutan));
    $tahun = date('Y', strtotime($tanggal_penyusutan));

    // $result_cek = $this->generate_model->cek_generate($tanggal_penyusutan);
    //die(json_encode($tanggal_penyusutan));
    // if ($result_cek and ($data['tetap_proses'] == '~')) {
    //   // die("halo");
    //   $data['tetap_proses'] = '2';
    //   $data['notiv'] = "
    //     swal({
    //       title: '!!!',
    //       text: 'Proses " . $this->input->post('tahun') . " sudah dilakukan',
    //       type: 'warning',
    //       // showCancelButton: true,
    //       confirmButtonColor: '#3085d6',
    //       cancelButtonColor: '#d33',
    //       confirmButtonText: 'Tetap Proses'
    //     }).then((result) => {
    //       if (result.value) {
    //         //location.reload();
    //         $('#submit').click();
    //       }
    //     });
    //   ";
    //   // die(json_encode($data));
    //   $data['content'] = $this->load->view('/generate/home', $data, true);
    //   $this->load->view('template', $data);
    // } else {
    // die();
    $lokasi = $this->input->post('id_kuasa_pengguna', TRUE);

    foreach ($lokasi as $key => $value) {
      $locked = $this->global_model->get_locked($bulan, $tahun, $lokasi_explode->id_kode_lokasi);

      if ($locked > 0 || $tahun == '2020') {
        $status = 'true';
        $this->generate_model->generate($session->id_user, $tanggal_penyusutan, $value);
      } else {
        $status = 'false';
        break;
      }
    }
    if ($status == 'true') {
      $data['tetap_proses'] = '1';
      $data['notiv'] = "
        swal({
          type: 'success',
          title: '!!!',
          text: 'Proses Berhasil',
        });
      ";
    } else {

      $data['tetap_proses'] = '1';
      $data['notiv'] = "
        swal({
          type: 'warning',
          title: '!!!',
          text: 'Belum Stock Opname Bulan " . bulan_indo($bulan) . " Tahun " . $tahun . "',
        });
      ";
    }

    $data['content'] = $this->load->view('/generate/home', $data, true);
    $this->load->view('template', $data);
  }
}
