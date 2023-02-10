<?php

if (!defined('BASEPATH')) exit('No direct script access allowed');

class Rekap_mutasi_aset_model extends CI_Model
{

  public $tbl_kode_barang = 'tbl_kode_barang';
  public $tbl_kode_lokasi = 'tbl_kode_lokasi';
  public $tbl_master_intra_extra = 'tbl_master_intra_extra';

  public $tbl_usulan_barang = 'tbl_usulan_barang';
  public $id_usulan_barang = 'id_usulan_barang';




  function __construct()
  {
    parent::__construct();
  }




  // get all
  function get_all($data)
  {
    
    $data['last_bulan'] = date('m', strtotime($data['last_date']));
    $data['last_tahun'] = date('Y',strtotime($data['last_date']));

  $date = $data['last_date'];
  if(substr($date,9,2) == '01'){
    $date1 = str_replace('-', '/', $date);
    $data['last_date'] = date('Y-m-d',strtotime($date1 . "-1 days"));
  }

  if($data['kode_jenis'] != ''){ //jika filter kode jenis diisi
    $kode_jenis  = " AND kode_jenis = '".$data['kode_jenis']."' ";
    $kode_jenis2 = " AND SUBSTR(jenis, 3, 1) = '3' AND SUBSTR(jenis, 5, 2) = '".$data['kode_jenis']."' ";
}else{
  $kode_jenis = "";
  $kode_jenis2 = "";
}

$bulan_before = $data['bulan']-1; // bulan sebelum untuk saldo awal
$tahun_before = $data['tahun'];

if($data['bulan'] == '1'){ // jika bulan januari maka tahun - 1;
  $bulan_before = '12';
  $tahun_before = $data['tahun']-1;
}

if ($data['id_pengguna'] != '') { // jika unit lokasi dipilih 
$group_by1 =  " AND b.pengguna = '".$data['pengguna']."' GROUP BY b.id_kode_lokasi "; // group by 1 = sub query
$group_by2 =  " AND id_pengguna = TRIM(LEADING '0' FROM '".$data['pengguna']."') GROUP BY id_kode_lokasi "; // left group by 2 = join
} else { 
  $id_kode_lokasi = $data['id_pengguna'];
  $group_by1 =  " GROUP BY b.pengguna "; // group by 1 = sub query
  $group_by2 =  " GROUP BY id_pengguna "; // left group by 2 = join
}

$sql = "SELECT d.`instansi`,d.`pengguna`,sq.id_sumber_dana,sq.id_rekening,IFNULL(sq.entri_lain_2,0) entri_lain_2,IFNULL(sq.entri_lain_1,0) entri_lain_1,IFNULL(sq.entri_hibah_1,0) entri_hibah_1,IFNULL(sq.entri_hibah_2,0) entri_hibah_2,IFNULL(sq.entri_hibah_3,0) entri_hibah_3,IFNULL(sq.entri_hibah_4,0) entri_hibah_4,IFNULL(sq.entri_hibah_5,0) entri_hibah_5,IFNULL(sq.entri_tt,0) entri_tt,COALESCE(sq.entri_bm_a,0) - COALESCE(sq.koreksi_bm_a) entri_bm_a,COALESCE(sq.entri_bm_b,0) - COALESCE(sq.koreksi_bm_b) entri_bm_b,COALESCE(sq.entri_bm_c,0) - COALESCE(sq.koreksi_bm_c) entri_bm_c,COALESCE(sq.entri_bm_d,0) - COALESCE(sq.koreksi_bm_d) entri_bm_d,COALESCE(sq.entri_bm_e,0) - COALESCE(sq.koreksi_bm_e) entri_bm_e,IFNULL(sq.entri_barjas,0) entri_barjas,IFNULL(sq.kapitalisasi_lain_3,0) kapitalisasi_lain_3,IFNULL(sq.kapitalisasi_lain_2,0) kapitalisasi_lain_2,IFNULL(sq.kapitalisasi_hibah_5,0) kapitalisasi_hibah_5,IFNULL(sq.kapitalisasi_hibah_4,0) kapitalisasi_hibah_4,IFNULL(sq.kapitalisasi_hibah_3,0) kapitalisasi_hibah_3,IFNULL(sq.kapitalisasi_hibah_2,0) kapitalisasi_hibah_2,IFNULL(sq.kapitalisasi_hibah_1,0) kapitalisasi_hibah_1,IFNULL(sq.kapitalisasi_tt_harga,0) kapitalisasi_tt_harga,IFNULL(sq.kapitalisasi_barjas_harga,0) kapitalisasi_barjas_harga,IFNULL(sq.mutasi_in,0) mutasi_in,IFNULL(sq.reklas_plus,0) reklas_plus,IFNULL(sq.penghapusan,0) penghapusan,IFNULL(sq.urai_catat,0) urai_catat,IFNULL(sq.lebih_catat,0) lebih_catat,IFNULL(sq.koreksi_kurang_harga,0) koreksi_kurang_harga,IFNULL(sq.mutasi_out,0) mutasi_out,IFNULL(sq.reklas_min,0) reklas_min,IFNULL(c.saldo_awal,0) saldo_awal,
IFNULL(sq.kapitalisasi_bm_a,0) kapitalisasi_bm_a,IFNULL(sq.kapitalisasi_bm_b,0) kapitalisasi_bm_b,IFNULL(sq.kapitalisasi_bm_c,0) kapitalisasi_bm_c,IFNULL(sq.kapitalisasi_bm_d,0) kapitalisasi_bm_d,IFNULL(sq.kapitalisasi_bm_e,0) kapitalisasi_bm_e,IFNULL(s.saldo_akhir,0) saldo_akhir";
// -- ((COALESCE(c.saldo_awal,0) + COALESCE(sq.entri,0) + COALESCE(sq.kapitalisasi,0) + COALESCE(sq.mutasi_in,0) + COALESCE(sq.reklas_plus,0)) - (COALESCE(sq.penghapusan_all,0) + COALESCE(sq.koreksi_kurang,0) + COALESCE(sq.mutasi_out,0) + COALESCE(sq.reklas_min,0))) AS saldo_akhir ";

if ($data['id_pengguna'] != '') { // jika unit lokasi dipilih 
  $sql .= " FROM view_kuasa_pengguna d ";
  } else { 
    $sql .= " FROM view_pengguna d ";
  }
$sql .= " LEFT JOIN  ( 
SELECT b.id_kode_lokasi,b.instansi,b.`pengguna`,a.id_sumber_dana,a.id_rekening,
CASE WHEN '".$data['intra_ekstra']."' = '00' THEN SUM(CASE WHEN a.id_rekening = '1753' AND status_histori = 'entri' THEN harga ELSE 0 END)
WHEN '".$data['intra_ekstra']."' = '01' THEN SUM(CASE WHEN a.id_rekening = '1753' AND status_histori = 'entri' AND harga >= d.value THEN harga ELSE 0 END)
WHEN '".$data['intra_ekstra']."' = '02' THEN SUM(CASE WHEN a.id_rekening = '1753' AND status_histori = 'entri' AND harga < d.value THEN harga ELSE 0 END) 
END AS entri_lain_1,
CASE WHEN '".$data['intra_ekstra']."' = '00' THEN SUM(CASE WHEN a.id_rekening = '1754' AND status_histori = 'entri' THEN harga ELSE 0 END)
WHEN '".$data['intra_ekstra']."' = '01' THEN SUM(CASE WHEN a.id_rekening = '1754' AND status_histori = 'entri' AND harga >= d.value THEN harga ELSE 0 END)
WHEN '".$data['intra_ekstra']."' = '02' THEN SUM(CASE WHEN a.id_rekening = '1754' AND status_histori = 'entri' AND harga < d.value THEN harga ELSE 0 END) 
END AS entri_lain_2,
CASE WHEN '".$data['intra_ekstra']."' = '00' THEN SUM(CASE WHEN a.id_rekening = '1756' AND status_histori = 'entri' THEN harga ELSE 0 END)
WHEN '".$data['intra_ekstra']."' = '01' THEN SUM(CASE WHEN a.id_rekening = '1756' AND status_histori = 'entri' AND harga >= d.value THEN harga ELSE 0 END)
WHEN '".$data['intra_ekstra']."' = '02' THEN SUM(CASE WHEN a.id_rekening = '1756' AND status_histori = 'entri' AND harga < d.value THEN harga ELSE 0 END) 
END AS entri_hibah_1,
CASE WHEN '".$data['intra_ekstra']."' = '00' THEN SUM(CASE WHEN a.id_rekening = '1757' AND status_histori = 'entri' THEN harga ELSE 0 END)
WHEN '".$data['intra_ekstra']."' = '01' THEN SUM(CASE WHEN a.id_rekening = '1757' AND status_histori = 'entri' AND harga >= d.value THEN harga ELSE 0 END)
WHEN '".$data['intra_ekstra']."' = '02' THEN SUM(CASE WHEN a.id_rekening = '1757' AND status_histori = 'entri' AND harga < d.value THEN harga ELSE 0 END) 
END AS entri_hibah_2,
CASE WHEN '".$data['intra_ekstra']."' = '00' THEN SUM(CASE WHEN a.id_rekening = '1758' AND status_histori = 'entri' THEN harga ELSE 0 END)
WHEN '".$data['intra_ekstra']."' = '01' THEN SUM(CASE WHEN a.id_rekening = '1758' AND status_histori = 'entri' AND harga >= d.value THEN harga ELSE 0 END)
WHEN '".$data['intra_ekstra']."' = '02' THEN SUM(CASE WHEN a.id_rekening = '1758' AND status_histori = 'entri' AND harga < d.value THEN harga ELSE 0 END) 
END AS entri_hibah_3,
CASE WHEN '".$data['intra_ekstra']."' = '00' THEN SUM(CASE WHEN a.id_rekening = '1759' AND status_histori = 'entri' THEN harga ELSE 0 END)
WHEN '".$data['intra_ekstra']."' = '01' THEN SUM(CASE WHEN a.id_rekening = '1759' AND status_histori = 'entri' AND harga >= d.value THEN harga ELSE 0 END)
WHEN '".$data['intra_ekstra']."' = '02' THEN SUM(CASE WHEN a.id_rekening = '1759' AND status_histori = 'entri' AND harga < d.value THEN harga ELSE 0 END) 
END AS entri_hibah_4,
CASE WHEN '".$data['intra_ekstra']."' = '00' THEN SUM(CASE WHEN a.id_rekening = '1760' AND status_histori = 'entri' THEN harga ELSE 0 END)
WHEN '".$data['intra_ekstra']."' = '01' THEN SUM(CASE WHEN a.id_rekening = '1760' AND status_histori = 'entri' AND harga >= d.value THEN harga ELSE 0 END)
WHEN '".$data['intra_ekstra']."' = '02' THEN SUM(CASE WHEN a.id_rekening = '1760' AND status_histori = 'entri' AND harga < d.value THEN harga ELSE 0 END) 
END AS entri_hibah_5,
CASE WHEN '".$data['intra_ekstra']."' = '00' THEN SUM(CASE WHEN a.id_sumber_dana = '3' AND status_histori = 'entri' THEN harga ELSE 0 END)
WHEN '".$data['intra_ekstra']."' = '01' THEN SUM(CASE WHEN a.id_sumber_dana = '3' AND status_histori = 'entri' AND harga >= d.value THEN harga ELSE 0 END)
WHEN '".$data['intra_ekstra']."' = '02' THEN SUM(CASE WHEN a.id_sumber_dana = '3' AND status_histori = 'entri' AND harga < d.value THEN harga ELSE 0 END) 
END AS entri_tt,
CASE WHEN '".$data['intra_ekstra']."' = '00' THEN SUM(CASE WHEN r.id_sumber_dana = '2' AND SUBSTR(r.kode_rekening, 5, 2) = '01' AND status_histori = 'entri' THEN harga ELSE 0 END)
WHEN '".$data['intra_ekstra']."' = '01' THEN SUM(CASE WHEN r.id_sumber_dana = '2' AND SUBSTR(r.kode_rekening, 5, 2) = '01' AND status_histori = 'entri' AND harga >= d.value THEN harga ELSE 0 END)
WHEN '".$data['intra_ekstra']."' = '01' THEN SUM(CASE WHEN r.id_sumber_dana = '2' AND SUBSTR(r.kode_rekening, 5, 2) = '01' AND status_histori = 'entri' AND harga < d.value THEN harga ELSE 0 END)  
END AS entri_bm_a,
CASE WHEN '".$data['intra_ekstra']."' = '00' THEN SUM(CASE WHEN r.id_sumber_dana = '2' AND SUBSTR(r.kode_rekening, 5, 2) = '02' AND status_histori = 'entri' THEN harga ELSE 0 END)
WHEN '".$data['intra_ekstra']."' = '01' THEN SUM(CASE WHEN r.id_sumber_dana = '2' AND SUBSTR(r.kode_rekening, 5, 2) = '02' AND status_histori = 'entri' AND harga >= d.value THEN harga ELSE 0 END)
WHEN '".$data['intra_ekstra']."' = '01' THEN SUM(CASE WHEN r.id_sumber_dana = '2' AND SUBSTR(r.kode_rekening, 5, 2) = '02' AND status_histori = 'entri' AND harga < d.value THEN harga ELSE 0 END)  
END AS entri_bm_b,
CASE WHEN '".$data['intra_ekstra']."' = '00' THEN SUM(CASE WHEN r.id_sumber_dana = '2' AND SUBSTR(r.kode_rekening, 5, 2) = '03' AND status_histori = 'entri' THEN harga ELSE 0 END)
WHEN '".$data['intra_ekstra']."' = '01' THEN SUM(CASE WHEN r.id_sumber_dana = '2' AND SUBSTR(r.kode_rekening, 5, 2) = '03' AND status_histori = 'entri' AND harga >= d.value THEN harga ELSE 0 END)
WHEN '".$data['intra_ekstra']."' = '01' THEN SUM(CASE WHEN r.id_sumber_dana = '2' AND SUBSTR(r.kode_rekening, 5, 2) = '03' AND status_histori = 'entri' AND harga < d.value THEN harga ELSE 0 END)  
END AS entri_bm_c,
CASE WHEN '".$data['intra_ekstra']."' = '00' THEN SUM(CASE WHEN r.id_sumber_dana = '2' AND SUBSTR(r.kode_rekening, 5, 2) = '04' AND status_histori = 'entri' THEN harga ELSE 0 END)
WHEN '".$data['intra_ekstra']."' = '01' THEN SUM(CASE WHEN r.id_sumber_dana = '2' AND SUBSTR(r.kode_rekening, 5, 2) = '04' AND status_histori = 'entri' AND harga >= d.value THEN harga ELSE 0 END)
WHEN '".$data['intra_ekstra']."' = '01' THEN SUM(CASE WHEN r.id_sumber_dana = '2' AND SUBSTR(r.kode_rekening, 5, 2) = '04' AND status_histori = 'entri' AND harga < d.value THEN harga ELSE 0 END)  
END AS entri_bm_d,
CASE WHEN '".$data['intra_ekstra']."' = '00' THEN SUM(CASE WHEN r.id_sumber_dana = '2' AND SUBSTR(r.kode_rekening, 5, 2) = '05' AND status_histori = 'entri' THEN harga ELSE 0 END)
WHEN '".$data['intra_ekstra']."' = '01' THEN SUM(CASE WHEN r.id_sumber_dana = '2' AND SUBSTR(r.kode_rekening, 5, 2) = '05' AND status_histori = 'entri' AND harga >= d.value THEN harga ELSE 0 END)
WHEN '".$data['intra_ekstra']."' = '01' THEN SUM(CASE WHEN r.id_sumber_dana = '2' AND SUBSTR(r.kode_rekening, 5, 2) = '05' AND status_histori = 'entri' AND harga < d.value THEN harga ELSE 0 END)  
END AS entri_bm_e,
CASE WHEN '".$data['intra_ekstra']."' = '00' THEN SUM(CASE WHEN a.id_sumber_dana = '1' AND status_histori = 'entri' THEN harga ELSE 0 END)
WHEN '".$data['intra_ekstra']."' = '01' THEN SUM(CASE WHEN a.id_sumber_dana = '1' AND status_histori = 'entri' AND harga >= d.value THEN harga ELSE 0 END)
WHEN '".$data['intra_ekstra']."' = '02' THEN SUM(CASE WHEN a.id_sumber_dana = '1' AND status_histori = 'entri' AND harga < d.value THEN harga ELSE 0 END) 
END AS entri_barjas,
CASE WHEN '".$data['intra_ekstra']."' = '00' THEN SUM(CASE WHEN a.id_rekening = '1754' AND status_histori IN ('koreksi_tambah','kapitalisasi') THEN harga ELSE 0 END) 
WHEN '".$data['intra_ekstra']."' = '01' THEN SUM(CASE WHEN a.id_rekening = '1754' AND status_histori IN ('koreksi_tambah','kapitalisasi') AND harga >= d.value THEN harga ELSE 0 END)
WHEN '".$data['intra_ekstra']."' = '02' THEN SUM(CASE WHEN a.id_rekening = '1754' AND status_histori IN ('kapitalisasi','koreksi_tambah') AND harga < d.value THEN harga ELSE 0 END)
END AS kapitalisasi_lain_2,
CASE WHEN '".$data['intra_ekstra']."' = '00' THEN SUM(CASE WHEN a.id_rekening = '1755' AND status_histori IN ('koreksi_tambah','kapitalisasi') THEN harga ELSE 0 END) 
WHEN '".$data['intra_ekstra']."' = '01' THEN SUM(CASE WHEN a.id_rekening = '1755' AND status_histori IN ('koreksi_tambah','kapitalisasi') AND harga >= d.value THEN harga ELSE 0 END)
WHEN '".$data['intra_ekstra']."' = '02' THEN SUM(CASE WHEN a.id_rekening = '1755' AND status_histori IN ('kapitalisasi','koreksi_tambah') AND harga < d.value THEN harga ELSE 0 END)
END AS kapitalisasi_lain_3,
CASE WHEN '".$data['intra_ekstra']."' = '00' THEN SUM(CASE WHEN a.id_rekening = '1756' AND status_histori IN ('koreksi_tambah','kapitalisasi') THEN harga ELSE 0 END) 
WHEN '".$data['intra_ekstra']."' = '01' THEN SUM(CASE WHEN a.id_rekening = '1756' AND status_histori IN ('koreksi_tambah','kapitalisasi') AND harga >= d.value THEN harga ELSE 0 END)
WHEN '".$data['intra_ekstra']."' = '02' THEN SUM(CASE WHEN a.id_rekening = '1756' AND status_histori IN ('kapitalisasi','koreksi_tambah') AND harga < d.value THEN harga ELSE 0 END)
END AS kapitalisasi_hibah_1,
CASE WHEN '".$data['intra_ekstra']."' = '00' THEN SUM(CASE WHEN a.id_rekening = '1757' AND status_histori IN ('koreksi_tambah','kapitalisasi') THEN harga ELSE 0 END) 
WHEN '".$data['intra_ekstra']."' = '01' THEN SUM(CASE WHEN a.id_rekening = '1757' AND status_histori IN ('koreksi_tambah','kapitalisasi') AND harga >= d.value THEN harga ELSE 0 END)
WHEN '".$data['intra_ekstra']."' = '02' THEN SUM(CASE WHEN a.id_rekening = '1757' AND status_histori IN ('kapitalisasi','koreksi_tambah') AND harga < d.value THEN harga ELSE 0 END)
END AS kapitalisasi_hibah_2,
CASE WHEN '".$data['intra_ekstra']."' = '00' THEN SUM(CASE WHEN a.id_rekening = '1758' AND status_histori IN ('koreksi_tambah','kapitalisasi') THEN harga ELSE 0 END) 
WHEN '".$data['intra_ekstra']."' = '01' THEN SUM(CASE WHEN a.id_rekening = '1758' AND status_histori IN ('koreksi_tambah','kapitalisasi') AND harga >= d.value THEN harga ELSE 0 END)
WHEN '".$data['intra_ekstra']."' = '02' THEN SUM(CASE WHEN a.id_rekening = '1758' AND status_histori IN ('kapitalisasi','koreksi_tambah') AND harga < d.value THEN harga ELSE 0 END)
END AS kapitalisasi_hibah_3,
CASE WHEN '".$data['intra_ekstra']."' = '00' THEN SUM(CASE WHEN a.id_rekening = '1759' AND status_histori IN ('koreksi_tambah','kapitalisasi') THEN harga ELSE 0 END) 
WHEN '".$data['intra_ekstra']."' = '01' THEN SUM(CASE WHEN a.id_rekening = '1759' AND status_histori IN ('koreksi_tambah','kapitalisasi') AND harga >= d.value THEN harga ELSE 0 END)
WHEN '".$data['intra_ekstra']."' = '02' THEN SUM(CASE WHEN a.id_rekening = '1759' AND status_histori IN ('kapitalisasi','koreksi_tambah') AND harga < d.value THEN harga ELSE 0 END)
END AS kapitalisasi_hibah_4,
CASE WHEN '".$data['intra_ekstra']."' = '00' THEN SUM(CASE WHEN a.id_rekening = '1760' AND status_histori IN ('koreksi_tambah','kapitalisasi') THEN harga ELSE 0 END) 
WHEN '".$data['intra_ekstra']."' = '01' THEN SUM(CASE WHEN a.id_rekening = '1760' AND status_histori IN ('koreksi_tambah','kapitalisasi') AND harga >= d.value THEN harga ELSE 0 END)
WHEN '".$data['intra_ekstra']."' = '02' THEN SUM(CASE WHEN a.id_rekening = '1760' AND status_histori IN ('kapitalisasi','koreksi_tambah') AND harga < d.value THEN harga ELSE 0 END)
END AS kapitalisasi_hibah_5,
CASE WHEN '".$data['intra_ekstra']."' = '00' THEN SUM(CASE WHEN a.id_sumber_dana  = '3' AND status_histori IN ('koreksi_tambah','kapitalisasi') THEN harga ELSE 0 END) 
WHEN '".$data['intra_ekstra']."' = '01' THEN SUM(CASE WHEN a.id_sumber_dana  = '3' AND status_histori IN ('koreksi_tambah','kapitalisasi') AND harga >= d.value THEN harga ELSE 0 END)
WHEN '".$data['intra_ekstra']."' = '02' THEN SUM(CASE WHEN a.id_sumber_dana  = '3' AND status_histori IN ('kapitalisasi','koreksi_tambah') AND harga < d.value THEN harga ELSE 0 END)
END AS kapitalisasi_tt_harga,
CASE WHEN '".$data['intra_ekstra']."' = '00' THEN SUM(CASE WHEN a.id_sumber_dana  = '2' AND SUBSTR(r.kode_rekening, 5, 2) = '01' AND status_histori IN ('koreksi_tambah','kapitalisasi') THEN harga ELSE 0 END) 
WHEN '".$data['intra_ekstra']."' = '01' THEN SUM(CASE WHEN a.id_sumber_dana  = '2' AND SUBSTR(r.kode_rekening, 5, 2) = '01' AND status_histori IN ('koreksi_tambah','kapitalisasi') AND harga >= d.value THEN harga ELSE 0 END)
WHEN '".$data['intra_ekstra']."' = '02' THEN SUM(CASE WHEN a.id_sumber_dana  = '2' AND SUBSTR(r.kode_rekening, 5, 2) = '01' AND status_histori IN ('kapitalisasi','koreksi_tambah') AND harga < d.value THEN harga ELSE 0 END)
END AS kapitalisasi_bm_a,
CASE WHEN '".$data['intra_ekstra']."' = '00' THEN SUM(CASE WHEN a.id_sumber_dana  = '2' AND SUBSTR(r.kode_rekening, 5, 2) = '02' AND status_histori IN ('koreksi_tambah','kapitalisasi') THEN harga ELSE 0 END) 
WHEN '".$data['intra_ekstra']."' = '01' THEN SUM(CASE WHEN a.id_sumber_dana  = '2' AND SUBSTR(r.kode_rekening, 5, 2) = '02' AND status_histori IN ('koreksi_tambah','kapitalisasi') AND harga >= d.value THEN harga ELSE 0 END)
WHEN '".$data['intra_ekstra']."' = '02' THEN SUM(CASE WHEN a.id_sumber_dana  = '2' AND SUBSTR(r.kode_rekening, 5, 2) = '02' AND status_histori IN ('kapitalisasi','koreksi_tambah') AND harga < d.value THEN harga ELSE 0 END)
END AS kapitalisasi_bm_b,
CASE WHEN '".$data['intra_ekstra']."' = '00' THEN SUM(CASE WHEN a.id_sumber_dana  = '2' AND SUBSTR(r.kode_rekening, 5, 2) = '03' AND status_histori IN ('koreksi_tambah','kapitalisasi') THEN harga ELSE 0 END) 
WHEN '".$data['intra_ekstra']."' = '01' THEN SUM(CASE WHEN a.id_sumber_dana  = '2' AND SUBSTR(r.kode_rekening, 5, 2) = '03' AND status_histori IN ('koreksi_tambah','kapitalisasi') AND harga >= d.value THEN harga ELSE 0 END)
WHEN '".$data['intra_ekstra']."' = '02' THEN SUM(CASE WHEN a.id_sumber_dana  = '2' AND SUBSTR(r.kode_rekening, 5, 2) = '03' AND status_histori IN ('kapitalisasi','koreksi_tambah') AND harga < d.value THEN harga ELSE 0 END)
END AS kapitalisasi_bm_c,
CASE WHEN '".$data['intra_ekstra']."' = '00' THEN SUM(CASE WHEN a.id_sumber_dana  = '2' AND SUBSTR(r.kode_rekening, 5, 2) = '04' AND status_histori IN ('koreksi_tambah','kapitalisasi') THEN harga ELSE 0 END) 
WHEN '".$data['intra_ekstra']."' = '01' THEN SUM(CASE WHEN a.id_sumber_dana  = '2' AND SUBSTR(r.kode_rekening, 5, 2) = '04' AND status_histori IN ('koreksi_tambah','kapitalisasi') AND harga >= d.value THEN harga ELSE 0 END)
WHEN '".$data['intra_ekstra']."' = '02' THEN SUM(CASE WHEN a.id_sumber_dana  = '2' AND SUBSTR(r.kode_rekening, 5, 2) = '04' AND status_histori IN ('kapitalisasi','koreksi_tambah') AND harga < d.value THEN harga ELSE 0 END)
END AS kapitalisasi_bm_d,
CASE WHEN '".$data['intra_ekstra']."' = '00' THEN SUM(CASE WHEN a.id_sumber_dana  = '2' AND SUBSTR(r.kode_rekening, 5, 2) = '05' AND status_histori IN ('koreksi_tambah','kapitalisasi') THEN harga ELSE 0 END) 
WHEN '".$data['intra_ekstra']."' = '01' THEN SUM(CASE WHEN a.id_sumber_dana  = '2' AND SUBSTR(r.kode_rekening, 5, 2) = '05' AND status_histori IN ('koreksi_tambah','kapitalisasi') AND harga >= d.value THEN harga ELSE 0 END)
WHEN '".$data['intra_ekstra']."' = '02' THEN SUM(CASE WHEN a.id_sumber_dana  = '2' AND SUBSTR(r.kode_rekening, 5, 2) = '05' AND status_histori IN ('kapitalisasi','koreksi_tambah') AND harga < d.value THEN harga ELSE 0 END)
END AS kapitalisasi_bm_e,
CASE WHEN '".$data['intra_ekstra']."' = '00' THEN SUM(CASE WHEN a.id_sumber_dana  = '1' AND status_histori IN ('koreksi_tambah','kapitalisasi') THEN harga ELSE 0 END) 
WHEN '".$data['intra_ekstra']."' = '01' THEN SUM(CASE WHEN a.id_sumber_dana  = '1' AND status_histori IN ('koreksi_tambah','kapitalisasi') AND harga >= d.value THEN harga ELSE 0 END)
WHEN '".$data['intra_ekstra']."' = '02' THEN SUM(CASE WHEN a.id_sumber_dana  = '1' AND status_histori IN ('kapitalisasi','koreksi_tambah') AND harga < d.value THEN harga ELSE 0 END)
END AS kapitalisasi_barjas_harga,
CASE WHEN '".$data['intra_ekstra']."' = '00' THEN SUM(CASE WHEN status_histori = 'mutasi_in' AND a.keterangan = 'proc_mutasi' THEN harga ELSE 0 END) 
WHEN '".$data['intra_ekstra']."' = '01' THEN SUM(CASE WHEN status_histori = 'mutasi_in' AND a.keterangan = 'proc_mutasi' AND harga >= d.value THEN harga ELSE 0 END) 
WHEN '".$data['intra_ekstra']."' = '02' THEN SUM(CASE WHEN status_histori = 'mutasi_in' AND a.keterangan = 'proc_mutasi' AND harga < d.value THEN harga ELSE 0 END) 
END AS mutasi_in,
CASE WHEN '".$data['intra_ekstra']."' = '00' THEN SUM(CASE WHEN status_histori = 'mutasi_in' AND a.keterangan = 'proc_reklas_kode' THEN harga ELSE 0 END) 
WHEN '".$data['intra_ekstra']."' = '01' THEN SUM(CASE WHEN status_histori = 'mutasi_in' AND a.keterangan = 'proc_reklas_kode' AND harga_akhir >= d.value THEN harga ELSE 0 END)
WHEN '".$data['intra_ekstra']."' = '02' THEN SUM(CASE WHEN status_histori = 'mutasi_in' AND a.keterangan = 'proc_reklas_kode' AND harga_akhir < d.value THEN harga ELSE 0 END) 
END AS reklas_plus,
CASE WHEN '".$data['intra_ekstra']."' = '00' THEN SUM(CASE WHEN status_histori = 'penghapusan' AND a.keterangan = '1' THEN harga ELSE 0 END) 
WHEN '".$data['intra_ekstra']."' = '01' THEN SUM(CASE WHEN status_histori = 'penghapusan' AND a.keterangan = '1' AND harga >= d.value THEN harga ELSE 0 END)
WHEN '".$data['intra_ekstra']."' = '02' THEN SUM(CASE WHEN status_histori = 'penghapusan' AND a.keterangan = '1' AND harga < d.value THEN harga ELSE 0 END) 
END AS penghapusan,
CASE WHEN '".$data['intra_ekstra']."' = '00' THEN SUM(CASE WHEN status_histori = 'penghapusan' AND a.keterangan = '2' THEN harga ELSE 0 END) 
WHEN '".$data['intra_ekstra']."' = '01' THEN SUM(CASE WHEN status_histori = 'penghapusan' AND a.keterangan = '2' AND harga >= d.value THEN harga ELSE 0 END)
WHEN '".$data['intra_ekstra']."' = '02' THEN SUM(CASE WHEN status_histori = 'penghapusan' AND a.keterangan = '2' AND harga < d.value THEN harga ELSE 0 END) 
END AS urai_catat,
CASE WHEN '".$data['intra_ekstra']."' = '00' THEN SUM(CASE WHEN status_histori = 'penghapusan' AND a.keterangan = '3' THEN harga ELSE 0 END) 
WHEN '".$data['intra_ekstra']."' = '01' THEN SUM(CASE WHEN status_histori = 'penghapusan' AND a.keterangan = '3' AND harga >= d.value THEN harga ELSE 0 END)
WHEN '".$data['intra_ekstra']."' = '02' THEN SUM(CASE WHEN status_histori = 'penghapusan' AND a.keterangan = '3' AND harga < d.value THEN harga ELSE 0 END) 
END AS lebih_catat,
CASE WHEN '".$data['intra_ekstra']."' = '00' THEN SUM(CASE WHEN status_histori = 'penghapusan' AND SUBSTR(r.kode_rekening, 5, 2) = '01' AND a.keterangan = '4' THEN harga ELSE 0 END) 
WHEN '".$data['intra_ekstra']."' = '01' THEN SUM(CASE WHEN status_histori = 'penghapusan' AND SUBSTR(r.kode_rekening, 5, 2) = '01' AND a.keterangan = '4' AND harga >= d.value THEN harga ELSE 0 END)
WHEN '".$data['intra_ekstra']."' = '02' THEN SUM(CASE WHEN status_histori = 'penghapusan' AND SUBSTR(r.kode_rekening, 5, 2) = '01' AND a.keterangan = '4' AND harga < d.value THEN harga ELSE 0 END) 
END AS koreksi_bm_a,
CASE WHEN '".$data['intra_ekstra']."' = '00' THEN SUM(CASE WHEN status_histori = 'penghapusan' AND SUBSTR(r.kode_rekening, 5, 2) = '02' AND a.keterangan = '4' THEN harga ELSE 0 END) 
WHEN '".$data['intra_ekstra']."' = '01' THEN SUM(CASE WHEN status_histori = 'penghapusan' AND SUBSTR(r.kode_rekening, 5, 2) = '02' AND a.keterangan = '4' AND harga >= d.value THEN harga ELSE 0 END)
WHEN '".$data['intra_ekstra']."' = '02' THEN SUM(CASE WHEN status_histori = 'penghapusan' AND SUBSTR(r.kode_rekening, 5, 2) = '02' AND a.keterangan = '4' AND harga < d.value THEN harga ELSE 0 END) 
END AS koreksi_bm_b,
CASE WHEN '".$data['intra_ekstra']."' = '00' THEN SUM(CASE WHEN status_histori = 'penghapusan' AND SUBSTR(r.kode_rekening, 5, 2) = '03' AND a.keterangan = '4' THEN harga ELSE 0 END) 
WHEN '".$data['intra_ekstra']."' = '01' THEN SUM(CASE WHEN status_histori = 'penghapusan' AND SUBSTR(r.kode_rekening, 5, 2) = '03' AND a.keterangan = '4' AND harga >= d.value THEN harga ELSE 0 END)
WHEN '".$data['intra_ekstra']."' = '02' THEN SUM(CASE WHEN status_histori = 'penghapusan' AND SUBSTR(r.kode_rekening, 5, 2) = '03' AND a.keterangan = '4' AND harga < d.value THEN harga ELSE 0 END) 
END AS koreksi_bm_c,
CASE WHEN '".$data['intra_ekstra']."' = '00' THEN SUM(CASE WHEN status_histori = 'penghapusan' AND SUBSTR(r.kode_rekening, 5, 2) = '04' AND a.keterangan = '4' THEN harga ELSE 0 END) 
WHEN '".$data['intra_ekstra']."' = '01' THEN SUM(CASE WHEN status_histori = 'penghapusan' AND SUBSTR(r.kode_rekening, 5, 2) = '04' AND a.keterangan = '4' AND harga >= d.value THEN harga ELSE 0 END)
WHEN '".$data['intra_ekstra']."' = '02' THEN SUM(CASE WHEN status_histori = 'penghapusan' AND SUBSTR(r.kode_rekening, 5, 2) = '04' AND a.keterangan = '4' AND harga < d.value THEN harga ELSE 0 END) 
END AS koreksi_bm_d,
CASE WHEN '".$data['intra_ekstra']."' = '00' THEN SUM(CASE WHEN status_histori = 'penghapusan' AND SUBSTR(r.kode_rekening, 5, 2) = '05' AND a.keterangan = '4' THEN harga ELSE 0 END) 
WHEN '".$data['intra_ekstra']."' = '01' THEN SUM(CASE WHEN status_histori = 'penghapusan' AND SUBSTR(r.kode_rekening, 5, 2) = '05' AND a.keterangan = '4' AND harga >= d.value THEN harga ELSE 0 END)
WHEN '".$data['intra_ekstra']."' = '02' THEN SUM(CASE WHEN status_histori = 'penghapusan' AND SUBSTR(r.kode_rekening, 5, 2) = '05' AND a.keterangan = '4' AND harga < d.value THEN harga ELSE 0 END) 
END AS koreksi_bm_e,
CASE WHEN '".$data['intra_ekstra']."' = '00' THEN SUM(CASE WHEN status_histori = 'koreksi_kurang' THEN harga ELSE 0 END) 
WHEN '".$data['intra_ekstra']."' = '01' THEN SUM(CASE WHEN status_histori IN ('koreksi_kurang') AND harga >= d.value THEN harga ELSE 0 END)
WHEN '".$data['intra_ekstra']."' = '02' THEN SUM(CASE WHEN status_histori IN ('koreksi_kurang') AND harga < d.value  THEN harga ELSE 0 END)
END AS koreksi_kurang_harga,
CASE WHEN '".$data['intra_ekstra']."' = '00' THEN SUM(CASE WHEN status_histori = 'mutasi_out' AND a.keterangan = 'proc_mutasi' THEN harga ELSE 0 END) 
WHEN '".$data['intra_ekstra']."' = '01' THEN SUM(CASE WHEN status_histori = 'mutasi_out' AND a.keterangan = 'proc_mutasi' AND harga >= d.value THEN harga ELSE 0 END)
WHEN '".$data['intra_ekstra']."' = '02' THEN SUM(CASE WHEN status_histori = 'mutasi_out' AND a.keterangan = 'proc_mutasi' AND harga < d.value THEN harga ELSE 0 END) 
END AS mutasi_out,
CASE WHEN '".$data['intra_ekstra']."' = '00' THEN SUM(CASE WHEN status_histori = 'mutasi_out' AND a.keterangan = 'proc_reklas_kode' THEN harga ELSE 0 END) 
WHEN '".$data['intra_ekstra']."' = '01' THEN SUM(CASE WHEN status_histori = 'mutasi_out' AND a.keterangan = 'proc_reklas_kode' AND harga_awal >= d.value THEN harga ELSE 0 END)
WHEN '".$data['intra_ekstra']."' = '02' THEN SUM(CASE WHEN status_histori = 'mutasi_out' AND a.keterangan = 'proc_reklas_kode' AND harga_awal < d.value THEN harga ELSE 0 END) 
END AS reklas_min,
CASE WHEN '".$data['intra_ekstra']."' = '00' THEN SUM(CASE WHEN status_histori = 'entri' THEN harga ELSE 0 END)
WHEN '".$data['intra_ekstra']."' = '01' THEN SUM(CASE WHEN status_histori = 'entri' AND harga >= d.value THEN harga ELSE 0 END)
WHEN '".$data['intra_ekstra']."' = '02' THEN SUM(CASE WHEN status_histori = 'entri' AND harga < d.value THEN harga ELSE 0 END) 
END AS entri,
CASE WHEN '".$data['intra_ekstra']."' = '00' THEN SUM(CASE WHEN status_histori IN ('koreksi_tambah','kapitalisasi') THEN harga ELSE 0 END) 
WHEN '".$data['intra_ekstra']."' = '01' THEN SUM(CASE WHEN status_histori IN ('koreksi_tambah','kapitalisasi') AND (harga_awal < d.value AND harga_akhir >= d.value) THEN harga_akhir WHEN status_histori IN ('kapitalisasi','koreksi_tambah') AND (harga_awal >= d.value AND harga_akhir >= d.value) THEN harga ELSE 0 END)
WHEN '".$data['intra_ekstra']."' = '02' THEN SUM(CASE WHEN status_histori = 'koreksi_kurang' AND (harga_awal >= d.value AND harga_akhir < d.value) THEN harga_akhir WHEN status_histori IN ('kapitalisasi','koreksi_tambah') AND (harga_awal < d.value AND harga_akhir < d.value) THEN harga ELSE 0 END)
END AS kapitalisasi,
CASE WHEN '".$data['intra_ekstra']."' = '00' THEN SUM(CASE WHEN status_histori = 'koreksi_kurang' THEN harga ELSE 0 END) 
WHEN '".$data['intra_ekstra']."' = '01' THEN SUM(CASE WHEN status_histori IN ('koreksi_kurang') AND (harga_awal >= d.value AND harga_akhir < d.value) THEN harga_awal WHEN status_histori IN ('koreksi_kurang') AND (harga_awal >= d.value AND harga_akhir >= d.value) THEN harga ELSE 0 END)
WHEN '".$data['intra_ekstra']."' = '02' THEN SUM(CASE WHEN status_histori IN ('kapitalisasi','koreksi_tambah') AND (harga_awal < d.value AND harga_akhir >= d.value) THEN harga_awal WHEN status_histori IN ('koreksi_kurang') AND (harga_awal < d.value AND harga_akhir < d.value) THEN harga ELSE 0 END)
END AS koreksi_kurang,
CASE WHEN '".$data['intra_ekstra']."' = '00' THEN SUM(CASE WHEN status_histori = 'penghapusan' THEN harga ELSE 0 END) 
WHEN '".$data['intra_ekstra']."' = '01' THEN SUM(CASE WHEN status_histori = 'penghapusan'  AND harga >= d.value THEN harga ELSE 0 END)
WHEN '".$data['intra_ekstra']."' = '02' THEN SUM(CASE WHEN status_histori = 'penghapusan' AND harga < d.value THEN harga ELSE 0 END) 
END AS penghapusan_all
FROM tbl_kode_lokasi b 
LEFT JOIN (
  SELECT a.* FROM tbl_histori_barang a
  JOIN tbl_kode_barang b ON a.id_kode_barang = b.id_kode_barang
  WHERE b.`kode_kelompok` <> '5' AND (tanggal_histori BETWEEN '".$data['start_date']."' AND '".$data['last_date']."')
  )  a ON b.`id_kode_lokasi` = a.`id_kode_lokasi` ".$kode_jenis."
JOIN tbl_master_intra_extra d ON a.kode_jenis = d.kode_jenis
LEFT JOIN tbl_master_rekening r ON a.id_rekening = r.id_rekening
WHERE a.id_pemilik = '2' AND kuasa_pengguna <> '*'   
".$group_by1.") sq ";

if ($data['id_pengguna'] != '') { // jika unit lokasi dipilih 
  $sql .= " ON d.id_kuasa_pengguna = sq.id_kode_lokasi ";
  } else { 
    $sql .= " ON d.pengguna = sq.`pengguna` ";
  }

$sql .= " LEFT JOIN (SELECT SUM(harga) AS saldo_awal,id_pengguna,id_kode_lokasi FROM tbl_histori_rekap_mutasi WHERE bulan_data = '".$bulan_before."' AND tahun_data = '".$tahun_before."' AND LENGTH(objek) = '6' AND kode_intra_extra = '".$data['intra_ekstra']."' ".$kode_jenis2." ".$group_by2.") c ";

if ($data['id_pengguna'] != '') { // jika unit lokasi dipilih 
  $sql .= " ON d.id_kuasa_pengguna = c.id_kode_lokasi 
            WHERE d.pengguna = '".$data['pengguna']."';";
  } else { 
    $sql .= " ON TRIM(LEADING '0' FROM d.pengguna) = c.id_pengguna ";
  }
  $sql .= " LEFT JOIN (SELECT SUM(harga) AS saldo_akhir,id_pengguna,id_kode_lokasi FROM tbl_histori_rekap_mutasi WHERE bulan_data = '".$data['last_bulan']."' AND tahun_data = '".$data['last_tahun']."' AND LENGTH(objek) = '6' AND kode_intra_extra = '".$data['intra_ekstra']."' ".$kode_jenis2." ".$group_by2.") s ";

if ($data['id_pengguna'] != '') { // jika unit lokasi dipilih 
  $sql .= " ON d.id_kuasa_pengguna = c.id_kode_lokasi 
            WHERE d.pengguna = '".$data['pengguna']."';";
  } else { 
    $sql .= " ON TRIM(LEADING '0' FROM d.pengguna) = s.id_pengguna;";
  }
  
    $query = $this->db->query($sql);
    // if($this->session->userdata('session')->id_upik == 'jss-a7324'){
    // die($this->db->last_query());
    // }
    return $query->result_array();
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
    $this->db->like('id_usulan', $q);
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
  function get_limit_data($limit, $start = 0, $q = NULL)
  {
    $this->db->order_by($this->id, $this->order);
    $this->db->like('id_usulan', $q);
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

  function getParentLokasi($kode_lokasi)
  {
    $this->db->where('kode_lokasi', $kode_lokasi);
    return $this->db->get('tbl_kode_lokasi')->row();
  }
}
