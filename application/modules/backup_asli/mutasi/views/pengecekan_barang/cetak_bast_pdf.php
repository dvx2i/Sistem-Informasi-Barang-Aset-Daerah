<!DOCTYPE html>
<html lang="en" dir="ltr">

<head>
    <meta charset="utf-8">
    <title>BAST</title>
    <style media="screen">
        .header {
            text-align: center;
        }

        .footer {
            width: 100%;
        }

        .text-center {
            text-align: center;
        }

        /* .space-ttd{
      height: 75px;
    } */
        .fonts-12 {
            font-size: 12px;
        }

        table {
            font-family: arial, sans-serif;
            border-collapse: collapse;
            width: 100%;
        }

        td.tbl-1,
        th.tbl-1 {
            border: 1px solid #dddddd;
            text-align: left;
            padding: 8px;
        }

        tr:nth-child(even) {
            /* background-color: #dddddd; */
        }

        .page_break {
            page-break-before: always;
        }
    </style>
</head>

<body>
    <div class="header">
        <h2>BERITA ACARA SERAH TERIMA (BAST)<br>
            BARANG MILIK DAERAH MILIK PEMERINTAH KOTA YOGYAKARTA</h2>

    </div>
    <div class="content">
        <br>

        <table width='100%' ; style="margin-bottom:10px;">
            <tr>
                <td style="text-align: center;"> Nomor : …(diinput pada saat cetak BAST mutasi)… </td>
            </tr>
        </table>
        <p style="font-family: arial, sans-serif;">Pada hari ini, …………… tanggal …………….….. tahun …………………… (00-00-0000) (hari dan tanggal saat usulan validasi)
            bertempat di …(nama lokasi Unit Kerja/UPT/Sekolah PIHAK PERTAMA)..., kami yang
            bertanda tangan di bawah ini:</p>
        <table border="0" width='100%' ; style="margin-bottom:10px; margin-left: 15%;">
            <tr>
                <td style="width: 8%;"> I. </td>
                <td style="width: 20%;"> Nama </td>
                <td style=""> : </td>
            </tr>
            <tr>
                <td style=""> </td>
                <td style=""> NIP </td>
                <td style=""> : </td>
            </tr>
            <tr>
                <td style=""> </td>
                <td style=""> Pangkat/Gol </td>
                <td style=""> : </td>
            </tr>
            <tr>
                <td style=""> </td>
                <td style=""> Jabatan </td>
                <td style=""> : </td>
            </tr>
        </table>
        <table border="0" width='100%' ; style="margin-bottom:10px; margin-left: 15%;">
            <tr>
                <td style="width: 8%;"></td>
                <td>
                    * data kepala Unit Kerja/UPT/Sekolah (sumber data master dari user)
                    Dalam hal ini bertindak untuk dan atas nama …(Unit Kerja/UPT/Sekolah)… selaku Kuasa Pengguna Barang pada …(nama SKPD)…. untuk selanjutnya disebut sebagai PIHAK PERTAMA.
                </td>
            </tr>
        </table>

        <table border="0" width='100%' ; style="margin-bottom:10px; margin-left: 15%;">
            <tr>
                <td style="width: 8%;"> II. </td>
                <td style="width: 20%;"> Nama </td>
                <td style=""> : </td>
            </tr>
            <tr>
                <td style=""> </td>
                <td style=""> NIP </td>
                <td style=""> : </td>
            </tr>
            <tr>
                <td style=""> </td>
                <td style=""> Pangkat/Gol </td>
                <td style=""> : </td>
            </tr>
            <tr>
                <td style=""> </td>
                <td style=""> Jabatan </td>
                <td style=""> : </td>
            </tr>
        </table>
        <table border="0" width='100%' ; style="margin-bottom:10px; margin-left: 15%;">
            <tr>
                <td style="width: 8%;"></td>
                <td>
                    * data kepala Unit Kerja/UPT/Sekolah (sumber data master dari user)
                    Dalam hal ini bertindak untuk dan atas nama …(Unit Kerja/UPT/Sekolah)… selaku Kuasa Pengguna Barang pada …(nama SKPD)…. untuk selanjutnya disebut sebagai PIHAK KEDUA.
                </td>
            </tr>
        </table>
        <table>
            <tr>
                <td style="text-align: left;">
                    Telah melakukan serah terima Barang Milik Daerah yang pengelolaannya dilaksanakan dan menjadi tanggung jawab mutlak PIHAK PERTAMA berdasarkan dengan ketentuan sebagaimana disebutkan dalam pasal-pasal di bawah ini:
                    <br> <br>
                </td>
            </tr>

            <tr>
                <td style="text-align: center;">
                    Pasal 1 <br> <br>
                </td>
            </tr>
            <tr>
                <td style="text-align: left;">
                    PIHAK PERTAMA menyerahkan dan PIHAK KEDUA menerima hak atas Barang Milik Daerah sejumlah …(jumlah barang yang diserahkan)… unit, senilai Rp …(total rupiah yang diserahkan)… (…dalam huruf…), dengan rincian sebagaimana terlampir
                    <br> <br>
                </td>
            </tr>
            <tr>
                <td style="text-align: center;">
                    Pasal 2<br> <br>
                </td>
            </tr>
            <tr>
                <td style="text-align: left;">
                    Dengan ditandatanganinya Berita Acara Serah Terima ini maka tanggung jawab pengelolaan Barang Milik Daerah sebagaimana tersebut dalam Pasal 1 beralih dari PIHAK PERTAMA kepada PIHAK KEDUA
                    <br> <br>
                </td>
            </tr>
            <tr>
                <td style="text-align: center;">
                    Pasal 3<br> <br>
                </td>
            </tr>
            <tr>
                <td style="text-align: left;">
                    Berita Acara Serah Terima Barang Milik Daerah ini dibuat sebagai bukti yang sah dalam rangkap 2 (dua) dan mempunyai kekuatan hukum yang sama bagi PIHAK PERTAMA dan PIHAK KEDUA
                    <br> <br>
                </td>
            </tr>

        </table>




        <?php /*if ($kode_jenis == '1') : ?>
            <?php $this->load->view('pdf_kib_a'); ?>
        <?php elseif ($kode_jenis == '2') : ?>
            <?php $this->load->view('pdf_kib_b'); ?>
        <?php elseif ($kode_jenis == '3') : ?>
            <?php $this->load->view('pdf_kib_c'); ?>
        <?php elseif ($kode_jenis == '4') : ?>
            <?php $this->load->view('pdf_kib_d'); ?>
        <?php elseif ($kode_jenis == '5') : ?>
            <?php $this->load->view('pdf_kib_e'); ?>
        <?php elseif ($kode_jenis == '6') : ?>
            <?php $this->load->view('pdf_kib_f'); ?>
        <?php endif; */ ?>


        <br>
        <br>
        <br>


    </div>
    <div class="page_break">
        <table border="0" class="footer text-center">
            <?php //if (isset($tertanda[0]) or isset($tertanda[1]) or isset($tertanda[2])) : 
            ?>
            <!-- <tr>
                <td colspan="2"><br><br><br></td>
                <td>Yogyakarta, <?= tgl_indo(date('Y-m-d')); ?></td>
            </tr> -->
            <!-- <tr>
                <td>Mengetahui<br><br></td>
                <td colspan="2"></td>
            </tr> -->
            <?php /*
            <tr>
                <td><?= isset($tertanda[0]) ? $tertanda[0]->jabatan : ''; ?></td>
                <td><?= isset($tertanda[1]) ? $tertanda[1]->jabatan : ''; ?></td>
                <td><?= isset($tertanda[2]) ? $tertanda[2]->jabatan : ''; ?></td>
            </tr>
            <tr class="space-ttd">
                <td colspan="3"><br><br><br><br></td>
            </tr>
            <tr>
                <td><?= isset($tertanda[0]) ? '( ' . $tertanda[0]->nama . ' )' : '';  ?></td>
                <td><?= isset($tertanda[1]) ? '( ' . $tertanda[1]->nama . ' )' : '';  ?></td>
                <td><?= isset($tertanda[2]) ? '( ' . $tertanda[2]->nama . ' )' : '';  ?></td>
            </tr>
            <tr>
                <td><?= isset($tertanda[0]) ? 'NIP. ' . $tertanda[0]->nip : '';  ?> </td>
                <td><?= isset($tertanda[1]) ? 'NIP. ' . $tertanda[1]->nip : '';  ?> </td>
                <td><?= isset($tertanda[2]) ? 'NIP. ' . $tertanda[2]->nip : '';  ?> </td>
            </tr>
             */ ?>

            <tr>
                <td>PIHAK KEDUA</td>
                <td></td>
                <td>PIHAK PERTAMA</td>
            </tr>

            <tr>
                <td><br><br><br><br></td>
                <td></td>
                <td></td>
            </tr>
            <tr>
                <td>(......................)<br></td>
                <td></td>
                <td>(......................)</td>
            </tr>
            <tr>
                <td>NIP......................<br></td>
                <td></td>
                <td>NIP......................</td>
            </tr>
            <?php //endif; 
            ?>
        </table>
        <table class="footer text-center">
            <tr>
                <td>Mengetahui,</td>
            </tr>
            <tr>
                <td><br><br><br><br></td>
            </tr>
            <tr>
                <td>(......................)</td>
            </tr>
            <tr>
                <td>NIP......................</td>
            </tr>
        </table>


    </div>
</body>

</html>