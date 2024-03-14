
<?php echo form_open($url); ?>
<div class="col-xs-16">
    <a href="<?php echo base_url()."help/view_ticket"; ?>  " class="btn btn-dark" role="button"><span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span> View Ticket</a>
    <hr>
</div>
<div>
    <font color="red">
        <?php 
            echo validation_errors(); 
            echo br(1);
        ?>
    </font>
</div> 

<div class="card">

    <div class="col-sm-12">
        <div class="form-group row">
            <label class="col-sm-2 col-form-label">PO From</label>
            <div class="col-sm-9">
                <div class="col-sm-6">
                    <input class="form-control" type="date" name="from" id="from" value="<?php echo $from ?>" required />
                </div>
            </div>
        </div>
    </div>

    <div class="col-sm-12">
        <div class="form-group row">
            <label class="col-sm-2 col-form-label">PO To</label>
            <div class="col-sm-9">
                <div class="col-sm-6">
                    <input class="form-control" type="date" name="to" id="to" value="<?php echo $to; ?>" required />
                </div>
            </div>
        </div>
    </div>



    <div class="col-sm-12">
        <div class="form-group row">            
            <div class="col-sm-2"></div>
            <div class="col-sm-9">
                <div class="col-sm-6">
                    <?php echo form_submit('submit','Search PO', 'class="btn btn-warning "');?>
                    
                    <!-- <input type="button" class="btn btn-primary btn-sm" id="search_po" value="search" onclick="search_po()" > -->
                      
                    <!-- <button type="button" class="btn btn-info" id = "cek_po" >Get PO</button> -->


                   
                    
                </div>
            </div>
        </div>
    </div>






    <div class="card table-card">
    <div class="card-header">
    <div class="card-block">
    <div class="dt-responsive table-responsive">
        <table id="multi-colum-dt" class="table table-striped table-bordered nowrap">    
            <thead>     
                <tr>    
                    <th width="1%">
                        <input type="button" class="btn btn-default btn-sm" id="toggle" value="click all" onclick="click_all_po()" >
                    </th>                                      
                    <th width="20%"><font size="2px">Nopo</font></th>                                      
                    <th width="10%"><font size="2px">Tanggal PO</font></th>                                      
                    <th width="10%"><font size="2px">Company</font></th>                                      
                    <th width="10%"><font size="2px">Tipe</font></th>                                      
                </tr>
            </thead>
            <tbody>
                <?php if(is_array($get_po)): ?>
                <?php foreach($get_po as $y) : ?>
                <tr>              
                    <td><center>
                    <input type="checkbox" id="<?php echo $y->id; ?>" name="options[]" class = "<?php echo $y->nopo; ?>" value="<?php echo $y->nopo; ?>"></center>
                    </td>              
                    <td><font size="2px"><?php echo $y->nopo; ?></td>
                    <td><font size="2px"><?php echo $y->tglpo; ?></td>
                    <td><font size="2px"><?php echo $y->company; ?></td>
                    <td><font size="2px"><?php echo $y->tipe; ?></td>
                </tr>
            <?php endforeach; ?>
            <?php endif; ?>
            </tbody>
                                
        </table>

        <!-- <input type="checkbox" id="<?php echo $b->kodeprod; ?>" name="options[]" class = "<?php echo $b->supp.$b->grup.$b->subgroup; ?>" value="<?php echo $b->kodeprod; ?>"> -->
        


    </div> 
</div>

<?php echo form_close();?>

<!-- <div class="col-sm-12">
        <div class="form-group row">
            <label class="col-sm-2 col-form-label">Keterangan Error</label>
            <div class="col-sm-9">
                <div class="col-sm-6">
                    <input class="form-control" type="text" name="nama_ticket" value="" required />
                </div>
            </div>
        </div>
    </div> -->
    





    <!-- <table id="example" class="display" style="width:100%">
    <thead>
        <tr>
            <th>
                <button style="border: none; background: transparent; font-size: 14px;" id="MyTableCheckAllButton">
                <i class="far fa-square"></i>  
                </button>
            </th>
            <th>Name</th>
            <th>Position</th>
            <th>Office</th>
            <th>Age</th>
            <th>Salary</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td></td>
            <td>Tiger Nixon</td>
            <td>System Architect</td>
            <td>Edinburgh</td>
            <td>61</td>
            <td>$320,800</td>
        </tr>
        <tr>
            <td></td>
            <td>Garrett Winters</td>
            <td>Accountant</td>
            <td>Tokyo</td>
            <td>63</td>
            <td>$170,750</td>
        </tr>
        <tr>
            <td></td>
            <td>Ashton Cox</td>
            <td>Junior Technical Author</td>
            <td>San Francisco</td>
            <td>66</td>
            <td>$86,000</td>
        </tr>
        <tr>
            <td></td>
            <td>Cedric Kelly</td>
            <td>Senior Javascript Developer</td>
            <td>Edinburgh</td>
            <td>22</td>
            <td>$433,060</td>
        </tr>
        <tr>
            <td></td>
            <td>Airi Satou</td>
            <td>Accountant</td>
            <td>Tokyo</td>
            <td>33</td>
            <td>$162,700</td>
        </tr>
        <tr>
            <td></td>
            <td>Brielle Williamson</td>
            <td>Integration Specialist</td>
            <td>New York</td>
            <td>61</td>
            <td>$372,000</td>
        </tr>
        <tr>
            <td></td>
            <td>Herrod Chandler</td>
            <td>Sales Assistant</td>
            <td>San Francisco</td>
            <td>59</td>
            <td>$137,500</td>
        </tr>
        <tr>
            <td></td>
            <td>Rhona Davidson</td>
            <td>Integration Specialist</td>
            <td>Tokyo</td>
            <td>55</td>
            <td>$327,900</td>
        </tr>
        <tr>
            <td></td>
            <td>Colleen Hurst</td>
            <td>Javascript Developer</td>
            <td>San Francisco</td>
            <td>39</td>
            <td>$205,500</td>
        </tr>
        <tr>
            <td></td>
            <td>Sonya Frost</td>
            <td>Software Engineer</td>
            <td>Edinburgh</td>
            <td>23</td>
            <td>$103,600</td>
        </tr>
        <tr>
            <td></td>
            <td>Jena Gaines</td>
            <td>Office Manager</td>
            <td>London</td>
            <td>30</td>
            <td>$90,560</td>
        </tr>
    </tbody>
    <tfoot>
        <tr>
            <th></th>
            <th>Name</th>
            <th>Position</th>
            <th>Office</th>
            <th>Age</th>
            <th>Salary</th>
        </tr>
    </tfoot>
</table> -->







    <!-- <script>
        $(document).ready(function() {
    let myTable = $('#example').DataTable({
        columnDefs: [{
            orderable: false,
            className: 'select-checkbox',
            targets: 0,
        }],
        select: {
            style: 'os', // 'single', 'multi', 'os', 'multi+shift'
            selector: 'td:first-child',
        },
        order: [
            [1, 'asc'],
        ],
    });

  myTable.on('select deselect draw', function () {
        var all = myTable.rows({ search: 'applied' }).count(); // get total count of rows
        var selectedRows = myTable.rows({ selected: true, search: 'applied' }).count(); // get total count of selected rows

        if (selectedRows < all) {
            $('#MyTableCheckAllButton i').attr('class', 'fa fa-square');
        } else {
            $('#MyTableCheckAllButton i').attr('class', 'fa fa-check-square');
        }

    });

    $('#MyTableCheckAllButton').click(function () {
        var all = myTable.rows({ search: 'applied' }).count(); // get total count of rows
        var selectedRows = myTable.rows({ selected: true, search: 'applied' }).count(); // get total count of selected rows


        if (selectedRows < all) {
            //Added search applied in case user wants the search items will be selected
            myTable.rows({ search: 'applied' }).deselect();
            myTable.rows({ search: 'applied' }).select();
        } else {
            myTable.rows({ search: 'applied' }).deselect();
        }
    });
});
</script> -->

<script>

$('#myTable').DataTable( {
    dom: 'Blfrtip',
    buttons: [
        'selectAll',
        'selectNone'
    ],
    language: {
        buttons: {
            selectAll: "Select all items",
            selectNone: "Select none"
        }
    }
} );

</script>
