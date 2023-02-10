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
      <tr><td>Konstruksi</td><td><?php echo $konstruksi; ?></td></tr>
      <tr><td>Panjang (MM)</td><td><?php echo $panjang_km; ?></td></tr>
      <tr><td>Lebar (M)</td><td><?php echo $lebar_m; ?></td></tr>
      <tr><td>Luas M2</td><td><?php echo $luas_m2; ?></td></tr>
      <tr><td>Letak Lokasi</td><td><?php echo $letak_lokasi; ?></td></tr>
      <tr><td>Dokumen Tanggal</td><td><?php echo $dokumen_tanggal; ?></td></tr>
      <tr><td>Dokumen Nomor</td><td><?php echo $dokumen_nomor; ?></td></tr>
      <tr><td>Status Tanah</td><td><?php echo $status_tanah; ?></td></tr>
      <tr><td>Kode Tanah</td><td><?php echo $kode_tanah; ?></td></tr>
      <tr><td>Asal Usul</td><td><?php echo $asal_usul; ?></td></tr>
      <tr><td>Harga</td><td><?php echo $harga; ?></td></tr>
      <tr><td>Kondisi</td><td><?php echo $kondisi; ?></td></tr>
      <tr><td>Keterangan</td><td><?php echo $keterangan; ?></td></tr>
      <tr><td>Deskripsi</td><td><?php echo $deskripsi; ?></td></tr>
	  <tr><td colspan="2"><div id="myMap" style="width:400px; height:400px"></div></div></td></tr>
      <tr><td></td><td><a href="<?php echo base_url('kib_d') ?>" class="btn btn-default">Batalkan</a></td></tr>
    </table>
  </section>
