<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Laporan extends CI_Controller{

    public function index(){
        $this->load->library('mypdf');
        $this->mypdf->generate('laporan/dompdf');

    }

    public function download(){
        $this->load->library('mypdf');
        $this->mypdf->download('laporan/dompdf');

    }
}