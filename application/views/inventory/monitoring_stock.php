<div class="content-body">

            <div class="row page-titles mx-0">
                <div class="col p-md-0">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="javascript:void(0)">Dashboard</a></li>
                        <li class="breadcrumb-item active"><a href="javascript:void(0)">Home</a></li>
                    </ol>
                </div>
            </div>
            <!-- row -->

            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <h4 class="card-title"><?php echo $title; ?> </h4>
                                <?php foreach ($get_max_updated as $key) { ?>  
                                    <?php echo "di proses oleh : ".$key->username; ?>
                                    <?php echo ", pada : ".$key->tanggal_max; ?>
                                <?php } ?>

                                <hr>
                                <a href="<?php echo base_url()."inventory_old/export_monitoring_stock_deltomed/" ?>" class="btn btn-primary" role="button"><span class="glyphicon glyphicon-print">Export</a>
                                <?php if ($id == 297 || $id == 515) { ?>
                                    <a href="<?php echo base_url()."inventory_old/update_monitoring_stock_deltomed/" ?>" class="btn btn-warning" role="button"><span class="glyphicon glyphicon-refresh"><font color="white">Update</font></a>
                                <?php }else{ ?>

                                <?php } ?>
                                
                                                      

                                <hr>
                                <div class="table-responsive">
                                    <table class="table table-striped table-bordered zero-configuration">
                                        <thead>
                                            <tr>
                                                <th>Branch</th>         
                                                <th>SubBranch</th> 
                                                <th>Group</th>
                                                <th>Kodeprod</th>                                                    
                                                <th>Namaprod</th>    
                                                <th>Avg</th>  
                                                <th>Stock</th> 
                                                <th>PoOutstanding</th> 
                                                <th>TotalStock</th>  
                                                <th>SalesBerjalan</th> 
                                                <th>Target</th> 
                                                <th>PotensiSales</th> 
                                                <th>StockBerjalan</th> 
                                                <th>Doi</th> 
                                                <th>StockIdealHari</th> 
                                                <th>StockIdealUnit</th> 
                                                <th>SuggestPo</th> 
                                            </tr>
                                        </thead>
                                        <tbody>
                                        <?php foreach ($get_monitoring_stock as $key) { ?>
                                            <tr>           
                                                <td><?php echo $key->branch_name; ?></td>                                     
                                                <td><?php echo $key->nama_comp; ?></td>
                                                <td><?php echo $key->nama_group; ?></td>
                                                <td><?php echo $key->kodeprod; ?></td>
                                                <td><?php echo $key->namaprod; ?></td>
                                                <td><?php echo $key->avg_unit; ?></td>
                                                <td><?php echo $key->stock_unit; ?></td>
                                                <td><?php echo $key->unit_po_outstanding; ?></td>
                                                <td><?php echo $key->total_stock; ?></td>
                                                <td><?php echo $key->unit_omzet_berjalan; ?></td>
                                                <td><?php echo $key->unit_target; ?></td>
                                                <td><?php echo $key->potensi_sales; ?></td>
                                                <td><?php echo $key->stock_berjalan; ?></td>
                                                <td><?php echo $key->doi; ?></td>
                                                <td><?php echo $key->stock_ideal_hari; ?></td>
                                                <td><?php echo $key->stock_ideal_unit; ?></td>
                                                <td><?php echo $key->suggest_po; ?></td>
                                            </tr>
                                        <?php } ?>
                                            
                                        <?php  ?>
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <th>Branch</th>         
                                                <th>SubBranch</th> 
                                                <th>Group</th>
                                                <th>Kodeprod</th>                                                    
                                                <th>Namaprod</th>    
                                                <th>Avg</th>  
                                                <th>Stock</th> 
                                                <th>PoOutstanding</th> 
                                                <th>TotalStock</th>  
                                                <th>SalesBerjalan</th> 
                                                <th>Target</th> 
                                                <th>PotensiSales</th> 
                                                <th>StockBerjalan</th> 
                                                <th>Doi</th> 
                                                <th>StockIdealHari</th> 
                                                <th>StockIdealUnit</th> 
                                                <th>SuggestPo</th> 
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- #/ container -->
        </div>

