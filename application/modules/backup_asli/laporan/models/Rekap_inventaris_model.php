<?php

if (!defined('BASEPATH')) exit('No direct script access allowed');

class Rekap_inventaris_model extends CI_Model
{

  public $tbl_kode_barang = 'tbl_kode_barang';
  public $tbl_kode_lokasi = 'tbl_kode_lokasi';
  public $tbl_master_intra_extra = 'tbl_master_intra_extra';

  public $tbl_usulan_barang = 'tbl_usulan_barang';
  public $id_usulan_barang = 'id_usulan_barang';



  function __construct()
  {
    parent::__construct();
  }


  // // get all
  // function get_all(){
  //   // $this->db->order_by($this->id, $this->order);
  //   $this->db->select(' A.*, B.kode_barang , B.nama_barang, C.instansi');
  //   $this->db->from('view_kib A');
  //   $this->db->join('tbl_kode_barang B','B.id_kode_barang=A.id_kode_barang','left');
  //   $this->db->join('tbl_kode_lokasi C','C.id_kode_lokasi=A.id_kode_lokasi','left');
  //   $this->db->where('validasi','2');;
  //   return $this->db->get()->result();
  //   // $this->db->get()->result(); die($this->db->last_query());
  // }

  // get data by id
  function get_by_id($id)
  {
    $this->db->where($this->id, $id);
    return $this->db->get($this->table)->row();
  }

  // get total rows
  function total_rows($q = NULL)
  {
    $this->db->like('id_usulan', $q);
    $this->db->or_like('kib', $q);
    $this->db->or_like('tanggal', $q);
    $this->db->or_like('kode_lokasi_pemberi', $q);
    $this->db->or_like('kode_lokasi_penerima', $q);
    $this->db->or_like('barang', $q);
    $this->db->or_like('nip_pembuat', $q);
    $this->db->or_like('created_at', $q);
    $this->db->or_like('updated_at', $q);
    $this->db->or_like('status', $q);
    $this->db->or_like('validasi', $q);
    $this->db->from($this->table);
    return $this->db->count_all_results();
  }

  // get data with limit and search
  function get_limit_data($limit, $start = 0, $q = NULL)
  {
    $this->db->order_by($this->id, $this->order);
    $this->db->like('id_usulan', $q);
    $this->db->or_like('kib', $q);
    $this->db->or_like('tanggal', $q);
    $this->db->or_like('kode_lokasi_pemberi', $q);
    $this->db->or_like('kode_lokasi_penerima', $q);
    $this->db->or_like('barang', $q);
    $this->db->or_like('nip_pembuat', $q);
    $this->db->or_like('created_at', $q);
    $this->db->or_like('updated_at', $q);
    $this->db->or_like('status', $q);
    $this->db->or_like('validasi', $q);
    $this->db->limit($limit, $start);
    return $this->db->get($this->table)->result();
  }

  // function get_all($data)
  // {
  //   // die($data['session']->id_user . "," . $data['id_pemilik'] . "," . $data['id_kode_lokasi'] . ",'" . $data['start_date'] . "','" . $data['last_date'] . "', '" . $data['intra_ekstra']);
  //   $queri = null;
  //   $res = null;
  //   //jika pencarian sekota data kode lokasi di buat 0
  //   if (!$data['id_pengguna']) $data['id_kode_lokasi'] = '0';
  //   if ($data['jenis_rekap'] == 'objek') {
  //     $query    = $this->db->query("call proc_rekap_inventaris_objek(" . $data['session']->id_user . "," . $data['id_pemilik'] . "," . $data['id_kode_lokasi'] . ",'" . $data['start_bulan'] . "','" . $data['start_tahun'] . "','" . $data['last_bulan'] . "','" . $data['last_tahun'] . "', '" . $data['intra_ekstra'] . "')");
  //     $res      = $query->result_array();
  //   } else if ($data['jenis_rekap'] == 'rincian_objek') {
  //     $query    = $this->db->query("call proc_rekap_inventaris_rincian_objek(" . $data['session']->id_user . "," . $data['id_pemilik'] . "," . $data['id_kode_lokasi'] . ",'" . $data['start_bulan'] . "','" . $data['start_tahun'] . "','" . $data['last_bulan'] . "','" . $data['last_tahun'] . "', '" . $data['intra_ekstra'] . "')");
  //     $res      = $query->result_array();
  //   }


  //   //add this two line
  //   $query->next_result();
  //   $query->free_result();
  //   //end of new code

  //   return $res;
  //   // return
  //   // $this->db->get()->result_array();
  //   // die($this->db->last_query());
  // }
  
  public function get_all($data)
  {
    $tahun = $data['last_tahun'];
    $bulan = $data['last_bulan'];
    
    $this->db->select('status,no AS NO,id_kode_barang,objek,nama_barang,jumlah AS total_jumlah, harga AS total_harga, keterangan');
    $this->db->from('tbl_histori_rekap_mutasi A');
    $this->db->where('tahun_data', $tahun);
    $this->db->where('bulan_data', $bulan);
    if ($data['jenis_rekap'] == 'objek') {
      $this->db->where('status <>', '4');
    }
    $this->db->where('id_kode_lokasi', $data['id_kode_lokasi']);
    $this->db->where('kode_intra_extra', $data['intra_ekstra']);
    $this->db->order_by('objek', 'ASC');
    
    return $this->db->get()->result_array();
  }
  
  public function get_all_skpd($data)
  {
    $tahun = $data['last_tahun'];
    $bulan = $data['last_bulan'];
    
    $this->db->select('status,no AS NO,id_kode_barang,objek,nama_barang,SUM(jumlah) AS total_jumlah, SUM(harga) AS total_harga, keterangan');
    $this->db->from('tbl_histori_rekap_mutasi A');
    $this->db->where('tahun_data', $tahun);
    $this->db->where('bulan_data', $bulan);
    if ($data['jenis_rekap'] == 'objek') {
      $this->db->where('status <>', '4');
    }
    $this->db->where('id_pengguna', $data['pengguna'], false);
    $this->db->where('kode_intra_extra', $data['intra_ekstra']);
    $this->db->group_by('id_pengguna,id_kode_barang');
    $this->db->order_by('objek', 'ASC');
    
    return $this->db->get()->result_array();
  }
  
  public function get_all_kota($data)
  {
    $tahun = $data['last_tahun'];
    $bulan = $data['last_bulan'];
    
    $this->db->select('status,no AS NO,id_kode_barang,objek,nama_barang,SUM(jumlah) AS total_jumlah, SUM(harga) AS total_harga, keterangan');
    $this->db->from('tbl_histori_rekap_mutasi A');
    $this->db->where('tahun_data', $tahun);
    $this->db->where('bulan_data', $bulan);
    if ($data['jenis_rekap'] == 'objek') {
      $this->db->where('status <>', '4');
    }
    $this->db->where('kode_intra_extra', $data['intra_ekstra']);
    $this->db->group_by('id_kode_barang');
    $this->db->order_by('objek', 'ASC');
    
    return $this->db->get()->result_array();
  }

  function set_saldo_awal($data)
  {
    // die($data['session']->id_user . "," . $data['id_pemilik'] . "," . $data['id_kode_lokasi'] . ",'" . $data['start_date'] . "','" . $data['last_date'] . "', '" . $data['intra_ekstra']);
    $queri = null;
    $res = null;
    $tahun_sebelum = $data['last_tahun']-1;
    //jika pencarian sekota data kode lokasi di buat 0
    if (!$data['id_pengguna']) $data['id_kode_lokasi'] = '0';

    $query    = $this->db->query("CALL proc_rekap_mutasi_objek_inventaris(" . $data['session']->id_user . "," . $data['id_pemilik'] . "," . $data['id_kode_lokasi'] . ",'','". $tahun_sebelum.'-12-31'. "','12','". $tahun_sebelum . "','". $data['intra_ekstra'] . "')");
    
    


    //add this two line
    // $query->next_result();
    // $query->free_result();
    //end of new code

    return $res;
    // return
    // $this->db->get()->result_array();
    // die($this->db->last_query());
  }

  public function get_pengguna_kib()
  {
    $this->db->select('select distinct C.*');
    $this->db->from('view_kib A');
    $this->db->join($this->tbl_kode_lokasi . ' B', 'A.id_kode_lokasi = B.id_kode_lokasi');
    $this->db->join('view_pengguna C', 'B.pengguna = C.pengguna');
    return $this->db->get()->result();
  }
  
  public function get_saldo_awal($data)
  {
    $tahun_sebelum = $data['last_tahun']-1;
    
    $this->db->select('status,no AS NO,id_kode_barang,jenis,objek,nama_barang,jumlah AS total_jumlah, harga AS total_harga, keterangan');
    $this->db->from('tbl_histori_rekap_mutasi A');
    $this->db->where('tahun_data', $tahun_sebelum);
    $this->db->where('bulan_data', '12');
    if ($data['jenis_rekap'] == 'objek') {
      $this->db->where('status <>', '4');
    }
    $this->db->where('id_kode_lokasi', $data['id_kode_lokasi']);
    $this->db->where('kode_intra_extra', $data['intra_ekstra']);
    $this->db->order_by('objek', 'ASC');
    return $this->db->get()->result_array();
  }

  public function get_saldo_awal_skpd($data)
  {
    $tahun_sebelum = $data['last_tahun']-1;
    
    $this->db->select('status,no AS NO,id_kode_barang,jenis,objek,nama_barang,SUM(jumlah) AS total_jumlah, SUM(harga) AS total_harga, keterangan');
    $this->db->from('tbl_histori_rekap_mutasi A');
    $this->db->where('tahun_data', $tahun_sebelum);
    $this->db->where('bulan_data', '12');
    if ($data['jenis_rekap'] == 'objek') {
      $this->db->where('status <>', '4');
    }
    $this->db->where('id_pengguna', $data['pengguna'], false);
    $this->db->where('kode_intra_extra', $data['intra_ekstra']);
    $this->db->group_by('id_pengguna,id_kode_barang');
    $this->db->order_by('objek', 'ASC');
    // return 
    $this->db->get()->result_array();
    die($this->db->last_query());
  }

  public function get_saldo_awal_kota($data)
  {
    $tahun_sebelum = $data['last_tahun']-1;
    
    $this->db->select('status,no AS NO,id_kode_barang,jenis,objek,nama_barang,SUM(jumlah) AS total_jumlah, SUM(harga) AS total_harga, keterangan');
    $this->db->from('tbl_histori_rekap_mutasi A');
    $this->db->where('tahun_data', $tahun_sebelum);
    $this->db->where('bulan_data', '12');
    if ($data['jenis_rekap'] == 'objek') {
      $this->db->where('status <>', '4');
    }
    $this->db->where('kode_intra_extra', $data['intra_ekstra']);
    $this->db->group_by('id_kode_barang');
    $this->db->order_by('objek', 'ASC');
    return 
    $this->db->get()->result_array();
    die($this->db->last_query());
  }
}
