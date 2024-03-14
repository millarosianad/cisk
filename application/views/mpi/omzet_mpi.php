<script src="<?php echo base_url() ?>assets/js/script.js"></script>
<?php echo form_open($url);?>
        
 
    <div class="row">        
        <div class="col-xs-11">
            <?php echo br(3); ?>
            <h3><?php echo $page_title; ?></h3><hr />
        </div>
    </div>

    <div class="col-xs-2">
        Tahun
    </div>
    <div class="col-xs-5">
    
    <?php 
        $tahun = array(
            '2022'  => '2022',     
            '2021'  => '2021'        
            );
    ?>
       <?php echo form_dropdown('tahun', $tahun,'','class="form-control"');?>
    </div>

    <div class="col-xs-11">
        &nbsp;
    </div>

    <div class="col-xs-2">
        Unit / Value
    </div>
    <div class="col-xs-5">
    
    <?php 
        $uv = array(
            '1'  => 'Unit',
            '2'  => 'Value',          
            );
    ?>
       <?php echo form_dropdown('uv', $uv,'','class="form-control"');?>
    </div>

    <div class="col-xs-11">
        &nbsp;
    </div>

    <div class="col-xs-2">
        Group By
    </div>
    <div class="col-xs-3">
    
    <?php 
        $group_by = array(
            '1'  => 'Kode Cabang',
            '2'  => 'Kode Produk',
            '3'  => 'Kode Cabang dan Kode Produk ',          
            );
    ?>
       <?php echo form_dropdown('group_by', $group_by,'','class="form-control"');?>
    </div>

    <div class="col-xs-11">
        &nbsp;
    </div>

    <div class="col-xs-2">
        Tipe Outlet
    </div>
    <div class="col-xs-8">

    <div class="row">
        <div class="col-xs-10">
            <label class="fancy-checkbox">
                <input type="checkbox" name="apotik" value="1"><span> Apotik (tanpa kimia farma)</span> &nbsp;</span>
            </label>
        </div>

        <div class="col-xs-10">
            <label class="fancy-checkbox">
                <input type="checkbox" name="kimia_farma" value="1"><span> Apotik Kimia Farma</span> &nbsp;</span>
            </label>
        </div> 

        <!-- <div class="col-xs-10">
            <label class="fancy-checkbox">
                <input type="checkbox" name="pbf" value="1"><span> PBF (tanpa kimia farma)</span> &nbsp;</span>
            </label>
        </div>
        
        <div class="col-xs-10">
            <label class="fancy-checkbox">
                <input type="checkbox" name="pbf_kimia_farma" value="1"><span> PBF Kimia Farma</span> &nbsp;</span>
            </label>
        </div>  -->

        

    </div>

    <div class="row">

        <!-- <div class="col-xs-10">
            <label class="fancy-checkbox">
                <input type="checkbox" name="minimarket" value="1"><span> MINIMARKET</span> &nbsp;</span>
            </label>
        </div>
       
        <div class="col-xs-10">
            <label class="fancy-checkbox">
                <input type="checkbox" name="pd" value="1"><span> P&D</span> &nbsp;</span>
            </label>
        </div> -->

        
    </div>

    <div class="row">

        <!-- <div class="col-xs-10">
            <label class="fancy-checkbox">
                <input type="checkbox" name="rs" value="1"><span> RS SWASTA</span> &nbsp;</span>
            </label>
        </div> 
       
        <div class="col-xs-10">
            <label class="fancy-checkbox">
                <input type="checkbox" name="supermarket" value="1"><span> SUPERMARKET</span> &nbsp;</span>
            </label>
        </div>

        <div class="col-xs-10">
            <label class="fancy-checkbox">
                <input type="checkbox" name="tokoobat" value="1"><span> TOKO OBAT</span> &nbsp;</span>
            </label>
        </div> -->

    </div>

    
    
    
    </div>

    <div class="col-xs-11">
        &nbsp;
    </div>



    <div class="col-xs-2">
        &nbsp;
    </div>

    <div class="col-xs-3">  
        <?php echo form_submit('submit','Proses','class="btn btn-primary"');?>
        <?php echo form_close();?>
    </div>

    <div class="col-xs-11">
        &nbsp;
    </div>

    <div class="col-xs-11">  
    <pre>
Catatan
- klsout (kelas outlet) MPI terdiri dari : APOTIK, MINIMARKET, P&D, PBF, RS PEMERINTAH, RS SWASTA, SUPERMARKET, TOKO OBAT

- Centang Apotik (tanpa kimia farma) artinya : menampilkan semua sales dengan kondisi "klsout = apotik" dan "namalang <b><u>tidak</u></b> mengandung kata kimia farma"  
- Centang Apotik Kimia Farma artinya : menampilkan semua sales dengan kondisi "klsout = apotik" dan "namalang yang didalamnya mengandung kata kimia farma"  
- Centang keduanya artinya : menampilkan semua sales dengan kondisi "klsout = apotik"
- Tidak Centang keduanya artinya : menampilkan semua sales dengan tidak memperhatikan kondisi apapun
  
</pre>
    </div>