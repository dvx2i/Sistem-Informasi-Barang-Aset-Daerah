<?php

if (!defined('BASEPATH'))    exit('No direct script access allowed');

class Validasi_model extends CI_Model
{
  public $table = 'tbl_kib_a';
  public $id = 'id_kib_a';
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
        where validasi in (1) and status_barang='kib' ";
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
    // die($this->db->last_query());

    // $this->db->select(" distinct '1' as status, C.id_pengguna,C.pengguna, concat_ws(' - ',C.pengguna,C.instansi) instansi, null as jumlah");
    // $this->db->from($this->table." A");
    // $this->db->join('tbl_kode_lokasi B', 'A.id_kode_lokasi=B.id_kode_lokasi', 'left');
    // $this->db->join('view_pengguna C', 'B.pengguna=C.pengguna', 'left');
    // $this->db->where('validasi in ','(1,3)');
    // $this->db->where('C.pengguna',$pengguna);
    // $query1 = $this->db->get_compiled_select();
    //
    // $this->db->select("'2' as status, A.id_kode_lokasi, B.pengguna, get_lokasi(A.id_kode_lokasi),count(A.id_kode_lokasi) as jumlah");
    // $this->db->from($this->table." A");
    // $this->db->join('tbl_kode_lokasi B', 'A.id_kode_lokasi=B.id_kode_lokasi', 'left');
    // $this->db->where('validasi in ','(1,3)');
    // $this->db->where('B.pengguna',$pengguna);
    // $query2 = $this->db->get_compiled_select();
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
    $this->datatables->select('id_kib_a,tanggal_transaksi,A.nama_barang,A.kode_barang,A.nomor_register,A.luas,A.tahun_pengadaan,A.letak_alamat,A.status_hak,A.sertifikat_tanggal,A.sertifikat_nomor,A.penggunaan,A.asal_usul,A.harga,A.keterangan,A.kode_lokasi,A.created_at,A.updated_at, B.nama_barang as nama_barang2, A.validasi, A.reject_note, C.instansi,A.deskripsi');
    $this->datatables->from('tbl_kib_a A');
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

    $this->datatables->edit_column('luas', '$1', 'format_number(luas)');
    $this->datatables->edit_column('sertifikat_tanggal', '$1', 'date_time(sertifikat_tanggal)');
    $this->datatables->edit_column('tanggal_transaksi', '$1', 'date_time(tanggal_transaksi)');
    $this->datatables->edit_column('harga', '$1', 'format_float(harga)');

    $this->datatables->add_column('action', '$1', 'validasi_kib_action(id_kib_a,kib_a)');

    // $checkbox = "
    //   <label class='switch' title='Validasi'>
    //   <input class='live' type='checkbox' id='$1' $2>
    //   <span class='slider round'></span>
    //   </label>
    //   ";
    // $this->datatables->add_column('checkbox_validasi', $checkbox, $this->id . ', registrasi_status(nomor_register,validasi)');
    // $this->datatables->add_column('reject', '$1', 'reject(' . $this->id . ',validasi, reject_note)');

    return  $this->datatables->generate();
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
    $this->db->like('id_kib_a', $q);
    $this->db->or_like('nama_barang', $q);
    $this->db->or_like('kode_barang', $q);
    $this->db->or_like('nomor_register', $q);
    $this->db->or_like('luas', $q);
    $this->db->or_like('tahun_pengadaan', $q);
    $this->db->or_like('letak_alamat', $q);
    $this->db->or_like('status_hak', $q);
    $this->db->or_like('sertifikat_tanggal', $q);
    $this->db->or_like('sertifikat_nomor', $q);
    $this->db->or_like('penggunaan', $q);
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
  /*
    // get data with limit and search
    function get_limit_data($limit, $start = 0, $q = NULL) {
      $this->db->order_by($this->id, $this->order);
      $this->db->like('id_kib_a', $q);
      $this->db->or_like('nama_barang', $q);
      $this->db->or_like('kode_barang', $q);
      $this->db->or_like('nomor_register', $q);
      $this->db->or_like('luas', $q);
      $this->db->or_like('tahun_pengadaan', $q);
      $this->db->or_like('letak_alamat', $q);
      $this->db->or_like('status_hak', $q);
      $this->db->or_like('sertifikat_tanggal', $q);
      $this->db->or_like('sertifikat_nomor', $q);
      $this->db->or_like('penggunaan', $q);
      $this->db->or_like('asal_usul', $q);
      $this->db->or_like('harga', $q);
      $this->db->or_like('keterangan', $q);
      $this->db->or_like('kode_lokasi', $q);
      $this->db->or_like('created_at', $q);
      $this->db->or_like('updated_at', $q);
      $this->db->limit($limit, $start);
      return $this->db->get($this->table)->result();
    }*/
}

/* End of file Kib_a_model.php */
/* Location: ./application/models/Kib_a_model.php */
/* Please DO NOT modify this information : */
/* Generated by Harviacode Codeigniter CRUD Generator 2018-08-07 08:12:54 */
/* http://harviacode.com */
