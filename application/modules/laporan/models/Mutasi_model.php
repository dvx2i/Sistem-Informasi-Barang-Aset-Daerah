<?php

if (!defined('BASEPATH')) exit('No direct script access allowed');

class Mutasi_model extends CI_Model
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

  function get_all($data)
  {
    $queri = null;
    $res = null;
    // $data['limit'] = 5000;
    // $data['offset'] = 0;
    if($data['session']->id_upik == 'jss-a7324'){
    // die("call proc_laporan_mutasi(" . $data['session']->id_user . "," . $data['id_pemilik'] . "," . $data['id_kode_lokasi'] . ",'" . $data['start_bulan'] . "','" . $data['start_tahun'] . "','" . $data['last_bulan'] . "','" . $data['last_tahun'] . "', '" . $data['intra_ekstra'] . "', " . $data['limit'] . ", " . $data['offset'] . ",'" . $data['status_histori'] . "'," . $data['id_sumber_dana'] . "," . $data['id_rekening'] . ")");
    $query    = $this->db->query("call proc_laporan_mutasi(" . $data['session']->id_user . "," . $data['id_pemilik'] . "," . $data['id_kode_lokasi'] . ",'" . $data['start_bulan'] . "','" . $data['start_tahun'] . "','" . $data['last_bulan'] . "','" . $data['last_tahun'] . "', '" . $data['intra_ekstra'] . "', " . $data['limit'] . ", " . $data['offset'] . ",'" . $data['status_histori'] . "'," . $data['id_sumber_dana'] . "," . $data['id_rekening'] . ",'" . $data['kode_jenis'] . "')");
    $res      = $query->result_array();
    }
    else{
      // die("call proc_laporan_mutasi(" . $data['session']->id_user . "," . $data['id_pemilik'] . "," . $data['id_kode_lokasi'] . ",'" . $data['start_date'] . "','" . $data['last_date'] . "', '" . $data['intra_ekstra'] . "')");
    $query    = $this->db->query("call proc_laporan_mutasi_limit(" . $data['session']->id_user . "," . $data['id_pemilik'] . "," . $data['id_kode_lokasi'] . ",'" . $data['start_bulan'] . "','" . $data['start_tahun'] . "','" . $data['last_bulan'] . "','" . $data['last_tahun'] . "', '" . $data['intra_ekstra'] . "', " . $data['limit'] . ", " . $data['offset'] . ")");
    $res      = $query->result_array();
    }

    $query->next_result();
    $query->free_result();

    // die($this->db->last_query());
    //add this two line
    //end of new code
    // die($this->db->last_query());
    // die(json_encode($res));
    return $res;
  }

  /*
  // get all
  function get_all($data){
    $this->db->query('SET @row_number = 0;');
    $this->db->query(' SET @input_banyak = 0;');
    if ($data['id_pengguna'] != '')  $pengguna = $this->db->get_where('view_pengguna', array('id_pengguna' => $data['id_pengguna'], ))->row()->pengguna;
    if ($data['id_kuasa_pengguna'] != '')  $kuasa_pengguna = $this->db->get_where('view_kuasa_pengguna', array('id_pengguna' => $data['id_pengguna'],'id_kuasa_pengguna' => $data['id_kuasa_pengguna'] ))->row()->kuasa_pengguna;

    // $this->db->select('(@row_number:=@row_number + 1) AS no,A.*, B.instansi, C.value');
    $this->db->select('case when ifnull(A.input_banyak, 0)=0 or ifnull(A.input_banyak, 0) <>@input_banyak then (@row_number:=@row_number + 1) end AS no,
      @input_banyak:=ifnull(A.input_banyak, 0),
      A.kode_jenis,A.jenis_name,A.id_kib,A.id_pemilik,A.id_kode_lokasi,A.kode_barang,
      A.nama_barang,group_concat(A.nomor_register  order by A.nomor_register asc)nomor_register,A.kode_lokasi,A.keterangan,A.asal_usul,A.tanggal_perolehan,
      sum(A.harga)harga,A.merk_type,A.sertifikat_nomor,A.bahan,A.ukuran_barang,A.satuan,A.keadaan_barang,
      count(A.jumlah_barang)jumlah_barang,A.input_banyak,
      B.instansi, C.value');
    $this->db->from('view_laporan_inventaris A');
    // $this->db->where('validasi', '2');
    $this->db->where('A.id_pemilik', $data['id_pemilik']);
    if ($data['id_sub_kuasa_pengguna'] != ''){
      $this->db->where('A.id_kode_lokasi', $data['id_sub_kuasa_pengguna']);
    }
    else if ($data['id_kuasa_pengguna'] != ''){
      $this->db->where('B.pengguna', $pengguna);
      $this->db->where('B.kuasa_pengguna', $kuasa_pengguna);
    }
    else if ($data['id_pengguna'] != ''){
      $this->db->where('B.pengguna', $pengguna);
    }
    if ($data['start_date'] != '')
      $this->db->where('tanggal_perolehan >= ', $data['start_date']);
    if ($data['last_date'] != '')
      $this->db->where('tanggal_perolehan <= ', $data['last_date']);
    $this->db->join($this->tbl_kode_lokasi.' B','B.id_kode_lokasi=A.id_kode_lokasi' ,'left');

    $harga_nilaikontrak='harga';
    // if ($data['kode_jenis'] == '6') $harga_nilaikontrak = 'nilai_kontrak'; tidak dipakai karena pakai union

    if ($data['intra_ekstra'] == '01') $this->db->where($harga_nilaikontrak.' >=', 'C.value', FALSE);  //intra
    else if ($data['intra_ekstra'] == '02') $this->db->where($harga_nilaikontrak.' <', 'C.value', FALSE); //extra

    $this->db->join($this->tbl_master_intra_extra.' C', 'C.kode_jenis=A.kode_jenis','left');
    $this->db->group_by('input_banyak, kode_jenis, kode_barang');
    $this->db->order_by('kode_jenis', 'asc');
    $this->db->order_by('id_kib', 'asc');
    return
    $this->db->get()->result_array();
     // die($this->db->last_query());
  }
*/

  /*
  public function get_pengguna_kib(){
    $this->db->select('select distinct C.*');
    $this->db->from('view_kib A');
    $this->db->join($this->tbl_kode_lokasi.' B', 'A.id_kode_lokasi = B.id_kode_lokasi');
    $this->db->join('view_pengguna C', 'B.pengguna = C.pengguna');
    return $this->db->get()->result();
  }
*/
}
