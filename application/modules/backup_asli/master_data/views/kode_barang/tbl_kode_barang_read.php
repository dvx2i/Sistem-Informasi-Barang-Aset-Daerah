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
        <h2 style="margin-top:0px">Tbl_kode_barang Read</h2>
        <table class="table">
	    <tr><td>Kode Barang</td><td><?php echo $kode_barang; ?></td></tr>
	    <tr><td>Uraian</td><td><?php echo $nama_barang; ?></td></tr>
	    <tr><td>Akun</td><td><?php echo $akun; ?></td></tr>
	    <tr><td>Kelompok</td><td><?php echo $rincian_objek; ?></td></tr>
	    <tr><td>Jenis</td><td><?php echo $jenis; ?></td></tr>
	    <tr><td>Objek</td><td><?php echo $objek; ?></td></tr>
	    <tr><td>Rincian Objek</td><td><?php echo $rincian_objek; ?></td></tr>
	    <tr><td>Sub Rincian Objek</td><td><?php echo $sub_rincian_objek; ?></td></tr>
	    <tr><td>Sub Sub Rincian Objek</td><td><?php echo $sub_sub_rincian_objek; ?></td></tr>
	    <tr><td>Umur Ekonomis</td><td><?php echo $umur_ekonomis; ?></td></tr>
	    <tr><td>Nilai Residu</td><td><?php echo $nilai_residu; ?></td></tr>
	    <tr><td>Kelompok Manfaat</td><td><?php echo $rincian_objek_manfaat; ?></td></tr>
	    <tr><td></td><td><a href="<?php echo base_url('kode_barang') ?>" class="btn btn-default">Cancel</a></td></tr>
	</table>
        </body>
</html>