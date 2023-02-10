<?php

if (!defined('BASEPATH')) exit('No direct script access allowed');

class Realisasi_model extends CI_Model
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
	 //echo $data['jenis'];die;
    $where1 = " ";
    $where2 = " ";
    $where3 = " and  (c.harga IS NOT NULL OR nilai_realisasi > 0)  ";

    if ($data['id_pengguna'] != '') {
      $where2 .= " AND l.pengguna = '".$data['pengguna']."' ";
      $where3 .= " AND TRIM(REPLACE(SUBSTR(a.kode_unit,1,8),'.', '')) = TRIM(LEADING '0' FROM '".$data['pengguna']."') ";
    } 
    
    if ($data['jenis'] == 'bulan') {
      
          $where2    .=  " AND tanggal_histori BETWEEN '".$data['start_date']."' AND '".$data['last_date']."' ";
          $where1= " AND (bulan BETWEEN '".$data['start_bulan']."' AND '".$data['last_bulan']."' ) AND tahun = '".$data['tahun']."' "; 
        
        // $where2 .= " AND MONTH(tanggal_histori) = '".$data['bulan']."' AND YEAR(tanggal_histori) = '".$data['tahun']."' ";
        // $where1 .= " AND bulan = '".$data['bulan']."' AND tahun = '".$data['tahun']."'"; 
        
    }
    else if ($data['jenis'] == 'semester') {
		//echo "aaa";die;
      $where2    .=  " AND tanggal_histori BETWEEN '".$data['start_date']."' AND '".$data['last_date']."' ";
      $where1= " AND (bulan BETWEEN '".$data['start_bulan']."' AND '".$data['last_bulan']."' ) AND tahun = '".$data['tahun']."' "; 
    } else {
      $where2 .= " AND YEAR(tanggal_histori) = '".$data['tahun']."' ";
      $where1 .= " AND tahun = '".$data['tahun']."' "; 
    }

    $sql = "SELECT a.kode_rekening,a.nama_rekening,coalesce(c.harga,0) - coalesce(d.harga,0) AS realisasi, SUM(coalesce(a.nilai_dpa,0)) AS dpa, SUM(coalesce(a.nilai_realisasi,0)) AS nilai_realisasi 
    FROM (SELECT kode_rekening,nama_rekening,CASE WHEN kode_unit = '7.01.06.' THEN '7.01.07.'
    WHEN kode_unit = '7.01.07.' THEN '7.01.08.'
    WHEN kode_unit = '7.01.08.' THEN '7.01.09.'
    WHEN kode_unit = '7.01.09.' THEN '7.01.10.'
    WHEN kode_unit = '7.01.10.' THEN '7.01.11.'
    WHEN kode_unit = '7.01.11.' THEN '7.01.06.' ELSE kode_unit END AS kode_unit,nilai_dpa,nilai_realisasi,bulan,tahun FROM sipkd_belanja_modal where 1=1 ".$where1." ) a
    LEFT JOIN tbl_master_rekening b ON SUBSTRING(a.kode_rekening, 1, CHAR_LENGTH(a.kode_rekening) - 1) = b.`kode_rekening` 
    LEFT JOIN (SELECT pengguna,id_rekening,SUM(harga) AS harga,status_histori,id_sumber_dana,tanggal_histori FROM 
      (SELECT tbl_histori_barang.*,l.pengguna FROM tbl_histori_barang 
      JOIN tbl_kode_lokasi  l  ON tbl_histori_barang.`id_kode_lokasi` = l.`id_kode_lokasi` 
      where `status_histori` IN ('entri','kapitalisasi','koreksi_tambah') AND `id_sumber_dana` IN ('1','2') ".$where2." 
      UNION ALL
      SELECT tbl_histori_barang_atb.*,l.pengguna FROM tbl_histori_barang_atb 
      JOIN tbl_kode_lokasi  l  ON tbl_histori_barang_atb.`id_kode_lokasi` = l.`id_kode_lokasi` 
      where `status_histori` IN ('entri','kapitalisasi','koreksi_tambah') AND `id_sumber_dana` IN ('1','2') ".$where2." 
      ) h
    GROUP BY `id_rekening`)  c
    ON b.id_rekening = c.id_rekening AND TRIM(REPLACE(SUBSTR(a.kode_unit,1,8),'.', '')) = TRIM(LEADING '0' FROM c.pengguna) 
    LEFT JOIN (SELECT pengguna,id_rekening,SUM(harga) AS harga,status_histori,id_sumber_dana,tanggal_histori FROM 
    (SELECT tbl_histori_barang.*,l.pengguna FROM tbl_histori_barang 
    JOIN tbl_kode_lokasi  l  ON tbl_histori_barang.`id_kode_lokasi` = l.`id_kode_lokasi` 
    where `status_histori` IN ('penghapusan','koreksi_kurang') AND `id_sumber_dana` IN ('1','2') ".$where2." 
    GROUP BY id_kib,kode_jenis,id_kode_lokasi,status_histori,tanggal_histori,harga,harga_awal) h
  GROUP BY `id_rekening`)  d
  ON b.id_rekening = d.id_rekening AND TRIM(REPLACE(SUBSTR(a.kode_unit,1,8),'.', '')) = TRIM(LEADING '0' FROM c.pengguna) 
    where 1=1  ".$where3."
    GROUP BY a.`kode_rekening`";

    // $session = $this->session->userdata('session');
    // if($session->id_upik == 'jss-a7324'){
    //  die($sql); 
    // }
    $query = $this->db->query($sql);
    return $query->result_array();
  }
  

  // get all
  function get_all_rsud($data)
  {
	//  //echo $data['jenis'];die;
  //   $where1 = " ";
  //   $where2 = " ";
  //   $where3 = " and  (c.harga IS NOT NULL OR nilai_realisasi > 0)  ";

  //   if ($data['id_pengguna'] != '') {
  //     $where2 .= " AND l.pengguna = '".$data['pengguna']."' ";
  //     $where3 .= " AND TRIM(REPLACE(SUBSTR(a.kode_unit,1,8),'.', '')) = TRIM(LEADING '0' FROM '".$data['pengguna']."') ";
  //   } 
    
  //   if ($data['jenis'] == 'bulan') {
      
  //         $where2    .=  " AND tanggal_histori BETWEEN '".$data['start_date']."' AND '".$data['last_date']."' ";
  //         $where1= " AND (bulan BETWEEN '".$data['start_bulan']."' AND '".$data['last_bulan']."' ) AND tahun = '".$data['tahun']."' "; 
        
  //       // $where2 .= " AND MONTH(tanggal_histori) = '".$data['bulan']."' AND YEAR(tanggal_histori) = '".$data['tahun']."' ";
  //       // $where1 .= " AND bulan = '".$data['bulan']."' AND tahun = '".$data['tahun']."'"; 
        
  //   }
  //   else if ($data['jenis'] == 'semester') {
	// 	//echo "aaa";die;
  //     $where2    .=  " AND tanggal_histori BETWEEN '".$data['start_date']."' AND '".$data['last_date']."' ";
  //     $where1= " AND (bulan BETWEEN '".$data['start_bulan']."' AND '".$data['last_bulan']."' ) AND tahun = '".$data['tahun']."' "; 
  //   } else {
  //     $where2 .= " AND YEAR(tanggal_histori) = '".$data['tahun']."' ";
  //     $where1 .= " AND tahun = '".$data['tahun']."' "; 
  //   }

    $sql = "SELECT 
    a.kode_rekening, 
    a.nama_rekening, 
    COALESCE(c.harga, 0) - COALESCE(d.harga, 0) AS realisasi, 
    SUM(
      COALESCE(a.nilai_dpa, 0)
    ) AS dpa, 
    SUM(
      COALESCE(a.nilai_realisasi, 0)
    ) AS nilai_realisasi 
  FROM 
    (
      SELECT 
        kode_rekening, 
        nama_rekening, 
        CASE WHEN kode_unit = '7.01.06.' THEN '7.01.07.' WHEN kode_unit = '7.01.07.' THEN '7.01.08.' WHEN kode_unit = '7.01.08.' THEN '7.01.09.' WHEN kode_unit = '7.01.09.' THEN '7.01.10.' WHEN kode_unit = '7.01.10.' THEN '7.01.11.' WHEN kode_unit = '7.01.11.' THEN '7.01.06.' ELSE kode_unit END AS kode_unit, 
        nilai_dpa, 
        nilai_realisasi, 
        bulan, 
        tahun 
      FROM 
        sipkd_belanja_modal 
      WHERE 
        1 = 1 
        AND (
          bulan BETWEEN '1' 
          AND '12'
        ) 
        AND tahun = '2022'
    ) a 
    LEFT JOIN tbl_master_rekening b ON SUBSTRING(
      a.kode_rekening, 
      1, 
      CHAR_LENGTH(a.kode_rekening) -1
    ) = b.`kode_rekening` 
    LEFT JOIN (
      SELECT 
        pengguna, 
        id_rekening, 
        SUM(harga) AS harga, 
        status_histori, 
        id_sumber_dana, 
        tanggal_histori 
      FROM 
        (
          SELECT 
            tbl_histori_barang.*, 
            l.pengguna 
          FROM 
            tbl_histori_barang 
            JOIN tbl_kode_lokasi l ON tbl_histori_barang.`id_kode_lokasi` = l.`id_kode_lokasi` 
          WHERE 
            `status_histori` IN (
              'entri', 'kapitalisasi', 'koreksi_tambah'
            ) 
            AND `id_sumber_dana` IN ('1', '2') 
            AND l.pengguna = '010201' AND l.id_kode_lokasi = '123'
            AND tanggal_histori BETWEEN '2022-01-01' 
            AND '2022-12-31' 
          UNION ALL 
          SELECT 
            tbl_histori_barang_atb.*, 
            l.pengguna 
          FROM 
            tbl_histori_barang_atb 
            JOIN tbl_kode_lokasi l ON tbl_histori_barang_atb.`id_kode_lokasi` = l.`id_kode_lokasi` 
          WHERE 
            `status_histori` IN (
              'entri', 'kapitalisasi', 'koreksi_tambah'
            ) 
            AND `id_sumber_dana` IN ('1', '2') 
            AND l.pengguna = '010201' AND l.id_kode_lokasi = '123'
            AND tanggal_histori BETWEEN '2022-01-01' 
            AND '2022-12-31'
        ) h 
      GROUP BY 
        `id_rekening`
    ) c ON b.id_rekening = c.id_rekening 
    AND TRIM(
      REPLACE(
        SUBSTR(a.kode_unit, 1, 8), 
        '.', 
        ''
      )
    ) = TRIM(
      LEADING '0' 
      FROM 
        c.pengguna
    ) 
    LEFT JOIN (
      SELECT 
        pengguna, 
        id_rekening, 
        SUM(harga) AS harga, 
        status_histori, 
        id_sumber_dana, 
        tanggal_histori 
      FROM 
        (
          SELECT 
            tbl_histori_barang.*, 
            l.pengguna 
          FROM 
            tbl_histori_barang 
            JOIN tbl_kode_lokasi l ON tbl_histori_barang.`id_kode_lokasi` = l.`id_kode_lokasi` 
          WHERE 
            `status_histori` IN ('penghapusan', 'koreksi_kurang') 
            AND `id_sumber_dana` IN ('1', '2') 
            AND l.pengguna = '010201' 
            AND tanggal_histori BETWEEN '2022-01-01' 
            AND '2022-12-31' 
          GROUP BY 
            id_kib, 
            kode_jenis, 
            id_kode_lokasi, 
            status_histori, 
            tanggal_histori, 
            harga, 
            harga_awal
        ) h 
      GROUP BY 
        `id_rekening`
    ) d ON b.id_rekening = d.id_rekening 
    AND TRIM(
      REPLACE(
        SUBSTR(a.kode_unit, 1, 8), 
        '.', 
        ''
      )
    ) = TRIM(
      LEADING '0' 
      FROM 
        c.pengguna
    ) 
  WHERE 
    1 = 1 
    AND (
      c.harga IS NOT NULL 
      OR nilai_realisasi > 0
    ) 
    AND 
        a.kode_unit = '1.02.01.022.'
  GROUP BY 
    a.`kode_rekening`
  ;";

    // $session = $this->session->userdata('session');
    // if($session->id_upik == 'jss-a7324'){
    //  die($sql); 
    // }
    $query = $this->db->query($sql);
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
