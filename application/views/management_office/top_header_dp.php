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
    <link href="<?= base_url() ?>assets/css/lib/fontawesome-free/css/all.min.css" rel="stylesheet">
    <link href="<?= base_url() ?>assets/css/lib/ionicons/css/ionicons.min.css" rel="stylesheet">
    <link href="<?= base_url() ?>assets/css/lib/typicons.font/typicons.css" rel="stylesheet">
    <link href="<?= base_url() ?>assets/css/lib/flag-icon-css/css/flag-icon.min.css" rel="stylesheet">

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
            <li class="nav-item <?= $this->uri->segment('2') == "" ? 'active show' : '' ?>">
              <a href="<?= base_url() ?>management_office" class="nav-link"><i class="typcn typcn-chart-area-outline"></i> Dashboard</a>
            </li>
            <li class="nav-item">
              <a href="" class="nav-link with-sub"><i class="typcn typcn-home-outline"></i> Inventory</a>
              <nav class="az-menu-sub">
                <a href="<?= base_url() ?>stok/stok_product" class="nav-link" target="_blank">Report Stock Product</a>
                <a href="<?= base_url() ?>inventory/stock_akhir_doi" class="nav-link" target="_blank">Report Stock DOI</a>
                <a href="<?= base_url() ?>inventory/po_outstanding" class="nav-link" target="_blank">Report PO Outstanding</a>
                <a href="<?= base_url() ?>inventory/laporan_po" class="nav-link" target="_blank">Report PO  </a>
                <a href="#" class="nav-link">Dashboard PO (*)</a>
                <a href="<?= base_url().'management_inventory/dashboard' ?>" class="nav-link">Retur Versi 2</a>
                <a href="<?= base_url().'retur' ?>" class="nav-link" target="_blank">Retur Versi 1</a>
                <a href="#" class="nav-link">Dashboard Tracking Bonus (*)</a>
              </nav>
            </li>
            <li class="nav-item">
              <a href="" class="nav-link with-sub"><i class="typcn typcn-chart-line-outline"></i> Sales</a>
              <nav class="az-menu-sub">
                <a href="<?= base_url() ?>portal_raw" class="nav-link" target="_blank">Portal Raw Data</a>
                <a href="<?= base_url() ?>sales_omzet/omzet" class="nav-link" target="_blank">Omzet DP  </a>
                <a href="<?= base_url() ?>sales_omzet/omzet_dp" class="nav-link" target="_blank">Omzet DP (Versi 1) </a>
                <a href="<?= base_url() ?>sales_omzet/sell_out_product" class="nav-link" target="_blank">Sell out Product  </a>
                <a href="<?= base_url() ?>sales_omzet/sales_outlet" class="nav-link" target="_blank">Sales Outlet  </a>
                <a href="<?= base_url() ?>sales_omzet/sell_out_nasional" class="nav-link" target="_blank">Sell Out Nasional  </a>
                <a href="<?= base_url() ?>outlet_transaksi/outlet_transaksi_ytd" class="nav-link" target="_blank">Outlet Transaksi Ytd</a>
                <a href="<?= base_url() ?>outlet_transaksi/pengambilan" class="nav-link" target="_blank">Outlet Transaksi 1X 2X 3X</a>
                <a href="<?= base_url() ?>outlet_transaksi/otsc" class="nav-link" target="_blank">Outlet Exception</a>
              </nav>
            </li>
            <li class="nav-item">
              <a href="" class="nav-link with-sub"><i class="typcn typcn-plane-outline"></i> Aktivitas</a>
              <nav class="az-menu-sub">
                <a href="<?= base_url() ?>upload_file" class="nav-link" target="_blank">Upload Data</a>
                <a href="<?= base_url() ?>transaction/list_product" class="nav-link" target="_blank">SPK</a>
                <a href="<?= base_url() ?>inventory/konfirmasi_po" class="nav-link" target="_blank">Konfirmasi PO</a>
                <a href="<?= base_url() ?>helpdesk" class="nav-link" target="_blank">Helpdesk</a>
              </nav>
            </li>
            <li class="nav-item">
              <a href="" class="nav-link with-sub"><i class="typcn typcn-document-text"></i> Claim Monitoring</a>
              <nav class="az-menu-sub">
                <a href="<?= base_url() ?>management_claim/dashboard" class="nav-link">dashboard</a>
                <a href="<?= base_url() ?>management_claim/ajuan_claim" class="nav-link">input pengajuan</a>
              </nav>
            </li>
            <li class="nav-item">
              <a href="" class="nav-link with-sub"><i class="typcn typcn-cog-outline"></i> Other</a>
              <nav class="az-menu-sub">
                <a href="<?= base_url() ?>dashboard_dummy" class="nav-link" target="_blank">Website MPM Old Template</a>
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
              </div>
              <a href="<?= base_url() ?>profile/account" class="dropdown-item" target="_blank"><i class="typcn typcn-user-outline"></i> My Profile</a>
              <a href="<?= base_url() ?>management_profile/signature" class="dropdown-item" target="_blank"><i class="typcn typcn-user-outline"></i> Signature</a>
              <a href="<?= base_url() ?>login/logout" class="dropdown-item"><i class="typcn typcn-user-outline"></i> Logout</a>
            </div>
          </div>
        </div>
      </div>
    </div>