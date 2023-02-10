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
    // $this->datatables->add_column("action", anchor(base_url('penghapusan/laporan/detail/$1'), "<button title='Detail' class='btn btn-info btn-sm'><span class='fa fa-search'></span></button>").' '.anchor(base_url('$2'), "<button title='SK' class='btn btn-success btn-sm'><span class='fa fa-file'></span></button>").' '.anchor(base_url('penghapusan/laporan/laporan_detail/$1'), "<button title='SK' class='btn btn-success btn-sm'><span class='fa fa-download'></span> Rincian Lampiran</button>").' '.anchor(base_url('penghapusan/laporan/laporan_rekap/$1'), "<button title='Rekap Lampiran' class='btn btn-success btn-sm'><span class='fa fa-download'></span> Rekap Lampiran</button>"), "id_penghapusan_lampiran,file_sk");

    $this->datatables->add_column("action", anchor(base_url('penghapusan/laporan/laporan_detail/$1'), "<button title='SK' class='btn btn-success btn-sm'><span class='fa fa-download'></span> Rincian Lampiran</button>").' '.anchor(base_url('penghapusan/laporan/laporan_rekap/$1'), "<button title='Rekap Lampiran' class='btn btn-success btn-sm'><span class='fa fa-download'></span> Rekap Lampiran</button>").' '.anchor(base_url('$2'), "<button title='SK' class='btn btn-success btn-sm'><span class='fa fa-file'></span> SK</button>"), "id_penghapusan_lampiran,file_sk");
    return
      $this->datatables->generate();
    // die($this->datatables->last_query());
  }

  // datatables
  function json_detail($id_pengahapusan_lampiran)
  {
    $this->load->helper('penghapusan');
    $this->db->query('SET lc_time_names = "id_ID";');
    $this->datatables->select('C.id_kib,C.kode_jenis,C.kib_name, C.nomor_register, D.kode_barang, CASE WHEN C.id_inventaris <> "" THEN CONCAT(D.nama_barang," / ",C.deskripsi) ELSE D.nama_barang END nama_barang,id_inventaris');
    $this->datatables->from('tbl_penghapusan_lampiran_detail A');
    $this->datatables->join('tbl_penghapusan_barang B', 'A.id_penghapusan_barang=B.id_penghapusan_barang', 'left');
    $this->datatables->join('view_penghapusan C', 'B.kode_jenis=C.kode_jenis and B.id_kib=C.id_kib', 'left');
    $this->datatables->join('tbl_kode_barang D', 'C.id_kode_barang=D.id_kode_barang', 'left');
    $this->datatables->where('id_penghapusan_lampiran', $id_pengahapusan_lampiran);
    $this->datatables->where('status_diterima', '2');

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


  function get_lampiran($id_pengahapusan_lampiran,$lastPenyusutan)
  {
    $this->db->query('SET @row_number = 0;');
    return
      $this->db->query("
      select (@row_number:=@row_number + 1) AS no,A.id_penghapusan_barang, A.kode_jenis, A.id_kib , B.id_penghapusan_lampiran,
      	E.nama_barang,D.merk_type,D.tanggal_pembelian, E.kode_barang,D.nomor_register,D.asal_usul,
        CASE when C.nilai_perolehan<>'' THEN C.nilai_perolehan ELSE D.harga end as harga_barang,
        C.nilai_penyusutan,C.akumulasi_penyusutan, C.nilai_buku,D.id_kode_lokasi,G.instansi as skpd, F.instansi,D.id_inventaris,D.keterangan,
      	  C.umur_bulan, C.umur_ekonomis, C.nilai_perolehan, C.nilai_penyusutan
      from tbl_penghapusan_barang as A
      join tbl_penghapusan_lampiran_detail as B on A.id_penghapusan_barang=B.id_penghapusan_barang
      left join
      tbl_penyusutan as C on A.kode_jenis=C.kode_jenis and A.id_kib=C.id_kib AND C.tanggal_penyusutan = '".$lastPenyusutan."'
      join (
        SELECT '01' AS `kode_jenis`,`tbl_kib_a`.`id_kib_a` AS `id_kib`,`tbl_kib_a`.`id_pemilik` AS `id_pemilik`,`tbl_kib_a`.`id_kode_lokasi` AS `id_kode_lokasi`,'KIB A' AS `kib_name`,`tbl_kib_a`.`nomor_register` AS `nomor_register`,`tbl_kib_a`.`id_kode_barang` AS `id_kode_barang`,`tbl_kib_a`.`harga` AS `harga`,'' AS `merk_type`,`tbl_kib_a`.`asal_usul` AS `asal_usul`,`tbl_kib_a`.`tanggal_pembelian` AS `tanggal_pembelian`,`tbl_kib_a`.`sertifikat_nomor` AS `nomor_nomor`,`tbl_kib_a`.`tanggal_perolehan` AS `tanggal_perolehan`,`tbl_kib_a`.`kondisi` AS `kondisi`,`tbl_kib_a`.`keterangan` AS `keterangan`,`tbl_kib_a`.`deskripsi` AS `deskripsi`,`tbl_kib_a`.`id_inventaris` AS `id_inventaris` FROM `tbl_kib_a` WHERE `tbl_kib_a`.`id_kib_a` IN (SELECT `tbl_penghapusan_barang`.`id_kib` FROM `tbl_penghapusan_barang`) UNION ALL SELECT '02' AS `kode_jenis`,`tbl_kib_b`.`id_kib_b` AS `id_kib_b`,`tbl_kib_b`.`id_pemilik` AS `id_pemilik`,`tbl_kib_b`.`id_kode_lokasi` AS `id_kode_lokasi`,'KIB B' AS `kib_name`,`tbl_kib_b`.`nomor_register` AS `nomor_register`,`tbl_kib_b`.`id_kode_barang` AS `id_kode_barang`,`tbl_kib_b`.`harga` AS `harga`,`tbl_kib_b`.`merk_type` AS `merk_type`,`tbl_kib_b`.`asal_usul` AS `asal_usul`,`tbl_kib_b`.`tanggal_pembelian` AS `tanggal_pembelian`,CONCAT(`tbl_kib_b`.`nomor_pabrik`,', ',`tbl_kib_b`.`nomor_rangka`,', ',`tbl_kib_b`.`nomor_mesin`,', ',`tbl_kib_b`.`nomor_polisi`,', ',`tbl_kib_b`.`nomor_bpkb`) AS `nomor_nomor`,`tbl_kib_b`.`tanggal_perolehan` AS `tanggal_perolehan`,`tbl_kib_b`.`kondisi` AS `kondisi`,`tbl_kib_b`.`keterangan` AS `keterangan`,`tbl_kib_b`.`deskripsi` AS `deskripsi`,`tbl_kib_b`.`id_inventaris` AS `id_inventaris` FROM `tbl_kib_b` WHERE `tbl_kib_b`.`id_kib_b` IN (SELECT `tbl_penghapusan_barang`.`id_kib` FROM `tbl_penghapusan_barang`) UNION ALL SELECT '03' AS `kode_jenis`,`tbl_kib_c`.`id_kib_c` AS `id_kib_c`,`tbl_kib_c`.`id_pemilik` AS `id_pemilik`,`tbl_kib_c`.`id_kode_lokasi` AS `id_kode_lokasi`,'KIB C' AS `kib_name`,`tbl_kib_c`.`nomor_register` AS `nomor_register`,`tbl_kib_c`.`id_kode_barang` AS `id_kode_barang`,`tbl_kib_c`.`harga` AS `harga`,'' AS `merk_type`,`tbl_kib_c`.`asal_usul` AS `asal_usul`,`tbl_kib_c`.`tanggal_pembelian` AS `tanggal_pembelian`,`tbl_kib_c`.`gedung_nomor` AS `nomor_nomor`,`tbl_kib_c`.`tanggal_perolehan` AS `tanggal_perolehan`,`tbl_kib_c`.`kondisi_bangunan` AS `kondisi_bangunan`,`tbl_kib_c`.`keterangan` AS `keterangan`,`tbl_kib_c`.`deskripsi` AS `deskripsi`,`tbl_kib_c`.`id_inventaris` AS `id_inventaris` FROM `tbl_kib_c` WHERE `tbl_kib_c`.`id_kib_c` IN (SELECT `tbl_penghapusan_barang`.`id_kib` FROM `tbl_penghapusan_barang`) UNION ALL SELECT '04' AS `kode_jenis`,`tbl_kib_d`.`id_kib_d` AS `id_kib_d`,`tbl_kib_d`.`id_pemilik` AS `id_pemilik`,`tbl_kib_d`.`id_kode_lokasi` AS `id_kode_lokasi`,'KIB D' AS `kib_name`,`tbl_kib_d`.`nomor_register` AS `nomor_register`,`tbl_kib_d`.`id_kode_barang` AS `id_kode_barang`,`tbl_kib_d`.`harga` AS `harga`,'' AS `merk_type`,`tbl_kib_d`.`asal_usul` AS `asal_usul`,`tbl_kib_d`.`tanggal_pembelian` AS `tanggal_pembelian`,`tbl_kib_d`.`dokumen_nomor` AS `nomor_nomor`,`tbl_kib_d`.`tanggal_perolehan` AS `tanggal_perolehan`,`tbl_kib_d`.`kondisi` AS `kondisi`,`tbl_kib_d`.`keterangan` AS `keterangan`,`tbl_kib_d`.`deskripsi` AS `deskripsi`,`tbl_kib_d`.`id_inventaris` AS `id_inventaris` FROM `tbl_kib_d` WHERE `tbl_kib_d`.`id_kib_d` IN (SELECT `tbl_penghapusan_barang`.`id_kib` FROM `tbl_penghapusan_barang`) UNION ALL SELECT '05' AS `kode_jenis`,`tbl_kib_e`.`id_kib_e` AS `id_kib_e`,`tbl_kib_e`.`id_pemilik` AS `id_pemilik`,`tbl_kib_e`.`id_kode_lokasi` AS `id_kode_lokasi`,'KIB E' AS `kib_name`,`tbl_kib_e`.`nomor_register` AS `nomor_register`,`tbl_kib_e`.`id_kode_barang` AS `id_kode_barang`,`tbl_kib_e`.`harga` AS `harga`,'' AS `merk_type`,`tbl_kib_e`.`asal_usul` AS `asal_usul`,`tbl_kib_e`.`tanggal_pembelian` AS `tanggal_pembelian`,'-' AS `nomor_nomor`,`tbl_kib_e`.`tanggal_perolehan` AS `tanggal_perolehan`,`tbl_kib_e`.`kondisi` AS `kondisi`,`tbl_kib_e`.`keterangan` AS `keterangan`,`tbl_kib_e`.`deskripsi` AS `deskripsi`,`tbl_kib_e`.`id_inventaris` AS `id_inventaris` FROM `tbl_kib_e` WHERE `tbl_kib_e`.`id_kib_e` IN (SELECT `tbl_penghapusan_barang`.`id_kib` FROM `tbl_penghapusan_barang`) UNION ALL SELECT '06' AS `kode_jenis`,`tbl_kib_f`.`id_kib_f` AS `id_kib_f`,`tbl_kib_f`.`id_pemilik` AS `id_pemilik`,`tbl_kib_f`.`id_kode_lokasi` AS `id_kode_lokasi`,'KIB F' AS `kib_name`,'000000' AS `nomor_register`,`tbl_kib_f`.`id_kode_barang` AS `id_kode_barang`,'' AS `harga`,'' AS `merk_type`,`tbl_kib_f`.`asal_usul` AS `asal_usul`,`tbl_kib_f`.`tanggal_pembelian` AS `tanggal_pembelian`,`tbl_kib_f`.`dokumen_nomor` AS `nomor_nomor`,`tbl_kib_f`.`tanggal_perolehan` AS `tanggal_perolehan`,`tbl_kib_f`.`bangunan` AS `bangunan`,`tbl_kib_f`.`keterangan` AS `keterangan`,`tbl_kib_f`.`deskripsi` AS `deskripsi`,`tbl_kib_f`.`id_inventaris` AS `id_inventaris` FROM `tbl_kib_f` WHERE `tbl_kib_f`.`id_kib_f` IN (SELECT `tbl_penghapusan_barang`.`id_kib` FROM `tbl_penghapusan_barang`) UNION ALL SELECT '5.03' AS `kode_jenis`,`tbl_kib_atb`.`id_kib_atb` AS `id_kib`,`tbl_kib_atb`.`id_pemilik` AS `id_pemilik`,`tbl_kib_atb`.`id_kode_lokasi` AS `id_kode_lokasi`,'KIB ATB' AS `kib_name`,'000000' AS `nomor_register`,`tbl_kib_atb`.`id_kode_barang` AS `id_kode_barang`,`tbl_kib_atb`.`harga` AS `harga`,'' AS `merk_type`,`tbl_kib_atb`.`asal_usul` AS `asal_usul`,'0000-00-00' AS `tanggal_pembelian`,'-' AS `nomor_nomor`,`tbl_kib_atb`.`tanggal_perolehan` AS `tanggal_perolehan`,'-' AS `kondisi`,`tbl_kib_atb`.`keterangan` AS `keterangan`,`tbl_kib_atb`.`deskripsi` AS `deskripsi`,`tbl_kib_atb`.`id_inventaris` AS `id_inventaris` FROM `tbl_kib_atb` WHERE `tbl_kib_atb`.`id_kib_atb` IN (SELECT `tbl_penghapusan_barang`.`id_kib` FROM `tbl_penghapusan_barang`)
      ) D on A.kode_jenis=D.kode_jenis and A.id_kib=D.id_kib
      join tbl_kode_barang E on D.id_kode_barang=E.id_kode_barang
      join tbl_kode_lokasi F on D.id_kode_lokasi=F.id_kode_lokasi
      join view_pengguna G on F.pengguna=G.pengguna
      where B.id_penghapusan_lampiran=" . $id_pengahapusan_lampiran . " AND A.status_diterima = '2';
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
      join tbl_penghapusan H on H.id_penghapusan =A.id_penghapusan
      join tbl_penghapusan_lampiran_detail as B on A.id_penghapusan_barang=B.id_penghapusan_barang
      left join
      	(select *
      	from tbl_penyusutan
      	where tanggal_penyusutan=first_day(curdate())
        ) as C on A.kode_jenis=C.kode_jenis and A.id_kib=C.id_kib
      join (
        SELECT '01' AS `kode_jenis`,`tbl_kib_a`.`id_kib_a` AS `id_kib`,`tbl_kib_a`.`id_pemilik` AS `id_pemilik`,`tbl_kib_a`.`id_kode_lokasi` AS `id_kode_lokasi`,'KIB A' AS `kib_name`,`tbl_kib_a`.`nomor_register` AS `nomor_register`,`tbl_kib_a`.`id_kode_barang` AS `id_kode_barang`,`tbl_kib_a`.`harga` AS `harga`,'' AS `merk_type`,`tbl_kib_a`.`asal_usul` AS `asal_usul`,`tbl_kib_a`.`tanggal_pembelian` AS `tanggal_pembelian`,`tbl_kib_a`.`sertifikat_nomor` AS `nomor_nomor`,`tbl_kib_a`.`tanggal_perolehan` AS `tanggal_perolehan`,`tbl_kib_a`.`kondisi` AS `kondisi`,`tbl_kib_a`.`keterangan` AS `keterangan`,`tbl_kib_a`.`deskripsi` AS `deskripsi`,`tbl_kib_a`.`id_inventaris` AS `id_inventaris` FROM `tbl_kib_a` WHERE `tbl_kib_a`.`id_kib_a` IN (SELECT `tbl_penghapusan_barang`.`id_kib` FROM `tbl_penghapusan_barang`) UNION ALL SELECT '02' AS `kode_jenis`,`tbl_kib_b`.`id_kib_b` AS `id_kib_b`,`tbl_kib_b`.`id_pemilik` AS `id_pemilik`,`tbl_kib_b`.`id_kode_lokasi` AS `id_kode_lokasi`,'KIB B' AS `kib_name`,`tbl_kib_b`.`nomor_register` AS `nomor_register`,`tbl_kib_b`.`id_kode_barang` AS `id_kode_barang`,`tbl_kib_b`.`harga` AS `harga`,`tbl_kib_b`.`merk_type` AS `merk_type`,`tbl_kib_b`.`asal_usul` AS `asal_usul`,`tbl_kib_b`.`tanggal_pembelian` AS `tanggal_pembelian`,CONCAT(`tbl_kib_b`.`nomor_pabrik`,', ',`tbl_kib_b`.`nomor_rangka`,', ',`tbl_kib_b`.`nomor_mesin`,', ',`tbl_kib_b`.`nomor_polisi`,', ',`tbl_kib_b`.`nomor_bpkb`) AS `nomor_nomor`,`tbl_kib_b`.`tanggal_perolehan` AS `tanggal_perolehan`,`tbl_kib_b`.`kondisi` AS `kondisi`,`tbl_kib_b`.`keterangan` AS `keterangan`,`tbl_kib_b`.`deskripsi` AS `deskripsi`,`tbl_kib_b`.`id_inventaris` AS `id_inventaris` FROM `tbl_kib_b` WHERE `tbl_kib_b`.`id_kib_b` IN (SELECT `tbl_penghapusan_barang`.`id_kib` FROM `tbl_penghapusan_barang`) UNION ALL SELECT '03' AS `kode_jenis`,`tbl_kib_c`.`id_kib_c` AS `id_kib_c`,`tbl_kib_c`.`id_pemilik` AS `id_pemilik`,`tbl_kib_c`.`id_kode_lokasi` AS `id_kode_lokasi`,'KIB C' AS `kib_name`,`tbl_kib_c`.`nomor_register` AS `nomor_register`,`tbl_kib_c`.`id_kode_barang` AS `id_kode_barang`,`tbl_kib_c`.`harga` AS `harga`,'' AS `merk_type`,`tbl_kib_c`.`asal_usul` AS `asal_usul`,`tbl_kib_c`.`tanggal_pembelian` AS `tanggal_pembelian`,`tbl_kib_c`.`gedung_nomor` AS `nomor_nomor`,`tbl_kib_c`.`tanggal_perolehan` AS `tanggal_perolehan`,`tbl_kib_c`.`kondisi_bangunan` AS `kondisi_bangunan`,`tbl_kib_c`.`keterangan` AS `keterangan`,`tbl_kib_c`.`deskripsi` AS `deskripsi`,`tbl_kib_c`.`id_inventaris` AS `id_inventaris` FROM `tbl_kib_c` WHERE `tbl_kib_c`.`id_kib_c` IN (SELECT `tbl_penghapusan_barang`.`id_kib` FROM `tbl_penghapusan_barang`) UNION ALL SELECT '04' AS `kode_jenis`,`tbl_kib_d`.`id_kib_d` AS `id_kib_d`,`tbl_kib_d`.`id_pemilik` AS `id_pemilik`,`tbl_kib_d`.`id_kode_lokasi` AS `id_kode_lokasi`,'KIB D' AS `kib_name`,`tbl_kib_d`.`nomor_register` AS `nomor_register`,`tbl_kib_d`.`id_kode_barang` AS `id_kode_barang`,`tbl_kib_d`.`harga` AS `harga`,'' AS `merk_type`,`tbl_kib_d`.`asal_usul` AS `asal_usul`,`tbl_kib_d`.`tanggal_pembelian` AS `tanggal_pembelian`,`tbl_kib_d`.`dokumen_nomor` AS `nomor_nomor`,`tbl_kib_d`.`tanggal_perolehan` AS `tanggal_perolehan`,`tbl_kib_d`.`kondisi` AS `kondisi`,`tbl_kib_d`.`keterangan` AS `keterangan`,`tbl_kib_d`.`deskripsi` AS `deskripsi`,`tbl_kib_d`.`id_inventaris` AS `id_inventaris` FROM `tbl_kib_d` WHERE `tbl_kib_d`.`id_kib_d` IN (SELECT `tbl_penghapusan_barang`.`id_kib` FROM `tbl_penghapusan_barang`) UNION ALL SELECT '05' AS `kode_jenis`,`tbl_kib_e`.`id_kib_e` AS `id_kib_e`,`tbl_kib_e`.`id_pemilik` AS `id_pemilik`,`tbl_kib_e`.`id_kode_lokasi` AS `id_kode_lokasi`,'KIB E' AS `kib_name`,`tbl_kib_e`.`nomor_register` AS `nomor_register`,`tbl_kib_e`.`id_kode_barang` AS `id_kode_barang`,`tbl_kib_e`.`harga` AS `harga`,'' AS `merk_type`,`tbl_kib_e`.`asal_usul` AS `asal_usul`,`tbl_kib_e`.`tanggal_pembelian` AS `tanggal_pembelian`,'-' AS `nomor_nomor`,`tbl_kib_e`.`tanggal_perolehan` AS `tanggal_perolehan`,`tbl_kib_e`.`kondisi` AS `kondisi`,`tbl_kib_e`.`keterangan` AS `keterangan`,`tbl_kib_e`.`deskripsi` AS `deskripsi`,`tbl_kib_e`.`id_inventaris` AS `id_inventaris` FROM `tbl_kib_e` WHERE `tbl_kib_e`.`id_kib_e` IN (SELECT `tbl_penghapusan_barang`.`id_kib` FROM `tbl_penghapusan_barang`) UNION ALL SELECT '06' AS `kode_jenis`,`tbl_kib_f`.`id_kib_f` AS `id_kib_f`,`tbl_kib_f`.`id_pemilik` AS `id_pemilik`,`tbl_kib_f`.`id_kode_lokasi` AS `id_kode_lokasi`,'KIB F' AS `kib_name`,'000000' AS `nomor_register`,`tbl_kib_f`.`id_kode_barang` AS `id_kode_barang`,'' AS `harga`,'' AS `merk_type`,`tbl_kib_f`.`asal_usul` AS `asal_usul`,`tbl_kib_f`.`tanggal_pembelian` AS `tanggal_pembelian`,`tbl_kib_f`.`dokumen_nomor` AS `nomor_nomor`,`tbl_kib_f`.`tanggal_perolehan` AS `tanggal_perolehan`,`tbl_kib_f`.`bangunan` AS `bangunan`,`tbl_kib_f`.`keterangan` AS `keterangan`,`tbl_kib_f`.`deskripsi` AS `deskripsi`,`tbl_kib_f`.`id_inventaris` AS `id_inventaris` FROM `tbl_kib_f` WHERE `tbl_kib_f`.`id_kib_f` IN (SELECT `tbl_penghapusan_barang`.`id_kib` FROM `tbl_penghapusan_barang`) UNION ALL SELECT '5.03' AS `kode_jenis`,`tbl_kib_atb`.`id_kib_atb` AS `id_kib`,`tbl_kib_atb`.`id_pemilik` AS `id_pemilik`,`tbl_kib_atb`.`id_kode_lokasi` AS `id_kode_lokasi`,'KIB ATB' AS `kib_name`,'000000' AS `nomor_register`,`tbl_kib_atb`.`id_kode_barang` AS `id_kode_barang`,`tbl_kib_atb`.`harga` AS `harga`,'' AS `merk_type`,`tbl_kib_atb`.`asal_usul` AS `asal_usul`,'0000-00-00' AS `tanggal_pembelian`,'-' AS `nomor_nomor`,`tbl_kib_atb`.`tanggal_perolehan` AS `tanggal_perolehan`,'-' AS `kondisi`,`tbl_kib_atb`.`keterangan` AS `keterangan`,`tbl_kib_atb`.`deskripsi` AS `deskripsi`,`tbl_kib_atb`.`id_inventaris` AS `id_inventaris` FROM `tbl_kib_atb` WHERE `tbl_kib_atb`.`id_kib_atb` IN (SELECT `tbl_penghapusan_barang`.`id_kib` FROM `tbl_penghapusan_barang`)
      ) as D on A.kode_jenis=D.kode_jenis and A.id_kib=D.id_kib
      join tbl_kode_lokasi F on H.id_kode_lokasi=F.id_kode_lokasi
      LEFT join view_pengguna G on F.pengguna=G.pengguna
      where B.id_penghapusan_lampiran=" . $id_pengahapusan_lampiran . " AND A.status_diterima = '2' group by F.id_kode_lokasi ORDER BY no ASC;")->result_array();
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
