<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Dashboard extends MX_Controller
{

	function __construct()
	{
		parent::__construct();
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
		$this->load->model('Dashboard_model', 'dashboard_model');
	}
	public function index()
	{


		$data['css'] = array(
		// 	"assets/bootstrap/bootstrap-datepicker-master/dist/css/bootstrap-datepicker.min.css",
        'assets/sweetalert/sweetalert.css',
		);
		$data['js'] = array(
			// "assets/highchart/code/highcharts.js",
			// "assets/highchart/code/modules/data.js",
			// "assets/highchart/code/modules/drilldown.js",
			// "assets/highchart/code/modules/exporting.js",
			// "assets/highchart/code/modules/export-data.js",
			// "assets/highchart/code/modules/accessibility.js",
        'assets/sweetalert/sweetalert.min.js',
		);

		$data['session'] = $this->session->userdata('menu');
		$data['validasi'] = false;
		foreach ($data['session'] as $key => $value) {
			if ($value['name'] == 'Validasi')	$data['validasi'] = TRUE;
		}

		$session = $this->session->userdata('session');
		// die(json_encode($session->id_kode_lokasi));
		$data['jenis'] = $this->global_model->kode_jenis;
		$request_validasi_a = $this->global_model->get_request_validasi_a();
		$request_validasi_b = $this->global_model->get_request_validasi_b();
		$request_validasi_c = $this->global_model->get_request_validasi_c();
		$request_validasi_d = $this->global_model->get_request_validasi_d();
		$request_validasi_e = $this->global_model->get_request_validasi_e();
		$request_validasi_f = $this->global_model->get_request_validasi_f();
		$request_validasi_atb = $this->global_model->get_request_validasi_atb();
		$data['request_validasi']= array($request_validasi_a,$request_validasi_b,$request_validasi_c,$request_validasi_d,$request_validasi_e,$request_validasi_f,$request_validasi_atb);
		//$data['request_validasi'] = $this->global_model->get_request_validasi();
		 //die(json_encode($data['request_validasi']));
		
		$nominal_kib_a = $this->dashboard_model->get_nominal_kib_a($session->id_kode_lokasi);
		$nominal_kib_b = $this->dashboard_model->get_nominal_kib_b($session->id_kode_lokasi);
		$nominal_kib_c = $this->dashboard_model->get_nominal_kib_c($session->id_kode_lokasi);
		$nominal_kib_d = $this->dashboard_model->get_nominal_kib_d($session->id_kode_lokasi);
		$nominal_kib_e = $this->dashboard_model->get_nominal_kib_e($session->id_kode_lokasi);
		$nominal_kib_f = $this->dashboard_model->get_nominal_kib_f($session->id_kode_lokasi);
		$nominal_kib_atb = $this->dashboard_model->get_nominal_kib_atb($session->id_kode_lokasi);
		$nominal_kib_kemitraan = $this->dashboard_model->get_nominal_kib_kemitraan($session->id_kode_lokasi);
		$nominal_kib_lain = $this->dashboard_model->get_nominal_kib_lain($session->id_kode_lokasi);
		$data['nominal_kib'] = array($nominal_kib_a,$nominal_kib_b,$nominal_kib_c,$nominal_kib_d,$nominal_kib_e,$nominal_kib_f,$nominal_kib_atb,$nominal_kib_kemitraan,$nominal_kib_lain);
		
		//$data['nominal_kib'] = $this->dashboard_model->get_nominal_kib_dashboard($session->id_kode_lokasi);
		$data['penghapusan_pengecekan'] = $this->dashboard_model->get_request_penghapusan_pengecekan();
		$data['mutasi_penerimaan'] = $this->dashboard_model->get_request_mutasi_penerimaan($session->id_kode_lokasi);
		$data['mutasi_validasi'] = $this->dashboard_model->get_request_mutasi_validasi($session->id_kode_lokasi);
		$data['kapitalisasi'] = $this->dashboard_model->get_request_kapitalisasi();
		$data['reklas'] = $this->dashboard_model->get_request_reklas();
		
		// die(json_encode($data['nominal_kib_atb']));
		$data["content"] = $this->load->view("dashboard", $data, TRUE);
		$data["title"]   = "Dashboard";
		$data['breadcrumb'] = array(
			array('label' => 'Dashboard', 'url' => '#', 'icon' => 'fa dashboard', 'li_class' => 'active'),
		);
		$this->load->view('template', $data);
	}

	public function generate_register()
	{
		$kib = $this->dashboard_model->get_kib();

		foreach($kib as $key){

			$kode_jenis = '5.03';
			$tabel		= 'tbl_kib_atb';
			$id_tabel	= 'id_kib_atb';
			$id_kode_lokasi = $key['id_kode_lokasi'];
			$id_kode_barang	= $key['id_kode_barang'];
			$id_kib			= $key['id_kib_atb'];
			$tanggal_perolehan = $key['tanggal_perolehan'];

			$this->dashboard_model->generate_register($kode_jenis,$tabel,$id_tabel,$id_kib,$id_kode_lokasi,$id_kode_barang,$tanggal_perolehan);
		}

			redirect('https://aset.jogjakota.go.id/');
	}
	
	public function generate_rekap()
	{
		$lokasi = $this->dashboard_model->get_lokasi();
		for($i=4; $i<=12; $i++){
			foreach($lokasi as $key){

				$data['session'] = $this->session->userdata('session');
				$data['id_pemilik'] = '2';
				$data['id_kode_lokasi'] =  $key['id_kode_lokasi'];
				$data['start_bulan'] = $i;
				$data['start_tahun'] = '2021';
				$data['last_bulan'] = $i;
				$data['last_tahun'] = '2021';
				$data['intra_ekstra'] = '00';

				$this->dashboard_model->generate_rekap_mutasi($data);
			}
		}


			redirect('https://jss.jogjakota.go.id/');
	}
}
/* End of file Dashboard.php */
/* Location: ./modules/dashboard/controllers/dashboard.php */
