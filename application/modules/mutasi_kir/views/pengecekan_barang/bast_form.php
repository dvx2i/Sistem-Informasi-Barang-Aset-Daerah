<style media="screen">
    /* th, td { white-space: nowrap; } */
    .platform {
        width: 100%;
        border-radius: 10px;
        margin-top: 10px;
    }

    .btn-edit-cover {
        position: absolute;
        /* right: 4%; */
        /* left: 13%; */
        /* margin-top: 12px; */
        width: 40px;
        /* height: 40px; */
        border-radius: 50%;
        /* bottom: 8px; */
    }

    .fa_floating {
        font-size: x-large;
        margin-left: -3px;
    }

    .box.box-solid.profile {
        margin-bottom: 10%;
        padding: 0% 6%;
        box-shadow: 0px 0px 0px rgba(0, 0, 0, 0);
    }


    @media only screen and (max-width: 1200px) {
        .profile-user-img {
            width: 110px !important;
            margin-top: 35px;
        }

        /* .btn-edit-cover.v2{
          margin-top: 116px !important;
      } */
    }

    /* @media only screen and (max-width: 500px){
      div.row.v1{
          margin-top: 60px;
      }
  } */
</style>

<section class="content">
    <h2 style="margin-top:0px">Input BAST</h2>
<div class="row">
    <div class="col-md-12">
      <div class="box box-info">
        <div class="box-header with-border">
          <h3 class="box-title">Progress Mutasi</h3>
        </div>

        <div class="fullwidth">
          <div class="container separator">

            <ul class="progress-tracker progress-tracker--text progress-tracker--text-top" style="opacity: 0.9; margin-top: 5px; margin-bottom: 15px;">
              <?php
              // $class = array('1' => 'is-complete', '2' => 'is-active',  '3' => '',);
              foreach ($list_status_mutasi as $key => $value) :
                // echo $key . "==" . (string)$id_status_mutasi->status_proses;
                if ($key < ($id_status_mutasi->status_proses - 1) or ($id_status_mutasi->status_proses == 5)) :        $class =  'is-complete';
                elseif ($key == ($id_status_mutasi->status_proses - 1)) :    $class =  'is-active';
                else : $class =  '';
                endif;

                $url  = '#';
                $url2 = '';
                
                if($key == 1){
                  $url = site_url('mutasi/pengecekan_barang/form_pengecekan/'. encrypt_url($id_status_mutasi->id_mutasi));
                }
                elseif($key == 2){
                  if($id_status_mutasi->status_proses == '3' || $id_status_mutasi->status_proses == '4'){
                  $url = site_url('mutasi/pengecekan_barang/bast/'. encrypt_url($id_status_mutasi->id_mutasi));
                  $url2= site_url('mutasi/pengecekan_barang/form_pengajuan_register/'. encrypt_url($id_status_mutasi->id_mutasi));
                  }
                }
                elseif($key == 4){
                  $url = site_url('mutasi/list_data');
                }
              ?>
                <li class="progress-step <?= $class ?>">
                  <div class="progress-text">
                    <h4 class="progress-title">Step <?= $key + 1; ?></h4>
                   <?= $key <> 2 ?  '<a  href="'.$url.'"><b>'.$value['deskripsi'].'</a></b>' : '<a   href="'.$url.'"><b>Cetak BAST</b></a> / <a   href="'.$url2.'"><b>Upload BAST</b></a>';  ?> 
                  </div>
                  <div class="progress-marker"></div>
                </li>
              <?php
              endforeach; ?>

            </ul>

          </div>
        </div>

      </div>

    </div>
  </div>
    <form action="<?php echo $action; ?>" method="post" id="form_mutasi" autocomplete="off" target="_blank">
        <div class="row">
            <div class="col-md-4">
                <div class="form-group">
                    <label for="date">Tanggal Pengajuan <?php echo form_error('tanggal') ?></label>
                    <input type="text" class="form-control date_input" name="tanggal" id="tanggal" placeholder="Tanggal" value="<?php echo $tanggal; ?>" disabled />
                </div>
            </div>
        </div>

        <div class="form-group">
            <label for="varchar">Lokasi Lama <?php echo form_error('kode_lokasi_lama') ?></label>
            <select class="form-control" name="kode_lokasi_lama" id="kode_lokasi_lama" disabled>
                <?php foreach ($lokasi as $key) : ?>
                    <option value="<?php echo $key->id_kode_lokasi ?>" <?= ($key->id_kode_lokasi == $id_kode_lokasi_lama) ? 'selected' : '' ?>><?php echo remove_star($key->kode) . ' - ' . $key->instansi ?></option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="form-group">
            <label for="varchar">Lokasi Baru <?php echo form_error('kode_lokasi_baru') ?></label>
            <select class="form-control" name="kode_lokasi_baru" id="kode_lokasi_baru" disabled>
                <?php foreach ($lokasi as $key) : ?>
                    <option value="<?php echo $key->id_kode_lokasi ?>" <?= ($key->id_kode_lokasi == $id_kode_lokasi_baru) ? 'selected' : '' ?>><?php echo remove_star($key->kode) . ' - ' . $key->instansi ?></option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="row">
            <div class="col-md-4">
                <div class="form-group">
                    <label for="varchar">Tanggal BAST <?php echo form_error('tanggal_bast') ?></label>
                    <input type="text" id="tanggal_bast" name="tanggal_bast" value="<?= $tanggal_bast ? tgl_indo($tanggal_bast) : ''; ?>" class="form-control date_input" placeholder="Tanggal BAST">
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-4">
                <div class="form-group">
                    <label for="varchar">Nomor BAST <?php echo form_error('nomor_bast') ?></label>
                    <input type="text" name="nomor_bast" value="<?= $nomor_bast; ?>" class="form-control" placeholder="Nomor BAST">
                </div>
            </div>
        </div>
        <?php /*
        <div class="row" style="margin-bottom:10px;">
            <div class="col-md-12">
                <?php foreach ($picture_bast as $key => $value) : ?>
                    <div class="<?= 'remove_' . $value->id_mutasi_picture; ?>" style="display: inline-block; margin: 5px;">
                        <a class="btn btn-danger  btn-edit-cover v2 remove_picture" href="#!" type="button" id_mutasi_picture='<?= $value->id_mutasi_picture; ?>'>
                            <i class="fa fa-trash fa_floating"></i>
                        </a>
                        <a id="single_image" href="<?php echo base_url() . $value->url; ?>">
                            <img src="<?php echo base_url() . $value->url; ?>" alt="" style="height: 100px;width: 150px; border:2px solid;" />
                        </a>
                    </div>
                <?php endforeach; ?>
                <span id="selector_picture"></span>
                <a id="upload_picture" class="group" href="javascript:">
                    <img src="<?= base_url('assets/files/images/add-picture-icon-13.jpg') ?>" alt="" style="height:80px;" />
                </a>
            </div>
        </div>
         */ ?>

        <input type="hidden" name="id_mutasi" value="<?php echo $id_mutasi; ?>" />
        <button type="submit" class="btn btn-primary">Cetak</button>
        <a href="<?php echo base_url('mutasi/pengecekan_barang/toExcel/'.$id_mutasi) ?>" class="btn btn-primary">Cetak Lampiran</a>
    </form>

    <?php echo form_open_multipart(base_url('mutasi/pengecekan_barang/upload_bast'), array('id' => 'update_picture',)); ?>
    <input type="hidden" name="id_mutasi" value="<?php echo $id_mutasi; ?>" />
    <input type="file" name="file" id="upload-picture" class="hide" size="20">
    <input type="submit" name="submit" id="submit" class="hide">
    <?php echo form_close(); ?>


</section>


<script type="text/javascript">
    $('a#upload_picture').on('click', function(e) {
        $('#upload-picture').click();
        // NProgress.configure({
        //     parent : "#modal-header-picture",
        // });
        // NProgress.start();
    });

    $('#upload-picture').change(function(e) {
        $('form#update_picture').submit();
    })


    $('form#update_picture').submit(function(e) {
        e.preventDefault();
        var formData = new FormData(this);
        var link = $(this).attr('action');
        $.ajax({
            url: link,
            type: "POST",
            data: formData,
            cache: false,
            contentType: false,
            processData: false,
            success: function(respon) {
                var obj = jQuery.parseJSON(respon);
                // $('.profile-user-img').attr('src',);
                // location.reload();

                var image =
                    "<div class='remove_" + obj.id_mutasi_picture + "' style='display: inline-block; margin: 5px;'>" +
                    "<a  class='btn btn-danger  btn-edit-cover v2 remove_picture' href='#!'  type='button' id_mutasi_picture='" + obj.id_mutasi_picture + "'>" +
                    "<i class='fa fa-trash fa_floating'></i>" +
                    "</a>" +
                    "<a id='single_image' href='" + obj.img_src + "'>" +
                    "<img src='" + obj.img_src + "' alt='' style='height: 100px;width: 150px; border:2px solid;' />" +
                    "</a>" +
                    "</div>";
                $(image).insertBefore('#selector_picture');
            }
        });
    })

    $(document).on('click', 'a.remove_picture', function(e) {
        var id_mutasi_picture = $(this).attr('id_mutasi_picture');
        url = base_url + 'mutasi/pengecekan_barang/remove_picture/' + id_mutasi_picture;
        $.post(url, {
            id_mutasi_picture: '',
        }, function(respon) {
            obj_respon = jQuery.parseJSON(respon);
            $('.remove_' + id_mutasi_picture).remove();
        });
    });


    $(document).ready(function() {
        $("a#single_image").fancybox();
        //Date picker
        $('.date_input').datepicker({
            format: "dd MM yyyy",
            autoclose: true,
            language: "id",
            locale: 'id',
            todayHighlight: true,
            endDate: '31-12-' + (new Date).getFullYear(),
        });
        

        $(".date_input").datepicker().on('changeDate', function (e) {
            var pickedMonth = new Date(e.date).getMonth() + 1;
            var pickedYear = new Date(e.date).getFullYear();    
            // console.log(pickedMonth);
            // console.log(pickedYear);      
            url = base_url + 'global_controller/cek_stock_opname/';
            $.post(url, { bulan: pickedMonth, tahun: pickedYear  }, function (respon) {
                obj_respon = jQuery.parseJSON(respon);
                // console.log(obj_respon.status);
                if(!obj_respon.status){
                $(".date_input").datepicker('setDate', new Date()); 
                swal({
                    type: 'error',
                    title: '!!!',
                    text: obj_respon.message,
                    // footer: '<a href>Why do I have this issue?</a>'
                });
                return false;
                }
                // getNoRegister();
            });     
        });
    })
</script>