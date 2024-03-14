<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class All_assets extends MY_Controller
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

    function all_assets()
    {
        set_time_limit(0);
        $this->load->library(array('table','template','Excel_generator', 'form_validation'));
        $this->load->helper('url');
        $this->load->model('model_assets');
        $this->load->database();
        $this->querymenu='select a.id,a.menuview,a.target,a.groupname from mpm.menu a inner join mpm.menudetail b on a.id=b.menuid where b.userid='. $this->session->userdata('id') . ' and active=1 order by a.groupname,a.menuview ';
    }
    function index()
    {

        $logged_in= $this->session->userdata('logged_in');
        if(!isset($logged_in) || $logged_in != TRUE)
        {
            redirect('login/','refresh');
        }

        $this->view_assets();


    }

    private function template($view,$data)
    {
        $this->template->set_title('MPM SQUARE');
        $this->template->add_js('modules/skeleton.js');
        $this->template->add_css('modules/skeleton.css');
        $this->template->load_view($view, $data);
    }

    public function input_assets(){

        $logged_in= $this->session->userdata('logged_in');
        if(!isset($logged_in) || $logged_in != TRUE)
        {
            redirect('login/','refresh');
        }
            $data['page_content'] = 'asset/view_input_assets';
            $data['url'] = 'all_assets/proses_input_assets/';
            $data['page_title'] = 'Input Asset';           
            $data['menu']=$this->db->query($this->querymenu);
            $data['query']=$this->model_assets->getGrupassetcombo();
            $this->template($data['page_content'],$data);        
    }

    public function proses_input_assets(){

        $logged_in= $this->session->userdata('logged_in');
        if(!isset($logged_in) || $logged_in != TRUE)
        {
            redirect('login/','refresh');
        }

        $this->load->library('form_validation');

        $this->form_validation->set_rules('kode', '"Nomor Voucher"', 'required');
        $this->form_validation->set_rules('namabarang', '"nama barang"', 'required');
        $this->form_validation->set_rules('sn', '"S/N"', 'required');
        $this->form_validation->set_rules('jumlah', '"jumlah barang"', 'required|numeric');
        $this->form_validation->set_rules('untuk', '"untuk"', 'required');
        $this->form_validation->set_rules('tglperol', '"tanggal payroll"', 'required');
        $this->form_validation->set_rules('np', '"nilai perolehan"', 'required');
        //$this->form_validation->set_rules('nj', '"nilai jual"', 'required');
        //$this->form_validation->set_rules('tgljual', '"tanggal jual"', 'required');
        //$this->form_validation->set_rules('deskripsi', '"deskripsi"', 'required');   

        if($this->form_validation->run() === FALSE)
        {

          //echo "ada kesalahan";

            $data['page_content'] = 'asset/view_input_assets';
            $data['url'] = 'all_assets/proses_input_assets/';
            $data['page_title'] = 'Input Asset';           
            $data['menu']=$this->db->query($this->querymenu);
            $data['query']=$this->model_assets->getGrupassetcombo();
            $this->template($data['page_content'],$data); 

            
        }else{
            
            $data = array(
                'kode'       => set_value('kode'),
                'namabarang' => set_value('namabarang'),
                'sn'         => set_value('sn'),
                'jumlah'     => set_value('jumlah'),
                'untuk'      => set_value('untuk'),
                'tglperol'   => set_value('tglperol'),
                'gol'        => $this->input->post('gol'),
                'grup'       => $this->input->post('grup'),
                'np'         => set_value('np'),
                'nj'         => set_value('nj'),
                'tgljual'    => set_value('tgljual'),
                'deskripsi'     => set_value('deskripsi')
            );
            

            $data['reports']=$this->model_assets->proses_input_assets($data);

        }

    }

    public function view_assets(){

      $this->load->library('form_validation');

      $logged_in= $this->session->userdata('logged_in');
      if(!isset($logged_in) || $logged_in != TRUE)
      {
          redirect('login/','refresh');
      }

        $data['page_content'] = 'asset/view_assets';                      
        $data['menu']=$this->db->query($this->querymenu);
        $data['assets']=$this->model_assets->view_assets();
        $this->template($data['page_content'],$data);        
    }

    public function detail_assets(){

      $this->load->library('form_validation');

      $logged_in= $this->session->userdata('logged_in');
      if(!isset($logged_in) || $logged_in != TRUE)
      {
          redirect('login/','refresh');
      }
          $data['page_content'] = 'asset/detail_assets';                      
          $data['menu']=$this->db->query($this->querymenu);
          $data['assets']=$this->model_assets->detail_assets();
          $this->template($data['page_content'],$data);        
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
        
        $data = "kode asset : $kode\nnama asset : $namabarang\nPIC : $untuk\nS/N : $sn\nLihat Detail : ".base_url()."all_assets/detail_assets/".$id."";


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

    public function edit_assets(){

      $this->load->library('form_validation');

      $logged_in= $this->session->userdata('logged_in');
      if(!isset($logged_in) || $logged_in != TRUE)
      {
          redirect('login/','refresh');
      }

          $segment = $this->uri->segment(3);
          
          $data['segments'] = $segment;
          $data['page_content'] = 'asset/edit_assets';                      
          $data['menu']=$this->db->query($this->querymenu);
          $data['assets']=$this->model_assets->detail_assets($data);
          $data['query']=$this->model_assets->getGrupassetcombo();
          $this->template($data['page_content'],$data);        
    }

    public function proses_update_assets($id){
        $logged_in= $this->session->userdata('logged_in');
        if(!isset($logged_in) || $logged_in != TRUE)
        {
            redirect('login/','refresh');
        }

        $id=$this->session->userdata('id');
        //echo "<br><br>id : ".$id; 

        $this->form_validation->set_rules('kode', '"Nomor Voucher"', 'required');
        $this->form_validation->set_rules('namabarang', '"nama barang"', 'required');
        $this->form_validation->set_rules('sn', '"S/N"', 'required');
        $this->form_validation->set_rules('jumlah', '"jumlah barang"', 'required|numeric');
        $this->form_validation->set_rules('untuk', '"untuk"', 'required');
        $this->form_validation->set_rules('tglperol', '"tanggal payroll"', 'required');
        $this->form_validation->set_rules('np', '"nilai perolehan"', 'required');
        $this->form_validation->set_rules('nj', '"nilai jual"', 'required');
        $this->form_validation->set_rules('tgljual', '"tanggal jual"', 'required');
        $this->form_validation->set_rules('deskripsi', '"deskripsi"', 'required');   
        
        if($this->form_validation->run() === FALSE)
        {

          
            $segment = $this->uri->segment('3');
            $data['page_content'] = 'asset/edit_assets';
            $data['segments'] = $segment;
            $data['url'] = 'all_report/detail_po/';
            $data['page_title'] = 'Detail PO';            
            $data['menu']=$this->db->query($this->querymenu);
            $data['query']=$this->model_assets->getGrupassetcombo();

            $data['assets']=$this->model_assets->detail_assets($data);

            $this->template($data['page_content'],$data);


            //$this->load->view('report/view_detail_po');
            //echo "ada kesalahan";
            //redirect('all_report/list_po','refresh');
            
        }else{
            
            $data = array(
                'kode'        => set_value('kode'),
                'namabarang'  => set_value('namabarang'),
                'sn'          => set_value('sn'),
                'jumlah'      => set_value('jumlah'),
                'untuk'       => set_value('untuk'),
                'tglperol'    => set_value('tglperol'),
                'np'          => set_value('np'),
                'nj'          => set_value('nj'),
                'tgljual'     => set_value('tgljual'),
                'deskripsi'   => set_value('deskripsi'),
                'id'          => $this->uri->segment('3'),
                'gol'         => $this->input->post('gol'),
                'grup'        => $this->input->post('grup')
            );
            //$dob1=trim($dataSegment['tglperol']);//$dob1='dd/mm/yyyy' format
            //$dob_disp1=strftime('%Y-%m-%d',strtotime($dob1));

            //echo "tgl perol controller : ".set_value('tglperol')."<br>";

            $data['assets']=$this->model_assets->proses_update($data);

        }
    }

    public function delete_asset($id){

      $this->model_assets->proses_delete($id);
      redirect('all_assets/view_assets','refresh');

    }
   
}
?>
