<?php

if (!defined('BASEPATH')) exit('No direct script access allowed');

class Kib_f_model extends CI_Model
{

  public $table = 'tbl_kib_f';
  public $id = 'id_kib_f';
  public $order = 'DESC';
  public $tbl_barang = 'tbl_kode_barang';
  public $tbl_kode_lokasi = 'tbl_kode_lokasi';

  function __construct()
  {
    parent::__construct();
  }

  // datatables
  function json()
  {
    $this->load->helper('my_datatable');
    $session = $this->session->userdata('session');
    $this->datatables->select('A.id_kib_f,A.kode_barang, A.nama_barang, A.nama_barang_migrasi,
      A.nama_barang as nama_barang_desc,A.bangunan,A.kontruksi_bertingkat,A.kontruksi_beton,
      A.luas_m2,A.lokasi_alamat,A.dokumen_tanggal,A.dokumen_nomor,A.tanggal_mulai,
      A.status_tanah,A.nomor_kode_tanah,A.asal_usul,A.nilai_kontrak,A.keterangan,
      A.kode_lokasi,A.validasi, C.instansi, reject_note,COALESCE(A.id_inventaris,A.id_kib_f) as id_inventaris,A.validasi as status_validasi,A.nomor_register,A.deskripsi ');
    $this->datatables->from('tbl_kib_f A');
   $this->datatables->join($this->tbl_barang . ' B', 'A.id_kode_barang = B.id_kode_barang','LEFT');
    $this->datatables->join($this->tbl_kode_lokasi . ' C', 'A.id_kode_lokasi = C.id_kode_lokasi','LEFT');
    $this->datatables->where('A.status_barang', 'kib');
    $this->datatables->where(" (id_kode_barang_aset_lainya < 1 and A.status_barang <> 'aset_lainya') ", NULL, FALSE);
    $session = $this->session->userdata('session');
    $lokasi = explode('.', $session->kode_lokasi);
    $pengguna = $lokasi[3];
    $kuasa_pengguna =  $lokasi[4];
    /*
    1: superadmin; 2:adminskpd; 5:kepalaskpd
     */
    /*if ($session->id_role != '1' and $kuasa_pengguna != '*') //bukan super admin, bukan skpd
        $this->datatables->where('A.id_kode_lokasi', $session->id_kode_lokasi);
    else if ($kuasa_pengguna == '*') //jika bukan skpd(hak skses)
        $this->datatables->where('C.pengguna', $pengguna);
        */

    /* update 11 sep 20     karena super admin bisa memilih lokasi   */ 
    if (cek_hak_akses('superadmin', $session->id_role)) {

      if($this->input->post('id_pemilik') != ''){
        $this->datatables->where('id_pemilik', $this->input->post('id_pemilik')); // where id pemilik
      }
      
      if($this->input->post('id_pengguna') != ''){
        $this->datatables->where('C.pengguna', $this->input->post('id_pengguna')); // where id pengguna
      }
      
      if($this->input->post('id_kuasa_pengguna') != ''){
        $this->datatables->where('A.id_kode_lokasi', $this->input->post('id_kuasa_pengguna')); // where id kuasa pengguna
      }
    } else
  
    if (cek_hak_akses('skpd', $session->id_role)) {
      $this->datatables->where('C.pengguna', $pengguna); //jika skpd
    } else { // jika bukan superadmin dan bukan skpd
      // $this->datatables->where('A.id_kode_lokasi', $session->id_kode_lokasi);
      
      if($this->input->post('id_pemilik') != ''){
        $this->datatables->where('id_pemilik', $this->input->post('id_pemilik')); // where id pemilik
      }
      
      if($this->input->post('id_pengguna') != ''){
        $this->datatables->where('C.pengguna', $this->input->post('id_pengguna')); // where id pengguna
      }
      
      if($this->input->post('id_kuasa_pengguna') != ''){
        $this->datatables->where('A.id_kode_lokasi', $this->input->post('id_kuasa_pengguna')); // where id kuasa pengguna
      }
    }
    $this->datatables->edit_column('validasi', '$1', 'status_validasi_kib(validasi,reject_note)');
    $this->datatables->add_column('action', '$1', 'get_action(' . $session->id_role . ',kib_f,' . $this->id . ', validasi)');
    $this->datatables->edit_column('luas_m2', '$1', 'format_number(luas_m2)');
    $this->datatables->edit_column('dokumen_tanggal', '$1', 'date_time(dokumen_tanggal)');
    $this->datatables->edit_column('tanggal_mulai', '$1', 'date_time(tanggal_mulai)');
    $this->datatables->edit_column('nilai_kontrak', '$1', 'format_float(nilai_kontrak)');
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
  function total_rows($q = NULL)
  {
    $this->db->like('id_kib_f', $q);
    $this->db->or_like('nama_barang', $q);
    $this->db->or_like('bangunan', $q);
    $this->db->or_like('kontruksi_bertingkat', $q);
    $this->db->or_like('kontruksi_beton', $q);
    $this->db->or_like('luas_m2', $q);
    $this->db->or_like('lokasi_alamat', $q);
    $this->db->or_like('dokumen_tanggal', $q);
    $this->db->or_like('dokumen_nomor', $q);
    $this->db->or_like('tanggal_mulai', $q);
    $this->db->or_like('status_tanah', $q);
    $this->db->or_like('nomor_kode_tanah', $q);
    $this->db->or_like('asal_usul', $q);
    $this->db->or_like('nilai_kontrak', $q);
    $this->db->or_like('keterangan', $q);
	$this->db->or_like('deskripsi', $q);
    $this->db->from($this->table);
    return $this->db->count_all_results();
  }

  // get data with limit and search
  function get_limit_data($limit, $start = 0, $q = NULL)
  {
    $this->db->order_by($this->id, $this->order);
    $this->db->like('id_kib_f', $q);
    $this->db->or_like('nama_barang', $q);
    $this->db->or_like('bangunan', $q);
    $this->db->or_like('kontruksi_bertingkat', $q);
    $this->db->or_like('kontruksi_beton', $q);
    $this->db->or_like('luas_m2', $q);
    $this->db->or_like('lokasi_alamat', $q);
    $this->db->or_like('dokumen_tanggal', $q);
    $this->db->or_like('dokumen_nomor', $q);
    $this->db->or_like('tanggal_mulai', $q);
    $this->db->or_like('status_tanah', $q);
    $this->db->or_like('nomor_kode_tanah', $q);
    $this->db->or_like('asal_usul', $q);
    $this->db->or_like('nilai_kontrak', $q);
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

  // delete data
  function delete($id)
  {
    $this->db->where($this->id, $id);
    $this->db->update($this->table, array('status_barang' => 'terhapus'));
  }

  // delete histori barang
  function delete_histori_barang($id)
  {
    $this->db->where('id_kib', $id);
    $this->db->where('kode_jenis', '06'); // kib f
    $this->db->delete('tbl_histori_barang');
  }

  // delete histori kib
  function delete_histori_kib($id)
  {
    $this->db->where('id_kib_f', $id);
    $this->db->delete('tbl_kib_f_histori');
  }

  // get kib
  function get_kib()
  {
    // $this->db->join($this->tbl_kode_lokasi, $this->table.'kode_lokasi='.$this->tbl_kode_lokasi.'')
    return $this->db->get($this->table)->result();
  }
}

/* End of file Kib_f_model.php */
/* Location: ./application/models/Kib_f_model.php */
/* Please DO NOT modify this information : */
/* Generated by Harviacode Codeigniter CRUD Generator 2018-08-07 08:14:32 */
/* http://harviacode.com */
