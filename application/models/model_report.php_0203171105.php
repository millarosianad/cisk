<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Model_report extends CI_Model 
{
	    
    public function report_po($dataSegment){

	/* ---------DEFINISI VARIABEL----------------- */
		$id=$this->session->userdata('id');

		$year = $dataSegment['tahun'];
		$month = $dataSegment['bulan'];
		echo "tahun di model : ".$year;
		//$supp = $dataSegment['supp'];
		//echo "supplier di model : ".$supp;

		$query = "
		select supp, namasupp, SUM(total_po) as total_po, SUM(approve) as approve, SUM(pending) as pending, format(SUM(total_values_all_po),0) as all_value, format(SUM(total_values_po_approve),0) as approve_value,format(SUM(total_values_po_pending),0) as pending_value
		FROM
		(
			select a.supp as supp, namasupp, COUNT(*) as total_po, '' as approve, '' as pending, SUM(total_value) as total_values_all_po, '' as total_values_po_approve, '' as total_values_po_pending
			FROM(
						
					select	a.id, a.supp, SUM(banyak*harga) as `total_value`, namasupp, company, nopo, tglpo, created
					from
					(
						select a.id id, a.supp supp,b.NAMASUPP namasupp, company, nopo, tglpo, tglpesan, a.created, a.modified, tipe, `open`, open_date, open_by
						from	 mpm.po a INNER JOIN mpm.tabsupp b
											on a.supp = b.SUPP
						where  a.created like '$year-$month%'
						order by id desc
					)a LEFT JOIN mpm.po_detail b
							on a.id = b.id_ref
					GROUP BY id
					ORDER BY supp
			)a GROUP BY supp

			union ALL

			select a.supp, namasupp, '', COUNT(*) as approve, '' as pending, '', SUM(total_value) as total_values, ''
			FROM(
						
					select	a.id, a.supp, SUM(banyak*harga) as `total_value`, namasupp, company, nopo, tglpo, created
					from
					(
						select a.id id, a.supp supp,b.NAMASUPP namasupp, company, nopo, tglpo, tglpesan, a.created, a.modified, tipe, `open`, open_date, open_by
						from	 mpm.po a INNER JOIN mpm.tabsupp b
											on a.supp = b.SUPP
						where  a.created like '$year-$month%'  and `open` = '1'
						order by id desc
					)a LEFT JOIN mpm.po_detail b
							on a.id = b.id_ref
					GROUP BY id
					ORDER BY supp
			)a GROUP BY supp

			union ALL

			select a.supp, namasupp, '', '' as approve, COUNT(*) as pending, '', '', SUM(total_value) as total_values_po_pending
			FROM(
						
					select	a.id, a.supp, SUM(banyak*harga) as `total_value`, namasupp, company, nopo, tglpo, created
					from
					(
						select a.id id, a.supp supp,b.NAMASUPP namasupp, company, nopo, tglpo, tglpesan, a.created, a.modified, tipe, `open`, open_date, open_by
						from	 mpm.po a INNER JOIN mpm.tabsupp b
											on a.supp = b.SUPP
						where  a.created like '$year-$month%'  and `open` = '0'
						order by id desc
					)a LEFT JOIN mpm.po_detail b
							on a.id = b.id_ref
					GROUP BY id
					ORDER BY supp
			)a GROUP BY supp
		)a GROUP BY supp
		";
		
		/*
		echo "<pre>";
		echo "<br>";
		print($query);
		echo "</pre>";
		*/

		$sql = $this->db->query($query);
		
		if ($sql->num_rows() > 0) 
		{
			return $sql->result();
		} else {
			return array();
		}

	}

	public function cek_status_po($dataSegment){
        $status = "aaa";

        /*
			status :
			1. nodo = null -> status = pending(principal)
			2. nodo not null & tglterima = null-> status = on process
			3. tglterima not null -> status = delivered
	
        */

		$this->db->order_by("po.id", "desc");		        
        $this->db->where('po.nopo is not null');
        $this->db->join('po_new', 'po_new.id = po.id','left'); 
        $this->db->select('po.nopo, po.id, po.created, po.company, po_new.nodo,po_new.tgldo');
		$hasil = $this->db->get('mpm.po', 100);


		//query update produk berdasarkan id_ref	
		/*					
			$this->db->where('id', $dataSegment['segments']);
			$hasil = $this->db->update('mpm.po_new', $data); 
				
			if ($hasil == '1') 
			{
				echo "update success";
				redirect('all_report/list_po','refresh');
			} else {
				return array();
			}
		*/
		//end update


    }

	public function list_po($dataSegment){

	/* ---------DEFINISI VARIABEL----------------- */
		$id=$this->session->userdata('id');
		//echo "<br><br>id : ".$id;

		//query mencari 'supp' dari userid
	        $this->db->where('id = '.$id);
	        $query = $this->db->get('mpm.user');
	        foreach ($query->result() as $row) {
	        	$supplier = $row->supp;
	        	//echo "supplier : ".$supplier;
	        }

	        if ($supplier == '000') {
	        	
	        	//query menampilkan po berdasarkan supplier, kalau supplier 000 maka menampilkan seluruh po
		        $this->db->order_by("po.id", "desc");		        
		        $this->db->where('po.nopo is not null');
		        $this->db->join('po_new', 'po_new.id = po.id','left');
		        $this->db->join('tabsupp', 'po.supp = tabsupp.supp','left');
		        $this->db->select('po.nopo, po.id, po.created, po.company, tabsupp.namasupp, po_new.nodo,po_new.tgldo, po_new.tglterima');
				$hasil = $this->db->get('mpm.po', 500);
				
				if ($hasil->num_rows() > 0) 
				{
					return $hasil->result();
				} else {
					return array();
				}
				//end menampilkan po

	        }else{

	        	//query menampilkan po berdasarkan supplier
		        $this->db->order_by("id", "desc"); 
		        $this->db->where('po.supp = '.$supplier);
		        $this->db->where('nopo is not null');
				$this->db->join('po_new', 'po_new.id = po.id','left'); 
				$this->db->join('tabsupp', 'po.supp = tabsupp.supp','left');
		        $this->db->select('po.nopo, po.id, po.created, po.company, tabsupp.namasupp, po_new.nodo,po_new.tgldo, po_new.tglterima');
				$hasil = $this->db->get('mpm.po', 500);
				
				if ($hasil->num_rows() > 0) 
				{
					return $hasil->result();
				} else {
					return array();
				}
				//end menampilkan po
	        
	        }
	    //end cari supp
	}

	public function list_po_branch($dataSegment){

	/* ---------DEFINISI VARIABEL----------------- */
		$id=$this->session->userdata('id');
		//echo "<br><br>id : ".$id;

		//query mencari 'supp' dari userid
	        $this->db->where('id = '.$id);
	        $query = $this->db->get('mpm.user');
	        foreach ($query->result() as $row) {
	        	$supplier = $row->supp;
	        	//echo "supplier : ".$supplier;
	        	$company = $row->company;
	        	//echo "company : ".$company;
	        }



	        if ($supplier == '000') {
	        	
	        	//query menampilkan po berdasarkan supplier, kalau supplier 000 maka menampilkan seluruh po
		        $this->db->order_by("po.id", "desc");		   
		        $this->db->where('company = '.'"'.$company.'"');     
		        $this->db->where('po.nopo is not null and po_new.nodo is not null');
		        $this->db->join('po_new', 'po_new.id = po.id','left'); 
		        $this->db->select('po.nopo, po.id, po.created, po.company, po_new.nodo,po_new.tgldo, po_new.tglterima');
				$hasil = $this->db->get('mpm.po', 500);
				
				if ($hasil->num_rows() > 0) 
				{
					return $hasil->result();
				} else {
					return array();
				}
				//end menampilkan po

	        }else{

	        	//query menampilkan po berdasarkan supplier
		        $this->db->order_by("id", "desc"); 
		        $this->db->where('company = '.'"'.$company.'"');  
		        $this->db->where('supp = '.$supplier);
		        $this->db->where('nopo is not null and po_new.nodo is not null');
				$this->db->join('po_new', 'po_new.id = po.id','left'); 
		        $this->db->select('po.nopo, po.id, po.created, po.company, po_new.nodo,po_new.tgldo, po_new.tglterima');
				$hasil = $this->db->get('mpm.po', 500);
				
				if ($hasil->num_rows() > 0) 
				{
					return $hasil->result();
				} else {
					return array();
				}
				//end menampilkan po
	        
	        }
	    //end cari supp
	}

	public function list_po_custom($dataSegment){

	/* ---------DEFINISI VARIABEL----------------- */
		$id=$this->session->userdata('id');
		//echo "<br><br>id : ".$id;

		//mendefinisikan variabel
			//echo $dataSegment['kategori'];
			//echo $dataSegment['tanggal'];
			$kategori = $dataSegment['kategori'];
			$supplier = $dataSegment['supplier'];
	   		$tahun = $dataSegment['tahun'];

	   		/*
	   		echo "<br>";
	   		echo $kategori;
	   		echo "<br>";
	   		echo $supplier;
	   		echo "<br>";
	   		echo $tanggal;
			*/


	   		if ($kategori == 'all') {

	   			//echo "tampil semua po";
	   			/*
	   			$this->db->where('supp = '.$dataSegment['supplier']);
		        $this->db->where('created like '.'"'.$dataSegment['tahun'].'-'.$dataSegment['bulan'].'%'.'"');
				$hasil = $this->db->get('mpm.po');
				*/
				$this->db->where('po.supp = '.$dataSegment['supplier']);
		        $this->db->where('po.created like '.'"'.$dataSegment['tahun'].'-'.$dataSegment['bulan'].'%'.'"');
		        $this->db->join('po_new', 'po_new.id = po.id','left');
		        $this->db->join('tabsupp', 'po.supp = tabsupp.supp','left');
		        $this->db->select('po.nopo, po.id, po.created, po.company, tabsupp.namasupp, po_new.nodo,po_new.tgldo, po_new.tglterima');
				$hasil = $this->db->get('mpm.po');

	   		}elseif ($kategori == 'approve' ) {

	   			//echo "tampil po yang approve";
	   			$this->db->where('open = 1');
	   			$this->db->where('po.supp = '.$dataSegment['supplier']);
		        $this->db->where('po.created like '.'"'.$dataSegment['tahun'].'-'.$dataSegment['bulan'].'%'.'"');
		        $this->db->join('po_new', 'po_new.id = po.id','left');
		        $this->db->join('tabsupp', 'po.supp = tabsupp.supp','left');
		        $this->db->select('po.nopo, po.id, po.created, po.company, tabsupp.namasupp, po_new.nodo,po_new.tgldo, po_new.tglterima');
				$hasil = $this->db->get('mpm.po');

	   		}elseif ($kategori == 'pending') {

	   			//echo "tampil po pending";
	   			$this->db->where('open = 0');
	   			$this->db->where('po.supp = '.$dataSegment['supplier']);
		        $this->db->where('po.created like '.'"'.$dataSegment['tahun'].'-'.$dataSegment['bulan'].'%'.'"');
		        $this->db->join('po_new', 'po_new.id = po.id','left');
		        $this->db->join('tabsupp', 'po.supp = tabsupp.supp','left');
		        $this->db->select('po.nopo, po.id, po.created, po.company, tabsupp.namasupp, po_new.nodo,po_new.tgldo, po_new.tglterima');
				$hasil = $this->db->get('mpm.po');
	   		}

			if ($hasil->num_rows() > 0) 
			{
				return $hasil->result();
			} else {
				return array();
			}
	}

	public function detail_po($dataSegment){

	/* ---------DEFINISI VARIABEL----------------- */
		$id=$this->session->userdata('id');
		//echo "<br><br>id : ".$id;

		//echo "<br><br>";
		//echo $dataSegment['segments']."<br>";

	    //query menampilkan produk berdasarkan id_ref
	        $this->db->order_by("kodeprod", "asc");
	        $this->db->join('mpm.po', 'po_detail.id_ref = po.id','inner');
	        $this->db->where('id_ref = '.$dataSegment['segments']);
			$hasil = $this->db->get('mpm.po_detail', 100);
			
			//print_r($hasil->num_rows());

			if ($hasil->num_rows() > 0) 
			{
				return $hasil->result();
				//echo "x";
			} else {
				return array();
				//echo "y";
			}
		//end menampilkan po
	}

	public function single_po($dataSegment){

	/* ---------DEFINISI VARIABEL----------------- */
		$id=$this->session->userdata('id');
		//echo "<br><br>id : ".$id;	


	    //query menampilkan produk berdasarkan id_ref
	        $this->db->order_by("kodeprod", "asc");
	        $this->db->join('mpm.po', 'po_detail.id_ref = po.id','inner');
	        $this->db->where('id_ref = '.$dataSegment['segments']);
			$hasil = $this->db->get('mpm.po_detail', 1);
			
			if ($hasil->num_rows() > 0) 
			{
				return $result = $hasil->result();
			} else {
				return array();
			}
		//end menampilkan po
	}

	public function single_po_branch($dataSegment){

	/* ---------DEFINISI VARIABEL----------------- */
		$id=$this->session->userdata('id');
		//echo "<br><br>id : ".$id;	


	    //query menampilkan produk berdasarkan id_ref
	      
	        $this->db->where('id = '.$dataSegment['segments']);
			$hasil = $this->db->get('mpm.po_new', 1);
			
			if ($hasil->num_rows() > 0) 
			{
				return $result = $hasil->result();
			} else {
				return array();
			}
		//end menampilkan po
	}

	public function proses_po($dataSegment)
	{
		if ( function_exists( 'date_default_timezone_set' ) )
        date_default_timezone_set('Asia/Jakarta');        
        $tgl_created='"'.date('H:i:s').'"';

        //echo $dataSegment['tgldo'].' '.'00:00:00';
		//echo $tgl_created;

		$data = array(
               'nodo' => $dataSegment['nodo'],
               'tgldo' => $dataSegment['tgldo']
            );

		$nodo = $dataSegment['nodo'];
		$tgldo = $dataSegment['tgldo'];

		//cek id sudah ada / belum di mpm.po_new

			$this->db->where("id = ".'"'.$dataSegment['segments'].'"');
			$hasil = $this->db->get('mpm.po_new');
			
			if ($hasil->num_rows() > 0) 
			{
				
				//query update produk berdasarkan id_ref	
					
					$this->db->where('id', $dataSegment['segments']);
					$hasil = $this->db->update('mpm.po_new', $data); 
						
					if ($hasil == '1') 
					{
						echo "update success";
						redirect('all_report/list_po','refresh');
					} else {
						return array();
					}

				//end update

			} else {
				
				//query insert data ke tabel mpm.po_new			
				    
				    /*
				    $query = "
				   			insert into mpm.po_new
				   			select * from mpm.po where id = ".'"'.$dataSegment['segments'].'"';
				    */
				    $data = array(
				               'id' => $dataSegment['segments'],
				               'nodo' => $dataSegment['nodo'],
				               'tgldo' => $dataSegment['tgldo'],
				               'tglterima' => '',
				               'keterangan' => '',
				               'status' => ''
				        	);

				    $proses = $this->db->insert('mpm.po_new', $data); 

				    /*
					   echo "<pre>";
					   print_r($query);
					   echo "</pre>";
				    */

					   //$proses = $this->db->query($query);					   
					   if ($proses == '1') {
					   		echo "insert success";
					   		redirect('all_report/list_po','refresh');
					   }
					   else
					   {
					   		return array(); 
					   }
				// end query insert
			}	
	}
	
	public function proses_po_branch($dataSegment)
	{
		if ( function_exists( 'date_default_timezone_set' ) )
        date_default_timezone_set('Asia/Jakarta');        
        $tgl_created='"'.date('H:i:s').'"';

        //echo $dataSegment['tgldo'].' '.'00:00:00';
		//echo $tgl_created;

		$data = array(
               'tglterima' => $dataSegment['tglterima']
            );

		$tglterima = $dataSegment['tglterima'];

		//cek id sudah ada / belum di mpm.po_new

		//query update produk berdasarkan id_ref	
					
			$this->db->where('id', $dataSegment['segments']);
			$hasil = $this->db->update('mpm.po_new', $data); 
				
			if ($hasil == '1') 
			{
				echo "update success";
				redirect('all_report/list_po_branch','refresh');
			} else {
				return array();
			}

		//end update



			
	}

}