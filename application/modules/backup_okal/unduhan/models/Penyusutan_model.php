<?php

if (!defined('BASEPATH')) exit('No direct script access allowed');

class Penyusutan_model extends CI_Model
{

  public $tbl_kode_barang = 'tbl_kode_barang';
  public $tbl_kode_lokasi = 'tbl_kode_lokasi';
  public $tbl_master_intra_extra = 'tbl_master_intra_extra';

  public $tbl_usulan_barang = 'tbl_usulan_barang';
  public $id_usulan_barang = 'id_usulan_barang';

  public $tbl_penyusutan = 'tbl_penyusutan';

  function __construct()
  {
    parent::__construct();
  }
  /*
  function get_all($data)
  {
    $this->db->query('SET @row_number = 0;');
    $this->db->query(' SET @input_banyak = 0;');
    if ($data['id_pengguna'] != '')  $pengguna = $this->db->get_where('view_pengguna', array('id_pengguna' => $data['id_pengguna'],))->row()->pengguna;
    if ($data['id_kuasa_pengguna'] != '')  $kuasa_pengguna = $this->db->get_where('view_kuasa_pengguna', array('id_pengguna' => $data['id_pengguna'], 'id_kuasa_pengguna' => $data['id_kuasa_pengguna']))->row()->kuasa_pengguna;

    // $this->db->select('(@row_number:=@row_number + 1) AS no,A.*, B.instansi, C.value');
    $this->db->select('case when ifnull(A.input_banyak, 0)=0 or ifnull(A.input_banyak, 0) <>@input_banyak then (@row_number:=@row_number + 1) end AS no,
		@input_banyak:=ifnull(A.input_banyak, 0),
        `A`.`kode_jenis`,
        `A`.`jenis_name`,
        `A`.`id_kib`,
        `A`.`id_pemilik`,
        `A`.`id_kode_lokasi`,
        `A`.`kode_barang`,
        `A`.`nama_barang`,
        group_concat(A.nomor_register  order by A.nomor_register asc)nomor_register,
        `A`.`kode_lokasi`,
        `A`.`keterangan`,
        `A`.`asal_usul`,
        `A`.`tanggal_perolehan`,
        sum(A.harga)harga,
        `A`.`merk_type`,
        `A`.`sertifikat_nomor`,
        `A`.`bahan`,
        `A`.`ukuran_barang`,
        `A`.`satuan`,
        `A`.`keadaan_barang`,
        count(A.jumlah_barang)jumlah_barang,
        `A`.`input_banyak`,
        `B`.`instansi`,
        `C`.`value`,
         D.tanggal_pembelian,
         D.umur_bulan,
         D.umur_ekonomis,
         sum(D.nilai_perolehan) as nilai_perolehan,
         sum(D.nilai_penyusutan) as nilai_penyusutan,
         sum(D.akumulasi_penyusutan) as akumulasi_penyusutan,
         sum(D.nilai_buku) as nilai_buku');
    $this->db->from('view_laporan_inventaris A');
    // $this->db->where('validasi', '2');
    $this->db->where('A.id_pemilik', $data['id_pemilik']);
    $this->db->where_not_in('status_barang', array('penghapusan_diterima', 'terhapus'));
    if ($data['id_sub_kuasa_pengguna'] != '') {
      $this->db->where('A.id_kode_lokasi', $data['id_sub_kuasa_pengguna']);
    } else if ($data['id_kuasa_pengguna'] != '') {
      $this->db->where('B.pengguna', $pengguna);
      $this->db->where('B.kuasa_pengguna', $kuasa_pengguna);
    } else if ($data['id_pengguna'] != '') {
      $this->db->where('B.pengguna', $pengguna);
    }
    if ($data['start_date'] != '')
      $this->db->where('tanggal_perolehan >= ', $data['start_date']);
    if ($data['last_date'] != '')
      $this->db->where('tanggal_perolehan <= ', $data['last_date']);
    $this->db->join($this->tbl_kode_lokasi . ' B', 'B.id_kode_lokasi=A.id_kode_lokasi', 'left');

    $harga_nilaikontrak = 'harga';
    // if ($data['kode_jenis'] == '6') $harga_nilaikontrak = 'nilai_kontrak'; tidak dipakai karena pakai union

    if ($data['intra_ekstra'] == '01') $this->db->where($harga_nilaikontrak . ' >=', 'C.value', FALSE);  //intra
    else if ($data['intra_ekstra'] == '02') $this->db->where($harga_nilaikontrak . ' <', 'C.value', FALSE); //extra

    $this->db->join($this->tbl_master_intra_extra . ' C', 'C.kode_jenis=A.kode_jenis', 'left');
    $this->db->join($this->tbl_penyusutan . ' D', 'A.kode_jenis=D.kode_jenis and A.id_kib=D.id_kib', 'left');
    $date = $data['last_date'] != '' ? $data['last_date'] : date('Y-m-d');
    $this->db->where('month(D.tanggal_penyusutan)', date('m', strtotime($date)));
    $this->db->where('year(D.tanggal_penyusutan)', date('Y', strtotime($date)));

    $this->db->group_by('input_banyak, kode_jenis, kode_barang');
    $this->db->order_by('kode_jenis', 'asc');
    $this->db->order_by('id_kib', 'asc');
    return
      $this->db->get()->result_array();
    // die($this->db->last_query());
  }
*/

  function get_allnotuse($data)
  {
    // die(json_encode($data));
    if ($data['id_pengguna'] != '')  $pengguna = $this->db->get_where('view_pengguna', array('id_pengguna' => $data['id_pengguna'],))->row()->pengguna;
    if ($data['id_kuasa_pengguna'] != '')  $kuasa_pengguna = $this->db->get_where('view_kuasa_pengguna', array('id_pengguna' => $data['id_pengguna'], 'id_kuasa_pengguna' => $data['id_kuasa_pengguna']))->row()->kuasa_pengguna;

    $andwhere = "AND id_pemilik = '" . $data['id_pemilik'] . "' ";

    if ($data['id_kuasa_pengguna'] != '') {
      $andwhere .= "AND l.pengguna = '" . $pengguna . "' AND l.kuasa_pengguna = '" . $kuasa_pengguna . "' ";
    } else if ($data['id_pengguna'] != '') {
      $andwhere .= "AND l.pengguna = '" . $pengguna . "' ";
    }

    $andwhere .= "AND tanggal_perolehan <= '" . $data['last_date'] . "' ";

    $kib = "
  SELECT '02' AS `kode_jenis`,'KIB B' AS `jenis_name`,`b`.`id_kib_b` AS `id_kib`,`b`.`id_pemilik` AS `id_pemilik`,`b`.`id_kode_lokasi` AS `id_kode_lokasi`,`b`.`id_kode_barang` AS `id_kode_barang`,`b`.`kode_barang` AS `kode_barang`,`b`.`nama_barang` AS `nama_barang`,`b`.`nomor_register` AS `nomor_register`,`b`.`kode_lokasi` AS `kode_lokasi`,`b`.`keterangan` AS `keterangan`,`b`.`asal_usul` AS `asal_usul`,`b`.`tanggal_perolehan` AS `tanggal_perolehan`,`b`.`harga` AS `harga`,`b`.`status_barang` AS `status_barang`,`b`.`merk_type` AS `merk_type`,CONCAT_WS(', ',`b`.`nomor_pabrik`,`b`.`nomor_rangka`,`b`.`nomor_mesin`) AS `sertifikat_nomor`,`b`.`bahan` AS `bahan`,`b`.`ukuran_cc` AS `ukuran_barang`,`b`.`satuan` AS `satuan`,`b`.`kondisi` AS `keadaan_barang`,'' AS `jumlah_barang`,IFNULL(`b`.`input_banyak`,0) AS `input_banyak` FROM `tbl_kib_b` `b` LEFT JOIN tbl_kode_lokasi l ON b.id_kode_lokasi = l.id_kode_lokasi WHERE (`b`.`validasi` = 2) " . $andwhere . "
  UNION ALL SELECT '03' AS `kode_jenis`,'KIB C' AS `jenis_name`,`c`.`id_kib_c` AS `id_kib`,`c`.`id_pemilik` AS `id_pemilik`,`c`.`id_kode_lokasi` AS `id_kode_lokasi`,`c`.`id_kode_barang` AS `id_kode_barang`,`c`.`kode_barang` AS `kode_barang`,`c`.`nama_barang` AS `nama_barang`,`c`.`nomor_register` AS `nomor_register`,`c`.`kode_lokasi` AS `kode_lokasi`,`c`.`keterangan` AS `keterangan`,`c`.`asal_usul` AS `asal_usul`,`c`.`tanggal_perolehan` AS `tanggal_perolehan`,`c`.`harga` AS `harga`,`c`.`status_barang` AS `status_barang`,'' AS `merk_type`,`c`.`gedung_nomor` AS `sertifikat_nomor`,`c`.`bangunan_beton` AS `bangunan_beton`,`c`.`luas_lantai_m2` AS `ukuran_barang`,'M2' AS `satuan`,`c`.`kondisi_bangunan` AS `kondisi_bangunan`,'' AS `jumlah_barang`,0 AS `input_banyak` FROM `tbl_kib_c` `c` LEFT JOIN tbl_kode_lokasi l ON c.id_kode_lokasi = l.id_kode_lokasi  WHERE (`c`.`validasi` = 2)  " . $andwhere . "
  UNION ALL SELECT '04' AS `kode_jenis`,'KIB D' AS `jenis_name`,`d`.`id_kib_d` AS `id_kib`,`d`.`id_pemilik` AS `id_pemilik`,`d`.`id_kode_lokasi` AS `id_kode_lokasi`,`d`.`id_kode_barang` AS `id_kode_barang`,`d`.`kode_barang` AS `kode_barang`,`d`.`nama_barang` AS `nama_barang`,`d`.`nomor_register` AS `nomor_register`,`d`.`kode_lokasi` AS `kode_lokasi`,`d`.`keterangan` AS `keterangan`,`d`.`asal_usul` AS `asal_usul`,`d`.`tanggal_perolehan` AS `tanggal_perolehan`,`d`.`harga` AS `harga`,`d`.`status_barang` AS `status_barang`,'' AS `merk_type`,`d`.`dokumen_nomor` AS `sertifikat_nomor`,'' AS `bahan`,`d`.`luas_m2` AS `ukuran_barang`,'M2' AS `satuan`,`d`.`kondisi` AS `kondisi`,'' AS `jumlah_barang`,0 AS `input_banyak` FROM `tbl_kib_d` `d` LEFT JOIN tbl_kode_lokasi l ON d.id_kode_lokasi = l.id_kode_lokasi WHERE (`d`.`validasi` = 2)  " . $andwhere . "
  UNION ALL SELECT '05' AS `kode_jenis`,'KIB E' AS `jenis_name`,`e`.`id_kib_e` AS `id_kib`,`e`.`id_pemilik` AS `id_pemilik`,`e`.`id_kode_lokasi` AS `id_kode_lokasi`,`e`.`id_kode_barang` AS `id_kode_barang`,`e`.`kode_barang` AS `kode_barang`,`e`.`nama_barang` AS `nama_barang`,`e`.`nomor_register` AS `nomor_register`,`e`.`kode_lokasi` AS `kode_lokasi`,`e`.`keterangan` AS `keterangan`,`e`.`asal_usul` AS `asal_usul`,`e`.`tanggal_perolehan` AS `tanggal_perolehan`,`e`.`harga` AS `harga`,`e`.`status_barang` AS `status_barang`,'' AS `merk_type`,'' AS `sertifikat_nomor`,`e`.`kesenian_bahan` AS `kesenian_bahan`,`e`.`hewan_tumbuhan_ukuran` AS `ukuran_barang`,`e`.`satuan` AS `satuan`,`e`.`kondisi` AS `keadaan_barang`,`e`.`jumlah` AS `jumlah`,IFNULL(`e`.`input_banyak`,0) AS `input_banyak` FROM `tbl_kib_e` `e` LEFT JOIN tbl_kode_lokasi l ON e.id_kode_lokasi = l.id_kode_lokasi WHERE (`e`.`validasi` = 2)  " . $andwhere . "
  UNION ALL SELECT '5.03' AS `kode_jenis`,'KIB ATB' AS `jenis_name`,`atb`.`id_kib_atb` AS `id_kib`,`atb`.`id_pemilik` AS `id_pemilik`,`atb`.`id_kode_lokasi` AS `id_kode_lokasi`,`atb`.`id_kode_barang` AS `id_kode_barang`,`atb`.`kode_barang` AS `kode_barang`,`atb`.`nama_barang` AS `nama_barang`,`atb`.`nomor_register` AS `nomor_register`,`atb`.`kode_lokasi` AS `kode_lokasi`,`atb`.`keterangan` AS `keterangan`,`atb`.`asal_usul` AS `asal_usul`,`atb`.`tanggal_perolehan` AS `tanggal_perolehan`,`atb`.`harga` AS `harga`,`atb`.`status_barang` AS `status_barang`,'' AS `merk_type`,'' AS `sertifikat_nomor`,'' AS `kontruksi_beton`,'' AS `ukuran_barang`,'' AS `satuan`,'' AS `keadaan_barang`,'' AS `jumlah`,0 AS `input_banyak` FROM `tbl_kib_atb` `atb` LEFT JOIN tbl_kode_lokasi l ON atb.id_kode_lokasi = l.id_kode_lokasi WHERE (`atb`.`validasi` = 2) " . $andwhere . "
";
    // $this->db->select('(@row_number:=@row_number + 1) AS no,A.*, B.instansi, C.value');
    $this->db->select(' 
  `A`.`kode_jenis`,
`A`.`jenis_name`,
  `A`.`id_kib`,
  `A`.`id_pemilik`,
  `A`.`id_kode_lokasi`,
  `A`.`kode_barang`,
  `A`.`nama_barang`,
    A.nomor_register,
  `A`.`kode_lokasi`,
  `A`.`keterangan`,
  `A`.`asal_usul`,
  `A`.`tanggal_perolehan`,
  A.harga,
  `A`.`merk_type`,
  `A`.`sertifikat_nomor`,
  `A`.`bahan`,
  `A`.`ukuran_barang`,
  `A`.`satuan`,
  `A`.`keadaan_barang`,
  "1" as jumlah_barang,
  `A`.`input_banyak`,
  `B`.`instansi`,
  `C`.`value`,
   D.tanggal_pembelian,
   IFNULL(D.umur_bulan,0) AS umur_bulan,
   IFNULL(D.umur_ekonomis,0) AS umur_ekonomis,
   IFNULL(D.nilai_perolehan,0) AS nilai_perolehan,
   IFNULL(D.nilai_penyusutan,0) AS nilai_penyusutan,
   IFNULLD.akumulasi_penyusutan,0) AS akumulasi_penyusutan,
   IFNULL(D.nilai_buku,0) AS nilai_buku');
    //$this->db->from('view_laporan_inventaris A');

    $this->db->from($kib, ' as A');
    // $this->db->where('validasi', '2');
    $this->db->join($this->tbl_kode_lokasi . ' B', 'B.id_kode_lokasi=A.id_kode_lokasi', 'left');

    $harga_nilaikontrak = 'harga';
    // if ($data['kode_jenis'] == '6') $harga_nilaikontrak = 'nilai_kontrak'; tidak dipakai karena pakai union

    if ($data['intra_ekstra'] == '01') $this->db->where($harga_nilaikontrak . ' >=', 'C.value', FALSE);  //intra
    else if ($data['intra_ekstra'] == '02') $this->db->where($harga_nilaikontrak . ' <', 'C.value', FALSE); //extra

    $this->db->join($this->tbl_master_intra_extra . ' C', 'C.kode_jenis=A.kode_jenis', 'left');
    $this->db->join($this->tbl_penyusutan . ' D', 'A.kode_jenis=D.kode_jenis and A.id_kib=D.id_kib', 'left');
    $date = $data['last_date'] != '' ? $data['last_date'] : date('Y-m-d');
    $this->db->where('month(D.tanggal_penyusutan)', date('m', strtotime($date)));
    $this->db->where('year(D.tanggal_penyusutan)', date('Y', strtotime($date)));

    // $this->db->group_by('input_banyak, kode_jenis, kode_barang');
    $this->db->order_by('kode_jenis', 'asc');
    $this->db->order_by('id_kib', 'asc');
    return
      $this->db->get()->result_array();
    // die($this->db->last_query());
  }

  function get_all($data)
  {
    $mulai = $data['mulai'];
    if ($data['last_date'] == date('Y').'-07-01') $data['last_date'] = date('Y').'-06-30'; // filternya ke post 1 julli
    // die(json_encode($data));
    if ($data['id_pengguna'] != '')  $pengguna = $this->db->get_where('view_pengguna', array('id_pengguna' => $data['id_pengguna'],))->row()->pengguna;
    if ($data['id_kuasa_pengguna'] != '')  $kuasa_pengguna = $this->db->get_where('view_kuasa_pengguna', array('id_pengguna' => $data['id_pengguna'], 'id_kuasa_pengguna' => $data['id_kuasa_pengguna']))->row()->kuasa_pengguna;

    $date = $data['last_date'] != '' ? $data['last_date'] : date('Y-m-d');

    $andwhere = "AND id_pemilik = '" . $data['id_pemilik'] . "' ";

    if ($data['id_kuasa_pengguna'] != '') {
      $andwhere .= "AND l.pengguna = '" . $pengguna . "' AND l.kuasa_pengguna = '" . $kuasa_pengguna . "' ";
    } else if ($data['id_pengguna'] != '') {
      $andwhere .= "AND l.pengguna = '" . $pengguna . "' ";
    }

    $andwhere .= "AND tanggal_perolehan <= '" . $data['last_date'] . "' ";

    $harga_nilaikontrak = 'harga';
    // if ($data['kode_jenis'] == '6') $harga_nilaikontrak = 'nilai_kontrak'; tidak dipakai karena pakai union

    if ($data['intra_ekstra'] == '01') {
      $where_intraekstra = "AND harga >= C.value ";
    } else if ($data['intra_ekstra'] == '02') {
      $where_intraekstra = "AND harga < C.value ";
    } else {
      $where_intraekstra = "";
    }

    
    $prefix = $data['prefix'];

    if($data['filter'] == ''){
    $kib = "
    SELECT 
        '02' AS `kode_jenis`, 
        'KIB B' AS `jenis_name`, 
        `b`.`id_kib_b` AS `id_kib`, 
        `b`.`id_pemilik` AS `id_pemilik`, 
        `b`.`id_kode_lokasi` AS `id_kode_lokasi`, 
        `b`.`id_kode_barang` AS `id_kode_barang`, 
        `b`.`kode_barang` AS `kode_barang`, 
        `b`.`nama_barang` AS `nama_barang`, 
        `b`.`nomor_register` AS `nomor_register`, 
        `b`.`kode_lokasi` AS `kode_lokasi`, 
        `b`.`keterangan` AS `keterangan`, 
        `b`.`asal_usul` AS `asal_usul`, 
        `b`.`tanggal_perolehan` AS `tanggal_perolehan`, 
        `b`.`harga` AS `harga`, 
        `b`.`status_barang` AS `status_barang`, 
        `b`.`merk_type` AS `merk_type`, 
        CONCAT_WS(
          ', ', `b`.`nomor_pabrik`, `b`.`nomor_rangka`, 
          `b`.`nomor_mesin`
        ) AS `sertifikat_nomor`, 
        `b`.`bahan` AS `bahan`, 
        `b`.`ukuran_cc` AS `ukuran_barang`, 
        `b`.`satuan` AS `satuan`, 
        `b`.`kondisi` AS `keadaan_barang`, 
        '' AS `jumlah_barang`, 
        IFNULL(`b`.`input_banyak`, 0) AS `input_banyak` 
      FROM 
        `tbl_kib_b".$prefix."` `b` 
        LEFT JOIN tbl_kode_lokasi l ON b.id_kode_lokasi = l.id_kode_lokasi 
      WHERE 
        (`b`.`validasi` = 2 AND status_barang <> 'terhapus') 
        " . $andwhere . " 
      UNION ALL 
      SELECT 
        '03' AS `kode_jenis`, 
        'KIB C' AS `jenis_name`, 
        `c`.`id_kib_c` AS `id_kib`, 
        `c`.`id_pemilik` AS `id_pemilik`, 
        `c`.`id_kode_lokasi` AS `id_kode_lokasi`, 
        `c`.`id_kode_barang` AS `id_kode_barang`, 
        `c`.`kode_barang` AS `kode_barang`, 
        `c`.`nama_barang` AS `nama_barang`, 
        `c`.`nomor_register` AS `nomor_register`, 
        `c`.`kode_lokasi` AS `kode_lokasi`, 
        `c`.`keterangan` AS `keterangan`, 
        `c`.`asal_usul` AS `asal_usul`, 
        `c`.`tanggal_perolehan` AS `tanggal_perolehan`, 
        `c`.`harga` AS `harga`, 
        `c`.`status_barang` AS `status_barang`, 
        '' AS `merk_type`, 
        `c`.`gedung_nomor` AS `sertifikat_nomor`, 
        `c`.`bangunan_beton` AS `bangunan_beton`, 
        `c`.`luas_lantai_m2` AS `ukuran_barang`, 
        'M2' AS `satuan`, 
        `c`.`kondisi_bangunan` AS `kondisi_bangunan`, 
        '' AS `jumlah_barang`, 
        0 AS `input_banyak` 
      FROM 
        `tbl_kib_c".$prefix."` `c` 
        LEFT JOIN tbl_kode_lokasi l ON c.id_kode_lokasi = l.id_kode_lokasi 
      WHERE 
        (`c`.`validasi` = 2 AND status_barang <> 'terhapus') 
        " . $andwhere . "
      UNION ALL 
      SELECT 
        '04' AS `kode_jenis`, 
        'KIB D' AS `jenis_name`, 
        `d`.`id_kib_d` AS `id_kib`, 
        `d`.`id_pemilik` AS `id_pemilik`, 
        `d`.`id_kode_lokasi` AS `id_kode_lokasi`, 
        `d`.`id_kode_barang` AS `id_kode_barang`, 
        `d`.`kode_barang` AS `kode_barang`, 
        `d`.`nama_barang` AS `nama_barang`, 
        `d`.`nomor_register` AS `nomor_register`, 
        `d`.`kode_lokasi` AS `kode_lokasi`, 
        `d`.`keterangan` AS `keterangan`, 
        `d`.`asal_usul` AS `asal_usul`, 
        `d`.`tanggal_perolehan` AS `tanggal_perolehan`, 
        `d`.`harga` AS `harga`, 
        `d`.`status_barang` AS `status_barang`, 
        '' AS `merk_type`, 
        `d`.`dokumen_nomor` AS `sertifikat_nomor`, 
        '' AS `bahan`, 
        `d`.`luas_m2` AS `ukuran_barang`, 
        'M2' AS `satuan`, 
        `d`.`kondisi` AS `kondisi`, 
        '' AS `jumlah_barang`, 
        0 AS `input_banyak` 
      FROM 
        `tbl_kib_d".$prefix."` `d` 
        LEFT JOIN tbl_kode_lokasi l ON d.id_kode_lokasi = l.id_kode_lokasi 
      WHERE 
        (`d`.`validasi` = 2 AND status_barang <> 'terhapus') 
        " . $andwhere . "
      UNION ALL 
      SELECT 
        '05' AS `kode_jenis`, 
        'KIB E' AS `jenis_name`, 
        `e`.`id_kib_e` AS `id_kib`, 
        `e`.`id_pemilik` AS `id_pemilik`, 
        `e`.`id_kode_lokasi` AS `id_kode_lokasi`, 
        `e`.`id_kode_barang` AS `id_kode_barang`, 
        `e`.`kode_barang` AS `kode_barang`, 
        `e`.`nama_barang` AS `nama_barang`, 
        `e`.`nomor_register` AS `nomor_register`, 
        `e`.`kode_lokasi` AS `kode_lokasi`, 
        `e`.`keterangan` AS `keterangan`, 
        `e`.`asal_usul` AS `asal_usul`, 
        `e`.`tanggal_perolehan` AS `tanggal_perolehan`, 
        `e`.`harga` AS `harga`, 
        `e`.`status_barang` AS `status_barang`, 
        `e`.`judul_pencipta` AS `merk_type`, 
        '' AS `sertifikat_nomor`, 
        `e`.`kesenian_bahan` AS `kesenian_bahan`, 
        `e`.`hewan_tumbuhan_ukuran` AS `ukuran_barang`, 
        `e`.`satuan` AS `satuan`, 
        `e`.`kondisi` AS `keadaan_barang`, 
        `e`.`jumlah` AS `jumlah`, 
        IFNULL(`e`.`input_banyak`, 0) AS `input_banyak` 
      FROM 
        `tbl_kib_e".$prefix."` `e` 
        LEFT JOIN tbl_kode_lokasi l ON e.id_kode_lokasi = l.id_kode_lokasi 
      WHERE 
        (`e`.`validasi` = 2 AND status_barang <> 'terhapus') 
        " . $andwhere . "
      UNION ALL 
      SELECT 
        '5.03' AS `kode_jenis`, 
        'KIB ATB' AS `jenis_name`, 
        `atb`.`id_kib_atb` AS `id_kib`, 
        `atb`.`id_pemilik` AS `id_pemilik`, 
        `atb`.`id_kode_lokasi` AS `id_kode_lokasi`, 
        `atb`.`id_kode_barang` AS `id_kode_barang`, 
        `atb`.`kode_barang` AS `kode_barang`, 
        `atb`.`nama_barang` AS `nama_barang`, 
        `atb`.`nomor_register` AS `nomor_register`, 
        `atb`.`kode_lokasi` AS `kode_lokasi`, 
        `atb`.`keterangan` AS `keterangan`, 
        `atb`.`asal_usul` AS `asal_usul`, 
        `atb`.`tanggal_perolehan` AS `tanggal_perolehan`, 
        `atb`.`harga` AS `harga`, 
        `atb`.`status_barang` AS `status_barang`, 
        `atb`.`judul_kajian_nama_software` AS `merk_type`, 
        '' AS `sertifikat_nomor`, 
        '' AS `kontruksi_beton`, 
        '' AS `ukuran_barang`, 
        '' AS `satuan`, 
        '' AS `keadaan_barang`, 
        '' AS `jumlah`, 
        0 AS `input_banyak` 
      FROM 
        `tbl_kib_atb".$prefix."` `atb` 
        LEFT JOIN tbl_kode_lokasi l ON atb.id_kode_lokasi = l.id_kode_lokasi 
      WHERE 
        (`atb`.`validasi` = 2 AND status_barang <> 'terhapus') 
        " . $andwhere . "";
    }elseif($data['filter'] == 'kajian'){
        
        $kib = "SELECT 
        '5.03' AS `kode_jenis`, 
        'KIB ATB' AS `jenis_name`, 
        `atb`.`id_kib_atb` AS `id_kib`, 
        `atb`.`id_pemilik` AS `id_pemilik`, 
        `atb`.`id_kode_lokasi` AS `id_kode_lokasi`, 
        `atb`.`id_kode_barang` AS `id_kode_barang`, 
        `atb`.`kode_barang` AS `kode_barang`, 
        `atb`.`nama_barang` AS `nama_barang`, 
        `atb`.`nomor_register` AS `nomor_register`, 
        `atb`.`kode_lokasi` AS `kode_lokasi`, 
        `atb`.`keterangan` AS `keterangan`, 
        `atb`.`asal_usul` AS `asal_usul`, 
        `atb`.`tanggal_perolehan` AS `tanggal_perolehan`, 
        `atb`.`harga` AS `harga`, 
        `atb`.`status_barang` AS `status_barang`, 
        `atb`.`judul_kajian_nama_software` AS `merk_type`, 
        '' AS `sertifikat_nomor`, 
        '' AS `bahan`, 
        '' AS `ukuran_barang`, 
        '' AS `satuan`, 
        '' AS `keadaan_barang`, 
        '' AS `jumlah`, 
        0 AS `input_banyak` 
      FROM 
        `tbl_kib_atb".$prefix."` `atb` 
        LEFT JOIN tbl_kode_lokasi l ON atb.id_kode_lokasi = l.id_kode_lokasi 
      WHERE 
        (`atb`.`validasi` = 2 AND status_barang <> 'terhapus' AND id_kode_barang IN ('14741','14850') ) 
        " . $andwhere . "";
    }

    $sql = "SELECT 
    `A`.`kode_jenis`, 
    `A`.`jenis_name`, 
    `A`.`id_kib`, 
    `A`.`id_pemilik`, 
    `A`.`id_kode_lokasi`, 
    `A`.`kode_barang`, 
    `A`.`nama_barang`, 
    `A`.`nomor_register`, 
    `A`.`kode_lokasi`, 
    `A`.`keterangan`, 
    `A`.`asal_usul`, 
    `A`.`tanggal_perolehan`, 
    `A`.`harga`, 
    `A`.`merk_type`, 
    `A`.`sertifikat_nomor`, 
    `A`.`bahan`, 
    `A`.`ukuran_barang`, 
    `A`.`satuan`, 
    `A`.`keadaan_barang`, 
    '1' as `jumlah_barang`, 
    `A`.`input_banyak`, 
    `B`.`instansi`, 
    `C`.`value`, 
    `D`.`tanggal_pembelian`, 
   IFNULL(D.umur_bulan,0) AS umur_bulan,
   IFNULL(D.masa_manfaat,0) AS umur_ekonomis,
   IFNULL(D.sisa_umur_ekonomis,0) AS sisa_umur_ekonomis,
   IFNULL(D.nilai_perolehan,0) AS nilai_perolehan,
   IFNULL(D.nilai_penyusutan,0) AS nilai_penyusutan,
   IFNULL(D.akumulasi_penyusutan,0) AS akumulasi_penyusutan,
   IFNULL(D.nilai_buku,0) AS nilai_buku,
   IFNULL(E.akumulasi_penyusutan,0) AS akumulasi_penyusutan_lama
  FROM 
    (
      ".$kib."
    ) A
    JOIN `tbl_kode_lokasi` `B` ON `B`.`id_kode_lokasi` = `A`.`id_kode_lokasi` 
    JOIN `tbl_master_intra_extra` `C` ON `C`.`kode_jenis` = `A`.`kode_jenis` 
    LEFT JOIN `tbl_penyusutan` `D` ON `A`.`kode_jenis` = `D`.`kode_jenis` 
    and `A`.`id_kib` = `D`.`id_kib` AND month(D.tanggal_penyusutan) = '" . date('m', strtotime($date)) . "' 
    AND year(D.tanggal_penyusutan) = '" . date('Y', strtotime($date)) . "'
    LEFT JOIN `tbl_penyusutan_juni_2022` `E` ON `A`.`kode_jenis` = `E`.`kode_jenis`
    and `A`.`id_kib` = `E`.`id_kib` 
    LEFT JOIN tbl_mutasi_barang F ON A.id_kib = F.id_kib AND A.kode_jenis = F.kode_jenis
    WHERE F.tanggal_diterima  <= '2022-06-30' 
    
    " . $where_intraekstra . "
  ORDER BY 
    `A`.`id_kode_lokasi` ASC,
    `kode_barang` ASC, 
    `id_kib` ASC
    LIMIT 30000 OFFSET $mulai
  ";

  // die($sql); 
  
    $query = $this->db->query($sql);
    $res      = $query->result_array();
    return $res;
  }


  /*
  function laporan_penyusutan($data){
    $queri = null;
    $res = null;

    $query    = $this->db->query("call proc_laporan_penyusutan(".$data['session']->id_user.",".$data['id_pemilik'].",".$data['id_kode_lokasi'].",'".$data['start_date']."','".$data['last_date']."', '".$data['intra_ekstra']."')");
    $res      = $query->result_array();


    //add this two line
    $query->next_result();
    $query->free_result();
    //end of new code

    return $res;
    // return
    // $this->db->get()->result_array();
    // die($this->db->last_query());
  }
*/
}
