<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Unzip extends MY_Controller {

	public function index()
	{
		$zip = new ZipArchive;

		//$file = $data[$base_url];
		//$file = $data['nopassword'];
		echo $base_url;

		

		chmod($file,0777);
		if ($zip->open('nopassword.zip') === TRUE) {
		     $zip->extractTo('./hasil/');
		     $zip->close();
		     echo 'ok';
		} else {
		     echo 'failed';
		}

	}
}
/* End of file unzip.php */
/* Location: ./application/controllers/unzip.php */