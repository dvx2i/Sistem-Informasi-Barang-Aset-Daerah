<?php

if (!defined('BASEPATH'))
	exit('No direct script access allowed');

class Kib_atb extends MX_Controller
{
	protected $kode_jenis = '5.03';
	function __construct()
	{
		parent::__construct();
		$this->global_model->cek_hak_akses('90');
		$this->load->model('Tbl_kib_atb_model');
		$this->load->library('form_validation');
		$this->load->library('datatables');
		$this->load->library('Global_library');
		$this->load->helper('my_global');
		$this->form_validation->CI = &$this;
	}

	public function index()
	{
		//HAPUS SESSION REKLAS
		 $this->session->unset_userdata('data_reklas');

    $data['css'] = array(
      'assets/datatables/dataTables.min.css',
      "assets/jquery/select2-4.0.6-rc.1/dist/css/select2.min.css",
      "assets/jquery/select2-bootstrap-theme-master/dist/select2-bootstrap.min.css",
	  'assets/sweetalert/sweetalert2.css',
    );

    $data['js'] = array(
    //   'assets/datatables/jquery.dataTables.js',
    //   'assets/datatables/dataTables.bootstrap.js',
      "assets/jquery/select2-4.0.6-rc.1/dist/js/select2.min.js",
	  'assets/sweetalert/sweetalert2.min.js',
    );
    $session = $this->session->userdata('session');
    // die(json_encode(substr($session->kode_lokasi, -1)));
    $data['show_lokasi'] = false;
    if (substr($session->kode_lokasi, -1) == "*")
      $data['show_lokasi'] = true;

    //filter

    $data['pemilik'] = $this->global_model->get_pemilik();
    $data['intra_ekstra'] = array(
      '00' => 'Semua',
      '01' => 'Intrakomptable',
      '02' => 'Extrakomptable',
    );

    $session = $this->session->userdata('session');
    $lokasi_explode = $this->global_model->get_kode_lokasi_by_id($session->id_kode_lokasi);

    $data['pengguna_list'] = $this->global_model->get_pengguna();
    // die(json_encode($data['pengguna_list']));
    $data['pengguna'] = $this->global_model->get_view_pengguna($lokasi_explode->pengguna);
    // if (empty($data['pengguna'])) $data['pengguna']->id_pengguna = "";

    // die(json_encode($lokasi_explode->pengguna));
    if ($data['pengguna']) {
      $data['kuasa_pengguna_list'] = $this->global_model->get_kuasa_pengguna_by_pengguna($data['pengguna']->pengguna);
      $data['kuasa_pengguna'] = $this->global_model->get_view_kuasa_pengguna($lokasi_explode->pengguna, $lokasi_explode->kuasa_pengguna);
      // if ($data['kuasa_pengguna']) {
      //   $data['sub_kuasa_pengguna_list'] = $this->global_model->get_sub_kuasa_pengguna($data['kuasa_pengguna']->id_pengguna, $data['kuasa_pengguna']->id_kuasa_pengguna);
      //   $data['sub_kuasa_pengguna'] = $this->global_model->get_view_sub_kuasa_pengguna($lokasi_explode->pengguna, $lokasi_explode->kuasa_pengguna, $lokasi_explode->sub_kuasa_pengguna);
      // }
    }
    //filter

    $data['menu'] = $this->global_model->kode_jenis[$this->kode_jenis];
		$data["content"] = $this->load->view('tbl_kib_atb_list', $data, TRUE);
		$data['breadcrumb'] = array(
			array('label' => 'Data KIB', 'url' => '#', 'icon' => 'fa fa-pie-chart', 'li_class' => ''),
			array('label' => 'KIB ATB', 'url' => '#', 'icon' => '', 'li_class' => 'active'),
		);
		$this->load->view('template', $data);
	}

	public function json()
	{
		// die("ok");
		header('Content-Type: application/json');
		echo $this->Tbl_kib_atb_model->json();
	}

	public function read($id)
	{
		$id = decrypt_url($id);
		$row = $this->Tbl_kib_atb_model->get_by_id($id);
		if ($row) {
			$data = array(
				'menu' => $this->global_model->kode_jenis[$this->kode_jenis]['nama'],
				'id_kib_atb' => $row->id_kib_atb,
				'id_pemilik' => $row->id_pemilik,
				'id_kode_barang' => $row->id_kode_barang,
				'id_kode_lokasi' => $row->id_kode_lokasi,
				'kode_barang' => $row->kode_lokasi,
				'nama_barang' => $row->nama_barang,
				'nomor_register' => $row->nomor_register,
				'judul_kajian_nama_software' => $row->judul_kajian_nama_software,
				'tanggal_perolehan' => tgl_indo($row->tanggal_perolehan), // $row->tanggal_perolehan,
				'asal_usul' => $row->asal_usul,
				'keterangan' => $row->keterangan,
				'deskripsi' => $row->deskripsi,
				'kode_lokasi' => $row->kode_lokasi,
				'validasi' => $row->validasi,
				'harga' => my_format_number($row->harga),
				// 'created_at' => $row->created_at,
				// 'updated_at' => $row->updated_at,
			);
			// $this->load->view('tbl_kib_atb_read', $data);
			$data['content'] = $this->load->view('tbl_kib_atb_read', $data, TRUE);
			$this->load->view('template', $data);
		} else {
			$this->session->set_flashdata('message', 'Data Tidak Ditemukan');
			redirect(base_url('tbl_kib_atb'));
		}
	}

	public function create()
	{

		$pemilik = $this->global_model->get_pemilik_by_id($this->default_pemilik);
		$kode_lokasi = remove_star($pemilik->kode . '.' . $this->session->userdata('session')->kode_lokasi);
		$data = array(
			'button' => 'Buat',
			'menu' => $this->global_model->kode_jenis[$this->kode_jenis]['nama'],
			'action' => base_url('kib_atb/create_action'),
			'id_kib_atb' => set_value('id_kib_atb'),
			'sumber_dana' => set_value('sumber_dana'),
			'rekening' => set_value('rekening'),
			// 'id_pemilik' => set_value('id_pemilik', $this->default_pemilik),
			// 'id_kode_barang' => set_value('id_kode_barang'),
			// 'id_kode_lokasi' => set_value('id_kode_lokasi'),
			'kode_barang' => set_value('kode_barang'),
			'nama_barang' => set_value('nama_barang'),
			'kode_objek' => set_value('kode_objek'),
			'kode_rincian_objek' => set_value('kode_rincian_objek'),
			'kode_sub_rincian_objek' => set_value('kode_sub_rincian_objek'),
			'kode_sub_sub_rincian_objek' => set_value('kode_sub_sub_rincian_objek'),
			'status_pemilik' => set_value('status_pemilik', $this->default_pemilik),
			'nama_lokasi' => set_value('nama_lokasi', $this->session->userdata('session')->nama_lokasi),


			'nomor_register' => set_value('nomor_register'),
			'judul_kajian_nama_software' => set_value('judul_kajian_nama_software'),
			'tanggal_perolehan' => set_value('tanggal_perolehan'),
			'asal_usul' => set_value('asal_usul'),
			'harga' => set_value('harga'),
			'satuan' => set_value('satuan'),
			'umur_ekonomis' => set_value('umur_ekonomis'),
			'keterangan' => set_value('keterangan'),
			'deskripsi' => set_value('deskripsi'),
			'kode_lokasi' => set_value('kode_lokasi', $kode_lokasi),

			'tanggal_transaksi' => set_value('tanggal_transaksi'),
			'nomor_transaksi' => set_value('nomor_transaksi'),

			'jumlah_barang' => set_value('jumlah_barang', 1),
			// 'validasi' => set_value('validasi'),
			// 'created_at' => set_value('created_at'),
			// 'updated_at' => set_value('updated_at'),
			'validasi' => set_value('validasi'),
		);

		$data['css'] = array(
			"assets/bootstrap/bootstrap-datepicker-master/dist/css/bootstrap-datepicker.min.css",
			"assets/jquery/select2-4.0.6-rc.1/dist/css/select2.min.css",
			"assets/jquery/select2-bootstrap-theme-master/dist/select2-bootstrap.min.css",
			'assets/sweetalert/sweetalert.css',
			'assets/adminlte/plugins/iCheck/all.css',
			"assets/css/kib.css",
		);
		$data['js'] = array(
			"assets/bootstrap/bootstrap-datepicker-master/dist/js/bootstrap-datepicker.min.js",
			"assets/bootstrap/bootstrap-datepicker-master/dist/locales/bootstrap-datepicker.id.min.js",
			"assets/jquery/jQuery-Mask-Plugin-master/dist/jquery.mask.min.js",
			"assets/jquery/select2-4.0.6-rc.1/dist/js/select2.min.js",
			'assets/adminlte/plugins/iCheck/icheck.min.js',
			'assets/sweetalert/sweetalert.min.js',
			"assets/js/kib.js",
		);


		//REKLAS CONDITION
		$data['data_reklas'] = null;
		$data_reklas = $this->session->userdata('data_reklas');
		if (!empty($data_reklas)) {
			$kode_jenis_asal = $this->global_model->kode_jenis[$data_reklas['kode_jenis']];
			$kib_asal = $this->global_model->row_get_where('view_kib', array('kode_jenis' => $data_reklas['kode_jenis'], 'id_kib' => $data_reklas['id_kib'],));
			$kode_barang_asal = $this->global_model->row_get_where('tbl_kode_barang', array('id_kode_barang' => $data_reklas['id_kode_barang'],));
			$kode_barang_tujuan = $this->global_model->row_get_where('tbl_kode_barang', array('id_kode_barang' => $data_reklas['id_kode_barang_tujuan'],));
			$data['data_reklas'] = $data_reklas;
			$data['kode_jenis_asal'] = $kode_jenis_asal;
			$data['kode_barang_asal'] = $kode_barang_asal;
			$data['kib_asal'] = $kib_asal;

			$data['kode_objek'] = set_value('kode_objek', $kode_barang_tujuan['kode_objek']);
			$data['kode_rincian_objek'] = set_value('kode_rincian_objek', $kode_barang_tujuan['kode_rincian_objek']);
			$data['kode_sub_rincian_objek'] = set_value('kode_sub_rincian_objek', $kode_barang_tujuan['kode_sub_rincian_objek']);
			$data['kode_sub_sub_rincian_objek'] = set_value('kode_sub_sub_rincian_objek', $kode_barang_tujuan['kode_sub_sub_rincian_objek']);
			$data['kode_barang'] = set_value('kode_barang', $kode_barang_tujuan['kode_barang']);
			$data['nama_barang'] = set_value('nama_barang', $kode_barang_tujuan['nama_barang_simbada'] ? $kode_barang_tujuan['nama_barang_simbada'] : $kode_barang_tujuan['nama_barang']);
			
			$data['sumber_dana'] = set_value('id_sumber_dana', $kib_asal['id_sumber_dana']);
			$data['rekening'] = set_value('id_rekening', $kib_asal['id_rekening']);
			
			$data['tanggal_transaksi'] = set_value('tanggal_transaksi',  date('d-m-Y', strtotime($data_reklas['tanggal'])));
			if($data_reklas['kode_jenis'] != '06'){
			  $data['nilai_kontrak'] = set_value('nilai_kontrak', $kib_asal['harga']);
			}else{
			  $data['nilai_kontrak'] = set_value('nilai_kontrak', $kib_asal['nilai_kontrak']);
			}
		}
		//END REKLAS CONDITION

		//sumber dana
		$data['lis_sumber_dana'] = $this->global_model->get_sumber_dana();
		//kode rekening
		$data['lis_rekening'] = $this->global_model->get_rekening($data['sumber_dana']);

		//pemilik
		$data['pemilik'] = $this->global_model->get_pemilik();
		$obj_kode_jenis = explode('.', $this->kode_jenis);
		// objek
		$data['objek'] = $this->global_model->get_objek_atb($obj_kode_jenis[1]);

		$kode = $this->global_library->explode_kode_barang($data['kode_barang']);

		// rincian_objek
		$data['rincian_objek'] = $this->global_model->get_rincian_objek_atb($kode['kode_jenis'], $kode['kode_objek']);
		// sub_rincian_objek
		$data['sub_rincian_objek'] = $this->global_model->get_sub_rincian_objek_atb($kode['kode_jenis'], $kode['kode_objek'], $kode['kode_rincian_objek']);
		// sub_sub_rincian_objek 
		//06 may 2019
		$data['sub_sub_rincian_objek'] = $this->global_model->get_sub_sub_rincian_objek($kode['kode_jenis'], $kode['kode_objek'], $kode['kode_rincian_objek'], $kode['kode_sub_rincian_objek']);

		$data['master_hak_tanah'] = $this->global_model->get_master_hak_tanah();
		$data['master_penggunaan'] = $this->global_model->get_master_penggunaan();
		$data['master_asal_usul'] = $this->global_model->get_master_asal_usul();
		$data['master_kondisi_bangunan'] = $this->global_model->get_master_kondisi_bangunan();
		$data['master_satuan'] = $this->global_model->get_master_satuan();

		$data["content"] = $this->load->view('tbl_kib_atb_form', $data, TRUE);
		$data['breadcrumb'] = array(
			array('label' => 'Data KIB', 'url' => '#', 'icon' => 'fa fa-pie-chart', 'li_class' => ''),
			array('label' => 'KIB ATB', 'url' => '#', 'icon' => '', 'li_class' => ''),
			array('label' => 'Buat', 'url' => '#', 'icon' => '', 'li_class' => 'active'),
		);
		$this->load->view('template', $data);
	}

	public function create_action()
	{
		$this->_rules();

		if ($this->form_validation->run() == FALSE) {
			$this->create();
		} else {
			if($this->input->post('umur_ekonomis', TRUE) == '0' || $this->input->post('umur_ekonomis', TRUE) == ''){
				$umur_ekonomis =  $this->global_model->get_kode_barang($this->input->post('kode_barang', TRUE))->umur_ekonomis == NULL ? '0' : $this->global_model->get_kode_barang($this->input->post('kode_barang', TRUE))->umur_ekonomis;
					}else{
				$umur_ekonomis = $this->input->post('umur_ekonomis', TRUE);
			  }
			  $harga = str_replace('.', '', $this->input->post('harga', TRUE));
			  $harga = str_replace('Rp', '', $harga);
			  $harga = str_replace(',', '.', $harga);
			$data = array(
				'id_pemilik' => $this->input->post('status_pemilik', TRUE),
				'id_kode_barang' => $this->global_model->get_kode_barang($this->input->post('kode_barang', TRUE))->id_kode_barang, // $this->input->post('id_kode_barang', TRUE),
				'id_kode_lokasi' => $this->global_model->get_kode_lokasi($this->input->post('kode_lokasi', TRUE))->id_kode_lokasi, // $this->input->post('id_kode_lokasi', TRUE),
				'kode_barang' => $this->input->post('kode_barang', TRUE),
				'nama_barang' => $this->input->post('nama_barang', TRUE),
				// 'nomor_register' => $this->input->post('nomor_register', TRUE),
				'judul_kajian_nama_software' => $this->input->post('judul_kajian_nama_software', TRUE),
				'tanggal_perolehan' => date('Y-m-d', tgl_inter($this->input->post('tanggal_perolehan', TRUE))), // $this->input->post('tanggal_perolehan', TRUE),
				'asal_usul' => $this->input->post('asal_usul', TRUE),
				'harga' => $harga,
				'keterangan' => $this->input->post('keterangan', TRUE),
				'deskripsi' => $this->input->post('deskripsi', TRUE),
				'kode_lokasi' => str_replace(".*", "", $this->global_model->get_kode_lokasi($this->input->post('kode_lokasi', TRUE))->kode_lokasi),
				// 'validasi' => $this->input->post('validasi', TRUE),

				'tanggal_transaksi' => date('Y-m-d', tgl_inter($this->input->post('tanggal_transaksi', TRUE))),
				'nomor_transaksi' => $this->input->post('nomor_transaksi', TRUE),
				'id_sumber_dana' => set_null($this->input->post('sumber_dana', TRUE)),
				'id_rekening' => set_null($this->input->post('rekening', TRUE)),
				'satuan' => $this->input->post('satuan', TRUE),
				'umur_ekonomis' => $umur_ekonomis,

				'created_at' => date('Y-m-d H:i:s'),
				'updated_at' => date('Y-m-d H:i:s'),
			);
			// die(json_encode($data));
			// $this->Tbl_kib_atb_model->insert($data);

			$jumlah_barang = str_replace('.', '', $this->input->post('jumlah_barang', TRUE));

			//REKLAS CONDITION
			$data_reklas = $this->session->userdata('data_reklas');

			if ($jumlah_barang > 1) {
				for ($jumlah_barang; $jumlah_barang > 0; $jumlah_barang--) {
					$this->Tbl_kib_atb_model->insert($data); //memang di awal , supaya no register pas
					$this->global_model->_logs($menu = 'data_kib', $sub_menu = 'kib_atb', $tabel_name = 'tbl_kib_atb', $action_id = null, $action = 'insert', $data = $data);
					$data['created_at'] = date('Y-m-d H:i:s');
					$data['updated_at'] = date('Y-m-d H:i:s');
				}
			} else if (!empty($data_reklas)) {
				//REKLAS CONDITION
				$data['status_barang'] = 'reklas_kode';
				$id = $this->Kib_atb_model->insert($data);
				$this->global_model->update_reklas($data_reklas['id_reklas_kode'],array('id_kib_tujuan' => $id));

				$this->global_model->_logs($menu = 'data_kib', $sub_menu = 'kib_atb', $tabel_name = 'tbl_kib_atb', $action_id = $id, $action = 'insert_reklas', $data = $data);

				//HAPUS SESSION REKLAS
				$this->session->unset_userdata('data_reklas');
				redirect(base_url('reklas/reklas_kode'));
				//END REKLAS CONDITION				
			} else {
				$this->Tbl_kib_atb_model->insert($data);
				$this->global_model->_logs($menu = 'data_kib', $sub_menu = 'kib_atb', $tabel_name = 'tbl_kib_atb', $action_id = null, $action = 'insert', $data = $data);
			}


			$this->session->set_flashdata('message', 'Data Baru Berhasil Dibuat');
			redirect(base_url('kib_atb'));
		}
	}

	public function update($id)
	{
		$id = decrypt_url($id);
		$row = $this->Tbl_kib_atb_model->get_by_id($id);
		// if ($row->validasi == '2' and $this->session->userdata('session')->id_role != '1')
		// 	redirect('dashboard');
		if ($row) {
			$kode_barang = $this->global_library->get_kode_barang($row->kode_barang);
			// $kode_lokasi=$this->global_library->get_kode_lokasi($row->kode_lokasi);
			$kode_lokasi = $this->global_model->get_kode_lokasi_by_id($row->id_kode_lokasi);

			$data = array(
				'button' => 'Perbarui',
				'menu' => $this->global_model->kode_jenis[$this->kode_jenis]['nama'],
				'action' => base_url('kib_atb/update_action'),

				'id_kib_atb' => set_value('id_kib_atb', $row->id_kib_atb),
				'sumber_dana' => set_value('sumber_dana', $row->id_sumber_dana),
				'rekening' => set_value('rekening',  $row->id_rekening),
				// 'id_pemilik' => set_value('id_pemilik', $this->default_pemilik),
				// 'id_kode_barang' => set_value('id_kode_barang'),
				// 'id_kode_lokasi' => set_value('id_kode_lokasi'),
				'kode_barang' => set_value('kode_barang', $row->kode_barang),
				'nama_barang' => set_value('nama_barang', $row->nama_barang),

				'kode_lokasi' => set_value('kode_lokasi', $row->kode_lokasi),
				'kode_objek' => set_value('kode_objek', $kode_barang['kode_objek']),
				'kode_rincian_objek' => set_value('kode_rincian_objek', $kode_barang['kode_rincian_objek']),
				'kode_sub_rincian_objek' => set_value('kode_sub_rincian_objek', $kode_barang['kode_sub_rincian_objek']),
				'kode_sub_sub_rincian_objek' => set_value('kode_sub_sub_rincian_objek', $kode_barang['kode_sub_sub_rincian_objek']),
				'status_pemilik' => set_value('status_pemilik', $row->id_pemilik),
				'nama_lokasi' => set_value('nama_lokasi', $kode_lokasi->instansi),


				// 'nomor_register' => set_value('nomor_register'),
				'judul_kajian_nama_software' => set_value('judul_kajian_nama_software', $row->judul_kajian_nama_software),
				'tanggal_perolehan' => set_value('tanggal_perolehan', tgl_indo($row->tanggal_perolehan)),
				'asal_usul' => set_value('asal_usul', $row->asal_usul),
				'harga' => set_value('harga', $row->harga),
				'keterangan' => set_value('keterangan', $row->keterangan),
				'deskripsi' => set_value('deskripsi', $row->deskripsi),
				// 'kode_lokasi' => set_value('kode_lokasi', $kode_lokasi),
				'satuan' => set_value('satuan', $row->satuan),
				'umur_ekonomis' => set_value('satuan', $row->umur_ekonomis),

				'tanggal_transaksi' => set_value('tanggal_transaksi', tgl_indo($row->tanggal_transaksi)),
				'nomor_transaksi' => set_value('nomor_transaksi', $row->nomor_transaksi),
				'jumlah_barang' => set_value('jumlah_barang', 1),
				'validasi' => set_value('validasi', $row->validasi),
			);
			// die(json_encode($data));
			$data['css'] = array(
				"assets/bootstrap/bootstrap-datepicker-master/dist/css/bootstrap-datepicker.min.css",
				"assets/jquery/select2-4.0.6-rc.1/dist/css/select2.min.css",
				"assets/jquery/select2-bootstrap-theme-master/dist/select2-bootstrap.min.css",
				'assets/adminlte/plugins/iCheck/all.css',
				'assets/sweetalert/sweetalert.css',
				"assets/css/kib.css",
			);
			$data['js'] = array(
				"assets/bootstrap/bootstrap-datepicker-master/dist/js/bootstrap-datepicker.min.js",
				"assets/bootstrap/bootstrap-datepicker-master/dist/locales/bootstrap-datepicker.id.min.js",
				"assets/jquery/jQuery-Mask-Plugin-master/dist/jquery.mask.min.js",
				"assets/jquery/select2-4.0.6-rc.1/dist/js/select2.min.js",
				'assets/adminlte/plugins/iCheck/icheck.min.js',
				'assets/sweetalert/sweetalert.min.js',
				"assets/js/kib.js",
			);


			//REKLAS CONDITION
			$data['data_reklas'] = null;
			$data_reklas = $this->session->userdata('data_reklas');
			if (!empty($data_reklas)) {
				$kode_jenis_asal = $this->global_model->kode_jenis[$data_reklas['kode_jenis']];
				$kib_asal = $this->global_model->row_get_where('view_kib', array('kode_jenis' => $data_reklas['kode_jenis'], 'id_kib' => $data_reklas['id_kib'],));
				$kode_barang_asal = $this->global_model->row_get_where('tbl_kode_barang', array('id_kode_barang' => $data_reklas['id_kode_barang'],));
				$kode_barang_tujuan = $this->global_model->row_get_where('tbl_kode_barang', array('id_kode_barang' => $data_reklas['id_kode_barang_tujuan'],));
				$data['data_reklas'] = $data_reklas;
				$data['kode_jenis_asal'] = $kode_jenis_asal;
				$data['kode_barang_asal'] = $kode_barang_asal;
				$data['kib_asal'] = $kib_asal;

				$data['kode_objek'] = set_value('kode_objek', $kode_barang_tujuan['kode_objek']);
				$data['kode_rincian_objek'] = set_value('kode_rincian_objek', $kode_barang_tujuan['kode_rincian_objek']);
				$data['kode_sub_rincian_objek'] = set_value('kode_sub_rincian_objek', $kode_barang_tujuan['kode_sub_rincian_objek']);
				$data['kode_sub_sub_rincian_objek'] = set_value('kode_sub_sub_rincian_objek', $kode_barang_tujuan['kode_sub_sub_rincian_objek']);
				$data['kode_barang'] = set_value('kode_barang', $kode_barang_tujuan['kode_barang']);
				$data['nama_barang'] = set_value('nama_barang', $kode_barang_tujuan['nama_barang_simbada'] ? $kode_barang_tujuan['nama_barang_simbada'] : $kode_barang_tujuan['nama_barang']);
			
				$data['sumber_dana'] = set_value('id_sumber_dana', $kib_asal['id_sumber_dana']);
				$data['rekening'] = set_value('id_rekening', $kib_asal['id_rekening']);
			}
			//END REKLAS CONDITION





			//sumber dana
			$data['lis_sumber_dana'] = $this->global_model->get_sumber_dana();
			//kode rekening
			$data['lis_rekening'] = $this->global_model->get_rekening($data['sumber_dana']);

			//pemilik
			$data['pemilik'] = $this->global_model->get_pemilik();

			$obj_kode_jenis = explode('.', $this->kode_jenis);
			// objek
			$data['objek'] = $this->global_model->get_objek_atb($obj_kode_jenis[1]);

			$kode = $this->global_library->explode_kode_barang($data['kode_barang']);

			// rincian_objek
			$data['rincian_objek'] = $this->global_model->get_rincian_objek_atb($kode['kode_jenis'], $kode['kode_objek']);
			// sub_rincian_objek
			$data['sub_rincian_objek'] = $this->global_model->get_sub_rincian_objek_atb($kode['kode_jenis'], $kode['kode_objek'], $kode['kode_rincian_objek']);
			// sub_sub_rincian_objek
			$data['sub_sub_rincian_objek'] = $this->global_model->get_sub_sub_rincian_objek_atb($kode['kode_jenis'], $kode['kode_objek'], $kode['kode_rincian_objek'], $kode['kode_sub_rincian_objek']);
			// die(json_encode($data['sub_sub_rincian_objek']));
			// $data['master_hak_tanah'] = $this->global_model->get_master_hak_tanah();
			// $data['master_penggunaan'] = $this->global_model->get_master_penggunaan();
			$data['master_asal_usul'] = $this->global_model->get_master_asal_usul();
			// $data['master_kondisi_bangunan'] = $this->global_model->get_master_kondisi_bangunan();
			$data['master_satuan'] = $this->global_model->get_master_satuan();

			$data['content'] = $this->load->view('kib_atb/tbl_kib_atb_form', $data, TRUE);
			$data['breadcrumb'] = array(
				array('label' => 'Data KIB', 'url' => '#', 'icon' => 'fa fa-pie-chart', 'li_class' => ''),
				array('label' => 'KIB ATB', 'url' => '#', 'icon' => '', 'li_class' => ''),
				array('label' => 'Edit', 'url' => '#', 'icon' => '', 'li_class' => 'active'),
			);
			$this->load->view('template', $data);
		} else {
			$this->session->set_flashdata('message', 'Data Tidak Ditemukan');
			redirect(base_url('kib_atb'));
		}
	}

	public function update_action()
	{
		$this->_rules_update();

		if ($this->form_validation->run() == FALSE) {
			$this->update($this->input->post('id_kib_atb', TRUE));
		} else {
			$data = array(
				'id_pemilik' => $this->input->post('status_pemilik', TRUE),



				// 'nomor_register' => $this->input->post('nomor_register', TRUE),
				'judul_kajian_nama_software' => $this->input->post('judul_kajian_nama_software', TRUE),

				'asal_usul' => $this->input->post('asal_usul', TRUE),

				'keterangan' => $this->input->post('keterangan', TRUE),
				'deskripsi' => $this->input->post('deskripsi', TRUE),
				'kode_lokasi' => $this->input->post('kode_lokasi', TRUE),
				// 'validasi' => $this->input->post('validasi', TRUE),

				'tanggal_transaksi' => $this->input->post('tanggal_transaksi', TRUE) != '-' ? date('Y-m-d', tgl_inter($this->input->post('tanggal_transaksi', TRUE))) : '0000-00-00',
        		'nomor_transaksi' => $this->input->post('nomor_transaksi', TRUE),

				'id_sumber_dana' => set_null($this->input->post('sumber_dana', TRUE)),
				'id_rekening' => set_null($this->input->post('rekening', TRUE)),
				'satuan' => set_null($this->input->post('satuan', TRUE)),
				'validasi' => '1',

				// 'created_at' => date('Y-m-d H:i:s'),
				'updated_at' => date('Y-m-d H:i:s'),
			);

			if ($this->input->post('validasi', TRUE) != '2') {
				
				$harga = str_replace('.', '', $this->input->post('harga', TRUE));
				$harga = str_replace('Rp', '', $harga);
				$harga = str_replace(',', '.', $harga);
				
				// $data['id_kode_lokasi'] = $this->global_model->get_kode_lokasi($this->input->post('kode_lokasi', TRUE))->id_kode_lokasi;
				$data['id_kode_barang'] = $this->global_model->get_kode_barang($this->input->post('kode_barang', TRUE))->id_kode_barang;
				$data['kode_barang'] = $this->input->post('kode_barang', TRUE);
				$data['nama_barang'] = $this->input->post('nama_barang', TRUE);
				$data['harga'] = $harga;
				$data['tanggal_perolehan'] = $this->input->post('tanggal_perolehan', TRUE) != '-' ?  date('Y-m-d', tgl_inter($this->input->post('tanggal_perolehan', TRUE))) : '0000-00-00';
			}else{
				$data['validasi'] = '2';
			  }

			$row = $this->Tbl_kib_atb_model->get_by_id($this->input->post('id_kib_atb', TRUE));

			if ($row->validasi == 2 && $this->session->userdata('session')->id_role == '1') { // kib yg sudah tervalidasi
			  $this->global_model->insert_histori_kib('5.03', $this->input->post('id_kib_atb', TRUE), 'update_kib'); // 1 = KIB A
			  $data['validasi'] = '2';
			  $data['tanggal_perolehan'] = $this->input->post('tanggal_perolehan', TRUE) != '-' ?  date('Y-m-d', tgl_inter($this->input->post('tanggal_perolehan', TRUE))) : '0000-00-00';
     
			  $data_histori = array(
				'tanggal_histori' => date('Y-m-d', tgl_inter($this->input->post('tanggal_transaksi', TRUE))),
				'tanggal_pembelian' => $data['tanggal_perolehan'],
				'id_sumber_dana'  => $data['id_sumber_dana'],
				'id_rekening'  => $data['id_rekening'],
			  );
	  
			  $this->global_model->update_histori_barang_atb('5.03', $this->input->post('id_kib_atb', TRUE), $row->tanggal_transaksi, 'entri', $data_histori);
         
			}
			

			//REKLAS CONDITION
			$data_reklas = $this->session->userdata('data_reklas');
			if (!empty($data_reklas)) {
				//REKLAS CONDITION
				$data['status_barang'] = 'reklas_kode';
				$this->Tbl_kib_atb_model->update($this->input->post('id_kib_atb', TRUE), $data);
				// $this->global_model->global_update(
				// 	'tbl_reklas_kode',
				// 	array('id_reklas_kode' => $data_reklas['id_reklas_kode']),
				// 	array('id_kib_tujuan' => $this->input->post('id_kib_atb', TRUE),)
				// );
				$this->global_model->_logs($menu = 'data_kib', $sub_menu = 'kib_atb', $tabel_name = 'tbl_kib_atb', $action_id = $this->input->post('id_kib_atb', TRUE), $action = 'update_reklas', $data = $data);

				//HAPUS SESSION REKLAS
				$this->session->unset_userdata('data_reklas');
				redirect(base_url('reklas/reklas_kode'));
				//END REKLAS CONDITION
			} else {
				$this->Tbl_kib_atb_model->update($this->input->post('id_kib_atb', TRUE), $data);
				$this->session->set_flashdata('message', 'Data Berhasil Diubah');
				redirect(base_url('kib_atb'));
			}
		}
	}

	public function delete($id)
	{
		$id = decrypt_url($id);
		/*$row = $this->Tbl_kib_atb_model->get_by_id($id);

		if ($row) {
			$this->Tbl_kib_atb_model->delete($id);
			$this->session->set_flashdata('message', 'Data Berhasil Dihapus');
			redirect(base_url('tbl_kib_atb'));
		} else {
			$this->session->set_flashdata('message', 'Data Tidak Ditemukan');
			redirect(base_url('tbl_kib_atb'));
		}*/
		$row = $this->Tbl_kib_atb_model->get_by_id($id);
		$bulan = date('n');
		$tahun = date('Y');
		$id_kode_lokasi = $row->id_kode_lokasi;
		$locked = $this->global_model->get_locked($bulan,$tahun,$id_kode_lokasi);
	
		if($locked > 0){
		$this->session->set_flashdata('message', 'Stock Opname Bulan '.bulan_indo($bulan).' Telah Dikunci');
		redirect(base_url('kib_atb'));
		}
		// bila sudah di validasi dan bukan super admin
		if ($row->validasi == '2' and $this->session->userdata('session')->id_role != '1'){
		// if ($row->validasi == '2')
		  redirect('dashboard');
		}
		  
		if ($row) {
		  // die;
		  $this->db->trans_start();
				$this->global_model->insert_histori_kib('5.03', $id, 'delete_kib'); // 1 = KIB A

			$this->Tbl_kib_atb_model->delete($id);
			$this->Tbl_kib_atb_model->delete_histori_barang($id);
			$this->global_model->_logs($menu = 'data_kib', $sub_menu = 'kib_atb', $tabel_name = 'tbl_kib_atb', $action_id = $id, $action = 'delete', $data = $row);
			$this->session->set_flashdata('message', 'Data Berhasil Dihapus');
			$this->db->trans_complete(); # Completing transaction
	  
			if ($this->db->trans_status() === FALSE) {
				# Something went wrong.
				$this->db->trans_rollback();
				$this->session->set_flashdata('message', 'Data Tidak Ditemukan');
				redirect(base_url('kib_atb'));
			} 
			else {
				# Committing data to the database.
				$this->db->trans_commit();
				redirect(base_url('kib_atb'));
			}
		} else {
			$this->session->set_flashdata('message', 'Data Tidak Ditemukan');
			redirect(base_url('kib_atb'));
		}
	}

	public function delete_all()
	{
	  // print_r($_POST); die;
	  if(!empty($_POST[ 'list_id'])) {

		$this->db->trans_start();

		foreach ($_POST['list_id'] as $key => $value) {
  
		  $id = $value;
		  $row = $this->Tbl_kib_atb_model->get_by_id($id);
		  $bulan = date('n');
		  $tahun = date('Y');
		  $id_kode_lokasi = $row->id_kode_lokasi;
		  $locked = $this->global_model->get_locked($bulan, $tahun, $id_kode_lokasi);
  
		  if ($locked > 0) {
			echo json_encode(array('status' => FALSE, 'message' => 'Stock Opname Bulan ' . bulan_indo($bulan) . ' Tahun ' . $tahun . ' Telah Dikunci'));
			exit;
			break;
		  }
  
		  // bila sudah di validasi dan bukan super admin
		  if ($row->validasi == '2' and $this->session->userdata('session')->id_role != '1') {
			// if ($row->validasi == '2')
			echo json_encode(array('status' => FALSE, 'message' => 'Stock Opname Bulan ' . bulan_indo($bulan) . ' Tahun ' . $tahun . ' Telah Dikunci'));
			exit;
			break;
		  }
  
		  if ($row) {
			// die;
		   
			if ($row->validasi == '2'){
			$this->global_model->insert_histori_kib('5.03', $id, 'delete_kib'); // 1 = KIB A$this->Tbl_kib_atb_model->delete_histori_barang($id);
			$this->global_model->_logs($menu = 'data_kib', $sub_menu = 'kib_atb', $tabel_name = 'tbl_kib_atb', $action_id = $id, $action = 'delete', $data = $row);
			}

			$this->Tbl_kib_atb_model->delete($id);
			// $this->session->set_flashdata('message', 'Data Berhasil Dihapus');
  
		  } else {
			echo json_encode(array('status' => TRUE, 'message' => 'Data Tidak Ditemukan'));
		  }
		}
  
		$this->db->trans_complete(); # Completing transaction
  
		if ($this->db->trans_status() === FALSE) {
		  # Something went wrong.
		  $this->db->trans_rollback();
		  echo json_encode(array('status' => FALSE, 'message' => 'Gagal Dihapus'));
		  exit;
		} else {
		  # Committing data to the database.
		  $this->db->trans_commit();
		  echo json_encode(array('status' => TRUE, 'message' => 'Berhasil Dihapus'));
		  // exit;
		}
	  }
	}

	public function _rules()
	{
		// $this->form_validation->set_rules('id_pemilik', 'id pemilik', 'trim|required');
		// $this->form_validation->set_rules('id_kode_barang', 'id kode barang', 'trim|required');
		// $this->form_validation->set_rules('id_kode_lokasi', 'id kode lokasi', 'trim|required');
		$this->form_validation->set_rules('nama_barang', 'nama barang', 'trim|required');
		// $this->form_validation->set_rules('nomor_register', 'nomor register', 'trim|required');
		$this->form_validation->set_rules('judul_kajian_nama_software', 'judul kajian nama software', 'trim|required');
		$this->form_validation->set_rules('tanggal_perolehan', 'tanggal perolehan', 'trim|required');
		$this->form_validation->set_rules('asal_usul', 'asal usul', 'trim|required');
		$this->form_validation->set_rules('keterangan', 'keterangan', 'trim|required');
		// $this->form_validation->set_rules('deskripsi', 'deskripsi', 'trim|required');
		$this->form_validation->set_rules('kode_lokasi', 'kode lokasi', 'trim|required');
		// $this->form_validation->set_rules('validasi', 'validasi', 'trim|required');
		// $this->form_validation->set_rules('created_at', 'created at', 'trim|required');
		// $this->form_validation->set_rules('updated_at', 'updated at', 'trim|required');

		$this->form_validation->set_rules('id_kib_atb', 'id_kib_atb', 'trim');
		$this->form_validation->set_error_delimiters('<span class="text-danger">', '</span>');
	}

	public function _rules_update()
	{
		// $this->form_validation->set_rules('id_pemilik', 'id pemilik', 'trim|required');
		// $this->form_validation->set_rules('id_kode_barang', 'id kode barang', 'trim|required');
		// $this->form_validation->set_rules('id_kode_lokasi', 'id kode lokasi', 'trim|required');
		// $this->form_validation->set_rules('nama_barang', 'nama barang', 'trim|required');
		// $this->form_validation->set_rules('nomor_register', 'nomor register', 'trim|required');
		$this->form_validation->set_rules('judul_kajian_nama_software', 'judul kajian nama software', 'trim|required');
		// $this->form_validation->set_rules('tanggal_perolehan', 'tanggal perolehan', 'trim|required');
		// $this->form_validation->set_rules('asal_usul', 'asal usul', 'trim|required');
		$this->form_validation->set_rules('keterangan', 'keterangan', 'trim|required');
		// $this->form_validation->set_rules('deskripsi', 'deskripsi', 'trim|required');
		// $this->form_validation->set_rules('kode_lokasi', 'kode lokasi', 'trim|required');
		// $this->form_validation->set_rules('validasi', 'validasi', 'trim|required');
		// $this->form_validation->set_rules('created_at', 'created at', 'trim|required');
		// $this->form_validation->set_rules('updated_at', 'updated at', 'trim|required');

		$this->form_validation->set_rules('id_kib_atb', 'id_kib_atb', 'trim');
		$this->form_validation->set_error_delimiters('<span class="text-danger">', '</span>');
	}
}

/* End of file Tbl_kib_atb.php */
/* Location: ./application/controllers/Tbl_kib_atb.php */
/* Please DO NOT modify this information : */
/* Generated by Harviacode Codeigniter CRUD Generator 2020-08-02 19:12:11 */
/* http://harviacode.com */