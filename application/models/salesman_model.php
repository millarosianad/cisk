<?php if( ! defined('BASEPATH')) exit('Akses langsung tidak diperkenankan');

//error_reporting(E_ALL);
//ini_set('display_errors', '1');

class Salesman_model extends CI_Model
{
     var $output_table='';
     var $total_query='';
     var $limit=20;
     var $image_print=array(
                 'src'    => 'assets/css/images/print.png',
                 'height' => '20',
                 'weight' => '20'
     );
     var $image_del=array(
         'src'    => 'assets/css/images/delete.png',
         'width'  => '20',
         'height' => '20',
     );
     var $image_mobile_del=array(
         'src'    => 'assets/css/images/delete.png',
         'width'  => '60',
         'height' => '60',
     );
     var $image_yes=array(
         'src'    => 'assets/css/images/yes.png',
         'width'  => '20',
         'height' => '20',
     );
     var $image_no=array(
         'src'    => 'assets/css/images/no.png',
         'width'  => '20',
         'height' => '20',
     );
     var $tmpl=array(
                'table_open'=>'<table class="table" style="font-size:12px">'
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

                ,'row_alt_start' =>'<tr bgcolor="#AFDEF8">'
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
    public function Salesman_model()
    {
        set_time_limit(0);

        $this->load->database();
        $this->load->library(array('table','session','zip'));//session untuk sisi administrasi
        $this->load->helper(array('text','array','file','to_excel_helper'));
    }
    public function getTotalQuery()
    {
        return $this->total_query;
    }
    function list_cust($key=0,$id=0,$init=0)
    {
        $year=date('Y');
        $nocab=23;//$this->session->userdata('nocab');
        $sql="select kode_lang, nama_lang,concat(ALAMAT1,', ',ALAMAT2)alamat, KODERAYON from data".$year.".tblang a 
              inner join  (select koderayon from data".$year.".tabsales 
              where kodesales=? and nocab=? ) b using(KODERAYON) where a.nocab=?
              order by nama_lang";
        $query=$this->db->query($sql,array($key,$nocab,$nocab));
        return $query;
    }
    function daftar_produk()
    {
        return $this->db->query('select kodeprod,namaprod from mpm.tabprod where active=1 and supp!="BSP" order by supp asc');
    }
    function daftar_pelanggan()
    {
        $year=date('Y');
        $sql='select kode_comp,sub from mpm.tabcomp where nocab='.$this->session->userdata('nocab');
        $query=$this->db->query($sql);
        $row=$query->row();
        
        //return $this->db->query('select id,company from mpm.user where level=4 order by company asc');
        $sql='select custid,nama_lang from data'.$year.'.tblang where nocab='.$row->sub.' and kode_comp="'.$row->kode_comp.'" order by nama_lang';
        return $this->db->query($sql);
    }
    public function list_client($userid=0)
    {
        $sql="select id,kode_dp,company from mpm.user where level in (4,5,6) order by company";
        return $this->db->query($sql,array($userid));
        //return $query->row();
    }
    function login_check($user=null,$pass=null)
    {
        $sql="select id from salesman.user where username=? and password=? and active=1";
        $query=$this->db->query($sql,array($user,$pass));
        if($query->num_rows()>0)
        {
            $row=$query->row();
            $userid=$row->id;
            $sql="update salesman.user set last_login=? where id =?";
            $query2=$this->db->query($sql,array(date('Y-m-d H:i:s'),$userid));
            $sql="select kode_sales,siteid,id,username from salesman.user where id=?";
            $query3=$this->db->query($sql,$userid);
            return $query3;
        }
        else
        {
            return false;
        }
    }
    function rks($state='show',$key=0,$id=0,$init=0)
    {
        switch($state)
        {
            case 'show';
            {
                $userid=$this->session->userdata('id');
                $sql='select concat(kode_lang," ",nama_lang) pelanggan,alamat,hari day,concat("Minggu ",minggu) minggu,
                    case hari 
                    when 1 then "1.Senin"
                    when 2 then "2.Selasa"
                    when 3 then "3.Rabu"
                    when 4 then "4.Kamis"
                    when 5 then "5.Jumat"
                    else "6.Sabtu"
                    end hari
                    from salesman.rks_temp where userid=? and kode_sales=?';
                $query=$this->db->query($sql,array($userid,$key));
                return json_encode($query->result()); 
            }break;
            case 'delete_temp':
            {
                $sql='delete from salesman.rks_temp where id=?';
                $query=$this->db->query($sql,array($key));
                return true;
            }break;
            case 'save_temp':
            {
                list($kode_lang,$nama_lang,$alamat)=explode('|',$this->input->post('pelanggan'));
                $minggu=$this->input->post('minggu');
                $hari=$this->input->post('hari');
                
                $post['kode_lang']=$kode_lang;
                $post['nama_lang']=$nama_lang;
                $post['alamat']=$alamat;
                $post['minggu']=$minggu;
                $post['hari']=$hari;
                $post['kode_sales']=$key;
                $post['sl']=$this->input->post('sl');
                $post['userid']=$this->session->userdata('id');

                //$post['tgldokjdi']=date('Y-m-d H:i:s');
                $this->db->insert('salesman.rks_temp',$post);
            }break;
            case 'show_temp':
            {
                $userid=$this->session->userdata('id');
                $sql="select * from salesman.rks_temp where userid=? and kode_sales=? order by kode_lang, minggu,hari";
                $query=$this->db->query($sql,array($userid,$key));
                if($query->num_rows()>0)
                {
                    $image_properties=array(
                        'src'    => 'assets/css/images/delete.png',
                        'width'  => '20',
                        'height' => '20',
                    );
                    $this->load->library('table');
                    $this->table->set_empty('0');
                    $this->table->set_template($this->tmpl);
                    $this->table->set_caption('<h3>RKS</h3>');
                    $this->table->set_heading('Kode Plg.','Nama','Alamat','SL','Minggu','Hari','Delete');
                    $js=array('onclick'=>"return confirm('Are you sure you want to delete?')");
                    foreach ($query->result() as $value)
                    {
                        switch($value->hari)
                        {
                            case 1:$hari='Senin';break;
                            case 2:$hari='Selasa';break;
                            case 3:$hari='Rabu';break;
                            case 4:$hari='Kamis';break;
                            case 5:$hari='Jumat';break;
                        }
                        $this->table->add_row(
                           
                                $value->kode_lang
                                ,$value->nama_lang
                                ,$value->alamat
                                ,$value->sl
                                ,"Minggu ".$value->minggu
                                ,$hari
                                ,'<div div style="text-align:center">'.anchor('salesman/rks/delete_temp/'.$value->id,img($image_properties,$js)).'</div>'
                                );
                    }

                    $this->output_table .= $this->table->generate();
                    $this->output_table .= '<br />';
                    $this->table->clear();
                    $query->free_result();
                    $this->output_table;
                    return $this->output_table;
                }
            }break;
        }
    }
    function admin($state='show',$key=0,$id=0,$init=0)
    {
        switch($state)
        {
            case 'edit':
            {
                $sql="select * from salesman.user where id=?";
                $query=$this->db->query($sql,array($key));
                $row=$query->row();
                return $row;
            }break;
            case 'download':
            {
               $this->load->dbutil();
               $this->load->helper('file');
               $sql='select * from salesman.so where tanggal=? and siteid=?';
               $query=$this->db->query($sql,$key,$id);
               $delimiter = ",";
               $newline = "\r\n";
               $data=$this->dbutil->csv_from_result($query, $delimiter, $newline); 
               $userid=$this->session->userdata('id');
               $filename=$key.$id;
               
               if ( ! write_file('assets/mobile/'.$filename, $data,'w+'))
               {
                    echo 'Unable to write the file';
               }
               else
               {
                    echo 'File written!';
               }
            }break;
            case 'upload':
            {
                
            }break;
            case 'update':
            {
                $userid=$this->session->userdata('id');
                //$post['username']=$this->input->post('username');
                $post['password']=$this->input->post('password');
                $post['kode_sales']=$this->input->post('kode_sales');
                $post['siteid']=$this->input->post('siteid');
                $post['modified']=date('Y-m-d H:i:s');
                $post['modified_by']=$userid;
                $where='id='.$key;
                $this->db->trans_begin();
                $sql=$this->db->update_string('salesman.user',$post,$where);
                $this->db->query($sql);
                if($this->db->trans_status()===FALSE)
                {
                    $this->db->trans_rollback();
                }
                else
                {
                    $this->db->trans_commit();
                }
            }break;
            case 'save':
            {
                $post['created_by'] =$this->session->userdata('id');
                $post['created']=date('Y-m-d H:i:s');
                $post['username']=$this->input->post('username');
                $post['password']=$this->input->post('password');
                $post['kode_sales']=$this->input->post('kode_sales');
                $post['siteid']=$this->input->post('siteid');
                //$post['nocab']=23;//$this->session->userdata('nocab');
                //$tglpesan=date('Y-m-d H:i:s');
                $sql="select 1 from salesman.user where username=?";
                $query=$this->db->query($sql,array($post['username']));
                
                if($query->num_rows()>0)
                {
                    return true;
                } 
                else
                {
                    $this->db->trans_begin();
                    $this->db->insert('salesman.user',$post);
                    $this->db->trans_complete();
                    if($this->db->trans_status()===FALSE)
                    {
                        $this->db->trans_rollback();
                    }
                    else
                    {
                        $this->db->trans_commit();
                    }
                    redirect('salesman/admin');
                }
            }
            case 'kode_sales':
            {
                //$siteid='jkt001';//$this->session->userdata('nocab');//$this->session->userdata('username');
                //$sql="select salesmanid,nama_salesman from ".$branch.".m_sales_salesman where siteid=?";
                $sql="select salesmanid kodesales,nama_salesman namasales from salesman.m_sales_salesman where siteid=?";
                $query=$this->db->query($sql,array($key));
                return $query;
            }break;
            case 'add_ajax':
            {
                $branch='JKT';//$this->session->userdata('username');
                $sql="select salesmanid,nama_salesman from ".$branch.".m_sales_salesman where siteid=?";
                $query=$this->db->query($sql,array($key));
                if($query->num_rows()>0)
                {
                    $output=array();
                    foreach($query->result() as $row)
                    {
                        $output[$row->salesmanid]=$row->nama_salesman;
                    }
                }
                return $output;
                //return $query;
            }break;
            case 'getSalesName':
            {
                $sql='select kodesales,namasales from data'.date('Y').'.tabsales where nocab=?';
                $query=$this->db->query($sql,array($key));
                return $query;
            }break;
            case 'siteid':
            {
                $userid='204';//$this->session->userdata('id');
                $sql="select * from salesman.siteid where userid=?";
                $query=$this->db->query($sql,array($userid));//array($this->session->userdata('id')));
                return $query;
            }break;
            case 'active':
            {
                $sql='update salesman.user set active=? where id=?';
                $this->db->query($sql,array($key,$id));
            }break;
            case 'show':
            {
                $sql='select * from salesman.user where created_by='.$this->session->userdata('id');
                $query=$this->db->query($sql);
                if($query->num_rows()>0)
                {
                    $image_properties=array(
                        'src'    => 'assets/css/images/edit.png',
                        'width'  => '20',
                        'height' => '20',
                    );
                    $this->load->library('table');
                    $this->table->set_empty('0');
                    $this->table->set_template($this->tmpl);
                    $this->table->set_caption('<h3>DAFTAR SALESMAN</h3>');
                    $this->table->set_heading('USERNAME','PASSWORD','KODE SALES','SITE ID','EDIT','ACTIVE','RKS');
                    $js=array('onclick'=>"return confirm('Are you sure you want to delete?')");
                    foreach ($query->result() as $value)
                    {
                        if($value->active)
                        {
                            $active='<div div style="text-align:center">'.anchor('salesman/admin/active/0/'.$value->id,img($this->image_yes)).'</div>';
                        }
                        else
                        {
                            $active='<div div style="text-align:center">'.anchor('salesman/admin/active/1/'.$value->id,img($this->image_no)).'</div>';
                        }
                        $this->table->add_row(
                           
                                $value->username
                                ,$value->password
                                ,$value->kode_sales
                                ,$value->siteid
                                ,'<div div style="text-align:center">'.anchor('salesman/admin/edit/'.$value->id,img($image_properties,$js)).'</div>'
                                ,$active
                                ,'<div div style="text-align:center">'.anchor('salesman/rks/add/'.$value->kode_sales,img($image_properties,$js)).'</div>'
                                );
                    }

                    $this->output_table .= $this->table->generate();
                    $this->output_table .= '<br />';
                    $this->table->clear();
                    $query->free_result();
                    $this->output_table;
                    return $this->output_table;
                }
                else
                {
                    return FALSE;
                }
                //$query=
            }break;
        }
    }
    function po($state='show',$key=0,$id=0,$init=0)
    {
        switch($state)
        {
            case 'list':
            {
                $sql='select kode_produk, nama_produk  from salesman.po where siteid=? and kode_sales=? order by nama_produk';
                $query=$this->db->query($sql,array($this->session->userdata('m_siteid'),$this->session->userdata('m_kode_sales')));
                if($query->num_rows()>0)
                {
                    $this->load->library('table');
                    $this->table->set_template($this->tmpl);
                    $this->table->set_caption('DAFTAR PESANAN');
                    $this->table->set_heading('NAMA PRODUK','AVG BELI','STOK LALU','BELI LALU','STOK SEKARANG','TERJUAL','SUGGEST ORDER','COMMIT ORDER');
                    $js=array('onclick'=>"return confirm('APAKAH ANDA YAKIN AKAN DIHAPUS?')");
                    
                    foreach ($query->result() as $value)
                    {
                        $this->table->add_row(
                                $value->nama_produk
                                ,$value->avg_beli
                                ,$value->last_stok
                                ,$value->last_bid
                                ,$value->current_stok
                                ,$value->sale_out
                                ,$value->suggest_order
                                ,'<input type="number" name="'. $value->id.'" size=3>'
                                );
                    }

                    $this->output_table .= $this->table->generate();
                    $this->output_table .= '<br />';
                    $this->table->clear();
                    $query->free_result();
                    return $this->output_table;
                    }
                else
                {
                    return false;
                }
            }break;
            case 'save':
            {
                $sql='select * from salesman.po where siteid=? and kode_sales=? order by nama_produk';
                $query=$this->db->query($sql,array($this->session->userdata('m_siteid'),$this->session->userdata('m_kode_sales')));
                foreach($query->result() as $value)
                {
                    $input=$this->input->post($value->id);
                    $sql='update salesman.po set commit_order=? where id=?';
                    $query=$this->db->query($sql,array($input,$value->id));
                }
                
            }break;
            case 'list_pelanggan':
            {
                $sql='select customerid from salesman.t_sales_rrk where siteid=? and kode_sales=? and periode=? order by nama_produk';
                $query=$this->db->query($sql,array($this->session->userdata('m_siteid'),$this->session->userdata('m_kode_sales'),$this->input->post('periode')));
                if($query->num_rows()>0)
                {
                    $this->load->library('table');
                    $this->table->set_template($this->tmpl);
                    $this->table->set_caption('DAFTAR PESANAN');
                    $this->table->set_heading('NAMA PRODUK','AVG BELI','STOK LALU','BELI LALU','STOK SEKARANG','TERJUAL','SUGGEST ORDER','COMMIT ORDER');
                    $js=array('onclick'=>"return confirm('APAKAH ANDA YAKIN AKAN DIHAPUS?')");
                    
                    foreach ($query->result() as $value)
                    {
                        $this->table->add_row(
                                $value->nama_produk
                                ,$value->avg_beli
                                ,$value->last_stok
                                ,$value->last_bid
                                ,$value->current_stok
                                ,$value->sale_out
                                ,$value->suggest_order
                                ,'<input type="number" name="'. $value->id.'" size=3>'
                                );
                    }

                    $this->output_table .= $this->table->generate();
                    $this->output_table .= '<br />';
                    $this->table->clear();
                    $query->free_result();
                    return $this->output_table;
                    }
                else
                {
                    return false;
                }
            }
            case 'show':
            {
                $sql='select * from salesman.po where siteid=? and kode_sales=? order by nama_produk';
                $query=$this->db->query($sql,array($this->session->userdata('m_siteid'),$this->session->userdata('m_kode_sales')));
                if($query->num_rows()>0)
                {
                    $this->load->library('table');
                    $this->table->set_template($this->tmpl);
                    $this->table->set_caption('DAFTAR PESANAN');
                    $this->table->set_heading('NAMA PRODUK','AVG BELI','STOK LALU','BELI LALU','STOK SEKARANG','TERJUAL','SUGGEST ORDER','COMMIT ORDER');
                    $js=array('onclick'=>"return confirm('APAKAH ANDA YAKIN AKAN DIHAPUS?')");
                    
                    foreach ($query->result() as $value)
                    {
                        $this->table->add_row(
                                $value->nama_produk
                                ,$value->avg_beli
                                ,$value->last_stok
                                ,$value->last_bid
                                ,$value->current_stok
                                ,$value->sale_out
                                ,$value->suggest_order
                                ,'<input type="number" name="'. $value->id.'" size=3>'
                                );
                    }

                    $this->output_table .= $this->table->generate();
                    $this->output_table .= '<br />';
                    $this->table->clear();
                    $query->free_result();
                    return $this->output_table;
                    }
                else
                {
                    return false;
                }
           }break;
        }
    }
    function stok($state='show',$key=0,$id=0,$init=0)
    {
        switch($state)
        {
            case 'save':
            {
                $sql='select * from salesman.po where siteid=? and kode_sales=? order by nama_produk';
                $query=$this->db->query($sql,array($this->session->userdata('m_siteid'),$this->session->userdata('m_kode_sales')));
                foreach($query->result() as $value)
                {
                    $input=$this->input->post($value->id);
                    $sql='update salesman.po set current_stok=?,sale_out=last_stok+last_bid-'.$input.',suggest_order=if(avg_beli*sl/30-'.$input.'<0,0,avg_beli*sl/30-'.$input.') where id=?';
                    $query=$this->db->query($sql,array($input,$value->id));
                }
                
            }break;
            case 'show':
            {
                $sql='select * from salesman.po where siteid=? and kode_sales=? order by nama_produk';
                $query=$this->db->query($sql,array($this->session->userdata('m_siteid'),$this->session->userdata('m_kode_sales')));
                if($query->num_rows()>0)
                {
                    $this->load->library('table');
                    $this->table->set_template($this->tmpl);
                    $this->table->set_caption('STOCK OPNAME');
                    $this->table->set_heading('<h2>NAMA PRODUK</h2>','<h2>STOK</h2>');
                    $js=array('onclick'=>"return confirm('APAKAH ANDA YAKIN AKAN DIHAPUS?')");
                    
                    foreach ($query->result() as $value)
                    {
                        $this->table->add_row(
                                $value->nama_produk
                                ,'<input type="number" name="<h1>'. $value->id.'</h1>" size=3>'
                                );
                    }
      
                    $this->output_table .= $this->table->generate();
                    $this->output_table .= '<br />';
                    $this->table->clear();
                    $query->free_result();
                    return $this->output_table;
                    }
                else
                {
                    return false;
                }
           }break;
        }
    }
    function so($state='login',$key=null,$id=0,$init=0)
    {
        switch($state)
        {
            case 'login':
            {
                $sql="select id from salesman.user where username=? and password=? and active=1";
                $query=$this->db->query($sql,array($key,$id));
                if($query->num_rows()>0)
                {
                    $row=$query->row();
                    $userid=$row->id;
                    $sql="update salesman.user set last_login=? where id =?";
                    $query=$this->db->query($sql,array(date('Y-m-d H:i:s'),$userid));
                    $sql="select kode_sales,siteid,nocab from salesman.user where id=?";
                    $query=$this->db->query($sql,$userid);
                    $row=$query->row();
                    $res=array();
                    $res['success']=1;
                    $res['kode_sales']=$row->kode_sales;
                    $res['siteid']=$row->siteid;
                    $res['nocab']=$row->nocab;
                    $res['username']=$key;
                    $res['password']=$id;
                    
                    return(json_encode($res));
                    //echo 'sukses';
                }
                else
                {
                    // echo 'noreturn';
                    return false;
                   
                }
            }break;
            case 'import':
            {
                $posted=json_decode($key);
                /*$posted=
                '[{"commit_order":"20","suggest_order":"25","kode_sales":"008","sale_out":"45","kode_produk":"010001","siteid":"JKT001","current_stok":"5","tanggal":"2014-12-22","nama_customer":"MONALISA","kode_customer":"100023","nama_produk":"ANTAGIN 4R"},
                {"commit_order":"20","suggest_order":"130","kode_sales":"008","sale_out":"130","kode_produk":"010002","siteid":"JKT001","current_stok":"20","tanggal":"2014-12-22","nama_customer":"MONALISA","kode_customer":"100023","nama_produk":"ANTAGIN 4D"}]';
                $postarray=array(array("commit_order"=>"175","suggest_order"=>"25","kode_sales"=>"008","sale_out"=>"45","kode_produk"=>"010001","siteid"=>"JKT001","current_stok"=>"5","tanggal"=>"2014-12-22","nama_customer"=>"MONALISA","kode_customer"=>"100023","nama_produk"=>"ANTAGIN 4R")
                              ,array("commit_order"=>"177","suggest_order"=>"130","kode_sales"=>"008","sale_out"=>"130","kode_produk"=>"010002","siteid"=>"JKT001","current_stok"=>"20","tanggal"=>"2014-12-22","nama_customer"=>"MONALISA","kode_customer"=>"100023","nama_produk"=>"ANTAGIN 4D"));
                $json=  json_encode($postarray);
                $posted=json_decode($json);*/
                
                foreach($posted as $post)
                {
                    //$where='id='.$key;
                    $set='current_stok='.$post->current_stok.
                         ', sale_out='.$post->sale_out.
                         ', suggest_order='.$post->suggest_order.
                         ', commit_order='.$post->commit_order;
                    $where='tanggal="'.$post->tanggal.'" 
                             and siteid="'.$post->siteid.
                            '" and kode_sales="'.$post->kode_sales.
                            '" and kode_customer="'.$post->kode_customer.
                            '" and kode_produk="'.$post->kode_produk.'"';
                  
                    $sql='update salesman.so set '.$set.' where '.$where; 
                    //$sql=$this->db->update_string('salesman.so',$post,$where);
                    $this->db->query($sql);
                }
                $this->db->trans_begin();
                if($this->db->trans_status()===FALSE)
                {
                    $this->db->trans_rollback();
                }
                else
                {
                    $this->db->trans_commit();
                }
            }break;
            case 'import2':
            {
                $post=json_decode($key);
                $this->db->trans_begin();
                $this->db->insert('salesman.so',$post);
                
                $this->db->trans_complete();
                if($this->db->trans_status()===FALSE)
                {
                    $this->db->trans_rollback();
                }
                else
                {
                    $this->db->trans_commit();
                }
                return 1;
                //redirect('trans/po/show');
            }break;
            case 'download':
            {
                $sql='select weekday(now())';
                $sql='select * from salesman.po a inner join salesman.po_detail b on a.id=b.id_ref'; //where tanggal="'.$key.'" and kode_sales='.$id.' and siteid='.$init;
                $query=$this->db->query($sql);
                return json_encode($query->result_array());
            }break;
            case 'download3':
            {
                //$init='2014-09-11';
                $siteid=$key;
                $kode_sales=$id;
                $tanggal=$init;
                $sql="select a.kode_customer,'".$tanggal."' as tanggal,a.nama_customer,a.kode_produk,a.nama_produk,b.current_stok last_stok, b.commit_order last_bid,b.sale_out from salesman.so a 
                            left join (select kode_customer,siteid,kode_sales,kode_produk,current_stok, commit_order ,sale_out from salesman.so 
                            where siteid='".$siteid."' and kode_sales='".$kode_sales."') b 
                            on a.kode_customer=b.kode_customer and a.kode_produk=b.kode_produk
                            where a.tanggal='".$tanggal."' and a.kode_sales='".$kode_sales."' and a.siteid='".$siteid."'
                            group by a.siteid,a.kode_sales,kode_produk,kode_customer
                            order by nama_customer
                        ";
                $query=$this->db->query($sql);//siteid,kode_sales,tanggal
                return json_encode($query->result_array());
            }break;
            case 'download2':
            {   $kodesales=$key;
                $cycle=6;//(int)($this->input->post('cycle'));
                $counter=$cycle+1;
                //$tanggal=strftime('%Y-%m-%d',strtotime(trim($this->input->post('tanggal'))));
                $tanggal=date('Y-m-d');
                $date=array();
                $dp=$id;
                $year=substr($tanggal,0,4);
                $month=substr($tanggal,5,2);
                //echo date('m');
                //echo date('Y')-1;
                $date[6]=date('Y-m-d',strtotime('-6 month',strtotime($tanggal)));
                $date[5]=date('Y-m-d',strtotime('-5 month',strtotime($tanggal)));
                $date[4]=date('Y-m-d',strtotime('-4 month',strtotime($tanggal)));
                $date[3]=date('Y-m-d',strtotime('-3 month',strtotime($tanggal)));
                $date[2]=date('Y-m-d',strtotime('-2 month',strtotime($tanggal)));
                $date[1]=date('Y-m-d',strtotime('-1 month',strtotime($tanggal)));

                $selectfi="select kode_lang kode,nama,alamat,sl,kodeprod,namaprod,sum(banyak) as  average from data".date('Y',strtotime($date[1])).".fi a inner join
                    (select kode_lang ,nama_lang nama,alamat,sl from salesman.rks_temp where hari=weekday(now())+1 and kode_sales='".$kodesales."') b using(kode_lang) 
                    where nocab=".$dp." and kode_type<>'TD' and kodesales='".$kodesales."' and bulan =".date('m',strtotime($date[1]))." group by kodeprod";
                for($i=2;$i<$counter;$i++)
                {
                    $selectfi.=" union all
                        select kode_lang kode,nama,alamat,sl,kodeprod,namaprod,sum(banyak) as  average from data".date('Y',strtotime($date[$i])).".fi a inner join
                       (select kode_lang ,nama_lang nama,alamat,sl from salesman.rks_temp where hari=weekday(now())+1 and kode_sales='".$kodesales."') b using(kode_lang)
                        where nocab=".$dp." and kode_type<>'TD' and kodesales='".$kodesales."' and bulan =".date('m',strtotime($date[$i]))." group by kodeprod
                        ";
                }
                $selectri="select kode_lang kode,nama,alamat,sl,kodeprod,namaprod,sum(banyak) as  average from data".date('Y',strtotime($date[1])).".ri a inner join
                        (select kode_lang ,nama_lang nama,alamat,sl from salesman.rks_temp where hari=weekday(now())+1 and kode_sales='".$kodesales."') b using(kode_lang)
                        where nocab=".$dp." and kodesales='".$kodesales."' and bulan =".date('m',strtotime($date[1]))." group by kodeprod";
                for($i=2;$i<$counter;$i++)
                {
                    $selectri.=" union all
                        select kode_lang kode,nama,alamat,sl,kodeprod,namaprod,sum(banyak) as  average from data".date('Y',strtotime($date[$i])).".ri a inner join
                        (select kode_lang ,nama_lang nama,alamat,sl from salesman.rks_temp where hari=weekday(now())+1 and kode_sales='".$kodesales."') b using(kode_lang)
                        where nocab=".$dp." and kodesales='".$kodesales."' and bulan =".date('m',strtotime($date[$i]))." group by kodeprod
                        ";
                }
                 $sql="select kode,nama,kodeprod,namaprod,sum(average)/".$cycle." average,sl,alamat from (
                        ".$selectfi." union all ".$selectri." 
                    )a group by kode,kodeprod";
                $query=$this->db->query($sql);
                return json_encode($query->result_array());
            }break;
        }
        
        
    }
    private function getNamaProduk($produk='')
    {
        $sql='select namaprod from mpm.tabprod where kodeprod="'.$produk.'"';
        $query=$this->db->query($sql);
        return $query->row();
    }
    private function getNamaPelanggan($id='')
    {
        $year=date('Y');
        $nocab=$this->session->userdata('nocab');
        $sql="select nama_lang from data".$year.".tblang where custid=".$id;
        $query=$this->db->query($sql);
        return $query->row();
    }
    private function getAlamatPelanggan($id='')
    {
        $year=date('Y');
        $nocab=$this->session->userdata('nocab');
        $sql="select alamat1 alamat from data".$year.".tblang where custid=".$id;
        $query=$this->db->query($sql);
        return $query->row();
    }
    private function getCustomerId($customerid='')
    {
        $sql='select concat("1",kode_lang)kode_lang from mpm.user where id="'.$customerid.'"';
        $query=$this->db->query($sql);
        return $query->row();
    }
    function mobile($state='',$key=0,$id=0,$init=0)
    {
        switch($state)
        {
            case 'po_save':
            {
                $kode_lang=$this->getCustomerId($this->session->userdata('sales_cabang'));
                $namalang=$this->getNamaPelanggan($this->input->post('pelanggan'));
                $alamat=$this->getAlamatPelanggan($this->input->post('pelanggan'));
                $post['tanggal']=date('Y-m-d');
                $post['kode_lang']=$kode_lang->kode_lang;
                $post['cabang']=$this->session->userdata('sales_cabang');
                $post['salesmanid'] =$this->session->userdata('sales_id');
                $post['salesmanname']=$this->session->userdata('sales_nama');
                $post['nama_pelanggan']=$namalang->nama_lang;
                $post['id_pelanggan']=$this->input->post('pelanggan');
                $post['alamat']=$alamat->alamat;
                $post['created_by'] =$this->session->userdata('sales_id');
                $post['created']=date('Y-m-d H:i:s');
                $this->db->insert('mpm.salesman_po',$post);
                $sql='select id from mpm.salesman_po where created="'.$post['created'].'"';
                $query=$this->db->query($sql);
                $row=$query->row();
                $sql='insert into mpm.salesman_po_detail(id_ref,kodeprod,namaprod,jumlah,cabang,kode_lang,salesmanid,salesmanname) select '.$row->id.',kodeprod,namaprod,jumlah,cabang,kode_lang,salesmanid,salesmanname from mpm.salesman_temp where cabang='.$this->session->userdata('sales_cabang');
                $this->db->query($sql); 
                $sql='delete from mpm.salesman_temp where salesmanid='.$this->session->userdata('sales_id');
                $this->db->query($sql); 
            }break;
            case 'temp_delete':
            {
                $sql='delete from mpm.salesman_temp where id=?';
                $this->db->query($sql,array($key));
                
            }break;
            case 'temp_list':
            {
                $sql='select * from mpm.salesman_temp where cabang=? order by id desc';
                $query=$this->db->query($sql,array($this->session->userdata('sales_cabang')));
                if($query->num_rows()>0)
                {
                    $this->load->library('table');
                    $this->table->set_template($this->tmpl);
                    $this->table->set_caption('DAFTAR PESANAN');
                    $this->table->set_heading('DELETE','NAMA PRODUK','JUMLAH');
                    $js=array('onclick'=>"return confirm('APAKAH ANDA YAKIN AKAN DIHAPUS?')");
                    
                    foreach ($query->result() as $value)
                    {
                        $this->table->add_row(
                                '<div div style="text-align:center">'.anchor('salesman/mobile/temp_delete/'.$value->id,img($this->image_mobile_del),$js).'</div>'                         
                                ,$value->namaprod
                                ,'<div div style="text-align:right">'.$value->jumlah.'</div>'
                                );
                    }

                    $this->output_table .= $this->table->generate();
                    $this->output_table .= '<br />';
                    $this->table->clear();
                    $query->free_result();
                    return $this->output_table;
                    }
                else
                {
                    return false;
                }
                
            }break;
            case 'temp_save':
            {
                $sales_id=$this->session->userdata('sales_id');
                $sales_nama=$this->session->userdata('sales_nama');
                $sales_cabang=$this->session->userdata('sales_cabang');
                
                $row=$this->getNamaProduk($this->input->post('produk'));
                $kode_lang=$this->getCustomerId($sales_cabang);
                $post['kodeprod']= $this->input->post('produk');
                $post['namaprod']= $row->namaprod;
                $post['jumlah']=$this->input->post('jumlah');
                $post['kode_lang']=$kode_lang->kode_lang;
                $post['cabang']=$sales_cabang;
                $post['salesmanid'] =$sales_id;
                $post['salesmanname']=$sales_nama;
                $this->db->insert('mpm.salesman_temp',$post);
                
            }break;
            case 'login_check':
            {
                $sql='select id,nama,cabang,nocab from mpm.salesman_user where username=? and hash=? and active=1';
                $query = $this->db->query($sql,array($key,$id));
                if($query->num_rows()>0)
                {
                    $row=$query->row();
                    $sql='update mpm.salesman_user set lastlogin=? where id=?';
                    $this->db->query($sql,array(date('Y-m-d H:i:s'),$row->id));
                    return $query;
                }
                else
                {
                    return 0;
                }
            }
        }
    }
  
    function admin2($state='',$key=0,$id=0,$init=0)
    {
        $userid=$this->session->userdata('id');
        switch($state)
        {
            case 'check':
            {
                $sql='select 1 from mpm.salesman_user where username = ?';
                $query = $this->db->query($sql,array($key));
                if($query->num_rows()>0)
                {
                    return FALSE;
                }
                else
                {
                    return TRUE;
                }
            }break;
            case 'activer':
            {
                $userid=$this->session->userdata('id');
                $post['modified_by']=$userid;
                $post['modified']=date('Y-m-d H:i:s');;
                $post['active']=$key;
                $where='id='.$id;
                $this->db->trans_begin();
                $sql=$this->db->update_string('mpm.salesman_user',$post,$where);
                $this->db->query($sql);
                if($this->db->trans_status()===FALSE)
                {
                    $this->db->trans_rollback();
                }
                else
                {
                    $this->db->trans_commit();
                }
            }break;
            case 'delete':
            {
                $userid=$this->session->userdata('id');
                $post['modified_by']=$userid;
                $post['modified']=date('Y-m-d H:i:s');;
                $post['deleted']=1;
                $where='id='.$key;
                $this->db->trans_begin();
                $sql=$this->db->update_string('mpm.salesman_user',$post,$where);
                $this->db->query($sql);
                if($this->db->trans_status()===FALSE)
                {
                    $this->db->trans_rollback();
                }
                else
                {
                    $this->db->trans_commit();
                }
            }break;
            case 'save':
            {
                $post['nama']=strtoupper($this->input->post('nama'));
                $post['username']= $this->input->post('username');
                $post['password']=$this->input->post('password');
                $post['hash']=md5($this->input->post('password'));
                $post['cabang']=$userid;
                $post['nocab']=$this->session->userdata('nocab');
                $post['created_by'] =$userid;
                $post['created']=date('Y-m-d H:i:s');
                $this->db->insert('mpm.salesman_user',$post);
            }break;
            case 'po_delete':
            {
                $sql='update mpm.salesman_po set deleted=1 where id='.$key;
                $this->db->query($sql);
            }break;
            case 'po_print':
            {
                $server='192.168.1.2';
                $user='root';
                $pass='mpm123';
                $db='mpm';
                $this->load->library('PHPJasperXML');
                $xml = simplexml_load_file("assets/report/report_po_salesman.jrxml");
                @$this->phpjasperxml->debugsql=false;
                @$this->phpjasperxml->arrayParameter=array('nopesan'=>$key);
                @$this->phpjasperxml->xml_dismantle($xml);

                @$this->phpjasperxml->transferDBtoArray($server,$user,$pass,$db);
                @$this->phpjasperxml->outpage("D",$key.'.pdf');
            }break;
            case 'po_list':
            {
                switch($id)
                {
                     case '0':
                     {
                            if($this->session->userdata('cabang')=='1')
                            {
                                $sql='select id,date_format(tanggal,"%d %M %Y") tanggal,salesmanname,
                                nama_pelanggan from mpm.salesman_po 
                                where left(id_pelanggan,2)='.$this->session->userdata('nocab').' and deleted=0 and id_pelanggan='.$key;
                            }
                            else
                            {
                                $sql='select id,date_format(tanggal,"%d %M %Y") tanggal,salesmanname,
                                nama_pelanggan from mpm.salesman_po 
                                where cabang='.$this->session->userdata('id').' and deleted=0 and id_pelanggan='.$key;

                            }
                     }break;
                     case '1':
                     {
                            if($this->session->userdata('cabang')=='1')
                            {
                                $sql='select id,date_format(tanggal,"%d %M %Y") tanggal,salesmanname,
                                nama_pelanggan from mpm.salesman_po 
                                where left(id_pelanggan,2)='.$this->session->userdata('nocab').' and deleted=0 and tanggal like "%'.$key.'%"';
                            }
                            else 
                            {
                                $sql='select id,date_format(tanggal,"%d %M %Y") tanggal,salesmanname,
                                nama_pelanggan from mpm.salesman_po 
                                where cabang='.$this->session->userdata('id').' and deleted=0 and tanggal like "%'.$key.'%"';
                            }
                     }break;
                     default:
                     {
                            if($this->session->userdata('cabang')=='1')
                            {
                                $sql='select a.id,date_format(tanggal,"%d %M %Y") tanggal,salesmanname,nama_pelanggan, upper(username)branch 
                                    from mpm.salesman_po a inner join mpm.user b on a.cabang=b.id where deleted=0 and left(id_pelanggan,2)='.$this->session->userdata('nocab');
                            }
                            else
                            {
                                $sql='select a.id,date_format(tanggal,"%d %M %Y") tanggal,salesmanname,nama_pelanggan, upper(username) branch 
                                    from mpm.salesman_po a inner join mpm.user b on a.cabang=b.id where deleted=0 and cabang='.$this->session->userdata('id');
                            }
                     }break;
                }
                //$sql='select id,date_format(tanggal,"%d %M %Y") tanggal,salesmanname,nama_pelanggan from mpm.salesman_po where cabang='.$this->session->userdata('id');
                $query=$this->db->query($sql);
                $this->total_query = $query->num_rows();
                $sql2= $sql.' limit ? offset ?';
                $query = $this->db->query($sql2,array(10,$init));
                
                if($query->num_rows()>0)
                {
                    $this->load->library('table');
                    $this->table->set_template($this->tmpl);
                    $this->table->set_caption('DAFTAR PO');
                    $this->table->set_heading('DELETE','TANGGAL','SALESMAN','PELANGGAN','BRANCH','PRINT');
                    $js=array('onclick'=>"return confirm('Are you sure you want to delete?')");
                    
                    foreach ($query->result() as $value)
                    {
                         $this->table->add_row(
                                '<div div style="text-align:center">'.anchor('salesman/admin/po_delete/'.$value->id,img($this->image_del),$js).'</div>'                         
                                ,$value->tanggal
                                ,$value->salesmanname
                                ,$value->nama_pelanggan
                                ,$value->branch
                                ,'<div div style="text-align:center">'.anchor('salesman/admin/po_print/'.$value->id,img($this->image_print)).'</div>'    
                                );
                    }
                    $this->output_table .= $this->table->generate();
                    $this->output_table .= '<br />';
                    $this->table->clear();
                    $query->free_result();
                    $this->output_table;
                    return $this->output_table;
                    }
                else
                {
                    return false;
                }
            }break;
            case 'show':
            {
                $sql='select id,nama,username,password,lastlogin,active from mpm.salesman_user where deleted=0 and cabang='.$userid;
                $query=$this->db->query($sql);
                if($query->num_rows()>0)
                {
                    $this->load->library('table');
                    $this->table->set_template($this->tmpl);
                    $this->table->set_caption('<h3>DAFTAR SALESMAN</h3>');
                    $this->table->set_heading('DELETE','NAMA','USERNAME','PASSWORD','TERAKHIR LOGIN','ACTIVE');
                    $js=array('onclick'=>"return confirm('Are you sure you want to delete?')");
                    
                    foreach ($query->result() as $value)
                    {
                        switch ($value->active)
                        {
                            case 1:$active=anchor('salesman/admin/activer/0/'.$value->id,img($this->image_yes));BREAK;
                            case 0:$active=anchor('salesman/admin/activer/1/'.$value->id,img($this->image_no));BREAK;
                        }
                        $this->table->add_row(
                                '<div div style="text-align:center">'.anchor('salesman/admin/delete/'.$value->id,img($this->image_del),$js).'</div>'                         
                                ,$value->nama
                                ,$value->username
                                ,$value->password
                                ,$value->lastlogin
                                ,'<div div style="text-align:center">'.$active.'</div>'
                                );
                    }

                    $this->output_table .= $this->table->generate();
                    $this->output_table .= '<br />';
                    $this->table->clear();
                    $query->free_result();
                    $this->output_table;
                    return $this->output_table;
                    }
                else
                {
                    return false;
                }
            }break;
        }
    }
}
