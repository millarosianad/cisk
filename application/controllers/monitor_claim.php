<?php

if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Monitor_claim extends MY_Controller
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

    function monitor_claim()
    {
        set_time_limit(0);
        $this->load->library(array('table','template','Excel_generator', 'form_validation','email'));
        $this->load->helper(array('url','form'));

        $this->load->model('model_claim','claim');
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
    }

    private function template($view,$data)
    {
        $this->template->set_title('MPM SQUARE');
        $this->template->add_js('modules/skeleton.js');
        $this->template->add_css('modules/skeleton.css');
        $this->template->load_view($view, $data);
    }

    public function view_claim(){   
        $data['page_content'] = 'claim/view_claim';                      
        $data['menu']=$this->db->query($this->querymenu);
        $data['url']="monitor_claim/proses_claim";
        $data['page_title']="Input Claim";
        $data['username']=$this->session->userdata('username');
        $this->template($data['page_content'],$data); 
    } 

    public function proses_claim()
    {
        if($this->input->post('no_surat_program') <> NULL)
        {            
            if($this->input->post('nama_program') <> NULL)
            {
                if($this->input->post('nocab') <> NULL)
                {
                    if($this->input->post('from') <> NULL && $this->input->post('to') <> NULL)
                    {                        
                        if(!is_dir('./assets/uploads/claim/'.date('Ym').'/'))
                        {
                            @mkdir('./assets/uploads/claim/'.date('Ym').'/',0777);
                        }                      
                        
                        if($this->input->post('proses') != NULL )
                        {
                            $data = array();
                            $countfiles = count($_FILES['files']['name']);
                            for($i=0;$i<$countfiles;$i++)
                            {
                                if(!empty($_FILES['files']['name'][$i]))
                                { 
                                    $_FILES['file']['name'] = $_FILES['files']['name'][$i];
                                    $_FILES['file']['type'] = $_FILES['files']['type'][$i];
                                    $_FILES['file']['tmp_name'] = $_FILES['files']['tmp_name'][$i];
                                    $_FILES['file']['error'] = $_FILES['files']['error'][$i];
                                    $_FILES['file']['size'] = $_FILES['files']['size'][$i];
                                    // Set preference
                                    $config['upload_path'] = './assets/uploads/claim/'.date('Ym').'';
                                    $config['allowed_types'] = 'jpg|jpeg|png|gif|zip|ZIP|pdf|PDF|xls|xlsx';
                                    $config['max_size'] = '5000';
                                    $this->load->library('upload',$config);  
                                    if($this->upload->do_upload('file'))
                                    {
                                        $uploadData = $this->upload->data();
                                        $filename[$i] = $uploadData['file_name'];
                                    }else{
                                        echo "<script>alert('Ada kesalahan. Tidak bisa mengupload file. Harap ulangi atau hubungi IT')</script>";
                                        echo "<script>document.location='view_claim'</script>";
                                    }
                                }else{
                                    echo "<script>alert('File Attachment belum diisi')</script>";
                                    // echo "<script>document.location='view_claim'</script>";
                                    redirect('monitor_claim/view_claim');
                                }
                            }         
                            $file = implode(", ", $filename); 
                            $data['filenames']= $file;
                            $data['principal'] = $this->input->post('principal');
                            $data['username'] = $this->input->post('username');  
                            $data['no_surat_program']= $this->input->post('no_surat_program');
                            $data['no_ap']= $this->input->post('no_ap');
                            $data['nama_program'] = $this->input->post('nama_program');
                            $data['tipe_claim'] = $this->input->post('tipe_claim');
                            $data['from'] = $this->input->post('from');
                            $data['to'] = $this->input->post('to');
                            $data['nocab'] = $this->input->post('nocab');  
                            $data['folder'] = date('Ym');       
                            $data['menu']=$this->db->query($this->querymenu);
                            $proses=$this->claim->proses_claim($data);
                        }else{
                            echo "b";
                        }   
                    }else{
                        echo "<script>alert('Periode belum diisi')</script>";
                        echo "<script>document.location='view_claim'</script>";
                    }
                }else{
                    echo "<script>alert('DP belum diisi')</script>";
                    echo "<script>document.location='view_claim'</script>";
                }
            }else{
                echo "<script>alert('Nama Program belum diisi')</script>";
                echo "<script>document.location='view_claim'</script>";
            }
        }else{
            echo "<script>alert('No Surat Program belum diisi')</script>";
            echo "<script>document.location='view_claim'</script>";
        }        
    }

    public function view_program($id)
    {   
        $cek = $this->claim->get_claim($id);
        foreach ($cek as $key) {
            $no_surat_program = $key->no_surat_program;
            $principal = $key->principal;
        }

        if($principal == 1)
        {
            $group = 'G0101';
            $data['product'] = $this->claim->getProductDeltomed($group);
            
        }elseif($principal == 2)
        {
            $group = 'G0102';
            $data['product'] = $this->claim->getProductDeltomed($group);
        }elseif($principal == 3)
        {
            $group = 'G0103';
            $data['product'] = $this->claim->getProductDeltomed($group);
        }elseif($principal == 4)
        {
            $supp = '005';
            $data['product'] = $this->claim->getProduct($supp); 
        }elseif($principal == 5)
        {
            $supp = '002';
            $data['product'] = $this->claim->getProduct($supp); 
        }

        $data['id'] = $id;
        $data['page_content'] = 'claim/view_program';                      
        $data['menu']=$this->db->query($this->querymenu);
        $data['url']="monitor_claim/proses_program";
        $data['page_title']="Input Detail Program";
        $data['username']=$this->session->userdata('username');
        $this->template($data['page_content'],$data);         
    }

    public function edit_program($id){ 
        $get_program = $this->claim->get_program_detail($id)->result();
        // var_dump($data['get_program']);
        foreach ($get_program as $key) {
            $data['kodeprod_beli1'] = $key->kodeprod_beli1;
            $data['unit_beli1'] = $key->unit_beli1;
            $data['value_beli1'] = $key->value_beli1;
            $data['kodeprod_beli2'] = $key->kodeprod_beli2;
            $data['unit_beli2'] = $key->unit_beli2;
            $data['value_beli2'] = $key->value_beli2;
            $data['kodeprod_beli3'] = $key->kodeprod_beli3;
            $data['unit_beli3'] = $key->unit_beli3;
            $data['value_beli3'] = $key->value_beli3;
            $data['kodeprod_bonus'] = $key->kodeprod_bonus;            
            $data['unit_bonus'] = $key->unit_bonus;
            $data['value_bonus'] = $key->value_bonus;
            $data['keterangan'] = $key->keterangan;
            $data['status_kelipatan'] = $key->status_kelipatan;
            $data['status_faktur'] = $key->status_faktur;
            $data['id_claim'] = $key->id_claim;
            $principal = $key->principal;
        }

        if($principal == 1)
        {
            $group = 'G0101';
            $data['product'] = $this->claim->getProductDeltomed($group);
            
        }elseif($principal == 2)
        {
            $group = 'G0102';
            $data['product'] = $this->claim->getProductDeltomed($group);
        }elseif($principal == 3)
        {
            $group = 'G0103';
            $data['product'] = $this->claim->getProductDeltomed($group);
        }elseif($principal == 4)
        {
            $supp = '005';
            $data['product'] = $this->claim->getProduct($supp); 
        }elseif($principal == 5)
        {
            $supp = '002';
            $data['product'] = $this->claim->getProduct($supp); 
        }

        $data['page_content'] = 'claim/edit_program';                      
        $data['menu']=$this->db->query($this->querymenu);
        $data['page_title']="Edit Program";
        $data['id'] = $id;
        $data['username']=$this->session->userdata('username');
        $data['query'] = $this->claim->get_claim($id);
        $this->template($data['page_content'],$data);               
    } 

    public function proses_edit_program($id)
    {         
        $data['kodeprod_beli1'] = implode(", ", $this->input->post('kodeprod_beli1')); 
        $data['unit_beli1'] = $this->input->post('unit_beli1');
        $data['value_beli1'] = $this->input->post('value_beli1');

        $data['kodeprod_beli2'] = implode(", ", $this->input->post('kodeprod_beli2')); 
        $data['unit_beli2'] = $this->input->post('unit_beli2');
        $data['value_beli2'] = $this->input->post('value_beli2');

        $data['kodeprod_beli3'] = implode(", ", $this->input->post('kodeprod_beli3')); 
        $data['unit_beli3'] = $this->input->post('unit_beli3');
        $data['value_beli3'] = $this->input->post('value_beli3');

        $data['kodeprod_bonus'] = implode(", ", $this->input->post('kodeprod_bonus'));         
        $data['unit_bonus'] = $this->input->post('unit_bonus');
        $data['keterangan'] = $this->input->post('keterangan');
        $data['status_kelipatan'] = $this->input->post('status_kelipatan');
        $data['status_faktur'] = $this->input->post('status_faktur');
        $id_claim = $this->input->post('id_claim');

        $this->db->where('id', $id);
        $proses = $this->db->update('mpm.tbl_program_claim', $data);
        if ($proses) {
            echo "<script>alert('Data sudah diubah. Terima kasih');document.location='../edit_program/$id_claim'</script>";
        }else{
            echo "<script>alert('Error..!!');document.location='../view_program/$id'</script>";
        }
               
    } 
    

    public function proses_program()
    {
        // $kodeprod_beli1 = $this->input->post('kodeprod_beli1');
        // $unit_beli1 = $this->input->post('unit_beli1');
        // $value_beli1 = $this->input->post('value_beli1');

        // $kodeprod_beli2 = $this->input->post('kodeprod_beli2');
        // $unit_beli2 = $this->input->post('unit_beli2');
        // $value_beli2 = $this->input->post('value_beli2');

        // $kodeprod_beli3 = $this->input->post('kodeprod_beli3');
        // $unit_beli3 = $this->input->post('unit_beli3');
        // $value_beli3 = $this->input->post('value_beli3');

        // $kodeprod_bonus = $this->input->post('kodeprod_bonus');
        // $unit_bonus = $this->input->post('unit_bonus');
        // $value_bonus = $this->input->post('value_bonus');

        // $keterangan = $this->input->post('keterangan');
        // $status_kelipatan = $this->input->post('status_kelipatan');
        // $status_faktur = $this->input->post('status_faktur');

        // echo "<pre>";
        // // echo "kodeprod_beli1 : ".$kodeprod_beli1."<br>";
        // var_dump($kodeprod_beli1);
        // echo "unit_beli1 : ".$unit_beli1."<br>";
        // echo "value_beli1 : ".$value_beli1."<br>";

        // // echo "kodeprod_beli2 : ".$kodeprod_beli2."<br>";
        // var_dump($kodeprod_beli2);
        // echo "unit_beli2 : ".$unit_beli2."<br>";
        // echo "value_beli2 : ".$value_beli2."<br>";

        // // echo "kodeprod_beli3 : ".$kodeprod_beli3."<br>";
        // var_dump($kodeprod_beli3);
        // echo "unit_beli3 : ".$unit_beli3."<br>";
        // echo "value_beli3 : ".$value_beli3."<br>";

        // // echo "kodeprod_bonus : ".$kodeprod_bonus."<br>";
        // var_dump($kodeprod_bonus);
        // echo "unit_bonus : ".$unit_bonus."<br>";
        // echo "value_bonus : ".$value_bonus."<br>";

        // echo "keterangan : ".$keterangan."<br>";
        // echo "status_kelipatan : ".$status_kelipatan."<br>";
        // echo "status_faktur : ".$status_faktur."<br>";
        // echo "</pre>";

        $data['kodeprod_beli1'] = $this->input->post('kodeprod_beli1');
        $data['unit_beli1'] = $this->input->post('unit_beli1');
        $data['value_beli1'] = $this->input->post('value_beli1');

        $data['kodeprod_beli2'] = $this->input->post('kodeprod_beli2');
        $data['unit_beli2'] = $this->input->post('unit_beli2');
        $data['value_beli2'] = $this->input->post('value_beli2');

        $data['kodeprod_beli3'] = $this->input->post('kodeprod_beli3');
        $data['unit_beli3'] = $this->input->post('unit_beli3');
        $data['value_beli3'] = $this->input->post('value_beli3');

        $data['kodeprod_bonus'] = $this->input->post('kodeprod_bonus');
        $data['unit_bonus'] = $this->input->post('unit_bonus');
        $data['value_bonus'] = $this->input->post('value_bonus');

        $data['keterangan'] = $this->input->post('keterangan');
        $data['status_kelipatan'] = $this->input->post('status_kelipatan');
        $data['status_faktur'] = $this->input->post('status_faktur');

        $data['id'] = $this->input->post('id');
        $proses = $this->claim->proses_program($data);
    }

    public function detail_claim(){   
        $data['page_content'] = 'claim/detail_claim';                      
        $data['menu']=$this->db->query($this->querymenu);
        $data['url']="monitor_claim/proses_claim";
        $data['page_title']="Input Claim Regular";
        $data['username']=$this->session->userdata('username');
        $this->template($data['page_content'],$data);         
    } 

    public function edit_claim($id){ 
        $data['page_content'] = 'claim/edit_claim';                      
        $data['menu']=$this->db->query($this->querymenu);
        // $data['url']="monitor_claim/proses_edit_claim";
        $data['page_title']="Edit Claim Regular";
        $data['username']=$this->session->userdata('username');
        $data['query'] = $this->claim->get_claim($id);
        $this->template($data['page_content'],$data);               
    } 

    public function proses_edit_claim($id)
    { 
        if($this->input->post('no_surat_program') <> NULL)
        {            
            if($this->input->post('nama_program') <> NULL)
            {
                if($this->input->post('nocab') <> NULL)
                {
                    if($this->input->post('from') <> NULL && $this->input->post('to') <> NULL)
                    {                        
                        if(!is_dir('./assets/uploads/claim/'.date('Ym').'/'))
                        {
                            @mkdir('./assets/uploads/claim/'.date('Ym').'/',0777);
                        }                        
                        
                        if($this->input->post('proses') != NULL )
                        {
                            $data = array();
                            $countfiles = count($_FILES['files']['name']);

                            for($i=0;$i<$countfiles;$i++)
                            {
                                if(!empty($_FILES['files']['name'][$i]))
                                { 
                                    $_FILES['file']['name'] = $_FILES['files']['name'][$i];
                                    $_FILES['file']['type'] = $_FILES['files']['type'][$i];
                                    $_FILES['file']['tmp_name'] = $_FILES['files']['tmp_name'][$i];
                                    $_FILES['file']['error'] = $_FILES['files']['error'][$i];
                                    $_FILES['file']['size'] = $_FILES['files']['size'][$i];

                                    // Set preference
                                    $config['upload_path'] = './assets/uploads/claim/'.date('Ym').'';
                                    $config['allowed_types'] = 'jpg|jpeg|png|gif|zip|ZIP|pdf|PDF|xls|xlsx';
                                    $config['max_size'] = '5000';
                                    $this->load->library('upload',$config);  
                                    if($this->upload->do_upload('file'))
                                    {
                                        $uploadData = $this->upload->data();
                                        $filename = $uploadData['file_name'];
                                        $data['filenames'][] = $filename;

                                        $data['principal'] = $this->input->post('principal');
                                        $data['username'] = $this->input->post('username');  
                                        $data['no_surat_program']= $this->input->post('no_surat_program');
                                        $data['no_ap']= $this->input->post('no_ap');
                                        $data['nama_program'] = $this->input->post('nama_program');
                                        $data['tipe_claim'] = $this->input->post('tipe_claim');
                                        $data['from'] = $this->input->post('from');
                                        $data['to'] = $this->input->post('to');
                                        $data['nocab'] = $this->input->post('nocab');  
                                        $data['folder'] = date('Ym');       
                                        $data['menu']=$this->db->query($this->querymenu);
                                        $proses=$this->claim->proses_edit_claim($data);
                                    }else{
                                        echo "<script>alert('Ada kesalahan. Harap ulangi atau hubungi IT')</script>";
                                        echo "<script>document.location='../edit_claim/$id'</script>";
                                    }
                                }else{
                                    echo "<script>alert('File Attachment belum diisi')</script>";
                                    echo "<script>document.location='../edit_claim/$id'</script>";
                                }
                            }
                           
                        }else{
                            echo "b";
                        }   


                    }else{
                        echo "<script>alert('Periode belum diisi')</script>";
                        echo "<script>document.location='../edit_claim/$id'</script>";
                    }
                }else{
                    echo "<script>alert('DP belum diisi')</script>";
                    echo "<script>document.location='../edit_claim/$id'</script>";
                }
            }else{
                echo "<script>alert('Nama Program belum diisi')</script>";
                echo "<script>document.location='../edit_claim/$id'</script>";
            }
        }else{
            echo "<script>alert('No Surat Program belum diisi')</script>";
            echo "<script>document.location='../edit_claim/$id'</script>";
        }

        
    } 

    public function proses_edit_claim_principal($id)
    {                      
        if(!is_dir('./assets/uploads/claim/'.date('Ym').'/'))
        {
            @mkdir('./assets/uploads/claim/'.date('Ym').'/',0777);
        }                      
        
        if($this->input->post('proses') != NULL )
        {
            $data = array();
            $countfiles = count($_FILES['files']['name']);
            for($i=0;$i<$countfiles;$i++)
            {
                if(!empty($_FILES['files']['name'][$i]))
                { 
                    $_FILES['file']['name'] = $_FILES['files']['name'][$i];
                    $_FILES['file']['type'] = $_FILES['files']['type'][$i];
                    $_FILES['file']['tmp_name'] = $_FILES['files']['tmp_name'][$i];
                    $_FILES['file']['error'] = $_FILES['files']['error'][$i];
                    $_FILES['file']['size'] = $_FILES['files']['size'][$i];
                    // Set preference
                    $config['upload_path'] = './assets/uploads/claim/'.date('Ym').'';
                    $config['allowed_types'] = 'jpg|jpeg|png|gif|zip|ZIP|pdf|PDF|xls|xlsx';
                    $config['max_size'] = '5000';
                    $this->load->library('upload',$config);  
                    if($this->upload->do_upload('file'))
                    {
                        $uploadData = $this->upload->data();
                        $filename[$i] = $uploadData['file_name'];
                    }else{
                        echo "<script>alert('Ada kesalahan. Tidak bisa mengupload file. Harap ulangi atau hubungi IT')</script>";
                        echo "<script>document.location='detail_claim_principal/$id'</script>";
                    }
                }else{
                    echo "<script>alert('File Attachment belum diisi')</script>";
                    // echo "<script>document.location='view_claim'</script>";
                    redirect('monitor_claim/detail_claim_principal/'.$id);
                }
            }         
            $file = implode(", ", $filename); 
            $data['filenames']= $file;

            var_dump($file);
            echo "<br>keterangan : ".$this->input->post('keterangan');  
           
            $data['keterangan'] = $this->input->post('keterangan');  
            $data['folder'] = date('Ym');       
            $data['menu']=$this->db->query($this->querymenu);
            $proses=$this->claim->proses_claim_principal($id, $data);
        }else{
            echo "b";
        }           
    } 

    

    function ambil_data(){
        $id_user=$this->session->userdata('id');
        if ($id_user == 364) {
            $tampil_principal = '4';
        }elseif($id_user == 444){
            $tampil_principal = '1,2,3';
        }else{
            $tampil_principal = '1,2,3,4,5';
        }
        $draw=$_REQUEST['draw'];
        $length=$_REQUEST['length'];
        $start=$_REQUEST['start'];
        $search=$_REQUEST['search']["value"];
        $total=$this->db->count_all_results("mpm.tbl_monitor_claim");
        $output=array();
        $output['draw']=$draw;
        $output['recordsTotal']=$output['recordsFiltered']=$total;
        $output['data']=array();
        if($search!=""){
        $this->db->like("no_surat_program",$search);
        }
        $this->db->limit($length,$start);
        $this->db->order_by('id','desc');
        $this->db->where("principal in ($tampil_principal)");
        $this->db->where('deleted', null);
        $query=$this->db->get('mpm.tbl_monitor_claim');

        if($search!=""){
        $this->db->like("no_surat_program",$search);
        
        $jum=$this->db->get('mpm.tbl_monitor_claim');
        $output['recordsTotal']=$output['recordsFiltered']=$jum->num_rows();
        }

        $nomor_urut=$start+1;
        foreach ($query->result_array() as $kode) 
        {

            if($kode['principal'] == '1'){
                $principal = "Deltomed Herbal";
            }elseif($kode['principal'] == '2'){
                $principal = "Deltomed Candy";
            }elseif($kode['principal'] == '3'){
                $principal = "Deltomed Lainnya";
            }elseif($kode['principal'] == '4'){
                $principal = "Ultra Sakti";
            }elseif($kode['principal'] == '5'){
                $principal = "Marguna";
            }else{
                $principal = "-";
            }

            if($kode['tipe_claim'] == '1'){
                $tipe_claim = "Barang";
            }elseif($kode['tipe_claim'] == '2'){
                $tipe_claim = "Non Barang";
            }elseif($kode['tipe_claim'] == '3'){
                $tipe_claim = "Reward";
            }else{
                $tipe_claim = "-";
            }     
            
          $output['data'][]=array(
            // $nomor_urut,
            $kode['id'],                       
            $principal,
            $kode['no_surat_program'],
            // $kode['no_ap'],
            $kode['nama_program'],
            // $tipe_claim,
            // $kode['from'].' s.d '.$kode['to'],
            // $kode['area'],
            // $kode['created_username'],
            // $kode['created_date'],   
            '<center>'.anchor('monitor_claim/view_program/'.number_format($kode['id']),'<span class="glyphicon glyphicon-list-alt aria-hidden="true"></span>','target="_blank"'),         
            '<center>'.anchor('monitor_claim/download_file/'.number_format($kode['id']),'<span class="glyphicon glyphicon-paperclip aria-hidden="true"></span>','target="_blank"'),
            '<center>'.anchor('monitor_claim/send_email_to_dp/'.number_format($kode['id']),'<span class="glyphicon glyphicon-send aria-hidden="true"></span>','target="_blank"'),
            '<center>'.anchor('monitor_claim/generate_claim/'.number_format($kode['id']),'<span class="glyphicon glyphicon-check aria-hidden="true"></span>','target="_blank"'),
            '<center>'.anchor('monitor_claim/detail_claim/'.number_format($kode['id']),'<span class="glyphicon glyphicon-th-list aria-hidden="true"></span>','target="_blank"'),
            '<center>'.anchor('monitor_claim/edit_claim/'.number_format($kode['id']),'<span class="glyphicon glyphicon-edit aria-hidden="true"></span>','target="_blank"'),            
            '<center>'.anchor('monitor_claim/delete_claim/'.number_format($kode['id']),'<span class="glyphicon glyphicon-remove aria-hidden="true"></span>','target="_blank"'),
        );
        $nomor_urut++;
        }

        echo json_encode($output);
    }

    function ambil_data_program($id){
        $id_user=$this->session->userdata('id');
        $draw=$_REQUEST['draw'];
        $length=$_REQUEST['length'];
        $start=$_REQUEST['start'];
        $search=$_REQUEST['search']["value"];
        $total=$this->db->count_all_results("mpm.tbl_program_claim");
        $output=array();
        $output['draw']=$draw;
        $output['recordsTotal']=$output['recordsFiltered']=$total;
        $output['data']=array();
        if($search!=""){
        $this->db->like("kodeprod_beli",$search);
        }
        $this->db->limit($length,$start);
        $this->db->order_by('id','desc');
        $this->db->where('id_claim', $id);
        $query=$this->db->get('mpm.tbl_program_claim');

        if($search!=""){
        $this->db->like("kodeprod_beli",$search);
        
        $jum=$this->db->get('mpm.tbl_program_claim');
        $output['recordsTotal']=$output['recordsFiltered']=$jum->num_rows();
        }

        $nomor_urut=$start+1;
        foreach ($query->result_array() as $kode) 
        {            
            if($kode['status_kelipatan'] == '1'){
                $status_kelipatan = "Ya";
            }else{
                $status_kelipatan = "Tidak";
            }

            if($kode['status_faktur'] == '1'){
                $status_faktur = "Ya";
            }else{
                $status_faktur = "Tidak";
            }

          $output['data'][]=array(
            $nomor_urut,
            $kode['kodeprod_beli1'],
            $kode['unit_beli1'],
            $kode['value_beli1'],
            
            $kode['kodeprod_beli2'],
            $kode['unit_beli2'],
            $kode['value_beli2'],

            $kode['kodeprod_beli3'],
            $kode['unit_beli3'],
            $kode['value_beli3'],

            $kode['kodeprod_bonus'],
            $kode['unit_bonus'],
            $kode['value_bonus'],
            
            $kode['keterangan'],
            $status_kelipatan,
            $status_faktur,
            '<center>'.anchor('monitor_claim/edit_program/'.number_format($kode['id']),'<span class="glyphicon glyphicon-edit aria-hidden="true"></span>','target="_blank"'),
            '<center>'.anchor('monitor_claim/delete_program/'.number_format($kode['id']),'<span class="glyphicon glyphicon-remove aria-hidden="true"></span>','target="_blank"'),
        );
        $nomor_urut++;
        }

        echo json_encode($output);
    }

    function ambil_data_program_detail($id){
        $id_user=$this->session->userdata('id');
        $draw=$_REQUEST['draw'];
        $length=$_REQUEST['length'];
        $start=$_REQUEST['start'];
        $search=$_REQUEST['search']["value"];
        $total=$this->db->count_all_results("mpm.tbl_program_claim");
        $output=array();
        $output['draw']=$draw;
        $output['recordsTotal']=$output['recordsFiltered']=$total;
        $output['data']=array();
        if($search!=""){
        $this->db->like("kodeprod_beli1",$search);
        }
        $this->db->limit($length,$start);
        $this->db->order_by('id','desc');
        $this->db->where('id', $id);
        $query=$this->db->get('mpm.tbl_program_claim');

        if($search!=""){
        $this->db->like("kodeprod_beli1",$search);
        
        $jum=$this->db->get('mpm.tbl_program_claim');
        $output['recordsTotal']=$output['recordsFiltered']=$jum->num_rows();
        }

        $nomor_urut=$start+1;
        foreach ($query->result_array() as $kode) 
        {
            if($kode['status_kelipatan'] == '1'){
                $status_kelipatan = "Ya";
            }else{
                $status_kelipatan = "Tidak";
            }

            if($kode['status_faktur'] == '1'){
                $status_faktur = "Ya";
            }else{
                $status_faktur = "Tidak";
            }
            
          $output['data'][]=array(
            $nomor_urut,
            $kode['kodeprod_beli1'],
            $kode['unit_beli1'],
            $kode['value_beli1'],
            $kode['kodeprod_beli2'],
            $kode['unit_beli2'],
            $kode['value_beli2'],
            $kode['kodeprod_beli3'],
            $kode['unit_beli3'],
            $kode['value_beli3'],
            $kode['kodeprod_bonus'],
            $kode['unit_bonus'],
            $kode['value_bonus'],
            $kode['keterangan'],
            $status_kelipatan,
            $status_faktur,
        );
        $nomor_urut++;
        }

        echo json_encode($output);
    }

    function ambil_data_edit($id){
        $id_user=$this->session->userdata('id');
        $draw=$_REQUEST['draw'];
        $length=$_REQUEST['length'];
        $start=$_REQUEST['start'];
        $search=$_REQUEST['search']["value"];
        $total=$this->db->count_all_results("mpm.tbl_monitor_claim");
        $output=array();
        $output['draw']=$draw;
        $output['recordsTotal']=$output['recordsFiltered']=$total;
        $output['data']=array();
        if($search!=""){
        $this->db->like("no_surat_program",$search);
        }
        $this->db->limit($length,$start);
        $this->db->order_by('id','desc');
        $this->db->where('id',$id);
        $this->db->where('deleted', null);
        $query=$this->db->get('mpm.tbl_monitor_claim');

        if($search!=""){
        $this->db->like("no_surat_program",$search);
        
        $jum=$this->db->get('mpm.tbl_monitor_claim');
        $output['recordsTotal']=$output['recordsFiltered']=$jum->num_rows();
        }

        $nomor_urut=$start+1;
        foreach ($query->result_array() as $kode) 
        {

            if($kode['principal'] == '1'){
                $principal = "Deltomed Herbal";
            }elseif($kode['principal'] == '2'){
                $principal = "Deltomed Candy";
            }elseif($kode['principal'] == '3'){
                $principal = "Deltomed Lainnya";
            }elseif($kode['principal'] == '4'){
                $principal = "Ultra Sakti";
            }elseif($kode['principal'] == '5'){
                $principal = "Marguna";
            }else{
                $principal = "-";
            }

            if($kode['tipe_claim'] == '1'){
                $tipe_claim = "Barang";
            }elseif($kode['tipe_claim'] == '2'){
                $tipe_claim = "Non Barang";
            }elseif($kode['tipe_claim'] == '3'){
                $tipe_claim = "Reward";
            }else{
                $tipe_claim = "-";
            }     
            
          $output['data'][]=array(
            // $nomor_urut,
            $kode['id'],                       
            $principal,
            $kode['no_surat_program'],
            $kode['no_ap'],
            $kode['nama_program'],
            $tipe_claim,
            $kode['from'].' s.d '.$kode['to'],
            $kode['area'],
            $kode['created_username'],
            $kode['created_date'],
            '<center>'.anchor('monitor_claim/generate_claim/'.number_format($kode['id']),'<span class="glyphicon glyphicon-export aria-hidden="true"></span>','target="_blank"'),
            '<center>'.anchor('monitor_claim/download_file/'.number_format($kode['id']),'<span class="glyphicon glyphicon-floppy-disk aria-hidden="true"></span>','target="_blank"'),
            '<center>'.anchor('monitor_claim/delete_claim/'.number_format($kode['id']),'<span class="glyphicon glyphicon-remove aria-hidden="true"></span>','target="_blank"'),
            '<center>'.anchor('monitor_claim/edit_claim/'.number_format($kode['id']),'<span class="glyphicon glyphicon-edit aria-hidden="true"></span>','target="_blank"'),
            '<center>'.anchor('monitor_claim/detail_claim/'.number_format($kode['id']),'<span class="glyphicon glyphicon-th-list aria-hidden="true"></span>','target="_blank"'),
        );
        $nomor_urut++;
        }

        echo json_encode($output);
    }

    function ambil_data_principal(){

        // $id_user=$this->session->userdata('id');
        $username = $this->session->userdata('username');
        $draw=$_REQUEST['draw'];
        $length=$_REQUEST['length'];
        $start=$_REQUEST['start'];
        $search=$_REQUEST['search']["value"];
        $total=$this->db->count_all_results("mpm.tbl_detail_claim");
        $output=array();
        $output['draw']=$draw;
        $output['recordsTotal']=$output['recordsFiltered']=$total;
        $output['data']=array();
        if($search!=""){
        $this->db->like("no_surat_program",$search);
        }

        $sql = "
            select 	a.id id, a.id_ref id_ref, b.no_surat_program no_surat_program, a.area area,
                    b.no_ap, b.nama_program, b.`from`, b.`to`, 
                    a.tgl_claim_dr_dp, a.keterangan_dr_dp, a.created_dp_username,
                    a.created_dp_date, c.nama_comp, a.status_verifikasi status,a.tgl_penggantian,a.ket_penggantian
            from 	mpm.tbl_detail_claim a INNER JOIN mpm.tbl_monitor_claim b on a.id_ref = b.id
                    LEFT JOIN
                    (
                        select concat(kode_comp, nocab) as kode, nama_comp
                        from mpm.tbl_tabcomp_new
                        GROUP BY concat(kode_comp, nocab)
                    )c on a.area = c.kode
            where   a.status_verifikasi = 2
            limit $start,$length
        ";
        $query = $this->db->query($sql);
          
        if($search!="")
        {
            $this->db->like("no_surat_program",$search);
            $jum=$this->db->get('mpm.tbl_detail_claim');
            $output['recordsTotal']=$output['recordsFiltered']=$jum->num_rows();
        }
        $nomor_urut=$start+1;
        foreach ($query->result_array() as $kode) 
        {
            if($kode['keterangan_dr_dp'] == '1'){
                $keterangan = "Lengkap";
            }elseif($kode['keterangan_dr_dp'] == '2'){
                $keterangan = "Reject";
            }else{
                $keterangan = "-";
            } 

            if($kode['status'] == null){
                $status = "pending";
            }elseif($kode['status'] == '1'){
                $status = "ongoing";
            }elseif($kode['status'] == '2'){
                $status = "verified";
            }else{
                $status = "-";
            } 

          $output['data'][]=array(
            $nomor_urut,
            // $kode['id'],     
            $kode['no_surat_program'],   
            $kode['no_ap'],   
            $kode['nama_program'],
            $kode['tgl_penggantian'],
            $kode['ket_penggantian'],
            $status,
            '<center>'.anchor('monitor_claim/download_file_Dp/'.number_format($kode['id']),'<span class="glyphicon glyphicon-floppy-disk aria-hidden="true"></span>','target="_blank"'),
            $status,
            '<center>'.anchor('monitor_claim/ubah_status_claim/'.number_format($kode['id']),'<span class="glyphicon glyphicon-edit aria-hidden="true"></span>','target="_blank"'),
          );
        $nomor_urut++;
        }

        echo json_encode($output);
    }

    public function generate_claim($id)
    {
        $data['page_content'] = 'claim/view_claim';  
        $proses=$this->claim->generate_claim($id);  
        if ($proses == 1) {
            echo "<script>alert('Generate Claim Berhasil');document.location='../view_claim'</script>";
        }else{
            echo "<script>alert('Generate Claim Gagal Karena Data ini sudah pernah di generate sebelumnya');document.location='../view_claim'</script>";
        }         
    } 

    public function send_email_to_dp($id)
    {
        $query = "select no_surat_program,nama_program,area,filename,folder from mpm.tbl_monitor_claim where id = $id";
        $proses = $this->db->query($query)->result();
        foreach ($proses as $row) 
        {            
            $filename = $row->filename;
            $area = $row->area;
            $folder = $row->folder;
            $no_surat_program = $row->no_surat_program;
            $nama_program = $row->nama_program;
            $periode = $row->from.'-'.$row->to;
        }
        $per_dp = explode(", ", $area);        
        $jumlahDP = count($per_dp);
        for ($i=0; $i < $jumlahDP ; $i++) 
        { 
            $dp = substr($per_dp[$i],0,3);   
            $query_email = "select email from mpm.user where username = '$dp'";
            $proses_email = $this->db->query($query_email)->result();
            foreach ($proses_email as $row) 
            {
                $email[$i]=$row->email;
            }   
        }
        $to = implode(", ", $email);
        $this->load->library('email');
        $from = "claim@muliaputramandiri.com";
        $config['protocol']  = 'smtp';
        $config['smtp_host'] = 'ssl://smtp.gmail.com';
        $config['smtp_port'] = '465';
        $config['smtp_timeout'] = '300';
        $config['smtp_user'] = 'mpmclaim@gmail.com';
        $config['smtp_pass'] = 'mpm12345!@#$%';
        $config['charset']  = 'utf-8'; 
        $config['use_ci_email'] = TRUE;
        $config['wordwrap'] = TRUE;
        $config['mailtype'] = 'html';

        $per_file = explode(", ", $filename);
        $jumlahFile = count($per_file);        
        $this->email->initialize($config);
        $this->email->set_newline("\r\n"); 
        
        $this->email->from($from,'PT. Mulia Putra Mandiri');
        $this->email->to($to);
        $this->email->subject("Program Claim (dikirim otomatis oleh site.muliaputramandiri.com)");
        $data = array(
            'no_surat_program'=> $no_surat_program,
            'nama_program' => $nama_program,
            'periode' => $periode
        );
        $message = $this->load->view("claim/template.php",$data,TRUE);
        $this->email->message($message);
        for ($i=0; $i < $jumlahFile ; $i++) 
        { 
            $file = $per_file[$i];            
            $this->email->attach("assets/uploads/claim/$folder/$per_file[$i]");
        }
        $kirim = $this->email->send();
        // echo $this->email->print_debugger();
        if($kirim == 1){
            echo "<script>alert('Pengiriman email berhasil');document.location='../detail_claim/$id_ref'</script>"; 
        }else{
            echo "<script>alert('Pengiriman email gagal, mungkin koneksi tidak stabil. Harap ulangi kembali');document.location='../detail_claim/$id_ref'</script>"; 
        }
    }

    public function send_email_to_principal($id)
    {
        $query = "
            select  a.id, b.no_surat_program, b.nama_program, a.filename_to_principal, a.folder, b.principal,c.email
            from    mpm.tbl_detail_claim a INNER JOIN mpm.tbl_monitor_claim b 
                        on a.id_ref = b.id
                    INNER JOIN mpm.tbl_email_claim c
                        on b.principal = c.id_principal
            where a.id = $id
        ";
        $proses = $this->db->query($query)->result();
        foreach ($proses as $row) 
        {          
            $email = $row->email;
            $filename_to_principal = $row->filename_to_principal;
            $folder = $row->folder;
            $no_surat_program = $row->no_surat_program;
            $nama_program = $row->nama_program;
        }
        
        $to = $email;
        $this->load->library('email');
        $from = "claim@muliaputramandiri.com";
        $config['protocol']  = 'smtp';
        $config['smtp_host'] = 'ssl://smtp.gmail.com';
        $config['smtp_port'] = '465';
        $config['smtp_timeout'] = '300';
        $config['smtp_user'] = 'mpmclaim@gmail.com';
        $config['smtp_pass'] = 'mpm12345!@#$%';
        $config['charset']  = 'utf-8'; 
        $config['use_ci_email'] = TRUE;
        $config['wordwrap'] = TRUE;
        $config['mailtype'] = 'html';

        $per_file = explode(", ", $filename_to_principal);
        $jumlahFile = count($per_file);        
        $this->email->initialize($config);
        $this->email->set_newline("\r\n"); 
        
        $this->email->from($from,'PT. Mulia Putra Mandiri');
        $this->email->to($to);
        $this->email->subject("Program Claim (dikirim otomatis oleh site.muliaputramandiri.com)");
        $data = array(
            'no_surat_program'=> $no_surat_program,
            'nama_program' => $nama_program
        );
        $message = $this->load->view("claim/template_principal.php",$data,TRUE);
        $this->email->message($message);
        for ($i=0; $i < $jumlahFile ; $i++) 
        { 
            $file = $per_file[$i];            
            $this->email->attach("assets/uploads/claim/$folder/$per_file[$i]");
        }
        $kirim = $this->email->send();
        echo $this->email->print_debugger();
        // if($kirim == 1){
        //     echo "<script>alert('Pengiriman email berhasil');document.location='../detail_claim/$id_ref'</script>"; 
        // }else{
        //     echo "<script>alert('Pengiriman email gagal, mungkin koneksi tidak stabil. Harap ulangi kembali');document.location='../detail_claim/$id_ref'</script>"; 
        // }
    }

    public function delete_claim($id)
    {
        echo "id : ".$id;
        $data = array(
            'deleted' => 1,
        );
        
        $this->db->where('id', $id);
        $proses = $this->db->update('mpm.tbl_monitor_claim', $data);
        if ($proses) {
            echo "<script>alert('Data sudah dihapus. Terima kasih');document.location='../view_claim'</script>";
        }else{
            echo "<script>alert('Error..!!');document.location='view_claim'</script>";
        }
    }

    public function delete_program($id)
    {
        echo "id : ".$id;
        $data = array(
            'deleted' => 1,
        );
        
        $this->db->where('id', $id);
        $proses = $this->db->delete('mpm.tbl_program_claim');
        if ($proses) {
            echo "<script>alert('Data sudah dihapus. Terima kasih');document.location='../view_claim'</script>";
        }else{
            echo "<script>alert('Error..!!');document.location='view_claim'</script>";
        }
    }

    public function view_claim_dp(){    

        $data['page_content'] = 'claim/view_claim_dp';                      
        $data['menu']=$this->db->query($this->querymenu);
        $data['url']="monitor_claim/update_dp";
        $data['page_title']="View Claim DP";
        $username = $this->session->userdata('username');
        $data['query']=$this->claim->get_claim_dp();
        $this->template($data['page_content'],$data);  
    }    

    public function view_claim_principal()
    {    
        $data['page_content'] = 'claim/view_claim_principal';                      
        $data['menu']=$this->db->query($this->querymenu);
        $data['page_title']="View Claim Principal";
        $data['query']=$this->claim->get_claim_principal();
        $this->template($data['page_content'],$data);  
    }    

    function ambil_data_dp(){

        // $id_user=$this->session->userdata('id');
        $username = $this->session->userdata('username');
        $draw=$_REQUEST['draw'];
        $length=$_REQUEST['length'];
        $start=$_REQUEST['start'];
        $search=$_REQUEST['search']["value"];
        $total=$this->db->count_all_results("mpm.tbl_detail_claim");
        $output=array();
        $output['draw']=$draw;
        $output['recordsTotal']=$output['recordsFiltered']=$total;
        $output['data']=array();
        if($search!=""){
        $this->db->like("no_surat_program",$search);
        }

        $sql = "
            select 	a.id id, a.id_ref id_ref, b.no_surat_program no_surat_program, 
                    b.no_ap, b.nama_program, b.`from`, b.`to`, 
                    a.tgl_claim_dr_dp, a.keterangan_dr_dp, a.created_dp_username,
                    a.created_dp_date,b.area
            from 		mpm.tbl_detail_claim a INNER JOIN mpm.tbl_monitor_claim b on a.id_ref = b.id
            where 	a.area like '$username%'
            limit $start,$length
        ";
        $query = $this->db->query($sql);
          
        if($search!="")
        {
            $this->db->like("no_surat_program",$search);
            $jum=$this->db->get('mpm.tbl_detail_claim');
            $output['recordsTotal']=$output['recordsFiltered']=$jum->num_rows();
        }
        $nomor_urut=$start+1;
        foreach ($query->result_array() as $kode) 
        {
            if($kode['keterangan_dr_dp'] == '1'){
                $keterangan = "Lengkap";
            }elseif($kode['keterangan_dr_dp'] == '2'){
                $keterangan = "Reject";
            }else{
                $keterangan = "-";
            } 

          $output['data'][]=array(
            $nomor_urut,
            // $kode['id'],            
            '<center>'.anchor('monitor_claim/update_claim_dp/'.number_format($kode['id']),$kode['no_surat_program'],''),
            $kode['no_ap'],
            $kode['nama_program'],
            $kode['from'].'-'.$kode['to'],
            $kode['tgl_claim_dr_dp'],
            $keterangan,
            $kode['created_dp_username'],
            $kode['created_dp_date'],
            '<center>'.anchor('monitor_claim/download_file_Dp/'.number_format($kode['id']),'<span class="glyphicon glyphicon-floppy-disk aria-hidden="true"></span>','target="_blank"'),
          );
        $nomor_urut++;
        }

        echo json_encode($output);
    }

    function ambil_data_detail($id){

        // $id_user=$this->session->userdata('id');
        $username = $this->session->userdata('username');
        $draw=$_REQUEST['draw'];
        $length=$_REQUEST['length'];
        $start=$_REQUEST['start'];
        $search=$_REQUEST['search']["value"];
        $total=$this->db->count_all_results("mpm.tbl_detail_claim");
        $output=array();
        $output['draw']=$draw;
        $output['recordsTotal']=$output['recordsFiltered']=$total;
        $output['data']=array();
        if($search!=""){
        $this->db->like("no_surat_program",$search);
        }

        $sql = "
            select 	a.id id, a.id_ref id_ref, b.no_surat_program no_surat_program, a.area area,
                    b.no_ap, b.nama_program, b.`from`, b.`to`, 
                    a.tgl_claim_dr_dp, a.keterangan_dr_dp, a.created_dp_username,a.filename,a.filename_to_principal,
                    a.created_dp_date, c.nama_comp, a.status_verifikasi status, a.filename_principal
            from 	mpm.tbl_detail_claim a INNER JOIN mpm.tbl_monitor_claim b on a.id_ref = b.id
                    LEFT JOIN
                    (
                        select concat(kode_comp, nocab) as kode, nama_comp
                        from mpm.tbl_tabcomp_new
                        where status = 1
                        GROUP BY concat(kode_comp, nocab)
                    )c on a.area = c.kode
            where 	a.id_ref = $id
            limit $start,$length
        ";
        $query = $this->db->query($sql);
          
        if($search!="")
        {
            $this->db->like("no_surat_program",$search);
            $jum=$this->db->get('mpm.tbl_detail_claim');
            $output['recordsTotal']=$output['recordsFiltered']=$jum->num_rows();
        }
        $nomor_urut=$start+1;
        foreach ($query->result_array() as $kode) 
        {
            if($kode['status'] == null || $kode['status'] == '0'){
                $status = "Terkirim ke DP";
            }elseif($kode['status'] == '1'){
                $status = "Terkirim ke MPM";
            }elseif($kode['status'] == '2'){
                $status = "Cross Cek di MPM";
            }elseif($kode['status'] == '3'){
                $status = "Terkirim ke Principal";
            }elseif($kode['status'] == '4'){
                $status = "Selesai";
            }elseif($kode['status'] == '5'){
                $status = "Selesai dari Principal";
            }else{
                $status = "-";
            } 

            if($kode['filename_principal'] == null){
                $file_principal = "-";
            }else{
                $file_principal = '<center>'.anchor('monitor_claim/download_file_principal/'.number_format($kode['id']),'<span class="glyphicon glyphicon-paperclip aria-hidden="true"></span>','target="_blank"');
            }

            if($kode['filename'] == null){
                $filename = "-";
            }else{
                $filename = '<center>'.anchor('monitor_claim/download_file_Dp/'.number_format($kode['id']),'<span class="glyphicon glyphicon-paperclip aria-hidden="true"></span>','target="_blank"');
            }

            if($kode['filename_to_principal'] == null){
                $file_to_principal = "-";
                $email = "-";
            }else{
                $file_to_principal = '<center>'.anchor('monitor_claim/download_file_to_principal/'.number_format($kode['id']),'<span class="glyphicon glyphicon-paperclip aria-hidden="true"></span>','target="_blank"');
                $email = '<center>'.anchor('monitor_claim/send_email_to_principal/'.number_format($kode['id']),'<span class="glyphicon glyphicon-send aria-hidden="true"></span>','target="_blank"');
            }

          $output['data'][]=array(
            $nomor_urut,
            // $kode['id'],     
            $kode['no_surat_program'],   
            $kode['nama_comp'],   
            $kode['tgl_claim_dr_dp'],
            $kode['keterangan_dr_dp'],
            $kode['created_dp_username'],
            $kode['created_dp_date'],
            $filename,
            $file_principal,
            $file_to_principal,
            $email,
            $status,
            '<center>'.anchor('monitor_claim/ubah_status_claim/'.number_format($kode['id']),'<span class="glyphicon glyphicon-edit aria-hidden="true"></span>','target="_blank"'),
          );
        $nomor_urut++;
        }

        echo json_encode($output);
    }

    public function update_claim_dp($id){    

        $data['page_content'] = 'claim/update_claim_dp';                      
        $data['menu']=$this->db->query($this->querymenu);
        $data['url']="monitor_claim/proses_insert_claim_dp";
        $data['page_title']="Update Claim Regular";
        $data['userid']=$this->session->userdata('id');
        $data['username']=$this->session->userdata('username');
        // $data['query']=$this->model_kalender_data->view_kalender_data_closing();
        $data['claim'] = $this->claim->get_detail_claim($id);
        $this->template($data['page_content'],$data); 
              
    }

    public function ubah_status_claim($id){    
        $data['page_content'] = 'claim/ubah_status_claim';                      
        $data['menu']=$this->db->query($this->querymenu);
        $data['page_title']="Ubah Status Claim";
        $data['userid']=$this->session->userdata('id');
        $data['username']=$this->session->userdata('username');
        $data['claim'] = $this->claim->get_detail_claim_untuk_ubah($id);
        $this->template($data['page_content'],$data);               
    }
    

    public function update_dp($id)
    {
        if($this->input->post('ket') <> NULL)
        {                        
            if(!is_dir('./assets/uploads/claim/'.date('Ym').'/'))
            {
                @mkdir('./assets/uploads/claim/'.date('Ym').'/',0777);
            }                      
            
            if($this->input->post('proses') != NULL )
            {
                $data = array();
                $countfiles = count($_FILES['files']['name']);
                for($i=0;$i<$countfiles;$i++)
                {
                    if(!empty($_FILES['files']['name'][$i]))
                    { 
                        $_FILES['file']['name'] = $_FILES['files']['name'][$i];
                        $_FILES['file']['type'] = $_FILES['files']['type'][$i];
                        $_FILES['file']['tmp_name'] = $_FILES['files']['tmp_name'][$i];
                        $_FILES['file']['error'] = $_FILES['files']['error'][$i];
                        $_FILES['file']['size'] = $_FILES['files']['size'][$i];
                        // Set preference
                        $config['upload_path'] = './assets/uploads/claim/'.date('Ym').'';
                        $config['allowed_types'] = 'jpg|jpeg|png|gif|zip|ZIP|pdf|PDF|xls|xlsx';
                        $config['max_size'] = '5000';
                        $this->load->library('upload',$config);  
                        if($this->upload->do_upload('file'))
                        {
                            $uploadData = $this->upload->data();
                            $filename[$i] = $uploadData['file_name'];
                        }else{
                            echo "<script>alert('Ada kesalahan. Tidak bisa mengupload file. Harap ulangi atau hubungi IT')</script>";
                            echo "<script>document.location='../view_claim_dp'</script>";
                        }
                    }else{
                        echo "<script>alert('File Attachment belum diisi')</script>";
                        redirect('monitor_claim/view_claim_dp');
                    }
                }         
                $data['file'] = implode(", ", $filename); 
                $data['id'] = $id; 
                $data['keterangan_dr_dp'] = $this->input->post('ket');
                $data['folder'] = date('Ym');
                $data['status'] = "1"; 
                $data['created_dp_by'] = $this->session->userdata('id');
                $data['created_dp_username'] = $this->session->userdata('username');
                $proses=$this->claim->proses_update_claim($data);
            }else{
                echo "b";
            }   
        }else{
            echo "<script>alert('Keterangan belum diisi')</script>";
            echo "<script>document.location='../view_claim_dp'</script>";
        }
    }  
    
    public function update_principal($id = '')
    {      
        $id_check_box = $this->input->post('options'); 
        if ($id_check_box) {
            $data['id'] = $id_check_box; 
            $data['status'] = '4';
            $data['created_by'] = $this->session->userdata('id');
            $data['created_username'] = $this->session->userdata('username');
            $proses=$this->claim->proses_update_principal($data);  
        }else{
            echo "b";
        }
    }  

    public function proses_status_claim($id)
    {
        if($this->input->post('keterangan') <> NULL)
        {                        
            if(!is_dir('./assets/uploads/claim/'.date('Ym').'/'))
            {
                @mkdir('./assets/uploads/claim/'.date('Ym').'/',0777);
            }   
                $data = array();
                $countfiles = count($_FILES['files']['name']);
                for($i=0;$i<$countfiles;$i++)
                {
                    if(!empty($_FILES['files']['name'][$i]))
                    { 
                        $_FILES['file']['name'] = $_FILES['files']['name'][$i];
                        $_FILES['file']['type'] = $_FILES['files']['type'][$i];
                        $_FILES['file']['tmp_name'] = $_FILES['files']['tmp_name'][$i];
                        $_FILES['file']['error'] = $_FILES['files']['error'][$i];
                        $_FILES['file']['size'] = $_FILES['files']['size'][$i];
                        // Set preference
                        $config['upload_path'] = './assets/uploads/claim/'.date('Ym').'';
                        $config['allowed_types'] = 'jpg|jpeg|png|gif|zip|ZIP|pdf|PDF|xls|xlsx';
                        $config['max_size'] = '5000';
                        $this->load->library('upload',$config);  
                        if($this->upload->do_upload('file'))
                        {
                            $uploadData = $this->upload->data();
                            $filename[$i] = $uploadData['file_name'];
                        }else{
                            echo "<script>alert('Ada kesalahan. Tidak bisa mengupload file. Harap ulangi atau hubungi IT')</script>";
                            echo "<script>document.location='../ubah_status_claim/$id'</script>";
                        }
                    }else{
                        echo "<script>alert('File Attachment belum diisi')</script>";
                        // echo "<script>document.location='../ubah_status_claim/$id'</script>";
                        redirect('monitor_claim/ubah_status_claim/'.$id);
                    }
                }    
                $data['file'] = implode(", ", $filename); 
                $data['id_ref'] = $this->input->post('id_ref');
                $data['id'] = $this->input->post('id');
                $data['status'] = $this->input->post('status');
                $data['keterangan'] = $this->input->post('keterangan');
                $data['folder'] = date('Ym'); 
                $data['verifikasi_by'] = $this->input->post('verifikasi_by');
                $data['verifikasi_username'] = $this->input->post('verifikasi_username');
                $proses=$this->claim->proses_status_claim($data); 
        }else{
            echo "<script>alert('Keterangan belum diisi')</script>";
            echo "<script>document.location='../ubah_status_claim/$id'</script>";
        }
    }    


    public function proses_update_claim_dp()
    {
        $id = $this->input->post('id');
        $tgl_claim_dr_dp = $this->input->post('tgl_claim_dr_dp');
        $keterangan_dr_dp = $this->input->post('keterangan_dr_dp');
        $created_dp_by = $this->input->post('created_dp_by');
        $created_dp_username = $this->input->post('created_dp_username');
        
        $data = array(
            'tgl_claim_dr_dp' => date_format(date_create($this->input->post('tgl_claim_dr_dp')),"Y/m/d"),
            'keterangan_dr_dp' => $this->input->post('keterangan_dr_dp'),
            'created_dp_by' => $this->input->post('created_dp_by'),
            'created_dp_username' => $this->input->post('created_dp_username'),
            'created_dp_date' => date('Y-m-d H:i:s')
        );
        
        $this->db->where('id', $this->input->post('id'));
        $proses = $this->db->update('mpm.tbl_monitor_claim', $data);
        if ($proses) {
            echo "<script>alert('Data sudah masuk. Terima kasih');document.location='view_claim_dp'</script>";
        }else{
            echo "<script>alert('Error..!!');document.location='view_claim_dp'</script>";
        }
    }    

    public function download_file($id)
    {    
        $sql = "
            select folder, filename
            from mpm.tbl_monitor_claim
            where id = $id
        ";
        $proses = $this->db->query($sql)->result();
        foreach ($proses as $key) {
            $folder = $key->folder;
            $filename = $key->filename;
        }
        $file = explode(", ",$filename);
        for ( $i = 0; $i < count( $file ); $i++ ) {
            echo anchor(base_url()."assets/uploads/claim/$folder/$file[$i]", "$file[$i]");
            echo "<br>";
        }        
    }

    public function download_file_dp($id)
    {    
        $sql = "
            select a.folder, b.filename
            from mpm.tbl_monitor_claim a INNER JOIN mpm.tbl_detail_claim b on a.id = b.id_ref
            where b.id = $id
        ";
        $proses = $this->db->query($sql)->result();
        foreach ($proses as $key) {
            $folder = $key->folder;
            $filename = $key->filename;
        }
        $file = explode(", ",$filename);
        for ( $i = 0; $i < count( $file ); $i++ ) {
            echo anchor(base_url()."assets/uploads/claim/$folder/$file[$i]",$file[$i]); 
            echo "<br>";
        }
    }

    public function download_file_principal($id){    
        $sql = "
            select folder, filename_principal
            from mpm.tbl_detail_claim
            where id = $id
        ";
        $proses = $this->db->query($sql)->result();

        foreach ($proses as $key) {
            $folder = $key->folder;
            $filename = $key->filename_principal;
        }
        
        $file = explode(", ",$filename);
        for ( $i = 0; $i < count( $file ); $i++ ) {
            echo anchor(base_url()."assets/uploads/claim/$folder/$file[$i]", "$file[$i]");
            echo "<br>";
        }        
    }

    public function detail_claim_principal($id)
    {
        $data['page_content'] = 'claim/edit_claim_principal';                      
        $data['menu']=$this->db->query($this->querymenu);
        // $data['url']="monitor_claim/proses_edit_claim";
        $data['page_title']="Edit Claim Principal";
        $data['username']=$this->session->userdata('username');
        $data['query'] = $this->claim->get_detail_principal($id);
        $this->template($data['page_content'],$data);   
    }

    public function download_file_to_principal($id){    
        $sql = "
            select folder, filename_to_principal
            from mpm.tbl_detail_claim
            where id = $id
        ";
        $proses = $this->db->query($sql)->result();

        foreach ($proses as $key) {
            $folder = $key->folder;
            $filename = $key->filename_to_principal;
        }
        
        $file = explode(", ",$filename);
        for ( $i = 0; $i < count( $file ); $i++ ) {
            echo anchor(base_url()."assets/uploads/claim/$folder/$file[$i]", "$file[$i]");
            echo "<br>";
        }        
    }

    public function form_hitung()
    {
        $data['page_content'] = 'claim/form_hitung';                      
        $data['menu']=$this->db->query($this->querymenu);
        $data['url']="monitor_claim/proses_hitung";
        $data['page_title']="Perhitungan Claim";
        $data['username']=$this->session->userdata('username');
        $data['claim'] = $this->claim->get_claim_all();     
        // $data['program'] = $this->claim->get_program();       
        $this->template($data['page_content'],$data); 
    }

    function get_program($id)
    {
        $logged_in= $this->session->userdata('logged_in');
        if(!isset($logged_in) || $logged_in != TRUE)
        {
            redirect('login/','refresh');
        }
        $id = $this->input->post('id_claim',TRUE);
        $query=$this->claim->get_program($id);
        foreach($query->result() as $row)
        {
            $output .= "<option value='".$row->id."'>".$row->keterangan."</option>";
        }
        echo $output;
    }

    function get_program_by_bulan($id)
    {
        $logged_in= $this->session->userdata('logged_in');
        if(!isset($logged_in) || $logged_in != TRUE)
        {
            redirect('login/','refresh');
        }
        $id = $this->input->post('id_bulan',TRUE);
        $id_tahun = $this->input->post('id_tahun',TRUE);
        $query=$this->claim->get_program_by_bulan($id,$id_tahun);
        foreach($query->result() as $row)
        {
            $output .= "<option value='".$row->id."'>".$row->nama_program."</option>";
        }
        echo $output;
    }

    function get_program_by_periode()
    {
        $logged_in= $this->session->userdata('logged_in');
        if(!isset($logged_in) || $logged_in != TRUE)
        {
            redirect('login/','refresh');
        }

        $from_periode = trim($this->input->post('from',TRUE));
        if ($from_periode == '') {
            $convert_from_periode='';
        }else{
            $convert_from_periode=strftime('%Y-%m-%d',strtotime($from_periode));
        }

        $to_periode = trim($this->input->post('to',TRUE));
        if ($to_periode == '') {
            $convert_to_periode='';
        }else{
            $convert_to_periode=strftime('%Y-%m-%d',strtotime($to_periode));
        }

        $query=$this->claim->get_program_by_periode($convert_from_periode,$convert_to_periode);
        foreach($query->result() as $row)
        {
            $output .= "<option value='".$row->id."'>".$row->nama_program."</option>";
            // $output = "<option value='".$row->id."'>".$convert_from_periode."</option>";
        }
        echo $output;
    }

    function get_detail_program()
    {
        $logged_in= $this->session->userdata('logged_in');
        if(!isset($logged_in) || $logged_in != TRUE)
        {
            redirect('login/','refresh');
        }
        $id_program = $this->input->post('program',TRUE);
        $query=$this->claim->get_detail_program($id_program);
        foreach($query->result() as $row)
        {
            $output .= "<option value='".$row->id."'>".$row->keterangan."</option>";
        }
        echo $output;
    }

    public function proses_hitung()
    {
        $data['menu']=$this->db->query($this->querymenu);
        $data['page_content'] = 'claim/proses_hitung'; 
        $data['page_title']="Perhitungan Claim";
        $data['nocab'] = $this->input->post('nocab');
        $idProgram = $this->input->post('program');

        $from_periode = trim($this->input->post('from',TRUE));
        if ($from_periode == '') {
            $data['from']='';
        }else{
            $data['from']=strftime('%Y-%m-%d',strtotime($from_periode));
        }

        $to_periode = trim($this->input->post('to',TRUE));
        if ($to_periode == '') {
            $data['to']='';
        }else{
            $data['to']=strftime('%Y-%m-%d',strtotime($to_periode));
        }

        $data['program'] = $this->input->post('program');
        $detail_program = $this->input->post('detail_program');

        $data['url']="monitor_claim/proses_hitung";  
        $get_detail_program = $this->claim->getDetailProgram($detail_program)->result();
        foreach ($get_detail_program as $key) {
            $data['kodeprod_beli1'] = $key->kodeprod_beli1;
            $data['unit_beli1'] = $key->unit_beli1;
            $data['value_beli1'] = $key->value_beli1;
            $data['kodeprod_beli2'] = $key->kodeprod_beli2;
            $data['unit_beli2'] = $key->unit_beli2;
            $data['value_beli2'] = $key->value_beli2;
            $data['kodeprod_beli3'] = $key->kodeprod_beli3;
            $data['unit_beli3'] = $key->unit_beli3;
            $data['value_beli3'] = $key->value_beli3;

            $data['kodeprod_bonus'] = $key->kodeprod_bonus;
            $data['unit_bonus'] = $key->unit_bonus;
            $data['value_bonus'] = $key->value_bonus;

            $data['max_bonus_unit_faktur'] = $key->max_bonus_unit_faktur;
            $data['max_bonus_value_faktur'] = $key->max_bonus_value_faktur;
            $data['max_bonus_unit_outlet'] = $key->max_bonus_unit_outlet;
            $data['max_bonus_value_outlet'] = $key->max_bonus_value_outlet;

            $data['keterangan'] = $key->keterangan;
            $data['status_kelipatan'] = $key->status_kelipatan;
            $data['status_faktur'] = $key->status_faktur;
        }

        $data['proses'] = $this->claim->proses_hitung($data);
        $this->template($data['page_content'],$data);
    }

    public function export_hitung_claim() {
        
      $id_user=$this->session->userdata('id');
      $this->db->where("tbl_hitung_claim.userid = ".'"'.$id_user.'"');
      $hasil = $this->db->get('tbl_hitung_claim');
     
      $this->excel_generator->set_query($hasil);
      $this->excel_generator->set_header(array
        (
          'Faktur', 'subbranch','kode_lang','nama_lang','alamat','kodeprod1','banyak1','value1','kodeprod2','banyak2','value2','kelipatan1','kelipatan2','bonus'
        ));
      $this->excel_generator->set_column(array
        (
          'faktur', 'subbranch','kode_lang','nama_lang','alamat','kodeprod1','banyak1','value1','kodeprod2','banyak2','value2','kelipatan1','kelipatan2','bonus'
        ));
        $this->excel_generator->set_width(array(10,10,8,10,13,10,8,8,8,8,8,8,8,8));
        $this->excel_generator->exportTo2007('Claim');   

      }

    public function form_rekap()
    {
        $data['page_content'] = 'claim/form_rekap';                      
        $data['menu']=$this->db->query($this->querymenu);
        $data['url']="monitor_claim/proses_rekap";
        $data['page_title']="Rekap Claim";
        $data['username']=$this->session->userdata('username');
        // $data['claim'] = $this->claim->get_claim_all();          
        $this->template($data['page_content'],$data); 
    }

    public function proses_rekap()
    {
        $data['menu']=$this->db->query($this->querymenu);
        $data['page_content'] = 'claim/view_rekap'; 
        $data['page_title']="Perhitungan Claim";
        $data['idProgram'] = $this->input->post('program');
        $data['url']="monitor_claim/proses_hitung";  
        $data['proses'] = $this->claim->proses_rekap($data);
        $this->template($data['page_content'],$data);
    }

    public function export_rekap() {
        
        $id_user=$this->session->userdata('id');
        $this->db->where("tbl_rekap_claim.userid = ".'"'.$id_user.'"');
        $hasil = $this->db->get('tbl_rekap_claim');
       
        $this->excel_generator->set_query($hasil);
        $this->excel_generator->set_header(array
          (
            'no_surat_program', 'nama_program','detail_program','area','keterangan_dr_mpm','tgl_claim_dr_dp','status','keterangan_dr_principal','tgl_update_status_principal','file_mpm_ke_principal','file_principal'
          ));
        $this->excel_generator->set_column(array
          (
            'no_surat_program', 'nama_program','keterangan','nama_comp','ket','tgl_claim_dr_dp','status_verifikasi','ket_penggantian','tgl_penggantian','filename_to_principal','filename_principal'
          ));
          $this->excel_generator->set_width(array(10,10,10,10,10,10,10,10,10,10,10));
          $this->excel_generator->exportTo2007('Rekap_Claim');   
  
    }

    public function log_claim(){
        $data['page_content'] = 'claim/log_claim';                      
        $data['menu']=$this->db->query($this->querymenu);
        $data['url']="monitor_claim/proses_claim";
        $data['page_title']="History Upload Claim DP";
        $data['username']=$this->session->userdata('username');
        $data['query'] = $this->claim->get_log_claim();
        // $query = $this->claim->get_log_claim();
        // var_dump($query);
        $this->template($data['page_content'],$data); 
    }

    public function analisa_claim(){
        $data['page_content'] = 'claim/analisa_claim';                      
        $data['menu']=$this->db->query($this->querymenu);
        $data['url']="monitor_claim/analisa_claim_proses";
        $data['page_title']="Analisa Claim DP";
        $data['username']=$this->session->userdata('username');
        // $data['query'] = $this->claim->get_prinsipal();
        // $query = $this->claim->get_prinsipal();
        // var_dump($query);
        $this->template($data['page_content'],$data); 
    }

    public function analisa_claim_proses(){
        $supp = $this->input->post("supp");
        $data['periode1'] = $this->input->post("periode1");
        $data['periode2'] = $this->input->post("periode2");
        $p1 = trim($this->input->post("periode1"));
        $periode1=strftime('%Y-%m-%d',strtotime($p1));
        $p2 = trim($this->input->post("periode2"));
        $periode2=strftime('%Y-%m-%d',strtotime($p2));
        $data['page_content'] = 'claim/analisa_claim_proses';                      
        $data['menu']=$this->db->query($this->querymenu);
        $data['url']="monitor_claim/analisa_claim_proses";
        $data['page_title']="Analisa Claim DP";
        $data['username']=$this->session->userdata('username');
        $data['query'] = $this->claim->get_claim_master($periode1,$periode2,$supp);
        $this->template($data['page_content'],$data); 
    }

    public function export_claim(){
        $this->load->helper('csv');
        $id=$this->session->userdata('id');
        $sql = "
        select * from dbklaim.t_claim_laporan
        where id =$id
        ";
        $quer = $this->db->query($sql);

        query_to_csv($quer,TRUE,'analisa_claim.csv');

    }

    public function detail_claim_produk($no_claim){
        
        // echo "no_claim : ".$no_claim;
        $data['page_content'] = 'claim/detail_claim_produk';                      
        $data['menu']=$this->db->query($this->querymenu);
        $data['url']="monitor_claim/analisa_claim_proses";
        $data['page_title']="Detail Produk";
        $data['username']=$this->session->userdata('username');
        $data['query'] = $this->claim->get_claim_by_no_claim($no_claim);
        $this->template($data['page_content'],$data); 

    }

    public function export_claim_detail_produk(){
        $this->load->helper('csv');
        $id=$this->session->userdata('id');
        $sql = "
        select * from dbklaim.t_claim_laporan_detail_produk
        where id =$id
        ";
        $quer = $this->db->query($sql);

        query_to_csv($quer,TRUE,'claim_detail_produk.csv');

    }

    public function detail_claim_transaksi($no_claim){
        
        // echo "no_claim : ".$no_claim;
        $data['page_content'] = 'claim/detail_claim_transaksi';                      
        $data['menu']=$this->db->query($this->querymenu);
        $data['url']="monitor_claim/analisa_claim_proses";
        $data['page_title']="Detail Transaksi";
        $data['username']=$this->session->userdata('username');
        $data['query'] = $this->claim->get_detail_transaksi($no_claim);
        $this->template($data['page_content'],$data); 

    }

    public function export_claim_detail_transaksi(){
        $this->load->helper('csv');
        $id=$this->session->userdata('id');
        $sql = "
        select * from dbklaim.t_claim_laporan_detail_transaksi
        where id =$id
        ";
        $quer = $this->db->query($sql);

        query_to_csv($quer,TRUE,'claim_detail_transaksi.csv');

    }

    public function proses_log($id)
    {
        // echo "id : ".$id;
        // $data['page_content'] = 'claim/log_claim';                      
        // $data['menu']=$this->db->query($this->querymenu);
        // $data['url']="monitor_claim/analisa_claim_proses";
        // $data['page_title']="Detail Transaksi";
        // $data['username']=$this->session->userdata('username');
        $query = $this->claim->proses_log($id);
        
        if ($query) {
            redirect('monitor_claim/log_claim');
        }




    }

    public function hitung_klaim(){
        $this->load->model('model_omzet');
        $data['page_content'] = 'claim/hitung_klaim';                      
        $data['menu']=$this->db->query($this->querymenu);
        $data['url']="monitor_claim/hitung_klaim_proses";
        $data['page_title']="Rekap Claim";
        $data['query'] = $this->model_omzet->getSuppbyid();
        $data['username']=$this->session->userdata('username');
        $this->template($data['page_content'],$data); 
    }

    public function hitung_klaim_proses(){
        $this->load->model('model_omzet');
        $data['tahun'] = $this->input->post('tahun');
        $data['bulan'] = $this->input->post('bulan');
        $data['supp'] = $this->input->post('supp');
        $data['kondisi'] = $this->input->post('kondisi');
        
        $data['proses'] = $this->claim->hitung_klaim_proses($data);
        // var_dump($x);
        $data['query'] = $this->model_omzet->getSuppbyid();
        $data['page_content'] = 'claim/hitung_klaim_proses';                      
        $data['menu']=$this->db->query($this->querymenu);
        $data['url']="monitor_claim/hitung_klaim_proses";
        $data['page_title']="Rekap Claim";
        $this->template($data['page_content'],$data); 
    }

    public function export_rekap_klaim()
    {
        $id= $this->session->userdata('id');
        $query="
            select * from dbklaim.t_rekap
            where id = $id order by urutan
        ";

        $hasil = $this->db->query($query);
        $this->excel_generator->set_query($hasil);
        $this->excel_generator->set_header(array
        (
        'kode', 'branch_name','nama_comp', 'sub','urutan', 'status','kodeprod', 'namaprod', 'sales_unit', 'sales_value', 'bonus_unit', 'last_updated'
        ));
        $this->excel_generator->set_column(array
            (
            'kode', 'branch_name','nama_comp', 'sub','urutan', 'status','kodeprod', 'namaprod', 'sales_unit', 'sales_value', 'bonus_unit', 'last_update'
            ));       
        
        $this->excel_generator->set_width(array(10,10,10,10,10,10,10,10,10,10,10,10));
        $this->excel_generator->exportTo2007('rekap_klaim'); 
    }  

    public function export_summary_report(){
        $this->load->helper('csv');
        $id=$this->session->userdata('id');
        $quer = $this->db->query("select * from db_klaim.t_summary_report where userid =$id order by urutan");
        query_to_csv($quer,TRUE,'report by sub branch.csv');
    }
    public function export_detail_report(){
        $this->load->helper('csv');
        $id=$this->session->userdata('id');
        $quer = $this->db->query("select * from db_klaim.t_detail_report where userid =$id order by urutan");
        query_to_csv($quer,TRUE,'report by outlet.csv');
    }
    public function export_summary_faktur(){
        $this->load->helper('csv');
        $id=$this->session->userdata('id');
        $quer = $this->db->query("select * from db_klaim.t_summary_faktur where userid =$id order by kode, faktur");
        query_to_csv($quer,TRUE,'summary faktur.csv');
    }
    public function export_detail_faktur(){
        $this->load->helper('csv');
        $id=$this->session->userdata('id');
        $quer = $this->db->query("select * from db_klaim.t_detail_faktur where userid =$id order by kode, faktur");
        query_to_csv($quer,TRUE,'detail faktur.csv');
    }
    
   
}
?>
