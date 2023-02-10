<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Migrasi_model extends CI_Model
{

    function __construct()
    {
        parent::__construct();
    }

    public function get_kode_jenis($kode_jenis = null)
    {
        if (!empty($kode_jenis)) {
            $this->db->where("kode_jenis", $kode_jenis);
        }
        $this->db->where(array("kode_kelompok" => "3", "kode_jenis <> " => "07"));
        $this->db->get('view_kode_barang_jenis');
    }

    // Buat sebuah fungsi untuk melakukan insert lebih dari 1 data
    public function insert_multiple($tabel, $kode_jenis, $data, $id_user)
    {
        $this->db_1 = $this->load->database('default', true);
        // $this->db_2 = $this->load->database('default', true);

        // $this->db_1->insert_batch($tabel, $data);
        $this->db->delete($tabel, array('id_user' => $id_user,));
        $this->db->trans_start();
        foreach ($data as $item) {
            $insert_query = $this->db->insert_string($tabel, $item);
            $insert_query = str_replace('INSERT INTO', 'INSERT IGNORE INTO', $insert_query);
            $this->db->query($insert_query);
        }
        $this->db->trans_complete();


        // die("cek");



        /*echo '<script type="text/javascript">
        alert("ID JSS Anda belum terdaftar di aplikasi ini.");
        window.location = "' . base_url("migrasi") . '"
        </script>';*/


        // $this->db_2->query(" CALL proc_migrasi('$kode_jenis'); ");
        /*echo '<script type="text/javascript">
        window.close();
        </script>';*/
    }

    public function proses_migrasi($kode_jenis, $id_user)
    {
        $this->db_2 = $this->load->database('default', true);
        // $this->db_2->query(" CALL proc_migrasi('$kode_jenis'); ");
        $res = null;
        if ($kode_jenis == "01") $res = $this->db_2->query(" CALL proc_migrasi_kib_a($id_user); ");
        else if ($kode_jenis == "02") $res = $this->db_2->query(" CALL proc_migrasi_kib_b($id_user); ");
        else if ($kode_jenis == "03") $res = $this->db_2->query(" CALL proc_migrasi_kib_c($id_user); ");
        else if ($kode_jenis == "04") $res = $this->db_2->query(" CALL proc_migrasi_kib_d($id_user); ");
        else if ($kode_jenis == "05") $res = $this->db_2->query(" CALL proc_migrasi_kib_e($id_user); ");
        else if ($kode_jenis == "06") $res = $this->db_2->query(" CALL proc_migrasi_kib_f($id_user); ");

        return $res->row_array();
    }
}
