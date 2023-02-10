<?php

if (!defined('BASEPATH')) exit('No direct script access allowed');

class Rekap_aset_tetap_model extends CI_Model
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

      $sql = "SELECT a.instansi,penyusutan1.nilai_penyusutan+penyusutan2.nilai_penyusutan nilai_penyusutan,penyusutan2.akumulasi_penyusutan,penyusutan2.nilai_buku,kib_a.harga AS kib_a,kib_b.harga AS kib_b,kib_c.harga AS kib_c,kib_d.harga AS kib_d,kib_e.harga AS kib_e,kib_f.harga AS kib_f
      FROM view_pengguna a 
      LEFT JOIN ( SELECT SUM(harga) AS harga,id_pengguna,objek FROM tbl_histori_rekap_mutasi WHERE bulan_data = '12' AND tahun_data = '2022' AND LENGTH(objek) = '6'  AND objek = '1.3.01' AND kode_intra_extra = '".$data['intra_ekstra']."' GROUP BY id_pengguna) kib_a
      ON TRIM(LEADING '0' FROM a.pengguna) = kib_a.id_pengguna
      LEFT JOIN ( SELECT SUM(harga) AS harga,id_pengguna,objek FROM tbl_histori_rekap_mutasi WHERE bulan_data = '12' AND tahun_data = '2022' AND LENGTH(objek) = '6'  AND objek = '1.3.02' AND kode_intra_extra = '".$data['intra_ekstra']."' GROUP BY id_pengguna) kib_b
      ON TRIM(LEADING '0' FROM a.pengguna) = kib_b.id_pengguna
      LEFT JOIN ( SELECT SUM(harga) AS harga,id_pengguna,objek FROM tbl_histori_rekap_mutasi WHERE bulan_data = '12' AND tahun_data = '2022' AND LENGTH(objek) = '6'  AND objek = '1.3.03' AND kode_intra_extra = '".$data['intra_ekstra']."' GROUP BY id_pengguna) kib_c
      ON TRIM(LEADING '0' FROM a.pengguna) = kib_c.id_pengguna
      LEFT JOIN ( SELECT SUM(harga) AS harga,id_pengguna,objek FROM tbl_histori_rekap_mutasi WHERE bulan_data = '12' AND tahun_data = '2022' AND LENGTH(objek) = '6'  AND objek = '1.3.04' AND kode_intra_extra = '".$data['intra_ekstra']."' GROUP BY id_pengguna) kib_d
      ON TRIM(LEADING '0' FROM a.pengguna) = kib_d.id_pengguna
      LEFT JOIN ( SELECT SUM(harga) AS harga,id_pengguna,objek FROM tbl_histori_rekap_mutasi WHERE bulan_data = '12' AND tahun_data = '2022' AND LENGTH(objek) = '6'  AND objek = '1.3.05' AND kode_intra_extra = '".$data['intra_ekstra']."' GROUP BY id_pengguna) kib_e
      ON TRIM(LEADING '0' FROM a.pengguna) = kib_e.id_pengguna
      LEFT JOIN ( SELECT SUM(harga) AS harga,id_pengguna,objek FROM tbl_histori_rekap_mutasi WHERE bulan_data = '12' AND tahun_data = '2022' AND LENGTH(objek) = '6'  AND objek = '1.3.06' AND kode_intra_extra = '".$data['intra_ekstra']."' GROUP BY id_pengguna) kib_f
      ON TRIM(LEADING '0' FROM a.pengguna) = kib_f.id_pengguna
      LEFT JOIN (SELECT id_pengguna,SUM(nilai_penyusutan) AS nilai_penyusutan,SUM(nilai_buku) nilai_buku,SUM(akumulasi_penyusutan) akumulasi_penyusutan,objek FROM tbl_histori_rekap_penyusutan WHERE bulan_data = '12' AND tahun_data = '2022' AND objek = '1.3' AND kode_intra_extra = '".$data['intra_ekstra']."' GROUP BY id_pengguna) penyusutan2
      ON TRIM(LEADING '0' FROM a.pengguna) = penyusutan2.id_pengguna
      LEFT JOIN (SELECT id_pengguna,SUM(nilai_penyusutan) AS nilai_penyusutan,SUM(nilai_buku) nilai_buku,SUM(akumulasi_penyusutan) akumulasi_penyusutan,objek FROM tbl_histori_rekap_penyusutan WHERE bulan_data = '6' AND tahun_data = '2022' AND objek = '1.3' AND kode_intra_extra = '".$data['intra_ekstra']."' GROUP BY id_pengguna) penyusutan1
      ON TRIM(LEADING '0' FROM a.pengguna) = penyusutan1.id_pengguna
      ";
   
  
    $query = $this->db->query($sql);
    // if($this->session->userdata('session')->id_upik == 'jss-a7324'){
    // die($this->db->last_query());
    // }
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
