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

		ini_set('memory_limit', '124M');

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

	// public function generate_register()
	// {
	// 	$kib = $this->dashboard_model->get_kib();

	// 	foreach($kib as $key){

	// 		$kode_jenis = '05';
	// 		$tabel		= 'tbl_kib_e';
	// 		$id_tabel	= 'id_kib_e';
	// 		$id_kode_lokasi = $key['id_kode_lokasi'];
	// 		$id_kode_barang	= $key['id_kode_barang'];
	// 		$id_kib			= $key['id_kib_e'];
	// 		$tanggal_perolehan = $key['tanggal_perolehan'];

	// 		$this->dashboard_model->generate_register($kode_jenis,$tabel,$id_tabel,$id_kib,$id_kode_lokasi,$id_kode_barang,$tanggal_perolehan);
	// 	}

	// 		redirect('https://aset.jogjakota.go.id/');
	// }
	
// 	public function generate_histori()
// 	{
// 		$kib = $this->dashboard_model->get_fix_histori();
// // print_r($kib[0]); die;
// 		foreach($kib as $key){

// 			$kode_jenis = '05';
// 			$id_sumber_dana		= $key['id_sumber_dana'];
// 			$id_rekening	= $key['id_rekening'];
// 			$id_kode_lokasi = $key['id_kode_lokasi'];
// 			$ket	= 'proc_validasi';
// 			$id_kib			= $key['id_kib_e'];
// 			$tgl_validasi = $key['tanggal_transaksi'];

// 			$this->dashboard_model->generate_histori($id_kode_lokasi,$kode_jenis,$id_kib,$tgl_validasi,$ket,$id_sumber_dana,$id_rekening);
// 		}

// 			redirect('https://aset.jogjakota.go.id/');
// 	}
	
	
// 	public function generate_rekap()
// 	{
// 		$lokasi = $this->dashboard_model->get_lokasi();
// 			foreach($lokasi as $key){
// 				for($i=7; $i<13; $i++){

// 				$data['session'] = 1;
// 				$data['id_pemilik'] = '2';
// 				$data['id_kode_lokasi'] =  $key['id_kode_lokasi'];
// 				$data['start_bulan'] = $i;
// 				$data['start_tahun'] = '2022';
// 				$data['last_bulan'] = $i;
// 				$data['last_tahun'] = '2022';
// 				$data['intra_ekstra'] = '01';
				
// 				$this->dashboard_model->generate_rekap_mutasi($data);

// 				}
// 			}
		

// // die('aa');
// 			redirect('https://google.com/');
// 	}

	
	public function inject_penyusutan()
	{
		$lokasi = $this->dashboard_model->get_lokasi();
			foreach($lokasi as $key){
				
// CALL proc_rekap_penyusutan_objek_inject(1,2,3,'','2022-12-31', '01');
				$this->dashboard_model->generate_rekap_penyusutan($key['id_kode_lokasi']);

			}
		

// die('aa');
			redirect('https://google.com/');
	}
	
	
// 	public function generate_penyusutan__()
// 	{
// 		$lokasi = $this->dashboard_model->get_lokasi();
// 			foreach($lokasi as $key){

// 				$data['session'] = $this->session->userdata('session');
// 				$data['id_kode_lokasi'] =  $key['id_kode_lokasi'];

// 				$this->dashboard_model->generate_penyusutan($data);
// 			}
		

// // // die('aa');
// 			redirect('google.com/');
// 	}

	// public function validasi__()
	// {
	// 	$list_id = $this->dashboard_model->get_kib_validasi();
	// 	$kode_jenis = '05';
	// 	$jenis = $this->global_model->kode_jenis[$kode_jenis];

	// 	foreach($list_id as $key){
	// 		// print_r($key->id_kib_e); die;
	// 		if($key->id_kib_e != ''){
	// 		$kib = $this->global_model->get_kib($kode_jenis, $key->id_kib_e);
	// 		$bulan = date('n', strtotime($kib->tanggal_transaksi));
	// 		$tahun = date('Y', strtotime($kib->tanggal_transaksi));
	// 		$tgl_pengajuan = date('Y-m-d', strtotime($kib->tanggal_transaksi));
				
	// 			// $locked = $this->global_model->get_locked($bulan, $tahun, $kib->id_kode_lokasi);
				
	// 			// if ($locked < 1) {
	// 				$data['result'] = $this->global_model->set_validasi($kode_jenis, $key->id_kib_e, $kib->tanggal_transaksi);
					
	// 				// foreach ($data['result'] as $key => $value) {
	// 					// //set histori barang
	// 					// $this->global_model->set_histori_barang('entri', $value->id_kode_lokasi, $value->kode_jenis, $value->id_kib);
	// 					//set_log
	// 					$this->global_model->_logs($menu = 'validasi', $sub_menu = $jenis['controller_name'], $tabel_name = $jenis['table'], $action_id = $key->id_kib_e, $action = 'update', $key->id_kib_e);
	// 				// }
			
	// 			// }else{
					
	// 			// 	echo json_encode(array('status' => FALSE, 'message' => 'Stock Opname Bulan ' . bulan_indo($bulan) . ' Tahun ' . $tahun . ' Telah Dikunci'));
	// 			// 	exit;
	// 			// 	break;
	// 			// }
	// 		}
	// 	}
	// 	// print_r(count($id)); die;

	// 	// print_r($id); die;
	// 	echo 'OK';

	// }

	// public function peneghapusan__()
	// {
	// 	$barang = $this->dashboard_model->get_barang_penghapusan();
	// 	foreach($barang as $key){
	// 		$data['id_kode_lokasi'] = $key->id_kode_lokasi;
	// 		$data['id_kib'] = $key->id_kib;
	// 		$data['kode_jenis'] = $key->kode_jenis;
	// 		$data['tanggal_transaksi'] = '2022-08-31';
	// 		$data['jenis_sk'] = '2';
	// 		$data['id_penghapusan_barang'] = $key->id_penghapusan_barang;

	// 		$this->dashboard_model->generate_penghapusan($data);
	// 	}
	// 	return 'ok';
	// }

// 	function inject__(){
		
// 		$this->load->model('kib_e/Kib_e_model');

// 		$inject = $this->dashboard_model->get_inject_data();

// 		foreach($inject as $key){
// 		$no = $key['no'];
// 		$harga = $key['harga'];
// 		$jumlah_barang = $key['jumlah'];
// 		$id = $key['id_kib'];
// 		$input_banyak = $key['input_banyak'];
// 		$decimal = $key['koma'];

// 		$kib = $this->Kib_e_model->get_by_id($id);
		
// 		$data = array(
// 		'id_kode_barang' => $kib->id_kode_barang,
// 		'id_pemilik' => $kib->id_pemilik,
// 		'id_kode_lokasi' => $kib->id_kode_lokasi,
// 		'nama_barang' => $kib->nama_barang,
// 		'kode_barang' => $kib->kode_barang,
// 		'kondisi' => $kib->kondisi,
// 		'judul_pencipta' => $kib->deskripsi,
// 		'spesifikasi' => $kib->spesifikasi,
// 		'kesenian_asal_daerah' => $kib->kesenian_asal_daerah,
// 		'kesenian_pencipta' => $kib->kesenian_pencipta,
// 		'kesenian_bahan' => $kib->kesenian_bahan,
// 		'hewan_tumbuhan_jenis' => $kib->hewan_tumbuhan_jenis,
// 		'hewan_tumbuhan_ukuran' => $kib->hewan_tumbuhan_ukuran,
// 		'jumlah' => 1, 
// 		'tahun_pembelian' => $kib->tahun_pembelian,
// 		'asal_usul' => 'Lain-lain', //1
// 		'harga' => $harga,
// 		'keterangan' => $kib->keterangan,
// 		'deskripsi' => 'Urai Hasil Tindak Lanjut LHP BPK 2021',
// 		'kode_lokasi' => $kib->kode_lokasi,
// 		'tanggal_transaksi' => date('Y-m-d', strtotime('2022-12-01')), // 5
// 		'nomor_transaksi' => 'TindakLanjutLHPBPK2021', //4
// 		'tanggal_pembelian' => date('Y-m-d', strtotime($kib->tahun_pembelian.'-12-01')), //2
// 		'tanggal_perolehan' =>  date('Y-m-d', strtotime($kib->tahun_pembelian.'-12-01')), //3
// 		'id_sumber_dana' => '5', // 6
// 		'id_rekening' => '1753', // 7
// 		'satuan' => $kib->satuan,
// 		'umur_ekonomis' => $kib->umur_ekonomis,

// 		'created_at' => '2022-12-12 00:00:00',
// 		'updated_at' => date('Y-m-d H:i:s'),
// 		);

// // print_r($data); die;
// 		if ($jumlah_barang > 1) {
// 		$insert_id = $this->Kib_e_model->insert($data); //ambil id untuk input_banyak
// 		$this->Kib_e_model->update($insert_id, array('input_banyak' => $insert_id,)); //update insert yang pertama
// 		$data['input_banyak'] = $insert_id;
// 		$jumlah_barang--; //karena sudah insert 1 kali di atas
// 		for ($jumlah_barang; $jumlah_barang > 0; $jumlah_barang--) {

// 			if($jumlah_barang == 1 && $decimal != 0){
// 				$j = $key['jumlah']-1;
// 				$total = $j*$harga;
// 				$harga = $kib->harga - $total;
// 				$data['harga'] = $harga;
// 			}
// 			$this->Kib_e_model->insert($data);
// 			$this->global_model->_logs($menu = 'data_kib', $sub_menu = 'kib_e', $tabel_name = 'tbl_kib_e', $action_id = null, $action = 'insert', $data = $data);
// 			// $data['created_at'] = date('Y-m-d H:i:s');
// 			// $data['updated_at'] = date('Y-m-d H:i:s');
// 		}
// 		$this->dashboard_model->update_inject_data($no,$insert_id);
// 		}else {
// 			$insert_id = $this->Kib_e_model->insert($data); //ambil id untuk input_banyak
// 			$this->dashboard_model->update_inject_data($no,$insert_id);
// 			$this->global_model->_logs($menu = 'data_kib', $sub_menu = 'kib_e', $tabel_name = 'tbl_kib_e', $action_id = null, $action = 'insert', $data = $data);
// 		  }

// 		// if($decimal != 0){
// 		// 	$this->dashboard_model->update_inject_decimal($no,$insert_id);
// 		// }
// 	}
// 		print_r('OK'); die;
// 	}
	

// 	function inject__e(){
		

// 		$inject = $this->dashboard_model->get_inject_data_e();

// 		foreach($inject as $key){
// 			$jumlah_barang = $key->jumlah;
		
// 		$data = array(
// 		'id_kode_barang' => $key->id_kode_barang,
// 		'id_pemilik' =>'2',
// 		'id_kode_lokasi' => $key->id_kode_lokasi,
// 		'nama_barang' => $key->nama_barang,
// 		'kode_barang' => $key->kode_barang,
// 		'kondisi' => $key->kondisi,
// 		'judul_pencipta' => $key->judul_pencipta,
// 		// 'spesifikasi' => $key->spesifikasi,
// 		// 'kesenian_asal_daerah' => $key->kesenian_asal_daerah,
// 		'kesenian_pencipta' => $key->kesenian_pencipta,
// 		'kesenian_bahan' => $key->kesenian_bahan,
// 		// 'hewan_tumbuhan_jenis' => $key->hewan_tumbuhan_jenis,
// 		// 'hewan_tumbuhan_ukuran' => $key->hewan_tumbuhan_ukuran,
// 		'jumlah' => 1, 
// 		'tahun_pembelian' => $key->tahun_pembelian,
// 		'asal_usul' => 'Lain-lain', //1
// 		'harga' => $key->harga_satuan,
// 		'keterangan' => $key->keterangan,
// 		'deskripsi' => 'Urai Hasil Tindak Lanjut LHP BPK 2021',
// 		// 'kode_lokasi' => $key->kode_lokasi,
// 		'tanggal_transaksi' => date('Y-m-d', strtotime('2022-12-01')), // 5
// 		'nomor_transaksi' => 'TindakLanjutLHPBPK2021', //4
// 		'tanggal_pembelian' => date('Y-m-d', strtotime($key->tahun_pembelian.'-12-01')), //2
// 		'tanggal_perolehan' =>  date('Y-m-d', strtotime($key->tahun_pembelian.'-12-01')), //3
// 		'id_sumber_dana' => '5', // 6
// 		'id_rekening' => '1753', // 7
// 		// 'satuan' => $key->satuan,
// 		// 'umur_ekonomis' => $key->umur_ekonomis,

// 		'created_at' => '2022-12-12 00:00:00',
// 		'updated_at' => '2022-12-12 00:00:00',
// 		);

// // print_r($data); die; break;
// 		if ($jumlah_barang > 1) {
// 		$insert_id = $this->dashboard_model->inject_e($data); //ambil id untuk input_banyak
// 		$this->dashboard_model->update_inject_e($insert_id, array('input_banyak' => $insert_id,)); //update insert yang pertama
// 		$data['input_banyak'] = $insert_id;
// 		$jumlah_barang--; //karena sudah insert 1 kali di atas
// 		// die; break;
// 		for ($jumlah_barang; $jumlah_barang > 0; $jumlah_barang--) {

// 			if($jumlah_barang == 1){
// 				$data['harga'] = $key->harga_fix;
// 			}
// 			$this->dashboard_model->inject_e($data);
// 			// $this->global_model->_logs($menu = 'data_kib', $sub_menu = 'kib_e', $tabel_name = 'tbl_kib_e', $action_id = null, $action = 'insert', $data = $data);
// 			// $data['created_at'] = date('Y-m-d H:i:s');
// 			// $data['updated_at'] = date('Y-m-d H:i:s');
// 		}
// 		}else {
// 			$insert_id = $this->dashboard_model->inject_e($data); //ambil id untuk input_banyak
// 			// $this->global_model->_logs($menu = 'data_kib', $sub_menu = 'kib_e', $tabel_name = 'tbl_kib_e', $action_id = null, $action = 'insert', $data = $data);
// 		  }

// 		// if($decimal != 0){
// 		// 	$this->dashboard_model->update_inject_decimal($no,$insert_id);
// 		// }
// 	}
// 		print_r('OK'); die;
// 	}
}
/* End of file Dashboard.php */
/* Location: ./modules/dashboard/controllers/dashboard.php */
