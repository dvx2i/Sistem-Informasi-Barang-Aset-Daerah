<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Tbl_kib_atb_model extends CI_Model
{

    public $table = 'tbl_kib_atb';
    public $id = 'id_kib_atb';
    public $order = 'DESC';
    public $tbl_barang = 'tbl_kode_barang';
    public $tbl_kode_lokasi = 'tbl_kode_lokasi';

    function __construct()
    {
        parent::__construct();
    }

    // datatables
    function json()
    {
        /*
        $this->datatables->select('id_kib_atb,id_pemilik,id_kode_barang,id_kode_lokasi,nama_barang,nomor_register,judul_kajian_nama_software,tahun_perolehan,asal_usul,keterangan,deskripsi,kode_lokasi,validasi,created_at,updated_at');
        $this->datatables->from('tbl_kib_atb');
        //add this line for join
        //$this->datatables->join('table2', 'tbl_kib_atb.field = table2.field');
        $this->datatables->add_column('action', anchor(site_url('tbl_kib_atb/read/$1'), 'Read') . " | " . anchor(site_url('tbl_kib_atb/update/$1'), 'Update') . " | " . anchor(site_url('tbl_kib_atb/delete/$1'), 'Delete', 'onclick="javasciprt: return confirm(\'Are You Sure ?\')"'), 'id_kib_atb');
        return $this->datatables->generate();
        */
        $this->load->helper('my_datatable');
        // $this->datatables->select('( A.id_kib_atb,A.nama_barang,A.kode_barang,A.nomor_register,A.luas,A.tahun_pengadaan,A.letak_alamat,A.status_hak,A.sertifikat_tanggal,A.sertifikat_nomor,A.penggunaan,A.asal_usul,A.harga,A.keterangan,A.kode_lokasi,A.created_at,A.updated_at, B.nama_barang as nama_barang2,A.validasi');
        $this->datatables->select(' A.id_kib_atb,A.id_pemilik,A.id_kode_barang,A.id_kode_lokasi, 
            A.kode_barang,A.nama_barang,A.nama_barang_migrasi, A.nama_barang as nama_barang_desc,A.nomor_register,A.judul_kajian_nama_software,
            A.tanggal_perolehan,A.asal_usul, A.harga,A.keterangan,A.deskripsi,A.kode_lokasi,
            A.validasi,A.created_at,A.updated_at, B.nama_barang as nama_barang2, C.instansi, reject_note,COALESCE(A.id_inventaris,A.id_kib_atb) as id_inventaris,A.validasi as status_validasi  ');
        $this->datatables->from('tbl_kib_atb A');
        $this->datatables->join($this->tbl_barang . ' B', 'A.id_kode_barang = B.id_kode_barang');
        $this->datatables->join($this->tbl_kode_lokasi . ' C', 'A.id_kode_lokasi = C.id_kode_lokasi');
        $this->datatables->where('A.status_barang', 'kib');
        // die("ok");
        $session = $this->session->userdata('session');
        $lokasi = explode('.', $session->kode_lokasi);
        $pengguna = $lokasi[3];
        $kuasa_pengguna =  $lokasi[4];
        /*
        1: superadmin; 2:adminskpd; 5:kepalaskpd
         */
        /*if ($session->id_role != '1' and $kuasa_pengguna != '*') //bukan super admin, bukan skpd
            $this->datatables->where('A.id_kode_lokasi', $session->id_kode_lokasi);
        else if ($kuasa_pengguna == '*') //jika bukan skpd(hak skses)
            $this->datatables->where('C.pengguna', $pengguna);
            */
        /* update 11 sep 20     karena super admin bisa memilih lokasi */
        if (cek_hak_akses('superadmin', $session->id_role)) {

            if ($this->input->post('id_pemilik') != '') {
                $this->datatables->where('id_pemilik', $this->input->post('id_pemilik')); // where id pemilik
            }

            if ($this->input->post('id_pengguna') != '') {
                $this->datatables->where('C.pengguna', $this->input->post('id_pengguna')); // where id pengguna
            }

            if ($this->input->post('id_kuasa_pengguna') != '') {
                $this->datatables->where('A.id_kode_lokasi', $this->input->post('id_kuasa_pengguna')); // where id kuasa pengguna
            }
        } else
  
    if (cek_hak_akses('skpd', $session->id_role)) {
            $this->datatables->where('C.pengguna', $pengguna); //jika skpd
        } else { // jika bukan superadmin dan bukan skpd
            //   $this->datatables->where('A.id_kode_lokasi', $session->id_kode_lokasi);

            if ($this->input->post('id_pemilik') != '') {
                $this->datatables->where('id_pemilik', $this->input->post('id_pemilik')); // where id pemilik
            }

            if ($this->input->post('id_pengguna') != '') {
                $this->datatables->where('C.pengguna', $this->input->post('id_pengguna')); // where id pengguna
            }

            if ($this->input->post('id_kuasa_pengguna') != '') {
                $this->datatables->where('A.id_kode_lokasi', $this->input->post('id_kuasa_pengguna')); // where id kuasa pengguna
            }
        }


        $this->datatables->edit_column('validasi', '$1', 'status_validasi_kib(validasi,reject_note)');
        // $this->datatables->edit_column('luas', '$1', 'format_number(luas)');
        $this->datatables->edit_column('tanggal_perolehan', '$1', 'date_time(tanggal_perolehan)');
        $this->datatables->edit_column('harga', '$1', 'format_float(harga)');
        $this->datatables->add_column('action', '$1', 'get_action(' . $session->id_role . ',kib_atb,' . $this->id . ', validasi)');
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
        $this->db->like('id_kib_atb', $q);
        $this->db->or_like('id_pemilik', $q);
        $this->db->or_like('id_kode_barang', $q);
        $this->db->or_like('id_kode_lokasi', $q);
        $this->db->or_like('nama_barang', $q);
        $this->db->or_like('nomor_register', $q);
        $this->db->or_like('judul_kajian_nama_software', $q);
        // $this->db->or_like('tahun_perolehan', $q);
        $this->db->or_like('asal_usul', $q);
        $this->db->or_like('keterangan', $q);
        $this->db->or_like('deskripsi', $q);
        $this->db->or_like('kode_lokasi', $q);
        $this->db->or_like('validasi', $q);
        $this->db->or_like('created_at', $q);
        $this->db->or_like('updated_at', $q);
        $this->db->from($this->table);
        return $this->db->count_all_results();
    }

    // get data with limit and search
    function get_limit_data($limit, $start = 0, $q = NULL)
    {
        $this->db->order_by($this->id, $this->order);
        $this->db->like('id_kib_atb', $q);
        $this->db->or_like('id_pemilik', $q);
        $this->db->or_like('id_kode_barang', $q);
        $this->db->or_like('id_kode_lokasi', $q);
        $this->db->or_like('nama_barang', $q);
        $this->db->or_like('nomor_register', $q);
        $this->db->or_like('judul_kajian_nama_software', $q);
        // $this->db->or_like('tahun_perolehan', $q);
        $this->db->or_like('asal_usul', $q);
        $this->db->or_like('keterangan', $q);
        $this->db->or_like('deskripsi', $q);
        $this->db->or_like('kode_lokasi', $q);
        $this->db->or_like('validasi', $q);
        $this->db->or_like('created_at', $q);
        $this->db->or_like('updated_at', $q);
        $this->db->limit($limit, $start);
        return $this->db->get($this->table)->result();
    }

    // insert data
    function insert($data)
    {
        $this->db->insert($this->table, $data);
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

    // delete histori barang
    function delete_histori_barang($id)
    {
      $this->db->where('id_kib', $id);
      $this->db->where('kode_jenis', '5.03'); // kib atb
      $this->db->delete('tbl_histori_barang_atb');
    }
  
    // delete histori kib
    function delete_histori_kib($id)
    {
      $this->db->where('id_kib_f', $id);
      $this->db->delete('tbl_kib_f_histori');
    }
}

/* End of file Tbl_kib_atb_model.php */
/* Location: ./application/models/Tbl_kib_atb_model.php */
/* Please DO NOT modify this information : */
/* Generated by Harviacode Codeigniter CRUD Generator 2020-08-02 19:12:12 */
/* http://harviacode.com */