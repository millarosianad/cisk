<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Model_status extends CI_Model 
{    
    
    public function view_status()
    {
    	/*
		$this->db->join('pusat.permit_detail', 'pusat.permit.id = pusat.permit_detail.id_ref','inner');
		$this->db->select('pusat.permit.id, kode_lang, nama_lang, tanggal, keterangan');
		$this->db->where('pusat.permit.deleted = 0');			
		$this->db->order_by("pusat.permit.id", "desc");
		$this->db->order_by("keterangan");
		$hasil = $this->db->get('pusat.permit',10);
		*/
		$this->db->order_by("tbl_status_proses_data.id asc");
		$this->db->join('mpm.tbl_afiliasi', 'mpm.tbl_afiliasi.id_afiliasi = mpm.tbl_status_proses_data.id_afiliasi','left');
		$this->db->join('mpm.tbl_detail_status', 'mpm.tbl_status_proses_data.status = mpm.tbl_detail_status.id','inner');
		$this->db->select('tbl_status_proses_data.id, tbl_status_proses_data.id_afiliasi,tbl_status_proses_data.tgl_update, tbl_status_proses_data.tgl_data, tbl_detail_status.ket_status, tbl_status_proses_data.keterangan, tbl_afiliasi.nama_afiliasi');
		$hasil = $this->db->get('mpm.tbl_status_proses_data');

		if ($hasil->num_rows() > 0) 
		{

			return $hasil->result();
		} else {
			return array();
		}
    }

    public function getGrupassetcombo()
    {
    	$id = $this->uri->segment(3);

		if ($id != NULL) {
			
			//$sql='select id,ket_status from mpm.tbl_detail_status order by id = '.$id.' desc';
			
			$sql='
			select b.id, ket_status 
			from mpm.tbl_status_proses_data a right JOIN mpm.tbl_detail_status b
							on a.`status` = b.id
			GROUP BY b.id
			ORDER BY a.id = '.$id.' desc';

		} else {
			
			 $sql='select id,ket_status from mpm.tbl_detail_status';

		}       
        return $this->db->query($sql);
    }

    public function list_afiliasi()
    {
    	$sql = "select * from mpm.tbl_afiliasi";
        return $this->db->query($sql);
    }

    public function proses_input_status($dataSegment){

    	if ( function_exists( 'date_default_timezone_set' ) )
        date_default_timezone_set('Asia/Jakarta');        
        //$tgl_created='"'.date('H:i:s').'"';

        $id=$this->session->userdata('id');

        
		echo "var id afiliasi : ".$dataSegment['id_afiliasi'];
		echo "<br>";
		echo "var tgldata : ".$dataSegment['tgldata'];
		echo "<br>";
		echo "var status : ".$dataSegment['status'];
		echo "<br>";
		echo "var keterangan : ".$dataSegment['keterangan'];
		
        $dob2=trim($dataSegment['tgldata']);//$dob1='dd/mm/yyyy' format
        $dob_tgldata=strftime('%Y-%m-%d',strtotime($dob2));
        $tahun = substr($dob_tgldata, 0,4);
       
        /*
		echo "<br>";
		echo "var tgldata_2 : ".$dob_tgldata;
		*/
		//query insert data ke tabel mpm.tbl_status_proses_data		

	    $data = array(
           'id_afiliasi' 	=> $dataSegment['id_afiliasi'],
           'tgl_update' 	=> date('Y-m-d H:i:s'),
           'tgl_data' 	=> $dob_tgldata,
           'status'		=> $dataSegment['status'],
           'keterangan' => $dataSegment['keterangan'],
           'tahun'		=> $tahun
    	);
	    
	   	$proses = $this->db->insert('mpm.tbl_status_proses_data',$data);
	    echo "<pre>";
	    print_r($proses);
	    echo "</pre>";
	    
	   if ($proses == '1') {
	   		echo "insert success";
	   		redirect('all_status_proses_data/view_status','refresh');
	   }
	   else
	   {
	   		return array(); 
	   }
		
	}

	public function detail_status_daily(){

		$id = $this->uri->segment(3);

    	//echo br(10);

    	//echo "id : ".$id;
    	$this->db->where("tbl_status_proses_data.id = ".$id);
    	$this->db->join('mpm.tbl_afiliasi', 'tbl_afiliasi.id_afiliasi = tbl_status_proses_data.id_afiliasi','left');
    	$this->db->join('mpm.tbl_detail_status', 'tbl_detail_status.id = tbl_status_proses_data.status','inner');
    	$hasil = $this->db->get('mpm.tbl_status_proses_data');
		
		if ($hasil->num_rows() > 0) 
		{
			return $hasil->result();
		} else {
			return array();
		}
	}

	public function proses_update_daily($datastatus){
		date_default_timezone_set('Asia/Jakarta');
		//echo "tglperol : ".$dataasset['tglperol']."<br>";

    	$dob1=trim($datastatus['tgl_data']);//$dob1='dd/mm/yyyy' format
        $dob_disp1=strftime('%Y-%m-%d',strtotime($dob1));

    	
    	$data = array(
    			'id' 			=> $datastatus['id'],
                
                'tgl_data'  	=> $dob_disp1,
                'status'  		=> $datastatus['status'],
                'keterangan'    => $datastatus['keterangan'],
                'tgl_update' 	=> date('Y-m-d H:i:s')
            );
    	    
        echo "id : ".$datastatus['id']."<br>";
    	//echo "id_afiliasi : ".$datastatus['id_afiliasi']."<br>";
		echo "tgl_data : ".$datastatus['tgl_data']."<br>";
    	echo "status : ".$datastatus['status']."<br>";
    	echo "keterangan : ".$datastatus['keterangan']."<br>";
    	

    	//Query update

    	$hasil = $this->db->where('id',$datastatus['id'])
    			 ->update('mpm.tbl_status_proses_data', $data);
    	
    	if ($hasil == '1') 
			{
				echo "update berhasil";
				//redirect('all_status_proses_data/','refresh');
			} else {
				return array();
			}

	}

	public function proses_delete_daily($id){
    	
    	$this->db->where('id', $id)
    			 ->delete('mpm.tbl_status_proses_data');
	}
	
	public function upload_stock($data, $subbranch)
	{

		// $kode = $data['kode'];
		if ($subbranch == 'SBTB5') {
			$this->db->insert_batch('db_stock.t_stock_baturaja_original', $data);

			$max_created_date = $this->db->query("select max(created_date) as created_date from db_stock.t_stock_baturaja_original")->result();
			foreach ($max_created_date as $a) {
				$created_date = $a->created_date;
			}

			if ($created_date == '') {
				$sql = "
						update db_stock.t_stock_baturaja_original a
						set a.kd_barang = concat('0',a.kd_barang)
						where length(a.kd_barang) = 5
					";
			} else {
				$sql = "
						update db_stock.t_stock_baturaja_original a
						set a.kd_barang = concat('0',a.kd_barang)
						where length(a.kd_barang) = 5 and a.created_date = '$created_date'
					";
			}
			$this->db->query($sql);
		} else if ($subbranch == 'SBJS7') {
			$this->db->insert_batch('db_stock.t_stock_palembang_original', $data);

			$max_created_date = $this->db->query("select max(created_date) as created_date from db_stock.t_stock_palembang_original")->result();
			foreach ($max_created_date as $a) {
				$created_date = $a->created_date;
			}

			if ($created_date == '') {
				$sql = "
						update db_stock.t_stock_palembang_original a
						set a.kd_barang = concat('0',a.kd_barang)
						where length(a.kd_barang) = 5
					";
			} else {
				$sql = "
						update db_stock.t_stock_palembang_original a
						set a.kd_barang = concat('0',a.kd_barang)
						where length(a.kd_barang) = 5 and a.created_date = '$created_date'
					";
			}
			$this->db->query($sql);
		} elseif ($subbranch == 'SBMS2') {
			$this->db->insert_batch('db_stock.t_stock_bangka_original', $data);

		} elseif ($subbranch == 'ACHA1') {
			$this->db->insert_batch('db_stock.t_stock_aceh_original', $data);

			$max_created_date = $this->db->query("select max(created_date) as created_date from db_stock.t_stock_aceh_original")->result();
			foreach ($max_created_date as $a) {
				$created_date = $a->created_date;
			}

			if ($created_date == '') {
				$sql = "
						update db_stock.t_stock_aceh_original a
						set a.kodeprod = a.kodeprod
					";
			} else {
				$sql = "
						update db_stock.t_stock_aceh_original a
						set a.kodeprod = a.kodeprod
						where a.created_date = '$created_date'
					";
			}
			$this->db->query($sql);

		}elseif ($subbranch == 'BTM65') {
			$this->db->insert_batch('db_stock.t_stock_batam_original', $data);

			$max_created_date = $this->db->query("select max(created_date) as created_date from db_stock.t_stock_batam_original")->result();
			foreach ($max_created_date as $a) {
				$created_date = $a->created_date;
			}

			if ($created_date == '') {
				$sql = "
						update db_stock.t_stock_batam_original a
						set a.kodeprod = a.kodeprod
					";
			} else {
				$sql = "
						update db_stock.t_stock_batam_original a
						set a.kodeprod = a.kodeprod
						where a.created_date = '$created_date'
					";
			}
			$this->db->query($sql);
		}elseif ($subbranch == 'SJDT5') {
			$this->db->insert_batch('db_stock.t_stock_tanjung_original', $data);

			$max_created_date = $this->db->query("select max(created_date) as created_date from db_stock.t_stock_tanjung_original")->result();
			foreach ($max_created_date as $a) {
				$created_date = $a->created_date;
			}

			if ($created_date == '') {
				$sql = "
						update db_stock.t_stock_tanjung_original a
						set a.kodeprod = a.kodeprod
					";
			} else {
				$sql = "
						update db_stock.t_stock_tanjung_original a
						set a.kodeprod = a.kodeprod
						where a.created_date = '$created_date'
					";
			}
			$this->db->query($sql);
		}else if ($subbranch == 'PDG33') {
			$this->db->insert_batch('db_stock.t_stock_padang_original', $data);
			// echo "<pre>";
			// echo "<br><br><br>";
			// echo "created_date : ".$created_date."<br>";
			// print_r($sql);
			// echo "</pre>";


		}elseif ($subbranch == 'SJDT5') {
			$this->db->insert_batch('db_stock.t_stock_batam_original', $data);

			$max_created_date = $this->db->query("select max(created_date) as created_date from db_stock.t_stock_batam_original")->result();
			foreach ($max_created_date as $a) {
				$created_date = $a->created_date;
			}

			if ($created_date == '') {
				$sql = "
						update db_stock.t_stock_batam_original a
						set a.kodeprod = a.kodeprod
					";
			} else {
				$sql = "
						update db_stock.t_stock_batam_original a
						set a.kodeprod = a.kodeprod
						where a.created_date = '$created_date'
					";
			}
			$this->db->query($sql);
		}elseif ($subbranch == 'SBMS2') {
			$this->db->insert_batch('db_stock.t_stock_bangka_original', $data);

			$max_created_date = $this->db->query("select max(created_date) as created_date from db_stock.t_stock_bangka_original")->result();
			foreach ($max_created_date as $a) {
				$created_date = $a->created_date;
			}

			if ($created_date == '') {
				$sql = "
						update db_stock.t_stock_bangka_original a
						set a.kodeprod = a.kodeprod
					";
			} else {
				$sql = "
						update db_stock.t_stock_bangka_original a
						set a.kodeprod = a.kodeprod
						where a.created_date = '$created_date'
					";
			}
			$this->db->query($sql);
		}elseif ($subbranch == 'PKB51') {
			$this->db->insert_batch('db_stock.t_stock_pekanbaru_original', $data);
		}
	}

	public function mapping_stock_palembang($kode)
	{
		date_default_timezone_set('Asia/Jakarta');
		$created_date = '"' . date('Y-m-d H:i:s') . '"';
		$id = $this->session->userdata('id');
		$sql = "
			select 	max(created_date) last_update
			from 	db_stock.t_stock_palembang_original a
			where 	a.kode = '$kode' and a.id = $id
		";
		$proses = $this->db->query($sql)->result();
		foreach ($proses as $a) {
			$last_update = $a->last_update;
		}

		$sql = "
		insert into db_stock.t_mapping_stock_palembang
		select 	a.kode,a.kd_cabang,a.kd_gudang,a.kd_paret,
				a.kd_barang,sum(a.so_awal),sum(a.masuk),sum(a.keluar),
				sum(a.so_akhir),b.kodeprod,b.isisatuan, 
				sum((a.so_awal / isisatuan)) as so_awal_mpm, 
				sum((a.masuk / isisatuan)) as masuk_mpm,
				sum((a.keluar / isisatuan)) as keluar_mpm,
				sum((a.so_akhir / isisatuan)) as so_akhir_mpm,$id,$created_date
		from 	db_stock.t_stock_palembang_original a LEFT JOIN db_stock.t_mapping_palembang b
					on a.kd_barang = b.kodeprod
		where	a.created_date = '$last_update' and a.id = $id
		GROUP BY kodeprod
		";
		$proses = $this->db->query($sql);

		// echo "<pre>";
		// echo "<br><br><br><br>";
		// print_r($sql);
		// echo "</pre>";

		$proses_tampil = $this->db->query("select * from db_stock.t_mapping_stock_palembang a where a.id = $id and a.created_date = $created_date")->result();

		return $proses_tampil;
	}

	public function stock_rilis_palembang($kode, $tahun, $bulan)
	{
		//jika bulan 1 karakter maka tambah 0 di depan
		if (strlen($bulan) == 1) {
			$bulan = '0'.$bulan;
		}else{
			$bulan = $bulan;
		}

		$nocab = substr($kode, 3, 2);
		$bulan_versi_st = substr($tahun, 2) . $bulan;

		$sql = "
			delete from data$tahun.st
			where bulan = $bulan_versi_st and nocab = '$nocab' 
		";
		$proses = $this->db->query($sql);
		// echo "<pre><br><br><br><br><br>";
		// print_r($sql);
		// echo "</pre>";

		if ($proses) {
			$sql = "
				insert into data$tahun.st
				select 	a.kodeprod, b.KODE_PRC, b.NAMAPROD,b.supp,b.GRUPPROD,
						b.SATUAN,b.ISISATUAN,'','',a.so_awal_mpm,
						a.masuk_mpm,'','','',a.keluar_mpm,'','','','','',
						'','','','','','','','','','',
						'','','','','','','','','','','',
						'$nocab', $bulan_versi_st
				from 	db_stock.t_mapping_stock_palembang a INNER JOIN mpm.tabprod b
							on a.kodeprod = b.KODEPROD
				where	a.kode = '$kode' and a.created_date = (select max(created_date) from db_stock.t_mapping_stock_palembang where kode ='$kode')
			";

			$proses = $this->db->query($sql);
			// echo "<pre>";
			// echo "<br><br><br><br><br><br>";
			// echo "tahun : ".$tahun."<br>";
			// echo "bulan : ".$bulan."<br>";
			// echo "bulan_versi_st : ".$bulan_versi_st."<br>";
			// print_r($sql);
			// echo "</pre>";
			if ($proses) {
				$sql = "select * from data$tahun.st where bulan = $bulan_versi_st and nocab = '$nocab' order by kodeprod asc";
				$proses = $this->db->query($sql)->result();
				return $proses;
			}
		}
		// echo "<pre>";
		// print_r($sql);
		// echo "</pre>";
	}
	

	public function get_upload_stock($tgl_created)
	{

		$id = $this->session->userdata('id');
		$sql = "
			select *
			from db_stock.t_stock_bangka_original a
			where created_date = '$tgl_created' and a.id = $id
		";
		$proses = $this->db->query($sql)->result();
		return $proses;
	}

	public function get_upload_stock_palembang($tgl_created)
	{

		$id = $this->session->userdata('id');
		$sql = "
			select *
			from db_stock.t_stock_palembang_original a
			where created_date = '$tgl_created' and a.id = $id
		";
		$proses = $this->db->query($sql)->result();
		return $proses;
	}

	public function get_upload_stock_baturaja($tgl_created)
	{

		$id = $this->session->userdata('id');
		$sql = "
			select *
			from db_stock.t_stock_baturaja_original a
			where created_date = '$tgl_created' and a.id = $id
		";
		$proses = $this->db->query($sql)->result();
		return $proses;
	}

	public function get_upload_stock_aceh($tgl_created)
	{

		$id = $this->session->userdata('id');
		$sql = "
			select *
			from db_stock.t_stock_aceh_original a
			where created_date = '$tgl_created' and a.id = $id
		";
		$proses = $this->db->query($sql)->result();
		return $proses;
	}

	public function get_upload_stock_batam($tgl_created)
	{

		$id = $this->session->userdata('id');
		$sql = "
			select *
			from db_stock.t_stock_batam_original a
			where created_date = '$tgl_created' and a.id = $id
		";
		$proses = $this->db->query($sql)->result();
		return $proses;
	}

	public function get_upload_stock_tanjung($tgl_created)
	{

		$id = $this->session->userdata('id');
		$sql = "
			select *
			from db_stock.t_stock_tanjung_original a
			where created_date = '$tgl_created' and a.id = $id
		";
		$proses = $this->db->query($sql)->result();
		return $proses;
	}

	public function get_upload_stock_bangka($tgl_created)
	{

		$id = $this->session->userdata('id');
		$sql = "
			select *
			from db_stock.t_stock_bangka_original a
			where created_date = '$tgl_created' and a.id = $id
		";
		$proses = $this->db->query($sql)->result();
		return $proses;
	}

	public function mapping_stock($kode)
	{
		date_default_timezone_set('Asia/Jakarta');
		$created_date = '"' . date('Y-m-d H:i:s') . '"';
		$id = $this->session->userdata('id');
		$sql = "
			select 	max(created_date) last_update
			from 	db_stock.t_stock_bangka_original a
			where 	a.kode = '$kode' and a.id = $id
		";
		$proses = $this->db->query($sql)->result();
		foreach ($proses as $a) {
			$last_update = $a->last_update;
		}

		$sql = "
		insert into db_stock.t_mapping_stock
		select 	a.kode, a.kodeprod_dp, a.namaprod_dp, a.isi, a.stock as karton,a.isi*a.stock as total_stock, 
				b.kodeprod_mpm,c.namaprod, c.ISISATUAN,	c.ISISATUAN * a.stock as total_stock_2,$id,$created_date
		from 	db_stock.t_stock_bangka_original a LEFT JOIN db_stock.t_mapping b
					on a.kodeprod_dp = b.kodeprod_dp LEFT JOIN mpm.tabprod c 
					on b.kodeprod_mpm = c.kodeprod
		where 	a.created_date = '$last_update' and a.id = $id
		";
		$proses = $this->db->query($sql);

		// echo "<pre>";
		// echo "<br><br><br><br>";
		// print_r($sql);
		// echo "</pre>";

		$proses_tampil = $this->db->query("select * from db_stock.t_mapping_stock a where a.id = $id and a.created_date = $created_date")->result();

		return $proses_tampil;
	}

	public function mapping_stock_baturaja($kode)
	{
		date_default_timezone_set('Asia/Jakarta');
		$created_date = '"' . date('Y-m-d H:i:s') . '"';
		$id = $this->session->userdata('id');
		$sql = "
			select 	max(created_date) last_update
			from 	db_stock.t_stock_baturaja_original a
			where 	a.kode = '$kode' and a.id = $id
		";
		$proses = $this->db->query($sql)->result();
		foreach ($proses as $a) {
			$last_update = $a->last_update;
		}


		$sql = "
		insert into db_stock.t_mapping_stock_baturaja
		select 	a.kode,a.kd_cabang,a.kd_gudang,a.kd_paret,
				a.kd_barang,a.so_awal,a.masuk,a.keluar,
				a.so_akhir,b.kodeprod,b.isisatuan, 
				(a.so_awal / isisatuan) as so_awal_mpm, 
				(a.masuk / isisatuan) as masuk_mpm,
				(a.keluar / isisatuan) as keluar_mpm,
				(a.so_akhir / isisatuan) as so_akhir_mpm,$id,$created_date
		from 	db_stock.t_stock_baturaja_original a LEFT JOIN db_stock.t_mapping_baturaja b
					on a.kd_barang = b.kodeprod
		where	a.created_date = '$last_update' and a.id = $id
		";
		$proses = $this->db->query($sql);

		// echo "<pre>";
		// echo "<br><br><br><br>";
		// print_r($sql);
		// echo "</pre>";

		$proses_tampil = $this->db->query("select * from db_stock.t_mapping_stock_baturaja a where a.id = $id and a.created_date = $created_date")->result();

		return $proses_tampil;
	}

	public function mapping_stock_batam($kode)
	{
		date_default_timezone_set('Asia/Jakarta');
		$created_date = '"' . date('Y-m-d H:i:s') . '"';
		$id = $this->session->userdata('id');
		$sql = "
			select 	max(created_date) last_update
			from 	db_stock.t_stock_batam_original a
			where 	a.id = $id
		";
		$proses = $this->db->query($sql)->result();
		foreach ($proses as $a) {
			$last_update = $a->last_update;
		}


		$sql = "
		insert into db_stock.t_mapping_stock_batam
		select a.no, a.kodeprod,a.nama_barang, a.sisa, b.isisatuan, (a.sisa / b.isisatuan) as stock,a.filename,$created_date,$id
		from db_stock.t_stock_batam_original a INNER JOIN 
		(
			select a.kodeprod,a.isisatuan
			from db_stock.t_mapping_batam a
		)b on a.kodeprod = b.kodeprod
		where	a.created_date = '$last_update' and a.id = $id
		";
		$proses = $this->db->query($sql);

		// echo "<pre>";
		// echo "<br><br><br><br>";
		// print_r($sql);
		// echo "</pre>";

		$proses_tampil = $this->db->query("select * from db_stock.t_mapping_stock_batam a where a.id = $id and a.created_date = $created_date")->result();

		return $proses_tampil;
	}

	public function mapping_stock_tanjung($kode)
	{
		date_default_timezone_set('Asia/Jakarta');
		$created_date = '"' . date('Y-m-d H:i:s') . '"';
		$id = $this->session->userdata('id');
		$sql = "
			select 	max(created_date) last_update
			from 	db_stock.t_stock_tanjung_original a
			where 	a.id = $id
		";
		$proses = $this->db->query($sql)->result();
		foreach ($proses as $a) {
			$last_update = $a->last_update;
		}


		$sql = "
		insert into db_stock.t_mapping_stock_tanjung
		select a.no, a.kodeprod,a.nama_barang, a.sisa, b.isisatuan, (a.sisa / b.isisatuan) as stock,a.filename,$created_date,$id
		from db_stock.t_stock_tanjung_original a INNER JOIN 
		(
			select a.kodeprod,a.isisatuan
			from db_stock.t_mapping_batam a
		)b on a.kodeprod = b.kodeprod
		where	a.created_date = '$last_update' and a.id = $id
		";
		$proses = $this->db->query($sql);

		// echo "<pre>";
		// echo "<br><br><br><br>";
		// print_r($sql);
		// echo "</pre>";

		$proses_tampil = $this->db->query("select * from db_stock.t_mapping_stock_tanjung a where a.id = $id and a.created_date = $created_date")->result();

		return $proses_tampil;
	}

	public function stock_rilis($kode, $tahun, $bulan)
	{

		$nocab = substr($kode, 3, 2);
		$bulan_versi_st = substr($tahun, 2) . $bulan;

		$sql = "
			delete from data$tahun.st
			where bulan = $bulan_versi_st and nocab = '$nocab' 
		";
		$proses = $this->db->query($sql);
		// echo "<pre><br><br><br><br><br>";
		// print_r($sql);
		// echo "</pre>";

		if ($proses) {
			$sql = "
				insert into data$tahun.st
				select 	a.kodeprod_mpm, b.KODE_PRC, b.NAMAPROD,b.supp,b.GRUPPROD,
						b.SATUAN,b.ISISATUAN,'','',a.total_stock_2,
						'','','','','','','','','','',
						'','','','','','','','','','',
						'','','','','','','','','','','',
						'$nocab', $bulan_versi_st
				from 	db_stock.t_mapping_stock a INNER JOIN mpm.tabprod b
							on a.kodeprod_mpm = b.KODEPROD
				where	a.kode = '$kode' and a.created_date = (select max(created_date) from db_stock.t_mapping_stock where kode ='$kode')
			";
			$proses = $this->db->query($sql);
			// echo "<pre>";
			// print_r($sql);
			// echo "</pre>";
			if ($proses) {
				$sql = "select * from data$tahun.st where bulan = $bulan_versi_st and nocab = '$nocab' order by kodeprod asc";
				$proses = $this->db->query($sql)->result();
				return $proses;
			}
		}
		// echo "<pre>";
		// print_r($sql);
		// echo "</pre>";
	}

	public function stock_rilis_baturaja($kode, $tahun, $bulan)
	{

		$nocab = substr($kode, 3, 2);
		$bulan_versi_st = substr($tahun, 2) . $bulan;

		$sql = "
			delete from data$tahun.st
			where bulan = $bulan_versi_st and nocab = '$nocab' 
		";
		$proses = $this->db->query($sql);
		// echo "<pre><br><br><br><br><br>";
		// print_r($sql);
		// echo "</pre>";

		if ($proses) {
			$sql = "
				insert into data$tahun.st
				select 	a.kodeprod, b.KODE_PRC, b.NAMAPROD,b.supp,b.GRUPPROD,
						b.SATUAN,b.ISISATUAN,'','',a.so_awal_mpm,
						a.masuk_mpm,'','','',a.keluar_mpm,'','','','','',
						'','','','','','','','','','',
						'','','','','','','','','','','',
						'$nocab', $bulan_versi_st
				from 	db_stock.t_mapping_stock_baturaja a INNER JOIN mpm.tabprod b
							on a.kodeprod = b.KODEPROD
				where	a.kode = '$kode' and a.created_date = (select max(created_date) from db_stock.t_mapping_stock_baturaja where kode ='$kode')
			";

			$proses = $this->db->query($sql);
			// echo "<pre>";
			// print_r($sql);
			// echo "</pre>";
			if ($proses) {
				$sql = "select * from data$tahun.st where bulan = $bulan_versi_st and nocab = '$nocab' order by kodeprod asc";
				$proses = $this->db->query($sql)->result();
				return $proses;
			}
		}
		// echo "<pre>";
		// print_r($sql);
		// echo "</pre>";
	}

	public function stock_rilis_aceh($kode, $tahun, $bulan)
	{

		$nocab = substr($kode, 3, 2);
		$bulan_versi_st = substr($tahun, 2) . $bulan;

		$sql = "
			delete from data$tahun.st
			where bulan = $bulan_versi_st and nocab = '$nocab' 
		";
		$proses = $this->db->query($sql);
		// echo "<pre><br><br><br><br><br>";
		// print_r($sql);
		// echo "</pre>";

		if ($proses) {
			$sql = "
				insert into data$tahun.st
				select 	a.kodeprod, b.KODE_PRC, b.NAMAPROD,b.supp,b.GRUPPROD,
						b.SATUAN,b.ISISATUAN,'','',a.stock,
						'','','','','','','','','','',
						'','','','','','','','','','',
						'','','','','','','','','','','',
						'$nocab', $bulan_versi_st
				from 	db_stock.t_stock_aceh_original a INNER JOIN mpm.tabprod b
							on a.kodeprod = b.KODEPROD
				where	a.kode = '$kode' and a.created_date = (select max(created_date) from db_stock.t_stock_aceh_original where kode ='$kode')
			";

			$proses = $this->db->query($sql);
			// echo "<pre>";
			// print_r($sql);
			// echo "</pre>";
			if ($proses) {
				$sql = "select * from data$tahun.st where bulan = $bulan_versi_st and nocab = '$nocab' order by kodeprod asc";
				$proses = $this->db->query($sql)->result();
				return $proses;
			}
		}
		// echo "<pre>";
		// print_r($sql);
		// echo "</pre>";
	}

	public function stock_rilis_batam($kode, $tahun, $bulan)
	{
		//jika bulan 1 karakter maka tambah 0 di depan
		if (strlen($bulan) == 1) {
			$bulan = '0'.$bulan;
		}else{
			$bulan = $bulan;
		}

		$nocab = substr($kode, 3, 2);
		$bulan_versi_st = substr($tahun, 2) . $bulan;

		$sql = "
			delete from data$tahun.st
			where bulan = $bulan_versi_st and nocab = '$nocab' 
		";
		$proses = $this->db->query($sql);
		// echo "<pre><br><br><br><br><br>";
		// print_r($sql);
		// echo "</pre>";

		if ($proses) {
			$sql = "
				insert into data$tahun.st
				select 	a.kodeprod, b.KODE_PRC, b.NAMAPROD,b.supp,b.GRUPPROD,
						b.SATUAN,b.ISISATUAN,'','',a.stock,
						'','','','','','','','','','',
						'','','','','','','','','','',
						'','','','','','','','','','','',
						'$nocab', $bulan_versi_st
				from 	db_stock.t_mapping_stock_batam a INNER JOIN mpm.tabprod b
							on a.kodeprod = b.KODEPROD
				where	a.created_date = (select max(created_date) from db_stock.t_mapping_stock_batam)
			";

			$proses = $this->db->query($sql);
			// echo "<pre>";
			// echo "<br><br><br><br><br><br>";
			// echo "tahun : ".$tahun."<br>";
			// echo "bulan : ".$bulan."<br>";
			// echo "bulan_versi_st : ".$bulan_versi_st."<br>";
			// print_r($sql);
			// echo "</pre>";
			if ($proses) {
				$sql = "select * from data$tahun.st where bulan = $bulan_versi_st and nocab = '$nocab' order by kodeprod asc";
				$proses = $this->db->query($sql)->result();
				return $proses;
			}
		}
		// echo "<pre>";
		// print_r($sql);
		// echo "</pre>";
	}

	public function stock_rilis_tanjung($kode, $tahun, $bulan)
	{
		//jika bulan 1 karakter maka tambah 0 di depan
		if (strlen($bulan) == 1) {
			$bulan = '0'.$bulan;
		}else{
			$bulan = $bulan;
		}

		$nocab = substr($kode, 3, 2);
		$bulan_versi_st = substr($tahun, 2) . $bulan;

		$sql = "
			delete from data$tahun.st
			where bulan = $bulan_versi_st and nocab = '$nocab' 
		";
		$proses = $this->db->query($sql);
		// echo "<pre><br><br><br><br><br>";
		// print_r($sql);
		// echo "</pre>";

		if ($proses) {
			$sql = "
				insert into data$tahun.st
				select 	a.kodeprod, b.KODE_PRC, b.NAMAPROD,b.supp,b.GRUPPROD,
						b.SATUAN,b.ISISATUAN,'','',a.stock,
						'','','','','','','','','','',
						'','','','','','','','','','',
						'','','','','','','','','','','',
						'$nocab', $bulan_versi_st
				from 	db_stock.t_mapping_stock_tanjung a INNER JOIN mpm.tabprod b
							on a.kodeprod = b.KODEPROD
				where	a.created_date = (select max(created_date) from db_stock.t_mapping_stock_tanjung)
			";

			$proses = $this->db->query($sql);
			// echo "<pre>";
			// echo "<br><br><br><br><br><br>";
			// echo "tahun : ".$tahun."<br>";
			// echo "bulan : ".$bulan."<br>";
			// echo "bulan_versi_st : ".$bulan_versi_st."<br>";
			// print_r($sql);
			// echo "</pre>";
			if ($proses) {
				$sql = "select * from data$tahun.st where bulan = $bulan_versi_st and nocab = '$nocab' order by kodeprod asc";
				$proses = $this->db->query($sql)->result();
				return $proses;
			}
		}
		// echo "<pre>";
		// print_r($sql);
		// echo "</pre>";
	}

	public function stock_rilis_bangka($kode, $tahun, $bulan)
	{

		$nocab = substr($kode, 3, 2);
		$bulan_versi_st = substr($tahun, 2) . $bulan;

		$sql = "
			delete from data$tahun.st
			where bulan = $bulan_versi_st and nocab = '$nocab' 
		";
		$proses = $this->db->query($sql);
		// echo "<pre><br><br><br><br><br>";
		// print_r($sql);
		// echo "</pre>";

		if ($proses) {
			$sql = "
				insert into data$tahun.st
				select 	a.kodeprod, b.KODE_PRC, b.NAMAPROD,b.supp,b.GRUPPROD,
						b.SATUAN,b.ISISATUAN,'','',a.stock,
						'','','','','','','','','','',
						'','','','','','','','','','',
						'','','','','','','','','','','',
						'$nocab', $bulan_versi_st
				from 	db_stock.t_stock_bangka_original a INNER JOIN mpm.tabprod b
							on a.kodeprod = b.KODEPROD
				where	a.kode = '$kode' and a.created_date = (select max(created_date) from db_stock.t_stock_bangka_original where kode ='$kode')
			";

			$proses = $this->db->query($sql);
			// echo "<pre>";
			// print_r($sql);
			// echo "</pre>";
			if ($proses) {
				$sql = "select * from data$tahun.st where bulan = $bulan_versi_st and nocab = '$nocab' order by kodeprod asc";
				$proses = $this->db->query($sql)->result();
				return $proses;
			}
		}
		// echo "<pre>";
		// print_r($sql);
		// echo "</pre>";
	}

	public function stock_rilis_pekanbaru($kode, $tahun, $bulan)
	{

		$nocab = substr($kode, 3, 2);
		$bulan_versi_st = substr($tahun, 2) . $bulan;

		$sql = "
			delete from data$tahun.st
			where bulan = $bulan_versi_st and nocab = '$nocab' 
		";
		$proses = $this->db->query($sql);
		// echo "<pre><br><br><br><br><br>";
		// print_r($sql);
		// echo "</pre>";

		if ($proses) {
			$sql = "
				insert into data$tahun.st
				select 	a.kodeprod_mpm, b.KODE_PRC, b.NAMAPROD,b.supp,b.GRUPPROD,
						b.SATUAN,b.ISISATUAN,'','',a.sisa,
						'','','','','','','','','','',
						'','','','','','','','','','',
						'','','','','','','','','','','',
						'$nocab', $bulan_versi_st
				from 	db_stock.t_mapping_stock_pekanbaru a INNER JOIN mpm.tabprod b
							on a.kodeprod_mpm = b.KODEPROD
				where	a.created_date = (select max(created_date) from db_stock.t_mapping_stock_pekanbaru)
			";

			$proses = $this->db->query($sql);
			// echo "<pre>";
			// print_r($sql);
			// echo "</pre>";
			if ($proses) {
				$sql = "select * from data$tahun.st where bulan = $bulan_versi_st and nocab = '$nocab' order by kodeprod asc";
				$proses = $this->db->query($sql)->result();
				return $proses;
			}
		}
		// echo "<pre>";
		// print_r($sql);
		// echo "</pre>";
	}

	public function get_subbranch()
	{

		$id = $this->session->userdata('id');
		$proses = $this->db->query("select username from mpm.`user` a where a.id = $id")->result();
		foreach ($proses as $a) {
			$kode = $a->username;
		}

		if ($id == 547) {
			$sql = "
				select concat(a.kode_comp,a.nocab) as kode, nama_comp
				from mpm.tbl_tabcomp a
				where a.`status` = 1 
				GROUP BY kode
			";
		} else {
			$sql = "
				select concat(a.kode_comp,a.nocab) as kode, nama_comp
				from mpm.tbl_tabcomp a
				where a.`status` = 1 and concat(a.kode_comp,a.nocab) like '$kode%'
				GROUP BY kode
			";
		}


		$proses = $this->db->query($sql);
		return $proses;
	}

	public function get_upload_stock_padang($tgl_created)
	{

		$id = $this->session->userdata('id');
		$sql = "
			select *
			from db_stock.t_stock_padang_original a
			where created_date = '$tgl_created' and a.id = $id
		";
		$proses = $this->db->query($sql)->result();
		return $proses;
	}

	public function get_upload_stock_pekanbaru($tgl_created)
	{

		$id = $this->session->userdata('id');
		$sql = "
			select *
			from db_stock.t_stock_pekanbaru_original a
			where created_date = '$tgl_created' and a.id = $id
		";
		$proses = $this->db->query($sql)->result();
		return $proses;
	}

	public function mapping_stock_padang($kode)
	{
		date_default_timezone_set('Asia/Jakarta');
		$created_date = '"' . date('Y-m-d H:i:s') . '"';
		$id = $this->session->userdata('id');
		$sql = "
			select 	max(created_date) last_update
			from 	db_stock.t_stock_padang_original a
			where 	a.kode = '$kode' and a.id = $id
		";
		$proses = $this->db->query($sql)->result();
		foreach ($proses as $a) {
			$last_update = $a->last_update;
		}

		$sql = "
		insert into db_stock.t_mapping_stock_padang
		select 	a.kode, a.kodeprod_original,a.nama,a.isi,a.harga_beli,
				/*a.m_ctn,a.m_unt,a.rp_mbl,*/ 
				sum(a.g_ctn),sum(a.g_unt),sum(a.rp_gdg),
				/*a.t_ctn,a.t_unt,a.rp_stock,*/ 
				b.kodeprod_mpm, (sum(a.g_ctn) * b.ctn_to_pcs) as ctn_pcs, (sum(a.g_unt) * b.box_to_pcs) as box_pcs,
				(sum(a.g_ctn) * b.ctn_to_pcs) + (sum(a.g_unt) * b.box_to_pcs) as total_pcs,$created_date,$id
		from db_stock.t_stock_padang_original a LEFT JOIN db_stock.t_mapping_padang b
			on a.kodeprod_original =b.kodeprod_original
		where a.created_date = '$last_update' and a.id = $id
		group by a.kodeprod_original
		";
		$proses = $this->db->query($sql);

		// echo "<pre>";
		// echo "<br><br><br><br>";
		// print_r($sql);
		// echo "</pre>";

		$proses_tampil = $this->db->query("select * from db_stock.t_mapping_stock_padang a where a.id = $id and a.created_date = $created_date")->result();

		return $proses_tampil;
	}

	public function mapping_stock_pekanbaru($kode)
	{
		date_default_timezone_set('Asia/Jakarta');
		$created_date = '"' . date('Y-m-d H:i:s') . '"';
		$id = $this->session->userdata('id');
		$sql = "
			select 	max(created_date) last_update
			from 	db_stock.t_stock_pekanbaru_original a
			where 	a.id = $id
		";
		$proses = $this->db->query($sql)->result();
		foreach ($proses as $a) {
			$last_update = $a->last_update;
		}

		$sql = "
		insert into db_stock.t_mapping_stock_pekanbaru
		select 	b.kodeprod_mpm, c.namaprod,a.kodebarang,a.namabarang,a.barcode,a.gol,a.merk,a.konversi,
				sum(a.sisa),a.hargapokok,a.jumlah,a.catatan,a.kodesup,a.rasio,a.satuan,a.rak,
				a.adamutasi,a.filename,$created_date,a.id
		from 	db_stock.t_stock_pekanbaru_original a INNER JOIN db_stock.t_mapping_pekanbaru b
					on a.kodebarang =b.kodeprod_pkb LEFT JOIN
				mpm.tabprod c on b.kodeprod_mpm = c.kodeprod
		where a.created_date = '$last_update' and a.id = $id
		group by a.kodebarang
		";
		$proses = $this->db->query($sql);

		// echo "<pre>";
		// echo "<br><br><br><br>";
		// print_r($last_update);
		// echo "</pre>";

		$proses_tampil = $this->db->query("select * from db_stock.t_mapping_stock_pekanbaru a where a.id = $id and a.created_date = $created_date")->result();
		// print_r($proses_tampil);
		return $proses_tampil;
	}
	
	public function stock_rilis_padang($kode, $tahun, $bulan)
	{
		//jika bulan 1 karakter maka tambah 0 di depan
		if (strlen($bulan) == 1) {
			$bulan = '0'.$bulan;
		}else{
			$bulan = $bulan;
		}

		$nocab = substr($kode, 3, 2);
		$bulan_versi_st = substr($tahun, 2) . $bulan;

		$sql = "
			delete from data$tahun.st
			where bulan = $bulan_versi_st and nocab = '$nocab' 
		";
		$proses = $this->db->query($sql);
		// echo "<pre><br><br><br><br><br>";
		// print_r($sql);
		// echo "</pre>";

		if ($proses) {
			$sql = "
				insert into data$tahun.st
				select 	a.kodeprod_mpm, b.KODE_PRC, b.NAMAPROD,b.supp,b.GRUPPROD,
						b.SATUAN,b.ISISATUAN,'','',a.total_pcs,
						'','','','','','','','','','',
						'','','','','','','','','','',
						'','','','','','','','','','','',
						'$nocab', $bulan_versi_st
				from 	db_stock.t_mapping_stock_padang a INNER JOIN mpm.tabprod b
							on a.kodeprod_mpm = b.KODEPROD
				where	a.kode = '$kode' and a.created_date = (select max(created_date) from db_stock.t_mapping_stock_padang where kode ='$kode')
			";

			$proses = $this->db->query($sql);
			// echo "<pre>";
			// echo "<br><br><br><br><br><br>";
			// echo "tahun : ".$tahun."<br>";
			// echo "bulan : ".$bulan."<br>";
			// echo "bulan_versi_st : ".$bulan_versi_st."<br>";
			// print_r($sql);
			// echo "</pre>";
			if ($proses) {
				$sql = "select * from data$tahun.st where bulan = $bulan_versi_st and nocab = '$nocab' order by kodeprod asc";
				$proses = $this->db->query($sql)->result();
				return $proses;
			}
		}
		// echo "<pre>";
		// print_r($sql);
		// echo "</pre>";
	}

}