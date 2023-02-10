<?php

if (!defined('BASEPATH')) exit('No direct script access allowed');

class Kib_e_model extends CI_Model
{
  public $table = 'tbl_kib_e';
  public $id = 'id_kib_e';
  public $order = 'DESC';
   public $tbl_barang = 'tbl_kode_barang';
  public $tbl_kode_lokasi = 'tbl_kode_lokasi';
   public $tbl_ruang_barang = 'tbl_ruang_barang';

  function __construct()
  {
    parent::__construct();
  }

  // datatables
  function json()
  {
    $this->load->helper('my_datatable');
	/*$this->datatables->select('(case when (kib_f="1") then "KIB E" else "KIB F" end) as jenis, A.nama_barang, A.nama_barang_migrasi,
      A.id_kib_e, A.nama_barang as nama_barang_desc,
      A.kode_barang,A.nomor_register,A.judul_pencipta,A.spesifikasi,A.kesenian_asal_daerah,
      A.kesenian_pencipta,A.kesenian_bahan,A.hewan_tumbuhan_jenis,A.hewan_tumbuhan_ukuran,
      A.jumlah,tahun_pembelian,A.asal_usul,A.harga,A.keterangan, A.kode_lokasi,A.validasi, C.instansi');
    $this->datatables->from('tbl_kib_e A');
     $this->datatables->join($this->tbl_barang . ' B', 'A.id_kode_barang = B.id_kode_barang','LEFT');
    $this->datatables->join($this->tbl_kode_lokasi . ' C', 'A.id_kode_lokasi = C.id_kode_lokasi','LEFT');
	  */
    $this->datatables->select('(case when (kib_f="1") then "KIB E" else "KIB F" end) as jenis, A.nama_barang, A.nama_barang_migrasi,
      A.id_kib_e, A.nama_barang as nama_barang_desc,
      A.kode_barang,A.nomor_register,A.judul_pencipta,A.spesifikasi,A.tahun_pembelian,A.asal_usul,A.harga,A.keterangan, A.kode_lokasi,A.validasi, C.instansi,reject_note,COALESCE(A.id_inventaris,A.id_kib_e) as id_inventaris,A.validasi as status_validasi,A.deskripsi ');
    $this->datatables->from('tbl_kib_e A');
    // $this->datatables->join($this->tbl_barang . ' B', 'A.id_kode_barang = B.id_kode_barang','LEFT');
    $this->datatables->join($this->tbl_kode_lokasi . ' C', 'A.id_kode_lokasi = C.id_kode_lokasi','LEFT');
    $this->datatables->where('A.status_barang', 'kib');
    $this->datatables->where(" (id_kode_barang_aset_lainya < 1 and A.status_barang <> 'aset_lainya') ", NULL, FALSE);
    $this->datatables->join($this->tbl_ruang_barang . ' D', 'A.id_kib_e = D.id_kib','LEFT');
    $this->datatables->where('A.status_barang', 'kib');
    $this->datatables->where(" (id_kode_barang_aset_lainya < 1 and A.status_barang <> 'aset_lainya') ", NULL, FALSE);
   if($this->input->post('id_ruang_asal') != ''){
        $this->datatables->where('D.id_ruang', $this->input->post('id_ruang_asal')); // where id kuasa pengguna
      }
    //$this->datatables->edit_column('jumlah', '$1', 'format_number(jumlah)');
    $this->datatables->edit_column('harga', '$1', 'format_float(harga)');
    return $this->datatables->generate();
  }

  // get all
  function get_all()
  {
    $this->db->order_by($this->id, $this->order);
    return $this->db->get($this->table)->result();
  }

  // get data by id
  function get_by_id($id)
  {
    $this->db->where($this->id, $id);
    return $this->db->get($this->table)->row();
  }

  // get total rows
  /*
  function total_rows($q = NULL)
  {
    $this->db->like('id_kib_e', $q);
    $this->db->or_like('nama_barang', $q);
    $this->db->or_like('kode_barang', $q);
    $this->db->or_like('nomor_register', $q);
    $this->db->or_like('judul_pencipta', $q);
    $this->db->or_like('spesifikasi', $q);
    $this->db->or_like('kesenian_asal_daerah', $q);
    $this->db->or_like('kesenian_pencipta', $q);
    $this->db->or_like('kesenian_bahan', $q);
    $this->db->or_like('hewan_tumbuhan_jenis', $q);
    $this->db->or_like('hewan_tumbuhan_ukuran', $q);
    $this->db->or_like('jumlah', $q);
    $this->db->or_like('tahun_pembelian', $q);
    $this->db->or_like('asal_usul', $q);
    $this->db->or_like('harga', $q);
    $this->db->or_like('keterangan', $q);
    $this->db->from($this->table);
    return $this->db->count_all_results();
  }

  // get data with limit and search
  function get_limit_data($limit, $start = 0, $q = NULL)
  {
    $this->db->order_by($this->id, $this->order);
    $this->db->like('id_kib_e', $q);
    $this->db->or_like('nama_barang', $q);
    $this->db->or_like('kode_barang', $q);
    $this->db->or_like('nomor_register', $q);
    $this->db->or_like('judul_pencipta', $q);
    $this->db->or_like('spesifikasi', $q);
    $this->db->or_like('kesenian_asal_daerah', $q);
    $this->db->or_like('kesenian_pencipta', $q);
    $this->db->or_like('kesenian_bahan', $q);
    $this->db->or_like('hewan_tumbuhan_jenis', $q);
    $this->db->or_like('hewan_tumbuhan_ukuran', $q);
    $this->db->or_like('jumlah', $q);
    $this->db->or_like('tahun_pembelian', $q);
    $this->db->or_like('asal_usul', $q);
    $this->db->or_like('harga', $q);
    $this->db->or_like('keterangan', $q);
    $this->db->limit($limit, $start);
    return $this->db->get($this->table)->result();
  }
*/
function total_rows($q = NULL)
  {
    $this->db->like('id_kib_e', $q);
    $this->db->or_like('nama_barang', $q);
    $this->db->or_like('kode_barang', $q);
    $this->db->or_like('nomor_register', $q);
    $this->db->or_like('judul_pencipta', $q);
    $this->db->or_like('spesifikasi', $q);
    
    $this->db->or_like('tahun_pembelian', $q);
    $this->db->or_like('asal_usul', $q);
    $this->db->or_like('harga', $q);
    $this->db->or_like('keterangan', $q);
	$this->db->or_like('deskripsi', $q);
    $this->db->from($this->table);
    return $this->db->count_all_results();
  }

  // get data with limit and search
  function get_limit_data($limit, $start = 0, $q = NULL)
  {
    $this->db->order_by($this->id, $this->order);
    $this->db->like('id_kib_e', $q);
    $this->db->or_like('nama_barang', $q);
    $this->db->or_like('kode_barang', $q);
    $this->db->or_like('nomor_register', $q);
    $this->db->or_like('judul_pencipta', $q);
    $this->db->or_like('spesifikasi', $q);
   
    $this->db->or_like('tahun_pembelian', $q);
    $this->db->or_like('asal_usul', $q);
    $this->db->or_like('harga', $q);
    $this->db->or_like('keterangan', $q);
	$this->db->or_like('deskripsi', $q);
    $this->db->limit($limit, $start);
    return $this->db->get($this->table)->result();
  }
  // insert data
  function insert($data)
  {
    $this->db->insert($this->table, $data);
    return $this->db->insert_id();
  }

  // update data
  function update($id, $data)
  {
    $this->db->where($this->id, $id);
    $this->db->update($this->table, $data);
  }
  
  // update data
  function update_input_banyak($id, $data)
  {
    $this->db->where('input_banyak', $id);
    $this->db->update($this->table, $data);
  }

  // delete data
  function delete($id)
  {
    $this->db->where($this->id, $id);
    $this->db->delete($this->table);
  }

  // delete histori barang
  function delete_histori_barang($id)
  {
    $this->db->where('id_kib', $id);
    $this->db->where('kode_jenis', '05'); // kib e
    $this->db->delete('tbl_histori_barang');
  }

  // delete histori kib
  function delete_histori_kib($id)
  {
    $this->db->where('id_kib_e', $id);
    $this->db->delete('tbl_kib_e_histori');
  }
}

/* End of file Kib_e_model.php */
/* Location: ./application/models/Kib_e_model.php */
/* Please DO NOT modify this information : */
/* Generated by Harviacode Codeigniter CRUD Generator 2018-08-07 08:14:13 */
/* http://harviacode.com */
