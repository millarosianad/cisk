<div class="col-xs-16">
    <a href="<?php echo base_url()."assets_2/pengajuan_assets"; ?>  " class="btn btn-primary" role="button"><span class="glyphicon glyphicon-plus" aria-hidden="true"></span> Tambah</a>
    <hr />
</div>
<div class="card table-card">
        <div class="card-header">
        <div class="card-block">
        <div class="dt-responsive table-responsive">
            <table id="multi-colum-dt" class="table table-striped table-bordered nowrap">    
                    <thead>
                        <tr>                
                            <th width="1"><font size="2px">No PO</th>
                            <th><font size="2px">Nama Toko</th>
                            <th><font size="2px">Alamat</th>
                            <th><font size="2px">No. Telpon</th>
                            <th><font size="2px">Tanggal Pengajuan</th>
                            <th><font size="2px">Harga</th>
                            <th><font size="2px">Tax</th>
                            <th><font size="2px">Total</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php foreach($proses as $a) : ?>
                        <tr>                      
                            <td><font size="2px"><?php echo $a->no_po; ?></td>
                            <td><font size="2px"><?php echo $a->nama_toko; ?></td>
                            <td><font size="2px"><?php echo $a->alamat; ?></td>
                            <td><font size="2px"><?php echo $a->telp; ?></td>
                            <td><font size="2px"><?php echo date('d-m-Y', strtotime($a->tgl_po)); ?></td>
                            <td><font size="2px"><?php echo number_format($a->sub_harga); ?></td>
                            <td><font size="2px"><?php echo number_format($a->sub_tax); ?></td>
                            <td><font size="2px"><?php echo number_format($a->total); ?></td>
                            <td>
                            <a href="<?php echo base_url()."assets/file/bukti_permintaan/".$a->upload_req; ?>"class="btn btn-info btn-sm" role="button" target="_blank">View</a>
                            <a href="<?php echo base_url()."assets_2/pengajuan_pdf/".$a->no_po; ?>"class="btn btn-danger btn-sm fa fa-file" role="button" target="_blank"> Pdf 1</a>
                            <a href="<?php echo base_url()."assets_2/pengajuan_pdf_2/".$a->no_po; ?>"class="btn btn-danger btn-sm fa fa-file" role="button" target="_blank"> Pdf 2</a>
                        </td>
                        </td>
                        </tr>
                    <?php endforeach; ?>                    
                    </tbody>
                    <tfoot>
                            <tr>                
                            <th width="1"><font size="2px">No PO</th>
                            <th><font size="2px">Nama Toko</th>
                            <th><font size="2px">Alamat</th>
                            <th><font size="2px">No. Telpon</th>
                            <th><font size="2px">Tanggal Pengajuan</th>
                            <th><font size="2px">Harga</th>
                            <th><font size="2px">Tax</th>
                            <th><font size="2px">Total</th>
                            <th></th>
                            </tr>
                    </tfoot>
                    </table>
                    </div>
        </div>
    </div>