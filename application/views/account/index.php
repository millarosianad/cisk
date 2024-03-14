<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <title>Document</title>
    <script>
    /* confirmation dialog before submit delete request */
    function delete_confirm(){
        if($('.checkbox:checked').length > 0){
            var result = confirm("Are you sure to delete selected users ? ");
            if(result){
                return true;
            }else{
                return false;
            }
        }else{
            alert('Select at least 1 record to delete');
            return false;
        }
    }

    /* Select/Deselect all checkboxes functionality */
    $(document).ready(function(){
        $('#select_all').on('click', function(){
            if($this.checked){
                $('.checkbox').each(function(){
                    this.checked = true;
                });
            }else{
                $('.checkbox').each(function(){
                    this.checked = false;
                });
            }
        });

        $('.checkbox').on('click',function(){
            if($'.checkbox:checked').length == $('.checkbox').length){
                $('#select_all').prop('checked',true);
            }else{
                $('#select_all').prop('checked',false);
            }
        });

    });
    </script>

</head>
<body>
<div class="container">
    <h1>Delete Multiple Rows</h1>
    
    <?php 
        if(!empty($statusMsg)){
            ?>
            <div class="alert alert-success"><?php echo $statusMsg; ?></div>
    <?php } ?>

    <!-- Users data list -->
    <form name="bulk_action_form" action="" method="post" onSubmit="return delete_confirm();"/>
        <table class="bordered">
            <thead>
            <tr>
                <th><input type="checkbox" id="select_all" value=""/>
                <th>First name</th>
                <th>Last Name</th>
                <th>Email</th>
                <th>Phone</th>
            </tr>
            </thead>
            <tbody>
            <?php if(!empty($users)){ foreach($users as $row){ ?>
            <tr>
                <td align="center"><input type="checkbox" name="checked_id[]" class="checkbox" value="<?php echo $row['id']; ?>"></td>
                <td><?php echo $row['first_name']; ?></td>
                <td><?php echo $row['last_name']; ?></td>
                <td><?php echo $row['email']; ?></td>
                <td><?php echo $row['phone']; ?></td>            
            </tr>
             <?php } } else{ ?>
                <tr><td colspan ="5"> No records found</td></tr>
             <?php } ?>
            </tbody>
        
        </table>    

        <input type="submit" class="btn btn-danger" name="bulk_delete_submit" value="DELETE"/>


</div>

    
</body>
</html>