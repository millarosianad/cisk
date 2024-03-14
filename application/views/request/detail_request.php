<?php echo form_open($url); ?>
<div class="col-xs-16">
    <a href="<?php echo base_url()."request/history"; ?>  " class="btn btn-primary btn-md" role="button"><span class="glyphicon glyphicon-plus" aria-hidden="true"></span> kembali</a> 
    <hr>

</div>
<div class="card table-card">
        <div class="card-header">
        <div class="card-block">
        <div class="dt-responsive table-responsive">
            <table id="multi-colum-dt" class="table table-striped table-bordered nowrap">    
                <thead>
                    <tr>                
                        <!-- <th width="1"><font size="2px">no</th> -->
                        <th width="1"><font size="1px"><input type="button" class="btn btn-default btn-sm" id="toggle" value="click all" onclick="click_all_request()" ></th>
                        
                        <th><font size="2px">Status</th>
                        <th><font size="2px">Customerid</th>
                        <th><font size="2px">NamaCustomer</th>
                        <th><font size="2px">Segment</th>
                        <th><font size="2px">Type</th>
                        <th><font size="2px">Class</th>
                        
                    </tr>
                </thead>
                <tbody>
                <?php 
                $no = 1;
                foreach($request as $a) : ?>
                    <tr>                      
                        <!-- <td><font size="2px"><?php echo $no++; ?></td> -->
                        <td>
                        <center>
                            <input type="checkbox" id="<?php echo $a->no_request; ?>" name="options[]" class = "<?php echo $a->id; ?>" value="<?php echo $a->no_request; ?>"><?php echo "no_request : ".$a->no_request; ?>
                        </center>
                        </td>    
                        <td><?php                         
                            if($a->status_approval == '0'){ ?>
                                <font size="2" color="danger"><?php
                                echo "rejected";
                            }elseif($a->status_approval == '1'){ ?></font>
                                <font size="2" color="blue">
                                <?php
                                    echo "approved";
                            }else{ ?> <font size="2" color="grey"><?php
                                echo "pending";
                            }?></font>
                        </td>
                                         
                        <td><font size="2px"><?php echo $a->customerid; ?></td>                      
                        <td><font size="2px"><?php echo $a->nama_customer; ?></td>  
                        <td><font size="2px">
                            <?php echo $a->segmentid_current; ?> ->
                            <?php echo $a->segmentid_request; ?>
                        </td>                     
                        <td><font size="2px">
                            <?php echo $a->typeid_current; ?> ->
                            <?php echo $a->typeid_request; ?>
                        </td>                                
                        <td><font size="2px">
                            <?php echo $a->classid_current; ?> ->
                            <?php echo $a->classid_request; ?>
                        </td>                                
                                         
                    </tr>
                <?php endforeach; ?>
                
                </tbody>
               
            </table>
        </div>
        <div class="col-xs-11">&nbsp; </div>
        </div>
</div>

<div class="card">
    <div class="col-sm-12">
        <div class="form-group row">
            <label class="col-sm-2 col-form-label">Catatan (*)</label>
            <div class="col-sm-9">
                <div class="col-sm-6">
                    <textarea name="note" cols="30" rows="3" class = "form-control" required></textarea>
                </div>
            </div>
        </div>
    </div>

    <div class="col-sm-12">
        <div class="form-group row">            
            <div class="col-sm-2"></div>
            <div class="col-sm-9">
                <div class="col-sm-6">
                    <?php 
                        $uri_id = $this->uri->segment('3');
                        // echo $uri_id;
                    ?>
                    <input type="hidden" value=<?php echo $uri_id; ?> name = "id_log">

                    

                    <button type="submit" class="btn btn-primary" name="status_approve" value="1" onclick="return confirm('Are you sure you want approve?');">Approve</button>
                    <button type="submit" class="btn btn-danger" name="status_approve" value="0" onclick="return confirm('Are you sure you want reject?');">Reject</button>
                                        
                </div>
            </div>
        </div>
    </div>

    <?php echo form_close();?>

     
