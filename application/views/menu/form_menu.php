<?php echo form_open($url);?>
<?php
$req='<sup>*</sup>';
$attr = array(
    'style' => 'size: 100;',
);
if(isset($edit))
{
    foreach($edit->result() as $row)
    {
        $menuname=$row->menuname;
        $menuview=$row->menuview;
        $target=$row->target;
        $description=$row->description;
        $selected=$row->groupid;
        $id=$row->id;
    }
    $edit->free_result();
}
else
{
    $menuname='';
    $menuview='';
    $target='';
    $description='';
    $selected=1;
    $id='';
}

?>
<div class='col-md-6'>
<?php echo br().form_fieldset($page_title);?>
<?php echo form_hidden('id',$id)?>
<?php echo form_label('Menu Name'.$req.' <small><i>min 3 character, max 20 character</i></small>','menuname');?><br/>
<?php echo form_error('menuname','<div class="error">','</div>');?>
<?php
if($menuname=='')
   echo form_input('menuname',set_value('menuname'),'class="form-control"');
else
   echo form_input('menuname',$menuname,'readonly class="form-control"');
?><br/><br/>
<?php echo form_label("Menu View");?><br/>
<?php echo form_error('menuview','<div class="error">','</div>');?>
<?php echo form_input('menuview',$menuview,'class="form-control"');?><br/><br/>
<?php echo form_label("Target");?><br/>
<?php echo form_error('target','<div class="error">','</div>');?>
<?php echo form_input('target',$target,'class="form-control"');?><br/><br/>
<?php echo form_label("Description");?><br/>
<?php echo form_error('description','<div class="error">','</div>');?>
<?php echo form_textarea('description',$description,'class="form-control"');?><br/><br/>
<?php echo form_label("Group");?><br/>
<?php
foreach($query->result() as $value)
{
    $group[$value->id]= $value->groupname;
}
echo form_dropdown('group', $group,$selected,'class="form-control"');
$query->free_result();
?><br/><br/>

<?php echo form_submit('submit','SUBMIT','class="btn btn-info"')?>
<?php echo form_reset('reset','RESET','class="btn default"')?><br/><br/>

<?php echo form_fieldset_close();?>
<?php echo form_close();?>
</div>