<script type="text/javascript">
  $(document).ready(function() {
    $("#datepicker").datepicker({
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
    case 'dialog_download':
    {
        ?>
        <div style="position: fixed;
            top: 50%;
            left: 50%;
            margin-top: -50px;
            margin-left: -100px;">
        <?php
        echo form_open($uri);
        echo form_label(" TANGGAL : ");
        echo form_input('tanggal','','id="datepicker" class="form-control"');
        echo br();
        echo form_label(" FORMAT : ");
            $options=array();
            // $options['1']='PPN';
            // $options['2']='MYOB';
            // $options['3']='PEMBELIAN';
            // $options['4']='FAKTUR PAJAK & KOMERSIL';
            // $options['5']='RETUR SUPP TDT';
            // $options['6']='SURAT KONF. PIUTANG';
            $options['7']='RETUR SUPP NON TDT';
            // $options['8']='RETUR SUPP NON TDT (Client)';
            $options['9']='RETUR SUPP NON TDT (US)';
            $options['10']='RETUR SUPP NON TDT (Delto)';
        echo form_dropdown('format', $options, 'PPN','class="form-control"');
        echo br();
        echo form_submit('submit','DOWNLOAD','class="btn btn-info"');
        echo form_close();
        echo '</div>';
    }break;
}
?>
