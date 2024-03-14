<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Model_mt extends CI_Model 
{
    function insert($data)
	{
        $this->db->query("truncate db_retur.t_import_retur");
		$this->db->insert_batch('db_retur.t_import_retur', $data);
    }

    function history_import($table, $params_where)
    {
        $userid = $this->session->userdata('id');
        if ($userid == '297' || $userid == '11' || $userid == '140' || $userid == '63') { //jika user suffy, felix, tgr, sadmin
            $params_site_code = "";
        }else{
            $params_site_code = " and a.site_code in ($params_where)";
        }
        $sql = "
            select 	a.id, a.site_code, a.partner, 
                    a.filename_csv, concat(left(a.filename_csv,15), ' ...') as filename_csv_cut, 
                    a.filename_pdf, concat(left(a.filename_pdf,15), ' ...') as filename_pdf_cut, 
                    a.signature, 
                    a.`status`, a.status_mapping_kodeprod, a.status_mapping_outlet, a.created_at,
                    a.created_by,
                    b.branch_name, b.nama_comp
            from $table a LEFT JOIN
            (
                select concat(a.kode_comp, a.nocab) as site_code, a.branch_name, a.nama_comp
                from mpm.tbl_tabcomp a 
                where a.status = 1
                GROUP BY concat(a.kode_comp, a.nocab)
            )b on a.site_code = b.site_code
            where a.deleted is null $params_site_code
            order by id desc
        ";

        // echo "<pre><br><br><br>";
        // print_r($sql);
        // echo "</pre>";

        return $this->db->query($sql);
    }

    function import_ranch($data){

        echo $data['site_code'];

        // die;


        $this->load->library('upload'); // Load librari upload        
        $config['upload_path'] = './assets/uploads/import_mt/';  
        $config['allowed_types'] = '*';    
        $config['max_size']  = '5000';    
        $config['overwrite'] = false;   
        $this->upload->initialize($config); 
    
        // Load konfigurasi uploadnya    
        if($this->upload->do_upload('file'))
        { 

            // echo "aa";

            // die;
          $upload_data = $this->upload->data();
          $filename = $upload_data['file_name'];
          $this->load->library('excel');
          $object = PHPExcel_IOFactory::load("assets/uploads/import_mt/$filename");

            foreach($object->getWorksheetIterator() as $worksheet)
            {
                $highestRow = $worksheet->getHighestRow();
                $highestColumn = $worksheet->getHighestColumn();

                for($row=2; $row<=$highestRow; $row++)
                {

                    echo $worksheet->getCellByColumnAndRow(0, $row)->getValue();
                   
                    $data[] = array(
                        'site_code'         => $data['site_code'],
                        'po_number'		    => $worksheet->getCellByColumnAndRow(0, $row)->getValue(),
                        'po_group'		    => $worksheet->getCellByColumnAndRow(1, $row)->getValue(),
                        'supplier_name'     => $worksheet->getCellByColumnAndRow(2, $row)->getValue(),  
                        'supplier_code'     => $worksheet->getCellByColumnAndRow(3, $row)->getValue(),  
                        'contact_person'    => $worksheet->getCellByColumnAndRow(4, $row)->getValue(),  
                        'delivery_to_name'  => $worksheet->getCellByColumnAndRow(5, $row)->getValue(),  
                        'delivery_location_code' => $worksheet->getCellByColumnAndRow(6, $row)->getValue(),  
                        'line_item_no'      => $worksheet->getCellByColumnAndRow(7, $row)->getValue(),  
                        'product_code'      => $worksheet->getCellByColumnAndRow(8, $row)->getValue(),  
                        'item_barcode'      => $worksheet->getCellByColumnAndRow(9, $row)->getValue(),  
                        'order_quantity'    => $worksheet->getCellByColumnAndRow(10, $row)->getValue(),  
                        'uom'               => $worksheet->getCellByColumnAndRow(11, $row)->getValue(),  
                        'uom_pack_size'     => $worksheet->getCellByColumnAndRow(12, $row)->getValue(),  
                        'uom_inner'         => $worksheet->getCellByColumnAndRow(13, $row)->getValue(),  
                        'unit_price'        => $worksheet->getCellByColumnAndRow(14, $row)->getValue(),  
                        'discount'          => $worksheet->getCellByColumnAndRow(15, $row)->getValue(),  
                        'line_item_total'   => $worksheet->getCellByColumnAndRow(16, $row)->getValue(),  
                        'item_descriptions' => $worksheet->getCellByColumnAndRow(17, $row)->getValue(),  
                        'expected_delivery_date'     => $worksheet->getCellByColumnAndRow(18, $row)->getValue(),  
                        'line_total_dc_alw_x_dock'   => $worksheet->getCellByColumnAndRow(19, $row)->getValue(),  
                        'line_total_dmg_allowance'   => $worksheet->getCellByColumnAndRow(20, $row)->getValue(),  
                        'line_vat_total'    => $worksheet->getCellByColumnAndRow(21, $row)->getValue(),  
                        'grand_total'       => $worksheet->getCellByColumnAndRow(22, $row)->getValue(),  
                        'po_order_date'     => $worksheet->getCellByColumnAndRow(23, $row)->getValue(),
                        'created_at'        => $data['tgl'], 
                        'created_by'        => $data['created_by'], 
                    );
                }
            }
            $insert = $this->model_inventory->insert_ms($data, 'db_mt.t_ranch');
        }else{  
            $return = array('result' => 'failed', 'file' => '', 'error' => $this->upload->display_errors());      
            echo "<pre>";
            print_r($return);
            echo "</pre>";
        }
        
    }

    public function get_log($signature){
        // echo $signature;
        $sql = "
        select b.site_code, c.branch_name, c.nama_comp, b.partner, a.id_history, a.signature, a.`status`, a.created_at, a.dokumen, a.alasan, a.tanggal_terima
        from site.log_history a LEFT JOIN
        (
            select a.id, a.site_code, a.partner
            from site.t_history_import a
        )b on a.id_history = b.id LEFT JOIN
        (
            select concat(a.kode_comp, a.nocab) as site_code, a.branch_name, a.nama_comp
            from mpm.tbl_tabcomp a 
            where a.`status` = 1
            GROUP BY concat(a.kode_comp, a.nocab)
        )c on b.site_code = c.site_code
        where a.signature = '$signature'
        order by a.id desc
        ";

        $proses = $this->db->query($sql);
        // echo "<pre><br><br><br>";
        // print_r($sql);
        // echo "</pre>";
        return $proses;
    }

    public function get_log_header($signature){
        // echo $signature;
        $sql = "
        select 	a.id, a.site_code, a.partner, 
                a.filename_csv, concat(left(a.filename_csv,15), ' ...') as filename_csv_cut, 
                a.filename_pdf, concat(left(a.filename_pdf,15), ' ...') as filename_pdf_cut, 
                a.signature, 
                a.`status`, a.status_mapping_kodeprod, a.status_mapping_outlet, a.created_at,
                a.created_by,
                b.branch_name, b.nama_comp, c.username
        from site.t_history_import a LEFT JOIN
        (
            select concat(a.kode_comp, a.nocab) as site_code, a.branch_name, a.nama_comp
            from mpm.tbl_tabcomp a 
            where a.status = 1
            GROUP BY concat(a.kode_comp, a.nocab)
        )b on a.site_code = b.site_code LEFT JOIN 
        (
            select id, username
            from mpm.user a             
        )c on a.created_by = c.id
        where a.signature = '$signature'
        order by id desc
        ";

        $proses = $this->db->query($sql);
        // echo "<pre><br><br><br>";
        // print_r($sql);
        // echo "</pre>";
        return $proses;
    }

    public function get_detail($signature){
        // echo $signature;
        $sql = "
        select 	a.site_code, a.po_number, a.po_group, a.supplier_name, a.supplier_code, a.delivery_to_name,
				a.line_item_no, a.product_code, a.item_descriptions, a.kodeprod_mpm, a.customer_code_mpm,
                c.branch_name, c.nama_comp, date(a.po_order_date) as po_order_date, a.created_at, d.username,
                a.qty1, a.qty2, a.qty3, a.uom_pack_size, a.order_quantity
        from site.t_import a INNER join 
        (
            select a.id, a.signature
            from site.t_history_import a 
        )b on a.id_history = b.id left join 
        (
            select concat(a.kode_comp, a.nocab) as site_code, a.branch_name, a.nama_comp
            from mpm.tbl_tabcomp a 
            where a.`status` = 1
            group by concat(a.kode_comp, a.nocab)
        )c on a.site_code = c.site_code left join
        (
            select a.id, a.username
            from mpm.user a 
        )d on a.created_by = d.id
        where b.signature = '$signature'
        ";
        // echo "<pre><br><br><br>";
        // print_r($sql);
        // echo "</pre>";

        $proses = $this->db->query($sql);
        return $proses;
    }

    public function mapping_mpm($id_history, $partner){
        $update_detail = "
            update site.t_import a 
            set a.kodeprod_mpm = (
                select b.kodeprod_mpm
                from site.map_product_mt b
                where a.product_code = b.kodeprod_partner and b.partner_id = '$partner'
            ), a.customer_code_mpm = (
                select c.customer_code_mpm
                from site.map_outlet_mt c
                where a.delivery_location_code = c.customer_code_partner and c.partner_id = '$partner'
            ), a.site_code = (
                select c.site_code
                from site.map_outlet_mt c
                where a.delivery_location_code = c.customer_code_partner and c.partner_id = '$partner'
            ), a.qty1 = (
                select d.qty1
	            from mpm.tabprod d
                where a.kodeprod_mpm = d.kodeprod
            ), a.qty2 = (
                select e.qty2
	            from mpm.tabprod e
                where a.kodeprod_mpm = e.kodeprod
            ), a.qty3 = (
                select f.qty3
	            from mpm.tabprod f
                where a.kodeprod_mpm = f.kodeprod
            ), a.order_in_sachet = (
                IF(a.uom_pack_size = a.qty1, convert(a.order_quantity, int) * (a.qty1 * a.qty3), 
                IF(a.uom_pack_size = a.qty2, convert(a.order_quantity, int) * (a.qty2 * a.qty3),
                IF(a.uom_pack_size = a.qty3, convert(a.order_quantity, int), null)))
            )
            where a.id_history = $id_history
        ";
        $proses_detail = $this->db->query($update_detail);

        $update_header = "
            update site.t_history_import a
            set a.site_code = (
                select b.site_code
                from site.t_import b
                where a.id = b.id_history and b.id_history = $id_history
                group by b.site_code
            )where a.id = $id_history
        ";
        $proses_header = $this->db->query($update_header);
        
        
        return $proses_header;
    }

    public function get_header($signature){
        $get_header = "
            select *
            from site.t_history_import a 
            where a.signature = '$signature'
        ";
        return $this->db->query($get_header);
    }

    public function update_status($id_history){

        $update_status_kodeprod_failed = "
            update site.t_history_import a left join 
            (
                select a.id_history, a.kodeprod_mpm, a.site_code
                from site.t_import a
                where a.id_history = $id_history
            )b on a.id = b.id_history
            set a.status_mapping_kodeprod = 'failed'
            where b.kodeprod_mpm is null and a.id = $id_history
        ";
        $this->db->query($update_status_kodeprod_failed);

        $update_status_kodeprod_success = "
            update site.t_history_import a left join 
            (
                select a.id_history, a.kodeprod_mpm, a.site_code
                from site.t_import a
                where a.id_history = $id_history
            )b on a.id = b.id_history
            set a.status_mapping_kodeprod = 'success'
            where b.kodeprod_mpm is not null and a.id = $id_history
        ";
        $this->db->query($update_status_kodeprod_success);

        $update_status_outlet_failed = "
            update site.t_history_import a inner join 
            (
                select a.id_history, a.customer_code_mpm, a.site_code
                from site.t_import a
                where a.id_history = $id_history
            )b on a.id = b.id_history
            set a.status_mapping_outlet = 'failed', a.site_code = b.site_code
            where b.customer_code_mpm is null and a.id = $id_history
        ";
        $proses = $this->db->query($update_status_outlet_failed);

        $update_status_outlet_success = "
            update site.t_history_import a inner join 
            (
                select a.id_history, a.customer_code_mpm, a.site_code
                from site.t_import a
                where a.id_history = $id_history
            )b on a.id = b.id_history
            set a.status_mapping_outlet = 'success', a.site_code = b.site_code
            where b.customer_code_mpm is not null and a.id = $id_history
        ";
        $proses = $this->db->query($update_status_outlet_success);

        return $proses;
    }
    
}