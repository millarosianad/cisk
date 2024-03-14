<div class="col-sm-10">
    - Periode : <?php echo $from. " - ".$to; ?> <br>
    - Output versi : <?php echo $output; ?><hr>
    <br>
    <br>
    <a href="<?php echo base_url()."sales_omzet/omzet"; ?>"class="btn btn-dark" role="button">kembali</a>
    <a href="<?php echo base_url()."sales_omzet/".$url_export; ?>" class="btn btn-warning" role="button">export (.csv)</a>
</div>
    <br>
    </div>

    <div class="card table-card">
        <div class="card-header">
        <div class="card-block">
        <div class="dt-responsive table-responsive">
            <table id="multi-colum-dt" class="table table-striped table-bordered nowrap">    
                    <thead>
                        <tr>                
                            <th width="1"><font size="2px">Principal</th>
                            <th><font size="2px">Bulan</th>
                            <th><font size="2px">Unit</th>
                            <th><font size="2px">Omzet</th>
                            <th><font size="2px">OT</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php foreach($proses as $a) : ?>
                        <tr>                      
                            <td><font size="2px"><?php echo $a->namasupp; ?></td>
                            <td><font size="2px"><?php echo $a->bulan; ?></td>
                            <td><font size="2px"><?php echo number_format($a->unit); ?></td>
                            <td><font size="2px"><?php echo number_format($a->omzet); ?></td>
                            <td><font size="2px"><?php echo number_format($a->ot); ?></td>
                        </tr>
                    <?php endforeach; ?>
                    
                    </tbody>
                    </table>
                    </div>
                    <div class="col-xs-11">&nbsp; </div>
        </div>
    </div>
    </body>
</html>