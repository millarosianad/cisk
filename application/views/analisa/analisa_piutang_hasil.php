<div class="col-sm-10">
    <a href="<?php echo base_url()."analisa/analisa_piutang"; ?>"class="btn btn-dark" role="button">Kembali</a>
    <a href="<?php echo base_url()."analisa/export/"; ?>" class="btn btn-warning" role="button">Export (.csv)</a>
    <a href="<?php echo base_url()."analisa/export_detail/"; ?>" class="btn btn-success" role="button">Export Detail</a>
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
                    <th width="1"><font size="2px">No</font></th>
                    <th><font size="2px">Group Customer</th>
                    <th><font size="2px">Kode Pelanggan</th>
                    <th><font size="2px">Saldo Awal</th>
                    <th><font size="2px">Debit</th>
                    <th><font size="2px">Kredit</th>
                    <th><font size="2px">Current</th>
                    <th><font size="2px">Due Date</th>
                    <th><font size="2px">belum jatuh tempo</th>
                    <th><font size="2px">1-7</th>
                    <th><font size="2px">8-15</th>
                    <th><font size="2px">16-30</th>
                    <th><font size="2px">31-45</th>
                    <th><font size="2px">46-60</th>
                    <th><font size="2px">>60</th>
                    <th><font size="2px">total</th>
                </tr>
            </thead>
            <tbody>
                <?php $no =1;
                foreach($proses as $a) : ?>
                    <tr>        
                        <td><center><font size="2px"><?php echo $no++; ?></font></center></td>               
                        <td><font size="2px"><?php echo $a->group_descr; ?></td>
                        <td><font size="2px"><?php echo $a->customerid; ?></td>
                        <td><font size="2px"><?php echo number_format($a->saldoawal); ?></td>
                        <td><font size="2px"><?php echo number_format($a->debit); ?></td>
                        <td><font size="2px"><?php echo number_format($a->kredit); ?></td>
                        <td><font size="2px"><?php echo number_format($a->current); ?></td>
                        <td><font size="2px"><?php echo number_format($a->duedate); ?></td>
                        <td><font size="2px"><?php echo number_format($a->a); ?></td>
                        <td><font size="2px"><?php echo number_format($a->b); ?></td>
                        <td><font size="2px"><?php echo number_format($a->c); ?></td>
                        <td><font size="2px"><?php echo number_format($a->d); ?></td>
                        <td><font size="2px"><?php echo number_format($a->e); ?></td>
                        <td><font size="2px"><?php echo number_format($a->f); ?></td>
                        <td><font size="2px"><?php echo number_format($a->g); ?></td>
                        <td><font size="2px"><?php echo number_format($a->total); ?></td>
                    </tr>
                <?php endforeach; ?>           
            </tbody>
            <tfoot> 
                <tr>                
                    <th width="1"><font size="2px">No</font></th>
                    <th><font size="2px">Group Customer</th>
                    <th><font size="2px">Kode Pelanggan</th>
                    <th><font size="2px">Saldo Awal</th>
                    <th><font size="2px">Debit</th>
                    <th><font size="2px">Kredit</th>
                    <th><font size="2px">Current</th>
                    <th><font size="2px">Due Date</th>
                    <th><font size="2px">belum jatuh tempo</th>
                    <th><font size="2px">1-7</th>
                    <th><font size="2px">8-15</th>
                    <th><font size="2px">16-30</th>
                    <th><font size="2px">31-45</th>
                    <th><font size="2px">46-60</th>
                    <th><font size="2px">>60</th>
                    <th><font size="2px">total</th>
                </tr>
            </tfoot>                    
        </table>
    </div> 
</div>