<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class All_help extends MY_Controller
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

    function all_help()
    {
        set_time_limit(0);
        $this->load->library(array('table','template','Excel_generator', 'form_validation','email'));
        $this->load->helper('url');
        $this->load->model('model_help');
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

        $this->view_complain();

        //echo "a";

    }

    private function template($view,$data)
    {
        $this->template->set_title('MPM SQUARE');
        $this->template->add_js('modules/skeleton.js');
        $this->template->add_css('modules/skeleton.css');
        $this->template->load_view($view, $data);
    }

    public function view_complain(){

      $this->load->library('form_validation');

      $logged_in= $this->session->userdata('logged_in');
      if(!isset($logged_in) || $logged_in != TRUE)
      {
          redirect('login/','refresh');
      }

      $id=$this->session->userdata('id');

      if ($id == '11' || $id == '289' || $id == '297') {
        
      }else{
          redirect('login/','refresh');    
        
      }
      

        $data['page_content'] = 'help/view_help';                      
        $data['menu']=$this->db->query($this->querymenu);
        $data['helps']=$this->model_help->view_complain();
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

    public function proses_input_complain(){

        $logged_in= $this->session->userdata('logged_in');
        if(!isset($logged_in) || $logged_in != TRUE)
        {
            redirect('login/','refresh');
        }

        $this->load->library('form_validation');

        $this->form_validation->set_rules('user', '"User Pelapor"', 'required');
        $this->form_validation->set_rules('email', 'Email', 'required|valid_email');
        $this->form_validation->set_rules('kontak', '"No kontak"', 'required|min_length[5]'); 
        $this->form_validation->set_rules('grup', '"Kategori Permasalahan"', 'required');  
        $this->form_validation->set_rules('masalah', '"Detail Permasalahan"', 'required|min_length[15]');
        //$this->form_validation->set_rules('userfile', '"Attachment"', 'required');

        if($this->form_validation->run() === FALSE)
        {

            //echo "ada kesalahan";           
            
            $data['page_content'] = 'help/view_input_complain';
            $data['url'] = 'all_help/proses_input_complain/';
            $data['page_title'] = 'Input Complain';           
            $data['menu']=$this->db->query($this->querymenu);
            $data['query']=$this->model_help->getGrupassetcombo();
            $this->template($data['page_content'],$data);  

        }else{

            //echo "berhasil proses<br>";

            $config['upload_path'] = './uploads/';
            $config['allowed_types'] = 'jpg|png| ';
            $config['max_size'] = '100';
            $config['max_width']  = '1024';
            $config['max_height']  = '768';

            $this->load->library('upload', $config);

            //cek ada attachment atau tdk
      
            if ( ! $this->upload->do_upload())
            {
                echo "jika gagal upload, tetap insert data";
                
                /*
                $data['page_content'] = 'help/view_input_complain';
                $data['url'] = 'all_help/proses_input_complain/';
                $data['page_title'] = 'Input Complain';           
                $data['menu']=$this->db->query($this->querymenu);
                $data['query']=$this->model_help->getGrupassetcombo();
                $this->template($data['page_content'],$data);
                */

                $gambar = $this->upload->data();

                $data = array(
                    'user'       => set_value('user'),
                    'email'      => set_value('email'),
                    'kontak'     => set_value('kontak'),
                    'grup'       => $this->input->post('grup'),
                    'masalah'    => set_value('masalah'),
                    'image'      => "tidak ada"
                );
                /*
                echo "user    : ".$data['user']."<br>";
                echo "email   : ".$data['email']."<br>";
                echo "kontak  : ".$data['kontak']."<br>";
                echo "grup    : ".$data['grup']."<br>";
                echo "masalah : ".$data['masalah']."<br>";
                */
                //echo "image   : ".$data['image']."<br>";
                
                $data['reports']=$this->model_help->proses_input_complain($data);
  
            }else{
                echo "jika berhasil upload";
                
                $gambar = $this->upload->data();

                $data = array(
                    'user'       => set_value('user'),
                    'email'      => set_value('email'),
                    'kontak'     => set_value('kontak'),
                    'grup'       => $this->input->post('grup'),
                    'masalah'    => set_value('masalah'),
                    'image'      => $gambar['file_name']
                );
                /*
                echo "user    : ".$data['user']."<br>";
                echo "email   : ".$data['email']."<br>";
                echo "kontak  : ".$data['kontak']."<br>";
                echo "grup    : ".$data['grup']."<br>";
                echo "masalah : ".$data['masalah']."<br>";
                echo "image   : ".$data['image']."<br>";
                */
                $data['reports']=$this->model_help->proses_input_complain($data);
            }
        }

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
        $data['url'] = 'all_help/proses_input_complain/';
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
        
        if($this->form_validation->run() === FALSE)
        {

            
            $data['page_content'] = 'help/edit_complain';
            $data['url'] = 'all_help/proses_input_complain/';
            $data['page_title'] = 'Edit Complain';           
            $data['menu']=$this->db->query($this->querymenu);
            $data['helps']=$this->model_help->detail_complain($data);
            $data['flag']=$this->model_help->getFlag();
            $data['query']=$this->model_help->getGrupassetcombo();
            $this->template($data['page_content'],$data);
            
        }else{
                        
            $data = array(
                'id'          => $segment,
                'nama_it'     => set_value('nama_it'),
                'id_status'=> set_value('id_status'),
                'tgl_selesai' => set_value('tgl_selesai'),
                'solusi'      => set_value('solusi')
            );           

            
            echo "segment    : ".$data['id']."<br>";
            echo "nama_it    : ".$data['nama_it']."<br>";
            echo "id_status   : ".$data['id_status']."<br>";
            echo "tgl_selesai  : ".$data['tgl_selesai']."<br>";
            echo "solusi    : ".$data['solusi']."<br><hr>";
            
            $data['helps']=$this->model_help->proses_update($data);
            
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
