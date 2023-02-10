<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Switch_lokasi extends MX_Controller
{

	function __construct()
	{
		parent::__construct();
		$this->load->model('switch_lokasi_model', 'switch_model');
		// die(json_encode($this->session->userdata('session')->id_role));
		if (!$this->session->userdata('session')->id_role) {
			redirect(base_url('login'));
			// redirect('https://jss.jogjakota.go.id/');
		}
		// $menu = $this->session->userdata('menu');
		// die(json_encode($menu));
		$session = $this->session->userdata('session');
		$menu = $this->session->userdata('menu');
		// die(json_encode($menu));
	}
	public function index()
	{
		// $data['session'] = $this->session->userdata('menu');
		// die(json_encode($data['session']));
		// $user_session = $this->session->session;
		// die(json_encode($this->session->session));
		$data = array(
			'button' => 'Pindah',
			'action' => base_url('dashboard/switch_lokasi/action'),
			'id_pengguna' => set_value('id_pengguna'),
			'id_kuasa_pengguna' => set_value('id_kuasa_pengguna'),
			'id_sub_kuasa_pengguna' => set_value('id_sub_kuasa_pengguna'),
			'kode_lokasi' => set_value('kode_lokasi'),
		);
		$data['css'] = array(

			"assets/jquery/select2-4.0.6-rc.1/dist/css/select2.css",
			"assets/jquery/select2-bootstrap-theme-master/dist/select2-bootstrap.min.css",
			'assets/adminlte/plugins/iCheck/all.css',
		);
		$data['js'] = array(

			"assets/jquery/jQuery-Mask-Plugin-master/dist/jquery.mask.min.js",
			"assets/jquery/select2-4.0.6-rc.1/dist/js/select2.min.js",
			'assets/adminlte/plugins/iCheck/icheck.min.js',
		);



		$data['pengguna_list'] = $this->global_model->get_pengguna();
		// die(json_encode($data['pengguna']));
		$data['kuasa_pengguna_list'] = null;
		if (!empty($data['id_pengguna'])) {
			$data['kuasa_pengguna_list'] = $this->switch_model->get_kuasa_pengguna($data['id_pengguna']);

			//   $data['kuasa_pengguna'] = $this->global_model->get_view_kuasa_pengguna($lokasi_explode->pengguna, $lokasi_explode->kuasa_pengguna);
			if (!empty($data['id_kuasa_pengguna'])) {
				$data['sub_kuasa_pengguna_list'] = $this->switch_model->get_sub_kuasa_pengguna($data['id_pengguna'], $data['id_kuasa_pengguna']);
				// die(json_encode($data['sub_kuasa_pengguna_list']));
				//     $data['sub_kuasa_pengguna'] = $this->global_model->get_view_sub_kuasa_pengguna($lokasi_explode->pengguna, $lokasi_explode->kuasa_pengguna, $lokasi_explode->sub_kuasa_pengguna);
			}
		}

		if ($data['kode_lokasi'])
			$data['lokasi'] = $this->switch_model->get_lokasi($data['kode_lokasi']);

		$data["content"] = $this->load->view("switch_lokasi", $data, TRUE);
		$data["title"]   = "Dashboard";
		$data['breadcrumb'] = array(
			array('label' => 'Dashboard', 'url' => '#', 'icon' => 'fa dashboard', 'li_class' => 'active'),
		);
		$this->load->view('template', $data);
	}

	public function action()
	{
		$session = $this->session->userdata('session');
		// die($this->input->post('kode_lokasi_baru'));
		if (!$this->input->post('kode_lokasi_baru')) {
			$this->index();
		} else {
			// $session->id_kode_lokasi = '5';
			$session->id_kode_lokasi = $this->input->post('kode_lokasi_baru');
			$row_kode_lokasi = $this->global_model->row_get_where('tbl_kode_lokasi',  array('id_kode_lokasi' => $session->id_kode_lokasi,));
			$session->kode_lokasi = $row_kode_lokasi['kode_lokasi'];
			$session->nama_lokasi = $row_kode_lokasi['instansi'];
			// die(json_encode($session->id_kode_lokasi));
			// die($this->input->post('kode_lokasi_baru'));
			// die(json_encode($this->session->userdata('session')));
			// echo "<script>alert('Success')</script>";
			// redirect(base_url('dashboard'));
			$this->session->set_flashdata('message', 'Berhasil Pindah Lokasi');
			redirect(base_url('dashboard'));
		}
	}


	public function get_pengguna()
	{
		$pengguna = $this->switch_model->get_pengguna($this->input->post('kode_jenis'));

		$data['option'] = '<option value="">Silahkan Pilih</option>';
		foreach ($pengguna as $key => $value) {
			$data['option'] .= '<option  value="' . $value->id_pengguna . '">' . $value->pengguna . ' - ' . $value->instansi . '</option>';
		}
		echo json_encode($data);
	}

	public function get_kuasa_pengguna()
	{
		$kuasa_pengguna = $this->switch_model->get_kuasa_pengguna($this->input->post('id_pengguna'));

		$data['option'] = '<option value="">Silahkan Pilih</option>';
		foreach ($kuasa_pengguna as $key => $value) {
			$data['option'] .= '<option  value="' . $value->id_kuasa_pengguna . '">' . $value->kuasa_pengguna . ' - ' . $value->instansi . '</option>';
		}
		echo json_encode($data);
	}

	public function get_sub_kuasa_pengguna()
	{
		$sub_kuasa_pengguna = $this->switch_model->get_sub_kuasa_pengguna($this->input->post('id_pengguna'), $this->input->post('id_kuasa_pengguna'));

		$data['option'] = '<option value="">Silahkan Pilih</option>';
		foreach ($sub_kuasa_pengguna as $key => $value) {
			$data['option'] .= '<option  value="' . $value->id_sub_kuasa_pengguna . '">' . $value->sub_kuasa_pengguna . ' - ' . $value->instansi . '</option>';
		}
		echo json_encode($data);
	}

	public function get_lokasi()
	{
		// $lokasi = $this->pengajuan_model->get_lokasi($this->input->post('id_pengguna'));
		$lokasi = $this->switch_model->get_lokasi($this->input->post('id_kode_lokasi'));

		$data['option'] = '<option value="">Silahkan Pilih</option>';
		foreach ($lokasi as $key => $value) {
			$data['option'] .= '<option  value="' . $value['id_kode_lokasi'] . '" selected>' . $value['kode_lokasi'] . ' - ' . $value['instansi'] . '</option>';
		}
		echo json_encode($data);
	}
}
/* End of file Dashboard.php */
/* Location: ./modules/dashboard/controllers/dashboard.php */
