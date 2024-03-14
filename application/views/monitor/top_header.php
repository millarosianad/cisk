<!DOCTYPE html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Meta -->
    <meta name="description" content="Responsive Bootstrap 4 Dashboard Template">
    <meta name="author" content="BootstrapDash">

    <title><?= $title ?></title>

    <!-- vendor css -->
    <link href="<?= base_url() ?>assets_milla/lib/fontawesome-free/css/all.min.css" rel="stylesheet">
    <link href="<?= base_url() ?>assets_milla/lib/ionicons/css/ionicons.min.css" rel="stylesheet">
    <link href="<?= base_url() ?>assets_milla/lib/typicons.font/typicons.css" rel="stylesheet">
    <link href="<?= base_url() ?>assets_milla/lib/flag-icon-css/css/flag-icon.min.css" rel="stylesheet">

    <!-- azia CSS -->
    <link rel="stylesheet" href="<?= base_url() ?>assets/css/azia.css">

    <!-- datatable -->
    <link
      rel="stylesheet"
      href="https://cdn.datatables.net/1.13.3/css/jquery.dataTables.min.css"
    />
    <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
    <script src="https://cdn.datatables.net/1.13.3/js/jquery.dataTables.min.js"></script>
  </head>
  <body>

    <div class="az-header">
      <div class="container">
        <div class="az-header-left">
          <!-- <a href="#" class="az-logo"><span></span> MPM</a> -->
          <a href="#" class="az-logo"><span></span> 
            <img class="img-fluid" src="<?= base_url() ?>assets/css/images/semutgajah.png" alt="semutgajah" width="70" />
          </a>
          <a href="" id="azMenuShow" class="az-header-menu-icon d-lg-none"><span></span></a>
        </div><!-- az-header-left -->
        <div class="az-header-menu">
          <div class="az-header-menu-header">
            <!-- <a href="#" class="az-logo"><span></span> MPM</a> -->
            <a href="#" class="az-logo"><span></span> 
              <img class="img-fluid" src="<?= base_url() ?>assets/css/images/semutgajah.png" alt="semutgajah" width="70" />
            </a>
            <a href="" class="close">&times;</a>
          </div><!-- az-header-menu-header -->
          <ul class="nav">
            <li class="nav-item <?= $this->uri->segment('2') == "dashboard" ? 'active show' : '' ?>">
              <a href="<?= base_url().'monitor/dashboard' ?>" class="nav-link"><i class="typcn typcn-chart-area-outline"></i> Dashboard</a>
            </li>
            <li class="nav-item <?= $this->uri->segment('2') == "library_raw_data" ? 'active show' : '' ?>">
              <a href="<?= base_url().'monitor/library_raw_data' ?>" class="nav-link"><i class="fas fa-archive"></i>&nbsp; Library Raw Data</a>
            </li>
            <li class="nav-item">
              <a href="" class="nav-link with-sub"><i class="typcn typcn-document"></i> Links to</a>
              <nav class="az-menu-sub">
                <a href="<?= base_url() ?>dashboard_dummy" class="nav-link">Website MPM</a>
                <a href="https://semutgajah.com/" class="nav-link">Semut Gajah</a>
              </nav>
            </li>
          </ul>
        </div>
        <div class="az-header-right">
          <div class="dropdown az-profile-menu">
            <a href="" class="az-img-user"><img src="<?= base_url() ?>assets/img/faces/face1.jpg" alt=""></a>
            <div class="dropdown-menu">
              <div class="az-dropdown-header d-sm-none">
                <a href="" class="az-header-arrow"><i class="icon ion-md-arrow-back"></i></a>
              </div>
              <div class="az-header-profile">
                <div class="az-img-user">
                  <img src="<?= base_url() ?>assets/img/faces/face1.jpg" alt="">
                </div>
                <h6>No Name</h6>
                <!-- <span>Premium Member</span> -->
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>