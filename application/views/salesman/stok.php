<script type="text/javascript">
  $(document).ready(function() {
    $("#inputField").datepicker({
        dateFormat:"yy-mm-dd",
        changeYear:true,
        changeMonth:true
    });
  });
</script>

<?php
echo br(2);
switch($state)
{
    case 'dialog':
    {
        echo form_open($uri);?>
        <div class="col-lg-4"><h1>
            <?php echo form_label('PERIODE',"class=form-control");?>
            <br/><br/><input type="text" name="periode" size="12" id="inputField" /><br/><br/>
            <?php echo form_submit('pilih','PILIH')?>
            </h1>
        </div>
        <?php echo form_close();
    }break;
    case 'list_pelanggan':
    {
        echo form_open($uri);
        $row=$query->row();
        echo form_dropdown();
        echo form_close();
    }break;
    case 'show':
    {
        echo form_open($uri);
        echo br(2).$query;
        echo form_submit('save','SIMPAN');
        echo form_close();
    }break;
}
?>
