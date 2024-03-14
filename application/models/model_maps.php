<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 
class Model_maps extends CI_Model
{
    function __construct()
    {
        // Call the Model constructor
        parent::__construct();
        
    }
    public function peta()
    {

        $sql="
                select  nama_comp,company,
                        latitude,address,longitude 
                from    mpm.user a inner join mpm.tabcomp b 
                                 on a.username = b.kode_comp 
                where   !isnull(latitude) and KDPULAU = '001'
        ";
        $query = $this->db->query($sql);
        $this->load->library('googlemaps');
                //$this->load->library('geoip_lib');
                
        if($query->num_rows()>0)
        {
            $config['zoom'] = 'auto';
            //$config['onload'] = "google.maps.event.trigger(marker_3, 'click');";

            
            //$config['panoramio'] = TRUE;
            //$config['panoramioTag'] = 'sunset';

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
                $marker['infowindow_content'] =$value->nama_comp.' | '.$value->company;
                $marker['title']= $value->company;
                $marker['animation']= 'drop';
                $marker['clickable'] = true;
                $marker['flat'] = TRUE;
                $marker['visible'] = TRUE;
                $marker['draggable'] = TRUE;
                
                $marker['icon'] = 'http://chart.apis.google.com/chart?chst=d_map_pin_letter&chld=|00FF00|000000';
                $this->googlemaps->add_marker($marker);
            }
                    
            return $this->googlemaps->create_map();
        }
        else
            return false;
    }

    public function peta_jawa()
    {
        $sql="
                select  nama_comp,company,
                        latitude,address,longitude 
                from    mpm.user a inner join mpm.tabcomp b 
                                 on a.username = b.kode_comp 
                where   !isnull(latitude) and KDPULAU = '002' and AREA = 1
        ";


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
                $marker['title']= $value->company;
                $marker['animation']= 'drop';
                $marker['visible'] = false;


                
                $marker['icon'] = 'http://chart.apis.google.com/chart?chst=d_map_pin_letter&chld=A|00FF00|000000';
                $this->googlemaps->add_marker($marker);
            }
                    
            return $this->googlemaps->create_map();
        }
        else
            return false;
    }

    function peta_outlet_subbranch($dataSegment){
        
        $subbranch = $dataSegment['subbranch'];

        //echo $subbranch;

        $sql="
                select NAMA_LANG, kode_lang, alamat1, latitude, longitude from data2016.tblang
                where KODE_COMP ='$subbranch' and LONGITUDE is not null and LATITUDE is not null
        ";

        /*
        $sql="
                select NAMA_LANG, kode_lang, alamat1, LONGITUDE, LATITUDE from data2016.tblang
                where KODE_COMP ='$subbranch' and LONGITUDE is not null and LATITUDE is not null
        ";
        */

        $query = $this->db->query($sql);
        $this->load->library('googlemaps');
                //$this->load->library('geoip_lib');
                
        if($query->num_rows()>0)
        {

            $config['zoom'] = 'auto';
            //$config['onload'] = "google.maps.event.trigger(marker_3, 'click');";

            
            //$config['panoramio'] = TRUE;
            //$config['panoramioTag'] = 'sunset';

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
                $marker['infowindow_content'] =$value->kode_lang.' | '.$value->NAMA_LANG.' | '.$value->alamat1;
                $marker['title']= $value->alamat1;
                $marker['animation']= 'none';
                $marker['clickable'] = true;
                $marker['flat'] = TRUE;
                $marker['visible'] = TRUE;
                $marker['draggable'] = TRUE;
                
                $marker['icon'] = 'http://chart.apis.google.com/chart?chst=d_map_pin_letter&chld=|00FF00|000000';
                $this->googlemaps->add_marker($marker);
            }
                    
            return $this->googlemaps->create_map();
        }
        else
            return false;
        
    }
    
}
?>
