<html>
<head>
<title>Detail Product</title>
<link rel="stylesheet" type="text/css" href=" <?php echo base_url()."" ?>assets/uploads/popup.css">
<!-- Load Datatables dan Bootstrap dari CDN -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js"></script>
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" />  
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script> 
</head>
<body>
<div class="popup-wrapper" id="popup">
<div class="popup-container">
<!-- Isi Popup -->

        <form method="post" id="update_form">
            <div align="left">
                <input type="submit" name="multiple_update" id="multiple_update" class="btn btn-info" value="Terima" />
            
            </div>
            <br />
            <div class="table-responsive">
                <table class="table table-bordered table-striped">
                    <thead>
                    <th width="5%"></th>
                    <th width="20%">Name</th>
                    <th width="30%">Address</th>
                    <th width="15%">Gender</th>
                    <th width="20%">Designation</th>
                    <th width="10%">Age</th>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </form>










<a class="popup-close" href="<?php echo base_url() ?>all_po/view_po">X</a>
</div>
</div>






</body>
</html>

 
<script>  
$(document).ready(function(){  
    
    function fetch_data()
    {
        $.ajax({
            url:"<?php echo base_url('all_po/json') ?>",
            method:"POST",
            dataType:"json",
            success:function(data)
            {
                var html = '';
                for(var count = 0; count < data.length; count++)
                {
                    html += '<tr>';
                    html += '<td><input type="checkbox" id="'+data[count].id+'" data-name="'+data[count].name+'" data-address="'+data[count].address+'" data-gender="'+data[count].gender+'" data-designation="'+data[count].designation+'" data-age="'+data[count].age+'" class="check_box"  /></td>';
                    html += '<td>'+data[count].name+'</td>';
                    html += '<td>'+data[count].address+'</td>';
                    html += '<td>'+data[count].gender+'</td>';
                    html += '<td>'+data[count].designation+'</td>';
                    html += '<td>'+data[count].age+'</td></tr>';
                }
                $('tbody').html(html);
            }
        });
    }

    fetch_data();

    $(document).on('click', '.check_box', function(){
        var html = '';
        if(this.checked)
        {
            html = '<td><input type="checkbox" id="'+$(this).attr('id')+'" data-name="'+$(this).data('name')+'" data-address="'+$(this).data('address')+'" data-gender="'+$(this).data('gender')+'" data-designation="'+$(this).data('designation')+'" data-age="'+$(this).data('age')+'" class="check_box" checked /></td>';
            html += '<td><input type="text" name="name[]" class="form-control" value="'+$(this).data("name")+'" /></td>';
            html += '<td><input type="text" name="address[]" class="form-control" value="'+$(this).data("address")+'" /></td>';
            html += '<td><select name="gender[]" id="gender_'+$(this).attr('id')+'" class="form-control"><option value="Male">Male</option><option value="Female">Female</option></select></td>';
            html += '<td><input type="text" name="designation[]" class="form-control" value="'+$(this).data("designation")+'" /></td>';
            html += '<td><input type="text" name="age[]" class="form-control" value="'+$(this).data("age")+'" /><input type="hidden" name="hidden_id[]" value="'+$(this).attr('id')+'" /></td>';
        }
        else
        {
            html = '<td><input type="checkbox" id="'+$(this).attr('id')+'" data-name="'+$(this).data('name')+'" data-address="'+$(this).data('address')+'" data-gender="'+$(this).data('gender')+'" data-designation="'+$(this).data('designation')+'" data-age="'+$(this).data('age')+'" class="check_box" /></td>';
            html += '<td>'+$(this).data('name')+'</td>';
            html += '<td>'+$(this).data('address')+'</td>';
            html += '<td>'+$(this).data('gender')+'</td>';
            html += '<td>'+$(this).data('designation')+'</td>';
            html += '<td>'+$(this).data('age')+'</td>';            
        }
        $(this).closest('tr').html(html);
        $('#gender_'+$(this).attr('id')+'').val($(this).data('gender'));
    });

    $('#update_form').on('submit', function(event){
        event.preventDefault();
        if($('.check_box:checked').length > 0)
        {
            $.ajax({
                url:"multiple_update.php",
                method:"POST",
                data:$(this).serialize(),
                success:function()
                {
                    alert('Data Updated');
                    fetch_data();
                }
            })
        }
    });

});  
</script>