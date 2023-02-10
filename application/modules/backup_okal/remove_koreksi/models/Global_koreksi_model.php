<?php

if (!defined('BASEPATH')) exit('No direct script access allowed');

class Global_koreksi_model extends CI_Model{
  public $order = 'DESC';
  public $tbl_kode_barang = 'tbl_kode_barang';
  public $tbl_master_kapitalisasi = 'tbl_master_kapitalisasi';

/*
  public $view_kib = 'view_kib';
  public $tbl_penghapusan = 'tbl_penghapusan';
  public $id_penghapusan = 'id_penghapusan';
  public $tbl_penghapusan_barang = 'tbl_penghapusan_barang';
  public $id_penghapusan_barang = 'id_penghapusan_barang';
  public $tbl_penghapusan_picture = 'tbl_penghapusan_picture';
  public $id_penghapusan_picture = 'id_penghapusan_picture';
  public $tbl_kode_lokasi = 'tbl_kode_lokasi';
  public $id_kode_lokasi = 'id_kode_lokasi';*/

  public $kib=null;

  function __construct(){
    parent::__construct();
    $this->kib=$this->global_model->kode_jenis;
  }
/*
  // datatables
  function json_kib_a($pihak=null) {
      $this->load->helper('my_datatable');
      $id_jenis='1';
      $kib = $this->kib[$id_jenis];
      $this->datatables->select('id_kib_a,A.nama_barang,A.kode_barang,nomor_register,
        luas,tahun_pengadaan,letak_alamat,status_hak,sertifikat_tanggal,sertifikat_nomor,
        penggunaan,asal_usul,harga,keterangan,kode_lokasi, B.nama_barang as nama_barang2,
        C.id_penghapusan_barang,C.id_penghapusan, C.status_diterima');
      $this->datatables->from($kib['table'].' A');
      $this->datatables->join($this->tbl_kode_barang.' B', 'A.kode_barang = B.kode_barang');
      $this->datatables->join($this->tbl_penghapusan_barang.' C', 'C.kode_jenis="'.$id_jenis.'" and A.'.$kib['id_name'].' = C.id_kib', 'left');
      $this->datatables->join($this->tbl_penghapusan.' D', 'D.id_penghapusan = C.id_penghapusan', 'left');
      $this->datatables->where('A.validasi','2');

      if ($pihak == 'list_barang') {
        $this->datatables->where('A.id_kode_lokasi',$this->session->userdata('session')->id_kode_lokasi);
        // $this->datatables->where('ifnull(C.id_penghapusan,0)','0',false);// yang di tampilkan yang belum di mutasi
        $this->datatables->where('A.status_barang','kib');
      }
      else if ($pihak == 'pengecekan_barang'){
        $this->datatables->where('C.id_penghapusan',$this->input->post('id_penghapusan'),false);
        // $this->datatables->where('A.id_kode_lokasi',$this->session->userdata('session')->id_kode_lokasi,false);
        $this->datatables->add_column('radio_terima', '$1', 'radio_pengecekan_barang(id_penghapusan_barang,status_diterima,2)');
        $this->datatables->add_column('radio_tolak', '$1', 'radio_pengecekan_barang(id_penghapusan_barang,status_diterima,3)');
      }
      else if ($pihak == 'penghapusan_barang'){
        $this->datatables->where('status_diterima','2',false);
      }
      else if ($pihak == 'laporan'){
        $this->datatables->where('status_diterima','2',false);
        $this->datatables->where('A.status_barang','penghapusan_diterima');
      }

      $this->datatables->edit_column('luas','$1','format_number(luas)');
      $this->datatables->edit_column('sertifikat_tanggal','$1','date_time(sertifikat_tanggal)');
      $this->datatables->edit_column('harga','$1','format_number(harga)');
      // $this->datatables->edit_column('status_validasi', '$1', 'status_validasi(id_mutasi_barang,status_validasi)');

      return $this->datatables->generate();
      // $this->datatables->generate(); die($this->db->last_query());

  }
*/
  // datatables
  function json_kib_b($pihak=null) {
    $this->load->helper('my_datatable');
    $id_jenis='2';
    $kib = $this->kib[$id_jenis];

    $this->datatables->select('id_kib_b,A.kode_barang,A.nama_barang,nomor_register,merk_type,
      ukuran_cc,bahan,tahun_pembelian,nomor_pabrik,nomor_rangka,nomor_mesin,nomor_polisi,
      nomor_bpkb,asal_usul,harga,keterangan,kode_lokasi, B.nama_barang as nama_barang2,
      C.id_penghapusan_barang,C.id_penghapusan, C.status_diterima');
    $this->datatables->from($kib['table'].' A');
    $this->datatables->join($this->tbl_kode_barang.' B', 'A.kode_barang = B.kode_barang');
    $this->datatables->join($this->tbl_penghapusan_barang.' C', 'C.kode_jenis="'.$id_jenis.'" and A.'.$kib['id_name'].' = C.id_kib', 'left');
    $this->datatables->join($this->tbl_penghapusan.' D', 'D.id_penghapusan = C.id_penghapusan', 'left');
    $this->datatables->where('A.validasi','2');
    if ($pihak == 'list_barang') {
      $this->datatables->where('A.id_kode_lokasi',$this->session->userdata('session')->id_kode_lokasi);
      // $this->datatables->where('ifnull(C.id_penghapusan,0)','0',false);// yang di tampilkan yang belum di mutasi
      $this->datatables->where('A.status_barang','kib');
    }
    else if ($pihak == 'pengecekan_barang'){
      $this->datatables->where('C.id_penghapusan',$this->input->post('id_penghapusan'),false);
      // $this->datatables->where('A.id_kode_lokasi',$this->session->userdata('session')->id_kode_lokasi,false);
      $this->datatables->add_column('radio_terima', '$1', 'radio_pengecekan_barang(id_penghapusan_barang,status_diterima,2)');
      $this->datatables->add_column('radio_tolak', '$1', 'radio_pengecekan_barang(id_penghapusan_barang,status_diterima,3)');
    }
    else if ($pihak == 'penghapusan_barang'){
      $this->datatables->where('status_diterima','2',false);
    }
    else if ($pihak == 'laporan'){
      $this->datatables->where('status_diterima','2',false);
      $this->datatables->where('A.status_barang','penghapusan_diterima');
    }

    $this->datatables->edit_column('ukuran_cc','$1','format_number(ukuran_cc)');
    $this->datatables->edit_column('harga','$1','format_number(harga)');
    // $this->datatables->edit_column('status_validasi', '$1', 'status_validasi(id_mutasi_barang,status_validasi)');
    return $this->datatables->generate();
    // $this->datatables->generate(); die($this->db->last_query());
  }

  // datatables
  function json_kib_c($pihak=null) {
    $this->load->helper('my_datatable');
    $id_jenis='3';
    $kib = $this->kib[$id_jenis];
    $this->datatables->select('id_kib_c,A.nama_barang,A.kode_barang,nomor_register,
      kondisi_bangunan,bangunan_bertingkat,bangunan_beton,luas_lantai_m2,lokasi_alamat,
      gedung_tanggal,gedung_nomor,luas_m2,status,nomor_kode_tanah,asal_usul,harga,keterangan,
      B.nama_barang as nama_barang2, validasi,
      C.id_penghapusan_barang,C.id_penghapusan, C.status_diterima');
    $this->datatables->from($kib['table'].' A');
    $this->datatables->join($this->tbl_kode_barang.' B', 'A.kode_barang = B.kode_barang');
    $this->datatables->join($this->tbl_penghapusan_barang.' C', 'C.kode_jenis="'.$id_jenis.'" and A.'.$kib['id_name'].' = C.id_kib', 'left');
    $this->datatables->join($this->tbl_penghapusan.' D', 'D.id_penghapusan = C.id_penghapusan', 'left');
    $this->datatables->where('A.validasi','2');
    if ($pihak == 'list_barang') {
      $this->datatables->where('A.id_kode_lokasi',$this->session->userdata('session')->id_kode_lokasi);
      // $this->datatables->where('ifnull(C.id_penghapusan,0)','0',false);// yang di tampilkan yang belum di mutasi
      $this->datatables->where('A.status_barang','kib');
    }
    else if ($pihak == 'pengecekan_barang'){
      $this->datatables->where('C.id_penghapusan',$this->input->post('id_penghapusan'),false);
      // $this->datatables->where('A.id_kode_lokasi',$this->session->userdata('session')->id_kode_lokasi,false);
      $this->datatables->add_column('radio_terima', '$1', 'radio_pengecekan_barang(id_penghapusan_barang,status_diterima,2)');
      $this->datatables->add_column('radio_tolak', '$1', 'radio_pengecekan_barang(id_penghapusan_barang,status_diterima,3)');
    }
    else if ($pihak == 'penghapusan_barang'){
      $this->datatables->where('status_diterima','2',false);
    }
    else if ($pihak == 'laporan'){
      $this->datatables->where('status_diterima','2',false);
      $this->datatables->where('A.status_barang','penghapusan_diterima');
    }

    // $this->datatables->add_column('action', '$1', 'get_action(kib_c, id_kib_c, validasi)');
    $this->datatables->edit_column('luas_lantai_m2','$1','format_number(luas_lantai_m2)');
    $this->datatables->edit_column('luas_m2','$1','format_number(luas_m2)');
    $this->datatables->edit_column('harga','$1','format_number(harga)');
    $this->datatables->edit_column('gedung_tanggal','$1','date_time(gedung_tanggal)');
    // $this->datatables->edit_column('status_validasi', '$1', 'status_validasi(id_mutasi_barang,status_validasi)');
    return $this->datatables->generate();
  }

  // datatables
  function json_kib_d($pihak=null) {
    $this->load->helper('my_datatable');
    $id_jenis='4';
    $kib = $this->kib[$id_jenis];
    $this->datatables->select('id_kib_d,nama_barang,kode_barang,nomor_register,konstruksi,
      panjang_km,lebar_m,luas_m2,letak_lokasi,dokumen_tanggal,dokumen_nomor,status_tanah,
      kode_tanah,asal_usul,harga,kondisi,keterangan,kode_lokasi,validasi,
      C.id_penghapusan_barang,C.id_penghapusan, C.status_diterima');
    $this->datatables->from($kib['table'].' A');
    $this->datatables->join($this->tbl_penghapusan_barang.' C', 'C.kode_jenis="'.$id_jenis.'" and A.'.$kib['id_name'].' = C.id_kib', 'left');
    $this->datatables->join($this->tbl_penghapusan.' D', 'D.id_penghapusan = C.id_penghapusan', 'left');
    $this->datatables->where('A.validasi','2');
    if ($pihak == 'list_barang') {
      $this->datatables->where('A.id_kode_lokasi',$this->session->userdata('session')->id_kode_lokasi);
      // $this->datatables->where('ifnull(C.id_penghapusan,0)','0',false);// yang di tampilkan yang belum di mutasi
      $this->datatables->where('A.status_barang','kib');
    }
    else if ($pihak == 'pengecekan_barang'){
      $this->datatables->where('C.id_penghapusan',$this->input->post('id_penghapusan'),false);
      // $this->datatables->where('A.id_kode_lokasi',$this->session->userdata('session')->id_kode_lokasi,false);
      $this->datatables->add_column('radio_terima', '$1', 'radio_pengecekan_barang(id_penghapusan_barang,status_diterima,2)');
      $this->datatables->add_column('radio_tolak', '$1', 'radio_pengecekan_barang(id_penghapusan_barang,status_diterima,3)');
    }
    else if ($pihak == 'penghapusan_barang'){
      $this->datatables->where('status_diterima','2',false);
    }
    else if ($pihak == 'laporan'){
      $this->datatables->where('status_diterima','2',false);
      $this->datatables->where('A.status_barang','penghapusan_diterima');
    }

    // $this->datatables->add_column('action', '$1', 'get_action(kib_d, id_kib_d, validasi)');
    $this->datatables->edit_column('panjang_km','$1','format_number(panjang_km)');
    $this->datatables->edit_column('lebar_m','$1','format_number(lebar_m)');
    $this->datatables->edit_column('luas_m2','$1','format_number(luas_m2)');
    $this->datatables->edit_column('dokumen_tanggal','$1','date_time(dokumen_tanggal)');
    $this->datatables->edit_column('harga','$1','format_number(harga)');
    // $this->datatables->edit_column('status_validasi', '$1', 'status_validasi(id_mutasi_barang,status_validasi)');
    return $this->datatables->generate();
  }

  public function get_role_kapitalisasi($id_kode_barang){
    $this->db->select('B.*');
    $this->db->from($this->tbl_kode_barang.' A');
    $this->db->join($this->tbl_master_kapitalisasi.' B','A.kelompok_manfaat=B.kelompok_manfaat','left');
    $this->db->where('id_kode_barang',$id_kode_barang);
    return $this->db->get()->row();
  }

/*
  // datatables
  function json_kib_e($pihak=null) {
    $this->load->helper('my_datatable');
    $id_jenis='5';
    $kib = $this->kib[$id_jenis];
    $this->datatables->select('id_kib_e,A.nama_barang,A.kode_barang,nomor_register,
      judul_pencipta,spesifikasi,kesenian_asal_daerah,kesenian_pencipta,kesenian_bahan,
      hewan_tumbuhan_jenis,hewan_tumbuhan_ukuran,jumlah,tahun_pembelian,asal_usul,harga,
      keterangan, kode_lokasi,validasi,
      C.id_penghapusan_barang,C.id_penghapusan, C.status_diterima');
    $this->datatables->from($kib['table'].' A');
    $this->datatables->join($this->tbl_penghapusan_barang.' C', 'C.kode_jenis="'.$id_jenis.'" and A.'.$kib['id_name'].' = C.id_kib', 'left');
    $this->datatables->join($this->tbl_penghapusan.' D', 'D.id_penghapusan = C.id_penghapusan', 'left');
    $this->datatables->where('A.validasi','2');
    if ($pihak == 'list_barang') {
      $this->datatables->where('A.id_kode_lokasi',$this->session->userdata('session')->id_kode_lokasi);
      // $this->datatables->where('ifnull(C.id_penghapusan,0)','0',false);// yang di tampilkan yang belum di mutasi
      $this->datatables->where('A.status_barang','kib');
    }
    else if ($pihak == 'pengecekan_barang'){
      $this->datatables->where('C.id_penghapusan',$this->input->post('id_penghapusan'),false);
      // $this->datatables->where('A.id_kode_lokasi',$this->session->userdata('session')->id_kode_lokasi,false);
      $this->datatables->add_column('radio_terima', '$1', 'radio_pengecekan_barang(id_penghapusan_barang,status_diterima,2)');
      $this->datatables->add_column('radio_tolak', '$1', 'radio_pengecekan_barang(id_penghapusan_barang,status_diterima,3)');
    }
    else if ($pihak == 'penghapusan_barang'){
      $this->datatables->where('status_diterima','2',false);
    }
    else if ($pihak == 'laporan'){
      $this->datatables->where('status_diterima','2',false);
      $this->datatables->where('A.status_barang','penghapusan_diterima');
    }

    // $this->datatables->add_column('action', '$1', 'get_action(kib_e, id_kib_e, validasi)');
    $this->datatables->edit_column('hewan_tumbuhan_ukuran','$1','format_number(hewan_tumbuhan_ukuran)');
    $this->datatables->edit_column('jumlah','$1','format_number(jumlah)');
    $this->datatables->edit_column('harga','$1','format_number(harga)');
    // $this->datatables->edit_column('status_validasi', '$1', 'status_validasi(id_mutasi_barang,status_validasi)');

    return $this->datatables->generate();
  }
*/

/*
  // datatables
  function json_kib_f($pihak=null) {
    $this->load->helper('my_datatable');
    $id_jenis='6';
    $kib = $this->kib[$id_jenis];
    $this->datatables->select('id_kib_f,kode_barang,nama_barang,bangunan,kontruksi_bertingkat,
      kontruksi_beton,luas_m2,lokasi_alamat,dokumen_tanggal,dokumen_nomor,tanggal_mulai,
      status_tanah,nomor_kode_tanah,asal_usul,nilai_kontrak,keterangan,kode_lokasi,validasi,
      C.id_penghapusan_barang,C.id_penghapusan, C.status_diterima');
    $this->datatables->from($kib['table'].' A');
    $this->datatables->join($this->tbl_penghapusan_barang.' C', 'C.kode_jenis="'.$id_jenis.'" and A.'.$kib['id_name'].' = C.id_kib', 'left');
    $this->datatables->join($this->tbl_penghapusan.' D', 'D.id_penghapusan = C.id_penghapusan', 'left');
    $this->datatables->where('A.validasi','2');
    if ($pihak == 'list_barang') {
      $this->datatables->where('A.id_kode_lokasi',$this->session->userdata('session')->id_kode_lokasi);
      $this->datatables->where('ifnull(C.id_penghapusan,0)','0',false);// yang di tampilkan yang belum di mutasi
    }
    else if ($pihak == 'pengecekan_barang'){
    $this->datatables->where('C.id_penghapusan',$this->input->post('id_penghapusan'),false);
    // $this->datatables->where('A.id_kode_lokasi',$this->session->userdata('session')->id_kode_lokasi,false);
    $this->datatables->add_column('radio_terima', '$1', 'radio_pengecekan_barang(id_penghapusan_barang,status_diterima,2)');
    $this->datatables->add_column('radio_tolak', '$1', 'radio_pengecekan_barang(id_penghapusan_barang,status_diterima,3)');
    }
    else if ($pihak == 'penghapusan_barang'){
      $this->datatables->where('status_diterima','2',false);
    }
    else if ($pihak == 'laporan'){
      $this->datatables->where('status_diterima','2',false);
    }

    // $this->datatables->add_column('action', '$1', 'get_action(kib_f, id_kib_f, validasi)');
    $this->datatables->edit_column('luas_m2','$1','format_number(luas_m2)');
    $this->datatables->edit_column('dokumen_tanggal','$1','date_time(dokumen_tanggal)');
    $this->datatables->edit_column('tanggal_mulai','$1','date_time(tanggal_mulai)');
    $this->datatables->edit_column('nilai_kontrak','$1','format_number(nilai_kontrak)');
    // $this->datatables->edit_column('status_validasi', '$1', 'status_validasi(id_mutasi_barang,status_validasi)');
    return $this->datatables->generate();
  }
*/
/*
  public function get_penghapusan($id_penghapusan){
    $this->db->select('A.id_penghapusan, A.tanggal_pengajuan, B.instansi');
    $this->db->from($this->tbl_penghapusan.' A');
    $this->db->join($this->tbl_kode_lokasi.' B', 'A.id_kode_lokasi=B.id_kode_lokasi', 'left');
    $this->db->where($this->id_penghapusan, $id_penghapusan);
    return $this->db->get()->row();
  }

  public function get_picture($id_penghapusan){
    return $this->db->get_where($this->tbl_penghapusan_picture, array($this->id_penghapusan => $id_penghapusan, ))->result();
  }

  public function get_kode_aset_lain_lain(){
    $data = array(
      'kode_akun'=>'1',
      'kode_kelompok'=>'5',
      'kode_jenis'=>'4',
      'kode_objek'=>'01',
      'kode_rincian_objek'=>'01',
      'kode_sub_rincian_objek'=>'01',
      'kode_sub_sub_rincian_objek<>'=>'*',
    );
    $result = $this->db->get_where($this->tbl_kode_barang, $data)->result();
    $return=array();
    foreach ($result as $key => $value) {
      $return[substr($value->kode_sub_sub_rincian_objek,2,1)] = $value->id_kode_barang;
    }

    return $return;
  }
*/

}
