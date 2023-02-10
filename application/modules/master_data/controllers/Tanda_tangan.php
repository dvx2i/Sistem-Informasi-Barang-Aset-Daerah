<?php

if (!defined('BASEPATH'))
exit('No direct script access allowed');

class Tanda_tangan extends CI_Controller
{
  function __construct()
  {
    parent::__construct();
    // $this->global_model->cek_hak_akses('1');
    $this->load->model('Tanda_tangan_model');
    $this->load->library('form_validation');
    $this->load->library('datatables');
  }

  public function index()
  {
    $data['css']= array(
      'assets/datatables/dataTables.bootstrap.css',
    );

    $data['js']=array(
      'assets/datatables/jquery.dataTables.js',
      'assets/datatables/dataTables.bootstrap.js',
    );
    $data['content'] = $this->load->view('tanda_tangan/list', '', true);
    $data['breadcrumb'] = array(
      array('label' => 'Administrator', 'url' => '#','icon'=>'fa fa-pie-chart', 'li_class'=>''),
      array('label' => 'Tanda Tangan', 'url' => '#','icon'=>'', 'li_class'=>'active'),
      // array('label' => 'Buat', 'url' => '#','icon'=>'', 'li_class'=>'active'),
    );
    $this->load->view('template', $data);
  }

  public function json() {
    header('Content-Type: application/json');
    echo $this->Tanda_tangan_model->json();
  }

  public function create(){
    $data = array(
    'button' => 'Pengaturan',
    'action' => base_url('master_data/tanda_tangan/create_action'),
    'id_tanda_tangan' => set_value('id_tanda_tangan'),
    'id_kode_lokasi' => set_value('id_kode_lokasi'),
    // 'id_user' => set_value('id_user'),
    'id_jabatan' => set_value('id_jabatan'),
    );
    $data['css']= array(
      "assets/jquery/select2-4.0.6-rc.1/dist/css/select2.min.css",
      "assets/jquery/select2-bootstrap-theme-master/dist/select2-bootstrap.min.css",
    );

    $data['js']=array(
      "assets/jquery/select2-4.0.6-rc.1/dist/js/select2.min.js",
    );

    $data['list_lokasi']=$this->Tanda_tangan_model->getLokasi();
    $data['list_jabatan']=$this->global_model->getJabatan();
    // $data['list_user']=$this->global_model->getUser();

    $tanda_tangan=array();
    foreach ($data['list_jabatan'] as $key => $value) {
      // array_push($tanda_tangan,  array($value->id_jabatan => $value->id_user, ));
      $tanda_tangan[$value->id_jabatan] ='0';
    }
    $data['tanda_tangan'] = $tanda_tangan;

    $data['content'] = $this->load->view('tanda_tangan/form', $data, true);
    $data['breadcrumb'] = array(
      array('label' => 'Administrator', 'url' => '#','icon'=>'fa fa-pie-chart', 'li_class'=>''),
      array('label' => 'Tanda Tangan', 'url' => '#','icon'=>'', 'li_class'=>''),
      array('label' => 'Buat', 'url' => '#','icon'=>'', 'li_class'=>'active'),
    );
    $this->load->view('template', $data);
  }

  public function create_action()
  {
    $this->_rules();

    if ($this->form_validation->run() == FALSE) {
      $this->create();
    } else {
      $arr_tanda_tangan=array();
      foreach ($this->input->post('user[]',TRUE) as $key => $value) {
          array_push($arr_tanda_tangan,
                      array(
                        'id_kode_lokasi' => $this->input->post('id_kode_lokasi',TRUE),
                        'id_user' => $value,
                        'id_jabatan' => $key,
                      )
                    );
      }

      $this->Tanda_tangan_model->insert_batch($arr_tanda_tangan);
      $this->global_model->_logs($menu='master_data', $sub_menu='tanda_tangan', $tabel_name='tbl_tanda_tangan', $action_id=null, $action='insert', $data=$arr_tanda_tangan);
      $this->session->set_flashdata('message', 'Berhasil Menambah Data');
      redirect(base_url('master_data/tanda_tangan'));
    }
  }

  public function update($id_kode_lokasi){
     $row = $this->Tanda_tangan_model->get_axist_lokasi($id_kode_lokasi);
    if ($row) {
      $data = array(
      'button' => 'Perbarui',
      'action' => base_url('master_data/tanda_tangan/update_action'),
      'id_kode_lokasi' => set_value('id_kode_lokasi', $id_kode_lokasi),
      );

      $data['css']= array(
        "assets/jquery/select2-4.0.6-rc.1/dist/css/select2.min.css",
        "assets/jquery/select2-bootstrap-theme-master/dist/select2-bootstrap.min.css",
      );

      $data['js']=array(
        "assets/jquery/select2-4.0.6-rc.1/dist/js/select2.min.js",
      );

      $data['list_lokasi']=$this->global_model->getLokasi();
      $data['list_jabatan']=$this->global_model->getJabatan();
      // $data['list_user']=$this->global_model->getUser();
      $data['list_user'] = $this->Tanda_tangan_model->getUser($row->kode_lokasi);

      $temp=$this->Tanda_tangan_model->tanda_tangan($id_kode_lokasi);

      $tanda_tangan=array();
      foreach ($temp as $key => $value) {
        $tanda_tangan[$value->id_jabatan] =$value->id_user;
      }
      $data['tanda_tangan'] = $tanda_tangan;


      $data['content'] = $this->load->view('tanda_tangan/form', $data, true);
      $data['breadcrumb'] = array(
        array('label' => 'Administrator', 'url' => '#','icon'=>'fa fa-pie-chart', 'li_class'=>''),
        array('label' => 'Tanda Tangan', 'url' => '#','icon'=>'', 'li_class'=>''),
        array('label' => 'Perbarui', 'url' => '#','icon'=>'', 'li_class'=>'active'),
      );
      $this->load->view('template', $data);
    } else {
      $this->session->set_flashdata('message', 'Record Not Found');
      redirect(base_url('master_data/tanda_tangan'));
    }
  }

  public function update_action(){
    $this->_rules();

    if ($this->form_validation->run() == FALSE) {
      $this->update($this->input->post('id_kode_lokasi', TRUE));
    } else {
      $this->Tanda_tangan_model->delete_by_kode_lokasi($this->input->post('id_kode_lokasi', TRUE));
      $arr_tanda_tangan=array();
      foreach ($this->input->post('user[]',TRUE) as $key => $value) {
          array_push($arr_tanda_tangan,
                      array(
                        'id_kode_lokasi' => $this->input->post('id_kode_lokasi',TRUE),
                        'id_user' => $value,
                        'id_jabatan' => $key,
                      )
                    );
      }

      $this->Tanda_tangan_model->insert_batch($arr_tanda_tangan);
      $this->global_model->_logs($menu='master_data', $sub_menu='tanda_tangan', $tabel_name='tbl_tanda_tangan', $action_id=$this->input->post('id_kode_lokasi', TRUE), $action='update', $data=$arr_tanda_tangan);
      $this->session->set_flashdata('message', 'Berhasil Memperbarui');
      redirect(base_url('master_data/tanda_tangan'));
    }
  }

  public function delete_by_kode_lokasi($id_kode_lokasi){
    $row = $this->Tanda_tangan_model->get_axist_lokasi($id_kode_lokasi);

    if ($row) {
      $this->Tanda_tangan_model->delete_by_kode_lokasi($id_kode_lokasi);
      $this->global_model->_logs($menu='master_data', $sub_menu='tanda_tangan', $tabel_name='tbl_tanda_tangan', $action_id=$id_kode_lokasi, $action='delete', $data=$row);
      $this->session->set_flashdata('message', 'Berhasil menghapus');
      redirect(base_url('master_data/tanda_tangan'));
    } else {
      $this->session->set_flashdata('message', 'Record Not Found');
      redirect(base_url('master_data/tanda_tangan'));
    }
  }

  public function _rules(){
    $this->form_validation->set_rules('id_kode_lokasi', 'kode lokasi', 'trim|required');

    $this->form_validation->set_rules('id_tanda_tangan', 'id_tanda_tangan', 'trim');
    $this->form_validation->set_error_delimiters('<span class="text-danger">', '</span>');
    $this->form_validation->set_message('required', 'Objek %s harus di isi.');
  }

  public function get_user(){
		$kode_lokasi=$this->input->post('kode_lokasi');
		// $kode=$kode=$this->global_library->explode_kode_barang(set_value('kode_barang'));
    // $kode_sub_sub_rincian_objek=$this->global_model->get_sub_sub_rincian_objek($kode['kode_jenis'],$kode['kode_objek'],$kode['kode_rincian_objek'],$kode['kode_sub_rincian_objek']);
    $result_lokasi = $this->Tanda_tangan_model->getUser($kode_lokasi);
    $data['option']='<option value="">Silahkan Pilih</option>';
    foreach ($result_lokasi as $key => $value) {
			$data['option'] .='<option  value="'.$value->id_user.'">'.$value->nama.'</option>';
    }                    /*<option  value="<?php echo $key2->id_user; ?>" <?= ($key2->id_user == $tanda_tangan[$value->id_jabatan]) ? 'selected' : ''?>><?php echo $key2->nama ?></option>*/
    echo json_encode($data);
  }

}
