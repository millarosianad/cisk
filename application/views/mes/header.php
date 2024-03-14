<!DOCTYPE html>
<html lang="en">
<head>
<title>MES Dashboard</title>

<link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700,800" rel="stylesheet">
<link href="https://fonts.googleapis.com/css?family=Quicksand:500,700" rel="stylesheet">

<link rel="stylesheet" type="text/css" href="<?= base_url() ?>assets_new/web/css/bootstrap.min.css">
<link rel="stylesheet" href="<?= base_url() ?>assets_new/web/css/waves.min.css" type="text/css" media="all">
<link rel="stylesheet" type="text/css" href="<?= base_url() ?>assets_new/web/css/feather.css">
<link rel="stylesheet" type="text/css" href="<?= base_url() ?>assets_new/web/css/themify-icons.css">
<link rel="stylesheet" type="text/css" href="<?= base_url() ?>assets_new/web/css/icofont.css">
<link rel="stylesheet" type="text/css" href="<?= base_url() ?>assets_new/web/css/font-awesome.min.css">
<link rel="stylesheet" type="text/css" href="<?= base_url() ?>assets_new/web/css/prism.css">
<link rel="stylesheet" type="text/css" href="<?= base_url() ?>assets_new/web/css/style.css">
<link rel="stylesheet" type="text/css" href="<?= base_url() ?>assets_new/web/pcoded-horizontal.min.html">


<!-- <link rel="stylesheet" type="text/css" href="<?php echo base_url() ?>assets_new/web/css/themify-icons.css">
<link rel="stylesheet" type="text/css" href="<?php echo base_url() ?>assets_new/web/css/icofont.css">
<link rel="stylesheet" type="text/css" href="<?php echo base_url() ?>assets_new/web/css/datatables.bootstrap4.min.css">
<link rel="stylesheet" type="text/css" href="<?php echo base_url() ?>assets_new/web/css/buttons.datatables.min.css">
<link rel="stylesheet" type="text/css" href="<?php echo base_url() ?>assets_new/web/css/responsive.bootstrap4.min.css">
<link rel="stylesheet" type="text/css" href="<?php echo base_url() ?>assets_new/web/css/pages.css">
<script type="text/javascript" language="javascript" src="<?php echo base_url() ?>assets_new/js/jquery-1.10.2.min.js"></script> -->

<!-- https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.5.2/css/bootstrap.css -->

<!-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.5.2/css/bootstrap.css"> -->
<!-- <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap4.min.css"> -->

<link rel="stylesheet" href="https://cdn.datatables.net/1.13.3/css/jquery.dataTables.min.css"/>
<script src="https://code.jquery.com/jquery-3.5.1.js"></script>
<script src="https://cdn.datatables.net/1.13.3/js/jquery.dataTables.min.js"></script>

<script type="text/javascript" language="javascript" src="<?php echo base_url() ?>assets_new/js/checkbox_all.js"></script>

</head>

<body>

<div class="loader-bg">
    <div class="loader-bar"></div>
</div>

<div id="pcoded" class="pcoded">
    <div class="pcoded-overlay-box"></div>
    <div class="pcoded-container navbar-wrapper">

<nav class="navbar header-navbar pcoded-header">
    <div class="navbar-wrapper">
        <div class="navbar-logo">
            <a href="#">
                <img class="img-fluid" src="<?= base_url() ?>assets/css/images/semutgajah.png" alt="semutgajah" width="70" />
            </a>
            <a class="mobile-menu" id="mobile-collapse" href="#!">
                <i class="feather icon-menu icon-toggle-right"></i>
            </a>
            <a class="mobile-options waves-effect waves-light">
                <i class="feather icon-more-horizontal"></i>
            </a>
        </div>

        <div class="navbar-container container-fluid">
            <ul class="nav-left">
                <li class="header-search">
                    <div class="main-search morphsearch-search">
                        <div class="input-group">
                            <span class="input-group-prepend search-close">
                                <i class="feather icon-x input-group-text"></i>
                            </span>
                            <input type="text" class="form-control" placeholder="Enter Keyword">
                            <span class="input-group-append search-btn">
                                <i class="feather icon-search input-group-text"></i>
                            </span>
                        </div>
                    </div>
                </li>
                <li>
                    <a href="#!" onclick="if (!window.__cfRLUnblockHandlers) return false; javascript:toggleFullScreen()" class="waves-effect waves-light" data-cf-modified-906ddac6b3775a96bb4ff3ff-="">
                        <i class="full-screen feather icon-maximize"></i>
                    </a>
                </li>
            </ul>
            <ul class="nav-right">
                
                <li class="user-profile header-notification">
                    <div class="dropdown-primary dropdown">
                        <div class="dropdown-toggle" data-toggle="dropdown">
                            <img src="<?= base_url() ?>assets_new/web/jpg/avatar-4.jpg" class="img-radius" alt="User-Profile-Image">
                            <span>VERSI DEV 1.0</span>
                            <i class="feather icon-chevron-down"></i>
                        </div>
                        <ul class="show-notification profile-notification dropdown-menu" data-dropdown-in="fadeIn" data-dropdown-out="fadeOut">
                            <li>
                                <a href="<?= base_url() ?>login/logout">
                                    <i class="feather icon-log-out"></i> Logout
                                </a>
                            </li>
                            <li>
                                <a href="<?= base_url() ?>dashboard_dummy">
                                    <i class="feather icon-log-out"></i> web versi lama
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>
            </ul>
        </div>
    </div>
</nav>





<div class="pcoded-main-container">
    <div class="pcoded-wrapper">

        <nav class="pcoded-navbar">
            <div class="pcoded-inner-navbar">
                <ul class="pcoded-item">
                    <li class="pcoded-hasmenu">
                        <a href="javascript:void(0)" class="waves-effect waves-dark">
                            <span class="pcoded-micon"><i class="feather icon-sidebar"></i></span>
                            <span class="pcoded-mtext">Master</span>
                        </a>
                        <ul class="pcoded-submenu">
                            <li>
                                <a href="<?= base_url() ?>mes/user" class="waves-effect waves-dark">
                                    <span class="pcoded-mtext">User</span>
                                </a>
                            </li>
                            <li>
                                <a href="<?= base_url() ?>mes/store" class="waves-effect waves-dark">
                                    <span class="pcoded-mtext">Store</span>
                                </a>
                            </li>
                            <li>
                                <a href="<?= base_url() ?>mes/olshop" class="waves-effect waves-dark">
                                    <span class="pcoded-mtext">Olshop</span>
                                </a>
                            </li>
                            <li>
                                <a href="<?= base_url() ?>mes/sku_olshop" class="waves-effect waves-dark">
                                    <span class="pcoded-mtext">SKU Olshop</span>
                                </a>
                            </li>
                            <li>
                                <a href="<?= base_url() ?>mes/product" class="waves-effect waves-dark">
                                    <span class="pcoded-mtext">Product</span>
                                </a>
                            </li>
                        </ul>
                    </li>
                    <li class="pcoded-hasmenu">
                        <a href="javascript:void(0)" class="waves-effect waves-dark">
                            <span class="pcoded-micon"><i class="feather icon-sidebar"></i></span>
                            <span class="pcoded-mtext">Transaction</span>
                        </a>
                        <ul class="pcoded-submenu">
                            <li>
                                <a href="<?= base_url() ?>mes/transaksi" class="waves-effect waves-dark">
                                    <span class="pcoded-mtext">Proses Transaksi</span>
                                </a>
                            </li>
                            <li>
                                <a href="<?= base_url() ?>mes/posting" class="waves-effect waves-dark">
                                    <span class="pcoded-mtext">Posting Transaksi</span>
                                </a>
                            </li>
                            <li>
                                <a href="<?= base_url() ?>mes/proses_gudang" class="waves-effect waves-dark">
                                    <span class="pcoded-mtext">Proses Gudang</span>
                                </a>
                            </li>
                            <li>
                                <a href="<?= base_url() ?>mes/piutang" class="waves-effect waves-dark">
                                    <span class="pcoded-mtext">Piutang</span>
                                </a>
                            </li>
                            <li>
                                <a href="#" class="waves-effect waves-dark">
                                    <span class="pcoded-mtext">Ajuan</span>
                                </a>
                            </li>
                        </ul>
                    </li>

                    <li class="pcoded-hasmenu">
                        <a href="javascript:void(0)" class="waves-effect waves-dark">
                            <span class="pcoded-micon"><i class="feather icon-sidebar"></i></span>
                            <span class="pcoded-mtext">Report</span>
                        </a>
                        <ul class="pcoded-submenu">
                            <li>
                                <a href="<?= base_url() ?>mes/raw_data" class="waves-effect waves-dark">
                                    <span class="pcoded-mtext">Raw Data</span>
                                </a>
                            </li>
                        </ul>
                    </li>

                </ul>
            </div>
        </nav>

<div class="pcoded-content">

<br><br>
<div class="pcoded-inner-content">

<div class="main-body">
<div class="page-wrapper">

<div class="page-body">
<div class="row">
<div class="col-lg-12">