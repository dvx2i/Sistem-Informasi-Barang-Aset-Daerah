<style media="screen">
  /* th, td { white-space: nowrap; } */
  .platform{
      width: 100%;
      border-radius: 10px;
      margin-top: 10px;
  }
  /* .profile-user-img{
      margin-left: 20%;
  }*/
  .profile-picture{
      /* position: absolute; */
      /* margin-top: -95px; */
      /* height: 200px; */
  }
  .btn-edit-cover{
      position: absolute;
      /* right: 4%; */
      left: 13%;
      /* margin-top: 12px; */
      width: 40px;
      /* height: 40px; */
      border-radius: 50%;
      bottom: 8px;
  }
  .fa_floating{
      font-size: x-large;
      margin-left: -3px;
  }

  .box.box-solid.profile{
      margin-bottom: 10%;
      padding: 0%  6%;
      box-shadow: 0px 0px 0px rgba(0,0,0,0);
  }


  @media only screen and (max-width: 1200px){
      .profile-user-img{
          width: 110px !important;
          margin-top: 35px;
      }
      .btn-edit-cover.v2{
          margin-top: 116px !important;
      }
  }
  /* @media only screen and (max-width: 500px){
      div.row.v1{
          margin-top: 60px;
      }
  } */
</style>

  <section class="content">
    <h2 style="margin-top:0px">Pengajuan Register</h2>
    <form action="<?php echo $action; ?>" method="post" id="form_mutasi">
      <div class="row">
        <div class="col-md-4">
          <div class="form-group">
            <label for="date">Tanggal <?php echo form_error('tanggal') ?></label>
            <input type="text" class="form-control date_input" name="tanggal" id="tanggal" placeholder="Tanggal" value="<?php echo $tanggal; ?>" disabled />
          </div>
        </div>
      </div>

      <div class="form-group">
        <label for="varchar">Lokasi Pengguna Lama <?php echo form_error('kode_lokasi_lama') ?></label>
        <select class="form-control" name="kode_lokasi_lama" id="kode_lokasi_lama" disabled>
          <?php foreach ($lokasi as $key): ?>
            <option  value="<?php echo $key->id_kode_lokasi ?>" <?= ($key->id_kode_lokasi == $id_kode_lokasi_lama ) ? 'selected' : ''?>><?php echo remove_star($key->kode).' - '.$key->instansi ?></option>
          <?php endforeach; ?>
        </select>
      </div>

      <div class="form-group">
        <label for="varchar">Lokasi Pengguna Baru <?php echo form_error('kode_lokasi_baru') ?></label>
        <select class="form-control" name="kode_lokasi_baru" id="kode_lokasi_baru" disabled>
          <?php foreach ($lokasi as $key): ?>
            <option  value="<?php echo $key->id_kode_lokasi ?>" <?= ($key->id_kode_lokasi == $id_kode_lokasi_baru ) ? 'selected' : ''?>><?php echo remove_star($key->kode).' - '.$key->instansi ?></option>
          <?php endforeach; ?>
        </select>
      </div>

      <div class="form-group">
        <label for="varchar">Unggah Berita Acara Serah Terima (BAST) </label>


        <div class="row" >
            <div class="col-md-12 ">
                <div class="profile-picture">
                    <a id="upload_picture" class="btn btn-success  btn-edit-cover v2" href="#!" data-target="#myModal2" type="button" data-toggle="modal" >
                        <i class="fa fa-pencil fa_floating"></i>
                    </a>
                    <?php if ($url_upload_bast) {?>
                        <img class="profile-user-img picture" src="<?php echo base_url().$url_upload_bast ;?>" style="width: 180px;" >
                    <?php } else { ?>
                        <img class="profile-user-img picture" src="<?=base_url('assets/files/images/no-preview-available.jpg') ?>" style="width: 180px;">
                    <?php } ?>
                </div>
            </div><!-- col-md-12 -->
        </div><!-- row -->

      </div>

      <input type="hidden" name="id_mutasi" value="<?php echo $id_mutasi; ?>" />
      <button type="submit" class="btn btn-primary">Ajukan</button>
      <a href="<?php echo base_url('mutasi/pengecekan_barang') ?>" class="btn btn-default">Batalkan</a>
    </form>

    <?php  echo form_open_multipart(base_url('mutasi/pengecekan_barang/upload_bast'), array('id' => 'update_picture',)); ?>
      <input type="hidden" name="id_mutasi" value="<?php echo $id_mutasi; ?>" />
      <input type="file" name="file" id="upload-picture" class="hide" size="20">
      <input type="submit" name="submit" id="submit" class="hide">
    <?php echo form_close(); ?>


  </section>


<script type="text/javascript">
// $(document).ready(function(){
//   console.log('=====');
// })
$('a#upload_picture').on('click', function(e){
  $('#upload-picture').click();
  // NProgress.configure({
  //     parent : "#modal-header-picture",
  // });
  // NProgress.start();
});

$('#upload-picture').change(function(e){
  $('form#update_picture').submit();
})


$('form#update_picture').submit(function(e){
  e.preventDefault();
  var formData = new FormData(this);
  var link = $(this).attr('action');
  $.ajax({
    url: link,
    type: "POST",
    data: formData,
    cache: false,
    contentType : false,
    processData : false,
    success: function(respon) {
      var obj = jQuery.parseJSON(respon);
      $('.profile-user-img').attr('src',obj.img_src);
      location.reload();
    }
  });
})
</script>
