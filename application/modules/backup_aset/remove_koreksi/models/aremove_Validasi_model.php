<?php

if (!defined('BASEPATH')) exit('No direct script access allowed');

class Validasi_model extends CI_Model{

  public $table = 'tbl_kapitalisasi';
  public $id = 'id_kapitalisasi';
  public $order = 'DESC';
  public $tbl_kode_barang = 'tbl_kode_barang';


  function __construct(){
    parent::__construct();
  }

  // datatables
  function json() {
    $this->load->helper('kapitalisasi');
    $this->db->query('SET lc_time_names = "id_ID";');

    $this->datatables->select('A.id_kapitalisasi, A.status,DATE_FORMAT(tanggal_pengajuan, "%d %M %Y") as tanggal_pengajuan, format(nilai_kapitalisasi,0) as nilai_kapitalisasi, penambahan_umur_ekonomis, concat_ws("-",B.kode_barang, B.nama_barang) as barang');
    $this->datatables->from($this->table.' as A');
    $this->datatables->join($this->tbl_kode_barang.' as B','A.id_kode_barang=B.id_kode_barang','left');
    $this->datatables->join('tbl_kode_lokasi C','A.id_kode_lokasi=C.id_kode_lokasi','left');
    // $this->datatables->where('A.id_kode_lokasi',$this->session->userdata('session')->id_kode_lokasi);
    $this->datatables->where('A.status','1');
    $this->datatables->add_column('action', '$1', 'validasi_action(status,id_kapitalisasi)');
    // $this->datatables->add_column('action', '$1', 'pengajuan_action(status,id_kapitalisasi)');
    // $this->datatables->add_column('keterangan', '$1', 'status_proses(status_proses)');
    return
    $this->datatables->generate();
    // die($this->db->last_query());
  }

  // get data by id
  function get_by_id($id){
    $this->db->where($this->id, $id);
    return $this->db->get($this->table)->row();
  }

  function update($id_kapitalisasi, $data){
    $this->db->where('id_kapitalisasi',$id_kapitalisasi);
    $this->db->update('tbl_kapitalisasi', $data);
  }

  // delete data
  function delete($id){
    $this->db->where($this->id, $id);
    $this->db->delete($this->table);
  }

}
