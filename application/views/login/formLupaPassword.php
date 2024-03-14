
<!DOCTYPE html>
<html class="h-100" lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title><?php echo $title; ?></title>
    <!-- Favicon icon -->
    <link rel="icon" type="image/png" sizes="16x16" href="../../../assets_new/login/images/favicon.png">
    <link href="<?php echo base_url(); ?>assets_new/login/css/style.css" rel="stylesheet">
    
</head>

<body class="h-100">
    
    <!--*******************
        Preloader start
    ********************-->
    <!-- <div id="preloader">
        <div class="loader">
            <svg class="circular" viewBox="25 25 50 50">
                <circle class="path" cx="50" cy="50" r="20" fill="none" stroke-width="3" stroke-miterlimit="10" />
            </svg>
        </div>
    </div> -->
    <!--*******************
        Preloader end
    ********************-->

<div class="login-form-bg h-100">
    <div class="container h-100">
        <div class="row justify-content-center h-100">
            <div class="col-xl-6">
                <div class="form-input-content">
                    <div class="card login-form mb-0">
                        <div class="card-body pt-5">
                            <a class="text-center" href="index.html"> <h4>Forgot your Password?</h4></a>
        
                               <?php 
                                $attributes = array('class' => 'mt-5 mb-5 login-input');
                                echo form_open($url, $attributes);
                                ?>
 
                                <div class="form-group">
                                    <input type="text" class="form-control" placeholder="email" name="email" required>
                                </div>
                               
                                <button class="btn login-form__btn submit w-100">Reset Password</button>
                            </form>
                            <!-- <p class="mt-5 login-form__footer">Belum punya akun ? <a href="<?php echo base_url(); ?>login/formRegistrasi" class="text-primary">Daftar disini</a><br>
                            Lupa password login ? <a href="<?php echo base_url(); ?>login/formLupaPassword" class="text-primary">Klik Reset Password</a></p> -->

                            <p class="mt-5 login-form__footer"> <a href="<?php echo base_url(); ?>login_sistem/formLogin" class="text-primary">Back to Login</a></p>
                        
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
    

    <!--**********************************
        Scripts
    ***********************************-->
    <script src="<?php echo base_url(); ?>assets_new/login/plugins/common/common.min.js"></script>
    <script src="<?php echo base_url(); ?>assets_new/login/js/custom.min.js"></script>
    <script src="<?php echo base_url(); ?>assets_new/login/js/settings.js"></script>
    <script src="<?php echo base_url(); ?>assets_new/login/js/gleek.js"></script>
    <script src="<?php echo base_url(); ?>assets_new/login/js/styleSwitcher.js"></script>
</body>
</html>





