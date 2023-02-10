<?php

if (!defined('BASEPATH')) exit('No direct script access allowed');

class Validasi_model extends CI_Model
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
    $session = $this->session->userdata('session');
    $lokasi = explode('.', $session->kode_lokasi);
    $pengguna = $lokasi[3];
    $kuasa_pengguna =  $lokasi[4];
    $this->db->query('SET lc_time_names = "id_ID";');

    $this->datatables->select('A.id_kapitalisasi, A.jenis, A.status,DATE_FORMAT(tanggal_pengajuan, "%d %M %Y") as tanggal_pengajuan, nilai_kapitalisasi, penambahan_umur_ekonomis, concat_ws("-",B.kode_barang, B.nama_barang) as barang,nilai_awal,C.instansi');
    $this->datatables->from($this->table . ' as A');
    $this->datatables->join($this->tbl_kode_barang . ' as B', 'A.id_kode_barang=B.id_kode_barang', 'left');
    $this->datatables->join('tbl_kode_lokasi C', 'A.id_kode_lokasi=C.id_kode_lokasi', 'left');
    // $this->datatables->where('A.id_kode_lokasi',$this->session->userdata('session')->id_kode_lokasi);
    $this->datatables->where('A.status', '1');
    $this->datatables->where('A.status_data', 'kapitalisasi');
    if ($session->id_role != 1){
      $this->datatables->where('C.pengguna', $pengguna);
      if ($kuasa_pengguna != '0001'){
        $this->datatables->where('C.kuasa_pengguna', $kuasa_pengguna);
      }
    }
    $this->datatables->edit_column('jenis', '$1', 'jenis(jenis)');
    $this->datatables->edit_column('nilai_awal', '$1', 'format_float(nilai_awal)');
    $this->datatables->edit_column('nilai_kapitalisasi', '$1', 'format_float(nilai_kapitalisasi)');
    $this->datatables->add_column('action', '$1', 'validasi_action(status,id_kapitalisasi)');

    return
      $this->datatables->generate();
    // die($this->db->last_query());
  }

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

  function update($id_kapitalisasi, $data)
  {
    $this->db->where('id_kapitalisasi', $id_kapitalisasi);
    $this->db->update('tbl_kapitalisasi', $data);
  }

  // delete data
  function delete($id)
  {
    $this->db->where($this->id, $id);
    $this->db->delete($this->table);
  }
  
  function set_histori_barang($status_histori, $id_kode_lokasi, $kode_jenis, $id_kib, $tanggal_validasi,$id_sumber_dana_kapitalisasi, $id_rekening_kapitalisasi,$id_kapitalisasi)
  {
    if($status_histori != 'kapitalisasi'){
      $id_sumber_dana_kapitalisasi = '0';
      $id_rekening_kapitalisasi    = '0'; 
      // yg dicatat hanya kapitalisasi, karena memakai anggaran dana
    }

    if($kode_jenis != '5.03'){
      $query = $this->db->query("call proc_histori_barang('" . $status_histori . "'," . $id_kode_lokasi . ",'" . $kode_jenis . "','" . $id_kib ."','" . $tanggal_validasi . "','validasi_kapitalisasi','".$id_sumber_dana_kapitalisasi."','".$id_rekening_kapitalisasi."','".$id_kapitalisasi."');");
    }else{
      $query = $this->db->query("call proc_histori_barang_atb('" . $status_histori . "'," . $id_kode_lokasi . ",'" . $kode_jenis . "','" . $id_kib ."','" . $tanggal_validasi . "','validasi_kapitalisasi','".$id_sumber_dana_kapitalisasi."','".$id_rekening_kapitalisasi."','".$id_kapitalisasi."');");
    }
    // $query->next_result();
    // $query->free_result();
    // die($this->db->last_query());
  }
}
