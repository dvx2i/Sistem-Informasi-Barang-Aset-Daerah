<!doctype html>
<html>
<head>
  <title>harviacode.com - codeigniter crud generator</title>
  <link rel="stylesheet" href="<?php echo base_url('assets/bootstrap/css/bootstrap.min.css') ?>"/>
  <style>
  body{
    padding: 15px;
  }
  </style>
</head>
<body>
  <h2 style="margin-top:0px">User Read</h2>
  <table class="table">
    <tr><td>Nama</td><td><?php echo $nama; ?></td></tr>
    <tr><td>Nip</td><td><?php echo $nip; ?></td></tr>
    <tr><td>Kode Lokasi</td><td><?php echo $kode_lokasi; ?></td></tr>
    <tr><td></td><td><a href="<?php echo base_url('User') ?>" class="btn btn-default">Batalkan</a></td></tr>
  </table>
</body>
</html>
