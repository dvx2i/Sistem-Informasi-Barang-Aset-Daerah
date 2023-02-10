<?php

if (!defined('BASEPATH')) exit('No direct script access allowed');

class Rekap_mutasi2_model extends CI_Model
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

  function get_all($data)
  {
    $queri = null;
    $res = null;
    //jika pencarian sekota data kode lokasi di buat 0
    if (!$data['id_pengguna']) $data['id_kode_lokasi'] = '0';
    if ($data['jenis_rekap'] == 'objek') {
      // die("call proc_rekap_mutasi_objek2(" . $data['session']->id_user . "," . $data['id_pemilik'] . "," . $data['id_kode_lokasi'] . ",'" . $data['start_date'] . "','" . $data['last_date'] . "', '" . $data['intra_ekstra'] . "')");
      $query    = $this->db->query("call proc_rekap_mutasi_objek_cutoff(" . $data['session']->id_user . "," . $data['id_pemilik'] . "," . $data['id_kode_lokasi'] . ",'" . $data['bulan'] . "','" . $data['tahun'] . "', '" . $data['intra_ekstra'] . "')");
      $res      = $query->result_array();
    } else if ($data['jenis_rekap'] == 'rincian_objek') {
      $query    = $this->db->query("call proc_rekap_mutasi_rincian_objek_cutoff(" . $data['session']->id_user . "," . $data['id_pemilik'] . "," . $data['id_kode_lokasi'] . ",'" . $data['bulan'] . "','" . $data['tahun'] . "', '" . $data['intra_ekstra'] . "')");
      $res      = $query->result_array();
    }


    //add this two line
    $query->next_result();
    $query->free_result();
    //end of new code

    // die(json_encode($res));
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
}
