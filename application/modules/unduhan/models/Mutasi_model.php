<?php

if (!defined('BASEPATH')) exit('No direct script access allowed');

class Mutasi_model extends CI_Model
{

  public $table = 'tbl_mutasi';
  public $id = 'id_mutasi';
  public $order = 'DESC';
  public $tbl_kode_barang = 'tbl_kode_barang';

  public $tbl_mutasi_barang = 'tbl_mutasi_barang';
  public $id_mutasi_barang = 'id_mutasi_barang';

  public $tbl_mutasi_picture = 'tbl_mutasi_picture';
  public $id_mutasi_picture = 'id_mutasi_picture';


  function __construct()
  {
    parent::__construct();
  }

  // datatables
  function json()
  {
    $this->load->helper('my_datatable');
    $this->db->query('SET lc_time_names = "id_ID";');
    $this->datatables->select('A.id_mutasi,DATE_FORMAT(tanggal, "%d %M %Y") as tanggal,id_kode_lokasi_lama,id_kode_lokasi_baru, status_validasi, A.status_proses,A.created_at,updated_at, B.instansi as pengguna_lama, C.instansi as pengguna_baru,url');
    $this->datatables->from('tbl_mutasi A');
    $this->datatables->join('tbl_kode_lokasi B', 'A.id_kode_lokasi_lama=B.id_kode_lokasi', 'left');
    $this->datatables->join('tbl_kode_lokasi C', 'A.id_kode_lokasi_baru=C.id_kode_lokasi', 'left');
    $this->datatables->join('tbl_mutasi_picture E', 'A.id_mutasi=E.id_mutasi');
    // if($this->session->userdata('session')->id_role != '1'){  
      $this->datatables->where('id_kode_lokasi_baru', $this->session->userdata('session')->id_kode_lokasi);
      $this->datatables->or_where('id_kode_lokasi_lama', $this->session->userdata('session')->id_kode_lokasi);
    // }
    // $this->datatables->add_column('barang', '$1', 'barang_pengguna_baru(id_mutasi)');
    // $this->datatables->add_column('status_validasi_icon', '$1', 'status_validasi_icon(status_validasi)');
    $this->datatables->group_by('A.id_mutasi'); //jika skpd
    $this->datatables->add_column('status_validasi_icon', '$1', 'status_proses(status_proses)');
    // $this->datatables->add_column('action', '$1', 'pengecekan_barang_action(status_proses,status_validasi, id_mutasi)');
    $this->datatables->add_column("action", anchor(base_url('unduhan/mutasi/toExcel/$1'), "<button title='Lampiran BA Mutasi' class='btn btn-success btn-sm'><span class='fa fa-download'></span>Lampiran</button>").' '.anchor(base_url('$2'), "<button title='SK' class='btn btn-success btn-sm'><span class='fa fa-file'></span> BA Mutasi</button>"), "id_mutasi,url");
    // $this->datatables->add_column('action', '$1', 'pengajuan_action(status_validasi,id_mutasi)');
    return
      $this->datatables->generate();
  }

  function json_detail($id_mutasi)
  {
    $this->load->helper('my_datatable');
    $this->db->query('SET lc_time_names = "id_ID";');
    $this->datatables->select('A.id_mutasi_barang, A.status_diterima,A.id_kib,B.kib_name, B.nomor_register, C.kode_barang, C.nama_barang,B.id_inventaris');
    $this->datatables->from('tbl_mutasi_barang A');
    // $this->datatables->join('view_kib B', 'A.kode_jenis=B.kode_jenis and A.id_kib=B.id_kib','left');
    $this->datatables->join('(
      SELECT kode_jenis, id_kib, id_pemilik, kib_name, nomor_register, id_kode_barang, merk_type, id_kode_lokasi, kode_lokasi, asal_usul, tanggal_pembelian, tanggal_perolehan, validasi, kib_f, harga, status_barang,id_inventaris
      FROM `view_mutasi`
    ) B', 'A.kode_jenis=B.kode_jenis and A.id_kib=B.id_kib', 'left');
    $this->datatables->join('tbl_kode_barang C', 'B.id_kode_barang=C.id_kode_barang', 'left');
    $this->datatables->where('A.id_mutasi', $id_mutasi);
    $this->datatables->edit_column('status_diterima', '$1', 'status_diterima(status_diterima)');
    return
      $this->datatables->generate();
  }

  // get data by id
  function get_by_id($id)
  {
    $this->db->where($this->id, $id);
    return $this->db->get($this->table)->row();
  }

  // get data by id
  function get_bast($id_mutasi)
  {
    $this->db->select('A.*,B.instansi AS instansi_lama,C.instansi AS instansi_baru, H.nama AS pihak_lama, I.nama AS pihak_baru, H.nip AS pihak_nip_lama, I.nip AS pihak_nip_baru,F.instansi AS skpd_lama,G.instansi AS skpd_baru,B.kode_lokasi ');
    $this->db->from('tbl_mutasi A');
    $this->db->join('tbl_kode_lokasi B', 'A.id_kode_lokasi_lama = B.id_kode_lokasi', 'LEFT');
    $this->db->join('tbl_kode_lokasi C', 'A.id_kode_lokasi_baru = C.id_kode_lokasi', 'LEFT');
    $this->db->join('tbl_tanda_tangan D', 'A.id_kode_lokasi_lama = D.id_kode_lokasi AND D.id_jabatan = 4', 'LEFT'); //kpl unit kerja lama
    $this->db->join('tbl_tanda_tangan E', 'A.id_kode_lokasi_baru = E.id_kode_lokasi AND E.id_jabatan = 4', 'LEFT'); //kpl unit kerja baru
    $this->db->join('tbl_kode_lokasi F', 'A.id_skpd_lama = F.id_kode_lokasi', 'LEFT');
    $this->db->join('tbl_kode_lokasi G', 'A.id_skpd_baru = G.id_kode_lokasi', 'LEFT');
    $this->db->join('tbl_user H', 'D.id_user = H.id_user', 'LEFT');
    $this->db->join('tbl_user I', 'E.id_user = I.id_user', 'LEFT');
    $this->db->where('id_mutasi', $id_mutasi);
    return $this->db->get()->row();
  }

  function get_jumlah_barang($id_mutasi)
  {
    $this->db->select('COUNT(A.id_kib) as jumlah,SUM(A.harga) as harga');
    $this->db->from('view_mutasi A');
    $this->db->join('tbl_mutasi_barang B', 'A.id_kib = B.id_kib AND A.kode_jenis = B.kode_jenis', 'LEFT');
    $this->db->where('B.id_mutasi', $id_mutasi);
    $this->db->group_by('B.id_mutasi');
    return $this->db->get()->row();
  }

  function get_jenis_mutasi($id_mutasi)
  {
    $this->db->select('group_concat(distinct kode_jenis) as jenis');
    $this->db->where($this->id, $id_mutasi);
    return $this->db->get($this->tbl_mutasi_barang)->row();
  }

  function update_pengajuan($data, $id_mutasi_barang)
  {
    $this->db->update_batch($this->tbl_mutasi_barang, $data, $id_mutasi_barang);
  }

  function update_url_bast($id_mutasi, $data)
  {
    $this->db->where('id_mutasi', $id_mutasi);
    $this->db->update('tbl_mutasi', $data);
  }

  function update_pengajuan_register($id_mutasi, $data)
  {
    $this->db->where('id_mutasi', $id_mutasi);
    $this->db->update('tbl_mutasi', $data);
  }

  function update_bast($id_mutasi, $data)
  {
    $this->db->where('id_mutasi', $id_mutasi);
    $this->db->update('tbl_mutasi', $data);
  }


  function insert_picture($data)
  {
    $this->db->insert($this->tbl_mutasi_picture, $data);
    return $this->db->insert_id();
  }

  function get_picture_bast($id_mutasi)
  {
    $this->db->order_by($this->id_mutasi_picture, 'asc');
    $this->db->where('id_mutasi', $id_mutasi);
    return $this->db->get($this->tbl_mutasi_picture)->result();
  }

  function remove_picture($id_mutasi_picture)
  {
    $this->db->where($this->id_mutasi_picture, $id_mutasi_picture);
    $this->db->delete($this->tbl_mutasi_picture);
  }

  function get_lampiran_kib($id_mutasi,$lastPenyusutan)
  {
    $this->db->query('SET @no = 0;');
    $this->db->select(' (@no := @no + 1)  as no , 
      A.kode_jenis, A.kib_name, A.id_kib, A.id_pemilik, A.id_kode_lokasi, KB.kode_barang, 
      KB.nama_barang, nomor_register, 
      A.kode_lokasi, A.keterangan, A.asal_usul, A.tanggal_perolehan, A.harga,
      A.merk_type, A.sertifikat_nomor, A.bahan, A.ukuran_barang, A.satuan, 
      A.keadaan_barang, 1 as jumlah_barang, C.instansi,D.value,CASE WHEN E.nilai_penyusutan IS NOT NULL THEN E.nilai_penyusutan ELSE "Belum Disusutkan" END nilai_penyusutan,IFNULL(E.akumulasi_penyusutan,0) akumulasi_penyusutan,IFNULL(E.nilai_buku, A.harga) nilai_buku ', FALSE);
    $this->db->from(' view_mutasi A ');
    // $this->db->where('validasi', '2');
    $this->db->join('tbl_mutasi_barang B', 'A.id_kib = B.id_kib AND A.kode_jenis = B.kode_jenis');
    $this->db->join('tbl_kode_barang KB', 'A.id_kode_barang = KB.id_kode_barang');
    $this->db->join('tbl_kode_lokasi C', 'C.id_kode_lokasi=A.id_kode_lokasi', 'left');
    $this->db->join('tbl_master_intra_extra D', 'D.kode_jenis=A.kode_jenis', 'left');
    $this->db->join('tbl_penyusutan E', 'A.kode_jenis=E.kode_jenis AND A.id_kib = E.id_kib AND E.tanggal_penyusutan = "'.$lastPenyusutan.'"', 'left');
    $this->db->where('B.id_mutasi', $id_mutasi);
    $this->db->where('B.status_diterima', '2');
    $this->db->order_by('no', 'asc');
    return
      $this->db->get()->result_array();
    // die($this->db->last_query());
  
  }
}
