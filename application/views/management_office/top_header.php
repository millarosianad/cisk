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

    <!-- fontawesome -->
    <link href="<?= base_url() ?>assets/css/fontawesome-free-6.5.1-web/css/fontawesome.css" rel="stylesheet">
    <link href="<?= base_url() ?>assets/css/fontawesome-free-6.5.1-web/css/brands.css" rel="stylesheet">
    <link href="<?= base_url() ?>assets/css/fontawesome-free-6.5.1-web/css/solid.css" rel="stylesheet">

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
              <!-- <a href="<?= base_url() ?>management_office" class="nav-link"><i class="typcn typcn-chart-area-outline"></i> Dashboard</a> -->
              <a href="" class="nav-link with-sub"><i class="typcn typcn-chart-area-outline"></i> Dashboard</a>
              <nav class="az-menu-sub">
                <a href="<?= base_url() ?>management_dashboard/dashboard" class="nav-link" target="_blank">Dashboard RM Kal-Sul</a>
              </nav>
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
                <a href="<?= base_url() ?>outlet_transaksi/outlet_transaksi_ytd" class="nav-link">Outlet Transaksi Ytd</a>
                <a href="<?= base_url() ?>outlet_transaksi/pengambilan" class="nav-link" target="_blank">Outlet Transaksi 1X 2X 3X</a>
                <a href="<?= base_url() ?>outlet_transaksi/otsc" class="nav-link" target="_blank">Outlet Exception</a>
              </nav>
            </li>
            <li class="nav-item">
              <a href="" class="nav-link with-sub"><i class="typcn typcn-document-text"></i> Building Block</a>
              <nav class="az-menu-sub">
                <a href="<?= base_url().'building_block/summary_sales' ?>" class="nav-link">Master Summary</a>
                <a href="<?= base_url().'building_block/workspace_target_principal' ?>" class="nav-link">Target By Principal</a>
                <a href="<?= base_url().'building_block/workspace_target_outlet' ?>" class="nav-link">Target By Outlet</a>
              </nav>
            </li>
            <li class="nav-item">
              <a href="" class="nav-link with-sub"><i class="typcn typcn-document-text"></i> Claim Monitoring</a>
              <nav class="az-menu-sub">
                <a href="<?= base_url().'management_claim/dashboard' ?>" class="nav-link">Dashboard</a>
                <a href="<?= base_url().'management_claim/registrasi_program' ?>" class="nav-link">Registrasi Program</a>
                <a href="<?= base_url().'management_claim/ajuan_claim' ?>" class="nav-link">Pengajuan Claim</a>
                <a href="<?= base_url().'management_claim/report' ?>" class="nav-link">Raw Data</a>
              </nav>
            </li>
            <li class="nav-item">
              <a href="" class="nav-link with-sub"><i class="typcn typcn-plane-outline"></i> Aktivitas</a>
              <nav class="az-menu-sub">
                <a href="<?= base_url() ?>management_rpd/pengajuan" class="nav-link">Rencana Perjalanan Dinas</a>
                <a href="<?= base_url() ?>management_event/pelaporan" class="nav-link">Pelaporan Event</a>
                <a href="<?= base_url() ?>biaya_operasional/history" class="nav-link" target="_blank">Klaim BIOP</a>
                <a href="<?= base_url() ?>management_asset/pengajuan_asset" class="nav-link">Pengajuan Asset</a>
                <a href="<?= base_url() ?>management_asset/data_asset" class="nav-link">Data Asset (Finance)</a>
              </nav>
            </li>
            <li class="nav-item">
              <a href="" class="nav-link with-sub"><i class="typcn typcn-cog-outline"></i> Other</a>
              <nav class="az-menu-sub">
                <a href="<?= base_url() ?>dashboard_dummy" class="nav-link" target="_blank">Website MPM Old Template</a>
                <?php 
                  if ($this->session->userdata('username') == 'nanita' || $this->session->userdata('username') == 'suffy') { ?>
                    <a href="<?= base_url() ?>all_transaction/open_credit_limit" class="nav-link" target="_blank">Open Credit Limit <i>(Finance)</i></a>
                  <?php
                  }
                ?>
                <?php 
                  if ($this->session->userdata('username') == 'milla' || $this->session->userdata('username') == 'linda' || $this->session->userdata('username') == 'tria' || $this->session->userdata('username') == 'fakhrul') { ?>
                    <a href="<?= base_url() ?>master_product/product" class="nav-link" target="_blank">Master Product</a>
                <?php
                  }
                ?>
                <?php 
                  if ($this->session->userdata('username') == 'fakhrul' || $this->session->userdata('username') == 'suffy' || $this->session->userdata('username') == 'linda' || $this->session->userdata('username') == 'tria') { ?>
                    <a href="<?= base_url() ?>management_bonus/master_data" class="nav-link" target="_blank">Master Bonus <i>(SupplyChain)</i></a>
                    <a href="<?= base_url() ?>management_bonus/tracking" class="nav-link" target="_blank">Tracking Bonus <i>(SupplyChain)</i></a>
                    <a href="<?= base_url() ?>transaction/list_order" class="nav-link" target="_blank">List Order <i>(SupplyChain)</i></a>
                    <a href="<?= base_url() ?>all_po/po_monitoring" class="nav-link" target="_blank">PO Monitoring <i>(SupplyChain)</i></a>
                    <a href="<?= base_url() ?>dc/dashboard" class="nav-link" target="_blank">DC <i>(SupplyChain)</i></a>
                  <?php
                  }
                ?>
                <?php 
                  if ($this->session->userdata('username') == 'suffy' || $this->session->userdata('username') == 'erick' || $this->session->userdata('username') == 'gabriela' || $this->session->userdata('username') == 'sara' || $this->session->userdata('username') == 'tria' || $this->session->userdata('username') == 'ilham') { ?>
                    <a href="<?= base_url() ?>management_retur/dashboard" class="nav-link" target="_blank">dashboard nota retur <i>(Accounting)</i></a>
                    <a href="<?= base_url() ?>management_retur/master_dbsls" class="nav-link" target="_blank">Master DBSLS <i>(Accounting)</i></a>
                    <a href="<?= base_url() ?>management_retur/ajuan_retur" class="nav-link" target="_blank">Ajuan Retur <i>(Accounting)</i></a>
                    <a href="#" class="nav-link" target="_blank">Ajuan Relokasi <i>(Accounting)</i></a>
                    <a href="<?= base_url() ?>management_retur/nota_retur" class="nav-link" target="_blank">History Nota Retur <i>(Accounting)</i></a>
                    <a href="<?= base_url() ?>trans/retur" class="nav-link" target="_blank">Retur Versi 1 <i>(Accounting)</i></a>
                  <?php
                  }
                ?>
                <?php 
                  if ($this->session->userdata('username') == 'suffy' || $this->session->userdata('username') == 'septian' || $this->session->userdata('username') == 'milla' || $this->session->userdata('username') == 'ilham') { ?>
                    <a href="<?= base_url() ?>management_raw" class="nav-link" target="_blank">Management Raw Area <i>(RM)</i></a>
                    <a href="<?= base_url() ?>kalimantan/po" class="nav-link" target="_blank">Dashboard PO <i>(RM)</i></a>
                    <a href="<?= base_url() ?>kalimantan/ajuan_retur" class="nav-link" target="_blank">Dashboard Retur <i>(RM)</i></a>
                  <?php
                  }
                ?>
                <?php 
                  if ($this->session->userdata('username') == 'suffy' || $this->session->userdata('username') == 'sadmin' || $this->session->userdata('username') == 'dewi' || $this->session->userdata('username') == 'adinda' || $this->session->userdata('username') == 'angga') { ?>
                    <a href="<?= base_url() ?>mes" class="nav-link" target="_blank">MES <i>(Eccommerce)</i></a>
                  <?php
                  }
                ?>
                <?php 
                  if ($this->session->userdata('username') == 'suffy' || $this->session->userdata('username') == 'felix') { ?>
                    <a href="<?= base_url() ?>monitor" class="nav-link" target="_blank">Monitor Closing Deltomed <i>(System)</i></a>
                  <?php
                  }
                ?>
                <?php 
                  if ($this->session->userdata('username') == 'suffy' || $this->session->userdata('username') == 'sampurno') { ?>
                    <a href="<?= base_url() ?>monitor" class="nav-link" target="_blank">Dashboard MTI <i>(Kam)</i></a>
                  <?php
                  }
                ?>
                <?php 
                  if ($this->session->userdata('username') == 'suffy') { ?>
                    <a href="<?= base_url() ?>management_claim/dashboard" class="nav-link" target="_blank">Claim Dashboard <i>(Admin)</i></a>
                    <a href="<?= base_url() ?>management_claim/site" class="nav-link" target="_blank">Registrasi Site <i>(Admin)</i></a>
                    <a href="<?= base_url() ?>management_claim/registrasi_program" class="nav-link" target="_blank">Registrasi Program <i>(Admin)</i></a>
                    <a href="<?= base_url() ?>management_claim/ajuan_claim" class="nav-link" target="_blank">Ajuan Program <i>(Admin)</i></a>
                  <?php
                  }
                ?>
                <?php 
                  if ($this->session->userdata('username') == 'suffy' || $this->session->userdata('username') == 'tius' || $this->session->userdata('username') == 'erlandy' || $this->session->userdata('username') == 'milla') { ?>
                    <a href="<?= base_url() ?>surat_jalan" class="nav-link" target="_blank">Surat Jalan <i>(Finance)</i></a>
                  <?php
                  }
                ?>
                <?php 
                  if ($this->session->userdata('username') == 'sampurno' || $this->session->userdata('username') == 'suffy') { ?>
                    <a href="<?= base_url() ?>request/history" class="nav-link" target="_blank">Perubahan Tipe Outlet <i>(KAM)</i></a>
                  <?php
                  }
                ?>
                <?php 
                  if ($this->session->userdata('username') == 'milla' || $this->session->userdata('username') == 'suffy') { ?>
                    <a href="<?= base_url() ?>management_raw/customer_banjarmasin" class="nav-link" target="_blank"> Customer Bridging <i>(SUM)</i></a>
                <?php
                  }
                  elseif ($this->session->userdata('username') == 'ilham') { ?>
                    <a href="<?= base_url() ?>management_raw/customer_palangkaraya" class="nav-link" target="_blank"> Customer Bridging <i>(SUM)</i></a>
                <?php
                  }
                ?>
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