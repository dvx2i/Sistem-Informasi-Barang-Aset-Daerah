<?php

if (!defined('BASEPATH')) exit('No direct script access allowed');

class Realisasi_model extends CI_Model
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




  // get all
  function get_all($data)
  {
    $and = '';
    if ($data['id_pengguna'] != '') {
      $and =  " AND c.pengguna = '".$data['pengguna']."' ";
    } elseif($data['id_kuasa_pengguna'] != '') {
      $and =  " AND c.pengguna = '".$data['pengguna']."' AND c.id_kode_lokasi = '".$data['id_kode_lokasi']."'";
    }
    
    if ($data['jenis'] == 'bulan') {
      $and .=  " AND MONTH(tanggal_histori) = '".$data['bulan']."' AND YEAR(tanggal_histori) = '".$data['tahun']."' ";
    } else {
      $and .=  " AND YEAR(tanggal_histori) = '".$data['tahun']."' ";
    }

    $sql = "SELECT b.kode_rekening,b.nama_rekening,SUM(a.harga) AS realisasi FROM tbl_histori_barang a
    JOIN tbl_master_rekening b ON a.`id_rekening` = b.`id_rekening`
    JOIN tbl_kode_lokasi c ON a.`id_kode_lokasi` = c.`id_kode_lokasi`
    WHERE a.`status_histori` IN ('entri','kapitalisasi','koreksi_tambah') AND a.`id_sumber_dana` IN ('1','2')
    ".$and."
    GROUP BY a.`id_rekening`;";
// die($sql); 
    $query = $this->db->query($sql);
    return $query->result_array();
  }

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

  function getParentLokasi($kode_lokasi)
  {
    $this->db->where('kode_lokasi', $kode_lokasi);
    return $this->db->get('tbl_kode_lokasi')->row();
  }
}
