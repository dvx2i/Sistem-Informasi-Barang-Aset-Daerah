<?php

if (!defined('BASEPATH')) exit('No direct script access allowed');

class Validasi_model extends CI_Model
{

  public $table = 'tbl_kib_atb';
  public $id = 'id_kib_atb';
  public $order = 'DESC';

  public function __construct()
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
    $query .= "select '2' as status, A.id_kode_lokasi, B.pengguna, CONCAT(B.kode_lokasi,' - ',B.instansi),count(A.id_kode_lokasi) as jumlah
      from " . $this->table . " A
      left join tbl_kode_lokasi B on A.id_kode_lokasi=B.id_kode_lokasi
      where validasi in (1)  and status_barang='kib' ";
    if ($session->id_role != 1){
      $query .= " and B.pengguna = '" . $pengguna . "'";
      if ($kuasa_pengguna != '0001')
        $query .= " and B.kuasa_pengguna = '" . $kuasa_pengguna . "'";
    }
    $query .= " group by A.id_kode_lokasi
    order by pengguna, id_pengguna, status";
    $offset = $offset ? $offset : '0';
    // $query .= ' limit ' . $offset . ',' . $number . ';';
    return
      $this->db->query($query)->result();
    // die($this->db->last_query());
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
    /*$this->datatables->select('id_kib_atb,kode_barang,nama_barang,bangunan,kontruksi_bertingkat,kontruksi_beton,luas_m2,lokasi_alamat,dokumen_tanggal,dokumen_nomor,tanggal_mulai,status_tanah,nomor_kode_tanah,asal_usul,nilai_kontrak,keterangan,kode_lokasi,validasi, reject_note');
    $this->datatables->from('tbl_kib_atb');
    $this->datatables->where('id_kode_lokasi', $id_kode_lokasi);
    $this->datatables->where_in('validasi', array('1', '3'));
    $this->datatables->add_column('action', anchor(base_url('kib_f/validasi/read/$1'), 'Detail'), 'id_kib_f');
    $this->datatables->edit_column('luas_m2', '$1', 'format_number(luas_m2)');
    $this->datatables->edit_column('tanggal_', '$1', 'date_time(dokumen_tanggal)');
    $this->datatables->edit_column('tanggal_mulai', '$1', 'date_time(tanggal_mulai)');
    $this->datatables->edit_column('nilai_kontrak', '$1', 'format_number(nilai_kontrak)');*/
    $this->datatables->select('A.id_kib_atb,tanggal_transaksi,A.id_pemilik,A.id_kode_barang,A.id_kode_lokasi, A.kode_barang,A.nama_barang,A.nomor_register,A.judul_kajian_nama_software,A.tanggal_perolehan,A.asal_usul, A.harga,A.keterangan,A.deskripsi,A.kode_lokasi,A.validasi,A.created_at,A.updated_at, B.instansi');
    $this->datatables->from(' tbl_kib_atb as A ');
    $this->datatables->join(' tbl_kode_lokasi B', 'A.id_kode_lokasi = B.id_kode_lokasi');
    $this->db->where('status_barang', "kib");
    $this->datatables->where('A.id_kode_lokasi', $id_kode_lokasi);

    $this->datatables->where_in('validasi', array('1'));
    $this->datatables->add_column('action', anchor(base_url('kib_atb/validasi/read/$1'), 'Detail'), 'id_kib_atb');
    // $this->datatables->edit_column('validasi', '$1', 'status_validasi_kib(validasi,reject_note)');

    $this->datatables->edit_column('tanggal_perolehan', '$1', 'date_time(tanggal_perolehan)');
    $this->datatables->edit_column('harga', '$1', 'format_float(harga)');
    $this->datatables->edit_column('tanggal_transaksi', '$1', 'date_time(tanggal_transaksi)');
    $this->datatables->add_column('action', '$1', 'validasi_kib_action(id_kib_atb,kib_atb)');

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
    $this->db->like('id_kib_atb', $q);
    $this->db->or_like('id_pemilik', $q);
    $this->db->or_like('id_kode_barang', $q);
    $this->db->or_like('id_kode_lokasi', $q);
    $this->db->or_like('nama_barang', $q);
    $this->db->or_like('nomor_register', $q);
    $this->db->or_like('judul_kajian_nama_software', $q);
    // $this->db->or_like('tahun_perolehan', $q);
    $this->db->or_like('asal_usul', $q);
    $this->db->or_like('keterangan', $q);
    $this->db->or_like('deskripsi', $q);
    $this->db->or_like('kode_lokasi', $q);
    $this->db->or_like('validasi', $q);
    $this->db->or_like('created_at', $q);
    $this->db->or_like('updated_at', $q);
    $this->db->from($this->table);
    return $this->db->count_all_results();
  }

  // get data with limit and search
  function get_limit_data($limit, $start = 0, $q = NULL)
  {
    $this->db->order_by($this->id, $this->order);
    $this->db->like('id_kib_atb', $q);
    $this->db->or_like('id_pemilik', $q);
    $this->db->or_like('id_kode_barang', $q);
    $this->db->or_like('id_kode_lokasi', $q);
    $this->db->or_like('nama_barang', $q);
    $this->db->or_like('nomor_register', $q);
    $this->db->or_like('judul_kajian_nama_software', $q);
    // $this->db->or_like('tahun_perolehan', $q);
    $this->db->or_like('asal_usul', $q);
    $this->db->or_like('keterangan', $q);
    $this->db->or_like('deskripsi', $q);
    $this->db->or_like('kode_lokasi', $q);
    $this->db->or_like('validasi', $q);
    $this->db->or_like('created_at', $q);
    $this->db->or_like('updated_at', $q);
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
