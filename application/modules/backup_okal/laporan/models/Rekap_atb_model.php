<?php

if (!defined('BASEPATH')) exit('No direct script access allowed');

class Rekap_atb_model extends CI_Model
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



  function get_atb($data,$mulai)
  {
    if ($data['id_pengguna'] != '')  $pengguna = $this->db->get_where('view_pengguna', array('id_pengguna' => $data['id_pengguna'],))->row()->pengguna;
    if ($data['id_kuasa_pengguna'] != '')  $kuasa_pengguna = $this->db->get_where('view_kuasa_pengguna', array('id_pengguna' => $data['id_pengguna'], 'id_kuasa_pengguna' => $data['id_kuasa_pengguna']))->row()->kuasa_pengguna;

    $where = "";
    if ($data['id_sub_kuasa_pengguna'] != '') {
      $where .= "AND A.id_kode_lokasi = '".$data['id_sub_kuasa_pengguna']."' ";
    } else if ($data['id_kuasa_pengguna'] != '') {
      $where .= "AND B.pengguna = '". $pengguna."'  AND B.kuasa_pengguna = '".$kuasa_pengguna."' ";
    } else if ($data['id_pengguna'] != '') {
      $where .= "AND B.pengguna = '".$pengguna."' ";
    }

    if ($data['start_date'] != '')
      $where .= "AND tanggal_transaksi >= '".$data['start_date']."' ";
    if ($data['last_date'] != '')
      $where .= "AND tanggal_transaksi <= '".$data['last_date']."' ";


    if ($data['intra_ekstra'] == '01')  $where .= "AND harga >=  C.value ";  //intra
    else if ($data['intra_ekstra'] == '02') $where .= "AND harga <  C.value "; //extra


    if ($data['atb'] == 'kajian')
      $where .= "AND A.id_kode_barang IN ('14741','14850') ";
      
    if ($data['atb'] == 'software')
    $where .= "AND A.id_kode_barang IN ('14739','14851') ";

    $sql = "
    SELECT * FROM 
    (SELECT '1' as LEVEL,NULL AS no,
    id_pengguna AS kode_lokasi_,  `A`.*,NULL as umur_ekonomis_p, NULL as umur_bulan,NULL as nilai_penyusutan,NULL as akumulasi_penyusutan, NULL as nilai_buku, YEAR(A.tanggal_perolehan) AS tahun_perolehan, case when (A.nama_barang_migrasi is NOT NULL) THEN A.nama_barang_migrasi ELSE A.nama_barang END as nama_barang_desc, `D`.`instansi`, `B`.`pengguna`, `C`.`value` 
    FROM `tbl_kib_atb` `A` 
    JOIN `tbl_kode_lokasi` `B` ON `B`.`id_kode_lokasi`=`A`.`id_kode_lokasi` 
    JOIN `view_pengguna` `D` ON `B`.`pengguna`=`D`.`pengguna`
    LEFT JOIN `tbl_master_intra_extra` `C` ON `C`.`kode_jenis`='5.03' 
    WHERE `validasi` = '2' AND `id_pemilik` = '2' 
    AND `id_kode_barang_aset_lainya` = '0' 
    AND `status_barang` NOT IN('terhapus') 
    $where
    GROUP BY `B`.`pengguna`
    UNION ALL
    SELECT '2' as LEVEL,(@row_number:=@row_number + 1) AS no,A.id_kode_lokasi as kode_lokasi_, `A`.*,D.umur_ekonomis as umur_ekonomis_p, umur_bulan,nilai_penyusutan, akumulasi_penyusutan, nilai_buku, YEAR(A.tanggal_perolehan) AS tahun_perolehan, case when (A.nama_barang_migrasi is NOT NULL) THEN A.nama_barang_migrasi ELSE A.nama_barang END as nama_barang_desc, `B`.`instansi`, `B`.`pengguna`, `C`.`value` 
    FROM `tbl_kib_atb` `A` 
    LEFT JOIN `tbl_kode_lokasi` `B` ON `B`.`id_kode_lokasi`=`A`.`id_kode_lokasi` 
    LEFT JOIN `tbl_master_intra_extra` `C` ON `C`.`kode_jenis`='5.03' 
    LEFT JOIN `tbl_penyusutan` D ON '5.03'=D.kode_jenis AND A.id_kib_atb = D.id_kib AND D.tanggal_penyusutan = '".$data['last_date']."'
    WHERE `validasi` = '2' AND `id_pemilik` = '2' AND `id_kode_barang_aset_lainya` = '0' 
    AND `status_barang` NOT IN('terhapus') $where 
    ORDER BY kode_lokasi_ ASC, `kode_barang` ASC, YEAR(tanggal_transaksi) ASC, YEAR(tanggal_perolehan) ASC, `nomor_register` ASC) sq
    ORDER BY `kode_lokasi_` ASC,level ASC, `kode_barang` ASC, YEAR(tanggal_transaksi) ASC, YEAR(tanggal_perolehan) ASC, `nomor_register` ASC";
// die($sql); 
  // $session = $this->session->userdata('session');
  // if($session->id_upik == 'jss-a7324'){
  //   $this->db->get()->result_array();
  //   die($this->db->last_query());
  // }
    return
      $this->db->query($sql)->result_array();
    // die($this->db->last_query());
  }



  // get kib atb
  function get_pengguna($data)
  {
    $jenis = $this->global_model->kode_jenis[$data['kode_jenis']];

    if ($data['id_pengguna'] != '')  $pengguna = $this->db->get_where('view_pengguna', array('id_pengguna' => $data['id_pengguna'],))->row()->pengguna;
    if ($data['id_kuasa_pengguna'] != '')  $kuasa_pengguna = $this->db->get_where('view_kuasa_pengguna', array('id_pengguna' => $data['id_pengguna'], 'id_kuasa_pengguna' => $data['id_kuasa_pengguna']))->row()->kuasa_pengguna;

    $prefix = $data['prefix'];
      $this->db->select('
      pengguna,instansi'); //, set_intra_extra(A.kode_lokasi,"'.$data['intra_ekstra'].'") as intra_ekstra_kode_lokasi
    

    $this->db->from($jenis['table'] . $prefix . ' A');
    $this->db->where('validasi', '2');
    /* 15 juli 2020 kib f di pisah lagi
    $this->db->where('kib_f', '1'); //bukan kib f
    */
    $this->db->where('id_pemilik', $data['id_pemilik']);
    $this->db->where('id_kode_barang_aset_lainya', '0');
    $this->db->where_not_in('status_barang', array('terhapus'));

    if ($data['id_sub_kuasa_pengguna'] != '') {
      $this->db->where('A.id_kode_lokasi', $data['id_sub_kuasa_pengguna']);
    } else if ($data['id_kuasa_pengguna'] != '') {
      $this->db->where('B.pengguna', $pengguna);
      $this->db->where('B.kuasa_pengguna', $kuasa_pengguna);
    } else if ($data['id_pengguna'] != '') {
      $this->db->where('B.pengguna', $pengguna);
    }
    if ($data['start_date'] != '')
      $this->db->where('tanggal_transaksi >= ', $data['start_date']);
    if ($data['last_date'] != '')
      $this->db->where('tanggal_transaksi <= ', $data['last_date']);

    $harga_nilaikontrak = 'harga';

    if ($data['intra_ekstra'] == '01') $this->db->where($harga_nilaikontrak . ' >=', 'C.value', FALSE);  //intra
    else if ($data['intra_ekstra'] == '02') $this->db->where($harga_nilaikontrak . ' <', 'C.value', FALSE); //extra

    $this->db->join($this->tbl_kode_lokasi . ' B', 'B.id_kode_lokasi=A.id_kode_lokasi', 'left');
    $this->db->join($this->tbl_master_intra_extra . ' C', 'C.kode_jenis="' . $data['kode_jenis'] . '"', 'left');

    // if ($data['kode_jenis'] == "02" or $data['kode_jenis'] == "05") $this->db->group_by('input_banyak');

    if ($data['atb'] == 'kajian')
      $this->db->where('id_kode_barang IN  ', "('14741','14850')", FALSE);
      
    if ($data['atb'] == 'software')
    $this->db->where('id_kode_barang IN  ', "('14739','14851')", FALSE);
    
    $this->db->group_by('B.pengguna');
    $this->db->order_by('B.id_kode_lokasi', 'asc');
  
  // $session = $this->session->userdata('session');
  // if($session->id_upik == 'jss-a7324'){
  //   $this->db->get()->result_array();
  // }
    // return
      $this->db->get()->result_array();
    // die($this->db->last_query());
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
