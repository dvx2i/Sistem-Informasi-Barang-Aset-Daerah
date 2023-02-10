<!DOCTYPE html>
<html lang="en" dir="ltr">

<head>
  <meta charset="utf-8">
  <title>REKAPITULASI BUKU MUTASI</title>
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
      vertical-align: middle;
      word-wrap: break-word;
    }

    td.tbl-l {
      border: 1px solid #dddddd;
      padding: 8px;
      vertical-align: middle;
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
      font-weight: bold;
      font-size: 12px;
    }

    .bold{
      font-weight: bold;
    }
  </style>
</head>

<body>
  <br>


  <table width='100%' ; style="margin-bottom:10px;">
    <tr>
      <td style="text-align:right;"><?= $des_pemilik; ?></td>
    </tr>
  </table>
  <table class="fonts-12 tg" style="undefined;table-layout: fixed; width: 1170px">
    <!-- <colgroup>
        <col style="width: 20px">
        <col style="width: 50px">
        <col style="width: 34px">
        <col style="width: 34px">
        <col style="width: 34px">
        <col style="width: 34px">
        <col style="width: 34px">
        <col style="width: 34px">
        <col style="width: 34px">
        <col style="width: 34px">
        <col style="width: 34px">
        <col style="width: 34px">
        <col style="width: 34px">
        <col style="width: 34px">
        <col style="width: 34px">
        <col style="width: 34px">
        </colgroup> -->
    <?php
    // $jenis_rekap_desc = null;
    // if ($jenis_rekap == 'objek') {
    // } else if ($jenis_rekap == 'rincian_objek') {
    // }
    ?>

    <tr>
      <th class="tg-s6z2" colspan="1" rowspan="3" width='3%'>No. Urt</th>
      <!-- <th class="tg-s6z2" colspan="1" rowspan="3" width='5%'>JENIS</th> -->
      <th class="tg-s6z2" colspan="1" rowspan="3" width='5%'>OBJEK</th>
      <th class="tg-s6z2" colspan="1" rowspan="3" width='10%'>URAIAN</th>
      <!-- <th class="tg-s6z2" colspan="4">SPESIFIKASI BARANG</th> -->
      <!-- <th class="tg-s6z2" rowspan="3" width="6%">Asal/Cara<br>Perolehan<br>Barang</th> -->
      <!-- <th class="tg-s6z2" rowspan="3" width="6%">Tahun<br>Beli/<br>Perolehan</th> -->
      <!-- <th class="tg-s6z2" rowspan="3" width="6%">Ukuran<br>Barang/<br>konstruksi<br>(P,SP,D)</th> -->
      <!-- <th class="tg-s6z2" rowspan="3" width="4%">Satuan</th> -->
      <!-- <th class="tg-s6z2" rowspan="3" width="4%">Kondisi<br>(B,BR,<br>RB)</th> -->
      <th class="tg-s6z2" colspan="2" rowspan="2" width='10%'>Jumlah(Awal)</th>
      <th class="tg-s6z2" colspan="4" width='0%'>MUTASI/PERUBAHAN</th>
      <th class="tg-s6z2" colspan="2" rowspan="2" width='10%'>JUMLAH(Akhir)</th>
      <th class="tg-s6z2" colspan="1" rowspan="3" width='5%'>Ket.</th>

      <?php /* if (in_array('instansi', $field_name)): ?>
            <th class="tg-s6z2" rowspan="2">Unit Kerja</th>
            <?php endif; */ ?>
    </tr>
    <tr>
      <!-- <td class="tg-s6z2"  width='3%' rowspan="2">No. Urut</td> -->
      <!-- <td class="tg-s6z2" rowspan="2">Kode Barang</td> -->
      <!-- <td class="tg-s6z2" width='5%' rowspan="2">Register</td> -->
      <!-- <td class="tg-s6z2" rowspan="2">Nama/<br>Jenis<br>Barang</td>
            <td class="tg-s6z2" rowspan="2">Merk<br>Type</td>
            <td class="tg-s6z2" rowspan="2" width='9%'>No. Sertifikat<br>No. Pabrik<br>No. Chasis<br>Mesin</td>
            <td class="tg-s6z2" rowspan="2">Bahan</td> -->

      <td class="tg-s6z2" width='10%' colspan="2">Berkurang</td>
      <td class="tg-s6z2" width='10%' colspan="2">Bertambah</td>
    </tr>
    <tr>
      <td class="tg-s6z2" width='3%'>Barang</td>
      <td class="tg-s6z2" width='7%'>Harga</td>
      <td class="tg-s6z2" width='3%'>Jumlah Barang</td>
      <td class="tg-s6z2" width='7%'>Jumlah Harga</td>
      <td class="tg-s6z2" width='3%'>Jumlah Barang</td>
      <td class="tg-s6z2" width='7%'>Jumlah Harga</td>
      <td class="tg-s6z2" width='3%'>Barang</td>
      <td class="tg-s6z2" width='7%'>Harga</td </tr> <tr>
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
      <!-- <td class="tg-s6z2">13</td> -->
      <!-- <td class="tg-s6z2">14</td>
            <td class="tg-s6z2">15</td>
            <td class="tg-s6z2">16</td>
            <td class="tg-s6z2">17</td>
            <td class="tg-s6z2">18</td>
            <td class="tg-s6z2">19</td>
            <td class="tg-s6z2">20</td> -->
    </tr>
    <?php //= json_encode($rekap_mutasi)
      $t_jumlah_awal = 0;
      $t_harga_awal = 0;
      $t_jumlah_min = 0;
      $t_harga_min = 0;
      $t_jumlah_plus = 0;
      $t_harga_plus = 0;
      $t_jumlah_akhir = 0;
      $t_harga_akhir = 0;
    ?>
    <?php foreach ($rekap_mutasi as $key => $value1) : 
      if($value1['status'] == '1') :
        $t_jumlah_awal  += $value1['jumlah_awal'];
        $t_harga_awal   += $value1['harga_awal'];
        $t_jumlah_min   += $value1['jumlah_min'];
        $t_harga_min    += $value1['harga_min'];
        $t_jumlah_plus  += $value1['jumlah_plus'];
        $t_harga_plus   += $value1['harga_plus'];
        $t_jumlah_akhir += $value1['jumlah_akhir'];
        $t_harga_akhir  += $value1['harga_akhir'];
      endif;
      ?>
      <tr>
        <td class="tbl-l"><?= $value1['NO']; ?></td>
        <?php /*<td class="tbl-l"><?= $value1['jenis']; ?></td> */ ?>
        <td class="tbl-l <?= $value1['status'] == '1' ? 'bold' : ''?>"><?= $value1['objek']; ?></td>
        <td class="tbl-l <?= $value1['status'] == '1' ? 'bold' : ''?>"><?= $value1['nama_barang']; ?></td>
        <td class="tbl-r <?= $value1['status'] == '1' ? 'bold' : ''?>"><?= my_format_number($value1['jumlah_awal']); ?></td>
        <td class="tbl-r <?= $value1['status'] == '1' ? 'bold' : ''?>"><?= my_format_number($value1['harga_awal']); ?></td>
        <td class="tbl-r <?= $value1['status'] == '1' ? 'bold' : ''?>"><?= my_format_number($value1['jumlah_min']); ?></td>
        <td class="tbl-r <?= $value1['status'] == '1' ? 'bold' : ''?>"><?= my_format_number($value1['harga_min']); ?></td>
        <td class="tbl-r <?= $value1['status'] == '1' ? 'bold' : ''?>"><?= my_format_number($value1['jumlah_plus']); ?></td>
        <td class="tbl-r <?= $value1['status'] == '1' ? 'bold' : ''?>"><?= my_format_number($value1['harga_plus']); ?></td>
        <td class="tbl-r <?= $value1['status'] == '1' ? 'bold' : ''?>"><?= my_format_number($value1['jumlah_akhir']); ?></td>
        <td class="tbl-r <?= $value1['status'] == '1' ? 'bold' : ''?>"><?= my_format_number($value1['harga_akhir']); ?></td>
        <td class="tbl-l"><?= "" ?></td>
      </tr>

      <?php /*if ($value1['level'] == 1): ?>
            <tr>
              <td colspan="20">
                <b> <?=remove_star($value1['kode_lokasi']).' - '.$value1['instansi']; ?></b>
              </td>
            </tr>
          <?php else: ?>
            <tr>
              <td class="tbl-l"><?=$value1['no']; ?></td>
              <td class="tbl-l"><?=$value1['kode_barang']; ?></td>
              <?php
                $temp_register =  explode(',',$value1['nomor_register']);
                $nomor_register = cek_register_kosong($temp_register); ?>
              <td class="tbl-l"><?=$nomor_register; ?></td>
              <td class="tbl-l"><?=$value1['nama_barang']; ?></td>
              <td class="tbl-l"><?=$value1['merk_type']; ?></td>
              <td class="tbl-l"><?=$value1['sertifikat_nomor']; ?></td>
              <td class="tbl-l"><?=$value1['bahan']; ?></td>
              <td class="tbl-l"><?=$value1['asal_usul']; ?></td>
              <?php $tgl=explode(' ',tgl_indo($value1['tanggal_perolehan'])); ?>
              <td class="tbl-l"><?= $tgl[2]; ?></td>
              <td class="tbl-l"><?=$value1['ukuran_barang']; ?></td>
              <td class="tbl-l"><?=$value1['satuan']; ?></td>
              <td class="tbl-l"><?=$value1['keadaan_barang']; ?></td>

              <?php if ($value1['min_id'] == $value1['min_id_by_kode_barang']): ?>
                <td class="tbl-l"><?=my_format_number($value1['jumlah_awal']); ?></td>
                <td class="tbl-l"><?=my_format_number($value1['harga_awal']); ?></td>
              <?php else: ?>
                <td class="tbl-l"></td>
                <td class="tbl-l"></td>
              <?php endif; ?>

              <?php if ($value1['status_histori'] == 'entri' or $value1['status_histori'] == 'mutasi_in'): ?>
                <td class="tbl-l"></td>
                <td class="tbl-l"></td>
                <td class="tbl-l"><?=my_format_number($value1['jumlah']); ?></td>
                <td class="tbl-l"><?=my_format_number($value1['harga']); ?></td>
              <?php else: ?>
                <td class="tbl-l"><?=my_format_number($value1['jumlah']); ?></td>
                <td class="tbl-l"><?=my_format_number($value1['harga']); ?></td>
                <td class="tbl-l"></td>
                <td class="tbl-l"></td>
              <?php endif; ?>

              <?php if ($value1['max_id'] == $value1['max_id_by_kode_barang']): ?>
                <td class="tbl-l"><?=my_format_number($value1['jumlah_akhir']); ?></td>
                <td class="tbl-l"><?=my_format_number($value1['harga_akhir']); ?></td>
              <?php else: ?>
                <td class="tbl-l"></td>
                <td class="tbl-l"></td>
              <?php endif; ?>

            </tr>
          <?php endif;*/ ?>
    <?php endforeach; ?>
      <tr>
        <td class="tbl-l bold" colspan="3">JUMLAH ASET</td>
        <td class="tbl-r bold"><?= my_format_number($t_jumlah_awal); ?></td>
        <td class="tbl-r bold"><?= my_format_number($t_harga_awal); ?></td>
        <td class="tbl-r bold"><?= my_format_number($t_jumlah_min); ?></td>
        <td class="tbl-r bold"><?= my_format_number($t_harga_min); ?></td>
        <td class="tbl-r bold"><?= my_format_number($t_jumlah_plus); ?></td>
        <td class="tbl-r bold"><?= my_format_number($t_harga_plus); ?></td>
        <td class="tbl-r bold"><?= my_format_number($t_jumlah_akhir); ?></td>
        <td class="tbl-r bold"><?= my_format_number($t_harga_akhir); ?></td>
        <td class="tbl-l bold"><?= "" ?></td>
      </tr>
  </table>




  <?php /*
    <table width='100%'; style="margin-bottom:10px;">
      <tr>
        <td width='20%' >SKPD/Unit Kerja</td>
        <td> : <?= $nama_lokasi; ?>
  </td>
  <td></td>
  </tr>
  <tr>
    <td>KOTA</td>
    <td> : Kota Yogyakarta</td>
    <td width='30%'>Kode SKPD/Unit Kerja : <?= remove_star($kode_lokasi); ?></td>
  </tr>
  <tr>
    <td>PROPINSI</td>
    <td> : D.I Yogyakarta</td>
    <td></td>
  </tr>
  </table>

  <div class="header">
    <h2>REKAPITULASI BUKU MUTASI</h2>
    <h3></h3>
  </div>
  <div class="content">
    <br>
    <table width='100%' ; style="margin-bottom:10px;">
      <tr>
        <td style="text-align:right;"><?= $des_pemilik; ?></td>
      </tr>
    </table>
    <table class="fonts-12">
      <tr>
        <?php foreach ($label as $key => $value) : ?>
          <?php if ($value == 'Nomor Register') : ?>
            <th class="tbl-1"><?= 'No. Reg.'; ?></th>
          <?php else : ?>
            <th class="tbl-1"><?= $value; ?></th>
          <?php endif; ?>
        <?php endforeach; ?>
      </tr>
      <?php foreach ($rekap_mutasi as $key => $value1) : ?>
        <?php
        $tr_font = '';
        if ($jenis_rekap == 'objek') {
          if ($value1['status'] == '1' or $value1['status'] == '2') : $tr_font = 'f-w-b';
          else : $tr_font = '';
          endif;
        } else if ($jenis_rekap == 'rincian_objek') {
          if ($value1['status'] == '1' or $value1['status'] == '2' or $value1['status'] == '3') : $tr_font = 'f-w-b';
          else : $tr_font = '';
          endif;
        }
        ?>
        <tr class="<?= $tr_font; ?>">
          <?php foreach ($field_name as $key => $value2) : ?>
            <?php
            if (($value2 == "total_jumlah" or $value2 == "total_harga") and (is_numeric($value1[$value2]))) :  ?>
              <td class="tbl-r"><?= ($value1[$value2] != '0') ? my_format_number($value1[$value2]) : '0';   ?></td>
            <?php
            else : ?>
              <td class="tbl-1"><?= $value1[$value2]; ?></td>
            <?php endif; ?>
          <?php endforeach; ?>
        </tr>

      <?php endforeach; ?>
    </table>
    */ ?>
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