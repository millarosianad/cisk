
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
               Product : {field:'produk', sort:"asc", showAll:false, aggregateType:"distinct" },
               Type : {field:'tipe', sort:"asc", showAll:true, aggregateType:"distinct" },            
               Cabang :{field:'cabang', sort:"asc", showAll:true, aggregateType:"distinct"},
               month :{field:'bulan', sort:"asc", showAll:true, aggregateType:"distinct"},
                                           
               unit: { field:'unit',agregateType: "sum", groupType:"none",formatter:"default"},
               value: { field:'value',agregateType: "sum", groupType:"none" }
           
           
            },
            //xfields: ["Cabang"],--suffy edit request by lita
            xfields: ["Cabang"],
            yfields: ['month'],
            zfields: ['value'],
            copyright:false,
            summary:true,
            data: <?php echo $query;?>
          });
        });
      </script>