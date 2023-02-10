<?php

if (!defined('BASEPATH')) exit('No direct script access allowed');

class Global_reklas_model extends CI_Model
{
    public $order = 'DESC';
    public $tbl_kode_barang = 'tbl_kode_barang';


    public $view_kib = 'view_kib';
    public $tbl_reklas_kode = 'tbl_reklas_kode';
    public $id_reklas_kode = 'id_reklas_kode';
    // public $tbl_mutasi_barang = 'tbl_mutasi_barang';
    // public $id_mutasi_barang = 'id_mutasi_barang';
    // public $tbl_mutasi_picture = 'tbl_mutasi_picture';

    public $tbl_kode_lokasi = 'tbl_kode_lokasi';
    public $kib = null;

    function __construct()
    {
        parent::__construct();
        $this->kib = $this->global_model->kode_jenis;
    }

    // datatables
    function json_kib_a($pihak = null, $data = null)
    {
        $this->load->helper('my_datatable');
        $id_jenis = '01';
        $kib = $this->kib[$id_jenis];
        // $this->datatables->select('id_kib_a,A.nama_barang,A.kode_barang,nomor_register,
        // luas,tahun_pengadaan,letak_alamat,status_hak,sertifikat_tanggal,sertifikat_nomor,
        // penggunaan,asal_usul,harga,keterangan,kode_lokasi, B.nama_barang as nama_barang2,
        // validasi,C.id_mutasi_barang,status_diterima,status_validasi, id_kode_lokasi_lama, id_kode_lokasi_baru');
        $this->datatables->select('id_kib_a,A.nama_barang,A.nama_barang_migrasi,
        (case when A.nama_barang_migrasi is not null then A.nama_barang_migrasi else A.nama_barang end) as nama_barang_desc,
        A.kode_barang,nomor_register,
        luas,tahun_pengadaan,letak_alamat,status_hak,sertifikat_tanggal,sertifikat_nomor,
        penggunaan,asal_usul,harga,keterangan,kode_lokasi, B.nama_barang as nama_barang2,id_inventaris');
        $this->datatables->from($kib['table'] . ' A');
        $this->datatables->join($this->tbl_kode_barang . ' B', 'A.id_kode_barang = B.id_kode_barang');
        // $this->datatables->join($this->tbl_mutasi_barang . ' C', 'C.kode_jenis="' . $id_jenis . '" and A.' . $kib['id_name'] . ' = C.id_kib', 'left');
        // $this->datatables->join($this->tbl_mutasi . ' D', 'D.id_mutasi = C.id_mutasi', 'left');
        $this->datatables->where('A.validasi', '2');
        $this->datatables->where(" ((id_kode_barang_aset_lainya is NULL OR id_kode_barang_aset_lainya = '0') and A.status_barang <> 'aset_lainya') ", NULL, FALSE);
        $this->datatables->where(" (id_kib_a NOT IN (SELECT id_kib FROM tbl_reklas_kode WHERE kode_jenis = '01' AND status_validasi = '1')) ", NULL, FALSE);
        


        if ($pihak == 'list_barang') { //pengajuan
            $this->datatables->where('A.id_kode_lokasi', $this->session->userdata('session')->id_kode_lokasi);
            // $this->datatables->where('ifnull(C.id_mutasi,0)','0',false);
            $this->datatables->where('A.status_barang', 'kib');
            if ($data) {
                foreach (json_decode($data) as $key => $value) {
                    $this->datatables->where('A.id_kib_a <> ', $value);
                }
            }
        }
        /*else if ($pihak == 'pengecekan_barang') {
            // $this->datatables->where('status_pengajuan','2',false);
            $this->datatables->where('C.id_mutasi', $this->input->post('id_mutasi'), false);
            $this->datatables->where('id_kode_lokasi_baru', $this->session->userdata('session')->id_kode_lokasi, false);
            $this->datatables->add_column('radio_terima', '$1', 'radio_pengecekan_barang(id_mutasi_barang,status_diterima,2)');
            $this->datatables->add_column('radio_tolak', '$1', 'radio_pengecekan_barang(id_mutasi_barang,status_diterima,3)');
        }*/

        $this->datatables->edit_column('luas', '$1', 'format_number(luas)');
        $this->datatables->edit_column('sertifikat_tanggal', '$1', 'date_time(sertifikat_tanggal)');
        $this->datatables->edit_column('harga', '$1', 'format_number(harga)');
        // $this->datatables->edit_column('status_validasi', '$1', 'status_validasi(id_mutasi_barang,status_validasi)');
        $this->datatables->edit_column('aksi', "<div class='radio'>
                            <label><input type='radio' name='id_kib' style='width:23px; height:23px' value='$1'></label> 
                            </div>", 'id_kib_a');

        return $this->datatables->generate();
        // $this->datatables->generate(); die($this->db->last_query());

    }

    // datatables
    function json_kib_b($pihak = null, $data = null)
    {
        $this->load->helper('my_datatable');
        $id_jenis = '02';
        $kib = $this->kib[$id_jenis];

        // $this->datatables->select('id_kib_b,A.kode_barang,A.nama_barang,nomor_register,merk_type,
        // ukuran_cc,bahan,tahun_pembelian,nomor_pabrik,nomor_rangka,nomor_mesin,nomor_polisi,
        // nomor_bpkb,asal_usul,harga,keterangan,kode_lokasi, B.nama_barang as nama_barang2,
        // validasi,C.id_mutasi_barang,status_diterima,status_validasi, id_kode_lokasi_lama, id_kode_lokasi_baru');
        $this->datatables->select('id_kib_b,A.kode_barang,A.nama_barang,A.nama_barang_migrasi,
        (case when A.nama_barang_migrasi is not null then A.nama_barang_migrasi else A.nama_barang end) as nama_barang_desc,
        nomor_register,merk_type,
        ukuran_cc,bahan,tahun_pembelian,nomor_pabrik,nomor_rangka,nomor_mesin,nomor_polisi,
        nomor_bpkb,asal_usul,harga,keterangan,kode_lokasi, B.nama_barang as nama_barang2,
        validasi,id_inventaris');
        $this->datatables->from($kib['table'] . ' A');
        $this->datatables->join($this->tbl_kode_barang . ' B', 'A.id_kode_barang = B.id_kode_barang');
        // $this->datatables->join($this->tbl_mutasi_barang . ' C', 'C.kode_jenis="' . $id_jenis . '" and A.' . $kib['id_name'] . ' = C.id_kib', 'left');
        // $this->datatables->join($this->tbl_mutasi . ' D', 'D.id_mutasi = C.id_mutasi', 'left');
        $this->datatables->where('A.validasi', '2');
        $this->datatables->where(" ((id_kode_barang_aset_lainya is NULL OR id_kode_barang_aset_lainya = '0') and A.status_barang <> 'aset_lainya') ", NULL, FALSE);

        $this->datatables->where(" (id_kib_b NOT IN (SELECT id_kib FROM tbl_reklas_kode WHERE kode_jenis = '02' AND status_validasi = '1')) ", NULL, FALSE);
        if ($pihak == 'list_barang') { //pengajuan
            $this->datatables->where('A.id_kode_lokasi', $this->session->userdata('session')->id_kode_lokasi);
            // $this->datatables->where('ifnull(C.id_mutasi,0)','0',false);
            $this->datatables->where('A.status_barang', 'kib');
            if ($data) {
                foreach (json_decode($data) as $key => $value) {
                    $this->datatables->where('A.id_kib_b <> ', $value);
                }
            }
        }
        /*else if ($pihak == 'pengecekan_barang') {
            $this->datatables->where('C.id_mutasi', $this->input->post('id_mutasi'), false);
            $this->datatables->where('id_kode_lokasi_baru', $this->session->userdata('session')->id_kode_lokasi, false);
            $this->datatables->add_column('radio_terima', '$1', 'radio_pengecekan_barang(id_mutasi_barang,status_diterima,2)');
            $this->datatables->add_column('radio_tolak', '$1', 'radio_pengecekan_barang(id_mutasi_barang,status_diterima,3)');
        }*/
        // $this->datatables->edit_column('ukuran_cc', '$1', 'format_number(ukuran_cc)');
        $this->datatables->edit_column('harga', '$1', 'format_float(harga)');
        // $this->datatables->edit_column('status_validasi', '$1', 'status_validasi(id_mutasi_barang,status_validasi)');
        $this->datatables->edit_column('aksi', "<div class='radio'>
                            <label><input type='radio' name='id_kib' style='width:23px; height:23px' value='$1'></label> 
                            </div>", 'id_kib_b');
        return $this->datatables->generate();
        //$this->datatables->generate(); die($this->db->last_query());
    }

    // datatables
    function json_kib_c($pihak = null, $data = null)
    {
        $this->load->helper('my_datatable');
        $id_jenis = '03';
        $kib = $this->kib[$id_jenis];
        // $this->datatables->select('id_kib_c,A.nama_barang,A.kode_barang,nomor_register,
        // kondisi_bangunan,bangunan_bertingkat,bangunan_beton,luas_lantai_m2,lokasi_alamat,
        // gedung_tanggal,gedung_nomor,luas_m2,status,nomor_kode_tanah,asal_usul,harga,keterangan,
        // B.nama_barang as nama_barang2, validasi,C.id_mutasi_barang,
        // status_diterima,status_validasi, id_kode_lokasi_lama, id_kode_lokasi_baru');
        $this->datatables->select('id_kib_c,A.nama_barang,A.nama_barang_migrasi,
        (case when A.nama_barang_migrasi is not null then A.nama_barang_migrasi else A.nama_barang end) as nama_barang_desc,
        A.kode_barang,nomor_register,
        kondisi_bangunan,bangunan_bertingkat,bangunan_beton,luas_lantai_m2,lokasi_alamat,
        gedung_tanggal,gedung_nomor,luas_m2,status,nomor_kode_tanah,asal_usul,harga,keterangan,
        B.nama_barang as nama_barang2, validasi,id_inventaris');
        $this->datatables->from($kib['table'] . ' A');
        $this->datatables->join($this->tbl_kode_barang . ' B', 'A.id_kode_barang = B.id_kode_barang');
        // $this->datatables->join($this->tbl_mutasi_barang . ' C', 'C.kode_jenis="' . $id_jenis . '" and A.' . $kib['id_name'] . ' = C.id_kib', 'left');
        // $this->datatables->join($this->tbl_mutasi . ' D', 'D.id_mutasi = C.id_mutasi', 'left');
        $this->datatables->where('A.validasi', '2');
        $this->datatables->where(" ((id_kode_barang_aset_lainya is NULL OR id_kode_barang_aset_lainya = '0') and A.status_barang <> 'aset_lainya') ", NULL, FALSE);

        $this->datatables->where(" (id_kib_c NOT IN (SELECT id_kib FROM tbl_reklas_kode WHERE kode_jenis = '03' AND status_validasi = '1')) ", NULL, FALSE);
        if ($pihak == 'list_barang') { //pengajuan
            $this->datatables->where('A.id_kode_lokasi', $this->session->userdata('session')->id_kode_lokasi);
            // $this->datatables->where('ifnull(C.id_mutasi,0)','0',false);
            $this->datatables->where('A.status_barang', 'kib');
            if ($data) {
                foreach (json_decode($data) as $key => $value) {
                    $this->datatables->where('A.id_kib_c <> ', $value);
                }
            }
        } /*else if ($pihak == 'pengecekan_barang') {
            $this->datatables->where('C.id_mutasi', $this->input->post('id_mutasi'), false);
            $this->datatables->where('id_kode_lokasi_baru', $this->session->userdata('session')->id_kode_lokasi, false);
            $this->datatables->add_column('radio_terima', '$1', 'radio_pengecekan_barang(id_mutasi_barang,status_diterima,2)');
            $this->datatables->add_column('radio_tolak', '$1', 'radio_pengecekan_barang(id_mutasi_barang,status_diterima,3)');
        }*/
        // $this->datatables->add_column('action', '$1', 'get_action(kib_c, id_kib_c, validasi)');
        // $this->datatables->edit_column('luas_lantai_m2', '$1', 'format_number(luas_lantai_m2)');
        // $this->datatables->edit_column('luas_m2', '$1', 'format_number(luas_m2)');
        $this->datatables->edit_column('harga', '$1', 'format_float(harga)');
        $this->datatables->edit_column('gedung_tanggal', '$1', 'date_time(gedung_tanggal)');
        // $this->datatables->edit_column('status_validasi', '$1', 'status_validasi(id_mutasi_barang,status_validasi)');
        $this->datatables->edit_column('aksi', "<div class='radio'>
                            <label><input type='radio' name='id_kib' style='width:23px; height:23px' value='$1'></label> 
                            </div>", 'id_kib_c');
        return $this->datatables->generate();
    }

    // datatables
    function json_kib_d($pihak = null, $data = null)
    {
        $this->load->helper('my_datatable');
        $id_jenis = '04';
        $kib = $this->kib[$id_jenis];
        // $this->datatables->select('id_kib_d,nama_barang,kode_barang,nomor_register,konstruksi,
        // panjang_km,lebar_m,luas_m2,letak_lokasi,dokumen_tanggal,dokumen_nomor,status_tanah,
        // kode_tanah,asal_usul,harga,kondisi,keterangan,kode_lokasi,validasi,C.id_mutasi_barang,
        // status_diterima,status_validasi, id_kode_lokasi_lama, id_kode_lokasi_baru');
        $this->datatables->select('id_kib_d,A.nama_barang,A.nama_barang_migrasi,
        (case when A.nama_barang_migrasi is not null then A.nama_barang_migrasi else A.nama_barang end) as nama_barang_desc,
        kode_barang,nomor_register,konstruksi,
        panjang_km,lebar_m,luas_m2,letak_lokasi,dokumen_tanggal,dokumen_nomor,status_tanah,
        kode_tanah,asal_usul,harga,kondisi,keterangan,kode_lokasi,validasi,id_inventaris');
        $this->datatables->from($kib['table'] . ' A');
        // $this->datatables->join($this->tbl_mutasi_barang . ' C', 'C.kode_jenis="' . $id_jenis . '" and A.' . $kib['id_name'] . ' = C.id_kib', 'left');
        // $this->datatables->join($this->tbl_mutasi . ' D', 'D.id_mutasi = C.id_mutasi', 'left');
        $this->datatables->where('A.validasi', '2');
        $this->datatables->where(" ((id_kode_barang_aset_lainya is NULL OR id_kode_barang_aset_lainya = '0') and A.status_barang <> 'aset_lainya') ", NULL, FALSE);


        $this->datatables->where(" (id_kib_d NOT IN (SELECT id_kib FROM tbl_reklas_kode WHERE kode_jenis = '04' AND status_validasi = '1')) ", NULL, FALSE);
        if ($pihak == 'list_barang') { //pengajuan
            $this->datatables->where('A.id_kode_lokasi', $this->session->userdata('session')->id_kode_lokasi);
            // $this->datatables->where('ifnull(C.id_mutasi,0)','0',false);
            $this->datatables->where('A.status_barang', 'kib');
            if ($data) {
                foreach (json_decode($data) as $key => $value) {
                    $this->datatables->where('A.id_kib_d <> ', $value);
                }
            }
        }
        /*else if ($pihak == 'pengecekan_barang') {
            $this->datatables->where('C.id_mutasi', $this->input->post('id_mutasi'), false);
            $this->datatables->where('id_kode_lokasi_baru', $this->session->userdata('session')->id_kode_lokasi, false);
            $this->datatables->add_column('radio_terima', '$1', 'radio_pengecekan_barang(id_mutasi_barang,status_diterima,2)');
            $this->datatables->add_column('radio_tolak', '$1', 'radio_pengecekan_barang(id_mutasi_barang,status_diterima,3)');
        }*/
        $this->datatables->add_column('action', '$1', 'get_action(kib_d, id_kib_d, validasi)');
        // $this->datatables->edit_column('panjang_km', '$1', 'format_number(panjang_km)');
        // $this->datatables->edit_column('lebar_m', '$1', 'format_number(lebar_m)');
        // $this->datatables->edit_column('luas_m2', '$1', 'format_number(luas_m2)');
        $this->datatables->edit_column('dokumen_tanggal', '$1', 'date_time(dokumen_tanggal)');
        $this->datatables->edit_column('harga', '$1', 'format_float(harga)');
        // $this->datatables->edit_column('status_validasi', '$1', 'status_validasi(id_mutasi_barang,status_validasi)');
        $this->datatables->edit_column('aksi', "<div class='radio'>
                            <label><input type='radio' name='id_kib' style='width:23px; height:23px' value='$1'></label> 
                            </div>", 'id_kib_d');
        return $this->datatables->generate();
    }

    // datatables
    function json_kib_e($pihak = null, $data = null)
    {
        $this->load->helper('my_datatable');
        $id_jenis = '05';
        $kib = $this->kib[$id_jenis];
        // $this->datatables->select('id_kib_e,A.nama_barang,A.kode_barang,nomor_register,
        // judul_pencipta,spesifikasi,kesenian_asal_daerah,kesenian_pencipta,kesenian_bahan,
        // hewan_tumbuhan_jenis,hewan_tumbuhan_ukuran,jumlah,tahun_pembelian,asal_usul,harga,
        // keterangan, kode_lokasi,validasi,C.id_mutasi_barang,status_diterima,status_validasi, id_kode_lokasi_lama, id_kode_lokasi_baru');
        $this->datatables->select('id_kib_e,A.nama_barang,A.nama_barang_migrasi,
        (case when A.nama_barang_migrasi is not null then A.nama_barang_migrasi else A.nama_barang end) as nama_barang_desc,
        A.kode_barang,nomor_register,
        judul_pencipta,spesifikasi,kesenian_asal_daerah,kesenian_pencipta,kesenian_bahan,
        hewan_tumbuhan_jenis,hewan_tumbuhan_ukuran,jumlah,tahun_pembelian,asal_usul,harga,
        keterangan, kode_lokasi,validasi,id_inventaris');
        $this->datatables->from($kib['table'] . ' A');
        // $this->datatables->join($this->tbl_mutasi_barang . ' C', 'C.kode_jenis="' . $id_jenis . '" and A.' . $kib['id_name'] . ' = C.id_kib', 'left');
        // $this->datatables->join($this->tbl_mutasi . ' D', 'D.id_mutasi = C.id_mutasi', 'left');
        $this->datatables->where('A.validasi', '2');
        $this->datatables->where(" ((id_kode_barang_aset_lainya is NULL OR id_kode_barang_aset_lainya = '0') and A.status_barang <> 'aset_lainya') ", NULL, FALSE);

        $this->datatables->where(" (id_kib_e NOT IN (SELECT id_kib FROM tbl_reklas_kode WHERE kode_jenis = '05' AND status_validasi = '1')) ", NULL, FALSE);
        
        if ($pihak == 'list_barang') { //pengajuan
            $this->datatables->where('A.id_kode_lokasi', $this->session->userdata('session')->id_kode_lokasi);
            // $this->datatables->where('ifnull(C.id_mutasi,0)','0',false);
            $this->datatables->where('A.status_barang', 'kib');
            if ($data) {
                foreach (json_decode($data) as $key => $value) {
                    $this->datatables->where('A.id_kib_e <> ', $value);
                }
            }
        }
        /*else if ($pihak == 'pengecekan_barang') {
            $this->datatables->where('C.id_mutasi', $this->input->post('id_mutasi'), false);
            $this->datatables->where('id_kode_lokasi_baru', $this->session->userdata('session')->id_kode_lokasi, false);
            $this->datatables->add_column('radio_terima', '$1', 'radio_pengecekan_barang(id_mutasi_barang,status_diterima,2)');
            $this->datatables->add_column('radio_tolak', '$1', 'radio_pengecekan_barang(id_mutasi_barang,status_diterima,3)');
        }*/
        $this->datatables->add_column('action', '$1', 'get_action(kib_e, id_kib_e, validasi)');
        $this->datatables->edit_column('hewan_tumbuhan_ukuran', '$1', 'format_number(hewan_tumbuhan_ukuran)');
        $this->datatables->edit_column('jumlah', '$1', 'format_number(jumlah)');
        $this->datatables->edit_column('harga', '$1', 'format_number(harga)');
        // $this->datatables->edit_column('status_validasi', '$1', 'status_validasi(id_mutasi_barang,status_validasi)');
        $this->datatables->edit_column('aksi', "<div class='radio'>
                            <label><input type='radio' name='id_kib' style='width:23px; height:23px' value='$1'></label> 
                            </div>", 'id_kib_e');
        return $this->datatables->generate();
    }

    
    // datatables
    function json_kib_f($pihak = null, $data = null)
    {
        $this->load->helper('my_datatable');
        $id_jenis = '06';
        $kib = $this->kib[$id_jenis];
        // $this->datatables->select('id_kib_e,A.nama_barang,A.kode_barang,nomor_register,
        // judul_pencipta,spesifikasi,kesenian_asal_daerah,kesenian_pencipta,kesenian_bahan,
        // hewan_tumbuhan_jenis,hewan_tumbuhan_ukuran,jumlah,tahun_pembelian,asal_usul,harga,
        // keterangan, kode_lokasi,validasi,C.id_mutasi_barang,status_diterima,status_validasi, id_kode_lokasi_lama, id_kode_lokasi_baru');
        
        $this->datatables->select('id_kib_f,A.kode_barang,A.nama_barang, A.nama_barang_migrasi,
        A.nama_barang as nama_barang_desc,bangunan,kontruksi_bertingkat,
        kontruksi_beton,luas_m2,lokasi_alamat,dokumen_tanggal,dokumen_nomor,tanggal_mulai,
        status_tanah,nomor_kode_tanah,asal_usul,nilai_kontrak,keterangan,kode_lokasi,validasi,id_inventaris');
        $this->datatables->from($kib['table'] . ' A');
        // $this->datatables->join($this->tbl_mutasi_barang . ' C', 'C.kode_jenis="' . $id_jenis . '" and A.' . $kib['id_name'] . ' = C.id_kib', 'left');
        // $this->datatables->join($this->tbl_mutasi . ' D', 'D.id_mutasi = C.id_mutasi', 'left');
        $this->datatables->where('A.validasi', '2');
        $this->datatables->where(" ((id_kode_barang_aset_lainya is NULL OR id_kode_barang_aset_lainya = '0') and A.status_barang <> 'aset_lainya') ", NULL, FALSE);

        $this->datatables->where(" (id_kib_f NOT IN (SELECT id_kib FROM tbl_reklas_kode WHERE kode_jenis = '06' AND status_validasi = '1')) ", NULL, FALSE);
        
        if ($pihak == 'list_barang') { //pengajuan
            $this->datatables->where('A.id_kode_lokasi', $this->session->userdata('session')->id_kode_lokasi);
            // $this->datatables->where('ifnull(C.id_mutasi,0)','0',false);
            $this->datatables->where('A.status_barang', 'kib');
            if ($data) {
                foreach (json_decode($data) as $key => $value) {
                    $this->datatables->where('A.id_kib_f <> ', $value);
                }
            }
        }
        /*else if ($pihak == 'pengecekan_barang') {
            $this->datatables->where('C.id_mutasi', $this->input->post('id_mutasi'), false);
            $this->datatables->where('id_kode_lokasi_baru', $this->session->userdata('session')->id_kode_lokasi, false);
            $this->datatables->add_column('radio_terima', '$1', 'radio_pengecekan_barang(id_mutasi_barang,status_diterima,2)');
            $this->datatables->add_column('radio_tolak', '$1', 'radio_pengecekan_barang(id_mutasi_barang,status_diterima,3)');
        }*/
        $this->datatables->add_column('action', '$1', 'get_action(kib_f, id_kib_f, validasi)');
        // $this->datatables->edit_column('hewan_tumbuhan_ukuran', '$1', 'format_number(hewan_tumbuhan_ukuran)');
        // $this->datatables->edit_column('jumlah', '$1', 'format_number(jumlah)');
        $this->datatables->edit_column('nilai_kontrak', '$1', 'format_float(nilai_kontrak)');
        // $this->datatables->edit_column('status_validasi', '$1', 'status_validasi(id_mutasi_barang,status_validasi)');
        $this->datatables->edit_column('aksi', "<div class='radio'>
                            <label><input type='radio' name='id_kib' style='width:23px; height:23px' value='$1'></label> 
                            </div>", 'id_kib_f');
        return $this->datatables->generate();
    }

    // datatables
    function json_kib_atb($pihak = null, $data = null)
    {
        $this->load->helper('my_datatable');
        $id_jenis = '5.03';
        $kib = $this->kib[$id_jenis];
        // $this->datatables->select('
        // id_kib_atb, id_pemilik, id_kode_barang, id_kode_lokasi, nama_barang, 
        // nama_barang_migrasi, nomor_register, judul_kajian_nama_software, tanggal_perolehan, asal_usul, 
        // harga, keterangan, deskripsi, kode_lokasi, kode_barang, tanggal_transaksi, nomor_transaksi, 
        // id_sumber_dana, id_rekening, validasi, reject_note, status_barang, id_inventaris, 

        // C.id_mutasi_barang,status_diterima,status_validasi, 
        // id_kode_lokasi_lama, id_kode_lokasi_baru');
        $this->datatables->select('
            id_kib_atb, id_pemilik, id_kode_barang, id_kode_lokasi,A.nama_barang,A.nama_barang_migrasi,
            (case when A.nama_barang_migrasi is not null then A.nama_barang_migrasi else A.nama_barang end) as nama_barang_desc, 
            nama_barang_migrasi, nomor_register, judul_kajian_nama_software, tanggal_perolehan, asal_usul, 
            harga, keterangan, deskripsi, kode_lokasi, kode_barang, tanggal_transaksi, nomor_transaksi, 
            id_sumber_dana, id_rekening, validasi, reject_note, status_barang, id_inventaris ');
        $this->datatables->from($kib['table'] . ' A');
        // $this->datatables->join($this->tbl_mutasi_barang . ' C', 'C.kode_jenis="' . $id_jenis . '" and A.' . $kib['id_name'] . ' = C.id_kib', 'left');
        // $this->datatables->join($this->tbl_mutasi . ' D', 'D.id_mutasi = C.id_mutasi', 'left');
        $this->datatables->where('A.validasi', '2');
        $this->datatables->where(" (id_kib_atb NOT IN (SELECT id_kib FROM tbl_reklas_kode WHERE kode_jenis = '5.03' AND status_validasi = '1')) ", NULL, FALSE);
        


        if ($pihak == 'list_barang') { //pengajuan
            $this->datatables->where('A.id_kode_lokasi', $this->session->userdata('session')->id_kode_lokasi);
            // $this->datatables->where('ifnull(C.id_mutasi,0)','0',false);
            $this->datatables->where('A.status_barang', 'kib');
            if ($data) {
                foreach (json_decode($data) as $key => $value) {
                    $this->datatables->where('A.id_kib_atb <> ', $value);
                }
            }
        }
        /*else if ($pihak == 'pengecekan_barang') {
            $this->datatables->where('C.id_mutasi', $this->input->post('id_mutasi'), false);
            $this->datatables->where('id_kode_lokasi_baru', $this->session->userdata('session')->id_kode_lokasi, false);
            $this->datatables->add_column('radio_terima', '$1', 'radio_pengecekan_barang(id_mutasi_barang,status_diterima,2)');
            $this->datatables->add_column('radio_tolak', '$1', 'radio_pengecekan_barang(id_mutasi_barang,status_diterima,3)');
        }*/
        $this->datatables->add_column('action', '$1', 'get_action(kib_atb, id_kib_atb, validasi)');
        $this->datatables->edit_column('harga', '$1', 'format_number(harga)');
        // $this->datatables->edit_column('status_validasi', '$1', 'status_validasi(id_mutasi_barang,status_validasi)');
        $this->datatables->edit_column('aksi', "<div class='radio'>
                            <label><input type='radio' name='id_kib' style='width:23px; height:23px' value='$1'></label> 
                            </div>", 'id_kib_atb');
        return $this->datatables->generate();
    }

    // datatables
    function json_kib_lainnya($pihak = null, $data = null)
    {
        $this->load->helper('my_datatable');
        // $id_jenis = '02';
        // $kib = $this->kib[$id_jenis];

        $kib = "( SELECT '01' AS `kode_jenis`, `tbl_kib_a`.`id_kib_a` AS `id_kib`, `tbl_kib_a`.`nomor_register` AS `nomor_register`, `tbl_kib_a`.`id_kode_barang` AS `id_kode_barang`, `tbl_kib_a`.`id_kode_barang_aset_lainya` AS `id_kode_barang_aset_lainya`, '-' AS `merk_type`, `tbl_kib_a`.`asal_usul` AS `asal_usul`, `tbl_kib_a`.`tanggal_pembelian` AS `tanggal_pembelian`, `tbl_kib_a`.`tanggal_perolehan` AS `tanggal_perolehan`, `tbl_kib_a`.`validasi` AS `validasi`, `tbl_kib_a`.`harga` AS `harga`, `tbl_kib_a`.`status_barang` AS `status_barang`, `tbl_kib_a`.`kode_barang` AS `kode_barang`, `tbl_kib_a`.`nama_barang` AS `nama_barang`, `tbl_kib_a`.`keterangan` AS `keterangan`, `tbl_kib_a`.`sertifikat_nomor` AS `sertifikat_nomor`, '-' AS `bahan`, '-' AS `ukuran_barang`, `tbl_kib_a`.`satuan` AS `satuan`, `tbl_kib_a`.`kondisi` AS `keadaan_barang`, '0' AS `input_banyak`, `tbl_kib_a`.`deskripsi` AS `deskripsi`, `id_inventaris`, `id_kode_lokasi`, `kode_lokasi` FROM `tbl_kib_a` WHERE `tbl_kib_a`.`id_kode_barang_aset_lainya` IS NOT NULL AND `tbl_kib_a`.`id_kode_barang_aset_lainya` <> '0' UNION ALL SELECT '02' AS `kode_jenis`, `tbl_kib_b`.`id_kib_b` AS `id_kib_b`, `tbl_kib_b`.`nomor_register` AS `nomor_register`, `tbl_kib_b`.`id_kode_barang` AS `id_kode_barang`, `tbl_kib_b`.`id_kode_barang_aset_lainya` AS `id_kode_barang_aset_lainya`, `tbl_kib_b`.`merk_type` AS `merk_type`, `tbl_kib_b`.`asal_usul` AS `asal_usul`, `tbl_kib_b`.`tanggal_pembelian` AS `tanggal_pembelian`, `tbl_kib_b`.`tanggal_perolehan` AS `tanggal_perolehan`, `tbl_kib_b`.`validasi` AS `validasi`, `tbl_kib_b`.`harga` AS `harga`, `tbl_kib_b`.`status_barang` AS `status_barang`, `tbl_kib_b`.`kode_barang` AS `kode_barang`, `tbl_kib_b`.`nama_barang` AS `nama_barang`, `tbl_kib_b`.`keterangan` AS `keterangan`, '-' AS `sertifikat_nomor`, `tbl_kib_b`.`bahan` AS `bahan`, '-' AS `ukuran_barang`, `tbl_kib_b`.`satuan` AS `satuan`, `tbl_kib_b`.`kondisi` AS `keadaan_barang`, `tbl_kib_b`.`input_banyak` AS `input_banyak`, `tbl_kib_b`.`deskripsi` AS `deskripsi`, `id_inventaris`, `id_kode_lokasi`, `kode_lokasi` FROM `tbl_kib_b` WHERE `tbl_kib_b`.`id_kode_barang_aset_lainya` IS NOT NULL AND `tbl_kib_b`.`id_kode_barang_aset_lainya` <> '0' UNION ALL SELECT '03' AS `kode_jenis`, `tbl_kib_c`.`id_kib_c` AS `id_kib_c`, `tbl_kib_c`.`nomor_register` AS `nomor_register`, `tbl_kib_c`.`id_kode_barang` AS `id_kode_barang`, `tbl_kib_c`.`id_kode_barang_aset_lainya` AS `id_kode_barang_aset_lainya`, '-' AS `merk_type`, `tbl_kib_c`.`asal_usul` AS `asal_usul`, `tbl_kib_c`.`tanggal_pembelian` AS `tanggal_pembelian`, `tbl_kib_c`.`tanggal_perolehan` AS `tanggal_perolehan`, `tbl_kib_c`.`validasi` AS `validasi`, `tbl_kib_c`.`harga` AS `harga`, `tbl_kib_c`.`status_barang` AS `status_barang`, `tbl_kib_c`.`kode_barang` AS `kode_barang`, `tbl_kib_c`.`nama_barang` AS `nama_barang`, `tbl_kib_c`.`keterangan` AS `keterangan`, '-' AS `sertifikat_nomor`, '-' AS `bahan`, '-' AS `ukuran_barang`, `tbl_kib_c`.`satuan` AS `satuan`, '-' AS `keadaan_barang`, '0' AS `input_banyak`, `tbl_kib_c`.`deskripsi` AS `deskripsi`, `id_inventaris`, `id_kode_lokasi`, kode_lokasi FROM `tbl_kib_c` WHERE `tbl_kib_c`.`id_kode_barang_aset_lainya` IS NOT NULL AND `tbl_kib_c`.`id_kode_barang_aset_lainya` <> '0' UNION ALL SELECT '04' AS `kode_jenis`, `tbl_kib_d`.`id_kib_d` AS `id_kib_d`, `tbl_kib_d`.`nomor_register` AS `nomor_register`, `tbl_kib_d`.`id_kode_barang` AS `id_kode_barang`, `tbl_kib_d`.`id_kode_barang_aset_lainya` AS `id_kode_barang_aset_lainya`, '-' AS `merk_type`, `tbl_kib_d`.`asal_usul` AS `asal_usul`, `tbl_kib_d`.`tanggal_pembelian` AS `tanggal_pembelian`, `tbl_kib_d`.`tanggal_perolehan` AS `tanggal_perolehan`, `tbl_kib_d`.`validasi` AS `validasi`, `tbl_kib_d`.`harga` AS `harga`, `tbl_kib_d`.`status_barang` AS `status_barang`, `tbl_kib_d`.`kode_barang` AS `kode_barang`, `tbl_kib_d`.`nama_barang` AS `nama_barang`, `tbl_kib_d`.`keterangan` AS `keterangan`, '-' AS `sertifikat_nomor`, '-' AS `bahan`, '-' AS `ukuran_barang`, `tbl_kib_d`.`satuan` AS `satuan`, `tbl_kib_d`.`kondisi` AS `keadaan_barang`, '0' AS `input_banyak`, `tbl_kib_d`.`deskripsi` AS `deskripsi`, `id_inventaris`, `id_kode_lokasi`, kode_lokasi FROM `tbl_kib_d` WHERE `tbl_kib_d`.`id_kode_barang_aset_lainya` IS NOT NULL AND `tbl_kib_d`.`id_kode_barang_aset_lainya` <> 0 UNION ALL SELECT '05' AS `kode_jenis`, `tbl_kib_e`.`id_kib_e` AS `id_kib_e`, `tbl_kib_e`.`nomor_register` AS `nomor_register`, `tbl_kib_e`.`id_kode_barang` AS `id_kode_barang`, `tbl_kib_e`.`id_kode_barang_aset_lainya` AS `id_kode_barang_aset_lainya`, '-' AS `merk_type`, `tbl_kib_e`.`asal_usul` AS `asal_usul`, `tbl_kib_e`.`tanggal_pembelian` AS `tanggal_pembelian`, `tbl_kib_e`.`tanggal_perolehan` AS `tanggal_perolehan`, `tbl_kib_e`.`validasi` AS `validasi`, `tbl_kib_e`.`harga` AS `harga`, `tbl_kib_e`.`status_barang` AS `status_barang`, `tbl_kib_e`.`kode_barang` AS `kode_barang`, `tbl_kib_e`.`nama_barang` AS `nama_barang`, `tbl_kib_e`.`keterangan` AS `keterangan`, '-' AS `sertifikat_nomor`, '-' AS `bahan`, '-' AS `ukuran_barang`, `tbl_kib_e`.`satuan` AS `satuan`, `tbl_kib_e`.`kondisi` AS `keadaan_barang`, `tbl_kib_e`.`input_banyak` AS `input_banyak`, `tbl_kib_e`.`deskripsi` AS `deskripsi`, `id_inventaris`, `id_kode_lokasi`, `kode_lokasi` FROM `tbl_kib_e` WHERE `tbl_kib_e`.`id_kode_barang_aset_lainya` IS NOT NULL AND `tbl_kib_e`.`id_kode_barang_aset_lainya` <> 0 UNION ALL SELECT '06' AS `kode_jenis`, `tbl_kib_f`.`id_kib_f` AS `id_kib_f`, '000000' AS `nomor_register`, `tbl_kib_f`.`id_kode_barang` AS `id_kode_barang`, `tbl_kib_f`.`id_kode_barang_aset_lainya` AS `id_kode_barang_aset_lainya`, '-' AS `merk_type`, `tbl_kib_f`.`asal_usul` AS `asal_usul`, `tbl_kib_f`.`tanggal_pembelian` AS `tanggal_pembelian`, `tbl_kib_f`.`tanggal_perolehan` AS `tanggal_perolehan`, `tbl_kib_f`.`validasi` AS `validasi`, `tbl_kib_f`.`nilai_kontrak` AS `harga`, `tbl_kib_f`.`status_barang` AS `status_barang`, `tbl_kib_f`.`kode_barang` AS `kode_barang`, `tbl_kib_f`.`nama_barang` AS `nama_barang`, `tbl_kib_f`.`keterangan` AS `keterangan`, '-' AS `sertifikat_nomor`, '-' AS `bahan`, '-' AS `ukuran_barang`, `tbl_kib_f`.`satuan` AS `satuan`, '-' AS `keadaan_barang`, '0' AS `input_banyak`, `tbl_kib_f`.`deskripsi` AS `deskripsi`, `id_inventaris`, `id_kode_lokasi`, `kode_lokasi` FROM `tbl_kib_f` WHERE `tbl_kib_f`.`id_kode_barang_aset_lainya` IS NOT NULL AND `tbl_kib_f`.`id_kode_barang_aset_lainya` <> '0' ) A";

        // $this->datatables->select('id_kib_b,A.kode_barang,A.nama_barang,nomor_register,merk_type,
        // ukuran_cc,bahan,tahun_pembelian,nomor_pabrik,nomor_rangka,nomor_mesin,nomor_polisi,
        // nomor_bpkb,asal_usul,harga,keterangan,kode_lokasi, B.nama_barang as nama_barang2,
        // validasi,C.id_mutasi_barang,status_diterima,status_validasi, id_kode_lokasi_lama, id_kode_lokasi_baru');
        $this->datatables->select('A.kode_jenis,id_kib,A.kode_barang,A.nama_barang,
        nomor_register,merk_type,
        ukuran_barang,bahan,YEAR(tanggal_pembelian) AS tahun_pembelian,sertifikat_nomor,asal_usul,harga,keterangan,kode_lokasi, deskripsi,
        validasi,id_inventaris');
        $this->datatables->from("tbl_kib_asetlainnya A");
        $this->datatables->join($this->tbl_kode_barang . ' B', 'A.id_kode_barang = B.id_kode_barang');
        // $this->datatables->join($this->tbl_mutasi_barang . ' C', 'C.kode_jenis="' . $id_jenis . '" and A.' . $kib['id_name'] . ' = C.id_kib', 'left');
        // $this->datatables->join($this->tbl_mutasi . ' D', 'D.id_mutasi = C.id_mutasi', 'left');
        $this->datatables->where(" (CONCAT(id_kib,A.kode_jenis) NOT IN (SELECT CONCAT(id_kib,kode_jenis) FROM tbl_reklas_kode WHERE status_validasi = '1')) ", NULL, FALSE);
        

        if ($pihak == 'list_barang') { //pengajuan
            $this->datatables->where('A.id_kode_lokasi', $this->session->userdata('session')->id_kode_lokasi);
            // $this->datatables->where('ifnull(C.id_mutasi,0)','0',false);
            $this->datatables->where('A.status_barang', 'kib');
            if ($data) {
                foreach (json_decode($data) as $key => $value) {
                    $this->datatables->where('A.id_kib_atb <> ', $value);
                }
            }
        }
        /*else if ($pihak == 'pengecekan_barang') {
            $this->datatables->where('C.id_mutasi', $this->input->post('id_mutasi'), false);
            $this->datatables->where('id_kode_lokasi_baru', $this->session->userdata('session')->id_kode_lokasi, false);
            $this->datatables->add_column('radio_terima', '$1', 'radio_pengecekan_barang(id_mutasi_barang,status_diterima,2)');
            $this->datatables->add_column('radio_tolak', '$1', 'radio_pengecekan_barang(id_mutasi_barang,status_diterima,3)');
        }*/
        // $this->datatables->edit_column('ukuran_cc', '$1', 'format_number(ukuran_cc)');
        $this->datatables->edit_column('harga', '$1', 'format_float(harga)');
        // $this->datatables->edit_column('status_validasi', '$1', 'status_validasi(id_mutasi_barang,status_validasi)');
        $this->datatables->edit_column('aksi', "<div class='radio'>
                            <label><input type='radio' name='id_kib' style='width:23px; height:23px' value='$1'></label> 
                            <input type='hidden' name='kode_jenis_lainnya$1' value='$2'>
                            </div>", 'id_kib,kode_jenis');
        return $this->datatables->generate();
        //$this->datatables->generate(); die($this->db->last_query());
    }

    function get_jenis($kode_kelompok = null)
    {
        $this->db->where('kode_kelompok', $kode_kelompok);
        if ($kode_kelompok == '5')  // aset lainya
            $this->db->where('kode_jenis<>', '03'); // ATB tidak termasuk


        return
            $this->db->get('view_kode_barang_jenis')->result();
        // die($this->db->last_query());
    }


    function get_objek($kode)
    {
        /*
        $this->db->where('kode_kelompok', $kode_kelompok);
        if ($kode_kelompok == '3') { // aset tetap
            $this->db->where('kode_jenis', $kode_jenis);
        } elseif ($kode_kelompok == '5') { // aset lainya
            $this->db->where('kode_jenis<>', '03'); // ATB tidak termasuk
        }*/
        $this->db->where('kode_kelompok', $kode['kode_kelompok']);
        $this->db->where('kode_jenis', $kode['kode_jenis']);
        return
            $this->db->get('view_kode_barang_objek')->result();
        // die($this->db->last_query());
    }

    function get_rincian_objek($kode)
    {
        $this->db->where('kode_kelompok', $kode['kode_kelompok']);
        $this->db->where('kode_jenis', $kode['kode_jenis']);
        $this->db->where('kode_objek', $kode['kode_objek']);
        return
            $this->db->get('view_kode_barang_rincian_objek')->result();
        // die(print_r($this->db->last_query()));
    }

    function get_sub_rincian_objek($kode)
    {
        $this->db->where('kode_kelompok', $kode['kode_kelompok']);
        $this->db->where('kode_jenis', $kode['kode_jenis']);
        $this->db->where('kode_objek', $kode['kode_objek']);
        $this->db->where('kode_rincian_objek', $kode['kode_rincian_objek']);
        return $this->db->get('view_kode_barang_sub_rincian_objek')->result();
    }

    function get_sub_sub_rincian_objek($kode)
    {
        $this->db->where('kode_kelompok', $kode['kode_kelompok']);
        $this->db->where('kode_jenis', $kode['kode_jenis']);
        $this->db->where('kode_objek', $kode['kode_objek']);
        $this->db->where('kode_rincian_objek', $kode['kode_rincian_objek']);
        $this->db->where('kode_sub_rincian_objek', $kode['kode_sub_rincian_objek']);
        // karena di kib masih di kode 000, habis migrasi data
        $this->db->where('kode_sub_sub_rincian_objek <> ', '000');
        // $this->db->where('kode_sub_sub_rincian_objek <> ', '0');
        return $this->db->get('view_kode_barang_sub_sub_rincian_objek')->result();
    }



    function get_list_kelompok($data_where)
    {
        $this->db->where_not_in('kode_kelompok', $data_where);
        $res = $this->db->get('view_kode_barang_kelompok');
        return $res->result_array();
    }

    function cek_barang_tujuan($kode_jenis,$kode_barang)
    {
        $id_jenis = $kode_jenis[2];
        $kib = $this->kib[$id_jenis];
        $this->db->where('kode_barang', $kode_barang);
        $this->db->where('id_kode_lokasi', $this->session->userdata('session')->id_kode_lokasi);
        return
            $this->db->get($kib['table'])->num_rows();
        // die(print_r($this->db->last_query()));
    }










    /*
    public function get_mutasi($id_mutasi)
    {
        $this->db->select('A.id_mutasi, A.tanggal, A.tanggal_bast, A.nomor_bast, A.status_validasi, A.tanggal_validasi, A.status_proses, B.instansi as pengguna_lama, C.instansi as pengguna_baru');
        $this->db->from($this->tbl_mutasi . ' A');
        $this->db->join($this->tbl_kode_lokasi . ' B', 'A.id_kode_lokasi_lama=B.id_kode_lokasi', 'left');
        $this->db->join($this->tbl_kode_lokasi . ' C', 'A.id_kode_lokasi_baru=C.id_kode_lokasi', 'left');
        $this->db->where($this->id_mutasi, $id_mutasi);
        return $this->db->get()->row();
    }
*/
    /*
    public function get_mutasi_picture($id_mutasi)
    {
        return $this->db->get_where($this->tbl_mutasi_picture, array($this->id_mutasi => $id_mutasi,))->result();
    }
*/
    /*
    function get_skpd($id_kode_lokasi = null)
    {
        $this->db->select('B.*');
        $this->db->from('tbl_kode_lokasi A');
        $this->db->join('view_pengguna B', 'A.pengguna=B.pengguna', 'left');
        $this->db->where(array('A.id_kode_lokasi' => $id_kode_lokasi,));

        $res = $this->db->get();
        return $res->row_array();
    }
*/
    /*
    function get_status_mutasi_all()
    {
        return $this->db->get('tbl_master_status_mutasi')->result_array();
    }
*/
}
