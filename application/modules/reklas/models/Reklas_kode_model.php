<?php

if (!defined('BASEPATH')) exit('No direct script access allowed');

class Reklas_kode_model extends CI_Model
{

  public $table = 'tbl_reklas_kode';
  public $id = 'id_reklas_kode';
  public $order = 'DESC';
  // public $tbl_kode_barang = 'tbl_kode_barang';

  // public $tbl_mutasi_barang = 'tbl_mutasi_barang';
  // public $id_mutasi_barang = 'id_mutasi_barang';


  function __construct()
  {
    parent::__construct();
  }

  // datatables
  function json()
  {
    // die("ok");
    $this->load->helper('my_datatable');
    $session = $this->session->userdata('session');
    $lokasi = explode('.', $session->kode_lokasi);
    $pengguna = $lokasi[3];
    $this->db->query('SET lc_time_names = "id_ID";');
    $this->datatables->select("
      A.id_reklas_kode,DATE_FORMAT(tanggal, '%d %M %Y') as tanggal,
      case when A.status_validasi='1' then '<span class=text-warning>Menunggu Validasi</span>' else '<span class=text-success>Tervalidasi</span>' end status_validasi_desc, 
      A.id_kode_barang,A.status_validasi
      ,concat_ws('-',D.kode_lokasi, D.instansi) as lokasi,
      B.kode_barang
      , CONCAT_WS(' - ',B.kode_barang, CASE WHEN ((B.kode_sub_sub_rincian_objek='000') or (B.kode_sub_sub_rincian_objek='0')) THEN B.nama_barang ELSE B.nama_barang_simbada END, CONCAT('(id kib : ', id_kib,')') ) nama_barang_with_id
      , CONCAT_WS(' - ',C.kode_barang, CASE WHEN ((C.kode_sub_sub_rincian_objek='000') or (C.kode_sub_sub_rincian_objek='0')) THEN C.nama_barang ELSE C.nama_barang_simbada END, CONCAT('(id kib : ', id_kib_tujuan,')') ) nama_barang_tujuan_with_id,C.nama_barang
      ,A.jenis_reklas
    ");
    $this->datatables->from('tbl_reklas_kode A');
    $this->datatables->join('tbl_kode_barang B', 'A.id_kode_barang=B.id_kode_barang', 'left');
    $this->datatables->join('tbl_kode_barang C', 'A.id_kode_barang_tujuan=C.id_kode_barang', 'left');
    $this->datatables->join('tbl_kode_lokasi D', 'A.id_kode_lokasi=D.id_kode_lokasi', 'left');
    if (cek_hak_akses('superadmin', $session->id_role)) {
      $this->datatables->where('1', "1"); // jika superadmin
    } elseif (cek_hak_akses('skpd', $session->id_role)) {
      $this->datatables->where('D.pengguna', $pengguna); //jika skpd
    } else { // jika bukan superadmin dan bukan skpd
      $this->datatables->where('A.id_kode_lokasi', $session->id_kode_lokasi);
    }
    // $this->datatables->add_column('barang', '$1', 'barang(id_mutasi)');
    // $this->datatables->add_column('action', '$1', 'pengajuan_action(status_validasi,id_mutasi)');
    $this->datatables->add_column('action', '$1', 'get_action_reklas_kode(status_validasi,id_reklas_kode,jenis_reklas)');
    // $this->datatables->add_column('keterangan', '$1', 'pengajuan_keterangan(status_validasi,id_mutasi)');
    // $this->datatables->add_column('keterangan', '$1', 'status_proses(status_proses)');
    return
      $this->datatables->generate();
    // die($this->db->last_query());
  }
  /*
  // datatables
  function json_detail($id_mutasi)
  {
    $this->load->helper('my_datatable');
    $this->db->query('SET lc_time_names = "id_ID";');
    $this->datatables->select('A.id_mutasi_barang, A.status_diterima,A.id_kib,B.kib_name, B.nomor_register, C.kode_barang, C.nama_barang');
    $this->datatables->from('tbl_mutasi_barang A');
    $this->datatables->join('(
      SELECT kode_jenis, id_kib, id_pemilik, kib_name, nomor_register, id_kode_barang, merk_type, id_kode_lokasi, kode_lokasi, asal_usul, tanggal_pembelian, tanggal_perolehan, validasi, kib_f, harga, status_barang
      FROM `view_kib`
      UNION ALL 
      SELECT "5.03" as kode_jenis, id_kib_atb, id_pemilik, "KIB ATB" as kib_name, nomor_register, id_kode_barang, ""  as merk_type , id_kode_lokasi, kode_lokasi, asal_usul, "" tanggal_pembelian, tanggal_perolehan, validasi, "" kib_f, harga, status_barang
      FROM tbl_kib_atb 
    ) B', 'A.kode_jenis=B.kode_jenis and A.id_kib=B.id_kib', 'left');
    $this->datatables->join('tbl_kode_barang C', 'B.id_kode_barang=C.id_kode_barang', 'left');
    $this->datatables->where('A.id_mutasi', $id_mutasi);
    $this->datatables->edit_column('status_diterima', '$1', 'status_diterima(status_diterima)');

    return
      $this->datatables->generate();
    // die($this->db->last_query());
  }
*/

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

  // get data by id
  function get_barang($id_mutasi_barang)
  {
    $this->db->select('group_concat(id_kode_barang) as id_kode_barang');
    $this->db->where($this->id, $id_mutasi_barang);
    $this->db->group_by($this->id);
    return $this->db->get($this->tbl_mutasi_barang)->row();
    // die($this->db->last_query());
  }

  // get total rows
  function total_rows($q = NULL)
  {
    $this->db->like('id_reklas_kode', $q);
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
    $this->db->like('id_reklas_kode', $q);
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

  // insert data
  function insert($data)
  {
    $this->db->insert($this->table, $data);
    return $this->db->insert_id();
  }
  /*
  // insert data
  function insert_barang($data)
  {
    $this->db->insert_batch($this->tbl_mutasi_barang, $data);
  }

  function update_barang($id_mutasi, $data)
  {
    $this->db->where($this->id, $id_mutasi);
    $this->db->delete($this->tbl_mutasi_barang);

    $this->db->insert_batch($this->tbl_mutasi_barang, $data);
  }


  // update data
  function update($id, $data)
  {
    $this->db->where($this->id, $id);
    $this->db->update($this->table, $data);
  }
*/
  // delete data
  function delete($id)
  {
    $this->db->where($this->id, $id);
    $this->db->delete($this->table);
  }

  function delete_kib($table, $where_data)
  {
    $this->db->where($where_data);
    $this->db->delete($table);
  }


  /*
  // delete data
  function delete_mutasi_barang($id)
  {
    $this->db->where($this->id, $id);
    $this->db->delete($this->tbl_mutasi_barang);
  }
*/
  function get_lokasi($id_pengguna)
  {
    // $res = $this->db->get_where('tbl_kode_lokasi', array('kuasa_pengguna<>' => "*"));
    $query = "
        SELECT A.* 
        FROM tbl_kode_lokasi A 
        LEFT JOIN view_pengguna B on A.pengguna=B.pengguna
        WHERE A.kuasa_pengguna <> '*' AND B.id_pengguna=$id_pengguna;
    ";
    $res = $this->db->query($query);
    return $res->result_array();
  }

  function get_jenis_aset_lainya()
  {
    $query = "
      SELECT * 
      FROM tbl_kode_barang 
      WHERE kode_kelompok='5' AND (kode_jenis='02' OR kode_jenis='04') and kode_objek='*';
    ";
    $res = $this->db->query($query);
    return $res->result_array();
  }

  function get_list_kelompok()
  {
    $res = $this->db->get_where('view_kode_barang_kelompok', array('kode_kelompok<>' => "4"));
    return $res->result_array();
  }


  function get_barang_aset_lainya($kode_jenis, $id_kode_jenis_aset_lainya)
  {
    $aset_lainya = $this->db->get_where('tbl_kode_barang', array('id_kode_barang' => $id_kode_jenis_aset_lainya))->row_array();
    // if ($aset_lainya['kode_jenis'] == '02') {

    // } elseif ($aset_lainya['kode_jenis'] == '04') {

    // }
    // die(json_encode($aset_lainya));
    if ($kode_jenis == '01') {
      $this->db->where(
        array('kode_kelompok' => "5", 'kode_jenis' => $aset_lainya['kode_jenis'], 'kode_objek' => '01', 'kode_rincian_objek' => '01', 'kode_sub_rincian_objek' => '001', 'kode_sub_sub_rincian_objek' => '1')
      );
    } elseif ($kode_jenis == '02') {
      $this->db->where(
        array('kode_kelompok' => "5", 'kode_jenis' => $aset_lainya['kode_jenis'], 'kode_objek' => '01', 'kode_rincian_objek' => '01', 'kode_sub_rincian_objek' => '001', 'kode_sub_sub_rincian_objek' => '2')
      );
    } elseif ($kode_jenis == '03') {
      $this->db->where(
        array('kode_kelompok' => "5", 'kode_jenis' => $aset_lainya['kode_jenis'], 'kode_objek' => '01', 'kode_rincian_objek' => '01', 'kode_sub_rincian_objek' => '001', 'kode_sub_sub_rincian_objek' => '3')
      );
    } elseif ($kode_jenis == '04') {
      $this->db->where(
        array('kode_kelompok' => "5", 'kode_jenis' => $aset_lainya['kode_jenis'], 'kode_objek' => '01', 'kode_rincian_objek' => '01', 'kode_sub_rincian_objek' => '001', 'kode_sub_sub_rincian_objek' => '4')
      );
    } elseif ($kode_jenis == '05') {
      $this->db->where(
        array('kode_kelompok' => "5", 'kode_jenis' => $aset_lainya['kode_jenis'], 'kode_objek' => '01', 'kode_rincian_objek' => '01', 'kode_sub_rincian_objek' => '001', 'kode_sub_sub_rincian_objek' => '5')
      );
    } elseif ($kode_jenis == '06') {
      $this->db->where(
        array('kode_kelompok' => "5", 'kode_jenis' => $aset_lainya['kode_jenis'], 'kode_objek' => '01', 'kode_rincian_objek' => '01', 'kode_sub_rincian_objek' => '001', 'kode_sub_sub_rincian_objek' => '5')
      );
    } elseif ($kode_jenis == '5.03') {
      $this->db->where(
        array('kode_kelompok' => "5", 'kode_jenis' => $aset_lainya['kode_jenis'], 'kode_objek' => '01', 'kode_rincian_objek' => '01', 'kode_sub_rincian_objek' => '001', 'kode_sub_sub_rincian_objek' => '5')
      );
    }
    $res = $this->db->get('tbl_kode_barang');
    // die($this->db->last_query());
    return  $res->row_array();
  }

  function get_kib_by_jenis_id($kode_jenis, $id_kib)
  {
    // $res = $this->db->get_where('view_kib',  array('kode_jenis' => $kode_jenis, 'id_kib' => $id_kib));
    // return $res->row_array();
    $jenis = $this->global_model->kode_jenis[$kode_jenis];
    $res = $this->db->get_where($jenis['table'],  array($jenis['id_name'] => $id_kib));
    return $res->row_array();
  }
}

/* End of file mutasi_model.php */
/* Location: ./application/models/mutasi_model.php */
/* Please DO NOT modify this information : */
/* Generated by Harviacode Codeigniter CRUD Generator 2018-08-24 05:56:49 */
/* http://harviacode.com */
