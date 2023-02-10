<?php $session = $this->session->userdata('session');?>
<!-- Left side column. contains the logo and sidebar -->
<aside class="main-sidebar">
  <!-- sidebar: style can be found in sidebar.less -->
  <section class="sidebar">
    <!-- Sidebar user panel -->
    <div class="user-panel">
      <div class="pull-left image">
        <img src="<?= base_url('assets/adminlte/') ?>dist/img/avatar.png" class="img-circle" alt="User Image">
      </div>
      <div class="pull-left info">
        <p><?= $this->session->userdata('session')->nama ?></p>
        <a href="<?= base_url('login/logout') ?>"><i class="fa fa-circle text-success"></i> Logout</a>
      </div>
    </div>
    <ul class="sidebar-menu" data-widget="tree" style="font-size:1px; white-space:normal; border: 1px solid #00aaea70; margin-right: 4px;">
      <li ><a href="#"><i class="fa  fa-location-arrow text-blue" style=" font-size:20px;"></i> <span style="text-decoration: underline;"><?= remove_star($this->session->userdata('session')->kode_lokasi); ?></span></a></li>
      <li ><a href="#" style="padding-top:0px;"> <span><?= $this->session->userdata('session')->nama_lokasi; ?></span></a></li>
    </ul>


    <!-- sidebar menu: : style can be found in sidebar.less -->
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

      <?php // if (getRole(array('Super Admin','Petugas Entri'))):?>
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

      <?php  // if (getRole(array('Super Admin','Validator SKPD'))):?>
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
                <?php /*<span class="pull-right-container">
                  <small id='request_1' class="label pull-right bg-orange">17</small>
                </span>*/ ?>
              </a>
            </li>
            <li id="validator_b" onclick="setMenu(this);">
              <a href="<?= base_url('kib_b/validasi'); ?>" ><i class="fa fa-circle-o text-blue"></i> <span>KIB B (Peralatan & Mesin)</span>
                <?php /*<span class="pull-right-container">
                  <small id='request_2' class="label pull-right bg-orange">17</small>
                </span>*/ ?>
              </a>
            </li>
            <li id="validator_c" onclick="setMenu(this);">
              <a href="<?= base_url('kib_c/validasi'); ?>"><i class="fa fa-circle-o text-blue"></i> <span>KIB C (Gedung & Bangunan)</span>
                <?php /*<span class="pull-right-container">
                  <small id='request_3' class="label pull-right bg-orange">17</small>
                </span>*/ ?>
              </a>
            </li>
            <li id="validator_d" onclick="setMenu(this);">
              <a href="<?= base_url('kib_d/validasi'); ?>"><i class="fa fa-circle-o text-blue"></i> <span>KIB D (Jalan, Irigasi & Jaringan)</span>
                <?php /*<span class="pull-right-container">
                  <small id='request_4' class="label pull-right bg-orange">17</small>
                </span>*/ ?>
              </a>
            </li>
            <li id="validator_e" onclick="setMenu(this);">
              <a href="<?= base_url('kib_e/validasi'); ?>"><i class="fa fa-circle-o text-blue"></i> <span>KIB E (Aset Tetap Lainya)</span>
                <?php /*<span class="pull-right-container">
                  <small id='request_5' class="label pull-right bg-orange">17</small>
                </span>*/ ?>
              </a>
            </li>
            <li id="validator_f" onclick="setMenu(this);">
              <a href="<?= base_url('kib_f/validasi'); ?>"><i class="fa fa-circle-o text-blue"></i> <span>KIB F (Kontruksi Dlm Pengerj.)</span>
                <?php /*<span class="pull-right-container">
                  <small id='request_6' class="label pull-right bg-orange">17</small>
                </span>*/ ?>
              </a>
            </li>
          </ul>
        </li>
      <?php endif; ?>

      <?php //if (getRole(array('Super Admin','Validator SKPD','Petugas Entri'))):?>
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

      <?php //if (getRole(array('Super Admin','Validator SKPD','Petugas Entri'))):?>
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


      <?php /*
      <li class="active treeview">
        <a href="#">
          <i class="fa fa-dashboard"></i> <span>Dashboard</span>
          <span class="pull-right-container">
            <i class="fa fa-angle-left pull-right"></i>
          </span>
        </a>
        <ul class="treeview-menu">
          <li class="active"><a href="index.html"><i class="fa fa-circle-o"></i> Dashboard v1</a></li>
          <li><a href="index2.html"><i class="fa fa-circle-o"></i> Dashboard v2</a></li>
        </ul>
      </li>

      <li class="treeview">
        <a href="#">
          <i class="fa fa-files-o"></i>
          <span>Layout Options</span>
          <span class="pull-right-container">
            <span class="label label-primary pull-right">4</span>
          </span>
        </a>
        <ul class="treeview-menu">
          <li><a href="pages/layout/top-nav.html"><i class="fa fa-circle-o"></i> Top Navigation</a></li>
          <li><a href="pages/layout/boxed.html"><i class="fa fa-circle-o"></i> Boxed</a></li>
          <li><a href="pages/layout/fixed.html"><i class="fa fa-circle-o"></i> Fixed</a></li>
          <li><a href="pages/layout/collapsed-sidebar.html"><i class="fa fa-circle-o"></i> Collapsed Sidebar</a></li>
        </ul>
      </li>
      <li>
        <a href="pages/widgets.html">
          <i class="fa fa-th"></i> <span>Widgets</span>
          <span class="pull-right-container">
            <small class="label pull-right bg-green">new</small>
          </span>
        </a>
      </li>
      <li class="treeview">
        <a href="#">
          <i class="fa fa-pie-chart"></i>
          <span>Charts</span>
          <span class="pull-right-container">
            <i class="fa fa-angle-left pull-right"></i>
          </span>
        </a>
        <ul class="treeview-menu">
          <li><a href="pages/charts/chartjs.html"><i class="fa fa-circle-o"></i> ChartJS</a></li>
          <li><a href="pages/charts/morris.html"><i class="fa fa-circle-o"></i> Morris</a></li>
          <li><a href="pages/charts/flot.html"><i class="fa fa-circle-o"></i> Flot</a></li>
          <li><a href="pages/charts/inline.html"><i class="fa fa-circle-o"></i> Inline charts</a></li>
        </ul>
      </li>
      <li class="treeview">
        <a href="#">
          <i class="fa fa-laptop"></i>
          <span>UI Elements</span>
          <span class="pull-right-container">
            <i class="fa fa-angle-left pull-right"></i>
          </span>
        </a>
        <ul class="treeview-menu">
          <li><a href="pages/UI/general.html"><i class="fa fa-circle-o"></i> General</a></li>
          <li><a href="pages/UI/icons.html"><i class="fa fa-circle-o"></i> Icons</a></li>
          <li><a href="pages/UI/buttons.html"><i class="fa fa-circle-o"></i> Buttons</a></li>
          <li><a href="pages/UI/sliders.html"><i class="fa fa-circle-o"></i> Sliders</a></li>
          <li><a href="pages/UI/timeline.html"><i class="fa fa-circle-o"></i> Timeline</a></li>
          <li><a href="pages/UI/modals.html"><i class="fa fa-circle-o"></i> Modals</a></li>
        </ul>
      </li>
      <li class="treeview">
        <a href="#">
          <i class="fa fa-edit"></i> <span>Forms</span>
          <span class="pull-right-container">
            <i class="fa fa-angle-left pull-right"></i>
          </span>
        </a>
        <ul class="treeview-menu">
          <li><a href="pages/forms/general.html"><i class="fa fa-circle-o"></i> General Elements</a></li>
          <li><a href="pages/forms/advanced.html"><i class="fa fa-circle-o"></i> Advanced Elements</a></li>
          <li><a href="pages/forms/editors.html"><i class="fa fa-circle-o"></i> Editors</a></li>
        </ul>
      </li>
      <li class="treeview">
        <a href="#">
          <i class="fa fa-table"></i> <span>Tables</span>
          <span class="pull-right-container">
            <i class="fa fa-angle-left pull-right"></i>
          </span>
        </a>
        <ul class="treeview-menu">
          <li><a href="pages/tables/simple.html"><i class="fa fa-circle-o"></i> Simple tables</a></li>
          <li><a href="pages/tables/data.html"><i class="fa fa-circle-o"></i> Data tables</a></li>
        </ul>
      </li>
      <li>
        <a href="pages/calendar.html">
          <i class="fa fa-calendar"></i> <span>Calendar</span>
          <span class="pull-right-container">
            <small class="label pull-right bg-red">3</small>
            <small class="label pull-right bg-blue">17</small>
          </span>
        </a>
      </li>
      <li>
        <a href="pages/mailbox/mailbox.html">
          <i class="fa fa-envelope"></i> <span>Mailbox</span>
          <span class="pull-right-container">
            <small class="label pull-right bg-yellow">12</small>
            <small class="label pull-right bg-green">16</small>
            <small class="label pull-right bg-red">5</small>
          </span>
        </a>
      </li>
      <li class="treeview">
        <a href="#">
          <i class="fa fa-folder"></i> <span>Examples</span>
          <span class="pull-right-container">
            <i class="fa fa-angle-left pull-right"></i>
          </span>
        </a>
        <ul class="treeview-menu">
          <li><a href="pages/examples/invoice.html"><i class="fa fa-circle-o"></i> Invoice</a></li>
          <li><a href="pages/examples/profile.html"><i class="fa fa-circle-o"></i> Profile</a></li>
          <li><a href="pages/examples/login.html"><i class="fa fa-circle-o"></i> Login</a></li>
          <li><a href="pages/examples/register.html"><i class="fa fa-circle-o"></i> Register</a></li>
          <li><a href="pages/examples/lockscreen.html"><i class="fa fa-circle-o"></i> Lockscreen</a></li>
          <li><a href="pages/examples/404.html"><i class="fa fa-circle-o"></i> 404 Error</a></li>
          <li><a href="pages/examples/500.html"><i class="fa fa-circle-o"></i> 500 Error</a></li>
          <li><a href="pages/examples/blank.html"><i class="fa fa-circle-o"></i> Blank Page</a></li>
          <li><a href="pages/examples/pace.html"><i class="fa fa-circle-o"></i> Pace Page</a></li>
        </ul>
      </li>
      <li class="treeview">
        <a href="#">
          <i class="fa fa-share"></i> <span>Multilevel</span>
          <span class="pull-right-container">
            <i class="fa fa-angle-left pull-right"></i>
          </span>
        </a>
        <ul class="treeview-menu">
          <li><a href="#"><i class="fa fa-circle-o"></i> Level One</a></li>
          <li class="treeview">
            <a href="#"><i class="fa fa-circle-o"></i> Level One
              <span class="pull-right-container">
                <i class="fa fa-angle-left pull-right"></i>
              </span>
            </a>
            <ul class="treeview-menu">
              <li><a href="#"><i class="fa fa-circle-o"></i> Level Two</a></li>
              <li class="treeview">
                <a href="#"><i class="fa fa-circle-o"></i> Level Two
                  <span class="pull-right-container">
                    <i class="fa fa-angle-left pull-right"></i>
                  </span>
                </a>
                <ul class="treeview-menu">
                  <li><a href="#"><i class="fa fa-circle-o"></i> Level Three</a></li>
                  <li><a href="#"><i class="fa fa-circle-o"></i> Level Three</a></li>
                </ul>
              </li>
            </ul>
          </li>
          <li><a href="#"><i class="fa fa-circle-o"></i> Level One</a></li>
        </ul>
      </li>
      <li><a href="https://adminlte.io/docs"><i class="fa fa-book"></i> <span>Documentation</span></a></li>
      <li class="header">LABELS</li>
      <li><a href="#"><i class="fa fa-circle-o text-red"></i> <span>Important</span></a></li>
      <li><a href="#"><i class="fa fa-circle-o text-yellow"></i> <span>Warning</span></a></li>
      <li><a href="#"><i class="fa fa-circle-o text-aqua"></i> <span>Information</span></a></li>
      */ ?>
    </ul>
  </section>
  <!-- /.sidebar -->
</aside>
