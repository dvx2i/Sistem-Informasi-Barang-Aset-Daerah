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
		$groupby = 'GROUP BY objek'; 
	}else{
		$where_lokasi = "AND CONVERT(id_kode_lokasi,signed) = '".$id_kode_lokasi."'";
		$groupby = ''; 
	}
    
        $query = "            
            SELECT 'KIB A (Tanah)' AS nama_jenis,sum(harga) harga,SUBSTR(objek,5,2) AS kode_jenis FROM tbl_histori_rekap_mutasi WHERE bulan_data = '12' AND tahun_data = '2022' AND kode_intra_extra = '00' AND LENGTH(objek) = '6' AND SUBSTR(objek,5,2) = '01'
            $where_lokasi 
            $groupby
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
            $groupby = 'GROUP BY objek'; 
        }else{
            $where_lokasi = "AND CONVERT(id_kode_lokasi,signed) = '".$id_kode_lokasi."'";
            $groupby = ''; 
        }
        
            $query = "            
                SELECT 'KIB B (Peralatan dan Mesin)' AS nama_jenis,sum(harga) harga,SUBSTR(objek,5,2) AS kode_jenis FROM tbl_histori_rekap_mutasi WHERE bulan_data = '12' AND tahun_data = '2022' AND kode_intra_extra = '00' AND LENGTH(objek) = '6' AND SUBSTR(objek,5,2) = '02'
                $where_lokasi 
                $groupby
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
            $groupby = 'GROUP BY objek'; 
        }else{
            $where_lokasi = "AND CONVERT(id_kode_lokasi,signed) = '".$id_kode_lokasi."'";
            $groupby = ''; 
        }
        
            $query = "            
                SELECT 'KIB C (Gedung dan Bangunan)' AS nama_jenis,sum(harga) harga,SUBSTR(objek,5,2) AS kode_jenis FROM tbl_histori_rekap_mutasi WHERE bulan_data = '12' AND tahun_data = '2022' AND kode_intra_extra = '00' AND LENGTH(objek) = '6' AND SUBSTR(objek,5,2) = '03'
                $where_lokasi 
                $groupby
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
            $groupby = 'GROUP BY objek'; 
        }else{
            $where_lokasi = "AND CONVERT(id_kode_lokasi,signed) = '".$id_kode_lokasi."'";
            $groupby = ''; 
        }
        
            $query = "            
                SELECT 'KIB D (Jalan, Irigasi dan Jaringan)' AS nama_jenis,sum(harga) harga,SUBSTR(objek,5,2) AS kode_jenis FROM tbl_histori_rekap_mutasi WHERE bulan_data = '12' AND tahun_data = '2022' AND kode_intra_extra = '00' AND LENGTH(objek) = '6' AND SUBSTR(objek,5,2) = '04'
                $where_lokasi 
                $groupby
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
            $groupby = 'GROUP BY objek'; 
        }else{
            $where_lokasi = "AND CONVERT(id_kode_lokasi,signed) = '".$id_kode_lokasi."'";
            $groupby = ''; 
        }
        
            $query = "            
                SELECT 'KIB E (Aset Tetap Lainya)' AS nama_jenis,sum(harga) harga,SUBSTR(objek,5,2) AS kode_jenis FROM tbl_histori_rekap_mutasi WHERE bulan_data = '12' AND tahun_data = '2022' AND kode_intra_extra = '00' AND LENGTH(objek) = '6' AND SUBSTR(objek,5,2) = '05'
                $where_lokasi 
                $groupby
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
        $sql = "
        SELECT * FROM tbl_kode_lokasi a
        WHERE kuasa_pengguna = '*' AND pengguna_opd <> '*' AND id_kode_lokasi NOT IN ('348','350','352'); ";
//id_kode_lokasi NOT IN ('349','351','353')
        $query = $this->db->query($sql);
        return $query->result_array();
    }
    

    function get_kib_validasi()
    {
        $sql = "SELECT id_kib_e FROM tbl_kib_e WHERE id_kode_lokasi = '76' AND validasi = '1' AND nomor_transaksi = 'TindakLanjutLHPBPK2021';";

        $query = $this->db->query($sql);
        return $query->result();
    }
    
    function get_inject_data()
    {
        $sql = "SELECT * from temp_inject where lokasi = 'suryo'";

        $query = $this->db->query($sql);
        return $query->result_array();
    }
    
    function get_inject_data_e()
    {
        $sql = "SELECT a.* FROM tbl_kib_e_inject_jetis a";

        $query = $this->db->query($sql);
        return $query->result();
    }
    
    // insert data
    function inject_e($data)
    {
        $this->db->insert('tbl_kib_e_copy2', $data);
        return $this->db->insert_id();
    }

    // update data
    function update_inject_e($id, $data)
    {
        $this->db->where('id_kib_e', $id);
        $this->db->update('tbl_kib_e_copy2', $data);
    }
    
    function update_inject_data($id,$input_banyak)
    {
        $sql = "update temp_inject set input_banyak = '".$input_banyak."' where no = '".$id."'";

        $query = $this->db->query($sql);
    }
    
    function update_inject_decimal($id,$input_banyak)
    {
        $sql = "update temp_inject set decimal = '".$input_banyak."' where no = '".$id."'";

        $query = $this->db->query($sql);
    }

    function get_fix_histori()
    {
        $sql = "SELECT a.* FROM tbl_kib_e a
        LEFT JOIN tbl_histori_barang b ON a.`id_kib_e` = b.`id_kib` AND '05' = b.`kode_jenis` AND b.`status_histori` = 'entri'
        WHERE a.`tanggal_transaksi` > DATE('2022-08-31') AND a.`validasi` ='2' AND b.`id_kib` IS NULL AND a.`status_barang` = 'kib'";
        
        $query = $this->db->query($sql);
        return $query->result_array();
    }
    
    function generate_histori($id_kode_lokasi,$kode_jenis,$id_kib,$tgl_validasi,$ket,$id_sumber_dana,$id_rekening){
        
        $sql = "CALL proc_histori_barang('entri', ".$id_kode_lokasi.", '".$kode_jenis."', ".$id_kib.", '".$tgl_validasi."','".$ket."',".$id_sumber_dana.", ".$id_rekening.", ".$id_kib.")";
        // die($sql);
        $query = $this->db->query($sql);
        // print_r($query->result_array()); die;
    }

    function get_lokasi_penyusutan()
    {
        $sql = "SELECT a.`id_kode_lokasi` FROM tbl_kib_atb a
        JOIN tbl_kode_lokasi b ON a.`id_kode_lokasi` = b.`id_kode_lokasi`
            WHERE umur_ekonomis <> '0' AND id_kode_barang IN ('14741','14850')
            GROUP BY a.`id_kode_lokasi`; ";

        $query = $this->db->query($sql);
        return $query->result_array();
    }

    function get_kib()
    {
        $sql = "SELECT id_kib_e,id_kode_lokasi,id_kode_barang,tanggal_perolehan FROM tbl_kib_e WHERE created_at = '2022-12-03 00:00:00' ";

        $query = $this->db->query($sql);
        return $query->result_array();
    }

    function generate_register($kode_jenis,$tabel,$id_tabel,$id_kib,$id_kode_lokasi,$id_kode_barang,$tanggal_perolehan){
        
        $sql = "CALL proc_generate_register('".$kode_jenis."','".$tabel."','".$id_tabel."',".$id_kib.",".$id_kode_lokasi.",".$id_kode_barang.",'".$tanggal_perolehan."')";

        $query = $this->db->query($sql);
        // print_r($query->result_array()); die;
    }

    function generate_rekap_mutasi($data){
        // die("call proc_stock_opname(" . $data['session']->id_user . "," . $data['id_pemilik'] . "," . $data['id_kode_lokasi'] . ",'" . $data['start_bulan'] . "','" . $data['start_tahun'] . "','" . $data['last_bulan'] . "','" . $data['last_tahun'] . "',  '" . $data['intra_ekstra'] . "')");
        $query    = $this->db->query("call proc_stock_opname(1," . $data['id_pemilik'] . "," . $data['id_kode_lokasi'] . ",'" . $data['start_bulan'] . "','" . $data['start_tahun'] . "','" . $data['last_bulan'] . "','" . $data['last_tahun'] . "',  '" . $data['intra_ekstra'] . "')");
        $res      = $query->result_array();
        // print_r($query->result_array()); die;
        //add this two line
        $query->next_result();
        $query->free_result();
        //end of new code

        // die(json_encode($res));
        // return $res;
    }

    function generate_rekap_penyusutan($id){
        // die("CALL proc_rekap_penyusutan_objek_inject(1,2,".$id.",'','2021-12-31', '01');");
        $query    = $this->db->query("
        CALL proc_rekap_penyusutan_objek_inject(1,2,".$id.",'','2021-12-31', '01');");
        // $res      = $query->result_array();
        // print_r($query->result_array()); die;
        //add this two line
        $query->next_result();
        $query->free_result();
        //end of new code

        // die(json_encode($res));
        // return $res;
    }
    
    

    function generate_penyusutan($data){
        // die("CALL proc_penyusutan('1'," . $data['id_kode_lokasi'] . ",240);");
        $query    = $this->db->query("CALL proc_penyusutan('1','2022-12-31'," . $data['id_kode_lokasi'] . ");");
        $res      = $query->result_array();
        // print_r($query->result_array()); die;
        //add this two line
        $query->next_result();
        $query->free_result();
        //end of new code

        // die(json_encode($res));
        // return $res;
    }

    function get_barang_penghapusan(){
        
        $sql = "SELECT a.*,c.* FROM tbl_penghapusan_barang a
        JOIN tbl_penghapusan_lampiran_detail b ON a.`id_penghapusan_barang` = b.`id_penghapusan_barang`
        JOIN tbl_kib_e c ON a.`id_kib` = c.`id_kib_e`
        WHERE b.`id_penghapusan_lampiran` = '134' AND a.`status_diterima` = '2' AND a.`status_barang` = '1' ";

        $query = $this->db->query($sql);
        return $query->result();
    }

    function generate_penghapusan($data){
        // die("CALL proc_histori_barang('penghapusan', ".$data['id_kode_lokasi'].", '".$data['kode_jenis']."', ".$data['id_kib'].",'".$data['tanggal_transaksi']."', '".$data['jenis_sk']."',0,0, ".$data['id_penghapusan_barang'].")");
        $query    = $this->db->query("CALL proc_histori_barang('penghapusan', ".$data['id_kode_lokasi'].", '".$data['kode_jenis']."', ".$data['id_kib'].",'".$data['tanggal_transaksi']."', '".$data['jenis_sk']."',0,0, ".$data['id_penghapusan_barang'].")");
        // $res      = $query->result_array();
        // print_r($query->result_array()); die;
        //add this two line
        // $query->next_result();
        // $query->free_result();
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
