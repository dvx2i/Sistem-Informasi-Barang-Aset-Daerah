<!-- jQuery 3 -->
<script src="<?= base_url('assets/adminlte/') ?>bower_components/jquery/dist/jquery.min.js"></script>

<!-- Bootstrap 3.3.7 -->
<script src="<?= base_url('assets/adminlte/') ?>bower_components/bootstrap/dist/js/bootstrap.min.js"></script>

<script src="<?= base_url('assets/jquery/jquery-ui-1.12.1.custom/jquery-ui.min.js') ?>"></script>

<!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
<script>
  $.widget.bridge('uibutton', $.ui.button);
  base_url='<?= base_url(); ?>';
</script>


<!-- AdminLTE App -->
<script src="<?= base_url('assets/adminlte/') ?>dist/js/adminlte.min.js"></script>

<script src="<?= base_url('assets/js/custom.js') ?>"></script>

<script type="text/javascript">
  $(document).ready(function(){
    <?php /*get_request_validasi();*/ ?>
  })
  <?php /*
  function get_request_validasi(){
    url=base_url+'global_controller/get_request_validasi/';
    $.post(url,{data:'',}, function(respon){
        obj_respon      =jQuery.parseJSON(respon);
        $('#request_1').removeClass('hidden');
        $('#request_2').removeClass('hidden');
        $('#request_3').removeClass('hidden');
        $('#request_4').removeClass('hidden');
        $('#request_5').removeClass('hidden');
        $('#request_6').removeClass('hidden');

        if (obj_respon[1] == '0') $('#request_1').addClass('hidden');
        if (obj_respon[2] == '0') $('#request_2').addClass('hidden');
        if (obj_respon[3] == '0') $('#request_3').addClass('hidden');
        if (obj_respon[4] == '0') $('#request_4').addClass('hidden');
        if (obj_respon[5] == '0') $('#request_5').addClass('hidden');
        if (obj_respon[6] == '0') $('#request_6').addClass('hidden');

        $('#request_1').text(obj_respon[1]);
        $('#request_2').text(obj_respon[2]);
        $('#request_3').text(obj_respon[3]);
        $('#request_4').text(obj_respon[4]);
        $('#request_5').text(obj_respon[5]);
        $('#request_6').text(obj_respon[6]);
    });

  }*/ ?>
</script>
