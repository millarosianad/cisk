<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require 'vendor/autoload.php';
use GuzzleHttp\Client;
use GuzzleHttp\Psr7;
use GuzzleHttp\Exception\RequestException;

class Model_backup extends CI_Model
{
    public function getFi(){    

        $client = new Client();
		$response = $client->request('GET', "http://site.muliaputramandiri.com/restapi/api/backup/backupFi", [
			'query' => [
				'tahun'         => '2021',
				'bulan'         => '4',
				'limit_from'    => '0',
				'limit_to'      => '2',
				'token'         => '64646ecaf773b3192034998fccbb27b5',
                'X-API-KEY'     => '123'
			]
		]);

		$result = json_decode($response->getBody()->getContents(),true);

        foreach ($result['data'] as $key) {
            $data = array(
                'KDDOKJDI' => $key["KDDOKJDI"],
                'NODOKJDI' => $key["NODOKJDI"],
                'NODOKACU' => $key["NODOKACU"],
                'TGLDOKJDI' => $key["TGLDOKJDI"],
                'KODESALES' => $key["KODESALES"],
                'KODE_COMP' => $key["KODE_COMP"],
                'KODE_KOTA' => $key["KODE_KOTA"],
                'KODE_TYPE' => $key["KODE_TYPE"],
                'KODE_LANG' => $key["KODE_LANG"],
                'KODERAYON' => $key["KODERAYON"],
                'KODEPROD' => $key["KODEPROD"],
                'SUPP' => $key["SUPP"],
                'HRDOK' => $key["HRDOK"],
                'BLNDOK' => $key["BLNDOK"],
                'THNDOK' => $key["THNDOK"],
                'NAMAPROD' => $key["NAMAPROD"],
                'GRUPPROD' => $key["GRUPPROD"],
                'BANYAK' => $key["BANYAK"],
                'HARGA' => $key["HARGA"],
                'POTONGAN' => $key["POTONGAN"],
                'TOT1' => $key["POTONGAN"],
                'JUM_PROMO' => $key["JUM_PROMO"],
                'KETERANGAN' => $key["KETERANGAN"],
                'USER_ISI' => $key["USER_ISI"],
                'JAM_ISI' => $key["JAM_ISI"],
                'TGL_ISI' => $key["TGL_ISI"],
                'USER_EDIT' => $key["USER_EDIT"],
                'JAM_EDIT' => $key["JAM_EDIT"],
                'TGL_EDIT' => $key["TGL_EDIT"],
                'USER_DEL' => $key["USER_DEL"],
                'JAM_DEL' => $key["JAM_DEL"],
                'TGL_DEL' => $key["TGL_DEL"],
                'NO' => $key["NO"],
                'BACKUP' => $key["BACKUP"],
                'NO_URUT' => $key["NO_URUT"],
                'KODE_GDG' => $key["KODE_GDG"],
                'NAMA_GDG' => $key["NAMA_GDG"],
                'KODESALUR' => $key["KODESALUR"],
                'KODEBONUS' => $key["KODEBONUS"],
                'NAMABONUS' => $key["NAMABONUS"],
                'GRUPBONUS' => $key["GRUPBONUS"],
                'UNITBONUS' => $key["UNITBONUS"],
                'LAMPIRAN' => $key["LAMPIRAN"],
                'H_BELI' => $key["H_BELI"],
                'KODEAREA' => $key["KODEAREA"],
                'NAMAAREA' => $key["NAMAAREA"],
                'PINJAM' => $key["PINJAM"],
                'JUALBANYAK' => $key["JUALBANYAK"],
                'JUALPINJAM' => $key["JUALPINJAM"],
                'HARGA_EXCL' => $key["HARGA_EXCL"],
                'TOT1_EXCL' => $key["TOT1_EXCL"],
                'NAMA_LANG' => $key["NAMA_LANG"],
                'NOCAB' => $key["NOCAB"],
                'BULAN' => $key["BULAN"],
                'siteid' => $key["siteid"],
                'qty1' => $key["qty1"],
                'qty2' => $key["qty2"],
                'qty3' => $key["qty3"],
                'qty_bonus' => $key["qty_bonus"],
                'flag_bonus' => $key["flag_bonus"],
                'disc_persen' => $key["disc_persen"],
                'disc_rp' => $key["disc_rp"],
                'disc_value' => $key["disc_value"],
                'disc_cabang' => $key["disc_cabang"],
                'disc_prinsipal' => $key["disc_prinsipal"],
                'disc_xtra' => $key["disc_xtra"],
                'rp_cabang' => $key["rp_cabang"],
                'rp_prinsipal' => $key["rp_prinsipal"],
                'rp_xtra' => $key["rp_xtra"],
                'bonus' => $key["bonus"],
                'prinsipalid' => $key["prinsipalid"],
                'ex_no_sales' => $key["ex_no_sales"],
                'status_retur' => $key["status_retur"],
                'ref' => $key["ref"],
                
			);
			$proses = $this->db->insert('db_temp.fi', $data);
		}	

    }

    public function insert_mpi($data){
		$id=$this->session->userdata('id');
		$from = $data['from'];
		$to = $data['to'];
		
		$client = new Client();

		$response = $client->request('GET', "http://222.165.240.170:8012/report-api/public/sales_data/$from/$to", [
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
			window.location.href='../../omzet/insert_mpi';
			</script>";
		} else {
			echo '<script>alert("Insert gagal. Harap hubungi IT !");</script>';
		}

	}
	
}