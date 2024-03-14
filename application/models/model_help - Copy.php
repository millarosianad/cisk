<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Model_help extends CI_Model 
{    
    public function proses_input_complain($dataSegment){

    	if ( function_exists( 'date_default_timezone_set' ) )
        date_default_timezone_set('Asia/Jakarta');        
        //$tgl_created='"'.date('H:i:s').'"';

        $id=$this->session->userdata('id');

        /*
		echo "var kode : ".$dataSegment['kode'];
		echo "<br>";
		echo "var namabarang : ".$dataSegment['namabarang'];
		echo "<br>";
		echo "var jumlah : ".$dataSegment['jumlah'];
		echo "<br>";
		echo "var untuk : ".$dataSegment['untuk'];
		echo "<br>";
		echo "var tglperol : ".$dataSegment['tglperol'];
		*/

		/*
		$dob1=trim($dataSegment['tglperol']);//$dob1='dd/mm/yyyy' format
        $dob_disp1=strftime('%Y-%m-%d',strtotime($dob1));
        */

        
        echo "<br>";
		//echo "var tglperol_2 : ".$dob_disp1;
/*
		echo "<br>";
		echo "var user : ".$dataSegment['user'];
		echo "<br>";
		echo "var email : ".$dataSegment['email'];
		echo "<br>";
		echo "var kontak : ".$dataSegment['kontak'];
		echo "<br>";
		echo "var grup : ".$dataSegment['grup'];
		echo "<br>";
		echo "var masalah : ".$dataSegment['masalah'];
		//echo "<br>";
		//echo "var kode_tiket : ".$dataSegment['masalah']$dataSegment['masalah'];
*/	
		//query insert data ke tabel mpm.tbl_complain_system			

	    $data = array(
               'tgl_ajuan'      => date('Y-m-d H:i:s'),
               'userid_pelapor' => $id,
               'nama_pelapor' 	=> $dataSegment['user'],
               'kontak_pelapor' => $dataSegment['kontak'],
               'email_pelapor'  => $dataSegment['email'],
               'id_kategori'    => $dataSegment['grup'],
               'masalah'        => $dataSegment['masalah'],
               'file'           => $dataSegment['image'],
               'id_status' 	    => '1'
        	);

	   	$proses = $this->db->insert('mpm.tbl_complain_system',$data);

	   	if ($proses == '1') 
	   	{
	   		echo "insert success";
	   		
	   		$this->email_input($data);
	   		redirect('all_help/view_complain_user','refresh');
		}
		else
		{
			return array(); 
		}
		
	}

	public function getGrupassetcombo()
    {
        $sql='select id,nama_kategori from mpm.tbl_kategori_complain';
        return $this->db->query($sql);
    }

    public function getFlag()
    {
        //$sql='select id,nama from mpm.tbl_flag';
        //return $this->db->query($sql);

        $id = $this->uri->segment(3);

        if ($id != NULL) {
			
			$sql= "
	        select  a.id, a.nama_status
			from	mpm.tbl_status_complain a
						LEFT JOIN mpm.tbl_complain_system b
							on a.id = b.id_status
			ORDER BY b.id = $id desc
			";

		} else {
			
			$sql= "
	        select  a.id, a.nama_status
			from	mpm.tbl_status_complain a
						LEFT JOIN mpm.tbl_complain_system b
							on a.id = b.id_status
			ORDER BY a.id asc
			";

		}       
		/*
		echo "<pre><br><br>";
		print_r($sql);
		echo "</pre>";
        */
		return $this->db->query($sql);

    }

    public function view_complain()
    {
		$id=$this->session->userdata('id');
        $this->db->order_by("tbl_complain_system.id", "desc");
        //$this->db->where('userid_pelapor', $id);
        $this->db->join('mpm.tbl_kategori_complain', 'tbl_kategori_complain.id = tbl_complain_system.id_kategori','left');
        $this->db->join('mpm.tbl_status_complain', 'tbl_complain_system.id_status = tbl_status_complain.id','left');
        $this->db->select('tbl_complain_system.id, tbl_complain_system.userid_pelapor, tbl_complain_system.nama_pelapor, tbl_complain_system.tgl_ajuan, tbl_kategori_complain.nama_kategori, tbl_complain_system.masalah,tbl_status_complain.nama_status,tbl_complain_system.file');
        //$this->db->where('tbl_complain_system.userid_pelapor',$id);
        $hasil = $this->db->get('mpm.tbl_complain_system');

		if ($hasil->num_rows() > 0) 
		{
			return $hasil->result();
		} else {
			return array();
		}
    }

    public function view_complain_user()
    {
    	$id=$this->session->userdata('id');
		$this->db->order_by("tbl_complain_system.id", "desc");
        $this->db->where('userid_pelapor', $id);
		$this->db->join('mpm.tbl_kategori_complain', 'tbl_kategori_complain.id = tbl_complain_system.id_kategori','left');
		$this->db->join('mpm.tbl_status_complain', 'tbl_complain_system.id_status = tbl_status_complain.id','left');
		$this->db->select('tbl_complain_system.id, tbl_complain_system.userid_pelapor, tbl_complain_system.nama_pelapor, tbl_complain_system.tgl_ajuan, tbl_kategori_complain.nama_kategori, tbl_complain_system.masalah,tbl_status_complain.nama_status,tbl_complain_system.file');
		$this->db->where('tbl_complain_system.userid_pelapor',$id);
		$hasil = $this->db->get('mpm.tbl_complain_system');

		if ($hasil->num_rows() > 0) 
		{
			return $hasil->result();
		} else {
			return array();
		}
    }

    public function detail_complain(){

    	$id = $this->uri->segment(3);

    	//echo br(10);

    	//echo "id : ".$id;
    	$this->db->where("tbl_complain_system.id = ".$id);
        $this->db->join('mpm.tbl_status_complain', 'tbl_status_complain.id = tbl_complain_system.id_status','left');
    	$this->db->join('mpm.tbl_kategori_complain', 'tbl_kategori_complain.id = tbl_complain_system.id_kategori','left');
        $this->db->select('tbl_complain_system.id as id_tiket, tbl_complain_system.userid_pelapor, tbl_complain_system.nama_pelapor, tbl_complain_system.email_pelapor, tbl_kategori_complain.id, tbl_kategori_complain.nama_kategori, tbl_status_complain.id, tbl_status_complain.nama_status, tbl_complain_system.masalah, tbl_complain_system.file, tbl_complain_system.user_it, tbl_complain_system.nama_it, tbl_complain_system.solusi, tbl_complain_system.tgl_selesai, tbl_complain_system.id_status, tbl_complain_system.tgl_ajuan, tbl_complain_system.id_kategori, tbl_complain_system.kontak_pelapor');
    	$hasil = $this->db->get('mpm.tbl_complain_system');
		
		if ($hasil->num_rows() > 0) 
		{
			return $hasil->result();
		} else {
			return array();
		}

    }

    public function proses_update($dataasset){

    	//echo "tglperol : ".$dataasset['tglperol']."<br>";

    	$dob1=trim($dataasset['tgl_selesai']);//$dob1='dd/mm/yyyy' format
        $dob_disp1=strftime('%Y-%m-%d',strtotime($dob1));

        //echo "tgl_selesai : ".$dob_disp1;
   	
    	$data = array(
                'id'       		=> $dataasset['id'],
                'nama_it'       => $dataasset['nama_it'],
                'id_status'     => $dataasset['id_status'],
                'tgl_selesai'  	=> $dob_disp1,
                'solusi'      	=> $dataasset['solusi']
            );
        
        echo "<br>id : ".$dataasset['id']."<br>";
    	echo "nama_it : ".$dataasset['nama_it']."<br>";
		echo "id_status : ".$dataasset['id_status']."<br>";
    	echo "tgl_selesai : ".$dob_disp1."<br>";
    	echo "solusi : ".$dataasset['solusi']."<br>";
    	echo "idnya : ".$data['id'];
    	
    	//Query update
    	$hasil = $this->db->where('id',$dataasset['id'])
    			 ->update('mpm.tbl_complain_system', $data);
    	
    	if ($hasil == '1') 
			{
				//echo "update berhasil";
				$this->email_edit($data);
				redirect('all_help/view_complain');
			} else {
				return array();
			}
		
    }

    public function proses_delete($id){
    	
    	$this->db->where('id', $id)
    			 ->delete('mpm.tbl_complain_system');
    }

    private function email_config()
    {
         $config['protocol']  = 'smtp';
         $config['smtp_host'] = 'ssl://smtp.gmail.com';
         $config['smtp_port'] = '465';
         $config['smtp_timeout'] = '3000';
         $config['smtp_user'] = 'muliaputramandiri@gmail.com';
         $config['smtp_pass'] = 'mpmdelto12345';
         $config['charset']  = 'utf-8';
         $config['newline']  = "\r\n";
         $config['use_ci_email'] = TRUE;
         $config['mailtype'] = 'html';

         $this->email->initialize($config);
    }

    public function email_input($data){
        
        /* cari nama kategori */
	        $this->db->where('id = '.'"'.$data['id_kategori'].'"');
	        $query = $this->db->get('mpm.tbl_kategori_complain');
	        foreach ($query->result() as $row) {
	            $nama_kategori = $row->nama_kategori;
	            //echo "nama_kategori : ".$nama_kategori."<br>";
        }
        /* end cari nama kategori */

        /* cari nama status */
	        $this->db->where('id = '.'"'.$data['id_status'].'"');
	        $query = $this->db->get('mpm.tbl_status_complain');
	        foreach ($query->result() as $row) {
	            $nama_status = $row->nama_status;
	            //echo "nama_kategori : ".$nama_status."<br>";
        }
        /* end cari nama kategori */


        $this->email_config();

        /* jika tidak ada attachment */

    	if ($data['file'] == "tidak ada") {
    		
    	} else {
    		$this->email->attach('uploads/'.$data['file'].'');
    	}

        /* end jika tidak ada attachment */

        //$this->email->attach('uploads/'.$data['image'].'');
        
        /*
        echo "Userid pelapor : ".$data['userid_pelapor']."<br>";
        echo "Nama pelapor : ".$data['nama_pelapor']."<br>";
        echo "Email pelapor : ".$data['email_pelapor']."<br>";        
        echo "Kontak pelapor : ".$data['kontak_pelapor']."<br>";
        echo "Tgl ajuan : ".$data['tgl_ajuan']."<br>";
		echo "Kategori : ".$nama_kategori."<br>";
       	echo "Permasalahan : ".$data['masalah']."<br>";
        echo "Status : ".$nama_status."<br>";
        echo "File : ".$data['file']."<br>";
		*/
        $isi = "        
        Dear Bpk / Ibu ".$data['nama_pelapor'].",<br><br>

        Berikut adalah detail informasi yang telah anda masukkan ke dalam menu Complaine System :
        <br><br>
        <i><h3>Data Pelapor</h3></i><hr>
        User ID 				: ".$data['userid_pelapor']." \r\n <br>
        Nama 					: ".$data['nama_pelapor']." \r\n <br>
        Email 					: ".$data['email_pelapor']." \r\n <br>
        kontak 					: ".$data['kontak_pelapor']." \r\n <br>
        Tgl pengajuan 			: ".$data['tgl_ajuan']." \r\n <br>
        Kategori permasalahan	: ".$nama_kategori." \r\n <br>
        Detail permasalahan		: ".$data['masalah']." \r\n <br>
        status permasalahan 	: ".$nama_status." \r\n <br>
        Image / Lampiran 		: ".$data['file']." \r\n \r\n <hr><br>

        (*) Email ini akan otomatis terkirim ke IT MPM, mohon tunggu informasi selanjutnya. \r\n \r\n <br><br>

        Terima kasih<br>
        ";

        //print_r($isi);

        $this->email->to($data['email_pelapor']);
        //isi dengan email IT
        $this->email->cc('suffy.yanuar@gmail.com,ninol_cute@yahoo.com,hendyfaturahman@gmail.com');
        //$this->email->cc('suffy.yanuar@gmail.com');
        
        $this->email->from('muliaputramandiri@gmail.com','Automatic Email - MPM Site');
        $this->email->subject('Informasi Penginputan - Complaine System');
        $this->email->message($isi);
        //$this->email->attach('LOKASI_FOLDER_FILE/NAMA_FILE_attachment.pdf');
        $this->email->send();
        //echo $this->email->print_debugger();
        redirect('all_help/view_complain_user','refresh');
    }

    public function email_edit($data){
        /*
        $data = array(
                'id'       		=> $dataasset['id'],
                'nama_it'       => $dataasset['nama_it'],
                'flag_selesai'  => $dataasset['flag_selesai'],
                'tgl_selesai'  	=> $dob_disp1,
                'solusi'      	=> $dataasset['solusi']
            );
		*/
        /* cari nama kategori */
	        $this->db->where('tbl_complain_system.id = '.'"'.$data['id'].'"');
	        $this->db->join('mpm.tbl_kategori_complain','mpm.tbl_kategori_complain.id = mpm.tbl_complain_system.id_kategori','left');
	        $this->db->select('tbl_complain_system.id,tbl_complain_system.id_kategori,tbl_kategori_complain.id,tbl_kategori_complain.nama_kategori');
	        $query = $this->db->get('mpm.tbl_complain_system');
	        foreach ($query->result() as $row) {
	            $nama_kategori = $row->nama_kategori;
	            //echo "nama_kategori : ".$nama_kategori."<br>";
        }
        /* end cari nama kategori */

        /* cari nama image */
	        $this->db->where('id = '.'"'.$data['id'].'"');
	        $query = $this->db->get('mpm.tbl_complain_system');
	        foreach ($query->result() as $row) {
	            $image = $row->file;
	            $nama_pelapor = $row->nama_pelapor;
	            $userid_pelapor = $row->userid_pelapor;
	            $email_pelapor = $row->email_pelapor;
	            $kontak_pelapor = $row->kontak_pelapor;
	            $tgl_ajuan = $row->tgl_ajuan;
	            $masalah = $row->masalah;
	            $nama_it = $row->nama_it;
	            $solusi = $row->solusi;
	            $tgl_selesai = $row->tgl_selesai;
	            //echo "nama_kategori : ".$nama_status."<br>";

                if ($tgl_selesai == "1970-01-01") {
                     $tgl_selesai = "belum ditentukan";
                } else {
                    $tgl_selesai = $row->tgl_selesai;
                }
                
        }
        /* end cari nama kategori */

        /* cari nama status */
	        $this->db->where('id = '.'"'.$data['id_status'].'"');
	        $query = $this->db->get('mpm.tbl_status_complain');
	        foreach ($query->result() as $row) {
	            $nama_status = $row->nama_status;

	            //echo "nama_kategori : ".$nama_status."<br>";
        }
        /* end cari nama kategori */


        $this->email_config();
        /* jika tidak ada attachment */

    	if ($image == "tidak ada") {
    		
    	} else {
    		$this->email->attach('uploads/'.$data['image'].'');
    	}

        /* end jika tidak ada attachment */
        /*
        echo "id tiket : ".$data['id']."<br>";
        echo "userid pelapor : ".$userid_pelapor."<br>";
        echo "nama pelapor : ".$nama_pelapor."<br>";
        echo "email pelapor : ".$email_pelapor."<br>";        
        echo "kontak pelapor : ".$kontak_pelapor."<br>";
        echo "tgl ajuan : ".$tgl_ajuan."<br>";
		echo "kategori : ".$nama_kategori."<br>";
       	echo "permasalahan : ".$masalah."<br>";
        echo "status : ".$nama_status."<br>";
        echo "image : ".$image."<br>";
		*/
        $isi = "

        Dear Bpk / Ibu ".$nama_pelapor.",<br><br>

        Berikut adalah Update Informasi yang berasal dari menu Complaine System :
        <br><br>
        <i><h3>Data Pelapor</h3></i><hr>
        Nama 					: ".$nama_pelapor." \r\n <br>
        Email 					: ".$email_pelapor." \r\n <br>
        kontak 					: ".$kontak_pelapor." \r\n <br>
        Tgl pengajuan 			: ".$tgl_ajuan." \r\n <br>
        Kategori permasalahan	: ".$nama_kategori." \r\n <br>
        Detail permasalahan		: ".$masalah." \r\n <br>
        status permasalahan 	: ".$nama_status." \r\n <br>
        Image / Lampiran 		: ".$image." \r\n \r\n <hr><br>

        <i><h3>Data IT</h3></i><hr>
        Nama 					: ".$nama_it." \r\n <br>
        solusi 					: ".$solusi." \r\n <br>
        tanggal selesai 		: ".$tgl_selesai." \r\n <br>

        (*) Email ini akan otomatis terkirim ke IT MPM, mohon tunggu informasi selanjutnya. \r\n \r\n <br><br>

        Terima kasih<br>
        ";

        $this->email->to($email_pelapor);
        $this->email->cc('suffy.yanuar@gmail.com,ninol_cute@yahoo.com,hendyfaturahman@gmail.com');
        $this->email->from('muliaputramandiri@gmail.com','Automatic Email - MPM Site');
        $this->email->subject('Informasi Penginputan - Complaine System');
        $this->email->message($isi);
        //$this->email->attach('LOKASI_FOLDER_FILE/NAMA_FILE_attachment.pdf');
        $this->email->send();
        //echo $this->email->print_debugger();

        redirect('all_help/view_complain','refresh');

    }

}