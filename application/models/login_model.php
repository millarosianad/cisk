<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 
class Login_model extends CI_Model
{
    function __construct()
    {
        // Call the Model constructor
        parent::__construct();
        
    }
    public function peta()
    {
        $sql="select nama_comp,company,latitude,address,longitude from mpm.user a inner join mpm.tbl_tabcomp b on a.username = b.kode_comp where !isnull(latitude)";
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
                //$marker['icon'] = 'http://chart.apis.google.com/chart?chst=d_map_pin_letter&chld=A|9999FF|000000';
                $this->googlemaps->add_marker($marker);
            }
                    
            return $this->googlemaps->create_map();
        }
        else
            return false;
    }
    function login_check($user,$pass)
    {
        $sql='select  id,username,supp,level,
        nocab nocab,if(b.nocab=b.sub,1,0)cabang, 
                    address, email, company,a.kode_lang 
            from    mpm.user a left join (
                select kode_comp, concat(kode_comp, nocab), naper, nocab, sub
                from mpm.tbl_tabcomp
                where `status` = 1
                GROUP BY concat(kode_comp, nocab)
            )b on a.username = b.kode_comp 
            where   username=? and password=? and active=1';
        $query = $this->db->query($sql,array($user,$pass));
        if($query->num_rows()>0)
        {
            $sql='update mpm.user set lastlogin=? where username=?';
            $this->db->query($sql,array(date('Y-m-d H:i:s'),$user));
            return $query;
        }
        else
        {
            return 0;
        }
    }
}
?>
