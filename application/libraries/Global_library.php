<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Global_library
{

	public function some_method()
	{
		echo "hallo";
	}

	public function get_kode_barang($kode_barang)
	{
		//kode barang
		$kode_barang = explode('.', $kode_barang);

		$kode_barang[0] = isset($kode_barang[0]) ? $kode_barang[0] : null;
		$kode_barang[1] = isset($kode_barang[1]) ? $kode_barang[1] : null;
		$kode_barang[2] = isset($kode_barang[2]) ? $kode_barang[2] : null;
		$kode_barang[3] = isset($kode_barang[3]) ? $kode_barang[3] : null;
		$kode_barang[4] = isset($kode_barang[4]) ? $kode_barang[4] : null;
		$kode_barang[5] = isset($kode_barang[5]) ? $kode_barang[5] : null;
		$kode_barang[6] = isset($kode_barang[6]) ? $kode_barang[6] : null;

		$kode_jenis = $kode_barang[0] . '.' . $kode_barang[1] . '.' . $kode_barang[2];
		$kode_objek = $kode_jenis . '.' . $kode_barang[3];
		$kode_rincian_objek = $kode_objek . '.' . $kode_barang[4];
		$kode_sub_rincian_objek = $kode_rincian_objek . '.' . $kode_barang[5];
		$kode_sub_sub_rincian_objek = $kode_sub_rincian_objek . '.' . $kode_barang[6];
		// return array(
		// 	'kode_jenis' => $kode_jenis.'.*'.'.*'.'.*'.'.*',
		// 	'kode_objek' => $kode_objek.'.*'.'.*'.'.*',
		// 	'kode_rincian_objek' => $kode_rincian_objek.'.*'.'.*',
		// 	'kode_sub_rincian_objek'=> $kode_sub_rincian_objek.'.*',
		// 	'kode_sub_sub_rincian_objek'=> $kode_sub_sub_rincian_objek,
		// );
		return array(
			'kode_jenis' => $kode_jenis,
			'kode_objek' => $kode_objek,
			'kode_rincian_objek' => $kode_rincian_objek,
			'kode_sub_rincian_objek' => $kode_sub_rincian_objek,
			'kode_sub_sub_rincian_objek' => $kode_sub_sub_rincian_objek,
		);
	}

	public function explode_kode_barang($kode_barang)
	{
		//kode barang
		$kode_barang = explode('.', $kode_barang);

		$kode_barang[0] = isset($kode_barang[0]) ? $kode_barang[0] : null;
		$kode_barang[1] = isset($kode_barang[1]) ? $kode_barang[1] : null;
		$kode_barang[2] = isset($kode_barang[2]) ? $kode_barang[2] : null;
		$kode_barang[3] = isset($kode_barang[3]) ? $kode_barang[3] : null;
		$kode_barang[4] = isset($kode_barang[4]) ? $kode_barang[4] : null;
		$kode_barang[5] = isset($kode_barang[5]) ? $kode_barang[5] : null;
		$kode_barang[6] = isset($kode_barang[6]) ? $kode_barang[6] : null;

		return array(
			'kode_kelompok' => $kode_barang[1],
			'kode_jenis' => $kode_barang[2],
			'kode_objek' => $kode_barang[3],
			'kode_rincian_objek' => $kode_barang[4],
			'kode_sub_rincian_objek' => $kode_barang[5],
			'kode_sub_sub_rincian_objek' => $kode_barang[6],
		);
	}

	public function get_kode_lokasi($kode_lokasi)
	{
		//kode lokasi
		$kode_lokasi = explode('.', $kode_lokasi);
		$kode_lokasi[0] = isset($kode_lokasi[0]) ? $kode_lokasi[0] : null;
		$kode_pemilik = $kode_lokasi[0];
		return array(
			'kode_pemilik' => $kode_pemilik,
		);
	}
}
