<a href="<?php echo base_url()."ecommerce/otp_user"; ?>" type="button" class="btn btn-md btn-dark">kembali</a>
<a href="<?php echo base_url()."ecommerce/export_csv/log_otp"; ?>" type="button" class="btn btn-md btn-success">Export (.csv)</a>
   
<hr>

<div class="col-12">
    <div class="dt-responsive table-responsive">
        <!-- <table id="multi-colum-dt" class="table table-striped table-bordered nowrap"> -->
        <table id="multi-colum-dt" class="table table-striped table-bordered nowrap">
            <thead>
                <tr>
                    <th width="1%"><font size="2px">branch</th>
                    <th width="1%"><font size="2px">subbranch</th>
                    <th width="1%"><font size="2px">customer code</th>
                    <th width="1%"><font size="2px">telp</th>
                    <th width="1%"><font size="2px">type</th>
                    <th width="1%"><font size="2px">otp code</th>
                    <th width="1%"><font size="2px">created at</th>
                    <th width="1%"><font size="2px">valid until</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($get_log_otp as $key) : ?>
                <tr>    
                    <td><?= $key->branch_name; ?></td>
                    <td><?= $key->nama_comp; ?></td>
                    <td><?= $key->customer_code; ?></td>
                    <td><?= $key->email_phone; ?></td>
                    <td><?= $key->type; ?></td>
                    <td><?= $key->otp_code; ?></td>
                    <td><?= $key->created_at; ?></td>
                    <td><?= $key->valid_until; ?></td>
                </tr>
                <?php endforeach; ?>
                </tbody> 
        </table>
    </div>
</div>
