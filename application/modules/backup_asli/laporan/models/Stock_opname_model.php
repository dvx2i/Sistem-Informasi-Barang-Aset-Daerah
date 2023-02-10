<?php

if (!defined('BASEPATH'))
  exit('No direct script access allowed');

class Stock_opname_model extends CI_Model
{

  public $table = 'tbl_stock_opname';
  public $id = 'id';
  public $order = 'DESC';
  public $tbl_barang = 'tbl_kode_barang';
  public $tbl_kode_lokasi = 'tbl_kode_lokasi';

  function __construct()
  {
    parent::__construct();
  }

  // datatables
  function json()
  { //die(explode('.',$this->session->userdata('session')->kode_lokasi)[4]);
    $this->load->helper('my_datatable');
    $this->datatables->select('A.id,A.bulan,A.tahun,A.id_pemilik,A.id_pengguna,A.id_kode_lokasi,A.locked_skpd,locked_admin,instansi');
    $this->datatables->from('tbl_stock_opname A');
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
        if($this->input->post('id_pemilik') != ''){
          $this->datatables->where('A.id_pemilik', $this->input->post('id_pemilik')); // where id pemilik
        }
        
        if($this->input->post('id_pengguna') != ''){
          $this->datatables->where('A.id_pengguna', $this->input->post('id_pengguna')); // where id pengguna
        }
      }
      // else{
      //   $this->datatables->where('C.kuasa_pengguna', '*'); // where id kuasa pengguna
      // }
    $this->db->order_by('bulan', 'DESC');
    $this->datatables->edit_column('bulan', '$1', 'bulan_indo(bulan)');
    $this->datatables->edit_column('id', '$1', 'encrypt_id(id)');
    $this->datatables->add_column('action', '$1', 'get_action_stock_opname(' . $session->id_role . ',locked_admin,locked_skpd,' . $this->id . ')');
    return $this->datatables->generate();
     //$this->datatables->generate();
     //die($this->db->last_query());
  }

  function set_stock_opname($data)
  {
    $queri = null;
    $res = null;
    // print_r($data['id_kode_lokasi']); die;
    // die("call proc_rekap_mutasi_objek(" . $data['session']->id_user . "," . $data['id_pemilik'] . "," . $data['id_kode_lokasi'] . ",'" . $data['start_bulan'] . "','" . $data['start_tahun'] . "','" . $data['last_bulan'] . "','" . $data['last_tahun'] . "',  '" . $data['intra_ekstra'] . "')");
    $query    = $this->db->query("call proc_stock_opname(" . $data['session']->id_user . "," . $data['id_pemilik'] . "," . $data['id_kode_lokasi'] . ",'" . $data['start_bulan'] . "','" . $data['start_tahun'] . "','" . $data['last_bulan'] . "','" . $data['last_tahun'] . "',  '" . $data['intra_ekstra'] . "')");
    $res      = $query->result_array();
   


    //add this two line
    $query->next_result();
    $query->free_result();
    //end of new code

    // die(json_encode($res));
    return $res;
    // return
    // $this->db->get()->result_array();
    // die($this->db->last_query());
  }
  
  // get data by id
  function get_locked($bulan,$tahun,$id_kode_lokasi)
  {
    $session = $this->session->userdata('session');
    $this->db->where('bulan >', $bulan);
    $this->db->where('tahun >=', $tahun);
    $this->db->where('locked_admin >', 0, FALSE);
    // $this->db->or_where('locked_skpd = 1)', NULL, FALSE);
    $this->db->where('id_kode_lokasi', $id_kode_lokasi);
    return $this->db->get('tbl_stock_opname')->num_rows();
    // echo $this->db->last_query(); die;
  }
  
  // get data by id
  function get_unlocked($bulan,$tahun,$id_kode_lokasi)
  {
    $session = $this->session->userdata('session');
    $this->db->where('bulan <', $bulan);
    $this->db->where('tahun <=', $tahun);
    // if ($session->id_role == '1') {
    // $this->db->where('locked_admin <', 2, false);
    // }else {
      $this->db->where('locked_admin', '0');
      // }
    $this->db->where('id_kode_lokasi', $id_kode_lokasi);
   return $this->db->get('tbl_stock_opname')->num_rows();
    // echo $this->db->last_query(); die;
  }

  
  // get data by id
  function get_id_lokasi_by_pengguna($pengguna)
  {
    $this->db->where('pengguna', $pengguna);
    $this->db->where('kuasa_pengguna', '*');
    return $this->db->get('tbl_kode_lokasi')->row();
  }
  // get data by id
  function get_lokasi_by_pengguna($pengguna)
  {
    $this->db->where('pengguna', $pengguna);
    $this->db->where('kuasa_pengguna <>', '*');
    return $this->db->get('tbl_kode_lokasi')->result_array();
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

  function get_last_data($data)
  {
    $this->db->select('bulan,tahun');
    $this->db->from('tbl_stock_opname');
    $this->db->where('id_pemilik', $data['id_pemilik']);
    $this->db->where('id_pengguna', $data['id_pengguna']);
    $this->db->where('id_kode_lokasi', $data['id_kode_lokasi']);
    $this->db->order_by('created_at', 'DESC');
    $this->db->limit('1');
    $data = $this->db->get()->result_array();
    //die($this->db->last_query());
    return $data;
  }

  function get_by_lokasi($id,$bulan,$tahun)
  {
    $this->db->select('*');
    $this->db->from('tbl_stock_opname');
    $this->db->where('id_kode_lokasi', $id);
    $this->db->where('bulan', $bulan);
    $this->db->where('tahun', $tahun);
    $this->db->order_by('created_at', 'DESC');
    $this->db->limit('1');
    $data = $this->db->get();
    //die($this->db->last_query());
    return $data;
  }

  // get total rows
  /*
  function total_rows($q = NULL)
  {
    $this->db->like('id_kib_b', $q);
    $this->db->or_like('kode_barang', $q);
    $this->db->or_like('nama_barang', $q);
    $this->db->or_like('nomor_register', $q);
    $this->db->or_like('merk_type', $q);
    $this->db->or_like('ukuran_cc', $q);
    $this->db->or_like('bahan', $q);
    $this->db->or_like('tahun_pembelian', $q);
    $this->db->or_like('nomor_pabrik', $q);
    $this->db->or_like('nomor_rangka', $q);
    $this->db->or_like('nomor_mesin', $q);
    $this->db->or_like('nomor_polisi', $q);
    $this->db->or_like('nomor_bpkb', $q);
    $this->db->or_like('asal_usul', $q);
    $this->db->or_like('harga', $q);
    $this->db->or_like('keterangan', $q);
    $this->db->or_like('kode_lokasi', $q);
    $this->db->or_like('created_at', $q);
    $this->db->or_like('updated_at', $q);
    // $this->db->where(" (id_kode_barang_aset_lainya is NULL and status_barang <> 'aset_lainya') ", NULL, FALSE);
    $this->db->from($this->table);
    return $this->db->count_all_results();
  }

  // get data with limit and search
  function get_limit_data($limit, $start = 0, $q = NULL)
  {
    $this->db->order_by($this->id, $this->order);
    $this->db->like('id_kib_b', $q);
    $this->db->or_like('kode_barang', $q);
    $this->db->or_like('nama_barang', $q);
    $this->db->or_like('nomor_register', $q);
    $this->db->or_like('merk_type', $q);
    $this->db->or_like('ukuran_cc', $q);
    $this->db->or_like('bahan', $q);
    $this->db->or_like('tahun_pembelian', $q);
    $this->db->or_like('nomor_pabrik', $q);
    $this->db->or_like('nomor_rangka', $q);
    $this->db->or_like('nomor_mesin', $q);
    $this->db->or_like('nomor_polisi', $q);
    $this->db->or_like('nomor_bpkb', $q);
    $this->db->or_like('asal_usul', $q);
    $this->db->or_like('harga', $q);
    $this->db->or_like('keterangan', $q);
    $this->db->or_like('kode_lokasi', $q);
    $this->db->or_like('created_at', $q);
    $this->db->or_like('updated_at', $q);
    $this->db->limit($limit, $start);
    return $this->db->get($this->table)->result();
  }
*/
function total_rows($q = NULL)
  {
    $this->db->like('id_kib_b', $q);
    $this->db->or_like('kode_barang', $q);
    $this->db->or_like('nama_barang', $q);
    $this->db->or_like('nomor_register', $q);
     $this->db->or_like('merk_type', $q);
    $this->db->or_like('ukuran_cc', $q);
    $this->db->or_like('bahan', $q);
    $this->db->or_like('tahun_pembelian', $q);
    $this->db->or_like('nomor_polisi', $q);
   
    $this->db->or_like('asal_usul', $q);
    $this->db->or_like('harga', $q);
    $this->db->or_like('keterangan', $q);
    $this->db->or_like('kode_lokasi', $q);
    // $this->db->where(" (id_kode_barang_aset_lainya is NULL and status_barang <> 'aset_lainya') ", NULL, FALSE);
    $this->db->from($this->table);
    return $this->db->count_all_results();
  }

  // get data with limit and search
  function get_limit_data($limit, $start = 0, $q = NULL)
  {
    $this->db->order_by($this->id, $this->order);
    $this->db->like('id_kib_b', $q);
    $this->db->or_like('kode_barang', $q);
    $this->db->or_like('nama_barang', $q);
    $this->db->or_like('nomor_register', $q);
     $this->db->or_like('merk_type', $q);
    $this->db->or_like('ukuran_cc', $q);
    $this->db->or_like('bahan', $q);
    $this->db->or_like('tahun_pembelian', $q);
    $this->db->or_like('nomor_polisi', $q);
    
    $this->db->or_like('asal_usul', $q);
    $this->db->or_like('harga', $q);
    $this->db->or_like('keterangan', $q);
    $this->db->or_like('kode_lokasi', $q);
   
    $this->db->limit($limit, $start);
    return $this->db->get($this->table)->result();
  }
  // insert data
  function insert($data)
  {
    // die(json_encode($data));
    $this->db->insert($this->table, $data);
    // die($this->db->last_query());
    return $this->db->insert_id();
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

  function get_rekap_skpd($tahun)
  {
    $session = $this->session->userdata('session');
    $lokasi = explode('.', $session->kode_lokasi);
    $pengguna = $lokasi[3];
    $kuasa_pengguna =  $lokasi[4];

    // die($kuasa_pengguna);
    $sql = "SELECT L.`instansi`,L.pengguna,L.id_kode_lokasi,IFNULL(Q.Jan,'x') Jan,IFNULL(Q.Feb,'x') Feb,IFNULL(Q.Mar,'x') Mar,IFNULL(Q.Apr,'x') Apr,IFNULL(Q.Mei,'x') Mei,IFNULL(Q.Jun,'x') Jun,IFNULL(Q.Jul,'x') Jul,IFNULL(Q.Agu,'x') Agu,IFNULL(Q.Sep,'x') Sep,IFNULL(Q.Okt,'x') Okt,IFNULL(Q.Nov,'x') Nov,IFNULL(Q.Des,'x') Des FROM(
      SELECT KL.`instansi`,A.id_pengguna,A.id_kode_lokasi,
      (SELECT CASE WHEN COUNT(*) >= (SELECT COUNT(*) FROM tbl_kode_lokasi SKL WHERE SKL.pengguna = KL.`pengguna` AND SKL.kuasa_pengguna != '*' ) THEN 'v' ELSE 'x' END AS bulan FROM tbl_stock_opname SA WHERE bulan = '1' AND tahun = '".$tahun."' AND SA.id_pengguna =  A.`id_pengguna` AND locked_admin > 0) AS `Jan`,
      (SELECT CASE WHEN COUNT(*) >= (SELECT COUNT(*) FROM tbl_kode_lokasi SKL WHERE SKL.pengguna = KL.`pengguna` AND SKL.kuasa_pengguna != '*' ) THEN 'v' ELSE 'x' END AS bulan FROM tbl_stock_opname SA WHERE bulan = '2' AND tahun = '".$tahun."' AND SA.id_pengguna =  A.`id_pengguna` AND locked_admin > 0) AS `Feb`,
      (SELECT CASE WHEN COUNT(*) >= (SELECT COUNT(*) FROM tbl_kode_lokasi SKL WHERE SKL.pengguna = KL.`pengguna` AND SKL.kuasa_pengguna != '*' ) THEN 'v' ELSE 'x' END AS bulan FROM tbl_stock_opname SA WHERE bulan = '3' AND tahun = '".$tahun."' AND SA.id_pengguna =  A.`id_pengguna` AND locked_admin > 0) AS `Mar`,
      (SELECT CASE WHEN COUNT(*) >= (SELECT COUNT(*) FROM tbl_kode_lokasi SKL WHERE SKL.pengguna = KL.`pengguna` AND SKL.kuasa_pengguna != '*' ) THEN 'v' ELSE 'x' END AS bulan FROM tbl_stock_opname SA WHERE bulan = '4' AND tahun = '".$tahun."' AND SA.id_pengguna =  A.`id_pengguna` AND locked_admin > 0) AS `Apr`,
      (SELECT CASE WHEN COUNT(*) >= (SELECT COUNT(*) FROM tbl_kode_lokasi SKL WHERE SKL.pengguna = KL.`pengguna` AND SKL.kuasa_pengguna != '*' ) THEN 'v' ELSE 'x' END AS bulan FROM tbl_stock_opname SA WHERE bulan = '5' AND tahun = '".$tahun."' AND SA.id_pengguna =  A.`id_pengguna` AND locked_admin > 0) AS `Mei`,
      (SELECT CASE WHEN COUNT(*) >= (SELECT COUNT(*) FROM tbl_kode_lokasi SKL WHERE SKL.pengguna = KL.`pengguna` AND SKL.kuasa_pengguna != '*' ) THEN 'v' ELSE 'x' END AS bulan FROM tbl_stock_opname SA WHERE bulan = '6' AND tahun = '".$tahun."' AND SA.id_pengguna =  A.`id_pengguna` AND locked_admin > 0) AS `Jun`,
      (SELECT CASE WHEN COUNT(*) >= (SELECT COUNT(*) FROM tbl_kode_lokasi SKL WHERE SKL.pengguna = KL.`pengguna` AND SKL.kuasa_pengguna != '*' ) THEN 'v' ELSE 'x' END AS bulan FROM tbl_stock_opname SA WHERE bulan = '7' AND tahun = '".$tahun."' AND SA.id_pengguna =  A.`id_pengguna` AND locked_admin > 0) AS `Jul`,
      (SELECT CASE WHEN COUNT(*) >= (SELECT COUNT(*) FROM tbl_kode_lokasi SKL WHERE SKL.pengguna = KL.`pengguna` AND SKL.kuasa_pengguna != '*' ) THEN 'v' ELSE 'x' END AS bulan FROM tbl_stock_opname SA WHERE bulan = '8' AND tahun = '".$tahun."' AND SA.id_pengguna =  A.`id_pengguna` AND locked_admin > 0) AS `Agu`,
      (SELECT CASE WHEN COUNT(*) >= (SELECT COUNT(*) FROM tbl_kode_lokasi SKL WHERE SKL.pengguna = KL.`pengguna` AND SKL.kuasa_pengguna != '*' ) THEN 'v' ELSE 'x' END AS bulan FROM tbl_stock_opname SA WHERE bulan = '9' AND tahun = '".$tahun."' AND SA.id_pengguna =  A.`id_pengguna` AND locked_admin > 0) AS `Sep`,
      (SELECT CASE WHEN COUNT(*) >= (SELECT COUNT(*) FROM tbl_kode_lokasi SKL WHERE SKL.pengguna = KL.`pengguna` AND SKL.kuasa_pengguna != '*' ) THEN 'v' ELSE 'x' END AS bulan FROM tbl_stock_opname SA WHERE bulan = '10' AND tahun = '".$tahun."' AND SA.id_pengguna =  A.`id_pengguna` AND locked_admin > 0) AS `Okt`,
      (SELECT CASE WHEN COUNT(*) >= (SELECT COUNT(*) FROM tbl_kode_lokasi SKL WHERE SKL.pengguna = KL.`pengguna` AND SKL.kuasa_pengguna != '*' ) THEN 'v' ELSE 'x' END AS bulan FROM tbl_stock_opname SA WHERE bulan = '11' AND tahun = '".$tahun."' AND SA.id_pengguna =  A.`id_pengguna` AND locked_admin > 0) AS `Nov`,
      (SELECT CASE WHEN COUNT(*) >= (SELECT COUNT(*) FROM tbl_kode_lokasi SKL WHERE SKL.pengguna = KL.`pengguna` AND SKL.kuasa_pengguna != '*' ) THEN 'v' ELSE 'x' END AS bulan FROM tbl_stock_opname SA WHERE bulan = '12' AND tahun = '".$tahun."' AND SA.id_pengguna =  A.`id_pengguna` AND locked_admin > 0) AS `Des`
      FROM tbl_stock_opname A
      JOIN tbl_kode_lokasi KL ON KL.`pengguna` = A.`id_pengguna` AND KL.`kuasa_pengguna` = '*' AND KL.`pengguna_opd` != '*'
      WHERE tahun = '".$tahun."'
      GROUP BY tahun,id_pengguna
      ) Q
      RIGHT JOIN tbl_kode_lokasi L ON Q.id_pengguna = L.`pengguna` ";
      //jika super admin muncul semua
      if($session->id_role == '1'){
      $sql .="
      WHERE L.kuasa_pengguna = '*' AND L.`pengguna_opd` != '*'
      ORDER BY L.kode_lokasi ASC";
      }
      // muncul sesuai skpd masing"
      else {
        $sql .="
        WHERE L.kuasa_pengguna = '*' AND L.`pengguna` = '".$pengguna."'
        ORDER BY L.kode_lokasi ASC";
        }

      $result = $this->db->query($sql);
      return $result->result_array();
  }
  
  function get_rekap_lokasi($tahun)
  {
    $session = $this->session->userdata('session');
    $lokasi = explode('.', $session->kode_lokasi);
    $pengguna = $lokasi[3];
    $kuasa_pengguna =  $lokasi[4];

    $sql = "SELECT L.`instansi`,L.pengguna,L.id_kode_lokasi,IFNULL(Q.Jan,'x') Jan,IFNULL(Q.Feb,'x') Feb,IFNULL(Q.Mar,'x') Mar,IFNULL(Q.Apr,'x') Apr,IFNULL(Q.Mei,'x') Mei,IFNULL(Q.Jun,'x') Jun,IFNULL(Q.Jul,'x') Jul,IFNULL(Q.Agu,'x') Agu,IFNULL(Q.Sep,'x') Sep,IFNULL(Q.Okt,'x') Okt,IFNULL(Q.Nov,'x') Nov,IFNULL(Q.Des,'x') Des FROM(
      SELECT id_pengguna,id_kode_lokasi,
    (SELECT CASE WHEN locked_admin = 1 THEN 'y' WHEN locked_admin = 2 THEN 'v' ELSE 'x' END AS bulan FROM tbl_stock_opname SA WHERE bulan = '1' AND tahun = '".$tahun."' AND SA.id_kode_lokasi =  A.`id_kode_lokasi` AND locked_admin > 0) AS `Jan`,
    (SELECT CASE WHEN locked_admin = 1 THEN 'y' WHEN locked_admin = 2  THEN 'v' ELSE 'x' END AS bulan FROM tbl_stock_opname SA WHERE bulan = '2' AND tahun = '".$tahun."' AND SA.id_kode_lokasi =  A.`id_kode_lokasi` AND locked_admin > 0) AS `Feb`,
    (SELECT CASE WHEN locked_admin = 1 THEN 'y' WHEN locked_admin = 2  THEN 'v' ELSE 'x' END AS bulan FROM tbl_stock_opname SA WHERE bulan = '3' AND tahun = '".$tahun."' AND SA.id_kode_lokasi =  A.`id_kode_lokasi` AND locked_admin > 0) AS `Mar`,
    (SELECT CASE WHEN locked_admin = 1 THEN 'y' WHEN locked_admin = 2  THEN 'v' ELSE 'x' END AS bulan FROM tbl_stock_opname SA WHERE bulan = '4' AND tahun = '".$tahun."' AND SA.id_kode_lokasi =  A.`id_kode_lokasi` AND locked_admin > 0) AS `Apr`,
    (SELECT CASE WHEN locked_admin = 1 THEN 'y' WHEN locked_admin = 2  THEN 'v' ELSE 'x' END AS bulan FROM tbl_stock_opname SA WHERE bulan = '5' AND tahun = '".$tahun."' AND SA.id_kode_lokasi =  A.`id_kode_lokasi` AND locked_admin > 0) AS `Mei`,
    (SELECT CASE WHEN locked_admin = 1 THEN 'y' WHEN locked_admin = 2  THEN 'v' ELSE 'x' END AS bulan FROM tbl_stock_opname SA WHERE bulan = '6' AND tahun = '".$tahun."' AND SA.id_kode_lokasi =  A.`id_kode_lokasi` AND locked_admin > 0) AS `Jun`,
    (SELECT CASE WHEN locked_admin = 1 THEN 'y' WHEN locked_admin = 2  THEN 'v' ELSE 'x' END AS bulan FROM tbl_stock_opname SA WHERE bulan = '7' AND tahun = '".$tahun."' AND SA.id_kode_lokasi =  A.`id_kode_lokasi` AND locked_admin > 0) AS `Jul`,
    (SELECT CASE WHEN locked_admin = 1 THEN 'y' WHEN locked_admin = 2  THEN 'v' ELSE 'x' END AS bulan FROM tbl_stock_opname SA WHERE bulan = '8' AND tahun = '".$tahun."' AND SA.id_kode_lokasi =  A.`id_kode_lokasi` AND locked_admin > 0) AS `Agu`,
    (SELECT CASE WHEN locked_admin = 1 THEN 'y' WHEN locked_admin = 2  THEN 'v' ELSE 'x' END AS bulan FROM tbl_stock_opname SA WHERE bulan = '9' AND tahun = '".$tahun."' AND SA.id_kode_lokasi =  A.`id_kode_lokasi` AND locked_admin > 0) AS `Sep`,
    (SELECT CASE WHEN locked_admin = 1 THEN 'y' WHEN locked_admin = 2  THEN 'v' ELSE 'x' END AS bulan FROM tbl_stock_opname SA WHERE bulan = '10' AND tahun = '".$tahun."' AND SA.id_kode_lokasi =  A.`id_kode_lokasi` AND locked_admin > 0) AS `Okt`,
    (SELECT CASE WHEN locked_admin = 1 THEN 'y' WHEN locked_admin = 2  THEN 'v' ELSE 'x' END AS bulan FROM tbl_stock_opname SA WHERE bulan = '11' AND tahun = '".$tahun."' AND SA.id_kode_lokasi =  A.`id_kode_lokasi` AND locked_admin > 0) AS `Nov`,
    (SELECT CASE WHEN locked_admin = 1 THEN 'y' WHEN locked_admin = 2  THEN 'v' ELSE 'x' END AS bulan FROM tbl_stock_opname SA WHERE bulan = '12' AND tahun = '".$tahun."' AND SA.id_kode_lokasi =  A.`id_kode_lokasi` AND locked_admin > 0) AS `Des`
    FROM tbl_stock_opname A
    WHERE tahun = '".$tahun."'
    GROUP BY tahun,id_pengguna,id_kode_lokasi) Q
      RIGHT JOIN tbl_kode_lokasi L ON Q.id_kode_lokasi = L.`id_kode_lokasi` ";
      //jika super admin muncul semua
      if($session->id_role == '1'){
      $sql .="
      WHERE L.kuasa_pengguna != '*'
      ORDER BY L.kode_lokasi ASC";
      }
      // jika admin skpd/kepala skpd muncul sesuai skpd masing"
      else if($kuasa_pengguna == '0001'){
        $sql .="
        WHERE L.kuasa_pengguna != '*' AND L.`pengguna` = '".$pengguna."'
        ORDER BY L.kode_lokasi ASC";
      }
      else {
        $sql .="
        WHERE L.kuasa_pengguna = '$kuasa_pengguna' AND L.`pengguna` = '".$pengguna."'
        ORDER BY L.kode_lokasi ASC";
        }

      $result = $this->db->query($sql);
      return $result->result_array();
  }
}

/* End of file Kib_b_model.php */
/* Location: ./application/models/Kib_b_model.php */
/* Please DO NOT modify this information : */
/* Generated by Harviacode Codeigniter CRUD Generator 2018-08-07 08:13:19 */
/* http://harviacode.com */
