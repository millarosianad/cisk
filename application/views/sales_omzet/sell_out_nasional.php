<script type="text/javascript">       
    $(document).ready(function() 
    { 
        // console.log('b');
        $("#supp").click(function()
        {
            // console.log('a');
            $.ajax({
            url:"<?php echo base_url(); ?>omzet/buildgroup_target",    
            data: {kode_supp: $(this).val()},
            type: "POST",
            success: function(data){
                $("#group").html(data);
                }
            });
        });
    });            
</script>

<?php 
$supplier=array();
foreach($query->result() as $value)
{
    $supplier[$value->supp]= $value->namasupp;
}    

$group=array();
$group['0']='--';  

echo form_open($url);
?>

<div class="card">

    <div class="col-sm-12">
        <div class="form-group row">
            <label class="col-sm-2 col-form-label">Periode</label>
            <div class="col-sm-9">
                <div class="col-sm-6">
                    <input class="form-control" type="date" name="periode_1" required />
                </div>
            </div>
        </div>
    </div>

    <div class="col-sm-12">
        <div class="form-group row">
            <label class="col-sm-2 col-form-label">Supplier</label>
            <div class="col-sm-9">
                <div class="col-sm-6">
                <?php echo form_dropdown('supp', $supplier,'','class="form-control"  id="supp"');?>
                </div>
            </div>
        </div>
    </div>

    <div class="col-sm-12">
        <div class="form-group row">
            <label class="col-sm-2 col-form-label">Group</label>
            <div class="col-sm-9">
               <div class="col-sm-6">
                <?php  echo form_dropdown('group', $group, 'ALL','class="form-control" id="group"'); ?>
                </div>
            </div>
        </div>
    </div>

    <div class="col-sm-10">
        <div class="form-group row">            
            <div class="col-sm-2"></div>
            <div class="col-sm-5">
                <?php echo form_submit('submit','Proses','class="btn btn-primary"');?>
                <?php echo form_close();?>
            </div>
        </div>
    </div>
</div>
