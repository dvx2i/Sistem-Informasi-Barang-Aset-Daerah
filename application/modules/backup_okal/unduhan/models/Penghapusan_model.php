<?php

if (!defined('BASEPATH')) exit('No direct script access allowed');

class Penghapusan_model extends CI_Model
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
    $session = $this->session->userdata('session');
    $lokasi = explode('.', $session->kode_lokasi);
    $pengguna = $lokasi[3];
    $kuasa_pengguna =  $lokasi[4];
    $this->load->helper('penghapusan');
    $this->db->query('SET lc_time_names = "id_ID";');
    $this->datatables->select('a.id_penghapusan_lampiran, tanggal_lampiran, a.keterangan,file_sk');
    $this->datatables->from('tbl_penghapusan_lampiran a');
    $this->datatables->join('tbl_penghapusan_lampiran_detail b', 'a.id_penghapusan_lampiran = b.id_penghapusan_lampiran');
    $this->datatables->join('tbl_penghapusan_barang c', 'b.id_penghapusan_barang = c.id_penghapusan_barang');
    $this->datatables->join('tbl_penghapusan d', 'c.id_penghapusan = d.id_penghapusan');
    $this->datatables->join('tbl_kode_lokasi e', 'd.id_kode_lokasi = e.id_kode_lokasi');
    if ($session->id_role != '1') {
      $this->datatables->where('e.pengguna', $pengguna); //jika skpd
    }
    $this->datatables->group_by('a.id_penghapusan_lampiran'); //jika skpd
    // $this->datatables->group_by('nomor_pembuatan,tanggal_lampiran');
    $this->datatables->edit_column('tanggal_lampiran', '$1', 'tgl_indo(tanggal_lampiran)');
    // $this->datatables->add_column("action", anchor(base_url('unduhan/penghapusan/detail/$1'), "<button title='Detail' class='btn btn-info btn-sm'><span class='fa fa-search'></span></button>").' '.anchor(base_url('$2'), "<button title='SK' class='btn btn-success btn-sm'><span class='fa fa-file'></span></button>").' '.anchor(base_url('unduhan/penghapusan/laporan_detail/$1'), "<button title='SK' class='btn btn-success btn-sm'><span class='fa fa-download'></span> Rincian Lampiran</button>").' '.anchor(base_url('unduhan/penghapusan/laporan_rekap/$1'), "<button title='Rekap Lampiran' class='btn btn-success btn-sm'><span class='fa fa-download'></span> Rekap Lampiran</button>"), "id_penghapusan_lampiran,file_sk");

    $this->datatables->add_column("action", anchor(base_url('unduhan/penghapusan/laporan_detail/$1'), "<button title='SK' class='btn btn-success btn-sm'><span class='fa fa-download'></span> Rincian Lampiran</button>").' '.anchor(base_url('unduhan/penghapusan/laporan_rekap/$1'), "<button title='Rekap Lampiran' class='btn btn-success btn-sm'><span class='fa fa-download'></span> Rekap Lampiran</button>").' '.anchor(base_url('$2'), "<button title='SK' class='btn btn-success btn-sm'><span class='fa fa-file'></span> SK</button>"), "id_penghapusan_lampiran,file_sk");
    return
      $this->datatables->generate();
    // die($this->datatables->last_query());
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
