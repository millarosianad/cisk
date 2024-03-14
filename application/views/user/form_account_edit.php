<?php echo form_open($url);?>
<?php
$req='<sup>*</sup>';
$attr = array(
    'style' => 'size: 100;',
);
if(isset($list))
{
        $id=$list->id;
        $username=$list->username;
        $name=$list->name;
        $email=$list->email;
        $email_finance=$list->email_finance;
        $charge=$list->charge;
        $company=$list->company;
        $address=$list->address;
        $npwp=$list->npwp;
        $phone=$list->phone;
        $alamat_wp=$list->alamat_wp;
        $nama_wp=$list->nama_wp;
       
    //$list->free_result();
}
else
{
        $id=$edit->id;
        $username=$edit->username;
        $name=$edit->name;
        $email=$edit->email;
        $email_finance=$edit->email_finance;
        $charge=$edit->charge;
        $company=$edit->company;
        $npwp=$edit->npwp;
        $address=$edit->address;
        $phone=$edit->phone;
        $alamat_wp=$edit->alamat_wp;
        $nama_wp=$edit->nama_wp;
}


$data = array( 'id'  => $id );


?>

<?php
echo br(2);
echo form_fieldset($page_title);
?>
<div class='col-md-6'>
<?php
echo isset($list)? $edit.'&nbsp;&nbsp;&nbsp;'.$pass:'';
?>
<table class="table">
<tr>
<td><?php echo form_label('Username');?></td>
<td>:</td>
<td><?php echo $username;?></td>
</tr>
<tr>
<td><?php echo form_label("Email");?></td>
<td>:</td>
<td><?php echo (isset($list)) ? $email : form_input('email',$email,"class='form-control'");?></td>
</tr>
<tr>
<td><?php echo form_label("Email Finance");?></td>
<td>:</td>
<td><?php echo (isset($list)) ? $email_finance : form_input('email_finance',$email_finance,"class='form-control'");?></td>
</tr>
<tr>
<td><?php echo form_label("Nama Pengguna");?></td>
<td>:</td>
<td><?php echo (isset($list)) ? $name : form_input('name',$name,"class='form-control'");?></td>
</tr>
<tr>
<td><?php echo form_label("Nama Perusahaan");?></td>
<td>:</td>
<td><?php echo (isset($list)) ? $company :form_input('company',$company,"class='form-control'");?></td>
</tr>
<tr>
<td><?php echo form_label("PIC (Person In Charge)");?></td>
<td>:</td>
<td><?php echo (isset($list)) ?$charge : form_input('charge',$charge,"class='form-control'");?></td>
</tr>
<tr>
<td><?php echo form_label("Phone / Fax");?></td>
<td>:</td>
<td><?php echo (isset($list)) ?$phone : form_input('phone',$phone,"class='form-control'");?></td>
</tr>
<tr>
<td><?php echo form_label("NPWP");?></td>
<td>:</td>
<td><?php echo (isset($list)) ? $npwp : form_input('npwp',$npwp,"class='form-control'");?></td>
</tr>
<tr>
<td><?php echo form_label("Nama (NPWP)");?></td>
<td>:</td>
<td><?php echo (isset($list)) ? $nama_wp : form_input('nama_wp',$nama_wp,"class='form-control'");?></td>
</tr>
</table>
</div>
<div class='col-md-6'>
<?php echo form_label("Alamat (NPWP) : ");?><br/>
<?php echo (isset($list)) ? $alamat_wp : form_textarea('alamat_wp',$alamat_wp,"class='form-control'");?><br/><br/>
<?php echo form_label("Alamat Kantor (Pengiriman) : ");?><br/>
<?php echo (isset($list)) ? $address : form_textarea('address',$address,"class='form-control'");?><br/><br/>

<div class="form-group">
<?php echo (isset($list)) ?'':form_submit('submit','SUBMIT','class="btn btn-info"').'&nbsp&nbsp&nbsp'?>
<?php echo (isset($list)) ?'':form_reset('reset','RESET','class="btn btn-default"')?>
</div>
<br/><br/>
<?php echo form_hidden($data);?>
<?php echo form_fieldset_close();?>
<?php echo form_close();?>
</div>