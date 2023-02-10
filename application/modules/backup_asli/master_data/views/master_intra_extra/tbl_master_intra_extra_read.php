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
        <h2 style="margin-top:0px">Tbl_master_intra_extra Read</h2>
        <table class="table">
	    <tr><td>Kode Jenis</td><td><?php echo $kode_jenis; ?></td></tr>
	    <tr><td>Value</td><td><?php echo $value; ?></td></tr>
	    <tr><td>Created At</td><td><?php echo $created_at; ?></td></tr>
	    <tr><td>Updated At</td><td><?php echo $updated_at; ?></td></tr>
	    <tr><td></td><td><a href="<?php echo base_url('master_intra_extra') ?>" class="btn btn-default">Cancel</a></td></tr>
	</table>
        </body>
</html>
