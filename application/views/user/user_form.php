<style media="screen">
   .select2-selection__rendered { margin-top: 1px!important }
</style>

  <section class="content">
    <h2 style="margin-top:0px"><?php echo $data['button'] ?> Pengguna </h2>
    <form action="<?php echo $data['action']; ?>" method="post">
      <input type="hidden" name="id_user_jss" id="id_user_jss" value="<?php echo $data['id_user_jss']; ?>">
      <input type="hidden" name="username" id="username" value="<?php echo $data['username']; ?>">
      <div class="row">
        <div class="col-md-3">
          <div class="form-group">
            <label for="varchar">ID JSS(Jogja Smart Service) <?php echo form_error('id_upik') ?></label>
            <input type="text" class="form-control" name="id_upik" id="id_upik" placeholder="ID JSS Contoh : JSS-A1234" value="<?php echo $data['id_upik']; ?>" />
            <button type="button" id="cari_jss" class="btn btn-success" style="margin-top:5px;"><?php echo "Cari"; ?></button>
          </div>
        </div>
      </div>

      <div class="row">
        <div class="col-md-3">
          <div class="form-group">
            <label for="varchar">NIP <?php echo form_error('nip') ?></label>
            <input type="text" class="form-control" name="nip" id="nip" placeholder="NIP" value="<?php echo $data['nip']; ?>" />
          </div>
        </div>
      </div>
      <div class="row">
        <div class="col-md-6">
          <div class="form-group">
            <label for="varchar">Nama <?php echo form_error('nama') ?></label>
            <input type="text" class="form-control" name="nama" id="nama" placeholder="Nama" value="<?php echo $data['nama']; ?>" />
          </div>
        </div>
      </div>
      <div class="row">
        <div class="col-md-5">
          <div class="form-group">
            <label for="varchar">Email <?php echo form_error('email') ?></label>
            <input type="text" class="form-control" name="email" id="email" placeholder="Email" value="<?php echo $data['email']; ?>" />
          </div>
        </div>
      </div>
      <div class="row">
        <div class="col-md-5">
          <div class="form-group">
            <label for="varchar">Nomor Telepon <?php echo form_error('nomor_telepone') ?></label>
            <input type="text" class="form-control" name="nomor_telepone" id="nomor_telepone" placeholder="Nomor Telepone" value="<?php echo $data['nomor_telepone']; ?>" />
          </div>
        </div>
      </div>

      <div class="form-group">
        <label for="checkbox">Hak Akses</label>
        <?php
        foreach ($role_list as $key => $value) :
          $selected = $value->id_role == $data['role'] ? 'checked':'';
          $session = $this->session->userdata('session');?>
          <?php if ($session->id_role == 1 and $value->id_role == 1): ?>
            <div class="">
                <label style="margin-right:7%">
                  <input type="radio" name="role" value="<?=$value->id_role; ?>" class="flat-red" <?=$selected; ?>> <?=$value->description; ?>
                </label>
            </div>
          <?php elseif ($value->id_role != 1) :?>
            <div class="">
                <label style="margin-right:7%">
                  <input type="radio" name="role" value="<?=$value->id_role; ?>" class="flat-red" <?=$selected; ?>> <?=$value->description; ?>
                </label>
            </div>
          <?php endif; ?>



        <?php endforeach; ?>
      </div>

      <div class="row">
        <div class="col-md-8">
          <div class="form-group">
            <label for="varchar">Kode Lokasi <?php echo form_error('kode_lokasi') ?></label>
            <select class="form-control" name="kode_lokasi" id="kode_lokasi" placeholder="Kode Lokasi" data-role="select2">
              <option value="261">00.34.71.06 - UNSUR PENGAWASAN</option>
              <?php foreach ($lokasi as $key): ?>
                <option data-instansi="<?php echo $key->instansi ?>" value="<?php echo $key->id_kode_lokasi; ?>" <?= ($key->id_kode_lokasi == $data['kode_lokasi']) ? 'selected' : ''?>><?php echo remove_star($key->kode).' - '.$key->instansi ?></option>
              <?php endforeach; ?>
            </select>
          </div>
        </div>
      </div>
	  <div class="row">
        <div class="col-md-2">
          <div class="form-group">
            <label for="varchar">Status Freze <?php echo form_error('freze') ?></label>
            <select class="form-control" name="freze" id="freze" placeholder="freze" >
              <option value="0" <?php if($data['freze']=='0'){echo "selected";}?>> Tidak</option>
			  <option value="1" <?php if($data['freze']=='1'){echo "selected";}?>> Ya</option>
              
            </select>
          </div>
        </div>
      </div>
	  
      <?php /*
      <div class="row">
        <div class="col-md-4">
          <div class="form-group">
            <label for="varchar">Jabatan <?php echo form_error('jabatan') ?></label>
            <select class="form-control" name="jabatan" id="jabatan" placeholder="Jabatan" data-role="select2">
              <?php foreach ($list_jabatan as $key): ?>
                <option value="<?php echo $key->id_jabatan; ?>" <?= ($key->id_jabatan == $data['jabatan']) ? 'selected' : ''?>><?php echo $key->description ?></option>
              <?php endforeach; ?>
            </select>
          </div>
        </div>
      </div>
      */ ?>

      <input type="hidden" name="id_user" value="<?php echo $data['id_user']; ?>" />
      <button type="submit" class="btn btn-primary"><?php echo 'Simpan';//$data['button'] ?></button>
      <a href="<?php echo base_url('User') ?>" class="btn btn-default">Batalkan</a>
    </form>
  </section>
