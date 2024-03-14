<div class="col-md-6">

<?php
switch($state)
{
    case 'show':
    {
        echo br(2);
        ?>
        
        <div class="row">
            <div class='col-md-4'>
                <?php echo $image_add;?>
            </div>
            <div class='col-md-4'>
                <?php echo $image_upload_up;?>
            </div>
            <div class='col-md-4'>
                <?php echo $image_download_down;?>
            </div>
        </div>
        <?php echo br(2).$query;
    }break;
    case 'download_dialog':
    {
        echo br(2);
        echo form_open($uri);
        ?>
        <table class='table'>
             <tr>
                <td class="col-md-5"><?php echo form_label('Site ID','class="form-control"');?></td>
                <td class="col-md-5">
                    <?php 
                        $dd=array();
                        foreach($siteid->result() as $row)
                        {
                            $dd[$row->siteid] = $row->wilayah;
                        }
                        echo form_dropdown('siteid',$dd,'','class="form-control"')
                    ;?>
                </td>
                <td>
                    <?php echo form_submit('pilih','PILIH','class="btn btn-info"')?>
                </td>    
            </tr>
        </table>
        <?php    
        echo form_close();
    }break;
    case 'preadd':
    {
        echo br(2);
        echo form_open($uri);
        ?>

        <table class='table'>
             <tr>
                <td class="col-md-5"><?php echo form_label('Site ID','class="form-control"');?></td>
                <td class="col-md-5">
                    <?php 
                        $dd=array();
                        foreach($siteid->result() as $row)
                        {
                            $dd[$row->siteid] = $row->wilayah;
                        }
                        echo form_dropdown('siteid',$dd,'','class="form-control"')
                    ;?>
                </td>
                <td>
                    <?php echo form_submit('pilih','PILIH','class="btn btn-info"')?>
                </td>    
            </tr>
        </table>
        <?php    
        echo form_close();
    }break;
    case 'add':
    {
        ?>
        <script type="text/javascript">       
            $(document).ready(function() { 
                $("#dp").change(function(){
                             /*dropdown post */
                    $.ajax({
                    url:"<?php echo base_url(); ?>salesman/admin/buildSalesName",    
                    data: {id: $(this).val()},
                    type: "POST",
                    success: function(data){
                        $("#salesman").html(data);
                        }
                    });
                });
            });

        </script>
        <?php
        echo br(2);
        echo form_open($uri2);
        ?>
        <table class='table'>
            <tr>
                <td class="col-md-5"><?php echo form_label('Username','class="form-control"');?></td>
                <td class="col-md-5"><?php echo form_input('username','','class="form-control"');?></td>
                <?php echo $error_message;?>
            </tr>
            <tr>
                <td class="col-md-5"><?php echo form_label('Password','class="form-control"');?></td>
                <td class="col-md-5"><?php echo form_input('password','','class="form-control"');?></td>
            </tr>
             <tr>
                <td class="col-md-5"><?php echo form_label('Site ID','class="form-control"');?></td>
                <td class="col-md-5">
                    <?php 
                        $dd=array();
                        foreach($siteid->result() as $row)
                        {
                            $dd[$row->siteid] = $row->wilayah;
                        }
                        echo form_dropdown('siteid',$dd,'','class="form-control" id="dp"')
                    ;?>
                </td>
            </tr>
            <tr>
                <td class="col-md-5"><?php echo form_label('Salesman','class="form-control"');?></td>
                <td class="col-md-5">
                   <?php 
                        $options=array();
                        $options['0']='Select Site id First';
                        //$options['1']='UNDER CONSTRUCTIOM';
                        echo form_dropdown('kode_sales',$options,'','class="form-control" id="salesman"');
                        //echo form_hidden('siteid',$site);
                    ?>
                </td>
            </tr>
            <tr>
                <td colspan="2"><?php echo form_submit('save','SAVE','class="btn btn-info"')?></td>
            </tr>
            
        </table>
        
        <?php
        echo form_close();
    }break;
    case 'edit':
    {
         ?>
        <script type="text/javascript">       
            $(document).ready(function() { 
                $("#dp").change(function(){
                             /*dropdown post */
                    $.ajax({
                    url:"<?php echo base_url(); ?>salesman/admin/buildSalesName",    
                    data: {id: $(this).val()},
                    type: "POST",
                    success: function(data){
                        $("#salesman").html(data);
                        }
                    });
                });
            });

        </script>
        <?php
        echo br(2);
        echo form_open($uri);
        ?>
        <table class='table'>
            <tr>
                <td class="col-md-5"><?php echo form_label('Username','class="form-control"');?></td>
                <td class="col-md-5"><?php echo form_input('username',$row->username,'class="form-control" disabled');?></td>
            </tr>
            <tr>
                <td class="col-md-5"><?php echo form_label('Password','class="form-control"');?></td>
                <td class="col-md-5"><?php echo form_input('password',$row->password,'class="form-control"');?></td>
            </tr>
            <tr>
                <td class="col-md-5"><?php echo form_label('Site ID','class="form-control"');?></td>
                <td class="col-md-5">
                    <?php 
                        $dd=array();
                        foreach($siteid->result() as $row)
                        {
                            $dd[$row->siteid] = $row->wilayah;
                        }
                        echo form_dropdown('siteid',$dd,'','class="form-control" id="dp"')
                    ;?>
                </td>
            </tr>
            <tr>
                <td class="col-md-5"><?php echo form_label('Salesman','class="form-control"');?></td>
                <td class="col-md-5">
                   <?php 
                        $dd=array();
                        $options=array();
                        $options['0']='Select Site id First';
                        //$options['1']='UNDER CONSTRUCTIOM';
                        echo form_dropdown('kode_sales',$options,'','class="form-control" id="salesman"');
                       
                    ?>
                </td>
            </tr>
            <tr>
                <td colspan="2"><?php echo form_submit('save','SAVE','class="btn btn-info"')?></td>
            </tr>
            
        </table>
        
        <?php
        echo form_close();
    }break;
}
?>
</div>
