<?php

if (!defined('BASEPATH')) exit('No direct script access allowed');

class Laporan_model extends CI_Model
{

  public $tbl_kode_barang = 'tbl_kode_barang';
  public $tbl_kode_lokasi = 'tbl_kode_lokasi';

  public $tbl_usulan_barang = 'tbl_usulan_barang';
  public $id_usulan_barang = 'id_usulan_barang';

  public $view_lokasi = 'view_lokasi';
  public $view_pengguna = 'view_pengguna';
  public $view_kuasa_pengguna = 'view_kuasa_pengguna';


  function __construct()
  {
    parent::__construct();
  }

  public function get_kuasa_pengguna($id_pengguna = null)
  {
    return $this->db->get_where('view_kuasa_pengguna', array('id_pengguna' => $id_pengguna,))->result();
  }

  public function get_sub_kuasa_pengguna($id_pengguna = null, $id_kuasa_pengguna = null)
  {
    return
      $this->db->get_where('view_sub_kuasa_pengguna', array('id_pengguna' => $id_pengguna, 'id_kuasa_pengguna' => $id_kuasa_pengguna))->result();
    // die($this->db->last_query());
  }

  public function get_lokasi_by_id($id_kode_lokasi = null)
  {
    return $this->db->get_where($this->view_lokasi, array('id_kode_lokasi' => $id_kode_lokasi,))->row();
  }
   public function get_lokasi_all()
  {
    return $this->db->get_where($this->view_lokasi)->row();
  }

  public function get_pengguna_by_id($id_pengguna = null)
  {
    return $this->db->get_where($this->view_pengguna, array('id_pengguna' => $id_pengguna,))->row();
  }

  public function get_kuasa_pengguna_by_id($id_kuasa_pengguna = null)
  {
    return $this->db->get_where($this->view_kuasa_pengguna, array('id_pengguna' => $id_kuasa_pengguna,))->row();
  }

  public function get_tertanda($id_kode_lokasi = null)
  {
    // $this->db->select('A.*, B.description as jabatan');
    // $this->db->from('tbl_user A');
    // $this->db->join('tbl_master_jabatan B', 'A.id_jabatan=B.id_jabatan', 'left');
    // $this->db->where('A.id_kode_lokasi', $id_kode_lokasi);

    $this->db->select('A.* , B.nama, B.nip, C.description as jabatan');
    $this->db->from('tbl_tanda_tangan A');
    $this->db->join('tbl_user B', 'A.id_user=B.id_user', 'left');
    $this->db->join('tbl_master_jabatan C', 'A.id_jabatan=C.id_jabatan', 'left');
    $this->db->where('A.id_user <>', 0);
    $this->db->where('A.id_kode_lokasi', $id_kode_lokasi);
    $this->db->order_by('id_jabatan', 'asc');

    return
      $this->db->get();
    // die($this->db->last_query());
  }


  function get_kode_jenis($kode)
  {
    $this->db->where('kode_kelompok', $kode['kode_kelompok']);
    if ($kode['kode_kelompok'] == '5') {
      $this->db->where_in('kode_jenis', array('02', '03', '04'));
    }
    $res = $this->db->get('view_kode_barang_jenis');
    return $res->result_array();
    // die($this->db->last_query());
  }
}
