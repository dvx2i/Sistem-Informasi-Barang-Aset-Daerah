<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Migrasi extends MX_Controller
{

    function __construct()
    {
        parent::__construct();
        $session = $this->session->userdata('session');
        $menu = $this->session->userdata('menu');
        $this->load->model('Migrasi_model', 'Migrasi_model');
    }
    public function index()
    {
        $data['session'] = $this->session->userdata('menu');
        $data['validasi'] = false;
        foreach ($data['session'] as $key => $value) {
            if ($value['name'] == 'Validasi')    $data['validasi'] = TRUE;
        }

        $data['jenis'] = $this->global_model->kode_jenis;
        $data['kode_jenis'] = set_value('kode_jenis');
        $data['action'] = "migrasi/proses";
        $data["content"] = $this->load->view("migrasi", $data, TRUE);
        $data["title"]   = "Migrasi";
        $data['breadcrumb'] = array(
            array('label' => 'Migrasi', 'url' => '#', 'icon' => 'fa dashboard', 'li_class' => 'active'),
        );
        $this->load->view('template', $data);
    }


    // kib_a, kib_b, kib_c, kib_d, kib_e, kib_f
    public function proses()
    {
        // die(json_encode($_FILES));
        // die($this->input->post('kode_jenis'));
        $data = array(); // Buat variabel $data sebagai array

        if (isset($_POST['migrasi'])) { // Jika user menekan tombol Preview pada form
            // lakukan upload file dengan memanggil function upload yang ada di SiswaModel.php
            $jenis = $this->global_model->kode_jenis;
            $kode_jenis = $this->input->post('kode_jenis');
            // die($jenis[$kode_jenis]['controller_name']);

            $session = $this->session->userdata('session');
            // die(json_encode($session));
            $filename = $jenis[$kode_jenis]['controller_name'] . "_" . $session->id_user; // Kita tentukan nama filenya

            $upload = $this->upload($filename);
            // die(json_encode($upload));
            // die($upload['result']);
            if ($upload['result'] == "failed") { // jika upload gagal
                echo "<script>
                alert('Upload file gagal. (Jenis file harus .xlsx ).');
                window.close();
                </script>";
            }
            // Load plugin PHPExcel nya
            // include APPPATH . 'third_party/PHPExcel/PHPExcel.php';
            include APPPATH . 'libraries/Excel/PHPExcel.php';

            // $csvreader = PHPExcel_IOFactory::createReader('Xlsx')->setDelimiter("\t");
            $csvreader = PHPExcel_IOFactory::createReader('Excel2007');
            $loadcsv = $csvreader->load('assets/files/migrasi_upload/' . $filename . '.xlsx');

            $sheet = $loadcsv->getActiveSheet()->getRowIterator();
            $data = null;
            $count_row = $loadcsv->setActiveSheetIndex(0)->getHighestRow();

            if ($count_row > 1001) {
                echo "<script>
				alert('Data Maksimal 1000 Baris.');
                window.close();
                </script>";
            }

            //==================================================================================
            if ($kode_jenis == '01') $data = $this->set_kib_a($sheet, $session->id_user);
            else if ($kode_jenis == '02') $data = $this->set_kib_b($sheet, $session->id_user);
            else if ($kode_jenis == '03') $data = $this->set_kib_c($sheet, $session->id_user);
            else if ($kode_jenis == '04') $data = $this->set_kib_d($sheet, $session->id_user); // die("TAHUN UNTUK KEPENTINGAN VALIDASI TDK ADA"); // $data = $this->set_kib_d($sheet);
            else if ($kode_jenis == '05') $data = $this->set_kib_e($sheet, $session->id_user);
            else if ($kode_jenis == '06') $data = $this->set_kib_f($sheet, $session->id_user);
            //==================================================================================

            $this->Migrasi_model->insert_multiple($jenis[$kode_jenis]['table'] . "_migrasi", $kode_jenis, $data, $session->id_user);
            // die("coba B");

            echo '<script type="text/javascript">
                if (window.confirm("Lanjut Proses Migrasi."))
                {
                    window.location.href = "' . base_url("migrasi/proses_migrasi/$kode_jenis/$session->id_user") . '";
                }
                </script>';
        }
    }




    public function upload($filename = null)
    {
        // die(json_encode($filename));
        $this->load->library('upload'); // Load librari upload
        // $filename = "kib_a";
        $config['upload_path'] = 'assets/files/migrasi_upload/';
        // $config['allowed_types'] = 'csv';
        $config['allowed_types'] = 'xlsx|xls';
        // $config['max_size']  = '524288'; //512 MB
        $config['max_size']  = '1024'; //1 MB
        $config['overwrite'] = true;
        $config['file_name'] = $filename;
        // die(json_encode($this->input->post()));
        // die(json_encode($_FILES['file']));
        $this->upload->initialize($config); // Load konfigurasi uploadnya
        if ($this->upload->do_upload('file')) { // Lakukan upload dan Cek jika proses upload berhasil
            // Jika berhasil :
            $return = array('result' => 'success', 'file' => $this->upload->data(), 'error' => '');
            return $return;
        } else {
            // Jika gagal :
            $return = array('result' => 'failed', 'file' => '', 'error' => $this->upload->display_errors());
            return $return;
            // echo json_encode(array('result' => 'failed', 'file' => '', 'error' => $this->upload->display_errors()));
        }
    }

    public function proses_migrasi($kode_jenis, $id_user)
    {
        $res = $this->Migrasi_model->proses_migrasi($kode_jenis, $id_user);
        // die(json_encode($res));
        if ($res['status'] == 'true') {
            echo '<script type="text/javascript">
            alert("Migrasi Selesai.");
            window.close();
            </script>';
        } elseif ($res['status'] == 'false') {
            echo '<script type="text/javascript">
            alert("Perika file excel, Kode Lokasi, Kode Barang, Tahun, Harus Di isi.");
            window.close();
            </script>';
        }
    }

    public function set_kib_a($sheet, $id_user)
    {
        // Buat sebuah variabel array untuk menampung array data yg akan kita insert ke database
        $data = array();

        $numrow = 1;
        // die(json_encode($sheet));
        foreach ($sheet as $row) {
            // Cek $numrow apakah lebih dari 1
            // Artinya karena baris pertama adalah nama-nama kolom
            // Jadi dilewat saja, tidak usah diimport
            if ($numrow > 1) {
                // START -->
                // Skrip untuk mengambil value nya
                $cellIterator = $row->getCellIterator();
                $cellIterator->setIterateOnlyExistingCells(false); // Loop all cells, even if it is not set

                $get = array(); // Valuenya akan di simpan kedalam array,dimulai dari index ke 0
                foreach ($cellIterator as $cell) {
                    array_push($get, $cell->getValue()); // Menambahkan value ke variabel array $get
                }
                // <-- END
                // die(json_encode($get));

                // Ambil data value yang telah di ambil dan dimasukkan ke variabel $get
                // Kita push (add) array data ke variabel data

                // die(json_encode($get));
                $tgl_transaksi      = isset($get[0]) ? $get[0] : null;
                $no_transaksi       = isset($get[1]) ? $get[1] : null;
                $id_sumber_dana     = isset($get[2]) ? $get[2] : null;
                $id_kode_anggaran   = isset($get[3]) ? $get[3] : null;
                $Id_Inventaris      = isset($get[4]) ? $get[4] : null;
                $Permendagri_90     = isset($get[5]) ? $get[5] : null;
                $Nama_Barang_Permendagri_90 = isset($get[6]) ? $get[6] : null;
                $Nama_Simbada       = isset($get[7]) ? $get[7] : null;
                $Luas_Tanah         = isset($get[8]) ? $get[8] : null;
                $Thn_Pengadaan      = isset($get[9]) ? $get[9] : null;
                $Letak              = isset($get[10]) ? $get[10] : null;
                $Hak_Tanah          = isset($get[11]) ? $get[11] : null;
                $Tgl_Sertifikat     = isset($get[12]) ? $get[12] : null;
                $No_Sertifikat      = isset($get[13]) ? $get[13] : null;
                $Penggunaan         = isset($get[14]) ? $get[14] : null;
                $Asal_Usul          = isset($get[15]) ? $get[15] : null;
                $Harga_Barang       = isset($get[16]) ? $get[16] : null;
                $Keterangan         = isset($get[17]) ? $get[17] : null;
                $Kode_Lokasi        = isset($get[18]) ? $get[18] : null;
                $Satuan             = isset($get[19]) ? $get[19] : null;

                array_push($data, array(
                    "id_user" => $id_user,
                    // "id_kib_a" =>
                    "id_pemilik" => 2,
                    // "id_kode_barang" =>
                    // "id_kode_lokasi" =>
                    "nama_barang" => $Nama_Barang_Permendagri_90,
                    "nama_barang_migrasi" => $Nama_Simbada,
                    "kode_barang" => $Permendagri_90,
                    // "nomor_register" =>
                    "luas" => $Luas_Tanah,
                    "tahun_pengadaan" => $Thn_Pengadaan,
                    "letak_alamat" => $Letak,
                    "status_hak" => $Hak_Tanah,
                    "sertifikat_tanggal" => $Tgl_Sertifikat,
                    "sertifikat_nomor" => $No_Sertifikat,
                    "penggunaan" => $Penggunaan,
                    // "kondisi" =>
                    "asal_usul" => $Asal_Usul,
                    "harga" => $Harga_Barang,
                    "keterangan" => $Keterangan,
                    "deskripsi" => $Nama_Simbada,
                    "kode_lokasi" => $Kode_Lokasi,
                    "tanggal_transaksi" => $tgl_transaksi,
                    "nomor_transaksi" => $no_transaksi,
                    "tanggal_pembelian" => $Thn_Pengadaan . "-12-31",
                    "tanggal_perolehan" => $Thn_Pengadaan . "-12-31",
                    "id_sumber_dana" => $id_sumber_dana,
                    "id_rekening" => $id_kode_anggaran,
                    "validasi" => 1,
                    // "reject_note" =>
                    // "kib_f" =>1
                    // "kode_barang_f" =>
                    // "latitute" =>
                    // "longitute" =>
                    "status_barang" => 'kib_' . $id_user,
                    "id_inventaris" => $Id_Inventaris,
                    "satuan" => $Satuan,
                    "created_at" => '2019-12-31 00:00:00',
                    "updated_at" => '2019-12-31 00:00:00',

                    // $No_Kode_Barang
                    // $Nama_Sesuai_Kode
                    // $Kode_SKPD
                    // $Nama_SKPD
                    // $Nama_Lokasi
                ));
                // die(json_encode($data));
            }

            $numrow++; // Tambah 1 setiap kali looping
        }
        return $data;
    }

    public function set_kib_b($sheet, $id_user)
    {

        $data = array();
        $numrow = 1;
        // die(json_encode($sheet));
        foreach ($sheet as $row) {

            if ($numrow > 1) {
                $cellIterator = $row->getCellIterator();
                $cellIterator->setIterateOnlyExistingCells(false); // Loop all cells, even if it is not set

                $get = array(); // Valuenya akan di simpan kedalam array,dimulai dari index ke 0
                foreach ($cellIterator as $cell) {
                    array_push($get, $cell->getValue()); // Menambahkan value ke variabel array $get
                }

                // die(json_encode($get));
                // $Id_Inventaris    = isset($get[0]) ? $get[0] : null;
                $data_get = array();
                $data_get['tgl_transaksi']                  = isset($get[0]) ? $get[0] : null;
                $data_get['no_transaksi']                   = isset($get[1]) ? $get[1] : null;
                $data_get['id_sumber_dana']                 = isset($get[2]) ? $get[2] : null;
                $data_get['id_kode_anggaran']               = isset($get[3]) ? $get[3] : null;
                $data_get['Id_Inventaris']                  = isset($get[4]) ? $get[4] : null;
                $data_get['Permendagri_90']                 = isset($get[5]) ? $get[5] : null;
                $data_get['Nama_Barang_Permendagri_90']     = isset($get[6]) ? $get[6] : null;
                $data_get['Nama_Simbada_Deskripsi_Barang']  = isset($get[7]) ? $get[7] : null;
                $data_get['Merk']                           = isset($get[8]) ? $get[8] : null;
                $data_get['Type']                           = isset($get[9]) ? $get[9] : null;
                $data_get['Ukuran']                         = isset($get[10]) ? $get[10] : null;
                $data_get['Bahan']                          = isset($get[11]) ? $get[11] : null;
                $data_get['Thn_Beli']                       = isset($get[12]) ? $get[12] : null;
                $data_get['No_Pabrik']                      = isset($get[13]) ? $get[13] : null;
                $data_get['No_Rangka']                      = isset($get[14]) ? $get[14] : null;
                $data_get['No_Mesin']                       = isset($get[15]) ? $get[15] : null;
                $data_get['No_Polisi']                      = isset($get[16]) ? $get[16] : null;
                $data_get['No_BPKB']                        = isset($get[17]) ? $get[17] : null;
                $data_get['Asal_Usul']                      = isset($get[18]) ? $get[18] : null;
                $data_get['Harga_Barang']                   = isset($get[19]) ? $get[19] : null;
                $data_get['Keterangan']                     = isset($get[20]) ? $get[20] : null;
                $data_get['Kode_Lokasi']                    = isset($get[21]) ? $get[21] : null;
                $data_get['Satuan']                         = isset($get[22]) ? $get[22] : null;


                // die(json_encode($data_get));
                array_push($data, array(
                    "id_user" => $id_user,
                    // "id_kib_b" => ,
                    "id_pemilik" => 2,
                    // "id_kode_barang"=> ,
                    // "id_kode_lokasi"=> ,
                    "kode_barang" => $data_get['Permendagri_90'],
                    "nama_barang" => $data_get['Nama_Barang_Permendagri_90'],
                    "nama_barang_migrasi" => $data_get['Nama_Simbada_Deskripsi_Barang'],
                    // "nomor_register"=> ,
                    "merk_type" => $data_get['Merk'] . "/" . $data_get['Type'],
                    "ukuran_cc" => $data_get['Ukuran'],
                    "bahan" => $data_get['Bahan'],
                    "tahun_pembelian" => $data_get['Thn_Beli'],
                    "nomor_pabrik" => $data_get['No_Pabrik'],
                    "nomor_rangka" => $data_get['No_Rangka'],
                    "nomor_mesin" => $data_get['No_Mesin'],
                    "nomor_polisi" => $data_get['No_Polisi'],
                    "nomor_bpkb" => $data_get['No_BPKB'],
                    // "kondisi"=> , tidak ada
                    "asal_usul" => $data_get['Asal_Usul'],
                    "harga" => $data_get['Harga_Barang'],
                    "keterangan" => $data_get['Keterangan'],
                    "deskripsi" => $data_get['Nama_Simbada_Deskripsi_Barang'],
                    "kode_lokasi" => $data_get['Kode_Lokasi'],
                    "tanggal_transaksi" => $data_get['tgl_transaksi'],
                    "nomor_transaksi" => $data_get['no_transaksi'],
                    "tanggal_pembelian" => $data_get['Thn_Beli'] . "-12-31",
                    "tanggal_perolehan" => $data_get['Thn_Beli'] . "-12-31",
                    "id_sumber_dana" => $data_get['id_sumber_dana'],
                    "id_rekening" => $data_get['id_kode_anggaran'],
                    "validasi" => 1,
                    // "reject_note"=> ,
                    // "kib_f"=> ,
                    // "kode_barang_f"=> ,
                    // "input_banyak"=> ,
                    "status_barang" => 'kib_' . $id_user,
                    "id_inventaris" => $data_get['Id_Inventaris'],
                    "satuan" => $data_get['Satuan'],
                    "created_at" => '2019-12-31 00:00:00',
                    "updated_at" => '2019-12-31 00:00:00',


                ));
                // die(json_encode($data));
            }
            $numrow++; // Tambah 1 setiap kali looping
        }
        return $data;
    }

    public function set_kib_c($sheet, $id_user)

    {
        $data = array();
        $numrow = 1;
        // die(json_encode($sheet));
        foreach ($sheet as $row) {

            if ($numrow > 1) {
                $cellIterator = $row->getCellIterator();
                $cellIterator->setIterateOnlyExistingCells(false); // Loop all cells, even if it is not set

                $get = array(); // Valuenya akan di simpan kedalam array,dimulai dari index ke 0
                foreach ($cellIterator as $cell) {
                    array_push($get, $cell->getValue()); // Menambahkan value ke variabel array $get
                }

                // die(json_encode($get));
                $data_get = null;
                $data_get['tgl_transaksi']  =           isset($get[0]) ? $get[0] : null;
                $data_get['no_transaksi']   =           isset($get[1]) ? $get[1] : null;
                $data_get['id_sumber_dana'] =           isset($get[2]) ? $get[2] : null;
                $data_get['id_kode_anggaran'] =         isset($get[3]) ? $get[3] : null;
                $data_get['Id_Inventaris'] =            isset($get[4]) ? $get[4] : null;
                $data_get['Permendagri_90'] =           isset($get[5]) ? $get[5] : null;
                $data_get['Nama_Barang_Permendagri_90'] = isset($get[6]) ? $get[6] : null;
                $data_get['Nama_Simbada_Deskripsi_Barang'] = isset($get[7]) ? $get[7] : null;
                $data_get['Kondisi_Bangunan'] =         isset($get[8]) ? $get[8] : null;
                $data_get['Bertingkat'] =               isset($get[9]) ? $get[9] : null;
                $data_get['Beton'] =                    isset($get[10]) ? $get[10] : null;
                $data_get['Luas_Lantai'] =              isset($get[11]) ? $get[11] : null;
                $data_get['Letak'] =                    isset($get[12]) ? $get[12] : null;
                $data_get['Thn_Beli'] =                 isset($get[13]) ? $get[13] : null;
                $data_get['Tgl_Sertifikat'] =           isset($get[14]) ? $get[14] : null;
                $data_get['No_Sertifikat'] =            isset($get[15]) ? $get[15] : null;
                $data_get['Luas_Gedung'] =              isset($get[16]) ? $get[16] : null;
                $data_get['Status_Tanah'] =             isset($get[17]) ? $get[17] : null;
                $data_get['No_Kode_Tanah'] =            isset($get[18]) ? $get[18] : null;
                $data_get['Asal_Usul'] =                isset($get[19]) ? $get[19] : null;
                $data_get['Harga_Barang'] =             isset($get[20]) ? $get[20] : null;
                $data_get['Keterangan'] =               isset($get[21]) ? $get[21] : null;
                $data_get['Kode_Lokasi'] =              isset($get[22]) ? $get[22] : null;
                $data_get['Satuan'] =                   isset($get[23]) ? $get[23] : null;


                array_push($data, array(
                    "id_user" => $id_user,
                    // "id_kib_c" => 
                    "id_pemilik" => 2,
                    // "id_kode_barang" => 
                    // "id_kode_lokasi" => 
                    "nama_barang" => $data_get['Nama_Barang_Permendagri_90'],
                    "nama_barang_migrasi" => $data_get['Nama_Simbada_Deskripsi_Barang'],
                    "kode_barang" => $data_get['Permendagri_90'],
                    // "nomor_register" => 
                    "kondisi_bangunan" => $data_get['Kondisi_Bangunan'],
                    "bangunan_bertingkat" => $data_get['Bertingkat'],
                    "bangunan_beton" => $data_get['Beton'],
                    "luas_lantai_m2" => $data_get['Luas_Lantai'],
                    "lokasi_alamat" => $data_get['Letak'],
                    "gedung_tanggal" => $data_get['Tgl_Sertifikat'],
                    "gedung_nomor" => $data_get['No_Sertifikat'],
                    "luas_m2" => $data_get['Luas_Gedung'],
                    "status" => $data_get['Status_Tanah'],
                    "nomor_kode_tanah" => $data_get['No_Kode_Tanah'],
                    "asal_usul" => $data_get['Asal_Usul'],
                    "harga" => $data_get['Harga_Barang'],
                    "keterangan" => $data_get['Keterangan'],
                    "deskripsi" => $data_get['Nama_Simbada_Deskripsi_Barang'],
                    "kode_lokasi" => $data_get['Kode_Lokasi'],
                    "tanggal_transaksi" => $data_get['tgl_transaksi'],
                    "nomor_transaksi" => $data_get['no_transaksi'],
                    "tanggal_pembelian" => $data_get['Thn_Beli'] . "-12-31",
                    "tanggal_perolehan" => $data_get['Thn_Beli'] . "-12-31",
                    "id_sumber_dana" => $data_get['id_sumber_dana'],
                    "id_rekening" => $data_get['id_kode_anggaran'],
                    "validasi" => 1,
                    // "reject_note" => 
                    // "kib_f" => 
                    // "kode_barang_f" => 
                    // "latitute" => 
                    // "longitute" => 
                    "status_barang" => 'kib_' . $id_user,
                    "id_inventaris" =>  $data_get['Id_Inventaris'],
                    "Satuan"        =>  $data_get['Satuan'],
                    "created_at"    => '2019-12-31 00:00:00',
                    "updated_at"    => '2019-12-31 00:00:00',

                ));
                // die(json_encode($data));
            }
            $numrow++; // Tambah 1 setiap kali looping
        }
        return $data;
    }
    public function set_kib_d($sheet, $id_user)
    {
        $data = array();
        $numrow = 1;
        // die(json_encode($sheet));
        foreach ($sheet as $row) {

            if ($numrow > 1) {
                $cellIterator = $row->getCellIterator();
                $cellIterator->setIterateOnlyExistingCells(false); // Loop all cells, even if it is not set

                $get = array(); // Valuenya akan di simpan kedalam array,dimulai dari index ke 0
                foreach ($cellIterator as $cell) {
                    array_push($get, $cell->getValue()); // Menambahkan value ke variabel array $get
                }

                // die(json_encode($get));
                $data_get = null;
                $data_get['tgl_transaksi']              = isset($get[0]) ? $get[0] : null;
                $data_get['no_transaksi']               = isset($get[1]) ? $get[1] : null;
                $data_get['id_sumber_dana']             = isset($get[2]) ? $get[2] : null;
                $data_get['id_kode_anggaran']           = isset($get[3]) ? $get[3] : null;
                $data_get['Id_Inventaris']              = isset($get[4]) ? $get[4] : null;
                $data_get['Permendagri_90']             = isset($get[5]) ? $get[5] : null;
                $data_get['Nama_Barang_Permendagri_90']     = isset($get[6]) ? $get[6] : null;
                $data_get['Nama_Simbada_Deskripsi_Barang']  = isset($get[7]) ? $get[7] : null;
                $data_get['Konstruksi']                 = isset($get[8]) ? $get[8] : null;
                $data_get['Panjang']                    = isset($get[9]) ? $get[9] : null;
                $data_get['Lebar']                      = isset($get[10]) ? $get[10] : null;
                $data_get['Luas']                       = isset($get[11]) ? $get[11] : null;
                $data_get['Letak']                      = isset($get[12]) ? $get[12] : null;
                $data_get['Tgl_Sertifikat']             = isset($get[13]) ? $get[13] : null;
                $data_get['No_Sertifikat']              = isset($get[14]) ? $get[14] : null;
                $data_get['Status_Tanah']               = isset($get[15]) ? $get[15] : null;
                $data_get['No_Kode_Tanah']              = isset($get[16]) ? $get[16] : null;
                $data_get['Asal_Usul']                  = isset($get[17]) ? $get[17] : null;
                $data_get['Harga_Barang']               = isset($get[18]) ? $get[18] : null;
                $data_get['Kondisi_Bangunan']           = isset($get[19]) ? $get[19] : null;
                $data_get['Keterangan']                 = isset($get[20]) ? $get[20] : null;
                $data_get['Kode_Lokasi']                = isset($get[21]) ? $get[21] : null;
                $data_get['Satuan']                     = isset($get[22]) ? $get[22] : null;
                $data_get['Thn_Beli']                   = isset($get[23]) ? $get[23] : null;


                array_push($data, array(
                    "id_user" => $id_user,
                    // "id_kib_d" =>
                    "id_pemilik" => 2,
                    // "id_kode_barang" =>
                    // "id_kode_lokasi" =>
                    "nama_barang" => $data_get['Nama_Barang_Permendagri_90'],
                    "nama_barang_migrasi" => $data_get['Nama_Simbada_Deskripsi_Barang'],
                    "kode_barang" => $data_get['Permendagri_90'],
                    // "nomor_register" =>
                    "konstruksi" => $data_get['Konstruksi'],
                    "panjang_km" => $data_get['Panjang'],
                    "lebar_m" => $data_get['Lebar'],
                    "luas_m2" => $data_get['Luas'],
                    "letak_lokasi" => $data_get['Letak'],
                    "dokumen_tanggal" => $data_get['Tgl_Sertifikat'],
                    "dokumen_nomor" => $data_get['No_Sertifikat'],
                    "status_tanah" => $data_get['Status_Tanah'],
                    "kode_tanah" => $data_get['No_Kode_Tanah'],
                    "asal_usul" => $data_get['Asal_Usul'],
                    "harga" => $data_get['Harga_Barang'],
                    "kondisi" => $data_get['Kondisi_Bangunan'],
                    "keterangan" => $data_get['Keterangan'],
                    "deskripsi" => $data_get['Nama_Simbada_Deskripsi_Barang'],
                    "kode_lokasi" => $data_get['Kode_Lokasi'],
                    "tanggal_transaksi" => $data_get['tgl_transaksi'],
                    "nomor_transaksi" => $data_get['no_transaksi'],
                    "tanggal_pembelian" => $data_get['Thn_Beli'] . "-12-31",
                    "tanggal_perolehan" => $data_get['Thn_Beli'] . "-12-31",
                    "id_sumber_dana" => $data_get['id_sumber_dana'],
                    "id_rekening" => $data_get['id_kode_anggaran'],
                    "validasi" => 1,
                    // "reject_note" =>
                    // "kib_f" =>
                    // "kode_barang_f" =>
                    // "latitute" =>
                    // "longitute" =>
                    "status_barang" => 'kib_' . $id_user,
                    "id_inventaris" => $data_get['Id_Inventaris'],
                    "satuan"        => $data_get['Satuan'],
                    "created_at"    => '2019-12-31 00:00:00',
                    "updated_at"    =>  '2019-12-31 00:00:00',

                ));
                // die(json_encode($data));
            }
            $numrow++; // Tambah 1 setiap kali looping
        }
        return $data;
    }
    public function set_kib_e($sheet, $id_user)
    {
        $data = array();
        $numrow = 1;
        // die(json_encode($sheet));
        foreach ($sheet as $row) {

            if ($numrow > 1) {
                $cellIterator = $row->getCellIterator();
                $cellIterator->setIterateOnlyExistingCells(false); // Loop all cells, even if it is not set

                $get = array(); // Valuenya akan di simpan kedalam array,dimulai dari index ke 0
                foreach ($cellIterator as $cell) {
                    array_push($get, $cell->getValue()); // Menambahkan value ke variabel array $get
                }

                // die(json_encode($get));
                $data_get = null;
                $data_get['tgl_transaksi']          = isset($get[0]) ? $get[0] : null;
                $data_get['no_transaksi']           = isset($get[1]) ? $get[1] : null;
                $data_get['id_sumber_dana']         = isset($get[2]) ? $get[2] : null;
                $data_get['id_kode_anggaran']       = isset($get[3]) ? $get[3] : null;
                $data_get["Id_Inventaris"]          = isset($get[4]) ? $get[4] : null;
                $data_get["Permendagri_90"]         = isset($get[5]) ? $get[5] : null;
                $data_get["Nama_Barang_Permendagri_90"]     = isset($get[6]) ? $get[6] : null;
                $data_get["Nama_Simbada_Deskripsi_Barang"]  = isset($get[7]) ? $get[7] : null;
                $data_get["Judul_Buku"]             = isset($get[8]) ? $get[8] : null;
                $data_get["Pencipta_Buku"]          = isset($get[9]) ? $get[9] : null;
                $data_get["Spesifikasi"]            = isset($get[10]) ? $get[10] : null;
                $data_get["Asal_Daerah"]            = isset($get[11]) ? $get[11] : null;
                $data_get["Pencipta_Seni"]          = isset($get[12]) ? $get[12] : null;
                $data_get["Bahan"]                  = isset($get[13]) ? $get[13] : null;
                $data_get["Jenis_Hewan_Tmbhn"]      = isset($get[14]) ? $get[14] : null;
                $data_get["Ukuran"]                 = isset($get[15]) ? $get[15] : null;
                $data_get["Jumlah_Barang"]          = isset($get[16]) ? $get[16] : null;
                $data_get["Thn_Beli"]               = isset($get[17]) ? $get[17] : null;
                $data_get["Asal_Usul"]              = isset($get[18]) ? $get[18] : null;
                $data_get["Harga_Barang"]           = isset($get[19]) ? $get[19] : null;
                $data_get["Keterangan"]             = isset($get[20]) ? $get[20] : null;
                $data_get["Kode_Lokasi"]            = isset($get[21]) ? $get[21] : null;
                $data_get["Satuan"]                 = isset($get[22]) ? $get[22] : null;


                array_push($data, array(
                    "id_user" => $id_user,
                    // "id_kib_e" =>
                    "id_pemilik" => 2,
                    // "id_kode_barang"=>
                    // "id_kode_lokasi"=>
                    "nama_barang" => $data_get["Nama_Barang_Permendagri_90"],
                    "nama_barang_migrasi" => $data_get["Nama_Simbada_Deskripsi_Barang"],
                    "kode_barang" => $data_get["Permendagri_90"],
                    // "nomor_register"=>
                    "judul_pencipta" => $data_get["Judul_Buku"] . "/" . $data_get["Pencipta_Buku"],
                    "spesifikasi" => $data_get["Spesifikasi"],
                    "kesenian_asal_daerah" => $data_get["Asal_Daerah"],
                    "kesenian_pencipta" => $data_get["Pencipta_Seni"],
                    "kesenian_bahan" => $data_get["Bahan"],
                    "hewan_tumbuhan_jenis" => $data_get["Jenis_Hewan_Tmbhn"],
                    "hewan_tumbuhan_ukuran" => $data_get["Ukuran"],
                    "jumlah" => $data_get["Jumlah_Barang"],
                    "tahun_pembelian" => $data_get["Thn_Beli"],
                    // "kondisi"=>
                    "asal_usul" => $data_get["Asal_Usul"],
                    "harga" => $data_get["Harga_Barang"],
                    "keterangan" => $data_get["Keterangan"],
                    "deskripsi" => $data_get["Nama_Simbada_Deskripsi_Barang"],
                    "kode_lokasi" => $data_get["Kode_Lokasi"],
                    "tanggal_transaksi" => $data_get['tgl_transaksi'],
                    "nomor_transaksi" => $data_get['no_transaksi'],
                    "tanggal_pembelian" => $data_get['Thn_Beli'] . "-12-31",
                    "tanggal_perolehan" => $data_get['Thn_Beli'] . "-12-31",
                    "id_sumber_dana" => $data_get['id_sumber_dana'],
                    "id_rekening" => $data_get['id_kode_anggaran'],
                    "validasi" => 1,
                    // "reject_note"=>
                    // "kib_f"=>
                    // "kode_barang_f"=>
                    // "input_banyak"=>
                    "status_barang" => 'kib_' . $id_user,
                    "id_inventaris" => $data_get["Id_Inventaris"],
                    "satuan"        => $data_get["Satuan"],
                    "created_at"    => '2019-12-31 00:00:00',
                    "updated_at"    => '2019-12-31 00:00:00',

                ));
                // die(json_encode($data));
            }
            $numrow++; // Tambah 1 setiap kali looping
        }
        return $data;
    }
    public function set_kib_f($sheet, $id_user)
    {
        $data = array();
        $numrow = 1;
        // die(json_encode($sheet));
        foreach ($sheet as $row) {

            if ($numrow > 1) {
                $cellIterator = $row->getCellIterator();
                $cellIterator->setIterateOnlyExistingCells(false); // Loop all cells, even if it is not set

                $get = array(); // Valuenya akan di simpan kedalam array,dimulai dari index ke 0
                foreach ($cellIterator as $cell) {
                    array_push($get, $cell->getValue()); // Menambahkan value ke variabel array $get
                }

                // die(json_encode($get));
                $data_get = null;
                $data_get['tgl_transaksi']      = isset($get[0]) ? $get[0] : null;
                $data_get['no_transaksi']       = isset($get[1]) ? $get[1] : null;
                $data_get['id_sumber_dana']     = isset($get[2]) ? $get[2] : null;
                $data_get['id_kode_anggaran']   = isset($get[3]) ? $get[3] : null;
                $data_get["Id_Inventaris"]      = isset($get[4]) ? $get[4] : null;
                $data_get["Permendagri_90"]     = isset($get[5]) ? $get[5] : null;
                $data_get["Nama_Barang_Permendagri_90"]     = isset($get[6]) ? $get[6] : null;
                $data_get["Nama_Simbada_Deskripsi_Barang"]  = isset($get[7]) ? $get[7] : null;
                $data_get["Bangunan"]           = isset($get[8]) ? $get[8] : null;
                $data_get["Bertingkat"]         = isset($get[9]) ? $get[9] : null;
                $data_get["Beton"]              = isset($get[10]) ? $get[10] : null;
                $data_get["Luas_Lantai"]        = isset($get[11]) ? $get[11] : null;
                $data_get["Letak"]              = isset($get[12]) ? $get[12] : null;
                $data_get["No_Sertifikat"]      = isset($get[13]) ? $get[13] : null;
                $data_get["Tgl_Sertifikat"]     = isset($get[14]) ? $get[14] : null;
                $data_get["Mulai_Konstruksi"]   = isset($get[15]) ? $get[15] : null;
                $data_get["Status_Tanah"]       = isset($get[16]) ? $get[16] : null;
                $data_get["No_Kode_Tanah"]      = isset($get[17]) ? $get[17] : null;
                $data_get["Thn_Beli"]           = isset($get[18]) ? $get[18] : null;
                $data_get["Asal_Usul"]          = isset($get[19]) ? $get[19] : null;
                $data_get["Harga_Barang"]       = isset($get[20]) ? $get[20] : null;
                $data_get["Keterangan"]         = isset($get[21]) ? $get[21] : null;
                $data_get["Kode_Lokasi"]        = isset($get[22]) ? $get[22] : null;
                $data_get["Satuan"]             = isset($get[23]) ? $get[23] : null;


                array_push($data, array(
                    "id_user" => $id_user,
                    // "id_kib_f"=>
                    "id_pemilik" => 2,
                    // "id_kode_barang"=>
                    // "id_kode_lokasi"=>
                    "kode_barang" => $data_get["Permendagri_90"],
                    "nama_barang" => $data_get["Nama_Barang_Permendagri_90"],
                    "nama_barang_migrasi" => $data_get["Nama_Simbada_Deskripsi_Barang"],
                    "bangunan" => $data_get["Bangunan"],
                    "kontruksi_bertingkat" => $data_get["Bertingkat"],
                    "kontruksi_beton" => $data_get["Beton"],
                    "luas_m2" => $data_get["Luas_Lantai"],
                    "lokasi_alamat" => $data_get["Letak"],
                    "dokumen_tanggal" => $data_get["Tgl_Sertifikat"],
                    "dokumen_nomor" => $data_get["No_Sertifikat"],
                    "tanggal_mulai" => $data_get["Mulai_Konstruksi"],
                    "status_tanah" => $data_get["Status_Tanah"],
                    "nomor_kode_tanah" => $data_get["No_Kode_Tanah"],
                    "asal_usul" => $data_get["Asal_Usul"],
                    "nilai_kontrak" => $data_get["Harga_Barang"],
                    "keterangan" => $data_get["Keterangan"],
                    "deskripsi" => $data_get["Nama_Simbada_Deskripsi_Barang"],
                    "kode_lokasi" => $data_get["Kode_Lokasi"],
                    "tanggal_transaksi" => $data_get['tgl_transaksi'],
                    "nomor_transaksi" => $data_get['no_transaksi'],
                    "tanggal_pembelian" => $data_get["Thn_Beli"] . "-12-31",
                    "tanggal_perolehan" => $data_get["Thn_Beli"] . "-12-31",
                    "id_sumber_dana" => $data_get['id_sumber_dana'],
                    "id_rekening" => $data_get['id_kode_anggaran'],
                    "validasi" => 1,
                    // "reject_note"=>
                    // "referensi_id"=>
                    "status_barang" => 'kib_' . $id_user,
                    "id_inventaris" => $data_get["Id_Inventaris"],
                    "satuan"        => $data_get["Satuan"],
                    "created_at"    => '2019-12-31 00:00:00',
                    "updated_at"    => '2019-12-31 00:00:00',

                ));
                // die(json_encode($data));
            }
            $numrow++; // Tambah 1 setiap kali looping
        }
        return $data;
    }
}
