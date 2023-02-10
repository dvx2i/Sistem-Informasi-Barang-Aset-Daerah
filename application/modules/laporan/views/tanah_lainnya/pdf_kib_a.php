<style media="screen">
  .tg {
    border-collapse: collapse;
    border-spacing: 0;
  }

  .tg td {
    font-family: Arial, sans-serif;
    font-size: 12px;
    padding: 0px 3px;
    border-style: solid;
    border-width: 1px;
    overflow: hidden;
    word-break: normal;
    border-color: black;
  }

  .tg th {
    font-family: Arial, sans-serif;
    font-size: 12px;
    font-weight: normal;
    padding: 0px 3px;
    border-style: solid;
    border-width: 1px;
    overflow: hidden;
    word-break: normal;
    border-color: black;
  }

  .tg .tg-uys7 {
    border-color: inherit;
    text-align: center
  }

  .tg .tg-0lax {
    text-align: left;
    vertical-align: top
  }
</style>
<table border="1" class="tg">
  <tr>
    <th class="tg-uys7" rowspan="4">No.</th>
    <th class="tg-uys7" rowspan="4">Jenis barang/Nama barang</th>
    <th class="tg-uys7" colspan="2" rowspan="2">Nomor</th>
    <th class="tg-uys7" rowspan="4">Luas (M2)</th>
    <th class="tg-uys7" rowspan="4">Tahun Pengadaan</th>
    <th class="tg-uys7" rowspan="4">Letak/alamat</th>
    <th class="tg-uys7" colspan="3" rowspan="2">Status Tanah</th>
    <th class="tg-uys7" rowspan="4">Penggunaan</th>
    <th class="tg-uys7" rowspan="4">Asal usul</th>
    <th class="tg-uys7" rowspan="4">Harga(Ribuan Rp.)</th>
    <th class="tg-uys7" rowspan="4">Keterangan</th>
    <?php if (in_array('instansi', $field_name)) : ?>
      <th class="tg-uys7" rowspan="4">Unit Kerja</th>
    <?php endif; ?>
  </tr>
  <tr>
  </tr>
  <tr>
    <td class="tg-uys7" rowspan="2">Kode barang</td>
    <td class="tg-uys7" rowspan="2">Register</td>
    <td class="tg-uys7" rowspan="2">Hak</td>
    <td class="tg-uys7" colspan="2">Sertifikat</td>
  </tr>
  <tr>
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
    <?php if (in_array('instansi', $field_name)) : ?>
      <td class="tg-uys7">15</td>
    <?php endif; ?>
  </tr>
  <?php /*foreach ($kib as $key => $value): ?>
    <tr>
      <td class="tg-0lax"><?=$value['no']; ?></td>
      <td class="tg-0lax"><?=$value['nama_barang']; ?></td>
      <td class="tg-0lax"><?=$value['kode_barang']; ?></td>
      <td class="tg-0lax"><?=$value['nomor_register']; ?></td>
      <td class="tg-0lax"><?=my_format_number($value['luas']); ?></td>
      <td class="tg-0lax"><?=$value['tahun_pengadaan']; ?></td>
      <td class="tg-0lax"><?=$value['letak_alamat']; ?></td>
      <td class="tg-0lax"><?=$value['status_hak']; ?></td>
      <td class="tg-0lax"><?=tgl_indo($value['sertifikat_tanggal']); ?></td>
      <td class="tg-0lax"><?=$value['sertifikat_nomor']; ?></td>
      <td class="tg-0lax"><?=$value['penggunaan']; ?></td>
      <td class="tg-0lax"><?=$value['asal_usul']; ?></td>
      <td class="tg-0lax"><?=my_format_number($value['harga']); ?></td>
      <td class="tg-0lax"><?=$value['keterangan']; ?></td>
      <?php if (isset($value['instansi'])): ?>
      <td class="tg-0lax"><?=$value['instansi']; ?></td>
      <?php endif; ?>
    </tr>
  <?php endforeach; */ ?>
  <?php $total_harga = 0; ?>
  <?php foreach ($kib as $key => $value1) : ?>
    <tr>
      <?php foreach ($field_name as $key => $value2) : ?>
        <?php
        if ($field_name[$key] == 'sertifikat_tanggal' or $field_name[$key] == 'gedung_tanggal' or $field_name[$key] == 'dokumen_tanggal' or $field_name[$key] == 'tanggal_mulai') : ?>
          <td class="tg-0lax"><?= tgl_indo($value1[$value2]); ?></td>
        <?php
        elseif (
          $value2 == "luas" or $value2 == "harga" or $value2 == "ukuran_cc" or $value2 == "luas_lantai_m2" or $value2 == "luas_m2" or $value2 == "panjang_km" or $value2 == "lebar_m" or $value2 == "luas_m2"
          or $value2 == "hewan_tumbuhan_ukuran" or $value2 == "jumlah"  or $value2 == "nilai_kontrak"
        ) : ?>
          <td class="tg-0lax"><?= my_format_number($value1[$value2]); ?></td>
        <?php else : ?>
          <td class="tg-0lax"><?= $value1[$value2]; ?></td>
        <?php endif; ?>

        <?php
        if ($value2 == "harga") $total_harga += $value1[$value2];
        ?>

      <?php endforeach; ?>
    </tr>
  <?php endforeach; ?>
  <tr>
    <td colspan="12" align="right">Total :</td>
    <td class="tg-xldj"><?= my_format_number($total_harga) ?></td>
    <?php if (in_array('instansi', $field_name)) : ?>
      <td colspan="2"></td>
    <?php else : ?>
      <td colspan="1"></td>
    <?php endif; ?>
  </tr>




</table>