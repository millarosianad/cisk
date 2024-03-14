<div class="col-sm-10">
    - periode master outlet  : <b> <?php echo $from_outlet.' s/d '.$to_outlet; ?> </b> <br>
    - periode otsc  : <b> <?php echo $from_otsc.' s/d '.$to_otsc; ?> </b> <br>
    - Kodeprod : <b> <?php echo $kodeprod; ?><hr>
    Informasi !! : menu ini menampilkan nilai ot baru yang belum pernah bertransaksi selama periode master outlet
    <br>
    <br>
    <a href="<?php echo base_url()."outlet_transaksi/otsc"; ?>"class="btn btn-dark" role="button">
                kembali</a>
    <a href="<?php echo base_url()."outlet_transaksi/otsc_export"; ?>" class="btn btn-warning" role="button">export otsc (.csv)</a><a href="<?php echo base_url()."outlet_transaksi/otsc_master_export"; ?>" class="btn btn-danger" role="button">export master outlet (.csv)</a>
    </div>
    <br>
    
<?php $no = 1; ?>
<div class="card table-card">
    <div class="card-header">
     <div class="card-block">
         <div class="dt-responsive table-responsive">
           <table id="multi-colum-dt" class="table table-striped table-bordered nowrap">    
            <thead>
                <tr>                
                    <th width="1"><font size="2px">SubBranch</font></th>   
                    <th width="1"><font size="2px">OT</font></th>
                    <th width="1"><font size="2px">1</font></th> 
                    <th width="1"><font size="2px">2</font></th> 
                    <th width="1"><font size="2px">3</font></th> 
                    <th width="1"><font size="2px">4</font></th> 
                    <th width="1"><font size="2px">5</font></th> 
                    <th width="1"><font size="2px">6</font></th> 
                    <th width="1"><font size="2px">7</font></th> 
                    <th width="1"><font size="2px">8</font></th> 
                    <th width="1"><font size="2px">9</font></th> 
                    <th width="1"><font size="2px">10</font></th>
                    <th width="1"><font size="2px">11</font></th>
                    <th width="1"><font size="2px">12</font></th>
                    <th width="1"><font size="2px">13</font></th>
                    <th width="1"><font size="2px">14</font></th>
                    <th width="1"><font size="2px">15</font></th>
                    <th width="1"><font size="2px">16</font></th>
                    <th width="1"><font size="2px">17</font></th>
                    <th width="1"><font size="2px">18</font></th>
                    <th width="1"><font size="2px">19</font></th>
                    <th width="1"><font size="2px">20</font></th> 
                    <th width="1"><font size="2px">21</font></th> 
                    <th width="1"><font size="2px">22</font></th> 
                    <th width="1"><font size="2px">23</font></th> 
                    <th width="1"><font size="2px">24</font></th> 
                    <th width="1"><font size="2px">25</font></th> 
                    <th width="1"><font size="2px">26</font></th> 
                    <th width="1"><font size="2px">27</font></th> 
                    <th width="1"><font size="2px">28</font></th> 
                    <th width="1"><font size="2px">29</font></th> 
                    <th width="1"><font size="2px">30</font></th> 
                    <th width="1"><font size="2px">31</font></th> 
                </tr>
            </thead>
            <tbody>
            <?php foreach($proses as $x) : ?>
                <tr>                      
                    <td><?php echo $x->nama_comp; ?></td>                    
                    <td><font size="2px"><?php echo $x->ot; ?></td>
                    <td><?php echo $x->tgl_1; ?></td>
                    <td><?php echo $x->tgl_2; ?></td>
                    <td><?php echo $x->tgl_3; ?></td>
                    <td><?php echo $x->tgl_4; ?></td>
                    <td><?php echo $x->tgl_5; ?></td>
                    <td><?php echo $x->tgl_6; ?></td>
                    <td><?php echo $x->tgl_7; ?></td>
                    <td><?php echo $x->tgl_8; ?></td>
                    <td><?php echo $x->tgl_9; ?></td>
                    <td><?php echo $x->tgl_10; ?></td>
                    <td><?php echo $x->tgl_11; ?></td>
                    <td><?php echo $x->tgl_12; ?></td>
                    <td><?php echo $x->tgl_13; ?></td>
                    <td><?php echo $x->tgl_14; ?></td>
                    <td><?php echo $x->tgl_15; ?></td>
                    <td><?php echo $x->tgl_16; ?></td>
                    <td><?php echo $x->tgl_17; ?></td>
                    <td><?php echo $x->tgl_18; ?></td>
                    <td><?php echo $x->tgl_19; ?></td>
                    <td><?php echo $x->tgl_20; ?></td>
                    <td><?php echo $x->tgl_21; ?></td>
                    <td><?php echo $x->tgl_22; ?></td>
                    <td><?php echo $x->tgl_23; ?></td>
                    <td><?php echo $x->tgl_24; ?></td>
                    <td><?php echo $x->tgl_25; ?></td>
                    <td><?php echo $x->tgl_26; ?></td>
                    <td><?php echo $x->tgl_27; ?></td>
                    <td><?php echo $x->tgl_28; ?></td>
                    <td><?php echo $x->tgl_29; ?></td>
                    <td><?php echo $x->tgl_30; ?></td>
                    <td><?php echo $x->tgl_31; ?></td>
                </tr>
            <?php endforeach; ?>
            </tbody>
            <tfoot>
                    <tr>                
                        <th width="1"><font size="2px">SubBranch</font></th>   
                        <th width="1"><font size="2px">OT</font></th>
                        <th width="1"><font size="2px">1</font></th> 
                        <th width="1"><font size="2px">2</font></th> 
                        <th width="1"><font size="2px">3</font></th> 
                        <th width="1"><font size="2px">4</font></th> 
                        <th width="1"><font size="2px">5</font></th> 
                        <th width="1"><font size="2px">6</font></th> 
                        <th width="1"><font size="2px">7</font></th> 
                        <th width="1"><font size="2px">8</font></th> 
                        <th width="1"><font size="2px">9</font></th> 
                        <th width="1"><font size="2px">10</font></th>
                        <th width="1"><font size="2px">11</font></th>
                        <th width="1"><font size="2px">12</font></th>
                        <th width="1"><font size="2px">13</font></th>
                        <th width="1"><font size="2px">14</font></th>
                        <th width="1"><font size="2px">15</font></th>
                        <th width="1"><font size="2px">16</font></th>
                        <th width="1"><font size="2px">17</font></th>
                        <th width="1"><font size="2px">18</font></th>
                        <th width="1"><font size="2px">19</font></th>
                        <th width="1"><font size="2px">20</font></th> 
                        <th width="1"><font size="2px">21</font></th> 
                        <th width="1"><font size="2px">22</font></th> 
                        <th width="1"><font size="2px">23</font></th> 
                        <th width="1"><font size="2px">24</font></th> 
                        <th width="1"><font size="2px">25</font></th> 
                        <th width="1"><font size="2px">26</font></th> 
                        <th width="1"><font size="2px">27</font></th> 
                        <th width="1"><font size="2px">28</font></th> 
                        <th width="1"><font size="2px">29</font></th> 
                        <th width="1"><font size="2px">30</font></th> 
                        <th width="1"><font size="2px">31</font></th> 
                    </tr>
            </tfoot>
            </table>
            </div>
        </div>
    </div>
</div>
