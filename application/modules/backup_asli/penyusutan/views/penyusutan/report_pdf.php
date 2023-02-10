<!DOCTYPE html>
<html lang="en" dir="ltr">

<head>
  <meta charset="utf-8">
  <title>DAFTAR PENYUSUTAN BARANG <?= tgl_indo(date('Y-m-d')) ?></title>
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
      font-size: 10px;
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
      word-wrap: break-word;

    }

    tr:nth-child(even) {
      /* background-color: #dddddd; */
    }

    td.tbl-r {
      border: 1px solid #dddddd;
      text-align: right;
      padding: 8px;
      vertical-align: top;
      /* word-wrap:break-word; */
    }

    td.tbl-l {
      border: 1px solid #dddddd;
      padding: 8px;
      vertical-align: top;
      word-wrap: break-word;
    }

    .page_break {
      page-break-before: always;
    }

    .tg {
      border-collapse: collapse;
      border-spacing: 0;
    }

    .tg td {
      font-family: Arial, sans-serif;
      font-size: 14px;
      padding: 3px 6px;
      border-style: solid;
      border-width: 1px;
      overflow: hidden;
      word-break: normal;
      border-color: black;
    }

    .tg th {
      font-family: Arial, sans-serif;
      font-size: 14px;
      font-weight: normal;
      padding: 3px 6px;
      border-style: solid;
      border-width: 1px;
      overflow: hidden;
      word-break: normal;
      border-color: black;
    }

    .tg .tg-s6z2 {
      text-align: center;
      font-weight: bold
    }
  </style>
</head>

<body>
  <div class="header">
    <h2>DAFTAR PENYUSUTAN BARANG</h2>
    <h3></h3>
  </div>
  <div class="content">
    <br>
    <table width='100%' ; style="margin-bottom:10px;">
      <tr>
        <td width='20%'>SKPD</td>
        <td> : <?= $nama_lokasi; ?></td>
        <td></td>
      </tr>
      <tr>
        <td>KABUPATEN/KOTA</td>
        <td> : Kota Yogyakarta</td>
        <td width='30%'>NO. KODE LOKASI : <?= remove_star($kode_lokasi); ?></td>
      </tr>
      <tr>
        <td>PROVINSI</td>
        <td> : D.I Yogyakarta</td>
        </td>
        <td></td>
      </tr>
    </table>

    <br>
    <table width='100%' ; style="margin-bottom:10px;">
      <tr>
        <td style="text-align:right;"><?= $des_pemilik; ?></td>
      </tr>
    </table>
    <table class="fonts-12 tg" style="table-layout: fixed; width: 1170px">
      <tr>
        <th class="tg-s6z2" colspan="3">Nomor</th>
        <th class="tg-s6z2" colspan="2">Spesifikasi Barang</th>
        <th class="tg-s6z2" rowspan="2">Tahun Pembelian/<br>Pengadaan</th>
        <th class="tg-s6z2" rowspan="2">Umur Barang</th>
        <th class="tg-s6z2" rowspan="2" width='5%'>Umur Ekonomis</th>
        <?php /*<th class="tg-s6z2" rowspan="2" width='5%'>Jumlah Barang</th> */ ?>
        <th class="tg-s6z2" rowspan="2">Nilai Perolehan</th>
        <th class="tg-s6z2" rowspan="2" width='7.5%'>Nilai Penyusutan</th>
        <th class="tg-s6z2" rowspan="2" width='7.5%'>Akumulasi Penyusutan</th>
        <th class="tg-s6z2" rowspan="2" width='7%'>Nilai Buku</th>
        <?php if (in_array('instansi', $field_name)) : ?>
          <th class="tg-s6z2" rowspan="2" width='6%'>Unit Kerja</th>
        <?php endif; ?>
      </tr>
      <tr>
        <td class="tg-s6z2" width='3%'>No Urt</td>
        <td class="tg-s6z2">Kode Barang</td>
        <td class="tg-s6z2" width='5%'>Register</td>
        <td class="tg-s6z2">Jenis/ Nama Barang</td>
        <td class="tg-s6z2">Merk Type</td>
      </tr>
      <tr>
        <td class="tg-s6z2">1</td>
        <td class="tg-s6z2">2</td>
        <td class="tg-s6z2">3</td>
        <td class="tg-s6z2">4</td>
        <td class="tg-s6z2">5</td>
        <td class="tg-s6z2">6</td>
        <td class="tg-s6z2">7</td>
        <td class="tg-s6z2">8</td>
        <td class="tg-s6z2">9</td>
        <td class="tg-s6z2">10</td>
        <td class="tg-s6z2">11</td>
        <td class="tg-s6z2">12</td>
        <?php /*<td class="tg-s6z2">13</td> */ ?>
        <?php if (in_array('instansi', $field_name)) : ?>
          <td class="tg-s6z2">14</td>
        <?php endif; ?>

      </tr>

      <?php foreach ($penyusutan as $key => $value1) : ?>
        <tr>
          <?php foreach ($field_name as $key => $value2) : ?>
            <?php
            if (($value2 == 'tanggal_pembelian') and ($value1[$value2] != '')) : ?>
              <td class="tbl-l"><?= tgl_indo($value1[$value2]); ?></td>

            <?php
            elseif (($value2 == 'umur_bulan' or $value2 == 'umur_ekonomis' or $value2 == 'jumlah_barang' or $value2 == 'nilai_perolehan' or $value2 == 'nilai_penyusutan' or
              $value2 == 'akumulasi_penyusutan' or $value2 == 'nilai_buku') and (is_numeric($value1[$value2]))) :  ?>
              <td class="tbl-r"><?= ($value1[$value2] != '0') ? my_format_number($value1[$value2]) : '0';   ?></td>
            <?php
            elseif ($value2 == "nomor_register") :
              $temp_register =  explode(',', $value1[$value2]);
              $nomor_register = cek_register_kosong($temp_register); ?>
              <td class="tbl-l"><?= $nomor_register ?></td>
            <?php
            else : ?>
              <td class="tbl-l"><?= $value1[$value2]; ?></td>
            <?php endif; ?>
          <?php endforeach; ?>
        </tr>
      <?php endforeach; ?>
    </table>
    <br>
    <br>
    <br>

  </div>
  <div class="page_break">

    <table border="0" class="footer text-center">
      <?php if (isset($tertanda[0]) or isset($tertanda[1]) or isset($tertanda[2])) : ?>
        <tr>
          <td colspan="2"><br><br><br></td>
          <td>Yogyakarta, <?= tgl_indo(date('Y-m-d')); ?></td>
        </tr>
        <tr>
          <td>Mengetahui<br><br></td>
          <td colspan="2"></td>
          <!-- <td></td> -->
        </tr>
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
      <?php endif; ?>
    </table>

  </div>
</body>

</html>