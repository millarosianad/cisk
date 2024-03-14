<script type="text/javascript" src="<?php echo base_url()."assets/js/select.js"?>"></script>


<?php echo form_open($url);?>


<h2>
<?php echo form_label($page_title);?>
</h2>
<hr />

<div class='row'>
    <div class="col-md-5">
        <div class="form-group">
        <font color="red"><i><strong>
            <?php
                echo "faktur tidak ditemukan !!";
            ?></i></strong>
        </font>
        </div>
    </div>   
</div>


<div class='row'>
    <div class="col-md-5">
        <div class="form-group">
            <?php
                echo form_label("Silahkan Pilih Client : ");
                foreach($query->result() as $value)
                {
                    $x[$value->grup_lang]= $value->grup_nama;                                       
                }
                echo form_dropdown('grup_lang',$x,'','class="form-control" id="grup_lang"');
                //echo $x;
            ?>
        </div>
    </div>
    <div class="col-md-3">
        <div class="form-group">
            <?php echo form_label("Tampilkan Jenis Faktur : "); ?>
            <?php $ketdd=array(
                    1=>'Faktur Lunas',
                    0=>'Copy Faktur',
                    2=>'Seluruh Faktur'
                );?>
                <?php echo form_dropdown('keterangan',$ketdd,'','class="form-control"');?>
        </div>
    </div>
    <div class="col-md-2">
        <div class="form-group">
            <?php echo br().form_submit('submit','Proses','onclick="return ValidateCompare();" class="btn btn-primary"');?>    
        </div>
    </div>         
</div>

<?php echo form_close();?>