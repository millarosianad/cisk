<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */


class User_model extends CI_Model
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
   
    public function User_model()
    {
        $this->load->database();
        $this->load->library(array('table','session'));//session untuk sisi administrasi
        $this->load->helper(array('text','array'));
        //$this->config->load('sorot');
    }
    public function getTotalQuery()
    {
        return $this->total_query;
    }
    public function save_user($str_id='')
    {
        $post['username']=$this->input->post('username');
        $post['password']=$this->input->post('password');
        $post['email']=$this->input->post('email');
        $post['email_finance']=$this->input->post('email_finance');
        $post['level']=$this->input->post('level');
        $post['supp']=$this->input->post('supp');
        $post['created_by']=$str_id;
        $post['modified_by']=$str_id;
        $post['created']=date('Y-m-d H:i:s');
        $post['modified']=date('Y-m-d H:i:s');
    
        $this->db->trans_begin();
        $this->db->insert('mpm.user',$post);
        $this->db->trans_complete();
        if($this->db->trans_status()===FALSE)
        {
            $this->db->trans_rollback();
        }
        else
        {
            $this->db->trans_commit();
        }
        redirect('user/show_user/');
    }
    public function check($field,$str)
    {
        //$sql='select username from mpm.user where '.$field.' = "'.$str.'"';
        $sql='select 1 from mpm.user where username = ?';
        $query = $this->db->query($sql,array($str));
        if($query->num_rows()>0)
        {
            return FALSE;
        }
        else
        {
            return TRUE;
        }
    }
    public function getSupp()
    {
        $query=$this->db->query('select supp,namasupp from mpm.tabsupp');
        return $query;
    }
    public function activer($flag,$id)
    {
        $sql='update mpm.user set active=? where id=?';
        $this->db->trans_begin();
        $this->db->query($sql,array($flag,$id));
        $this->db->trans_complete();
        if($this->db->trans_status()===FALSE)
        {
            $this->db->trans_rollback();
        }
        else
        {
            $this->db->trans_commit();
        }
        //redirect('user/show_user');
    }
    public function print_user()
    {
        $sql1="select id,username, email,level,active from mpm.user ";
        $query_print = $this->db->query($sql1);
        if($query_print->num_rows() > 0)
        {
            $this->load->library('table');
            $this->table->set_empty('0');

            $this->table->set_template($this->print);
            $this->table->set_caption('LIST USER');
            $this->table->set_heading('USERNAME','EMAIL', 'LEVEL','ACTIVE');
            foreach ($query_print->result() as $value)
            {
                switch($value->level)
                {
                    case 1:$level='ADMIN';break;
                    case 2:$level='MANAGER';break;
                    case 3:$level='USER';break;
                    case 4:$level='GUEST';break;
                }
                switch ($value->active)
                {
                    case 1:$active='YES';BREAK;
                    case 0:$active='NO';BREAK;
                }
          
                $this->table->add_row(
                        $value->username
                        ,$value->email
                        ,$level
                        ,$active
                      

                );
            }

            $this->output_print .= $this->table->generate();
            $this->output_print .= '<br />';
            //$this->session->set_flashdata('print',$this->output_print);
            $this->table->clear();
            return $this->output_print;
        }
        else
        {
            return FALSE;
        }
    }
    public function show_user_edit($id)
    {
        $sql='select id,username, email,email_finance,level,active from mpm.user where id=?';
        return $query=$this->db->query($sql,array($id));
    }
    public function assign_menu($str_id=0,$id=0)
    {
        $sql="select * from (
              select a.*, b.userid ,b.id as detailid from menu a left join menudetail b on a.id=b.menuid where userid=".$id."
              union all
              select *,'',''  from menu where id not in  (select a.id from menu a left join menudetail b on a.id=b.menuid where userid=".$id.")
              )a  where active = 1  order by groupname, menuname";



        return $this->db->query($sql);
    }
    public function account($id)
    {
        $sql='select * from user where id=?';
        $query=$this->db->query($sql,array($id));
        if($query->num_rows()>0)
        {
            $row=$query->row();
            $this->load->library('table');

            
            $this->table->add_row('Username',':', $row->username);
            $this->table->add_row('Email',':', $row->email);
            $this->table->add_row('Email Finance',':', $row->email_finance);
            $this->table->add_row('Name',':', $row->name);
            $this->table->add_row('PIC',':', $row->charge);
            $this->table->add_row('Company',':',$row->company);
            $this->table->add_row('Alamat Kantor',':',$row->address);
            $this->table->add_row('Telepon/Fax',':',$row->phone);
            $this->table->add_row('NPWP',':', $row->npwp);
            $this->table->add_row('Nama WP',':',$row->nama_wp);
            $this->table->add_row('Alamat WP',':', $row->alamat_wp);
            
            return $row;//$this->table->generate();
        }
    }
    public function account_save($id)
    {
        $post['name']=$this->input->post('name');
        $post['email']=$this->input->post('email');
        $post['email_finance']=$this->input->post('email_finance');
        $post['charge']=$this->input->post('charge');
        $post['npwp']=$this->input->post('npwp');
        $post['nama_wp']=$this->input->post('nama_wp');
        $post['alamat_wp']=$this->input->post('alamat_wp');
        $post['phone']=$this->input->post('phone');
        $post['company']=strtoupper($this->input->post('company'));
        $post['address']=$this->input->post('address');
        //$post['created_by']=$str_id;
        $post['modified_by']=$id;
        //$post['created']=date('Y-m-d H:i:s');
        $post['modified']=date('Y-m-d H:i:s');

        
        $where='id='.$id;
        $this->db->trans_begin();
        $st=$this->db->update_string('mpm.user',$post,$where);
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
        //redirect('user/account/');
    }
    public function password_save($id)
    {
        if($this->input->post('password')!='')
        {
            $post['password']=md5($this->input->post('password'));
            //$post['created_by']=$str_id;
            $post['modified_by']=$id;
            //$post['created']=date('Y-m-d H:i:s');
            $post['modified']=date('Y-m-d H:i:s');
            $where='id='.$id;
            $this->db->trans_begin();
            $st=$this->db->update_string('mpm.user',$post,$where);
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
            redirect('user/account/');
        }
        else
        {
            redirect('user/password_err/');
        }
    }
    public function assign_menu_save($str_id=0,$id=0)
    {
        $menu=$this->input->post('menu');
        $this->delete_user_menu($id);
        foreach($menu as $ar)
        {
            $post['menuid']=$ar;
            $post['userid']=$id;
            $post['modified_by']=$str_id;
            $post['modified']=date('Y-m-d H:i:s');
        
            $this->db->trans_begin();
            $this->db->insert('menudetail',$post);
            $this->db->trans_complete();
            if($this->db->trans_status()===FALSE)
            {
                $this->db->trans_rollback();
            }
            else
            {
                $this->db->trans_commit();
            }
        }
        redirect('user/show_user');
    }
    public function list_menu($str_id='',$id='')
    {
        $sql1='select * from menu';
        $query_print = $this->db->query($sql1);
        $this->total_query = $query_print->num_rows();
        $sql2= $sql1.' limit ? offset ?';
        $query = $this->db->query($sql2,array($limit,$offset));

        if($query->num_rows() > 0)
        {
            $this->load->library('table');
            $this->table->set_empty('0');

            $this->table->set_empty('0');
            $this->table->set_template($this->tmpl);
            $this->table->set_caption('LIST MENU');
            $this->table->set_heading('MENUNAME','MENU VIEW', 'TARGET','DESCRIPTION','GROUP','SET MENU');
            foreach ($query->result() as $value)
            {
                /*switch($value->level)
                {
                    case 1:$level='ADMIN';break;
                    case 2:$level='MANAGER';break;
                    case 3:$level='menu';break;
                    case 4:$level='GUEST';break;
                }*/
                switch ($value->active)
                {
                    case 1:$active=anchor('menu/activer/0/'.$value->id,'YES');BREAK;
                    case 0:$active=anchor('menu/activer/1/'.$value->id,'NO');BREAK;
                }
               // $js=array('onclick'=>"return confirm('Are you sure you want to delete?')");
                $this->table->add_row(
                        $value->menuname
                        ,$value->menuview
                        ,$value->target
                        ,$value->description
                        ,$value->groupname
                        ,$active
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
    public function show_user($limit=null,$offset=null)
    {
        $user=$this->input->post('search');
        $sql1="select id,username,company,address,npwp,email,email_finance,level,active from mpm.user where username like '%".$user."%'";
        $query_print = $this->db->query($sql1);
        $this->total_query = $query_print->num_rows();
        $sql2= $sql1.' limit ? offset ?';
        $query = $this->db->query($sql2,array($limit,$offset));
        
        if($query->num_rows() > 0)
        {
            $this->load->library('table');
            $this->table->set_empty('0');
            $this->table->set_empty('0');
            $this->table->set_template($this->tmpl);
            $this->table->set_caption('LIST USER');
            $this->table->set_heading('USERNAME','COMPANY','ADDRESS','NPWP','EMAIL','LEVEL','ACTIVE','MENU','EDIT','DELETE');
            $image_properties_yes=array(
                 'src'    => 'assets/css/images/yes.png',
                 'width'  => '20',
                 'height' => '20',
            );
            $image_properties_no=array(
                 'src'    => 'assets/css/images/no.png',
                 'width'  => '20',
                 'height' => '20',
            );
            $image_properties_del=array(
                 'src'    => 'assets/css/images/delete.png',
                 'width'  => '20',
                 'height' => '20',
            );
            $image_properties_menu=array(
                 'src'    => 'assets/css/images/menu.png',
                 'width'  => '20',
                 'height' => '20',
            );
            $image_add = array(
                 'src'    => 'assets/css/images/ADD.png',
                 'height' => '30',
            );
            $image_properties_edit=array(
                 'src'    => 'assets/css/images/edit.png',
                 'width'  => '20',
                 'height' => '20',
            );
            foreach ($query->result() as $value)
            {
                switch($value->level)
                {
                    case 1:$level='ADMIN';break;
                    case 2:$level='MANAGER';break;
                    case 3:$level='USER';break;
                    case 4:$level='DP';break;
                    case 5:$level='PBF';break;
                    case 6:$level='BSP';break;
                    case 7:$level='PERMEN';break;
                    
                }
                switch ($value->active)
                {
                    case 1:$active=anchor('user/activer/0/'.$value->id,img($image_properties_yes));BREAK;
                    case 0:$active=anchor('user/activer/1/'.$value->id,img($image_properties_no));BREAK;
                }
                $id=$this->session->userdata('id');
                if($id==11 || $id==297)
                {
                    $menu='<div align="center">'.anchor('user/assign_menu/1/'.$value->id,img($image_properties_menu))."</div>";
                }
                else
                {
                    $menu='<div align="center">'.img($image_properties_menu)."</div>";
                }
                $js=array('onclick'=>"return confirm('Are you sure you want to delete?')");
                $this->table->add_row(
                        $value->username
                        ,$value->company
                        ,$value->address
                        ,$value->npwp
                        ,$value->email
                        ,$level
                        ,'<div align="center">'.$active.'</div>'
                        ,$menu
                        ,'<div align="center">'.anchor('user/account_edit/'.$value->id,img($image_properties_edit))."</div>"
                        ,'<div align="center">'.anchor('user/delete_user/'.$value->id,img($image_properties_del),$js)."</div>"
                );
            }

            $this->output_table .= '<br /><br />';
            $this->output_table .=anchor('user',img($image_add,'ADD'));
            $this->output_table .= '<br />';
            $this->output_table .= $this->table->generate();
            $this->output_table .= '<br />';
            $this->table->clear();
            return $this->output_table;
        }
        else
        {
            return FALSE;
        }
    }
    private function delete_user_menu($id=0)
    {
        $sql='delete from mpm.menudetail where userid=?';
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
    }
    public function delete_user($id='')
    {
        $sql='delete from mpm.user where id=?';
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
        redirect('user/show_user');
    }
    public function edit_user($str_id='', $id='')
    {
        $post['username']=$this->input->post('username');
        $post['password']=$this->input->post('password');
        $post['email']=$this->input->post('email');
        $post['email_finance']=$this->input->post('email_finance');
        $post['level']=$this->input->post('level');
        
        //$post['created_by']=$str_id;
        $post['modified_by']=$str_id;
        //$post['created']=date('Y-m-d H:i:s');
        $post['modified']=date('Y-m-d H:i:s');
        $where='id='.$id;
        $this->db->trans_begin();
        $this->db->update_string('user',$post,$where);
        $this->db->trans_complete();
        if($this->db->trans_status()===FALSE)
        {
            $this->db->trans_rollback();
        }
        else
        {
            $this->db->trans_commit();
        }
        redirect('user');
    }
}
?>