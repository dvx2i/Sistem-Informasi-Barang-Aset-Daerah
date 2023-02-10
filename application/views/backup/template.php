<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">

  <title><?= isset($title)?'Aset v2 | '.$title:"Aset v2";?></title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">

  <?php $this->load->view('partials/css.php'); ?>
  <?php
    if (isset($css)) {
      foreach ($css as $value) {
        echo "<link rel='stylesheet' href='".base_url($value)."'/>";
      }
    }
  ?>


  <?php $this->load->view('partials/js.php'); ?>
  <?php
    if (isset($js)) {
      foreach ($js as $value) {
        echo "<script src='".base_url($value)."'></script>";
      }
    }
  ?>


</head>
<body class="hold-transition skin-blue sidebar-mini">
<div class="wrapper">

  <?php
    $this->load->view('partials/header.php');
    $this->load->view('partials/sidebar.php');

    $this->load->view('partials/content.php');
    ?>
    
    <!-- Modal -->
    <div class="modal fade" id="loadMe" tabindex="-1" role="dialog" aria-labelledby="loadMeLabel">
      <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content">
          <div class="modal-body text-center">
            <div class="loader"></div>
            <div clas="loader-txt">
              <p>Sedang memproses <br><br><small>silahkan tunggu.. </small></p>
            </div>
          </div>
        </div>
      </div>
    </div>

    <?php

    $this->load->view('partials/footer.php');
    // $this->load->view('partials/sidebar-right.php');
  ?>

</div>
<!-- ./wrapper -->
<script>
  $(document).ajaxStart(function(){
    // $('#loadMe').show();
    $("#loadMe").modal({
      backdrop: "static", //remove ability to close modal with click
      keyboard: false, //remove option to close with keyboard
      show: true //Display loader!
    });
  }).ajaxComplete(function(){
      // $('#loadMe').hide();
    $("#loadMe").modal("hide");
  });
</script>

</body>
</html>
