
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
               Product : {field:'namaprod', sort:"asc", showAll:false, aggregateType:"distinct" },
               GroupProduct : {field:'grupprod', sort:"asc", showAll:true, aggregateType:"distinct" },            
               DP :{field:'nama_comp', sort:"asc", showAll:true, aggregateType:"distinct"},
               month :{field:'month', sort:"asc", showAll:true, aggregateType:"distinct"},
               Supplier : {field:'namasupp', sort:"asc", showAll:false, aggregateType:"distinct"},
               Target : {field : 'target',sort:'asc',showAll:false,aggregateType:"distict"},
                            
               count: { field:'ot',agregateType: "sum",groupType:"none" },
               unit: { field:'unit',agregateType: "sum", groupType:"none",formatter:"default"},
               value: { field:'value',agregateType: "sum", groupType:"none" }
           
           
            },
            xfields: ["DP"],
            yfields: ['month'],
            zfields: ['unit'],
            copyright:false,
            summary:true,
            data: <?php echo $query;?>
          });
        });
      </script>