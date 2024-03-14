<?php if( ! defined('BASEPATH')) exit('Akses langsung tidak diperkenankan');
error_reporting(E_ALL);
ini_set('display_errors', '1');
class Bsp_model extends CI_Model
{
    var $total_query='';
    var $output_table = '';
    var $output_print='';

    var $print=array(
                'table_open'=>'<table border="1" cellpadding="4" cellspacing="0">'
                ,'heading_row_start' =>'<tr>'
                ,'heading_row_end'   =>'</tr>'
                ,'heading_cell_start'=>'<th bgcolor = "#ffffff">'
                ,'heading_cell_end'=>'</th>'

                ,'row_start' =>'<tr>'
                ,'row_end'   =>'</tr>'
                ,'cell_start'=>'<td bgcolor = "#ffffff" border="#000000">'
                ,'cell_end'=>'</td>'

                ,'row_alt_start' =>'<tr>'
                ,'row_alt_end'   =>'</tr>'
                ,'cell_alt_start'=>'<td bgcolor = "#ffffff"  border="#000000">'
                ,'cell_alt_end'=>'</td>'
                ,'table_close'=>'</table>'
                );
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

    public function getTotalQuery()
    {
        return $this->total_query;
    }
    private function getName($dp)
    {
        $sql="select nama_comp from bsp.bspsales".date('Y')." where kode_comp=".$dp." limit 1";
        $query = $this->db->query($sql);
        return $query->row();
    }
    public function Bsp_model()
    {
        set_time_limit(0);
        ini_set('mysql.connect_timeout', 3000);
        ini_set('default_socket_timeout', 3000);

        $this->load->database();
        $this->load->library(array('table','session'));//session untuk sisi administrasi
        $this->load->helper(array('text','to_excel','array'));
        //$this->config->load('sorot');
    }

    public function vessel($num)
    {
        $id=$this->session->userdata('id');
        switch($num)
        {
            case 1:
                $sql='delete from bsp.sobsp where id='.$id;
                $this->db->query($sql);
                ;break;
           
            case 2:
                $sql='delete from bsp.outlet where id='.$id;
                $this->db->query($sql);
                ;break;
            
            case 3:
                $sql='delete from bsp.stokbsp where id='.$id;
                $this->db->query($sql);
                ;break;
            case 4:
                $sql='delete from bsp.stokprodbsp where id='.$id;
                $this->db->query($sql);
                ;break;
            case 5:
                $sql='delete from bsp.soprod where id='.$id;
                $this->db->query($sql);
                ;break;
            case 6:
                $sql='delete from bsp.omzet where id='.$id;
                $this->db->query($sql);
                ;break;
        }
    }
    public function listbsp()
    {
        $sql='select kode_comp from bsp.bspsales'.date('Y').' limit 1';
        $query = $this->db->query($sql);
        if($query->num_rows() > 0)
        {
                $sql='select distinct kode_comp,nama_comp from bsp.bspsales'.date('Y').' order by nama_comp';
        }
        else
        {
                $sql='select distinct kode_comp,nama_comp from bsp.bspsales'.(date('Y')-1).' order by nama_comp';
        }
        return $query = $this->db->query($sql);
    }
    public function listProduct()
    {
        $id=$this->session->userdata('id');
        //echo "id : ".$id."<br>";

        if($id == '97'){
            
            $sql='select kode_comp from bsp.bspsales'.date('Y').' limit 1';
            $query = $this->db->query($sql);

            if($query->num_rows() > 0)
            {
                //echo "ini muncul jika numrows>0<br>";
                ///$sql='select distinct deskripsi from bsp.bspsales'.date('Y').' order by deskripsi';
                $sql="
                select distinct namaprod as deskripsi from bsp.tabprod 
                where supp = '003'
                order by namaprod
                ";
            }
            else
            {
                echo "ini muncul jika numrows=0";
                $sql='select distinct deskripsi from bsp.bspsales'.(date('Y')-1).' order by deskripsi';
            }

        }else{

            $sql='select kode_comp from bsp.bspsales'.date('Y').' limit 1';
            $query = $this->db->query($sql);

            if($query->num_rows() > 0)
            {
                //echo "ini muncul jika numrows>0<br>";

                //$sql='select distinct deskripsi from bsp.bspsales'.date('Y').' order by deskripsi';
                
                
                $sql='
                select *
                FROM
                (
                    select distinct deskripsi from bsp.bspsales'.date('Y').'
                    union all

                    select namaprod from mpm.tabprod where KODEPROD = 700007
                )a ORDER BY deskripsi';
                
                //$sql='select distinct namaprod from bsp.tabprod order by namaprod';
            }
            else
            {
                //echo "ini muncul jika numrows=0";
                $sql='select distinct deskripsi from bsp.bspsales'.(date('Y')-1).' order by deskripsi';
            }

        }
        return $query = $this->db->query($sql);
    }
    public function listPermen()
    {
        $sql='select kode_comp from bsp.bspsales'.date('Y').' limit 1';
        $query = $this->db->query($sql);
        if($query->num_rows() > 0)
        {
               $sql='select distinct deskripsi from bsp.bspsales'.date('Y').' where deskripsi like "%permen%" or deskripsi like "%lozenge%" order by deskripsi';
        }
        else
        {
               $sql='select distinct deskripsi from bsp.bspsales'.(date('Y')-1).' where deskripsi like "%permen%" or deskripsi like "%lozenge%" order by deskripsi';
        }

        return $query = $this->db->query($sql);
    }
    public function sales_omzet_pdf($year=0)
    {
        $sql="select cabang, 
                    format(sum(if(bulan=1,omzet,0)),2) b1,
                    format(sum(if(bulan=2,omzet,0)),2) b2,
                    format(sum(if(bulan=3,omzet,0)),2) b3,
                    format(sum(if(bulan=4,omzet,0)),2) b4,
                    format(sum(if(bulan=5,omzet,0)),2) b5,
                    format(sum(if(bulan=6,omzet,0)),2) b6,
                    format(sum(if(bulan=7,omzet,0)),2) b7,
                    format(sum(if(bulan=8,omzet,0)),2) b8,
                    format(sum(if(bulan=9,omzet,0)),2) b9,
                    format(sum(if(bulan=10,omzet,0)),2) b10,
                    format(sum(if(bulan=11,omzet,0)),2) b11,
                    format(sum(if(bulan=12,omzet,0)),2) b12
                    from(
                        select nama_comp cabang,month(tgldokjdi) bulan,sum(jumlah)omzet from bsp.bspsales".$year." group by kode_comp,month(tgldokjdi)
                    )a group by cabang
                    union all
                    select 'TOTAL',
                    format(sum(if(month(tgldokjdi)=1,jumlah,0)),2) b1,
                    format(sum(if(month(tgldokjdi)=2,jumlah,0)),2) b2,
                    format(sum(if(month(tgldokjdi)=3,jumlah,0)),2) b3,
                    format(sum(if(month(tgldokjdi)=4,jumlah,0)),2) b4,
                    format(sum(if(month(tgldokjdi)=5,jumlah,0)),2) b5,
                    format(sum(if(month(tgldokjdi)=6,jumlah,0)),2) b6,
                    format(sum(if(month(tgldokjdi)=7,jumlah,0)),2) b7,
                    format(sum(if(month(tgldokjdi)=8,jumlah,0)),2) b8,
                    format(sum(if(month(tgldokjdi)=9,jumlah,0)),2) b9,
                    format(sum(if(month(tgldokjdi)=10,jumlah,0)),2) b10,
                    format(sum(if(month(tgldokjdi)=11,jumlah,0)),2) b11,
                    format(sum(if(month(tgldokjdi)=12,jumlah,0)),2) b12
                    from bsp.bspsales".$year;
        $query = $this->db->query($sql);
            $this->load->library('cezpdf');
            $this->cezpdf->Cezpdf('A4','landscape');

            foreach($query->result() as $row)
            {
                $db_data[] = array(
                'cabang'=> $row->cabang,
                'b1'    => $row->b1,
                'b2'    => $row->b2,
                'b3'    => $row->b3,
                'b4'    => $row->b4,
                'b5'    => $row->b5,
                'b6'    => $row->b6,
                'b7'    => $row->b7,
                'b8'    => $row->b8,
                'b9'    => $row->b9,
                'b10'   => $row->b10,
                'b11'   => $row->b11,
                'b12'   => $row->b12
                );
            }
            $col_names = array(
            'cabang'=>'Cabang',
            'b1' => 'January',
            'b2' => 'February',
            'b3' => 'March',
            'b4' => 'April',
            'b5' => 'May',
            'b6' => 'June',
            'b7' => 'july',
            'b8' => 'August',
            'b9' => 'September',
            'b10'=> 'October',
            'b11'=> 'November',
            'b12'=> 'December'
            );
           
            $this->cezpdf->ezTable($db_data, $col_names, 'Omzet BSP'.$year, array('width'=>10,'fontSize' => 6.5));
            $this->cezpdf->ezStream();
        
    }
    public function sales_omzet_excel($year=0)
    {
        $sql="select cabang, 
                    format(sum(if(bulan=1,omzet,0)),2) b1,
                    format(sum(if(bulan=2,omzet,0)),2) b2,
                    format(sum(if(bulan=3,omzet,0)),2) b3,
                    format(sum(if(bulan=4,omzet,0)),2) b4,
                    format(sum(if(bulan=5,omzet,0)),2) b5,
                    format(sum(if(bulan=6,omzet,0)),2) b6,
                    format(sum(if(bulan=7,omzet,0)),2) b7,
                    format(sum(if(bulan=8,omzet,0)),2) b8,
                    format(sum(if(bulan=9,omzet,0)),2) b9,
                    format(sum(if(bulan=10,omzet,0)),2) b10,
                    format(sum(if(bulan=11,omzet,0)),2) b11,
                    format(sum(if(bulan=12,omzet,0)),2) b12
                    from(
                        select nama_comp cabang,month(tgldokjdi) bulan,sum(jumlah)omzet from bsp.bspsales".$year." group by kode_comp,month(tgldokjdi)
                    )a group by cabang";
        $query = $this->db->query($sql);
        to_excel($query);
        
    }
    public function sales_omzet($limit=null,$offset=null,$year=0)
    {
        $id=$this->session->userdata('id');
        $sql="select 1 from bsp.omzet where id=".$id;
        $query=$this->db->query($sql);
        if($query->num_rows()>0)
        {
            $sqlout="select * from (select namacomp,b1,b2,b3,b4,b5,b6,b7,b8,b9,b10,b11,b12 from bsp.omzet where id=".$id."
                  union all
                  select '~~~TOTAL~~~', sum(b1),sum(b2),sum(b3),sum(b4),sum(b5),sum(b6)
                  ,sum(b7),sum(b8),sum(b9),sum(b10),sum(b11),sum(b12) from bsp.omzet where id=".$id.") a order by namacomp";
            $query = $this->db->query($sqlout);
            $sql2  = $sqlout.' limit ? offset ?';
            $this->total_query = $query->num_rows();
            $query = $this->db->query($sql2,array($limit,$offset));
        }
        else
        {
            $sql="insert into bsp.omzet(namacomp,b1,b2,b3,b4,b5,b6,b7,b8,b9,b10,b11,b12,id) 
                    select cabang, 
                    sum(if(bulan=1,omzet,0)) b1,
                    sum(if(bulan=2,omzet,0)) b2,
                    sum(if(bulan=3,omzet,0)) b3,
                    sum(if(bulan=4,omzet,0)) b4,
                    sum(if(bulan=5,omzet,0)) b5,
                    sum(if(bulan=6,omzet,0)) b6,
                    sum(if(bulan=7,omzet,0)) b7,
                    sum(if(bulan=8,omzet,0)) b8,
                    sum(if(bulan=9,omzet,0)) b9,
                    sum(if(bulan=10,omzet,0)) b10,
                    sum(if(bulan=11,omzet,0)) b11,
                    sum(if(bulan=12,omzet,0)) b12,".$id."
                    from(
                    select nama_comp cabang,month(tgldokjdi) bulan,sum(jumlah)omzet from bsp.bspsales".$year." group by kode_comp,month(tgldokjdi)
                    )a group by cabang";
            $query = $this->db->query($sql);
            $sql="select * from (
                  select namacomp,b1,b2,b3,b4,b5,b6,b7,b8,b9,b10,b11,b12 from bsp.omzet where id=".$id."
                  union all
                  select '~~~TOTAL~~~', 
                   sum(b1),sum(b2),sum(b3),sum(b4) ,sum(b5) ,sum(b6)
                  ,sum(b7),sum(b8),sum(b9),sum(b10),sum(b11),sum(b12) 
                  from bsp.omzet where id=".$id.") a order by namacomp";
            $query = $this->db->query($sql);
            $sql2  = $sql.' limit ? offset ?';
            $this->total_query = $query->num_rows();
            $query = $this->db->query($sql2,array($limit,$offset));
        }
        if($query->num_rows() > 0)
        {
            $this->load->library('table');
            $this->table->set_empty('0');

            $this->table->set_template($this->tmpl);
            $this->table->set_caption('Omzet BSP');

            $this->table->set_heading('Cabang','Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember');
            foreach ($query->result() as $value)
            {
                $this->table->add_row($value->namacomp
                        ,@number_format($value->b1,0)
                        ,@number_format($value->b2,0)
                        ,@number_format($value->b3,0)
                        ,@number_format($value->b4,0)
                        ,@number_format($value->b5,0)
                        ,@number_format($value->b6,0)
                        ,@number_format($value->b7,0)
                        ,@number_format($value->b8,0)
                        ,@number_format($value->b9,0)
                        ,@number_format($value->b10,0)
                        ,@number_format($value->b11,0)
                        ,@number_format($value->b12,0)
                        );
            }
            $this->output_table .= $this->table->generate();
            $this->output_table .= '<br />';
            $this->table->clear();
            return $this->output_table;
        }
        else
        {
            return FALSE;
        }
    }
    public function sales_bsp($limit=null,$offset=null,$year=0)
    {
        $year2=(int)$year-1;
        $id=$this->session->userdata('id');
        $uv=$this->input->post('uv');
        switch($uv)
        {
            case 0:
              $unit='banyak/isisatuan';
              break;
            case 1:
              $unit='jumlah';
              break;
        }
        $sql="select 1 from bsp.sobsp where id=".$id;
        $query = $this->db->query($sql);

        if($query->num_rows()>0)
        {
            $sqlout="select * from (select * from bsp.sobsp where id=".$id."
                  union all
                  select '~~~TOTAL~~~', sum(rata),sum(b1),sum(b2),sum(b3),sum(b4),sum(b5),sum(b6)
                  ,sum(b7),sum(b8),sum(b9),sum(b10),sum(b11),sum(b12),0 from bsp.sobsp where id=".$id.") a order by deskripsi";
            $query = $this->db->query($sqlout);
            $sql2  = $sqlout.' limit ? offset ?';
            $this->total_query = $query->num_rows();
            $query = $this->db->query($sql2,array($limit,$offset));
        }
        else{
            
            //echo "ini muncul jika else query->num_rows()>0<br>";

            $cekid = $id;

            //echo "cekid = ".$cekid."<br>";

            if ($cekid == '97') {
                
                $sql1="
                insert into bsp.sobsp 
                select  deskripsi,
                        (b1+b2+b3+b4+b5+b6+b7+b8+b9+b10+b11+b12) as rata,
                        b1,b2,b3,b4,b5,b6,b7,b8,b9,b10,b11,b12 ,".$id."
                from
                (
                        select  deskripsi,
                                sum(if(bulan=1,unit,0)) as b1,sum(if(bulan=2,unit,0))as b2,
                                sum(if(bulan=3,unit,0))  as b3,sum(if(bulan=4,unit,0)) as b4,
                                sum(if(bulan=5,unit,0)) as b5,sum(if(bulan=6,unit,0)) as b6,
                                sum(if(bulan=7,unit,0)) as b7,sum(if(bulan=8,unit,0)) as b8,
                                sum(if(bulan=9,unit,0)) as b9,sum(if(bulan=10,unit,0)) as b10,
                                sum(if(bulan=11,unit,0)) as b11,sum(if(bulan=12,unit,0)) as b12
                        from 
                        (
                            select  deskripsi,
                                    month(tgldokjdi) as bulan, 
                                    sum(".$unit.") as unit 
                            from    bsp.bspsales".$year." a 
                                        inner join bsp.tabprod b
                                            on a.deskripsi=b.namaprod
                            where b.supp = '003'
                            group by deskripsi, bulan
                        )a group by deskripsi
                )a                
                  ";

            }else{
                
                $sql1="
                insert into bsp.sobsp 
                select  deskripsi,
                        (b1+b2+b3+b4+b5+b6+b7+b8+b9+b10+b11+b12) as rata,
                        b1,b2,b3,b4,b5,b6,b7,b8,b9,b10,b11,b12 ,".$id."
                from
                (
                        select  deskripsi,
                                sum(if(bulan=1,unit,0)) as b1,sum(if(bulan=2,unit,0))as b2,
                                sum(if(bulan=3,unit,0))  as b3,sum(if(bulan=4,unit,0)) as b4,
                                sum(if(bulan=5,unit,0)) as b5,sum(if(bulan=6,unit,0)) as b6,
                                sum(if(bulan=7,unit,0)) as b7,sum(if(bulan=8,unit,0)) as b8,
                                sum(if(bulan=9,unit,0)) as b9,sum(if(bulan=10,unit,0)) as b10,
                                sum(if(bulan=11,unit,0)) as b11,sum(if(bulan=12,unit,0)) as b12
                        from 
                        (
                            select  deskripsi,
                                    month(tgldokjdi) as bulan, 
                                    sum(".$unit.") as unit 
                            from    bsp.bspsales".$year." a 
                                        inner join bsp.tabprod b
                                            on a.deskripsi=b.namaprod
                            group by deskripsi, bulan
                        )a group by deskripsi
                )a                
                  ";

            }            

            $query = $this->db->query($sql1);
            $sqlout="select * from (select * from bsp.sobsp where id=".$id."
                  union all
                  select '~~~TOTAL~~~', sum(rata),sum(b1),sum(b2),sum(b3),sum(b4),sum(b5),sum(b6)
                  ,sum(b7),sum(b8),sum(b9),sum(b10),sum(b11),sum(b12),0 from bsp.sobsp where id=".$id.")a order by deskripsi";
            $query = $this->db->query($sqlout);
            $sql2  = $sqlout.' limit ? offset ?';
            $this->total_query = $query->num_rows();
            $query = $this->db->query($sql2,array($limit,$offset));
         }

        
       
     
        if($query->num_rows() > 0)
        {
            $this->load->library('table');
            $this->table->set_empty('0');

            $this->table->set_template($this->tmpl);
            $this->table->set_caption('Sell Out BSP');

            $this->table->set_heading('Deskripsi','Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember','Total '.$year);
            foreach ($query->result() as $value)
            {
                $this->table->add_row($value->deskripsi
                        ,@number_format($value->b1,0)
                        ,@number_format($value->b2,0)
                        ,@number_format($value->b3,0)
                        ,@number_format($value->b4,0)
                        ,@number_format($value->b5,0)
                        ,@number_format($value->b6,0)
                        ,@number_format($value->b7,0)
                        ,@number_format($value->b8,0)
                        ,@number_format($value->b9,0)
                        ,@number_format($value->b10,0)
                        ,@number_format($value->b11,0)
                        ,@number_format($value->b12,0)
                        ,@number_format($value->rata,0)
                        );
            }
            $this->output_table .= $this->table->generate();
            $this->output_table .= '<br />';
            $this->table->clear();
            return $this->output_table;
        }
        else
        {
            return FALSE;
        }
    }
    public function stok_produk_bsp($limit=null,$offset=null,$year=null,$kode=null)
    {
        $id=$this->session->userdata('id');
        $sql="select 1 from bsp.stokprodbsp where id=".$id;
        $query = $this->db->query($sql);

        if($query->num_rows()>0)
        {
             $sqlout="select * from (select * from bsp.stokprodbsp where id=".$id." order by deskripsi) a
                  union all
                  select 'TOTAL', sum(b1),sum(b2),sum(b3),sum(b4),sum(b5),sum(b6)
                  ,sum(b7),sum(b8),sum(b9),sum(b10),sum(b11),sum(b12),0 from bsp.stokprodbsp where id=".$id;
            //$sqlout="select * from bsp.stokbsp where id=".$id." order by deskripsi";
            $query = $this->db->query($sqlout);
            $sql2  = $sqlout.' limit ? offset ?';
            $this->total_query = $query->num_rows();
            $query = $this->db->query($sql2,array($limit,$offset));
        }
        else{
            $sql1="insert into bsp.stokprodbsp
                    select nama_comp,
                    sum(if(bulan=1,stok,0)) as b1,sum(if(bulan=2,stok,0))as b2,
                    sum(if(bulan=3,stok,0))  as b3,sum(if(bulan=4,stok,0)) as b4,
                    sum(if(bulan=5,stok,0)) as b5,sum(if(bulan=6,stok,0)) as b6,
                    sum(if(bulan=7,stok,0)) as b7,sum(if(bulan=8,stok,0)) as b8,
                    sum(if(bulan=9,stok,0)) as b9,sum(if(bulan=10,stok,0)) as b10,
                    sum(if(bulan=11,stok,0)) as b11,sum(if(bulan=12,stok,0)) as b12,".$id."
                    from (
                    select nama_comp,sum(stok)/isisatuan as stok,bulan from bsp.stok".$year." a inner join bsp.tabprod b using(namaprod) where a.namaprod in (".$kode.") group by nama_comp,bulan
                    ) a group by nama_comp

                    ";

            $query = $this->db->query($sql1);
            $sqlout="select * from (select * from bsp.stokprodbsp where id=".$id." order by deskripsi) a
                  union all
                  select 'TOTAL', sum(b1),sum(b2),sum(b3),sum(b4),sum(b5),sum(b6)
                  ,sum(b7),sum(b8),sum(b9),sum(b10),sum(b11),sum(b12),0 from bsp.stokprodbsp where id=".$id;
            $query = $this->db->query($sqlout);
            $sql2  = $sqlout.' limit ? offset ?';
            $this->total_query = $query->num_rows();
            $query = $this->db->query($sql2,array($limit,$offset));
         }



        if($query->num_rows() > 0)
        {
            $this->load->library('table');
            $this->table->set_empty('0');

            $this->table->set_template($this->tmpl);
            $this->table->set_caption('Stok BSP');

            $this->table->set_heading('Cabang','Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember');
            foreach ($query->result() as $value)
            {
                $this->table->add_row($value->deskripsi
                        ,@number_format($value->b1,0)
                        ,@number_format($value->b2,0)
                        ,@number_format($value->b3,0)
                        ,@number_format($value->b4,0)
                        ,@number_format($value->b5,0)
                        ,@number_format($value->b6,0)
                        ,@number_format($value->b7,0)
                        ,@number_format($value->b8,0)
                        ,@number_format($value->b9,0)
                        ,@number_format($value->b10,0)
                        ,@number_format($value->b11,0)
                        ,@number_format($value->b12,0)
                        );
            }
            $this->output_table .= $this->table->generate();
            $this->output_table .= '<br />';
            $this->table->clear();
            return $this->output_table;
        }
        else
        {
            return FALSE;
        }
    }
    public function sales_produk_bsp($limit=null,$offset=null,$year=null,$kode=null)
    {
        $id=$this->session->userdata('id');
        $uv=$this->input->post('uv');
        switch($uv)
        {
            case 0:$unit='sum(banyak)/isisatuan';break;
            case 1:$unit='sum(jumlah)';break;
        }
        $sql="select 1 from bsp.soprod where id=".$id;
        $query = $this->db->query($sql);

        if($query->num_rows()>0)
        {
             $sqlout="select * from (select * from bsp.soprod where id=".$id." order by nama_comp) a
                  union all
                  select '~~~TOTAL~~~', sum(b1),sum(b2),sum(b3),sum(b4),sum(b5),sum(b6)
                  ,sum(b7),sum(b8),sum(b9),sum(b10),sum(b11),sum(b12),0 from bsp.soprod where id=".$id;
            //$sqlout="select * from bsp.stokbsp where id=".$id." order by deskripsi";
            $query = $this->db->query($sqlout);
            $sql2  = $sqlout.' limit ? offset ?';
            $this->total_query = $query->num_rows();
            $query = $this->db->query($sql2,array($limit,$offset));
        }
        else{
        
            $sql1="insert into bsp.soprod
                    select nama_comp
                    ,sum(if(bulan=1,unit,0)) b1
                    ,sum(if(bulan=2,unit,0)) b2
                    ,sum(if(bulan=3,unit,0)) b3
                    ,sum(if(bulan=4,unit,0)) b4
                    ,sum(if(bulan=5,unit,0)) b5
                    ,sum(if(bulan=6,unit,0)) b6
                    ,sum(if(bulan=7,unit,0)) b7
                    ,sum(if(bulan=8,unit,0)) b8
                    ,sum(if(bulan=9,unit,0)) b9
                    ,sum(if(bulan=10,unit,0)) b10
                    ,sum(if(bulan=11,unit,0)) b11
                    ,sum(if(bulan=12,unit,0)) b12,".$id."
                    from(
                    select nama_comp,month(tgldokjdi) as bulan ,".$unit." as unit from bsp.bspsales".$year." a inner join bsp.tabprod b on a.deskripsi=b.namaprod where deskripsi in (".$kode.") group by nama_comp,bulan 
                    ) a group by nama_comp";
            $query = $this->db->query($sql1);
            $sqlout="select * from (select * from bsp.soprod where id=".$id." order by nama_comp) a
                  union all
                  select '~~~TOTAL~~~', sum(b1),sum(b2),sum(b3),sum(b4),sum(b5),sum(b6)
                  ,sum(b7),sum(b8),sum(b9),sum(b10),sum(b11),sum(b12),0 from bsp.soprod where id=".$id;
            $query = $this->db->query($sqlout);
            $sql2  = $sqlout.' limit ? offset ?';
            $this->total_query = $query->num_rows();
            $query = $this->db->query($sql2,array($limit,$offset));
         }



        if($query->num_rows() > 0)
        {
            $this->load->library('table');
            $this->table->set_empty('0');

            $this->table->set_template($this->tmpl);
            $this->table->set_caption('Sales BSP');

            $this->table->set_heading('Cabang','Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember');
            foreach ($query->result() as $value)
            {
                $this->table->add_row($value->nama_comp
                        ,@number_format($value->b1,0)
                        ,@number_format($value->b2,0)
                        ,@number_format($value->b3,0)
                        ,@number_format($value->b4,0)
                        ,@number_format($value->b5,0)
                        ,@number_format($value->b6,0)
                        ,@number_format($value->b7,0)
                        ,@number_format($value->b8,0)
                        ,@number_format($value->b9,0)
                        ,@number_format($value->b10,0)
                        ,@number_format($value->b11,0)
                        ,@number_format($value->b12,0)
                        );
            }
            $this->output_table .= $this->table->generate();
            $this->output_table .= '<br />';
            $this->table->clear();
            return $this->output_table;
        }
        else
        {
            return FALSE;
        }
    }
    public function stok_bsp($limit=null,$offset=null,$year=0)
    {
        $id=$this->session->userdata('id');
        $sql="select 1 from bsp.stokbsp where id=".$id;
        $query = $this->db->query($sql);

        if($query->num_rows()>0)
        {
            /*$sqlout="select * from (select * from bsp.sobsp where id=".$id."
                  union all
                  select 'TOTAL', sum(rata),sum(b1),sum(b2),sum(b3),sum(b4),sum(b5),sum(b6)
                  ,sum(b7),sum(b8),sum(b9),sum(b10),sum(b11),sum(b12),0 from bsp.sobsp where id=".$id.") a order by deskripsi";*/
            $sqlout="select * from bsp.stokbsp where id=".$id." order by deskripsi";
            $query = $this->db->query($sqlout);
            $sql2  = $sqlout.' limit ? offset ?';
            $this->total_query = $query->num_rows();
            $query = $this->db->query($sql2,array($limit,$offset));
        }
        else{
            $sql1="insert into bsp.stokbsp 
                    select namaprod,
                    sum(if(bulan=1,stok,0)) as b1,sum(if(bulan=2,stok,0))as b2,
                    sum(if(bulan=3,stok,0))  as b3,sum(if(bulan=4,stok,0)) as b4,
                    sum(if(bulan=5,stok,0)) as b5,sum(if(bulan=6,stok,0)) as b6,
                    sum(if(bulan=7,stok,0)) as b7,sum(if(bulan=8,stok,0)) as b8,
                    sum(if(bulan=9,stok,0)) as b9,sum(if(bulan=10,stok,0)) as b10,
                    sum(if(bulan=11,stok,0)) as b11,sum(if(bulan=12,stok,0)) as b12,".$id."
                    from (
                    select namaprod,sum(stok)/isisatuan as stok,bulan from bsp.stok".$year." a inner join bsp.tabprod b  using(namaprod) group by namaprod,bulan
                    ) a group by namaprod
                   
                    ";

            $query = $this->db->query($sql1);
            $sqlout="select * from bsp.stokbsp where id=".$id." order by deskripsi";
            $query = $this->db->query($sqlout);
            $sql2  = $sqlout.' limit ? offset ?';
            $this->total_query = $query->num_rows();
            $query = $this->db->query($sql2,array($limit,$offset));
         }



        if($query->num_rows() > 0)
        {
            $this->load->library('table');
            $this->table->set_empty('0');

            $this->table->set_template($this->tmpl);
            $this->table->set_caption('Stok BSP');

            $this->table->set_heading('Deskripsi','Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember');
            foreach ($query->result() as $value)
            {
                $this->table->add_row($value->deskripsi
                        ,@number_format($value->b1,0)
                        ,@number_format($value->b2,0)
                        ,@number_format($value->b3,0)
                        ,@number_format($value->b4,0)
                        ,@number_format($value->b5,0)
                        ,@number_format($value->b6,0)
                        ,@number_format($value->b7,0)
                        ,@number_format($value->b8,0)
                        ,@number_format($value->b9,0)
                        ,@number_format($value->b10,0)
                        ,@number_format($value->b11,0)
                        ,@number_format($value->b12,0)
                        );
            }
            $this->output_table .= $this->table->generate();
            $this->output_table .= '<br />';
            $this->table->clear();
            return $this->output_table;
        }
        else
        {
            return FALSE;
        }
    }
    public function sales_outlet_bsp($offset=null,$year=null,$kodeprod=null,$dp=null)
    {
        //$naper=$this->getNocab($dp);
        $id=$this->session->userdata('id');
        //echo $dp;
      
        $uv=$this->input->post('uv');
        switch($uv)
        {
            case 0:
              $unit='banyak';
              break;
            case 1:
              $unit='jumlah';
              break;
        }
        $limit=20;
       
        $sql='select * from bsp.outlet where id='.$id.' order by tipe,outlet';
        $query = $this->db->query($sql);
        if($query->num_rows()>0)
        {
            $sql2  = $sql.' limit ? offset ?';
            $this->total_query = $query->num_rows();
            $query = $this->db->query($sql2,array($limit,$offset));
        }
        else
        {
              $sql="  insert into bsp.outlet select kode,tipe,outlet,address,b1,b2,b3,b4,b5,b6,b7,b8,b9,b10,b11,b12,(b1+b2+b3+b4+b5+b6+b7+b8+b9+b10+b11+b12)/12 as rata,id from (
                select kode, outlet,address,tipe,
                sum(if(blndok=1,unit/isisatuan,0)) as b1,sum(if(blndok=2,unit/isisatuan,0)) as b2, sum(if(blndok=3,unit/isisatuan,0)) as b3,
                sum(if(blndok=4,unit/isisatuan,0)) as b4, sum(if(blndok=5,unit/isisatuan,0)) as b5,sum(if(blndok=6,unit/isisatuan,0)) as b6,
                sum(if(blndok=7,unit/isisatuan,0)) as b7,sum(if(blndok=8,unit/isisatuan,0)) as b8, sum(if(blndok=9,unit/isisatuan,0)) as b9,
                sum(if(blndok=10,unit/isisatuan,0)) as b10, sum(if(blndok=11,unit/isisatuan,0)) as b11,sum(if(blndok=12,unit/isisatuan,0)) as b12,".$id." as id
                from (
                select kode_lang as kode,isisatuan, nama_type as tipe,nama_lang as outlet,alamat as address,sum(".$unit.")as unit,month(tgldokjdi) as blndok  from bsp.bspsales".$year."
                    a inner join bsp.tabprod b on a.deskripsi=b.namaprod
                where kode_comp = ".$dp." and deskripsi in (".$kodeprod.") group by kode_lang,blndok
                )a group by kode
                )a ";

                $this->db->trans_begin();
                $query = $this->db->query($sql);
                $this->db->trans_complete();
                if($this->db->trans_status()===FALSE)
                {
                    $this->db->trans_rollback();
                }
                else
                {
                    $this->db->trans_commit();
                }

            $sql='select * from bsp.outlet where id='.$id.' order by tipe,outlet';
            $query = $this->db->query($sql);
            $sql2  = $sql.' limit ? offset ?';
            $this->total_query = $query->num_rows();
            $query = $this->db->query($sql2,array($limit,$offset));
        }

        if($query->num_rows() > 0)
        {
            $this->load->library('table');
            $this->table->set_empty('0');

            $this->table->set_template($this->tmpl);
            //$name = $this->getName($dp);
            //$namaprod=$this->get_product_name($kodeprod);
            //$this->table->set_caption('SELL OUT OUTLET '.$name->nama_comp.'<br />Product : '.$kodeprod);

            $this->table->set_heading('CODE','OUTLET','TYPE','ADDRESS', 'JANUARY','FEBRUARY','MARCH','APRIL','MAY','JUNE','JULY','AUGUST','SEPTEMBER','OCTOBER','NOVEMBER','DECEMBER');
            foreach ($query->result() as $value)
            {
                $this->table->add_row(
                        $value->kode
                        ,$value->outlet
                        ,$value->tipe
                        ,$value->alamat
                        ,'<div div style="text-align:right">' . number_format($value->b1,0)   . '</div>'
                        ,'<div div style="text-align:right">' . number_format($value->b2,0)   . '</div>'
                        ,'<div div style="text-align:right">' . number_format($value->b3,0)   . '</div>'
                        ,'<div div style="text-align:right">' . number_format($value->b4,0)   . '</div>'
                        ,'<div div style="text-align:right">' . number_format($value->b5,0)   . '</div>'
                        ,'<div div style="text-align:right">' . number_format($value->b6,0)   . '</div>'
                        ,'<div div style="text-align:right">' . number_format($value->b7,0)   . '</div>'
                        ,'<div div style="text-align:right">' . number_format($value->b8,0)   . '</div>'
                        ,'<div div style="text-align:right">' . number_format($value->b9,0)   . '</div>'
                        ,'<div div style="text-align:right">' . number_format($value->b10,0)  . '</div>'
                        ,'<div div style="text-align:right">' . number_format($value->b11,0)  . '</div>'
                        ,'<div div style="text-align:right">' . number_format($value->b12,0)  . '</div>'
                );
            }
            $this->output_table .= $this->table->generate();
            $this->output_table .= '<br />';
            $this->table->clear();
            return $this->output_table;
        }
        else
        {
            return FALSE;
        }
             
 
    }
    public function sales_outlet_bsp_pdf($year=null,$kodeprod=null,$dp=null)
    {
        $uv=$this->input->post('uv');
        switch($uv)
        {
            case 0:
              $unit='banyak';
              break;
            case 1:
              $unit='jumlah';
              break;
        }
        $sql="select kode,tipe,outlet,address,
                
                format(b1,0)as b1,format(b2,0)as b2,
                format(b3,0)as b3,format(b4,0)as b4,
                format(b5,0)as b5,format(b6,0)as b6,
                format(b7,0)as b7,format(b8,0)as b8,
                format(b9,0)as b9,format(b10,0)as b10,
                format(b11,0)as b11,format(b12,0)as b12,
                format((b1+b2+b3+b4+b5+b6+b7+b8+b9+b10+b11+b12)/12,0) as rata from (

                    select kode, outlet,address,tipe,
                sum(if(blndok=1,unit,0)) as b1,sum(if(blndok=2,unit,0)) as b2, sum(if(blndok=3,unit,0)) as b3,
                sum(if(blndok=4,unit,0)) as b4, sum(if(blndok=5,unit,0)) as b5,sum(if(blndok=6,unit,0)) as b6,
                sum(if(blndok=7,unit,0)) as b7,sum(if(blndok=8,unit,0)) as b8, sum(if(blndok=9,unit,0)) as b9,
                sum(if(blndok=10,unit,0)) as b10, sum(if(blndok=11,unit,0)) as b11,sum(if(blndok=12,unit,0)) as b12
                from (
                select kode_lang as kode, nama_type as tipe,nama_lang as outlet,alamat as address,sum(".$unit.")as unit,month(tgldokjdi) as blndok  from bsp.bspsales".$year." where kode_comp = ".$dp." and deskripsi in (".$kodeprod.") group by kode_lang,blndok
                )a group by kode
                )a order by tipe, outlet";
            $query = $this->db->query($sql);
            $this->load->library('cezpdf');
            $this->cezpdf->Cezpdf('A3','landscape');

            foreach($query->result() as $row)
            {
                $db_data[] = array(
                'kode'  => $row->kode,
                'outlet' => $row->outlet,
                'tipe'  =>  $row->tipe,
                'address' => $row->address,
                'b1'    => $row->b1,
                'b2'    => $row->b2,
                'b3'    => $row->b3,
                'b4'    => $row->b4,
                'b5'    => $row->b5,
                'b6'    => $row->b6,
                'b7'    => $row->b7,
                'b8'    => $row->b8,
                'b9'    => $row->b9,
                'b10'   => $row->b10,
                'b11'   => $row->b11,
                'b12'   => $row->b12,
                'rata'  => $row->rata
                    );
            }
            $col_names = array(
            'kode' => 'Code',
            'outlet' => 'Outlet',
            'tipe' => 'Type',
            'address' => 'Address',
            'b1' => 'January',
            'b2' => 'February',
            'b3' => 'March',
            'b4' => 'April',
            'b5' => 'May',
            'b6' => 'June',
            'b7' => 'july',
            'b8' => 'August',
            'b9' => 'September',
            'b10'=> 'October',
            'b11'=> 'November',
            'b12'=> 'December',
            'rata'=> 'Average'
                );
            $name = $this->getName($dp);
            //$namaprod = $this->get_product_name($kodeprod);
            $this->cezpdf->ezTable($db_data, $col_names, 'Outlet "'.$name->nama_comp.'" '.$year.' For Product :'.$kodeprod, array('width'=>10,'fontSize' => 7.5));
            $this->cezpdf->ezStream();
    }
    public function sales_outlet_bsp_excel($year=null,$kodeprod=null,$dp=null)
    {
        $uv=$this->input->post('uv');
        switch($uv)
        {
            case 0:
              $unit='banyak';
              break;
            case 1:
              $unit='jumlah';
              break;
        }
        $sql="select kode,tipe,outlet,address,
                format(b1,0)as b1,format(b2,0)as b2,
                format(b3,0)as b3,format(b4,0)as b4,
                format(b5,0)as b5,format(b6,0)as b6,
                format(b7,0)as b7,format(b8,0)as b8,
                format(b9,0)as b9,format(b10,0)as b10,
                format(b11,0)as b11,format(b12,0)as b12,
                format((b1+b2+b3+b4+b5+b6+b7+b8+b9+b10+b11+b12)/12,0) as rata from (
                select kode, outlet,address,tipe,
                sum(if(blndok=1,unit,0)) as b1,sum(if(blndok=2,unit,0)) as b2, sum(if(blndok=3,unit,0)) as b3,
                sum(if(blndok=4,unit,0)) as b4, sum(if(blndok=5,unit,0)) as b5,sum(if(blndok=6,unit,0)) as b6,
                sum(if(blndok=7,unit,0)) as b7,sum(if(blndok=8,unit,0)) as b8, sum(if(blndok=9,unit,0)) as b9,
                sum(if(blndok=10,unit,0)) as b10, sum(if(blndok=11,unit,0)) as b11,sum(if(blndok=12,unit,0)) as b12
                from (
                select kode_lang as kode, nama_type as tipe,nama_lang as outlet,alamat as address,sum(".$unit.")as unit,month(tgldokjdi) as blndok  from bsp.bspsales".$year." where kode_comp = ".$dp." and deskripsi in (".$kodeprod.") group by kode_lang,blndok
                )a group by kode
                )a";
         $query = $this->db->query($sql);
         to_excel($query);
    }
    public function sales_produk_bsp_pdf($year=null,$kodeprod=null,$dp=null)
    {
        $uv=$this->input->post('uv');
        switch($uv)
        {
            case 0:
              $unit='sum(banyak)/isisatuan';
              break;
            case 1:
              $unit='sum(jumlah)';
              break;
        }
        $sql="select nama_comp
                    ,sum(if(bulan=1,unit,0)) b1
                    ,sum(if(bulan=2,unit,0)) b2
                    ,sum(if(bulan=3,unit,0)) b3
                    ,sum(if(bulan=4,unit,0)) b4
                    ,sum(if(bulan=5,unit,0)) b5
                    ,sum(if(bulan=6,unit,0)) b6
                    ,sum(if(bulan=7,unit,0)) b7
                    ,sum(if(bulan=8,unit,0)) b8
                    ,sum(if(bulan=9,unit,0)) b9
                    ,sum(if(bulan=10,unit,0)) b10
                    ,sum(if(bulan=11,unit,0)) b11
                    ,sum(if(bulan=12,unit,0)) b12
                    from(
                    select nama_comp,month(tgldokjdi) as bulan ,".$unit." as unit from bsp.bspsales".$year." a inner join bsp.tabprod b on a.deskripsi=b.namaprod where deskripsi in (".$kodeprod.") group by nama_comp,bulan 
                    ) a group by nama_comp";
            $query = $this->db->query($sql);
            $this->load->library('cezpdf');
            $this->cezpdf->Cezpdf('A4','landscape');

            foreach($query->result() as $row)
            {
                $db_data[] = array(
                'area'  => $row->nama_comp,
                'b1'    => number_format($row->b1,0),
                'b2'    => number_format($row->b2,0),
                'b3'    => number_format($row->b3,0),
                'b4'    => number_format($row->b4,0),
                'b5'    => number_format($row->b5,0),
                'b6'    => number_format($row->b6,0),
                'b7'    => number_format($row->b7,0),
                'b8'    => number_format($row->b8,0),
                'b9'    => number_format($row->b9,0),
                'b10'   => number_format($row->b10,0),
                'b11'   => number_format($row->b11,0),
                'b12'   => number_format($row->b12,0),
                );
            }
            $col_names = array(
            'area' => 'AREA',
            'b1' => 'January',
            'b2' => 'February',
            'b3' => 'March',
            'b4' => 'April',
            'b5' => 'May',
            'b6' => 'June',
            'b7' => 'july',
            'b8' => 'August',
            'b9' => 'September',
            'b10'=> 'October',
            'b11'=> 'November',
            'b12'=> 'December',
             );
            //$name = $this->getName($dp);
            //$namaprod = $this->get_product_name($kodeprod);
            $this->cezpdf->ezTable($db_data, $col_names, 'SELL OUT BSP '.$year.' For Product :'.$kodeprod, array('width'=>10,'fontSize' => 7.5));
            $this->cezpdf->ezStream();
    }
    public function sales_produk_bsp_excel($year=null,$kodeprod=null,$dp=null)
    {
        $uv=$this->input->post('uv');
        switch($uv)
        {
            case 0:
              $unit='sum(banyak)/isisatuan';
              break;
            case 1:
              $unit='sum(jumlah)';
              break;
        }
        $sql="select nama_comp
                    ,format(sum(if(bulan=1,unit,0)),0) b1
                    ,format(sum(if(bulan=2,unit,0)),0) b2
                    ,format(sum(if(bulan=3,unit,0)),0) b3
                    ,format(sum(if(bulan=4,unit,0)),0) b4
                    ,format(sum(if(bulan=5,unit,0)),0) b5
                    ,format(sum(if(bulan=6,unit,0)),0) b6
                    ,format(sum(if(bulan=7,unit,0)),0) b7
                    ,format(sum(if(bulan=8,unit,0)),0) b8
                    ,format(sum(if(bulan=9,unit,0)),0) b9
                    ,format(sum(if(bulan=10,unit,0)),0) b10
                    ,format(sum(if(bulan=11,unit,0)),0) b11
                    ,format(sum(if(bulan=12,unit,0)),0) b12
                    from(
                    select nama_comp,month(tgldokjdi) as bulan ,".$unit." as unit from bsp.bspsales".$year." a inner join bsp.tabprod b on a.deskripsi=b.namaprod where deskripsi in (".$kodeprod.") group by nama_comp,bulan 
                    ) a group by nama_comp";
         $query = $this->db->query($sql);
         to_excel($query);
    }
    public function print_bsp($year=null,$file=null)
    {
        $uv=$this->input->post('uv');
        switch($uv)
            {
                case 0:
                    $unit='banyak/isisatuan';
                    break;
                case 1:
                    $unit='jumlah';
                    break;
            }
        $id=$this->session->userdata('id');
        
        if ($id == '97') {
            
            $sql="select deskripsi,format(((b1+b2+b3+b4+b5+b6+b7+b8+b9+b10+b11+b12)/12),0) as rata,
                    format(b1,0) as b1, format(b2,0) as b2,
                    format(b3,0) as b3, format(b4,0) as b4,
                    format(b5,0) as b5, format(b6,0) as b6,
                    format(b7,0) as b7, format(b8,0) as b8,
                    format(b9,0) as b9, format(b10,0) as b10,
                    format(b11,0) as b11, format(b12,0) as b12,".$id."
                    from(
                    select deskripsi,
                    sum(if(bulan=1,unit,0)) as b1,sum(if(bulan=2,unit,0))as b2,
                    sum(if(bulan=3,unit,0))  as b3,sum(if(bulan=4,unit,0)) as b4,
                    sum(if(bulan=5,unit,0)) as b5,sum(if(bulan=6,unit,0)) as b6,
                    sum(if(bulan=7,unit,0)) as b7,sum(if(bulan=8,unit,0)) as b8,
                    sum(if(bulan=9,unit,0)) as b9,sum(if(bulan=10,unit,0)) as b10,
                    sum(if(bulan=11,unit,0)) as b11,sum(if(bulan=12,unit,0)) as b12
                    from (
                    select deskripsi,month(tgldokjdi) as bulan, sum(".$unit.") as unit,isisatuan from  bsp.bspsales".$year." a inner join bsp.tabprod b
                    on a.deskripsi=b.namaprod where b.supp =003 group by deskripsi, bulan
                    ) a group by deskripsi
                    )a
                    ";

        }else{

            $sql="select deskripsi,format(((b1+b2+b3+b4+b5+b6+b7+b8+b9+b10+b11+b12)/12),0) as rata,
                    format(b1,0) as b1, format(b2,0) as b2,
                    format(b3,0) as b3, format(b4,0) as b4,
                    format(b5,0) as b5, format(b6,0) as b6,
                    format(b7,0) as b7, format(b8,0) as b8,
                    format(b9,0) as b9, format(b10,0) as b10,
                    format(b11,0) as b11, format(b12,0) as b12,".$id."
                    from(
                    select deskripsi,
                    sum(if(bulan=1,unit,0)) as b1,sum(if(bulan=2,unit,0))as b2,
                    sum(if(bulan=3,unit,0))  as b3,sum(if(bulan=4,unit,0)) as b4,
                    sum(if(bulan=5,unit,0)) as b5,sum(if(bulan=6,unit,0)) as b6,
                    sum(if(bulan=7,unit,0)) as b7,sum(if(bulan=8,unit,0)) as b8,
                    sum(if(bulan=9,unit,0)) as b9,sum(if(bulan=10,unit,0)) as b10,
                    sum(if(bulan=11,unit,0)) as b11,sum(if(bulan=12,unit,0)) as b12
                    from (
                    select deskripsi,month(tgldokjdi) as bulan, sum(".$unit.") as unit,isisatuan from  bsp.bspsales".$year." a inner join bsp.tabprod b
                    on a.deskripsi=b.namaprod group by deskripsi, bulan
                    ) a group by deskripsi
                    )a
                    ";

        }

        $query = $this->db->query($sql);
        switch(strtoupper($file))
        {
            case 'PDF' :

            $this->load->library('cezpdf');
            $this->cezpdf->Cezpdf('A4','landscape');

            foreach($query->result() as $row)
            {
                $db_data[] = array(
                    'deskripsi' => $row->deskripsi,
                    'b1'    => $row->b1,
                    'b2'    => $row->b2,
                    'b3'    => $row->b3,
                    'b4'    => $row->b4,
                    'b5'    => $row->b5,
                    'b6'    => $row->b6,
                    'b7'    => $row->b7,
                    'b8'    => $row->b8,
                    'b9'    => $row->b9,
                    'b10'   => $row->b10,
                    'b11'   => $row->b11,
                    'b12'   => $row->b12,
                    'avg'   => $row->rata,
                    );
            }
            $col_names = array(
            'deskripsi' => 'Deskripsi',
            'b1' => 'January',
            'b2' => 'February',
            'b3' => 'March',
            'b4' => 'April',
            'b5' => 'May',
            'b6' => 'June',
            'b7' => 'july',
            'b8' => 'August',
            'b9' => 'September',
            'b10'=> 'October',
            'b11'=> 'November',
            'b12'=> 'December',
            'avg'=>'AVG '.$year

            );
              $this->cezpdf->ezTable($db_data, $col_names, 'SELL OUT BSP '.$year, array('width'=>10,'fontSize' => 7));
              $this->cezpdf->ezStream();
            break;
            case 'EXCEL':
                return to_excel($query);
            break;
        }
    }
}
?>