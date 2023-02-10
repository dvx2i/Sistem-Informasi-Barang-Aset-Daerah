    <?php

    if (!defined('BASEPATH')) {
      exit('No direct script access allowed');
    }

    class Kib_e extends CI_Controller
    {
      public function __construct()
      {
        parent::__construct();
        $this->global_model->cek_hak_akses('18');
        $this->load->model('laporan/Kib_e_model','Kib_e_model');
        $this->load->model('laporan_model');
        $this->load->helper('my_global');
        $this->load->library('Global_library');
        $this->load->library('ciqrcode');
      }

      public function json()
  {
    //print_r($this->input->post());die;
    header('Content-Type: application/json');
    echo $this->Kib_e_model->json();
  }

      public function index()
      {
       $data = array(
      'button' => 'Buat',
      'action' => base_url('laporan/kib_e/cetak'),
      'id_ruang' => set_value('id_ruang'),
      'kib' => set_value('kib'),
      'tanggal' => set_value('tanggal'),
      'id_pengguna' => set_value('id_pengguna'),
      'id_kuasa_pengguna' => set_value('id_kuasa_pengguna'),
      'id_sub_kuasa_pengguna' => set_value('id_sub_kuasa_pengguna'),
      'kode_lokasi' => set_value('kode_lokasi'),
      //'kode_lokasi_baru' => set_value('kode_lokasi_baru'),

      'barang' => set_value('barang'),
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
      "assets/js/mutasi.js",
      'assets/adminlte/plugins/iCheck/icheck.min.js',
    );

    $data['list_kib'] = $this->global_model->kode_jenis_ruang;

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
        // die(json_encode($data['sub_kuasa_pengguna_list']));
        //     $data['sub_kuasa_pengguna'] = $this->global_model->get_view_sub_kuasa_pengguna($lokasi_explode->pengguna, $lokasi_explode->kuasa_pengguna, $lokasi_explode->sub_kuasa_pengguna);
      }
    

    if ($data['kode_lokasi'])
      $data['lokasi'] = $this->pengajuan_model->get_lokasi($data['kode_lokasi']);

    $data['content'] = $this->load->view('kib_e/home', $data, true);
    $data['breadcrumb'] = array(
      array('label' => 'master_ruang', 'url' => '#', 'icon' => 'fa fa-pie-chart', 'li_class' => ''),
      array('label' => 'Pengajuan', 'url' => '#', 'icon' => '', 'li_class' => ''),
      array('label' => 'Buat', 'url' => '#', 'icon' => '', 'li_class' => 'active'),
    );
    $this->load->view('template', $data);
  }

  public function cetak()
      {
       //print_r($this->input->post());die;
        // BY ID
        $arr_barang = array();
      foreach ($this->input->post('pengajuan') as $key => $value) {
        // die(json_encode($value));
        foreach ($value as $value_2) {
          $kd_brangx = explode("-",$value_2);
          $kd_brang  = $kd_brangx[1];
          $value_x = $kd_brangx[0];
         // echo  $value_x.' aaa '.str_replace("-", ".", $key).' bbb '.encrypt_url(str_replace("-", ".", $key).$value_x);die;
          array_push(
            $arr_barang,
            array(
              //"id_mutasi" => $id,
              "kode_jenis" => str_replace("-", ".", $key), // index array menggunkan "." tidak mau
              "id_kib" => $value_x,
             // "status_diterima" => '1',
              //"tanggal_diterima" => date('Y-m-d'),
              "kode_barang"=>  $kd_brang,
              "id_enkripsi" => encrypt_url(str_replace("-", ".", $key).$value_x),
              "url_qr" => "https://aset.jogjakota.go.id/index.php/scanekir/prev?id=".encrypt_url(str_replace("-", ".", $key).$value_x),
              "jenis_kib"=> substr(decrypt_url(encrypt_url(str_replace("-", ".", $key).$value_x)),0,2)
            )
          );
              $params['data'] = "https://aset.jogjakota.go.id/index.php/scanekir/prev?id=".encrypt_url(str_replace("-", ".", $key).$value_x);
                $params['level'] = 'm';
                $params['size'] = 2;
        $params['savename'] = FCPATH . 'qr/'.encrypt_url(str_replace("-", ".", $key).$value_2).'.png';
                $this->ciqrcode->generate($params);
        }
      }
      //echo "https://aset.jogjakota.go.id/index.php/scanekir/prev?id=".encrypt_url(str_replace("-", ".", $key).$value_x);die;
     // echo FCPATH . 'qr/'.encrypt_url(str_replace("-", ".", $key).$value_2).'.png';
             /*   foreach ($_POST['ids'] as $key) {
                    // echo $key;
                    // die;
          //34 71 010 001 006 0128 0 2022
                    $nop = explode(" ", $key);

            
                        $nopqrname = str_replace(' ', '', $key);
                        $sppt = $this->ttde_model->get_esppt_salinan($nop);
            //print_r($sppt);die;
            $id_enc = encrypt_url($nopqrname);
            $angka_kontrol = $this->ttde_model->get_angkakontrol_salinan($nop);
            $angka_genrt = $this->ttde_model->get_angkagnrt_salinan($nop);
            $angka_ctrl = $angka_kontrol['ANGKA_CTRL'];
            $angka_gnrt1 = $angka_genrt['ANGKA_GNRT'];
            $angka_gnrt2 = substr($angka_ctrl,0,2);
            $angka_gnrt ='#'.$angka_gnrt1.'/'.$angka_gnrt2.'#';
            
            
            //print_r($angka_kontrol);exit;
            $data_array[] = array(
            'sppt' =>$sppt,
            'nopqrname' => $nopqrname,
            'angka_ctrl'=> $angka_ctrl,
            'angka_gnrt' =>$angka_gnrt,
            );

                    
                      $tteqr = 'https://aset.jogjakota.go.id/index.php/scanekir/?id='.$id_enc ;
    
                //header("Content-Type: image/png");
                //echo base_url().'qr/'.$img_qr.'.png'.' -'.$sppt['NOP'].'s';exit;
                //$img_qr = $nmqr;
                $params['data'] = $tteqr;
                $params['level'] = 'm';
                $params['size'] = 2;
        $params['savename'] = FCPATH . 'qr/s'.$nopqrname.'.png';
                $this->ciqrcode->generate($params);
                    
                }
       
         $this->load->view('ttde/cetak_spptz',array('result'=>$data_array));
       
            }
*/
            $this->load->view('laporan/kib_e/kir',array('result'=>$arr_barang));
      } 

      public function getParentLokasi($kode_lokasi)
      {
        $kode = explode(".", $kode_lokasi);
        $kode_lokasi = $kode[1].'.'.$kode[2].'.'.$kode[3].'.'.$kode[4].'.*.*'; 
        $return = $this->Kib_model->getParentLokasi($kode_lokasi);
        return $return->instansi;
      }
    }
