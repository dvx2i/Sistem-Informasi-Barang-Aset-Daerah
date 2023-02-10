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
	//$this->load->view('login');
	$this->login();
  }

  public function login(){
     //die(base_url());
   /* if (base_url() != "https://aset.jogjakota.go.id") {
      $id_jss = $_POST['username'];
      if ($id_jss) {
        $user = $this->Login_model->get_by_id_upik($id_jss);
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
    else {*/

        $this->sso();
//    }
  }

  function sso()
  {
      $this->load->library('curl');
      $this->load->library('encrypt');
      // if ($this->input->cookie('jss_session', false)) {

      //     $decrypt = $this->encrypt->decode($this->input->cookie('jss_session', false), "diskominfo");

      //     $jsonuser = json_decode($decrypt);
	
	  // MAINTENANCE
	  // if($jsonuser->id_upik != "JSS-A4562")	  redirect(base_url('login/maintenance'));


          $id_jss = 'JSS-A0198';
          // die($id_jss);
            // print_r($jsonuser);exit;
            //   $data = $this->authentication_model->GetUserByNik($id_jss);
            //   // print_r($data);exit;
            //   if (sizeof($data) > 0) {
            //       $_SESSION['userid'] = $data['UserId'];
            //       $_SESSION['groupid'] = $data['UserGroupId'];
            //       $_SESSION['realname'] = $data['UserRealName'];

            //       $_SESSION['id_jss'] = $data['id_jss'];


            //       redirect('home/home');
            //   } else {
            //       echo '<script type="text/javascript">
            //           alert("Anda Tidak Memiliki Hak akses, ID JSS anda belum terdaftar pada website ini, silahkan Hubungi Admin UPT PUSAT BISNIS Kota Jogjakarta ");
            //           window.location = "https://jss.jogjakota.go.id"
            //           </script>';
            //   }
            $user = $this->Login_model->get_by_id_upik($id_jss);
            // die(json_encode($user));
	      if (empty($user)) {
		echo '<script type="text/javascript">
		alert("ID JSS Anda belum terdaftar di aplikasi ini.");
		window.location = "https://jss.jogjakota.go.id"
		</script>';              
	      }      
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
                array_push(
                    $array_menu,
                    array(
                        'id_hak_akses' => $key,
                        'name' => $value->menu,
                        'menu' => $array_sub_menu
                    )
                );
            }
            // if($user->id_upik == 'jss-a7324'){
              // die(json_encode($array_menu));
            // }
            // die(json_encode($array_menu));
            $this->session->set_userdata('menu', $array_menu);
            // die(json_encode($menu));
            $this->session->set_userdata('session', $user);
            $session = $this->session->userdata('session');
            $this->global_model->_logs($menu = 'login', $sub_menu = 'login', $tabel_name = 'tbl_user', $action_id = $session->id_user, $action = 'authentication', $data = $user);
            redirect(base_url('dashboard'));


      // } else {
      //     echo '<script type="text/javascript">
      //         alert("Login gagal!!! Anda Belum login Jogja Smart Service");
      //         window.location = "https://jss.jogjakota.go.id"
      //         </script>';
      // }
  }



//   public function ssoJSS($id_upik){
//     $this->load->library('curl');
// 		$parameter = json_encode(array('id_upik'=>str_replace('_','-',$id_upik) ));

//     // die($parameter);
// 		$response_json = $this->curl->simple_post('https://layananupik.jogjakota.go.id/lumen/public/api/sso_id_jss', $parameter);

//     $response_object = json_decode($response_json);

//     if ($response_object->status) {
//       $user = $this->Login_model->get_by_id_upik($response_object->data->id_upik);
//       // die(json_encode($user));
//       $menu = $this->Login_model->get_menu($user->id_role);
//       $sub_menu = $this->Login_model->get_sub_menu($user->id_role);
//       $array_menu = array();
//       foreach ($menu as $key => $value) {
//         $array_sub_menu = array();
//         foreach ($sub_menu as $key2 => $value2) {
//           if ($value->menu == $value2->menu) {
//             array_push($array_sub_menu, json_decode(json_encode($value2)));
//           }

//         }
//         array_push($array_menu,
//                     array(
//                       'id_hak_akses' => $key,
//                       'name' => $value->menu,
//                       'menu' => $array_sub_menu
//                     )
//                   );
//       }
//       // die(json_encode($array_menu));
//       $this->session->set_userdata('menu',$array_menu);
//       // die(json_encode($menu));
//       $this->session->set_userdata('session',$user);
//       $session = $this->session->userdata('session');
//       $this->global_model->_logs($menu='login', $sub_menu='login', $tabel_name='tbl_user', $action_id=$session->id_user, $action='authentication', $data=$user);
//       redirect(base_url('dashboard'));
//     }else{
//       $this->global_model->_logs($menu='login', $sub_menu='login', $tabel_name='tbl_user', $action_id=$session->id_user, $action='authentication', $data = array('status' => $response_object->msg, ));
//       $this->session->set_flashdata('message', $response_object->msg);
//       redirect('https://jss.jogjakota.go.id/');
//     }
//   }




  public function logout(){
    $this->session->sess_destroy();
    // redirect(base_url('login'));
    redirect('https://jss.jogjakota.go.id/');
  }


  public function maintenance()
  {
	$this->session->sess_destroy();
    if ($this->config->item('maintenance_mode')) {
      $this->load->view('maintenance.php');
    } else {
      redirect(base_url('dashboard'));
    }
  }


}
?>
