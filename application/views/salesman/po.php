<style>input[type="number"] {
   width:4em;
}</style>
<br />
<?php
switch($state)
{
    case 'dialog':
    {
        
    }break;
    case 'show':
    {
        echo form_open($uri);
        echo $query;
        echo form_submit('save','SIMPAN');
        echo form_close();
    }break;
    case 'list':
    {
        echo $query;
    }break;
}
?>
