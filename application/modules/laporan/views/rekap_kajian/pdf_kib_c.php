<style type="text/css">
.tg  {border-collapse:collapse;border-spacing:0;}
.tg td{font-family:Arial, sans-serif;font-size:12px;padding:1px 5px;border-style:solid;border-width:1px;overflow:hidden;word-break:normal;border-color:black;}
.tg th{font-family:Arial, sans-serif;font-size:12px;font-weight:normal;padding:1px 5px;border-style:solid;border-width:1px;overflow:hidden;word-break:normal;border-color:black;}
.tg .tg-c3ow{border-color:inherit;text-align:center;vertical-align:top}
.tg .tg-uys7{border-color:inherit;text-align:center}
.tg .tg-xldj{border-color:inherit;text-align:left;vertical-align:top;word-wrap:break-word}
</style>
<table class="tg"  style="undefined;table-layout: fixed; width: 1170px">
  <tr>
    <th class="tg-uys7" rowspan="2" style="width: 2%">No Urt.</th>
    <th class="tg-uys7" rowspan="2">Jenis Barang/ Nama Barang</th>
    <th class="tg-uys7" colspan="2">Nomor</th>
    <th class="tg-uys7" rowspan="2">Kondisi Bangunan (B, KB, RB)</th>
    <th class="tg-uys7" colspan="2">Konstruksi Bangunan</th>
    <th class="tg-uys7" rowspan="2">Luas Lantai (M2)</th>
    <th class="tg-uys7" rowspan="2">Letak/ Lokasi Alamat</th>
    <th class="tg-uys7" colspan="2">Dokumen Gedung</th>
    <th class="tg-uys7" rowspan="2">Luas (M2)</th>
    <th class="tg-uys7" rowspan="2">Status</th>
    <th class="tg-uys7" rowspan="2">Nomor Kode Tanah</th>
    <th class="tg-uys7" rowspan="2">Asal Usul</th>
    <th class="tg-uys7" rowspan="2">Harga</th>
    <th class="tg-uys7" rowspan="2">Ket.</th>
    <?php if (in_array('instansi', $field_name)): ?>
      <th class="tg-uys7" rowspan="2">Unit Kerja</th>
    <?php endif; ?>

  </tr>
  <tr>
    <td class="tg-uys7">Kode Barang</td>
    <td class="tg-uys7">Register</td>
    <td class="tg-uys7">Bertingkat/Tidak</td>
    <td class="tg-uys7">Beton/Tidak</td>
    <td class="tg-uys7">Tanggal</td>
    <td class="tg-uys7">Nomor</td>
  </tr>
  <tr>
    <td class="tg-uys7">1</td>
    <td class="tg-uys7">2</td>
    <td class="tg-uys7">3</td>
    <td class="tg-uys7">4</td>
    <td class="tg-uys7">5</td>
    <td class="tg-uys7">6</td>
    <td class="tg-uys7">7</td>
    <td class="tg-uys7">8</td>
    <td class="tg-uys7">9</td>
    <td class="tg-uys7">10</td>
    <td class="tg-uys7">11</td>
    <td class="tg-uys7">12</td>
    <td class="tg-uys7">13</td>
    <td class="tg-uys7">14</td>
    <td class="tg-uys7">15</td>
    <td class="tg-uys7">16</td>
    <td class="tg-uys7">17</td>
    <?php if (in_array('instansi', $field_name)): ?>
      <td class="tg-uys7">18</td>
    <?php endif; ?>

  </tr>
  <?php /*foreach ($kib as $key => $value): ?>
  <tr>
    <td class="tg-uys7"><?=$value['no']; ?></td>
    <td class="tg-uys7"><?=$value['nama_barang']; ?></td>
    <td class="tg-uys7"><?=$value['kode_barang']; ?></td>
    <td class="tg-uys7"><?=$value['nomor_register']; ?></td>
    <td class="tg-uys7"><?=$value['no']; ?></td>
    <td class="tg-uys7"><?=$value['no']; ?></td>
    <td class="tg-uys7"><?=$value['no']; ?></td>
    <td class="tg-uys7"><?=$value['no']; ?></td>
    <td class="tg-uys7"><?=$value['no']; ?></td>
    <td class="tg-uys7"><?=$value['no']; ?></td>
    <td class="tg-uys7"><?=$value['no']; ?></td>
    <td class="tg-uys7"><?=$value['no']; ?></td>
    <td class="tg-uys7"><?=$value['no']; ?></td>
    <td class="tg-uys7"><?=$value['no']; ?></td>
    <td class="tg-uys7"><?=$value['no']; ?></td>
    <td class="tg-c3ow"><?=$value['no']; ?></td>
    <td class="tg-c3ow"><?=$value['no']; ?></td>
    <td class="tg-c3ow"><?=$value['no']; ?></td>
  </tr>
  <?php endforeach;*/ ?>
  <?php $total_harga=0;?>
  <?php foreach ($kib as $key => $value1) :?>
  <tr>
    <?php foreach ($field_name as $key => $value2) :?>
      <?php
      if ($field_name[$key] == 'sertifikat_tanggal' or $field_name[$key] == 'gedung_tanggal' or $field_name[$key] == 'dokumen_tanggal' or $field_name[$key] == 'tanggal_mulai'): ?>
        <td class="tg-xldj"><?= tgl_indo($value1[$value2]); ?></td>
      <?php
      elseif ( $value2 == "luas" or $value2 == "harga" or $value2 == "ukuran_cc" or $value2 == "luas_lantai_m2" or $value2 == "luas_m2" or $value2 == "panjang_km" or $value2 == "lebar_m" or $value2 == "luas_m2"
               or $value2 == "hewan_tumbuhan_ukuran" or $value2 == "jumlah"  or $value2 == "nilai_kontrak") : ?>
        <td class="tg-xldj"><?= my_format_number($value1[$value2]); ?></td>
      <?php else : ?>
        <td class="tg-xldj"><?= $value1[$value2]; ?></td>
      <?php endif; ?>
      <?php
      if ( $value2 == "harga") $total_harga += $value1[$value2];
      ?>      
    <?php endforeach; ?>
  </tr>
  <?php endforeach; ?>
  <tr>
    <td colspan="15" align="right">Total :</td>
    <td class="tg-xldj"><?=my_format_number($total_harga)?></td>
    <?php if (in_array('instansi', $field_name)): ?>
      <td colspan="2"></td>
    <?php else : ?>
      <td colspan="1"></td>
    <?php endif; ?> 
  </tr>

</table>
