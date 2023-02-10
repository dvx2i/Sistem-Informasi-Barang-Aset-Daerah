<style media="screen">
  .select2-selection__rendered {
    margin-top: 1px !important
  }
</style>

<section class="content">
  <h2 style="margin-top:0px"><?php echo $data['button'] ?> Hak Akses </h2>

  <form action="<?php echo $data['action']; ?>" method="post">
    <?php
    foreach ($list_menu as $key => $value) :
    ?>
      <div class="row">
        <div class="col-md-12">
          <div class="box">
            <div class="box-header with-border">
              <h3 class="box-title"><strong><?php echo $value->menu; ?></strong></h3>
            </div>
            <!--box-header with-border-->
            <div class="box-body">
              <div class="form-group">
                <?php //die(json_encode($list_sub_menu)); 
                ?>
                <?php foreach ($list_sub_menu as $key => $value2) : ?>
                  <?php if (($value2->menu == $value->menu) and ($value2->id_menu != 80)) : // hidden menu yg tidak digunakan
                  ?>
                    <div class="checkbox">
                      <label>
                        <?php /*<input type="checkbox" class="page_access" group="<?php echo $value2->sub_menu; ?>" name="permission[<?php echo $value->slug; ?>][]" value="<?php echo $value2->slug; ?>"><?php echo $value2->title; ?>  */ ?>
                        <input type="hidden" name="list_id_menu[<?= $value2->id_hak_akses; ?>][<?= $value2->id_menu; ?>]" value="<?= $value2->id_menu; ?>">
                        <input type="checkbox" name="checkbox_akses[]" value="<?= $value2->id_menu; ?>" <?= $value2->active == 'y' ? 'checked' : ''; ?>><?php echo $value2->sub_menu; ?>
                      </label>
                    </div>

                  <?php endif; ?>
                <?php endforeach;  ?>

              </div>
              <!--form-group-->
            </div>
            <!--box-body-->
          </div>
          <!--box-->
        </div>
      </div>
      <!--row-->
    <?php endforeach; ?>

    <input type="hidden" name="id_role" value="<?php echo $data['id_role']; ?>" />
    <button type="submit" class="btn btn-primary"><?php echo $data['button'] ?></button>
    <a href="<?php echo base_url('master_data/hak_akses') ?>" class="btn btn-default">Batalkan</a>
  </form>

</section>