<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');?>
   <div style="position: fixed;
            top: 25%;
            left: 30%;
            margin-top: -50px;
            margin-left: -100px;">
       <!--img class="img-responsive"  width="200px" src="<?php echo base_url().'assets/css/images/mpm_new.jpg'?>"-->
        <?php echo form_open($uri)?>
            <h1><div class="form-group">
                <?php echo form_label('Username','for="user"')?>
                <?php echo br().form_input('userx','','size="10" id="user"')?>
                <?php echo br().form_label('Password','for="user"')?>
                <?php echo br().form_password('passx','','size="10" id="pass"')?>
                <br/><?php echo $err;?><br/>
                <?php echo br().form_submit('submit','Login','size="40" align="right"')?>
                <?php echo form_reset('reset','Clear','align="right"')?>
            </div></h1>
        <?php echo form_close()?>
   </div>

