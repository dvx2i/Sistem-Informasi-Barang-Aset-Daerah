<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <?php if (isset($breadcrumb)): ?>
  <section class="content-header" style="margin-top:-15px;">
    <h1>
      <!-- Dashboard -->
      <?php /*<small>Control panel</small>*/ ?>
    </h1>
    <ol class="breadcrumb">

        <?php foreach ($breadcrumb as $key => $value): ?>
          <?php if ($value['li_class'] != 'active'): ?>
            <li class="">
              <a href="<?=$value['url']; ?>">
                <i class="<?=$value['icon'];  ?>"></i> <?=$value['label']; ?>
              </a>
            </li>
          <?php else: ?>
            <li class="<?=$value['li_class']; ?>"><?=$value['label']; ?></li>
          <?php endif; ?>
        <?php endforeach; ?>
    </ol>
  </section>
<?php endif; ?>


  <!-- Main content -->
  <section class="content">
    <?=$content; ?>
  </section>
  <!-- /.content -->
</div>
<!-- /.content-wrapper -->
