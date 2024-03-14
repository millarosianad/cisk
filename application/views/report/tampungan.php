case 'showsales':
            {
               $data['url']=site_url('report/analisa/switcher');
               $data['query']=$this->rmodel->list_product();
            }break;
            case 'switcher':
            {
                switch($this->input->post('pilih'))
                {
                    case 'dp':  $this->analisa('dp');break;
                    case 'bsp': $this->analisa('bsp');break;
                }
            }break;



/*
    
    case 'showoutlet':
    {
        
    }break;
    case 'outlet':
    {
        
    }break;


 <?php
            echo form_label("PRODUCT : ");
            
            $supp='';
            $count=0;
            $pindah=1;
            ?>
            <!--input type="checkbox" name="checkall" onclick="checkUncheckAll(this);"/>PILIH / HAPUS SEMUA PRODUK-->
            <div id='accordion'>
            <?php
            foreach($query->result() as $value)
            {
                if($supp!=$value->supp)
                {
                    if($count>0)
                    {
                        ?>
                        </tr></table></div>
                        <?php
                    }?>
                    <h3><?php echo strtoupper($value->namasupp);$pindah=1?></h3>
                    <div><table><tr>
                    <?php $count++;
                }
                if($pindah%10!=0)
                {
                    ?>
                    <td>
                    <INPUT NAME="options[]" TYPE="CHECKBOX" id="options" VALUE="<?php echo $value->kodeprod ?>"><?php echo $value->namaprod?>
                    </td>
                    <?php 
                } 
                else
                {
                    ?>
                    <td>
                    <INPUT NAME="options[]" TYPE="CHECKBOX" id="options" VALUE="<?php echo $value->kodeprod ?>"><?php echo $value->namaprod?>
                    </td>
                    </tr><tr>
                    <?php 
                }
                ?>
                <?php $supp=$value->supp;$pindah++;
            }
            ?>
            </tr></table></div>
            </div> 