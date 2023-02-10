<style type="text/css">
.tg  {border-collapse:collapse;border-spacing:0;}
.tg td{font-family:Arial, sans-serif;font-size:12px;padding:2px 5px;border-style:solid;border-width:1px;overflow:hidden;word-break:normal;border-color:black;}
.tg th{font-family:Arial, sans-serif;font-size:12px;font-weight:normal;padding:2px 5px;border-style:solid;border-width:1px;overflow:hidden;word-break:normal;border-color:black;}
.tg .tg-s6z2{text-align:center}
.tg .tg-s268{text-align:left;vertical-align:top;}
</style>
<table class="tg">
  <tr>
    <th class="tg-s6z2" rowspan="2">No. Urt</th>
    <th class="tg-s6z2" rowspan="2">Jenis Barang / Nama Barang</th>
    <th class="tg-s6z2" rowspan="2">Bangunan (S, SP, D)</th>
    <th class="tg-s6z2" colspan="2">Konstruksi Bangunan</th>
    <th class="tg-s6z2" rowspan="2">Luas (M2)</th>
    <th class="tg-s6z2" rowspan="2">Letak / Lokasi ALamat</th>
    <th class="tg-s6z2" colspan="2">Dokumen</th>
    <th class="tg-s6z2" rowspan="2">Tgl, Bln, Thn mulai</th>
    <th class="tg-s6z2" rowspan="2">Status Tanah</th>
    <th class="tg-s6z2" rowspan="2">Nomor Kode Tanah</th>
    <th class="tg-s6z2" rowspan="2">Asal -usul Pembiayaan</th>
    <th class="tg-s6z2" rowspan="2">Nilai Kontrak (Ribuan Rp)</th>
    <th class="tg-s6z2" rowspan="2">Ket.</th>
    <?php if (in_array('instansi', $field_name)): ?>
      <th class="tg-s6z2" rowspan="2">Unit Kerja</th>
    <?php endif; ?>

  </tr>
  <tr>
    <td class="tg-s6z2">Bertingkat/ Tidak</td>
    <td class="tg-s6z2">Beton/ Tidak</td>
    <td class="tg-s6z2">Tanggal</td>
    <td class="tg-s6z2">Nomor</td>
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
    <td class="tg-s6z2">13</td>
    <td class="tg-s6z2">14</td>
    <td class="tg-s6z2">15</td>
    <?php if (in_array('instansi', $field_name)): ?>
      <td class="tg-s6z2">16</td>
    <?php endif; ?>
  </tr>
  <?php /*foreach ($kib as $key => $value): ?>
  <tr>
    <td class="tg-s268"><?=$value['no']; ?></td>
    <td class="tg-s268"><?=$value['no']; ?></td>
    <td class="tg-s268"><?=$value['no']; ?></td>
    <td class="tg-s268"><?=$value['no']; ?></td>
    <td class="tg-s268"><?=$value['no']; ?></td>
    <td class="tg-s268"><?=$value['no']; ?></td>
    <td class="tg-s268"><?=$value['no']; ?></td>
    <td class="tg-s268"><?=$value['no']; ?></td>
    <td class="tg-s268"><?=$value['no']; ?></td>
    <td class="tg-s268"><?=$value['no']; ?></td>
    <td class="tg-s268"><?=$value['no']; ?></td>
    <td class="tg-s268"><?=$value['no']; ?></td>
    <td class="tg-s268"><?=$value['no']; ?></td>
    <td class="tg-s268"><?=$value['no']; ?></td>
    <td class="tg-s268"><?=$value['no']; ?></td>
    <td class="tg-s268"><?=$value['no']; ?></td>
  </tr>
  <?php endforeach;*/ ?>
  <?php $total_harga=0;?>
  <?php foreach ($kib as $key => $value1) :?>
  <tr>
    <?php foreach ($field_name as $key => $value2) :?>
      <?php
      if ($field_name[$key] == 'sertifikat_tanggal' or $field_name[$key] == 'gedung_tanggal' or $field_name[$key] == 'dokumen_tanggal' or $field_name[$key] == 'tanggal_mulai' or $field_name[$key] == 'tgl_bln_thn_mulai'): ?>
        <td class="tg-s268"><?= $value1[$value2]?tgl_indo($value1[$value2]):''; ?></td>
      <?php
      elseif ( $value2 == "luas" or $value2 == "harga" or $value2 == "ukuran_cc" or $value2 == "luas_lantai_m2" or $value2 == "luas_m2" or $value2 == "panjang_km" or $value2 == "lebar_m" or $value2 == "luas_m2"
               or $value2 == "hewan_tumbuhan_ukuran" or $value2 == "jumlah"  or $value2 == "nilai_kontrak_rp") : ?>
        <td class="tg-s268"><?= my_format_number($value1[$value2]); ?></td>
      <?php else : ?>
        <td class="tg-s268"><?= $value1[$value2]; ?></td>
      <?php endif; ?>
      <?php
      if ( $value2 == "nilai_kontrak_rp") $total_harga += $value1[$value2];
      ?>      
    <?php endforeach; ?>
  </tr>
  <?php endforeach; ?>
  <tr>
    <td colspan="13" align="right">Total :</td>
    <td class="tg-xldj"><?=my_format_number($total_harga)?></td>
    <?php if (in_array('instansi', $field_name)): ?>
      <td colspan="2"></td>
    <?php else : ?>
      <td colspan="1"></td>
    <?php endif; ?> 
  </tr>

</table>
