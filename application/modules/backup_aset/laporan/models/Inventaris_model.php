<?php

if (!defined('BASEPATH')) exit('No direct script access allowed');

class Inventaris_model extends CI_Model
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


  // // get all
  // function get_all(){
  //   // $this->db->order_by($this->id, $this->order);
  //   $this->db->select(' A.*, B.kode_barang , B.nama_barang, C.instansi');
  //   $this->db->from('view_kib A');
  //   $this->db->join('tbl_kode_barang B','B.id_kode_barang=A.id_kode_barang','left');
  //   $this->db->join('tbl_kode_lokasi C','C.id_kode_lokasi=A.id_kode_lokasi','left');
  //   $this->db->where('validasi','2');;
  //   return $this->db->get()->result();
  //   // $this->db->get()->result(); die($this->db->last_query());
  // }

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

  // get all
  function get_all($data)
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
    $this->db->select(' (@no := @no + 1)  as no , 
      A.kode_jenis, A.jenis_name, A.id_kib, A.id_pemilik, A.id_kode_lokasi, A.kode_barang, 
      A.nama_barang, nomor_register, 
      A.kode_lokasi, A.keterangan, A.asal_usul, A.tanggal_perolehan, A.harga,
      A.merk_type, A.sertifikat_nomor, A.bahan, A.ukuran_barang, A.satuan, 
      A.keadaan_barang, 1 as jumlah_barang,
      A.input_banyak, B.instansi,C.value ');
    $this->db->from(' view_laporan_inventaris A ');
    // $this->db->where('validasi', '2');
    $this->db->where('A.id_pemilik', $data['id_pemilik']);
    $this->db->where_not_in('status_barang', array('terhapus','lampiran_penghapusan'));
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
    $this->db->from(' (SELECT @no:=0) as var ');
    /* abaikan input banyak , kepentingan laporan pertama sehabis migrasi
    $this->db->group_by('input_banyak, kode_jenis, kode_barang, id_kode_lokasi');
    */
    // $this->db->group_by('kode_jenis, kode_barang, id_kode_lokasi');
    // $this->db->group_by('`kode_jenis`, `kode_barang`, `id_kode_lokasi`, asal_usul, tanggal_perolehan, harga, merk_type, sertifikat_nomor,  bahan, ukuran_barang, satuan, keadaan_barang');
    $this->db->order_by('kode_jenis', 'asc');
    $this->db->order_by('kode_barang', 'asc');
    return
      $this->db->get()->result_array();
    // die($/this->db->last_query());
  }
  
  function get_all_limit($data,$mulai)
  {
    $this->db->query('SET @row_number = '.$mulai.';');
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
    $this->db->select(' (@no := @no + 1)  as no , 
      A.kode_jenis, A.jenis_name, A.id_kib, A.id_pemilik, A.id_kode_lokasi, A.kode_barang, 
      A.nama_barang, nomor_register, 
      A.kode_lokasi, A.keterangan, A.asal_usul, A.tanggal_perolehan, A.harga,
      A.merk_type, A.sertifikat_nomor, A.bahan, A.ukuran_barang, A.satuan, 
      A.keadaan_barang, 1 as jumlah_barang,
      A.input_banyak, B.instansi,C.value ');
    $this->db->from(' view_laporan_inventaris A ');
    // $this->db->where('validasi', '2');
    $this->db->where('A.id_pemilik', $data['id_pemilik']);
    $this->db->where_not_in('status_barang', array('terhapus','lampiran_penghapusan'));
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
    $this->db->from(' (SELECT @no:=0) as var ');
    /* abaikan input banyak , kepentingan laporan pertama sehabis migrasi
    $this->db->group_by('input_banyak, kode_jenis, kode_barang, id_kode_lokasi');
    */
    // $this->db->group_by('kode_jenis, kode_barang, id_kode_lokasi');
    // $this->db->group_by('`kode_jenis`, `kode_barang`, `id_kode_lokasi`, asal_usul, tanggal_perolehan, harga, merk_type, sertifikat_nomor,  bahan, ukuran_barang, satuan, keadaan_barang');
    // $this->db->order_by('kode_jenis', 'asc');
    $this->db->order_by('kode_barang', 'asc');
	$this->db->limit(30000, $mulai);
    return
      $this->db->get()->result_array();
    // die($/this->db->last_query());
  }

  public function get_pengguna_kib()
  {
    $this->db->select('select distinct C.*');
    $this->db->from('view_kib A');
    $this->db->join($this->tbl_kode_lokasi . ' B', 'A.id_kode_lokasi = B.id_kode_lokasi');
    $this->db->join('view_pengguna C', 'B.pengguna = C.pengguna');
    return $this->db->get()->result();
  }
}
