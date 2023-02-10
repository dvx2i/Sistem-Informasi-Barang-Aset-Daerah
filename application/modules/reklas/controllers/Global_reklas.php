<?php

if (!defined('BASEPATH')) exit('No direct script access allowed');

class Global_reklas extends MX_Controller
{
    function __construct()
    {
        parent::__construct();
        // $this->load->model('pengajuan_model');
        $this->load->library('form_validation');
        $this->load->library('datatables');
        $this->load->helper('my_global');
        $this->load->library('Global_library');
        $this->load->model('User_model');
        $this->load->model('Global_reklas_model', 'reklas_model');
    }

    public function json_kib_a($pihak = null)
    {
        header('Content-Type: application/json');
        $list_id_kib_a = $this->input->post('list_id_kib_a');
        echo $this->reklas_model->json_kib_a($pihak, $list_id_kib_a);
    }

    public function json_kib_b($pihak = null)
    {
        header('Content-Type: application/json');
        $list_id_kib_b = $this->input->post('list_id_kib_b');
        // die(json_encode($list_id_kib_b));
        echo $this->reklas_model->json_kib_b($pihak, $list_id_kib_b);
    }

    public function json_kib_c($pihak = null)
    {
        header('Content-Type: application/json');
        $list_id_kib_c = $this->input->post('list_id_kib_c');
        echo $this->reklas_model->json_kib_c($pihak, $list_id_kib_c);
    }

    public function json_kib_d($pihak = null)
    {
        header('Content-Type: application/json');
        $list_id_kib_d = $this->input->post('list_id_kib_d');
        echo $this->reklas_model->json_kib_d($pihak, $list_id_kib_d);
    }

    public function json_kib_e($pihak = null)
    {
        header('Content-Type: application/json');
        $list_id_kib_e = $this->input->post('list_id_kib_e');
        echo $this->reklas_model->json_kib_e($pihak, $list_id_kib_e);
    }

    public function json_kib_f($pihak = null)
    {
        header('Content-Type: application/json');
        $list_id_kib_f = $this->input->post('list_id_kib_f');
        echo $this->reklas_model->json_kib_f($pihak, $list_id_kib_f);
    }

    public function json_kib_atb($pihak = null)
    {
        header('Content-Type: application/json');
        $list_id_kib_atb = $this->input->post('list_id_kib_atb');
        echo $this->reklas_model->json_kib_atb($pihak, $list_id_kib_atb);
    }

    public function json_kib_lainnya($pihak = null)
    {
        header('Content-Type: application/json');
        $list_id_kib = $this->input->post('list_id_kib_lainnya');
        echo $this->reklas_model->json_kib_lainnya($pihak, $list_id_kib);
    }

    public function get_jenis_tujuan()
    {
        // $kode_kelompok = $this->input->post('kode_kelompok');
        // die(set_value('kode_kelompok'));
        $kode_1 = $this->global_library->explode_kode_barang(set_value('kode_barang'));
        // die(json_encode($kode_1));
        // $kode_jenis = $this->input->post('kode_jenis');
        // // $kode_2 = $this->global_library->explode_kode_barang(set_value('kode_jenis'));

        $kode_jenis = $this->reklas_model->get_jenis($kode_1['kode_kelompok']);
        // die(json_encode($kode_jenis));
        $data['option'] = '<option value="">Silahkan Pilih</option>';
        foreach ($kode_jenis as $key => $value) {
            $data['option'] .= '<option  value="' . $value->kode_barang . '">' . $value->kode_jenis . ' - ' . $value->nama_barang . '</option>';
        }
        echo json_encode($data);
    }


    public function get_objek()
    {
        // $kode_kelompok = $this->input->post('kode_kelompok');
        // die(set_value('kode_kelompok'));
        // $kode_1 = $this->global_library->explode_kode_barang(set_value('kode_kelompok'));
        // die(json_encode($kode_1));
        // $kode_jenis = $this->input->post('kode_jenis');
        // $kode_2 = $this->global_library->explode_kode_barang(set_value('kode_jenis'));
        $temp = $this->input->post('kode_barang');
        $kode = $kode = $this->global_library->explode_kode_barang(set_value('kode_barang'));
        $kode_objek = $this->reklas_model->get_objek($kode);

        $data['option'] = '<option value="">Silahkan Pilih</option>';
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
        $kode_rincian_objek = $this->reklas_model->get_rincian_objek($kode);

        $data['option'] = '<option value="">Silahkan Pilih</option>';
        foreach ($kode_rincian_objek as $key => $value) {
            $data['option'] .= '<option  value="' . $value->kode_barang . '">' . $value->kode_rincian_objek . ' - ' . $value->nama_barang . '</option>';
        }
        echo json_encode($data);
    }

    public function get_sub_rincian_objek()
    {
        $temp = $this->input->post('kode_barang');
        $kode = $kode = $this->global_library->explode_kode_barang(set_value('kode_barang'));
        $kode_sub_rincian_objek = $this->reklas_model->get_sub_rincian_objek($kode);

        $data['option'] = '<option value="">Silahkan Pilih</option>';
        foreach ($kode_sub_rincian_objek as $key => $value) {
            $data['option'] .= '<option  value="' . $value->kode_barang . '">' . $value->kode_sub_rincian_objek . ' - ' . $value->nama_barang . '</option>';
        }
        echo json_encode($data);
    }

    public function get_sub_sub_rincian_objek()
    {
        $temp = $this->input->post('kode_barang');
        $kode = $kode = $this->global_library->explode_kode_barang(set_value('kode_barang'));
        $kode_sub_sub_rincian_objek = $this->reklas_model->get_sub_sub_rincian_objek($kode);
        $data['option'] = '<option value="">Silahkan Pilih</option>';
        foreach ($kode_sub_sub_rincian_objek as $key => $value) {
            // $data['option'] .= '<option  value="' . $value->kode_barang . '">' . $value->kode_sub_sub_rincian_objek . ' - ' . $value->nama_barang . '</option>';
            $nama_barang = null;
            if ($value->kode_sub_sub_rincian_objek == '000')
                $nama_barang = $value->nama_barang;
            else
                $nama_barang = $value->nama_barang_simbada;
            $data['option'] .= '<option  value="' . $value->kode_barang . '">' . $value->kode_sub_sub_rincian_objek . ' - ' . $nama_barang . '</option>';
        }
        echo json_encode($data);
    }

    public function get_kode_kelompok_tujuan()
    {
        $status = $this->input->post('status');

        $list_kelompok = null;
        if ($status == 'checked') {
            $data_where = array('4', '5');

            $list_kelompok = $this->reklas_model->get_list_kelompok($data_where);
        } elseif ($status == 'no_checked') {
            $data_where = array('4');
            $list_kelompok = $this->reklas_model->get_list_kelompok($data_where);
        }

        $data['option'] = '<option value="">Silahkan Pilih</option>';
        foreach ($list_kelompok as $key => $value) {
            $data['option'] .= '<option  value="' . $value["kode_barang"] . '">' . $value["kode_barang"] . ' - ' . $value["nama_barang"] . '</option>';
        }
        echo json_encode($data);
    }

    public function cek_barang_tujuan()
    {
        $kode_jenis = explode(".", $this->input->post('kode_jenis'));
        $kode_barang = $this->input->post('kode_barang');

        $ada = $this->reklas_model->cek_barang_tujuan($kode_jenis,$kode_barang);
        if($ada > 0){
            $data = array('status' => true);
        }else{
            $data = array('status' => false);
        }
        echo json_encode($data);
    }













    /*
    function update_mutasi($id_mutasi, $data)
    {
        $this->db->where('id_mutasi', $id_mutasi);
        $this->db->update('tbl_mutasi', $data);
    }
    */
}

/* End of file Usulan.php */
/* Location: ./application/controllers/Usulan.php */
/* Please DO NOT modify this information : */
/* Generated by Harviacode Codeigniter CRUD Generator 2018-08-24 05:56:49 */
/* http://harviacode.com */
