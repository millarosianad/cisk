<script type="text/javascript">
  $(document).ready(function() {
    $("#datepicker").datepicker({
        dateFormat:"yy-mm-dd",
        changeYear:true,
        changeMonth:true
    });
  });
</script>
<?php
    echo br(2);
    echo form_open($uri);
    echo form_input('keyword',$keyword,'id="datepicker"');
    echo form_submit('search','SEARCH');
    echo form_close();
    echo br(2);
    echo $pagination;
    echo $query;
    echo $pagination;
?>