<script type="text/javascript" src="<?php echo base_url()."assets/js/select.js"?>"></script>

<?php echo form_open($url);?>
<h2>
<?php echo form_label($page_title);?>
</h2><hr />
<div class='row'>
    <div class="col-md-3">
        <div class="form-group">
            <?php
                echo form_label("Kategori : ");
                foreach($query->result() as $value)
                {
                    $kategori[$value->id_kategori]= $value->nama_kategori;
                }
                echo form_dropdown('id_kategori',$kategori,'','class="form-control" id="kategori"');
            ?>
        </div>
    </div>
    <div class="col-md-2">
        <div class="form-group">
            <?php
                echo form_label("Tahun : ");
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

<div class="row">
    <div class="col-md-4">
        <div class="form-group">
             <?php
                echo form_label("Wilayah : ");
                foreach($query2->result() as $value)
                {
                    $wilayah[$value->no_wilayah]= $value->nama_wilayah;
                }
                echo form_dropdown('no_wilayah',$wilayah,'','class="form-control" id="wilayah"');
            ?>
        </div>
    </div>     
</div>

    <div class="col-md-2">
        <div class="form-group">
            <?php echo br().form_submit('submit','Proses','onclick="return ValidateCompare();" class="btn btn-primary"');?>    
        </div>
    </div>    
</div>

<?php echo form_close();?>