<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

if ( ! function_exists('pengajuan_action'))
{
  function pengajuan_action($status,$id_kapitalisasi)
  {
    $return='';
    $hapus = anchor(base_url('kapitalisasi/pengajuan/delete/'.$id_kapitalisasi),'<button class="btn btn-danger" title="Hapus"><span class="fa fa-trash"></span></button>','onclick="javasciprt: return confirm(\'Yakin dihapus ?\')"');
    if ($status=="1") {
      $return=$hapus;
    }
    return $return;
  }
}

if ( ! function_exists('jenis')){
  function jenis($jenis){
    $return='';
    if ($jenis == 'kapitalisasi') {
      $return='<label>Kapitalisasi</label>';
    }
    else if ($jenis == 'koreksi_tambah') {
      $return='<label class="text-success">Koreksi Tambah</label>';
    }
    else if ($jenis == 'koreksi_kurang') {
      $return='<label class="text-danger">Koreksi Kurang</label>';
    }

    return $return;
  }
}

if (!function_exists('format_float')) {
	function format_float($number = '')
	{
	  return number_format($number, 2, ',', '.');
	}
  }

if ( ! function_exists('validasi_action')){
  function validasi_action($status,$id_kapitalisasi){
    $hapus = anchor(base_url('kapitalisasi/validasi/delete/'.$id_kapitalisasi),'<button class="btn btn-danger" title="Hapus"><span class="fa fa-trash"></span></button>','onclick="javasciprt: return confirm(\'Yakin dihapus ?\')"');
    $validasi = "<a href='javascript:' id_kapitalisasi='".$id_kapitalisasi."' class='validasi_kapitalisasi'><button title='Validasi' class='btn btn-success'><span class='fa fa-check'></span></button></a>";
    return $hapus.'&nbsp'.$validasi;
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
