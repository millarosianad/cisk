<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.4/jquery.min.js"></script>
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8/jquery-ui.min.js"></script>
        <script type="text/javascript">
            $(document).ready(function() { 
                $("#x").change(function(){
                    //alert('daddadad');
                     /*dropdown post */
                    //alert($(this).val());
                    $.ajax({
                    url:"http://http://180.242.158.165/salesman/admin/add_ajax",    
                    data: {siteid: $(this).val()},
                    type: "POST",
                    success: function(data){
                        $("#y").value(data);
                        alert('sukses');
                    },
                    error:function(data){
                        alert('gagal');
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
                <td class="col-md-5"><?php echo form_input('username','','class="form-control"');?></td>
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
                        echo form_dropdown('kode_sales',$dd,'','id="x" class="form-control"')
                    ;?>
                </td>
            </tr>
            <tr>
                <td class="col-md-5"><?php echo form_label('Salesman','class="form-control"');?></td>
                <td class="col-md-5">
                    <select name="kode_sales" id="y" class='form-control'>
                    <option value="">Select</option>
                    </select>
                </td>
            </tr>
            <tr>
                <td colspan="2"><?php echo form_button('save','SAVE','class="btn btn-info"')?></td>
            </tr>
            
        </table>
        
        <?php
        echo form_close();
?>
