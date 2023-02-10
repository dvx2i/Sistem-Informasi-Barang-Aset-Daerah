
  <section class="content">
    <h2 style="margin-top:0px">Detail <?= $menu;?> </h2>
    <table class="table">
      <tr><td>Kode Barang</td><td><?php echo $kode_barang; ?></td></tr>
      <tr><td>Nama Barang</td><td><?php echo $nama_barang; ?></td></tr>
      <tr><td>Nomor Register</td><td><?php echo $nomor_register; ?></td></tr>
      <tr><td>Konstruksi</td><td><?php echo $konstruksi; ?></td></tr>
      <tr><td>Panjang (MM)</td><td><?php echo $panjang_km; ?></td></tr>
      <tr><td>Lebar (M)</td><td><?php echo $lebar_m; ?></td></tr>
      <tr><td>Luas M2</td><td><?php echo $luas_m2; ?></td></tr>
      <tr><td>Letak Lokasi</td><td><?php echo $letak_lokasi; ?></td></tr>
      <tr><td>Dokumen Tanggal</td><td><?php echo $dokumen_tanggal; ?></td></tr>
      <tr><td>Dokumen Nomor</td><td><?php echo $dokumen_nomor; ?></td></tr>
      <tr><td>Status Tanah</td><td><?php echo $status_tanah; ?></td></tr>
      <tr><td>Kode Tanah</td><td><?php echo $kode_tanah; ?></td></tr>
      <tr><td>Asal Usul</td><td><?php echo $asal_usul; ?></td></tr>
      <tr><td>Harga</td><td><?php echo $harga; ?></td></tr>
      <tr><td>Kondisi</td><td><?php echo $kondisi; ?></td></tr>
      <tr><td>Keterangan</td><td><?php echo $keterangan; ?></td></tr>
      <tr><td>Deskripsi</td><td><?php echo $deskripsi; ?></td></tr>
      <tr><td></td><td><a href="<?php echo base_url('kib_d') ?>" class="btn btn-default">Batalkan</a></td></tr>
    </table>
  </section>
