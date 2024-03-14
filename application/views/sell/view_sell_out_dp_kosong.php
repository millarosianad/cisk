<script type="text/javascript" src="<?php echo base_url()."assets/js/select.js"?>"></script>

<script type="text/javascript">
         
            function ValidateCompare(){
                var c=document.getElementsByName("options[]");
                var count=0;
                for(var i=0;i<c.length;i++)
                {
                    if(c[i].checked)
                    {
                        count++;
                    }
                }
                if (count<1) {
                    alert("PILIH PRODUK YANG AKAN DIAMATI.");
                    return false; 
                }
                return true;
                }
</script>
<?php echo form_open($url);?>
<h2>
<?php echo form_label($page_title);?>
</h2><hr />

<div class='row'>
    <div class="col-md-4">
        <div class="form-group">
            <?php
                echo form_label("DP / Sub Branch : ");
                foreach($query2->result() as $value)
                {
                    $dd[$value->naper]= $value->nama_comp;
                                         
                                    }
                echo form_dropdown('nocab',$dd,'','class="form-control" id="dp"');
            ?>
        </div>
    </div>
    <div class="col-md-2">
        <div class="form-group">
            <?php
                echo form_label(" Year / Tahun : ");
                //$options = array(date('Y')-1=>date('Y')-1,date('Y')=>date('Y'));
                $interval=date('Y')-2010;
                $options=array();
                $options['2010']='2010';
                for($i=1;$i<=$interval;$i++)
                {
                    $options[''.$i+2010]=''.$i+2010;
                }
                echo form_dropdown('year', $options, date('Y'),'class="form-control"');
            ?>
        </div>
    </div>
   
     <div class="col-md-2">
        <div class="form-group">
            <?php        
                echo form_label(" UNIT/VALUE : ");
                $options=array();
                $options['0']='UNIT';
                $options['1']='VALUE';
                echo form_dropdown('uv', $options, 'UNIT','class="form-control"');
            ?>
        </div>
    </div>
</div>

<div class='row'>
    <div class="col-md-2">
        <div class="form-group">
            <?php echo br().form_submit('submit','Proses','class="btn btn-primary"');?>    
        </div>
    </div>    
</div><hr>

<?php echo form_close();?>