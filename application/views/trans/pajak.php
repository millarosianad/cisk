<?php
    switch($state)
    {
        case 'upload':
        {
            echo form_open_multipart($uri);
            echo br(4);
            ?>
            <div class='form-inline'>
            UPLOAD PPH23 : <div class="form-control"><input type="file" name="userfile" size="20" /></div>
            <?php echo form_submit('submit','UPLOAD',"class='form-control'");?>
            </div>
       
            <?php
            echo form_close();            
        }break;
    }
?>
