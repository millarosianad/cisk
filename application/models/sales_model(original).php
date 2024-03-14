
<?php if( ! defined('BASEPATH')) exit('Akses langsung tidak diperkenankan');
/**
* Basic_model
* menyediakan fungsi-fungsi dasar yang
* berhubungan dengan manipulasi basis data
*/
class Sales_model extends CI_Model
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
                'table_open'=>'<table class="table" cellpadding="4" cellspacing="1" style="font-size:12px">'
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
    
    public function Sales_model()
    {
        set_time_limit(0);
        ini_set('mysql.connect_timeout', 3000);
        ini_set('default_socket_timeout', 3000);

        $this->load->database();
        $this->load->library(array('table','session'));//session untuk sisi administrasi
        $this->load->helper(array('text','to_excel_helper','array'));

        //$this->config->load('sorot');
    }
    protected function getNocab($naper)
    {
        $sql="select nocab from mpm.tabcomp where naper=?";
        $query = $this->db->query($sql,array($naper));
        $nocab='';
        foreach($query->result() as $row)
        {
            $nocab.=",".$row->nocab;
        }
        $naper=preg_replace('/,/', '', $nocab,1);
        return $naper;
    }
    public function getTotalQuery()
    {
        return $this->total_query;
    }
    public function getSalesName($key='')
    {
        //$year=year(now());
        $sql='select distinct concat(kodesales,fileid)kodesales,namasales from data'.date('Y').'.tabsales where nocab=?';
        $query=$this->db->query($sql,array($key));
        return $query;
    }
    protected function make_query($fields='',$table='',$where=array(),$limit=NULL,$offset='',$order=array('field'=>NULL,'sort'=>'ASC'))
    {
        $this->db->select($fields);

        if( ! is_null($order['field']))
        {
            $this->db->order_by($order['field'],$order['sort']);
        }

        return $this->db->get_where($table,$where,$limit,$offset);
    }
    public function getSuppbyid()
    {
        $supp=$this->session->userdata('supp');
        if($supp=='000')
        {
            return $this->db->query('select supp,namasupp from mpm.tabsupp');
        }
        else
        {
            return $this->db->query('select supp,namasupp from mpm.tabsupp where supp='.$supp);
        }
    }
    public function list_dp()
    {
        $userid=$this->session->userdata('id');
        $nocab=$this->session->userdata('nocab');
        if ($nocab!='')
        {
            if($userid =='191')//pak jan
            {
                return $this->db->query('select distinct naper, nama_comp from mpm.tabcomp where  naper!=99 and wilayah=2 and naper like "%'.$nocab.'%" order by nama_comp');
            }
            else if($userid == '232')//pak kartono
            {
                return $this->db->query('select distinct naper, nama_comp from mpm.tabcomp where  naper!=99 and wilayah=1 and naper like "%'.$nocab.'%" order by nama_comp');
            }
             else{
                 return $this->db->query('select distinct naper, nama_comp from mpm.tabcomp where  naper!=99 and naper like "%'.$nocab.'%" order by nama_comp');
             }       
            
        }
        else
        {
            return $this->db->query('select distinct naper, nama_comp from mpm.tabcomp where  naper!=99 order by nama_comp');
        }
    }
    public function list_product2()
    {
        return $this->db->query('select kodeprod, namaprod from mpm.tabprod where active=1 order by namaprod');
    }
    public function list_product()
    {
        $supp=$this->session->userdata('supp');
        
        if($supp=='000')
        {
            return $this->db->query('select supp,namasupp,kodeprod, namaprod from mpm.tabprod a left join mpm.tabsupp b using(supp) where left(kodeprod,3)<>"BSP"  and a.report=1 order by supp,namaprod');
        }
        else
        {
            return $this->db->query('select supp,namasupp,kodeprod, namaprod from mpm.tabprod a left join mpm.tabsupp b using(supp) where supp='.$supp.' and left(kodeprod,3)<>"BSP" and a.report=1 order by namaprod');
        }
    }
    public function list_product_permen()
    {
        $supp=$this->session->userdata('supp');
        
        if($supp=='000')
        {
            return $this->db->query('select supp,namasupp,kodeprod, namaprod from mpm.tabprod a left join mpm.tabsupp b using(supp) where left(kodeprod,3)<>"BSP"  and a.report=1 and permen=1 order by supp,namaprod');
        }
        else
        {
            return $this->db->query('select supp,namasupp,kodeprod, namaprod from mpm.tabprod a left join mpm.tabsupp b using(supp) where supp='.$supp.' and left(kodeprod,3)<>"BSP" and a.report=1 and permen=1 order by namaprod');
        }
    }
    protected function get_dp_name($nocab = null)
    {
        $sql = 'select nama_comp from mpm.tabcomp where nocab = ?';
        $query = $this->db->query($sql,array($nocab));
        return $query->row();
    }
    protected function get_product_name($kodeprod= null)
    {
        $sql="select namaprod from mpm.tabprod where kodeprod in (".$kodeprod.")";
        $query = $this->db->query($sql,array($kodeprod));
        $namaprod='';
        foreach($query->result() as $row)
        {
            $namaprod.=" ,'".$row->namaprod."'";
        }
        return $namaprod = preg_replace('/,/', '', $namaprod,1);
    }
    protected function get_product_title($kodeprod= null)
    {
        $sql="select namaprod from mpm.tabprod where kodeprod in (".$kodeprod.")";
        $query = $this->db->query($sql,array($kodeprod));
        $namaprod='';
        foreach($query->result() as $row)
        {
            $namaprod.="_".str_replace(" ","",$row->namaprod);
        }
        return $namaprod = preg_replace('/_/', '', $namaprod,1);
    }
    public function peta()
    {
            //$sql="select nama_comp,company,latitude,address,longitude from mpm.user a inner join mpm.tabcomp b on a.username = b.kode_comp where !isnull(latitude)";
            $year_current=date('Y');
            $year_past=date('m')=='01' ? date('Y')-1:date('Y');
            $sql="select nocab,nama_comp,company,persen,latitude,longitude 
                    from(
                    select b.kode_comp,nama_comp,nocab,ceil(current/past*100)persen from(
                    select a.nocab,sum(a.tot1)/max(hrdok) current, past from data".$year_current.".fi a 
                    INNER join (select nocab, sum(tot1)/max(hrdok) past from data".$year_past.".fi where bulan=if(MONTH(now())-1=0,12,MONTH(now())-1) group by nocab) b using(nocab)
                    where a.bulan=MONTH(now())group by nocab)a  left join tabcomp b using(nocab)
                    )a left join mpm.user c on a.kode_comp = c.username";
            $query = $this->db->query($sql);
            $this->load->library('googlemaps');
            //$this->load->library('geoip_lib');

            if($query->num_rows()>0)
            {
                $config['zoom'] = 'auto';
                    //$config['cluster'] = TRUE;
                $this->googlemaps->initialize($config);
                foreach ($query->result() as $value)
                {
                    //$this->geoip_lib->InfoIP($value->sourceip);
                    //echo $value->sourceip;
                    //$config['center'] = '37.4419, -122.1419';
                    $gps=$value->latitude.",".$value->longitude;//$this->geoip_lib->result_custom('%LA, %LO');
                    //$gps='-6.17210,+106.76758';
                    //$config['center'] = "'".$this->geoip_lib->result_latitude().",".$this->geoip_lib->result_longitude()."'";


                    $marker = array();
                    $marker['position']= $gps;
                    //$text= $this->hextostr($value->data).br(2);
                    $marker['infowindow_content'] =$value->nama_comp.'<br />'.$value->company;
                    //$marker['title']= $value->company;
                    if($value->persen<60)
                    {
                        $marker['icon'] = base_url()."assets/css/images/red.png";
                    }
                    else if($value->persen>=60 and $value->persen<=90)
                    {
                        $marker['icon'] = base_url()."assets/css/images/yellow.png";
                    }
                    else {
                        $marker['icon'] = base_url()."assets/css/images/green.png";
                    }
                    $this->googlemaps->add_marker($marker);
                }

                return $this->googlemaps->create_map();
          }
          else
                return false;

    }
    public function print_per_dp($dp=null, $year=null,$file=null)
    {
        $naper=$this->getNocab($dp);
        $year2=(int)$year-1;
        $uv=$this->input->post('uv');
        switch($uv)
        {
            case 0:$unit='banyak';break;
            case 1:$unit='tot1';break;
        }
        $supp=$this->session->userdata('supp');
        if($supp=='000')
        {
            $wheresupp='';
        }
        else if($supp=='005')
        {
            $wheresupp='and kodeprod like "06%" ';
        }
        else
        {
            $wheresupp="and supp=".$supp;
        }
        $sql ="
                select kodeprod,namaprod,
                sum(if(blndok=1,unit,0)) as b1,sum(if(blndok=2,unit,0)) as b2,
                sum(if(blndok=3,unit,0)) as b3,sum(if(blndok=4,unit,0)) as b4,
                sum(if(blndok=5,unit,0)) as b5,sum(if(blndok=6,unit,0)) as b6,
                sum(if(blndok=7,unit,0)) as b7,sum(if(blndok=8,unit,0)) as b8,
                sum(if(blndok=9,unit,0)) as b9,sum(if(blndok=10,unit,0)) as b10,
                sum(if(blndok=11,unit,0)) as b11,sum(if(blndok=12,unit,0)) as b12
                from
                (
                    select kodeprod,namaprod,sum(".$unit.") as unit, blndok from data".$year.".fi where nocab in (select nocab from mpm.tabcomp where naper in (".$dp.")) ".$wheresupp." group by kodeprod, blndok
                    union all
                    select kodeprod,namaprod,sum(".$unit.") as unit, blndok from data".$year.".ri where nocab in (select nocab from mpm.tabcomp where naper in (".$dp.")) ".$wheresupp." group by kodeprod, blndok
                )a group by kodeprod
            ";

        $query = $this->db->query($sql);
        switch(strtoupper($file))
        {
            case 'PDF':

        $this->load->library('cezpdf');
        $this->cezpdf->Cezpdf('A4','landscape');

        foreach($query->result() as $row)
        {
            $db_data[] = array(
                'code'   => $row->kodeprod,
                'product'=> $row->namaprod,
                'b1'     => $row->b1,
                'b2'     => $row->b2,
                'b3'     => $row->b3,
                'b4'     => $row->b4,
                'b5'     => $row->b5,
                'b6'     => $row->b6,
                'b7'     => $row->b7,
                'b8'     => $row->b8,
                'b9'     => $row->b9,
                'b10'    => $row->b10,
                'b11'    => $row->b11,
                'b12'    => $row->b12,
                );
        }
        $col_names = array(
        'code' => 'Code',
        'product'=>'Product',
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
        $name = $this->get_dp_name($dp);
        $this->cezpdf->ezTable($db_data, $col_names, 'SELL OUT "'.$name->nama_comp.'" '.$year, array('width'=>10,'fontSize' => 7.5));
        $this->cezpdf->ezStream();
        break;
        case 'EXCEL':
        {
            $name = $this->get_dp_name($dp);
            $title=str_replace(' ','_',$name->nama_comp);
            return to_excel($query,$title);
        }break;
        case 'GRAFIK':
        {
            $name = $this->get_dp_name($dp);
            $title=$name->nama_comp;
            $year2=$year-1;
            $params = array('width' => 800, 'height' => 600, 'margin' => 15, 'backgroundColor' => '#eeeeee');
            $this->load->library('chart', $params);
            if($year2!=2009)
            {
                   $last='union all
                            select concat(bulan,thndok) bulan, sum(tot1) as omzet from data'.$year2.'.fi where nocab in ('.$naper.') and nodokjdi <> "XXXXXX"  group by bulan
                            union all
                            select concat(bulan,thndok) bulan, sum(tot1) as omzet from data'.$year2.'.ri where nocab in ('.$naper.') and nodokjdi <> "XXXXXX"  group by bulan
                        ';
                   $last2='
                        sum(if(bulan=1'.$year2.',omzet,0)) b12,
                        sum(if(bulan=2'.$year2.',omzet,0)) b22,
                        sum(if(bulan=3'.$year2.',omzet,0)) b32,
                        sum(if(bulan=4'.$year2.',omzet,0)) b42,
                        sum(if(bulan=5'.$year2.',omzet,0)) b52,
                        sum(if(bulan=6'.$year2.',omzet,0)) b62,
                        sum(if(bulan=7'.$year2.',omzet,0)) b72,
                        sum(if(bulan=8'.$year2.',omzet,0)) b82,
                        sum(if(bulan=9'.$year2.',omzet,0)) b92,
                        sum(if(bulan=10'.$year2.',omzet,0))b102,
                        sum(if(bulan=11'.$year2.',omzet,0))b112,
                        sum(if(bulan=12'.$year2.',omzet,0))b122,';

               }

               $sql='
                   select '.$last2.'
                   sum(if(bulan=1'.$year.',omzet,0)) b11,
                   sum(if(bulan=2'.$year.',omzet,0)) b21,
                   sum(if(bulan=3'.$year.',omzet,0)) b31,
                   sum(if(bulan=4'.$year.',omzet,0)) b41,
                   sum(if(bulan=5'.$year.',omzet,0)) b51,
                   sum(if(bulan=6'.$year.',omzet,0)) b61,
                   sum(if(bulan=7'.$year.',omzet,0)) b71,
                   sum(if(bulan=8'.$year.',omzet,0)) b81,
                   sum(if(bulan=9'.$year.',omzet,0)) b91,
                   sum(if(bulan=10'.$year.',omzet,0))b101,
                   sum(if(bulan=11'.$year.',omzet,0))b111,
                   sum(if(bulan=12'.$year.',omzet,0))b121 from(
                   select bulan, sum(omzet) as omzet from (
                   select concat(bulan,thndok) as bulan, sum(tot1) as omzet from data'.$year.'.fi where nocab in ('.$naper.') and nodokjdi <> "XXXXXX"  group by bulan
                   union all
                   select concat(bulan,thndok) as bulan, sum(tot1) as omzet from data'.$year.'.ri where nocab in ('.$naper.') and nodokjdi <> "XXXXXX"  group by bulan
                   '.$last.'
                    )a group by bulan order by bulan)a';

               $query=$this->db->query($sql);
               $data_last=array();
               $data_now=array();

               foreach($query->result() as $row)
               {
                   if($year2!=2009)
                   {
                        $data_last[]=$row->b12;
                        $data_last[]=$row->b22;
                        $data_last[]=$row->b32;
                        $data_last[]=$row->b42;
                        $data_last[]=$row->b52;
                        $data_last[]=$row->b62;
                        $data_last[]=$row->b72;
                        $data_last[]=$row->b82;
                        $data_last[]=$row->b92;
                        $data_last[]=$row->b102;
                        $data_last[]=$row->b112;
                        $data_last[]=$row->b122;
                   }
                   $data_now[]=$row->b11;
                   $data_now[]=$row->b21;
                   $data_now[]=$row->b31;
                   $data_now[]=$row->b41;
                   $data_now[]=$row->b51;
                   $data_now[]=$row->b61;
                   $data_now[]=$row->b71;
                   $data_now[]=$row->b81;
                   $data_now[]=$row->b91;
                   $data_now[]=$row->b101;
                   $data_now[]=$row->b111;
                   $data_now[]=$row->b121;
               }
            /*
            $sql="select bulan,sum(omzet) as omzet
                  from
                     (
                        select bulan,sum(tot1)as omzet from data".$year.".fi where nocab in (".$naper.") ".$wheresupp." group by bulan
                        union all
                        select bulan,sum(tot1)as omzet from data".$year.".ri where nocab in (".$naper.") ".$wheresupp." group by bulan
                     )a group by bulan order by bulan";
            $query=$this->db->query($sql);

            $data_now=array();
            foreach($query->result() as $row)
            {
               $data_now[]=$row->omzet;
            }
            $data_last=array();
            if($year2!=2009)
            {
               $sql="select bulan,sum(omzet) as omzet
                    from
                    (
                        select bulan,sum(tot1)as omzet from data".$year2.".fi where nocab in (".$naper.") ".$wheresupp." group by bulan
                        union all
                        select bulan,sum(tot1)as omzet from data".$year2.".ri where nocab in (".$naper.") ".$wheresupp." group by bulan
                    )a group by bulan order by bulan";
              $query=$this->db->query($sql);

              foreach($query->result() as $row)
              {
                   $data_last[]=$row->omzet;
              }
              $this->chart->addSeries($data_last,'line',$year2, LARGE_SOLID,'#00ff00', '#00ff00');
           }*/

           $Labels = array('Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec');
           $this->chart->setTitle("Omzet ".$title,"#000000",2);
           $this->chart->setLegend(SOLID, "#444444", "#ffffff", 2);
           $this->chart->setPlotArea(SOLID,"#444444", '#dddddd');
           $this->chart->setFormat(0,',','.');


           $this->chart->addSeries($data_now,'bar',$year, LARGE_SOLID,'#F6B442', '#F6B442');
           $this->chart->addSeries($data_last,'line',$year2,LARGE_SOLID,'#000', '#000');

           $this->chart->setXAxis('#000000', SOLID, 3, "Month");

           $this->chart->setYAxis('#000000', SOLID, 3, "Value");

           $this->chart->setLabels($Labels, '#000000', 3, HORIZONTAL);
           $this->chart->setGrid("#bbbbbb", DASHED, "#bbbbbb", DOTTED);
           $this->chart->plot('assets/images/omzet.png');
           $data['title']='omzet.png';
           $this->load->view('graph',$data);
           }break;
        }
    }
    public function print_stock_product($year=null,$code=null,$file=null)
    {
         //$naper=$this->getNocab($dp);
        $sql='select max(bulan) as bulan from data'.$year.'.st where bulan='.date('m');
        $query = $this->db->query($sql);
        $row=$query->row();
        $bulan=$row->bulan;
        $tanggal=$year.'-'.substr('00'.$bulan,-2).'-01';
        $harga=1;
        $date[6]=date('Y-m-d',strtotime('-6 month',strtotime($tanggal)));
        $date[5]=date('Y-m-d',strtotime('-5 month',strtotime($tanggal)));
        $date[4]=date('Y-m-d',strtotime('-4 month',strtotime($tanggal)));
        $date[3]=date('Y-m-d',strtotime('-3 month',strtotime($tanggal)));
        $date[2]=date('Y-m-d',strtotime('-2 month',strtotime($tanggal)));
        $date[1]=date('Y-m-d',strtotime('-1 month',strtotime($tanggal)));

        $selectfi="select nocab,sum(banyak) as  average from data".date('Y',strtotime($date[1])).".fi
                    where kodeprod in (".$code.") and bulan =".date('m',strtotime($date[1]))." group by nocab";
        for($i=2;$i<=6;$i++)
        {
             $selectfi.=" union all
                select nocab,sum(banyak) as  average from data".date('Y',strtotime($date[$i])).".fi
                where kodeprod in (".$code.") and bulan =".date('m',strtotime($date[$i]))." group by nocab
                ";
        }
        $selectri="select nocab,sum(banyak) as  average from data".date('Y',strtotime($date[1])).".ri
                  where kodeprod in (".$code.") and bulan =".date('m',strtotime($date[1]))." group by nocab";
        for($i=2;$i<=6;$i++)
        {
            $selectri.=" union all
                 select nocab,sum(banyak) as  average from data".date('Y',strtotime($date[$i])).".ri
                 where kodeprod in (".$code.") and bulan =".date('m',strtotime($date[$i]))." group by nocab
                 ";
        }
         $uv=$this->input->post('uv');
        switch($uv)
        {
             case 0:
             {
                 $harga=1;
                
             }break;
             case 1:
             {
                 $harga='h_dp';
                 
             }break;
        }
        $sql="select c.naper,c.nama_comp,b1,b2,b3,b4,b5,b6,b7,b8,b9,b10,b11,b12 from(
                 select nocab, 
                     sum(if(bulan=1,stok*".$harga.",0)) b1,
                     sum(if(bulan=2,stok*".$harga.",0)) b2,
                     sum(if(bulan=3,stok*".$harga.",0)) b3,
                     sum(if(bulan=4,stok*".$harga.",0)) b4,
                     sum(if(bulan=5,stok*".$harga.",0)) b5,
                     sum(if(bulan=6,stok*".$harga.",0)) b6,
                     sum(if(bulan=7,stok*".$harga.",0)) b7,
                     sum(if(bulan=8,stok*".$harga.",0)) b8,
                     sum(if(bulan=9,stok*".$harga.",0)) b9,
                     sum(if(bulan=10,stok*".$harga.",0)) b10,
                     sum(if(bulan=11,stok*".$harga.",0)) b11,
                     sum(if(bulan=12,stok*".$harga.",0)) b12
                 from(
                 select
          a.nocab,b.h_dp,
          substr(bulan, 3)AS bulan,a.kodeprod,
          sum( if(kode_gdg='PST',
                ((Saldoawal+masuk_pbk+masuk_supp+retur_sal+bPinjam+kvretur+kr_gudang+retur_depo)-
                (sales+pinjam+minta_depo+kvbeli+kr_min)-(rusak+sisih)),(Saldoawal+masuk_pbk+retur_sal+kvretur+bPinjam+tukar_msk+msk_gddepo) -
                (sales+Pinjam+kvbeli+retur_depo+tukar_klr+klr_gddepo))) as stok
            FROM
            data".$year.".st a 
            inner join (select a.kodeprod, a.h_dp * (100-d_dp)/100 as h_dp from prod_detail a inner join tabprod b using(kodeprod)
                        where tgl=(select max(tgl) from prod_detail where kodeprod=a.kodeprod) group by kodeprod) b using(kodeprod)
                where
                    a.kodeprod in (".$code.")
                AND kode_gdg != ''
                
    
            GROUP BY
                nocab,
                bulan,kodeprod
            ORDER BY
                nocab
                    ) a group by nocab
                    ) a inner join mpm.tabcomp c using(nocab) group by naper order by nama_comp";
                
        $query = $this->db->query($sql);
        switch(strtoupper($file))
        {
            case 'PDF':

        $this->load->library('cezpdf');
        $this->cezpdf->Cezpdf('A4','landscape');

        foreach($query->result() as $row)
        {
            $db_data[] = array(
                'nocab'  => $row->naper,
                'DP'     => $row->nama_comp,
                'b1'     => number_format($row->b1,0),
                'b2'     => number_format($row->b2,0),
                'b3'     => number_format($row->b3,0),
                'b4'     => number_format($row->b4,0),
                'b5'     => number_format($row->b5,0),
                'b6'     => number_format($row->b6,0),
                'b7'     => number_format($row->b7,0),
                'b8'     => number_format($row->b8,0),
                'b9'     => number_format($row->b9,0),
                'b10'    => number_format($row->b10,0),
                'b11'    => number_format($row->b11,0),
                'b12'    => number_format($row->b12,0)

                );
        }
        $col_names = array(
        'nocab' => 'Nocab',
        'DP' => 'DP',
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
        $name = $this->get_dp_name($dp);
        $this->cezpdf->ezTable($db_data, $col_names, 'SALDO AKHIR STOK '.$year, array('width'=>10,'fontSize' => 7.5));
        $this->cezpdf->ezStream();
        break;
        case 'EXCEL':
            return to_excel($query);
            break;
        }
    }
    public function print_stock_dp($dp=null, $year=null,$file=null)
    {
        $naper=$this->getNocab($dp);
        $sql='select max(bulan) as bulan from data'.$year.'.st where nocab=?';
        $query = $this->db->query($sql,array($dp));
        $row=$query->row();
        $bulan=$row->bulan;
        $tanggal=$year.'-'.substr('00'.$bulan,-2).'-01';
        $cycle=6;

        $date[6]=date('Y-m-d',strtotime('-6 month',strtotime($tanggal)));
        $date[5]=date('Y-m-d',strtotime('-5 month',strtotime($tanggal)));
        $date[4]=date('Y-m-d',strtotime('-4 month',strtotime($tanggal)));
        $date[3]=date('Y-m-d',strtotime('-3 month',strtotime($tanggal)));
        $date[2]=date('Y-m-d',strtotime('-2 month',strtotime($tanggal)));
        $date[1]=date('Y-m-d',strtotime('-1 month',strtotime($tanggal)));

        $selectfi="select kodeprod,sum(banyak) as  average from data".date('Y',strtotime($date[1])).".fi
                    where nocab=".$dp." and kode_type<>'TD' and bulan =".date('m',strtotime($date[1]))." group by kodeprod";
        for($i=2;$i<=$cycle;$i++)
        {
             $selectfi.=" union all
                select kodeprod,sum(banyak) as  average from data".date('Y',strtotime($date[$i])).".fi
                where nocab=".$dp." and kode_type<>'TD' and bulan =".date('m',strtotime($date[$i]))." group by kodeprod
                ";
        }
        $selectri="select kodeprod,sum(banyak) as  average from data".date('Y',strtotime($date[1])).".ri
                  where nocab=".$dp." and bulan =".date('m',strtotime($date[1]))." group by kodeprod";
        for($i=2;$i<=$cycle;$i++)
        {
            $selectri.=" union all
                 select kodeprod,sum(banyak) as  average from data".date('Y',strtotime($date[$i])).".ri
                 where nocab=".$dp." and bulan =".date('m',strtotime($date[$i]))." group by kodeprod
                 ";
        }

        
        $uv=$this->input->post('uv');
        switch($uv)
        {
             case 0:
             {
                 $harga=1;
                 $harga2=1;
             }break;
             case 1:{$harga='b.h_dp';$harga2='h_dp';}break;
        }
        $sql="select kodeprod,namaprod,(b.rata*".$harga2.") as rata ,b1,b2,b3,b4,b5,b6,b7,b8,b9,b10,b11,b12 from(
                select a.kodeprod,a.namaprod,".$harga."
                ,sum(if(bulan=1,stok,0))*".$harga." as b1
                ,sum(if(bulan=2,stok,0))*".$harga." as b2
                ,sum(if(bulan=3,stok,0))*".$harga." as b3
                ,sum(if(bulan=4,stok,0))*".$harga." as b4
                ,sum(if(bulan=5,stok,0))*".$harga." as b5
                ,sum(if(bulan=6,stok,0))*".$harga." as b6
                ,sum(if(bulan=7,stok,0))*".$harga." as b7
                ,sum(if(bulan=8,stok,0))*".$harga." as b8
                ,sum(if(bulan=9,stok,0))*".$harga." as b9
                ,sum(if(bulan=10,stok,0))*".$harga." as b10
                ,sum(if(bulan=11,stok,0))*".$harga." as b11
                ,sum(if(bulan=12,stok,0))*".$harga." as b12
                from(
                select kodeprod,namaprod,substr(bulan,3)as bulan,sum( if(kode_gdg='PST',
                ((Saldoawal+masuk_pbk+masuk_supp+retur_sal+bPinjam+kvretur+kr_gudang+retur_depo)-
                (sales+pinjam+minta_depo+kvbeli+kr_min)-(rusak+sisih)),(Saldoawal+masuk_pbk+retur_sal+kvretur+bPinjam+tukar_msk+msk_gddepo) -
                (sales+Pinjam+kvbeli+retur_depo+tukar_klr+klr_gddepo))) as stok
                 from data".$year.".st where nocab in (".$naper.")  and kode_gdg!= '' group by kodeprod,bulan
                order by kodeprod
                )a left join (select a.kodeprod, a.h_dp * (100-d_dp)/100 as h_dp from prod_detail a inner join tabprod b using(kodeprod)
                        where tgl=(select max(tgl) from prod_detail where kodeprod=a.kodeprod)) b using(kodeprod) group by kodeprod)
                a left join (select kodeprod,sum(average)/6 as rata from(
                        ".$selectfi."
                        union all
                        ".$selectri."
                )a group by kodeprod )b using(kodeprod)
                ";
        $query = $this->db->query($sql);
        switch(strtoupper($file))
        {
            case 'PDF':

        $this->load->library('cezpdf');
        $this->cezpdf->Cezpdf('A3','landscape');

        foreach($query->result() as $row)
        {
            $db_data[] = array(
                'code'   => $row->kodeprod,
                'product'=> $row->namaprod,
                'avg'    => number_format($row->rata,0),
                'b1'     => number_format($row->b1,0),
                'b2'     => number_format($row->b2,0),
                'b3'     => number_format($row->b3,0),
                'b4'     => number_format($row->b4,0),
                'b5'     => number_format($row->b5,0),
                'b6'     => number_format($row->b6,0),
                'b7'     => number_format($row->b7,0),
                'b8'     => number_format($row->b8,0),
                'b9'     => number_format($row->b9,0),
                'b10'    => number_format($row->b10,0),
                'b11'    => number_format($row->b11,0),
                'b12'    => number_format($row->b12,0)

                );
        }
        $col_names = array(
        'code' => 'Code',
        'product'=>'Product',
        'avg'=> 'AvgSales6bln',
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
        $name = $this->get_dp_name($dp);
        $this->cezpdf->ezTable($db_data, $col_names, 'SALDO AKHIR STOK "'.$name->nama_comp.'" '.$year, array('width'=>10,'fontSize' => 7.5));
        $this->cezpdf->ezStream();
        break;
        case 'EXCEL':
            return to_excel($query);
            break;
        }
    }
    public function print_buy_per_dp($dp=null, $year=null,$file=null)
    {
        //$year2=(int)$year-1;

        $sql ="select a.kodeprod,a.namaprod,
            format(b1,0) as b1,format(b2,0) as b2,format(b3,0) as b3,
            format(b4,0) as b4,format(b5,0) as b5,format(b6,0) as b6,
            format(b7,0) as b7,format(b8,0) as b8,format(b9,0) as b9,
            format(b10,0) as b10,format(b11,0) as b11,format(b12,0) as b12,
            format(b1+b2+b3+b4+b5+b6+b7+b8+b9+b10+b11+b12,0) as total
            from(
                select kodeprod,namaprod,
                sum(if(blndok=1,unit,0)) as b1,sum(if(blndok=2,unit,0)) as b2,
                sum(if(blndok=3,unit,0)) as b3,sum(if(blndok=4,unit,0)) as b4,
                sum(if(blndok=5,unit,0)) as b5,sum(if(blndok=6,unit,0)) as b6,
                sum(if(blndok=7,unit,0)) as b7,sum(if(blndok=8,unit,0)) as b8,
                sum(if(blndok=9,unit,0)) as b9,sum(if(blndok=10,unit,0)) as b10,
                sum(if(blndok=11,unit,0)) as b11,sum(if(blndok=12,unit,0)) as b12
                from
                (
                    select kodeprod,namaprod,sum(banyak) as unit, bulan as blndok from data".$year.".ti where nocab in (select nocab from mpm.tabcomp where naper in (".$dp.")) group by kodeprod, blndok
                )a group by kodeprod
            )a
          ";

        $query = $this->db->query($sql);
        switch(strtoupper($file))
        {
            case 'PDF':

        $this->load->library('cezpdf');
        $this->cezpdf->Cezpdf('A4','landscape');

        foreach($query->result() as $row)
        {
            $db_data[] = array(
                'code'   => $row->kodeprod,
                'product'=> $row->namaprod,
                'b1'     => $row->b1,
                'b2'     => $row->b2,
                'b3'     => $row->b3,
                'b4'     => $row->b4,
                'b5'     => $row->b5,
                'b6'     => $row->b6,
                'b7'     => $row->b7,
                'b8'     => $row->b8,
                'b9'     => $row->b9,
                'b10'    => $row->b10,
                'b11'    => $row->b11,
                'b12'    => $row->b12,
                'total'    => $row->total,
                );
        }
        $col_names = array(
        'code' => 'Code',
        'product'=>'Product',
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
        'total' => 'TOTAL',
        );
        $name = $this->get_dp_name($dp);
        $this->cezpdf->ezTable($db_data, $col_names, 'SELL IN "'.$name->nama_comp.'" '.$year, array('width'=>10,'fontSize' => 7.5));
        $this->cezpdf->ezStream();
        break;
        case 'EXCEL':
            $name = $this->get_dp_name($dp);
            $title=$name->nama_comp;
            return to_excel($query,$title);
            break;
        }
    }
    public function sales_per_dp($limit=null,$offset=null,$dp=null,$year=null,$retur=null)
    {

        $year2=(int)$year-1;
        $naper=$this->getNocab($dp);
        $id=$this->session->userdata('id');

        $sql='select 1 from mpm.sodp where id='.$id;
        $query = $this->db->query($sql);
        if($query->num_rows()>0)
        {
            //$sql='select * from mpm.sodp where id='.$id.' order by kodeprod';
            $sql='select * from (
                  select * from mpm.sodp where id='.$id.' 
                  union all
                  select "GRAND","TOTAL",
                  sum(rata),
                  sum(b1),
                  sum(b2),
                  sum(b3),
                  sum(b4),
                  sum(b5),
                  sum(b6),
                  sum(b7),
                  sum(b8),
                  sum(b9),
                  sum(b10),
                  sum(b11),
                  sum(b12),"" from mpm.sodp where id='.$id.')a order by kodeprod';
            $query = $this->db->query($sql);
            $sql2  = $sql.' limit ? offset ?';
            $this->total_query = $query->num_rows();
            $query = $this->db->query($sql2,array($limit,$offset));
        }
        else
        {
            $uv=$this->input->post('uv');
            switch($uv)
            {
                case 0:
                    $unit='banyak';
                    break;
                case 1:
                    $unit='tot1';
                    break;
            }
            $supp=$this->session->userdata('supp');
            if($supp=='000')
                {
                    $wheresupp='';
                }
                else if($supp=='001')
                {
                    $wheresupp='and kodeprod like "60%" or kodeprod like "01%" or kodeprod like "50%"';
                    //$wheresupp="and kodeprod like '01%'&&'60%'&&'50%'";
                }
                else if($supp=='005')
                {
                    $wheresupp='and kodeprod like "06%" ';
                }
                else
                {
                    $wheresupp="and kodeprod like '".substr($supp,-2)."%'";
                }
            if(!$retur)
            {
                $trans="select kodeprod,namaprod,sum(".$unit.") as unit, blndok from data".$year.".fi where nodokjdi<>'XXXXXX' and nocab in (".$naper.")".$wheresupp." group by kodeprod, blndok
                        union all
                        select kodeprod,namaprod,sum(".$unit.") as unit, blndok from data".$year.".ri where nodokjdi<>'XXXXXX' and nocab in (".$naper.")".$wheresupp." group by kodeprod, blndok
                        ";
            }
            else
            {
                $trans="select kodeprod,namaprod,sum(".$unit.") as unit, blndok from data".$year.".ri where nodokjdi<>'XXXXXX' and nocab in (".$naper.")".$wheresupp." group by kodeprod, blndok";
            }
                $sql="
                insert into mpm.sodp select a.kodeprod,a.namaprod,0 as rata,
                b1,b2,b3,b4,b5,b6,b7,b8,b9,b10,b11,b12,".$id."
                from(
                    select kodeprod,namaprod,
                    sum(if(blndok=1,unit,0)) as b1,sum(if(blndok=2,unit,0)) as b2,
                    sum(if(blndok=3,unit,0)) as b3,sum(if(blndok=4,unit,0)) as b4,
                    sum(if(blndok=5,unit,0)) as b5,sum(if(blndok=6,unit,0)) as b6,
                    sum(if(blndok=7,unit,0)) as b7,sum(if(blndok=8,unit,0)) as b8,
                    sum(if(blndok=9,unit,0)) as b9,sum(if(blndok=10,unit,0)) as b10,
                    sum(if(blndok=11,unit,0)) as b11,sum(if(blndok=12,unit,0)) as b12
                    from
                    (
                      ".$trans."
                    )a group by kodeprod
                )a"
                ;
                $query = $this->db->query($sql);
                $sql='select * from mpm.sodp where id='.$id.' order by kodeprod';
                $query = $this->db->query($sql);
                $sql2  = $sql.' limit ? offset ?';
                $query = $this->db->query($sql);
                $this->total_query = $query->num_rows();
                $query = $this->db->query($sql2,array($limit,$offset));

            /*else
            {
                $sql="insert into mpm.sodp select a.kodeprod,a.namaprod,0,
                    format(b1,0) as b1,format(b2,0) as b2,format(b3,0) as b3,
                    format(b4,0) as b4,format(b5,0) as b5,format(b6,0) as b6,
                    format(b7,0) as b7,format(b8,0) as b8,format(b9,0) as b9,
                    format(b10,0) as b10,format(b11,0) as b11,format(b12,0) as b12,".$id."
                    from(
                    select kodeprod,namaprod,
                    sum(if(blndok=1,unit,0)) as b1,sum(if(blndok=2,unit,0)) as b2,
                    sum(if(blndok=3,unit,0)) as b3,sum(if(blndok=4,unit,0)) as b4,
                    sum(if(blndok=5,unit,0)) as b5,sum(if(blndok=6,unit,0)) as b6,
                    sum(if(blndok=7,unit,0)) as b7,sum(if(blndok=8,unit,0)) as b8,
                    sum(if(blndok=9,unit,0)) as b9,sum(if(blndok=10,unit,0)) as b10,
                    sum(if(blndok=11,unit,0)) as b11,sum(if(blndok=12,unit,0)) as b12
                     from
                    (
                    select kodeprod,namaprod,sum(".$unit.") as unit, blndok from data2010.fi where nocab in (select nocab from mpm.tabcomp where naper=".$dp.") group by kodeprod, blndok
                    union all
                    select kodeprod,namaprod,sum(".$unit.") as unit, blndok from data2010.ri where nocab in (select nocab from mpm.tabcomp where naper=".$dp.") group by kodeprod, blndok
                    )a group by kodeprod
                    )a

                    ";
                $query = $this->db->query($sql);
                $sql='select * from mpm.sodp where id='.$id.' order by kodeprod';
                $query = $this->db->query($sql);
                $sql2  = $sql.' limit ? offset ?';
                $query = $this->db->query($sql);
                $this->total_query = $query->num_rows();
                $query = $this->db->query($sql2,array($limit,$offset));
            }*/

        }
        if($query->num_rows() > 0)
        {
            $this->load->library('table');
            $this->table->set_empty('0');

            $this->table->set_template($this->tmpl);
            $name = $this->get_dp_name($dp);
            $this->table->set_caption('SELL OUT '.$name->nama_comp);

            $this->table->set_heading('CODE', 'PRODUCT', 'JANUARY','FEBRUARY','MARCH','APRIL','MAY','JUNE','JULY','AUGUST','SEPTEMBER','OCTOBER','NOVEMBER','DECEMBER');
            foreach ($query->result() as $value)
            {
                $this->table->add_row(
                        $value->kodeprod
                        ,$value->namaprod
                        //,'<div div style="text-align:right">' . $value->rata . '</div>'
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
    public function buy_per_dp($limit=null,$offset=null,$dp=null,$year=null,$file=null)
    {
        $year2=(int)$year-1;
        $naper=$this->getNocab($dp);
        $id=$this->session->userdata('id');

        $sql='select * from mpm.sidp where id='.$id.' order by kodeprod';
        $query = $this->db->query($sql);
        if($query->num_rows()>0)
        {
            $sql2  = $sql.' limit ? offset ?';
            $this->total_query = $query->num_rows();
            $query = $this->db->query($sql2,array($limit,$offset));
        }
        else
        {
            $sql="
                insert into mpm.sidp select a.kodeprod,a.namaprod,
                format(b1,0) as b1,format(b2,0) as b2,format(b3,0) as b3,
                format(b4,0) as b4,format(b5,0) as b5,format(b6,0) as b6,
                format(b7,0) as b7,format(b8,0) as b8,format(b9,0) as b9,
                format(b10,0) as b10,format(b11,0) as b11,format(b12,0) as b12,
                format(b1+b2+b3+b4+b5+b6+b7+b8+b9+b10+b11+b12,0) as total,".$id."
                from(
                    select kodeprod,namaprod,
                    sum(if(blndok=1,unit,0)) as b1,sum(if(blndok=2,unit,0)) as b2,
                    sum(if(blndok=3,unit,0)) as b3,sum(if(blndok=4,unit,0)) as b4,
                    sum(if(blndok=5,unit,0)) as b5,sum(if(blndok=6,unit,0)) as b6,
                    sum(if(blndok=7,unit,0)) as b7,sum(if(blndok=8,unit,0)) as b8,
                    sum(if(blndok=9,unit,0)) as b9,sum(if(blndok=10,unit,0)) as b10,
                    sum(if(blndok=11,unit,0)) as b11,sum(if(blndok=12,unit,0)) as b12
                    from
                    (
                        select kodeprod,namaprod,sum(banyak) as unit, bulan as blndok from data".$year.".ti where nocab in (".$naper.") group by kodeprod, blndok
                    )a group by kodeprod
                )a";

                $query = $this->db->query($sql);
                $sql='select * from mpm.sidp where id='.$id.' order by kodeprod';
                $query = $this->db->query($sql);
                $sql2  = $sql.' limit ? offset ?';
                $query = $this->db->query($sql);
                $this->total_query = $query->num_rows();
                $query = $this->db->query($sql2,array($limit,$offset));
            /*if($year!='2010')
            {
                $sql="
                insert into mpm.sidp select a.kodeprod,a.namaprod,format(b.rata,0) as rata,
                format(b1,0) as b1,format(b2,0) as b2,format(b3,0) as b3,
                format(b4,0) as b4,format(b5,0) as b5,format(b6,0) as b6,
                format(b7,0) as b7,format(b8,0) as b8,format(b9,0) as b9,
                format(b10,0) as b10,format(b11,0) as b11,format(b12,0) as b12,".$id."
                from(
                    select kodeprod,namaprod,
                    sum(if(blndok=1,unit,0)) as b1,sum(if(blndok=2,unit,0)) as b2,
                    sum(if(blndok=3,unit,0)) as b3,sum(if(blndok=4,unit,0)) as b4,
                    sum(if(blndok=5,unit,0)) as b5,sum(if(blndok=6,unit,0)) as b6,
                    sum(if(blndok=7,unit,0)) as b7,sum(if(blndok=8,unit,0)) as b8,
                    sum(if(blndok=9,unit,0)) as b9,sum(if(blndok=10,unit,0)) as b10,
                    sum(if(blndok=11,unit,0)) as b11,sum(if(blndok=12,unit,0)) as b12
                    from
                    (
                        select kodeprod,namaprod,sum(banyak) as unit, bulan as blndok from data".$year.".ti where nocab in (".$naper.") group by kodeprod, blndok
                    )a group by kodeprod
                )a
                left join
                (
                    select kodeprod,sum(unit)/12 as rata
                    from
                    (
                        select kodeprod,sum(banyak) as unit from data".$year2.".ti where nocab in (".$naper.") group by kodeprod
                        union all
                        select kodeprod,sum(banyak) as unit from data".$year2.".ti where nocab in (".$naper.") group by kodeprod
                    )a group by kodeprod
                 )b using(kodeprod) group by kodeprod";
                $query = $this->db->query($sql);
                $sql='select * from mpm.sidp where id='.$id.' order by kodeprod';
                $query = $this->db->query($sql);
                $sql2  = $sql.' limit ? offset ?';
                $query = $this->db->query($sql);
                $this->total_query = $query->num_rows();
                $query = $this->db->query($sql2,array($limit,$offset));
            }

            else
            {
                $sql="insert into mpm.sidp select a.kodeprod,a.namaprod,'----' as rata,
                    format(b1,0) as b1,format(b2,0) as b2,format(b3,0) as b3,
                    format(b4,0) as b4,format(b5,0) as b5,format(b6,0) as b6,
                    format(b7,0) as b7,format(b8,0) as b8,format(b9,0) as b9,
                    format(b10,0) as b10,format(b11,0) as b11,format(b12,0) as b12,".$id."
                    from(
                    select kodeprod,namaprod,
                    sum(if(blndok=1,unit,0)) as b1,sum(if(blndok=2,unit,0)) as b2,
                    sum(if(blndok=3,unit,0)) as b3,sum(if(blndok=4,unit,0)) as b4,
                    sum(if(blndok=5,unit,0)) as b5,sum(if(blndok=6,unit,0)) as b6,
                    sum(if(blndok=7,unit,0)) as b7,sum(if(blndok=8,unit,0)) as b8,
                    sum(if(blndok=9,unit,0)) as b9,sum(if(blndok=10,unit,0)) as b10,
                    sum(if(blndok=11,unit,0)) as b11,sum(if(blndok=12,unit,0)) as b12
                    from
                    (
                    select kodeprod,namaprod,sum(banyak) as unit, bulan as blndok from data2010.ti where nocab in (select nocab from mpm.tabcomp where naper=".$dp.") group by kodeprod, blndok
                    )a group by kodeprod
                    )a
                    ";
                $query = $this->db->query($sql);
                $sql='select * from mpm.sidp where id='.$id.' order by kodeprod';
                $query = $this->db->query($sql);
                $sql2  = $sql.' limit ? offset ?';
                $query = $this->db->query($sql);
                $this->total_query = $query->num_rows();
                $query = $this->db->query($sql2,array($limit,$offset));
            }*/

        }
        if($query->num_rows() > 0)
        {
            $this->load->library('table');
            $this->table->set_empty('0');

            $this->table->set_template($this->tmpl);
            $name = $this->get_dp_name($dp);
            $this->table->set_caption('SELL IN '.$name->nama_comp);

            $this->table->set_heading('CODE', 'PRODUCT', 'JANUARY','FEBRUARY','MARCH','APRIL','MAY','JUNE','JULY','AUGUST','SEPTEMBER','OCTOBER','NOVEMBER','DECEMBER','TOTAL');
            foreach ($query->result() as $value)
            {
                $this->table->add_row(
                        $value->kodeprod
                        ,$value->namaprod
                        ,'<div div style="text-align:right">' . $value->b1   . '</div>'
                        ,'<div div style="text-align:right">' . $value->b2   . '</div>'
                        ,'<div div style="text-align:right">' . $value->b3   . '</div>'
                        ,'<div div style="text-align:right">' . $value->b4   . '</div>'
                        ,'<div div style="text-align:right">' . $value->b5   . '</div>'
                        ,'<div div style="text-align:right">' . $value->b6   . '</div>'
                        ,'<div div style="text-align:right">' . $value->b7   . '</div>'
                        ,'<div div style="text-align:right">' . $value->b8   . '</div>'
                        ,'<div div style="text-align:right">' . $value->b9   . '</div>'
                        ,'<div div style="text-align:right">' . $value->b10  . '</div>'
                        ,'<div div style="text-align:right">' . $value->b11  . '</div>'
                        ,'<div div style="text-align:right">' . $value->b12  . '</div>'
                        ,'<div div style="text-align:right">' . $value->total . '</div>'
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
    public function print_omzet_timur($year=null,$file=null)
    {
        $id=$this->session->userdata('id');
        $supp=$this->input->post('supp');
        if($supp=='000')
        {
            $wheresupp='';
        }
        else if($supp=='001')
        {
            $wheresupp='and kodeprod like "60%" or kodeprod like "01%" or kodeprod like "50%" or kodeprod like "70%"';
            //$wheresupp="and kodeprod like '01%'&&'60%'&&'50%'";
        }
        else if($supp=='005')
        {
            $wheresupp='and kodeprod like "06%" ';
        }
        else
        {
            $wheresupp="and kodeprod like '".substr($supp,-2)."%'";
        }
        $sql='select namasupp from mpm.tabsupp where supp='.$supp;
        $query=$this->db->query($sql);
        $title=$query->row();
        $sql=" select naper,nama_comp,t1,b1,t2,b2,t3,b3,t4,b4,t5,b5,t6,b6,t7,b7,t8,b8,t9,b9,t10,b10,t11,b11,t12,b12,lastupdate from(
                select naper,b.nama_comp,
                    sum(t1) as t1,
                    round(sum(b1),0) as b1,
                    sum(t2) as t2,
                    round(sum(b2),0) as b2,
                    sum(t3) as t3,
                    round(sum(b3),0) as b3,
                    sum(t4) as t4,
                    round(sum(b4),0) as b4,
                    sum(t5) as t5,
                    round(sum(b5),0) as b5,
                    sum(t6) as t6,
                    round(sum(b6),0) as b6,
                    sum(t7) as t7,
                    round(sum(b7),0) as b7,
                    sum(t8) as t8,
                    round(sum(b8),0) as b8,
                    sum(t9) as t9,
                    round(sum(b9),0) as b9,
                    sum(t10) as t10,
                    round(sum(b10),0) as b10,
                    sum(t11) as t11,
                    round(sum(b11),0) as b11,
                    sum(t12) as t12,
                    round(sum(b12),0) as b12

                from
                    (
                    select
                        nocab,
                            sum(if(blndok = 1, unit, 0)) as b1,
                            sum(if(blndok = 2, unit, 0)) as b2,
                            sum(if(blndok = 3, unit, 0)) as b3,
                            sum(if(blndok = 4, unit, 0)) as b4,
                            sum(if(blndok = 5, unit, 0)) as b5,
                            sum(if(blndok = 6, unit, 0)) as b6,
                            sum(if(blndok = 7, unit, 0)) as b7,
                            sum(if(blndok = 8, unit, 0)) as b8,
                            sum(if(blndok = 9, unit, 0)) as b9,
                            sum(if(blndok = 10, unit, 0)) as b10,
                            sum(if(blndok = 11, unit, 0)) as b11,
                            sum(if(blndok = 12, unit, 0)) as b12,
                            sum(if(blndok = 1, trans, 0)) as t1,
                            sum(if(blndok = 2, trans, 0)) as t2,
                            sum(if(blndok = 3, trans, 0)) as t3,
                            sum(if(blndok = 4, trans, 0)) as t4,
                            sum(if(blndok = 5, trans, 0)) as t5,
                            sum(if(blndok = 6, trans, 0)) as t6,
                            sum(if(blndok = 7, trans, 0)) as t7,
                            sum(if(blndok = 8, trans, 0)) as t8,
                            sum(if(blndok = 9, trans, 0)) as t9,
                            sum(if(blndok = 10, trans, 0)) as t10,
                            sum(if(blndok = 11, trans, 0)) as t11,
                            sum(if(blndok = 12, trans, 0)) as t12
                            from
                            (
                                 select nocab, blndok, sum(tot1) as unit, count(distinct(kode_lang)) as trans
                                 from data".$year.".fi
                                 where nodokjdi <> 'XXXXXX' ".$wheresupp."
                                 group by nocab,blndok

                                 union all

                                 select nocab, blndok, sum(tot1) as unit,0 as trans
                                 from data".$year.".ri
                                 where nodokjdi <> 'XXXXXX' ".$wheresupp."
                                 group by nocab,blndok
                            ) a

                        group by nocab) a
                     inner join
                     mpm.tabcomp b USING (nocab) where wilayah=2
                group by naper
                order by naper
                )a
                left join
                 (SELECT max(a.lastupload) as lastupdate,b.naper FROM upload a
                 inner join tabcomp b on substring(a.filename,3,2) = b.nocab group by naper
                  )b using(naper) order by naper
            ";
        $query = $this->db->query($sql);
        switch(strtoupper($file))
        {
            case 'PDF' :

            $this->load->library('cezpdf');
            $this->cezpdf->Cezpdf('A3','landscape');
            $i=0;
            foreach($query->result() as $row)
            {
                $db_data[] = array(
                    'no' => $i++,
                    'agent' => $row->nama_comp,
                    't1'    => number_format($row->t1,0),
                    'b1'    => number_format($row->b1,0),
                    't2'    => number_format($row->t2,0),
                    'b2'    => number_format($row->b2,0),
                    't3'    => number_format($row->t3,0),
                    'b3'    => number_format($row->b3,0),
                    't4'    => number_format($row->t4,0),
                    'b4'    => number_format($row->b4,0),
                    't5'    => number_format($row->t5,0),
                    'b5'    => number_format($row->b5,0),
                    't6'    => number_format($row->t6,0),
                    'b6'    => number_format($row->b6,0),
                    't7'    => number_format($row->t7,0),
                    'b7'    => number_format($row->b7,0),
                    't8'    => number_format($row->t8,0),
                    'b8'    => number_format($row->b8,0),
                    't9'    => number_format($row->t9,0),
                    'b9'    => number_format($row->b9,0),
                    't10'   => number_format($row->t10,0),
                    'b10'   => number_format($row->b10,0),
                    't11'   => number_format($row->t11,0),
                    'b11'   => number_format($row->b11,0),
                    't12'   => number_format($row->t12,0),
                    'b12'   => number_format($row->b12,0),
                    'upd'   => $row->lastupdate
                    );
            }
            $col_names = array(
            'no' => 'No',
            'agent' => 'DP',
            't1' => 'T1',
            'b1' => 'January',
            't2' => 'T2',
            'b2' => 'February',
            't3' => 'T3',
            'b3' => 'March',
            't4' => 'T4',
            'b4' => 'April',
            't5' => 'T5',
            'b5' => 'May',
            't6' => 'T6',
            'b6' => 'June',
            't7' => 'T7',
            'b7' => 'July',
            't8' => 'T8',
            'b8' => 'August',
            't9' => 'T9',
            'b9' => 'September',
            't10'=> 'T10',
            'b10'=> 'October',
            't11'=> 'T11',
            'b11'=> 'November',
            't12'=> 'T12',
            'b12'=> 'December',
            'upd'=> 'lastupdate'
            );
            $this->cezpdf->ezTable($db_data, $col_names, 'OMZET DP '.$year, array('width'=>10,'fontSize' =>7));
            $this->cezpdf->ezStream();
            break;
           
            case 'EXCEL':
                return to_excel($query,'Omzet'.$year);
            break;
            case 'GRAFIK':
            {
               $year2=$year-1;
               $params = array('width' => 800, 'height' => 600, 'margin' => 15, 'backgroundColor' => '#eeeeee');
               $this->load->library('chart', $params);
               if($year2!=2009)
               {
                   $last='union all
                            select concat(bulan,thndok) bulan, sum(tot1) as omzet from data'.$year2.'.fi where nodokjdi <> "XXXXXX" '.$wheresupp.' group by bulan
                            union all
                            select concat(bulan,thndok) bulan, sum(tot1) as omzet from data'.$year2.'.ri where nodokjdi <> "XXXXXX" '.$wheresupp.' group by bulan
                        ';
                   $last2='
                        sum(if(bulan=1'.$year2.',omzet,0)) b12,
                        sum(if(bulan=2'.$year2.',omzet,0)) b22,
                        sum(if(bulan=3'.$year2.',omzet,0)) b32,
                        sum(if(bulan=4'.$year2.',omzet,0)) b42,
                        sum(if(bulan=5'.$year2.',omzet,0)) b52,
                        sum(if(bulan=6'.$year2.',omzet,0)) b62,
                        sum(if(bulan=7'.$year2.',omzet,0)) b72,
                        sum(if(bulan=8'.$year2.',omzet,0)) b82,
                        sum(if(bulan=9'.$year2.',omzet,0)) b92,
                        sum(if(bulan=10'.$year2.',omzet,0))b102,
                        sum(if(bulan=11'.$year2.',omzet,0))b112,
                        sum(if(bulan=12'.$year2.',omzet,0))b122,';

               }

               $sql='
                   select '.$last2.'
                   sum(if(bulan=1'.$year.',omzet,0)) b11,
                   sum(if(bulan=2'.$year.',omzet,0)) b21,
                   sum(if(bulan=3'.$year.',omzet,0)) b31,
                   sum(if(bulan=4'.$year.',omzet,0)) b41,
                   sum(if(bulan=5'.$year.',omzet,0)) b51,
                   sum(if(bulan=6'.$year.',omzet,0)) b61,
                   sum(if(bulan=7'.$year.',omzet,0)) b71,
                   sum(if(bulan=8'.$year.',omzet,0)) b81,
                   sum(if(bulan=9'.$year.',omzet,0)) b91,
                   sum(if(bulan=10'.$year.',omzet,0))b101,
                   sum(if(bulan=11'.$year.',omzet,0))b111,
                   sum(if(bulan=12'.$year.',omzet,0))b121 from(
                   select bulan, sum(omzet) as omzet from (
                   select concat(bulan,thndok) as bulan, sum(tot1) as omzet from data'.$year.'.fi where nodokjdi <> "XXXXXX" '.$wheresupp.' group by bulan
                   union all
                   select concat(bulan,thndok) as bulan, sum(tot1) as omzet from data'.$year.'.ri where nodokjdi <> "XXXXXX" '.$wheresupp.' group by bulan
                   '.$last.'
                    )a group by bulan order by bulan)a';

               $query=$this->db->query($sql);
               $data_last=array();
               $data_now=array();

               foreach($query->result() as $row)
               {
                   if($year2!=2009)
                   {
                        $data_last[]=$row->b12;
                        $data_last[]=$row->b22;
                        $data_last[]=$row->b32;
                        $data_last[]=$row->b42;
                        $data_last[]=$row->b52;
                        $data_last[]=$row->b62;
                        $data_last[]=$row->b72;
                        $data_last[]=$row->b82;
                        $data_last[]=$row->b92;
                        $data_last[]=$row->b102;
                        $data_last[]=$row->b112;
                        $data_last[]=$row->b122;
                   }
                   $data_now[]=$row->b11;
                   $data_now[]=$row->b21;
                   $data_now[]=$row->b31;
                   $data_now[]=$row->b41;
                   $data_now[]=$row->b51;
                   $data_now[]=$row->b61;
                   $data_now[]=$row->b71;
                   $data_now[]=$row->b81;
                   $data_now[]=$row->b91;
                   $data_now[]=$row->b101;
                   $data_now[]=$row->b111;
                   $data_now[]=$row->b121;
               }


               /*if($year2!=2009)
               {
                   $sql='select bulan, sum(omzet) as omzet from (
                   select bulan,sum(tot1) as omzet from data'.$year2.'.fi where nodokjdi <> "XXXXXX" '.$wheresupp.' group by bulan
                   union all
                   select bulan,sum(tot1) as omzet from data'.$year2.'.ri where nodokjdi <> "XXXXXX" '.$wheresupp.' group by bulan
                   )a group by bulan order by bulan';
                    $query=$this->db->query($sql);

                    foreach($query->result() as $row)
                    {
                       $data_last[]=$row->omzet;
                    }
                    $this->chart->addSeries($data_last,'line',$year2, LARGE_SOLID,'#00ff00', '#00ff00');
               }*/
               //$data_last = array(43,163,56,21,0,22,0,5,73,152,294);
               //$data_now = array($isi);
               $Labels = array('Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec');
               /*$Labels =array();
               if($year2!=2009)
               {
                    $Labels[]='Jan'.$year2;
                    $Labels[]='Feb'.$year2;
                    $Labels[]='Mar'.$year2;
                    $Labels[]='Apr'.$year2;
                    $Labels[]='May'.$year2;
                    $Labels[]='Jun'.$year2;
                    $Labels[]='Jul'.$year2;
                    $Labels[]='Aug'.$year2;
                    $Labels[]='Sep'.$year2;
                    $Labels[]='Oct'.$year2;
                    $Labels[]='Nov'.$year2;
                    $Labels[]='Dec'.$year2;
               }
               $Labels[]='Jan'.$year;
               $Labels[]='Feb'.$year;
               $Labels[]='Mar'.$year;
               $Labels[]='Apr'.$year;
               $Labels[]='May'.$year;
               $Labels[]='Jun'.$year;
               $Labels[]='Jul'.$year;
               $Labels[]='Aug'.$year;
               $Labels[]='Sep'.$year;
               $Labels[]='Oct'.$year;
               $Labels[]='Nov'.$year;
               $Labels[]='Dec'.$year;
               foreach($query->result() as $row)
               {
                   $Labels[]=$row->bulan;
               }*/
               if($supp=='000')
               {
                    $this->chart->setTitle("Omzet All Supplier","#000000",2);
               }
               else
               {
                   $this->chart->setTitle("Omzet ".$title->namasupp,"#000000",2);
               }
               $this->chart->setLegend(SOLID, "#444444", "#ffffff", 2);
               $this->chart->setPlotArea(SOLID,"#444444", '#dddddd');
               $this->chart->setFormat(0,',','.');

               $this->chart->addSeries($data_now,'bar',$year, LARGE_SOLID,'#F6B442', '#F6B442');
               $this->chart->addSeries($data_last,'line',$year2,LARGE_SOLID,'#000', '#000');


               $this->chart->setXAxis('#000000', SOLID, 1, "Month");
               $this->chart->setYAxis('#000000', SOLID, 2, "Value");


               $this->chart->setLabels($Labels, '#000000', 1, HORIZONTAL);
               $this->chart->setGrid("#bbbbbb", DASHED, "#bbbbbb", DOTTED);
               $this->chart->plot('assets/images/omzet.png');
               $data['title']='omzet.png';
               $this->load->view('graph',$data);
            }break;
        }

    }
    public function print_omzet_barat($year=null,$file=null)
    {
        $id=$this->session->userdata('id');
        $supp=$this->input->post('supp');
        if($supp=='000')
        {
            $wheresupp='';
        }
        else if($supp=='001')
        {
            $wheresupp='and kodeprod like "60%" or kodeprod like "01%" or kodeprod like "50%" or kodeprod like "70%"';
            //$wheresupp="and kodeprod like '01%'&&'60%'&&'50%'";
        }
        else if($supp=='005')
        {
            $wheresupp='and kodeprod like "06%" ';
        }
        else
        {
            $wheresupp="and kodeprod like '".substr($supp,-2)."%'";
        }
        $sql='select namasupp from mpm.tabsupp where supp='.$supp;
        $query=$this->db->query($sql);
        $title=$query->row();
        $sql=" select naper,nama_comp,t1,b1,t2,b2,t3,b3,t4,b4,t5,b5,t6,b6,t7,b7,t8,b8,t9,b9,t10,b10,t11,b11,t12,b12,lastupdate from(
                select naper,b.nama_comp,
                    sum(t1) as t1,
                    round(sum(b1),0) as b1,
                    sum(t2) as t2,
                    round(sum(b2),0) as b2,
                    sum(t3) as t3,
                    round(sum(b3),0) as b3,
                    sum(t4) as t4,
                    round(sum(b4),0) as b4,
                    sum(t5) as t5,
                    round(sum(b5),0) as b5,
                    sum(t6) as t6,
                    round(sum(b6),0) as b6,
                    sum(t7) as t7,
                    round(sum(b7),0) as b7,
                    sum(t8) as t8,
                    round(sum(b8),0) as b8,
                    sum(t9) as t9,
                    round(sum(b9),0) as b9,
                    sum(t10) as t10,
                    round(sum(b10),0) as b10,
                    sum(t11) as t11,
                    round(sum(b11),0) as b11,
                    sum(t12) as t12,
                    round(sum(b12),0) as b12

                from
                    (
                    select
                        nocab,
                            sum(if(blndok = 1, unit, 0)) as b1,
                            sum(if(blndok = 2, unit, 0)) as b2,
                            sum(if(blndok = 3, unit, 0)) as b3,
                            sum(if(blndok = 4, unit, 0)) as b4,
                            sum(if(blndok = 5, unit, 0)) as b5,
                            sum(if(blndok = 6, unit, 0)) as b6,
                            sum(if(blndok = 7, unit, 0)) as b7,
                            sum(if(blndok = 8, unit, 0)) as b8,
                            sum(if(blndok = 9, unit, 0)) as b9,
                            sum(if(blndok = 10, unit, 0)) as b10,
                            sum(if(blndok = 11, unit, 0)) as b11,
                            sum(if(blndok = 12, unit, 0)) as b12,
                            sum(if(blndok = 1, trans, 0)) as t1,
                            sum(if(blndok = 2, trans, 0)) as t2,
                            sum(if(blndok = 3, trans, 0)) as t3,
                            sum(if(blndok = 4, trans, 0)) as t4,
                            sum(if(blndok = 5, trans, 0)) as t5,
                            sum(if(blndok = 6, trans, 0)) as t6,
                            sum(if(blndok = 7, trans, 0)) as t7,
                            sum(if(blndok = 8, trans, 0)) as t8,
                            sum(if(blndok = 9, trans, 0)) as t9,
                            sum(if(blndok = 10, trans, 0)) as t10,
                            sum(if(blndok = 11, trans, 0)) as t11,
                            sum(if(blndok = 12, trans, 0)) as t12
                            from
                            (
                                 select nocab, blndok, sum(tot1) as unit, count(distinct(kode_lang)) as trans
                                 from data".$year.".fi
                                 where nodokjdi <> 'XXXXXX' ".$wheresupp."
                                 group by nocab,blndok

                                 union all

                                 select nocab, blndok, sum(tot1) as unit,0 as trans
                                 from data".$year.".ri
                                 where nodokjdi <> 'XXXXXX' ".$wheresupp."
                                 group by nocab,blndok
                            ) a

                        group by nocab) a
                     inner join
                     mpm.tabcomp b USING (nocab) where wilayah=1
                group by naper
                order by naper
                )a
                left join
                 (SELECT max(a.lastupload) as lastupdate,b.naper FROM upload a
                 inner join tabcomp b on substring(a.filename,3,2) = b.nocab group by naper
                  )b using(naper) order by naper
            ";
        $query = $this->db->query($sql);
        switch(strtoupper($file))
        {
            case 'PDF' :

            $this->load->library('cezpdf');
            $this->cezpdf->Cezpdf('A3','landscape');
            $i=0;
            foreach($query->result() as $row)
            {
                $db_data[] = array(
                    'no' => $i++,
                    'agent' => $row->nama_comp,
                    't1'    => number_format($row->t1,0),
                    'b1'    => number_format($row->b1,0),
                    't2'    => number_format($row->t2,0),
                    'b2'    => number_format($row->b2,0),
                    't3'    => number_format($row->t3,0),
                    'b3'    => number_format($row->b3,0),
                    't4'    => number_format($row->t4,0),
                    'b4'    => number_format($row->b4,0),
                    't5'    => number_format($row->t5,0),
                    'b5'    => number_format($row->b5,0),
                    't6'    => number_format($row->t6,0),
                    'b6'    => number_format($row->b6,0),
                    't7'    => number_format($row->t7,0),
                    'b7'    => number_format($row->b7,0),
                    't8'    => number_format($row->t8,0),
                    'b8'    => number_format($row->b8,0),
                    't9'    => number_format($row->t9,0),
                    'b9'    => number_format($row->b9,0),
                    't10'   => number_format($row->t10,0),
                    'b10'   => number_format($row->b10,0),
                    't11'   => number_format($row->t11,0),
                    'b11'   => number_format($row->b11,0),
                    't12'   => number_format($row->t12,0),
                    'b12'   => number_format($row->b12,0),
                    'upd'   => $row->lastupdate
                    );
            }
            $col_names = array(
            'no' => 'No',
            'agent' => 'DP',
            't1' => 'T1',
            'b1' => 'January',
            't2' => 'T2',
            'b2' => 'February',
            't3' => 'T3',
            'b3' => 'March',
            't4' => 'T4',
            'b4' => 'April',
            't5' => 'T5',
            'b5' => 'May',
            't6' => 'T6',
            'b6' => 'June',
            't7' => 'T7',
            'b7' => 'July',
            't8' => 'T8',
            'b8' => 'August',
            't9' => 'T9',
            'b9' => 'September',
            't10'=> 'T10',
            'b10'=> 'October',
            't11'=> 'T11',
            'b11'=> 'November',
            't12'=> 'T12',
            'b12'=> 'December',
            'upd'=> 'lastupdate'
            );
            $this->cezpdf->ezTable($db_data, $col_names, 'OMZET DP '.$year, array('width'=>10,'fontSize' =>7));
            $this->cezpdf->ezStream();
            break;
           
            case 'EXCEL':
                return to_excel($query,'Omzet'.$year);
            break;
            case 'GRAFIK':
            {
               $year2=$year-1;
               $params = array('width' => 800, 'height' => 600, 'margin' => 15, 'backgroundColor' => '#eeeeee');
               $this->load->library('chart', $params);
               if($year2!=2009)
               {
                   $last='union all
                            select concat(bulan,thndok) bulan, sum(tot1) as omzet from data'.$year2.'.fi where nodokjdi <> "XXXXXX" '.$wheresupp.' group by bulan
                            union all
                            select concat(bulan,thndok) bulan, sum(tot1) as omzet from data'.$year2.'.ri where nodokjdi <> "XXXXXX" '.$wheresupp.' group by bulan
                        ';
                   $last2='
                        sum(if(bulan=1'.$year2.',omzet,0)) b12,
                        sum(if(bulan=2'.$year2.',omzet,0)) b22,
                        sum(if(bulan=3'.$year2.',omzet,0)) b32,
                        sum(if(bulan=4'.$year2.',omzet,0)) b42,
                        sum(if(bulan=5'.$year2.',omzet,0)) b52,
                        sum(if(bulan=6'.$year2.',omzet,0)) b62,
                        sum(if(bulan=7'.$year2.',omzet,0)) b72,
                        sum(if(bulan=8'.$year2.',omzet,0)) b82,
                        sum(if(bulan=9'.$year2.',omzet,0)) b92,
                        sum(if(bulan=10'.$year2.',omzet,0))b102,
                        sum(if(bulan=11'.$year2.',omzet,0))b112,
                        sum(if(bulan=12'.$year2.',omzet,0))b122,';

               }

               $sql='
                   select '.$last2.'
                   sum(if(bulan=1'.$year.',omzet,0)) b11,
                   sum(if(bulan=2'.$year.',omzet,0)) b21,
                   sum(if(bulan=3'.$year.',omzet,0)) b31,
                   sum(if(bulan=4'.$year.',omzet,0)) b41,
                   sum(if(bulan=5'.$year.',omzet,0)) b51,
                   sum(if(bulan=6'.$year.',omzet,0)) b61,
                   sum(if(bulan=7'.$year.',omzet,0)) b71,
                   sum(if(bulan=8'.$year.',omzet,0)) b81,
                   sum(if(bulan=9'.$year.',omzet,0)) b91,
                   sum(if(bulan=10'.$year.',omzet,0))b101,
                   sum(if(bulan=11'.$year.',omzet,0))b111,
                   sum(if(bulan=12'.$year.',omzet,0))b121 from(
                   select bulan, sum(omzet) as omzet from (
                   select concat(bulan,thndok) as bulan, sum(tot1) as omzet from data'.$year.'.fi where nodokjdi <> "XXXXXX" '.$wheresupp.' group by bulan
                   union all
                   select concat(bulan,thndok) as bulan, sum(tot1) as omzet from data'.$year.'.ri where nodokjdi <> "XXXXXX" '.$wheresupp.' group by bulan
                   '.$last.'
                    )a group by bulan order by bulan)a';

               $query=$this->db->query($sql);
               $data_last=array();
               $data_now=array();

               foreach($query->result() as $row)
               {
                   if($year2!=2009)
                   {
                        $data_last[]=$row->b12;
                        $data_last[]=$row->b22;
                        $data_last[]=$row->b32;
                        $data_last[]=$row->b42;
                        $data_last[]=$row->b52;
                        $data_last[]=$row->b62;
                        $data_last[]=$row->b72;
                        $data_last[]=$row->b82;
                        $data_last[]=$row->b92;
                        $data_last[]=$row->b102;
                        $data_last[]=$row->b112;
                        $data_last[]=$row->b122;
                   }
                   $data_now[]=$row->b11;
                   $data_now[]=$row->b21;
                   $data_now[]=$row->b31;
                   $data_now[]=$row->b41;
                   $data_now[]=$row->b51;
                   $data_now[]=$row->b61;
                   $data_now[]=$row->b71;
                   $data_now[]=$row->b81;
                   $data_now[]=$row->b91;
                   $data_now[]=$row->b101;
                   $data_now[]=$row->b111;
                   $data_now[]=$row->b121;
               }


               /*if($year2!=2009)
               {
                   $sql='select bulan, sum(omzet) as omzet from (
                   select bulan,sum(tot1) as omzet from data'.$year2.'.fi where nodokjdi <> "XXXXXX" '.$wheresupp.' group by bulan
                   union all
                   select bulan,sum(tot1) as omzet from data'.$year2.'.ri where nodokjdi <> "XXXXXX" '.$wheresupp.' group by bulan
                   )a group by bulan order by bulan';
                    $query=$this->db->query($sql);

                    foreach($query->result() as $row)
                    {
                       $data_last[]=$row->omzet;
                    }
                    $this->chart->addSeries($data_last,'line',$year2, LARGE_SOLID,'#00ff00', '#00ff00');
               }*/
               //$data_last = array(43,163,56,21,0,22,0,5,73,152,294);
               //$data_now = array($isi);
               $Labels = array('Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec');
               /*$Labels =array();
               if($year2!=2009)
               {
                    $Labels[]='Jan'.$year2;
                    $Labels[]='Feb'.$year2;
                    $Labels[]='Mar'.$year2;
                    $Labels[]='Apr'.$year2;
                    $Labels[]='May'.$year2;
                    $Labels[]='Jun'.$year2;
                    $Labels[]='Jul'.$year2;
                    $Labels[]='Aug'.$year2;
                    $Labels[]='Sep'.$year2;
                    $Labels[]='Oct'.$year2;
                    $Labels[]='Nov'.$year2;
                    $Labels[]='Dec'.$year2;
               }
               $Labels[]='Jan'.$year;
               $Labels[]='Feb'.$year;
               $Labels[]='Mar'.$year;
               $Labels[]='Apr'.$year;
               $Labels[]='May'.$year;
               $Labels[]='Jun'.$year;
               $Labels[]='Jul'.$year;
               $Labels[]='Aug'.$year;
               $Labels[]='Sep'.$year;
               $Labels[]='Oct'.$year;
               $Labels[]='Nov'.$year;
               $Labels[]='Dec'.$year;
               foreach($query->result() as $row)
               {
                   $Labels[]=$row->bulan;
               }*/
               if($supp=='000')
               {
                    $this->chart->setTitle("Omzet All Supplier","#000000",2);
               }
               else
               {
                   $this->chart->setTitle("Omzet ".$title->namasupp,"#000000",2);
               }
               $this->chart->setLegend(SOLID, "#444444", "#ffffff", 2);
               $this->chart->setPlotArea(SOLID,"#444444", '#dddddd');
               $this->chart->setFormat(0,',','.');

               $this->chart->addSeries($data_now,'bar',$year, LARGE_SOLID,'#F6B442', '#F6B442');
               $this->chart->addSeries($data_last,'line',$year2,LARGE_SOLID,'#000', '#000');


               $this->chart->setXAxis('#000000', SOLID, 1, "Month");
               $this->chart->setYAxis('#000000', SOLID, 2, "Value");


               $this->chart->setLabels($Labels, '#000000', 1, HORIZONTAL);
               $this->chart->setGrid("#bbbbbb", DASHED, "#bbbbbb", DOTTED);
               $this->chart->plot('assets/images/omzet.png');
               $data['title']='omzet.png';
               $this->load->view('graph',$data);
            }break;
        }

    }
    public function print_outlet2($year=null,$kodeprod=null,$dp=null,$file=null)
    {
        
        $uv=$this->input->post('uv');
        $id=$this->session->userdata('id');
        $naper=$this->getNocab($dp);
        switch($uv)
        {
            case 0:
                $unit='banyak';
                break;
            case 1:
                $unit='tot1';
                break;
        }
        if(strlen($naper)==5)
        {
            $sql="insert into mpm.outlet select kode,outlet,address,kota,b1,b2,b3,b4,b5,b6,b7,b8,b9,b10,b11,b12,(b1+b2+b3+b4+b5+b6+b7+b8+b9+b10+b11+b12)/12 as rata,id from (
                        select kode_lang as kode, nama_lang as outlet,alamat1 as address,kode_kota as kota,
                        sum(if(blndok=1,unit,0)) as b1,sum(if(blndok=2,unit,0)) as b2, sum(if(blndok=3,unit,0)) as b3,
                        sum(if(blndok=4,unit,0)) as b4, sum(if(blndok=5,unit,0)) as b5,sum(if(blndok=6,unit,0)) as b6,
                        sum(if(blndok=7,unit,0)) as b7,sum(if(blndok=8,unit,0)) as b8, sum(if(blndok=9,unit,0)) as b9,
                        sum(if(blndok=10,unit,0)) as b10, sum(if(blndok=11,unit,0)) as b11,sum(if(blndok=12,unit,0)) as b12,".$id." as id
                        from (
                        select * from(
                        select kode_lang, blndok,sum(".$unit.") as unit from data".$year.".fi where nocab = ".substr($naper,0,2)." and kodeprod in (".$kodeprod.") group by kode_lang,blndok
                        union all
                        select kode_lang, blndok,sum(".$unit.") as unit from data".$year.".ri where nocab = ".substr($naper,0,2)." and kodeprod in (".$kodeprod.") group by kode_lang,blndok
                        )a
                        left join (select kode_lang,nama_lang,alamat1,kode_kota from data".$year.".tblang where nocab = ".substr($naper,0,2)." group by kode_lang ) b using(kode_lang)
                        )a group by kode_lang

                        union all

                        select kode_lang as kode, nama_lang as outlet,alamat1 as address,kode_kota as kota,
                        sum(if(blndok=1,unit,0)) as b1,sum(if(blndok=2,unit,0)) as b2, sum(if(blndok=3,unit,0)) as b3,
                        sum(if(blndok=4,unit,0)) as b4, sum(if(blndok=5,unit,0)) as b5,sum(if(blndok=6,unit,0)) as b6,
                        sum(if(blndok=7,unit,0)) as b7,sum(if(blndok=8,unit,0)) as b8, sum(if(blndok=9,unit,0)) as b9,
                        sum(if(blndok=10,unit,0)) as b10, sum(if(blndok=11,unit,0)) as b11,sum(if(blndok=12,unit,0)) as b12,".$id." as id
                        from (

                        select * from (
                        select kode_lang, blndok,sum(".$unit.") as unit from data".$year.".fi where nocab = ".substr($naper,3,2)." and kodeprod in (".$kodeprod.") group by kode_lang,blndok
                        union all
                        select kode_lang, blndok,sum(".$unit.") as unit from data".$year.".ri where nocab = ".substr($naper,3,2)." and kodeprod in (".$kodeprod.") group by kode_lang,blndok
                        )a
                        left join (select kode_lang,nama_lang,alamat1,kode_kota from data".$year.".tblang where nocab = ".substr($naper,3,2)." group by kode_lang) b using(kode_lang)

                        ) b group by kode_lang


                        )a
                        ORDER BY OUTLET";

                $sql2="insert into mpm.outlet select kode,outlet,address,b1,b2,b3,b4,b5,b6,b7,b8,b9,b10,b11,b12,(b1+b2+b3+b4+b5+b6+b7+b8+b9+b10+b11+b12)/12 as rata,id from (
                        select kode_lang as kode, nama_lang as outlet,alamat1 as address,
                        sum(if(blndok=1,unit,0)) as b1,sum(if(blndok=2,unit,0)) as b2,
                        sum(if(blndok=3,unit,0)) as b3,sum(if(blndok=4,unit,0)) as b4,
                        sum(if(blndok=5,unit,0)) as b5,sum(if(blndok=6,unit,0)) as b6,
                        sum(if(blndok=7,unit,0)) as b7,sum(if(blndok=8,unit,0)) as b8,
                        sum(if(blndok=9,unit,0)) as b9,sum(if(blndok=10,unit,0)) as b10,
                        sum(if(blndok=11,unit,0)) as b11,sum(if(blndok=12,unit,0)) as b12,".$id." as id
                        from
                        (
                        select * FROM(
                        select kode_lang, blndok,sum(".$unit.") as unit from data".$year.".fi where nocab = ".substr($naper,0,2)." and kodeprod in (".$kodeprod.")  group by kode_lang,blndok
                        union all
                        select kode_lang, blndok,sum(".$unit.") as unit from data".$year.".ri where nocab = ".substr($naper,0,2)." and kodeprod in (".$kodeprod.") group by kode_lang,blndok ) a
                        left join
                        (select distinct(kode_lang),nama_lang,alamat1 from  data".$year.".tblang where nocab = ".substr($naper,0,2)." ) b using(kode_lang)
                        union all
                        select *FROM(
                        select kode_lang, blndok,sum(".$unit.") as unit from data".$year.".fi where nocab = ".substr($naper,3,2)." and kodeprod in (".$kodeprod.")  group by kode_lang,blndok
                        union all
                        select kode_lang, blndok,sum(".$unit.") as unit from data".$year.".ri where nocab = ".substr($naper,3,2)." and kodeprod in (".$kodeprod.")  group by kode_lang,blndok ) a
                        left join
                        (select distinct(kode_lang),nama_lang,alamat1 from  data".$year.".tblang where nocab = ".substr($naper,3,2)." ) b using(kode_lang)
                        )a
                        group by kode_lang
                        ) a";

        }
        else
        {
            $sql="select new from mpm.tabcomp where nocab=".$naper;
            $query=$this->db->query($sql);
            if ($query->num_rows() > 0)
            {
                $row=$query->row();
            }
            if($row->new)
            {
                $sql="  insert into mpm.outlet select kode_lang as kode,nama_lang as outlet,alamat1 as address,kode_kota as kota,b1,b2,b3,b4,b5,b6,b7,b8,b9,b10,b11,b12,(b1+b2+b3+b4+b5+b6+b7+b8+b9+b10+b11+b12)/12 as rata,id from (
                        select kode_lang,
                            sum(if(blndok=1,unit,0)) as b1,sum(if(blndok=2,unit,0)) as b2,
                            sum(if(blndok=3,unit,0)) as b3,sum(if(blndok=4,unit,0)) as b4,
                            sum(if(blndok=5,unit,0)) as b5,sum(if(blndok=6,unit,0)) as b6,
                            sum(if(blndok=7,unit,0)) as b7,sum(if(blndok=8,unit,0)) as b8,
                            sum(if(blndok=9,unit,0)) as b9,sum(if(blndok=10,unit,0)) as b10,
                            sum(if(blndok=11,unit,0)) as b11,sum(if(blndok=12,unit,0)) as b12,".$id." as id
                            from
                            (
                            select concat(kode_comp,kode_lang) kode_lang, blndok,sum(".$unit.") as unit from data".$year.".fi where nocab = ".$naper." and kodeprod in (".$kodeprod.")  group by kode_lang,blndok
                            union all
                            select concat(kode_comp,kode_lang) kode_lang, blndok,sum(".$unit.") as unit from data".$year.".ri where nocab = ".$naper." and kodeprod in (".$kodeprod.") group by kode_lang,blndok ) a
                            group by kode_lang)a
                            left join
                            (select distinct concat(kode_comp,kode_lang) kode_lang,nama_lang,alamat1,kode_kota from  data".$year.".tblang where nocab = ".$naper." ) b using(kode_lang)
                         ";
            }
            else
            {
                $sql="  insert into mpm.outlet select kode_lang as kode,nama_lang as outlet,alamat1 as address,kode_kota as kota,b1,b2,b3,b4,b5,b6,b7,b8,b9,b10,b11,b12,(b1+b2+b3+b4+b5+b6+b7+b8+b9+b10+b11+b12)/12 as rata,id from (
                            select kode_lang,
                            sum(if(blndok=1,unit,0)) as b1,sum(if(blndok=2,unit,0)) as b2,
                            sum(if(blndok=3,unit,0)) as b3,sum(if(blndok=4,unit,0)) as b4,
                            sum(if(blndok=5,unit,0)) as b5,sum(if(blndok=6,unit,0)) as b6,
                            sum(if(blndok=7,unit,0)) as b7,sum(if(blndok=8,unit,0)) as b8,
                            sum(if(blndok=9,unit,0)) as b9,sum(if(blndok=10,unit,0)) as b10,
                            sum(if(blndok=11,unit,0)) as b11,sum(if(blndok=12,unit,0)) as b12,".$id." as id
                            from
                            (
                            select kode_lang, blndok,sum(".$unit.") as unit from data".$year.".fi where nocab = ".$naper." and kodeprod in (".$kodeprod.")  group by kode_lang,blndok
                            union all
                            select kode_lang, blndok,sum(".$unit.") as unit from data".$year.".ri where nocab = ".$naper." and kodeprod in (".$kodeprod.") group by kode_lang,blndok ) a
                            group by kode_lang)a
                            left join
                            (select distinct kode_lang,nama_lang,alamat1,kode_kota from  data".$year.".tblang where nocab = ".$naper." ) b using(kode_lang)
                         ";
            }
            
        }
        $query = $this->db->query($sql);
        $sql="select * from mpm.outlet where id=".$id;
        $query=$this->db->query($sql);
        
        switch(strtoupper($file))
        {
            case 'PDF' :
            {
                $this->load->library('cezpdf');
                $this->cezpdf->Cezpdf('A3','landscape');

                foreach($query->result() as $row)
                {
                    $db_data[] = array(
                        'kode'  => $row->kode,
                        'outlet' => $row->outlet,
                        'address'   => $row->address,
                        'kota'=>$row->kota,
                        'b1'    => number_format($row->b1),
                        'b2'    => number_format($row->b2),
                        'b3'    => number_format($row->b3),
                        'b4'    => number_format($row->b4),
                        'b5'    => number_format($row->b5),
                        'b6'    => number_format($row->b6),
                        'b7'    => number_format($row->b7),
                        'b8'    => number_format($row->b8),
                        'b9'    => number_format($row->b9),
                        'b10'   => number_format($row->b10),
                        'b11'   => number_format($row->b11),
                        'b12'   => number_format($row->b12),
                        'rata'  => number_format($row->rata)
                        );
                }
                $col_names = array(
                'kode' => 'Code',
                'outlet' => 'Outlet',
                'address' => 'Address',
                'kota'=>'kota',
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
                $name = $this->get_dp_name($dp);
                //$namaprod = $this->get_product_name($kodeprod);
                $title=str_replace(' ','-',$name->nama_comp);
                $this->cezpdf->ezTable($db_data, $col_names,$title, array('width'=>10,'fontSize' => 7.5));
                $this->cezpdf->ezStream();
            }break;

            case 'EXCEL':
            {
                $name = $this->get_dp_name($dp);
                $title=str_replace(' ','-',$name->nama_comp);
                return to_excel($query,$title);

            }break;
        }
            
    }
    public function print_omzet($year=null,$file=null)
    {
        $id=$this->session->userdata('id');
        $supp=$this->input->post('supp');
        if($supp=='000')
        {
            $wheresupp='';
        }
        else if($supp=='001')
        {
            $wheresupp='and kodeprod like "60%" or kodeprod like "01%" or kodeprod like "50%" or kodeprod like "70%"';
            //$wheresupp="and kodeprod like '01%'&&'60%'&&'50%'";
        }
        else if($supp=='005')
        {
            $wheresupp='and kodeprod like "06%" ';
        }
        else
        {
            $wheresupp="and kodeprod like '".substr($supp,-2)."%'";
        }
        $sql='select namasupp from mpm.tabsupp where supp='.$supp;
        $query=$this->db->query($sql);
        $title=$query->row();
        $sql=" select naper,nama_comp,t1,b1,t2,b2,t3,b3,t4,b4,t5,b5,t6,b6,t7,b7,t8,b8,t9,b9,t10,b10,t11,b11,t12,b12,lastupdate from(
                select naper,b.nama_comp,
                    sum(t1) as t1,
                    round(sum(b1),0) as b1,
                    sum(t2) as t2,
                    round(sum(b2),0) as b2,
                    sum(t3) as t3,
                    round(sum(b3),0) as b3,
                    sum(t4) as t4,
                    round(sum(b4),0) as b4,
                    sum(t5) as t5,
                    round(sum(b5),0) as b5,
                    sum(t6) as t6,
                    round(sum(b6),0) as b6,
                    sum(t7) as t7,
                    round(sum(b7),0) as b7,
                    sum(t8) as t8,
                    round(sum(b8),0) as b8,
                    sum(t9) as t9,
                    round(sum(b9),0) as b9,
                    sum(t10) as t10,
                    round(sum(b10),0) as b10,
                    sum(t11) as t11,
                    round(sum(b11),0) as b11,
                    sum(t12) as t12,
                    round(sum(b12),0) as b12

                from
                    (
                    select
                        nocab,
                            sum(if(blndok = 1, unit, 0)) as b1,
                            sum(if(blndok = 2, unit, 0)) as b2,
                            sum(if(blndok = 3, unit, 0)) as b3,
                            sum(if(blndok = 4, unit, 0)) as b4,
                            sum(if(blndok = 5, unit, 0)) as b5,
                            sum(if(blndok = 6, unit, 0)) as b6,
                            sum(if(blndok = 7, unit, 0)) as b7,
                            sum(if(blndok = 8, unit, 0)) as b8,
                            sum(if(blndok = 9, unit, 0)) as b9,
                            sum(if(blndok = 10, unit, 0)) as b10,
                            sum(if(blndok = 11, unit, 0)) as b11,
                            sum(if(blndok = 12, unit, 0)) as b12,
                            sum(if(blndok = 1, trans, 0)) as t1,
                            sum(if(blndok = 2, trans, 0)) as t2,
                            sum(if(blndok = 3, trans, 0)) as t3,
                            sum(if(blndok = 4, trans, 0)) as t4,
                            sum(if(blndok = 5, trans, 0)) as t5,
                            sum(if(blndok = 6, trans, 0)) as t6,
                            sum(if(blndok = 7, trans, 0)) as t7,
                            sum(if(blndok = 8, trans, 0)) as t8,
                            sum(if(blndok = 9, trans, 0)) as t9,
                            sum(if(blndok = 10, trans, 0)) as t10,
                            sum(if(blndok = 11, trans, 0)) as t11,
                            sum(if(blndok = 12, trans, 0)) as t12
                            from
                            (
                                 select nocab, blndok, sum(tot1) as unit, count(distinct(kode_lang)) as trans
                                 from data".$year.".fi
                                 where nodokjdi <> 'XXXXXX' ".$wheresupp."
                                 group by nocab,blndok

                                 union all

                                 select nocab, blndok, sum(tot1) as unit,0 as trans
                                 from data".$year.".ri
                                 where nodokjdi <> 'XXXXXX' ".$wheresupp."
                                 group by nocab,blndok
                            ) a

                        group by nocab) a
                     inner join
                     mpm.tabcomp b USING (nocab)
                group by naper
                order by naper
                )a
                left join
                 (SELECT max(a.lastupload) as lastupdate,b.naper FROM upload a
                 inner join tabcomp b on substring(a.filename,3,2) = b.nocab group by naper
                  )b using(naper) order by naper
            ";
        $query = $this->db->query($sql);
        switch(strtoupper($file))
        {
            case 'PDF' :

            $this->load->library('cezpdf');
            $this->cezpdf->Cezpdf('A2','landscape');
            $i=0;
            foreach($query->result() as $row)
            {
                $db_data[] = array(
                    'no' => $i++,
                    'agent' => $row->nama_comp,
                    't1'    => number_format($row->t1,0),
                    'b1'    => number_format($row->b1,0),
                    't2'    => number_format($row->t2,0),
                    'b2'    => number_format($row->b2,0),
                    't3'    => number_format($row->t3,0),
                    'b3'    => number_format($row->b3,0),
                    't4'    => number_format($row->t4,0),
                    'b4'    => number_format($row->b4,0),
                    't5'    => number_format($row->t5,0),
                    'b5'    => number_format($row->b5,0),
                    't6'    => number_format($row->t6,0),
                    'b6'    => number_format($row->b6,0),
                    't7'    => number_format($row->t7,0),
                    'b7'    => number_format($row->b7,0),
                    't8'    => number_format($row->t8,0),
                    'b8'    => number_format($row->b8,0),
                    't9'    => number_format($row->t9,0),
                    'b9'    => number_format($row->b9,0),
                    't10'   => number_format($row->t10,0),
                    'b10'   => number_format($row->b10,0),
                    't11'   => number_format($row->t11,0),
                    'b11'   => number_format($row->b11,0),
                    't12'   => number_format($row->t12,0),
                    'b12'   => number_format($row->b12,0),
                    'upd'   => $row->lastupdate
                    );
            }
            $col_names = array(
            'no' => 'No',
            'agent' => 'DP',
            't1' => 'T1',
            'b1' => 'January',
            't2' => 'T2',
            'b2' => 'February',
            't3' => 'T3',
            'b3' => 'March',
            't4' => 'T4',
            'b4' => 'April',
            't5' => 'T5',
            'b5' => 'May',
            't6' => 'T6',
            'b6' => 'June',
            't7' => 'T7',
            'b7' => 'July',
            't8' => 'T8',
            'b8' => 'August',
            't9' => 'T9',
            'b9' => 'September',
            't10'=> 'T10',
            'b10'=> 'October',
            't11'=> 'T11',
            'b11'=> 'November',
            't12'=> 'T12',
            'b12'=> 'December',
            'upd'=> 'lastupdate'
            );
            $this->cezpdf->ezTable($db_data, $col_names, 'OMZET DP '.$year, array('width'=>10,'fontSize' =>7));
            $this->cezpdf->ezStream();
            break;
           
            case 'EXCEL':
                return to_excel($query,'Omzet'.$year);
            break;
            case 'GRAFIK':
            {
               $year2=$year-1;
               $params = array('width' => 800, 'height' => 600, 'margin' => 15, 'backgroundColor' => '#eeeeee');
               $this->load->library('chart', $params);
               if($year2!=2009)
               {
                   $last='union all
                            select concat(bulan,thndok) bulan, sum(tot1) as omzet from data'.$year2.'.fi where nodokjdi <> "XXXXXX" '.$wheresupp.' group by bulan
                            union all
                            select concat(bulan,thndok) bulan, sum(tot1) as omzet from data'.$year2.'.ri where nodokjdi <> "XXXXXX" '.$wheresupp.' group by bulan
                        ';
                   $last2='
                        sum(if(bulan=1'.$year2.',omzet,0)) b12,
                        sum(if(bulan=2'.$year2.',omzet,0)) b22,
                        sum(if(bulan=3'.$year2.',omzet,0)) b32,
                        sum(if(bulan=4'.$year2.',omzet,0)) b42,
                        sum(if(bulan=5'.$year2.',omzet,0)) b52,
                        sum(if(bulan=6'.$year2.',omzet,0)) b62,
                        sum(if(bulan=7'.$year2.',omzet,0)) b72,
                        sum(if(bulan=8'.$year2.',omzet,0)) b82,
                        sum(if(bulan=9'.$year2.',omzet,0)) b92,
                        sum(if(bulan=10'.$year2.',omzet,0))b102,
                        sum(if(bulan=11'.$year2.',omzet,0))b112,
                        sum(if(bulan=12'.$year2.',omzet,0))b122,';

               }

               $sql='
                   select '.$last2.'
                   sum(if(bulan=1'.$year.',omzet,0)) b11,
                   sum(if(bulan=2'.$year.',omzet,0)) b21,
                   sum(if(bulan=3'.$year.',omzet,0)) b31,
                   sum(if(bulan=4'.$year.',omzet,0)) b41,
                   sum(if(bulan=5'.$year.',omzet,0)) b51,
                   sum(if(bulan=6'.$year.',omzet,0)) b61,
                   sum(if(bulan=7'.$year.',omzet,0)) b71,
                   sum(if(bulan=8'.$year.',omzet,0)) b81,
                   sum(if(bulan=9'.$year.',omzet,0)) b91,
                   sum(if(bulan=10'.$year.',omzet,0))b101,
                   sum(if(bulan=11'.$year.',omzet,0))b111,
                   sum(if(bulan=12'.$year.',omzet,0))b121 from(
                   select bulan, sum(omzet) as omzet from (
                   select concat(bulan,thndok) as bulan, sum(tot1) as omzet from data'.$year.'.fi where nodokjdi <> "XXXXXX" '.$wheresupp.' group by bulan
                   union all
                   select concat(bulan,thndok) as bulan, sum(tot1) as omzet from data'.$year.'.ri where nodokjdi <> "XXXXXX" '.$wheresupp.' group by bulan
                   '.$last.'
                    )a group by bulan order by bulan)a';

               $query=$this->db->query($sql);
               $data_last=array();
               $data_now=array();

               foreach($query->result() as $row)
               {
                   if($year2!=2009)
                   {
                        $data_last[]=$row->b12;
                        $data_last[]=$row->b22;
                        $data_last[]=$row->b32;
                        $data_last[]=$row->b42;
                        $data_last[]=$row->b52;
                        $data_last[]=$row->b62;
                        $data_last[]=$row->b72;
                        $data_last[]=$row->b82;
                        $data_last[]=$row->b92;
                        $data_last[]=$row->b102;
                        $data_last[]=$row->b112;
                        $data_last[]=$row->b122;
                   }
                   $data_now[]=$row->b11;
                   $data_now[]=$row->b21;
                   $data_now[]=$row->b31;
                   $data_now[]=$row->b41;
                   $data_now[]=$row->b51;
                   $data_now[]=$row->b61;
                   $data_now[]=$row->b71;
                   $data_now[]=$row->b81;
                   $data_now[]=$row->b91;
                   $data_now[]=$row->b101;
                   $data_now[]=$row->b111;
                   $data_now[]=$row->b121;
               }


               /*if($year2!=2009)
               {
                   $sql='select bulan, sum(omzet) as omzet from (
                   select bulan,sum(tot1) as omzet from data'.$year2.'.fi where nodokjdi <> "XXXXXX" '.$wheresupp.' group by bulan
                   union all
                   select bulan,sum(tot1) as omzet from data'.$year2.'.ri where nodokjdi <> "XXXXXX" '.$wheresupp.' group by bulan
                   )a group by bulan order by bulan';
                    $query=$this->db->query($sql);

                    foreach($query->result() as $row)
                    {
                       $data_last[]=$row->omzet;
                    }
                    $this->chart->addSeries($data_last,'line',$year2, LARGE_SOLID,'#00ff00', '#00ff00');
               }*/
               //$data_last = array(43,163,56,21,0,22,0,5,73,152,294);
               //$data_now = array($isi);
               $Labels = array('Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec');
               /*$Labels =array();
               if($year2!=2009)
               {
                    $Labels[]='Jan'.$year2;
                    $Labels[]='Feb'.$year2;
                    $Labels[]='Mar'.$year2;
                    $Labels[]='Apr'.$year2;
                    $Labels[]='May'.$year2;
                    $Labels[]='Jun'.$year2;
                    $Labels[]='Jul'.$year2;
                    $Labels[]='Aug'.$year2;
                    $Labels[]='Sep'.$year2;
                    $Labels[]='Oct'.$year2;
                    $Labels[]='Nov'.$year2;
                    $Labels[]='Dec'.$year2;
               }
               $Labels[]='Jan'.$year;
               $Labels[]='Feb'.$year;
               $Labels[]='Mar'.$year;
               $Labels[]='Apr'.$year;
               $Labels[]='May'.$year;
               $Labels[]='Jun'.$year;
               $Labels[]='Jul'.$year;
               $Labels[]='Aug'.$year;
               $Labels[]='Sep'.$year;
               $Labels[]='Oct'.$year;
               $Labels[]='Nov'.$year;
               $Labels[]='Dec'.$year;
               foreach($query->result() as $row)
               {
                   $Labels[]=$row->bulan;
               }*/
               if($supp=='000')
               {
                    $this->chart->setTitle("Omzet All Supplier","#000000",2);
               }
               else
               {
                   $this->chart->setTitle("Omzet ".$title->namasupp,"#000000",2);
               }
               $this->chart->setLegend(SOLID, "#444444", "#ffffff", 2);
               $this->chart->setPlotArea(SOLID,"#444444", '#dddddd');
               $this->chart->setFormat(0,',','.');

               $this->chart->addSeries($data_now,'bar',$year, LARGE_SOLID,'#F6B442', '#F6B442');
               $this->chart->addSeries($data_last,'line',$year2,LARGE_SOLID,'#000', '#000');


               $this->chart->setXAxis('#000000', SOLID, 1, "Month");
               $this->chart->setYAxis('#000000', SOLID, 2, "Value");


               $this->chart->setLabels($Labels, '#000000', 1, HORIZONTAL);
               $this->chart->setGrid("#bbbbbb", DASHED, "#bbbbbb", DOTTED);
               $this->chart->plot('assets/images/omzet.png');
               $data['title']='omzet.png';
               $this->load->view('graph',$data);
            }break;
        }

    }
    public function print_outlet($year=null,$kodeprod=null,$dp=null,$file=null)
    {
        set_time_limit(0);
        $naper=$this->getNocab($dp);
        $kode_sales=$this->input->post('sm');
        $uv=$this->input->post('uv');
            switch($uv)
            {
                case 0:
                    $unit='banyak';
                    break;
                case 1:
                    $unit='tot1';
                    break;
            }
        if(strlen($naper)==5)
        {
        $sql="select kode, outlet,address,koderayon as rayon,kota,b1,b2,b3,b4,b5,b6,b7,b8,b9,b10,b11,b12,(b1+b2+b3+b4+b5+b6+b7+b8+b9+b10+b11+b12) as total from (
              select kode_lang as kode, nama_lang as outlet,alamat1 as address,koderayon,kode_kota as kota,
              sum(if(blndok=1,unit,0)) as b1,sum(if(blndok=2,unit,0)) as b2, sum(if(blndok=3,unit,0)) as b3,
              sum(if(blndok=4,unit,0)) as b4, sum(if(blndok=5,unit,0)) as b5,sum(if(blndok=6,unit,0)) as b6,
              sum(if(blndok=7,unit,0)) as b7,sum(if(blndok=8,unit,0)) as b8, sum(if(blndok=9,unit,0)) as b9,
                        sum(if(blndok=10,unit,0)) as b10, sum(if(blndok=11,unit,0)) as b11,sum(if(blndok=12,unit,0)) as b12
                        from (
                        select * from(
                        select kode_lang, blndok,sum(".$unit.") as unit from data".$year.".fi where nocab = ".substr($naper,0,2)." and kodesales like '%".$kode_sales."%' and kodeprod in (".$kodeprod.") group by kode_lang,blndok
                        union all
                        select kode_lang, blndok,sum(".$unit.") as unit from data".$year.".ri where nocab = ".substr($naper,0,2)." and kodesales like '%".$kode_sales."%' and kodeprod in (".$kodeprod.") group by kode_lang,blndok
                        )a
                        left join (select kode_lang,nama_lang,alamat1,koderayon,kode_kota from data".$year.".tblang where nocab = ".substr($naper,0,2)."  group by kode_lang) b using(kode_lang)
                        )a group by kode_lang

                        union all

                        select kode_lang as kode, nama_lang as outlet,alamat1 as address,koderayon,kode_kota as kota,
                        sum(if(blndok=1,unit,0)) as b1,sum(if(blndok=2,unit,0)) as b2, sum(if(blndok=3,unit,0)) as b3,
                        sum(if(blndok=4,unit,0)) as b4, sum(if(blndok=5,unit,0)) as b5,sum(if(blndok=6,unit,0)) as b6,
                        sum(if(blndok=7,unit,0)) as b7,sum(if(blndok=8,unit,0)) as b8, sum(if(blndok=9,unit,0)) as b9,
                        sum(if(blndok=10,unit,0)) as b10, sum(if(blndok=11,unit,0)) as b11,sum(if(blndok=12,unit,0)) as b12
                        from (

                        select * from (
                        select kode_lang, blndok,sum(".$unit.") as unit from data".$year.".fi where nocab = ".substr($naper,3,2)." and kodesales like '%".$kode_sales."%' and kodeprod in (".$kodeprod.") group by kode_lang,blndok
                        union all
                        select kode_lang, blndok,sum(".$unit.") as unit from data".$year.".ri where nocab = ".substr($naper,3,2)." and kodesales like '%".$kode_sales."%' and kodeprod in (".$kodeprod.") group by kode_lang,blndok
                        )a
                        left join (select kode_lang,nama_lang,alamat1,koderayon,kode_kota from data".$year.".tblang where nocab = ".substr($naper,3,2)." group by kode_lang) b using(kode_lang)

                        ) b group by kode_lang


                        )a
                        ORDER BY OUTLET";

        }
        else
        {
            $sql="select new from mpm.tabcomp where nocab=".$naper;
                $query=$this->db->query($sql);
                if ($query->num_rows() > 0)
                {
                    $row=$query->row();
                }
                if($row->new)
                { 
                    $sql="select kode,nama_lang as outlet,alamat1 as address,koderayon as rayon,kota,b1,b2,b3,b4,b5,b6,b7,b8,b9,b10,b11,b12,(b1+b2+b3+b4+b5+b6+b7+b8+b9+b10+b11+b12) as total from (
                           select kode,
                           sum(if(blndok=1,unit,0)) as b1,sum(if(blndok=2,unit,0)) as b2,
                           sum(if(blndok=3,unit,0)) as b3,sum(if(blndok=4,unit,0)) as b4,
                           sum(if(blndok=5,unit,0)) as b5,sum(if(blndok=6,unit,0)) as b6,
                           sum(if(blndok=7,unit,0)) as b7,sum(if(blndok=8,unit,0)) as b8,
                           sum(if(blndok=9,unit,0)) as b9,sum(if(blndok=10,unit,0)) as b10,
                           sum(if(blndok=11,unit,0)) as b11,sum(if(blndok=12,unit,0)) as b12
                           from
                           (
                             select concat(kode_comp,kode_lang) kode, blndok,sum(".$unit.") as unit from data".$year.".fi where nocab = ".$naper." and kodesales like '%".$kode_sales."%' and kodeprod in (".$kodeprod.")  group by kode,blndok
                             union all
                             select concat(kode_comp,kode_lang) kode, blndok,sum(".$unit.") as unit from data".$year.".ri where nocab = ".$naper." and kodesales like '%".$kode_sales."%' and kodeprod in (".$kodeprod.") group by kode,blndok ) a
                             group by kode
                           )a
                           inner join
                           (select compid,nama_lang,alamat1,koderayon,kode_kota kota from data".$year.".tblang where nocab=".$naper.")b on a.kode=b.compid group by compid";
      
                }
                else
                {
                    $sql="select kode_lang as kode,nama_lang as outlet,alamat1 as address,koderayon as rayon,kota,b1,b2,b3,b4,b5,b6,b7,b8,b9,b10,b11,b12,(b1+b2+b3+b4+b5+b6+b7+b8+b9+b10+b11+b12)as total from (
                           select kode_lang,
                           sum(if(blndok=1,unit,0)) as b1,sum(if(blndok=2,unit,0)) as b2,
                           sum(if(blndok=3,unit,0)) as b3,sum(if(blndok=4,unit,0)) as b4,
                           sum(if(blndok=5,unit,0)) as b5,sum(if(blndok=6,unit,0)) as b6,
                           sum(if(blndok=7,unit,0)) as b7,sum(if(blndok=8,unit,0)) as b8,
                           sum(if(blndok=9,unit,0)) as b9,sum(if(blndok=10,unit,0)) as b10,
                           sum(if(blndok=11,unit,0)) as b11,sum(if(blndok=12,unit,0)) as b12
                           from
                           (
                             select kode_lang, blndok,sum(".$unit.") as unit from data".$year.".fi where nocab = ".$naper." and kodesales like '%".$kode_sales."%' and kodeprod in (".$kodeprod.")  group by kode_lang,blndok
                             union all
                             select kode_lang, blndok,sum(".$unit.") as unit from data".$year.".ri where nocab = ".$naper." and kodesales like '%".$kode_sales."%' and kodeprod in (".$kodeprod.") group by kode_lang,blndok ) a
                             group by kode_lang
                           )a
                           left join
                           (select kode_lang,nama_lang,alamat1,koderayon,kode_kota as kota from  data".$year.".tblang where nocab = ".$naper." ) b using(kode_lang)";
        
                }
        }
        $query = $this->db->query($sql);
        switch(strtoupper($file))
        {
            case 'PDF' :
            {
                $this->load->library('cezpdf');
                $this->cezpdf->Cezpdf('A4','landscape');

                foreach($query->result() as $row)
                {
                    $db_data[] = array(
                        'kode'  => $row->kode,
                        'outlet' => $row->outlet,
                        'address'   => $row->address,
                        'rayon'=>$row->rayon,
                        'kota'=>$row->kota,
                        'b1'    => number_format($row->b1),
                        'b2'    => number_format($row->b2),
                        'b3'    => number_format($row->b3),
                        'b4'    => number_format($row->b4),
                        'b5'    => number_format($row->b5),
                        'b6'    => number_format($row->b6),
                        'b7'    => number_format($row->b7),
                        'b8'    => number_format($row->b8),
                        'b9'    => number_format($row->b9),
                        'b10'   => number_format($row->b10),
                        'b11'   => number_format($row->b11),
                        'b12'   => number_format($row->b12),
                        'total'  => number_format($row->total)
                        );
                }
                $col_names = array(
                'kode' => 'Code',
                'outlet' => 'Outlet',
                'address' => 'Address',
                'rayon'=>'Rayon',
                'kota'=>'kota',
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
                'total'=> 'total'
                );
                $name = $this->get_dp_name($dp);
                //$namaprod = $this->get_product_name($kodeprod);
                $title=str_replace(' ','-',$name->nama_comp);
                $this->cezpdf->ezTable($db_data, $col_names,$title, array('width'=>10,'fontSize' => 7.5));
                $this->cezpdf->ezStream();
            }break;

            case 'EXCEL':
            {
                $name = $this->get_dp_name($dp);
                $title=str_replace(' ','-',$name->nama_comp);
                return to_excel($query,$title);

            }break;
        }

    }
    public function print_per_product($kodeprod=null,$year=null,$file=null)
    {
        $year2=(int)$year-1;
        //if($year!='2010')
        //{
        $uv=$this->input->post('uv');
        switch($uv)
        {
            case 0:$unit='banyak';break;
            case 1:$unit='tot1';break;
        }

        $sql= "select `naper`,`b`.`nama_comp`,

                    format(sum(`t1`), 0) as `t1`,
                    format(sum(`b1`), 0) as `b1`,
                    format(sum(`t2`), 0) as `t2`,
                    format(sum(`b2`), 0) as `b2`,
                    format(sum(`t3`), 0) as `t3`,
                    format(sum(`b3`), 0) as `b3`,
                    format(sum(`t4`), 0) as `t4`,
                    format(sum(`b4`), 0) as `b4`,
                    format(sum(`t5`), 0) as `t5`,
                    format(sum(`b5`), 0) as `b5`,
                    format(sum(`t6`), 0) as `t6`,
                    format(sum(`b6`), 0) as `b6`,
                    format(sum(`t7`), 0) as `t7`,
                    format(sum(`b7`), 0) as `b7`,
                    format(sum(`t8`), 0) as `t8`,
                    format(sum(`b8`), 0) as `b8`,
                    format(sum(`t9`), 0) as `t9`,
                    format(sum(`b9`), 0) as `b9`,
                    format(sum(`t10`), 0) as `t10`,
                    format(sum(`b10`), 0) as `b10`,
                    format(sum(`t11`), 0) as `t11`,
                    format(sum(`b11`), 0) as `b11`,
                    format(sum(`t12`), 0) as `t12`,
                    format(sum(`b12`), 0) as `b12`


                from
                    (
                    select
                        `nocab`,

                            sum(if(`blndok` = 1, `unit`, 0)) as `b1`,
                            sum(if(`blndok` = 2, `unit`, 0)) as `b2`,
                            sum(if(`blndok` = 3, `unit`, 0)) as `b3`,
                            sum(if(`blndok` = 4, `unit`, 0)) as `b4`,
                            sum(if(`blndok` = 5, `unit`, 0)) as `b5`,
                            sum(if(`blndok` = 6, `unit`, 0)) as `b6`,
                            sum(if(`blndok` = 7, `unit`, 0)) as `b7`,
                            sum(if(`blndok` = 8, `unit`, 0)) as `b8`,
                            sum(if(`blndok` = 9, `unit`, 0)) as `b9`,
                            sum(if(`blndok` = 10, `unit`, 0)) as `b10`,
                            sum(if(`blndok` = 11, `unit`, 0)) as `b11`,
                            sum(if(`blndok` = 12, `unit`, 0)) as `b12`,
                            sum(if(`blndok` = 1, `trans`, 0)) as `t1`,
                            sum(if(`blndok` = 2, `trans`, 0)) as `t2`,
                            sum(if(`blndok` = 3, `trans`, 0)) as `t3`,
                            sum(if(`blndok` = 4, `trans`, 0)) as `t4`,
                            sum(if(`blndok` = 5, `trans`, 0)) as `t5`,
                            sum(if(`blndok` = 6, `trans`, 0)) as `t6`,
                            sum(if(`blndok` = 7, `trans`, 0)) as `t7`,
                            sum(if(`blndok` = 8, `trans`, 0)) as `t8`,
                            sum(if(`blndok` = 9, `trans`, 0)) as `t9`,
                            sum(if(`blndok` = 10, `trans`, 0)) as `t10`,
                            sum(if(`blndok` = 11, `trans`, 0)) as `t11`,
                            sum(if(`blndok` = 12, `trans`, 0)) as `t12`
                            from
                            (
                                 select `nocab`, `blndok`, sum(`".$unit."`) as `unit`, count(distinct(kode_lang)) as trans
                                 from `data".$year."`.`fi`
                                 where`kodeprod` in (".$kodeprod.") and `nodokjdi` <> 'XXXXXX'
                                 group by `nocab`,`blndok`

                                 union all

                                 select `nocab`, `blndok`, sum(`".$unit."`) as `unit`,0 as trans
                                 from `data".$year."`.`ri`
                                 where `kodeprod` in (".$kodeprod.") and `nodokjdi` <> 'XXXXXX'
                                 group by `nocab`,`blndok`
                            ) `a`

                        group by `nocab`) `a`
                     inner join
                     `mpm`.`tabcomp` `b` USING (`nocab`)
                group by `naper`
                order by `naper`
            ";
        $sql2 =" select naper,b.nama_comp, format(sum(rata),0) as rata,
            format(sum(b1),0) as b1,format(sum(b2),0) as b2,format(sum(b3),0) as b3,
            format(sum(b4),0) as b4,format(sum(b5),0) as b5,format(sum(b6),0) as b6,
            format(sum(b7),0) as b7,format(sum(b8),0) as b8,format(sum(b9),0) as b9,
            format(sum(b10),0) as b10,format(sum(b11),0) as b11,format(sum(b12),0) as b12
            from(
                select nocab,b.rata,
                sum(if(blndok=1,unit,0)) as b1,sum(if(blndok=2,unit,0)) as b2,
                sum(if(blndok=3,unit,0)) as b3,sum(if(blndok=4,unit,0)) as b4,
                sum(if(blndok=5,unit,0)) as b5,sum(if(blndok=6,unit,0)) as b6,
                sum(if(blndok=7,unit,0)) as b7,sum(if(blndok=8,unit,0)) as b8,
                sum(if(blndok=9,unit,0)) as b9,sum(if(blndok=10,unit,0)) as b10,
                sum(if(blndok=11,unit,0)) as b11,sum(if(blndok=12,unit,0)) as b12
                from
                (
                    select nocab,blndok,sum(banyak) as unit from data".$year.".fi  where kodeprod in (".$kodeprod.")  and nodokjdi<>'XXXXXX' group by nocab, blndok
                    union all
                    select nocab,blndok,sum(banyak) as unit from data".$year.".ri  where kodeprod in (".$kodeprod.")  and nodokjdi<>'XXXXXX' group by nocab, blndok
                )
                a
                left join
                (
                    select nocab,sum(unit)/12 as rata from
                    (
                    select nocab,sum(banyak) as unit from data".$year2.".fi  where kodeprod in (".$kodeprod.")  and nodokjdi<>'XXXXXX' group by nocab
                    union all
                    select nocab,sum(banyak) as unit from data".$year2.".ri  where kodeprod in (".$kodeprod.")  and nodokjdi<>'XXXXXX' group by nocab
                    )
                    a group by nocab
                )
                b
                using(nocab)
                group by nocab
            )
            a
            inner join mpm.tabcomp b using(nocab)
            group by naper
            order by naper
        ";


        $query = $this->db->query($sql);
        switch(strtoupper($file))
        {
            case 'PDF':
            $this->load->library('cezpdf');
            $this->cezpdf->Cezpdf('A4','landscape');

            foreach($query->result() as $row)
            {
                $db_data[] = array(
                    'agent' => $row->nama_comp,
                    'ti'    => $row->t1,
                    'b1'    => $row->b1,
                    't2'    => $row->t2,
                    'b2'    => $row->b2,
                    't3'    => $row->t3,
                    'b3'    => $row->b3,
                    't4'    => $row->t4,
                    'b4'    => $row->b4,
                    't5'    => $row->t5,
                    'b5'    => $row->b5,
                    't6'    => $row->t6,
                    'b6'    => $row->b6,
                    't7'    => $row->t7,
                    'b7'    => $row->b7,
                    't8'    => $row->t8,
                    'b8'    => $row->b8,
                    't9'    => $row->t9,
                    'b9'    => $row->b9,
                    't10'   => $row->t10,
                    'b10'   => $row->b10,
                    't11'   => $row->t11,
                    'b11'   => $row->b11,
                    't12'   => $row->t12,
                    'b12'   => $row->b12,
                    );
            }
            $col_names = array(
            'agent' => 'Agent',
            'ti' => 'T1',
            'b1' => 'January',
            't2' => 'T2',
            'b2' => 'February',
            't3' => 'T3',
            'b3' => 'March',
            't4' => 'T4',
            'b4' => 'April',
            't5' => 'T5',
            'b5' => 'May',
            't6' => 'T6',
            'b6' => 'June',
            't7' => 'T7',
            'b7' => 'july',
            't8' => 'T8',
            'b8' => 'August',
            't9' => 'T9',
            'b9' => 'September',
            't10' => 'T10',
            'b10'=> 'October',
            't11' => 'T11',
            'b11'=> 'November',
            't12' => 'T12',
            'b12'=> 'December',
            );
            $name = $this->get_product_name($kodeprod);
            $this->cezpdf->ezTable($db_data, $col_names, 'SELL OUT "'.$name.'" '.$year, array('width'=>5,'fontSize' => 6));
            $this->cezpdf->ezStream();
            break;
            case 'EXCEL':
            {
                $title = $this->get_product_title($kodeprod);
                return to_excel($query,"Products");
            } break;
            case 'GRAFIK':
            {
               $name = $this->get_product_name($kodeprod);
               $params = array('width' => 800, 'height' => 600, 'margin' => 15, 'backgroundColor' => '#eeeeee');
               $this->load->library('chart', $params);


               if($year2!=2009)
               {
                   $last='union all
                            select concat(bulan,thndok) bulan, sum('.$unit.') as omzet from data'.$year2.'.fi where nodokjdi <> "XXXXXX" and kodeprod in ('.$kodeprod.') group by bulan
                            union all
                            select concat(bulan,thndok) bulan, sum('.$unit.') as omzet from data'.$year2.'.ri where nodokjdi <> "XXXXXX" and kodeprod in ('.$kodeprod.') group by bulan
                        ';
                   $last2='
                        sum(if(bulan=1'.$year2.',omzet,0)) b12,
                        sum(if(bulan=2'.$year2.',omzet,0)) b22,
                        sum(if(bulan=3'.$year2.',omzet,0)) b32,
                        sum(if(bulan=4'.$year2.',omzet,0)) b42,
                        sum(if(bulan=5'.$year2.',omzet,0)) b52,
                        sum(if(bulan=6'.$year2.',omzet,0)) b62,
                        sum(if(bulan=7'.$year2.',omzet,0)) b72,
                        sum(if(bulan=8'.$year2.',omzet,0)) b82,
                        sum(if(bulan=9'.$year2.',omzet,0)) b92,
                        sum(if(bulan=10'.$year2.',omzet,0))b102,
                        sum(if(bulan=11'.$year2.',omzet,0))b112,
                        sum(if(bulan=12'.$year2.',omzet,0))b122,';

               }

               $sql='
                   select '.$last2.'
                   sum(if(bulan=1'.$year.',omzet,0)) b11,
                   sum(if(bulan=2'.$year.',omzet,0)) b21,
                   sum(if(bulan=3'.$year.',omzet,0)) b31,
                   sum(if(bulan=4'.$year.',omzet,0)) b41,
                   sum(if(bulan=5'.$year.',omzet,0)) b51,
                   sum(if(bulan=6'.$year.',omzet,0)) b61,
                   sum(if(bulan=7'.$year.',omzet,0)) b71,
                   sum(if(bulan=8'.$year.',omzet,0)) b81,
                   sum(if(bulan=9'.$year.',omzet,0)) b91,
                   sum(if(bulan=10'.$year.',omzet,0))b101,
                   sum(if(bulan=11'.$year.',omzet,0))b111,
                   sum(if(bulan=12'.$year.',omzet,0))b121 from(
                   select bulan, sum(omzet) as omzet from (
                   select concat(bulan,thndok) as bulan, sum('.$unit.') as omzet from data'.$year.'.fi where nodokjdi <> "XXXXXX"  and kodeprod in ('.$kodeprod.') group by bulan
                   union all
                   select concat(bulan,thndok) as bulan, sum('.$unit.') as omzet from data'.$year.'.ri where nodokjdi <> "XXXXXX"  and kodeprod in ('.$kodeprod.')  group by bulan
                   '.$last.'
                    )a group by bulan order by bulan)a';

               $query=$this->db->query($sql);
               $data_last=array();
               $data_now=array();

               foreach($query->result() as $row)
               {
                   if($year2!=2009)
                   {
                        $data_last[]=$row->b12;
                        $data_last[]=$row->b22;
                        $data_last[]=$row->b32;
                        $data_last[]=$row->b42;
                        $data_last[]=$row->b52;
                        $data_last[]=$row->b62;
                        $data_last[]=$row->b72;
                        $data_last[]=$row->b82;
                        $data_last[]=$row->b92;
                        $data_last[]=$row->b102;
                        $data_last[]=$row->b112;
                        $data_last[]=$row->b122;
                   }
                   $data_now[]=$row->b11;
                   $data_now[]=$row->b21;
                   $data_now[]=$row->b31;
                   $data_now[]=$row->b41;
                   $data_now[]=$row->b51;
                   $data_now[]=$row->b61;
                   $data_now[]=$row->b71;
                   $data_now[]=$row->b81;
                   $data_now[]=$row->b91;
                   $data_now[]=$row->b101;
                   $data_now[]=$row->b111;
                   $data_now[]=$row->b121;
               }/*
               $sql='
                   select bulan, sum(unit) as unit from(
                    select bulan,sum('.$unit.') as unit from data'.$year.'.fi where kodeprod in ('.$kodeprod.') group by bulan
                    union all
                    select bulan,sum('.$unit.') as unit from data'.$year.'.ri where kodeprod in ('.$kodeprod.') group by bulan )a
                    group by bulan order by bulan';
               $query=$this->db->query($sql);

               $data_now=array();
               foreach($query->result() as $row)
               {
                   $data_now[]=$row->unit;
               }
               $data_last=array();
               if($year2!=2009)
               {
                    $sql='select bulan, sum(unit) as unit from(
                        select bulan,sum('.$unit.') as unit from data'.$year2.'.fi where kodeprod in ('.$kodeprod.') group by bulan
                        union all
                        select bulan,sum('.$unit.') as unit from data'.$year2.'.ri where kodeprod in ('.$kodeprod.') group by bulan )a
                        group by bulan order by bulan';
                    $query=$this->db->query($sql);

                    foreach($query->result() as $row)
                    {
                       $data_last[]=$row->unit;
                    }
                    $this->chart->addSeries($data_last,'line',$year2, LARGE_SOLID,'#00ff00', '#00ff00');
               }*/
               //$data_last = array(43,163,56,21,0,22,0,5,73,152,294);
               //$data_now = array($isi);
               $Labels = array('Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec');

               $this->chart->setTitle($name,"#000000",2);
               $this->chart->setLegend(SOLID, "#444444", "#ffffff", 2);
               $this->chart->setPlotArea(SOLID,"#444444", '#dddddd');
               $this->chart->setFormat(0,',','.');

               $this->chart->addSeries($data_now,'bar',$year, LARGE_SOLID,'#F6B442', '#F6B442');
               $this->chart->addSeries($data_last,'line',$year2,LARGE_SOLID,'#000', '#000');

               $this->chart->setXAxis('#000000', SOLID, 1, "Month");
               if($unit=='banyak')
               {
                   $this->chart->setYAxis('#000000', SOLID, 2, "Sell Out in Unit");
               }
               else
               {
                   $this->chart->setYAxis('#000000', SOLID, 2, "Sell Out in Value");
               }


               $this->chart->setLabels($Labels, '#000000', 1, HORIZONTAL);
               $this->chart->setGrid("#bbbbbb", DASHED, "#bbbbbb", DOTTED);
               $this->chart->plot('assets/images/produk.png');
               $data['title']='produk.png';
               $this->load->view('graph',$data);
            }break;
        }
    }
    public function vessel($num)
    {
        $id=$this->session->userdata('id');
        switch($num)
        {
            case 1:
                $sql='delete from mpm.soprod where id='.$id;
                $this->db->query($sql);
                ;break;
            case 2:
                $sql='delete from mpm.sodp where id='.$id;
                $this->db->query($sql);
                ;break;
            case 3:
                $sql='delete from mpm.omzet where id='.$id;
                $this->db->query($sql);
                ;break;
            case 4:
                $sql='delete from mpm.sounit where id='.$id;
                $this->db->query($sql);
                ;break;
            case 5:
                $sql='delete from mpm.outlet where id='.$id;
                $this->db->query($sql);
                ;break;
            case 6:
                $sql='delete from mpm.sidp where id='.$id;
                $this->db->query($sql);
                ;break;
            case 7:
                $sql='delete from mpm.stokdp where id='.$id;
                $this->db->query($sql);
                ;break;
            case 8:
                $sql='delete from mpm.stokprod where id='.$id;
                $this->db->query($sql);
                ;break;
            case 9:
                $sql='delete from mpm.sounit where id='.$id;
                $this->db->query($sql);
                ;break;
            case 10:
                $sql='delete from mpm.sounit where id='.$id;
                $this->db->query($sql);
                ;break;

        }
    }
    public function delete_stok_dp()
    {
        $id=$this->session->userdata('id');
        $sql='delete from mpm.stokdp where id='.$id;
        $this->db->query($sql);
    }
    public function sales_outlet2($limit=null,$offset=null,$year=null,$kodeprod=null,$dp=null)
    {
        $naper=$this->getNocab($dp);
        $id=$this->session->userdata('id');

        $sql='select * from mpm.outlet where id='.$id.' order by outlet';
        $query = $this->db->query($sql);
        if($query->num_rows()>0)
        {
            $sql2  = $sql.' limit ? offset ?';
            $this->total_query = $query->num_rows();
            $query = $this->db->query($sql2,array($limit,$offset));
        }
        else
        {
            $sql=" insert into mpm.outlet
            select outlet,alamat,b1,b2,b3,b4,b5,b6,b7,b8,b9,b10,b11,b12,
            (b1+b2+b3+b4+b5+b6+b7+b8+b9+b10+b11+b12)/12 as rata,".$id." from(
            select a.kode_lang as kode, b.nama_lang as outlet, b.alamat1 as alamat,
            sum(if(blndok=1, unit, 0)) as b1, sum(if(blndok=2, unit, 0)) as b2,
            sum(if(blndok=3, unit, 0)) as b3, sum(if(blndok=4, unit, 0)) as b4,
            sum(if(blndok=5, unit, 0)) as b5, sum(if(blndok=6, unit, 0)) as b6,
            sum(if(blndok=7, unit, 0)) as b7, sum(if(blndok=8, unit, 0)) as b8,
            sum(if(blndok=9, unit, 0)) as b9, sum(if(blndok=10, unit, 0)) as b10,
            sum(if(blndok=11, unit, 0)) as b11, sum(if(blndok=12, unit, 0)) as b12
            from
            (
            select kode_lang, blndok, sum(banyak) as unit from data".$year.".fi where nocab in (".$naper.") and kodeprod in (".$kodeprod.") group by kode_lang, blndok
            union all
            select kode_lang, blndok, sum(banyak) as unit from data".$year.".ri where nocab in (".$naper.") and kodeprod in (".$kodeprod.") group by kode_lang, blndok
            )
            a

            left join
            (select kode_lang, nama_lang, alamat1 from data".$year.".tblang where nocab in (".$naper.") ) b using(kode_lang)
            group by kode_lang order by b.nama_lang) a";

            $query = $this->db->query($sql);
            $sql='select * from mpm.outlet where id='.$id.' order by outlet';
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
            $name = $this->get_dp_name($dp);
            $namaprod=$this->get_product_name($kodeprod);
            $this->table->set_caption('SELL OUT OUTLET '.$name->nama_comp.'<br />Product : '.$namaprod);

            $this->table->set_heading('OUTLET','ADDRESS', 'JANUARY','FEBRUARY','MARCH','APRIL','MAY','JUNE','JULY','AUGUST','SEPTEMBER','OCTOBER','NOVEMBER','DECEMBER');
            foreach ($query->result() as $value)
            {
                $this->table->add_row(
                        $value->outlet
                        ,$value->alamat
                        ,'<div div style="text-align:right">' . $value->b1   . '</div>'
                        ,'<div div style="text-align:right">' . $value->b2   . '</div>'
                        ,'<div div style="text-align:right">' . $value->b3   . '</div>'
                        ,'<div div style="text-align:right">' . $value->b4   . '</div>'
                        ,'<div div style="text-align:right">' . $value->b5   . '</div>'
                        ,'<div div style="text-align:right">' . $value->b6   . '</div>'
                        ,'<div div style="text-align:right">' . $value->b7   . '</div>'
                        ,'<div div style="text-align:right">' . $value->b8   . '</div>'
                        ,'<div div style="text-align:right">' . $value->b9   . '</div>'
                        ,'<div div style="text-align:right">' . $value->b10  . '</div>'
                        ,'<div div style="text-align:right">' . $value->b11  . '</div>'
                        ,'<div div style="text-align:right">' . $value->b12  . '</div>'
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
    public function sales_outlet($limit=0,$offset=0,$year=null,$kodeprod=null,$dp=null,$retur=false)
    {
        $naper=$this->getNocab($dp);
        $id=$this->session->userdata('id');
        
        //echo $dp;
        $sql='select * from mpm.outlet where id='.$id;
              
        $query = $this->db->query($sql);
        if($query->num_rows()>0)
        {
           
            $sql='select * from(select * from mpm.outlet where id='.$id.' order by outlet) a
              union all
              select "-","TOTAL","-","-","-",
                  sum(b1),
                  sum(b2),
                  sum(b3),
                  sum(b4),
                  sum(b5),
                  sum(b6),
                  sum(b7),
                  sum(b8),
                  sum(b9),
                  sum(b10),
                  sum(b11),
                  sum(b12),sum(rata),"-" from mpm.outlet where id='.$id;
            
            $sql2  = $sql.' limit ? offset ?';
            $this->total_query = $query->num_rows();
            $query = $this->db->query($sql2,array($limit,$offset));
        }
        else
        {
            $uv=$this->input->post('uv');
            $kode_sales=$this->input->post('sm');
            switch($uv)
            {
                case 0:
                    $unit='banyak';
                    break;
                case 1:
                    $unit='tot1';
                    break;
            }
            if(strlen($naper)==5)
            {

                $sql="insert into mpm.outlet select kode,outlet,address,koderayon,kota,b1,b2,b3,b4,b5,b6,b7,b8,b9,b10,b11,b12,(b1+b2+b3+b4+b5+b6+b7+b8+b9+b10+b11+b12)/12 as rata,id from (

                        select kode_lang as kode, nama_lang as outlet,alamat1 as address,koderayon,kode_kota as kota,
                        sum(if(blndok=1,unit,0)) as b1,sum(if(blndok=2,unit,0)) as b2, sum(if(blndok=3,unit,0)) as b3,
                        sum(if(blndok=4,unit,0)) as b4, sum(if(blndok=5,unit,0)) as b5,sum(if(blndok=6,unit,0)) as b6,
                        sum(if(blndok=7,unit,0)) as b7,sum(if(blndok=8,unit,0)) as b8, sum(if(blndok=9,unit,0)) as b9,
                        sum(if(blndok=10,unit,0)) as b10, sum(if(blndok=11,unit,0)) as b11,sum(if(blndok=12,unit,0)) as b12,".$id." as id
                        from (
                        select * from(
                        select kode_lang, blndok,sum(".$unit.") as unit from data".$year.".fi where nocab = ".substr($naper,0,2)." and concat(kodesales) like '%".$kode_sales."%' and kodeprod in (".$kodeprod.") group by kode_lang,blndok
                        union all
                        select kode_lang, blndok,sum(".$unit.") as unit from data".$year.".ri where nocab = ".substr($naper,0,2)." and concat(kodesales) like '%".$kode_sales."%' and kodeprod in (".$kodeprod.") group by kode_lang,blndok
                        )a
                        left join (select kode_lang,koderayon,nama_lang,alamat1,kode_kota from data".$year.".tblang where nocab = ".substr($naper,0,2)." group by kode_lang ) b using(kode_lang)
                        )a group by kode_lang

                        union all

                        select kode_lang as kode, nama_lang as outlet,alamat1 as address,koderayon,kode_kota as kota,
                        sum(if(blndok=1,unit,0)) as b1,sum(if(blndok=2,unit,0)) as b2, sum(if(blndok=3,unit,0)) as b3,
                        sum(if(blndok=4,unit,0)) as b4, sum(if(blndok=5,unit,0)) as b5,sum(if(blndok=6,unit,0)) as b6,
                        sum(if(blndok=7,unit,0)) as b7,sum(if(blndok=8,unit,0)) as b8, sum(if(blndok=9,unit,0)) as b9,
                        sum(if(blndok=10,unit,0)) as b10, sum(if(blndok=11,unit,0)) as b11,sum(if(blndok=12,unit,0)) as b12,".$id." as id
                        from (

                        select * from (
                        select kode_lang, blndok,sum(".$unit.") as unit from data".$year.".fi where nocab = ".substr($naper,3,2)." and concat(kodesales) like '%".$kode_sales."%' and kodeprod in (".$kodeprod.") group by kode_lang,blndok
                        union all
                        select kode_lang, blndok,sum(".$unit.") as unit from data".$year.".ri where nocab = ".substr($naper,3,2)." and concat(kodesales) like '%".$kode_sales."%' and kodeprod in (".$kodeprod.") group by kode_lang,blndok
                        )a
                        left join (select kode_lang,koderayon,nama_lang,alamat1,kode_kota from data".$year.".tblang where nocab = ".substr($naper,3,2)." group by kode_lang) b using(kode_lang)

                        ) b group by kode_lang


                        )a
                        ORDER BY OUTLET";

                $sql2="insert into mpm.outlet select kode,outlet,address,b1,b2,b3,b4,b5,b6,b7,b8,b9,b10,b11,b12,(b1+b2+b3+b4+b5+b6+b7+b8+b9+b10+b11+b12)/12 as rata,id from (
                        select kode_lang as kode, nama_lang as outlet,alamat1 as address,
                        sum(if(blndok=1,unit,0)) as b1,sum(if(blndok=2,unit,0)) as b2,
                        sum(if(blndok=3,unit,0)) as b3,sum(if(blndok=4,unit,0)) as b4,
                        sum(if(blndok=5,unit,0)) as b5,sum(if(blndok=6,unit,0)) as b6,
                        sum(if(blndok=7,unit,0)) as b7,sum(if(blndok=8,unit,0)) as b8,
                        sum(if(blndok=9,unit,0)) as b9,sum(if(blndok=10,unit,0)) as b10,
                        sum(if(blndok=11,unit,0)) as b11,sum(if(blndok=12,unit,0)) as b12,".$id." as id
                        from
                        (
                        select * FROM(
                        select kode_lang, blndok,sum(".$unit.") as unit from data".$year.".fi where nocab = ".substr($naper,0,2)." and kodeprod in (".$kodeprod.")  group by kode_lang,blndok
                        union all
                        select kode_lang, blndok,sum(".$unit.") as unit from data".$year.".ri where nocab = ".substr($naper,0,2)." and kodeprod in (".$kodeprod.") group by kode_lang,blndok ) a
                        left join
                        (select distinct(kode_lang),nama_lang,alamat1 from  data".$year.".tblang where nocab = ".substr($naper,0,2)." ) b using(kode_lang)
                        union all
                        select *FROM(
                        select kode_lang, blndok,sum(".$unit.") as unit from data".$year.".fi where nocab = ".substr($naper,3,2)." and kodeprod in (".$kodeprod.")  group by kode_lang,blndok
                        union all
                        select kode_lang, blndok,sum(".$unit.") as unit from data".$year.".ri where nocab = ".substr($naper,3,2)." and kodeprod in (".$kodeprod.")  group by kode_lang,blndok ) a
                        left join
                        (select distinct(kode_lang),nama_lang,alamat1 from  data".$year.".tblang where nocab = ".substr($naper,3,2)." ) b using(kode_lang)
                        )a
                        group by kode_lang
                        ) a";

            }
            else
            {
                $sql="select new from mpm.tabcomp where nocab=".$naper;
                $query=$this->db->query($sql);
                if ($query->num_rows() > 0)
                {
                    $row=$query->row();
                }
                if($row->new)
                {
                    $sql="  insert into mpm.outlet select kode,nama_lang as outlet,alamat1 as address,koderayon,kode_kota as kota,b1,b2,b3,b4,b5,b6,b7,b8,b9,b10,b11,b12,(b1+b2+b3+b4+b5+b6+b7+b8+b9+b10+b11+b12)/12 as rata,".$id." from (
                           select kode,
                           sum(if(blndok=1,unit,0)) as b1,sum(if(blndok=2,unit,0)) as b2,
                           sum(if(blndok=3,unit,0)) as b3,sum(if(blndok=4,unit,0)) as b4,
                           sum(if(blndok=5,unit,0)) as b5,sum(if(blndok=6,unit,0)) as b6,
                           sum(if(blndok=7,unit,0)) as b7,sum(if(blndok=8,unit,0)) as b8,
                           sum(if(blndok=9,unit,0)) as b9,sum(if(blndok=10,unit,0)) as b10,
                           sum(if(blndok=11,unit,0)) as b11,sum(if(blndok=12,unit,0)) as b12
                           from
                           (
                             select concat(kode_comp,kode_lang) kode, blndok,sum(".$unit.") as unit from data".$year.".fi where nocab = ".$naper." and concat(kodesales) like '%".$kode_sales."%' and kodeprod in (".$kodeprod.")  group by kode,blndok
                             union all
                             select concat(kode_comp,kode_lang) kode, blndok,sum(".$unit.") as unit from data".$year.".ri where nocab = ".$naper." and concat(kodesales) like '%".$kode_sales."%' and kodeprod in (".$kodeprod.") group by kode,blndok ) a
                             group by kode
                           )a
                           inner join
                           (select compid,nama_lang,koderayon,alamat1,kode_kota from data".$year.".tblang where nocab=".$naper.")b on a.kode=b.compid group by compid";
      
                }
                else
                {
                    $sql="  insert into mpm.outlet select kode_lang as kode,nama_lang as outlet,alamat1 as address,koderayon,kode_kota as kota,b1,b2,b3,b4,b5,b6,b7,b8,b9,b10,b11,b12,(b1+b2+b3+b4+b5+b6+b7+b8+b9+b10+b11+b12)/12 as rata,id from (
                            select kode_lang,
                            sum(if(blndok=1,unit,0)) as b1,sum(if(blndok=2,unit,0)) as b2,
                            sum(if(blndok=3,unit,0)) as b3,sum(if(blndok=4,unit,0)) as b4,
                            sum(if(blndok=5,unit,0)) as b5,sum(if(blndok=6,unit,0)) as b6,
                            sum(if(blndok=7,unit,0)) as b7,sum(if(blndok=8,unit,0)) as b8,
                            sum(if(blndok=9,unit,0)) as b9,sum(if(blndok=10,unit,0)) as b10,
                            sum(if(blndok=11,unit,0)) as b11,sum(if(blndok=12,unit,0)) as b12,".$id." as id
                            from
                            (
                            select kode_lang, blndok,sum(".$unit.") as unit from data".$year.".fi where nocab = ".$naper." and concat(kodesales) like '%".$kode_sales."%' and kodeprod in (".$kodeprod.")  group by kode_lang,blndok
                            union all
                            select kode_lang, blndok,sum(".$unit.") as unit from data".$year.".ri where nocab = ".$naper." and concat(kodesales) like '%".$kode_sales."%' and kodeprod in (".$kodeprod.") group by kode_lang,blndok ) a
                            group by kode_lang)a
                            left join
                            (select distinct kode_lang,koderayon,nama_lang,alamat1,kode_kota from  data".$year.".tblang where nocab = ".$naper." ) b using(kode_lang)
                         ";
                }
            }


            $query = $this->db->query($sql);
            $sql='select * from mpm.outlet where id='.$id.' order by outlet';
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
            $name = $this->get_dp_name($dp);
            $namaprod=$this->get_product_name($kodeprod);
            $this->table->set_caption('SELL OUT OUTLET '.$name->nama_comp.'<br />Product : '.$namaprod);

            $this->table->set_heading('CODE','OUTLET','ADDRESS','RAYON','KOTA', 'JANUARY','FEBRUARY','MARCH','APRIL','MAY','JUNE','JULY','AUGUST','SEPTEMBER','OCTOBER','NOVEMBER','DECEMBER');
            foreach ($query->result() as $value)
            {
                $this->table->add_row(
                        $value->kode
                        ,$value->outlet
                        ,$value->alamat
                        ,$value->rayon
                        ,$value->kota
                        ,'<div div style="text-align:right">' . number_format($value->b1,0)  . '</div>'
                        ,'<div div style="text-align:right">' . number_format($value->b2,0)  . '</div>'
                        ,'<div div style="text-align:right">' . number_format($value->b3,0)  . '</div>'
                        ,'<div div style="text-align:right">' . number_format($value->b4,0)  . '</div>'
                        ,'<div div style="text-align:right">' . number_format($value->b5,0)  . '</div>'
                        ,'<div div style="text-align:right">' . number_format($value->b6,0)  . '</div>'
                        ,'<div div style="text-align:right">' . number_format($value->b7,0)  . '</div>'
                        ,'<div div style="text-align:right">' . number_format($value->b8,0)  . '</div>'
                        ,'<div div style="text-align:right">' . number_format($value->b9,0)  . '</div>'
                        ,'<div div style="text-align:right">' . number_format($value->b10,0) . '</div>'
                        ,'<div div style="text-align:right">' . number_format($value->b11,0) . '</div>'
                        ,'<div div style="text-align:right">' . number_format($value->b12,0) . '</div>'
                );
            }
            //$this->output_table .= '<div style="width:100%; overflow:auto;height:500px;">';
            $this->output_table .= $this->table->generate();
            //$this->output_table .= '</div>';
            $this->output_table .= '<br />';
            $this->table->clear();
            return $this->output_table;
        }
        else
        {
            return FALSE;
        }
    }
    public function sales_per_product($limit=null,$offset=null,$kodeprod=null,$year=null,$retur=false)
    {
        $year2=(int)$year-1;
        $id=$this->session->userdata('id');
        $uv=$this->input->post('uv');

        $sql='select 1 from mpm.soprod where id='.$id;
        $query = $this->db->query($sql);
        if($query->num_rows()>0)
        {
            $sql= 'select * from mpm.soprod where id='.$id.'
                union all
                select "GD" as naper,"TOTAL" as namacomp,
                    sum(`t1`) as `t1`,sum(`b1`) as `b1`,
                    sum(`t2`) as `t2`,sum(`b2`) as `b2`,
                    sum(`t3`) as `t3`,sum(`b3`) as `b3`,
                    sum(`t4`) as `t4`,sum(`b4`) as `b4`,
                    sum(`t5`) as `t5`,sum(`b5`) as `b5`,
                    sum(`t6`) as `t6`,sum(`b6`) as `b6`,
                    sum(`t7`) as `t7`,sum(`b7`) as `b7`,
                    sum(`t8`) as `t8`,sum(`b8`) as `b8`,
                    sum(`t9`) as `t9`,sum(`b9`) as `b9`,
                    sum(`t10`) as `t10`,sum(`b10`) as `b10`,
                    sum(`t11`) as `t11`,sum(`b11`) as `b11`,
                    sum(`t12`) as `t12`,sum(`b12`) as `b12`,'.$id.'
                from mpm.soprod where id='.$id.'
                order by naper';
            $sql2  = $sql.' limit ? offset ?';
            $this->total_query = $query->num_rows();
            $query = $this->db->query($sql2,array($limit,$offset));
        }
        else
        {

               switch($uv)
               {
                case 0:
                    $unit='banyak';
                    break;
                case 1:
                    $unit='tot1';
                    break;

               }
                   $sql=" insert into mpm.soprod  select `naper`,`b`.`nama_comp`,

                    sum(`t1`) as `t1`,sum(`b1`) as `b1`,
                    sum(`t2`) as `t2`,sum(`b2`) as `b2`,
                    sum(`t3`) as `t3`,sum(`b3`) as `b3`,
                    sum(`t4`) as `t4`,sum(`b4`) as `b4`,
                    sum(`t5`) as `t5`,sum(`b5`) as `b5`,
                    sum(`t6`) as `t6`,sum(`b6`) as `b6`,
                    sum(`t7`) as `t7`,sum(`b7`) as `b7`,
                    sum(`t8`) as `t8`,sum(`b8`) as `b8`,
                    sum(`t9`) as `t9`,sum(`b9`) as `b9`,
                    sum(`t10`) as `t10`,sum(`b10`) as `b10`,
                    sum(`t11`) as `t11`,sum(`b11`) as `b11`,
                    sum(`t12`) as `t12`,sum(`b12`) as `b12`,
                    ".$id."

                from
                    (
                    select
                        `nocab`,

                            sum(if(`blndok` = 1, `unit`, 0)) as `b1`,
                            sum(if(`blndok` = 2, `unit`, 0)) as `b2`,
                            sum(if(`blndok` = 3, `unit`, 0)) as `b3`,
                            sum(if(`blndok` = 4, `unit`, 0)) as `b4`,
                            sum(if(`blndok` = 5, `unit`, 0)) as `b5`,
                            sum(if(`blndok` = 6, `unit`, 0)) as `b6`,
                            sum(if(`blndok` = 7, `unit`, 0)) as `b7`,
                            sum(if(`blndok` = 8, `unit`, 0)) as `b8`,
                            sum(if(`blndok` = 9, `unit`, 0)) as `b9`,
                            sum(if(`blndok` = 10, `unit`, 0)) as `b10`,
                            sum(if(`blndok` = 11, `unit`, 0)) as `b11`,
                            sum(if(`blndok` = 12, `unit`, 0)) as `b12`,
                            sum(if(`blndok` = 1, `trans`, 0)) as `t1`,
                            sum(if(`blndok` = 2, `trans`, 0)) as `t2`,
                            sum(if(`blndok` = 3, `trans`, 0)) as `t3`,
                            sum(if(`blndok` = 4, `trans`, 0)) as `t4`,
                            sum(if(`blndok` = 5, `trans`, 0)) as `t5`,
                            sum(if(`blndok` = 6, `trans`, 0)) as `t6`,
                            sum(if(`blndok` = 7, `trans`, 0)) as `t7`,
                            sum(if(`blndok` = 8, `trans`, 0)) as `t8`,
                            sum(if(`blndok` = 9, `trans`, 0)) as `t9`,
                            sum(if(`blndok` = 10, `trans`, 0)) as `t10`,
                            sum(if(`blndok` = 11, `trans`, 0)) as `t11`,
                            sum(if(`blndok` = 12, `trans`, 0)) as `t12`
                            from
                            (
                                 select `nocab`, `blndok`, sum(`".$unit."`) as `unit`, count(distinct(kode_lang)) as trans
                                 from `data".$year."`.`fi`
                                 where`kodeprod` in (".$kodeprod.") and `nodokjdi` <> 'XXXXXX'
                                 group by `nocab`,`blndok`

                                 union all

                                 select `nocab`, `blndok`, sum(`".$unit."`) as `unit`,0 as trans
                                 from `data".$year."`.`ri`
                                 where `kodeprod` in (".$kodeprod.") and `nodokjdi` <> 'XXXXXX'
                                 group by `nocab`,`blndok`
                            ) `a`

                        group by `nocab`) `a`
                     inner join
                     `mpm`.`tabcomp` `b` USING (`nocab`)
                group by `naper`
                order by `naper`
            ";
                $query = $this->db->query($sql);
                //$sql='select * from mpm.soprod where id='.$id.' order by naper';
                $sql= 'select * from mpm.soprod where id='.$id.'
                union all
                select "GD" as naper,"TOTAL" as namacomp,
                    sum(`t1`) as `t1`,sum(`b1`) as `b1`,
                    sum(`t2`) as `t2`,sum(`b2`) as `b2`,
                    sum(`t3`) as `t3`,sum(`b3`) as `b3`,
                    sum(`t4`) as `t4`,sum(`b4`) as `b4`,
                    sum(`t5`) as `t5`,sum(`b5`) as `b5`,
                    sum(`t6`) as `t6`,sum(`b6`) as `b6`,
                    sum(`t7`) as `t7`,sum(`b7`) as `b7`,
                    sum(`t8`) as `t8`,sum(`b8`) as `b8`,
                    sum(`t9`) as `t9`,sum(`b9`) as `b9`,
                    sum(`t10`) as `t10`,sum(`b10`) as `b10`,
                    sum(`t11`) as `t11`,sum(`b11`) as `b11`,
                    sum(`t12`) as `t12`,sum(`b12`) as `b12`,'.$id.'
                from mpm.soprod where id='.$id.'
                order by naper';
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
            $name = $this->get_product_name($kodeprod);
            $this->table->set_caption("SELL OUT ".$name.' '.$year);

            $this->table->set_heading('AGENT','T','JANUARY','T','FEBRUARY','T','MARCH','T','APRIL','T','MAY','T','JUNE','T',
                    'JULY','T','AUGUST','T','SEPTEMBER','T','OCTOBER','T','NOVEMBER','T','DECEMBER');
            foreach ($query->result() as $value)
            {
                $this->table->add_row(
                        $value->nama_comp
                        ,'<div div style="text-align:right">' . number_format($value->t1,0)   . '</div>'
                        ,'<div div style="text-align:right">' . number_format($value->b1,0)   . '</div>'
                        ,'<div div style="text-align:right">' . number_format($value->t2,0)   . '</div>'
                        ,'<div div style="text-align:right">' . number_format($value->b2,0)   . '</div>'
                        ,'<div div style="text-align:right">' . number_format($value->t3,0)   . '</div>'
                        ,'<div div style="text-align:right">' . number_format($value->b3,0)   . '</div>'
                        ,'<div div style="text-align:right">' . number_format($value->t4,0)   . '</div>'
                        ,'<div div style="text-align:right">' . number_format($value->b4,0)   . '</div>'
                        ,'<div div style="text-align:right">' . number_format($value->t5,0)   . '</div>'
                        ,'<div div style="text-align:right">' . number_format($value->b5,0)   . '</div>'
                        ,'<div div style="text-align:right">' . number_format($value->t6,0)   . '</div>'
                        ,'<div div style="text-align:right">' . number_format($value->b6,0)   . '</div>'
                        ,'<div div style="text-align:right">' . number_format($value->t7,0)   . '</div>'
                        ,'<div div style="text-align:right">' . number_format($value->b7,0)   . '</div>'
                        ,'<div div style="text-align:right">' . number_format($value->t8,0)   . '</div>'
                        ,'<div div style="text-align:right">' . number_format($value->b8,0)   . '</div>'
                        ,'<div div style="text-align:right">' . number_format($value->t9,0)   . '</div>'
                        ,'<div div style="text-align:right">' . number_format($value->b9,0)   . '</div>'
                        ,'<div div style="text-align:right">' . number_format($value->t10,0)  . '</div>'
                        ,'<div div style="text-align:right">' . number_format($value->b10,0)  . '</div>'
                        ,'<div div style="text-align:right">' . number_format($value->t11,0)  . '</div>'
                        ,'<div div style="text-align:right">' . number_format($value->b11,0)  . '</div>'
                        ,'<div div style="text-align:right">' . number_format($value->t12,0)  . '</div>'
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
    public function sales_per_product2($limit=null,$offset=null,$kodeprod=null,$year=null)
    {
        $year2=(int)$year-1;
        $id=$this->session->userdata('id');

        $sql='select * from mpm.soprod where id='.$id.' order by naper';
        $query = $this->db->query($sql);
        if($query->num_rows()>0)
        {
            $sql2  = $sql.' limit ? offset ?';
            $this->total_query = $query->num_rows();
            $query = $this->db->query($sql2,array($limit,$offset));
        }
        else
        {
            if($year!='2010')
            {
                $sql =" insert into mpm.soprod select naper,b.nama_comp, format(sum(rata),0) as rata,
                format(sum(b1),0) as b1,format(sum(b2),0) as b2,format(sum(b3),0) as b3,
                format(sum(b4),0) as b4,format(sum(b5),0) as b5,format(sum(b6),0) as b6,
                format(sum(b7),0) as b7,format(sum(b8),0) as b8,format(sum(b9),0) as b9,
                format(sum(b10),0) as b10,format(sum(b11),0) as b11,format(sum(b12),0) as b12,".$id."
                from(
                    select nocab,b.rata,
                    sum(if(blndok=1,unit,0)) as b1,sum(if(blndok=2,unit,0)) as b2,
                    sum(if(blndok=3,unit,0)) as b3,sum(if(blndok=4,unit,0)) as b4,
                    sum(if(blndok=5,unit,0)) as b5,sum(if(blndok=6,unit,0)) as b6,
                    sum(if(blndok=7,unit,0)) as b7,sum(if(blndok=8,unit,0)) as b8,
                    sum(if(blndok=9,unit,0)) as b9,sum(if(blndok=10,unit,0)) as b10,
                    sum(if(blndok=11,unit,0)) as b11,sum(if(blndok=12,unit,0)) as b12
                    from
                    (
                        select nocab,blndok,sum(banyak) as unit from data".$year.".fi  where kodeprod in (".$kodeprod.")  and nodokjdi<>'XXXXXX' group by nocab, blndok
                        union all
                        select nocab,blndok,sum(banyak) as unit from data".$year.".ri  where kodeprod in (".$kodeprod.")  and nodokjdi<>'XXXXXX' group by nocab, blndok
                    )
                    a
                    left join
                    (
                        select nocab,sum(unit)/12 as rata from
                        (
                        select nocab,sum(banyak) as unit from data".$year2.".fi  where kodeprod in (".$kodeprod.")  and nodokjdi<>'XXXXXX' group by nocab
                        union all
                        select nocab,sum(banyak) as unit from data".$year2.".ri  where kodeprod in (".$kodeprod.")  and nodokjdi<>'XXXXXX' group by nocab
                        )
                        a group by nocab
                    )
                    b
                    using(nocab)
                    group by nocab
                )
                a
                inner join mpm.tabcomp b using(nocab)
                group by naper
                order by naper
            ";
                $query = $this->db->query($sql);
                $sql='select * from mpm.soprod where id='.$id.' order by naper';
                $query = $this->db->query($sql);
                $sql2  = $sql.' limit ? offset ?';
                $this->total_query = $query->num_rows();
                $query = $this->db->query($sql2,array($limit,$offset));
            }
            else
            {
                $sql="
                insert into mpm.soprod select naper,b.nama_comp, format(sum(rata),0) as rata,
                format(sum(b1),0) as b1,format(sum(b2),0) as b2,format(sum(b3),0) as b3,
                format(sum(b4),0) as b4,format(sum(b5),0) as b5,format(sum(b6),0) as b6,
                format(sum(b7),0) as b7,format(sum(b8),0) as b8,format(sum(b9),0) as b9,
                format(sum(b10),0) as b10,format(sum(b11),0) as b11,format(sum(b12),0) as b12,".$id."
                FROM(
                    select a.nocab, rata,
                    sum(if(blndok=1,unit,0)) as b1,sum(if(blndok=2,unit,0)) as b2,
                    sum(if(blndok=3,unit,0)) as b3,sum(if(blndok=4,unit,0)) as b4,
                    sum(if(blndok=5,unit,0)) as b5,sum(if(blndok=6,unit,0)) as b6,
                    sum(if(blndok=7,unit,0)) as b7,sum(if(blndok=8,unit,0)) as b8,
                    sum(if(blndok=9,unit,0)) as b9,sum(if(blndok=10,unit,0)) as b10,
                    sum(if(blndok=11,unit,0)) as b11,sum(if(blndok=12,unit,0)) as b12
                    from
                    (
                        select nocab,blndok,sum(banyak) as unit from data2010.fi  where kodeprod in (".$kodeprod.")  and nodokjdi<>'XXXXXX' group by nocab, blndok
                        union all
                        select nocab,blndok,sum(banyak) as unit from data2010.ri  where kodeprod in (".$kodeprod.") and nodokjdi<>'XXXXXX' group by nocab, blndok
                    )a
                    left join
                    (
                        select nocab,( bulan1+bulan2+bulan3+bulan4+bulan5+bulan6+bulan7+bulan8+bulan9+bulan10+bulan11+bulan12)/12 as rata
                        from data2010.total2009 where kodeprod in (".$kodeprod.") group by nocab
                    )
                    b using (nocab) group by nocab order by a.nocab
                ) a
                inner join
                mpm.tabcomp b using(nocab)group by naper order by naper
                ";
                $query = $this->db->query($sql);
                $sql='select * from mpm.soprod where id='.$id.' order by naper';
                $query = $this->db->query($sql);
                $sql2  = $sql.' limit ? offset ?';
                $this->total_query = $query->num_rows();
                $query = $this->db->query($sql2,array($limit,$offset));
            }
        }

        if($query->num_rows() > 0)
        {
            $this->load->library('table');
            $this->table->set_empty('0');

            $this->table->set_template($this->tmpl);
            $name = $this->get_product_name($kodeprod);
            $this->table->set_caption("SELL OUT ".$name.' '.$year);

            $this->table->set_heading('AGENT','AVG '.$year2, 'JANUARY','FEBRUARY','MARCH','APRIL','MAY','JUNE','JULY','AUGUST','SEPTEMBER','OCTOBER','NOVEMBER','DECEMBER');
            foreach ($query->result() as $value)
            {
                $this->table->add_row(
                        $value->nama_comp
                        ,'<div div style="text-align:right">' . $value->rata . '</div>'
                        ,'<div div style="text-align:right">' . $value->b1   . '</div>'
                        ,'<div div style="text-align:right">' . $value->b2   . '</div>'
                        ,'<div div style="text-align:right">' . $value->b3   . '</div>'
                        ,'<div div style="text-align:right">' . $value->b4   . '</div>'
                        ,'<div div style="text-align:right">' . $value->b5   . '</div>'
                        ,'<div div style="text-align:right">' . $value->b6   . '</div>'
                        ,'<div div style="text-align:right">' . $value->b7   . '</div>'
                        ,'<div div style="text-align:right">' . $value->b8   . '</div>'
                        ,'<div div style="text-align:right">' . $value->b9   . '</div>'
                        ,'<div div style="text-align:right">' . $value->b10  . '</div>'
                        ,'<div div style="text-align:right">' . $value->b11  . '</div>'
                        ,'<div div style="text-align:right">' . $value->b12  . '</div>'
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
    public function raw($bulan=null,$tahun=null,$supp=null)
    {

        $supp=$this->input->post('supp');
                if($supp=='000')
                {
                    $wheresupp='';
                }
                else if($supp=='001')
                {
                    $wheresupp='and kodeprod like "60%" or kodeprod like "01%" or kodeprod like "50%"';
                }
                else if($supp=='005')
                {
                    $wheresupp='and kodeprod like "06%" ';
                }
                else
                {
                    $wheresupp="and kodeprod like '".substr($supp,-2)."%'";
                }
        /*$userid=$this->session->userdata('id');
        $sql="delete from mpm.raw where userid=".$userid;*/

        $sql= "select
                a.kode_lang as 'Customer ID' ,
                b.nama_lang as 'Customer Name',
                c.nama_comp as 'Customer Branch',
                d.nama_kota as 'Customer Territory',
                concat(b.alamat1,' ',b.alamat2) as 'Customer Address',
                '' as 'Customer Criteria',
                '' as 'Customer Phone Number',
                '' as 'Customer Postal Code',
                '' as 'Customer Channel',
                if(a.kodesalur='01','Grosir','Retail') as 'Customer Class',
                a.kodesales as 'Sales Code',
                concat(thndok,'-',blndok,'-',hrdok) as 'Full Date',
                a.nodokjdi as 'Invoice Number',
                a.kodeprod as 'Product Code',
                a.namaprod as 'Product Name',
                'PCS' as 'Unit Of Sales',
                banyak as 'Sales Quantity',
                harga*banyak as 'Sales Value Gross',
                0 as Diskon,
                harga*banyak as 'Sales Value Nett'
                from (select kode_lang,kode_kota,kodesales,thndok,blndok,hrdok,kodeprod,namaprod,banyak,harga,nocab,nodokjdi,kodesalur from data".$tahun.".fi where  bulan =".$bulan." ".$wheresupp."
                      union all
                      select kode_lang,kode_kota,kodesales,thndok,blndok,hrdok,kodeprod,namaprod,banyak,harga,nocab,nodokjdi,kodesalur from data".$tahun.".ri where  bulan =".$bulan." ".$wheresupp." )a
                left join data".$tahun.".tblang b on concat(a.nocab,a.kode_lang)=b.custid
                left join mpm.tabcomp c on a.nocab=c.nocab
                left join data".$tahun.".tbkota d on a.nocab=d.nocab and a.kode_kota=d.kode_kota";
         $query=$this->db->query($sql);
        
        /*$sql= "insert into mpm.raw select
                concat(a.nocab,a.kode_lang) as 'Customer ID' ,
                '' as 'Customer Name',
                c.nama_comp as 'Customer Branch',
                d.nama_kota as 'Customer Territory',
                '' as 'Customer Address',
                '' as 'Customer Criteria',
                '' as 'Customer Phone Number',
                '' as 'Customer Postal Code',
                '' as 'Customer Channel',
                if(a.kodesalur='01','Grosir','Retail') as 'Customer Class',
                a.kodesales as 'Sales Code',
                concat(thndok,'-',blndok,'-',hrdok) as 'Full Date',
                nodokjdi as 'Invoice Number',
                kodeprod as 'Product Code',
                namaprod as 'Product Name',
                'PCS' as 'Unit Of Sales',
                banyak as 'Sales Quantity',
                harga*banyak as 'Sales Value Gross',
                0 as Diskon,
                banyak*harga as 'Sales Value Nett',".$userid."
                from (select * from data".$tahun.".fi where  bulan =".$bulan." ".$wheresupp." order by nocab,kode_lang)a
                join mpm.tabcomp c on a.nocab=c.naper
                join data".$tahun.".tbkota d on a.nocab=d.nocab and a.kode_kota=d.kode_kota";*/
        /*$sql= "select
                a.kode_lang as 'Customer ID' ,
                b.nama_lang as 'Customer Name',
                c.nama_comp as 'Customer Branch',
                d.nama_kota as 'Customer Territory',
                '' as 'Customer Address',
                '' as 'Customer Criteria',
                '' as 'Customer Phone Number',
                '' as 'Customer Postal Code',
                '' as 'Customer Channel',
                if(a.kodesalur='01','Grosir','Retail') as 'Customer Class',
                a.kodesales as 'Sales Code',
                concat(thndok,'-',blndok,'-',hrdok) as 'Full Date',
                nodokjdi as 'Invoice Number',
                kodeprod as 'Product Code',
                namaprod as 'Product Name',
                'PCS' as 'Unit Of Sales',
                banyak as 'Sales Quantity',
                0 as 'Sales Value Gross',
                0 as Diskon,
                0 as 'Sales Value Nett'
                from (select * from data".$tahun.".fi where  bulan =".$bulan." and supp=".$supp." and nocab=1)a
                inner join  (select kode_lang,nama_lang from data".$tahun.".tblang where nocab=1) b on a.kode_lang=b.kode_lang
                join mpm.tabcomp c on a.nocab=c.naper
                join data".$tahun.".tbkota d on a.nocab=d.nocab and a.kode_kota=d.kode_kota";
        for($i=2;$i<100;$i++)
        {
        $sql.="  union all
                select
                a.kode_lang as 'Customer ID' ,
                b.nama_lang as 'Customer Name',
                c.nama_comp as 'Customer Branch',
                d.nama_kota as 'Customer Territory',
                '' as 'Customer Address',
                '' as 'Customer Criteria',
                '' as 'Customer Phone Number',
                '' as 'Customer Postal Code',
                '' as 'Customer Channel',
                if(a.kodesalur='01','Grosir','Retail') as 'Customer Class',
                a.kodesales as 'Sales Code',
                concat(thndok,'-',blndok,'-',hrdok) as 'Full Date',
                nodokjdi as 'Invoice Number',
                kodeprod as 'Product Code',
                namaprod as 'Product Name',
                'PCS' as 'Unit Of Sales',
                banyak as 'Sales Quantity',
                0 as 'Sales Value Gross',
                0 as Diskon,
                0 as 'Sales Value Nett'
                from (select * from data".$tahun.".fi where  bulan =".$bulan." and supp=".$supp." and nocab=".$i.")a
                inner join  (select kode_lang,nama_lang from data".$tahun.".tblang where nocab=".$i.") b on a.kode_lang=b.kode_lang
                join mpm.tabcomp c on a.nocab=c.naper
                join data".$tahun.".tbkota d on a.nocab=d.nocab and a.kode_kota=d.kode_kota";
        }*/

        /*$query=$this->db->query($sql);
        $sql="UPDATE mpm.raw a inner join data".$tahun.".tblang b
        on a.customer_id = b.custid
        set a.customer_name=b.nama_lang,
        a.customer_address=concat(b.alamat1,' ',b.alamat2),
        a.customer_id=right(a.customer_id,5)where a.userid=".$userid;
        $query=$this->db->query($sql);
        $sql="select * from mpm.raw where userid=".$userid;
        $query=$this->db->query($sql);*/
        return to_excel($query,'rawdata'.$bulan.$tahun);
    }
    public function sales_omzet($limit=null,$offset=null,$year=null,$retur=null)
    {
        $year2=(int)$year-1;
        $id=$this->session->userdata('id');

        $sql='select 1 from mpm.omzet where id='.$id;
        $query = $this->db->query($sql);
        if($query->num_rows()>0)
        {
            $sql='select * from mpm.omzet a
                LEFT JOIN(
                select b.lastupload , concat(substring(filename,7,2),"-",substring(filename,5,2),"-",year(lastupload))filename,substring(filename,3,2) as naper from (select max(lastupload) as maxupload,substring(filename,3,2) naper  from mpm.upload group by naper) a
                inner join mpm.upload b on b.lastupload =a.maxupload order by naper
                )b USING(naper)
                 where id='.$id.'
                    union all
                    select "GD" as naper,"TOTAL" as namacomp,
                    sum(t1),format(sum(replace(b1,",","")),0) as b1,
                    sum(t2),format(sum(replace(b2,",","")),0) as b2,
                    sum(t3),format(sum(replace(b3,",","")),0) as b3,
                    sum(t4),format(sum(replace(b4,",","")),0) as b4,
                    sum(t5),format(sum(replace(b5,",","")),0) as b5,
                    sum(t6),format(sum(replace(b6,",","")),0) as b6,
                    sum(t7),format(sum(replace(b7,",","")),0) as b7,
                    sum(t8),format(sum(replace(b8,",","")),0) as b8,
                    sum(t9),format(sum(replace(b9,",","")),0) as b9,
                    sum(t10),format(sum(replace(b10,",","")),0) as b10,
                    sum(t11),format(sum(replace(b11,",","")),0) as b11,
                    sum(t12),format(sum(replace(b12,",","")),0) as b12,'.$id.',"",""
                    from mpm.omzet b where b.id='.$id.' order by naper';
               $sql1='select * from mpm.omzet a
                LEFT JOIN(
                select b.lastupload , concat(substring(filename,7,2),"-",substring(filename,5,2),"-",year(lastupload))filename,substring(filename,3,2) as naper from (select max(lastupload) as maxupload,substring(filename,3,2) naper  from mpm.upload group by naper) a
                inner join mpm.upload b on b.lastupload =a.maxupload order by naper
                )b USING(naper)
                 where id='.$id.'
                    union all
                    SELECT "~~~",a.sub, concat("SUBTOTAL ",sub_nama),
                    sum(t1),format(sum(replace(b1,",","")),0) as b1,
                    sum(t2),format(sum(replace(b2,",","")),0) as b2,
                    sum(t3),format(sum(replace(b3,",","")),0) as b3,
                    sum(t4),format(sum(replace(b4,",","")),0) as b4,
                    sum(t5),format(sum(replace(b5,",","")),0) as b5,
                    sum(t6),format(sum(replace(b6,",","")),0) as b6,
                    sum(t7),format(sum(replace(b7,",","")),0) as b7,
                    sum(t8),format(sum(replace(b8,",","")),0) as b8,
                    sum(t9),format(sum(replace(b9,",","")),0) as b9,
                    sum(t10),format(sum(replace(b10,",","")),0) as b10,
                    sum(t11),format(sum(replace(b11,",","")),0) as b11,
                    sum(t12),format(sum(replace(b12,",","")),0) as b12,'.$id.',"",""
                    FROM
            mpm.omzet a inner join mpm.tabcomp b using(naper)
                        where b.sub<>"" and a.id='.$id.' GROUP BY sub 
                    union all
                    select "~~~" as naper,"~~~","TOTAL" as namacomp,
                    sum(t1),format(sum(replace(b1,",","")),0) as b1,
                    sum(t2),format(sum(replace(b2,",","")),0) as b2,
                    sum(t3),format(sum(replace(b3,",","")),0) as b3,
                    sum(t4),format(sum(replace(b4,",","")),0) as b4,
                    sum(t5),format(sum(replace(b5,",","")),0) as b5,
                    sum(t6),format(sum(replace(b6,",","")),0) as b6,
                    sum(t7),format(sum(replace(b7,",","")),0) as b7,
                    sum(t8),format(sum(replace(b8,",","")),0) as b8,
                    sum(t9),format(sum(replace(b9,",","")),0) as b9,
                    sum(t10),format(sum(replace(b10,",","")),0) as b10,
                    sum(t11),format(sum(replace(b11,",","")),0) as b11,
                    sum(t12),format(sum(replace(b12,",","")),0) as b12,'.$id.',"",""
                    from mpm.omzet b where b.id='.$id.' order by sub,naper';
            $sql2  = $sql1.' limit ? offset ?';
            $this->total_query = $query->num_rows();
            $query = $this->db->query($sql2,array($limit,$offset));
        }
        else
        {
                $supp=$this->input->post('supp');
                if($supp=='000')
                {
                    $wheresupp='';
                }
                else if($supp=='001')
                {
                    $wheresupp='and kodeprod like "60%" or kodeprod like "01%" or kodeprod like "50%" or kodeprod like "70%"';
                }
                else if($supp=='005')
                {
                    $wheresupp='and kodeprod like "06%" ';
                }
                else
                {
                    $wheresupp="and kodeprod like '".substr($supp,-2)."%'";
                }
                if($retur)
                {

                    $trans="select `nocab`, `blndok`, sum(`tot1`) as `unit`
                                 from `data".$year."`.`ri`
                                 where `nodokjdi` <> 'XXXXXX' ".$wheresupp."
                                 group by `nocab`,`blndok`";
                }
                else
                {

                    $trans="select `nocab`, `blndok`, sum(`tot1`) as `unit`
                                 from `data".$year."`.`fi`
                                 where `nodokjdi` <> 'XXXXXX' ".$wheresupp."
                                 group by `nocab`,`blndok`

                                 union all

                                 select `nocab`, `blndok`, sum(`tot1`) as `unit`
                                 from `data".$year."`.`ri`
                                 where `nodokjdi` <> 'XXXXXX' ".$wheresupp."
                                 group by `nocab`,`blndok`";
                }

                $sql=" insert into mpm.omzet  select naper,`sub`,`b`.`nama_comp`,0,
                    format(sum(`b1`), 0) as `b1`,0,
                    format(sum(`b2`), 0) as `b2`,0,
                    format(sum(`b3`), 0) as `b3`,0,
                    format(sum(`b4`), 0) as `b4`,0,
                    format(sum(`b5`), 0) as `b5`,0,
                    format(sum(`b6`), 0) as `b6`,0,
                    format(sum(`b7`), 0) as `b7`,0,
                    format(sum(`b8`), 0) as `b8`,0,
                    format(sum(`b9`), 0) as `b9`,0,
                    format(sum(`b10`), 0) as `b10`,0,
                    format(sum(`b11`), 0) as `b11`,0,
                    format(sum(`b12`), 0) as `b12`,
                    ".$id."

                from
                    (
                    select
                        `nocab`,

                            sum(if(`blndok` = 1, `unit`, 0)) as `b1`,
                            sum(if(`blndok` = 2, `unit`, 0)) as `b2`,
                            sum(if(`blndok` = 3, `unit`, 0)) as `b3`,
                            sum(if(`blndok` = 4, `unit`, 0)) as `b4`,
                            sum(if(`blndok` = 5, `unit`, 0)) as `b5`,
                            sum(if(`blndok` = 6, `unit`, 0)) as `b6`,
                            sum(if(`blndok` = 7, `unit`, 0)) as `b7`,
                            sum(if(`blndok` = 8, `unit`, 0)) as `b8`,
                            sum(if(`blndok` = 9, `unit`, 0)) as `b9`,
                            sum(if(`blndok` = 10, `unit`, 0)) as `b10`,
                            sum(if(`blndok` = 11, `unit`, 0)) as `b11`,
                            sum(if(`blndok` = 12, `unit`, 0)) as `b12`

                            from
                           (

                                 ".$trans."
                            ) `a`

                        group by `nocab`) `a`
                     inner join
                     `mpm`.`tabcomp` `b` USING (`nocab`)
                group by `naper`
                order by `naper`
            ";
             $query = $this->db->query($sql);

               $sql='select * from mpm.omzet a 
                 LEFT JOIN(
                    select b.lastupload , concat(substring(filename,7,2),"-",substring(filename,5,2),"-",year(lastupload))filename,substring(filename,3,2) as naper from (select max(lastupload) as maxupload,substring(filename,3,2) naper  from mpm.upload group by naper) a
                    inner join mpm.upload b on b.lastupload =a.maxupload order by naper
                    )b USING(naper)
                  where id='.$id.'
                    union all
                    select "GD" as naper,"TOTAL" as namacomp,
                    sum(t1),format(sum(replace(b1,",","")),0) as b1,
                    sum(t2),format(sum(replace(b2,",","")),0) as b2,
                    sum(t3),format(sum(replace(b3,",","")),0) as b3,
                    sum(t4),format(sum(replace(b4,",","")),0) as b4,
                    sum(t5),format(sum(replace(b5,",","")),0) as b5,
                    sum(t6),format(sum(replace(b6,",","")),0) as b6,
                    sum(t7),format(sum(replace(b7,",","")),0) as b7,
                    sum(t8),format(sum(replace(b8,",","")),0) as b8,
                    sum(t9),format(sum(replace(b9,",","")),0) as b9,
                    sum(t10),format(sum(replace(b10,",","")),0) as b10,
                    sum(t11),format(sum(replace(b11,",","")),0) as b11,
                    sum(t12),format(sum(replace(b12,",","")),0) as b12,'.$id.',"",""
                    from mpm.omzet b where b.id='.$id.' order by naper ';
               $sql1='select * from mpm.omzet a
                LEFT JOIN(
                select b.lastupload , concat(substring(filename,7,2),"-",substring(filename,5,2),"-",year(lastupload))filename,substring(filename,3,2) as naper from (select max(lastupload) as maxupload,substring(filename,3,2) naper  from mpm.upload group by naper) a
                inner join mpm.upload b on b.lastupload =a.maxupload order by naper
                )b USING(naper)
                 where id='.$id.'
                    union all
                    SELECT "~~~",a.sub, concat("Z_SUBTOTAL ",sub_nama),
                    sum(t1),format(sum(replace(b1,",","")),0) as b1,
                    sum(t2),format(sum(replace(b2,",","")),0) as b2,
                    sum(t3),format(sum(replace(b3,",","")),0) as b3,
                    sum(t4),format(sum(replace(b4,",","")),0) as b4,
                    sum(t5),format(sum(replace(b5,",","")),0) as b5,
                    sum(t6),format(sum(replace(b6,",","")),0) as b6,
                    sum(t7),format(sum(replace(b7,",","")),0) as b7,
                    sum(t8),format(sum(replace(b8,",","")),0) as b8,
                    sum(t9),format(sum(replace(b9,",","")),0) as b9,
                    sum(t10),format(sum(replace(b10,",","")),0) as b10,
                    sum(t11),format(sum(replace(b11,",","")),0) as b11,
                    sum(t12),format(sum(replace(b12,",","")),0) as b12,'.$id.',"",""
                    FROM
            mpm.omzet a inner join mpm.tabcomp b using(naper)
                        where b.sub<>"" and a.id='.$id.' GROUP BY sub 
                    union all
                    select "~~~" as naper,"~~~","TOTAL" as namacomp,
                    sum(t1),format(sum(replace(b1,",","")),0) as b1,
                    sum(t2),format(sum(replace(b2,",","")),0) as b2,
                    sum(t3),format(sum(replace(b3,",","")),0) as b3,
                    sum(t4),format(sum(replace(b4,",","")),0) as b4,
                    sum(t5),format(sum(replace(b5,",","")),0) as b5,
                    sum(t6),format(sum(replace(b6,",","")),0) as b6,
                    sum(t7),format(sum(replace(b7,",","")),0) as b7,
                    sum(t8),format(sum(replace(b8,",","")),0) as b8,
                    sum(t9),format(sum(replace(b9,",","")),0) as b9,
                    sum(t10),format(sum(replace(b10,",","")),0) as b10,
                    sum(t11),format(sum(replace(b11,",","")),0) as b11,
                    sum(t12),format(sum(replace(b12,",","")),0) as b12,'.$id.',"",""
                    from mpm.omzet b where b.id='.$id.' order by sub,namacomp';
             $query = $this->db->query($sql1);
             $sql2  = $sql1.' limit ? offset ?';
             $query = $this->db->query($sql1);
             $this->total_query = $query->num_rows();
             $query = $this->db->query($sql2,array($limit,$offset));
        }


        if($query->num_rows() > 0)
        {
            $this->load->library('table');
            $this->table->set_empty('0');

            $this->table->set_template($this->tmpl);
            $this->table->set_caption('OMZET DP '.$year);

            /*$this->table->set_heading('NAPER','AGENT','T','JANUARY','T','FEBRUARY','T','MARCH','T','APRIL','T','MAY','T','JUNE','T',
                    'JULY','T','AUGUST','T','SEPTEMBER','T','OCTOBER','T','NOVEMBER','T','DECEMBER');*/
            $this->table->set_heading("NO.CAB",'AGENT','JANUARY','FEBRUARY','MARCH','APRIL','MAY','JUNE',
                    'JULY','AUGUST','SEPTEMBER','OCTOBER','NOVEMBER','DECEMBER','LAST UPDATE','TGL FILE');
            $total=array();
                $total1='';
                $total2='';
                $total3='';
                $total4='';
                $total5='';
                $total6='';
                $total7='';
                $total8='';
                $total9='';
                $total10='';
                $total11='';
                $total12='';
            foreach ($query->result() as $value)
            {
                /*$total1+=str_replace(",","",$value->b1);
                $total2+=$value->b2;
                $total3+=$value->b3;
                $total4+=$value->b4;
                $total5+=$value->b5;
                $total6+=$value->b6;
                $total7+=$value->b7;
                $total8+=$value->b8;
                $total9+=$value->b9;
                $total10+=$value->b10;
                $total11+=$value->b11;
                $total12+=$value->b12;*/



                $this->table->add_row(
                        $value->naper,
                        $value->namacomp
                        //,'<div div style="text-align:right">' . $value->rata . '</div>'
                        //,'<div div style="text-align:right">' . $value->t1   . '</div>'
                        ,'<div div style="text-align:right">' . $value->b1   . '</div>'
                        //,'<div div style="text-align:right">' . $value->t2   . '</div>'
                        ,'<div div style="text-align:right">' . $value->b2   . '</div>'
                        //,'<div div style="text-align:right">' . $value->t3   . '</div>'
                        ,'<div div style="text-align:right">' . $value->b3   . '</div>'
                        //,'<div div style="text-align:right">' . $value->t4   . '</div>'
                        ,'<div div style="text-align:right">' . $value->b4   . '</div>'
                        //,'<div div style="text-align:right">' . $value->t5   . '</div>'
                        ,'<div div style="text-align:right">' . $value->b5   . '</div>'
                        //,'<div div style="text-align:right">' . $value->t6   . '</div>'
                        ,'<div div style="text-align:right">' . $value->b6   . '</div>'
                        //,'<div div style="text-align:right">' . $value->t7   . '</div>'
                        ,'<div div style="text-align:right">' . $value->b7   . '</div>'
                        //,'<div div style="text-align:right">' . $value->t8   . '</div>'
                        ,'<div div style="text-align:right">' . $value->b8   . '</div>'
                        //,'<div div style="text-align:right">' . $value->t9   . '</div>'
                        ,'<div div style="text-align:right">' . $value->b9   . '</div>'
                        //,'<div div style="text-align:right">' . $value->t10  . '</div>'
                        ,'<div div style="text-align:right">' . $value->b10  . '</div>'
                        //,'<div div style="text-align:right">' . $value->t11  . '</div>'
                        ,'<div div style="text-align:right">' . $value->b11  . '</div>'
                        //,'<div div style="text-align:right">' . $value->t12  . '</div>'
                        ,'<div div style="text-align:right">' . $value->b12  . '</div>'
                        ,$value->lastupload
                        ,$value->filename
                        //,$value->filename
                );
            }
            //$this->table->add_row('','TOTAL',$total1,$total2,$total3,$total4,$total5,$total6,$total7,$total8,$total10,$total11,$total11,$total12,'');
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
    public function sales_omzet_barat($limit=null,$offset=null,$year=null,$retur=null)
    {
        $year2=(int)$year-1;
        $id=$this->session->userdata('id');

        $sql='select 1 from mpm.omzet where id='.$id;
        $query = $this->db->query($sql);
        if($query->num_rows()>0)
        {
            $sql='select * from mpm.omzet a
                LEFT JOIN(
                select b.lastupload , concat(substring(filename,7,2),"-",substring(filename,5,2),"-",year(lastupload))filename,substring(filename,3,2) as naper from (select max(lastupload) as maxupload,substring(filename,3,2) naper  from mpm.upload group by naper) a
                inner join mpm.upload b on b.lastupload =a.maxupload order by naper
                )b USING(naper)
                 where id='.$id.'
                    union all
                    select "GD" as naper,"","TOTAL" as namacomp,
                    sum(t1),format(sum(replace(b1,",","")),0) as b1,
                    sum(t2),format(sum(replace(b2,",","")),0) as b2,
                    sum(t3),format(sum(replace(b3,",","")),0) as b3,
                    sum(t4),format(sum(replace(b4,",","")),0) as b4,
                    sum(t5),format(sum(replace(b5,",","")),0) as b5,
                    sum(t6),format(sum(replace(b6,",","")),0) as b6,
                    sum(t7),format(sum(replace(b7,",","")),0) as b7,
                    sum(t8),format(sum(replace(b8,",","")),0) as b8,
                    sum(t9),format(sum(replace(b9,",","")),0) as b9,
                    sum(t10),format(sum(replace(b10,",","")),0) as b10,
                    sum(t11),format(sum(replace(b11,",","")),0) as b11,
                    sum(t12),format(sum(replace(b12,",","")),0) as b12,'.$id.',"",""
                    from mpm.omzet b where b.id='.$id.' order by naper';
            $sql2  = $sql.' limit ? offset ?';
            $this->total_query = $query->num_rows();
            $query = $this->db->query($sql2,array($limit,$offset));
        }
        else
        {
                $supp=$this->input->post('supp');
                if($supp=='000')
                {
                    $wheresupp='';
                }
                else if($supp=='001')
                {
                    $wheresupp='and kodeprod like "60%" or kodeprod like "01%" or kodeprod like "50%" or kodeprod like "70%"';
                }
                else if($supp=='005')
                {
                    $wheresupp='and kodeprod like "06%" ';
                }
                else
                {
                    $wheresupp="and kodeprod like '".substr($supp,-2)."%'";
                }
                if($retur)
                {

                    $trans="select `nocab`, `blndok`, sum(`tot1`) as `unit`
                                 from `data".$year."`.`ri`
                                 where `nodokjdi` <> 'XXXXXX' ".$wheresupp."
                                 group by `nocab`,`blndok`";
                }
                else
                {

                    $trans="select `nocab`, `blndok`, sum(`tot1`) as `unit`
                                 from `data".$year."`.`fi`
                                 where `nodokjdi` <> 'XXXXXX' ".$wheresupp."
                                 group by `nocab`,`blndok`

                                 union all

                                 select `nocab`, `blndok`, sum(`tot1`) as `unit`
                                 from `data".$year."`.`ri`
                                 where `nodokjdi` <> 'XXXXXX' ".$wheresupp."
                                 group by `nocab`,`blndok`";
                }

                $sql=" insert into mpm.omzet  select `naper`,'',`b`.`nama_comp`,0,
                    format(sum(`b1`), 0) as `b1`,0,
                    format(sum(`b2`), 0) as `b2`,0,
                    format(sum(`b3`), 0) as `b3`,0,
                    format(sum(`b4`), 0) as `b4`,0,
                    format(sum(`b5`), 0) as `b5`,0,
                    format(sum(`b6`), 0) as `b6`,0,
                    format(sum(`b7`), 0) as `b7`,0,
                    format(sum(`b8`), 0) as `b8`,0,
                    format(sum(`b9`), 0) as `b9`,0,
                    format(sum(`b10`), 0) as `b10`,0,
                    format(sum(`b11`), 0) as `b11`,0,
                    format(sum(`b12`), 0) as `b12`,
                    ".$id."

                from
                    (
                    select
                        `nocab`,'',

                            sum(if(`blndok` = 1, `unit`, 0)) as `b1`,
                            sum(if(`blndok` = 2, `unit`, 0)) as `b2`,
                            sum(if(`blndok` = 3, `unit`, 0)) as `b3`,
                            sum(if(`blndok` = 4, `unit`, 0)) as `b4`,
                            sum(if(`blndok` = 5, `unit`, 0)) as `b5`,
                            sum(if(`blndok` = 6, `unit`, 0)) as `b6`,
                            sum(if(`blndok` = 7, `unit`, 0)) as `b7`,
                            sum(if(`blndok` = 8, `unit`, 0)) as `b8`,
                            sum(if(`blndok` = 9, `unit`, 0)) as `b9`,
                            sum(if(`blndok` = 10, `unit`, 0)) as `b10`,
                            sum(if(`blndok` = 11, `unit`, 0)) as `b11`,
                            sum(if(`blndok` = 12, `unit`, 0)) as `b12`

                            from
                           (

                                 ".$trans."
                            ) `a`

                        group by `nocab`) `a`
                     inner join
                     `mpm`.`tabcomp` `b` USING (`nocab`) where b.wilayah=1
                group by `naper`
                order by `naper`
            ";
             $query = $this->db->query($sql);

               $sql='select * from mpm.omzet a 
                 LEFT JOIN(
                    select b.lastupload , concat(substring(filename,7,2),"-",substring(filename,5,2),"-",year(lastupload))filename,substring(filename,3,2) as naper from (select max(lastupload) as maxupload,substring(filename,3,2) naper  from mpm.upload group by naper) a
                    inner join mpm.upload b on b.lastupload =a.maxupload order by naper
                    )b USING(naper)
                  where id='.$id.'
                    union all
                    select "GD" as naper,"","TOTAL" as namacomp,
                    sum(t1),format(sum(replace(b1,",","")),0) as b1,
                    sum(t2),format(sum(replace(b2,",","")),0) as b2,
                    sum(t3),format(sum(replace(b3,",","")),0) as b3,
                    sum(t4),format(sum(replace(b4,",","")),0) as b4,
                    sum(t5),format(sum(replace(b5,",","")),0) as b5,
                    sum(t6),format(sum(replace(b6,",","")),0) as b6,
                    sum(t7),format(sum(replace(b7,",","")),0) as b7,
                    sum(t8),format(sum(replace(b8,",","")),0) as b8,
                    sum(t9),format(sum(replace(b9,",","")),0) as b9,
                    sum(t10),format(sum(replace(b10,",","")),0) as b10,
                    sum(t11),format(sum(replace(b11,",","")),0) as b11,
                    sum(t12),format(sum(replace(b12,",","")),0) as b12,'.$id.',"",""
                    from mpm.omzet b where b.id='.$id.' order by naper ';
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
            $this->table->set_caption('OMZET DP '.$year);

            /*$this->table->set_heading('NAPER','AGENT','T','JANUARY','T','FEBRUARY','T','MARCH','T','APRIL','T','MAY','T','JUNE','T',
                    'JULY','T','AUGUST','T','SEPTEMBER','T','OCTOBER','T','NOVEMBER','T','DECEMBER');*/
            $this->table->set_heading('NAPER','AGENT','JANUARY','FEBRUARY','MARCH','APRIL','MAY','JUNE',
                    'JULY','AUGUST','SEPTEMBER','OCTOBER','NOVEMBER','DECEMBER','LAST UPDATE','TGL FILE');
            $total=array();
                $total1='';
                $total2='';
                $total3='';
                $total4='';
                $total5='';
                $total6='';
                $total7='';
                $total8='';
                $total9='';
                $total10='';
                $total11='';
                $total12='';
            foreach ($query->result() as $value)
            {
                /*$total1+=str_replace(",","",$value->b1);
                $total2+=$value->b2;
                $total3+=$value->b3;
                $total4+=$value->b4;
                $total5+=$value->b5;
                $total6+=$value->b6;
                $total7+=$value->b7;
                $total8+=$value->b8;
                $total9+=$value->b9;
                $total10+=$value->b10;
                $total11+=$value->b11;
                $total12+=$value->b12;*/



                $this->table->add_row(
                        $value->naper,
                        $value->namacomp
                        //,'<div div style="text-align:right">' . $value->rata . '</div>'
                        //,'<div div style="text-align:right">' . $value->t1   . '</div>'
                        ,'<div div style="text-align:right">' . $value->b1   . '</div>'
                        //,'<div div style="text-align:right">' . $value->t2   . '</div>'
                        ,'<div div style="text-align:right">' . $value->b2   . '</div>'
                        //,'<div div style="text-align:right">' . $value->t3   . '</div>'
                        ,'<div div style="text-align:right">' . $value->b3   . '</div>'
                        //,'<div div style="text-align:right">' . $value->t4   . '</div>'
                        ,'<div div style="text-align:right">' . $value->b4   . '</div>'
                        //,'<div div style="text-align:right">' . $value->t5   . '</div>'
                        ,'<div div style="text-align:right">' . $value->b5   . '</div>'
                        //,'<div div style="text-align:right">' . $value->t6   . '</div>'
                        ,'<div div style="text-align:right">' . $value->b6   . '</div>'
                        //,'<div div style="text-align:right">' . $value->t7   . '</div>'
                        ,'<div div style="text-align:right">' . $value->b7   . '</div>'
                        //,'<div div style="text-align:right">' . $value->t8   . '</div>'
                        ,'<div div style="text-align:right">' . $value->b8   . '</div>'
                        //,'<div div style="text-align:right">' . $value->t9   . '</div>'
                        ,'<div div style="text-align:right">' . $value->b9   . '</div>'
                        //,'<div div style="text-align:right">' . $value->t10  . '</div>'
                        ,'<div div style="text-align:right">' . $value->b10  . '</div>'
                        //,'<div div style="text-align:right">' . $value->t11  . '</div>'
                        ,'<div div style="text-align:right">' . $value->b11  . '</div>'
                        //,'<div div style="text-align:right">' . $value->t12  . '</div>'
                        ,'<div div style="text-align:right">' . $value->b12  . '</div>'
                        ,$value->lastupload
                        ,$value->filename
                        //,$value->filename
                );
            }
            //$this->table->add_row('','TOTAL',$total1,$total2,$total3,$total4,$total5,$total6,$total7,$total8,$total10,$total11,$total11,$total12,'');
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
    public function sales_omzet_timur($limit=null,$offset=null,$year=null,$retur=null)
    {
        $year2=(int)$year-1;
        $id=$this->session->userdata('id');

        $sql='select 1 from mpm.omzet where id='.$id;
        $query = $this->db->query($sql);
        if($query->num_rows()>0)
        {
            $sql='select * from mpm.omzet a
                LEFT JOIN(
                select b.lastupload , concat(substring(filename,7,2),"-",substring(filename,5,2),"-",year(lastupload))filename,substring(filename,3,2) as naper from (select max(lastupload) as maxupload,substring(filename,3,2) naper  from mpm.upload group by naper) a
                inner join mpm.upload b on b.lastupload =a.maxupload order by naper
                )b USING(naper)
                 where id='.$id.'
                    union all
                    select "GD" as naper,"","TOTAL" as namacomp,
                    sum(t1),format(sum(replace(b1,",","")),0) as b1,
                    sum(t2),format(sum(replace(b2,",","")),0) as b2,
                    sum(t3),format(sum(replace(b3,",","")),0) as b3,
                    sum(t4),format(sum(replace(b4,",","")),0) as b4,
                    sum(t5),format(sum(replace(b5,",","")),0) as b5,
                    sum(t6),format(sum(replace(b6,",","")),0) as b6,
                    sum(t7),format(sum(replace(b7,",","")),0) as b7,
                    sum(t8),format(sum(replace(b8,",","")),0) as b8,
                    sum(t9),format(sum(replace(b9,",","")),0) as b9,
                    sum(t10),format(sum(replace(b10,",","")),0) as b10,
                    sum(t11),format(sum(replace(b11,",","")),0) as b11,
                    sum(t12),format(sum(replace(b12,",","")),0) as b12,'.$id.',"",""
                    from mpm.omzet b where b.id='.$id.' order by naper';
            $sql2  = $sql.' limit ? offset ?';
            $this->total_query = $query->num_rows();
            $query = $this->db->query($sql2,array($limit,$offset));
        }
        else
        {
                $supp=$this->input->post('supp');
                if($supp=='000')
                {
                    $wheresupp='';
                }
                else if($supp=='001')
                {
                    $wheresupp='and kodeprod like "60%" or kodeprod like "01%" or kodeprod like "50%" or kodeprod like "70%"';
                }
                else if($supp=='005')
                {
                    $wheresupp='and kodeprod like "06%" ';
                }
                else
                {
                    $wheresupp="and kodeprod like '".substr($supp,-2)."%'";
                }
                if($retur)
                {

                    $trans="select `nocab`, `blndok`, sum(`tot1`) as `unit`
                                 from `data".$year."`.`ri`
                                 where `nodokjdi` <> 'XXXXXX' ".$wheresupp."
                                 group by `nocab`,`blndok`";
                }
                else
                {

                    $trans="select `nocab`, `blndok`, sum(`tot1`) as `unit`
                                 from `data".$year."`.`fi`
                                 where `nodokjdi` <> 'XXXXXX' ".$wheresupp."
                                 group by `nocab`,`blndok`

                                 union all

                                 select `nocab`, `blndok`, sum(`tot1`) as `unit`
                                 from `data".$year."`.`ri`
                                 where `nodokjdi` <> 'XXXXXX' ".$wheresupp."
                                 group by `nocab`,`blndok`";
                }

                $sql=" insert into mpm.omzet  select `naper`,'',`b`.`nama_comp`,0,
                    format(sum(`b1`), 0) as `b1`,0,
                    format(sum(`b2`), 0) as `b2`,0,
                    format(sum(`b3`), 0) as `b3`,0,
                    format(sum(`b4`), 0) as `b4`,0,
                    format(sum(`b5`), 0) as `b5`,0,
                    format(sum(`b6`), 0) as `b6`,0,
                    format(sum(`b7`), 0) as `b7`,0,
                    format(sum(`b8`), 0) as `b8`,0,
                    format(sum(`b9`), 0) as `b9`,0,
                    format(sum(`b10`), 0) as `b10`,0,
                    format(sum(`b11`), 0) as `b11`,0,
                    format(sum(`b12`), 0) as `b12`,
                    ".$id."

                from
                    (
                    select
                        `nocab`,

                            sum(if(`blndok` = 1, `unit`, 0)) as `b1`,
                            sum(if(`blndok` = 2, `unit`, 0)) as `b2`,
                            sum(if(`blndok` = 3, `unit`, 0)) as `b3`,
                            sum(if(`blndok` = 4, `unit`, 0)) as `b4`,
                            sum(if(`blndok` = 5, `unit`, 0)) as `b5`,
                            sum(if(`blndok` = 6, `unit`, 0)) as `b6`,
                            sum(if(`blndok` = 7, `unit`, 0)) as `b7`,
                            sum(if(`blndok` = 8, `unit`, 0)) as `b8`,
                            sum(if(`blndok` = 9, `unit`, 0)) as `b9`,
                            sum(if(`blndok` = 10, `unit`, 0)) as `b10`,
                            sum(if(`blndok` = 11, `unit`, 0)) as `b11`,
                            sum(if(`blndok` = 12, `unit`, 0)) as `b12`

                            from
                           (

                                 ".$trans."
                            ) `a`

                        group by `nocab`) `a`
                     inner join
                     `mpm`.`tabcomp` `b` USING (`nocab`) where b.wilayah=2
                group by `naper`
                order by `naper`
            ";
             $query = $this->db->query($sql);

               $sql='select * from mpm.omzet a 
                 LEFT JOIN(
                    select b.lastupload , concat(substring(filename,7,2),"-",substring(filename,5,2),"-",year(lastupload))filename,substring(filename,3,2) as naper from (select max(lastupload) as maxupload,substring(filename,3,2) naper  from mpm.upload group by naper) a
                    inner join mpm.upload b on b.lastupload =a.maxupload order by naper
                    )b USING(naper)
                  where id='.$id.'
                    union all
                    select "GD" as naper,"","TOTAL" as namacomp,
                    sum(t1),format(sum(replace(b1,",","")),0) as b1,
                    sum(t2),format(sum(replace(b2,",","")),0) as b2,
                    sum(t3),format(sum(replace(b3,",","")),0) as b3,
                    sum(t4),format(sum(replace(b4,",","")),0) as b4,
                    sum(t5),format(sum(replace(b5,",","")),0) as b5,
                    sum(t6),format(sum(replace(b6,",","")),0) as b6,
                    sum(t7),format(sum(replace(b7,",","")),0) as b7,
                    sum(t8),format(sum(replace(b8,",","")),0) as b8,
                    sum(t9),format(sum(replace(b9,",","")),0) as b9,
                    sum(t10),format(sum(replace(b10,",","")),0) as b10,
                    sum(t11),format(sum(replace(b11,",","")),0) as b11,
                    sum(t12),format(sum(replace(b12,",","")),0) as b12,'.$id.',"",""
                    from mpm.omzet b where b.id='.$id.' order by naper ';
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
            $this->table->set_caption('OMZET DP '.$year);

            /*$this->table->set_heading('NAPER','AGENT','T','JANUARY','T','FEBRUARY','T','MARCH','T','APRIL','T','MAY','T','JUNE','T',
                    'JULY','T','AUGUST','T','SEPTEMBER','T','OCTOBER','T','NOVEMBER','T','DECEMBER');*/
            $this->table->set_heading('NAPER','AGENT','JANUARY','FEBRUARY','MARCH','APRIL','MAY','JUNE',
                    'JULY','AUGUST','SEPTEMBER','OCTOBER','NOVEMBER','DECEMBER','LAST UPDATE','TGL FILE');
            $total=array();
                $total1='';
                $total2='';
                $total3='';
                $total4='';
                $total5='';
                $total6='';
                $total7='';
                $total8='';
                $total9='';
                $total10='';
                $total11='';
                $total12='';
            foreach ($query->result() as $value)
            {
                /*$total1+=str_replace(",","",$value->b1);
                $total2+=$value->b2;
                $total3+=$value->b3;
                $total4+=$value->b4;
                $total5+=$value->b5;
                $total6+=$value->b6;
                $total7+=$value->b7;
                $total8+=$value->b8;
                $total9+=$value->b9;
                $total10+=$value->b10;
                $total11+=$value->b11;
                $total12+=$value->b12;*/



                $this->table->add_row(
                        $value->naper,
                        $value->namacomp
                        //,'<div div style="text-align:right">' . $value->rata . '</div>'
                        //,'<div div style="text-align:right">' . $value->t1   . '</div>'
                        ,'<div div style="text-align:right">' . $value->b1   . '</div>'
                        //,'<div div style="text-align:right">' . $value->t2   . '</div>'
                        ,'<div div style="text-align:right">' . $value->b2   . '</div>'
                        //,'<div div style="text-align:right">' . $value->t3   . '</div>'
                        ,'<div div style="text-align:right">' . $value->b3   . '</div>'
                        //,'<div div style="text-align:right">' . $value->t4   . '</div>'
                        ,'<div div style="text-align:right">' . $value->b4   . '</div>'
                        //,'<div div style="text-align:right">' . $value->t5   . '</div>'
                        ,'<div div style="text-align:right">' . $value->b5   . '</div>'
                        //,'<div div style="text-align:right">' . $value->t6   . '</div>'
                        ,'<div div style="text-align:right">' . $value->b6   . '</div>'
                        //,'<div div style="text-align:right">' . $value->t7   . '</div>'
                        ,'<div div style="text-align:right">' . $value->b7   . '</div>'
                        //,'<div div style="text-align:right">' . $value->t8   . '</div>'
                        ,'<div div style="text-align:right">' . $value->b8   . '</div>'
                        //,'<div div style="text-align:right">' . $value->t9   . '</div>'
                        ,'<div div style="text-align:right">' . $value->b9   . '</div>'
                        //,'<div div style="text-align:right">' . $value->t10  . '</div>'
                        ,'<div div style="text-align:right">' . $value->b10  . '</div>'
                        //,'<div div style="text-align:right">' . $value->t11  . '</div>'
                        ,'<div div style="text-align:right">' . $value->b11  . '</div>'
                        //,'<div div style="text-align:right">' . $value->t12  . '</div>'
                        ,'<div div style="text-align:right">' . $value->b12  . '</div>'
                        ,$value->lastupload
                        ,$value->filename
                        //,$value->filename
                );
            }
            //$this->table->add_row('','TOTAL',$total1,$total2,$total3,$total4,$total5,$total6,$total7,$total8,$total10,$total11,$total11,$total12,'');
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
    public function print_konsolidasi($year=null)
    {
        $newmoon1='';
        $newmoon2='';
        $newmoon3='';
        for($i=1;$i<=12;$i++)
        {
                $newmoon1 .=",sum(b".$i.") as b".$i;
        }

        for($i=1;$i<=12;$i++)
        {
                $newmoon2 .=",b".$i;
        }
        for($i=1;$i<=12;$i++)
        {
                $newmoon3 .=",if(month(tgldokjdi)='".$i."',banyak,0) as b".$i;
        }

        $sql1 =
        "
        select kodeprod, namaprod ".$newmoon1." from(
        select kodeprod, namaprod ".$newmoon2." from mpm.sum".$year."
        union all
        select tab.kodeprod, tab.namaprod ".$newmoon2." from mpm.tabprod tab
        left join
        (
          select map.kode_bsp, map.kodeprod ".$newmoon2." from bsp.mapprod map
          left join
          (
            select kode_bsp ".$newmoon1." from ( select kode_bsp ".$newmoon3." from bsp.bspsales".$year.")a group by kode_bsp
          )
          a using (kode_bsp)
        )b using (kodeprod) order by kodeprod
        )
        finale group by kodeprod
        ";


        $query = $this->db->query($sql1);


        if($query->num_rows() > 0)
        {
            $this->load->library('table');
            $this->table->set_empty('0');

            $this->table->set_template($this->print);
            $this->table->set_caption('Sell Out Consolidation');

            $this->table->set_heading('Kode','Produk','Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember');
            foreach ($query->result() as $value)
            {
                $this->table->add_row($value->kodeprod
                        ,$value->namaprod
                        ,$value->b1
                        ,$value->b2
                        ,$value->b3
                        ,$value->b4
                        ,$value->b5
                        ,$value->b6
                        ,$value->b7
                        ,$value->b8
                        ,$value->b9
                        ,$value->b10
                        ,$value->b11
                        ,$value->b12);
            }
            $this->output_print .= $this->table->generate();
            $this->output_print .= '<br />';
            $this->table->clear();
            return $this->output_print;
        }
        else
        {
            return FALSE;
        }
    }
    public function sales_konsolidasi($limit=null,$offset=null,$year=null)
    {
        $newmoon1='';
        $newmoon2='';
        $newmoon3='';
        for($i=1;$i<=12;$i++)
        {
                $newmoon1 .=",sum(b".$i.") as b".$i;
        }

        for($i=1;$i<=12;$i++)
        {
                $newmoon2 .=",b".$i;
        }
        for($i=1;$i<=12;$i++)
        {
                $newmoon3 .=",if(month(tgldokjdi)='".$i."',banyak,0) as b".$i;
        }

        $sql1 =
        "
        select kodeprod, namaprod ".$newmoon1." from(
        select kodeprod, namaprod ".$newmoon2." from mpm.sum".$year."
        union all
        select tab.kodeprod, tab.namaprod ".$newmoon2." from mpm.tabprod tab
        left join
        (
          select map.kode_bsp, map.kodeprod ".$newmoon2." from bsp.mapprod map
          left join
          (
            select kode_bsp ".$newmoon1." from ( select kode_bsp ".$newmoon3." from bsp.bspsales".$year.")a group by kode_bsp
          )
          a using (kode_bsp)
        )b using (kodeprod) order by kodeprod
        )
        finale group by kodeprod
        ";


        $sql2 = $sql1." limit ? offset ?";
        $query = $this->db->query($sql1);
        $this->total_query = $query->num_rows();

        $query = $this->db->query($sql2,array($limit,$offset));

        if($query->num_rows() > 0)
        {
            $this->load->library('table');
            $this->table->set_empty('0');

            $this->table->set_template($this->tmpl);
            $this->table->set_caption('Sell Out Consolidation');

            $this->table->set_heading('Kode','Produk','Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember');
            foreach ($query->result() as $value)
            {
                $this->table->add_row($value->kodeprod
                        ,$value->namaprod
                        ,$value->b1
                        ,$value->b2
                        ,$value->b3
                        ,$value->b4
                        ,$value->b5
                        ,$value->b6
                        ,$value->b7
                        ,$value->b8
                        ,$value->b9
                        ,$value->b10
                        ,$value->b11
                        ,$value->b12);
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
    public function print_bsp($year=null)
    {
        $newmoon1='';
        $newmoon2='';
        for($i=1;$i<=12;$i++)
        {
            $newmoon1 .=",sum(b".$i.") as b".$i;
        }

        for($i=1;$i<=12;$i++)
        {
            $newmoon2 .=",if(month(tgldokjdi)='".$i."',banyak,0) as b".$i;
        }

        echo $sql1 =
        "
        select deskripsi
        ".$newmoon1."
        from
        (
        select deskripsi
        ".$newmoon2."
        from bsp.bspsales".$year."
        )a group by deskripsi
        ";

        $query = $this->db->query($sql1);

        if($query->num_rows() > 0)
        {
            $this->load->library('table');
            $this->table->set_empty('0');

            $this->table->set_template($this->print);
            $this->table->set_caption('Sell Out BSP');

            $this->table->set_heading('Deskripsi','Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember');
            foreach ($query->result() as $value)
            {
                $this->table->add_row($value->deskripsi
                        ,$value->b1
                        ,$value->b2
                        ,$value->b3
                        ,$value->b4
                        ,$value->b5
                        ,$value->b6
                        ,$value->b7
                        ,$value->b8
                        ,$value->b9
                        ,$value->b10
                        ,$value->b11
                        ,$value->b12);
            }
            $this->output_print .= $this->table->generate();
            $this->output_print .= '<br />';
            $this->table->clear();
            return $this->output_print;
        }
        else
        {
            return FALSE;
        }
    }
    public function sales_bsp($limit=null,$offset=null,$year=0)
    {
        $newmoon1='';
        $newmoon2='';
        $year2= (int)$year - 1;
        for($i=1;$i<=12;$i++)
        {
            $newmoon1 .=",format(sum(b".$i."),0) as b".$i;
        }

        for($i=1;$i<=12;$i++)
        {
            $newmoon2 .=",if(month(tgldokjdi)='".$i."',banyak,0) as b".$i;
        }
        if($year=='2010')
        {
        $sql1 =
        "
        select deskripsi,0 as total,0 as rata2
        ".$newmoon1."
        from
        (
        select deskripsi
        ".$newmoon2."
        from bsp.bspsales".$year."
        )a
        group by deskripsi
        ";
        }
        else
        {
        $sql1 =
        "
        select deskripsi,b.total,b.rata2
        ".$newmoon1."
        from
        (
        select deskripsi
        ".$newmoon2."
        from bsp.bspsales".$year."
        )a
        inner join
        (select deskripsi,format(sum(banyak),0) as total, format(sum(banyak)/12,2) as rata2 from bsp.bspsales".$year2." group by deskripsi) b using(deskripsi)
        group by deskripsi
        ";
        }


        $sql2 = $sql1." order by deskripsi limit ? offset ?";
        $query = $this->db->query($sql1);
        $this->total_query = $query->num_rows();

        $query = $this->db->query($sql2,array($limit,$offset));

        if($query->num_rows() > 0)
        {
            $this->load->library('table');
            $this->table->set_empty('0');

            $this->table->set_template($this->tmpl);
            $this->table->set_caption('Sell Out BSP');

            $this->table->set_heading('Deskripsi','Total '.$year2,'Average '.$year2,'Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember');
            foreach ($query->result() as $value)
            {
                $this->table->add_row($value->deskripsi
                        ,$value->total
                        ,$value->rata2
                        ,$value->b1
                        ,$value->b2
                        ,$value->b3
                        ,$value->b4
                        ,$value->b5
                        ,$value->b6
                        ,$value->b7
                        ,$value->b8
                        ,$value->b9
                        ,$value->b10
                        ,$value->b11
                        ,$value->b12);
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

    function print_dp($year=null,$file=null)
    {
        $year2=(int)$year-1;
        $uv=$this->input->post('uv');
            switch($uv)
            {
                case 0:$unit='banyak';break;
                case 1:$unit='tot1';break;
            }
                $supp=$this->session->userdata('supp');
                if($supp=='000')
                {
                    $wheresupp='';
                }
                else if($supp=='005')
                {
                    $wheresupp='and kodeprod like "06%" ';
                }
                else
                {
                    $wheresupp="and supp=".$supp;
                }
        if($year!='2010')
        {
            $sql="select a.kodeprod, a.namaprod,format(b.rata,0) as rata,
            format(b1,0) as b1,format(b2,0) as b2,format(b3,0) as b3,
            format(b4,0) as b4,format(b5,0) as b5,format(b6,0) as b6,
            format(b7,0) as b7,format(b8,0) as b8,format(b9,0) as b9,
            format(b10,0) as b10,format(b11,0) as b11,format(b12,0) as b12
            from (
            select b.kodeprod, b.namaprod,
            sum(if(bulan=1,unit,0)) as b1,sum(if(bulan=2,unit,0)) as b2,
            sum(if(bulan=3,unit,0)) as b3,sum(if(bulan=4,unit,0)) as b4,
            sum(if(bulan=5,unit,0)) as b5,sum(if(bulan=6,unit,0)) as b6,
            sum(if(bulan=7,unit,0)) as b7,sum(if(bulan=8,unit,0)) as b8,
            sum(if(bulan=9,unit,0)) as b9,sum(if(bulan=10,unit,0)) as b10,
            sum(if(bulan=11,unit,0)) as b11,sum(if(bulan=12,unit,0)) as b12
            from
            (
            select kodeprod, namaprod, sum(".$unit.") as unit, bulan from data".$year.".fi where nodokjdi<>'XXXXXX' ".$wheresupp." group by kodeprod,bulan
            union all
            select kodeprod, namaprod, sum(".$unit.") as unit, bulan from data".$year.".ri where nodokjdi<>'XXXXXX' ".$wheresupp." group by kodeprod,bulan
            ) a
            inner join mpm.tabprod b using(kodeprod)
            group by kodeprod
            )
            a
            left join
            (
            SELECT kodeprod, sum(unit)/12 as rata
            from
            (
            select kodeprod,SUM(".$unit.") AS UNIT from data".$year2.".fi where nodokjdi<>'XXXXXX' ".$wheresupp." group by kodeprod
            UNION ALL
            select kodeprod,Sum(".$unit.")  AS UNIT from data".$year2.".ri where nodokjdi<>'XXXXXX' ".$wheresupp." group by kodeprod
            )
            a
            group by kodeprod

            ) b using (kodeprod)
            ";
        }
        else
        {
            $sql="select a.kodeprod, a.namaprod,0 as rata,
            format(b1,0) as b1,format(b2,0) as b2,format(b3,0) as b3,
            format(b4,0) as b4,format(b5,0) as b5,format(b6,0) as b6,
            format(b7,0) as b7,format(b8,0) as b8,format(b9,0) as b9,
            format(b10,0) as b10,format(b11,0) as b11,format(b12,0) as b12
            from (
                select b.kodeprod, b.namaprod,
                sum(if(bulan=1,unit,0)) as b1,sum(if(bulan=2,unit,0)) as b2,
                sum(if(bulan=3,unit,0)) as b3,sum(if(bulan=4,unit,0)) as b4,
                sum(if(bulan=5,unit,0)) as b5,sum(if(bulan=6,unit,0)) as b6,
                sum(if(bulan=7,unit,0)) as b7,sum(if(bulan=8,unit,0)) as b8,
                sum(if(bulan=9,unit,0)) as b9,sum(if(bulan=10,unit,0)) as b10,
                sum(if(bulan=11,unit,0)) as b11,sum(if(bulan=12,unit,0)) as b12
                from
                (
                        select kodeprod, namaprod, sum(".$unit.") as unit, bulan from data2010.fi where nodokjdi<>'XXXXXX' ".$wheresupp." group by kodeprod,bulan
                        union all
                        select kodeprod, namaprod, sum(".$unit.") as unit, bulan from data2010.ri where nodokjdi<>'XXXXXX' ".$wheresupp." group by kodeprod,bulan
                ) a
                inner join mpm.tabprod b using(kodeprod)
                group by kodeprod
            )
            a
            left join
            (
                select kodeprod,sum(bulan1+bulan2+bulan3+bulan4+bulan5+bulan6+bulan7+bulan8+bulan9+bulan10+bulan11+bulan12)/12  as rata from data2010.total2009 group by kodeprod
            )
            b using (kodeprod)
            group by kodeprod";
        }
        $query = $this->db->query($sql);
        //$this->total_query = $query->num_rows();
        switch(strtoupper($file))
        {
        case 'PDF':
        $this->load->library('cezpdf');
        $this->cezpdf->Cezpdf('A4','landscape');

        foreach($query->result() as $row)
        {
            $db_data[] = array(
                'code'  => $row->kodeprod,
                'name'  => $row->namaprod,
                'avg'   => $row->rata,
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
                );
        }
        $col_names = array(
        'code' => 'Code',
        'name' => 'Name',
        'avg' => 'AVG '.$year2,
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

        $this->cezpdf->ezTable($db_data, $col_names, 'Sell Out '.$year, array('width'=>10,'fontSize' => 6));
        $this->cezpdf->ezStream();

        break;
        case 'EXCEL':
            return to_excel($query,'SO'.$year);
            break;
        }
        /*
        if($query->num_rows() > 0)
        {
            $this->load->library('table');
            $this->table->set_empty('0');

            $this->table->set_template($this->print);
            $this->table->set_caption('Sell Out DP');

            $this->table->set_heading('Kode Produk', 'Nama Produk', 'Rata2 '.$year2,'Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember');

            $this->total_query = $query->num_rows();
            foreach ($query->result() as $value)
            {
                $this->table->add_row(
                        $value->kodeprod
                        ,$value->namaprod
                        ,$value->rata
                        ,$value->b1
                        ,$value->b2
                        ,$value->b3
                        ,$value->b4
                        ,$value->b5
                        ,$value->b6
                        ,$value->b7
                        ,$value->b8
                        ,$value->b9
                        ,$value->b10
                        ,$value->b11
                        ,$value->b12
                       );
            }
            $this->print_table .= $this->table->generate();
            $this->print_table .= '<br />';
            $this->table->clear();

            return $this->print_table;
        }
        else
        {
            return FALSE;
        }
        //$this->benchmark->mark('movie_list_end');
         *
         */
    }
    //Tambahan Tizar
    function print_dp_timur($year=null,$file=null)
    {
        $year2=(int)$year-1;
        $uv=$this->input->post('uv');
            switch($uv)
            {
                case 0:$unit='banyak';break;
                case 1:$unit='tot1';break;
            }
                $supp=$this->session->userdata('supp');
                if($supp=='000')
                {
                    $wheresupp='';
                }
                else if($supp=='005')
                {
                    $wheresupp='and kodeprod like "06%" ';
                }
                else
                {
                    $wheresupp="and supp=".$supp;
                }
        if($year!='2010')
        {
            $sql="SELECT  kodeprod, namaprod, FORMAT(rata,0) as rata, FORMAT(b1,0) as b1,FORMAT(b2,0) as b2,
            FORMAT(b3,0) as b3,FORMAT(b4,0) as b4,FORMAT(b5,0) as b5,FORMAT(b6,0) as b6,FORMAT(b7,0) as b7,
            FORMAT(b8,0) as b8,FORMAT(b9,0) as b9,FORMAT(b10,0) as b10,FORMAT(b11,0) as b11,FORMAT(b12,0) as b12 FROM (
            SELECT g.KODEPROD, g.NAMAPROD, tot/12 as rata, b1,b2,b3,b4,b5,b6,b7,b8,b9,b10,b11,b12 FROM(
            SELECT NOCAB, KODEPROD, NAMAPROD,
            SUM(IF(THNDOK=".$year2.",v,0))Tot,
            SUM(IF(BULAN=01 and THNDOK=".$year.",v,0)) b1,
            SUM(IF(BULAN=02 and THNDOK=".$year.",v,0)) b2,
            SUM(IF(BULAN=03 and THNDOK=".$year.",v,0)) b3, 
            SUM(IF(BULAN=04 and THNDOK=".$year.",v,0)) b4, 
            SUM(IF(BULAN=05 and THNDOK=".$year.",v,0)) b5, 
            SUM(IF(BULAN=06 and THNDOK=".$year.",v,0)) b6,
            SUM(IF(BULAN=07 and THNDOK=".$year.",v,0)) b7, 
            SUM(IF(BULAN=08 and THNDOK=".$year.",v,0)) b8, 
            SUM(IF(BULAN=09 and THNDOK=".$year.",v,0)) b9,
            SUM(IF(BULAN=10 and THNDOK=".$year.",v,0)) b10,
            SUM(IF(BULAN=11 and THNDOK=".$year.",v,0)) b11,
            SUM(IF(BULAN=12 and THNDOK=".$year.",v,0)) b12
            FROM (
            select nocab, KODEPROD, NAMAPROD, bulan, sum(v)v, THNDOK from (
            select nocab, kodeprod, namaprod, sum(".$unit.")v, BULAN,THNDOK from data".$year2.".fi where nodokjdi<>'XXXXXX' ".$wheresupp." GROUP BY NOCAB, KODEPROD
            union all
            select nocab, kodeprod, namaprod, sum(".$unit.")v, BULAN,THNDOK from data".$year2.".ri where nodokjdi<>'XXXXXX' ".$wheresupp." GROUP BY NOCAB, KODEPROD
            )a GROUP BY NOCAB, KODEPROD, thndok
            UNION ALL
            select nocab, KODEPROD, NAMAPROD, bulan, sum(v)v, THNDOK from (
            select nocab, kodeprod, namaprod, sum(".$unit.")v, BULAN, THNDOK from data".$year.".fi where nodokjdi<>'XXXXXX' ".$wheresupp." GROUP BY NOCAB, KODEPROD,BULAN
            union all
            select nocab, kodeprod, namaprod, sum(".$unit.")v, BULAN , THNDOK from data".$year.".ri where nodokjdi<>'XXXXXX' ".$wheresupp." GROUP BY NOCAB, KODEPROD, BULAN
            )a GROUP BY NOCAB, KODEPROD, BULAN, THNDOK
            )d INNER JOIN mpm.tabcomp e USING (NOCAB) WHERE WILAYAH in (2) GROUP BY KODEPROD
            )f INNER JOIN mpm.tabprod g USING (KODEPROD) GROUP BY KODEPROD
            )h GROUP BY KODEPROD
            ";
        }
        else
        {
            $sql="select a.kodeprod, a.namaprod,0 as rata,
            format(b1,0) as b1,format(b2,0) as b2,format(b3,0) as b3,
            format(b4,0) as b4,format(b5,0) as b5,format(b6,0) as b6,
            format(b7,0) as b7,format(b8,0) as b8,format(b9,0) as b9,
            format(b10,0) as b10,format(b11,0) as b11,format(b12,0) as b12
            from (
                select b.kodeprod, b.namaprod,
                sum(if(bulan=1,unit,0)) as b1,sum(if(bulan=2,unit,0)) as b2,
                sum(if(bulan=3,unit,0)) as b3,sum(if(bulan=4,unit,0)) as b4,
                sum(if(bulan=5,unit,0)) as b5,sum(if(bulan=6,unit,0)) as b6,
                sum(if(bulan=7,unit,0)) as b7,sum(if(bulan=8,unit,0)) as b8,
                sum(if(bulan=9,unit,0)) as b9,sum(if(bulan=10,unit,0)) as b10,
                sum(if(bulan=11,unit,0)) as b11,sum(if(bulan=12,unit,0)) as b12
                from
                (
                        select kodeprod, namaprod, sum(".$unit.") as unit, bulan from data2010.fi where nodokjdi<>'XXXXXX' ".$wheresupp." group by kodeprod,bulan
                        union all
                        select kodeprod, namaprod, sum(".$unit.") as unit, bulan from data2010.ri where nodokjdi<>'XXXXXX' ".$wheresupp." group by kodeprod,bulan
                ) a
                inner join mpm.tabprod b using(kodeprod)
                group by kodeprod
            )
            a
            left join
            (
                select kodeprod,sum(bulan1+bulan2+bulan3+bulan4+bulan5+bulan6+bulan7+bulan8+bulan9+bulan10+bulan11+bulan12)/12  as rata from data2010.total2009 group by kodeprod
            )
            b using (kodeprod)
            group by kodeprod";
        }
        $query = $this->db->query($sql);
        //$this->total_query = $query->num_rows();
        switch(strtoupper($file))
        {
        case 'PDF':
        $this->load->library('cezpdf');
        $this->cezpdf->Cezpdf('A4','landscape');

        foreach($query->result() as $row)
        {
            $db_data[] = array(
                'code'  => $row->kodeprod,
                'name'  => $row->namaprod,
                'avg'   => $row->rata,
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
                );
        }
        $col_names = array(
        'code' => 'Code',
        'name' => 'Name',
        'avg' => 'AVG '.$year2,
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

        $this->cezpdf->ezTable($db_data, $col_names, 'Sell Out '.$year, array('width'=>10,'fontSize' => 6));
        $this->cezpdf->ezStream();

        break;
        case 'EXCEL':
            return to_excel($query,'SO'.$year);
            break;
        }
        /*
        if($query->num_rows() > 0)
        {
            $this->load->library('table');
            $this->table->set_empty('0');

            $this->table->set_template($this->print);
            $this->table->set_caption('Sell Out DP');

            $this->table->set_heading('Kode Produk', 'Nama Produk', 'Rata2 '.$year2,'Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember');

            $this->total_query = $query->num_rows();
            foreach ($query->result() as $value)
            {
                $this->table->add_row(
                        $value->kodeprod
                        ,$value->namaprod
                        ,$value->rata
                        ,$value->b1
                        ,$value->b2
                        ,$value->b3
                        ,$value->b4
                        ,$value->b5
                        ,$value->b6
                        ,$value->b7
                        ,$value->b8
                        ,$value->b9
                        ,$value->b10
                        ,$value->b11
                        ,$value->b12
                       );
            }
            $this->print_table .= $this->table->generate();
            $this->print_table .= '<br />';
            $this->table->clear();

            return $this->print_table;
        }
        else
        {
            return FALSE;
        }
        //$this->benchmark->mark('movie_list_end');
         *
         */
    }
    //Tambahan Tizar
    function print_dp_barat($year=null,$file=null)
    {
        $year2=(int)$year-1;
        $uv=$this->input->post('uv');
            switch($uv)
            {
                case 0:$unit='banyak';break;
                case 1:$unit='tot1';break;
            }
                $supp=$this->session->userdata('supp');
                if($supp=='000')
                {
                    $wheresupp='';
                }
                else if($supp=='005')
                {
                    $wheresupp='and kodeprod like "06%" ';
                }
                else
                {
                    $wheresupp="and supp=".$supp;
                }
        if($year!='2010')
        {
            $sql="SELECT  kodeprod, namaprod, FORMAT(rata,0) as rata, FORMAT(b1,0) as b1,FORMAT(b2,0) as b2,
            FORMAT(b3,0) as b3,FORMAT(b4,0) as b4,FORMAT(b5,0) as b5,FORMAT(b6,0) as b6,FORMAT(b7,0) as b7,
            FORMAT(b8,0) as b8,FORMAT(b9,0) as b9,FORMAT(b10,0) as b10,FORMAT(b11,0) as b11,FORMAT(b12,0) as b12 FROM (
            SELECT g.KODEPROD, g.NAMAPROD, tot/12 as rata, b1,b2,b3,b4,b5,b6,b7,b8,b9,b10,b11,b12 FROM(
            SELECT NOCAB, KODEPROD, NAMAPROD,
            SUM(IF(THNDOK=".$year2.",v,0))Tot,
            SUM(IF(BULAN=01 and THNDOK=".$year.",v,0)) b1,
            SUM(IF(BULAN=02 and THNDOK=".$year.",v,0)) b2,
            SUM(IF(BULAN=03 and THNDOK=".$year.",v,0)) b3, 
            SUM(IF(BULAN=04 and THNDOK=".$year.",v,0)) b4, 
            SUM(IF(BULAN=05 and THNDOK=".$year.",v,0)) b5, 
            SUM(IF(BULAN=06 and THNDOK=".$year.",v,0)) b6,
            SUM(IF(BULAN=07 and THNDOK=".$year.",v,0)) b7, 
            SUM(IF(BULAN=08 and THNDOK=".$year.",v,0)) b8, 
            SUM(IF(BULAN=09 and THNDOK=".$year.",v,0)) b9,
            SUM(IF(BULAN=10 and THNDOK=".$year.",v,0)) b10,
            SUM(IF(BULAN=11 and THNDOK=".$year.",v,0)) b11,
            SUM(IF(BULAN=12 and THNDOK=".$year.",v,0)) b12
            FROM (
            select nocab, KODEPROD, NAMAPROD, bulan, sum(v)v, THNDOK from (
            select nocab, kodeprod, namaprod, sum(".$unit.")v, BULAN,THNDOK from data".$year2.".fi where nodokjdi<>'XXXXXX' ".$wheresupp." GROUP BY NOCAB, KODEPROD
            union all
            select nocab, kodeprod, namaprod, sum(".$unit.")v, BULAN,THNDOK from data".$year2.".ri where nodokjdi<>'XXXXXX' ".$wheresupp." GROUP BY NOCAB, KODEPROD
            )a GROUP BY NOCAB, KODEPROD, thndok
            UNION ALL
            select nocab, KODEPROD, NAMAPROD, bulan, sum(v)v, THNDOK from (
            select nocab, kodeprod, namaprod, sum(".$unit.")v, BULAN, THNDOK from data".$year.".fi where nodokjdi<>'XXXXXX' ".$wheresupp." GROUP BY NOCAB, KODEPROD,BULAN
            union all
            select nocab, kodeprod, namaprod, sum(".$unit.")v, BULAN , THNDOK from data".$year.".ri where nodokjdi<>'XXXXXX' ".$wheresupp." GROUP BY NOCAB, KODEPROD, BULAN
            )a GROUP BY NOCAB, KODEPROD, BULAN, THNDOK
            )d INNER JOIN mpm.tabcomp e USING (NOCAB) WHERE WILAYAH in (1) GROUP BY KODEPROD
            )f INNER JOIN mpm.tabprod g USING (KODEPROD) GROUP BY KODEPROD
            )h GROUP BY KODEPROD
            ";
        }
        else
        {
            $sql="select a.kodeprod, a.namaprod,0 as rata,
            format(b1,0) as b1,format(b2,0) as b2,format(b3,0) as b3,
            format(b4,0) as b4,format(b5,0) as b5,format(b6,0) as b6,
            format(b7,0) as b7,format(b8,0) as b8,format(b9,0) as b9,
            format(b10,0) as b10,format(b11,0) as b11,format(b12,0) as b12
            from (
                select b.kodeprod, b.namaprod,
                sum(if(bulan=1,unit,0)) as b1,sum(if(bulan=2,unit,0)) as b2,
                sum(if(bulan=3,unit,0)) as b3,sum(if(bulan=4,unit,0)) as b4,
                sum(if(bulan=5,unit,0)) as b5,sum(if(bulan=6,unit,0)) as b6,
                sum(if(bulan=7,unit,0)) as b7,sum(if(bulan=8,unit,0)) as b8,
                sum(if(bulan=9,unit,0)) as b9,sum(if(bulan=10,unit,0)) as b10,
                sum(if(bulan=11,unit,0)) as b11,sum(if(bulan=12,unit,0)) as b12
                from
                (
                        select kodeprod, namaprod, sum(".$unit.") as unit, bulan from data2010.fi where nodokjdi<>'XXXXXX' ".$wheresupp." group by kodeprod,bulan
                        union all
                        select kodeprod, namaprod, sum(".$unit.") as unit, bulan from data2010.ri where nodokjdi<>'XXXXXX' ".$wheresupp." group by kodeprod,bulan
                ) a
                inner join mpm.tabprod b using(kodeprod)
                group by kodeprod
            )
            a
            left join
            (
                select kodeprod,sum(bulan1+bulan2+bulan3+bulan4+bulan5+bulan6+bulan7+bulan8+bulan9+bulan10+bulan11+bulan12)/12  as rata from data2010.total2009 group by kodeprod
            )
            b using (kodeprod)
            group by kodeprod";
        }
        $query = $this->db->query($sql);
        //$this->total_query = $query->num_rows();
        switch(strtoupper($file))
        {
        case 'PDF':
        $this->load->library('cezpdf');
        $this->cezpdf->Cezpdf('A4','landscape');

        foreach($query->result() as $row)
        {
            $db_data[] = array(
                'code'  => $row->kodeprod,
                'name'  => $row->namaprod,
                'avg'   => $row->rata,
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
                );
        }
        $col_names = array(
        'code' => 'Code',
        'name' => 'Name',
        'avg' => 'AVG '.$year2,
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

        $this->cezpdf->ezTable($db_data, $col_names, 'Sell Out '.$year, array('width'=>10,'fontSize' => 6));
        $this->cezpdf->ezStream();

        break;
        case 'EXCEL':
            return to_excel($query,'SO'.$year);
            break;
        }
        /*
        if($query->num_rows() > 0)
        {
            $this->load->library('table');
            $this->table->set_empty('0');

            $this->table->set_template($this->print);
            $this->table->set_caption('Sell Out DP');

            $this->table->set_heading('Kode Produk', 'Nama Produk', 'Rata2 '.$year2,'Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember');

            $this->total_query = $query->num_rows();
            foreach ($query->result() as $value)
            {
                $this->table->add_row(
                        $value->kodeprod
                        ,$value->namaprod
                        ,$value->rata
                        ,$value->b1
                        ,$value->b2
                        ,$value->b3
                        ,$value->b4
                        ,$value->b5
                        ,$value->b6
                        ,$value->b7
                        ,$value->b8
                        ,$value->b9
                        ,$value->b10
                        ,$value->b11
                        ,$value->b12
                       );
            }
            $this->print_table .= $this->table->generate();
            $this->print_table .= '<br />';
            $this->table->clear();

            return $this->print_table;
        }
        else
        {
            return FALSE;
        }
        //$this->benchmark->mark('movie_list_end');
         *
         */
    }
    //Tambahan Tizar
    public function sales_dp_barat($limit=null,$offset=null,$year=null,$retur=false)
    {
        $year2=(int)$year-1;
        $id=$this->session->userdata('id');

        $sql='select 1 from mpm.sounit where id=?';
        $query = $this->db->query($sql,array($id));
        
        if($query->num_rows()>0)
        {
            $sql='select * from (
                  select * from mpm.sounit where id='.$id.' 
                  union all
                  select "GRAND","TOTAL",
                  sum(rata),
                  sum(b1),
                  sum(b2),
                  sum(b3),
                  sum(b4),
                  sum(b5),
                  sum(b6),
                  sum(b7),
                  sum(b8),
                  sum(b9),
                  sum(b10),
                  sum(b11),
                  sum(b12),"" from mpm.sounit where id='.$id.')a order by kodeprod';
                   
            
            $query = $this->db->query($sql);
            $sql2  = $sql.' limit ? offset ?';
            $this->total_query = $query->num_rows();
            $query = $this->db->query($sql2,array($limit,$offset));
        }
        else
        {
            $uv=$this->input->post('uv');
            switch($uv)
            {
                case 0:$unit='banyak';break;
                case 1:$unit='tot1';break;
            }
            $supp=$this->session->userdata('supp');
            if($supp=='000')
            {
                $wheresupp='';
            }
            else if($supp=='001')
            {
                $wheresupp="and kodeprod like '01%' or kodeprod like '60%' or kodeprod like '50%'";
            }
            else if($supp=='005')
            {
                $wheresupp=' and kodeprod like "06%" ';
            }
            else
            {
                $wheresupp=" and kodeprod like '".substr($supp,-2)."%'";
            }
            if($year!='2010')
            {
       /*         $sql="insert into mpm.sounit select a.kodeprod, a.namaprod,b.rata as rata,
                b1,b2,b3,b4,b5,b6,b7,b8,b9,b10,b11,b12,".$id."
                from (
                select b.kodeprod, b.namaprod,
                sum(if(bulan=1,unit,0)) as b1,sum(if(bulan=2,unit,0)) as b2,
                sum(if(bulan=3,unit,0)) as b3,sum(if(bulan=4,unit,0)) as b4,
                sum(if(bulan=5,unit,0)) as b5,sum(if(bulan=6,unit,0)) as b6,
                sum(if(bulan=7,unit,0)) as b7,sum(if(bulan=8,unit,0)) as b8,
                sum(if(bulan=9,unit,0)) as b9,sum(if(bulan=10,unit,0)) as b10,
                sum(if(bulan=11,unit,0)) as b11,sum(if(bulan=12,unit,0)) as b12
                from
                (
                select kodeprod, namaprod, sum(".$unit.") as unit, bulan from data".$year.".fi where nodokjdi<>'XXXXXX' ".$wheresupp." group by kodeprod,bulan
                union all
                select kodeprod, namaprod, sum(".$unit.") as unit, bulan from data".$year.".ri where nodokjdi<>'XXXXXX' ".$wheresupp." group by kodeprod,bulan
                ) a
                inner join mpm.tabprod b using(kodeprod)
                group by kodeprod
                )
                a
                left join
                (
                SELECT kodeprod, sum(unit)/12 as rata
                from
                (
                select kodeprod,SUM(".$unit.") AS UNIT from data".$year2.".fi where nodokjdi<>'XXXXXX' ".$wheresupp." group by kodeprod
                UNION ALL
                select kodeprod,Sum(".$unit.")  AS UNIT from data".$year2.".ri where nodokjdi<>'XXXXXX'".$wheresupp." group by kodeprod
                )
                a
                group by kodeprod

                ) b using (kodeprod)";
        
        */
                $sql="insert into mpm.sounit SELECT g.KODEPROD, g.NAMAPROD, tot/12 as rata, b1,b2,b3,b4,b5,b6,b7,b8,b9,b10,b11,b12,".$id."
                    FROM(
                SELECT NOCAB, KODEPROD, NAMAPROD,
                SUM(IF(THNDOK=".$year2.",v,0))Tot,
                SUM(IF(BULAN=01 and THNDOK=".$year.",v,0)) b1,
                SUM(IF(BULAN=02 and THNDOK=".$year.",v,0)) b2,
                SUM(IF(BULAN=03 and THNDOK=".$year.",v,0)) b3, 
                SUM(IF(BULAN=04 and THNDOK=".$year.",v,0)) b4, 
                SUM(IF(BULAN=05 and THNDOK=".$year.",v,0)) b5, 
                SUM(IF(BULAN=06 and THNDOK=".$year.",v,0)) b6,
                SUM(IF(BULAN=07 and THNDOK=".$year.",v,0)) b7, 
                SUM(IF(BULAN=08 and THNDOK=".$year.",v,0)) b8, 
                SUM(IF(BULAN=09 and THNDOK=".$year.",v,0)) b9,
                SUM(IF(BULAN=10 and THNDOK=".$year.",v,0)) b10,
                SUM(IF(BULAN=11 and THNDOK=".$year.",v,0)) b11,
                SUM(IF(BULAN=12 and THNDOK=".$year.",v,0)) b12
                FROM (
                select nocab, KODEPROD, NAMAPROD, bulan, sum(v)v, THNDOK from (
                select nocab, kodeprod, namaprod, sum(".$unit.")v, BULAN,THNDOK from data".$year2.".fi where nodokjdi<>'XXXXXX' ".$wheresupp." GROUP BY NOCAB, KODEPROD
                union all
                select nocab, kodeprod, namaprod, sum(".$unit.")v, BULAN,THNDOK from data".$year2.".ri where nodokjdi<>'XXXXXX' ".$wheresupp." GROUP BY NOCAB, KODEPROD
                )a GROUP BY NOCAB, KODEPROD, thndok
                UNION ALL
                select nocab, KODEPROD, NAMAPROD, bulan, sum(v)v, THNDOK from (
                select nocab, kodeprod, namaprod, sum(".$unit.")v, BULAN, THNDOK from data".$year.".fi where nodokjdi<>'XXXXXX' ".$wheresupp." GROUP BY NOCAB, KODEPROD,BULAN
                union all
                select nocab, kodeprod, namaprod, sum(".$unit.")v, BULAN , THNDOK from data".$year.".ri where nodokjdi<>'XXXXXX' ".$wheresupp." GROUP BY NOCAB, KODEPROD, BULAN
                )a GROUP BY NOCAB, KODEPROD, BULAN, THNDOK
                )d INNER JOIN mpm.tabcomp e USING (NOCAB) WHERE WILAYAH in (1) GROUP BY KODEPROD
                )f INNER JOIN mpm.tabprod g USING (KODEPROD) GROUP BY KODEPROD ";
                $query = $this->db->query($sql);
                $sql='select * from mpm.sounit where id='.$id.' order by kodeprod';
                $query = $this->db->query($sql);
                $sql2  = $sql.' limit ? offset ?';
                $query = $this->db->query($sql);
                $this->total_query = $query->num_rows();
                $query = $this->db->query($sql2,array($limit,$offset));
            }
            else
            {
                $sql="insert into mpm.sounit select a.kodeprod, a.namaprod,0 as rata,
                b1,b2,b3,b4,b5,b6,b6,b7,b8,b9,b10,b11,b12,".$id."
                from (
                    select b.kodeprod, b.namaprod,
                    sum(if(bulan=1,unit,0)) as b1,sum(if(bulan=2,unit,0)) as b2,
                    sum(if(bulan=3,unit,0)) as b3,sum(if(bulan=4,unit,0)) as b4,
                    sum(if(bulan=5,unit,0)) as b5,sum(if(bulan=6,unit,0)) as b6,
                    sum(if(bulan=7,unit,0)) as b7,sum(if(bulan=8,unit,0)) as b8,
                    sum(if(bulan=9,unit,0)) as b9,sum(if(bulan=10,unit,0)) as b10,
                    sum(if(bulan=11,unit,0)) as b11,sum(if(bulan=12,unit,0)) as b12
                    from
                    (
                            select kodeprod, namaprod, sum(".$unit.") as unit, bulan from data2010.fi where nodokjdi<>'XXXXXX' group by kodeprod,bulan
                            union all
                            select kodeprod, namaprod, sum(".$unit.") as unit, bulan from data2010.ri where nodokjdi<>'XXXXXX' group by kodeprod,bulan
                    ) a
                    inner join mpm.tabprod b using(kodeprod)
                    group by kodeprod
                )
                a
                left join
                (
                    select kodeprod,sum(bulan1+bulan2+bulan3+bulan4+bulan5+bulan6+bulan7+bulan8+bulan9+bulan10+bulan11+bulan12)/12  as rata from data2010.total2009 group by kodeprod
                )
                b using (kodeprod)
                group by kodeprod";
            }
            $query = $this->db->query($sql);
            $sql='select * from mpm.sounit where id='.$id.' order by kodeprod';
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
            $this->table->set_caption('Sell Out DP Barat');

            $this->table->set_heading('Kode Produk', 'Nama Produk', 'Rata2 '.$year2,'Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember');

            foreach ($query->result() as $value)
            {
                $this->table->add_row(
                        $value->kodeprod
                        ,$value->namaprod
                        ,'<div div style="text-align:right">' . number_format($value->rata,0) . '</div>'
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
    
    //Tambahan Tizar
    public function sales_dp_timur($limit=null,$offset=null,$year=null,$retur=false)
    {
        $year2=(int)$year-1;
        $id=$this->session->userdata('id');

        $sql='select 1 from mpm.sounit where id=?';
        $query = $this->db->query($sql,array($id));
        
        if($query->num_rows()>0)
        {
            $sql='select * from (
                  select * from mpm.sounit where id='.$id.' 
                  union all
                  select "GRAND","TOTAL",
                  sum(rata),
                  sum(b1),
                  sum(b2),
                  sum(b3),
                  sum(b4),
                  sum(b5),
                  sum(b6),
                  sum(b7),
                  sum(b8),
                  sum(b9),
                  sum(b10),
                  sum(b11),
                  sum(b12),"" from mpm.sounit where id='.$id.')a order by kodeprod';
                   
            
            $query = $this->db->query($sql);
            $sql2  = $sql.' limit ? offset ?';
            $this->total_query = $query->num_rows();
            $query = $this->db->query($sql2,array($limit,$offset));
        }
        else
        {
            $uv=$this->input->post('uv');
            switch($uv)
            {
                case 0:$unit='banyak';break;
                case 1:$unit='tot1';break;
            }
            $supp=$this->session->userdata('supp');
            if($supp=='000')
            {
                $wheresupp='';
            }
            else if($supp=='001')
            {
                $wheresupp="and kodeprod like '01%' or kodeprod like '60%' or kodeprod like '50%'";
            }
            else if($supp=='005')
            {
                $wheresupp=' and kodeprod like "06%" ';
            }
            else
            {
                $wheresupp=" and kodeprod like '".substr($supp,-2)."%'";
            }
            if($year!='2010')
            {
       /*         $sql="insert into mpm.sounit select a.kodeprod, a.namaprod,b.rata as rata,
                b1,b2,b3,b4,b5,b6,b7,b8,b9,b10,b11,b12,".$id."
                from (
                select b.kodeprod, b.namaprod,
                sum(if(bulan=1,unit,0)) as b1,sum(if(bulan=2,unit,0)) as b2,
                sum(if(bulan=3,unit,0)) as b3,sum(if(bulan=4,unit,0)) as b4,
                sum(if(bulan=5,unit,0)) as b5,sum(if(bulan=6,unit,0)) as b6,
                sum(if(bulan=7,unit,0)) as b7,sum(if(bulan=8,unit,0)) as b8,
                sum(if(bulan=9,unit,0)) as b9,sum(if(bulan=10,unit,0)) as b10,
                sum(if(bulan=11,unit,0)) as b11,sum(if(bulan=12,unit,0)) as b12
                from
                (
                select kodeprod, namaprod, sum(".$unit.") as unit, bulan from data".$year.".fi where nodokjdi<>'XXXXXX' ".$wheresupp." group by kodeprod,bulan
                union all
                select kodeprod, namaprod, sum(".$unit.") as unit, bulan from data".$year.".ri where nodokjdi<>'XXXXXX' ".$wheresupp." group by kodeprod,bulan
                ) a
                inner join mpm.tabprod b using(kodeprod)
                group by kodeprod
                )
                a
                left join
                (
                SELECT kodeprod, sum(unit)/12 as rata
                from
                (
                select kodeprod,SUM(".$unit.") AS UNIT from data".$year2.".fi where nodokjdi<>'XXXXXX' ".$wheresupp." group by kodeprod
                UNION ALL
                select kodeprod,Sum(".$unit.")  AS UNIT from data".$year2.".ri where nodokjdi<>'XXXXXX'".$wheresupp." group by kodeprod
                )
                a
                group by kodeprod

                ) b using (kodeprod)";
        
        */
                $sql="insert into mpm.sounit SELECT g.KODEPROD, g.NAMAPROD, tot/12 as rata, b1,b2,b3,b4,b5,b6,b7,b8,b9,b10,b11,b12,".$id."
                    FROM(
                SELECT NOCAB, KODEPROD, NAMAPROD,
                SUM(IF(THNDOK=".$year2.",v,0))Tot,
                SUM(IF(BULAN=01 and THNDOK=".$year.",v,0)) b1,
                SUM(IF(BULAN=02 and THNDOK=".$year.",v,0)) b2,
                SUM(IF(BULAN=03 and THNDOK=".$year.",v,0)) b3, 
                SUM(IF(BULAN=04 and THNDOK=".$year.",v,0)) b4, 
                SUM(IF(BULAN=05 and THNDOK=".$year.",v,0)) b5, 
                SUM(IF(BULAN=06 and THNDOK=".$year.",v,0)) b6,
                SUM(IF(BULAN=07 and THNDOK=".$year.",v,0)) b7, 
                SUM(IF(BULAN=08 and THNDOK=".$year.",v,0)) b8, 
                SUM(IF(BULAN=09 and THNDOK=".$year.",v,0)) b9,
                SUM(IF(BULAN=10 and THNDOK=".$year.",v,0)) b10,
                SUM(IF(BULAN=11 and THNDOK=".$year.",v,0)) b11,
                SUM(IF(BULAN=12 and THNDOK=".$year.",v,0)) b12
                FROM (
                select nocab, KODEPROD, NAMAPROD, bulan, sum(v)v, THNDOK from (
                select nocab, kodeprod, namaprod, sum(".$unit.")v, BULAN,THNDOK from data".$year2.".fi where nodokjdi<>'XXXXXX' ".$wheresupp." GROUP BY NOCAB, KODEPROD
                union all
                select nocab, kodeprod, namaprod, sum(".$unit.")v, BULAN,THNDOK from data".$year2.".ri where nodokjdi<>'XXXXXX' ".$wheresupp." GROUP BY NOCAB, KODEPROD
                )a GROUP BY NOCAB, KODEPROD, thndok
                UNION ALL
                select nocab, KODEPROD, NAMAPROD, bulan, sum(v)v, THNDOK from (
                select nocab, kodeprod, namaprod, sum(".$unit.")v, BULAN, THNDOK from data".$year.".fi where nodokjdi<>'XXXXXX' ".$wheresupp." GROUP BY NOCAB, KODEPROD,BULAN
                union all
                select nocab, kodeprod, namaprod, sum(".$unit.")v, BULAN , THNDOK from data".$year.".ri where nodokjdi<>'XXXXXX' ".$wheresupp." GROUP BY NOCAB, KODEPROD, BULAN
                )a GROUP BY NOCAB, KODEPROD, BULAN, THNDOK
                )d INNER JOIN mpm.tabcomp e USING (NOCAB) WHERE WILAYAH in (2) GROUP BY KODEPROD
                )f INNER JOIN mpm.tabprod g USING (KODEPROD) GROUP BY KODEPROD ";
                $query = $this->db->query($sql);
                $sql='select * from mpm.sounit where id='.$id.' order by kodeprod';
                $query = $this->db->query($sql);
                $sql2  = $sql.' limit ? offset ?';
                $query = $this->db->query($sql);
                $this->total_query = $query->num_rows();
                $query = $this->db->query($sql2,array($limit,$offset));
            }
            else
            {
                $sql="insert into mpm.sounit select a.kodeprod, a.namaprod,0 as rata,
                b1,b2,b3,b4,b5,b6,b6,b7,b8,b9,b10,b11,b12,".$id."
                from (
                    select b.kodeprod, b.namaprod,
                    sum(if(bulan=1,unit,0)) as b1,sum(if(bulan=2,unit,0)) as b2,
                    sum(if(bulan=3,unit,0)) as b3,sum(if(bulan=4,unit,0)) as b4,
                    sum(if(bulan=5,unit,0)) as b5,sum(if(bulan=6,unit,0)) as b6,
                    sum(if(bulan=7,unit,0)) as b7,sum(if(bulan=8,unit,0)) as b8,
                    sum(if(bulan=9,unit,0)) as b9,sum(if(bulan=10,unit,0)) as b10,
                    sum(if(bulan=11,unit,0)) as b11,sum(if(bulan=12,unit,0)) as b12
                    from
                    (
                            select kodeprod, namaprod, sum(".$unit.") as unit, bulan from data2010.fi where nodokjdi<>'XXXXXX' group by kodeprod,bulan
                            union all
                            select kodeprod, namaprod, sum(".$unit.") as unit, bulan from data2010.ri where nodokjdi<>'XXXXXX' group by kodeprod,bulan
                    ) a
                    inner join mpm.tabprod b using(kodeprod)
                    group by kodeprod
                )
                a
                left join
                (
                    select kodeprod,sum(bulan1+bulan2+bulan3+bulan4+bulan5+bulan6+bulan7+bulan8+bulan9+bulan10+bulan11+bulan12)/12  as rata from data2010.total2009 group by kodeprod
                )
                b using (kodeprod)
                group by kodeprod";
            }
            $query = $this->db->query($sql);
            $sql='select * from mpm.sounit where id='.$id.' order by kodeprod';
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
            $this->table->set_caption('Sell Out DP Timur');

            $this->table->set_heading('Kode Produk', 'Nama Produk', 'Rata2 '.$year2,'Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember');

            foreach ($query->result() as $value)
            {
                $this->table->add_row(
                        $value->kodeprod
                        ,$value->namaprod
                        ,'<div div style="text-align:right">' . number_format($value->rata,0) . '</div>'
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
    
    public function sales_dp($limit=null,$offset=null,$year=null,$retur=false)
    {
        $year2=(int)$year-1;
        $id=$this->session->userdata('id');

        $sql='select 1 from mpm.sounit where id=?';
        $query = $this->db->query($sql,array($id));
        
        if($query->num_rows()>0)
        {
            $sql='select * from (
                  select * from mpm.sounit where id='.$id.' 
                  union all
                  select "GRAND","TOTAL",
                  sum(rata),
                  sum(b1),
                  sum(b2),
                  sum(b3),
                  sum(b4),
                  sum(b5),
                  sum(b6),
                  sum(b7),
                  sum(b8),
                  sum(b9),
                  sum(b10),
                  sum(b11),
                  sum(b12),"" from mpm.sounit where id='.$id.')a order by kodeprod';
                   
            
            $query = $this->db->query($sql);
            $sql2  = $sql.' limit ? offset ?';
            $this->total_query = $query->num_rows();
            $query = $this->db->query($sql2,array($limit,$offset));
        }
        else
        {
            $uv=$this->input->post('uv');
            switch($uv)
            {
                case 0:$unit='banyak';break;
                case 1:$unit='tot1';break;
            }
            $supp=$this->session->userdata('supp');
            if($supp=='000')
            {
                $wheresupp='';
            }
            else if($supp=='001')
            {
                $wheresupp="and kodeprod like '01%' or kodeprod like '60%' or kodeprod like '50%'";
            }
            else if($supp=='005')
            {
                $wheresupp=' and kodeprod like "06%" ';
            }
            else
            {
                $wheresupp=" and kodeprod like '".substr($supp,-2)."%'";
            }
            if($year!='2010')
            {
                $sql="insert into mpm.sounit select a.kodeprod, a.namaprod,b.rata as rata,
                b1,b2,b3,b4,b5,b6,b7,b8,b9,b10,b11,b12,".$id."
                from (
                select b.kodeprod, b.namaprod,
                sum(if(bulan=1,unit,0)) as b1,sum(if(bulan=2,unit,0)) as b2,
                sum(if(bulan=3,unit,0)) as b3,sum(if(bulan=4,unit,0)) as b4,
                sum(if(bulan=5,unit,0)) as b5,sum(if(bulan=6,unit,0)) as b6,
                sum(if(bulan=7,unit,0)) as b7,sum(if(bulan=8,unit,0)) as b8,
                sum(if(bulan=9,unit,0)) as b9,sum(if(bulan=10,unit,0)) as b10,
                sum(if(bulan=11,unit,0)) as b11,sum(if(bulan=12,unit,0)) as b12
                from
                (
                select kodeprod, namaprod, sum(".$unit.") as unit, bulan from data".$year.".fi where nodokjdi<>'XXXXXX' ".$wheresupp." group by kodeprod,bulan
                union all
                select kodeprod, namaprod, sum(".$unit.") as unit, bulan from data".$year.".ri where nodokjdi<>'XXXXXX' ".$wheresupp." group by kodeprod,bulan
                ) a
                inner join mpm.tabprod b using(kodeprod)
                group by kodeprod
                )
                a
                left join
                (
                SELECT kodeprod, sum(unit)/12 as rata
                from
                (
                select kodeprod,SUM(".$unit.") AS UNIT from data".$year2.".fi where nodokjdi<>'XXXXXX' ".$wheresupp." group by kodeprod
                UNION ALL
                select kodeprod,Sum(".$unit.")  AS UNIT from data".$year2.".ri where nodokjdi<>'XXXXXX'".$wheresupp." group by kodeprod
                )
                a
                group by kodeprod

                ) b using (kodeprod)";
                $query = $this->db->query($sql);
                $sql='select * from mpm.sounit where id='.$id.' order by kodeprod';
                $query = $this->db->query($sql);
                $sql2  = $sql.' limit ? offset ?';
                $query = $this->db->query($sql);
                $this->total_query = $query->num_rows();
                $query = $this->db->query($sql2,array($limit,$offset));
            }
            else
            {
                $sql="insert into mpm.sounit select a.kodeprod, a.namaprod,0 as rata,
                b1,b2,b3,b4,b5,b6,b6,b7,b8,b9,b10,b11,b12,".$id."
                from (
                    select b.kodeprod, b.namaprod,
                    sum(if(bulan=1,unit,0)) as b1,sum(if(bulan=2,unit,0)) as b2,
                    sum(if(bulan=3,unit,0)) as b3,sum(if(bulan=4,unit,0)) as b4,
                    sum(if(bulan=5,unit,0)) as b5,sum(if(bulan=6,unit,0)) as b6,
                    sum(if(bulan=7,unit,0)) as b7,sum(if(bulan=8,unit,0)) as b8,
                    sum(if(bulan=9,unit,0)) as b9,sum(if(bulan=10,unit,0)) as b10,
                    sum(if(bulan=11,unit,0)) as b11,sum(if(bulan=12,unit,0)) as b12
                    from
                    (
                            select kodeprod, namaprod, sum(".$unit.") as unit, bulan from data2010.fi where nodokjdi<>'XXXXXX' group by kodeprod,bulan
                            union all
                            select kodeprod, namaprod, sum(".$unit.") as unit, bulan from data2010.ri where nodokjdi<>'XXXXXX' group by kodeprod,bulan
                    ) a
                    inner join mpm.tabprod b using(kodeprod)
                    group by kodeprod
                )
                a
                left join
                (
                    select kodeprod,sum(bulan1+bulan2+bulan3+bulan4+bulan5+bulan6+bulan7+bulan8+bulan9+bulan10+bulan11+bulan12)/12  as rata from data2010.total2009 group by kodeprod
                )
                b using (kodeprod)
                group by kodeprod";
            }
            $query = $this->db->query($sql);
            $sql='select * from mpm.sounit where id='.$id.' order by kodeprod';
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
            $this->table->set_caption('Sell Out DP');

            $this->table->set_heading('Kode Produk', 'Nama Produk', 'Rata2 '.$year2,'Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember');

            foreach ($query->result() as $value)
            {
                $this->table->add_row(
                        $value->kodeprod
                        ,$value->namaprod
                        ,'<div div style="text-align:right">' . number_format($value->rata,0) . '</div>'
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
    public function count_table_rows($table = '')
    {
        return $this->db->count_all($table);
    }
    public function stock_nas($limit=null,$offset=null)
    {

        /*$sql1 = "select
        b.kodeprod,
        b.namaprod,
        SUM(saldoawal) as saldoawal,
        SUM(if(kode_gdg='PST',((kvsisa+Saldoawal+masuk_pbk+masuk_supp+retur_sal+bPinjam+kvretur+kr_gudang+tukar_msk+retur_depo+tukar_msk+msk_gd_pst) -
        (sales+kvbeli+Pinjam+minta_depo+kr_min+tukar_klr+klr_gd_pst)- (rusak+sisih)),((kvsisa+Saldoawal+BPINJAM+masuk_pbk+kvretur+retur_sal+tukar_msk+msk_gddepo)-
        (sales+kvbeli+retur_depo+tukar_klr+klr_gddepo+pinjam))))
        as stokakhir
        from mpm.stok".date('Y')." a INNER JOIN mpm.tabprod b using(kodeprod)
        where blndok= (select max(blndok) from mpm.stok".date('Y').")
        GROUP BY KODEPROD
        order by kodeprod";*/
        $sql1="select kodeprod,namaprod,sum( if(kode_gdg='PST',
            ((Saldoawal+masuk_pbk+masuk_supp+retur_sal+bPinjam+kvretur+kr_gudang+retur_depo)-
            (sales+pinjam+minta_depo+kvbeli+kr_min)-(rusak+sisih)),(Saldoawal+masuk_pbk+retur_sal+kvretur+bPinjam+tukar_msk+msk_gddepo) -
            (sales+Pinjam+kvbeli+retur_depo+tukar_klr+klr_gddepo))) as stok
             from data2012.st where nocab=29  and kode_gdg!= '' and bulan = (select max(bulan) from data2012.st where nocab=29) group by kodeprod
            order by kodeprod
            ";

        $sql2 = $sql1." limit ? offset ?";
        $query = $this->db->query($sql1);
        $this->total_query = $query->num_rows();
        $query = $this->db->query($sql2,array($limit,$offset));

        if($query->num_rows() > 0)
        {
            $this->load->library('table');
            $this->table->set_empty('0');

            $this->table->set_template($this->tmpl);

            $this->table->set_caption('National Stock');

            $this->table->set_heading('Kode','Produk','Saldo Awal','Stok Akhir');
            foreach ($query->result() as $value)
            {
                $this->table->add_row($value->kodeprod
                        ,$value->namaprod
                        ,$value->saldoawal
                        ,$value->stokakhir)
                       ;
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
    public function stock_product($year=null,$code=null,$offset=0,$limit=0)
    {
        //$naper=$this->getNocab($dp);
        $sql='select max(bulan) as bulan from data'.$year.'.st where bulan='.date('m');
        $query = $this->db->query($sql);
        $row=$query->row();
        $bulan=$row->bulan;
        $tanggal=$year.'-'.substr('00'.$bulan,-2).'-01';

        $date[6]=date('Y-m-d',strtotime('-6 month',strtotime($tanggal)));
        $date[5]=date('Y-m-d',strtotime('-5 month',strtotime($tanggal)));
        $date[4]=date('Y-m-d',strtotime('-4 month',strtotime($tanggal)));
        $date[3]=date('Y-m-d',strtotime('-3 month',strtotime($tanggal)));
        $date[2]=date('Y-m-d',strtotime('-2 month',strtotime($tanggal)));
        $date[1]=date('Y-m-d',strtotime('-1 month',strtotime($tanggal)));

        $selectfi="select nocab,sum(banyak) as  average from data".date('Y',strtotime($date[1])).".fi
                    where kodeprod in (".$code.") and bulan =".date('m',strtotime($date[1]))." group by nocab";
        for($i=2;$i<=6;$i++)
        {
             $selectfi.=" union all
                select nocab,sum(banyak) as  average from data".date('Y',strtotime($date[$i])).".fi
                where kodeprod in (".$code.") and bulan =".date('m',strtotime($date[$i]))." group by nocab
                ";
        }
        $selectri="select nocab,sum(banyak) as  average from data".date('Y',strtotime($date[1])).".ri
                  where kodeprod in (".$code.") and bulan =".date('m',strtotime($date[1]))." group by nocab";
        for($i=2;$i<=6;$i++)
        {
            $selectri.=" union all
                 select nocab,sum(banyak) as  average from data".date('Y',strtotime($date[$i])).".ri
                 where kodeprod in (".$code.") and bulan =".date('m',strtotime($date[$i]))." group by nocab
                 ";
        }


        $uv=$this->input->post('uv');
        switch($uv)
        {
             case 0:
             {
                 $harga=1;
                
             }break;
             case 1:
             {
                 $harga='h_dp';
                 
             }break;
        }

        $id=$this->session->userdata('id');
        $sql='select naper,namacomp
            ,format(rata,0) as rata
                ,format(b1,0) as b1
                ,format(b2,0) as b2
                ,format(b3,0) as b3
                ,format(b4,0) as b4
                ,format(b5,0) as b5
                ,format(b6,0) as b6
                ,format(b7,0) as b7
                ,format(b8,0) as b8
                ,format(b9,0) as b9
                ,format(b10,0) as b10
                ,format(b11,0) as b11
                ,format(b12,0) as b12
            from mpm.stokprod where id='.$id .' order by naper';
       
        $sql='select 1  from mpm.stokprod where id='.$id;
        $query = $this->db->query($sql);
        if($query->num_rows()>0)
        {

             $sql='select naper,namacomp,b1,b2,b3,b4,b5,b6,b7,b8,b9,b10,b11,b12,id  from mpm.stokprod where id='.$id .'
                 union all
                select "GD" as naper,"TOTAL" as namacomp,
                    sum(`b1`) as `b1`,
                    sum(`b2`) as `b2`,
                    sum(`b3`) as `b3`,
                    sum(`b4`) as `b4`,
                    sum(`b5`) as `b5`,
                    sum(`b6`) as `b6`,
                    sum(`b7`) as `b7`,
                    sum(`b8`) as `b8`,
                    sum(`b9`) as `b9`,
                    sum(`b10`) as `b10`,
                    sum(`b11`) as `b11`,
                    sum(`b12`) as `b12`,'.$id.'
                from mpm.stokprod where id='.$id.'
                order by naper
                ';
            $query = $this->db->query($sql);
            $this->total_query = $query->num_rows();
            $sql2  = $sql.' limit ? offset ?';
            $query = $this->db->query($sql2,array($limit,$offset));
        }
        else
        {
            $sql="insert into mpm.stokprod select c.naper,c.nama_comp,b1,b2,b3,b4,b5,b6,b7,b8,b9,b10,b11,b12,0,".$id." from(
                 select nocab, 
                     sum(if(bulan=1,stok*".$harga.",0)) b1,
                     sum(if(bulan=2,stok*".$harga.",0)) b2,
                     sum(if(bulan=3,stok*".$harga.",0)) b3,
                     sum(if(bulan=4,stok*".$harga.",0)) b4,
                     sum(if(bulan=5,stok*".$harga.",0)) b5,
                     sum(if(bulan=6,stok*".$harga.",0)) b6,
                     sum(if(bulan=7,stok*".$harga.",0)) b7,
                     sum(if(bulan=8,stok*".$harga.",0)) b8,
                     sum(if(bulan=9,stok*".$harga.",0)) b9,
                     sum(if(bulan=10,stok*".$harga.",0)) b10,
                     sum(if(bulan=11,stok*".$harga.",0)) b11,
                     sum(if(bulan=12,stok*".$harga.",0)) b12
                 from(
                 select
          a.nocab,b.h_dp,
          substr(bulan, 3)AS bulan,a.kodeprod,
          sum( if(kode_gdg='PST',
                ((Saldoawal+masuk_pbk+masuk_supp+retur_sal+bPinjam+kvretur+kr_gudang+retur_depo)-
                (sales+pinjam+minta_depo+kvbeli+kr_min)-(rusak+sisih)),(Saldoawal+masuk_pbk+retur_sal+kvretur+bPinjam+tukar_msk+msk_gddepo) -
                (sales+Pinjam+kvbeli+retur_depo+tukar_klr+klr_gddepo))) as stok
            FROM
            data".$year.".st a 
            inner join (select a.kodeprod, a.h_dp * (100-d_dp)/100 as h_dp from prod_detail a inner join tabprod b using(kodeprod)
                        where tgl=(select max(tgl) from prod_detail where kodeprod=a.kodeprod) group by kodeprod) b using(kodeprod)
                where
                    a.kodeprod in (".$code.")
                AND kode_gdg != ''
                
    
            GROUP BY
                nocab,
                bulan,kodeprod
            ORDER BY
                nocab
                    ) a group by nocab
                    ) a inner join mpm.tabcomp c using(nocab) group by naper order by nama_comp";
            
                $query = $this->db->query($sql);
                 $sql='select naper,namacomp,b1,b2,b3,b4,b5,b6,b7,b8,b9,b10,b11,b12,id  from mpm.stokprod where id='.$id .'
                 union all
                select "GD" as naper,"TOTAL" as namacomp,
                    sum(`b1`) as `b1`,
                    sum(`b2`) as `b2`,
                    sum(`b3`) as `b3`,
                    sum(`b4`) as `b4`,
                    sum(`b5`) as `b5`,
                    sum(`b6`) as `b6`,
                    sum(`b7`) as `b7`,
                    sum(`b8`) as `b8`,
                    sum(`b9`) as `b9`,
                    sum(`b10`) as `b10`,
                    sum(`b11`) as `b11`,
                    sum(`b12`) as `b12`,'.$id.'
                from mpm.stokprod where id='.$id.'
                order by naper
                ';
                $query = $this->db->query($sql);
                $this->total_query = $query->num_rows();
                $sql2  = $sql.' limit ? offset ?';
                $query = $this->db->query($sql2,array($limit,$offset));
        }

        if($query->num_rows() > 0)
        {
            $this->load->library('table');
            $this->table->set_empty('0');

            $this->table->set_template($this->tmpl);
            $name = $this->get_product_name($code);
            $this->table->set_caption('SALDO AKHIR STOK '.$name.' '.$year);

            $this->table->set_heading('NOCAB', 'DP','Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember');
            foreach ($query->result() as $value)
            {
                $this->table->add_row(
                        $value->naper
                        ,$value->namacomp
                        //,'<div style="text-align:right">' . $value->rata. '</div>'
                        ,'<div style="text-align:right">' . number_format($value->b1,0)  . '</div>'
                        ,'<div style="text-align:right">' . number_format($value->b2,0)  . '</div>'
                        ,'<div style="text-align:right">' . number_format($value->b3,0)  . '</div>'
                        ,'<div style="text-align:right">' . number_format($value->b4,0)  . '</div>'
                        ,'<div style="text-align:right">' . number_format($value->b5,0)  . '</div>'
                        ,'<div style="text-align:right">' . number_format($value->b6,0)  . '</div>'
                        ,'<div style="text-align:right">' . number_format($value->b7,0)  . '</div>'
                        ,'<div style="text-align:right">' . number_format($value->b8,0)  . '</div>'
                        ,'<div style="text-align:right">' . number_format($value->b9,0)  . '</div>'
                        ,'<div style="text-align:right">' . number_format($value->b10,0)  . '</div>'
                        ,'<div style="text-align:right">' . number_format($value->b11,0)  . '</div>'
                        ,'<div style="text-align:right">' . number_format($value->b12,0)  . '</div>'
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
    
    public function stock_dp($limit=null,$offset=null,$dp=null,$year=null)
    {

        $naper=$this->getNocab($dp);
        //echo "naper : ".$naper;
        //echo "dp : ".$dp;

        if ($dp=="")
        {
            echo "<br><br><h1>Data Not Found</h1>";
        }

        else
        {         
                

                $sql='select max(bulan) as bulan from data'.$year.'.st where nocab=?';
                //echo "<br>";
                //echo "sql : ".$sql;
                
                $query = $this->db->query($sql,array($dp));
                $row=$query->row();
                $bulan=$row->bulan;
                $tanggal=$year.'-'.substr('00'.$bulan,-2).'-01';
                $year2=substr($year,-2);
                $date[6]=date('Y-m-d',strtotime('-6 month',strtotime($tanggal)));
                $date[5]=date('Y-m-d',strtotime('-5 month',strtotime($tanggal)));
                $date[4]=date('Y-m-d',strtotime('-4 month',strtotime($tanggal)));
                $date[3]=date('Y-m-d',strtotime('-3 month',strtotime($tanggal)));
                $date[2]=date('Y-m-d',strtotime('-2 month',strtotime($tanggal)));
                $date[1]=date('Y-m-d',strtotime('-1 month',strtotime($tanggal)));

                /*
                $cekdata="select * from data".date('Y',strtotime($date[1])).".fi
                            where nocab=".$dp." group by kodeprod";
                
                $query = $this->db->query($cekdata);
                if($query->num_rows()>0)
                {
                */
                    $selectfi="select kodeprod,sum(banyak) as  average from data".date('Y',strtotime($date[1])).".fi
                            where nocab=".$dp." and kode_type<>'TD' and bulan =".date('m',strtotime($date[1]))." group by kodeprod";
                
                        for($i=2;$i<=6;$i++)
                        {
                             $selectfi.=" union all
                                select kodeprod,sum(banyak) as  average from data".date('Y',strtotime($date[$i])).".fi
                                where nocab=".$dp." and kode_type<>'TD' and bulan =".date('m',strtotime($date[$i]))." group by kodeprod
                                ";
                        }
                        $selectri="select kodeprod,sum(banyak) as  average from data".date('Y',strtotime($date[1])).".ri
                                  where nocab=".$dp." and bulan =".date('m',strtotime($date[1]))." group by kodeprod";
                        for($i=2;$i<=6;$i++)
                        {
                            $selectri.=" union all
                                 select kodeprod,sum(banyak) as  average from data".date('Y',strtotime($date[$i])).".ri
                                 where nocab=".$dp." and bulan =".date('m',strtotime($date[$i]))." group by kodeprod
                                 ";
                        }


                        $uv=$this->input->post('uv');
                        $id=$this->session->userdata('id');
                        $union= 'union all
                                    select "Grand","Total",sum(rata),
                                    sum(b1),sum(b2),sum(b3),sum(b4),sum(b5),sum(b6),
                                    sum(b7),sum(b8),sum(b9),sum(b10),sum(b11),sum(b12),sum(rata)/sum('.$bulan.')*30 from mpm.stokdp where id='.$id;

                        switch($uv)
                        {
                             case 0:
                             {
                                 $harga=1;
                                 $harga2=1;
                             }break;
                             case 1:
                             {
                                 $harga='b.h_dp';
                                 $harga2='h_dp';
                             }break;
                        }

                        
                        $sql='select max(bulan) as bulan from data'.$year.'.st where nocab in ('.$naper.')';
                        $query= $this->db->query($sql);
                        $row=$query->row();
                        $bulan=substr($row->bulan,2,1)=='0'?'b'.substr($row->bulan,3,1):'b'.substr($row->bulan,2,2);
                        $sql='select 1 from mpm.stokdp where id='.$id;

                        $query = $this->db->query($sql);
                        if($query->num_rows()>0)
                        {
                        $sql='select * from (select kodeprod,namaprod
                                , rata
                                , b1
                                , b2
                                , b3
                                , b4
                                , b5
                                , b6
                                , b7
                                , b8
                                , b9
                                , b10
                                , b11
                                , b12
                                ,doi
                            from mpm.stokdp where id='.$id .' '.$union.')a order by kodeprod';
                           
                            $query = $this->db->query($sql);
                            //$query = $this->db->query($sql);
                            $this->total_query = $query->num_rows();
                            $sql2  = $sql.' limit ? offset ?';
                            $query = $this->db->query($sql2,array($limit,$offset));
                        }
                        else
                        {
                           
                            $sql=" insert into mpm.stokdp
                                select kodeprod,namaprod,b1,b2,b3,b4,b5,b6,b7,b8,b9,b10,b11,b12,(b.rata*".$harga2.")as rata,((".$bulan."/(b.rata*".$harga2."))*30) as doi,".$id." from(
                                select a.kodeprod,a.namaprod,".$harga."
                                ,sum(if(bulan=01,stok,0))*".$harga." as b1
                                ,sum(if(bulan=02,stok,0))*".$harga." as b2
                                ,sum(if(bulan=03,stok,0))*".$harga." as b3
                                ,sum(if(bulan=04,stok,0))*".$harga." as b4
                                ,sum(if(bulan=05,stok,0))*".$harga." as b5
                                ,sum(if(bulan=06,stok,0))*".$harga." as b6
                                ,sum(if(bulan=07,stok,0))*".$harga." as b7
                                ,sum(if(bulan=08,stok,0))*".$harga." as b8
                                ,sum(if(bulan=09,stok,0))*".$harga." as b9
                                ,sum(if(bulan=10,stok,0))*".$harga." as b10
                                ,sum(if(bulan=11,stok,0))*".$harga." as b11
                                ,sum(if(bulan=12,stok,0))*".$harga." as b12
                                from(
                                select kodeprod,namaprod,substr(bulan,3) as bulan,sum( if(kode_gdg='PST',
                                ((Saldoawal+masuk_pbk+masuk_supp+retur_sal+bPinjam+kvretur+kr_gudang+retur_depo)-
                                (sales+pinjam+minta_depo+kvbeli+kr_min)-(rusak+sisih)),(Saldoawal+masuk_pbk+retur_sal+kvretur+bPinjam+tukar_msk+msk_gddepo) -
                                (sales+Pinjam+kvbeli+retur_depo+tukar_klr+klr_gddepo))) as stok
                                from data".$year.".st where nocab in (".$naper.")  and kode_gdg!= '' group by kodeprod,bulan
                                order by kodeprod
                                )a inner join (select a.kodeprod, a.h_dp * (100-d_dp)/100 as h_dp from prod_detail a inner join tabprod b using(kodeprod)
                                        where tgl=(select max(tgl) from prod_detail where kodeprod=a.kodeprod)) b using(kodeprod) group by kodeprod)
                                a left join (select kodeprod,sum(average)/6 as rata from(
                                        ".$selectfi."
                                        union all
                                        ".$selectri."
                                )a group by kodeprod )b using(kodeprod) 
                                ";
                               
                                $query = $this->db->query($sql);
                                $sql='select * from(select kodeprod,namaprod
                                ,rata
                                , b1
                                , b2
                                , b3
                                , b4
                                , b5
                                , b6
                                , b7
                                , b8
                                , b9
                                , b10
                                , b11
                                , b12
                                ,doi
                                from mpm.stokdp where id='.$id .' '.$union.') a order by kodeprod';
                                $query = $this->db->query($sql);
                                $this->total_query = $query->num_rows();
                                $sql2  = $sql.' limit ? offset ?';
                                $query = $this->db->query($sql2,array($limit,$offset));
                        }

                        if($query->num_rows() > 0)
                        {
                            $this->load->library('table');
                            $this->table->set_empty('0');

                            $this->table->set_template($this->tmpl);
                            $name = $this->get_dp_name($dp);
                            $this->table->set_caption('SALDO AKHIR STOK '.$name->nama_comp);

                            $this->table->set_heading('CODE', 'PRODUCT','AVG SALES (6) ','Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember','DOI');
                            $rata=0;
                            $b1=0;
                            $b2=0;
                            $b3=0;
                            $b4=0;
                            $b5=0;
                            $b6=0;
                            $b7=0;
                            $b8=0;
                            $b9=0;
                            $b10=0;
                            $b11=0;
                            $b12=0;
                            foreach ($query->result() as $value)
                            {
                                $this->table->add_row(
                                        $value->kodeprod
                                        ,$value->namaprod
                                        ,'<div style="text-align:right">' . number_format($value->rata). '</div>'
                                        ,'<div style="text-align:right">' . number_format($value->b1) . '</div>'
                                        ,'<div style="text-align:right">' . number_format($value->b2)  . '</div>'
                                        ,'<div style="text-align:right">' . number_format($value->b3)  . '</div>'
                                        ,'<div style="text-align:right">' . number_format($value->b4)  . '</div>'
                                        ,'<div style="text-align:right">' . number_format($value->b5)  . '</div>'
                                        ,'<div style="text-align:right">' . number_format($value->b6)  . '</div>'
                                        ,'<div style="text-align:right">' . number_format($value->b7)  . '</div>'
                                        ,'<div style="text-align:right">' . number_format($value->b8)  . '</div>'
                                        ,'<div style="text-align:right">' . number_format($value->b9)  . '</div>'
                                        ,'<div style="text-align:right">' . number_format($value->b10)  . '</div>'
                                        ,'<div style="text-align:right">' . number_format($value->b11) . '</div>'
                                        ,'<div style="text-align:right">' . number_format($value->b12) . '</div>'
                                        ,'<div style="text-align:right">' . number_format($value->doi) . '</div>'
                                );
                                $rata+=$value->rata;
                                $b1+=$value->b1;
                                $b2+=$value->b2;
                                $b3+=$value->b3;
                                $b4+=$value->b4;
                                $b5+=$value->b5;
                                $b6+=$value->b6;
                                $b7+=$value->b7;
                                $b8+=$value->b8;
                                $b9+=$value->b9;
                                $b10+=$value->b10;
                                $b11+=$value->b11;
                                $b12+=$value->b12;
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
                 
    }
}