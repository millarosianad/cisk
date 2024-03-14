<!doctype html>
<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title>MPM</title>
        <script type="text/javascript" language="javascript" src="//code.jquery.com/jquery-1.10.2.min.js"></script>
        
        <!-- Load Datatables dan Bootstrap dari CDN -->
        <script type="text/javascript" language="javascript" src="//cdn.datatables.net/1.10.4/js/jquery.dataTables.min.js"></script>
        <script type="text/javascript" language="javascript" src="//cdn.datatables.net/plug-ins/9dcbecd42ad/integration/bootstrap/3/dataTables.bootstrap.js"></script>
        
        <link rel="stylesheet" type="text/css" href="//netdna.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
        <link rel="stylesheet" type="text/css" href="//cdn.datatables.net/plug-ins/9dcbecd42ad/integration/bootstrap/3/dataTables.bootstrap.css">
        <script type="text/javascript" src="<?php echo base_url()."assets/js/select.js"?>"></script>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js"></script>    
</head>
<body><br>
<div class="row">
<div class="col-xs-12">
  <div>
  <div class="col-xs-12">
  <h4>Input Target</h4>
  <div><a href="<?php echo base_url()."target"; ?>  " class="btn btn-default" role="button"><span class="glyphicon glyphicon-back" aria-hidden="true"></span> kembali</a>
  
 <a href="<?php echo base_url()."target/export/". $tahun."/". $bulan."/"; ?>  " class="btn btn-warning" role="button"><span class="glyphicon glyphicon-print" aria-hidden="true"></span> export target (excel)</a>
        
</div>
<hr>
<div class="row"> 
        <div class="col-xs-12">
            <div class="col-xs-12">
            <table id="myTable" class="table table-striped table-bordered table-hover">     
                    <thead>
                        <tr>                
                          <th>Sub Branch</th>
                          <th>Tahun</th>
                          <th>Bulan</th>            
                          <th>herbal</th>
                          <th>candy</th>
                          <th>deltomed</th>
                          <th>marguna</th>
                          <th>us</th>
                          <th>natura</th>
                          <th>intrafood</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                    </table>
              </div>
        </div>
  </div>

</body>
</html>

<script type="text/javascript" language="javascript" >
$(document).ready(function(){
  
  function load_data()
  {
    $.ajax({
      url:"<?php echo base_url(); ?>target/load_data/<?php echo $tahun ?>/<?php echo $bulan ?>",
      dataType:"JSON",
      success:function(data){
        var html = '<tr>';
        for(var count = 0; count < data.length; count++)
        {
          html += '<tr>';
          html += '<td class="table_data" data-row_id="'+data[count].id+'" data-column_name="nama_comp" >'+data[count].nama_comp+'</td>';
          html += '<td class="table_data" data-row_id="'+data[count].id+'" data-column_name="tahun" >'+data[count].tahun+'</td>';
          html += '<td class="table_data" data-row_id="'+data[count].id+'" data-column_name="bulan">'+data[count].bulan+'</td>';
          html += '<td class="table_data" data-row_id="'+data[count].id+'" data-column_name="herbal" contenteditable>'+data[count].herbal+'</td>';
          html += '<td class="table_data" data-row_id="'+data[count].id+'" data-column_name="candy" contenteditable>'+data[count].candy+'</td>';
          html += '<td class="table_data" data-row_id="'+data[count].id+'" data-column_name="deltomed">'+data[count].deltomed+'</td>';
          html += '<td class="table_data" data-row_id="'+data[count].id+'" data-column_name="marguna" contenteditable>'+data[count].marguna+'</td>';
          html += '<td class="table_data" data-row_id="'+data[count].id+'" data-column_name="us" contenteditable>'+data[count].us+'</td>';
          html += '<td class="table_data" data-row_id="'+data[count].id+'" data-column_name="natura" contenteditable>'+data[count].natura+'</td>';
          html += '<td class="table_data" data-row_id="'+data[count].id+'" data-column_name="intrafood" contenteditable>'+data[count].intrafood+'</td>';
          }
        $('tbody').html(html);
      }
    });
  }

  load_data();

  $(document).on('click', '#btn_add', function(){
    var first_name = $('#first_name').text();
    var last_name = $('#last_name').text();
    var age = $('#age').text();
    if(first_name == '')
    {
      alert('Enter First Name');
      return false;
    }
    if(last_name == '')
    {
      alert('Enter Last Name');
      return false;
    }
    $.ajax({
      url:"<?php echo base_url(); ?>target/insert",
      method:"POST",
      data:{first_name:first_name, last_name:last_name, age:age},
      success:function(data){
        load_data();
      }
    })
  });

  $(document).on('blur', '.table_data', function(){
    var id = $(this).data('row_id');
    var table_column = $(this).data('column_name');
    var value = $(this).text();
    $.ajax({
      url:"<?php echo base_url(); ?>target/update",
      method:"POST",
      data:{id:id, table_column:table_column, value:value},
      success:function(data)
      {
        load_data();
      }
    })
  });

  $(document).on('click', '.btn_delete', function(){
    var id = $(this).attr('id');
    if(confirm("Are you sure you want to delete this?"))
    {
      $.ajax({
        url:"<?php echo base_url(); ?>target/delete",
        method:"POST",
        data:{id:id},
        success:function(data){
          load_data();
        }
      })
    }
  });
  
});
</script>
