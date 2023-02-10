<?php

if (!defined('BASEPATH')) exit('No direct script access allowed');

class Pengajuan_model extends CI_Model
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
  function json_list()
  {
    $this->load->helper('kapitalisasi');
    $this->db->query('SET lc_time_names = "id_ID";');

    $this->datatables->select('A.id_kapitalisasi,A.jenis, A.status,DATE_FORMAT(tanggal_pengajuan, "%d %M %Y") as tanggal_pengajuan, nilai_kapitalisasi, penambahan_umur_ekonomis, concat_ws("-",B.kode_barang, B.nama_barang) as barang,C.instansi');
    $this->datatables->from($this->table . ' as A');
    $this->datatables->join($this->tbl_kode_barang . ' as B', 'A.id_kode_barang=B.id_kode_barang', 'left');
    $this->datatables->join('tbl_kode_lokasi C', 'A.id_kode_lokasi=C.id_kode_lokasi', 'left');
    $this->datatables->where('A.id_kode_lokasi', $this->session->userdata('session')->id_kode_lokasi);
    
    $session = $this->session->userdata('session');
    $lokasi = explode('.', $session->kode_lokasi);
    $pengguna = $lokasi[3];
    $kuasa_pengguna =  $lokasi[4];

    if ($session->id_role != 1){
        $this->datatables->where('C.kuasa_pengguna', $kuasa_pengguna);
    }
    $this->datatables->where('A.status IN', '(1,2)', FALSE);
    $this->datatables->where('A.status_data', 'kapitalisasi');
    $this->datatables->edit_column('jenis', '$1', 'jenis(jenis)');
    $this->datatables->edit_column('nilai_kapitalisasi', '$1', 'format_float(nilai_kapitalisasi)');
    $this->datatables->add_column('action', '$1', 'pengajuan_action(status,id_kapitalisasi)');
    // $this->datatables->add_column('keterangan', '$1', 'status_proses(status_proses)');
    return
      $this->datatables->generate();
    // die($this->db->last_query());
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

  // insert data
  function insert($data)
  {
    $this->db->insert($this->table, $data);
    return $this->db->insert_id();
  }

  // delete data
  function delete($id)
  {
    $this->db->where($this->id, $id);
    $this->db->delete($this->table);
  }

  function get_harga_awal($kode_jenis, $id_kib)
  {
    $master_jenis = $this->db->get('tbl_master_jenis')->result_array();

    foreach($master_jenis as $key){
      if($key['kode_jenis'] == $kode_jenis){
        $tbl    = $key['nama_tabel'];
        $id_tbl = $key['id_tabel'];
      }
    }

    $this->db->where($id_tbl, $id_kib);
    $harga = $this->db->get($tbl)->result_array();
    if($kode_jenis == '06'){
      return $harga[0]['nilai_kontrak'];
    } else {
      return $harga[0]['harga'];
    }
  }
}
