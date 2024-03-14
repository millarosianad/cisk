<?php

if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Management_asset extends MY_Controller
{
    function management_asset()
    {       
        $logged_in= $this->session->userdata('logged_in');
        if(!isset($logged_in) || $logged_in != TRUE)
        {
            redirect('login_sistem/management_asset','refresh');
        }
        set_time_limit(0);
        $this->load->library(array('table', 'template', 'Excel_generator', 'form_validation', 'email', 'zip'));
        $this->load->helper(array('url', 'csv'));
        $this->load->model(array('model_outlet_transaksi','model_management_asset'));
    }
    
    function index()
    {
        $this->my_asset();
    }

    public function my_asset()
    {
        $userid =$this->session->userdata('id');
        $data = [
            'title'     => 'My Asset',
            'asset'     => $this->model_management_asset->my_asset($userid),
        ];
        
        $this->load->view('management_rpd/top_header', $data);
        $this->load->view('kalimantan/header_full_width', $data);
        $this->load->view('management_asset/my_asset',$data);
        $this->load->view('kalimantan/footer');
    }

    // ======================== pengajuan asset ===========================

    public function pengajuan_asset()
    {
        $userid = $this->session->userdata('id');
        if ($userid == 297 || $userid == 547 || $userid == 134 || $userid == 231) {
            $pr = $this->model_management_asset->pengajuan_asset();
        } else {
            $pr = $this->model_management_asset->pengajuan_asset_by_m_karyawan($userid);
        }
        
        $data = [
            'title'     => 'Pengajuan Asset',
            'url'       => 'management_asset/pengajuan_asset_tambah',
            'pr'        => $pr,
            'username'  => $this->session->userdata('username'),
            'userid'    => $userid
        ];
        
        $this->load->view('management_rpd/top_header', $data);
        $this->load->view('kalimantan/header_full_width', $data);
        $this->load->view('management_asset/pengajuan_asset',$data);
        $this->load->view('kalimantan/footer');
    }

    public function pengajuan_asset_tambah()
    {
        if(!$this->input->post('nama')){
            redirect('management_asset/pengajuan_asset');
            die;
        }

        //mendapatkan userid atasan untuk view detail atasan
        $userid = $this->session->userdata('id');
        $query = "
                SELECT userid_verifikasi1 FROM management_rpd.m_karyawan a
                WHERE a.userid_pelaksana = $userid
                ORDER BY id desc
                limit 1
            ";
        $userid_atasan = $this->db->query($query)->row()->userid_verifikasi1;

        $created_at = $this->model_outlet_transaksi->timezone();
        $signature = 'PR-' . rand() . md5($this->model_outlet_transaksi->timezone()) . date('Ymd');
        
        //generate draft no pr
        $no_pr = $this->model_management_asset->generate_draft($created_at);

        $data   = [
            'no_pr'             => $no_pr,
            'divisi'            => $this->input->post('divisi'),
            'barang'            => $this->input->post('barang'),
            'keterangan'        => $this->input->post('keterangan'),
            'userid_atasan'     => $userid_atasan,
            'created_by'        => $this->session->userdata('id'),
            'created_at'        => $created_at,
            'signature '        => $signature,
        ];

        $this->db->insert('management_asset.pengajuan',$data);
        redirect("management_asset/email_verifikasi1/$signature");
    }

    public function pengajuan_asset_detail()
    {
        $signature = $this->uri->segment(3);
        $userid = $this->session->userdata('id');

        $data = [
            'title'         => 'Pengajuan Asset Detail',
            'signature'     => $signature,
            'pr_summary'    => $this->model_management_asset->pengajuan_asset_by_signature($signature)->result(),
            'pr_detail'     => $this->model_management_asset->pengajuan_asset_detail($signature)->result(),
            'userid'        => $userid,
        ];

        // view dengan url dan userid
        $flag_detail = $this->uri->segment(4);
        if ($flag_detail == 'detail_atasan') {
            $view = 'management_asset/pengajuan_asset_detail_atasan';
        } else if ($flag_detail == 'detail_it' && ($userid == 297 || $userid == 547)) {
            $view = 'management_asset/pengajuan_asset_detail_it';
        } else if ($flag_detail == 'detail_finance'  && ($userid == 134 || $userid == 231)) {
            $view = 'management_asset/pengajuan_asset_detail_finance';
        } else {
            $view = 'management_asset/pengajuan_asset_detail';
        }

        $this->load->view('management_rpd/top_header', $data);  
        $this->load->view('template_claim/top_header_signature');
        $this->load->view('kalimantan/header_full_width', $data);
        $this->load->view($view,$data);
        $this->load->view('kalimantan/footer');
    }

    public function pengajuan_asset_konfirm_atasan()
    {
        $signature = $this->input->post('signature');
        $submit = $this->input->post('submit');
        $created_at = $this->model_outlet_transaksi->timezone();

        //cek submit approved
        if ($submit == '1') {
            // cek apakah sudah ada di database
            $folderPath = './assets/uploads/signature/pengajuan_asset/';        
            $image_parts = explode(";base64,", $_POST['signed']);
            $image_type_aux = explode("image/", $image_parts[0]);
            $image_type = $image_type_aux[1];
            $image_base64 = base64_decode($image_parts[1]);
            $file = $folderPath . $signature . '-verifikasi1.' .$image_type;
            file_put_contents($file, $image_base64);
            
            $status = '1';
            $nama_status = 'proses verifikasi 2 (IT)';
            $flag_atasan = '1';
            $ttd_atasan = $signature . '-verifikasi1.' .$image_type;
        } else {
            //status dan flag atasan
            $status = '9';
            $nama_status = 'rejected by (Atasan)';
            $flag_atasan = '9';
            $ttd_atasan = '';
        }
        //

        $data = [
            'userid_atasan'         => $this->session->userdata('id'),
            'username_atasan'       => $this->session->userdata('username'),
            'tgl_konfirmasi_atasan' => $created_at,
            'flag_atasan'           => $flag_atasan,
            'ttd_atasan'            => $ttd_atasan,
            'status'                => $status,
            'nama_status'           => $nama_status,
            'updated_at'            => $created_at,
            'updated_by'            => $this->session->userdata('id'),
        ];
        $this->db->where('signature', $signature);
        $this->db->update('management_asset.pengajuan', $data);

        redirect("management_asset/email_verifikasi2/$signature");
    }

    public function pengajuan_asset_konfirm_it()
    {
        $signature = $this->uri->segment('3');
        $created_at = $this->model_outlet_transaksi->timezone();

        $data = [
            'userid_it'                 => $this->session->userdata('id'),
            'username_it'               => $this->session->userdata('username'),
            'tgl_konfirmasi_it'         => $created_at,
            'flag_it'                   => '1',
            'status'                    => '2',
            'nama_status'               => 'proses verifikasi 3 (Finance)',
            'userid_finance'            => '',
            'username_finance'          => '',
            'tgl_konfirmasi_finance'    => '',
            'flag_finance'              => '0',
            'ttd_finance'               => '',
            'updated_by'                => $this->session->userdata('id'),
            'updated_at'                => $created_at,
        ];

        $this->db->where('signature',$signature);
        $this->db->update('management_asset.pengajuan',$data);

        redirect("management_asset/email_verifikasi3/$signature");
    }

    public function pengajuan_asset_konfirm_it_tambah()
    {
        $signature = $this->input->post('signature');
        $submit = $this->input->post('submit');
        $created_at = $this->model_outlet_transaksi->timezone();
        $barang = $this->input->post('barang_it');
        $spesifikasi  = $this->input->post('spesifikasi');
        $harga  = $this->input->post('harga');
        $link = $this->input->post('link');
        $jml_barang = count($barang);

        // var_dump($barang);die;
        
        //cek apakah ada ttd it
        if ($submit == '1') {
            // cek apakah sudah ada di database
            $folderPath = './assets/uploads/signature/pengajuan_asset/';        
            $image_parts = explode(";base64,", $_POST['signed']);
            $image_type_aux = explode("image/", $image_parts[0]);
            $image_type = $image_type_aux[1];
            $image_base64 = base64_decode($image_parts[1]);
            $file = $folderPath . $signature . '-verifikasi2.' .$image_type;
            file_put_contents($file, $image_base64);
            
            $data = [
                'userid_it'                 => $this->session->userdata('id'),
                'username_it'               => $this->session->userdata('username'),
                'keterangan_it'             => $this->input->post('keterangan_it'),
                'ttd_it'                    => $signature . '-verifikasi2.' .$image_type,
                'updated_by'                => $this->session->userdata('id'),
                'updated_at'                => $created_at,
            ];

            $this->db->where('signature',$signature);
            $this->db->update('management_asset.pengajuan',$data);
            
           
            //untuk mendapatkan id yang di update
            $this->db->select('id');
            $this->db->where('signature',$signature);
            $id_ref = $this->db->get('management_asset.pengajuan')->row()->id;
            // var_dump($id_ref);die;

            //mengambil urutan jika barang sudah ada
            $query = "
                select urutan
                from management_asset.pengajuan_detail
                where id_ref = $id_ref and signature = '$signature'
                ORDER BY id desc
                limit 1
            ";

            $urutan = $this->db->query($query);
            if ($urutan->num_rows() > 0) {
                $params_urut = $urutan->row()->urutan + 1;
            }else{
                $params_urut = 1;
            }
            //

            if ($barang[0] != '' || $barang[0] != null){
                for ($i=0; $i < $jml_barang ; $i++) { 
                    $data = [
                        'id_ref'        => $id_ref,
                        'barang'        => $barang[$i],
                        'spesifikasi'   => $spesifikasi[$i],
                        'harga'         => $harga[$i],
                        'link'          => $link[$i],
                        'urutan'        => $params_urut,
                        'userid_it'     => $this->session->userdata('id'),
                        'created_by'    => $this->session->userdata('id'),
                        'created_at'    => $created_at,
                        'signature'     => $signature
                    ];
        
                    $this->db->insert('management_asset.pengajuan_detail', $data);
                    $params_urut++;
                }
            }
            redirect("management_asset/pengajuan_asset_detail/$signature/detail_it",'refresh');
            
        } else {
            $data = [
                'userid_it'         => $this->session->userdata('id'),
                'username_it'       => $this->session->userdata('username'),
                'tgl_konfirmasi_it' => $created_at,
                'flag_it'           => '9',
                'status'            => '9',
                'nama_status'       => 'rejected by (IT)',
                'updated_by'        => $this->session->userdata('id'),
                'updated_at'        => $created_at,
            ];
            
            $this->db->where('signature',$signature);
            $this->db->update('management_asset.pengajuan',$data);
            redirect("management_asset/pengajuan_asset",'refresh');
        }
    }

    public function pengajuan_asset_konfirm_it_delete()
    {
        $signature = $this->uri->segment('3');
        $id_barang = $this->uri->segment('4');
        // var_dump($id_barang);die;

        $data = [
            'deleted'       => '1',
            'updated_at'    => $this->model_outlet_transaksi->timezone(),
            'updated_by'    => $this->session->userdata('id'),
        ];

        $this->db->where('id', $id_barang);
        $this->db->where('signature', $signature);
        $this->db->update('management_asset.pengajuan_detail', $data);

        redirect("management_asset/pengajuan_asset_detail/$signature/detail_it");
    }

    public function pengajuan_asset_konfirm_finance()
    {
        $signature = $this->input->post('signature');
        $submit = $this->input->post('submit');

        //generate no pr
        $created_at = $this->model_outlet_transaksi->timezone();
        $no_pr = $this->model_management_asset->generate($created_at);

        //cek apakah ada ttd finance
        if ($submit == '1') {
            // cek apakah sudah ada di database
            $folderPath = './assets/uploads/signature/pengajuan_asset/';        
            $image_parts = explode(";base64,", $_POST['signed']);
            $image_type_aux = explode("image/", $image_parts[0]);
            $image_type = $image_type_aux[1];
            $image_base64 = base64_decode($image_parts[1]);
            $file = $folderPath . $signature . '-verifikasi3.' .$image_type;
            file_put_contents($file, $image_base64);
            
            $status = '4';
            $nama_status = 'approved';
            $flag_finance = '1';
            $ttd_finance = $signature . '-verifikasi3.' .$image_type;
        } else {
            //status dan flag finance
            $status = '9';
            $nama_status = 'rejected by (Finance)';
            $flag_finance = '9';
            $ttd_finance = '';
        }
        //

        $data = [
            'no_pr'                     => $no_pr,
            'userid_finance'            => $this->session->userdata('id'),
            'username_finance'          => $this->session->userdata('username'),
            'tgl_konfirmasi_finance'    => $created_at,
            'flag_finance'              => $flag_finance,
            'ttd_finance'               => $ttd_finance,
            'status'                    => $status,
            'nama_status'               => $nama_status,
            'updated_at'                => $created_at,
            'updated_by'                => $this->session->userdata('id'),
        ];
        $this->db->where('signature', $signature);
        $this->db->update('management_asset.pengajuan', $data);

        redirect("management_asset/pengajuan_asset/",'refresh');
    }

    public function download_pdf_pr()
    {
        $this->load->library('mypdf');
        $signature = $this->uri->segment('3');
        // var_dump($id);die;
        
        $data = [
            'header'    => $this->model_management_asset->pengajuan_asset_by_signature($signature)->result(),
            'detail'    => $this->model_management_asset->pengajuan_asset_detail($signature)->result(),
        ];

        $generate_pdf = $this->mypdf->generate('management_asset/pengajuan_asset_pdf',$data,"$signature",'A4','portrait');
    }

    public function email_verifikasi1($signature_pengajuan)
    {
        $get_pengajuan = $this->model_management_asset->pengajuan_asset_by_signature($signature_pengajuan)->result();
        foreach ($get_pengajuan as $key) {
            $no_pr                  = $key->no_pr;
            $divisi                 = $key->divisi;
            $barang                 = $key->barang;
            $keterangan             = $key->keterangan;
            $userid                 = $key->created_by;
            $userid_verifikasi1     = $key->userid_atasan;
            $tgl_pengajuan          = $key->created_at;
        }

        $from = "suffy@muliaputramandiri.com";

        $username_pengajuan = $this->model_management_asset->getUser($userid)->row()->username;
        $username_verifikasi1 = $this->model_management_asset->getUser($userid_verifikasi1)->row()->username;
        // $email_to = $this->model_management_asset->getUser($userid_verifikasi1)->row()->email;
        $email_to = "ilhammsyah@gmail.com";
        $email_cc = $this->model_management_asset->getUser($userid)->row()->email;

        $subject = "MPM Site | Pengajuan Asset | VERIFIKASI 1";
        $this->load->model('model_relokasi');
        $this->model_relokasi->email();

        // $get_id_ref_relokasi_header = $this->model_relokasi->get_data_relokasi_header($signature)->row()->id;

        $data = [
            'title'                 => 'Pengajuan Asset - Verifikasi 1',
            'no_pr'                 => $no_pr,
            'divisi'                => $divisi,
            'barang'                => $barang,
            'keterangan'            => $keterangan,
            'username_pengajuan'    => $username_pengajuan,
            'username_verifikasi1'  => $username_verifikasi1,
            'tgl_pengajuan'         => $tgl_pengajuan,
            'signature_pengajuan'   => $signature_pengajuan,
        ];

        $message = $this->load->view("management_asset/email_verifikasi1",$data,TRUE);

        $this->email->from($from,'PT. Mulia Putra Mandiri');
        $this->email->to($email_to);
        $this->email->cc($email_cc);
        $this->email->subject($subject);
        $this->email->message($message);
        $send = $this->email->send();
        // echo $this->email->print_debugger();
        if ($send) {
            echo "<script>alert('pengiriman email ke atasan berhasil'); </script>";
            redirect('management_asset/pengajuan_asset','refresh');
        }
    }

    public function email_verifikasi2($signature_pengajuan)
    {
        $get_pengajuan = $this->model_management_asset->pengajuan_asset_by_signature($signature_pengajuan)->result();
        foreach ($get_pengajuan as $key) {
            $no_pr                  = $key->no_pr;
            $divisi                 = $key->divisi;
            $barang                 = $key->barang;
            $keterangan             = $key->keterangan;
            $userid                 = $key->created_by;
            $userid_verifikasi1     = $key->userid_atasan;
            $username_verifikasi1   = $key->username_atasan;
            $tgl_pengajuan          = $key->created_at;
        }

        $from = "suffy@muliaputramandiri.com";

        $username_pengajuan = $this->model_management_asset->getUser($userid)->row()->username;
        // $email_to = 'ilham@muliaputramandiri.com, suffy@muliaputramandiri.com, milla@muliaputramandiri.com';
        $email_to = "ilhammsyah@gmail.com";
        $email_cc = $this->model_management_asset->getUser($userid)->row()->email;

        $subject = "MPM Site | Pengajuan Asset | VERIFIKASI 2";
        $this->load->model('model_relokasi');
        $this->model_relokasi->email();

        // $get_id_ref_relokasi_header = $this->model_relokasi->get_data_relokasi_header($signature)->row()->id;

        $data = [
            'title'                 => 'Pengajuan Asset - Verifikasi 2',
            'no_pr'                 => $no_pr,
            'divisi'                => $divisi,
            'barang'                => $barang,
            'keterangan'            => $keterangan,
            'username_pengajuan'    => $username_pengajuan,
            'username_verifikasi1'  => $username_verifikasi1,
            'tgl_pengajuan'         => $tgl_pengajuan,
            'signature_pengajuan'   => $signature_pengajuan,
        ];

        $message = $this->load->view("management_asset/email_verifikasi2",$data,TRUE);

        $this->email->from($from,'PT. Mulia Putra Mandiri');
        $this->email->to($email_to);
        $this->email->cc($email_cc);
        $this->email->subject($subject);
        $this->email->message($message);
        $send = $this->email->send();
        // echo $this->email->print_debugger();
        if ($send) {
            echo "<script>alert('pengiriman email ke tim IT berhasil'); </script>";
            redirect('management_asset/pengajuan_asset','refresh');
        }
    }

    public function email_verifikasi3($signature_pengajuan)
    {
        $get_pengajuan = $this->model_management_asset->pengajuan_asset_by_signature($signature_pengajuan)->result();
        foreach ($get_pengajuan as $key) {
            $no_pr                  = $key->no_pr;
            $divisi                 = $key->divisi;
            $barang                 = $key->barang;
            $keterangan             = $key->keterangan;
            $userid                 = $key->created_by;
            $userid_verifikasi1     = $key->userid_atasan;
            $username_verifikasi1   = $key->username_atasan;
            $tgl_pengajuan          = $key->created_at;
        }

        $from = "suffy@muliaputramandiri.com";

        $username_pengajuan = $this->model_management_asset->getUser($userid)->row()->username;
        // $email_to = 'hwiryanto@muliaputramandiri.com, nanita@muliaputramandiri.com';
        $email_to = "ilhammsyah@gmail.com";
        $email_cc = $this->model_management_asset->getUser($userid)->row()->email;

        $get_detail = $this->model_management_asset->pengajuan_asset_detail($signature_pengajuan);
        $total_biaya = $this->model_management_asset->pengajuan_asset_by_signature($signature_pengajuan)->row()->total_biaya;

        $subject = "MPM Site | Pengajuan Asset | VERIFIKASI 3";
        $this->load->model('model_relokasi');
        $this->model_relokasi->email();

        $data = [
            'title'                 => 'Pengajuan Asset - Verifikasi 3',
            'no_pr'                 => $no_pr,
            'divisi'                => $divisi,
            'barang'                => $barang,
            'keterangan'            => $keterangan,
            'username_pengajuan'    => $username_pengajuan,
            'username_verifikasi1'  => $username_verifikasi1,
            'tgl_pengajuan'         => $tgl_pengajuan,
            'total_biaya'           => $total_biaya,
            'get_detail'            => $get_detail,
            'signature_pengajuan'   => $signature_pengajuan,
        ];

        $message = $this->load->view("management_asset/email_verifikasi3",$data,TRUE);

        $this->email->from($from,'PT. Mulia Putra Mandiri');
        $this->email->to($email_to);
        $this->email->cc($email_cc);
        $this->email->subject($subject);
        $this->email->message($message);
        $send = $this->email->send();
        // echo $this->email->print_debugger();
        if ($send) {
            echo "<script>alert('pengiriman email ke tim Finance berhasil'); </script>";
            redirect('management_asset/pengajuan_asset','refresh');
        }
    }

    // ===========================================================================================

    // ======================== input asset ke database cloud dari sds ===========================
    function get_asset_sds()
    {
        $kode = [
            'from'          => $_POST['from'],
            'to'            => $_POST['to'],
            'created_at'    => $this->model_outlet_transaksi->timezone()
        ];
        
        $this->model_management_asset->getAssets_sds($kode);
    }

    function get_asset()
    {
        $userid = $this->session->userdata('id');
        $data = $this->model_management_asset->getAssets_temp($userid)->result();
        // var_dump($data);die;
        echo json_encode($data);
    }

    function get_asset_by_kode()
    {
        $kode_sds = $_POST['kode_sds'];
        $data = $this->model_management_asset->getAssets_temp_by_kode($kode_sds)->row();
        // var_dump($data);die;
        echo json_encode($data);
    }
    
    public function data_asset()
    {
        $data = [
            'title'         => 'Data Asset',
            'asset'         => $this->model_management_asset->data_asset()->result(),
            'grup_asset'    => $this->model_management_asset->grup_asset()->result(),
        ];
        
        $this->load->view('management_rpd/top_header', $data);
        $this->load->view('kalimantan/header_full_width', $data);
        $this->load->view('management_asset/data_asset',$data);
        $this->load->view('kalimantan/footer');
    }

    public function data_asset_tambah()
    {
        $data   = [
            'kode'          => $this->input->post('kode_sds'),
            'tglperol'      => $this->input->post('tgl_pembelian'),
            'barang'        => $this->input->post('barang'),
            'jumlah'        => $this->input->post('jumlah'),
            'gol'           => $this->input->post('golongan'),
            'grupid'        => $this->input->post('grup'),
            'np'            => $this->input->post('nilai_perolehan'),
            'keperluan'     => $this->input->post('keperluan'),
            'created_by'    => $this->session->userdata('id'),
            'created_at'    => $this->model_outlet_transaksi->timezone(),
        ];

        $this->db->insert('management_asset.asset',$data);
        redirect('management_asset/data_asset');
    }

    public function data_asset_detail()
    {
        $id = $this->uri->segment('3');
        $data = [
            'title'                 => 'Data Asset Detail',
            'asset'                 => $this->model_management_asset->data_asset($id)->row(),
            'penyerahan_asset'      => $this->model_management_asset->data_asset_penyerahan($id)->result(),
            'grup_asset'            => $this->model_management_asset->grup_asset()->result(),
            'user'                  => $this->model_management_asset->getUser()->result(),
            'id'                    => $id,
            
        ];
        $this->load->view('management_rpd/top_header', $data);
        $this->load->view('kalimantan/header_full_width', $data);
        $this->load->view('management_asset/data_asset_detail',$data);
        $this->load->view('kalimantan/footer');
    }

    public function data_asset_update()
    {
        $id = $this->input->post('id');

        $this->load->library('upload'); // Load librari upload
        $config['upload_path'] = './assets/file/faktur_asset/';
        $config['allowed_types'] = 'pdf';
        $config['max_size']  = '*';
        $this->upload->initialize($config);

        if(!$this->upload->do_upload('file'))
        {
            $this->db->select('upload_faktur');
            $this->db->where('id',$id);
            $filename = $this->db->get('management_asset.asset')->row()->upload_faktur;
        }else{
            $upload_data = $this->upload->data();
            $filename = $upload_data["file_name"];
        }

        $data   = [
            'kode'          => $this->input->post('kode'),
            'tglperol'      => $this->input->post('tgl_pembelian'),
            'barang'        => $this->input->post('barang'),
            'jumlah'        => $this->input->post('jumlah'),
            'gol'           => $this->input->post('golongan'),
            'grupid'        => $this->input->post('grup'),
            'np'            => $this->input->post('nilai_perolehan'),
            'keperluan'     => $this->input->post('keperluan'),
            'tgljual'       => $this->input->post('tgl_jual'),
            'nj'            => $this->input->post('nilai_jual'),
            'keterangan'    => $this->input->post('keterangan'),
            'upload_faktur' => $filename,
            'updated_by'    => $this->session->userdata('id'),
            'updated_at'    => $this->model_outlet_transaksi->timezone(),
        ];

        $this->db->where('id',$id);
        $this->db->update('management_asset.asset',$data);
        redirect("management_asset/data_asset_detail/$id");
    }

    public function data_asset_penyerahan_tambah()
    {
        $id = $this->input->post('id');

        $data1 = [
            'flag_pengguna'     => '0'
        ];
        $this->db->where('id_ref',$id);
        $this->db->update('management_asset.penyerahan_asset',$data1);
        
        $data2   = [
            'id_ref'                => $id,
            'userid_pengguna'       => $this->input->post('userid_pengguna'),
            'tgl_penyerahan'        => $this->input->post('tgl_penyerahan'),
            'status'                => $this->input->post('status'),
            'ekspedisi_pengiriman'  => $this->input->post('ekspedisi_pengiriman'),
            'resi_pengiriman'       => $this->input->post('resi_pengiriman'),
            'biaya_pengiriman'      => $this->input->post('biaya_pengiriman'),
            'flag_pengguna'         => '1',
            'created_by'            => $this->session->userdata('id'),
            'created_at'            => $this->model_outlet_transaksi->timezone(),
        ];

        $this->db->insert('management_asset.penyerahan_asset',$data2);
        redirect("management_asset/data_asset_detail/$id");
    }
    
    // ===========================================================================================
}