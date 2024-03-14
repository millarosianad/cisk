<div class="card">
    <div class="card-header">
        <h5><?= $title; ?></h5>
    </div>

    <div class="card-block">
        <?php echo form_open_multipart($url); ?>
        <div class="row">        
            <div class="col-md-12">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="productid">Product ID</label>
                            <input type="text" class="form-control" id="productid" name="productid" value="<?= $get_product->row()->productid; ?>">
                            <small id="emailHelp" class="form-text text-muted">tipe data dapat berubah string ataupun angka</small>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="nama_product">Nama Product</label>
                            <input type="text" class="form-control" name="nama_product" value="<?= $get_product->row()->nama_product; ?>">
                        </div> 
                    </div>
                </div>   
                
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="harga">Harga</label>
                            <input type="text" class="form-control" name="harga" value="<?= $get_product->row()->harga; ?>">
                        </div> 
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="discount">Discount</label>
                            <input type="text" class="form-control" name="discount" value="<?= $get_product->row()->discount; ?>">
                        </div> 
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="satuan1">Satuan Besar / Sedang</label>
                            <input type="text" class="form-control" name="satuan1" value="<?= $get_product->row()->satuan1; ?>">
                        </div>   
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="unit1">Quantity (1)</label>
                            <input type="text" class="form-control" name="unit1" value="<?= $get_product->row()->unit1; ?>">
                        </div>  
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="satuan2">Satuan Kecil</label>
                            <input type="text" class="form-control" name="satuan2" value="<?= $get_product->row()->satuan2; ?>">
                        </div>   
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="unit2">Quantity (2)</label>
                            <input type="text" class="form-control" name="unit2" value="<?= $get_product->row()->unit2; ?>">
                            <input type="hidden" name="signature" value="<?= $signature; ?>">
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        
                        <?php echo form_submit('submit', 'Update Product', 'class="btn btn-primary"'); ?>
                        <?php echo form_close(); ?>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            &nbsp; 
                        </div>  
                    </div>
                </div>
            </div>                                   
        </div>
    </div>

    <div class="card-block">
    
        <div class="row">
            <div class="col-md-12">

               <table id="example" class="table table-striped table-bordered" style="display: inline-block; overflow-y: scroll">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Product ID</th>
                            <th width="100%">Nama Product</th>
                            <th>Harga</th>
                            <th>Discount</th>
                            <th>Satuan 1</th>
                            <th>Unit 1</th>
                            <th>Satuan 2</th>
                            <th>Unit 2</th>
                            <th><center>#</center></th>
                        </tr>
                    </thead>
                    <tbody>     
                        <?php 
                        $no = 1;
                        foreach ($get_product->result() as $a) : ?>
                        <tr>
                            <td><?= $no++; ?></td>
                            <td><?= $a->productid; ?></td>
                            <td><?= $a->nama_product; ?></td>
                            <td><?= $a->harga; ?></td>
                            <td><?= $a->discount; ?></td>
                            <td><?= $a->satuan1; ?></td>
                            <td><?= $a->unit1; ?></td>
                            <td><?= $a->satuan2; ?></td>
                            <td><?= $a->unit2; ?></td>
                            <td>
                            <a href="<?= base_url().'mes/product_edit/'.$a->signature ?>" class="btn btn-warning">edit</a>
                            <a href="<?= base_url().'mes/product_delete/'.$a->signature ?>" class="btn btn-danger">delete</a>

                            </td>
                        </tr>
                        <?php endforeach; ?>   
                    </tbody>
                </table>

            </div>
        </div>

    </div>


</div>



<script>
    $(document).ready(function () {
        $('#example').DataTable();
    });
</script>