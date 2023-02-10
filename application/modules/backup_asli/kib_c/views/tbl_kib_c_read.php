
  <section class="content">
    <h2 style="margin-top:0px">Detail <?= $menu;?> </h2>
    <table class="table">
      <tr><td>Kode Barang</td><td><?php echo $kode_barang; ?></td></tr>
      <tr><td>Nama Barang</td><td><?php echo $nama_barang; ?></td></tr>
      <tr><td>Nomor Register</td><td><?php echo $nomor_register; ?></td></tr>
      <tr><td>Kondisi Bangunan</td><td><?php echo $kondisi_bangunan; ?></td></tr>
      <tr><td>Bangunan Bertingkat</td><td><?php echo $bangunan_bertingkat; ?></td></tr>
      <tr><td>Bangunan Beton</td><td><?php echo $bangunan_beton; ?></td></tr>
      <tr><td>Luas Lantai(M2)</td><td><?php echo $luas_lantai_m2; ?></td></tr>
      <tr><td>Lokasi Alamat</td><td><?php echo $lokasi_alamat; ?></td></tr>
      <tr><td>Gedung Tanggal</td><td><?php echo $gedung_tanggal; ?></td></tr>
      <tr><td>Gedung Nomor</td><td><?php echo $gedung_nomor; ?></td></tr>
      <tr><td>Luas (M2)</td><td><?php echo $luas_m2; ?></td></tr>
      <tr><td>Status</td><td><?php echo $status; ?></td></tr>
      <tr><td>Nomor Kode Tanah</td><td><?php echo $nomor_kode_tanah; ?></td></tr>
      <tr><td>Asal Usul</td><td><?php echo $asal_usul; ?></td></tr>
      <tr><td>Harga</td><td><?php echo $harga; ?></td></tr>
      <tr><td>Keterangan</td><td><?php echo $keterangan; ?></td></tr>
      <tr><td>Deskripsi</td><td><?php echo $deskripsi; ?></td></tr>
      <tr><td></td><td><a href="<?php echo base_url('kib_c') ?>" class="btn btn-default">Batalkan</a></td></tr>
    </table>
  </section>
