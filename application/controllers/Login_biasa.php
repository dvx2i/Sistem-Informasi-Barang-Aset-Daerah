<?php

if (!defined('BASEPATH'))
exit('No direct script access allowed');

class Login extends CI_Controller{
  function __construct(){
    parent::__construct();
    $this->load->helper('url');
    $this->load->model('Login_model');
  }

  public function index(){
    $this->load->view('login');
  }

  public function ssoJSS($id_upik){
    $this->load->library('curl');
		$parameter = json_encode(array('id_upik'=>str_replace('_','-',$id_upik) ));

    // die($parameter);
		$response_json = $this->curl->simple_post('https://layananupik.jogjakota.go.id/lumen/public/api/sso_id_jss', $parameter);

    $response_object = json_decode($response_json);

    if ($response_object->status) {
      $user = $this->Login_model->get_by_id_upik($response_object->data->id_upik);
      // die(json_encode($user));
      $menu = $this->Login_model->get_menu($user->id_role);
      $sub_menu = $this->Login_model->get_sub_menu($user->id_role);
      $array_menu = array();
      foreach ($menu as $key => $value) {
        $array_sub_menu = array();
        foreach ($sub_menu as $key2 => $value2) {
          if ($value->menu == $value2->menu) {
            array_push($array_sub_menu, json_decode(json_encode($value2)));
          }

        }
        array_push($array_menu,
                    array(
                      'id_hak_akses' => $key,
                      'name' => $value->menu,
                      'menu' => $array_sub_menu
                    )
                  );
      }
      // die(json_encode($array_menu));
      $this->session->set_userdata('menu',$array_menu);
      // die(json_encode($menu));
      $this->session->set_userdata('session',$user);
      $session = $this->session->userdata('session');
      $this->global_model->_logs($menu='login', $sub_menu='login', $tabel_name='tbl_user', $action_id=$session->id_user, $action='authentication', $data=$user);
      redirect(base_url('dashboard'));
    }else{
      $this->global_model->_logs($menu='login', $sub_menu='login', $tabel_name='tbl_user', $action_id=$session->id_user, $action='authentication', $data = array('status' => $response_object->msg, ));
      $this->session->set_flashdata('message', $response_object->msg);
      redirect('https://jss.jogjakota.go.id/');
    }
  }

  public function logout(){
    $this->session->sess_destroy();
    // redirect(base_url('login'));
    redirect('https://jss.jogjakota.go.id/');
  }
}
?>
