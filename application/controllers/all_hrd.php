<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class All_hrd extends MY_Controller
{
    var $nocab;
    var $options;
    var $image_properties_pdf = array(
          'src' => 'assets/css/images/pdf.png',
          'alt' => 'Printing Document',
          'class' => 'post_images',
          'width' => '50',
          'height' => '50',
          'title' => 'Printer',
          'border' => 0,
    );
    var $image_properties_excel = array(
          'src' => 'assets/css/images/excel.png',
          'alt' => 'Printing Document',
          'class' => 'post_images',
          'width' => '50',
          'height' => '50',
          'title' => 'Printer',
          'border' => 0,
    );
    var $querymenu;
    var $attr = array(
              'width'      => '800',
              'height'     => '600',
              'scrollbars' => 'yes',
              'status'     => 'yes',
              'resizable'  => 'yes',
              'screenx'   =>  '\'+((parseInt(screen.width) - 800)/2)+\'',
              'screeny'   =>  '\'+((parseInt(screen.height) - 600)/2)+\'',

            );

    function all_hrd()
    {
        set_time_limit(0);
        $this->load->library(array('table','template','Excel_generator', 'form_validation','email'));
        $this->load->helper('url');
        $this->load->model('model_hrd');
        $this->load->database();
        $this->querymenu='select  a.id,
                        a.menuview,
                        a.target,
                        a.groupname 
                from    mpm.menu a inner join mpm.menudetail b 
                            on a.id=b.menuid 
                where   b.userid='.$this->session->userdata('id').' and 
                        active=1 
                order by a.groupname,menuview ';
    }
    function index()
    {

        $logged_in= $this->session->userdata('logged_in');
        if(!isset($logged_in) || $logged_in != TRUE)
        {
            redirect('login/','refresh');
        }

        $this->view_karyawan();

        //echo "a";

    }

    private function template($view,$data)
    {
        $this->template->set_title('MPM SQUARE');
        $this->template->add_js('modules/skeleton.js');
        $this->template->add_css('modules/skeleton.css');
        $this->template->load_view($view, $data);
    }

    public function view_karyawan(){

      $this->load->library('form_validation');

      $logged_in= $this->session->userdata('logged_in');
      if(!isset($logged_in) || $logged_in != TRUE)
      {
          redirect('login/','refresh');
      }

      $id=$this->session->userdata('id');    

        $data['page_content'] = 'all_hrd/view_karyawan';                      
        $data['menu']=$this->db->query($this->querymenu);
        $data['query']=$this->model_hrd->view_karyawan();
        $this->template($data['page_content'],$data);        
    }

    public function view_cuti(){

      $logged_in= $this->session->userdata('logged_in');
      if(!isset($logged_in) || $logged_in != TRUE)
      {
          redirect('login/','refresh');
      }

      $id=$this->session->userdata('id');    

      $data['page_content'] = 'all_hrd/view_cuti';                      
      $data['menu']=$this->db->query($this->querymenu);
      $data['query']=$this->model_hrd->view_cuti();
      $this->template($data['page_content'],$data);        
    }

    public function input_karyawan(){

        $logged_in= $this->session->userdata('logged_in');
        if(!isset($logged_in) || $logged_in != TRUE)
        {
            redirect('login/','refresh');
        }
            $data['page_content'] = 'all_hrd/input_karyawan';
            $data['url'] = 'all_hrd/proses_input_karyawan/';
            $data['page_title'] = 'Tambah Karyawan';           
            $data['menu']=$this->db->query($this->querymenu);
            $data['query']=$this->model_hrd->get_divisi();
            $this->template($data['page_content'],$data);        
    }

    public function input_cuti(){

        $logged_in= $this->session->userdata('logged_in');
        if(!isset($logged_in) || $logged_in != TRUE)
        {
            redirect('login/','refresh');
        }
            $data['page_content'] = 'all_hrd/input_cuti';
            $data['url'] = 'all_hrd/proses_input_cuti/';
            $data['page_title'] = 'Tambah Cuti';           
            $data['menu']=$this->db->query($this->querymenu);
            $data['query']=$this->model_hrd->get_divisi();
            $data['query_kary']=$this->model_hrd->get_karyawan();
            $data['query_cuti']=$this->model_hrd->get_jenis_cuti();
            $this->template($data['page_content'],$data);        
    }

    public function input_divisi(){

        $logged_in= $this->session->userdata('logged_in');
        if(!isset($logged_in) || $logged_in != TRUE)
        {
            redirect('login/','refresh');
        }
            $data['page_content'] = 'all_hrd/input_divisi';
            $data['url'] = 'all_hrd/proses_input_divisi/';
            $data['page_title'] = 'Tambah Divisi';           
            $data['menu']=$this->db->query($this->querymenu);
            $data['query']=$this->model_hrd->view_divisi();
            $this->template($data['page_content'],$data);        
    }

    public function input_hak_cuti(){

        $logged_in= $this->session->userdata('logged_in');
        if(!isset($logged_in) || $logged_in != TRUE)
        {
            redirect('login/','refresh');
        }
            $data['page_content'] = 'all_hrd/input_hak';
            $data['url'] = 'all_hrd/proses_input_hak/';
            $data['page_title'] = 'Tambah Hak Cuti Karyawan';           
            $data['menu']=$this->db->query($this->querymenu);
            $data['query_kary']=$this->model_hrd->get_karyawan();
            $data['query_cuti']=$this->model_hrd->get_jenis_cuti();
            $data['query']=$this->model_hrd->view_hak();
            $this->template($data['page_content'],$data);        
    }

    public function input_jenis_cuti(){

        $logged_in= $this->session->userdata('logged_in');
        if(!isset($logged_in) || $logged_in != TRUE)
        {
            redirect('login/','refresh');
        }
            $data['page_content'] = 'all_hrd/input_jenis_cuti';
            $data['url'] = 'all_hrd/proses_input_jenis_cuti/';
            $data['page_title'] = 'Tambah Jenis Cuti';           
            $data['menu']=$this->db->query($this->querymenu);
            $data['query']=$this->model_hrd->view_jenis_cuti();
            $this->template($data['page_content'],$data);        
    }



    public function view_complain_user(){

      $this->load->library('form_validation');

      $logged_in= $this->session->userdata('logged_in');
      if(!isset($logged_in) || $logged_in != TRUE)
      {
          redirect('login/','refresh');
      }

        $data['page_content'] = 'help/view_help_user';                      
        $data['menu']=$this->db->query($this->querymenu);
        $data['helps']=$this->model_help->view_complain_user();
        $this->template($data['page_content'],$data);        
    }

    public function input_complain(){

        $logged_in= $this->session->userdata('logged_in');
        if(!isset($logged_in) || $logged_in != TRUE)
        {
            redirect('login/','refresh');
        }
            $data['page_content'] = 'help/view_input_complain';
            $data['url'] = 'all_help/proses_input_complain/';
            $data['page_title'] = 'Input Complain';           
            $data['menu']=$this->db->query($this->querymenu);
            $data['query']=$this->model_help->getGrupassetcombo();
            $this->template($data['page_content'],$data);        
    }

    public function proses_input_karyawan(){

        $logged_in= $this->session->userdata('logged_in');
        if(!isset($logged_in) || $logged_in != TRUE)
        {
            redirect('login/','refresh');
        }

        date_default_timezone_set('Asia/Jakarta');

        $this->load->library('form_validation');

        $this->form_validation->set_rules('nik', '"nik"', 'required|min_length[5]');
        $this->form_validation->set_rules('nama', '"nama"', 'required|min_length[5]');
        $this->form_validation->set_rules('divisi', '"Divisi"', 'required');

        if($this->form_validation->run() === FALSE)
        {

            $data['page_content'] = 'all_hrd/input_karyawan';
            $data['url'] = 'all_hrd/proses_input_karyawan/';
            $data['page_title'] = 'Tambah Karyawan';           
            $data['menu']=$this->db->query($this->querymenu);
            $data['query']=$this->model_hrd->get_divisi();
            $this->template($data['page_content'],$data);  

        }else{

            //echo "berhasil proses<br>";

            $config['upload_path'] = './uploads/foto/';
            $config['allowed_types'] = 'jpg|png|JPG';
            $config['max_size'] = '5000';
            $config['max_width']  = '1024';
            $config['max_height']  = '768';

            $this->load->library('upload', $config);

            //cek ada attachment atau tdk
      
            if (!$this->upload->do_upload())
            {
                //echo "jika gagal upload, tetap insert data";
                
                $error = array('error' => $this->upload->display_errors());
                echo "<br>";
                print_r($error);
                
                $gambar = $this->upload->data();

                $data = array(
                    'nik'       => $this->input->post('nik'),
                    'nama'      => $this->input->post('nama'),
                    'tgl_lahir' => $this->input->post('tgl_lahir'),
                    'alamat'    => $this->input->post('alamat'),
                    'email'     => $this->input->post('email'),
                    'kontak'    => $this->input->post('kontak'),
                    'divisi'    => $this->input->post('divisi'),
                    'jabatan'    => $this->input->post('jabatan'),
                    'tgl_gabung'    => $this->input->post('tgl_gabung'),
                    'status'    => $this->input->post('status'),
                    'tgl_resign'    => $this->input->post('tgl_resign')
                );
                /*
                echo "nik    : ".$data['nik']."<br>";
                echo "nama   : ".$data['nama']."<br>";
                echo "tgl_lahir  : ".$data['tgl_lahir']."<br>";
                echo "alamat    : ".$data['alamat']."<br>";
                echo "email : ".$data['email']."<br>";
                echo "kontak : ".$data['kontak']."<br>";
                echo "divisi : ".$data['divisi']."<br>";
                echo "jabatan : ".$data['jabatan']."<br>";
                echo "tgl_gabung : ".$data['tgl_gabung']."<br>";
                echo "status : ".$data['status']."<br>";
                echo "tgl_resign : ".$data['tgl_resign']."<br>";
                */
                $data['query']=$this->model_hrd->proses_input_karyawan($data);
                
            }else{

                echo "jika berhasil upload";
                
                $gambar = $this->upload->data();  

                $data = array(
                    'nik'       => $this->input->post('nik'),
                    'nama'      => $this->input->post('nama'),
                    'tgl_lahir' => $this->input->post('tgl_lahir'),
                    'alamat'    => $this->input->post('alamat'),
                    'email'     => $this->input->post('email'),
                    'kontak'    => $this->input->post('kontak'),
                    'divisi'    => $this->input->post('divisi'),
                    'jabatan'   => $this->input->post('jabatan'),
                    'tgl_gabung'=> $this->input->post('tgl_gabung'),
                    'status'    => $this->input->post('status'),
                    'tgl_resign'=> $this->input->post('tgl_resign'),
                    'foto'      => $gambar['file_name']
                );
                
                $data['query']=$this->model_hrd->proses_input_karyawan($data);
            }
        }
    }

    public function proses_edit_karyawan(){

        $logged_in= $this->session->userdata('logged_in');
        if(!isset($logged_in) || $logged_in != TRUE)
        {
            redirect('login/','refresh');
        }

        date_default_timezone_set('Asia/Jakarta');

        $this->load->library('form_validation');

        $this->form_validation->set_rules('nik', '"nik"', 'required|min_length[5]');
        $this->form_validation->set_rules('nama', '"nama"', 'required|min_length[5]');
        $this->form_validation->set_rules('divisi', '"Divisi"', 'required');

        if($this->form_validation->run() === FALSE)
        {

            $data['page_content'] = 'all_hrd/input_karyawan';
            $data['url'] = 'all_hrd/proses_input_karyawan/';
            $data['page_title'] = 'Tambah Karyawan';           
            $data['menu']=$this->db->query($this->querymenu);
            $data['query']=$this->model_hrd->get_divisi();
            $this->template($data['page_content'],$data);  

        }else{

            echo "berhasil proses<br>";

            $config['upload_path'] = './uploads/foto/';
            $config['allowed_types'] = 'jpg|png|JPG';
            $config['max_size'] = '5000';
            $config['max_width']  = '1024';
            $config['max_height']  = '768';

            $this->load->library('upload', $config);

            //cek ada attachment atau tdk
      
            if (!$this->upload->do_upload())
            {
                //echo "jika gagal upload, tetap insert data";
                
                $error = array('error' => $this->upload->display_errors());
                echo "<br>";
                print_r($error);
                
                $gambar = $this->upload->data();

                $data = array(
                    'nik'       => $this->input->post('nik'),
                    'nama'      => $this->input->post('nama'),
                    'tgl_lahir' => $this->input->post('tgl_lahir'),
                    'alamat'    => $this->input->post('alamat'),
                    'email'     => $this->input->post('email'),
                    'kontak'    => $this->input->post('kontak'),
                    'divisi'    => $this->input->post('divisi'),
                    'jabatan'    => $this->input->post('jabatan'),
                    'tgl_gabung'    => $this->input->post('tgl_gabung'),
                    'status'    => $this->input->post('id_status'),
                    'tgl_resign'    => $this->input->post('tgl_resign')
                );
                
                echo "nik    : ".$data['nik']."<br>";
                echo "nama   : ".$data['nama']."<br>";
                echo "tgl_lahir  : ".$data['tgl_lahir']."<br>";
                echo "alamat    : ".$data['alamat']."<br>";
                echo "email : ".$data['email']."<br>";
                echo "kontak : ".$data['kontak']."<br>";
                echo "divisi : ".$data['divisi']."<br>";
                echo "jabatan : ".$data['jabatan']."<br>";
                echo "tgl_gabung : ".$data['tgl_gabung']."<br>";
                echo "status : ".$data['status']."<br>";
                echo "tgl_resign : ".$data['tgl_resign']."<br>";
                
                $data['query']=$this->model_hrd->proses_edit_karyawan($data);
                
            }else{

                echo "jika berhasil upload";
                
                $gambar = $this->upload->data();  

                $data = array(
                    'nik'       => $this->input->post('nik'),
                    'nama'      => $this->input->post('nama'),
                    'tgl_lahir' => $this->input->post('tgl_lahir'),
                    'alamat'    => $this->input->post('alamat'),
                    'email'     => $this->input->post('email'),
                    'kontak'    => $this->input->post('kontak'),
                    'divisi'    => $this->input->post('divisi'),
                    'jabatan'   => $this->input->post('jabatan'),
                    'tgl_gabung'=> $this->input->post('tgl_gabung'),
                    'status'    => $this->input->post('status'),
                    'tgl_resign'=> $this->input->post('tgl_resign'),
                    'foto'      => $gambar['file_name']
                );
                
                //$data['query']=$this->model_hrd->proses_input_karyawan($data);
            }
        }
    }

    public function proses_input_divisi(){

        $logged_in= $this->session->userdata('logged_in');
        if(!isset($logged_in) || $logged_in != TRUE)
        {
            redirect('login/','refresh');
        }

        date_default_timezone_set('Asia/Jakarta');

        $this->load->library('form_validation');

        $this->form_validation->set_rules('kode_divisi', '"Kode Divisi"', 'required|max_length[2]');
        $this->form_validation->set_rules('nama_divisi', '"Nama Divisi"', 'required|min_length[2]');

        if($this->form_validation->run() === FALSE)
        {

            $data['page_content'] = 'all_hrd/input_divisi';
            $data['url'] = 'all_hrd/proses_input_divisi/';
            $data['page_title'] = 'Tambah Divisi';           
            $data['menu']=$this->db->query($this->querymenu);
            $data['query']=$this->model_hrd->view_divisi();
            $this->template($data['page_content'],$data);  

        }else{

          $data = array(
              'kode_divisi'      => $this->input->post('kode_divisi'),
              'nama_divisi'      => $this->input->post('nama_divisi')              
          );
          
          $data['query']=$this->model_hrd->proses_input_divisi($data);
  
          }
  
    }

    public function proses_input_cuti(){

        $logged_in= $this->session->userdata('logged_in');
        if(!isset($logged_in) || $logged_in != TRUE)
        {
            redirect('login/','refresh');
        }

        date_default_timezone_set('Asia/Jakarta');

        $this->load->library('form_validation');

        $this->form_validation->set_rules('nik', '"Karyawan"', 'required|min_length[2]');
        $this->form_validation->set_rules('bulan', '"Bulan"', 'required|max_length[2]');
        $this->form_validation->set_rules('jenis_cuti', '"Jenis Cuti"', 'required|max_length[2]');
        $this->form_validation->set_rules('tgl_cuti', '"Tanggal Cuti"', 'required');
        $this->form_validation->set_rules('status', '"Potong Cuti Tahunan"', 'required|max_length[2]');
        $this->form_validation->set_rules('keterangan', '"keterangan"', 'required|min_length[2]');

        if($this->form_validation->run() === FALSE)
        {

            $data['page_content'] = 'all_hrd/input_cuti';
            $data['url'] = 'all_hrd/proses_input_cuti/';
            $data['page_title'] = 'Tambah Cuti';           
            $data['menu']=$this->db->query($this->querymenu);
            $data['query']=$this->model_hrd->get_divisi();
            $data['query_kary']=$this->model_hrd->get_karyawan();
            $data['query_cuti']=$this->model_hrd->get_jenis_cuti();
            $this->template($data['page_content'],$data);    

        }else{

          $data = array(
              'nik'        => $this->input->post('nik'),
              'bulan'      => $this->input->post('bulan'),
              'jenis_cuti' => $this->input->post('jenis_cuti'),
              'tgl_cuti'   => $this->input->post('tgl_cuti'),   
              'status'    => $this->input->post('status'),
              'keterangan' => $this->input->post('keterangan'),

          );


                echo "nik    : ".$data['nik']."<br>";
                echo "bulan   : ".$data['bulan']."<br>";
                echo "jenis_cuti  : ".$data['jenis_cuti']."<br>";
                echo "tgl_cuti    : ".$data['tgl_cuti']."<br>";
                echo "status : ".$data['status']."<br>";
                echo "keterangan   : ".$data['keterangan']."<br>";


          
          $data['query']=$this->model_hrd->proses_input_cuti($data);
  
          }
    }

    public function proses_input_hak(){

        $logged_in= $this->session->userdata('logged_in');
        if(!isset($logged_in) || $logged_in != TRUE)
        {
            redirect('login/','refresh');
        }

        date_default_timezone_set('Asia/Jakarta');

        $this->load->library('form_validation');

        $this->form_validation->set_rules('nik', '"Nama Karyawan"', 'required|min_length[2]');
        $this->form_validation->set_rules('tahun', '"tahun"', 'required|min_length[2]');
        $this->form_validation->set_rules('jenis_cuti', '"jenis_cuti"', 'required|max_length[2]');
        $this->form_validation->set_rules('hak_cuti', '"jumlah hak cuti"', 'required');
        
        if($this->form_validation->run() === FALSE)
        {

            $data['page_content'] = 'all_hrd/input_divisi';
            $data['url'] = 'all_hrd/proses_input_divisi/';
            $data['page_title'] = 'Tambah Divisi';           
            $data['menu']=$this->db->query($this->querymenu);
            $data['query']=$this->model_hrd->view_divisi();
            $this->template($data['page_content'],$data);  

        }else{

            $data = array(
                'nik'      => $this->input->post('nik'),
                'tahun'      => $this->input->post('tahun'),
                'jenis_cuti'      => $this->input->post('jenis_cuti'),
                'hak_cuti'      => $this->input->post('hak_cuti')               
            );
          
          $data['query']=$this->model_hrd->proses_input_hak($data);
  
          }
  
    }

    public function detail_kary(){

      $this->load->library('form_validation');

      $logged_in= $this->session->userdata('logged_in');
      if(!isset($logged_in) || $logged_in != TRUE)
      {
          redirect('login/','refresh');
      }
          $data['page_content'] = 'all_hrd/detail_kary';   
          $data['page_title'] = 'Data Detail Karyawan';                   
          $data['menu']=$this->db->query($this->querymenu);
          $data['query']=$this->model_hrd->detail_kary();
          $this->template($data['page_content'],$data);        
    }

    public function edit_kary(){

      $this->load->library('form_validation');
      $data['id_kary'] = $this->uri->segment('3');
      $logged_in= $this->session->userdata('logged_in');
      if(!isset($logged_in) || $logged_in != TRUE)
      {
          redirect('login/','refresh');
      }
          $data['page_content'] = 'all_hrd/edit_kary';   
          $data['page_title'] = 'Data Edit Karyawan';                   
          $data['menu']=$this->db->query($this->querymenu);
          $data['query']=$this->model_hrd->detail_kary();
          $data['query_div']=$this->model_hrd->get_divisi_edit($data);
          $data['query_sts']=$this->model_hrd->get_status_edit($data);
          $this->template($data['page_content'],$data);        
    }

    public function detail_complain(){

      $this->load->library('form_validation');

      $logged_in= $this->session->userdata('logged_in');
      if(!isset($logged_in) || $logged_in != TRUE)
      {
          redirect('login/','refresh');
      }
          $data['page_content'] = 'help/detail_complain';                      
          $data['menu']=$this->db->query($this->querymenu);
          $data['helps']=$this->model_help->detail_complain();
          $this->template($data['page_content'],$data);        
    }

    public function detail_complain_user(){

      $this->load->library('form_validation');

      $logged_in= $this->session->userdata('logged_in');
      if(!isset($logged_in) || $logged_in != TRUE)
      {
          redirect('login/','refresh');
      }
          $data['page_content'] = 'help/detail_complain_user';                      
          $data['menu']=$this->db->query($this->querymenu);
          $data['helps']=$this->model_help->detail_complain();
          $this->template($data['page_content'],$data);        
    }

    public function edit_complain(){

        $this->load->library('form_validation');
        $logged_in= $this->session->userdata('logged_in');
        if(!isset($logged_in) || $logged_in != TRUE)
        {
            redirect('login/','refresh');
        }

        $segment = $this->uri->segment(3);

        $data['page_content'] = 'help/edit_complain';
        $data['url'] = 'all_help/edit_complain/';
        $data['page_title'] = 'Edit Complain';           
        $data['menu']=$this->db->query($this->querymenu);
        $data['helps']=$this->model_help->detail_complain($data);
        $data['query']=$this->model_help->getGrupassetcombo();
        $data['flag']=$this->model_help->getFlag();
        $this->template($data['page_content'],$data);

    }

    public function proses_update_complain($id){
        $logged_in= $this->session->userdata('logged_in');
        if(!isset($logged_in) || $logged_in != TRUE)
        {
            redirect('login/','refresh');
        }

        $segment = $this->uri->segment(3);
        $id=$this->session->userdata('id');
        //echo "<br><br>id : ".$id; 

        $this->form_validation->set_rules('nama_it', '"Nama IT"', 'required');
        $this->form_validation->set_rules('id_status', 'status masalah', 'required');
        $this->form_validation->set_rules('tgl_selesai', 'tgl_selesai', 'required');
        $this->form_validation->set_rules('solusi', '"solusi"', 'required|min_length[5]');
        $this->form_validation->set_rules('note_tambahan', '"note tambahan"', 'required|min_length[0]');
        
        if($this->form_validation->run() === FALSE)
        {
            
            $data['page_content'] = 'help/edit_complain';
            $data['url'] = 'all_help/edit_complain/';
            $data['page_title'] = 'Edit Complain';           
            $data['menu']=$this->db->query($this->querymenu);
            $data['helps']=$this->model_help->detail_complain($data);
            $data['flag']=$this->model_help->getFlag();
            $data['query']=$this->model_help->getGrupassetcombo();
            $this->template($data['page_content'],$data);
            
        }else{

            //echo "berhasil proses<br>";

            $config['upload_path'] = './uploads/it';
            $config['allowed_types'] = 'jpg|png|doc|docx';
            $config['max_size'] = '10000';
            $config['max_width']  = '1024';
            $config['max_height']  = '768';

            $this->load->library('upload', $config);

            //cek ada attachment atau tdk
      
            if ( ! $this->upload->do_upload())
            {
                //echo "jika tidak ada file upload, tetap insert datanya<br>";
                
                /* cek kolom file_it*/
              
                $this->db->where('id = '.'"'.$segment.'"');
                $query = $this->db->get('mpm.tbl_complain_system');
                foreach ($query->result() as $row) {
                    $file_it = $row->file_it;
                    //echo "file_it : ".$file_it."<br />";
                }
                if ($file_it == NULL || $file_it == "" || $file_it == "NULL") {
                    $file_it_isi = "tidak ada";
                } else {
                    $file_it_isi = $file_it;
                    //echo "file it isi : ".$file_it_isi."<br>";
                }
                      
        
                /* end cek kolom file_it*/

                $gambar = $this->upload->data();        
                $data = array(
                    'id'          => $segment,
                    'nama_it'     => set_value('nama_it'),
                    'id_status'   => set_value('id_status'),
                    'tgl_selesai' => set_value('tgl_selesai'),
                    'solusi'      => set_value('solusi'),
                    'image_it'    => $file_it_isi,
                    'note_tambahan' => set_value('note_tambahan')
                );           

            /*
            echo "segment    : ".$data['id']."<br>";
            echo "nama_it    : ".$data['nama_it']."<br>";
            echo "id_status   : ".$data['id_status']."<br>";
            echo "tgl_selesai  : ".$data['tgl_selesai']."<br>";
            echo "solusi    : ".$data['solusi']."<br>";
            echo "image it  : ".$data['image_it']."<br>";
            echo "note_tambahan  : ".$data['note_tambahan']."<br><hr>";
            */
            $data['helps']=$this->model_help->proses_update($data);

                
          }
          else{

            //echo "ada attachment";

            $gambar = $this->upload->data();

            $data = array(
                    'id'          => $segment,
                    'nama_it'     => set_value('nama_it'),
                    'id_status'   => set_value('id_status'),
                    'tgl_selesai' => set_value('tgl_selesai'),
                    'solusi'      => set_value('solusi'),
                    'image_it'    => $gambar['file_name'],
                    'note_tambahan' => set_value('note_tambahan')

                );           

            
            echo "segment    : ".$data['id']."<br>";
            echo "nama_it    : ".$data['nama_it']."<br>";
            echo "id_status   : ".$data['id_status']."<br>";
            echo "tgl_selesai  : ".$data['tgl_selesai']."<br>";
            echo "solusi    : ".$data['solusi']."<br>";
            echo "image it  : ".$data['image_it']."<br>";
            echo "note_tambahan : ".$data['note_tambahan']."<br><hr>";
            
            $data['helps']=$this->model_help->proses_update($data);
          }
        }
    }

    public function delete_complain($id){

      $hasil = $this->model_help->proses_delete($id);
      redirect('all_help/view_complain','refresh');

    }

    private function email_config()
    {
         $config['protocol']  = 'smtp';
         $config['smtp_host'] = 'ssl://smtp.gmail.com';
         $config['smtp_port'] = '465';
         $config['smtp_timeout'] = '3000';
         $config['smtp_user'] = 'suffy.yanuar@gmail.com';
         $config['smtp_pass'] = 'yanuar123!@#';
         $config['charset']  = 'utf-8';
         $config['newline']  = "\r\n";
         $config['use_ci_email'] = TRUE;

         $this->email->initialize($config);
    }

    public function email(){
        
        $htmlContent = '<h1>Mengirim email disertai lampiran (attachement) dengan Codeigniter</h1>';
        $htmlContent .= '<div>Contoh pengiriman email yang disertai dengan file lampiran dengan menggunakan Codeigniter</div>';

        $this->email_config();

        //$config['mailtype'] = 'html';
        //$this->email->initialize($config);
        $this->email->to('suffy.yanuar@gmail.com');
        $this->email->from('suffy.yanuar@gmail.com','Automatic Email - MPM Site');
        $this->email->subject('Test Email (Attachment)');
        $this->email->message($htmlContent);
        //$this->email->attach('LOKASI_FOLDER_FILE/NAMA_FILE_attachment.pdf');
        $this->email->send();
        //echo $this->email->print_debugger();
    }

   
}
?>
