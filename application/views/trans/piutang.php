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
    case 'show_dialog':
    {?>
        <div style="position: fixed;
            top: 50%;
            left: 50%;
            margin-top: -50px;
            margin-left: -100px;">
        <?php
        echo '<div class="form-group">';
        echo form_open($uri);
        echo form_label('TANGGAL : ','for="datepicker"');
        echo form_input('tanggal','','id="datepicker" class="form-control"');
        $options=array(1=>'SCREEN',2=>'PDF',3=>'EXCEL');
        echo form_label('FORMAT : ','for="drop_format"');
        echo form_dropdown('format',$options,'','id="drop_format" class="form-control"');
        $options=array(1=>'MINGGU',2=>'BULAN');
        echo form_label('RANGE : ','for="drop_range"');
        echo form_dropdown('range',$options,'','id="drop_range" class="form-control"').br();
        echo form_submit('submit','SUBMIT','class="btn btn-info"');
        echo form_close(); 
        echo '</div>';
    }break;
    case 'show_dialog_barat':
    {?>
        <div style="position: fixed;
            top: 50%;
            left: 50%;
            margin-top: -50px;
            margin-left: -100px;">
        <?php
        echo '<div class="form-group">';
        echo form_open($uri);
        echo form_label('TANGGAL : ','for="datepicker"');
        echo form_input('tanggal','','id="datepicker" class="form-control"');
        $options=array(1=>'SCREEN',2=>'PDF',3=>'EXCEL');
        echo form_label('FORMAT : ','for="drop_format"');
        echo form_dropdown('format',$options,'','id="drop_format" class="form-control"');
        $options=array(1=>'MINGGU',2=>'BULAN');
        echo form_label('RANGE : ','for="drop_range"');
        echo form_dropdown('range',$options,'','id="drop_range" class="form-control"').br();
        echo form_submit('submit','SUBMIT','class="btn btn-info"');
        echo form_close(); 
        echo '</div>';
    }break;
    case 'show_dialog_timur':
    {?>
        <div style="position: fixed;
            top: 50%;
            left: 50%;
            margin-top: -50px;
            margin-left: -100px;">
        <?php
        echo '<div class="form-group">';
        echo form_open($uri);
        echo form_label('TANGGAL : ','for="datepicker"');
        echo form_input('tanggal','','id="datepicker" class="form-control"');
        $options=array(1=>'SCREEN',2=>'PDF',3=>'EXCEL');
        echo form_label('FORMAT : ','for="drop_format"');
        echo form_dropdown('format',$options,'','id="drop_format" class="form-control"');
        $options=array(1=>'MINGGU',2=>'BULAN');
        echo form_label('RANGE : ','for="drop_range"');
        echo form_dropdown('range',$options,'','id="drop_range" class="form-control"').br();
        echo form_submit('submit','SUBMIT','class="btn btn-info"');
        echo form_close(); 
        echo '</div>';
    }break;
    case 'show':
    {
        ?>
        <script>
        jQuery(document).ready(function($)
        {
            $('#thetable').tableScroll({height:400});

        });
        </script>
        <?php
        echo $query;
    }break;
    case 'show_barat':
    {
        ?>
        <script>
        jQuery(document).ready(function($)
        {
            $('#thetable').tableScroll({height:400});

        });
        </script>
        <?php
        echo $query;
    }break;
    case 'show_timur':
    {
        ?>
        <script>
        jQuery(document).ready(function($)
        {
            $('#thetable').tableScroll({height:400});

        });
        </script>
        <?php
        echo $query;
    }break;
    case 'detail':
    {
        //echo '<div id="table-responsive">'.$query.'</div>';
        echo $query;
        echo form_open($url);
        echo form_submit('submit','Send Email');
        echo form_close();
    }break;
}

?>
