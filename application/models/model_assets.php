<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Model_assets extends CI_Model 
{    
    public function proses_input_assets($dataSegment){

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

		$dob1=trim($dataSegment['tglperol']);//$dob1='dd/mm/yyyy' format
        $dob_disp1=strftime('%Y-%m-%d',strtotime($dob1));
        
        /*
        echo "<br>";
		echo "var tglperol_2 : ".$dob_disp1;

		echo "<br>";
		echo "var gol : ".$dataSegment['gol'];
		echo "<br>";
		echo "var grup : ".$dataSegment['grup'];
		echo "<br>";
		echo "var np : ".$dataSegment['np'];
		echo "<br>";
		echo "var nj : ".$dataSegment['nj'];
		echo "<br>";
		echo "var tgljual : ".$dataSegment['tgljual'];

		*/

		$dob2=trim($dataSegment['tgljual']);//$dob1='dd/mm/yyyy' format
        $dob_disp2=strftime('%Y-%m-%d',strtotime($dob2));
        /*
        echo "<br>";
		echo "var tgljual_2 : ".$dob_disp2;

		echo "<br>";
		echo "var deskripsi : ".$dataSegment['deskripsi'];
		*/

		//cek apakah kode sudah ada di tabel . belum

			$this->db->where("sn = ".'"'.$dataSegment['sn'].'"');
			$this->db->where("kode = ".'"'.$dataSegment['kode'].'"');
			$hasil = $this->db->get('mpm.asset');
			
			if ($hasil->num_rows() > 0) 
			{
				echo "Nomor SN sama di satu nomor Voucher. Input gagal";

			} else {
				
				//query insert data ke tabel mpm.po_new			

				    $data = array(
				               'kode' => $dataSegment['kode'],
				               'namabarang' => $dataSegment['namabarang'],
				               'sn' => $dataSegment['sn'],
				               'jumlah' => $dataSegment['jumlah'],
				               'untuk' => $dataSegment['untuk'],
				               'tglperol' => $dob_disp1,
				               'jumlah' => $dataSegment['jumlah'],
				               'gol' => $dataSegment['gol'],
				               'grupid' => $dataSegment['grup'],
				               'np' => $dataSegment['np'],
				               'nj' => $dataSegment['nj'],
				               'tgljual' => $dob_disp2,
				               'deskripsi' => $dataSegment['deskripsi']	,
				               'created_by' => $id,
				               'modified_by' => $id,
				               'created' => date('Y-m-d H:i:s'),
        					   'modified'=> date('Y-m-d H:i:s')
				        	);

				   	$proses = $this->db->insert('mpm.asset',$data);

				    /*
					   echo "<pre>";
					   print_r($query);
					   echo "</pre>";
				    */

					   //$proses = $this->db->query($query);					   
					
					   if ($proses == '1') {
					   		echo "insert success";
					   		redirect('all_assets/view_assets','refresh');
					   }
					   else
					   {
					   		return array(); 
					   }
				// end query insert
			}
		// end cek kode
	}

	public function getGrupassetcombo()
    {
        $sql='select id,namagrup from mpm.grupasset';
        return $this->db->query($sql);
    }

    public function view_assets()
    {
 		$this->db->join('mpm.grupasset', 'grupasset.id = asset.grupid','left');
		$this->db->select('asset.id, asset.grupid, asset.kode, asset.namabarang, grupasset.namagrup, asset.untuk, asset.tglperol, asset.np, asset.nj');
		$this->db->order_by("id", "desc"); 
		$hasil = $this->db->get('mpm.asset');
		
		if ($hasil->num_rows() > 0) 
		{

			return $hasil->result();
		} else {
			return array();
		}
    }

    public function detail_assets(){

    	$id = $this->uri->segment(3);

    	//echo br(10);

    	//echo "id : ".$id;
    	$this->db->where("asset.id = ".$id);
    	$this->db->join('mpm.grupasset', 'grupasset.id = asset.grupid','left');
    	$this->db->select('asset.kode, asset.namabarang, asset.sn, asset.jumlah, asset.untuk, asset.tglperol, asset.gol, asset.grupid, grupasset.namagrup, asset.np, asset.nj, asset.tgljual, asset.deskripsi');
    	$hasil = $this->db->get('mpm.asset');
		
		if ($hasil->num_rows() > 0) 
		{
			return $hasil->result();
		} else {
			return array();
		}

    }

    public function proses_update($dataasset){

    	//echo "tglperol : ".$dataasset['tglperol']."<br>";

    	$dob1=trim($dataasset['tglperol']);//$dob1='dd/mm/yyyy' format
        $dob_disp1=strftime('%Y-%m-%d',strtotime($dob1));

        $dob2=trim($dataasset['tgljual']);//$dob1='dd/mm/yyyy' format
        $dob_disp2=strftime('%Y-%m-%d',strtotime($dob2));

        //echo "tglperol : ".$dob_disp1;


    	
    	$data = array(
                'kode'        => $dataasset['kode'],
                'namabarang'  => $dataasset['namabarang'],
                'sn'  		  => $dataasset['sn'],
                'jumlah'      => $dataasset['jumlah'],
                'untuk'       => $dataasset['untuk'],
                'tglperol'    => $dob_disp1,
                'np'          => $dataasset['np'],
                'nj'          => $dataasset['nj'],
                'tgljual'     => $dob_disp2,
                'deskripsi'   => $dataasset['deskripsi'],
                //'grupid'      => $dataasset['grup'],
                //'gol'		  => $dataasset['gol']
            );
         
        /*
    	echo "kode : ".$dataasset['kode']."<br>";
		echo "id : ".$dataasset['id']."<br>";
    	echo "namabarang : ".$dataasset['namabarang']."<br>";
    	echo "grup : ".$dataasset['grup']."<br>";
    	echo "gol : ".$dataasset['gol']."<br>";
    	echo "deskripsi : ".$dataasset['deskripsi']."<br>";
    	*/


    	//Query update

    	$hasil = $this->db->where('id',$dataasset['id'])
    			 ->update('asset', $data);
    	
    	if ($hasil == '1') 
			{
				echo "update berhasil";
				redirect('all_assets/view_assets','refresh');
			} else {
				return array();
			}
		
    }

    public function proses_delete($id){
    	
    	$this->db->where('id', $id)
    			 ->delete('asset');
    }

}