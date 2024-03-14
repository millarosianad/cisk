<?php if( ! defined('BASEPATH')) exit('Akses langsung tidak diperkenankan');
/**
* Basic_model
* menyediakan fungsi-fungsi dasar yang
* berhubungan dengan manipulasi basis data
*/
class Aam_model extends CI_Model
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
        $sql="select nama_comp from aam.aamsales".date('Y')." where kode_comp=".$dp." limit 1";
        $query = $this->db->query($sql);
        return $query->row();
    }
    public function Aam_model()
    {
        set_time_limit(0);
        ini_set('mysql.connect_timeout', 3000);
        ini_set('default_socket_timeout', 3000);

       
        $this->load->database();
        $this->load->library(array('table','session','template'));//session untuk sisi administrasi
        $this->load->helper(array('text','array','to_excel_helper'));
        //$this->config->load('sorot');
    }

    public function vessel($num)
    {
        $id=$this->session->userdata('id');
        switch($num)
        {
            case 1:
                $sql='delete from aam.soaam where id='.$id;
                $this->db->query($sql);
                ;break;
           
            case 2:
                $sql='delete from aam.outlet where id='.$id;
                $this->db->query($sql);
                ;break;
            /*
            case 3:
                $sql='delete from mpm.omzet where id='.$id;
                $this->db->query($sql);
                ;break;
            case 4:
                $sql='delete from mpm.sounit where id='.$id;
                $this->db->query($sql);
                ;break;*/
            case 5:
                $sql='delete from aam.socb where id='.$id;
                $this->db->query($sql);
                ;break;
             /*
            case 6:
                $sql='delete from mpm.sidp where id='.$id;
                $this->db->query($sql);
                ;break;*/
        }
    }
    public function listaam()
    {
        $sql='select kode_comp from aam.aamsales'.date('Y').' limit 1';
        $query = $this->db->query($sql);
        if($query->num_rows() > 0)
        {
                $sql='select distinct kode_comp,nama_comp from aam.aamsales'.date('Y').' order by nama_comp';
        }
        else
        {
                $sql='select distinct kode_comp,nama_comp from aam.aamsales'.(date('Y')-1).' order by nama_comp';
        }
        return $query = $this->db->query($sql);
    }
    public function listProduct()
    {
        $sql='select kode_comp from aam.aamsales'.date('Y').' limit 1';
        $query = $this->db->query($sql);
        if($query->num_rows() > 0)
        {
               $sql='select distinct deskripsi,kode_aam from aam.aamsales'.date('Y').' order by deskripsi';
        }
        else
        {
               $sql='select distinct deskripsi,kode_aam from aam.aamsales'.(date('Y')-1).' order by deskripsi';
        }

        return $query = $this->db->query($sql);
    }
    public function sales_per_product_aam($limit=null,$offset=null,$year=null,$kode=null)
    {
        $year2=(int)$year-1;
        $id=$this->session->userdata('id');
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
        $sql='select * from aam.socb where id='.$id.' order by cabang';
        $query = $this->db->query($sql);
        if($query->num_rows()>0)
        {
            $sql2  = $sql.' limit ? offset ?';
            $this->total_query = $query->num_rows();
            $query = $this->db->query($sql2,array($limit,$offset));
        }
        else
        {

            $sql1="insert into aam.socb
                        select @row:=@row+1,cabang,b1,b2,b3,b4,b5,b6,b7,b8,b9,b10,b11,b12,(b1+b2+b3+b4+b5+b6+b7+b8+b9+b10+b11+b12)  as ' TOTAL',".$id."  from(
                        select nama_comp as cabang,
                        sum(if(bulan=1,unit,0))as b1,
                        sum(if(bulan=2,unit,0))as b2,
                        sum(if(bulan=3,unit,0))as b3,
                        sum(if(bulan=4,unit,0))as b4,
                        sum(if(bulan=5,unit,0))as b5,
                        sum(if(bulan=6,unit,0))as b6,
                        sum(if(bulan=7,unit,0))as b7,
                        sum(if(bulan=8,unit,0))as b8,
                        sum(if(bulan=9,unit,0))as b9,
                        sum(if(bulan=10,unit,0))as b10,
                        sum(if(bulan=11,unit,0))as b11,
                        sum(if(bulan=12,unit,0))as b12 from(
                        select nama_comp,month(tgldokjdi) as bulan,sum(".$unit.") as unit from (select @row:=0)c, aam.aamsales".$year."
                        where kode_aam in (".$kode.")
                        group by nama_comp,bulan
                        )a group by nama_comp
                        )a
                        union all
                        select '-','~~~TOTAL~~~',t1,t2,t3,t4,t5,t6,t7,t8,t9,t10,t11,t12,(t1+t2+t3+t4+t5+t6+t7+t8+t9+t10+t11+t12),".$id."
                        from (select
                        'TOTAL',
                        sum(if (month(tgldokjdi)=1,".$unit.",0) ) as t1,
                        sum(if (month(tgldokjdi)=2,".$unit.",0) ) as t2 ,
                        sum(if (month(tgldokjdi)=3,".$unit.",0) ) as t3 ,
                        sum(if (month(tgldokjdi)=4,".$unit.",0) ) as t4 ,
                        sum(if (month(tgldokjdi)=5,".$unit.",0) ) as t5 ,
                        sum(if (month(tgldokjdi)=6,".$unit.",0) ) as t6 ,
                        sum(if (month(tgldokjdi)=7,".$unit.",0) ) as t7 ,
                        sum(if (month(tgldokjdi)=8,".$unit.",0) ) as t8 ,
                        sum(if (month(tgldokjdi)=9,".$unit.",0) ) as t9 ,
                        sum(if (month(tgldokjdi)=10,".$unit.",0) ) as t10 ,
                        sum(if (month(tgldokjdi)=11,".$unit.",0) ) as t11 ,
                        sum(if (month(tgldokjdi)=12,".$unit.",0) ) as t12
                        from aam.aamsales".$year." where kode_aam in (".$kode."
                        ))b
                    ";

            $query = $this->db->query($sql1);
            $sql='select * from aam.socb where id='.$id.' order by cabang';
            $query = $this->db->query($sql);
            $sql2  = $sql.' limit ? offset ?';
            $query = $this->db->query($sql);
            $this->total_query = $query->num_rows();
            $query = $this->db->query($sql2,array($limit,$offset));
        }


        if($query->num_rows() > 0)
        {
            $this->load->library('table');
            $this->table->set_empty('0');

            $this->table->set_template($this->tmpl);
            $this->table->set_caption('Sell Out aam');

            $this->table->set_heading('NO.','Branch','Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember','TOTAL');
            $i=1;
            foreach ($query->result() as $value)
            {
                $this->table->add_row(
                        $value->num
                        ,$value->cabang
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
                        ,'<div div style="text-align:right">' . number_format($value->total,0)  . '</div>'
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
    public function sales_aam($limit=null,$offset=null,$year=0)
    {
        $year2=(int)$year-1;
        $id=$this->session->userdata('id');
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
        $sql="select 1 from aam.soaam where id=".$id;
        $query = $this->db->query($sql);
        if($query->num_rows()>0)
        {
            $sql="select * from aam.soaam where id=".$id.
             " union all  
                 select '~~~TOTAL~~~',sum(rata), sum(b1),sum(b2),sum(b3),sum(b4),sum(b5),sum(b6)
                  ,sum(b7),sum(b8),sum(b9),sum(b10),sum(b11),sum(b12)
                  ,".$id." from aam.soaam where id=".$id." order by deskripsi";
            $sql2  = $sql.' limit ? offset ?';
            $this->total_query = $query->num_rows();
            $query = $this->db->query($sql2,array($limit,$offset));
        }
        else
        {
          $sql= "insert into aam.soaam
                select deskripsi,sum(unit) as rata,
                sum(if(bulan=1,unit,0)) b1,
                sum(if(bulan=2,unit,0)) b2,
                sum(if(bulan=3,unit,0)) b3,
                sum(if(bulan=4,unit,0)) b4,
                sum(if(bulan=5,unit,0)) b5,
                sum(if(bulan=6,unit,0)) b6,
                sum(if(bulan=7,unit,0)) b7,
                sum(if(bulan=8,unit,0)) b8,
                sum(if(bulan=9,unit,0)) b9,
                sum(if(bulan=10,unit,0)) b10,
                sum(if(bulan=11,unit,0)) b11,
                sum(if(bulan=12,unit,0)) b12,".$id."
                from(
                select kode_aam,deskripsi, month(tgldokjdi) as bulan,sum(".$unit.") as unit from aam.aamsales".$year." group by kode_aam,bulan
                )a group by deskripsi
            ";
            $query = $this->db->query($sql);
            $sql="select * from aam.soaam where id=".$id.
             " union all  
                 select '~~~TOTAL~~~',sum(rata), sum(b1),sum(b2),sum(b3),sum(b4),sum(b5),sum(b6)
                  ,sum(b7),sum(b8),sum(b9),sum(b10),sum(b11),sum(b12)
                  ,".$id." from aam.soaam where id=".$id." order by deskripsi";
            $query = $this->db->query($sql);
            $sql2  = $sql.' limit ? offset ?';
            $query = $this->db->query($sql);
            $this->total_query = $query->num_rows();
            $query = $this->db->query($sql2,array($limit,$offset));
        }

     
        if($query->num_rows() > 0)
        {
            $this->load->library('table');
            $this->table->set_empty('0');

            $this->table->set_template($this->tmpl);
            $this->table->set_caption('<H2>SELL OUT AAM</H2>');

            $this->table->set_heading('Deskripsi','Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember','TOTAL '.$year);
            foreach ($query->result() as $value)
            {
                $this->table->add_row($value->deskripsi
                        ,number_format($value->b1)
                        ,number_format($value->b2)
                        ,number_format($value->b3)
                        ,number_format($value->b4)
                        ,number_format($value->b5)
                        ,number_format($value->b6)
                        ,number_format($value->b7)
                        ,number_format($value->b8)
                        ,number_format($value->b9)
                        ,number_format($value->b10)
                        ,number_format($value->b11)
                        ,number_format($value->b12)
                        ,number_format($value->rata)
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

    public function sales_outlet_aam($offset=null,$year=null,$kodeprod=null,$dp=null)
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
       
        $sql='select * from aam.outlet where id='.$id.' order by tipe,outlet';
        $query = $this->db->query($sql);
        if($query->num_rows()>0)
        {
            $sql2  = $sql.' limit ? offset ?';
            $this->total_query = $query->num_rows();
            $query = $this->db->query($sql2,array($limit,$offset));
        }
        else
        {
              $sql="  insert into aam.outlet select kode,tipe,outlet,address,b1,b2,b3,b4,b5,b6,b7,b8,b9,b10,b11,b12,(b1+b2+b3+b4+b5+b6+b7+b8+b9+b10+b11+b12)/12 as rata,id from (
                select kode, outlet,address,tipe,
                sum(if(blndok=1,unit/isisatuan,0)) as b1,sum(if(blndok=2,unit/isisatuan,0)) as b2, sum(if(blndok=3,unit/isisatuan,0)) as b3,
                sum(if(blndok=4,unit/isisatuan,0)) as b4, sum(if(blndok=5,unit/isisatuan,0)) as b5,sum(if(blndok=6,unit/isisatuan,0)) as b6,
                sum(if(blndok=7,unit/isisatuan,0)) as b7,sum(if(blndok=8,unit/isisatuan,0)) as b8, sum(if(blndok=9,unit/isisatuan,0)) as b9,
                sum(if(blndok=10,unit/isisatuan,0)) as b10, sum(if(blndok=11,unit/isisatuan,0)) as b11,sum(if(blndok=12,unit/isisatuan,0)) as b12,".$id." as id
                from (
                select kode_lang as kode,isisatuan, nama_type as tipe,nama_lang as outlet,alamat as address,sum(".$unit.")as unit,month(tgldokjdi) as blndok  from aam.aamsales".$year."
                    a inner join aam.tabprod b on a.deskripsi=b.namaprod
                where kode_comp = ".$dp." and kode_aam in (".$kodeprod.") group by kode_lang,blndok
                )a group by kode order by kode,tipe
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

            $sql='select * from aam.outlet where id='.$id.' order by tipe,outlet';
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
    public function sales_outlet_aam_pdf($year=null,$kodeprod=null,$dp=null)
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
                select kode_lang as kode, nama_type as tipe,nama_lang as outlet,alamat as address,sum(".$unit.")as unit,month(tgldokjdi) as blndok  from aam.aamsales".$year." where kode_comp = ".$dp." and kode_aam in (".$kodeprod.") group by kode_lang,blndok
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
    public function sales_outlet_aam_excel($year=null,$kodeprod=null,$dp=null)
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
                select kode_lang as kode, nama_type as tipe,nama_lang as outlet,alamat as address,sum(".$unit.")as unit,month(tgldokjdi) as blndok  from aam.aamsales".$year." where kode_comp = ".$dp." and kode_aam in (".$kodeprod.") group by kode_lang,blndok
                )a group by kode
                )a";
         $query = $this->db->query($sql);
         to_excel($query);
    }
    public function print_aam($year=null,$file=null)
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
        $id=$this->session->userdata('id');
        $sql="select  deskripsi,format(((b1+b2+b3+b4+b5+b6+b7+b8+b9+b10+b11+b12)/12),0) as rata,
                        format(b1,0) as b1, format(b2,0) as b2,
                        format(b3,0) as b3, format(b4,0) as b4,
                        format(b5,0) as b5, format(b6,0) as b6,
                        format(b7,0) as b7, format(b8,0) as b8,
                        format(b9,0) as b9, format(b10,0) as b10,
                        format(b11,0) as b11, format(b12,0) as b12,".$id."
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
                                sum(".$unit.") as unit,
                                isisatuan 
                        from    aam.aamsales".$year." a left join aam.tabprod b
                                    on a.deskripsi=b.namaprod group by deskripsi, bulan
                    ) a group by deskripsi
                )a
                    ";

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
              $this->cezpdf->ezTable($db_data, $col_names, 'SELL OUT aam '.$year, array('width'=>10,'fontSize' => 7));
              $this->cezpdf->ezStream();
            break;
            case 'EXCEL':
                return to_excel($query);
            break;
        }
    }
    public function print_per_product_aam($year=null,$kode=null,$file=null)
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
        $id=$this->session->userdata('id');
            $sql="select @row:=@row+1 as num,cabang,b1,b2,b3,b4,b5,b6,b7,b8,b9,b10,b11,b12,(b1+b2+b3+b4+b5+b6+b7+b8+b9+b10+b11+b12)  as ' total' from(
                        select nama_comp as cabang,
                        sum(if(bulan=1,unit,0))as b1,
                        sum(if(bulan=2,unit,0))as b2,
                        sum(if(bulan=3,unit,0))as b3,
                        sum(if(bulan=4,unit,0))as b4,
                        sum(if(bulan=5,unit,0))as b5,
                        sum(if(bulan=6,unit,0))as b6,
                        sum(if(bulan=7,unit,0))as b7,
                        sum(if(bulan=8,unit,0))as b8,
                        sum(if(bulan=9,unit,0))as b9,
                        sum(if(bulan=10,unit,0))as b10,
                        sum(if(bulan=11,unit,0))as b11,
                        sum(if(bulan=12,unit,0))as b12 from(
                        select nama_comp,month(tgldokjdi) as bulan,sum(".$unit.") as unit from (select @row:=0)c, aam.aamsales".$year."
                        where kode_aam in (".$kode.")
                        group by nama_comp,bulan
                        )a group by nama_comp
                        )a
                        union all
                        select '-','TOTAL',t1,t2,t3,t4,t5,t6,t7,t8,t9,t10,t11,t12,(t1+t2+t3+t4+t5+t6+t7+t8+t9+t10+t11+t12)
                        from (select
                        'TOTAL',
                        sum(if (month(tgldokjdi)=1,".$unit.",0) ) as t1,
                        sum(if (month(tgldokjdi)=2,".$unit.",0) ) as t2 ,
                        sum(if (month(tgldokjdi)=3,".$unit.",0) ) as t3 ,
                        sum(if (month(tgldokjdi)=4,".$unit.",0) ) as t4 ,
                        sum(if (month(tgldokjdi)=5,".$unit.",0) ) as t5 ,
                        sum(if (month(tgldokjdi)=6,".$unit.",0) ) as t6 ,
                        sum(if (month(tgldokjdi)=7,".$unit.",0) ) as t7 ,
                        sum(if (month(tgldokjdi)=8,".$unit.",0) ) as t8 ,
                        sum(if (month(tgldokjdi)=9,".$unit.",0) ) as t9 ,
                        sum(if (month(tgldokjdi)=10,".$unit.",0) ) as t10 ,
                        sum(if (month(tgldokjdi)=11,".$unit.",0) ) as t11 ,
                        sum(if (month(tgldokjdi)=12,".$unit.",0) ) as t12
                        from aam.aamsales".$year." where kode_aam in (".$kode.")
                        )b
                    ";

        $query = $this->db->query($sql);
        switch(strtoupper($file))
        {
            case 'PDF' :

            $this->load->library('cezpdf');
            $this->cezpdf->Cezpdf('A4','landscape');

            foreach($query->result() as $row)
            {
                $db_data[] = array(
                    'no' => $row->num,
                    'branch'=> $row->cabang,
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
                    'total'   => number_format($row->total,0)
                    );
            }
            $col_names = array(
            'no' => 'No',
            'branch'=> 'Branch',
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
            'total'=>'TOTAL'

            );
              $this->cezpdf->ezTable($db_data, $col_names, 'SELL OUT AAM '.$year, array('width'=>10,'fontSize' => 7));
              $this->cezpdf->ezStream();
            break;
            case 'EXCEL':
                return to_excel($query);
            break;
        }
    }
}
?>