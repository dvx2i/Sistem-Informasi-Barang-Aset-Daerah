<style media="screen">
  /* th, td { white-space: nowrap; } */
  .platform{
      width: 100%;
      border-radius: 10px;
      margin-top: 10px;
  }

  .btn-edit-cover{
      position: absolute;
      /* right: 4%; */
      /* left: 13%; */
      /* margin-top: 12px; */
      width: 40px;
      /* height: 40px; */
      border-radius: 50%;
      /* bottom: 8px; */
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
    <h2 style="margin-top:0px">Unggah Foto Barang</h2>
    <form action="<?php echo $action; ?>" method="post" id="form_penghapusan">
      <div class="row" style="margin-bottom:10px;">
        <div class="col-md-12">
          <?php foreach ($picture as $key => $value): ?>
            <div class="<?= 'remove_'.$value->id_penghapusan_picture; ?>" style="display: inline-block; margin: 5px;">
              <a  class="btn btn-danger  btn-edit-cover v2 remove_picture" href="#!"  type="button" id_penghapusan_picture='<?=$value->id_penghapusan_picture; ?>'>
                <i class="fa fa-trash fa_floating"></i>
              </a>
              <a id="single_image" href="<?php echo base_url().$value->url ;?>">
                <img src="<?php echo base_url().$value->url ;?>" alt="" style="height: 100px;width: 150px; border:2px solid;" />
              </a>
            </div>
          <?php endforeach; ?>
          <span id="selector_picture"></span>

          <a id="upload_picture" class="group" href="javascript:">
            <img src="<?=base_url('assets/files/images/add-picture-icon-13.jpg') ?>" alt=""style="height:80px;"/>
          </a>
        </div>
      </div>

      <input type="hidden" name="id_penghapusan" value="<?php echo $id_penghapusan; ?>" />
      <!-- <button type="submit" class="btn btn-primary">Simpan</button> -->
      <a href="<?php echo base_url('penghapusan/pengajuan') ?>" class="btn btn-success">Kembali</a>
    </form>

    <?php  echo form_open_multipart(base_url('penghapusan/pengajuan/upload_foto'), array('id' => 'update_picture',)); ?>
      <input type="hidden" name="id_penghapusan" value="<?php echo $id_penghapusan; ?>" />
      <input type="file" name="file" id="upload-picture" class="hide" size="20">
      <input type="submit" name="submit" id="submit" class="hide">
    <?php echo form_close(); ?>


  </section>


<script type="text/javascript">
$('a#upload_picture').on('click', function(e){
  $('#upload-picture').click();
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
      var image =
      "<div class='remove_"+obj.id_penghapusan_picture+"' style='display: inline-block; margin: 5px;'>"+
        "<a  class='btn btn-danger  btn-edit-cover v2 remove_picture' href='#!'  type='button' id_penghapusan_picture='"+obj.id_penghapusan_picture+"'>"+
          "<i class='fa fa-trash fa_floating'></i>"+
        "</a>"+
        "<a id='single_image' href='"+obj.img_src+"'>"+
        "<img src='"+obj.img_src+"' alt='' style='height: 100px;width: 150px; border:2px solid;' />"+
        "</a>"+
      "</div>"  ;
      $(image).insertBefore('#selector_picture');
    }
  });
})

$(document).on('click','a.remove_picture', function(e){
  var id_penghapusan_picture=$(this).attr('id_penghapusan_picture');
  url=base_url+'penghapusan/pengajuan/remove_picture/'+id_penghapusan_picture;
  $.post(url,{id_penghapusan_picture:'',}, function(respon){
    obj_respon      =jQuery.parseJSON(respon);
    $('.remove_'+id_penghapusan_picture).remove();
  });
});


$(document).ready(function(){
  $("a#single_image").fancybox();
  //Date picker
  $('.date_input').datepicker({
      format: "dd MM yyyy",
      autoclose: true,
      language: "id",
      locale: 'id',
      todayHighlight: true,
      endDate:'31-12-'+(new Date).getFullYear(),
  });
})
</script>
