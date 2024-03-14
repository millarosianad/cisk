<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');
class Rpd extends MY_Controller
{
    function Rpd()
    {
        $logged_in = $this->session->userdata('logged_in');
        if (!isset($logged_in) || $logged_in != TRUE) {
            redirect('login/', 'refresh');
        }
        set_time_limit(0);
        $this->load->library(array('table', 'template', 'Excel_generator', 'form_validation', 'email'));
        $this->load->helper('url');
        $this->load->helper('csv');
        $this->load->model('M_menu');
        $this->load->model(array('M_menu', 'model_rpd', 'model_outlet_transaksi', 'model_asset', 'model_biaya'));
        $this->load->database();
    }

    function index()
    {
        $this->history();
    }

    public function get_data()
    {
        $hak_akses = $this->model_biaya->hak_akses($this->session->userdata('id'));
        $id = $_POST['id'];
        $data['get_history'] = $this->model_rpd->get_history($id, $hak_akses)->row();
        $data['get_master_karyawan'] = $this->model_rpd->getMaster_karyawan($id)->row();
        $data['get_aktivitas'] = $this->model_rpd->get_aktivitas('',$id)->row();
        $data['get_master_biaya'] = $this->model_rpd->getMaster_biaya($id)->row();
        $data['get_realisasi'] = $this->model_rpd->get_realisasi('',$id)->row();
        echo json_encode($data);
    }

    public function get_pelaksana()
    {
        $data['get_pelaksana'] = $this->model_rpd->get_pelaksana($this->session->userdata('id'))->row();
        echo json_encode($data);
    }

    public function history()
    {
        $hak_akses = $this->model_rpd->hak_akses($this->session->userdata('id'));

        $data = [
            'id' => $this->session->userdata('id'),
            'title' => 'Rencana Perjalanan Dinas (RPD)',
            'get_label' => $this->M_menu->get_label(),
            'get_history' => $this->model_rpd->get_history('', $hak_akses)->result(),
            'list_user' => $this->model_asset->getUser()->result(),
        ];
        $this->load->view('template_claim/top_header');
        $this->load->view('template_claim/header');
        $this->load->view('template_claim/sidebar', $data);
        $this->load->view('template_claim/top_content', $data);
        $this->load->view('rpd/history', $data);
        $this->load->view('template_claim/bottom_content');
        $this->load->view('template_claim/footer');
    }

    public function proses_rpd()
    {
        $created_at = $this->model_outlet_transaksi->timezone();
        $id = $this->input->post('id');
        $userid = $this->session->userdata('id');
        $tanggal_berangkat = $this->input->post('tanggal_berangkat');

        $signature = md5(str_replace('-', '', $created_at)) . "-" . md5($userid . $tanggal_berangkat);
        $kode = $this->model_rpd->generate($userid, $tanggal_berangkat);
        
        $data = [
            "kode"                  => null,
            "kode_reserved"         => $kode,
            "tanggal_berangkat"     => $this->input->post('tanggal_berangkat'),
            "tanggal_tiba"          => $this->input->post('tanggal_tiba'),
            "tempat_berangkat"      => $this->input->post('tempat_berangkat'),
            "tempat_tujuan"         => $this->input->post('tempat_tujuan'),
            "maksud_perjalanan_dinas" => $this->input->post('maksud_perjalanan_dinas'),
            "pelaksana"             => $this->input->post('pelaksana'),
            "keterangan"            => $this->input->post('keterangan'),
            "signature"             => $signature,
            "created_at"            => $this->model_outlet_transaksi->timezone(),
            "created_by"            => $userid
        ];

        if ($id == '') {
            $id_rpd = $this->model_rpd->insert('site.t_rpd', $data);
            // echo "id_rpd : ".$id_rpd;
            
            // $get_signature = $this->db->get_where('site.t_rpd', array('id' => $id_rpd));
            // var_dump($get_signature->row()->signature);
            // $signature = 

        } else {
        }
        // die;
        redirect('rpd/aktivitas/'.$signature);
    }

    public function delete_rpd()
    {
        $signature = $this->uri->segment('3');
        $get_id_rpd = $this->model_rpd->get_id_rpd($signature);

        $data = [
            'deleted' => '1',
            'updated_by' => $this->session->userdata('id'),
            'updated_at' => $this->model_outlet_transaksi->timezone(),
        ];

        $this->db->where('id', $get_id_rpd);
        $this->db->where('signature', $signature);
        $this->db->update('site.t_rpd', $data);
        redirect("rpd");
    }

    public function aktivitas($signature)
    {

        $get_id_rpd = $this->model_rpd->get_id_rpd($signature);
        // var_dump($get_id_rpd);die;
        $data = [
            'id' => $this->session->userdata('id'),
            'title' => 'RPD | Tambah Rencana Aktivitas ',
            'get_label' => $this->M_menu->get_label(),
            'get_history' => $this->model_rpd->get_history($get_id_rpd,'1')->row(),
            'get_aktivitas' => $this->model_rpd->get_aktivitas($get_id_rpd,'')->result(),
        ];

        // var_dump($data['get_aktivitas']);die;
        $this->load->view('template_claim/top_header');
        $this->load->view('template_claim/header');
        $this->load->view('template_claim/sidebar', $data);
        $this->load->view('template_claim/top_content', $data);
        $this->load->view('rpd/aktivitas', $data);
        $this->load->view('template_claim/bottom_content');
        $this->load->view('template_claim/footer');
    }

    public function proses_aktivitas()
    {
        $rencana = $this->input->post('rencana');
        $id = $this->input->post('id');
        $userid = $this->session->userdata('id');
        $signature = $this->input->post('signature');

        $id_rpd = $this->input->post('id_rpd');
        $rencana = $this->input->post('rencana');
        $tanggal = $this->input->post('tanggal');
        $detail = $this->input->post('detail');
        $jenis_pengeluaran = $this->input->post('jenis_pengeluaran');
        $limit_budget = $this->input->post('limit_budget');
        $nominal_biaya = $this->input->post('nominal_biaya');
        $keterangan = $this->input->post('keterangan');

        $jml_data = count($rencana);
        for ($i=0; $i < $jml_data; $i++) {
            $data = [
                "id_rpd" => $id_rpd,
                "rencana" => $rencana[$i],
                "tanggal" => $tanggal[$i],
                "detail" => $detail[$i],
                "jenis_pengeluaran" => $jenis_pengeluaran[$i],
                "limit_budget" => $limit_budget[$i],
                "nominal_biaya" => $nominal_biaya[$i],
                "keterangan" => $keterangan[$i],
                "signature" => $signature
            ];

            if ($id == '') {
                $data["created_at"] = $this->model_outlet_transaksi->timezone();
                $data["created_by"] = $userid;
                $this->model_rpd->insert('site.t_rpd_aktivitas', $data);
            } else {
                $data["updated_at"] = $this->model_outlet_transaksi->timezone();
                $data["updated_by"] = $userid;
                $this->db->where('id', $id);
                $this->db->update('site.t_rpd_aktivitas', $data);

                $data2["status_approval"] = null;
                $data2["nama_status_approval"] = null;
                $data2["approved_at"] = null;
                $data2["approved_by"] = null;
                $data2["alasan_atasan"] = null;
                $this->db->where('signature', $signature);
                $this->db->update('site.t_rpd', $data2);
            }
        }

        $update_kode = "
            update site.t_rpd a 
            set a.kode = a.kode_reserved
            where a.signature = '$signature'";
        $proses_update = $this->db->query($update_kode);

        redirect("rpd/aktivitas/$signature");
    }

    public function proses_realisasi(){
        $id_realisasi = $this->input->post('id_realisasi');
        $signature =  $this->input->post('signature_realisasi');
        $rpd_id = $this->input->post('id_rpd_realisasi');
        $aktivitas_id = $this->input->post('id_aktivitas_realisasi');
        $tanggal = $this->input->post('tanggal_realisasi');
        $detail = $this->input->post('detail_realisasi');
        $jenis_pengeluaran = $this->input->post('jenis_pengeluaran_realisasi');
        $nominal_biaya = $this->input->post('nominal_biaya_realisasi');
        $keterangan = $this->input->post('keterangan_realisasi');
        $created_at = $this->model_outlet_transaksi->timezone();
        $userid = $this->session->userdata('id');
        // var_dump($id_realisasi);die;

        $jml_data = count($tanggal);
        for ($i=0; $i < $jml_data; $i++) {

            if(!empty($_FILES['foto_struk']['name'][$i])){

                $_FILES['file']['name'] = $_FILES['foto_struk']['name'][$i];
                $_FILES['file']['type'] = $_FILES['foto_struk']['type'][$i];
                $_FILES['file']['tmp_name'] = $_FILES['foto_struk']['tmp_name'][$i];
                $_FILES['file']['error'] = $_FILES['foto_struk']['error'][$i];
                $_FILES['file']['size'] = $_FILES['foto_struk']['size'][$i];

                $config['upload_path'] = './assets/file/rpd/';
                $config['allowed_types'] = 'jpg|jpeg|png|gif';
                $config['max_size'] = '5000';
                $config['overwrite'] = false;
                $config['file_name'] = $_FILES['foto_struk']['name'][$i];

                $this->load->library('upload',$config);

                if($this->upload->do_upload('file')){
                $uploadData = $this->upload->data();
                $filename_struk = $uploadData['file_name'];
                }
            }

            if ($id_realisasi == '') {

                $data = [
                    "rpd_id" => $rpd_id,
                    "aktivitas_id" => $aktivitas_id[$i],
                    "tanggal" => $tanggal[$i],
                    "detail" => $detail[$i],
                    "jenis_pengeluaran" => $jenis_pengeluaran[$i],
                    "nominal_biaya" => $nominal_biaya[$i],
                    "keterangan" => $keterangan[$i],
                    "file_struk" => $filename_struk,
                    "created_at" => $created_at,
                    "created_by" => $userid,
                    "signature" => $signature,
                ];

                $this->model_rpd->insert('site.t_rpd_realisasi', $data);
                $redirect ="rpd/aktivitas/$signature";
            } else {
                $this->db->select('file_struk');
                $this->db->where('id', $id_realisasi);
                $proses =  $this->db->get('site.t_rpd_realisasi')->row();


                $data = [
                    "tanggal" => $tanggal[$i],
                    "detail" => $detail[$i],
                    "jenis_pengeluaran" => $jenis_pengeluaran[$i],
                    "nominal_biaya" => $nominal_biaya[$i],
                    "keterangan" => $keterangan[$i],
                    "updated_at" => $created_at,
                    "updated_by" => $userid
                ];

                if ($filename_struk == '') {
                    $data['file_struk'] = $proses->file_struk;
                }else {
                    $data['file_struk'] = $filename_struk;
                }
                // var_dump($data);die;
                $this->db->where('id', $id_realisasi);
                $this->db->update('site.t_rpd_realisasi', $data);
                $redirect ="rpd/view_realisasi/$signature/$aktivitas_id";
            }
        }
        redirect("$redirect");
    }

    public function view_realisasi()
    {
        $get_id_aktivitas = $this->uri->segment('4');
        $signature = $this->uri->segment('3');
        // var_dump($get_id_rpd);die;
        $data = [
            'id' => $this->session->userdata('id'),
            'title' => 'RPD | View Realisasi ',
            'url' => "rpd/aktivitas/$signature",
            'get_label' => $this->M_menu->get_label(),
            'get_realisasi' => $this->model_rpd->get_realisasi($get_id_aktivitas,'')->result(),
        ];

        // var_dump($data['get_aktivitas']);die;
        $this->load->view('template_claim/top_header');
        $this->load->view('template_claim/header');
        $this->load->view('template_claim/sidebar', $data);
        $this->load->view('template_claim/top_content', $data);
        $this->load->view('rpd/realisasi', $data);
        $this->load->view('template_claim/bottom_content');
        $this->load->view('template_claim/footer');
    }

    public function kategori_biaya()
    {

        $curl = curl_init();
        $token = '11f3a8a682c1e8d097ae60d72ecf07c7';
        // $userid = $this->input->post('userid');
        $userid = $this->session->userdata('id');

        curl_setopt_array($curl, array(
            CURLOPT_URL => "http://localhost:81/restapi/api/master_data/kategori_biaya?token=$token&X-API-KEY=123&userid=$userid",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            // CURLOPT_HTTPHEADER => array('X-API-KEY : 123')
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
            echo "cURL Error #:" . $err;
        } else {
            $array_response = json_decode($response, true);
            $data_kategori = $array_response['data'];
            echo "<option value=''> -- Pilih Kategori -- </option>";

            foreach ($data_kategori as $key => $tiap_kategori) {
                echo "<option value='" . $tiap_kategori["kategori_id"] . "' id='" . $tiap_kategori["kategori_id"] . "' >";
                echo ucwords($tiap_kategori["nama_kategori"].' - (Rp.'.number_format($tiap_kategori["biaya"]).')');
                echo "</option>";
            }
        }
    }

    public function limit_budget()
    {

        $curl = curl_init();
        $token = '11f3a8a682c1e8d097ae60d72ecf07c7';
        // $userid = $this->input->post('userid');
        $userid = $this->session->userdata('id');
        $kategori_id = $this->input->post('kategori_id');

        curl_setopt_array($curl, array(
            CURLOPT_URL => "http://localhost:81/restapi/api/master_data/kategori_biaya?token=$token&X-API-KEY=123&userid=$userid&kategori_id=$kategori_id",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            // CURLOPT_HTTPHEADER => array('X-API-KEY : 123')
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
            echo "cURL Error #:" . $err;
        } else {
            $array_response = json_decode($response, true);
            $data_limit_budget = $array_response['data'];

            foreach ($data_limit_budget as $key => $tiap_budget) {
                echo $tiap_budget["biaya"];
            }
        }
    }

    public function get_aktivitas()
    {

        $curl = curl_init();
        $token = '11f3a8a682c1e8d097ae60d72ecf07c7';
        $id_rpd = $this->input->post('id');

        curl_setopt_array($curl, array(
            CURLOPT_URL => "http://localhost:81/restapi/api/master_data/aktivitas?token=$token&X-API-KEY=123&id_rpd=$id_rpd",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            // CURLOPT_HTTPHEADER => array('X-API-KEY : 123')
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
            echo "cURL Error #:" . $err;
        } else {
            $array_response = json_decode($response, true);
            $data_aktivitas = $array_response['data'];
            echo "<option value=''> -- Pilih Aktivitas -- </option>";

            foreach ($data_aktivitas as $key => $aktivitas) {
                echo "<option value='" . $aktivitas["id"] . "' id='" . $aktivitas["id"] . "' >";

                echo ucwords($aktivitas["rencana"]);
                echo "</option>";
            }
        }
    }

    public function delete_aktivitas()
    {
        $signature = $this->uri->segment('3');
        $id = $this->uri->segment('4');

        $data = [
            'deleted' => '1',
            'updated_by' => $this->session->userdata('id'),
            'updated_at' => $this->model_outlet_transaksi->timezone(),
        ];

        $this->db->where('md5(id)', $id);
        $this->db->where('signature', $signature);
        $this->db->update('site.t_rpd_aktivitas', $data);
        redirect("rpd/aktivitas/$signature");
    }

    public function delete_realisasi()
    {
        $signature = $this->uri->segment('3');
        $id = $this->uri->segment('4');
        $aktivitas_id = $this->uri->segment('5');

        $data = [
            'deleted' => '1',
            'updated_by' => $this->session->userdata('id'),
            'updated_at' => $this->model_outlet_transaksi->timezone(),
        ];

        $this->db->where('md5(id)', $id);
        $this->db->where('signature', $signature);
        $this->db->update('site.t_rpd_realisasi', $data);
        redirect("rpd/view_realisasi/$signature/$aktivitas_id");
    }

    public function request_approval($signature)
    {

        $cek_aktivitas = $this->db->get_where('site.t_rpd_aktivitas', array('signature' => $signature, 'deleted' => 0));
        $result = $cek_aktivitas->num_rows();
        // echo "result : ".$result;
        if (!$result) {
            // aler
            // redirect('rpd/aktivitas'.$signature);
            // echo "<script>alert('Aktivitas anda masih kosong. Pastikan isi aktivitas sebelum mengajukan approval !'); window.location.replace('rpd/aktivitas/$signature');</script>";

            echo "<script>alert('Silahkan isi RENCANA AKTIVITAS anda sebelum mengajukan approval'); </script>";
            redirect('rpd/aktivitas/'.$signature, 'refresh');
        }

        // die;

        // die;

        $data = [
            'status_approval' => '0',
            'nama_status_approval' => 'proses',
            'updated_at' => $this->model_outlet_transaksi->timezone(),
            'updated_by' => $this->session->userdata('id'),
        ];

        $this->db->where('signature', $signature);
        $this->db->update('site.t_rpd', $data);

        $this->report_pdf_download($signature);

        $this->email_rpd($signature);
    }

    public function master_karyawan()
    {
        $data = [
            'id' => $this->session->userdata('id'),
            'title' => 'Master Karyawan',
            'get_label' => $this->M_menu->get_label(),
            'data_karyawan' => $this->model_rpd->getMaster_karyawan()->result(),
            'list_user' => $this->model_asset->getUser()->result(),
        ];

        $this->load->view('template_claim/top_header');
        $this->load->view('template_claim/header');
        $this->load->view('template_claim/sidebar', $data);
        $this->load->view('template_claim/top_content', $data);
        $this->load->view('rpd/master_karyawan', $data);
        $this->load->view('template_claim/bottom_content');
        $this->load->view('template_claim/footer');
    }

    public function master_karyawan_simpan()
    {
        $id = $this->input->post('id');

        if ($id == '') {
            $data = [
                'userid' => $this->input->post('userid'),
                'atasan_id' => $this->input->post('atasan_id'),
                'status' => $this->input->post('status'),
                'created_by' => $this->session->userdata('id'),
                'created_at' => $this->model_outlet_transaksi->timezone(),
            ];
            $this->model_rpd->insert('site.m_karyawan', $data);
        } else {
            $data = [
                'atasan_id' => $this->input->post('atasan_id'),
                'status' => $this->input->post('status'),
                'updated_by' => $this->session->userdata('id'),
                'updated_at' => $this->model_outlet_transaksi->timezone(),
            ];
            $this->db->where('id', $id);
            $this->db->update('site.m_karyawan', $data);
        }
        redirect('rpd/master_karyawan');

    }

    public function master_karyawan_delete()
    {
        $id = $this->uri->segment('3');
        $data = [
            'deleted' => '1',
            'updated_by' => $this->session->userdata('id'),
            'updated_at' => $this->model_outlet_transaksi->timezone(),
        ];

        $this->db->where('md5(id)', $id);
        $this->db->update('site.m_karyawan', $data);
        redirect('rpd/master_karyawan');
    }

    public function master_biaya()
    {
        $data = [
            'id' => $this->session->userdata('id'),
            'title' => 'Master Biaya',
            'get_label' => $this->M_menu->get_label(),
            'data_biaya' => $this->model_rpd->getMaster_biaya()->result(),
            'list_user' => $this->model_asset->getUser()->result(),
            'list_kategori_biaya' => $this->model_rpd->getKategori_biaya()->result(),
        ];

        $this->load->view('template_claim/top_header');
        $this->load->view('template_claim/header');
        $this->load->view('template_claim/sidebar', $data);
        $this->load->view('template_claim/top_content', $data);
        $this->load->view('rpd/master_biaya', $data);
        $this->load->view('template_claim/bottom_content');
        $this->load->view('template_claim/footer');
    }

    public function master_biaya_simpan()
    {
        $id = $this->input->post('id');

        if ($id == '') {
            $data = [
                'userid' => $this->input->post('userid'),
                'kategori_id' => $this->input->post('kategori_id'),
                'biaya' => $this->input->post('biaya'),
                'created_by' => $this->session->userdata('id'),
                'created_at' => $this->model_outlet_transaksi->timezone(),
            ];
            $this->model_rpd->insert('site.m_rpd_biaya', $data);
        } else {
            $data = [
                'biaya' => $this->input->post('biaya'),
                'updated_by' => $this->session->userdata('id'),
                'updated_at' => $this->model_outlet_transaksi->timezone(),
            ];
            $this->db->where('id', $id);
            $this->db->update('site.m_rpd_biaya', $data);
        }
        redirect('rpd/master_biaya');

    }

    public function master_biaya_delete()
    {
        $id = $this->uri->segment('3');
        $data = [
            'deleted' => '1',
            'updated_by' => $this->session->userdata('id'),
            'updated_at' => $this->model_outlet_transaksi->timezone(),
        ];

        $this->db->where('md5(id)', $id);
        $this->db->update('site.m_rpd_biaya', $data);
        redirect('rpd/master_biaya');
    }

    public function add_kategori()
    {
        $data = [
            'nama_kategori' => $this->input->post('kategori'),
            'created_by' => $this->session->userdata('id'),
            'created_at' => $this->model_outlet_transaksi->timezone(),
        ];
        $this->model_rpd->insert('site.m_rpd_kategori_biaya', $data);

        redirect('rpd/master_biaya');

    }

    function email(){
        $this->load->library('email');

        $config['protocol']     = 'smtp';
        $config['smtp_host']    = 'ssl://mail.muliaputramandiri.com';
        $config['smtp_port']    = '465';
        $config['smtp_timeout'] = '300';
        $config['smtp_user']    = 'support@muliaputramandiri.com';
        $config['smtp_pass']    = 'support123!@#';
        $config['charset']      = 'utf-8';
        $config['newline']      = "\r\n";
        $config['mailtype']     ="html";
        $config['use_ci_email'] = TRUE;
        $config['wordwrap']     = TRUE;

        $this->email->initialize($config);
    }

    public function email_rpd($signature)
	{
        $url = base_url().'rpd/aktivitas/'.$signature;
        // var_dump($signature);die;
        $this->db->select('*');
        $this->db->where('signature', $signature);
        $data_rpd = $this->db->get('site.t_rpd')->row();
        $userid = $data_rpd->created_by;
        // var_dump($userid);die;

        // var_dump($data_rpd);
        // $filename = $get_kode->kode;
        // $filename_pdf = str_replace("/","_", $filename);
        $filename = $data_rpd->kode;
        $filename_pdf = str_replace("/","_", $filename);
        // echo "filename_pdf : ".$filename_pdf;
        // die;

        $data_karyawan = $this->model_rpd->getMaster_karyawan('',$userid)->row();

        if (!$data_karyawan) {
            echo "<script>alert('Pengiriman email error. Silahkan hubungi IT !'); window.location.replace('$url');</script>";
            die;
        }

        // var_dump($data_karyawan);die;
        $email_karyawan = $data_karyawan->email_karyawan;
        $email_atasan = $data_karyawan->email_atasan;

        $this->db->select_sum('nominal_biaya');
        $this->db->where('signature',$signature);
        $this->db->where('deleted','0');
        $biaya = $this->db->get('site.t_rpd_aktivitas')->row();

        // var_dump($biaya);die;

		$detail = [
            'atasan_id' => $data_karyawan->atasan_id,
            'nama_atasan' => $data_karyawan->nama_atasan,
			'kode' => $data_rpd->kode,
			'signature' => $signature,
			'tanggal_berangkat' => $data_rpd->tanggal_berangkat,
			'tempat_tujuan' => $data_rpd->tempat_tujuan,
            'nama_karyawan' => $data_karyawan->nama_karyawan,
			'maksud_perjalanan_dinas' => $data_rpd->maksud_perjalanan_dinas,
			'total_biaya' => $biaya->nominal_biaya,
		];

        // var_dump($url);die;

        $from = "$email_karyawan";
        $to = "$email_atasan";
        // $cc = "ilhammsyah@gmail.com,suffy@muliaputramandiri.com";
        $subject = "Mulia Putra Mandiri | Rencana Perjalanan Dinas";

        $message = $this->load->view("rpd/email_rpd",$detail,TRUE);

        $this->email();
        $this->email->from($from,'PT. Mulia Putra Mandiri');
        $this->email->to($to);
        // $this->email->cc($cc);
        $this->email->subject($subject);
        $this->email->message($message);
        $this->email->attach('assets/file/rpd/'.$filename_pdf.'.pdf');
        $send = $this->email->send();
        // echo $this->email->print_debugger();
        if ($send) {
            echo "<script>alert('Pengajuan RPD sudah berhasil dan diteruskan ke atasan anda untuk proses approval'); window.location.replace('$url');</script>";
        }else{
            echo "<script>alert('Pengiriman email pengajuan RPD gagal'); window.location.replace('$url');</script>";
        }
	}

    public function report_pdf(){
        $signature = $this->uri->segment('3');
        $get_id_rpd = $this->model_rpd->get_id_rpd($signature);
        // var_dump($id_pdf);die;

        $this->load->library('mypdf');
        $data = [
            'header' => $this->model_rpd->get_history($get_id_rpd,'1')->row(),
            'aktivitas' => $this->model_rpd->get_aktivitas($get_id_rpd,'')->result(),
            'realisasi' => $this->model_rpd->get_realisasi('','',$get_id_rpd)->result(),
        ];

        $this->db->select_sum('nominal_biaya');
        $this->db->where('id_rpd',$get_id_rpd);
        $this->db->where('deleted','0');
        $total_aktivitas = $this->db->get('site.t_rpd_aktivitas')->result();

        $data['total_aktivitas'] = $total_aktivitas['0']->nominal_biaya;

        $this->db->select_sum('nominal_biaya');
        $this->db->where('rpd_id',$get_id_rpd);
        $this->db->where('deleted','0');
        $total_realisasi = $this->db->get('site.t_rpd_realisasi')->result();

        $data['total_realisasi'] = $total_realisasi['0']->nominal_biaya;

        $generate_pdf = $this->mypdf->generate('rpd/template_pdf_rpd',$data,$data['header']->kode." - RPD",'A4','portrait');
    }

    public function report_pdf_download($signature){
        // $signature = $this->uri->segment('3');
        $get_id_rpd = $this->model_rpd->get_id_rpd($signature);
        // var_dump($id_pdf);die;

        $this->load->library('mypdf');
        $data = [
            'header' => $this->model_rpd->get_history($get_id_rpd,'1')->row(),
            'aktivitas' => $this->model_rpd->get_aktivitas($get_id_rpd,'')->result(),
            'realisasi' => $this->model_rpd->get_realisasi('','',$get_id_rpd)->result(),
        ];

        $this->db->select_sum('nominal_biaya');
        $this->db->where('id_rpd',$get_id_rpd);
        $this->db->where('deleted','0');
        $total_aktivitas = $this->db->get('site.t_rpd_aktivitas')->result();

        $data['total_aktivitas'] = $total_aktivitas['0']->nominal_biaya;

        $this->db->select_sum('nominal_biaya');
        $this->db->where('rpd_id',$get_id_rpd);
        $this->db->where('deleted','0');
        $total_realisasi = $this->db->get('site.t_rpd_realisasi')->result();

        $data['total_realisasi'] = $total_realisasi['0']->nominal_biaya;

        $get_kode = $this->model_rpd->get_history($get_id_rpd,'1')->row();
        $filename = $get_kode->kode;
        $filename_pdf = str_replace("/","_", $filename);
        // echo "filename : ".$filename;
        // die;

        // $generate_pdf = $this->mypdf->download_rpd('rpd/template_pdf_rpd',$data,$data['header']->kode." - RPD",'A4','portrait');
        $generate_pdf = $this->mypdf->download_rpd('rpd/template_pdf_rpd',$data,$filename_pdf,'A4','portrait');
    }

}
