<?php echo form_open($url);?>
<?php echo form_fieldset($page_title);?>
<?php
if(isset($query))
{
    echo '<table class="table"><tr>';
    $count=1;
    $flag=0;
    $gr='';
    foreach($query->result() as $row)
    {
        if($gr!=$row->groupname)
        {
            $col=6-($count%6);
            //$supp=$value->supp;
            $gr=$row->groupname;
            echo '<tr><td><h4>'.$gr.'</h4></td></tr><tr>';
            $count=1;
            $flag=1;
        }
        if($count%6!=0)
        {
            echo '<td>';
         
            if($row->userid!=null)
            {
                echo form_checkbox('menu[]', $row->id,true).' '.$row->menuview;
            }
            else
            {
                echo form_checkbox('menu[]', $row->id,false).' '.$row->menuview;
            }
            ?>
        </td>

        <?php
        }
        else
        {
        ?>
            <td>
                <?php 
                if($row->userid!=null)
                {
                    echo form_checkbox('menu[]', $row->id,true).' '.$row->menuview;
                }
                else
                {
                    echo form_checkbox('menu[]', $row->id,false).' '.$row->menuview;
                }?>
            </td>
        <?php
            echo '</tr><tr>';
        }
        $count++;
    }
    echo '</tr></table>';

    $query->free_result();
}

?>
<br/><br/>
    <input type="checkbox" name="checkall" onclick="checkUncheckAll(this);"/>Select / Unselect All
<br/><br/>
<?php echo form_submit('submit','SUBMIT','class="btn btn-primary"').'&nbsp&nbsp'?>
<?php echo form_reset('reset','RESET','class="btn btn-default"')?><br/><br/>

<?php echo form_fieldset_close();?>
<?php echo form_close();?>