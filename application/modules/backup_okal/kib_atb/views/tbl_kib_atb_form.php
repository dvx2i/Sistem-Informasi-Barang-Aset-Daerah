<?php 
    $session = $this->session->userdata('session'); ?>
<section class="content">
    <h2 class="text-success" style="margin-top:0px"><?php echo $button . ' ' . $menu; ?></h2>

    <form action="<?php echo $action; ?>" method="post" autocomplete="off">


        <?php if ($data_reklas) : ?>
            <div class="box" style="padding: 10px; border: 3px solid #d2d6de; background-color:transparent;">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="varchar">KIB Asal : <?php echo $kode_jenis_asal['nama']; ?></label><br>
                            <label for="varchar">No Register : <?php echo $kib_asal['nomor_register']; ?></label>
                        </div>
                    </div>

                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="varchar">Kode Barang <?php /*echo form_error('kode_barang')*/ ?></label>
                            <input type="text" class="form-control" name="" id="" placeholder="" value="<?php echo $kode_barang_asal['kode_barang']; ?>" readonly />
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="varchar">Nama Barang <?php /*echo form_error('nama_barang')*/ ?></label>
                            <input type="text" class="form-control" name="" id="" placeholder="Nama Barang" value="<?php echo $kode_barang_asal['nama_barang_simbada'] ? $kode_barang_asal['nama_barang_simbada'] : $kode_barang_asal['nama_barang']; ?>" readonly />
                        </div>
                    </div>
                </div>

            </div>
        <?php endif; ?>


        <div class="row">
            <?php if($tanggal_transaksi == '-' || ($this->session->userdata('session')->id_role != '1' && $validasi == '2' )) : ?>
            <div class="col-md-6">
                <div class="form-group">
                <label for="varchar">Tanggal Transaksi / Entri<?php echo form_error('tanggal_transaksi') ?></label>
                <input type="text" class="form-control date_input" name="tanggal_transaksi" id="tanggal_transaksi" placeholder="Tanggal Transaksi / Entri" value="<?php echo $tanggal_transaksi; ?>" disabled />
                </div>
            </div>
            <?php else : ?>
                
            <div class="col-md-6">
                <div class="form-group">
                <label for="varchar">Tanggal Transaksi / Entri<?php echo form_error('tanggal_transaksi') ?></label>
                <input type="text" class="form-control date_input" name="tanggal_transaksi" id="tanggal_transaksi" placeholder="Tanggal Transaksi / Entri" value="<?php echo $tanggal_transaksi; ?>" onchange="show_input();" />
                </div>
            </div>
            <?php endif; ?>
            <div class="col-md-6">
                <div class="form-group">
                    <label for="varchar">Nomor Transaksi (SP2D, BAST, kwitansi, dll.) <?php echo form_error('nomor_transaksi') ?></label>
                    <input type="text" class="form-control" name="nomor_transaksi" id="nomor_transaksi" placeholder="Nomor Transaksi" value="<?php echo $nomor_transaksi; ?>" onkeyup="show_input();" />
                </div>
            </div>
        </div>




        <div <?= $id_kib_atb == '' ? ' id="input_disable" class="input_disable" ' : ''; ?>>


            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="varchar">Sumber Dana / Anggaran <?php echo form_error('sumber_dana') ?></label>
                        <select class="form-control" name="sumber_dana" id="sumber_dana">
                            <option value="">Silahkan Pilih</option>
                            <?php foreach ($lis_sumber_dana as $key => $value) : ?>
                                <option value="<?= $value['id_sumber_dana']; ?>" <?= ($value['id_sumber_dana'] == $sumber_dana) ? 'selected' : ''; ?>><?= $value['nama_sumber_dana']; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="varchar">Kode Anggaran <?php echo form_error('kode_rekening') ?></label>
                        <?php /*<input type="text" class="form-control" name="rekening" id="rekening" placeholder="" value="<?php echo $rekening; ?>" /> */ ?>

                        <select class="form-control" name="rekening" id="rekening" data-role="select2">
                            <option value="">Silahkan Pilih</option>
                            <?php foreach ($lis_rekening as $key => $value) : ?>
                                <option value="<?= $value['id_rekening']; ?>" <?= $value['id_rekening'] == $rekening ? 'selected' : ''; ?>> <?= $value['kode_rekening'] . ' - ' . $value['nama_rekening']; ?></option>;
                            <?php endforeach; ?>
                        </select>

                    </div>
                </div>
            </div>

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

            <div class="box" style="padding: 10px; border: 3px solid #d2d6de;">

                <input type="hidden" name="validasi" value="<?php echo $validasi; ?>">

                <div id='input_disable_barang' class="<?php echo (($validasi == 2) or (!empty($data_reklas))) ? "input_disable" : ""; ?>">
                    <div class="row">
                        <div class="col-md-6">
              <a style="margin-bottom: 5px;" class="btn btn-success btn-sm" href="https://aset.jogjakota.go.id/assets/files/ASET_TIDAK_BERWUJUD.pdf" target="_blank">Referensi Kode Barang</a>
          
              
                            <div class="form-group">
                                <label for="enum">Kode Objek</label>
                                <select class="form-control" name="kode_objek" id="kode_objek_atb" data-role="select2">
                                    <option value="">Silahkan Pilih</option>
                                    <?php foreach ($objek as $key => $value) : ?>
                                        <?php //die($kode_objek . "==" . $value->kode_barang)  
                                        ?>
                                        <option value=<?= $value->kode_barang; ?> <?= $kode_objek . "." == $value->kode_barang ? 'selected' : ''; ?>><?= $value->kode_objek . ' - ' . $value->nama_barang; ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="enum">Kode Rincian Objek</label>
                                <select class="form-control" name="kode_rincian_objek" id="kode_rincian_objek_atb" data-role="select2">
                                    <option value="">Silahkan Pilih</option>
                                    <?php foreach ($rincian_objek as $key => $value) : ?>
                                        <option value=<?= $value->kode_barang; ?> <?= $kode_rincian_objek . "." == $value->kode_barang ? 'selected' : ''; ?>><?= $value->kode_rincian_objek . ' - ' . $value->nama_barang; ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="enum">Kode Sub Rincian Objek</label>
                                <select class="form-control" name="kode_sub_rincian_objek" id="kode_sub_rincian_objek_atb" data-role="select2">
                                    <option value="">Silahkan Pilih</option>
                                    <?php foreach ($sub_rincian_objek as $key => $value) : ?>
                                        <option value="<?= $value->kode_barang; ?>" <?= $kode_sub_rincian_objek . "." == $value->kode_barang ? 'selected' : ''; ?>> <?= $value->kode_sub_rincian_objek . ' - ' . $value->nama_barang; ?></option>;
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                    </div>


                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="enum">Kode Sub Sub Rincian Objek</label>
                                <select class="form-control" name="kode_sub_sub_rincian_objek" id="kode_sub_sub_rincian_objek" data-role="select2">
                                    <option value="">Silahkan Pilih</option>
                                    <?php foreach ($sub_sub_rincian_objek as $key => $value) : ?>
                                        <?php //die($kode_sub_sub_rincian_objek . "==" . $value->kode_barang);
                                        ?>
                                        <option value="<?= $value->kode_barang; ?>" <?= $kode_sub_sub_rincian_objek == $value->kode_barang ? 'selected' : ''; ?>> <?= $value->kode_sub_sub_rincian_objek . ' - ' . $value->nama_barang; ?></option>;
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                    </div>

                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="varchar">Kode Barang <?php echo form_error('kode_barang') ?></label>
                            <input type="hidden" id="kode_barang_old" value="<?php echo $kode_barang; ?>" />
                            <input type="text" class="form-control" name="kode_barang" id="kode_barang" placeholder="Kode Barang" value="<?php echo $kode_barang; ?>" readonly />
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="varchar">Nama Barang <?php echo form_error('nama_barang') ?></label>
                            <input type="text" class="form-control" name="nama_barang" id="nama_barang" placeholder="Nama Barang" value="<?php echo $nama_barang; ?>" readonly />
                        </div>
                    </div>
                </div>
            </div>
            <?php /*
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="char">Nomor Register <?php echo form_error('nomor_register') ?></label>
                        <input type="text" class="form-control" name="nomor_register" id="nomor_register" placeholder="Nomor Register" value="<?php echo $nomor_register; ?>" />
                    </div>
                </div>
            </div>
             */ ?>
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                        <label for="varchar">Judul Kajian Nama Software <?php echo form_error('judul_kajian_nama_software') ?></label>
                        <input type="text" class="form-control" name="judul_kajian_nama_software" id="judul_kajian_nama_software" placeholder="Judul Kajian Nama Software" value="<?php echo $judul_kajian_nama_software; ?>" />
                    </div>
                </div>
            </div>
            <div class="row">
            <?php if($validasi == '2' && $this->session->userdata('session')->id_role == '1' && $tanggal_perolehan != '-' ) : ?>
                <div class="col-md-6">
                <div class="form-group">
                    <label for="varchar">Tanggal Perolehan <?php echo form_error('tanggal_perolehan') ?></label>
                    <input type="text" class="form-control date_input" name="tanggal_perolehan" id="tanggal_perolehan" placeholder="Tanggal Perolehan" value="<?php echo $tanggal_perolehan; ?>" />
                </div>
                </div>
                <?php else : ?>
                <div class="col-md-6">
                <div class="form-group">
                    <label for="varchar">Tanggal Perolehan <?php echo form_error('tanggal_perolehan') ?></label>
                    <input type="text" class="form-control <?php echo $validasi == 2 ? "" : "date_input"; ?>" name="tanggal_perolehan" id="tanggal_perolehan" placeholder="Tanggal Perolehan" value="<?php echo $tanggal_perolehan; ?>" <?php echo $validasi == 2 ? "readonly" : ""; ?> />
                </div>
                </div>
                <?php endif; ?>
                
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="varchar">Asal Usul <?php echo form_error('asal_usul') ?></label>
                        <input type="text" id="asal_usul_text" readonly class="form-control" value="">
                        <input type="hidden" name="asal_usul" id="asal_usul" value="">
                        <?php /*<select class="form-control" name="asal_usul" id="asal_usul" data-role="select2">
                        <option value="">Silahkan Pilih</option>
                        <?php foreach ($master_asal_usul as $key => $value) : ?>
                            <option value="<?= $value->description; ?>" <?= ($asal_usul == $value->description) ? 'selected' : ''; ?>><?= $value->description; ?></option>
                        <?php endforeach; ?>
                        
                        </select> */ ?>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="int">Harga <?php echo form_error('harga') ?></label>
                        <input type="text" class="form-control format_float" name="harga" id="harga" placeholder="Harga" value="<?php echo str_replace(".", ",", $harga); ?>" <?php echo $validasi == 2 ? "readonly" : ""; ?> />
                    </div>
                </div>
            </div>


            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="varchar">Satuan <?php echo form_error('satuan') ?></label>

                        <select class="form-control " name="satuan" id="satuan" data-role="select2">
                            <option value="">Silahkan Pilih</option>
                            <?php foreach ($master_satuan as $key => $value) : ?>
                                <?php if($value->description != 'DLL') : ?>
                                <option value="<?= $value->description; ?>" <?= ($satuan == $value->description) ? 'selected' : ''; ?>><?= $value->description; ?></option>
                                <?php endif; ?>
                                <?php endforeach; ?>
                            <option value="DLL">DLL</option>
                        </select>
                    </div>
                </div>
                <?php if($session->id_role == '1') : ?>
                <div class="col-md-4">
                <div class="form-group">
                    <label for="varchar">Umur Ekonomis <?php echo form_error('umur_ekonomis') ?></label>
                    <input type="text" class="form-control format_number" value="0" name="umur_ekonomis" id="umur_ekonomis" placeholder="Umur Ekonomis" value="<?php echo $umur_ekonomis; ?>" />
                </div>
                </div>
                <?php endif; ?>
            </div>


            <!-- <div class="row">
                <div class="col-md-12"> -->
            <div class="form-group">
                <label for="keterangan">Keterangan <?php echo form_error('keterangan') ?></label>
                <textarea class="form-control" rows="3" name="keterangan" id="keterangan" placeholder="Keterangan"><?php echo $keterangan; ?></textarea>
            </div>
            <!-- </div>
            </div> -->
            <div class="form-group">
                <label for="deskripsi">Deskripsi (Spesifikasi)<?php echo form_error('deskripsi') ?></label>
                <textarea class="form-control" rows="3" name="deskripsi" id="deskripsi" placeholder="Deskripsi"><?php echo $deskripsi; ?></textarea>
            </div>
            <?php /*
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="float">Luas (M2) <?php echo form_error('luas') ?></label>
                        <input type="text" class="form-control format_number" name="luas" id="luas" placeholder="Luas" value="<?php echo $luas; ?>" />
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <label for="varchar">Tahun Pengadaan <?php echo form_error('tahun_pengadaan') ?></label>
                        <input type="hidden" id="tahun_pengadaan_old" value="<?php echo $tahun_pengadaan; ?>" />
                        <input type="text" class="form-control tahun_input" name="tahun_pengadaan" id="tahun_pengadaan" placeholder="<?= date('Y'); ?>" value="<?php echo $tahun_pengadaan; ?>" />
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
                        <label for="varchar">Status Hak <?php echo form_error('status_hak') ?></label>
                        <select class="form-control " name="status_hak" id="status_hak" data-role="select2">
                            <option value="">Silahkan Pilih</option>
                            <?php foreach ($master_hak_tanah as $key => $value) : ?>
                                <option value="<?= $value->description; ?>" <?= ($status_hak == $value->description) ? 'selected' : ''; ?>><?= $value->description; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <label for="date">Sertifikat Tanggal <?php echo form_error('sertifikat_tanggal') ?></label>
                        <input type="text" class="form-control date_input" name="sertifikat_tanggal" id="sertifikat_tanggal" placeholder="<?= tgl_indo(date('Y-m-d')); ?>" value="<?php echo $sertifikat_tanggal; ?>" />
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="varchar">Sertifikat Nomor <?php echo form_error('sertifikat_nomor') ?></label>
                        <input type="text" class="form-control" name="sertifikat_nomor" id="sertifikat_nomor" placeholder="Sertifikat Nomor" value="<?php echo $sertifikat_nomor; ?>" />
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                        <label for="varchar">Penggunaan <?php echo form_error('penggunaan') ?></label>
                        <input type="text" class="form-control" name="penggunaan" id="penggunaan" placeholder="Penggunaan" value="<?php echo $penggunaan; ?>" />
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="varchar">Kondisi <?php echo form_error('kondisi') ?></label>

                        <select class="form-control " name="kondisi" id="kondisi" data-role="select2">
                            <option value="">Silahkan Pilih</option>
                            <?php foreach ($master_kondisi_bangunan as $key => $value) : ?>
                                <option value="<?= $value->description; ?>" <?= ($kondisi == $value->description) ? 'selected' : ''; ?>><?= $value->description; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="form-group">
                        <label for="varchar">Asal Usul <?php echo form_error('asal_usul') ?></label>
                        <select class="form-control" name="asal_usul" id="asal_usul" data-role="select2">
                            <option value="">Silahkan Pilih</option>
                            <?php foreach ($master_asal_usul as $key => $value) : ?>
                                <option value="<?= $value->description; ?>" <?= ($asal_usul == $value->description) ? 'selected' : ''; ?>><?= $value->description; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="form-group">
                        <label for="int">Harga <?php echo form_error('harga') ?></label>
                        <input type="text" class="form-control format_number" name="harga" id="harga" placeholder="Harga" value="<?php echo $harga; ?>" />
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="varchar">Latitude <?php echo form_error('latitute') ?></label>
                        <input type="text" class="form-control" name="latitute" id="latitute" placeholder="-7.78278" value="<?php echo $latitute; ?>" />
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="varchar">Longitute <?php echo form_error('longitute') ?></label>
                        <input type="text" class="form-control" name="longitute" id="longitute" placeholder="110.36083" value="<?php echo $longitute; ?>" />
                    </div>
                </div>
            </div>

            <div class="form-group">
                <label for="keterangan">Keterangan <?php echo form_error('keterangan') ?></label>
                <textarea class="form-control" rows="3" name="keterangan" id="keterangan" placeholder="Keterangan"><?php echo $keterangan; ?></textarea>
            </div>
            <div class="form-group">
                <label for="deskripsi">Deskripsi (Spesifikasi)<?php echo form_error('deskripsi') ?></label>
                <textarea class="form-control" rows="3" name="deskripsi" id="deskripsi" placeholder="Deskripsi"><?php echo $deskripsi; ?></textarea>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="varchar">Tanggal Pembelian <?php echo form_error('tanggal_pembelian') ?></label>
                        <input type="text" class="form-control date_input" name="tanggal_pembelian" id="tanggal_pembelian" placeholder="<?= tgl_indo(date('Y-m-d')) ?>" value="<?php echo $tanggal_pembelian; ?>" />
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <label for="varchar">Tanggal Perolehan <?php echo form_error('tanggal_perolehan') ?></label>
                        <input type="text" class="form-control date_input" name="tanggal_perolehan" id="tanggal_perolehan" placeholder="<?= tgl_indo(date('Y-m-d')) ?>" value="<?php echo $tanggal_perolehan; ?>" />
                    </div>
                </div>
            </div>
 */ ?>
            <input type="hidden" name="id_kib_atb" value="<?php echo $id_kib_atb; ?>" />
            <button type="submit" class="btn btn-primary">Simpan</button>


            <?php if (empty($data_reklas)) : ?>
                <a href="<?php echo base_url('kib_atb') ?>" class="btn btn-default">Batalkan</a>
            <?php else : ?>
                <a href="<?php echo base_url('reklas/reklas_kode/index/batal_entri') ?>" class="btn btn-default">Batalkan</a>
            <?php endif; ?>

        </div>
    </form>
</section>

<?php /*
<!doctype html>
<html>

<head>
    <title>harviacode.com - codeigniter crud generator</title>
    <link rel="stylesheet" href="<?php echo base_url('assets/bootstrap/css/bootstrap.min.css') ?>" />
    <style>
        body {
            padding: 15px;
        }
    </style>
</head>

<body>
    <h2 style="margin-top:0px">Tbl_kib_atb <?php echo $button ?></h2>
    <form action="<?php echo $action; ?>" method="post">
        <div class="form-group">
            <label for="int">Id Pemilik <?php echo form_error('id_pemilik') ?></label>
            <input type="text" class="form-control" name="id_pemilik" id="id_pemilik" placeholder="Id Pemilik" value="<?php echo $id_pemilik; ?>" />
        </div>
        <div class="form-group">
            <label for="int">Id Kode Barang <?php echo form_error('id_kode_barang') ?></label>
            <input type="text" class="form-control" name="id_kode_barang" id="id_kode_barang" placeholder="Id Kode Barang" value="<?php echo $id_kode_barang; ?>" />
        </div>
        <div class="form-group">
            <label for="int">Id Kode Lokasi <?php echo form_error('id_kode_lokasi') ?></label>
            <input type="text" class="form-control" name="id_kode_lokasi" id="id_kode_lokasi" placeholder="Id Kode Lokasi" value="<?php echo $id_kode_lokasi; ?>" />
        </div>
        <div class="form-group">
            <label for="varchar">Nama Barang <?php echo form_error('nama_barang') ?></label>
            <input type="text" class="form-control" name="nama_barang" id="nama_barang" placeholder="Nama Barang" value="<?php echo $nama_barang; ?>" />
        </div>
        <div class="form-group">
            <label for="char">Nomor Register <?php echo form_error('nomor_register') ?></label>
            <input type="text" class="form-control" name="nomor_register" id="nomor_register" placeholder="Nomor Register" value="<?php echo $nomor_register; ?>" />
        </div>
        <div class="form-group">
            <label for="varchar">Judul Kajian Nama Software <?php echo form_error('judul_kajian_nama_software') ?></label>
            <input type="text" class="form-control" name="judul_kajian_nama_software" id="judul_kajian_nama_software" placeholder="Judul Kajian Nama Software" value="<?php echo $judul_kajian_nama_software; ?>" />
        </div>
        <div class="form-group">
            <label for="varchar">Tahun Perolehan <?php echo form_error('tahun_perolehan') ?></label>
            <input type="text" class="form-control" name="tahun_perolehan" id="tahun_perolehan" placeholder="Tahun Perolehan" value="<?php echo $tahun_perolehan; ?>" />
        </div>
        <div class="form-group">
            <label for="varchar">Asal Usul <?php echo form_error('asal_usul') ?></label>
            <input type="text" class="form-control" name="asal_usul" id="asal_usul" placeholder="Asal Usul" value="<?php echo $asal_usul; ?>" />
        </div>
        <div class="form-group">
            <label for="keterangan">Keterangan <?php echo form_error('keterangan') ?></label>
            <textarea class="form-control" rows="3" name="keterangan" id="keterangan" placeholder="Keterangan"><?php echo $keterangan; ?></textarea>
        </div>
        <div class="form-group">
            <label for="deskripsi">Deskripsi (Spesifikasi)<?php echo form_error('deskripsi') ?></label>
            <textarea class="form-control" rows="3" name="deskripsi" id="deskripsi" placeholder="Deskripsi"><?php echo $deskripsi; ?></textarea>
        </div>
        <div class="form-group">
            <label for="varchar">Kode Lokasi <?php echo form_error('kode_lokasi') ?></label>
            <input type="text" class="form-control" name="kode_lokasi" id="kode_lokasi" placeholder="Kode Lokasi" value="<?php echo $kode_lokasi; ?>" />
        </div>
        <div class="form-group">
            <label for="enum">Validasi <?php echo form_error('validasi') ?></label>
            <input type="text" class="form-control" name="validasi" id="validasi" placeholder="Validasi" value="<?php echo $validasi; ?>" />
        </div>
        <div class="form-group">
            <label for="datetime">Created At <?php echo form_error('created_at') ?></label>
            <input type="text" class="form-control" name="created_at" id="created_at" placeholder="Created At" value="<?php echo $created_at; ?>" />
        </div>
        <div class="form-group">
            <label for="datetime">Updated At <?php echo form_error('updated_at') ?></label>
            <input type="text" class="form-control" name="updated_at" id="updated_at" placeholder="Updated At" value="<?php echo $updated_at; ?>" />
        </div>
        <input type="hidden" name="id_kib_atb" value="<?php echo $id_kib_atb; ?>" />
        <button type="submit" class="btn btn-primary"><?php echo $button ?></button>
        <a href="<?php echo site_url('tbl_kib_atb') ?>" class="btn btn-default">Cancel</a>
    </form>
</body>

</html>
*/ ?>