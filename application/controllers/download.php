<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Download extends MY_Controller {

	public function __construct()
	{
		parent::__construct();
		//Do your magic here
		$this->load->helper(array('form', 'url', 'file', 'date'));
        $this->load->model('model_data');
	}

	public function index()
	{
		$logged_in= $this->session->userdata('logged_in');
            if(!isset($logged_in) || $logged_in != TRUE)
            {
                redirect('login/','refresh');
            }     

        $this->load->view('download/navbar');
        $this->load->view('download/form_upload_data', array('error' => ' ' ));
	}

    public function report()
    {
        $logged_in= $this->session->userdata('logged_in');
            if(!isset($logged_in) || $logged_in != TRUE)
            {
                redirect('login/','refresh');
            }     

        $this->load->view('download/navbar');
        $this->load->view('download/form_upload_data', array('error' => ' ' ));
    }

    public function create()
    {
        $this->load->view('download/navbar');
        $logged_in= $this->session->userdata('logged_in');
            if(!isset($logged_in) || $logged_in != TRUE)
            {
                redirect('login/','refresh');
            }
            if(!is_dir('./assets/uploads/download/'.date('Ymd').'/'))
            {
                @mkdir('./assets/uploads/download/'.date('Ymd').'/',0777);
            }


        //validation
        $this->form_validation->set_rules('title', 'Title', 'required');


        if($this->form_validation->run() == FALSE)
        {
            $this->load->view('download/form_upload_data');
        }
        else
        {   
            //konfigurasi upload zip
            $config['upload_path'] = './assets/uploads/download/'.date('Ymd').'';
            $config['allowed_types'] = 'zip|ZIP|txt|pdf|doc|xls|jpg|gif|png|docx|xlsx';
            $config['max_size'] = '';
            $config['overwrite'] = 'TRUE';

            $this->load->library('upload', $config);
            if ( ! $this->upload->do_upload())
            {
                $error = array('error' => $this->upload->display_errors());

                //$this->load->view('download/form_upload_data', $error);
                $this->load->view('download/view_all_file' ,$error);
            }
            else
            {
                $upload_data = $this->upload->data();
                $tanggal = date('Y-m-d H:i:s');
                
                //mengambil data upload
                $nama_file = $upload_data['orig_name'];
                $title  = $this->input->post('title');
                $path_download = $upload_data['full_path'];

                //echo $path_download;


                $data_file = array(
                    'title' => set_value('title'),
                    'nama_file' => $nama_file,
                    'tanggal_upload' => $tanggal,
                    'path' => $path_download
                );
                $this->model_data->create($data_file);
                redirect('download/all','refresh');
            }          
        }
    }

    public function all()
    {   
        $logged_in= $this->session->userdata('logged_in');
        

        if(!isset($logged_in) || $logged_in != TRUE)
        {
            redirect('login/','refresh');
        }

        else
        {
            $this->load->view('download/navbar');        
            $data['files'] = $this->model_data->all();
            $this->load->view('download/view_all_file', $data);    
        }     

                 
    }

    public function delete($id){
        $this->model_data->delete($id);
        //redirect('download/all');
        //echo "path file nya : ".$path_download."<br>";
        //delete_files($id);
        //$cari_path = $this->db->query("select path from mpm.tabfile where id = $id");
        //echo $cari_path;

        redirect('download/all');
    }
}

/* End of file download.php */
/* Location: ./application/controllers/download.php */