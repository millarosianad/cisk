<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Model_data extends CI_Model {

	public function create($data_files){
		//query insert into
		$this->db->insert('tabfile', $data_files);
	}

	public function all()
	{
		//query semua record di tabel file
		$hasil = $this->db->get('tabfile');

		if ($hasil -> num_rows() > 0 ) {
			return $hasil -> result();
		} else {
			return array();
		}
	}

		public function delete($id){
		//query delete ... where id = ...
		
		
		
		echo "id : ".$id."<br>";
		$query = $this->db->query('select path from mpm.tabfile where id = '.$id.'');
        foreach ($query->result() as $row)
        {
            //echo $row->path;
            $path_file = $row->path;
            unlink($path_file);
            //echo "<br>delete = ".$delete."";
            $this->db->where('id' ,$id)
				 ->delete('tabfile');
        }

        //delete_files('./path/to/directory/');
	}
}

/* End of file model_data.php */
/* Location: ./application/models/model_data.php */