<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Trans extends MY_Controller
{
    var $folder_zip='./assets/uploads/zip/';
    var $folder_unzip='./assets/uploads/unzip/';
    var $image_properties = array(
          'src' => 'assets/css/images/printer.png',
          'alt' => 'Printing Document',
          'class' => 'post_images',
          'width' => '50',
          'height' => '50',
          'title' => 'Printer',
          'border' => 0,
    );
    var $image_add = array(
          'src'    => 'assets/css/images/ADD.png',
          'height' => '30',
    );
    var $image_printer = array(
          'src'    => 'assets/css/images/printer.png',
          'height' => '30',
    );
    var $image_back = array(
          'src'    => 'assets/css/images/back.png',
          'height' => '30',
    );
    var $image_half = array(
          'src'    => 'assets/css/images/half.png',
          'height' => '30',
    );
    var $querymenu='';
    var $limit;
    ////'select menuview,target,groupname from mpm.menu where active = 1 order by groupname';
    function Trans()
    {
        $this->load->library(array('table','email','template','stable'));
        //$this->load->plugin('excel_helper');
        $this->load->helper('url_helper','excel_helper');
        $this->load->model('trans_model','tmodel');
        $this->querymenu = 'select a.id,a.menuview,a.target,a.groupname from mpm.menu a inner join mpm.menudetail b on a.id=b.menuid where b.userid='.$this->session->userdata('id').' and active=1 order by a.groupname,menuview';
    }
    private function template($view,$data)
    {
        
        $this->template->set_title('MPM SQUARE');
        $this->template->add_js('modules/skeleton.js');
        $this->template->add_css('modules/skeleton.css');
        $this->template->load_view($view, $data);
    }
    private function auth()
    {
        $logged_in= $this->session->userdata('logged_in');
        if(!isset($logged_in) || $logged_in != TRUE)
        {
            redirect('login/login/','refresh');
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
       $logged_in= $this->session->userdata('logged_in');
       if(!isset($logged_in) || $logged_in != TRUE)
       {
            redirect('login/login/','refresh');
       }
    }
    function pajak($state='upload',$key=0,$id=0,$init=0)
    {
        $this->auth();
        $data['page_content'] = 'trans/pajak';
        $data['page_title']='Tanda Terima';
        $data['menu']=$this->db->query($this->querymenu);
        $data['state']=$state;
        $data['keyword']=$this->input->post('keyword');
        switch($state)
        {
            case 'upload':
            {
                $data['uri']=  site_url('trans/pajak/do_upload');
            }break;
            case 'do_upload':
            {
                $config['upload_path'] = './uploads/';
		$config['allowed_types'] = 'csv';
		$config['max_size']	= '1000';

		$this->load->library('upload', $config);

		if ( ! $this->upload->do_upload())
		{
			$error = array('error' => $this->upload->display_errors());

			$this->load->view('upload_form', $error);
		}
		else
		{
			$data = array('upload_data' => $this->upload->data());

			$this->load->view('upload_success', $data);
		}
            }break;
           
        }
        $this->template('trans/pajak',$data);
    }
    function permit($state='show',$key=0,$id=0,$init=0)
    {
        $this->auth();
        $data['page_content'] = 'trans/permit';
        $data['page_title']='Tanda Terima';
        $data['menu']=$this->db->query($this->querymenu);
        $data['state']=$state;
        $data['keyword']=$this->input->post('keyword');
        switch($state)
        {
            case 'print':
            {
                $this->tmodel->permit('print',$key,$id);
            }break;
            case 'show_faktur':
            {
                if($this->input->post('tanggal')!='')
                {
                    $key=$this->input->post('tanggal');
                }
                $data['uri']=site_url('trans/permit/show_faktur');
                $this->load->library('pagination');
                $limit = 10;
                $data['query']= $this->tmodel->permit('show_faktur',$key,(int)$id);
                $config['base_url'] = site_url('trans/permit/show_faktur/'.$key);
                $config['total_rows']=$this->tmodel->getTotalQuery();
                $config['per_page']=$limit;
                $config['uri_segment']=5;
                $this->pagination->initialize($config);
                $data['pagination']=$this->pagination->create_links();
            }break;
            case 'download_email':
            {
                $this->session->set_userdata('redirect', $_SERVER['HTTP_REFERER']);
                $this->tmodel->permit('download_email',$key);
                redirect($this->session->userdata('redirect'));
            }break;
            case 'email_faktur':
            {
                $this->session->set_userdata('redirect', $_SERVER['HTTP_REFERER']);
                $this->tmodel->permit('email_faktur',$key,$id);
                redirect($this->session->userdata('redirect'));
            }break;
            case 'print_range':
            {
                $tanggal=$this->input->post('range');
                $pilih=$this->input->post('keterangan');
                //echo str_replace('-','',$tanggal);
                $this->tmodel->permit('print_range',str_replace('-','',$tanggal),$pilih);
            }break;
            case 'print_rekap_dialog':
            {
                $data['uri']=  site_url('trans/permit/print_rekap');
            }break;
            case 'print_rekap':
            {
                return $this->tmodel->permit('print_rekap');
            }break;
            case 'amplop':
            {
                $this->tmodel->permit('amplop',$key);
            }break;
            case 'amplop_coklat':
            {
                $this->tmodel->permit('amplop_coklat',$key);
            }break;
            case 'download_baru':
           {
                $this->session->set_userdata('redirect', $_SERVER['HTTP_REFERER']);
                $this->tmodel->permit('download_baru',$key,$id);
                redirect($this->session->userdata('redirect'));
            }break;
            case 'download':
            {
                $this->session->set_userdata('redirect', $_SERVER['HTTP_REFERER']);
                $this->tmodel->permit('download',$key);
                redirect($this->session->userdata('redirect'));
            }break;
            case 'email':
            {
                $this->session->set_userdata('redirect', $_SERVER['HTTP_REFERER']);
                $this->tmodel->permit('email',$key,$id);
                redirect($this->session->userdata('redirect'));
            }break;
            case 'delete':
            {
                $this->session->set_userdata('redirect', $_SERVER['HTTP_REFERER']);
                $this->tmodel->permit('delete',$key);
                redirect($this->session->userdata('redirect'));
            }break;
            case 'show':
            {
                if($this->input->post('keyword')!='')
                {
                    $key=$this->input->post('keyword');
                }
                $data['add']=anchor('trans/permit/pilih/',img($this->image_add,'ADD'));
                $data['print']=anchor('trans/permit/print_rekap_dialog/',img($this->image_properties,'PRINT REKAP'));
                $data['uri']=site_url('trans/permit/show');
                $data['uri2']=site_url('trans/permit/print_range');
                $this->load->library('pagination');
                $limit = 10;
                $data['query']= $this->tmodel->permit('show',$key,(int)$id);
                $config['base_url'] = site_url('trans/permit/show/'.$key);
                $config['total_rows']=$this->tmodel->getTotalQuery();
                $config['per_page']=$limit;
                $config['uri_segment']=5;
                $this->pagination->initialize($config);
                $data['pagination']=$this->pagination->create_links();
            }break;
            case 'pilih':
            {
                $data['uri']=site_url('trans/permit/set_client/');
                $data['client']=$this->tmodel->list_pelanggan();
                if(isset($key))
                {
                    $data['err']='TIDAK ADA FAKTUR YANG DAPAT DIKIRIM';
                }
            }break;
            case 'set_client':
            {
                $this->session->set_userdata('client', $this->input->post('client'));
                redirect(site_url('trans/permit/show_add/'));
            }break;
            case 'show_add':
            {
                $client=$this->session->userdata('client');
                if($this->tmodel->list_faktur($client))
                {
                    $data['lang']=$this->tmodel->getPelanggan($client);
                    $data['kode']=$this->tmodel->list_faktur($client);
                    $data['query']=$this->tmodel->permit('show_add');

                    $data['uri2']=site_url('trans/permit/add/'.$client);
                    $data['uri3']=site_url('trans/permit/save/'.$client);
                }
                else
                {
                    redirect(site_url('trans/permit/pilih/err'));
                }
            }break;
            case 'save':
            {
                $this->tmodel->permit('save',$key);
                redirect(site_url('trans/permit/'));
            }break;
            case 'add':
            {
                $this->session->set_userdata('redirect', $_SERVER['HTTP_REFERER']);
                $this->tmodel->permit('add',$key);
                redirect($this->session->userdata('redirect'));
            }break;
            case 'delete_temp':
            {
                $this->session->set_userdata('redirect', $_SERVER['HTTP_REFERER']);
                $this->tmodel->permit('delete_temp',$key);
                redirect($this->session->userdata('redirect'));
            }break;
        }
        $this->template('trans/permit',$data);
    }
    function sj($state='show',$key=0,$id=0,$init=0,$userid=0)
    {
        $this->auth();
        $data['page_content'] = 'trans/sj';
        $data['page_title']='Tanda Terima';
        $data['menu']=$this->db->query($this->querymenu);
        $data['state']=$state;
        $data['keyword']=$this->input->post('keyword');
        switch($state)
        {
           
            case 'show':
            {
                if($this->input->post('keyword')!=0)
                {
                    $key=$this->input->post('keyword');
                }
                $data['add']=anchor('trans/sj/show_add/',img($this->image_add,'ADD'));
                $data['uri']=site_url('trans/sj/show');
                $this->load->library('pagination');
                $data['printer']=anchor('trans/sj/print_range_dialog/',img($this->image_printer,'PRINTER'));
                $limit = 10;
                $data['query']= $this->tmodel->sj('show',$key,(int)$id);
                $config['base_url'] = site_url('trans/sj/show/'.$key);
                $config['total_rows']=$this->tmodel->getTotalQuery();
                $config['per_page']=$limit;
                $config['uri_segment']=5;
                $this->pagination->initialize($config);
                $data['pagination']=$this->pagination->create_links();
            }break;
            case 'print_range_dialog':
            {
                $data['uri']=site_url('trans/sj/print_range');
            }break;
            case 'print_range':
            {
                $this->tmodel->sj('print_range');
            }break;
            case 'print':
            {
                $this->tmodel->sj('print',$key);
            }break;
            case 'show_add':
            {
                //$this->session->set_userdata('redirect', $_SERVER['HTTP_REFERER']);
                $data['query']=$this->tmodel->sj('show_add');
                $data['job']=$this->tmodel->getJob('T');
                $data['nomor']=$this->tmodel->getNomorsj();
                $data['uri']=site_url('trans/sj/add');
                $data['uri2']=site_url('trans/sj/save');
                
            }break;
            case 'add':
            {
                $this->session->set_userdata('redirect', $_SERVER['HTTP_REFERER']);
                $this->tmodel->sj('add');
                redirect($this->session->userdata('redirect'));
                 
            }break;
            case 'delete':
            {
                $this->session->set_userdata('redirect', $_SERVER['HTTP_REFERER']);
                $this->tmodel->sj('delete',$key);
                redirect($this->session->userdata('redirect'));
            }break;
            case 'delete_temp':
            {
                $this->session->set_userdata('redirect', $_SERVER['HTTP_REFERER']);
                $this->tmodel->sj('delete_temp',$key);
                redirect($this->session->userdata('redirect'));
            }break;
            case 'save':
            {
                $this->tmodel->sj('save',$key);
                redirect(site_url('trans/sj/'));
            }break;
        }
        $this->template('trans/sj',$data);
    }
    function sell($state='show',$key=0,$id=0,$init=0,$userid=0)
    {
        $this->auth();
        $data['page_content'] = 'trans/sell';
        $data['page_title']='Download sales';
        $data['menu']=$this->db->query($this->querymenu);
        $data['state']=$state;
        $data['keyword']=$this->input->post('keyword');
        switch($state)
        {
            case 'dialog_download':
            {
                $data['uri']=site_url('trans/sell/download');
            }break;
            case 'download':
            {
                return $this->tmodel->sell('download');
            }break;

        }
        $this->template('trans/sell',$data);

    }
    
    function retur($state='show',$key=0,$id=0,$init=0,$userid=0)
    {
        $this->auth();
        $data['page_content'] = 'trans/retur';
        $data['page_title']='RETUR';
        $data['menu']=$this->db->query($this->querymenu);
        $data['state']=$state;
        $data['keyword']=$this->input->post('keyword');
        switch($state)
        {
            case 'edit_detail':
            {
                $this->session->set_userdata('redirect', $_SERVER['HTTP_REFERER']);
                $data['uri']=site_url('trans/retur/update_detail/'.$key);
                $data['query']=$this->tmodel->retur('edit_detail',$key);
            }break;
            case 'update_detail':
            {
                $this->tmodel->retur('update_detail',$key);
                redirect($this->session->userdata('redirect'));
            }break;
            case 'print_dialog':
            {
                $data['uri']=site_url('trans/retur/print_rekap');
            }break;
            case 'print_rekap':
            {
                return $this->tmodel->retur('print_rekap');
            }break;
            case 'show':
            {
                $pilih=$this->input->post('pilih');
                $data['uri']=site_url('trans/retur/show');
                $data['print_dialog']=anchor('trans/retur/print_dialog/',img($this->image_properties,'ADD'));
                $this->load->library('pagination');
                $limit = 10;
                $data['query2']=$this->tmodel->list_client();
                $data['pilih']=$pilih==''?$id:$pilih;
                if($key==''){
                    $key=10;
                    $id=10;
                }
                switch($pilih)
                {
                    case '0':
                    $userid=$this->input->post('userid');
                    $data['userid']=$userid==''?$key:$userid;
                    if($userid!='')
                    {
                        $key=$userid;
                        $id=0;
                    }
                    break;
                    case '1':
                    $tanggal=$this->input->post('tanggal');
                    $data['tanggal']=$tanggal==''?$key:$tanggal;
                    if($tanggal!='')
                    {
                        $key=$tanggal;
                        $id=1;
                    }
                    break;
                }

                $data['query']=$this->tmodel->retur('show',$key,$id,(int)$init);
                $config['base_url'] = site_url('trans/retur/show/'.$key.'/'.$id);
                $config['total_rows']=$this->tmodel->getTotalQuery();
                $config['per_page']=$limit;
                $config['uri_segment']=6;
                $this->pagination->initialize($config);

                $data['pagination']=$this->pagination->create_links();
                $data['add']=anchor('trans/retur/show_supp/',img($this->image_add,'ADD'));
                $this->session->set_userdata('redirect', $_SERVER['HTTP_REFERER']);

            }break;
            case 'print':
            {
                $this->tmodel->retur('print',$key);
            }break;
            case 'print_beli':
            {
                $this->tmodel->retur('print_beli',$key);
            }break;
            case 'show_supp':
            {
                //$data['page_content']='trans/spk';
                $data['uri'] = site_url('trans/retur/set_supp');
                $data['query']=$this->tmodel->getSupp();
                $data['query2']=$this->tmodel->list_client();
            }break;
            case 'set_supp':
            {
                $supp=$this->input->post('supp');
                $tgl=$this->input->post('tgl');
                $client=$this->input->post('client');
                $newdata = array(
                   'supplier'  => $supp,
                   'tgl'=>$tgl,
                   'client'=>$client
                );
                $this->session->set_userdata($newdata);
                $this->tmodel->retur('clear_temp');
                redirect(site_url('trans/retur/add'));
            }break;
            case 'add':
            {
                //$query->free_result();
                $data['uri'] = site_url('trans/retur/addtemp');
                $data['uri_detail']=site_url('trans/retur/add');
                $data['uri2'] = site_url('trans/retur/confirm');
                $data['query']=$this->tmodel->list_product_supp_retur($this->session->userdata('supplier'));
                $data['table']=$this->tmodel->retur('show_temp');
                
                $produk=$this->input->post('product');
                if($produk!='')
                {
                    $data['detail']=$this->tmodel->getProductRetur($produk);
                }
                /*if(isset($_GET['term']))
                {
                    $q = strtolower($_GET['term']);
                    $this->tmodel->getProductReturAjax($q);
                    $this->load->view('wrapper',$data);
                }*/
               
            }break;
            case 'show_detail':
            {
                $data['query']=$this->tmodel->retur('show_detail',$key);
                $data['query2']=$this->tmodel->list_product_supp_retur($id);
                $data['info']=$this->tmodel->getReturInfo($key);
                $data['uri']=site_url('trans/retur/edit/'.$key.'/'.$init.'/'.$userid);
                $data['uri2']=site_url('trans/retur/update/'.$key);
                
                $newdata = array(
                   'supplier'  => $id
                );
                $this->session->set_userdata($newdata);
            }break;
            case 'confirm':
            {
                $data['page_title'] = 'Confirmation';
                $data['uri'] = site_url('trans/retur/save');
            }break;
            case 'addtemp':
            {
                $this->input->post('kodeprod');
                $this->tmodel->retur('addtemp');
            }break;
            case 'save':
                $this->tmodel->retur('save');
                break;
            case 'edit':
                $this->tmodel->retur('edit',$key,$id,$init);
                break;
            case 'delete':
                $this->tmodel->retur('delete',$key);
                break;
            case 'delete_detail':
                $this->session->set_userdata('redirect', $_SERVER['HTTP_REFERER']);
                $this->tmodel->retur('delete_detail',$key);
                redirect($this->session->userdata('redirect'));
                break;
            case 'delete_temp':
                $this->tmodel->retur('delete_temp',$key);
                redirect('trans/retur/add');
                break;
            case 'update':
                $this->tmodel->retur('update',$key,$init);
                break;
        }

	$this->template('trans/retur',$data);
    }
    function repl($state='null',$key=null,$id=null,$init=null,$offset=null)
    {
        $this->auth();
        $data['page_content'] = 'trans/repl';
        $data['page_title']='REPLENISHMENT';

        $data['menu']=$this->db->query($this->querymenu);
        $data['state']=strtolower($state);
        $data['keyword']='';
       
        switch(strtolower($state))
        {
            case 'print':
            {
                return $this->tmodel->repl('print',$key,$id,$init);
            }break;
            case 'list_upload':
            {
                $limit='20';
                $keyword=$this->input->post('userid');
          
                if($keyword!='')
                {
                    $id=$keyword;
                }
                $data['uri']=site_url('trans/repl/list_upload');
                
                $data['page_content'] = 'trans/repl';
                $data['page_title']='LIST OF THE LAST UPLOAD';
                $data['query'] = $this->tmodel->repl('list_upload',(int)$key,$id);
                $data['query2']=$this->tmodel->list_client2();
                $config['base_url'] = site_url('trans/repl/list_upload/');
                $config['total_rows']=$this->tmodel->getTotalQuery();
                $config['uri_segment']=4;
                $config['per_page']=$limit;
                $this->pagination->initialize($config);

                $data['menu']=$this->db->query($this->querymenu);
                $data['pagination']=$this->pagination->create_links();

                //$data['sum']=$this->rmodel->getTotalQuery();
            }break;
            case 'detail':
            {
                $data['uri']=site_url('trans/repl/detail/'.$key.'/'.$id.'/'.$init);
                $this->tmodel->repl('detail',$key);
                $data['supp']=$this->tmodel->getSupp();
                $limit=20;
                $this->tmodel->repl('delete_temp');
                $tanggal=$this->input->post('tanggal');
                if($tanggal!='')
                {
                    $data['query']=$this->tmodel->repl('show_temp',$id);
                }
                $data['tanggal']=$tanggal;
                $data['id']=$init;
                $data['customer']=$this->tmodel->getCustomer($id);//$id is nocab to get the nama_comp from tabcomp
                $data['company']=$this->tmodel->getCompany($init);//init is userid to  get the company name from user
                //$data['query']=$this->tmodel->repl('show_temp',$key);
                $data['uri2']=site_url('trans/repl/save/'.$tanggal.'/'.$id);
                $data['link']=anchor('trans/repl/list_upload','Show List');
                
            }break;
            case 'show':
            {
                
                $data['uri']=site_url('trans/repl/show');
                $data['query2']=$this->tmodel->list_client2();
                $data['tanggal']=$this->input->post('tanggal');
                $tanggal=$this->input->post('tanggal');
                $userid=$this->input->post('userid');
                $pilih=$this->input->post('pilih');
                switch($pilih)
                {
                    case '0':
                    if($userid!='')
                    {
                        $key=$userid;
                        $id=0;
                    }
                    break;
                    case '1':
                    if($tanggal!='')
                    {
                        $key=$tanggal;
                        $id=1;
                    }
                    break;
                }
                
                //if($this->input->post('submit') or $init)
                $data['query']=$this->tmodel->repl('show',$key,$id,(int)$init);
                $config['base_url'] = site_url('trans/repl/show/'.$key.'/'.$id);
                $config['total_rows']=$this->tmodel->getTotalQuery();
                $config['uri_segment']=6;
                $config['per_page']=$this->limit;
                $this->pagination->initialize($config);
                $data['pagination']=$this->pagination->create_links();
                $data['pilih']=$pilih;
                
            }break;
            case 'show_detail':
            {
                $data['query']=$this->tmodel->repl('show_detail',$key,$id);
                $data['uri']=site_url('trans/repl/show_detail');
                $data['supp']=$this->tmodel->getSupp();
                $this->session->set_userdata('redirect', $_SERVER['HTTP_REFERER']);
                $data['uri2']=site_url('trans/repl/save2/'.$key.'/'.$id);
                //$data['query2']=$this->tmodel->list_client();
                //$data['tanggal']=$this->input->post('tanggal');
            }break;
            case 'save':
            {
                $this->tmodel->repl('save',$key,$id);
                redirect(site_url('trans/repl/list_upload'));
            }break;
            case 'save2':
            {
                $this->tmodel->repl('save2',$key,$id);
                redirect($this->session->userdata('redirect'));
            }
            
        }
        $this->template('trans/repl',$data);
    }
  
    function reader($state='show',$key=null,$id=null)
    {
        $this->auth();
        $data['page_content'] = 'trans/upload_form';
        $data['page_title']='Upload Text File';
        $data['menu']=$this->db->query($this->querymenu);
        $data['state']=$state;
        $data['keyword']=$this->input->post('keyword');
        switch($state)
        {
            case 'show':
            {
                $config['upload_path'] = $this->folder_zip.date('Ym').'/';
		$config['allowed_types'] = 'zip';
		$config['max_size']	= '4000';
                $config['overwrite']	= TRUE;
		$this->load->library('upload', $config);
                $data['uri']=site_url('trans/reader');

                if ( !$this->upload->do_upload())
		{
			$error = $this->upload->display_errors();
                        $data['page_title'] = 'Upload Data';
                        $data['page_content']='trans/upload_form';
                        $data['error']=$error;
		}
		else
		{
			$upload_data = $this->upload->data();
                        $data['page_title'] = 'Upload Data';
                        $data['page_content']='trans/upload_success';
                        $data['upload_data']=$upload_data;
                        delete_files('./assets/uploads/unzip/'.substr($upload_data['orig_name'],2,2).'/');
                        $this->reader('unzip',$upload_data['orig_name']);
                        $data['msg']=$this->reader('infile',$upload_data['orig_name'],$this->input->post('year'));

                        //$this->delete_files(substr($upload_data['orig_name'],2,2));
                }
            }break;
            case 'unzip':
            {
                if(!is_dir('./assets/uploads/unzip/'.substr($key,2,2).'/'))
                {
                    mkdir('./assets/uploads/unzip/'.substr($key,2,2).'/',0777);
                }
                shell_exec('unzip -P DELTOMED ./assets/uploads/zip/'.date('Ym').'/'.substr($key,0,-4).' -d ./assets/uploads/unzip/'.substr($key,2,2).'/ 2>&1');
            
                return true;
            }break;
            case 'infile': {
                    $nocab=substr($key,2,2);
                    $month=substr($key,4,2);
                    $year=substr($id,2,2);
                    $ddl1 = array('fh','fi','mp','mh','rh','ri');
           
                    $msg=array();
                    //$load="LOAD DATA INFILE '".str_replace('\\','/',realpath('.'))."/assets/uploads/unzip/".$nocab."/";
                    $load="LOAD DATA INFILE '".realpath('.')."/assets/uploads/unzip/".$nocab."/";
                    //$file=str_replace('\\','/',realpath('.')).'/assets/uploads/unzip/'.$nocab.'/'.$ddl.$nocab.$year.$month.'.TXT';
                    $file='./assets/uploads/unzip/'.$nocab.'/TBLANG00.TXT';
                    if(file_exists($file))
                        {
                            $sql_del='delete from pusat.user ';
                            $this->db->query($sql_del);
                            $sql = $load."TBLANG00.TXT' INTO TABLE pusat.user FIELDS TERMINATED BY ',' ENCLOSED BY '~' LINES TERMINATED BY '\\r\\n'";
                            $this->db->query($sql);
                            $msg[]= 'TBLANG00.TXT'.' found and uploaded <br />';
                            //$this->delete_files($file);
                            //$sql='update pusat.user set custid=concat(nocab,kode_lang)';
                            //$this->db->query($sql);
                        }
                        else {
                            $msg[]= 'TBLANG00.TXT not found<BR />';
                        }
                    foreach($ddl1 as $ddl)
                    {
                                                    
                        $file=realpath('.').'/assets/uploads/unzip/'.$nocab.'/'.strtoupper($ddl).$nocab.$year.$month.'.TXT';

                        $fields = $this->db->field_data('pusat.'.$ddl);
                        $name='(';
                        $set=' , ';
                        $i=1;
                        foreach ($fields as $field)
                        {
                            if($field->type=='date')
                            {
                                $name.='@date'.$i.' , ';
                                $set .=$field->name." = "."str_to_date(@date".$i.",'%Y/%m/%d')  , ";
                                $i++;
                            }
                            else
                            {
                                $name.=$field->name.", ";
                            }
                        }
                        $name = substr($name, 0, -2);
                        $name.=')';
                        $set  = substr($set, 0, -2);

                        if(file_exists($file))
                        {
                            $sql_del='delete from pusat.'.$ddl.' where bulan ='.$year.$month;
                            $this->db->query($sql_del);
                            $sql = $load.strtoupper($ddl).$nocab.$year.$month.".TXT' INTO TABLE pusat.".$ddl." FIELDS TERMINATED BY ',' ENCLOSED BY '~' LINES TERMINATED BY '\\r\\n' ".$name." set bulan = ".$year.$month." ".$set;
                            $this->db->query($sql);
                            $sql_del='delete from pusat.'.$ddl.' where nodokjdi ="XXXXXX" or nodokjdi=""';
                            $this->db->query($sql_del);
                            $msg[]= $ddl.$nocab.$year.$month.'.TXT'.' found and uploaded <br />';
                            //$this->delete_files($file);
                        }
                        else {
                            $msg[]= $ddl.$nocab.$year.$month.'.TXT'.' not found<BR />';
                        }
                    }
                    return $msg;
              }break;
        }
        $this->template('trans/upload_form',$data);
    }

    function piutang($state='null',$key=null,$id=null,$init=null,$offset=null)
    {
        $this->auth();
        $data['page_content'] = 'trans/piutang';
        $data['page_title']='PIUTANG';

        $data['menu']=$this->db->query($this->querymenu);
        $data['state']=$state;
        $data['keyword']=$this->input->post('keyword');
        $data['uri']=site_url('trans/piutang/');
        switch(strtolower($state))
        {
            case 'test':
            {
                $data['query']=$this->tmodel->piutang('test');
            }break;
            case 'show_dialog':
            {
                $data['uri']='trans/piutang/show';
            }break;
            case 'show_dialog_barat':
            {
                $data['uri']='trans/piutang/show_barat';
            }break;
            case 'show_dialog_timur':
            {
                $data['uri']='trans/piutang/show_timur';
            }break;
            case 'show':
            {
                $format=$this->input->post('format');
                if($format=='3')
                    return $this->tmodel->piutang('show');
                else
                    $data['query']=$this->tmodel->piutang('show');
            }break;
            case 'show_barat':
            {
                $format=$this->input->post('format');
                if($format=='3')
                    return $this->tmodel->piutang('show_barat');
                else
                    $data['query']=$this->tmodel->piutang('show_barat');
            }break;
            case 'show_timur':
            {
                $format=$this->input->post('format');
                if($format=='3')
                    return $this->tmodel->piutang('show_timur');
                else
                    $data['query']=$this->tmodel->piutang('show_timur');
            }break;
            case 'detail':
            {
                $data['query']=$this->tmodel->piutang('detail',$key,$id,$init);
                $data['url']='trans/piutang/email2/'.$key.'/'.$id.'/'.$init;
            }break;
            case 'detail7up':
            {
                $data['query']=$this->tmodel->piutang('detail7up',$key,$id,$init);
                $data['url']='trans/piutang/email/'.$key.'/'.$id.'/'.$init;
            }break;
            case 'email2':
            {
                $this->tmodel->piutang('email2',$key,$id,$init);
            }break;
            case 'email':
            {
                $this->tmodel->piutang('email',$key,$id,$init);
            }break;
            case 'faktur':
            {
                return $this->tmodel->piutang('faktur',$key,$id);
            }break;

        }
        $this->template('trans/piutang',$data);
    }
    function po($state='null',$key=null,$id=null,$init=null,$offset=null)
    {
        $this->auth();
        //$data['page_content'] = 'trans/po';
        $data['page_title']='LIST PO';
        $data['menu']=$this->db->query($this->querymenu);
        $data['state']=strtolower($state);
        $data['keyword']='';
        $data['uri']=site_url('trans/po/show');
        switch(strtolower($state))
        {
            case 'unlock':
            {
                $this->session->set_userdata('redirect', $_SERVER['HTTP_REFERER']);
                $this->tmodel->po('unlock',$key);
                redirect($this->session->userdata('redirect'));
            }break;
            case 'open':
            {
                $this->load->library('pagination');
                $limit = 10;
                $data['query2']=$this->tmodel->list_client();
                $pilih=$this->input->post('pilih');
                $data['pilih']=$pilih==''?$id:$pilih;
                if($key==''){
                    $key=10;
                    $id=10;
                }
                switch($pilih)
                {
                    case '0':
                    $userid=$this->input->post('userid');
                    $data['userid']=$userid==''?$key:$userid;
                    if($userid!='')
                    {
                        $key=$userid;
                        $id=0;
                    }
                    break;
                    case '1':
                    $tanggal=$this->input->post('tanggal');
                    $data['tanggal']=$tanggal==''?$key:$tanggal;
                    if($tanggal!='')
                    {
                        $key=$tanggal;
                        $id=1;
                    }
                    break;
                }
                $data['uri']=site_url('trans/po/open');
                $data['query']=$this->tmodel->po('open',$key,$id,(int)$init);
                $config['base_url'] = site_url('trans/po/open/'.$key.'/'.$id);
                $config['total_rows']=$this->tmodel->getTotalQuery();
                $config['per_page']=$limit;
                $config['uri_segment']=6;
                $this->pagination->initialize($config);

                $data['pagination']=$this->pagination->create_links();
                $data['add']=anchor('trans/po/show_supp/',img($this->image_add,'ADD'));
                $data['printer']=anchor('trans/po/rekap_dialog/',img($this->image_printer,'PRINTER'));
                $this->session->set_userdata('redirect', $_SERVER['HTTP_REFERER']);
            }break;
            case 'half':
            {
                $this->session->set_userdata('redirect', $_SERVER['HTTP_REFERER']);
                $this->tmodel->po('half',$key);
                redirect($this->session->userdata('redirect'));
            }
            break;
            case 'rekap_dialog':
            {
                $data['uri']=site_url('trans/po/rekap');
                $data['supp']=$this->tmodel->getSupp();
            }break;
            case 'rekap':
            {
                return $this->tmodel->po('rekap');
            }break;
            case 'download':
            {
                $data['page_title']='DOWNLOAD PO';
                $data['uri']=site_url('trans/po/download');
                //$this->tmodel->po('download');
                $date=$this->input->post('download');
                if($date!='')
                {
                    $this->tmodel->po('download');
                }
            }break;
            case 'email':
            {
                $this->session->set_userdata('redirect', $_SERVER['HTTP_REFERER']);
                $this->tmodel->po('email',$key,$id);
                redirect($this->session->userdata('redirect'));
            }break;
            case 'email_warn':
            {
                $this->session->set_userdata('redirect', $_SERVER['HTTP_REFERER']);
                $this->tmodel->po('email_warn',$key,$id);
                redirect($this->session->userdata('redirect'));
            }break;
            case 'show':
            {
                $this->load->library('pagination');
                $limit = 10;
                $data['query2']=$this->tmodel->list_client();
                $pilih=$this->input->post('pilih');
                $data['pilih']=$pilih==''?$id:$pilih;
                if($key==''){
                    $key=10;
                    $id=10;
                }
                switch($pilih)
                {
                    case '0':
                    $userid=$this->input->post('userid');
                    $data['userid']=$userid==''?$key:$userid;
                    if($userid!='')
                    {
                        $key=$userid;
                        $id=0;
                    }
                    break;
                    case '1':
                    $tanggal=$this->input->post('tanggal');
                    $data['tanggal']=$tanggal==''?$key:$tanggal;
                    if($tanggal!='')
                    {
                        $key=$tanggal;
                        $id=1;
                    }
                    break;
                }
               
                $data['query']=$this->tmodel->po('show',$key,$id,(int)$init);
                $config['base_url'] = site_url('trans/po/show/'.$key.'/'.$id);
                $config['total_rows']=$this->tmodel->getTotalQuery();
                $config['per_page']=$limit;
                $config['uri_segment']=6;
                $this->pagination->initialize($config);
                                
                $data['pagination']=$this->pagination->create_links();
                $data['add']=anchor('trans/po/show_supp/',img($this->image_add,'ADD'));
                $data['printer']=anchor('trans/po/rekap_dialog/',img($this->image_printer,'PRINTER'));
                $this->session->set_userdata('redirect', $_SERVER['HTTP_REFERER']);
            }break;
            case 'show_supp':
            {
                $data['url'] = site_url('trans/po/set_supp');
                $data['query']=$this->tmodel->getSupp();
                $data['query2']=$this->tmodel->list_client();
                $data['page_title']='PILIH SUPPLIER DAN CLIENT';
            }break;
            case 'set_supp':
            {
                $supp=$this->input->post('supp');
                $client=$this->input->post('userid');
                $newdata = array(
                   'supplier'  => $supp,
                   'client'=>$client
                );
                $this->session->set_userdata($newdata);
                $this->tmodel->po('delete_temp');
                redirect(site_url('trans/po/manual'));
            }
            case 'show_detail':
            {
                $data['query']=$this->tmodel->po('show_detail',$key);
                $data['query2']=$this->tmodel->list_product_supp_admin($id);
                $data['query3']=$this->tmodel->getPO($key);
                $data['uri']=site_url('trans/po/add/'.$key);
                $data['uri2']=site_url('trans/po/save/'.$key);
                $data['half']=anchor('trans/po/half/'.$key,img($this->image_half,'BAGI DUA'));
                //$supp=$this->input->post('supp');
                $newdata = array(
                   'supplier'  => $id
                );
                $this->session->set_userdata($newdata);
            }break;
            case 'manual':
            {
                //$query->free_result();
                $data['page_title'] = 'SURAT PESANAN KHUSUS (SPK)';
                $data['url'] = site_url('trans/po/manual_addtemp');
                $data['url2'] = site_url('trans/po/manual_confirm');
                $data['query']=$this->tmodel->list_product_supp_admin($this->session->userdata('supplier'));
               
                $data['table']=$this->tmodel->po('show_temp');
            }break;
            case 'manual_confirm':
            {
                //$tipe=$this->input->post('tipe');
                $id=$this->input->post('userid');
                $data['page_title'] = 'Confirmation';
                $data['client']=$this->tmodel->getCustInfo2($this->session->userdata('client'));
                $data['url'] = site_url('trans/po/manual_save/'.$id);
               
            }break;
            case 'manual_save':
            {
                $this->tmodel->po('manual_save',$key,$id);
            }break;
            case 'manual_delete':
            {
                $this->tmodel->po('manual_delete',$key);
            }break;
            case 'manual_addtemp':
            {
                 $this->tmodel->po('manual_addtemp');
            }break;
            case 'add':
            {
               $this->tmodel->po('add',$key);
            }
            break;
            case 'addtemp':
            {
                $this->tmodel->po('addtemp');
            }break;
            case 'save':
            {
                $this->tmodel->po('save',$key);
                //echo $this->session->userdata('redirect');
                //redirect($this->session->userdata('redirect'));
                redirect('trans/po/show');
            }break;
            case 'print':
            {
                $this->tmodel->po('print',$key);
            }break;
            case 'delete':
            {
                $this->tmodel->po('delete',$key);
            }break;
            case 'delete_detail':
            {
                $this->tmodel->po('delete_detail',$key);
            }break;
        }

	$this->template('trans/po',$data);
    }
    function spk($state='show_supp',$key=null,$id=null)
    {
        $this->auth();
        $data['page_content'] = 'trans/spk';
        $data['page_title']='PILIH SUPPLIER';

        $data['menu']=$this->db->query($this->querymenu);
        $data['state']=$state;
        $data['keyword']=$this->input->post('keyword');
        $data['uri']=site_url('trans/spk/');
        switch(strtolower($state))
        {
            case 'show':
            {
                $data['add']=anchor('trans/spk/add/',img($this->image_add,'ADD'));
                $data['query']= $this->tmodel->spk('show',$data['keyword']);
            }break;
            case 'check_order':
            {
                $this->load->library('pagination');
                $limit = 20;
                $data['query']= $this->tmodel->spk('check_order',$limit,(int)$key);
                $config['base_url'] = site_url('trans/spk/check_order');
                $config['total_rows']=$this->tmodel->getTotalQuery();
                $config['per_page']=$limit;
                $config['uri_segment']=4;
                $this->pagination->initialize($config);
                $data['page_title'] = 'Check Order';
                $data['pagination']=$this->pagination->create_links();
                
            }break;
            case 'show_supp':
            {
                //$data['page_content']='trans/spk';
                $data['url'] = site_url('trans/spk/set_supp');
                $data['query']=$this->tmodel->getSupp();
            }break;
            case 'set_supp':
            {
                $supp=$this->input->post('supp');
                $newdata = array(
                   'supplier'  => $supp
                );
                $this->session->set_userdata($newdata);
                $this->tmodel->spk('delete_temp');
                redirect(site_url('trans/spk/add'));
            }break;
            case 'add':
            {
                //$query->free_result();
                $data['page_title'] = 'SURAT PESANAN KHUSUS (SPK)';
                $data['url'] = site_url('trans/spk/addtemp');
                $data['url2'] = site_url('trans/spk/confirm');
                $data['query']=$this->tmodel->list_product_supp($this->session->userdata('supplier'));
                $data['table']=$this->tmodel->spk('show_temp');
            }break;
            case 'confirm':
            {
                $data['page_title'] = 'Confirmation';
                $data['client']=$this->tmodel->getCustInfo();
                $data['url'] = site_url('trans/spk/save');
            }break;
            case 'addtemp':
            {
                $this->tmodel->spk('addtemp');
            }break;
            case 'save':
                $this->tmodel->spk('save');
                break;
            case 'edit':
                $data['edit']=$this->tmodel->spk('edit',$kode);
                break;
            case 'delete':
                $this->tmodel->spk('delete',$key);
                break;
            case 'update':
                $this->tmodel->spk('update');
                break;
        }

	$this->template('trans/spk',$data);
    }
}
?>