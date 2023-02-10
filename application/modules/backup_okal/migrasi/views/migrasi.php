<section class="content">
    <h2 style="margin-top:0px"> <?php //echo $button 
                                ?> Migrasi</h2>
    <form action="<?php echo $action; ?>" method="post" target="_blank" enctype="multipart/form-data">
        <?php //=json_encode($kode)
        ?>
        <div class="form-group">
            <label for="char">JENIS KIB<?php //echo form_error('kode_akun') 
                                        ?></label>
            <div class="row">
                <div class="col-md-4">
                    <select class="form-control" name="kode_jenis" id="kode_jenis" data-role="select2" onchange="" required="required">
                        <option value="">== Silahkan Pilih ==</option>
                        <?php
                        $arr_kode_jenis = array('01', '02', '03', '04', '05', '06');
                        ?>
                        <?php foreach ($jenis as $key => $value) : ?>
                            <?php if (in_array($key, $arr_kode_jenis)) : ?>

                                <option value="<?php echo $key; ?>" <?php echo ($key == $kode_jenis) ? 'selected' : '';
                                                                    ?>><?= $key . "-" . $value['nama']; ?></option>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
        </div>

        <div class="form-group">
            <label for="char">Pilih File <?php //echo form_error('kode_akun') 
                                            ?></label>
            <div class="row">
                <div class="col-md-4">
                    <input type="file" name="file" required>
                </div>
            </div>
        </div>


        <?php /*
        <input type="hidden" name="id_kode_barang" value="<?php //echo $id_kode_barang; 
                                                            ?>" />
        <button type="submit" class="btn btn-primary"><?php //echo $button 
                                                        ?></button>
                                                         */ ?>
        <button type="submit" name="migrasi" class="btn btn-primary"><?php echo "Proses"; ?></button>

        <a href="<?php echo base_url('migrasi') ?>" class="btn btn-default">Batal</a>
    </form>
</section>

<script type="text/javascript">
    $(document).ready(function() {
        $('select[data-role=select2]').select2({
            theme: 'bootstrap',
            width: '100%'
        });

    })
</script>