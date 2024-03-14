<script type="text/javascript">
  $(document).ready(function() {
    $("#datepicker").datepicker({
        dateFormat:"yy-mm-dd",
        changeYear:true,
        changeMonth:true
    });
  });
</script>
<script type="text/javascript">
  $(document).ready(function() {
    $("#datepicker2").datepicker({
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
    case 'show':
    {
        echo $add;
        echo isset($print)?$print:''.br(2);
        echo '<table class=table>';
        echo '<tr>';
        echo '<td>';
       
        ?>
        
        <?php
        echo form_open($uri);?>
        <div class="form-inline">
        <?php echo form_input('keyword',$keyword,'id="datepicker" class="form-control"');?>
        <?php echo form_submit('submit','FIND','class="btn btn-info"');?>
        </div>
        <?php
        echo form_close();
        
        echo '</td>';
        
        echo '<td>';
        echo form_open($uri2);
        ?>
        <div class="form-inline">
        <?php echo form_input('range','','id="datepicker2" class="form-control"');?>
        <?php $ketdd=array(1=>'Faktur Lunas',0=>'Copy Faktur');?>
        <?php echo form_dropdown('keterangan',$ketdd,'','class="form-control"');?>
        <?php echo form_submit('submit','PRINT','class="btn btn-info"');?></div>
        <?php echo form_close();
        echo '</td>';
        echo '</tr>';
        echo '</table>';
        echo $pagination;?>
        <div class="row">
            <div class="col-md-10">
                <?php echo $query;?>
            </div>
        </div>
        <?php echo $pagination;
    }break;
    case 'show_faktur':
    {?>
        <div class="row">
            <div class="col-md-5">
                <?php 
                echo form_open($uri);
                echo form_label('PILIH TANGGAL : ');?>
                <div class="form-inline">
                <?php
                echo form_input('tanggal','','id="datepicker" class="form-control"');
                echo form_submit('submit',"SUBMIT","class='btn btn-primary'");
                echo form_close();
                ?>
                </div>
            </div>
        </div>
        <?php
        echo br(2);
        echo $pagination;
        echo $query;
        echo $pagination;
    }break;
    case 'pilih':
    {
        echo form_open($uri);
        echo form_label('CLIENT : ');
        $options=array();
        foreach ($client->result() as $row)
        {
            $options[$row->grup_lang]=$row->grup_nama;
        }
        echo form_dropdown('client',$options);
        echo form_submit('submit','PILIH').br(2);
        echo form_close();
        echo isset($err)? 'WARNING : '.$err:'';
    }break;
    case 'print_rekap_dialog':
    {
        ?>
        <div class="form-inline">
        <?php
        echo form_open($uri);
        echo form_label('Start Date');
        echo form_input('startdate','','id=datepicker class=form-control');
        echo form_label(' - End Date');
        echo form_input('enddate','','id=datepicker2 class="form-control"');
        echo form_submit('submit','Download Excel','class="btn btn-info"');
        echo form_close();
        ?>
        </div>
        <?php
    }break;
    case 'show_add':
    {
        if(isset($kode))
        {
            echo form_open($uri2);
            
            $fakturdd=array();
            foreach ($kode->result() as $row)
            {
                    $fakturdd[$row->nodokjdi]=$row->nomor;
                    //$fakturdd[((int)$row->nodokjdi).'/MPM/'.$row->substr($row->tgldokjdi,5,2).$row->substr($row->tgldokjdi,2,2)]=((int)$row->nodokjdi).'/MPM/'.$row->substr($row->tgldokjdi,5,2).$row->substr($row->tgldokjdi,2,2);
            }
            echo $lang;
        
        ?>
        <div class='row'>
        <div class='col-md-5'>
        <table class="table">
            <tr>
                <th>Faktur No.</th><th align="center">Keterangan</th><th align="center">Tambah</th>
            </tr>
            <tr><td>
            <?php
            echo form_dropdown('faktur',$fakturdd,'','class="form-control"');
            ?>
            </td>
             <td>
            <?php 
            $ketdd=array('Faktur Lunas'=>'Faktur Lunas','Copy Faktur'=>'Copy Faktur');
            echo form_dropdown('keterangan',$ketdd,'','class="form-control"')?>
            </td>
            <td>
            <?php echo form_submit('submit','Tambah','class="btn btn-info"')?>
            </td>
            </tr>
        </table>
        </div></div>
        
<?php
            if($query)
            {
                echo form_close();
                ?>
                <div class='row'>
                    <div class='col-md-6'>
                    <?php echo $query;?>
                    </div>
                </div>
                <div class='row'>
                    <div class='col-md-5'>
                        <div class='form-inline'>
                            <?php
                            echo form_open($uri3);
                            echo form_label('Tanggal : ','datepicker');
                            echo form_input('tanggal','','id="datepicker" class="form-control"');
                            echo form_submit('submit','SAVE','class="form-control"');
                            echo form_close();?>
                        </div>
                   </div>
                </div>
<?php
            }
        }
    }break;
}
?>
