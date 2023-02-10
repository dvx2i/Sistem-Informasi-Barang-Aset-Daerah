<script type='text/javascript' 
   src='https://www.bing.com/maps/sdk/mapcontrol?callback=GetMap' 
   async defer>
 </script>
  <script type='text/javascript'>
    var map, infobox;
    function GetMap() {
        // Seting Map Options
  map = new Microsoft.Maps.Map('#myMap', {
            credentials:"U4VD6Xi1NuVkAaN8KvJF~dereRmfzkm5VdVorK5lmlA~Ar4MuDpGzRmqdUtbXYvjm31t06tAU-400GnsVAY8Zna23hb05WjeiHiszdHOEAXU",
   center: new Microsoft.Maps.Location(<?php echo $latitute ?>, <?php echo $longitute ?>),
            mapTypeId: Microsoft.Maps.MapTypeId.street,
            zoom: 15
        }); 

        //Membuat jendela infobox berada di tengah pin (tidak ditampilkan)
        infobox = new Microsoft.Maps.Infobox(map.getCenter(), {
            visible: false
        });
        //Assign infobox pada variabel map
        infobox.setMap(map);
        
  //Create sebuah pin/marker pada kordinat banda aceh
  var center = map.getCenter(); 
        var pin = new Microsoft.Maps.Pushpin(center);

        //Menyimpan informasi metadata pada pushpin.
        pin.metadata = {
            title: 'Keterangan',
            description: '<?php echo $deskripsi;?>'
        };

        // Menambah penanganan event click pada pushpin
        Microsoft.Maps.Events.addHandler(pin, 'click', pushpinClicked);

        //Memasang entitas pushpin pada peta
        map.entities.push(pin);
    }

    // Fungsi yang menampilkan infobox ketika pushpin diklik
 function pushpinClicked(e) {        
        if (e.target.metadata) {
            //Menambah metadata pushpin pada option di infobox
            infobox.setOptions({
                location: e.target.getLocation(),
                title: e.target.metadata.title,
                description: e.target.metadata.description,
                visible: true
            });
        }
    }
    </script> 
 <section class="content">
    <h2 style="margin-top:0px">Detail <?= $menu;?> </h2>
    <table class="table">
      <tr><td>Kode Barang</td><td><?php echo $kode_barang; ?></td></tr>
      <tr><td>Nama Barang</td><td><?php echo $nama_barang; ?></td></tr>
      <tr><td>Nomor Register</td><td><?php echo $nomor_register; ?></td></tr>
      <tr><td>Luas</td><td><?php echo $luas; ?></td></tr>
      <tr><td>Tahun Pengadaan</td><td><?php echo $tahun_pengadaan; ?></td></tr>
      <tr><td>Letak Alamat</td><td><?php echo $letak_alamat; ?></td></tr>
      <tr><td>Status Hak</td><td><?php echo $status_hak; ?></td></tr>
      <tr><td>Sertifikat Tanggal</td><td><?php echo $sertifikat_tanggal; ?></td></tr>
      <tr><td>Sertifikat Nomor</td><td><?php echo $sertifikat_nomor; ?></td></tr>
      <tr><td>Penggunaan</td><td><?php echo $penggunaan; ?></td></tr>
      <tr><td>Asal Usul</td><td><?php echo $asal_usul; ?></td></tr>
      <tr><td>Harga</td><td><?php echo $harga; ?></td></tr>
      <tr><td>Keterangan</td><td><?php echo $keterangan; ?></td></tr>
      <tr><td>Deskripsi</td><td><?php echo $deskripsi; ?></td></tr>
      <tr><td>Kode Lokasi</td><td><?php echo $kode_lokasi; ?></td></tr>
	  <tr><td colspan="2"><div id="myMap" style="width:400px; height:400px"></div></div></td></tr>
      <tr><td></td><td><a href="<?php echo base_url('kib_a') ?>" class="btn btn-default">Batalkan</a></td></tr>
    </table>
  </section>
