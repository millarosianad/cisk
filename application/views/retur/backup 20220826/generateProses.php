<div class="col-sm-12">
        - periode : <b> <?php echo $periode_1.' - '.$periode_2; ?> </b> <br>
</div>

        
<div class="card-block">

    <div class="col-sm-10">
        <div class="form-group row">            
            <div class="col-sm-10">
                <a href="<?php echo base_url()."retur/generate/"; ?>"class="btn btn-dark" role="button">
                kembali</a>
                <a href="<?php echo base_url()."retur/exportGenerate"; ?>"class="btn btn-warning" role="button">
                <span class="glyphicon glyphicon-print" aria-hidden="true"></span> export (csv)</a>        
            </div>
            <div class="col-sm-5"></div>
        </div>
    </div>
</div>

    <hr>
    
    <div class="card-block">

    <div class="dt-responsive table-responsive">
        <table id="multi-colum-dt" class="table table-striped table-bordered nowrap">
            <thead>
                <tr>
                    <th>Company</th>
                    <th>Principal</th>
                    <th>Nodo Beli</th> 
                    <th>TglDoBeli</th> 
                    <th>Bruto</th> 
                    <th>Disc</th>                     
                    <th>Dpp</th>                     
                </tr>
            </thead>
            <tbody>

            <?php 
            // var_dump($proses);
            foreach($proses as $a) : ?>
                <tr>                 
                    <td><font size="2px"><?php echo $a->company; ?></td>
                    <td><font size="2px"><?php echo $a->namasupp; ?></td>
                    <td><font size="2px"><?php echo $a->nodo_beli; ?></td>
                    <td><font size="2px"><?php echo $a->tgldo_beli; ?></td>
                    <td><font size="2px"><?php echo number_format($a->bruto); ?></td>
                    <td><font size="2px"><?php echo number_format($a->disc); ?></td>
                    <td><font size="2px"><?php echo number_format($a->dpp); ?></td>
                </tr>
            <?php endforeach; ?>
            
            </tbody>
            <tfoot>
                <tr>
                    <th>Company</th>
                    <th>Principal</th>
                    <th>Nodo Beli</th> 
                    <th>TglDoBeli</th> 
                    <th>Bruto</th> 
                    <th>Disc</th>                     
                    <th>Dpp</th>     
                </tr>
            </tfoot>
        </table>
    </div>
    </div>
</div>







