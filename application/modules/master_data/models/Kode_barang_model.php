<?php

if (!defined('BASEPATH'))
  exit('No direct script access allowed');

class Kode_barang_model extends CI_Model
{

  public $table = 'tbl_kode_barang';
  public $id = 'id_kode_barang';
  public $order = 'DESC';

  function __construct()
  {
    parent::__construct();
  }

  // datatables
  function json()
  {
    $this->load->helper('my_datatable');
    $this->datatables->select("A.id_kode_barang,A.kode_barang
      ,case when ((A.kode_sub_sub_rincian_objek='000')or(A.kode_sub_sub_rincian_objek='*') ) then A.nama_barang else A.nama_barang_simbada end as nama_barang
      ,A.kode_akun,
      A.kode_kelompok,A.kode_rincian_objek,A.kode_jenis,A.kode_objek,A.kode_rincian_objek,
      A.kode_sub_rincian_objek,A.kode_sub_sub_rincian_objek,A.umur_ekonomis,
      A.nilai_residu,B.Uraian as kelompok_manfaat
      ");
    $this->datatables->from('tbl_kode_barang as A');
    // $this->datatables->where('kode_sub_sub_rincian_objek <> ', '*');
    //add this line for join
    $this->datatables->join('tbl_master_kapitalisasi as B', 'B.Kelompok_Manfaat = A.kelompok_manfaat', 'left');
    // $this->datatables->add_column('action', anchor(base_url('master_data/kode_barang/update/$1'), '<span class="fa fa-edit" style="font-size:18px;"></span>') . " &nbsp " . anchor(base_url('master_data/kode_barang/delete/$1'), '<span class="fa fa-trash" style="font-size:18px;"></span>', 'onclick="javasciprt: return confirm(\'Yakin Ingin Mengahapus ?\')"'), 'id_kode_barang');
    $this->db->order_by('A.kode_akun, A.kode_kelompok, A.kode_jenis, A.kode_objek, A.kode_rincian_objek, A.kode_sub_rincian_objek, A.kode_sub_sub_rincian_objek');
    // $this->db->order_by('A.kode_akun');
    $this->datatables->add_column('action', '$1', 'get_action_kode_barang(id_kode_barang, kode_sub_sub_rincian_objek)');
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
    $this->db->where($this->id, $id);
    return $this->db->get($this->table)->row();
  }

  // get total rows
  function total_rows($q = NULL)
  {
    $this->db->like('id_kode_barang', $q);
    $this->db->or_like('kode_barang', $q);
    $this->db->or_like('nama_barang', $q);
    $this->db->or_like('akun', $q);
    $this->db->or_like('rincian_objek', $q);
    $this->db->or_like('jenis', $q);
    $this->db->or_like('objek', $q);
    $this->db->or_like('rincian_objek', $q);
    $this->db->or_like('sub_rincian_objek', $q);
    $this->db->or_like('sub_sub_rincian_objek', $q);
    $this->db->or_like('umur_ekonomis', $q);
    $this->db->or_like('nilai_residu', $q);
    $this->db->or_like('rincian_objek_manfaat', $q);
    $this->db->from($this->table);
    return $this->db->count_all_results();
  }

  // get data with limit and search
  function get_limit_data($limit, $start = 0, $q = NULL)
  {
    $this->db->order_by($this->id, $this->order);
    $this->db->like('id_kode_barang', $q);
    $this->db->or_like('kode_barang', $q);
    $this->db->or_like('nama_barang', $q);
    $this->db->or_like('akun', $q);
    $this->db->or_like('rincian_objek', $q);
    $this->db->or_like('jenis', $q);
    $this->db->or_like('objek', $q);
    $this->db->or_like('rincian_objek', $q);
    $this->db->or_like('sub_rincian_objek', $q);
    $this->db->or_like('sub_sub_rincian_objek', $q);
    $this->db->or_like('umur_ekonomis', $q);
    $this->db->or_like('nilai_residu', $q);
    $this->db->or_like('rincian_objek_manfaat', $q);
    $this->db->limit($limit, $start);
    return $this->db->get($this->table)->result();
  }

  // insert data
  function insert($data)
  {
    $this->db->insert($this->table, $data);
  }

  // update data
  function update($id, $data)
  {
    $this->db->where($this->id, $id);
    $this->db->update($this->table, $data);
  }

  // delete data
  function delete($id)
  {
    $this->db->where($this->id, $id);
    $this->db->delete($this->table);
  }

  function get_master_kapitalisasi()
  {
    return $this->db->get('tbl_master_kapitalisasi')->result();
  }

  function get_kode()
  {
    $this->db->group_by("kode_akun");
    $this->db->where("kode_akun<>", "*");
    $this->db->select("kode_akun, nama_barang");
    $data['kode_akun'] = $this->db->get($this->table)->result_array();

    $this->db->group_by("kode_kelompok");
    $this->db->where("kode_kelompok<>", "*");
    $this->db->select("kode_kelompok, nama_barang");
    $data['kode_kelompok'] = $this->db->get($this->table)->result_array();

    $this->db->group_by("kode_jenis");
    $this->db->where("kode_jenis<>", "*");
    $this->db->select("kode_jenis, nama_barang");
    $data['kode_jenis'] = $this->db->get($this->table)->result_array();

    $this->db->group_by("kode_objek");
    $this->db->where("kode_objek<>", "*");
    $this->db->select("kode_objek, nama_barang");
    $data['kode_objek'] = $this->db->get($this->table)->result_array();

    $this->db->group_by("kode_rincian_objek");
    $this->db->where("kode_rincian_objek<>", "*");
    $this->db->select("kode_rincian_objek, nama_barang");
    $data['kode_rincian_objek'] = $this->db->get($this->table)->result_array();

    $this->db->group_by("kode_sub_rincian_objek");
    $this->db->where("kode_sub_rincian_objek<>", "*");
    $this->db->select("kode_sub_rincian_objek, nama_barang");
    $data['kode_sub_rincian_objek'] = $this->db->get($this->table)->result_array();
    return $data;
    // die(json_encode($data));
  }

  function get_jenis($kode_kelompok)
  {
    $this->db->group_by("kode_jenis");
    $this->db->where("kode_jenis<>", "*");
    $this->db->where("kode_kelompok", $kode_kelompok);
    $this->db->select("kode_jenis, nama_barang");
    $res = $this->db->get($this->table)->result_array();
    return $res;
    // die($this->db->last_query());
    // die(json_encode($lis_kode_jenis));
  }

  function get_objek($kode_kelompok, $kode_jenis)
  {
    $data_where = array(
      'kode_jenis<>' => '*',
      'kode_objek<>' => '*',
      'kode_kelompok' => $kode_kelompok,
      'kode_jenis' => $kode_jenis
    );
    $this->db->group_by("kode_jenis, kode_objek");
    $this->db->where($data_where);
    $this->db->select("kode_objek, nama_barang");
    $res = $this->db->get($this->table)->result_array();
    return $res;
    // die($this->db->last_query());
    // die(json_encode($lis_kode_jenis));
  }

  function get_rincian_objek($kode_kelompok, $kode_jenis, $kode_objek)
  {
    $data_where = array(
      'kode_jenis<>' => '*',
      'kode_objek<>' => '*',
      'kode_rincian_objek<>' => '*',
      'kode_kelompok' => $kode_kelompok,
      'kode_jenis' => $kode_jenis,
      'kode_objek' => $kode_objek
    );
    $this->db->group_by("kode_jenis, kode_objek, kode_rincian_objek");
    $this->db->where($data_where);
    $this->db->select("kode_rincian_objek, nama_barang");
    $res = $this->db->get($this->table)->result_array();
    return $res;
    // die($this->db->last_query());
    // die(json_encode($lis_kode_jenis));
  }

  function get_sub_rincian_objek($kode_kelompok, $kode_jenis, $kode_objek, $kode_rincian_objek)
  {
    $data_where = array(
      'kode_jenis<>' => '*',
      'kode_objek<>' => '*',
      'kode_rincian_objek<>' => '*',
      'kode_sub_rincian_objek<>' => '*',
      'kode_kelompok' => $kode_kelompok,
      'kode_jenis' => $kode_jenis,
      'kode_objek' => $kode_objek,
      'kode_rincian_objek' => $kode_rincian_objek,
    );
    $this->db->group_by("kode_jenis, kode_objek, kode_rincian_objek, kode_sub_rincian_objek");
    $this->db->where($data_where);
    $this->db->select("kode_sub_rincian_objek, nama_barang");
    $res = $this->db->get($this->table)->result_array();
    return $res;
    // die($this->db->last_query());
    // die(json_encode($res));
  }

  function get_kode_sub_sub_rincian_objek($kode_kelompok, $kode_jenis, $kode_objek, $kode_rincian_objek, $kode_sub_rincian_objek)
  {
    $query = "
      SELECT LPAD(kode_sub_sub_rincian_objek+1,3,'000') as next_kode_sub_sub_rincian_objek
      FROM tbl_kode_barang
      WHERE kode_kelompok='$kode_kelompok' and kode_jenis='$kode_jenis' AND kode_objek='$kode_objek' AND kode_rincian_objek='$kode_rincian_objek' and kode_sub_rincian_objek='$kode_sub_rincian_objek'
      and kode_sub_sub_rincian_objek<>'*'
      ORDER by kode_sub_sub_rincian_objek DESC
      LIMIT 1
      ;    
    ";
    $res = $this->db->query($query);
    return $res->row_array();
  }
}

/* End of file Kode_barang_model.php */
/* Location: ./application/models/Kode_barang_model.php */
/* Please DO NOT modify this information : */
/* Generated by Harviacode Codeigniter CRUD Generator 2018-09-19 06:22:01 */
/* http://harviacode.com */
