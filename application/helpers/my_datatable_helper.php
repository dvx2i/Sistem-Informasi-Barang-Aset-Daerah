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

if (!function_exists('check_kelamin')) {
  function check_kelamin($status = '')
  {
    return ($status == 'l') ? 'Laki - laki' : 'Perempuan';
  }
}

if (!function_exists('date_time')) {
  function date_time($date = '')
  {
    return tgl_indo($date);
    // return date('d M Y', strtotime($date));
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

if (!function_exists('format_number')) {
  function format_number($number = '')
  {
    $number = str_replace("cc", "", $number);
    $number = str_replace("CC", "", $number);
    // die($number);
    if (!empty($number) and ($number != "-"))

      // return number_format($number, 0, 0, '.');
      return number_format($number, 0, ",", ".");
    else
      return $number;
  }
}

if (!function_exists('format_float')) {
  function format_float($number = '')
  {
    return number_format($number, 2, ',', '.');
  }
}

if (!function_exists('registrasi_status')) {
  function registrasi_status($reg = null, $status = null)
  {
    return $reg != '000000' && $status == 'y' ? 'checked' : '';
  }
}

if (!function_exists('status_validasi_kib')) {
  function status_validasi_kib($status_validasi,$reject_note)
  {
    $status = '';
    if ($status_validasi == 1) {
      $status = "<span class='fa fa-clock-o text-orange'></span>";
    } else if ($status_validasi == 2) {
      $status = "<span class='fa fa-check text-green'></span>";
    } else if ($status_validasi == 3) {
      $status = "<span class='fa fa-close text-danger'></span> (".$reject_note.")";
    }
    return $status;
  }
}

if (!function_exists('get_action')) {
  function get_action($role = null, $controler_name = null, $id_kib = null, $validasi = null)
  {
    if ($validasi == '1' || $validasi == '3') {
      return anchor(base_url($controler_name . '/read/' . encrypt_url($id_kib)), '<button class="btn btn-info btn-xs" title="Detail"><span class="fa fa-search"><span></button>') . " | " . anchor(base_url($controler_name . '/update/' . encrypt_url($id_kib)), '<button class="btn btn-success btn-xs" title="Perbaharui"><span class="fa fa-edit"><span></button>') . " | " . anchor(base_url($controler_name . '/delete/' . encrypt_url($id_kib)), '<button class="btn btn-danger btn-xs" title="Hapus"><span class="fa fa-trash"><span></button>', 'onclick="javasciprt: return confirm(\'Yakin Ingin Dihapus ?\')"');
    } else if ($role == '1') {
      return anchor(base_url($controler_name . '/read/' . encrypt_url($id_kib)), '<button class="btn btn-info btn-xs" title="Detail"><span class="fa fa-search"><span></button>') . " | " .
        anchor(base_url($controler_name . '/update/' . encrypt_url($id_kib)), '<button class="btn btn-success btn-xs" title="Perbaharui"><span class="fa fa-edit"><span></button>'). " | " .
      anchor(base_url($controler_name.'/delete/'.encrypt_url($id_kib)),'<button class="btn btn-danger btn-xs" title="Hapus"><span class="fa fa-trash"><span></button>','onclick="javasciprt: return confirm(\'Yakin Ingin Dihapus ?\')"');

    } else if ($validasi == '2') {
      return anchor(base_url($controler_name . '/read/' . encrypt_url($id_kib)), '<button class="btn btn-info btn-xs" title="Detail"><span class="fa fa-search"><span></button>') . " | " .
        anchor(base_url($controler_name . '/update/' . encrypt_url($id_kib)), '<button class="btn btn-success btn-xs" title="Perbaharui"><span class="fa fa-edit"><span></button>');
    }
  }
}


if (!function_exists('get_action_stock_opname')) {
  function get_action_stock_opname($role = null, $locked_admin = null, $locked_skpd = null, $id = null)
  {
    if ($locked_admin == '2') {
      return '<button class="btn btn-danger btn-sm"" data-id="'.encrypt_url($id).'" data-skpd="'.$locked_skpd.'" data-admin="'.$locked_admin.'" id="btn-unlock" title="Terkunci"><span class="fa fa-lock"><span></button>';
    } else if ($locked_admin == '1') {
      return '<button class="btn btn-warning btn-sm" data-id="'.encrypt_url($id).'" data-skpd="'.$locked_skpd.'" data-admin="'.$locked_admin.'"  id="btn-unlock" title="Terkunci"><span class="fa fa-lock"><span></button>';
    } else if ($locked_admin == '0') {
      return '<button class="btn btn-success btn-sm" data-id="'.encrypt_url($id).'" data-skpd="'.$locked_skpd.'" data-admin="'.$locked_admin.'" id="btn-lock" title="Belum Terkunci"><span class="fa fa-unlock"><span></button>';
    }
  }
}

if (!function_exists('get_action_stock_opname_kib')) {
  function get_action_stock_opname_kib($id = null)
  {
      return '<button class="btn btn-danger btn-sm"" data-id="'.$id.'" id="btn-delete" title="Hapus"><span class="fa fa-trash"><span> Hapus</button>';
    
  }
}

if (!function_exists('validasi_kib_action')) {
  function validasi_kib_action($id_kib = null, $controler_name = null)
  {
      return '<a href="'.site_url($controler_name.'/validasi/read/'.$id_kib).'" class="btn btn-info btn-sm" title="Detail"><span class="fa fa-search"><span></a>' . " | " .
             '<button type="button" data-id="' . $id_kib . '" class="btn btn-success btn-sm validasi" title="Validasi"><span class="fa fa-check"><span></button>'. " | " .
             '<button type="button" data-id="' . $id_kib . '" class="btn btn-danger btn-sm reject" title="Tolak"><span class="fa fa-times"><span></button>';
  }
}

//MUTASI
if (!function_exists('barang')) {
  function barang($id_mutasi = null)
  {
    // Get a reference to the controller object
    $CI = get_instance();
    // You may need to load the model if it hasn't been pre-loaded
    // $CI->load->model('my_model');
    $CI->db->select('A.*,B.kib_name, B.nomor_register, C.nama_barang');
    $CI->db->from('tbl_mutasi_barang A');
    $CI->db->join('view_kib B', 'A.kode_jenis=B.kode_jenis and A.id_kib=B.id_kib');
    $CI->db->join('tbl_kode_barang C', 'B.id_kode_barang=C.id_kode_barang', 'left');
    $CI->db->where('A.id_mutasi', $id_mutasi);
    $view_kib = $CI->db->get()->result();

    $list_barang = "
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
      $no = $key + 1;
      $status_diterima = null;
      if ($value->status_diterima == '1') {
        $status_diterima = "<span class='fa fa-clock-o text-orange'></span>";
      } else if ($value->status_diterima == '2') {
        $status_diterima = "<span class='fa fa-check text-success'></span>";
      } else if ($value->status_diterima == '3') {
        $status_diterima = "<span class='fa fa-remove text-red'></span>";
      }
      // $status_validasi = $value->status_validasi == '2'?"<span class='fa fa-check text-success'></span>":"<span class='fa fa-clock-o text-orange'></span>";
      $list_barang .=
        "<tr>
          <td>" . $no . "</td>
          <td>" . $value->kib_name . "</td>
          <td>" . $value->nama_barang . "</td>
          <td>" . $value->nomor_register . "</td>
          <td style='text-align:center;'>" . $status_diterima . "</td>
        </tr>";
    }
    $list_barang .=
      " </tbody>
    </table>";
    return $list_barang;
  }
}


if (!function_exists('status_diterima')) {
  function status_diterima($status_diterima)
  {
    $return = null;
    if ($status_diterima == '1') {
      $return = "<span class='fa fa-clock-o text-orange'></span>";
    } else if ($status_diterima == '2') {
      $return = "<span class='fa fa-check text-success'></span>";
    } else if ($status_diterima == '3') {
      $return = "<span class='fa fa-remove text-red'></span>";
    }

    return $return;
  }
}

if (!function_exists('encrypt_id')) {
  function encrypt_id($id = '')
  {
    return encrypt_url($id);
  }
}

if (!function_exists('pengajuan_action')) {
  function pengajuan_action($status_validasi, $id_mutasi)
  {
    $return = '';
    $delete = '';
    $detail = '';
    if ($status_validasi <> "2") {
      $delete = anchor(base_url('mutasi/pengajuan/delete/' . encrypt_url($id_mutasi)), '<button title="Hapus" class="btn btn-danger btn-sm"><span class="fa fa-trash" ></span></button>', 'onclick="javasciprt: return confirm(\'Yakin dihapus ?\')"');
    }
    $detail = anchor(base_url('mutasi/pengajuan/detail/' . encrypt_url($id_mutasi)), '<button title="Detail" class="btn btn-info  btn-sm"><span class="fa fa-search" ></span></button>');

    if ($delete) {
      $return = $detail . '&nbsp' . $delete;
    } else {
      $return = $detail;
    }





    return $return;
  }
}

if (!function_exists('pengajuan_keterangan')) {
  function pengajuan_keterangan($status_validasi, $id_mutasi)
  {
    $return = '<h4 class="text-orange" style="margin:0px;">Proses mutasi.</h4>';
    if ($status_validasi == "2") {
      $return = '<h4 class="text-success" style="margin:0px;">Mutasi selesai.</h4>';
    }
    return $return;
  }
}

if (!function_exists('status_proses')) {
  function status_proses($status_proses)
  {
    $return = '';
    /*
    if ($status_proses=="1") {
      $return='<h4 class="text-orange" style="margin:0px;">Persetujuan SKPD Internal.</h4>';
    }
    else if ($status_proses=="2") {
      $return='<h4 class="text-orange" style="margin:0px;">Persetujuan SKPD Eksternal.</h4>';
    }    
    else if ($status_proses=="3") {
      $return='<h4 class="text-orange" style="margin:0px;">Penerimaan Barang/Cek Lis.</h4>';
    }    
    else if ($status_proses=="4") {
      $return='<h4 class="text-orange" style="margin:0px;">Proses Validasi.</h4>';
    }
    else if ($status_proses=="5") {
      $return='<h4 class="text-success" style="margin:0px;">Mutasi selesai.</h4>';
    }
*/
    $CI = get_instance();
    $CI->db->select('*');
    $CI->db->from('tbl_master_status_mutasi');
    $CI->db->where('id_status_mutasi', $status_proses);
    // $CI->db->where('A.status_pengajuan','2');
    $status_mutasi = $CI->db->get()->row_array();

    
    $return = '<span class="label label-warning"><i class="fa fa-clock-o"></i> Menunggu ' . $status_mutasi['deskripsi'] . '</span>';
    if ($status_mutasi['id_status_mutasi'] == 5) {
      $return = '<span class="label label-success"><i class="fa fa-check"></i> ' . $status_mutasi['deskripsi'] . '</span>';
    }
    return $return;
  }
}


if (!function_exists('pengecekan_barang_action')) {
  function pengecekan_barang_action($status_proses, $status_validasi, $id_mutasi)
  {
    $return = '';
    $cek = '';
    /* REVISI TGL 17 OKT 2019 -> SKPD HARUS VALIDASI JIKA MUTASI ANTAR SKPD
    $cek.= anchor(base_url('mutasi/pengecekan_barang/detail/'.$id_mutasi),'<button title="Detail" class="btn btn-info"><span class="fa fa-search"></span></button>');
    if ($status_validasi<>"2") {
      $cek.=
            anchor(base_url('mutasi/pengecekan_barang/form_pengecekan/'.$id_mutasi),'&nbsp<button title="Cek" class="btn btn-warning"><span class="fa fa-check-square-o"></span></button>').
            anchor(base_url('mutasi/pengecekan_barang/delete/'.$id_mutasi),'&nbsp<button title="Hapus" class="btn btn-danger"><span class="fa fa-trash"><span></button>').
            anchor(base_url('mutasi/pengecekan_barang/form_pengajuan_register/'.$id_mutasi),'&nbsp<button title="Upload BAST" class="btn btn-success"><span class="fa fa-send"><span></button>').'<br>'
      ;
    }
    */
    if ($status_proses == '2') {
      $cek .=
        anchor(base_url('mutasi/pengecekan_barang/detail/'  . encrypt_url($id_mutasi)), '<button title="Detail" class="btn btn-info btn-sm"><span class="fa fa-search"></span></button>') .
        anchor(base_url('mutasi/pengecekan_barang/form_pengecekan/'  . encrypt_url($id_mutasi)), '&nbsp<button title="Cek" class="btn btn-warning btn-sm"><span class="fa fa-check-square-o"></span></button>') . '<br>';
      // anchor(base_url('mutasi/pengecekan_barang/delete/'  . encrypt_url($id_mutasi)), '&nbsp<button title="Hapus" class="btn btn-danger"><span class="fa fa-trash"><span></button>') . '<br>';
    } elseif ($status_proses == '3') {
      $cek .=
        anchor(base_url('mutasi/pengecekan_barang/detail/'  . encrypt_url($id_mutasi)), '<button title="Detail" class="btn btn-info btn-sm"><span class="fa fa-search"></span></button>') .
        anchor(base_url('mutasi/pengecekan_barang/form_pengecekan/'  . encrypt_url($id_mutasi)), '&nbsp<button title="Cek" class="btn btn-warning btn-sm"><span class="fa fa-check-square-o"></span></button>') .
        // anchor(base_url('mutasi/pengecekan_barang/delete/'  . encrypt_url($id_mutasi)), '&nbsp<button title="Hapus" class="btn btn-danger"><span class="fa fa-trash"><span></button>') .
        anchor(base_url('mutasi/pengecekan_barang/bast/'  . encrypt_url($id_mutasi)), '&nbsp<button title="Cetak BAST" class="btn btn-info btn-sm"><span class="fa fa-file"><span></button>')  .
        anchor(base_url('mutasi/pengecekan_barang/form_pengajuan_register/'  . encrypt_url($id_mutasi)), '&nbsp<button title="Upload BAST" class="btn btn-success btn-sm"><span class="fa fa-send"><span></button>') . '<br>';
    } elseif ($status_proses == '4' or $status_proses == '5') {
      $cek .=
        anchor(base_url('mutasi/pengecekan_barang/detail/'  . encrypt_url($id_mutasi)), '<button title="Detail" class="btn btn-info btn-sm"><span class="fa fa-search"></span></button>') .
        anchor(base_url('mutasi/pengecekan_barang/bast/'  . encrypt_url($id_mutasi)), '&nbsp<button title="Cetak BAST" class="btn btn-info btn-sm"><span class="fa fa-file"><span></button>')  .
        anchor(base_url('mutasi/pengecekan_barang/form_pengajuan_register/'  . encrypt_url($id_mutasi)), '&nbsp<button title="Upload BAST" class="btn btn-success btn-sm"><span class="fa fa-send"><span></button>') . '<br>';
    }

    $return = $cek;
    return $return;
  }
}

if (!function_exists('barang_pengguna_baru')) {
  function barang_pengguna_baru($id_mutasi = null)
  {
    $CI = get_instance();
    $CI->db->select('A.*,B.kib_name, B.nomor_register, C.nama_barang');
    $CI->db->from('tbl_mutasi_barang A');
    $CI->db->join('view_kib B', 'A.kode_jenis=B.kode_jenis and A.id_kib=B.id_kib');
    $CI->db->join('tbl_kode_barang C', 'B.id_kode_barang=C.id_kode_barang', 'left');
    $CI->db->where('A.id_mutasi', $id_mutasi);
    // $CI->db->where('A.status_pengajuan','2');
    $view_kib = $CI->db->get()->result();

    $list_barang = "
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
      $no = $key + 1;
      // $status_pengajuan = null;
      // if ($value->status_pengajuan == '1') {
      //   $status_pengajuan = "<span class='fa fa-clock-o text-orange'></span>";
      // }
      // else if ($value->status_pengajuan == '2') {
      //   $status_pengajuan = "<span class='fa fa-check text-success'></span>";
      // }
      // else if ($value->status_pengajuan == '3'){
      //   $status_pengajuan = "<span class='fa fa-remove text-red'></span>";
      // }

      $status_diterima = null;
      if ($value->status_diterima == '1' or $value->status_diterima == '') {
        $status_diterima = "<span class='fa fa-clock-o text-orange'></span>";
      } else if ($value->status_diterima == '2') {
        $status_diterima = "<span class='fa fa-check text-success'></span>";
      } else if ($value->status_diterima == '3') {
        $status_diterima = "<span class='fa fa-remove text-red'></span>";
      }
      // $status_validasi = $value->status_validasi == '2'?"<span class='fa fa-check text-success'></span>":"<span class='fa fa-clock-o text-orange'></span>";
      $list_barang .=
        "<tr>
          <td>" . $no . "</td>
          <td>" . $value->kib_name . "</td>
          <td>" . $value->nama_barang . "</td>
          <td>" . $value->nomor_register . "</td>

          <td style='text-align:center;'>" . $status_diterima . "</td>
        </tr>";
    }
    $list_barang .=
      " </tbody>
    </table>";
    return $list_barang;
  }
}


//MUTASI->penerima
if (!function_exists('status_checked')) {
  function status_checked($status = null)
  {
    return $status == 'diterima' ? 'checked' : '';
  }
}

if (!function_exists('validasi_checked')) {
  function validasi_checked($validasi_status = null)
  {
    return $validasi_status == 'y' ? 'checked' : '';
  }
}
/*
if ( ! function_exists('radio_validasi_pengajuan'))
{
  function radio_validasi_pengajuan($id_mutasi_barang=null,$status_pengajuan=null, $view=null ){
    $checkbox='null';
    if ($view == 2) {
      $_status_pengajuan = $status_pengajuan == 2?'checked':'';
      $checkbox=
        "<div class='radio'>
          <label>
            <input type='radio' name='pengajuan[".$id_mutasi_barang."]'  class='radio_terima' value='2' ".$_status_pengajuan.">
          </label>
        </div>";
    }
    else if ($view == 3) {
      $_status_pengajuan = $status_pengajuan == 3?'checked':'';
      $checkbox=
      "<div class='radio'>
      <label>
      <input type='radio' name='pengajuan[".$id_mutasi_barang."]'  class='radio_tolak' value='3' ".$_status_pengajuan.">
      </label>
      </div>";
    }

    return $checkbox;
  }
}
*/

if (!function_exists('radio_pengecekan_barang')) {
  function radio_pengecekan_barang($id_mutasi_barang = null, $status_diterima = null, $view = null)
  {
    $checkbox = 'null'; //die($status_diterima);
    if ($view == 2) {
      $_status_diterima = $status_diterima == 2 ? 'checked' : '';
      $checkbox =
        "<div class='radio'>
          <label>
            <input type='radio' name='pengajuan[" . $id_mutasi_barang . "]'  class='radio_terima' value='2' " . $_status_diterima . ">
          </label>
        </div>";
    } else if ($view == 3) {
      $_status_diterima = $status_diterima == 3 ? 'checked' : '';
      $checkbox =
        "<div class='radio'>
      <label>
      <input type='radio' name='pengajuan[" . $id_mutasi_barang . "]'  class='radio_tolak' value='3' " . $_status_diterima . ">
      </label>
      </div>";
    }

    return $checkbox;
  }
}

if (!function_exists('status_validasi')) {
  function status_validasi($id_mutasi_barang = null, $status_diterima = null)
  {
    $_status_diterima = $status_diterima == '2' ? 'checked ' : '';
    $checkbox =
      "<div class='checkbox'>
        <label>
          <input type='checkbox' id_mutasi_barang='" . $id_mutasi_barang . "' class='minimal pengajuan' " . $_status_diterima . ">
          <input id='input_" . $id_mutasi_barang . "' type='hidden' name='pengajuan[" . $id_mutasi_barang . "]' value='" . $status_diterima . "'>
        </label>
      </div>";
    return $checkbox;
  }
}

if (!function_exists('reject')) {
  function reject($id_kib = null, $status_validasi = null, $reject_note = null)
  {
    $color = '#ccc';
    if ($status_validasi == '3') $color = 'red';

    $reject = '<a id_kib="' . $id_kib . '" class="reject" href="#" note="' . $reject_note . '" title="Tolak">
                <span class="glyphicon glyphicon-remove-circle" style="font-size:20px; color:' . $color . ';"></span>
              </a>';

    return $reject;
  }
}

if (!function_exists('hak_akses')) {
  function hak_akses($parameter = null)
  {
    $data = null;
    // die($parameter);
    if ($parameter == 'y') {
      $data = "<span class='fa fa-check' style='color:green'></span>";
    } else if ($parameter == 'n') {
      $data = "<span class='fa fa-close' style='color:red'></span>";
    }
    return $data;
  }
}


if (!function_exists('status_validasi_icon')) {
  function status_validasi_icon($parameter = null)
  {

    $status_validasi = '';
    if ($parameter == '1') {
      $status_validasi = "<span class='fa fa-clock-o text-orange' style='font-size:30px;'></span>";
    } else if ($parameter == '2') {
      $status_validasi = "<span class='fa fa-check text-success' style='font-size:30px;'></span>";
    }

    return $status_validasi;
  }
}

if (!function_exists('status_validasi_checkbox')) {
  function status_validasi_checkbox($id_mutasi, $status_validasi)
  {
    $_status_validasi = $status_validasi == '2' ? 'checked ' : '';
    $checkbox = "
      <label class='switch'>
      <input class='live' type='checkbox' id_mutasi='" . $id_mutasi . "' " . $_status_validasi . ">
      <span class='slider round'></span>
      </label>
      ";

    return $checkbox;
  }
}

if (!function_exists('status_persetujuan_lama_checkbox')) {
  function status_persetujuan_lama_checkbox($id_mutasi, $skpd_aktiv, $persetujuan_skpd)
  {
    $_status_validasi = $persetujuan_skpd == '2' ? 'checked ' : '';
    $checkbox = "";
    if ($skpd_aktiv == 'skpd_lama') {
      $checkbox = "
      <label class='switch'>
      <input class='live persetujuan_lama' type='checkbox' id_mutasi='" . $id_mutasi . "' " . $_status_validasi . ">
      <span class='slider round'></span>
      </label>
      ";
    }


    return $checkbox;
  }
}

if (!function_exists('status_persetujuan_baru_checkbox')) {
  function status_persetujuan_baru_checkbox($id_mutasi, $skpd_aktiv, $persetujuan_skpd)
  {
    $_status_validasi = $persetujuan_skpd == '2' ? 'checked ' : '';
    $checkbox = "";
    if ($skpd_aktiv == 'skpd_baru') {
      $checkbox = "
      <label class='switch'>
      <input class='live persetujuan_baru' type='checkbox' id_mutasi='" . $id_mutasi . "' " . $_status_validasi . ">
      <span class='slider round'></span>
      </label>
      ";
    }

    return $checkbox;
  }
}



if (!function_exists('list_tanda_tangan')) {
  function list_tanda_tangan($tanda_tangan)
  {
    // die($tanda_tangan);
    $temp = explode(',', $tanda_tangan);
    $return = '<table>';
    foreach ($temp as $key => $value) {
      $temp2 = explode(':', $value);
      $return .= '<tr>';
      foreach ($temp2 as $key2 => $value2) {
        if ($key2 == 0) {
          $return .= '<td>';
          $return .= $value2;
          $return .= '</td>';
        } else if ($key2 == 1) {
          $return .= '<td> &nbsp&nbsp&nbsp: ';
          $return .= $value2;
          $return .= '</td>';
        }
      }
      $return .= '</tr>';
    }
    $return .= '</table>';
    return $return;
  }
}



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
    return $pecahkan[2] . ' ' . $bulan[(int) $pecahkan[1]] . ' ' . $pecahkan[0];
  }
}
if (!function_exists('get_action_ruang')) {
  function get_action_ruang($role = null, $controler_name = null, $id_ruang = null)
  {
   
      return anchor(base_url($controler_name . '/read/' . encrypt_url($id_ruang)), '<button class="btn btn-info btn-xs" title="Detail"><span class="fa fa-search"><span></button>') . " | " . anchor(base_url($controler_name . '/update/' . encrypt_url($id_ruang)), '<button class="btn btn-success btn-xs" title="Perbaharui"><span class="fa fa-edit"><span></button>') . " | " . anchor(base_url($controler_name . '/delete/' . encrypt_url($id_ruang)), '<button class="btn btn-danger btn-xs" title="Hapus"><span class="fa fa-trash"><span></button>', 'onclick="javasciprt: return confirm(\'Yakin Ingin Dihapus ?\')"');
    
  }
}

if (!function_exists('get_action_kode_barang')) {
  function get_action_kode_barang($id_kode_barang, $kode_sub_sub_rincian_objek)
  {
    /*
    if ($validasi == '1') {
      return anchor(base_url($controler_name . '/read/' . $id_kib), '<button class="btn btn-info btn-xs" title="Detail"><span class="fa fa-search"><span></button>') . " | " . anchor(base_url($controler_name . '/update/' . $id_kib), '<button class="btn btn-success btn-xs" title="Perbaharui"><span class="fa fa-edit"><span></button>') . " | " . anchor(base_url($controler_name . '/delete/' . $id_kib), '<button class="btn btn-danger btn-xs" title="Hapus"><span class="fa fa-trash"><span></button>', 'onclick="javasciprt: return confirm(\'Yakin Ingin Dihapus ?\')"');
    } else if ($role == '1') {
      return anchor(base_url($controler_name . '/read/' . $id_kib), '<button class="btn btn-info btn-xs" title="Detail"><span class="fa fa-search"><span></button>') . " | " .
        anchor(base_url($controler_name . '/update/' . $id_kib), '<button class="btn btn-success btn-xs" title="Perbaharui"><span class="fa fa-edit"><span></button>');
      // anchor(base_url($controler_name.'/delete/'.$id_kib),'<button class="btn btn-danger btn-xs" title="Hapus"><span class="fa fa-trash"><span></button>','onclick="javasciprt: return confirm(\'Yakin Ingin Dihapus ?\')"');

    } else if ($validasi == '2') {
      return anchor(base_url($controler_name . '/read/' . $id_kib), '<button class="btn btn-info btn-xs" title="Detail"><span class="fa fa-search"><span></button>');
    }*/

    if (($kode_sub_sub_rincian_objek != "*") and ($kode_sub_sub_rincian_objek != "000")) {
      return anchor(base_url('master_data/kode_barang/update/' . $id_kode_barang), '<button class="btn btn-success btn-xs" title="Perbaharui"><span class="fa fa-edit"><span></button>') . " &nbsp " .
        anchor(base_url('master_data/kode_barang/delete/' . $id_kode_barang), '<button class="btn btn-danger btn-xs" title="Hapus"><span class="fa fa-trash"><span></button>', 'onclick="javasciprt: return confirm(\'Yakin Ingin Dihapus ?\')"');
    } else {
      return "";
    }
  }
}

if (!function_exists('cek_hak_akses')) {
  function cek_hak_akses($role, $id_role)
  {
    /*
        1: superadmin; 2:adminskpd; 5:kepalaskpd
         */
    $skpd = array('2', '5'); //skpd
    $superadmin = array('1'); //super admin

    if ($role == 'superadmin') {
      if (in_array($id_role, $superadmin)) {
        return true;
      } else {
        return false;
      }
    } elseif ($role == 'skpd') {
      if (in_array($id_role, $skpd)) {
        return true;
      } else {
        return false;
      }
    }
  }
}



if (!function_exists('get_action_reklas_kode')) {
  function get_action_reklas_kode($status_validasi = null, $id_reklas_kode = null, $jenis_reklas = null)
  {
    if ($status_validasi == '1') {
      if ($jenis_reklas == 'reklas_jenis') {
        return anchor(base_url('reklas/reklas_kode/update_entri/' . $id_reklas_kode), '<button class="btn btn-success btn-xs" title="Perbaharui"><span class="fa fa-edit"><span></button>') . " &nbsp " .
          anchor(base_url('reklas/reklas_kode/delete/' . $id_reklas_kode), '<button class="btn btn-danger btn-xs" title="Hapus"><span class="fa fa-trash"><span></button>', 'onclick="javasciprt: return confirm(\'Yakin Ingin Dihapus ?\')"');
      } elseif ($jenis_reklas == 'kapitalisasi') {
        return /*anchor(base_url('reklas/reklas_kode/update_kapitalisasi/' . $id_reklas_kode), '<button class="btn btn-success btn-xs" title="Perbaharui"><span class="fa fa-edit"><span></button>') . " &nbsp " .*/
          anchor(base_url('reklas/reklas_kode/delete/' . $id_reklas_kode), '<button class="btn btn-danger btn-xs" title="Hapus"><span class="fa fa-trash"><span></button>', 'onclick="javasciprt: return confirm(\'Yakin Ingin Dihapus ?\')"');
      } else {
        return /*anchor(base_url('reklas/reklas_kode/update/' . $id_reklas_kode), '<button class="btn btn-success btn-xs" title="Perbaharui"><span class="fa fa-edit"><span></button>') . " &nbsp " .*/
          anchor(base_url('reklas/reklas_kode/delete/' . $id_reklas_kode), '<button class="btn btn-danger btn-xs" title="Hapus"><span class="fa fa-trash"><span></button>', 'onclick="javasciprt: return confirm(\'Yakin Ingin Dihapus ?\')"');
      }
    } elseif ($status_validasi == '2') {
      return "";
    }
  }
}


if (!function_exists('get_action_reklas_kode_validasi')) {
  function get_action_reklas_kode_validasi($status_validasi, $id_reklas_kode)
  {
    if ($status_validasi == '1') {
      // return anchor(base_url('reklas/validasi_reklas_kode/reklas_actio/' . $id_reklas_kode), '<button class="btn btn-success btn-sm btn-validasi" title="Hapus">Validasi</button>');
      return "<input class='btn btn-success btn-sm btn-validasi' type='button' value='validasi' attr_id_reklas_kode='$id_reklas_kode'> ";
    } elseif ($status_validasi == '2') {
      return "sudah tervalidasi";
    }
  }
}

if (!function_exists('encrypt_url')) {
function encrypt_url($string) {
  $output = false;
  /*
  * read security.ini file & get encryption_key | iv | encryption_mechanism value for generating encryption code
  */        
  $security       = parse_ini_file("security.ini");
  $secret_key     = $security["encryption_key"];
  $secret_iv      = $security["iv"];
  $encrypt_method = $security["encryption_mechanism"];
  // hash
  $key    = hash("sha256", $secret_key);
  // iv – encrypt method AES-256-CBC expects 16 bytes – else you will get a warning
  $iv     = substr(hash("sha256", $secret_iv), 0, 16);
  //do the encryption given text/string/number
  $result = openssl_encrypt($string, $encrypt_method, $key, 0, $iv);
  $output = base64_encode($result);
  return $output;
}
}






/* End of file MY_datatable_helper.php */
/* Location: ./application/helpers/MY_datatable_helper.php */
