<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Assets extends MY_Controller
{
    function assets()
    {
        $logged_in= $this->session->userdata('logged_in');
        if(!isset($logged_in) || $logged_in != TRUE)
        {
            redirect('login/','refresh');
        }
        set_time_limit(0);
        $this->load->library(array('table','template','Excel_generator', 'form_validation','email'));
        $this->load->helper('url');
        $this->load->helper('csv');
        $this->load->model('model_asset');
        $this->load->model('M_menu');
        $this->load->database();

    }

    public function qrcode(){
        //mencari uri segment

        $id = $this->uri->segment(3);
        //echo "id : ".$id;

        //query mencari 'kode, untuk, status' dari userid
          $this->db->where('id = '.$id);
          $query = $this->db->get('mpm.asset');
          foreach ($query->result() as $row) {
            $kode = $row->kode;
            $namabarang = $row->namabarang;
            $untuk = $row->untuk;
            $sn = $row->sn;
            //echo "supplier : ".$supplier;
          }


        $this->load->library('ci_qr_code');
        $this->config->load('qr_code');
        $qr_code_config = array(); 
        $qr_code_config['cacheable']  = $this->config->item('cacheable');
        $qr_code_config['cachedir']   = $this->config->item('cachedir');
        $qr_code_config['imagedir']   = $this->config->item('imagedir');
        $qr_code_config['errorlog']   = $this->config->item('errorlog');
        $qr_code_config['ciqrcodelib']  = $this->config->item('ciqrcodelib');
        $qr_code_config['quality']    = $this->config->item('quality');
        $qr_code_config['size']     = $this->config->item('size');
        $qr_code_config['black']    = $this->config->item('black');
        $qr_code_config['white']    = $this->config->item('white');

        $this->ci_qr_code->initialize($qr_code_config);

        $image_name = 'qr_code_test.png';

        //$params['data'] = "kode : ".br(1).base_url()."All_assets/detail_assets/".$id;
        
        $data = "kode asset : $kode\nnama asset : $namabarang\nPIC : $untuk\nS/N : $sn\nLihat Detail : ".base_url()."assets/detail_assets/".$id."";


        $params['data'] = $data;
        

        $params['level'] = "B";
        $params['size'] = "5";

        if($this->input->post('display_format') == 'image')
        {

          $params['savename'] = FCPATH.$qr_code_config['imagedir'].$image_name;
          $this->ci_qr_code->generate($params); 
          $this->data['qr_code_image_url'] = base_url().$qr_code_config['imagedir'].$image_name;
          // Display the QR Code here on browser uncomment the below line
          //echo '<img src="'.base_url().$qr_code_config['imagedir'].$image_name.'" />'; 
          $this->load->view('qr_code', $this->data); 
        }
        else
        {
          header("Content-Type: image/png"); 
          $this->ci_qr_code->generate($params);
        } 
    
    }

    public function my_asset(){
        $logged_in= $this->session->userdata('logged_in');
        if(!isset($logged_in) || $logged_in != TRUE)
        {
            redirect('login/','refresh');
        }

        $data = [
            'id'        => $this->session->userdata('id'),
            'url'       => 'assets/my_assets/',
            'title'     => 'My Asset',
            'get_label' => $this->M_menu->get_label(),
            'asset'     => $this->model_asset->my_asset(),
            'konfirmasi'     => $this->model_asset->konfirmasi_asset()
        ];

        $this->load->view('template_claim/top_header');
        $this->load->view('template_claim/header');
        $this->load->view('template_claim/sidebar',$data);
        $this->load->view('template_claim/top_content',$data);
        $this->load->view('assets/my_asset',$data);
        $this->load->view('template_claim/bottom_content');
        $this->load->view('template_claim/footer');
    }

    public function konfirmasi_asset(){
        $data = array(
            'id_asset'          => $this->uri->segment('3'),
            'id_mutasi'         => $this->uri->segment('4'),
            'alasan_approv'     => $this->input->post('approv')
        );

        $this->model_asset->save_konfirmasi_asset($data);
        redirect("assets/my_asset/",'refresh');
    }

    public function input_assets(){

        $logged_in= $this->session->userdata('logged_in');
        if(!isset($logged_in) || $logged_in != TRUE)
        {
            redirect('login/','refresh');
        }

        $kode = [
            'nv'  =>  $this->input->post('nv'),
            'from'  =>  $this->input->post('from'),
            'to'    =>  $this->input->post('to')
        ];
        $data = [
            'url'       => 'assets/input_assets_hasil/',
            'title'     => 'Tambah Asset',
            'get_label' => $this->M_menu->get_label(),
            'query'     =>$this->model_asset->getGrupassetcombo(),
            'q_modal'   =>$this->model_asset->getAssets_sds($kode),
            'no_po'     =>$this->model_asset->getPengajuan()
        ];

        $this->load->view('template_claim/top_header');
        $this->load->view('template_claim/header');
        $this->load->view('template_claim/sidebar',$data);
        $this->load->view('template_claim/top_content',$data);
        $this->load->view('assets/input_assets',$data);
        $this->load->view('template_claim/bottom_content');
        $this->load->view('template_claim/footer');
    }

    public function input_assets_hasil(){

        $logged_in= $this->session->userdata('logged_in');
        if(!isset($logged_in) || $logged_in != TRUE)
        {
            redirect('login/','refresh');
        }
            
            $data = array(
                'kode'           => $this->input->post('nv'),
                'namabarang'     => $this->input->post('nb'),
                'sn'             => $this->input->post('sn'),
                'jumlah'         => $this->input->post('jb'),
                'untuk'          => $this->input->post('kpr'),
                'tglperol'       => $this->input->post('tp'),
                'gol'            => $this->input->post('gol'),
                'grup'           => $this->input->post('grup'),
                'np'             => $this->input->post('np'),
                'nj'             => $this->input->post('nj'),
                'tgljual'        => $this->input->post('tj'),
                'deskripsi'      => $this->input->post('deskripsi'),
                'nopo'           => $this->input->post('nopo')
            );
            
            $data['asset']=$this->model_asset->input_assets($data);

    }

    public function view_assets(){

        $logged_in= $this->session->userdata('logged_in');
        if(!isset($logged_in) || $logged_in != TRUE)
        {
            redirect('login/','refresh');
        }
        
        $data = [
            'id'        => $this->session->userdata('id'),
            'url'       => 'assets/input_assets/',
            'title'     => 'Table Asset',
            'get_label' => $this->M_menu->get_label(),
            'asset'     =>$this->model_asset->view_asset()
        ];
        $this->load->view('template_claim/top_header');
        $this->load->view('template_claim/header');
        $this->load->view('template_claim/sidebar',$data);
        $this->load->view('template_claim/top_content',$data);
        $this->load->view('assets/view_assets',$data);
        $this->load->view('template_claim/bottom_content');
        $this->load->view('template_claim/footer');
    }

    public function detail_assets(){

        $this->load->library('form_validation');
  
        $logged_in= $this->session->userdata('logged_in');
        if(!isset($logged_in) || $logged_in != TRUE)
        {
            redirect('login/','refresh');
        }

        $data = [
            'url' => 'assets/view_assets/',
            'title' => 'Detail Asset',
            'get_label' => $this->M_menu->get_label(),
            'asset'     =>$this->model_asset->detail_asset(),
            'proses'    =>$this->model_asset->detail_mutasi()
        ];
        
        $segment = $this->uri->segment(3);
        $this->load->view('template_claim/top_header');
        $this->load->view('template_claim/header');
        $this->load->view('template_claim/sidebar',$data);
        $this->load->view('assets/detail_assets',$data);
        $this->load->view('template_claim/bottom_content');
        $this->load->view('template_claim/footer');
      }

    public function edit_assets(){

        $this->load->library('form_validation');
  
        $logged_in= $this->session->userdata('logged_in');
        if(!isset($logged_in) || $logged_in != TRUE)
        {
            redirect('login/','refresh');
        }

        $data = [
            'url' => 'assets/update_assets/',
            'title' => 'Edit Asset',
            'get_label' => $this->M_menu->get_label(),
            'asset'=>$this->model_asset->detail_asset(),
            'no_po'     =>$this->model_asset->getPengajuan()
        ];

        $this->load->view('template_claim/top_header');
        $this->load->view('template_claim/header');
        $this->load->view('template_claim/sidebar',$data);
        $this->load->view('template_claim/top_content',$data);
        $this->load->view('assets/edit_assets',$data);
        $this->load->view('template_claim/bottom_content');
        $this->load->view('template_claim/footer');
      }

    public function update_assets($id){
        $logged_in= $this->session->userdata('logged_in');
        if(!isset($logged_in) || $logged_in != TRUE)
        {
            redirect('login/','refresh');
        }

        $id=$this->session->userdata('id');

        $this->load->library('upload'); // Load librari upload        
        $config['upload_path'] = './assets/file/faktur_asset/';
        $config['allowed_types'] = 'pdf';    
        $config['max_size']  = '2048';
        $config['encrypt_name']	= TRUE;
        $this->upload->initialize($config);

        if(!$this->upload->do_upload('file'))
        { 
            $filename = '';
            // $error = $this->upload->display_errors();
            // menampilkan pesan error
            // print_r($error);
        }else{
            $upload_data = $this->upload->data();
            $filename = $upload_data["file_name"];
        }

        $data = array(
            'id'             => $this->uri->segment(3),    
            'nopo'           => $this->input->post('nopo'),
            'nj'             => $this->input->post('nj'),
            'tgljual'        => $this->input->post('tj'),
            'deskripsi'      => $this->input->post('deskripsi'),
            'upload_faktur'  => $filename
        );

        $data['asset']=$this->model_asset->proses_update($data);
    }

    public function delete_assets($id){

        $this->model_asset->proses_delete($id);
        redirect('assets/view_assets','refresh');
  
      }

    public function mutasi_assets(){

        $this->load->library('form_validation');

        $logged_in= $this->session->userdata('logged_in');
        if(!isset($logged_in) || $logged_in != TRUE)
        {
            redirect('login/','refresh');
        }

        $data = [
            'url' => 'assets/save_mutasi_assets/',
            'title' => 'Mutasi Asset',
            'get_label' => $this->M_menu->get_label(),
            'asset'=>$this->model_asset->detail_asset(),
            'user'=>$this->model_asset->getUser(),
            'proses'=>$this->model_asset->detail_mutasi()
        ];
        $segment = $this->uri->segment(3);

        $data['segments'] = $segment;
        $this->load->view('template_claim/top_header');
        $this->load->view('template_claim/header');
        $this->load->view('template_claim/sidebar',$data);
        $this->load->view('template_claim/top_content',$data);
        $this->load->view('assets/mutasi_assets',$data);
        $this->load->view('template_claim/bottom_content');
        $this->load->view('template_claim/footer');
    }

    public function save_mutasi_assets($id){
        $logged_in= $this->session->userdata('logged_in');
        if(!isset($logged_in) || $logged_in != TRUE)
        {
            redirect('login/','refresh');
        }

        $id=$this->session->userdata('id');

        $this->load->library('upload'); // Load librari upload        
        $config['upload_path'] = './assets/file/bukti_mutasi/';
        $config['allowed_types'] = 'gif|jpg|png';    
        $config['max_size']  = '2048';
        $config['encrypt_name']	= TRUE;
        $this->upload->initialize($config);

        if(!$this->upload->do_upload('file'))
        { 
            $filename = '';
        
        }else{

            //kalau berhasil upload file1, lakukan upload file2

            $upload_data = $this->upload->data();
            $filename = $upload_data["file_name"];

            // echo $filename;

            if(!$this->upload->do_upload('file2'))
            { 
                $filename2 = '';
                // $error = $this->upload->display_errors();
                // menampilkan pesan error
                // print_r($error);
            
            }else{
                $upload_data = $this->upload->data();
                $filename2 = $upload_data["file_name"];

                // echo $filename2;
            }

            $data = array(
                'id'            => $this->uri->segment('3'),
                'user'          => $this->input->post('user'),
                'tgl_mutasi'    => $this->input->post('tm'),
                'bukti_upload'  => $filename,
                'bukti_upload2'  => $filename2,
                'alasan_mutasi' => $this->input->post('am')
            );

            $data['asset']=$this->model_asset->mutasi_asset($data);

        }
    }

    public function edit_mutasi_assets($id_mutasi){
        $logged_in= $this->session->userdata('logged_in');
        if(!isset($logged_in) || $logged_in != TRUE)
        {
            redirect('login/','refresh');
        }

        $id=$this->session->userdata('id');

        $this->load->library('upload'); // Load librari upload        
        $config['upload_path'] = './assets/file/bukti_mutasi/';
        $config['allowed_types'] = 'gif|jpg|png';    
        $config['max_size']  = '2048';
        $config['encrypt_name']	= TRUE;
        $this->upload->initialize($config);

        if(!$this->upload->do_upload('file'))
        { 
            $filename = '';
        
        }else{

            //kalau berhasil upload file1, lakukan upload file2

            $upload_data = $this->upload->data();
            $filename = $upload_data["file_name"];

            // echo $filename;
        }

        if(!$this->upload->do_upload('file2'))
            { 
                $filename2 = '';
                // $error = $this->upload->display_errors();
                // menampilkan pesan error
                // print_r($error);
            
            }else{
                $upload_data = $this->upload->data();
                $filename2 = $upload_data["file_name"];

                // echo $filename2;
            }
        
        $data = array(
            'id'            => $this->uri->segment('3'),
            'id_mutasi'     => $this->uri->segment('4'),
            'user'          => $this->input->post('user'),
            'tgl_mutasi'    => $this->input->post('tm'),
            'bukti_upload'  => $filename,
            'bukti_upload2'  => $filename2,
            'alasan_mutasi' => $this->input->post('am')
        );

        $data['asset']=$this->model_asset->edit_mutasi_asset($data);
        
    }

    public function delete_mutasi($id,$id_mutasi){

        $this->model_asset->proses_delete_mutasi($id_mutasi);
        redirect("assets/mutasi_assets/$id",'refresh');
  
      }

    public function approv_mutasi_assets(){
        $id = $this->uri->segment('3');
        $data = array(
            'id_asset'         => $this->uri->segment('3'),
            'id_mutasi'         => $this->uri->segment('4'),
            'alasan_approv'     => $this->input->post('approv')
        );

        $this->model_asset->approv_mutasi($data);
        redirect("assets/mutasi_assets/$id",'refresh');
      }


    // ==============================Pengajuan Asset========================= //
    public function pengajuan_assets(){

        $logged_in= $this->session->userdata('logged_in');
        if(!isset($logged_in) || $logged_in != TRUE)
        {
            redirect('login/','refresh');
        }
        $data = [
            'id'        => $this->session->userdata('id'),
            'url'       => 'assets/input_barang_pengajuan/',
            'url2'       => 'assets/confirm_pengajuan/',
            'title'     => 'Pengajuan Assets',
            'get_label' => $this->M_menu->get_label(),
            'proses'    => $this->model_asset->showbarang()
        ];

        $this->load->view('template_claim/top_header');
        $this->load->view('template_claim/header');
        $this->load->view('template_claim/sidebar',$data);
        $this->load->view('assets/pengajuan_assets',$data);
        $this->load->view('assets/check_pengajuan',$data);
        $this->load->view('template_claim/bottom_content');
        $this->load->view('template_claim/footer');
    }

    public function input_barang_pengajuan(){
        $this->model_asset->input_barang_pengajuan();
    }

    public function delete_barang_pengajuan(){

        $this->model_asset->delete_barang_pengajuan();
    }

    public function confirm_pengajuan(){

        $logged_in= $this->session->userdata('logged_in');
        if(!isset($logged_in) || $logged_in != TRUE)
        {
            redirect('login/','refresh');
        }
        $id = $this->session->userdata('id');
        $query = $this->db->query("select * from db_temp.t_temp_pengajuan_asset_temp where created_by= $id");
        if($query->num_rows()==0)
           {
              redirect('assets/pengajuan_assets/','refresh');
           }
        $data = [
            'id'        => $this->session->userdata('id'),
            'url'       => 'assets/simpan_pengajuan/',
            'title'     => 'Pengajuan Assets',
            'get_label' => $this->M_menu->get_label(),
            'user'=>$this->model_asset->getUser()
        ];
        
        $this->load->view('template_claim/top_header');
        $this->load->view('template_claim/header');
        $this->load->view('template_claim/sidebar',$data);
        $this->load->view('assets/confirm_pengajuan',$data);
        $this->load->view('template_claim/bottom_content');
        $this->load->view('template_claim/footer');
    }

    // public function input_pengajuan(){
    //     $this->model_asset->pengajuan_asset();
    // }
    
    public function simpan_pengajuan(){
        $this->load->library('upload'); // Load librari upload        
        $config['upload_path'] = './assets/file/bukti_permintaan/';
        $config['allowed_types'] = 'gif|jpg|png';    
        $config['max_size']  = '2048';
        $config['encrypt_name']	= TRUE;
        $this->upload->initialize($config);

        if(!$this->upload->do_upload('file'))
        { 
            $error = $this->upload->display_errors();
            // menampilkan pesan error
            print_r($error);
        
        }else{
            $upload_data = $this->upload->data();
            $filename = $upload_data["file_name"];
            $data = array(
                'upload'  => $filename
            );
        $this->model_asset->save_pengajuan($data);
        }
    }

    public function view_pengajuan(){

        $logged_in= $this->session->userdata('logged_in');
        if(!isset($logged_in) || $logged_in != TRUE)
        {
            redirect('login/','refresh');
        }
        $data = [
            'id'        => $this->session->userdata('id'),
            'url'       => 'assets/input_pengajuan/',
            'title'     => 'Pengajuan Assets',
            'get_label' => $this->M_menu->get_label(),
            'proses' => $this->model_asset->view_pengajuan()
        ];

        $this->load->view('template_claim/top_header');
        $this->load->view('template_claim/header');
        $this->load->view('template_claim/sidebar',$data);
        $this->load->view('template_claim/top_content',$data);
        $this->load->view('assets/view_pengajuan',$data);
        $this->load->view('template_claim/bottom_content');
        $this->load->view('template_claim/footer');
    }
    
    public function pengajuan_pdf (){
        if ( function_exists( 'date_default_timezone_set' ) )
        date_default_timezone_set('Asia/Jakarta');
        $no_po = $this->uri->segment(3);
        $data['no'] = substr($no_po,0,3);
        $data['bln'] = substr($no_po,6,2);
        $data['thn'] = substr($no_po,8,4);
        $this->load->library('dompdf_gen');
        $data['toko'] = $this->db->query("select * from db_temp.t_temp_pengajuan_asset where no_po = '$no_po'")->result();
        $data['barang'] = $this->db->query("select * from db_temp.t_temp_pengajuan_asset_detail where no_po = '$no_po'")->result();
        $data['total'] = $this->db->query("select sum(sub_harga) as sub_harga, sum(tax) as sub_tax, SUM(sub_harga+tax) as total
        FROM db_temp.t_temp_pengajuan_asset_detail where no_po = '$no_po'")->result();

        $this->load->view('assets/report_pengajuan', $data);
        $paper_size = 'A4';
        $orientation = 'potrait';
        $html = $this->output->get_output();
        $this->dompdf->set_paper($paper_size. $orientation);

        $this->dompdf->load_html($html);
        $this->dompdf->render();
        $this->dompdf->stream("Report_pegajuan_$no_po.pdf", array('attachment'=>0));

    }
}
?>