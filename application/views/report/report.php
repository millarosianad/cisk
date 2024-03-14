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
            $options=array();
            $options['dp']="DISTRIBUTOR PELAKSANA (DP)";
            $options['bsp']="BSP (SEMUA WILAYAH)";
            $options['permen']="DISTRIBUTOR PERMEN";
            echo form_label("KATEGORI : ");
            echo form_dropdown('pilih', $options,'dp','class="form-control"');
            echo br();
            
            echo form_label(" TAHUN : ");
            $interval=date('Y')-2010;
            $year=array();
            $year['2010']='2010';
            for($i=1;$i<=$interval;$i++)
            {
                $year[''.$i+2010]=''.$i+2010;
            }
            //$options = array(date('Y')-1=>date('Y')-1,date('Y')=>date('Y'));
            echo form_dropdown('year', $year, date('Y'),'class="form-control"');
            echo br();            
            echo form_label("WILAYAH : ");
            $options=array();
            $options['0']='SEMUA WILAYAH';
            $options['1']='WILAYAH SUMATERA';
            $options['2']='JABODETABEKSER';
            $options['3']='WILAYAH JAWA BARAT';
            $options['4']='WILAYAH JAWA TENGAH';
            $options['5']='WILAYAH JAWA TIMUR';
            $options['6']='BALI, LOMBOK, KALIMANTAN, SULAWESI';
            $options['7']='WILAYAH BARAT';
            $options['8']='WILAYAH TIMUR';
            echo form_dropdown('wilayah', $options, "1",'class="form-control"');
            echo br();
            echo form_submit('submit','SUBMIT','class="btn btn-primary"');
            echo form_close();
         ?>
             
            </div>
             </div>
                </div>
    <?php 
    }break;
    case 'bsp':
    {
        echo br(2);
        ?>
        <div class='scroll'>
        <div id="pivot1"></div>
        </div>

        <script type="text/javascript">
        $(document).ready(function(){
          $("#pivot1").jbPivot({
             fields: {
               Produk : {field:'produk', sort:"asc", showAll:true, aggregateType:"distinct" },
               Tipe : {field:'tipe', sort:"asc", showAll:true, aggregateType:"distinct" },            
               Cabang :{field:'cabang', sort:"asc", showAll:true, aggregateType:"distinct"},
               Bulan :{field:'bulan', sort:"asc", showAll:true, aggregateType:"distinct"},
              
             
                            
               count:{ field:'ot', agregateType:"count", groupType:"distinct" },
               Unit: { field:'unit',agregateType: "sum", groupType:"none"},
               Value: { field:'value',agregateType: "sum", groupType:"none" }
           
           
            },
            xfields: ["Cabang"],
            yfields: ['Produk'],
            zfields: ['Unit'],
            copyright:false,
            summary:true,
            data: <?php echo $query;?>
          });
        });
      </script>
    <?php
    }break;
    case 'dp':
    {
        echo br(2);
        
        echo link_tag('assets/css/jbpivot.min.css');
        ?>
        <script type="text/javascript" src="<?php echo base_url()."assets/js/jbpivot.min.js"?>"></script>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.8/jquery.min.js"></script>
        <script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.9.1/jquery-ui.min.js"></script>
        <div class='scroll'>
        <div id="pivot1"></div>
        </div>
      
        <script type="text/javascript">
        $(document).ready(function(){
          $("#pivot1").jbPivot({
             fields: {
               Product : {field:'namaprod', sort:"asc", showAll:false, aggregateType:"distinct" },
               GroupProduct : {field:'grupprod', sort:"asc", showAll:false, aggregateType:"distinct" },            
               DP :{field:'nama_comp', sort:"asc", showAll:false, aggregateType:"distinct"},
               month :{field:'month', sort:"asc", showAll:false, aggregateType:"distinct"},
               Supplier : {field:'namasupp', sort:"asc", showAll:false, aggregateType:"distinct"},
             
                            
               count: { agregateType: "count", groupType:"none" },
               unit: { field:'unit',agregateType: "sum", groupType:"none",formatter:$.number("unit")},
               value: { field:'value',agregateType: "sum", groupType:"none" }
           
           
            },
            xfields: ["DP"],
            yfields: ['Product'],
            zfields: ['unit'],
            copyright:false,
            summary:true,
            data: <?php echo $query;?>
          });
        });
      </script>
    <?php
    }
    break;
    
}
?>



      