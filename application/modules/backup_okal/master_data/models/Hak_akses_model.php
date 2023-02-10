<?php

if (!defined('BASEPATH'))
exit('No direct script access allowed');

class Hak_akses_model extends CI_Model{

  public $table = 'tbl_hak_akses';
  public $tbl_lokasi = 'tbl_kode_lokasi';
  public $tbl_role = 'tbl_role';
  public $tbl_menu = 'tbl_menu';


  public $id = 'id_hak_akses';
  public $order = 'DESC';

  function __construct(){
    parent::__construct();
  }

  // datatables
  /*function json() {
    $this->load->helper('my_datatable');
    $this->datatables->select('B.description as role_desc'); //,A.id_hak_akses, A.create, A.update, A.delete, C.menu, C.sub_menu');
    $this->datatables->from($this->table.' A');
    $this->datatables->join($this->tbl_role.' B', 'A.id_role = B.id_role', 'left');
    $this->datatables->join($this->tbl_menu.' C', 'A.id_menu = C.id_menu', 'left');
    $this->datatables->group_by('B.description');

    // $this->datatables->edit_column('create','$1' , 'hak_akses(create)');
    // $this->datatables->edit_column('update','$1' , 'hak_akses(update)');
    // $this->datatables->edit_column('delete','$1' , 'hak_akses(delete)');
    $this->datatables->add_column('action',anchor(base_url('master_data/hak_akses/update/$1'),'<span class="fa fa-edit"></span>'), 'id_hak_akses');

    return
    $this->datatables->generate();
    // die($this->db->last_query());
  }*/
  function json() {
    $this->load->helper('my_datatable');
    $this->datatables->select('A.id_role, A.role, A.description');
    $this->datatables->from($this->tbl_role.' A');
    $this->datatables->group_by('A.description');

    $this->datatables->add_column('action',anchor(base_url('master_data/hak_akses/update/$1'),'<span class="fa fa-edit"></span>'), 'id_role');

    return
    $this->datatables->generate();
    // die($this->db->last_query());
  }

  // get all
  function get_all()
  {
    $this->db->order_by($this->id, $this->order);
    return $this->db->get($this->table)->result();
  }

  // get data by id
  function get_by_id($id)
  {
    $this->db->where('id_role', $id);
    return $this->db->get($this->tbl_role)->row();
  }
/*
  // get total rows
  function total_rows($q = NULL) {
    $this->db->like('id_user', $q);
    $this->db->or_like('nama', $q);
    $this->db->or_like('nip', $q);
    $this->db->or_like('kode_lokasi', $q);
    $this->db->from($this->table);
    return $this->db->count_all_results();
  }

  // get data with limit and search
  function get_limit_data($limit, $start = 0, $q = NULL) {
    $this->db->order_by($this->id, $this->order);
    $this->db->like('id_user', $q);
    $this->db->or_like('nama', $q);
    $this->db->or_like('nip', $q);
    $this->db->or_like('kode_lokasi', $q);
    $this->db->limit($limit, $start);
    return $this->db->get($this->table)->result();
  }
*/
  // insert data
  function insert($data){
    $this->db->insert($this->table, $data);
  }

  // update data
  function update($id, $data){
    $this->db->where($this->id, $id);
    $this->db->update($this->table, $data);
  }
/*
  // delete data
  function delete($id)
  {
    $this->db->where($this->id, $id);
    $this->db->delete($this->table);
  }

  function getLokasi()
  {
    $this->db->select('id_kode_lokasi,CONCAT(intra_kom_ekstra_kom,".",propinsi,".",kabupaten,".",pengguna,".",kuasa_pengguna,".",sub_kuasa_pengguna) AS kode, instansi');
    $this->db->from('tbl_kode_lokasi');
    $this->db->order_by('kode', 'ASC');
    return $this->db->get()->result();
  }
*/
  function getRole(){
    return $this->db->get('tbl_role')->result();
  }

  function getJabatan(){
    return $this->db->get('tbl_master_jabatan')->result();
  }
/*
  function get_by_id_upik($id_upik){
    return $this->db->get_where($this->table,array('id_upik' => $id_upik, ))->row();
  }*/

  function getMenu(){
    $this->db->select('menu');
    $this->db->group_by('menu');
    return $this->db->get('tbl_menu')->result();
  }

  function getSubMenu($id_role){
    $this->db->select('A.* , ifnull(B.id_hak_akses,0) id_hak_akses, ifnull(B.id_role,0) id_role, ifnull(B.active,"n") active');
    $this->db->from('tbl_menu A');
    $this->db->join('tbl_hak_akses B','(A.id_menu = B.id_menu) and (B.id_role = '.$id_role.')','left' );

    return
    $this->db->get()->result();
    // die($this->db->last_query());
  }

  function getHakAksesById($id_role){
    $this->db->select('A.id_hak_akses, A.id_role, A.id_menu, A.active, B.menu, B.sub_menu');
    $this->db->from('tbl_hak_akses A');
    $this->db->join('tbl_menu B', 'A.id_menu = B.id_menu', 'left');
    $this->db->where('id_role',$id_role);
    return $this->db->get()->result();
  }

  function getConcatHakAksesById($id_role){
    $this->db->select('id_role, group_concat(id_menu)  list_id_menu');
    $this->db->from('tbl_hak_akses');
    $this->db->where('id_role',$id_role);
    $this->db->where('active','y');
    $this->db->group_by('id_role');
    return $this->db->get()->row();
  }

  function insertHakAkses($datainsert){
    $this->db->insert_batch($this->table, $datainsert);
    // return $this->db->insert_id();
  }

  function cekNamaHakAkses($description){
    return $this->db->get_where('tbl_role', array('description' => $description))->result();
  }

  function insertRole($data){
    $this->db->insert('tbl_role', $data);
    return $this->db->insert_id();
  }

}

/* End of file User_model.php */
/* Location: ./application/models/User_model.php */
/* Please DO NOT modify this information : */
/* Generated by Harviacode Codeigniter CRUD Generator 2018-08-09 06:17:03 */
/* http://harviacode.com */
