<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */


class Groupmenu_model extends CI_Model
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
    
    Public function Groupmenu_model()
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
    public function save_groupmenu($str_id='')
    {
        $groupid = $this->input->post('group');
        $sql='select groupname from mpm.groupmenu where id =?';
        $query = $this->db->query($sql,array($groupid));
        $post['groupname']=strtoupper($this->input->post('groupname'));
        $post['description']=$this->input->post('description');
        $post['created_by']=$str_id;
        $post['modified_by']=$str_id;
        $post['created']=date('Y-m-d H:i:s');
        $post['modified']=date('Y-m-d H:i:s');

        $this->db->trans_begin();
        $this->db->insert('groupmenu',$post);
        $this->db->trans_complete();
        if($this->db->trans_status()===FALSE)
        {
            $this->db->trans_rollback();
        }
        else
        {
            $this->db->trans_commit();
        }
        redirect('groupmenu');
    }
    public function check($field,$str)
    {
        //$sql='select groupmenuname from mpm.groupmenu where '.$field.' = "'.$str.'"';
        $sql='select 1 from mpm.groupmenu where groupname = ?';
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
        $sql='update mpm.groupmenu set active=? where id=?';
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
        redirect('groupmenu/show_groupmenu');
    }
    public function print_groupmenu()
    {
        $sql1="select id,groupname, description ,active from mpm.groupmenu ";
        $query_print = $this->db->query($sql1);
        if($query_print->num_rows() > 0)
        {
            $this->load->library('table');
            $this->table->set_empty('0');

            $this->table->set_template($this->print);
            $this->table->set_caption('LIST GROUPMENU');
            $this->table->set_heading('GOUPNAME','DESCRIPTION','ACTIVE');
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
                        $value->groupname
                        ,$value->description
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
    public function show_groupmenu($limit=null,$offset=null)
    {
        

        $sql1="select id,groupname, description,active from mpm.groupmenu ";
        $query_print = $this->db->query($sql1);
        $this->total_query = $query_print->num_rows();
        $sql2= $sql1.' limit ? offset ?';
        $query = $this->db->query($sql2,array($limit,$offset));

         $image_properties_yes=array(
                 'src'    => 'assets/css/images/yes.png',
                 'width'  => '40',
                 'height' => '40',
            );
            $image_properties_no=array(
                 'src'    => 'assets/css/images/no.png',
                 'width'  => '40',
                 'height' => '40',
            );
            $image_properties_del=array(
                 'src'    => 'assets/css/images/delete.png',
                 'width'  => '40',
                 'height' => '40',
            );
            $image_properties_menu=array(
                 'src'    => 'assets/css/images/menu.png',
                 'width'  => '40',
                 'height' => '40',
            );
            $image_add = array(
                 'src'    => 'assets/css/images/ADD.png',
                 'height' => '30',
            );
        if($query->num_rows() > 0)
        {
            $this->load->library('table');
            $this->table->set_empty('0');

            $this->table->set_empty('0');
            $this->table->set_template($this->tmpl);
            $this->table->set_caption('LIST GROUPMENU');
            $this->table->set_heading('GROUP NAME','DESCRIPTION','ACTIVE','DELETE');
            foreach ($query->result() as $value)
            {
                /*switch($value->level)
                {
                    case 1:$level='ADMIN';break;
                    case 2:$level='MANAGER';break;
                    case 3:$level='USER';break;
                    case 4:$level='GUEST';break;
                }*/
                switch ($value->active)
                {
                    case 1:$active=anchor('groupmenu/activer/0/'.$value->id,img($image_properties_yes));BREAK;
                    case 0:$active=anchor('groupmenu/activer/1/'.$value->id,img($image_properties_no));BREAK;
                }
                $js=array('onclick'=>"return confirm('Are you sure you want to delete?')");
                $this->table->add_row(
                        $value->groupname
                        ,$value->description
                        ,$active
                        ,anchor('groupmenu/delete_groupmenu/'.$value->id,img($image_properties_del),$js)

                );
            }

            $this->output_table .= '<br /><br />';
            $this->output_table .=anchor('groupmenu',img($image_add,'ADD'));
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
    public function delete_groupmenu($id='')
    {
        $sql='delete from mpm.groupmenu where id=?';
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
        redirect('groupmenu/show_groupmenu');
    }
    public function edit_groupmenu($str_id='', $id='')
    {
        $post['groupmenuname']=$this->input->post('groupmenuname');
        $post['password']=$this->input->post('password');
        $post['email']=$this->input->post('email');
        $post['level']=$this->input->post('level');
        //$post['created_by']=$str_id;
        $post['modified_by']=$str_id;
        //$post['created']=date('Y-m-d H:i:s');
        $post['modified']=date('Y-m-d H:i:s');
        $where='id='.$id;
        $this->db->trans_begin();
        $this->db->update_string('groupmenu',$post,$where);
        $this->db->trans_complete();
        if($this->db->trans_status()===FALSE)
        {
            $this->db->trans_rollback();
        }
        else
        {
            $this->db->trans_commit();
        }
        redirect('groupmenu');
    }
}
?>