<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


if ( ! function_exists('barang'))
{
  function barang($id_penghapusan=null)
  {
    // Get a reference to the controller object
    $CI = get_instance();
    // You may need to load the model if it hasn't been pre-loaded
    // $CI->load->model('my_model');
    $CI->db->select('A.*,B.kib_name, B.nomor_register, C.nama_barang');
    $CI->db->from('tbl_penghapusan_barang A');
    $CI->db->join('view_kib B', 'A.kode_jenis=B.kode_jenis and A.id_kib=B.id_kib');
    $CI->db->join('tbl_kode_barang C', 'B.id_kode_barang=C.id_kode_barang','left');
    $CI->db->where('A.id_penghapusan',$id_penghapusan);
    $view_kib = $CI->db->get()->result();

    $list_barang="
      <table class='table table-bordered table-striped' id=''>
        <thead>
          <tr>
            <th width='10%'>No</th>
            <th width='15%'>KIB</th>
            <th width='50%'>Nama Barang</th>
            <th>No Register</th>
            <th>Status Diterima</th>

          </tr>
        </thead>
        <tbody>";

    foreach ($view_kib as $key => $value) {
      $no=$key+1;
      $status_diterima = null;
      if ($value->status_diterima == '1') {
        $status_diterima = "<span class='fa fa-clock-o text-orange'></span>";
      }
      else if ($value->status_diterima == '2') {
        $status_diterima = "<span class='fa fa-check text-success'></span>";
      }
      else if ($value->status_diterima == '3'){
        $status_diterima = "<span class='fa fa-remove text-red'></span>";
      }
      // $status_validasi = $value->status_validasi == '2'?"<span class='fa fa-check text-success'></span>":"<span class='fa fa-clock-o text-orange'></span>";
      $list_barang .=
        "<tr>
          <td>".$no."</td>
          <td>".$value->kib_name."</td>
          <td>".$value->nama_barang."</td>
          <td>".$value->nomor_register."</td>
          <td style='text-align:center;'>".$status_diterima."</td>
        </tr>";
    }
    $list_barang .=
    " </tbody>
    </table>";
    return $list_barang;
  }
}


if ( ! function_exists('barang_pengecekan'))
{
  function barang_pengecekan($id_penghapusan=null)
  {
    $CI = get_instance();
    $CI->db->select('A.*,B.kib_name, B.nomor_register, C.nama_barang');
    $CI->db->from('tbl_penghapusan_barang A');
    $CI->db->join('view_kib B', 'A.kode_jenis=B.kode_jenis and A.id_kib=B.id_kib');
    $CI->db->join('tbl_kode_barang C', 'B.id_kode_barang=C.id_kode_barang','left');
    $CI->db->where('A.id_penghapusan',$id_penghapusan);
    // $CI->db->where('A.status_pengajuan','2');
    $view_kib = $CI->db->get()->result();

    $list_barang="
      <table class='table table-bordered table-striped' id=''>
        <thead>
          <tr>
            <th width='10%'>No</th>
            <th width='15%'>KIB</th>
            <th width='50%'>Nama Barang</th>
            <th>No Register</th>

            <th>Status Diterima</th>
          </tr>
        </thead>
        <tbody>";

    foreach ($view_kib as $key => $value) {
      $no=$key+1;
      $status_diterima = null;
      if ($value->status_diterima == '1' or $value->status_diterima == '') {
        $status_diterima = "<span class='fa fa-clock-o text-orange'></span>";
      }
      else if ($value->status_diterima == '2') {
        $status_diterima = "<span class='fa fa-check text-success'></span>";
      }
      else if ($value->status_diterima == '3'){
        $status_diterima = "<span class='fa fa-remove text-red'></span>";
      }

      $list_barang .=
        "<tr>
          <td>".$no."</td>
          <td>".$value->kib_name."</td>
          <td>".$value->nama_barang."</td>
          <td>".$value->nomor_register."</td>

          <td style='text-align:center;'>".$status_diterima."</td>
        </tr>";
    }
    $list_barang .=
    " </tbody>
    </table>";
    return $list_barang;
  }
}


if ( ! function_exists('pengajuan_action'))
{
  function pengajuan_action($status_proses,$id_penghapusan)
  {
    $return='';
    $hapus = anchor(base_url('penghapusan/pengajuan/delete/'.$id_penghapusan),'<button class="btn btn-danger btn-sm" title="Hapus"><span class="fa fa-trash"></span></button>','onclick="javasciprt: return confirm(\'Yakin dihapus ?\')"');
    $detail = anchor(base_url('penghapusan/pengajuan/detail/'.$id_penghapusan),'<button class="btn btn-info btn-sm" title="Detail"><span class="fa fa-search"></span></button>');
    $upload = anchor(base_url('penghapusan/pengajuan/cetak/'.$id_penghapusan),'<button class="btn btn-success btn-sm" title="Cetak BA Penarikan"><span class="fa fa-file"></span></button>');
    if ($status_proses=="1") {
      $return=$detail.'&nbsp'.$upload.'&nbsp'.$hapus;
    }else if ($status_proses=="2" || $status_proses=="3"){
      $return=$detail.'&nbsp'.$upload;
    }
    return $return;
  }
}

if ( ! function_exists('laporan_action'))
{
  function laporan_action($id_pengahapusan_lampiran,$id_penghapusan)
  {
    $return='';
    $detail = anchor(base_url('penghapusan/laporan/detail/'.$id_pengahapusan_lampiran),'<button class="btn btn-info btn-sm" title="Detail"><span class="fa fa-search"></span></button>');
    $lampiran = anchor(base_url('penghapusan/laporan/create/'.$id_penghapusan),'<button class="btn btn-success btn-sm" title="Buat Lampiran"><span class="fa fa-file"></span></button>');
    if ($id_pengahapusan_lampiran <> 0) {
      $return=$detail.'&nbsp';
    }else{
      $return=$lampiran;
    }
    return $return;
  }
}

if ( ! function_exists('lampiran_action'))
{
  function lampiran_action($id_penghapusan,$nama_paket)
  {
    $return='';
    $detail = '<a data-toggle="modal" data-id="'.$id_penghapusan.'" data-paket="'.$nama_paket.'" class="btn btn-info btn-sm detail" title="Detail"><span class="fa fa-search"></span></a>';
   
    $return=$detail.'&nbsp';
    
    return $return;
  }
}

if ( ! function_exists('pengajuan_keterangan'))
{
  function pengajuan_keterangan($status_diterima,$id_penghapusan)
  {
    $return='<h4 class="text-orange">Proses penghapusan.</h4>';
    if ($status_diterima=="2") {
      $return='<h4 class="text-success">Penghapusan selesai.</h4>';
    }
    return $return;
  }
}

if ( ! function_exists('status_proses'))
{
  function status_proses($status_proses)
  {
    // $return='';
    // if ($status_proses=="1") {
    //   $return='<h4 class="text-orange" style="margin:0px;">Pengajuan Barang.</h4>';
    // }
    // else if ($status_proses=="2" || $status_proses=="3") {
    //   $return='<h4 class="text-success" style="margin:0px;">Barang Diterima.</h4>';
    // }

    $return = '<span style="font-size: 12px;" class="label label-warning"><i class="fa fa-clock-o"></i> Pengajuan Barang</span>';
    if ($status_proses == "2" || $status_proses=="3") {
      $return = '<span style="font-size: 12px;" class="label label-success"><i class="fa fa-check"></i> Barang Diterima</span>';
    }
    return $return;
  }
}

if ( ! function_exists('pengecekan_barang_action')){
  function pengecekan_barang_action($id_penghapusan, $status_proses){
    $cek=anchor(base_url('penghapusan/pengecekan_barang/form_pengecekan/'.$id_penghapusan),'<button title="Cek Barang" class="btn btn-sm btn-warning"><span class="fa fa-check-square-o"><span></button>');
    $detail=anchor(base_url('penghapusan/pengecekan_barang/detail/'.$id_penghapusan),'<button title="Detail" class="btn btn-sm btn-info" ><span class="fa fa-search"></span></button>');
    $hapus=anchor(base_url('penghapusan/pengecekan_barang/delete/'.$id_penghapusan),'<button title="Hapus" class="btn btn-sm btn-danger" ><span class="fa fa-trash"></span></button>');
    $return='';
    if ($status_proses=='1') {
      $return.=$detail.'&nbsp'.$cek.'&nbsp'.$hapus;
    }
    else if ($status_proses=='2') {
      $return.=$detail.'&nbsp'.$cek;
    }
    return $return;
  }
}


if ( ! function_exists('status_penghapusan')){
  function status_penghapusan($status_diterima){
    $return = null;
    if ($status_diterima == '1' or $status_diterima == '') {
      $return = "<span class='fa fa-clock-o text-orange'></span>";
    }
    else if ($status_diterima == '2') {
      $return = "<span class='fa fa-check text-success'></span>";
    }
    else if ($status_diterima == '3'){
      $return = "<span class='fa fa-remove text-red'></span>";
    }
    return $return;
  }
}

// if ( ! function_exists('detail_penghapusan_barang')){
//   function detail_penghapusan_barang($nomor_sk){
//     $detail = anchor(base_url('penghapusan/penghapusan_barang/detail/'.$nomor_sk),'<span class="fa fa-search">Detail</span>');
//     return $detail;
//   }
// }

if ( ! function_exists('penghapusan_barang_action')){
  function penghapusan_barang_action($id_pengahapusan_lampiran){
    $detail = anchor(base_url('penghapusan/penghapusan_barang/detail/'.$id_pengahapusan_lampiran),"<button title='Detail' class='btn btn-info btn-sm'><span class='fa fa-search'></span></button>");
    $hapus = "<a href='javascript:' id_penghapusan_lampiran='".$id_pengahapusan_lampiran."' class='delete'><button title='Hapus' class='btn btn-danger btn-sm'><span class='fa fa-trash'></span></button></a>";
    
    $validasi = "<a href='javascript:' id_penghapusan_lampiran='".$id_pengahapusan_lampiran."' class='validasi_penghapusan'><button title='Validasi' class='btn btn-success btn-sm'><span class='fa fa-check'></span></button></a>";
    
    return $detail.'&nbsp'.$hapus.'&nbsp'.$validasi;
  }
}


if ( ! function_exists('tgl_indo')){
  function tgl_indo($tanggal){
  	$bulan = array (
  		1 =>   'Januari',
  		'Februari',
  		'Maret',
  		'April',
  		'Mei',
  		'Juni',
  		'Juli',
  		'Agustus',
  		'September',
  		'Oktober',
  		'November',
  		'Desember'
  	);
  	$pecahkan = explode('-', $tanggal);

  	// variabel pecahkan 0 = tahun
  	// variabel pecahkan 1 = bulan
  	// variabel pecahkan 2 = tanggal
  	return $pecahkan[2] . ' ' . $bulan[ (int)$pecahkan[1] ] . ' ' . $pecahkan[0];
  }
}


if ( ! function_exists('tgl_inter')){
  function tgl_inter($tanggal){
  	$bulan = array (
  		'Januari' => '01',
  		'Februari' => '02',
  		'Maret' => '03',
  		'April' => '04',
  		'Mei'=> '05',
  		'Juni' => '06',
  		'Juli' => '07',
  		'Agustus' => '08',
  		'September'=> '09',
  		'Oktober' => '10',
  		'November' => '11',
  		'Desember' => '12',
  	);
  	$pecahkan = explode(' ', $tanggal);
  	return strtotime($pecahkan[0] . '-' . $bulan[$pecahkan[1] ] . '-' . $pecahkan[2]);
  }
}
