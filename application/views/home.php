
<?php
echo br(1);
//echo "<h2>Welcome To MPM Distribution</h2>";
//echo "<p><b>PT. MULIA PUTRA MANDIRI</b></p>";
//echo $map['js'];
//echo $map['html'];
//echo br()."Rukan Aries Niaga Blok A1 No.1 VWX";
//echo br()."Meruya Utara Jakarta Barat";
//echo br()."Telp : 021 58906892, Fax : 021 58906893";
//echo br()."Website : www.muliaputramandiri.com";

?>

<div class="container">

      <div class="row row-offcanvas row-offcanvas-right">

        <div class="col-xs-12 col-sm-9">
          <p class="pull-right visible-xs">
            <button type="button" class="btn btn-primary btn-xs" data-toggle="offcanvas">Toggle nav</button>
          </p>
          <div class="jumbotron">
            <h2>Welcome To MPM Distribution</h2><br>
            <h4>PT. MULIA PUTRA MANDIRI</h4>
            <h6>The Mahitala building</h6>
            <h6>Jalan Alam Utama No. 6, Alam Sutera – Tangerang</h6>
            <h6>Telp : 021 - 5317 0130</h6>
            <h6>Website : www.muliaputramandiri.com</h6>
          </div>


          <div class="row">
          	<!--
            <div class="col-xs-6 col-lg-4">
              <h2>Heading</h2>
              <p> 
              		<?php //$this->load->view('charts/view_kalender_data') ?>
              </p>
              <p><a class="btn btn-default" href="#" role="button">View details &raquo;</a></p>
            </div>

           
            <div class="col-xs-6 col-lg-4">
              <h2>Heading</h2>
              <p>Donec id elit non mi porta gravida at eget metus. Fusce dapibus, tellus ac cursus commodo, tortor mauris condimentum nibh, ut fermentum massa justo sit amet risus. Etiam porta sem malesuada magna mollis euismod. Donec sed odio dui. </p>
              <p><a class="btn btn-default" href="#" role="button">View details &raquo;</a></p>
            </div>
            <div class="col-xs-6 col-lg-4">
              <h2>Heading</h2>
              <p>Donec id elit non mi porta gravida at eget metus. Fusce dapibus, tellus ac cursus commodo, tortor mauris condimentum nibh, ut fermentum massa justo sit amet risus. Etiam porta sem malesuada magna mollis euismod. Donec sed odio dui. </p>
              <p><a class="btn btn-default" href="#" role="button">View details &raquo;</a></p>
            </div>
            <div class="col-xs-6 col-lg-4">
              <h2>Heading</h2>
              <p>Donec id elit non mi porta gravida at eget metus. Fusce dapibus, tellus ac cursus commodo, tortor mauris condimentum nibh, ut fermentum massa justo sit amet risus. Etiam porta sem malesuada magna mollis euismod. Donec sed odio dui. </p>
              <p><a class="btn btn-default" href="#" role="button">View details &raquo;</a></p>
            </div>
            <div class="col-xs-6 col-lg-4">
              <h2>Heading</h2>
              <p>Donec id elit non mi porta gravida at eget metus. Fusce dapibus, tellus ac cursus commodo, tortor mauris condimentum nibh, ut fermentum massa justo sit amet risus. Etiam porta sem malesuada magna mollis euismod. Donec sed odio dui. </p>
              <p><a class="btn btn-default" href="#" role="button">View details &raquo;</a></p>
            </div>
            <div class="col-xs-6 col-lg-4">
              <h2>Heading</h2>
              <p>Donec id elit non mi porta gravida at eget metus. Fusce dapibus, tellus ac cursus commodo, tortor mauris condimentum nibh, ut fermentum massa justo sit amet risus. Etiam porta sem malesuada magna mollis euismod. Donec sed odio dui. </p>
              <p><a class="btn btn-default" href="#" role="button">View details &raquo;</a></p>
            </div>-->
          </div>
        </div>

        <div class="col-xs-6 col-sm-3 sidebar-offcanvas" id="sidebar">
          <div class="list-group">
            <a class="list-group-item active">User Login</a>
            <a class="list-group-item">UserID : <?php echo $username; ?></a>
            <a class="list-group-item">Nama : <?php echo $company; ?></a>
            <a class="list-group-item">Email : <?php echo $email; ?></a>
            <a class="list-group-item">Alamat : <?php echo $address; ?></a>
            <a href="<?php echo base_url() ?>user/account" class="list-group-item" class="btn btn-default" role="button"><b> <center>&raquo; Ubah Data &raquo;</center></b></a>
          </div>
        </div><!--/.sidebar-offcanvas-->
      </div><!--/row-->

      <hr>

    </div><!--/.container-->