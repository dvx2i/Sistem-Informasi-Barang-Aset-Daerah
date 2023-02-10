    <?php

    if (!defined('BASEPATH')) {
      exit('No direct script access allowed');
    }

    class Kib_b extends CI_Controller
    {
      public function __construct()
      {
        parent::__construct();
        $this->global_model->cek_hak_akses('18');
        $this->load->model('laporan/Kib_b_model','Kib_b_model');
        $this->load->model('kib_b/Kib_b_model','Kib_model');
        $this->load->model('laporan_model');
        $this->load->helper('my_global');
        $this->load->library('Global_library');
        $this->load->library('ciqrcode');
      }
      public function json()
  {
    //print_r($this->input->post());die;
    header('Content-Type: application/json');
    echo $this->Kib_b_model->json();
  }

      public function index()
      {
       $data = array(
      'button' => 'Buat',
      'action' => base_url('laporan/kib_b/cetak'),
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

    $data['content'] = $this->load->view('kib_b/home', $data, true);
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
        $kode_jenis = '02';
        $arr_barang = array();
        // die(json_encode($value));
        $list_id = $this->input->post('list_id');
        $list_id = explode(",",$list_id);
        
        
        foreach($list_id as $key => $value_x){
          $row = $this->Kib_model->get_by_id($value_x);
         // echo  $value_x.' aaa '.str_replace("-", ".", $key).' bbb '.encrypt_url(str_replace("-", ".", $key).$value_x);die;
         $value_2 = $row->id_kib_e."-".$row->kode_barang;
          array_push(
            $arr_barang,
            array(
              "kode_jenis" => $kode_jenis, // index array menggunkan "." tidak mau
              "id_kib" => $value_x,
             "nama_barang" => !empty($row->nama_barang) ? $row->nama_barang : '-' ,
             "nomor_register" => !empty($row->nomor_register) ? $row->nomor_register : '-' ,
             "tahun" => !empty($row->tahun_pembelian) ? $row->tahun_pembelian : '-' ,
             "spesifikasi" => !empty($row->judul_pencipta) ? $row->judul_pencipta : '-' ,
              //"tanggal_diterima" => date('Y-m-d'),
              "kode_barang"=>  $row->kode_barang,
              "id_enkripsi" => encrypt_url( $kode_jenis.$value_2),
              "url_qr" => site_url('scanekir')."/prev?id=".encrypt_url($kode_jenis.$value_2),
              "jenis_kib"=> $kode_jenis
            )
          );
              $params['data'] = site_url('scanekir')."/prev?id=".encrypt_url($kode_jenis.$value_2);
                $params['level'] = 'm';
                $params['size'] = 2;
        $params['savename'] = FCPATH . 'qr/'.encrypt_url($kode_jenis.$value_2).'.png';
                $this->ciqrcode->generate($params);
        }
      //echo "https://aset.jogjakota.go.id/index.php/scanekir/prev?id=".encrypt_url(str_replace("-", ".", $key).$value_x);die;
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
            $this->load->view('laporan/kib_b/kir',array('result'=>$arr_barang));
      } 

      public function getParentLokasi($kode_lokasi)
      {
        $kode = explode(".", $kode_lokasi);
        $kode_lokasi = $kode[1].'.'.$kode[2].'.'.$kode[3].'.'.$kode[4].'.*.*'; 
        $return = $this->Kib_model->getParentLokasi($kode_lokasi);
        return $return->instansi;
      }
    }
