<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Model_wilayah extends CI_Model 
{
	public function get_wilayah($data)
	{
		echo "<pre>";
		echo " -- model wilayah --<br>";
		echo "userid : ".$this->session->userdata('id');
		echo "userid : ".$data['userid'];
		echo "</pre>";

		$this->db->where('userid',$data['userid']);
		$hasil = $this->db->get('db_user.t_login_wilayah');
		if ($hasil->num_rows() > 0) {
			return $hasil->result();
			//print_r($hasil->result());
		} else {
			//return array();
			echo "<pre>tidak dapat mengambil hak akses wilayah..!</pre>";
		}
	}
}