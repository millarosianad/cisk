

<?php 
echo form_open('retur/register_signature_proses');
?>
<div class="card">
    
<div class="col-lg-12">
        <div class="form-group row">
            <label class="col-lg-2 col-form-label">Signature</label>
            <div class="col-lg-9">
                <div id="sig"></div>
            </div>
        </div>
    </div>

    <div class="col-lg-12">
        <div class="form-group row">
            <label class="col-lg-2 col-form-label"></label>
            <div class="col-lg-9">
                <textarea name="signed" id="signature64" style="display: none;"></textarea>
                <!-- <textarea name="signed" id="signature64"></textarea> -->
                <button id="clear" class="btn btn-dark" type="reset">Clear</button>
                <button class="btn btn-success" type="submit">Save</button>
            </div>
        </div>
    </div>
</div> 
</form>

<script type="text/javascript" src="<?= base_url(); ?>assets/js/jquery.ui.touch-punch.min.js"></script>


<script type="text/javascript">
    var sig = $('#sig').signature({syncField: '#signature64', syncFormat: 'PNG'});
    $('#clear').click(function (e){
      e.preventDefault();
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