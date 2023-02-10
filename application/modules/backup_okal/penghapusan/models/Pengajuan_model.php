<?php

if (!defined('BASEPATH')) exit('No direct script access allowed');

class Pengajuan_model extends CI_Model{

  public $table = 'tbl_penghapusan';
  public $id = 'id_penghapusan';
  public $order = 'DESC';
  public $tbl_kode_barang = 'tbl_kode_barang';

  public $tbl_penghapusan_barang = 'tbl_penghapusan_barang';
  public $id_penghapusan_barang = 'id_penghapusan_barang';

  public $tbl_penghapusan_picture = 'tbl_penghapusan_picture';
  public $id_penghapusan_picture = 'id_penghapusan_picture';

  function __construct(){
    parent::__construct();
  }

  // datatables
  function json_list() {
    $this->load->helper('penghapusan');
    $this->db->query('SET lc_time_names = "id_ID";');
    // $this->datatables->select('A.id_penghapusan,DATE_FORMAT(tanggal_pengajuan, "%d %M %Y") as tanggal');
    // $this->datatables->from('tbl_penghapusan A');
    // $this->datatables->join('tbl_kode_lokasi B','A.id_kode_lokasi=B.id_kode_lokasi','left');
    // $this->datatables->where('A.id_kode_lokasi',$this->session->userdata('session')->id_kode_lokasi);
    // $this->datatables->add_column('barang', '$1', 'barang(id_penghapusan)');
    // $this->datatables->add_column('action', '$1', 'pengajuan_action(status_diterima,id_penghapusan)');
    // $this->datatables->add_column('keterangan', '$1', 'pengajuan_keterangan(status_diterima,id_penghapusan)');

    $this->datatables->select('A.id_penghapusan,DATE_FORMAT(A.tanggal_pengajuan, "%d %M %Y") as tanggal, A.status_proses,A.nama_paket');
    $this->datatables->from('tbl_penghapusan A');
	$this->datatables->join('tbl_penghapusan_barang C','A.id_penghapusan=C.id_penghapusan','left');
    $this->datatables->join('tbl_kode_lokasi B','A.id_kode_lokasi=B.id_kode_lokasi','left');
    if($this->session->userdata('session')->id_role != '1'){
    $this->datatables->where('A.id_kode_lokasi',$this->session->userdata('session')->id_kode_lokasi);
    }
    $this->db->group_by('A.id_penghapusan');
    $this->datatables->add_column('action', '$1', 'pengajuan_action(status_proses,id_penghapusan)');
    $this->datatables->add_column('keterangan', '$1', 'status_proses(status_proses)');
    return
    $this->datatables->generate();
    // die($this->db->last_query());
  }

  // datatables
  function json_detail($id_penghapusan) {
    $this->load->helper('penghapusan');
    $this->db->query('SET lc_time_names = "id_ID";');
    $this->datatables->select('A.kib_name, A.id_kib, A.nomor_register, B.status_diterima, C.kode_barang,CASE WHEN A.id_inventaris <> "" THEN CONCAT(C.nama_barang," / ",A.deskripsi) ELSE C.nama_barang END nama_barang,merk_type,harga,asal_usul,nomor_nomor,A.id_inventaris');
    $this->datatables->from('view_penghapusan as A ');
    $this->datatables->join('tbl_penghapusan_barang as B','A.kode_jenis=B.kode_jenis and A.id_kib=B.id_kib');
    $this->datatables->join('tbl_kode_barang as C','A.id_kode_barang=C.id_kode_barang');
    $this->datatables->where('B.id_penghapusan',$id_penghapusan);
    $this->datatables->add_column('status_penghapusan', '$1', 'status_penghapusan(status_diterima)');
    // $this->datatables->add_column('barang', '$1', 'barang(id_penghapusan)');
    // $this->datatables->add_column('action', '$1', 'pengajuan_action(status_diterima,id_penghapusan)');
    // $this->datatables->add_column('keterangan', '$1', 'pengajuan_keterangan(status_diterima,id_penghapusan)');
    return
    $this->datatables->generate();
    // die($this->db->last_query());
  }


  // get all
  function get_all(){
    $this->db->order_by($this->id, $this->order);
    return $this->db->get($this->table)->result();
  }

  // get data by id
  function get_by_id($id){
    $this->db->where($this->id, $id);
    return $this->db->get($this->table)->row();
  }

  // get data by id
  function get_barang($id_penghapusan_barang){
    $this->db->select('group_concat(id_kode_barang) as id_kode_barang');
    $this->db->where($this->id, $id_penghapusan_barang);
    $this->db->group_by($this->id);
    return $this->db->get($this->tbl_penghapusan_barang)->row();
    // die($this->db->last_query());
  }

  // get total rows
  function total_rows($q = NULL) {
    $this->db->like('id_penghapusan', $q);
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
  function get_limit_data($limit, $start = 0, $q = NULL) {
    $this->db->order_by($this->id, $this->order);
    $this->db->like('id_penghapusan', $q);
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
  function insert($data){
    $this->db->insert($this->table, $data);
    return $this->db->insert_id();
  }

  // insert data
  function insert_barang($data){
    $this->db->insert_batch($this->tbl_penghapusan_barang, $data);
  }

  function update_barang($id_penghapusan,$data){
    $this->db->where($this->id, $id_penghapusan);
    $this->db->delete($this->tbl_penghapusan_barang);

    $this->db->insert_batch($this->tbl_penghapusan_barang, $data);
  }


  // update data
  function update($id, $data){
    $this->db->where($this->id, $id);
    $this->db->update($this->table, $data);
  }

  // delete data
  function delete($id){
    $this->db->where($this->id, $id);
    $this->db->delete($this->table);
  }

  // delete data
  function delete_penghapusan_barang($id){
    $this->db->where($this->id, $id);
    $this->db->delete($this->tbl_penghapusan_barang);
  }

  function insert_picture($data){
    $this->db->insert($this->tbl_penghapusan_picture,$data);
    return $this->db->insert_id();
  }

  function remove_picture($id_penghapusan_picture){
    $this->db->where($this->id_penghapusan_picture,$id_penghapusan_picture);
    $this->db->delete($this->tbl_penghapusan_picture);
  }

  
  function get_lampiran($id_pengahapusan)
  {
    $this->db->query('SET @row_number = 0;');
    return
      $this->db->query("
      select (@row_number:=@row_number + 1) AS no,A.id_penghapusan_barang, A.kode_jenis, A.id_kib , B.id_penghapusan_lampiran,
      	E.nama_barang,D.merk_type,D.tanggal_pembelian, E.kode_barang,D.nomor_register,D.asal_usul,
        CASE when C.nilai_perolehan<>'' THEN C.nilai_perolehan ELSE D.harga end as harga_barang,
        C.akumulasi_penyusutan, C.nilai_buku,D.id_kode_lokasi,G.instansi as skpd, F.instansi,'' AS status_diterima,
      	  C.umur_bulan, C.umur_ekonomis, C.nilai_perolehan, C.nilai_penyusutan,D.nomor_nomor,YEAR(D.tanggal_perolehan) AS tahun_perolehan,D.kondisi,D.keterangan,D.deskripsi,D.id_inventaris
      from tbl_penghapusan_barang as A
      left join tbl_penghapusan_lampiran_detail as B on A.id_penghapusan_barang=B.id_penghapusan_barang
      left join
      (select *
      from tbl_penyusutan
      where month(tanggal_penyusutan)=month(curdate()) and year(tanggal_penyusutan)=year(curdate())
      ) as C on A.kode_jenis=C.kode_jenis and A.id_kib=C.id_kib
      left join view_penghapusan as D on A.kode_jenis=D.kode_jenis and A.id_kib=D.id_kib
      left join tbl_kode_barang E on D.id_kode_barang=E.id_kode_barang
      left join tbl_kode_lokasi F on D.id_kode_lokasi=F.id_kode_lokasi
      left join view_pengguna G on F.pengguna=G.pengguna
      where A.id_penghapusan=" . $id_pengahapusan . ";
      ")->result_array();
    // die($this->db->last_query());
  }

}
