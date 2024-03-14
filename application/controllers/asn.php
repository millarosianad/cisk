<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Asn extends MY_Controller
{
    var $nocab;
    var $options;
    var $image_properties_pdf = array(
          'src' => 'assets/css/images/pdf.png',
          'alt' => 'Printing Document',
          'class' => 'post_images',
          'width' => '50',
          'height' => '50',
          'title' => 'Printer',
          'border' => 0,
    );
    var $image_properties_excel = array(
          'src' => 'assets/css/images/excel.png',
          'alt' => 'Printing Document',
          'class' => 'post_images',
          'width' => '50',
          'height' => '50',
          'title' => 'Printer',
          'border' => 0,
    );
    var $querymenu;
    var $attr = array(
              'width'      => '800',
              'height'     => '600',
              'scrollbars' => 'yes',
              'status'     => 'yes',
              'resizable'  => 'yes',
              'screenx'   =>  '\'+((parseInt(screen.width) - 800)/2)+\'',
              'screeny'   =>  '\'+((parseInt(screen.height) - 600)/2)+\'',
            );

    function asn()
    {
        $logged_in= $this->session->userdata('logged_in');
        if(!isset($logged_in) || $logged_in != TRUE)
        {
            redirect('login/','refresh');
        }
        set_time_limit(0);
        $this->load->library(array('table','template','Excel_generator', 'form_validation','email'));
        $this->load->helper('url');
        $this->load->helper('csv');
        $this->load->model('M_asn');
        $this->load->model('M_menu');
        $this->load->database();
    }

    public function list_asn(){

        $data = [
            'id'            => $this->session->userdata('id'),
            'url_import'    => 'asn/upload_asn',
            'url_export'    => 'asn/export_asn',
            'title'         => 'Advanced Shipping Notes (ASN)',
            'get_label'     => $this->M_menu->get_label(),
            'get_po'        => $this->M_asn->get_po(),
            'supp'          => $this->session->userdata('supp')
        ];

        $supp = $this->session->userdata('supp');
        if ($supp == '012') {
            // $view = 'list_asn';
            $view = 'list_asn_intrafood';
        }else{
            $view = 'list_asn_marguna';
        }

        $this->load->view('template_claim/top_header');
        $this->load->view('template_claim/header');
        $this->load->view('template_claim/sidebar',$data);
        $this->load->view('template_claim/top_content',$data);
        $this->load->view('asn/'.$view,$data);
        $this->load->view('template_claim/bottom_content');
        $this->load->view('template_claim/footer');
    }

    public function get_id_po($signature){
        $cek_id_po = $this->db->query("select id_po from mpm.t_asn where signature = '$signature'")->result();
        foreach($cek_id_po as $key){
            $id_po = $key->id_po;
            // echo $id_po;
            // die;
        }
        return $id_po;
    }

    public function input_asn($id_po){
        $supp = $this->session->userdata('supp');
        if ($supp == '012') {
            // $view = 'input_asn';
            $view = 'input_asn_intrafood';
        }else{
            $view = 'input_asn_marguna';
        }
        $data = [
            'id' => $this->session->userdata('id'),
            'title' => 'Input Advanced Shipping Notes (ASN)',
            'get_po_by_produk' => $this->M_asn->get_po_by_produk_asn($id_po),
            'get_po_by_produk_table_asn' => $this->M_asn->get_table_produk_asn($id_po),
            'url_input' => 'asn/proses_tambah_asn',
            'url_edit' => 'asn/proses_edit_asn',
            'get_label' => $this->M_menu->get_label(),
        ];
        $this->load->view('template_claim/top_header');
        $this->load->view('template_claim/header');
        $this->load->view('template_claim/sidebar',$data);
        $this->load->view('template_claim/top_content',$data);
        $this->load->view('asn/'.$view,$data);
        $this->load->view('template_claim/bottom_content');
        $this->load->view('template_claim/footer');
    }

    public function proses_tambah_asn(){
        $this->load->model('model_sales_omzet');
        // echo "<pre>";
        // echo "id_po : ".$this->input->post('id_po')."<br>";
        $signature = md5($this->input->post('id_po'));
        // echo "signature : ".$signature."<br>";
        // echo "kodeprod : ".$this->input->post('kodeprod')."<br>";
        // echo "tanggal_kirim : ".$this->input->post('tanggal_kirim')."<br>";
        $tanggal_kirim = trim($this->input->post('tanggal_kirim'));        
        if ($tanggal_kirim == '') {
            $convert_tanggal_kirim='';
        }else{
            $convert_tanggal_kirim=strftime('%Y-%m-%d',strtotime($tanggal_kirim));
        }  
        // echo "convert_tanggal_kirim : ".$convert_tanggal_kirim."<br>";
        // echo "batch number : ".$this->input->post('batch_number')."<br>";
        // echo "jumlah_unit : ".$this->input->post('jumlah_unit')."<br>";
        // echo "status_pemenuhan : ".$this->input->post('status_pemenuhan')."<br>";
        // echo "nama_ekspedisi : ".$this->input->post('nama_ekspedisi')."<br>";
        // echo "tanggal_tiba : ".$this->input->post('tanggal_tiba')."<br>";
        // echo "keterangan : ".$this->input->post('keterangan')."<br>";
        // echo "created_date : ".$this->model_sales_omzet->timezone2();

        // echo "est_leadime : ".$this->input->post('tanggal_kirim')->diff($this->input->post('tanggal_tiba'));

        $datetime1 = new DateTime($this->input->post('tanggal_kirim'));
        $datetime2 = new DateTime($this->input->post('tanggal_tiba'));    
        $est_lead_time = $datetime1->diff($datetime2)->d;

        // echo "est_lead_time : ".$est_lead_time;
        // echo "</pre>";
        // die;
        $data = [
            'id_po'             => $this->input->post('id_po'),
            'kodeprod'          => $this->input->post('kodeprod'),
            'batch_number'      => $this->input->post('batch_number'),
            'nodo'              => $this->input->post('nodo'),
            'ed'                => $this->input->post('ed'),
            'tanggal_kirim'     => $this->input->post('tanggal_kirim'),
            'jumlah_unit'       => $this->input->post('jumlah_unit'),
            'jumlah_karton'     => $this->input->post('jumlah_karton'),
            'status_pemenuhan'  => $this->input->post('status_pemenuhan'),
            'nama_ekspedisi'    => $this->input->post('nama_ekspedisi'),
            'est_lead_time'     => $est_lead_time,
            'tanggal_tiba'      => $this->input->post('tanggal_tiba'),
            'keterangan'        => $this->input->post('keterangan'),
            'signature'         => md5($this->input->post('id_po')),
            'created_date'      => $this->model_sales_omzet->timezone2(),
            'created_by'        => $this->session->userdata('id'),
        ];

        // $data['proses'] = $this->M_asn->proses_tambah_asn($data);
        $id_asn = $this->M_asn->insert('mpm.t_asn', $data);

        //update leadtime asn
        // $update_leadtime = $this->M_asn->update_leadtime($data);

        $hitung_unit_po = $this->M_asn->get_total_unit_po($this->input->post('id_po'));
        foreach ($hitung_unit_po as $key) {
            $total_unit_po = $key->total_unit;
            $total_karton_po = $key->total_karton;
            $total_produk_po = $key->total_produk;
        } 

        $hitung_unit_asn = $this->M_asn->get_total_unit_asn($this->input->post('id_po'));
        foreach ($hitung_unit_asn as $key) {
            $total_unit_asn = $key->total_unit_asn;
            $total_karton_asn = $key->total_karton_asn;
            $total_produk_asn = $key->total_produk_asn;
        }

        $data = [
            "id_asn"            => $id_asn,
            "id_po"             => $this->input->post('id_po'),
            "total_unit_po"     => $total_unit_po,
            "total_unit_asn"    => $total_unit_asn,
            "total_karton_po"   => $total_karton_po,
            "total_karton_asn"  => $total_karton_asn,
            "total_produk_po"   => $total_produk_po,
            "total_produk_asn"  => $total_produk_asn,
            "status"            => 1,
        ];

        $insert_cek_asn = $this->M_asn->insert_cek_asn($data);
        if($insert_cek_asn){
            echo "<script>alert('input asn berhasil'); </script>";
            redirect('asn/input_asn/'.$this->input->post('id_po'),'refresh');
        }else{
            echo "<script>alert('warning !! input asn gagal. Silahkan coba ulangi. Jika perlu segera hubungi IT'); </script>";
            redirect('asn/input_asn/'.$this->input->post('id_po'),'refresh');
        }



    }

    public function closed_asn($id_po){
        $logged_in= $this->session->userdata('logged_in');
        if(!isset($logged_in) || $logged_in != TRUE)
        {
            redirect('login/','refresh');
        }
        // $id_po = $this->uri->segment('3');
        $this->M_asn->closed_asn($id_po);
    }

    public function edit_asn($signature,$id_asn,$supp, $kodeprod){

        $cek_id_po = $this->db->query("select id_po from mpm.t_asn where signature = '$signature'")->result();
        foreach($cek_id_po as $key){
            $id_po = $key->id_po;
            // echo $id_po;
            // die;
        }

        $data = [
            'id' => $this->session->userdata('id'),
            'title' => 'Edit Advanced Shipping Notes (ASN)',
            'get_po_by_produk' => $this->M_asn->edit_produk_asn($id_po,$id_asn),
            'url_input' => 'asn/proses_tambah_asn',
            'url_edit' => 'asn/proses_edit_asn',
            'get_label' => $this->M_menu->get_label(),
            'get_po_by_produk' => $this->M_asn->get_po_by_produk_asn($id_po, $kodeprod, $id_asn),
            'get_po_by_produk_table_asn' => $this->M_asn->get_table_produk_asn($id_po),
        ];

        if ($supp == '012') {
            // $view = 'edit_asn';
            $view = 'input_asn_intrafood';
        }else{
            // $view = 'edit_asn_marguna';
            $view = 'input_asn_marguna';
        }

        $this->load->view('template_claim/top_header');
        $this->load->view('template_claim/header');
        $this->load->view('template_claim/sidebar',$data);
        $this->load->view('template_claim/top_content',$data);
        $this->load->view('asn/'.$view,$data);
        $this->load->view('template_claim/bottom_content');
        $this->load->view('template_claim/footer');
    }

    public function proses_edit_asn(){
        // echo "<pre>";
        // // echo "id : ".$id;
        // echo "id_asn : ".$this->input->post('id_asn');
        // echo "id_po : ".$this->input->post('id_po');
        // echo "supp : ".$this->input->post('supp');
        // echo "kodeprod : ".$this->input->post('kodeprod');
        // echo "tanggal_kirim : ".$this->input->post('tanggal_kirim');
        // echo "tanggal_tiba : ".$this->input->post('tanggal_tiba');
        // echo "jumlah_unit : ".$this->input->post('jumlah_unit');
        // echo "jumlah_karton : ".$this->input->post('jumlah_karton');
        // echo "nama_ekspedisi : ".$this->input->post('nama_ekspedisi');
        // echo "keterangan : ".$this->input->post('keterangan');
        // echo "status_pemenuhan : ".$this->input->post('status_pemenuhan');
        // echo "supp : ".$supp;

        $datetime1 = new DateTime($this->input->post('tanggal_kirim'));
        $datetime2 = new DateTime($this->input->post('tanggal_tiba'));    
        $est_lead_time = $datetime1->diff($datetime2)->d;

        // echo "est_lead_time : ".$est_lead_time;
        // echo "</pre>";
        // die;
        $id_asn = $this->input->post('id_asn');
        $id_po = $this->input->post('id_po');
        $supp = $this->input->post('supp');
        // echo "id_asn : ".$id_asn;
        // die;
        $data = [
            'batch_number'      => $this->input->post('batch_number'),
            'nodo'              => $this->input->post('nodo'),
            'ed'                => $this->input->post('ed'),
            'tanggal_kirim'     => $this->input->post('tanggal_kirim'),
            'jumlah_karton'     => $this->input->post('jumlah_karton'),
            'jumlah_unit'       => $this->input->post('jumlah_unit'),
            'nama_ekspedisi'    => $this->input->post('nama_ekspedisi'),
            'est_lead_time'     => $est_lead_time,
            'tanggal_tiba'      => $this->input->post('tanggal_tiba'),
            'keterangan'        => $this->input->post('keterangan'),
            'status_pemenuhan'  => $this->input->post('status_pemenuhan')
        ];
        $update = $this->M_asn->update_asn('mpm.t_asn', $data, $id_asn);
        // var_dump($update);
        // if($update){
        //     echo "<script>alert('update berhasil'); </script>";
        //     redirect('asn/edit_asn/'.$id_po.'/'.$id_asn.'/'.$supp,'refresh');
        // }else{
        //     echo "<script>alert('warning !! update gagal. Silahkan coba ulangi. Jika perlu segera hubungi IT'); </script>";
        //     redirect('asn/edit_asn/'.$id_po.'/'.$id_asn.'/'.$supp,'refresh');
        // }


        $hitung_unit_po = $this->M_asn->get_total_unit_po($this->input->post('id_po'));
        foreach ($hitung_unit_po as $key) {
            $total_unit_po = $key->total_unit;
            $total_karton_po = $key->total_karton;
            $total_produk_po = $key->total_produk;
        } 

        // echo "total_unit_po : ".$total_unit_po;
        // echo "total_karton_po : ".$total_karton_po;
        // echo "total_produk_po : ".$total_produk_po;

        $hitung_unit_asn = $this->M_asn->get_total_unit_asn($this->input->post('id_po'));
        foreach ($hitung_unit_asn as $key) {
            $total_unit_asn = $key->total_unit_asn;
            $total_karton_asn = $key->total_karton_asn;
            $total_produk_asn = $key->total_produk_asn;
        }

        // echo "total_unit_asn : ".$total_unit_asn;
        // echo "total_karton_asn : ".$total_karton_asn;
        // echo "total_produk_asn : ".$total_produk_asn;

        // die;

        $data = [
            "id_asn"            => $id_asn,
            "id_po"             => $this->input->post('id_po'),
            "total_unit_po"     => $total_unit_po,
            "total_unit_asn"    => $total_unit_asn,
            "total_karton_po"   => $total_karton_po,
            "total_karton_asn"  => $total_karton_asn,
            "total_produk_po"   => $total_produk_po,
            "total_produk_asn"  => $total_produk_asn,
            "status"            => 1,
        ];

        $insert_cek_asn = $this->M_asn->insert_cek_asn($data);
        if($insert_cek_asn){
            echo "<script>alert('update berhasil'); </script>";
            redirect('asn/input_asn/'.$id_po,'refresh');
        }else{
            echo "<script>alert('warning !! update gagal. Silahkan coba ulangi. Jika perlu segera hubungi IT'); </script>";
            redirect('asn/edit_asn/'.$id_po.'/'.$id_asn.'/'.$supp,'refresh');
        }

    }

    public function delete_produk_asn($id_po,$id_asn){

        $this->M_asn->delete_produk_asn($id_po,$id_asn);

        $hitung_unit_po = $this->M_asn->get_total_unit_po($id_po);
        foreach ($hitung_unit_po as $key) {
            $total_unit_po = $key->total_unit;
            $total_karton_po = $key->total_karton;
            $total_produk_po = $key->total_produk;
        } 

        // echo "total_unit_po : ".$total_unit_po;
        // echo "total_karton_po : ".$total_karton_po;
        // echo "total_produk_po : ".$total_produk_po;

        $hitung_unit_asn = $this->M_asn->get_total_unit_asn($id_po);
        foreach ($hitung_unit_asn as $key) {
            $total_unit_asn = $key->total_unit_asn;
            $total_karton_asn = $key->total_karton_asn;
            $total_produk_asn = $key->total_produk_asn;
        }

        // echo "total_unit_asn : ".$total_unit_asn;
        // echo "total_karton_asn : ".$total_karton_asn;
        // echo "total_produk_asn : ".$total_produk_asn;

        // die;

        $data = [
            "id_asn"            => $id_asn,
            "id_po"             => $id_po,
            "total_unit_po"     => $total_unit_po,
            "total_unit_asn"    => $total_unit_asn,
            "total_karton_po"   => $total_karton_po,
            "total_karton_asn"  => $total_karton_asn,
            "total_produk_po"   => $total_produk_po,
            "total_produk_asn"  => $total_produk_asn,
            "status"            => 1,
        ];

        $insert_cek_asn = $this->M_asn->insert_cek_asn($data);
        if($insert_cek_asn){
            echo "<script>alert('delete berhasil'); </script>";
            redirect('asn/input_asn/'.$id_po,'refresh');
        }else{
            echo "<script>alert('warning !! delete gagal. Silahkan coba ulangi. Jika perlu segera hubungi IT'); </script>";
            redirect('asn/input_asn/'.$id_po,'refresh');
        }

    }

    public function export_asn(){

        $from = $this->input->post('from');
        $to = $this->input->post('to');
        $tahun_from = substr($from,0,4);
        $tahun_to = substr($to,0,4);
        $bulan_from = substr($from,5,2);
        $bulan_to = substr($to,5,2);

        if ($tahun_from - $tahun_to != 0 || $bulan_from - $bulan_to != 0) { 
            echo "<script>alert('export gagal ! silahkan masukkan tahun dan rentang bulan yang sama'); </script>";
            redirect('asn/list_asn/','refresh');
        }

        $supp = $this->session->userdata('supp');
        if($supp == '012'){
            $suppx = "a.supp = '012' and";
            
            $sql = "
            select 	a.id, e.id_asn, d.branch_name as 'branch',d.nama_comp as 'sub branch', 
                    DATE_FORMAT(a.tglpo,'%Y-%m-%d') as 'tgl po',a.nopo, a.company, a.tipe,
                    a.alamat,b.kodeprod as 'kodeproduk',b.namaprod as 'nama produk',sum(b.banyak_karton) as 'total karton',
                    sum(round((b.berat*b.banyak_karton))) as 'total berat', 
                    e.total_karton as 'total karton(*isi angka tanpa koma)',
                    e.status_pemenuhan as 'kunci pemenuhan(*isi angka tanpa koma)',
                    e.batch_number as 'batch number (*isi)',
                    e.nodo as 'no surat jalan/DO (*isi)',
                    e.ed as 'asn expired date (*isi bln/tgl/thn)',
                    e.tanggal_kirim as 'asn tgl kirim (*isi bln/tgl/thn)',
                    e.tanggal_tiba as 'asn tgl tiba (*isi bln/tgl/thn)',
                    e.nama_ekspedisi as 'asn ekspedisi (*isi)',e.keterangan as 'asn keterangan(*isi)',e.status_closed as 'asn status closed(*isi)'
            from mpm.po a INNER JOIN 
            (
                select a.id_ref, a.kodeprod, a.banyak, a.banyak_karton, a.berat, a.namaprod
                from mpm.po_detail a
                where a.deleted = 0
            )b on a.id = b.id_ref LEFT JOIN
            (
                select a.id, a.username
                from mpm.`user` a
            )c on a.userid = c.id LEFT JOIN
            (
                select a.kode,a.branch_name,a.nama_comp,a.kode_comp
                from
                (
                    select concat(a.kode_comp,a.nocab) as kode, a.branch_name, a.nama_comp, a.kode_comp
                    from mpm.tbl_tabcomp a
                    where a.`status` = 1
                    GROUP BY kode
                )a INNER JOIN
                (
                    select concat(a.kode_comp,a.nocab) as kode
                    from db_dp.t_dp a
                    where tahun = $tahun_from and a.`status` = 1
                )b on a.kode = b.kode
                GROUP BY kode_comp
            )d on c.username = d.kode_comp LEFT JOIN
            (
                SELECT a.id_po, a.kodeprod, sum(a.jumlah_unit) as total_unit, sum(a.jumlah_karton) as total_karton, a.tanggal_kirim, a.tanggal_tiba, a.nama_ekspedisi, a.keterangan, a.status_pemenuhan, a.status_closed, a.id as id_asn, a.batch_number, a.nodo, a.ed
                from mpm.t_asn a
                GROUP BY a.id_po, a.kodeprod
            )e on a.id = e.id_po and b.kodeprod = e.kodeprod
            where $suppx a.deleted = 0 and a.nopo not like '/mpm%' and a.nopo not like '%batal%' and (date(a.tglpo) between '$from' and '$to')
            GROUP BY a.id, b.kodeprod
            ORDER BY a.id DESC
            ";

        }elseif($supp == '002'){
            $suppx = "a.supp = '$supp' and";

            $sql = "
            select 	a.id, e.id_asn, d.branch_name as 'branch',d.nama_comp as 'sub branch', 
                    DATE_FORMAT(a.tglpo,'%Y-%m-%d') as 'tgl po',a.nopo, a.company, a.tipe,
                    a.alamat,b.kodeprod as 'kodeproduk',b.namaprod as 'nama produk',sum(b.banyak) as 'total unit',
                    sum(round((b.berat*b.banyak_karton))) as 'total berat', 
                    e.total_unit as 'total unit(*isi angka tanpa koma)',
                    e.status_pemenuhan as 'kunci pemenuhan(*isi angka tanpa koma)',
                    e.batch_number as 'batch number (*isi)',
                    e.nodo as 'no surat jalan/DO (*isi)',
                    e.ed as 'asn expired date (*isi bln/tgl/thn)',
                    e.tanggal_kirim as 'asn tgl kirim (*isi bln/tgl/thn)',
                    e.tanggal_tiba as 'asn tgl tiba (*isi bln/tgl/thn)',
                    e.nama_ekspedisi as 'asn ekspedisi (*isi)',e.keterangan as 'asn keterangan(*isi)',e.status_closed as 'asn status closed(*isi)'
            from mpm.po a INNER JOIN 
            (
                select a.id_ref, a.kodeprod, a.banyak, a.banyak_karton, a.berat, a.namaprod
                from mpm.po_detail a
                where a.deleted = 0
            )b on a.id = b.id_ref LEFT JOIN
            (
                select a.id, a.username
                from mpm.`user` a
            )c on a.userid = c.id LEFT JOIN
            (
                select a.kode,a.branch_name,a.nama_comp,a.kode_comp
                from
                (
                    select concat(a.kode_comp,a.nocab) as kode, a.branch_name, a.nama_comp, a.kode_comp
                    from mpm.tbl_tabcomp a
                    where a.`status` = 1
                    GROUP BY kode
                )a INNER JOIN
                (
                    select concat(a.kode_comp,a.nocab) as kode
                    from db_dp.t_dp a
                    where tahun = $tahun_from and a.`status` = 1
                )b on a.kode = b.kode
                GROUP BY kode_comp
            )d on c.username = d.kode_comp LEFT JOIN
            (
                SELECT a.id_po, a.kodeprod, sum(a.jumlah_unit) as total_unit, sum(a.jumlah_karton) as total_karton, a.tanggal_kirim, a.tanggal_tiba, a.nama_ekspedisi, a.keterangan, a.status_pemenuhan, a.status_closed, a.id as id_asn, a.batch_number, a.nodo, a.ed
                from mpm.t_asn a
                GROUP BY a.id_po, a.kodeprod
            )e on a.id = e.id_po and b.kodeprod = e.kodeprod
            where $suppx a.deleted = 0 and a.nopo not like '/mpm%' and a.nopo not like '%batal%' and (date(a.tglpo) between '$from' and '$to')
            GROUP BY a.id, b.kodeprod
            ORDER BY a.id DESC
            ";
        }else{
            echo "<script>alert('user anda tidak terdaftar sebagai user principal'); </script>";
            redirect('asn/list_asn/','refresh');
        }

        
        $proses = $this->db->query($sql);       
        
        // echo "<pre>";
        // print_r($sql);
        // echo "</pre>";
        
        query_to_csv($proses,TRUE,'advanced shipping notes '."$from"."_"."$to".'.csv');
    }

    public function upload_asn($supp)
    {
        $this->load->model('model_outlet_transaksi');
        if (!is_dir('./assets/uploads/asn/' . date('Ym') . '/')) {
            @mkdir('./assets/uploads/asn/' . date('Ym') . '/', 0777);
        }

        if ($supp == '012') { // jika intrafood maka gunakan karton
            $params_satuan = 'jumlah_karton';
        }else{ // jika selain intrafood maka gunakan unit
            $params_satuan = 'jumlah_unit';
        }

        $id = $this->session->userdata('id');
        // $tgl_created = date('Y-m-d H:i:s');
        $created_date = $this->model_outlet_transaksi->timezone();
        $this->load->library('upload'); // Load librari upload        
        $config['upload_path'] = './assets/uploads/asn/' . date('Ym') . '';
        // $config['allowed_types'] = 'xls|xlsx|csv';
        $config['allowed_types'] = '*';    
        $config['max_size']  = '2048';
        $config['overwrite'] = true;
        $this->upload->initialize($config);

        if ($this->upload->do_upload('file')) 
        {
            $upload_data = $this->upload->data();
            $filename = $upload_data['orig_name'];
            $file_type = $upload_data['file_type'];
            //   echo "filename : ".$filename."<br>";
            //   echo "file_type : ".$file_type;
            $this->load->library('excel');
            $object = PHPExcel_IOFactory::load("assets/uploads/asn/" . date('Ym') . "/$filename");

            $jumlahSheet = $object->getSheetCount();
            // echo "jumlahsheet : ".$jumlahSheet."<br>";
            // die;
            if ($jumlahSheet > 1) {
                echo "jumlah_sheet : ".$jumlahSheet;
                echo "<script>alert('upload file gagal karena file mempunyai lebih dari 1 sheet'); </script>";
                redirect('asn/list_asn/','refresh');
            }
            foreach ($object->getWorksheetIterator() as $worksheet) {

                $highestRow = $worksheet->getHighestRow();
                $highestColumn = $worksheet->getHighestColumn();

                for ($row = 2; $row <= $highestRow; $row++) {          
                
                    $id_po = $worksheet->getCellByColumnAndRow(0, $row)->getValue();
                    $id_asn = $worksheet->getCellByColumnAndRow(1, $row)->getValue();
                    $kodeprod = $worksheet->getCellByColumnAndRow(9, $row)->getValue();
                    $jumlah_unit = $worksheet->getCellByColumnAndRow(13, $row)->getValue();
                    $status_pemenuhan = $worksheet->getCellByColumnAndRow(14, $row)->getValue();
                    $batch_number = $worksheet->getCellByColumnAndRow(15, $row)->getValue();
                    $nodo = $worksheet->getCellByColumnAndRow(16, $row)->getValue();
                    $ed = $worksheet->getCellByColumnAndRow(17, $row)->getValue();
                    $tgl_kirim = $worksheet->getCellByColumnAndRow(18, $row)->getValue();
                    $tgl_tiba = $worksheet->getCellByColumnAndRow(19, $row)->getValue();
                    $ekspedisi = $worksheet->getCellByColumnAndRow(20, $row)->getValue();
                    $keterangan = $worksheet->getCellByColumnAndRow(21, $row)->getValue();
                    $status_closed = $worksheet->getCellByColumnAndRow(22, $row)->getValue();

                    // echo "<pre>";
                    // echo "id_po : ".$id_po."<br>";
                    // echo "kodeprod : ".$kodeprod."<br>";
                    // echo "jumlah_unit : ".$jumlah_unit."<br>";
                    // echo "status_pemenuhan : ".$status_pemenuhan."<br>";
                    // echo "batch_number : ".$batch_number."<br>";
                    // echo "tgl_kirim : ".$tgl_kirim."<br>";
                    // echo "tgl_tiba : ".$tgl_tiba."<br>";
                    // echo "ekspedisi : ".$ekspedisi."<br>";
                    // echo "keterangan : ".$keterangan."<br>";
                    // echo "status_closed : ".$status_closed."<br>";
                    
                    // die;

                    $datetime1 = new DateTime(strftime('%Y-%m-%d',strtotime($tgl_kirim)));
                    $datetime2 = new DateTime(strftime('%Y-%m-%d',strtotime($tgl_tiba)));    
                    $est_lead_time = $datetime1->diff($datetime2)->d;
                    // echo "est_lead_time : ".$est_lead_time."<hr>";
                    // echo "</pre>";

                    if ($jumlah_unit != NULL) {
                        
                        $this->db->where('id', $id_asn);
                        $this->db->where('kodeprod', $kodeprod);
                        $cek = $this->db->get('mpm.t_asn')->num_rows();
                        // echo "cek : ".$cek;

                        if ($cek) {
                            //update
                            $data = [
                                // 'id_po'             => $id_po,       
                                // 'kodeprod'          => $kodeprod,
                                $params_satuan      => $jumlah_unit,
                                'status_pemenuhan'  => $status_pemenuhan,
                                'batch_number'      => $batch_number,
                                'nodo'              => $nodo,
                                'ed'                => $ed,
                                'tanggal_kirim'     => strftime('%Y-%m-%d',strtotime($tgl_kirim)),
                                'tanggal_tiba'      => strftime('%Y-%m-%d',strtotime($tgl_tiba)),
                                'est_lead_time'     => $est_lead_time,
                                'nama_ekspedisi'    => $ekspedisi,
                                'keterangan'        => $keterangan,
                                'status_closed'     => $status_closed,
                                // 'created_date'      => $tgl_created,
                                // 'created_by'        => $id,
                                'last_updated'      => $created_date,
                                'last_updated_by'   => $id,
                                // 'filename'          => $filename
                            ]; 
                            $update = $this->M_asn->update_asn('mpm.t_asn', $data, $id_asn);

                            $hitung_unit_po = $this->M_asn->get_total_unit_po($id_po);
                            foreach ($hitung_unit_po as $key) {
                                $total_unit_po = $key->total_unit;
                                $total_karton_po = $key->total_karton;
                                $total_produk_po = $key->total_produk;
                            } 

                            $hitung_unit_asn = $this->M_asn->get_total_unit_asn($id_po);
                            foreach ($hitung_unit_asn as $key) {
                                $total_unit_asn = $key->total_unit_asn;
                                $total_karton_asn = $key->total_karton_asn;
                                $total_produk_asn = $key->total_produk_asn;
                            }

                            $data = [
                                "id_asn"            => $id_asn,
                                "id_po"             => $id_po,
                                "total_unit_po"     => $total_unit_po,
                                "total_unit_asn"    => $total_unit_asn,
                                "total_karton_po"   => $total_karton_po,
                                "total_karton_asn"  => $total_karton_asn,
                                "total_produk_po"   => $total_produk_po,
                                "total_produk_asn"  => $total_produk_asn,
                                "status"            => 1,
                            ];

                            $insert_cek_asn = $this->M_asn->insert_cek_asn($data); 

                        }else{
                            // echo "insert";    
                            $data = [
                                'id'                => $id_asn,       
                                'id_po'             => $id_po,       
                                'kodeprod'          => $kodeprod,
                                $params_satuan      => $jumlah_unit,
                                'status_pemenuhan'  => $status_pemenuhan,
                                'batch_number'      => $batch_number,
                                'nodo'              => $nodo,
                                'ed'                => strftime('%Y-%m-%d',strtotime($ed)),
                                'tanggal_kirim'     => strftime('%Y-%m-%d',strtotime($tgl_kirim)),
                                'tanggal_tiba'      => strftime('%Y-%m-%d',strtotime($tgl_tiba)),
                                'est_lead_time'     => $est_lead_time,
                                'nama_ekspedisi'    => $ekspedisi,
                                'keterangan'        => $keterangan,
                                'status_closed'     => $status_closed,
                                'created_date'      => $created_date,
                                'created_by'        => $id,
                                // 'last_updated'       => $created_date,
                                // 'last_updated_by'    => $id,
                                // 'filename'          => $filename
                            ];      
    
                            // $id_asn = $this->M_asn->insert('mpm.t_asn', $data);

                            // $insert = $this->db->insert('mpm.t_asn', $data);
                            $insert = $this->M_asn->insert('mpm.t_asn', $data);
                            $id_asn = $this->db->insert_id();
                            $update_digit = $this->db->query("update mpm.t_asn a set a.kodeprod = concat(0,a.kodeprod) where a.id = $id_asn and length(a.kodeprod) = 5");

                            $cek_id_po = $this->db->query("select a.id_po from mpm.t_asn a where a.id = $id_asn limit 1")->result();
                            foreach ($cek_id_po as $a) {
                               $id_po = $a->id_po; 
                            }
                            // die;
                            $hitung_unit_po = $this->M_asn->get_total_unit_po($id_po);
                            foreach ($hitung_unit_po as $key) {
                                $total_unit_po = $key->total_unit;
                                $total_karton_po = $key->total_karton;
                                $total_produk_po = $key->total_produk;
                            } 

                            $hitung_unit_asn = $this->M_asn->get_total_unit_asn($id_po);
                            foreach ($hitung_unit_asn as $key) {
                                $total_unit_asn = $key->total_unit_asn;
                                $total_karton_asn = $key->total_karton_asn;
                                $total_produk_asn = $key->total_produk_asn;
                            }

                            $data = [
                                "id_asn"            => $id_asn,
                                "id_po"             => $id_po,
                                "total_unit_po"     => $total_unit_po,
                                "total_unit_asn"    => $total_unit_asn,
                                "total_karton_po"   => $total_karton_po,
                                "total_karton_asn"  => $total_karton_asn,
                                "total_produk_po"   => $total_produk_po,
                                "total_produk_asn"  => $total_produk_asn,
                                "status"            => 1,
                            ];

                            $insert_cek_asn = $this->M_asn->insert_cek_asn($data);                            
    
                        }
                    }else{
                        echo "<script>alert('import berhasil.'); </script>";
                        redirect('asn/list_asn','refresh');
                    }
                        // echo "<script>alert('import berhasil..'); </script>";
                        // redirect('asn/list_asn','refresh');
                }

                echo "<script>alert('import berhasil...'); </script>";
                redirect('asn/list_asn','refresh');
            }
            echo "<script>alert('import berhasil....'); </script>";
            redirect('asn/list_asn','refresh');         
        } else {
            echo "<script>alert('import gagal.'); </script>";
            redirect('asn/list_asn','refresh');
        }
        echo "<script>alert('import berhasil.....'); </script>";
        redirect('asn/list_asn','refresh');
    }

    public function list_do(){
        $data = [
            'id' => $this->session->userdata('id'),
            'title' => 'Delivery Order (DO)',
            'get_label' => $this->M_menu->get_label(),
            'get_po' => $this->M_asn->get_po_asn()
        ];
        $this->load->view('template_claim/top_header');
        $this->load->view('template_claim/header');
        $this->load->view('template_claim/sidebar',$data);
        $this->load->view('template_claim/top_content',$data);
        $this->load->view('asn/list_do',$data);
        $this->load->view('template_claim/bottom_content');
        $this->load->view('template_claim/footer');
    }

    public function input_do(){
        $id_po = $this->uri->segment('3');
        $data = [
            'id' => $this->session->userdata('id'),
            'title' => 'Input Delivery Order (DO)',
            'get_po_by_produk' =>$this->M_asn->get_po_by_produk_do(),
            'get_po_by_produk_table_do' => $this->M_asn->get_table_produk_do($id_po),
            'url' => 'asn/proses_tambah_do',
            'get_label' => $this->M_menu->get_label(),
        ];
        $this->load->view('template_claim/top_header');
        $this->load->view('template_claim/header');
        $this->load->view('template_claim/sidebar',$data);
        $this->load->view('template_claim/top_content',$data);
        $this->load->view('asn/input_do',$data);
        $this->load->view('template_claim/bottom_content');
        $this->load->view('template_claim/footer');
    }

    public function proses_tambah_do(){

        $data = [
            'id' => $this->session->userdata('id'),
            'id_po' => $this->input->post('id_po'),
            'no_asn' => $this->input->post('no_asn'),
            'no_do' => $this->input->post('no_do'),
            'do_kodeprod' => $this->input->post('do_kodeprod'),
            'do_tanggalKirim' => $this->input->post('do_tanggalKirim'),
            'do_unit' => $this->input->post('do_unit'),
            'do_nama_expedisi' => $this->input->post('do_nama_expedisi'),
            'do_est_lead_time' => $this->input->post('do_est_lead_time'),
            'do_eta' => $this->input->post('do_eta')
        ];

        $data['proses'] = $this->M_asn->proses_tambah_do($data);

    }

    public function edit_do(){
        $data = [
            'id' => $this->session->userdata('id'),
            'title' => 'Edit Delivery Order (DO)',
            'get_po_by_produk' => $this->M_asn->edit_produk_do(),
            'url' => 'asn/proses_edit_do',
            'get_label' => $this->M_menu->get_label(),
        ];
        $this->load->view('template_claim/top_header');
        $this->load->view('template_claim/header');
        $this->load->view('template_claim/sidebar',$data);
        $this->load->view('template_claim/top_content',$data);
        $this->load->view('asn/edit_do',$data);
        $this->load->view('template_claim/bottom_content');
        $this->load->view('template_claim/footer');
    }

    public function proses_edit_do(){
        $data = [
            'id' => $this->session->userdata('id'),
            'id_do' => $this->input->post('id_do'),
            'id_po' => $this->input->post('id_po'),
            'no_asn' => $this->input->post('no_asn'),
            'no_do' => $this->input->post('no_do'),
            'do_kodeprod' => $this->input->post('do_kodeprod'),
            'do_tanggal_kirim' => $this->input->post('do_tanggalKirim'),
            'do_unit' => $this->input->post('do_unit'),
            'do_nama_expedisi' => $this->input->post('do_nama_expedisi'),
            'do_est_lead_time' => $this->input->post('do_est_lead_time'),
            'do_eta' => $this->input->post('do_eta')
        ];

        $data['proses'] = $this->M_asn->proses_edit_do($data);

    }

    public function delete_produk_do(){

        $this->M_asn->delete_produk_do();
    }

    public function report(){
        $data = [
            'id' => $this->session->userdata('id'),
            'title' => 'Report',
            'get_label' => $this->M_menu->get_label(),
            'get_report' => $this->M_asn->report()
        ];
        $this->load->view('template_claim/top_header');
        $this->load->view('template_claim/header');
        $this->load->view('template_claim/sidebar',$data);
        $this->load->view('template_claim/top_content',$data);
        $this->load->view('asn/report',$data);
        $this->load->view('template_claim/bottom_content');
        $this->load->view('template_claim/footer');
    }

    public function export_report(){
        $id = $this->session->userdata('id');
        $sql = "
        SELECT 	a.id, a.nopo, a.no_asn, a.no_do, a.company, a.branch_name, a.nama_comp,
                a.userid,a.tipe,date_format(a.tglpo,'%Y-%m-%d') as tglpo,
                a.u, a.u_asn, a.u_do, a.v, a.v_do, FORMAT(SUM(a.u_do/a.u*100),2) as persen

        FROM(
                select 	a.id, a.nopo, e.no_asn, f.no_do, a.company, d.branch_name, d.nama_comp,
                a.userid,a.tipe,date_format(a.tglpo,'%Y-%m-%d') as tglpo,
                sum(b.banyak) as u, sum(e.asn_unit) as u_asn, sum(f.do_unit) as u_do,FORMAT(SUM(f.do_unit/b.banyak),2) as persen, sum(b.banyak * b.harga) as v,sum(f.do_unit * b.harga) as v_do
                from    mpm.po a INNER JOIN mpm.po_detail b
                                                on a.id = b.id_ref LEFT JOIN
                (
                        select a.id, a.username
                        from mpm.`user` a
                )c on a.userid = c.id INNER JOIN 
                (
                        select a.kode, a.branch_name, a.nama_comp, a.kode_comp
                        FROM
                        (
                                        select 	concat(a.kode_comp,a.nocab) as kode, a.branch_name,a.nama_comp, a.kode_comp
                                        from 	mpm.tbl_tabcomp a
                                        WHERE	a.`status` = 1
                                        GROUP BY a.kode_comp
                        )a
                )d on c.username = d.kode_comp LEFT JOIN
                (
                        select a.id as id_asn, a.id_po, a.no_asn, a.asn_kodeprod, a.asn_unit
                        from mpm.t_asn a
                )e on a.id = e.id_po and b.kodeprod = e.asn_kodeprod INNER JOIN
                (
                        select a.id as id_do, a.id_po, a.no_do, a.do_kodeprod, a.do_unit
                        from mpm.t_do a
                )f on a.id = f.id_po and b.kodeprod = f.do_kodeprod
                where a.deleted = 0 and b.deleted = 0 and nopo not like '/mpm%' and a.nopo not like '%batal%' 
                GROUP BY a.nopo,e.no_asn, f.no_do
        )a 
        GROUP BY a.nopo,a.no_asn,a.no_do
        ORDER BY a.id DESC
        ";
        $proses = $this->db->query($sql);              
        query_to_csv($proses,TRUE,'Report DO.csv');
    }
}
?>
