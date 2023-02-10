<?php

if (!defined('BASEPATH')) exit('No direct script access allowed');

class Kib_d_model extends CI_Model
{
  public $table = 'tbl_kib_d';
  public $id = 'id_kib_d';
  public $order = 'DESC';
  public $tbl_kode_lokasi = 'tbl_kode_lokasi';
  public $tbl_barang = 'tbl_kode_barang';

  function __construct()
  {
    parent::__construct();
  }

  // datatables
  function json()
  {
    $this->load->helper('my_datatable');
    $this->datatables->select('(case when (kib_f="1") then "KIB D" else "KIB F" end) as jenis, A.nama_barang, A.nama_barang_migrasi,
      A.id_kib_d, A.nama_barang as nama_barang_desc,
      A.kode_barang,A.nomor_register,A.konstruksi,A.panjang_km,A.lebar_m,A.luas_m2,A.letak_lokasi,A.dokumen_tanggal,A.dokumen_nomor,A.status_tanah,A.kode_tanah,A.asal_usul,A.harga,A.kondisi,A.keterangan,A.kode_lokasi,A.validasi, C.instansi, reject_note,COALESCE(A.id_inventaris,A.id_kib_d) as id_inventaris,A.validasi as status_validasi,A.deskripsi , 
      CASE WHEN D.status_keberadaan = "1" THEN "Ada" ELSE CONCAT("Tidak Ada </br>","(",keterangan_tdk_ada,")") END keberadaan,CASE WHEN kondisi_barang = "1" THEN "Baik" WHEN kondisi_barang = "2" THEN "Rusak Ringan" WHEN kondisi_barang = "3" THEN "Rusak Berat" ELSE "" END kondisi', FALSE);
    $this->datatables->from('tbl_kib_d A');
	$this->datatables->join($this->tbl_barang . ' B', 'A.id_kode_barang = B.id_kode_barang','LEFT');
    $this->datatables->join($this->tbl_kode_lokasi . ' C', 'A.id_kode_lokasi = C.id_kode_lokasi','LEFT');
    $this->datatables->join('tbl_sensus_barang D', 'A.id_kib_d = D.id_kib AND "04" = D.kode_jenis','LEFT');
    $this->datatables->where('A.status_barang', 'kib');
    $this->datatables->where(" (A.id_kode_barang_aset_lainya < 1 and A.status_barang <> 'aset_lainya') ", NULL, FALSE);
    $session = $this->session->userdata('session');
    $lokasi = explode('.', $session->kode_lokasi);
    $pengguna = $lokasi[3];
    $kuasa_pengguna =  $lokasi[4];
    
    if($this->input->post('id_kode_barang') != ''){
      $this->datatables->where('A.id_kode_barang', $this->input->post('id_kode_barang')); // 
    }
    if($this->input->post('status_keberadaan') != ''){
      $this->datatables->where('D.status_keberadaan', $this->input->post('status_keberadaan')); // 
    }
    if($this->input->post('kondisi_barang') != ''){
      $this->datatables->where('D.kondisi_barang', $this->input->post('kondisi_barang')); // 
    }
    /*
    1: superadmin; 2:adminskpd; 5:kepalaskpd
     */
    /*if ($session->id_role != '1' and $kuasa_pengguna != '*') //bukan super admin, bukan skpd
        $this->datatables->where('A.id_kode_lokasi', $session->id_kode_lokasi);
    else if ($kuasa_pengguna == '*') //jika bukan skpd(hak skses)
        $this->datatables->where('C.pengguna', $pengguna);
        */
    /* update 11 sep 20     karena super admin bisa memilih lokasi */   
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
    $this->datatables->add_column('action', '$1', 'get_action(' . $session->id_role . ',kib_d,' . $this->id . ', validasi)');
    $this->datatables->edit_column('panjang_km', '$1', 'format_number(panjang_km)');
    $this->datatables->edit_column('lebar_m', '$1', 'format_number(lebar_m)');
    $this->datatables->edit_column('luas_m2', '$1', 'format_number(luas_m2)');
    $this->datatables->edit_column('dokumen_tanggal', '$1', 'date_time(dokumen_tanggal)');
    $this->datatables->edit_column('harga', '$1', 'format_float(harga)');
    return 
	$this->datatables->generate();
	//echo $this->db->last_query();die;
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
    $this->db->like('id_kib_d', $q);
    $this->db->or_like('nama_barang', $q);
    $this->db->or_like('kode_barang', $q);
    $this->db->or_like('nomor_register', $q);
    $this->db->or_like('konstruksi', $q);
    $this->db->or_like('panjang_km', $q);
    $this->db->or_like('lebar_m', $q);
    $this->db->or_like('luas_m2', $q);
    $this->db->or_like('letak_lokasi', $q);
    $this->db->or_like('dokumen_tanggal', $q);
    $this->db->or_like('dokumen_nomor', $q);
    $this->db->or_like('status_tanah', $q);
    $this->db->or_like('kode_tanah', $q);
    $this->db->or_like('asal_usul', $q);
    $this->db->or_like('harga', $q);
    $this->db->or_like('kondisi', $q);
    $this->db->or_like('keterangan', $q);
	$this->db->or_like('deskripsi', $q);
    $this->db->from($this->table);
    return $this->db->count_all_results();
  }

  // get data with limit and search
  function get_limit_data($limit, $start = 0, $q = NULL)
  {
    $this->db->order_by($this->id, $this->order);
    $this->db->like('id_kib_d', $q);
    $this->db->or_like('nama_barang', $q);
    $this->db->or_like('kode_barang', $q);
    $this->db->or_like('nomor_register', $q);
    $this->db->or_like('konstruksi', $q);
    $this->db->or_like('panjang_km', $q);
    $this->db->or_like('lebar_m', $q);
    $this->db->or_like('luas_m2', $q);
    $this->db->or_like('letak_lokasi', $q);
    $this->db->or_like('dokumen_tanggal', $q);
    $this->db->or_like('dokumen_nomor', $q);
    $this->db->or_like('status_tanah', $q);
    $this->db->or_like('kode_tanah', $q);
    $this->db->or_like('asal_usul', $q);
    $this->db->or_like('harga', $q);
    $this->db->or_like('kondisi', $q);
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
    $this->db->delete($this->table);
  }

  // delete histori barang
  function delete_histori_barang($id)
  {
    $this->db->where('id_kib', $id);
    $this->db->where('kode_jenis', '04'); // kib d
    $this->db->delete('tbl_histori_barang');
  }

  // delete histori kib
  function delete_histori_kib($id)
  {
    $this->db->where('id_kib_d', $id);
    $this->db->delete('tbl_kib_d_histori');
  }
  // insert data
  function insert_sensus($data)
  {
    // die(json_encode($data));
    $this->db->insert('tbl_sensus_barang', $data);
    // die($this->db->last_query());
    return $this->db->insert_id();
  }
  
  // delete histori kib
  function delete_sensus($kode_jenis,$id)
  {
    $this->db->where('id_kib', $id);
    $this->db->where('kode_jenis', $kode_jenis);
    $this->db->delete('tbl_sensus_barang');
  }
}

/* End of file Kib_d_model.php */
/* Location: ./application/models/Kib_d_model.php */
/* Please DO NOT modify this information : */
/* Generated by Harviacode Codeigniter CRUD Generator 2018-08-07 08:13:55 */
/* http://harviacode.com */
