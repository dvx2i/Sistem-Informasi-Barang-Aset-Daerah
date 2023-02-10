<?php

if (!defined('BASEPATH')) exit('No direct script access allowed');

class Pengecekan_barang extends CI_Controller{
  function __construct(){
    parent::__construct();
    $this->global_model->cek_hak_akses('30');//pengecekan barang penghapusan
    $this->load->model('pengecekan_barang_model');
    $this->load->model('global_penghapusan_model');
    $this->load->library('form_validation');
    $this->load->library('datatables');
    $this->load->helper('my_global');
  }

  public function index(){
    $data['css']= array(
      'assets/datatables/dataTables.bootstrap.css',
    );

    $data['js']=array(
      'assets/datatables/jquery.dataTables.js',
      'assets/datatables/dataTables.bootstrap.js'
    );

    $data['content']=$this->load->view('pengecekan_barang/list',$data, true);
    $data['breadcrumb'] = array(
      array('label' => 'Penghapusan', 'url' => '#','icon'=>'fa fa-pie-chart', 'li_class'=>''),
      array('label' => 'Pengecekan Barang', 'url' => '#','icon'=>'', 'li_class'=>'active'),
      // array('label' => 'Detail', 'url' => '#','icon'=>'', 'li_class'=>'active'),
    );
    $this->load->view('template', $data);
  }

  public function detail($id_penghapusan){
    $data['css']= array(
      'assets/datatables/dataTables.bootstrap.css',
      'assets/jquery/fancybox-master/dist/jquery.fancybox.css',
    );

    $data['js']=array(
      'assets/datatables/jquery.dataTables.js',
      'assets/datatables/dataTables.bootstrap.js',
      'assets/jquery/fancybox-master/dist/jquery.fancybox.js',
    );
    $data['id_penghapusan']=$id_penghapusan;
    $data['penghapusan']=$this->global_penghapusan_model->get_penghapusan($id_penghapusan);
    $data['picture']=$this->global_penghapusan_model->get_picture($id_penghapusan);

    $data['content']=$this->load->view('pengecekan_barang/detail',$data, true);
    $data['breadcrumb'] = array(
      array('label' => 'Penghapusan', 'url' => '#','icon'=>'fa fa-pie-chart', 'li_class'=>''),
      array('label' => 'Pengecekan Barang', 'url' => '#','icon'=>'', 'li_class'=>''),
      array('label' => 'Detail', 'url' => '#','icon'=>'', 'li_class'=>'active'),
    );
    $this->load->view('template', $data);
  }


  public function json() {
    header('Content-Type: application/json');
    echo $this->pengecekan_barang_model->json();
  }

  public function json_detail($id_penghapusan) {
    header('Content-Type: application/json');
    echo $this->pengecekan_barang_model->json_detail($id_penghapusan);
  }

  public function form_pengecekan($id){
    $row = $this->pengecekan_barang_model->get_by_id($id);

    if ($row) {
      $data = array(
      'button' => 'Simpan',
      'action' => base_url('penghapusan/pengecekan_barang/pengecekan_barang_action/'.$id),
      'id_penghapusan' => set_value('id_penghapusan', $row->id_penghapusan),
      'tanggal' => set_value('tanggal', tgl_indo($row->tanggal_pengajuan)),
      'id_kode_lokasi' => set_value('id_kode_lokasi', $row->id_kode_lokasi),
      'nama_paket' => set_value('nama_paket', $row->nama_paket),
      );
      $data['css']=array(
      'assets/datatables/dataTables.bootstrap.css',
      "assets/bootstrap/bootstrap-datepicker-master/dist/css/bootstrap-datepicker.min.css",
      "assets/jquery/select2-4.0.6-rc.1/dist/css/select2.css",
      "assets/jquery/select2-bootstrap-theme-master/dist/select2-bootstrap.min.css",
      'assets/adminlte/plugins/iCheck/all.css',
      'assets/jquery/fancybox-master/dist/jquery.fancybox.css',
      );
      $data['js']=array(
      'assets/datatables/jquery.dataTables.js',
      'assets/datatables/dataTables.bootstrap.js',
      "assets/bootstrap/bootstrap-datepicker-master/dist/js/bootstrap-datepicker.min.js",
      "assets/bootstrap/bootstrap-datepicker-master/dist/locales/bootstrap-datepicker.id.min.js",
      "assets/jquery/jQuery-Mask-Plugin-master/dist/jquery.mask.min.js",
      "assets/jquery/select2-4.0.6-rc.1/dist/js/select2.min.js",
      "assets/js/penghapusan.js",
      'assets/adminlte/plugins/iCheck/icheck.min.js',
      'assets/jquery/fancybox-master/dist/jquery.fancybox.js',
      );

      $data['lokasi'] = $this->global_model->get_all_lokasi();

      $data['penghapusan']=$this->global_penghapusan_model->get_penghapusan($id);
      $data['picture']=$this->global_penghapusan_model->get_picture($id);

      $data['jenis_penghapusan'] = $this->pengecekan_barang_model->get_jenis_penghapusan($id)->jenis; //ambil kode jenis yang ada
      $arr_jenis = explode(',',$data['jenis_penghapusan']);

      // print_r($data['jenis_penghapusan']); die;

      if (in_array('1',$arr_jenis)) $data['kib_active'][]='1';
      else $data['kib_hidden']['1']='hidden';
      if (in_array('2',$arr_jenis)) $data['kib_active'][]='2';
      else $data['kib_hidden']['2']='hidden';
      if (in_array('3',$arr_jenis)) $data['kib_active'][]='3';
      else $data['kib_hidden']['3']='hidden';
      if (in_array('4',$arr_jenis)) $data['kib_active'][]='4';
      else $data['kib_hidden']['4']='hidden';
      if (in_array('5',$arr_jenis)) $data['kib_active'][]='5';
      else $data['kib_hidden']['5']='hidden';
      if (in_array('6',$arr_jenis)) $data['kib_active'][]='6';
      else $data['kib_hidden']['6']='hidden';
      if (in_array('5.03',$arr_jenis)) $data['kib_active'][]='5.03';
      else $data['kib_hidden']['5.03']='hidden';


      $data['content']=$this->load->view('pengecekan_barang/form', $data, true);
      $data['breadcrumb'] = array(
        array('label' => 'Penghapusan', 'url' => '#','icon'=>'fa fa-pie-chart', 'li_class'=>''),
        array('label' => 'Pengecekan Barang', 'url' => '#','icon'=>'', 'li_class'=>''),
        array('label' => 'Form', 'url' => '#','icon'=>'', 'li_class'=>'active'),
      );
      $this->load->view('template', $data);
    } else {
      $this->session->set_flashdata('message', 'Record Not Found');
      redirect(base_url('penghapusan/pengecekan_barang'));
    }
  }

  public function pengecekan_barang_action($id_penghapusan=null){
    $arr_barang=array();
    $list_id_penghapusan_barang=array();
    foreach ($this->input->post('pengajuan') as $key => $value) {
        $list_id_penghapusan_barang[]=$key;
        array_push($arr_barang,
          array(
            "id_penghapusan_barang" => $key,
            "status_diterima" => $value,
            "tanggal_diterima" => date('Y-m-d'),
          )
        );
        $this->global_model->_logs($menu='penghapusan', $sub_menu='pengecekan_barang', $tabel_name='tbl_penghapusan_barang', $action_id=$key, $action='update', $data = array("id_penghapusan_barang" => $key,"status_diterima" => $value,"tanggal_diterima" => date('Y-m-d'),), $feature='pengecekan_barang_action');
    }

    $this->pengecekan_barang_model->update_pengajuan($arr_barang,'id_penghapusan_barang');
    // update kib status barang = penghapusan
    $result = $this->pengecekan_barang_model->get_by_list_id($list_id_penghapusan_barang);
    foreach ($result as $key => $value) {

      /*if ($value->status_diterima == 2) {
        $this->global_model->update_kib($value->kode_jenis,$value->id_kib,array('status_barang' => 'penghapusan', ));
      }
      else if ($value->status_diterima == 1 or $value->status_diterima == 3) {
        $this->global_model->update_kib($value->kode_jenis,$value->id_kib,array('status_barang' => 'kib', ));
      }
      */

      $jenis=$this->global_model->kode_jenis[$value->kode_jenis];
      $this->global_model->_logs($menu='penghapusan', $sub_menu='pengecekan_barang', $tabel_name=$jenis['table'], $action_id=$value->id_kib, $action='update', $data = array($jenis['id_name']=>$value->id_kib, 'status_barang' => 'penghapusan', ), $feature='pengecekan_barang_action');
    }

    $this->pengecekan_barang_model->update_penghapusan($id_penghapusan, array('status_proses' => '2', ));

    $this->session->set_flashdata('message', 'Pengecekan Barang selesai');
    redirect(base_url('penghapusan/pengecekan_barang'));
  }

  public function delete($id){
    $this->load->model('pengajuan_model');
    $row = $this->pengajuan_model->get_by_id($id);

    if ($row) {
      $this->pengajuan_model->delete_penghapusan_barang($id);
      $this->pengajuan_model->delete($id);
      $this->global_model->_logs($menu='penghapusan', $sub_menu='pengajuan', $tabel_name='tbl_penghapusan', $action_id=$id, $action='delete', $data=$row, $feature='delete');
      $this->global_model->_logs($menu='penghapusan', $sub_menu='pengajuan', $tabel_name='tbl_penghapusan_barang', $action_id=$id, $action='delete', $data=$row, $feature='delete');
                              //($menu='location', $sub_menu='set_location', $tabel_name='tbl_kode_location', $action_id=$session->id_user, $action='set', $data=$data, $feature);
      $this->session->set_flashdata('message', 'Berhasil menghapus pengajuan.');
      redirect(base_url('penghapusan/pengecekan_barang'));
    } else {
      $this->session->set_flashdata('message', 'Record Not Found');
      redirect(base_url('penghapusan/pengecekan_barang'));
    }
  }

/*
  public function form_pengajuan_register($id){
    $row = $this->pengecekan_barang_model->get_by_id($id);

    if ($row) {
      $data = array(
      'button' => 'Ajukan',
      'action' => base_url('mutasi/pengecekan_barang/pengajuan_register_action/'.$id),
      'id_mutasi' => set_value('id_mutasi', $row->id_mutasi),
      'tanggal' => set_value('tanggal', tgl_indo($row->tanggal)),
      'id_kode_lokasi_lama' => set_value('id_kode_lokasi_lama', $row->id_kode_lokasi_lama),
      'id_kode_lokasi_baru' => set_value('id_kode_lokasi_baru', $row->id_kode_lokasi_baru),
      'url_upload_bast' => set_value('url_upload_bast', $row->url_upload_bast),
      );
      $data['css']=array(
      "assets/bootstrap/bootstrap-datepicker-master/dist/css/bootstrap-datepicker.min.css",
      "assets/jquery/select2-4.0.6-rc.1/dist/css/select2.css",
      "assets/jquery/select2-bootstrap-theme-master/dist/select2-bootstrap.min.css",
      );
      $data['js']=array(
      "assets/bootstrap/bootstrap-datepicker-master/dist/js/bootstrap-datepicker.min.js",
      "assets/bootstrap/bootstrap-datepicker-master/dist/locales/bootstrap-datepicker.id.min.js",
      "assets/jquery/select2-4.0.6-rc.1/dist/js/select2.min.js",
      );

      $data['lokasi'] = $this->global_model->get_all_lokasi();

      $data['content']=$this->load->view('pengecekan_barang/register_form', $data, true);
      $this->load->view('template', $data);
    } else {
      $this->session->set_flashdata('message', 'Record Not Found');
      redirect(base_url('mutasi'));
    }
  }

  public function pengajuan_register_action($id_mutasi=null){
     $data_update = array(
       'status_validasi' => '1',
       'tanggal_validasi' => date('Y-m-d'),
     );
    $this->pengecekan_barang_model->update_pengajuan_register($id_mutasi,$data_update);

    $this->session->set_flashdata('message', 'Pengajuan register berhasil.');
    redirect(base_url('mutasi/pengecekan_barang'));
  }

  function upload_bast(){
    $id_mutasi=$this->input->post('id_mutasi');
    // $folder = 'assets/files/mutasi/picture_bast_'.md5($id_mutasi);
    $folder = "assets/files/mutasi";

    if(!file_exists($folder)){
      mkdir($folder, 0755);
      $dir = $folder;
    }
    else $dir = $folder;
    $fieldname = 'file';

    $config['upload_path']    = $dir;
    $config['allowed_types']  = 'jpg|jpeg|png';
    $config['overwrite']      = TRUE;
    $config['file_ext_tolower'] = TRUE;
    $config['max_size'] 		= 2*1024*1024;
    //add 27 oktober 2018
    // $config['encrypt_name'] = TRUE;
    $config['file_name'] = md5($id_mutasi);

    $this->load->library('upload');

    $this->upload->initialize($config);

    if ($this->upload->do_upload($fieldname)){
      $upload = array();
      $upload = $this->upload->data();


      //set ratio
      $ratio = $upload['image_height'] / $upload['image_width'];
      $this->load->library('image_lib');
      $resize['image_library'] = 'gd2';
      $resize['source_image'] = $dir.'/'.$upload['file_name'];
      $resize['maintain_ratio'] = TRUE;
      $resize['height'] = $ratio * 600;
      $resize['width'] = 600;

      $this->image_lib->initialize($resize);
      $this->image_lib->resize();

      $status = array('status' => TRUE,'message' => $upload);

      //update url BAST
      $data_update = array(
        'url_upload_bast'=>$dir.'/'.$upload['file_name'],
        'tanggal_upload_bast'=>date('Y-m-d'),
      );
      $this->pengecekan_barang_model->update_url_bast($id_mutasi,$data_update);
      $this->global_model->_logs($menu='mutasi', $sub_menu='pengecekan_barang', $tabel_name='tbl_mutasi', $action_id=$id_mutasi, $action='update', $data = $data_update, $feature='upload_bast');

    }else{
      $status = array('status' => FALSE,'message' => $this->upload->display_errors());
    }

    if($status['status']){
      echo json_encode(array(
        'status'=>true,'extension'=>$status['message']['file_ext'],
        'path'=>$dir.'/'.$status['message']['file_name'],
        'img_src'=>base_url($dir.'/'.$upload['file_name']),
        ));
      }
      else{
        echo json_encode(array(
        'status'=>false,
        'error_message'=>$status['message'],
        ));
      }
	}
*/


}
