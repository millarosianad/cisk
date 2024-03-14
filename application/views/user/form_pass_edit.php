<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
$hid=array('id'=>$id);
echo form_fieldset($page_title);
echo form_open($url);
echo 'Change password for username <b>'.$name.'</b>';
echo br(2);
echo form_label('New Password').'&nbsp;&nbsp;&nbsp;&nbsp;';
echo form_password('password');
echo br(2);
echo form_submit('submit','SUBMIT');
echo form_reset('reset',"RESET");
echo form_hidden($hid);
echo br(2);
echo isset($message)?'<b>Warning : '.$message.'</b>' : '';
echo form_close();
echo form_fieldset_close();

?>
