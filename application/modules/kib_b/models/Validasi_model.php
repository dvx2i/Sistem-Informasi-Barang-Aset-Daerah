<?php

if (!defined('BASEPATH'))
  exit('No direct script access allowed');

class Validasi_model extends CI_Model
{

  public $table = 'tbl_kib_b';
  public $id = 'id_kib_b';
  public $order = 'DESC';
  public $tbl_barang = 'tbl_kode_barang';
  public $tbl_kode_lokasi = "tbl_kode_lokasi";

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
    $this->datatables->select('A.id_kib_b,tanggal_transaksi,A.kode_barang,A.nama_barang,A.nomor_register,A.merk_type,A.ukuran_cc,A.bahan,A.tahun_pembelian,A.nomor_pabrik,A.nomor_rangka,A.nomor_mesin,A.nomor_polisi,A.nomor_bpkb,A.asal_usul,A.harga,A.keterangan,A.kode_lokasi,A.created_at,A.updated_at, B.nama_barang as nama_barang2,A.validasi, A.reject_note, C.instansi,A.deskripsi');
    $this->datatables->from('tbl_kib_b A');
    $this->datatables->join($this->tbl_barang . ' B', 'A.id_kode_barang = B.id_kode_barang');
    $this->datatables->join($this->tbl_kode_lokasi . ' C', 'A.id_kode_lokasi = C.id_kode_lokasi');
    $this->datatables->where('status_barang', 'kib');
    $this->datatables->where('A.id_kode_lokasi', $id_kode_lokasi);
    $session = $this->session->userdata('session');
    $lokasi = explode('.', $session->kode_lokasi);
    $pengguna = $lokasi[3];
    $kuasa_pengguna =  $lokasi[4];
    if ($session->id_role != '1') //bukan super admin
      $this->datatables->where('C.pengguna', $pengguna);

    $this->datatables->where_in('validasi', array('1'));
    // $this->datatables->edit_column('ukuran_cc', '$1', 'format_number(ukuran_cc)');
    $this->datatables->edit_column('harga', '$1', 'format_float(harga)');
    $this->datatables->edit_column('tanggal_transaksi', '$1', 'date_time(tanggal_transaksi)');
    // $this->datatables->add_column('action', anchor(base_url('kib_b/validasi/read/$1'), '<button  class="btn btn-primary btn-sm"><i class="fa fa-eye"></i></button>'), 'id_kib_b');
    // $checkbox = "
    // <label class='switch'>
    // <input type='checkbox' class='live' status='1' id='$1' $2>
    // <span class='slider round'></span>
    // </label>
    // ";
    // $this->datatables->add_column('checkbox_validasi', $checkbox, $this->id . ', registrasi_status(nomor_register,validasi)');
    // $this->datatables->add_column('reject', '$1', 'reject(' . $this->id . ',validasi, reject_note)');
    // $this->datatables->add_column('test_validasi', '<input class="checkbox_kib" type="checkbox" id="validasi_$1" attr_id="$1">', $this->id);

    $this->datatables->add_column('action', '$1', 'validasi_kib_action(id_kib_b,kib_b)');
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
	$this->db->or_like('deskripsi', $q);
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
	$this->db->or_like('deskripsi', $q);
    $this->db->limit($limit, $start);
    return $this->db->get($this->table)->result();
  }
}

/* End of file Kib_b_model.php */
/* Location: ./application/models/Kib_b_model.php */
/* Please DO NOT modify this information : */
/* Generated by Harviacode Codeigniter CRUD Generator 2018-08-07 08:13:19 */
/* http://harviacode.com */
