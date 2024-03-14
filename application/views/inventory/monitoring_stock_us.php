<div class="col-sm-12">
         </div>

        
<div class="card-block">

    <div class="col-sm-10">
        <div class="form-group row">   
        <?php 
            $id = $this->session->userdata('id');
            if ($id == '297' || $id == '589' || $id == '208') { ?>
                <div class="col-sm-10">                    
                    <?php 
                        echo anchor('inventory/clear_monitoring/', 'clear data',"class='btn btn-dark btn-md'");
                        echo anchor('inventory/monitoring_stock_us_proses/', 'generate purchase plan',"class='btn btn-primary btn-md'");
                        echo anchor('inventory/export_monitoring_stock/005', 'export (csv)',"class='btn btn-warning btn-md'");
                        echo anchor('inventory/monitoring_stock_us_proses_regenerate/005', 're-generate purchase plan',"class='btn btn-danger btn-md'");
                    ?> 
                </div>
            <?php    
            }else{ ?>
                <div class="col-sm-10">
                    <?php                        
                        echo anchor('inventory/export_monitoring_stock/005', 'export (csv)',"class='btn btn-warning btn-md'");
                        echo anchor('inventory/monitoring_stock_us_proses_regenerate/005', 're-generate purchase plan',"class='btn btn-danger btn-md'");
                    ?>  
                       
                </div>

            <?php
            }
        ?>         
            
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
                    <th><center>#</center></th>
                    <th>Branch</th>
                    <th>SubBranch</th>
                    <th>Group</th>
                    <th>Kodeprod</th>
                    <th>Namaprod</th>  
                    <th>Average</th>
                    <th>StockOnHand</th>
                    <th>GIT</th>
                    <th>TotalStock</th>
                    <th>EstSales</th>
                    <th>EstSaldoBerjalan</th>
                    <th>StockLevel</th>
                    <th>StockLevelUnit</th>
                    <th>PurchasePlan</th>
                    <th>EstSaldoAkhir</th>
                    <th>EstDoiAkhir</th>
                    <th>PpInKarton</th>
                    <th>PpInKg</th>
                    <th>Value</th>
                    <th>LastUpload</th>
                </tr>
            </thead>
            <tbody>

            <?php 
            // var_dump($proses);
            foreach($get_monitoring_stock_deltomed_last as $a) : ?>
                <tr>                 
                    <td><font size="2px">
                    <?php
                        echo anchor('inventory/update_monitoring_stock/' . $a->kode.'/'.$a->kodeprod.'/'.$a->supp, 'update',[
                            'class' => 'btn btn-primary btn-sm',
                            'target' => '_blank']);
                    ?>
                    </td>
                    <td><font size="2px"><?php echo $a->branch_name; ?></td>
                    <td><font size="2px"><?php echo $a->nama_comp; ?></td>
                    <td><font size="2px"><?php echo $a->nama_group; ?></td>
                    <td><font size="2px"><?php echo $a->kodeprod; ?></td>
                    <td><font size="2px"><?php echo $a->namaprod; ?></td>
                    <td><font size="2px"><?php echo $a->average; ?></td>
                    <td><font size="2px"><?php echo $a->stock_on_hand; ?></td>
                    <td><font size="2px"><?php echo $a->git; ?></td>
                    <td><font size="2px"><?php echo $a->total_stock; ?></td>
                    <td><font size="2px"><?php echo $a->est_sales; ?></td>
                    <td><font size="2px"><?php echo $a->est_saldo_berjalan; ?></td>
                    <td><font size="2px"><?php echo $a->stock_level; ?></td>
                    <td><font size="2px"><?php echo $a->stock_level_unit; ?></td>
                    <td><font size="2px"><?php echo $a->purchase_plan; ?></td>
                    <td><font size="2px"><?php echo $a->est_saldo_akhir; ?></td>
                    <td><font size="2px"><?php echo $a->est_doi_akhir; ?></td>
                    <td><font size="2px"><?php echo $a->pp_in_karton; ?></td>
                    <td><font size="2px"><?php echo $a->pp_in_kg; ?></td>
                    <td><font size="2px"><?php echo number_format($a->pp_in_value); ?></td>
                    <td><font size="2px"><?php echo $a->lastupload; ?></td>
                    
                </tr>
            <?php 
            endforeach; 
            ?>
            
            </tbody>
            <tfoot>
                <tr>
                    <th><center>#</center></th>
                    <th>Branch</th>
                    <th>SubBranch</th>
                    <th>Group</th>
                    <th>Kodeprod</th>
                    <th>Namaprod</th>  
                    <th>Average</th>
                    <th>StockOnHand</th>
                    <th>GIT</th>
                    <th>TotalStock</th>
                    <th>EstSales</th>
                    <th>EstSaldoBerjalan</th>
                    <th>StockLevel</th>
                    <th>StockLevelUnit</th>
                    <th>PurchasePlan</th>
                    <th>EstSaldoAkhir</th>
                    <th>EstDoiAkhir</th>
                    <th>PpInKarton</th>
                    <th>PpInKg</th>
                    <th>Value</th>
                    <th>LastUpload</th>
                </tr>
            </tfoot>
        </table>
    </div>
    </div>
</div>