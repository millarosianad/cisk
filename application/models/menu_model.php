<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */


class Menu_model extends CI_Model
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
    public function Menu_model()
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
    public function list_group()
    {
        $sql='select id,groupname from mpm.groupmenu';
        return $query = $this->db->query($sql);
    }
    public function save_menu($str_id='')
    {
        $groupid = $this->input->post('group');
        $sql='select groupname from mpm.groupmenu where id =?';
        $query = $this->db->query($sql,array($groupid));
        $row = $query->row();
        $post['menuname']=strtoupper($this->input->post('menuname'));
        $post['menuview']=strtoupper($this->input->post('menuview'));
        $post['target']=strtolower($this->input->post('target'));
        $post['description']=$this->input->post('description');
        $post['groupid']=$this->input->post('group');
        $post['groupname']= $row->groupname;
        $post['created_by']=$str_id;
        $post['modified_by']=$str_id;
        $post['created']=date('Y-m-d H:i:s');
        $post['modified']=date('Y-m-d H:i:s');

        $this->db->trans_begin();
        $this->db->insert('menu',$post);
        $this->db->trans_complete();
        if($this->db->trans_status()===FALSE)
        {
            $this->db->trans_rollback();
        }
        else
        {
            $this->db->trans_commit();
        }
        $query->free_result();
        redirect('menu');
    }
    public function check($field,$str)
    {
        //$sql='select menuname from mpm.menu where '.$field.' = "'.$str.'"';
        $sql='select 1 from mpm.menu where menuname = ?';
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
    public function activer($flag,$id)
    {
        $sql='update mpm.menu set active=? where id=?';
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
        redirect('menu/show_menu');
    }
    public function print_menu()
    {
        $sql1="select id,menuname, email,level,active from mpm.menu ";
        $query_print = $this->db->query($sql1);
        if($query_print->num_rows() > 0)
        {
            $this->load->library('table');
            $this->table->set_empty('0');

            $this->table->set_template($this->print);
            $this->table->set_caption('LIST menu');
            $this->table->set_heading('menuNAME','EMAIL', 'LEVEL','ACTIVE');
            foreach ($query_print->result() as $value)
            {
                switch($value->level)
                {
                    case 1:$level='ADMIN';break;
                    case 2:$level='MANAGER';break;
                    case 3:$level='menu';break;
                    case 4:$level='GUEST';break;
                }
                switch ($value->active)
                {
                    case 1:$active='YES';BREAK;
                    case 0:$active='NO';BREAK;
                }

                $this->table->add_row(
                        $value->menuname
                        ,$value->email
                        ,$level
                        ,$active


                );
            }

            $this->output_print .= $this->table->generate();
            $this->output_print .= '<br />';
            //$this->session->set_flashdata('print',$this->output_print);
            $this->table->clear();
            $query->free_result();
            return $this->output_print;
        }
        else
        {
            return FALSE;
        }
    }
    public function show_menu($limit=null,$offset=null)
    {
        $sql1="select id,menuname, menuview,target,description,groupname,active from mpm.menu order by groupname";
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
            $this->table->set_heading('MENUNAME','MENU VIEW', 'TARGET','DESCRIPTION','GROUP','ACTIVE','EDIT','DELETE');
            
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
            $image_properties_edit=array(
                 'src'    => 'assets/css/images/edit.png',
                 'width'  => '20',
                 'height' => '20',
            );
            $image_add = array(
                 'src'    => 'assets/css/images/ADD.png',
                 'height' => '30',
            );
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
                    case 1:$active=anchor('menu/activer/0/'.$value->id,img($image_properties_yes));BREAK;
                    case 0:$active=anchor('menu/activer/1/'.$value->id,img($image_properties_no));BREAK;
                }
                $js=array('onclick'=>"return confirm('Are you sure you want to delete?')");
                $this->table->add_row(
                        $value->menuname
                        ,$value->menuview
                        ,$value->target
                        ,$value->description
                        ,$value->groupname
                        ,$active
                        ,anchor('menu/editMenu/'.$value->id,img($image_properties_edit))
                        ,anchor('menu/delete_menu/'.$value->id,img($image_properties_del),$js)

                );
            }

            $this->output_table .= '<br /><br />';
            $this->output_table .=anchor('menu',img($image_add,'ADD'));
            $this->output_table .= '<br />';
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
    public function delete_menu($id='')
    {
        $sql='delete from mpm.menu where id=?';
        $sql2='delete from mpm.menudetail where menuid=?';
        $this->db->trans_begin();
        $this->db->query($sql,array($id));
        $this->db->query($sql2,array($id));
        $this->db->trans_complete();
        
        if($this->db->trans_status()===FALSE)
        {
            $this->db->trans_rollback();
        }
        else
        {
            $this->db->trans_commit();
        }
        redirect('menu/show_menu');
    }
    public function show_menu_edit($id)
    {
        $sql='select id,menuname, menuview,target,description,groupid, groupname from mpm.menu where id=?';
        return $query=$this->db->query($sql,array($id));
    }
    public function edit_menu($str_id=0, $id=0)
    {
        $groupid = $this->input->post('group');
        $sql='select groupname from mpm.groupmenu where id =?';
        $query = $this->db->query($sql,array($groupid));
        $row = $query->row();
        //$post['menuname']=strtoupper($this->input->post('menuname'));
        $post['menuview']=strtoupper($this->input->post('menuview'));
        $post['target']=strtolower($this->input->post('target'));
        $post['description']=$this->input->post('description');
        $post['groupid']=$this->input->post('group');
        $post['groupname']= $row->groupname;
        //$post['created_by']=$str_id;
        $post['modified_by']=$str_id;
        //$post['created']=date('Y-m-d H:i:s');
        
        $this->db->where('id',$id);
        $this->db->trans_begin();
        $this->db->update('menu',$post);
        $this->db->trans_complete();
        if($this->db->trans_status()===FALSE)
        {
            $this->db->trans_rollback();
        }
        else
        {
            $this->db->trans_commit();
            //echo 'success';
        }
        redirect('menu/show_menu');
    }
}
?>