<script type="text/javascript">       
    $(document).ready(function() 
    { 
        // console.log('b');
        $("#supp").click(function()
        {
            // console.log('a');
            $.ajax({
            url:"<?php echo base_url(); ?>omzet/buildgroup",    
            data: {kode_supp: $(this).val()},
            type: "POST",
            success: function(data){
                $("#group").html(data);
                }
            });
        });
    });            
</script>
<?php 
    $interval=date('Y')-2013;
    $year=array();
    $year['2020']='2020';
    for($i=1;$i<=$interval;$i++)
    {
        $year[''.$i+2013]=''.$i+2013;
    }

    $bulan = array(
        '1'  => 'Januari',
        '2'  => 'Februari',
        '3'  => 'Maret', 
        '4'  => 'April',
        '5'  => 'Mei',
        '6'  => 'Juni',
        '7'  => 'Juli',
        '8'  => 'Agustus',
        '9'  => 'September',
        '10'  => 'Oktober',
        '11'  => 'November',
        '12'  => 'Desember'               
    );

    $supplier=array();
    foreach($get_supp->result() as $value)
    {
        $supplier[$value->supp]= $value->namasupp;
    }    

    $group=array();
    $group['0']='--';  

    echo form_open($url);  
?>
          
    <div class="col-sm-10">
        - Tahun dan Bulan : <b> <?php echo $tahun.' - '.$bln; ?> </b> <br>
        - Supplier : <b> <?php echo $namasupp; ?> </b> dan Group Product : <b> <?php echo $namagroup; ?> </b> <hr>
        Informasi !! : Halaman ini hanya menampilkan nilai Unit. Untuk melihat OT dan Value, silahkan klik Export
    </div>
</div>
        
<div class="card-block">

    <div class="col-sm-10">
        <div class="form-group row">            
            <div class="col-sm-10">
                <a href="<?php echo base_url()."report_sales_per_hari/input_data_hari/"; ?>"class="btn btn-dark" role="button">
                kembali</a>
                <a href="<?php echo base_url()."report_sales_per_hari/export_sales_kodeprod_kode_type/"; ?>"class="btn btn-warning" role="button">
                <span class="glyphicon glyphicon-print" aria-hidden="true"></span> export (csv)</a>        
            </div>
            <div class="col-sm-5"></div>
        </div>
    </div>
</div>

    <div class="card-block">
    <div class="dt-responsive table-responsive">
        <table id="multi-colum-dt" class="table table-striped table-bordered nowrap">
            <thead>
                <tr>
                    <th colspan="2"><center>DP</center></th>
                    <th colspan="3"><center>Breakdown</center></th>
                    <th colspan="31">&nbsp;&nbsp;&nbsp;Tanggal</th>
                </tr>
                <tr>
                    <th>Branch</th>
                    <th>SB</th>
                    <th>Produk</th>
                    <th>Type</th>
                    <th>Nama Type</th>
                    <th>Sektor</th>
                    <th>1</th> 
                    <th>2</th> 
                    <th>3</th> 
                    <th>4</th> 
                    <th>5</th> 
                    <th>6</th> 
                    <th>7</th> 
                    <th>8</th> 
                    <th>9</th> 
                    <th>10</th> 
                    <th>11</th>
                    <th>12</th>
                    <th>13</th>
                    <th>14</th>
                    <th>15</th>
                    <th>16</th>
                    <th>17</th>
                    <th>18</th>
                    <th>19</th>
                    <th>20</th>
                    <th>21</th> 
                    <th>22</th> 
                    <th>23</th> 
                    <th>24</th> 
                    <th>25</th> 
                    <th>26</th> 
                    <th>27</th> 
                    <th>28</th> 
                    <th>29</th> 
                    <th>30</th> 
                    <th>31</th>                     
                </tr>
            </thead>
            <tbody>

            <?php foreach($proses as $a) : ?>
                <tr>                 
                    <td><font size="2px"><?php echo $a->branch_name; ?></td>
                    <td><font size="2px"><?php echo $a->nama_comp; ?></td>
                    <td><font size="2px"><?php echo $a->namaprod; ?></td>
                    <td><font size="2px"><?php echo $a->kode_type; ?></td
                    <td><font size="2px"><?php echo $a->nama_type; ?></td>
                    <td><font size="2px"><?php echo $a->sektor; ?></td>
                    <td><font size="2px"><?php echo $a->unit_1; ?></td>
                    <td><font size="2px"><?php echo $a->unit_2; ?></td>
                    <td><font size="2px"><?php echo $a->unit_3; ?></td>
                    <td><font size="2px"><?php echo $a->unit_4; ?></td>
                    <td><font size="2px"><?php echo $a->unit_5; ?></td>
                    <td><font size="2px"><?php echo $a->unit_6; ?></td>
                    <td><font size="2px"><?php echo $a->unit_7; ?></td>
                    <td><font size="2px"><?php echo $a->unit_8; ?></td>
                    <td><font size="2px"><?php echo $a->unit_9; ?></td>
                    <td><font size="2px"><?php echo $a->unit_10; ?></td>

                    <td><font size="2px"><?php echo $a->unit_11; ?></td>
                    <td><font size="2px"><?php echo $a->unit_12; ?></td>
                    <td><font size="2px"><?php echo $a->unit_13; ?></td>
                    <td><font size="2px"><?php echo $a->unit_14; ?></td>
                    <td><font size="2px"><?php echo $a->unit_15; ?></td>
                    <td><font size="2px"><?php echo $a->unit_16; ?></td>
                    <td><font size="2px"><?php echo $a->unit_17; ?></td>
                    <td><font size="2px"><?php echo $a->unit_18; ?></td>
                    <td><font size="2px"><?php echo $a->unit_19; ?></td>
                    <td><font size="2px"><?php echo $a->unit_20; ?></td>

                    <td><font size="2px"><?php echo $a->unit_21; ?></td>
                    <td><font size="2px"><?php echo $a->unit_22; ?></td>
                    <td><font size="2px"><?php echo $a->unit_23; ?></td>
                    <td><font size="2px"><?php echo $a->unit_24; ?></td>
                    <td><font size="2px"><?php echo $a->unit_25; ?></td>
                    <td><font size="2px"><?php echo $a->unit_26; ?></td>
                    <td><font size="2px"><?php echo $a->unit_27; ?></td>
                    <td><font size="2px"><?php echo $a->unit_28; ?></td>
                    <td><font size="2px"><?php echo $a->unit_29; ?></td>
                    <td><font size="2px"><?php echo $a->unit_30; ?></td>
                    <td><font size="2px"><?php echo $a->unit_31; ?></td>
                </tr>
            <?php endforeach; ?>
            
            </tbody>
            <tfoot>
                <tr>
                    <th>Branch</th>
                    <th>SB</th>
                    <th>Produk</th>
                    <th>Type</th>
                    <th>Nama Type</th>
                    <th>Sektor</th>
                    <th>1</th> 
                    <th>2</th> 
                    <th>3</th> 
                    <th>4</th> 
                    <th>5</th> 
                    <th>6</th> 
                    <th>7</th> 
                    <th>8</th> 
                    <th>9</th> 
                    <th>10</th> 
                    <th>11</th>
                    <th>12</th>
                    <th>13</th>
                    <th>14</th>
                    <th>15</th>
                    <th>16</th>
                    <th>17</th>
                    <th>18</th>
                    <th>19</th>
                    <th>20</th>
                    <th>21</th> 
                    <th>22</th> 
                    <th>23</th> 
                    <th>24</th> 
                    <th>25</th> 
                    <th>26</th> 
                    <th>27</th> 
                    <th>28</th> 
                    <th>29</th> 
                    <th>30</th> 
                    <th>31</th>    
                </tr>
            </tfoot>
        </table>
    </div>
    </div>
</div>