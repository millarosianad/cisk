<?php echo form_open_multipart('inventory/save_import_ms_setting_product');?>
<input type="hidden" name="created_at" value="<?php echo $created_at; ?>">
<input type="hidden" name="created_by" value="<?php echo $created_by; ?>">

<?php echo form_submit('submit','save','class="btn btn-warning btn-md"');?>
<?php echo form_close();?>
<div class="card-block">
    <div class="dt-responsive table-responsive">
        <table id="multi-colum-dt" class="table table-striped table-bordered nowrap">
            <thead>
                <tr>
                    <th width="1"><font size="1px">Kodeprod</th>
                    <th width="1"><font size="1px">Namaprod</th>
                    <th width="1"><font size="1px">Status</th>
                    <th width="1"><font size="1px">Created at</th>
                    <th width="1"><font size="1px">Created by</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($get_import_ms as $a) : ?>
                <tr>
                    <td><font size="1px"><?php echo $a->kodeprod; ?></td>
                    <td><font size="1px"><?php echo $a->namaprod; ?></td>
                    <td><font size="1px"><?php echo $a->status; ?></td>
                    <td><font size="1px"><?php echo $a->created_at; ?></td>
                    <td><font size="1px"><?php echo $a->created_by; ?></td>
                </tr>

                <?php endforeach; ?>

            </tbody>
        </table>
    </div>
    
</div>