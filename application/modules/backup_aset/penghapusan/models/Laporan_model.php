<?php

if (!defined('BASEPATH')) exit('No direct script access allowed');

class laporan_model extends CI_Model
{

  public $table = 'tbl_penghapusan_lampiran';
  public $id = 'id_penghapusan_lampiran';
  public $tbl_penghapusan_lampiran_detail = 'tbl_penghapusan_lampiran_detail';
  public $id_penghapusan_lampiran_detail = 'id_penghapusan_lampiran_detail';
  public $order = 'DESC';
  public $tbl_kode_barang = 'tbl_kode_barang';

  function __construct()
  {
    parent::__construct();
  }

  // datatables
  function json_list()
  {
    $this->load->helper('penghapusan');
    $this->db->query('SET lc_time_names = "id_ID";');
    $this->datatables->select('id_penghapusan_lampiran, tanggal_lampiran, keterangan,file_sk');
    $this->datatables->from('tbl_penghapusan_lampiran');
    // $this->datatables->group_by('nomor_pembuatan,tanggal_lampiran');
    $this->datatables->edit_column('tanggal_lampiran', '$1', 'tgl_indo(tanggal_lampiran)');
    $this->datatables->add_column("action", anchor(base_url('penghapusan/laporan/detail/$1'), "<button title='Detail' class='btn btn-info btn-sm'><span class='fa fa-search'></span></button>").' '.anchor(base_url('$2'), "<button title='SK' class='btn btn-success btn-sm'><span class='fa fa-file'></span></button>"), "id_penghapusan_lampiran,file_sk");

    return
      $this->datatables->generate();
    // die($this->datatables->last_query());
  }

  // datatables
  function json_detail($id_pengahapusan_lampiran)
  {
    $this->load->helper('penghapusan');
    $this->db->query('SET lc_time_names = "id_ID";');
    $this->datatables->select('C.kode_jenis,C.kib_name, C.nomor_register, D.kode_barang, CASE WHEN C.id_inventaris <> "" THEN CONCAT(D.nama_barang," / ",C.deskripsi) ELSE D.nama_barang END nama_barang,id_inventaris');
    $this->datatables->from('tbl_penghapusan_lampiran_detail A');
    $this->datatables->join('tbl_penghapusan_barang B', 'A.id_penghapusan_barang=B.id_penghapusan_barang', 'left');
    $this->datatables->join('view_penghapusan C', 'B.kode_jenis=C.kode_jenis and B.id_kib=C.id_kib', 'left');
    $this->datatables->join('tbl_kode_barang D', 'C.id_kode_barang=D.id_kode_barang', 'left');
    $this->datatables->where('id_penghapusan_lampiran', $id_pengahapusan_lampiran);

    return
      $this->datatables->generate();
    // die($this->datatables->last_query());
  }

  // datatables
  function json_paket() {
    $this->load->helper('penghapusan');
    $this->db->query('SET lc_time_names = "id_ID";');
    $this->datatables->select('A.id_penghapusan,DATE_FORMAT(tanggal_pengajuan, "%d %M %Y") as tanggal,A.id_kode_lokasi, A.status_proses,B.instansi,A.nama_paket');
    $this->datatables->from('tbl_penghapusan A');
    $this->datatables->join('tbl_kode_lokasi B','A.id_kode_lokasi=B.id_kode_lokasi','left');
    // $this->datatables->add_column('barang', '$1', 'barang_pengecekan(id_penghapusan)');
    $this->datatables->where('status_proses', '2');
    $this->datatables->add_column('action','$1','lampiran_action(id_penghapusan,nama_paket)');
    return
    $this->datatables->generate();
  }


  // insert data
  function insert($data)
  {
    $this->db->insert($this->table, $data);
    return $this->db->insert_id();
  }

  // insert data detail
  function insert_detail($data)
  {
    $this->db->insert($this->tbl_penghapusan_lampiran_detail, $data);
    return $this->db->insert_id();
  }

  // update data
  function update($id, $data)
  {
    $this->db->where($this->id, $id);
    $this->db->update($this->table, $data);
  }

  public function get_barang($id_penghapusan)
  {
    $this->db->select('id_penghapusan_barang');
    $this->db->from('tbl_penghapusan_barang' . ' A');
    $this->db->where('id_penghapusan', $id_penghapusan);
    return $this->db->get()->result();
  }


  function get_lampiran($id_pengahapusan_lampiran)
  {
    $this->db->query('SET @row_number = 0;');
    return
      $this->db->query("
      select (@row_number:=@row_number + 1) AS no,A.id_penghapusan_barang, A.kode_jenis, A.id_kib , B.id_penghapusan_lampiran,
      	E.nama_barang,D.merk_type,D.tanggal_pembelian, E.kode_barang,D.nomor_register,D.asal_usul,
        CASE when C.nilai_perolehan<>'' THEN C.nilai_perolehan ELSE D.harga end as harga_barang,
        C.akumulasi_penyusutan, C.nilai_buku,D.id_kode_lokasi,G.instansi as skpd, F.instansi,D.id_inventaris,D.keterangan,
      	  C.umur_bulan, C.umur_ekonomis, C.nilai_perolehan, C.nilai_penyusutan
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
      where B.id_penghapusan_lampiran=" . $id_pengahapusan_lampiran . ";
      ")->result_array();
    // die($this->db->last_query());
  }


  function get_lampiran_rekap($id_pengahapusan_lampiran)
  {
    $this->db->query('SET @row_number = 0;');
    return
      $this->db->query("
      select (@row_number:=@row_number + 1) AS no,C.id_kode_lokasi,G.instansi as skpd, F.instansi, 
        count(*) as jumlah_barang, 
        sum(CASE  when C.nilai_perolehan <> '' THEN C.nilai_perolehan else D.harga end) as jumlah_harga,
        sum(C.akumulasi_penyusutan) as jumlah_akumulasi_penyusutan, sum(C.nilai_buku) as jumlah_nilai_buku
      from tbl_penghapusan_barang as A
      left join tbl_penghapusan H on H.id_penghapusan =A.id_penghapusan
      left join tbl_penghapusan_lampiran_detail as B on A.id_penghapusan_barang=B.id_penghapusan_barang
      left join
      	(select *
      	from tbl_penyusutan
      	where tanggal_penyusutan=first_day(curdate())
        ) as C on A.kode_jenis=C.kode_jenis and A.id_kib=C.id_kib
        left join view_penghapusan as D on A.kode_jenis=D.kode_jenis and A.id_kib=D.id_kib
      left join tbl_kode_lokasi F on H.id_kode_lokasi=F.id_kode_lokasi
      left join view_pengguna G on F.pengguna=G.pengguna
      where B.id_penghapusan_lampiran=" . $id_pengahapusan_lampiran . "
      group by C.id_kode_lokasi;
      ")->result_array();
    // die($this->db->last_query());
  }

  /*
  function get_lampiran_backup($data){
    $jenis = $this->global_model->kode_jenis[$data['kode_jenis']];
    $this->db->query('SET @row_number = 0;');
    return $this->db->query(
      "
      select (@row_number:=@row_number + 1) AS no,A.*
      from ".$jenis['table']." as A
      where ".$jenis['id_name']." in
      	(
      	select id_kib
      	from tbl_penghapusan_lampiran A
      	left join tbl_penghapusan_barang B on A.id_penghapusan_barang=B.id_penghapusan_barang
      	where kode_jenis=".$data['kode_jenis']." and nomor_pembuatan=".$data['nomor_pembuatan']."
          )
      ;
      "
    )->result_array();

  }
*/
}
