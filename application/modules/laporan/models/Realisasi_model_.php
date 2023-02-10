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
    $where1 = " WHERE 1=1 ";
    $where2 = " WHERE h.`status_histori` IN ('entri','kapitalisasi','koreksi_tambah') AND h.`id_sumber_dana` IN ('1','2') ";
    $where3 = " WHERE (harga IS NOT NULL OR nilai_realisasi > 0)  ";

    if ($data['id_pengguna'] != '') {
      $where2 .= " AND l.pengguna = '".$data['pengguna']."' ";
      $where3 .= " AND TRIM(REPLACE(a.kode_unit,'.', '')) = TRIM(LEADING '0' FROM '".$data['pengguna']."') ";
    } 
    
    if ($data['jenis'] == 'bulan') {
      $where2 .= " AND MONTH(tanggal_histori) = '".$data['bulan']."' AND YEAR(tanggal_histori) = '".$data['tahun']."' ";
      $where1 .= " AND bulan = '".$data['bulan']."' AND tahun = '".$data['tahun']."'"; 
    }
    else if ($data['jenis'] == 'semester') {
      $where2    .=  " AND tanggal_histori BETWEEN '".$data['start_date']."' AND '".$data['last_date']."' ";
      $where1= " AND (bulan BETWEEN '".$data['start_bulan']."' AND '".$data['last_bulan']."' ) AND tahun = '".$data['tahun']."' "; 
    } else {
      $where2 .= " AND YEAR(tanggal_histori) = '".$data['tahun']."' ";
      $where1 .= " AND tahun = '".$data['tahun']."' "; 
    }

    $sql = "SELECT a.kode_rekening,a.nama_rekening,c.harga AS realisasi, SUM(a.nilai_dpa) AS dpa, SUM(a.nilai_realisasi) AS nilai_realisasi 
    FROM (SELECT kode_rekening,nama_rekening,CASE WHEN kode_unit = '7.01.06.' THEN '7.01.07.'
    WHEN kode_unit = '7.01.07.' THEN '7.01.08.'
    WHEN kode_unit = '7.01.08.' THEN '7.01.09.'
    WHEN kode_unit = '7.01.09.' THEN '7.01.10.'
    WHEN kode_unit = '7.01.10.' THEN '7.01.11.'
    WHEN kode_unit = '7.01.11.' THEN '7.01.06.' ELSE kode_unit END AS kode_unit,nilai_dpa,nilai_realisasi,bulan,tahun FROM sipkd_belanja_modal ".$where1." ) a
    JOIN tbl_master_rekening b ON SUBSTRING(a.kode_rekening, 1, CHAR_LENGTH(a.kode_rekening) - 1) = b.`kode_rekening` 
    LEFT JOIN (SELECT l.pengguna,id_rekening,SUM(harga) AS harga,status_histori,id_sumber_dana,tanggal_histori FROM (SELECT * FROM tbl_histori_barang GROUP BY id_kib,kode_jenis,id_kode_lokasi,status_histori,tanggal_histori,harga) h
    JOIN tbl_kode_lokasi  l  ON h.`id_kode_lokasi` = l.`id_kode_lokasi` ".$where2." GROUP BY `id_rekening`)  c
    ON b.id_rekening = c.id_rekening AND TRIM(REPLACE(a.kode_unit,'.', '')) = TRIM(LEADING '0' FROM c.pengguna) 
    ".$where3."
    GROUP BY a.`kode_rekening`";

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
