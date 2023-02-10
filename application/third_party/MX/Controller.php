<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

/** load the CI class for Modular Extensions **/
require dirname(__FILE__).'/Base.php';

/**
 * Modular Extensions - HMVC
 *
 * Adapted from the CodeIgniter Core Classes
 * @link	http://codeigniter.com
 *
 * Description:
 * This library replaces the CodeIgniter Controller class
 * and adds features allowing use of modules and the HMVC design pattern.
 *
 * Install this file as application/third_party/MX/Controller.php
 *
 * @copyright	Copyright (c) 2015 Wiredesignz
 * @version 	5.5
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 **/
class MX_Controller
{
	public $autoload = array();
	public $default_pemilik='2';

	public $default_kode_intra='00';
	public $default_kode_propinsi='34';
	public $default_kode_kabupaten='71';
	public function __construct()	{
		$class = str_replace(CI::$APP->config->item('controller_suffix'), '', get_class($this));
		log_message('debug', $class." MX_Controller Initialized");
		Modules::$registry[strtolower($class)] = $this;

		/* copy a loader instance and initialize */
		$this->load = clone load_class('Loader');
		$this->load->initialize($this);

		/* autoload module items */
		$this->load->_autoloader($this->autoload);

		//=============================================================
		// $this->session->set_userdata(array(
    //   'kode_lokasi' => '010101.00005.00001',
    //   'nama_lokasi'=>'UPT PENGELOLA TK DAN SD WILAYAH UTARA',
    // ));
		// $this->session->sess_destroy();
		// $array_items = array('kode_lokasi', 'nama_lokasi');
    // $this->session->unset_userdata($array_items);

		if ((!empty($this->session->userdata('session')->id_upik))) {
			if ($this->session->userdata('session')->id_upik != 'jss-a4562') {
				if ($this->config->item('maintenance_mode')) {
					redirect(base_url('login/maintenance'));
				}
			}
		}
		
		// if (!$this->input->cookie('jss_session', false)) {
		// 	$this->session->sess_destroy();
		// 	redirect('https://jss.jogjakota.go.id');
		// } else {
		// 	$this->load->library('curl');
		// 	$this->load->library('encrypt');

		// 	$decrypt = $this->encrypt->decode($this->input->cookie('jss_session', false), "diskominfo");

		// 	$jsonuser = json_decode($decrypt);

		// 	// $nik = $jsonuser->nik;
		// 	$id_upik = $jsonuser->id_upik;
		// 	$id_ses = $this->session->userdata('session')->id_upik;
		// 	if (!empty($id_ses)) {
		// 		if ($id_upik == $id_ses) {
		// 		} else {
		// 			// 	  echo '<script type="text/javascript">
		// 			// alert("Anda tidak memiliki akses");
		// 			//  window.location = "https://jss.jogjakota.go.id"
		// 			// </script>';
		// 			$this->session->sess_destroy();
		// 			redirect('https://aset.jogjakota.go.id');
		// 		}
		// 	} else {
		// 	}
		// }



	}

	// public function get_default_lokasi($kode_lokasi){
	// 	return $this->default_pemilik.'.'.$this->$default_kode_intra.'.'.$this->$default_kode_propinsi.'.'.$this->$default_kode_kabupaten.'.'.$kode_lokasi;
	// }

	public function __get($class)
	{
		return CI::$APP->$class;
	}
}
