<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Product extends MY_Controller
{
    var $image_properties = array(
          'src' => 'assets/css/images/printer.png',
          'alt' => 'Printing Document',
          'class' => 'post_images',
          'width' => '50',
          'height' => '50',
          'title' => 'Printer',
          'border' => 0,
    );
    var $querymenu='';
    function Product()
    {
       
        $this->load->library(array('table','template'));
        $this->load->helper('url');
        $this->load->model('product_model','pmodel');
        $this->querymenu = 'select a.id,a.menuview,a.target,a.groupname from mpm.menu a inner join mpm.menudetail b on a.id=b.menuid where b.userid='.$this->session->userdata('id').' and active=1 order by a.groupname,menuview';
    }
    private function template($view,$data)
    {
        $this->template->set_title('MPM SQUARE');
        $this->template->add_js('modules/skeleton.js');
        $this->template->add_css('modules/skeleton.css');
        $this->template->load_view($view, $data);
    }
    public function print_product()
    {
        $this->pmodel->print_product();
    }
    public function print_supp()
    {
        $this->pmodel->print_supp();
    }
    public function activer_product($flag=null,$id=null,$offset=null)
    {
        $logged_in= $this->session->userdata('logged_in');
        if(!isset($logged_in) || $logged_in != TRUE)
        {
            redirect('login/','refresh');
        }
        $this->pmodel->activer_product($flag,$id,$offset);
    }
    public function activer_produksi($flag=null,$id=null,$offset=null)
    {
        $logged_in= $this->session->userdata('logged_in');
        if(!isset($logged_in) || $logged_in != TRUE)
        {
            redirect('login/','refresh');
        }
        $this->pmodel->activer_produksi($flag,$id,$offset);
    }
    public function activer_report($flag=null,$id=null,$offset=null)
    {
        $logged_in= $this->session->userdata('logged_in');
        if(!isset($logged_in) || $logged_in != TRUE)
        {
            redirect('login/','refresh');
        }
        $this->pmodel->activer_report($flag,$id,$offset);
    }
      public function activer_supp($flag=null,$id=null)
    {
        $logged_in= $this->session->userdata('logged_in');
        if(!isset($logged_in) || $logged_in != TRUE)
        {
            redirect('login/','refresh');
        }
        $this->pmodel->activer_supp($flag,$id);
    }
    public function kodeprod_check($str)
    {
        if ($this->pmodel->check('tabprod','kodeprod',$str))
    {
            return TRUE;
    }
    else
    {
            $this->valid->set_message('kodeprod_check', 'The %s has been used');
            return FALSE;
    }
    }
    public function supp_check($str)
    {
        if ($this->pmodel->check('tabsupp','supp',$str))
    {
            return TRUE;
    }
    else
    {
            $this->valid->set_message('supp_check', 'The %s has been used');
            return FALSE;
    }
    }
    function plus_product($kode=null)
    {
        $logged_in= $this->session->userdata('logged_in');
        if(!isset($logged_in) || $logged_in != TRUE)
        {
            redirect('login/','refresh');
        }
        $data['page_content'] = 'product/plus_product';
        $data['uri'] = site_url('product/save_harga/'.$kode);
        $data['page_title']='Tambah Harga';
        $data['menu']=$this->db->query($this->querymenu);
        $data['kodeprod']=$kode;
        $data['namaprod']=$this->pmodel->getProdName($kode);
        $this->template('product/plus_product',$data);
    }
    function detail_product($kode)
    {
        $logged_in= $this->session->userdata('logged_in');
        if(!isset($logged_in) || $logged_in != TRUE)
        {
            redirect('login/','refresh');
        }
        $data['page_content'] = 'product/product_detail';
        //$data['uri'] = site_url('product/save_harga/'.$kode);
        $data['page_title']='Detail Harga';
        $data['menu']=$this->db->query($this->querymenu);
        $data['query']=$this->pmodel->detail_product($kode);
        $this->session->set_userdata('redirect', $_SERVER['HTTP_REFERER']);
        $this->template('product/product_detail',$data);
    }
    function save_harga($kode)
    {
        $logged_in= $this->session->userdata('logged_in');
        if(!isset($logged_in) || $logged_in != TRUE)
        {
            redirect('login/','refresh');
        }
        $this->pmodel->save_harga();
        redirect('product/show_product');
    }
    function show_product($offset=0)
    {
        $logged_in= $this->session->userdata('logged_in');
        if(!isset($logged_in) || $logged_in != TRUE)
        {
            redirect('login/','refresh');
        }

        $this->load->library('pagination');

        $limit = 15;
        $data['page_content'] = 'report';
        $data['page_title']='Table Of Product';
        $newdata = array(
                   'offset'  => $offset
        );
        $this->session->set_userdata($newdata);
        $data['query']= $this->pmodel->show_product($limit,(int)$offset);
        $data['print']=anchor_popup('product/print_product/',img($this->image_properties));
        $config['base_url'] = site_url('product/show_product/');
        $config['total_rows']=$this->pmodel->getTotalQuery();
        $config['per_page']=$limit;
        $config['uri_segment']=3;

        $this->pagination->initialize($config);

        $data['menu']=$this->db->query($this->querymenu);
        $data['pagination']=$this->pagination->create_links();

    $this->template('report',$data);
    }
    function price_list($keyword=null,$offset=null)
    {
        $logged_in= $this->session->userdata('logged_in');
        if(!isset($logged_in) || $logged_in != TRUE)
        {
            redirect('login/','refresh');
        }

        $this->load->library('pagination');

        $limit = 10;
        $data['page_content'] = 'product/price_list';
        $data['keyword']=$this->input->post('keyword');
        if($keyword=='')
        {
            $keyword=$data['keyword'];
        }
        $data['uri']='product/price_list/';
        $data['keyword']=$this->input->post('keyword');
        $data['page_title']='Price List';
        $data['query']= $this->pmodel->price_list($keyword,$limit,(int)$offset);
        //$data['print']=anchor_popup('product/print_supp/',img($this->image_properties));
        $config['base_url'] = site_url('product/price_list/'.$keyword);
        $config['total_rows']=$this->pmodel->getTotalQuery();
        $config['per_page']=$limit;
        $config['uri_segment']=4;

        $this->pagination->initialize($config);

        $data['menu']=$this->db->query($this->querymenu);
        $data['pagination']=$this->pagination->create_links();
        $this->session->set_userdata('redirect', current_url());
    $this->template('product/product_detail',$data);
    }
    
    function edit_detail($id=0,$kodeprod=0)
    {
        $data['edit']=$this->pmodel->getDetail($id);
        $data['kodeprod']=$kodeprod;
        $data['namaprod']=$this->pmodel->getProdName($kodeprod);
        $data['page_content']='product/plus_product';
        $data['page_title']='EDIT PRICE';
        $data['uri']='product/update_detail/'.$id.'/'.$kodeprod;
        $data['menu']=$this->db->query($this->querymenu);
        $this->template('product/plus_product',$data);
    }
    function show_supp($offset=0)
    {
        $logged_in= $this->session->userdata('logged_in');
        if(!isset($logged_in) || $logged_in != TRUE)
        {
            redirect('login/','refresh');
        }

        $this->load->library('pagination');

        $limit = 15;
        $data['page_content'] = 'report';
        $data['page_title']='Table Of Supplier';
        $data['query']= $this->pmodel->show_supp($limit,(int)$offset);
        $data['print']=anchor_popup('product/print_supp/',img($this->image_properties));
        $config['base_url'] = site_url('product/show_supp/');
        $config['total_rows']=$this->pmodel->getTotalQuery();
        $config['per_page']=$limit;
        $config['uri_segment']=3;

        $this->pagination->initialize($config);

        $data['menu']=$this->db->query($this->querymenu);
        $data['pagination']=$this->pagination->create_links();

    $this->template('report',$data);
    }
    public function add_supp()
    {
        $logged_in= $this->session->userdata('logged_in');
        if(!isset($logged_in) || $logged_in != TRUE)
        {
            redirect('login/','refresh');
        }
        $data['page_title'] = 'Table of Supplier';
        $data['page_content']='product/form_supp';
        $data['url'] = site_url('product/save_supp/');
        $data['menu']=$this->db->query($this->querymenu);
        //$data['suppq']=$this->pmodel->getSupp();
        $this->template('product/form_supp',$data);
    }
    function edit_supp($id='')
    {
        $logged_in= $this->session->userdata('logged_in');
        if(!isset($logged_in) || $logged_in != TRUE)
        {
             redirect('login/','refresh');
        }
        $data['page_title'] = 'Table of Supplier';
        $data['page_content']='product/form_supp';
        $data['url'] = site_url('product/update_supp'.'/'.$id);
        $data['menu']=$this->db->query($this->querymenu);
        $data['edit']=$this->pmodel->getSuppDetail($id);
        //$data['suppq']=$this->pmodel->getSupp();
        $this->template('product/form_supp',$data);
    }
    public function delete_supp($id=null)
    {
        $logged_in= $this->session->userdata('logged_in');
        if(!isset($logged_in) || $logged_in != TRUE)
        {
            redirect('login/','refresh');
        }
        $this->pmodel->delete_supp($id);
    }
    public function update_supp($id=null)
    {
        $logged_in= $this->session->userdata('logged_in');
        if(!isset($logged_in) || $logged_in != TRUE)
        {
            redirect('login/','refresh');
        }
        $this->pmodel->update_supp($id);
    }
     public function save_supp($id=null)
    {
        $logged_in= $this->session->userdata('logged_in');
        if(!isset($logged_in) || $logged_in != TRUE)
        {
            redirect('login/','refresh');
        }
        if($this->_comment(2)==FALSE)
        {
           $this->add_supp();
        }
        else
        {
            $this->pmodel->save_supp($id);
        }

    }
    public function add_product()
    {
        $logged_in= $this->session->userdata('logged_in');
        if(!isset($logged_in) || $logged_in != TRUE)
        {
            redirect('login/','refresh');
        }
        $data['page_title'] = 'Tambah Produk';
        $data['page_content']='product/form_product';
        $data['url'] = site_url('product/save_product/');
        $data['menu']=$this->db->query($this->querymenu);
        $data['suppq']=$this->pmodel->getSupp();
        $this->template('product/form_product',$data);
    }
    function edit_product($id='')
    {
        $logged_in= $this->session->userdata('logged_in');
        if(!isset($logged_in) || $logged_in != TRUE)
        {
             redirect('login/','refresh');
        }
        $data['page_title'] = 'Table of Product';
        $data['page_content']='product/form_product';
        $data['url'] = site_url('product/update_product'.'/'.$id);
        $data['menu']=$this->db->query($this->querymenu);
        $data['edit']=$this->pmodel->getProduct($id);
        $data['suppq']=$this->pmodel->getSupp();
        $this->template('product/form_product',$data);
    }
    public function delete_product($id=null)
    {
        $logged_in= $this->session->userdata('logged_in');
        if(!isset($logged_in) || $logged_in != TRUE)
        {
            redirect('login/','refresh');
        }
        $this->pmodel->delete_product($id);
    }
    public function delete_detail($id=null)
    {
        $logged_in= $this->session->userdata('logged_in');
        if(!isset($logged_in) || $logged_in != TRUE)
        {
            redirect('login/','refresh');
        }
        $this->session->set_userdata('redirect', $_SERVER['HTTP_REFERER']);
        $this->pmodel->delete_detail($id);
        redirect($this->session->userdata('redirect'));
    }
    public function update_product($id=null)
    {
        $logged_in= $this->session->userdata('logged_in');
        if(!isset($logged_in) || $logged_in != TRUE)
        {
            redirect('login/','refresh');
        }
        
        $this->pmodel->update_product($id);
    }
    public function update_detail($id=null)
    {
        $logged_in= $this->session->userdata('logged_in');
        if(!isset($logged_in) || $logged_in != TRUE)
        {
            redirect('login/','refresh');
        }
        //echo "A";
        $tanggal=$this->input->post('tgl');
        $this->pmodel->update_detail($id,$tanggal);
        redirect($this->session->userdata('redirect'));
    }
    public function save_product($id=null)
    {
        $logged_in= $this->session->userdata('logged_in');
        if(!isset($logged_in) || $logged_in != TRUE)
        {
            redirect('login/','refresh');
        }
        if($this->_comment(1)==FALSE)
        {
           $this->add_product();
        }
        else
        {
            $this->pmodel->save_product($id);
        }

    }
    private function _comment($id='')
    {
        $this->load->library('form_validation');
        $this->valid = $this->form_validation;
        switch($id)
        {
            case 1:  $this->valid->set_rules('kodeprod','Product Code','trim|required|min_length[6]|max_length[6]|callback_kodeprod_check|xss_clean');
                    break;
            case 2: $this->valid->set_rules('supp','Code','trim|required|min_length[3]|max_length[3]|callback_supp_check|xss_clean');break;
        }

        
        //$this->valid->set_rules('cpassword','Password Confirmation','trim|required');
        //$this->valid->set_rules('email','Email Address','trim|required|valid_email|callback_email_check');
        return ($this->form_validation->run()==FALSE)?FALSE:TRUE;
    }
}
?>
