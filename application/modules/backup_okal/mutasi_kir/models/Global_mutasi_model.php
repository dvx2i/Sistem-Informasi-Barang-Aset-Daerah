<?php

if (!defined('BASEPATH')) exit('No direct script access allowed');

class Global_mutasi_model extends CI_Model
{
  public $order = 'DESC';
  public $tbl_kode_barang = 'tbl_kode_barang';


  public $view_kib = 'view_kib';
  public $tbl_mutasi = 'tbl_mutasi_kir';
  public $id_mutasi = 'id_mutasi';
  public $tbl_mutasi_barang = 'tbl_mutasi_barang_kir';
  public $id_mutasi_barang = 'id_mutasi_barang';
  public $tbl_mutasi_picture = 'tbl_mutasi_picture';

  public $tbl_kode_lokasi = 'tbl_kode_lokasi';
  public $kib = null;

  function __construct()
  {
    parent::__construct();
    $this->kib = $this->global_model->kode_jenis;
  }

  // datatables
  function json_kib_a($pihak = null, $data = null)
  {
    $this->load->helper('my_datatable');
    $id_jenis = '01';
    $kib = $this->kib[$id_jenis];
    $this->datatables->select('id_kib_a,A.nama_barang, A.nama_barang_migrasi,
        A.nama_barang as nama_barang_desc,
        A.kode_barang,nomor_register,luas,tahun_pengadaan,letak_alamat,status_hak,sertifikat_tanggal,sertifikat_nomor,
        penggunaan,asal_usul,harga,keterangan,kode_lokasi, B.nama_barang as nama_barang2,
        validasi,
        C.id_mutasi_barang,status_diterima,status_validasi, id_kode_lokasi_lama, id_kode_lokasi_baru, id_inventaris');
    $this->datatables->from($kib['table'] . ' A');
    $this->datatables->join($this->tbl_kode_barang . ' B', 'A.id_kode_barang = B.id_kode_barang');

    $this->datatables->join($this->tbl_mutasi_barang . ' C', 'C.kode_jenis="' . $id_jenis . '" and A.' . $kib['id_name'] . ' = C.id_kib', 'left');
    $this->datatables->join($this->tbl_mutasi . ' D', 'D.id_mutasi = C.id_mutasi', 'left');

    $this->datatables->where('A.validasi', '2');
    // $this->datatables->where('A.status_barang', 'kib'); // karena di pengecekan status barang adalah penerimaan
    $this->datatables->where(" (id_kode_barang_aset_lainya < 1 and A.status_barang <> 'aset_lainya') ", NULL, FALSE);


    if ($pihak == 'list_barang') { //pengajuan
      $this->datatables->where('A.id_kode_lokasi', $this->session->userdata('session')->id_kode_lokasi);
      // $this->datatables->where('ifnull(C.id_mutasi,0)','0',false);
      $this->datatables->where('A.status_barang', 'kib');
      if ($data) {
        foreach (json_decode($data) as $key => $value) {
          $this->datatables->where('A.id_kib_a <> ', $value);
        }
      }
    } else if ($pihak == 'pengecekan_barang') {
      // $this->datatables->where('status_pengajuan','2',false);
      $this->datatables->where('C.id_mutasi', $this->input->post('id_mutasi'), false);
      if($this->session->userdata('session')->id_role != '1'){  
      $this->datatables->where('id_kode_lokasi_baru', $this->session->userdata('session')->id_kode_lokasi, false);
      }
      $this->datatables->add_column('radio_terima', '$1', 'radio_pengecekan_barang(id_mutasi_barang,status_diterima,2)');
      $this->datatables->add_column('radio_tolak', '$1', 'radio_pengecekan_barang(id_mutasi_barang,status_diterima,3)');
    }

    $this->datatables->edit_column('luas', '$1', 'format_number(luas)');
    $this->datatables->edit_column('sertifikat_tanggal', '$1', 'date_time(sertifikat_tanggal)');
    $this->datatables->edit_column('harga', '$1', 'format_float(harga)');
    $this->datatables->edit_column('status_validasi', '$1', 'status_validasi(id_mutasi_barang,status_validasi)');

    return $this->datatables->generate();
    // $this->datatables->generate();
    // die($this->db->last_query());
  }

  // datatables
  function json_kib_b($pihak = null, $data = null)
  {
    $this->load->helper('my_datatable');
    $id_jenis = '02';
    $kib = $this->kib[$id_jenis];

    $this->datatables->select('id_kib_b,A.kode_barang,A.nama_barang, A.nama_barang_migrasi,
    A.nama_barang as nama_barang_desc,
      nomor_register,merk_type,
      ukuran_cc,bahan,tahun_pembelian,nomor_pabrik,nomor_rangka,nomor_mesin,nomor_polisi,
      nomor_bpkb,asal_usul,harga,keterangan,kode_lokasi, B.nama_barang as nama_barang2,
      validasi,C.id_mutasi_barang,status_diterima,status_validasi, id_kode_lokasi_lama, id_kode_lokasi_baru, id_inventaris');
    $this->datatables->from($kib['table'] . ' A');
    $this->datatables->join($this->tbl_kode_barang . ' B', 'A.id_kode_barang = B.id_kode_barang');
    $this->datatables->join($this->tbl_mutasi_barang . ' C', 'C.kode_jenis="' . $id_jenis . '" and A.' . $kib['id_name'] . ' = C.id_kib', 'left');
    $this->datatables->join($this->tbl_mutasi . ' D', 'D.id_mutasi = C.id_mutasi', 'left');
    $this->datatables->where('A.validasi', '2');
    // $this->datatables->where('A.status_barang', 'kib');
    $this->datatables->where(" (id_kode_barang_aset_lainya < 1 and A.status_barang <> 'aset_lainya') ", NULL, FALSE);

    if ($pihak == 'list_barang') { //pengajuan
      $this->datatables->where('A.id_kode_lokasi', $this->session->userdata('session')->id_kode_lokasi);
      // $this->datatables->where('ifnull(C.id_mutasi,0)','0',false);
      $this->datatables->where('A.status_barang', 'kib');
      if ($data) {
        foreach (json_decode($data) as $key => $value) {
          $this->datatables->where('A.id_kib_b <> ', $value);
        }
      }
    } else if ($pihak == 'pengecekan_barang') {
      $this->datatables->where('C.id_mutasi', $this->input->post('id_mutasi'), false);
      if($this->session->userdata('session')->id_role != '1'){  
      $this->datatables->where('id_kode_lokasi_baru', $this->session->userdata('session')->id_kode_lokasi, false);
      }
      $this->datatables->add_column('radio_terima', '$1', 'radio_pengecekan_barang(id_mutasi_barang,status_diterima,2)');
      $this->datatables->add_column('radio_tolak', '$1', 'radio_pengecekan_barang(id_mutasi_barang,status_diterima,3)');
    }
    //$this->datatables->edit_column('ukuran_cc', '$1', 'format_number(ukuran_cc)');
    $this->datatables->edit_column('harga', '$1', 'format_float(harga)');
    $this->datatables->edit_column('status_validasi', '$1', 'status_validasi(id_mutasi_barang,status_validasi)');
    return $this->datatables->generate();
   //$this->datatables->generate(); die($this->db->last_query());
  }

  // datatables
  function json_kib_c($pihak = null, $data = null)
  {
    $this->load->helper('my_datatable');
    $id_jenis = '03';
    $kib = $this->kib[$id_jenis];
    $this->datatables->select('id_kib_c,A.nama_barang, A.nama_barang_migrasi,
      A.nama_barang as nama_barang_desc,
      A.kode_barang,nomor_register,
      kondisi_bangunan,bangunan_bertingkat,bangunan_beton,luas_lantai_m2,lokasi_alamat,
      gedung_tanggal,gedung_nomor,luas_m2,status,nomor_kode_tanah,asal_usul,harga,keterangan,
      B.nama_barang as nama_barang2, validasi,C.id_mutasi_barang,
      status_diterima,status_validasi, id_kode_lokasi_lama, id_kode_lokasi_baru, id_inventaris');
    $this->datatables->from($kib['table'] . ' A');
    $this->datatables->join($this->tbl_kode_barang . ' B', 'A.id_kode_barang = B.id_kode_barang');
    $this->datatables->join($this->tbl_mutasi_barang . ' C', 'C.kode_jenis="' . $id_jenis . '" and A.' . $kib['id_name'] . ' = C.id_kib', 'left');
    $this->datatables->join($this->tbl_mutasi . ' D', 'D.id_mutasi = C.id_mutasi', 'left');
     $this->datatables->where('A.validasi', '2');
    // $this->datatables->where('A.status_barang', 'kib');
    $this->datatables->where(" (id_kode_barang_aset_lainya < 1 and A.status_barang <> 'aset_lainya') ", NULL, FALSE);

    if ($pihak == 'list_barang') { //pengajuan
      $this->datatables->where('A.id_kode_lokasi', $this->session->userdata('session')->id_kode_lokasi);
      // $this->datatables->where('ifnull(C.id_mutasi,0)','0',false);
      $this->datatables->where('A.status_barang', 'kib');
      if ($data) {
        foreach (json_decode($data) as $key => $value) {
          $this->datatables->where('A.id_kib_c <> ', $value);
        }
      }
    } else if ($pihak == 'pengecekan_barang') {
      $this->datatables->where('C.id_mutasi', $this->input->post('id_mutasi'), false);
      if($this->session->userdata('session')->id_role != '1'){  
      $this->datatables->where('id_kode_lokasi_baru', $this->session->userdata('session')->id_kode_lokasi, false);
      }
      $this->datatables->add_column('radio_terima', '$1', 'radio_pengecekan_barang(id_mutasi_barang,status_diterima,2)');
      $this->datatables->add_column('radio_tolak', '$1', 'radio_pengecekan_barang(id_mutasi_barang,status_diterima,3)');
    }
    $this->datatables->add_column('action', '$1', 'get_action(kib_c, id_kib_c, validasi)');
    $this->datatables->edit_column('luas_lantai_m2', '$1', 'format_number(luas_lantai_m2)');
    $this->datatables->edit_column('luas_m2', '$1', 'format_number(luas_m2)');
    $this->datatables->edit_column('harga', '$1', 'format_float(harga)');
    $this->datatables->edit_column('gedung_tanggal', '$1', 'date_time(gedung_tanggal)');
    $this->datatables->edit_column('status_validasi', '$1', 'status_validasi(id_mutasi_barang,status_validasi)');
    return $this->datatables->generate();
  }

  // datatables
  function json_kib_d($pihak = null, $data = null)
  {
    $this->load->helper('my_datatable');
    $id_jenis = '04';
    $kib = $this->kib[$id_jenis];
    $this->datatables->select('id_kib_d,A.nama_barang, A.nama_barang_migrasi,
      A.nama_barang as nama_barang_desc,
      A.kode_barang,nomor_register,konstruksi,
      panjang_km,lebar_m,luas_m2,letak_lokasi,dokumen_tanggal,dokumen_nomor,status_tanah,
      kode_tanah,asal_usul,harga,kondisi,keterangan,B.nama_barang as nama_barang2,kode_lokasi,validasi,C.id_mutasi_barang,
      status_diterima,status_validasi, id_kode_lokasi_lama, id_kode_lokasi_baru, id_inventaris');
    $this->datatables->from($kib['table'] . ' A');
	 $this->datatables->join($this->tbl_kode_barang . ' B', 'A.id_kode_barang = B.id_kode_barang');
    $this->datatables->join($this->tbl_mutasi_barang . ' C', 'C.kode_jenis="' . $id_jenis . '" and A.' . $kib['id_name'] . ' = C.id_kib', 'left');
    $this->datatables->join($this->tbl_mutasi . ' D', 'D.id_mutasi = C.id_mutasi', 'left');
    $this->datatables->where('A.validasi', '2');
    // $this->datatables->where('A.status_barang', 'kib');
    $this->datatables->where(" (id_kode_barang_aset_lainya < 1 and A.status_barang <> 'aset_lainya') ", NULL, FALSE);


    if ($pihak == 'list_barang') { //pengajuan
      $this->datatables->where('A.id_kode_lokasi', $this->session->userdata('session')->id_kode_lokasi);
      // $this->datatables->where('ifnull(C.id_mutasi,0)','0',false);
      $this->datatables->where('A.status_barang', 'kib');
      if ($data) {
        foreach (json_decode($data) as $key => $value) {
          $this->datatables->where('A.id_kib_d <> ', $value);
        }
      }
    } else if ($pihak == 'pengecekan_barang') {
      $this->datatables->where('C.id_mutasi', $this->input->post('id_mutasi'), false);
      if($this->session->userdata('session')->id_role != '1'){  
      $this->datatables->where('id_kode_lokasi_baru', $this->session->userdata('session')->id_kode_lokasi, false);
      }
      $this->datatables->add_column('radio_terima', '$1', 'radio_pengecekan_barang(id_mutasi_barang,status_diterima,2)');
      $this->datatables->add_column('radio_tolak', '$1', 'radio_pengecekan_barang(id_mutasi_barang,status_diterima,3)');
    }
    $this->datatables->add_column('action', '$1', 'get_action(kib_d, id_kib_d, validasi)');
    $this->datatables->edit_column('panjang_km', '$1', 'format_number(panjang_km)');
    $this->datatables->edit_column('lebar_m', '$1', 'format_number(lebar_m)');
    $this->datatables->edit_column('luas_m2', '$1', 'format_number(luas_m2)');
    $this->datatables->edit_column('dokumen_tanggal', '$1', 'date_time(dokumen_tanggal)');
    $this->datatables->edit_column('harga', '$1', 'format_float(harga)');
    $this->datatables->edit_column('status_validasi', '$1', 'status_validasi(id_mutasi_barang,status_validasi)');
    return $this->datatables->generate();
  }

  // datatables
  function json_kib_e($pihak = null, $data = null)
  {
    $this->load->helper('my_datatable');
    $id_jenis = '05';
    $kib = $this->kib[$id_jenis];
    $this->datatables->select('id_kib_e,A.nama_barang, A.nama_barang_migrasi,
      A.nama_barang as nama_barang_desc,
      A.kode_barang,nomor_register,
      judul_pencipta,spesifikasi,kesenian_asal_daerah,kesenian_pencipta,kesenian_bahan,
      hewan_tumbuhan_jenis,hewan_tumbuhan_ukuran,jumlah,tahun_pembelian,asal_usul,harga,
      keterangan, kode_lokasi,validasi,C.id_mutasi_barang,status_diterima,status_validasi, id_kode_lokasi_lama, id_kode_lokasi_baru, id_inventaris');
    $this->datatables->from($kib['table'] . ' A');
	 $this->datatables->join($this->tbl_kode_barang . ' B', 'A.id_kode_barang = B.id_kode_barang');
    $this->datatables->join($this->tbl_mutasi_barang . ' C', 'C.kode_jenis="' . $id_jenis . '" and A.' . $kib['id_name'] . ' = C.id_kib', 'left');
    $this->datatables->join($this->tbl_mutasi . ' D', 'D.id_mutasi = C.id_mutasi', 'left');
    $this->datatables->where('A.validasi', '2');
    // $this->datatables->where('A.status_barang', 'kib');
    $this->datatables->where(" (id_kode_barang_aset_lainya < 1 and A.status_barang <> 'aset_lainya') ", NULL, FALSE);


    if ($pihak == 'list_barang') { //pengajuan
      $this->datatables->where('A.id_kode_lokasi', $this->session->userdata('session')->id_kode_lokasi);
      $this->datatables->where('A.status_barang', 'kib');
      if ($data) {
        foreach (json_decode($data) as $key => $value) {
          $this->datatables->where('A.id_kib_e <> ', $value);
        }
      }
    } else if ($pihak == 'pengecekan_barang') {
      $this->datatables->where('C.id_mutasi', $this->input->post('id_mutasi'), false);
      if($this->session->userdata('session')->id_role != '1'){  
      $this->datatables->where('id_kode_lokasi_baru', $this->session->userdata('session')->id_kode_lokasi, false);
      }
      $this->datatables->add_column('radio_terima', '$1', 'radio_pengecekan_barang(id_mutasi_barang,status_diterima,2)');
      $this->datatables->add_column('radio_tolak', '$1', 'radio_pengecekan_barang(id_mutasi_barang,status_diterima,3)');
    }
    $this->datatables->add_column('action', '$1', 'get_action(kib_e, id_kib_e, validasi)');
    $this->datatables->edit_column('hewan_tumbuhan_ukuran', '$1', 'format_number(hewan_tumbuhan_ukuran)');
    $this->datatables->edit_column('jumlah', '$1', 'format_number(jumlah)');
    $this->datatables->edit_column('harga', '$1', 'format_float(harga)');
    $this->datatables->edit_column('status_validasi', '$1', 'status_validasi(id_mutasi_barang,status_validasi)');

    return $this->datatables->generate();
  }

    // datatables
  function json_kib_f($pihak=null, $data=null) {
    $this->load->helper('my_datatable');
    $id_jenis='06';
    $kib = $this->kib[$id_jenis];
    $this->datatables->select('id_kib_f,A.kode_barang,A.nama_barang, A.nama_barang_migrasi,
      A.nama_barang as nama_barang_desc,bangunan,kontruksi_bertingkat,
      kontruksi_beton,luas_m2,lokasi_alamat,dokumen_tanggal,dokumen_nomor,tanggal_mulai,
      status_tanah,nomor_kode_tanah,asal_usul,nilai_kontrak,keterangan,kode_lokasi,validasi,
      C.id_mutasi_barang,status_diterima,status_validasi, id_kode_lokasi_lama, id_kode_lokasi_baru, id_inventaris');
    $this->datatables->from($kib['table'].' A');
	 $this->datatables->join($this->tbl_kode_barang . ' B', 'A.id_kode_barang = B.id_kode_barang');
    $this->datatables->join($this->tbl_mutasi_barang.' C', 'C.kode_jenis="'.$id_jenis.'" and A.id_kib_f = C.id_kib', 'left');
    $this->datatables->join($this->tbl_mutasi.' D', 'D.id_mutasi = C.id_mutasi', 'left');
    $this->datatables->where('A.validasi','2');


    if ($pihak == 'list_barang') {//pengajuan
      $this->datatables->where('A.id_kode_lokasi', $this->session->userdata('session')->id_kode_lokasi);
      // $this->datatables->where('ifnull(C.id_mutasi,0)','0',false);
      $this->datatables->where('A.status_barang', 'kib');
      if ($data) {
        foreach (json_decode($data) as $key => $value) {
          $this->datatables->where('A.id_kib_f <> ',$value);
        }
      }
    }



    // else if ($pihak == 'validasi_pengajuan'){
    //   $this->datatables->where('C.id_mutasi',$this->input->post('id_mutasi'),false);
    //   $this->datatables->add_column('radio_terima', '$1', 'radio_validasi_pengajuan(id_mutasi_barang,status_pengajuan,2)');
    //   $this->datatables->add_column('radio_tolak', '$1', 'radio_validasi_pengajuan(id_mutasi_barang,status_pengajuan,3)');
    // }
    else if ($pihak == 'pengecekan_barang'){
      // $this->datatables->where('status_pengajuan','2',false);
      // $this->datatables->where('status_validasi','1',false);
      $this->datatables->where('C.id_mutasi',$this->input->post('id_mutasi'),false);
      if($this->session->userdata('session')->id_role != '1'){  
      $this->datatables->where('id_kode_lokasi_baru', $this->session->userdata('session')->id_kode_lokasi, false);
      }
      $this->datatables->add_column('radio_terima', '$1', 'radio_pengecekan_barang(id_mutasi_barang,status_diterima,2)');
      $this->datatables->add_column('radio_tolak', '$1', 'radio_pengecekan_barang(id_mutasi_barang,status_diterima,3)');
    }
     $this->datatables->add_column('action', '$1', 'get_action(kib_atb, id_kib_e, validasi)');
    //$this->datatables->edit_column('hewan_tumbuhan_ukuran', '$1', 'format_number(hewan_tumbuhan_ukuran)');
    //$this->datatables->edit_column('jumlah', '$1', 'format_number(jumlah)');
    $this->datatables->edit_column('nilai_kontrak', '$1', 'format_float(nilai_kontrak)');
    $this->datatables->edit_column('status_validasi', '$1', 'status_validasi(id_mutasi_barang,status_validasi)');
    return $this->datatables->generate();
	
	//$this->datatables->generate();
    // die($this->db->last_query());
  }



  // datatables
  function json_kib_atb($pihak = null, $data = null)
  {
    $this->load->helper('my_datatable');
    $id_jenis = '5.03';
    $kib = $this->kib[$id_jenis];
    $this->datatables->select('
      id_kib_atb, id_pemilik, A.id_kode_barang, id_kode_lokasi,A.nama_barang, A.nama_barang_migrasi, 
      A.nama_barang as nama_barang_desc,
      nama_barang_migrasi, nomor_register, judul_kajian_nama_software, tanggal_perolehan, asal_usul, 
      harga, keterangan, deskripsi, kode_lokasi, A.kode_barang, tanggal_transaksi, nomor_transaksi, 
      id_sumber_dana, id_rekening, validasi, reject_note, status_barang, id_inventaris, 
      
      C.id_mutasi_barang,status_diterima,status_validasi, 
      id_kode_lokasi_lama, id_kode_lokasi_baru');
    $this->datatables->from($kib['table'] . ' A');
	 $this->datatables->join($this->tbl_kode_barang . ' B', 'A.id_kode_barang = B.id_kode_barang');
    $this->datatables->join($this->tbl_mutasi_barang . ' C', 'C.kode_jenis="' . $id_jenis . '" and A.' . $kib['id_name'] . ' = C.id_kib', 'left');
    $this->datatables->join($this->tbl_mutasi . ' D', 'D.id_mutasi = C.id_mutasi', 'left');
     $this->datatables->where('A.validasi', '2');



    if ($pihak == 'list_barang') { //pengajuan
      $this->datatables->where('A.id_kode_lokasi', $this->session->userdata('session')->id_kode_lokasi);
      // $this->datatables->where('ifnull(C.id_mutasi,0)','0',false);
      $this->datatables->where('A.status_barang', 'kib');
      if ($data) {
        foreach (json_decode($data) as $key => $value) {
          $this->datatables->where('A.id_kib_atb <> ', $value);
        }
      }
    } else if ($pihak == 'pengecekan_barang') {
      $this->datatables->where('C.id_mutasi', $this->input->post('id_mutasi'), false);
      if($this->session->userdata('session')->id_role != '1'){  
      $this->datatables->where('id_kode_lokasi_baru', $this->session->userdata('session')->id_kode_lokasi, false);
      }
      $this->datatables->add_column('radio_terima', '$1', 'radio_pengecekan_barang(id_mutasi_barang,status_diterima,2)');
      
    }
    $this->datatables->add_column('action', '$1', 'get_action(kib_atb, id_kib_atb, validasi)');
    $this->datatables->edit_column('harga', '$1', 'format_float(harga)');
    $this->datatables->edit_column('status_validasi', '$1', 'status_validasi(id_mutasi_barang,status_validasi)');

    return $this->datatables->generate();
  }

  public function get_mutasi($id_mutasi)
  {
    $this->db->select('A.id_mutasi, A.tanggal, A.tanggal_bast, A.nomor_bast, A.status_validasi, A.tanggal_validasi, A.status_proses, B.instansi as pengguna_lama, C.instansi as pengguna_baru,concat(D.nama_ruang,D.nama_gedung) as ruang_lama,concat(E.nama_ruang,E.nama_gedung) as ruang_baru');
    $this->db->from($this->tbl_mutasi . ' A');
    $this->db->join($this->tbl_kode_lokasi . ' B', 'A.id_kode_lokasi_lama=B.id_kode_lokasi', 'left');
    $this->db->join($this->tbl_kode_lokasi . ' C', 'A.id_kode_lokasi_baru=C.id_kode_lokasi', 'left');
     $this->db->join('tbl_ruang D', 'A.id_ruang_lama = D.id_ruang', 'left');
      $this->db->join('tbl_ruang E', 'A.id_ruang_baru= E.id_ruang', 'left');
    $this->db->where($this->id_mutasi, $id_mutasi);
    return $this->db->get()->row();
  }

  public function get_mutasi_picture($id_mutasi)
  {
    return $this->db->get_where($this->tbl_mutasi_picture, array($this->id_mutasi => $id_mutasi,))->result();
  }

  public function get_mutasi_barang_diterima($id_mutasi)
  {
    return $this->db->get_where($this->tbl_mutasi_barang, array($this->id_mutasi => $id_mutasi, 'status_diterima' => '2'))->result();
  }


  function get_skpd($id_kode_lokasi = null)
  {
    $this->db->select('B.*');
    $this->db->from('tbl_kode_lokasi A');
    $this->db->join('view_pengguna B', 'A.pengguna=B.pengguna', 'left');
    $this->db->where(array('A.id_kode_lokasi' => $id_kode_lokasi,));

    $res = $this->db->get();
    return $res->row_array();
  }


  function get_status_mutasi_all()
  {
    return $this->db->get('tbl_master_status_mutasi')->result_array();
  }
   function get_status_mutasi_all_kir()
  {
    return $this->db->get('tbl_master_status_mutasi_kir')->result_array();
  }

  // function get_status_mutasi_by_id_mutasi($id_mutasi){
  //   $sql = "
  //     SELECT * 
  //     FROM tbl_master_status_mutasi
  //     WHERE id_status_mutasi <= (SELECT status_proses 
  //     FROM tbl_mutasi   WHERE id_mutasi = $id_mutasi);    
  //   ";
  //   $res = $this->db->query($sql);
  //   return $res->result_array();
  // }



}
