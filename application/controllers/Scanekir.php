<?php

if (!defined('BASEPATH'))
exit('No direct script access allowed');

class Scanekir extends CI_Controller{
  function __construct(){
    parent::__construct();
    $this->load->helper('url');
    $this->load->model('Login_model');
	 $this->load->model('global_model');
   $this->load->library('Global_library');
    $this->load->helper('my_global');
    $this->load->model('kib_e/Kib_e_model','Kib_e_model');
    $this->load->model('kib_b/Kib_b_model','Kib_b_model');
	error_reporting(0);
  }

  /*public function index(){
	//$this->load->view('login');
	$this->prev();
  }
  */

  public function prev(){
	 // echo"bisa_login";die;
     //die(base_url());
   /* if (base_url() != "https://aset.jogjakota.go.id") {
      $id_jss = $_POST['username'];
      if ($id_jss) {
        $user = $this->Login_model->get_by_id_upik($id_jss);
        // die(json_encode($user));
        $menu = $this->Login_model->get_menu($user->id_role);
        $sub_menu = $this->Login_model->get_sub_menu($user->id_role);
        $array_menu = array();
        foreach ($menu as $key => $value) {
          $array_sub_menu = array();
          foreach ($sub_menu as $key2 => $value2) {
            if ($value->menu == $value2->menu) {
              array_push($array_sub_menu, json_decode(json_encode($value2)));
            }
  
          }
          array_push($array_menu,
                      array(
                        'id_hak_akses' => $key,
                        'name' => $value->menu,
                        'menu' => $array_sub_menu
                      )
                    );
        }
        // die(json_encode($array_menu));
        $this->session->set_userdata('menu',$array_menu);
        // die(json_encode($menu));
        $this->session->set_userdata('session',$user);
        $session = $this->session->userdata('session');
        $this->global_model->_logs($menu='login', $sub_menu='login', $tabel_name='tbl_user', $action_id=$session->id_user, $action='authentication', $data=$user);
        redirect(base_url('dashboard'));
      }else{
        $this->global_model->_logs($menu='login', $sub_menu='login', $tabel_name='tbl_user', $action_id=$session->id_user, $action='authentication', $data = array('status' => $response_object->msg, ));
        $this->session->set_flashdata('message', $response_object->msg);
        redirect('https://jss.jogjakota.go.id/');
      }
    }
    else {*/

        $this->sso();
//    }
  }

  function sso()
  {
      $this->load->library('curl');
      $this->load->library('encrypt');
	 
      // if ($this->input->cookie('jss_session', false)) {

      //     $decrypt = $this->encrypt->decode($this->input->cookie('jss_session', false), "diskominfo");

      //     $jsonuser = json_decode($decrypt);
	
	  // MAINTENANCE
	  // if($jsonuser->id_upik != "JSS-A4562")	  redirect(base_url('login/maintenance'));


          $id_jss ='JSS-A0198';
		   //$id_jss = 'JSS-A0198';
            //print_r($id_jss);exit;
               //$data = $this->authentication_model->GetUserByNik($id_jss);
            //   // print_r($data);exit;
               //if (sizeof($data) > 0) {
               //    $_SESSION['userid'] = $data['UserId'];
                 //  $_SESSION['groupid'] = $data['UserGroupId'];
                //   $_SESSION['realname'] = $data['UserRealName'];

                //   $_SESSION['id_jss'] = $data['id_jss'];


            //       redirect('home/home');
            //   } else {
            //       echo '<script type="text/javascript">
            //           alert("Anda Tidak Memiliki Hak akses, ID JSS anda belum terdaftar pada website ini, silahkan Hubungi Admin UPT PUSAT BISNIS Kota Jogjakarta ");
            //           window.location = "https://jss.jogjakota.go.id"
            //           </script>';
            //   }
            $user = $this->Login_model->get_by_id_upik($id_jss);
            // die(json_encode($user));
			
	  //     if (empty($user)) {
		//         echo '<script type="text/javascript">
		// alert("ID JSS Anda Tidak memiliki hak akses ini.");
		// window.location = "https://jss.jogjakota.go.id"
		// </script>';      
	  //     }else{
			$jenis_kib = substr(decrypt_url($_GET['id']),0,2);
        // print_r(decrypt_url($_GET['id']));die;
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
      $id = substr(decrypt_url($_GET['id']),2);
       if($jenis_kib=='05'){
      //echo "lll";die;
 //print_r($id);die;
          $row = $this->Kib_e_model->get_by_id($id);
    // print_r($row);die;
    if ($row) {
      // $kode_barang = $this->global_library->get_kode_barang($row->kode_barang);
      $kode_barang = $this->global_model->get_kode_barang_by_id($row->id_kode_barang);
      // $kode_lokasi=$this->global_library->get_kode_lokasi($row->kode_lokasi);
      $kode_lokasi = $this->global_model->get_kode_lokasi_by_id($row->id_kode_lokasi);
      $data = array(
        //'button' => 'Detail',
        //'menu' => $this->global_model->kode_jenis[$this->kode_jenis]['nama'],
       // 'action' => base_url('kib_e/update_action'),
        'id_kib_e' => set_value('id_kib_e', $row->id_kib_e),
        'sumber_dana' => set_value('sumber_dana', $row->id_sumber_dana),
        'rekening' => set_value('rekening', $row->id_rekening),
        'nama_barang' => set_value('nama_barang', $row->nama_barang),
        'kode_barang' => set_value('kode_barang', $row->kode_barang),
        'kondisi' => set_value('kondisi', $row->kondisi),
        'nomor_register' => set_value('nomor_register', $row->nomor_register),
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

      

        }

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

// print_r($data);die;
      //$data['content'] = $this->load->view('tbl_kib_e_read', $data, TRUE);
      /*$data['breadcrumb'] = array(
        array('label' => 'Data KIB', 'url' => '#', 'icon' => 'fa fa-pie-chart', 'li_class' => ''),
        array('label' => 'KIB E', 'url' => '#', 'icon' => '', 'li_class' => ''),
        array('label' => 'Edit', 'url' => '#', 'icon' => '', 'li_class' => 'active'),
      );*/
    //ECHO "BBB";DIE;
   $this->load->view('tbl_kib_e_read', $data);
 }elseif($jenis_kib=='02'){
  //echo "aaa";die;
  $row = $this->Kib_b_model->get_by_id($id);
  
    if ($row) {
      $data = array(
        'menu' => $this->global_model->kode_jenis[$this->kode_jenis]['nama'],
        'id_kib_b' => $row->id_kib_b,
        'kode_barang' => $row->kode_barang,
        'nama_barang' => $row->nama_barang,
        'nomor_register' => $row->nomor_register,
        'merk_type' => $row->merk_type,
        'ukuran_cc' => my_format_number($row->ukuran_cc),
        'bahan' => $row->bahan,
        'tahun_pembelian' => $row->tahun_pembelian,
        'nomor_pabrik' => $row->nomor_pabrik,
        'nomor_rangka' => $row->nomor_rangka,
        'nomor_mesin' => $row->nomor_mesin,
        'nomor_polisi' => $row->nomor_polisi,
        'nomor_bpkb' => $row->nomor_bpkb,
        'asal_usul' => $row->asal_usul,
        'harga' => my_format_number($row->harga),
        'keterangan' => $row->keterangan,
        'deskripsi' => $row->deskripsi,
        'kode_lokasi' => $row->kode_lokasi,
      );
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
      //$data['content'] = $this->load->view('tbl_kib_b_read', $data, TRUE);
      // print_r($data);die;
      //print_r($data);die;
     $this->load->view('tbl_kib_b_read', $data);
 } else{
   echo '<script type="text/javascript">
    alert("Data Tidak Di temukan");
    window.location = "https://jss.jogjakota.go.id"
    </script>';
 }

		  }
		  
	  // }
	  // }
	  // else{
		//   echo '<script type="text/javascript">
		// alert("ID JSS Anda Tidak memiliki hak akses ini.");
		// window.location = "https://jss.jogjakota.go.id"
		// </script>'; 
	  // }
		 
  }




  public function logout(){
    $this->session->sess_destroy();
    // redirect(base_url('login'));
    redirect('https://jss.jogjakota.go.id/');
  }


  public function maintenance()
  {
	$this->session->sess_destroy();
    if ($this->config->item('maintenance_mode')) {
      $this->load->view('maintenance.php');
    } else {
      redirect(base_url('dashboard'));
    }
  }


}
?>
