<?php

if (!defined('BASEPATH')) exit('No direct script access allowed');

class Rekap_mutasi_kib_model extends CI_Model
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
  
    if(substr($data['kode_jenis'], 1) != '5'){ // jika aset tetap)

      if($data['kode_jenis'] != ''){ //jika filter kode jenis diisi
          $kode_jenis  = " AND kode_jenis = '".$data['kode_jenis']."' ";
          $kode_jenis2 = " AND SUBSTR(jenis, 3, 1) = '3' AND SUBSTR(jenis, 5, 2) = '".$data['kode_jenis']."' ";
      }else{
        $kode_jenis = "";
        $kode_jenis2 = "";
      }

      $bulan_before = $data['bulan']-1; // bulan sebelum untuk saldo awal
      $tahun_before = $data['tahun'];

      if($data['bulan'] == '1'){ // jika bulan januari maka tahun - 1;
        $bulan_before = '12';
        $tahun_before = $data['tahun']-1;
      }

      if ($data['id_pengguna'] != '') { // jika unit lokasi dipilih 
      $group_by1 =  " AND b.pengguna = '".$data['pengguna']."' GROUP BY b.id_kode_lokasi "; // group by 1 = sub query
      $group_by2 =  " AND id_pengguna = TRIM(LEADING '0' FROM '".$data['pengguna']."') GROUP BY id_kode_lokasi "; // left group by 2 = join
      } else { 
        $id_kode_lokasi = $data['id_pengguna'];
        $group_by1 =  " GROUP BY b.pengguna "; // group by 1 = sub query
        $group_by2 =  " GROUP BY id_pengguna "; // left group by 2 = join
      }

      $sql = "SELECT sq.*,c.saldo_awal,((c.saldo_awal + sq.entri + sq.kapitalisasi + sq.mutasi_in + sq.reklas_plus) - (sq.penghapusan + sq.koreksi_kurang + sq.mutasi_out + sq.reklas_min)) AS saldo_akhir  
      FROM (
      SELECT b.id_kode_lokasi,b.instansi,b.`pengguna`,
      SUM(CASE WHEN status_histori = 'entri' THEN harga ELSE 0 END) AS entri,
      SUM(CASE WHEN status_histori = 'koreksi_tambah' THEN harga ELSE 0 END) AS kapitalisasi,
      SUM(CASE WHEN status_histori = 'mutasi_in' AND a.keterangan = 'proc_mutasi' THEN harga ELSE 0 END) AS mutasi_in,
      SUM(CASE WHEN status_histori = 'mutasi_in' AND a.keterangan = 'proc_reklas_kode' THEN harga ELSE 0 END) AS reklas_plus,
      SUM(CASE WHEN status_histori = 'penghapusan' THEN harga ELSE 0 END) AS penghapusan,
      SUM(CASE WHEN status_histori = 'koreksi_kurang' THEN harga ELSE 0 END) AS koreksi_kurang,
      SUM(CASE WHEN status_histori = 'mutasi_out' AND a.keterangan = 'proc_mutasi' THEN harga ELSE 0 END) AS mutasi_out,
      SUM(CASE WHEN status_histori = 'mutasi_out' AND a.keterangan = 'proc_reklas_kode' THEN harga ELSE 0 END) AS reklas_min
      FROM tbl_kode_lokasi b 
      LEFT JOIN tbl_histori_barang  a ON b.`id_kode_lokasi` = a.`id_kode_lokasi` ".$kode_jenis."
      WHERE (tanggal_histori BETWEEN '".$data['start_date']."' AND '".$data['last_date']."') AND kuasa_pengguna <> '*'   
      ".$group_by1.") sq
      LEFT JOIN (SELECT SUM(harga) AS saldo_awal,id_pengguna,id_kode_lokasi FROM tbl_histori_rekap_mutasi WHERE bulan_data = '".$bulan_before."' AND tahun_data = '".$tahun_before."' AND LENGTH(objek) = '6' AND kode_intra_extra = '".$data['intra_ekstra']."' ".$kode_jenis2." ".$group_by2.") c 
      ON sq.id_kode_lokasi = c.id_kode_lokasi;";
    }
  
    $query = $this->db->query($sql);
    // die($this->db->last_query());
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
