<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title><?php echo $title; ?></title>
        <link rel="stylesheet" type="text/css" href=" <?php echo base_url()."assets/css/flick/jquery-ui.css" ?> ">
        
        <?php 
            echo link_tag('assets/css/jquery.tablescroll.css');
        ?>
        
        <!-- <script type="text/javascript" src="<?php echo base_url()."assets/js/jquery-1.10.2.js"?>"></script>
        <script type="text/javascript" src="<?php echo base_url()."assets/js/jquery-ui-1.10.4.custom.min.js"?>"></script>       -->
        <!-- <script type="text/javascript" src="<?php echo assets_url("js/jquery.tablescroll.js")?>"></script> -->
        <!-- <script src="<?php echo assets_url('js/bootstrap.min.js'); ?> -->
        <script type="text/javascript" language="javascript" src="//code.jquery.com/jquery-1.10.2.min.js"></script>
        <!-- Load Datatables dan Bootstrap dari CDN -->
        <!-- <script type="text/javascript" language="javascript" src="https://cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js"></script>
        <script type="text/javascript" language="javascript" src="https://cdn.datatables.net/1.10.21/js/dataTables.bootstrap.min.js"></script>
        <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.21/css/jquery.dataTables.min.css">
        <link rel="stylesheet" type="text/css" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
        <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.21/css/dataTables.bootstrap.min.css"> -->

        <!-- Load Datatables dan Bootstrap dari CDN -->
        <script type="text/javascript" language="javascript" src="//cdn.datatables.net/1.10.4/js/jquery.dataTables.min.js"></script>
        <script type="text/javascript" language="javascript" src="//cdn.datatables.net/plug-ins/9dcbecd42ad/integration/bootstrap/3/dataTables.bootstrap.js"></script>
        
        <link rel="stylesheet" type="text/css" href="//netdna.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
        <link rel="stylesheet" type="text/css" href="//cdn.datatables.net/plug-ins/9dcbecd42ad/integration/bootstrap/3/dataTables.bootstrap.css">

        <!-- sebelumnya -->
        <script type="text/javascript" src="<?php echo base_url()."assets/js/jquery-ui-1.10.4.custom.min.js"?>"></script>
        
    <!-- https://code.jquery.com/jquery-3.5.1.js
    https://cdn.datatables.net/1.10.21/js/dataTables.bootstrap.min.js -->

    
    <!-- https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css
    https://cdn.datatables.net/1.10.21/css/dataTables.bootstrap.min.css -->



        <meta name="description" content="<?php echo $description; ?>">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <!-- Extra metadata -->
        <?php echo $metadata; ?>
        <!-- / -->
        <!-- favicon.ico and apple-touch-icon.png -->
        <!-- Bootstrap core CSS -->
        <link rel="stylesheet" href="<?php echo assets_url('css/bootstrap.min.css'); ?>">
        <!-- Custom styles -->
        <link rel="stylesheet" href="<?php echo assets_url('css/main.css'); ?>">
        <?php echo $css; ?>
        <!-- / -->

        <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
        <!--[if lt IE 9]>
            <script src="<?php echo assets_url('js/html5shiv.min.js'); ?>"></script>
            <script src="<?php echo assets_url('js/respond.min.js'); ?>"></script>
        <![endif]-->
    </head>
    <body>
        <?php echo $body; ?>
        <!-- / -->

        <!--script src="<?php echo assets_url('js/jquery-1.11.0.min.js'); ?>"></script-->
        <!-- <script src="<?php echo assets_url('js/bootstrap.min.js'); ?>"></script> -->
        <script src="<?php echo assets_url('js/main.js'); ?>"></script>
        <!-- Extra javascript -->
        <!-- <?php echo $js; ?> -->

        <!-- <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script> -->
        <!-- <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script> -->
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js" integrity="sha384-OgVRvuATP1z7JjHLkuOU7Xw704+h835Lr+6QL9UvYjZE3Ipu6Tp75j7Bh/kR0JKI" crossorigin="anonymous"></script>
  


        <!-- / -->
    </body>
</html>