<nav class="pcoded-navbar">
    <div class="nav-list">
        <div class="pcoded-inner-navbar main-menu"><a href="<?php echo base_url() ?>dashboard_dummy">
        <div class="pcoded-navigation-label"><i class="feather icon-home"></i> 
        
            Dashboard
        
        </div></a>
        <?php   
        foreach ($get_label as $key) 
        { 
            ?>
            <div class="pcoded-navigation-label"><?php echo $key->nama_label; ?></div>

            <?php 
                $id = $this->session->userdata('id');
                $menu = "
                    select  a.id_menu,a.nama_menu,a.parent,a.target, a.`level`, a.id_label, b.alias
                    from    db_menu.t_menu_temp a left join db_menu.t_menu b on a.id_menu = b.id_menu
                    where   a.userid = $id and `level` = 2 and a.id_label = $key->id_label and created_date = (
                        select max(a.created_date)
                        from db_menu.t_menu_temp a
                        where a.userid = $id
                    )
                    GROUP BY a.id_menu
                    ORDER BY a.nama_menu, level asc
                ";
                // echo "<pre>";
                // print_r($menu);
                // echo "</pre>";
                $menux = $this->db->query($menu)->result();
                foreach ($menux as $a) { ?>

                <ul class="pcoded-item pcoded-left-item">
                    <?php 
                        $url = $this->uri->segment('1');
                        if ($url == strtolower($a->alias)) { ?>
                            <li class="pcoded-hasmenu active pcoded-trigger">
                        <?php
                        }else{ ?>
                            <li class="pcoded-hasmenu">
                        <?php    
                        }
                    ?>
                    
                        <a href="javascript:void(0)" class="waves-effect waves-dark">
                            <span class="pcoded-micon"><i class="feather icon-sidebar"></i></span>
                            <span class="pcoded-mtext"><?php echo $a->nama_menu; ?></span>
                        </a>

                        <?php 
                        $menu2 = "
                                select  a.id_menu, a.nama_menu, a.target, b.alias
                                from    db_menu.t_menu_temp a LEFT JOIN db_menu.t_menu b on a.id_menu = b.id_menu
                                where   a.userid = $id and a.id_label = $key->id_label and a.parent = $a->id_menu and `level` = 3 and created_date = (
                                    select max(a.created_date)
                                    from db_menu.t_menu_temp a
                                    where a.userid = $id
                                )
                                GROUP BY id_menu
                                order by nama_menu asc
                        ";
                        $menuy = $this->db->query($menu2)->result();
                            // echo "<pre>";
                            // print_r($menu2);
                            // echo "</pre>";

                        if ($menuy) {
                            // echo "aaaa";
                        }else{
                            // echo "bbbb";
                            $menu2 = "
                                select  a.id_menu, a.nama_menu, a.target, b.alias
                                from    db_menu.t_menu_temp a LEFT JOIN db_menu.t_menu b on a.id_menu = b.id_menu
                                where   a.userid = $id and a.id_label = $key->id_label and a.parent = $a->id_menu and `level` = 4 and created_date = (
                                    select max(a.created_date)
                                    from db_menu.t_menu_temp a
                                    where a.userid = $id
                                )
                                GROUP BY id_menu
                                order by nama_menu asc
                            ";
                            $menuy = $this->db->query($menu2)->result();
                            // echo "<pre>";
                            // print_r($menu2);
                            // echo "</pre>";
                        }

                        foreach ($menuy as $b) { ?>

                        <ul class="pcoded-submenu">
                            <?php 
                                // echo "alias : ".$b->alias;
                                if ($b->target == '#' || $b->target == '') { 
                                $url = $this->uri->segment('2');
                                if ($b->alias == strtolower($url) ) { ?>
                                    <li class="pcoded-hasmenu active pcoded-trigger">
                                <?php    
                                }else{ ?>
                                    <li class="pcoded-hasmenu">
                                <?php
                                }                                    
                                ?>                                    
                                        <a href="javascript:void(0)" class="waves-effect waves-dark">
                                <?php    
                                }else{ 
                                    if ($b->alias == strtolower($b->nama_menu) ) { ?>
                                        <li class="pcoded-hasmenu active pcoded-trigger">
                                        <a href="<?php echo base_url().$b->target; ?>" class="waves-effect waves-dark">
                                    <?php    
                                    }else{ ?>
                                        <li class="pcoded-hasmenu">
                                        <a href="<?php echo base_url().$b->target; ?>" class="waves-effect waves-dark">
                                    <?php
                                    }                                    
                                    ?>
                                <?php
                                }
                            ?>                            
                                    <span class="pcoded-mtext"><?php echo $b->nama_menu; ?></span>
                                </a>

                                <?php 
                                $menu3 = "select a.id_menu, a.nama_menu, a.target, b.alias
                                        from db_menu.t_menu_temp a left join db_menu.t_menu b on a.id_menu = b.id_menu
                                        where a.userid = $id and a.id_label = $key->id_label and a.parent = $b->id_menu and created_date = (
                                            select max(a.created_date)
                                            from db_menu.t_menu_temp a
                                            where a.userid = $id
                                        )
                                        order by nama_menu asc
                                ";
                                $menuz = $this->db->query($menu3)->result();
                                foreach ($menuz as $c) { ?>

                                <ul class="pcoded-submenu">
                                    <?php 
                                        $url = $this->uri->segment('2');
                                        if ($c->alias == strtolower($url)) { ?>
                                            <li class="active">
                                        <?php
                                        }else{ ?>
                                            <li class="">
                                        <?php
                                        }

                                    ?>
                                    
                                        <a href="<?php echo base_url().$c->target; ?>" class="waves-effect waves-dark">
                                            <span class="pcoded-mtext"><?php echo $c->nama_menu; ?></span>
                                        </a>
                                    </li>
                                </ul>
                                <?php     
                                }?>
                            </li>
                        </ul>
                        <?php     
                        }?>                        
                    </li>
                </ul>                    
                <?php     
                }?>
            
            <?php 
        } ?>   
        
        
        <div class="pcoded-navigation-label">Kembali ke Halaman Awal</div>
            <ul class="pcoded-item pcoded-left-item">            
                <li class="pcoded-hasmenu ">
                    <a href="<?php echo base_url().'login/home'; ?>" class="waves-effect waves-dark">
                        <span class="pcoded-micon"><i class="feather icon-home"></i></span>
                        <span class="pcoded-mtext">Klik disini</span>
                        <span class="pcoded-badge label label-warning">Penting</span>
                    </a>
                </li>
            </ul>
        </div>













        </div>
    </div>
</nav>
