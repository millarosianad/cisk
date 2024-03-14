
<!DOCTYPE html>
<html class="h-100" lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title><?php echo $title; ?></title>
    <link href="<?php echo base_url(); ?>assets_new/login/css/style.css" rel="stylesheet">
    
</head>

<body class="h-100">
    
   

<div class="login-form-bg h-100">
    <div class="container h-100">
        <div class="row justify-content-center h-100">
            <div class="col-xl-6">
                <div class="form-input-content">
                    <div class="card login-form mb-0">
                        <div class="card-body pt-5">
                            <a class="text-center" href="index.html"> <h4>Validasi Email</h4></a>

                                <?php 
                                $attributes = array('class' => 'mt-5 mb-5 login-input');
                                echo form_open($url, $attributes);
                                ?>
                                <!-- <p>(*) Website memerlukan alamat email anda sebagai syarat penggunaan web ini</p> -->
                                <p><i> Mohon masukkan alamat email anda.</i></p>
                                <p><i> Mohon masukkan alamat email anda terlebih dahulu. Utamakan email dengan domain perusahaan</i></p><hr><br>
                                <div class="form-group">
                                    <input type="email" class="form-control" placeholder="silahkan masukkan email anda disini ... " name="email" required>
                                </div>
                                <button class="btn login-form__btn submit w-100">Proses</button>
                            </form>
                            <p class="mt-5 login-form__footer">kembali ke halaman login <a href="<?php echo base_url(); ?>login_sistem/formLogin" class="text-primary">Klik disini</a></p>
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





