<?php

if (!defined('BASEPATH'))
exit('No direct script access allowed');

class User extends CI_Controller{
  protected $default_pemilik = '2';
  function __construct(){
    parent::__construct();
    $this->global_model->cek_hak_akses('20');
    $this->load->model('User_model');
    $this->load->library('form_validation');
    $this->load->library('datatables');
  }

  public function index(){
    $data['css']= array(
      'assets/datatables/dataTables.bootstrap.css',
    );

    $data['js']=array(
      'assets/datatables/jquery.dataTables.js',
      'assets/datatables/dataTables.bootstrap.js'
    );

    $data["content"]=$this->load->view('user/user_list', "", TRUE);
    $data['breadcrumb'] = array(
      array('label' => 'Administrator', 'url' => '#','icon'=>'fa fa-pie-chart', 'li_class'=>''),
      array('label' => 'User', 'url' => '#','icon'=>'', 'li_class'=>'active'),
      // array('label' => 'Buat', 'url' => '#','icon'=>'', 'li_class'=>'active'),
    );
    $this->load->view('template', $data);
  }

  public function json() {
    header('Content-Type: application/json');
    echo $this->User_model->json();
  }

  public function read($id)  {
    $row = $this->User_model->get_by_id($id);
    if ($row) {
      $data = array(
        'id_user' => $row->id_user,
        'nama' => $row->nama,
        'nip' => $row->nip,
        'kode_lokasi' => $row->kode_lokasi,
		'freze' => $row->freze,
      );
      $this->load->view('user/user_read', $data);
    } else {
      $this->session->set_flashdata('message', 'Record Not Found');
      redirect(base_url('user'));
    }
  }

  public function create()  {
    $data['data'] = array(
      'button' => 'Pengaturan',
      'action' => base_url('User/create_action'),
      'id_user' => set_value('id_user'),
      'id_upik' => set_value('id_upik'),
      'nama' => set_value('nama'),
      // 'nik' => set_value('nik'),
      'nip' => set_value('nip'),
      // 'alamat' => set_value('alamat'),
      'email' => set_value('email'),
      'nomor_telepone' => set_value('nomor_telepone'),
      'role' => set_value('role'),
      'kode_lokasi' => set_value('kode_lokasi'),
      'id_user_jss' => set_value('id_user_jss'),
      'username' => set_value('username'),
	   'freze' => set_value('freze'),
      // 'jabatan' => set_value('jabatan'),
    );

    $data['css']=array(
      "assets/adminlte/bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css",
      "assets/jquery/select2-4.0.6-rc.1/dist/css/select2.min.css",
      "assets/jquery/select2-bootstrap-theme-master/dist/select2-bootstrap.min.css",
      'assets/adminlte/plugins/iCheck/all.css',
      'assets/sweetalert/sweetalert.css',
    );
    $data['js']=array(
      "assets/adminlte/bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js",
      "assets/jquery/jQuery-Mask-Plugin-master/dist/jquery.mask.min.js",
      "assets/jquery/select2-4.0.6-rc.1/dist/js/select2.min.js",
      'assets/adminlte/plugins/iCheck/icheck.min.js',
      'assets/sweetalert/sweetalert.min.js',
      "assets/js/user.js",
    );

    $data['pemilik']=$this->global_model->get_pemilik();
    $data['role_list'] = $this->User_model->getRole();
    $data['lokasi'] = $this->User_model->getLokasi();
    // $data['list_jabatan'] = $this->User_model->getJabatan();

    $data["content"]=$this->load->view('user/user_form', $data, TRUE);
    $data['breadcrumb'] = array(
      array('label' => 'Administrator', 'url' => '#','icon'=>'fa fa-pie-chart', 'li_class'=>''),
      array('label' => 'User', 'url' => '#','icon'=>'', 'li_class'=>''),
      array('label' => 'Buat', 'url' => '#','icon'=>'', 'li_class'=>'active'),
    );
    $this->load->view('template', $data);
  }

  public function create_action(){
    $this->_rules();

    if ($this->form_validation->run() == FALSE) {
      $this->create();
    } else {
      if ($this->User_model->get_by_id_upik($this->input->post('id_upik',TRUE))) {
        echo "Data JSS sudah ada.";
        header( "refresh:3;url=".base_url('user') );
        die();
      }
      $this->load->helper('password');
      $data = array(
        'id_kode_lokasi' => $this->input->post('kode_lokasi',TRUE),
        'id_user_jss' => $this->input->post('id_user_jss',TRUE),
        'id_upik' => $this->input->post('id_upik',TRUE),
        // 'id_jabatan' => $this->input->post('jabatan',TRUE),
        'nama' => $this->input->post('nama',TRUE),
        'username' => $this->input->post('username',TRUE),
        // 'alamat' => $this->input->post('alamat',TRUE),
        // 'nik' => $this->input->post('nik',TRUE),
        'nip' => $this->input->post('nip',TRUE),
        'email' => $this->input->post('email',TRUE),
        'nomor_telepone' => $this->input->post('nomor_telepone',TRUE),
        'id_role' => $this->input->post('role', TRUE),
		'freze' => $this->input->post('freze', TRUE),
      );

      $this->User_model->insert($data);
      $this->global_model->_logs($menu='user', $sub_menu='user', $tabel_name='tbl_user', $action_id=null, $action='insert', $data=$data);
      $this->session->set_flashdata('message', 'Create Record Success');
      redirect(base_url('user'));
    }
  }

  public function update($id){
	  if ($id == 'freze') {
            //$tgl = date('Y-m-d', strtotime($_POST['jaminan_verifikasi_tgl']));
            $arr_id = implode(',', $this->input->post('user_id',TRUE));

            

                $this->db->trans_start();
                $this->User_model->set_freze($arr_id);

                $this->db->trans_complete();

                if ($this->db->trans_status()) {
                    echo json_encode(array('success' => true, 'message' => 'Berhasil DI Freze'));
					die;
                } else {
                    echo json_encode(array('success' => false, 'message' => 'Gagal DI Freze'));
					die;
                }
            
        }
        if ($id == 'unfreze') {
             $arr_id = implode(',', $this->input->post('user_id',TRUE));

            // print($arr_id);
            // exit;

            $this->db->trans_start();
            $this->User_model->set_unfreze($arr_id);

            $this->db->trans_complete();

            if ($this->db->trans_status()) {
                echo json_encode(array('success' => true, 'message' => 'Berhasil DI UnFreze'));
				die;
            } else {
                echo json_encode(array('success' => false, 'message' => 'Gagal DI UnFreze'));
				die;
            }
        }else{
    $row = $this->User_model->get_by_id($id);

    if ($row) {
      $this->load->helper('password');
      $data['data'] = array(
        'button' => 'Perbarui',
        'action' => base_url('User/update_action'),
        'id_user' => set_value('id_user', $row->id_user),
        'id_upik' => set_value('id_upik', $row->id_upik),
        'nama' => set_value('nama', $row->nama),
        // 'nik' => set_value('nik', $row->nik),
        'nip' => set_value('nip', $row->nip),
        // 'alamat' => set_value('alamat', $row->alamat),
        'email' => set_value('email', $row->email),
        'nomor_telepone' => set_value('nomor_telepone', $row->nomor_telepone),
        'role' => set_value('role', $row->id_role),
        'kode_lokasi' => set_value('kode_lokasi', $row->id_kode_lokasi),
        'id_user_jss' => set_value('id_user_jss', $row->id_user_jss),
        'username' => set_value('username', $row->username),
		'freze' => set_value('freze', $row->freze),
		//'status_freze' => set_value('freze', $row->freze),
        // 'jabatan' => set_value('jabatan', $row->id_jabatan),
      );



      $data['css']=array(
        "assets/adminlte/bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css",
        "assets/jquery/select2-4.0.6-rc.1/dist/css/select2.min.css",
        "assets/jquery/select2-bootstrap-theme-master/dist/select2-bootstrap.min.css",
        'assets/adminlte/plugins/iCheck/all.css',
        'assets/sweetalert/sweetalert.css',
      );
      $data['js']=array(
        "assets/adminlte/bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js",
        "assets/jquery/jQuery-Mask-Plugin-master/dist/jquery.mask.min.js",
        "assets/jquery/select2-4.0.6-rc.1/dist/js/select2.min.js",
        'assets/adminlte/plugins/iCheck/icheck.min.js',
        'assets/sweetalert/sweetalert.min.js',
        "assets/js/user.js",
      );

      $data['pemilik']=$this->global_model->get_pemilik();
      $data['role_list'] = $this->User_model->getRole();
      $data['lokasi'] = $this->User_model->getLokasi();
      // $data['list_jabatan'] = $this->User_model->getJabatan();

      $data["content"]=$this->load->view('user/user_form', $data, TRUE);
      $data['breadcrumb'] = array(
        array('label' => 'Administrator', 'url' => '#','icon'=>'fa fa-pie-chart', 'li_class'=>''),
        array('label' => 'User', 'url' => '#','icon'=>'', 'li_class'=>''),
        array('label' => 'Perbarui', 'url' => '#','icon'=>'', 'li_class'=>'active'),
      );
      $this->load->view('template', $data);
    } else {
      $this->session->set_flashdata('message', 'Record Not Found');
      redirect(base_url('user'));
    }
		}
  }

  public function update_action(){
    $this->_rules();

    if ($this->form_validation->run() == FALSE) {
      $this->update($this->input->post('id_user', TRUE));
    } else {
      $this->load->helper('password');
      $data = array(
        'id_kode_lokasi' => $this->input->post('kode_lokasi',TRUE),
        'id_user_jss' => $this->input->post('id_user_jss',TRUE),
        'id_upik' => $this->input->post('id_upik',TRUE),
        // 'id_jabatan' => $this->input->post('jabatan',TRUE),
        'nama' => $this->input->post('nama',TRUE),
        'username' => $this->input->post('username',TRUE),
        // 'alamat' => $this->input->post('alamat',TRUE),
        // 'nik' => $this->input->post('nik',TRUE),
        'nip' => $this->input->post('nip',TRUE),
        'email' => $this->input->post('email',TRUE),
        'nomor_telepone' => $this->input->post('nomor_telepone',TRUE),
        'id_role' => $this->input->post('role', TRUE),
		'freze' => $this->input->post('freze', TRUE),

        'updated_at' => date('Y-m-d H:i:s'),
      );


      $this->User_model->update($this->input->post('id_user', TRUE), $data);
      $this->global_model->_logs($menu='user', $sub_menu='user', $tabel_name='tbl_user', $action_id=$this->input->post('id_user', TRUE), $action='update', $data=$data);
      $this->session->set_flashdata('message', 'Update Record Success');
      redirect(base_url('user'));
    }
  }

  public function delete($id){
    $row = $this->User_model->get_by_id($id);

    if ($row) {
      $this->User_model->delete($id);
      $this->global_model->_logs($menu='user', $sub_menu='user', $tabel_name='tbl_user', $action_id=$id, $action='delete', $data=$row);
      $this->session->set_flashdata('message', 'Delete Record Success');
      redirect(base_url('user'));
    } else {
      $this->session->set_flashdata('message', 'Record Not Found');
      redirect(base_url('user'));
    }
  }

  public function _rules(){
    $this->form_validation->set_rules('nama', 'nama', 'trim|required');
    // $this->form_validation->set_rules('nik', 'nik', 'trim|required');
    $this->form_validation->set_rules('nip', 'nip', 'trim|required');
    // $this->form_validation->set_rules('alamat', 'alamat', 'trim|required');
    $this->form_validation->set_rules('email', 'email', 'trim|required|valid_email');
    $this->form_validation->set_rules('nomor_telepone', 'nomor telepone', 'trim|required');
    $this->form_validation->set_rules('kode_lokasi', 'kode lokasi', 'trim|required');

    $this->form_validation->set_rules('id_user', 'id_user', 'trim');
    $this->form_validation->set_error_delimiters('<span class="text-danger">', '</span>');
  }

  public function get_jss(){
    $id_upik = $this->input->post('id_upik');
    $url = "https://webservice.jogjakota.go.id/jss/akun?id=".$id_upik;
    $data = json_decode(file_get_contents($url));
    $status = isset($data[0])?TRUE:FALSE;

    if ($status) {
      $data = $data[0];

      if ($this->User_model->get_by_id_upik($data->id_upik)) {
        $status=FALSE;
        echo json_encode(array('status' => $status,'message'=>'Data JSS sudah ada.' ));
      }else{
        $data->status=TRUE;
        echo json_encode($data);
      }
    }else {
      echo json_encode(array('status' => $status,'message'=>'Data tidak ditemukan.' ));
    }
  }

}

  /* End of file User.php */
  /* Location: ./application/controllers/User.php */
  /* Please DO NOT modify this information : */
  /* Generated by Harviacode Codeigniter CRUD Generator 2018-08-09 06:17:03 */
  /* http://harviacode.com */
