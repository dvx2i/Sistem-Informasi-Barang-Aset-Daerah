<?php

if (!defined('BASEPATH')) exit('No direct script access allowed');

class Dashboard_model extends CI_Model
{

    public $view_lokasi = 'view_lokasi';
    public $view_pengguna = 'view_pengguna';
    public $view_kuasa_pengguna = 'view_kuasa_pengguna';


    function __construct()
    {
        parent::__construct();
    }



    function get_nominal_kib($id_kode_lokasi)
    {
        $session = $this->session->userdata('session');
		//print_r($session->id_role);
	if($session->id_role =='1'){
		$where_lokasi = '';
		$groupby = 'GROUP by kode_jenis'; 
	}else{
		$where_lokasi = "where CONVERT(id_kode_lokasi,signed) = '".$id_kode_lokasi."'";
		$groupby = 'GROUP by id_kode_lokasi, kode_jenis'; 
	}
    
        $query = "
        SELECT  A.kode_jenis , A.nama_jenis, coalesce(B.harga,0) as harga 
        FROM  tbl_master_jenis A
        left join (
            SELECT  id_kode_lokasi, kode_jenis , SUM(coalesce(harga,0)) harga
            FROM  view_kib 
            $where_lokasi 
            $groupby
        ) B on CONVERT(A.kode_jenis,char) = CONVERT(B.kode_jenis,char)
      ";
        $res = $this->db->query($query);
        return $res->result_array();
    }
	 function get_nominal_kib_a($id_kode_lokasi)
    {
        $session = $this->session->userdata('session');
		//print_r($session->id_role);
	if($session->id_role =='1'){
		$where_lokasi = '';
		$groupby = 'GROUP by kode_jenis'; 
	}else{
		$where_lokasi = "AND CONVERT(id_kode_lokasi,signed) = '".$id_kode_lokasi."'";
		$groupby = 'GROUP by id_kode_lokasi, kode_jenis'; 
	}
    
        $query = "
        SELECT  A.kode_jenis , A.nama_jenis, coalesce(B.harga,0) as harga 
        FROM  tbl_master_jenis A
        left join (
            select '01' AS `kode_jenis`,`tbl_kib_a`.`id_kode_lokasi` AS `id_kode_lokasi`,`tbl_kib_a`.`kode_lokasi` AS `kode_lokasi`,SUM(coalesce(`tbl_kib_a`.`harga`,0)) AS `harga` from `tbl_kib_a` 
            LEFT JOIN tbl_kode_barang B ON tbl_kib_a.id_kode_barang = B.id_kode_barang 
            WHERE kode_kelompok = '3' AND kode_jenis = '01'
            $where_lokasi 
           
        ) B on CONVERT(A.kode_jenis,char) = CONVERT(B.kode_jenis,char)
      ";
        $res = $this->db->query($query);
        return $res->result_array()[0];
    }
	 function get_nominal_kib_b($id_kode_lokasi)
    {
        $session = $this->session->userdata('session');
		//print_r($session->id_role);
	if($session->id_role =='1'){
		$where_lokasi = '';
		$groupby = 'GROUP by kode_jenis'; 
	}else{
		$where_lokasi = "AND CONVERT(id_kode_lokasi,signed) = '".$id_kode_lokasi."'";
		$groupby = 'GROUP by id_kode_lokasi, kode_jenis'; 
	}
    
        $query = "
        SELECT  A.kode_jenis , A.nama_jenis, coalesce(B.harga,0) as harga 
        FROM  tbl_master_jenis A
        left join (
            
			select '02' AS `kode_jenis`,`tbl_kib_b`.`id_kode_lokasi` AS `id_kode_lokasi`,`tbl_kib_b`.`kode_lokasi` AS `kode_lokasi`,SUM(coalesce(`tbl_kib_b`.`harga`,0)) AS `harga` from `tbl_kib_b` 
            LEFT JOIN tbl_kode_barang B ON tbl_kib_b.id_kode_barang = B.id_kode_barang 
            WHERE kode_kelompok = '3' AND kode_jenis = '02'
            $where_lokasi 
        ) B on CONVERT(A.kode_jenis,char) = CONVERT(B.kode_jenis,char)
      ";
        $res = $this->db->query($query);
        return $res->result_array()[0];
    }
	 function get_nominal_kib_c($id_kode_lokasi)
    {
        $session = $this->session->userdata('session');
		//print_r($session->id_role);
	if($session->id_role =='1'){
		$where_lokasi = '';
		$groupby = 'GROUP by kode_jenis'; 
	}else{
		$where_lokasi = "AND CONVERT(id_kode_lokasi,signed) = '".$id_kode_lokasi."'";
		$groupby = 'GROUP by id_kode_lokasi, kode_jenis'; 
	}
    
        $query = "
        SELECT  A.kode_jenis , A.nama_jenis, coalesce(B.harga,0) as harga 
        FROM  tbl_master_jenis A
        left join (
            select '03' AS `kode_jenis`,`tbl_kib_c`.`id_kode_lokasi` AS `id_kode_lokasi`,`tbl_kib_c`.`kode_lokasi` AS `kode_lokasi`,SUM(coalesce(`tbl_kib_c`.`harga`,0)) AS `harga` from `tbl_kib_c` 
            LEFT JOIN tbl_kode_barang B ON tbl_kib_c.id_kode_barang = B.id_kode_barang 
            WHERE kode_kelompok = '3' AND kode_jenis = '03'
            $where_lokasi 
        ) B on CONVERT(A.kode_jenis,char) = CONVERT(B.kode_jenis,char)
      ";
        $res = $this->db->query($query);
        return $res->result_array()[0];
    }
	 function get_nominal_kib_d($id_kode_lokasi)
    {
        $session = $this->session->userdata('session');
		//print_r($session->id_role);
	if($session->id_role =='1'){
		$where_lokasi = '';
		$groupby = 'GROUP by kode_jenis'; 
	}else{
		$where_lokasi = "AND CONVERT(id_kode_lokasi,signed) = '".$id_kode_lokasi."'";
		$groupby = 'GROUP by id_kode_lokasi, kode_jenis'; 
	}
    
        $query = "
        SELECT  A.kode_jenis , A.nama_jenis, coalesce(B.harga,0) as harga 
        FROM  tbl_master_jenis A
        left join (
            select '04' AS `kode_jenis`,`tbl_kib_d`.`id_kode_lokasi` AS `id_kode_lokasi`,`tbl_kib_d`.`kode_lokasi` AS `kode_lokasi`,SUM(coalesce(`tbl_kib_d`.`harga`,0)) AS `harga` from `tbl_kib_d` 
            LEFT JOIN tbl_kode_barang B ON tbl_kib_d.id_kode_barang = B.id_kode_barang 
            WHERE kode_kelompok = '3' AND kode_jenis = '04'
            $where_lokasi 
        ) B on CONVERT(A.kode_jenis,char) = CONVERT(B.kode_jenis,char)
      ";
        $res = $this->db->query($query);
        return $res->result_array()[0];
    }
	 function get_nominal_kib_e($id_kode_lokasi)
    {
        $session = $this->session->userdata('session');
		//print_r($session->id_role);
	if($session->id_role =='1'){
		$where_lokasi = '';
		$groupby = 'GROUP by kode_jenis'; 
	}else{
		$where_lokasi = "AND CONVERT(id_kode_lokasi,signed) = '".$id_kode_lokasi."'";
		$groupby = 'GROUP by id_kode_lokasi, kode_jenis'; 
	}
    
        $query = "
        SELECT  A.kode_jenis , A.nama_jenis, coalesce(B.harga,0) as harga 
        FROM  tbl_master_jenis A
        left join (
            select '05' AS `kode_jenis`,`tbl_kib_e`.`id_kode_lokasi` AS `id_kode_lokasi`,`tbl_kib_e`.`kode_lokasi` AS `kode_lokasi`,SUM(coalesce(`tbl_kib_e`.`harga`,0)) AS `harga` from `tbl_kib_e` 
            LEFT JOIN tbl_kode_barang B ON tbl_kib_e.id_kode_barang = B.id_kode_barang 
            WHERE kode_kelompok = '3' AND kode_jenis = '05'
            $where_lokasi 
        ) B on CONVERT(A.kode_jenis,char) = CONVERT(B.kode_jenis,char)
      ";
        $res = $this->db->query($query);
        return $res->result_array()[0];
    }
	 function get_nominal_kib_f($id_kode_lokasi)
    {
        $session = $this->session->userdata('session');
		//print_r($session->id_role);
	if($session->id_role =='1'){
		$where_lokasi = '';
		$groupby = 'GROUP by kode_jenis'; 
	}else{
		$where_lokasi = "AND CONVERT(id_kode_lokasi,signed) = '".$id_kode_lokasi."'";
		$groupby = 'GROUP by id_kode_lokasi, kode_jenis'; 
	}
    
        $query = "
        SELECT  A.kode_jenis , A.nama_jenis, coalesce(B.harga,0) as harga 
        FROM  tbl_master_jenis A
        left join (
           select '06' AS `kode_jenis`,`tbl_kib_f`.`id_kode_lokasi` AS `id_kode_lokasi`,`tbl_kib_f`.`kode_lokasi` AS `kode_lokasi`,SUM(coalesce(`tbl_kib_f`.`nilai_kontrak`,0)) AS `harga` from `tbl_kib_f` 
           LEFT JOIN tbl_kode_barang B ON tbl_kib_f.id_kode_barang = B.id_kode_barang 
           WHERE kode_kelompok = '3' AND kode_jenis = '06'
           $where_lokasi 
           
        ) B on CONVERT(A.kode_jenis,char) = CONVERT(B.kode_jenis,char)
      ";
        $res = $this->db->query($query);
        return $res->result_array()[0];
    }
	 function get_nominal_kib_atb($id_kode_lokasi)
    {
        $session = $this->session->userdata('session');
		//print_r($session->id_role);
	if($session->id_role =='1'){
		$where_lokasi = '';
		$groupby = 'GROUP by kode_jenis'; 
	}else{
		$where_lokasi = "AND CONVERT(id_kode_lokasi,signed) = '".$id_kode_lokasi."'";
		$groupby = 'GROUP by id_kode_lokasi, kode_jenis'; 
	}
    
        $query = "
        SELECT  A.kode_jenis , A.nama_jenis, coalesce(B.harga,0) as harga 
        FROM  tbl_master_jenis A
        left join (
            select '5.03' AS `kode_jenis`,`tbl_kib_atb`.`id_kode_lokasi` AS `id_kode_lokasi`,`tbl_kib_atb`.`kode_lokasi` AS `kode_lokasi`,SUM(coalesce(`tbl_kib_atb`.`harga`,0)) AS `harga` from `tbl_kib_atb` 
            LEFT JOIN tbl_kode_barang B ON tbl_kib_atb.id_kode_barang = B.id_kode_barang 
            WHERE kode_kelompok = '5' AND kode_jenis = '03'
            $where_lokasi 
        ) B on CONVERT(A.kode_jenis,char) = CONVERT(B.kode_jenis,char)
      ";
        $res = $this->db->query($query);
        return $res->result_array()[0];
    }

    function get_nominal_kib_kemitraan($id_kode_lokasi)
   {
       $session = $this->session->userdata('session');
       //print_r($session->id_role);
   if($session->id_role =='1'){
       $where_lokasi = '';
       $groupby = 'GROUP by kode_jenis'; 
   }else{
       $where_lokasi = "AND CONVERT(id_kode_lokasi,signed) = '".$id_kode_lokasi."'";
       $groupby = 'GROUP by id_kode_lokasi, kode_jenis'; 
   }
   
       $query = "
       SELECT '5.02' , 'Kemitraan Dengan Pihak Ketiga' nama_jenis, COALESCE(SUM(B.harga),0) as harga 
       FROM  tbl_master_jenis A
       left join (
            select '01' AS `kode_jenis`,`tbl_kib_a`.`id_kode_lokasi` AS `id_kode_lokasi`,`tbl_kib_a`.`kode_lokasi` AS `kode_lokasi`,SUM(coalesce(`tbl_kib_a`.`harga`,0)) AS `harga` from `tbl_kib_a` 
            LEFT JOIN tbl_kode_barang B ON tbl_kib_a.id_kode_barang = B.id_kode_barang 
            WHERE kode_kelompok = '5' AND kode_jenis = '02'
            $where_lokasi 
            UNION ALL
            select '02' AS `kode_jenis`,`tbl_kib_b`.`id_kode_lokasi` AS `id_kode_lokasi`,`tbl_kib_b`.`kode_lokasi` AS `kode_lokasi`,SUM(coalesce(`tbl_kib_b`.`harga`,0)) AS `harga` from `tbl_kib_b` 
            LEFT JOIN tbl_kode_barang B ON tbl_kib_b.id_kode_barang = B.id_kode_barang 
            WHERE kode_kelompok = '5' AND kode_jenis = '02'
            $where_lokasi 
            UNION ALL
            select '03' AS `kode_jenis`,`tbl_kib_c`.`id_kode_lokasi` AS `id_kode_lokasi`,`tbl_kib_c`.`kode_lokasi` AS `kode_lokasi`,SUM(coalesce(`tbl_kib_c`.`harga`,0)) AS `harga` from `tbl_kib_c` 
            LEFT JOIN tbl_kode_barang B ON tbl_kib_c.id_kode_barang = B.id_kode_barang 
            WHERE kode_kelompok = '5' AND kode_jenis = '02'
            $where_lokasi 
            UNION ALL
            select '04' AS `kode_jenis`,`tbl_kib_d`.`id_kode_lokasi` AS `id_kode_lokasi`,`tbl_kib_d`.`kode_lokasi` AS `kode_lokasi`,SUM(coalesce(`tbl_kib_d`.`harga`,0)) AS `harga` from `tbl_kib_d` 
            LEFT JOIN tbl_kode_barang B ON tbl_kib_d.id_kode_barang = B.id_kode_barang 
            WHERE kode_kelompok = '5' AND kode_jenis = '02'
            $where_lokasi 
            UNION ALL
            select '05' AS `kode_jenis`,`tbl_kib_e`.`id_kode_lokasi` AS `id_kode_lokasi`,`tbl_kib_e`.`kode_lokasi` AS `kode_lokasi`,SUM(coalesce(`tbl_kib_e`.`harga`,0)) AS `harga` from `tbl_kib_e` 
            LEFT JOIN tbl_kode_barang B ON tbl_kib_e.id_kode_barang = B.id_kode_barang 
            WHERE kode_kelompok = '5' AND kode_jenis = '02'
            $where_lokasi 
            UNION ALL
            select '06' AS `kode_jenis`,`tbl_kib_f`.`id_kode_lokasi` AS `id_kode_lokasi`,`tbl_kib_f`.`kode_lokasi` AS `kode_lokasi`,SUM(coalesce(`tbl_kib_f`.`nilai_kontrak`,0)) AS `harga` from `tbl_kib_f` 
            LEFT JOIN tbl_kode_barang B ON tbl_kib_f.id_kode_barang = B.id_kode_barang 
            WHERE kode_kelompok = '5' AND kode_jenis = '02'
            $where_lokasi 
            UNION ALL
           select '5.03' AS `kode_jenis`,`tbl_kib_atb`.`id_kode_lokasi` AS `id_kode_lokasi`,`tbl_kib_atb`.`kode_lokasi` AS `kode_lokasi`,SUM(coalesce(`tbl_kib_atb`.`harga`,0)) AS `harga` from `tbl_kib_atb` 
           LEFT JOIN tbl_kode_barang B ON tbl_kib_atb.id_kode_barang = B.id_kode_barang 
           WHERE kode_kelompok = '5' AND kode_jenis = '02'
           $where_lokasi 
       ) B on CONVERT(A.kode_jenis,char) = CONVERT(B.kode_jenis,char)
     ";
    //    die($query);
       $res = $this->db->query($query);
       return $res->result_array()[0];
   }

   function get_nominal_kib_lain($id_kode_lokasi)
  {
      $session = $this->session->userdata('session');
      //print_r($session->id_role);
  if($session->id_role =='1'){
      $where_lokasi = '';
      $groupby = 'GROUP by kode_jenis'; 
  }else{
      $where_lokasi = "AND CONVERT(id_kode_lokasi,signed) = '".$id_kode_lokasi."'";
      $groupby = 'GROUP by id_kode_lokasi, kode_jenis'; 
  }
  
      $query = "
      SELECT '5.02' , 'Aset Lain-Lain' nama_jenis, COALESCE(SUM(B.harga),0) as harga 
      FROM  tbl_master_jenis A
      left join (
           select '01' AS `kode_jenis`,`tbl_kib_a`.`id_kode_lokasi` AS `id_kode_lokasi`,`tbl_kib_a`.`kode_lokasi` AS `kode_lokasi`,SUM(coalesce(`tbl_kib_a`.`harga`,0)) AS `harga` from `tbl_kib_a` 
           LEFT JOIN tbl_kode_barang B ON tbl_kib_a.id_kode_barang = B.id_kode_barang 
           WHERE kode_kelompok = '5' AND kode_jenis = '04'
           $where_lokasi 
           UNION ALL
           select '02' AS `kode_jenis`,`tbl_kib_b`.`id_kode_lokasi` AS `id_kode_lokasi`,`tbl_kib_b`.`kode_lokasi` AS `kode_lokasi`,SUM(coalesce(`tbl_kib_b`.`harga`,0)) AS `harga` from `tbl_kib_b` 
           LEFT JOIN tbl_kode_barang B ON tbl_kib_b.id_kode_barang = B.id_kode_barang 
           WHERE kode_kelompok = '5' AND kode_jenis = '04'
           $where_lokasi 
           UNION ALL
           select '03' AS `kode_jenis`,`tbl_kib_c`.`id_kode_lokasi` AS `id_kode_lokasi`,`tbl_kib_c`.`kode_lokasi` AS `kode_lokasi`,SUM(coalesce(`tbl_kib_c`.`harga`,0)) AS `harga` from `tbl_kib_c` 
           LEFT JOIN tbl_kode_barang B ON tbl_kib_c.id_kode_barang = B.id_kode_barang 
           WHERE kode_kelompok = '5' AND kode_jenis = '04'
           $where_lokasi 
           UNION ALL
           select '04' AS `kode_jenis`,`tbl_kib_d`.`id_kode_lokasi` AS `id_kode_lokasi`,`tbl_kib_d`.`kode_lokasi` AS `kode_lokasi`,SUM(coalesce(`tbl_kib_d`.`harga`,0)) AS `harga` from `tbl_kib_d` 
           LEFT JOIN tbl_kode_barang B ON tbl_kib_d.id_kode_barang = B.id_kode_barang 
           WHERE kode_kelompok = '5' AND kode_jenis = '04'
           $where_lokasi 
           UNION ALL
           select '05' AS `kode_jenis`,`tbl_kib_e`.`id_kode_lokasi` AS `id_kode_lokasi`,`tbl_kib_e`.`kode_lokasi` AS `kode_lokasi`,SUM(coalesce(`tbl_kib_e`.`harga`,0)) AS `harga` from `tbl_kib_e` 
           LEFT JOIN tbl_kode_barang B ON tbl_kib_e.id_kode_barang = B.id_kode_barang 
           WHERE kode_kelompok = '5' AND kode_jenis = '04'
           $where_lokasi 
           UNION ALL
           select '06' AS `kode_jenis`,`tbl_kib_f`.`id_kode_lokasi` AS `id_kode_lokasi`,`tbl_kib_f`.`kode_lokasi` AS `kode_lokasi`,SUM(coalesce(`tbl_kib_f`.`nilai_kontrak`,0)) AS `harga` from `tbl_kib_f` 
           LEFT JOIN tbl_kode_barang B ON tbl_kib_f.id_kode_barang = B.id_kode_barang 
           WHERE kode_kelompok = '5' AND kode_jenis = '04'
           $where_lokasi 
           UNION ALL
          select '5.03' AS `kode_jenis`,`tbl_kib_atb`.`id_kode_lokasi` AS `id_kode_lokasi`,`tbl_kib_atb`.`kode_lokasi` AS `kode_lokasi`,SUM(coalesce(`tbl_kib_atb`.`harga`,0)) AS `harga` from `tbl_kib_atb` 
          LEFT JOIN tbl_kode_barang B ON tbl_kib_atb.id_kode_barang = B.id_kode_barang 
          WHERE kode_kelompok = '5' AND kode_jenis = '04'
          $where_lokasi 
      ) B on CONVERT(A.kode_jenis,char) = CONVERT(B.kode_jenis,char)
    ";
   //    die($query);
      $res = $this->db->query($query);
      return $res->result_array()[0];
  }

	 function get_nominal_kib_dashboard($id_kode_lokasi)
    {
        $session = $this->session->userdata('session');
		//print_r($session->id_role);
	if($session->id_role =='1'){
		$where_lokasi = '';
		$groupby = 'GROUP by A.kode_jenis'; 
	}else{
		$where_lokasi = "where CONVERT(B.id_kode_lokasi,signed) = '".$id_kode_lokasi."'";
		$groupby = 'GROUP by B.id_kode_lokasi, A.kode_jenis'; 
	}
    
        $query = "
        SELECT  A.kode_jenis , A.nama_jenis, coalesce(B.harga,0) as harga 
        FROM  tbl_master_jenis A
        left join (
            select '01' AS `kode_jenis`,`tbl_kib_a`.`id_kode_lokasi` AS `id_kode_lokasi`,`tbl_kib_a`.`kode_lokasi` AS `kode_lokasi`,SUM(coalesce(`tbl_kib_a`.`harga`,0)) AS `harga` from `tbl_kib_a`  
union all
select '02' AS `kode_jenis`,`tbl_kib_b`.`id_kode_lokasi` AS `id_kode_lokasi`,`tbl_kib_b`.`kode_lokasi` AS `kode_lokasi`,SUM(coalesce(`tbl_kib_b`.`harga`,0)) AS `harga` from `tbl_kib_b` 
union all
select '03' AS `kode_jenis`,`tbl_kib_c`.`id_kode_lokasi` AS `id_kode_lokasi`,`tbl_kib_c`.`kode_lokasi` AS `kode_lokasi`,SUM(coalesce(`tbl_kib_c`.`harga`,0)) AS `harga` from `tbl_kib_c` 
union all
select '04' AS `kode_jenis`,`tbl_kib_d`.`id_kode_lokasi` AS `id_kode_lokasi`,`tbl_kib_d`.`kode_lokasi` AS `kode_lokasi`,SUM(coalesce(`tbl_kib_d`.`harga`,0)) AS `harga` from `tbl_kib_d` 
union all
select '05' AS `kode_jenis`,`tbl_kib_e`.`id_kode_lokasi` AS `id_kode_lokasi`,`tbl_kib_e`.`kode_lokasi` AS `kode_lokasi`,SUM(coalesce(`tbl_kib_e`.`harga`,0)) AS `harga` from `tbl_kib_e` 
union all
select '06' AS `kode_jenis`,`tbl_kib_f`.`id_kode_lokasi` AS `id_kode_lokasi`,`tbl_kib_f`.`kode_lokasi` AS `kode_lokasi`,SUM(coalesce(`tbl_kib_f`.`nilai_kontrak`,0)) AS `harga` from `tbl_kib_f` 
union all
select '5.03' AS `kode_jenis`,`tbl_kib_atb`.`id_kode_lokasi` AS `id_kode_lokasi`,`tbl_kib_atb`.`kode_lokasi` AS `kode_lokasi`,SUM(coalesce(`tbl_kib_atb`.`harga`,0)) AS `harga` from `tbl_kib_atb` 
            
        ) B on CONVERT(A.kode_jenis,char) = CONVERT(B.kode_jenis,char)
		$where_lokasi 
            $groupby
      ";
	  //print_r($query);exit;
        $res = $this->db->query($query);
        return $res->result_array();
    }

    function get_request_penghapusan_pengecekan()
    {
        $this->db->where('status_proses', '1');
        $result = $this->db->get('tbl_penghapusan');
        return $result->num_rows();
    }

    function get_request_mutasi_penerimaan($kode_lokasi)
    {
        $this->db->where('status_proses', '2');
        $this->db->where('id_kode_lokasi_baru', $kode_lokasi);
        $result = $this->db->get('tbl_mutasi');
        return $result->num_rows();
    }

    function get_request_mutasi_validasi($kode_lokasi)
    {
        $this->db->where('status_proses', '4');
        $this->db->where('id_kode_lokasi_baru', $kode_lokasi);
        $result = $this->db->get('tbl_mutasi');
        return $result->num_rows();
    }

    function get_request_kapitalisasi()
    {
        $session = $this->session->userdata('session');
        $pengguna = explode('.', $session->kode_lokasi)[3];
        $kuasa_pengguna =  explode('.', $session->kode_lokasi)[4];
        $this->db->where('status', '1');
        $this->db->where('status_data', 'kapitalisasi');
        $this->db->where('id_kode_lokasi', $session->id_kode_lokasi);
        $result = $this->db->get('tbl_kapitalisasi');
        return $result->num_rows();
    }

    function get_request_reklas()
    {
        $session = $this->session->userdata('session');
        $pengguna = explode('.', $session->kode_lokasi)[3];
        $kuasa_pengguna =  explode('.', $session->kode_lokasi)[4];
        $this->db->where('status_validasi', '1');
        $this->db->where('id_kode_lokasi', $session->id_kode_lokasi);
        $result = $this->db->get('tbl_reklas_kode');
        return $result->num_rows();
    }

    function get_lokasi()
    {
        $sql = "SELECT id_kode_lokasi FROM tbl_kode_lokasi WHERE kuasa_pengguna <> '*' and id_kode_lokasi IN ('104','229') ";

        $query = $this->db->query($sql);
        return $query->result_array();
    }

    function get_kib()
    {
        $sql = "SELECT id_kib_atb,id_kode_lokasi,id_kode_barang,tanggal_perolehan FROM tbl_kib_atb WHERE (nomor_register = '' OR nomor_register = '000000' ) AND status_barang <> 'terhapus' AND validasi = '2' ";

        $query = $this->db->query($sql);
        return $query->result_array();
    }

    function generate_register($kode_jenis,$tabel,$id_tabel,$id_kib,$id_kode_lokasi,$id_kode_barang,$tanggal_perolehan){
        
        $sql = "CALL proc_generate_register_atb('".$kode_jenis."','".$tabel."','".$id_tabel."',".$id_kib.",".$id_kode_lokasi.",".$id_kode_barang.",'".$tanggal_perolehan."')";

        $query = $this->db->query($sql);
        // print_r($query->result_array()); die;
    }

    function generate_rekap_mutasi($data){
        // die("call proc_stock_opname(" . $data['session']->id_user . "," . $data['id_pemilik'] . "," . $data['id_kode_lokasi'] . ",'" . $data['start_bulan'] . "','" . $data['start_tahun'] . "','" . $data['last_bulan'] . "','" . $data['last_tahun'] . "',  '" . $data['intra_ekstra'] . "')");
        $query    = $this->db->query("call proc_stock_opname(" . $data['session']->id_user . "," . $data['id_pemilik'] . "," . $data['id_kode_lokasi'] . ",'" . $data['start_bulan'] . "','" . $data['start_tahun'] . "','" . $data['last_bulan'] . "','" . $data['last_tahun'] . "',  '" . $data['intra_ekstra'] . "')");
        $res      = $query->result_array();
        // print_r($query->result_array()); die;
        //add this two line
        $query->next_result();
        $query->free_result();
        //end of new code

        // die(json_encode($res));
        // return $res;
    }

	/*select '01' AS `kode_jenis`,`tbl_kib_a`.`kode_lokasi` AS `kode_lokasi`,`tbl_kib_a`.`harga` AS `harga` from `tbl_kib_a`  
union all
select '02' AS `kode_jenis`,`tbl_kib_b`.`kode_lokasi` AS `kode_lokasi`,`tbl_kib_b`.`harga` AS `harga` from `tbl_kib_b` 
union all
select '03' AS `kode_jenis`,`tbl_kib_c`.`kode_lokasi` AS `kode_lokasi`,`tbl_kib_c`.`harga` AS `harga` from `tbl_kib_c` 
union all
select '04' AS `kode_jenis`,`tbl_kib_d`.`kode_lokasi` AS `kode_lokasi`,`tbl_kib_d`.`harga` AS `harga` from `tbl_kib_d` 
union all
select '05' AS `kode_jenis`,`tbl_kib_e`.`kode_lokasi` AS `kode_lokasi`,`tbl_kib_e`.`harga` AS `harga` from `tbl_kib_e` 
union all
select '06' AS `kode_jenis`,`tbl_kib_f`.`kode_lokasi` AS `kode_lokasi`,`tbl_kib_f`.`nilai_kontrak` AS `harga` from `tbl_kib_f` 
union all
select '5.03' AS `kode_jenis`,`tbl_kib_atb`.`kode_lokasi` AS `kode_lokasi`,`tbl_kib_atb`.`harga` AS `harga` from `tbl_kib_atb`  

*/
}
