
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
                    <?php echo form_submit('submit','Search PO', 'class="btn btn-warning"');?>
                    
                    <!-- <input type="button" class="btn btn-primary btn-sm" id="search_po" value="search" onclick="search_po()" > -->
                      
                    <!-- <button type="button" class="btn btn-info" id = "cek_po" >Get PO</button> -->


                   
                    
                </div>
            </div>
        </div>
    </div>


    <?php echo form_close();?>

    <?php echo form_open($url2); ?>

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
                    <input type="checkbox" id="<?php echo $y->id; ?>" name="options[]" class = "<?php echo $y->nopo; ?>" value="<?php echo $y->id; ?>"></center>
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


    </div> 
</div>



    </div>

<div class="card">
    <div class="col-sm-12">
        <div class="form-group row">
            <label class="col-sm-2 col-form-label">Description</label>
            <div class="col-sm-9">
                <div class="col-sm-6">
                    <!-- <input class="form-control" type="text" name="error" required /> -->
                    <textarea name="deskripsi" cols="30" rows="3" class = "form-control" required></textarea>
                </div>
            </div>
        </div>
    </div>

    <div class="col-sm-12">
        <div class="form-group row">            
            <div class="col-sm-2"></div>
            <div class="col-sm-9">
                <div class="col-sm-6">
                    <?php echo form_submit('submit','Proses Ticket', 'onclick="return ValidateCompare_po();" class="btn btn-primary"');?>
                                        
                </div>
            </div>
        </div>
    </div>

    <?php echo form_close();?>
    
