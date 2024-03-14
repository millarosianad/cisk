<?php
if( ! defined('BASEPATH')) exit('Akses langsung tidak diperkenankan');

error_reporting(E_ALL);
ini_set('display_errors', '1');
ini_set('memory_limit', '-1');
class Report_model extends CI_Model
{
    public function Report_model()
    {
        set_time_limit(0);

        $this->load->database();
        $this->load->library(array('table','session','zip','stable','user_agent'));//session untuk sisi administrasi
        $this->load->helper(array('text','array','file','to_excel'));
        //$this->config->load('sorot');
    }
    function outcast($state=null,$key=0,$id='',$init='')
    {
        switch($state)
        {
            case 'dp':
            {
                $tahun=$this->input->post('year');
                $nocab=$this->input->post('wilayah');

                $sql='select a.koderayon,a.namaprod,a.grupprod,a.unit,a.value,a.supp,a.blndok,b.namasupp from(
                    SELECT kodeprod,namaprod,koderayon,grupprod,sum(banyak)unit,sum(tot1)value,supp,blndok FROM data'.$tahun.'.fi where nocab='.$nocab.' group by blndok,kodeprod,supp,grupprod
                    union ALL
                    SELECT kodeprod,namaprod,koderayon,grupprod,sum(banyak)unit,sum(tot1)value,supp,blndok FROM data'.$tahun.'.ri where nocab='.$nocab.' group by blndok,kodeprod,supp,grupprod
                    )a inner join mpm.tabsupp b using(supp) ';
                $query=$this->db->query($sql);
                return json_encode($query->result()); 
            }
            break;
        }
    }
    function list_product()
    {
        $sql='select supp,namasupp,kodeprod,namaprod from mpm.tabprod a inner join tabsupp b using(supp) 
              where supp!="BSP" and b.active=1 order by supp,namaprod asc';
        return $this->db->query($sql); 
    }
    public function list_dp()
    {
        $nocab=$this->session->userdata('nocab');
        if ($nocab!='')
        {
            return $this->db->query('select distinct naper, nama_comp from mpm.tabcomp where  naper!=99 and naper like "%'.$nocab.'%" order by nama_comp');
        }
        else
        {
            return $this->db->query('select distinct naper, nama_comp from mpm.tabcomp where  naper!=99 order by nama_comp');
        }
    }
    function analisa($state=null,$key=0,$id='',$init='')
    {
        $tahun=$this->input->post('year');
        $wilayah=$this->input->post('wilayah');
        $bulan=$this->input->post('bulan');
        switch($state)
        {
            case 'bsp':
            {
                $kondisi=$wilayah>0?" where bagian =".$wilayah:"";
                switch($wilayah)
                {
                    case '0':$kondisi='';break;
                    case '1':
                    case '2':
                    case '3':
                    case '4':
                    case '5':
                    case '6':    
                        $kondisi="where bagian=".$wilayah;break;
                    case '7':$kondisi="where wilayah in (1)";break;
                    case '8':$kondisi="where wilayah in (2)";break;
                }
                
                 $tahun=$this->input->post('year');
                 $sql="select   a.nama_comp cabang,
                                nama_type tipe,
                                deskripsi produk,
                                sum(banyak/isisatuan) unit,
                                sum(jumlah) value,
                                substr(tgldokjdi,6,2) bulan
                            from 
                                bsp.bspsales".$tahun." a
                                inner join  bsp.tabprod b 
                                        on a.deskripsi=b.namaprod
                                inner join bsp.tabcomp c
                                        on a.kode_comp = c.kode_comp
                                ".$kondisi." 
                                group by cabang,tipe,produk,bulan";
                echo "<pre><br><br><br>";
                print_r($sql);
                echo "</pre>";

                 $query=$this->db->query($sql);
                 return json_encode($query->result()); 
            }break;
            case 'permen':
            {
                $tahun=$this->input->post('year');
                $sql="select cust_name,kota,date_format(tgl,'%m') month,tipe_jual,areajual_name ,nama_barang,tipe_trans,sum(qty)unit,sum(netto) value,group_barang from permen.sales
                        where year(tgl)=? group by cust_name,kota,month,tipe_jual,tipe_trans,group_barang,nama_barang";
                $query=$this->db->query($sql,array($tahun));
                return json_encode($query->result()); 
            }
            case 'dp':
            {
                $kondisi=$wilayah>0?" where bagian =".$wilayah:"";

                echo "<hr>var kondisi : ".$kondisi."<br>";
                echo "<hr>var wilayah : ".$wilayah."<br>";

                switch($wilayah)
                {
                    case '0':$kondisi='';break;
                    case '1':
                    case '2':
                    case '3':
                    case '4':
                    case '5':
                    case '6':    
                        $kondisi="where bagian=".$wilayah;break;
                    case '7':$kondisi="where wilayah in (1)";break;
                    case '8':$kondisi="where wilayah in (2)";break;
                }
                
                 $sql="select format(target,2)target,namaprod,namasupp,nama_comp, grupprod,ot,unit,value,a.bulan month from (
                       select nocab,case when left(kodeprod,2)in('01',50,60,70) then '001'
                                         when left(kodeprod,2)='06' then '005'
                                         else concat('0',left(kodeprod,2)) end as supp,kodeprod,count(distinct kode_lang) ot,sum(banyak) unit, sum(tot1) value, blndok as bulan from data".$tahun.".fi group by kodeprod,bulan,nocab,supp
                       union all 
                       select nocab,case when left(kodeprod,2)in('01',50,60,70) then '001'
                                         when left(kodeprod,2)='06' then '005'
                                         else concat('0',left(kodeprod,2)) end as supp,kodeprod,0 ot,sum(banyak) unit, sum(tot1) value, blndok as bulan from data".$tahun.".ri group by kodeprod,bulan,nocab,supp
                       )a 
                       inner join mpm.tabcomp b using(nocab) 
                       left join mpm.tabprod c using(kodeprod)
                       left join mpm.tabsupp d on a.supp=d.supp
                       left join mpm.target2 e on a.nocab=e.nocab and a.bulan=e.bulan
                       ".$kondisi." group by namaprod,nama_comp,c.grupprod,unit,value,month"; 
                
                echo "<pre>";
                print_r($sql);
                echo "</pre>";

                $query=$this->db->query($sql);
                return json_encode($query->result()); 
            }break;
            case 'outlet':
            {
                $sql="";
                $query=$this->db->query($sql);
                return json_encode($query->result()); 
            }break;
        }
    }
    function analisapermen($state=null,$key=0,$id='',$init='')
    {
        $tahun=$this->input->post('year');
        $wilayah=$this->input->post('wilayah');
        $bulan=$this->input->post('bulan');
        switch($state)
        {
            case 'bsp':
            {
                 $tahun=$this->input->post('year');
                 $sql="select nama_comp cabang,nama_type tipe,deskripsi produk,sum(banyak/isisatuan) unit ,sum(jumlah) value,substr(tgldokjdi,6,2) bulan from 
                       bsp.bspsales".$tahun." a inner join  bsp.tabprod b on a.deskripsi=b.namaprod where deskripsi like '%permen%' or deskripsi like '%lozenge%'
                       group by cabang,tipe,produk,bulan";
                 $query=$this->db->query($sql);
                 return json_encode($query->result()); 
            }break;
            case 'permen':
            {
                $tahun=$this->input->post('year');
                $sql="select cust_name,kota,date_format(tgl,'%m') month,tipe_jual,areajual_name ,nama_barang,tipe_trans,sum(qty)unit,sum(netto) value,group_barang from permen.sales
                        where year(tgl)=? group by cust_name,kota,month,tipe_jual,tipe_trans,group_barang,nama_barang";
                $query=$this->db->query($sql,array($tahun));
                return json_encode($query->result()); 
            }
            case 'dp':
            {
                $kondisi=$wilayah>0?" where bagian =".$wilayah:"";
                
                /* cek tahun, jika tahun 2017 ke atas, maka pontianak masuk ke wilayah 2 atau timur */

                    if ($tahun == '2010' || $tahun == '2011' || $tahun == '2012' || $tahun == '2013' || $tahun == '2014' || $tahun == '2015' || $tahun == '2016')
                    {
                        switch($wilayah)
                        {
                            case '0':$kondisi='';break;
                            case '1':
                            case '2':
                            case '3':
                            case '4':
                            case '5':
                            case '6':    
                                $kondisi="where bagian=".$wilayah;break;
                            case '7':$kondisi="where wilayah in (1)";break;
                            case '8':$kondisi="where wilayah in (2)";break;
                        }

                    } else {
                        
                        switch($wilayah)
                        {
                            case '0':$kondisi='';break;
                            case '1':
                            case '2':
                            case '3':
                            case '4':
                            case '5':
                            case '6':    
                                $kondisi="where bagian=".$wilayah;break;
                            case '7':$kondisi="where wilayah_2017 in (1)";break;
                            case '8':$kondisi="where wilayah_2017 in (2)";break;
                        }

                    }
                
                /* end cek tahun, jika tahun 2017 ke atas, maka pontianak masuk ke wilayah 2 atau timur */
                
                 $sql="select format(target,2)target,namaprod,namasupp,nama_comp, grupprod,ot,unit,value,a.bulan month from (
                       select nocab,case when left(kodeprod,2)in('01',50,60,70) then '001'
                                         when left(kodeprod,2)='06' then '005'
                                         else concat('0',left(kodeprod,2)) end as supp,kodeprod,count(distinct kode_lang) ot,sum(banyak) unit, sum(tot1) value, blndok as bulan from data".$tahun.".fi group by kodeprod,bulan,nocab,supp
                       union all 
                       select nocab,case when left(kodeprod,2)in('01',50,60,70) then '001'
                                         when left(kodeprod,2)='06' then '005'
                                         else concat('0',left(kodeprod,2)) end as supp,kodeprod,0 ot,sum(banyak) unit, sum(tot1) value, blndok as bulan from data".$tahun.".ri group by kodeprod,bulan,nocab,supp
                       )a 
                       inner join mpm.tabcomp b using(nocab) 
                       left join mpm.tabprod c using(kodeprod)
                       left join mpm.tabsupp d on a.supp=d.supp
                       left join mpm.target2 e on a.nocab=e.nocab and a.bulan=e.bulan
                       ".$kondisi." and c.permen=1  group by namaprod,nama_comp,c.grupprod,unit,value,month"; 
         
                $query=$this->db->query($sql);
                return json_encode($query->result()); 
            }break;
            case 'outlet':
            {
                $sql="";
                $query=$this->db->query($sql);
                return json_encode($query->result()); 
            }break;
        }
    }
}
?>
