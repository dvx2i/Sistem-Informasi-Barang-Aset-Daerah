<?php

if (!defined('BASEPATH')) exit('No direct script access allowed');

class Pengecekan_barang_model extends CI_Model{

  public $table = 'tbl_penghapusan';
  public $id = 'id_penghapusan';
  public $order = 'DESC';
  public $tbl_kode_barang = 'tbl_kode_barang';

  public $tbl_penghapusan_barang = 'tbl_penghapusan_barang';
  public $id_penghapusan_barang = 'id_penghapusan_barang';


  function __construct(){
    parent::__construct();
  }

  // datatables
  function json() {
    $this->load->helper('penghapusan');
    $this->db->query('SET lc_time_names = "id_ID";');
    $this->datatables->select('A.id_penghapusan,DATE_FORMAT(tanggal_pengajuan, "%d %M %Y") as tanggal,A.id_kode_lokasi, A.status_proses,B.instansi,A.nama_paket');
    $this->datatables->from('tbl_penghapusan A');
    $this->datatables->join('tbl_kode_lokasi B','A.id_kode_lokasi=B.id_kode_lokasi','left');
    // $this->datatables->add_column('barang', '$1', 'barang_pengecekan(id_penghapusan)');
    $this->datatables->where('status_proses', '1');
    $this->datatables->add_column('action','$1','pengecekan_barang_action(id_penghapusan,status_proses)');
    return
    $this->datatables->generate();
  }

  // datatables
  function json_detail($id_penghapusan) {
    $this->load->helper('penghapusan');
    $this->db->query('SET lc_time_names = "id_ID";');
    $this->datatables->select('A.kib_name, A.id_kib, A.nomor_register, B.status_diterima, C.kode_barang, ,CASE WHEN A.id_inventaris <> "" THEN CONCAT(C.nama_barang," / ",A.deskripsi) ELSE C.nama_barang END nama_barang, A.id_inventaris ');
    $this->datatables->from('view_penghapusan as A');
    $this->datatables->join('tbl_penghapusan_barang as B','A.kode_jenis=B.kode_jenis and A.id_kib=B.id_kib','left');
    $this->datatables->join('tbl_kode_barang as C','A.id_kode_barang=C.id_kode_barang','left');
    $this->datatables->join('tbl_kode_barang as D','B.id_kode_barang_baru=D.id_kode_barang','left');
    $this->datatables->where('B.id_penghapusan',$id_penghapusan);
    $this->datatables->add_column('status_penghapusan', '$1', 'status_penghapusan(status_diterima)');
    return
    $this->datatables->generate();
  }

  // get data by id
  function get_by_id($id){
    $this->db->where($this->id, $id);
    return $this->db->get($this->table)->row();
  }

  function get_jenis_penghapusan($id_penghapusan){
    $this->db->select('group_concat(distinct kode_jenis) as jenis');
    $this->db->where($this->id,$id_penghapusan);
    return $this->db->get($this->tbl_penghapusan_barang)->row();
  }

  function update_pengajuan($data, $id_penghapusan_barang){
    $this->db->update_batch($this->tbl_penghapusan_barang,$data, $id_penghapusan_barang);
  }

  function get_by_list_id($list_id_penghapusan_barang){
    // $this->db->where('status_diterima',2);
    $this->db->where_in('id_penghapusan_barang',$list_id_penghapusan_barang);
    return $this->db->get($this->tbl_penghapusan_barang)->result();
  }

  function update_penghapusan($id_penghapusan, $data){
    $this->db->where('id_penghapusan',$id_penghapusan);
    $this->db->update('tbl_penghapusan', $data);
  }
}
