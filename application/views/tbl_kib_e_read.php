<link rel="stylesheet" href="<?= base_url('assets/adminlte/') ?>bower_components/bootstrap/dist/css/bootstrap.min.css">

<link rel="stylesheet" href="<?= base_url('assets/jquery/jquery-ui-1.12.1.custom/jquery-ui.min.css'); ?>">

<!-- Font Awesome -->
<link rel="stylesheet" href="<?= base_url('assets/adminlte/') ?>bower_components/font-awesome/css/font-awesome.min.css">
<!-- Ionicons -->
<link rel="stylesheet" href="<?= base_url('assets/adminlte/') ?>bower_components/Ionicons/css/ionicons.min.css">
<!-- Theme style -->
<link rel="stylesheet" href="<?= base_url('assets/adminlte/') ?>dist/css/AdminLTE.min.css">
<!-- AdminLTE Skins. Choose a skin from the css/skins
     folder instead of downloading all of them to reduce the load. -->

<link rel="stylesheet" href="<?= base_url('assets/adminlte/') ?>dist/css/skins/_all-skins.min.css">

<!-- Google Font -->
<!-- <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic"> -->
<link rel="stylesheet" href="<?= base_url('assets/css/google_font.css') ?>">

<html>
<div style="background-color: #ecf0f5;">
  <div class="col-md-6 col-md-offset-3">
    <div class="box box-solid">
      <div class="box-header with-border">
        <!-- <i class="fa fa-text-witdh"></i> -->
        <h3 class="box-title">Detail Barang</h3>
      </div>

      <div class="box-body">
        <table class="table table-striped">
          <tbody>
            <tr>
              <td>Instansi</td>
              <td><b><?= $nama_lokasi; ?><b></td>
            </tr>
            <tr>
              <td>ID KIB</td>
              <td><b><?= $id_kib_e; ?><b></td>
            </tr>
            <tr>
              <td>No Register</td>
              <td><b><?= $nomor_register; ?><b></td>
            </tr>
            <tr>
              <td>Kode Barang</td>
              <td><b><?= $kode_barang; ?><b></td>
            </tr>
            <tr>
              <td>Nama Barang</td>
              <td><b><?= $nama_barang; ?><b></td>
            </tr>
            <tr>
              <td>Sumber Dana</td>
              <td><b><?= $nama_sumber_dana; ?><b></td>
            </tr>
            <tr>
              <td>Judul Pencipta</td>
              <td><b><?= $judul_pencipta; ?><b></td>
            </tr>
            <tr>
              <td>Tanggal Perolehan</td>
              <td><b><?= $tanggal_perolehan; ?><b></td>
            </tr>
            <tr>
              <td>Harga</td>
              <td><b><?= "Rp " . number_format($harga,2,',','.'); ?><b></td>
            </tr>
            <tr>
              <td>Kondisi</td>
              <td><b><?= $kondisi; ?><b></td>
            </tr>
            <tr>
              <td>Deskripsi</td>
              <td><b><?= $deskripsi; ?><b></td>
            </tr>
          </tbody>
        </table>
      </div>

    </div>

  </div>



</html>