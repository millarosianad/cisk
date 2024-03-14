<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Uploads extends MY_Controller {

		/*var $querymenu='';
        var $source ='';
        var $finale = '';
        var $folder='./assets/uploads/zip/';
      	*/
        
        /*private function template($view,$data)
        {
            $this->template->set_title('MPM SQUARE');
            $this->template->add_js('modules/skeleton.js');
            $this->template->add_css('modules/skeleton.css');
            $this->template->load_view($view, $data);
        }
        */

	function __construct()
	{
		parent::__construct();
	    // load ci's Form and Url Helpers
		$this->load->helper(array('form', 'url'));
	}
	function index()
	{
		
			$logged_in= $this->session->userdata('logged_in');
            if(!isset($logged_in) || $logged_in != TRUE)
            {
                redirect('login/','refresh');
            }
            /*
            $data['page_title'] = 'Welcome to MPM Distribution System';
            $data['page_content']='upload/upload_form';
            $data['menu']=$this->db->query($this->querymenu);
            $sql='select date_format(max(lastupload),"%d %M %Y, %T")as last from mpm.upload where userid=?';
            $query=$this->db->query($sql,array($this->session->userdata('id')));
            $data['upl']=$query->row();            
            $data['url'] = site_url('upload');
            $data['error']='';
            $this->template($data['page_content'],$data);
            $this->load->view('upload_form', array('error' => ' ' ));
			*/
        

            $this->load->view('upload/upload_form_view', array('error' => ' ' ));

	}
	function file_upload()
	{
		$logged_in= $this->session->userdata('logged_in');
            if(!isset($logged_in) || $logged_in != TRUE)
            {
                redirect('login/','refresh');
            }
            if(!is_dir('./assets/uploads/zip/'.date('Ym').'/'))
            {
                @mkdir('./assets/uploads/zip/'.date('Ym').'/',0777);
            }
		
		//konfigurasi upload zip
		$config['upload_path'] = './assets/uploads/zip/'.date('Ym').'';
		$config['allowed_types'] = 'zip' || 'ZIP';
		$config['max_size']	= '';
		$config['overwrite'] = 'TRUE';
		
		$this->load->library('upload', $config);
		if ( ! $this->upload->do_upload())
		{
			$error = array('error' => $this->upload->display_errors());

				//data dari file asli			
				/*$data['page_title'] = 'Upload Data';
                $data['page_content']='upload/upload_form';
                $data['menu']=$this->db->query($this->querymenu);
                $data['url'] = site_url('upload');
                $data['error']=$error;
                $this->template($data['page_content'],$data);
				*/
			$this->load->view('upload/upload_form_view', $error);
		}
		else
		{
			/*$data['page_title'] = 'Upload Data';
            $data['page_content']='upload/upload_success';
            $data['menu']=$this->db->query($this->querymenu);
            $data['url'] = site_url('upload');
            $data['upload_data']=$upload_data;
			*/

			$data = array('upload_data' => $this->upload->data());
			$zip = new ZipArchive;
			$file = $data['upload_data']['full_path'];
			chmod($file,0777);

			//judul halaman
			echo "<h1>Upload File</h1><hr>";

			//mengambil no cabang dari orig_name => 96
			$upload_data = $this->upload->data();
			$nocab = substr($upload_data['orig_name'],2,2);
			$year  = $this->input->post('year');
	        $month = substr($upload_data['orig_name'],4,2);
			//echo "no cabang : ".$nocab.'<br />';
	        //echo 'tahun'.$year.'<br />';
	        //echo 'bulan : '.$month.'<br />';

	        //proses ekstraksi zip
			if ($zip->open($file) === TRUE) {
		     	$zip->extractTo('./assets/uploads/unzip/'.$nocab.'');
		     	$zip->close();

		    	echo 'Extraction Success ...<br><hr><br>';
				} else {
		     	echo 'Extraction Failed. Please check your connection and Try Again..';
		     	redirect('uploads','refresh');
			}

			//$total = "select sum(tot1) from data$year.fi where nocab='$nocab' and bulan='$month'";
			//$total_hasil = $this->db->query($total)->result();
			//echo "<h3>Total Harga : ".$total_hasil.'</h3>'; 
			//echo "<br />";

			//$total_hasil = $this->db->query($total);

			//foreach ($total_hasil->result() as $row)
			//{
			//echo $row->tot1;
			//}


			//$data['msg']=$this->console($upload_data['orig_name']);
            
            //$this->delete_files(substr($upload_data['orig_name'],2,2));
            //$this->template($data['page_content'],$data);




			//url hasil ekstrak jika melihat di komputer
			//echo 'realpath : '.realpath('.').'<br><br>';
			$path_load = str_replace('\\','/',realpath('.'))."/assets/uploads/unzip/$nocab/";
			//echo '$PATH_LOAD : '.$path_load.'<br>';
		
		    //menampilkan file di dalam direktori $url;
			$dir    = $path_load;	
			$files2 = scandir($dir, 1);

			echo "<pre>";
				//$array = print_r($files2);		
			echo "</pre>";
			
			$sql_del='delete from data'.$year.'.fi where nodokjdi="XXXXXX"';
            $this->db->query($sql_del);
            $sql_upd="UPDATE data".$year.".tblang set custid=concat(nocab,kode_lang),compid=concat(kode_comp,kode_lang) where nocab=".$nocab;
            $this->db->query($sql_upd);
            $sql_pil_fi="update data".$year.".fi set kodeprod='020002' where kodeprod='020002 P'"; 
            $this->db->query($sql_pil_fi);
            $sql_pil_ri="update data".$year.".ri set kodeprod='020002' where kodeprod='020002 P'"; 
            
            $this->db->query($sql_pil_ri);
            $sql_madu_fi="update data".$year.".fi set kodeprod='060009' where kodeprod='06009'";
            $this->db->query($sql_madu_fi);
            $sql_madu_ri="update data".$year.".ri set kodeprod='060009' where kodeprod='06009'";
            $this->db->query($sql_madu_ri);
            $delete00fi = "DELETE  from data".$year.".fi where left(kodeprod,2)='00'";
            $this->db->query($delete00fi);
            $delete00ri = "DELETE  from data".$year.".ri where left(kodeprod,2)='00'";
            $this->db->query($delete00ri);
            
            $upl['userid']=$this->session->userdata('id');
            $upl['lastupload']=date('Y-m-d H:i:s');
            $upl['filename']=$filename."ZIP";
            $this->db->insert('mpm.upload',$upl);
            if ($this->db->trans_status() === FALSE)
            {
                $this->db->trans_rollback();
            }
            else
            {
                $this->db->trans_commit();
            }			
			
			//fungsi perulangan array
			for ($i=0;$i<count($files2);$i++){
				//mengambil 2 huruf awal dari file name
				$b = substr($files2[$i], 0,2);
				//echo "".$b."";

				//mencari file yang sesuai untuk di load ke database
					if ($b == "TB") 
						{
							//echo "<strong>File yang berawalan huruf TB yaitu : ".$files2[$i]."</strong><br>";

							switch ($files2[$i]) 
							{
								case 'TBRAYO~1.TXT':

									$tabel = "tbrayon";	
									echo "Loading data : ".$files2[$i];

									$file_path = $path_load.$files2[$i].'';
									//echo '$FILE_PATH : '.$file_path.'<br>';
									$sql_del='delete from data'.$year.'.'.$tabel.' where nocab='.$nocab.'';
			                        $this->db->query($sql_del);

									$load=mysql_query("LOAD DATA INFILE '$file_path' REPLACE INTO TABLE data$year.$tabel FIELDS TERMINATED BY ',' ENCLOSED BY '~' LINES TERMINATED BY '\\r\\n' SET NOCAB = '$nocab'") or die(mysql_error());
									if ($load){
										echo(".. Success ..");
										} else {
										echo(".. Failed .. Please check your connection and Try Again");
										}
									echo "<br />";
									break;

								case 'TBKOTA.TXT':										
									
									$tabel = "tbkota";	
									echo "Loading data : ".$files2[$i];

									$file_path = $path_load.$files2[$i].'';
									//echo '$FILE_PATH : '.$file_path.'<br>';
									$sql_del='delete from data'.$year.'.'.$tabel.' where nocab='.$nocab.'';
			                        $this->db->query($sql_del);

									$load=mysql_query("LOAD DATA INFILE '$file_path' REPLACE INTO TABLE data$year.$tabel FIELDS TERMINATED BY ',' ENCLOSED BY '~' LINES TERMINATED BY '\\r\\n' SET NOCAB = '$nocab'") or die(mysql_error());
									if ($load){
										echo(".. Success ..");
										} else {
										echo(".. Failed .. Please check your connection and Try Again");
										}
									echo "<br />";
									break;				
								
								default:
									
									$tabel = "tblang";	
									echo "Loading data : ".$files2[$i];

									$file_path = $path_load.$files2[$i].'';
									//echo '$FILE_PATH : '.$file_path.'<br>';
									$sql_del='delete from data'.$year.'.'.$tabel.' where nocab='.$nocab.'';
			                        $this->db->query($sql_del);

									$load=mysql_query("LOAD DATA INFILE '$file_path' REPLACE INTO TABLE data$year.$tabel FIELDS TERMINATED BY ',' ENCLOSED BY '~' LINES TERMINATED BY '\\r\\n' SET NOCAB = '$nocab'") or die(mysql_error());
									if ($load){
										echo(".. Success ..");
										} else {
										echo(".. Failed .. Please check your connection and Try Again");
										}
									echo "<br />";
									break;
							}

						}

					elseif ($b == "TA")
						{
							//echo "<strong>File yang berawalan huruf TA yaitu : ".$files2[$i]."</strong><br>";

							switch ($files2[$i]) 
							{
								case 'TABSALES.TXT':

									$tabel = "tabsales";	
									echo "Loading data : ".$files2[$i];

									$file_path = $path_load.$files2[$i].'';
									//echo '$FILE_PATH : '.$file_path.'<br>';
									$sql_del='delete from data'.$year.'.'.$tabel.' where nocab='.$nocab.'';
			                        $this->db->query($sql_del);

									$load=mysql_query("LOAD DATA INFILE '$file_path' REPLACE INTO TABLE data$year.$tabel FIELDS TERMINATED BY ',' ENCLOSED BY '~' LINES TERMINATED BY '\\r\\n' SET NOCAB = '$nocab'") or die(mysql_error());
									if ($load){
										echo(".. Success ..");
										} else {
										echo(".. Failed .. Please check your connection and Try Again");
										}
									echo "<br />";
									break;										
								
								default:									
										echo 'lainnya : '.$files2[$i].'';
									break;
							}
						}

					elseif ($b == "BD")
						{
							//echo "<strong>File yang berawalan huruf BD yaitu : ".$files2[$i]."</strong><br>";
							$tabel = "bd";	
							echo "Loading data : ".$files2[$i];

							$file_path = $path_load.$files2[$i].'';
							//echo '$FILE_PATH : '.$file_path.'<br>';
							$sql_del="delete from data$year.$tabel where nocab='$nocab' and bulan='$month'";
	                        $this->db->query($sql_del);                  

							$load=mysql_query("LOAD DATA INFILE '$file_path' REPLACE INTO TABLE data$year.$tabel FIELDS TERMINATED BY ',' ENCLOSED BY '~' LINES TERMINATED BY '\\r\\n' SET NOCAB = '$nocab', BULAN = '$month'") or die(mysql_error());
							if ($load){
								echo(".. Success ..");
								} else {
								echo(".. Failed .. Please check your connection and Try Again");
								}
							echo "<br />";
						}

					elseif ($b == "BG")
						{
							//echo "<strong>File yang berawalan huruf BG yaitu : ".$files2[$i]."</strong><br>";
							$tabel = "bg";	
							echo "Loading data : ".$files2[$i];

							$file_path = $path_load.$files2[$i].'';
							//echo '$FILE_PATH : '.$file_path.'<br>';
							$sql_del="delete from data$year.$tabel where nocab='$nocab' and bulan='$month'";
	                        $this->db->query($sql_del);                  

							$load=mysql_query("LOAD DATA INFILE '$file_path' REPLACE INTO TABLE data$year.$tabel FIELDS TERMINATED BY ',' ENCLOSED BY '~' LINES TERMINATED BY '\\r\\n' SET NOCAB = '$nocab', BULAN = '$month'") or die(mysql_error());
							if ($load){
								echo(".. Success ..");
								} else {
								echo(".. Failed .. Please check your connection and Try Again");
								}
							echo "<br />";
						}

					elseif ($b == "BP")
						{
							//echo "<strong>File yang berawalan huruf BP yaitu : ".$files2[$i]."</strong><br>";
							$tabel = "bp";	
							echo "Loading data : ".$files2[$i];

							$file_path = $path_load.$files2[$i].'';
							//echo '$FILE_PATH : '.$file_path.'<br>';
							$sql_del="delete from data$year.$tabel where nocab='$nocab' and bulan='$month'";
	                        $this->db->query($sql_del);                  

							$load=mysql_query("LOAD DATA INFILE '$file_path' REPLACE INTO TABLE data$year.$tabel FIELDS TERMINATED BY ',' ENCLOSED BY '~' LINES TERMINATED BY '\\r\\n' SET NOCAB = '$nocab', BULAN = '$month'") or die(mysql_error());
							if ($load){
								echo(".. Success ..");
								} else {
								echo(".. Failed .. Please check your connection and Try Again");
								}
							echo "<br />";
						}

					elseif ($b == "EH")
						{
							//echo "<strong>File yang berawalan huruf EH yaitu : ".$files2[$i]."</strong><br>";
							$tabel = "eh";	
							echo "Loading data : ".$files2[$i];

							$file_path = $path_load.$files2[$i].'';
							//echo '$FILE_PATH : '.$file_path.'<br>';
							$sql_del="delete from data$year.$tabel where nocab='$nocab' and bulan='$month'";
	                        $this->db->query($sql_del);                  

							$load=mysql_query("LOAD DATA INFILE '$file_path' REPLACE INTO TABLE data$year.$tabel FIELDS TERMINATED BY ',' ENCLOSED BY '~' LINES TERMINATED BY '\\r\\n' SET NOCAB = '$nocab', BULAN = '$month'") or die(mysql_error());
							if ($load){
								echo(".. Success ..");
								} else {
								echo(".. Failed .. Please check your connection and Try Again");
								}
							echo "<br />";
						}

					elseif ($b == "EI")
						{
							//echo "<strong>File yang berawalan huruf EI yaitu : ".$files2[$i]."</strong><br>";
							$tabel = "ei";	
							echo "Loading data : ".$files2[$i];

							$file_path = $path_load.$files2[$i].'';
							//echo '$FILE_PATH : '.$file_path.'<br>';
							$sql_del="delete from data$year.$tabel where nocab='$nocab' and bulan='$month'";
	                        $this->db->query($sql_del);                  

							$load=mysql_query("LOAD DATA INFILE '$file_path' REPLACE INTO TABLE data$year.$tabel FIELDS TERMINATED BY ',' ENCLOSED BY '~' LINES TERMINATED BY '\\r\\n' SET NOCAB = '$nocab', BULAN = '$month'") or die(mysql_error());
							if ($load){
								echo(".. Success ..");
								} else {
								echo(".. Failed .. Please check your connection and Try Again");
								}
							echo "<br />";
						}

					elseif ($b == "FH")
						{
							//echo "<strong>File yang berawalan huruf FH yaitu : ".$files2[$i]."</strong><br>";

							$tabel = "fh";	
							echo "Loading data : ".$files2[$i];

							$file_path = $path_load.$files2[$i].'';
							//echo '$FILE_PATH : '.$file_path.'<br>';
							$sql_del="delete from data$year.$tabel where nocab='$nocab' and bulan='$month'";
	                        $this->db->query($sql_del);                  

							$load=mysql_query("LOAD DATA INFILE '$file_path' REPLACE INTO TABLE data$year.$tabel FIELDS TERMINATED BY ',' ENCLOSED BY '~' LINES TERMINATED BY '\\r\\n' SET NOCAB = '$nocab', BULAN = '$month'") or die(mysql_error());
							if ($load){
								echo(".. Success ..");
								} else {
								echo(".. Failed .. Please check your connection and Try Again");
								}
							echo "<br />";
						}

					elseif ($b == "FI")
						{
							//echo "<strong>File yang berawalan huruf FI yaitu : ".$files2[$i]."</strong><br>";
							$tabel = "fi";	
							echo "Loading data : ".$files2[$i];

							$file_path = $path_load.$files2[$i].'';
							//echo '$FILE_PATH : '.$file_path.'<br>';
							$sql_del="delete from data$year.$tabel where nocab='$nocab' and bulan='$month'";
	                        $this->db->query($sql_del);                  

							$load=mysql_query("LOAD DATA INFILE '$file_path' REPLACE INTO TABLE data$year.$tabel FIELDS TERMINATED BY ',' ENCLOSED BY '~' LINES TERMINATED BY '\\r\\n' SET NOCAB = '$nocab', BULAN = '$month'") or die(mysql_error());
							if ($load){
								echo(".. Success ..");
								} else {
								echo(".. Failed .. Please check your connection and Try Again");
								}
							echo "<br />";
						}

					elseif ($b == "GH")
						{
							//echo "<strong>File yang berawalan huruf GI yaitu : ".$files2[$i]."</strong><br>";
							$tabel = "gh";	
							echo "Loading data : ".$files2[$i];

							$file_path = $path_load.$files2[$i].'';
							//echo '$FILE_PATH : '.$file_path.'<br>';
							$sql_del="delete from data$year.$tabel where nocab='$nocab' and bulan='$month'";
	                        $this->db->query($sql_del);                  

							$load=mysql_query("LOAD DATA INFILE '$file_path' REPLACE INTO TABLE data$year.$tabel FIELDS TERMINATED BY ',' ENCLOSED BY '~' LINES TERMINATED BY '\\r\\n' SET NOCAB = '$nocab', BULAN = '$month'") or die(mysql_error());
							if ($load){
								echo(".. Success ..");
								} else {
								echo(".. Failed .. Please check your connection and Try Again");
								}
							echo "<br />";
						}

					elseif ($b == "GI")
						{
							//echo "<strong>File yang berawalan huruf GI yaitu : ".$files2[$i]."</strong><br>";
							$tabel = "gi";	
							echo "Loading data : ".$files2[$i];

							$file_path = $path_load.$files2[$i].'';
							//echo '$FILE_PATH : '.$file_path.'<br>';
							$sql_del="delete from data$year.$tabel where nocab='$nocab' and bulan='$month'";
	                        $this->db->query($sql_del);                  

							$load=mysql_query("LOAD DATA INFILE '$file_path' REPLACE INTO TABLE data$year.$tabel FIELDS TERMINATED BY ',' ENCLOSED BY '~' LINES TERMINATED BY '\\r\\n' SET NOCAB = '$nocab', BULAN = '$month'") or die(mysql_error());
							if ($load){
								echo(".. Success ..");
								} else {
								echo(".. Failed .. Please check your connection and Try Again");
								}
							echo "<br />";
						}

					elseif ($b == "KO")
						{
							//echo "<strong>File yang berawalan huruf KO yaitu : ".$files2[$i]."</strong><br>";
							$tabel = "ko";	
							echo "Loading data : ".$files2[$i];

							$file_path = $path_load.$files2[$i].'';
							//echo '$FILE_PATH : '.$file_path.'<br>';
							$sql_del="delete from data$year.$tabel where nocab='$nocab' and bulan='$month'";
	                        $this->db->query($sql_del);                  

							$load=mysql_query("LOAD DATA INFILE '$file_path' REPLACE INTO TABLE data$year.$tabel FIELDS TERMINATED BY ',' ENCLOSED BY '~' LINES TERMINATED BY '\\r\\n' SET NOCAB = '$nocab', BULAN = '$month'") or die(mysql_error());
							if ($load){
								echo(".. Success ..");
								} else {
								echo(".. Failed .. Please check your connection and Try Again");
								}
							echo "<br />";
						}

					elseif ($b == "KR")
						{
							//echo "<strong>File yang berawalan huruf KR yaitu : ".$files2[$i]."</strong><br>";
							$tabel = "kr";	
							echo "Loading data : ".$files2[$i];

							$file_path = $path_load.$files2[$i].'';
							//echo '$FILE_PATH : '.$file_path.'<br>';
							$sql_del="delete from data$year.$tabel where nocab='$nocab' and bulan='$month'";
	                        $this->db->query($sql_del);                  

							$load=mysql_query("LOAD DATA INFILE '$file_path' REPLACE INTO TABLE data$year.$tabel FIELDS TERMINATED BY ',' ENCLOSED BY '~' LINES TERMINATED BY '\\r\\n' SET NOCAB = '$nocab', BULAN = '$month'") or die(mysql_error());
							if ($load){
								echo(".. Success ..");
								} else {
								echo(".. Failed .. Please check your connection and Try Again");
								}
							echo "<br />";
						}

					elseif ($b == "LD")
						{
							//echo "<strong>File yang berawalan huruf LD yaitu : ".$files2[$i]."</strong><br>";
							$tabel = "ld";	
							echo "Loading data : ".$files2[$i];

							$file_path = $path_load.$files2[$i].'';
							//echo '$FILE_PATH : '.$file_path.'<br>';
							$sql_del="delete from data$year.$tabel where nocab='$nocab' and bulan='$month'";
	                        $this->db->query($sql_del);                  

							$load=mysql_query("LOAD DATA INFILE '$file_path' REPLACE INTO TABLE data$year.$tabel FIELDS TERMINATED BY ',' ENCLOSED BY '~' LINES TERMINATED BY '\\r\\n' SET NOCAB = '$nocab', BULAN = '$month'") or die(mysql_error());
							if ($load){
								echo(".. Success ..");
								} else {
								echo(".. Failed .. Please check your connection and Try Again");
								}
							echo "<br />";
						}

					elseif ($b == "LH")
						{
							//echo "<strong>File yang berawalan huruf LH yaitu : ".$files2[$i]."</strong><br>";
							$tabel = "lh";	
							echo "Loading data : ".$files2[$i];

							$file_path = $path_load.$files2[$i].'';
							//echo '$FILE_PATH : '.$file_path.'<br>';
							$sql_del="delete from data$year.$tabel where nocab='$nocab' and bulan='$month'";
	                        $this->db->query($sql_del);                  

							$load=mysql_query("LOAD DATA INFILE '$file_path' REPLACE INTO TABLE data$year.$tabel FIELDS TERMINATED BY ',' ENCLOSED BY '~' LINES TERMINATED BY '\\r\\n' SET NOCAB = '$nocab', BULAN = '$month'") or die(mysql_error());
							if ($load){
								echo(".. Success ..");
								} else {
								echo(".. Failed .. Please check your connection and Try Again");
								}
							echo "<br />";
						}

					elseif ($b == "MH")
						{
							//echo "<strong>File yang berawalan huruf MH yaitu : ".$files2[$i]."</strong><br>";
							$tabel = "mh";	
							echo "Loading data : ".$files2[$i];

							$file_path = $path_load.$files2[$i].'';
							//echo '$FILE_PATH : '.$file_path.'<br>';
							$sql_del="delete from data$year.$tabel where nocab='$nocab' and bulan='$month'";
	                        $this->db->query($sql_del);                  

							$load=mysql_query("LOAD DATA INFILE '$file_path' REPLACE INTO TABLE data$year.$tabel FIELDS TERMINATED BY ',' ENCLOSED BY '~' LINES TERMINATED BY '\\r\\n' SET NOCAB = '$nocab', BULAN = '$month'") or die(mysql_error());
							if ($load){
								echo(".. Success ..");
								} else {
								echo(".. Failed .. Please check your connection and Try Again");
								}
							echo "<br />";
						}

					elseif ($b == "MP")
						{
							//echo "<strong>File yang berawalan huruf MP yaitu : ".$files2[$i]."</strong><br>";
							$tabel = "mp";	
							echo "Loading data : ".$files2[$i];

							$file_path = $path_load.$files2[$i].'';
							//echo '$FILE_PATH : '.$file_path.'<br>';
							$sql_del="delete from data$year.$tabel where nocab='$nocab' and bulan='$month'";
	                        $this->db->query($sql_del);                  

							$load=mysql_query("LOAD DATA INFILE '$file_path' REPLACE INTO TABLE data$year.$tabel FIELDS TERMINATED BY ',' ENCLOSED BY '~' LINES TERMINATED BY '\\r\\n' SET NOCAB = '$nocab', BULAN = '$month'") or die(mysql_error());
							if ($load){
								echo(".. Success ..");
								} else {
								echo(".. Failed .. Please check your connection and Try Again");
								}
							echo "<br />";
						}

					elseif ($b == "OD")
						{
							//echo "<strong>File yang berawalan huruf OD yaitu : ".$files2[$i]."</strong><br>";
							$tabel = "od";	
							echo "Loading data : ".$files2[$i];

							$file_path = $path_load.$files2[$i].'';
							//echo '$FILE_PATH : '.$file_path.'<br>';
							$sql_del="delete from data$year.$tabel where nocab='$nocab' and bulan='$month'";
	                        $this->db->query($sql_del);                  

							$load=mysql_query("LOAD DATA INFILE '$file_path' REPLACE INTO TABLE data$year.$tabel FIELDS TERMINATED BY ',' ENCLOSED BY '~' LINES TERMINATED BY '\\r\\n' SET NOCAB = '$nocab', BULAN = '$month'") or die(mysql_error());
							if ($load){
								echo(".. Success ..");
								} else {
								echo(".. Failed .. Please check your connection and Try Again");
								}
							echo "<br />";
						}

					elseif ($b == "OH")
						{
							//echo "<strong>File yang berawalan huruf OH yaitu : ".$files2[$i]."</strong><br>";
							$tabel = "oh";	
							echo "Loading data : ".$files2[$i];

							$file_path = $path_load.$files2[$i].'';
							//echo '$FILE_PATH : '.$file_path.'<br>';
							$sql_del="delete from data$year.$tabel where nocab='$nocab' and bulan='$month'";
	                        $this->db->query($sql_del);                  

							$load=mysql_query("LOAD DATA INFILE '$file_path' REPLACE INTO TABLE data$year.$tabel FIELDS TERMINATED BY ',' ENCLOSED BY '~' LINES TERMINATED BY '\\r\\n' SET NOCAB = '$nocab', BULAN = '$month'") or die(mysql_error());
							if ($load){
								echo(".. Success ..");
								} else {
								echo(".. Failed .. Please check your connection and Try Again");
								}
							echo "<br />";
						}

					elseif ($b == "PB")
						{
							//echo "<strong>File yang berawalan huruf PB yaitu : ".$files2[$i]."</strong><br>";
							$tabel = "pb";	
							echo "Loading data : ".$files2[$i];

							$file_path = $path_load.$files2[$i].'';
							//echo '$FILE_PATH : '.$file_path.'<br>';
							$sql_del="delete from data$year.$tabel where nocab='$nocab' and bulan='$month'";
	                        $this->db->query($sql_del);                  

							$load=mysql_query("LOAD DATA INFILE '$file_path' REPLACE INTO TABLE data$year.$tabel FIELDS TERMINATED BY ',' ENCLOSED BY '~' LINES TERMINATED BY '\\r\\n' SET NOCAB = '$nocab', BULAN = '$month'") or die(mysql_error());
							if ($load){
								echo(".. Success ..");
								} else {
								echo(".. Failed .. Please check your connection and Try Again");
								}
							echo "<br />";
						}

					elseif ($b == "PH")
						{
							//echo "<strong>File yang berawalan huruf PH yaitu : ".$files2[$i]."</strong><br>";
							$tabel = "ph";	
							echo "Loading data : ".$files2[$i];

							$file_path = $path_load.$files2[$i].'';
							//echo '$FILE_PATH : '.$file_path.'<br>';
							$sql_del="delete from data$year.$tabel where nocab='$nocab' and bulan='$month'";
	                        $this->db->query($sql_del);                  

							$load=mysql_query("LOAD DATA INFILE '$file_path' REPLACE INTO TABLE data$year.$tabel FIELDS TERMINATED BY ',' ENCLOSED BY '~' LINES TERMINATED BY '\\r\\n' SET NOCAB = '$nocab', BULAN = '$month'") or die(mysql_error());
							if ($load){
								echo(".. Success ..");
								} else {
								echo(".. Failed .. Please check your connection and Try Again");
								}
							echo "<br />";
						}

					elseif ($b == "QH")
						{
							echo "<strong>File yang berawalan huruf QH yaitu : ".$files2[$i]."</strong><br>";
						}

					elseif ($b == "QI")
						{
							echo "<strong>File yang berawalan huruf QI yaitu : ".$files2[$i]."</strong><br>";
						}

					elseif ($b == "RH")
						{
							//echo "<strong>File yang berawalan huruf RH yaitu : ".$files2[$i]."</strong><br>";
							$tabel = "rh";	
							echo "Loading data : ".$files2[$i];

							$file_path = $path_load.$files2[$i].'';
							//echo '$FILE_PATH : '.$file_path.'<br>';
							$sql_del="delete from data$year.$tabel where nocab='$nocab' and bulan='$month'";
	                        $this->db->query($sql_del);                  

							$load=mysql_query("LOAD DATA INFILE '$file_path' REPLACE INTO TABLE data$year.$tabel FIELDS TERMINATED BY ',' ENCLOSED BY '~' LINES TERMINATED BY '\\r\\n' SET NOCAB = '$nocab', BULAN = '$month'") or die(mysql_error());
							if ($load){
								echo(".. Success ..");
								} else {
								echo(".. Failed .. Please check your connection and Try Again");
								}
							echo "<br />";
						}

					elseif ($b == "RI")
						{
							//echo "<strong>File yang berawalan huruf RI yaitu : ".$files2[$i]."</strong><br>";
							$tabel = "ri";	
							echo "Loading data : ".$files2[$i];

							$file_path = $path_load.$files2[$i].'';
							//echo '$FILE_PATH : '.$file_path.'<br>';
							$sql_del="delete from data$year.$tabel where nocab='$nocab' and bulan='$month'";
	                        $this->db->query($sql_del);                  

							$load=mysql_query("LOAD DATA INFILE '$file_path' REPLACE INTO TABLE data$year.$tabel FIELDS TERMINATED BY ',' ENCLOSED BY '~' LINES TERMINATED BY '\\r\\n' SET NOCAB = '$nocab', BULAN = '$month'") or die(mysql_error());
							if ($load){
								echo(".. Success ..");
								} else {
								echo(".. Failed .. Please check your connection and Try Again");
								}
							echo "<br />";
						}

					elseif ($b == "SD")
						{
							//echo "<strong>File yang berawalan huruf SD yaitu : ".$files2[$i]."</strong><br>";
							$tabel = "sd";	
							echo "Loading data : ".$files2[$i];

							$file_path = $path_load.$files2[$i].'';
							//echo '$FILE_PATH : '.$file_path.'<br>';
							$sql_del="delete from data$year.$tabel where nocab='$nocab' and bulan='$month'";
	                        $this->db->query($sql_del);                  

							$load=mysql_query("LOAD DATA INFILE '$file_path' REPLACE INTO TABLE data$year.$tabel FIELDS TERMINATED BY ',' ENCLOSED BY '~' LINES TERMINATED BY '\\r\\n' SET NOCAB = '$nocab', BULAN = '$month'") or die(mysql_error());
							if ($load){
								echo(".. Success ..");
								} else {
								echo(".. Failed .. Please check your connection and Try Again");
								}
							echo "<br />";
						}

					elseif ($b == "SH")
						{
							//echo "<strong>File yang berawalan huruf SH yaitu : ".$files2[$i]."</strong><br>";
							$tabel = "sh";	
							echo "Loading data : ".$files2[$i];

							$file_path = $path_load.$files2[$i].'';
							//echo '$FILE_PATH : '.$file_path.'<br>';
							$sql_del="delete from data$year.$tabel where nocab='$nocab' and bulan='$month'";
	                        $this->db->query($sql_del);                  

							$load=mysql_query("LOAD DATA INFILE '$file_path' REPLACE INTO TABLE data$year.$tabel FIELDS TERMINATED BY ',' ENCLOSED BY '~' LINES TERMINATED BY '\\r\\n' SET NOCAB = '$nocab', BULAN = '$month'") or die(mysql_error());
							if ($load){
								echo(".. Success ..");
								} else {
								echo(".. Failed .. Please check your connection and Try Again");
								}
							echo "<br />";
						}

					elseif ($b == "SK")
						{
							//echo "<strong>File yang berawalan huruf SK yaitu : ".$files2[$i]."</strong><br>";
							$tabel = "sk";	
							echo "Loading data : ".$files2[$i];

							$file_path = $path_load.$files2[$i].'';
							//echo '$FILE_PATH : '.$file_path.'<br>';
							$sql_del="delete from data$year.$tabel where nocab='$nocab' and bulan='$month'";
	                        $this->db->query($sql_del);                  

							$load=mysql_query("LOAD DATA INFILE '$file_path' REPLACE INTO TABLE data$year.$tabel FIELDS TERMINATED BY ',' ENCLOSED BY '~' LINES TERMINATED BY '\\r\\n' SET NOCAB = '$nocab', BULAN = '$month'") or die(mysql_error());
							if ($load){
								echo(".. Success ..");
								} else {
								echo(".. Failed .. Please check your connection and Try Again");
								}
							echo "<br />";
						}

					elseif ($b == "SO")
						{
							//echo "<strong>File yang berawalan huruf SO yaitu : ".$files2[$i]."</strong><br>";
							$tabel = "so";	
							echo "Loading data : ".$files2[$i];

							$file_path = $path_load.$files2[$i].'';
							//echo '$FILE_PATH : '.$file_path.'<br>';
							$sql_del="delete from data$year.$tabel where nocab='$nocab' and bulan='$month'";
	                        $this->db->query($sql_del);                  

							$load=mysql_query("LOAD DATA INFILE '$file_path' REPLACE INTO TABLE data$year.$tabel FIELDS TERMINATED BY ',' ENCLOSED BY '~' LINES TERMINATED BY '\\r\\n' SET NOCAB = '$nocab', BULAN = '$month'") or die(mysql_error());
							if ($load){
								echo(".. Success ..");
								} else {
								echo(".. Failed .. Please check your connection and Try Again");
								}
							echo "<br />";
						}

					elseif ($b == "SP")
						{
							//echo "<strong>File yang berawalan huruf SP yaitu : ".$files2[$i]."</strong><br>";
							$tabel = "sp";	
							echo "Loading data : ".$files2[$i];

							$file_path = $path_load.$files2[$i].'';
							//echo '$FILE_PATH : '.$file_path.'<br>';
							$sql_del="delete from data$year.$tabel where nocab='$nocab' and bulan='$month'";
	                        $this->db->query($sql_del);                  

							$load=mysql_query("LOAD DATA INFILE '$file_path' REPLACE INTO TABLE data$year.$tabel FIELDS TERMINATED BY ',' ENCLOSED BY '~' LINES TERMINATED BY '\\r\\n' SET NOCAB = '$nocab', BULAN = '$month'") or die(mysql_error());
							if ($load){
								echo(".. Success ..");
								} else {
								echo(".. Failed .. Please check your connection and Try Again");
								}
							echo "<br />";
						}

					elseif ($b == "ST")
						{
							//echo "<strong>File yang berawalan huruf ST yaitu : ".$files2[$i]."</strong><br>";
							$tabel = "st";	
							echo "Loading data : ".$files2[$i];

							$file_path = $path_load.$files2[$i].'';
							//echo '$FILE_PATH : '.$file_path.'<br>';
							$sql_del="delete from data$year.$tabel where nocab='$nocab' and bulan='$month'";
	                        $this->db->query($sql_del);                  

							$load=mysql_query("LOAD DATA INFILE '$file_path' REPLACE INTO TABLE data$year.$tabel FIELDS TERMINATED BY ',' ENCLOSED BY '~' LINES TERMINATED BY '\\r\\n' SET NOCAB = '$nocab', BULAN = '$month'") or die(mysql_error());
							if ($load){
								echo(".. Success ..");
								} else {
								echo(".. Failed .. Please check your connection and Try Again");
								}
							echo "<br />";
						}

					elseif ($b == "TD")
						{
							//echo "<strong>File yang berawalan huruf TD yaitu : ".$files2[$i]."</strong><br>";
							$tabel = "td";	
							echo "Loading data : ".$files2[$i];

							$file_path = $path_load.$files2[$i].'';
							//echo '$FILE_PATH : '.$file_path.'<br>';
							$sql_del="delete from data$year.$tabel where nocab='$nocab' and bulan='$month'";
	                        $this->db->query($sql_del);                  

							$load=mysql_query("LOAD DATA INFILE '$file_path' REPLACE INTO TABLE data$year.$tabel FIELDS TERMINATED BY ',' ENCLOSED BY '~' LINES TERMINATED BY '\\r\\n' SET NOCAB = '$nocab', BULAN = '$month'") or die(mysql_error());
							if ($load){
								echo(".. Success ..");
								} else {
								echo(".. Failed .. Please check your connection and Try Again");
								}
							echo "<br />";
						}

					elseif ($b == "TG")
						{
							//echo "<strong>File yang berawalan huruf TG yaitu : ".$files2[$i]."</strong><br>";
							$tabel = "tg";	
							echo "Loading data : ".$files2[$i];

							$file_path = $path_load.$files2[$i].'';
							//echo '$FILE_PATH : '.$file_path.'<br>';
							$sql_del="delete from data$year.$tabel where nocab='$nocab' and bulan='$month'";
	                        $this->db->query($sql_del);                  

							$load=mysql_query("LOAD DATA INFILE '$file_path' REPLACE INTO TABLE data$year.$tabel FIELDS TERMINATED BY ',' ENCLOSED BY '~' LINES TERMINATED BY '\\r\\n' SET NOCAB = '$nocab', BULAN = '$month'") or die(mysql_error());
							if ($load){
								echo(".. Success ..");
								} else {
								echo(".. Failed .. Please check your connection and Try Again");
								}
							echo "<br />";
						}

					elseif ($b == "TH")
						{
							//echo "<strong>File yang berawalan huruf TH yaitu : ".$files2[$i]."</strong><br>";
							$tabel = "th";	
							echo "Loading data : ".$files2[$i];

							$file_path = $path_load.$files2[$i].'';
							//echo '$FILE_PATH : '.$file_path.'<br>';
							$sql_del="delete from data$year.$tabel where nocab='$nocab' and bulan='$month'";
	                        $this->db->query($sql_del);                  

							$load=mysql_query("LOAD DATA INFILE '$file_path' REPLACE INTO TABLE data$year.$tabel FIELDS TERMINATED BY ',' ENCLOSED BY '~' LINES TERMINATED BY '\\r\\n' SET NOCAB = '$nocab', BULAN = '$month'") or die(mysql_error());
							if ($load){
								echo(".. Success ..");
								} else {
								echo(".. Failed .. Please check your connection and Try Again");
								}
							echo "<br />";
						}

					elseif ($b == "TI")
						{
							//echo "<strong>File yang berawalan huruf TI yaitu : ".$files2[$i]."</strong><br>";
							$tabel = "ti";	
							echo "Loading data : ".$files2[$i];

							$file_path = $path_load.$files2[$i].'';
							//echo '$FILE_PATH : '.$file_path.'<br>';
							$sql_del="delete from data$year.$tabel where nocab='$nocab' and bulan='$month'";
	                        $this->db->query($sql_del);                  

							$load=mysql_query("LOAD DATA INFILE '$file_path' REPLACE INTO TABLE data$year.$tabel FIELDS TERMINATED BY ',' ENCLOSED BY '~' LINES TERMINATED BY '\\r\\n' SET NOCAB = '$nocab', BULAN = '$month'") or die(mysql_error());
							if ($load){
								echo(".. Success ..");
								} else {
								echo(".. Failed .. Please check your connection and Try Again");
								}
							echo "<br />";
						}

					elseif ($b == "TR")
						{
							//echo "<strong>File yang berawalan huruf TR yaitu : ".$files2[$i]."</strong><br>";
							$tabel = "tr";	
							echo "Loading data : ".$files2[$i];

							$file_path = $path_load.$files2[$i].'';
							//echo '$FILE_PATH : '.$file_path.'<br>';
							$sql_del="delete from data$year.$tabel where nocab='$nocab' and bulan='$month'";
	                        $this->db->query($sql_del);                  

							$load=mysql_query("LOAD DATA INFILE '$file_path' REPLACE INTO TABLE data$year.$tabel FIELDS TERMINATED BY ',' ENCLOSED BY '~' LINES TERMINATED BY '\\r\\n' SET NOCAB = '$nocab', BULAN = '$month'") or die(mysql_error());
							if ($load){
								echo(".. Success ..");
								} else {
								echo(".. Failed .. Please check your connection and Try Again");
								}
							echo "<br />";
						}

					elseif ($b == "TU")
						{
							//echo "<strong>File yang berawalan huruf TU yaitu : ".$files2[$i]."</strong><br>";
							$tabel = "tu";	
							echo "Loading data : ".$files2[$i];

							$file_path = $path_load.$files2[$i].'';
							//echo '$FILE_PATH : '.$file_path.'<br>';
							$sql_del="delete from data$year.$tabel where nocab='$nocab' and bulan='$month'";
	                        $this->db->query($sql_del);                  

							$load=mysql_query("LOAD DATA INFILE '$file_path' REPLACE INTO TABLE data$year.$tabel FIELDS TERMINATED BY ',' ENCLOSED BY '~' LINES TERMINATED BY '\\r\\n' SET NOCAB = '$nocab', BULAN = '$month'") or die(mysql_error());
							if ($load){
								echo(".. Success ..");
								} else {
								echo(".. Failed .. Please check your connection and Try Again");
								}
							echo "<br />";
						}

					elseif ($b == "VH")
						{
							//echo "<strong>File yang berawalan huruf VH yaitu : ".$files2[$i]."</strong><br>";
							$tabel = "vh";	
							echo "Loading data : ".$files2[$i];

							$file_path = $path_load.$files2[$i].'';
							//echo '$FILE_PATH : '.$file_path.'<br>';
							$sql_del="delete from data$year.$tabel where nocab='$nocab' and bulan='$month'";
	                        $this->db->query($sql_del);                  

							$load=mysql_query("LOAD DATA INFILE '$file_path' REPLACE INTO TABLE data$year.$tabel FIELDS TERMINATED BY ',' ENCLOSED BY '~' LINES TERMINATED BY '\\r\\n' SET NOCAB = '$nocab', BULAN = '$month'") or die(mysql_error());
							if ($load){
								echo(".. Success ..");
								} else {
								echo(".. Failed .. Please check your connection and Try Again");
								}
							echo "<br />";
						}

					elseif ($b == "VI")
						{
							//echo "<strong>File yang berawalan huruf VI yaitu : ".$files2[$i]."</strong><br>";
							$tabel = "vi";	
							echo "Loading data : ".$files2[$i];

							$file_path = $path_load.$files2[$i].'';
							//echo '$FILE_PATH : '.$file_path.'<br>';
							$sql_del="delete from data$year.$tabel where nocab='$nocab' and bulan='$month'";
	                        $this->db->query($sql_del);                  

							$load=mysql_query("LOAD DATA INFILE '$file_path' REPLACE INTO TABLE data$year.$tabel FIELDS TERMINATED BY ',' ENCLOSED BY '~' LINES TERMINATED BY '\\r\\n' SET NOCAB = '$nocab', BULAN = '$month'") or die(mysql_error());
							if ($load){
								echo(".. Success ..");
								} else {
								echo(".. Failed .. Please check your connection and Try Again");
								}
							echo "<br />";
						}

					else
						{
							echo "File etc : ".$files2[$i]."</strong><br>";	
						}


			}
			//echo "<br>";
			$sql='select format(sum(val),2) val from(
                select sum(tot1) val from data'.$year.'.fi where bulan='.$month.' and nocab='.$nocab.'
                union ALL
                select sum(tot1) val from data'.$year.'.ri where bulan='.$month.' and nocab='.$nocab.'
                )a';
            $query=$this->db->query($sql);
            $row=$query->row();
            $data['omzet']=$row->val;
            echo  '<h2>Omzet saat ini : Rp '.$data['omzet'].'</h2>';

		}

		$this->load->view('upload/upload_success_view');
	}
}
?>