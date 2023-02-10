  <section class="content">
    <h2 style="margin-top:0px">Detail <?= $menu;?> </h2>
    <table class="table">
       <tr><td> Alamat</td><td><?php echo $letak_alamat; ?></td></tr>
      <tr><td>Nama Gedung</td><td><?php echo $nama_gedung; ?></td></tr>
       <tr><td>Lantai</td><td><?php echo $lantai; ?></td></tr>
      <tr><td>Nama Ruang</td><td><?php echo $nama_ruang; ?></td></tr>
      <tr><td>Deskripsi</td><td><?php echo $deskripsi; ?></td></tr>
      <tr><td>Status Ruang</td><td><?php if($status_ruang =='1'){ echo "Aktif" ;}else{ echo "Tidak Aktif";} ?></td></tr>
     
      <tr><td>Kode Lokasi</td><td><?php echo $kode_lokasi; ?></td></tr>
      <tr><td></td><td><a href="<?php echo base_url('master_ruang') ?>" class="btn btn-default">Batalkan</a></td></tr>
    </table>
  </section>
