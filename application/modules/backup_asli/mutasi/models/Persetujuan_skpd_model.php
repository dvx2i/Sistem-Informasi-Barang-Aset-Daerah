<?php

if (!defined('BASEPATH')) exit('No direct script access allowed');

class Persetujuan_skpd_model extends CI_Model
{

  public $table = 'tbl_mutasi';
  public $id = 'id_mutasi';
  public $order = 'DESC';
  public $tbl_kode_barang = 'tbl_kode_barang';

  public $tbl_mutasi_barang = 'tbl_mutasi_barang';
  public $id_mutasi_barang = 'id_mutasi_barang';


  function __construct()
  {
    parent::__construct();
  }

  // datatables
  function json()
  {
    $this->load->helper('my_datatable');
    $this->load->model('global_mutasi_model');
    $id_lokasi = $this->session->userdata('session')->id_kode_lokasi;
    $id_skpd = $this->global_mutasi_model->get_skpd($id_lokasi)['id_pengguna'];


    $this->db->query('SET lc_time_names = "id_ID";');
    $this->datatables->select("A.id_mutasi,DATE_FORMAT(tanggal, '%d %M %Y') as tanggal,
          id_kode_lokasi_lama,id_kode_lokasi_baru, status_validasi,created_at,updated_at, 
          A.id_skpd_lama, A.id_skpd_lama,
          A.persetujuan_skpd_lama, A.persetujuan_skpd_baru, B.instansi as pengguna_lama, 
          C.instansi as pengguna_baru, $id_skpd as id_skpd,  
          case when $id_skpd = A.id_skpd_lama then 'skpd_lama' when $id_skpd = A.id_skpd_baru then 'skpd_baru' end as skpd_aktiv ");
    $this->datatables->from('tbl_mutasi A');
    $this->datatables->join('tbl_kode_lokasi B', 'A.id_kode_lokasi_lama=B.id_kode_lokasi', 'left');
    $this->datatables->join('tbl_kode_lokasi C', 'A.id_kode_lokasi_BARU=C.id_kode_lokasi', 'left');

    $this->datatables->where('A.id_skpd_lama <> ', 'A.id_skpd_baru', false); //127
    $this->datatables->where('(A.id_skpd_lama ', $id_skpd, false);
    $this->datatables->or_where('A.id_skpd_baru ', $id_skpd . ')', false);

    // $this->datatables->add_column('barang', '$1', 'barang_pengguna_baru(id_mutasi)');
    $this->datatables->add_column('detail', '<a href="' . base_url("mutasi/persetujuan_skpd/detail/$1") . '"> <button class="btn btn-info" title="Detail"> <span class="fa fa-search"></span></button></a>', 'id_mutasi');
    $this->datatables->add_column('status_persetujuan_lama_checkbox', '$1', 'status_persetujuan_lama_checkbox(id_mutasi,skpd_aktiv,persetujuan_skpd_lama)');
    $this->datatables->add_column('status_persetujuan_baru_checkbox', '$1', 'status_persetujuan_baru_checkbox(id_mutasi,skpd_aktiv,persetujuan_skpd_baru)');

    return
      $this->datatables->generate();
    // die($this->db->last_query());
  }

  function json_detail($id_mutasi)
  {
    $this->load->helper('my_datatable');
    $this->db->query('SET lc_time_names = "id_ID";');
    $this->datatables->select('A.id_mutasi_barang, A.status_diterima,A.id_kib,B.kib_name, B.nomor_register, C.kode_barang, C.nama_barang');
    $this->datatables->from('tbl_mutasi_barang A');
    // $this->datatables->join('view_kib B', 'A.kode_jenis=B.kode_jenis and A.id_kib=B.id_kib','left');
    $this->datatables->join('(
      SELECT kode_jenis, id_kib, id_pemilik, kib_name, nomor_register, id_kode_barang, merk_type, id_kode_lokasi, kode_lokasi, asal_usul, tanggal_pembelian, tanggal_perolehan, validasi, kib_f, harga, status_barang
      FROM `view_mutasi`
     
    ) B', 'A.kode_jenis=B.kode_jenis and A.id_kib=B.id_kib', 'left');
    $this->datatables->join('tbl_kode_barang C', 'B.id_kode_barang=C.id_kode_barang', 'left');
    $this->datatables->where('A.id_mutasi', $id_mutasi);
    $this->datatables->edit_column('status_diterima', '$1', 'status_diterima(status_diterima)');

    return
      $this->datatables->generate();
  }

  // get data by id
  function get_by_id($id)
  {
    $this->db->where($this->id, $id);
    return $this->db->get($this->table)->row();
  }
  /*
  function get_jenis_mutasi($id_mutasi){
    $this->db->select('group_concat(distinct kode_jenis) as jenis');
    $this->db->where($this->id,$id_mutasi);
    return $this->db->get($this->tbl_mutasi_barang)->row();
  }

  function update_pengajuan($data, $id_mutasi_barang){
    $this->db->update_batch($this->tbl_mutasi_barang,$data, $id_mutasi_barang);
  }

  function update_url_bast($id_mutasi, $data){
    $this->db->where('id_mutasi', $id_mutasi);
    $this->db->update('tbl_mutasi', $data);
  }

  function update_pengajuan_register($id_mutasi, $data){
    $this->db->where('id_mutasi', $id_mutasi);
    $this->db->update('tbl_mutasi', $data);
  }

  function mutasi_action($id_mutasi){
    $query = $this->db->query("call proc_mutasi(".$id_mutasi.")");
    $res      = $query->result();

    //add this two line
    $query->next_result();
    $query->free_result();
    //end of new code

    return $res;
  }
  */


  function update_persetujuan_skpd($id_mutasi, $data)
  {
    $this->db->where('id_mutasi', $id_mutasi);
    $this->db->update('tbl_mutasi', $data);
  }
}
