<style>
    input[type=button] 
    {
        font-weight: bold;
        color: white;
        background-color: transparent;
        text-align: center;
        border: none;
    }
    td{
        font-size: 13px;
    }
    th{
        font-size: 14px; 
    }
</style>

</div>

<div class="container">

    <div class="row">
        <div class="col-md-12 az-content-label">
            <?= $title ?>
        </div>
    </div>
    </form>

    <?php echo form_open_multipart($url); ?>

    <div class="row">
        <div class="col-md-5 mt-4">     
            <label for="supp" class="form-label">Draw Your Signature</label>
            <div class="col-md-10 d-flex flex-row">
                <div id="sig"></div>
            </div>
        </div>

        <div class="col-md-10 mt-4">     
            <!-- <textarea name="signed" id="signature64"></textarea> -->
            <textarea name="signed" id="signature64" style="display: none;"></textarea>
            <button class="btn btn-info" type="submit">Save</button>
            <button id="clear" class="btn btn-dark" type="reset">Clear</button>

        </div>
    </div>
    <?= form_close();?>
</div>
<br>

<script>
      $(document).ready(function () {
        $("#example").DataTable({
            "pageLength": 100,
            // "ordering": false,
            "aLengthMenu": [
                [10, 20, 50, -1],
                [10, 20, 50, "All"]
            ],
            "fixedHeader": {
                header: true,
                footer: true
            }
        });
      });
</script>

<script src="https://cdn.datatables.net/fixedheader/3.4.0/js/dataTables.fixedHeader.min.js"></script>



<script type="text/javascript">
    var sig = $('#sig').signature({syncField: '#signature64', syncFormat: 'PNG'});
    $('#clear').click(function (e){
    //   e.preventDefault();
      sig.signature('clear');
      $("#signature64").val('');
    });
  </script>

    <script
      src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js"
      integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q"
      crossorigin="anonymous"
    ></script>
    <script
      src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js"
      integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl"
      crossorigin="anonymous"
    ></script>
