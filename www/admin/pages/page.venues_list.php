<?php

if(@$_GET['action']=='delete'){
    venue::delete(@$_GET['deleteid']);
} 

 $s = new venue_search();
 $s->num_results(100);
 $s->order('name');
 
 $paging_base_url = '?p=venues_list';
 $glue = '&';
 
 
 
 
 if(@$_GET['lat']&@$_GET['lng']){
     $s->set_location($_GET['lat'],$_GET['lng']);
     $paging_base_url.= $glue.'lat='.$_GET['lat'].'&lng='.$_GET['lng'];
     $glue = '&';
 }
 if(@$_GET['radio']){
     $s->set_radius ($_GET['radio']);
     $paging_base_url.=$glue.'radio='.$_GET['radio'];
     $glue = '&';
 }
 if(@$_GET['category_id']){
     $s->set_category ($_GET['category_id']);
     $paging_base_url.=$glue.'category_id='.$_GET['category_id'];
     $glue = '&';
 }
 
 if(@$_GET['q']){
     $s->add_keyword ($_GET['q']);
     $paging_base_url.=$glue.'q='.$_GET['q'];
     $glue = '&';
 }
 
 
 if(@$_GET['page']){
     $s->page($_GET['page']);
 }
 
 
 $count = $s->get_result_count();
 $list = $s->get_list();

 
 
?>

 
 <div class="gb13_inhalte">

		
		<?php echo $count;?> resultados
                <div class="paging">
               <?php 
                    $pipe = '';
                    $num_pages = $s->get_num_pages();
                    
                    for($i=1;$i<=$num_pages;$i++){
                            
                            echo $pipe;
                            $pipe = '|';
                            if(@$_GET['page']==$i){
                                echo ' &nbsp; <b>'.$i.'</b> &nbsp';
                            }else{
                                echo ' &nbsp;  <a href="'.$paging_base_url.$glue.'page='.$i.'">'.$i.'</a> &nbsp; ';
                            }
                    }
               ?>
            </div>
                <table  width="100%">
                <tr style="font-weight: bold">
                    <td>#</td>
                    <td>Nombre</td>
                    <td>Direccion</td>
                    <td>Contacto</td>
                    <td>&nbsp;</td>
                    
                
                </tr>

                <?php 
                $i = 0;
                
                foreach($list as $entry){ 

                $i++;
                ?>
                <tr>
                    <td><?php echo (($s->page()-1)*$s->num_results())+$i;?></td>
                    <td><a href="?p=venue_profile&id=<?php echo $entry['id']; ?>"><?php echo $entry['name']; ?></td>
                    <td><?php echo $entry['address']; ?></td>
                    <td><?php 
                    
                        $contact = venue::parse_contact_data($entry['contact']); 
                        foreach($contact as $key=>$value) echo '<br>'.$key.': '.$value;
               
                    
                    ?></td>
                    <td><input type="button" value="Borrar" onclick="if(confirm('Esta seguro que desea borrar este local?'))window.location='<?php echo $paging_base_url.$glue.'page='.@$_GET['page'].$glue.'action=delete&deleteid='.$entry['id']; ?>'"></td>
               
                </tr>
                <tr><td colspan="5"><hr></td></tr>
                
                <?php } ?>           


        </table>
        
                    
       
         
    </div>
 



