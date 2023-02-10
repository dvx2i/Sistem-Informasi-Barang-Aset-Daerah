<?php

if (!defined('BASEPATH')) exit('No direct script access allowed');

class Penyusutan_rekap_model extends CI_Model
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

  function get_all($data)
  {
    $queri = null;
    $res = null;
    $session = $this->session->userdata('session');
    // die("call proc_rekap_penyusutan_objek(" . $data['session']->id_user . "," . $data['id_pemilik'] . "," . $data['id_kode_lokasi'] . ",'" . $data['start_date'] . "','" . $data['last_date'] . "', '" . $data['intra_ekstra'] . "')");
    //jika pencarian sekota data kode lokasi di buat 0
    if (!$data['id_pengguna']) $data['id_kode_lokasi'] = '0';

    
    if ($data['last_date'] == date('Y').'-07-01') $data['last_date'] = date('Y').'-06-30'; // filternya ke post 1 julli

    if ($data['jenis_rekap'] == 'objek') {
		//print_r($data);exit;
    if($session->id_upik != 'jss-a0149'){
      $query    = $this->db->query("call proc_rekap_penyusutan_objek(" . $data['session']->id_user . "," . $data['id_pemilik'] . "," . $data['id_kode_lokasi'] . ",'" . $data['start_date'] . "','" . $data['last_date'] . "', '" . $data['intra_ekstra'] . "')");
      $res      = $query->result_array();
    }else{
       die("call proc_rekap_penyusutan_objek2(" . $data['session']->id_user . "," . $data['id_pemilik'] . "," . $data['id_kode_lokasi'] . ",'" . $data['start_date'] . "','" . $data['last_date'] . "', '" . $data['intra_ekstra'] . "')");
      
    }
    } else if ($data['jenis_rekap'] == 'rincian_objek') {
      $query    = $this->db->query("call proc_rekap_penyusutan_rincian_objek(" . $data['session']->id_user . "," . $data['id_pemilik'] . "," . $data['id_kode_lokasi'] . ",'" . $data['start_date'] . "','" . $data['last_date'] . "', '" . $data['intra_ekstra'] . "')");
      $res      = $query->result_array();
    }

    // die($this->db->last_query());
    //add this two line
    $query->next_result();
    $query->free_result();
    //end of new code
    // die(json_encode($res));
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

function get_all_juni($data)
{
  $queri = null;
  $res = null;
  $session = $this->session->userdata('session');
  // die("call proc_rekap_penyusutan_objek(" . $data['session']->id_user . "," . $data['id_pemilik'] . "," . $data['id_kode_lokasi'] . ",'" . $data['start_date'] . "','" . $data['last_date'] . "', '" . $data['intra_ekstra'] . "')");
  //jika pencarian sekota data kode lokasi di buat 0
  if (!$data['id_pengguna']) $data['id_kode_lokasi'] = '0';


  if ($data['jenis_rekap'] == 'objek') {
  //print_r($data);exit;
    $query    = $this->db->query("call proc_rekap_penyusutan_objek(" . $data['session']->id_user . "," . $data['id_pemilik'] . "," . $data['id_kode_lokasi'] . ",'" . $data['start_date2'] . "','" . $data['last_date2'] . "', '" . $data['intra_ekstra'] . "')");
    $res      = $query->result_array();
  
  } else if ($data['jenis_rekap'] == 'rincian_objek') {
    $query    = $this->db->query("call proc_rekap_penyusutan_rincian_objek(" . $data['session']->id_user . "," . $data['id_pemilik'] . "," . $data['id_kode_lokasi'] . ",'" . $data['start_date2'] . "','" . $data['last_date2'] . "', '" . $data['intra_ekstra'] . "')");
    $res      = $query->result_array();
  }

  // die($this->db->last_query());
  //add this two line
  $query->next_result();
  $query->free_result();
  //end of new code
  // die(json_encode($res));
  return $res;
}
}
