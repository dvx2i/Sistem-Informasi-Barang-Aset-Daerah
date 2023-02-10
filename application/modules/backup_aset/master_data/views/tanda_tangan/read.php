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
        <h2 style="margin-top:0px">Tbl_kode_lokasi Read</h2>
        <table class="table">
	    <tr><td>Intra Kom Ekstra Kom</td><td><?php echo $intra_kom_ekstra_kom; ?></td></tr>
	    <tr><td>Propinsi</td><td><?php echo $propinsi; ?></td></tr>
	    <tr><td>Kabupaten</td><td><?php echo $kabupaten; ?></td></tr>
	    <tr><td>Pengguna</td><td><?php echo $pengguna; ?></td></tr>
	    <tr><td>Kuasa Pengguna</td><td><?php echo $kuasa_pengguna; ?></td></tr>
	    <tr><td>Sub Kuasa Pengguna</td><td><?php echo $sub_kuasa_pengguna; ?></td></tr>
	    <tr><td>Instansi</td><td><?php echo $instansi; ?></td></tr>
	    <tr><td>Keterangan</td><td><?php echo $keterangan; ?></td></tr>
	    <tr><td></td><td><a href="<?php echo base_url('kode_lokasi') ?>" class="btn btn-default">Cancel</a></td></tr>
	</table>
        </body>
</html>