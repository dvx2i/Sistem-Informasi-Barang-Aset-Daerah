  <section class="content">
    <h2 style="margin-top:0px">Detail <?= $menu;?> </h2>
    <table class="table">
      <tr><td>Kode Barang</td><td><?php echo $kode_barang; ?></td></tr>
      <tr><td>Nama Barang</td><td><?php echo $nama_barang; ?></td></tr>
      <tr><td>Nomor Register</td><td><?php echo $nomor_register; ?></td></tr>
      <tr><td>Luas</td><td><?php echo $luas; ?></td></tr>
      <tr><td>Tahun Pengadaan</td><td><?php echo $tahun_pengadaan; ?></td></tr>
      <tr><td>Letak Alamat</td><td><?php echo $letak_alamat; ?></td></tr>
      <tr><td>Status Hak</td><td><?php echo $status_hak; ?></td></tr>
      <tr><td>Sertifikat Tanggal</td><td><?php echo $sertifikat_tanggal; ?></td></tr>
      <tr><td>Sertifikat Nomor</td><td><?php echo $sertifikat_nomor; ?></td></tr>
      <tr><td>Penggunaan</td><td><?php echo $penggunaan; ?></td></tr>
      <tr><td>Asal Usul</td><td><?php echo $asal_usul; ?></td></tr>
      <tr><td>Harga</td><td><?php echo $harga; ?></td></tr>
      <tr><td>Keterangan</td><td><?php echo $keterangan; ?></td></tr>
      <tr><td>Deskripsi</td><td><?php echo $deskripsi; ?></td></tr>
      <tr><td>Kode Lokasi</td><td><?php echo $kode_lokasi; ?></td></tr>
      <tr><td></td><td><a href="<?php echo base_url('kib_a') ?>" class="btn btn-default">Batalkan</a></td></tr>
    </table>
  </section>
