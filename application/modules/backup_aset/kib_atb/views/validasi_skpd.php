<section class="content">
  <div class="row" style="margin-bottom: 10px">
    <div class="col-md-12">
      <h2 class='text-success' style="margin-top:0px">Pengelompokan Per SKPD <?= $menu['nama'] ?></h2>
    </div>
  </div>
  <table class="table table-bordered table-striped" id="mytable">
    <thead>
      <tr>
            <th>Nama SKPD</th>
        <th>Nama SKPD</th>
        <th>Nama Lokasi</th>
        <th>Jumlah</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($skpd as $key => $value) : ?>
        <tr>
          <td><?= $value->pengguna ?></td>
          <td><?= $value->status == '1' ? $value->instansi : ''; ?></td>
          <td><?= $value->status == '2' ? $value->instansi : ''; ?></td>
          <?php if ($value->status == '2') : ?>
            <td><a href="<?= base_url('kib_atb/validasi/detail/' . $value->id_pengguna); ?>" class="btn btn-warning" style="width:75%"><?= $value->jumlah; ?></a></td>
          <?php else : ?>
            <td></td>
          <?php endif; ?>

        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
	<?php 
	// echo $this->pagination->create_links();
	?>
</section>

<input type="hidden" id='kode_jenis' value="<?= $kode_jenis; ?>">

<script>
  $(document).ready(function() {
    $('#mytable').DataTable({
      'columnDefs': [
    { 'orderData':[0], 'targets': [1] },
    {
        'targets': [0],
        'visible': false,
        'searchable': false
    },
],
    });
} );
</script>