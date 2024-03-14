<table class="table table-bordered table-striped" >
 
<thead> 
 
<tr>   
 
<th >namakolom1</th>
 
 
<th >namakolom2</th>
 
  </tr>
</thead>
 
 
<tbody class=" table-hover"> 
  <?php if(!empty($posts)): foreach($posts as $post): ?>
 
<tr>
 
<td><?php echo $post["namakolom1"];?></td>
 
 
<td><?php echo $post["namakolom2"];?></td
     
  </tr>
      <?php } $n++; endforeach; else: ?>
              
 
<tr>
 
<td colspan="2">
 
 
</td>
 
                </tr>
 
            <?php endif; ?>
             </tbody>
 
</table>