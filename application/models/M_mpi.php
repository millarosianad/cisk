<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require 'vendor/autoload.php';
use GuzzleHttp\Client;
use GuzzleHttp\Psr7;
use GuzzleHttp\Exception\RequestException;

class M_mpi extends CI_Model 
{
    public function data_mpi()
	{
		$sql = "
			select a.tgl_invoice, b.username
			from mpi.datampi a LEFT JOIN mpm.`user` b on a.id = b.id
			GROUP BY a.tgl_invoice
            order by a.tgl_invoice desc
		";
		$hasil = $this->db->query($sql);
		if ($hasil->num_rows() > 0) {
			return $hasil->result();
		} else {
			return array();
		}
	}

    public function insert_mpi($data)
    {
		$id=$this->session->userdata('id');
		$from = $data['from'];
		$to = $data['to'];
		// echo "<pre>";
		// print_r($from);
		// print_r($to);
		// echo "</pre>";
		$client = new Client();

		// $response = $client->request('GET', "http://222.165.240.170:8012/report-api/public/sales_data/$from/$to", [
		// 	'query' => [
		// 		'key' => 'c089ed651ff206a7361406b0a1929db0'
		// 	]
		// ]);

		$response = $client->request('GET', "http://222.165.240.170:8012/report-api/public/sales_data_fusion/$from/$to", [
			'query' => [
				'key' => 'c089ed651ff206a7361406b0a1929db0'
			]
		]);

		$result = json_decode($response->getBody()->getContents(),true);
		// return $result;

		if($result)
		{
			$sql = "delete from mpi.tbl_mpi where id = $id";
			$proses = $this->db->query($sql);
		}

		foreach ($result as $key) {
            $data = array(
                'org_id' => $key["ORG_ID"],
                'tgl_invoice' => $key["TGL_INVOICE"],
                'year' => $key["YEAR"],
                'bln' => $key["BLN"],
                'days' => $key["DAYS"],
                'periode' => $key["PERIODE"],
                'jenis' => $key["JENIS"],
                'no_performa' => $key["NO_PERFORMA"],
                'no_invoice' => $key["NO_INVOICE"],
                'id_paket' => $key["ID_PAKET"],
                'inventory_item_id' => $key["INVENTORY_ITEM_ID"],
                'item_code' => $key["ITEM_CODE"],
                'nama_produk' => $key["NAMA_PRODUK"],
                'kemasan' => $key["KEMASAN"],
                'site_number' => $key["SITE_NUMBER"],
                'namalang' => $key["NAMALANG"],
                'almtl1' => $key["ALMTL1"],
                'banyak' => $key["BANYAK"],
                'hna' => $key["HNA"],
                'thna' => $key["THNA"],
                'dharga' => $key["DHARGA"],
                'prsnd' => $key["PRSND"],
                'rpbonus' => $key["RPBONUS"],
                'perbonus' => $key["PERBONUS"],
                'rpphb' => $key["RPPHB"],
                'perphb' => $key["PERPHB"],
                'bulan' => $key["BULAN"],
                'tahun' => $key["TAHUN"],
                'kode_cab' => $key["KODE_CAB"],
                'nama_cab' => $key["NAMACAB"],
                'no_pi' => $key["NO_PI"],
                'tgl_pi' => $key["TGL_PI"],
                'almtlang' => $key["ALMTLANG"],
                'sales_type' => $key["SALES_TYPE"],
                'klsout' => $key["KLSOUT"],
                'no_referensi_retur' => $key["NO_REFERENSI_RETUR"],
                'tgl_eks_ref_retur' => $key["TGL_EKS_REF_RETUR"],
                'no_dpl' => $key["NO_DPL"],
				'salesman' => $key["SALESMAN"],
                'id' => $id
			);
			$proses = $this->db->insert('mpi.tbl_mpi', $data);
		}	
		$sql = "select * from mpi.tbl_mpi where id = $id";
		$hasil = $this->db->query($sql);
		// $hasil = $this->db->get('user');
		if ($hasil->num_rows() > 0) {
			return $hasil->result();
		} else {
			return array();
		}
	}

	public function insert_mpi_to_db()
	{
		$id = $this->session->userdata('id');		
		date_default_timezone_set('Asia/Jakarta');        
		$tgl_created='"'.date('Y-m-d H:i:s').'"';

		$sql = "
			insert into mpi.datampi
			select *,$tgl_created from mpi.tbl_mpi where id = $id
		";
		$hasil = $this->db->query($sql);
		if ($hasil) {
			echo "<script>
			alert('Insert Berhasil');
			window.location.href='../../mpi/insert_mpi';
			</script>";
		} else {
			echo '<script>alert("Insert gagal. Harap hubungi IT !");</script>';
		}
	}

	public function omzet_mpi_proses($data)
	{
		$id = $this->session->userdata('id');
		$tahun = $data['tahun'];
		$uv = $data['uv'];
		if ($uv == '1') {
			$status = 'banyak';
		}else{
			$status = 'thna';
		}
		$apotik = $data['apotik'];
		$kimia_farma = $data['kimia_farma'];
		$pbf_kimia_farma = $data['pbf_kimia_farma'];
		$minimarket = $data['minimarket'];
		$pd = $data['pd'];
		$pbf = $data['pbf'];
		$rs = $data['rs'];
		$supermarket = $data['supermarket'];
		$tokoobat = $data['tokoobat'];
		$group = $data['group'];

		// echo "<pre>";
		// echo "apotik : ".$apotik."<br>";
		// echo "kimia_farma : ".$kimia_farma."<br>";
		// echo "minimarket : ".$minimarket."<br>";
		// echo "pd : ".$pd."<br>";
		// echo "pbf : ".$pbf."<br>";
		// echo "rs : ".$rs."<br>";
		// echo "supermarket : ".$supermarket."<br>";
		// echo "tokoobat : ".$tokoobat."<br>";
		// echo "</pre>";

		if ($minimarket == 1) {
			$minimarketx = 'minimarket';
		}else{
			$minimarketx ="";
		}

		if ($pd == 1) {
			$pdx = 'p&d';
		}else{
			$pdx ="";
		}

		if ($pbf == 1) {
			$pbfx = 'pbf';
		}else{
			$pbfx ="";
		}

		if ($rs == 1) {
			$rsx = 'rs swasta';
		}else{
			$rsx ="";
		}

		if ($supermarket == 1) {
			$supermarketx = 'supermarket';
		}else{
			$supermarketx ="";
		}

		if ($tokoobat == 1) {
			$tokoobatx = 'toko obat';
		}else{
			$tokoobatx ="";
		}

		if ($apotik == 1) {
			$apotikx = "apotik";
			
		}else{
			$apotikx ="";
		}
		
		if($apotik =='1' && $kimia_farma =='0' && $pbf =='0' && $pbf_kimia_farma =='0'){
			// echo "A";
			$penghubung ="and klsout in ('apotik') and namalang not like '%kimia%farma%'";
		}elseif($apotik =='0' && $kimia_farma =='1' && $pbf =='0' && $pbf_kimia_farma =='0'){
			// echo "B";
			$penghubung ="and klsout in ('apotik') and namalang like '%kimia%farma%'";
		}
		elseif($apotik =='1' && $kimia_farma =='1' && $pbf =='0' && $pbf_kimia_farma =='0'){
			// echo "C";
			$penghubung ="and klsout in ('apotik')";
		}if($pbf =='1' && $pbf_kimia_farma =='0' && $apotik =='0' && $kimia_farma =='0'){
			// echo "D";
			$penghubung ="and klsout in ('pbf') and namalang not like '%kimia%farma%'";
		}elseif($pbf =='0' && $pbf_kimia_farma =='1' && $apotik =='0' && $kimia_farma =='0'){
			// echo "E";
			$penghubung ="and klsout in ('pbf') and namalang like '%kimia%farma%'";
		}
		elseif($pbf =='1' && $pbf_kimia_farma =='1'  && $apotik =='0' && $kimia_farma =='0'){
			// echo "F";
			$penghubung ="and klsout in ('pbf')";
		}elseif($apotik =='1' && $kimia_farma =='1' && $pbf =='1' && $pbf_kimia_farma =='1'){
			// echo "G";
			$penghubung ="and klsout in ('pbf','apotik')";
		}elseif($apotik =='1' && $kimia_farma =='1' && $pbf =='1' && $pbf_kimia_farma =='0'){
			// echo "H";
			$penghubung ="and (klsout in ('apotik')) or (klsout in ('pbf') and namalang not like '%kimia%farma%')";
		}elseif($apotik =='1' && $kimia_farma =='1' && $pbf =='0' && $pbf_kimia_farma =='1'){
			// echo "I";
			$penghubung ="and (klsout in ('apotik')) or (klsout in ('pbf') and namalang like '%kimia%farma%')";
		}elseif($apotik =='0' && $kimia_farma =='0' && $pbf =='0' && $pbf_kimia_farma =='0'){
			// echo "Z";
			$penghubung = "";
		}

		if($group == 1 ){
			$grup = 'a.nama_cab';
		}elseif($group == 2 ){
			$grup = 'item_code';
		}elseif($group == 3){
			$grup = 'a.nama_cab, item_code';
		}

		$sql = "delete from mpi.t_temp_omzet_mpi where id = $id";
		$query = $this->db->query($sql);

		$sql = "
		insert into mpi.t_temp_omzet_mpi		
		SELECT	a.kode_cab as kode_cab, a.nama_cab as nama_cab,
				a.item_code as kodeprod_mpi, a.nama_produk as namaprod_mpi,
				a.kodeprod_mpm,a.namaprod_mpm,a.grup, a.nama_group, a.supp, a.namasupp,
				sum(if(bulan = 1,$status,0)) as b1,
				sum(if(bulan = 2,$status,0)) as b2,
				sum(if(bulan = 3,$status,0)) as b3,
				sum(if(bulan = 4,$status,0)) as b4,
				sum(if(bulan = 5,$status,0)) as b5,
				sum(if(bulan = 6,$status,0)) as b6,
				sum(if(bulan = 7,$status,0)) as b7,
				sum(if(bulan = 8,$status,0)) as b8,
				sum(if(bulan = 9,$status,0)) as b9,
				sum(if(bulan = 10,$status,0)) as b10,
				sum(if(bulan = 11,$status,0)) as b11,
				sum(if(bulan = 12,$status,0)) as b12,
				sum(if(bulan = 1,ot,0)) as t1,
				sum(if(bulan = 2,ot,0)) as t2,
				sum(if(bulan = 3,ot,0)) as t3,
				sum(if(bulan = 4,ot,0)) as t4,
				sum(if(bulan = 5,ot,0)) as t5,
				sum(if(bulan = 6,ot,0)) as t6,
				sum(if(bulan = 7,ot,0)) as t7,
				sum(if(bulan = 8,ot,0)) as t8,
				sum(if(bulan = 9,ot,0)) as t9,
				sum(if(bulan = 10,ot,0)) as t10,
				sum(if(bulan = 11,ot,0)) as t11,
				sum(if(bulan = 12,ot,0)) as t12,$id
		FROM
		(
			select 	a.kode_cab, a.nama_cab, a.bulan, a.item_code,a.nama_produk, 
					sum(a.thna) as thna,sum(a.banyak) as banyak, a.namalang, a.klsout, 
					count(distinct(a.namalang)) as ot,b.kodeprod_mpm,b.namaprod_mpm,b.grup, b.nama_group, b.supp, b.namasupp
			from 	mpi.datampi a LEFT JOIN
			(
				select 	a.kodeprod_mpi, a.namaprod_mpi, a.satuan,
						b.kodeprod as kodeprod_mpm, b.NAMAPROD as namaprod_mpm, b.grup, b.supp,  
						c.nama_group, d.namasupp
				from 	mpi.t_produk_mapping a LEFT JOIN mpm.tabprod b 
							on a.kodeprod = b.kodeprod LEFT JOIN mpm.tbl_group c
							on b.grup = c.kode_group LEFT JOIN mpm.tabsupp d 
							on b.supp = d.supp
			)b on a.item_code = b.kodeprod_mpi
			where 	`year` = $tahun and a.last_updated = (
						select max(b.last_updated)
						from mpi.datampi b
						where a.bulan = b.bulan
					) $penghubung
			group BY $grup, bulan
		)a GROUP BY $grup
		";

		// echo "<pre><br><br><br><br>";
		// print_r($sql);
		// echo "</pre>";

		$proses = $this->db->query($sql);
		if($proses){

			$sql = "
			insert into mpi.t_temp_omzet_mpi
			select 	'', 'Z_TOTAL', '','Z_TOTAL','','','','','','',sum(a.b1),sum(a.b2),sum(a.b3),sum(a.b4),sum(a.b5),sum(a.b6),
					sum(a.b7),sum(a.b8),sum(a.b9),sum(a.b10),sum(a.b11),sum(a.b12),
					sum(a.t1),sum(a.t2),sum(a.t3),sum(a.t4),sum(a.t5),sum(a.t6),
					sum(a.t7),sum(a.t8),sum(a.t9),sum(a.t10),sum(a.t11),sum(a.t12),
					$id
			from mpi.t_temp_omzet_mpi a
			where a.id = $id
			";
			
			$proses = $this->db->query($sql);
			if($proses){

				$sql = "select * from mpi.t_temp_omzet_mpi where id = $id";
				$hasil = $this->db->query($sql);
				if ($hasil->num_rows() > 0) {
					return $hasil->result();
				} else {
					return array();
				}

			}else{
				return array();
			}
		}else{
			return array();
		}
	
	}

    public function get_raw_mpi()
    {
        $sql = "select * from mpi.tbl_list_raw order by id desc";        
        $proses = $this->db->query($sql)->result();
        if ($proses) {
            return $proses;
        }else{
            return array();
		}
		
    }

    public function omzet_mpi($data)
    {
		$id=$this->session->userdata('id');
		$from = $data['from'];
		$to = $data['to'];
		
		$client = new Client();
	
			// $response = $client->request('GET', "http://222.165.240.170:8012/report-api/public/sales_data/$from/$to", [
			// 	'query' => [
			// 		'key' => 'c089ed651ff206a7361406b0a1929db0'
			// 	]
			// ]);

			$response = $client->request('GET', "http://222.165.240.170:8012/report-api/public/sales_data_fusion/$from/$to", [
				'query' => [
					'key' => 'c089ed651ff206a7361406b0a1929db0'
				]
			]);

			$result = json_decode($response->getBody()->getContents(),true);

			

			if($result != array())
			{
				$sql = "delete from mpi.tbl_mpi where id = $id";
				$proses = $this->db->query($sql);
				foreach ($result as $key) 
				{
					$data = array(
						'org_id' => $key["ORG_ID"],
						'tgl_invoice' => $key["TGL_INVOICE"],
						'year' => $key["YEAR"],
						'bln' => $key["BLN"],
						'days' => $key["DAYS"],
						'periode' => $key["PERIODE"],
						'jenis' => $key["JENIS"],
						'no_performa' => $key["NO_PERFORMA"],
						'no_invoice' => $key["NO_INVOICE"],
						'id_paket' => $key["ID_PAKET"],
						'inventory_item_id' => $key["INVENTORY_ITEM_ID"],
						'item_code' => $key["ITEM_CODE"],
						'nama_produk' => $key["NAMA_PRODUK"],
						'kemasan' => $key["KEMASAN"],
						'site_number' => $key["SITE_NUMBER"],
						'namalang' => $key["NAMALANG"],
						'almtl1' => $key["ALMTL1"],
						'banyak' => $key["BANYAK"],
						'hna' => $key["HNA"],
						'thna' => $key["THNA"],
						'dharga' => $key["DHARGA"],
						'prsnd' => $key["PRSND"],
						'rpbonus' => $key["RPBONUS"],
						'perbonus' => $key["PERBONUS"],
						'rpphb' => $key["RPPHB"],
						'perphb' => $key["PERPHB"],
						'bulan' => $key["BULAN"],
						'tahun' => $key["TAHUN"],
						'kode_cab' => $key["KODE_CAB"],
						'nama_cab' => $key["NAMACAB"],
						'no_pi' => $key["NO_PI"],
						'tgl_pi' => $key["TGL_PI"],
						'almtlang' => $key["ALMTLANG"],
						'sales_type' => $key["SALES_TYPE"],
						'klsout' => $key["KLSOUT"],
						'no_referensi_retur' => $key["NO_REFERENSI_RETUR"],
						'tgl_eks_ref_retur' => $key["TGL_EKS_REF_RETUR"],
						'no_dpl' => $key["NO_DPL"],
						'salesman' => $key["SALESMAN"],
						'id' => $id
					);
					$proses = $this->db->insert('mpi.tbl_mpi', $data);
				}	
				$sql = "select * from mpi.tbl_mpi where id = $id limit 10";
				$hasil = $this->db->query($sql);
				// $hasil = $this->db->get('user');
				if ($hasil->num_rows() > 0) {
					return $hasil->result();
				} else {
					return array();
				}
			}else{
				return array();
			}
	}

	public function omzet_mpi_lokal($data)
    {
		$id=$this->session->userdata('id');
		$from = $data['from'];
		$to = $data['to'];		
		$client = new Client();

		try {
			// $response = $client->request('GET', "http://localhost:81/restapi/api/mpi/", [
			$response = $client->request('GET', "http://site.muliaputramandiri.com/restapi/api/mpi/", [
				'query' => [
					'X-API-KEY' => '123',
					'periode1' => $from,
					'periode2' => $to,
				]
			]);

			$result = json_decode($response->getBody()->getContents(),true);
			if($result)
			{
				$sql = "delete from mpi.tbl_mpi where id = $id";
				$proses = $this->db->query($sql);

				foreach ($result['data'] as $key) 
				{
					$data = array(
						'org_id' => $key["org_id"],
						'tgl_invoice' => $key["tgl_invoice"],
						'year' => $key["year"],
						'bln' => $key["bln"],
						'days' => $key["days"],
						'periode' => $key["periode"],
						'jenis' => $key["jenis"],
						'no_performa' => $key["no_performa"],
						'no_invoice' => $key["no_invoice"],
						'id_paket' => $key["id_paket"],
						'inventory_item_id' => $key["inventory_item_id"],
						'item_code' => $key["item_code"],
						'nama_produk' => $key["nama_produk"],
						'kemasan' => $key["kemasan"],
						'site_number' => $key["site_number"],
						'namalang' => $key["namalang"],
						'almtl1' => $key["almtl1"],
						'banyak' => $key["banyak"],
						'hna' => $key["hna"],
						'thna' => $key["thna"],
						'dharga' => $key["dharga"],
						'prsnd' => $key["prsnd"],
						'rpbonus' => $key["rpbonus"],
						'perbonus' => $key["perbonus"],
						'rpphb' => $key["rpphb"],
						'perphb' => $key["perphb"],
						'bulan' => $key["bulan"],
						'tahun' => $key["tahun"],
						'kode_cab' => $key["kode_cab"],
						'nama_cab' => $key["nama_cab"],
						'no_pi' => $key["no_pi"],
						'tgl_pi' => $key["tgl_pi"],
						'almtlang' => $key["almtlang"],
						'sales_type' => $key["sales_type"],
						'klsout' => $key["klsout"],
						'no_referensi_retur' => $key["no_referensi_retur"],
						'tgl_eks_ref_retur' => $key["tgl_eks_ref_retur"],
						'no_dpl' => $key["no_dpl"],
						'salesman' => $key["salesman"],
						'id' => $id,
						'region' => $key["region"],
						'wilayah' => $key["wilayah"],
						'divisi' => $key["divisi"],
						
					);
					$proses = $this->db->insert('mpi.tbl_mpi', $data);
				}	
				$sql = "select * from mpi.tbl_mpi where id = $id limit 10";
				$hasil = $this->db->query($sql);
				if ($hasil->num_rows() > 0) {
					return $hasil->result();
				} else {
					return array();
				}
			}
		} catch (RequestException $e) {
            return array();   
        }
	}

    public function data_stock_mpi()
	{
		$sql = "
            select  a.cut_off, a.last_update, b.username, date(a.cut_off) as tgl, 
                    sum(a.onhand) as onhand, sum(a.git) as git, sum(a.onhand+a.git) as stock,
                    sum((a.onhand + a.git) * a.hna) as stock_value
            from mpi.t_stock_mpi a LEFT JOIN mpm.`user` b on a.id = b.id
            GROUP BY a.cut_off
            ORDER BY a.cut_off desc
		";
		$hasil = $this->db->query($sql);
		if ($hasil->num_rows() > 0) {
			return $hasil->result();
		} else {
			return array();
		}
    }

    public function insert_stock_mpi(){
        $id = $this->session->userdata('id');
        if ( function_exists( 'date_default_timezone_set' ) )
        date_default_timezone_set('Asia/Jakarta');  
        $created=date('Y-m-d H:i:s');

		$client = new Client();

		// $response = $client->request('GET', "http://222.165.240.170:8012/report-api/public/stock_data", [
		// 	'query' => [
		// 		'key' => 'c089ed651ff206a7361406b0a1929db0'
		// 	]
		// ]);
		$response = $client->request('GET', "http://222.165.240.170:8012/report-api/public/stock_data_fusion", [
			'query' => [
				'key' => 'c089ed651ff206a7361406b0a1929db0'
			]
		]);

		$result = json_decode($response->getBody()->getContents(),true);
        // return $result;
        // echo "<pre>";
        // var_dump($result(array()));
        // echo "</pre>";

		if($result != array())
		{
			$sql = "delete from mpi.t_temp_stock_mpi where id = $id";
			$proses = $this->db->query($sql);
		}else{
            echo "b";
        }
		foreach ($result as $key) {
            $data = array(
                'cut_off' => $key["CUT_OFF"],
                'classprod' => $key["CLASSPROD"],
                'class_name' => $key["CLASS_NAME"],
                'item' => $key["ITEM"],
                'produk' => $key["PRODUK"],
                'hna' => $key["HNA"],
                'kemasan' => $key["KEMASAN"],
                'lot_number' => $key["LOT_NUMBER"],
                'ed' => $key["ED"],
                'onhand' => $key["ONHAND"],
                'git' => $key["GIT"],
                'branch_id' => $key["BRANCHID"],
                'branch_code' => $key["BRANCHCODE"],
                'branch_name' => $key["CABANG"],
                'receipt_date' => $key["RECEIPT_DATE"],
                'last_activity_date' => $key["LAST_ACTIVITY_DATE"],
                'tgl_git' => $key["TGL_GIT"],
                'id' => $id,
                'last_update' => $created
			);
			$proses = $this->db->insert('mpi.t_temp_stock_mpi', $data);
		}	
		$sql = "select * from mpi.t_temp_stock_mpi where id = $id";
		$hasil = $this->db->query($sql);
		// $hasil = $this->db->get('user');
		if ($hasil->num_rows() > 0) {
			return $hasil->result();
		} else {
			return array();
		}
    }

    public function insert_stock_mpi_to_db()
	{
        $id = $this->session->userdata('id');
        
        $proses = $this->db->query("select cut_off from mpi.t_temp_stock_mpi limit 1")->result();
        foreach ($proses as $x) {
            $cut_off = $x->cut_off;
        }

        $sql = "select * from mpi.t_stock_mpi a where a.cut_off = '$cut_off' limit 1";
        $proses = $this->db->query($sql)->num_rows();
        echo "proses: ".$proses;
        if ($proses > 0) {
            $proses = $this->db->query("delete from mpi.t_stock_mpi where cut_off='$cut_off'");
        }

		$sql = "
			insert into mpi.t_stock_mpi
			select * from mpi.t_temp_stock_mpi where id = $id
		";
		$hasil = $this->db->query($sql);
		if ($hasil) {
			echo "<script>
			alert('Insert Berhasil');
			window.location.href='../../mpi/insert_stock_mpi';
			</script>";
		} else {
			echo '<script>alert("Insert gagal. Harap hubungi IT !");</script>';
		}
    }

    public function monitoring_stock_mpi($cut_off_stock,$avg)
	{
        $id = $this->session->userdata('id');
        if ( function_exists( 'date_default_timezone_set' ) )
        date_default_timezone_set('Asia/Jakarta');  
        $created=date('Y-m-d H:i:s');

        // echo "avg : ".$avg."<br>";
        // echo "cut_off_stock : ".$cut_off_stock."<br>";

        $sql = "
            select 	date(a.cut_off) as cut_off
            from	mpi.t_stock_mpi a
            where 	date(a.cut_off) <= '$cut_off_stock'
            ORDER BY a.cut_off desc
            limit 1
        ";
        $proses = $this->db->query($sql)->result();
        foreach ($proses as $key) {
            $cut_off_stockx = $key->cut_off;
        }

        $del = $this->db->query("delete from mpi.t_temp_monitoring_stock_mpi where id = $id");
        $del = $this->db->query("delete from mpi.t_temp_monitoring_stock_sales_mpi where id = $id");
        $del = $this->db->query("delete from mpi.t_temp_monitoring_sales_berjalan_mpi where id = $id");
        $del = $this->db->query("delete from mpi.t_temp_monitoring_doi_mpi where id = $id");

		$sql = "
            insert into mpi.t_temp_monitoring_stock_mpi
            select a.item, a.produk, a.branch_id, a.branch_code, a.branch_name, 
            sum(a.onhand) as onhand_unit, sum(a.git) as git_unit, a.hna, sum(a.onhand) * a.hna as onhand_value,sum(a.git) * a.hna as git_value, 
            $id, '$created'
            from
            (
                select	a.item, a.produk, a.branch_id, a.branch_code, a.branch_name, a.onhand, a.git, a.hna
                from	mpi.t_stock_mpi a
                where 	date(a.cut_off) = '$cut_off_stockx'
            )a GROUP BY a.item, a.branch_code
		";
        $proses = $this->db->query($sql);

        // echo "<pre><br><br><br><br>";
        // print_r($sql);
        // echo "</pre>";

        //mencari max lastupdated dari setiap bulan

        $sql_del_datampi_temp = $this->db->query("delete from mpi.datampi_temp where created_by = $id");

        $sql_cari_max_lastupdated = "
            select  a.bulan, max(a.last_updated) as last_updated_x
            from    mpi.datampi a
            where   a.periode >= date_format(date(now()) - INTERVAL '$avg' MONTH, '%Y-%m') and
                        a.periode <= date_format(date(now()), '%Y-%m')
            GROUP BY a.bulan
        ";
        $proses_cari_max_lastupdated = $this->db->query($sql_cari_max_lastupdated)->result();
        foreach($proses_cari_max_lastupdated as $a){
            $sql_insert = "
                insert into mpi.datampi_temp
                select 	a.*, $id 
                from 	mpi.datampi a
                where 	a.bulan = $a->bulan and last_updated = '$a->last_updated_x'
            ";
            $proses_insert = $this->db->query($sql_insert);

        }


        $sql = "
            insert into mpi.t_temp_monitoring_stock_sales_mpi
            select	a.tgl_invoice,a.kode_cab, a.nama_cab,a.item_code, a.nama_produk, a.kemasan,sum(a.banyak) as total_unit,sum(a.banyak)/$avg as avg_unit, 
                    a.hna, sum(a.thna) as total_value, sum(a.thna)/$avg as avg_value, $id, '$created'
            from    mpi.datampi_temp a
            where   a.created_by = $id and a.periode >= date_format(date(now()) - INTERVAL '$avg' MONTH, '%Y-%m') and
                    a.periode <= date_format(date(now()), '%Y-%m')
            GROUP BY a.item_code, a.kode_cab
		";
        $proses = $this->db->query($sql);

        // echo "<pre>";
        // print_r($sql);
        // echo "</pre>";

        $sql = "
            insert into mpi.t_temp_monitoring_sales_berjalan_mpi
            select	a.tgl_invoice,a.kode_cab, a.nama_cab,a.item_code, a.nama_produk, a.kemasan,sum(a.banyak) as total_unit,sum(a.banyak)/$avg as avg_unit, 
                    a.hna, sum(a.thna) as total_value, sum(a.thna)/$avg as avg_value, $id, '$created'
            from    mpi.datampi_temp a
            where   a.created_by = $id and a.periode = date_format(date(now()), '%Y-%m')
            GROUP BY a.item_code, a.kode_cab
		";
        $proses = $this->db->query($sql);

        // echo "<pre>";
        // print_r($sql);
        // echo "</pre>";

        $sql = "
            insert into mpi.t_temp_monitoring_doi_mpi
            select	'$cut_off_stockx',a.kode_cab, a.nama_cab, a.item_code, a.nama_produk, a.kemasan, 
                    '' as kodeprod, '' as namaprod, '' as satuan, '' as `group`, '' as nama_group, '' as supp, '' as principal,
                    a.total_unit, a.avg_unit, a.total_value, a.avg_value, '' as unit_berjalan, '' as value_berjalan,
                    b.onhand_unit, b.git_unit, (b.onhand_unit / a.avg_unit * 30) as doi_onhand_unit, 
                    ((b.onhand_unit + b.git_unit) / a.avg_unit * 30) as doi_stock_unit, 
                    b.onhand_value, b.git_value, (b.onhand_value / a.avg_value * 30) as doi_onhand_value, 
                    ((b.onhand_value + b.git_value) / a.avg_value * 30) as doi_stock_value,
                    '' as po_outstanding_unit, '' as po_outstanding_value,
                    $id, '$created'			
            from 	mpi.t_temp_monitoring_stock_sales_mpi a LEFT JOIN (
                select 	a.item, a.produk, a.branch_id, a.branch_code, a.branch_name, a.onhand_unit, a.git_unit,a.onhand_value, a.git_value
                from 	mpi.t_temp_monitoring_stock_mpi a
                where 	a.id = $id
            )b on a.kode_cab = b.branch_id and a.item_code = b.item 
            where 	a.id = $id
            ORDER BY a.nama_cab, a.nama_produk
		";
        $proses = $this->db->query($sql);

        // echo "<pre>";
        // print_r($sql);
        // echo "</pre>";

        $sql = "                
            update mpi.t_temp_monitoring_doi_mpi a
            set a.kodeprod = (
                select b.kodeprod
                from mpi.t_produk_mapping b
                where a.item_code = b.kodeprod_mpi
            ),a.namaprod = (
                select b.namaprod
                from mpi.t_produk_mapping b
                where a.item_code = b.kodeprod_mpi
            ),a.satuan = (
                select b.satuan
                from mpi.t_produk_mapping b
                where a.item_code = b.kodeprod_mpi
            ),a.supp = (
                select b.supp
                from mpi.t_produk_mapping b
                where a.item_code = b.kodeprod_mpi
            ),  a.`group` = (
                select c.`grup`
                from mpm.tabprod c
                where a.kodeprod =c.kodeprod
            ), a.nama_group = (
                select d.nama_group
                from mpm.tbl_group d
                where a.`group` = d.kode_group
            ), a.principal = (
                select e.namasupp
                from mpm.tabsupp e
                where a.supp = e.supp
            )
            where a.id = $id
        ";
        $proses = $this->db->query($sql);

		// echo "<pre>";
        // print_r($sql);
        // echo "</pre>";

        $sql = "                
            update mpi.t_temp_monitoring_doi_mpi a
            set a.unit_berjalan = (
                select b.total_unit
                from mpi.t_temp_monitoring_sales_berjalan_mpi b
                where a.kode_cab = b.kode_cab and a.item_code = b.item_code and b.id = $id      
            ),a.value_berjalan = (
                select b.total_value
                from mpi.t_temp_monitoring_sales_berjalan_mpi b
                where a.kode_cab = b.kode_cab and a.item_code = b.item_code and b.id = $id      
            )
            where a.id = $id
        ";
        $proses = $this->db->query($sql);

        // echo "<pre>";
        // print_r($sql);
        // echo "</pre>";

        $hasil = $this->db->query("select * from mpi.t_temp_monitoring_doi_mpi where id=$id");

		if ($hasil->num_rows() > 0) {
			return $hasil->result();
		} else {
			return array();
		}

    }
}