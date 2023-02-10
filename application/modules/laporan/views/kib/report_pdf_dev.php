<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title>KARTU INVENTARIS BARANG (KIB)</title>
  <style media="screen">
    .header{
      text-align: center;
    }
    .footer{
      width: 100%;
    }
    .text-center{
      text-align: center;
    }
    /* .space-ttd{
      height: 75px;
    } */
    .fonts-12{
      font-size: 12px;
    }

    table {
        font-family: arial, sans-serif;
        border-collapse: collapse;
        width: 100%;
    }

    td.tbl-1, th.tbl-1 {
        border: 1px solid #dddddd;
        text-align: left;
        padding: 8px;
    }

    tr:nth-child(even) {
        /* background-color: #dddddd; */
    }
    .page_break { page-break-before: always; }

  </style>
  </head>
  <body>
    <div class="header">
      <h2>KARTU INVENTARIS BARANG (KIB)</h2>
      <h3><?=$jenis['nama']; ?></h3>
    </div>
    <div class="content">
      <br>

      <table width='100%'; style="margin-bottom:10px;">
        <tr>
          <td> No. Kode Lokasi : </td>
          <td></td>
        </tr>
        <tr>
          <td><?=remove_star($kode_lokasi); ?></td>
          <td></td>
        </tr>
        <tr>
          <td><?= $nama_lokasi; ?></td>
          <td style="text-align:right;"><?=$des_pemilik; ?></td>
        </tr>
      </table>
      <?php if ($kode_jenis=='1'): ?>
        <?php $this->load->view('pdf_kib_a'); ?>
      <?php elseif ($kode_jenis=='2'): ?>
        <?php $this->load->view('pdf_kib_b'); ?>
      <?php elseif ($kode_jenis=='3'): ?>
        <?php $this->load->view('pdf_kib_c'); ?>
      <?php elseif ($kode_jenis=='4'): ?>
        <?php $this->load->view('pdf_kib_d'); ?>
      <?php elseif ($kode_jenis=='5'): ?>
        <?php $this->load->view('pdf_kib_e'); ?>
      <?php elseif ($kode_jenis=='6'): ?>
        <?php $this->load->view('pdf_kib_f'); ?>
      <?php endif; ?>


      <br>
      <br>
      <br>

      <?php /*
      </table>
      <tr>
        <?php foreach ($label as $key => $value) :?>
          <?php if ($value == 'Nomor Register') : ?>
            <th class="tbl-1"><?= 'No. Reg.'; ?></th>
          <?php else : ?>
            <th class="tbl-1"><?= $value; ?></th>
        <?php endif; ?>
        <?php endforeach; ?>
      </tr>
      */ ?>
      <?php /*foreach ($kib as $key => $value1) :?>
      <tr>
        <?php foreach ($field_name as $key => $value2) :?>
          <?php
          if ($field_name[$key] == 'sertifikat_tanggal' or $field_name[$key] == 'gedung_tanggal' or $field_name[$key] == 'dokumen_tanggal' or $field_name[$key] == 'tanggal_mulai'): ?>
            <td class="tbl-1"><?= tgl_indo($value1[$value2]); ?></td>
          <?php
          elseif ( $value2 == "luas" or $value2 == "harga" or $value2 == "ukuran_cc" or $value2 == "luas_lantai_m2" or $value2 == "luas_m2" or $value2 == "panjang_km" or $value2 == "lebar_m" or $value2 == "luas_m2"
                   or $value2 == "hewan_tumbuhan_ukuran" or $value2 == "jumlah"  or $value2 == "nilai_kontrak") : ?>
            <td class="tbl-1"><?= my_format_number($value1[$value2]); ?></td>
          <?php else : ?>
            <td class="tbl-1"><?= $value1[$value2]; ?></td>
          <?php endif; ?>
        <?php endforeach; ?>
      </tr>
      <?php endforeach;
    </table>  */ ?>

    </div>
    <div class="page_break">
      <table border="0" class="footer text-center">
        <?php if (isset($tertanda[0]) or isset($tertanda[1]) or isset($tertanda[2])): ?>
          <tr>
            <td colspan="2"><br><br><br></td>
            <td>Yogyakarta, <?=tgl_indo(date('Y-m-d')); ?></td>
          </tr>
          <tr>
            <td>Mengetahui<br><br></td>
            <td colspan="2"></td>
            <!-- <td></td> -->
          </tr>
          <tr>
            <td><?= isset($tertanda[0])?$tertanda[0]->jabatan:''; ?></td>
            <td><?= isset($tertanda[1])?$tertanda[1]->jabatan:''; ?></td>
            <td><?= isset($tertanda[2])?$tertanda[2]->jabatan:''; ?></td>
          </tr>
          <tr class="space-ttd">
            <td colspan="3"><br><br><br><br></td>
          </tr>
          <tr>
            <td><?=isset($tertanda[0])?'( '.$tertanda[0]->nama.' )':'';  ?></td>
            <td><?=isset($tertanda[1])?'( '.$tertanda[1]->nama.' )':'';  ?></td>
            <td><?=isset($tertanda[2])?'( '.$tertanda[2]->nama.' )':'';  ?></td>
          </tr>
          <tr>
            <td><?=isset($tertanda[0])?'NIP. '.$tertanda[0]->nip:'';  ?> </td>
            <td><?=isset($tertanda[1])?'NIP. '.$tertanda[1]->nip:'';  ?> </td>
            <td><?=isset($tertanda[2])?'NIP. '.$tertanda[2]->nip:'';  ?> </td>
          </tr>
        <?php endif; ?>
      </table>

    </div>
  </body>
</html>
