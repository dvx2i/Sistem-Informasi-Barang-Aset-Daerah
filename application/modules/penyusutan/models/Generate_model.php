<?php

if (!defined('BASEPATH')) exit('No direct script access allowed');

class Generate_model extends CI_Model
{
  public $tbl_penyusutan = 'tbl_penyusutan';
  public $tbl_kode_lokasi = 'tbl_kode_lokasi';
  function __construct()
  {
    parent::__construct();
  }

  // function generate($id_user, $tanggal_penyusutan){
  //   $this->db->query("call proc_penyusutan('".$id_user."', '".$tanggal_penyusutan."');");
  // }
  function generate($id_user, $tanggal_penyusutan,$id_kode_lokasi)
  {
	  //echo $id_user.' '.$tanggal_penyusutan;exit;
    // if($id_kode_lokasi == '239'){
    //   // die("call proc_penyusutan_test('" . $id_user . "', '" . $tanggal_penyusutan . "',".$id_kode_lokasi.");");
    //   $query = $this->db->query("call proc_penyusutan_test('" . $id_user . "', '" . $tanggal_penyusutan . "',".$id_kode_lokasi.");");
    // }else{
      // die("call proc_penyusutan('" . $id_user . "', '" . $tanggal_penyusutan . "',".$id_kode_lokasi.");");
      $query = $this->db->query("call proc_penyusutan('" . $id_user . "', '" . $tanggal_penyusutan . "',".$id_kode_lokasi.");");
    // }

    // $res      = $query->result_array();
  //   //add this two line
    $query->next_result();
    $query->free_result();
  //   //end of new code
  // die(json_encode($res));
  }


  function cek_generate($tanggal_penyusutan)
  {
    $this->db->limit(1);
    return
      $this->db->get_where(
        $this->tbl_penyusutan,
        array(
          'month(tanggal_penyusutan)' => date('m', strtotime($tanggal_penyusutan)),
          'year(tanggal_penyusutan)' => date('Y', strtotime($tanggal_penyusutan)),
        )
      )->result();
    // die($this->db->last_query());
  }
  
  // datatables
  function json()
  { //die(explode('.',$this->session->userdata('session')->kode_lokasi)[4]);
    $this->load->helper('my_datatable');
    $this->datatables->select('A.id_penyusutan,MONTH(tanggal_penyusutan) as bulan,YEAR(tanggal_penyusutan) as tahun,instansi', FALSE);
    $this->datatables->from('tbl_penyusutan A');
    //$this->datatables->join($this->tbl_barang . ' B', 'A.id_kode_barang = B.id_kode_barang','LEFT');
    $this->datatables->join($this->tbl_kode_lokasi . ' C', 'A.id_kode_lokasi = C.id_kode_lokasi','LEFT');
    $session = $this->session->userdata('session');
    // die($session->id_role);
    $lokasi = explode('.', $session->kode_lokasi);
    $pengguna = $lokasi[3];
    $kuasa_pengguna =  $lokasi[4];
     
      
      if($this->input->post('id_kuasa_pengguna') != ''){
        foreach ($this->input->post('id_kuasa_pengguna') as $key => $value) {
          $this->datatables->or_where('A.id_kode_lokasi', $value);
        } // where id kuasa pengguna
      }else{
        // if($this->input->post('id_pemilik') != ''){
        //   $this->datatables->where('C.id_pemilik', $this->input->post('id_pemilik')); // where id pemilik
        // }
        
        if($this->input->post('id_pengguna') != ''){
          $this->datatables->where('C.pengguna', $this->input->post('id_pengguna')); // where id pengguna
        }
      }
      // else{
      //   $this->datatables->where('C.kuasa_pengguna', '*'); // where id kuasa pengguna
      // }
    $this->datatables->group_by('A.id_kode_lokasi,MONTH(tanggal_penyusutan),YEAR(tanggal_penyusutan)');
    $this->db->order_by('tahun,bulan', 'DESC');
    $this->datatables->edit_column('bulan', '$1', 'bulan_indo(bulan)');
    $this->datatables->edit_column('id', '$1', 'encrypt_id(id)');
    // $this->datatables->add_column('action', '$1', 'get_action_stock_opname(' . $session->id_role . ',locked_admin,locked_skpd,' . $this->id . ')');
    return $this->datatables->generate();
     //$this->datatables->generate();
     //die($this->db->last_query());
  }
}
