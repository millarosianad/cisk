
<script type="text/javascript" src="<?php echo base_url()."assets/js/jbpivot.min.js"?>"></script>
 <?php echo link_tag('assets/css/jbpivot.min.css');
 echo br(2);   
 ?>
<div class='scroll'>
        <div id="pivot1"></div>
        </div>

        <script type="text/javascript">
        $(document).ready(function(){
          $("#pivot1").jbPivot({
             fields: {
               Pelanggan: {field:'pelanggan', sort:"asc", showAll:false, aggregateType:"distinct" },
               Alamat :{field:'alamat', sort:"asc", showAll:true, aggregateType:"distinct"},
               Minggu : {field:'minggu', sort:"asc", showAll:false, aggregateType:"distinct"},
               Hari : {field:'hari', sort:"asc", showAll:false, aggregateType:"distinct"},
               
               Count: { agregateType: "count", groupType:"none" }
             
            },
            xfields: ['Pelanggan','Minggu'],
            yfields: ['Hari'],
            zfields: ['Count'],
            copyright:false,
            summary:true,
            formatter:'default',
            data: <?php echo $query;?>
          });
        });
      </script>