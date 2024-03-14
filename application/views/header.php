<header class="navbar navbar-inverse navbar-fixed-top">
    
    <div class="container">
        <div class="navbar-header">
            <button data-target=".navbar-collapse" data-toggle="collapse" class="navbar-toggle" type="button">
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a href="<?php echo site_url('login/home'); ?>" class="navbar-brand">PT. MPM</a>
        </div>
        <nav class="collapse navbar-collapse">
            <ul class="nav navbar-nav">
                <?php 
                if(isset($menu)){ 
                    $group='';
                    //$this->load->library('database');
                    foreach ($menu->result() as $row)
                    {
                        if($group!=(strtoupper($row->groupname)))
                        {
                            if($group!='')
                            {
                                echo '</ul></li>';
                            }
                            $group= ucfirst(strtoupper($row->groupname));
                            ?>
                            <li class="dropdown">
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown"><?php echo $group?> <b class="caret"></b></a>
                                    <ul class='dropdown-menu'>
                        <?php
                        }
                        echo '<li><a href="'.site_url($row->target).'">'.(strtolower($row->menuview)).'</a></li>'; 
                    }
                ?>
                
                <!--li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">Dropdown <b class="caret"></b></a>
                    <ul class='dropdown-menu'>
                        <li><a href="#">Link #1</a></li>
                        <li><a href="#">Link #1</a></li-->
                    </ul>
                </li>
                <li><a href="<?php echo site_url('login/logout'); ?>">LOGOUT</a></li>
                <?php }?>
            </ul>
        </nav>
    </div>
</header>