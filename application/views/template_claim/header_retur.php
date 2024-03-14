<?php $username = $this->session->userdata('username'); ?>
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
                    </a>
                    <a class="mobile-menu" id="mobile-collapse" href="#!">
                        <i class="feather icon-menu icon-toggle-right"></i>
                    </a>
                    <a class="mobile-options waves-effect waves-light">
                        <i class="feather icon-more-horizontal"></i>
                    </a>
                </div>
                <div class="navbar-container container-fluid">
                    <ul class="nav-right">
                        <li class="user-profile header-notification">

                            <div class="dropdown-primary dropdown">
                                <div class="dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                                    <img src="<?php echo base_url() . 'assets_new/images/icon_avatar.jpg' ?>" class="img-radius" alt="User-Profile-Image">
                                    <span><?php echo $username; ?></span>
                                    <i class="feather icon-chevron-down"></i>
                                </div>
                                <ul class="show-notification profile-notification dropdown-menu" data-dropdown-in="fadeIn" data-dropdown-out="fadeOut">
                                    <li>
                                        <a href="<?php echo base_url() . 'profile/account' ?>">
                                            <i class="feather icon-user"></i> Profile
                                        </a>
                                    </li>
                                    <li>
                                        <a href="<?php echo base_url() . 'assets_2/my_asset' ?>">
                                            <i class="ti-bag"></i>My Asset
                                        </a>
                                    </li>
                                    <li>
                                        <a href="<?php echo base_url() . 'biaya_operasional/history' ?>">
                                            <i class="ti-car"></i>Biaya Operasional
                                        </a>
                                    </li>
                                    <li>
                                        <a href="<?php echo base_url() . 'rpd/history' ?>">
                                            <i class="ti-target"></i>Rencana Perjalanan Dinas
                                        </a>
                                    </li>
                                    <li>
                                        <a href="<?php echo base_url() . 'login/logout' ?>">
                                            <i class="feather icon-log-out"></i>Log Out
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </li>
                    </ul>
                </div>
        </nav>

        <div class="pcoded-main-container">
            <div class="pcoded-wrapper">