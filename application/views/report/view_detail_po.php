<?php 
    //$id_ref = $reports->id_ref;
?>
<!doctype html>
<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title>Admin Page | View List PO</title>
        <!-- Load Jquery, Bootstrap, dan DataTables dari CDN -->
        <!-- buka url ini: http://pastebin.com/index/WeaY5Fra -->
        <!-- load Jquery dari CDN -->
        <script type="text/javascript" language="javascript" src="//code.jquery.com/jquery-1.10.2.min.js"></script>
        
        <!-- Load Datatables dan Bootstrap dari CDN -->
        <script type="text/javascript" language="javascript" src="//cdn.datatables.net/1.10.4/js/jquery.dataTables.min.js"></script>
        <script type="text/javascript" language="javascript" src="//cdn.datatables.net/plug-ins/9dcbecd42ad/integration/bootstrap/3/dataTables.bootstrap.js"></script>
        
        <link rel="stylesheet" type="text/css" href="//netdna.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
        <link rel="stylesheet" type="text/css" href="//cdn.datatables.net/plug-ins/9dcbecd42ad/integration/bootstrap/3/dataTables.bootstrap.css">

        <!-- Load jQuery from Google's CDN -->
        <!-- Load jQuery UI CSS  -->
        <link rel="stylesheet" href="http://code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css" />
        
        <!-- Load jQuery JS -->
        <script src="http://code.jquery.com/jquery-1.9.1.js"></script>
        <!-- Load jQuery UI Main JS  -->
        <script src="http://code.jquery.com/ui/1.10.3/jquery-ui.js"></script>
        
        <!-- Load SCRIPT.JS which will create datepicker for input field  -->
        <script src="<?php echo base_url() ?>assets/js/script.js"></script>


    </head>
    <body>
   
    <div class="row">        
        <div class="col-xs-16">
            
            <h3>Detail PO</h3><hr />
            <form class = "form-horizontal">
            <?php foreach($singles as $single) : ?>
              <div class="form-group">
                <label for="inputEmail3" class="col-sm-2 control-label">Nomor Order</label>
                <div class="col-sm-10">
                  <input type="text" class="form-control" name="inputEmail3" value ="<?php echo $single->id_ref ?>"
                  readonly>
                </div>
              </div>

            <div class="form-group">
                <label for="inputEmail3" class="col-sm-2 control-label">Nomor Purchase Order</label>
                <div class="col-sm-10">
                  <input type="email" class="form-control" id="inputEmail3" value ="<?php echo $single->nopo ?>" readonly>
                </div>
            </div>

            <div class="form-group">
                <label for="inputEmail3" class="col-sm-2 control-label">Tanggal Order</label>
                <div class="col-sm-10">
                  <input type="email" class="form-control" id="inputEmail3" value ="<?php echo $single->tglpo ?>" readonly>
                </div>
            </div>

            <div class="form-group">
                <label for="inputEmail3" class="col-sm-2 control-label">Nama Perusahaan</label>
                <div class="col-sm-10">
                  <input type="email" class="form-control" id="inputEmail3" value ="<?php echo $single->company ?>" readonly>
                </div>
            </div>

            <div class="form-group">
                <label for="inputEmail3" class="col-sm-2 control-label">Note</label>
                <div class="col-sm-10">
                    <textarea class="form-control" readonly><?php echo $single->note ?></textarea>
                </div>
            </div>

            </form>
             <?php endforeach; ?>
            <hr>
            <h4>
            <p>Silahkan isi data pengiriman barang di bawah ini (<b><i>hanya dapat diisi oleh Principal</i></b>)</p>
            </h4><br>
            <?php echo form_open('all_report/proses/' . $single->id_ref, "class='form-horizontal'") ?>
            <font color ="red">
            <div>
                <?php echo validation_errors(); ?>
            </div>
            </font>
            <div class="form-group">
                <label for="inputEmail3" class="col-sm-2 control-label">Nomor Delivery Order / No Surat Jalan</label>
                <div class="col-sm-10">
                  <input type="text" class="form-control" name="nodo" placeholder="Masukkan Nomor Surat Jalan">
                </div>
            </div>
            <div class="form-group">
                <label for="inputEmail3" class="col-sm-2 control-label">Tanggal Pengiriman</label>
                <div class="col-sm-10">
                  <input type="text" class="form-control" id="datepicker" name="tgldo" placeholder="Masukkan Tanggal Pengiriman">
               
                </div>
            </div>

            <div class="form-group">
                <div class="col-sm-offset-2 col-sm-10">
                  <button type="submit" class="btn btn-primary">Submit</button>
                  
                  
                </div>
            </div>
            <?php echo form_close(); ?>

            <hr />


        </div>
    </div>

    
    

    <?php $no = 1 ; ?> 
    <div class="row">      
        <div class="col-xs-19">
        <center>
        <h3>List Produk</h3>
        </center>
            <table id="myTable" class="table table-striped table-bordered table-hover">     
                    <thead>
                        <tr>                
                            <th>No</th>
                            <th>Kode Produk</th>
                            <th>Nama Produk </th>
                            <th>Jumlah</th>
                                              
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($reports as $report) : ?>
                        <tr>        
                            <td><?php echo $no; ?></td>
                            <td><?php echo $report->kodeprod; ?></td>
                            <td><?php echo $report->namaprod; ?></td>
                            <td><?php echo $report->banyak; ?></td>                              
                        </tr>

                        <?php $no++; ?>
                    
                    <?php endforeach; ?>

                    </tbody>
                    </table>
        </div> 
    </div>
        
       <script>
    $(document).ready(function(){
        $('#myTable').DataTable( {
            
        });
    });
    </script>
    <!--jquery dan select2-->
    <script src="<?php echo base_url('assets/js/jquery-1.11.2.min.js') ?>"></script>
    <script src="<?php echo base_url('assets/select2/js/select2.min.js') ?>"></script>
    <script>
        $(document).ready(function () {
            $(".select2").select2({
                placeholder: "Please Select"
            });
        });
    </script>
    </body>
</html>