<?php

if (!defined('BASEPATH'))
  exit('No direct script access allowed');

class Stock_opname_kib_model extends CI_Model
{

  public $table = 'tbl_backup_kib';
  public $id = 'id';
  public $order = 'DESC';
  public $tbl_barang = 'tbl_kode_barang';
  public $tbl_kode_lokasi = 'tbl_kode_lokasi';
  public $tbl_master_jenis = 'tbl_master_jenis';

  function __construct()
  {
    parent::__construct();
  }

  // datatables
  function json()
  { //die(explode('.',$this->session->userdata('session')->kode_lokasi)[4]);
    $this->load->helper('my_datatable');
    $this->datatables->select('A.id,A.semester,A.tahun,C.instansi');
    $this->datatables->from('tbl_backup_kib A');
    //$this->datatables->join($this->tbl_barang . ' B', 'A.id_kode_barang = B.id_kode_barang','LEFT');
    $this->datatables->join($this->tbl_kode_lokasi . ' C', 'A.id_pengguna = C.pengguna','LEFT');
    
    $this->datatables->where('C.pengguna', $this->input->post('id_pengguna')); // where id kuasa pengguna
     
    $this->db->order_by('tahun', 'DESC');
    $this->db->order_by('semester', 'DESC');
    $this->datatables->edit_column('id', '$1', 'encrypt_id(id)');
    $this->datatables->add_column('action', '$1', 'get_action_stock_opname_kib(' . $this->id . ')');
    return $this->datatables->generate();
     //$this->datatables->generate();
     //die($this->db->last_query());
  }

  function backup($data)
  {
    $queri = null;
    $res = null;
    // print_r($data['id_kode_lokasi']); die;
    // die("call proc_backup_kib('" . $data['id_pengguna'] . "','" . $data['semester'] . "','" . $data['tahun'] . "')");
    $query    = $this->db->query("call proc_backup_kib('" . $data['id_pengguna'] . "','" . $data['semester'] . "','" . $data['tahun'] . "')");
    // $res      = $query->result_array();
   


    //add this two line
    // $query->next_result();
    // $query->free_result();
    //end of new code

    // die(json_encode($res));
    // return $res;
    // return
    // $this->db->get()->result_array();
    // die($this->db->last_query());
  }
  
  // get data by id
  function get_locked($bulan,$tahun,$id_kode_lokasi)
  {
    $session = $this->session->userdata('session');
    $this->db->where('bulan >', $bulan);
    $this->db->where('tahun >=', $tahun);
    $this->db->where('locked_admin >', 0, FALSE);
    // $this->db->or_where('locked_skpd = 1)', NULL, FALSE);
    $this->db->where('id_kode_lokasi', $id_kode_lokasi);
    return $this->db->get('tbl_stock_opname')->num_rows();
    // echo $this->db->last_query(); die;
  }
  
  // get data by id
  function get_unlocked($bulan,$tahun,$id_kode_lokasi)
  {
    $session = $this->session->userdata('session');
    $this->db->where('bulan <', $bulan);
    $this->db->where('tahun <=', $tahun);
    // if ($session->id_role == '1') {
    // $this->db->where('locked_admin <', 2, false);
    // }else {
      $this->db->where('locked_admin', '0');
      // }
    $this->db->where('id_kode_lokasi', $id_kode_lokasi);
   return $this->db->get('tbl_stock_opname')->num_rows();
    // echo $this->db->last_query(); die;
  }

  
  // get data by id
  function get_id_lokasi_by_pengguna($pengguna)
  {
    $this->db->where('pengguna', $pengguna);
    $this->db->where('kuasa_pengguna', '*');
    return $this->db->get('tbl_kode_lokasi')->row();
  }
  // get data by id
  function get_lokasi_by_pengguna($pengguna)
  {
    $this->db->where('pengguna', $pengguna);
    $this->db->where('kuasa_pengguna <>', '*');
    return $this->db->get('tbl_kode_lokasi')->result_array();
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

  function get_last_data($data)
  {
    $this->db->select('bulan,tahun');
    $this->db->from('tbl_stock_opname');
    $this->db->where('id_pemilik', $data['id_pemilik']);
    $this->db->where('id_pengguna', $data['id_pengguna']);
    $this->db->where('id_kode_lokasi', $data['id_kode_lokasi']);
    $this->db->order_by('created_at', 'DESC');
    $this->db->limit('1');
    $data = $this->db->get()->result_array();
    //die($this->db->last_query());
    return $data;
  }

  function get_by_lokasi($id,$semester,$tahun)
  {
    $this->db->select('*');
    $this->db->from('tbl_backup_kib');
    $this->db->where('id_pengguna', $id);
    $this->db->where('semester', $semester);
    $this->db->where('tahun', $tahun);
    $this->db->order_by('created_at', 'DESC');
    $this->db->limit('1');
    $data = $this->db->get();
    //die($this->db->last_query());
    return $data;
  }

  // get total rows
  /*
  function total_rows($q = NULL)
  {
    $this->db->like('id_kib_b', $q);
    $this->db->or_like('kode_barang', $q);
    $this->db->or_like('nama_barang', $q);
    $this->db->or_like('nomor_register', $q);
    $this->db->or_like('merk_type', $q);
    $this->db->or_like('ukuran_cc', $q);
    $this->db->or_like('bahan', $q);
    $this->db->or_like('tahun_pembelian', $q);
    $this->db->or_like('nomor_pabrik', $q);
    $this->db->or_like('nomor_rangka', $q);
    $this->db->or_like('nomor_mesin', $q);
    $this->db->or_like('nomor_polisi', $q);
    $this->db->or_like('nomor_bpkb', $q);
    $this->db->or_like('asal_usul', $q);
    $this->db->or_like('harga', $q);
    $this->db->or_like('keterangan', $q);
    $this->db->or_like('kode_lokasi', $q);
    $this->db->or_like('created_at', $q);
    $this->db->or_like('updated_at', $q);
    // $this->db->where(" (id_kode_barang_aset_lainya is NULL and status_barang <> 'aset_lainya') ", NULL, FALSE);
    $this->db->from($this->table);
    return $this->db->count_all_results();
  }

  // get data with limit and search
  function get_limit_data($limit, $start = 0, $q = NULL)
  {
    $this->db->order_by($this->id, $this->order);
    $this->db->like('id_kib_b', $q);
    $this->db->or_like('kode_barang', $q);
    $this->db->or_like('nama_barang', $q);
    $this->db->or_like('nomor_register', $q);
    $this->db->or_like('merk_type', $q);
    $this->db->or_like('ukuran_cc', $q);
    $this->db->or_like('bahan', $q);
    $this->db->or_like('tahun_pembelian', $q);
    $this->db->or_like('nomor_pabrik', $q);
    $this->db->or_like('nomor_rangka', $q);
    $this->db->or_like('nomor_mesin', $q);
    $this->db->or_like('nomor_polisi', $q);
    $this->db->or_like('nomor_bpkb', $q);
    $this->db->or_like('asal_usul', $q);
    $this->db->or_like('harga', $q);
    $this->db->or_like('keterangan', $q);
    $this->db->or_like('kode_lokasi', $q);
    $this->db->or_like('created_at', $q);
    $this->db->or_like('updated_at', $q);
    $this->db->limit($limit, $start);
    return $this->db->get($this->table)->result();
  }
*/
function total_rows($q = NULL)
  {
    $this->db->like('id_kib_b', $q);
    $this->db->or_like('kode_barang', $q);
    $this->db->or_like('nama_barang', $q);
    $this->db->or_like('nomor_register', $q);
     $this->db->or_like('merk_type', $q);
    $this->db->or_like('ukuran_cc', $q);
    $this->db->or_like('bahan', $q);
    $this->db->or_like('tahun_pembelian', $q);
    $this->db->or_like('nomor_polisi', $q);
   
    $this->db->or_like('asal_usul', $q);
    $this->db->or_like('harga', $q);
    $this->db->or_like('keterangan', $q);
    $this->db->or_like('kode_lokasi', $q);
    // $this->db->where(" (id_kode_barang_aset_lainya is NULL and status_barang <> 'aset_lainya') ", NULL, FALSE);
    $this->db->from($this->table);
    return $this->db->count_all_results();
  }

  // get data with limit and search
  function get_limit_data($limit, $start = 0, $q = NULL)
  {
    $this->db->order_by($this->id, $this->order);
    $this->db->like('id_kib_b', $q);
    $this->db->or_like('kode_barang', $q);
    $this->db->or_like('nama_barang', $q);
    $this->db->or_like('nomor_register', $q);
     $this->db->or_like('merk_type', $q);
    $this->db->or_like('ukuran_cc', $q);
    $this->db->or_like('bahan', $q);
    $this->db->or_like('tahun_pembelian', $q);
    $this->db->or_like('nomor_polisi', $q);
    
    $this->db->or_like('asal_usul', $q);
    $this->db->or_like('harga', $q);
    $this->db->or_like('keterangan', $q);
    $this->db->or_like('kode_lokasi', $q);
   
    $this->db->limit($limit, $start);
    return $this->db->get($this->table)->result();
  }
  // insert data
  function insert($data)
  {
    // die(json_encode($data));
    $this->db->insert($this->table, $data);
    // die($this->db->last_query());
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

  // delete data
  function delete_backup($id)
  {
    // print_r($data['id_kode_lokasi']); die;
    // die("call proc_rekap_mutasi_objek(" . $data['session']->id_user . "," . $data['id_pemilik'] . "," . $data['id_kode_lokasi'] . ",'" . $data['start_bulan'] . "','" . $data['start_tahun'] . "','" . $data['last_bulan'] . "','" . $data['last_tahun'] . "',  '" . $data['intra_ekstra'] . "')");
    $query    = $this->db->query("call proc_delete_backup_kib(" . $id . ")");
    // $res      = $query->result_array();
   


    //add this two line
    // $query->next_result();
    // $query->free_result();
    //end of new code

    // die(json_encode($res));
    return $res;
    // return
    // $this->db->get()->result_array();
    // die($this->db->last_query());
  }
}

/* End of file Kib_b_model.php */
/* Location: ./application/models/Kib_b_model.php */
/* Please DO NOT modify this information : */
/* Generated by Harviacode Codeigniter CRUD Generator 2018-08-07 08:13:19 */
/* http://harviacode.com */
