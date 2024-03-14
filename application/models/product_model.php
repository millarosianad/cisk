<?php 
if( ! defined('BASEPATH')) exit('Akses langsung tidak diperkenankan');

class Product_model extends CI_Model
{
    var $total_query='';
    var $output_table = '';
    var $output_print='';
    var $tmpl=array(
                'table_open'=>'<table class="table" style="font-size:12px">'
                ,'heading_start'=>'<thead>'
                ,'heading_end'=>'</thead>'
                ,'heading_row_start' =>'<tr class="success">'
                ,'heading_row_end'   =>'</tr>'
                //,'heading_cell_start'=>'<th bgcolor = "#666666">'
                ,'heading_cell_start'=>'<th>'
                ,'heading_cell_end'=>'</th>'

                ,'body_start'=>'<tbody>'
                ,'body_end'=>'</tbody>'
                ,'row_start' =>'<tr>'
                ,'row_end'   =>'</tr>'
                ,'cell_start'=>'<td>'
                ,'cell_end'=>'</td>'

                ,'row_alt_start' =>'<tr class="success">'
                ,'row_alt_end'   =>'</tr>'
                ,'cell_alt_start'=>'<td>'
                ,'cell_alt_end'=>'</td>'
        
                ,'foot_start'=>'<tfoot>'
                ,'foot_end'=>'</tfoot>'
                ,'foot_row_start' =>'<tr class="danger">'
                ,'foot_row_end'   =>'</tr>'
                ,'foot_cell_start'=>'<th>'
                ,'foot_cell_end'=>'</th>'
                ,'table_close'=>'</table>'
                );
    public function Product_model()
    {
        set_time_limit(0);

        $this->load->database();
        $this->load->library(array('table','session'));//session untuk sisi administrasi
        $this->load->helper(array('text_helper','array_helper'));
        //$this->config->load('sorot');
    }
    public function check($table,$field,$str)
    {
        $sql='select 1 from mpm.'.$table.' where '.$field.' = "'.$str.'"';
        //$sql='select 1 from mpm.tabprod where kodeprod = ?';
        $query = $this->db->query($sql,array($str));
        if($query->num_rows()>0)
        {
            return FALSE;
        }
        else
        {
            return TRUE;
        }
    }
    public function getTotalQuery()
    {
        return $this->total_query;
    }
    public function getSupp()
    {
        $sql='select supp, namasupp from mpm.tabsupp';
        return $this->db->query($sql);
    }
    public function getSuppDetail($id='')
    {
        $sql='select supp, namasupp, npwp, alamat_wp,telp,email from mpm.tabsupp where supp=?';
        return $this->db->query($sql,array($id));
    }
    public function list_product()
    {
        return $this->db->query('select kodeprod, namaprod from mpm.tabprod  where active=1 order by namaprod');
    }
    public function getProduct($kodeprod='')
    {
        return $this->db->query('select supp,kodeprod,namaprod,kode_prc,grupprod,satuan,isisatuan,odrunit,h_dp,h_pbf,h_bsp from mpm.tabprod where kodeprod="'.$kodeprod.'"');
    }
    public function getProdName($kode='')
    {
        $sql='select namaprod from mpm.tabprod where kodeprod="'.$kode.'"';
        $query=$this->db->query($sql);
        $row= $query->row();

        echo "<pre>";
        print_r($sql);
        echo "</pre>";

        return $row->namaprod;
    }
    public function getDetail($id='')
    {
        $sql='select * from mpm.prod_detail where id=?';
        $query=$this->db->query($sql,array($id));

        echo "<pre>";
        print_r($sql);
        echo "</pre>";

        return $query->row();
    }
    public function print_supp()
    {
        $sql='select supp, namasupp, npwp, alamat_wp,telp,email,active from mpm.tabsupp';
        $query = $this->db->query($sql);
        $this->load->library('cezpdf');
        $this->cezpdf->Cezpdf('A4','portrait');

        foreach($query->result() as $row)
        {
            if($row->active==1)
            {
                $act='YES';
            }
            else
            $act='NO';
            $db_data[] = array(
                'supp'     => $row->supp,
                'namasupp' => $row->namasupp,
                'npwp'     => $row->npwp,
                'alamat'   => $row->alamat_wp,
                'telp'     => $row->telp,
                'email'    => $row->email,
                'active'   => $act,
                );
        }
        $col_names = array(
        'supp' => 'Code',
        'namasupp' => 'Supplier',
        'npwp' => 'npwp',
        'alamat' => 'alamat_wp',
        'telp' => 'telp',
        'email' => 'email',
        'active' =>'Active',
        );
        $this->cezpdf->ezTable($db_data, $col_names, 'Table Of Supplier', array('width'=>10,'fontSize' => 7));
        $this->cezpdf->ezStream();
    }
    
    public function delete_supp($id='')
    {
        $sql='delete from mpm.tabsupp where supp=?';

        $this->db->trans_begin();
        $this->db->query($sql,array($id));
        $this->db->trans_complete();

        if($this->db->trans_status()===FALSE)
        {
            $this->db->trans_rollback();
        }
        else
        {
            $this->db->trans_commit();
        }
        redirect('product/show_supp');
    }
    public function save_supp($id=0)
    {
        $id=$this->session->userdata('id');

        $post['created_by']=$id;
        $post['supp']      =$this->input->post('supp');
        $post['namasupp']  =$this->input->post('namasupp');
        $post['telp']      =$this->input->post('telp');
        $post['kota']      =$this->input->post('kota');
        $post['alamat_wp']   =$this->input->post('alamat');
        $post['npwp']      =$this->input->post('npwp');
        $post['email']     =$this->input->post('email');
        $post['created']   =date('Y-m-d H:i:s');
        $this->db->insert('mpm.tabsupp',$post);

        redirect('product/show_supp/');
    }
    public function update_supp($id='')
    {
        $str_id=$this->session->userdata('id');

        //$post['kodeprod']   =$this->input->post('kodeprod');
        $post['namasupp']  =$this->input->post('namasupp');
        $post['telp']      =$this->input->post('telp');
        $post['kota']      =$this->input->post('kota');
        $post['alamat_wp']   =$this->input->post('alamat');
        $post['npwp']      =$this->input->post('npwp');
        $post['email']     =$this->input->post('email');
        $post['modified_by']=$str_id;
        $post['modified']   =date('Y-m-d H:i:s');
        $where='supp='.$id;
        $this->db->trans_begin();
        $sql=$this->db->update_string('mpm.tabsupp',$post,$where);
        $this->db->query($sql);
        $this->db->trans_complete();
        if($this->db->trans_status()===FALSE)
        {
            $this->db->trans_rollback();
        }
        else
        {
            $this->db->trans_commit();
        }
        redirect('product/show_supp/');
    }
    public function price_list($tgl='',$limit=0,$offset=0)
    {
       $image_properties_del=array(
                 'src'    => 'assets/css/images/delete.png',
                 'width'  => '20',
                 'height' => '20',
            );
            $image_properties_edit=array(
                 'src'    => 'assets/css/images/edit.png',
                 'width'  => '20',
                 'height' => '20',
            );
       $sql='select a.*,b.namaprod from mpm.prod_detail a inner join tabprod b using(kodeprod) where tgl like "%'.$tgl.'%" order by kodeprod,tgl';
       $query = $this->db->query($sql);
       $this->total_query = $query->num_rows();
       $sql2= $sql.' limit ? offset ?';
       $query=$this->db->query($sql2,array($limit,$offset));

       if($query->num_rows() > 0)
       {
            $this->load->library('table');
            $this->table->set_empty('0');

            $this->table->set_empty('0');
            $this->table->set_template($this->tmpl);
            $this->table->set_caption('PRICE LIST');
            $this->table->set_heading('KODE','PRODUCT NAME','DATE','BELI_DP','DISC B DP','BELI BSP','DISC B BSP','BELI PBF','DISC B PBF','H.DP','DISC DP','H.BSP','DISC BSP','H.PBF','DISC PBF','EDIT','DELETE');
            foreach ($query->result() as $value)
            {

                $js=array('onclick'=>"return confirm('Are you sure you want to delete?')");
                $this->table->add_row(
                        $value->kodeprod
                        ,$value->namaprod
                        ,$value->tgl
                        ,'<div div style="text-align:right">'.$value->h_beli_dp.'</div>'
                        ,'<div div style="text-align:right">'.$value->d_beli_dp.'</div>'
                        ,'<div div style="text-align:right">'.$value->h_beli_bsp.'</div>'
                        ,'<div div style="text-align:right">'.$value->d_beli_bsp.'</div>'
                        ,'<div div style="text-align:right">'.$value->h_beli_pbf.'</div>'
                        ,'<div div style="text-align:right">'.$value->d_beli_pbf.'</div>'
                        ,'<div div style="text-align:right">'.$value->h_dp.'</div>'
                        ,'<div div style="text-align:right">'.$value->d_dp.'</div>'
                        ,'<div div style="text-align:right">'.$value->h_bsp.'</div>'
                        ,'<div div style="text-align:right">'.$value->d_bsp.'</div>'
                        ,'<div div style="text-align:right">'.$value->h_pbf.'</div>'
                        ,'<div div style="text-align:right">'.$value->d_pbf.'</div>'
                        //,'<div div style="text-align:center">'.$active.'</div>'
                        ,'<div div style="text-align:center">'.anchor('product/edit_detail/'.$value->id.'/'.$value->kodeprod.'/',img($image_properties_edit)).'</div>'
                        //,'<div div style="text-align:center">'.anchor('product/plus_product/'.$value->kodeprod,img($image_properties_plus)).'</div>'
                        //,'<div div style="text-align:center">'.anchor('product/detail_product/'.$value->kodeprod,img($image_properties_detail)).'</div>'
                        ,'<div div style="text-align:center">'.anchor('product/delete_detail/'.$value->id,img($image_properties_del),$js).'</div>'

                );
            }
            $this->output_table .= '<br /><br />';
            //$this->output_table .=anchor('product/add_product/',img($image_add,'ADD'));
            $this->output_table .= '<br />';
            $this->output_table .= $this->table->generate();
            $this->output_table .= '<br />';
            $this->table->clear();
            $query->free_result();
            return $this->output_table;
        }
        else
        {
            return FALSE;
        }

    }
    public function show_supp($limit=0,$offset=0)
    {
        $sql1="select supp,namasupp,email,telp,active from mpm.tabsupp order by supp";
        $query_print = $this->db->query($sql1);
        $this->total_query = $query_print->num_rows();
        $sql2= $sql1.' limit ? offset ?';
        $query = $this->db->query($sql2,array($limit,$offset));

        if($query->num_rows() > 0)
        {
            $this->load->library('table');
            $this->table->set_empty('0');
            $this->table->set_empty('0');
            $this->table->set_template($this->tmpl);
            $this->table->set_caption('LIST SUPPLIER');
            $this->table->set_heading('CODE','SUPPLIER','EMAIL','TELP','ACTIVE','EDIT','DELETE');

            $image_properties_yes=array(
                 'src'    => 'assets/css/images/yes.png',
                 'width'  => '20',
                 'height' => '20',
            );
            $image_properties_no=array(
                 'src'    => 'assets/css/images/no.png',
                 'width'  => '20',
                 'height' => '20',
            );
            $image_properties_del=array(
                 'src'    => 'assets/css/images/delete.png',
                 'width'  => '20',
                 'height' => '20',
            );
            $image_properties_edit=array(
                 'src'    => 'assets/css/images/edit.png',
                 'width'  => '20',
                 'height' => '20',
            );
            $image_add = array(
                 'src'    => 'assets/css/images/ADD.png',
                 'height' => '30',
            );
            foreach ($query->result() as $value)
            {
                switch ($value->active)
                {
                    case 1:$active=anchor('product/activer_supp/0/'.$value->supp,img($image_properties_yes));BREAK;
                    case 0:$active=anchor('product/activer_supp/1/'.$value->supp,img($image_properties_no));BREAK;
                }
                $js=array('onclick'=>"return confirm('Are you sure you want to delete?')");
                $this->table->add_row(
                        $value->supp
                        ,$value->namasupp
                        ,$value->email
                        ,$value->telp
                        ,'<div div style="text-align:center">'.$active.'</div>'
                        ,'<div div style="text-align:center">'.anchor('product/edit_supp/'.$value->supp,img($image_properties_edit)).'</div>'
                        ,'<div div style="text-align:center">'.anchor('product/delete_supp/'.$value->supp,img($image_properties_del),$js).'</div>'

                );
            }
            $this->output_table .= '<br /><br />';
            $this->output_table .=anchor('product/add_supp/',img($image_add,'ADD'));
            $this->output_table .= '<br />';
            $this->output_table .= $this->table->generate();
            $this->output_table .= '<br />';
            $this->table->clear();
            $query->free_result();
            return $this->output_table;
        }
        else
        {
            return FALSE;
        }
    }
    public function activer_product($flag,$id,$offset)
    {
        $sql='update mpm.tabprod set active=? where kodeprod=?';
        $this->db->trans_begin();
        $this->db->query($sql,array($flag,$id));
        $this->db->trans_complete();
        if($this->db->trans_status()===FALSE)
        {
            $this->db->trans_rollback();
        }
        else
        {
            $this->db->trans_commit();
        }
        redirect('product/show_product/'.$offset);
    }
    public function activer_produksi($flag,$id,$offset)
    {
        $sql='update mpm.tabprod set produksi=? where kodeprod=?';
        $this->db->trans_begin();
        $this->db->query($sql,array($flag,$id));
        $this->db->trans_complete();
        if($this->db->trans_status()===FALSE)
        {
            $this->db->trans_rollback();
        }
        else
        {
            $this->db->trans_commit();
        }
        redirect('product/show_product/'.$offset);
    }
    public function activer_report($flag,$id,$offset)
    {
        $sql='update mpm.tabprod set report=? where kodeprod=?';
        $this->db->trans_begin();
        $this->db->query($sql,array($flag,$id));
        $this->db->trans_complete();
        if($this->db->trans_status()===FALSE)
        {
            $this->db->trans_rollback();
        }
        else
        {
            $this->db->trans_commit();
        }
        redirect('product/show_product/'.$offset);
    }
    public function activer_supp($flag,$id)
    {
        $sql='update mpm.tabsupp set active=? where supp=?';
        $this->db->trans_begin();
        $this->db->query($sql,array($flag,$id));
        $this->db->trans_complete();
        if($this->db->trans_status()===FALSE)
        {
            $this->db->trans_rollback();
        }
        else
        {
            $this->db->trans_commit();
        }
        redirect('product/show_supp');
    }
    public function delete_product($id='')
    {
        $sql='delete from mpm.tabprod where kodeprod=?';

        $this->db->trans_begin();
        $this->db->query($sql,array($id));
        $this->db->trans_complete();

        if($this->db->trans_status()===FALSE)
        {
            $this->db->trans_rollback();
        }
        else
        {
            $this->db->trans_commit();
        }
        redirect('product/show_product/'.$this->session->userdata('offset'));
    }
    public function delete_detail($id=null)
    {
        $sql='delete from mpm.prod_detail where id=?';

        $this->db->trans_begin();
        $this->db->query($sql,array($id));
        $this->db->trans_complete();

        if($this->db->trans_status()===FALSE)
        {
            $this->db->trans_rollback();
        }
        else
        {
            $this->db->trans_commit();
        }
    }
    public function save_harga($id=0)
    {
        $id=$this->session->userdata('id');
        $post['created_by']=$id;
        $post['kodeprod']  =$this->input->post('kodeprod');
        $post['d_dp']      =$this->input->post('d_dp');
        $post['d_pbf']     =$this->input->post('d_pbf');
        $post['d_bsp']     =$this->input->post('d_bsp');
        $post['d_beli_dp'] =$this->input->post('d_beli_dp');
        $post['d_beli_bsp']=$this->input->post('d_beli_bsp');
        $post['d_beli_pbf']=$this->input->post('d_beli_pbf');
        $post['h_dp']      =$this->input->post('h_dp');
        $post['h_pbf']     =$this->input->post('h_pbf');
        $post['h_bsp']     =$this->input->post('h_bsp');
        $post['h_beli_bsp']=$this->input->post('h_beli_bsp');
        $post['h_beli_dp'] =$this->input->post('h_beli_dp');
        $post['h_beli_pbf']=$this->input->post('h_beli_pbf');
        $post['tgl']    =$this->input->post('tgl');
        //$post['supp']    =$this->input->post('supp');
        $post['created']   =date('Y-m-d H:i:s');
        $this->db->insert('mpm.prod_detail',$post);
    }
    public function save_product($id=0)
    {
        $id=$this->session->userdata('id');

        $post['created_by']=$id;
        $post['kodeprod']  =$this->input->post('kodeprod');
        $post['namaprod']  =$this->input->post('namaprod');
        $post['grupprod']  =$this->input->post('grupprod');
        $post['kode_prc']  =$this->input->post('kode_prc');
        $post['isisatuan'] =$this->input->post('isisatuan');
        $post['satuan']    =strtoupper($this->input->post('satuan'));
        $post['odrunit']   =strtoupper($this->input->post('odrunit'));
        $post['h_dp']      =$this->input->post('h_dp');
        $post['h_pbf']    =$this->input->post('h_pbf');
        $post['h_bsp']    =$this->input->post('h_bsp');
        $post['supp']      =$this->input->post('supp');
        $post['created']   =date('Y-m-d H:i:s');
        $this->db->insert('mpm.tabprod',$post);

        redirect('product/show_product/'.$this->session->userdata('offset'));
    }
    public function print_product()
    {
        $sql='select kodeprod,namaprod,supp,grupprod,active, isisatuan,kode_prc from mpm.tabprod order by kodeprod';
        $query = $this->db->query($sql);
        $this->load->library('cezpdf');
        $this->cezpdf->Cezpdf('A4','portrait');

        foreach($query->result() as $row)
        {
            if($row->active==1)
            {
                $act='YES';
            }
            else
            $act='NO';
            $db_data[] = array(
                'kodeprod' => $row->kodeprod,
                'namaprod' => $row->namaprod,
                'supp'     => $row->supp,
                'grupprod'=> $row->grupprod,
                'isisatuan'=> $row->isisatuan,
                'kode_prc' => $row->kode_prc,
                'active'   => $act,
                );
        }
        $col_names = array(
        'kodeprod' => 'Code',
        'kode_prc' => 'PRC Code',
        'namaprod' => 'Product Name',
        'supp' => 'Supplier',
        'grupprod' => 'Group',
        'isisatuan' => 'Content',
        'active' =>'Active',
        );
        $this->cezpdf->ezTable($db_data, $col_names, 'Table Of Product', array('width'=>10,'fontSize' => 7));
        $this->cezpdf->ezStream();
    }
    public function update_product($id='')
    {
        $str_id=$this->session->userdata('id');

        //$post['kodeprod']   =$this->input->post('kodeprod');
        $post['namaprod']   =$this->input->post("namaprod");
        $post['grupprod']   =$this->input->post('grupprod');
        $post['kode_prc']   =$this->input->post('kode_prc');
        $post['isisatuan']  =$this->input->post('isisatuan');
        $post['satuan']   =strtoupper($this->input->post('satuan'));
        $post['odrunit']   =strtoupper($this->input->post('odrunit'));
        $post['h_dp']     =$this->input->post('h_dp');
        $post['h_pbf']    =$this->input->post('h_pbf');
        $post['h_bsp']    =$this->input->post('h_bsp');
        $post['supp']       =$this->input->post('supp');
        $post['modified_by']=$str_id;
        $post['modified']   =date('Y-m-d H:i:s');
        $where='kodeprod='."'".$id."'";
        $this->db->trans_begin();
        $sql=$this->db->update_string('mpm.tabprod',$post,$where);
        $this->db->query($sql);
        $this->db->trans_complete();
        if($this->db->trans_status()===FALSE)
        {
            $this->db->trans_rollback();
        }
        else
        {
            $this->db->trans_commit();
        }
        redirect('product/show_product/'.$this->session->userdata('offset'));
    }
    public function update_detail($id='',$tgl='')
    {
        $str_id=$this->session->userdata('id');

        //$post['kodeprod']   =$this->input->post('kodeprod');
        //$post['namaprod']   =$this->input->post('namaprod');
        $post['d_dp']          =$this->input->post('d_dp');
        $post['d_pbf']         =$this->input->post('d_pbf');
        $post['d_bsp']         =$this->input->post('d_bsp');
        $post['d_beli_dp']     =$this->input->post('d_beli_dp');
        $post['d_beli_bsp']    =$this->input->post('d_beli_bsp');
        $post['d_beli_pbf']    =$this->input->post('d_beli_pbf');
        $post['h_dp']          =$this->input->post('h_dp');
        $post['h_pbf']         =$this->input->post('h_pbf');
        $post['h_bsp']         =$this->input->post('h_bsp');
        $post['h_beli_dp']     =$this->input->post('h_beli_dp');
        $post['h_beli_bsp']    =$this->input->post('h_beli_bsp');
        $post['h_beli_pbf']    =$this->input->post('h_beli_pbf');

        $post['h_beli_mpm']    =$this->input->post('h_beli_mpm');
        $post['h_beli_mpm_bsp']    =$this->input->post('h_beli_mpm_bsp');

        
        $post['h_beli_mpm_candy_jawa']    =$this->input->post('h_beli_mpm_candy_jawa');
        $post['h_beli_mpm_candy_Ljawa']    =$this->input->post('h_beli_mpm_candy_Ljawa');

        /* khusus batam */
        $post['h_dpbatam']    =$this->input->post('h_dpbatam');
        $post['d_dpbatam']    =$this->input->post('d_dpbatam');
        /* end khusus batam */

        /* khusus DP luar pulau jawa */
        $post['h_luarjawa']    =$this->input->post('h_luarjawa');
        $post['d_luarjawa']    =$this->input->post('d_luarjawa');
        /* khusus DP luar pulau jawa */

        $post['tgl']       =$tgl;
        $post['modified_by']=$str_id;
        $post['modified']   =date('Y-m-d H:i:s');
        $where='id='.$id;

        
        $this->db->trans_begin();
        $sql=$this->db->update_string('mpm.prod_detail',$post,$where);
        
        
        $this->db->query($sql);
        $this->db->trans_complete();
        if($this->db->trans_status()===FALSE)
        {
            $this->db->trans_rollback();
        }
        else
        {
            $this->db->trans_commit();
        }
        
        //redirect('product/show_product/'.$this->session->userdata('offset'));
    }
    public function detail_product($kode)
    {
       $image_properties_del=array(
                 'src'    => 'assets/css/images/delete.png',
                 'width'  => '20',
                 'height' => '20',
            );
            $image_properties_edit=array(
                 'src'    => 'assets/css/images/edit.png',
                 'width'  => '20',
                 'height' => '20',
            );
       $sql='select a.*,b.namaprod from mpm.prod_detail a inner join tabprod b using(kodeprod) where kodeprod=?';
       $query = $this->db->query($sql,array($kode));
       if($query->num_rows() > 0)
       {
            $this->load->library('table');
            $this->table->set_empty('0');

            $this->table->set_empty('0');
            $this->table->set_template($this->tmpl);
            $this->table->set_caption('LIST PRODUCT');
            $this->table->set_heading('KODE','PRODUCT NAME','DATE','BELI_DP','DISC B DP','BELI BSP','DISC B BSP','BELI PBF','DISC B PBF','H.DP','DISC DP','H.BSP','DISC BSP','H.PBF','DISC PBF','EDIT','DELETE');
            foreach ($query->result() as $value)
            {
              
                $js=array('onclick'=>"return confirm('Are you sure you want to delete?')");
                $this->table->add_row(
                        $value->kodeprod
                        ,$value->namaprod
                        ,$value->tgl
                        ,'<div div style="text-align:right">'.$value->h_beli_dp.'</div>'
                        ,'<div div style="text-align:right">'.$value->d_beli_dp.'</div>'
                        ,'<div div style="text-align:right">'.$value->h_beli_bsp.'</div>'
                        ,'<div div style="text-align:right">'.$value->d_beli_bsp.'</div>'
                        ,'<div div style="text-align:right">'.$value->h_beli_pbf.'</div>'
                        ,'<div div style="text-align:right">'.$value->d_beli_pbf.'</div>'
                        ,'<div div style="text-align:right">'.$value->h_dp.'</div>'
                        ,'<div div style="text-align:right">'.$value->d_dp.'</div>'
                        ,'<div div style="text-align:right">'.$value->h_bsp.'</div>'
                        ,'<div div style="text-align:right">'.$value->d_bsp.'</div>'
                        ,'<div div style="text-align:right">'.$value->h_pbf.'</div>'
                        ,'<div div style="text-align:right">'.$value->d_pbf.'</div>'
                        //,'<div div style="text-align:center">'.$active.'</div>'
                        ,'<div div style="text-align:center">'.anchor('product/edit_detail/'.$value->id.'/'.$value->kodeprod.'/',img($image_properties_edit)).'</div>'
                        //,'<div div style="text-align:center">'.anchor('product/plus_product/'.$value->kodeprod,img($image_properties_plus)).'</div>'
                        //,'<div div style="text-align:center">'.anchor('product/detail_product/'.$value->kodeprod,img($image_properties_detail)).'</div>'
                        ,'<div div style="text-align:center">'.anchor('product/delete_detail/'.$value->id,img($image_properties_del),$js).'</div>'

                );
            }
            $this->output_table .= '<br /><br />';
            //$this->output_table .=anchor('product/add_product/',img($image_add,'ADD'));
            $this->output_table .= '<br />';
            $this->output_table .= $this->table->generate();
            $this->output_table .= '<br />';
            $this->table->clear();
            $query->free_result();
            return $this->output_table;
        }
        else
        {
            return FALSE;
        }

    }
    public function show_product($limit=0,$offset=0)
    {   
        //test cm utk us aja
        $sql1="
            select  kodeprod, kode_prc, 
                    namaprod, supp,
                    grupprod, isisatuan,
                    produksi, satuan,
                    odrunit,  h_dp,
                    h_bsp, h_pbf,
                    active, report 
            from    mpm.tabprod
            order by kodeprod,supp
            ";
        $query_print = $this->db->query($sql1);
        /*
        echo "<pre>";
        print_r($sql1);
        echo "</pre>";
        */
        $this->total_query = $query_print->num_rows();
        $sql2= $sql1.' limit ? offset ?';
        $query = $this->db->query($sql2,array($limit,$offset));

        if($query->num_rows() > 0)
        {
            $this->load->library('table');
            $this->table->set_empty('0');

            $this->table->set_empty('0');
            $this->table->set_template($this->tmpl);
            $this->table->set_caption('LIST PRODUCT');
            $this->table->set_heading('CODE','PRC CODE', 'PRODUCT NAME','SUPPLIER','GROUP','CONTENT','UNIT','ORDER UNIT','ACTIVE','PRODUKSI','REPORT','EDIT','HARGA','DETAIL');

            $image_properties_yes=array(
                 'src'    => 'assets/css/images/yes.png',
                 'width'  => '20',
                 'height' => '20',
            );
            $image_properties_no=array(
                 'src'    => 'assets/css/images/no.png',
                 'width'  => '20',
                 'height' => '20',
            );
            $image_properties_del=array(
                 'src'    => 'assets/css/images/delete.png',
                 'width'  => '20',
                 'height' => '20',
            );
            $image_properties_edit=array(
                 'src'    => 'assets/css/images/edit.png',
                 'width'  => '20',
                 'height' => '20',
            );
            $image_properties_plus=array(
                 'src'    => 'assets/css/images/plus3.png',
                 'width'  => '20',
                 'height' => '20',
            );
            $image_add = array(
                 'src'    => 'assets/css/images/ADD.png',
                 'height' => '30',
            );
            $image_properties_detail=array(
            'src'    => 'assets/css/images/detail.gif',
            );
            foreach ($query->result() as $value)
            {
                switch ($value->active)
                {
                    case 1:$active=anchor('product/activer_product/0/'.$value->kodeprod.'/'.$offset,img($image_properties_yes));BREAK;
                    case 0:$active=anchor('product/activer_product/1/'.$value->kodeprod.'/'.$offset,img($image_properties_no));BREAK;
                }
                switch ($value->produksi)
                {
                    case 1:$produksi=anchor('product/activer_produksi/0/'.$value->kodeprod.'/'.$offset,img($image_properties_yes));BREAK;
                    case 0:$produksi=anchor('product/activer_produksi/1/'.$value->kodeprod.'/'.$offset,img($image_properties_no));BREAK;
                }
                switch ($value->report)
                {
                    case 1:$report=anchor('product/activer_report/0/'.$value->kodeprod.'/'.$offset,img($image_properties_yes));BREAK;
                    case 0:$report=anchor('product/activer_report/1/'.$value->kodeprod.'/'.$offset,img($image_properties_no));BREAK;
                }
                $js=array('onclick'=>"return confirm('Are you sure you want to delete?')");
                $this->table->add_row(
                        $value->kodeprod
                        ,$value->kode_prc
                        ,$value->namaprod
                        ,$value->supp
                        ,$value->grupprod
                        ,'<div div style="text-align:right">'.$value->isisatuan.'</div>'
                        ,'<div div style="text-align:left">'.$value->satuan.'</div>'
                         ,'<div div style="text-align:left">'.$value->odrunit.'</div>'
                        //,'<div div style="text-align:right">'.$value->h_dp.'</div>'
                        //,'<div div style="text-align:right">'.$value->h_bsp.'</div>'
                        //,'<div div style="text-align:right">'.$value->h_pbf.'</div>'
                        ,'<div div style="text-align:center">'.$active.'</div>'
                        ,'<div div style="text-align:center">'.$produksi.'</div>'
                        ,'<div div style="text-align:center">'.$report.'</div>'
                        ,'<div div style="text-align:center">'.anchor('product/edit_product/'.$value->kodeprod,img($image_properties_edit)).'</div>'
                        ,'<div div style="text-align:center">'.anchor('product/plus_product/'.$value->kodeprod,img($image_properties_plus)).'</div>'
                        ,'<div div style="text-align:center">'.anchor('product/detail_product/'.$value->kodeprod,img($image_properties_detail)).'</div>'
                        //,'<div div style="text-align:center">'.anchor('product/delete_product/'.$value->kodeprod,img($image_properties_del),$js).'</div>'

                );
            }
            $this->output_table .= '<br /><br />';
            $this->output_table .=anchor('product/add_product/',img($image_add,'ADD'));
            $this->output_table .= '<br />';
            $this->output_table .= $this->table->generate();
            $this->output_table .= '<br />';
            $this->table->clear();
            $query->free_result();
            return $this->output_table;
        }
        else
        {
            return FALSE;
        }
    }
}
?>
