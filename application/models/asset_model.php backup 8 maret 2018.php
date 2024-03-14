<?php
class Asset_model extends CI_Model
{
    var $total_query='';
    var $output_table = '';
    var $output_print = '';
    var $print=array(
                'table_open'=>'<table border="1" cellpadding="4" cellspacing="0">'
                ,'heading_row_start' =>'<tr>'
                ,'heading_row_end'   =>'</tr>'
                ,'heading_cell_start'=>'<th bgcolor = "#ffffff">'
                ,'heading_cell_end'=>'</th>'

                ,'row_start' =>'<tr>'
                ,'row_end'   =>'</tr>'
                ,'cell_start'=>'<td bgcolor = "#ffffff" border="#000000">'
                ,'cell_end'=>'</td>'

                ,'row_alt_start' =>'<tr>'
                ,'row_alt_end'   =>'</tr>'
                ,'cell_alt_start'=>'<td bgcolor = "#ffffff"  border="#000000">'
                ,'cell_alt_end'=>'</td>'
                ,'table_close'=>'</table>'
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

                ,'row_alt_start' =>'<tr class="success">'
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
   
    public function Asset_model()
    {
        $this->load->database();
        $this->load->library(array('table','session'));//session untuk sisi administrasi
        $this->load->helper(array('text','array','to_excel_helper'));
    }
    public function getTotalQuery()
    {
        return $this->total_query;
    }
    public function getAsset($id=null)
    {
        $sql='select * from mpm.asset where id=?';
        return $this->db->query($sql,array($id));
    }
    public function getGrupasset($id=null)
    {
        $sql='select * from mpm.grupasset where id=?';
        return $this->db->query($sql,array($id));
    }
    public function getGrupassetcombo()
    {
        $sql='select id,namagrup from mpm.grupasset';
        return $this->db->query($sql);
    }
    public function delete_asset($id=null)
    {
        $sql='delete from mpm.asset where id=?';
        $this->db->trans_begin();
        $this->db->query($sql,array($id));
        $this->db->trans_complete();
        if($this->db->trans_status()===FALSE)
        {
            $this->db->trans_rollback();
        }
        else
        {
            $this->db->trans_commit();
        }
        redirect('asset/show_asset/');
    }
     public function delete_grupasset($id=null)
    {
        $sql='delete from mpm.grupasset where id=?';
        $this->db->trans_begin();
        $this->db->query($sql,array($id));
        $this->db->trans_complete();
        if($this->db->trans_status()===FALSE)
        {
            $this->db->trans_rollback();
        }
        else
        {
            $this->db->trans_commit();
        }
        redirect('asset/show_grupasset/');
    }
    public function update_asset($id)
    {
        $post['kode']=$this->input->post('kode');
        $post['grupid']=$this->input->post('grup');
        $post['namabarang']=$this->input->post('namabarang');
        $post['jumlah']=$this->input->post('jumlah');
        $post['untuk']=$this->input->post('untuk');
        $dob1=trim($this->input->post('tglperol'));//$dob1='dd/mm/yyyy' format
        $dob_disp1=strftime('%Y-%m-%d',strtotime($dob1));
        $post['tglperol']=$dob_disp1;
        $post['gol']=$this->input->post('gol');
        $post['np']=$this->input->post('np');
        /*$post['koreksi']=$this->input->post('koreksi');
        $dob3=trim($this->input->post('tglkoreksi'));//$dob1='dd/mm/yyyy' format
        $dob_disp3=strftime('%Y-%m-%d',strtotime($dob3));
        $post['tglkoreksi']=$dob_disp3;*/
        $post['nj']=$this->input->post('nj');
        $dob2=trim($this->input->post('tgljual'));//$dob1='dd/mm/yyyy' format
        $dob_disp2=strftime('%Y-%m-%d',strtotime($dob2));
        $post['tgljual']=$dob_disp2;
        $post['deskripsi']=$this->input->post('deskripsi');
        $post['modified_by']=$this->session->userdata('id');
        $post['modified']=date('Y-m-d H:i:s');
        $where='id='.$id;
        $this->db->trans_begin();
        $st=$this->db->update_string('mpm.asset',$post,$where);
        $this->db->query($st);
        $this->db->trans_complete();
        if($this->db->trans_status()===FALSE)
        {
            $this->db->trans_rollback();
        }
        else
        {
            $this->db->trans_commit();
        }
        //echo $st;
        redirect('asset/show_asset/');
    }
    public function update_grupasset($id)
    {
        $post['namagrup']=$this->input->post('namagrup');
        $post['deskripsi']=$this->input->post('deskripsi');
       
        $post['modified_by']=$this->session->userdata('id');
        $post['modified']=date('Y-m-d H:i:s');
        $where='id='.$id;
        $this->db->trans_begin();
        $st=$this->db->update_string('mpm.grupasset',$post,$where);
        $this->db->query($st);
        $this->db->trans_complete();
        if($this->db->trans_status()===FALSE)
        {
            $this->db->trans_rollback();
        }
        else
        {
            $this->db->trans_commit();
        }
        //echo $st;
        redirect('asset/show_grupasset/');
    }
    private function print_asset_excel($tanggal=null,$grupid=0)
    {   
        /*
        echo "<pre>";
        echo "tanggal : ".$tanggal."<br>";
        print_r($grupid);
        echo "</pre>";
        */
        if ($grupid!='')
        {
            $grup='';
            foreach($grupid as $row)
            {
               $grup.=','.$row;
            }
            $grup=preg_replace('/,/', '', $grup,1);
            $sql="select * from(
                select id,grupid,kode,namagrup,jumlah,namabarang,untuk,tglperol,tglbeli,
                persen,nilai as np
                ,golongan
                ,bln as perbulan
                ,if(total>=nilai,nilai*1,total) as totalakm
                ,nilai-total as nilaibuku
                ,if(nj=0 or nj='','AKTIF','JUAL') as status
                from(
                select a.id,kode,NJ,grupid,b.namagrup,namabarang,jumlah,untuk,date_format(tglperol,'%d/%m/%Y') as tglperol,tgljual, tglperol as tglbeli,
                format(gol*100,2) as persen,
                np as nilai,
                if(gol='0.25','I',if(gol='0.125','II',if(gol='0.0625','III','B'))) as golongan,
                (gol*np/12) as bln,
                ((abs(period_diff('".$tanggal."',date_format(tglperol,'%Y%m')))+1) * gol * np / 12)  as total
                from mpm.asset a
                left join mpm.grupasset b on a.grupid=b.id order by id desc
                )a
                where grupid in (".$grup.") and period_diff('".$tanggal."',if(date_format(tgljual,'%Y%m')=197001,date_format(now(),'%Y%m'),
                date_format(tgljual,'%Y%m')))<=0
                and period_diff('".$tanggal."',date_format(tglbeli,'%Y%m'))>=0
                order by namagrup) a where nilaibuku>=0";
            $query=$this->db->query($sql);
            /*
            echo "<pre>";
            print_r($sql);
            echo "</pre>";
            */

            //note suffy :
            /* ada perubahan : sebelumnya query : period_diff($tgl_jual-1)<=0,
                                artinya misal tgljual 201801, tgl now = 201802, maka datanya tidak muncul. jadi saya ubah : period_diff($tgljual,$tglpenarikan)<=0
            return to_excel($query);
        }
        else {
            $sql="select * from (
                select id,grupid,kode,namagrup,jumlah,namabarang,untuk,tglperol,persen,nilai as np,tglbeli
                ,golongan
                ,bln as perbulan
                ,if(total>=nilai,nilai*1,total) as totalakm
                ,nilai-total as nilaibuku  from(
                select a.id,kode,grupid,b.namagrup,namabarang,jumlah,untuk,
                date_format(tglperol,'%d/%m/%Y') as tglperol, tglperol as tglbeli,
                format(gol*100,2) as persen,tgljual,
                np as nilai,
                if(gol='0.25','I',if(gol='0.125','II',if(gol='0.0625','III','B'))) as golongan,
                (gol*np/12) as bln,
                ((abs(period_diff('".$tanggal."',date_format(tglperol,'%Y%m')))+1) * gol * np / 12)  as total
                from mpm.asset a
                left join mpm.grupasset b on a.grupid=b.id order by id desc
                )a
                where
                period_diff('".$tanggal."',if(date_format(tgljual,'%Y%m')=197001,date_format(now(),'%Y%m'),
                date_format(tgljual,'%Y%m')-1))<=0
                and period_diff('".$tanggal."',date_format(tglbeli,'%Y%m'))>=0
                order by namagrup) a where nilaibuku>=0";
            $query=$this->db->query($sql);
            return to_excel($query);
        }

    }

    private function print_asset_excel_nb($tanggal=null,$grupid=0)
    {   
        /*
        echo "<pre>";
        echo "tanggal : ".$tanggal."<br>";
        print_r($grupid);
        echo "</pre>";
        */
        if ($grupid!='')
        {
            $grup='';
            foreach($grupid as $row)
            {
               $grup.=','.$row;
            }
            $grup=preg_replace('/,/', '', $grup,1);
            $sql="select * from(
                select id,grupid,kode,namagrup,jumlah,namabarang,untuk,tglperol,tglbeli,
                persen,nilai as np
                ,golongan
                ,bln as perbulan
                ,if(total>=nilai,nilai*1,total) as totalakm
                ,nilai-total as nilaibuku
                ,if(nj=0 or nj='','AKTIF','JUAL') as status
                from(
                select a.id,kode,NJ,grupid,b.namagrup,namabarang,jumlah,untuk,date_format(tglperol,'%d/%m/%Y') as tglperol,tgljual, tglperol as tglbeli,
                format(gol*100,2) as persen,
                np as nilai,
                if(gol='0.25','I',if(gol='0.125','II',if(gol='0.0625','III','B'))) as golongan,
                (gol*np/12) as bln,
                ((abs(period_diff('".$tanggal."',date_format(tglperol,'%Y%m')))+1) * gol * np / 12)  as total
                from mpm.asset a
                left join mpm.grupasset b on a.grupid=b.id order by id desc
                )a
                where grupid in (".$grup.") and period_diff('".$tanggal."',if(date_format(tgljual,'%Y%m')=197001,date_format(now(),'%Y%m'),
                date_format(tgljual,'%Y%m')-1))<=0
                and period_diff('".$tanggal."',date_format(tglbeli,'%Y%m'))>=0
                order by namagrup) a where nilaibuku=0";
            $query=$this->db->query($sql);


            return to_excel($query);
        }
        else {
            $sql="select * from (
                select id,grupid,kode,namagrup,jumlah,namabarang,untuk,tglperol,persen,nilai as np,tglbeli
                ,golongan
                ,bln as perbulan
                ,if(total>=nilai,nilai*1,total) as totalakm
                ,nilai-total as nilaibuku  from(
                select a.id,kode,grupid,b.namagrup,namabarang,jumlah,untuk,
                date_format(tglperol,'%d/%m/%Y') as tglperol, tglperol as tglbeli,
                format(gol*100,2) as persen,tgljual,
                np as nilai,
                if(gol='0.25','I',if(gol='0.125','II',if(gol='0.0625','III','B'))) as golongan,
                (gol*np/12) as bln,
                ((abs(period_diff('".$tanggal."',date_format(tglperol,'%Y%m')))+1) * gol * np / 12)  as total
                from mpm.asset a
                left join mpm.grupasset b on a.grupid=b.id order by id desc
                )a
                where
                period_diff('".$tanggal."',if(date_format(tgljual,'%Y%m')=197001,date_format(now(),'%Y%m'),
                date_format(tgljual,'%Y%m')-1))<=0
                and period_diff('".$tanggal."',date_format(tglbeli,'%Y%m'))>=0
                order by namagrup) a where nilaibuku=0";
            $query=$this->db->query($sql);
            return to_excel($query);
        }

    }


    private function print_asset_jual_excel()
    {
        $sql="select id,grupid,kode,namagrup,jumlah,namabarang,untuk,tglperol,persen,format(nilai,2) as np,format(nj,2) as nj,tgljual
            ,golongan
            ,format(nilai-total,2) as nilaibuku
            ,format((nj-(nilai-total)),2) as untung
            from(
            select a.id,kode,grupid,b.namagrup,namabarang,jumlah,untuk,tglperol,
            format(gol*100,2) as persen, nj,tgljual,
            np as nilai,
            if(gol='0.25','I',if(gol='0.125','II',if(gol='0.0625','III','B'))) as golongan,
            (gol*np/12) as bln,
            (((abs(period_diff(date_format(tglperol,'%Y%m'),date_format(tgljual,'%Y%m'))))+1) * gol * np / 12)  as total
            from mpm.asset a
            left join mpm.grupasset b on a.grupid=b.id order by id desc
            )a
            where nj!=0";
          $query=$this->db->query($sql);
          echo "<pre>";
            print_r($sql);
            echo "</pre>";

           //return to_excel($query);
    }
    public function print_asset_tahun()
    {
        //$post['format'] =$this->input->post('format');
        $server='192.168.1.2';
        $user='root';
        $pass='mpm123';
        $db='mpm';
        $grupid=$this->input->post('options');

        $format=$this->input->post('format');
        //$dob1=trim($this->input->post('tanggal'));//$dob1='dd/mm/yyyy' format
        //$dob_disp1=strftime('%Y%m',strtotime($dob1));
        //$dob_disp2=strftime('%B %Y',strtotime($dob1));
        $post['tglsusut']=$this->input->post('year');
        //$post['tgltampil']=$dob_disp2;

        //$post['tglsusut']='201108';

        switch($format)
        {
            case 'PDF':
               
                $this->load->library('PHPJasperXML');
                if($grupid!=null)
                {
                    $options=$this->input->post('options');
                    $code='';
                    foreach($options as $kode)
                    {
                        $code.=",".$kode;
                    }
                    $code=preg_replace('/,/', '', $code,1);
                    //echo 'uyk';
                    $post['grupid']=$code;

                    $xml = simplexml_load_file("assets/report/report_penyusutan_pertahun.jrxml");

                }
                else
                {
                    //echo 'uykasdas';
                    $xml = simplexml_load_file("assets/report/report_penyusutan_all_pertahun.jrxml");
                }

                @$this->phpjasperxml->debugsql=false;
                @$this->phpjasperxml->arrayParameter=$post;

                @$this->phpjasperxml->xml_dismantle($xml);

                @$this->phpjasperxml->transferDBtoArray($server,$user,$pass,$db);
                @$this->phpjasperxml->outpage("I");
                //echo '1';
                break;
            case 'EXCEL':
                $this->print_asset_excel($dob_disp1, $grupid);
                //echo '2';
                break;
        }

    }
    public function print_asset()
    {
        //$post['format'] =$this->input->post('format');
        
        $grupid=$this->input->post('options');
        
        $format=$this->input->post('format');
        $dob1=trim($this->input->post('tanggal'));//$dob1='dd/mm/yyyy' format
        $dob_disp1=strftime('%Y%m',strtotime($dob1));
        $dob_disp2=strftime('%B %Y',strtotime($dob1));
        $post['tglsusut']=$dob_disp1;
        $post['tgltampil']=$dob_disp2;

        //$post['tglsusut']='201108';

        switch($format)
        {
            case 'PDF':
               
                $server='192.168.1.2';
                $user='root';
                $pass='mpm123';
                $db='mpm';
                $this->load->library('PHPJasperXML');
                if($grupid!=null)
                {
                    $options=$this->input->post('options');
                    $code='';
                    foreach($options as $kode)
                    {
                        $code.=",".$kode;
                    }
                    $code=preg_replace('/,/', '', $code,1);
                    //echo 'uyk';
                    $post['grupid']=$code;

                    $xml = simplexml_load_file("assets/report/report_penyusutan.jrxml");
                   
                }
                else
                {
                    //echo 'uykasdas';
                    $xml = simplexml_load_file("assets/report/report_penyusutan_all.jrxml");
                }
                
                @$this->phpjasperxml->debugsql=false;
                @$this->phpjasperxml->arrayParameter=$post;

                @$this->phpjasperxml->xml_dismantle($xml);

                @$this->phpjasperxml->transferDBtoArray($server,$user,$pass,$db);
                @$this->phpjasperxml->outpage("D",'ASSET_MPM.PDF');
                //echo '1';
                break;
            case 'EXCEL':
                $this->print_asset_excel($dob_disp1, $grupid);
                //echo '2';
                break;
            case 'EXCEL_nb':
                $this->print_asset_excel_nb($dob_disp1, $grupid);
                //echo '2';
                break;
        }
       
    }
    public function print_asset_jual()
    {
        $format=$this->input->post('format');
        
        switch($format)
        {
            case 'PDF':
                
                $server='192.168.1.2';
                $user='root';
                $pass='mpm123';
                $db='mpm';
                $this->load->library('PHPJasperXML');
                $xml = simplexml_load_file("assets/report/report_penyusutan_jual.jrxml");
               
                @$this->phpjasperxml->debugsql=false;
                //@$this->phpjasperxml->arrayParameter=$post;

                @$this->phpjasperxml->xml_dismantle($xml);

                @$this->phpjasperxml->transferDBtoArray($server,$user,$pass,$db);
                @$this->phpjasperxml->outpage("I");
                break;
            case 'EXCEL':
                $this->print_asset_jual_excel();
                break;
        }

    }
    public function show_asset($limit=null,$offset=null,$keyword=null)
    {
        $sql="select a.id,kode,b.namagrup,nj,namabarang,jumlah,untuk,date_format(tglperol,'%d-%M-%Y') as tglperol,gol,np from mpm.asset a
             left join mpm.grupasset b on a.grupid=b.id where namabarang like '%".$keyword."%' order by b.namagrup,kode";
        $query_sum = $this->db->query($sql);
        $this->total_query = $query_sum->num_rows();
        $sql2= $sql.' limit ? offset ?';
        $query = $this->db->query($sql2,array($limit,$offset));
        if($query->num_rows()>0)
        {
            $image_properties=array(
                 'src'    => 'assets/css/images/detail.gif',
            );
            $image_properties_del=array(
                 'src'    => 'assets/css/images/delete.png',
                 'height' => '20',
                 'weight' => '20'
            );
            $image_properties_print=array(
                 'src'    => 'assets/css/images/print.png',
                 'height' => '20',
                 'weight' => '20'
            );
            $image_properties_print_off=array(
                 'src'    => 'assets/css/images/print_off.png',
                 'height' => '20',
                 'weight' => '20'
            );
            $image_properties_email=array(
                 'src'    => 'assets/css/images/mail.png',
                 'height' => '20',
                 'weight' => '20'
            );
            $image_properties_email_off=array(
                 'src'    => 'assets/css/images/mail_off.png',
                 'height' => '20',
                 'weight' => '20'
            );
            $image_properties_edit=array(
                 'src'    => 'assets/css/images/icon_edit.gif',
            );
            $this->load->library('table');
            $this->table->set_empty('0');

            $this->table->set_empty('0');
            $this->table->set_template($this->tmpl);
            $this->table->set_caption('List Asset');
            $this->table->set_heading('Kode','Nama Barang','Grup', 'Jumlah','Untuk','Tgl Perol','Gol','%','Nilai Perolehan','Status','EDIT','DELETE');
            foreach ($query->result() as $value)
            {
                $js=array('onclick'=>"return confirm('Are you sure you want to delete?')");
                
                switch($value->gol)
                {
                    case '0.25' :$gol='I';break;
                    case '0.125':$gol='II';break;
                    case '0.0625':$gol='III';break;
                    case '0.05':$gol='B';break;
                }
                if($value->nj==0||$value->nj=='')
                {
                    $status ='Aktif';
                }
                else
                {
                    $status ='Jual';
                }
                $persen=($value->gol*100).'%';
                $this->table->add_row(
                        $value->kode
                        ,$value->namabarang
                        ,$value->namagrup
                        ,'<div div style="text-align:right">'.$value->jumlah.'</div>'
                        ,$value->untuk
                        ,$value->tglperol
                        ,'<div div style="text-align:center">'.$gol.'</div>'
                        ,'<div div style="text-align:right">'.$persen.'</div>'
                        ,'<div div style="text-align:right">'.number_format($value->np,2).'</div>'
                        ,'<div div style="text-align:center">'.$status.'</div>'
                        ,'<div div style="text-align:center">'.anchor('asset/edit_asset/'.$value->id,img($image_properties_edit)).'</div>'
                        //,'<div div style="text-align:center">'.anchor('trans/check_po_detail/'.$value->nopesan,img($image_properties_edit)).'</div>'
                        //$image_print_po//'<div div style="text-align:center">'.anchor('trans/print_po_mpm/'.$value->nopesan,img($image_print)).'</div>'
                        //,$image_email_po
                        ,'<div div style="text-align:center">'.anchor('asset/delete_asset/'.$value->id,img($image_properties_del),$js).'</div>'
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
            return FALSE;
        }
    }
   public function show_grupasset($limit=null,$offset=null)
    {
        $sql="select * from mpm.grupasset";
        $query_sum = $this->db->query($sql);
        $this->total_query = $query_sum->num_rows();
        $sql2= $sql.' limit ? offset ?';
        $query = $this->db->query($sql2,array($limit,$offset));
        if($query->num_rows()>0)
        {
            $image_properties=array(
                 'src'    => 'assets/css/images/detail.gif',
            );
            $image_properties_del=array(
                 'src'    => 'assets/css/images/delete.png',
                 'height' => '20',
                 'weight' => '20'
            );
            $image_properties_print=array(
                 'src'    => 'assets/css/images/print.png',
                 'height' => '20',
                 'weight' => '20'
            );
            $image_properties_print_off=array(
                 'src'    => 'assets/css/images/print_off.png',
                 'height' => '20',
                 'weight' => '20'
            );
            $image_properties_email=array(
                 'src'    => 'assets/css/images/mail.png',
                 'height' => '20',
                 'weight' => '20'
            );
            $image_properties_email_off=array(
                 'src'    => 'assets/css/images/mail_off.png',
                 'height' => '20',
                 'weight' => '20'
            );
            $image_properties_edit=array(
                 'src'    => 'assets/css/images/icon_edit.gif',
            );
            $this->load->library('table');
            $this->table->set_empty('0');

            $this->table->set_empty('0');
            $this->table->set_template($this->tmpl);
            $this->table->set_caption('LIST GRUP ASSET');
            $this->table->set_heading('NAMA GRUP','DESKRIPSI','EDIT','DELETE');
            foreach ($query->result() as $value)
            {
                $js=array('onclick'=>"return confirm('Are you sure you want to delete?')");

                $this->table->add_row(
                        $value->namagrup
                       ,$value->deskripsi
                       ,'<div div style="text-align:center">'.anchor('asset/edit_grupasset/'.$value->id,img($image_properties_edit)).'</div>'
                       ,'<div div style="text-align:center">'.anchor('asset/delete_grupasset/'.$value->id,img($image_properties_del),$js).'</div>'
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
            return FALSE;
        }
    }
    public function save_asset($str_id='')
    {
        $post['kode']=$this->input->post('kode');
        $post['grupid']=$this->input->post('grup');
        $post['namabarang']=$this->input->post('namabarang');
        $post['jumlah']=$this->input->post('jumlah');
        $post['untuk']=$this->input->post('untuk');
        $dob1=trim($this->input->post('tglperol'));//$dob1='dd/mm/yyyy' format
        $dob_disp1=strftime('%Y-%m-%d',strtotime($dob1));
        $post['tglperol']=$dob_disp1;
        $post['gol']=$this->input->post('gol');
        $post['np']=$this->input->post('np');
        /*$post['koreksi']=$this->input->post('koreksi');
        $dob3=trim($this->input->post('tglkoreksi'));//$dob1='dd/mm/yyyy' format
        $dob_disp3=strftime('%Y-%m-%d',strtotime($dob3));
        $post['tglkoreksi']=$dob_disp3;*/
        $post['nj']=$this->input->post('nj');
        $dob2=trim($this->input->post('tgljual'));//$dob1='dd/mm/yyyy' format
        $dob_disp2=strftime('%Y-%m-%d',strtotime($dob2));
        $post['tgljual']=$dob_disp2;
       
        $post['deskripsi']=$this->input->post('deskripsi');
        $post['created_by']=$str_id;
        $post['modified_by']=$str_id;
        $post['created']=date('Y-m-d H:i:s');
        $post['modified']=date('Y-m-d H:i:s');

        $this->db->trans_begin();
        $this->db->insert('mpm.asset',$post);
        $this->db->trans_complete();
        if($this->db->trans_status()===FALSE)
        {
            $this->db->trans_rollback();
        }
        else
        {
            $this->db->trans_commit();
        }
        redirect('asset/show_asset/');
    }
    public function save_grupasset($str_id='')
    {
        $post['namagrup']=$this->input->post('namagrup');
        $post['deskripsi']=$this->input->post('deskripsi');
        $post['created_by']=$str_id;
        $post['modified_by']=$str_id;
        $post['created']=date('Y-m-d H:i:s');
        $post['modified']=date('Y-m-d H:i:s');

        $this->db->trans_begin();
        $this->db->insert('mpm.grupasset',$post);
        $this->db->trans_complete();
        if($this->db->trans_status()===FALSE)
        {
            $this->db->trans_rollback();
        }
        else
        {
            $this->db->trans_commit();
        }
        redirect('asset/show_grupasset/');
    }
}

?>
