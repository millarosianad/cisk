<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Salesman extends MY_Controller
{
    var $image_add = array(
          'src'    => 'assets/css/images/ADD.png',
          'height' => '30',
    );
    var $image_upload_up = array(
          'src'    => 'assets/css/images/upload_up.png',
          'height' => '30',
    );
    var $image_download_down = array(
          'src'    => 'assets/css/images/download_down.png',
          'height' => '30',
    );
    function Salesman()
    {
        $this->load->library(array('table','template'));
        $this->load->helper('url');
        $this->load->model('salesman_model','smmodel');
        $this->querymenu = 'select a.id,a.menuview,a.target,a.groupname from mpm.menu a inner join mpm.menudetail b on a.id=b.menuid where b.userid='.$this->session->userdata('id').' and active=1 order by a.groupname,menuview';
    
        //redirect('salesman/login');
    }
    private function template($view,$data)
    {
        $this->template->set_title('MPM SQUARE');
        $this->template->add_js('modules/skeleton.js');
        $this->template->add_css('modules/skeleton.css');
        $this->template->load_view($view, $data);
    }
    private function m_auth()
    {
        $logged_in= $this->session->userdata('m_logged');
        if(!isset($logged_in) || $logged_in != TRUE)
        {
            redirect('salesman/login/','refresh');
        }
    }
    private function auth()
    {
        $logged_in= $this->session->userdata('logged_in');
        if(!isset($logged_in) || $logged_in != TRUE)
        {
            redirect('login/','refresh');
        }
        else
        {
           $uri=$this->uri->segment(1).$this->uri->slash_segment(2,'both').$this->uri->segment(3);
           if(substr($uri,-1)!="/")
           {
               $uri.="/";
           }
           $sql="select 1 from menu a inner join menudetail b on a.id=b.menuid where b.userid=".$this->session->userdata('id')." and a.target='".$uri."'";
           $query=$this->db->query($sql,array($this->session->userdata('id'),$uri));

           if($query->num_rows()==0)
           {
              //redirect('welcome/home/','refresh');
           }
        }
        $this->load->library('pagination');
    }
    function index()
    {
       $this->auth();
    }
    public function logout()
    {
         $this->session->unset_userdata($this->session->all_userdata());
         $this->session->sess_destroy();
         redirect('salesman/login/','refresh');
    }
    public function login($err=0)
    {
        $data['page_content'] = 'salesman/login';
        $data['page_title']='Salesman LOGIN';
        $data['uri']='salesman/login_check';
        
        if($err==1)
        {
            $data['err']='USENAME ATAU PASSWORD SALAH';
        }
        else {
            $data['err']='';
        }
        $this->template('salesman/login',$data);
    }
    public function home()
    {
        $this->m_auth();
        $data['page_content'] = 'salesman/admin';
        $data['page_title']='Square Salesforce';
        //list menu
        $data['menu1']=site_url('salesman/rks');
        $data['title1']='RKS';
        $data['menu2']=site_url('salesman/stok/dialog');
        $data['title2']='STOK OPNAME';
        $data['menu3']=site_url('salesman/po');
        $data['title3']='PURCHASE ORDER (PO)';
        $data['menu4']=site_url('salesman/po/list');
        $data['title4']='DAFTAR PESANAN';
        $this->template('salesman/home',$data);
    }
    public function stok($state='show',$key=0,$id=0,$init=0)
    {
        $this->m_auth();
        $data['page_content'] = 'salesman/stok';
        $data['page_title']='INPUT STOK';
        $data['state']=$state;
        switch($state)
        {
            case 'dialog':
            {
               $data['uri']=  site_url('salesman/stok/list_pelanggan');
            }break;
            case 'list_pelanggan':
            {
               $data['uri']=site_url('salesman/stok/show');
               $data['query']=$this->smmodel->stok('list_pelanggan');
            }break;
            case 'show':
            {
               $data['query']=$this->smmodel->stok('show');
               $data['uri']=  site_url('salesman/stok/save');
            }break;
            case 'save':
            {
                $this->smmodel->stok('save');
                redirect('salesman/home');
            }break;
        }
        $this->template('salesman/stok',$data);
    }
    public function rks($state='add',$key=0,$id=0,$init=0)
    {
        //$this->m_auth();
        $data['page_content'] = 'salesman/rks';
        $data['page_title']='Square Salesforce';
        $data['menu']=$this->db->query($this->querymenu);
        $data['state']=$state;
        $data['keyword']=$this->input->post('keyword');
        switch($state)
        {
            case 'delete_temp':
            {
                $this->session->set_userdata('redirect', $_SERVER['HTTP_REFERER']);
                $this->smmodel->rks('delete_temp',$key);
                redirect($this->session->userdata('redirect'));
            }break;
            case 'show':
            {
                $data['query']=$this->smmodel->rks('show',$key);
                $data['page_content'] = 'salesman/pivot_rks';
            }break;
            case 'add':
            {
                $data['dp']=$this->smmodel->list_cust($key);
                $data['uri']=site_url('salesman/rks/save_temp/'.$key);
                $data['uri2']=site_url('salesman/rks/show/'.$key);
                $data['table']=$this->smmodel->rks('show_temp',$key);
            }break;
            case 'save_temp':
            {
                $this->smmodel->rks('save_temp',$key);
                redirect('salesman/rks/add/'.$key);
            }break;
            case 'save':
            {
                $this->smmodel->rks('save',$key);
                redirect('salesman/rks/show/'.$key);
            }break;
        }
        $this->template($data['page_content'],$data);
    }
    public function po($state='show',$key=0,$id=0,$init=0)
    {
        $this->m_auth();
        $data['page_content'] = 'salesman/po';
        $data['page_title']='Square Salesforce';
        $data['state']=$state;
        $data['keyword']=$this->input->post('keyword');
        switch($state)
        {
            case 'show':
            {
                $data['query']=$this->smmodel->po('show');
                $data['uri']=  site_url('salesman/po/save');
            }break;
            case 'save':
            {
                $this->smmodel->po('save');
                redirect('salesman/home');
            }
            case 'list':
            {
                $data['query']=$this->smmodel->po('list');
            }break;
        }
        $this->template('salesman/po',$data);
    }
    public function login_check()
    {
        $user=mysql_real_escape_string($this->input->post('userx'));
        $pass=mysql_real_escape_string($this->input->post('passx'));
        
        $query=$this->smmodel->login_check($user,$pass);
        if($query)
        {
                //echo 'success';
            $row = $query->row_array(1);
            $newdata = array(
                'm_username'  => $row['username'],
                'm_id'        => $row['id'],
                'm_siteid'    => $row['siteid'],
                'm_kode_sales'=> $row['kode_sales'], 
                'm_logged' =>TRUE
            );
                //$query->free_result();
            $this->session->set_userdata($newdata);
            redirect('salesman/home');        
        }
        else
        {
            redirect('salesman/login/1');
        }
    }
    public function admin($state='show',$key=0,$id=0,$init=0)
    {
        $this->auth();
        $data['page_content'] = 'salesman/admin';
        $data['page_title']='Salesman Registration';
        $data['menu']=$this->db->query($this->querymenu);
        $data['state']=$state;
        $data['keyword']=$this->input->post('keyword');
        switch ($state)
        {
            case 'upload':
            {
                
            }break;
            case 'download_dialog':
            {
                $data['siteid']=$this->smmodel->admin('siteid'); 
                $data['uri']='salesman/admin/download/';
            }break;
            case 'download':
            {
                return $this->smmodel->admin('download');
            }break;
            case 'show':
            {
                $data['image_add']=anchor('salesman/admin/add',img($this->image_add));
                $data['image_upload_up']=anchor('salesman/admin/upload',img($this->image_upload_up));
                $data['image_download_down']=anchor('salesman/admin/download',img($this->image_download_down));
                $data['query']=$this->smmodel->admin('show');
            }break;
            case 'preadd':
            {
                $data['uri']='salesman/admin/add/';
                $data['siteid']=$this->smmodel->admin('siteid');
            }break;
            case 'buildSalesName':
            {
                $dp = $this->input->post('id',TRUE);
                $output="";
                $query=$this->smmodel->admin('kode_sales',$dp);
                foreach($query->result() as $row)
                {
                    $output .= "<option value='".$row->kodesales."'>".$row->kodesales." ".$row->namasales."</option>";
                }
                echo $output;
            }break;
            case 'add':
            {
                $data['siteid']=$this->smmodel->admin('siteid');
                //$data['kode_sales']=$this->smmodel->admin('kode_sales',$input);
                if($key=='606')
                {
                    $data['error_message']="<font color='red'>Username sudah dipakai gunakan yang lainnya</font>";
                }
                else
                {
                    $data['error_message']="";
                }
                //$data['uri1']='salesman/admin/add/'.$input;
                $data['uri2']='salesman/admin/save/';
                //$data['site']=$input;
                
                //$this->smmodel->admin('add_ajax','TGR001');
            }break;
            case 'add_ajax':
            {
                $siteid=$_POST['siteid'];
                $query= $this->smmodel->admin('add_ajax',$siteid);
                //echo $siteid;
                $output=null;
                foreach($query->result as $row)
                {
                   $output .= "<option value='".$row->salesmanid."'>".$row->nama_salesman."</option>";
                }
                echo $output;
            }break;
            case 'save':
            {
                $flag=$this->smmodel->admin('save');
                if($flag)
                {
                    $siteid=$this->input->post('siteid');
                    $path='salesman/admin/add/606/'.$siteid;
                    redirect($path);
                }
                //redirect('salesman/admin/');
            }break;
            case 'edit':
            {
                $data['row']=$this->smmodel->admin('edit',$key);
                $data['siteid']=$this->smmodel->admin('siteid');
                $data['kode_sales']=$this->smmodel->admin('kode_sales',$data['row']->siteid);
                $data['uri']='salesman/admin/update/'.$key;
            }break;
            case 'update':
            {
                $this->smmodel->admin('update',$key);
                redirect('salesman/admin/');
            }break;
            case 'active':
            {
                $this->session->set_userdata('redirect', $_SERVER['HTTP_REFERER']);
                $this->smmodel->admin('active',$key,$id);
                redirect($this->session->userdata('redirect'));
            }break;
        }
        $this->template('salesman/admin',$data);
    }
    Public function so($state='import',$key=0,$id=0,$init=0)
    {
        switch($state)
        {
            case 'login':
            {
                //$user=mysql_real_escape_string($key);
                //$pass=mysql_real_escape_string($id);
                
                //$json = $_SERVER['HTTP_JSON'];
                $user = $this->input->post('username');
                $pass = $this->input->post('password');
                if($user && $pass)
                {
                    $test= $this->smmodel->so('login',$user,$pass);
                    if($test)
                    {
                        //echo $user;
                        //echo $pass;
                        echo $test;
                    }
                    else {
                        echo "nothingness";
                        return false;
                    }
                }
                else
                {
                    echo 'emptiness';
                    return false;
                }
            }break;
            case 'import':
            {
                //echo var_dump($key);
                $json = $_SERVER['HTTP_JSON'];
                return $this->smmodel->so('import',$json);
            }break;
            case 'download':
            {
               echo $this->smmodel->so('download3',$key,$id,$init);
            }break;
        }
        
        
    }
    public function add($state='show',$key=0,$id=0,$init=0)
    {
        switch($state)
        {
            case 'show':
            {
                
            }break;
            case 'save':
            {
                
            }break;
            case 'activer':
            {
                
            }break;
            case 'edit':
            {
                
            }break;
        }
    }
}
?>
