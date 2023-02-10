<?php

if (!defined('BASEPATH'))
  exit('No direct script access allowed');

class Global_model extends CI_Model
{

  public $tbl_kib_a = 'tbl_kib_a';
  public $tbl_kib_b = 'tbl_kib_b';
  public $tbl_kib_c = 'tbl_kib_c';
  public $tbl_kib_d = 'tbl_kib_d';
  public $tbl_kib_e = 'tbl_kib_e';
  public $tbl_kib_f = 'tbl_kib_f';
  public $tbl_pemilik = 'tbl_pemilik';
  public $tbl_barang = 'tbl_kode_barang';
  public $tbl_lokasi = 'tbl_kode_lokasi';
  public $tbl_logs = 'tbl_logs';

  public $tbl_usulan = 'tbl_usulan';
  public $id_usulan = 'id_usulan';

  public $kode_jenis = array(
    '01' => array(
      'nama' => 'KIB A (Tanah)',
      'nama_pendek' => 'KIB A',
      'table' => 'tbl_kib_a',
      'id_name' => 'id_kib_a',
      'year' => 'tahun_pengadaan',
      'controller_name' => 'kib_a',
    ),
    '02' => array(
      'nama' => 'KIB B (Peralatan dan Mesin)',
      'nama_pendek' => 'KIB B',
      'table' => 'tbl_kib_b',
      'id_name' => 'id_kib_b',
      'year' => 'tahun_pembelian',
      'controller_name' => 'kib_b',
    ),
    '03' => array(
      'nama' => 'KIB C (Gedung dan Bangunan)',
      'nama_pendek' => 'KIB C',
      'table' => 'tbl_kib_c',
      'id_name' => 'id_kib_c',
      'year' => 'year(gedung_tanggal)',
      'controller_name' => 'kib_c',
    ),
    '04' => array(
      'nama' => 'KIB D (Jalan, Irigasi dan Jaringan)',
      'nama_pendek' => 'KIB D',
      'table' => 'tbl_kib_d',
      'id_name' => 'id_kib_d',
      'year' => 'year(dokumen_tanggal)',
      'controller_name' => 'kib_d',
    ),
    '05' => array(
      'nama' => 'KIB E (Aset Tetap Lainya)',
      'nama_pendek' => 'KIB E',
      'table' => 'tbl_kib_e',
      'id_name' => 'id_kib_e',
      'year' => 'tahun_pembelian',
      'controller_name' => 'kib_e',
    ),
    '06' => array(
      'nama' => 'KIB F (Kontruksi Dalam Pengerjaan)',
      'nama_pendek' => 'KIB F',
      'table' => 'tbl_kib_f',
      'id_name' => 'id_kib_f',
      'year' => 'year(dokumen_tanggal)',
      'controller_name' => 'kib_f',
    ),
    '5.03' => array(
      'nama' => 'KIB Aset Tidak Berwujud',
      'nama_pendek' => 'KIB ATB',
      'table' => 'tbl_kib_atb',
      'id_name' => 'id_kib_atb',
      'year' => null,
      'controller_name' => 'kib_atb',
    ),
    '5.02' => array(
      'nama' => 'KIB KEMITRAAN DENGAN PIHAK KETIGA',
      'nama_pendek' => 'KEMITRAAN DENGAN PIHAK KETIGA',
      'table' => '',
      'id_name' => '',
      'year' => null,
      'controller_name' => '',
    ),
    '5.04' => array(
      'nama' => 'KIB ASET LAIN-LAIN',
      'nama_pendek' => 'ASET LAIN LAIN',
      'table' => '',
      'id_name' => '',
      'year' => null,
      'controller_name' => '',
    ),
  );

  function __construct()
  {
    parent::__construct();
  }

  function get_sumber_dana()
  {
    $res = $this->db->get('tbl_master_sumber_dana');
    return $res->result_array();
  }

  function get_rekening($id_sumber_dana)
  {
    $this->db->where(array('id_sumber_dana' => $id_sumber_dana,));
    $res = $this->db->get('tbl_master_rekening');
    return $res->result_array();
  }

  function get_kode_kelompok()
  {
    $this->db->where('kode_kelompok<>', "4");
    return $this->db->get('view_kode_barang_kelompok')->result();
  }


  function get_objek($kode_jenis = null)
  {
    $this->db->where('kode_kelompok', '3');
    $this->db->where('kode_jenis', $kode_jenis);
    return $this->db->get('view_kode_barang_objek')->result();
  }

  function get_rincian_objek($kode_jenis = null, $kode_objek = null)
  {
    $this->db->where('kode_kelompok', '3');
    $this->db->where('kode_jenis', $kode_jenis);
    $this->db->where('kode_objek', $kode_objek);
    return
      $this->db->get('view_kode_barang_rincian_objek')->result();
    // die(print_r($this->db->last_query()));
  }

  function get_sub_rincian_objek($kode_jenis, $kode_objek, $kode_rincian_objek)
  {
    $this->db->where('kode_kelompok', '3');
    $this->db->where('kode_jenis', $kode_jenis);
    $this->db->where('kode_objek', $kode_objek);
    $this->db->where('kode_rincian_objek', $kode_rincian_objek);
    return $this->db->get('view_kode_barang_sub_rincian_objek')->result();
  }

  function get_sub_sub_rincian_objek($kode_jenis, $kode_objek, $kode_rincian_objek, $kode_sub_rincian_objek)
  {
    $this->db->where('kode_kelompok', '3');
    $this->db->where('kode_jenis', $kode_jenis);
    $this->db->where('kode_objek', $kode_objek);
    $this->db->where('kode_rincian_objek', $kode_rincian_objek);
    $this->db->where('kode_sub_rincian_objek', $kode_sub_rincian_objek);
    $this->db->where('kode_sub_sub_rincian_objek <> ', '000');
    return
      $this->db->get('view_kode_barang_sub_sub_rincian_objek')->result();
    // die($this->db->last_query());
  }

  function get_objek_atb($kode_jenis = null)
  {
    $this->db->where('kode_kelompok', '5');
    $this->db->where('kode_jenis', $kode_jenis);
    return $this->db->get('view_kode_barang_objek')->result();
  }

  function get_rincian_objek_atb($kode_jenis = null, $kode_objek = null)
  {
    $this->db->where('kode_kelompok', '5');
    $this->db->where('kode_jenis', $kode_jenis);
    $this->db->where('kode_objek', $kode_objek);
    return
      $this->db->get('view_kode_barang_rincian_objek')->result();
    // die(print_r($this->db->last_query()));
  }

  function get_sub_rincian_objek_atb($kode_jenis, $kode_objek, $kode_rincian_objek)
  {
    $this->db->where('kode_kelompok', '5');
    $this->db->where('kode_jenis', $kode_jenis);
    $this->db->where('kode_objek', $kode_objek);
    $this->db->where('kode_rincian_objek', $kode_rincian_objek);
    return $this->db->get('view_kode_barang_sub_rincian_objek')->result();
  }

  function get_sub_sub_rincian_objek_atb($kode_jenis, $kode_objek, $kode_rincian_objek, $kode_sub_rincian_objek)
  {
    $this->db->where('kode_kelompok', '5');
    $this->db->where('kode_jenis', $kode_jenis);
    $this->db->where('kode_objek', $kode_objek);
    $this->db->where('kode_rincian_objek', $kode_rincian_objek);
    $this->db->where('kode_sub_rincian_objek', $kode_sub_rincian_objek);
    // $this->db->where('kode_sub_sub_rincian_objek <> ', '000');
    return $this->db->get('view_kode_barang_sub_sub_rincian_objek')->result();
  }


  function get_pemilik($id_pemilik = null)
  {
    $this->db->order_by('kode', 'asc');
    return $this->db->get($this->tbl_pemilik)->result();
  }
  function get_pemilik_by_id($id_pemilik = null)
  {
    $this->db->where('id_pemilik', $id_pemilik);
    return $this->db->get($this->tbl_pemilik)->row();
  }

  function get_no_register($kode_lokasi, $kode_barang, $tahun_pengadaan)
  {
    $temp = explode('.', $kode_barang);
    $table_name = $this->kode_jenis[$temp[0]]['table'];
    $id_name = $this->kode_jenis[$temp[0]]['id_name'];
    $year = $this->kode_jenis[$temp[0]]['year'];

    $this->db->order_by('nomor_register', 'asc'); //last row
    $this->db->where('kode_lokasi', $kode_lokasi);
    $this->db->where('kode_barang', $kode_barang);
    $this->db->where('nomor_register<>', '0000');
    $this->db->where($year, $tahun_pengadaan);
    return $this->db->get($table_name)->last_row();
  }

  function get_no_register_histori($kode_lokasi, $kode_barang, $tahun_pengadaan)
  {
    $temp = explode('.', $kode_barang);
    $table_name = $this->kode_jenis[$temp[0]]['table'] . '_histori';
    $id_name = $this->kode_jenis[$temp[0]]['id_name'];
    $year = $this->kode_jenis[$temp[0]]['year'];

    $this->db->order_by('nomor_register', 'asc'); //last row
    $this->db->where('kode_lokasi', $kode_lokasi);
    $this->db->where('kode_barang', $kode_barang);
    $this->db->where('nomor_register<>', '0000');
    $this->db->where($year, $tahun_pengadaan);
    return $this->db->get($table_name)->last_row();
  }

  function get_kib($kode_gol, $id_kib)
  {
    $jenis = $this->kode_jenis[$kode_gol];
    return $this->db->get_where($jenis['table'], array($jenis['id_name'] => $id_kib,))->row();
  }

  function get_kib_input_banyak($kode_gol, $id_kib)
  {
    $jenis = $this->kode_jenis[$kode_gol];
    return $this->db->get_where($jenis['table'], array('input_banyak' => $id_kib,))->result();
  }

  function update_kib($kode_gol, $id_kib, $data)
  {
    $jenis = $this->kode_jenis[$kode_gol];
    $this->db->where($jenis['id_name'], $id_kib);
    $this->db->update($jenis['table'], $data);
  }

  function update_histori_barang($kode_gol,$id_kib,$tanggal_histori, $status_histori,$data)
  {
    $this->db->where('id_kib', $id_kib);
    $this->db->where('tanggal_histori', $tanggal_histori);
    $this->db->where('status_histori', $status_histori);
    $this->db->where('kode_jenis', $kode_gol);
    $this->db->update('tbl_histori_barang', $data);
  }
  
  function update_histori_barang_atb($kode_gol,$id_kib,$tanggal_histori, $status_histori,$data)
  {
    $this->db->where('id_kib', $id_kib);
    $this->db->where('kode_jenis', $kode_gol);
    $this->db->where('tanggal_histori', $tanggal_histori);
    $this->db->where('status_histori', $status_histori);
    $this->db->update('tbl_histori_barang_atb', $data);
  }

  function delete_histori_barang($kode_gol,$id_kib,$tanggal_histori, $status_histori)
  {
    $this->db->where('id_kib', $id_kib);
    $this->db->where('tanggal_histori', $tanggal_histori);
    $this->db->where('status_histori', $status_histori);
    $this->db->where('kode_jenis', $kode_gol);
    $this->db->delete('tbl_histori_barang');
  }

  function update_reklas($id_reklas_kode, $data)
  {
    $this->db->where('id_reklas_kode', $id_reklas_kode);
    $this->db->update('tbl_reklas_kode', $data);
    // die($this->db->last_query());
  }

  function get_row_kib($kode_gol, $list_id)
  {
    $jenis = $this->kode_jenis[$kode_gol];
    $this->db->group_by('validasi');
    $this->db->where_in($jenis['id_name'], $list_id);
    return $this->db->get($jenis['table'])->num_rows();
  }

  public function _logs($menu = null, $sub_menu = null, $tabel_name = null, $action_id = null, $action = null, $data = null, $feature = null)
  {
    
    $session = $this->session->userdata('session');
    if ($action_id == null) $action_id = $this->db->insert_id();
    // die($menu.' == '.$sub_menu);
    $data_logs = array(
      'id_user'  => $session->id_user, //id user
      'roles'    => $session->id_role, //id_role

      'menu'  =>  $menu, // menu (data_kib, validasi_kib, mutasi, laporan)
      'sub_menu'  =>  $sub_menu, // sub menu (kib_a, kib_b, usulan, penerima)
      'table_name' => $tabel_name, // tabel name
      'action_id' => $action_id, // id tabel
      'action'  => $action, //insert, update, delete
      'note'  => json_encode($data),
      'feature'  => $feature,

      'ip_address' => $this->input->ip_address(),
      'user_agent' => $this->input->user_agent(),
      'created_at' => date('Y-m-d H:i:s'),
    );
    $this->db->insert($this->tbl_logs, $data_logs);
    
  }

  function get_barang_kib($kode_gol = null)
  {
    $this->db->distinct();
    $this->db->select('id_kode_barang,kode_barang,nama_barang');
    $this->db->where('validasi', 'y');
    if ($kode_gol != null)
      return $this->db->get($this->kode_jenis[$kode_gol]['table'])->result();
  }

  function get_kode_barang_by_id($id_kode_barang = null)
  {
    return $this->db->get_where($this->tbl_barang, array('id_kode_barang' => $id_kode_barang,))->row();
  }

  function get_kode_barang($kode_barang = null)
  {
    return $this->db->get_where($this->tbl_barang, array('kode_barang' => $kode_barang,))->row();
  }


  function getLokasi()
  {
    $this->db->select('id_kode_lokasi,CONCAT(intra_kom_ekstra_kom,".",propinsi,".",kabupaten,".",pengguna,".",kuasa_pengguna,".",sub_kuasa_pengguna) AS kode, instansi');
    $this->db->from('tbl_kode_lokasi');
    $this->db->order_by('kode', 'ASC');
    return $this->db->get()->result();
  }

  function getJabatan()
  {
    $this->db->select('*');
    $this->db->from('tbl_master_jabatan');
    $this->db->order_by('id_jabatan', 'ASC');
    return $this->db->get()->result();
  }

  function getUser()
  {
    $this->db->select('*');
    $this->db->from('tbl_user');
    $this->db->order_by('id_user', 'ASC');
    return $this->db->get()->result();
  }

  function get_kode_lokasi($kode_lokasi = null)
  {
    $kode = explode('.', $kode_lokasi);
    $kode[0] = isset($kode[0]) ? $kode[0] : "*";
    $kode[1] = isset($kode[1]) ? $kode[1] : "*";
    $kode[2] = isset($kode[2]) ? $kode[2] : "*";
    $kode[3] = isset($kode[3]) ? $kode[3] : "*";
    $kode[4] = isset($kode[4]) ? $kode[4] : "*";
    $kode[5] = isset($kode[5]) ? $kode[5] : "*";
    $kode[6] = isset($kode[6]) ? $kode[6] : "*";
    $data = array(
      'intra_kom_ekstra_kom' => $kode[1],
      'propinsi' => $kode[2],
      'kabupaten' => $kode[3],
      'pengguna' => $kode[4],
      'kuasa_pengguna' => $kode[5],
      'sub_kuasa_pengguna' => $kode[6],
    );
    return $this->db->get_where($this->tbl_lokasi, $data)->row();
  }

  function get_kode_lokasi_by_id($id_kode_lokasi = null)
  {
    return $this->db->get_where($this->tbl_lokasi, array('id_kode_lokasi' => $id_kode_lokasi,))->row();
  }

  function get_row_usulan($id_usulan = null)
  {
    $this->db->where($this->id_usulan, $id_usulan);
    return $this->db->get($this->tbl_usulan)->row();
  }


  function get_lokasi($id_kode_lokasi = null)
  {
    return $this->db->get_where($this->tbl_lokasi, array('id_kode_lokasi' => $id_kode_lokasi,))->row();
  }

  function get_lokasi_by_pengguna($id_pengguna = null)
  {
    return $this->db->get_where($this->tbl_lokasi, array('pengguna' => $id_pengguna,'kuasa_pengguna' => '*'))->row();
  }


  function set_location($id_kode_lokasi)
  {
    $lokasi = $this->get_lokasi($id_kode_lokasi);
    $session = $this->session->userdata('session');
    $session->id_kode_lokasi = $lokasi->id_kode_lokasi;
    $session->kode_lokasi = $lokasi->intra_kom_ekstra_kom . "." . $lokasi->propinsi . "." . $lokasi->kabupaten . "." . $lokasi->pengguna . "." . $lokasi->kuasa_pengguna . "." . $lokasi->sub_kuasa_pengguna;
    $session->nama_lokasi = $lokasi->instansi;
    $this->session->set_userdata('session', $session);
  }

  public function get_all_lokasi()
  {
    $this->db->select('tbl_kode_lokasi.*,CONCAT(intra_kom_ekstra_kom,".",propinsi,".",kabupaten,".",pengguna,".",kuasa_pengguna,".",sub_kuasa_pengguna) AS kode');
    $this->db->from('tbl_kode_lokasi');
    $this->db->order_by('kode', 'ASC');
    return $this->db->get()->result();
  }

  public function get_pengguna()
  {
    return $this->db->get('view_pengguna')->result();
  }
  public function get_kuasa_pengguna()
  {
    return $this->db->get('view_kuasa_pengguna')->result();
  }

  public function get_master_asal_usul()
  {
    $this->db->where('is_remove <> "0"');
    return $this->db->get('tbl_master_asal_usul')->result();
  }

  public function get_master_bangunan()
  {
    $this->db->where('is_remove <> "0"');
    return $this->db->get('tbl_master_bangunan')->result();
  }

  public function get_master_hak_tanah()
  {
    $this->db->where('is_remove <> "0"');
    return $this->db->get('tbl_master_hak_tanah')->result();
  }

  public function get_master_kondisi_bangunan()
  {
    $this->db->where('is_remove <> "0"');
    return $this->db->get('tbl_master_kondisi_bangunan')->result();
  }

  public function get_master_konstruksi()
  {
    $this->db->where('is_remove <> "0"');
    return $this->db->get('tbl_master_konstruksi')->result();
  }

  public function get_master_penggunaan()
  {
    return $this->db->get('tbl_master_penggunaan')->result();
  }

  public function get_master_status_tanah()
  {
    return $this->db->get('tbl_master_status_tanah')->result();
  }

  public function get_master_satuan()
  {
    $this->db->where('is_remove <> "0"');
    return $this->db->get('tbl_master_satuan')->result();
  }


  // public function get_request_validasi()
  // {
  //   $session = $this->session->userdata('session');
  //   $pengguna = explode('.', $session->kode_lokasi)[3];
  //   $this->db->select('A.kode_jenis, A.kib_name,count(A.validasi) jumlah, B.controller_name');
  //   $this->db->from('view_kib A');
  //   $this->db->join('tbl_master_jenis B', 'A.kode_jenis = B.kode_jenis', 'left');
  //   $this->db->join('tbl_kode_lokasi C', 'A.id_kode_lokasi = C.id_kode_lokasi', 'left');
  //   $this->db->where('validasi', '1');
  //   $this->db->where('status_barang', 'kib');
  //   if ($session->id_role != 1)
  //     $this->db->where('C.pengguna', $pengguna);

  //   $this->db->group_by('kode_jenis');
	// $this->db->order_by('B.id_master_jenis');
  //   return
  //     $this->db->get()->result();
  //   // die($this->db->last_query());
  // }
  public function get_request_validasi_a()
  {
	 $session = $this->session->userdata('session');
    $pengguna = explode('.', $session->kode_lokasi)[3];
    $kuasa_pengguna = explode('.', $session->kode_lokasi)[4];
		$where_lokasi = '';

    if($session->id_role !='1'){
      $where_lokasi = " and C.pengguna = '".$pengguna."' ";
      if($kuasa_pengguna != '0001'){
        $where_lokasi .= " and C.kuasa_pengguna = '".$kuasa_pengguna."' ";
      }
      // $groupby = 'GROUP by kode_jenis'; 
    }
    
        $query = "
        SELECT  A.kode_jenis, A.kib_name,count(A.validasi) jumlah, B.controller_name 
        FROM  (
            
			select '01' AS `kode_jenis`,'KIB A' AS `kib_name`,`tbl_kib_a`.`id_kode_lokasi` AS `id_kode_lokasi`,`tbl_kib_a`.`kode_lokasi` AS `kode_lokasi`,`tbl_kib_a`.`validasi` AS `validasi`,`tbl_kib_a`.`status_barang` AS `status_barang` from `tbl_kib_a` 
            
        ) A
        left join  tbl_master_jenis B on A.kode_jenis = B.kode_jenis 
		 left join  tbl_kode_lokasi C on A.id_kode_lokasi = C.id_kode_lokasi 
		where A.validasi < 2  and A.status_barang = 'kib' 
		$where_lokasi 
		order by B.id_master_jenis
      ";
	 
        $res = $this->db->query($query);
        return $res->result_array();
	  
    /*
    $this->db->select('A.kode_jenis, A.kib_name,count(A.validasi) jumlah, B.controller_name');
    $this->db->from('view_kib A');
    $this->db->join('tbl_master_jenis B', 'A.kode_jenis = B.kode_jenis', 'left');
    $this->db->join('tbl_kode_lokasi C', 'A.id_kode_lokasi = C.id_kode_lokasi', 'left');
    $this->db->where('validasi', '1');
    $this->db->where('status_barang', 'kib');
    if ($session->id_role != 1)
      $this->db->where('C.pengguna', $pengguna);

    $this->db->group_by('kode_jenis');
	$this->db->order_by('B.id_master_jenis');
    return
      $this->db->get()->result();
    // die($this->db->last_query());
	*/
  }
  public function get_request_validasi_b()
  {
    $session = $this->session->userdata('session');
     $pengguna = explode('.', $session->kode_lokasi)[3];
     $kuasa_pengguna = explode('.', $session->kode_lokasi)[4];
     $where_lokasi = '';

     if($session->id_role !='1'){
       $where_lokasi = " and C.pengguna = '".$pengguna."' ";
       if($kuasa_pengguna != '0001'){
         $where_lokasi .= " and C.kuasa_pengguna = '".$kuasa_pengguna."' ";
       }
       // $groupby = 'GROUP by kode_jenis'; 
     }
    
        $query = "
        SELECT  A.kode_jenis, A.kib_name,count(A.validasi) jumlah, B.controller_name 
        FROM  (
            
			select '02' AS `kode_jenis`,'KIB B' AS `kib_name`,`tbl_kib_b`.`id_kode_lokasi` AS `id_kode_lokasi`,`tbl_kib_b`.`kode_lokasi` AS `kode_lokasi`,`tbl_kib_b`.`validasi` AS `validasi`,`tbl_kib_b`.`status_barang` AS `status_barang` from `tbl_kib_b` 
            
        ) A
        left join  tbl_master_jenis B on A.kode_jenis = B.kode_jenis 
		 left join  tbl_kode_lokasi C on A.id_kode_lokasi = C.id_kode_lokasi 
		where A.validasi < 2  and A.status_barang = 'kib' 
		$where_lokasi 
		order by B.id_master_jenis
      ";
	   //echo $query;exit;
        $res = $this->db->query($query);
        return $res->result_array();
	
  }
   public function get_request_validasi_c()
  {
    $session = $this->session->userdata('session');
     $pengguna = explode('.', $session->kode_lokasi)[3];
     $kuasa_pengguna = explode('.', $session->kode_lokasi)[4];
     $where_lokasi = '';

     if($session->id_role !='1'){
       $where_lokasi = " and C.pengguna = '".$pengguna."' ";
       if($kuasa_pengguna != '0001'){
         $where_lokasi .= " and C.kuasa_pengguna = '".$kuasa_pengguna."' ";
       }
       // $groupby = 'GROUP by kode_jenis'; 
     }
    
        $query = "
        SELECT  A.kode_jenis, A.kib_name,count(A.validasi) jumlah, B.controller_name 
        FROM  (
            
			select '03' AS `kode_jenis`,'KIB C' AS `kib_name`,`tbl_kib_c`.`id_kode_lokasi` AS `id_kode_lokasi`,`tbl_kib_c`.`kode_lokasi` AS `kode_lokasi`,`tbl_kib_c`.`validasi` AS `validasi`,`tbl_kib_c`.`status_barang` AS `status_barang` from `tbl_kib_c` 
            
        ) A
        left join  tbl_master_jenis B on A.kode_jenis = B.kode_jenis 
		 left join  tbl_kode_lokasi C on A.id_kode_lokasi = C.id_kode_lokasi 
		where A.validasi < 2  and A.status_barang = 'kib' 
		$where_lokasi 
		order by B.id_master_jenis
      ";
        $res = $this->db->query($query);
        return $res->result_array();
	
  }
   public function get_request_validasi_d()
  {
    $session = $this->session->userdata('session');
     $pengguna = explode('.', $session->kode_lokasi)[3];
     $kuasa_pengguna = explode('.', $session->kode_lokasi)[4];
     $where_lokasi = '';

     if($session->id_role !='1'){
       $where_lokasi = " and C.pengguna = '".$pengguna."' ";
       if($kuasa_pengguna != '0001'){
         $where_lokasi .= " and C.kuasa_pengguna = '".$kuasa_pengguna."' ";
       }
       // $groupby = 'GROUP by kode_jenis'; 
     }
    
        $query = "
        SELECT  A.kode_jenis, A.kib_name,count(A.validasi) jumlah, B.controller_name 
        FROM  (
            
			select '04' AS `kode_jenis`,'KIB D' AS `kib_name`,`tbl_kib_d`.`id_kode_lokasi` AS `id_kode_lokasi`,`tbl_kib_d`.`kode_lokasi` AS `kode_lokasi`,`tbl_kib_d`.`validasi` AS `validasi`,`tbl_kib_d`.`status_barang` AS `status_barang` from `tbl_kib_d` 
            
        ) A
        left join  tbl_master_jenis B on A.kode_jenis = B.kode_jenis 
		 left join  tbl_kode_lokasi C on A.id_kode_lokasi = C.id_kode_lokasi 
		where A.validasi < 2  and A.status_barang = 'kib' 
		$where_lokasi 
		order by B.id_master_jenis
      ";
        $res = $this->db->query($query);
        return $res->result_array();
	
  }
   public function get_request_validasi_e()
  {
    $session = $this->session->userdata('session');
     $pengguna = explode('.', $session->kode_lokasi)[3];
     $kuasa_pengguna = explode('.', $session->kode_lokasi)[4];
     $where_lokasi = '';

     if($session->id_role !='1'){
       $where_lokasi = " and C.pengguna = '".$pengguna."' ";
       if($kuasa_pengguna != '0001'){
         $where_lokasi .= " and C.kuasa_pengguna = '".$kuasa_pengguna."' ";
       }
       // $groupby = 'GROUP by kode_jenis'; 
     }
    
        $query = "
        SELECT  A.kode_jenis, A.kib_name,count(A.validasi) jumlah, B.controller_name 
        FROM  (
            
			select '05' AS `kode_jenis`,'KIB E' AS `kib_name`,`tbl_kib_e`.`id_kode_lokasi` AS `id_kode_lokasi`,`tbl_kib_e`.`kode_lokasi` AS `kode_lokasi`,`tbl_kib_e`.`validasi` AS `validasi`,`tbl_kib_e`.`status_barang` AS `status_barang` from `tbl_kib_e` 
            
        ) A
        left join  tbl_master_jenis B on A.kode_jenis = B.kode_jenis 
		 left join  tbl_kode_lokasi C on A.id_kode_lokasi = C.id_kode_lokasi 
		where A.validasi < 2  and A.status_barang = 'kib' 
		$where_lokasi 
		order by B.id_master_jenis
      ";
        $res = $this->db->query($query);
        return $res->result_array();
	
  }
  public function get_request_validasi_f()
  {
    $session = $this->session->userdata('session');
     $pengguna = explode('.', $session->kode_lokasi)[3];
     $kuasa_pengguna = explode('.', $session->kode_lokasi)[4];
     $where_lokasi = '';

     if($session->id_role !='1'){
       $where_lokasi = " and C.pengguna = '".$pengguna."' ";
       if($kuasa_pengguna != '0001'){
         $where_lokasi .= " and C.kuasa_pengguna = '".$kuasa_pengguna."' ";
       }
       // $groupby = 'GROUP by kode_jenis'; 
     }
    
        $query = "
        SELECT  A.kode_jenis, A.kib_name,count(A.validasi) jumlah, B.controller_name 
        FROM  (
            
			select '06' AS `kode_jenis`,'KIB F' AS `kib_name`,`tbl_kib_f`.`id_kode_lokasi` AS `id_kode_lokasi`,`tbl_kib_f`.`kode_lokasi` AS `kode_lokasi`,`tbl_kib_f`.`validasi` AS `validasi`,`tbl_kib_f`.`status_barang` AS `status_barang` from `tbl_kib_f` 
            
        ) A
        left join  tbl_master_jenis B on A.kode_jenis = B.kode_jenis 
		 left join  tbl_kode_lokasi C on A.id_kode_lokasi = C.id_kode_lokasi 
		where A.validasi < 2  and A.status_barang = 'kib' 
		$where_lokasi 
		order by B.id_master_jenis
      ";
        $res = $this->db->query($query);
        return $res->result_array();
	
  }
  public function get_request_validasi_atb()
  {
    $session = $this->session->userdata('session');
     $pengguna = explode('.', $session->kode_lokasi)[3];
     $kuasa_pengguna = explode('.', $session->kode_lokasi)[4];
     $where_lokasi = '';

     if($session->id_role !='1'){
       $where_lokasi = " and C.pengguna = '".$pengguna."' ";
       if($kuasa_pengguna != '0001'){
         $where_lokasi .= " and C.kuasa_pengguna = '".$kuasa_pengguna."' ";
       }
       // $groupby = 'GROUP by kode_jenis'; 
     }
    
        $query = "
        SELECT  A.kode_jenis, A.kib_name,count(A.validasi) jumlah, B.controller_name 
        FROM  (
            
			select '5.03' AS `kode_jenis`,'KIB ATB' AS `kib_name`,`tbl_kib_atb`.`id_kode_lokasi` AS `id_kode_lokasi`,`tbl_kib_atb`.`kode_lokasi` AS `kode_lokasi`,`tbl_kib_atb`.`validasi` AS `validasi`,`tbl_kib_atb`.`status_barang` AS `status_barang` from `tbl_kib_atb` 
            
        ) A
        left join  tbl_master_jenis B on A.kode_jenis = B.kode_jenis 
		 left join  tbl_kode_lokasi C on A.id_kode_lokasi = C.id_kode_lokasi 
		where A.validasi < 2  and A.status_barang = 'kib' 
		$where_lokasi 
		order by B.id_master_jenis
      ";
        $res = $this->db->query($query);
        return $res->result_array();
	
  }
  
	public function get_kuasa_pengguna_by_pengguna($id_pengguna = null)
  {
    return $this->db->get_where('view_kuasa_pengguna', array('pengguna' => $id_pengguna,))->result();
  }
  
  public function get_view_pengguna($pengguna = null)
  {
    return $this->db->get_where('view_pengguna', array('pengguna' => $pengguna))->row();
  }

  public function get_view_kuasa_pengguna($pengguna = null, $kuasa_pengguna = null)
  {
    return $this->db->get_where('view_kuasa_pengguna', array('pengguna' =>  $pengguna, 'kuasa_pengguna' => $kuasa_pengguna))->row();
  }

  public function get_view_sub_kuasa_pengguna($pengguna = null, $kuasa_pengguna = null, $sub_kuasa_pengguna = null)
  {
    return $this->db->get_where('view_sub_kuasa_pengguna', array('pengguna' =>  $pengguna, 'kuasa_pengguna' => $kuasa_pengguna, 'sub_kuasa_pengguna' => $sub_kuasa_pengguna))->row();
  }

  public function set_validasi($kode_jenis = null, $id_kib = null, $tanggal_validasi = null)
  {
    // die(" call validasi ('" . $kode_jenis . "','" . $id_kib . "') ");
    $this->db_1 = $this->load->database('default', true);
    if($kode_jenis == '01'){
      $query    = $this->db_1->query(" call validasi_a('" . $kode_jenis . "','" . $id_kib . "','" . $tanggal_validasi . "') ");
      $res      = $query->result();
    }
    elseif($kode_jenis == '02'){
      $query    = $this->db_1->query(" call validasi_b('" . $kode_jenis . "','" . $id_kib . "','" . $tanggal_validasi . "') ");
      $res      = $query->result();
    }
    elseif($kode_jenis == '03'){
      $query    = $this->db_1->query(" call validasi_c('" . $kode_jenis . "','" . $id_kib . "','" . $tanggal_validasi . "') ");
      $res      = $query->result();
    }
    elseif($kode_jenis == '04'){
      $query    = $this->db_1->query(" call validasi_d('" . $kode_jenis . "','" . $id_kib . "','" . $tanggal_validasi . "') ");
      $res      = $query->result();
    }
    elseif($kode_jenis == '05'){
      $query    = $this->db_1->query(" call validasi_e('" . $kode_jenis . "','" . $id_kib . "','" . $tanggal_validasi . "') ");
      $res      = $query->result();
    }
    elseif($kode_jenis == '06'){
      $query    = $this->db_1->query(" call validasi_f('" . $kode_jenis . "','" . $id_kib . "','" . $tanggal_validasi . "') ");
      $res      = $query->result();
    }
    elseif($kode_jenis == '5.03'){
      $query    = $this->db_1->query(" call validasi_atb('" . $kode_jenis . "','" . $id_kib . "','" . $tanggal_validasi . "') ");
      $res      = $query->result();
    }
    //add this two line
    $query->next_result();
    $query->free_result();
    //end of new code
    // die(json_encode($res));
    return $res;
  }
  
  // get data by id
  function get_locked($bulan,$tahun,$id_kode_lokasi)
  {
    $this->db->where('bulan', $bulan);
    $this->db->where('tahun', $tahun);
    $this->db->where('locked_admin >', 0, FALSE);
    // $this->db->or_where('locked_skpd = 1)', NULL, FALSE);
    $this->db->where('id_kode_lokasi', $id_kode_lokasi);
    return $this->db->get('tbl_stock_opname')->num_rows();
    // echo $this->db->last_query(); die;
  }

  public function set_validasi_atb($kode_jenis = null, $id_kib = null, $tanggal_validasi = null)
  {
    // die($kode_jenis . "','" . $id_kib);
    $query    = $this->db->query("call validasi_atb ('" . $kode_jenis . "','" . $id_kib . "','" . $tanggal_validasi . "')");
    $res      = $query->result();

    //add this two line
    $query->next_result();
    $query->free_result();
    //end of new code
    // die(json_encode($res));
    return $res;
  }

  public function get_sertifikat_nomor()
  {
    $this->db->select("id_kib_a,sertifikat_nomor, concat(kode_barang,'-',nama_barang) uraian ,sertifikat_nomor, status_hak, penggunaan");
    $this->db->from('tbl_kib_a A');
    return
      $this->db->get()->result();
    // die($this->db->last_query());
  }

  public function cek_hak_akses($id_menu)
  {
    $session = $this->session->userdata('session');
    $list_id_menu = $session->list_id_menu;
    // die(json_encode($session));
    if (!in_array($id_menu, explode(',', $list_id_menu))) {
      redirect(base_url('dashboard'));
    }
  }

  public function get_kib_a_by_id_lokasi($id_kode_lokasi)
  {
    return $this->db->get_where($this->tbl_kib_a, array('id_kode_lokasi' =>  $id_kode_lokasi, 'validasi' => 2))->result();
  }

  function get_kode_barang_kib_f($kode_jenis)
  {
    $data = array(
      'kode_akun' => 1,
      'kode_kelompok' => 3,
      'kode_jenis' => 6,
      'substring(kode_sub_sub_rincian_objek,3,1)' => $kode_jenis,
    );
    return $this->db->get_where('tbl_kode_barang', $data)->row();
  }

  function set_histori_barang($status_histori, $id_kode_lokasi, $kode_jenis, $id_kib)
  {
    $query = $this->db->query("call proc_histori_barang('" . $status_histori . "'," . $id_kode_lokasi . ",'" . $kode_jenis . "'," . $id_kib . ",'global_model');");

    // $query->next_result();
    // $query->free_result();
    // die($this->db->last_query());
  }

  public function insert_histori_kib($kode_jenis, $id_kib, $desc)
  {
    $query = $this->db->query("call proc_histori_kib('" . $kode_jenis . "'," . $id_kib . ",'" . $desc . "');");
  }

  public function row_get_where($tabel, $where)
  {
    $res = $this->db->get_where($tabel, $where);
    return $res->row_array();
  }

  public function result_get_where($tabel, $where)
  {
    $res = $this->db->get_where($tabel, $where);
    return $res->result_array();
  }

  public function global_update($tabel, $where, $data)
  {
    $this->db->where($where);
    $this->db->update($tabel, $data);
  }

  /*
  public function  edit_harga($data){
    // hapus data lama di bulan ini
    $bulan = date('m',strtotime($data["tanggal"]));
    $tahun = date('Y',strtotime($data["tanggal"]));
    $this->db->where('month(tanggal)', $bulan);
    $this->db->where('year(tanggal)', $tahun);
    $this->db->where('kode_jenis', $data['kode_jenis']);
    $this->db->where('id_kib', $data['id_kib']);
    $this->db->delete('tbl_edit_harga');

    //insert data baru
    $this->db->insert('tbl_edit_harga', $data);
  }
*/
}
