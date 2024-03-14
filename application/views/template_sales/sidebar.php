<nav class="pcoded-navbar">
    <div class="nav-list">
        <div class="pcoded-inner-navbar main-menu">
            <div class="pcoded-navigation-label">Report Sales</div>
            <ul class="pcoded-item pcoded-left-item">
                <li class="pcoded-hasmenu active pcoded-trigger">
                    <a href="javascript:void(0)" class="waves-effect waves-dark">
                        <span class="pcoded-micon"><i class="feather icon-home"></i></span>
                        <span class="pcoded-mtext">Program Loyalty</span>
                    </a>
                    
                    <?php $aktif = $this->uri->segment('2');?>
                    <ul class="pcoded-submenu">
                        <li class="<?php if($aktif == "ant_group"){ echo "active"; } ?>">
                            <a href="<?php echo base_url()?>report_sales/ant_group" class="waves-effect waves-dark">
                                <span class="pcoded-mtext">Ant Group</span>
                            </a>
                        </li>
                        <li class="<?php if($aktif == "candy"){ echo "active"; } ?>">
                            <a href="<?php echo base_url()?>report_sales/candy" class="waves-effect waves-dark">
                                <span class="pcoded-mtext">Candy</span>
                            </a>
                        </li>
                        <li class="<?php if($aktif == "ob_herbal"){ echo "active"; } ?>">
                            <a href="<?php echo base_url()?>report_sales/ob_herbal" class="waves-effect waves-dark">
                                <span class="pcoded-mtext">OB Herbal</span>
                            </a>
                        </li>
                    </ul>
                </li>

                <li class="pcoded-hasmenu active pcoded-trigger">
                    <a href="<?php echo base_url()?>login/home" class="waves-effect waves-dark">
                        <span class="pcoded-micon"><i class="feather icon-home"></i></span>
                        <span class="pcoded-mtext">Kembali ke Menu Awal</span>
                    </a>
                </li>

            </ul>
        </div>
    </div>
</nav>