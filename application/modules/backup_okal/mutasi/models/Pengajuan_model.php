<?php

if (!defined('BASEPATH')) exit('No direct script access allowed');

class Pengajuan_model extends CI_Model
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
    // die("ok");
    $this->load->helper('my_datatable');
    $this->db->query('SET lc_time_names = "id_ID";');
    $this->datatables->select('A.id_mutasi,DATE_FORMAT(tanggal, "%d %M %Y") as tanggal,id_kode_lokasi_lama,id_kode_lokasi_baru,A.status_validasi,status_proses as keterangan, A.status_proses, B.instansi as pengguna_lama, C.instansi as pengguna_baru');
    $this->datatables->from('tbl_mutasi A');
    $this->datatables->join('tbl_kode_lokasi B', 'A.id_kode_lokasi_lama=B.id_kode_lokasi', 'left');
    $this->datatables->join('tbl_kode_lokasi C', 'A.id_kode_lokasi_BARU=C.id_kode_lokasi', 'left');
    if($this->session->userdata('session')->id_role != '1'){  
    $this->datatables->where('id_kode_lokasi_lama', $this->session->userdata('session')->id_kode_lokasi);
    }
    // $this->datatables->add_column('barang', '$1', 'barang(id_mutasi)');
    $this->datatables->add_column('action', '$1', 'pengajuan_action(status_validasi,id_mutasi)');
    // $this->datatables->add_column('keterangan', '$1', 'pengajuan_keterangan(status_validasi,id_mutasi)');
    $this->datatables->edit_column('keterangan', '$1', 'status_proses(status_proses)');
    return
      $this->datatables->generate();
    // die($this->db->last_query());
  }

  // datatables
  function json_detail($id_mutasi)
  {
    $this->load->helper('my_datatable');
    $this->db->query('SET lc_time_names = "id_ID";');
    $this->datatables->select('A.id_mutasi_barang, A.status_diterima,A.id_kib,B.kib_name, B.nomor_register,B.id_inventaris, C.kode_barang, C.nama_barang');
    $this->datatables->from('tbl_mutasi_barang A');
    $this->datatables->join('(
      SELECT kode_jenis, id_kib, id_pemilik, kib_name, nomor_register, id_kode_barang, merk_type, id_kode_lokasi, kode_lokasi, asal_usul, tanggal_pembelian, tanggal_perolehan, validasi, kib_f, harga, status_barang,id_inventaris
      FROM `view_mutasi`
      
    ) B', 'A.kode_jenis=B.kode_jenis and A.id_kib=B.id_kib', 'left');
    $this->datatables->join('tbl_kode_barang C', 'B.id_kode_barang=C.id_kode_barang', 'left');
    $this->datatables->where('A.id_mutasi', $id_mutasi);
    $this->datatables->edit_column('status_diterima', '$1', 'status_diterima(status_diterima)');

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

  // get data by id
  function get_barang($id_mutasi_barang)
  {
    $this->db->select('group_concat(id_kode_barang) as id_kode_barang');
    $this->db->where($this->id, $id_mutasi_barang);
    $this->db->group_by($this->id);
    return $this->db->get($this->tbl_mutasi_barang)->row();
    // die($this->db->last_query());
  }

  // get total rows
  function total_rows($q = NULL)
  {
    $this->db->like('id_mutasi', $q);
    $this->db->or_like('kib', $q);
    $this->db->or_like('tanggal', $q);
    $this->db->or_like('kode_lokasi_pemberi', $q);
    $this->db->or_like('kode_lokasi_penerima', $q);
    $this->db->or_like('barang', $q);
    $this->db->or_like('nip_pembuat', $q);
    $this->db->or_like('created_at', $q);
    $this->db->or_like('updated_at', $q);
    $this->db->or_like('status', $q);
    $this->db->or_like('validasi', $q);
    $this->db->from($this->table);
    return $this->db->count_all_results();
  }

  // get data with limit and search
  function get_limit_data($limit, $start = 0, $q = NULL)
  {
    $this->db->order_by($this->id, $this->order);
    $this->db->like('id_mutasi', $q);
    $this->db->or_like('kib', $q);
    $this->db->or_like('tanggal', $q);
    $this->db->or_like('kode_lokasi_pemberi', $q);
    $this->db->or_like('kode_lokasi_penerima', $q);
    $this->db->or_like('barang', $q);
    $this->db->or_like('nip_pembuat', $q);
    $this->db->or_like('created_at', $q);
    $this->db->or_like('updated_at', $q);
    $this->db->or_like('status', $q);
    $this->db->or_like('validasi', $q);
    $this->db->limit($limit, $start);
    return $this->db->get($this->table)->result();
  }

  // insert data
  function insert($data)
  {
    $this->db->insert($this->table, $data);
    return $this->db->insert_id();
  }

  // insert data
  function insert_barang($data)
  {
    $this->db->insert_batch($this->tbl_mutasi_barang, $data);
  }

  function update_barang($id_mutasi, $data)
  {
    $this->db->where($this->id, $id_mutasi);
    $this->db->delete($this->tbl_mutasi_barang);

    $this->db->insert_batch($this->tbl_mutasi_barang, $data);
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

  // delete data
  function delete_mutasi_barang($id)
  {
    $this->db->where($this->id, $id);
    $this->db->delete($this->tbl_mutasi_barang);
  }

  function get_lokasi($id_kode_lokasi)
  {
    // $res = $this->db->get_where('tbl_kode_lokasi', array('kuasa_pengguna<>' => "*"));
    // $query = "
    //     SELECT A.* 
    //     FROM tbl_kode_lokasi A 
    //     LEFT JOIN view_pengguna B on A.pengguna=B.pengguna
    //     WHERE A.kuasa_pengguna <> '*' AND B.id_pengguna=$id_pengguna;
    // ";
    $query = "
        SELECT A.* 
        FROM tbl_kode_lokasi A 
        WHERE  A.id_kode_lokasi=$id_kode_lokasi;
    ";
    $res = $this->db->query($query);
    return $res->result_array();
  }


  public function get_kuasa_pengguna($id_pengguna = null)
  {
    return $this->db->get_where('view_kuasa_pengguna', array('id_pengguna' => $id_pengguna,))->result();
  }

  public function get_sub_kuasa_pengguna($id_pengguna = null, $id_kuasa_pengguna = null)
  {
    return
      $this->db->get_where('view_sub_kuasa_pengguna', array('id_pengguna' => $id_pengguna, 'id_kuasa_pengguna' => $id_kuasa_pengguna))->result();
    // die($this->db->last_query());
  }
}

/* End of file mutasi_model.php */
/* Location: ./application/models/mutasi_model.php */
/* Please DO NOT modify this information : */
/* Generated by Harviacode Codeigniter CRUD Generator 2018-08-24 05:56:49 */
/* http://harviacode.com */
