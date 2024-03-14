<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Uploadfile extends CI_Controller {

	function __construct()
	{
		parent::__construct();
	    // load ci's Form and Url Helpers
		$this->load->helper(array('form', 'url'));
	}
	function index()
	{
		$this->load->view('upload_form_view', array('error' => ' ' ));
	}
	function file_upload()
	{
		//konfigurasi upload zip
		$config['upload_path'] = './uploads/zip/';
		$config['allowed_types'] = 'zip' || 'ZIP';
		$config['max_size']	= '';
		$config['overwrite'] = 'TRUE';
		$this->load->library('upload', $config);
		if ( ! $this->upload->do_upload())
		{
			$error = array('error' => $this->upload->display_errors());
			$this->load->view('upload_form_view', $error);

			echo "gagal upload";
		}
		else
		{
			$data = array('upload_data' => $this->upload->data());
			$zip = new ZipArchive;
			$file = $data['upload_data']['full_path'];
			chmod($file,0777);

			echo "berhasil upload";


		//mengambil no cabang dari orig_name => 96
		//$a = '96'; //ini statis hanya untuk test
		$upload_data = $this->upload->data();
		$nocab = substr($upload_data['orig_name'],2,2);
		$year  = "2015";
		$month = "07";
		//echo "no cabang : ".$nocab.'<br />';

		if ($zip->open($file) === TRUE) {
	     	
			echo "<br><br>berhasil open";

			if($zip->setPassword("DELTOMED")){
				$zip->extractTo('./uploads/unzip/'.$nocab.'');
		     	$zip->close();

		    	echo 'Extraction Success ...';	
				}else{
					echo "pass salah";
				}
	     	
			} 
		else 
		{
	     	echo 'Extraction Failed. Please check your connection and Try Again..';
		}

		
		}			
	}

	public function cek(){
		echo phpinfo();
	}
}