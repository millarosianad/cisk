<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');
class Master_product extends MY_Controller
{
    public function Master_product()
    {
        $logged_in = $this->session->userdata('logged_in');
        if (!isset($logged_in) || $logged_in != TRUE) {
            redirect('login/', 'refresh');
        }
        set_time_limit(0);
        $this->load->library(array('table', 'template', 'Excel_generator', 'form_validation', 'email'));
        $this->load->helper('url');
        $this->load->helper('csv');
        $this->load->model('M_menu');
        $this->load->model('M_product');
        $this->load->model('model_master_data');
        $this->load->model('model_sales_omzet');
        $this->load->database();
    }

    public function get_productsid()
    {
        $logged_in = $this->session->userdata('logged_in');
        if (!isset($logged_in) || $logged_in != TRUE) {
            redirect('login/', 'refresh');
        }
        set_time_limit(0);

        $productID = $_GET['id'];
        // $data['edit']   = 'aas';
        $data['edit']   = $this->M_product->get_product_by_ID($productID);
        $data['harga']   = $this->M_product->get_hargaproduct_by_ID($productID);
        $data['harga_apps']   = $this->M_product->get_hargaproduct_apps_by_ID($productID);
        $data['harga_dp']   = $this->M_product->get_hargaproduct_dp_by_ID($productID);
        echo json_encode($data);
    }

    function get_namaprod()
    {
        $kodeprod = $this->input->post('kodeprod');
        $query=$this->M_product->get_namaprod($kodeprod);
        if ($query) {
            foreach($query as $row)
            {
                $output= $row->namaprod;
            }
            echo $output;
            
        }else{
            echo "produk tidak ditemukan";
        }
    
    }

    public function tambah_pricelist_dp()
    {
        $logged_in = $this->session->userdata('logged_in');
        if (!isset($logged_in) || $logged_in != TRUE) {
            redirect('login/', 'refresh');
        }
        $data['timezone'] = $this->model_sales_omzet->timezone2();
        $this->M_product->tambah_pricelist_dp($data);
    }

    public function get_pricelist_by_id()
    {
        $logged_in = $this->session->userdata('logged_in');
        if (!isset($logged_in) || $logged_in != TRUE) {
            redirect('login/', 'refresh');
        }
        set_time_limit(0);

        $productID = $_GET['id'];
        // $data['edit']   = 'aas';
        $data['edit']   = $this->M_product->get_product_by_ID($productID);
        $data['harga']   = $this->M_product->get_hargaproduct_by_ID($productID);
        $data['harga_apps']   = $this->M_product->get_hargaproduct_apps_by_ID($productID);
        $data['harga_dp']   = $this->M_product->get_hargaproduct_dp_by_ID($productID);
        $data['pricelist']   = $this->M_product->get_pricelist_by_id($productID);
        echo json_encode($data);
    }

    public function get_product_dp()
    {
        $logged_in = $this->session->userdata('logged_in');
        if (!isset($logged_in) || $logged_in != TRUE) {
            redirect('login/', 'refresh');
        }
        set_time_limit(0);

        $productID = $_GET['id'];
        // $data['edit']   = 'aas';
        $data['edit']   = $this->M_product->get_product_by_ID($productID);
        $data['harga']   = $this->M_product->get_hargaproduct_by_ID($productID);
        // $data['harga_apps']   = $this->M_product->get_hargaproduct_apps_by_ID($productID);
        // $data['product_dp']   = $this->M_product->get_hargaproduct_apps_by_ID($productID);
        $data['product_dp']   = $this->M_product->get_hargaproduct_dp_by_id($productID);
        echo json_encode($data);
    }

    function build_group()
    {
        //$this->load->model('basic_model','bmodel');
        $logged_in = $this->session->userdata('logged_in');
        if (!isset($logged_in) || $logged_in != TRUE) {
            redirect('login/', 'refresh');
        }
        $kode_supp = $this->input->post('kode_supp', TRUE);
        // var_dump($group);die;
        //$query=$this->model_stock->get_namacomp($dp);

        $data['supp'] = $kode_supp;
        $query = $this->M_product->getBuild_Group($data);
        foreach ($query->result() as $row) {
            $output = "<option value='" . $row->kode_group . "'>" . $row->nama_group . "</option>";
            echo $output;
        }
    }

    function build_subgroup()
    {
        //$this->load->model('basic_model','bmodel');
        $logged_in = $this->session->userdata('logged_in');
        if (!isset($logged_in) || $logged_in != TRUE) {
            redirect('login/', 'refresh');
        }
        $group = $this->input->post('grup', TRUE);
        // var_dump($group);die;
        //$query=$this->model_stock->get_namacomp($dp);

        $data['grup'] = $group;
        $query = $this->M_product->getBuild_Subgroup($data);

        foreach ($query->result() as $row) {
            $output = "<option value='" . $row->sub_group . "'>" . $row->nama_sub_group . "</option>";
            echo $output;
        }
    }

    public function product()
    {
        $logged_in = $this->session->userdata('logged_in');
        if (!isset($logged_in) || $logged_in != TRUE) {
            redirect('login/', 'refresh');
        }
        set_time_limit(0);
        $id = $this->session->userdata('id');

        $data = [
            'id'            => $this->session->userdata('id'),
            'url'           => 'master_product/product/',
            'title'         => 'Product',
            'get_label'     => $this->M_menu->get_label(),
            'product'       => $this->M_product->get_product(),
            'suppq'         => $this->M_product->getSupp(),
            'group'         => $this->M_product->getGroup(),
            'subgroup'      => $this->M_product->getSubgroup(),
            'jenis'         => $this->M_product->getJenis(),
            's_code'        => $this->model_master_data->site_code()
        ];
        // var_dump($data);die;

        if ($id == '11' || $id == '289' || $id == '297a' || $id == '547') {
            $view = 'master_product/product_apps';
        } else {
            $view = 'master_product/product';
        }
        $this->load->view('template_claim/top_header');
        $this->load->view('template_claim/header');
        $this->load->view('template_claim/sidebar', $data);
        $this->load->view('template_claim/top_content', $data);
        $this->load->view($view, $data);
        $this->load->view('template_claim/bottom_content');
        $this->load->view('template_claim/footer');
    }

    public function product_detail_harga()
    {
        $logged_in = $this->session->userdata('logged_in');
        if (!isset($logged_in) || $logged_in != TRUE) {
            redirect('login/', 'refresh');
        }
        set_time_limit(0);

        $id_porduct = $this->uri->segment('3');
        $data = [
            'id'            => $this->session->userdata('id'),
            'url'           => 'master_product/detail_product/',
            'title'         => 'Detail Harga Product',
            'get_label'     => $this->M_menu->get_label(),
            'harga'         => $this->M_product->get_hargaproduct($id_porduct)
        ];
        // var_dump($data['harga']);die;
        $this->load->view('template_claim/top_header');
        $this->load->view('template_claim/header');
        $this->load->view('template_claim/sidebar', $data);
        $this->load->view('template_claim/top_content', $data);
        $this->load->view('master_product/product_detail_harga', $data);
        $this->load->view('template_claim/bottom_content');
        $this->load->view('template_claim/footer');
    }

    public function product_detail_harga_apps()
    {
        $logged_in = $this->session->userdata('logged_in');
        if (!isset($logged_in) || $logged_in != TRUE) {
            redirect('login/', 'refresh');
        }
        set_time_limit(0);

        $id_porduct = $this->uri->segment('3');
        $data = [
            'id'            => $this->session->userdata('id'),
        'url'           => 'master_product/detail_product/',
            'title'         => 'Detail Harga Product',
            'get_label'     => $this->M_menu->get_label(),
            'harga'         => $this->M_product->get_hargaproduct_apps($id_porduct)
        ];
        // var_dump($data['harga']);die;
        $this->load->view('template_claim/top_header');
        $this->load->view('template_claim/header');
        $this->load->view('template_claim/sidebar', $data);
        $this->load->view('template_claim/top_content', $data);
        $this->load->view('master_product/product_detail_harga_apps', $data);
        $this->load->view('template_claim/bottom_content');
        $this->load->view('template_claim/footer');
    }

    public function product_detail_harga_dp()
    {
        $logged_in = $this->session->userdata('logged_in');
        if (!isset($logged_in) || $logged_in != TRUE) {
            redirect('login/', 'refresh');
        }
        set_time_limit(0);

        $kodeprod = $this->uri->segment('3');
        $data = [
            'id'            => $this->session->userdata('id'),
            'url'           => 'master_product/detail_product/',
            'title'         => 'Detail Harga Product',
            'get_label'     => $this->M_menu->get_label(),
            'harga'         => $this->M_product->get_hargaproduct_dp($kodeprod)
        ];
        // var_dump($data['harga']);die;
        $this->load->view('template_claim/top_header');
        $this->load->view('template_claim/header');
        $this->load->view('template_claim/sidebar', $data);
        $this->load->view('template_claim/top_content', $data);
        $this->load->view('master_product/product_detail_harga_dp', $data);
        $this->load->view('template_claim/bottom_content');
        $this->load->view('template_claim/footer');
    }

    public function activer_product($flag = null, $id = null)
    {
        $logged_in = $this->session->userdata('logged_in');
        if (!isset($logged_in) || $logged_in != TRUE) {
            redirect('login/', 'refresh');
        }
        $this->M_product->activer_product($flag, $id);
    }

    public function activer_produksi($flag = null, $id = null)
    {
        $logged_in = $this->session->userdata('logged_in');
        if (!isset($logged_in) || $logged_in != TRUE) {
            redirect('login/', 'refresh');
        }
        $this->M_product->activer_produksi($flag, $id);
    }

    public function pricelist()
    {
        $logged_in = $this->session->userdata('logged_in');
        if (!isset($logged_in) || $logged_in != TRUE) {
            redirect('login/', 'refresh');
        }
        set_time_limit(0);
        $id = $this->session->userdata('id');

        $data = [
            'id'            => $this->session->userdata('id'),
            'url'           => 'master_product/product/',
            'title'         => 'Product',
            'get_label'     => $this->M_menu->get_label(),
            'product'       => $this->M_product->get_product(),
            'suppq'         => $this->M_product->getSupp(),
            'group'         => $this->M_product->getGroup(),
            'subgroup'      => $this->M_product->getSubgroup(),
            'jenis'         => $this->M_product->getJenis(),
            's_code'        => $this->model_master_data->site_code(),
            'pricelist'     => $this->M_product->get_pricelist(),
        ];
        // var_dump($data);die;

        $this->load->view('template_claim/top_header');
        $this->load->view('template_claim/header');
        $this->load->view('template_claim/sidebar', $data);
        $this->load->view('template_claim/top_content', $data);
        $this->load->view('master_product/pricelist', $data);
        $this->load->view('template_claim/bottom_content');
        $this->load->view('template_claim/footer');
    }

    public function activer_report($flag = null, $id = null)
    {
        $logged_in = $this->session->userdata('logged_in');
        if (!isset($logged_in) || $logged_in != TRUE) {
            redirect('login/', 'refresh');
        }
        $this->M_product->activer_report($flag, $id);
    }

    public function tambah_product()
    {
        $logged_in = $this->session->userdata('logged_in');
        if (!isset($logged_in) || $logged_in != TRUE) {
            redirect('login/', 'refresh');
        }
        $this->M_product->tambah_product();
    }

    public function tambah_pricelist()
    {
        $logged_in = $this->session->userdata('logged_in');
        if (!isset($logged_in) || $logged_in != TRUE) {
            redirect('login/', 'refresh');
        }
        $data['timezone'] = $this->model_sales_omzet->timezone2();
        $this->M_product->tambah_pricelist($data);
    }

    public function edit_product()
    {
        $logged_in = $this->session->userdata('logged_in');
        if (!isset($logged_in) || $logged_in != TRUE) {
            redirect('login/', 'refresh');
        }
        $this->M_product->edit_product();
    }

    public function edit_product_apps()
    {
        $logged_in = $this->session->userdata('logged_in');
        if (!isset($logged_in) || $logged_in != TRUE) {
            redirect('login/', 'refresh');
        }
        $this->M_product->edit_product_apps();
    }

    public function tambah_harga()
    {
        $logged_in = $this->session->userdata('logged_in');
        if (!isset($logged_in) || $logged_in != TRUE) {
            redirect('login/', 'refresh');
        }
        $this->M_product->tambah_harga();
    }

    public function tambah_harga_apps()
    {
        $logged_in = $this->session->userdata('logged_in');
        if (!isset($logged_in) || $logged_in != TRUE) {
            redirect('login/', 'refresh');
        }
        $this->M_product->tambah_harga_apps();
    }

    public function tambah_harga_jual_dp()
    {
        $logged_in = $this->session->userdata('logged_in');
        if (!isset($logged_in) || $logged_in != TRUE) {
            redirect('login/', 'refresh');
        }
        $this->M_product->tambah_harga_jual_dp();
    }

    public function update_harga()
    {
        $logged_in = $this->session->userdata('logged_in');
        if (!isset($logged_in) || $logged_in != TRUE) {
            redirect('login/', 'refresh');
        }
        $this->M_product->update_harga();
    }

    public function update_harga_apps()
    {
        $logged_in = $this->session->userdata('logged_in');
        if (!isset($logged_in) || $logged_in != TRUE) {
            redirect('login/', 'refresh');
        }
        $this->M_product->update_harga_apps();
    }

    public function update_harga_dp()
    {
        $logged_in = $this->session->userdata('logged_in');
        if (!isset($logged_in) || $logged_in != TRUE) {
            redirect('login/', 'refresh');
        }
        $this->M_product->update_harga_dp();
    }

    public function delete_harga()
    {
        $logged_in = $this->session->userdata('logged_in');
        if (!isset($logged_in) || $logged_in != TRUE) {
            redirect('login/', 'refresh');
        }
        $id_product = $this->uri->segment('3');
        $id_detail = $this->uri->segment('4');
        $this->M_product->delete_harga($id_detail);
        redirect("master_product/product_detail_harga/$id_product");
    }

    public function delete_harga_apps()
    {
        $logged_in = $this->session->userdata('logged_in');
        if (!isset($logged_in) || $logged_in != TRUE) {
            redirect('login/', 'refresh');
        }
        $id_product = $this->uri->segment('3');
        $id_detail = $this->uri->segment('4');
        $this->M_product->delete_harga_apps($id_detail);
        redirect("master_product/product_detail_harga_apps/$id_product");
    }

    public function delete_harga_dp()
    {
        $logged_in = $this->session->userdata('logged_in');
        if (!isset($logged_in) || $logged_in != TRUE) {
            redirect('login/', 'refresh');
        }
        $id_product = $this->uri->segment('3');
        $id_detail = $this->uri->segment('4');
        $this->M_product->delete_harga_dp($id_detail);
        redirect("master_product/product_detail_harga_dp/$id_product");
    }

    public function export_product_apps()
    {
        $id = $this->session->userdata('id');
        if (function_exists('date_default_timezone_set'))
            date_default_timezone_set('Asia/Jakarta');
        $query = "
            select a.kodeprod, a.apps_namaprod, a.apps_deskripsi, a.apps_images, a.apps_konversi_sedang_ke_kecil, a.apps_urutan, a.apps_status_aktif, a.apps_min_pembelian, a.apps_status_promosi_coret, a.apps_satuan_online,
                    b.apps_harga_ritel_gt,b.apps_harga_grosir_mt,b.apps_harga_semi_grosir
            from mpm.tabprod a INNER JOIN mpm.prod_detail_apps b
                on a.kodeprod = b.kodeprod
            where b.tgl = (
                select max(c.tgl)
                from mpm.prod_detail_apps c
                where b.kodeprod = c.kodeprod
            )
            ORDER BY a.kodeprod
        ";
        $hasil = $this->db->query($query);
        query_to_csv($hasil, TRUE, "Master_product(Apps).csv");
    }

    public function export_product()
    {
        $id = $this->session->userdata('id');
        $query = "
        select  a.KODEPROD, a.KODE_PRC, a.kodeprod_deltomed, a.NAMAPROD, e.namasupp, a.SATUAN, a.ISISATUAN, a.grup, c.nama_group,
        a.subgroup, d.nama_sub_group, a.berat, a.volume, a.qty1, a.qty2, a.qty3, b.h_dp, a.active as status_report, a.produksi as status_spk          
        from   mpm.tabprod a INNER JOIN mpm.prod_detail b
        on a.kodeprod = b.kodeprod LEFT JOIN 
        (
            select a.kode_group, a.nama_group
            from mpm.tbl_group a
        )c on a.grup = c.kode_group LEFT JOIN 
        (
            select a.sub_group, a.nama_sub_group
            from db_produk.t_sub_group a
        )d on a.subgroup = d.sub_group LEFT JOIN
        (
            select a.supp, a.namasupp
            from mpm.tabsupp a
        )e on a.supp = e.supp
        where b.tgl = (
            select max(c.tgl)
            from mpm.prod_detail c
            where b.kodeprod = c.kodeprod
        ) 
        ORDER BY a.kodeprod
        ";
        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";
        $hasil = $this->db->query($query);
        query_to_csv($hasil, TRUE, "Master_product.csv");
    }

    public function cekapi(){
        $site_code = $this->input->post('site_code');
        // echo "site_code : ".$site_code;

        redirect("http://site.muliaputramandiri.com/restapi/api/request/pricelist?kode=$site_code&X-API-KEY=123&token=64646ecaf773b3192034998fccbb27b5",'refresh');
        // $http://localhost:81/restapi/api/request/pricelist?kode=smg14&X-API-KEY=123&token=64646ecaf773b3192034998fccbb27b5
    }

}
