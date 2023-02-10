<?php

if (!defined('BASEPATH')) exit('No direct script access allowed');

class Validasi_reklas_kode_model extends CI_Model
{

  public $table = 'tbl_reklas_kode';
  public $id = 'id_reklas_kode';
  public $order = 'DESC';
  public $tbl_kode_barang = 'tbl_kode_barang';

  // public $tbl_mutasi_barang = 'tbl_mutasi_barang';
  // public $id_mutasi_barang = 'id_mutasi_barang';


  function __construct()
  {
    parent::__construct();
  }

  function json()
  {
    // die("ok");
    $this->load->helper('my_datatable');
    $session = $this->session->userdata('session');
    $pengguna = explode('.', $session->kode_lokasi)[3];
    $kuasa_pengguna =  explode('.', $session->kode_lokasi)[4];
    $this->db->query('SET lc_time_names = "id_ID";');
    $this->datatables->select("
      A.id_reklas_kode,DATE_FORMAT(tanggal, '%d %M %Y') as tanggal,case when A.status_validasi='1' then 'Menunggu Validasi' else 'Tervalidasi' end status_validasi_desc, 
      A.id_kode_barang,A.status_validasi
      ,concat_ws('-',D.kode_lokasi, D.instansi) as lokasi,
      B.kode_barang
      , CONCAT_WS(' - ',B.kode_barang, CASE WHEN ((B.kode_sub_sub_rincian_objek='000') or (B.kode_sub_sub_rincian_objek='0')) THEN B.nama_barang ELSE B.nama_barang_simbada END) nama_barang
      , CONCAT_WS(' - ',C.kode_barang, CASE WHEN ((C.kode_sub_sub_rincian_objek='000') or (C.kode_sub_sub_rincian_objek='0')) THEN C.nama_barang ELSE C.nama_barang_simbada END) nama_barang_tujuan
    ");
    $this->datatables->from('tbl_reklas_kode A');
    $this->datatables->where('A.status_validasi', '1');
    $this->datatables->join('tbl_kode_barang B', 'A.id_kode_barang=B.id_kode_barang', 'left');
    $this->datatables->join('tbl_kode_barang C', 'A.id_kode_barang_tujuan=C.id_kode_barang', 'left');
    $this->datatables->join('tbl_kode_lokasi D', 'A.id_kode_lokasi=D.id_kode_lokasi', 'left');

    if ($session->id_role != 1){
      $this->datatables->where('D.pengguna', $pengguna);
      if ($kuasa_pengguna != '0001'){
        $this->datatables->where('D.kuasa_pengguna', $kuasa_pengguna);
      }
    }
    // $this->datatables->add_column('barang', '$1', 'barang(id_mutasi)');
    // $this->datatables->add_column('action', '$1', 'pengajuan_action(status_validasi,id_mutasi)');
    $this->datatables->add_column('action', '$1', 'get_action_reklas_kode_validasi(status_validasi,id_reklas_kode)');
    // $this->datatables->add_column('keterangan', '$1', 'pengajuan_keterangan(status_validasi,id_mutasi)');
    // $this->datatables->add_column('keterangan', '$1', 'status_proses(status_proses)');
    return
      $this->datatables->generate();
    // die($this->db->last_query());
  }

  /*
  function json_detail($id_mutasi)
  {
    $this->load->helper('my_datatable');
    $this->db->query('SET lc_time_names = "id_ID";');
    $this->datatables->select('A.id_mutasi_barang, A.status_diterima,A.id_kib,B.kib_name, B.nomor_register, C.kode_barang, C.nama_barang');
    $this->datatables->from('tbl_mutasi_barang A');
    // $this->datatables->join('view_kib B', 'A.kode_jenis=B.kode_jenis and A.id_kib=B.id_kib', 'left');
    $this->datatables->join('(
      SELECT kode_jenis, id_kib, id_pemilik, kib_name, nomor_register, id_kode_barang, merk_type, id_kode_lokasi, kode_lokasi, asal_usul, tanggal_pembelian, tanggal_perolehan, validasi, kib_f, harga, status_barang
      FROM `view_kib`
      UNION ALL 
      SELECT "5.03" as kode_jenis, id_kib_atb, id_pemilik, "KIB ATB" as kib_name, nomor_register, id_kode_barang, ""  as merk_type , id_kode_lokasi, kode_lokasi, asal_usul, "" tanggal_pembelian, tanggal_perolehan, validasi, "" kib_f, harga, status_barang
      FROM tbl_kib_atb 
    ) B', 'A.kode_jenis=B.kode_jenis and A.id_kib=B.id_kib', 'left');
    $this->datatables->join('tbl_kode_barang C', 'B.id_kode_barang=C.id_kode_barang', 'left');
    $this->datatables->where('A.id_mutasi', $id_mutasi);
    $this->datatables->edit_column('status_diterima', '$1', 'status_diterima(status_diterima)');

    return
      $this->datatables->generate();
  }
*/
  
  // get data by id
  function get_by_id($id)
  {
    $this->db->where($this->id, $id);
    return $this->db->get($this->table)->row();
  }
  
  // get data by id
  function get_locked($bulan,$tahun,$id_kode_lokasi)
  {
    $this->db->where('bulan', $bulan);
    $this->db->where('tahun', $tahun);
    $this->db->where('locked_admin >', 0, FALSE);
    $this->db->where('(id_kode_lokasi = "',$id_kode_lokasi.'")', FALSE);
    return $this->db->get('tbl_stock_opname')->num_rows();
    // echo $this->db->last_query(); die;
  }

  /*
  function get_jenis_mutasi($id_mutasi)
  {
    $this->db->select('group_concat(distinct kode_jenis) as jenis');
    $this->db->where($this->id, $id_mutasi);
    return $this->db->get($this->tbl_mutasi_barang)->row();
  }
*/
  /*
  function update_pengajuan($data, $id_mutasi_barang)
  {
    $this->db->update_batch($this->tbl_mutasi_barang, $data, $id_mutasi_barang);
  }
*/
  /*
  function update_url_bast($id_mutasi, $data)
  {
    $this->db->where('id_mutasi', $id_mutasi);
    $this->db->update('tbl_mutasi', $data);
  }
*/
  /*
  function update_pengajuan_register($id_mutasi, $data)
  {
    $this->db->where('id_mutasi', $id_mutasi);
    $this->db->update('tbl_mutasi', $data);
  }
*/
  /*
dalam 1 kib di aset tetap : simpan kib lama di histori,ganti kode barang , ganti register baru, 
dalam 1 kib di aset lainya : masukan kode barang aset lainya, ubah status reklas, simpan kib lama di lama di histori
*/
  function reklas_action($id_reklas_kode, $tanggal_validasi)
  {
    // die("call proc_reklas_kode(" . $id_reklas_kode . ");");
    $query = $this->db->query("call proc_reklas_kode(" . $id_reklas_kode . ",'".$tanggal_validasi."');");
    $res      = $query->result();

    //add this two line
    $query->next_result();
    $query->free_result();
    //end of new code

    return $res;
  }
}
