<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');?>
   <div style="position: fixed;
            top: 50%;
            left: 50%;
            margin-top: -50px;
            margin-left: -100px;">
       <!--img class="img-responsive"  width="200px" src="<?php echo base_url().'assets/css/images/mpm_new.jpg'?>"-->
        <?php echo form_open($uri)?>
            <div class="form-group">
                <?php echo form_label('Username','for="user"')?>
                <?php echo form_input('userx','','id="user" class="form-control"')?>
                <?php echo form_label('Password','for="user"')?>
                <?php echo form_password('passx','','id="pass" class="form-control"')?>
                <br/>
                <?php echo form_submit('submit','Login','class="btn btn-info" size="20" align="right"')?>
                <?php echo form_reset('reset','Clear','class="btn btn-default" align="right"')?>
            </div>
        <?php echo form_close()?>
   </div>

