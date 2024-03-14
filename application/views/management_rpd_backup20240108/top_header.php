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
            <li class="nav-item <?= $this->uri->segment('2') == "dashboard_retur" ? 'active show' : '' ?>">
              <a href="#" class="nav-link"><i class="typcn typcn-chart-area-outline"></i> Dashboard</a>
            </li>
            <li class="nav-item">
              <a href="" class="nav-link with-sub"><i class="typcn typcn-document"></i> Inventory</a>
              <nav class="az-menu-sub">
                <a href="<?= base_url() ?>stok/stok_product" class="nav-link" target="_blank">Report Stock Product</a>
                <a href="<?= base_url() ?>inventory/stock_akhir_doi" class="nav-link" target="_blank">Report Stock DOI</a>
                <a href="<?= base_url() ?>inventory/po_outstanding" class="nav-link" target="_blank">Report PO Outstanding</a>
                <a href="<?= base_url() ?>inventory/laporan_po" class="nav-link" target="_blank">Report PO  </a>
                <a href="#" class="nav-link">Dashboard PO</a>
                <a href="<?= base_url().'management_inventory/dashboard' ?>" class="nav-link">Dashboard Retur</a>
                <a href="#" class="nav-link">Dashboard Tracking Bonus</a>
              </nav>
            </li>
            <li class="nav-item">
              <a href="" class="nav-link with-sub"><i class="typcn typcn-document"></i> Sales</a>
              <nav class="az-menu-sub">
                <a href="<?= base_url() ?>portal_raw" class="nav-link" target="_blank">Portal Raw Data</a>
                <a href="<?= base_url() ?>sales_omzet/omzet" class="nav-link" target="_blank">Omzet DP  </a>
                <a href="<?= base_url() ?>sales_omzet/omzet_dp" class="nav-link" target="_blank">Omzet DP (Versi 1) </a>
                <a href="<?= base_url() ?>sales_omzet/sell_out_product" class="nav-link" target="_blank">Sell out Product  </a>
                <a href="<?= base_url() ?>sales_omzet/sales_outlet" class="nav-link" target="_blank">Sales Outlet  </a>
                <a href="<?= base_url() ?>sales_omzet/sell_out_nasional" class="nav-link" target="_blank">Sell Out Nasional  </a>
                <a href="<?= base_url() ?>outlet_transaksi/outlet_transaksi_ytd" class="nav-link">Outlet Transaksi Ytd</a>
                <a href="<?= base_url() ?>outlet_transaksi/pengambilan" class="nav-link" target="_blank">Outlet Transaksi 1X 2X 3X</a>
                <a href="<?= base_url() ?>outlet_transaksi/otsc" class="nav-link" target="_blank">Outlet Exception</a>
              </nav>
            </li>
            <li class="nav-item">
              <a href="" class="nav-link with-sub"><i class="typcn typcn-document"></i> Claim Monitoring</a>
              <nav class="az-menu-sub">
                <a href="#" class="nav-link">Dashboard Claim</a>
              </nav>
            </li>
            <li class="nav-item">
              <a href="" class="nav-link with-sub"><i class="typcn typcn-document"></i> Aktivitas</a>
              <nav class="az-menu-sub">
                <a href="<?= base_url() ?>management_rpd/pengajuan" class="nav-link">Rencana Perjalanan Dinas</a>
                <a href="<?= base_url() ?>biaya_operasional/history" class="nav-link" target="_blank">Klaim BIOP</a>
              </nav>
            </li>
            <li class="nav-item">
              <a href="" class="nav-link with-sub"><i class="typcn typcn-document"></i> Links to</a>
              <nav class="az-menu-sub">
                <a href="<?= base_url() ?>dashboard_dummy" class="nav-link" target="_blank">Website MPM</a>
                <!-- <a href="<?= base_url() ?>retur" class="nav-link" target="_blank">Ajuan Retur</a>
                <a href="<?= base_url() ?>relokasi" class="nav-link" target="_blank">Relokasi</a> -->
              </nav>
            </li>
          </ul>
        </div>
        <div class="az-header-right">
          <div class="dropdown az-profile-menu">
            <a href="" class="az-img-user"><img src="<?= base_url() ?>assets/img/faces/user.png" alt=""></a>
            <div class="dropdown-menu">
              <div class="az-dropdown-header d-sm-none">
                <a href="" class="az-header-arrow"><i class="icon ion-md-arrow-back"></i></a>
              </div>
              <div class="az-header-profile">
                <div class="az-img-user">
                  <img src="<?= base_url() ?>assets/img/faces/user.png" alt="">
                </div>
                <h6><?= $this->session->userdata('username') ?></h6>
                <!-- <span>Premium Member</span> -->
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>