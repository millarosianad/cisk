<?php
defined('BASEPATH') or exit('No direct script access allowed');

class M_profile extends CI_Model
{
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
        redirect('profile/account/');
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
            redirect('profile/account/');
        }
        else
        {
            echo '<script>alert("Password error, Silahkan masukan password baru");</script>';
            redirect('profile/account/', 'refresh');
        }
    }
}