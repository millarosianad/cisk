<?php echo br(5)?>
<div class="col-md-offset-4">
    <div class="col-md-5">
        <?php echo form_open($url);?>
        <h2><?php echo form_label($page_title);?></h2>
        <div class="form-group">
        <?php echo form_label(" Year : ");
            //$options = array(date('Y')-1=>date('Y')-1,date('Y')=>date('Y'));
            $interval=date('Y')-2010;
            $options=array();
            $options['2010']='2010';
            for($i=1;$i<=$interval;$i++)
            {
                $options[''.$i+2010]=''.$i+2010;
            }
            
            echo form_dropdown('year', $options,date('Y'),"class='form-control'");
            ?>
            </div>
            <div class="form-group">
                <?php
                    echo form_label(" Format : ");
                    $options=array();
                    $options['1']='MONITOR';
                    $options['6']='PETA';
                    $options['2']='PDF';
                    $options['3']='EXCEL';
                    $options['4']='GRAFIK';
                    $options['5']='RETUR';
                    echo form_dropdown('format', $options, 'MONITOR',"class='form-control'");
                ?>
            </div>
            <div class="form-group">
                <?php
                    echo form_label(" SUPPLIER : ");
                    $options=array();
                    foreach($query->result() as $value)
                    {
                        $options[$value->supp]= $value->namasupp;
                    }
                    echo form_dropdown('supp', $options, 'All',"class='form-control'");
                ?>
            </div>


        <?php echo form_submit('submit','Proses','class="btn btn-primary"');?>
        <?php echo form_close();?>
        </div>
    </div>
</div>


