<div class="col-12">
    
    <div class="dt-responsive table-responsive">
        <table id="multi-colum-dt" class="table table-striped table-bordered nowrap">
            <thead>
                <tr>
                    <th><font size="2px"><center>Kodeprod</center></th>
                    <th><font size="2px"><center>Namaprod</center></th>
                    <th><font size="2px"><center>Harga (apps)</center></th>
                    <th><font size="2px"><center>Harga (sds)</center></th>
                    <th><font size="2px"><center>Qty (apps)</center></th>
                    <th><font size="2px"><center>Qty (sds)</center></th>
                    <th><font size="2px"><center>Item Disc</center></th>
                    <th><font size="2px"><center>Total Price</center></th>
                    <th><font size="2px"><center>Disc Cabang</center></th>
                    <th><font size="2px"><center>Disc Principal</center></th>
                    <th><font size="2px"><center>Disc Extra</center></th>
                    <th><font size="2px"><center>Rp Cabang</center></th>
                    <th><font size="2px"><center>Rp Principal</center></th>
                    <th><font size="2px"><center>Rp Extra</center></th>
                    <th><font size="2px"><center>Bonus (apps)</center></th>
                    <th><font size="2px"><center>Bonus (sds)</center></th>
                </tr>
            </thead>
            <tbody>

                <?php foreach($transaksi as $x) : ?>
                <tr>
                    <td><font size="2px"><?php echo $x->kodeprod; ?></font></td>
                    <td><font size="2px"><?php echo $x->namaprod; ?></font></td>
                    <td><font size="2px"><?php echo $x->harga_product; ?></font></td>
                    <td><font size="2px"><?php echo $x->harga_product_konversi; ?></font></td>
                    <td><font size="2px"><?php echo $x->small_qty; ?></font></td>
                    <td><font size="2px"><?php echo $x->qty_konversi; ?></font></td>
                    <td><font size="2px"><?php echo $x->item_disc; ?></font></td>
                    <td><font size="2px"><?php echo $x->total_price; ?></font></td>
                    <td><font size="2px"><?php echo $x->disc_cabang; ?></font></td>
                    <td><font size="2px"><?php echo $x->disc_principal; ?></font></td>
                    <td><font size="2px"><?php echo $x->disc_extra; ?></font></td>
                    <td><font size="2px"><?php echo $x->rp_cabang; ?></font></td>
                    <td><font size="2px"><?php echo $x->rp_principal; ?></font></td>
                    <td><font size="2px"><?php echo $x->rp_extra; ?></font></td>
                    <td><font size="2px"><?php echo $x->bonus; ?></font></td>
                    <td><font size="2px"><?php echo $x->bonus_konversi; ?></font></td>
                  
                </tr>
                <?php endforeach; ?>

            </tbody>
        </table>
    </div>
</div>
