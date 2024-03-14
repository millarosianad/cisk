<link rel="stylesheet" type="text/css" href="<?php echo base_url().'assets/datatables/css/bootstrap.min.css' ?>">
<link rel="stylesheet" type="text/css" href="<?php echo base_url().'assets/datatables/css/dataTables.bootstrap.min.css' ?>">

<?php echo form_open($url);?>

<h2>
<?php echo form_label($page_title);?>
</h2>
<hr />
<a href="<?php echo base_url()."piutang/emailPiutang/". $key."/".$grup_lang."/".$tanggal."/"; ?>  " class="btn btn-primary" role="button"><span class="glyphicon glyphicon-envelope" aria-hidden="true"></span> email</a>
       
<hr />


<?php echo form_close();?>
<!-- Load SCRIPT.JS which will create datepicker for input field  -->
</div>
<?php $no = 1; ?>
<div class="row">
    <div class="col-xs-12">
        <div class="col-xs-12">
            <table id="example" class="table table table-striped table-bordered table-hover" style="width:100%">
            <thead>
                <tr>                
                    <th width="1"><font size="2px">No</font></th>
                    <th><font size="2px">Nomor Faktur</th>
                    <th><font size="2px">No DO</th>
                    <th><font size="2px">Tanggal</th>
                    <th><font size="2px">Tanggal Jatuh Tempo</th>
                    <th id='nilai'><font size="2px">Nilai</th>
                </tr>
            </thead>
            <tbody>
            <?php foreach($query as $q) : ?>
                <tr>
                    <td><center><font size="3px"><?php echo $no++; ?></font></center></td>               
                    <td><font size="3px"><?php echo $q->nodokjdi; ?></td>
                    <td><font size="3px"><?php echo $q->nodokacu; ?></td>
                    <td><font size="3px"><?php echo $q->tgldokjdi; ?></td>
                    <td><font size="3px"><?php echo $q->tgl_jtempo; ?></td>
                    <td><?php echo number_format($q->nilai); ?></td>
                </tr>
            <?php endforeach; ?>
            </tbody>
            <tfoot>
                <tr>
                    <th colspan="5" style="text-align:right">Total:</th>
                    <th></th>
                </tr>
            </tfoot>
            </table>
        </div>
    </div>
</div>

<script type="text/javascript" src="<?php echo base_url('assets/jquery.js') ?>"></script>
<script type="text/javascript" src="<?php echo base_url('assets/datatables/media/js/jquery.dataTables.min.js') ?>"></script>
<script type="text/javascript" src="<?php echo base_url('assets/datatables/media/js/dataTables.bootstrap.min.js') ?>"></script>

<script>
        $(document).ready(function() {
        $('#example').DataTable( {
            


            "ordering": false,
            "footerCallback": function ( row, data, start, end, display ) {
            var api = this.api(), data;
 
            // Remove the formatting to get integer data for summation
            var intVal = function ( i ) {
                return typeof i === 'string' ?
                    i.replace(/[\$,]/g, '')*1 :
                    typeof i === 'number' ?
                        i : 0;
            };
 
            // Total over all pages
            total = api
                .column( 5 )
                .data()
                .reduce( function (a, b) {
                    return intVal(a) + intVal(b);
                }, 0 );
 
            // Total over this page
            pageTotal = api
                .column( 5, { page: 'current'} )
                .data()
                .reduce( function (a, b) {
                    return intVal(a) + intVal(b);
                }, 0 );
 
            // Update footer
            $( api.column( 5 ).footer() ).html(
                'Rp '+ total
            );
        }
    } );
} );

function commaSeparateNumber(val) {
    while (/(\d+)(\d{3})/.test(val.toString())) {
        val = val.toString().replace(/(\d+)(\d{3})/, '$1' + ',' + '$2');
    }
    return val;
}

        </script>