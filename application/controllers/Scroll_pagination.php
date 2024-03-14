<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Scroll_pagination extends CI_Controller {

	


	function index()
	{
		$this->load->view('scroll_pagination');
	}

	function fetch()
	{
		// $this->load->library(array('table'));
        // $this->load->helper('url');
        // $this->load->helper('csv');
        // $this->load->model('model_omzet');
        // $this->load->model('model_retur');
        // $this->load->model('M_menu');
        // $this->load->model('model_per_hari');
        // $this->load->model('model_sales_omzet');
        // $this->load->model('model_sales');
        // $this->load->model('model_outlet_transaksi');
        // $this->load->model('model_master_data');
        // $this->load->model('M_helpdesk');
        // $this->load->model('M_product');
        // $this->load->model('model_inventory');
        // $this->load->database();

		$this->load->helper('database');

		$output = '';
		$this->load->model('scroll_pagination_model');
		$data = $this->scroll_pagination_model->fetch_data($this->input->post('limit'), $this->input->post('start'));
		if($data->num_rows() > 0)
		{
			foreach($data->result() as $row)
			{
				$output .= '
				<div class="post_data">
					<h3 class="text-danger">'.$row->post_title.'</h3>
					<p>'.$row->post_description.'</p>
				</div>
				';
			}
		}
		echo $output;
	}

}
