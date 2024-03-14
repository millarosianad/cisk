<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Generate extends MY_Controller
{
    function all_raw()
    {
        set_time_limit(0);
        $this->load->library(array('table','template','Excel_generator', 'form_validation','email'));
        $this->load->helper('url');
        $this->load->helper('csv');
        $this->load->model('Model_generate', 'generate');
        $this->load->database();
        $this->querymenu='select  a.id,
                        a.menuview,
                        a.target,
                        a.groupname 
                from    mpm.menu a inner join mpm.menudetail b 
                            on a.id=b.menuid 
                where   b.userid='.$this->session->userdata('id').' and 
                        active=1 
                order by a.groupname,menuview ';
    }
    function index()
    {
        $logged_in= $this->session->userdata('logged_in');
        if(!isset($logged_in) || $logged_in != TRUE)
        {
            redirect('login/','refresh');
        }
        $this->json();
    }

    public function json(){
        $this->load->model('Model_generate','generate');
        $generate= $this->generate->getfi();
        $data['generate'] = $generate;

        // echo "<pre>";
        // var_dump($generate);
        // echo "</pre>";

        $response = array();
        $posts = array();
        foreach ($generate as $gen)
        {
            $posts[] = array(
                "KDDOKJDI" => $gen->KDDOKJDI,
                "NODOKJDI" => $gen->NODOKJDI,
                "NODOKACU" => $gen->NODOKACU,
                "TGLDOKJDI" => $gen->TGLDOKJDI,                
                "KODESALES" => $gen->KODESALES,
                "KODE_COMP" => $gen->KODE_COMP,                
                "KODE_KOTA" => $gen->KODE_KOTA,             
                "KODE_TYPE" => $gen->KODE_TYPE,             
                "KODE_LANG" => $gen->KODE_LANG,             
                "KODERAYON" => $gen->KODERAYON,             
                "KODEPROD" => $gen->KODEPROD
            );
        } 
        $response['posts'] = $posts;
        echo json_encode($response, true);


        // echo "<pre>";
        // echo "proses : ".$generate;
        // var_dump($generate);
        // echo "</pre>";
        
    }

    public function json2(){
        $this->load->model('Model_generate','generate');
        $generate= $this->generate->getfi();
        // $data['generate'] = $generate;

        // echo "<pre>";
        // var_dump($generate);
        // echo "</pre>";

        $encode_data = json_encode($generate);
        echo $encode_data;

        $fp = fopen('./test.json', 'w');
        fwrite($fp, json_encode($generate));

        
    }

    public function filejson(){
        $data = file_get_contents(base_url()."assets/json/mlg.json");

        // var_dump($data);
        $test = json_decode($data, true);
        var_dump($test);
        
    }



}
?>
