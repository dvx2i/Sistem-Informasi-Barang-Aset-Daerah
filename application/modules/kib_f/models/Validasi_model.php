<?php

if (!defined('BASEPATH')) exit('No direct script access allowed');

class Validasi_model extends CI_Model
{

  public $table = 'tbl_kib_f';
  public $id = 'id_kib_f';
  public $order = 'DESC';

  function __construct()
  {
    parent::__construct();
  }

  function data($number, $offset)
  {
    $session = $this->session->userdata('session');
    $pengguna = explode('.', $session->kode_lokasi)[3];
    $kuasa_pengguna =  explode('.', $session->kode_lokasi)[4];
    $query = "
      select distinct '1' as status, C.id_pengguna,C.pengguna, concat_ws(' - ',C.pengguna,C.instansi) instansi, null as jumlah
      from " . $this->table . " A
      left join tbl_kode_lokasi B on A.id_kode_lokasi=B.id_kode_lokasi
      left join view_pengguna C on B.pengguna=C.pengguna
      where validasi in (1)  and status_barang='kib' ";
    if ($session->id_role != 1){
      $query .= " and C.pengguna = '" . $pengguna . "'";
      if ($kuasa_pengguna != '0001')
        $query .= " and B.kuasa_pengguna = '" . $kuasa_pengguna . "'";
    }
    $query .= " union all ";
    $query .= "select '2' as status, A.id_kode_lokasi, B.pengguna, get_lokasi(A.id_kode_lokasi),count(A.id_kode_lokasi) as jumlah
      from " . $this->table . " A
      left join tbl_kode_lokasi B on A.id_kode_lokasi=B.id_kode_lokasi
      where validasi in (1)  and status_barang='kib' ";
    if ($session->id_role != 1){
      $query .= " and B.pengguna = '" . $pengguna . "'";
      if ($kuasa_pengguna != '0001')
        $query .= " and B.kuasa_pengguna = '" . $kuasa_pengguna . "'";
    }
    $query .= " group by A.id_kode_lokasi
      order by pengguna, id_pengguna";
    $offset = $offset ? $offset : '0';
    // $query .= ' limit ' . $offset . ',' . $number . ';';
    return
      $this->db->query($query)->result();
  }

  function jumlah_data()
  {
    $session = $this->session->userdata('session');
    $pengguna = explode('.', $session->kode_lokasi)[3];
    $kuasa_pengguna =  explode('.', $session->kode_lokasi)[4];
    $query = "
      select distinct '1' as status, C.id_pengguna,C.pengguna, concat_ws(' - ',C.pengguna,C.instansi) instansi, null as jumlah
      from " . $this->table . " A
      left join tbl_kode_lokasi B on A.id_kode_lokasi=B.id_kode_lokasi
      left join view_pengguna C on B.pengguna=C.pengguna
      where validasi in (1)  and status_barang='kib' ";
    if ($session->id_role != 1){
      $query .= " and C.pengguna = '" . $pengguna . "'";
      if ($kuasa_pengguna != '0001')
        $query .= " and B.kuasa_pengguna = '" . $kuasa_pengguna . "'";
    }
    $query .= " union all ";
    $query .= "select '2' as status, A.id_kode_lokasi, B.pengguna, get_lokasi(A.id_kode_lokasi),count(A.id_kode_lokasi) as jumlah
      from " . $this->table . " A
      left join tbl_kode_lokasi B on A.id_kode_lokasi=B.id_kode_lokasi
      where validasi in (1)  and status_barang='kib' ";
    if ($session->id_role != 1){
      $query .= " and B.pengguna = '" . $pengguna . "'";
      if ($kuasa_pengguna != '0001')
        $query .= " and B.kuasa_pengguna = '" . $kuasa_pengguna . "'";
    }
    $query .= " group by A.id_kode_lokasi
      order by pengguna, id_pengguna";
    return $this->db->query($query)->num_rows();
  }

  // datatables
  function json($id_kode_lokasi)
  {
    $this->load->helper('my_datatable');
    $this->datatables->select('id_kib_f,tanggal_transaksi,kode_barang,nama_barang,bangunan,kontruksi_bertingkat,kontruksi_beton,luas_m2,lokasi_alamat,dokumen_tanggal,dokumen_nomor,tanggal_mulai,status_tanah,nomor_kode_tanah,asal_usul,nilai_kontrak,A.keterangan,A.kode_lokasi,validasi, reject_note, B.instansi,A.deskripsi');
    $this->datatables->from('tbl_kib_f as A');
    $this->datatables->join('tbl_kode_lokasi B', 'A.id_kode_lokasi = B.id_kode_lokasi');
    $this->datatables->where('status_barang', 'kib');
    $this->datatables->where('A.id_kode_lokasi', $id_kode_lokasi);
    $this->datatables->where_in('validasi', array('1'));
    $this->datatables->add_column('action', anchor(base_url('kib_f/validasi/read/$1'), 'Detail'), 'id_kib_f');
    $this->datatables->edit_column('luas_m2', '$1', 'format_number(luas_m2)');
    $this->datatables->edit_column('dokumen_tanggal', '$1', 'date_time(dokumen_tanggal)');
    $this->datatables->edit_column('tanggal_mulai', '$1', 'date_time(tanggal_mulai)');
    $this->datatables->edit_column('nilai_kontrak', '$1', 'format_float(nilai_kontrak)');
    $this->datatables->edit_column('tanggal_transaksi', '$1', 'date_time(tanggal_transaksi)');
    $this->datatables->add_column('action', '$1', 'validasi_kib_action(id_kib_f,kib_f)');
    // $checkbox = "
    // <label class='switch'>
    // <input type='checkbox' class='live' status='1' id='$1' $2>
    // <span class='slider round'></span>
    // </label>
    // ";
    // $this->datatables->add_column('checkbox_validasi', $checkbox, $this->id . ', registrasi_status(nomor_register,validasi)');
    // $this->datatables->add_column('reject', '$1', 'reject(' . $this->id . ',validasi, reject_note)');
    return $this->datatables->generate();
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

  // get total rows
  function total_rows($q = NULL)
  {
    $this->db->like('id_kib_f', $q);
    $this->db->or_like('nama_barang', $q);
    $this->db->or_like('bangunan', $q);
    $this->db->or_like('kontruksi_bertingkat', $q);
    $this->db->or_like('kontruksi_beton', $q);
    $this->db->or_like('luas_m2', $q);
    $this->db->or_like('lokasi_alamat', $q);
    $this->db->or_like('dokumen_tanggal', $q);
    $this->db->or_like('dokumen_nomor', $q);
    $this->db->or_like('tanggal_mulai', $q);
    $this->db->or_like('status_tanah', $q);
    $this->db->or_like('nomor_kode_tanah', $q);
    $this->db->or_like('asal_usul', $q);
    $this->db->or_like('nilai_kontrak', $q);
    $this->db->or_like('keterangan', $q);
	 $this->db->or_like('deskripsi', $q);
    $this->db->from($this->table);
    return $this->db->count_all_results();
  }

  // get data with limit and search
  function get_limit_data($limit, $start = 0, $q = NULL)
  {
    $this->db->order_by($this->id, $this->order);
    $this->db->like('id_kib_f', $q);
    $this->db->or_like('nama_barang', $q);
    $this->db->or_like('bangunan', $q);
    $this->db->or_like('kontruksi_bertingkat', $q);
    $this->db->or_like('kontruksi_beton', $q);
    $this->db->or_like('luas_m2', $q);
    $this->db->or_like('lokasi_alamat', $q);
    $this->db->or_like('dokumen_tanggal', $q);
    $this->db->or_like('dokumen_nomor', $q);
    $this->db->or_like('tanggal_mulai', $q);
    $this->db->or_like('status_tanah', $q);
    $this->db->or_like('nomor_kode_tanah', $q);
    $this->db->or_like('asal_usul', $q);
    $this->db->or_like('nilai_kontrak', $q);
    $this->db->or_like('keterangan', $q);
	$this->db->or_like('deskripsi', $q);
    $this->db->limit($limit, $start);
    return $this->db->get($this->table)->result();
  }

  // insert data
  function insert($data)
  {
    $this->db->insert($this->table, $data);
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
}

/* End of file Kib_f_model.php */
/* Location: ./application/models/Kib_f_model.php */
/* Please DO NOT modify this information : */
/* Generated by Harviacode Codeigniter CRUD Generator 2018-08-07 08:14:32 */
/* http://harviacode.com */
