    <script type="text/javascript" src="<?php echo base_url()."assets/js/select.js"?>"></script>

    <?php echo form_open($url);?>   
    <?php
        //echo form_label(" SUPPLIER : ");
        $supplier=array();
        foreach($query->result() as $value)
        {
            $supplier[$value->supp]= $value->namasupp;
        }
    ?>

    <?php 
        $interval=date('Y')-2019;
        $year=array();
        $year['2020']='2020';
        for($i=1;$i<=$interval;$i++)
        {
            $year[''.$i+2019]=''.$i+2019;
        }
    ?>

    <div class="row">        
        <div class="col-xs-16">            
            <h3><?php echo br(3).' '.$page_title; ?></h3><hr />
        </div>

        <div class="col-md-3">
            <div class="form-group">
            </div>
        </div>
    </div>

        <div class="col-xs-2">
            Tahun (1)
        </div>

        <div class="col-xs-5">
            <?php echo form_dropdown('tahun', $year,'','class="form-control"');?>
        </div>
        <div class="col-xs-11">&nbsp;</div>

        <div class="col-xs-2">
            Bulan (2)
        </div>

        <div class="col-xs-5">
            <?php        
                $bulan=array();
                $bulan['0']='- pilih bulan -';
                $bulan['1']='Januari';
                $bulan['2']='Februari';
                $bulan['3']='Maret';
                $bulan['4']='April';
                $bulan['5']='Mei';
                $bulan['6']='Juni';
                $bulan['7']='Juli';
                $bulan['8']='Agustus';
                $bulan['9']='September';
                $bulan['10']='Oktober';
                $bulan['11']='November';
                $bulan['12']='Desember';
                echo form_dropdown('bulan', $bulan, '0','class="form-control"');
            ?>
        </div>

        <div class="col-xs-11">&nbsp;</div>

        <div class="col-xs-2">
            Supplier (3)
        </div>

        <div class="col-xs-5">
            <?php echo form_dropdown('supp', $supplier,'','class="form-control"  id="supp"');?>
        </div>

        <div class="col-xs-11">&nbsp;</div>

        <div class="col-xs-2">        
            
        </div>
        <div class="col-xs-5">
            <?php echo form_submit('submit','Proses','class="btn btn-primary"');?>
            <?php echo form_close();?>

        </div>
    </div>
