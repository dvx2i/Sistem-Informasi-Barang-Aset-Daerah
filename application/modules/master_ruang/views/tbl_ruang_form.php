<?php 
    $session = $this->session->userdata('session'); ?>
<section class="content">
  <h2 class="text-success" style="margin-top:0px"><?php echo $button . ' ' . $menu; ?></h2>


  <form action="<?php echo $action; ?>" method="post" autocomplete="off">


   

      <div class="box" style="padding: 10px; border: 3px solid #d2d6de; background-color:transparent;">
        <div class="row">
          <div class="col-md-6">
            <div class="form-group">
              <label for="enum">Status Pemilik</label>
              <select class="form-control" name="status_pemilik" id="status_pemilik">
                <?php foreach ($pemilik as $key => $value) : ?>
                  <option value="<?= $value->id_pemilik; ?>" <?= ($value->id_pemilik == $status_pemilik) ? 'selected' : ''; ?>><?= $value->kode . ' - ' . $value->nama; ?></option>
                <?php endforeach; ?>
              </select>
            </div>
          </div>
        </div>

        <div class="row">
          <div class="col-md-6">
            <div class="form-group">
              <label for="varchar">Kode Lokasi <?php echo form_error('kode_lokasi') ?></label>
              <input type="text" class="form-control" name="kode_lokasi" id="kode_lokasi" placeholder="Kode Lokasi" value="<?php echo $kode_lokasi; ?>" readonly />
              <input type="hidden" class="form-control" id="kode_lokasi_old" value="<?php echo $kode_lokasi; ?>" readonly />
            </div>
          </div>
          <div class="col-md-6">
            <div class="form-group">
              <label for="varchar">Nama Lokasi </label>
              <input type="text" class="form-control" name="nama_lokasi" id="nama_lokasi" placeholder="Nama Lokasi" value="<?php echo $nama_lokasi; ?>" readonly />
            </div>
          </div>
        </div>

      </div>


      <div class="form-group">
        <label for="varchar">Letak/Alamat <?php echo form_error('letak_alamat') ?></label>
        <input type="text" class="form-control" name="letak_alamat" id="letak_alamat" placeholder="Letak Alamat" value="<?php echo $letak_alamat; ?>" />
      </div>
       <div class="row">
        <div class="col-md-6">
          <div class="form-group">
            <label for="varchar">Nama gedung <?php echo form_error('nama_gedung') ?></label>
             <input type="text" class="form-control" name="nama_gedung" id="nama_gedung" placeholder="Nama Gedung" value="<?php echo $nama_gedung; ?>" />
          </div>
        </div>
        </div>
      <div class="row">
        <div class="col-md-4">
          <div class="form-group">
            <label for="varchar">Lantai <?php echo form_error('lantai') ?></label>

            <select class="form-control " name="lantai" id="lantai" >
              <option value="">Silahkan Pilih</option>
              <option value="1">Lantai 1</option>
              <option value="2">Lantai 2</option>
              <option value="3">Lantai 3</option>
              <option value="4">Lantai 4</option>
              <option value="5">Lantai 5</option>
            </select>
          </div>
        </div>
        </div>
      <div class="row">
        <div class="col-md-6">
          <div class="form-group">
            <label for="varchar">Nama Ruang <?php echo form_error('nama_ruang') ?></label>
             <input type="text" class="form-control" name="nama_ruang" id="nama_ruang" placeholder="Nama Ruang" value="<?php echo $nama_ruang; ?>" />
          </div>
        </div>
        </div>
        <div class="row">
        <div class="col-md-6">
          <div class="form-group">
            <label for="varchar">Deskripsi <?php echo form_error('deskripsi') ?></label>
             <input type="text" class="form-control" name="deskripsi" id="deskripsi" placeholder="Deskripsi" value="<?php echo $deskripsi; ?>" />
          </div>
        </div>
        </div>
        <div class="row">
        <div class="col-md-4">
          <div class="form-group">
            <label for="varchar">Status Ruang <?php echo form_error('status_ruang') ?></label>

            <select class="form-control " name="status_ruang" id="status_ruang" >
              <option value="">Silahkan Pilih</option>
              <option value="1">Aktif</option>
              <option value="2">Tidak Aktif</option>
             
            </select>
          </div>
        </div>
        </div>
        

      <div class="row">
        <div class="col-md-3">
          <div class="form-group">
            <label for="varchar">Latitude <?php echo form_error('latitute') ?></label>
            <input type="text" class="form-control" name="latitute" id="latitute" placeholder="Latitude" value="<?php echo $latitute; ?>" />
          </div>
        </div>
        <div class="col-md-3">
          <div class="form-group">
            <label for="varchar">Longitute <?php echo form_error('longitute') ?></label>
            <input type="text" class="form-control" name="longitute" id="longitute" placeholder="Longitute" value="<?php echo $longitute; ?>" />
          </div>
        </div>
      </div>

      

      <input type="hidden" name="id_ruang" value="<?php echo $id_ruang; ?>" />
      <button type="submit" class="btn btn-primary">Simpan</button>
      
        <a href="<?php echo base_url('master_ruang') ?>" class="btn btn-default">Batalkan</a>
    

    </div>
  </form>
</section>