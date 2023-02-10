<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 *  edit_column callback function in Codeigniter (Ignited Datatables)
 *
 * Grabs a value from the edit_column field for the specified field so you can
 * return the desired value.
 *
 * @access   public
 * @return   mixed
 */

if (!function_exists('tgl_indo')) {
  function tgl_indo($tanggal)
  {
    $bulan = array(
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
    if ((!empty($pecahkan[0])) and (!empty($pecahkan[1] != "00")) and (!empty($pecahkan[2]))) {
      return $pecahkan[2] . ' ' . $bulan[(int) $pecahkan[1]] . ' ' . $pecahkan[0];
    } else {
      return "-";
    }
  }
}


if (!function_exists('bulan_indo')) {
  function bulan_indo($var)
  {
    $bulan = array(
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
      return $bulan[$var];
  }
}

if (!function_exists('hari_ini')) {
  function hari_ini()
  {
    $hari = date("D");

    switch ($hari) {
      case 'Sun':
        $hari_ini = "Minggu";
        break;

      case 'Mon':
        $hari_ini = "Senin";
        break;

      case 'Tue':
        $hari_ini = "Selasa";
        break;

      case 'Wed':
        $hari_ini = "Rabu";
        break;

      case 'Thu':
        $hari_ini = "Kamis";
        break;

      case 'Fri':
        $hari_ini = "Jumat";
        break;

      case 'Sat':
        $hari_ini = "Sabtu";
        break;

      default:
        $hari_ini = "Tidak di ketahui";
        break;
    }

    return  $hari_ini;
  }
}

if (!function_exists('hari')) {
  function hari($tgl)
  {
    $hari = date("D", strtotime($tgl));

    switch ($hari) {
      case 'Sun':
        $hari_ini = "Minggu";
        break;

      case 'Mon':
        $hari_ini = "Senin";
        break;

      case 'Tue':
        $hari_ini = "Selasa";
        break;

      case 'Wed':
        $hari_ini = "Rabu";
        break;

      case 'Thu':
        $hari_ini = "Kamis";
        break;

      case 'Fri':
        $hari_ini = "Jumat";
        break;

      case 'Sat':
        $hari_ini = "Sabtu";
        break;

      default:
        $hari_ini = " ";
        break;
    }

    return  $hari_ini;
  }
}
 

if (!function_exists('tgl_inter')) {
  function tgl_inter($tanggal)
  {
    $bulan = array(
      'Januari' => '01',
      'Februari' => '02',
      'Maret' => '03',
      'April' => '04',
      'Mei' => '05',
      'Juni' => '06',
      'Juli' => '07',
      'Agustus' => '08',
      'September' => '09',
      'Oktober' => '10',
      'November' => '11',
      'Desember' => '12',
    );
    $pecahkan = explode(' ', $tanggal);
    // die(json_encode($pecahkan));
    if ((!empty($pecahkan[0])) and (!empty($pecahkan[1] != "00")) and (!empty($pecahkan[2]))) {
      return strtotime($pecahkan[0] . '-' . $bulan[$pecahkan[1]] . '-' . $pecahkan[2]);
    } else {
      return "-";
    }
  }
}

function my_format_number($number)
{
  if (!empty($number) and ($number != "-"))
    return number_format($number, 0, ",", ".");
  else
    return $number;
}

function to_float($data)
{
  return str_replace(['.', ','], ['', '.'], $data);
}

if (!function_exists('remove_star')) {
  function remove_star($parameter = null)
  {
    $temp = explode('.', $parameter);
    $temp2 = null;
    foreach ($temp as $key => $value) {
      if ($value != '*') {
        $temp2 .= $value . '.';
      }
    }
    return rtrim($temp2, '.');
  }
}

if (!function_exists('get_intra_extra')) {
  function get_intra_extra($kode_lokasi = null, $kode_intra_extra = null)
  {
    $temp = explode('.', $kode_lokasi);
    $temp2 = null;
    foreach ($temp as $key => $value) {
      if ($key == 1) $temp2 .= $kode_intra_extra . '.';
      else $temp2 .= $value . '.';
    }
    return rtrim($temp2, '.');
  }
}

if (!function_exists('cek_register_kosong')) {
  function cek_register_kosong($arr_data)
  {
    $temp = 0;

    $result = '';
    $value2 = '';
    foreach ($arr_data as $key => $value) {
      if ((int) $value != $temp) {
        if ($key <> 0) $result .= $arr_data[($key - 1)] . ', ';

        $result .= $value . '-';

        $value2 = $value;
      }
      $temp = (int) $value + 1;
    }

    $jumlah = count($arr_data);
    if ($value2 != $arr_data[$jumlah - 1]) {
      $result .= $arr_data[$jumlah - 1];
    }

    $result = rtrim($result, '-');
    return $result;
  }
}



if (!function_exists('set_null')) {
  function set_null($data)
  {
    return $data == '' ? null : $data;
  }
}



if (!function_exists('icon_checklist')) {
function icon_checklist($status)
{
  if($status == 'y')
  {
    return '<i style=\'color: #ec971f;\' class=\'fa fa-check-circle\'></i>';
  }
  if($status == 'v')
  {
    return '<i style=\'color: green;\' class=\'fa fa-check-circle\'></i>';
  }
  else{
    return '<i style=\'color: red;\' class=\'fa fa-times-circle\'></i>';
  }
}
}

if (!function_exists('Terbilang')) {
  function Terbilang($number)
  {
      $words = "";

      $arr_number = array(

          "",

          "satu",

          "dua",

          "tiga",

          "empat",

          "lima",

          "enam",

          "tujuh",

          "delapan",

          "sembilan",

          "sepuluh",

          "sebelas"
      );

      if ($number < 12) {

          $words = " " . $arr_number[$number];
      } else if ($number < 20) {

          $words = Terbilang($number - 10) . " belas";
      } else if ($number < 100) {

          $words = Terbilang($number / 10) . " puluh " . Terbilang($number % 10);
      } else if ($number < 200) {

          $words = "seratus " . Terbilang($number - 100);
      } else if ($number < 1000) {

          $words = Terbilang($number / 100) . " ratus " . Terbilang($number % 100);
      } else if ($number < 2000) {

          $words = "seribu " . Terbilang($number - 1000);
      } else if ($number < 1000000) {

          $words = Terbilang($number / 1000) . " ribu " . Terbilang($number % 1000);
      } else if ($number < 1000000000) {

          $words = Terbilang($number / 1000000) . " juta " . Terbilang($number % 1000000);
      } else if ($number < 1000000000000) {
          $words = Terbilang($number / 1000000000) . " milyar" . Terbilang(fmod($number, 1000000000));
      } else if ($number < 1000000000000000) {
          $words = Terbilang($number / 1000000000000) . " trilyun" . Terbilang(fmod($number, 1000000000000));
      } else {

          $words = "undefined";
      }

      return ucwords($words);
  }
}
