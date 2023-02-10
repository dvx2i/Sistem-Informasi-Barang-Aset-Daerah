<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title>REKAPITULASI BUKU INVENTARIS</title>
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
    .fonts-12{
      font-size: 12px;
    }

    table {
        font-family: arial, sans-serif;
        border-collapse: collapse;
        width: 100%;
    }

    tr.f-w-b{font-weight: bold}

    td.tbl-1, th.tbl-1 {
        border: 1px solid #dddddd;
        text-align: left;
        padding: 8px;
    }

    tr:nth-child(even) {
        /* background-color: #dddddd; */
    }

    td.tbl-r{
        border: 1px solid #dddddd;
        text-align: right;
        padding: 8px;
    }
    .page_break { page-break-before: always; }



  </style>
  </head>
  <body>
    <br>

    <table width='100%'; style="margin-bottom:10px;">
      <tr>
        <td width='20%' >SKPD/Unit Kerja</td>
        <td> : <?= $nama_lokasi; ?></td>
        <td></td>
      </tr>
      <tr>
        <td>KOTA</td>
        <td> : Kota Yogyakarta</td>
        <td  width='30%' >Kode SKPD/Unit Kerja : <?=remove_star($kode_lokasi); ?></td>
      </tr>
      <tr>
        <td>PROPINSI</td>
        <td> : D.I Yogyakarta</td>
        <td></td>
      </tr>
    </table>

    <div class="header">
      <h2>REKAPITULASI BUKU INVENTARIS</h2>
      <h3></h3>
    </div>
    <div class="content">
      <br>
      <table width='100%'; style="margin-bottom:10px;">
        <tr>
          <td style="text-align:right;"><?=$des_pemilik; ?></td>
        </tr>
      </table>
      <table class="fonts-12">
        <tr>
          <?php foreach ($label as $key => $value) :?>
            <?php if ($value == 'Nomor Register') : ?>
              <th class="tbl-1"><?= 'No. Reg.'; ?></th>
            <?php else : ?>
              <th class="tbl-1"><?= $value; ?></th>
          <?php endif; ?>
          <?php endforeach; ?>
        </tr>
        <?php foreach ($rekap_inventaris as $key => $value1) :?>
        <?php
        $tr_font = '';
        if ($jenis_rekap == 'objek') {
          if ($value1['status'] == '1' or $value1['status'] == '2'): $tr_font = 'f-w-b';
          else: $tr_font = '';
          endif;
        }
        else if ($jenis_rekap == 'rincian_objek') {
          if ($value1['status'] == '1' or $value1['status'] == '2' or $value1['status'] == '3' ): $tr_font = 'f-w-b';
          else: $tr_font = '';
          endif;
        }
        ?>
        <tr class="<?=$tr_font; ?>">
          <?php foreach ($field_name as $key => $value2) :?>
            <?php
            if (( $value2 == "total_jumlah" or $value2 == "total_harga" ) and (is_numeric($value1[$value2]) ) ) :  ?>
              <td class="tbl-r"><?=($value1[$value2] != '0') ? my_format_number($value1[$value2]):'0';   ?></td>
            <?php
            else : ?>
              <td class="tbl-1"><?= $value1[$value2]; ?></td>
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
