
  <section class="content">
    <h2 style="margin-top:0px">Detail <?= $menu;?> </h2>
    <table class="table">
      <tr><td>Kode Barang</td><td><?php echo $kode_barang; ?></td></tr>
      <tr><td>Nama Barang</td><td><?php echo $nama_barang; ?></td></tr>
      <tr><td>Bangunan</td><td><?php echo $bangunan; ?></td></tr>
      <tr><td>Kontruksi Bertingkat</td><td><?php echo $kontruksi_bertingkat; ?></td></tr>
      <tr><td>Kontruksi Beton</td><td><?php echo $kontruksi_beton; ?></td></tr>
      <tr><td>Luas (M2)</td><td><?php echo $luas_m2; ?></td></tr>
      <tr><td>Lokasi Alamat</td><td><?php echo $lokasi_alamat; ?></td></tr>
      <tr><td>Dokumen Tanggal</td><td><?php echo $dokumen_tanggal; ?></td></tr>
      <tr><td>Dokumen Nomor</td><td><?php echo $dokumen_nomor; ?></td></tr>
      <tr><td>Tanggal Mulai</td><td><?php echo $tanggal_mulai; ?></td></tr>
      <tr><td>Status Tanah</td><td><?php echo $status_tanah; ?></td></tr>
      <tr><td>Nomor Kode Tanah</td><td><?php echo $nomor_kode_tanah; ?></td></tr>
      <tr><td>Asal Usul</td><td><?php echo $asal_usul; ?></td></tr>
      <tr><td>Nilai Kontrak</td><td><?php echo $nilai_kontrak; ?></td></tr>
      <tr><td>Keterangan</td><td><?php echo $keterangan; ?></td></tr>
      <tr><td>Deskripsi</td><td><?php echo $deskripsi; ?></td></tr>
      <tr><td></td><td><a href="<?php echo base_url('kib_f') ?>" class="btn btn-default">Batalkan</a></td></tr>
    </table>
  </section>
