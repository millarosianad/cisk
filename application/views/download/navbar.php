<nav class="navbar navbar-default">
  <div class="container-fluid">
    <!-- Brand and toggle get grouped for better mobile display -->
    <div class="navbar-header">
      <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
        <span class="sr-only">Toggle navigation</span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </button>

      <?php //echo anchor(base_url().'login/home', 'Home', ['class' => 'navbar-brand']); ?>
      <?php //echo anchor(base_url().'download/all', 'Download', ['class' => 'navbar-brand']); ?>

      <?php echo anchor(base_url().'login/home', 'Home', array('class' => 'navbar-brand')); ?>
      <?php echo anchor(base_url().'download/all', 'Download', array('class' => 'navbar-brand')); ?>



    </div>
    <!-- Collect the nav links, forms, and other content for toggling -->
    <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
    

    </div><!-- /.navbar-collapse -->
  </div><!-- /.container-fluid -->
</nav>