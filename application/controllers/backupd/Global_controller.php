<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Global_controller extends CI_Controller
{

	function __construct()
	{
		parent::__construct();
		$this->load->library('Global_library');
		$this->load->helper('my_global');
	}

	public function index()
	{
		$this->load->view('welcome_message');
	}

	public function location()
	{
		if (!getRole(array('Super Admin')))	redirect('dashboard');

		$this->load->model('User_model');
		$data['css'] = array(
			"assets/jquery/select2-4.0.6-rc.1/dist/css/select2.min.css",
			'assets/sweetalert/sweetalert.css',
		);
		$data['js'] = array(
			"assets/jquery/select2-4.0.6-rc.1/dist/js/select2.min.js",
			'assets/sweetalert/sweetalert.min.js',
		);

		$data['list_pemilik'] = $this->global_model->get_pemilik();
		$data['list_lokasi'] = $this->User_model->getLokasi();
		$data['content'] = $this->load->view('location', $data, true);
		$this->load->view('template', $data);
	}

	public function set_location()
	{
		if (!getRole(array('Super Admin')))	redirect('dashboard');
		$pemilik = $this->global_model->get_pemilik_by_id($this->input->post('pemilik', TRUE));
		$kode_lokasi = $pemilik->kode . '.' . $this->input->post('kode_lokasi');
		$lokasi = $this->global_model->get_kode_lokasi($kode_lokasi);
		$session = $this->session->userdata('session');
		$session->id_kode_lokasi = $lokasi->id_kode_lokasi;
		$session->kode_lokasi = $kode_lokasi;
		$session->nama_lokasi = $lokasi->instansi;
		$session->id_pemilik = $pemilik->id_pemilik;
		$this->session->set_userdata('session', $session);
		$this->global_model->_logs($menu = 'location', $sub_menu = 'set_location', $tabel_name = 'tbl_kode_location', $action_id = $session->id_user, $action = 'set', $session);
		//($menu='location', $sub_menu='set_location', $tabel_name='tbl_kode_location', $action_id=$session->id_user, $action='set', $data=$data);
		$data['status'] = TRUE;
		echo json_encode($data);
	}

	public function get_objek()
	{
		$kode_jenis = $this->input->post('kode_jenis');
		$kode_objek = $this->global_model->get_objek($kode_jenis);

		$data['option'] = '<option value="">Please Select</option>';
		foreach ($kode_objek as $key => $value) {
			$data['option'] .= '<option  value="' . $value->kode_barang . '">' . $value->kode_objek . ' - ' . $value->nama_barang . '</option>';
		}
		echo json_encode($data);
	}

	public function get_rincian_objek()
	{
		$temp = $this->input->post('kode_barang');
		$kode = $kode = $this->global_library->explode_kode_barang(set_value('kode_barang'));

		// die(json_encode($temp));
		$kode_rincian_objek = $this->global_model->get_rincian_objek($kode['kode_jenis'], $kode['kode_objek']);

		$data['option'] = '<option value="">Please Select</option>';
		foreach ($kode_rincian_objek as $key => $value) {
			$data['option'] .= '<option  value="' . $value->kode_barang . '">' . $value->kode_rincian_objek . ' - ' . $value->nama_barang . '</option>';
		}
		echo json_encode($data);
	}

	public function get_sub_rincian_objek()
	{
		$temp = $this->input->post('kode_barang');
		$kode = $kode = $this->global_library->explode_kode_barang(set_value('kode_barang'));
		$kode_sub_rincian_objek = $this->global_model->get_sub_rincian_objek($kode['kode_jenis'], $kode['kode_objek'], $kode['kode_rincian_objek']);
		// die(json_encode($kode_sub_rincian_objek));
		$data['option'] = '<option value="">Please Select</option>';
		foreach ($kode_sub_rincian_objek as $key => $value) {
			$data['option'] .= '<option  value="' . $value->kode_barang . '">' . $value->kode_sub_rincian_objek . ' - ' . $value->nama_barang . '</option>';
		}
		echo json_encode($data);
	}

	public function get_sub_sub_rincian_objek()
	{
		$temp = $this->input->post('kode_barang');
		$kode = $kode = $this->global_library->explode_kode_barang(set_value('kode_barang'));
		// die(json_encode($kode));
		$kode_sub_sub_rincian_objek = $this->global_model->get_sub_sub_rincian_objek($kode['kode_jenis'], $kode['kode_objek'], $kode['kode_rincian_objek'], $kode['kode_sub_rincian_objek']);
		$data['option'] = '<option value="">Please Select</option>';
		foreach ($kode_sub_sub_rincian_objek as $key => $value) {
			// $data['option'] .= '<option  value="' . $value->kode_barang . '">' . $value->kode_sub_sub_rincian_objek . ' - ' . $value->nama_barang . '</option>';
			$data['option'] .= '<option  value="' . $value->kode_barang . '">' . $value->kode_sub_sub_rincian_objek . ' - ' . $value->nama_barang_simbada . '</option>';
		}
		echo json_encode($data);
	}

	//ATB======================================================================================
	public function get_rincian_objek_atb()
	{
		$temp = $this->input->post('kode_barang');
		$kode = $kode = $this->global_library->explode_kode_barang(set_value('kode_barang'));

		// die(json_encode($temp));
		$kode_rincian_objek = $this->global_model->get_rincian_objek_atb($kode['kode_jenis'], $kode['kode_objek']);

		$data['option'] = '<option value="">Please Select</option>';
		foreach ($kode_rincian_objek as $key => $value) {
			$data['option'] .= '<option  value="' . $value->kode_barang . '">' . $value->kode_rincian_objek . ' - ' . $value->nama_barang . '</option>';
		}
		echo json_encode($data);
	}

	public function get_sub_rincian_objek_atb()
	{
		$temp = $this->input->post('kode_barang');
		$kode = $kode = $this->global_library->explode_kode_barang(set_value('kode_barang'));
		$kode_sub_rincian_objek = $this->global_model->get_sub_rincian_objek_atb($kode['kode_jenis'], $kode['kode_objek'], $kode['kode_rincian_objek']);

		$data['option'] = '<option value="">Please Select</option>';
		foreach ($kode_sub_rincian_objek as $key => $value) {
			$data['option'] .= '<option  value="' . $value->kode_barang . '">' . $value->kode_sub_rincian_objek . ' - ' . $value->nama_barang . '</option>';
		}
		echo json_encode($data);
	}

	public function get_sub_sub_rincian_objek_atb()
	{
		$temp = $this->input->post('kode_barang');
		$kode = $kode = $this->global_library->explode_kode_barang(set_value('kode_barang'));
		$kode_sub_sub_rincian_objek = $this->global_model->get_sub_sub_rincian_objek_atb($kode['kode_jenis'], $kode['kode_objek'], $kode['kode_rincian_objek'], $kode['kode_sub_rincian_objek']);
		$data['option'] = '<option value="">Please Select</option>';
		foreach ($kode_sub_sub_rincian_objek as $key => $value) {
			// $data['option'] .= '<option  value="' . $value->kode_barang . '">' . $value->kode_sub_sub_rincian_objek . ' - ' . $value->nama_barang . '</option>';
			$nama_barang = "$value->nama_barang_simbada";
			if (($value->nama_barang_simbada == "Nama sesuai simbada"))
				$nama_barang = $value->nama_barang;
			$data['option'] .= '<option  value="' . $value->kode_barang . '">' . $value->kode_sub_sub_rincian_objek . ' - ' . $nama_barang . '</option>';
		}
		echo json_encode($data);
	}


	public function get_kuasa_pengguna_by_pengguna()
	{
	  $kuasa_pengguna = $this->global_model->get_kuasa_pengguna_by_pengguna($this->input->post('pengguna'));
  
	  $data['option'] = '<option value="">Silahkan Pilih</option>';
	  foreach ($kuasa_pengguna as $key => $value) {
		$data['option'] .= '<option  value="' . $value->id_kuasa_pengguna . '">' . $value->kuasa_pengguna . ' - ' . $value->instansi . '</option>';
	  }
	  echo json_encode($data);
	}



	function get_no_register($kode_lokasi, $kode_barang, $tahun_pengadaan)
	{
		$register = $this->global_model->get_no_register($kode_lokasi, $kode_barang, $tahun_pengadaan);
		$register_histori = $this->global_model->get_no_register_histori($kode_lokasi, $kode_barang, $tahun_pengadaan);
		$nomor_register = '1';
		if ($register and !$register_histori) {
			$last_regsiter = 0;
			$last_regsiter = intval($register->nomor_register);
			$last_regsiter++;
			$nomor_register = (string) $last_regsiter;
		} else if (!$register and $register_histori) { //bila di tabel kib sudah dihapus
			$last_regsiter = 0;
			$last_regsiter = intval($register_histori->nomor_register);
			$last_regsiter++;
			$nomor_register = (string) $last_regsiter;
		} else if ($register and $register_histori) {
			$last_regsiter = 0;
			$reg = intval($register->nomor_register);
			$reg_his = intval($register_histori->nomor_register);
			$last_regsiter = $reg >= $reg_his ? $reg : $reg_his;
			$last_regsiter++;
			$nomor_register = (string) $last_regsiter;
		}

		return $nomor_register = str_pad($nomor_register, 5, '0', STR_PAD_LEFT);
	}

	public function validasi_kib_f($kode_jenis)
	{
		$jenis = $this->global_model->kode_jenis[$kode_jenis];
		$id_kib = $this->input->post('id_kib');
		$data = null;
		if ($id_kib != '') {
			$kib = $this->global_model->get_kib($kode_jenis, $id_kib);
			if ($kib->validasi == '2') { //cek apakah sudah tervalidasi
				$data = array('validasi' => '1',);
			} else {
				$data = array('validasi' => '2',);
			}
			$this->global_model->update_kib($kode_jenis, $id_kib, $data);
			$this->global_model->_logs($menu = 'validasi', $sub_menu = $jenis['controller_name'], $tabel_name = $jenis['table'], $action_id = $id_kib, $action = 'update', $data);
			//($menu='location', $sub_menu='set_location', $tabel_name='tbl_kode_location', $action_id=$session->id_user, $action='set', $data=$data);
		}
		$data['status'] = true;
		echo json_encode($data);
	}

	public function validasi($kode_jenis)
	{
		$jenis = $this->global_model->kode_jenis[$kode_jenis];
		$id_kib = $this->input->post('id_kib');
		$kib = $this->global_model->get_kib($kode_jenis, $id_kib);

		$tanggal_validasi = date('Y-m-d', tgl_inter($this->input->post('tanggal_validasi')));
		$tgl_pengajuan = date('Y-m-d', strtotime($kib->tanggal_transaksi));
		
		$bulan = date('n', strtotime($tanggal_validasi));
		$tahun = date('Y', strtotime($tanggal_validasi));

		if ($tanggal_validasi >= $tgl_pengajuan) {
			//set histori barang
			// $this->global_model->set_histori_barang('entri', $value->id_kode_lokasi, $value->kode_jenis, $value->id_kib);

			$locked = $this->global_model->get_locked($bulan, $tahun, $kib->id_kode_lokasi);
			
			if ($locked < 1) {

				$data['result'] = $this->global_model->set_validasi($kode_jenis, $id_kib, $tanggal_validasi);
				foreach ($data['result'] as $key => $value) {
					//set_log
					$this->global_model->_logs($menu = 'validasi', $sub_menu = $jenis['controller_name'], $tabel_name = $jenis['table'], $action_id = $value->id_kib, $action = 'update', $value);
				}
				$data['status'] = true;
				echo json_encode($data);
			} else {
				echo json_encode(array('status' => FALSE, 'message' => 'Stock Opname Bulan ' . bulan_indo($bulan) . ' Tahun ' . $tahun . ' Telah Dikunci'));
			}
		} else {
			echo json_encode(array('status' => FALSE, 'message' => 'Tanggal Validasi Tidak Valid'));
		}
	}


	public function validasi_kib_atb($kode_jenis)
	{
		$jenis = $this->global_model->kode_jenis[$kode_jenis];
		// die(json_encode($jenis));
		$id_kib = $this->input->post('id_kib');
		$kib = $this->global_model->get_kib($kode_jenis, $id_kib);
		// die(json_encode($kib));
		//set histori barang
		// $this->global_model->set_histori_barang('entri', $value->id_kode_lokasi, $value->kode_jenis, $value->id_kib);$kib = $this->global_model->get_kib($kode_jenis, $id_kib);

		$tanggal_validasi = date('Y-m-d', tgl_inter($this->input->post('tanggal_validasi')));
		$tgl_pengajuan = date('Y-m-d', strtotime($kib->tanggal_transaksi));
		
		$bulan = date('n', strtotime($tanggal_validasi));
		$tahun = date('Y', strtotime($tanggal_validasi));

		if ($tanggal_validasi >= $tgl_pengajuan) {
			//set histori barang
			// $this->global_model->set_histori_barang('entri', $value->id_kode_lokasi, $value->kode_jenis, $value->id_kib);

			$locked = $this->global_model->get_locked($bulan, $tahun, $kib->id_kode_lokasi);

			if ($locked < 1) {
				$obj_kode_jenis = explode(".", $kode_jenis);
				// $data['result'] = $this->global_model->set_validasi_atb($obj_kode_jenis[1], $id_kib);
				$data['result'] = $this->global_model->set_validasi_atb($kode_jenis, $id_kib,$tanggal_validasi);
				foreach ($data['result'] as $key => $value) {
					//set_log
					$this->global_model->_logs($menu = 'validasi', $sub_menu = $jenis['controller_name'], $tabel_name = $jenis['table'], $action_id = $value->id_kib, $action = 'update', $value);
				}
				$data['status'] = true;
				echo json_encode($data);
			} else {
				echo json_encode(array('status' => FALSE, 'message' => 'Stock Opname Bulan ' . bulan_indo($bulan) . ' Tahun ' . $tahun . ' Telah Dikunci'));
			}
		} else {
			echo json_encode(array('status' => FALSE, 'message' => 'Tanggal Validasi Tidak Valid'));
		}
	}


	public function check_all($kode_jenis)
	{
		$jenis = $this->global_model->kode_jenis[$kode_jenis];
		$list_id = $this->input->post('list_id');
		$tanggal_validasi = date('Y-m-d', tgl_inter($this->input->post('tanggal_validasi')));
		
		$bulan = date('n', strtotime($tanggal_validasi));
		$tahun = date('Y', strtotime($tanggal_validasi));

		$id = explode(',',$list_id);

		for($i = 0; $i<count($id); $i++){
			
			if($id[$i] != ''){
			$kib = $this->global_model->get_kib($kode_jenis, $id[$i]);
			$tgl_pengajuan = date('Y-m-d', strtotime($kib->tanggal_transaksi));
			
				if ($tanggal_validasi < $tgl_pengajuan) {
					echo json_encode(array('status' => FALSE, 'message' => 'Tanggal Validasi Tidak Valid'));
					exit;
				}
				
				$locked = $this->global_model->get_locked($bulan, $tahun, $kib->id_kode_lokasi);
				
				if ($locked > 0) {
					echo json_encode(array('status' => FALSE, 'message' => 'Stock Opname Bulan ' . bulan_indo($bulan) . ' Tahun ' . $tahun . ' Telah Dikunci'));
					exit;
				}
			}
		}
		// print_r(count($id)); die;

		// print_r($list_id); die;
		$data['result'] = $this->global_model->set_validasi($kode_jenis, $list_id, $tanggal_validasi);

		foreach ($data['result'] as $key => $value) {
			// //set histori barang
			// $this->global_model->set_histori_barang('entri', $value->id_kode_lokasi, $value->kode_jenis, $value->id_kib);
			//set_log
			$this->global_model->_logs($menu = 'validasi', $sub_menu = $jenis['controller_name'], $tabel_name = $jenis['table'], $action_id = $value->id_kib, $action = 'update', $value);
		}

		$data['status'] = true;
		echo json_encode($data);
	}

	public function reject_kib($kode_jenis = null)
	{
		$jenis = $this->global_model->kode_jenis[$kode_jenis];
		$id_kib = $this->input->post('id_kib');
		$note = $this->input->post('note');

		$data = array(
			'validasi' => '3',
			'reject_note' => $note,
			'updated_at' => date('Y-m-d H:i:s'),
		);
		$this->global_model->update_kib($kode_gol = $kode_jenis, $id_kib, $data);
		$this->global_model->_logs($menu = 'validasi', $sub_menu = $jenis['controller_name'], $tabel_name = $jenis['table'], $action_id = $id_kib, $action = 'update', $data, $feature = 'reject');
		//($menu='location', $sub_menu='set_location', $tabel_name='tbl_kode_location', $action_id=$session->id_user, $action='set', $data=$data, $feature);
		$data['status'] = TRUE;
		echo json_encode($data);
	}



	public function get_kode_lokasi()
	{
		$id_pemilik = $this->input->post('kode_pemilik');
		$pemilik = $this->global_model->get_pemilik_by_id($id_pemilik);

		$kode_lokasi = $this->session->userdata('session')->kode_lokasi;
		$nama_lokasi = $this->session->userdata('session')->nama_lokasi;
		$data['kode_lokasi'] = $pemilik->kode . '.' . $kode_lokasi;
		$data['nama_lokasi'] = $nama_lokasi;
		echo json_encode($data);
	}


	public function get_barang_kib()
	{
		$result = $this->global_model->get_barang_kib($this->input->post('kode_gol'));

		$data['option'] = null;
		foreach ($result as $key => $value) {
			$data['option'] .= '<option  value="' . $value->id_kode_barang . '">' . $value->nama_barang . '</option>';
		}

		echo json_encode($data);
	}

	public function get_request_validasi()
	{
		$req_validasi = $this->global_model->get_request_validasi();
		echo json_encode($req_validasi);
	}

	function get_kode_barang_kib_f($kode_jenis)
	{
		$result = $this->global_model->get_kode_barang_kib_f($kode_jenis);
		echo json_encode($result);
	}


	function get_rekening()
	{
		$id_sumber_dana = $this->input->post("id_sumber_dana");

		$rekening = $this->global_model->get_rekening($id_sumber_dana);

		$data['option'] = '<option value="">Silahkan Pilih</option>';
		foreach ($rekening as $key => $value) {
			if(substr($value['kode_rekening'],-1) != '*') {
				$data['option'] .= '<option  value="' . $value['id_rekening'] . '">' . $value['kode_rekening'] . ' - ' . $value['nama_rekening'] . '</option>';
			}
		}
		echo json_encode($data);
	}

	function set_histori_barang($status_histori = null, $id_kode_lokasi = null, $kode_jenis = null, $id_kib = null)
	{
		//$last_histori = $this->global_model->get_last_histori_barang(array('kode_jenis' => $kode_jenis,'id_kib'=>$id_kib ));
		// $data_insert['tanggal_histori'] = date('Y-m-d');
		// $data_insert['status_histori'] = $status_histori;
		// $data_insert['kode_jenis'] =
		// $data_insert['id_kib'] =
		// $data_insert['id_kode_lokasi'] = $id_kode_lokasi;
		// $data_insert['id_kode_barang'] =
		// $data_insert['jumlah_awal'] =
		// $data_insert['harga_awal'] =
		// $data_insert['jumlah'] =
		// $data_insert['harga'] =
		// $data_insert['jumlah_akhir'] =
		// $data_insert['harga_akhir'] =

	}

	public function cek_stock_opname()
	{
		$bulan = $this->input->post('bulan');
		$tahun = $this->input->post('tahun');
		$session = $this->session->userdata('session');
		$locked = $this->global_model->get_locked($bulan, $tahun, $session->id_kode_lokasi);
		if($locked > 0){
			echo json_encode(array('status'=> FALSE, 'message'=> 'Stock Opname Bulan '.bulan_indo($bulan).' Telah Dikunci'));
		}else{
			echo json_encode(array('status' => TRUE));
		}
	}
	

	function maintenance()
	{
		$this->load->view('maintenance');
	}
}
