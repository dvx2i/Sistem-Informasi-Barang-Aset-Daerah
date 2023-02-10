<?php

if (!defined('BASEPATH')) exit('No direct script access allowed');

class Kib_model extends CI_Model{

  public $tbl_kode_barang = 'tbl_kode_barang';
  public $tbl_kode_lokasi = 'tbl_kode_lokasi';
  public $tbl_master_intra_extra = 'tbl_master_intra_extra';

  public $tbl_usulan_barang = 'tbl_usulan_barang';
  public $id_usulan_barang = 'id_usulan_barang';

  function __construct(){
    parent::__construct();
  }

    // datatables
  function json_a() { 
    $this->load->helper('my_datatable');
    
    //serializeArray
    $form = array();
    foreach ($_POST['form'] as $key => $value) {
      $form[$value['name']] = $value['value'];
    }

    if ($form['id_pengguna'] != '')  $pengguna = $this->db->get_where('view_pengguna', array('id_pengguna' => $form['id_pengguna'], ))->row()->pengguna;
    if ($form['id_kuasa_pengguna'] != '')  $kuasa_pengguna = $this->db->get_where('view_kuasa_pengguna', array('id_pengguna' => $form['id_pengguna'],'id_kuasa_pengguna' => $form['id_kuasa_pengguna'] ))->row()->kuasa_pengguna;


    $this->datatables->select('(case when (kib_f="1") then "KIB A" else "KIB F" end) as jenis, A.id_kib_a,A.nama_barang,A.kode_barang,A.nomor_register,A.luas,A.tahun_pengadaan,A.letak_alamat,A.status_hak,A.sertifikat_tanggal,A.sertifikat_nomor,A.penggunaan,A.asal_usul,A.harga,A.keterangan,A.kode_lokasi,A.created_at,A.updated_at, B.nama_barang as nama_barang2,A.validasi');
    $this->datatables->from('tbl_kib_a A');
    $this->datatables->join($this->tbl_kode_barang.' B', 'A.id_kode_barang = B.id_kode_barang');
    $this->datatables->join($this->tbl_kode_lokasi.' C', 'A.id_kode_lokasi = C.id_kode_lokasi');

    // $session = $this->session->userdata('session');
    // die(json_encode($form));
    /*
    $lokasi = explode('.',$session->kode_lokasi);
    $pengguna = $lokasi[3]; $kuasa_pengguna =  $lokasi[4];
    if ($session->id_role != '1' and $kuasa_pengguna != '*') //bukan super admin, bukan skpd
      $this->datatables->where('A.id_kode_lokasi',$session->id_kode_lokasi);
    else if ($kuasa_pengguna == '*') //jika bukan skpd
      $this->datatables->where('C.pengguna',$pengguna);
*/

    if ( isset($form['checkbox_kib_f']) ) $this->datatables->where('A.kib_f','2');  //2 untuk kib f
    else $this->datatables->where('A.kib_f','1');  
    
    $this->datatables->where('id_pemilik', $form['pemilik']);

    if ($form['id_kuasa_pengguna'] != ''){
      $this->datatables->where('C.id_kode_lokasi', $form['id_kuasa_pengguna']);
    }
    else if (empty($form['id_kuasa_pengguna']) && !empty($form['id_pengguna'])){
      $this->datatables->where('C.pengguna', $pengguna);
    }
    // else if ($form['id_pengguna'] != ''){
    //   $this->datatables->where('C.pengguna', $pengguna);
    // }
    if ($form['start_date'] != '')
      $this->datatables->where('tanggal_perolehan >= ', date('Y-m-d', tgl_inter($form['start_date'])));
    if ($form['last_date'] != '')
      $this->datatables->where('tanggal_perolehan <= ', date('Y-m-d', tgl_inter($form['last_date'])));
    
    if($form['kode_barang'])
      $this->datatables->where('A.kode_barang', $form['kode_barang']);

    if(empty($form['kode_sub_sub_rincian_objek'])){
      $this->datatables->where("(A.kode_barang LIKE '%".$form['kode_sub_rincian_objek']."%')", NULL, FALSE);  
    }
    if(empty($form['kode_sub_rincian_objek'])){
      $this->datatables->where("(A.kode_barang LIKE '%".$form['kode_rincian_objek']."%')", NULL, FALSE);  
    }
    if(empty($form['kode_rincian_objek'])){
      $this->datatables->where("(A.kode_barang LIKE '%".$form['kode_objek']."%')", NULL, FALSE);  
    }    

    $this->datatables->like('A.nama_barang',$form['nama_barang']);
    
    // $this->datatables->edit_column('validasi','$1','status_validasi_kib(validasi)');  
    // $this->datatables->edit_column('luas','$1','format_number(luas)');
    $this->datatables->edit_column('sertifikat_tanggal','$1','date_time(sertifikat_tanggal)');
    $this->datatables->edit_column('harga','$1','format_number(harga)');
    // $this->datatables->add_column('action', '$1', 'get_action('.$session->id_role.',kib_a,'.$this->id.', validasi)');
    return 
    $this->datatables->generate();
  }



  function json_b() { //die(explode('.',$this->session->userdata('session')->kode_lokasi)[4]);
    $this->load->helper('my_datatable');
    //serializeArray
    $form = array();
    foreach ($_POST['form'] as $key => $value) {
      $form[$value['name']] = $value['value'];
    }

    if ($form['id_pengguna'] != '')  $pengguna = $this->db->get_where('view_pengguna', array('id_pengguna' => $form['id_pengguna'], ))->row()->pengguna;
    if ($form['id_kuasa_pengguna'] != '')  $kuasa_pengguna = $this->db->get_where('view_kuasa_pengguna', array('id_pengguna' => $form['id_pengguna'],'id_kuasa_pengguna' => $form['id_kuasa_pengguna'] ))->row()->kuasa_pengguna;


    $this->datatables->select('(case when (kib_f="1") then "KIB B" else "KIB F" end) as jenis, A.id_kib_b,A.kode_barang,A.nama_barang,A.nomor_register,A.merk_type,A.ukuran_cc,A.bahan,A.tahun_pembelian,A.nomor_pabrik,A.nomor_rangka,A.nomor_mesin,A.nomor_polisi,A.nomor_bpkb,A.asal_usul,A.harga,A.keterangan,A.kode_lokasi,A.created_at,A.updated_at, B.nama_barang as nama_barang2,A.validasi');
    $this->datatables->from('tbl_kib_b A');
    $this->datatables->join($this->tbl_kode_barang.' B', 'A.id_kode_barang = B.id_kode_barang');
    $this->datatables->join($this->tbl_kode_lokasi.' C', 'A.id_kode_lokasi = C.id_kode_lokasi');
    $session = $this->session->userdata('session');
    $lokasi = explode('.',$session->kode_lokasi);
/*
    $pengguna = $lokasi[3]; $kuasa_pengguna =  $lokasi[4];
    if ($session->id_role != '1' and $kuasa_pengguna != '*') //bukan super admin, bukan skpd
      $this->datatables->where('A.id_kode_lokasi',$session->id_kode_lokasi);
    else if ($kuasa_pengguna == '*') //jika bukan skpd
      $this->datatables->where('C.pengguna',$pengguna);
*/
    if ( isset($form['checkbox_kib_f']) ) $this->datatables->where('A.kib_f','2');  //2 untuk kib f
    else $this->datatables->where('A.kib_f','1');  

    $this->datatables->where('id_pemilik', $form['pemilik']);

    // if ($form['id_kuasa_pengguna'] != ''){
    //   $this->datatables->where('C.id_kode_lokasi', $form['id_kuasa_pengguna']);
    // }
    // else if ($form['id_pengguna'] != ''){
    //   $this->datatables->where('C.pengguna', $pengguna);
    //   $this->datatables->where('C.kuasa_pengguna', $kuasa_pengguna);
    // }
    // else if ($form['id_pengguna'] != ''){
    //   $this->datatables->where('C.pengguna', $pengguna);
    // }
    if ($form['id_kuasa_pengguna'] != ''){
      $this->datatables->where('C.id_kode_lokasi', $form['id_kuasa_pengguna']);
    }
    else if (empty($form['id_kuasa_pengguna']) && !empty($form['id_pengguna'])){
      $this->datatables->where('C.pengguna', $pengguna);
    }

    if ($form['start_date'] != '')
      $this->datatables->where('tanggal_perolehan >= ', date('Y-m-d', tgl_inter($form['start_date'])));
    if ($form['last_date'] != '')
      $this->datatables->where('tanggal_perolehan <= ', date('Y-m-d', tgl_inter($form['last_date'])));

    if($form['kode_barang']){
      $this->datatables->where('A.kode_barang', $form['kode_barang']);
    }

    if(empty($form['kode_sub_sub_rincian_objek'])){
      $this->datatables->where("(A.kode_barang LIKE '%".$form['kode_sub_rincian_objek']."%')", NULL, FALSE);  
    }
    if(empty($form['kode_sub_rincian_objek'])){
      $this->datatables->where("(A.kode_barang LIKE '%".$form['kode_rincian_objek']."%')", NULL, FALSE);  
    }
    if(empty($form['kode_rincian_objek'])){
      $this->datatables->where("(A.kode_barang LIKE '%".$form['kode_objek']."%')", NULL, FALSE);  
    }    

    // $this->datatables->like('A.nama_barang',$form['nama_barang']);
    // $this->datatables->or_like('A.nomor_polisi',$form['nama_barang']);
    if($form['nama_barang'])
    $this->datatables->where("(A.nomor_polisi LIKE '%".$form['nama_barang']."%' OR A.nama_barang LIKE '%".$form['nama_barang']."%')", NULL, FALSE);


    // $this->datatables->edit_column('validasi','$1','status_validasi_kib(validasi)');    
    // $this->datatables->edit_column('ukuran_cc','$1','format_number(ukuran_cc)');
    $this->datatables->edit_column('harga','$1','format_number(harga)');
    // $this->datatables->add_column('action', '$1', 'get_action('.$session->id_role.',kib_b,'.$this->id.', validasi)');
    return 
    $this->datatables->generate();
    // die($this->db->last_query());

  }


  function json_c() {
    $this->load->helper('my_datatable');
    //serializeArray
    $form = array();
    foreach ($_POST['form'] as $key => $value) {
      $form[$value['name']] = $value['value'];
    }

    if ($form['id_pengguna'] != '')  $pengguna = $this->db->get_where('view_pengguna', array('id_pengguna' => $form['id_pengguna'], ))->row()->pengguna;
    if ($form['id_kuasa_pengguna'] != '')  $kuasa_pengguna = $this->db->get_where('view_kuasa_pengguna', array('id_pengguna' => $form['id_pengguna'],'id_kuasa_pengguna' => $form['id_kuasa_pengguna'] ))->row()->kuasa_pengguna;

    $this->datatables->select('(case when (kib_f="1") then "KIB C" else "KIB F" end) as jenis, A.id_kib_c,A.nama_barang,A.kode_barang,A.nomor_register,A.kondisi_bangunan,A.bangunan_bertingkat,A.bangunan_beton,A.luas_lantai_m2,A.lokasi_alamat,A.gedung_tanggal,A.gedung_nomor,A.luas_m2,A.status,A.nomor_kode_tanah,A.asal_usul,A.harga,A.keterangan, B.nama_barang as nama_barang2, A.validasi');
    $this->datatables->from('tbl_kib_c A');
    $this->datatables->join($this->tbl_kode_barang.' B', 'A.id_kode_barang = B.id_kode_barang');
    $this->datatables->join($this->tbl_kode_lokasi.' C', 'A.id_kode_lokasi = C.id_kode_lokasi');

    $session = $this->session->userdata('session');
    $lokasi = explode('.',$session->kode_lokasi);
/*    
    $pengguna = $lokasi[3]; $kuasa_pengguna =  $lokasi[4];
    if ($session->id_role != '1' and $kuasa_pengguna != '*') //bukan super admin, bukan skpd
      $this->datatables->where('A.id_kode_lokasi',$session->id_kode_lokasi);
    else if ($kuasa_pengguna == '*') //jika bukan skpd
      $this->datatables->where('C.pengguna',$pengguna);
*/
    if ( isset($form['checkbox_kib_f']) ) $this->datatables->where('A.kib_f','2');  //2 untuk kib f
    else $this->datatables->where('A.kib_f','1');  

    $this->datatables->where('id_pemilik', $form['pemilik']);

   
    if ($form['id_kuasa_pengguna'] != ''){
      $this->datatables->where('C.id_kode_lokasi', $form['id_kuasa_pengguna']);
    }
    else if (empty($form['id_kuasa_pengguna']) && !empty($form['id_pengguna'])){
      $this->datatables->where('C.pengguna', $pengguna);
    }
    
    if ($form['start_date'] != '')
      $this->datatables->where('tanggal_perolehan >= ', date('Y-m-d', tgl_inter($form['start_date'])));
    if ($form['last_date'] != '')
      $this->datatables->where('tanggal_perolehan <= ', date('Y-m-d', tgl_inter($form['last_date'])));

    if($form['kode_barang']){
      $this->datatables->where('A.kode_barang', $form['kode_barang']);
    }

    if(empty($form['kode_sub_sub_rincian_objek'])){
      $this->datatables->where("(A.kode_barang LIKE '%".$form['kode_sub_rincian_objek']."%')", NULL, FALSE);  
    }
    if(empty($form['kode_sub_rincian_objek'])){
      $this->datatables->where("(A.kode_barang LIKE '%".$form['kode_rincian_objek']."%')", NULL, FALSE);  
    }
    if(empty($form['kode_rincian_objek'])){
      $this->datatables->where("(A.kode_barang LIKE '%".$form['kode_objek']."%')", NULL, FALSE);  
    }    

    $this->datatables->like('A.nama_barang',$form['nama_barang']);

    // $this->datatables->edit_column('validasi','$1','status_validasi_kib(validasi)');    
    // $this->datatables->add_column('action', '$1', 'get_action('.$session->id_role.',kib_c,'.$this->id.', validasi)');
    $this->datatables->edit_column('luas_lantai_m2','$1','format_number(luas_lantai_m2)');
    $this->datatables->edit_column('luas_m2','$1','format_number(luas_m2)');
    $this->datatables->edit_column('harga','$1','format_number(harga)');
    $this->datatables->edit_column('gedung_tanggal','$1','date_time(gedung_tanggal)');
    return $this->datatables->generate();
  }


  function json_d() {
    $this->load->helper('my_datatable');
    //serializeArray
    $form = array();
    foreach ($_POST['form'] as $key => $value) {
      $form[$value['name']] = $value['value'];
    }    

    if ($form['id_pengguna'] != '')  $pengguna = $this->db->get_where('view_pengguna', array('id_pengguna' => $form['id_pengguna'], ))->row()->pengguna;
    if ($form['id_kuasa_pengguna'] != '')  $kuasa_pengguna = $this->db->get_where('view_kuasa_pengguna', array('id_pengguna' => $form['id_pengguna'],'id_kuasa_pengguna' => $form['id_kuasa_pengguna'] ))->row()->kuasa_pengguna;

    $this->datatables->select('(case when (kib_f="1") then "KIB D" else "KIB F" end) as jenis, A.id_kib_d,A.nama_barang,A.kode_barang,A.nomor_register,A.konstruksi,A.panjang_km,A.lebar_m,A.luas_m2,A.letak_lokasi,A.dokumen_tanggal,A.dokumen_nomor,A.status_tanah,A.kode_tanah,A.asal_usul,A.harga,A.kondisi,A.keterangan,A.kode_lokasi,A.validasi');
    $this->datatables->from('tbl_kib_d A');
    $this->datatables->join($this->tbl_kode_lokasi.' C', 'A.id_kode_lokasi = C.id_kode_lokasi');

    $session = $this->session->userdata('session');
    $lokasi = explode('.',$session->kode_lokasi);
/*
    $pengguna = $lokasi[3]; $kuasa_pengguna =  $lokasi[4];
    if ($session->id_role != '1' and $kuasa_pengguna != '*') //bukan super admin, bukan skpd
      $this->datatables->where('A.id_kode_lokasi',$session->id_kode_lokasi);
    else if ($kuasa_pengguna == '*') //jika bukan skpd
      $this->datatables->where('C.pengguna',$pengguna);
 */    

    if ( isset($form['checkbox_kib_f']) ) $this->datatables->where('A.kib_f','2');  //2 untuk kib f
    else $this->datatables->where('A.kib_f','1');  

    $this->datatables->where('id_pemilik', $form['pemilik']);

    if ($form['id_kuasa_pengguna'] != ''){
      $this->datatables->where('C.id_kode_lokasi', $form['id_kuasa_pengguna']);
    }
    else if (empty($form['id_kuasa_pengguna']) && !empty($form['id_pengguna'])){
      $this->datatables->where('C.pengguna', $pengguna);
    }
    
    if ($form['start_date'] != '')
      $this->datatables->where('tanggal_perolehan >= ', date('Y-m-d', tgl_inter($form['start_date'])));
    if ($form['last_date'] != '')
      $this->datatables->where('tanggal_perolehan <= ', date('Y-m-d', tgl_inter($form['last_date'])));

    if($form['kode_barang']){
      $this->datatables->where('A.kode_barang', $form['kode_barang']);
    }

    if(empty($form['kode_sub_sub_rincian_objek'])){
      $this->datatables->where("(A.kode_barang LIKE '%".$form['kode_sub_rincian_objek']."%')", NULL, FALSE);  
    }
    if(empty($form['kode_sub_rincian_objek'])){
      $this->datatables->where("(A.kode_barang LIKE '%".$form['kode_rincian_objek']."%')", NULL, FALSE);  
    }
    if(empty($form['kode_rincian_objek'])){
      $this->datatables->where("(A.kode_barang LIKE '%".$form['kode_objek']."%')", NULL, FALSE);  
    }    
      
    $this->datatables->like('A.nama_barang',$form['nama_barang']);


    // $this->datatables->edit_column('validasi','$1','status_validasi_kib(validasi)');    
    // $this->datatables->add_column('action', '$1', 'get_action('.$session->id_role.',kib_d,'.$this->id.', validasi)');
    $this->datatables->edit_column('panjang_km','$1','format_number(panjang_km)');
    $this->datatables->edit_column('lebar_m','$1','format_number(lebar_m)');
    $this->datatables->edit_column('luas_m2','$1','format_number(luas_m2)');
    $this->datatables->edit_column('dokumen_tanggal','$1','date_time(dokumen_tanggal)');
    $this->datatables->edit_column('harga','$1','format_number(harga)');
    return $this->datatables->generate();
  }


  function json_e() {
    $this->load->helper('my_datatable');
    //serializeArray
    $form = array();
    foreach ($_POST['form'] as $key => $value) {
      $form[$value['name']] = $value['value'];
    }    

    if ($form['id_pengguna'] != '')  $pengguna = $this->db->get_where('view_pengguna', array('id_pengguna' => $form['id_pengguna'], ))->row()->pengguna;
    if ($form['id_kuasa_pengguna'] != '')  $kuasa_pengguna = $this->db->get_where('view_kuasa_pengguna', array('id_pengguna' => $form['id_pengguna'],'id_kuasa_pengguna' => $form['id_kuasa_pengguna'] ))->row()->kuasa_pengguna;


    $this->datatables->select('(case when (kib_f="1") then "KIB E" else "KIB F" end) as jenis, A.id_kib_e,A.nama_barang,A.kode_barang,A.nomor_register,A.judul_pencipta,A.spesifikasi,A.kesenian_asal_daerah,A.kesenian_pencipta,A.kesenian_bahan,A.hewan_tumbuhan_jenis,A.hewan_tumbuhan_ukuran,A.jumlah,tahun_pembelian,A.asal_usul,A.harga,A.keterangan, A.kode_lokasi,A.validasi');
    $this->datatables->from('tbl_kib_e A');
    $this->datatables->join($this->tbl_kode_lokasi.' C', 'A.id_kode_lokasi = C.id_kode_lokasi');

    $session = $this->session->userdata('session');
    $lokasi = explode('.',$session->kode_lokasi);
/*
    $pengguna = $lokasi[3]; $kuasa_pengguna =  $lokasi[4];
    if ($session->id_role != '1' and $kuasa_pengguna != '*') //bukan super admin, bukan skpd
      $this->datatables->where('A.id_kode_lokasi',$session->id_kode_lokasi);
    else if ($kuasa_pengguna == '*') //jika bukan skpd
      $this->datatables->where('C.pengguna',$pengguna);
*/    

    if ( isset($form['checkbox_kib_f']) ) $this->datatables->where('A.kib_f','2');  //2 untuk kib f
    else $this->datatables->where('A.kib_f','1');  

    $this->datatables->where('id_pemilik', $form['pemilik']);

   
    if ($form['id_kuasa_pengguna'] != ''){
      $this->datatables->where('C.id_kode_lokasi', $form['id_kuasa_pengguna']);
    }
    else if (empty($form['id_kuasa_pengguna']) && !empty($form['id_pengguna'])){
      $this->datatables->where('C.pengguna', $pengguna);
    }
    
    if ($form['start_date'] != '')
      $this->datatables->where('tanggal_perolehan >= ', date('Y-m-d', tgl_inter($form['start_date'])));
    if ($form['last_date'] != '')
      $this->datatables->where('tanggal_perolehan <= ', date('Y-m-d', tgl_inter($form['last_date'])));

    if($form['kode_barang']){
      $this->datatables->where('A.kode_barang', $form['kode_barang']);
    }

    if(empty($form['kode_sub_sub_rincian_objek'])){
      $this->datatables->where("(A.kode_barang LIKE '%".$form['kode_sub_rincian_objek']."%')", NULL, FALSE);  
    }
    if(empty($form['kode_sub_rincian_objek'])){
      $this->datatables->where("(A.kode_barang LIKE '%".$form['kode_rincian_objek']."%')", NULL, FALSE);  
    }
    if(empty($form['kode_rincian_objek'])){
      $this->datatables->where("(A.kode_barang LIKE '%".$form['kode_objek']."%')", NULL, FALSE);  
    }    

    $this->datatables->like('A.nama_barang',$form['nama_barang']);

    // $this->datatables->edit_column('validasi','$1','status_validasi_kib(validasi)');    
    // $this->datatables->add_column('action', '$1', 'get_action('.$session->id_role.',kib_e,'.$this->id.', validasi)');
    $this->datatables->edit_column('hewan_tumbuhan_ukuran','$1','format_number(hewan_tumbuhan_ukuran)');
    $this->datatables->edit_column('jumlah','$1','format_number(jumlah)');
    $this->datatables->edit_column('harga','$1','format_number(harga)');
    return $this->datatables->generate();
  }



  function json_f() {
    $this->load->helper('my_datatable');
    $form = array();
    foreach ($_POST['form'] as $key => $value) {
      $form[$value['name']] = $value['value'];
    }    

    if ($form['id_pengguna'] != '')  $pengguna = $this->db->get_where('view_pengguna', array('id_pengguna' => $form['id_pengguna'], ))->row()->pengguna;
    if ($form['id_kuasa_pengguna'] != '')  $kuasa_pengguna = $this->db->get_where('view_kuasa_pengguna', array('id_pengguna' => $form['id_pengguna'],'id_kuasa_pengguna' => $form['id_kuasa_pengguna'] ))->row()->kuasa_pengguna;

    $this->datatables->select('id_kib as id_kib_f,kode_barang,nama_barang,bangunan,bangunan_bertingkat as konstruksi_bertingkat,bangunan_beton as konstruksi_beton,luas_m2,letak_lokasi_alamat as lokasi_alamat,dokumen_tanggal,dokumen_nomor,tgl_bln_thn_mulai as tanggal_mulai,status_tanah,nomor_kode_tanah,asal_usul_pembiayaan as asal_usul,nilai_kontrak_rp as nilai_kontrak,A.keterangan,A.kode_lokasi,validasi');
    $this->datatables->from('view_kib_f A');
    $this->datatables->join($this->tbl_kode_lokasi.' C', 'A.id_kode_lokasi = C.id_kode_lokasi');
    $this->datatables->where('id_pemilik', $form['pemilik']);

   
    if ($form['id_kuasa_pengguna'] != ''){
      $this->datatables->where('C.id_kode_lokasi', $form['id_kuasa_pengguna']);
    }
    else if (empty($form['id_kuasa_pengguna']) && !empty($form['id_pengguna'])){
      $this->datatables->where('C.pengguna', $pengguna);
    }
    
    if ($form['start_date'] != '')
      $this->datatables->where('tanggal_perolehan >= ', date('Y-m-d', tgl_inter($form['start_date'])));
    if ($form['last_date'] != '')
      $this->datatables->where('tanggal_perolehan <= ', date('Y-m-d', tgl_inter($form['last_date'])));

    if($form['kode_barang']){
      $this->datatables->where('A.kode_barang', $form['kode_barang']);
    }

    if(empty($form['kode_sub_sub_rincian_objek'])){
      $this->datatables->where("(A.kode_barang LIKE '%".$form['kode_sub_rincian_objek']."%')", NULL, FALSE);  
    }
    if(empty($form['kode_sub_rincian_objek'])){
      $this->datatables->where("(A.kode_barang LIKE '%".$form['kode_rincian_objek']."%')", NULL, FALSE);  
    }
    if(empty($form['kode_rincian_objek'])){
      $this->datatables->where("(A.kode_barang LIKE '%".$form['kode_objek']."%')", NULL, FALSE);  
    }    

    $this->datatables->like('A.nama_barang',$form['nama_barang']);
    // $this->datatables->add_column('action', '$1', 'get_action('.$session->id_role.',kib_f,'.$this->id.', validasi)');
    $this->datatables->edit_column('luas_m2','$1','format_number(luas_m2)');
    $this->datatables->edit_column('dokumen_tanggal','$1','date_time(dokumen_tanggal)');
    $this->datatables->edit_column('tanggal_mulai','$1','date_time(tanggal_mulai)');
    $this->datatables->edit_column('nilai_kontrak','$1','format_number(nilai_kontrak)');
    return $this->datatables->generate();
  }













  // get all
  function get_all($data){
    $jenis = $this->global_model->kode_jenis[$data['kode_jenis']];

    if ($data['kode_jenis']=='6') {
      return $this->get_all_kib_f($data);
    }else{
      return $this->get_all_kib_a_e($data);
    }

  }

  function get_all_kib_a_e_limit($data,$mulai)
  {
    $this->db->query('SET @row_number = '.$mulai.';');
    $this->db->query(' SET @input_banyak = 0;');
    $jenis = $this->global_model->kode_jenis[$data['kode_jenis']];

    if ($data['id_pengguna'] != '')  $pengguna = $this->db->get_where('view_pengguna', array('id_pengguna' => $data['id_pengguna'],))->row()->pengguna;
    if ($data['id_kuasa_pengguna'] != '')  $kuasa_pengguna = $this->db->get_where('view_kuasa_pengguna', array('id_pengguna' => $data['id_pengguna'], 'id_kuasa_pengguna' => $data['id_kuasa_pengguna']))->row()->kuasa_pengguna;

    $prefix = $data['prefix'];
    // KEPERLUAN MIGRASI DI MATIKAN DULU (INPUT BANYAK)
    /*
    if ($data['kode_jenis'] == "02") { //KIB B ->input_banyak
      $this->db->select('case when ifnull(A.input_banyak, 0) <>@input_banyak then (@row_number:=@row_number + 1) end AS no,
	    @input_banyak:=ifnull(A.input_banyak, 0),
      `A`.id_kib_b,`A`.id_pemilik, A.id_kode_barang, A.id_kode_lokasi, A.kode_barang,
      A.nama_barang, group_concat(A.nomor_register order by A.nomor_register asc) as nomor_register, A.merk_type, A.ukuran_cc,
      A.bahan, A.tahun_pembelian, A.nomor_pabrik, A.nomor_rangka, A.nomor_mesin,
      A.nomor_polisi, A.nomor_bpkb, A.asal_usul,count(*)jumlah, sum(A.harga)harga, A.keterangan, A.kode_lokasi,
      A.tanggal_transaksi, A.nomor_transaksi, A.tanggal_pembelian,A.tanggal_perolehan,
      A.validasi, A.reject_note, ifnull(A.input_banyak,0),
      B.instansi, C.value');
    } else if ($data['kode_jenis'] == 5) { //KIB E ->input_banyak
      $this->db->select('case when ifnull(A.input_banyak, 0) <>@input_banyak then (@row_number:=@row_number + 1) end AS no,
	      @input_banyak:=ifnull(A.input_banyak, 0),
        A.id_kib_e, A.id_pemilik,A.id_kode_barang,A.id_kode_lokasi,A.nama_barang,A.kode_barang,
        group_concat(A.nomor_register  order by A.nomor_register asc) as  nomor_register,A.judul_pencipta,A.spesifikasi,A.kesenian_asal_daerah,A.kesenian_pencipta,
        A.kesenian_bahan,A.hewan_tumbuhan_jenis,A.hewan_tumbuhan_ukuran,sum(A.jumlah)jumlah,A.tahun_pembelian,
        A.asal_usul,sum(A.harga)harga,A.keterangan,A.kode_lokasi,A.tanggal_transaksi,A.nomor_transaksi,
        A.tanggal_pembelian,A.tanggal_perolehan,A.validasi,A.reject_note,A.input_banyak,
        B.instansi, C.value');
    } else {
      $this->db->select('(@row_number:=@row_number + 1) AS no,A.*, B.instansi, C.value'); //, set_intra_extra(A.kode_lokasi,"'.$data['intra_ekstra'].'") as intra_ekstra_kode_lokasi
    }
    */
    if($data['kode_jenis'] == "01" || $data['kode_jenis'] == "03" || $data['kode_jenis'] == "04"){
      $this->db->select('
        (@row_number:=@row_number + 1) AS no,A.*, YEAR(A.tanggal_perolehan) AS tahun_perolehan,
        case when (A.nama_barang_migrasi is NOT NULL) THEN A.nama_barang_migrasi ELSE A.nama_barang END as nama_barang_desc ,
        B.instansi, C.value, CONCAT(A.latitute,", ",A.longitute) AS latitute', false); //, set_intra_extra(A.kode_lokasi,"'.$data['intra_ekstra'].'") as intra_ekstra_kode_lokasi
    }else{
      $this->db->select('
      (@row_number:=@row_number + 1) AS no,A.*, YEAR(A.tanggal_perolehan) AS tahun_perolehan,
      case when (A.nama_barang_migrasi is NOT NULL) THEN A.nama_barang_migrasi ELSE A.nama_barang END as nama_barang_desc ,
      B.instansi, C.value'); //, set_intra_extra(A.kode_lokasi,"'.$data['intra_ekstra'].'") as intra_ekstra_kode_lokasi
    }

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
    if ($data['kode_jenis'] == '06') $harga_nilaikontrak = 'nilai_kontrak';

    if ($data['intra_ekstra'] == '01') $this->db->where($harga_nilaikontrak . ' >=', 'C.value', FALSE);  //intra
    else if ($data['intra_ekstra'] == '02') $this->db->where($harga_nilaikontrak . ' <', 'C.value', FALSE); //extra

    $this->db->join($this->tbl_kode_lokasi . ' B', 'B.id_kode_lokasi=A.id_kode_lokasi', 'left');
    $this->db->join($this->tbl_master_intra_extra . ' C', 'C.kode_jenis="' . $data['kode_jenis'] . '"', 'left');

    // if ($data['kode_jenis'] == "02" or $data['kode_jenis'] == "05") $this->db->group_by('input_banyak');

    
    $this->db->order_by('kode_barang', 'asc');
    $this->db->order_by('YEAR(tanggal_transaksi)', 'asc');
    $this->db->order_by('YEAR(tanggal_perolehan)', 'asc');
    $this->db->order_by('nomor_register', 'asc');
	$this->db->limit(30000, $mulai);
  
  // $session = $this->session->userdata('session');
  // if($session->id_upik == 'jss-a7324'){
  //   $this->db->get()->result_array();
  //   die($this->db->last_query());
  // }
    return
      $this->db->get()->result_array();
    // die($this->db->last_query());
  }

  function get_all_kib_f($data)
  {
    $this->db->query('SET @row_number = 0;');
    // $jenis = $this->global_model->kode_jenis[$data['kode_jenis']];

    if ($data['id_pengguna'] != '')  $pengguna = $this->db->get_where('view_pengguna', array('id_pengguna' => $data['id_pengguna'],))->row()->pengguna;
    if ($data['id_kuasa_pengguna'] != '')  $kuasa_pengguna = $this->db->get_where('view_kuasa_pengguna', array('id_pengguna' => $data['id_pengguna'], 'id_kuasa_pengguna' => $data['id_kuasa_pengguna']))->row()->kuasa_pengguna;

    $this->db->select('(@row_number:=@row_number + 1) AS no,A.*, B.instansi, C.value'); //, set_intra_extra(A.kode_lokasi,"'.$data['intra_ekstra'].'") as intra_ekstra_kode_lokasi
    $this->db->from('view_kib_f A');
    $this->db->where('validasi', '2');
    $this->db->where('id_pemilik', $data['id_pemilik']);
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

    $harga_nilaikontrak='harga';
    if ($data['kode_jenis'] == '6') $harga_nilaikontrak = 'nilai_kontrak';

    if ($data['intra_ekstra'] == '01') $this->db->where($harga_nilaikontrak . ' >=', 'C.value', FALSE);  //intra
    else if ($data['intra_ekstra'] == '02') $this->db->where($harga_nilaikontrak . ' <', 'C.value', FALSE); //extra

    $this->db->join($this->tbl_kode_lokasi . ' B', 'B.id_kode_lokasi=A.id_kode_lokasi', 'left');
    $this->db->join($this->tbl_master_intra_extra . ' C', 'C.kode_jenis=A.kode_jenis', 'left');
    // if ($data['kode_jenis'] == 2 or $data['kode_jenis'] == 5) $this->db->group_by('input_banyak');
    // die(json_encode($data));
    
    $this->db->order_by('kode_barang', 'asc');
    $this->db->order_by('YEAR(tanggal_transaksi)', 'asc');
    $this->db->order_by('YEAR(tanggal_perolehan)', 'asc');
    $this->db->order_by('nomor_register', 'asc');
    return
      $this->db->get()->result_array();
    // die($this->db->last_query());
  }





  // get kib atb
  function get_kib_aset_lainya($data)
  {
    $this->db->query('SET @row_number = 0;');
    $this->db->query('SET @input_banyak = 0;');
    $this->db->query('SET @id_kode_lokasi = 0;');
    if ($data['id_pengguna'] != '')  $pengguna = $this->db->get_where('view_pengguna', array('id_pengguna' => $data['id_pengguna'],))->row()->pengguna;
    if ($data['id_kuasa_pengguna'] != '')  $kuasa_pengguna = $this->db->get_where('view_kuasa_pengguna', array('id_pengguna' => $data['id_pengguna'], 'id_kuasa_pengguna' => $data['id_kuasa_pengguna']))->row()->kuasa_pengguna;

    // $this->db->select('(@row_number:=@row_number + 1) AS no,A.*, B.instansi, C.value');

    /*   INPUT BANYAK HILANGKAN DULU 15 JULI 2020
    $this->db->select('case when ifnull(A.input_banyak, 0)=0 or ifnull(A.input_banyak, 0) <> @input_banyak or @id_kode_lokasi <> A.id_kode_lokasi  then (@row_number:=@row_number + 1) end AS no,
      @input_banyak:=ifnull(A.input_banyak, 0), (@id_kode_lokasi := A.id_kode_lokasi),
      A.kode_jenis,A.jenis_name,A.id_kib,A.id_pemilik,A.id_kode_lokasi,A.kode_barang,
      A.nama_barang,group_concat(A.nomor_register  order by A.nomor_register asc)nomor_register,A.kode_lokasi,A.keterangan,A.asal_usul,A.tanggal_perolehan,
      sum(A.harga)harga,A.merk_type,A.sertifikat_nomor,A.bahan,A.ukuran_barang,A.satuan,A.keadaan_barang,
      count(A.jumlah_barang)jumlah_barang,A.input_banyak,
      B.instansi, C.value');*/
    $this->db->select(' (@row_number:=@row_number + 1) AS no , A.kode_jenis,
      A.id_kib, A.id_pemilik, A.id_kode_lokasi, A.kode_barang, 
      CONCAT(A.nama_barang," (",A.deskripsi,")") AS nama_barang, nomor_register, 
      A.kode_lokasi, A.keterangan, A.asal_usul, A.tanggal_perolehan, A.harga,
      A.merk_type, A.sertifikat_nomor, A.bahan, A.ukuran_barang, A.satuan, 
      A.keadaan_barang, 1 as jumlah_barang,
      A.input_banyak, B.instansi ');
    $this->db->from(" view_kib_asetlainnya A ");
    $this->db->join($this->tbl_kode_lokasi . ' B', 'B.id_kode_lokasi=A.id_kode_lokasi', 'left');
    $this->db->join($this->tbl_kode_barang . ' C', 'C.id_kode_barang=A.id_kode_barang', 'left');
    $this->db->where('validasi', '2');
    if($data['kode_jenis'] == '5.04'){
      $this->db->where('C.kode_kelompok', '5');
      $this->db->where('C.kode_jenis', '04');
    }
    if($data['kode_jenis'] == '5.02'){
      $this->db->where('C.kode_kelompok', '5');
      $this->db->where('C.kode_jenis', '02');
    }
    $this->db->where('A.id_pemilik', $data['id_pemilik']);
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
      $this->db->where('tanggal_perolehan >= ', $data['start_date']);
    if ($data['last_date'] != '')
      $this->db->where('tanggal_perolehan <= ', $data['last_date']);

    $harga_nilaikontrak = 'harga';
    // if ($data['kode_jenis'] == '6') $harga_nilaikontrak = 'nilai_kontrak'; tidak dipakai karena pakai union

    if ($data['intra_ekstra'] == '01') $this->db->where($harga_nilaikontrak . ' >=', 'C.value', FALSE);  //intra
    else if ($data['intra_ekstra'] == '02') $this->db->where($harga_nilaikontrak . ' <', 'C.value', FALSE); //extra

    // $this->db->join($this->tbl_master_intra_extra . ' C', 'C.kode_jenis=A.kode_jenis', 'left');
    // $this->db->from(' (SELECT @no:=0) as var ');
    /* abaikan input banyak , kepentingan laporan pertama sehabis migrasi
    $this->db->group_by('input_banyak, kode_jenis, kode_barang, id_kode_lokasi');
    */
    // $this->db->group_by('kode_jenis, kode_barang, id_kode_lokasi');
    // $this->db->group_by('`kode_jenis`, `kode_barang`, `id_kode_lokasi`, asal_usul, tanggal_perolehan, harga, merk_type, sertifikat_nomor,  bahan, ukuran_barang, satuan, keadaan_barang');
    // $this->db->order_by('kode_jenis', 'asc');
    $this->db->order_by('kode_barang', 'asc');
    $this->db->order_by('YEAR(tanggal_perolehan)', 'asc');
    $this->db->order_by('YEAR(tanggal_perolehan)', 'asc');
    $this->db->order_by('nomor_register', 'asc');
    
  // $session = $this->session->userdata('session');
  // if($session->id_upik == 'jss-a7324'){
  //   $this->db->get()->result_array();
  //   die($this->db->last_query());
  // }

    return
      $this->db->get()->result_array();
    // die($this->db->last_query());
  }



  // get data by id
  function get_by_id($id){
    $this->db->where($this->id, $id);
    return $this->db->get($this->table)->row();
  }

  // get total rows
  function total_rows($q = NULL) {
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
  function get_limit_data($limit, $start = 0, $q = NULL) {
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

}
