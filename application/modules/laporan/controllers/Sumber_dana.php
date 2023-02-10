<?php

if (!defined('BASEPATH')) exit('No direct script access allowed');

class Sumber_dana extends MX_Controller
{
  protected $kode_jenis = '02';
  function __construct()
  {
    parent::__construct();
    $this->global_model->cek_hak_akses('108'); //SEMENTARA DI LOCK (kasih tnd *) SDG DIPAKAI GENERATE PENYUSUTAN 2020
    $this->load->model('Sumber_dana_model');
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
      'assets/datatables/dataTables.bootstrap.css',
      "assets/jquery/select2-4.0.6-rc.1/dist/css/select2.min.css",
      "assets/jquery/select2-bootstrap-theme-master/dist/select2-bootstrap.min.css",
      'assets/sweetalert/sweetalert.css',
    );

    $data['js'] = array(
      'assets/datatables/jquery.dataTables.js',
      'assets/datatables/dataTables.bootstrap.js',
      "assets/jquery/select2-4.0.6-rc.1/dist/js/select2.min.js",
      'assets/sweetalert/dist/sweetalert.min.js',
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
    $data['menu'] = 'Sumber Dana';
    $data['content'] = $this->load->view('sumber_dana/list', $data, TRUE);
    $data['breadcrumb'] = array(
      array('label' => 'Sumber Dana', 'url' => '#', 'icon' => 'fa fa-pie-chart', 'li_class' => ''),
      array('label' => 'Sumber Dana', 'url' => '#', 'icon' => '', 'li_class' => 'active'),
    );
    $this->load->view('template', $data);
  }

  public function json()
  {
    header('Content-Type: application/json');
    echo $this->Sumber_dana_model->json();
  }

  public function rekap()
  {
    $data['css'] = array(
      'assets/datatables/dataTables.bootstrap.css',
      'assets/nested-datatables-master/dist/nested.tables.css',
      "assets/jquery/select2-4.0.6-rc.1/dist/css/select2.min.css",
      "assets/jquery/select2-bootstrap-theme-master/dist/select2-bootstrap.min.css",
      'assets/sweetalert/sweetalert.css',
    );

    $data['js'] = array(
      'assets/datatables/jquery.dataTables.js',
      'assets/datatables/dataTables.bootstrap.js',
      "assets/jquery/select2-4.0.6-rc.1/dist/js/select2.min.js",
      'assets/sweetalert/dist/sweetalert.min.js',
      'assets/nested-datatables-master/dist/nested.tables.min.js',
    );
    $session = $this->session->userdata('session');
    // die(json_encode(substr($session->kode_lokasi, -1)));
    $data['show_lokasi'] = false;
    if (substr($session->kode_lokasi, -1) == "*")
    $data['show_lokasi'] = true;
    $data['menu'] = 'Sumber Dana';
    $data['content'] = $this->load->view('sumber_dana/list_rekap', $data, TRUE);
    $data['breadcrumb'] = array(
      array('label' => 'Rekap Sumber Dana', 'url' => '#', 'icon' => 'fa fa-pie-chart', 'li_class' => ''),
      array('label' => 'Rekap Sumber Dana', 'url' => '#', 'icon' => '', 'li_class' => 'active'),
    );
    $this->load->view('template', $data);
  }

  function sinkron()
  {
    $data['bulan'] = $this->input->post('bulan');
    $data['tahun'] = $this->input->post('tahun');
    $data['tahap'] = $this->input->post('tahap');
    
    $curl = curl_init();

    curl_setopt_array($curl, array(
      CURLOPT_URL => 'https://simpelaporan.jogjakota.go.id/api/aset/belanjamodal?bulan='.$data['bulan'].'&tahun='.$data['tahun'].'&tahap='.$data['tahap'],
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_ENCODING => '',
      CURLOPT_MAXREDIRS => 10,
      CURLOPT_TIMEOUT => 0,
      CURLOPT_FOLLOWLOCATION => true,
      CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
      CURLOPT_CUSTOMREQUEST => 'POST',
      CURLOPT_HTTPHEADER => array(
        'Cookie: cookiesession1=4866F048P4GFTOSZ3JC84SITULBJ2F39'
      ),
    ));

    $response = curl_exec($curl);

    curl_close($curl);
    
			$this->session->set_flashdata('message', 'Berhasil Sinkronisasi Data');
			redirect(site_url('laporan/sumber_dana'));

  }

  /*
  public function create_action()
  {

    if(is_array($this->input->post('id_kuasa_pengguna'))){
      
      $this->create_action_skpd();
    }
      $session = $this->session->userdata('session');
      $data = array(
        'id_pemilik' => $this->input->post('id_pemilik', TRUE),
        'id_kode_lokasi' => $this->input->post('id_kuasa_pengguna'),
        'id_pengguna' => $this->input->post('id_pengguna', TRUE),
        'bulan' => $this->input->post('bulan', TRUE),
        'tahun' => $this->input->post('tahun', TRUE),
        'locked_admin' => 1,
        'locked_skpd' => 0,
        'created_by' => $session->id_user,
        'updated_by' => $session->id_user,
        'created_at' => date('Y-m-d H:i:s'),
        'updated_at' => date('Y-m-d H:i:s'),
      );

      $last_data = $this->Sumber_dana_model->get_last_data($data);
      // bulan harus urut saat input
      if(!empty($last_data)){
        if($last_data[0]['bulan'] != 12){
          $next_month = $last_data[0]['bulan']+1;
          $next_year = $last_data[0]['tahun'];
        }else{
          $next_month = 1;
          $next_year = $last_data[0]['tahun']+1;
        }
      }else{
        $next_month = 1;
        $next_year = date('Y');
      }
      $this->db->trans_start();

      if($this->input->post('bulan') == $next_month && $this->input->post('tahun') == $next_year)
      {
       $new_data = $this->Sumber_dana_model->insert($data);

       if($this->input->post('id_kuasa_pengguna') != ''){
       $this->lock_skpd($new_data);
       }

       $this->db->trans_complete();

       if ($this->db->trans_status() === FALSE) {
        # Something went wrong.
        $this->db->trans_rollback();  
        echo json_encode(array('status' => false, 'message' => 'Gagal disimpan !'));
      
      } 
      else {
          # Committing data to the database.
          $this->db->trans_commit();
          echo json_encode(array('status' => true, 'message' => 'Berhasil Disimpan !'));
         
      }}else{
        echo json_encode(array('status' => false, 'message' => 'Sumber Dana Bulan '.bulan_indo($next_month).' Tahun '.$next_year.' Belum Diinput'));
      }
          
  }
  */

  public function create_action()
  {

      // $lokasi = $this->Sumber_dana_model->get_lokasi_by_pengguna($this->input->post('id_pengguna', TRUE));
      $lokasi = $this->input->post('id_kuasa_pengguna', TRUE);

    foreach ($lokasi as $key => $value) {

      $session = $this->session->userdata('session');
      $data = array(
        'id_pemilik' => $this->input->post('id_pemilik', TRUE),
        'id_kode_lokasi' => $value,
        'id_pengguna' => $this->input->post('id_pengguna', TRUE),
        'bulan' => $this->input->post('bulan', TRUE),
        'tahun' => $this->input->post('tahun', TRUE),
        'locked_admin' => 1,
        'locked_skpd' => 0,
        'created_by' => $session->id_user,
        'updated_by' => $session->id_user,
        'created_at' => date('Y-m-d H:i:s'),
        'updated_at' => date('Y-m-d H:i:s'),
      );

      $stock_opname = $this->Sumber_dana_model->get_by_lokasi($value, $data['bulan'], $data['tahun']);

      if ($stock_opname->num_rows() < 1) { // jika belum ada stock opname

        $last_data = $this->Sumber_dana_model->get_last_data($data);

        // bulan harus urut saat input
        if (!empty($last_data)) {
          if ($last_data[0]['bulan'] != 12) {
            $next_month = $last_data[0]['bulan'] + 1;
            $next_year = $last_data[0]['tahun'];
          } else {
            $next_month = 1;
            $next_year = $last_data[0]['tahun'] + 1;
          }
        } else {
          $next_month = 1;
          $next_year = date('Y');
        }
        $this->db->trans_start();

        if ($this->input->post('bulan') == $next_month && $this->input->post('tahun') == $next_year
        ) {
          $new_data = $this->Sumber_dana_model->insert($data);

          $this->lock_skpd($new_data);

          $this->db->trans_complete();

          if ($this->db->trans_status() === FALSE
          ) {
            # Something went wrong.
            $this->db->trans_rollback();
            echo json_encode(array('status' => false, 'message' => 'Gagal disimpan !'));
            exit;
            break;
          } else {
            # Committing data to the database.
            $this->db->trans_commit();
          }
        } else {
          echo json_encode(array('status' => false, 'message' => 'Sumber Dana Bulan ' . bulan_indo($next_month) . ' Tahun ' . $next_year . ' Belum Diinput'));

          exit;
          break;
        }
      }else{
        $stock_opname = $stock_opname->row();
        if($stock_opname->locked_admin == '0'){
          $this->lock_skpd($stock_opname->id);
        }
      }
    }

    echo json_encode(array('status' => true, 'message' => 'Berhasil Disimpan !'));
          
    exit;
  }

  public function lock()
  {
    $session = $this->session->userdata('session');
    $id = decrypt_url($this->input->post('id', TRUE));
    $stock_opname = $this->Sumber_dana_model->get_by_id($id);

    $locked = $this->Sumber_dana_model->get_unlocked($stock_opname->bulan, $stock_opname->tahun, $stock_opname->id_kode_lokasi);
    if ($locked < 1) {
      // die;
      $data['session'] = $this->session->userdata('session');
      $data['id_pemilik'] = $stock_opname->id_pemilik;
      $data['id_pengguna'] = $stock_opname->id_pengguna;
      $data['id_kode_lokasi'] = $stock_opname->id_kode_lokasi;
      $data['start_bulan'] = $stock_opname->bulan;
      $data['start_tahun'] = $stock_opname->tahun;
      $data['last_bulan'] = $stock_opname->bulan;
      $data['last_tahun'] = $stock_opname->tahun;
      $data['intra_ekstra'] = '00';


      $data_update = array(
        'updated_by' => $session->id_user,
        'updated_at' => date('Y-m-d H:i:s'),
      );

      if ($session->id_role == '1') {
        if($stock_opname->locked_admin == '0'){
          $set_stock_opname = $this->Sumber_dana_model->set_stock_opname($data); //lock hitung soalnya sebelumnya kebuka
        }
        $data_update['locked_admin'] = '2'; //lock uperadmin nga perlu hitung ulang
      } else {
        $set_stock_opname = $this->Sumber_dana_model->set_stock_opname($data); //lock skpd hitung ulang soalnya sebelumnya kebuka
        $data_update['locked_admin'] = '1';
      }

      $this->Sumber_dana_model->update($id, $data_update);

      echo json_encode(array('status' => true, 'message' => 'Berhasil Dikunci !'));
    } else {
      echo json_encode(array('status' => FALSE, 'message' => 'Stock Sebelum Bulan '.bulan_indo($stock_opname->bulan).' Belum Dikunci'));
    }
    
  }
  public function lock_skpd($id = null)
  {
    $session = $this->session->userdata('session');
    $stock_opname = $this->Sumber_dana_model->get_by_id($id);

    $locked = $this->Sumber_dana_model->get_unlocked($stock_opname->bulan, $stock_opname->tahun, $stock_opname->id_kode_lokasi);
    if ($locked < 1) {
      // die;
      $data['session'] = $this->session->userdata('session');
      $data['id_pemilik'] = $stock_opname->id_pemilik;
      $data['id_pengguna'] = $stock_opname->id_pengguna;
      $data['id_kode_lokasi'] = $stock_opname->id_kode_lokasi;
      $data['start_bulan'] = $stock_opname->bulan;
      $data['start_tahun'] = $stock_opname->tahun;
      $data['last_bulan'] = $stock_opname->bulan;
      $data['last_tahun'] = $stock_opname->tahun;
      $data['intra_ekstra'] = '00';

      $set_stock_opname = $this->Sumber_dana_model->set_stock_opname($data);

      $data_update = array(
        'updated_by' => $session->id_user,
        'updated_at' => date('Y-m-d H:i:s'),
        'locked_admin'=> '1'
      );

      $this->Sumber_dana_model->update($id, $data_update);

      return true;
    } else {
      echo json_encode(array('status' => FALSE, 'message' => 'Stock Sebelum Bulan '.bulan_indo($stock_opname->bulan).' Belum Dikunci'));
      exit;
      break;
    }
    
  }

  public function unlock()
  {
    $session = $this->session->userdata('session');
    $id = decrypt_url($this->input->post('id', TRUE));
    $stock_opname = $this->Sumber_dana_model->get_by_id($id);

    $locked = $this->Sumber_dana_model->get_locked($stock_opname->bulan, $stock_opname->tahun, $stock_opname->id_kode_lokasi);
    if ($locked < 1) {
      // die;

      $stock_opname = $this->Sumber_dana_model->get_by_id($id);
      $data['session'] = $this->session->userdata('session');

      $data_update = array(
        'updated_by' => $session->id_user,
        'updated_at' => date('Y-m-d H:i:s'),
      );

      if ($session->id_role == '1') {
        $data_update['locked_admin'] = '0';
      } else {
        if($stock_opname->locked_admin == '2'){
          echo json_encode(array('status' => FALSE, 'message' => 'Stock Telah Dikunci Oleh Administrator'));
          exit;
        }else{
          $data_update['locked_admin'] = '0';
        }
      }

      $this->Sumber_dana_model->update($id, $data_update);

      echo json_encode(array('status' => true, 'message' => 'Berhasil Buka Kunci !'));
    } else {
      echo json_encode(array('status' => FALSE, 'message' => 'Stock Setelah Bulan '.bulan_indo($stock_opname->bulan).' Telah Dikunci'));
    }
    
  }

  public function delete($id)
  {
    $row = $this->Sumber_dana_model->get_by_id($id);
    // bila sudah di validasi dan bukan super admin
    // if ($row->validasi == '2' and $this->session->userdata('session')->id_role != '1')
    if ($row->validasi == '2')
      redirect('dashboard');
    if ($row) {
      if ($row->validasi == '2')
        $this->global_model->insert_histori_kib('02', $id, 'delete_kib'); // 2 = KIB B

      $this->Sumber_dana_model->delete($id);
      $this->global_model->_logs($menu = 'data_kib', $sub_menu = 'kib_b', $tabel_name = 'tbl_kib_b', $action_id = $id, $action = 'delete', $data = $row);
      $this->session->set_flashdata('message', 'Delete Record Success');
      redirect(base_url('kib_b'));
    } else {
      $this->session->set_flashdata('message', 'Record Not Found');
      redirect(base_url('kib_b'));
    }
  }

  public function _rules()
  {
    $this->form_validation->set_rules('kode_barang', 'kode barang', 'trim|required');
    $this->form_validation->set_rules('nama_barang', 'nama barang', 'trim|required');
    // $this->form_validation->set_rules('nomor_register', 'nomor register', 'trim|required');
    $this->form_validation->set_rules('merk_type', 'merk type', 'trim|required');
    $this->form_validation->set_rules('ukuran_cc', 'ukuran cc', 'trim|required');
    $this->form_validation->set_rules('bahan', 'bahan', 'trim|required');
    $this->form_validation->set_rules('nomor_pabrik', 'nomor pabrik', 'trim|required');
    $this->form_validation->set_rules('nomor_rangka', 'nomor rangka', 'trim|required');
    $this->form_validation->set_rules('nomor_mesin', 'nomor mesin', 'trim|required');
    $this->form_validation->set_rules('nomor_polisi', 'nomor polisi', 'trim|required');
    $this->form_validation->set_rules('nomor_bpkb', 'nomor bpkb', 'trim|required');
    // $this->form_validation->set_rules('asal_usul', 'asal usul', 'trim|required');
    $this->form_validation->set_rules('rekening', 'Kode Rekening', 'trim|required');
    $this->form_validation->set_rules('harga', 'harga', 'trim|required');
    $this->form_validation->set_rules('keterangan', 'keterangan', 'trim|required');
    $this->form_validation->set_rules('kode_lokasi', 'kode lokasi', 'trim|required');

    $this->form_validation->set_rules('tanggal_transaksi', 'Tanggal Transaksi', 'trim|required|callback_max_year');
    $this->form_validation->set_rules('nomor_transaksi', 'Nomor Transaksi', 'trim|required');
    $this->form_validation->set_rules('tanggal_pembelian', 'Tanggal Pembelian', 'trim|required|callback_max_year');
    $this->form_validation->set_rules('tanggal_perolehan', 'Tanggal Perolehan', 'trim|required|callback_max_year');

    $this->form_validation->set_rules('id_kib_b', 'id_kib_b', 'trim');
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

/* End of file Kib_b.php */
/* Location: ./application/controllers/Kib_b.php */
/* Please DO NOT modify this information : */
/* Generated by Harviacode Codeigniter CRUD Generator 2018-08-07 08:13:19 */
/* http://harviacode.com */
