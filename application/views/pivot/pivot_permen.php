
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
               Product : {field:'nama_barang', sort:"asc", showAll:false, aggregateType:"distinct" },
               Group_Product : {field:'group_barang', sort:"asc", showAll:true, aggregateType:"distinct" },            
               Kota :{field:'kota', sort:"asc", showAll:true, aggregateType:"distinct"},
               Area :{field:'areajual_name', sort:"asc", showAll:true, aggregateType:"distinct"},
               Bulan :{field:'month', sort:"asc", showAll:true, aggregateType:"distinct"},
               Tipe_jual : {field:'tipe_jual', sort:"asc", showAll:false, aggregateType:"distinct"},
               Tipe_trans : {field : 'tipe_trans',sort:'asc',showAll:false,aggregateType:"distict"},
               Customer : {field : 'cust_name',sort:'asc',showAll:false,aggregateType:"distict"},
               
                         
               count: { field:'ot',agregateType: "sum",groupType:"none" },
               unit: { field:'unit',agregateType: "sum", groupType:"none",formatter:"default"},
               value: { field:'value',agregateType: "sum", groupType:"none" }
           
           
            },
            xfields: ["Product"],
            yfields: ['Bulan'],
            zfields: ['unit'],
            copyright:false,
            summary:true,
            data: <?php echo $query3;?>
          });
        });
      </script>