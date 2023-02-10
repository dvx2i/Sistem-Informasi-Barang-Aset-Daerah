<?php

if (!defined('BASEPATH')) exit('No direct script access allowed');

class Penghapusan_barang_model extends CI_Model
{

  public $table = 'tbl_penghapusan';
  public $id = 'id_penghapusan';
  public $order = 'DESC';
  public $tbl_kode_barang = 'tbl_kode_barang';

  public $tbl_penghapusan_barang = 'tbl_penghapusan_barang';
  public $id_penghapusan_barang = 'id_penghapusan_barang';

  public $tbl_penghapusan_lampiran = 'tbl_penghapusan_lampiran';
  public $id_penghapusan_lampiran = 'id_penghapusan_lampiran';

  function __construct()
  {
    parent::__construct();
  }

  // datatables
  function json()
  {
    $this->load->helper('penghapusan');
    $this->db->query('SET lc_time_names = "id_ID";');

    $this->datatables->select('id_penghapusan_lampiran, tanggal_lampiran, keterangan');
    $this->datatables->from('tbl_penghapusan_lampiran');
    $this->datatables->where('status_validasi', '1');
    $this->datatables->edit_column('tanggal_lampiran', '$1', 'tgl_indo(tanggal_lampiran)');
    $this->datatables->add_column("action", '$1', "penghapusan_barang_action(id_penghapusan_lampiran)");
    return
      $this->datatables->generate();
    // die($this->datatables->last_query());
  }

  function json_detail($id_pengahapusan_lampiran)
  {
    $this->load->helper('penghapusan');
    $this->db->query('SET lc_time_names = "id_ID";');
    // $this->datatables->select('B.kode_jenis,B.kib_name, B.nomor_register, C.kode_barang, C.nama_barang');
    // $this->datatables->from('tbl_penghapusan_barang A');
    // $this->datatables->join('view_kib_histori B','A.kode_jenis=B.kode_jenis and A.id_kib=B.id_kib','left');
    // $this->datatables->join('tbl_kode_barang C','B.id_kode_barang=C.id_kode_barang','left');
    // $this->datatables->where('nomor_sk',$nomor_sk);
    $this->datatables->select('C.kode_jenis,C.kib_name, C.nomor_register, D.kode_barang, CASE WHEN C.id_inventaris <> "" THEN CONCAT(D.nama_barang," / ",C.deskripsi) ELSE D.nama_barang END nama_barang,id_inventaris');
    $this->datatables->from('tbl_penghapusan_lampiran_detail A');
    $this->datatables->join('tbl_penghapusan_barang B', 'A.id_penghapusan_barang=B.id_penghapusan_barang', 'left');
    $this->datatables->join('view_penghapusan C', 'B.kode_jenis=C.kode_jenis and B.id_kib=C.id_kib', 'left');
    $this->datatables->join('tbl_kode_barang D', 'C.id_kode_barang=D.id_kode_barang', 'left');
    $this->datatables->where('id_penghapusan_lampiran', $id_pengahapusan_lampiran);
    return
      $this->datatables->generate();
  }

  // get data by id
  function get_by_id($id)
  {
    $this->db->where($this->id, $id);
    return $this->db->get($this->table)->row();
  }
  
  function get_penghapusan($id)
  {
    $this->db->select('C.id_kode_lokasi,C.tanggal_pengajuan');
    $this->db->from('tbl_penghapusan_lampiran_detail A');
    $this->db->join('tbl_penghapusan_barang B', 'A.id_penghapusan_barang=B.id_penghapusan_barang', 'left');
    $this->db->join('tbl_penghapusan C', 'B.id_penghapusan=C.id_penghapusan', 'left');
    $this->db->where('id_penghapusan_lampiran', $id);
    return $this->db->get()->row();
  }
  
  function get_penghapusan_by_lampiran($id)
  {
    $this->db->select('id_penghapusan');
    $this->db->from('tbl_penghapusan_lampiran_detail A');
    $this->db->join('tbl_penghapusan_barang B', 'A.id_penghapusan_barang=B.id_penghapusan_barang');
    $this->db->where('id_penghapusan_lampiran', $id);
    $this->db->group_by('id_penghapusan');
    return $this->db->get()->result();
  }
  
  // get data by id
  function get_locked($bulan,$tahun,$id_kode_lokasi)
  {
    $this->db->where('bulan', $bulan);
    $this->db->where('tahun', $tahun);
    $this->db->where('(locked_admin = ', '1', FALSE);
    $this->db->or_where('locked_skpd = 1)', NULL, FALSE);
    $this->db->where('(id_kode_lokasi = "', $id_kode_lokasi.'")', FALSE);
    return $this->db->get('tbl_stock_opname')->num_rows();
    // echo $this->db->last_query(); die;
  }

  function get_jenis_penghapusan($id_penghapusan)
  {
    $this->db->select('group_concat(distinct kode_jenis) as jenis');
    $this->db->where($this->id, $id_penghapusan);
    return $this->db->get($this->tbl_penghapusan_barang)->row();
  }

  function update_pengajuan($data, $id_penghapusan_barang)
  {
    $this->db->update_batch($this->tbl_penghapusan_barang, $data, $id_penghapusan_barang);
  }

  function proc_penghapusan($id_penghapusan_lampiran)
  {
    $query = null;
    $query = $this->db->query("call proc_penghapusan('" . $id_penghapusan_lampiran . "')");
    $res      = $query->result();

    //add this two line
    $query->next_result();
    $query->free_result();
    //end of new code

    return $res;
  }
  
  // delete data
  function delete_lampiran($id){
    $this->db->where('id_penghapusan_lampiran', $id);
    $this->db->delete($this->tbl_penghapusan_lampiran);
  }

  function update_penghapusan($data_update, $where)
  {
    $this->db->where_in('id_penghapusan_barang', $where);
    // $this->db->where('id_penghapusan_barang','41');
    $this->db->update($this->tbl_penghapusan_barang, $data_update);
  }
  
  function update_penghapusan_status($id_penghapusan, $data){
    $this->db->where('id_penghapusan',$id_penghapusan);
    $this->db->update('tbl_penghapusan', $data);
  }

  function update_penghapusan_lampiran($id_penghapusan_lampiran, $data_update)
  {
    $this->db->where($this->id_penghapusan_lampiran, $id_penghapusan_lampiran);
    $this->db->update($this->tbl_penghapusan_lampiran, $data_update);
  }
}
