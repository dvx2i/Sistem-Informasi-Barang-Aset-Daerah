<?php
$session = $this->session->userdata('session');
$menu = $this->session->userdata('menu');
// die(json_encode($menu));
?>

<aside class="main-sidebar">
  <section class="sidebar">
    <div class="user-panel">
      <div class="pull-left image">
        <img src="<?= base_url('assets/adminlte/') ?>dist/img/avatar.png" class="img-circle" alt="User Image">
      </div>
      <div class="pull-left info">
        <p><?= $this->session->userdata('session')->nama ?></p>
        <i class="fa fa-circle text-success"></i> Online
      </div>
    </div>
    <ul class="sidebar-menu" data-widget="tree" style=" white-space:normal; border: 1px solid #00aaea70; margin-right: 4px;">
      <li>
        <a href="#">
          <i class="fa  fa-location-arrow text-blue" style=" font-size:20px;"></i>
          <span style="text-decoration: underline;"><?= remove_star($this->session->userdata('session')->kode_lokasi); ?></span>
        </a>
      </li>
      <li><a href="#" style="padding-top:0px;"> <span><?= $this->session->userdata('session')->nama_lokasi; ?></span></a></li>
    </ul>

    <style>
      .dot {
        height: 10px;
        width: 10px;
        background-color: #bbb;
        border-radius: 50%;
        display: inline-block;
      }
    </style>

    <ul class="sidebar-menu" data-widget="tree">
      <li class="header">MAIN NAVIGATION</li>
      <li id="dashboard">
        <a id="dashboard" href="<?= base_url(); ?>" onclick="setTreeView(this)">
          <i class="fa fa-dashboard"></i> <span>Dashboard</span>
          <span class="pull-right-container"> </span>
        </a>
      </li>
      <?php $role = $this->session->userdata('session')->id_role; ?>
      <?php
      // 1 administrator
      if ($role == "1") : ?>
        <li id="migrasi">
          <a id="migrasi" href="<?= base_url("migrasi"); ?>" onclick="setTreeView(this)">
            <i class="fa fa-server"></i> <span>Migrasi</span>
            <span class="pull-right-container"> </span>
          </a>
        </li>
        <li id="switch_lokasi">
          <a id="switch_lokasi" href="<?= base_url("dashboard/switch_lokasi"); ?>" onclick="setTreeView(this)">
            <i class="fa  fa-location-arrow text-blue"></i> <span>Pindah Lokasi</span>
            <span class="pull-right-container"> </span>
          </a>
        </li>
      <?php endif ?>


      <?php foreach ($menu as $key => $value) : ?>
        <li class="treeview" id="<?= 'treeview' . $value['id_hak_akses']; ?>">
          <?php if (!in_array($value['name'], array('Laporan', 'Pencarian', 'Penyusutan', 'Lampiran LKPD', 'Unduhan'))) : ?>
            <a href="#" id="<?= 'treeview' . $value['id_hak_akses']; ?>" onclick="setTreeView(this)">
              <i class="fa fa-pie-chart text-red"></i>
              <span><?= $value['name']; ?></span>
              <span class="pull-right-container">
                <i class="fa fa-angle-left pull-right"></i>
              </span>
            </a>

            <ul class="treeview-menu">
              <?php foreach ($value['menu'] as $key2 => $value2) : ?>
                <?php /* "60", "61", "62", : kapitalisasi*/ ?>
                <?php /* "29", "30", "31", "32", : penghapusan */ ?>
                <?php
                /* 18, 19, 42-45: laporan */
                /* 70 : pencarian */
                /* 41 : penyusutan/proses,  */
                /* 42,43 : laporan penyusutan */
                ?>
                <?php if (!in_array($value2->id_menu, array("18", "19", "22", "44", "45",  "80", "70", "41"))) : /* hidden menu yg tidak digunakan*/ ?>
                  <li id="<?= 'treeview_menu' . $value2->id_hak_akses; ?>" onclick="setMenu(this);"><a href="<?= base_url($value2->url); ?>"><i class="fa fa-circle-o text-green"></i> <span><?= $value2->sub_menu; ?></span></a></li>
                <?php endif; ?>
              <?php endforeach; ?>
            </ul>
          <?php endif; ?>
        </li>

      <?php endforeach; ?>

      <?php foreach ($menu as $key => $value) : ?>
        <li class="treeview" id="<?= 'treeview' . $value['id_hak_akses']; ?>">
          <?php if (in_array($value['name'], array('Laporan','Penyusutan', 'Lampiran LKPD', 'Pencarian', 'Unduhan'))) : ?>
            <a href="#" id="<?= 'treeview' . $value['id_hak_akses']; ?>" onclick="setTreeView(this)">
              <i class="fa fa-pie-chart text-red"></i>
              <span><?= $value['name']; ?></span>
              <span class="pull-right-container">
                <i class="fa fa-angle-left pull-right"></i>
              </span>
            </a>

            <ul class="treeview-menu">
              <?php foreach ($value['menu'] as $key2 => $value2) : ?>
                <?php /* "60", "61", "62", : kapitalisasi*/ ?>
                <?php /* "29", "30", "31", "32", : penghapusan */ ?>
                <?php
                /* 18, 19, 42-45: laporan */
                /* 70 : pencarian */
                ?>
                <?php if (in_array($value2->id_menu, array("18", "19", "22",  "41", "42", "43", "44", "45","46","47","48","49","50","51","52","53","54","55","56","57","58","59","80", "70", "71","72","73","74", "103","104","105","106","107","108"))) : /* hidden menu yg tidak digunakan*/ ?>
                  <li id="<?= 'treeview_menu' . $value2->id_hak_akses; ?>" onclick="setMenu(this);"><a href="<?= base_url($value2->url); ?>"><i class="fa fa-circle-o text-green"></i> <span><?= $value2->sub_menu; ?></span></a></li>
                <?php endif; ?>
              <?php endforeach; ?>
            </ul>
          <?php endif; ?>
        </li>

      <?php endforeach; ?>







      <?php /*
      <?php if ($session->id_role == '1'): ?>
        <li class="treeview" id="administrator">
          <a href="#" id="administrator" onclick="setTreeView(this)">
            <i class="fa fa-pie-chart text-red"></i>
            <span>Administrator</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
            <li id="user" onclick="setMenu(this);"><a href="<?= base_url('user'); ?>"><i class="fa fa-circle-o text-red"></i> <span>User</span></a></li>
            <li id="li_kode_lokasi" onclick="setMenu(this);"><a href="<?= base_url('master_data/kode_lokasi'); ?>"><i class="fa fa-circle-o text-red"></i> <span>Kode Lokasi</span></a></li>
            <li id="li_kode_barang" onclick="setMenu(this);"><a href="<?= base_url('master_data/kode_barang'); ?>"><i class="fa fa-circle-o text-red"></i> <span>Kode Barang</span></a></li>
            <li id="li_kode_pemilik" onclick="setMenu(this);"><a href="<?= base_url('master_data/pemilik'); ?>"><i class="fa fa-circle-o text-red"></i> <span>Kode Pemilik</span></a></li>
            <li id="li_intra_extra" onclick="setMenu(this);"><a href="<?= base_url('master_data/master_intra_extra'); ?>"><i class="fa fa-circle-o text-red"></i> <span>Master Intra / Extra</span></a></li>
            <li id="li_master-data" onclick="setMenu(this);"><a href="<?= base_url('master_data'); ?>"><i class="fa fa-circle-o text-red"></i> <span>Master KIB</span></a></li>
          </ul>
        </li>
      <?php endif; ?>


      <?php if ($session->id_role == '1' or $session->id_role == '3'): ?>
        <li class="treeview" id="entri">
          <a href="#" id="entri" onclick="setTreeView(this)">
            <i class="fa fa-pie-chart text-red"></i>
            <span>Data KIB</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
            <li id="entri_a" onclick="setMenu(this);"><a href="<?= base_url('kib_a'); ?>"><i class="fa fa-circle-o text-red"></i> <span>KIB A (Tanah)</span></a></li>
            <li id="entri_b" onclick="setMenu(this);"><a href="<?= base_url('kib_b'); ?>" ><i class="fa fa-circle-o text-red"></i> <span>KIB B (Peralatan & Mesin)</span></a></li>
            <li id="entri_c" onclick="setMenu(this);"><a href="<?= base_url('kib_c'); ?>"><i class="fa fa-circle-o text-red"></i> <span>KIB C (Gedung & Bangunan)</span></a></li>
            <li id="entri_d" onclick="setMenu(this);"><a href="<?= base_url('kib_d'); ?>"><i class="fa fa-circle-o text-red"></i> <span>KIB D (Jalan, Irigasi & Jaringan)</span></a></li>
            <li id="entri_e" onclick="setMenu(this);"><a href="<?= base_url('kib_e'); ?>"><i class="fa fa-circle-o text-red"></i> <span>KIB E (Aset Tetap Lainya)</span></a></li>
            <li id="entri_f" onclick="setMenu(this);"><a href="<?= base_url('kib_f'); ?>"><i class="fa fa-circle-o text-red"></i> <span>KIB F (Kontruksi Dlm Pengerj.)</span></a></li>
          </ul>
        </li>
      <?php endif; ?>

      <?php if ($session->id_role == '1' or $session->id_role == '2'): ?>
        <li class="treeview" id="validasi">
          <a href="#" id="validasi" onclick="setTreeView(this)">
            <i class="fa fa-pie-chart text-blue"></i>
            <span>Validasi KIB</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
            <li id="validator_a" onclick="setMenu(this);">
              <a href="<?= base_url('kib_a/validasi'); ?>"><i class="fa fa-circle-o text-blue"></i> <span>KIB A (Tanah)</span>

              </a>
            </li>
            <li id="validator_b" onclick="setMenu(this);">
              <a href="<?= base_url('kib_b/validasi'); ?>" ><i class="fa fa-circle-o text-blue"></i> <span>KIB B (Peralatan & Mesin)</span>

              </a>
            </li>
            <li id="validator_c" onclick="setMenu(this);">
              <a href="<?= base_url('kib_c/validasi'); ?>"><i class="fa fa-circle-o text-blue"></i> <span>KIB C (Gedung & Bangunan)</span>

              </a>
            </li>
            <li id="validator_d" onclick="setMenu(this);">
              <a href="<?= base_url('kib_d/validasi'); ?>"><i class="fa fa-circle-o text-blue"></i> <span>KIB D (Jalan, Irigasi & Jaringan)</span>

              </a>
            </li>
            <li id="validator_e" onclick="setMenu(this);">
              <a href="<?= base_url('kib_e/validasi'); ?>"><i class="fa fa-circle-o text-blue"></i> <span>KIB E (Aset Tetap Lainya)</span>

              </a>
            </li>
            <li id="validator_f" onclick="setMenu(this);">
              <a href="<?= base_url('kib_f/validasi'); ?>"><i class="fa fa-circle-o text-blue"></i> <span>KIB F (Kontruksi Dlm Pengerj.)</span>
              </a>
            </li>
          </ul>
        </li>
      <?php endif; ?>

      <?php if ($session->id_role == '1' or $session->id_role == '2' or $session->id_role == '3'): ?>
        <li class="treeview hidden" id="mutasi">
          <a href="#" id="mutasi" onclick="setTreeView(this)">
            <i class="fa fa-pie-chart text-green"></i>
            <span>Mutasi</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
            <li id="mutasi-usulan" onclick="setMenu(this);"><a href="<?= base_url('mutasi/usulan'); ?>"><i class="fa fa-circle-o text-green"></i> <span>Usulan</span></a></li>
            <li id="mutasi-penerima" onclick="setMenu(this);"><a href="<?= base_url('mutasi/penerima'); ?>" ><i class="fa fa-circle-o text-green"></i> <span>Penerima</span></a></li>
            <?php if (in_array('Validator', json_decode($_SESSION['session']->role))): ?>
            <li id="mutasi-validasi" onclick="setMenu(this);"><a href="<?= base_url('mutasi/validasi'); ?>" ><i class="fa fa-circle-o text-green"></i> <span>Validasi</span></a></li>
            <?php endif; ?>
          </ul>
        </li>
      <?php endif; ?>

      <?php if ($session->id_role == '1' or $session->id_role == '2' or $session->id_role == '3'): ?>
        <li class="treeview" id="laporan">
          <a href="#" id="laporan" onclick="setTreeView(this)">
            <i class="fa fa-pie-chart text-yellow"></i>
            <span>Laporan</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
            <li id="laporan-kib" onclick="setMenu(this);"><a href="<?= base_url('laporan/kib'); ?>"><i class="fa fa-circle-o text-yellow"></i> <span>KIB</span></a></li>
            <li id="laporan-inventaris" onclick="setMenu(this);"><a href="<?= base_url('laporan/inventaris'); ?>" ><i class="fa fa-circle-o text-yellow"></i> <span>Inventaris</span></a></li>
          </ul>
        </li>
      <?php endif; ?>
*/ ?>
    </ul>
  </section>
  <!-- /.sidebar -->
</aside>