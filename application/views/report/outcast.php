<?php


switch($state)
{    
    case 'showsales':
    {?>
         <?php echo br(5)?>
            <div class="col-md-offset-4">
                <div class="col-md-5">
                     <div class="form-group">
                    <h2><?php echo form_label($page_title);?></h2>
        
         <?php echo form_open($url);?>
         <?php
            /*$options=array();
            $options['dp']="DISTRIBUTOR PELAKSANA (DP)";
            $options['bsp']="BSP (SEMUA WILAYAH)";
            $options['permen']="DISTRIBUTOR PERMEN";
            echo form_label("KATEGORI : ");
            echo form_dropdown('pilih', $options,'dp','class="form-control"');
            echo br();*/
            
            echo form_label("WILAYAH : ");
            foreach($query->result() as $value)
            {
                $dd[$value->naper]= $value->nama_comp;
            }
  
            echo form_dropdown('wilayah', $dd,"",'class="form-control"');
            echo br();
            
            echo form_label(" TAHUN : ");
            $interval=date('Y')-2010;
            $year=array();
            $year['2010']='2010';
            for($i=1;$i<=$interval;$i++)
            {
                $year[''.$i+2010]=''.$i+2010;
            }
            echo form_dropdown('year', $year, date('Y'),'class="form-control"');
            echo br();            
            //$options = array(date('Y')-1=>date('Y')-1,date('Y')=>date('Y'));
            
            echo form_submit('submit','SUBMIT','class="btn btn-primary"');
            echo form_close();
         ?>
             
            </div>
             </div>
                </div>
    <?php 
    }break;
   
    
}
?>



      