<?php

if (!defined('BASEPATH')) exit('No direct script access allowed');

class List_model extends CI_Model
{

  public $table = 'tbl_kapitalisasi';
  public $id = 'id_kapitalisasi';
  public $order = 'DESC';
  public $tbl_kode_barang = 'tbl_kode_barang';


  function __construct()
  {
    parent::__construct();
  }

  // datatables
  function json()
  {
    $this->load->helper('kapitalisasi');
    $this->db->query('SET lc_time_names = "id_ID";');

    $this->datatables->select('A.id_kib,A.jenis,D.nama_kib,A.id_kapitalisasi, A.status,DATE_FORMAT(tanggal_pengajuan, "%d %M %Y") as tanggal_pengajuan,DATE_FORMAT(tanggal_kapitalisasi, "%d %M %Y") as tanggal_kapitalisasi, nilai_kapitalisasi, penambahan_umur_ekonomis, concat_ws("-",B.kode_barang, B.nama_barang) as barang, CASE WHEN jenis = "koreksi_tambah" THEN nilai_awal+nilai_kapitalisasi WHEN jenis = "koreksi_kurang" THEN nilai_awal-nilai_kapitalisasi ELSE nilai_awal+nilai_kapitalisasi END AS nilai_akhir,nilai_awal,C.instansi', false);
    $this->datatables->from($this->table . ' as A');
    $this->datatables->join($this->tbl_kode_barang . ' as B', 'A.id_kode_barang=B.id_kode_barang', 'left');
    $this->datatables->join('tbl_kode_lokasi C', 'A.id_kode_lokasi=C.id_kode_lokasi', 'left');
    $this->datatables->join('tbl_master_jenis D', 'A.kode_jenis=D.kode_jenis', 'left');
    $this->datatables->where('A.status', '2');
    $this->datatables->where('A.status_data', 'kapitalisasi');

    $session = $this->session->userdata('session');
    $lokasi = explode('.', $session->kode_lokasi);
    $pengguna = $lokasi[3];
    $kuasa_pengguna =  $lokasi[4];

    if ($session->id_role != 1){
      $this->datatables->where('C.pengguna', $pengguna);
      if ($kuasa_pengguna != '0001'){
        $this->datatables->where('C.kuasa_pengguna', $kuasa_pengguna);
      }
    }
    
    $this->datatables->edit_column('nilai_awal', '$1', 'format_float(nilai_awal)');
    $this->datatables->edit_column('nilai_akhir', '$1', 'format_float(nilai_akhir)');
    $this->datatables->edit_column('nilai_kapitalisasi', '$1', 'format_float(nilai_kapitalisasi)');
    $this->datatables->add_column('action', '<a href="' . base_url("kapitalisasi/list_data/detail/$1"). '"> <button class="btn btn-info btn-sm" title="Detail"> <span class="fa fa-search"></span></button></a>&nbsp;&nbsp;<a href="' . base_url("kapitalisasi/list_data/update/$1"). '"> <button class="btn btn-warning btn-sm" title="Update"> <span class="fa fa-edit"></span></button></a>&nbsp;&nbsp;<a href="' . base_url("kapitalisasi/list_data/delete/$1"). '" onclick="javasciprt: return confirm(\'Yakin Ingin Dihapus ?\')"> <button class="btn btn-danger btn-sm" title="Hapus"> <span class="fa fa-trash"></span></button></a>', 'encrypt_url(id_kapitalisasi)');
    
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
